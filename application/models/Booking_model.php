<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Booking Model
 * 
 * Enhanced booking management with security and business logic
 * 
 * @package Hotel Management System
 * @version 2.0
 */
class Booking_model extends CI_Model {
    
    protected $table = 'bookings';
    protected $fillable = [
        'booking_reference', 'user_id', 'room_id', 'check_in_date', 'check_out_date',
        'adults', 'children', 'rooms', 'nights', 'base_amount', 'tax_amount',
        'discount_amount', 'total_amount', 'status', 'payment_status',
        'special_requests', 'guest_name', 'guest_email', 'guest_phone',
        'guest_address', 'payment_method', 'coupon_code', 'coupon_discount',
        'loyalty_points_used', 'loyalty_points_earned', 'notes', 'created_by'
    ];
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('security_lib');
        $this->load->model(['Room_model', 'User_model']);
    }
    
    /**
     * Create new booking with validation and security
     */
    public function create_booking($data, $current_user_id = null) {
        $this->db->trans_start();
        
        try {
            // Sanitize input data
            $data = $this->security_lib->sanitize_input($data, [
                'guest_name' => ['string', 'no_html'],
                'guest_email' => ['email'],
                'guest_phone' => ['string', 'no_html'],
                'guest_address' => ['string', 'html'],
                'special_requests' => ['string', 'html'],
                'notes' => ['string', 'html']
            ]);
            
            // Validate booking data
            $validation_result = $this->validate_booking_data($data);
            if ($validation_result !== true) {
                throw new Exception(implode(', ', $validation_result));
            }
            
            // Check room availability
            if (!$this->is_room_available($data['room_id'], $data['check_in_date'], $data['check_out_date'])) {
                throw new Exception('Room is not available for the selected dates');
            }
            
            // Calculate booking details
            $booking_calculations = $this->calculate_booking_amounts($data);
            $data = array_merge($data, $booking_calculations);
            
            // Generate booking reference
            $data['booking_reference'] = $this->generate_booking_reference();
            
            // Set defaults
            $data['status'] = $data['status'] ?? 'pending';
            $data['payment_status'] = $data['payment_status'] ?? 'pending';
            $data['created_by'] = $current_user_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            
            // Filter allowed fields
            $insert_data = array_intersect_key($data, array_flip($this->fillable));
            $insert_data['created_at'] = $data['created_at'];
            
            // Insert booking
            $this->db->insert($this->table, $insert_data);
            $booking_id = $this->db->insert_id();
            
            if (!$booking_id) {
                throw new Exception('Failed to create booking');
            }
            
            // Update room status if confirmed
            if ($data['status'] === 'confirmed') {
                $this->Room_model->update_room_status($data['room_id'], 'occupied');
            }
            
            // Handle loyalty points
            if (isset($data['loyalty_points_used']) && $data['loyalty_points_used'] > 0) {
                $this->User_model->add_loyalty_points($data['user_id'], -$data['loyalty_points_used'], 'Used for booking #' . $data['booking_reference']);
            }
            
            if (isset($data['loyalty_points_earned']) && $data['loyalty_points_earned'] > 0) {
                $this->User_model->add_loyalty_points($data['user_id'], $data['loyalty_points_earned'], 'Earned from booking #' . $data['booking_reference']);
            }
            
            // Handle coupon usage
            if (!empty($data['coupon_code'])) {
                $this->_record_coupon_usage($data['coupon_code'], $data['user_id'], $booking_id, $data['coupon_discount']);
            }
            
            // Log booking creation
            $this->security_lib->log_security_event('booking_created', $current_user_id ?: $data['user_id'], [
                'booking_id' => $booking_id,
                'booking_reference' => $data['booking_reference'],
                'room_id' => $data['room_id'],
                'total_amount' => $data['total_amount']
            ]);
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            return $booking_id;
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }
    
    /**
     * Update booking with validation
     */
    public function update_booking($id, $data, $current_user_id = null) {
        $this->db->trans_start();
        
        try {
            // Get current booking
            $old_booking = $this->get_booking_by_id($id);
            if (!$old_booking) {
                throw new Exception('Booking not found');
            }
            
            // Sanitize input
            $data = $this->security_lib->sanitize_input($data, [
                'guest_name' => ['string', 'no_html'],
                'guest_email' => ['email'],
                'guest_phone' => ['string', 'no_html'],
                'guest_address' => ['string', 'html'],
                'special_requests' => ['string', 'html'],
                'notes' => ['string', 'html']
            ]);
            
            // Check if dates are being changed and validate availability
            if ((isset($data['check_in_date']) && $data['check_in_date'] !== $old_booking->check_in_date) ||
                (isset($data['check_out_date']) && $data['check_out_date'] !== $old_booking->check_out_date)) {
                
                $check_in = $data['check_in_date'] ?? $old_booking->check_in_date;
                $check_out = $data['check_out_date'] ?? $old_booking->check_out_date;
                $room_id = $data['room_id'] ?? $old_booking->room_id;
                
                if (!$this->is_room_available($room_id, $check_in, $check_out, $id)) {
                    throw new Exception('Room is not available for the selected dates');
                }
                
                // Recalculate amounts if dates changed
                $temp_data = array_merge((array)$old_booking, $data);
                $booking_calculations = $this->calculate_booking_amounts($temp_data);
                $data = array_merge($data, $booking_calculations);
            }
            
            // Handle status changes
            if (isset($data['status']) && $data['status'] !== $old_booking->status) {
                $this->_handle_status_change($old_booking, $data['status'], $current_user_id);
            }
            
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            // Filter allowed fields
            $update_data = array_intersect_key($data, array_flip($this->fillable));
            $update_data['updated_at'] = $data['updated_at'];
            
            // Update booking
            $this->db->where('id', $id);
            $this->db->update($this->table, $update_data);
            
            // Log booking update
            $this->security_lib->log_security_event('booking_updated', $current_user_id, [
                'booking_id' => $id,
                'changes' => array_keys($update_data),
                'old_status' => $old_booking->status,
                'new_status' => $data['status'] ?? $old_booking->status
            ]);
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            return true;
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }
    
    /**
     * Cancel booking
     */
    public function cancel_booking($id, $reason = null, $cancelled_by = null) {
        $this->db->trans_start();
        
        try {
            $booking = $this->get_booking_by_id($id);
            if (!$booking) {
                throw new Exception('Booking not found');
            }
            
            if (in_array($booking->status, ['cancelled', 'checked_out'])) {
                throw new Exception('Booking cannot be cancelled');
            }
            
            // Calculate refund if applicable
            $refund_amount = $this->calculate_refund_amount($booking);
            
            // Update booking
            $update_data = [
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
                'cancelled_by' => $cancelled_by,
                'cancelled_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->where('id', $id);
            $this->db->update($this->table, $update_data);
            
            // Free up the room
            if ($booking->status === 'confirmed') {
                $this->Room_model->update_room_status($booking->room_id, 'available');
            }
            
            // Refund loyalty points if used
            if ($booking->loyalty_points_used > 0) {
                $this->User_model->add_loyalty_points($booking->user_id, $booking->loyalty_points_used, 'Refund for cancelled booking #' . $booking->booking_reference);
            }
            
            // Log cancellation
            $this->security_lib->log_security_event('booking_cancelled', $cancelled_by ?: $booking->user_id, [
                'booking_id' => $id,
                'booking_reference' => $booking->booking_reference,
                'reason' => $reason,
                'refund_amount' => $refund_amount
            ]);
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            return $refund_amount;
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }
    
    /**
     * Check in guest
     */
    public function check_in($id, $staff_id = null) {
        return $this->_update_booking_status($id, 'checked_in', $staff_id, [
            'check_in_time' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Check out guest
     */
    public function check_out($id, $staff_id = null) {
        $result = $this->_update_booking_status($id, 'checked_out', $staff_id, [
            'check_out_time' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            $booking = $this->get_booking_by_id($id);
            // Free up the room
            $this->Room_model->update_room_status($booking->room_id, 'available');
        }
        
        return $result;
    }
    
    /**
     * Get booking by ID with full details
     */
    public function get_booking_by_id($id) {
        $this->db->select('bookings.*, users.first_name, users.last_name, users.email as user_email, users.phone as user_phone, 
                          rooms.room_number, rooms.room_type, rooms.price_per_night, rooms.floor, rooms.bed_type, rooms.view_type,
                          staff.first_name as staff_first_name, staff.last_name as staff_last_name');
        $this->db->from($this->table);
        $this->db->join('users', 'bookings.user_id = users.id', 'left');
        $this->db->join('rooms', 'bookings.room_id = rooms.id', 'left');
        $this->db->join('users as staff', 'bookings.created_by = staff.id', 'left');
        $this->db->where('bookings.id', $id);
        $this->db->where('bookings.deleted_at IS NULL');
        return $this->db->get()->row();
    }
    
    /**
     * Get booking by reference
     */
    public function get_booking_by_reference($reference) {
        $this->db->where('booking_reference', $reference);
        $this->db->where('deleted_at IS NULL');
        return $this->db->get($this->table)->row();
    }
    
    /**
     * Get bookings by user
     */
    public function get_bookings_by_user($user_id, $limit = null, $offset = 0) {
        $this->db->select('bookings.*, rooms.room_number, rooms.room_type, rooms.price_per_night');
        $this->db->from($this->table);
        $this->db->join('rooms', 'bookings.room_id = rooms.id', 'left');
        $this->db->where('bookings.user_id', $user_id);
        $this->db->where('bookings.deleted_at IS NULL');
        $this->db->order_by('bookings.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * Get all bookings with filters
     */
    public function get_all_bookings($filters = [], $limit = null, $offset = 0) {
        $this->db->select('bookings.*, users.first_name, users.last_name, users.email, 
                          rooms.room_number, rooms.room_type, rooms.floor');
        $this->db->from($this->table);
        $this->db->join('users', 'bookings.user_id = users.id', 'left');
        $this->db->join('rooms', 'bookings.room_id = rooms.id', 'left');
        $this->db->where('bookings.deleted_at IS NULL');
        
        // Apply filters
        if (!empty($filters['status'])) {
            $this->db->where('bookings.status', $filters['status']);
        }
        
        if (!empty($filters['payment_status'])) {
            $this->db->where('bookings.payment_status', $filters['payment_status']);
        }
        
        if (!empty($filters['check_in_from'])) {
            $this->db->where('bookings.check_in_date >=', $filters['check_in_from']);
        }
        
        if (!empty($filters['check_in_to'])) {
            $this->db->where('bookings.check_in_date <=', $filters['check_in_to']);
        }
        
        if (!empty($filters['room_type'])) {
            $this->db->where('rooms.room_type', $filters['room_type']);
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('bookings.booking_reference', $filters['search']);
            $this->db->or_like('users.first_name', $filters['search']);
            $this->db->or_like('users.last_name', $filters['search']);
            $this->db->or_like('users.email', $filters['search']);
            $this->db->or_like('rooms.room_number', $filters['search']);
            $this->db->group_end();
        }
        
        $this->db->order_by('bookings.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * Check room availability
     */
    public function is_room_available($room_id, $check_in_date, $check_out_date, $exclude_booking_id = null) {
        $this->db->where('room_id', $room_id);
        $this->db->where('status !=', 'cancelled');
        $this->db->where('deleted_at IS NULL');
        $this->db->group_start();
            $this->db->group_start();
                $this->db->where('check_in_date <=', $check_in_date);
                $this->db->where('check_out_date >', $check_in_date);
            $this->db->group_end();
            $this->db->or_group_start();
                $this->db->where('check_in_date <', $check_out_date);
                $this->db->where('check_out_date >=', $check_out_date);
            $this->db->group_end();
            $this->db->or_group_start();
                $this->db->where('check_in_date >=', $check_in_date);
                $this->db->where('check_out_date <=', $check_out_date);
            $this->db->group_end();
        $this->db->group_end();
        
        if ($exclude_booking_id) {
            $this->db->where('id !=', $exclude_booking_id);
        }
        
        return $this->db->count_all_results($this->table) === 0;
    }
    
    /**
     * Get occupied rooms count for a specific date
     */
    public function get_occupied_count_on($date) {
        $this->db->where('check_in_date <=', $date);
        $this->db->where('check_out_date >', $date);
        $this->db->where_in('status', ['confirmed', 'checked_in']);
        $this->db->where('deleted_at IS NULL');
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Get revenue for a specific date
     */
    public function get_revenue_on($date) {
        $this->db->select_sum('total_amount');
        $this->db->where('DATE(created_at)', $date);
        $this->db->where_in('status', ['confirmed', 'checked_in', 'checked_out']);
        $this->db->where('deleted_at IS NULL');
        $result = $this->db->get($this->table)->row();
        return $result->total_amount ?: 0;
    }
    
    /**
     * Get booking statistics
     */
    public function get_booking_statistics() {
        $stats = [];
        
        // Bookings by status
        $this->db->select('status, COUNT(*) as count');
        $this->db->where('deleted_at IS NULL');
        $this->db->group_by('status');
        $status_stats = $this->db->get($this->table)->result();
        
        foreach ($status_stats as $stat) {
            $stats['by_status'][$stat->status] = $stat->count;
        }
        
        // Revenue this month
        $this->db->select_sum('total_amount');
        $this->db->where('created_at >=', date('Y-m-01'));
        $this->db->where_in('status', ['confirmed', 'checked_in', 'checked_out']);
        $this->db->where('deleted_at IS NULL');
        $result = $this->db->get($this->table)->row();
        $stats['revenue_this_month'] = $result->total_amount ?: 0;
        
        // Bookings this month
        $this->db->where('created_at >=', date('Y-m-01'));
        $this->db->where('deleted_at IS NULL');
        $stats['bookings_this_month'] = $this->db->count_all_results($this->table);
        
        return $stats;
    }
    
    /**
     * Generate unique booking reference
     */
    public function generate_booking_reference() {
        do {
            $reference = 'BK' . date('Y') . str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        } while ($this->get_booking_by_reference($reference));
        
        return $reference;
    }
    
    /**
     * Calculate booking amounts
     */
    public function calculate_booking_amounts($data) {
        $room = $this->Room_model->get_room_by_id($data['room_id']);
        if (!$room) {
            throw new Exception('Room not found');
        }
        
        // Calculate nights
        $check_in = new DateTime($data['check_in_date']);
        $check_out = new DateTime($data['check_out_date']);
        $nights = $check_in->diff($check_out)->days;
        
        if ($nights <= 0) {
            throw new Exception('Invalid date range');
        }
        
        // Base amount
        $base_amount = $room->price_per_night * $nights * ($data['rooms'] ?? 1);
        
        // Tax calculation (configurable)
        $tax_rate = 0.12; // 12% tax
        $tax_amount = $base_amount * $tax_rate;
        
        // Discount from coupon
        $coupon_discount = $data['coupon_discount'] ?? 0;
        
        // Loyalty points discount
        $loyalty_discount = ($data['loyalty_points_used'] ?? 0) * 0.01; // 1 point = $0.01
        
        $total_discount = $coupon_discount + $loyalty_discount;
        $total_amount = $base_amount + $tax_amount - $total_discount;
        
        // Loyalty points earned (1 point per $1 spent)
        $loyalty_points_earned = floor($total_amount);
        
        return [
            'nights' => $nights,
            'base_amount' => round($base_amount, 2),
            'tax_amount' => round($tax_amount, 2),
            'discount_amount' => round($total_discount, 2),
            'total_amount' => round($total_amount, 2),
            'loyalty_points_earned' => $loyalty_points_earned
        ];
    }
    
    /**
     * Calculate refund amount based on cancellation policy
     */
    public function calculate_refund_amount($booking) {
        $now = new DateTime();
        $check_in = new DateTime($booking->check_in_date);
        $days_until_checkin = $now->diff($check_in)->days;
        
        // Cancellation policy:
        // - More than 7 days: 90% refund
        // - 3-7 days: 50% refund  
        // - Less than 3 days: No refund
        
        if ($days_until_checkin >= 7) {
            return $booking->total_amount * 0.9;
        } elseif ($days_until_checkin >= 3) {
            return $booking->total_amount * 0.5;
        } else {
            return 0;
        }
    }
    
    /**
     * Validate booking data
     */
    private function validate_booking_data($data) {
        $errors = [];
        
        // Required fields
        $required = ['user_id', 'room_id', 'check_in_date', 'check_out_date', 'adults'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }
        
        // Date validation
        if (isset($data['check_in_date']) && isset($data['check_out_date'])) {
            $check_in = new DateTime($data['check_in_date']);
            $check_out = new DateTime($data['check_out_date']);
            $today = new DateTime();
            
            if ($check_in < $today) {
                $errors[] = 'Check-in date cannot be in the past';
            }
            
            if ($check_out <= $check_in) {
                $errors[] = 'Check-out date must be after check-in date';
            }
            
            if ($check_in->diff($check_out)->days > 365) {
                $errors[] = 'Booking period cannot exceed 365 days';
            }
        }
        
        // Guest count validation
        if (isset($data['adults']) && $data['adults'] < 1) {
            $errors[] = 'At least one adult is required';
        }
        
        if (isset($data['children']) && $data['children'] < 0) {
            $errors[] = 'Children count cannot be negative';
        }
        
        // Email validation for guest bookings
        if (!empty($data['guest_email']) && !filter_var($data['guest_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid guest email format';
        }
        
        return empty($errors) ? true : $errors;
    }
    
    /**
     * Handle status changes
     */
    private function _handle_status_change($booking, $new_status, $current_user_id) {
        switch ($new_status) {
            case 'confirmed':
                if ($booking->status === 'pending') {
                    $this->Room_model->update_room_status($booking->room_id, 'occupied');
                }
                break;
                
            case 'cancelled':
                if (in_array($booking->status, ['pending', 'confirmed'])) {
                    $this->Room_model->update_room_status($booking->room_id, 'available');
                }
                break;
                
            case 'checked_out':
                $this->Room_model->update_room_status($booking->room_id, 'available');
                break;
        }
    }
    
    /**
     * Update booking status
     */
    private function _update_booking_status($id, $status, $staff_id = null, $additional_data = []) {
        $update_data = array_merge([
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ], $additional_data);
        
        $this->db->where('id', $id);
        $result = $this->db->update($this->table, $update_data);
        
        if ($result) {
            $this->security_lib->log_security_event('booking_status_changed', $staff_id, [
                'booking_id' => $id,
                'new_status' => $status
            ]);
        }
        
        return $result;
    }
    
    /**
     * Record coupon usage
     */
    private function _record_coupon_usage($coupon_code, $user_id, $booking_id, $discount_amount) {
        // Get coupon ID
        $this->db->select('id');
        $this->db->where('code', $coupon_code);
        $coupon = $this->db->get('coupons')->row();
        
        if ($coupon) {
            // Record usage
            $this->db->insert('used_coupons', [
                'coupon_id' => $coupon->id,
                'user_id' => $user_id,
                'booking_id' => $booking_id,
                'discount_amount' => $discount_amount,
                'used_at' => date('Y-m-d H:i:s')
            ]);
            
            // Update coupon usage count
            $this->db->where('id', $coupon->id);
            $this->db->set('used_count', 'used_count + 1', false);
            $this->db->update('coupons');
        }
    }
} 