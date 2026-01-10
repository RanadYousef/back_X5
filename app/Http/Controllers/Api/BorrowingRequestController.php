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
                ->get();

            return $this->success($borrowings, 'Current active borrowings retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error fetching current borrowings: ' . $e->getMessage(), ['user_id' => Auth::id()]);
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
            Log::error('Error fetching borrowing history: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            return $this->error('Failed to retrieve history', 500);
        }
    }

    /**
     * Submit a new request to borrow a physical book.
     * * @param StoreBorrowingRequest $request
     */
    public function requestBorrow(StoreBorrowingRequest $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $validated = $request->validated();


                // Prevent duplicate pending borrow requests for the same book
                $pending = BorrowingRequest::where('user_id', Auth::id())
                    ->where('book_id', $validated['book_id'])
                    ->where('request_type', 'borrow')
                    ->exists();

                if ($pending) {
                    return $this->error('A borrow request for this book is already pending approval', 400);
                }

                $borrowRequest = BorrowingRequest::create([
                    'user_id' => Auth::id(),
                    'book_id' => $validated['book_id'],
                    'request_type' => 'borrow'
                ]);

                return $this->success($borrowRequest, 'Borrow request submitted. Please wait for staff approval.', 201);
            } catch (Exception $e) {
                Log::error('Borrow Request Creation Failed: ' . $e->getMessage());
                return $this->error('An error occurred while submitting the borrow request', 500);
            }
        });
    }

    /**
     * Submit a request to return a borrowed book.
     * * @param int $borrowingId
     */
    public function requestReturn(ReturnBookRequest $request)
    {
        $data = $request->validated();
        return DB::transaction(function () use ($data) {
            try {
                // Verify the user actually owns this active borrowing record
                $borrowing = Borrowing::where('user_id', Auth::id())
                    ->where('status', 'borrowed')
                    ->findOrFail($data['borrowing_id']);

                // Prevent duplicate return requests
                $pendingReturn = BorrowingRequest::where('borrowing_id', $data['borrowing_id'])
                    ->where('request_type', 'return')
                    ->exists();

                if ($pendingReturn) {
                    return $this->error('A return request for this book is already pending', 400);
                }

                $returnRequest = BorrowingRequest::create([
                    'user_id' => Auth::id(),
                    'book_id' => $borrowing->book_id,
                    'borrowing_id' => $data['borrowing_id'],
                    'request_type' => 'return'
                ]);

                return $this->success($returnRequest, 'Return request submitted. Please hand over the book to the staff.');
            } catch (Exception $e) {
                Log::error('Return Request Creation Failed: ' . $e->getMessage());
                return $this->error('Borrowing record not found or already returned', 404);
            }
        });
    }
}