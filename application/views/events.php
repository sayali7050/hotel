﻿<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Amenities - LuxuryHotel Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="<?php echo base_url('assets/img/favicon.png') ?>" rel="icon">
  <link href="<?php echo base_url('assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

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
  <link href="<?php echo base_url('assets/css/main.css') ?>" rel="stylesheet">

  <!-- =======================================================
  * Template Name: LuxuryHotel
  * Template URL: https://bootstrapmade.com/luxury-hotel-bootstrap-template/
  * Updated: Jun 30 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="amenities-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="<?php echo base_url('assets/img/logo.webp') ?>" alt=""> -->
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="#000000">
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

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?php echo base_url(); ?>">Home</a></li>
          <li><a href="<?php echo base_url('about'); ?>">About</a></li>
          <li><a href="<?php echo base_url('rooms'); ?>" >Rooms</a></li>
          <li><a href="<?php echo base_url('amenities'); ?>" >Amenities</a></li>
          <li><a href="<?php echo base_url('location'); ?>" >Location</a></li>
          <li class="dropdown"><a href="#"><span>Pages</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="<?php echo base_url('room-details'); ?>">Room Details</a></li>
              <li><a href="<?php echo base_url('restaurant'); ?>">Restaurant</a></li>
              <li><a href="<?php echo base_url('offers'); ?>">Offers</a></li>
              <li><a href="<?php echo base_url('events'); ?>">Events</a></li>
              <li><a href="<?php echo base_url('gallery'); ?>">Gallery</a></li>
              <li><a href="<?php echo base_url('booking'); ?>">Booking</a></li>
              <li><a href="<?php echo base_url('terms'); ?>">Terms Page</a></li>
              <li><a href="<?php echo base_url('privacy'); ?>">Privacy Page</a></li>
              <li><a href="<?php echo base_url('starter-page'); ?>">Starter Page</a></li>
            </ul>
          </li>
          <li><a href="<?php echo base_url('contact'); ?>" class="active">Contact</a></li>

          <li class="dropdown">
            <a href="#english">
              <svg class="icon" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_iconCarrier">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M4 0H6V2H10V4H8.86807C8.57073 5.66996 7.78574 7.17117 6.6656 8.35112C7.46567 8.73941 8.35737 8.96842 9.29948 8.99697L10.2735 6H12.7265L15.9765 16H13.8735L13.2235 14H9.77647L9.12647 16H7.0235L8.66176 10.9592C7.32639 10.8285 6.08165 10.3888 4.99999 9.71246C3.69496 10.5284 2.15255 11 0.5 11H0V9H0.5C1.5161 9 2.47775 8.76685 3.33437 8.35112C2.68381 7.66582 2.14629 6.87215 1.75171 6H4.02179C4.30023 6.43491 4.62904 6.83446 4.99999 7.19044C5.88743 6.33881 6.53369 5.23777 6.82607 4H0V2H4V0ZM12.5735 12L11.5 8.69688L10.4265 12H12.5735Z" fill="currentColor"></path>
                </g>
              </svg>
              <span>English</span>
              <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#french">French</a></li>
              <li><a href="#deutsch">Deutsch</a></li>
              <li><a href="#spanish">Spanish</a></li>
            </ul>
          </li>

        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <a class="btn-getstarted d-none d-sm-block" href="<?php echo base_url('booking'); ?>">Book Now</a>

    </div>
  </header>
  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(<?php echo base_url('assets/img/hotel/showcase-7.we'); ?>bp);">
      <div class="container position-relative">
        <h1>Events</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index,php">Home</a></li>
            <li class="current">Events</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Events Section -->
    <section id="events" class="events section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="event-showcase">
              <img src="<?php echo base_url('assets/img/hotel/event-1.webp'); ?>" alt="Event Space" class="img-fluid rounded">
              <div class="event-overlay">
                <div class="event-details">
                  <h3>Grand Conference Hall</h3>
                  <p>Up to 300 guests</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <div class="event-content">
              <h2>Memorable Events Begin Here</h2>
              <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>

              <div class="event-types">
                <div class="event-type" data-aos="fade-up" data-aos-delay="350">
                  <div class="icon">
                    <i class="bi bi-briefcase"></i>
                  </div>
                  <div class="content">
                    <h4>Corporate Events</h4>
                    <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.</p>
                  </div>
                </div>
                <div class="event-type" data-aos="fade-up" data-aos-delay="400">
                  <div class="icon">
                    <i class="bi bi-heart"></i>
                  </div>
                  <div class="content">
                    <h4>Weddings &amp; Celebrations</h4>
                    <p>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Cras ultricies ligula.</p>
                  </div>
                </div>
                <div class="event-type" data-aos="fade-up" data-aos-delay="450">
                  <div class="icon">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="content">
                    <h4>Private Functions</h4>
                    <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="event-spaces" data-aos="fade-up" data-aos-delay="500">
          <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
              <div class="space-card">
                <img src="<?php echo base_url('assets/img/hotel/event-3.webp'); ?>" alt="Meeting Room" class="img-fluid">
                <div class="card-content">
                  <h4>Imperial Ballroom</h4>
                  <div class="capacity-info">
                    <span><i class="bi bi-person-standing"></i> Theater: 250</span>
                    <span><i class="bi bi-table"></i> Banquet: 180</span>
                  </div>
                  <div class="amenities">
                    <span class="amenity"><i class="bi bi-projector"></i> AV Equipment</span>
                    <span class="amenity"><i class="bi bi-wifi"></i> High-Speed WiFi</span>
                    <span class="amenity"><i class="bi bi-mic"></i> Sound System</span>
                  </div>
                  <a href="#" class="btn-inquire">View Details</a>
                </div>
              </div>
            </div><!-- End Space Card -->

            <div class="col-lg-4 col-md-6">
              <div class="space-card">
                <img src="<?php echo base_url('assets/img/hotel/event-6.webp'); ?>" alt="Conference Room" class="img-fluid">
                <div class="card-content">
                  <h4>Executive Boardroom</h4>
                  <div class="capacity-info">
                    <span><i class="bi bi-person-standing"></i> Boardroom: 20</span>
                    <span><i class="bi bi-table"></i> U-Shape: 15</span>
                  </div>
                  <div class="amenities">
                    <span class="amenity"><i class="bi bi-display"></i> Smart Board</span>
                    <span class="amenity"><i class="bi bi-telephone"></i> Conference Call</span>
                    <span class="amenity"><i class="bi bi-cup-hot"></i> Refreshments</span>
                  </div>
                  <a href="#" class="btn-inquire">View Details</a>
                </div>
              </div>
            </div><!-- End Space Card -->

            <div class="col-lg-4 col-md-6">
              <div class="space-card">
                <img src="<?php echo base_url('assets/img/hotel/event-8.webp'); ?>" alt="Garden Venue" class="img-fluid">
                <div class="card-content">
                  <h4>Garden Pavilion</h4>
                  <div class="capacity-info">
                    <span><i class="bi bi-person-standing"></i> Cocktail: 150</span>
                    <span class="amenity"><i class="bi bi-flower1"></i> Outdoor Setting</span>
                  </div>
                  <div class="amenities">
                    <span class="amenity"><i class="bi bi-umbrella"></i> Weather Protection</span>
                    <span class="amenity"><i class="bi bi-lightbulb"></i> Ambient Lighting</span>
                    <span class="amenity"><i class="bi bi-music-note"></i> Sound System</span>
                  </div>
                  <a href="#" class="btn-inquire">View Details</a>
                </div>
              </div>
            </div><!-- End Space Card -->

          </div>
        </div>

        <div class="packages-section" data-aos="fade-up" data-aos-delay="600">
          <div class="text-center mb-5">
            <h3>Event Packages</h3>
            <p>Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Donec rutrum congue leo eget malesuada.</p>
          </div>

          <div class="row gy-4">
            <div class="col-lg-4">
              <div class="package-card" data-aos="zoom-in" data-aos-delay="100">
                <div class="package-header">
                  <h4>Business Package</h4>
                  <div class="package-price">
                    <span class="currency">$</span>
                    <span class="amount">85</span>
                    <span class="period">/person</span>
                  </div>
                </div>
                <div class="package-features">
                  <ul>
                    <li><i class="bi bi-check"></i> Meeting room rental (8 hours)</li>
                    <li><i class="bi bi-check"></i> AV equipment included</li>
                    <li><i class="bi bi-check"></i> Coffee breaks (2)</li>
                    <li><i class="bi bi-check"></i> Working lunch</li>
                    <li><i class="bi bi-check"></i> Event coordinator</li>
                    <li><i class="bi bi-check"></i> Complimentary WiFi</li>
                  </ul>
                  <a href="#" class="btn-package">Get Quote</a>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="package-card featured" data-aos="zoom-in" data-aos-delay="200">
                <div class="featured-badge">Most Popular</div>
                <div class="package-header">
                  <h4>Wedding Package</h4>
                  <div class="package-price">
                    <span class="currency">$</span>
                    <span class="amount">175</span>
                    <span class="period">/person</span>
                  </div>
                </div>
                <div class="package-features">
                  <ul>
                    <li><i class="bi bi-check"></i> Venue rental (12 hours)</li>
                    <li><i class="bi bi-check"></i> Ceremony &amp; reception setup</li>
                    <li><i class="bi bi-check"></i> Three-course dinner</li>
                    <li><i class="bi bi-check"></i> Open bar (5 hours)</li>
                    <li><i class="bi bi-check"></i> Wedding coordinator</li>
                    <li><i class="bi bi-check"></i> Floral centerpieces</li>
                    <li><i class="bi bi-check"></i> Sound system &amp; lighting</li>
                  </ul>
                  <a href="#" class="btn-package">Get Quote</a>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="package-card" data-aos="zoom-in" data-aos-delay="300">
                <div class="package-header">
                  <h4>Social Package</h4>
                  <div class="package-price">
                    <span class="currency">$</span>
                    <span class="amount">120</span>
                    <span class="period">/person</span>
                  </div>
                </div>
                <div class="package-features">
                  <ul>
                    <li><i class="bi bi-check"></i> Venue rental (6 hours)</li>
                    <li><i class="bi bi-check"></i> Cocktail reception setup</li>
                    <li><i class="bi bi-check"></i> Hors d'oeuvres selection</li>
                    <li><i class="bi bi-check"></i> Premium bar service</li>
                    <li><i class="bi bi-check"></i> Event host</li>
                    <li><i class="bi bi-check"></i> Basic decoration</li>
                  </ul>
                  <a href="#" class="btn-package">Get Quote</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="services-section" data-aos="fade-up" data-aos-delay="700">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <h3>Complete Event Services</h3>
              <p>Sed porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar.</p>

              <div class="services-grid">
                <div class="service-item">
                  <i class="bi bi-person-workspace"></i>
                  <div class="service-content">
                    <h5>Dedicated Event Coordinator</h5>
                    <p>Personal assistance from planning to execution</p>
                  </div>
                </div>
                <div class="service-item">
                  <i class="bi bi-camera-video"></i>
                  <div class="service-content">
                    <h5>Full AV Support</h5>
                    <p>State-of-the-art equipment and technical assistance</p>
                  </div>
                </div>
                <div class="service-item">
                  <i class="bi bi-palette"></i>
                  <div class="service-content">
                    <h5>Custom Decoration</h5>
                    <p>Tailored styling to match your event theme</p>
                  </div>
                </div>
                <div class="service-item">
                  <i class="bi bi-truck"></i>
                  <div class="service-content">
                    <h5>Setup &amp; Breakdown</h5>
                    <p>Complete event logistics management</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <img src="<?php echo base_url('assets/img/hotel/event-10.webp'); ?>" alt="Event Services" class="img-fluid rounded">
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Events Section -->

  </main>

  <footer id="footer" class="footer position-relative dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.php" class="logo d-flex align-items-center">
              <span class="sitename">LuxuryHotel</span>
            </a>
            <div class="footer-contact pt-3">
              <p>A108 Adam Street</p>
              <p>New York, NY 535022</p>
              <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
              <p><strong>Email:</strong> <span>info@example.com</span></p>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About us</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">Terms of service</a></li>
              <li><a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><a href="#">Web Design</a></li>
              <li><a href="#">Web Development</a></li>
              <li><a href="#">Product Management</a></li>
              <li><a href="#">Marketing</a></li>
              <li><a href="#">Graphic Design</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Hic solutasetp</h4>
            <ul>
              <li><a href="#">Molestiae accusamus iure</a></li>
              <li><a href="#">Excepturi dignissimos</a></li>
              <li><a href="#">Suscipit distinctio</a></li>
              <li><a href="#">Dilecta</a></li>
              <li><a href="#">Sit quas consectetur</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Nobis illum</h4>
            <ul>
              <li><a href="#">Ipsam</a></li>
              <li><a href="#">Laudantium dolorum</a></li>
              <li><a href="#">Dinera</a></li>
              <li><a href="#">Trodelas</a></li>
              <li><a href="#">Flexo</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>

    <div class="copyright text-center">
      <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>
            © Copyright <strong><span>MyWebsite</span></strong>. All Rights Reserved
          </div>
          <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/herobiz-bootstrap-business-template/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
          </div>
        </div>

        <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>

      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/vendor/php-email-form/validate.js') ?>"></script>
  <script src="<?php echo base_url('assets/vendor/aos/aos.js') ?>"></script>
  <script src="<?php echo base_url('assets/vendor/purecounter/purecounter_vanilla.js') ?>"></script>
  <script src="<?php echo base_url('assets/vendor/swiper/swiper-bundle.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/vendor/glightbox/js/glightbox.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/vendor/drift-zoom/Drift.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/vendor/isotope-layout/isotope.pkgd.min.js') ?>"></script>

  <!-- Main JS File -->
  <script src="<?php echo base_url('assets/js/main.js') ?>"></script>

</body>

</html>