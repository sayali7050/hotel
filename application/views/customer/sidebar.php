<?php $CI =& get_instance(); ?>
<?php 
    $segment2 = $CI->uri->segment(2);
    function is_active($tab) {
        global $segment2;
        return ($segment2 === $tab) ? 'active' : '';
    }
?>
<style>
    .customer-sidebar {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        border: 1px solid #e0e0e0;
        margin-top: 90px; /* Push below fixed header */
        min-height: calc(100vh - 110px); /* Fill below header */
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
    .customer-sidebar .nav-link {
        color: #0072ff;
        font-weight: 500;
        border-radius: 6px;
        margin-bottom: 6px;
        transition: background 0.2s, color 0.2s;
    }
    .customer-sidebar .nav-link.active, .customer-sidebar .nav-link:hover {
        background: linear-gradient(90deg, #0072ff 0%, #00c97b 100%);
        color: #fff !important;
        font-weight: 600;
    }
    .customer-sidebar h4 {
        color: #0072ff;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 991px) {
        .customer-sidebar {
            margin-top: 0;
            min-height: auto;
        }
    }
    .sidebar a:focus {
      outline: 2px solid #0072ff;
      background: #e6f0ff;
    }
</style>
<div class="customer-sidebar p-4">
    <h4 class="text-center">
        <i class="fas fa-user-circle"></i> Customer Panel
    </h4>
    <nav class="nav flex-column" role="navigation" aria-label="Customer sidebar navigation">
        <a class="nav-link <?php echo ($segment2 == 'dashboard') ? 'active' : ''; ?>" href="<?php echo base_url('customer/dashboard'); ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a class="nav-link <?php echo ($segment2 == 'my-bookings') ? 'active' : ''; ?>" href="<?php echo base_url('customer/my-bookings'); ?>">
            <i class="fas fa-calendar-check"></i> My Bookings
        </a>
        <a class="nav-link <?php echo ($segment2 == 'profile') ? 'active' : ''; ?>" href="<?php echo base_url('customer/profile'); ?>">
            <i class="fas fa-user"></i> Profile
        </a>
    </nav>
</div> 