@extends('layouts.admin')

@section('content')
<div class="admin-wrapper">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon">✦</span>
            <span>SheStitch</span>
        </div>
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item active">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="/admin/products" class="nav-item">
                <i class="fas fa-tshirt"></i> Products
            </a>
            <a href="/admin/orders" class="nav-item">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
            <a href="#messages" class="nav-item">
                <i class="fas fa-envelope"></i> Messages
            </a>
            <a href="#sales" class="nav-item">
                <i class="fas fa-tags"></i> Live Sales
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

    {{-- MAIN CONTENT --}}
    <main class="main-content">

        {{-- TOP BAR --}}
        <div class="topbar">
            <h4 class="page-title">Admin Dashboard</h4>
            <div class="topbar-right">
                <span class="admin-badge"><i class="fas fa-user-shield"></i> Admin</span>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="stats-grid">
            <div class="stat-card stat-pink">
                <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-info">
                    <h6>Total Orders</h6>
                    <h2>{{ $totalOrders }}</h2>
                </div>
            </div>
            <div class="stat-card stat-yellow">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <h6>Pending Orders</h6>
                    <h2>{{ $pendingOrders }}</h2>
                </div>
            </div>
            <div class="stat-card stat-green">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h6>Delivered Orders</h6>
                    <h2>{{ $deliveredOrders }}</h2>
                </div>
            </div>
            <div class="stat-card stat-blue">
                <div class="stat-icon"><i class="fas fa-tshirt"></i></div>
                <div class="stat-info">
                    <h6>Total Products</h6>
                    <h2>{{ $totalProducts }}</h2>
                </div>
            </div>
            <div class="stat-card stat-purple">
                <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
                <div class="stat-info">
                    <h6>Total Sales</h6>
                    <h2>Rs {{ number_format($totalSales) }}</h2>
                </div>
            </div>
            <div class="stat-card stat-dark">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h6>Total Customers</h6>
                    <h2>{{ $totalCustomers }}</h2>
                </div>
            </div>
        </div>

        {{-- CHARTS --}}
        <div class="charts-grid">
            <div class="chart-card">
                <h6 class="chart-title"><i class="fas fa-bar-chart"></i> Daily Orders</h6>
                <canvas id="dailyOrdersChart" height="120"></canvas>
            </div>
            <div class="chart-card">
                <h6 class="chart-title"><i class="fas fa-line-chart"></i> Monthly Sales (Rs)</h6>
                <canvas id="monthlySalesChart" height="120"></canvas>
            </div>
        </div>

        {{-- ADD PRODUCT --}}
        <div class="section-card" id="add-product">
            <div class="section-header">
                <h5><i class="fas fa-plus-circle"></i> Add New Product</h5>
            </div>
            <form method="POST" action="/admin/products" enctype="multipart/form-data" class="product-form">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Bridal Lehenga" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            @foreach(\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price (Rs)</label>
                        <input type="number" name="price" class="form-control" placeholder="e.g. 5000" required>
                    </div>
                    <div class="form-group">
                        <label>Rating (1-5)</label>
                        <input type="number" name="rating" class="form-control" min="1" max="5" step="0.1" placeholder="4.5">
                    </div>
                    <div class="form-group">
                        <label>Discount %</label>
                        <input type="number" name="discount_percent" class="form-control" min="0" max="90" placeholder="10">
                    </div>
                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Product description..."></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Sizes Available</label>
                        <div class="size-checkboxes">
                            @foreach(['S','M','L','XL','XXL'] as $size)
                                <label class="size-label">
                                    <input type="checkbox" name="sizes[]" value="{{ $size }}" checked> {{ $size }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <button type="submit" class="btn-admin-pink"><i class="fas fa-plus"></i> Add Product</button>
            </form>
        </div>

        {{-- RECENT ORDERS --}}
        <div class="section-card" id="orders">
            <div class="section-header">
                <h5><i class="fas fa-shopping-bag"></i> Recent Orders</h5>
                <a href="/admin/orders" class="btn-view-all">View All</a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Order::with('user')->latest()->limit(5)->get() as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>Rs {{ number_format($order->total_amount) }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                @if($order->status === 'delivered')
                                    <form method="POST" action="/admin/orders/{{ $order->id }}" onsubmit="return confirm('Is order delete karna chahti hain?')">
                                        @csrf @method('DELETE')
                                        <button class="btn-delete-order">🗑 Delete</button>
                                    </form>
                                @else
                                    <form method="POST" action="/admin/orders/{{ $order->id }}">
                                        @csrf @method('PUT')
                                        <select name="status" class="status-select" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $order->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- LIVE SALES --}}
        <div class="section-card" id="sales">
            <div class="section-header">
                <h5><i class="fas fa-tags"></i> Live Sale Management</h5>
            </div>
            <div class="sales-grid">
                <div class="sale-box">
                    <h6>Flash Sale Banner</h6>
                    <div class="flash-banner" id="flashBanner">
    <span class="flash-icon">⚡</span>
    <span id="bannerText">Flash Sale Active! Up to <strong id="discountText">50%</strong> OFF</span>
