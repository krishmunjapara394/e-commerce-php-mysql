<?php
    // fetch all data
    if(isset($_GET['edit_product'])){
        $edit_id = $_GET['edit_product'];
        $get_data_query = "SELECT * FROM `products` WHERE product_id = $edit_id";
        $get_data_result = mysqli_query($con,$get_data_query);
        $row_fetch_data = mysqli_fetch_array($get_data_result);
        $product_id = $row_fetch_data['product_id'];
        $product_title = $row_fetch_data['product_title'];
        $product_description = $row_fetch_data['product_description'];
        $product_keywords = $row_fetch_data['product_keywords'];
        $category_id = $row_fetch_data['category_id'];
        $brand_id = $row_fetch_data['brand_id'];
        $product_image_one_old = $row_fetch_data['product_image_one'];
        $product_image_two_old = $row_fetch_data['product_image_two'];
        $product_image_three_old = $row_fetch_data['product_image_three'];
        $product_price = $row_fetch_data['product_price'];
    }
    // edit product
    if(isset($_POST['update_product'])){
        $product_title = $_POST['product_title'];
        $product_description = $_POST['product_description'];
        $product_keywords = $_POST['product_keywords'];
        $product_category_id = $_POST['product_category'];
        $product_brand_id = $_POST['product_brand'];
        $product_image_one = $_FILES['product_image_one']['name'] == '' ? $product_image_one_old : $_FILES['product_image_one']['name'];
        $product_image_one_tmp = $_FILES['product_image_one']['tmp_name'];
        $product_image_two = $_FILES['product_image_two']['name'] == '' ? $product_image_two_old : $_FILES['product_image_two']['name'];
        $product_image_two_tmp = $_FILES['product_image_two']['tmp_name'];
        $product_image_three = $_FILES['product_image_three']['name'] == '' ? $product_image_three_old : $_FILES['product_image_three']['name'];
        $product_image_three_tmp = $_FILES['product_image_three']['tmp_name'];
        $product_price = $_POST['product_price'];
        
        // check empty fields 
        if(empty($product_title) || empty($product_description) || empty($product_keywords) || empty($product_category_id) || empty($product_brand_id) || empty($product_image_one) || empty($product_image_two) || empty($product_image_three) || empty($product_price)){
            echo "<script>showToast('Please fill all fields', 'error');</script>";
        }else{
            move_uploaded_file($product_image_one_tmp,"./product_images/$product_image_one");
            move_uploaded_file($product_image_two_tmp,"./product_images/$product_image_two");
            move_uploaded_file($product_image_three_tmp,"./product_images/$product_image_three");
            // update query 
            $update_product_query = "UPDATE `products` SET category_id=$product_category_id,brand_id=$product_brand_id,product_title='$product_title',product_description='$product_description',product_keywords='$product_keywords',product_image_one='$product_image_one',product_image_two='$product_image_two',product_image_three='$product_image_three',product_price='$product_price',date=NOW() WHERE product_id = $edit_id";
            $update_product_result = mysqli_query($con,$update_product_query);
            if($update_product_result){
                echo "<script>
                    if(typeof showToast === 'function') {
                        showToast('Product updated successfully', 'success');
                    } else {
                        alert('Product updated successfully');
                    }
                    setTimeout(() => { 
                        window.location.href = './index.php?view_products&edit_success=1'; 
                    }, 1500);
                </script>";
            }
        }
    }
    ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="admin-header-section mb-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="admin-title-bar"></div>
                    <span class="admin-title-tag">Products</span>
                </div>
                <h2 class="admin-main-title mb-0">Edit Product</h2>
            </div>
            <form action="" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3 mb-3">
                <div class="form-outline">
                    <label for="product_title" class="form-label">Product Title</label>
                    <input type="text" name="product_title" id="product_title" class="form-control" required value="<?php echo $product_title;?>">
                </div>
                <div class="form-outline">
                    <label for="product_description" class="form-label">Product Description</label>
                    <input type="text" name="product_description" id="product_description" class="form-control" required value="<?php echo $product_description;?>">
                </div>
                <div class="form-outline">
                    <label for="product_keywords" class="form-label">Product Keywords</label>
                    <input type="text" name="product_keywords" id="product_keywords" class="form-control" required value="<?php echo $product_keywords;?>">
                </div>
                <div class="form-outline">
                    <label for="product_category" class="form-label">Product Category</label>
                    <select name="product_category" id="product_category" class="form-select" required >
                        <?php
                        // fetch all category with selected
                        $select_category_query_all = "SELECT * FROM `categories`";
                        $select_category_result_all = mysqli_query($con,$select_category_query_all);
                        while($fetch_category_name_all = mysqli_fetch_array($select_category_result_all)){
                            $category_name_is_all = $fetch_category_name_all['category_title'];
                            $category_id_is_all = $fetch_category_name_all['category_id'];
                            echo $category_id_is_all == $category_id ? "
                            <option value='$category_id_is_all' selected>$category_name_is_all</option>
                        ": "
                        <option value='$category_id_is_all'>$category_name_is_all</option>
                    ";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-outline">
                    <label for="product_brand" class="form-label">Product Category</label>
                    <select name="product_brand" id="product_brand" class="form-select" required>
                        <?php
                        // fetch all brands with selected
                        $select_brand_query_all = "SELECT * FROM `brands`";
                        $select_brand_result_all = mysqli_query($con,$select_brand_query_all);
                        while($fetch_brand_name_all = mysqli_fetch_array($select_brand_result_all)){
                            $brand_name_is_all = $fetch_brand_name_all['brand_title'];
                            $brand_id_is_all = $fetch_brand_name_all['brand_id'];
                            echo $brand_id_is_all == $brand_id ? "
                            <option value='$brand_id_is_all' selected>$brand_name_is_all</option>
                        ": "
                        <option value='$brand_id_is_all'>$brand_name_is_all</option>
                    ";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-outline">
                    <label for="product_image_one" class="form-label">Product Image 1</label>
                    <div class="d-flex">
                        <input type="file" name="product_image_one" id="product_image_one" class="form-control" value="<?php echo $product_image_one_old;?>">
                        <img src="<?php echo $product_image_one_old;?>" alt="<?php echo $product_title;?>" class="img-thumbnail" width="100px">
                    </div>
                </div>
                <div class="form-outline">
                    <label for="product_image_two" class="form-label">Product Image 2</label>
                    <div class="d-flex">
                        <input type="file" name="product_image_two" id="product_image_two" class="form-control" value="<?php echo $product_image_two_old;?>">
                        <img src="<?php echo $product_image_two_old;?>" alt="<?php echo $product_title;?>" class="img-thumbnail" width="100px">
                    </div>
                </div>
                <div class="form-outline">
                    <label for="product_image_three" class="form-label">Product Image 3</label>
                    <div class="d-flex">
                        <input type="file" name="product_image_three" id="product_image_three" class="form-control" value="<?php echo $product_image_three_old;?>">
                        <img src="<?php echo $product_image_three_old;?>" alt="<?php echo $product_title;?>" class="img-thumbnail" width="100px">
                    </div>
                </div>
                <div class="form-outline">
                    <label for="product_price" class="form-label">Product Price</label>
                    <input type="number" name="product_price" id="product_price" class="form-control" required value="<?php echo $product_price;?>">
                </div>
                <div class="form-outline text-center">
                    <input type="submit" value="Update Product" class="btn btn-primary" name="update_product">
                </div>
            </form>
        </div>
    </div>
</div>
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
.btn-primary {
    background-color: #1e40af;
    border-color: #1e40af;
}
.btn-primary:hover {
    background-color: #1e3a8a;
    border-color: #1e3a8a;
}
.form-control:focus {
    border-color: #1e40af;
    box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.25);
}
</style>