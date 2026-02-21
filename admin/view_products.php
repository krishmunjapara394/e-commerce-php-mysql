<?php
// Function to get edit product form HTML
function getEditProductForm($product_id, $con) {
    $get_data_query = "SELECT * FROM `products` WHERE product_id = $product_id";
    $get_data_result = mysqli_query($con, $get_data_query);
    
    if($get_data_result && mysqli_num_rows($get_data_result) > 0){
        $row_fetch_data = mysqli_fetch_array($get_data_result);
        $product_title = $row_fetch_data['product_title'];
        $product_description = $row_fetch_data['product_description'];
        $product_keywords = $row_fetch_data['product_keywords'];
        $category_id = $row_fetch_data['category_id'];
        $brand_id = $row_fetch_data['brand_id'];
        $product_image_one_old = $row_fetch_data['product_image_one'];
        $product_image_two_old = $row_fetch_data['product_image_two'];
        $product_image_three_old = $row_fetch_data['product_image_three'];
        $product_price = $row_fetch_data['product_price'];
        
        // Handle image URLs
        $image_one_url = (strpos($product_image_one_old, 'http://') === 0 || strpos($product_image_one_old, 'https://') === 0) ? $product_image_one_old : './product_images/' . $product_image_one_old;
        $image_two_url = (strpos($product_image_two_old, 'http://') === 0 || strpos($product_image_two_old, 'https://') === 0) ? $product_image_two_old : './product_images/' . $product_image_two_old;
        $image_three_url = (strpos($product_image_three_old, 'http://') === 0 || strpos($product_image_three_old, 'https://') === 0) ? $product_image_three_old : './product_images/' . $product_image_three_old;
        
        // Get categories
        $categories_options = '';
        $select_category_query_all = "SELECT * FROM `categories`";
        $select_category_result_all = mysqli_query($con, $select_category_query_all);
        while($fetch_category_name_all = mysqli_fetch_array($select_category_result_all)){
            $category_name_is_all = $fetch_category_name_all['category_title'];
            $category_id_is_all = $fetch_category_name_all['category_id'];
            $selected = $category_id_is_all == $category_id ? 'selected' : '';
            $categories_options .= "<option value='$category_id_is_all' $selected>$category_name_is_all</option>";
        }
        
        // Get brands
        $brands_options = '';
        $select_brand_query_all = "SELECT * FROM `brands`";
        $select_brand_result_all = mysqli_query($con, $select_brand_query_all);
        while($fetch_brand_name_all = mysqli_fetch_array($select_brand_result_all)){
            $brand_name_is_all = $fetch_brand_name_all['brand_title'];
            $brand_id_is_all = $fetch_brand_name_all['brand_id'];
            $selected = $brand_id_is_all == $brand_id ? 'selected' : '';
            $brands_options .= "<option value='$brand_id_is_all' $selected>$brand_name_is_all</option>";
        }
        
        $form = "
        <form method='post' enctype='multipart/form-data' id='editProductForm_$product_id' class='d-flex flex-column gap-3'>
            <input type='hidden' name='product_id' value='$product_id'>
            <input type='hidden' name='update_product' value='1'>
            <div class='form-outline'>
                <label for='product_title_$product_id' class='form-label fw-semibold'>Product Title <span class='text-danger'>*</span></label>
                <input type='text' name='product_title' id='product_title_$product_id' class='form-control' required value='" . htmlspecialchars($product_title, ENT_QUOTES) . "'>
            </div>
            <div class='form-outline'>
                <label for='product_description_$product_id' class='form-label fw-semibold'>Product Description <span class='text-danger'>*</span></label>
                <textarea name='product_description' id='product_description_$product_id' class='form-control' rows='3' required>" . htmlspecialchars($product_description, ENT_QUOTES) . "</textarea>
            </div>
            <div class='form-outline'>
                <label for='product_keywords_$product_id' class='form-label fw-semibold'>Product Keywords <span class='text-danger'>*</span></label>
                <input type='text' name='product_keywords' id='product_keywords_$product_id' class='form-control' required value='" . htmlspecialchars($product_keywords, ENT_QUOTES) . "'>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='form-outline'>
                        <label for='product_category_$product_id' class='form-label fw-semibold'>Category <span class='text-danger'>*</span></label>
                        <select name='product_category' id='product_category_$product_id' class='form-select' required>
                            $categories_options
                        </select>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class='form-outline'>
                        <label for='product_brand_$product_id' class='form-label fw-semibold'>Brand <span class='text-danger'>*</span></label>
                        <select name='product_brand' id='product_brand_$product_id' class='form-select' required>
                            $brands_options
                        </select>
                    </div>
                </div>
            </div>
            <div class='form-outline'>
                <label for='product_price_$product_id' class='form-label fw-semibold'>Product Price <span class='text-danger'>*</span></label>
                <input type='number' name='product_price' id='product_price_$product_id' class='form-control' step='0.01' required value='$product_price'>
            </div>
            <div class='form-outline'>
                <label class='form-label fw-semibold'>Product Images</label>
                <div class='row g-3'>
                    <div class='col-md-4'>
                        <label class='form-label small'>Image 1</label>
                        <input type='file' name='product_image_one' class='form-control form-control-sm mb-2'>
                        <img src='$image_one_url' alt='Image 1' class='img-thumbnail' style='width: 100%; max-width: 150px; height: auto;'>
                    </div>
                    <div class='col-md-4'>
                        <label class='form-label small'>Image 2</label>
                        <input type='file' name='product_image_two' class='form-control form-control-sm mb-2'>
                        <img src='$image_two_url' alt='Image 2' class='img-thumbnail' style='width: 100%; max-width: 150px; height: auto;'>
                    </div>
                    <div class='col-md-4'>
                        <label class='form-label small'>Image 3</label>
                        <input type='file' name='product_image_three' class='form-control form-control-sm mb-2'>
                        <img src='$image_three_url' alt='Image 3' class='img-thumbnail' style='width: 100%; max-width: 150px; height: auto;'>
                    </div>
                </div>
            </div>
            <div class='d-flex gap-2 justify-content-end pt-3 border-top'>
                <button type='button' class='btn btn-light px-4' data-bs-dismiss='modal'>Cancel</button>
                <button type='submit' name='update_product' class='btn btn-primary px-4'>
                    <i class='fas fa-save me-2'></i>Update Product
                </button>
            </div>
        </form>";
        
        return $form;
    }
    return "<p class='text-danger'>Product not found.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Prevent Bootstrap from creating modal backdrop
        (function() {
            if(typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const originalModal = bootstrap.Modal;
                bootstrap.Modal = function(element, config) {
                    const defaultConfig = config || {};
                    defaultConfig.backdrop = false; // Disable backdrop
                    return new originalModal(element, defaultConfig);
                };
                // Copy static methods
                Object.setPrototypeOf(bootstrap.Modal, originalModal);
                bootstrap.Modal.prototype = originalModal.prototype;
            }
        })();
        
        // Toast Helper Function (if not already defined)
        if(typeof showToast === 'undefined') {
            window.showToast = function(message, type = 'success') {
                try {
                    if(typeof Toastify === 'undefined') {
                        // Fallback to alert if Toastify is not available
                        alert(message);
                        return;
                    }
                    
                    const colors = {
                        success: {
                            background: 'linear-gradient(to right, #10b981, #059669)',
                            icon: '✓'
                        },
                        error: {
                            background: 'linear-gradient(to right, #ef4444, #dc2626)',
                            icon: '✕'
                        },
                        warning: {
                            background: 'linear-gradient(to right, #f59e0b, #d97706)',
                            icon: '⚠'
                        },
                        info: {
                            background: 'linear-gradient(to right, #1e40af, #1e3a8a)',
                            icon: 'ℹ'
                        }
                    };
                    
                    const config = colors[type] || colors.info;
                    
                    Toastify({
                        text: message,
                        duration: 4000,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: config.background,
                            borderRadius: "8px",
                            boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                            padding: "16px 20px",
                            fontSize: "14px",
                            fontWeight: "500",
                            color: "#ffffff"
                        },
                        onClick: function() {}
                    }).showToast();
                } catch(error) {
                    console.error('Error showing toast:', error);
                    alert(message);
                }
            };
        }
    </script>
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
                    <h2 class="admin-main-title mb-0">All Products</h2>
                </div>
                <p class="text-muted mb-0">Manage your store products</p>
            </div>
            <div>
                <a href="./insert_product.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Product
                </a>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-4">ID</th>
                                <th class="border-0">Product Title</th>
                                <th class="border-0">Image</th>
                                <th class="border-0">Price</th>
                                <th class="border-0">Total Sold</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-end rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                        //get product info
                        $get_product_query = "SELECT * FROM `products`";
                        $get_product_result = mysqli_query($con,$get_product_query);
                        $id_number = 1;
                        while($row_fetch_products = mysqli_fetch_array($get_product_result)){
                            $product_id = $row_fetch_products['product_id'];
                            $product_title = $row_fetch_products['product_title'];
                            $product_image_one = $row_fetch_products['product_image_one'];
                            $product_price = $row_fetch_products['product_price'];
                            $product_status = $row_fetch_products['status'];
                            
                            // Handle both online URLs and local files
                            if (strpos($product_image_one, 'http://') === 0 || strpos($product_image_one, 'https://') === 0) {
                                $image_url = $product_image_one;
                            } else {
                                $image_url = './product_images/' . $product_image_one;
                            }
                            
                            //get product total sold 
                            $get_count_sold = "SELECT * FROM `orders_pending` WHERE product_id = $product_id";
                            $get_count_sold_result = mysqli_query($con,$get_count_sold);
                            $quantity_sold = 0;
                            $quantity_sold_of_each_product = 0;
                            while($get_fetch_data_sold = mysqli_fetch_array($get_count_sold_result)){
                                $quantity_sold = $get_fetch_data_sold['quantity'];
                                $quantity_sold_of_each_product +=$quantity_sold;
                            }
                            echo "
                            <tr>
                            <td class='ps-4'>$id_number</td>
                            <td>
                                <div class='d-flex align-items-center'>
                                    <div class='bg-light rounded p-2 me-3'>
                                        <i class='fas fa-box text-primary'></i>
                                    </div>
                                    <span class='fw-medium'>$product_title</span>
                                </div>
                            </td>
                            <td>
                                <img src='$image_url' alt='$product_title' width='60px' height='60px' class='img-thumbnail rounded' style='object-fit: cover;'/>
                            </td>
                            <td><span class='fw-bold text-2'>\$$product_price</span></td>
                            <td><span class='badge bg-info'>$quantity_sold_of_each_product</span></td>
                            <td>
                                " . ($product_status == 'true' ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-secondary'>Inactive</span>") . "
                            </td>
                            <td class='text-end pe-4'>
                                <button type='button' class='btn btn-sm btn-outline-primary me-2' data-bs-toggle='modal' data-bs-target='#editModal_$product_id' title='Edit'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                <button type='button' class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal_$product_id' title='Delete'>
                                    <i class='fas fa-trash-alt'></i>
                                </button>
                                <!-- Edit Modal -->
                                <div class='modal fade' id='editModal_$product_id' tabindex='-1' aria-labelledby='editModalLabel_$product_id' aria-hidden='true' data-product-id='$product_id' data-bs-backdrop='false' data-bs-keyboard='true'>
                                    <div class='modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable'>
                                        <div class='modal-content border-0 shadow-lg'>
                                            <div class='modal-header border-0 pb-2 bg-gradient-to-r from-[#1e40af] to-[#3b82f6] text-white'>
                                                <div class='w-100'>
                                                    <div class='d-flex align-items-center gap-3 mb-2'>
                                                        <div style='width: 4px; height: 24px; background-color: rgba(255,255,255,0.8); border-radius: 4px;'></div>
                                                        <span style='color: rgba(255,255,255,0.9); font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;'>Edit Product</span>
                                                    </div>
                                                    <h5 class='modal-title fw-bold mb-0' id='editModalLabel_$product_id'>
                                                        <i class='fas fa-edit me-2'></i>$product_title
                                                    </h5>
                                                </div>
                                                <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body p-4' id='editFormContainer_$product_id'>
                                                " . getEditProductForm($product_id, $con) . "
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete Modal -->
                                <div class='modal fade' id='deleteModal_$product_id' tabindex='-1' aria-hidden='true' data-bs-backdrop='false' data-bs-keyboard='true'>
                                    <div class='modal-dialog modal-dialog-centered'>
                                        <div class='modal-content'>
                                            <div class='modal-body p-4 text-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <div class='bg-danger bg-opacity-10 rounded-circle p-4 mb-3'>
                                                        <i class='fas fa-exclamation-triangle text-danger fa-2x'></i>
                                                    </div>
                                                    <h5 class='mb-3'>Delete Product</h5>
                                                    <p class='text-muted mb-4'>Are you sure you want to delete \"$product_title\"? This action cannot be undone.</p>
                                                    <div class='d-flex gap-2'>
                                                        <button type='button' class='btn btn-light px-4' data-bs-dismiss='modal'>Cancel</button>
                                                        <a href='index.php?delete_product=$product_id' class='btn btn-danger px-4'>Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                            ";

                            $id_number++;
                        }
                    ?>
                        </tbody>
                    </table>
                </div>
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
        .card {
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        }
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
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
        .btn-primary {
            background-color: #1e40af;
            border-color: #1e40af;
        }
        .btn-primary:hover {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
        }
        .text-primary {
            color: #1e40af !important;
        }
        .form-control:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.25);
        }
        .table tbody tr {
            transition: background-color 0.2s;
        }
        .table tbody tr:hover {
            background-color: rgba(30, 64, 175, 0.05);
        }
        
        /* Modal Overlay Styling - Remove dark overlay */
        .modal-backdrop {
            background-color: transparent !important;
            backdrop-filter: none !important;
            z-index: 10050 !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
        
        .modal-backdrop.show {
            z-index: 10050 !important;
            display: none !important;
            background-color: transparent !important;
            opacity: 0 !important;
        }
        
        /* Hide backdrop when modal is open */
        body.modal-open .modal-backdrop {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
        
        .modal {
            z-index: 10060 !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            padding: 0 !important;
        }
        
        .modal.show {
            z-index: 10060 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .modal.fade {
            z-index: 10060 !important;
        }
        
        .modal.show .modal-dialog {
            transform: translate(0, 0);
        }
        
        .modal-dialog {
            z-index: 10070 !important;
            position: relative !important;
            margin: 2rem auto !important;
            max-width: 90% !important;
            max-height: 90vh !important;
            pointer-events: auto !important;
        }
        
        .modal-content {
            z-index: 10080 !important;
            position: relative !important;
            pointer-events: auto !important;
        }
        
        .modal-header {
            z-index: 10090 !important;
            position: relative !important;
            pointer-events: auto !important;
        }
        
        .modal-body {
            z-index: 10090 !important;
            position: relative !important;
            pointer-events: auto !important;
        }
        
        /* Ensure all interactive elements in modal are clickable */
        .modal button,
        .modal input,
        .modal select,
        .modal textarea,
        .modal a,
        .modal label,
        .modal .btn,
        .modal .form-control,
        .modal .form-select,
        .modal .btn-close {
            pointer-events: auto !important;
            position: relative !important;
            z-index: 10100 !important;
        }
        
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 4rem);
        }
        
        .modal-header {
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
        }
        
        .modal-body {
            max-height: calc(90vh - 200px);
            overflow-y: auto;
        }
        
        /* Custom scrollbar for modal */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }
        
        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .modal-body::-webkit-scrollbar-thumb {
            background: #1e40af;
            border-radius: 10px;
        }
        
        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #1e3a8a;
        }
        
        /* Form styling in modal */
        .modal-body .form-control,
        .modal-body .form-select {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
        }
        
        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.15);
        }
        
        .modal-body .img-thumbnail {
            border-radius: 8px;
            border: 2px solid #e5e7eb;
        }
        
        /* Smooth modal animation */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translate(0, -50px);
        }
        
        .modal.show .modal-dialog {
            transform: translate(0, 0);
        }
    </style>
    <script>
        // Force modal z-index on show
        document.addEventListener('DOMContentLoaded', function() {
            // Override modal z-index when shown
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('show.bs.modal', function() {
                    // Force z-index
                    this.style.zIndex = '10060';
                    const backdrop = document.querySelector('.modal-backdrop');
                    if(backdrop) {
                        backdrop.style.zIndex = '10050';
                    }
                });
                
                modal.addEventListener('shown.bs.modal', function() {
                    // Ensure z-index after animation
                    this.style.zIndex = '10060';
                    const backdrop = document.querySelector('.modal-backdrop');
                    if(backdrop) {
                        backdrop.style.zIndex = '10050';
                    }
                    // Force all children to be clickable
                    const allElements = this.querySelectorAll('*');
                    allElements.forEach(el => {
                        el.style.pointerEvents = 'auto';
                        if(!el.style.zIndex || parseInt(el.style.zIndex) < 10070) {
                            el.style.position = 'relative';
                        }
                    });
                });
            });
            
            // Handle edit form submission using event delegation
            document.body.addEventListener('submit', function(e) {
                const form = e.target;
                // Check if this is a product edit form
                if(form && form.id && form.id.startsWith('editProductForm_')) {
                    e.preventDefault();
                    
                    const formData = new FormData(form);
                    const productId = form.id.split('_')[1];
                    const modal = document.getElementById('editModal_' + productId);
                    
                    // Try to find the submit button - check event target first, then form
                    let submitBtn = null;
                    
                    // First, try to get the button from the submit event (if clicked directly)
                    if(e.submitter && e.submitter.tagName === 'BUTTON') {
                        submitBtn = e.submitter;
                    }
                    
                    // If not found, try querying the form
                    if(!submitBtn) {
                        submitBtn = form.querySelector('button[type="submit"]');
                    }
                    if(!submitBtn) {
                        submitBtn = form.querySelector('button[name="update_product"]');
                    }
                    if(!submitBtn) {
                        submitBtn = form.querySelector('button.btn-primary');
                    }
                    // Try to find any button in the form that's not the cancel button
                    if(!submitBtn) {
                        const allButtons = form.querySelectorAll('button');
                        for(let btn of allButtons) {
                            if(btn.type !== 'button' && !btn.hasAttribute('data-bs-dismiss')) {
                                submitBtn = btn;
                                break;
                            }
                        }
                    }
                    
                    // Store original button text if button is found
                    let originalBtnText = '';
                    if(submitBtn) {
                        originalBtnText = submitBtn.innerHTML;
                        // Disable submit button and show loading
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
                    } else {
                        console.warn('Submit button not found in form:', form.id, '- continuing without button update');
                    }
                    
                    // Submit via AJAX
                    fetch('update_product_ajax.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if(data.success) {
                            // Close modal first
                            if(modal) {
                                const bsModal = bootstrap.Modal.getInstance(modal);
                                if(bsModal) bsModal.hide();
                            }
                            
                            // Show success message
                            const message = data.message || 'Product updated successfully';
                            if(typeof window.showToast === 'function') {
                                window.showToast(message, 'success');
                            } else if(typeof showToast === 'function') {
                                showToast(message, 'success');
                            } else {
                                alert(message);
                            }
                            
                            // Reload page after a short delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            // Show error message
                            const errorMsg = data.message || 'Failed to update product';
                            if(typeof window.showToast === 'function') {
                                window.showToast(errorMsg, 'error');
                            } else if(typeof showToast === 'function') {
                                showToast(errorMsg, 'error');
                            } else {
                                alert(errorMsg);
                            }
                            
                            // Re-enable submit button
                            if(submitBtn && originalBtnText) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const errorMsg = 'An error occurred. Please try again.';
                        if(typeof window.showToast === 'function') {
                            window.showToast(errorMsg, 'error');
                        } else if(typeof showToast === 'function') {
                            showToast(errorMsg, 'error');
                        } else {
                            alert(errorMsg);
                        }
                        
                        // Re-enable submit button
                        if(submitBtn && originalBtnText) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }
                    });
                }
            });
        });
        
        // Additional fix: Ensure modal z-index is always correct
        document.addEventListener('DOMContentLoaded', function() {
            // Watch for modal backdrop creation and hide it
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if(node.nodeType === 1) {
                            // Hide modal backdrop completely
                            if(node.classList && node.classList.contains('modal-backdrop')) {
                                node.style.display = 'none';
                                node.style.opacity = '0';
                                node.style.visibility = 'hidden';
                                node.style.backgroundColor = 'transparent';
                            }
                            // Check if it's a modal
                            if(node.classList && node.classList.contains('modal')) {
                                node.style.zIndex = '10060';
                                node.style.position = 'fixed';
                            }
                        }
                    });
                });
            });
            
            // Start observing
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
            
            // Hide all existing backdrops and fix modals
            setTimeout(function() {
                document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                    backdrop.style.display = 'none';
                    backdrop.style.opacity = '0';
                    backdrop.style.visibility = 'hidden';
                    backdrop.style.backgroundColor = 'transparent';
                });
                document.querySelectorAll('.modal').forEach(function(modal) {
                    modal.style.zIndex = '10060';
                    modal.style.position = 'fixed';
                });
            }, 100);
            
            // Also hide backdrop when modal events fire
            document.addEventListener('show.bs.modal', function() {
                document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                    backdrop.style.display = 'none';
                    backdrop.style.opacity = '0';
                    backdrop.style.visibility = 'hidden';
                });
            });
            
            document.addEventListener('shown.bs.modal', function() {
                document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                    backdrop.style.display = 'none';
                    backdrop.style.opacity = '0';
                    backdrop.style.visibility = 'hidden';
                });
            });
        });
    </script>
    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>