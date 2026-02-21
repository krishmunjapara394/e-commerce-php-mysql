<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    <?php include('../includes/header.php'); ?>

    <section class="py-16 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto animate-fade-in">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-[#1e40af] text-white p-8 text-center">
                        <i class="fas fa-user-plus text-6xl mb-4"></i>
                        <h2 class="text-3xl font-bold">New User Registration</h2>
                        <p class="text-white/90 mt-2">Create your account to start shopping with us.</p>
                    </div>
                    
                    <div class="p-8">
                        <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Username field -->
                                <div>
                                    <label for="user_username" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-user mr-2 text-[#1e40af]"></i>Username
                                    </label>
                                    <input type="text" placeholder="Enter your username" autocomplete="off" 
                                           required name="user_username" id="user_username" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                                
                                <!-- Email field -->
                                <div>
                                    <label for="user_email" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-envelope mr-2 text-[#1e40af]"></i>Email
                                    </label>
                                    <input type="email" placeholder="Enter your email" autocomplete="off" 
                                           required name="user_email" id="user_email" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                            </div>
                            
                            <!-- Image field -->
                            <div>
                                <label for="user_image" class="block text-sm font-semibold text-gray-800 mb-2">
                                    <i class="fas fa-image mr-2 text-[#1e40af]"></i>User Image
                                </label>
                                <input type="file" required name="user_image" id="user_image" 
                                       accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password field -->
                                <div>
                                    <label for="user_password" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-lock mr-2 text-[#1e40af]"></i>Password
                                    </label>
                                    <input type="password" placeholder="Enter your password" autocomplete="off" 
                                           required name="user_password" id="user_password" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                                
                                <!-- Confirm password field -->
                                <div>
                                    <label for="conf_user_password" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-lock mr-2 text-[#1e40af]"></i>Confirm Password
                                    </label>
                                    <input type="password" placeholder="Confirm your password" autocomplete="off" 
                                           required name="conf_user_password" id="conf_user_password" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Address field -->
                                <div>
                                    <label for="user_address" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-map-marker-alt mr-2 text-[#1e40af]"></i>Address
                                    </label>
                                    <input type="text" placeholder="Enter your address" autocomplete="off" 
                                           required name="user_address" id="user_address" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                                
                                <!-- Mobile field -->
                                <div>
                                    <label for="user_mobile" class="block text-sm font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-phone mr-2 text-[#1e40af]"></i>Mobile
                                    </label>
                                    <input type="text" placeholder="Enter your mobile" autocomplete="off" 
                                           required name="user_mobile" id="user_mobile" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <input type="submit" value="Register" 
                                       class="w-full bg-[#1e40af] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg hover:shadow-xl cursor-pointer" 
                                       name="user_register">
                                <p class="text-center mt-4 text-gray-600">
                                    Already have an account? 
                                    <a href="user_login.php" class="text-[#1e40af] font-semibold hover:underline">Login</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include('../includes/footer.php'); ?>
</body>

</html>
<!-- php code  -->
<?php
if (isset($_POST['user_register'])) {
    $user_username = $_POST['user_username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $hash_password = password_hash($user_password,PASSWORD_DEFAULT);
    $conf_user_password = $_POST['conf_user_password'];
    $user_address = $_POST['user_address'];
    $user_mobile = $_POST['user_mobile'];
    $user_image = $_FILES['user_image']['name'];
    $user_image_tmp = $_FILES['user_image']['tmp_name'];
    $user_ip = getIPAddress();
    // check if user exist or not
    $select_query = "SELECT * FROM `user_table` WHERE username='$user_username' OR user_email='$user_email'";
    $select_result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($select_result);
    if ($rows_count > 0) {
        echo "<script>showToast('Username or Email already exists', 'error');</script>";
    } else if ($user_password != $conf_user_password) {
        echo "<script>showToast('Passwords do not match', 'error');</script>";
    } else {
        // insert query
        move_uploaded_file($user_image_tmp, "./user_images/$user_image");
        $insert_query = "INSERT INTO `user_table` (username,user_email,user_password,user_image,user_ip,user_address,user_mobile) VALUES ('$user_username','$user_email','$hash_password','$user_image','$user_ip','$user_address','$user_mobile')";
        $insert_result = mysqli_query($con, $insert_query);
        if ($insert_result) {
            echo "<script>showToast('Registration successful! Redirecting to login...', 'success'); setTimeout(() => { window.location.href = 'user_login.php'; }, 2000);</script>";
        } else {
            die(mysqli_error($con));
        }
    }
}
?>
