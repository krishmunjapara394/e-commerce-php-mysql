<?php
// Get admin data
$admin_session_name = $_SESSION['admin_username'];
$select_admin_query = "SELECT * FROM `admin_table` WHERE admin_name='$admin_session_name'";
$select_admin_result = mysqli_query($con, $select_admin_query);
$row_admin_fetch = mysqli_fetch_array($select_admin_result);
$admin_id = $row_admin_fetch['admin_id'];
$admin_name = $row_admin_fetch['admin_name'];
$admin_email = $row_admin_fetch['admin_email'];
$admin_image = $row_admin_fetch['admin_image'];

// Update profile
if(isset($_POST['update_profile'])){
    $update_name = $_POST['admin_name'];
    $update_email = $_POST['admin_email'];
    $update_image = $_FILES['admin_image']['name'] != '' ? $_FILES['admin_image']['name'] : $admin_image;
    $update_image_tmp = $_FILES['admin_image']['tmp_name'];
    
    if($_FILES['admin_image']['name'] != ''){
        move_uploaded_file($update_image_tmp, "./admin_images/$update_image");
    }
    
    $update_query = "UPDATE `admin_table` SET admin_name='$update_name', admin_email='$update_email', admin_image='$update_image' WHERE admin_id=$admin_id";
    $update_result = mysqli_query($con, $update_query);
    if($update_result){
        $_SESSION['admin_username'] = $update_name;
        echo "<script>showToast('Profile updated successfully!', 'success'); setTimeout(() => { window.location.href = 'index.php?account'; }, 1500);</script>";
    } else {
        echo "<script>showToast('Failed to update profile', 'error');</script>";
    }
}

// Change password
if(isset($_POST['change_password'])){
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    $get_admin_query = "SELECT * FROM `admin_table` WHERE admin_id=$admin_id";
    $get_admin_result = mysqli_query($con, $get_admin_query);
    $admin_data = mysqli_fetch_assoc($get_admin_result);
    
    if(password_verify($current_password, $admin_data['admin_password'])){
        if($new_password == $confirm_password){
            if(strlen($new_password) >= 6){
                $hash_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password_query = "UPDATE `admin_table` SET admin_password='$hash_password' WHERE admin_id=$admin_id";
                $update_password_result = mysqli_query($con, $update_password_query);
                if($update_password_result){
                    echo "<script>showToast('Password changed successfully!', 'success');</script>";
                } else {
                    echo "<script>showToast('Failed to change password', 'error');</script>";
                }
            } else {
                echo "<script>showToast('Password must be at least 6 characters', 'error');</script>";
            }
        } else {
            echo "<script>showToast('New passwords do not match', 'error');</script>";
        }
    } else {
        echo "<script>showToast('Current password is incorrect', 'error');</script>";
    }
}

// Get current tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
?>

