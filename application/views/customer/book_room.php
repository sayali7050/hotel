<?php $CI =& get_instance(); ?>
<?php $this->load->view('header'); ?>
<div class="container py-5">
  <div class="row">
    <div class="col-md-3 mb-4 mb-md-0">
      <?php $this->load->view('customer/sidebar'); ?>
    </div>
    <div class="col-md-9">
      <h2 class="mb-4"><i class="fas fa-calendar-plus"></i> Book a Room</h2>
      <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
            <?php echo form_open('customer/book_room'); ?>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="room_id" class="form-label">Select Room *</label>
                  <?php if(isset($room)): ?>
                    <input type="hidden" name="room_id" value="<?php echo $room->id; ?>">
                    <input type="text" class="form-control" value="Room <?php echo $room->room_number; ?> (<?php echo $room->room_type; ?>)" readonly>
                  <?php else: ?>
                    <select class="form-control" id="room_id" name="room_id" required>
                      <option value="">Choose a room...</option>
                      <?php foreach($rooms as $room): ?>
                        <option value="<?php echo $room->id; ?>" <?php echo set_select('room_id', $room->id); ?>>
                          Room <?php echo $room->room_number; ?> - <?php echo $room->room_type; ?> ($<?php echo number_format($room->price_per_night, 2); ?>/night)
                        </option>
                      <?php endforeach; ?>
                    </select>
                  <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="nights" class="form-label">Number of Nights</label>
                  <input type="number" class="form-control" id="nights" min="1" max="30" value="1" readonly>
                  <small class="text-muted">Calculated from check-in/out dates</small>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="check_in_date" class="form-label">Check-in Date *</label>
                  <input type="date" class="form-control" id="check_in_date" name="check_in_date" value="<?php echo set_value('check_in_date'); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="check_out_date" class="form-label">Check-out Date *</label>
                  <input type="date" class="form-control" id="check_out_date" name="check_out_date" value="<?php echo set_value('check_out_date'); ?>" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="special_requests" class="form-label">Special Requests</label>
                <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Any special requests or preferences..."><?php echo set_value('special_requests'); ?></textarea>
                <small class="text-muted">Optional - Let us know if you have any special requirements</small>
              </div>
              <div class="d-flex justify-content-between">
                <a href="<?php echo base_url('customer/rooms'); ?>" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back to Rooms
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-calendar-check"></i> Confirm Booking
                </button>
              </div>
            <?php echo form_close(); ?>
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