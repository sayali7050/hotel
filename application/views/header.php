<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - LuxuryHotel Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="<?php echo base_url('/assets/img/favicon.png') ?>" rel="icon">
  <link href="<?php echo base_url('/assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/aos/aos.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/swiper/swiper-bundle.min.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/glightbox/css/glightbox.min.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/drift-zoom/drift-basic.css') ?>" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?php echo base_url('assets/css/main.css') ?>   " rel="stylesheet">

  <!-- =======================================================
  * Template Name: LuxuryHotel
  * Template URL: https://bootstrapmade.com/luxury-hotel-bootstrap-template/
  * Updated: Jun 30 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header fixed-top" style="background: linear-gradient(90deg, #0072ff 0%, #00c97b 100%);" role="banner">
    <style>
      .header .navmenu a { color: #fff !important; font-weight: 500; }
      .header .navmenu a.active { color: #ffd700 !important; }
      @media (max-width: 991px) {
        .header .container { flex-direction: column !important; align-items: stretch !important; }
        .header .logo { margin-bottom: 10px; }
        .header .navmenu { width: 100%; }
        .header .d-flex.align-items-center { justify-content: flex-end !important; margin-top: 10px; }
      }
    </style>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: transparent;" role="navigation" aria-label="Main site navigation">
      <div class="container position-relative">
        <a href="<?php echo base_url(); ?>" class="logo d-flex align-items-center me-auto me-xl-0" aria-label="LuxuryHotel Home">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="<?php echo base_url('assets/img/logo.webp') ?>" alt=""> -->
          <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="#000000" style="height:32px;width:32px;" aria-hidden="true" focusable="false">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
              <g id="a"></g>
              <g id="b">
                <path d="M21,4H11c-1.1046,0-2,.8954-2,2V28h4v-7c0-.5523,.4477-1,1-1h4c.5523,0,1,.4477,1,1v7h4V6c0-1.1046-.8954-2-2-2Z" style="fill:#f2ebe2;"></path>
                <path d="M30.1416,10.0103l-6.1416-.8774v-3.1329c0-1.6543-1.3457-3-3-3H11c-1.6543,0-3,1.3457-3,3v3.1329l-6.1416,.8774c-.4922,.0703-.8584,.4922-.8584,.9897V28c0,.5522,.4473,1,1,1H30c.5527,0,1-.4478,1-1V11c0-.4976-.3662-.9194-.8584-.9897ZM11,5h10c.5518,0,1,.4487,1,1V27h-2v-6c0-1.103-.8975-2-2-2h-4c-1.1025,0-2,.897-2,2v6h-2V6c0-.5513,.4482-1,1-1Zm7,22h-4v-6h4v6ZM3,11.8672l5-.7144v15.8472H3V11.8672Zm26,15.1328h-5V11.1528l5,.7144v15.1328ZM15,15.5v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5Zm3-7v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5Zm7.5,7v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5Zm0,3v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5ZM6.5,14.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5Zm0,3v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5Zm5.5-2v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5Zm0-7v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5Zm3,0v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5Zm3,3.5v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5Zm-6,0v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5Zm3,0v-1c0-.2762,.2239-.5,.5-.5h1c.2761,0,.5,.2238,.5,.5v1c0,.2761-.2239,.5-.5,.5h-1c-.2761,0-.5-.2239-.5-.5Z" style="fill:#917a5a;"></path>
              </g>
            </g>
          </svg>
          <h1 class="sitename">LuxuryHotel</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0" role="menubar">
            <li class="nav-item"><a class="nav-link active" href="<?php echo base_url(); ?>" role="menuitem">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('about'); ?>" role="menuitem">About</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('rooms'); ?>" role="menuitem">Rooms</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('amenities'); ?>" role="menuitem">Amenities</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('location'); ?>" role="menuitem">Location</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">Pages</a>
              <ul class="dropdown-menu" aria-labelledby="pagesDropdown" role="menu">
                <li><a class="dropdown-item" href="<?php echo base_url('room-details'); ?>" role="menuitem">Room Details</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('restaurant'); ?>" role="menuitem">Restaurant</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('offers'); ?>" role="menuitem">Offers</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('events'); ?>" role="menuitem">Events</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('gallery'); ?>" role="menuitem">Gallery</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('booking'); ?>" role="menuitem">Booking</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('terms'); ?>" role="menuitem">Terms Page</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('privacy'); ?>" role="menuitem">Privacy Page</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('starter-page'); ?>" role="menuitem">Starter Page</a></li>
              </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('contact'); ?>" role="menuitem">Contact</a></li>
          </ul>
          <div class="d-flex align-items-center ms-lg-3 mt-3 mt-lg-0" role="navigation" aria-label="User menu">
            <?php $CI =& get_instance(); ?>
            <?php if($CI->session->userdata('logged_in')): ?>
              <div class="dropdown me-3">
                <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-user"></i> <?php echo $CI->session->userdata('username'); ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="userDropdown" role="menu">
                  <li><a class="dropdown-item" href="<?php echo base_url('customer/dashboard'); ?>" role="menuitem">My Dashboard</a></li>
                  <li><a class="dropdown-item" href="<?php echo base_url('customer/my-bookings'); ?>" role="menuitem">My Bookings</a></li>
                  <li><a class="dropdown-item" href="<?php echo base_url('customer/profile'); ?>" role="menuitem">Profile</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="<?php echo base_url('logout'); ?>" role="menuitem">Logout</a></li>
                </ul>
              </div>
            <?php else: ?>
              <div class="dropdown me-2">
                <button class="btn btn-primary dropdown-toggle" type="button" id="loginDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-sign-in-alt"></i> Login
                </button>
                <ul class="dropdown-menu" aria-labelledby="loginDropdown" role="menu">
                  <li><a class="dropdown-item" href="<?php echo base_url('auth/admin_login'); ?>" role="menuitem">Login as Admin</a></li>
                  <li><a class="dropdown-item" href="<?php echo base_url('auth/staff_login'); ?>" role="menuitem">Login as Staff</a></li>
                  <li><a class="dropdown-item" href="<?php echo base_url('auth/customer_login'); ?>" role="menuitem">Login as Customer</a></li>
                </ul>
              </div>
              <a class="btn btn-outline-light me-2 mb-2 mb-lg-0" href="<?php echo base_url('auth/customer_register'); ?>">Register</a>
            <?php endif; ?>
            <a class="btn-getstarted d-none d-sm-block ms-2" href="<?php echo base_url('booking'); ?>">Book Now</a>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <style>
  .navbar-nav .nav-link:focus, .dropdown-menu .dropdown-item:focus, .btn:focus {
    outline: 2px solid #0072ff;
    background: #e6f0ff;
  }
  </style>