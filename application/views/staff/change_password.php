<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<body>
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
</body>
<main id="main-content" role="main">
  <div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-key"></i> Change Password</h2>
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
      <form action="<?= site_url('staff/change_password') ?>" method="post" aria-label="Change your password">
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