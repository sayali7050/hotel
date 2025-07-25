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
                <h2><i class="fas fa-bed"></i> Room Management</h2>
                <a href="<?php echo base_url('admin/add_room'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Add Room</a>
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
            <!-- Room Inventory Management -->
            <div class="table-card p-3 mb-4">
                <h4><i class="fas fa-calendar-alt"></i> Room Inventory Management</h4>
                <form method="post" action="<?php echo base_url('admin/update_room_inventory'); ?>" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="inventory_room_type" class="form-label">Room Type</label>
                        <select class="form-select" id="inventory_room_type" name="room_type" required>
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
                    <div class="col-md-3">
                        <label for="inventory_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="inventory_date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="inventory_total_rooms" class="form-label">Total Rooms</label>
                        <input type="number" class="form-control" id="inventory_total_rooms" name="total_rooms" min="0" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-save"></i> Update Inventory</button>
                    </div>
                </form>
                <hr>
                <h6 class="mt-3">Current Inventory (last 7 days)</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Room Type</th>
                                <th>Date</th>
                                <th>Total Rooms</th>
                                <th>Booked Rooms</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($room_inventory)) : ?>
                            <?php foreach ($room_inventory as $inv) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($inv->room_type); ?></td>
                                    <td><?php echo htmlspecialchars($inv->date); ?></td>
                                    <td><?php echo (int)$inv->total_rooms; ?></td>
                                    <td><?php echo (int)$inv->booked_rooms; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="4" class="text-center text-muted">No inventory records found.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="table-card p-3">
                <form method="post" action="<?php echo base_url('admin/bulk_action_rooms'); ?>" id="bulk-action-form">
                <div class="d-flex mb-2">
                    <div class="me-2">
                        <select name="bulk_action" class="form-select form-select-sm" required>
                            <option value="">Bulk Action</option>
                            <option value="delete">Delete Selected</option>
                            <option value="available">Set Status: Available</option>
                            <option value="maintenance">Set Status: Maintenance</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" role="table" aria-label="Rooms Management">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Room Number</th>
                                <th>Type</th>
                                <th>Capacity</th>
                                <th>Price/Night</th>
                                <th>Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($rooms): foreach($rooms as $room): ?>
                            <tr>
                                <td><input type="checkbox" name="room_ids[]" value="<?php echo $room->id; ?>"></td>
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
                                    <a href="<?php echo base_url('admin/edit_room/'.$room->id); ?>" class="btn btn-sm btn-warning" title="Edit Room" aria-label="Edit room <?php echo $room->id ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal(<?php echo $room->id; ?>, '<?php echo $room->room_number; ?>', '<?php echo $room->room_type; ?>')" title="Delete Room" aria-label="Delete room <?php echo $room->id ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-bed fa-2x mb-3"></i><br>
                                    No rooms found. <a href="<?php echo base_url('admin/add_room'); ?>">Add your first room</a>
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

// Select all checkboxes
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="room_ids[]"]');
    for (const cb of checkboxes) { cb.checked = this.checked; }
});
</script>
</body>
</html> 