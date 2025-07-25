<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Booking - Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .booking-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 30px 0;
        }
        
        .booking-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            border-radius: 20px 20px 0 0;
        }
        
        .booking-header h2 {
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .booking-content {
            padding: 40px;
        }
        
        .room-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .room-image {
            width: 100px;
            height: 80px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            margin-right: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
            outline: none;
        }
        
        .price-breakdown {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .price-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.1rem;
            color: #4facfe;
        }
        
        .btn-complete-booking {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3);
            width: 100%;
        }
        
        .btn-complete-booking:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-back {
            background: #6c757d;
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-back:hover {
            background: #5a6268;
            color: white;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
        }
        
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method:hover {
            border-color: #4facfe;
            background: #f8f9fa;
        }
        
        .payment-method input[type="radio"] {
            margin-right: 10px;
        }
        
        .payment-method.selected {
            border-color: #4facfe;
            background: rgba(79, 172, 254, 0.1);
        }
        
        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .terms-checkbox input[type="checkbox"] {
            margin-right: 10px;
            margin-top: 3px;
        }
        
        .terms-checkbox label {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.5;
        }
        
        .terms-checkbox a {
            color: #4facfe;
            text-decoration: none;
        }
        
        .terms-checkbox a:hover {
            text-decoration: underline;
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
        .error-message {
            color: #b30000;
            font-size: 1em;
            margin-top: 2px;
            display: block;
        }
    </style>
</head>
<body>
    <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="booking-container" role="main" id="main-content">
                    <!-- Header -->
                    <div class="booking-header">
                        <h2><i class="fas fa-credit-card me-3"></i>Complete Your Booking</h2>
                        <p>Please provide your details to confirm your reservation</p>
                    </div>
                    
                    <!-- Content -->
                    <div class="booking-content">
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="<?php echo base_url('booking/process_booking'); ?>" method="POST" aria-labelledby="bookingFormTitle">
                            <input type="hidden" name="room_id" value="<?php echo $room->id; ?>">
                            <div class="row">
                                <!-- Left Column - Guest Details -->
                                <div class="col-lg-8">
                                    <!-- Room Summary -->
                                    <div class="room-summary">
                                        <div class="d-flex align-items-center">
                                            <div class="room-image" style="background-image: url('<?php echo base_url('assets/img/hotel/room-' . rand(1, 12) . '.webp'); ?>');" role="img" aria-label="Room image"></div>
                                            <div>
                                                <h5 class="mb-1" id="room-type-label"><?php echo $room->room_type; ?> Room</h5>
                                                <p class="mb-1 text-muted">Room <?php echo $room->room_number; ?> • <?php echo $room->capacity; ?> Guests</p>
                                                <p class="mb-0 text-muted">
                                                    <?php echo date('M d', strtotime($search_data['check_in_date'])); ?> - 
                                                    <?php echo date('M d, Y', strtotime($search_data['check_out_date'])); ?> 
                                                    (<?php echo $search_data['nights']; ?> nights)
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Guest Information -->
                                    <h5 class="mb-3"><i class="fas fa-user me-2"></i>Guest Information</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="guest_name" class="form-label">Full Name *</label>
                                            <input type="text" class="form-control" id="guest_name" name="guest_name" aria-required="true" aria-label="Full Name" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="guest_email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control" id="guest_email" name="guest_email" aria-required="true" aria-label="Email Address" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="guest_phone" class="form-label">Phone Number *</label>
                                            <input type="tel" class="form-control" id="guest_phone" name="guest_phone" aria-required="true" aria-label="Phone Number" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="guest_address" class="form-label">Address *</label>
                                            <input type="text" class="form-control" id="guest_address" name="guest_address" aria-required="true" aria-label="Address" required>
                                        </div>
                                    </div>
                                    
                                    <!-- Payment Method -->
                                    <h5 class="mb-3 mt-4"><i class="fas fa-credit-card me-2"></i>Payment Method</h5>
                                    
                                    <div class="payment-method" onclick="selectPayment('credit_card')" tabindex="0" role="radio" aria-checked="false" aria-label="Credit or Debit Card">
                                        <input type="radio" name="payment_method" id="credit_card" value="credit_card" required>
                                        <label for="credit_card">
                                            <i class="fas fa-credit-card me-2"></i>Credit/Debit Card
                                        </label>
                                    </div>
                                    
                                    <div class="payment-method" onclick="selectPayment('paypal')" tabindex="0" role="radio" aria-checked="false" aria-label="PayPal">
                                        <input type="radio" name="payment_method" id="paypal" value="paypal">
                                        <label for="paypal">
                                            <i class="fab fa-paypal me-2"></i>PayPal
                                        </label>
                                    </div>
                                    
                                    <div class="payment-method" onclick="selectPayment('cash')" tabindex="0" role="radio" aria-checked="false" aria-label="Pay at Hotel">
                                        <input type="radio" name="payment_method" id="cash" value="cash">
                                        <label for="cash">
                                            <i class="fas fa-money-bill-wave me-2"></i>Pay at Hotel
                                        </label>
                                    </div>
                                    
                                    <!-- Coupon Code -->
                                    <div class="mb-3">
                                        <label for="coupon_code" class="form-label">Coupon Code</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="<?php echo set_value('coupon_code'); ?>" placeholder="Enter coupon code" aria-describedby="couponCodeError">
                                            <?php if (form_error('coupon_code')): ?>
                                                <span id="couponCodeError" class="error-message" role="alert"><?= form_error('coupon_code') ?></span>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-outline-primary" id="apply-coupon-btn">Apply</button>
                                        </div>
                                        <div id="coupon-message" class="mt-2"></div>
                                    </div>
                                    
                                    <!-- Terms and Conditions -->
                                    <div class="terms-checkbox mt-4">
                                        <input type="checkbox" id="terms_accepted" name="terms_accepted" aria-required="true">
                                        <label for="terms_accepted">
                                            I agree to the <a href="#" target="_blank">Terms and Conditions</a> and 
                                            <a href="#" target="_blank">Privacy Policy</a>. I understand that this booking is subject to our cancellation policy.
                                        </label>
                                    </div>
                                    
                                </div>
                                
                                <!-- Right Column - Price Summary -->
                                <div class="col-lg-4">
                                    <div class="price-breakdown">
                                        <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Price Summary</h5>
                                        
                                        <div class="price-item">
                                            <span>Room Rate (<?php echo $search_data['nights']; ?> nights)</span>
                                            <span>$<?php echo number_format($room->price_per_night * $search_data['nights']); ?></span>
                                        </div>
                                        
                                        <div class="price-item">
                                            <span>Taxes & Fees</span>
                                            <span>$<?php echo number_format(($room->price_per_night * $search_data['nights']) * 0.15); ?></span>
                                        </div>
                                        <?php if (!empty($applied_coupon)): ?>
                                           <div class="price-item text-success">
                                               <span>Coupon Discount (<?php echo htmlspecialchars($applied_coupon['code']); ?>)</span>
                                               <span>- $<?php echo number_format($applied_coupon['discount'], 2); ?></span>
                                           </div>
                                       <?php endif; ?>
                                        <div class="price-item">
                                            <span>Total Amount</span>
                                            <span>$<?php echo number_format($total_amount); ?></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-3">
                                        <button type="submit" class="btn btn-complete-booking">
                                            <i class="fas fa-check me-2"></i>Complete Booking
                                        </button>
                                        <a href="<?php echo base_url('booking/select_room'); ?>" class="btn btn-back">
                                            <i class="fas fa-arrow-left me-2"></i>Back to Rooms
                                        </a>
                                    </div>
                                    
                                    <!-- Booking Guarantee -->
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <h6 class="text-primary mb-2">
                                            <i class="fas fa-shield-alt me-2"></i>Booking Guarantee
                                        </h6>
                                        <ul class="list-unstyled small mb-0">
                                            <li><i class="fas fa-check text-success me-2"></i>Free cancellation up to 24h before check-in</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Best price guarantee</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Secure payment processing</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPayment(method) {
            // Remove selected class from all payment methods
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selected class to clicked payment method
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            document.getElementById(method).checked = true;
        }
        
        // Auto-select first payment method
        document.addEventListener('DOMContentLoaded', function() {
            const firstPayment = document.querySelector('.payment-method');
            if (firstPayment) {
                firstPayment.classList.add('selected');
                firstPayment.querySelector('input').checked = true;
            }
        });
    </script>
</body>
</html> 