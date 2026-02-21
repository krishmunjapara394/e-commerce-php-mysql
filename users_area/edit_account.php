<?php
if(isset($_GET['edit_account'])){
    $user_session_name = $_SESSION['username'];
    $select_user_query = "SELECT * FROM `user_table` WHERE username='$user_session_name'";
    $select_user_result = mysqli_query($con,$select_user_query);
    $row_user_fetch = mysqli_fetch_array($select_user_result);
    $user_id = $row_user_fetch['user_id'];
    $username = $row_user_fetch['username'];
    $user_email = $row_user_fetch['user_email'];
    $user_address = $row_user_fetch['user_address'];
    $user_mobile = $row_user_fetch['user_mobile'];
    $user_image = $row_user_fetch['user_image'];
    
    
}
// Update data
if(isset($_POST['user_update'])){
    $update_id = $user_id;
    $update_user = $_POST['user_username'];
    $update_email = $_POST['user_email'];
    $update_address = $_POST['user_address'];
    $update_mobile = $_POST['user_mobile'];
    $update_image = $_FILES['user_image']['name'] != ''? $_FILES['user_image']['name'] : $user_image;
    $update_image_tmp = $_FILES['user_image']['tmp_name'];
    move_uploaded_file($update_image_tmp,"./user_images/$update_image");
    
    // update query 
    $update_query = "UPDATE `user_table` SET username='$update_user',user_email='$update_email',user_image='$update_image',user_address='$update_address',user_mobile='$update_mobile' WHERE user_id=$update_id";
    $update_result = mysqli_query($con,$update_query);
    if($update_result){
        $_SESSION['username'] = $update_user;
        echo "<script>showToast('Account updated successfully!', 'success'); setTimeout(() => { window.location.href = 'profile.php?edit_account'; }, 1500);</script>";
    }
}
?>
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-1 h-9 bg-[#1e40af] rounded"></div>
        <h2 class="text-3xl font-bold text-gray-900">Edit Account Details</h2>
    </div>
    
    <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
        <!-- Profile Image Section -->
        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
            <label class="block text-sm font-semibold text-gray-700 mb-4">Profile Picture</label>
            <div class="flex items-center gap-6">
                <div class="relative">
                    <img src="./user_images/<?php echo htmlspecialchars($user_image);?>" 
                         alt="<?php echo htmlspecialchars($username);?> Photo" 
                         class="w-24 h-24 rounded-full object-cover border-4 border-[#1e40af] shadow-md"
                         id="preview-image">
                </div>
                <div class="flex-1">
                    <label for="user_image" class="block mb-2 text-sm font-medium text-gray-700">
                        Choose new image
                    </label>
                    <input type="file" 
                           name="user_image" 
                           id="user_image" 
                           accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#1e40af] file:text-white hover:file:bg-[#1e3a8a] file:cursor-pointer">
                    <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF. Max size 2MB</p>
                </div>
            </div>
        </div>

        <!-- Username -->
        <div>
            <label for="user_username" class="block text-sm font-semibold text-gray-700 mb-2">
                Username <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="user_username" 
                   id="user_username" 
                   value="<?php echo htmlspecialchars($username);?>" 
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none">
        </div>

        <!-- Email -->
        <div>
            <label for="user_email" class="block text-sm font-semibold text-gray-700 mb-2">
                Email Address <span class="text-red-500">*</span>
            </label>
            <input type="email" 
                   name="user_email" 
                   id="user_email" 
                   value="<?php echo htmlspecialchars($user_email);?>" 
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none">
        </div>

        <!-- Address -->
        <div>
            <label for="user_address" class="block text-sm font-semibold text-gray-700 mb-2">
                Address
            </label>
            <input type="text" 
                   name="user_address" 
                   id="user_address" 
                   value="<?php echo htmlspecialchars($user_address);?>" 
                   placeholder="Enter your full address"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none">
        </div>

        <!-- Mobile -->
        <div>
            <label for="user_mobile" class="block text-sm font-semibold text-gray-700 mb-2">
                Mobile Number
            </label>
            <input type="text" 
                   name="user_mobile" 
                   id="user_mobile" 
                   value="<?php echo htmlspecialchars($user_mobile);?>" 
                   placeholder="Enter your mobile number"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1e40af] focus:border-transparent transition-all outline-none">
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4 pt-4">
            <button type="submit" 
                    name="user_update" 
                    id="user_update"
                    class="flex-1 bg-[#1e40af] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#1e3a8a] transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Account
            </button>
            <a href="profile.php" 
               class="px-8 py-4 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // Preview image before upload
    document.getElementById('user_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>