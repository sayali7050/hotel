<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Offers Page -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Offers - Hotel</title>
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
    <!-- ======= Offers Section ======= -->
    <section id="offers" class="offers">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Special Offers</h2>
          <p>Discover our exclusive deals and packages for your perfect stay.</p>
        </div>
        <div class="row">
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="offer-box">
              <img src="<?php echo base_url('application/assets/img/hotel/room-3.webp'); ?>" class="img-fluid" alt="Early Bird Offer">
              <h3>Early Bird Offer</h3>
              <p>Book your stay 30 days in advance and get 20% off on all room types.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="offer-box">
              <img src="<?php echo base_url('application/assets/img/hotel/room-7.webp'); ?>" class="img-fluid" alt="Family Package">
              <h3>Family Package</h3>
              <p>Enjoy complimentary breakfast and kids stay free with our family package.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="offer-box">
              <img src="<?php echo base_url('application/assets/img/hotel/room-12.webp'); ?>" class="img-fluid" alt="Romantic Getaway">
              <h3>Romantic Getaway</h3>
              <p>Surprise your loved one with a romantic setup and late checkout.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Offers Section -->
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


