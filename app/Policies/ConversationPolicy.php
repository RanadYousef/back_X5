<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Determine whether the user can view the conversation
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $user->id === $conversation->manager_id || 
               $user->id === $conversation->employee_id;
    }

    /**
     * Determine whether the user can send a message
     */
    public function sendMessage(User $user, Conversation $conversation): bool
    {
        return $this->view($user, $conversation);
    }

    /**
     * Determine whether the user can delete the conversation
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        return $user->id === $conversation->manager_id || 
               $user->id === $conversation->employee_id;
    }
}
