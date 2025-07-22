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
    </style>
</head>
<body>
    <?php $this->load->view('admin/includes/header'); ?>
    <!-- Include Sidebar -->
    <?php $this->load->view('admin/includes/sidebar'); ?>

    <!-- Main Content -->
    <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-calendar-check"></i> Bookings Management</h2>
            </div>
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <div class="table-card p-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Status</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($bookings): foreach($bookings as $booking): ?>
                            <tr>
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
                                    <a href="<?php echo base_url('admin/view_booking/'.$booking->id); ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo base_url('admin/edit_booking/'.$booking->id); ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="<?php echo base_url('admin/update_booking_status/'.$booking->id); ?>" class="btn btn-sm btn-danger" title="Cancel" onclick="return confirm('Cancel this booking?')"><i class="fas fa-times"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-calendar-check fa-2x mb-3"></i><br>
                                    No bookings found.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 