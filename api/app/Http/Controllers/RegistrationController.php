<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class RegistrationController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'password' => 'required|confirmed|min:8',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|min:3|max:15'
        ]);
        
        $data['password'] = Hash::make($data['password']);
    
        DB::beginTransaction();
        try {
            /** @var User $user */
            $user = User::query()->create($data);
    
            $user->sendEmailVerificationNotification();
            
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            
            throw $e;
        }
        
        return response()->json($user);
    }
    
    public function verify(int $userId)
    {
        /** @var User $user */
        if ($user = User::query()->find($userId)) {
            $user->markEmailAsVerified();
        }
        
        return response()->json();
    }
}
