<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WishlistController extends Controller
{
    /**
     * Display the wishlist page.
     */
    public function index()
    {
        $wishlist = session()->get('wishlist', []);
        
        $wishlistItems = [];
        foreach ($wishlist as $productId => $addedAt) {
            if ($item = Product::find($productId)) {
                $wishlistItems[] = $item;
            }
        }

        // Recommendations: products NOT in wishlist
        $wishlistIds = array_keys($wishlist);
        $recommendations = Product::whereNotIn('id', $wishlistIds)->inRandomOrder()->take(4)->get();

        return view('wishlist', compact('wishlistItems', 'recommendations'));
    }

    /**
     * Toggle a product in the wishlist (add/remove).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $wishlist = session()->get('wishlist', []);

        if (isset($wishlist[$productId])) {
            // Remove from wishlist
            unset($wishlist[$productId]);
            session()->put('wishlist', $wishlist);
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'removed',
                    'count' => count($wishlist),
                    'message' => 'Produk dihapus dari wishlist'
                ]);
            }
            return redirect()->back()->with('success', 'Produk dihapus dari wishlist!');
        } else {
            // Add to wishlist
            $wishlist[$productId] = now()->toDateTimeString();
            session()->put('wishlist', $wishlist);
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'added',
                    'count' => count($wishlist),
                    'message' => 'Produk ditambahkan ke wishlist'
                ]);
            }
            return redirect()->back()->with('success', 'Produk ditambahkan ke wishlist!');
        }
    }

    /**
     * Remove a product from the wishlist.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $productId = $request->product_id;
        $wishlist = session()->get('wishlist', []);

        if (isset($wishlist[$productId])) {
            unset($wishlist[$productId]);
            session()->put('wishlist', $wishlist);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'removed',
                'count' => count($wishlist),
                'message' => 'Produk dihapus dari wishlist'
            ]);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari wishlist!');
    }
}
