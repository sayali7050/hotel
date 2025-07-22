<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<div class="container py-5">
  <h2 class="mb-4"><i class="fas fa-user"></i> My Profile</h2>
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
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="first_name" class="form-label">First Name *</label>
          <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo set_value('first_name', $user->first_name); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="last_name" class="form-label">Last Name *</label>
          <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo set_value('last_name', $user->last_name); ?>" required>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="email" class="form-label">Email *</label>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email', $user->email); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="phone" class="form-label">Phone *</label>
          <input type="text" class="form-control" id="phone" name="phone" value="<?php echo set_value('phone', $user->phone); ?>" required>
        </div>
      </div>
      <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="<?php echo set_value('address', $user->address); ?>">
      </div>
      <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
  </div>
</div>
<style>
.card { border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #e0e0e0; }
</style>
<?php $this->load->view('footer'); ?> 