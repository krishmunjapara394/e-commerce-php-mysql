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
$user_id = 0;
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $get_user_query = "SELECT * FROM `user_table` WHERE username='$username'";
    $get_user_result = mysqli_query($con, $get_user_query);
    if ($get_user_result && mysqli_num_rows($get_user_result) > 0) {
        $user_row = mysqli_fetch_array($get_user_result);
        $user_id = $user_row['user_id'];
    }
}

// Get cart count
$cart_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress'";
$cart_result = mysqli_query($con, $cart_query);
$cart_count = mysqli_num_rows($cart_result);

// Get wishlist count
$wishlist_count = 0;
$check_table = "SHOW TABLES LIKE 'wishlist'";
$table_exists = mysqli_query($con, $check_table);
if (mysqli_num_rows($table_exists) > 0) {
    if ($user_id > 0) {
        $wishlist_query = "SELECT COUNT(*) as count FROM `wishlist` WHERE user_id=$user_id";
    } else {
        $wishlist_query = "SELECT COUNT(*) as count FROM `wishlist` WHERE ip_address='$getIpAddress' AND user_id=0";
    }
    $wishlist_result = mysqli_query($con, $wishlist_query);
    if ($wishlist_result) {
        $wishlist_row = mysqli_fetch_assoc($wishlist_result);
        $wishlist_count = $wishlist_row['count'];
    }
}

echo json_encode([
    'success' => true,
    'cart_count' => $cart_count,
    'wishlist_count' => $wishlist_count
]);
exit;
?>

