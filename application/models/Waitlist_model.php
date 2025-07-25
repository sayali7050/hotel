<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Waitlist_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    // Add a waitlist entry
    public function add_waitlist($data) {
        $this->db->insert('waitlist', $data);
        return $this->db->insert_id();
    }

    // Get all waitlist entries (optionally by status)
    public function get_all($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->order_by('created_at', 'DESC')->get('waitlist')->result();
    }

    // Get waitlist entries by criteria
    public function get_by_criteria($room_type, $check_in, $check_out) {
        $this->db->where('room_type', $room_type);
        $this->db->where('check_in_date', $check_in);
        $this->db->where('check_out_date', $check_out);
        $this->db->where('status', 'waiting');
        return $this->db->get('waitlist')->result();
    }

    // Update waitlist status
    public function update_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('waitlist', ['status' => $status]);
    }

    // Get a waitlist entry by ID
    public function get_by_id($id) {
        return $this->db->get_where('waitlist', ['id' => $id])->row();
    }
} 