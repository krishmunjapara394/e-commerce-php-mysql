<?php
session_start();
include('./includes/connect.php');

header('Content-Type: application/json');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode(['success' => false, 'message' => 'No search query provided', 'products' => []]);
    exit;
}

$search_query = mysqli_real_escape_string($con, trim($_GET['q']));
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100; // Limit results (default 100 for main area filtering)

// Search in product title and keywords
$search_sql = "SELECT product_id, product_title, product_image_one, product_price 
               FROM `products` 
               WHERE product_title LIKE '%$search_query%' 
               OR product_keywords LIKE '%$search_query%'
               LIMIT $limit";

$result = mysqli_query($con, $search_sql);

$products = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Get image URL (handles both local files and online URLs)
        $image_path = $row['product_image_one'];
        if (strpos($image_path, 'http://') === 0 || strpos($image_path, 'https://') === 0) {
            $image_url = $image_path;
        } else {
            // Use relative path from root - JavaScript will handle base path
            $image_url = 'admin/product_images/' . $image_path;
        }
        
        $products[] = [
            'id' => $row['product_id'],
            'title' => htmlspecialchars($row['product_title']),
            'image' => $image_url,
            'price' => number_format($row['product_price'], 2),
            'discount_price' => number_format($row['product_price'] * 0.85, 2)
        ];
    }
}

echo json_encode([
    'success' => true,
    'query' => htmlspecialchars($search_query),
    'count' => count($products),
    'products' => $products
]);
?>

