<?php $CI =& get_instance(); ?>
<header class="header" role="banner">
  <nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation" aria-label="Admin top navigation">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="<?php echo base_url('admin/dashboard'); ?>">
        <img src="<?= base_url('assets/img/logo.webp') ?>" alt="Hotel Admin Logo" class="logo me-2">
        <span class="ms-2 fw-bold">Hotel Admin</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="adminNavbar">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="adminUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-circle"></i> <?php echo $CI->session->userdata('username'); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminUserDropdown">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?php echo base_url('logout'); ?>">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
<style>
.navbar-nav .nav-link:focus, .dropdown-menu .dropdown-item:focus {
  outline: 2px solid #0072ff;
  background: #e6f0ff;
}
</style> 