<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use App\Services\BorrowingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BorrowingController extends Controller
{
    protected $borrowingService;
    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }
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
            $this->borrowingService->approveRequest($borrowRequest);

            return back()->with('success', 'Request approved and processed successfully.');
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
        try {
            $this->borrowingService->rejectRequest($borrowRequest);

            return back()->with('success', 'Request has been rejected.');
        } catch (\Exception $e) {
            Log::error('Borrowing rejection error: ' . $e->getMessage());
            return back()->with('error', 'An internal error occurred during rejection.');
        }

    }
}