<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    // Get active coupon by code
    public function get_active_coupon($code) {
        $this->db->where('code', $code);
        $this->db->where('active', 1);
        $this->db->where('(expiry_date IS NULL OR expiry_date >= CURDATE())');
        $query = $this->db->get('coupons');
        return $query->row();
    }

    // Mark coupon as used
    public function mark_coupon_used($user_id, $booking_id, $coupon_id) {
        $this->db->insert('used_coupons', [
            'user_id' => $user_id,
            'booking_id' => $booking_id,
            'coupon_id' => $coupon_id
        ]);
        $this->db->set('used_count', 'used_count+1', false);
        $this->db->where('id', $coupon_id);
        $this->db->update('coupons');
    }

    // Check if user has used coupon for a booking
    public function has_used_coupon($user_id, $booking_id, $coupon_id) {
        $this->db->where(['user_id' => $user_id, 'booking_id' => $booking_id, 'coupon_id' => $coupon_id]);
        return $this->db->get('used_coupons')->num_rows() > 0;
    }

    // Get all coupons (admin)
    public function get_all_coupons() {
        return $this->db->order_by('created_at', 'DESC')->get('coupons')->result();
    }

    // Add a new coupon
    public function add_coupon($data) {
        $this->db->insert('coupons', $data);
        return $this->db->insert_id();
    }

    // Update coupon
    public function update_coupon($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('coupons', $data);
    }

    // Delete coupon
    public function delete_coupon($id) {
        $this->db->where('id', $id);
        return $this->db->delete('coupons');
    }
} 