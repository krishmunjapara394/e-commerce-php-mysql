<?php
// include connect file from DB
// include('./includes/connect.php');

// Helper function to get image URL (handles both local files and online URLs)
function getImageUrl($image_path) {
    // Check if it's a full URL (starts with http:// or https://)
    if (strpos($image_path, 'http://') === 0 || strpos($image_path, 'https://') === 0) {
        return $image_path;
    }
    // Otherwise, treat it as a local file
    return './admin/product_images/' . $image_path;
}

// Helper function to display modern product card
function displayProductCard($product_id, $product_title, $product_image_one, $product_price) {
    $product_title_escaped = htmlspecialchars($product_title);
    $discount_price = number_format($product_price * 0.85, 2);
    $image_url = getImageUrl($product_image_one);
    echo "
    <div class='product-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group' data-product-id='$product_id'>
        <div class='relative overflow-hidden bg-gray-100 h-64'>
            <img src='$image_url' 
                 alt='$product_title_escaped' 
                 class='product-image w-full h-full object-contain p-4 transition-transform duration-300'>
            <div class='product-overlay absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300 flex items-center justify-center gap-3 px-4'>
                <a href='products.php?add_to_cart=$product_id' 
                   class='bg-white text-[#1e40af] px-5 py-2.5 rounded-lg font-semibold hover:bg-[#1e40af] hover:text-white transition-all transform hover:scale-105 whitespace-nowrap flex items-center gap-2 shadow-lg'>
                    <i class='fas fa-shopping-cart'></i>
                    <span class='text-sm'>Add to Cart</span>
                </a>
                <a href='product_details.php?product_id=$product_id' 
                   class='bg-[#1e40af] text-white px-5 py-2.5 rounded-lg font-semibold transform hover:scale-105 whitespace-nowrap flex items-center gap-2 shadow-lg hover:bg-[#1e3a8a] transition-all'>
                    <span class='text-sm'>View Details</span>
                </a>
            </div>
            <button onclick='addToWishlistQuick($product_id)' 
                    class='absolute top-4 right-4 bg-white text-red-500 w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-lg z-10'>
                <i class='fas fa-heart'></i>
            </button>
            <span class='absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold'>-15%</span>
        </div>
        <div class='p-5'>
            <h3 class='font-semibold text-gray-800 mb-2 line-clamp-2'>$product_title_escaped</h3>
            <div class='flex items-center gap-1 mb-3'>
                <i class='fas fa-star text-yellow-400 text-sm'></i>
                <i class='fas fa-star text-yellow-400 text-sm'></i>
                <i class='fas fa-star text-yellow-400 text-sm'></i>
                <i class='fas fa-star text-yellow-400 text-sm'></i>
                <i class='fas fa-star text-yellow-400 text-sm'></i>
                <span class='text-gray-500 text-sm ml-2'>(35)</span>
            </div>
            <div class='flex items-center gap-3'>
                <span class='text-[#1e40af] font-bold text-xl'>\$$discount_price</span>
                <span class='text-gray-400 line-through'>\$$product_price</span>
            </div>
        </div>
    </div>
    ";
}

