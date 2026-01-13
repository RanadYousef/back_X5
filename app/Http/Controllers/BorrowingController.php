<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BorrowingController extends Controller
{
    /**
     * Display borrowing management dashboard.
     */
    public function index()
    {
        // Only fetch requests that actually need staff action
        $pendingRequests = BorrowingRequest::with(['book', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();
        return view('borrowings.index', compact('pendingRequests'));
    }
    public function history()
    {
        $borrowings = Borrowing::with(['book', 'user'])
            ->latest()
            ->get();
        return view('borrowings.history', compact('borrowings'));
    }

    /**
     * Approve borrow or return request.
     */
    public function approve(BorrowingRequest $borrowRequest)
    {
        try {
            return DB::transaction(function () use ($borrowRequest) {
                // Lock the book record to prevent stock inconsistencies 
                $book = $borrowRequest->book()->lockForUpdate()->first();

                if ($borrowRequest->request_type === 'borrow') {
                    // Double-check stock before final approval
                    if ($book->copies_number <= 0) {
                        return back()->with('error', 'Critical: Book is now out of stock.');
                    }

                    Borrowing::create([
                        'user_id' => $borrowRequest->user_id,
                        'book_id' => $borrowRequest->book_id,
                        'borrowed_at' => now(),
                        'due_date' => now()->addDays(25),
                        'status' => 'borrowed',
                    ]);

                    $book->decrement('copies_number');

                } elseif ($borrowRequest->request_type === 'return') {
                    // Find the specific borrowing record linked to this request
                    $borrowing = Borrowing::where('id', $borrowRequest->borrowing_id)
                        ->where('status', 'borrowed')
                        ->lockForUpdate()
                        ->first();

                    if (!$borrowing) {
                        return back()->with('error', 'Borrowing record not found or already processed.');
                    }

                    $borrowing->update([
                        'status' => 'returned',
                        'returned_at' => now(),
                    ]);

                    $book->increment('copies_number');
                }

                // Update request status 
                $borrowRequest->update(['status' => 'approved']);

                return back()->with('success', 'Request approved and processed successfully.');
            });

        } catch (\Exception $e) {
            Log::error('Borrowing approval error: ' . $e->getMessage());
            return back()->with('error', 'An internal error occurred during approval.');
        }
    }

    /**
     * Reject a borrow or return request.
     */
    public function reject(BorrowingRequest $borrowRequest)
    {
        $borrowRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Request has been rejected.');
    }

}