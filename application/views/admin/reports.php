<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reports</title>
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
        .card-report { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            border: 1px solid rgba(79, 172, 254, 0.1);
            padding: 30px; 
            margin-bottom: 30px; 
        }
        .stat-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; }
    </style>
</head>
<body>
    <?php $this->load->view('admin/includes/header'); ?>
    <!-- Include Sidebar -->
    <?php $this->load->view('admin/includes/sidebar'); ?>

    <!-- Main Content -->
    <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-chart-bar"></i> Reports & Analytics</h2>
            </div>
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card-report">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-icon bg-success me-3">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">$<?php echo number_format($total_revenue, 2); ?></h4>
                                <p class="text-muted mb-0">Total Revenue</p>
                            </div>
                        </div>
                        <div class="text-muted">Revenue generated from all bookings.</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card-report">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-icon bg-info me-3">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div>
                                <h4 class="mb-0"><?php echo isset($monthly_revenue) ? '$'.number_format($monthly_revenue, 2) : '$0.00'; ?></h4>
                                <p class="text-muted mb-0">Monthly Revenue</p>
                                <p class="text-muted mb-0">Occupancy Rate</p>
                            </div>
                        </div>
                        <div class="text-muted">Percentage of rooms occupied.</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card-report">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-icon bg-warning me-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <h4 class="mb-0"><?php echo array_sum($booking_stats); ?></h4>
                                <p class="text-muted mb-0">Total Bookings</p>
                            </div>
                        </div>
                        <div class="text-muted">Number of bookings made.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card-report">
                        <h5 class="mb-3"><i class="fas fa-chart-pie"></i> Booking Statistics</h5>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2"><?php echo $booking_stats['pending']; ?></span>
                                    <small>Pending</small>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2"><?php echo $booking_stats['confirmed']; ?></span>
                                    <small>Confirmed</small>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info me-2"><?php echo $booking_stats['checked_in']; ?></span>
                                    <small>Checked In</small>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2"><?php echo $booking_stats['checked_out']; ?></span>
                                    <small>Checked Out</small>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger me-2"><?php echo $booking_stats['cancelled']; ?></span>
                                    <small>Cancelled</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card-report text-center">
                        <h5 class="mb-3"><i class="fas fa-chart-line"></i> Revenue Trend</h5>
                        <div class="text-muted">[Chart Placeholder]</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 