<?php
include('../includes/connect.php');
include('../functions/common_functions.php');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'An unknown error occurred.'];

if(isset($_POST['update_product'])){
    // Validate product_id
    if(!isset($_POST['product_id']) || empty($_POST['product_id'])){
        $response['message'] = 'Product ID is required.';
        echo json_encode($response);
        exit;
    }
    
    $product_id = (int)$_POST['product_id'];
    
    if($product_id <= 0){
        $response['message'] = 'Invalid product ID.';
        echo json_encode($response);
        exit;
    }
    
    // Get old data
    $get_old_data_query = "SELECT * FROM `products` WHERE product_id = $product_id";
    $get_old_data_result = mysqli_query($con, $get_old_data_query);
    
    if(!$get_old_data_result || mysqli_num_rows($get_old_data_result) == 0){
        $response['message'] = 'Product not found.';
        echo json_encode($response);
        exit;
    }
    
    $row_old_data = mysqli_fetch_array($get_old_data_result);
    $product_image_one_old = $row_old_data['product_image_one'];
    $product_image_two_old = $row_old_data['product_image_two'];
    $product_image_three_old = $row_old_data['product_image_three'];
    
    // Validate required fields
    if(!isset($_POST['product_title']) || empty(trim($_POST['product_title']))){
        $response['message'] = 'Product title is required.';
        echo json_encode($response);
        exit;
    }
    
    if(!isset($_POST['product_description']) || empty(trim($_POST['product_description']))){
        $response['message'] = 'Product description is required.';
        echo json_encode($response);
        exit;
    }
    
    if(!isset($_POST['product_keywords']) || empty(trim($_POST['product_keywords']))){
        $response['message'] = 'Product keywords are required.';
        echo json_encode($response);
        exit;
    }
    
    if(!isset($_POST['product_category']) || empty($_POST['product_category'])){
        $response['message'] = 'Product category is required.';
        echo json_encode($response);
        exit;
    }
    
    if(!isset($_POST['product_brand']) || empty($_POST['product_brand'])){
        $response['message'] = 'Product brand is required.';
        echo json_encode($response);
        exit;
    }
    
    if(!isset($_POST['product_price']) || empty($_POST['product_price'])){
        $response['message'] = 'Product price is required.';
        echo json_encode($response);
        exit;
    }
    
    // Sanitize input
    $product_title = mysqli_real_escape_string($con, trim($_POST['product_title']));
    $product_description = mysqli_real_escape_string($con, trim($_POST['product_description']));
    $product_keywords = mysqli_real_escape_string($con, trim($_POST['product_keywords']));
    $product_category_id = (int)$_POST['product_category'];
    $product_brand_id = (int)$_POST['product_brand'];
    $product_price = floatval($_POST['product_price']);
    
    // Handle image uploads
    $product_image_one = !empty($_FILES['product_image_one']['name']) ? $_FILES['product_image_one']['name'] : $product_image_one_old;
    $product_image_two = !empty($_FILES['product_image_two']['name']) ? $_FILES['product_image_two']['name'] : $product_image_two_old;
    $product_image_three = !empty($_FILES['product_image_three']['name']) ? $_FILES['product_image_three']['name'] : $product_image_three_old;
    
    // Upload new images if provided
    if(!empty($_FILES['product_image_one']['name'])){
        $product_image_one_tmp = $_FILES['product_image_one']['tmp_name'];
        if(move_uploaded_file($product_image_one_tmp, "./product_images/$product_image_one")){
            // Image uploaded successfully
        } else {
            $product_image_one = $product_image_one_old; // Keep old image if upload fails
        }
    }
    if(!empty($_FILES['product_image_two']['name'])){
        $product_image_two_tmp = $_FILES['product_image_two']['tmp_name'];
        if(move_uploaded_file($product_image_two_tmp, "./product_images/$product_image_two")){
            // Image uploaded successfully
        } else {
            $product_image_two = $product_image_two_old; // Keep old image if upload fails
        }
    }
    if(!empty($_FILES['product_image_three']['name'])){
        $product_image_three_tmp = $_FILES['product_image_three']['tmp_name'];
        if(move_uploaded_file($product_image_three_tmp, "./product_images/$product_image_three")){
            // Image uploaded successfully
        } else {
            $product_image_three = $product_image_three_old; // Keep old image if upload fails
        }
    }
    
    // Update query
    $update_product_query = "UPDATE `products` SET 
        category_id=$product_category_id,
        brand_id=$product_brand_id,
        product_title='$product_title',
        product_description='$product_description',
        product_keywords='$product_keywords',
        product_image_one='$product_image_one',
        product_image_two='$product_image_two',
        product_image_three='$product_image_three',
        product_price='$product_price',
        date=NOW() 
        WHERE product_id = $product_id";
    
    $update_product_result = mysqli_query($con, $update_product_query);
    
    if($update_product_result){
        $response['success'] = true;
        $response['message'] = 'Product updated successfully!';
    } else {
        $response['message'] = 'Failed to update product: ' . mysqli_error($con);
    }
} else {
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>

