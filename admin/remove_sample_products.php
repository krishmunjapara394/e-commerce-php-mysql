<?php
include('../includes/connect.php');
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_username'])) {
    die("Access denied. Please login as admin.");
}

// List of all sample product titles that were added
$sample_products = [
    // Mobiles (Category 1)
    'iPhone 15 Pro Max',
    'Samsung Galaxy S24 Ultra',
    'Oppo Find X6 Pro',
    'Nokia G60 5G',
    'Samsung Galaxy A54',
    'iPhone 14',
    
    // Books (Category 2)
    'The Great Gatsby',
    'To Kill a Mockingbird',
    '1984 by George Orwell',
    'Pride and Prejudice',
    'The Catcher in the Rye',
    'Harry Potter Complete Set',
    
    // Food (Category 3)
    'Premium Coffee Beans',
    'Organic Green Tea',
    'Dark Chocolate Bar',
    'Honey Jar 500g',
    'Olive Oil Extra Virgin',
    'Gourmet Pasta Set',
    
    // Clothes (Category 4)
    'Nike Air Max 270',
    'Polo Ralph Lauren Shirt',
    'Nike Dri-FIT T-Shirt',
    'Denim Jeans Classic',
    'Winter Jacket',
    'Leather Belt',
    
    // HeadPhones (Category 5)
    'Sony WH-1000XM5',
    'Apple AirPods Pro',
    'Samsung Galaxy Buds2 Pro',
    'Beats Studio3 Wireless',
    'JBL Tune 760NC',
    'Sennheiser HD 450BT',
    
    // Electronics (Category 6)
    'Dell XPS 15 Laptop',
    'HP Pavilion 14',
    'Lenovo ThinkPad X1',
    'Apple MacBook Pro 16',
    'Samsung 4K Smart TV 55',
    'Canon EOS R6 Mark II',
    'Nikon D850 DSLR',
    'iPad Pro 12.9',
    'Samsung Galaxy Tab S9',
    'PlayStation 5 Console',
    'Xbox Series X',
    'Nintendo Switch OLED',
    'LG 27-inch 4K Monitor',
    'Logitech MX Master 3',
    'Mechanical Keyboard RGB',
    'External SSD 1TB',
    'Wireless Charging Pad',
    'Smart Watch Series 8',
    'Fitness Tracker Band',
    
    // Accessories (Category 7)
    'Phone Case iPhone 15',
    'Laptop Stand Aluminum',
    'USB-C Hub 7-in-1',
    'Camera Tripod Professional',
    'Car Phone Mount',
    'Bluetooth Speaker Portable',
    'Power Bank 20000mAh',
    'Screen Protector Glass',
    'Laptop Backpack',
    'Webcam HD 1080p',
    'Gaming Mouse Pad',
    'Cable Management Kit',
];

// Get count before deletion
$count_query = "SELECT COUNT(*) as count FROM `products`";
$count_result = mysqli_query($con, $count_query);
$count_before = mysqli_fetch_assoc($count_result)['count'];

// Delete products
$deleted = 0;
$errors = [];

// Only delete if button is clicked
if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    foreach ($sample_products as $product_title) {
        $title = mysqli_real_escape_string($con, $product_title);
        
        // Delete from cart_details first (if exists)
        $delete_cart_query = "DELETE FROM `card_details` WHERE product_id IN (SELECT product_id FROM `products` WHERE product_title = '$title')";
        mysqli_query($con, $delete_cart_query);
        
        // Delete from wishlist first (if exists)
        $delete_wishlist_query = "DELETE FROM `wishlist` WHERE product_id IN (SELECT product_id FROM `products` WHERE product_title = '$title')";
        mysqli_query($con, $delete_wishlist_query);
        
        // Delete from orders_pending first (if exists)
        $delete_orders_query = "DELETE FROM `orders_pending` WHERE product_id IN (SELECT product_id FROM `products` WHERE product_title = '$title')";
        mysqli_query($con, $delete_orders_query);
        
        // Delete the product
        $delete_query = "DELETE FROM `products` WHERE product_title = '$title'";
        $result = mysqli_query($con, $delete_query);
        
        if ($result) {
            $deleted++;
        } else {
            $errors[] = "Failed to delete: $product_title - " . mysqli_error($con);
        }
    }
}

// Get count after deletion
$count_query_after = "SELECT COUNT(*) as count FROM `products`";
$count_result_after = mysqli_query($con, $count_query_after);
$count_after = mysqli_fetch_assoc($count_result_after)['count'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Sample Products</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .result-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: 50px auto;
        }
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
        }
        .danger-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="result-box">
        <h2 class="mb-4"><i class="fas fa-trash-alt"></i> Remove Sample Products</h2>
        
        <div class="info-box">
            <h5><i class="fas fa-info-circle"></i> About This Tool</h5>
            <p class="mb-0">This will remove <strong><?php echo count($sample_products); ?> sample products</strong> that were added using the "Insert 60 Sample Products" feature.</p>
            <p class="mb-0 mt-2"><strong>Current products in database:</strong> <?php echo $count_before; ?></p>
            <p class="mb-0 mt-2"><strong>Products to be removed:</strong> <?php echo count($sample_products); ?></p>
        </div>
        
        <?php if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes'): ?>
            <?php if ($deleted > 0): ?>
            <div class="alert alert-success">
                <h5><i class="fas fa-check-circle"></i> Success!</h5>
                <p><strong><?php echo $deleted; ?></strong> sample products deleted successfully.</p>
                <p class="mb-0">Products before: <strong><?php echo $count_before; ?></strong> | Products after: <strong><?php echo $count_after; ?></strong></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mt-3">
                <h5><i class="fas fa-exclamation-triangle"></i> Errors:</h5>
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <div class="mt-4">
                <a href="index.php?view_products" class="btn btn-primary"><i class="fas fa-eye"></i> View All Products</a>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            </div>
        <?php else: ?>
            <div class="danger-box">
                <h5><i class="fas fa-exclamation-triangle"></i> Warning</h5>
                <p>This will permanently delete <strong><?php echo count($sample_products); ?> sample products</strong> from your database.</p>
                <p><strong>This action cannot be undone!</strong></p>
                <p class="mb-0"><strong>Note:</strong> This will also remove these products from carts, wishlists, and pending orders.</p>
            </div>
            
            <div class="mt-4">
                <a href="?confirm=yes" class="btn btn-danger btn-lg"><i class="fas fa-trash-alt"></i> Yes, Delete All Sample Products</a>
                <a href="index.php" class="btn btn-secondary btn-lg"><i class="fas fa-times"></i> Cancel</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

