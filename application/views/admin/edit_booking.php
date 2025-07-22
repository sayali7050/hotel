<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - Admin Panel</title>
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
        .edit-card { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            border: 1px solid rgba(79, 172, 254, 0.1);
        }
    </style>
</head>
<body>
    <?php $this->load->view('admin/includes/header'); ?>
    <!-- Include Sidebar -->
    <?php $this->load->view('admin/includes/sidebar'); ?>

    <!-- Main Content -->
    <div class="main-content">

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-edit"></i> Edit Booking</h2>
                    <a href="<?php echo base_url('admin/view_booking/'.$booking->id); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Booking Details
                    </a>
                </div>
            </div>
        </div>

        <?php if($booking): ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="edit-card p-4">
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

                        <?php echo form_open('admin/edit_booking/'.$booking->id); ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Booking Status *</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="pending" <?php echo set_select('status', 'pending', $booking->status == 'pending'); ?>>Pending</option>
                                        <option value="confirmed" <?php echo set_select('status', 'confirmed', $booking->status == 'confirmed'); ?>>Confirmed</option>
                                        <option value="checked_in" <?php echo set_select('status', 'checked_in', $booking->status == 'checked_in'); ?>>Checked In</option>
                                        <option value="checked_out" <?php echo set_select('status', 'checked_out', $booking->status == 'checked_out'); ?>>Checked Out</option>
                                        <option value="cancelled" <?php echo set_select('status', 'cancelled', $booking->status == 'cancelled'); ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="room_id" class="form-label">Room *</label>
                                    <select class="form-control" id="room_id" name="room_id" required>
                                        <?php foreach($rooms as $room): ?>
                                            <option value="<?php echo $room->id; ?>" <?php echo set_select('room_id', $room->id, $booking->room_id == $room->id); ?>>
                                                Room <?php echo $room->room_number; ?> - <?php echo $room->room_type; ?> ($<?php echo number_format($room->price_per_night, 2); ?>/night)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="check_in_date" class="form-label">Check-in Date *</label>
                                    <input type="date" class="form-control" id="check_in_date" name="check_in_date" 
                                           value="<?php echo set_value('check_in_date', $booking->check_in_date); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="check_out_date" class="form-label">Check-out Date *</label>
                                    <input type="date" class="form-control" id="check_out_date" name="check_out_date" 
                                           value="<?php echo set_value('check_out_date', $booking->check_out_date); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="special_requests" class="form-label">Special Requests</label>
                                <textarea class="form-control" id="special_requests" name="special_requests" rows="3" 
                                          placeholder="Any special requests or notes..."><?php echo set_value('special_requests', $booking->special_requests); ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo base_url('admin/view_booking/'.$booking->id); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Booking
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Current Booking Info -->
                    <div class="edit-card p-4">
                        <h5><i class="fas fa-info-circle"></i> Current Booking Info</h5>
                        <hr>
                        <p><strong>Customer:</strong> <?php echo $booking->first_name . ' ' . $booking->last_name; ?></p>
                        <p><strong>Email:</strong> <?php echo $booking->email; ?></p>
                        <p><strong>Phone:</strong> <?php echo $booking->phone; ?></p>
                        <p><strong>Current Room:</strong> Room <?php echo $booking->room_number; ?> (<?php echo $booking->room_type; ?>)</p>
                        <p><strong>Current Status:</strong> 
                            <span class="badge bg-<?php 
                                switch($booking->status) {
                                    case 'pending': echo 'warning'; break;
                                    case 'confirmed': echo 'success'; break;
                                    case 'checked_in': echo 'info'; break;
                                    case 'checked_out': echo 'secondary'; break;
                                    case 'cancelled': echo 'danger'; break;
                                    default: echo 'secondary';
                                }
                            ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $booking->status)); ?>
                            </span>
                        </p>
                        <p><strong>Total Amount:</strong> $<?php echo number_format($booking->total_amount, 2); ?></p>
                    </div>

                    <!-- Quick Actions -->
                    <div class="edit-card p-4 mt-3">
                        <h5><i class="fas fa-bolt"></i> Quick Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="<?php echo base_url('admin/view_booking/'.$booking->id); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="<?php echo base_url('admin/bookings'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-list"></i> All Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Booking Not Found</h4>
                        <p class="text-muted">The booking you're trying to edit doesn't exist.</p>
                        <a href="<?php echo base_url('admin/bookings'); ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Bookings
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date to today for check-in
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('check_in_date').min = today;
        document.getElementById('check_out_date').min = today;

        // Update check-out minimum date when check-in changes
        document.getElementById('check_in_date').addEventListener('change', function() {
            document.getElementById('check_out_date').min = this.value;
        });
    </script>
</body>
</html> 