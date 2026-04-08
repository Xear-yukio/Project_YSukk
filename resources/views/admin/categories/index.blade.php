@extends('layouts.admin')

@section('title', 'Manajemen Kategori - Belanja.ID')
@section('page_title', 'Manajemen Kategori')

@section('content')
@if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; color: #991b1b; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    {{-- Form Tambah/Edit Kategori --}}
    <div class="admin-card">
        <h3 id="form-title" style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Tambah Kategori Baru</h3>
        <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div id="method-field"></div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Nama Kategori</label>
                <input type="text" name="name" id="cat_name" oninput="generateSlug(this.value)" required placeholder="Contoh: Elektronik" style="width: 100%; padding: 10px 12px; border: 1px solid #eee; border-radius: 8px; outline: none; transition: border-color 0.2s;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Slug URL</label>
                <input type="text" name="slug" id="cat_slug" required placeholder="elektronik" style="width: 100%; padding: 10px 12px; border: 1px solid #eee; border-radius: 8px; outline: none; background: #f9f9f9;" readonly>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px;">Ikon (Font Awesome)</label>
                <input type="text" name="icon" id="cat_icon" placeholder="fas fa-laptop" style="width: 100%; padding: 10px 12px; border: 1px solid #eee; border-radius: 8px; outline: none;">
                <small style="color: #888; font-size: 11px; margin-top: 4px; display: block;">Gunakan nama class FontAwesome, misal: fas fa-mobile-alt</small>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" id="submit-btn" class="btn-primary" style="flex: 2; background: #e74c3c; color: #fff; border: none; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                    Simpan Kategori
                </button>
                <button type="button" id="cancel-btn" onclick="resetForm()" style="display: none; flex: 1; background: #f3f4f6; color: #374151; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Batal
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Kategori --}}
    <div class="admin-card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Daftar Kategori Aktif</h3>
        <div class="table-container">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid #f8f9fa; color: #888; font-size: 12px; text-transform: uppercase;">
                        <th style="padding: 12px; font-weight: 700;">Ikon</th>
                        <th style="padding: 12px; font-weight: 700;">Nama</th>
                        <th style="padding: 12px; font-weight: 700;">Slug</th>
                        <th style="padding: 12px; font-weight: 700;">Produk</th>
                        <th style="padding: 12px; font-weight: 700; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr style="border-bottom: 1px solid #f8f9fa; font-size: 14px;">
                        <td style="padding: 12px;"><i class="{{ $cat->icon ?? 'fas fa-tag' }}" style="color: #666; width: 20px; text-align: center;"></i></td>
                        <td style="padding: 12px; font-weight: 600; color: #333;">{{ $cat->name }}</td>
                        <td style="padding: 12px; color: #888; font-family: monospace;">{{ $cat->slug }}</td>
                        <td style="padding: 12px; font-weight: 700; color: #e74c3c;">{{ $cat->product_count }}</td>
                        <td style="padding: 12px; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                <button onclick="editCategory({{ json_encode($cat) }})" style="border: none; background: none; color: #3498db; cursor: pointer; padding: 5px;"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="border: none; background: none; color: #e74c3c; cursor: pointer; padding: 5px;"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #999;">Belum ada kategori yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .admin-card { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
    input:focus { border-color: #e74c3c !important; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2); }
</style>

<script>
    function generateSlug(name) {
        const slug = name.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('cat_slug').value = slug;
    }

    function editCategory(category) {
        document.getElementById('form-title').innerText = 'Edit Kategori: ' + category.name;
        document.getElementById('cat_name').value = category.name;
        document.getElementById('cat_slug').value = category.slug;
        document.getElementById('cat_icon').value = category.icon;
        
        document.getElementById('categoryForm').action = "/admin/categories/" + category.id;
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('submit-btn').innerText = 'Perbarui Kategori';
        document.getElementById('cancel-btn').style.display = 'block';
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('form-title').innerText = 'Tambah Kategori Baru';
        document.getElementById('cat_name').value = '';
        document.getElementById('cat_slug').value = '';
        document.getElementById('cat_icon').value = '';
        
        document.getElementById('categoryForm').action = "{{ route('admin.categories.store') }}";
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('submit-btn').innerText = 'Simpan Kategori';
        document.getElementById('cancel-btn').style.display = 'none';
    }
</script>
@endsection
