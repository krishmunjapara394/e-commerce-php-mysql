<?php
$con = new mysqli('127.0.0.1:3308', 'root', '', 'ecommerce_1');

if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}
?>