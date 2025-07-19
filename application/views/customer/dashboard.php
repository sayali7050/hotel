<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            padding: 20px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .welcome-card {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-user-circle"></i> Customer Panel
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="<?php echo base_url('customer/dashboard'); ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a class="nav-link" href="<?php echo base_url('customer/rooms'); ?>">
                            <i class="fas fa-bed"></i> Browse Rooms
                        </a>
                        <a class="nav-link" href="<?php echo base_url('customer/book-room'); ?>">
                            <i class="fas fa-calendar-plus"></i> Book Room
                        </a>
                        <a class="nav-link" href="<?php echo base_url('customer/my-bookings'); ?>">
                            <i class="fas fa-calendar-check"></i> My Bookings
                        </a>
                        <hr class="my-3">
                        <a class="nav-link" href="<?php echo base_url('customer/profile'); ?>">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <a class="nav-link" href="<?php echo base_url('logout'); ?>">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2><i class="fas fa-home"></i> Welcome back, <?php echo $user->first_name; ?>!</h2>
                            <p class="mb-0">We're glad to see you again. Here's what's happening with your bookings.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="fas fa-hotel fa-4x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary me-3">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $total_bookings; ?></h3>
                                    <p class="text-muted mb-0">Total Bookings</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $active_bookings; ?></h3>
                                    <p class="text-muted mb-0">Active Bookings</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info me-3">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $completed_bookings; ?></h3>
                                    <p class="text-muted mb-0">Completed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <a href="<?php echo base_url('customer/rooms'); ?>" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-bed"></i><br>
                                            Browse Rooms
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="<?php echo base_url('customer/book-room'); ?>" class="btn btn-outline-success w-100">
                                            <i class="fas fa-calendar-plus"></i><br>
                                            Book New Room
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="<?php echo base_url('customer/my-bookings'); ?>" class="btn btn-outline-info w-100">
                                            <i class="fas fa-calendar-check"></i><br>
                                            My Bookings
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="<?php echo base_url('customer/profile'); ?>" class="btn btn-outline-warning w-100">
                                            <i class="fas fa-user"></i><br>
                                            My Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Recent Bookings</h5>
                            </div>
                            <div class="card-body">
                                <?php if($bookings): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Room</th>
                                                    <th>Check-in</th>
                                                    <th>Check-out</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($bookings as $booking): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo $booking->room_number; ?></strong><br>
                                                            <small class="text-muted"><?php echo $booking->room_type; ?></small>
                                                        </td>
                                                        <td><?php echo date('M d, Y', strtotime($booking->check_in_date)); ?></td>
                                                        <td><?php echo date('M d, Y', strtotime($booking->check_out_date)); ?></td>
                                                        <td>$<?php echo number_format($booking->total_amount, 2); ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'checked_in' ? 'info' : 'secondary')); ?>">
                                                                <?php echo ucfirst($booking->status); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo base_url('customer/view-booking/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                            <?php if($booking->status == 'pending' || $booking->status == 'confirmed'): ?>
                                                                <a href="<?php echo base_url('customer/cancel-booking/' . $booking->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                                    <i class="fas fa-times"></i> Cancel
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No bookings yet</h5>
                                        <p class="text-muted">Start by browsing our available rooms and make your first booking!</p>
                                        <a href="<?php echo base_url('customer/rooms'); ?>" class="btn btn-primary">
                                            <i class="fas fa-bed"></i> Browse Rooms
                                        </a>
                                    </div>
                                <?php endif; ?>
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