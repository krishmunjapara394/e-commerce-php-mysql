<?php
    $order_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `user_orders`"));
    $pending_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `user_orders` WHERE order_status='pending'"));
    $completed_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `user_orders` WHERE order_status='Complete'"));
?>
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="admin-header-section mb-3">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="admin-title-bar"></div>
                    <span class="admin-title-tag">Orders</span>
                </div>
                <h2 class="admin-main-title mb-0">All Orders</h2>
            </div>
            <p class="text-muted mb-0">Manage customer orders</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="order-stat-card order-stat-card-primary">
                <div class="order-stat-icon-wrapper order-stat-icon-primary">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="order-stat-content">
                    <h3 class="order-stat-number"><?php echo $order_count; ?></h3>
                    <p class="order-stat-label">Total Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="order-stat-card order-stat-card-warning">
                <div class="order-stat-icon-wrapper order-stat-icon-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="order-stat-content">
                    <h3 class="order-stat-number"><?php echo $pending_count; ?></h3>
                    <p class="order-stat-label">Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="order-stat-card order-stat-card-success">
                <div class="order-stat-icon-wrapper order-stat-icon-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="order-stat-content">
                    <h3 class="order-stat-number"><?php echo $completed_count; ?></h3>
                    <p class="order-stat-label">Completed Orders</p>
                </div>
            </div>
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
                        <input type="text" class="form-control border-start-0" id="searchOrder" placeholder="Search orders...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="filterStatus">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="Complete">Completed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start ps-4">Order ID</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Amount</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Date</th>
                            <th class="border-0 text-end rounded-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $get_orders_query = "SELECT * FROM `user_orders` ORDER BY order_date DESC";
                        $get_orders_result = mysqli_query($con, $get_orders_query);
                        while($row = mysqli_fetch_array($get_orders_result)) {
                            $order_id = $row['order_id'];
                            $user_id = $row['user_id'];
                            $amount = $row['amount_due'];
                            $order_status = $row['order_status'];
                            $order_date = $row['order_date'];
                            
                            // Get user details
                            $get_user = mysqli_query($con, "SELECT * FROM `user_table` WHERE user_id=$user_id");
                            $user_row = mysqli_fetch_array($get_user);
                            $username = $user_row['username'];
                        ?>
                        <tr>
                            <td class="ps-4">#<?php echo $order_id; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <span class="fw-medium"><?php echo $username; ?></span>
                                </div>
                            </td>
                            <td>$<?php echo $amount; ?></td>
                            <td>
                                <?php if($order_status == 'pending'): ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Completed</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($order_date)); ?></td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal_<?php echo $order_id; ?>" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                                        </button>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal_<?php echo $order_id; ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body p-4 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-4 mb-3">
                                                <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                                    </div>
                                            <h5 class="mb-3">Delete Order</h5>
                                            <p class="text-muted mb-4">Are you sure you want to delete order #<?php echo $order_id; ?>? This action cannot be undone.</p>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                                                <a href="index.php?delete_order=<?php echo $order_id; ?>" class="btn btn-danger px-4">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
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

/* Order Stats Cards - Modern Design */
.order-stat-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}
.order-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #1e40af, #3b82f6);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}
.order-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #1e40af;
}
.order-stat-card:hover::before {
    transform: scaleX(1);
}
.order-stat-card-primary:hover {
    border-color: #1e40af;
}
.order-stat-card-warning:hover {
    border-color: #f59e0b;
}
.order-stat-card-success:hover {
    border-color: #10b981;
}
.order-stat-icon-wrapper {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.order-stat-icon-primary {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
}
.order-stat-icon-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}
.order-stat-icon-success {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}
.order-stat-icon-wrapper i {
    font-size: 28px;
    color: #ffffff;
}
.order-stat-card:hover .order-stat-icon-wrapper {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}
.order-stat-content {
    flex: 1;
}
.order-stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 4px 0;
    line-height: 1.2;
}
.order-stat-label {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

.bg-primary {
    background-color: #1e40af !important;
}

.input-group-text {
    color: #6c757d;
}

.form-control:focus,
.form-select:focus {
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

/* Badge styles */
.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}
</style>

<!-- Add search and filter functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchOrder');
    const statusFilter = document.getElementById('filterStatus');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        tableRows.forEach(row => {
            const orderID = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const customer = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const status = row.querySelector('.badge').textContent.toLowerCase();

            const matchesSearch = orderID.includes(searchText) || customer.includes(searchText);
            const matchesStatus = !statusValue || status === statusValue;

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>