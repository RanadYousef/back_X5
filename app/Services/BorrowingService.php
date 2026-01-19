<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

class BorrowingService
{
    public function approveRequest(BorrowingRequest $borrowRequest)
    {
        return DB::transaction(function () use ($borrowRequest) {
            // Lock the book record to prevent stock inconsistencies 
            $book = $borrowRequest->book()->lockForUpdate()->first();

            if ($borrowRequest->request_type === 'borrow') {
                // Double-check stock before final approval
                if ($book->copies_number <= 0) {
                    throw new \Exception('Critical: Book is now out of stock.');
                }

                Borrowing::create([
                    'user_id' => $borrowRequest->user_id,
                    'book_id' => $borrowRequest->book_id,
                    'borrowed_at' => now(),
                    'due_date' => now()->addDays(25),
                    'status' => 'borrowed',
                ]);

                $book->decrement('copies_number');
                if ($book->copies_number <= 0) {
                    $admins = User::role('admin')->get();
                    Notification::send($admins, new LowStockNotification($book));
                }

            } elseif ($borrowRequest->request_type === 'return') {
                // Find the specific borrowing record linked to this request
                $borrowing = Borrowing::where('id', $borrowRequest->borrowing_id)
                    ->where('status', 'borrowed')
                    ->lockForUpdate()
                    ->first();

                if (!$borrowing) {
                    throw new \Exception('Borrowing record not found or already processed.');
                }

                $borrowing->update([
                    'status' => 'returned',
                    'returned_at' => now(),
                ]);

                $book->increment('copies_number');
            }

            // Update request status 
            $borrowRequest->update(['status' => 'approved']);

            return true;
        });
    }
    public function rejectRequest(BorrowingRequest $borrowRequest)
    {
        return $borrowRequest->update(['status' => 'rejected']);
    }
}