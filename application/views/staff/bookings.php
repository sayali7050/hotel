<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<body>
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
<div class="container py-5">
  <h2 class="mb-4"><i class="fas fa-calendar-check"></i> All Bookings</h2>
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
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped" role="table" aria-label="Staff Bookings">
          <thead class="table-light">
            <tr>
              <th>Guest</th>
              <th>Room</th>
              <th>Status</th>
              <th>Check-in</th>
              <th>Check-out</th>
              <th>Total</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if($bookings): foreach($bookings as $booking): ?>
              <tr>
                <td><?php echo $booking->first_name . ' ' . $booking->last_name; ?></td>
                <td><?php echo $booking->room_number . ' (' . $booking->room_type . ')'; ?></td>
                <td>
                  <span class="badge bg-<?php echo $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'checked_in' ? 'info' : ($booking->status == 'checked_out' ? 'secondary' : 'danger'))); ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $booking->status)); ?>
                  </span>
                </td>
                <td><?php echo date('M d, Y', strtotime($booking->check_in_date)); ?></td>
                <td><?php echo date('M d, Y', strtotime($booking->check_out_date)); ?></td>
                <td>$<?php echo number_format($booking->total_amount, 2); ?></td>
                <td>
                  <a href="<?php echo base_url('staff/view_booking/'.$booking->id); ?>" class="btn btn-sm btn-info mb-1" title="View" aria-label="View booking <?php echo $booking->id ?>"><i class="fas fa-eye"></i></a>
                  <?php if($booking->status == 'confirmed'): ?>
                    <a href="<?php echo base_url('staff/check_in/'.$booking->id); ?>" class="btn btn-sm btn-success mb-1" title="Check In" onclick="return confirm('Check in this guest?')"><i class="fas fa-sign-in-alt"></i></a>
                  <?php endif; ?>
                  <?php if($booking->status == 'checked_in'): ?>
                    <a href="<?php echo base_url('staff/check_out/'.$booking->id); ?>" class="btn btn-sm btn-warning mb-1" title="Check Out" onclick="return confirm('Check out this guest?')"><i class="fas fa-sign-out-alt"></i></a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-calendar-check fa-2x mb-3"></i><br>No bookings found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<style>
.card {
  border-radius: 12px;
  box-shadow: 0 2px 16px rgba(0,0,0,0.06);
  border: 1px solid #e0e0e0;
}
th, td { vertical-align: middle !important; }
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
<?php $this->load->view('footer'); ?> 