// getting products
function getProduct($numToDisplay = '')
{
    global $con;
    // condition to check isset or not 
    if (!isset($_GET['category'])) {
        if (!isset($_GET['brand'])) {
            empty($numToDisplay) ? $select_product_query = "SELECT * FROM `products` ORDER BY rand()" : $select_product_query = "SELECT * FROM `products` ORDER BY rand() LIMIT 0,$numToDisplay";
            // $select_product_query = "SELECT * FROM `products` ORDER BY rand() LIMIT 0,10";
            $select_product_result = mysqli_query($con, $select_product_query);
            while ($row = mysqli_fetch_assoc($select_product_result)) {
                $product_id = $row['product_id'];
                $product_title = htmlspecialchars($row['product_title']);
                $product_image_one = $row['product_image_one'];
                $product_price = $row['product_price'];
                $discount_price = number_format($product_price * 0.85, 2);
                $category_id = $row['category_id'];
                $brand_id = $row['brand_id'];
                $image_url = getImageUrl($product_image_one);
                echo "
        <div class='product-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group'>
            <div class='relative overflow-hidden bg-gray-100 h-64'>
                <a href='product_details.php?product_id=$product_id' class='block w-full h-full z-0'>
                    <img src='$image_url' 
                         alt='$product_title' 
                         class='product-image w-full h-full object-contain p-4 transition-transform duration-300 cursor-pointer'>
                </a>
                <div class='product-overlay absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity duration-300 flex items-center justify-center gap-2 z-20'>
                    <a href='#' onclick='addToCartAjax($product_id); return false;' 
                       class='bg-white text-[#1e40af] px-6 py-3 rounded-lg font-semibold hover:bg-[#1e40af] hover:text-white transition-all transform hover:scale-105'>
                        <i class='fas fa-shopping-cart mr-2'></i>Add to Cart
                    </a>
                    <a href='product_details.php?product_id=$product_id' 
                       class='bg-[#1e40af] text-white px-6 py-3 rounded-lg font-semibold transform hover:scale-105'>
                        View Details
                    </a>
                </div>
                <button onclick='addToWishlistQuick($product_id)' 
                        class='absolute top-4 right-4 bg-white text-red-500 w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-lg z-10'>
                    <i class='fas fa-heart'></i>
                </button>
                <span class='absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold'>-15%</span>
            </div>
            <div class='p-5'>
                <a href='product_details.php?product_id=$product_id' class='block hover:text-[#1e40af] transition-colors'>
                    <h3 class='font-semibold text-gray-800 mb-2 line-clamp-2 cursor-pointer'>$product_title</h3>
                </a>
                <div class='flex items-center gap-1 mb-3'>
                    <i class='fas fa-star text-yellow-400 text-sm'></i>
                    <i class='fas fa-star text-yellow-400 text-sm'></i>
                    <i class='fas fa-star text-yellow-400 text-sm'></i>
                    <i class='fas fa-star text-yellow-400 text-sm'></i>
                    <i class='fas fa-star text-yellow-400 text-sm'></i>
                    <span class='text-gray-500 text-sm ml-2'>(35)</span>
                </div>
                <div class='flex items-center gap-3'>
                    <span class='text-[#1e40af] font-bold text-xl'>\$$discount_price</span>
                    <span class='text-gray-400 line-through'>\$$product_price</span>
                </div>
            </div>
        </div>
        ";
            }
        }
    }
}
// display unique product with category
function filterCategoryProduct()
{
    global $con;
    // condition to check isset or not 
    if (isset($_GET['category'])) {
        $category_id = $_GET['category'];
        $select_product_query = "SELECT * FROM `products` WHERE category_id = $category_id";
        $select_product_result = mysqli_query($con, $select_product_query);
        $num_of_rows = mysqli_num_rows($select_product_result);
        if ($num_of_rows == 0) {
            echo "
                <div class='col-span-full text-center py-12'>
                    <h2 class='text-2xl font-bold text-gray-900 mb-4'>No Stock for this category</h2>
                    <a href='products.php' class='text-[#1e40af] hover:underline'>View All Products</a>
                </div>
                ";
        }
        while ($row = mysqli_fetch_assoc($select_product_result)) {
            $product_id = $row['product_id'];
            $product_title = $row['product_title'];
            $product_image_one = $row['product_image_one'];
            $product_price = $row['product_price'];
            displayProductCard($product_id, $product_title, $product_image_one, $product_price);
        }
    }
}
// display unique product with brand 
function filterBrandProduct()
{
    global $con;
    // condition to check isset or not 
    if (isset($_GET['brand'])) {
        $brand_id = $_GET['brand'];
        $select_product_query = "SELECT * FROM `products` WHERE brand_id = $brand_id";
        $select_product_result = mysqli_query($con, $select_product_query);
        $num_of_rows = mysqli_num_rows($select_product_result);
        if ($num_of_rows == 0) {
            echo "
                <div class='col-span-full text-center py-12'>
                    <h2 class='text-2xl font-bold text-gray-900 mb-4'>No Stock for this brand</h2>
                    <a href='products.php' class='text-[#1e40af] hover:underline'>View All Products</a>
                </div>
                ";
        }
        while ($row = mysqli_fetch_assoc($select_product_result)) {
            $product_id = $row['product_id'];
            $product_title = $row['product_title'];
            $product_image_one = $row['product_image_one'];
            $product_price = $row['product_price'];
            displayProductCard($product_id, $product_title, $product_image_one, $product_price);
        }
    }
}


