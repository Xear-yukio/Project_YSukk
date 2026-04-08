<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Ulasan Anda telah berhasil dikirim.');
    }

    /**
     * Admin/Petugas: Lihat semua ulasan
     */
    public function adminIndex()
    {
        $reviews = Review::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Admin/Petugas: Beri tanda suka pada ulasan
     */
    public function toggleLike(Review $review)
    {
        $review->update([
            'is_liked' => !$review->is_liked
        ]);

        $message = $review->is_liked ? 'Ulasan telah diberi tanda suka.' : 'Tanda suka telah dihapus dari ulasan.';
        
        return redirect()->back()->with('success', $message);
    }
}
