<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Staff</title>
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
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php $this->load->view('admin/includes/sidebar'); ?>

    <!-- Main Content -->
    <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-user-plus"></i> Add New Staff</h2>
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
                <?php echo form_open('admin/add_staff'); ?>
                    <h5 class="mb-3"><i class="fas fa-user"></i> Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo set_value('first_name'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo set_value('last_name'); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo set_value('phone'); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo set_value('address'); ?></textarea>
                    </div>
                    
                    <hr class="my-4">
                    <h5 class="mb-3"><i class="fas fa-briefcase"></i> Employment Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department *</label>
                            <select class="form-control" id="department" name="department" required>
                                <option value="">Select Department</option>
                                <option value="Front Desk" <?php echo set_select('department', 'Front Desk'); ?>>Front Desk</option>
                                <option value="Housekeeping" <?php echo set_select('department', 'Housekeeping'); ?>>Housekeeping</option>
                                <option value="Maintenance" <?php echo set_select('department', 'Maintenance'); ?>>Maintenance</option>
                                <option value="Kitchen" <?php echo set_select('department', 'Kitchen'); ?>>Kitchen</option>
                                <option value="Security" <?php echo set_select('department', 'Security'); ?>>Security</option>
                                <option value="Management" <?php echo set_select('department', 'Management'); ?>>Management</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Position *</label>
                            <select class="form-control" id="position" name="position" required>
                                <option value="">Select Position</option>
                                <!-- Front Desk Positions -->
                                <option value="Receptionist" class="position-option" data-department="Front Desk" <?php echo set_select('position', 'Receptionist'); ?>>Receptionist</option>
                                <option value="Front Desk Manager" class="position-option" data-department="Front Desk" <?php echo set_select('position', 'Front Desk Manager'); ?>>Front Desk Manager</option>
                                <option value="Concierge" class="position-option" data-department="Front Desk" <?php echo set_select('position', 'Concierge'); ?>>Concierge</option>
                                <option value="Bell Boy" class="position-option" data-department="Front Desk" <?php echo set_select('position', 'Bell Boy'); ?>>Bell Boy</option>
                                
                                <!-- Housekeeping Positions -->
                                <option value="Housekeeper" class="position-option" data-department="Housekeeping" <?php echo set_select('position', 'Housekeeper'); ?>>Housekeeper</option>
                                <option value="Housekeeping Supervisor" class="position-option" data-department="Housekeeping" <?php echo set_select('position', 'Housekeeping Supervisor'); ?>>Housekeeping Supervisor</option>
                                <option value="Laundry Attendant" class="position-option" data-department="Housekeeping" <?php echo set_select('position', 'Laundry Attendant'); ?>>Laundry Attendant</option>
                                <option value="Housekeeping Manager" class="position-option" data-department="Housekeeping" <?php echo set_select('position', 'Housekeeping Manager'); ?>>Housekeeping Manager</option>
                                
                                <!-- Maintenance Positions -->
                                <option value="Maintenance Technician" class="position-option" data-department="Maintenance" <?php echo set_select('position', 'Maintenance Technician'); ?>>Maintenance Technician</option>
                                <option value="Plumber" class="position-option" data-department="Maintenance" <?php echo set_select('position', 'Plumber'); ?>>Plumber</option>
                                <option value="Electrician" class="position-option" data-department="Maintenance" <?php echo set_select('position', 'Electrician'); ?>>Electrician</option>
                                <option value="Maintenance Manager" class="position-option" data-department="Maintenance" <?php echo set_select('position', 'Maintenance Manager'); ?>>Maintenance Manager</option>
                                
                                <!-- Kitchen Positions -->
                                <option value="Chef" class="position-option" data-department="Kitchen" <?php echo set_select('position', 'Chef'); ?>>Chef</option>
                                <option value="Sous Chef" class="position-option" data-department="Kitchen" <?php echo set_select('position', 'Sous Chef'); ?>>Sous Chef</option>
                                <option value="Kitchen Helper" class="position-option" data-department="Kitchen" <?php echo set_select('position', 'Kitchen Helper'); ?>>Kitchen Helper</option>
                                <option value="Kitchen Manager" class="position-option" data-department="Kitchen" <?php echo set_select('position', 'Kitchen Manager'); ?>>Kitchen Manager</option>
                                
                                <!-- Security Positions -->
                                <option value="Security Guard" class="position-option" data-department="Security" <?php echo set_select('position', 'Security Guard'); ?>>Security Guard</option>
                                <option value="Security Supervisor" class="position-option" data-department="Security" <?php echo set_select('position', 'Security Supervisor'); ?>>Security Supervisor</option>
                                <option value="Security Manager" class="position-option" data-department="Security" <?php echo set_select('position', 'Security Manager'); ?>>Security Manager</option>
                                
                                <!-- Management Positions -->
                                <option value="General Manager" class="position-option" data-department="Management" <?php echo set_select('position', 'General Manager'); ?>>General Manager</option>
                                <option value="Assistant Manager" class="position-option" data-department="Management" <?php echo set_select('position', 'Assistant Manager'); ?>>Assistant Manager</option>
                                <option value="Operations Manager" class="position-option" data-department="Management" <?php echo set_select('position', 'Operations Manager'); ?>>Operations Manager</option>
                                <option value="HR Manager" class="position-option" data-department="Management" <?php echo set_select('position', 'HR Manager'); ?>>HR Manager</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="salary" class="form-label">Salary *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="salary" name="salary" value="<?php echo set_value('salary'); ?>" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hire_date" class="form-label">Hire Date *</label>
                            <input type="date" class="form-control" id="hire_date" name="hire_date" value="<?php echo set_value('hire_date'); ?>" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Staff Member</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department');
    const positionSelect = document.getElementById('position');
    const positionOptions = document.querySelectorAll('.position-option');
    
    // Function to filter positions based on selected department
    function filterPositions() {
        const selectedDepartment = departmentSelect.value;
        
        // Show all position options first
        positionOptions.forEach(option => {
            option.style.display = 'block';
        });
        
        // Hide positions that don't match selected department
        if (selectedDepartment) {
            positionOptions.forEach(option => {
                if (option.getAttribute('data-department') !== selectedDepartment) {
                    option.style.display = 'none';
                }
            });
        }
        
        // Reset position selection if current selection doesn't match department
        if (positionSelect.value && selectedDepartment) {
            const selectedOption = positionSelect.querySelector(`option[value="${positionSelect.value}"]`);
            if (selectedOption && selectedOption.getAttribute('data-department') !== selectedDepartment) {
                positionSelect.value = '';
            }
        }
    }
    
    // Add event listener to department select
    departmentSelect.addEventListener('change', filterPositions);
    
    // Initial filter on page load
    filterPositions();
});
</script>
</body>
</html> 