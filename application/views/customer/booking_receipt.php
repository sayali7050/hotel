<?php /* Booking Receipt View */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .receipt-container { max-width: 600px; margin: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 32px; }
        h2 { color: #0072ff; }
        .row { display: flex; justify-content: space-between; margin-bottom: 12px; }
        .label { font-weight: bold; }
        .total { font-size: 1.2em; font-weight: bold; color: #0072ff; }
        .footer { margin-top: 32px; text-align: center; color: #888; font-size: 0.95em; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
<div class="receipt-container">
    <h2>Booking Receipt</h2>
    <div class="row"><span class="label">Booking ID:</span> <span>#<?php echo $booking->id; ?></span></div>
    <div class="row"><span class="label">Customer Name:</span> <span><?php echo $booking->guest_name ?? ($booking->first_name . ' ' . $booking->last_name); ?></span></div>
    <div class="row"><span class="label">Room Number:</span> <span><?php echo $booking->room_number; ?></span></div>
    <div class="row"><span class="label">Room Type:</span> <span><?php echo $booking->room_type; ?></span></div>
    <div class="row"><span class="label">Check-in Date:</span> <span><?php echo date('F d, Y', strtotime($booking->check_in_date)); ?></span></div>
    <div class="row"><span class="label">Check-out Date:</span> <span><?php echo date('F d, Y', strtotime($booking->check_out_date)); ?></span></div>
    <div class="row"><span class="label">Nights:</span> <span><?php $nights = (new DateTime($booking->check_out_date))->diff(new DateTime($booking->check_in_date))->days; echo $nights; ?></span></div>
    <div class="row"><span class="label">Price per Night:</span> <span>$<?php echo number_format($booking->price_per_night, 2); ?></span></div>
    <div class="row total"><span>Total Amount:</span> <span>$<?php echo number_format($booking->total_amount, 2); ?></span></div>
    <div class="row"><span class="label">Status:</span> <span><?php echo ucfirst($booking->status); ?></span></div>
    <div class="row"><span class="label">Booking Date:</span> <span><?php echo date('F d, Y', strtotime($booking->created_at)); ?></span></div>
    <?php if (!empty($booking->special_requests)): ?>
        <div class="row"><span class="label">Special Requests:</span> <span><?php echo nl2br(htmlspecialchars($booking->special_requests)); ?></span></div>
    <?php endif; ?>
    <div class="footer">
        <p>Thank you for booking with us!</p>
        <p class="no-print"><button onclick="window.print()">Print / Save as PDF</button></p>
    </div>
</div>
</body>
</html> 