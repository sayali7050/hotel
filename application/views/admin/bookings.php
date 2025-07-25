<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Bookings Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .main-content { 
            margin-left: 250px; 
            padding: 20px; 
            min-height: 100vh; 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .table-card { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            border: 1px solid rgba(79, 172, 254, 0.1);
        }
        .status-badge { font-size: 0.8rem; }
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
</head>
<body>
    <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
    <?php $this->load->view('admin/includes/header'); ?>
    <!-- Include Sidebar -->
    <?php $this->load->view('admin/includes/sidebar'); ?>

    <!-- Main Content -->
    <main id="main-content" role="main">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-calendar-check"></i> Bookings Management</h2>
            </div>
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success" role="alert" aria-live="polite">
                    <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert" aria-live="assertive">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            <div class="table-card p-3">
                <form method="post" action="<?php echo base_url('admin/bulk_action_bookings'); ?>" id="bulk-action-form">
                <div class="d-flex mb-2">
                   <div class="me-2">
                       <select name="bulk_action" class="form-select form-select-sm" required>
                           <option value="">Bulk Action</option>
                           <option value="delete">Delete Selected</option>
                           <option value="cancel">Set Status: Cancelled</option>
                           <option value="confirm">Set Status: Confirmed</option>
                       </select>
                   </div>
                   <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" role="table" aria-label="Bookings Management">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Status</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Total</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($bookings): foreach($bookings as $booking): ?>
                            <tr>
                                <td><input type="checkbox" name="booking_ids[]" value="<?php echo $booking->id; ?>"></td>
                                <td><?php echo $booking->first_name . ' ' . $booking->last_name; ?></td>
                                <td><?php echo $booking->room_number . ' (' . $booking->room_type . ')'; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'checked_in' ? 'info' : ($booking->status == 'checked_out' ? 'secondary' : 'danger'))); ?> status-badge">
                                        <?php echo ucfirst(str_replace('_', ' ', $booking->status)); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($booking->check_in_date)); ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking->check_out_date)); ?></td>
                                <td>$<?php echo number_format($booking->total_amount, 2); ?></td>
                                <td>
                                    <a href="<?php echo base_url('admin/view_booking/'.$booking->id); ?>" class="btn btn-sm btn-info" title="View" aria-label="View booking <?php echo $booking->id ?>"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo base_url('admin/edit_booking/'.$booking->id); ?>" class="btn btn-sm btn-warning" title="Edit" aria-label="Edit booking <?php echo $booking->id ?>"><i class="fas fa-edit"></i></a>
                                    <a href="<?php echo base_url('admin/update_booking_status/'.$booking->id); ?>" class="btn btn-sm btn-danger" title="Cancel" onclick="return confirm('Cancel this booking?')"><i class="fas fa-times"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-calendar-check fa-2x mb-3"></i><br>
                                    No bookings found.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                </form>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Select all checkboxes
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="booking_ids[]"]');
    for (const cb of checkboxes) { cb.checked = this.checked; }
});
</script>
</body>
</html> 