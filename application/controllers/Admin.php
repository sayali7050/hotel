<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'Room_model', 'Booking_model']);
        $this->load->library(['form_validation', 'session']);
        
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/admin_login');
        }
    }
    
    // Admin dashboard
    public function dashboard() {
        $data['total_users'] = $this->User_model->get_user_count_by_role('customer');
        $data['total_staff'] = $this->User_model->get_user_count_by_role('staff');
        $data['total_rooms'] = $this->db->count_all('rooms');
        $data['available_rooms'] = $this->Room_model->get_room_count_by_status('available');
        $data['total_bookings'] = $this->db->count_all('bookings');
        $data['pending_bookings'] = $this->Booking_model->get_booking_count_by_status('pending');
        $data['total_revenue'] = $this->Booking_model->get_total_revenue();
        $data['recent_bookings'] = $this->Booking_model->get_recent_bookings(5);
        $data['recent_users'] = $this->User_model->get_recent_users(5);
        
        $this->load->view('admin/dashboard', $data);
    }
    
    // User management
    public function users() {
        $data['customers'] = $this->User_model->get_users_by_role('customer');
        $data['staff'] = $this->User_model->get_staff_with_assignments();
        
        $this->load->view('admin/users', $data);
    }
    
    // Add staff
    public function add_staff() {
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
                $this->session->set_flashdata('success', 'User updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user');
            }
            redirect('admin/users');
        }
    }
    
    // Delete user
    public function delete_user($id) {
        if ($this->User_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'User deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user');
        }
        redirect('admin/users');
    }
    
    // Room management
    public function rooms() {
        $data['rooms'] = $this->Room_model->get_all_rooms();
        $this->load->view('admin/rooms', $data);
    }
    
    // Add room
    public function add_room() {
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
                $this->session->set_flashdata('success', 'Room updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update room');
            }
            redirect('admin/rooms');
        }
    }
    
    // Delete room
    public function delete_room($id) {
        if ($this->Room_model->delete_room($id)) {
            $this->session->set_flashdata('success', 'Room deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete room');
        }
        redirect('admin/rooms');
    }
    
    // Booking management
    public function bookings() {
        $data['bookings'] = $this->Booking_model->get_all_bookings();
        $this->load->view('admin/bookings', $data);
    }
    
    // Update booking status
    public function update_booking_status($id) {
        $status = $this->input->post('status');
        
        if ($this->Booking_model->update_booking_status($id, $status)) {
            $this->session->set_flashdata('success', 'Booking status updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update booking status');
        }
        redirect('admin/bookings');
    }
    
    // View booking details
    public function view_booking($id) {
        $data['booking'] = $this->Booking_model->get_booking_by_id($id);
        
        if (!$data['booking']) {
            redirect('admin/bookings');
        }
        
        $this->load->view('admin/view_booking', $data);
    }
    
    // Reports
    public function reports() {
        $data['total_revenue'] = $this->Booking_model->get_total_revenue();
        $data['monthly_revenue'] = $this->Booking_model->get_monthly_revenue(date('Y'), date('m'));
        $data['booking_stats'] = [
            'pending' => $this->Booking_model->get_booking_count_by_status('pending'),
            'confirmed' => $this->Booking_model->get_booking_count_by_status('confirmed'),
            'checked_in' => $this->Booking_model->get_booking_count_by_status('checked_in'),
            'checked_out' => $this->Booking_model->get_booking_count_by_status('checked_out'),
            'cancelled' => $this->Booking_model->get_booking_count_by_status('cancelled')
        ];
        
        $this->load->view('admin/reports', $data);
    }
} 