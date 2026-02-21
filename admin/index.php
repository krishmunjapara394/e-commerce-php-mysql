<?php
    include('../includes/connect.php');
    include('../functions/common_functions.php');
    session_start();
    if(isset($_SESSION['admin_username'])){
        $admin_name = $_SESSION['admin_username'];
        $get_admin_data = "SELECT * FROM `admin_table` WHERE admin_name = '$admin_name'";
        $get_admin_result = mysqli_query($con,$get_admin_data);
        $row_fetch_admin_data = mysqli_fetch_array($get_admin_result);
        $admin_name = $row_fetch_admin_data['admin_name'];
        $admin_image = $row_fetch_admin_data['admin_image'];
    }else{
        echo "<script>window.open('./admin_login.php','_self');</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Prevent Bootstrap from creating modal backdrop
        (function() {
            const originalModal = bootstrap.Modal;
            bootstrap.Modal = function(element, config) {
                const defaultConfig = config || {};
                defaultConfig.backdrop = false; // Disable backdrop
                return new originalModal(element, defaultConfig);
            };
            // Copy static methods
            Object.setPrototypeOf(bootstrap.Modal, originalModal);
            bootstrap.Modal.prototype = originalModal.prototype;
        })();
        
        // Toast Helper Function
        function showToast(message, type = 'success') {
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
        }
    </script>
</head>

<body>
    <!-- upper-nav -->
    <div class="upper-nav p-2 px-3 text-center text-break" style="background-color: #1e40af;">
        <span style="color: #ffffff;">Admin Dashboard And Free Express Delivery</span>
    </div>
    <!-- upper-nav -->
    <!-- Start NavBar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm" style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb;">
        <div class="container">
            <a class="navbar-brand fw-bold text-2" href="index.php" style="font-size: 1.5rem;">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContentad" aria-controls="navbarSupportedContentad" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContentad">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link active text-2 fw-medium" aria-current="page" href="#">
                            <i class="fas fa-user-circle me-1"></i>Welcome, <?php echo $admin_name;?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="./admin_logout.php" class="btn btn-primary px-4">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End NavBar -->
    <!-- Start Control Buttons -->
    <div class="control bg-light py-5">
        <div class="container">
            <div class="admin-header-section mb-5">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="admin-title-bar"></div>
                    <span class="admin-title-tag">Admin Panel</span>
                </div>
                <h2 class="admin-main-title">Manage Details Of Ecommerce</h2>
            </div>
            <div class="row align-items-start">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="admin-profile-card card border-0 shadow-lg h-100">
                        <div class="card-body position-relative">
                            <!-- Decorative background gradient -->
                            <div class="admin-card-bg-gradient"></div>
                            
                            <!-- Card Content Container -->
                            <div class="admin-card-content">
                                <!-- Profile Image Section -->
                                <div class="admin-image-wrapper-container">
                                    <a href="./index.php?account" class="admin-image-link">
                                        <?php 
                                        $image_path = "./admin_images/" . htmlspecialchars($admin_image);
                                        $image_exists = file_exists($image_path);
                                        ?>
                                        <div class="admin-image-wrapper">
                                            <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                                 class="admin-profile-img" 
                                                 alt="<?php echo htmlspecialchars($admin_name); ?> Photo" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="admin-profile-placeholder">
                                                <?php echo strtoupper(substr($admin_name, 0, 1)); ?>
                                            </div>
                                            <!-- Status indicator -->
                                            <span class="admin-status-indicator">
                                                <i class="fas fa-circle"></i>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                
                                <!-- Admin Info Section -->
                                <div class="admin-info-section">
                                    <h5 class="admin-name"><?php echo htmlspecialchars($admin_name);?></h5>
                                    <div class="admin-badge">
                                        <span class="badge-admin">
                                            <i class="fas fa-shield-alt"></i>
                                            <span>Administrator</span>
                                        </span>
                                    </div>
                                    <a href="./index.php?account" class="admin-view-profile-btn">
                                        <i class="fas fa-user-edit"></i>
                                        <span>View Profile</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <a href="./insert_product.php" class="text-decoration-none">
                                <div class="admin-action-card">
                                    <div class="admin-card-icon-wrapper">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <h6 class="admin-card-title">Insert Products</h6>
                                    <p class="admin-card-desc">Add new products to your store</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <a href="index.php?insert_category" class="text-decoration-none">
                                <div class="admin-action-card">
                                    <div class="admin-card-icon-wrapper">
                                        <i class="fas fa-folder-plus"></i>
                                    </div>
                                    <h6 class="admin-card-title">Insert Categories</h6>
                                    <p class="admin-card-desc">Create new categories</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <a href="index.php?insert_brand" class="text-decoration-none">
                                <div class="admin-action-card">
                                    <div class="admin-card-icon-wrapper">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <h6 class="admin-card-title">Insert Brands</h6>
                                    <p class="admin-card-desc">Add new brands</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Control Buttons -->
    
    <!-- Start Navigation Tabs -->
    <?php
    // Get current page parameter - default to view_products if no parameter is set
    $current_page = '';
    if(isset($_GET['view_products'])) $current_page = 'view_products';
    elseif(isset($_GET['insert_category'])) $current_page = 'insert_category';
    elseif(isset($_GET['view_categories'])) $current_page = 'view_categories';
    elseif(isset($_GET['insert_brand'])) $current_page = 'insert_brand';
    elseif(isset($_GET['view_brands'])) $current_page = 'view_brands';
    elseif(isset($_GET['list_orders'])) $current_page = 'list_orders';
    elseif(isset($_GET['list_payments'])) $current_page = 'list_payments';
    elseif(isset($_GET['list_users'])) $current_page = 'list_users';
    elseif(isset($_GET['list_contacts'])) $current_page = 'list_contacts';
    elseif(isset($_GET['account'])) $current_page = 'account';
    elseif(isset($_GET['edit_product'])) $current_page = 'view_products';
    elseif(isset($_GET['edit_category'])) $current_page = 'view_categories';
    elseif(isset($_GET['edit_brand'])) $current_page = 'view_brands';
    else {
        // Default to view_products if no parameter is set
        $current_page = 'view_products';
    }
    ?>
    
    <?php if($current_page != ''): ?>
    <div class="admin-tabs-container">
    <div class="container">
            <div class="admin-tabs-wrapper">
                <nav class="admin-tabs-nav">
                    <a class="admin-tab-item <?php echo ($current_page == 'view_products' || isset($_GET['edit_product'])) ? 'active' : ''; ?>" href="index.php?view_products">
                        <span class="tab-icon"><i class="fas fa-box"></i></span>
                        <span class="tab-text">Products</span>
                    </a>
                    <a class="admin-tab-item <?php echo ($current_page == 'insert_category' || $current_page == 'view_categories' || isset($_GET['edit_category'])) ? 'active' : ''; ?>" href="index.php?view_categories">
                        <span class="tab-icon"><i class="fas fa-folder"></i></span>
                        <span class="tab-text">Categories</span>
                    </a>
                    <a class="admin-tab-item <?php echo ($current_page == 'insert_brand' || $current_page == 'view_brands' || isset($_GET['edit_brand'])) ? 'active' : ''; ?>" href="index.php?view_brands">
                        <span class="tab-icon"><i class="fas fa-tags"></i></span>
                        <span class="tab-text">Brands</span>
                    </a>
                    <a class="admin-tab-item <?php echo $current_page == 'list_orders' ? 'active' : ''; ?>" href="index.php?list_orders">
                        <span class="tab-icon"><i class="fas fa-shopping-cart"></i></span>
                        <span class="tab-text">Orders</span>
                    </a>
                    <a class="admin-tab-item <?php echo $current_page == 'list_payments' ? 'active' : ''; ?>" href="index.php?list_payments">
                        <span class="tab-icon"><i class="fas fa-credit-card"></i></span>
                        <span class="tab-text">Payments</span>
                    </a>
                    <a class="admin-tab-item <?php echo $current_page == 'list_users' ? 'active' : ''; ?>" href="index.php?list_users">
                        <span class="tab-icon"><i class="fas fa-users"></i></span>
                        <span class="tab-text">Users</span>
                    </a>
                    <a class="admin-tab-item <?php echo $current_page == 'list_contacts' ? 'active' : ''; ?>" href="index.php?list_contacts">
                        <span class="tab-icon"><i class="fas fa-envelope"></i></span>
                        <span class="tab-text">Messages</span>
                    </a>
                    <a class="admin-tab-item <?php echo $current_page == 'account' ? 'active' : ''; ?>" href="index.php?account">
                        <span class="tab-icon"><i class="fas fa-user-cog"></i></span>
                        <span class="tab-text">Account</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- End Navigation Tabs -->
    
    <!-- Start Changed Page  -->
    <div class="change-page <?php echo $current_page != '' ? 'has-tabs' : ''; ?>">
        <div class="container">
            <?php
            if(isset($_GET['insert_category'])){
                include('./insert_categories.php');
            }
            if(isset($_GET['insert_brand'])){
                include('./insert_brands.php');
            }
            if(isset($_GET['view_products']) || $current_page == 'view_products'){
                include('./view_products.php');
            }
            if(isset($_GET['edit_product'])){
                include('./edit_product.php');
            }
            if(isset($_GET['delete_product'])){
                include('./delete_product.php');
            }
            if(isset($_GET['view_categories'])){
                include('./view_categories.php');
            }
            if(isset($_GET['edit_category'])){
                include('./edit_category.php');
            }
            if(isset($_GET['delete_category'])){
                include('./delete_category.php');
            }
            if(isset($_GET['view_brands'])){
                include('./view_brands.php');
            }
            if(isset($_GET['edit_brand'])){
                include('./edit_brand.php');
            }
            if(isset($_GET['delete_brand'])){
                include('./delete_brand.php');
            }
            if(isset($_GET['list_orders'])){
                include('./list_orders.php');
            }
            if(isset($_GET['delete_order'])){
                include('./delete_order.php');
            }
            if(isset($_GET['list_payments'])){
                include('./list_payments.php');
            }
            if(isset($_GET['delete_payment'])){
                include('./delete_payment.php');
            }
            if(isset($_GET['list_users'])){
                include('./list_users.php');
            }
            if(isset($_GET['list_contacts'])){
                include('./list_contacts.php');
            }
            if(isset($_GET['mark_read_contact'])){
                include('./mark_read_contact.php');
            }
            if(isset($_GET['delete_contact'])){
                include('./delete_contact.php');
            }
            if(isset($_GET['account']) || $current_page == 'account'){
                include('./admin_account.php');
            }

            ?>
        </div>
    </div>
    <!-- End Changed Page  -->







    <!-- Start Footer -->
    <!-- <div class="upper-nav primary-bg p-2 px-3 text-center text-break">
        <span>All CopyRight &copy;2023</span>
    </div> -->
    <!-- End Footer -->

    <script src="../assets/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Admin Action Cards - Modern Design */
        .admin-action-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .admin-action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1e40af, #3b82f6);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        .admin-action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #1e40af;
        }
        .admin-action-card:hover::before {
            transform: scaleX(1);
        }
        .admin-card-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
        }
        .admin-action-card:hover .admin-card-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 20px rgba(30, 64, 175, 0.3);
        }
        .admin-card-icon-wrapper i {
            font-size: 32px;
            color: #ffffff;
        }
        .admin-card-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
            transition: color 0.3s ease;
        }
        .admin-action-card:hover .admin-card-title {
            color: #1e40af;
        }
        .admin-card-desc {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
            line-height: 1.5;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: #1e40af;
            border-color: #1e40af;
        }
        .btn-primary:hover {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
        }
        .navbar-brand.text-2 {
            color: #1e40af !important;
        }
        
        /* Admin Tabs Navigation - Modern Design */
        .admin-tabs-container {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 999;
            margin-top: 0;
        }
        .admin-tabs-wrapper {
            padding: 0;
        }
        .admin-tabs-nav {
            display: flex;
            align-items: center;
            gap: 0;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 0;
        }
        .admin-tabs-nav::-webkit-scrollbar {
            display: none;
        }
        .admin-tab-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 16px 24px;
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            white-space: nowrap;
            position: relative;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            background-color: transparent;
        }
        .admin-tab-item:hover {
            color: #1e40af;
            background-color: #f1f5f9;
        }
        .admin-tab-item.active {
            color: #1e40af;
            border-bottom-color: #1e40af;
            background-color: transparent;
        }
        .admin-tab-item.active .tab-icon {
            color: #1e40af;
        }
        .admin-tab-item .tab-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            color: #6b7280;
            transition: color 0.3s ease;
        }
        .admin-tab-item:hover .tab-icon {
            color: #1e40af;
        }
        .admin-tab-item .tab-text {
            font-weight: 500;
        }
        
        /* Change Page Section */
        .change-page {
            padding: 30px 0;
            min-height: 60vh;
            background-color: #ffffff;
        }
        .change-page.has-tabs {
            padding-top: 30px;
        }
        
        /* Remove divider when tabs are present */
        .change-page.has-tabs ~ .divider {
            display: none;
        }
        
        /* Improve card shadows - matching home page style */
        .card {
            transition: box-shadow 0.3s ease;
            border: 1px solid #e5e7eb;
        }
        .card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }
        
        /* Enhanced Admin Profile Card */
        .admin-profile-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px !important;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            height: 100%;
        }
        .admin-profile-card .card-body {
            padding: 2.5rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100%;
        }
        .admin-profile-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(30, 64, 175, 0.15) !important;
        }
        
        /* Decorative background gradient */
        .admin-card-bg-gradient {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            opacity: 0.05;
            border-radius: 20px 20px 0 0;
            z-index: 0;
        }
        
        /* Card Content Container */
        .admin-card-content {
            position: relative;
            z-index: 2;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        /* Profile Image Wrapper Container */
        .admin-image-wrapper-container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .admin-image-link {
            display: inline-block;
            text-decoration: none;
            outline: none;
        }
        .admin-image-link:focus {
            outline: none;
        }
        
        /* Profile Image Wrapper */
        .admin-image-wrapper {
            position: relative;
            display: inline-block;
            width: 130px;
            height: 130px;
            margin: 0 auto;
        }
        .admin-profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #ffffff;
            box-shadow: 0 8px 24px rgba(30, 64, 175, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: block;
        }
        .admin-image-link:hover .admin-profile-img {
            transform: scale(1.08);
            box-shadow: 0 12px 32px rgba(30, 64, 175, 0.3);
            border-color: #1e40af;
        }
        
        /* Profile Placeholder */
        .admin-profile-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 52px;
            font-weight: bold;
            border: 4px solid #ffffff;
            box-shadow: 0 8px 24px rgba(30, 64, 175, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute;
            top: 0;
            left: 0;
        }
        .admin-image-link:hover .admin-profile-placeholder {
            transform: scale(1.08);
            box-shadow: 0 12px 32px rgba(30, 64, 175, 0.3);
            border-color: #1e40af;
        }
        
        /* Status Indicator */
        .admin-status-indicator {
            position: absolute;
            bottom: 6px;
            right: 6px;
            width: 20px;
            height: 20px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            z-index: 3;
        }
        .admin-status-indicator i {
            font-size: 12px;
            color: #10b981;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        
        /* Admin Info Section */
        .admin-info-section {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        .admin-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1f2937;
            letter-spacing: -0.02em;
            margin: 0;
            line-height: 1.3;
            word-break: break-word;
        }
        
        /* Admin Badge */
        .admin-badge {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0.5rem 0;
        }
        .badge-admin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 8px 18px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #ffffff;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.25);
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .badge-admin i {
            font-size: 0.9rem;
        }
        .admin-profile-card:hover .badge-admin {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(30, 64, 175, 0.35);
        }
        
        /* View Profile Button */
        .admin-view-profile-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 10px 24px;
            background: #ffffff;
            color: #1e40af;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-top: 0.5rem;
            white-space: nowrap;
        }
        .admin-view-profile-btn i {
            font-size: 0.9rem;
        }
        .admin-view-profile-btn:hover {
            background: #1e40af;
            color: #ffffff;
            border-color: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
            text-decoration: none;
        }
        .admin-view-profile-btn:focus {
            outline: 2px solid #1e40af;
            outline-offset: 2px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .admin-profile-card .card-body {
                padding: 2rem 1.25rem;
            }
            .admin-image-wrapper {
                width: 110px;
                height: 110px;
            }
            .admin-profile-placeholder {
                font-size: 44px;
            }
            .admin-name {
                font-size: 1.2rem;
            }
        }
        
        /* Admin Header Section - Matching Home Page Design */
        .admin-header-section {
            margin-bottom: 2rem;
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
        
        /* Consistent background colors - matching home page */
        .control.bg-light {
            background-color: #f1f5f9 !important;
        }
        .navbar {
            background-color: #ffffff !important;
            position: sticky;
            top: 0;
            z-index: 1001;
        }
        
        /* Ensure proper scrolling behavior - control section scrolls normally */
        .control {
            position: relative;
            z-index: 1;
            /* Ensure it scrolls normally */
            transform: none;
        }
        
        /* Fix for content flow */
        body {
            overflow-x: hidden;
        }
        
        /* Ensure control section scrolls normally - no sticky positioning */
        .control {
            position: relative;
            z-index: 1;
            transform: none !important;
            will-change: auto;
        }
        
        /* Upper nav bar */
        .upper-nav {
            position: relative;
            z-index: 1;
        }
        
        /* Navbar - sticky at top */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1001;
        }
        
        /* Tabs - sticky below navbar, only when content is scrolled */
        .admin-tabs-container {
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        /* Ensure content flows properly */
        .change-page {
            position: relative;
            z-index: 1;
        }
        
        /* Global Modal Z-Index Fix - Ensure all modals appear above everything */
        /* Remove dark overlay - make backdrop transparent/invisible */
        .modal-backdrop {
            z-index: 10050 !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background-color: transparent !important;
            backdrop-filter: none !important;
            display: none !important;
        }
        
        .modal-backdrop.show {
            z-index: 10050 !important;
            display: none !important;
            background-color: transparent !important;
        }
        
        /* Hide backdrop completely */
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
        
        .modal-dialog {
            z-index: 10070 !important;
            position: relative !important;
            margin: 1.75rem auto !important;
            pointer-events: auto !important;
            max-width: 90% !important;
        }
        
        .modal-content {
            z-index: 10080 !important;
            position: relative !important;
            pointer-events: auto !important;
            border: none !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
        }
        
        .modal-header {
            position: relative !important;
            z-index: 10090 !important;
            pointer-events: auto !important;
        }
        
        .modal-body {
            position: relative !important;
            z-index: 10090 !important;
            pointer-events: auto !important;
        }
        
        .modal-footer {
            position: relative !important;
            z-index: 10090 !important;
            pointer-events: auto !important;
        }
        
        /* Ensure modal buttons and inputs are clickable */
        .modal button,
        .modal input,
        .modal select,
        .modal textarea,
        .modal a,
        .modal label,
        .modal .btn,
        .modal .form-control,
        .modal .form-select {
            pointer-events: auto !important;
            position: relative !important;
            z-index: 10100 !important;
        }
        
        /* Override any conflicting styles */
        body.modal-open {
            overflow: hidden !important;
        }
        
        /* Ensure modal is always on top */
        .modal[style*="z-index"] {
            z-index: 10060 !important;
        }
        
        /* Responsive tabs */
        @media (max-width: 768px) {
            .admin-tabs-nav {
                padding: 0 15px;
            }
            .admin-tab-item {
                padding: 12px 16px;
                font-size: 13px;
            }
            .admin-tab-item .tab-icon {
                width: 18px;
                height: 18px;
            }
        }
        
        /* Smooth scroll for tabs on mobile */
        @media (max-width: 768px) {
            .admin-tabs-nav {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
    <script>
        // Handle admin profile image errors
        document.addEventListener('DOMContentLoaded', function() {
            const adminProfileImg = document.querySelector('.admin-profile-img');
            const adminPlaceholder = document.querySelector('.admin-profile-placeholder');
            
            if (adminProfileImg && adminPlaceholder) {
                adminProfileImg.addEventListener('error', function() {
                    this.style.display = 'none';
                    if (adminPlaceholder) {
                        adminPlaceholder.style.display = 'flex';
                    }
                });
                
                // Check if image loaded successfully
                if (adminProfileImg.complete && adminProfileImg.naturalHeight === 0) {
                    adminProfileImg.style.display = 'none';
                    if (adminPlaceholder) {
                        adminPlaceholder.style.display = 'flex';
                    }
                }
                
                // Ensure image loads properly
                if (adminProfileImg.src && !adminProfileImg.complete) {
                    adminProfileImg.addEventListener('load', function() {
                        if (adminPlaceholder) {
                            adminPlaceholder.style.display = 'none';
                        }
                    });
                }
            }
        });
    </script>
</body>

</html>