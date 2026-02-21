<?php
// Determine base path for links (works from both root and subdirectories)
$base_path = '';
if(strpos($_SERVER['PHP_SELF'], '/users_area/') !== false || 
   strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    $base_path = '../';
}
?>
<!-- Footer Section -->
<footer class="bg-gray-900 text-gray-300">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <!-- About Company -->
            <div>
                <h3 class="text-white text-xl font-bold mb-4">A1 Store</h3>
                <p class="mb-4 text-gray-400">Your trusted online shopping destination. Quality products at great prices with exceptional customer service.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-facebook text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-youtube text-xl"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="<?php echo $base_path; ?>index.php" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="<?php echo $base_path; ?>products.php" class="hover:text-white transition-colors">Shop</a></li>
                    <li><a href="<?php echo $base_path; ?>about.php" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="<?php echo $base_path; ?>contact.php" class="hover:text-white transition-colors">Contact</a></li>
                    <li><a href="<?php echo $base_path; ?>cart.php" class="hover:text-white transition-colors">Cart</a></li>
                </ul>
            </div>

            <!-- Customer Support -->
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Customer Support</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Returns & Refunds</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Order Tracking</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Shipping Info</a></li>
                    <li><a href="<?php echo $base_path; ?>contact.php" class="hover:text-white transition-colors">Contact Support</a></li>
                </ul>
            </div>

            <!-- Newsletter Box -->
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Stay Connected</h3>
                <p class="mb-4 text-gray-400">Subscribe to get updates on new products and special offers.</p>
                <form class="flex flex-col gap-2" onsubmit="event.preventDefault(); showToast('Thank you for subscribing!', 'success');">
                    <input type="email" 
                           placeholder="Your email" 
                           class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 focus:outline-none focus:border-[#1e40af] text-white">
                    <button type="submit" 
                            class="bg-[#1e40af] text-white px-4 py-2 rounded-lg hover:bg-[#1e3a8a] transition-all">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <!-- Payment Icons & Copyright -->
        <div class="border-t border-gray-800 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex gap-4">
                    <i class="fab fa-cc-visa text-3xl text-gray-400"></i>
                    <i class="fab fa-cc-mastercard text-3xl text-gray-400"></i>
                    <i class="fab fa-cc-paypal text-3xl text-gray-400"></i>
                    <i class="fab fa-cc-amex text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-400 text-sm">© <?php echo date('Y'); ?> A1 Store. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" 
        class="fixed bottom-8 right-8 bg-[#1e40af] text-white p-4 rounded-full shadow-lg hover:bg-[#1e3a8a] transition-all z-50 opacity-0 invisible"
        style="transition: all 0.3s ease;">
    <i class="fas fa-arrow-up"></i>
</button>

<script>
    // Back to Top Button
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopBtn = document.getElementById('backToTop');
        
        if(backToTopBtn) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopBtn.style.opacity = '1';
                    backToTopBtn.style.visibility = 'visible';
                } else {
                    backToTopBtn.style.opacity = '0';
                    backToTopBtn.style.visibility = 'hidden';
                }
            });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    });
</script>
