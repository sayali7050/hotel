<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-3 mb-4 mb-md-0">
            <?php $this->load->view('customer/sidebar'); ?>
        </div>
        <div class="col-md-9" style="border-left: 1px solid #e0e0e0; min-height: 80vh;">
            <div class="welcome-card mb-4">
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
            <?php if($CI->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $CI->session->flashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if($CI->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $CI->session->flashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
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
<?php $this->load->view('footer'); ?> 