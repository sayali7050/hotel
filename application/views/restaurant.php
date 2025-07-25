﻿<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Restaurant - LuxuryHotel Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="<?php echo base_url('assets/img/favicon.png'); ?>" rel="icon">
  <link href="<?php echo base_url('assets/img/apple-touch-icon.png'); ?>" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/aos/aos.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/swiper/swiper-bundle.min.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/glightbox/css/glightbox.min.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/drift-zoom/drift-basic.css'); ?>" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?php echo base_url('assets/css/main.css'); ?>" rel="stylesheet">

  <!-- =======================================================
  * Template Name: LuxuryHotel
  * Template URL: https://bootstrapmade.com/luxury-hotel-bootstrap-template/
  * Updated: Jun 30 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="restaurant-page">
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="<?php echo base_url(); ?>" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="<?php echo base_url('assets/img/logo.webp" alt=""> -->
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
          <li><a href="<?= base_url() ?>">Home</a></li>
          <li><a href="<?= base_url('about') ?>">About</a></li>
          <li><a href="<?= base_url('rooms') ?>">Rooms</a></li>
          <li><a href="<?= base_url('amenities') ?>">Amenities</a></li>
          <li><a href="<?= base_url('location') ?>">Location</a></li>
          <li class="dropdown"><a href="#"><span>Pages</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="<?= base_url('room-details') ?>">Room Details</a></li>
              <li><a href="<?= base_url('restaurant') ?>" class="active">Restaurant</a></li>
              <li><a href="<?= base_url('offers') ?>">Offers</a></li>
              <li><a href="<?= base_url('events') ?>">Events</a></li>
              <li><a href="<?= base_url('gallery') ?>">Gallery</a></li>
              <li><a href="<?= base_url('booking') ?>">Booking</a></li>
              <li><a href="<?= base_url('terms') ?>">Terms Page</a></li>
              <li><a href="<?= base_url('privacy') ?>">Privacy Page</a></li>
              <li><a href="<?= base_url('starter-page') ?>">Starter Page</a></li>
            </ul>
          </li>
          <li><a href="<?= base_url('contact') ?>">Contact</a></li>
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

  <main id="main-content" role="main">

    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(<?php echo base_url('assets/img/hotel/showcase-7.webp');">
      <div class="container position-relative">
        <h1>Restaurant</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="current">Restaurant</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Restaurant Section -->
    <section id="restaurant" class="restaurant section" aria-label="Hotel Restaurant" role="region">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5 align-items-center">

          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
            <div class="about-content">
              <h3>Culinary Excellence in Luxury Setting</h3>
              <p class="lead">Nestled within our prestigious hotel, our restaurant offers an unparalleled dining experience that combines world-class cuisine with the sophisticated ambiance of luxury hospitality.</p>

              <p>Our executive chef brings over two decades of international culinary expertise, crafting seasonal menus that celebrate both local ingredients and global flavors. Every dish is carefully prepared to create memorable moments for our hotel guests and discerning local diners alike.</p>

              <div class="features-list">
                <div class="feature-item d-flex align-items-start">
                  <div class="feature-icon">
                    <i class="bi bi-award"></i>
                  </div>
                  <div class="feature-content">
                    <h5>Award-Winning Cuisine</h5>
                    <p>Recognized with multiple culinary awards and featured in prestigious dining guides for our innovative approach to contemporary cuisine.</p>
                  </div>
                </div>

                <div class="feature-item d-flex align-items-start">
                  <div class="feature-icon">
                    <i class="bi bi-leaf"></i>
                  </div>
                  <div class="feature-content">
                    <h5>Farm-to-Table Philosophy</h5>
                    <p>We partner with local farms and suppliers to ensure the freshest ingredients while supporting our community's sustainable agriculture.</p>
                  </div>
                </div>

                <div class="feature-item d-flex align-items-start">
                  <div class="feature-icon">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div class="feature-content">
                    <h5>Exceptional Service</h5>
                    <p>Our professionally trained staff provides attentive, personalized service that reflects the highest standards of hotel hospitality.</p>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <div class="about-images">
              <div class="main-image">
                <img src="<?php echo base_url('assets/img/restaurant/showcase-3.webp'); ?>" alt="Elegant hotel restaurant interior" class="img-fluid rounded">
              </div>
              <div class="secondary-image">
                <img src="<?php echo base_url('assets/img/restaurant/chef-2.webp'); ?>" alt="Executive chef preparing signature dish" class="img-fluid rounded">
              </div>
            </div>
          </div>

        </div>

        <div class="row mt-5">
          <div class="col-12" data-aos="fade-up" data-aos-delay="400">
            <div class="chef-quote">
              <div class="row align-items-center">
                <div class="col-lg-2 text-center">
                  <div class="chef-avatar">
                    <img src="<?php echo base_url('assets/img/restaurant/chef-5.webp'); ?>" alt="Executive Chef Marcus Thompson" class="img-fluid rounded-circle">
                  </div>
                </div>
                <div class="col-lg-10">
                  <blockquote class="quote-text">
                    "Our restaurant represents the perfect marriage of luxury hospitality and culinary artistry. Every dish tells a story, every meal creates a memory. We don't just serve food â€“ we craft experiences that reflect the exceptional standards our hotel guests deserve."
                  </blockquote>
                  <cite class="quote-author">
                    <strong>Marcus Thompson</strong><br>
                    Executive Chef &amp; Culinary Director
                  </cite>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-5 gy-4">
          <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100">
            <div class="highlight-card">
              <div class="highlight-icon">
                <i class="bi bi-star"></i>
              </div>
              <h4>Private Dining</h4>
              <p>Exclusive private dining rooms available for intimate celebrations and business meetings, with personalized menu options and dedicated service.</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="highlight-card">
              <div class="highlight-icon">
                <i class="bi bi-heart"></i>
              </div>
              <h4>Room Service Excellence</h4>
              <p>24-hour gourmet room service featuring our restaurant's signature dishes, bringing fine dining directly to our guests' accommodations.</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
            <div class="highlight-card">
              <div class="highlight-icon">
                <i class="bi bi-cup-hot"></i>
              </div>
              <h4>Wine &amp; Spirits</h4>
              <p>Curated selection of premium wines and craft cocktails, with our sommelier available to recommend perfect pairings for every occasion.</p>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Restaurant Section -->

    <!-- Menu Section -->
    <section id="menu" class="menu section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Menu</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up">

        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

          <div class="menu-filters isotope-filters mb-5">
            <ul>
              <li data-filter="*" class="filter-active">All</li>
              <li data-filter=".filter-starters">Starters</li>
              <li data-filter=".filter-main">Main Courses</li>
              <li data-filter=".filter-dessert">Desserts</li>
              <li data-filter=".filter-drinks">Drinks</li>
            </ul>
          </div>

          <div class="menu-container isotope-container row gy-4">

            <!-- Regular Menu Items -->
            <div class="col-lg-6 isotope-item filter-starters">
              <div class="menu-item d-flex align-items-center gap-4">
                <img src="<?php echo base_url('assets/img/restaurant/starter-2.webp'); ?>" alt="Starter" class="menu-img img-fluid rounded-3">
                <div class="menu-content">
                  <h5>Bruschetta Trio <span class="menu-tag">Vegetarian</span></h5>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit curabitur sed.</p>
                  <div class="price">$8.95</div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 isotope-item filter-starters">
              <div class="d-flex menu-item align-items-center gap-4">
                <img src="<?php echo base_url('assets/img/restaurant/starter-5.webp'); ?>" alt="Starter" class="menu-img img-fluid rounded-3">
                <div class="menu-content">
                  <h5>Calamari Fritti <span class="menu-tag">Seafood</span></h5>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit curabitur sed.</p>
                  <div class="price">$10.95</div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 isotope-item filter-main">
              <div class="d-flex menu-item align-items-center gap-4">
                <img src="<?php echo base_url('assets/img/restaurant/main-1.webp'); ?>" alt="Main Course" class="menu-img img-fluid rounded-3">
                <div class="menu-content">
                  <h5>Wild Mushroom Risotto <span class="menu-tag">Vegetarian</span></h5>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit curabitur sed.</p>
                  <div class="price">$18.95</div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 isotope-item filter-main">
              <div class="d-flex menu-item align-items-center gap-4">
                <img src="<?php echo base_url('assets/img/restaurant/main-4.webp'); ?>" alt="Main Course" class="menu-img img-fluid rounded-3">
                <div class="menu-content">
                  <h5>Braised Lamb Shank <span class="menu-tag">Chef's Choice</span></h5>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit curabitur sed.</p>
                  <div class="price">$26.95</div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 isotope-item filter-dessert">
              <div class="d-flex menu-item align-items-center gap-4">
                <img src="<?php echo base_url('assets/img/restaurant/dessert-2.webp'); ?>" alt="Dessert" class="menu-img img-fluid rounded-3">
                <div class="menu-content">
                  <h5>Chocolate Lava Cake <span class="menu-tag">Gluten Free</span></h5>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit curabitur sed.</p>
                  <div class="price">$9.95</div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 isotope-item filter-dessert">
              <div class="d-flex menu-item align-items-center gap-4">
                <img src="<?php echo base_url('assets/img/restaurant/dessert-7.webp'); ?>" alt="Dessert" class="menu-img img-fluid rounded-3">
                <div class="menu-content">
                  <h5>Tiramisu <span class="menu-tag">Classic</span></h5>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit curabitur sed.</p>
                  <div class="price">$8.95</div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 isotope-item filter-drinks">
              <div class="d-flex menu-item align-items-center gap-4">
                <img src="<?php echo base_url('assets/img/restaurant/drink-3.webp'); ?>" alt="Drink" class="menu-img img-fluid rounded-3">
                <div class="menu-content">
                  <h5>Signature Cocktail <span class="menu-tag">Alcoholic</span></h5>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit curabitur sed.</p>
                  <div class="price">$12.95</div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 isotope-item filter-drinks">
              <div class="d-flex menu-item align-items-center gap-4">
                <img src="<?php echo base_url('assets/img/restaurant/drink-8.webp'); ?>" alt="Drink" class="menu-img img-fluid rounded-3">
                <div class="menu-content">
                  <h5>Berry Smoothie <span class="menu-tag">Non-Alcoholic</span></h5>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit curabitur sed.</p>
                  <div class="price">$7.95</div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="text-center mt-5" data-aos="fade-up">
          <a href="#" class="download-menu">
            <i class="bi bi-file-earmark-pdf"></i> Download Full Menu
          </a>
        </div>

        <!-- Chef's Specials -->
        <div class="col-12 mt-5" data-aos="fade-up">
          <div class="specials-badge">
            <span><i class="bi bi-award"></i> Chef's Specials</span>
          </div>
          <div class="specials-container">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="menu-item special-item">
                  <div class="menu-item-img">
                    <img src="<?php echo base_url('assets/img/restaurant/main-3.webp'); ?>" alt="Special Dish" class="img-fluid">
                    <div class="menu-item-badges">
                      <span class="badge-special">Special</span>
                      <span class="badge-vegan">Vegan</span>
                    </div>
                  </div>
                  <div class="menu-item-content">
                    <h4>Mediterranean Grilled Salmon</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ut aliquam metus. Vivamus fermentum magna quis.</p>
                    <div class="menu-item-price">$24.99</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="menu-item special-item">
                  <div class="menu-item-img">
                    <img src="<?php echo base_url('assets/img/restaurant/main-7.webp'); ?>" alt="Special Dish" class="img-fluid">
                    <div class="menu-item-badges">
                      <span class="badge-special">Special</span>
                      <span class="badge-spicy">Spicy</span>
                    </div>
                  </div>
                  <div class="menu-item-content">
                    <h4>Signature Ribeye Steak</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam accumsan risus ut dui pretium, eget elementum nisi.</p>
                    <div class="menu-item-price">$32.99</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Menu Section -->

  </main>

  <footer id="footer" class="footer position-relative dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="<?php echo base_url(); ?>" class="logo d-flex align-items-center">
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
              <li><a href="<?php echo base_url(); ?>">Home</a></li>
              <li><a href="<?php echo base_url('about'); ?>">About us</a></li>
              <li><a href="<?php echo base_url('services'); ?>">Services</a></li>
              <li><a href="<?php echo base_url('terms'); ?>">Terms of service</a></li>
              <li><a href="<?php echo base_url('privacy'); ?>">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><a href="<?php echo base_url('web-design'); ?>">Web Design</a></li>
              <li><a href="<?php echo base_url('web-development'); ?>">Web Development</a></li>
              <li><a href="<?php echo base_url('product-management'); ?>">Product Management</a></li>
              <li><a href="<?php echo base_url('marketing'); ?>">Marketing</a></li>
              <li><a href="<?php echo base_url('graphic-design'); ?>">Graphic Design</a></li>
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
            Â© Copyright <strong><span>MyWebsite</span></strong>. All Rights Reserved
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
  <script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/vendor/php-email-form/validate.js'); ?>"></script>
  <script src="<?php echo base_url('assets/vendor/aos/aos.js'); ?>"></script>
  <script src="<?php echo base_url('assets/vendor/purecounter/purecounter_vanilla.js'); ?>"></script>
  <script src="<?php echo base_url('assets/vendor/swiper/swiper-bundle.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/vendor/glightbox/js/glightbox.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/vendor/drift-zoom/Drift.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/vendor/isotope-layout/isotope.pkgd.min.js'); ?>"></script>

  <!-- Main JS File -->
  <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>

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


