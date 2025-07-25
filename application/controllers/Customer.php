<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'Room_model', 'Booking_model']);
        $this->load->library(['form_validation', 'session']);
        $this->load->helper('notification');
        
        // Check if user is logged in and is customer
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'customer') {
            $this->session->set_flashdata('error', 'You must be logged in as a customer to access this page.');
            redirect(base_url());
        }
    }
    
    // Customer dashboard
    public function dashboard() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['bookings'] = $this->Booking_model->get_bookings_by_user($user_id);
        $data['total_bookings'] = count($data['bookings']);
        $data['active_bookings'] = 0;
        $data['completed_bookings'] = 0;
        
        foreach ($data['bookings'] as $booking) {
            if (in_array($booking->status, ['confirmed', 'checked_in'])) {
                $data['active_bookings']++;
            } elseif ($booking->status == 'checked_out') {
                $data['completed_bookings']++;
            }
        }
        
        $this->load->view('customer/dashboard', $data);
    }
    
    // View available rooms
    public function rooms() {
        // Redirect to main hotel rooms page for consistent theme
        redirect('rooms');
    }
    
    // Book a room
    public function book_room($room_id = null) {
        if ($room_id) {
            $data['room'] = $this->Room_model->get_room_by_id($room_id);
            if (!$data['room'] || $data['room']->status != 'available') {
                $this->session->set_flashdata('error', 'Room not available');
                redirect('customer/rooms');
            }
        } else {
            $data['rooms'] = $this->Room_model->get_available_rooms();
        }
        
        $this->form_validation->set_rules('room_id', 'Room', 'required');
        $this->form_validation->set_rules('check_in_date', 'Check-in Date', 'required');
        $this->form_validation->set_rules('check_out_date', 'Check-out Date', 'required');
        $this->form_validation->set_rules('special_requests', 'Special Requests', 'max_length[500]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('customer/book_room', $data);
        } else {
            $room_id = $this->input->post('room_id');
            $check_in = $this->input->post('check_in_date');
            $check_out = $this->input->post('check_out_date');
            
            // Check if room is available for the selected dates
            if (!$this->Booking_model->is_room_available($room_id, $check_in, $check_out)) {
                $this->session->set_flashdata('error', 'Room is not available for the selected dates');
                redirect('customer/book_room');
            }
            
            // Calculate total amount
            $room = $this->Room_model->get_room_by_id($room_id);
            $check_in_date = new DateTime($check_in);
            $check_out_date = new DateTime($check_out);
            $nights = $check_in_date->diff($check_out_date)->days;
            $total_amount = $room->price_per_night * $nights;
            
            $booking_data = [
                'user_id' => $this->session->userdata('user_id'),
                'room_id' => $room_id,
                'check_in_date' => $check_in,
                'check_out_date' => $check_out,
                'total_amount' => $total_amount,
                'special_requests' => $this->input->post('special_requests'),
                'status' => 'pending'
            ];
            
            if ($this->Booking_model->create_booking($booking_data)) {
                // Send notification to customer
                send_booking_notification(
                    $this->session->userdata('email'),
                    'Booking Created',
                    'Your booking has been created and is pending confirmation.'
                );
                // Send notification to admin (stub: replace with real admin email)
                send_booking_notification(
                    'admin@hotel.com',
                    'New Booking Created',
                    'A new booking has been created by customer ID: ' . $this->session->userdata('user_id')
                );
                $this->session->set_flashdata('success', 'Booking created successfully! Please wait for confirmation.');
                redirect('customer/my_bookings');
            } else {
                $this->session->set_flashdata('error', 'Failed to create booking');
                redirect('customer/book_room');
            }
        }
    }
    
    // View my bookings
    public function my_bookings() {
        $user_id = $this->session->userdata('user_id');
        $data['bookings'] = $this->Booking_model->get_bookings_by_user($user_id);
        $this->load->view('customer/my_bookings', $data);
    }
    
    // View booking details
    public function view_booking($id) {
        $user_id = $this->session->userdata('user_id');
        $data['booking'] = $this->Booking_model->get_booking_by_id($id);
        
        if (!$data['booking'] || $data['booking']->user_id != $user_id) {
            redirect('customer/my_bookings');
        }
        
        $this->load->view('customer/view_booking', $data);
    }
    
    // Cancel booking
    public function cancel_booking($id) {
        $user_id = $this->session->userdata('user_id');
        $booking = $this->Booking_model->get_booking_by_id($id);
        
        if (!$booking || $booking->user_id != $user_id) {
            $this->session->set_flashdata('error', 'Invalid booking');
            redirect('customer/my_bookings');
        }
        
        if ($booking->status != 'pending' && $booking->status != 'confirmed') {
            $this->session->set_flashdata('error', 'Cannot cancel this booking');
            redirect('customer/my_bookings');
        }
        
        if ($this->Booking_model->update_booking_status($id, 'cancelled')) {
            // Send notification to customer
            send_booking_notification(
                $this->session->userdata('email'),
                'Booking Cancelled',
                'Your booking has been cancelled.'
            );
            // Send notification to admin (stub: replace with real admin email)
            send_booking_notification(
                'admin@hotel.com',
                'Booking Cancelled',
                'Booking ID ' . $id . ' has been cancelled by customer ID: ' . $this->session->userdata('user_id')
            );
            $this->session->set_flashdata('success', 'Booking cancelled successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel booking');
        }
        
        redirect('customer/my_bookings');
    }
    
    // Edit booking
    public function edit_booking($id) {
        $user_id = $this->session->userdata('user_id');
        $booking = $this->Booking_model->get_booking_by_id($id);
        if (!$booking || $booking->user_id != $user_id) {
            $this->session->set_flashdata('error', 'Invalid booking');
            redirect('customer/my_bookings');
        }
        if (in_array($booking->status, ['checked_in', 'checked_out', 'cancelled'])) {
            $this->session->set_flashdata('error', 'This booking cannot be modified');
            redirect('customer/my_bookings');
        }
        $data['booking'] = $booking;
        $data['rooms'] = $this->Room_model->get_available_rooms();
        $this->form_validation->set_rules('room_id', 'Room', 'required');
        $this->form_validation->set_rules('check_in_date', 'Check-in Date', 'required');
        $this->form_validation->set_rules('check_out_date', 'Check-out Date', 'required');
        $this->form_validation->set_rules('special_requests', 'Special Requests', 'max_length[500]');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('customer/edit_booking', $data);
        } else {
            $room_id = $this->input->post('room_id');
            $check_in = $this->input->post('check_in_date');
            $check_out = $this->input->post('check_out_date');
            // Check if room is available for the new dates (excluding this booking)
            if (!$this->Booking_model->is_room_available($room_id, $check_in, $check_out, $id)) {
                $this->session->set_flashdata('error', 'Room is not available for the selected dates');
                redirect('customer/edit_booking/' . $id);
            }
            $room = $this->Room_model->get_room_by_id($room_id);
            $check_in_date = new DateTime($check_in);
            $check_out_date = new DateTime($check_out);
            $nights = $check_in_date->diff($check_out_date)->days;
            $total_amount = $room->price_per_night * $nights;
            $update_data = [
                'room_id' => $room_id,
                'check_in_date' => $check_in,
                'check_out_date' => $check_out,
                'total_amount' => $total_amount,
                'special_requests' => $this->input->post('special_requests'),
                'status' => 'pending' // Reset to pending for re-approval if needed
            ];
            if ($this->Booking_model->update_booking($id, $update_data)) {
                // Send notification to customer
                send_booking_notification(
                    $this->session->userdata('email'),
                    'Booking Modified',
                    'Your booking has been modified and is pending confirmation.'
                );
                // Send notification to admin (stub: replace with real admin email)
                send_booking_notification(
                    'admin@hotel.com',
                    'Booking Modified',
                    'Booking ID ' . $id . ' has been modified by customer ID: ' . $this->session->userdata('user_id')
                );
                $this->session->set_flashdata('success', 'Booking updated successfully!');
                redirect('customer/my_bookings');
            } else {
                $this->session->set_flashdata('error', 'Failed to update booking');
                redirect('customer/edit_booking/' . $id);
            }
        }
    }
    
    // Profile management
    public function profile() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('customer/profile', $data);
        } else {
            $update_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'preferences' => $this->input->post('preferences')
            ];
            
            if ($this->User_model->update_user($user_id, $update_data)) {
                $this->session->set_flashdata('success', 'Profile updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update profile');
            }
            redirect('customer/profile');
        }
    }
    
    // Change password
    public function change_password() {
        $user_id = $this->session->userdata('user_id');
        
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('customer/change_password');
        } else {
            $user = $this->User_model->get_user_by_id($user_id);
            $current_password = $this->input->post('current_password');
            
            if (password_verify($current_password, $user->password)) {
                $update_data = ['password' => $this->input->post('new_password')];
                
                if ($this->User_model->update_user($user_id, $update_data)) {
                    $this->session->set_flashdata('success', 'Password changed successfully');
                } else {
                    $this->session->set_flashdata('error', 'Failed to change password');
                }
            } else {
                $this->session->set_flashdata('error', 'Current password is incorrect');
            }
            redirect('customer/change_password');
        }
    }
    
    // Search rooms
    public function search_rooms() {
        $room_type = $this->input->get('room_type');
        $min_price = $this->input->get('min_price');
        $max_price = $this->input->get('max_price');
        
        $this->db->where('status', 'available');
        
        if ($room_type) {
            $this->db->where('room_type', $room_type);
        }
        
        if ($min_price) {
            $this->db->where('price_per_night >=', $min_price);
        }
        
        if ($max_price) {
            $this->db->where('price_per_night <=', $max_price);
        }
        
        $data['rooms'] = $this->db->get('rooms')->result();
        $data['room_types'] = $this->Room_model->get_room_types();
        
        $this->load->view('customer/search_rooms', $data);
    }

    // Download booking receipt
    public function download_receipt($id) {
        $user_id = $this->session->userdata('user_id');
        $booking = $this->Booking_model->get_booking_by_id($id);
        if (!$booking || $booking->user_id != $user_id) {
            show_error('You do not have permission to access this receipt.', 403);
        }
        $data['booking'] = $booking;
        $this->load->view('customer/booking_receipt', $data);
    }

    // Submit a review for a completed booking
    public function submit_review($booking_id) {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('Review_model');
        $booking = $this->Booking_model->get_booking_by_id($booking_id);
        if (!$booking || $booking->user_id != $user_id || $booking->status != 'checked_out') {
            $this->session->set_flashdata('error', 'You can only review completed bookings.');
            redirect('customer/view_booking/' . $booking_id);
        }
        // Prevent duplicate reviews
        $existing_review = $this->Review_model->get_review_by_booking($booking_id);
        if ($existing_review) {
            $this->session->set_flashdata('error', 'You have already reviewed this booking.');
            redirect('customer/view_booking/' . $booking_id);
        }
        $this->form_validation->set_rules('rating', 'Rating', 'required|integer|greater_than[0]|less_than_equal_to[5]');
        $this->form_validation->set_rules('review_text', 'Review', 'max_length[1000]');
        if ($this->form_validation->run() == FALSE) {
            $data['booking'] = $booking;
            $data['show_review_form'] = true;
            $this->load->view('customer/view_booking', $data);
        } else {
            $review_data = [
                'user_id' => $user_id,
                'room_id' => $booking->room_id,
                'booking_id' => $booking_id,
                'rating' => $this->input->post('rating'),
                'review_text' => $this->input->post('review_text'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->Review_model->add_review($review_data);
            $this->session->set_flashdata('success', 'Thank you for your review!');
            redirect('customer/view_booking/' . $booking_id);
        }
    }

    // Export user data (GDPR)
    public function export_my_data() {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        $bookings = $this->Booking_model->get_bookings_by_user($user_id);
        $this->load->model('Review_model');
        $reviews = $this->Review_model->get_reviews_by_user($user_id);
        $data = [
            'profile' => $user,
            'bookings' => $bookings,
            'reviews' => $reviews
        ];
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="my_data_' . date('Ymd_His') . '.json"');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // Request account deletion (GDPR)
    public function request_account_deletion() {
        $user_id = $this->session->userdata('user_id');
        // Mark user as pending deletion
        $this->User_model->update_user($user_id, ['status' => 'pending_deletion']);
        // Notify admin (for now, just set a flash message)
        $this->session->set_flashdata('success', 'Your account deletion request has been received. An administrator will process your request soon.');
        redirect('customer/profile');
    }

    // View loyalty points and transactions
    public function loyalty() {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('Loyalty_model');
        
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['loyalty_tier'] = $this->Loyalty_model->get_user_tier($user_id);
        $data['transactions'] = $this->Loyalty_model->get_user_transactions($user_id, 20);
        $data['points_balance'] = $data['user']->loyalty_points ?? 0;
        
        $this->load->view('customer/loyalty', $data);
    }

    // Redeem loyalty points
    public function redeem_points() {
        $this->form_validation->set_rules('points', 'Points', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customer/loyalty');
        }
        
        $user_id = $this->session->userdata('user_id');
        $points = $this->input->post('points');
        
        $this->load->model('Loyalty_model');
        
        // Check if user has enough points
        $user = $this->User_model->get_user_by_id($user_id);
        if ($user->loyalty_points < $points) {
            $this->session->set_flashdata('error', 'Insufficient loyalty points');
            redirect('customer/loyalty');
        }
        
        // Calculate discount amount
        $discount_amount = $this->Loyalty_model->calculate_discount_for_points($points);
        
        if ($this->Loyalty_model->redeem_points($user_id, $points, "Redeemed $points points for $$discount_amount discount")) {
            $this->session->set_flashdata('success', "Successfully redeemed $points points for $$discount_amount discount!");
        } else {
            $this->session->set_flashdata('error', 'Failed to redeem points. Please try again.');
        }
        
        redirect('customer/loyalty');
    }

    // Leave a review for a completed booking
    public function leave_review($booking_id) {
        // Check if booking exists and belongs to user
        $booking = $this->Booking_model->get_booking_by_id($booking_id);
        if (!$booking || $booking->user_id != $this->session->userdata('user_id') || $booking->status != 'checked_out') {
            $this->session->set_flashdata('error', 'Invalid booking or you cannot review this booking');
            redirect('customer/my_bookings');
        }
        
        // Check if review already exists
        $this->load->model('Review_model');
        $existing_review = $this->Review_model->get_review_by_booking($booking_id);
        if ($existing_review) {
            $this->session->set_flashdata('error', 'You have already reviewed this booking');
            redirect('customer/my_bookings');
        }
        
        $this->form_validation->set_rules('rating', 'Rating', 'required|in_list[1,2,3,4,5]');
        $this->form_validation->set_rules('review_text', 'Review', 'required|min_length[10]|max_length[1000]');
        
        if ($this->form_validation->run() == FALSE) {
            $data['booking'] = $booking;
            $this->load->view('customer/leave_review', $data);
        } else {
            $review_data = [
                'user_id' => $this->session->userdata('user_id'),
                'room_id' => $booking->room_id,
                'booking_id' => $booking_id,
                'rating' => $this->input->post('rating'),
                'review_text' => $this->input->post('review_text'),
                'status' => 'pending'
            ];
            
            if ($this->Review_model->add_review($review_data)) {
                // Award bonus loyalty points for leaving a review
                $this->load->model('Loyalty_model');
                $this->Loyalty_model->award_points(
                    $this->session->userdata('user_id'),
                    50,
                    'Bonus points for leaving a review'
                );
                
                $this->session->set_flashdata('success', 'Thank you for your review! It will be published after moderation. You earned 50 bonus loyalty points!');
            } else {
                $this->session->set_flashdata('error', 'Failed to submit review. Please try again.');
            }
            
            redirect('customer/my_bookings');
        }
    }

    // Change password
    public function change_password() {
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('customer/change_password');
        } else {
            $user_id = $this->session->userdata('user_id');
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');
            
            // Verify current password
            $user = $this->User_model->get_user_by_id($user_id);
            if (!password_verify($current_password, $user->password)) {
                $this->session->set_flashdata('error', 'Current password is incorrect');
                redirect('customer/change_password');
            }
            
            // Check password strength
            $this->load->library('Security_helper');
            $password_check = $this->security_helper->validate_password_strength($new_password);
            if ($password_check !== true) {
                $this->session->set_flashdata('error', implode('<br>', $password_check));
                redirect('customer/change_password');
            }
            
            // Update password
            if ($this->User_model->update_password($user_id, $new_password)) {
                $this->session->set_flashdata('success', 'Password updated successfully');
                
                // Log security event
                $this->security_helper->log_security_event($user_id, 'password_change', 'Customer changed password');
            } else {
                $this->session->set_flashdata('error', 'Failed to update password');
            }
            
            redirect('customer/profile');
        }
    }

    // Download booking receipt
    public function download_receipt($booking_id) {
        $booking = $this->Booking_model->get_booking_by_id($booking_id);
        if (!$booking || $booking->user_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Booking not found');
            redirect('customer/my_bookings');
        }
        
        // Generate PDF receipt (simplified - you'd want to use a proper PDF library)
        $this->load->library('pdf'); // Assuming you have a PDF library
        
        $data['booking'] = $booking;
        $html = $this->load->view('customer/receipt_pdf', $data, true);
        
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream("receipt_" . $booking->booking_reference . ".pdf");
    }
} 