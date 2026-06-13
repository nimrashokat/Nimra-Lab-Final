@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="text-center mb-3">
                <h2 class="fw-bold">Custom Tailoring Order</h2>
                <p class="text-muted">Fill in your details and we'll stitch your dream dress!</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success mb-3">✓ {{ session('success') }}</div>
            @endif

            {{-- Cart Items Summary - COMPACT --}}
            @php
                $cartItems = auth()->user() ? \App\Models\CartItem::where('user_id', auth()->id())->with('product')->get() : collect();
                $cartTotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
            @endphp

            @if($cartItems->count() > 0)
            <div class="cart-summary mb-3">
                <div class="cart-summary-header" onclick="toggleCart()" style="cursor:pointer;">
                    <span><i class="fa fa-shopping-bag me-2"></i> Your Items ({{ $cartItems->count() }})</span>
                    <span><strong class="text-pink">Rs {{ number_format($cartTotal) }}</strong> <i class="fa fa-chevron-down ms-2" id="cartChevron"></i></span>
                </div>
                <div id="cartItemsBody" style="display:none; padding: 12px 16px; border-top: 1px solid #f0e8e0;">
                    @foreach($cartItems as $item)
                    <div class="cart-item-row">
                        <img src="{{ $item->product->image ? Storage::url($item->product->image) : 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=50&h=50&fit=crop' }}"
                             class="cart-item-img"
                             onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=50&h=50&fit=crop'">
                        <div class="cart-item-info">
                            <strong>{{ $item->product->name }}</strong>
                            <small class="text-muted">x{{ $item->quantity }}</small>
                        </div>
                        <div class="cart-item-price">Rs {{ number_format($item->product->price * $item->quantity) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <form class="order-form" method="POST" enctype="multipart/form-data" action="{{ route('order.store') }}">
                @csrf

                {{-- Personal Info --}}
                <div class="form-section">
                    <h6 class="section-title"><i class="fa fa-user"></i> Personal Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input class="form-control" name="first_name" placeholder="e.g. Ayesha" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input class="form-control" name="last_name" placeholder="e.g. Khan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input class="form-control" type="email" name="email" placeholder="ayesha@email.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input class="form-control" name="phone" placeholder="03xx-xxxxxxx" required>
                        </div>
                    </div>
                </div>

                {{-- Order Details --}}
                <div class="form-section">
                    <h6 class="section-title"><i class="fa fa-tshirt"></i> Order Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Choose Design</label>
                            <input class="form-control" name="design_choice" placeholder="e.g. Bridal Lehenga" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Choose Size</label>
                            <select class="form-select" name="size" required>
                                <option value="">-- Select Size --</option>
                                <option>Small</option>
                                <option>Medium</option>
                                <option>Large</option>
                                <option>XL</option>
                                <option>XXL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fabric Details</label>
                            <input class="form-control" name="fabric_details" placeholder="e.g. Silk, Cotton, Chiffon" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Delivery Address</label>
                            <input class="form-control" name="delivery_address" placeholder="Full delivery address" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Special Instructions</label>
                            <textarea class="form-control" name="special_instructions" rows="2" placeholder="Any special requirements..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Upload Design Image <small class="text-muted">(optional)</small></label>
                            <input class="form-control" type="file" name="design_image" accept="image/*">
                        </div>
                    </div>
                </div>

                {{-- PAYMENT SECTION --}}
                <div class="form-section">
                    <h6 class="section-title"><i class="fa fa-credit-card"></i> Payment Method</h6>
                    <input type="hidden" name="payment_method" id="paymentMethodInput" value="">

                    <div class="payment-options">

                        {{-- Cash on Delivery --}}
                        <div class="payment-card" onclick="selectPayment('cod', this)">
                            <div class="payment-logo cod-logo">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="payment-info">
                                <strong>Cash on Delivery</strong>
                                <small>Pay when you receive your order</small>
                            </div>
                            <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                        </div>

                        {{-- JazzCash --}}
                        <div class="payment-card" onclick="selectPayment('jazzcash', this)">
                            <div class="payment-logo jazzcash-logo">
                                <span>Jazz<br>Cash</span>
                            </div>
                            <div class="payment-info">
                                <strong>JazzCash</strong>
                                <small>Mobile wallet payment</small>
                            </div>
                            <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                        </div>

                        {{-- EasyPaisa --}}
                        <div class="payment-card" onclick="selectPayment('easypaisa', this)">
                            <div class="payment-logo easypaisa-logo">
                                <span>Easy<br>Paisa</span>
                            </div>
                            <div class="payment-info">
                                <strong>EasyPaisa</strong>
                                <small>Mobile wallet payment</small>
                            </div>
                            <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                        </div>

                        {{-- UBL --}}
                        <div class="payment-card" onclick="selectPayment('ubl', this)">
                            <div class="payment-logo ubl-logo">
                                <span>UBL</span>
                            </div>
                            <div class="payment-info">
                                <strong>UBL Bank Transfer</strong>
                                <small>Direct bank transfer</small>
                            </div>
                            <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                        </div>

                    </div>

                    {{-- Payment Details Boxes --}}
                    <div id="detail-cod" class="payment-detail-box" style="display:none;">
                        <i class="fas fa-info-circle text-pink"></i>
                        <div>
                            <strong>Cash on Delivery</strong>
                            <p>Pay the full amount <strong>Rs {{ number_format($cartTotal) }}</strong> when your order arrives at your doorstep. No advance payment required!</p>
                        </div>
                    </div>

                    <div id="detail-jazzcash" class="payment-detail-box" style="display:none;">
                        <div class="payment-logo jazzcash-logo me-3" style="flex-shrink:0; width:50px; height:50px; font-size:0.6rem;">
                            <span>Jazz<br>Cash</span>
                        </div>
                        <div>
                            <strong>JazzCash Account Details</strong>
                            <table class="payment-table">
                                <tr><td>Account Name:</td><td><strong>SheStitch Official</strong></td></tr>
                                <tr><td>Account Number:</td><td><strong>0301-1234567</strong></td></tr>
                                <tr><td>Amount to Send:</td><td><strong class="text-pink">Rs {{ number_format($cartTotal) }}</strong></td></tr>
                            </table>
                            <small class="text-muted">Send payment and mention your name in description.</small>
                        </div>
                    </div>

                    <div id="detail-easypaisa" class="payment-detail-box" style="display:none;">
                        <div class="payment-logo easypaisa-logo me-3" style="flex-shrink:0; width:50px; height:50px; font-size:0.6rem;">
                            <span>Easy<br>Paisa</span>
                        </div>
                        <div>
                            <strong>EasyPaisa Account Details</strong>
                            <table class="payment-table">
                                <tr><td>Account Name:</td><td><strong>SheStitch Official</strong></td></tr>
                                <tr><td>Account Number:</td><td><strong>0311-7654321</strong></td></tr>
                                <tr><td>Amount to Send:</td><td><strong class="text-pink">Rs {{ number_format($cartTotal) }}</strong></td></tr>
                            </table>
                            <small class="text-muted">Send payment and share screenshot via WhatsApp.</small>
                        </div>
                    </div>

                    <div id="detail-ubl" class="payment-detail-box" style="display:none;">
                        <div class="payment-logo ubl-logo me-3" style="flex-shrink:0; width:50px; height:50px;">
                            <span>UBL</span>
                        </div>
                        <div>
                            <strong>UBL Bank Transfer Details</strong>
                            <table class="payment-table">
                                <tr><td>Account Name:</td><td><strong>SheStitch Pvt Ltd</strong></td></tr>
                                <tr><td>Account Number:</td><td><strong>0123456789012</strong></td></tr>
                                <tr><td>IBAN:</td><td><strong>PK36 UBL 0000 0123 4567 8901</strong></td></tr>
                                <tr><td>Branch:</td><td><strong>Main Branch, Lahore</strong></td></tr>
                                <tr><td>Amount:</td><td><strong class="text-pink">Rs {{ number_format($cartTotal) }}</strong></td></tr>
                            </table>
                            <small class="text-muted">Transfer and email receipt to: pay@shestitch.com</small>
                        </div>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-3 mt-4">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-dark px-4">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>
                    <button class="btn btn-pink px-5 btn-lg" type="submit">
                        <i class="fa fa-check me-2"></i> Place Order
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
.order-form { background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.form-section { margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #f0e8e0; }
.form-section:last-of-type { border-bottom: none; }
.section-title { font-weight: 700; color: #e99ab3; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
.form-control, .form-select { border: 1px solid #e8ddd5; border-radius: 8px; padding: 9px 12px; font-size: 0.9rem; }
.form-control:focus, .form-select:focus { border-color: #e99ab3; box-shadow: 0 0 0 3px rgba(233,154,179,0.15); outline: none; }
.form-label { font-size: 0.82rem; font-weight: 600; color: #555; margin-bottom: 5px; }
.btn-pink { background: #e99ab3; color: #fff; border: none; border-radius: 8px; font-weight: 600; }
.btn-pink:hover { background: #d985a0; color: #fff; }
.text-pink { color: #e99ab3; }

/* Cart Summary Compact */
.cart-summary { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); overflow: hidden; }
.cart-summary-header { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #fdf6f0; font-size: 0.9rem; font-weight: 600; }
.cart-item-row { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid #faf0ea; }
.cart-item-row:last-child { border-bottom: none; }
.cart-item-img { width: 44px; height: 44px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.cart-item-info { flex: 1; font-size: 0.85rem; }
.cart-item-price { font-weight: 600; font-size: 0.85rem; }

/* Payment Options */
.payment-options { display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px; }
.payment-card { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border: 2px solid #f0e8e0; border-radius: 12px; cursor: pointer; transition: all 0.2s; }
.payment-card:hover { border-color: #e99ab3; background: #fdf8fa; }
.payment-card.selected { border-color: #e99ab3; background: #fdf0f5; }
.payment-card .payment-check { margin-left: auto; color: #e99ab3; display: none; font-size: 1.1rem; }
.payment-card.selected .payment-check { display: block; }

.payment-logo { width: 56px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem; flex-shrink: 0; text-align: center; line-height: 1.2; }
.cod-logo { background: #e8f5e9; color: #2e7d32; font-size: 1.4rem; }
.jazzcash-logo { background: #c62828; color: #fff; }
.easypaisa-logo { background: #1b5e20; color: #fff; }
.ubl-logo { background: #0d47a1; color: #fff; font-size: 1rem; }

.payment-info { display: flex; flex-direction: column; gap: 2px; }
.payment-info strong { font-size: 0.9rem; color: #111; }
.payment-info small { color: #888; font-size: 0.78rem; }

.payment-detail-box { display: flex; align-items: flex-start; gap: 14px; background: #fdf6f0; border: 1px solid #f0e8e0; border-radius: 12px; padding: 16px; margin-top: 10px; }
.payment-table { width: 100%; font-size: 0.85rem; margin: 8px 0; }
.payment-table td { padding: 4px 8px 4px 0; }
.payment-table td:first-child { color: #888; width: 140px; }
</style>

<script>
function toggleCart() {
    const body = document.getElementById('cartItemsBody');
    const chevron = document.getElementById('cartChevron');
    if (body.style.display === 'none') {
        body.style.display = 'block';
        chevron.className = 'fa fa-chevron-up ms-2';
    } else {
        body.style.display = 'none';
        chevron.className = 'fa fa-chevron-down ms-2';
    }
}

function selectPayment(method, el) {
    // Remove selected from all
    document.querySelectorAll('.payment-card').forEach(c => c.classList.remove('selected'));
    // Add selected to clicked
    el.classList.add('selected');
    // Set hidden input
    document.getElementById('paymentMethodInput').value = method;
    // Hide all detail boxes
    ['cod','jazzcash','easypaisa','ubl'].forEach(m => {
        document.getElementById('detail-' + m).style.display = 'none';
    });
    // Show selected detail
    document.getElementById('detail-' + method).style.display = 'flex';
}
</script>
@endsection
