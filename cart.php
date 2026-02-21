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
    <title>Shopping Cart - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <?php include('./includes/header.php'); ?>

    <!-- Cart Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                <span class="text-[#1e40af] font-bold text-sm uppercase">Shopping Cart</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-12">Your Cart</h1>

                <?php
                $getIpAddress = getIPAddress();
                $total_price = 0;
                $cart_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress'";
                $cart_result = mysqli_query($con, $cart_query);
                $result_count = mysqli_num_rows($cart_result);
                
                if ($result_count > 0) {
                ?>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Product</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Image</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Quantity</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Price</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody" class="divide-y divide-gray-200">
                                <?php
                                while ($row = mysqli_fetch_array($cart_result)) {
                                    $product_id = $row['product_id'];
                                    $product_quantity = $row['quantity'];
                                    $select_product_query = "SELECT * FROM `products` WHERE product_id=$product_id";
                                    $select_product_result = mysqli_query($con, $select_product_query);
                                    while ($row_product_price = mysqli_fetch_array($select_product_result)) {
                                        $product_price = array($row_product_price['product_price']);
                                        $price_table = $row_product_price['product_price'];
                                        $product_id = $row_product_price['product_id'];
                                        $product_title = htmlspecialchars($row_product_price['product_title']);
                                        $product_image_one = $row_product_price['product_image_one'];
                                        $product_values = array_sum($product_price);
                                        $total_price += $product_values * $product_quantity;
                                ?>
                                <tr id="cart-row-<?php echo $product_id; ?>" class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="product_details.php?product_id=<?php echo $product_id; ?>" class="text-gray-900 font-semibold hover:text-[#1e40af] transition-colors">
                                            <?php echo $product_title; ?>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php 
                                        $image_url = (strpos($product_image_one, 'http://') === 0 || strpos($product_image_one, 'https://') === 0) ? $product_image_one : './admin/product_images/' . $product_image_one;
                                        ?>
                                        <img src="<?php echo $image_url; ?>" 
                                             alt="<?php echo $product_title; ?>" 
                                             class="w-20 h-20 object-contain rounded-lg bg-gray-100 p-2">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center">
                                            <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden">
                                                <button type="button" 
                                                        onclick="decreaseQty(<?php echo $product_id; ?>)" 
                                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition-colors">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" 
                                                       id="qty-<?php echo $product_id; ?>" 
                                                       value="<?php echo $product_quantity; ?>"
                                                       min="1" 
                                                       readonly
                                                       class="w-20 px-4 py-2 text-center border-0 focus:outline-none focus:ring-0 bg-white">
                                                <button type="button" 
                                                        onclick="increaseQty(<?php echo $product_id; ?>)" 
                                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition-colors">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span id="price-<?php echo $product_id; ?>" class="text-[#1e40af] font-bold text-lg">$<?php echo number_format($price_table * $product_quantity, 2); ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button" 
                                                onclick="removeItem(<?php echo $product_id; ?>)" 
                                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-semibold">
                                            <i class="fas fa-trash mr-1"></i>Remove
                                        </button>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2"></div>
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Cart Summary</h3>
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="cart-subtotal" class="text-gray-900 font-semibold">$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping:</span>
                                <span class="text-gray-900 font-semibold">Free</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-xl font-bold text-gray-900">Total:</span>
                                    <span id="cart-total" class="text-2xl font-bold text-[#1e40af]">$<?php echo number_format($total_price, 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <a href="users_area/checkout.php" 
                               class="block w-full bg-[#1e40af] text-white text-center px-6 py-3 rounded-lg font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg">
                                <i class="fas fa-credit-card mr-2"></i>Proceed to Checkout
                            </a>
                            <a href="index.php" 
                               class="block w-full bg-gray-100 text-gray-900 text-center px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                                <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                } else {
                ?>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-16 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shopping-cart text-gray-400 text-4xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Your Cart is Empty</h2>
                    <p class="text-gray-600 mb-8">Looks like you haven't added any items to your cart yet.</p>
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

    <script>
        function increaseQty(productId) {
            const qtyInput = document.getElementById('qty-' + productId);
            const currentQty = parseInt(qtyInput.value);
            const newQty = currentQty + 1;
            qtyInput.value = newQty;
            updateQuantity(productId, newQty);
        }

        function decreaseQty(productId) {
            const qtyInput = document.getElementById('qty-' + productId);
            const currentQty = parseInt(qtyInput.value);
            if (currentQty > 1) {
                const newQty = currentQty - 1;
                qtyInput.value = newQty;
                updateQuantity(productId, newQty);
            }
        }

        function updateQuantity(productId, quantity) {
            const formData = new FormData();
            formData.append('action', 'update_quantity');
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update item price
                    document.getElementById('price-' + productId).textContent = '$' + data.item_total;
                    // Update cart totals
                    document.getElementById('cart-subtotal').textContent = '$' + data.cart_total;
                    document.getElementById('cart-total').textContent = '$' + data.cart_total;
                    showToast('Quantity updated', 'success');
                    // Update cart count in header
                    if (data.cart_count !== undefined) {
                        const cartCountEl = document.getElementById('cart-count-header');
                        if (cartCountEl) {
                            cartCountEl.textContent = data.cart_count;
                        }
                    } else if (typeof updateHeaderCounts === 'function') {
                        updateHeaderCounts();
                    }
                } else {
                    showToast(data.message || 'Failed to update quantity', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred', 'error');
                location.reload();
            });
        }

        function removeItem(productId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'remove_item');
            formData.append('product_id', productId);

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove row from table
                    const row = document.getElementById('cart-row-' + productId);
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            
                            // Check if cart is empty
                            if (data.item_count === 0) {
                                location.reload(); // Show empty cart message
                            } else {
                                // Update cart totals
                                document.getElementById('cart-subtotal').textContent = '$' + data.cart_total;
                                document.getElementById('cart-total').textContent = '$' + data.cart_total;
                            }
                        }, 300);
                    }
                    showToast('Item removed from cart', 'success');
                    // Update cart count in header
                    if (data.cart_count !== undefined) {
                        const cartCountEl = document.getElementById('cart-count-header');
                        if (cartCountEl) {
                            cartCountEl.textContent = data.cart_count;
                        }
                    } else if (typeof updateHeaderCounts === 'function') {
                        updateHeaderCounts();
                    }
                } else {
                    showToast(data.message || 'Failed to remove item', 'error');
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