// display brands in sidenav 
function getBrands()
{
    global $con;
    $select_brands_query = "SELECT * FROM `brands`";
    $select_brands_result = mysqli_query($con, $select_brands_query);
    while ($brands_row_data = mysqli_fetch_assoc($select_brands_result)) {
        $brand_title = $brands_row_data['brand_title'];
        $brand_id = $brands_row_data['brand_id'];
        echo "
        <li class='nav-item'>
            <a href='products.php?brand=$brand_id' class='nav-link'>
                $brand_title
            </a>
        </li>
    ";
    }
}

// display categories in sidenav 
function getCategories()
{
    global $con;
    $select_category_query = "SELECT * FROM `categories`";
    $select_category_result = mysqli_query($con, $select_category_query);
    while ($categories_row_data = mysqli_fetch_assoc($select_category_result)) {
        $category_title = $categories_row_data['category_title'];
        $category_id = $categories_row_data['category_id'];
        echo "
        <li class='nav-item'>
        <a href='products.php?category=$category_id' class='nav-link'>
            $category_title
        </a>
    </li>
        ";
    }
}

// search product function 
function search_product()
{
    global $con;
    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $search_data_value = mysqli_real_escape_string($con, trim($_GET['search']));
        $search_product_query = "SELECT * FROM `products` WHERE product_title LIKE '%$search_data_value%' OR product_keywords LIKE '%$search_data_value%'";
        $search_product_result = mysqli_query($con, $search_product_query);
        $num_of_rows = mysqli_num_rows($search_product_result);
        if ($num_of_rows == 0) {
            echo "
                <div class='col-span-full text-center py-12'>
                    <div class='bg-white rounded-2xl shadow-lg border border-gray-200 p-8 max-w-2xl mx-auto'>
                        <i class='fas fa-search text-6xl text-gray-400 mb-4'></i>
                        <h4 class='text-2xl font-bold text-gray-900 mb-2'>No products found!</h4>
                        <p class='text-gray-600 mb-6'>No results match your search for \"<strong>" . htmlspecialchars($search_data_value) . "</strong>\". Try different keywords.</p>
                        <a href='products.php' class='inline-block bg-[#1e40af] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#1e3a8a] transition-all'>View All Products</a>
                    </div>
                </div>
                ";
        } else {
            echo "<div class='col-span-full mb-6'><h4 class='text-xl font-bold text-gray-900'>Search Results for: <strong>" . htmlspecialchars($search_data_value) . "</strong> ($num_of_rows products found)</h4></div>";
        }
        while ($row = mysqli_fetch_assoc($search_product_result)) {
            $product_id = $row['product_id'];
            $product_title = $row['product_title'];
            $product_image_one = $row['product_image_one'];
            $product_price = $row['product_price'];
            displayProductCard($product_id, $product_title, $product_image_one, $product_price);
        }
    }
}

