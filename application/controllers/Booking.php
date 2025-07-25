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
            // Store search data for waitlist
            $this->session->set_userdata('waitlist_search', $search_data);
            redirect('booking/waitlist_form');
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
        $data['applied_coupon'] = $this->session->userdata('applied_coupon') ?? null;
        $this->load->view('booking/book_room', $data);
    }

    // AJAX endpoint to apply coupon (optional, for real-time feedback)
    public function apply_coupon() {
        $this->load->model('Coupon_model');
        $code = $this->input->post('coupon_code');
        $room_id = $this->input->post('room_id');
        $nights = $this->input->post('nights');
        $room = $this->Room_model->get_room_by_id($room_id);
        $coupon = $this->Coupon_model->get_active_coupon($code);
        $discount = 0;
        if ($coupon) {
            $base = $room->price_per_night * $nights;
            $discount = $coupon->discount_type == 'percent' ? ($base * $coupon->discount_value / 100) : $coupon->discount_value;
            $discount = min($discount, $base);
            echo json_encode(['success' => true, 'discount' => $discount, 'code' => $coupon->code]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid or expired coupon.']);
        }
        exit;
    }

    // Process booking
    public function process_booking() {
        $search_data = $this->session->userdata('booking_search');
        if (!$search_data) redirect('booking');
        $room_type = $search_data['room_type'];
        $quantity = isset($search_data['rooms']) ? (int)$search_data['rooms'] : 1;
        $check_in = $search_data['check_in_date'];
        $check_out = $search_data['check_out_date'];
        // Check inventory
        if (!$this->Booking_model->is_room_type_available($room_type, $check_in, $check_out, $quantity)) {
            $this->session->set_flashdata('error', 'Not enough rooms available for the selected dates.');
            redirect('booking');
        }
        // Reserve inventory
        $this->Booking_model->reserve_room_inventory($room_type, $check_in, $check_out, $quantity);
        // Extra safety: Check user_id and room_id
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
        // Create bookings for each room requested
        for ($i = 0; $i < $quantity; $i++) {
            // Calculate total amount using rate plans
            $total_amount = 0;
            $current = strtotime($check_in);
            $end = strtotime($check_out);
            while ($current < $end) {
                $date = date('Y-m-d', $current);
                $rate = $this->Booking_model->get_price_per_night($room_type, $date);
                $total_amount += $rate;
                $current = strtotime('+1 day', $current);
            }
            $booking_data = [
                'user_id' => $user_id,
                'room_id' => null, // assign specific room if needed
                'room_type' => $room_type,
                'check_in_date' => $check_in,
                'check_out_date' => $check_out,
                'adults' => $search_data['adults'],
                'children' => $search_data['children'],
                'rooms' => 1,
                'total_amount' => $total_amount,
                'status' => 'confirmed',
                'special_requests' => $search_data['special_requests'] ?? '',
                'guest_name' => $this->input->post('guest_name'),
                'guest_email' => $this->input->post('guest_email'),
                'guest_phone' => $this->input->post('guest_phone'),
                'guest_address' => $this->input->post('guest_address'),
                'payment_method' => $this->input->post('payment_method'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->Booking_model->create_booking($booking_data);
            // Award loyalty points: 1 point per $10 spent
            $points = floor($total_amount / 10);
            if ($points > 0) {
                $this->User_model->add_loyalty_points($user_id, $points);
            }
        }
        $this->session->set_flashdata('success', 'Booking successful!');
        redirect('booking/confirmation');
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
        // Release inventory
        $this->Booking_model->release_room_inventory($booking->room_type, $booking->check_in_date, $booking->check_out_date, 1);
        if ($this->Booking_model->update_booking_status($booking_id, 'cancelled')) {
            $this->session->set_flashdata('success', 'Booking cancelled successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel booking');
        }
        redirect('booking');
    }

    // Show waitlist form
    public function waitlist_form() {
        $search_data = $this->session->userdata('waitlist_search');
        if (!$search_data) redirect('booking');
        $data['search_data'] = $search_data;
        $this->load->view('booking/waitlist_form', $data);
    }

    // Handle waitlist submission
    public function submit_waitlist() {
        $this->load->model('Waitlist_model');
        $search_data = $this->session->userdata('waitlist_search');
        if (!$search_data) redirect('booking');
        $this->form_validation->set_rules('guest_name', 'Name', 'required');
        $this->form_validation->set_rules('guest_email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('guest_phone', 'Phone', 'required');
        if ($this->form_validation->run() == FALSE) {
            $data['search_data'] = $search_data;
            $this->load->view('booking/waitlist_form', $data);
            return;
        }
        $waitlist_data = [
            'user_id' => $this->session->userdata('logged_in') ? $this->session->userdata('user_id') : null,
            'guest_name' => $this->input->post('guest_name'),
            'guest_email' => $this->input->post('guest_email'),
            'guest_phone' => $this->input->post('guest_phone'),
            'room_type' => $search_data['room_type'] ?? '',
            'check_in_date' => $search_data['check_in_date'],
            'check_out_date' => $search_data['check_out_date'],
            'adults' => $search_data['adults'],
            'children' => $search_data['children'],
            'special_requests' => $search_data['special_requests'] ?? '',
            'status' => 'waiting',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->Waitlist_model->add_waitlist($waitlist_data);
        $this->session->unset_userdata('waitlist_search');
        $this->session->set_flashdata('success', 'You have been added to the waitlist. We will notify you if a room becomes available.');
        redirect('booking');
    }
} 