<?php

namespace App\Http\Controllers\APIs\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Mail\NewRegisterMail;
use App\Mail\LostPasswordMail;
use App\Models\AccessVerifyUser;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'register_verify', 'lost_password', 'lost_password_verify', 'password_renew']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|string|max:255',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(errorResponse($validator->errors()), 202);
        }

        $credentials = request(['email', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(errorResponse('Account not found !'), 202);
        }
        if (Auth::user()->email_verified_at) {
            return $this->respondWithToken($token);
        }
        return response()->json(errorResponse('You must verify your account first, please check your email !'), 202);
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users',
            'password' => 'required|string',
            'repassword' => 'required|string|same:password'
        ], [
            'email.unique' => 'Account has been registered, if you forget your password, please click forgot password.'
        ]);
        if ($validator->fails()) {
            return response()->json(errorResponse($validator->errors()), 202);
        }
        // die;
        $newUser = User::create([
            'name' => ucwords(request()->name),
            'email' => request()->email,
            'password' => Hash::make(request()->password),
            'code' => createNewUserCode()
        ]);
        if ($newUser) {
            $newAccessToken = createAccessTokenUser();
            AccessVerifyUser::create(['email' => request()->email, 'type' => 'newregister', 'access' => $newAccessToken]);
            Mail::to(request()->email)->send((new NewRegisterMail)->markdown('emails.register.newregister', ['url' => url("/api/auth/register/verify?_access=$newAccessToken")]));
            return response()->json(successResponse('Successfully registered new user.'), 201);
        }
        return response()->json(errorResponse('Failed to register new user.'), 202);
    }

    /**
     * verify new register by email
     *
     * @return void
     */
    public function register_verify()
    {
        $validator = Validator::make(request()->all(), [
            '_access' => 'required|alpha_num|min:32|max:32'
        ], [
            '_access.*' => "Oops.. we could not verify this request."
        ]);
        if ($validator->fails()) {
            return redirect("/signin?_colortype=danger&_message=Oops.. we could not verify this request.");
        }
        $getAccess = AccessVerifyUser::where('access', request()->_access)->first();
        if ($getAccess) {
            if ($getAccess['type'] == 'newregister') {
                User::where('email', $getAccess['email'])->update(['email_verified_at' => Carbon_DBtimeNow()]);
                AccessVerifyUser::where('access', $getAccess['access'])->delete();
                return redirect('/signin?_colortype=success&_message=Your account has been successfully verified.');
            }
        }
        return redirect('/signin?_colortype=danger&_message=Sorry.. we could not verify this request.');
    }

    /**
     * create email forget password and send to user
     *
     * @return void
     */
    public function lost_password()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(errorResponse($validator->errors()), 202);
        }
        $getUser = User::where('email', request()->email)->first();
        if ($getUser) {
            $newAccessToken = createAccessTokenUser();
            AccessVerifyUser::create(['email' => request()->email, 'type' => 'lostpassword', 'access' => $newAccessToken]);
            Mail::to(request()->email)->send((new LostPasswordMail)->markdown('emails.register.lostpassword', ['url' => url('/api/auth/signin/lost/verify?_access=' . $newAccessToken)]));
        }
        return response()->json(successResponse('Your request has been sent, please check your email.'), 200);
    }

    /**
     * user open email and open the access code
     * if access code exist then redirect to renew password page
     *
     * @return void
     */
    public function lost_password_verify()
    {
        $validator = Validator::make(request()->all(), [
            '_access' => 'required|alpha_num|min:32|max:32'
        ], [
            '_access.*' => "Oops.. we could not verify this request."
        ]);
        if ($validator->fails()) {
            // return response()->json(errorResponse($validator->errors()), 202); -> for REST API purpose.
            return redirect("/signin?_colortype=danger&_message=Oops.. we could not verify this request.");
        }
        $getAccess = AccessVerifyUser::where('access', request()->_access)->first();
        if ($getAccess) {
            if ($getAccess['type'] == 'lostpassword') {
                // open form for renew password
                return redirect('/signin/renew-password?_access=' . $getAccess['access']);
            }
        }
        // return response()->json(errorResponse("Sorry.. we could not verify this request."), 202); -> for REST API purpose.
        return redirect("/signin?_colortype=danger&_message=Sorry.. we could not verify this request.");
    }

    /**
     * system do renew password after user update password
     *
     * @return void
     */
    public function password_renew()
    {
        $validator = Validator::make(request()->all(), [
            'password' => 'required|string',
            'repassword' => 'required|string|same:password',
            'access' => 'required|alpha_num|min:32|max:32'
        ]);
        if ($validator->fails()) {
            return response()->json(errorResponse("Oops.. we could not verify this request."), 202);
        }
        $getAccess = AccessVerifyUser::where('access', request()->access)->first();
        if ($getAccess) {
            if ($getAccess['type'] == 'lostpassword') {
                User::where('email', $getAccess['email'])->update(['password' => Hash::make(request()->password)]);
                AccessVerifyUser::where('access', $getAccess['access'])->delete();
                return response()->json(successResponse('Password updated successfully.'), 201);
            }
        }
        return response()->json(errorResponse('Well.. this access has expired.'), 202);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(successResponse('', ['credentials' => Auth::user()->name]), 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(successResponse('Successfully logged out'), 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json(successResponse('Authorization', [
            'account_name' => Auth::user()->name,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]));
    }
}
