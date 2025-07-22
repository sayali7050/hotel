<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<div class="container py-5">
  <h2 class="mb-4"><i class="fas fa-bed"></i> All Rooms</h2>
  <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Room #</th>
              <th>Type</th>
              <th>Capacity</th>
              <th>Price/Night</th>
              <th>Status</th>
              <th>Update Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if($rooms): foreach($rooms as $room): ?>
              <tr>
                <td><?php echo $room->room_number; ?></td>
                <td><?php echo $room->room_type; ?></td>
                <td><?php echo $room->capacity; ?></td>
                <td>$<?php echo number_format($room->price_per_night, 2); ?></td>
                <td><span class="badge bg-<?php echo $room->status == 'available' ? 'success' : ($room->status == 'occupied' ? 'warning' : ($room->status == 'maintenance' ? 'danger' : 'secondary')); ?>"><?php echo ucfirst($room->status); ?></span></td>
                <td>
                  <form method="post" action="<?php echo base_url('staff/update_room_status/'.$room->id); ?>" class="d-flex align-items-center gap-2">
                    <select name="status" class="form-select form-select-sm">
                      <option value="available" <?php if($room->status=='available') echo 'selected'; ?>>Available</option>
                      <option value="occupied" <?php if($room->status=='occupied') echo 'selected'; ?>>Occupied</option>
                      <option value="maintenance" <?php if($room->status=='maintenance') echo 'selected'; ?>>Maintenance</option>
                      <option value="reserved" <?php if($room->status=='reserved') echo 'selected'; ?>>Reserved</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-bed fa-2x mb-3"></i><br>No rooms found.</td></tr>
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