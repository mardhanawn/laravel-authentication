<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthControllers extends Controller
{
    public function show() {
        $user = auth()->user();
        return response([
            'name' => $user->name,
            'email'=>$user->email
        ]);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }
        return response()->json(['error' => 'Invalid Login Details'], 401);    
    }

    public function register(Request $request)
    {
        $user = User::create([
             'name' => $request->name,
             'email'    => $request->email,
             'password' => bcrypt($request->password),
         ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function logout() {
        $this->guard()->logout();
        return response()->json(['message' => 'Successfully logged out']);    
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ]);
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
