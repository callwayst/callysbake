<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * 1. List semua review (index)
     */
    public function index(Request $request)
    {
        $query = Review::with(['user','product','product.category']);

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search user name or comment
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhere('comment', 'like', "%$search%");
        }

        // Deteksi mobile sederhana
        $isMobile = preg_match('/Mobile|Android|iP(hone|od|ad)/', $request->header('User-Agent'));
        $perPage = $isMobile ? 5 : 10;

        $reviews = $query->latest()->paginate($perPage)->withQueryString();

        $products = Product::all(); // dropdown filter

        return view('admin.reviews.index', compact('reviews','products','isMobile'));
    }

    /**
     * 2. Approve review
     */
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->approved = true;
        $review->save();

        return back()->with('success', 'Review berhasil disetujui.');
    }

    /**
     * 3. Toggle status review: active / hidden
     */
    public function toggle($id)
    {
        $review = Review::findOrFail($id);
        $review->status = $review->status === 'active' ? 'hidden' : 'active';
        $review->save();

        return redirect()->back()->with('success', 'Review status updated.');
    }

    /**
     * 4. Hapus review
     */
    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success', 'Review berhasil dihapus.');
    }

    /**
     * 5. Lihat detail review
     */
    public function show($id)
    {
        $review = Review::with(['user', 'product', 'product.category'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }
}