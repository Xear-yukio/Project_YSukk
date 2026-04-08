<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class StockRequestController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $query = StockRequest::with(['product', 'user'])->latest();

        if (Auth::user()->role === 'petugas') {
            $query->where('user_id', Auth::id());
        }

        if ($q) {
            $query->whereHas('product', function($qr) use ($q) {
                $qr->where('name', 'like', "%{$q}%");
            });
        }

        $stockRequests = $query->paginate(10);
        return view('admin.stock_requests.index', compact('stockRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        StockRequest::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Permintaan stok berhasil diajukan!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $stockRequest = StockRequest::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        if ($request->status === 'approved' && $stockRequest->status === 'pending') {
            $product = $stockRequest->product;
            $product->increment('stock', $stockRequest->quantity);
        }

        $stockRequest->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Permintaan stok berhasil ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak') . '!');
    }
}
