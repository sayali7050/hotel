<body>
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
</body>
<main id="main-content" role="main">
<form action="<?= site_url('staff/edit_booking/'.$booking->id) ?>" method="post" aria-label="Edit booking details">
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