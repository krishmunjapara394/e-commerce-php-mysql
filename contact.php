<?php
include("./includes/connect.php");
include("./functions/common_functions.php");
session_start();

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, trim($_POST['name']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone = mysqli_real_escape_string($con, trim($_POST['phone']));
    $subject = mysqli_real_escape_string($con, trim($_POST['subject']));
    $message_text = mysqli_real_escape_string($con, trim($_POST['message']));
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $message = 'Please fill in all required fields.';
        $message_type = 'danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $message_type = 'danger';
    } else {
        // Check if contact_messages table exists, if not create it
        $check_table = "SHOW TABLES LIKE 'contact_messages'";
        $table_exists = mysqli_query($con, $check_table);
        
        if (mysqli_num_rows($table_exists) == 0) {
            // Create table if it doesn't exist
            $create_table = "CREATE TABLE `contact_messages` (
                `contact_id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `email` varchar(200) NOT NULL,
                `phone` varchar(20) DEFAULT NULL,
                `subject` varchar(255) NOT NULL,
                `message` text NOT NULL,
                `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
                `status` varchar(20) DEFAULT 'unread',
                PRIMARY KEY (`contact_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
            
            if (!mysqli_query($con, $create_table)) {
                $message = 'Database error. Please contact administrator.';
                $message_type = 'danger';
            }
        }
        
        // Insert contact message
        $insert_query = "INSERT INTO `contact_messages` (name, email, phone, subject, message, status) 
                        VALUES ('$name', '$email', '$phone', '$subject', '$message_text', 'unread')";
        
        if (mysqli_query($con, $insert_query)) {
            $message = 'Thank you for contacting us! We will get back to you soon.';
            $message_type = 'success';
            // Clear form data
            $_POST = array();
        } else {
            $message = 'Sorry, there was an error submitting your message. Please try again.';
            $message_type = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <?php include('./includes/header.php'); ?>

    <!-- Hero Section -->
    <section class="bg-[#1e40af] text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">Get In Touch</h1>
                <p class="text-xl text-white/90">Have a question or need help? We're here to assist you. Send us a message and we'll respond as soon as possible.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <?php if ($message): ?>
                <div class="max-w-4xl mx-auto mb-8">
                    <div class="bg-<?php echo $message_type == 'success' ? 'green' : 'red'; ?>-50 border border-<?php echo $message_type == 'success' ? 'green' : 'red'; ?>-200 text-<?php echo $message_type == 'success' ? 'green' : 'red'; ?>-800 px-6 py-4 rounded-xl">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                            <span class="text-[#1e40af] font-bold text-sm uppercase">Send Us a Message</span>
                        </div>
                        <form method="POST" action="contact.php" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" 
                                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent">
                                </div>
                                <div>
                                    <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                                    <select id="subject" name="subject" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent">
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                                        <option value="Product Question" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Product Question') ? 'selected' : ''; ?>>Product Question</option>
                                        <option value="Order Support" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Order Support') ? 'selected' : ''; ?>>Order Support</option>
                                        <option value="Return/Refund" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Return/Refund') ? 'selected' : ''; ?>>Return/Refund</option>
                                        <option value="Technical Support" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Technical Support') ? 'selected' : ''; ?>>Technical Support</option>
                                        <option value="Other" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Message <span class="text-red-500">*</span></label>
                                <textarea id="message" name="message" rows="6" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            <button type="submit" class="w-full bg-[#1e40af] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-[1.02] shadow-lg">
                                <i class="fas fa-paper-plane mr-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                            <span class="text-[#1e40af] font-bold text-sm uppercase">Contact Information</span>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#1e40af] rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-1">Address</h5>
                                    <p class="text-gray-600 text-sm">123 Commerce Street<br>Business District<br>City, State 12345</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#1e40af] rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-phone text-white"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-1">Phone</h5>
                                    <p class="text-gray-600 text-sm">+1 (555) 123-4567<br>+1 (555) 123-4568</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#1e40af] rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-1">Email</h5>
                                    <p class="text-gray-600 text-sm">support@a1ecommerce.com<br>info@a1ecommerce.com</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#1e40af] rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-1">Business Hours</h5>
                                    <p class="text-gray-600 text-sm">Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('./includes/footer.php'); ?>
</body>

</html>
