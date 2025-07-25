<?php $this->load->view('admin/includes/header'); ?>
<?php $this->load->view('admin/includes/sidebar'); ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus"></i> Add Coupon</h2>
        <a href="<?php echo base_url('admin/coupons'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Coupons</a>
    </div>
    <div class="form-card p-4">
        <?php if(validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
                <ul class="mb-0 mt-2"><?php echo validation_errors('<li>', '</li>'); ?></ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php echo form_open('admin/add_coupon'); ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="code" class="form-label">Coupon Code *</label>
                    <input type="text" class="form-control" id="code" name="code" value="<?php echo set_value('code'); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="discount_type" class="form-label">Discount Type *</label>
                    <select class="form-select" id="discount_type" name="discount_type" required>
                        <option value="percent" <?php echo set_select('discount_type', 'percent'); ?>>Percent (%)</option>
                        <option value="fixed" <?php echo set_select('discount_type', 'fixed'); ?>>Fixed Amount ($)</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="discount_value" class="form-label">Discount Value *</label>
                    <input type="number" step="0.01" class="form-control" id="discount_value" name="discount_value" value="<?php echo set_value('discount_value'); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="max_uses" class="form-label">Max Uses</label>
                    <input type="number" class="form-control" id="max_uses" name="max_uses" value="<?php echo set_value('max_uses'); ?>" placeholder="Leave blank for unlimited">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="expiry_date" class="form-label">Expiry Date</label>
                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo set_value('expiry_date'); ?>">
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" checked>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo base_url('admin/coupons'); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Coupon</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php $this->load->view('footer'); ?>
<style>
.form-card { background: white; border-radius: 15px; box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1); border: 1px solid rgba(79, 172, 254, 0.1); }
</style> 