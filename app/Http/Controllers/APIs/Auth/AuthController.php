<?php

namespace App\Http\Controllers\APIs\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
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
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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
        return $this->respondWithToken($token);
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
            return response()->json(successResponse('Successfully registered new user.'), 201);
        }
        return response()->json(errorResponse('Failed to register new user.'), 202);
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
