                    <?php
// Determine the current page for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Determine base path for links (works from both root and subdirectories)
$base_path = '';
if(strpos($_SERVER['PHP_SELF'], '/users_area/') !== false || 
   strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    $base_path = '../';
}
?>
<!-- Toastify CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<!-- Toastify JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
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
<!-- Announcement Bar -->
<div class="bg-[#1e40af] text-white py-2 text-center text-sm">
    <div class="container mx-auto px-4">
        <p class="flex items-center justify-center gap-4 flex-wrap">
            <span><i class="fas fa-truck mr-2"></i>Free Delivery on orders over $50</span>
            <span class="hidden md:inline">|</span>
            <span><i class="fas fa-headset mr-2"></i>24/7 Customer Support</span>
            <span class="hidden md:inline">|</span>
            <span><i class="fas fa-shield-alt mr-2"></i>Secure Payment</span>
        </p>
    </div>
</div>

<!-- Dynamic Header / Navbar -->
<header class="bg-white shadow-md sticky top-0 z-50">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="<?php echo $base_path; ?>index.php" class="text-3xl font-bold text-[#1e40af] hover:text-[#1e3a8a] transition-colors">
                <i class="fas fa-shopping-bag mr-2"></i>A1 Store
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="<?php echo $base_path; ?>index.php" 
                   class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors <?php echo ($current_page == 'index.php') ? 'border-b-2 border-[#1e40af] pb-1' : ''; ?>">
                    Home
                </a>
                <a href="<?php echo $base_path; ?>products.php" 
                   class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors <?php echo ($current_page == 'products.php') ? 'border-b-2 border-[#1e40af] pb-1' : ''; ?>">
                    Shop
                </a>
                <a href="<?php echo $base_path; ?>about.php" 
                   class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors <?php echo ($current_page == 'about.php') ? 'border-b-2 border-[#1e40af] pb-1' : ''; ?>">
                    About Us
                </a>
                <a href="<?php echo $base_path; ?>contact.php" 
                   class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors <?php echo ($current_page == 'contact.php') ? 'border-b-2 border-[#1e40af] pb-1' : ''; ?>">
                    Contact
                </a>
            </div>

            <!-- Search Bar -->
            <div class="hidden md:flex items-center flex-1 max-w-md mx-8" style="position: relative;">
                <div class="navbar-search" style="position: relative; width: 100%;">
                    <input type="search" 
                           id="liveSearchInput"
                           name="search" 
                           placeholder="Search products..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                           autocomplete="off"
                           style="width: 100%; padding: 12px 60px 12px 20px; border: 1px solid #e5e7eb; border-radius: 9999px; background-color: #ffffff; font-size: 14px; color: #1f2937; transition: all 0.3s ease;">
                    <button type="button" 
                            id="liveSearchButton"
                            class="navbar-search-button"
                            style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); width: 40px; height: 40px; background-color: #1e40af; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-search" style="color: #ffffff; font-size: 16px;"></i>
                        </button>
                    </div>
            </div>

            <!-- Icons -->
            <div class="flex items-center space-x-4">
                <!-- User Account -->
                <div class="relative group">
                    <a href="<?php echo $base_path . (isset($_SESSION['username']) ? 'users_area/profile.php' : 'users_area/user_login.php'); ?>" 
                       class="text-gray-700 hover:text-[#1e40af] transition-colors p-2">
                        <i class="fas fa-user text-xl"></i>
                        <?php if(isset($_SESSION['username'])): ?>
                            <span class="hidden lg:inline ml-1 text-sm"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if(!isset($_SESSION['username'])): ?>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <a href="<?php echo $base_path; ?>users_area/user_login.php" class="block px-4 py-2 text-gray-700 hover:bg-[#1e40af] hover:text-white transition-colors">Login</a>
                        <a href="<?php echo $base_path; ?>users_area/user_registration.php" class="block px-4 py-2 text-gray-700 hover:bg-[#1e40af] hover:text-white transition-colors">Register</a>
                    </div>
                    <?php else: ?>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <a href="<?php echo $base_path; ?>users_area/profile.php" class="block px-4 py-2 text-gray-700 hover:bg-[#1e40af] hover:text-white transition-colors">My Profile</a>
                        <a href="<?php echo $base_path; ?>users_area/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-[#1e40af] hover:text-white transition-colors">Logout</a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Wishlist -->
                <a href="<?php echo $base_path; ?>wishlist.php" class="text-gray-700 hover:text-[#1e40af] transition-colors p-2 relative">
                    <i class="fas fa-heart text-xl"></i>
                    <span id="wishlist-count-header" class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                <?php
                        if(function_exists('wishlist_item')) {
                            wishlist_item(); 
                        } else {
                            echo '0';
                        }
                                ?>
                            </span>
                        </a>

                <!-- Cart -->
                <a href="<?php echo $base_path; ?>cart.php" class="text-gray-700 hover:text-[#1e40af] transition-colors p-2 relative">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span id="cart-count-header" class="absolute top-0 right-0 bg-[#1e40af] text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                <?php
                        if(function_exists('cart_item')) {
                            cart_item(); 
                        } else {
                            echo '0';
                                }
                                ?>
                            </span>
                        </a>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="lg:hidden text-gray-700 hover:text-[#1e40af]">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden mt-4 pb-4 border-t border-gray-200">
            <div class="flex flex-col space-y-3 mt-4">
                    <a href="<?php echo $base_path; ?>index.php" 
                   class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors <?php echo ($current_page == 'index.php') ? 'text-[#1e40af] font-semibold' : ''; ?>">
                    Home
                </a>
                <a href="<?php echo $base_path; ?>products.php" 
                   class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors <?php echo ($current_page == 'products.php') ? 'text-[#1e40af] font-semibold' : ''; ?>">
                    Shop
                </a>
                <a href="<?php echo $base_path; ?>about.php" 
                   class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors <?php echo ($current_page == 'about.php') ? 'text-[#1e40af] font-semibold' : ''; ?>">
                    About Us
                </a>
                <a href="<?php echo $base_path; ?>contact.php" 
                   class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors <?php echo ($current_page == 'contact.php') ? 'text-[#1e40af] font-semibold' : ''; ?>">
                    Contact
                </a>
                <?php if(isset($_SESSION['username'])): ?>
                    <a href="<?php echo $base_path; ?>users_area/profile.php" class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors">My Account</a>
                    <a href="<?php echo $base_path; ?>users_area/logout.php" class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base_path; ?>users_area/user_login.php" class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors">Login</a>
                    <a href="<?php echo $base_path; ?>users_area/user_registration.php" class="text-gray-700 hover:text-[#1e40af] font-medium transition-colors">Register</a>
                <?php endif; ?>
                <div class="navbar-search mt-2" style="position: relative; width: 100%;">
                    <input type="search" 
                           id="liveSearchInputMobile"
                           name="search" 
                           placeholder="Search products..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                           autocomplete="off"
                           style="width: 100%; padding: 12px 60px 12px 20px; border: 1px solid #e5e7eb; border-radius: 9999px; background-color: #ffffff; font-size: 14px; color: #1f2937; transition: all 0.3s ease;">
                    <button type="button" 
                            id="liveSearchButtonMobile"
                            class="navbar-search-button"
                            style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); width: 40px; height: 40px; background-color: #1e40af; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-search" style="color: #ffffff; font-size: 16px;"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
    // Mobile Menu Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if(mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Search button hover effect
        const searchButtons = document.querySelectorAll('.navbar-search-button');
        searchButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#1e3a8a';
                this.style.transform = 'translateY(-50%) scale(1.05)';
            });
            button.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '#1e40af';
                this.style.transform = 'translateY(-50%) scale(1)';
            });
        });

        // Search input focus effect
        const searchInputs = document.querySelectorAll('.navbar-search input');
        searchInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = '#1e40af';
                this.style.boxShadow = '0 0 0 3px rgba(30, 64, 175, 0.1)';
            });
            input.addEventListener('blur', function() {
                this.style.borderColor = '#e5e7eb';
                this.style.boxShadow = 'none';
            });
        });

        // Live Search Functionality - Filter products in main area
        let searchTimeout;
        const basePath = window.location.pathname.includes('/users_area/') || window.location.pathname.includes('/admin/') ? '../' : './';
        const searchApiPath = basePath + 'search_products_ajax.php';

        // Store original products HTML for reset
        let originalProductsHTML = null;

        function performLiveSearch(inputElement) {
            const query = inputElement.value.trim();
            const productsGrid = document.querySelector('.grid.grid-cols-1, .grid[class*="grid-cols"], .grid');
            
            if (!productsGrid) {
                // If not on products page, redirect to products page with search
                if (query.length > 0) {
                    window.location.href = `${basePath}products.php?search=${encodeURIComponent(query)}`;
                }
                return;
            }

            // Store original HTML on first search
            if (!originalProductsHTML) {
                originalProductsHTML = productsGrid.innerHTML;
            }

            // Clear previous timeout
            clearTimeout(searchTimeout);

            // Debounce search - wait 300ms after user stops typing
            searchTimeout = setTimeout(() => {
                if (query.length === 0) {
                    // Restore original products if search is empty
                    if (originalProductsHTML) {
                        productsGrid.innerHTML = originalProductsHTML;
                    }
                    return;
                }

                // Fetch search results
                fetch(`${searchApiPath}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.products.length > 0) {
                            // Clear grid and add search results
                            productsGrid.innerHTML = '';
                            data.products.forEach(product => {
                                const cardHtml = createProductCard(product);
                                productsGrid.insertAdjacentHTML('beforeend', cardHtml);
                            });
                        } else {
                            // Show "no results" message
                            productsGrid.innerHTML = `
                                <div id="noSearchResults" class="col-span-full text-center py-12">
                                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 max-w-2xl mx-auto">
                                        <i class="fas fa-search text-6xl text-gray-400 mb-4"></i>
                                        <h4 class="text-2xl font-bold text-gray-900 mb-2">No products found!</h4>
                                        <p class="text-gray-600 mb-6">No results match your search for "<strong>${data.query}</strong>". Try different keywords.</p>
                                    </div>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }, 300);
        }

        function createProductCard(product) {
            // Handle image path - if it's not a full URL, prepend base path
            let imageUrl = product.image;
            if (!imageUrl.startsWith('http://') && !imageUrl.startsWith('https://')) {
                imageUrl = basePath + imageUrl;
            }
            
            return `
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group" data-product-id="${product.id}">
                    <div class="relative overflow-hidden bg-gray-100 h-64">
                        <img src="${imageUrl}" 
                             alt="${product.title}" 
                             class="product-image w-full h-full object-contain p-4 transition-transform duration-300">
                        <div class="product-overlay absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300 flex items-center justify-center gap-3 px-4">
                            <a href="${basePath}products.php?add_to_cart=${product.id}" 
                               class="bg-white text-[#1e40af] px-5 py-2.5 rounded-lg font-semibold hover:bg-[#1e40af] hover:text-white transition-all transform hover:scale-105 whitespace-nowrap flex items-center gap-2 shadow-lg">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="text-sm">Add to Cart</span>
                            </a>
                            <a href="${basePath}product_details.php?product_id=${product.id}" 
                               class="bg-[#1e40af] text-white px-5 py-2.5 rounded-lg font-semibold transform hover:scale-105 whitespace-nowrap flex items-center gap-2 shadow-lg hover:bg-[#1e3a8a] transition-all">
                                <span class="text-sm">View Details</span>
                            </a>
                        </div>
                        <button onclick="addToWishlistQuick(${product.id})" 
                                class="absolute top-4 right-4 bg-white text-red-500 w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-lg z-10">
                            <i class="fas fa-heart"></i>
                        </button>
                        <span class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">-15%</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">${product.title}</h3>
                        <div class="flex items-center gap-1 mb-3">
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <span class="text-gray-500 text-sm ml-2">(35)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[#1e40af] font-bold text-xl">$${product.discount_price}</span>
                            <span class="text-gray-400 line-through">$${product.price}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Desktop search
        const desktopSearchInput = document.getElementById('liveSearchInput');
        const desktopSearchButton = document.getElementById('liveSearchButton');

        if (desktopSearchInput) {
            desktopSearchInput.addEventListener('input', function() {
                performLiveSearch(this);
            });

            desktopSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();
                    if (query.length > 0) {
                        window.location.href = `${basePath}products.php?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        }

        if (desktopSearchButton) {
            desktopSearchButton.addEventListener('click', function() {
                const query = desktopSearchInput.value.trim();
                if (query.length > 0) {
                    window.location.href = `${basePath}products.php?search=${encodeURIComponent(query)}`;
                }
            });
        }

        // Mobile search
        const mobileSearchInput = document.getElementById('liveSearchInputMobile');
        const mobileSearchButton = document.getElementById('liveSearchButtonMobile');

        if (mobileSearchInput) {
            mobileSearchInput.addEventListener('input', function() {
                performLiveSearch(this);
            });

            mobileSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();
                    if (query.length > 0) {
                        window.location.href = `${basePath}products.php?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        }

        if (mobileSearchButton) {
            mobileSearchButton.addEventListener('click', function() {
                const query = mobileSearchInput.value.trim();
                if (query.length > 0) {
                    window.location.href = `${basePath}products.php?search=${encodeURIComponent(query)}`;
                }
            });
        }
    });
    
    // Global function to update header counts (must be outside DOMContentLoaded)
    window.updateHeaderCounts = function() {
        // Determine correct path to get_counts.php
        const basePath = window.location.pathname.includes('/users_area/') || window.location.pathname.includes('/admin/') ? '../' : './';
        const apiPath = basePath + 'get_counts.php';
        
        fetch(apiPath)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const cartCountEl = document.getElementById('cart-count-header');
                    const wishlistCountEl = document.getElementById('wishlist-count-header');
                    
                    if (cartCountEl) {
                        cartCountEl.textContent = data.cart_count || '0';
                    } else {
                        console.warn('Cart count element not found');
                    }
                    if (wishlistCountEl) {
                        wishlistCountEl.textContent = data.wishlist_count || '0';
                    } else {
                        console.warn('Wishlist count element not found');
                    }
                } else {
                    console.error('Failed to get counts:', data);
                }
            })
            .catch(error => {
                console.error('Error updating counts:', error);
            });
    };
    
    // Update counts on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof updateHeaderCounts === 'function') {
                updateHeaderCounts();
            }
        });
    } else {
        // DOM already loaded
        if (typeof updateHeaderCounts === 'function') {
            updateHeaderCounts();
        }
    }
</script>
