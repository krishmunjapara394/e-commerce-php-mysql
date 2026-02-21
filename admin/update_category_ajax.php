<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
session_start();

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if(isset($_POST['update_category_ajax'])){
    // Validate and sanitize category_id
    if(!isset($_POST['category_id']) || empty($_POST['category_id'])){
        $response['message'] = 'Category ID is required.';
        echo json_encode($response);
        exit;
    }
    
    $category_id = (int)$_POST['category_id'];
    
    if($category_id <= 0){
        $response['message'] = 'Invalid category ID.';
        echo json_encode($response);
        exit;
    }
    
    // Validate and sanitize category_title
    if(!isset($_POST['category_title']) || empty(trim($_POST['category_title']))){
        $response['message'] = 'Please fill the category title field.';
        echo json_encode($response);
        exit;
    }
    
    $category_title = mysqli_real_escape_string($con, trim($_POST['category_title']));
    
    // Check if category exists
    $check_category_query = "SELECT * FROM `categories` WHERE category_id = $category_id";
    $check_category_result = mysqli_query($con, $check_category_query);
    
    if(!$check_category_result || mysqli_num_rows($check_category_result) == 0){
        $response['message'] = 'Category not found.';
        echo json_encode($response);
        exit;
    }
    
    // Update query
    $update_category_query = "UPDATE `categories` SET category_title='$category_title' WHERE category_id = $category_id";
    $update_category_result = mysqli_query($con, $update_category_query);
    
    if($update_category_result){
        $response['status'] = 'success';
        $response['message'] = 'Category updated successfully!';
    } else {
        $response['message'] = 'Failed to update category: ' . mysqli_error($con);
    }
} else {
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>

