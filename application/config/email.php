<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email Configuration
| -------------------------------------------------------------------------
| This file contains the email configuration settings.
| You can override these settings in your application/config/email.php file.
*/

// Load settings from database
$CI =& get_instance();
$CI->load->model('Settings_model');

$config['protocol'] = 'smtp';
$config['smtp_host'] = $CI->Settings_model->get_setting('email_smtp_host', 'localhost');
$config['smtp_port'] = $CI->Settings_model->get_setting('email_smtp_port', 587);
$config['smtp_user'] = $CI->Settings_model->get_setting('email_smtp_username', '');
$config['smtp_pass'] = $CI->Settings_model->get_setting('email_smtp_password', '');
$config['smtp_crypto'] = 'tls';
$config['smtp_timeout'] = 30;

$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 76;

// From address settings
$config['from_email'] = $CI->Settings_model->get_setting('hotel_email', 'noreply@hotel.com');
$config['from_name'] = $CI->Settings_model->get_setting('hotel_name', 'Hotel Management System');

// Email validation
$config['validate'] = TRUE;
$config['priority'] = 3; // 1 = highest, 5 = lowest, 3 = normal

// Batch settings for bulk emails
$config['bcc_batch_mode'] = TRUE;
$config['bcc_batch_size'] = 200;

// Alternative configuration for different environments
if (ENVIRONMENT === 'development') {
    // For development, you might want to use a service like MailHog or log emails to file
    $config['protocol'] = 'mail'; // or 'sendmail' or 'smtp'
    // Uncomment below to log emails instead of sending them in development
    // $config['protocol'] = 'mail';
    // $config['mailpath'] = '/usr/sbin/sendmail';
} 