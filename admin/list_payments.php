<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="admin-header-section mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="admin-title-bar"></div>
                        <span class="admin-title-tag">Payments</span>
                    </div>
                    <h2 class="admin-main-title mb-0">All Payments</h2>
                </div>
                <p class="text-muted mb-0">View all payment transactions</p>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-4">ID</th>
                                <th class="border-0">Order ID</th>
                                <th class="border-0">Invoice Number</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Payment Method</th>
                                <th class="border-0">Payment Date</th>
                                <th class="border-0 text-end rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $get_payment_query = "SELECT * FROM `user_payments`";
                            $get_payment_result = mysqli_query($con, $get_payment_query);
                            $row_count = mysqli_num_rows($get_payment_result);
                            
                            if ($row_count == 0) {
                                echo "<tr><td colspan='7' class='text-center py-5'><i class='fas fa-credit-card fa-3x text-muted mb-3 d-block'></i><p class='text-muted'>No payments recorded yet</p></td></tr>";
                            } else {
                        $id_number = 1;
                        while ($row_fetch_payments = mysqli_fetch_array($get_payment_result)) {
                            $payment_id = $row_fetch_payments['payment_id'];
                            $order_id = $row_fetch_payments['order_id'];
                            $invoice_number = $row_fetch_payments['invoice_number'];
                            $amount_due = $row_fetch_payments['amount'];
                            $payment_method = $row_fetch_payments['payment_method'];
                            $payment_date = $row_fetch_payments['payment_date'];
                            echo "
                            <tr>
                            <td class='ps-4'>$id_number</td>
                            <td><span class='badge bg-info'>#$order_id</span></td>
                            <td>$invoice_number</td>
                            <td><span class='fw-bold text-2'>\$$amount_due</span></td>
                            <td><span class='badge bg-primary'>$payment_method</span></td>
                            <td>" . date('M d, Y', strtotime($payment_date)) . "</td>
                            <td class='text-end pe-4'>
                                <button type='button' class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal_$payment_id' title='Delete'>
                                    <i class='fas fa-trash-alt'></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Delete Modal -->
                        <div class='modal fade' id='deleteModal_$payment_id' tabindex='-1' aria-hidden='true' data-bs-backdrop='false' data-bs-keyboard='true'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-body p-4 text-center'>
                                        <div class='d-flex flex-column align-items-center'>
                                            <div class='bg-danger bg-opacity-10 rounded-circle p-4 mb-3'>
                                                <i class='fas fa-exclamation-triangle text-danger fa-2x'></i>
                                            </div>
                                            <h5 class='mb-3'>Delete Payment</h5>
                                            <p class='text-muted mb-4'>Are you sure you want to delete payment #$id_number? This action cannot be undone.</p>
                                            <div class='d-flex gap-2'>
                                                <button type='button' class='btn btn-light px-4' data-bs-dismiss='modal'>Cancel</button>
                                                <a href='index.php?delete_payment=$payment_id' class='btn btn-danger px-4'>Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            ";

                            $id_number++;
                        }
                    }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
        .btn-outline-danger {
            border-color: #ef4444;
            color: #ef4444;
        }
        .btn-outline-danger:hover {
            background-color: #ef4444;
            border-color: #ef4444;
            color: #fff;
        }
        .text-primary {
            color: #1e40af !important;
        }
        .bg-primary {
            background-color: #1e40af !important;
        }
        .table tbody tr {
            transition: background-color 0.2s;
        }
        .table tbody tr:hover {
            background-color: rgba(30, 64, 175, 0.05);
        }
    </style>
</body>

</html>