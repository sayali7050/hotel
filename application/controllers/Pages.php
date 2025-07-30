<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {
    public function about() {
        $this->load->view('about');
    }
    public function rooms() {
        $this->load->view('rooms');
    }
    public function amenities() {
        $this->load->view('amenities');
    }
    public function gallery() {
        $this->load->view('gallery');
    }
    public function events() {
        $this->load->view('events');
    }
    public function restaurant() {
        $this->load->view('restaurant');
    }
    public function contact() {
        $this->load->view('contact');
    }
    public function booking() {
        $this->load->view('booking');
    }
    public function offers() {
        $this->load->view('offers');
    }
    public function privacy() {
        $this->load->view('privacy');
    }
    public function terms() {
        $this->load->view('terms');
    }
    public function location() {
        $this->load->view('location');
    }
    public function starter_page() {
        $this->load->view('starter-page');
    }
    public function room_details($room_id = 1) {
        // Load models
        $this->load->model('Room_model');
        $this->load->model('Review_model');
        
        // Get room data
        $room = $this->Room_model->get_room_by_id($room_id);
        
        // If room not found, show error or redirect
        if (!$room) {
            show_404();
            return;
        }
        
        // Get reviews for this room
        $reviews = $this->Review_model->get_reviews_by_room($room_id);
        
        // Calculate average rating
        $avg_rating = $this->Review_model->get_room_average_rating($room_id);
        
        // Prepare data for view
        $data = [
            'room' => $room,
            'reviews' => $reviews,
            'avg_rating' => $avg_rating
        ];
        
        $this->load->view('room-details', $data);
    }
} 