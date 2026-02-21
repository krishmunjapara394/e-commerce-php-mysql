<?php
    // Function to generate edit brand form
    function getEditBrandForm($brand_id, $con) {
        $get_data_query = "SELECT * FROM `brands` WHERE brand_id = $brand_id";
        $get_data_result = mysqli_query($con, $get_data_query);
        $row_fetch_data = mysqli_fetch_array($get_data_result);
        $brand_title = $row_fetch_data['brand_title'];
        
        return "
        <form id='editBrandForm_$brand_id' method='post' enctype='multipart/form-data'>
            <input type='hidden' name='brand_id' value='$brand_id'>
            <div class='row g-3'>
                <div class='col-12'>
                    <label for='brand_title_$brand_id' class='form-label fw-semibold'>
                        Brand Title <span class='text-danger'>*</span>
                    </label>
                    <input type='text' 
                           class='form-control' 
                           id='brand_title_$brand_id' 
                           name='brand_title' 
                           value='" . htmlspecialchars($brand_title) . "' 
                           required 
                           placeholder='Enter brand title'>
                </div>
                <div class='col-12 mt-4'>
                    <div class='d-flex justify-content-end gap-2'>
                        <button type='button' class='btn btn-light px-4' data-bs-dismiss='modal'>Cancel</button>
                        <button type='submit' class='btn btn-primary px-4' name='update_brand_ajax'>
                            <i class='fas fa-save me-2'></i>Update Brand
                        </button>
                    </div>
                </div>
            </div>
        </form>
        ";
    }
    
    $brand_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `brands`"));
?>
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="admin-header-section mb-3">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="admin-title-bar"></div>
                    <span class="admin-title-tag">Brands</span>
                </div>
                <h2 class="admin-main-title mb-0">All Brands</h2>
            </div>
            <p class="text-muted mb-0">Manage your store brands</p>
        </div>
        <div>
            <a href="index.php?insert_brand" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Brand
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
                        <input type="text" class="form-control border-start-0" id="searchBrand" placeholder="Search brands...">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-md-end align-items-center">
                        <span class="text-muted me-3">Total Brands: <?php echo $brand_count; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Brands Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start ps-4" style="width: 80px;">ID</th>
                            <th class="border-0">Brand Name</th>
                            <th class="border-0 text-end rounded-end pe-4" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $get_brands_query = "SELECT * FROM `brands` ORDER BY brand_title ASC";
                        $get_brands_result = mysqli_query($con, $get_brands_query);
                        $id_number = 1;
                        while($row = mysqli_fetch_array($get_brands_result)) {
                            $brand_id = $row['brand_id'];
                            $brand_title = $row['brand_title'];
                        ?>
                        <tr>
                            <td class="ps-4"><?php echo $id_number; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="fas fa-tag text-primary"></i>
                                    </div>
                                    <span class="fw-medium"><?php echo $brand_title; ?></span>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-primary me-2 edit-brand-btn" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo $brand_id; ?>" data-brand-id="<?php echo $brand_id; ?>" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal_<?php echo $brand_id; ?>" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal_<?php echo $brand_id; ?>" tabindex="-1" aria-labelledby="editModalLabel_<?php echo $brand_id; ?>" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header border-0 pb-2 bg-gradient-to-r from-[#1e40af] to-[#3b82f6] text-white">
                                        <div class="d-flex align-items-center w-100">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <i class="fas fa-edit me-2"></i>
                                                <h5 class="modal-title mb-0" id="editModalLabel_<?php echo $brand_id; ?>">EDIT BRAND</h5>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                    </div>
                                    <div class="modal-body p-4" id="editFormContainer_<?php echo $brand_id; ?>">
                                        <?php echo getEditBrandForm($brand_id, $con); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal_<?php echo $brand_id; ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body p-4 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-4 mb-3">
                                                <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                            </div>
                                            <h5 class="mb-3">Delete Brand</h5>
                                            <p class="text-muted mb-4">Are you sure you want to delete "<?php echo $brand_title; ?>"? This action cannot be undone.</p>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                                                <a href="index.php?delete_brand=<?php echo $brand_id; ?>" class="btn btn-danger px-4">Delete</a>
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
document.getElementById('searchBrand').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let brandName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        if (brandName.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// AJAX form submission for edit brand - Using event delegation
document.addEventListener('DOMContentLoaded', function() {
    // Use event delegation on document body to catch all form submissions
    document.body.addEventListener('submit', function(e) {
        const form = e.target;
        // Check if this is a brand edit form
        if(form && form.id && form.id.startsWith('editBrandForm_')) {
            e.preventDefault();
            
            const formData = new FormData(form);
            formData.append('update_brand_ajax', '1');
            
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
                submitBtn = form.querySelector('button[name="update_brand_ajax"]');
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
            
            const brandId = form.id.split('_')[1];
            const modal = document.getElementById('editModal_' + brandId);
            
            // Store original button text if button is found
            let originalBtnText = '';
            if(submitBtn) {
                originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            } else {
                console.warn('Submit button not found in form:', form.id, '- continuing without button update');
            }

            fetch('update_brand_ajax.php', {
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
                        showToast(data.message || 'Failed to update brand', 'error');
                    } else {
                        alert(data.message || 'Failed to update brand');
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