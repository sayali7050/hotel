<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'Room_model', 'Booking_model']);
        $this->load->model('Audit_log_model');
        $this->load->helper('notification');
        $this->load->library(['form_validation', 'session']);
        
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/admin_login');
        }
        $this->admin_permissions = $this->User_model->get_permissions($this->session->userdata('user_id'));
    }
    
    // Permission check helper
    private function require_permission($perm) {
        if (empty($this->admin_permissions[$perm])) {
            $this->session->set_flashdata('error', 'You do not have permission to access this module.');
            redirect('admin/dashboard');
            exit;
        }
    }
    
    // Admin dashboard
    public function dashboard() {
        $this->require_permission('manage_bookings');
        $this->load->model('Booking_model');
        $this->load->model('Room_model');
        // Occupancy: last 7 days
        $labels = [];
        $values = [];
        $total_rooms = count($this->Room_model->get_all_rooms());
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('M d', strtotime($date));
            $occupied = $this->Booking_model->get_occupied_count_on($date);
            $values[] = $total_rooms > 0 ? round(($occupied / $total_rooms) * 100, 1) : 0;
        }
        $data['occupancy_data'] = ['labels' => $labels, 'values' => $values];
        // Revenue: last 7 days
        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('M d', strtotime($date));
            $values[] = $this->Booking_model->get_revenue_on($date);
        }
        $data['revenue_data'] = ['labels' => $labels, 'values' => $values];
        // Booking status breakdown
        $statuses = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];
        $labels = array_map('ucfirst', str_replace('_', ' ', $statuses));
        $values = [];
        foreach ($statuses as $status) {
            $values[] = $this->Booking_model->get_booking_count_by_status($status);
        }
        $data['status_data'] = ['labels' => $labels, 'values' => $values];
        $this->load->view('admin/dashboard', $data);
    }
    
    // User management
    public function users() {
        $this->require_permission('manage_users');
        $data['customers'] = $this->User_model->get_users_by_role('customer');
        $data['staff'] = $this->User_model->get_staff_with_assignments();
        
        $this->load->view('admin/users', $data);
    }
    
    // Add staff
    public function add_staff() {
        $this->require_permission('manage_users');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('department', 'Department', 'required');
        $this->form_validation->set_rules('position', 'Position', 'required');
        $this->form_validation->set_rules('salary', 'Salary', 'required|numeric');
        $this->form_validation->set_rules('hire_date', 'Hire Date', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/add_staff');
        } else {
            $user_data = [
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            ];
            
            if ($this->User_model->register_staff($user_data)) {
                $staff_id = $this->db->insert_id();
                
                $assignment_data = [
                    'staff_id' => $staff_id,
                    'department' => $this->input->post('department'),
                    'position' => $this->input->post('position'),
                    'salary' => $this->input->post('salary'),
                    'hire_date' => $this->input->post('hire_date')
                ];
                
                $this->db->insert('staff_assignments', $assignment_data);
                
                $this->session->set_flashdata('success', 'Staff member added successfully');
                redirect('admin/users');
            } else {
                $this->session->set_flashdata('error', 'Failed to add staff member');
                redirect('admin/add_staff');
            }
        }
    }
    
    // Edit user
    public function edit_user($id) {
        $this->require_permission('manage_users');
        $data['user'] = $this->User_model->get_user_by_id($id);
        
        if (!$data['user']) {
            redirect('admin/users');
        }
        
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/edit_user', $data);
        } else {
            $update_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'status' => $this->input->post('status')
            ];
            
            if ($this->User_model->update_user($id, $update_data)) {
                $this->Audit_log_model->add_log(
                    $this->session->userdata('user_id'),
                    'admin',
                    'edit_user',
                    'user',
                    $id,
                    json_encode($update_data)
                );
                $this->session->set_flashdata('success', 'User updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user');
            }
            redirect('admin/users');
        }
    }
    
    // Delete user
    public function delete_user($id) {
        $this->require_permission('manage_users');
        if ($this->User_model->delete_user($id)) {
            $this->Audit_log_model->add_log(
                $this->session->userdata('user_id'),
                'admin',
                'delete_user',
                'user',
                $id,
                null
            );
            $this->session->set_flashdata('success', 'User deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user');
        }
        redirect('admin/users');
    }
    
    // Bulk action for users
    public function bulk_action_users() {
        $this->require_permission('manage_users');
        $action = $this->input->post('bulk_action');
        $user_ids = $this->input->post('user_ids');
        if (!$action || !$user_ids || !is_array($user_ids)) {
            $this->session->set_flashdata('error', 'No users selected or invalid action.');
            redirect('admin/users');
        }
        $count = 0;
        foreach ($user_ids as $id) {
            if ($action == 'delete') {
                if ($this->User_model->delete_user($id)) {
                    $this->Audit_log_model->add_log(
                        $this->session->userdata('user_id'),
                        'admin',
                        'bulk_delete_user',
                        'user',
                        $id,
                        null
                    );
                    $count++;
                }
            } elseif (in_array($action, ['active', 'inactive', 'suspended'])) {
                if ($this->User_model->update_user($id, ['status' => $action])) {
                    $this->Audit_log_model->add_log(
                        $this->session->userdata('user_id'),
                        'admin',
                        'bulk_update_user_status',
                        'user',
                        $id,
                        json_encode(['status' => $action])
                    );
                    $count++;
                }
            }
        }
        $this->session->set_flashdata('success', "Bulk action applied to $count users.");
        redirect('admin/users');
    }
    
    // Room management
    public function rooms() {
        $this->require_permission('manage_rooms');
        $data['rooms'] = $this->Room_model->get_all_rooms();
        // Get room types for inventory management
        $data['room_types'] = $this->Room_model->get_room_types();
        // Get inventory for last 7 days
        $this->db->order_by('date DESC');
        $this->db->where('date >=', date('Y-m-d', strtotime('-7 days')));
        $data['room_inventory'] = $this->db->get('room_inventory')->result();
        $this->load->view('admin/rooms', $data);
    }

    // Update room inventory (admin)
    public function update_room_inventory() {
        $this->require_permission('manage_rooms');
        $room_type = $this->input->post('room_type');
        $date = $this->input->post('date');
        $total_rooms = (int)$this->input->post('total_rooms');
        if (!$room_type || !$date) {
            $this->session->set_flashdata('error', 'Room type and date are required.');
            redirect('admin/rooms');
        }
        // Upsert inventory record
        $existing = $this->db->get_where('room_inventory', ['room_type' => $room_type, 'date' => $date])->row();
        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('room_inventory', ['total_rooms' => $total_rooms]);
        } else {
            $this->db->insert('room_inventory', [
                'room_type' => $room_type,
                'date' => $date,
                'total_rooms' => $total_rooms,
                'booked_rooms' => 0
            ]);
        }
        $this->session->set_flashdata('success', 'Room inventory updated.');
        redirect('admin/rooms');
    }
    
    // Add room
    public function add_room() {
        $this->require_permission('manage_rooms');
        $this->form_validation->set_rules('room_number', 'Room Number', 'required|is_unique[rooms.room_number]');
        $this->form_validation->set_rules('room_type', 'Room Type', 'required');
        $this->form_validation->set_rules('capacity', 'Capacity', 'required|numeric');
        $this->form_validation->set_rules('price_per_night', 'Price per Night', 'required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/add_room');
        } else {
            $data = [
                'room_number' => $this->input->post('room_number'),
                'room_type' => $this->input->post('room_type'),
                'capacity' => $this->input->post('capacity'),
                'price_per_night' => $this->input->post('price_per_night'),
                'description' => $this->input->post('description'),
                'amenities' => $this->input->post('amenities'),
                'status' => $this->input->post('status')
            ];
            
            if ($this->Room_model->add_room($data)) {
                $room_id = $this->db->insert_id();
                $this->Audit_log_model->add_log(
                    $this->session->userdata('user_id'),
                    'admin',
                    'add_room',
                    'room',
                    $room_id,
                    json_encode($data)
                );
                $this->session->set_flashdata('success', 'Room added successfully');
                redirect('admin/rooms');
            } else {
                $this->session->set_flashdata('error', 'Failed to add room');
                redirect('admin/add_room');
            }
        }
    }
    
    // Edit room
    public function edit_room($id) {
        $this->require_permission('manage_rooms');
        $data['room'] = $this->Room_model->get_room_by_id($id);
        
        if (!$data['room']) {
            redirect('admin/rooms');
        }
        
        $this->form_validation->set_rules('room_type', 'Room Type', 'required');
        $this->form_validation->set_rules('capacity', 'Capacity', 'required|numeric');
        $this->form_validation->set_rules('price_per_night', 'Price per Night', 'required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/edit_room', $data);
        } else {
            $update_data = [
                'room_type' => $this->input->post('room_type'),
                'capacity' => $this->input->post('capacity'),
                'price_per_night' => $this->input->post('price_per_night'),
                'description' => $this->input->post('description'),
                'amenities' => $this->input->post('amenities'),
                'status' => $this->input->post('status')
            ];
            
            if ($this->Room_model->update_room($id, $update_data)) {
                $this->Audit_log_model->add_log(
                    $this->session->userdata('user_id'),
                    'admin',
                    'edit_room',
                    'room',
                    $id,
                    json_encode($update_data)
                );
                $this->session->set_flashdata('success', 'Room updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update room');
            }
            redirect('admin/rooms');
        }
    }
    
    // Delete room
    public function delete_room($id) {
        $this->require_permission('manage_rooms');
        if ($this->Room_model->delete_room($id)) {
            $this->Audit_log_model->add_log(
                $this->session->userdata('user_id'),
                'admin',
                'delete_room',
                'room',
                $id,
                null
            );
            $this->session->set_flashdata('success', 'Room deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete room');
        }
        redirect('admin/rooms');
    }
    
    // Bulk action for rooms
    public function bulk_action_rooms() {
        $this->require_permission('manage_rooms');
        $action = $this->input->post('bulk_action');
        $room_ids = $this->input->post('room_ids');
        if (!$action || !$room_ids || !is_array($room_ids)) {
            $this->session->set_flashdata('error', 'No rooms selected or invalid action.');
            redirect('admin/rooms');
        }
        $count = 0;
        foreach ($room_ids as $id) {
            if ($action == 'delete') {
                if ($this->Room_model->delete_room($id)) {
                    $this->Audit_log_model->add_log(
                        $this->session->userdata('user_id'),
                        'admin',
                        'bulk_delete_room',
                        'room',
                        $id,
                        null
                    );
                    $count++;
                }
            } elseif ($action == 'available' || $action == 'maintenance') {
                if ($this->Room_model->update_room_status($id, $action)) {
                    $this->Audit_log_model->add_log(
                        $this->session->userdata('user_id'),
                        'admin',
                        'bulk_update_room_status',
                        'room',
                        $id,
                        json_encode(['status' => $action])
                    );
                    $count++;
                }
            }
        }
        $this->session->set_flashdata('success', "Bulk action applied to $count rooms.");
        redirect('admin/rooms');
    }
    
    // Booking management
    public function bookings() {
        $this->require_permission('manage_bookings');
        $data['bookings'] = $this->Booking_model->get_all_bookings();
        $this->load->view('admin/bookings', $data);
    }
    
    // Bulk action for bookings
    public function bulk_action_bookings() {
        $this->require_permission('manage_bookings');
        $action = $this->input->post('bulk_action');
        $booking_ids = $this->input->post('booking_ids');
        if (!$action || !$booking_ids || !is_array($booking_ids)) {
            $this->session->set_flashdata('error', 'No bookings selected or invalid action.');
            redirect('admin/bookings');
        }
        $count = 0;
        foreach ($booking_ids as $id) {
            if ($action == 'delete') {
                if ($this->Booking_model->delete_booking($id)) {
                    $this->Audit_log_model->add_log(
                        $this->session->userdata('user_id'),
                        'admin',
                        'bulk_delete_booking',
                        'booking',
                        $id,
                        null
                    );
                    $count++;
                }
            } elseif ($action == 'cancel' || $action == 'confirm') {
                $status = $action == 'cancel' ? 'cancelled' : 'confirmed';
                if ($this->Booking_model->update_booking_status($id, $status)) {
                    $this->Audit_log_model->add_log(
                        $this->session->userdata('user_id'),
                        'admin',
                        'bulk_update_booking_status',
                        'booking',
                        $id,
                        json_encode(['status' => $status])
                    );
                    $count++;
                }
            }
        }
        $this->session->set_flashdata('success', "Bulk action applied to $count bookings.");
        redirect('admin/bookings');
    }
    
    // Update booking status
    public function update_booking_status($id, $status) {
        $this->require_permission('manage_bookings');
        if ($this->Booking_model->update_booking_status($id, $status)) {
            // Notify customer
            $booking = $this->Booking_model->get_booking_by_id($id);
            if ($booking && isset($booking->guest_email)) {
                send_booking_notification(
                    $booking->guest_email,
                    'Booking Status Updated',
                    'Your booking status has been updated to: ' . ucfirst($status)
                );
            }
            $this->Audit_log_model->add_log(
                $this->session->userdata('user_id'),
                'admin',
                'update_booking_status',
                'booking',
                $id,
                json_encode(['status' => $status])
            );
            $this->session->set_flashdata('success', 'Booking status updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update booking status');
        }
        redirect('admin/view_booking/'.$id);
    }
    
    // View booking details
    public function view_booking($id) {
        $data['booking'] = $this->Booking_model->get_booking_by_id($id);
        
        if (!$data['booking']) {
            redirect('admin/bookings');
        }
        
        $this->load->view('admin/view_booking', $data);
    }
    
    // Edit booking
    public function edit_booking($id) {
        $this->require_permission('manage_bookings');
        $data['booking'] = $this->Booking_model->get_booking_by_id($id);
        $data['rooms'] = $this->Room_model->get_all_rooms();
        
        if (!$data['booking']) {
            redirect('admin/bookings');
        }
        
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('room_id', 'Room', 'required');
        $this->form_validation->set_rules('check_in_date', 'Check-in Date', 'required');
        $this->form_validation->set_rules('check_out_date', 'Check-out Date', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/edit_booking', $data);
        } else {
            $update_data = [
                'status' => $this->input->post('status'),
                'room_id' => $this->input->post('room_id'),
                'check_in_date' => $this->input->post('check_in_date'),
                'check_out_date' => $this->input->post('check_out_date'),
                'special_requests' => $this->input->post('special_requests')
            ];
            
            // Recalculate total amount if room or dates changed
            $room = $this->Room_model->get_room_by_id($this->input->post('room_id'));
            $check_in = new DateTime($this->input->post('check_in_date'));
            $check_out = new DateTime($this->input->post('check_out_date'));
            $nights = $check_in->diff($check_out)->days;
            $update_data['total_amount'] = $room->price_per_night * $nights;
            
            if ($this->Booking_model->update_booking($id, $update_data)) {
                // Notify customer
                $booking = $this->Booking_model->get_booking_by_id($id);
                if ($booking && isset($booking->guest_email)) {
                    send_booking_notification(
                        $booking->guest_email,
                        'Booking Modified by Admin',
                        'Your booking has been modified by the admin.'
                    );
                }
                $this->Audit_log_model->add_log(
                    $this->session->userdata('user_id'),
                    'admin',
                    'edit_booking',
                    'booking',
                    $id,
                    json_encode($update_data)
                );
                $this->session->set_flashdata('success', 'Booking updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update booking');
            }
            redirect('admin/view_booking/'.$id);
        }
    }
    
    // Reports
    public function reports() {
        // Occupancy and revenue for last 30 days
        $occupancy = [];
        $revenue = [];
        $today = strtotime(date('Y-m-d'));
        $room_count = $this->db->count_all('rooms');
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days", $today));
            $occupied = $this->db->where_in('status', ['confirmed', 'checked_in'])
                ->where('check_in_date <=', $date)
                ->where('check_out_date >', $date)
                ->count_all_results('bookings');
            $day_revenue = $this->db->select_sum('total_amount')
                ->where_in('status', ['confirmed', 'checked_in', 'checked_out'])
                ->where('check_in_date <=', $date)
                ->where('check_out_date >', $date)
                ->get('bookings')->row()->total_amount;
            $occupancy[] = ['date' => $date, 'occupied' => $occupied, 'total' => $room_count];
            $revenue[] = ['date' => $date, 'revenue' => $day_revenue ? $day_revenue : 0];
        }
        // Top guests (all time)
        $top_guests = $this->db->select('users.*, COUNT(bookings.id) as total_bookings, SUM(bookings.total_amount) as total_spend')
            ->from('users')
            ->join('bookings', 'users.id = bookings.user_id', 'left')
            ->where('users.role', 'customer')
            ->group_by('users.id')
            ->order_by('total_spend', 'DESC')
            ->limit(10)
            ->get()->result();
        $this->load->view('admin/reports', [
            'occupancy' => $occupancy,
            'revenue' => $revenue,
            'top_guests' => $top_guests
        ]);
    }

    public function export_occupancy_csv() {
        $room_count = $this->db->count_all('rooms');
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=occupancy_report_' . date('Ymd_His') . '.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'Occupied Rooms', 'Total Rooms', 'Occupancy %']);
        $today = strtotime(date('Y-m-d'));
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days", $today));
            $occupied = $this->db->where_in('status', ['confirmed', 'checked_in'])
                ->where('check_in_date <=', $date)
                ->where('check_out_date >', $date)
                ->count_all_results('bookings');
            $percent = $room_count > 0 ? round(($occupied/$room_count)*100,1) : 0;
            fputcsv($output, [$date, $occupied, $room_count, $percent.'%']);
        }
        fclose($output);
        exit;
    }

    public function export_revenue_csv() {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=revenue_report_' . date('Ymd_His') . '.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'Revenue']);
        $today = strtotime(date('Y-m-d'));
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days", $today));
            $day_revenue = $this->db->select_sum('total_amount')
                ->where_in('status', ['confirmed', 'checked_in', 'checked_out'])
                ->where('check_in_date <=', $date)
                ->where('check_out_date >', $date)
                ->get('bookings')->row()->total_amount;
            fputcsv($output, [$date, $day_revenue ? $day_revenue : 0]);
        }
        fclose($output);
        exit;
    }

    public function export_top_guests_csv() {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=top_guests_' . date('Ymd_His') . '.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Guest', 'Email', 'Total Bookings', 'Total Spend']);
        $top_guests = $this->db->select('users.*, COUNT(bookings.id) as total_bookings, SUM(bookings.total_amount) as total_spend')
            ->from('users')
            ->join('bookings', 'users.id = bookings.user_id', 'left')
            ->where('users.role', 'customer')
            ->group_by('users.id')
            ->order_by('total_spend', 'DESC')
            ->limit(10)
            ->get()->result();
        foreach ($top_guests as $guest) {
            fputcsv($output, [
                $guest->first_name . ' ' . $guest->last_name,
                $guest->email,
                (int)$guest->total_bookings,
                number_format($guest->total_spend, 2)
            ]);
        }
        fclose($output);
        exit;
    }

    // View waitlist
    public function waitlist() {
        $this->require_permission('manage_bookings');
        $this->load->model('Waitlist_model');
        $data['waitlist'] = $this->Waitlist_model->get_all();
        $this->load->view('admin/waitlist', $data);
    }

    // Promote waitlist entry to booking
    public function promote_waitlist($id) {
        $this->require_permission('manage_bookings');
        $this->load->model(['Waitlist_model', 'Booking_model', 'Room_model']);
        $this->load->helper('notification');
        $entry = $this->Waitlist_model->get_by_id($id);
        if (!$entry || $entry->status != 'waiting') {
            $this->session->set_flashdata('error', 'Invalid or already processed waitlist entry.');
            redirect('admin/waitlist');
        }
        // Find an available room of the requested type
        $rooms = $this->Room_model->get_rooms_by_type($entry->room_type);
        $available_room = null;
        foreach ($rooms as $room) {
            if ($this->Booking_model->is_room_available($room->id, $entry->check_in_date, $entry->check_out_date)) {
                $available_room = $room;
                break;
            }
        }
        if (!$available_room) {
            $this->session->set_flashdata('error', 'No available room found for this waitlist entry.');
            redirect('admin/waitlist');
        }
        // Create booking
        $booking_data = [
            'user_id' => $entry->user_id,
            'room_id' => $available_room->id,
            'check_in_date' => $entry->check_in_date,
            'check_out_date' => $entry->check_out_date,
            'adults' => $entry->adults,
            'children' => $entry->children,
            'total_amount' => $available_room->price_per_night * ((new DateTime($entry->check_out_date))->diff(new DateTime($entry->check_in_date))->days),
            'special_requests' => $entry->special_requests,
            'guest_name' => $entry->guest_name,
            'guest_email' => $entry->guest_email,
            'guest_phone' => $entry->guest_phone,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->Booking_model->create_booking($booking_data);
        $this->Waitlist_model->update_status($id, 'booked');
        // Notify user
        if (!empty($entry->guest_email)) {
            send_booking_notification(
                $entry->guest_email,
                'Room Now Available - Booking Created',
                'Good news! A room matching your waitlist request is now available and a booking has been created for you. Please log in or contact us to confirm.'
            );
        }
        $this->session->set_flashdata('success', 'Waitlist entry promoted to booking.');
        redirect('admin/waitlist');
    }

    // Mark waitlist entry as notified
    public function mark_waitlist_notified($id) {
        $this->require_permission('manage_bookings');
        $this->load->model('Waitlist_model');
        $this->Waitlist_model->update_status($id, 'notified');
        $this->session->set_flashdata('success', 'Waitlist entry marked as notified.');
        redirect('admin/waitlist');
    }

    // Mark waitlist entry as cancelled
    public function mark_waitlist_cancelled($id) {
        $this->require_permission('manage_bookings');
        $this->load->model('Waitlist_model');
        $this->Waitlist_model->update_status($id, 'cancelled');
        $this->session->set_flashdata('success', 'Waitlist entry cancelled.');
        redirect('admin/waitlist');
    }

    // List all coupons
    public function coupons() {
        $this->require_permission('manage_bookings');
        $this->load->model('Coupon_model');
        $data['coupons'] = $this->Coupon_model->get_all_coupons();
        $this->load->view('admin/coupons', $data);
    }

    // Add a new coupon
    public function add_coupon() {
        $this->require_permission('manage_bookings');
        $this->load->model('Coupon_model');
        $this->form_validation->set_rules('code', 'Coupon Code', 'required|is_unique[coupons.code]');
        $this->form_validation->set_rules('discount_type', 'Discount Type', 'required');
        $this->form_validation->set_rules('discount_value', 'Discount Value', 'required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/add_coupon');
        } else {
            $data = [
                'code' => strtoupper($this->input->post('code')),
                'discount_type' => $this->input->post('discount_type'),
                'discount_value' => $this->input->post('discount_value'),
                'max_uses' => $this->input->post('max_uses'),
                'expiry_date' => $this->input->post('expiry_date'),
                'active' => $this->input->post('active') ? 1 : 0
            ];
            $this->Coupon_model->add_coupon($data);
            $this->session->set_flashdata('success', 'Coupon added successfully.');
            redirect('admin/coupons');
        }
    }

    // Edit coupon
    public function edit_coupon($id) {
        $this->require_permission('manage_bookings');
        $this->load->model('Coupon_model');
        $coupon = $this->Coupon_model->get_all_coupons();
        $data['coupon'] = null;
        foreach ($coupon as $c) { if ($c->id == $id) $data['coupon'] = $c; }
        if (!$data['coupon']) redirect('admin/coupons');
        $this->form_validation->set_rules('discount_type', 'Discount Type', 'required');
        $this->form_validation->set_rules('discount_value', 'Discount Value', 'required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/edit_coupon', $data);
        } else {
            $update = [
                'discount_type' => $this->input->post('discount_type'),
                'discount_value' => $this->input->post('discount_value'),
                'max_uses' => $this->input->post('max_uses'),
                'expiry_date' => $this->input->post('expiry_date'),
                'active' => $this->input->post('active') ? 1 : 0
            ];
            $this->Coupon_model->update_coupon($id, $update);
            $this->session->set_flashdata('success', 'Coupon updated successfully.');
            redirect('admin/coupons');
        }
    }

    // Delete coupon
    public function delete_coupon($id) {
        $this->require_permission('manage_bookings');
        $this->load->model('Coupon_model');
        $this->Coupon_model->delete_coupon($id);
        $this->session->set_flashdata('success', 'Coupon deleted.');
        redirect('admin/coupons');
    }

    // List all reviews
    public function reviews() {
        $this->require_permission('manage_bookings');
        $this->load->model('Review_model');
        $status = $this->input->get('status');
        $data['reviews'] = $this->Review_model->get_all_reviews($status);
        $this->load->view('admin/reviews', $data);
    }

    // Approve a review
    public function approve_review($id) {
        $this->require_permission('manage_bookings');
        $this->load->model('Review_model');
        $this->Review_model->approve_review($id);
        $this->session->set_flashdata('success', 'Review approved.');
        redirect('admin/reviews');
    }

    // Reject a review
    public function reject_review($id) {
        $this->require_permission('manage_bookings');
        $this->load->model('Review_model');
        $this->Review_model->reject_review($id);
        $this->session->set_flashdata('success', 'Review rejected.');
        redirect('admin/reviews');
    }

    // Reply to a review
    public function reply_review($id) {
        $this->require_permission('manage_bookings');
        $this->load->model('Review_model');
        $reply = $this->input->post('admin_reply');
        $this->Review_model->reply_review($id, $reply);
        $this->session->set_flashdata('success', 'Reply saved.');
        redirect('admin/reviews');
    }

    // Rate Plans Management
    public function rate_plans() {
        $this->require_permission('manage_rooms');
        $this->db->order_by('start_date DESC');
        $data['rate_plans'] = $this->db->get('rate_plans')->result();
        $data['room_types'] = $this->Room_model->get_room_types();
        $this->load->view('admin/rate_plans', $data);
    }

    public function save_rate_plan() {
        $this->require_permission('manage_rooms');
        $data = [
            'room_type' => $this->input->post('room_type'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'price_per_night' => $this->input->post('price_per_night'),
            'promotion_name' => $this->input->post('promotion_name'),
            'promotion_description' => $this->input->post('promotion_description'),
            'active' => 1
        ];
        $this->db->insert('rate_plans', $data);
        $this->session->set_flashdata('success', 'Rate plan saved.');
        redirect('admin/rate_plans');
    }

    public function edit_rate_plan($id) {
        $this->require_permission('manage_rooms');
        $plan = $this->db->get_where('rate_plans', ['id' => $id])->row();
        if (!$plan) {
            $this->session->set_flashdata('error', 'Rate plan not found.');
            redirect('admin/rate_plans');
        }
        $data['edit_plan'] = $plan;
        $data['rate_plans'] = $this->db->get('rate_plans')->result();
        $data['room_types'] = $this->Room_model->get_room_types();
        $this->load->view('admin/rate_plans', $data);
    }

    public function delete_rate_plan($id) {
        $this->require_permission('manage_rooms');
        $this->db->delete('rate_plans', ['id' => $id]);
        $this->session->set_flashdata('success', 'Rate plan deleted.');
        redirect('admin/rate_plans');
    }

    // List account deletion requests (GDPR)
    public function deletion_requests() {
        $this->require_permission('manage_users');
        $data['pending_deletions'] = $this->db->get_where('users', ['status' => 'pending_deletion'])->result();
        $this->load->view('admin/deletion_requests', $data);
    }

    // Approve and delete user account
    public function approve_deletion($user_id) {
        $this->require_permission('manage_users');
        $this->User_model->delete_user($user_id);
        $this->session->set_flashdata('success', 'User account deleted.');
        redirect('admin/deletion_requests');
    }

    // Restore user account (cancel deletion)
    public function restore_user($user_id) {
        $this->require_permission('manage_users');
        $this->User_model->update_user($user_id, ['status' => 'active']);
        $this->session->set_flashdata('success', 'User account restored.');
        redirect('admin/deletion_requests');
    }

    // System settings management
    public function settings() {
        $this->require_permission('manage_settings');
        $this->load->model('Settings_model');
        
        if ($this->input->post()) {
            $settings = $this->input->post('settings');
            foreach ($settings as $key => $value) {
                $this->Settings_model->set_setting($key, $value);
            }
            $this->session->set_flashdata('success', 'Settings updated successfully');
            redirect('admin/settings');
        }
        
        $data['settings'] = $this->Settings_model->get_all_settings();
        $this->load->view('admin/settings', $data);
    }

    // Email templates management
    public function email_templates() {
        $this->require_permission('manage_settings');
        $this->load->model('Email_template_model');
        
        $data['templates'] = $this->Email_template_model->get_all_templates();
        $this->load->view('admin/email_templates', $data);
    }

    // Edit email template
    public function edit_email_template($id) {
        $this->require_permission('manage_settings');
        $this->load->model('Email_template_model');
        
        if ($this->input->post()) {
            $template_data = [
                'name' => $this->input->post('name'),
                'subject' => $this->input->post('subject'),
                'body' => $this->input->post('body'),
                'variables' => json_encode($this->input->post('variables')),
                'active' => $this->input->post('active') ? 1 : 0
            ];
            
            if ($this->Email_template_model->update_template($id, $template_data)) {
                $this->session->set_flashdata('success', 'Email template updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update email template');
            }
            redirect('admin/email_templates');
        }
        
        $data['template'] = $this->Email_template_model->get_template_by_id($id);
        $this->load->view('admin/edit_email_template', $data);
    }

    // Security logs
    public function security_logs() {
        $this->require_permission('view_logs');
        
        // Pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url('admin/security_logs');
        $config['total_rows'] = $this->db->count_all('security_logs');
        $config['per_page'] = 50;
        $this->pagination->initialize($config);
        
        // Get logs with pagination
        $this->db->select('security_logs.*, users.username');
        $this->db->from('security_logs');
        $this->db->join('users', 'security_logs.user_id = users.id', 'left');
        $this->db->order_by('security_logs.created_at', 'DESC');
        $this->db->limit($config['per_page'], $this->input->get('per_page'));
        
        $data['logs'] = $this->db->get()->result();
        $data['pagination'] = $this->pagination->create_links();
        
        // Get security statistics
        $this->load->library('Security_helper');
        $data['stats'] = $this->security_helper->get_security_stats();
        
        $this->load->view('admin/security_logs', $data);
    }

    // Loyalty system management
    public function loyalty_system() {
        $this->require_permission('manage_loyalty');
        $this->load->model('Loyalty_model');
        
        $data['stats'] = $this->Loyalty_model->get_loyalty_stats();
        
        // Recent transactions
        $this->db->select('loyalty_transactions.*, users.username, users.first_name, users.last_name');
        $this->db->from('loyalty_transactions');
        $this->db->join('users', 'loyalty_transactions.user_id = users.id');
        $this->db->order_by('loyalty_transactions.created_at', 'DESC');
        $this->db->limit(20);
        $data['recent_transactions'] = $this->db->get()->result();
        
        $this->load->view('admin/loyalty_system', $data);
    }

    // Award loyalty points manually
    public function award_loyalty_points() {
        $this->require_permission('manage_loyalty');
        $this->load->model('Loyalty_model');
        
        $this->form_validation->set_rules('user_id', 'User', 'required|numeric');
        $this->form_validation->set_rules('points', 'Points', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('description', 'Description', 'required');
        
        if ($this->form_validation->run()) {
            $user_id = $this->input->post('user_id');
            $points = $this->input->post('points');
            $description = $this->input->post('description');
            
            if ($this->Loyalty_model->award_points($user_id, $points, $description)) {
                $this->session->set_flashdata('success', 'Loyalty points awarded successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to award loyalty points');
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }
        
        redirect('admin/loyalty_system');
    }

    // Room inventory management
    public function room_inventory() {
        $this->require_permission('manage_rooms');
        $this->load->model('Room_inventory_model');
        
        $start_date = $this->input->get('start_date') ?: date('Y-m-d');
        $end_date = $this->input->get('end_date') ?: date('Y-m-d', strtotime('+30 days'));
        
        $data['inventory'] = $this->Room_inventory_model->get_availability_report($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        $this->load->view('admin/room_inventory', $data);
    }

    // Block rooms for maintenance
    public function block_rooms() {
        $this->require_permission('manage_rooms');
        $this->load->model('Room_inventory_model');
        
        $this->form_validation->set_rules('room_type', 'Room Type', 'required');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run()) {
            $room_type = $this->input->post('room_type');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $quantity = $this->input->post('quantity');
            
            if ($this->Room_inventory_model->block_rooms($room_type, $start_date, $end_date, $quantity)) {
                $this->session->set_flashdata('success', 'Rooms blocked successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to block rooms');
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }
        
        redirect('admin/room_inventory');
    }

    // System backup
    public function backup() {
        $this->require_permission('system_backup');
        
        // Simple database backup (you might want to use a more robust solution)
        $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $backup_path = FCPATH . 'backups/' . $backup_file;
        
        // Create backups directory if it doesn't exist
        if (!is_dir(FCPATH . 'backups/')) {
            mkdir(FCPATH . 'backups/', 0755, true);
        }
        
        // Use mysqldump if available
        $db_config = $this->db->database;
        $command = "mysqldump -h {$db_config['hostname']} -u {$db_config['username']} -p{$db_config['password']} {$db_config['database']} > {$backup_path}";
        
        $output = [];
        $return_var = 0;
        exec($command, $output, $return_var);
        
        if ($return_var === 0 && file_exists($backup_path)) {
            $this->session->set_flashdata('success', "Database backup created: {$backup_file}");
        } else {
            $this->session->set_flashdata('error', 'Failed to create database backup');
        }
        
        redirect('admin/dashboard');
    }

    // System maintenance mode
    public function toggle_maintenance_mode() {
        $this->require_permission('system_maintenance');
        $this->load->model('Settings_model');
        
        $current_mode = $this->Settings_model->get_setting('maintenance_mode', 'false');
        $new_mode = ($current_mode === 'true') ? 'false' : 'true';
        
        $this->Settings_model->set_setting('maintenance_mode', $new_mode, 'System maintenance mode', 'boolean');
        
        $status = ($new_mode === 'true') ? 'enabled' : 'disabled';
        $this->session->set_flashdata('success', "Maintenance mode {$status}");
        
        redirect('admin/dashboard');
    }
} 