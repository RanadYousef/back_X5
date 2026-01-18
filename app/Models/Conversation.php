<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['manager_id', 'employee_id'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the other participant in the conversation
     */
    public function otherParticipant($userId)
    {
        return $userId == $this->manager_id 
            ? $this->employee 
            : $this->manager;
    }

    /**
     * Get unread messages count for a user
     */
    public function unreadMessagesCount($userId)
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}

