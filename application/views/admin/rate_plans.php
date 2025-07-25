<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Rate Plans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-tags"></i> Rate Plans Management</h2>
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Add / Edit Rate Plan</div>
        <div class="card-body">
            <form method="post" action="<?php echo base_url('admin/save_rate_plan'); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="room_type" class="form-label">Room Type</label>
                        <select class="form-select" id="room_type" name="room_type" required>
                            <?php if (!empty($room_types)) : ?>
                                <?php foreach ($room_types as $type) : ?>
                                    <option value="<?php echo htmlspecialchars(is_array($type) ? $type['room_type'] : $type); ?>">
                                        <?php echo htmlspecialchars(is_array($type) ? $type['room_type'] : $type); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <option value="Standard">Standard</option>
                                <option value="Suite">Suite</option>
                                <option value="Executive">Executive</option>
                                <option value="Family">Family</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <div class="col-md-2">
                        <label for="price_per_night" class="form-label">Price/Night</label>
                        <input type="number" step="0.01" class="form-control" id="price_per_night" name="price_per_night" required>
                    </div>
                    <div class="col-md-3">
                        <label for="promotion_name" class="form-label">Promotion Name</label>
                        <input type="text" class="form-control" id="promotion_name" name="promotion_name">
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-9">
                        <label for="promotion_description" class="form-label">Promotion Description</label>
                        <input type="text" class="form-control" id="promotion_description" name="promotion_description">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-save"></i> Save Rate Plan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-secondary text-white">Existing Rate Plans</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Room Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Price/Night</th>
                            <th>Promotion</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($rate_plans)) : ?>
                        <?php foreach ($rate_plans as $plan) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($plan->room_type); ?></td>
                                <td><?php echo htmlspecialchars($plan->start_date); ?></td>
                                <td><?php echo htmlspecialchars($plan->end_date); ?></td>
                                <td>$<?php echo number_format($plan->price_per_night, 2); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($plan->promotion_name); ?></strong><br>
                                    <small><?php echo htmlspecialchars($plan->promotion_description); ?></small>
                                </td>
                                <td>
                                    <?php if ($plan->active) : ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('admin/edit_rate_plan/'.$plan->id); ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="<?php echo base_url('admin/delete_rate_plan/'.$plan->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this rate plan?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="7" class="text-center text-muted">No rate plans found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html> 