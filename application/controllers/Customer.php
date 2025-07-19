<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'Room_model', 'Booking_model']);
        $this->load->library(['form_validation', 'session']);
        
        // Check if user is logged in and is customer
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'customer') {
            redirect('auth/customer_login');
        }
    }
    
    // Customer dashboard
    public function dashboard() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['bookings'] = $this->Booking_model->get_bookings_by_user($user_id);
        $data['total_bookings'] = count($data['bookings']);
        $data['active_bookings'] = 0;
        $data['completed_bookings'] = 0;
        
        foreach ($data['bookings'] as $booking) {
            if (in_array($booking->status, ['confirmed', 'checked_in'])) {
                $data['active_bookings']++;
            } elseif ($booking->status == 'checked_out') {
                $data['completed_bookings']++;
            }
        }
        
        $this->load->view('customer/dashboard', $data);
    }
    
    // View available rooms
    public function rooms() {
        $data['rooms'] = $this->Room_model->get_available_rooms();
        $data['room_types'] = $this->Room_model->get_room_types();
        $this->load->view('customer/rooms', $data);
    }
    
    // Book a room
    public function book_room($room_id = null) {
        if ($room_id) {
            $data['room'] = $this->Room_model->get_room_by_id($room_id);
            if (!$data['room'] || $data['room']->status != 'available') {
                $this->session->set_flashdata('error', 'Room not available');
                redirect('customer/rooms');
            }
        } else {
            $data['rooms'] = $this->Room_model->get_available_rooms();
        }
        
        $this->form_validation->set_rules('room_id', 'Room', 'required');
        $this->form_validation->set_rules('check_in_date', 'Check-in Date', 'required');
        $this->form_validation->set_rules('check_out_date', 'Check-out Date', 'required');
        $this->form_validation->set_rules('special_requests', 'Special Requests', 'max_length[500]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('customer/book_room', $data);
        } else {
            $room_id = $this->input->post('room_id');
            $check_in = $this->input->post('check_in_date');
            $check_out = $this->input->post('check_out_date');
            
            // Check if room is available for the selected dates
            if (!$this->Booking_model->is_room_available($room_id, $check_in, $check_out)) {
                $this->session->set_flashdata('error', 'Room is not available for the selected dates');
                redirect('customer/book_room');
            }
            
            // Calculate total amount
            $room = $this->Room_model->get_room_by_id($room_id);
            $check_in_date = new DateTime($check_in);
            $check_out_date = new DateTime($check_out);
            $nights = $check_in_date->diff($check_out_date)->days;
            $total_amount = $room->price_per_night * $nights;
            
            $booking_data = [
                'user_id' => $this->session->userdata('user_id'),
                'room_id' => $room_id,
                'check_in_date' => $check_in,
                'check_out_date' => $check_out,
                'total_amount' => $total_amount,
                'special_requests' => $this->input->post('special_requests'),
                'status' => 'pending'
            ];
            
            if ($this->Booking_model->create_booking($booking_data)) {
                $this->session->set_flashdata('success', 'Booking created successfully! Please wait for confirmation.');
                redirect('customer/my_bookings');
            } else {
                $this->session->set_flashdata('error', 'Failed to create booking');
                redirect('customer/book_room');
            }
        }
    }
    
    // View my bookings
    public function my_bookings() {
        $user_id = $this->session->userdata('user_id');
        $data['bookings'] = $this->Booking_model->get_bookings_by_user($user_id);
        $this->load->view('customer/my_bookings', $data);
    }
    
    // View booking details
    public function view_booking($id) {
        $user_id = $this->session->userdata('user_id');
        $data['booking'] = $this->Booking_model->get_booking_by_id($id);
        
        if (!$data['booking'] || $data['booking']->user_id != $user_id) {
            redirect('customer/my_bookings');
        }
        
        $this->load->view('customer/view_booking', $data);
    }
    
    // Cancel booking
    public function cancel_booking($id) {
        $user_id = $this->session->userdata('user_id');
        $booking = $this->Booking_model->get_booking_by_id($id);
        
        if (!$booking || $booking->user_id != $user_id) {
            $this->session->set_flashdata('error', 'Invalid booking');
            redirect('customer/my_bookings');
        }
        
        if ($booking->status != 'pending' && $booking->status != 'confirmed') {
            $this->session->set_flashdata('error', 'Cannot cancel this booking');
            redirect('customer/my_bookings');
        }
        
        if ($this->Booking_model->update_booking_status($id, 'cancelled')) {
            $this->session->set_flashdata('success', 'Booking cancelled successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel booking');
        }
        
        redirect('customer/my_bookings');
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
            $this->load->view('customer/profile', $data);
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
            redirect('customer/profile');
        }
    }
    
    // Change password
    public function change_password() {
        $user_id = $this->session->userdata('user_id');
        
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('customer/change_password');
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
            redirect('customer/change_password');
        }
    }
    
    // Search rooms
    public function search_rooms() {
        $room_type = $this->input->get('room_type');
        $min_price = $this->input->get('min_price');
        $max_price = $this->input->get('max_price');
        
        $this->db->where('status', 'available');
        
        if ($room_type) {
            $this->db->where('room_type', $room_type);
        }
        
        if ($min_price) {
            $this->db->where('price_per_night >=', $min_price);
        }
        
        if ($max_price) {
            $this->db->where('price_per_night <=', $max_price);
        }
        
        $data['rooms'] = $this->db->get('rooms')->result();
        $data['room_types'] = $this->Room_model->get_room_types();
        
        $this->load->view('customer/search_rooms', $data);
    }
} 