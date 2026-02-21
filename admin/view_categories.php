<?php
    // Function to generate edit category form
    function getEditCategoryForm($category_id, $con) {
        $get_data_query = "SELECT * FROM `categories` WHERE category_id = $category_id";
        $get_data_result = mysqli_query($con, $get_data_query);
        $row_fetch_data = mysqli_fetch_array($get_data_result);
        $category_title = $row_fetch_data['category_title'];
        
        return "
        <form id='editCategoryForm_$category_id' method='post' enctype='multipart/form-data'>
            <input type='hidden' name='category_id' value='$category_id'>
            <div class='row g-3'>
                <div class='col-12'>
                    <label for='category_title_$category_id' class='form-label fw-semibold'>
                        Category Title <span class='text-danger'>*</span>
                    </label>
                    <input type='text' 
                           class='form-control' 
                           id='category_title_$category_id' 
                           name='category_title' 
                           value='" . htmlspecialchars($category_title) . "' 
                           required 
                           placeholder='Enter category title'>
                </div>
                <div class='col-12 mt-4'>
                    <div class='d-flex justify-content-end gap-2'>
                        <button type='button' class='btn btn-light px-4' data-bs-dismiss='modal'>Cancel</button>
                        <button type='submit' class='btn btn-primary px-4' name='update_category_ajax'>
                            <i class='fas fa-save me-2'></i>Update Category
                        </button>
                    </div>
                </div>
            </div>
        </form>
        ";
    }
    
    $category_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `categories`"));
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
                <h2 class="admin-main-title mb-0">All Categories</h2>
            </div>
            <p class="text-muted mb-0">Manage your store categories</p>
        </div>
        <div>
            <a href="index.php?insert_category" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Category
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchCategory" placeholder="Search categories...">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-md-end align-items-center">
                        <span class="text-muted me-3">Total Categories: <?php echo $category_count; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start ps-4" style="width: 80px;">ID</th>
                            <th class="border-0">Category Name</th>
                            <th class="border-0 text-end rounded-end pe-4" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $get_category_query = "SELECT * FROM `categories` ORDER BY category_title ASC";
                        $get_category_result = mysqli_query($con, $get_category_query);
                        $id_number = 1;
                        while($row = mysqli_fetch_array($get_category_result)) {
                            $category_id = $row['category_id'];
                            $category_title = $row['category_title'];
                        ?>
                        <tr>
                            <td class="ps-4"><?php echo $id_number; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="fas fa-folder text-primary"></i>
                                    </div>
                                    <span class="fw-medium"><?php echo $category_title; ?></span>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-primary me-2 edit-category-btn" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo $category_id; ?>" data-category-id="<?php echo $category_id; ?>" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal_<?php echo $category_id; ?>" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal_<?php echo $category_id; ?>" tabindex="-1" aria-labelledby="editModalLabel_<?php echo $category_id; ?>" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header border-0 pb-2 bg-gradient-to-r from-[#1e40af] to-[#3b82f6] text-white">
                                        <div class="d-flex align-items-center w-100">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <i class="fas fa-edit me-2"></i>
                                                <h5 class="modal-title mb-0" id="editModalLabel_<?php echo $category_id; ?>">EDIT CATEGORY</h5>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                    </div>
                                    <div class="modal-body p-4" id="editFormContainer_<?php echo $category_id; ?>">
                                        <?php echo getEditCategoryForm($category_id, $con); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal_<?php echo $category_id; ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body p-4 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-4 mb-3">
                                                <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                            </div>
                                            <h5 class="mb-3">Delete Category</h5>
                                            <p class="text-muted mb-4">Are you sure you want to delete "<?php echo $category_title; ?>"? This action cannot be undone.</p>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                                                <a href="index.php?delete_category=<?php echo $category_id; ?>" class="btn btn-danger px-4">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            $id_number++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
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

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
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

.text-primary {
    color: #1e40af !important;
}

.input-group-text {
    color: #6c757d;
}

.form-control:focus {
    border-color: #1e40af;
    box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.25);
}

/* Add smooth hover effect on table rows */
.table tbody tr {
    transition: background-color 0.2s;
}

.table tbody tr:hover {
    background-color: rgba(30, 64, 175, 0.05);
}

/* Style for action buttons */
.btn-sm {
    padding: 0.25rem 0.5rem;
}

/* Modal animation */
.modal.fade .modal-dialog {
    transition: transform 0.2s ease-out;
}

.modal.show .modal-dialog {
    transform: none;
}

/* Modal Styling - Match Product Edit Modal */
.modal {
    z-index: 10060 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
    padding: 0 !important;
}

.modal.show {
    z-index: 10060 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.modal-dialog {
    z-index: 10070 !important;
    position: relative !important;
    margin: 2rem auto !important;
    max-width: 90% !important;
    max-height: 90vh !important;
    pointer-events: auto !important;
}

.modal-content {
    z-index: 10080 !important;
    position: relative !important;
    pointer-events: auto !important;
}

.modal-header,
.modal-body {
    z-index: 10090 !important;
    position: relative !important;
    pointer-events: auto !important;
}

.modal button,
.modal input,
.modal select,
.modal textarea,
.modal a,
.modal label,
.modal .btn,
.modal .form-control,
.modal .form-select,
.modal .btn-close {
    pointer-events: auto !important;
    position: relative !important;
    z-index: 10100 !important;
}

.modal-body {
    max-height: calc(90vh - 200px);
    overflow-y: auto;
}

.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #1e40af;
    border-radius: 10px;
}

.modal-body .form-control {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 0.75rem 1rem;
}

.modal-body .form-control:focus {
    border-color: #1e40af;
    box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.15);
}
</style>