<div class="max-w-7xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
        <span class="text-[#1e40af] font-bold text-sm uppercase">Admin Account</span>
    </div>
    <h1 class="text-4xl font-bold text-gray-900 mb-12">Account Management</h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sticky top-24">
                <div class="text-center mb-6">
                    <img src="./admin_images/<?php echo htmlspecialchars($admin_image); ?>" 
                         alt="<?php echo htmlspecialchars($admin_name); ?> photo" 
                         class="w-24 h-24 rounded-full object-cover border-4 border-[#1e40af] mx-auto mb-4 shadow-md">
                    <h5 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($admin_name); ?></h5>
                    <p class="text-sm text-gray-500 mt-1">Administrator</p>
                </div>
                
                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <a href="index.php?account&tab=profile" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo $active_tab == 'profile' ? 'bg-[#1e40af] text-white shadow-md' : 'text-gray-700 hover:bg-[#1e40af] hover:text-white'; ?>">
                        <i class="fas fa-user w-5"></i>
                        <span class="font-semibold">Edit Profile</span>
                    </a>
                    <a href="index.php?account&tab=password" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo $active_tab == 'password' ? 'bg-[#1e40af] text-white shadow-md' : 'text-gray-700 hover:bg-[#1e40af] hover:text-white'; ?>">
                        <i class="fas fa-lock w-5"></i>
                        <span class="font-semibold">Change Password</span>
                    </a>
                    <a href="index.php?account&tab=settings" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo $active_tab == 'settings' ? 'bg-[#1e40af] text-white shadow-md' : 'text-gray-700 hover:bg-[#1e40af] hover:text-white'; ?>">
                        <i class="fas fa-cog w-5"></i>
                        <span class="font-semibold">Settings</span>
                    </a>
                    <a href="./admin_logout.php" 
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

                <!-- Profile Tab -->
                <?php if($active_tab == 'profile'): ?>
                <div class="max-w-3xl mx-auto">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                        <h3 class="text-2xl font-bold text-gray-900">Edit Profile</h3>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
                        <!-- Profile Image Section -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-4">Profile Picture</label>
                            <div class="flex items-center gap-6">
                                <div class="relative">
                                    <img src="./admin_images/<?php echo htmlspecialchars($admin_image);?>" 
                                         alt="<?php echo htmlspecialchars($admin_name);?> Photo" 
                                         class="w-32 h-32 rounded-full object-cover border-4 border-[#1e40af] shadow-md"
                                         id="preview-image">
                                </div>
                                <div class="flex-1">
                                    <label for="admin_image" class="block mb-2 text-sm font-medium text-gray-700">
                                        Choose new image
                                    </label>
                                    <input type="file" 
                                           name="admin_image" 
                                           id="admin_image" 
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#1e40af] file:text-white hover:file:bg-[#1e3a8a] file:cursor-pointer">
                                    <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF. Max size 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="admin_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-[#1e40af]"></i>Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="admin_name" 
                                   id="admin_name" 
                                   value="<?php echo htmlspecialchars($admin_name);?>" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="admin_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-[#1e40af]"></i>Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="admin_email" 
                                   id="admin_email" 
                                   value="<?php echo htmlspecialchars($admin_email);?>" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none">
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" 
                                    name="update_profile" 
                                    class="bg-[#1e40af] text-white px-8 py-3 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg">
                                <i class="fas fa-save mr-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <!-- Change Password Tab -->
                <?php if($active_tab == 'password'): ?>
                <div class="max-w-3xl mx-auto">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                        <h3 class="text-2xl font-bold text-gray-900">Change Password</h3>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800">Password Security</p>
                                <p class="text-xs text-yellow-700 mt-1">Make sure your password is strong and at least 6 characters long.</p>
                            </div>
                        </div>
                    </div>

                    <form action="" method="post" class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-[#1e40af]"></i>Current Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   required
                                   placeholder="Enter your current password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none">
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-key mr-2 text-[#1e40af]"></i>New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="new_password" 
                                   id="new_password" 
                                   required
                                   placeholder="Enter new password (min 6 characters)"
                                   minlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none">
                            <p class="mt-2 text-xs text-gray-500">Password must be at least 6 characters long</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-check-circle mr-2 text-[#1e40af]"></i>Confirm New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="confirm_password" 
                                   id="confirm_password" 
                                   required
                                   placeholder="Confirm your new password"
                                   minlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none">
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" 
                                    name="change_password" 
                                    class="bg-[#1e40af] text-white px-8 py-3 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg">
                                <i class="fas fa-key mr-2"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <!-- Settings Tab -->
                <?php if($active_tab == 'settings'): ?>
                <div class="max-w-4xl mx-auto">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
                        <h3 class="text-2xl font-bold text-gray-900">Account Settings</h3>
                    </div>

        <div class="space-y-6">
            <!-- Account Information Card -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-[#1e40af]"></i>Account Information
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin ID</p>
                        <p class="text-base font-semibold text-gray-900">#<?php echo htmlspecialchars($admin_id); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Username</p>
                        <p class="text-base font-semibold text-gray-900"><?php echo htmlspecialchars($admin_name); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="text-base font-semibold text-gray-900"><?php echo htmlspecialchars($admin_email); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Account Type</p>
                        <p class="text-base font-semibold text-gray-900">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <i class="fas fa-shield-alt mr-1"></i>Administrator
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt mr-2 text-[#1e40af]"></i>Quick Actions
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="index.php?account&tab=profile" 
                       class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-[#1e40af] hover:shadow-md transition-all">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-edit text-[#1e40af]"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Edit Profile</p>
                            <p class="text-xs text-gray-500">Update your profile information</p>
                        </div>
                    </a>
                    <a href="index.php?account&tab=password" 
                       class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-[#1e40af] hover:shadow-md transition-all">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-lock text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Change Password</p>
                            <p class="text-xs text-gray-500">Update your account password</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-shield-alt mr-2 text-[#1e40af]"></i>Security
                </h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Two-Factor Authentication</p>
                                <p class="text-xs text-gray-500">Add an extra layer of security</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">Coming Soon</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-history text-blue-600"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Login History</p>
                                <p class="text-xs text-gray-500">View your recent login activity</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">Coming Soon</span>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 rounded-xl p-6 border border-red-200">
                <h4 class="text-lg font-semibold text-red-900 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>Danger Zone
                </h4>
                <div class="bg-white rounded-lg p-4 border border-red-200">
                    <p class="text-sm text-gray-700 mb-3">Once you delete your account, there is no going back. Please be certain.</p>
                    <button type="button" 
                            class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-all text-sm"
                            onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone!')) { alert('Account deletion feature coming soon'); }">
                        <i class="fas fa-trash mr-2"></i>Delete Account
                    </button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview image before upload
    document.getElementById('admin_image')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('preview-image');
                if(previewImg) {
                    previewImg.src = e.target.result;
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Password confirmation validation
    document.getElementById('confirm_password')?.addEventListener('input', function() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        
        if (newPassword !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });
</script>

<style>
    /* Additional styles for account page */
    .admin-tab-item.active {
        background-color: #f1f5f9;
    }
</style>

