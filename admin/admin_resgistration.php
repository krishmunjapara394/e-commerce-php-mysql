<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Ecommerce Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.6s ease-out; }
    </style>
</head>

<body class="bg-gray-50">
    <section class="py-16 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto animate-fade-in">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-[#1e40af] text-white p-8 text-center">
                        <i class="fas fa-user-shield text-6xl mb-4"></i>
                        <h2 class="text-3xl font-bold">Admin Registration</h2>
                        <p class="text-white/90 mt-2">Create your admin account to manage the store.</p>
                    </div>
                    
                    <div class="p-8">
                        <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Username field -->
                                <div>
                                    <label for="username" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-user mr-2 text-[#1e40af]"></i>Username
                                    </label>
                                    <input type="text" placeholder="Enter your username" autocomplete="off" 
                                           required name="username" id="username" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                                
                                <!-- Email field -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-envelope mr-2 text-[#1e40af]"></i>Email
                                    </label>
                                    <input type="email" placeholder="Enter your email" autocomplete="off" 
                                           required name="email" id="email" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                            </div>
                            
                            <!-- Image field -->
                            <div>
                                <label for="admin_image" class="block text-sm font-semibold text-gray-800 mb-2">
                                    <i class="fas fa-image mr-2 text-[#1e40af]"></i>Admin Image
                                </label>
                                <input type="file" required name="admin_image" id="admin_image" 
                                       accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password field -->
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-lock mr-2 text-[#1e40af]"></i>Password
                                    </label>
                                    <input type="password" placeholder="Enter your password" autocomplete="off" 
                                           required name="password" id="password" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                                
                                <!-- Confirm password field -->
                                <div>
                                    <label for="conf_password" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-lock mr-2 text-[#1e40af]"></i>Confirm Password
                                    </label>
                                    <input type="password" placeholder="Confirm your password" autocomplete="off" 
                                           required name="conf_password" id="conf_password" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <input type="submit" value="Register" 
                                       class="w-full bg-[#1e40af] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg hover:shadow-xl cursor-pointer" 
                                       name="admin_register">
                                <p class="text-center mt-4 text-gray-600">
                                    Already have an account? 
                                    <a href="./admin_login.php" class="text-[#1e40af] font-semibold hover:underline">Login</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
<!-- php code  -->
<?php
if (isset($_POST['admin_register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hash_password = password_hash($password,PASSWORD_DEFAULT);
    $conf_password = $_POST['conf_password'];
    $image = $_FILES['admin_image']['name'];
    $image_tmp = $_FILES['admin_image']['tmp_name'];
    // check if user exist or not
    $select_query = "SELECT * FROM `admin_table` WHERE admin_name='$username' OR admin_email='$email'";
    $select_result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($select_result);
    if ($rows_count > 0) {
        echo "<script>showToast('Username or Email already exists', 'error');</script>";
    } else if ($password != $conf_password) {
        echo "<script>showToast('Passwords do not match', 'error');</script>";
    } else {
        // insert query
        move_uploaded_file($image_tmp, "./admin_images/$image");
        $insert_query = "INSERT INTO `admin_table` (admin_name,admin_email,admin_image,admin_password) VALUES ('$username','$email','$image','$hash_password')";
        $insert_result = mysqli_query($con, $insert_query);
        if ($insert_result) {
            echo "<script>showToast('Admin registered successfully! Redirecting to login...', 'success'); setTimeout(() => { window.location.href = 'admin_login.php'; }, 2000);</script>";
        } else {
            die(mysqli_error($con));
        }
    }
}
?>
