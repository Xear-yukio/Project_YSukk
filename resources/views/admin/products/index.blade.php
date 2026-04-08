@extends('layouts.admin')

@section('title', 'Manajemen Produk - Belanja.ID')
@section('page_title', 'Manajemen Produk')

@section('content')
@if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(Auth::user()->isAdmin())
<div style="display: flex; justify-content: flex-end; margin-bottom: 24px;">
    <button onclick="openFormModal()" class="btn-primary" style="background: #e74c3c; color: #fff; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-plus"></i> Tambah Produk Baru
    </button>
</div>
@endif

<div class="admin-card">
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f8f9fa; color: #888; font-size: 12px; text-transform: uppercase;">
                    <th style="padding: 16px; font-weight: 700;">Produk</th>
                    <th style="padding: 16px; font-weight: 700;">Kategori</th>
                    <th style="padding: 16px; font-weight: 700;">Stok</th>
                    <th style="padding: 16px; font-weight: 700;">Harga (Rp)</th>
                    <th style="padding: 16px; font-weight: 700;">Promo</th>
                    <th style="padding: 16px; font-weight: 700; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr style="border-bottom: 1px solid #f8f9fa; font-size: 14px;">
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ $product->main_image }}" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; background: #f9f9f9;">
                            <div>
                                <h4 style="font-weight: 600; margin: 0; color: #333;">{{ $product->name }}</h4>
                                <small style="color: #888;">ID: #PRD-{{ $product->id }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px;">
                        <span style="background: #f3f4f6; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                    </td>
                    <td style="padding: 16px;">
                        @if($product->stock <= 5)
                            <span style="color: #e74c3c; font-weight: 700;"><i class="fas fa-exclamation-triangle"></i> {{ $product->stock }}</span>
                        @else
                            <span style="font-weight: 600; color: #2ecc71;">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td style="padding: 16px; font-weight: 700; color: #333;">
                        {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td style="padding: 16px;">
                        @if($product->old_price)
                            <span style="color: #e74c3c; font-weight: 600; font-size: 12px;">DISCOUNT {{ $product->discount }}</span>
                        @else
                            <span style="color: #aaa; font-size: 12px;">No Active Promo</span>
                        @endif
                    </td>
                    <td style="padding: 16px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 12px;">
                            @if(Auth::user()->role === 'petugas')
                                <button onclick="openStockRequestModal({{ json_encode(['id' => $product->id, 'name' => $product->name, 'stock' => $product->stock]) }})" style="color: #2ecc71; border: 1px solid #2ecc71; background: none; cursor: pointer; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 4px; display: flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-plus"></i> Minta Stok
                                </button>
                            @endif
                            <button onclick="editProduct({{ json_encode($product) }})" style="color: #3498db; border: none; background: none; cursor: pointer; font-size: 16px;"><i class="fas fa-edit"></i></button>
                            @if(Auth::user()->isAdmin())
                                <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: #e74c3c; border: none; background: none; cursor: pointer; font-size: 16px;"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- STOCK REQUEST MODAL (Petugas) --}}
<div id="stockRequestModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: #fff; width: 450px; border-radius: 20px; padding: 32px; box-shadow: 0 20px 50px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; margin-bottom: 8px; font-size: 20px; font-weight: 800;">Minta Stok Baru</h3>
        <p style="color: #888; font-size: 14px; margin-bottom: 24px;">Silakan isi jumlah stok yang dibutuhkan untuk produk ini.</p>
        
        <form action="{{ route('admin.stock_requests.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" id="reqProductId">
            <div style="margin-bottom: 20px; background: #f9fafb; padding: 12px; border-radius: 10px; border: 1px solid #f3f4f6;">
                <span style="display: block; font-size: 12px; color: #888; margin-bottom: 4px;">Produk Terpilih:</span>
                <span id="reqProductName" style="font-weight: 700; color: #333;"></span>
            </div>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Jumlah Stok yang Diminta</label>
                <input type="number" name="quantity" required min="1" placeholder="Contoh: 50" style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none;">
            </div>
            
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Catatan (Opsional)</label>
                <textarea name="notes" rows="3" placeholder="Sebutkan alasan atau detail lainnya..." style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none;"></textarea>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" style="flex: 2; background: #2ecc71; color: #fff; border: none; padding: 14px; border-radius: 8px; font-weight: 700; cursor: pointer;">Kirim Permintaan</button>
                <button type="button" onclick="closeStockRequestModal()" style="flex: 1; background: #f3f4f6; color: #374151; border: none; padding: 14px; border-radius: 8px; font-weight: 600; cursor: pointer;">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- CRUD MODAL (Admin Edit) --}}
<div id="productModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: #fff; width: 600px; border-radius: 20px; padding: 32px; box-shadow: 0 20px 50px rgba(0,0,0,0.1);">
        <h3 id="modalTitle" style="margin-top: 0; margin-bottom: 24px; font-size: 20px; font-weight: 800;">Tambah Produk</h3>
        <form id="productForm" action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div id="methodField"></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Nama Produk</label>
                    <input type="text" name="name" id="prodName" required style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Kategori</label>
                    <select name="category_id" id="prodCategory" required style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none;">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Harga (Rp)</label>
                    <input type="number" name="price" id="prodPrice" required style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Stok</label>
                    <input type="number" name="stock" id="prodStock" required style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Gambar URL</label>
                    <input type="text" name="main_image" id="prodImage" placeholder="https://..." style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none;">
                </div>
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Deskripsi Singkat</label>
                <textarea name="description" id="prodDesc" rows="3" style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none;"></textarea>
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" style="flex: 2; background: #e74c3c; color: #fff; border: none; padding: 14px; border-radius: 8px; font-weight: 700; cursor: pointer;">Simpan Produk</button>
                <button type="button" onclick="closeModal()" style="flex: 1; background: #f3f4f6; color: #374151; border: none; padding: 14px; border-radius: 8px; font-weight: 600; cursor: pointer;">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openFormModal() {
        document.getElementById('productModal').style.display = 'flex';
        document.getElementById('modalTitle').innerText = 'Tambah Produk Baru';
        document.getElementById('productForm').action = "{{ route('admin.products.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('productForm').reset();
    }

    function editProduct(product) {
        document.getElementById('productModal').style.display = 'flex';
        document.getElementById('modalTitle').innerText = 'Edit Produk';
        document.getElementById('productForm').action = "/admin/products/" + product.id;
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('prodName').value = product.name;
        document.getElementById('prodCategory').value = product.category_id;
        document.getElementById('prodPrice').value = Math.round(product.price);
        document.getElementById('prodStock').value = product.stock;
        document.getElementById('prodImage').value = product.main_image;
        document.getElementById('prodDesc').value = product.description;
    }

    function closeModal() {
        document.getElementById('productModal').style.display = 'none';
    }

    function openStockRequestModal(product) {
        document.getElementById('stockRequestModal').style.display = 'flex';
        document.getElementById('reqProductId').value = product.id;
        document.getElementById('reqProductName').innerText = product.name + ' (Sisa Stok: ' + product.stock + ')';
    }

    function closeStockRequestModal() {
        document.getElementById('stockRequestModal').style.display = 'none';
    }
</script>

<style>
    .admin-card { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    input:focus, select:focus, textarea:focus { border-color: #e74c3c !important; }
</style>
@endsection
