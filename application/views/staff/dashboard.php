<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-user-tie"></i> Staff Panel
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="<?php echo base_url('staff/dashboard'); ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a class="nav-link" href="<?php echo base_url('staff/bookings'); ?>">
                            <i class="fas fa-calendar-check"></i> Bookings
                        </a>
                        <a class="nav-link" href="<?php echo base_url('staff/rooms'); ?>">
                            <i class="fas fa-bed"></i> Rooms
                        </a>
                        <a class="nav-link" href="<?php echo base_url('staff/search-bookings'); ?>">
                            <i class="fas fa-search"></i> Search
                        </a>
                        <hr class="my-3">
                        <a class="nav-link" href="<?php echo base_url('staff/profile'); ?>">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-tachometer-alt"></i> Staff Dashboard</h2>
                    <div class="text-muted">
                        Welcome, <?php echo $this->session->userdata('username'); ?>!
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
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary me-3">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $total_rooms; ?></h3>
                                    <p class="text-muted mb-0">Total Rooms</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $available_rooms; ?></h3>
                                    <p class="text-muted mb-0">Available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning me-3">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $occupied_rooms; ?></h3>
                                    <p class="text-muted mb-0">Occupied</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-danger me-3">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $maintenance_rooms; ?></h3>
                                    <p class="text-muted mb-0">Maintenance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Statistics -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info me-3">
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
                                <div class="stat-icon bg-warning me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $pending_bookings; ?></h3>
                                    <p class="text-muted mb-0">Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success me-3">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $checked_in_bookings; ?></h3>
                                    <p class="text-muted mb-0">Checked In</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <a href="<?php echo base_url('staff/bookings'); ?>" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-calendar-check"></i><br>
                                            Manage Bookings
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="<?php echo base_url('staff/rooms'); ?>" class="btn btn-outline-success w-100">
                                            <i class="fas fa-bed"></i><br>
                                            Room Status
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="<?php echo base_url('staff/search-bookings'); ?>" class="btn btn-outline-info w-100">
                                            <i class="fas fa-search"></i><br>
                                            Search Bookings
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="<?php echo base_url('staff/profile'); ?>" class="btn btn-outline-warning w-100">
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
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Recent Bookings</h5>
                            </div>
                            <div class="card-body">
                                <?php if($recent_bookings): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Guest</th>
                                                    <th>Room</th>
                                                    <th>Check-in</th>
                                                    <th>Check-out</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($recent_bookings as $booking): ?>
                                                    <tr>
                                                        <td><?php echo $booking->first_name . ' ' . $booking->last_name; ?></td>
                                                        <td><?php echo $booking->room_number; ?></td>
                                                        <td><?php echo date('M d, Y', strtotime($booking->check_in_date)); ?></td>
                                                        <td><?php echo date('M d, Y', strtotime($booking->check_out_date)); ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'checked_in' ? 'info' : 'secondary')); ?>">
                                                                <?php echo ucfirst($booking->status); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo base_url('staff/view-booking/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <?php if($booking->status == 'confirmed'): ?>
                                                                <a href="<?php echo base_url('staff/check-in/' . $booking->id); ?>" class="btn btn-sm btn-success">
                                                                    <i class="fas fa-sign-in-alt"></i> Check-in
                                                                </a>
                                                            <?php elseif($booking->status == 'checked_in'): ?>
                                                                <a href="<?php echo base_url('staff/check-out/' . $booking->id); ?>" class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-sign-out-alt"></i> Check-out
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center">No recent bookings</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Room Cleaning & Maintenance Status -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-broom"></i> Room Cleaning & Maintenance Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Room #</th>
                                                <th>Type</th>
                                                <th>Cleaning Status</th>
                                                <th>Maintenance Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($rooms as $room): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($room->room_number); ?></td>
                                                <td><?php echo htmlspecialchars($room->room_type); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php
                                                        switch($room->cleaning_status) {
                                                            case 'clean': echo 'success'; break;
                                                            case 'dirty': echo 'danger'; break;
                                                            case 'in_progress': echo 'warning'; break;
                                                            case 'out_of_service': echo 'secondary'; break;
                                                        }
                                                    ?>">
                                                        <?php echo ucfirst(str_replace('_',' ',$room->cleaning_status)); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php
                                                        switch($room->maintenance_status) {
                                                            case 'ok': echo 'success'; break;
                                                            case 'needs_attention': echo 'warning'; break;
                                                            case 'under_maintenance': echo 'danger'; break;
                                                        }
                                                    ?>">
                                                        <?php echo ucfirst(str_replace('_',' ',$room->maintenance_status)); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url('staff/update_cleaning_status/'.$room->id); ?>" class="btn btn-sm btn-outline-primary">Update Cleaning</a>
                                                    <a href="<?php echo base_url('staff/update_maintenance_status/'.$room->id); ?>" class="btn btn-sm btn-outline-warning ms-1">Update Maintenance</a>
                                                    <a href="<?php echo base_url('staff/maintenance_requests/'.$room->id); ?>" class="btn btn-sm btn-outline-danger ms-1">Requests</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
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