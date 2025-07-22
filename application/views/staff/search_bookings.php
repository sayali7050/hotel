<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<div class="container py-5">
  <h2 class="mb-4"><i class="fas fa-search"></i> Search Bookings</h2>
  <form class="row g-3 mb-4" method="get" action="">
    <div class="col-md-4">
      <input type="text" class="form-control" name="search" placeholder="Guest name, email, or room #" value="<?php echo $this->input->get('search'); ?>">
    </div>
    <div class="col-md-3">
      <select class="form-select" name="status">
        <option value="">All Statuses</option>
        <option value="pending" <?php if($this->input->get('status')=='pending') echo 'selected'; ?>>Pending</option>
        <option value="confirmed" <?php if($this->input->get('status')=='confirmed') echo 'selected'; ?>>Confirmed</option>
        <option value="checked_in" <?php if($this->input->get('status')=='checked_in') echo 'selected'; ?>>Checked In</option>
        <option value="checked_out" <?php if($this->input->get('status')=='checked_out') echo 'selected'; ?>>Checked Out</option>
        <option value="cancelled" <?php if($this->input->get('status')=='cancelled') echo 'selected'; ?>>Cancelled</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Search</button>
    </div>
  </form>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Guest</th>
              <th>Room</th>
              <th>Status</th>
              <th>Check-in</th>
              <th>Check-out</th>
              <th>Total</th>
              <th>Actions</th>
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
                  <a href="<?php echo base_url('staff/view_booking/'.$booking->id); ?>" class="btn btn-sm btn-info mb-1" title="View"><i class="fas fa-eye"></i></a>
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
.card { border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #e0e0e0; }
th, td { vertical-align: middle !important; }
</style>
<?php $this->load->view('footer'); ?> 