<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Privacy Policy Page -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Privacy Policy - Hotel</title>
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
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
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

  <main id="main-content" role="main">
    <!-- ======= Privacy Policy Section ======= -->
    <section id="privacy" class="privacy" aria-label="Privacy Policy" role="region">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Privacy Policy</h2>
          <p>Your privacy is important to us. This policy explains how we handle your personal information.</p>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <h4>Information Collection</h4>
            <p>We collect personal information that you provide to us when making a booking, contacting us, or subscribing to our newsletter.</p>
            <h4>Use of Information</h4>
            <p>Your information is used to process bookings, respond to inquiries, and send promotional materials if you have opted in.</p>
            <h4>Data Security</h4>
            <p>We implement security measures to protect your data from unauthorized access, alteration, or disclosure.</p>
            <h4>Third-Party Disclosure</h4>
            <p>We do not sell, trade, or otherwise transfer your personal information to outside parties except as required by law.</p>
            <h4>Policy Updates</h4>
            <p>We may update this privacy policy from time to time. Changes will be posted on this page.</p>
          </div>
        </div>
      </div>
    </section>
    <!-- End Privacy Policy Section -->
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
</body>
</html>


