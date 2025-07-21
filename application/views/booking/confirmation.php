<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .confirmation-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 30px 0;
        }
        
        .confirmation-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            border-radius: 20px 20px 0 0;
            text-align: center;
        }
        
        .confirmation-header .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .confirmation-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .confirmation-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .confirmation-content {
            padding: 40px;
        }
        
        .booking-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #333;
        }
        
        .detail-value {
            color: #666;
            text-align: right;
        }
        
        .booking-id {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .status-badge {
            background: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .next-steps {
            background: #e3f2fd;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .step-item:last-child {
            margin-bottom: 0;
        }
        
        .step-number {
            background: #4facfe;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 15px;
            font-size: 0.9rem;
        }
        
        .step-text {
            color: #333;
            font-weight: 500;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 50px;
            padding: 15px 30px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 50px;
            padding: 15px 30px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            color: white;
        }
        
        .contact-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .contact-info h6 {
            color: #856404;
            margin-bottom: 15px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .contact-item i {
            color: #4facfe;
            margin-right: 10px;
            width: 20px;
        }
        
        .contact-item span {
            color: #856404;
        }
        
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            
            .detail-value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="confirmation-container">
                    <!-- Header -->
                    <div class="confirmation-header">
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h1>Booking Confirmed!</h1>
                        <p>Your reservation has been successfully created. We look forward to welcoming you!</p>
                    </div>
                    
                    <!-- Content -->
                    <div class="confirmation-content">
                        <h2>Booking Confirmed!</h2>
                        <div class="booking-details">
                            <p><strong>Booking ID:</strong> #<?php echo str_pad($booking->id, 6, '0', STR_PAD_LEFT); ?></p>
                            <p><strong>Guest Name:</strong> <?php echo $booking->guest_name; ?></p>
                            <p><strong>Email:</strong> <?php echo $booking->guest_email; ?></p>
                            <p><strong>Phone:</strong> <?php echo $booking->guest_phone; ?></p>
                            <p><strong>Room:</strong> <?php echo $booking->room_type . ' (Room ' . $booking->room_number . ')'; ?></p>
                            <p><strong>Check-in:</strong> <?php echo date('M d, Y', strtotime($booking->check_in_date)); ?></p>
                            <p><strong>Check-out:</strong> <?php echo date('M d, Y', strtotime($booking->check_out_date)); ?></p>
                            <p><strong>Guests:</strong> <?php echo $booking->adults + $booking->children; ?></p>
                            <p><strong>Total Amount:</strong> $<?php echo number_format($booking->total_amount, 2); ?></p>
                            <p><strong>Status:</strong> <?php echo ucfirst($booking->status); ?></p>
                        </div>
                        <div class="next-steps">Thank you for booking with us! We look forward to your stay.</div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="<?php echo base_url('booking'); ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Book Another Stay
                            </a>
                            
                            <a href="<?php echo base_url(); ?>" class="btn btn-secondary">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                            
                            <?php if($this->session->userdata('logged_in')): ?>
                            <a href="<?php echo base_url('customer/my_bookings'); ?>" class="btn btn-secondary">
                                <i class="fas fa-list me-2"></i>View My Bookings
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="contact-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Need Help?</h6>
                            
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>Call us: +1 (555) 123-4567</span>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>Email: reservations@hotel.com</span>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span>Available 24/7 for assistance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 