<?php $this->load->view('admin/includes/header'); ?>
<?php $this->load->view('admin/includes/sidebar'); ?>
<body>
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
<main id="main-content" role="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-ticket-alt"></i> Coupon Management</h2>
        <a href="<?php echo base_url('admin/add_coupon'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Add Coupon</a>
    </div>
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success" role="alert" aria-live="polite">
            <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger" role="alert" aria-live="assertive">
            <i class="fas fa-times-circle"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <div class="table-card p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" role="table" aria-label="Coupons Management">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Max Uses</th>
                        <th>Used</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($coupons): foreach($coupons as $c): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($c->code); ?></strong></td>
                        <td><?php echo ucfirst($c->discount_type); ?></td>
                        <td><?php echo $c->discount_type == 'percent' ? $c->discount_value.'%' : '$'.$c->discount_value; ?></td>
                        <td><?php echo $c->max_uses ? $c->max_uses : 'Unlimited'; ?></td>
                        <td><?php echo $c->used_count; ?></td>
                        <td><?php echo $c->expiry_date ? date('M d, Y', strtotime($c->expiry_date)) : 'None'; ?></td>
                        <td><span class="badge bg-<?php echo $c->active ? 'success' : 'secondary'; ?>"><?php echo $c->active ? 'Active' : 'Inactive'; ?></span></td>
                        <td>
                            <a href="<?php echo base_url('admin/edit_coupon/'.$c->id); ?>" class="btn btn-sm btn-warning" aria-label="Edit coupon <?= $c->id ?>">Edit</a>
                            <a href="<?php echo base_url('admin/delete_coupon/'.$c->id); ?>" class="btn btn-sm btn-danger" aria-label="Delete coupon <?= $c->id ?>" onclick="return confirm('Delete this coupon?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="8" class="text-center text-muted">No coupons found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php $this->load->view('footer'); ?>
<style>
.table-card { background: white; border-radius: 15px; box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1); border: 1px solid rgba(79, 172, 254, 0.1); }
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