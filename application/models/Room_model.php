<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all rooms
    public function get_all_rooms() {
        return $this->db->get('rooms')->result();
    }
    
    // Get room by ID
    public function get_room_by_id($id) {
        return $this->db->get_where('rooms', ['id' => $id])->row();
    }
    
    // Get available rooms
    public function get_available_rooms() {
        return $this->db->get_where('rooms', ['status' => 'available'])->result();
    }
    
    // Get rooms by type
    public function get_rooms_by_type($type) {
        return $this->db->get_where('rooms', ['room_type' => $type])->result();
    }
    
    // Add new room
    public function add_room($data) {
        return $this->db->insert('rooms', $data);
    }
    
    // Update room
    public function update_room($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('rooms', $data);
    }
    
    // Delete room
    public function delete_room($id) {
        return $this->db->delete('rooms', ['id' => $id]);
    }
    
    // Update room status
    public function update_room_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('rooms', ['status' => $status]);
    }
    
    // Check room availability for date range
    public function check_room_availability($room_id, $check_in, $check_out) {
        $this->db->select('bookings.*');
        $this->db->from('bookings');
        $this->db->where('room_id', $room_id);
        $this->db->where('status IN', ['confirmed', 'checked_in']);
        $this->db->where("(check_in_date <= '$check_out' AND check_out_date >= '$check_in')");
        
        return $this->db->get()->num_rows() == 0;
    }
    
    // Get room count by status
    public function get_room_count_by_status($status) {
        return $this->db->where('status', $status)->count_all_results('rooms');
    }
    
    // Get room types
    public function get_room_types() {
        $this->db->distinct();
        $this->db->select('room_type');
        return $this->db->get('rooms')->result();
    }

    // Get rooms with enhanced details
    public function get_rooms_with_details() {
        $this->db->select('rooms.*, COUNT(bookings.id) as total_bookings');
        $this->db->from('rooms');
        $this->db->join('bookings', 'rooms.id = bookings.room_id AND bookings.status IN ("confirmed", "checked_in", "checked_out")', 'left');
        $this->db->group_by('rooms.id');
        return $this->db->get()->result();
    }

    // Update room with maintenance status
    public function update_room_maintenance($id, $maintenance_status, $cleaning_status = null) {
        $data = ['maintenance_status' => $maintenance_status];
        
        if ($cleaning_status) {
            $data['cleaning_status'] = $cleaning_status;
        }
        
        // Auto-update room status based on maintenance
        if ($maintenance_status === 'under_maintenance') {
            $data['status'] = 'maintenance';
        } elseif ($maintenance_status === 'ok' && $cleaning_status === 'clean') {
            $data['status'] = 'available';
        }
        
        $this->db->where('id', $id);
        return $this->db->update('rooms', $data);
    }

    // Get rooms by floor
    public function get_rooms_by_floor($floor_number) {
        $this->db->where('floor_number', $floor_number);
        return $this->db->get('rooms')->result();
    }

    // Get room statistics
    public function get_room_statistics() {
        // Total rooms by status
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');
        $status_stats = $this->db->get('rooms')->result();
        
        // Rooms by type
        $this->db->select('room_type, COUNT(*) as count, AVG(price_per_night) as avg_price');
        $this->db->group_by('room_type');
        $type_stats = $this->db->get('rooms')->result();
        
        return [
            'by_status' => $status_stats,
            'by_type' => $type_stats,
            'total_rooms' => $this->db->count_all('rooms')
        ];
    }

    // Search rooms with filters
    public function search_rooms($filters = []) {
        $this->db->select('*');
        $this->db->from('rooms');
        
        if (!empty($filters['room_type'])) {
            $this->db->where('room_type', $filters['room_type']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        
        if (!empty($filters['min_price'])) {
            $this->db->where('price_per_night >=', $filters['min_price']);
        }
        
        if (!empty($filters['max_price'])) {
            $this->db->where('price_per_night <=', $filters['max_price']);
        }
        
        if (!empty($filters['capacity'])) {
            $this->db->where('capacity >=', $filters['capacity']);
        }
        
        if (!empty($filters['floor_number'])) {
            $this->db->where('floor_number', $filters['floor_number']);
        }
        
        return $this->db->get()->result();
    }
} 