<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<body>
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
<div class="container py-5">
  <div class="row">
    <div class="col-md-3 mb-4 mb-md-0">
      <?php $this->load->view('customer/sidebar'); ?>
    </div>
    <div class="col-md-9">
      <h2 class="mb-4"><i class="fas fa-edit"></i> Edit Booking</h2>
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success" role="alert" aria-live="polite">
          <?= $this->session->flashdata('success') ?>
        </div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger" role="alert" aria-live="assertive">
          <?= $this->session->flashdata('error') ?>
        </div>
      <?php endif; ?>
      <div class="row g-4 flex-lg-row flex-column-reverse">
        <div class="col-lg-8">
          <div class="booking-card p-4 mb-4 bg-white rounded shadow-sm">
            <?php if(validation_errors()): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
                <ul class="mb-0 mt-2">
                  <?php echo validation_errors('<li>', '</li>'); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>
            <form action="<?= site_url('customer/edit_booking/'.$booking->id) ?>" method="post" aria-label="Edit your booking details">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="room_id" class="form-label">Select Room *</label>
                  <select class="form-control" id="room_id" name="room_id" required>
                    <option value="">Choose a room...</option>
                    <?php foreach($rooms as $room): ?>
                      <option value="<?php echo $room->id; ?>" <?php echo set_select('room_id', $room->id, $room->id == $booking->room_id); ?>>
                        Room <?php echo $room->room_number; ?> - <?php echo $room->room_type; ?> ($<?php echo number_format($room->price_per_night, 2); ?>/night)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="nights" class="form-label">Number of Nights</label>
                  <input type="number" class="form-control" id="nights" min="1" max="30" value="<?php echo (new DateTime($booking->check_out_date))->diff(new DateTime($booking->check_in_date))->days; ?>" readonly>
                  <small class="text-muted">Calculated from check-in/out dates</small>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="check_in_date" class="form-label">Check-in Date *</label>
                  <input type="date" class="form-control" id="check_in_date" name="check_in_date" value="<?php echo set_value('check_in_date', $booking->check_in_date); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="check_out_date" class="form-label">Check-out Date *</label>
                  <input type="date" class="form-control" id="check_out_date" name="check_out_date" value="<?php echo set_value('check_out_date', $booking->check_out_date); ?>" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="special_requests" class="form-label">Special Requests</label>
                <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Any special requests or preferences..."><?php echo set_value('special_requests', $booking->special_requests); ?></textarea>
                <small class="text-muted">Optional - Let us know if you have any special requirements</small>
              </div>
              <div class="d-flex justify-content-between">
                <a href="<?php echo base_url('customer/my_bookings'); ?>" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back to My Bookings
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="room-preview bg-gradient p-4 mb-4 rounded shadow-sm text-white" style="background: linear-gradient(90deg, #0072ff 0%, #00c97b 100%);">
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Booking Information</h5>
            <div id="booking-summary">
              <p>Select a room and dates to see booking details.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('footer'); ?>
<style>
.booking-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 16px rgba(0,0,0,0.06);
  border: 1px solid #e0e0e0;
}
.room-preview {
  border-radius: 12px;
  box-shadow: 0 2px 16px rgba(0,0,0,0.06);
}
@media (max-width: 991px) {
  .booking-card, .room-preview { margin-bottom: 1.5rem; }
}
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
<script>
// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('check_in_date').min = today;
document.getElementById('check_out_date').min = today;
// Update check-out minimum date when check-in changes
document.getElementById('check_in_date').addEventListener('change', function() {
  document.getElementById('check_out_date').min = this.value;
  calculateNights();
});
document.getElementById('check_out_date').addEventListener('change', function() {
  calculateNights();
});
function calculateNights() {
  const checkIn = document.getElementById('check_in_date').value;
  const checkOut = document.getElementById('check_out_date').value;
  if (checkIn && checkOut) {
    const start = new Date(checkIn);
    const end = new Date(checkOut);
    const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    if (nights > 0) {
      document.getElementById('nights').value = nights;
      updateBookingSummary();
    }
  }
}
function updateBookingSummary() {
  const roomSelect = document.getElementById('room_id');
  const checkIn = document.getElementById('check_in_date').value;
  const checkOut = document.getElementById('check_out_date').value;
  const nights = document.getElementById('nights').value;
  if (roomSelect && roomSelect.value && checkIn && checkOut && nights > 0) {
    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    const priceMatch = selectedOption.text.match(/\$([\d,]+\.?\d*)/);
    if (priceMatch) {
      const pricePerNight = parseFloat(priceMatch[1].replace(',', ''));
      const totalAmount = pricePerNight * nights;
      document.getElementById('booking-summary').innerHTML = `
        <p><strong>Room:</strong> ${selectedOption.text.split(' - ')[0]}</p>
        <p><strong>Check-in:</strong> ${new Date(checkIn).toLocaleDateString()}</p>
        <p><strong>Check-out:</strong> ${new Date(checkOut).toLocaleDateString()}</p>
        <p><strong>Nights:</strong> ${nights}</p>
        <p><strong>Price per night:</strong> $${pricePerNight.toFixed(2)}</p>
        <hr>
        <h6><strong>Total Amount: $${totalAmount.toFixed(2)}</strong></h6>
      `;
    }
  }
}
// Update summary when room selection changes
if (document.getElementById('room_id')) {
  document.getElementById('room_id').addEventListener('change', updateBookingSummary);
}
</script> 