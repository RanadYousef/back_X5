<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\StoreBorrowingRequest;
use App\Http\Requests\Api\ReturnBookRequest;
use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BorrowingRequestController
 * Handles all subscriber operations: viewing records and submitting borrow/return requests.
 */
class BorrowingRequestController extends BaseApiController
{
    /**
     * Display books currently in the user's possession.
     */
    public function currentBorrowings()
    {
        try {
            $borrowings = Borrowing::with('book')
                ->where('user_id', Auth::id())
                ->where('status', 'borrowed')
                ->latest()
                ->get()
                ->map(function ($borrow) {
                    return [
                        'id' => $borrow->id,
                        'book_title' => $borrow->book->title,
                        'borrowed_at' => $borrow->borrowed_at->format('Y-m-d'),
                        'due_date' => $borrow->due_date ? $borrow->due_date->format('Y-m-d') : null,
                        'days_left' => $borrow->due_date ? (int) now()->diffInDays($borrow->due_date, false) : null,
                        'is_overdue' => $borrow->due_date ? now()->gt($borrow->due_date) : false,
                    ];
                });

            return $this->success($borrowings, 'Current active borrowings retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error fetching current borrowings: ' . $e->getMessage());
            return $this->error('Failed to retrieve current borrowings', 500);
        }
    }

    /**
     * Display historical record of books previously returned by the user.
     */
    public function borrowingHistory()
    {
        try {
            $history = Borrowing::with('book')
                ->where('user_id', Auth::id())
                ->where('status', 'returned')
                ->latest()
                ->get();

            return $this->success($history, 'Borrowing history retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error fetching borrowing history: ' . $e->getMessage());
            return $this->error('Failed to retrieve history', 500);
        }
    }

    /**
     * Submit a new request to borrow a physical book.
     */
    public function requestBorrow(StoreBorrowingRequest $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $validated = $request->validated();
                $userId = Auth::id();

                $alreadyBorrowed = Borrowing::where('user_id', $userId)
                    ->where('book_id', $validated['book_id'])
                    ->where('status', 'borrowed')
                    ->exists();

                if ($alreadyBorrowed) {
                    return $this->error('You already have this book in your possession', 400);
                }

                $pending = BorrowingRequest::where('user_id', $userId)
                    ->where('book_id', $validated['book_id'])
                    ->where('request_type', 'borrow')
                    ->where('status', 'pending')
                    ->exists();

                if ($pending) {
                    return $this->error('A borrow request for this book is already pending approval', 400);
                }

                $borrowRequest = BorrowingRequest::create([
                    'user_id' => $userId,
                    'book_id' => $validated['book_id'],
                    'request_type' => 'borrow',
                    'status' => 'pending'
                ]);

                return $this->success($borrowRequest, 'Borrow request submitted successfully', 201);
            } catch (Exception $e) {
                Log::error('Borrow Request Failed: ' . $e->getMessage());
                return $this->error('Failed to submit borrow request', 500);
            }
        });
    }

    /**
     * Submit a request to return a borrowed book.
     */
    public function requestReturn(ReturnBookRequest $request)
    {
        $data = $request->validated();
        return DB::transaction(function () use ($data) {
            try {
                $borrowing = Borrowing::where('user_id', Auth::id())
                    ->where('status', 'borrowed')
                    ->lockForUpdate()
                    ->findOrFail($data['borrowing_id']);

                $pendingReturn = BorrowingRequest::where('borrowing_id', $data['borrowing_id'])
                    ->where('request_type', 'return')
                    ->where('status', 'pending')
                    ->exists();

                if ($pendingReturn) {
                    return $this->error('A return request for this book is already pending', 400);
                }

                $returnRequest = BorrowingRequest::create([
                    'user_id' => Auth::id(),
                    'book_id' => $borrowing->book_id,
                    'borrowing_id' => $data['borrowing_id'],
                    'request_type' => 'return',
                    'status' => 'pending',
                ]);

                return $this->success($returnRequest, 'Return request submitted. Please return the book to the library.');
            } catch (Exception $e) {
                Log::error('Return Request Failed: ' . $e->getMessage());
                return $this->error('Invalid borrowing record or request already exists', 404);
            }
        });
    }

    /**
     * Get the status of all requests (Borrow/Return) for the authenticated user.
     */
    public function requestStatus()
    {
        try {
            $requests = BorrowingRequest::with('book')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

            return $this->success($requests, 'Request status history retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error fetching request status: ' . $e->getMessage());
            return $this->error('Internal Server Error', 500);
        }
    }
}