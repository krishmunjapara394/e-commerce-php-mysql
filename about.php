<?php
include("./includes/connect.php");
include("./functions/common_functions.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <?php include('./includes/header.php'); ?>

    <!-- Hero Section -->
    <section class="bg-[#1e40af] text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">About A1 Store</h1>
                <p class="text-xl text-white/90">Your trusted destination for quality products at unbeatable prices. We're committed to providing exceptional shopping experiences.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                    <span class="text-[#1e40af] font-bold text-sm uppercase">Our Story</span>
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Who We Are</h2>
                <p class="text-lg text-gray-600 leading-relaxed">
                    A1 Store was founded with a simple mission: to make quality products accessible to everyone. 
                    We believe that everyone deserves access to premium products without breaking the bank. 
                    Our team works tirelessly to curate the best selection of products, ensuring quality, affordability, and customer satisfaction.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center hover:shadow-xl transition-all">
                    <div class="w-20 h-20 bg-[#1e40af] rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-box text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Quality Products</h3>
                    <p class="text-gray-600">We carefully select each product to ensure it meets our high standards for quality and durability.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center hover:shadow-xl transition-all">
                    <div class="w-20 h-20 bg-[#1e40af] rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shipping-fast text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Fast Delivery</h3>
                    <p class="text-gray-600">We understand the importance of timely delivery. Our efficient shipping ensures your orders arrive quickly.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center hover:shadow-xl transition-all">
                    <div class="w-20 h-20 bg-[#1e40af] rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-headset text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Customer Support</h3>
                    <p class="text-gray-600">Our dedicated support team is always ready to help you with any questions or concerns you may have.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-[#1e40af] mb-2">10K+</div>
                    <div class="text-gray-600 font-semibold">Happy Customers</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-[#1e40af] mb-2">500+</div>
                    <div class="text-gray-600 font-semibold">Products</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-[#1e40af] mb-2">50+</div>
                    <div class="text-gray-600 font-semibold">Brands</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-[#1e40af] mb-2">24/7</div>
                    <div class="text-gray-600 font-semibold">Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                    <span class="text-[#1e40af] font-bold text-sm uppercase">Our Values</span>
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">What Drives Us</h2>
            </div>

            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-all">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Integrity</h3>
                    <p class="text-gray-600 leading-relaxed">We conduct our business with honesty and transparency, building trust with our customers through ethical practices.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-all">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Innovation</h3>
                    <p class="text-gray-600 leading-relaxed">We continuously improve our services and embrace new technologies to enhance your shopping experience.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-all">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Customer First</h3>
                    <p class="text-gray-600 leading-relaxed">Your satisfaction is our top priority. We go above and beyond to ensure you have the best shopping experience.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-all">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Sustainability</h3>
                    <p class="text-gray-600 leading-relaxed">We're committed to sustainable practices and reducing our environmental impact while serving our customers.</p>
                </div>
            </div>
        </div>
    </section>

    <?php include('./includes/footer.php'); ?>
</body>

</html>
