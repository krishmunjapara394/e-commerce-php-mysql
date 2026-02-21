<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
session_start();

// Get order details from URL or session
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : (isset($_SESSION['last_order_id']) ? $_SESSION['last_order_id'] : 0);
$payment_method = isset($_GET['payment_method']) ? $_GET['payment_method'] : 'cash_on_delivery';

if($order_id > 0){
    // Get order details
    $get_order_query = "SELECT * FROM `user_orders` WHERE order_id = '$order_id'";
    $get_order_result = mysqli_query($con, $get_order_query);
    $order_data = mysqli_fetch_array($get_order_result);
    
    if($order_data){
        $invoice_number = $order_data['invoice_number'];
        $amount_due = $order_data['amount_due'];
        $total_products = $order_data['total_products'];
        $order_date = $order_data['order_date'];
        $order_status = $order_data['order_status'];
        
        // Calculate delivery dates
        $order_timestamp = strtotime($order_date);
        $processing_date = date('M d, Y', strtotime('+1 day', $order_timestamp));
        $shipped_date = date('M d, Y', strtotime('+3 days', $order_timestamp));
        $delivery_date = date('M d, Y', strtotime('+5 days', $order_timestamp));
        
        // Format order date
        $formatted_order_date = date('M d, Y h:i A', $order_timestamp);
        
        // Get user details
        $username = $_SESSION['username'];
        $get_user_query = "SELECT * FROM `user_table` WHERE username='$username'";
        $get_user_result = mysqli_query($con, $get_user_query);
        $user_data = mysqli_fetch_array($get_user_result);
        
        // Get order products
        $get_products_query = "SELECT op.*, p.product_title, p.product_image_one, p.product_price 
                              FROM `orders_pending` op 
                              JOIN `products` p ON op.product_id = p.product_id 
                              WHERE op.invoice_number = '$invoice_number'";
        $get_products_result = mysqli_query($con, $get_products_query);
    } else {
        header('Location: profile.php?my_orders');
        exit;
    }
} else {
    header('Location: profile.php?my_orders');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes checkmark {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .checkmark-animation {
            animation: checkmark 0.6s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include('../includes/header.php'); ?>
    
    <section class="py-12 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Success Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4 checkmark-animation">
                        <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h1>
                    <p class="text-gray-600 text-lg">Thank you for your purchase. Your order has been confirmed.</p>
                </div>

                <!-- Order Summary Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-6">
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Order Number</p>
                            <h2 class="text-2xl font-bold text-gray-900">#<?php echo htmlspecialchars($order_id); ?></h2>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1">Invoice Number</p>
                            <p class="text-lg font-semibold text-gray-900">#<?php echo htmlspecialchars($invoice_number); ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 mb-1 font-medium">Order Date</p>
                            <p class="text-base font-semibold text-gray-900">
                                <i class="far fa-calendar-alt mr-2 text-[#1e40af]"></i><?php echo $formatted_order_date; ?>
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 mb-1 font-medium">Total Products</p>
                            <p class="text-base font-semibold text-gray-900">
                                <i class="fas fa-box mr-2 text-[#1e40af]"></i><?php echo htmlspecialchars($total_products); ?> item<?php echo $total_products > 1 ? 's' : ''; ?>
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 mb-1 font-medium">Total Amount</p>
                            <p class="text-base font-semibold text-[#1e40af]">
                                <i class="fas fa-dollar-sign mr-2"></i>$<?php echo number_format($amount_due, 2); ?>
                            </p>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                                <p class="text-base font-semibold text-gray-900">
                                    <?php 
                                    $payment_methods = [
                                        'paypal' => 'PayPal',
                                        'apple_pay' => 'Apple Pay',
                                        'visa' => 'Visa Card',
                                        'amazon_pay' => 'Amazon Pay',
                                        'cash_on_delivery' => 'Cash on Delivery'
                                    ];
                                    echo isset($payment_methods[$payment_method]) ? $payment_methods[$payment_method] : ucfirst(str_replace('_', ' ', $payment_method));
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Section -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-route text-[#1e40af]"></i>
                        Order Timeline
                    </h3>
                    
                    <div class="relative">
                        <!-- Timeline Line -->
                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        
                        <!-- Timeline Items -->
                        <div class="space-y-8">
                            <!-- Order Placed -->
                            <div class="relative flex items-start gap-4">
                                <div class="relative z-10 flex-shrink-0 w-12 h-12 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                                <div class="flex-1 pt-2">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Order Placed</h4>
                                    <p class="text-sm text-gray-600 mb-2">Your order has been successfully placed and confirmed.</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="far fa-calendar-alt mr-1"></i><?php echo $formatted_order_date; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Processing -->
                            <div class="relative flex items-start gap-4">
                                <div class="relative z-10 flex-shrink-0 w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-cog text-white"></i>
                                </div>
                                <div class="flex-1 pt-2">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Processing</h4>
                                    <p class="text-sm text-gray-600 mb-2">We're preparing your order for shipment.</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="far fa-calendar-alt mr-1"></i>Expected: <?php echo $processing_date; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Shipped -->
                            <div class="relative flex items-start gap-4">
                                <div class="relative z-10 flex-shrink-0 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                                    <i class="fas fa-shipping-fast text-white"></i>
                                </div>
                                <div class="flex-1 pt-2">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Shipped</h4>
                                    <p class="text-sm text-gray-600 mb-2">Your order has been shipped and is on its way.</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="far fa-calendar-alt mr-1"></i>Expected: <?php echo $shipped_date; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Delivered -->
                            <div class="relative flex items-start gap-4">
                                <div class="relative z-10 flex-shrink-0 w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                                    <i class="fas fa-home text-gray-600"></i>
                                </div>
                                <div class="flex-1 pt-2">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Delivered</h4>
                                    <p class="text-sm text-gray-600 mb-2">Your order will be delivered to your address.</p>
                                    <p class="text-xs font-semibold text-[#1e40af]">
                                        <i class="far fa-calendar-alt mr-1"></i>Expected Delivery: <?php echo $delivery_date; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-shopping-bag text-[#1e40af]"></i>
                        Order Items
                    </h3>
                    <div class="space-y-4">
                        <?php
                        $item_count = 0;
                        while($product_row = mysqli_fetch_array($get_products_result)){
                            $item_count++;
                            $product_title = $product_row['product_title'];
                            $product_image = $product_row['product_image_one'];
                            $product_price = $product_row['product_price'];
                            $quantity = $product_row['quantity'];
                            $subtotal = $product_price * $quantity;
                            
                            // Handle both online URLs and local files
                            if (strpos($product_image, 'http://') === 0 || strpos($product_image, 'https://') === 0) {
                                $image_url = $product_image;
                            } else {
                                $image_url = '../admin/product_images/' . $product_image;
                            }
                        ?>
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <img src="<?php echo htmlspecialchars($image_url); ?>" 
                                 alt="<?php echo htmlspecialchars($product_title); ?>" 
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($product_title); ?></h4>
                                <p class="text-sm text-gray-600">Quantity: <?php echo $quantity; ?> × $<?php echo number_format($product_price, 2); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">$<?php echo number_format($subtotal, 2); ?></p>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="profile.php?my_orders" 
                       class="flex-1 bg-[#1e40af] text-white px-6 py-4 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all text-center flex items-center justify-center gap-2">
                        <i class="fas fa-list"></i>
                        <span>View All Orders</span>
                    </a>
                    <a href="../index.php" 
                       class="flex-1 bg-white text-[#1e40af] border-2 border-[#1e40af] px-6 py-4 rounded-xl font-semibold hover:bg-[#1e40af] hover:text-white transition-all text-center flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Continue Shopping</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include('../includes/footer.php'); ?>
</body>
</html>

