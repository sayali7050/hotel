<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Library
 * 
 * Provides comprehensive security features including:
 * - Rate limiting
 * - Input validation and sanitization
 * - Security headers
 * - Brute force protection
 * - CSRF protection enhancements
 * 
 * @package Hotel Management System
 * @version 2.0
 */
class Security_lib {
    
    protected $CI;
    protected $config;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->config = $this->CI->config->item('hotel_config');
    }
    
    /**
     * Check rate limiting for an action
     */
    public function check_rate_limit($action = 'general', $ip = null) {
        if (!$this->config['rate_limit_enabled']) {
            return true;
        }
        
        $ip = $ip ?: $this->get_client_ip();
        $limit = $this->config['rate_limit_requests'];
        $window = 60; // 1 minute window
        
        // Clean old entries
        $this->CI->db->where('last_attempt <', date('Y-m-d H:i:s', strtotime('-' . $window . ' seconds')));
        $this->CI->db->delete('rate_limits');
        
        // Check current rate
        $this->CI->db->where('ip_address', $ip);
        $this->CI->db->where('action', $action);
        $rate_limit = $this->CI->db->get('rate_limits')->row();
        
        if ($rate_limit) {
            if ($rate_limit->blocked_until && strtotime($rate_limit->blocked_until) > time()) {
                return false; // Still blocked
            }
            
            if ($rate_limit->attempts >= $limit) {
                // Block for 15 minutes
                $this->CI->db->where('id', $rate_limit->id);
                $this->CI->db->update('rate_limits', [
                    'blocked_until' => date('Y-m-d H:i:s', strtotime('+15 minutes'))
                ]);
                return false;
            }
            
            // Increment attempts
            $this->CI->db->where('id', $rate_limit->id);
            $this->CI->db->update('rate_limits', [
                'attempts' => $rate_limit->attempts + 1,
                'last_attempt' => date('Y-m-d H:i:s')
            ]);
        } else {
            // First attempt
            $this->CI->db->insert('rate_limits', [
                'ip_address' => $ip,
                'action' => $action,
                'attempts' => 1,
                'last_attempt' => date('Y-m-d H:i:s')
            ]);
        }
        
        return true;
    }
    
    /**
     * Check login attempts and implement lockout
     */
    public function check_login_attempts($identifier, $type = 'username') {
        $max_attempts = $this->config['max_login_attempts'];
        $lockout_time = $this->config['login_lockout_time'];
        
        $this->CI->db->where($type, $identifier);
        $user = $this->CI->db->get('users')->row();
        
        if (!$user) {
            return ['allowed' => false, 'reason' => 'User not found'];
        }
        
        // Check if account is locked
        if ($user->locked_until && strtotime($user->locked_until) > time()) {
            $unlock_time = date('Y-m-d H:i:s', strtotime($user->locked_until));
            return ['allowed' => false, 'reason' => "Account locked until {$unlock_time}"];
        }
        
        // Check attempt count
        if ($user->login_attempts >= $max_attempts) {
            // Lock the account
            $locked_until = date('Y-m-d H:i:s', strtotime('+' . $lockout_time . ' seconds'));
            $this->CI->db->where('id', $user->id);
            $this->CI->db->update('users', [
                'locked_until' => $locked_until,
                'status' => 'locked'
            ]);
            
            // Log the lockout
            $this->log_security_event('account_locked', $user->id, [
                'reason' => 'Too many failed login attempts',
                'attempts' => $user->login_attempts
            ]);
            
            return ['allowed' => false, 'reason' => "Account locked due to too many failed attempts"];
        }
        
        return ['allowed' => true, 'user' => $user];
    }
    
    /**
     * Record failed login attempt
     */
    public function record_failed_login($user_id) {
        $this->CI->db->where('id', $user_id);
        $this->CI->db->set('login_attempts', 'login_attempts + 1', false);
        $this->CI->db->update('users');
        
        $this->log_security_event('failed_login', $user_id);
    }
    
    /**
     * Reset login attempts on successful login
     */
    public function reset_login_attempts($user_id) {
        $this->CI->db->where('id', $user_id);
        $this->CI->db->update('users', [
            'login_attempts' => 0,
            'locked_until' => null,
            'last_login' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ]);
        
        $this->log_security_event('successful_login', $user_id);
    }
    
    /**
     * Validate and sanitize input data
     */
    public function sanitize_input($data, $rules = []) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize_input($value, $rules[$key] ?? []);
            }
            return $data;
        }
        
        // Basic sanitization
        $data = trim($data);
        $data = stripslashes($data);
        
        // Apply specific rules
        foreach ($rules as $rule) {
            switch ($rule) {
                case 'email':
                    $data = filter_var($data, FILTER_SANITIZE_EMAIL);
                    break;
                case 'url':
                    $data = filter_var($data, FILTER_SANITIZE_URL);
                    break;
                case 'int':
                    $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                    break;
                case 'float':
                    $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    break;
                case 'string':
                    $data = filter_var($data, FILTER_SANITIZE_STRING);
                    break;
                case 'no_html':
                    $data = strip_tags($data);
                    break;
                case 'html':
                    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
                    break;
            }
        }
        
        return $data;
    }
    
    /**
     * Validate password strength
     */
    public function validate_password($password) {
        $min_length = $this->config['password_min_length'];
        $errors = [];
        
        if (strlen($password) < $min_length) {
            $errors[] = "Password must be at least {$min_length} characters long";
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }
        
        return empty($errors) ? true : $errors;
    }
    
    /**
     * Generate secure random token
     */
    public function generate_token($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Generate 2FA code
     */
    public function generate_2fa_code() {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Set security headers
     */
    public function set_security_headers() {
        // Content Security Policy
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; img-src 'self' data: https:; font-src 'self' https://cdnjs.cloudflare.com;");
        
        // X-Frame-Options
        header("X-Frame-Options: DENY");
        
        // X-Content-Type-Options
        header("X-Content-Type-Options: nosniff");
        
        // X-XSS-Protection
        header("X-XSS-Protection: 1; mode=block");
        
        // Referrer Policy
        header("Referrer-Policy: strict-origin-when-cross-origin");
        
        // Strict Transport Security (HTTPS only)
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
        }
        
        // Remove server signature
        header_remove("X-Powered-By");
    }
    
    /**
     * Log security events
     */
    public function log_security_event($action, $user_id = null, $data = []) {
        if (!$this->config['audit_log_enabled']) {
            return;
        }
        
        $log_data = [
            'user_id' => $user_id,
            'action' => $action,
            'ip_address' => $this->get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'new_values' => !empty($data) ? json_encode($data) : null,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->CI->db->insert('audit_logs', $log_data);
    }
    
    /**
     * Get real client IP address
     */
    public function get_client_ip() {
        $ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    
                    if (filter_var($ip, FILTER_VALIDATE_IP, 
                        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Check if maintenance mode is enabled
     */
    public function is_maintenance_mode() {
        return $this->config['maintenance_mode'] ?? false;
    }
    
    /**
     * Encrypt sensitive data
     */
    public function encrypt_data($data) {
        $this->CI->load->library('encryption');
        return $this->CI->encryption->encrypt($data);
    }
    
    /**
     * Decrypt sensitive data
     */
    public function decrypt_data($encrypted_data) {
        $this->CI->load->library('encryption');
        return $this->CI->encryption->decrypt($encrypted_data);
    }
    
    /**
     * Validate file upload
     */
    public function validate_file_upload($file) {
        $allowed_types = explode('|', $this->config['allowed_file_types']);
        $max_size = $this->config['max_upload_size'];
        
        $errors = [];
        
        // Check file size
        if ($file['size'] > $max_size) {
            $errors[] = "File size exceeds maximum allowed size";
        }
        
        // Check file type
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_ext), $allowed_types)) {
            $errors[] = "File type not allowed";
        }
        
        // Check for potential malicious content
        $forbidden_extensions = ['php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'sh'];
        if (in_array(strtolower($file_ext), $forbidden_extensions)) {
            $errors[] = "Potentially dangerous file type";
        }
        
        return empty($errors) ? true : $errors;
    }
}