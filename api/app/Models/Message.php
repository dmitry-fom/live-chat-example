<?php

namespace App\Models;

use App\Models\Collections\MessageCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $text
 * @property int $sender_id
 * @property int $receiver_id
 * @property User $receiver
 * @property User $sender
 */
class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'text',
        'sender_id',
        'receiver_id'
    ];
    
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
    
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }
    
    public function newCollection(array $models = []): MessageCollection
    {
        return new MessageCollection($models);
    }
}