</div>
<form class="mt-3" onsubmit="updateBanner(event)">
    <label>Discount Percentage</label>
    <div class="input-group mb-2">
        <input type="number" id="discountInput" class="form-control" placeholder="e.g. 30" min="0" max="90">
        <button type="submit" class="btn-admin-pink">Apply</button>
    </div>
    <small id="bannerMsg" style="color:green; display:none;">✓ Banner updated!</small>
</form>

<script>
function updateBanner(e) {
    e.preventDefault();
    const val = document.getElementById('discountInput').value;
    if (val) {
        document.getElementById('discountText').innerText = val + '%';
        document.getElementById('bannerMsg').style.display = 'block';
        setTimeout(() => document.getElementById('bannerMsg').style.display = 'none', 2000);
    }
}
</script>
                </div>
                <div class="sale-box">
                    <h6>Special Offers</h6>
                    <ul class="offer-list">
                        <li><i class="fas fa-gift text-pink"></i> Loyal customers get extra 10% off</li>
                        <li><i class="fas fa-star text-pink"></i> Bridal packages at special rates</li>
                        <li><i class="fas fa-truck text-pink"></i> Free delivery on orders above Rs 5000</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- MESSAGES --}}
        <div class="section-card" id="messages">
            <div class="section-header">
                <h5><i class="fas fa-envelope"></i> Customer Messages</h5>
            </div>
            <div class="messages-list">
                @forelse(\App\Models\Order::whereNotNull('special_instructions')->latest()->limit(5)->get() as $msg)
                <div class="message-item">
                    <div class="message-avatar"><i class="fas fa-user"></i></div>
                    <div class="message-body">
                        <strong>{{ $msg->first_name ?? 'Customer' }} {{ $msg->last_name ?? '' }}</strong>
                        <p>{{ $msg->special_instructions }}</p>
                        <small>{{ $msg->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3">No messages yet</p>
                @endforelse
            </div>
        </div>

    </main>
</div>

<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.admin-wrapper {
    display: flex;
    min-height: 100vh;
    font-family: 'Segoe UI', sans-serif;
    background: #f8f1e9;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    min-height: 100vh;
    background: #111;
    color: #fff;
    position: fixed;
    top: 0; left: 0;
    display: flex;
    flex-direction: column;
    z-index: 100;
}

.sidebar-brand {
    padding: 24px 20px;
    font-size: 1.3rem;
    font-weight: 700;
    border-bottom: 1px solid #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.brand-icon { color: #e99ab3; font-size: 1.5rem; }

.sidebar-nav {
    padding: 16px 0;
    display: flex;
    flex-direction: column;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #aaa;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s;
    border: none;
    background: none;
    cursor: pointer;
    width: 100%;
    text-align: left;
}

.nav-item:hover, .nav-item.active {
    background: #222;
    color: #e99ab3;
}

.nav-logout { color: #ff6b6b; margin-top: auto; }

/* MAIN */
.main-content {
    margin-left: 240px;
    flex: 1;
    padding: 0 0 40px 0;
}

/* TOPBAR */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    padding: 16px 28px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 28px;
}

.page-title { font-size: 1.2rem; font-weight: 700; color: #111; }

.admin-badge {
    background: #f5d0dc;
    color: #c0607e;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

/* STATS */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    padding: 0 28px;
    margin-bottom: 24px;
}

.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    border-left: 4px solid transparent;
}

.stat-pink  { border-left-color: #e99ab3; }
.stat-yellow{ border-left-color: #f5c842; }
.stat-green { border-left-color: #4caf87; }
.stat-blue  { border-left-color: #5b9bd5; }
.stat-purple{ border-left-color: #9b7fe8; }
.stat-dark  { border-left-color: #555; }

.stat-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: #f8f1e9;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: #e99ab3;
    flex-shrink: 0;
}

.stat-info h6 { font-size: 0.78rem; color: #888; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
.stat-info h2 { font-size: 1.6rem; font-weight: 700; color: #111; }

/* CHARTS */
.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    padding: 0 28px;
    margin-bottom: 24px;
}

.chart-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
}

.chart-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #555;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* SECTIONS */
.section-card {
    background: #fff;
    border-radius: 14px;
    padding: 24px;
    margin: 0 28px 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0e8e0;
}

.section-header h5 { font-size: 1rem; font-weight: 700; color: #111; display: flex; align-items: center; gap: 8px; }
.btn-view-all { color: #e99ab3; font-size: 0.85rem; text-decoration: none; font-weight: 600; }

/* PRODUCT FORM */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.form-group label { font-size: 0.82rem; font-weight: 600; color: #555; margin-bottom: 6px; display: block; }
.form-control { border: 1px solid #e8ddd5; border-radius: 8px; padding: 8px 12px; font-size: 0.9rem; width: 100%; }
.form-control:focus { outline: none; border-color: #e99ab3; box-shadow: 0 0 0 3px rgba(233,154,179,0.15); }
.full-width { grid-column: 1 / -1; }

.size-checkboxes { display: flex; gap: 12px; flex-wrap: wrap; }
.size-label { display: flex; align-items: center; gap: 6px; font-size: 0.88rem; cursor: pointer; }

.btn-admin-pink {
    background: #e99ab3;
    color: #fff;
    border: none;
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-admin-pink:hover { background: #d985a0; }

/* TABLE */
.admin-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
.admin-table th { background: #f8f1e9; padding: 10px 14px; text-align: left; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: #888; }
.admin-table td { padding: 12px 14px; border-bottom: 1px solid #f5ede5; color: #333; }
.admin-table tr:last-child td { border-bottom: none; }

.status-badge { padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 600; }
.status-pending    { background: #fff3cd; color: #856404; }
.status-in_progress{ background: #cfe2ff; color: #0a58ca; }
.status-delivered  { background: #d1e7dd; color: #0f5132; }

.status-select { border: 1px solid #ddd; border-radius: 6px; padding: 4px 8px; font-size: 0.82rem; cursor: pointer; }
.btn-delete-order { background: none; border: 1px solid #ffcdd2; color: #e53935; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; cursor: pointer; }
.btn-delete-order:hover { background: #ffebee; }

/* SALES */
.sales-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.sale-box { background: #fdf6f0; border-radius: 10px; padding: 18px; }
.sale-box h6 { font-weight: 700; margin-bottom: 12px; color: #111; }
.flash-banner { background: linear-gradient(135deg, #e99ab3, #f5c842); color: #fff; padding: 14px 18px; border-radius: 10px; display: flex; align-items: center; gap: 10px; font-size: 0.95rem; }
.flash-icon { font-size: 1.4rem; }
.offer-list { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.offer-list li { display: flex; align-items: center; gap: 10px; font-size: 0.88rem; color: #444; }
.text-pink { color: #e99ab3; }

/* MESSAGES */
.messages-list { display: flex; flex-direction: column; gap: 12px; }
.message-item { display: flex; gap: 12px; align-items: flex-start; padding: 14px; background: #fdf6f0; border-radius: 10px; }
.message-avatar { width: 38px; height: 38px; border-radius: 50%; background: #e99ab3; color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.message-body strong { font-size: 0.88rem; color: #111; }
.message-body p { font-size: 0.85rem; color: #555; margin: 4px 0 2px; }
.message-body small { color: #aaa; font-size: 0.78rem; }

/* RESPONSIVE */
@media (max-width: 900px) {
    .sidebar { width: 60px; }
    .sidebar span, .nav-item span { display: none; }
    .main-content { margin-left: 60px; }
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .charts-grid { grid-template-columns: 1fr; }
    .form-grid { grid-template-columns: 1fr; }
    .sales-grid { grid-template-columns: 1fr; }
}
</style>

<script>
// Daily Orders Chart
const dailyCtx = document.getElementById('dailyOrdersChart').getContext('2d');
new Chart(dailyCtx, {
    type: 'bar',
    data: {
        labels: @json($dailyOrders->pluck('day')),
        datasets: [{
            label: 'Orders',
            data: @json($dailyOrders->pluck('total')),
            backgroundColor: '#e99ab3',
            borderRadius: 12,
            borderSkipped: false,
            barThickness: 40,
            minBarLength: 2,
            categoryPercentage: 1.0,
            barPercentage: 0.8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
    beginAtZero: true,
    min: 0,
    ticks: { stepSize: 1, precision: 0 },
    grid: { color: '#f0e8e0' }
},
x: {
    grid: { display: false },
    offset: false
},
           
        }
    }
});

// Monthly Sales Chart
const monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: @json($monthlySales->pluck('month')),
        datasets: [{
            label: 'Sales (Rs)',
            data: @json($monthlySales->pluck('total')),
            borderColor: '#111',
            backgroundColor: 'rgba(233,154,179,0.15)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#e99ab3',
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0 },
                grid: { color: '#f0e8e0' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endsection
