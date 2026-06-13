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
            <a href="/admin/dashboard" class="nav-item">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="/admin/products" class="nav-item active">
                <i class="fas fa-tshirt"></i> Products
            </a>
            <a href="/admin/orders" class="nav-item">
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

    {{-- MAIN CONTENT --}}
    <main class="main-content">

        <div class="topbar">
            <h4 class="page-title">Product Management</h4>
            <div class="topbar-right">
                <span class="admin-badge"><i class="fas fa-user-shield"></i> Admin</span>
            </div>
        </div>

        {{-- ADD PRODUCT FORM --}}
        <div class="section-card">
            <div class="section-header">
                <h5><i class="fas fa-plus-circle"></i> Add New Product</h5>
            </div>

            @if(session('success'))
                <div class="alert-success-box">✓ {{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="product-form">
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
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                        <input type="number" name="discount_percent" class="form-control" min="0" max="90" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Product description..."></textarea>
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
                <button type="submit" class="btn-admin-pink"><i class="fas fa-plus"></i> Add Product</button>
            </form>
        </div>

        {{-- PRODUCTS TABLE --}}
        <div class="section-card">
            <div class="section-header">
                <h5><i class="fas fa-list"></i> All Products ({{ $products->total() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Rating</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="product-thumb"
                                         onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=60&h=60&fit=crop'">
                                @else
                                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=60&h=60&fit=crop"
                                         class="product-thumb" alt="No image">
                                @endif
                            </td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>Rs {{ number_format($product->price) }}</td>
                            <td>
                                @if($product->discount_percent > 0)
                                    <span class="discount-badge">{{ $product->discount_percent }}% OFF</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($product->rating)
                                    <span class="rating-stars">★ {{ $product->rating }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:6px;">
                                    <button class="btn-edit" onclick="openEdit({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->price }}', '{{ $product->discount_percent }}', '{{ $product->rating }}', '{{ addslashes($product->description) }}', {{ $product->category_id }})">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn-delete" onclick="return confirm('Delete this product?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No products yet. Add your first product above!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $products->links() }}</div>
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

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.form-group label { font-size: 0.82rem; font-weight: 600; color: #555; margin-bottom: 6px; display: block; }
.form-control { border: 1px solid #e8ddd5; border-radius: 8px; padding: 8px 12px; font-size: 0.9rem; width: 100%; }
.form-control:focus { outline: none; border-color: #e99ab3; box-shadow: 0 0 0 3px rgba(233,154,179,0.15); }
.full-width { grid-column: 1 / -1; }
.size-checkboxes { display: flex; gap: 12px; flex-wrap: wrap; }
.size-label { display: flex; align-items: center; gap: 6px; font-size: 0.88rem; cursor: pointer; }

.btn-admin-pink { background: #e99ab3; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: background 0.2s; }
.btn-admin-pink:hover { background: #d985a0; }

.admin-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
.admin-table th { background: #f8f1e9; padding: 10px 14px; text-align: left; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: #888; }
.admin-table td { padding: 12px 14px; border-bottom: 1px solid #f5ede5; color: #333; vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }

.product-thumb { width: 56px; height: 56px; object-fit: cover; border-radius: 10px; border: 2px solid #f0e8e0; }
.discount-badge { background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 10px; font-size: 0.78rem; font-weight: 600; }
.rating-stars { color: #f5c842; font-weight: 600; }
.btn-delete { background: none; border: 1px solid #ffcdd2; color: #e53935; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 5px; }
.btn-delete:hover { background: #ffebee; }
.text-muted { color: #aaa; }
.text-center { text-align: center; }
.btn-edit { background: none; border: 1px solid #c8e6c9; color: #2e7d32; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 5px; }
.btn-edit:hover { background: #e8f5e9; }

/* MODAL */
.modal-overlay { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 999; justify-content: center; align-items: center; }
.modal-overlay.active { display: flex; }
.modal-box { background: #fff; border-radius: 16px; padding: 28px; width: 560px; max-width: 95%; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
.modal-title { font-size: 1rem; font-weight: 700; margin-bottom: 20px; color: #111; display: flex; align-items: center; gap: 8px; }
.modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px; }
.modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 16px; }
.btn-cancel { background: #f5f5f5; border: none; padding: 8px 20px; border-radius: 8px; cursor: pointer; font-size: 0.9rem; }
</style>

{{-- EDIT MODAL --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Product</h5>
        <form method="POST" id="editForm" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-grid">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" id="edit_category" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Price (Rs)</label>
                    <input type="number" name="price" id="edit_price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Rating (1-5)</label>
                    <input type="number" name="rating" id="edit_rating" class="form-control" min="1" max="5" step="0.1">
                </div>
                <div class="form-group">
                    <label>Discount %</label>
                    <input type="number" name="discount_percent" id="edit_discount" class="form-control" min="0" max="90">
                </div>
                <div class="form-group">
                    <label>New Image (optional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" id="edit_desc" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
                <button type="submit" class="btn-admin-pink"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(id, name, price, discount, rating, desc, catId) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_discount').value = discount;
    document.getElementById('edit_rating').value = rating;
    document.getElementById('edit_desc').value = desc;
    document.getElementById('edit_category').value = catId;
    document.getElementById('editForm').action = '/admin/products/' + id;
    document.getElementById('editModal').classList.add('active');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('active');
}
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEdit();
});
</script>
@endsection
