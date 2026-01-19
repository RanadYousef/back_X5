<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use App\Services\BorrowingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class BorrowingController
 * Handles the administrative actions for book borrowing and return requests.
 */
class BorrowingController extends Controller
{
    /**
     * @var BorrowingService
     */
    protected $borrowingService;

    /**
     * BorrowingController constructor.
     * * @param BorrowingService $borrowingService
     */
    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    /**
     * Display borrowing management dashboard.
     * * @return View
     */
    public function index(): View
    {
        // Only fetch requests that actually need staff action
        $pendingRequests = BorrowingRequest::with(['book', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('borrowings.index', compact('pendingRequests'));
    }

    /**
     * Display a complete history of all borrowings.
     * * @return View
     */
    public function history(): View
    {
        $borrowings = Borrowing::with(['book', 'user'])
            ->latest()
            ->get();

        return view('borrowings.history', compact('borrowings'));
    }

    /**
     * Approve borrow or return request.
     *
     * @param BorrowingRequest $borrowRequest The request instance being approved.
     * @return RedirectResponse
     */
    public function approve(BorrowingRequest $borrowRequest): RedirectResponse
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
     *
     * @param BorrowingRequest $borrowRequest The request instance being rejected.
     * @return RedirectResponse
     */
    public function reject(BorrowingRequest $borrowRequest): RedirectResponse
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