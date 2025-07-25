<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loyalty_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Settings_model');
    }
    
    // Award points to user
    public function award_points($user_id, $points, $description, $booking_id = null) {
        $this->db->trans_start();
        
        // Add points to user
        $this->db->set('loyalty_points', 'loyalty_points + ' . (int)$points, false);
        $this->db->where('id', $user_id);
        $this->db->update('users');
        
        // Log transaction
        $this->db->insert('loyalty_transactions', [
            'user_id' => $user_id,
            'booking_id' => $booking_id,
            'transaction_type' => 'earned',
            'points' => $points,
            'description' => $description
        ]);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    // Redeem points
    public function redeem_points($user_id, $points, $description, $booking_id = null) {
        // Check if user has enough points
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user || $user->loyalty_points < $points) {
            return false;
        }
        
        $this->db->trans_start();
        
        // Deduct points from user
        $this->db->set('loyalty_points', 'loyalty_points - ' . (int)$points, false);
        $this->db->where('id', $user_id);
        $this->db->update('users');
        
        // Log transaction
        $this->db->insert('loyalty_transactions', [
            'user_id' => $user_id,
            'booking_id' => $booking_id,
            'transaction_type' => 'redeemed',
            'points' => -$points,
            'description' => $description
        ]);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    // Get user's loyalty transactions
    public function get_user_transactions($user_id, $limit = 50) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('loyalty_transactions')->result();
    }
    
    // Calculate points for booking amount
    public function calculate_points_for_amount($amount) {
        $points_per_dollar = $this->Settings_model->get_setting('loyalty_points_per_dollar', 1);
        return floor($amount * $points_per_dollar);
    }
    
    // Calculate discount amount for points
    public function calculate_discount_for_points($points) {
        $redemption_rate = $this->Settings_model->get_setting('loyalty_redemption_rate', 100);
        return floor($points / $redemption_rate);
    }
    
    // Get loyalty tier for user
    public function get_user_tier($user_id) {
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user) return null;
        
        $points = $user->loyalty_points;
        
        // Define tiers
        if ($points >= 10000) {
            return [
                'name' => 'Platinum',
                'benefits' => ['15% discount', 'Free room upgrades', 'Late checkout', 'Welcome amenities'],
                'next_tier' => null,
                'points_to_next' => 0
            ];
        } elseif ($points >= 5000) {
            return [
                'name' => 'Gold',
                'benefits' => ['10% discount', 'Priority booking', 'Free WiFi'],
                'next_tier' => 'Platinum',
                'points_to_next' => 10000 - $points
            ];
        } elseif ($points >= 1000) {
            return [
                'name' => 'Silver',
                'benefits' => ['5% discount', 'Member rates'],
                'next_tier' => 'Gold',
                'points_to_next' => 5000 - $points
            ];
        } else {
            return [
                'name' => 'Bronze',
                'benefits' => ['Member rates'],
                'next_tier' => 'Silver',
                'points_to_next' => 1000 - $points
            ];
        }
    }
    
    // Get tier discount percentage
    public function get_tier_discount($user_id) {
        $tier = $this->get_user_tier($user_id);
        
        switch ($tier['name']) {
            case 'Platinum':
                return 15;
            case 'Gold':
                return 10;
            case 'Silver':
                return 5;
            default:
                return 0;
        }
    }
    
    // Expire old points (run this as a cron job)
    public function expire_points($months = 24) {
        $expiry_date = date('Y-m-d H:i:s', strtotime("-{$months} months"));
        
        // Get points to expire
        $this->db->select('user_id, SUM(points) as points_to_expire');
        $this->db->where('transaction_type', 'earned');
        $this->db->where('created_at <', $expiry_date);
        $this->db->group_by('user_id');
        $expiring_points = $this->db->get('loyalty_transactions')->result();
        
        foreach ($expiring_points as $expiry) {
            if ($expiry->points_to_expire > 0) {
                $this->db->trans_start();
                
                // Deduct expired points from user
                $this->db->set('loyalty_points', 'GREATEST(loyalty_points - ' . (int)$expiry->points_to_expire . ', 0)', false);
                $this->db->where('id', $expiry->user_id);
                $this->db->update('users');
                
                // Log expiry transaction
                $this->db->insert('loyalty_transactions', [
                    'user_id' => $expiry->user_id,
                    'transaction_type' => 'expired',
                    'points' => -$expiry->points_to_expire,
                    'description' => 'Points expired after ' . $months . ' months'
                ]);
                
                $this->db->trans_complete();
            }
        }
    }
    
    // Get loyalty statistics
    public function get_loyalty_stats() {
        // Total points awarded
        $this->db->select_sum('points');
        $this->db->where('transaction_type', 'earned');
        $total_awarded = $this->db->get('loyalty_transactions')->row()->points ?? 0;
        
        // Total points redeemed
        $this->db->select_sum('points');
        $this->db->where('transaction_type', 'redeemed');
        $total_redeemed = abs($this->db->get('loyalty_transactions')->row()->points ?? 0);
        
        // Active members with points
        $this->db->where('loyalty_points >', 0);
        $active_members = $this->db->count_all_results('users');
        
        // Tier distribution
        $this->db->select('
            SUM(CASE WHEN loyalty_points >= 10000 THEN 1 ELSE 0 END) as platinum,
            SUM(CASE WHEN loyalty_points >= 5000 AND loyalty_points < 10000 THEN 1 ELSE 0 END) as gold,
            SUM(CASE WHEN loyalty_points >= 1000 AND loyalty_points < 5000 THEN 1 ELSE 0 END) as silver,
            SUM(CASE WHEN loyalty_points < 1000 THEN 1 ELSE 0 END) as bronze
        ', false);
        $tier_stats = $this->db->get('users')->row();
        
        return [
            'total_awarded' => $total_awarded,
            'total_redeemed' => $total_redeemed,
            'active_members' => $active_members,
            'tier_distribution' => [
                'platinum' => $tier_stats->platinum,
                'gold' => $tier_stats->gold,
                'silver' => $tier_stats->silver,
                'bronze' => $tier_stats->bronze
            ]
        ];
    }
    
    // Award points for booking
    public function award_booking_points($user_id, $booking_id, $amount) {
        $points = $this->calculate_points_for_amount($amount);
        
        if ($points > 0) {
            return $this->award_points(
                $user_id,
                $points,
                "Points earned from booking #$booking_id ($$amount)",
                $booking_id
            );
        }
        
        return true;
    }
    
    // Apply loyalty discount to booking
    public function apply_loyalty_discount($user_id, $amount) {
        $discount_percentage = $this->get_tier_discount($user_id);
        
        if ($discount_percentage > 0) {
            return ($amount * $discount_percentage) / 100;
        }
        
        return 0;
    }
}