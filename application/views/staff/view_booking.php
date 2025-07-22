<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<div class="container py-5">
  <a href="<?php echo base_url('staff/bookings'); ?>" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to Bookings</a>
  <div class="row">
    <div class="col-lg-8">
      <div class="card p-4 mb-4">
        <h3 class="mb-3"><i class="fas fa-info-circle"></i> Booking Details</h3>
        <?php if($booking): ?>
          <p><strong>Guest:</strong> <?php echo $booking->first_name . ' ' . $booking->last_name; ?></p>
          <p><strong>Email:</strong> <?php echo $booking->email; ?></p>
          <p><strong>Phone:</strong> <?php echo $booking->phone; ?></p>
          <p><strong>Room:</strong> <?php echo $booking->room_number . ' (' . $booking->room_type . ')'; ?></p>
          <p><strong>Status:</strong> <span class="badge bg-<?php echo $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'checked_in' ? 'info' : ($booking->status == 'checked_out' ? 'secondary' : 'danger'))); ?>"> <?php echo ucfirst(str_replace('_', ' ', $booking->status)); ?> </span></p>
          <p><strong>Check-in:</strong> <?php echo date('M d, Y', strtotime($booking->check_in_date)); ?></p>
          <p><strong>Check-out:</strong> <?php echo date('M d, Y', strtotime($booking->check_out_date)); ?></p>
          <p><strong>Total:</strong> $<?php echo number_format($booking->total_amount, 2); ?></p>
          <p><strong>Special Requests:</strong> <?php echo $booking->special_requests ? nl2br($booking->special_requests) : 'None'; ?></p>
          <form method="post" action="<?php echo base_url('staff/update_booking_status/'.$booking->id); ?>" class="mt-4">
            <div class="mb-3">
              <label for="status" class="form-label">Update Status</label>
              <select name="status" id="status" class="form-select">
                <option value="pending" <?php if($booking->status=='pending') echo 'selected'; ?>>Pending</option>
                <option value="confirmed" <?php if($booking->status=='confirmed') echo 'selected'; ?>>Confirmed</option>
                <option value="checked_in" <?php if($booking->status=='checked_in') echo 'selected'; ?>>Checked In</option>
                <option value="checked_out" <?php if($booking->status=='checked_out') echo 'selected'; ?>>Checked Out</option>
                <option value="cancelled" <?php if($booking->status=='cancelled') echo 'selected'; ?>>Cancelled</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
          </form>
          <?php if($booking->status == 'confirmed'): ?>
            <a href="<?php echo base_url('staff/check_in/'.$booking->id); ?>" class="btn btn-success mt-3" onclick="return confirm('Check in this guest?')"><i class="fas fa-sign-in-alt"></i> Check In</a>
          <?php endif; ?>
          <?php if($booking->status == 'checked_in'): ?>
            <a href="<?php echo base_url('staff/check_out/'.$booking->id); ?>" class="btn btn-warning mt-3" onclick="return confirm('Check out this guest?')"><i class="fas fa-sign-out-alt"></i> Check Out</a>
          <?php endif; ?>
        <?php else: ?>
          <div class="alert alert-danger">Booking not found.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<style>
.card { border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #e0e0e0; }
</style>
<?php $this->load->view('footer'); ?> 