<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6'
        ]);
        
        if (!$token = auth()->attempt($data)) {
            throw new \Exception('Unauthorized', 403);
        }
        
        /** @var User $user */
        $user = User::query()->firstWhere('email', $data['email']);
        $user->refresh_token = Str::uuid();
        $user->saveQuietly();
        
        return $this->respondWithSuccessJwt($user, $token);
    }
    
    public function logout(): Response
    {
        auth()->logout();
        
        return response('', 200);
    }
    
    public function refresh(Request $request): JsonResponse
    {
        $data = $request->validate([
            'refresh_token' => 'required|string'
        ]);
        
        /** @var User $user */
        $user = User::query()->firstWhere('refresh_token', $data['refresh_token']);
        
        if ($user?->refresh_token != $data['refresh_token']) {
            throw new \Exception('Invalid refresh token', 403);
        }
        
        /** @var JWTGuard $guard */
        $guard = auth()->guard();
        
        try {
            $accessToken = $guard->refresh(true, true);
            $user->refresh_token = Str::uuid();
            $user->save();
        } catch (\Exception $e) {
            throw $e;
        }
        
        return $this->respondWithSuccessJwt($user, $accessToken);
    }
    
    public function me(): Authenticatable
    {
        return auth()->guard()->user();
    }
    
    private function respondWithSuccessJwt(User $user, string $token): JsonResponse
    {
        return response()->json([
            'user' => $user,
            'auth' => [
                'type' => 'Bearer',
                'access_token' => $token,
                'refresh_token' => $user->refresh_token,
            ]
        ])
            ->withCookie(Cookie::make('tkn', $token, 10, '/', 'api.local:8000', false, false, sameSite: 'none'));
    }
    
}
