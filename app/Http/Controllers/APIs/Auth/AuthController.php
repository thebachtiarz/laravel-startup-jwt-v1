<?php

namespace App\Http\Controllers\APIs\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Mail\NewRegisterMail;
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
        $this->middleware('auth:api', ['except' => ['login', 'register', 'register_verify']]);
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

    public function register_verify() //verify new register by email
    {
        $validator = Validator::make(request()->all(), [
            '_access' => 'required|string|min:32|max:32'
        ], [
            '_access.*' => "Oops... we couldn't verify this request."
        ]);
        if ($validator->fails()) {
            // return response()->json(errorResponse($validator->errors()), 202); -> for REST API purpose.
            return redirect("/signin?_colortype=danger&_message=Oops... we couldn't verify this request.");
        }
        $getAccess = AccessVerifyUser::where('access', request()->_access)->first();
        if ($getAccess) {
            // return $getAccess;
            if ($getAccess['type'] == 'newregister') {
                User::where('email', $getAccess['email'])->update(['email_verified_at' => Carbon_DBtimeNow()]);
                AccessVerifyUser::where('access', $getAccess['access'])->delete();
                return redirect("/signin?_colortype=success&_message=Your account has been successfully verified.");
            }
        }
        // return response()->json(errorResponse("Sorry... we couldn't verify this request."), 202); -> for REST API purpose.
        return redirect("/signin?_colortype=danger&_message=Sorry... we couldn't verify this request.");
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
