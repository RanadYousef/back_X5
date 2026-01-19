<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $booksCount = Book::count();
        $categoriesCount = Category::count();
        $usersCount = User::count();
        $activeBorrowingsCount = Borrowing::whereNull('returned_at')->count();

        $borrowingsData = Borrowing::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $recentBorrowings = Borrowing::with(['book', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'booksCount',
            'categoriesCount',
            'usersCount',
            'activeBorrowingsCount',
            'borrowingsData',
            'recentBorrowings'
        ));
    }
}
