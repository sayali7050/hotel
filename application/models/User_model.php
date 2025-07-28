<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Model
 * 
 * Enhanced user management with security features
 * 
 * @package Hotel Management System
 * @version 2.0
 */
class User_model extends CI_Model {
    
    protected $table = 'users';
    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name', 
        'phone', 'address', 'role', 'status', 'email_verified',
        'two_fa_enabled', 'permissions', 'preferences', 'loyalty_points'
    ];
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('security_lib');
    }
    
    /**
     * Authenticate user with enhanced security
     */
    public function authenticate($identifier, $password, $type = 'username') {
        // Check rate limiting
        if (!$this->security_lib->check_rate_limit('login')) {
            return ['success' => false, 'error' => 'Too many login attempts. Please try again later.'];
        }
        
        // Check login attempts
        $attempt_check = $this->security_lib->check_login_attempts($identifier, $type);
        if (!$attempt_check['allowed']) {
            return ['success' => false, 'error' => $attempt_check['reason']];
        }
        
        $user = $attempt_check['user'];
        
        // Verify password
        if (!password_verify($password, $user->password)) {
            $this->security_lib->record_failed_login($user->id);
            return ['success' => false, 'error' => 'Invalid credentials'];
        }
        
        // Check user status
        if ($user->status !== 'active') {
            return ['success' => false, 'error' => 'Account is not active'];
        }
        
        // Check email verification
        if (!$user->email_verified) {
            return ['success' => false, 'error' => 'Email not verified', 'user_id' => $user->id];
        }
        
        // Reset login attempts on successful authentication
        $this->security_lib->reset_login_attempts($user->id);
        
        return ['success' => true, 'user' => $user];
    }
    
    /**
     * Create new user with validation and security
     */
    public function create_user($data, $skip_validation = false) {
        // Start transaction
        $this->db->trans_start();
        
        try {
            // Sanitize input
            $data = $this->security_lib->sanitize_input($data, [
                'username' => ['string', 'no_html'],
                'email' => ['email'],
                'first_name' => ['string', 'no_html'],
                'last_name' => ['string', 'no_html'],
                'phone' => ['string', 'no_html'],
                'address' => ['string', 'html']
            ]);
            
            // Validate required fields
            if (!$skip_validation) {
                $validation_result = $this->validate_user_data($data);
                if ($validation_result !== true) {
                    throw new Exception(implode(', ', $validation_result));
                }
            }
            
            // Check for duplicate username and email
            if ($this->username_exists($data['username'])) {
                throw new Exception('Username already exists');
            }
            
            if ($this->email_exists($data['email'])) {
                throw new Exception('Email already exists');
            }
            
            // Hash password if provided
            if (isset($data['password'])) {
                $password_validation = $this->security_lib->validate_password($data['password']);
                if ($password_validation !== true) {
                    throw new Exception(implode(', ', $password_validation));
                }
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            // Set defaults
            $data['role'] = $data['role'] ?? 'customer';
            $data['status'] = $data['status'] ?? 'active';
            $data['email_verified'] = $data['email_verified'] ?? 0;
            $data['two_fa_enabled'] = $data['two_fa_enabled'] ?? 0;
            $data['loyalty_points'] = $data['loyalty_points'] ?? 0;
            $data['created_at'] = date('Y-m-d H:i:s');
            
            // Generate email verification token
            $data['email_verification_token'] = $this->security_lib->generate_token();
            
            // Filter allowed fields
            $insert_data = array_intersect_key($data, array_flip($this->fillable));
            $insert_data['email_verification_token'] = $data['email_verification_token'];
            
            // Insert user
            $this->db->insert($this->table, $insert_data);
            $user_id = $this->db->insert_id();
            
            if (!$user_id) {
                throw new Exception('Failed to create user');
            }
            
            // Set IP address for audit log
            $this->db->set('ip_address', $this->security_lib->get_client_ip());
            
            // Log user creation
            $this->security_lib->log_security_event('user_created', $user_id, [
                'username' => $data['username'],
                'email' => $data['email'],
                'role' => $data['role']
            ]);
            
            // Complete transaction
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            return $user_id;
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }
    
    /**
     * Update user with validation and security
     */
    public function update_user($id, $data, $current_user_id = null) {
        $this->db->trans_start();
        
        try {
            // Get current user data for audit log
            $old_user = $this->get_user_by_id($id);
            if (!$old_user) {
                throw new Exception('User not found');
            }
            
            // Sanitize input
            $data = $this->security_lib->sanitize_input($data, [
                'username' => ['string', 'no_html'],
                'email' => ['email'],
                'first_name' => ['string', 'no_html'],
                'last_name' => ['string', 'no_html'],
                'phone' => ['string', 'no_html'],
                'address' => ['string', 'html']
            ]);
            
            // Check for duplicate username and email (excluding current user)
            if (isset($data['username']) && $this->username_exists($data['username'], $id)) {
                throw new Exception('Username already exists');
            }
            
            if (isset($data['email']) && $this->email_exists($data['email'], $id)) {
                throw new Exception('Email already exists');
            }
            
            // Handle password update
            if (isset($data['password']) && !empty($data['password'])) {
                $password_validation = $this->security_lib->validate_password($data['password']);
                if ($password_validation !== true) {
                    throw new Exception(implode(', ', $password_validation));
                }
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            } else {
                unset($data['password']);
            }
            
            // Set updated timestamp
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            // Filter allowed fields
            $update_data = array_intersect_key($data, array_flip($this->fillable));
            $update_data['updated_at'] = $data['updated_at'];
            
            // Update user
            $this->db->where('id', $id);
            $this->db->update($this->table, $update_data);
            
            if ($this->db->affected_rows() === 0) {
                throw new Exception('No changes made or user not found');
            }
            
            // Log user update
            $this->security_lib->log_security_event('user_updated', $current_user_id ?: $id, [
                'target_user_id' => $id,
                'changes' => array_keys($update_data)
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
     * Soft delete user
     */
    public function delete_user($id, $current_user_id = null) {
        $this->db->trans_start();
        
        try {
            // Check if user exists
            $user = $this->get_user_by_id($id);
            if (!$user) {
                throw new Exception('User not found');
            }
            
            // Soft delete
            $this->db->where('id', $id);
            $this->db->update($this->table, [
                'status' => 'inactive',
                'deleted_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            // Log user deletion
            $this->security_lib->log_security_event('user_deleted', $current_user_id, [
                'target_user_id' => $id,
                'username' => $user->username,
                'email' => $user->email
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
     * Get user by ID
     */
    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('deleted_at IS NULL');
        return $this->db->get($this->table)->row();
    }
    
    /**
     * Get user by username or email
     */
    public function get_user_by_identifier($identifier) {
        $this->db->group_start();
        $this->db->where('username', $identifier);
        $this->db->or_where('email', $identifier);
        $this->db->group_end();
        $this->db->where('deleted_at IS NULL');
        return $this->db->get($this->table)->row();
    }
    
    /**
     * Get users by role with pagination
     */
    public function get_users_by_role($role, $limit = null, $offset = 0) {
        $this->db->where('role', $role);
        $this->db->where('deleted_at IS NULL');
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Get staff with assignments
     */
    public function get_staff_with_assignments() {
        $this->db->select('users.*, staff_assignments.department, staff_assignments.position, 
                          staff_assignments.salary, staff_assignments.hire_date, staff_assignments.status as assignment_status');
        $this->db->from($this->table);
        $this->db->join('staff_assignments', 'users.id = staff_assignments.staff_id', 'left');
        $this->db->where('users.role', 'staff');
        $this->db->where('users.deleted_at IS NULL');
        $this->db->order_by('users.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    /**
     * Check if username exists
     */
    public function username_exists($username, $exclude_id = null) {
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $this->db->where('username', $username);
        $this->db->where('deleted_at IS NULL');
        return $this->db->get($this->table)->num_rows() > 0;
    }
    
    /**
     * Check if email exists
     */
    public function email_exists($email, $exclude_id = null) {
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $this->db->where('email', $email);
        $this->db->where('deleted_at IS NULL');
        return $this->db->get($this->table)->num_rows() > 0;
    }
    
    /**
     * Verify email address
     */
    public function verify_email($token) {
        $this->db->where('email_verification_token', $token);
        $user = $this->db->get($this->table)->row();
        
        if ($user) {
            $this->db->where('id', $user->id);
            $this->db->update($this->table, [
                'email_verified' => 1,
                'email_verification_token' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $this->security_lib->log_security_event('email_verified', $user->id);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Generate password reset token
     */
    public function generate_password_reset_token($email) {
        $user = $this->get_user_by_identifier($email);
        if (!$user) {
            return false;
        }
        
        $token = $this->security_lib->generate_token();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $this->db->where('id', $user->id);
        $this->db->update($this->table, [
            'password_reset_token' => $token,
            'password_reset_expires' => $expires,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $this->security_lib->log_security_event('password_reset_requested', $user->id);
        
        return $token;
    }
    
    /**
     * Reset password using token
     */
    public function reset_password($token, $new_password) {
        // Find user by token
        $this->db->where('password_reset_token', $token);
        $this->db->where('password_reset_expires >', date('Y-m-d H:i:s'));
        $user = $this->db->get($this->table)->row();
        
        if (!$user) {
            return false;
        }
        
        // Validate new password
        $password_validation = $this->security_lib->validate_password($new_password);
        if ($password_validation !== true) {
            throw new Exception(implode(', ', $password_validation));
        }
        
        // Update password
        $this->db->where('id', $user->id);
        $this->db->update($this->table, [
            'password' => password_hash($new_password, PASSWORD_DEFAULT),
            'password_reset_token' => null,
            'password_reset_expires' => null,
            'login_attempts' => 0,
            'locked_until' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $this->security_lib->log_security_event('password_reset_completed', $user->id);
        
        return true;
    }
    
    /**
     * Enable/disable 2FA
     */
    public function toggle_2fa($user_id, $enabled = true) {
        $secret = $enabled ? $this->security_lib->generate_token(16) : null;
        
        $this->db->where('id', $user_id);
        $this->db->update($this->table, [
            'two_fa_enabled' => $enabled ? 1 : 0,
            'two_fa_secret' => $secret,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $this->security_lib->log_security_event('2fa_toggled', $user_id, ['enabled' => $enabled]);
        
        return $secret;
    }
    
    /**
     * Get user permissions
     */
    public function get_permissions($user_id) {
        $user = $this->get_user_by_id($user_id);
        if ($user && !empty($user->permissions)) {
            return json_decode($user->permissions, true);
        }
        return [];
    }
    
    /**
     * Set user permissions
     */
    public function set_permissions($user_id, $permissions, $current_user_id = null) {
        $this->db->where('id', $user_id);
        $result = $this->db->update($this->table, [
            'permissions' => json_encode($permissions),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            $this->security_lib->log_security_event('permissions_updated', $current_user_id, [
                'target_user_id' => $user_id,
                'permissions' => $permissions
            ]);
        }
        
        return $result;
    }
    
    /**
     * Add loyalty points
     */
    public function add_loyalty_points($user_id, $points, $reason = null) {
        $this->db->where('id', $user_id);
        $this->db->set('loyalty_points', 'loyalty_points + ' . (int)$points, false);
        $this->db->set('updated_at', date('Y-m-d H:i:s'));
        $result = $this->db->update($this->table);
        
        if ($result) {
            $this->security_lib->log_security_event('loyalty_points_added', $user_id, [
                'points' => $points,
                'reason' => $reason
            ]);
        }
        
        return $result;
    }
    
    /**
     * Get user statistics
     */
    public function get_user_statistics() {
        $stats = [];
        
        // Total users by role
        $this->db->select('role, COUNT(*) as count');
        $this->db->where('deleted_at IS NULL');
        $this->db->group_by('role');
        $role_stats = $this->db->get($this->table)->result();
        
        foreach ($role_stats as $stat) {
            $stats['by_role'][$stat->role] = $stat->count;
        }
        
        // Total users by status
        $this->db->select('status, COUNT(*) as count');
        $this->db->where('deleted_at IS NULL');
        $this->db->group_by('status');
        $status_stats = $this->db->get($this->table)->result();
        
        foreach ($status_stats as $stat) {
            $stats['by_status'][$stat->status] = $stat->count;
        }
        
        // New users this month
        $this->db->where('created_at >=', date('Y-m-01'));
        $this->db->where('deleted_at IS NULL');
        $stats['new_this_month'] = $this->db->count_all_results($this->table);
        
        return $stats;
    }
    
    /**
     * Validate user data
     */
    private function validate_user_data($data) {
        $errors = [];
        
        // Required fields
        $required = ['username', 'email', 'first_name', 'last_name'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }
        
        // Username validation
        if (isset($data['username'])) {
            if (strlen($data['username']) < 3 || strlen($data['username']) > 50) {
                $errors[] = 'Username must be between 3 and 50 characters';
            }
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
                $errors[] = 'Username can only contain letters, numbers, and underscores';
            }
        }
        
        // Email validation
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        // Phone validation
        if (isset($data['phone']) && !empty($data['phone'])) {
            if (!preg_match('/^[\+]?[0-9\s\-\(\)]{10,15}$/', $data['phone'])) {
                $errors[] = 'Invalid phone number format';
            }
        }
        
        return empty($errors) ? true : $errors;
    }
} 