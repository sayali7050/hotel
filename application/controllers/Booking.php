<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Room_model', 'Booking_model', 'User_model']);
        $this->load->library(['form_validation', 'session']);
    }

    // Main booking page
    public function index() {
        $data['room_types'] = $this->Room_model->get_room_types();
        $data['available_rooms'] = $this->Room_model->get_available_rooms();
        $this->load->view('booking/index', $data);
    }

    // Search room availability
    public function search_availability() {
        $this->form_validation->set_rules('check_in_date', 'Check-in Date', 'required');
        $this->form_validation->set_rules('check_out_date', 'Check-out Date', 'required');
        $this->form_validation->set_rules('adults', 'Adults', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('children', 'Children', 'numeric');
        $this->form_validation->set_rules('rooms', 'Rooms', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('booking');
        }

        $check_in = $this->input->post('check_in_date');
        $check_out = $this->input->post('check_out_date');
        $adults = $this->input->post('adults');
        $children = $this->input->post('children');
        $rooms = $this->input->post('rooms');
        $special_requests = $this->input->post('special_requests');

        // Validate dates
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $today = new DateTime();
        if ($check_in_date < $today) {
            $this->session->set_flashdata('error', 'Check-in date cannot be in the past');
            redirect('booking');
        }
        if ($check_out_date <= $check_in_date) {
            $this->session->set_flashdata('error', 'Check-out date must be after check-in date');
            redirect('booking');
        }

        // Store search criteria in session
        $search_data = [
            'check_in_date' => $check_in,
            'check_out_date' => $check_out,
            'adults' => $adults,
            'children' => $children,
            'rooms' => $rooms,
            'special_requests' => $special_requests,
            'nights' => $check_in_date->diff($check_out_date)->days
        ];
        $this->session->set_userdata('booking_search', $search_data);

        // Find available rooms
        $available_rooms = $this->find_available_rooms($check_in, $check_out, $adults, $children, $rooms);
        if (empty($available_rooms)) {
            $this->session->set_flashdata('error', 'No rooms available for the selected dates and criteria');
            redirect('booking');
        }
        redirect('booking/select_room');
    }

    // Select room from available options
    public function select_room() {
        $search_data = $this->session->userdata('booking_search');
        if (!$search_data) redirect('booking');
        $available_rooms = $this->find_available_rooms(
            $search_data['check_in_date'],
            $search_data['check_out_date'],
            $search_data['adults'],
            $search_data['children'],
            $search_data['rooms']
        );
        $data['rooms'] = $available_rooms;
        $data['search_data'] = $search_data;
        $this->load->view('booking/select_room', $data);
    }

    // Room details and booking form
    public function book_room($room_id) {
        // Require login before booking
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirect_after_login', current_url());
            redirect('customer/login');
        }
        $search_data = $this->session->userdata('booking_search');
        if (!$search_data) redirect('booking');
        $data['room'] = $this->Room_model->get_room_by_id($room_id);
        if (!$data['room']) {
            $this->session->set_flashdata('error', 'Room not found');
            redirect('booking/select_room');
        }
        if (!$this->Booking_model->is_room_available($room_id, $search_data['check_in_date'], $search_data['check_out_date'])) {
            $this->session->set_flashdata('error', 'Room is no longer available for the selected dates');
            redirect('booking/select_room');
        }
        $data['search_data'] = $search_data;
        $data['total_amount'] = $data['room']->price_per_night * $search_data['nights'];
        $this->load->view('booking/book_room', $data);
    }

    // Process booking
    public function process_booking() {
        // Emergency: Show all errors
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            echo "<h2 style='color:red;'>PHP Error [$errno]</h2>";
            echo "<pre>$errstr in $errfile on line $errline</pre>";
            exit;
        });
        register_shutdown_function(function() {
            $error = error_get_last();
            if ($error) {
                echo "<h2 style='color:red;'>Fatal Error</h2>";
                echo "<pre>" . print_r($error, true) . "</pre>";
            }
        });
        $search_data = $this->session->userdata('booking_search');
        if (!$search_data) redirect('booking');
        $this->form_validation->set_rules('room_id', 'Room', 'required|numeric');
        $this->form_validation->set_rules('guest_name', 'Guest Name', 'required');
        $this->form_validation->set_rules('guest_email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('guest_phone', 'Phone', 'required');
        $this->form_validation->set_rules('guest_address', 'Address', 'required');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required');
        $this->form_validation->set_rules('terms_accepted', 'Terms and Conditions', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('booking/book_room/' . $this->input->post('room_id'));
        }
        $room_id = $this->input->post('room_id');
        $room = $this->Room_model->get_room_by_id($room_id);
        if (!$room) {
            $this->session->set_flashdata('error', 'Room not found');
            redirect('booking/select_room');
        }
        if (!$this->Booking_model->is_room_available($room_id, $search_data['check_in_date'], $search_data['check_out_date'])) {
            $this->session->set_flashdata('error', 'Room is no longer available');
            redirect('booking/select_room');
        }
        $user_id = null;
        if ($this->session->userdata('logged_in')) {
            $user_id = $this->session->userdata('user_id');
        } else {
            $guest_data = [
                'username' => 'guest_' . time() . '_' . rand(1000, 9999),
                'first_name' => $this->input->post('guest_name'),
                'last_name' => '',
                'email' => $this->input->post('guest_email'),
                'phone' => $this->input->post('guest_phone'),
                'address' => $this->input->post('guest_address'),
                'role' => 'customer',
                'status' => 'active',
                'password' => password_hash(uniqid(), PASSWORD_DEFAULT)
            ];
            $user_id = $this->User_model->create_user($guest_data);
            if (!$user_id) {
                echo '<h2 style="color:red;">Failed to create guest user account</h2>';
                echo '<pre>' . print_r($guest_data, true) . '</pre>';
                echo '<pre>DB Error: ' . print_r($this->db->error(), true) . '</pre>';
                exit;
            }
        }
        // Extra safety: Check user_id and room_id
        if (!$user_id || !$room_id) {
            echo '<h2 style="color:red;">Invalid user_id or room_id</h2>';
            echo '<pre>user_id: ' . print_r($user_id, true) . '</pre>';
            echo '<pre>room_id: ' . print_r($room_id, true) . '</pre>';
            exit;
        }
        // Double-check user_id exists
        $user_check = $this->User_model->get_user_by_id($user_id);
        if (!$user_check) {
            echo '<h2 style="color:red;">user_id does not exist in users table</h2>';
            echo '<pre>user_id: ' . print_r($user_id, true) . '</pre>';
            exit;
        }
        // Double-check room_id exists
        $room_check = $this->Room_model->get_room_by_id($room_id);
        if (!$room_check) {
            echo '<h2 style="color:red;">room_id does not exist in rooms table</h2>';
            echo '<pre>room_id: ' . print_r($room_id, true) . '</pre>';
            exit;
        }
        $total_amount = $room->price_per_night * $search_data['nights'];
        $booking_data = [
            'user_id' => $user_id,
            'room_id' => $room_id,
            'check_in_date' => $search_data['check_in_date'],
            'check_out_date' => $search_data['check_out_date'],
            'adults' => $search_data['adults'],
            'children' => $search_data['children'],
            'rooms' => $search_data['rooms'],
            'total_amount' => $total_amount,
            'special_requests' => $search_data['special_requests'],
            'guest_name' => $this->input->post('guest_name'),
            'guest_email' => $this->input->post('guest_email'),
            'guest_phone' => $this->input->post('guest_phone'),
            'guest_address' => $this->input->post('guest_address'),
            'payment_method' => $this->input->post('payment_method'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $booking_id = $this->Booking_model->create_booking($booking_data);
        if ($booking_id) {
            $this->session->unset_userdata('booking_search');
            $this->session->set_userdata('last_booking_id', $booking_id);
            redirect('booking/confirmation');
        } else {
            echo '<h2 style="color:red;">Failed to create booking. Please try again.</h2>';
            echo '<h3>Booking Data:</h3>';
            echo '<pre>' . print_r($booking_data, true) . '</pre>';
            echo '<pre>DB Error: ' . print_r($this->db->error(), true) . '</pre>';
            exit;
        }
    }

    // Booking confirmation page
    public function confirmation() {
        $booking_id = $this->session->userdata('last_booking_id');
        if (!$booking_id) redirect('booking');
        $data['booking'] = $this->Booking_model->get_booking_by_id($booking_id);
        if (!$data['booking']) redirect('booking');
        $this->load->view('booking/confirmation', $data);
    }

    // Find available rooms based on criteria
    private function find_available_rooms($check_in, $check_out, $adults, $children, $rooms) {
        $total_guests = $adults + $children;
        $all_rooms = $this->Room_model->get_all_rooms();
        $available_rooms = [];
        foreach ($all_rooms as $room) {
            if ($this->Booking_model->is_room_available($room->id, $check_in, $check_out)) {
                if ($room->capacity >= $total_guests) {
                    $available_rooms[] = $room;
                }
            }
        }
        return $available_rooms;
    }

    // Cancel booking (for guests)
    public function cancel_booking($booking_id) {
        $booking = $this->Booking_model->get_booking_by_id($booking_id);
        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking not found');
            redirect('booking');
        }
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            $this->session->set_flashdata('error', 'This booking cannot be cancelled');
            redirect('booking');
        }
        if ($this->Booking_model->update_booking_status($booking_id, 'cancelled')) {
            $this->session->set_flashdata('success', 'Booking cancelled successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel booking');
        }
        redirect('booking');
    }
} 