<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Create new booking
    public function create_booking($data) {
        $this->db->insert('bookings', $data);
        return $this->db->insert_id();
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
        $this->db->where_in('status', ['confirmed', 'checked_in', 'checked_out']);
        $result = $this->db->get('bookings')->row();
        return $result->total_amount ? $result->total_amount : 0;
    }
    
    // Get monthly revenue
    public function get_monthly_revenue($year, $month) {
        $this->db->select_sum('total_amount');
        $this->db->where_in('status', ['confirmed', 'checked_in', 'checked_out']);
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
        $this->db->where_in('status', ['confirmed', 'checked_in']);
        $this->db->where("(check_in_date <= '$check_out' AND check_out_date >= '$check_in')");
        
        if ($exclude_booking_id) {
            $this->db->where('id !=', $exclude_booking_id);
        }
        
        return $this->db->get('bookings')->num_rows() == 0;
    }

    // Get number of occupied rooms on a given date
    public function get_occupied_count_on($date) {
        $this->db->where('status IN ("confirmed", "checked_in")');
        $this->db->where('check_in_date <=', $date);
        $this->db->where('check_out_date >', $date);
        return $this->db->count_all_results('bookings');
    }

    // Get total revenue for a given date
    public function get_revenue_on($date) {
        $this->db->select_sum('total_amount');
        $this->db->where('status IN ("confirmed", "checked_in", "checked_out")');
        $this->db->where('check_in_date <=', $date);
        $this->db->where('check_out_date >', $date);
        $result = $this->db->get('bookings')->row();
        return $result ? (float)$result->total_amount : 0;
    }

    // Check if a room type is available for a given date range and quantity
    public function is_room_type_available($room_type, $check_in, $check_out, $quantity = 1) {
        $current = strtotime($check_in);
        $end = strtotime($check_out);
        while ($current < $end) {
            $date = date('Y-m-d', $current);
            $this->db->where('room_type', $room_type);
            $this->db->where('date', $date);
            $row = $this->db->get('room_inventory')->row();
            if (!$row || ($row->total_rooms - $row->booked_rooms) < $quantity) {
                return false;
            }
            $current = strtotime('+1 day', $current);
        }
        return true;
    }

    // Reserve inventory for a room type and date range
    public function reserve_room_inventory($room_type, $check_in, $check_out, $quantity = 1) {
        $current = strtotime($check_in);
        $end = strtotime($check_out);
        $this->db->trans_start();
        while ($current < $end) {
            $date = date('Y-m-d', $current);
            $this->db->set('booked_rooms', 'booked_rooms + ' . (int)$quantity, false);
            $this->db->where('room_type', $room_type);
            $this->db->where('date', $date);
            $this->db->update('room_inventory');
            $current = strtotime('+1 day', $current);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Release inventory for a room type and date range (e.g., on cancel)
    public function release_room_inventory($room_type, $check_in, $check_out, $quantity = 1) {
        $current = strtotime($check_in);
        $end = strtotime($check_out);
        $this->db->trans_start();
        while ($current < $end) {
            $date = date('Y-m-d', $current);
            $this->db->set('booked_rooms', 'GREATEST(booked_rooms - ' . (int)$quantity . ', 0)', false);
            $this->db->where('room_type', $room_type);
            $this->db->where('date', $date);
            $this->db->update('room_inventory');
            $current = strtotime('+1 day', $current);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Get price per night for a room type and date (using rate plans)
    public function get_price_per_night($room_type, $date) {
        $this->db->where('room_type', $room_type);
        $this->db->where('active', 1);
        $this->db->where('start_date <=', $date);
        $this->db->where('end_date >=', $date);
        $this->db->order_by('start_date DESC');
        $plan = $this->db->get('rate_plans')->row();
        if ($plan) {
            return $plan->price_per_night;
        }
        // fallback: get default from rooms table
        $this->db->where('room_type', $room_type);
        $room = $this->db->get('rooms')->row();
        return $room ? $room->price_per_night : 0;
    }

    // Generate unique booking reference
    public function generate_booking_reference() {
        do {
            $reference = 'BK' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $this->db->where('booking_reference', $reference);
            $exists = $this->db->get('bookings')->num_rows() > 0;
        } while ($exists);
        
        return $reference;
    }

    // Update booking with additional data
    public function update_booking_data($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('bookings', $data);
    }

    // Get bookings with payment status
    public function get_bookings_with_payment_status($status = null) {
        $this->db->select('bookings.*, users.first_name, users.last_name, users.email, rooms.room_number, rooms.room_type');
        $this->db->from('bookings');
        $this->db->join('users', 'bookings.user_id = users.id');
        $this->db->join('rooms', 'bookings.room_id = rooms.id');
        
        if ($status) {
            $this->db->where('bookings.payment_status', $status);
        }
        
        $this->db->order_by('bookings.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Get booking by reference
    public function get_booking_by_reference($reference) {
        $this->db->select('bookings.*, users.first_name, users.last_name, users.email, users.phone, rooms.room_number, rooms.room_type, rooms.price_per_night');
        $this->db->from('bookings');
        $this->db->join('users', 'bookings.user_id = users.id');
        $this->db->join('rooms', 'bookings.room_id = rooms.id');
        $this->db->where('bookings.booking_reference', $reference);
        return $this->db->get()->row();
    }

    // Cancel booking with inventory release
    public function cancel_booking_with_inventory($id, $room_type = null) {
        $this->db->trans_start();
        
        // Get booking details first
        $booking = $this->get_booking_by_id($id);
        if (!$booking) {
            return false;
        }
        
        // Update booking status
        $this->update_booking_status($id, 'cancelled');
        
        // Release inventory if room_type provided
        if ($room_type) {
            $this->release_room_inventory($room_type, $booking->check_in_date, $booking->check_out_date, $booking->rooms);
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
} 