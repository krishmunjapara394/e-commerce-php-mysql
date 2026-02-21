<?php
    // Ensure session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Ensure database connection is available
    if(!isset($con)){
        include("../includes/connect.php");
    }
    
    // access user id
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $get_user_query = "SELECT * FROM `user_table` WHERE username='$username'";
        $get_user_result = mysqli_query($con,$get_user_query);
        
        if($get_user_result && mysqli_num_rows($get_user_result) > 0){
            $row_user_data = mysqli_fetch_array($get_user_result);
            $user_id = $row_user_data['user_id'];
            
            // Get ALL orders with payment status
            // Using subquery to get payment method to avoid GROUP BY issues
            $get_order_details_query = "SELECT uo.order_id, 
                                        uo.user_id,
                                        uo.amount_due,
                                        uo.invoice_number,
                                        uo.total_products,
                                        uo.order_date,
                                        uo.order_status,
                                        CASE 
                                            WHEN EXISTS(SELECT 1 FROM user_payments WHERE order_id = uo.order_id) THEN 'Paid'
                                            ELSE 'Pending'
                                        END as payment_status,
                                        (SELECT payment_method FROM user_payments WHERE order_id = uo.order_id ORDER BY payment_date DESC LIMIT 1) as payment_method
                                        FROM `user_orders` uo
                                        WHERE uo.user_id = $user_id 
                                        ORDER BY uo.order_date DESC";
            $get_order_details_result = mysqli_query($con,$get_order_details_query);
            
            if($get_order_details_result){
                $total_orders = mysqli_num_rows($get_order_details_result);
            } else {
                $total_orders = 0;
                $get_order_details_result = false;
                // Show error for debugging (remove in production)
                $error_message = mysqli_error($con);
                if($error_message){
                    // Uncomment next line for debugging
                    // echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>Query Error: " . htmlspecialchars($error_message) . "</div>";
                }
            }
        } else {
            $user_id = 0;
            $total_orders = 0;
            $get_order_details_result = false;
        }
    } else {
        $user_id = 0;
        $total_orders = 0;
        $get_order_details_result = false;
    }
?>

<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
            <h2 class="text-3xl font-bold text-gray-900">My Orders</h2>
        </div>
        <div class="text-sm text-gray-600">
            <span class="font-semibold text-gray-900"><?php echo $total_orders; ?></span> total orders
        </div>
    </div>

    <?php if($total_orders > 0 && $get_order_details_result): ?>
        <div class="space-y-4">
            <?php
                $serial_number = 1;
                while($row_fetch_order_details = mysqli_fetch_array($get_order_details_result)){
                    $order_id = $row_fetch_order_details['order_id'];
                    $amount_due = $row_fetch_order_details['amount_due'];
                    $invoice_number = $row_fetch_order_details['invoice_number'];
                    $total_products = $row_fetch_order_details['total_products'];
                    $order_date = $row_fetch_order_details['order_date'];
                    $order_status = $row_fetch_order_details['order_status'];
                    $payment_status = $row_fetch_order_details['payment_status'];
                    $payment_method = $row_fetch_order_details['payment_method'] ?? '';
                    
                    // Format date
                    $formatted_date = date('M d, Y', strtotime($order_date));
                    $formatted_time = date('h:i A', strtotime($order_date));
                    
                    // Order Status (Pending/Completed)
                    $order_status_display = ($order_status == 'paid' || $order_status == 'completed') ? 'Completed' : 'Pending';
                    $order_status_bg = ($order_status_display == 'Completed') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                    $order_status_icon = ($order_status_display == 'Completed') ? 'fa-check-circle' : 'fa-clock';
                    
                    // Payment Status (Paid/Pending)
                    $payment_status_bg = ($payment_status == 'Paid') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                    $payment_status_icon = ($payment_status == 'Paid') ? 'fa-check-circle' : 'fa-exclamation-circle';
            ?>
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition-all">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <!-- Order Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-[#1e40af] w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shopping-bag text-white text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Order #<?php echo htmlspecialchars($order_id); ?></h3>
                                <p class="text-sm text-gray-500">
                                    <i class="far fa-calendar-alt mr-1"></i><?php echo $formatted_date; ?> at <?php echo $formatted_time; ?>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Order Details Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs text-gray-500 mb-1 font-medium">Invoice Number</p>
                                <p class="text-base font-bold text-gray-900">#<?php echo htmlspecialchars($invoice_number); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs text-gray-500 mb-1 font-medium">Total Products</p>
                                <p class="text-base font-bold text-gray-900">
                                    <i class="fas fa-box mr-1 text-[#1e40af]"></i><?php echo htmlspecialchars($total_products); ?> item<?php echo $total_products > 1 ? 's' : ''; ?>
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs text-gray-500 mb-1 font-medium">Total Amount</p>
                                <p class="text-base font-bold text-[#1e40af]">$<?php echo number_format($amount_due, 2); ?></p>
                            </div>
                        </div>
                        
                        <!-- Status Badges -->
                        <div class="flex flex-wrap items-center gap-3 mt-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1 font-medium">Payment Status</p>
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold <?php echo $payment_status_bg; ?>">
                                    <i class="fas <?php echo $payment_status_icon; ?>"></i>
                                    <?php echo htmlspecialchars($payment_status); ?>
                                </span>
                                <?php if($payment_method): ?>
                                    <span class="ml-2 text-xs text-gray-600">
                                        <i class="fas fa-credit-card mr-1"></i><?php echo ucfirst(str_replace('_', ' ', $payment_method)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1 font-medium">Order Status</p>
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold <?php echo $order_status_bg; ?>">
                                    <i class="fas <?php echo $order_status_icon; ?>"></i>
                                    <?php echo htmlspecialchars($order_status_display); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <div class="flex items-start lg:items-center justify-end">
                        <?php if($order_status == 'pending' && $payment_status == 'Pending'): ?>
                            <a href="confirm_payment.php?order_id=<?php echo $order_id; ?>" 
                               class="bg-[#1e40af] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-md whitespace-nowrap flex items-center gap-2">
                                <i class="fas fa-credit-card"></i>
                                <span>Pay Now</span>
                            </a>
                        <?php elseif($order_status_display == 'Completed'): ?>
                            <div class="px-6 py-3 rounded-xl bg-green-50 text-green-700 font-semibold whitespace-nowrap flex items-center gap-2 border border-green-200">
                                <i class="fas fa-check-circle"></i>
                                <span>Order Completed</span>
                            </div>
                        <?php else: ?>
                            <div class="px-6 py-3 rounded-xl bg-yellow-50 text-yellow-700 font-semibold whitespace-nowrap flex items-center gap-2 border border-yellow-200">
                                <i class="fas fa-clock"></i>
                                <span>Processing</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
                    $serial_number++;
                }
            ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-bag text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">No Orders Yet</h3>
            <p class="text-gray-600 mb-6">You haven't placed any orders yet. Start shopping to see your orders here!</p>
            <a href="../index.php" 
               class="inline-block bg-[#1e40af] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-shopping-cart mr-2"></i>Start Shopping
            </a>
        </div>
    <?php endif; ?>
</div>