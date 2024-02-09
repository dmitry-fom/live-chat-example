<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Collections\MessageCollection;
use App\Models\Message;

class ChatService
{
    public function sendMessage(int $senderId, int $receiverId, string $text): Message
    {
        $message = new Message();
        $message->receiver_id = $receiverId;
        $message->sender_id = $senderId;
        $message->text = $text;
        $message->save();
    
        MessageSent::broadcast($message)->toOthers();
        
        return $message;
    }
    
    public function retrieveMessages(int $user1, int $user2): MessageCollection
    {
        /** @var MessageCollection $messages */
        $messages = Message::query()
            ->where(fn ($query) =>
                $query
                    ->where('sender_id', $user1)
                    ->where('receiver_id', $user2)
            )
            ->orWhere(fn ($query) =>
                $query
                    ->where('sender_id', $user2)
                    ->where('receiver_id', $user1)
            )
            ->get();
        
        return $messages;
    }
}