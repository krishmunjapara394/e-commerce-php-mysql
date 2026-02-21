<?php
include('../includes/connect.php');
if(isset($_POST['insert_product'])){
    $product_title=$_POST['product_title'];
    $product_description=$_POST['product_description'];
    $product_keywords=$_POST['product_keywords'];
    $product_category=$_POST['product_category'];
    $product_brand=$_POST['product_brand'];
    $product_price=$_POST['product_price'];
    $product_status='true';
    //access images
    $product_image_one=$_FILES['product_image_one']['name'];
    $product_image_two=$_FILES['product_image_two']['name'];
    $product_image_three=$_FILES['product_image_three']['name'];
    //access images tmp name
    $temp_image_one=$_FILES['product_image_one']['tmp_name'];
    $temp_image_two=$_FILES['product_image_two']['tmp_name'];
    $temp_image_three=$_FILES['product_image_three']['tmp_name'];
    //checking empty condition
    if($product_title == '' || $product_description == '' || $product_keywords == '' || $product_category == '' || $product_brand == '' || empty($product_price) || empty($product_image_one) || empty($product_image_two) || empty($product_image_three)){
        echo "<script>showToast('All fields are required', 'error');</script>";
        exit();
    }else{
        //move folders
        move_uploaded_file($temp_image_one,"./product_images/$product_image_one");
        move_uploaded_file($temp_image_two,"./product_images/$product_image_two");
        move_uploaded_file($temp_image_three,"./product_images/$product_image_three");
        //insert query in db
        $insert_query = "INSERT INTO `products` (product_title,product_description,product_keywords,category_id,brand_id,product_image_one,product_image_two,product_image_three,product_price,date,status) VALUES ('$product_title','$product_description','$product_keywords','$product_category','$product_brand','$product_image_one','$product_image_two','$product_image_three','$product_price',NOW(),'$product_status')";
        $insert_result=mysqli_query($con,$insert_query);
        if($insert_result){
        echo "<script>showToast('Product inserted successfully', 'success');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Products - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="admin-header-section mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="admin-title-bar"></div>
                        <span class="admin-title-tag">Products</span>
                    </div>
                    <h2 class="admin-main-title mb-0">Insert Products</h2>
                </div>
                <p class="text-muted mb-0">Add a new product to your store</p>
            </div>
            <div>
                <a href="index.php?view_products" class="btn btn-outline-primary">
                    <i class="fas fa-list me-2"></i>View Products
                </a>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- title -->
                            <div class="form-outline mb-4">
                                <label for="product_title" class="form-label fw-medium">Product Title</label>
                                <input type="text" placeholder="Enter Product Title" name="product_title" id="product_title" class="form-control" autocomplete="off" required>
                            </div>
                            <!-- description -->
                            <div class="form-outline mb-4">
                                <label for="product_description" class="form-label fw-medium">Product Description</label>
                                <textarea placeholder="Enter Product Description" name="product_description" id="product_description" class="form-control" rows="4" autocomplete="off" required></textarea>
                            </div>
                            <!-- keywords -->
                            <div class="form-outline mb-4">
                                <label for="product_keywords" class="form-label fw-medium">Product Keywords</label>
                                <input type="text" placeholder="Enter Product Keywords (comma separated)" name="product_keywords" id="product_keywords" class="form-control" autocomplete="off" required>
                            </div>
                            <!-- categories -->
                            <div class="form-outline mb-4">
                                <label for="product_category" class="form-label fw-medium">Category</label>
                                <select class="form-select" name="product_category" id="product_category" required>
                                    <option selected disabled>Select a Category</option>
                                    <?php
                                    $select_query = 'SELECT * FROM `categories`';
                                    $select_result = mysqli_query($con, $select_query);
                                    while ($row = mysqli_fetch_assoc($select_result)) {
                                        $category_title = $row['category_title'];
                                        $category_id = $row['category_id'];
                                        echo "<option value='$category_id'>$category_title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- brands -->
                            <div class="form-outline mb-4">
                                <label for="product_brand" class="form-label fw-medium">Brand</label>
                                <select class="form-select" name="product_brand" id="product_brand" required>
                                    <option selected disabled>Select a Brand</option>
                                    <?php
                                    $select_query = 'SELECT * FROM `brands`';
                                    $select_result = mysqli_query($con, $select_query);
                                    while ($row = mysqli_fetch_assoc($select_result)) {
                                        $brand_title = $row['brand_title'];
                                        $brand_id = $row['brand_id'];
                                        echo "<option value='$brand_id'>$brand_title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Price -->
                            <div class="form-outline mb-4">
                                <label for="product_price" class="form-label fw-medium">Product Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" placeholder="0.00" name="product_price" id="product_price" class="form-control" autocomplete="off" required>
                                </div>
                            </div>
                            <!-- Image one -->
                            <div class="form-outline mb-4">
                                <label for="product_image_one" class="form-label fw-medium">Product Image One</label>
                                <input type="file" name="product_image_one" id="product_image_one" class="form-control" accept="image/*" required>
                                <small class="text-muted">Main product image</small>
                            </div>
                            <!-- Image two -->
                            <div class="form-outline mb-4">
                                <label for="product_image_two" class="form-label fw-medium">Product Image Two</label>
                                <input type="file" name="product_image_two" id="product_image_two" class="form-control" accept="image/*" required>
                                <small class="text-muted">Secondary product image</small>
                            </div>
                            <!-- Image three -->
                            <div class="form-outline mb-4">
                                <label for="product_image_three" class="form-label fw-medium">Product Image Three</label>
                                <input type="file" name="product_image_three" id="product_image_three" class="form-control" accept="image/*" required>
                                <small class="text-muted">Additional product image</small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="index.php?view_products" class="btn btn-light px-4">Cancel</a>
                        <button type="submit" name="insert_product" class="btn btn-primary px-4">
                            <i class="fas fa-plus me-2"></i>Insert Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Admin Header Section - Matching Home Page Design */
        .admin-header-section {
            margin-bottom: 1rem;
        }
        .admin-title-bar {
            width: 4px;
            height: 36px;
            background-color: #1e40af;
            border-radius: 4px;
        }
        .admin-title-tag {
            color: #1e40af;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .admin-main-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            line-height: 1.2;
        }
        .card {
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        }
        .btn-primary {
            background-color: #1e40af;
            border-color: #1e40af;
        }
        .btn-primary:hover {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
        }
        .btn-outline-primary {
            border-color: #1e40af;
            color: #1e40af;
        }
        .btn-outline-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
            color: #fff;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.25);
        }
    </style>
</body>

</html>