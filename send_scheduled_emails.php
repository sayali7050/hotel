<?php
// Run this script daily (via cron or Task Scheduler)
// Sends pre-arrival reminders and post-stay feedback requests

require_once __DIR__ . '/index.php'; // Bootstrap CodeIgniter
$CI =& get_instance();
$CI->load->database();
$CI->load->helper('notification');

// Pre-arrival reminders (check-in is tomorrow)
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$reminder_bookings = $CI->db->where('check_in_date', $tomorrow)
    ->where_in('status', ['confirmed', 'checked_in'])
    ->get('bookings')->result();
foreach ($reminder_bookings as $booking) {
    $subject = 'Your Stay is Coming Up!';
    $message = "Dear {$booking->guest_name},\n\nThis is a friendly reminder that your stay at our hotel begins tomorrow ({$booking->check_in_date}). We look forward to welcoming you!\n\nIf you have any questions or special requests, please contact us.\n\nBest regards,\nHotel Team";
    send_booking_notification($booking->guest_email, $subject, $message, null, ['user_id' => $booking->user_id]);
}

// Post-stay feedback requests (check-out was yesterday)
$yesterday = date('Y-m-d', strtotime('-1 day'));
$feedback_bookings = $CI->db->where('check_out_date', $yesterday)
    ->where_in('status', ['checked_out'])
    ->get('bookings')->result();
foreach ($feedback_bookings as $booking) {
    $subject = 'We Value Your Feedback!';
    $message = "Dear {$booking->guest_name},\n\nThank you for staying with us! We hope you enjoyed your visit.\n\nWe would appreciate it if you could take a moment to provide feedback on your experience.\n\nBest regards,\nHotel Team";
    send_booking_notification($booking->guest_email, $subject, $message, null, ['user_id' => $booking->user_id]);
}

echo "Scheduled emails sent: ".(count($reminder_bookings) + count($feedback_bookings))."\n"; 