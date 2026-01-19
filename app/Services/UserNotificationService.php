<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\UserReviewApprovedNotification;
/**
 * Summary of UserNotificationService
 */
class UserNotificationService
{
    public static function reviewApproved(User $user, string $bookTitle): void
    {
        $user->notify(
            new UserReviewApprovedNotification($bookTitle)
        );
    }
}