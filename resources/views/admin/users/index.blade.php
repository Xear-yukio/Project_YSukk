@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - Belanja.ID')
@section('page_title', 'Manajemen Pengguna')

@section('content')
@if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; color: #991b1b; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div style="background: #fee2e2; color: #991b1b; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="admin-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; color: #111;">Daftar Pengguna</h3>
        <button onclick="openAddModal()" class="btn-primary" style="background: #e74c3c; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </button>
    </div>

    <div class="table-container" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f8f9fa; color: #888; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <th style="padding: 16px; font-weight: 700;">Nama</th>
                    <th style="padding: 16px; font-weight: 700;">Email</th>
                    <th style="padding: 16px; font-weight: 700;">Role</th>
                    <th style="padding: 16px; font-weight: 700;">Tgl Bergabung</th>
                    <th style="padding: 16px; font-weight: 700; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid #f8f9fa; font-size: 14px; transition: all 0.2s;">
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #555;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight: 600; color: #333;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="padding: 16px; color: #666;">{{ $user->email }}</td>
                    <td style="padding: 16px;">
                        @php
                            $roleStyle = [
                                'admin' => 'background: #fee2e2; color: #b91c1c;',
                                'petugas' => 'background: #dbeafe; color: #1e40af;',
                                'user' => 'background: #f3f4f6; color: #374151;'
                            ][$user->role] ?? 'background: #f3f4f6; color: #374151;';
                        @endphp
                        <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; {{ $roleStyle }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td style="padding: 16px; color: #666;">{{ $user->created_at->format('d M Y') }}</td>
                    <td style="padding: 16px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <button onclick="openEditModal({{ $user->toJson() }})" title="Edit" style="width: 32px; height: 32px; border-radius: 6px; border: 1px solid #eee; background: #fff; color: #3498db; cursor: pointer; transition: all 0.2s;"><i class="fas fa-edit"></i></button>
                            
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" style="width: 32px; height: 32px; border-radius: 6px; border: 1px solid #eee; background: #fff; color: #e74c3c; cursor: pointer; transition: all 0.2s;"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 40px; text-align: center; color: #888;">Belum ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add User Modal --}}
<div id="addModal" class="modal">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="font-weight:700;">Tambah Pengguna / Petugas</h3>
            <span class="close" onclick="closeModal('addModal')">&times;</span>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Contoh: Andi Wijaya">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Contoh: andi@email.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="button" onclick="closeModal('addModal')" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary-red">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit User Modal --}}
<div id="editModal" class="modal">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="font-weight:700;">Edit Pengguna</h3>
            <span class="close" onclick="closeModal('editModal')">&times;</span>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="edit_role" required>
                    <option value="user">User</option>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ganti Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" placeholder="Minimal 8 karakter">
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="button" onclick="closeModal('editModal')" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary-red">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<style>
    .admin-card { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
    tr:hover { background: #fafafa; }
    
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
    .modal-content { background: #fff; margin: 5% auto; padding: 30px; border-radius: 16px; width: 450px; box-shadow: 0 5px 30px rgba(0,0,0,0.2); }
    .close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close:hover { color: #333; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #444; }
    .form-group input, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; }
    .form-group input:focus { border-color: #e74c3c; box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1); }

    .btn-secondary { background: #f3f4f6; color: #374151; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-primary-red { background: #e74c3c; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
</style>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'block';
    }

    function openEditModal(user) {
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('editForm').action = "/admin/users/" + user.id;
        document.getElementById('editModal').style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection
