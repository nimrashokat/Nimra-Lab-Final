@extends('layouts.admin')
@use('Illuminate\Support\Facades\Storage')

@section('content')
<div class="admin-wrapper">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon">✦</span>
            <span>SheStitch</span>
        </div>
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="/admin/products" class="nav-item">
                <i class="fas fa-tshirt"></i> Products
            </a>
            <a href="/admin/orders" class="nav-item active">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
            <a href="{{ route('home') }}" class="nav-item">
                <i class="fas fa-globe"></i> View Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item nav-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h4 class="page-title">Order Management</h4>
            <div class="topbar-right">
                <span class="admin-badge"><i class="fas fa-user-shield"></i> Admin</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success-box mx-4">✓ {{ session('success') }}</div>
        @endif

        <div class="section-card">
            <div class="section-header">
                <h5><i class="fas fa-shopping-bag"></i> All Orders ({{ $orders->total() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Design Image</th>
                            <th>Customer</th>
                            <th>Design</th>
                            <th>Size</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                @if($order->design_image)
                                    <img src="{{ Storage::url($order->design_image) }}"
                                         class="order-thumb"
                                         onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=60&h=60&fit=crop'"
                                         alt="Design">
                                @else
                                    <div class="no-image"><i class="fas fa-image"></i></div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $order->first_name }} {{ $order->last_name }}</strong>
                                <br><small class="text-muted">{{ $order->phone }}</small>
                            </td>
                            <td>{{ $order->design_choice }}</td>
                            <td><span class="size-badge">{{ $order->size }}</span></td>
                            <td>Rs {{ number_format($order->total_amount) }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                @if($order->status === 'delivered')
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" onsubmit="return confirm('Delete this order?')">
                                        @csrf @method('DELETE')
                                        <button class="btn-delete-order"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="d-flex gap-2">
                                        @csrf @method('PUT')
                                        <select name="status" class="status-select">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $order->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        </select>
                                        <button type="submit" class="btn-save">Save</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No orders yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
        </div>
    </main>
</div>

<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
.admin-wrapper { display: flex; min-height: 100vh; font-family: 'Segoe UI', sans-serif; background: #f8f1e9; }
.sidebar { width: 240px; min-height: 100vh; background: #111; color: #fff; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; z-index: 100; }
.sidebar-brand { padding: 24px 20px; font-size: 1.3rem; font-weight: 700; border-bottom: 1px solid #333; display: flex; align-items: center; gap: 10px; }
.brand-icon { color: #e99ab3; font-size: 1.5rem; }
.sidebar-nav { padding: 16px 0; display: flex; flex-direction: column; }
.nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #aaa; text-decoration: none; font-size: 0.9rem; transition: all 0.2s; border: none; background: none; cursor: pointer; width: 100%; text-align: left; }
.nav-item:hover, .nav-item.active { background: #222; color: #e99ab3; }
.nav-logout { color: #ff6b6b; }
.main-content { margin-left: 240px; flex: 1; padding: 0 0 40px 0; }
.topbar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 16px 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 28px; }
.page-title { font-size: 1.2rem; font-weight: 700; color: #111; }
.admin-badge { background: #f5d0dc; color: #c0607e; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
.section-card { background: #fff; border-radius: 14px; padding: 24px; margin: 0 28px 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f0e8e0; }
.section-header h5 { font-size: 1rem; font-weight: 700; color: #111; display: flex; align-items: center; gap: 8px; }
.alert-success-box { background: #d1e7dd; color: #0f5132; padding: 10px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 0.9rem; }
.admin-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
.admin-table th { background: #f8f1e9; padding: 10px 14px; text-align: left; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: #888; }
.admin-table td { padding: 12px 14px; border-bottom: 1px solid #f5ede5; color: #333; vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }
.order-thumb { width: 56px; height: 56px; object-fit: cover; border-radius: 10px; border: 2px solid #f0e8e0; }
.no-image { width: 56px; height: 56px; border-radius: 10px; background: #f8f1e9; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 1.2rem; }
.size-badge { background: #f5d0dc; color: #c0607e; padding: 3px 8px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; }
.status-badge { padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 600; }
.status-pending { background: #fff3cd; color: #856404; }
.status-in_progress { background: #cfe2ff; color: #0a58ca; }
.status-delivered { background: #d1e7dd; color: #0f5132; }
.status-select { border: 1px solid #ddd; border-radius: 6px; padding: 4px 8px; font-size: 0.82rem; cursor: pointer; }
.btn-save { background: #e99ab3; color: #fff; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; cursor: pointer; }
.btn-save:hover { background: #d985a0; }
.btn-delete-order { background: none; border: 1px solid #ffcdd2; color: #e53935; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; cursor: pointer; }
.btn-delete-order:hover { background: #ffebee; }
.text-muted { color: #aaa; }
.text-center { text-align: center; }
</style>
@endsection
