<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Create new booking
    public function create_booking($data) {
        return $this->db->insert('bookings', $data);
    }
    
    // Get booking by ID
    public function get_booking_by_id($id) {
        $this->db->select('bookings.*, users.first_name, users.last_name, users.email, users.phone, rooms.room_number, rooms.room_type, rooms.price_per_night');
        $this->db->from('bookings');
        $this->db->join('users', 'bookings.user_id = users.id');
        $this->db->join('rooms', 'bookings.room_id = rooms.id');
        $this->db->where('bookings.id', $id);
        return $this->db->get()->row();
    }
    
    // Get all bookings
    public function get_all_bookings() {
        $this->db->select('bookings.*, users.first_name, users.last_name, users.email, rooms.room_number, rooms.room_type');
        $this->db->from('bookings');
        $this->db->join('users', 'bookings.user_id = users.id');
        $this->db->join('rooms', 'bookings.room_id = rooms.id');
        $this->db->order_by('bookings.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    // Get bookings by user
    public function get_bookings_by_user($user_id) {
        $this->db->select('bookings.*, rooms.room_number, rooms.room_type, rooms.price_per_night');
        $this->db->from('bookings');
        $this->db->join('rooms', 'bookings.room_id = rooms.id');
        $this->db->where('bookings.user_id', $user_id);
        $this->db->order_by('bookings.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    // Get bookings by status
    public function get_bookings_by_status($status) {
        $this->db->select('bookings.*, users.first_name, users.last_name, users.email, rooms.room_number, rooms.room_type');
        $this->db->from('bookings');
        $this->db->join('users', 'bookings.user_id = users.id');
        $this->db->join('rooms', 'bookings.room_id = rooms.id');
        $this->db->where('bookings.status', $status);
        $this->db->order_by('bookings.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    // Update booking status
    public function update_booking_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('bookings', ['status' => $status]);
    }
    
    // Update booking
    public function update_booking($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('bookings', $data);
    }
    
    // Delete booking
    public function delete_booking($id) {
        return $this->db->delete('bookings', ['id' => $id]);
    }
    
    // Get booking count by status
    public function get_booking_count_by_status($status) {
        return $this->db->where('status', $status)->count_all_results('bookings');
    }
    
    // Get total revenue
    public function get_total_revenue() {
        $this->db->select_sum('total_amount');
        $this->db->where('status IN', ['confirmed', 'checked_in', 'checked_out']);
        $result = $this->db->get('bookings')->row();
        return $result->total_amount ? $result->total_amount : 0;
    }
    
    // Get monthly revenue
    public function get_monthly_revenue($year, $month) {
        $this->db->select_sum('total_amount');
        $this->db->where('status IN', ['confirmed', 'checked_in', 'checked_out']);
        $this->db->where('YEAR(created_at)', $year);
        $this->db->where('MONTH(created_at)', $month);
        $result = $this->db->get('bookings')->row();
        return $result->total_amount ? $result->total_amount : 0;
    }
    
    // Get recent bookings
    public function get_recent_bookings($limit = 10) {
        $this->db->select('bookings.*, users.first_name, users.last_name, rooms.room_number');
        $this->db->from('bookings');
        $this->db->join('users', 'bookings.user_id = users.id');
        $this->db->join('rooms', 'bookings.room_id = rooms.id');
        $this->db->order_by('bookings.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
    
    // Check if room is available for booking
    public function is_room_available($room_id, $check_in, $check_out, $exclude_booking_id = null) {
        $this->db->where('room_id', $room_id);
        $this->db->where('status IN', ['confirmed', 'checked_in']);
        $this->db->where("(check_in_date <= '$check_out' AND check_out_date >= '$check_in')");
        
        if ($exclude_booking_id) {
            $this->db->where('id !=', $exclude_booking_id);
        }
        
        return $this->db->get('bookings')->num_rows() == 0;
    }
} 