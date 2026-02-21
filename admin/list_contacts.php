<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contact Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="admin-header-section mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="admin-title-bar"></div>
                        <span class="admin-title-tag">Messages</span>
                    </div>
                    <h2 class="admin-main-title mb-0">Contact Messages</h2>
                </div>
                <p class="text-muted mb-0">Manage customer inquiries</p>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
            <?php
            // Check if table exists, if not create it
            $check_table = "SHOW TABLES LIKE 'contact_messages'";
            $table_exists = mysqli_query($con, $check_table);
            
            if (mysqli_num_rows($table_exists) == 0) {
                // Create table if it doesn't exist
                $create_table = "CREATE TABLE `contact_messages` (
                    `contact_id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(100) NOT NULL,
                    `email` varchar(200) NOT NULL,
                    `phone` varchar(20) DEFAULT NULL,
                    `subject` varchar(255) NOT NULL,
                    `message` text NOT NULL,
                    `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
                    `status` varchar(20) DEFAULT 'unread',
                    PRIMARY KEY (`contact_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
                
                mysqli_query($con, $create_table);
            }
            
            $get_contact_query = "SELECT * FROM `contact_messages` ORDER BY submission_date DESC";
            $get_contact_result = mysqli_query($con, $get_contact_query);
            $row_count = mysqli_num_rows($get_contact_result);
            ?>
            
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-4">ID</th>
                                <th class="border-0">Name</th>
                                <th class="border-0">Email</th>
                                <th class="border-0">Phone</th>
                                <th class="border-0">Subject</th>
                                <th class="border-0">Message</th>
                                <th class="border-0">Date</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-end rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($row_count == 0) {
                                echo "<tr><td colspan='9' class='text-center py-5'><i class='fas fa-envelope fa-3x text-muted mb-3 d-block'></i><p class='text-muted'>No contact messages yet</p></td></tr>";
                            } else {
                        while ($row_fetch_contact = mysqli_fetch_array($get_contact_result)) {
                            $contact_id = $row_fetch_contact['contact_id'];
                            $name = $row_fetch_contact['name'];
                            $email = $row_fetch_contact['email'];
                            $phone = $row_fetch_contact['phone'] ? $row_fetch_contact['phone'] : 'N/A';
                            $subject = $row_fetch_contact['subject'];
                            $message = $row_fetch_contact['message'];
                            $submission_date = $row_fetch_contact['submission_date'];
                            $status = $row_fetch_contact['status'];
                            
                            // Format date
                            $formatted_date = date('M d, Y H:i', strtotime($submission_date));
                            
                            // Truncate message for table display
                            $message_preview = strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message;
                            
                            // Status badge color
                            $status_badge = $status == 'read' ? 'success' : 'warning';
                            $status_text = ucfirst($status);
                            
                            echo "
                            <tr>
                            <td>$contact_id</td>
                            <td>$name</td>
                            <td><a href='mailto:$email'>$email</a></td>
                            <td>$phone</td>
                            <td>$subject</td>
                            <td>
                                <span data-bs-toggle='tooltip' data-bs-placement='top' title='" . htmlspecialchars($message, ENT_QUOTES) . "'>$message_preview</span>
                            </td>
                            <td>$formatted_date</td>
                            <td>
                                <span class='badge bg-$status_badge'>$status_text</span>
                            </td>
                            <td>
                                <div class='d-flex gap-2 justify-content-center'>
                                    <button type='button' class='btn btn-sm btn-info' data-bs-toggle='modal' data-bs-target='#viewModal_$contact_id'>
                                        View
                                    </button>
                                    <a href='index.php?mark_read_contact=$contact_id' class='btn btn-sm btn-success'>
                                        Mark Read
                                    </a>
                                    <a href='index.php?delete_contact=$contact_id' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this contact message?\");'>
                                        Delete
                                    </a>
                                </div>
                                
                                <!-- View Modal -->
                                <div class='modal fade' id='viewModal_$contact_id' tabindex='-1' aria-labelledby='viewModal_$contact_id.Label' aria-hidden='true' data-bs-backdrop='false' data-bs-keyboard='true'>
                                    <div class='modal-dialog modal-dialog-centered modal-lg'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='viewModal_$contact_id.Label'>Contact Message Details</h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                <div class='row g-3'>
                                                    <div class='col-md-6'>
                                                        <strong>Name:</strong>
                                                        <p>$name</p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <strong>Email:</strong>
                                                        <p><a href='mailto:$email'>$email</a></p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <strong>Phone:</strong>
                                                        <p>$phone</p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <strong>Subject:</strong>
                                                        <p>$subject</p>
                                                    </div>
                                                    <div class='col-12'>
                                                        <strong>Message:</strong>
                                                        <p style='white-space: pre-wrap; background: #f8f9fa; padding: 15px; border-radius: 5px;'>" . htmlspecialchars($message) . "</p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <strong>Date:</strong>
                                                        <p>$formatted_date</p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <strong>Status:</strong>
                                                        <p><span class='badge bg-$status_badge'>$status_text</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='modal-footer'>
                                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                <a href='index.php?mark_read_contact=$contact_id' class='btn btn-success'>Mark as Read</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                            ";
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
        .btn-primary {
            background-color: #1e40af;
            border-color: #1e40af;
        }
        .btn-primary:hover {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
        }
        .table tbody tr {
            transition: background-color 0.2s;
        }
        .table tbody tr:hover {
            background-color: rgba(30, 64, 175, 0.05);
        }
    </style>
    
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>

