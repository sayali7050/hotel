<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Welcome Message Page -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Welcome - Hotel</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="<?php echo base_url('application/assets/img/favicon.png'); ?>" rel="icon">
  <link href="<?php echo base_url('application/assets/img/apple-touch-icon.png'); ?>" rel="apple-touch-icon">
  <link href="<?php echo base_url('/assets/vendor/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('/assets/vendor/bootstrap-icons/bootstrap-icons.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('application/assets/vendor/aos/aos.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('application/assets/vendor/glightbox/css/glightbox.min.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('application/assets/vendor/swiper/swiper-bundle.min.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('application/assets/css/main.css'); ?>" rel="stylesheet">
</head>
<body>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="<?php echo base_url(); ?>" class="logo d-flex align-items-center">
        <img src="<?php echo base_url('application/assets/img/logo.webp'); ?>" alt="Hotel Logo">
        <span>Hotel</span>
      </a>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="<?php echo base_url(); ?>">Home</a></li>
          <li><a href="<?php echo base_url('about'); ?>">About</a></li>
          <li><a href="<?php echo base_url('rooms'); ?>">Rooms</a></li>
          <li><a href="<?php echo base_url('amenities'); ?>">Amenities</a></li>
          <li><a href="<?php echo base_url('gallery'); ?>">Gallery</a></li>
          <li><a href="<?php echo base_url('events'); ?>">Events</a></li>
          <li><a href="<?php echo base_url('restaurant'); ?>">Restaurant</a></li>
          <li><a href="<?php echo base_url('contact'); ?>">Contact</a></li>
          <li><a href="<?php echo base_url('booking'); ?>" class="book-a-table-btn">Book Now</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
  </header>
  <!-- End Header -->

  <main id="main">
    <!-- ======= Welcome Section ======= -->
    <section id="welcome" class="welcome">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Welcome to Our Hotel</h2>
          <p>Thank you for choosing us. We are delighted to have you as our guest and look forward to making your stay memorable.</p>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <p>Our hotel offers a blend of luxury, comfort, and exceptional service. Whether you are here for business or leisure, we strive to provide you with the best experience possible.</p>
            <p>Explore our website to learn more about our rooms, amenities, dining options, and special offers. If you need any assistance, our team is always here to help.</p>
            <a href="<?php echo base_url('rooms'); ?>" class="btn btn-primary mt-3">View Rooms</a>
          </div>
        </div>
      </div>
    </section>
    <!-- End Welcome Section -->
  </main>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Hotel</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by Hotel Team
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="<?php echo base_url('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?php echo base_url('application/assets/vendor/aos/aos.js'); ?>"></script>
  <script src="<?php echo base_url('application/assets/vendor/glightbox/js/glightbox.min.js'); ?>"></script>
  <script src="<?php echo base_url('application/assets/vendor/isotope-layout/isotope.pkgd.min.js'); ?>"></script>
  <script src="<?php echo base_url('application/assets/vendor/swiper/swiper-bundle.min.js'); ?>"></script>
  <script src="<?php echo base_url('application/assets/vendor/php-email-form/validate.js'); ?>"></script>
  <script src="<?php echo base_url('application/assets/js/main.js'); ?>"></script>
</body>
</html>



