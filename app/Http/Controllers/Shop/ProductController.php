<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan semua produk untuk user (LIST + FILTER + PAGINATION)
     */
    public function index(Request $request)
    {
        $query = Product::query()->active();

        // ================= FILTER =================

        // Category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Price range
        if ($request->filled('price_range')) {
            [$min, $max] = explode('-', $request->price_range);
            $query->whereBetween('price', [(int) $min, (int) $max]);
        }

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('name', 'like', "%{$search}%");
        }

        // ================= RELATIONS + AGGREGATE + PAGINATION =================
        // PENTING: gunakan paginate() BUKAN get()

        $products = $query
            ->with('category')
            ->withCount([
                'reviews as reviews_count' => function ($q) {
                    $q->where('status', 'active');
                }
            ])
            ->withAvg([
                'reviews as reviews_avg_rating' => function ($q) {
                    $q->where('status', 'active');
                }
            ], 'rating')
            ->latest()
            ->paginate(12)          // ✅ WAJIB supaya hasPages() & links() jalan
            ->withQueryString();   // ✅ biar filter/search tidak hilang saat pindah page

        $categories = Category::all();

        return view('shop.products.index', compact('products', 'categories'));
    }

    /**
     * Detail produk
     */
    public function show(Product $product)
    {
        $product->load([
            'category',
            'variants',
            'reviews' => function ($q) {
                $q->where('status', 'active')->with('user');
            }
        ]);

        $variant      = $product->variants->first();
        $avgRating    = $product->reviews->avg('rating') ?? 0;
        $reviewCount  = $product->reviews->count() ?? 0;
        $cartItems    = auth()->user()?->cartItems ?? collect();

        return view(
            'shop.products.show',
            compact('product', 'variant', 'avgRating', 'reviewCount', 'cartItems')
        );
    }
}