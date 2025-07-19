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
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/hotel/showcase-7.webp);">
      <div class="container position-relative">
        <h1>Room Details</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Room Details</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Room Details Section -->
    <section id="room-details" class="room-details section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <!-- Room Gallery -->
          <div class="col-lg-8">
            <div class="room-gallery">
              <div class="main-image-container image-zoom-container">
                <img id="main-product-image" src="assets/img/hotel/room-1.webp" alt="Presidential Suite" class="img-fluid main-room-image" data-zoom="assets/img/hotel/room-1.webp">
                <div class="image-nav-buttons">
                  <button class="image-nav-btn prev-image" type="button">
                    <i class="bi bi-chevron-left"></i>
                  </button>
                  <button class="image-nav-btn next-image" type="button">
                    <i class="bi bi-chevron-right"></i>
                  </button>
                </div>
              </div>
              <div class="thumbnail-gallery thumbnail-list">
                <div class="thumbnail-item active" data-image="assets/img/hotel/room-1.webp">
                  <img src="assets/img/hotel/room-1.webp" alt="Presidential Suite" class="img-fluid">
                </div>
                <div class="thumbnail-item" data-image="assets/img/hotel/room-3.webp">
                  <img src="assets/img/hotel/room-3.webp" alt="Bedroom View" class="img-fluid">
                </div>
                <div class="thumbnail-item" data-image="assets/img/hotel/room-7.webp">
                  <img src="assets/img/hotel/room-7.webp" alt="Bathroom" class="img-fluid">
                </div>
                <div class="thumbnail-item" data-image="assets/img/hotel/room-12.webp">
                  <img src="assets/img/hotel/room-12.webp" alt="City View" class="img-fluid">
                </div>
                <div class="thumbnail-item" data-image="assets/img/hotel/room-15.webp">
                  <img src="assets/img/hotel/room-15.webp" alt="Living Area" class="img-fluid">
                </div>
              </div>
            </div>
          </div><!-- End Room Gallery -->

          <!-- Room Details Sidebar -->
          <div class="col-lg-4">
            <div class="room-details-sidebar" data-aos="fade-left" data-aos-delay="200">
              <div class="room-header">
                <h2>Presidential Suite</h2>
                <div class="room-rating">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <span class="rating-text">(4.9)</span>
                </div>
              </div>

              <div class="room-price">
                <div class="price-value">
                  <span class="currency">$</span>
                  <span class="amount">899</span>
                  <span class="period">/night</span>
                </div>
                <p class="price-note">*Taxes and fees not included</p>
              </div>

              <div class="booking-form">
                <form action="" method="post">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="checkin-date" class="form-label">Check-in</label>
                      <input type="date" class="form-control" id="checkin-date" name="checkin" required="">
                    </div>
                    <div class="col-12 mb-3">
                      <label for="checkout-date" class="form-label">Check-out</label>
                      <input type="date" class="form-control" id="checkout-date" name="checkout" required="">
                    </div>
                    <div class="col-6 mb-3">
                      <label for="guests" class="form-label">Guests</label>
                      <select class="form-control" id="guests" name="guests" required="">
                        <option value="1">1 Guest</option>
                        <option value="2">2 Guests</option>
                        <option value="3">3 Guests</option>
                        <option value="4">4 Guests</option>
                      </select>
                    </div>
                    <div class="col-6 mb-3">
                      <label for="rooms" class="form-label">Rooms</label>
                      <select class="form-control" id="rooms" name="rooms" required="">
                        <option value="1">1 Room</option>
                        <option value="2">2 Rooms</option>
                        <option value="3">3 Rooms</option>
                      </select>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary btn-book">
                    <i class="bi bi-calendar-check me-2"></i>
                    Book Now
                  </button>
                </form>
              </div>

            </div>
          </div><!-- End Room Details Sidebar -->
        </div>

        <div class="row mt-5">
          <div class="col-12">
            <div class="room-info-tabs" data-aos="fade-up" data-aos-delay="300">
              <ul class="nav nav-tabs" id="roomTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#room-details-overview" type="button" role="tab">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="amenities-tab" data-bs-toggle="tab" data-bs-target="#room-details-amenities" type="button" role="tab">Amenities</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="policies-tab" data-bs-toggle="tab" data-bs-target="#room-details-policies" type="button" role="tab">Policies</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#room-details-reviews" type="button" role="tab">Reviews</button>
                </li>
              </ul>

              <div class="tab-content mt-4" id="roomTabsContent">
                <div class="tab-pane fade show active" id="room-details-overview" role="tabpanel">
                  <div class="row">
                    <div class="col-lg-8">
                      <h3>Room Description</h3>
                      <p class="room-description">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Mauris viverra veniam sit amet lacus cursus bibendum. Pellentesque non nisi enim. Maecenas malesuada lorem vel cursus blandit. Sed tempor, ipsum vel cursus bibendum, nunc nisl aliquam mauris, eget aliquam lacus nunc vel nisl.
                      </p>
                      <p>
                        Nunc auctor, nisl eget ultricies tincidunt, nunc nisl aliquam mauris, eget aliquam lacus nunc vel nisl. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Mauris viverra veniam sit amet lacus cursus bibendum.
                      </p>

                      <div class="room-features-grid mt-4">
                        <div class="feature-item">
                          <i class="bi bi-people"></i>
                          <div class="feature-info">
                            <h5>Max Occupancy</h5>
                            <p>4 Guests</p>
                          </div>
                        </div>
                        <div class="feature-item">
                          <i class="bi bi-arrows-fullscreen"></i>
                          <div class="feature-info">
                            <h5>Room Size</h5>
                            <p>85 sqm</p>
                          </div>
                        </div>
                        <div class="feature-item">
                          <i class="bi bi-moon"></i>
                          <div class="feature-info">
                            <h5>Bed Type</h5>
                            <p>King Bed</p>
                          </div>
                        </div>
                        <div class="feature-item">
                          <i class="bi bi-eye"></i>
                          <div class="feature-info">
                            <h5>View</h5>
                            <p>City Skyline</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="room-stats">
                        <h4>Quick Stats</h4>
                        <div class="stat-item">
                          <span class="stat-label">Floor Level:</span>
                          <span class="stat-value">25-30th Floor</span>
                        </div>
                        <div class="stat-item">
                          <span class="stat-label">Balcony:</span>
                          <span class="stat-value">Private</span>
                        </div>
                        <div class="stat-item">
                          <span class="stat-label">Check-in:</span>
                          <span class="stat-value">3:00 PM</span>
                        </div>
                        <div class="stat-item">
                          <span class="stat-label">Check-out:</span>
                          <span class="stat-value">11:00 AM</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="room-details-amenities" role="tabpanel">
                  <div class="amenities-section">
                    <div class="row">
                      <div class="col-md-6">
                        <h4>Bedroom &amp; Living</h4>
                        <ul class="amenities-list">
                          <li><i class="bi bi-check-circle-fill"></i> King-size bed with premium linens</li>
                          <li><i class="bi bi-check-circle-fill"></i> Separate living area</li>
                          <li><i class="bi bi-check-circle-fill"></i> Work desk with ergonomic chair</li>
                          <li><i class="bi bi-check-circle-fill"></i> Walk-in closet</li>
                          <li><i class="bi bi-check-circle-fill"></i> Blackout curtains</li>
                          <li><i class="bi bi-check-circle-fill"></i> Air conditioning control</li>
                        </ul>

                        <h4 class="mt-4">Entertainment</h4>
                        <ul class="amenities-list">
                          <li><i class="bi bi-check-circle-fill"></i> 65" Smart TV</li>
                          <li><i class="bi bi-check-circle-fill"></i> Premium cable channels</li>
                          <li><i class="bi bi-check-circle-fill"></i> Bluetooth sound system</li>
                          <li><i class="bi bi-check-circle-fill"></i> High-speed Wi-Fi</li>
                        </ul>
                      </div>
                      <div class="col-md-6">
                        <h4>Bathroom</h4>
                        <ul class="amenities-list">
                          <li><i class="bi bi-check-circle-fill"></i> Marble bathroom</li>
                          <li><i class="bi bi-check-circle-fill"></i> Deep soaking tub</li>
                          <li><i class="bi bi-check-circle-fill"></i> Separate rain shower</li>
                          <li><i class="bi bi-check-circle-fill"></i> Double vanity</li>
                          <li><i class="bi bi-check-circle-fill"></i> Luxury toiletries</li>
                          <li><i class="bi bi-check-circle-fill"></i> Heated floors</li>
                        </ul>

                        <h4 class="mt-4">Services &amp; Extras</h4>
                        <ul class="amenities-list">
                          <li><i class="bi bi-check-circle-fill"></i> 24/7 room service</li>
                          <li><i class="bi bi-check-circle-fill"></i> Daily housekeeping</li>
                          <li><i class="bi bi-check-circle-fill"></i> Concierge service</li>
                          <li><i class="bi bi-check-circle-fill"></i> Mini-bar</li>
                          <li><i class="bi bi-check-circle-fill"></i> Coffee maker</li>
                          <li><i class="bi bi-check-circle-fill"></i> Safe deposit box</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="room-details-policies" role="tabpanel">
                  <div class="policies-section">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="policy-group">
                          <h4>Check-in &amp; Check-out</h4>
                          <ul class="policy-list">
                            <li><strong>Check-in:</strong> 3:00 PM - 12:00 AM</li>
                            <li><strong>Check-out:</strong> 6:00 AM - 11:00 AM</li>
                            <li><strong>Early check-in:</strong> Subject to availability</li>
                            <li><strong>Late check-out:</strong> Additional charges may apply</li>
                          </ul>
                        </div>

                        <div class="policy-group">
                          <h4>Cancellation Policy</h4>
                          <ul class="policy-list">
                            <li>Free cancellation up to 48 hours before check-in</li>
                            <li>Cancellations within 48 hours: 1 night charge</li>
                            <li>No-show: Full charge for entire stay</li>
                            <li>Peak season: 7-day advance cancellation required</li>
                          </ul>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="policy-group">
                          <h4>House Rules</h4>
                          <ul class="policy-list">
                            <li>No smoking (designated areas available)</li>
                            <li>Pets allowed with additional fee</li>
                            <li>Maximum occupancy: 4 guests</li>
                            <li>Quiet hours: 10:00 PM - 8:00 AM</li>
                            <li>Valid ID required at check-in</li>
                          </ul>
                        </div>

                        <div class="policy-group">
                          <h4>Payment &amp; Fees</h4>
                          <ul class="policy-list">
                            <li>Credit card required for incidentals</li>
                            <li>City tax: $5 per night (not included)</li>
                            <li>Resort fee: $25 per night</li>
                            <li>Parking: $30 per night (valet only)</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="room-details-reviews" role="tabpanel">
                  <div class="reviews-section">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="review-summary">
                          <div class="overall-rating">
                            <span class="rating-number">4.9</span>
                            <div class="rating-stars">
                              <i class="bi bi-star-fill"></i>
                              <i class="bi bi-star-fill"></i>
                              <i class="bi bi-star-fill"></i>
                              <i class="bi bi-star-fill"></i>
                              <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="review-count">Based on 247 reviews</p>
                          </div>

                          <div class="rating-breakdown">
                            <div class="rating-item">
                              <span class="rating-label">Cleanliness</span>
                              <div class="rating-bar">
                                <div class="rating-fill" style="width: 95%"></div>
                              </div>
                              <span class="rating-value">4.8</span>
                            </div>
                            <div class="rating-item">
                              <span class="rating-label">Comfort</span>
                              <div class="rating-bar">
                                <div class="rating-fill" style="width: 98%"></div>
                              </div>
                              <span class="rating-value">4.9</span>
                            </div>
                            <div class="rating-item">
                              <span class="rating-label">Service</span>
                              <div class="rating-bar">
                                <div class="rating-fill" style="width: 94%"></div>
                              </div>
                              <span class="rating-value">4.7</span>
                            </div>
                            <div class="rating-item">
                              <span class="rating-label">Location</span>
                              <div class="rating-bar">
                                <div class="rating-fill" style="width: 96%"></div>
                              </div>
                              <span class="rating-value">4.8</span>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-8">
                        <div class="reviews-list">
                          <div class="review-item">
                            <div class="reviewer-info">
                              <img src="assets/img/person/person-f-3.webp" alt="Sarah M." class="reviewer-avatar">
                              <div class="reviewer-details">
                                <h5>Sarah M.</h5>
                                <div class="review-stars">
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="review-date">March 15, 2024</span>
                              </div>
                            </div>
                            <p class="review-text">
                              "Absolutely stunning suite with breathtaking city views. The marble bathroom was luxurious and the bed incredibly comfortable. Service was impeccable throughout our stay."
                            </p>
                          </div>

                          <div class="review-item">
                            <div class="reviewer-info">
                              <img src="assets/img/person/person-m-7.webp" alt="Michael R." class="reviewer-avatar">
                              <div class="reviewer-details">
                                <h5>Michael R.</h5>
                                <div class="review-stars">
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="review-date">February 28, 2024</span>
                              </div>
                            </div>
                            <p class="review-text">
                              "Perfect for our anniversary celebration. The suite exceeded our expectations in every way. The private balcony was our favorite feature."
                            </p>
                          </div>

                          <div class="review-item">
                            <div class="reviewer-info">
                              <img src="assets/img/person/person-f-11.webp" alt="Jessica L." class="reviewer-avatar">
                              <div class="reviewer-details">
                                <h5>Jessica L.</h5>
                                <div class="review-stars">
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star-fill"></i>
                                  <i class="bi bi-star"></i>
                                </div>
                                <span class="review-date">January 12, 2024</span>
                              </div>
                            </div>
                            <p class="review-text">
                              "Beautiful room with excellent amenities. The only minor issue was the check-in process took longer than expected, but the staff made up for it with exceptional service."
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Room Details Section -->

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