// view details function 
function viewDetails()
{
    global $con;
    // condition to check isset or not 
    if (isset($_GET['product_id'])) {
        if (!isset($_GET['category'])) {
            if (!isset($_GET['brand'])) {
                $product_id = $_GET['product_id'];
                $select_product_query = "SELECT * FROM `products` WHERE product_id=$product_id";
                $select_product_result = mysqli_query($con, $select_product_query);
                while ($row = mysqli_fetch_assoc($select_product_result)) {
                    $product_id = $row['product_id'];
                    $product_title = $row['product_title'];
                    $product_desc = $row['product_description'];
                    $product_image_one = $row['product_image_one'];
                    $product_image_two = $row['product_image_two'];
                    $product_image_three = $row['product_image_three'];
                    $product_price = $row['product_price'];
                    $category_id = $row['category_id'];
                    $brand_id = $row['brand_id'];
                    $image_url_one = getImageUrl($product_image_one);
                    $image_url_two = getImageUrl($product_image_two);
                    $image_url_three = getImageUrl($product_image_three);
                    echo "
                    <div class='row mx-0 justify-content-md-center gap-3 gap-md-0'>
                    <div class='col-md-2'>
                        <div class='prod-imgs'>
                            <img src='$image_url_one' alt='$product_title'>
                            <img src='$image_url_two' alt='$product_title'>
                            <img src='$image_url_three' alt='$product_title'>
                        </div>
                    </div>
                    <div class='col-md-5'>
                        <div class='main-img'>
                            <img src='$image_url_one' alt='$product_title'>
                        </div>
                    </div>
                    <div class='col-md-5'>
                        <div class='info d-flex flex-column gap-2'>
                            <h4 class='fw-bold'>$product_title</h4>
                            <div class='rates d-flex gap-2 flex-wrap'>
                                <span>
                                    <svg width='16' height='15' viewBox='0 0 16 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                        <path d='M14.673 7.17173C15.7437 6.36184 15.1709 4.65517 13.8284 4.65517H11.3992C10.7853 4.65517 10.243 4.25521 10.0617 3.66868L9.33754 1.32637C8.9309 0.0110567 7.0691 0.0110564 6.66246 1.32637L5.93832 3.66868C5.75699 4.25521 5.21469 4.65517 4.60078 4.65517H2.12961C0.791419 4.65517 0.215919 6.35274 1.27822 7.16654L3.39469 8.78792C3.85885 9.1435 4.05314 9.75008 3.88196 10.3092L3.11296 12.8207C2.71416 14.1232 4.22167 15.1704 5.30301 14.342L7.14861 12.9281C7.65097 12.5432 8.34903 12.5432 8.85139 12.9281L10.6807 14.3295C11.7636 15.159 13.2725 14.1079 12.8696 12.8046L12.09 10.2827C11.9159 9.71975 12.113 9.10809 12.5829 8.75263L14.673 7.17173Z' fill='#FFAD33' />
                                    </svg>
                                    <svg width='16' height='15' viewBox='0 0 16 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                        <path d='M14.673 7.17173C15.7437 6.36184 15.1709 4.65517 13.8284 4.65517H11.3992C10.7853 4.65517 10.243 4.25521 10.0617 3.66868L9.33754 1.32637C8.9309 0.0110567 7.0691 0.0110564 6.66246 1.32637L5.93832 3.66868C5.75699 4.25521 5.21469 4.65517 4.60078 4.65517H2.12961C0.791419 4.65517 0.215919 6.35274 1.27822 7.16654L3.39469 8.78792C3.85885 9.1435 4.05314 9.75008 3.88196 10.3092L3.11296 12.8207C2.71416 14.1232 4.22167 15.1704 5.30301 14.342L7.14861 12.9281C7.65097 12.5432 8.34903 12.5432 8.85139 12.9281L10.6807 14.3295C11.7636 15.159 13.2725 14.1079 12.8696 12.8046L12.09 10.2827C11.9159 9.71975 12.113 9.10809 12.5829 8.75263L14.673 7.17173Z' fill='#FFAD33' />
                                    </svg>
                                    <svg width='16' height='15' viewBox='0 0 16 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                        <path d='M14.673 7.17173C15.7437 6.36184 15.1709 4.65517 13.8284 4.65517H11.3992C10.7853 4.65517 10.243 4.25521 10.0617 3.66868L9.33754 1.32637C8.9309 0.0110567 7.0691 0.0110564 6.66246 1.32637L5.93832 3.66868C5.75699 4.25521 5.21469 4.65517 4.60078 4.65517H2.12961C0.791419 4.65517 0.215919 6.35274 1.27822 7.16654L3.39469 8.78792C3.85885 9.1435 4.05314 9.75008 3.88196 10.3092L3.11296 12.8207C2.71416 14.1232 4.22167 15.1704 5.30301 14.342L7.14861 12.9281C7.65097 12.5432 8.34903 12.5432 8.85139 12.9281L10.6807 14.3295C11.7636 15.159 13.2725 14.1079 12.8696 12.8046L12.09 10.2827C11.9159 9.71975 12.113 9.10809 12.5829 8.75263L14.673 7.17173Z' fill='#FFAD33' />
                                    </svg>
                                    <svg width='16' height='15' viewBox='0 0 16 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                        <path opacity='0.25' d='M14.673 7.17173C15.7437 6.36184 15.1709 4.65517 13.8284 4.65517H11.3992C10.7853 4.65517 10.243 4.25521 10.0617 3.66868L9.33754 1.32637C8.9309 0.0110567 7.0691 0.0110564 6.66246 1.32637L5.93832 3.66868C5.75699 4.25521 5.21469 4.65517 4.60078 4.65517H2.12961C0.791419 4.65517 0.215919 6.35274 1.27822 7.16654L3.39469 8.78792C3.85885 9.1435 4.05314 9.75008 3.88196 10.3092L3.11296 12.8207C2.71416 14.1232 4.22167 15.1704 5.30301 14.342L7.14861 12.9281C7.65097 12.5432 8.34903 12.5432 8.85139 12.9281L10.6807 14.3295C11.7636 15.159 13.2725 14.1079 12.8696 12.8046L12.09 10.2827C11.9159 9.71975 12.113 9.10809 12.5829 8.75263L14.673 7.17173Z' fill='black' />
                                    </svg>
                                    <svg width='16' height='15' viewBox='0 0 16 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                        <path opacity='0.25' d='M14.673 7.17173C15.7437 6.36184 15.1709 4.65517 13.8284 4.65517H11.3992C10.7853 4.65517 10.243 4.25521 10.0617 3.66868L9.33754 1.32637C8.9309 0.0110567 7.0691 0.0110564 6.66246 1.32637L5.93832 3.66868C5.75699 4.25521 5.21469 4.65517 4.60078 4.65517H2.12961C0.791419 4.65517 0.215919 6.35274 1.27822 7.16654L3.39469 8.78792C3.85885 9.1435 4.05314 9.75008 3.88196 10.3092L3.11296 12.8207C2.71416 14.1232 4.22167 15.1704 5.30301 14.342L7.14861 12.9281C7.65097 12.5432 8.34903 12.5432 8.85139 12.9281L10.6807 14.3295C11.7636 15.159 13.2725 14.1079 12.8696 12.8046L12.09 10.2827C11.9159 9.71975 12.113 9.10809 12.5829 8.75263L14.673 7.17173Z' fill='black' />
                                    </svg>

                                </span>
                                <span>
                                    (150 Reviews)
                                </span>
                                <span>|</span>
                                <span class='in-stack fw-bold'>
                                    In Stock
                                </span>
                            </div>
                            <h4>
                                \$$product_price
                            </h4>
                            <p>
                                $product_desc
                            </p>
                            <div class='divider'>
                            </div>
                            <form action='products.php?add_to_cart=$product_id'>
                                <div class='buy-item d-flex gap-3 justify-content-center align-items-center'>
                                    <div class='num-btns d-flex gap-1'>
                                        <button type='button' class='btn btn-increase' onclick='increaseValueBtn()'>+</button>
                                        <input type='number' class='form-control' name='num_of_items' id='num_of_items' value='1'>
                                        <input type='hidden' class='form-control' name='add_to_cart' id='add_to_cart' value='$product_id'/>
                                        <!-- <span class='num-of-items'>3</span> -->
                                        <button type='button' class='btn btn-decrease' onclick='decreaseValueBtn()'> -</button>
                                    </div>
                                    <div>
                                        <input type='submit' class='btn btn-primary' value='Buy Now'>
                                    </div>
                                </div>
                            </form>
                            <div class='delivery d-flex flex-column my-4 gap-3'>
                                <div class='d-flex gap-2 align-items-center'>
                                    <span>
                                        <svg width='40' height='40' viewBox='0 0 40 40' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                            <g clip-path='url(#clip0_261_4843)'>
                                                <path d='M11.6673 31.6667C13.5083 31.6667 15.0007 30.1743 15.0007 28.3333C15.0007 26.4924 13.5083 25 11.6673 25C9.82637 25 8.33398 26.4924 8.33398 28.3333C8.33398 30.1743 9.82637 31.6667 11.6673 31.6667Z' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M28.3333 31.6667C30.1743 31.6667 31.6667 30.1743 31.6667 28.3333C31.6667 26.4924 30.1743 25 28.3333 25C26.4924 25 25 26.4924 25 28.3333C25 30.1743 26.4924 31.6667 28.3333 31.6667Z' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M8.33398 28.3335H7.00065C5.89608 28.3335 5.00065 27.4381 5.00065 26.3335V21.6668M3.33398 8.3335H19.6673C20.7719 8.3335 21.6673 9.22893 21.6673 10.3335V28.3335M15.0007 28.3335H25.0007M31.6673 28.3335H33.0007C34.1052 28.3335 35.0007 27.4381 35.0007 26.3335V18.3335M35.0007 18.3335H21.6673M35.0007 18.3335L30.5833 10.9712C30.2218 10.3688 29.5708 10.0002 28.8683 10.0002H21.6673' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M8 28H6.66667C5.5621 28 4.66667 27.1046 4.66667 26V21.3333M3 8H19.3333C20.4379 8 21.3333 8.89543 21.3333 10V28M15 28H24.6667M32 28H32.6667C33.7712 28 34.6667 27.1046 34.6667 26V18M34.6667 18H21.3333M34.6667 18L30.2493 10.6377C29.8878 10.0353 29.2368 9.66667 28.5343 9.66667H21.3333' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M5 11.8182H11.6667' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M1.81836 15.4545H8.48503' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M5 19.0909H11.6667' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                            </g>
                                            <defs>
                                                <clipPath id='clip0_261_4843'>
                                                    <rect width='40' height='40' fill='white' />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </span>
                                    <div class='d-flex flex-column gap-2'>
                                        <h6>Free Delivery</h6>
                                        <span>Enter your postal code for Delivery Availability</span>
                                    </div>
                                </div>
                                <div class='d-flex gap-2 align-items-center'>
                                    <span>
                                        <svg width='40' height='40' viewBox='0 0 40 40' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                            <g clip-path='url(#clip0_261_4865)'>
                                                <path d='M33.3327 18.3334C32.9251 15.4004 31.5645 12.6828 29.4604 10.5992C27.3564 8.51557 24.6256 7.18155 21.6888 6.80261C18.752 6.42366 15.7721 7.02082 13.208 8.5021C10.644 9.98337 8.6381 12.2666 7.49935 15M6.66602 8.33335V15H13.3327' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M6.66602 21.6667C7.07361 24.5997 8.43423 27.3173 10.5383 29.4009C12.6423 31.4845 15.3731 32.8185 18.3099 33.1974C21.2467 33.5764 24.2266 32.9792 26.7907 31.4979C29.3547 30.0167 31.3606 27.7335 32.4994 25M33.3327 31.6667V25H26.666' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                            </g>
                                            <defs>
                                                <clipPath id='clip0_261_4865'>
                                                    <rect width='40' height='40' fill='white' />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </span>
                                    <div class='d-flex flex-column gap-2'>
                                        <h6>Return Delivery</h6>
                                        <span>Free 30 Days Delivery Returns. Details</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    ";
                }
            }
        }
    }
}

