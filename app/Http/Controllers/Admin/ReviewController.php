<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with([
            'user',
            'product',
        ])
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update([
            'status' => true,
        ]);

        return back()->with(
            'success',
            'نظر با موفقیت تأیید شد.'
        );
    }

    public function reject(Review $review)
    {
        $review->update([
            'status' => false,
        ]);

        return back()->with(
            'success',
            'نظر رد شد.'
        );
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with(
            'success',
            'نظر با موفقیت حذف شد.'
        );
    }
}
