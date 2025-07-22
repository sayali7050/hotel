<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Room Management</title>
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
                <h2><i class="fas fa-bed"></i> Room Management</h2>
                <a href="<?php echo base_url('admin/add_room'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Add Room</a>
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
                                <th>Room Number</th>
                                <th>Type</th>
                                <th>Capacity</th>
                                <th>Price/Night</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($rooms): foreach($rooms as $room): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $room->room_number; ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-info status-badge"><?php echo $room->room_type; ?></span>
                                </td>
                                <td>
                                    <i class="fas fa-user"></i> <?php echo $room->capacity; ?> person<?php echo $room->capacity > 1 ? 's' : ''; ?>
                                </td>
                                <td>
                                    <strong>$<?php echo number_format($room->price_per_night, 2); ?></strong>
                                </td>
                                <td>
                                    <?php 
                                    $statusClass = '';
                                    $statusIcon = '';
                                    switch($room->status) {
                                        case 'available':
                                            $statusClass = 'success';
                                            $statusIcon = 'fas fa-check-circle';
                                            break;
                                        case 'occupied':
                                            $statusClass = 'danger';
                                            $statusIcon = 'fas fa-times-circle';
                                            break;
                                        case 'maintenance':
                                            $statusClass = 'warning';
                                            $statusIcon = 'fas fa-tools';
                                            break;
                                        case 'reserved':
                                            $statusClass = 'info';
                                            $statusIcon = 'fas fa-clock';
                                            break;
                                        default:
                                            $statusClass = 'secondary';
                                            $statusIcon = 'fas fa-question-circle';
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass; ?> status-badge">
                                        <i class="<?php echo $statusIcon; ?>"></i> <?php echo ucfirst($room->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('admin/edit_room/'.$room->id); ?>" class="btn btn-sm btn-warning" title="Edit Room">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal(<?php echo $room->id; ?>, '<?php echo $room->room_number; ?>', '<?php echo $room->room_type; ?>')" title="Delete Room">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-bed fa-2x mb-3"></i><br>
                                    No rooms found. <a href="<?php echo base_url('admin/add_room'); ?>">Add your first room</a>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this room?</p>
                <div class="alert alert-warning">
                    <strong>Room Number:</strong> <span id="deleteRoomNumber"></span><br>
                    <strong>Room Type:</strong> <span id="deleteRoomType"></span>
                </div>
                <p class="text-danger"><small><i class="fas fa-info-circle"></i> This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Room
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showDeleteModal(roomId, roomNumber, roomType) {
    // Set the room details in the modal
    document.getElementById('deleteRoomNumber').textContent = roomNumber;
    document.getElementById('deleteRoomType').textContent = roomType;
    
    // Set the delete URL
    document.getElementById('confirmDeleteBtn').href = '<?php echo base_url("admin/delete_room/"); ?>' + roomId;
    
    // Show the modal
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
</body>
</html> 