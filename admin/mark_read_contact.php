<?php
include('../includes/connect.php');
session_start();

if(isset($_SESSION['admin_username'])){
    if(isset($_GET['mark_read_contact'])){
        $contact_id = $_GET['mark_read_contact'];
        
        // Check if table exists
        $check_table = "SHOW TABLES LIKE 'contact_messages'";
        $table_exists = mysqli_query($con, $check_table);
        
        if (mysqli_num_rows($table_exists) > 0) {
            $update_query = "UPDATE `contact_messages` SET status='read' WHERE contact_id=$contact_id";
            $update_result = mysqli_query($con, $update_query);
            
            if($update_result){
                echo "<script>showToast('Contact message marked as read successfully', 'success');</script>";
            } else {
                echo "<script>showToast('Error updating contact message', 'error');</script>";
            }
        }
    }
    echo "<script>window.open('index.php?list_contacts','_self')</script>";
} else {
    echo "<script>window.open('./admin_login.php','_self')</script>";
}
?>

