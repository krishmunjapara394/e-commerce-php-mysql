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
    <title>My Wishlist - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <?php include('./includes/header.php'); ?>

    <!-- Wishlist Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                <span class="text-[#1e40af] font-bold text-sm uppercase">My Wishlist</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-12">My Wishlist</h1>

            <?php
            $getIpAddress = getIPAddress();
            $user_id = isset($_SESSION['username']) ? getUserId() : 0;
            
            // Check if wishlist table exists
            $check_table = "SHOW TABLES LIKE 'wishlist'";
            $table_exists = mysqli_query($con, $check_table);
            
            if (mysqli_num_rows($table_exists) > 0) {
                // Get wishlist items
                if ($user_id > 0) {
                    $wishlist_query = "SELECT w.*, p.* FROM `wishlist` w 
                                      JOIN `products` p ON w.product_id = p.product_id 
                                      WHERE w.user_id = $user_id 
                                      ORDER BY w.created_at DESC";
                } else {
                    $wishlist_query = "SELECT w.*, p.* FROM `wishlist` w 
                                      JOIN `products` p ON w.product_id = p.product_id 
                                      WHERE w.ip_address = '$getIpAddress' AND w.user_id = 0 
                                      ORDER BY w.created_at DESC";
                }
                
                $wishlist_result = mysqli_query($con, $wishlist_query);
                $wishlist_count = mysqli_num_rows($wishlist_result);
                
                if ($wishlist_count > 0) {
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php
                while ($row = mysqli_fetch_array($wishlist_result)) {
                    $product_id = $row['product_id'];
                    $product_title = htmlspecialchars($row['product_title']);
                    $product_image_one = $row['product_image_one'];
                    $product_price = $row['product_price'];
                    $product_description = htmlspecialchars($row['product_description']);
                    $discount_price = number_format($product_price * 0.85, 2);
                    $image_url = (strpos($product_image_one, 'http://') === 0 || strpos($product_image_one, 'https://') === 0) ? $product_image_one : './admin/product_images/' . $product_image_one;
                ?>
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group" data-product-id="<?php echo $product_id; ?>">
                    <div class="relative overflow-hidden bg-gray-100 h-64">
                        <img src="<?php echo $image_url; ?>" 
                             alt="<?php echo $product_title; ?>" 
                             class="product-image w-full h-full object-contain p-4 transition-transform duration-300">
                        <div class="product-overlay absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300 flex items-center justify-center gap-3 px-4">
                            <a href="#" onclick="addToCartAjax(<?php echo $product_id; ?>); return false;" 
                               class="bg-white text-[#1e40af] px-5 py-2.5 rounded-lg font-semibold hover:bg-[#1e40af] hover:text-white transition-all transform hover:scale-105 whitespace-nowrap flex items-center gap-2 shadow-lg">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="text-sm">Add to Cart</span>
                            </a>
                            <a href="product_details.php?product_id=<?php echo $product_id; ?>" 
                               class="bg-[#1e40af] text-white px-5 py-2.5 rounded-lg font-semibold transform hover:scale-105 whitespace-nowrap flex items-center gap-2 shadow-lg hover:bg-[#1e3a8a] transition-all">
                                <span class="text-sm">View Details</span>
                            </a>
                        </div>
                        <button onclick="removeFromWishlist(<?php echo $product_id; ?>)" 
                                class="absolute top-4 right-4 bg-white text-red-500 w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-lg z-10">
                            <i class="fas fa-heart"></i>
                        </button>
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
                            <span class="text-[#1e40af] font-bold text-xl">$<?php echo $discount_price; ?></span>
                            <span class="text-gray-400 line-through">$<?php echo $product_price; ?></span>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
            <?php
                } else {
            ?>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-16 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-gray-400 text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Your Wishlist is Empty</h2>
                <p class="text-gray-600 mb-8">Start adding products you love to your wishlist!</p>
                <a href="index.php" 
                   class="inline-block bg-[#1e40af] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                </a>
            </div>
            <?php
                }
            } else {
            ?>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-16 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-gray-400 text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Your Wishlist is Empty</h2>
                <p class="text-gray-600 mb-8">Start adding products you love to your wishlist!</p>
                <a href="index.php" 
                   class="inline-block bg-[#1e40af] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                </a>
            </div>
            <?php
            }
            ?>
        </div>
    </section>

    <?php include('./includes/footer.php'); ?>

    <style>
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
    </style>

    <script>
        // Global function to add to cart via AJAX (if not already defined)
        if (typeof addToCartAjax === 'undefined') {
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
        }
        
        function removeFromWishlist(productId) {
            if (!confirm('Are you sure you want to remove this item from your wishlist?')) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'remove_from_wishlist');
            formData.append('product_id', productId);

            fetch('add_to_wishlist.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove row from page
                    const row = document.querySelector(`[data-product-id="${productId}"]`);
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            // Check if wishlist is empty
                            const wishlistGrid = document.querySelector('.grid');
                            if (wishlistGrid && wishlistGrid.children.length === 0) {
                                location.reload(); // Show empty state
                            }
                        }, 300);
                    } else {
                        location.reload(); // Fallback if row not found
                    }
                    showToast('Removed from wishlist', 'success');
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
                    showToast(data.message || 'Failed to remove from wishlist', 'error');
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

