<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<div class="container py-5">
  <div class="row">
    <div class="col-md-3 mb-4 mb-md-0">
      <?php $this->load->view('customer/sidebar'); ?>
    </div>
    <div class="col-md-9">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-check"></i> Booking Details</h2>
        <a href="<?php echo base_url('customer/my_bookings'); ?>" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Back to My Bookings
        </a>
      </div>
      <?php if($booking): ?>
        <div class="row g-4 flex-lg-row flex-column-reverse">
          <div class="col-lg-8">
            <div class="booking-detail-card p-4 mb-4 bg-white rounded shadow-sm">
              <!-- Booking Status -->
              <div class="text-center mb-4">
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
                <span class="badge bg-<?php echo $statusClass; ?> status-badge fs-6 px-3 py-2">
                  <i class="<?php echo $statusIcon; ?>"></i> 
                  <?php echo ucfirst(str_replace('_', ' ', $booking->status)); ?>
                </span>
              </div>
              <!-- Room Information -->
              <div class="info-section mb-4">
                <h5 class="mb-3"><i class="fas fa-bed"></i> Room Information</h5>
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>Room Number:</strong> <?php echo $booking->room_number; ?></p>
                    <p><strong>Room Type:</strong> <?php echo $booking->room_type; ?></p>
                    <?php if(isset($booking->capacity)): ?>
                      <p><strong>Capacity:</strong> <?php echo $booking->capacity; ?> person<?php echo $booking->capacity > 1 ? 's' : ''; ?></p>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Price per Night:</strong> $<?php echo number_format($booking->price_per_night, 2); ?></p>
                    <?php if(isset($booking->amenities) && $booking->amenities): ?>
                      <p><strong>Amenities:</strong> <?php echo $booking->amenities; ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <!-- Booking Dates -->
              <div class="info-section mb-4">
                <h5 class="mb-3"><i class="fas fa-calendar-alt"></i> Booking Dates</h5>
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>Check-in Date:</strong></p>
                    <p class="text-muted mb-2"><?php echo date('l, F d, Y', strtotime($booking->check_in_date)); ?></p>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Check-out Date:</strong></p>
                    <p class="text-muted mb-2"><?php echo date('l, F d, Y', strtotime($booking->check_out_date)); ?></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>Number of Nights:</strong></p>
                    <?php 
                    $check_in = new DateTime($booking->check_in_date);
                    $check_out = new DateTime($booking->check_out_date);
                    $nights = $check_in->diff($check_out)->days;
                    ?>
                    <p class="text-muted mb-2"><?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?></p>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Booking Date:</strong></p>
                    <p class="text-muted mb-2"><?php echo date('F d, Y', strtotime($booking->created_at)); ?></p>
                  </div>
                </div>
              </div>
              <!-- Special Requests -->
              <?php if($booking->special_requests): ?>
                <div class="info-section mb-4">
                  <h5 class="mb-3"><i class="fas fa-comment"></i> Special Requests</h5>
                  <p class="text-muted"><?php echo nl2br($booking->special_requests); ?></p>
                </div>
              <?php endif; ?>
              <!-- Actions -->
              <div class="text-center mt-4">
                <?php if($booking->status == 'pending' || $booking->status == 'confirmed'): ?>
                  <a href="<?php echo base_url('customer/cancel_booking/'.$booking->id); ?>" 
                     class="btn btn-danger" 
                     onclick="return confirm('Are you sure you want to cancel this booking?')">
                    <i class="fas fa-times"></i> Cancel Booking
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <!-- Booking Summary -->
            <div class="booking-detail-card p-4 mb-4 bg-white rounded shadow-sm">
              <h5 class="mb-3"><i class="fas fa-receipt"></i> Booking Summary</h5>
              <hr>
              <div class="d-flex justify-content-between mb-2">
                <span>Room Rate:</span>
                <span>$<?php echo number_format($booking->price_per_night, 2); ?> × <?php echo $nights; ?> nights</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Subtotal:</span>
                <span>$<?php echo number_format($booking->price_per_night * $nights, 2); ?></span>
              </div>
              <hr>
              <div class="d-flex justify-content-between">
                <strong>Total Amount:</strong>
                <strong>$<?php echo number_format($booking->total_amount, 2); ?></strong>
              </div>
            </div>
            <!-- Contact Information -->
            <div class="booking-detail-card p-4 bg-white rounded shadow-sm">
              <h5 class="mb-3"><i class="fas fa-phone"></i> Need Help?</h5>
              <p class="text-muted">If you have any questions about your booking, please contact us:</p>
              <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
              <p><i class="fas fa-envelope"></i> support@hotel.com</p>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="text-center py-5">
          <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
          <h4 class="text-muted">Booking Not Found</h4>
          <p class="text-muted">The booking you're looking for doesn't exist or you don't have permission to view it.</p>
          <a href="<?php echo base_url('customer/my_bookings'); ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to My Bookings
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php $this->load->view('footer'); ?>
<style>
.booking-detail-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 16px rgba(0,0,0,0.06);
  border: 1px solid #e0e0e0;
}
.info-section h5 {
  color: #0072ff;
  font-weight: 600;
}
@media (max-width: 991px) {
  .booking-detail-card { margin-bottom: 1.5rem; }
}
</style> 