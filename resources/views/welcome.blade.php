<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keno-Becho (কেনো-বেচো) - B2B Garments Trading Platform</title>
    <link rel="icon" href="/svg/logo.png" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('/svg/logo2.svg') no-repeat center center;
            background-size: 200px;
            opacity: 0.1;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            border: 2px solid var(--primary-color);
            color: white;
        }

        .btn-primary-custom:hover {
            background: transparent;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-outline-custom {
            background: transparent;
            border: 2px solid white;
            color: white;
        }

        .btn-outline-custom:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: none;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .stats-section {
            background: var(--dark-color);
            color: white;
            padding: 4rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 2rem 1rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--accent-color);
            display: block;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem !important;
            }

            .hero-section p {
                font-size: 1.1rem !important;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#">
                <img src="/svg/logo.png" alt="Becho" height="40" class="me-2">
                <span class="branding-animation">Keno-Becho (কেনো-বেচো)</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="btn btn-primary-custom btn-custom ms-2" href="{{ route('register') }}">Get Started</a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="floating-shapes">
            <div class="shape">
                <i class="fas fa-cube fa-3x"></i>
            </div>
            <div class="shape">
                <i class="fas fa-shopping-cart fa-2x"></i>
            </div>
            <div class="shape">
                <i class="fas fa-handshake fa-3x"></i>
            </div>
        </div>

        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold text-white mb-4">
                        Bangladesh's Leading <span class="text-warning">B2B Garments</span> Trading Platform
                    </h1>
                    <p class="lead text-white-50 mb-5 fs-4">
                        Connect with verified manufacturers, wholesalers, and retailers. Trade garments in bulk with
                        competitive pricing and secure transactions.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ url('/home') }}" class="btn btn-outline-custom btn-custom">
                                <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-outline-custom btn-custom">
                                <i class="fas fa-user-plus me-2"></i>Start Trading
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-primary-custom btn-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        @endauth
                        <a href="#features" class="btn btn-outline-custom btn-custom">
                            <i class="fas fa-info-circle me-2"></i>Learn More
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="/svg/logo2.svg" alt="Becho Platform" class="img-fluid" style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <h5>Verified Suppliers</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">10K+</span>
                        <h5>Products Listed</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">₹50M+</span>
                        <h5>Trade Volume</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <h5>Customer Satisfaction</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-4 fw-bold mb-4">Why Choose Keno-Becho (কেনো-বেচো)?</h2>
                    <p class="lead text-muted">Revolutionizing B2B garments trading with modern technology and trusted
                        partnerships</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Verified Suppliers</h4>
                        <p class="text-muted">Trade with confidence knowing all our suppliers are verified and vetted
                            for quality and reliability.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Bulk Pricing</h4>
                        <p class="text-muted">Get better prices with our tiered bulk pricing system designed for
                            wholesale transactions.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-handshake fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Quotation System</h4>
                        <p class="text-muted">Request and negotiate prices directly with suppliers through our
                            integrated quotation system.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-boxes fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Stocklot Deals</h4>
                        <p class="text-muted">Find amazing deals on surplus inventory and stocklot items at discounted
                            prices.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-truck fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Order Tracking</h4>
                        <p class="text-muted">Track your orders from confirmation to delivery with real-time updates and
                            notifications.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Mobile Friendly</h4>
                        <p class="text-muted">Access the platform anywhere, anytime with our responsive design and
                            mobile optimization.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-4 fw-bold mb-4">About Keno-Becho (কেনো-বেচো)</h2>
                    <p class="lead mb-4">
                        Keno-Becho (কেনো-বেচো) is Bangladesh's premier B2B e-commerce platform specifically designed for
                        the garments industry.
                        We connect manufacturers, wholesalers, and retailers in a seamless digital marketplace.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-3"></i>Trusted by 500+ verified
                            suppliers</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-3"></i>Secure payment processing
                        </li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-3"></i>24/7 customer support</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-3"></i>Quality assurance
                            guarantee</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <img src="/svg/logo2.svg" alt="About Becho" class="img-fluid" style="max-height: 300px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">Keno-Becho (কেনো-বেচো)</h5>
                    <p class="text-muted">Bangladesh's leading B2B garments trading platform connecting manufacturers,
                        wholesalers, and retailers.</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Platform</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Products</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Suppliers</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Stocklots</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Company</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contact</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Careers</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">Contact Info</h6>
                    <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i>support@becho.com</p>
                    <p class="text-muted mb-2"><i class="fas fa-phone me-2"></i>+880 1234 567890</p>
                    <p class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Dhaka, Bangladesh</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2024 Keno-Becho. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-muted me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-muted me-3"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-muted"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>