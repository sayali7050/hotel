<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_inventory_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get available rooms for a date range
    public function get_available_rooms($room_type, $check_in, $check_out, $quantity = 1) {
        $current = strtotime($check_in);
        $end = strtotime($check_out);
        
        while ($current < $end) {
            $date = date('Y-m-d', $current);
            
            $this->db->where('room_type', $room_type);
            $this->db->where('date', $date);
            $inventory = $this->db->get('room_inventory')->row();
            
            if (!$inventory || $inventory->available_rooms < $quantity) {
                return false;
            }
            
            $current = strtotime('+1 day', $current);
        }
        
        return true;
    }
    
    // Reserve rooms for a booking
    public function reserve_rooms($room_type, $check_in, $check_out, $quantity = 1) {
        $this->db->trans_start();
        
        $current = strtotime($check_in);
        $end = strtotime($check_out);
        
        while ($current < $end) {
            $date = date('Y-m-d', $current);
            
            // Check if inventory exists for this date
            $this->db->where('room_type', $room_type);
            $this->db->where('date', $date);
            $inventory = $this->db->get('room_inventory')->row();
            
            if ($inventory) {
                // Update existing inventory
                $this->db->set('booked_rooms', 'booked_rooms + ' . (int)$quantity, false);
                $this->db->where('room_type', $room_type);
                $this->db->where('date', $date);
                $this->db->update('room_inventory');
            } else {
                // Create new inventory record
                $total_rooms = $this->_get_total_rooms_by_type($room_type);
                $this->db->insert('room_inventory', [
                    'room_type' => $room_type,
                    'date' => $date,
                    'total_rooms' => $total_rooms,
                    'booked_rooms' => $quantity
                ]);
            }
            
            $current = strtotime('+1 day', $current);
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    // Release rooms from a booking (cancellation)
    public function release_rooms($room_type, $check_in, $check_out, $quantity = 1) {
        $this->db->trans_start();
        
        $current = strtotime($check_in);
        $end = strtotime($check_out);
        
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
    
    // Block rooms for maintenance
    public function block_rooms($room_type, $start_date, $end_date, $quantity = 1) {
        $this->db->trans_start();
        
        $current = strtotime($start_date);
        $end = strtotime($end_date);
        
        while ($current <= $end) {
            $date = date('Y-m-d', $current);
            
            $this->db->where('room_type', $room_type);
            $this->db->where('date', $date);
            $inventory = $this->db->get('room_inventory')->row();
            
            if ($inventory) {
                $this->db->set('blocked_rooms', 'blocked_rooms + ' . (int)$quantity, false);
                $this->db->where('room_type', $room_type);
                $this->db->where('date', $date);
                $this->db->update('room_inventory');
            } else {
                $total_rooms = $this->_get_total_rooms_by_type($room_type);
                $this->db->insert('room_inventory', [
                    'room_type' => $room_type,
                    'date' => $date,
                    'total_rooms' => $total_rooms,
                    'blocked_rooms' => $quantity
                ]);
            }
            
            $current = strtotime('+1 day', $current);
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    // Unblock rooms
    public function unblock_rooms($room_type, $start_date, $end_date, $quantity = 1) {
        $this->db->trans_start();
        
        $current = strtotime($start_date);
        $end = strtotime($end_date);
        
        while ($current <= $end) {
            $date = date('Y-m-d', $current);
            
            $this->db->set('blocked_rooms', 'GREATEST(blocked_rooms - ' . (int)$quantity . ', 0)', false);
            $this->db->where('room_type', $room_type);
            $this->db->where('date', $date);
            $this->db->update('room_inventory');
            
            $current = strtotime('+1 day', $current);
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    // Get inventory for a specific date range
    public function get_inventory($room_type, $start_date, $end_date) {
        $this->db->where('room_type', $room_type);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $this->db->order_by('date');
        return $this->db->get('room_inventory')->result();
    }
    
    // Get occupancy rate for a date range
    public function get_occupancy_rate($room_type, $start_date, $end_date) {
        $this->db->select('AVG((booked_rooms / total_rooms) * 100) as occupancy_rate', false);
        $this->db->where('room_type', $room_type);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $this->db->where('total_rooms >', 0);
        $result = $this->db->get('room_inventory')->row();
        
        return $result ? round($result->occupancy_rate, 2) : 0;
    }
    
    // Initialize inventory for future dates
    public function initialize_inventory($days_ahead = 365) {
        $room_types = $this->_get_all_room_types();
        
        foreach ($room_types as $room_type) {
            $total_rooms = $this->_get_total_rooms_by_type($room_type->room_type);
            
            for ($i = 0; $i <= $days_ahead; $i++) {
                $date = date('Y-m-d', strtotime("+$i days"));
                
                // Check if inventory already exists
                $this->db->where('room_type', $room_type->room_type);
                $this->db->where('date', $date);
                $exists = $this->db->get('room_inventory')->row();
                
                if (!$exists) {
                    $this->db->insert('room_inventory', [
                        'room_type' => $room_type->room_type,
                        'date' => $date,
                        'total_rooms' => $total_rooms,
                        'booked_rooms' => 0,
                        'blocked_rooms' => 0
                    ]);
                }
            }
        }
    }
    
    // Get available room types for a date range
    public function get_available_room_types($check_in, $check_out, $quantity = 1) {
        $this->db->select('ri.room_type, MIN(ri.available_rooms) as min_available, r.price_per_night, r.description, r.amenities');
        $this->db->from('room_inventory ri');
        $this->db->join('rooms r', 'ri.room_type = r.room_type');
        $this->db->where('ri.date >=', $check_in);
        $this->db->where('ri.date <', $check_out);
        $this->db->where('r.status', 'available');
        $this->db->group_by('ri.room_type');
        $this->db->having('min_available >=', $quantity);
        
        return $this->db->get()->result();
    }
    
    // Private helper methods
    private function _get_total_rooms_by_type($room_type) {
        $this->db->where('room_type', $room_type);
        $this->db->where('status !=', 'maintenance');
        return $this->db->count_all_results('rooms');
    }
    
    private function _get_all_room_types() {
        $this->db->distinct();
        $this->db->select('room_type');
        $this->db->where('status !=', 'maintenance');
        return $this->db->get('rooms')->result();
    }
    
    // Update total rooms for a room type (when rooms are added/removed)
    public function update_total_rooms($room_type, $new_total) {
        $this->db->where('room_type', $room_type);
        $this->db->where('date >=', date('Y-m-d'));
        return $this->db->update('room_inventory', ['total_rooms' => $new_total]);
    }
    
    // Get daily availability report
    public function get_availability_report($start_date, $end_date) {
        $this->db->select('date, room_type, total_rooms, booked_rooms, blocked_rooms, available_rooms');
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $this->db->order_by('date, room_type');
        return $this->db->get('room_inventory')->result();
    }
}