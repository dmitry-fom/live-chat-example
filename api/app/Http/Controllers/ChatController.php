<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(public ChatService $chat)
    {}
    
    public function send(Request $request, int $receiverId): JsonResponse
    {
        ['message' => $message] = $request->validate([
            'message' => 'required|string|min:1'
        ]);
        
        $message = $this->chat->sendMessage(auth()->user()->id, $receiverId, $message);
        
        return response()->json($message);
    }
    
    public function getChat(int $receiverId): JsonResponse
    {
        return response()->json(
            $this->chat->retrieveMessages(auth()->user()->id, $receiverId)
        );
    }
}
