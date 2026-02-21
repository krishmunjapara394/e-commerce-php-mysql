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

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    
    if ($product_id > 0) {
        $getIpAddress = getIPAddress();
        $getProductId = $product_id;
        
        // Check if product already in cart
        $select_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress' AND product_id=$getProductId";
        $select_result = mysqli_query($con, $select_query);
        $num_of_rows = mysqli_num_rows($select_result);
        
        if ($num_of_rows > 0) {
            $response = [
                'success' => false,
                'message' => 'This item is already present in Cart'
            ];
        } else {
            $insert_query = "INSERT INTO `card_details` (product_id,ip_address,quantity) VALUES ($getProductId,'$getIpAddress',1)";
            $insert_result = mysqli_query($con, $insert_query);
            
            if ($insert_result) {
                // Get cart count
                $cart_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress'";
                $cart_result = mysqli_query($con, $cart_query);
                $cart_count = mysqli_num_rows($cart_result);
                
                $response = [
                    'success' => true,
                    'message' => 'Item added to Cart successfully!',
                    'cart_count' => $cart_count
                ];
            } else {
                $response['message'] = 'Failed to add item to cart';
            }
        }
    } else {
        $response['message'] = 'Invalid product ID';
    }
}

echo json_encode($response);
exit;
?>

