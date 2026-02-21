<?php
include('../includes/connect.php');
session_start();

if(isset($_SESSION['admin_username'])){
    if(isset($_GET['delete_contact'])){
        $contact_id = $_GET['delete_contact'];
        
        // Check if table exists
        $check_table = "SHOW TABLES LIKE 'contact_messages'";
        $table_exists = mysqli_query($con, $check_table);
        
        if (mysqli_num_rows($table_exists) > 0) {
            $delete_query = "DELETE FROM `contact_messages` WHERE contact_id=$contact_id";
            $delete_result = mysqli_query($con, $delete_query);
            
            if($delete_result){
                echo "<script>showToast('Contact message deleted successfully', 'success');</script>";
            } else {
                echo "<script>showToast('Error deleting contact message', 'error');</script>";
            }
        }
    }
    echo "<script>window.open('index.php?list_contacts','_self')</script>";
} else {
    echo "<script>window.open('./admin_login.php','_self')</script>";
}
?>

