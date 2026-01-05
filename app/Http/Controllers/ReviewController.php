<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * عرض جميع التقييمات
     * (admin | employee)
     */
    public function index()
    {
        $reviews = Review::with(['user', 'book'])
            ->latest()
            ->get();

        return view('reviews.index', compact('reviews'));
    }

    /**
     * قبول / نشر التقييم
     * (employee فقط)
     */
    public function approve(Review $review): RedirectResponse
    {
        try {
            $review->update([
                'status' => 'approved',
            ]);

            return back()->with('success', 'تم نشر التقييم بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل نشر التقييم');
        }
    }

    /**
     * رفض التقييم
     * (employee فقط)
     */
    public function reject(Review $review): RedirectResponse
    {
        try {
            $review->update([
                'status' => 'rejected',
            ]);

            return back()->with('success', 'تم رفض التقييم');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل رفض التقييم');
        }
    }

    /**
     * حذف التقييم
     * (employee فقط)
     */
    public function destroy(Review $review): RedirectResponse
    {
        try {
            $review->delete();

            return back()->with('success', 'تم حذف التقييم');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل حذف التقييم');
        }
    }
}
