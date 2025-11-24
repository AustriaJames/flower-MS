<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bona\'s Flower Shop') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <style>
        /* Floating Chat Widget Styles */
        *{
            font-family: 'Poppins', sans-serif;
        }
        .floating-chat-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            font-family: 'Poppins', sans-serif;
        }

        /* Ensure scroll-to-top button doesn't overlap */
        #scrollToTop {
            z-index: 9998 !important;
        }

        .chat-toggle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #5D2B4C, #8B5A96);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(93, 43, 76, 0.3);
            transition: all 0.3s ease;
            position: relative;
            /* Ensure it's above other elements but below modals */
            z-index: 9999;
        }

        .chat-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(93, 43, 76, 0.4);
        }

        .chat-toggle i {
            color: white;
            font-size: 24px;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        .chat-container {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: slideInUp 0.3s ease-out;
            /* Ensure it's above other elements but below modals */
            z-index: 9999;
        }

        .chat-header {
            background: linear-gradient(135deg, #5D2B4C, #8B5A96);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header .btn-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
        }

        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8f9fa;
        }

        .chat-input {
            padding: 15px;
            border-top: 1px solid #e9ecef;
            background: white;
        }

        .chat-input .form-control {
            border-radius: 20px;
            border: 1px solid #e9ecef;
            padding: 10px 15px;
        }

        .chat-input .btn {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Message styles for floating chat */
        .floating-message {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .floating-message.outgoing {
            align-items: flex-end;
        }

        .floating-message.incoming {
            align-items: flex-start;
        }

        .floating-message-bubble {
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 18px;
            word-wrap: break-word;
        }

        .floating-message.outgoing .floating-message-bubble {
            background: linear-gradient(135deg, #5D2B4C, #8B5A96);
            color: white;
        }

        .floating-message.incoming .floating-message-bubble {
            background: white;
            color: #333;
            border: 1px solid #e9ecef;
        }

        .floating-message-time {
            font-size: 11px;
            color: #6c757d;
            margin-top: 5px;
            text-align: center;
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideOutDown {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(30px);
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

                /* Responsive */
        @media (max-width: 768px) {
            .floating-chat-widget {
                bottom: 20px;
                right: 20px;
            }

            .chat-container {
                width: 300px;
                height: 450px;
                bottom: 70px;
            }

            /* Adjust scroll-to-top button position on mobile */
            #scrollToTop {
                bottom: 20px !important;
                left: 20px !important;
                margin: 0 !important;
            }
        }

        /* Hide on mobile if needed */
        @media (max-width: 480px) {
            .floating-chat-widget {
                display: none;
            }

            /* Also hide scroll-to-top on very small screens */
            #scrollToTop {
                display: none !important;
            }
        }
        </style>
    </head>
<body>
    <div id="app">
        <!-- Top Bar -->
        <div class="top-bar py-2" style="background: #5D2B4C;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center text-white small">
                            <span class="me-3"><i class="fas fa-phone me-1"></i>+0955 644 6048</span>
                            <span class="me-3"><i class="fas fa-envelope me-1"></i>info@bonasflowershop.com</span>
                            <span><i class="fas fa-clock me-1"></i>Mon-Fri: 9AM-6PM</span>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex align-items-center justify-content-end">
                            @guest
                                <span class="text-white me-3 small">Welcome to Bona's Flower Shop</span>
                                <a href="{{ route('login') }}" class="btn btn-sm text-white border-white me-2" style="background: transparent;">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </a>
                                <a href="{{ route('register.show') }}" class="btn btn-sm text-white" style="background: #CFB8BE; border-radius: 20px; border: none;">
                                    <i class="fas fa-user-plus me-1"></i>Sign Up
                                </a>
                            @else
                                <span class="text-white me-3 small">Welcome, {{ Auth::user()->name }}</span>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-white text-decoration-none small">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forgot Password Modal -->
        <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 pb-0" style="background: #F5EEE4;">
                        <h5 class="modal-title fw-bold" id="forgotPasswordModalLabel" style="color: #5D2B4C;">Forgot Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-4 text-muted text-center">
                            Enter your email address and we will send you a link to reset your password.
                        </p>

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="forgot_email" class="form-label fw-semibold" style="color: #5D2B4C;">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #F5EEE4;">
                                        <i class="fas fa-envelope" style="color: #5D2B4C;"></i>
                                    </span>
                                    <input type="email" class="form-control border-0 @error('email') is-invalid @enderror"
                                           id="forgot_email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                           style="background: #F5EEE4; color: #5D2B4C;" placeholder="Enter your email">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px; padding: 12px;">
                                    <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                                </button>
                            </div>

                            <div class="text-center">
                                <span style="color: #5D2B4C;">Remembered your password? </span>
                                <a href="javascript:void(0)" class="text-decoration-none" style="color: #5D2B4C;" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg sticky-top" style="background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div class="container">
                <a class="navbar-brand fw-bold fs-3" href="{{ url('/') }}" style="color: #5D2B4C; white-space: nowrap;">
                    <i class="fas fa-seedling me-2"></i>Bona's Flower Shop
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border-color: #5D2B4C;">
                    <span class="navbar-toggler-icon" style="color: #5D2B4C;"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-3 me-auto align-items-lg-center">
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="{{ route('home') }}" style="color: #5D2B4C;">
                                <i class="fas fa-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #5D2B4C;">
                                <i class="fas fa-th-large me-1"></i>Flower Menu
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('products.index') }}">All Products</a></li>
                                <li><a class="dropdown-item" href="{{ route('categories.index') }}">Categories</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #5D2B4C;">
                                <i class="fas fa-calendar-alt me-1"></i>Occasions
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('occasions.index') }}">All Occasions</a></li>
                                @auth
                                    <li><a class="dropdown-item" href="{{ route('bookings.create') }}">Schedule Event</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('login') }}">Schedule Event</a></li>
                                @endauth
                            </ul>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="{{ route('orders.index') }}" style="color: #5D2B4C;">
                                <i class="fas fa-shopping-bag me-1"></i>My Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="{{ route('bookings.index') }}" style="color: #5D2B4C;">
                                <i class="fas fa-calendar-check me-1"></i>My Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="{{ route('wishlist.index') }}" style="color: #5D2B4C;">
                                <i class="fas fa-heart me-1"></i>Wishlist
                            </a>
                        </li>

                        @endauth
                    </ul>

                    <div class="d-flex align-items-center">
                        <form action="{{ route('products.search') }}" method="GET" class="input-group me-3" style="max-width: 300px; min-width: 250px;">
                            <input type="text" name="q" class="form-control border-0" placeholder="Search flowers..." style="background: #F5EEE4; border: 1px solid #CFB8BE;" value="{{ request('q') }}">
                            <button class="btn" type="submit" style="background: #5D2B4C; color: white; border: 1px solid #5D2B4C;">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>

                        <div class="d-flex align-items-center" style="white-space: nowrap;">
                            @auth
                                <!-- Cart Button -->
                                <a href="{{ route('cart.index') }}" class="btn me-2" style="background: #5D2B4C; color: white; border-radius: 8px;">
                                    <i class="fas fa-shopping-cart me-1"></i>Cart
                                    @php
                                        $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                                    @endphp
                                    @if($cartCount > 0)
                                        <span class="badge bg-light text-dark ms-1">{{ $cartCount }}</span>
                                    @endif
                                </a>

                                <!-- User Dropdown Menu -->
                                <div class="dropdown me-2">
                                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: white; color: #5D2B4C; border: 2px solid #5D2B4C; border-radius: 8px;">
                                        <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                                        <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-shopping-bag me-2"></i>My Orders</a></li>
                                        <li><a class="dropdown-item" href="{{ route('bookings.index') }}"><i class="fas fa-calendar-check me-2"></i>My Bookings</a></li>
                                        <li><a class="dropdown-item" href="{{ route('wishlist.index') }}"><i class="fas fa-heart me-2"></i>Wishlist</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <!-- Guest User Buttons -->
                             
                            @endauth

                            <!-- Track Button (Always Visible) -->
                            <a href="{{ route('tracking.index') }}" class="btn" style="background: white; color: #5D2B4C; border: 2px solid #5D2B4C; border-radius: 8px;">
                                <i class="fas fa-truck me-1"></i>Track
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>


        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 350px; max-width: 500px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border: none; border-radius: 12px;"
                 id="successAlert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-3" style="font-size: 1.2rem;"></i>
                    <div class="flex-grow-1">
                        <strong>Success!</strong><br>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 350px; max-width: 500px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border: none; border-radius: 12px;"
                 id="errorAlert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3" style="font-size: 1.2rem;"></i>
                    <div class="flex-grow-1">
                        <strong>Error!</strong><br>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 350px; max-width: 500px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border: none; border-radius: 12px;"
                 id="infoAlert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-3" style="font-size: 1.2rem;"></i>
                    <div class="flex-grow-1">
                        <strong>Information</strong><br>
                        {{ session('info') }}
                    </div>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 350px; max-width: 500px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border: none; border-radius: 12px;"
                 id="warningAlert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-3" style="font-size: 1.2rem;"></i>
                    <div class="flex-grow-1">
                        <strong>Warning</strong><br>
                        {{ session('warning') }}
                    </div>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Dynamic Notification Container -->
        <div id="notificationContainer" class="position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;"></div>

        <main>
            @yield('content')
        </main>

        <!-- Floating Chat Widget -->
        @auth
        <div id="floating-chat-widget" class="floating-chat-widget">
            <div class="chat-toggle" id="chat-toggle">
                <i class="fas fa-comments"></i>
                <span class="notification-badge" id="chat-notification-badge" style="display: none;">0</span>
            </div>

            <div class="chat-container" id="chat-container" style="display: none;">
                <div class="chat-header">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-headset text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-white">Customer Support</h6>
                            <small class="text-white-50">
                                <span class="badge bg-info ms-1" id="floating-connection-status">Real-time</span>
                            </small>
                        </div>
                    </div>
                    <button class="btn-close btn-close-white" id="chat-close"></button>
                </div>

                <div class="chat-messages" id="floating-messages-container">
                    <div class="text-center py-4">
                        <i class="fas fa-comments" style="font-size: 2rem; color: #CFB8BE;"></i>
                        <h6 class="mt-2" style="color: #5D2B4C;">Start a conversation</h6>
                        <p class="text-muted small">Send us a message and we'll get back to you as soon as possible.</p>
                    </div>
                </div>

                <div class="chat-input">
                    <form id="floating-chat-form" class="d-flex">
                        <input type="text" class="form-control me-2" id="floating-message-input" placeholder="Type your message..." required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth

        <!-- Footer -->
        <footer class="footer py-5" style="background: #5D2B4C;">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-3">Bona's Flower Shop</h5>
                        <p class="text-white-50 small mb-3">Creating magical moments with our exquisite blooms. Hand-picked, fresh, and delivered with love.</p>
                        <div class="d-flex gap-2">
                            <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h6 class="text-white mb-3">Quick Links</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white-50 text-decoration-none small">Home</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none small">Products</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none small">About us</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none small">Contact</a></li>
                            <li><a href="{{ route('tracking.index') }}" class="text-white-50 text-decoration-none small">Track Order</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h6 class="text-white mb-3">Categories</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white-50 text-decoration-none small">Bouquets</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none small">Single Flowers</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none small">Arrangements</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none small">Wedding Flowers</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none small">Gift Baskets</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h6 class="text-white mb-3">Contact Info</h6>
                        <p class="text-white-50 small mb-1">Bona's Flower Shop Silang Public Market</p>
                        <p class="text-white-50 small mb-1">+0955 644 6048</p>
                        <p class="text-white-50 small mb-1">info@bonasflowershop.com</p>
                        <p class="text-white-50 small mb-3">Mon-Fri: 9AM-6PM</p>

                      
                    </div>
                </div>

                <hr class="my-4" style="border-color: #CFB8BE;">

                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-white-50 small mb-0">© 2025 Bona's Flower Shop. All rights reserved.</p>
                    </div>
              
        </footer>

        <!-- Scroll to Top Button -->
        <button id="scrollToTop" class="btn rounded-circle position-fixed bottom-0 start-0 m-4" style="background: #5D2B4C; color: white; width: 50px; height: 50px; display: none; z-index: 9998;">
            <i class="fas fa-arrow-up"></i>
        </button>

        <style>
                                @error('last_name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold" style="color: #5D2B4C;">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #F5EEE4; color: #5D2B4C;">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input id="phone" type="tel" class="form-control border-0 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel" style="background: #F5EEE4; color: #5D2B4C;" placeholder="e.g., +63 912 345 6789" minlength="10" maxlength="20" pattern="[\+]?[0-9\s\-\(\)]+" title="Phone number can contain numbers, spaces, hyphens, and parentheses (10-20 characters)">
                                </div>
                                <small class="form-text text-muted">Format: +63 912 345 6789 or 09123456789 (10-20 characters)</small>
                                @error('phone')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold" style="color: #5D2B4C;">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #F5EEE4; color: #5D2B4C;">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="background: #F5EEE4; color: #5D2B4C;" placeholder="Enter your email address">
                                </div>
                                <small class="form-text text-muted">Enter a valid email address (e.g., user@example.com)</small>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold" style="color: #5D2B4C;">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #F5EEE4; color: #5D2B4C;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control border-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" style="background: #F5EEE4; color: #5D2B4C;" placeholder="Create a strong password" minlength="8" maxlength="255">
                                </div>
                                <small class="form-text text-muted">Minimum 8 characters with uppercase, lowercase, number, and special character</small>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold" style="color: #5D2B4C;">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #F5EEE4; color: #5D2B4C;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password_confirmation" type="password" class="form-control border-0" name="password_confirmation" required autocomplete="new-password" style="background: #F5EEE4; color: #5D2B4C;" placeholder="Confirm your password" minlength="8" maxlength="255">
                                </div>
                                <small class="form-text text-muted">Re-enter your password to confirm</small>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px; padding: 12px;">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>

                            <div class="text-center">
                                <span style="color: #5D2B4C;">Already have an account? </span>
                                <a href="#" class="text-decoration-none" style="color: #5D2B4C;" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Sign In</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <style>
    .top-bar, .special-offer {
        font-size: 0.875rem;
    }

    .navbar {
    }

    .navbar-nav .nav-link {
        position: relative;
        padding: 0.75rem 1rem !important;
        display: flex;
        align-items: center;
    }

    .navbar-nav .nav-link:hover {
        color: #8B5A8B !important;
        transform: translateY(-2px);
        background-color: rgba(93, 43, 76, 0.1);
        border-radius: 6px;
    }

    .navbar-nav .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 3px;
        background: #5D2B4C;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .navbar-nav .nav-link:hover::after {
        width: 80%;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.5rem;
        background: white;
        border: 1px solid #CFB8BE;
        margin-top: 0.5rem;
    }

    .dropdown-item {
        color: #5D2B4C;
        padding: 0.75rem 1rem;
        border-radius: 0.25rem;
        margin: 0.25rem;
    }

    .dropdown-item:hover {
        background: #F5EEE4;
        color: #5D2B4C;
        transform: translateX(5px);
        box-shadow: 0 2px 4px rgba(93, 43, 76, 0.1);
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    /* Button alignment and spacing */
    .d-flex.align-items-center {
        gap: 0.5rem;
    }

    .dropdown-menu {
        min-width: 200px;
    }

    .dropdown-item {
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #F5EEE4;
        transform: translateX(5px);
    }

    .footer a:hover {
        color: white !important;
    }

    #scrollToTop:hover {
        transform: translateY(-3px);
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .navbar-nav {
            margin: 1rem 0;
        }

        .navbar-nav .nav-link {
            padding: 0.5rem 0 !important;
            border-bottom: 1px solid #CFB8BE;
        }

        .navbar-nav .nav-link::after {
            display: none;
        }

        .d-flex.align-items-center.flex-wrap {
            margin-top: 1rem;
            justify-content: center;
        }

        .input-group {
            margin-bottom: 1rem;
            max-width: 100% !important;
            min-width: auto !important;
        }

        /* Improve button alignment on mobile */
        .d-flex.align-items-center {
            flex-direction: column;
            align-items: stretch !important;
            gap: 0.5rem;
        }

        .dropdown {
            width: 100%;
        }

        .dropdown .btn {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 767.98px) {
        .top-bar .d-flex {
            flex-direction: column;
            gap: 0.5rem;
        }

        .top-bar .text-end {
            text-align: center !important;
        }

        .special-offer {
            padding: 1rem 0;
        }

        .special-offer .d-flex {
            flex-direction: column;
            gap: 0.5rem;
        }

        /* Stack buttons vertically on very small screens */
        .d-flex.align-items-center > * {
            margin-bottom: 0.5rem;
        }

        .btn {
            width: 100%;
            margin: 0.25rem 0;
        }

        /* Ensure dropdown works properly on mobile */
        .dropdown-menu {
            position: static !important;
            float: none;
            width: 100%;
            margin-top: 0.5rem;
            box-shadow: none;
            border: 1px solid #CFB8BE;
        }
    }

    @media (max-width: 767.98px) {
        .top-bar .d-flex {
            flex-direction: column;
            gap: 0.5rem;
        }

        .top-bar .text-end {
            text-align: center !important;
        }

        .special-offer {
            padding: 1rem 0;
        }

        .special-offer .d-flex {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    </style>

    <script>
    // Notification system
    window.showNotification = function(message, type = 'success', duration = 5000) {
        const container = document.getElementById('notificationContainer');
        const notificationId = 'notification-' + Date.now();

        const alertClass = type === 'success' ? 'alert-success' :
                          type === 'error' ? 'alert-danger' :
                          type === 'warning' ? 'alert-warning' : 'alert-info';

        const iconClass = type === 'success' ? 'fa-check-circle' :
                         type === 'error' ? 'fa-exclamation-triangle' :
                         type === 'warning' ? 'fa-exclamation-circle' : 'fa-info-circle';

        const notification = document.createElement('div');
        notification.id = notificationId;
        notification.className = `alert ${alertClass} alert-dismissible fade show`;
        notification.innerHTML = `
            <i class="fas ${iconClass} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="removeNotification('${notificationId}')"></button>
        `;

        container.appendChild(notification);

        // Auto-hide after duration
        setTimeout(() => {
            removeNotification(notificationId);
        }, duration);

        return notificationId;
    };

    window.removeNotification = function(notificationId) {
        const notification = document.getElementById(notificationId);
        if (notification && notification.parentNode) {
            notification.remove();
        }
    };

    // Cart count update function
    window.updateCartCount = function() {
        // Update cart count badge
        const cartBadge = document.querySelector('.btn[href*="cart"] .badge');
        if (cartBadge) {
            const currentCount = parseInt(cartBadge.textContent) || 0;
            cartBadge.textContent = currentCount + 1;
        }

        // Update wishlist count badge
        const wishlistBadge = document.querySelector('.btn[href*="wishlist"] .badge');
        if (wishlistBadge) {
            const currentCount = parseInt(wishlistBadge.textContent) || 0;
            wishlistBadge.textContent = currentCount + 1;
        }
    };

    // Auto-hide success/error messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert && alert.parentNode) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    });

    // Enhanced alert animations
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            // Add slide-in animation
            alert.style.transform = 'translateX(100%)';
            alert.style.transition = 'transform 0.3s ease-out';

            setTimeout(function() {
                alert.style.transform = 'translateX(0)';
            }, 100);
        });
    });

    // Scroll to top functionality
    window.addEventListener('scroll', function() {
        const scrollBtn = document.getElementById('scrollToTop');
        if (window.pageYOffset > 300) {
            scrollBtn.style.display = 'block';
        } else {
            scrollBtn.style.display = 'none';
        }
    });

    document.getElementById('scrollToTop').addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.pageYOffset > 100) {
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            navbar.style.backdropFilter = 'blur(10px)';
        } else {
            navbar.style.background = 'white';
            navbar.style.backdropFilter = 'none';
        }
    });

    // Login form handling
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('loginSubmitBtn');
                const originalText = submitBtn.innerHTML;

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
                submitBtn.disabled = true;

                // Form will submit normally, but we can show loading state
                // The success/error messages will be handled by the server response
            });
        }

        // Auto-close login modal after successful login
        // Check if there's a success message in the session
        @if(session('success'))
            const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
            if (loginModal) {
                loginModal.hide();
            }
        @endif
    });
    </script>

    <!-- Floating Chat Widget JavaScript -->
    <script>
    $(document).ready(function() {
        const chatToggle = $('#chat-toggle');
        const chatContainer = $('#chat-container');
        const chatClose = $('#chat-close');
        const chatForm = $('#floating-chat-form');
        const messageInput = $('#floating-message-input');
        const messagesContainer = $('#floating-messages-container');
        const notificationBadge = $('#chat-notification-badge');

        let lastMessageId = 0;
        let unreadCount = 0;
        let isChatOpen = false;

        // Toggle chat
        chatToggle.click(function() {
            if (isChatOpen) {
                closeChat();
            } else {
                openChat();
            }
        });

        // Close chat
        chatClose.click(function() {
            closeChat();
        });

                function openChat() {
            chatContainer.show();
            isChatOpen = true;
            chatToggle.find('i').removeClass('fa-comments').addClass('fa-times');

            // Clear notification badge when chat is opened
            unreadCount = 0;
            updateNotificationBadge();

            // Mark messages as read
            markMessagesAsRead();

            // Load existing messages
            loadExistingMessages();

            // Start real-time updates
            startRealTimeUpdates();
        }

        function closeChat() {
            chatContainer.hide();
            isChatOpen = false;
            chatToggle.find('i').removeClass('fa-times').addClass('fa-comments');

            // Stop real-time updates
            stopRealTimeUpdates();
        }

        // Load existing messages
        function loadExistingMessages() {
            $.ajax({
                url: '{{ route("chat.getNewMessages") }}',
                method: 'GET',
                data: { last_message_id: 0 },
                success: function(response) {
                    if (response.success && response.new_messages.length > 0) {
                        messagesContainer.empty();
                        response.new_messages.forEach(function(msg) {
                            addMessageToUI(msg.message, msg.user_id !== {{ auth()->id() ?? 0 }}, msg.id, msg.created_at);
                            lastMessageId = Math.max(lastMessageId, msg.id);
                        });
                    }
                }
            });
        }

        // Start real-time updates
        let realTimeInterval;
        function startRealTimeUpdates() {
            realTimeInterval = setInterval(checkForNewMessages, 2000);
        }

        // Stop real-time updates
        function stopRealTimeUpdates() {
            if (realTimeInterval) {
                clearInterval(realTimeInterval);
            }
        }

        // Check for new messages
        function checkForNewMessages() {
            $.ajax({
                url: '{{ route("chat.getNewMessages") }}',
                method: 'GET',
                data: { last_message_id: lastMessageId },
                success: function(response) {
                    if (response.success && response.new_messages.length > 0) {
                        response.new_messages.forEach(function(msg) {
                            // Only add if it's not from the current user
                            if (msg.user_id !== {{ auth()->id() ?? 0 }}) {
                                addMessageToUI(msg.message, false, msg.id, msg.created_at);
                                lastMessageId = Math.max(lastMessageId, msg.id);

                                // Increment unread count if chat is closed
                                if (!isChatOpen) {
                                    unreadCount++;
                                    updateNotificationBadge();

                                    // Show notification
                                    showNotification('New message from support team');
                                }
                            }
                        });
                    }
                }
            });
        }

        // Add message to UI
        function addMessageToUI(message, isIncoming = true, messageId = null, timestamp = null) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `floating-message ${isIncoming ? 'incoming' : 'outgoing'}`;

            if (messageId) {
                messageDiv.className = `floating-message ${isIncoming ? 'incoming' : 'outgoing'}`;
            }

            const timeString = timestamp || new Date().toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });

            messageDiv.innerHTML = `
                <div class="floating-message-bubble">
                    ${message}
                </div>
                <div class="floating-message-time">
                    ${timeString}
                </div>
            `;

            messagesContainer.append(messageDiv);
            scrollToBottom();
        }

        // Scroll to bottom
        function scrollToBottom() {
            messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
            messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
        }

        // Update notification badge
        function updateNotificationBadge() {
            if (unreadCount > 0) {
                notificationBadge.text(unreadCount).show();
            } else {
                notificationBadge.hide();
            }
        }

        // Show notification
        function showNotification(message) {
            // Play notification sound
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
            audio.play().catch(e => console.log('Audio play failed:', e));

            // Browser notification
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('Bona\'s Flower Shop', {
                    body: message,
                    icon: '/favicon.ico'
                });
            }
        }

        // Handle form submission
        chatForm.submit(function(e) {
            e.preventDefault();

            const message = messageInput.val().trim();
            if (!message) return;

            // Add message to UI immediately
            addMessageToUI(message, false);

            // Clear input
            messageInput.val('');

            // Scroll to bottom
            scrollToBottom();

            // Send message to server
            $.ajax({
                url: '{{ route("chat.sendMessage") }}',
                method: 'POST',
                data: {
                    message: message,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Message sent successfully');

                        // Update last message ID
                        if (response.data && response.data.id) {
                            lastMessageId = Math.max(lastMessageId, response.data.id);
                        }

                        // Show success indicator
                        const lastMessage = messagesContainer.find('.floating-message').last();
                        const checkmark = $('<span class="text-success ms-2"><i class="fas fa-check-circle"></i></span>');
                        lastMessage.find('.floating-message-bubble').append(checkmark);

                        setTimeout(() => checkmark.remove(), 3000);
                    } else {
                        console.error('Error sending message:', response.message);
                        showError('Failed to send message. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    showError('Failed to send message. Please try again.');
                }
            });
        });

        // Show error message
        function showError(message) {
            const errorDiv = $('<div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;"><i class="fas fa-exclamation-triangle me-2"></i>' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            $('body').append(errorDiv);

            setTimeout(() => errorDiv.remove(), 5000);
        }

        // Enter key support
        messageInput.keypress(function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.submit();
            }
        });

        // Request notification permission
        if ('Notification' in window) {
            Notification.requestPermission();
        }

        // Mark messages as read
        function markMessagesAsRead() {
            $.ajax({
                url: '{{ route("chat.markAsRead") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Messages marked as read');
                    }
                }
            });
        }

        // Initialize real-time updates if user is authenticated
        @auth
            startRealTimeUpdates();
        @endauth
    });
    </script>
</body>
</html>
