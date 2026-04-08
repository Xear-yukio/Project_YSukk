<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with(['allReviews.user'])->find($id);

        if (!$product) {
            abort(404);
        }

        // Calculate dynamic rating stats
        $reviews = $product->allReviews;
        $totalReviews = $reviews->count();
        $avgRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;
        
        $starStats = [
            5 => ['count' => 0, 'percent' => 0],
            4 => ['count' => 0, 'percent' => 0],
            3 => ['count' => 0, 'percent' => 0],
            2 => ['count' => 0, 'percent' => 0],
            1 => ['count' => 0, 'percent' => 0],
        ];

        if ($totalReviews > 0) {
            foreach ($reviews as $review) {
                if (isset($starStats[$review->rating])) {
                    $starStats[$review->rating]['count']++;
                }
            }
            foreach ($starStats as $star => $data) {
                $starStats[$star]['percent'] = round(($data['count'] / $totalReviews) * 100);
            }
        }

        // Fetch related products from DB
        $relatedProducts = Product::where('id', '!=', $id)->take(4)->get();

        return view('products.show', compact('product', 'relatedProducts', 'avgRating', 'totalReviews', 'starStats'));
    }

    public function category($slug)
    {
        $categoryMapping = [
            'phones' => ['title' => 'Handphone & Aksesoris', 'tags' => ['Handphone', 'Accessories', 'Mobile', 'Audio']],
            'computers' => ['title' => 'Komputer & Laptop', 'tags' => ['Computing', 'Laptop', 'Monitor', 'Gaming']],
            'camera' => ['title' => 'Kamera', 'tags' => ['Camera', 'Electronics', 'Photo']],
            'fashion' => ['title' => 'Fashion Pria', 'tags' => ['Fashion', 'Clothing', 'Men']],
            'beauty' => ['title' => 'Kecantikan', 'tags' => ['Beauty', 'Skincare', 'Makeup']],
            'furniture' => ['title' => 'Perabotan', 'tags' => ['Furniture', 'Home', 'Decor']],
            'sports' => ['title' => 'Olahraga', 'tags' => ['Sports', 'Fitness', 'Outdoor']],
            'toys' => ['title' => 'Bayi & Anak', 'tags' => ['Baby & Kids', 'Toys', 'Kids']],
            'health' => ['title' => 'Kesehatan', 'tags' => ['Health', 'Medical', 'Wellness']],
            'audio' => ['title' => 'Koleksi Audio', 'tags' => ['Audio', 'Headphone', 'Speaker', 'Music']],
        ];

        if (!isset($categoryMapping[$slug])) {
            abort(404);
        }

        $categoryData = $categoryMapping[$slug];
        
        // Filter products using the database with tags support
        $products = Product::where(function($query) use ($slug, $categoryData) {
            $query->whereJsonContains('breadcrumbs', $slug)
                  ->orWhereHas('category', function($q) use ($slug) {
                      $q->where('slug', $slug);
                  });
            
            // Also check associated tags (e.g. 'Audio' vs 'audio')
            if (isset($categoryData['tags'])) {
                foreach ($categoryData['tags'] as $tag) {
                    $query->orWhereJsonContains('breadcrumbs', $tag);
                }
            }
        })->get();

        return view('products.category', [
            'title' => $categoryData['title'],
            'products' => $products
        ]);
    }

    public function newProducts()
    {
        $products = Product::getNew();
        return view('products.category', [
            'title' => 'Produk Terbaru',
            'products' => $products
        ]);
    }

    public function promoProducts()
    {
        $products = Product::getPromo();
        return view('products.category', [
            'title' => 'Promo Spesial',
            'products' => $products
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query) {
            return redirect()->back();
        }

        $products = Product::search($query);

        return view('products.category', [
            'title' => 'Hasil Pencarian: "' . $query . '"',
            'products' => $products
        ]);
    }
    public function apiIndex(Request $request)
    {
        $offset = $request->input('offset', 8);
        $limit = $request->input('limit', 8);
        
        $products = Product::orderBy('created_at', 'desc')->skip($offset)->take($limit)->get();
        
        $html = '';
        foreach ($products as $product) {
            // Passing a new AttributeBag to avoid error when rendering component as a regular view
            $html .= view('components.product-card', [
                'product' => $product,
                'attributes' => new \Illuminate\View\ComponentAttributeBag()
            ])->render();
        }
        
        return response()->json([
            'html' => $html,
            'count' => $products->count(),
            'hasMore' => Product::count() > ($offset + $limit)
        ]);
    }
}
