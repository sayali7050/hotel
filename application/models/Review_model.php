<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    // Add a review
    public function add_review($data) {
        $this->db->insert('reviews', $data);
        return $this->db->insert_id();
    }

    // Get reviews for a room
    public function get_reviews_by_room($room_id) {
        $this->db->select('reviews.*, users.first_name, users.last_name');
        $this->db->from('reviews');
        $this->db->join('users', 'reviews.user_id = users.id');
        $this->db->where('reviews.room_id', $room_id);
        $this->db->order_by('reviews.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Get review by booking (to prevent duplicate reviews)
    public function get_review_by_booking($booking_id) {
        return $this->db->get_where('reviews', ['booking_id' => $booking_id])->row();
    }

    // Get all reviews (optionally by status)
    public function get_all_reviews($status = null) {
        $this->db->select('reviews.*, users.first_name, users.last_name, rooms.room_number, rooms.room_type');
        $this->db->from('reviews');
        $this->db->join('users', 'reviews.user_id = users.id');
        $this->db->join('rooms', 'reviews.room_id = rooms.id');
        if ($status) {
            $this->db->where('reviews.status', $status);
        }
        $this->db->order_by('reviews.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Approve a review
    public function approve_review($id) {
        $this->db->where('id', $id);
        return $this->db->update('reviews', ['status' => 'approved']);
    }

    // Reject a review
    public function reject_review($id) {
        $this->db->where('id', $id);
        return $this->db->update('reviews', ['status' => 'rejected']);
    }

    // Admin reply to a review
    public function reply_review($id, $reply) {
        $this->db->where('id', $id);
        return $this->db->update('reviews', ['admin_reply' => $reply]);
    }
} 