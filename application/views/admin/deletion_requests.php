<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deletion Requests - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-user-slash"></i> Account Deletion Requests</h2>
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header bg-danger text-white">Pending Deletion Requests</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Requested At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($pending_deletions)) : ?>
                    <?php foreach ($pending_deletions as $user) : ?>
                        <tr>
                            <td><?php echo (int)$user->id; ?></td>
                            <td><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></td>
                            <td><?php echo htmlspecialchars($user->email); ?></td>
                            <td><?php echo htmlspecialchars($user->updated_at); ?></td>
                            <td>
                                <a href="<?php echo base_url('admin/approve_deletion/'.$user->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user account permanently?');">
                                    <i class="fas fa-user-slash"></i> Approve
                                </a>
                                <a href="<?php echo base_url('admin/restore_user/'.$user->id); ?>" class="btn btn-sm btn-success ms-2" onclick="return confirm('Restore this user account?');">
                                    <i class="fas fa-undo"></i> Restore
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="5" class="text-center text-muted">No pending deletion requests.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html> 