// get Ip Address Function
function getIPAddress()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// cart function
function cart($num_of_items = 1)
{
    if (isset($_GET['add_to_cart'])) {
        global $con;
        $getIpAddress = getIPAddress();
        $getProductId = $_GET['add_to_cart'];
        
        // Get quantity from form, default to 1 if not provided or invalid
        $quantity = 1;
        if (isset($_GET['num_of_items']) && is_numeric($_GET['num_of_items'])) {
            $quantity = intval($_GET['num_of_items']);
            if ($quantity < 1) {
                $quantity = 1;
            }
        }
        
        $select_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress' AND product_id=$getProductId";
        $select_result = mysqli_query($con, $select_query);
        $num_of_rows = mysqli_num_rows($select_result);
        if ($num_of_rows > 0) {
            echo "<script>showToast('This item is already present in Cart', 'warning');</script>";
        } else {
            $insert_query = "INSERT INTO `card_details` (product_id,ip_address,quantity) VALUES ($getProductId,'$getIpAddress',$quantity)";
            $insert_result = mysqli_query($con, $insert_query);
            if ($insert_result) {
                echo "<script>showToast('Item added to Cart successfully!', 'success'); if (typeof updateHeaderCounts === 'function') { updateHeaderCounts(); }</script>";
            }
        }
    }
}

