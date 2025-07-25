<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .main-content { 
            margin-left: 250px; 
            padding: 20px; 
            min-height: 100vh; 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .table-card { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            border: 1px solid rgba(79, 172, 254, 0.1);
        }
        .visually-hidden-focusable.skip-link {
            position: absolute;
            left: -9999px;
            top: auto;
            width: 1px;
            height: 1px;
            overflow: hidden;
            z-index: 1000;
        }
        .visually-hidden-focusable.skip-link:focus {
            left: 10px;
            top: 10px;
            width: auto;
            height: auto;
            background: #0072ff;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            outline: 2px solid #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
    <?php $this->load->view('admin/includes/header'); ?>
    <!-- Include Sidebar -->
    <?php $this->load->view('admin/includes/sidebar'); ?>

    <!-- Main Content -->
    <main id="main-content" role="main">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users"></i> User Management</h2>
                <a href="<?php echo base_url('admin/add_staff'); ?>" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add Staff</a>
            </div>
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success" role="alert" aria-live="polite">
                    <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert" aria-live="assertive">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            <ul class="nav nav-tabs mb-3" id="userTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers" type="button" role="tab">Customers</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff" type="button" role="tab">Staff</button>
                </li>
            </ul>
            <div class="tab-content" id="userTabsContent">
                <!-- Customers Tab -->
                <div class="tab-pane fade show active" id="customers" role="tabpanel">
                    <div class="table-card p-3 mb-4">
                        <h5><i class="fas fa-user"></i> Customers</h5>
                        <form method="post" action="<?php echo base_url('admin/bulk_action_users'); ?>" id="bulk-action-customers">
                        <div class="d-flex mb-2">
                           <div class="me-2">
                               <select name="bulk_action" class="form-select form-select-sm" required>
                                   <option value="">Bulk Action</option>
                                   <option value="delete">Delete Selected</option>
                                   <option value="active">Set Status: Active</option>
                                   <option value="inactive">Set Status: Inactive</option>
                                   <option value="suspended">Set Status: Suspended</option>
                               </select>
                           </div>
                           <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" role="table" aria-label="Users Management">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all-customers"></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Registered</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($customers): foreach($customers as $user): ?>
                                    <tr>
                                        <td><input type="checkbox" name="user_ids[]" value="<?php echo $user->id; ?>"></td>
                                        <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                                        <td><?php echo $user->email; ?></td>
                                        <td><?php echo $user->phone; ?></td>
                                        <td><span class="badge bg-<?php echo $user->status == 'active' ? 'success' : 'secondary'; ?>"><?php echo ucfirst($user->status); ?></span></td>
                                        <td><?php echo date('M d, Y', strtotime($user->created_at)); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/edit_user/'.$user->id); ?>" class="btn btn-sm btn-warning" aria-label="Edit user <?php echo $user->id ?>"><i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal(<?php echo $user->id; ?>, '<?php echo $user->first_name . ' ' . $user->last_name; ?>', '<?php echo $user->role; ?>')"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="7" class="text-center text-muted">No customers found.</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- Staff Tab -->
                <div class="tab-pane fade" id="staff" role="tabpanel">
                    <div class="table-card p-3 mb-4">
                        <h5><i class="fas fa-user-tie"></i> Staff</h5>
                        <form method="post" action="<?php echo base_url('admin/bulk_action_users'); ?>" id="bulk-action-staff">
                        <div class="d-flex mb-2">
                           <div class="me-2">
                               <select name="bulk_action" class="form-select form-select-sm" required>
                                   <option value="">Bulk Action</option>
                                   <option value="delete">Delete Selected</option>
                                   <option value="active">Set Status: Active</option>
                                   <option value="inactive">Set Status: Inactive</option>
                                   <option value="suspended">Set Status: Suspended</option>
                               </select>
                           </div>
                           <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" role="table" aria-label="Users Management">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all-staff"></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Hired</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($staff): foreach($staff as $user): ?>
                                    <tr>
                                        <td><input type="checkbox" name="user_ids[]" value="<?php echo $user->id; ?>"></td>
                                        <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                                        <td><?php echo $user->email; ?></td>
                                        <td><?php echo $user->phone; ?></td>
                                        <td><?php echo $user->department; ?></td>
                                        <td><?php echo $user->position; ?></td>
                                        <td><span class="badge bg-<?php echo $user->status == 'active' ? 'success' : 'secondary'; ?>"><?php echo ucfirst($user->status); ?></span></td>
                                        <td><?php echo date('M d, Y', strtotime($user->hire_date)); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/edit_user/'.$user->id); ?>" class="btn btn-sm btn-warning" aria-label="Edit user <?php echo $user->id ?>"><i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal(<?php echo $user->id; ?>, '<?php echo $user->first_name . ' ' . $user->last_name; ?>', '<?php echo $user->role; ?>')"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="9" class="text-center text-muted">No staff found.</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
                <div class="alert alert-warning">
                    <strong>Name:</strong> <span id="deleteUserName"></span><br>
                    <strong>Role:</strong> <span id="deleteUserRole"></span>
                </div>
                <p class="text-danger"><small><i class="fas fa-info-circle"></i> This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete User
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showDeleteModal(userId, userName, userRole) {
    // Set the user details in the modal
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteUserRole').textContent = userRole.charAt(0).toUpperCase() + userRole.slice(1);
    
    // Set the delete URL
    document.getElementById('confirmDeleteBtn').href = '<?php echo base_url("admin/delete_user/"); ?>' + userId;
    
    // Show the modal
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Select all checkboxes for customers
document.getElementById('select-all-customers').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('#bulk-action-customers input[name="user_ids[]"]');
    for (const cb of checkboxes) { cb.checked = this.checked; }
});
// Select all checkboxes for staff
document.getElementById('select-all-staff').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('#bulk-action-staff input[name="user_ids[]"]');
    for (const cb of checkboxes) { cb.checked = this.checked; }
});
</script>
</body>
</html> 