<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .profile-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .profile-header { background: linear-gradient(45deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0; padding: 30px; text-align: center; }
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
                            <li><a class="dropdown-item active" href="<?php echo base_url('customer/profile'); ?>">Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('customer/change_password'); ?>">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('logout'); ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main id="main-content" role="main">
    <div class="container py-5">
      <div class="row">
        <div class="col-md-3 mb-4 mb-md-0">
          <?php $this->load->view('customer/sidebar'); ?>
        </div>
        <div class="col-md-9" style="border-left: 1px solid #e0e0e0; min-height: 80vh;">
            <div class="profile-card">
                <div class="profile-header">
                    <i class="fas fa-user-circle fa-4x mb-3"></i>
                    <h3>My Profile</h3>
                    <p class="mb-0">Update your personal information</p>
                </div>
                
                <div class="p-4">
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

                    <?php if(validation_errors()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
                            <ul class="mb-0 mt-2">
                                <?php echo validation_errors('<li>', '</li>'); ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php echo form_open('customer/profile'); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo set_value('first_name', $user->first_name); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo set_value('last_name', $user->last_name); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo set_value('username', $user->username); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo set_value('email', $user->email); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone *</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo set_value('phone', $user->phone); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" 
                                       value="<?php echo set_value('address', $user->address); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-gem"></i> Loyalty Points</label>
                                <input type="text" class="form-control" value="<?php echo isset($user->loyalty_points) ? (int)$user->loyalty_points : 0; ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="preferences" class="form-label"><i class="fas fa-star"></i> Preferences</label>
                                <textarea class="form-control" id="preferences" name="preferences" rows="2" placeholder="E.g. High floor, king bed, vegan meals, etc."><?php echo set_value('preferences', isset($user->preferences) ? $user->preferences : ''); ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo base_url('customer/dashboard'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="<?php echo base_url('customer/export_my_data'); ?>" class="btn btn-outline-info">
                                <i class="fas fa-download"></i> Export My Data
                            </a>
                        </div>
                        <div class="mt-2 text-end">
                            <form action="<?php echo base_url('customer/request_account_deletion'); ?>" method="post" onsubmit="return confirm('Are you sure you want to request deletion of your account? This action cannot be undone.');">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-user-slash"></i> Request Account Deletion
                                </button>
                            </form>
                            <small class="text-danger d-block mt-2">Warning: This will permanently delete your account and all associated data after admin approval.</small>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
      </div>
    </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $this->load->view('footer'); ?> 