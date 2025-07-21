<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Room - Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .booking-summary {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .room-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .room-image {
            height: 250px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .room-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .room-price {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            text-align: center;
        }
        
        .room-price .amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4facfe;
        }
        
        .room-price .period {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .room-content {
            padding: 25px;
        }
        
        .room-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        
        .room-description {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .room-features {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .feature-tag {
            background: #f8f9fa;
            color: #4facfe;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .btn-select-room {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
        }
        
        .btn-select-room:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            color: #666;
            font-weight: 500;
        }
        
        .summary-value {
            color: #333;
            font-weight: 600;
        }
        
        .btn-back {
            background: #6c757d;
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: #5a6268;
            color: white;
        }
        
        .no-rooms {
            background: white;
            border-radius: 15px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .no-rooms i {
            font-size: 4rem;
            color: #4facfe;
            margin-bottom: 20px;
        }
        
        .no-rooms h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .no-rooms p {
            color: #666;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Booking Summary -->
        <div class="booking-summary">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-3"><i class="fas fa-calendar-check me-2"></i>Your Booking Summary</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="summary-item">
                                <span class="summary-label">Check-in:</span>
                                <span class="summary-value"><?php echo date('M d, Y', strtotime($search_data['check_in_date'])); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-item">
                                <span class="summary-label">Check-out:</span>
                                <span class="summary-value"><?php echo date('M d, Y', strtotime($search_data['check_out_date'])); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-item">
                                <span class="summary-label">Guests:</span>
                                <span class="summary-value"><?php echo $search_data['adults'] + $search_data['children']; ?></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-item">
                                <span class="summary-label">Nights:</span>
                                <span class="summary-value"><?php echo $search_data['nights']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="<?php echo base_url('booking'); ?>" class="btn btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Modify Search
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Available Rooms -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-white mb-4">
                    <i class="fas fa-bed me-2"></i>Available Rooms (<?php echo count($rooms); ?>)
                </h3>
            </div>
        </div>
        
        <?php if(empty($rooms)): ?>
            <div class="no-rooms">No rooms available for your selected dates. <a href="<?php echo base_url('booking'); ?>">Modify Search</a></div>
        <?php else: ?>
            <div class="row">
                <?php foreach($rooms as $room): ?>
                    <div class="col-lg-6 col-xl-4">
                        <div class="room-card">
                            <div class="room-image" style="background-image: url('<?php echo base_url('assets/img/hotel/room-' . rand(1, 12) . '.webp'); ?>');">
                                <div class="room-badge"><?php echo $room->room_type; ?></div>
                                <div class="room-price">
                                    <div class="amount">$<?php echo number_format($room->price_per_night); ?></div>
                                    <div class="period">per night</div>
                                </div>
                            </div>
                            <div class="room-content">
                                <h5 class="room-title"><?php echo $room->room_type; ?> Room</h5>
                                <p class="room-description">
                                    <?php echo isset($room->description) ? $room->description : 'Comfortable and well-appointed room with modern amenities for a relaxing stay.'; ?>
                                </p>
                                
                                <div class="room-features">
                                    <span class="feature-tag">
                                        <i class="fas fa-user me-1"></i><?php echo $room->capacity; ?> Guests
                                    </span>
                                    <span class="feature-tag">
                                        <i class="fas fa-wifi me-1"></i>Free Wi-Fi
                                    </span>
                                    <span class="feature-tag">
                                        <i class="fas fa-tv me-1"></i>Smart TV
                                    </span>
                                    <?php if(isset($room->amenities) && $room->amenities): ?>
                                        <?php 
                                        $amenities = explode(',', $room->amenities);
                                        foreach(array_slice($amenities, 0, 2) as $amenity): 
                                        ?>
                                            <span class="feature-tag">
                                                <i class="fas fa-check me-1"></i><?php echo trim($amenity); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-primary">Total: $<?php echo number_format($room->price_per_night * $search_data['nights']); ?></strong>
                                        <small class="text-muted d-block">for <?php echo $search_data['nights']; ?> nights</small>
                                    </div>
                                    <a href="<?php echo base_url('booking/book_room/' . $room->id); ?>" class="btn btn-select-room">
                                        <i class="fas fa-check me-2"></i>Select Room
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 