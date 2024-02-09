<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class MessageSent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    
    public function __construct(private readonly Message $message) {}
    
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
    
    public function broadcastWith(): array
    {
        return $this->message->toArray();
    }
    
    public function broadcastOn(): array
    {
        $key = [$this->message->sender_id, $this->message->receiver_id];
        sort($key);
        $key = implode($key);
        
        $chatId = md5($key);

        return [
            new PrivateChannel('chat.'. $chatId),
        ];
    }
}