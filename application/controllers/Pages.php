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
    public function room_details() {
        $this->load->view('room-details');
    }
} 