<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(79, 172, 254, 0.1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(79, 172, 254, 0.2);
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
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            border: 1px solid rgba(79, 172, 254, 0.1);
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php $this->load->view('admin/includes/sidebar'); ?>

    <!-- Main Content -->
    <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
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
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $total_users; ?></h3>
                                    <p class="text-muted mb-0">Total Customers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success me-3">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $total_staff; ?></h3>
                                    <p class="text-muted mb-0">Total Staff</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info me-3">
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
                                <div class="stat-icon bg-warning me-3">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $total_bookings; ?></h3>
                                    <p class="text-muted mb-0">Total Bookings</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $available_rooms; ?></h3>
                                    <p class="text-muted mb-0">Available Rooms</p>
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
                                    <p class="text-muted mb-0">Pending Bookings</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info me-3">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">$<?php echo number_format($total_revenue, 2); ?></h3>
                                    <p class="text-muted mb-0">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="table-card">
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
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($recent_bookings as $booking): ?>
                                                    <tr>
                                                        <td><?php echo $booking->first_name . ' ' . $booking->last_name; ?></td>
                                                        <td><?php echo $booking->room_number; ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary'); ?>">
                                                                <?php echo ucfirst($booking->status); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M d, Y', strtotime($booking->created_at)); ?></td>
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

                    <div class="col-md-6 mb-4">
                        <div class="table-card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-users"></i> Recent Users</h5>
                            </div>
                            <div class="card-body">
                                <?php if($recent_users): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($recent_users as $user): ?>
                                                    <tr>
                                                        <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                                                        <td><?php echo $user->email; ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $user->role == 'admin' ? 'danger' : ($user->role == 'staff' ? 'warning' : 'info'); ?>">
                                                                <?php echo ucfirst($user->role); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M d, Y', strtotime($user->created_at)); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center">No recent users</p>
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