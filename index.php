<?php
include('./includes/connect.php');
include('./functions/common_functions.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern E-Commerce - Premium Shopping Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Professional Color Scheme */
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary-color: #475569;
            --accent-color: #2563eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --gray-color: #6b7280;
            --light-gray: #f1f5f9;
            --white: #ffffff;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.6s ease-out; }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .hero-content { animation: slideInRight 0.8s ease-out 0.3s both; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .hero-product-image {
            animation: float 3s ease-in-out infinite;
        }

        /* Hero Section */
        .hero-slide { 
            display: none; 
            opacity: 0; 
            transition: opacity 0.5s ease-in-out; 
        }
        .hero-slide.active { 
            display: flex; 
            opacity: 1; 
        }
        .hero-bg-image {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: transform 10s ease-in-out;
        }
        .hero-slide.active .hero-bg-image {
            transform: scale(1.1);
        }
        .hero-overlay {
            background-color: rgba(30, 58, 138, 0.85);
        }
        .hero-overlay-2 {
            background-color: rgba(71, 85, 105, 0.85);
        }
        .hero-overlay-3 {
            background-color: rgba(37, 99, 235, 0.85);
        }

        /* Category Cards */
        .category-icon {
            background-color: var(--primary-color);
            transition: all 0.3s ease;
        }
        .category-icon.fashion { background-color: #ec4899; }
        .category-icon.electronics { background-color: #3b82f6; }
        .category-icon.beauty { background-color: #a855f7; }
        .category-icon.home { background-color: #10b981; }
        .category-icon.mobile { background-color: #2563eb; }
        .category-icon.grocery { background-color: #f59e0b; }
        .category-icon.footwear { background-color: #f97316; }
        .category-icon.sports { background-color: #14b8a6; }
        .category-card:hover .category-icon {
            transform: scale(1.1);
        }
        .category-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Product Cards */
        .product-card:hover .product-image { 
            transform: scale(1.05); 
        }
        .product-card:hover .product-overlay { 
            opacity: 1; 
        }

        /* Promotional Banners */
        .promo-banner-1 {
            background-color: #ec4899;
        }
        .promo-banner-2 {
            background-color: var(--primary-color);
        }
        .promo-banner-3 {
            background-color: #f59e0b;
        }

        /* Brand Logos */
        .brand-logo-img { 
            filter: grayscale(100%); 
            transition: all 0.3s ease; 
        }
        .brand-logo-img:hover { 
            filter: grayscale(0%); 
            transform: scale(1.1); 
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(30, 64, 175, 0.3);
        }
        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: var(--white);
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.8);
        }

        /* Back to Top */
        .back-to-top { 
            display: none; 
        }
        .back-to-top.show { 
            display: flex; 
        }

        /* Professional Sections */
        .section-bg-light {
            background-color: var(--light-gray);
        }
        .section-bg-white {
            background-color: var(--white);
        }
        .section-bg-primary {
            background-color: var(--primary-color);
        }
        .section-bg-secondary {
            background-color: var(--secondary-color);
        }

        /* Text Colors */
        .text-primary {
            color: var(--primary-color);
        }
        .text-secondary {
            color: var(--secondary-color);
        }
        .text-dark {
            color: var(--dark-color);
        }

        /* Search Bar Styling */
        .navbar-search {
            position: relative;
            width: 100%;
        }
        .navbar-search input {
            width: 100%;
            padding: 12px 60px 12px 20px;
            border: 1px solid #e5e7eb;
            border-radius: 9999px;
            background-color: #ffffff;
            font-size: 14px;
            color: var(--dark-color);
            transition: all 0.3s ease;
        }
        .navbar-search input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }
        .navbar-search input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        .navbar-search-button {
            position: absolute;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .navbar-search-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-50%) scale(1.05);
        }
        .navbar-search-button i {
            color: #ffffff;
            font-size: 16px;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Announcement Bar -->
    <div class="section-bg-primary text-white py-2 text-center text-sm">
        <div class="container mx-auto px-4">
            <p class="flex items-center justify-center gap-4 flex-wrap">
                <span><i class="fas fa-truck mr-2"></i>Free Delivery on orders over $50</span>
                <span class="hidden md:inline">|</span>
                <span><i class="fas fa-headset mr-2"></i>24/7 Customer Support</span>
                <span class="hidden md:inline">|</span>
                <span><i class="fas fa-shield-alt mr-2"></i>Secure Payment</span>
            </p>
        </div>
    </div>

    <!-- Dynamic Header / Navbar -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="index.php" class="text-3xl font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>A1 Store
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors border-b-2 border-indigo-600 pb-1">Home</a>
                    <a href="products.php" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Shop</a>
                    <a href="products.php" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Categories</a>
                    <a href="about.php" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">About Us</a>
                    <a href="contact.php" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Contact</a>
                </div>

                <!-- Search Bar -->
                <div class="hidden md:flex items-center flex-1 max-w-md mx-8">
                    <form method="GET" action="products.php" class="navbar-search">
                        <input type="search" name="search" placeholder="Search products..." 
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                            autocomplete="off">
                        <button type="submit" class="navbar-search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Icons -->
                <div class="flex items-center space-x-4">
                    <!-- User Account -->
                    <div class="relative group">
                        <a href="<?php echo isset($_SESSION['username']) ? 'users_area/profile.php' : 'users_area/user_login.php'; ?>" 
                           class="text-gray-700 hover:text-indigo-600 transition-colors p-2">
                            <i class="fas fa-user text-xl"></i>
                        </a>
                        <?php if(!isset($_SESSION['username'])): ?>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                            <a href="users_area/user_login.php" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Login</a>
                            <a href="users_area/user_registration.php" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Register</a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Wishlist -->
                    <a href="#" class="text-gray-700 hover:text-indigo-600 transition-colors p-2 relative">
                        <i class="fas fa-heart text-xl"></i>
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </a>

                    <!-- Cart -->
                    <a href="cart.php" class="text-gray-700 hover:text-indigo-600 transition-colors p-2 relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="absolute top-0 right-0 section-bg-primary text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            <?php cart_item(); ?>
                        </span>
                    </a>

                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="lg:hidden text-gray-700 hover:text-indigo-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden lg:hidden mt-4 pb-4 border-t border-gray-200">
                <div class="flex flex-col space-y-3 mt-4">
                    <a href="index.php" class="text-gray-700 hover:text-indigo-600 font-medium">Home</a>
                    <a href="products.php" class="text-gray-700 hover:text-indigo-600 font-medium">Shop</a>
                    <a href="products.php" class="text-gray-700 hover:text-indigo-600 font-medium">Categories</a>
                    <a href="about.php" class="text-gray-700 hover:text-indigo-600 font-medium">About Us</a>
                    <a href="contact.php" class="text-gray-700 hover:text-indigo-600 font-medium">Contact</a>
                    <form method="GET" action="products.php" class="navbar-search mt-2">
                        <input type="search" name="search" placeholder="Search products..." 
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                            autocomplete="off">
                        <button type="submit" class="navbar-search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="heroSection" class="relative h-[600px] md:h-[700px] lg:h-[800px] overflow-hidden">
        <?php
        $hero_slides = [
            [
                'title' => 'Summer Collection 2024', 
                'subtitle' => 'Summer Essentials - Up to 50% OFF', 
                'cta' => 'Discover', 
                'bg_image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80',
                'overlay' => 'hero-overlay'
            ],
            [
                'title' => 'New Arrivals', 
                'subtitle' => 'Latest Fashion Trends - Limited Time Offer', 
                'cta' => 'Shop Now', 
                'bg_image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1920&q=80',
                'overlay' => 'hero-overlay-2'
            ],
            [
                'title' => 'Tech Essentials', 
                'subtitle' => 'Premium Electronics - Best Deals', 
                'cta' => 'Explore', 
                'bg_image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=1920&q=80',
                'overlay' => 'hero-overlay-3'
            ]
        ];
        foreach($hero_slides as $index => $slide):
        ?>
        <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?> absolute inset-0 flex items-center">
            <!-- Background Image -->
            <div class="absolute inset-0 hero-bg-image" style="background-image: url('<?php echo $slide['bg_image']; ?>');">
                <div class="absolute inset-0 <?php echo $slide['overlay']; ?>"></div>
            </div>
            
            <!-- Content -->
            <div class="container mx-auto px-4 md:px-8 relative z-10 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <!-- Left Side - Text Content -->
                    <div class="text-white max-w-2xl hero-content">
                        <span class="inline-block bg-white/25 backdrop-blur-md px-5 py-2.5 rounded-full text-sm font-bold mb-6 border border-white/30 shadow-lg">
                            <i class="fas fa-tag mr-2"></i>50% OFF
                        </span>
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight animate-fade-in drop-shadow-2xl">
                            <?php echo $slide['title']; ?>
                        </h1>
                        <p class="text-xl md:text-2xl lg:text-3xl mb-8 text-white/95 font-medium drop-shadow-lg">
                            <?php echo $slide['subtitle']; ?>
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="products.php" class="btn-primary px-8 py-4 rounded-xl font-bold text-lg text-center shadow-2xl">
                                <i class="fas fa-shopping-bag mr-2"></i><?php echo $slide['cta']; ?>
                            </a>
                            <a href="products.php" class="btn-secondary px-8 py-4 rounded-xl font-bold text-lg text-center shadow-xl">
                                <i class="fas fa-eye mr-2"></i>View Offers
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right Side - Product Image (for first slide) -->
                    <?php if($index === 0): ?>
                    <div class="hidden lg:block relative">
                        <div class="relative hero-product-image">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80" 
                                 alt="Summer Collection" 
                                 class="w-full h-auto rounded-2xl shadow-2xl object-cover transform hover:scale-105 transition-transform duration-500"
                                 style="max-height: 600px;"
                                 loading="lazy">
                            <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl p-6 shadow-2xl transform hover:scale-110 transition-transform">
                                <div class="text-center">
                                    <p class="text-gray-500 text-sm mb-1">Starting at</p>
                                    <p class="text-3xl font-bold text-indigo-600">$29.99</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif($index === 1): ?>
                    <div class="hidden lg:block relative">
                        <div class="relative hero-product-image">
                            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=800&q=80" 
                                 alt="Fashion Trends" 
                                 class="w-full h-auto rounded-2xl shadow-2xl object-cover transform hover:scale-105 transition-transform duration-500"
                                 style="max-height: 600px;"
                                 loading="lazy">
                            <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl p-6 shadow-2xl transform hover:scale-110 transition-transform">
                                <div class="text-center">
                                    <p class="text-gray-500 text-sm mb-1">New Collection</p>
                                    <p class="text-3xl font-bold text-pink-600">$49.99</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="hidden lg:block relative">
                        <div class="relative hero-product-image">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80" 
                                 alt="Tech Essentials" 
                                 class="w-full h-auto rounded-2xl shadow-2xl object-cover transform hover:scale-105 transition-transform duration-500"
                                 style="max-height: 600px;"
                                 loading="lazy">
                            <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl p-6 shadow-2xl transform hover:scale-110 transition-transform">
                                <div class="text-center">
                                    <p class="text-gray-500 text-sm mb-1">Best Price</p>
                                    <p class="text-3xl font-bold text-purple-600">$199.99</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Indicators -->
        <div class="absolute bottom-6 md:bottom-8 left-1/2 transform -translate-x-1/2 flex gap-3 z-20">
            <?php for($i = 0; $i < count($hero_slides); $i++): ?>
            <button class="hero-indicator w-3 h-3 md:w-4 md:h-4 rounded-full <?php echo $i === 0 ? 'bg-white shadow-lg' : 'bg-white/50'; ?> transition-all hover:bg-white hover:scale-125" data-slide="<?php echo $i; ?>"></button>
            <?php endfor; ?>
        </div>
        
        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-20 left-1/2 transform -translate-x-1/2 z-20 hidden md:block animate-bounce">
            <a href="#categories" class="text-white/80 hover:text-white transition-colors">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-1 h-9 section-bg-primary rounded"></div>
                <span class="text-primary font-bold text-sm uppercase">Categories</span>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mb-12">Browse By Category</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <?php
                $categories = [
                    ['name' => 'Fashion', 'icon' => 'tshirt', 'class' => 'fashion'],
                    ['name' => 'Electronics', 'icon' => 'laptop', 'class' => 'electronics'],
                    ['name' => 'Beauty', 'icon' => 'spa', 'class' => 'beauty'],
                    ['name' => 'Home', 'icon' => 'home', 'class' => 'home'],
                    ['name' => 'Mobile', 'icon' => 'mobile-alt', 'class' => 'mobile'],
                    ['name' => 'Grocery', 'icon' => 'shopping-basket', 'class' => 'grocery'],
                    ['name' => 'Footwear', 'icon' => 'shoe-prints', 'class' => 'footwear'],
                    ['name' => 'Sports', 'icon' => 'dumbbell', 'class' => 'sports']
                ];
                foreach($categories as $cat):
                ?>
                <a href="products.php" class="category-card group bg-white border-2 border-gray-200 rounded-2xl p-6 text-center transition-all duration-300 hover:border-indigo-600">
                    <div class="category-icon category-icon-<?php echo $cat['class']; ?> w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-<?php echo $cat['icon']; ?> text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors"><?php echo $cat['name']; ?></h3>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-16 section-bg-light">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-1 h-9 section-bg-primary rounded"></div>
                <span class="text-primary font-bold text-sm uppercase">Our Products</span>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mb-12">Featured Products</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <?php
                $featured_query = "SELECT * FROM `products` ORDER BY rand() LIMIT 8";
                $featured_result = mysqli_query($con, $featured_query);
                while($product = mysqli_fetch_assoc($featured_result)):
                    $product_id = $product['product_id'];
                    $product_title = htmlspecialchars($product['product_title']);
                    $product_image = $product['product_image_one'];
                    $product_price = $product['product_price'];
                    $discount_price = number_format($product_price * 0.85, 2);
                    // Get image URL (handles both local files and online URLs)
                    $image_url = (strpos($product_image, 'http://') === 0 || strpos($product_image, 'https://') === 0) ? $product_image : './admin/product_images/' . $product_image;
                ?>
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group">
                    <div class="relative overflow-hidden bg-gray-100 h-64">
                        <img src="<?php echo $image_url; ?>" 
                             alt="<?php echo $product_title; ?>" 
                             class="product-image w-full h-full object-contain p-4 transition-transform duration-300">
                        <div class="product-overlay absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300 flex items-center justify-center gap-2">
                            <a href="products.php?add_to_cart=<?php echo $product_id; ?>" 
                               class="bg-white text-primary px-6 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition-all transform hover:scale-105">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </a>
                            <a href="product_details.php?product_id=<?php echo $product_id; ?>" 
                               class="btn-primary px-6 py-3 rounded-lg font-semibold transform hover:scale-105">
                                View Details
                            </a>
                        </div>
                        <span class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">-15%</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2"><?php echo $product_title; ?></h3>
                        <div class="flex items-center gap-1 mb-3">
                            <?php for($i = 0; $i < 5; $i++): ?>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <?php endfor; ?>
                            <span class="text-gray-500 text-sm ml-2">(35)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-primary font-bold text-xl">$<?php echo $discount_price; ?></span>
                            <span class="text-gray-400 line-through">$<?php echo $product_price; ?></span>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <div class="text-center">
                <a href="products.php" class="inline-block btn-primary px-8 py-4 rounded-lg font-semibold transform hover:scale-105 shadow-lg">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- Offer Banners / Promotional Posters -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Banner 1 -->
                <div class="relative h-64 rounded-2xl overflow-hidden group cursor-pointer">
                    <div class="absolute inset-0 promo-banner-1"></div>
                    <div class="absolute inset-0 flex flex-col justify-center items-start p-8 text-white z-10">
                        <span class="text-sm font-semibold mb-2">Special Offer</span>
                        <h3 class="text-3xl font-bold mb-4">Buy 1 Get 1 Free</h3>
                        <p class="mb-6 text-white/90">On selected items</p>
                        <a href="products.php" class="bg-white text-pink-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all transform group-hover:scale-105">
                            Shop Now
                        </a>
                    </div>
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-all"></div>
                </div>

                <!-- Banner 2 -->
                <div class="relative h-64 rounded-2xl overflow-hidden group cursor-pointer">
                    <div class="absolute inset-0 promo-banner-2"></div>
                    <div class="absolute inset-0 flex flex-col justify-center items-start p-8 text-white z-10">
                        <span class="text-sm font-semibold mb-2">Mega Sale</span>
                        <h3 class="text-3xl font-bold mb-4">Mega Festive Sale</h3>
                        <p class="mb-6 text-white/90">Up to 70% OFF</p>
                        <a href="products.php" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all transform group-hover:scale-105">
                            Explore Deals
                        </a>
                    </div>
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-all"></div>
                </div>

                <!-- Banner 3 -->
                <div class="relative h-64 rounded-2xl overflow-hidden group cursor-pointer">
                    <div class="absolute inset-0 promo-banner-3"></div>
                    <div class="absolute inset-0 flex flex-col justify-center items-start p-8 text-white z-10">
                        <span class="text-sm font-semibold mb-2">Exclusive</span>
                        <h3 class="text-3xl font-bold mb-4">Brand Deals</h3>
                        <p class="mb-6 text-white/90">Premium brands at best prices</p>
                        <a href="products.php" class="bg-white text-orange-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all transform group-hover:scale-105">
                            Discover
                        </a>
                    </div>
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-all"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Products Section -->
    <section class="py-16 section-bg-light">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-1 h-9 section-bg-primary rounded"></div>
                        <span class="text-primary font-bold text-sm uppercase">Trending</span>
                    </div>
                    <h2 class="text-4xl font-bold text-gray-900">Trending Now</h2>
                </div>
                <div class="hidden md:flex gap-2">
                    <button id="trendingPrev" class="bg-white border-2 border-gray-300 p-3 rounded-full hover:border-indigo-600 hover:text-indigo-600 transition-all">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="trendingNext" class="bg-white border-2 border-gray-300 p-3 rounded-full hover:border-indigo-600 hover:text-indigo-600 transition-all">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <div id="trendingCarousel" class="overflow-hidden">
                <div class="flex gap-6 transition-transform duration-500 ease-in-out">
                    <?php
                    $trending_query = "SELECT * FROM `products` ORDER BY rand() LIMIT 10";
                    $trending_result = mysqli_query($con, $trending_query);
                    while($product = mysqli_fetch_assoc($trending_result)):
                        $product_id = $product['product_id'];
                        $product_title = htmlspecialchars($product['product_title']);
                        $product_image = $product['product_image_one'];
                        $product_price = $product['product_price'];
                        $image_url = (strpos($product_image, 'http://') === 0 || strpos($product_image, 'https://') === 0) ? $product_image : './admin/product_images/' . $product_image;
                    ?>
                    <div class="min-w-[280px] bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                        <div class="relative h-64 bg-gray-100 overflow-hidden">
                            <img src="<?php echo $image_url; ?>" 
                                 alt="<?php echo $product_title; ?>" 
                                 class="w-full h-full object-contain p-4">
                            <a href="products.php?add_to_cart=<?php echo $product_id; ?>" 
                               class="absolute bottom-4 left-1/2 transform -translate-x-1/2 section-bg-primary text-white px-6 py-2 rounded-lg font-semibold opacity-0 hover:opacity-100 transition-opacity">
                                Add to Cart
                            </a>
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2"><?php echo $product_title; ?></h3>
                            <div class="flex items-center gap-1 mb-2">
                                <?php for($i = 0; $i < 5; $i++): ?>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-primary font-bold text-lg">$<?php echo $product_price; ?></span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Selling Section -->
    <section class="py-16 section-bg-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-1 h-9 section-bg-primary rounded"></div>
                <span class="text-primary font-bold text-sm uppercase">Best Sellers</span>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mb-12">Best Selling Products</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                $bestseller_query = "SELECT * FROM `products` ORDER BY rand() LIMIT 4";
                $bestseller_result = mysqli_query($con, $bestseller_query);
                $badges = ['Best Seller', 'Hot', 'New', 'Limited Stock'];
                $badge_index = 0;
                while($product = mysqli_fetch_assoc($bestseller_result)):
                    $product_id = $product['product_id'];
                    $product_title = htmlspecialchars($product['product_title']);
                    $product_image = $product['product_image_one'];
                    $product_price = $product['product_price'];
                    $badge = $badges[$badge_index % count($badges)];
                    $badge_index++;
                    $image_url = (strpos($product_image, 'http://') === 0 || strpos($product_image, 'https://') === 0) ? $product_image : './admin/product_images/' . $product_image;
                ?>
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 relative">
                    <div class="absolute top-4 left-4 z-10">
                        <span class="bg-<?php echo $badge === 'Hot' ? 'red' : ($badge === 'New' ? 'green' : ($badge === 'Limited Stock' ? 'orange' : 'indigo')); ?>-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            <?php echo $badge; ?>
                        </span>
                    </div>
                    <div class="relative h-64 bg-gray-100 overflow-hidden">
                        <img src="<?php echo $image_url; ?>" 
                             alt="<?php echo $product_title; ?>" 
                             class="w-full h-full object-contain p-4">
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-800 mb-2"><?php echo $product_title; ?></h3>
                        <div class="flex items-center gap-1 mb-3">
                            <?php for($i = 0; $i < 5; $i++): ?>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <?php endfor; ?>
                            <span class="text-gray-500 text-sm ml-2">(120)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-primary font-bold text-xl">$<?php echo $product_price; ?></span>
                            <a href="products.php?add_to_cart=<?php echo $product_id; ?>" 
                               class="section-bg-primary text-white px-4 py-2 rounded-lg hover:opacity-90 transition-all">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 section-bg-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="w-1 h-9 section-bg-primary rounded"></div>
                    <span class="text-primary font-bold text-sm uppercase">Testimonials</span>
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
            </div>
            
            <div id="testimonialsCarousel" class="overflow-hidden">
                <div class="flex gap-6 transition-transform duration-500 ease-in-out">
                    <?php
                    $testimonials = [
                        ['name' => 'Sarah Johnson', 'rating' => 5, 'text' => 'Amazing quality products and fast delivery! Highly recommended.', 'image' => 'https://ui-avatars.com/api/?name=Sarah+Johnson&background=indigo&color=fff'],
                        ['name' => 'Michael Chen', 'rating' => 5, 'text' => 'Best shopping experience ever. Great customer service!', 'image' => 'https://ui-avatars.com/api/?name=Michael+Chen&background=purple&color=fff'],
                        ['name' => 'Emily Davis', 'rating' => 5, 'text' => 'Love the variety and competitive prices. Will shop again!', 'image' => 'https://ui-avatars.com/api/?name=Emily+Davis&background=pink&color=fff'],
                        ['name' => 'David Wilson', 'rating' => 5, 'text' => 'Excellent product quality and seamless checkout process.', 'image' => 'https://ui-avatars.com/api/?name=David+Wilson&background=blue&color=fff']
                    ];
                    foreach($testimonials as $testimonial):
                    ?>
                    <div class="min-w-[350px] bg-white rounded-2xl p-8 shadow-lg">
                        <div class="flex items-center gap-1 mb-4">
                            <?php for($i = 0; $i < $testimonial['rating']; $i++): ?>
                            <i class="fas fa-star text-yellow-400"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-gray-700 mb-6 italic">"<?php echo $testimonial['text']; ?>"</p>
                        <div class="flex items-center gap-4">
                            <img src="<?php echo $testimonial['image']; ?>" alt="<?php echo $testimonial['name']; ?>" class="w-12 h-12 rounded-full">
                            <div>
                                <h4 class="font-semibold text-gray-800"><?php echo $testimonial['name']; ?></h4>
                                <p class="text-gray-500 text-sm">Verified Customer</p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Brand Logos Section -->
    <section class="py-16 section-bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Trusted Partners</h2>
                <p class="text-gray-600">Shop from your favorite brands</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-8 items-center">
                <?php
                $brands = ['Nike', 'Adidas', 'Apple', 'Samsung', 'Sony', 'LG', 'Canon', 'HP'];
                foreach($brands as $brand):
                ?>
                <div class="flex items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                    <div class="brand-logo-img text-2xl font-bold text-gray-400">
                        <?php echo $brand; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Newsletter Subscription Section -->
    <section class="py-16 section-bg-primary">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-4xl font-bold text-white mb-4">Subscribe to Our Newsletter</h2>
                <p class="text-white/90 mb-8 text-lg">Get the latest updates on new products and exclusive offers</p>
                <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto" onsubmit="event.preventDefault(); showToast('Thank you for subscribing!', 'success');">
                    <input type="email" placeholder="Enter your email address" 
                           class="flex-1 px-6 py-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-white">
                    <button type="submit" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <!-- About Company -->
                <div>
                    <h3 class="text-white text-xl font-bold mb-4">A1 Store</h3>
                    <p class="mb-4 text-gray-400">Your trusted online shopping destination. Quality products at great prices with exceptional customer service.</p>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-youtube text-xl"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="products.php" class="hover:text-white transition-colors">Shop</a></li>
                        <li><a href="about.php" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="contact.php" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="cart.php" class="hover:text-white transition-colors">Cart</a></li>
                    </ul>
                </div>

                <!-- Customer Support -->
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Customer Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Returns & Refunds</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Order Tracking</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Shipping Info</a></li>
                        <li><a href="contact.php" class="hover:text-white transition-colors">Contact Support</a></li>
                    </ul>
                </div>

                <!-- Newsletter Box -->
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Stay Connected</h3>
                    <p class="mb-4 text-gray-400">Subscribe to get updates on new products and special offers.</p>
                    <form class="flex flex-col gap-2" onsubmit="event.preventDefault(); showToast('Thank you for subscribing!', 'success');">
                        <input type="email" placeholder="Your email" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 focus:outline-none focus:border-indigo-500">
                        <button type="submit" class="section-bg-primary text-white px-4 py-2 rounded-lg hover:opacity-90 transition-all">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Payment Icons & Copyright -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex gap-4">
                        <i class="fab fa-cc-visa text-3xl text-gray-400"></i>
                        <i class="fab fa-cc-mastercard text-3xl text-gray-400"></i>
                        <i class="fab fa-cc-paypal text-3xl text-gray-400"></i>
                        <i class="fab fa-cc-amex text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-400 text-sm">© <?php echo date('Y'); ?> A1 Store. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top fixed bottom-8 right-8 section-bg-primary text-white p-4 rounded-full shadow-lg hover:opacity-90 transition-all z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Mobile Menu Toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        // Hero Carousel
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const indicators = document.querySelectorAll('.hero-indicator');
        const totalSlides = slides.length;

        function showSlide(index) {
            // Remove active class with fade out
            slides.forEach((slide, i) => {
                if (slide.classList.contains('active')) {
                    slide.style.opacity = '0';
                    setTimeout(() => {
                        slide.classList.remove('active');
                    }, 300);
                }
            });
            
            indicators.forEach(indicator => {
                indicator.classList.remove('bg-white', 'shadow-lg');
                indicator.classList.add('bg-white/50');
            });
            
            // Add active class with fade in
            setTimeout(() => {
                slides[index].classList.add('active');
                slides[index].style.opacity = '0';
                setTimeout(() => {
                    slides[index].style.opacity = '1';
                }, 50);
                
                indicators[index].classList.add('bg-white', 'shadow-lg');
                indicators[index].classList.remove('bg-white/50');
            }, 300);
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });

        // Auto-slide hero carousel
        let autoSlideInterval = setInterval(nextSlide, 6000);
        
        // Pause on hover
        const heroSection = document.getElementById('heroSection');
        if (heroSection) {
            heroSection.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
            heroSection.addEventListener('mouseleave', () => {
                autoSlideInterval = setInterval(nextSlide, 6000);
            });
        }

        // Trending Products Carousel
        let trendingPosition = 0;
        const trendingCarousel = document.getElementById('trendingCarousel');
        const trendingItems = trendingCarousel.querySelector('.flex');
        const itemWidth = 280 + 24; // width + gap

        document.getElementById('trendingNext').addEventListener('click', () => {
            trendingPosition = Math.max(trendingPosition - itemWidth, -(trendingItems.scrollWidth - itemWidth));
            trendingItems.style.transform = `translateX(${trendingPosition}px)`;
        });

        document.getElementById('trendingPrev').addEventListener('click', () => {
            trendingPosition = Math.min(trendingPosition + itemWidth, 0);
            trendingItems.style.transform = `translateX(${trendingPosition}px)`;
        });

        // Testimonials Carousel
        let testimonialPosition = 0;
        const testimonialsCarousel = document.getElementById('testimonialsCarousel');
        const testimonialItems = testimonialsCarousel.querySelector('.flex');
        const testimonialItemWidth = 350 + 24;

        setInterval(() => {
            testimonialPosition -= testimonialItemWidth;
            if (Math.abs(testimonialPosition) >= testimonialItems.scrollWidth - testimonialItemWidth) {
                testimonialPosition = 0;
            }
            testimonialItems.style.transform = `translateX(${testimonialPosition}px)`;
        }, 3000);

        // Back to Top Button
        const backToTopBtn = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
        
        // Global function to add to cart via AJAX
        function addToCartAjax(productId) {
            const formData = new FormData();
            formData.append('product_id', productId);
            
            fetch('add_to_cart_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    // Update cart count directly if provided, otherwise use updateHeaderCounts
                    if (data.cart_count !== undefined) {
                        const cartCountEl = document.getElementById('cart-count-header');
                        if (cartCountEl) {
                            cartCountEl.textContent = data.cart_count;
                        }
                    }
                    // Also call updateHeaderCounts to ensure both counts are updated
                    if (typeof updateHeaderCounts === 'function') {
                        updateHeaderCounts();
                    }
                } else {
                    showToast(data.message, 'warning');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred', 'error');
            });
        }
        
        // Global wishlist function for product cards
        function addToWishlistQuick(productId) {
            const formData = new FormData();
            formData.append('action', 'add_to_wishlist');
            formData.append('product_id', productId);
            
            fetch('add_to_wishlist.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Added to wishlist!', 'success');
                    // Update wishlist count directly if provided
                    if (data.wishlist_count !== undefined) {
                        const wishlistCountEl = document.getElementById('wishlist-count-header');
                        if (wishlistCountEl) {
                            wishlistCountEl.textContent = data.wishlist_count;
                        }
                    }
                    // Also call updateHeaderCounts to ensure both counts are updated
                    if (typeof updateHeaderCounts === 'function') {
                        updateHeaderCounts();
                    }
                } else {
                    if (data.message.includes('already')) {
                        showToast('Already in wishlist', 'info');
                    } else {
                        showToast(data.message || 'Failed to add to wishlist', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred', 'error');
            });
        }
    </script>
</body>

</html>
