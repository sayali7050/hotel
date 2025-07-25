<?php $this->load->view('admin/includes/header'); ?>
<?php $this->load->view('admin/includes/sidebar'); ?>
<body>
  <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
<main id="main-content" role="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-star"></i> Review Moderation</h2>
    </div>
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success" role="alert" aria-live="polite">
            <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger" role="alert" aria-live="assertive">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>
    <div class="table-card p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" role="table" aria-label="Reviews Moderation">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Guest</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($reviews): foreach($reviews as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r->room_number . ' (' . $r->room_type . ')'); ?></td>
                        <td><?php echo htmlspecialchars($r->first_name . ' ' . $r->last_name); ?></td>
                        <td><?php echo str_repeat('★', $r->rating) . str_repeat('☆', 5 - $r->rating); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($r->review_text)); ?></td>
                        <td><span class="badge bg-<?php echo $r->status == 'approved' ? 'success' : ($r->status == 'pending' ? 'warning' : 'danger'); ?>"><?php echo ucfirst($r->status); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($r->created_at)); ?></td>
                        <td>
                            <?php if($r->status == 'pending'): ?>
                                <a href="<?php echo base_url('admin/approve_review/'.$r->id); ?>" class="btn btn-sm btn-success mb-1" aria-label="Approve review <?= $r->id ?>">Approve</a>
                                <a href="<?php echo base_url('admin/reject_review/'.$r->id); ?>" class="btn btn-sm btn-danger mb-1" aria-label="Reject review <?= $r->id ?>">Reject</a>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-info mb-1" data-bs-toggle="collapse" data-bs-target="#reply-<?php echo $r->id; ?>">Reply</button>
                        </td>
                    </tr>
                    <tr class="collapse" id="reply-<?php echo $r->id; ?>">
                        <td colspan="7">
                            <form method="post" action="<?php echo base_url('admin/reply_review/'.$r->id); ?>">
                                <div class="mb-2">
                                    <label class="form-label">Admin Reply</label>
                                    <textarea class="form-control" name="admin_reply" rows="2"><?php echo htmlspecialchars($r->admin_reply); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Save Reply</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center text-muted">No reviews found.</td></tr>
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