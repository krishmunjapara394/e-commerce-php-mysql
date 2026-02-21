<?php
    if(isset($_GET['delete_category'])){
        $delete_id = $_GET['delete_category'];
        $delete_query = "DELETE FROM `categories` WHERE category_id = $delete_id";
        $delete_result = mysqli_query($con,$delete_query);
        if($delete_result){
            echo "<script>showToast('Category deleted successfully', 'success'); setTimeout(() => { window.location.href = 'index.php?view_categories'; }, 2000);</script>";
        }
    }
?>