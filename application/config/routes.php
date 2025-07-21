<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Public pages
$route['about'] = 'pages/about';
$route['rooms'] = 'pages/rooms';
$route['amenities'] = 'pages/amenities';
$route['gallery'] = 'pages/gallery';
$route['events'] = 'pages/events';
$route['restaurant'] = 'pages/restaurant';
$route['contact'] = 'pages/contact';
$route['booking'] = 'booking/index';
$route['booking/search-availability'] = 'booking/search_availability';
$route['booking/select-room'] = 'booking/select_room';
$route['booking/book-room/(:num)'] = 'booking/book_room/$1';
$route['booking/process-booking'] = 'booking/process_booking';
$route['booking/confirmation'] = 'booking/confirmation';
$route['booking/cancel-booking/(:num)'] = 'booking/cancel_booking/$1';
$route['offers'] = 'pages/offers';
$route['privacy'] = 'pages/privacy';
$route['terms'] = 'pages/terms';
$route['location'] = 'pages/location';
$route['starter-page'] = 'pages/starter_page';
$route['room-details'] = 'pages/room_details';

// Authentication routes
$route['admin/login'] = 'auth/admin_login';
$route['staff/login'] = 'auth/staff_login';
$route['customer/login'] = 'auth/customer_login';
$route['customer/register'] = 'auth/customer_register';
$route['logout'] = 'auth/logout';
$route['auth/test'] = 'auth/test';
$route['debug'] = 'debug_login.php';
$route['reset'] = 'reset_admin.php';

// Admin panel routes
$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/users'] = 'admin/users';
$route['admin/add-staff'] = 'admin/add_staff';
$route['admin/edit-user/(:num)'] = 'admin/edit_user/$1';
$route['admin/delete-user/(:num)'] = 'admin/delete_user/$1';
$route['admin/rooms'] = 'admin/rooms';
$route['admin/add-room'] = 'admin/add_room';
$route['admin/edit-room/(:num)'] = 'admin/edit_room/$1';
$route['admin/delete-room/(:num)'] = 'admin/delete_room/$1';
$route['admin/bookings'] = 'admin/bookings';
$route['admin/view-booking/(:num)'] = 'admin/view_booking/$1';
$route['admin/update-booking-status/(:num)'] = 'admin/update_booking_status/$1';
$route['admin/reports'] = 'admin/reports';

// Staff panel routes
$route['staff'] = 'staff/dashboard';
$route['staff/dashboard'] = 'staff/dashboard';
$route['staff/bookings'] = 'staff/bookings';
$route['staff/view-booking/(:num)'] = 'staff/view_booking/$1';
$route['staff/update-booking-status/(:num)'] = 'staff/update_booking_status/$1';
$route['staff/rooms'] = 'staff/rooms';
$route['staff/update-room-status/(:num)'] = 'staff/update_room_status/$1';
$route['staff/check-in/(:num)'] = 'staff/check_in/$1';
$route['staff/check-out/(:num)'] = 'staff/check_out/$1';
$route['staff/search-bookings'] = 'staff/search_bookings';
$route['staff/profile'] = 'staff/profile';
$route['staff/change-password'] = 'staff/change_password';

// Customer panel routes
$route['customer'] = 'customer/dashboard';
$route['customer/dashboard'] = 'customer/dashboard';
$route['customer/rooms'] = 'customer/rooms';
$route['customer/book-room'] = 'customer/book_room';
$route['customer/book-room/(:num)'] = 'customer/book_room/$1';
$route['customer/my-bookings'] = 'customer/my_bookings';
$route['customer/view-booking/(:num)'] = 'customer/view_booking/$1';
$route['customer/cancel-booking/(:num)'] = 'customer/cancel_booking/$1';
$route['customer/profile'] = 'customer/profile';
$route['customer/change-password'] = 'customer/change_password';
$route['customer/search-rooms'] = 'customer/search_rooms';

$route['(:any)'] = 'pages/view/$1';
