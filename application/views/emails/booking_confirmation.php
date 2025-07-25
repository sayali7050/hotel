<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Confirmation</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
    .email-container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); padding: 32px; }
    h2 { color: #0072ff; }
    .details-table { width: 100%; margin-top: 24px; border-collapse: collapse; }
    .details-table td { padding: 8px 0; }
    .details-table .label { font-weight: bold; color: #333; width: 40%; }
    .footer { margin-top: 32px; text-align: center; color: #888; font-size: 0.95em; }
    .btn { display: inline-block; background: #0072ff; color: #fff; padding: 10px 24px; border-radius: 4px; text-decoration: none; margin-top: 24px; }
  </style>
</head>
<body>
  <div class="email-container">
    <h2>Booking Confirmation</h2>
    <p>Dear <?php echo htmlspecialchars($user->first_name ?? $booking->guest_name); ?>,</p>
    <p>Thank you for booking with <strong>Your Hotel Name</strong>! Your reservation is confirmed. Here are your booking details:</p>
    <table class="details-table">
      <tr><td class="label">Booking ID:</td><td>#<?php echo $booking->id; ?></td></tr>
      <tr><td class="label">Room Number:</td><td><?php echo $booking->room_number ?? 'TBD'; ?></td></tr>
      <tr><td class="label">Room Type:</td><td><?php echo $booking->room_type ?? 'TBD'; ?></td></tr>
      <tr><td class="label">Check-in:</td><td><?php echo date('F d, Y', strtotime($booking->check_in_date)); ?></td></tr>
      <tr><td class="label">Check-out:</td><td><?php echo date('F d, Y', strtotime($booking->check_out_date)); ?></td></tr>
      <tr><td class="label">Guests:</td><td><?php echo (int)$booking->adults; ?> Adult(s), <?php echo (int)$booking->children; ?> Child(ren)</td></tr>
      <tr><td class="label">Total Amount:</td><td>$<?php echo number_format($booking->total_amount, 2); ?></td></tr>
      <tr><td class="label">Status:</td><td><?php echo ucfirst($booking->status); ?></td></tr>
    </table>
    <?php if (!empty($booking->special_requests)): ?>
      <p><strong>Special Requests:</strong> <?php echo nl2br(htmlspecialchars($booking->special_requests)); ?></p>
    <?php endif; ?>
    <a href="<?php echo base_url('customer/view_booking/'.$booking->id); ?>" class="btn">View Booking</a>
    <div class="footer">
      <p>If you have any questions, please contact us at <a href="mailto:support@hotel.com">support@hotel.com</a> or call +1 (555) 123-4567.</p>
      <p>We look forward to welcoming you!</p>
    </div>
  </div>
</body>
</html> 