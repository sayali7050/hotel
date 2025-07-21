<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .password-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .password-header { background: linear-gradient(45deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0; padding: 30px; text-align: center; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?php echo base_url('customer/dashboard'); ?>">
                <i class="fas fa-hotel"></i> Hotel Booking
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('customer/dashboard'); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('customer/rooms'); ?>">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('customer/my_bookings'); ?>">My Bookings</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo $this->session->userdata('username'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo base_url('customer/profile'); ?>">Profile</a></li>
                            <li><a class="dropdown-item active" href="<?php echo base_url('customer/change_password'); ?>">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('logout'); ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="password-card">
                    <div class="password-header">
                        <i class="fas fa-lock fa-4x mb-3"></i>
                        <h3>Change Password</h3>
                        <p class="mb-0">Update your account password</p>
                    </div>
                    
                    <div class="p-4">
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(validation_errors()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
                                <ul class="mb-0 mt-2">
                                    <?php echo validation_errors('<li>', '</li>'); ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php echo form_open('customer/change_password'); ?>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password *</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <small class="text-muted">Enter your current password to verify your identity</small>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password *</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                <small class="text-muted">Minimum 6 characters</small>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm New Password *</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <small class="text-muted">Re-enter your new password</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo base_url('customer/profile'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Profile
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key"></i> Change Password
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 