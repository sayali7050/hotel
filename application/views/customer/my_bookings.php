<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<div class="container py-5">
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
  <div class="row">
    <div class="col-md-3 mb-4 mb-md-0">
      <?php $this->load->view('customer/sidebar'); ?>
    </div>
    <div class="col-md-9" style="border-left: 1px solid #e0e0e0; min-height: 80vh;">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">My Bookings</h2>
        <a href="<?php echo base_url('rooms'); ?>" class="btn btn-primary btn-lg">
          <i class="fas fa-plus-circle"></i> Browse Rooms / Make New Booking
        </a>
      </div>
        <div class="row">
            <div class="col-12">
                
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success" role="alert" aria-live="polite">
                        <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger" role="alert" aria-live="assertive">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($bookings): ?>
            <div class="row">
                <?php foreach($bookings as $booking): ?>
                    <div class="col-12">
                        <div class="booking-card p-4">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h5 class="mb-1">Room <?php echo $booking->room_number; ?></h5>
                                    <p class="text-muted mb-0"><?php echo $booking->room_type; ?></p>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-1"><strong>Check-in:</strong></p>
                                    <p class="text-muted mb-0"><?php echo date('M d, Y', strtotime($booking->check_in_date)); ?></p>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-1"><strong>Check-out:</strong></p>
                                    <p class="text-muted mb-0"><?php echo date('M d, Y', strtotime($booking->check_out_date)); ?></p>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-1"><strong>Total:</strong></p>
                                    <p class="text-muted mb-0">$<?php echo number_format($booking->total_amount, 2); ?></p>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-1"><strong>Status:</strong></p>
                                    <?php 
                                    $statusClass = '';
                                    $statusIcon = '';
                                    switch($booking->status) {
                                        case 'pending':
                                            $statusClass = 'warning';
                                            $statusIcon = 'fas fa-clock';
                                            break;
                                        case 'confirmed':
                                            $statusClass = 'success';
                                            $statusIcon = 'fas fa-check-circle';
                                            break;
                                        case 'checked_in':
                                            $statusClass = 'info';
                                            $statusIcon = 'fas fa-sign-in-alt';
                                            break;
                                        case 'checked_out':
                                            $statusClass = 'secondary';
                                            $statusIcon = 'fas fa-sign-out-alt';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'danger';
                                            $statusIcon = 'fas fa-times-circle';
                                            break;
                                        default:
                                            $statusClass = 'secondary';
                                            $statusIcon = 'fas fa-question-circle';
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass; ?> status-badge">
                                        <i class="<?php echo $statusIcon; ?>"></i> <?php echo ucfirst(str_replace('_', ' ', $booking->status)); ?>
                                    </span>
                                </div>
                                <div class="col-md-1">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?php echo base_url('customer/view_booking/'.$booking->id); ?>" aria-label="View booking <?php echo $booking->id ?>">
                                                <i class="fas fa-eye"></i> View Details
                                            </a></li>
                                            <?php if($booking->status == 'pending' || $booking->status == 'confirmed'): ?>
                                                <li><a class="dropdown-item" href="<?php echo base_url('customer/edit_booking/'.$booking->id); ?>" aria-label="Edit booking <?php echo $booking->id ?>">
                                                    <i class="fas fa-edit"></i> Edit Booking
                                                </a></li>
                                                <li><a class="dropdown-item text-danger" href="<?php echo base_url('customer/cancel_booking/'.$booking->id); ?>" aria-label="Cancel booking <?php echo $booking->id ?>" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                    <i class="fas fa-times"></i> Cancel Booking
                                                </a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
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
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No bookings found</h4>
                        <p class="text-muted">You haven't made any bookings yet.</p>
                        <a href="<?php echo base_url('customer/rooms'); ?>" class="btn btn-primary">
                            <i class="fas fa-bed"></i> Browse Rooms
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
  </div>
</div>
<?php $this->load->view('footer'); ?> 
<style>
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
</style> 