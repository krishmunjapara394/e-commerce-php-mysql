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
    <title>Product Details - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
        }
        .product-image-main {
            transition: transform 0.3s ease;
        }
        .product-image-main:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include('./includes/header.php'); ?>

    <!-- Product Details Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <?php
            $product_id = 0; // Initialize product_id
            if (isset($_GET['product_id'])) {
                $product_id = $_GET['product_id'];
                $select_product_query = "SELECT * FROM `products` WHERE product_id=$product_id";
                $select_product_result = mysqli_query($con, $select_product_query);
                while ($row = mysqli_fetch_assoc($select_product_result)) {
                    $product_id = $row['product_id'];
                    $product_title = htmlspecialchars($row['product_title']);
                    $product_desc = htmlspecialchars($row['product_description']);
                    $product_image_one = $row['product_image_one'];
                    $product_image_two = $row['product_image_two'];
                    $product_image_three = $row['product_image_three'];
                    $product_price = $row['product_price'];
                    $category_id = $row['category_id'];
                    $brand_id = $row['brand_id'];
                    
                    // Use the getImageUrl function from common_functions.php
                    $image_url_one = getImageUrl($product_image_one);
                    $image_url_two = getImageUrl($product_image_two);
                    $image_url_three = getImageUrl($product_image_three);
            ?>
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Product Images -->
                    <div>
                        <div class="bg-gray-100 rounded-2xl p-8 mb-6 flex items-center justify-center h-96">
                            <img src="<?php echo $image_url_one; ?>" 
                                 alt="<?php echo $product_title; ?>" 
                                 id="mainImage"
                                 class="product-image-main max-w-full max-h-full object-contain">
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-gray-100 rounded-xl p-4 cursor-pointer border-2 border-transparent hover:border-[#1e40af] transition-all" onclick="changeImage('<?php echo $image_url_one; ?>')">
                                <img src="<?php echo $image_url_one; ?>" alt="Image 1" class="w-full h-24 object-contain">
                            </div>
                            <div class="bg-gray-100 rounded-xl p-4 cursor-pointer border-2 border-transparent hover:border-[#1e40af] transition-all" onclick="changeImage('<?php echo $image_url_two; ?>')">
                                <img src="<?php echo $image_url_two; ?>" alt="Image 2" class="w-full h-24 object-contain">
                            </div>
                            <div class="bg-gray-100 rounded-xl p-4 cursor-pointer border-2 border-transparent hover:border-[#1e40af] transition-all" onclick="changeImage('<?php echo $image_url_three; ?>')">
                                <img src="<?php echo $image_url_three; ?>" alt="Image 3" class="w-full h-24 object-contain">
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php echo $product_title; ?></h1>
                        
                        <div class="flex items-center gap-2 mb-6">
                            <div class="flex items-center">
                                <?php for($i = 0; $i < 5; $i++): ?>
                                <i class="fas fa-star text-yellow-400"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-gray-600">(150 Reviews)</span>
                            <span class="text-green-600 font-semibold ml-4">
                                <i class="fas fa-check-circle mr-1"></i>In Stock
                            </span>
                        </div>

                        <div class="mb-6">
                            <span class="text-4xl font-bold text-[#1e40af]">$<?php echo $product_price; ?></span>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                            <p class="text-gray-600 leading-relaxed"><?php echo $product_desc; ?></p>
                        </div>

                        <form action="products.php" method="get" class="mb-8">
                            <div class="flex items-center gap-4 mb-6">
                                <label class="text-sm font-semibold text-gray-800">Quantity:</label>
                                <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden">
                                    <button type="button" onclick="decreaseQty()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" name="num_of_items" id="quantity" value="1" min="1" 
                                           class="w-20 px-4 py-2 text-center border-0 focus:outline-none focus:ring-0">
                                    <button type="button" onclick="increaseQty()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="add_to_cart" value="<?php echo $product_id; ?>">
                            <div class="flex gap-4">
                                <button type="submit" class="flex-1 bg-[#1e40af] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                                </button>
                                <button type="button" id="wishlist-btn-<?php echo $product_id; ?>" onclick="toggleWishlist(<?php echo $product_id; ?>)" class="px-8 py-4 border-2 border-[#1e40af] text-[#1e40af] rounded-xl font-semibold hover:bg-[#1e40af] hover:text-white transition-all">
                                    <i class="fas fa-heart mr-2"></i><span id="wishlist-text-<?php echo $product_id; ?>">Add to Wishlist</span>
                                </button>
                            </div>
                        </form>

                        <div class="border-t border-gray-200 pt-6 space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="bg-[#1e40af] w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-truck text-white"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-1">Free Delivery</h5>
                                    <p class="text-gray-600 text-sm">Enter your postal code for Delivery Availability</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="bg-[#1e40af] w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-undo text-white"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-1">Return Delivery</h5>
                                    <p class="text-gray-600 text-sm">Free 30 Days Delivery Returns. Details</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div>
    </section>

    <!-- Related Products Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                <span class="text-[#1e40af] font-bold text-sm uppercase">Related Products</span>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mb-12">Discover More Products</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                getProduct(4);
                cart();
                ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="products.php" class="inline-block bg-[#1e40af] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg">
                    View More Products
                </a>
            </div>
        </div>
    </section>

    <?php include('./includes/footer.php'); ?>

    <script>
        function changeImage(src) {
            const mainImage = document.getElementById('mainImage');
            if (mainImage) {
                mainImage.src = src;
            }
        }
        
        function increaseQty() {
            const qty = document.getElementById('quantity');
            if (qty) {
                const currentValue = parseInt(qty.value) || 1;
                qty.value = currentValue + 1;
            }
        }
        
        function decreaseQty() {
            const qty = document.getElementById('quantity');
            if (qty) {
                const currentValue = parseInt(qty.value) || 1;
                if (currentValue > 1) {
                    qty.value = currentValue - 1;
                }
            }
        }
        
        // Check wishlist status on page load
        document.addEventListener('DOMContentLoaded', function() {
            const productId = <?php echo isset($product_id) && $product_id > 0 ? $product_id : 0; ?>;
            if (productId > 0) {
                checkWishlistStatus(productId);
            }
        });
        
        function checkWishlistStatus(productId) {
            if (!productId || productId <= 0) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'check_wishlist');
            formData.append('product_id', productId);
            
            fetch('add_to_wishlist.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.is_in_wishlist) {
                    const btn = document.getElementById('wishlist-btn-' + productId);
                    const text = document.getElementById('wishlist-text-' + productId);
                    if (btn && text) {
                        btn.classList.add('bg-[#1e40af]', 'text-white');
                        btn.classList.remove('border-2', 'border-[#1e40af]', 'text-[#1e40af]');
                        text.textContent = 'In Wishlist';
                    }
                }
            })
            .catch(error => {
                console.error('Error checking wishlist status:', error);
            });
        }
        
        function toggleWishlist(productId) {
            if (!productId || productId <= 0) {
                showToast('Invalid product ID', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'add_to_wishlist');
            formData.append('product_id', productId);
            
            fetch('add_to_wishlist.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const btn = document.getElementById('wishlist-btn-' + productId);
                    const text = document.getElementById('wishlist-text-' + productId);
                    if (btn && text) {
                        btn.classList.add('bg-[#1e40af]', 'text-white');
                        btn.classList.remove('border-2', 'border-[#1e40af]', 'text-[#1e40af]');
                        text.textContent = 'In Wishlist';
                    }
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
                    if (data.message && data.message.includes('already')) {
                        // Remove from wishlist
                        removeFromWishlist(productId);
                    } else {
                        showToast(data.message || 'Failed to add to wishlist', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating wishlist', 'error');
            });
        }
        
        function removeFromWishlist(productId) {
            if (!productId || productId <= 0) {
                showToast('Invalid product ID', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'remove_from_wishlist');
            formData.append('product_id', productId);
            
            fetch('add_to_wishlist.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const btn = document.getElementById('wishlist-btn-' + productId);
                    const text = document.getElementById('wishlist-text-' + productId);
                    if (btn && text) {
                        btn.classList.remove('bg-[#1e40af]', 'text-white');
                        btn.classList.add('border-2', 'border-[#1e40af]', 'text-[#1e40af]');
                        text.textContent = 'Add to Wishlist';
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
                showToast('An error occurred while updating wishlist', 'error');
            });
        }
    </script>
</body>

</html>
