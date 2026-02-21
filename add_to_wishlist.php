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
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_to_wishlist') {
        $product_id = intval($_POST['product_id'] ?? 0);
        
        if ($product_id > 0) {
            // Get user IP address (for guest users) or user ID (for logged in users)
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
            
            // Check if wishlist table exists, create if not
            $check_table = "SHOW TABLES LIKE 'wishlist'";
            $table_exists = mysqli_query($con, $check_table);
            
            if (mysqli_num_rows($table_exists) == 0) {
                $create_table = "CREATE TABLE `wishlist` (
                    `wishlist_id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) DEFAULT 0,
                    `ip_address` varchar(255) NOT NULL,
                    `product_id` int(11) NOT NULL,
                    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                    PRIMARY KEY (`wishlist_id`),
                    UNIQUE KEY `unique_wishlist` (`user_id`, `ip_address`, `product_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
                mysqli_query($con, $create_table);
            }
            
            // Check if item already exists in wishlist
            if ($user_id > 0) {
                $check_query = "SELECT * FROM `wishlist` WHERE user_id=$user_id AND product_id=$product_id";
            } else {
                $check_query = "SELECT * FROM `wishlist` WHERE ip_address='$getIpAddress' AND product_id=$product_id AND user_id=0";
            }
            
            $check_result = mysqli_query($con, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                $response = [
                    'success' => false,
                    'message' => 'Item already in wishlist'
                ];
            } else {
                // Insert into wishlist
                $insert_query = "INSERT INTO `wishlist` (user_id, ip_address, product_id) VALUES ($user_id, '$getIpAddress', $product_id)";
                $insert_result = mysqli_query($con, $insert_query);
                
                if ($insert_result) {
                    // Get wishlist count
                    if ($user_id > 0) {
                        $count_query = "SELECT COUNT(*) as count FROM `wishlist` WHERE user_id=$user_id";
                    } else {
                        $count_query = "SELECT COUNT(*) as count FROM `wishlist` WHERE ip_address='$getIpAddress' AND user_id=0";
                    }
                    $count_result = mysqli_query($con, $count_query);
                    $count_row = mysqli_fetch_assoc($count_result);
                    
                    $response = [
                        'success' => true,
                        'message' => 'Added to wishlist successfully',
                        'wishlist_count' => $count_row['count']
                    ];
                } else {
                    $response['message'] = 'Failed to add to wishlist';
                }
            }
        } else {
            $response['message'] = 'Invalid product ID';
        }
    }
    elseif ($action === 'remove_from_wishlist') {
        $product_id = intval($_POST['product_id'] ?? 0);
        
        if ($product_id > 0) {
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
            
            if ($user_id > 0) {
                $delete_query = "DELETE FROM `wishlist` WHERE user_id=$user_id AND product_id=$product_id";
            } else {
                $delete_query = "DELETE FROM `wishlist` WHERE ip_address='$getIpAddress' AND product_id=$product_id AND user_id=0";
            }
            
            $delete_result = mysqli_query($con, $delete_query);
            
            if ($delete_result) {
                // Get wishlist count
                if ($user_id > 0) {
                    $count_query = "SELECT COUNT(*) as count FROM `wishlist` WHERE user_id=$user_id";
                } else {
                    $count_query = "SELECT COUNT(*) as count FROM `wishlist` WHERE ip_address='$getIpAddress' AND user_id=0";
                }
                $count_result = mysqli_query($con, $count_query);
                $count_row = mysqli_fetch_assoc($count_result);
                
                $response = [
                    'success' => true,
                    'message' => 'Removed from wishlist successfully',
                    'wishlist_count' => $count_row['count']
                ];
            } else {
                $response['message'] = 'Failed to remove from wishlist';
            }
        } else {
            $response['message'] = 'Invalid product ID';
        }
    }
    elseif ($action === 'check_wishlist') {
        $product_id = intval($_POST['product_id'] ?? 0);
        
        if ($product_id > 0) {
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
            
            if ($user_id > 0) {
                $check_query = "SELECT * FROM `wishlist` WHERE user_id=$user_id AND product_id=$product_id";
            } else {
                $check_query = "SELECT * FROM `wishlist` WHERE ip_address='$getIpAddress' AND product_id=$product_id AND user_id=0";
            }
            
            $check_result = mysqli_query($con, $check_query);
            $is_in_wishlist = mysqli_num_rows($check_result) > 0;
            
            $response = [
                'success' => true,
                'is_in_wishlist' => $is_in_wishlist
            ];
        }
    }
}

echo json_encode($response);
exit;
?>

