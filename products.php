<?php
include("./includes/connect.php");
include("./functions/common_functions.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover {
            background-color: #f1f5f9;
            color: #1e40af;
            padding-left: 12px;
        }
        /* Modern Product Card Styling - Matching Home Page */
        .product-card:hover .product-image { 
            transform: scale(1.05); 
        }
        .product-card:hover .product-overlay { 
            opacity: 1; 
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        /* Button UI Fixes */
        .product-overlay {
            flex-wrap: wrap;
        }
        .product-overlay a {
            min-width: fit-content;
            white-space: nowrap;
            font-size: 14px;
        }
        @media (max-width: 640px) {
            .product-overlay {
                flex-direction: column;
                gap: 8px;
            }
            .product-overlay a {
                width: 80%;
                justify-content: center;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include('./includes/header.php'); ?>

    <!-- Page Header -->
    <section class="bg-white border-b border-gray-200 py-8">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                <span class="text-[#1e40af] font-bold text-sm uppercase">
                    <?php 
                    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
                        echo "Search Results";
                    } else {
                        echo "Categories & Brands";
                    }
                    ?>
                </span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900">
                <?php 
                if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
                    echo "Search: " . htmlspecialchars($_GET['search']);
                } else {
                    echo "Browse By Category & Brand";
                }
                ?>
            </h1>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar -->
                <aside class="lg:w-64 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sticky top-24">
                        <!-- Brands -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-1 h-6 bg-[#1e40af] rounded"></div>
                                <h3 class="font-bold text-gray-900 text-lg">Brands</h3>
                            </div>
                            <ul class="space-y-2">
                                <?php
                                $brands_query = "SELECT * FROM `brands`";
                                $brands_result = mysqli_query($con, $brands_query);
                                while ($brand = mysqli_fetch_assoc($brands_result)) {
                                    $brand_id = $brand['brand_id'];
                                    $brand_title = htmlspecialchars($brand['brand_title']);
                                    echo "<li><a href='products.php?brand=$brand_id' class='sidebar-link block px-4 py-2 rounded-lg text-gray-700 hover:text-[#1e40af]'>$brand_title</a></li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="border-t border-gray-200 my-6"></div>

                        <!-- Categories -->
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-1 h-6 bg-[#1e40af] rounded"></div>
                                <h3 class="font-bold text-gray-900 text-lg">Categories</h3>
                            </div>
                            <ul class="space-y-2">
                                <?php
                                $categories_query = "SELECT * FROM `categories`";
                                $categories_result = mysqli_query($con, $categories_query);
                                while ($category = mysqli_fetch_assoc($categories_result)) {
                                    $category_id = $category['category_id'];
                                    $category_title = htmlspecialchars($category['category_title']);
                                    echo "<li><a href='products.php?category=$category_id' class='sidebar-link block px-4 py-2 rounded-lg text-gray-700 hover:text-[#1e40af]'>$category_title</a></li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </aside>

                <!-- Products Grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <?php
                        $ip = getIPAddress();
                        cart();
                        
                        // Handle search first
                        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
                            search_product();
                        } else {
                            // If no search, show regular products
                            getProduct();
                            filterCategoryProduct();
                            filterBrandProduct();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('./includes/footer.php'); ?>

    <script>
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
                    // Update cart count directly if provided
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
    </script>
</body>

</html>
