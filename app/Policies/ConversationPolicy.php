<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
/**
 * Summary of ConversationPolicy
 */
class ConversationPolicy
{
    /**
     * Summary of view
     * @param User $user
     * @param Conversation $conversation
     * @return bool
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $user->id === $conversation->manager_id || 
               $user->id === $conversation->employee_id;
    }

    /**
     * Summary of sendMessage
     * @param User $user
     * @param Conversation $conversation
     * @return bool
     */
    public function sendMessage(User $user, Conversation $conversation): bool
    {
        return $this->view($user, $conversation);
    }

    /**
     * Summary of delete
     * @param User $user
     * @param Conversation $conversation
     * @return bool
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        return $user->id === $conversation->manager_id || 
               $user->id === $conversation->employee_id;
    }
}
