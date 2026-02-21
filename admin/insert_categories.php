<?php
if(isset($_POST['insert_cat'])){
    $category_title = $_POST['cat_title'];
    
    // Check if category already exists
    $select_query = "SELECT * FROM `categories` WHERE category_title='$category_title'";
    $result_select = mysqli_query($con, $select_query);
    $number = mysqli_num_rows($result_select);
    
    if($number > 0){
        echo "<script>showToast('This Category is already present in the database', 'warning');</script>";
    } else {
        $insert_query = "INSERT INTO `categories` (category_title) VALUES ('$category_title')";
        $result = mysqli_query($con, $insert_query);
        if($result){
            echo "<script>showToast('Category has been inserted successfully', 'success');</script>";
        }
    }
}
?>

<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="admin-header-section mb-3">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="admin-title-bar"></div>
                    <span class="admin-title-tag">Categories</span>
                </div>
                <h2 class="admin-main-title mb-0">Add New Category</h2>
            </div>
            <p class="text-muted mb-0">Create a new category for your products</p>
        </div>
        <div>
            <a href="index.php?view_categories" class="btn btn-outline-primary">
                <i class="fas fa-list me-2"></i>View Categories
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body p-4">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="categoryTitle" class="form-label">Category Title</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-folder text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="categoryTitle" 
                               name="cat_title" placeholder="Enter category name" required>
                        <div class="invalid-feedback">
                            Please enter a category title.
                        </div>
                    </div>
                    <div class="form-text">
                        Choose a clear and concise name for your category.
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light px-4">
                        <i class="fas fa-undo me-2"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-primary px-4" name="insert_cat">
                        <i class="fas fa-plus me-2"></i>Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Add custom styles -->
<style>
/* Admin Header Section - Matching Home Page Design */
.admin-header-section {
    margin-bottom: 1rem;
}
.admin-title-bar {
    width: 4px;
    height: 36px;
    background-color: #1e40af;
    border-radius: 4px;
}
.admin-title-tag {
    color: #1e40af;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.admin-main-title {
    font-size: 2.25rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    line-height: 1.2;
}
.card {
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
}

.btn-outline-primary {
    border-color: #1e40af;
    color: #1e40af;
}

.btn-outline-primary:hover {
    background-color: #1e40af;
    border-color: #1e40af;
    color: #fff;
}

.btn-primary {
    background-color: #1e40af;
    border-color: #1e40af;
}

.btn-primary:hover {
    background-color: #1e3a8a;
    border-color: #1e3a8a;
}

.input-group-text {
    color: #6c757d;
}

.form-control:focus {
    border-color: #1e40af;
    box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.25);
}

/* Custom form validation styles */
.was-validated .form-control:invalid,
.form-control.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #198754;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
</style>

<!-- Form validation script -->
<script>
(function () {
    'use strict'
    
    // Fetch all forms that need validation
    var forms = document.querySelectorAll('.needs-validation')
    
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>