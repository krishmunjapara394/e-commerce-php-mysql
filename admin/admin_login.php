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
    <title>Admin Login - Ecommerce Admin</title>
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
</head>

<body class="bg-gray-50">
    <section class="py-16 min-h-screen flex items-center">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-[#1e40af] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-shield text-white text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Admin Login</h2>
                        <p class="text-gray-600">Welcome back! Please login to your admin account.</p>
                    </div>
                    
                    <form action="" method="post" class="space-y-6">
                        <div>
                            <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-[#1e40af]"></i>Username
                            </label>
                            <input type="text" id="username" name="username" 
                                   placeholder="Enter your username" 
                                   autocomplete="off" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-[#1e40af]"></i>Password
                            </label>
                            <input type="password" id="password" name="password" 
                                   placeholder="Enter your password" 
                                   autocomplete="off" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all">
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember" class="w-4 h-4 text-[#1e40af] border-gray-300 rounded focus:ring-[#1e40af]">
                                <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                            </div>
                            <a href="" class="text-sm text-[#1e40af] hover:underline">Forgot password?</a>
                        </div>
                        
                        <button type="submit" name="admin_login" 
                                class="w-full bg-[#1e40af] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-[1.02] shadow-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </button>
                        
                        <p class="text-center text-gray-600">
                            Don't have an account? 
                            <a href="./admin_resgistration.php" class="text-[#1e40af] font-semibold hover:underline">Register</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
<?php
if (isset($_POST['admin_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $select_query = "SELECT * FROM `admin_table` WHERE admin_name='$username'";
    $select_result = mysqli_query($con, $select_query);
    $row_data = mysqli_fetch_assoc($select_result);
    $row_count = mysqli_num_rows($select_result);
    //user check about username & pass
    if ($row_count > 0) {
        if (password_verify($password, $row_data['admin_password'])) {
            $_SESSION['admin_username'] = $username;
            echo "<script>showToast('Login successful!', 'success'); setTimeout(() => { window.location.href = './index.php'; }, 1500);</script>";
        } else {
            echo "<script>showToast('Invalid username or password', 'error');</script>";
        }
    } else {
        echo "<script>showToast('Username does not exist', 'error');</script>";
    }
}
?>
