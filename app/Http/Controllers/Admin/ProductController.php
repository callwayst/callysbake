<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant as Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category','variants'])
            ->where('is_active', true);

        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(12);

        $products->getCollection()->transform(function ($product) {
            $variant = $product->variants->first();

            $product->final_price = $variant->price ?? $product->price;
            $product->final_stock = $variant->stock ?? $product->stock;

            return $product;
        });

        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }


    public function create()
    {
        $categories = Category::all();
        $product = null;

        return view('admin.products.create', compact('categories', 'product'));
    }


    /*
    |--------------------------------------------------------------------------
    | STORE (UPLOAD GAMBAR KE STORAGE)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        // harga integer
        $data['price'] = (int) preg_replace('/[^0-9]/', '', $request->price);

        // simpan gambar ke storage/products
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        if ($request->variants && is_array($request->variants['name'])) {
            foreach ($request->variants['name'] as $i => $name) {
                $product->variants()->create([
                    'name' => $name,
                    'price' => (int) preg_replace('/[^0-9]/', '', $request->variants['price'][$i]),
                    'stock' => $request->variants['stock'][$i],
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created!');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        // harga integer
        $data['price'] = (int) preg_replace('/[^0-9]/', '', $request->price);

        // replace image lama
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        if ($request->variants && is_array($request->variants['name'])) {
            foreach ($request->variants['name'] as $i => $name) {
                Variant::updateOrCreate(
                    ['product_id' => $product->id, 'name' => $name],
                    [
                        'price' => (int) preg_replace('/[^0-9]/', '', $request->variants['price'][$i]),
                        'stock' => $request->variants['stock'][$i]
                    ]
                );
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('variants');

        return view('admin.products.edit', compact('product', 'categories'));
    }


    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }


    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted!');
    }
}