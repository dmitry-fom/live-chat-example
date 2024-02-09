<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUsersToChat(): JsonResponse
    {
        return response()->json(
            DB::table('users')
                ->select('id', 'name')
                ->whereNot('id', auth()->user()->id)
                ->get()
        );
    }
    
    public function getUser(int $id): JsonResponse
    {
        return response()->json(
            User::find($id)
        );
    }
}
