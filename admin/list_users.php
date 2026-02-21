<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="admin-header-section mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="admin-title-bar"></div>
                        <span class="admin-title-tag">Users</span>
                    </div>
                    <h2 class="admin-main-title mb-0">All Users</h2>
                </div>
                <p class="text-muted mb-0">Manage registered users</p>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-4">ID</th>
                                <th class="border-0">Username</th>
                                <th class="border-0">Email</th>
                                <th class="border-0">Image</th>
                                <th class="border-0">Address</th>
                                <th class="border-0">Mobile</th>
                                <th class="border-0 text-end rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $get_user_query = "SELECT * FROM `user_table`";
                            $get_user_result = mysqli_query($con, $get_user_query);
                            $row_count = mysqli_num_rows($get_user_result);
                            
                            if ($row_count == 0) {
                                echo "<tr><td colspan='7' class='text-center py-5'><i class='fas fa-users fa-3x text-muted mb-3 d-block'></i><p class='text-muted'>No users registered yet</p></td></tr>";
                            } else {
                                $id_number = 1;
                                while ($row_fetch_users = mysqli_fetch_array($get_user_result)) {
                                    $user_id = $row_fetch_users['user_id'];
                                    $username = $row_fetch_users['username'];
                                    $user_email = $row_fetch_users['user_email'];
                                    $user_image = $row_fetch_users['user_image'];
                                    $user_address = $row_fetch_users['user_address'];
                                    $user_mobile = $row_fetch_users['user_mobile'];
                                    echo "
                                    <tr>
                                    <td class='ps-4'>$id_number</td>
                                    <td>
                                        <div class='d-flex align-items-center'>
                                            <div class='bg-light rounded p-2 me-3'>
                                                <i class='fas fa-user text-primary'></i>
                                            </div>
                                            <span class='fw-medium'>$username</span>
                                        </div>
                                    </td>
                                    <td>$user_email</td>
                                    <td>
                                        <img src='../users_area/user_images/$user_image' alt='$username photo' class='img-thumbnail rounded-circle' width='50px' height='50px' style='object-fit: cover;'/>
                                    </td>
                                    <td>$user_address</td>
                                    <td>$user_mobile</td>
                                    <td class='text-end pe-4'>
                                        <button type='button' class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal_$user_id' title='Delete'>
                                            <i class='fas fa-trash-alt'></i>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Delete Modal -->
                                <div class='modal fade' id='deleteModal_$user_id' tabindex='-1' aria-hidden='true' data-bs-backdrop='false' data-bs-keyboard='true'>
                                    <div class='modal-dialog modal-dialog-centered'>
                                        <div class='modal-content'>
                                            <div class='modal-body p-4 text-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <div class='bg-danger bg-opacity-10 rounded-circle p-4 mb-3'>
                                                        <i class='fas fa-exclamation-triangle text-danger fa-2x'></i>
                                                    </div>
                                                    <h5 class='mb-3'>Delete User</h5>
                                                    <p class='text-muted mb-4'>Are you sure you want to delete user \"$username\"? This action cannot be undone.</p>
                                                    <div class='d-flex gap-2'>
                                                        <button type='button' class='btn btn-light px-4' data-bs-dismiss='modal'>Cancel</button>
                                                        <a href='index.php?delete_user=$user_id' class='btn btn-danger px-4'>Delete</a>
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
        .table tbody tr {
            transition: background-color 0.2s;
        }
        .table tbody tr:hover {
            background-color: rgba(30, 64, 175, 0.05);
        }
    </style>
</body>

</html>