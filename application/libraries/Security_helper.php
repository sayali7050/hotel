<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Security_helper {
    
    private $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Settings_model');
        $this->CI->load->helper('security');
    }
    
    // Generate CSRF token
    public function generate_csrf_token() {
        $token = bin2hex(random_bytes(32));
        $this->CI->session->set_userdata('csrf_token', $token);
        return $token;
    }
    
    // Validate CSRF token
    public function validate_csrf_token($token) {
        $session_token = $this->CI->session->userdata('csrf_token');
        return $session_token && hash_equals($session_token, $token);
    }
    
    // Sanitize input data
    public function sanitize_input($data, $type = 'string') {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize_input($value, $type);
            }
            return $data;
        }
        
        switch ($type) {
            case 'email':
                return filter_var($data, FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var($data, FILTER_SANITIZE_URL);
            case 'int':
                return (int) $data;
            case 'float':
                return (float) $data;
            case 'html':
                return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            case 'sql':
                return $this->CI->db->escape_str($data);
            default:
                return strip_tags(trim($data));
        }
    }
    
    // Check for SQL injection patterns
    public function detect_sql_injection($input) {
        $patterns = [
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT)\b)/i',
            '/(\b(OR|AND)\s+\d+\s*=\s*\d+)/i',
            '/(\'|\"|;|--|\*|\/\*|\*\/)/i',
            '/(\b(INFORMATION_SCHEMA|SYSOBJECTS|SYSCOLUMNS)\b)/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    // Check for XSS patterns
    public function detect_xss($input) {
        $patterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/gi',
            '/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/gi'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    // Validate password strength
    public function validate_password_strength($password) {
        $min_length = $this->CI->Settings_model->get_setting('password_min_length', 8);
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
        
        // Check against common passwords
        $common_passwords = ['password', '123456', 'password123', 'admin', 'welcome', 'qwerty'];
        if (in_array(strtolower($password), $common_passwords)) {
            $errors[] = "Password is too common";
        }
        
        return empty($errors) ? true : $errors;
    }
    
    // Rate limiting
    public function check_rate_limit($action, $identifier, $max_attempts = 5, $time_window = 3600) {
        $cache_key = "rate_limit_{$action}_{$identifier}";
        $attempts = $this->CI->session->userdata($cache_key) ?? 0;
        
        if ($attempts >= $max_attempts) {
            return false;
        }
        
        return true;
    }
    
    // Increment rate limit counter
    public function increment_rate_limit($action, $identifier, $time_window = 3600) {
        $cache_key = "rate_limit_{$action}_{$identifier}";
        $attempts = $this->CI->session->userdata($cache_key) ?? 0;
        $this->CI->session->set_userdata($cache_key, $attempts + 1);
        
        // Set expiration (simplified - in production use proper cache with TTL)
        if ($attempts === 0) {
            $this->CI->session->set_userdata($cache_key . '_expires', time() + $time_window);
        }
    }
    
    // Log security event
    public function log_security_event($user_id, $action, $details, $risk_level = 'low') {
        $data = [
            'user_id' => $user_id,
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'action' => $action,
            'details' => $details,
            'risk_level' => $risk_level,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->CI->db->insert('security_logs', $data);
        
        // Alert on high-risk events
        if ($risk_level === 'high') {
            $this->alert_security_team($data);
        }
    }
    
    // Alert security team
    private function alert_security_team($event) {
        $this->CI->load->helper('notification');
        
        $message = "High-risk security event detected:\n\n";
        $message .= "Action: {$event['action']}\n";
        $message .= "IP Address: {$event['ip_address']}\n";
        $message .= "User Agent: {$event['user_agent']}\n";
        $message .= "Details: {$event['details']}\n";
        $message .= "Time: {$event['created_at']}\n";
        
        $admin_email = $this->CI->Settings_model->get_setting('security_alert_email', 'admin@hotel.com');
        send_booking_notification($admin_email, 'Security Alert', $message);
    }
    
    // Generate secure random token
    public function generate_secure_token($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    // Hash sensitive data
    public function hash_sensitive_data($data, $salt = null) {
        if (!$salt) {
            $salt = bin2hex(random_bytes(16));
        }
        
        return [
            'hash' => hash_pbkdf2('sha256', $data, $salt, 10000),
            'salt' => $salt
        ];
    }
    
    // Verify hashed data
    public function verify_hashed_data($data, $hash, $salt) {
        $computed_hash = hash_pbkdf2('sha256', $data, $salt, 10000);
        return hash_equals($hash, $computed_hash);
    }
    
    // Check if IP is in whitelist
    public function is_ip_whitelisted($ip) {
        $whitelist = $this->CI->Settings_model->get_setting('ip_whitelist', '');
        if (empty($whitelist)) {
            return true; // No whitelist means all IPs allowed
        }
        
        $allowed_ips = array_map('trim', explode(',', $whitelist));
        return in_array($ip, $allowed_ips);
    }
    
    // Check if IP is blacklisted
    public function is_ip_blacklisted($ip) {
        $blacklist = $this->CI->Settings_model->get_setting('ip_blacklist', '');
        if (empty($blacklist)) {
            return false;
        }
        
        $blocked_ips = array_map('trim', explode(',', $blacklist));
        return in_array($ip, $blocked_ips);
    }
    
    // Encrypt sensitive data
    public function encrypt_data($data, $key = null) {
        if (!$key) {
            $key = $this->CI->config->item('encryption_key') ?: 'default_key_change_this';
        }
        
        $cipher = 'AES-256-CBC';
        $iv = random_bytes(openssl_cipher_iv_length($cipher));
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        
        return base64_encode($iv . $encrypted);
    }
    
    // Decrypt sensitive data
    public function decrypt_data($encrypted_data, $key = null) {
        if (!$key) {
            $key = $this->CI->config->item('encryption_key') ?: 'default_key_change_this';
        }
        
        $cipher = 'AES-256-CBC';
        $data = base64_decode($encrypted_data);
        $iv_length = openssl_cipher_iv_length($cipher);
        $iv = substr($data, 0, $iv_length);
        $encrypted = substr($data, $iv_length);
        
        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }
    
    // Clean old security logs
    public function cleanup_security_logs($days = 90) {
        $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $this->CI->db->where('created_at <', $cutoff_date);
        return $this->CI->db->delete('security_logs');
    }
    
    // Get security statistics
    public function get_security_stats($days = 30) {
        $start_date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        // Failed login attempts
        $this->CI->db->where('action', 'failed_login');
        $this->CI->db->where('created_at >=', $start_date);
        $failed_logins = $this->CI->db->count_all_results('security_logs');
        
        // High-risk events
        $this->CI->db->where('risk_level', 'high');
        $this->CI->db->where('created_at >=', $start_date);
        $high_risk_events = $this->CI->db->count_all_results('security_logs');
        
        // Unique IPs
        $this->CI->db->distinct();
        $this->CI->db->select('ip_address');
        $this->CI->db->where('created_at >=', $start_date);
        $unique_ips = $this->CI->db->count_all_results('security_logs');
        
        return [
            'failed_logins' => $failed_logins,
            'high_risk_events' => $high_risk_events,
            'unique_ips' => $unique_ips,
            'period_days' => $days
        ];
    }
}