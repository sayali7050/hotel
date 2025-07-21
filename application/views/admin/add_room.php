<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Room</title>
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
                <h2><i class="fas fa-plus"></i> Add New Room</h2>
                <a href="<?php echo base_url('admin/rooms'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Rooms</a>
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
                <?php echo form_open('admin/add_room'); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_number" class="form-label">Room Number *</label>
                            <input type="text" class="form-control" id="room_number" name="room_number" value="<?php echo set_value('room_number'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="room_type" class="form-label">Room Type *</label>
                            <select class="form-control" id="room_type" name="room_type" required>
                                <option value="">Select Room Type</option>
                                <option value="Standard" <?php echo set_select('room_type', 'Standard'); ?>>Standard</option>
                                <option value="Deluxe" <?php echo set_select('room_type', 'Deluxe'); ?>>Deluxe</option>
                                <option value="Suite" <?php echo set_select('room_type', 'Suite'); ?>>Suite</option>
                                <option value="Executive" <?php echo set_select('room_type', 'Executive'); ?>>Executive</option>
                                <option value="Presidential" <?php echo set_select('room_type', 'Presidential'); ?>>Presidential</option>
                                <option value="Family" <?php echo set_select('room_type', 'Family'); ?>>Family</option>
                                <option value="Accessible" <?php echo set_select('room_type', 'Accessible'); ?>>Accessible</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Capacity *</label>
                            <select class="form-control" id="capacity" name="capacity" required>
                                <option value="">Select Capacity</option>
                                <option value="1" <?php echo set_select('capacity', '1'); ?>>1 Person</option>
                                <option value="2" <?php echo set_select('capacity', '2'); ?>>2 People</option>
                                <option value="3" <?php echo set_select('capacity', '3'); ?>>3 People</option>
                                <option value="4" <?php echo set_select('capacity', '4'); ?>>4 People</option>
                                <option value="5" <?php echo set_select('capacity', '5'); ?>>5 People</option>
                                <option value="6" <?php echo set_select('capacity', '6'); ?>>6 People</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price_per_night" class="form-label">Price per Night *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="price_per_night" name="price_per_night" value="<?php echo set_value('price_per_night'); ?>" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter room description..."><?php echo set_value('description'); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="amenities" class="form-label">Amenities</label>
                        <textarea class="form-control" id="amenities" name="amenities" rows="3" placeholder="Enter room amenities (e.g., WiFi, TV, AC, Mini Bar)"><?php echo set_value('amenities'); ?></textarea>
                        <small class="text-muted">Separate amenities with commas</small>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="available" <?php echo set_select('status', 'available'); ?>>Available</option>
                            <option value="maintenance" <?php echo set_select('status', 'maintenance'); ?>>Maintenance</option>
                            <option value="reserved" <?php echo set_select('status', 'reserved'); ?>>Reserved</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo base_url('admin/rooms'); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Room</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 