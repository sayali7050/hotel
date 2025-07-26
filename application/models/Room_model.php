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
        $this->db->where_in('status', ['confirmed', 'checked_in']);
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
} 