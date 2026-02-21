<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
@session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <?php include('../includes/header.php'); ?>
    
    <section class="py-16 min-h-[calc(100vh-200px)]">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-[#1e40af] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user text-white text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">User Login</h2>
                        <p class="text-gray-600">Welcome back! Please login to your account.</p>
                    </div>
                    
                    <form action="" method="post" class="space-y-6">
                        <div>
                            <label for="user_username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                            <input type="text" id="user_username" name="user_username" 
                                   placeholder="Enter your username" 
                                   autocomplete="off" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="user_password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <input type="password" id="user_password" name="user_password" 
                                   placeholder="Enter your password" 
                                   autocomplete="off" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent">
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember" class="w-4 h-4 text-[#1e40af] border-gray-300 rounded focus:ring-[#1e40af]">
                                <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                            </div>
                            <a href="" class="text-sm text-[#1e40af] hover:underline">Forgot password?</a>
                        </div>
                        
                        <button type="submit" name="user_login" 
                                class="w-full bg-[#1e40af] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-[1.02] shadow-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </button>
                        
                        <p class="text-center text-gray-600">
                            Don't have an account? 
                            <a href="user_registration.php" class="text-[#1e40af] font-semibold hover:underline">Register</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <?php include('../includes/footer.php'); ?>
</body>

</html>
<?php
if (isset($_POST['user_login'])) {
    $user_username = $_POST['user_username'];
    $user_password = $_POST['user_password'];
    $select_query = "SELECT * FROM `user_table` WHERE username='$user_username'";
    $select_result = mysqli_query($con, $select_query);
    $row_data = mysqli_fetch_assoc($select_result);
    $row_count = mysqli_num_rows($select_result);
    $user_ip = getIPAddress();
    //check if user have items |! -> redirect to payment | index 
    $select_cart_query = "SELECT * FROM `card_details` WHERE ip_address='$user_ip'";
    $select_cart_result = mysqli_query($con, $select_cart_query);
    $row_cart_count = mysqli_num_rows($select_cart_result);
    //user check about username & pass
    if ($row_count > 0) {
        if (password_verify($user_password, $row_data['user_password'])) {
            $_SESSION['username'] = $user_username;
            if ($row_count == 1 && $row_cart_count == 0) {
                $_SESSION['username'] = $user_username;
                echo "<script>showToast('Login successful!', 'success'); setTimeout(() => { window.location.href = 'profile.php'; }, 1500);</script>";
            } else if ($row_count == 1 && $row_cart_count > 0) {
                $_SESSION['username'] = $user_username;
                echo "<script>showToast('Login successful!', 'success'); setTimeout(() => { window.location.href = 'payment.php'; }, 1500);</script>";
            }
        } else {
            echo "<script>showToast('Invalid username or password', 'error');</script>";
        }
    } else {
        echo "<script>showToast('Invalid username or password', 'error');</script>";
    }
}
?>
