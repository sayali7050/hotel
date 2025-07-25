<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit User</title>
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
        .form-card { 
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
                <h2><i class="fas fa-user-edit"></i> Edit User</h2>
                <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Users</a>
            </div>
            <div class="form-card p-4">
                <?php if(validation_errors()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
                        <ul class="mb-0 mt-2">
                            <?php echo validation_errors('<li>', '</li>'); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success" role="alert" aria-live="polite">
                        <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger" role="alert" aria-live="assertive">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>
                <?php echo form_open('admin/edit_user/'.$user->id, ['aria-label' => 'Edit user details']); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo set_value('first_name', $user->first_name); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo set_value('last_name', $user->last_name); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email', $user->email); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo set_value('phone', $user->phone); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo set_value('address', $user->address); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" disabled>
                                <option value="customer" <?php echo $user->role == 'customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="staff" <?php echo $user->role == 'staff' ? 'selected' : ''; ?>>Staff</option>
                                <option value="admin" <?php echo $user->role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                            <small class="text-muted">Role cannot be changed</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" <?php echo $user->status == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $user->status == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                <option value="suspended" <?php echo $user->status == 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                            </select>
                        </div>
                    </div>
                    <?php if($user->role == 'admin'): ?>
                    <?php $CI =& get_instance(); $perms = $CI->User_model->get_permissions($user->id); ?>
                    <div class="mb-3">
                        <label class="form-label">Admin Permissions</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[manage_bookings]" id="perm_bookings" value="1" <?php if(!empty($perms['manage_bookings'])) echo 'checked'; ?>>
                            <label class="form-check-label" for="perm_bookings">Manage Bookings</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[manage_rooms]" id="perm_rooms" value="1" <?php if(!empty($perms['manage_rooms'])) echo 'checked'; ?>>
                            <label class="form-check-label" for="perm_rooms">Manage Rooms</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[manage_users]" id="perm_users" value="1" <?php if(!empty($perms['manage_users'])) echo 'checked'; ?>>
                            <label class="form-check-label" for="perm_users">Manage Users</label>
                        </div>
                        <small class="text-muted">Uncheck to restrict this admin's access to specific modules.</small>
                    </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?php echo $user->username; ?>" disabled>
                            <small class="text-muted">Username cannot be changed</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Registered Date</label>
                            <input type="text" class="form-control" value="<?php echo date('M d, Y', strtotime($user->created_at)); ?>" disabled>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update User</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 