<!-- Add search functionality -->
<script>
document.getElementById('searchCategory').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let categoryName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        if (categoryName.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// AJAX form submission for edit category - Using event delegation
document.addEventListener('DOMContentLoaded', function() {
    // Use event delegation on document body to catch all form submissions
    document.body.addEventListener('submit', function(e) {
        const form = e.target;
        // Check if this is a category edit form
        if(form && form.id && form.id.startsWith('editCategoryForm_')) {
            e.preventDefault();
            
            const formData = new FormData(form);
            formData.append('update_category_ajax', '1');
            
            // Try to find the submit button - check event target first, then form
            let submitBtn = null;
            
            // First, try to get the button from the submit event (if clicked directly)
            if(e.submitter && e.submitter.tagName === 'BUTTON') {
                submitBtn = e.submitter;
            }
            
            // If not found, try querying the form
            if(!submitBtn) {
                submitBtn = form.querySelector('button[type="submit"]');
            }
            if(!submitBtn) {
                submitBtn = form.querySelector('button[name="update_category_ajax"]');
            }
            if(!submitBtn) {
                submitBtn = form.querySelector('button.btn-primary');
            }
            // Try to find any button in the form that's not the cancel button
            if(!submitBtn) {
                const allButtons = form.querySelectorAll('button');
                for(let btn of allButtons) {
                    if(btn.type !== 'button' && !btn.hasAttribute('data-bs-dismiss')) {
                        submitBtn = btn;
                        break;
                    }
                }
            }
            
            const categoryId = form.id.split('_')[1];
            const modal = document.getElementById('editModal_' + categoryId);
            
            // Store original button text if button is found
            let originalBtnText = '';
            if(submitBtn) {
                originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            } else {
                console.warn('Submit button not found in form:', form.id, '- continuing without button update');
            }

            fetch('update_category_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Close modal
                    if(modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if(bsModal) bsModal.hide();
                    }
                    
                    if(typeof showToast !== 'undefined') {
                        showToast(data.message, 'success');
                    } else {
                        alert(data.message);
                    }
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    if(typeof showToast !== 'undefined') {
                        showToast(data.message || 'Failed to update category', 'error');
                    } else {
                        alert(data.message || 'Failed to update category');
                    }
                    if(submitBtn && originalBtnText) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if(typeof showToast !== 'undefined') {
                    showToast('An error occurred. Please try again.', 'error');
                } else {
                    alert('An error occurred. Please try again.');
                }
                if(submitBtn && originalBtnText) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        }
    });
});
</script>