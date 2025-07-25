<?php $this->load->view('header'); ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h2 class="mb-4 text-center"><i class="fas fa-clock"></i> Join the Waitlist</h2>
          <p class="text-muted text-center">No rooms are available for your selected dates and criteria. Enter your details below and we'll notify you if a room becomes available.</p>
          <?php if(validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
              <ul class="mb-0 mt-2"><?php echo validation_errors('<li>', '</li>'); ?></ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
          <?php echo form_open('booking/submit_waitlist'); ?>
            <div class="mb-3">
              <label for="guest_name" class="form-label">Name *</label>
              <input type="text" class="form-control" id="guest_name" name="guest_name" value="<?php echo set_value('guest_name'); ?>" required>
            </div>
            <div class="mb-3">
              <label for="guest_email" class="form-label">Email *</label>
              <input type="email" class="form-control" id="guest_email" name="guest_email" value="<?php echo set_value('guest_email'); ?>" required>
            </div>
            <div class="mb-3">
              <label for="guest_phone" class="form-label">Phone *</label>
              <input type="text" class="form-control" id="guest_phone" name="guest_phone" value="<?php echo set_value('guest_phone'); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Room Type</label>
              <input type="text" class="form-control" value="<?php echo htmlspecialchars($search_data['room_type'] ?? ''); ?>" readonly>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Check-in Date</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($search_data['check_in_date']); ?>" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Check-out Date</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($search_data['check_out_date']); ?>" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Adults</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($search_data['adults']); ?>" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Children</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($search_data['children']); ?>" readonly>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Special Requests</label>
              <textarea class="form-control" rows="2" readonly><?php echo htmlspecialchars($search_data['special_requests'] ?? ''); ?></textarea>
            </div>
            <div class="d-flex justify-content-between">
              <a href="<?php echo base_url('booking'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
              <button type="submit" class="btn btn-primary"><i class="fas fa-clock"></i> Join Waitlist</button>
            </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('footer'); ?> 