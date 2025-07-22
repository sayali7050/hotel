<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booking - Admin Panel</title>
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
        .booking-detail-card { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            border: 1px solid rgba(79, 172, 254, 0.1);
        }
        .status-badge { font-size: 1rem; padding: 8px 15px; }
        .info-section { border-left: 4px solid #4facfe; padding-left: 20px; margin-bottom: 30px; }
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
                    <h2><i class="fas fa-calendar-check"></i> Booking Details</h2>
                    <div>
                        <a href="<?php echo base_url('admin/edit_booking/'.$booking->id); ?>" class="btn btn-primary me-2">
                            <i class="fas fa-edit"></i> Edit Booking
                        </a>
                        <a href="<?php echo base_url('admin/bookings'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if($booking): ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="booking-detail-card p-4">
                        <!-- Booking Status -->
                        <div class="text-center mb-4">
                            <?php 
                            $statusClass = '';
                            $statusIcon = '';
                            switch($booking->status) {
                                case 'pending':
                                    $statusClass = 'warning';
                                    $statusIcon = 'fas fa-clock';
                                    break;
                                case 'confirmed':
                                    $statusClass = 'success';
                                    $statusIcon = 'fas fa-check-circle';
                                    break;
                                case 'checked_in':
                                    $statusClass = 'info';
                                    $statusIcon = 'fas fa-sign-in-alt';
                                    break;
                                case 'checked_out':
                                    $statusClass = 'secondary';
                                    $statusIcon = 'fas fa-sign-out-alt';
                                    break;
                                case 'cancelled':
                                    $statusClass = 'danger';
                                    $statusIcon = 'fas fa-times-circle';
                                    break;
                                default:
                                    $statusClass = 'secondary';
                                    $statusIcon = 'fas fa-question-circle';
                            }
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?> status-badge">
                                <i class="<?php echo $statusIcon; ?>"></i> 
                                <?php echo ucfirst(str_replace('_', ' ', $booking->status)); ?>
                            </span>
                        </div>

                        <!-- Customer Information -->
                        <div class="info-section">
                            <h5><i class="fas fa-user"></i> Customer Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> <?php echo $booking->first_name . ' ' . $booking->last_name; ?></p>
                                    <p><strong>Email:</strong> <?php echo $booking->email; ?></p>
                                    <p><strong>Phone:</strong> <?php echo $booking->phone; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <?php if(isset($booking->username)): ?>
                                        <p><strong>Username:</strong> <?php echo $booking->username; ?></p>
                                    <?php endif; ?>
                                    <p><strong>Address:</strong> <?php echo isset($booking->address) ? $booking->address : 'Not provided'; ?></p>
                                    <p><strong>Customer ID:</strong> #<?php echo $booking->user_id; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Room Information -->
                        <div class="info-section">
                            <h5><i class="fas fa-bed"></i> Room Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Room Number:</strong> <?php echo $booking->room_number; ?></p>
                                    <p><strong>Room Type:</strong> <?php echo $booking->room_type; ?></p>
                                    <?php if(isset($booking->capacity)): ?>
                                        <p><strong>Capacity:</strong> <?php echo $booking->capacity; ?> person<?php echo $booking->capacity > 1 ? 's' : ''; ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Price per Night:</strong> $<?php echo number_format($booking->price_per_night, 2); ?></p>
                                    <?php if(isset($booking->room_status)): ?>
                                        <p><strong>Room Status:</strong> 
                                            <span class="badge bg-<?php echo $booking->room_status == 'available' ? 'success' : 'danger'; ?>">
                                                <?php echo ucfirst($booking->room_status); ?>
                                            </span>
                                        </p>
                                    <?php endif; ?>
                                    <?php if(isset($booking->amenities) && $booking->amenities): ?>
                                        <p><strong>Amenities:</strong> <?php echo $booking->amenities; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Dates -->
                        <div class="info-section">
                            <h5><i class="fas fa-calendar-alt"></i> Booking Dates</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Check-in Date:</strong></p>
                                    <p class="text-muted"><?php echo date('l, F d, Y', strtotime($booking->check_in_date)); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Check-out Date:</strong></p>
                                    <p class="text-muted"><?php echo date('l, F d, Y', strtotime($booking->check_out_date)); ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Number of Nights:</strong></p>
                                    <?php 
                                    $check_in = new DateTime($booking->check_in_date);
                                    $check_out = new DateTime($booking->check_out_date);
                                    $nights = $check_in->diff($check_out)->days;
                                    ?>
                                    <p class="text-muted"><?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Booking Date:</strong></p>
                                    <p class="text-muted"><?php echo date('F d, Y', strtotime($booking->created_at)); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <?php if($booking->special_requests): ?>
                            <div class="info-section">
                                <h5><i class="fas fa-comment"></i> Special Requests</h5>
                                <p class="text-muted"><?php echo nl2br($booking->special_requests); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Booking Summary -->
                    <div class="booking-detail-card p-4">
                        <h5><i class="fas fa-receipt"></i> Booking Summary</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Room Rate:</span>
                            <span>$<?php echo number_format($booking->price_per_night, 2); ?> × <?php echo $nights; ?> nights</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($booking->price_per_night * $nights, 2); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount:</strong>
                            <strong>$<?php echo number_format($booking->total_amount, 2); ?></strong>
                        </div>
                    </div>

                    <!-- Status Actions -->
                    <div class="booking-detail-card p-4 mt-3">
                        <h5><i class="fas fa-cogs"></i> Status Actions</h5>
                        <div class="d-grid gap-2">
                            <?php if($booking->status == 'pending'): ?>
                                <a href="<?php echo base_url('admin/update_booking_status/'.$booking->id.'/confirmed'); ?>" 
                                   class="btn btn-success" 
                                   onclick="return confirm('Confirm this booking?')">
                                    <i class="fas fa-check"></i> Confirm Booking
                                </a>
                            <?php endif; ?>
                            
                            <?php if($booking->status == 'confirmed'): ?>
                                <a href="<?php echo base_url('admin/update_booking_status/'.$booking->id.'/checked_in'); ?>" 
                                   class="btn btn-info" 
                                   onclick="return confirm('Check in this guest?')">
                                    <i class="fas fa-sign-in-alt"></i> Check In
                                </a>
                            <?php endif; ?>
                            
                            <?php if($booking->status == 'checked_in'): ?>
                                <a href="<?php echo base_url('admin/update_booking_status/'.$booking->id.'/checked_out'); ?>" 
                                   class="btn btn-secondary" 
                                   onclick="return confirm('Check out this guest?')">
                                    <i class="fas fa-sign-out-alt"></i> Check Out
                                </a>
                            <?php endif; ?>
                            
                            <?php if(in_array($booking->status, ['pending', 'confirmed'])): ?>
                                <a href="<?php echo base_url('admin/update_booking_status/'.$booking->id.'/cancelled'); ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Cancel this booking?')">
                                    <i class="fas fa-times"></i> Cancel Booking
                                </a>
                            <?php endif; ?>
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
                        <p class="text-muted">The booking you're looking for doesn't exist.</p>
                        <a href="<?php echo base_url('admin/bookings'); ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Bookings
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 