<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Register new user
    public function register_user($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role'] = 'customer'; // Default role for self-registration
        $data['status'] = 'active';
        
        return $this->db->insert('users', $data);
    }
    
    // Register staff (by admin)
    public function register_staff($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role'] = 'staff';
        $data['status'] = 'active';
        
        return $this->db->insert('users', $data);
    }
    
    // Create user (for guest bookings)
    public function create_user($data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }
    
    // Login user
    public function login($username, $password) {
        // First try to find user by username
        $this->db->where('username', $username);
        $this->db->where('status', 'active');
        $user = $this->db->get('users')->row();
        
        // If not found by username, try email
        if (!$user) {
            $this->db->where('email', $username);
            $this->db->where('status', 'active');
            $user = $this->db->get('users')->row();
        }
        
        // If user found, verify password
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return false;
    }
    
    // Get user by ID
    public function get_user_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }
    
    // Get all users by role
    public function get_users_by_role($role) {
        return $this->db->get_where('users', ['role' => $role])->result();
    }
    
    // Get all staff with assignments
    public function get_staff_with_assignments() {
        $this->db->select('users.*, staff_assignments.department, staff_assignments.position, staff_assignments.salary, staff_assignments.hire_date');
        $this->db->from('users');
        $this->db->join('staff_assignments', 'users.id = staff_assignments.staff_id', 'left');
        $this->db->where('users.role', 'staff');
        return $this->db->get()->result();
    }
    
    // Update user
    public function update_user($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }
    
    // Delete user
    public function delete_user($id) {
        return $this->db->delete('users', ['id' => $id]);
    }
    
    // Check if username exists
    public function username_exists($username, $exclude_id = null) {
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $this->db->where('username', $username);
        return $this->db->get('users')->num_rows() > 0;
    }
    
    // Check if email exists
    public function email_exists($email, $exclude_id = null) {
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $this->db->where('email', $email);
        return $this->db->get('users')->num_rows() > 0;
    }
    
    // Get user count by role
    public function get_user_count_by_role($role) {
        return $this->db->where('role', $role)->count_all_results('users');
    }
    
    // Get recent users
    public function get_recent_users($limit = 10) {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('users')->result();
    }

    // Get permissions as array
    public function get_permissions($user_id) {
        $user = $this->get_user_by_id($user_id);
        if ($user && !empty($user->permissions)) {
            return json_decode($user->permissions, true);
        }
        return [];
    }

    // Set permissions (array) for a user
    public function set_permissions($user_id, $permissions) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['permissions' => json_encode($permissions)]);
    }

    // Get loyalty points for a user
    public function get_loyalty_points($user_id) {
        $user = $this->get_user_by_id($user_id);
        return $user ? (int)$user->loyalty_points : 0;
    }

    // Add loyalty points to a user
    public function add_loyalty_points($user_id, $points) {
        $current = $this->get_loyalty_points($user_id);
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['loyalty_points' => $current + (int)$points]);
    }

    // Set preferences for a user (as JSON string)
    public function set_preferences($user_id, $preferences) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['preferences' => json_encode($preferences)]);
    }

    // Get preferences for a user (as array)
    public function get_preferences($user_id) {
        $user = $this->get_user_by_id($user_id);
        return $user && $user->preferences ? json_decode($user->preferences, true) : [];
    }

    // Get user by username or email
    public function get_user_by_username_or_email($username) {
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        $this->db->where('status', 'active');
        return $this->db->get('users')->row();
    }

    // Get user by email
    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $this->db->where('status', 'active');
        return $this->db->get('users')->row();
    }

    // Increment failed login attempts
    public function increment_failed_attempts($user_id) {
        $this->db->set('failed_login_attempts', 'failed_login_attempts + 1', false);
        $this->db->set('last_failed_login', date('Y-m-d H:i:s'));
        $this->db->where('id', $user_id);
        return $this->db->update('users');
    }

    // Reset failed login attempts
    public function reset_failed_attempts($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', [
            'failed_login_attempts' => 0,
            'last_failed_login' => null,
            'account_locked_until' => null
        ]);
    }

    // Lock user account
    public function lock_account($user_id, $locked_until) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['account_locked_until' => $locked_until]);
    }

    // Set 2FA code
    public function set_2fa_code($user_id, $code, $expires) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', [
            'last_2fa_code' => $code,
            'last_2fa_expires' => $expires
        ]);
    }

    // Verify 2FA code
    public function verify_2fa_code($user_id, $code) {
        $this->db->where('id', $user_id);
        $this->db->where('last_2fa_code', $code);
        $this->db->where('last_2fa_expires >=', date('Y-m-d H:i:s'));
        $user = $this->db->get('users')->row();

        if ($user) {
            // Clear 2FA code after successful verification
            $this->db->where('id', $user_id);
            $this->db->update('users', [
                'last_2fa_code' => null,
                'last_2fa_expires' => null
            ]);
            return true;
        }

        return false;
    }

    // Set password reset token
    public function set_password_reset_token($user_id, $token, $expires) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', [
            'password_reset_token' => $token,
            'password_reset_expires' => $expires
        ]);
    }

    // Get user by reset token
    public function get_user_by_reset_token($token) {
        $this->db->where('password_reset_token', $token);
        $this->db->where('status', 'active');
        return $this->db->get('users')->row();
    }

    // Clear password reset token
    public function clear_password_reset_token($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', [
            'password_reset_token' => null,
            'password_reset_expires' => null
        ]);
    }

    // Update password
    public function update_password($user_id, $password) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    // Update last login time
    public function update_last_login($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['updated_at' => date('Y-m-d H:i:s')]);
    }
} 