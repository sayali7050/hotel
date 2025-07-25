<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get setting value by key
    public function get_setting($key, $default = null) {
        $this->db->where('key', $key);
        $result = $this->db->get('settings')->row();
        
        if ($result) {
            // Convert based on type
            switch($result->type) {
                case 'number':
                    return (int)$result->value;
                case 'boolean':
                    return $result->value === 'true';
                case 'json':
                    return json_decode($result->value, true);
                default:
                    return $result->value;
            }
        }
        
        return $default;
    }
    
    // Set setting value
    public function set_setting($key, $value, $description = null, $type = 'string') {
        // Convert value to string for storage
        if ($type === 'boolean') {
            $value = $value ? 'true' : 'false';
        } elseif ($type === 'json') {
            $value = json_encode($value);
        } else {
            $value = (string)$value;
        }
        
        $data = [
            'key' => $key,
            'value' => $value,
            'description' => $description,
            'type' => $type
        ];
        
        $this->db->replace('settings', $data);
        return $this->db->affected_rows() > 0;
    }
    
    // Get all settings
    public function get_all_settings() {
        $results = $this->db->get('settings')->result();
        $settings = [];
        
        foreach ($results as $result) {
            // Convert based on type
            switch($result->type) {
                case 'number':
                    $value = (int)$result->value;
                    break;
                case 'boolean':
                    $value = $result->value === 'true';
                    break;
                case 'json':
                    $value = json_decode($result->value, true);
                    break;
                default:
                    $value = $result->value;
            }
            
            $settings[$result->key] = $value;
        }
        
        return $settings;
    }
    
    // Get settings by category (prefix)
    public function get_settings_by_category($prefix) {
        $this->db->like('key', $prefix, 'after');
        $results = $this->db->get('settings')->result();
        $settings = [];
        
        foreach ($results as $result) {
            $settings[$result->key] = $result->value;
        }
        
        return $settings;
    }
    
    // Delete setting
    public function delete_setting($key) {
        return $this->db->delete('settings', ['key' => $key]);
    }
    
    // Get hotel configuration
    public function get_hotel_config() {
        return [
            'name' => $this->get_setting('hotel_name', 'Grand Hotel'),
            'email' => $this->get_setting('hotel_email', 'info@hotel.com'),
            'phone' => $this->get_setting('hotel_phone', '+1-234-567-8900'),
            'address' => $this->get_setting('hotel_address', ''),
            'website' => $this->get_setting('hotel_website', ''),
            'logo' => $this->get_setting('hotel_logo', ''),
        ];
    }
    
    // Get email configuration
    public function get_email_config() {
        return [
            'smtp_host' => $this->get_setting('email_smtp_host', ''),
            'smtp_port' => $this->get_setting('email_smtp_port', 587),
            'smtp_username' => $this->get_setting('email_smtp_username', ''),
            'smtp_password' => $this->get_setting('email_smtp_password', ''),
            'from_email' => $this->get_setting('email_from_address', ''),
            'from_name' => $this->get_setting('email_from_name', ''),
        ];
    }
    
    // Get security configuration
    public function get_security_config() {
        return [
            'max_login_attempts' => $this->get_setting('max_login_attempts', 5),
            'account_lockout_duration' => $this->get_setting('account_lockout_duration', 30),
            'enable_2fa' => $this->get_setting('enable_2fa', true),
            'session_timeout' => $this->get_setting('session_timeout', 3600),
            'password_min_length' => $this->get_setting('password_min_length', 8),
        ];
    }
}