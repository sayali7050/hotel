<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Stay - Hotel Management System</title>
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
        }
        
        .booking-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 40px;
            border-radius: 20px 20px 0 0;
            text-align: center;
        }
        
        .booking-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .booking-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .booking-form {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
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
            background: white;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
            outline: none;
        }
        
        .btn-search {
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
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .features-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 20px 20px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .feature-item i {
            color: #4facfe;
            font-size: 1.2rem;
            margin-right: 10px;
            width: 20px;
        }
        
        .feature-item span {
            color: #666;
            font-size: 0.9rem;
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
        
        .date-inputs {
            position: relative;
        }
        
        .date-inputs .form-control {
            padding-left: 45px;
        }
        
        .date-inputs i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4facfe;
            z-index: 10;
        }
        
        .guest-counter {
            display: flex;
            align-items: center;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .guest-counter button {
            background: #f8f9fa;
            border: none;
            padding: 15px 20px;
            font-size: 1.2rem;
            color: #4facfe;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .guest-counter button:hover {
            background: #4facfe;
            color: white;
        }
        
        .guest-counter input {
            border: none;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            background: white;
        }
        
        .guest-counter input:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="booking-container">
                    <!-- Header -->
                    <div class="booking-header">
                        <h1><i class="fas fa-calendar-check me-3"></i>Book Your Perfect Stay</h1>
                        <p>Find the best rates and availability for your dream vacation</p>
                    </div>
                    
                    <!-- Booking Form -->
                    <div class="booking-form">
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $this->session->flashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="<?php echo base_url('booking/search_availability'); ?>" method="POST">
                            <div class="row">
                                <!-- Check-in Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="check_in_date" class="form-label">
                                        <i class="fas fa-calendar-alt me-2"></i>Check-in Date
                                    </label>
                                    <div class="date-inputs">
                                        <i class="fas fa-calendar"></i>
                                        <input type="date" class="form-control" id="check_in_date" name="check_in_date" 
                                               value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                
                                <!-- Check-out Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="check_out_date" class="form-label">
                                        <i class="fas fa-calendar-alt me-2"></i>Check-out Date
                                    </label>
                                    <div class="date-inputs">
                                        <i class="fas fa-calendar"></i>
                                        <input type="date" class="form-control" id="check_out_date" name="check_out_date" 
                                               value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" 
                                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Adults -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user me-2"></i>Adults
                                    </label>
                                    <div class="guest-counter">
                                        <button type="button" onclick="changeGuestCount('adults', -1)">-</button>
                                        <input type="number" name="adults" id="adults" value="2" min="1" max="10" readonly>
                                        <button type="button" onclick="changeGuestCount('adults', 1)">+</button>
                                    </div>
                                </div>
                                
                                <!-- Children -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-child me-2"></i>Children
                                    </label>
                                    <div class="guest-counter">
                                        <button type="button" onclick="changeGuestCount('children', -1)">-</button>
                                        <input type="number" name="children" id="children" value="0" min="0" max="6" readonly>
                                        <button type="button" onclick="changeGuestCount('children', 1)">+</button>
                                    </div>
                                </div>
                                
                                <!-- Rooms -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-bed me-2"></i>Rooms
                                    </label>
                                    <div class="guest-counter">
                                        <button type="button" onclick="changeGuestCount('rooms', -1)">-</button>
                                        <input type="number" name="rooms" id="rooms" value="1" min="1" max="5" readonly>
                                        <button type="button" onclick="changeGuestCount('rooms', 1)">+</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Special Requests -->
                            <div class="mb-4">
                                <label for="special_requests" class="form-label">
                                    <i class="fas fa-comment me-2"></i>Special Requests (Optional)
                                </label>
                                <textarea class="form-control" id="special_requests" name="special_requests" 
                                          rows="3" placeholder="Any special needs, dietary requirements, or preferences..."></textarea>
                            </div>
                            
                            <!-- Search Button -->
                            <div class="text-center">
                                <button type="submit" name="search_availability" class="btn btn-search">
                                    <i class="fas fa-search me-2"></i>Check Availability & Rates
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Features Section -->
                    <div class="features-section">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Free cancellation</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-credit-card"></i>
                                    <span>No booking fees</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Instant confirmation</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeGuestCount(type, change) {
            const input = document.getElementById(type);
            const currentValue = parseInt(input.value);
            const min = parseInt(input.min);
            const max = parseInt(input.max);
            
            const newValue = currentValue + change;
            if (newValue >= min && newValue <= max) {
                input.value = newValue;
            }
        }
        
        // Set minimum check-out date based on check-in date
        document.getElementById('check_in_date').addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const nextDay = new Date(checkInDate);
            nextDay.setDate(nextDay.getDate() + 1);
            
            const checkOutInput = document.getElementById('check_out_date');
            checkOutInput.min = nextDay.toISOString().split('T')[0];
            
            // If current check-out date is before new check-in date, update it
            if (checkOutInput.value <= this.value) {
                checkOutInput.value = nextDay.toISOString().split('T')[0];
            }
        });
    </script>
</body>
</html> 