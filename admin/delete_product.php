<?php
    if(isset($_GET['delete_product'])){
        $delete_id = $_GET['delete_product'];
        $delete_query = "DELETE FROM `products` WHERE product_id = $delete_id";
        $delete_result = mysqli_query($con,$delete_query);
        if($delete_result){
            echo "<script>showToast('Product deleted successfully', 'success'); setTimeout(() => { window.location.href = 'index.php?view_products'; }, 2000);</script>";
        }
    }
?>