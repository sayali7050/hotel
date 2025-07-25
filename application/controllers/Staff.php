<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'Room_model', 'Booking_model']);
        $this->load->model('Audit_log_model');
        $this->load->helper('notification');
        $this->load->library(['form_validation', 'session']);
        
        // Check if user is logged in and is staff
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'staff') {
            redirect('auth/staff_login');
        }
    }
    
    // Staff dashboard
    public function dashboard() {
        $data['total_rooms'] = $this->db->count_all('rooms');
        $data['available_rooms'] = $this->Room_model->get_room_count_by_status('available');
        $data['occupied_rooms'] = $this->Room_model->get_room_count_by_status('occupied');
        $data['maintenance_rooms'] = $this->Room_model->get_room_count_by_status('maintenance');
        $data['total_bookings'] = $this->db->count_all('bookings');
        $data['pending_bookings'] = $this->Booking_model->get_booking_count_by_status('pending');
        $data['checked_in_bookings'] = $this->Booking_model->get_booking_count_by_status('checked_in');
        $data['recent_bookings'] = $this->Booking_model->get_recent_bookings(10);
        
        $this->load->view('staff/dashboard', $data);
    }
    
    // View all bookings
    public function bookings() {
        $data['bookings'] = $this->Booking_model->get_all_bookings();
        $this->load->view('staff/bookings', $data);
    }
    
    // View booking details
    public function view_booking($id) {
        $data['booking'] = $this->Booking_model->get_booking_by_id($id);
        
        if (!$data['booking']) {
            redirect('staff/bookings');
        }
        
        $this->load->view('staff/view_booking', $data);
    }
    
    // Update booking status
    public function update_booking_status($id) {
        $status = $this->input->post('status');
        
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
                'staff',
                'update_booking_status',
                'booking',
                $id,
                json_encode(['status' => $status])
            );
            // Update room status based on booking status
            $booking = $this->Booking_model->get_booking_by_id($id);
            if ($booking) {
                $room_status = 'available';
                if ($status == 'checked_in') {
                    $room_status = 'occupied';
                } elseif ($status == 'confirmed') {
                    $room_status = 'reserved';
                }
                
                $this->Room_model->update_room_status($booking->room_id, $room_status);
            }
            
            $this->session->set_flashdata('success', 'Booking status updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update booking status');
        }
        redirect('staff/bookings');
    }
    
    // Room management
    public function rooms() {
        $data['rooms'] = $this->Room_model->get_all_rooms();
        $this->load->view('staff/rooms', $data);
    }
    
    // Update room status
    public function update_room_status($id) {
        $status = $this->input->post('status');
        
        if ($this->Room_model->update_room_status($id, $status)) {
            $this->Audit_log_model->add_log(
                $this->session->userdata('user_id'),
                'staff',
                'update_room_status',
                'room',
                $id,
                json_encode(['status' => $status])
            );
            $this->session->set_flashdata('success', 'Room status updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update room status');
        }
        redirect('staff/rooms');
    }
    
    // Check-in guest
    public function check_in($booking_id) {
        $booking = $this->Booking_model->get_booking_by_id($booking_id);
        
        if (!$booking || $booking->status != 'confirmed') {
            $this->session->set_flashdata('error', 'Invalid booking or booking not confirmed');
            redirect('staff/bookings');
        }
        
        // Update booking status to checked_in
        if ($this->Booking_model->update_booking_status($booking_id, 'checked_in')) {
            // Update room status to occupied
            $this->Room_model->update_room_status($booking->room_id, 'occupied');
            // Notify customer
            if ($booking && isset($booking->guest_email)) {
                send_booking_notification(
                    $booking->guest_email,
                    'Checked In',
                    'You have been checked in. Enjoy your stay!'
                );
            }
            $this->Audit_log_model->add_log(
                $this->session->userdata('user_id'),
                'staff',
                'check_in',
                'booking',
                $booking_id,
                null
            );
            $this->session->set_flashdata('success', 'Guest checked in successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to check in guest');
        }
        
        redirect('staff/bookings');
    }
    
    // Check-out guest
    public function check_out($booking_id) {
        $booking = $this->Booking_model->get_booking_by_id($booking_id);
        
        if (!$booking || $booking->status != 'checked_in') {
            $this->session->set_flashdata('error', 'Invalid booking or guest not checked in');
            redirect('staff/bookings');
        }
        
        // Update booking status to checked_out
        if ($this->Booking_model->update_booking_status($booking_id, 'checked_out')) {
            // Update room status to available
            $this->Room_model->update_room_status($booking->room_id, 'available');
            // Notify customer
            if ($booking && isset($booking->guest_email)) {
                send_booking_notification(
                    $booking->guest_email,
                    'Checked Out',
                    'You have been checked out. Thank you for staying with us!'
                );
            }
            $this->Audit_log_model->add_log(
                $this->session->userdata('user_id'),
                'staff',
                'check_out',
                'booking',
                $booking_id,
                null
            );
            $this->session->set_flashdata('success', 'Guest checked out successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to check out guest');
        }
        
        redirect('staff/bookings');
    }
    
    // Search bookings
    public function search_bookings() {
        $search = $this->input->get('search');
        $status = $this->input->get('status');
        $room_type = $this->input->get('room_type');
        $check_in = $this->input->get('check_in');
        $check_out = $this->input->get('check_out');
        $this->db->select('bookings.*, users.first_name, users.last_name, users.email, rooms.room_number, rooms.room_type');
        $this->db->from('bookings');
        $this->db->join('users', 'bookings.user_id = users.id');
        $this->db->join('rooms', 'bookings.room_id = rooms.id');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('users.first_name', $search);
            $this->db->or_like('users.last_name', $search);
            $this->db->or_like('users.email', $search);
            $this->db->or_like('rooms.room_number', $search);
            $this->db->group_end();
        }
        
        if ($status) {
            $this->db->where('bookings.status', $status);
        }
        if ($room_type) {
            $this->db->where('rooms.room_type', $room_type);
        }
        if ($check_in) {
            $this->db->where('bookings.check_in_date >=', $check_in);
        }
        if ($check_out) {
            $this->db->where('bookings.check_out_date <=', $check_out);
        }
        
        $this->db->order_by('bookings.created_at', 'DESC');
        $data['bookings'] = $this->db->get()->result();
        $this->load->model('Room_model');
        $data['room_types'] = $this->Room_model->get_room_types();
        $this->load->view('staff/search_bookings', $data);
    }
    
    // Profile management
    public function profile() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('staff/profile', $data);
        } else {
            $update_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            ];
            
            if ($this->User_model->update_user($user_id, $update_data)) {
                $this->session->set_flashdata('success', 'Profile updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update profile');
            }
            redirect('staff/profile');
        }
    }
    
    // Change password
    public function change_password() {
        $user_id = $this->session->userdata('user_id');
        
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('staff/change_password');
        } else {
            $user = $this->User_model->get_user_by_id($user_id);
            $current_password = $this->input->post('current_password');
            
            if (password_verify($current_password, $user->password)) {
                $update_data = ['password' => $this->input->post('new_password')];
                
                if ($this->User_model->update_user($user_id, $update_data)) {
                    $this->session->set_flashdata('success', 'Password changed successfully');
                } else {
                    $this->session->set_flashdata('error', 'Failed to change password');
                }
            } else {
                $this->session->set_flashdata('error', 'Current password is incorrect');
            }
            redirect('staff/change_password');
        }
    }
} 