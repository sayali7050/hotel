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
} 