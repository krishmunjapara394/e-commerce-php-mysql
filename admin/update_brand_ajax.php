<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
session_start();

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if(isset($_POST['update_brand_ajax'])){
    // Validate and sanitize brand_id
    if(!isset($_POST['brand_id']) || empty($_POST['brand_id'])){
        $response['message'] = 'Brand ID is required.';
        echo json_encode($response);
        exit;
    }
    
    $brand_id = (int)$_POST['brand_id'];
    
    if($brand_id <= 0){
        $response['message'] = 'Invalid brand ID.';
        echo json_encode($response);
        exit;
    }
    
    // Validate and sanitize brand_title
    if(!isset($_POST['brand_title']) || empty(trim($_POST['brand_title']))){
        $response['message'] = 'Please fill the brand title field.';
        echo json_encode($response);
        exit;
    }
    
    $brand_title = mysqli_real_escape_string($con, trim($_POST['brand_title']));
    
    // Check if brand exists
    $check_brand_query = "SELECT * FROM `brands` WHERE brand_id = $brand_id";
    $check_brand_result = mysqli_query($con, $check_brand_query);
    
    if(!$check_brand_result || mysqli_num_rows($check_brand_result) == 0){
        $response['message'] = 'Brand not found.';
        echo json_encode($response);
        exit;
    }
    
    // Update query
    $update_brand_query = "UPDATE `brands` SET brand_title='$brand_title' WHERE brand_id = $brand_id";
    $update_brand_result = mysqli_query($con, $update_brand_query);
    
    if($update_brand_result){
        $response['status'] = 'success';
        $response['message'] = 'Brand updated successfully!';
    } else {
        $response['message'] = 'Failed to update brand: ' . mysqli_error($con);
    }
} else {
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>

