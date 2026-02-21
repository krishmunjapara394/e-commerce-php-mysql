<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
session_start();

// Get user_id from session username (more reliable)
$user_id = 0;
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $get_user_query = "SELECT * FROM `user_table` WHERE username='$username'";
    $get_user_result = mysqli_query($con,$get_user_query);
    if($get_user_result && mysqli_num_rows($get_user_result) > 0){
        $row_user = mysqli_fetch_array($get_user_result);
        $user_id = $row_user['user_id'];
    }
}

// Fallback to GET/POST if session doesn't have user
if($user_id == 0){
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : (isset($_POST['user_id']) ? $_POST['user_id'] : 0);
}

$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cash_on_delivery';

if($user_id > 0){
    // getting total items and total price of all items
    $get_ip_address = getIPAddress();
    $total_price = 0;
    $cart_query = "SELECT * FROM `card_details` WHERE ip_address= '$get_ip_address'";
    $cart_result = mysqli_query($con,$cart_query);
    $invoice_number = mt_rand();
    $status = "pending";
    $count_products = mysqli_num_rows($cart_result);
    
    // Calculate total price and insert pending orders
    while($row_price=mysqli_fetch_array($cart_result)){
        $product_id = $row_price['product_id'];
        $product_quantity = $row_price['quantity'];
        $select_product = "SELECT * FROM `products` WHERE product_id= $product_id";
        $select_product_result = mysqli_query($con,$select_product);
        
        while($row_product_price=mysqli_fetch_array($select_product_result)){
            $product_price = array($row_product_price['product_price']);
            $product_values = array_sum($product_price) * $product_quantity;
            $total_price+=$product_values;
        }
        
        // Insert pending orders
        $insert_pending_order_query = "INSERT INTO `orders_pending` (user_id,invoice_number,product_id,quantity,order_status) VALUES ($user_id,$invoice_number,$product_id,$product_quantity,'$status')";
        $insert_pending_order_result = mysqli_query($con,$insert_pending_order_query);
    }

    // Insert main order
    $insert_order_query = "INSERT INTO `user_orders` (user_id,amount_due,invoice_number,total_products,order_date,order_status) VALUES ($user_id,$total_price,$invoice_number,$count_products,NOW(),'$status')";
    $insert_order_result = mysqli_query($con,$insert_order_query);
    
    if($insert_order_result){
        // Get the inserted order ID
        $order_id = mysqli_insert_id($con);
        
        // If payment method is not COD, process payment
        if($payment_method != 'cash_on_delivery'){
            // Insert payment record
            $insert_payment_query = "INSERT INTO `user_payments` (order_id,invoice_number,amount,payment_method) VALUES ($order_id,$invoice_number,$total_price,'$payment_method')";
            $insert_payment_result = mysqli_query($con,$insert_payment_query);
            
            // Update order status to completed
            if($insert_payment_result){
                $update_order_query = "UPDATE `user_orders` SET order_status = 'completed' WHERE order_id = $order_id";
                mysqli_query($con,$update_order_query);
            }
        }
        
        // Delete items from cart
        $empty_cart = "DELETE FROM `card_details` WHERE ip_address='$get_ip_address'";
        mysqli_query($con,$empty_cart);
        
        // Store order_id in session for confirmation page
        $_SESSION['last_order_id'] = $order_id;
        
        // Redirect to order success page
        header("Location: order_success.php?order_id=$order_id&payment_method=$payment_method");
        exit;
    } else {
        echo "<script>alert('Error placing order. Please try again.'); window.location.href = 'payment.php';</script>";
    }
} else {
    header('Location: payment.php');
    exit;
}

