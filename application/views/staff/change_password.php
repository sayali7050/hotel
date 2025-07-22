<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<div class="container py-5">
  <h2 class="mb-4"><i class="fas fa-key"></i> Change Password</h2>
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
  <div class="card p-4 mb-4">
    <form method="post" action="">
      <div class="mb-3">
        <label for="current_password" class="form-label">Current Password *</label>
        <input type="password" class="form-control" id="current_password" name="current_password" required>
      </div>
      <div class="mb-3">
        <label for="new_password" class="form-label">New Password *</label>
        <input type="password" class="form-control" id="new_password" name="new_password" required>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm New Password *</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      </div>
      <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
  </div>
</div>
<style>
.card { border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #e0e0e0; }
</style>
<?php $this->load->view('footer'); ?> 