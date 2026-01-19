<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use App\Services\BorrowingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

/**
 * Class BorrowingController
 *
 * Handles staff/admin operations related to borrowing requests and history.
 *
 * Responsibilities:
 * - Viewing pending borrowing and return requests
 * - Approving or rejecting requests
 * - Viewing borrowing history
 *
 * This controller delegates business logic to BorrowingService.
 */
class BorrowingController extends Controller
{
    /**
     * Borrowing service instance.
     *
     * @var BorrowingService
     */
    protected $borrowingService;
    /**
     * BorrowingController constructor.
     *
     * Injects the BorrowingService which contains
     * the core business logic for borrowing operations.
     *
     * @param BorrowingService $borrowingService
     */
    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    /**
     * Display the borrowing management dashboard.
     *
     * Shows all pending borrowing and return requests
     * that require staff/admin action.
     *
     * @return \Illuminate\View\View
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
    /**
     * Display borrowing history.
     *
     * Shows a list of all borrowing records,
     * including returned and active borrowings.
     *
     * @return \Illuminate\View\View
     */    public function history()
    {
        $borrowings = Borrowing::with(['book', 'user'])
            ->latest()
            ->get();
        return view('borrowings.history', compact('borrowings'));
    }

    /**
     * Approve a borrowing or return request.
     *
     * This method delegates the approval logic
     * to the BorrowingService which:
     * - Updates request status
     * - Creates or updates borrowing records
     * - Handles stock changes if needed
     *
     * @param BorrowingRequest $borrowRequest
     * @return \Illuminate\Http\RedirectResponse
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
     * Reject a borrowing or return request.
     *
     * Marks the request as rejected without
     * performing any borrowing-related actions.
     *
     * @param BorrowingRequest $borrowRequest
     * @return \Illuminate\Http\RedirectResponse
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
