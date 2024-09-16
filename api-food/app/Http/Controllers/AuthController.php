<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Authenticates a user and returns a JWT.
     * 
     * This method validates the user credentials and generates a JWT for
     * authenticated users. If the credentials are invalid, it returns a 401 error.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Returns the JWT response including the token and its expiration details.
     * 
     * This method returns the JWT access token, the token type, and its expiration
     * time in seconds.
     * 
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     * 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
        ]);
    }
}
