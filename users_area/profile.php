<?php
include("../includes/connect.php");
include("../functions/common_functions.php");
session_start();
if (!isset($_SESSION['username'])) {
    header('location:user_login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($_SESSION['username']); ?> Profile - A1 Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <?php include('../includes/header.php'); ?>

    <!-- Profile Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                <span class="text-[#1e40af] font-bold text-sm uppercase">My Account</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-12">My Profile</h1>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sticky top-24">
                        <?php
                        $username = $_SESSION['username'];
                        $select_user_img = "SELECT * FROM `user_table` WHERE username='$username'";
                        $select_user_img_result = mysqli_query($con, $select_user_img);
                        $row_user_img = mysqli_fetch_array($select_user_img_result);
                        $userImg = $row_user_img['user_image'];
                        ?>
                        <div class="text-center mb-6">
                            <img src="./user_images/<?php echo $userImg; ?>" 
                                 alt="<?php echo htmlspecialchars($username); ?> photo" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-[#1e40af] mx-auto mb-4">
                            <h5 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($username); ?></h5>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <a href="profile.php" 
                               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo !isset($_GET['edit_account']) && !isset($_GET['my_orders']) && !isset($_GET['delete_account']) ? 'bg-[#1e40af] text-white shadow-md' : 'text-gray-700 hover:bg-[#1e40af] hover:text-white'; ?>">
                                <i class="fas fa-clock w-5"></i>
                                <span class="font-semibold">Pending Orders</span>
                            </a>
                            <a href="profile.php?edit_account" 
                               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo isset($_GET['edit_account']) ? 'bg-[#1e40af] text-white shadow-md' : 'text-gray-700 hover:bg-[#1e40af] hover:text-white'; ?>">
                                <i class="fas fa-edit w-5"></i>
                                <span class="font-semibold">Edit Account</span>
                            </a>
                            <a href="profile.php?my_orders" 
                               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo isset($_GET['my_orders']) ? 'bg-[#1e40af] text-white shadow-md' : 'text-gray-700 hover:bg-[#1e40af] hover:text-white'; ?>">
                                <i class="fas fa-shopping-bag w-5"></i>
                                <span class="font-semibold">My Orders</span>
                            </a>
                            <a href="profile.php?delete_account" 
                               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo isset($_GET['delete_account']) ? 'bg-red-50 text-red-600' : 'text-red-600 hover:bg-red-50'; ?>">
                                <i class="fas fa-trash w-5"></i>
                                <span class="font-semibold">Delete Account</span>
                            </a>
                            <a href="./logout.php" 
                               class="flex items-center gap-3 px-4 py-3 rounded-lg text-orange-600 hover:bg-orange-50 transition-all">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span class="font-semibold">Logout</span>
                            </a>
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <?php
                        get_user_order_details();
                        if (isset($_GET['edit_account'])) {
                            include('./edit_account.php');
                        }
                        if (isset($_GET['my_orders'])) {
                            include('./user_orders.php');
                        }
                        if (isset($_GET['delete_account'])) {
                            include('./delete_account.php');
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('../includes/footer.php'); ?>
</body>

</html>
