<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SheStitch - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f1e9 0%, #f5d0dc 50%, #f8f1e9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-wrapper {
            display: flex;
            width: 900px;
            max-width: 95%;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        }

        /* LEFT SIDE */
        .login-left {
            flex: 1;
            background: linear-gradient(160deg, #111 0%, #2a2a2a 100%);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
        }

        .brand-logo {
            font-size: 2.5rem;
            font-weight: 800;
            color: #e99ab3;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .brand-logo span { color: #fff; }

        .brand-tagline {
            font-size: 0.9rem;
            color: #aaa;
            margin-bottom: 40px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .brand-icon-big {
            font-size: 5rem;
            color: #e99ab3;
            margin-bottom: 24px;
            opacity: 0.8;
        }

        .brand-features {
            list-style: none;
            text-align: left;
            margin-top: 30px;
        }

        .brand-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 0.88rem;
            color: #ccc;
            border-bottom: 1px solid #333;
        }

        .brand-features li:last-child { border-bottom: none; }
        .brand-features li i { color: #e99ab3; width: 16px; }

        /* RIGHT SIDE */
        .login-right {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 0.88rem;
            color: #888;
            margin-bottom: 32px;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
        }

        .form-control {
            border: 1.5px solid #e8ddd5;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #e99ab3;
            box-shadow: 0 0 0 3px rgba(233,154,179,0.15);
            outline: none;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 0.9rem;
        }

        .input-icon .form-control { padding-right: 38px; }

        .btn-login {
            background: linear-gradient(135deg, #e99ab3, #d985a0);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(233,154,179,0.4);
        }

        .divider {
            text-align: center;
            color: #ccc;
            font-size: 0.82rem;
            margin: 20px 0;
            position: relative;
        }

        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #f0e8e0;
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .register-link {
            text-align: center;
            font-size: 0.88rem;
            color: #888;
        }

        .register-link a {
            color: #e99ab3;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover { color: #d985a0; }

        .forgot-link {
            font-size: 0.82rem;
            color: #aaa;
            text-decoration: none;
            float: right;
            margin-top: 4px;
        }

        .forgot-link:hover { color: #e99ab3; }

        .alert-danger {
            background: #fff0f3;
            border: 1px solid #ffcdd2;
            color: #c62828;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            margin-bottom: 16px;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #666;
        }

        .remember-check input { accent-color: #e99ab3; }

        @media (max-width: 640px) {
            .login-left { display: none; }
            .login-right { padding: 36px 24px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- LEFT SIDE --}}
    <div class="login-left">
        <div class="brand-logo">She<span>Stitch</span></div>
        <div class="brand-tagline">Design Your Dream Dress</div>
        <div class="brand-icon-big">
            <i class="fas fa-scissors"></i>
        </div>
        <ul class="brand-features">
            <li><i class="fas fa-tshirt"></i> Custom Tailoring for Every Occasion</li>
            <li><i class="fas fa-star"></i> Premium Quality Stitching</li>
            <li><i class="fas fa-truck"></i> Fast & Reliable Delivery</li>
            <li><i class="fas fa-heart"></i> Elegant Feminine Designs</li>
            <li><i class="fas fa-shield-alt"></i> Secure & Trusted Platform</li>
        </ul>
    </div>

    {{-- RIGHT SIDE --}}
    <div class="login-right">
        <h2 class="login-title">Welcome Back! 👗</h2>
        <p class="login-subtitle">Login to your SheStitch account</p>

        {{-- Errors --}}
        @if($errors->any())
            <div class="alert-danger">
                <i class="fas fa-exclamation-circle me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success mb-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-icon">
                    <input type="email" name="email" class="form-control"
                           placeholder="yourname@email.com"
                           value="{{ old('email') }}" required autofocus>
                    <i class="fas fa-envelope"></i>
                </div>
            </div>

            <div class="mb-1">
                <label class="form-label">Password</label>
                <div class="input-icon">
                    <input type="password" name="password" class="form-control"
                           placeholder="Enter your password" required>
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            @endif

            <div class="mb-4 mt-4">
                <label class="remember-check">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>

            <div class="divider mt-4">or</div>

            <div class="register-link">
                Don't have an account?
                <a href="{{ route('register') }}">Create one now</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>
