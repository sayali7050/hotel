<?php $this->load->view('admin/includes/header'); ?>
<?php $this->load->view('admin/includes/sidebar'); ?>
<body>
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
<main id="main-content" role="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clock"></i> Waitlist Management</h2>
    </div>
    <div class="table-card p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" role="table" aria-label="Waitlist Management">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Room Type</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Adults</th>
                        <th>Children</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($waitlist): foreach($waitlist as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry->guest_name); ?></td>
                        <td><?php echo htmlspecialchars($entry->guest_email); ?></td>
                        <td><?php echo htmlspecialchars($entry->guest_phone); ?></td>
                        <td><?php echo htmlspecialchars($entry->room_type); ?></td>
                        <td><?php echo htmlspecialchars($entry->check_in_date); ?></td>
                        <td><?php echo htmlspecialchars($entry->check_out_date); ?></td>
                        <td><?php echo (int)$entry->adults; ?></td>
                        <td><?php echo (int)$entry->children; ?></td>
                        <td><span class="badge bg-<?php echo $entry->status == 'waiting' ? 'warning' : ($entry->status == 'notified' ? 'info' : ($entry->status == 'booked' ? 'success' : 'secondary')); ?>"><?php echo ucfirst($entry->status); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($entry->created_at)); ?></td>
                        <td>
                            <?php if($entry->status == 'waiting'): ?>
                                <a href="<?php echo base_url('admin/promote_waitlist/'.$entry->id); ?>" class="btn btn-sm btn-success mb-1" aria-label="Promote waitlist entry <?php echo $entry->id ?>">Promote</a>
                                <a href="<?php echo base_url('admin/mark_waitlist_notified/'.$entry->id); ?>" class="btn btn-sm btn-info mb-1" aria-label="Mark waitlist entry <?php echo $entry->id ?> as notified">Mark Notified</a>
                                <a href="<?php echo base_url('admin/mark_waitlist_cancelled/'.$entry->id); ?>" class="btn btn-sm btn-danger mb-1" aria-label="Cancel waitlist entry <?php echo $entry->id ?>">Cancel</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="11" class="text-center text-muted">No waitlist entries found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php $this->load->view('footer'); ?>
<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success" role="alert" aria-live="polite">
    <?= $this->session->flashdata('success') ?>
  </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger" role="alert" aria-live="assertive">
    <?= $this->session->flashdata('error') ?>
  </div>
<?php endif; ?>
<style>
.table-card { background: white; border-radius: 15px; box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1); border: 1px solid rgba(79, 172, 254, 0.1); }
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