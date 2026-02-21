<?php
include("../includes/connect.php");
include("../functions/common_functions.php");
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: user_login.php');
    exit;
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $select_order_query = "SELECT * FROM `user_orders` WHERE order_id = '$order_id'";
    $select_order_result = mysqli_query($con,$select_order_query);
    
    if($select_order_result && mysqli_num_rows($select_order_result) > 0){
        $row_fetch = mysqli_fetch_array($select_order_result);
        $invoice_number = $row_fetch['invoice_number'];
        $amount_due = $row_fetch['amount_due'];
        $total_products = $row_fetch['total_products'];
        $order_date = $row_fetch['order_date'];
        
        // Format order date
        $formatted_date = date('M d, Y', strtotime($order_date));
        $formatted_time = date('h:i A', strtotime($order_date));
    } else {
        header('Location: profile.php?my_orders');
        exit;
    }
} else {
    header('Location: profile.php?my_orders');
    exit;
}

if(isset($_POST['confirm_payment'])){
    //insert user payment
    $invoice_number = $_POST['invoice_number'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $insert_payment_query = "INSERT INTO `user_payments` (order_id,invoice_number,amount,payment_method) VALUES ($order_id,$invoice_number,$amount,'$payment_method')";
    $insert_payment_result = mysqli_query($con,$insert_payment_query);
    if($insert_payment_result){
        //update user orders - set to completed when payment is confirmed
        $update_orders_query = "UPDATE `user_orders` SET order_status = 'completed' WHERE order_id = $order_id";
        $update_orders_result = mysqli_query($con,$update_orders_query);
        
        echo "<script>
            if(typeof showToast === 'function') {
                showToast('Payment completed successfully!', 'success');
            } else {
                alert('Payment completed successfully!');
            }
            setTimeout(() => { window.location.href = 'profile.php?my_orders'; }, 2000);
        </script>";
    } else {
        echo "<script>
            if(typeof showToast === 'function') {
                showToast('Payment failed. Please try again.', 'error');
            } else {
                alert('Payment failed. Please try again.');
            }
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Toast Helper Function
        function showToast(message, type = 'success') {
            const colors = {
                success: {
                    background: 'linear-gradient(to right, #10b981, #059669)',
                    icon: '✓'
                },
                error: {
                    background: 'linear-gradient(to right, #ef4444, #dc2626)',
                    icon: '✕'
                },
                warning: {
                    background: 'linear-gradient(to right, #f59e0b, #d97706)',
                    icon: '⚠'
                },
                info: {
                    background: 'linear-gradient(to right, #1e40af, #1e3a8a)',
                    icon: 'ℹ'
                }
            };
            
            const config = colors[type] || colors.info;
            
            Toastify({
                text: message,
                duration: 4000,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: config.background,
                    borderRadius: "8px",
                    boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                    padding: "16px 20px",
                    fontSize: "14px",
                    fontWeight: "500",
                    color: "#ffffff"
                },
                onClick: function() {}
            }).showToast();
        }
    </script>
</head>
<body class="bg-gray-50">
    <?php include('../includes/header.php'); ?>
    
    <section class="py-12 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center gap-4 mb-4">
                        <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                        <span class="text-[#1e40af] font-bold text-sm uppercase">Payment Confirmation</span>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
                    <p class="text-gray-600 text-lg">Review your order details and confirm payment</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Order Summary Card -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
                                <div class="bg-[#1e40af] w-12 h-12 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shopping-bag text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Order Summary</h3>
                                    <p class="text-sm text-gray-500">Order #<?php echo htmlspecialchars($order_id); ?></p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-file-invoice text-[#1e40af]"></i>
                                        <div>
                                            <p class="text-sm text-gray-500">Invoice Number</p>
                                            <p class="font-semibold text-gray-900">#<?php echo htmlspecialchars($invoice_number); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-box text-[#1e40af]"></i>
                                        <div>
                                            <p class="text-sm text-gray-500">Total Products</p>
                                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($total_products); ?> item<?php echo $total_products > 1 ? 's' : ''; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <i class="far fa-calendar-alt text-[#1e40af]"></i>
                                        <div>
                                            <p class="text-sm text-gray-500">Order Date</p>
                                            <p class="font-semibold text-gray-900"><?php echo $formatted_date; ?> at <?php echo $formatted_time; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-6 bg-gradient-to-r from-[#1e40af] to-[#3b82f6] rounded-lg text-white">
                                    <div>
                                        <p class="text-sm opacity-90">Total Amount</p>
                                        <p class="text-3xl font-bold">$<?php echo number_format($amount_due, 2); ?></p>
                                    </div>
                                    <i class="fas fa-dollar-sign text-4xl opacity-20"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sticky top-24">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fas fa-credit-card text-[#1e40af]"></i>
                                Payment Details
                            </h3>

                            <form method="post" action="" class="space-y-4">
                                <!-- Invoice Number -->
                                <div>
                                    <label for="invoice_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-file-invoice mr-2 text-[#1e40af]"></i>Invoice Number
                                    </label>
                                    <input type="text" 
                                           name="invoice_number" 
                                           id="invoice_number" 
                                           value="<?php echo htmlspecialchars($invoice_number); ?>" 
                                           readonly
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600 cursor-not-allowed focus:outline-none">
                                </div>

                                <!-- Amount -->
                                <div>
                                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-dollar-sign mr-2 text-[#1e40af]"></i>Amount
                                    </label>
                                    <input type="text" 
                                           name="amount" 
                                           id="amount" 
                                           value="<?php echo number_format($amount_due, 2); ?>" 
                                           readonly
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600 cursor-not-allowed focus:outline-none">
                                </div>

                                <!-- Payment Method -->
                                <div>
                                    <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-credit-card mr-2 text-[#1e40af]"></i>Payment Method <span class="text-red-500">*</span>
                                    </label>
                                    <select name="payment_method" 
                                            id="payment_method" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none appearance-none bg-white">
                                        <option value="" selected disabled>Select payment method</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="visa">Visa Card</option>
                                        <option value="mastercard">Mastercard</option>
                                        <option value="apple_pay">Apple Pay</option>
                                        <option value="amazon_pay">Amazon Pay</option>
                                        <option value="upi">UPI</option>
                                        <option value="masr_bank">Masr Bank</option>
                                        <option value="cash_on_delivery">Cash on Delivery</option>
                                    </select>
                                    <div class="relative">
                                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <!-- Security Notice -->
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-shield-alt text-blue-600 mt-1"></i>
                                        <div>
                                            <p class="text-sm font-semibold text-blue-900">Secure Payment</p>
                                            <p class="text-xs text-blue-700 mt-1">Your payment information is encrypted and secure.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" 
                                        name="confirm_payment" 
                                        class="w-full bg-[#1e40af] text-white px-6 py-4 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Confirm Payment</span>
                                </button>

                                <!-- Cancel Link -->
                                <a href="profile.php?my_orders" 
                                   class="block w-full text-center text-gray-600 hover:text-gray-900 transition-colors text-sm font-medium">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Info -->
                <div class="mt-8 bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#1e40af]"></i>
                        Accepted Payment Methods
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                            <i class="fab fa-cc-paypal text-2xl text-[#0070ba]"></i>
                            <span class="text-sm font-medium text-gray-700">PayPal</span>
                        </div>
                        <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                            <i class="fab fa-cc-visa text-2xl text-[#1a1f71]"></i>
                            <span class="text-sm font-medium text-gray-700">Visa</span>
                        </div>
                        <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                            <i class="fab fa-cc-mastercard text-2xl text-[#eb001b]"></i>
                            <span class="text-sm font-medium text-gray-700">Mastercard</span>
                        </div>
                        <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                            <span class="text-sm font-medium text-gray-700">Cash on Delivery</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('../includes/footer.php'); ?>
    <script src="../assets/js/bootstrap.bundle.js"></script>
    <style>
        /* Custom select arrow */
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }
        
        select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e40af' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        }
    </style>
</body>
</html>
