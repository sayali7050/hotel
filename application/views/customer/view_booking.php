<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .booking-detail-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .status-badge { font-size: 1rem; padding: 8px 15px; }
        .info-section { border-left: 4px solid #667eea; padding-left: 20px; margin-bottom: 30px; }
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
                        <a class="nav-link active" href="<?php echo base_url('customer/my_bookings'); ?>">My Bookings</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo $this->session->userdata('username'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo base_url('customer/profile'); ?>">Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('customer/change_password'); ?>">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('logout'); ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-calendar-check"></i> Booking Details</h2>
                    <a href="<?php echo base_url('customer/my_bookings'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to My Bookings
                    </a>
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

                        <!-- Actions -->
                        <div class="text-center mt-4">
                            <?php if($booking->status == 'pending' || $booking->status == 'confirmed'): ?>
                                <a href="<?php echo base_url('customer/cancel_booking/'.$booking->id); ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    <i class="fas fa-times"></i> Cancel Booking
                                </a>
                            <?php endif; ?>
                        </div>
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

                    <!-- Contact Information -->
                    <div class="booking-detail-card p-4 mt-3">
                        <h5><i class="fas fa-phone"></i> Need Help?</h5>
                        <p class="text-muted">If you have any questions about your booking, please contact us:</p>
                        <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                        <p><i class="fas fa-envelope"></i> support@hotel.com</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Booking Not Found</h4>
                        <p class="text-muted">The booking you're looking for doesn't exist or you don't have permission to view it.</p>
                        <a href="<?php echo base_url('customer/my_bookings'); ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to My Bookings
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 