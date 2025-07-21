<!-- Admin Sidebar -->
<div class="sidebar text-white" style="width: 250px; min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000; background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #4facfe 100%);">
    <div class="sidebar-header p-3 border-bottom border-white border-opacity-25">
        <h5 class="mb-0">
            <i class="fas fa-hotel"></i> Hotel Admin
        </h5>
        <small class="text-white text-opacity-75">Management Panel</small>
    </div>
    
    <div class="sidebar-user p-3 border-bottom border-white border-opacity-25">
        <div class="d-flex align-items-center">
            <div class="avatar me-3">
                <i class="fas fa-user-circle fa-2x text-white"></i>
            </div>
            <div>
                <div class="fw-bold"><?php echo $this->session->userdata('username'); ?></div>
                <small class="text-white text-opacity-75">Administrator</small>
            </div>
        </div>
    </div>
    
    <nav class="sidebar-nav p-0">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $this->uri->segment(2) == 'dashboard' ? 'active bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/dashboard'); ?>">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $this->uri->segment(2) == 'users' ? 'active bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/users'); ?>">
                    <i class="fas fa-users me-2"></i>
                    Users Management
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $this->uri->segment(2) == 'rooms' ? 'active bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/rooms'); ?>">
                    <i class="fas fa-bed me-2"></i>
                    Rooms Management
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $this->uri->segment(2) == 'bookings' ? 'active bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/bookings'); ?>">
                    <i class="fas fa-calendar-check me-2"></i>
                    Bookings Management
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $this->uri->segment(2) == 'reports' ? 'active bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/reports'); ?>">
                    <i class="fas fa-chart-bar me-2"></i>
                    Reports & Analytics
                </a>
            </li>
        </ul>
        
        <hr class="border-white border-opacity-25 mx-3">
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?php echo base_url('logout'); ?>">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Sidebar Toggle Button for Mobile -->
<button class="btn d-md-none position-fixed" 
        style="top: 10px; left: 10px; z-index: 1001; background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #4facfe 100%); border: none; color: white;" 
        onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<style>
.sidebar {
    transition: transform 0.3s ease;
}

.sidebar-nav .nav-link {
    color: rgba(255, 255, 255, 0.8);
    padding: 12px 20px;
    border-radius: 8px;
    margin: 2px 8px;
    transition: all 0.3s ease;
}

.sidebar-nav .nav-link:hover {
    color: white;
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateX(5px);
}

.sidebar-nav .nav-link.active {
    color: white;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
}

.sidebar-nav .nav-link i {
    width: 20px;
    text-align: center;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0 !important;
    }
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.btn-dark');
    
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    }
});
</script> 