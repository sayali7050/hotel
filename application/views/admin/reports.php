<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-chart-line"></i> Reports & Analytics</h2>
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">Occupancy (Last 30 Days)</div>
                <div class="card-body">
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Date</th><th>Occupied Rooms</th><th>Total Rooms</th><th>Occupancy %</th></tr></thead>
                        <tbody>
                        <?php foreach ($occupancy as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo (int)$row['occupied']; ?></td>
                                <td><?php echo (int)$row['total']; ?></td>
                                <td><?php echo $row['total'] > 0 ? round(($row['occupied']/$row['total'])*100,1) : 0; ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="<?php echo base_url('admin/export_occupancy_csv'); ?>" class="btn btn-sm btn-outline-primary mt-2"><i class="fas fa-file-csv"></i> Export CSV</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">Revenue (Last 30 Days)</div>
                <div class="card-body">
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Date</th><th>Revenue</th></tr></thead>
                        <tbody>
                        <?php foreach ($revenue as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td>$<?php echo number_format($row['revenue'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="<?php echo base_url('admin/export_revenue_csv'); ?>" class="btn btn-sm btn-outline-success mt-2"><i class="fas fa-file-csv"></i> Export CSV</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Top Guests (All Time)</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead><tr><th>Guest</th><th>Email</th><th>Total Bookings</th><th>Total Spend</th></tr></thead>
                <tbody>
                <?php foreach ($top_guests as $guest): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($guest->first_name . ' ' . $guest->last_name); ?></td>
                        <td><?php echo htmlspecialchars($guest->email); ?></td>
                        <td><?php echo (int)$guest->total_bookings; ?></td>
                        <td>$<?php echo number_format($guest->total_spend, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <a href="<?php echo base_url('admin/export_top_guests_csv'); ?>" class="btn btn-sm btn-outline-info mt-2"><i class="fas fa-file-csv"></i> Export CSV</a>
        </div>
    </div>
</div>
</body>
</html> 