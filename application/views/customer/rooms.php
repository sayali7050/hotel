<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms - Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .room-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform 0.3s; }
        .room-card:hover { transform: translateY(-5px); }
        .room-image { height: 200px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 15px 15px 0 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; }
        .price-badge { background: linear-gradient(45deg, #28a745, #20c997); color: white; padding: 8px 15px; border-radius: 25px; font-weight: bold; }
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
                        <a class="nav-link active" href="<?php echo base_url('customer/rooms'); ?>">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('customer/my_bookings'); ?>">My Bookings</a>
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
                <h2 class="mb-4"><i class="fas fa-bed"></i> Available Rooms</h2>
                
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
            </div>
        </div>

        <?php if($rooms): ?>
            <div class="row">
                <?php foreach($rooms as $room): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="room-card">
                            <div class="room-image">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0">Room <?php echo $room->room_number; ?></h5>
                                    <span class="price-badge">$<?php echo number_format($room->price_per_night, 2); ?>/night</span>
                                </div>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-tag"></i> <?php echo $room->room_type; ?>
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-user"></i> <?php echo $room->capacity; ?> person<?php echo $room->capacity > 1 ? 's' : ''; ?>
                                </p>
                                <?php if($room->description): ?>
                                    <p class="text-muted mb-3"><?php echo substr($room->description, 0, 100); ?>...</p>
                                <?php endif; ?>
                                <?php if($room->amenities): ?>
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-star"></i> <?php echo $room->amenities; ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                                <div class="d-grid">
                                    <a href="<?php echo base_url('customer/book_room/'.$room->id); ?>" class="btn btn-primary">
                                        <i class="fas fa-calendar-plus"></i> Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No rooms available at the moment</h4>
                        <p class="text-muted">Please check back later for available rooms.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 