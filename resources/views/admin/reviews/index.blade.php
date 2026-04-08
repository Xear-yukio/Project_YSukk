@extends('layouts.admin')

@section('title', 'Manajemen Ulasan - Belanja.ID')
@section('page_title', 'Manajemen Ulasan')

@section('content')
<div class="reviews-admin">
    {{-- Header Section --}}
    <div class="reviews-header">
        <div class="header-left">
            <h2 class="section-title">Semua Ulasan Customer</h2>
            <span class="review-count">{{ $reviews->total() }} ulasan</span>
        </div>
    </div>

    {{-- Reviews Table Card --}}
    <div class="reviews-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th width="180">Produk</th>
                        <th width="150">Customer</th>
                        <th width="100">Rating</th>
                        <th>Komentar</th>
                        <th width="120">Tanggal</th>
                        <th width="80" style="text-align: center;">Status</th>
                        <th width="100" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>
                            <div class="product-cell">
                                <img src="{{ $review->product->main_image }}" alt="" class="mini-product-img">
                                <span class="product-name" title="{{ $review->product->name }}">{{ Str::limit($review->product->name, 20) }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar-mini">{{ strtoupper(substr($review->user->name, 0, 1)) }}</div>
                                <span class="user-name">{{ $review->user->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="rating-cell">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            <p class="comment-text">{{ $review->comment }}</p>
                        </td>
                        <td>
                            <span class="date-cell">{{ $review->created_at->format('d M Y') }}</span>
                        </td>
                        <td style="text-align: center;">
                            @if($review->is_liked)
                                <span class="status-pill liked" title="Disukai oleh Admin">
                                    <i class="fas fa-heart"></i> Suka
                                </span>
                            @else
                                <span class="status-pill neutral">Normal</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('admin.reviews.like', $review->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-like {{ $review->is_liked ? 'active' : '' }}" title="{{ $review->is_liked ? 'Hapus Suka' : 'Beri Suka' }}">
                                        <i class="{{ $review->is_liked ? 'fas' : 'far' }} fa-heart"></i>
                                    </button>
                                </form>
                                <a href="{{ route('product.show', $review->product->id) }}" class="btn-link" target="_blank" title="Lihat di Produk">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="far fa-comment-dots"></i>
                                <p>Belum ada ulasan dari customer.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination-wrapper">
            {{ $reviews->links() }}
        </div>
    </div>
</div>

@push('styles')
<style>
    .reviews-admin {
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .reviews-header {
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .review-count {
        font-size: 13px;
        color: var(--text-muted);
        background: var(--bg);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    .reviews-card {
        background: var(--surface);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th {
        text-align: left;
        padding: 16px 20px;
        background: #fafbfc;
        border-bottom: 1px solid var(--border);
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-table td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-light);
        font-size: 14px;
        vertical-align: middle;
    }

    .admin-table tbody tr:hover {
        background: var(--surface-hover);
    }

    /* Product Cell */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mini-product-img {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        background: #f1f5f9;
    }

    .product-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }

    /* User Cell */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar-mini {
        width: 32px;
        height: 32px;
        background: var(--primary-soft);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12px;
    }

    .user-name {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 13px;
    }

    /* Rating stars */
    .rating-cell {
        color: #f59e0b;
        font-size: 11px;
        display: flex;
        gap: 2px;
    }

    .comment-text {
        color: var(--text-secondary);
        font-size: 13px;
        line-height: 1.5;
        max-width: 400px;
    }

    .date-cell {
        font-size: 12px;
        color: var(--text-muted);
    }

    /* Status Pills */
    .status-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-pill.liked {
        background: #fef2f2;
        color: #ef4444;
    }

    .status-pill.neutral {
        background: #f1f5f9;
        color: #64748b;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-like {
        background: none;
        border: 1px solid var(--border);
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--text-muted);
    }

    .btn-like:hover {
        background: #fef2f2;
        color: #ef4444;
        border-color: #fee2e2;
    }

    .btn-like.active {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
    }

    .btn-link {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-blue);
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-link:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 40px;
        margin-bottom: 12px;
        opacity: 0.3;
        display: block;
    }

    .pagination-wrapper {
        padding: 20px;
        border-top: 1px solid var(--border-light);
    }
</style>
@endpush
@endsection