// get cart item numbers function 
function cart_item()
{
    if (isset($_GET['add_to_cart'])) {
        global $con;
        $getIpAddress = getIPAddress();
        $select_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress' ";
        $select_result = mysqli_query($con, $select_query);
        $count_cart_items = mysqli_num_rows($select_result);
    } else {
        global $con;
        $getIpAddress = getIPAddress();
        $select_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress' ";
        $select_result = mysqli_query($con, $select_query);
        $count_cart_items = mysqli_num_rows($select_result);
    }
    echo $count_cart_items;
}

// total price function 
function total_cart_price()
{
    global $con;
    $getIpAddress = getIPAddress();
    $total_price = 0;
    $cart_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress'";
    $cart_result = mysqli_query($con, $cart_query);
    while ($row = mysqli_fetch_array($cart_result)) {
        $product_id = $row['product_id'];
        $select_product_query = "SELECT * FROM `products` WHERE product_id=$product_id";
        $select_product_result = mysqli_query($con, $select_product_query);
        while ($row_product_price = mysqli_fetch_array($select_product_result)) {
            $product_price = array($row_product_price['product_price']);
            $product_values = array_sum($product_price);
            $total_price += $product_values;
        }
    }
    echo $total_price;
}

// get user order details
function get_user_order_details()
{
    global $con;
    $username = $_SESSION['username'];
    $get_details_query = "SELECT * FROM `user_table` WHERE username = '$username'";
    $get_details_result = mysqli_query($con, $get_details_query);
    while ($row_query = mysqli_fetch_array($get_details_result)) {
        $user_id = $row_query['user_id'];
        if (!isset($_GET['edit_account'])) {
            if (!isset($_GET['my_orders'])) {
                if (!isset($_GET['delete_account'])) {
                    $get_orders_query = "SELECT * FROM `user_orders` WHERE user_id='$user_id' AND order_status='pending' ORDER BY order_date DESC";
                    $get_orders_result = mysqli_query($con,$get_orders_query);
                    $row_orders_count = mysqli_num_rows($get_orders_result);
                    
                    echo "<div class='max-w-4xl mx-auto'>";
                    echo "<div class='flex items-center gap-4 mb-6'>";
                    echo "<div class='w-1 h-9 bg-[#1e40af] rounded'></div>";
                    echo "<h2 class='text-3xl font-bold text-gray-900'>Pending Orders</h2>";
                    echo "</div>";
                    
                    if($row_orders_count > 0){
                        echo "<div class='bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg'>";
                        echo "<div class='flex items-center'>";
                        echo "<i class='fas fa-exclamation-triangle text-yellow-600 mr-3'></i>";
                        echo "<div>";
                        echo "<p class='text-sm font-semibold text-yellow-800'>You have <span class='text-lg'>$row_orders_count</span> pending order(s) waiting for confirmation</p>";
                        echo "<p class='text-xs text-yellow-700 mt-1'>Please confirm payment to complete your orders</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        
                        // Display pending orders
                        echo "<div class='space-y-4 mb-6'>";
                        $serial = 1;
                        while($order_row = mysqli_fetch_array($get_orders_result)){
                            $order_id = $order_row['order_id'];
                            $amount_due = $order_row['amount_due'];
                            $invoice_number = $order_row['invoice_number'];
                            $total_products = $order_row['total_products'];
                            $order_date = $order_row['order_date'];
                            $formatted_date = date('M d, Y', strtotime($order_date));
                            
                            echo "<div class='bg-white rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition-all'>";
                            echo "<div class='flex flex-col md:flex-row md:items-center md:justify-between gap-4'>";
                            echo "<div class='flex-1'>";
                            echo "<div class='flex items-center gap-4 mb-3'>";
                            echo "<div class='bg-yellow-100 w-12 h-12 rounded-full flex items-center justify-center'>";
                            echo "<i class='fas fa-clock text-yellow-600'></i>";
                            echo "</div>";
                            echo "<div>";
                            echo "<h3 class='text-lg font-bold text-gray-900'>Order #" . htmlspecialchars($order_id) . "</h3>";
                            echo "<p class='text-sm text-gray-500'>$formatted_date</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='grid grid-cols-3 gap-4 mt-4'>";
                            echo "<div>";
                            echo "<p class='text-xs text-gray-500 mb-1'>Invoice</p>";
                            echo "<p class='text-sm font-semibold text-gray-900'>" . htmlspecialchars($invoice_number) . "</p>";
                            echo "</div>";
                            echo "<div>";
                            echo "<p class='text-xs text-gray-500 mb-1'>Products</p>";
                            echo "<p class='text-sm font-semibold text-gray-900'>$total_products items</p>";
                            echo "</div>";
                            echo "<div>";
                            echo "<p class='text-xs text-gray-500 mb-1'>Amount</p>";
                            echo "<p class='text-sm font-semibold text-[#1e40af]'>$" . number_format($amount_due, 2) . "</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='flex items-center'>";
                            echo "<a href='confirm_payment.php?order_id=$order_id' class='bg-[#1e40af] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-md whitespace-nowrap'>";
                            echo "<i class='fas fa-check mr-2'></i>Confirm Payment";
                            echo "</a>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            $serial++;
                        }
                        echo "</div>";
                        
                        echo "<div class='text-center pt-4 border-t border-gray-200'>";
                        echo "<a href='profile.php?my_orders' class='inline-block bg-[#1e40af] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg'>";
                        echo "<i class='fas fa-list mr-2'></i>View All Orders";
                        echo "</a>";
                        echo "</div>";
                    }else{
                        echo "<div class='bg-white rounded-2xl shadow-md border border-gray-200 p-12 text-center'>";
                        echo "<div class='w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6'>";
                        echo "<i class='fas fa-check-circle text-4xl text-green-600'></i>";
                        echo "</div>";
                        echo "<h3 class='text-2xl font-bold text-gray-900 mb-3'>All Clear!</h3>";
                        echo "<p class='text-gray-600 mb-6'>You have no pending orders. All your orders are confirmed!</p>";
                        echo "<div class='flex gap-4 justify-center'>";
                        echo "<a href='profile.php?my_orders' class='bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-all'>";
                        echo "<i class='fas fa-list mr-2'></i>View All Orders";
                        echo "</a>";
                        echo "<a href='../index.php' class='bg-[#1e40af] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg'>";
                        echo "<i class='fas fa-shopping-cart mr-2'></i>Continue Shopping";
                        echo "</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
            }
        }
    }
}

// Get user ID function
function getUserId() {
    global $con;
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $get_user_query = "SELECT * FROM `user_table` WHERE username='$username'";
        $get_user_result = mysqli_query($con, $get_user_query);
        $row = mysqli_fetch_array($get_user_result);
        return $row['user_id'] ?? 0;
    }
    return 0;
}

// Get wishlist item count
function wishlist_item() {
    global $con;
    $getIpAddress = getIPAddress();
    $user_id = isset($_SESSION['username']) ? getUserId() : 0;
    
    // Check if wishlist table exists
    $check_table = "SHOW TABLES LIKE 'wishlist'";
    $table_exists = mysqli_query($con, $check_table);
    
    if (mysqli_num_rows($table_exists) == 0) {
        echo 0;
        return;
    }
    
    if ($user_id > 0) {
        $select_query = "SELECT * FROM `wishlist` WHERE user_id=$user_id";
    } else {
        $select_query = "SELECT * FROM `wishlist` WHERE ip_address='$getIpAddress' AND user_id=0";
    }
    
    $select_result = mysqli_query($con, $select_query);
    $count_wishlist_items = mysqli_num_rows($select_result);
    echo $count_wishlist_items;
}
