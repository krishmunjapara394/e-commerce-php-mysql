<?php
// Determine base path
$base_path = '';
if(strpos($_SERVER['PHP_SELF'], '/users_area/') !== false || 
   strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    $base_path = '../';
}

include($base_path . 'includes/connect.php');
include($base_path . 'functions/common_functions.php');
session_start();

header('Content-Type: application/json');

$getIpAddress = getIPAddress();
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_quantity') {
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 0);
        
        if ($product_id > 0 && $quantity > 0) {
            $update_query = "UPDATE `card_details` SET quantity = $quantity WHERE ip_address='$getIpAddress' AND product_id=$product_id";
            $update_result = mysqli_query($con, $update_query);
            
            if ($update_result) {
                // Get updated price
                $product_query = "SELECT product_price FROM `products` WHERE product_id=$product_id";
                $product_result = mysqli_query($con, $product_query);
                $product_row = mysqli_fetch_assoc($product_result);
                $product_price = $product_row['product_price'];
                $item_total = $product_price * $quantity;
                
                // Calculate cart total
                $cart_query = "SELECT cd.quantity, p.product_price FROM `card_details` cd 
                              JOIN `products` p ON cd.product_id = p.product_id 
                              WHERE cd.ip_address='$getIpAddress'";
                $cart_result = mysqli_query($con, $cart_query);
                $cart_total = 0;
                while ($row = mysqli_fetch_assoc($cart_result)) {
                    $cart_total += $row['quantity'] * $row['product_price'];
                }
                
                // Get cart count
                $cart_count_query = "SELECT COUNT(*) as count FROM `card_details` WHERE ip_address='$getIpAddress'";
                $cart_count_result = mysqli_query($con, $cart_count_query);
                $cart_count_row = mysqli_fetch_assoc($cart_count_result);
                
                $response = [
                    'success' => true,
                    'message' => 'Quantity updated successfully',
                    'item_total' => number_format($item_total, 2),
                    'cart_total' => number_format($cart_total, 2),
                    'cart_count' => $cart_count_row['count']
                ];
            } else {
                $response['message'] = 'Failed to update quantity';
            }
        } else {
            $response['message'] = 'Invalid product or quantity';
        }
    } 
    elseif ($action === 'remove_item') {
        $product_id = intval($_POST['product_id'] ?? 0);
        
        if ($product_id > 0) {
            $delete_query = "DELETE FROM `card_details` WHERE ip_address='$getIpAddress' AND product_id=$product_id";
            $delete_result = mysqli_query($con, $delete_query);
            
            if ($delete_result) {
                // Calculate cart total after removal
                $cart_query = "SELECT cd.quantity, p.product_price FROM `card_details` cd 
                              JOIN `products` p ON cd.product_id = p.product_id 
                              WHERE cd.ip_address='$getIpAddress'";
                $cart_result = mysqli_query($con, $cart_query);
                $cart_total = 0;
                $item_count = mysqli_num_rows($cart_result);
                
                while ($row = mysqli_fetch_assoc($cart_result)) {
                    $cart_total += $row['quantity'] * $row['product_price'];
                }
                
                $response = [
                    'success' => true,
                    'message' => 'Item removed successfully',
                    'cart_total' => number_format($cart_total, 2),
                    'item_count' => $item_count,
                    'cart_count' => $item_count
                ];
            } else {
                $response['message'] = 'Failed to remove item';
            }
        } else {
            $response['message'] = 'Invalid product ID';
        }
    }
}

echo json_encode($response);
exit;
?>

