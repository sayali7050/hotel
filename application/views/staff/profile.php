<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<body>
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
</body>
<main id="main-content" role="main">
  <div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-user"></i> My Profile</h2>
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
    <div class="card p-4 mb-4">
      <form action="<?= site_url('staff/update_profile') ?>" method="post" aria-label="Update your profile">
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
</main>
<style>
.card { border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #e0e0e0; }
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