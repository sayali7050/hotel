<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration - Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
        }
        .register-header {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .register-body {
            padding: 40px;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #ff9a9e;
            box-shadow: 0 0 0 0.2rem rgba(255, 154, 158, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 154, 158, 0.4);
            color: white;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .login-link {
            color: #ff9a9e;
            text-decoration: none;
        }
        .login-link:hover {
            color: #fecfef;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <i class="fas fa-user-plus fa-3x mb-3"></i>
            <h3>Customer Registration</h3>
            <p class="mb-0">Join our hotel community</p>
        </div>
        <div class="register-body">
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            
            <?php echo form_open('auth/customer_register'); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">
                            <i class="fas fa-user"></i> First Name
                        </label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo set_value('first_name'); ?>" required>
                        <?php echo form_error('first_name', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">
                            <i class="fas fa-user"></i> Last Name
                        </label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo set_value('last_name'); ?>" required>
                        <?php echo form_error('last_name', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-at"></i> Username
                        </label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username'); ?>" required>
                        <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" required>
                        <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone"></i> Phone
                        </label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo set_value('phone'); ?>" required>
                        <?php echo form_error('phone', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <?php echo form_error('confirm_password', '<small class="text-danger">', '</small>'); ?>
                </div>
                
                <div class="mb-4">
                    <label for="address" class="form-label">
                        <i class="fas fa-map-marker-alt"></i> Address
                    </label>
                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your full address"><?php echo set_value('address'); ?></textarea>
                    <?php echo form_error('address', '<small class="text-danger">', '</small>'); ?>
                </div>
                
                <button type="submit" class="btn btn-register w-100">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            <?php echo form_close(); ?>
            
            <div class="text-center mt-4">
                <p class="mb-2">Already have an account?</p>
                <a href="<?php echo base_url('auth/customer_login'); ?>" class="login-link">
                    <i class="fas fa-sign-in-alt"></i> Login Here
                </a>
                <br><br>
                <a href="<?php echo base_url(); ?>" class="text-decoration-none">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 