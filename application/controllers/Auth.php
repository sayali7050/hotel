<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'Settings_model']);
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'security', 'notification']);
    }
    
    // Admin login page
    public function admin_login() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'admin') {
            redirect('admin/dashboard');
        }
        
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/admin_login');
        } else {
            $this->_process_login('admin', 'admin/dashboard');
        }
    }

    // Staff login page
    public function staff_login() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'staff') {
            redirect('staff/dashboard');
        }
        
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/staff_login');
        } else {
            $this->_process_login('staff', 'staff/dashboard');
        }
    }

    // Customer login page
    public function customer_login() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'customer') {
            redirect('customer/dashboard');
        }
        
        $this->form_validation->set_rules('username', 'Username/Email', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/customer_login');
        } else {
            $this->_process_login('customer', 'customer/dashboard');
        }
    }

    // Process login with security checks
    private function _process_login($required_role, $redirect_url) {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $ip_address = $this->input->ip_address();
        $user_agent = $this->input->user_agent();
        
        // Check if IP is temporarily blocked
        if ($this->_is_ip_blocked($ip_address)) {
            $this->_log_security_event(null, $ip_address, 'login_attempt_blocked', 'IP temporarily blocked', 'medium');
            $this->session->set_flashdata('error', 'Too many failed attempts. Please try again later.');
            return;
        }
        
        // Attempt login
        $user = $this->User_model->login($username, $password);
        
        if ($user && $user->role == $required_role) {
            // Check if account is locked
            if ($this->_is_account_locked($user)) {
                $this->session->set_flashdata('error', 'Account is temporarily locked. Please try again later.');
                return;
            }
            
            // Reset failed attempts on successful login
            $this->User_model->reset_failed_attempts($user->id);
            
            // Check if 2FA is required
            if ($required_role == 'admin' && $this->_is_2fa_enabled()) {
                $this->_initiate_2fa($user);
                return;
            }
            
            // Complete login
            $this->_complete_login($user, $redirect_url);
            
        } else {
            // Handle failed login
            $this->_handle_failed_login($username, $ip_address, $user_agent);
        }
    }

    // Check if account is locked
    private function _is_account_locked($user) {
        if ($user->account_locked_until && strtotime($user->account_locked_until) > time()) {
            return true;
        }
        
        $max_attempts = $this->Settings_model->get_setting('max_login_attempts', 5);
        if ($user->failed_login_attempts >= $max_attempts) {
            // Lock account
            $lockout_duration = $this->Settings_model->get_setting('account_lockout_duration', 30);
            $locked_until = date('Y-m-d H:i:s', strtotime("+{$lockout_duration} minutes"));
            $this->User_model->lock_account($user->id, $locked_until);
            return true;
        }
        
        return false;
    }

    // Check if IP is blocked
    private function _is_ip_blocked($ip_address) {
        // Check failed attempts from this IP in last hour
        $this->db->where('ip_address', $ip_address);
        $this->db->where('action', 'failed_login');
        $this->db->where('created_at >', date('Y-m-d H:i:s', strtotime('-1 hour')));
        $attempts = $this->db->count_all_results('security_logs');
        
        return $attempts >= 10; // Block IP after 10 failed attempts in an hour
    }

    // Handle failed login
    private function _handle_failed_login($username, $ip_address, $user_agent) {
        // Log security event
        $this->_log_security_event(null, $ip_address, 'failed_login', "Failed login attempt for: $username", 'medium');
        
        // Increment failed attempts for user if exists
        $user = $this->User_model->get_user_by_username_or_email($username);
        if ($user) {
            $this->User_model->increment_failed_attempts($user->id);
        }
        
        $this->session->set_flashdata('error', 'Invalid username or password');
        redirect(uri_string());
    }

    // Initiate 2FA process
    private function _initiate_2fa($user) {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        $this->User_model->set_2fa_code($user->id, $code, $expires);
        
        // Send 2FA code via email
        $this->_send_2fa_code($user, $code);
        
        $this->session->set_userdata('pending_2fa_user_id', $user->id);
        $this->session->set_flashdata('success', 'A 2FA code has been sent to your email.');
        redirect('auth/verify_2fa');
    }

    // Send 2FA code via email
    private function _send_2fa_code($user, $code) {
        $template_data = [
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'code' => $code
        ];
        
        $this->load->model('Email_template_model');
        $template = $this->Email_template_model->get_template('2fa_code');
        
        if ($template) {
            $subject = $this->_parse_template($template->subject, $template_data);
            $body = $this->_parse_template($template->body, $template_data);
        } else {
            $subject = 'Your 2FA Authentication Code';
            $body = "Your 2FA code is: $code\nThis code will expire in 5 minutes.";
        }
        
        send_booking_notification($user->email, $subject, $body, null, ['user_id' => $user->id]);
    }

    // Verify 2FA code
    public function verify_2fa() {
        $user_id = $this->session->userdata('pending_2fa_user_id');
        if (!$user_id) {
            redirect('auth/admin_login');
        }
        
        $this->form_validation->set_rules('code', '2FA Code', 'required|exact_length[6]|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/verify_2fa');
        } else {
            $code = $this->input->post('code');
            $user = $this->User_model->get_user_by_id($user_id);
            
            if ($user && $this->User_model->verify_2fa_code($user->id, $code)) {
                $this->session->unset_userdata('pending_2fa_user_id');
                $redirect_url = $user->role == 'admin' ? 'admin/dashboard' : 'staff/dashboard';
                $this->_complete_login($user, $redirect_url);
            } else {
                $this->session->set_flashdata('error', 'Invalid or expired 2FA code');
                redirect('auth/verify_2fa');
            }
        }
    }

    // Complete login process
    private function _complete_login($user, $redirect_url) {
        $session_data = [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'role' => $user->role,
            'logged_in' => TRUE,
            'login_time' => time()
        ];
        
        $this->session->set_userdata($session_data);
        
        // Log successful login
        $this->_log_security_event($user->id, $this->input->ip_address(), 'successful_login', 'User logged in successfully', 'low');
        
        // Update last login time
        $this->User_model->update_last_login($user->id);
        
        // Redirect to intended page or dashboard
        $redirect_after_login = $this->session->userdata('redirect_after_login');
        if ($redirect_after_login) {
            $this->session->unset_userdata('redirect_after_login');
            redirect($redirect_after_login);
        } else {
            redirect($redirect_url);
        }
    }

    // Password reset request
    public function forgot_password() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/forgot_password');
        } else {
            $email = $this->input->post('email');
            $user = $this->User_model->get_user_by_email($email);
            
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $this->User_model->set_password_reset_token($user->id, $token, $expires);
                
                // Send reset email
                $reset_link = base_url("auth/reset_password/$token");
                $template_data = [
                    'user_name' => $user->first_name . ' ' . $user->last_name,
                    'reset_link' => $reset_link
                ];
                
                $this->load->model('Email_template_model');
                $template = $this->Email_template_model->get_template('password_reset');
                
                if ($template) {
                    $subject = $this->_parse_template($template->subject, $template_data);
                    $body = $this->_parse_template($template->body, $template_data);
                } else {
                    $subject = 'Password Reset Request';
                    $body = "Click the following link to reset your password: $reset_link\nThis link will expire in 1 hour.";
                }
                
                send_booking_notification($user->email, $subject, $body, null, ['user_id' => $user->id]);
            }
            
            // Always show success message for security
            $this->session->set_flashdata('success', 'If the email exists, a password reset link has been sent.');
            redirect('auth/forgot_password');
        }
    }

    // Reset password
    public function reset_password($token = null) {
        if (!$token) {
            redirect('auth/forgot_password');
        }
        
        $user = $this->User_model->get_user_by_reset_token($token);
        if (!$user || strtotime($user->password_reset_expires) < time()) {
            $this->session->set_flashdata('error', 'Invalid or expired reset token');
            redirect('auth/forgot_password');
        }
        
        $this->form_validation->set_rules('password', 'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        
        if ($this->form_validation->run() == FALSE) {
            $data['token'] = $token;
            $this->load->view('auth/reset_password', $data);
        } else {
            $password = $this->input->post('password');
            
            // Update password and clear reset token
            $this->User_model->update_password($user->id, $password);
            $this->User_model->clear_password_reset_token($user->id);
            
            $this->session->set_flashdata('success', 'Password updated successfully. Please login with your new password.');
            redirect('auth/customer_login');
        }
    }

    // Logout
    public function logout() {
        $user_id = $this->session->userdata('user_id');
        $this->_log_security_event($user_id, $this->input->ip_address(), 'logout', 'User logged out', 'low');
        
        $this->session->sess_destroy();
        redirect(base_url());
    }

    // Check if 2FA is enabled
    private function _is_2fa_enabled() {
        return $this->Settings_model->get_setting('enable_2fa', 'true') === 'true';
    }

    // Log security events
    private function _log_security_event($user_id, $ip_address, $action, $details, $risk_level = 'low') {
        $data = [
            'user_id' => $user_id,
            'ip_address' => $ip_address,
            'user_agent' => $this->input->user_agent(),
            'action' => $action,
            'details' => $details,
            'risk_level' => $risk_level
        ];
        
        $this->db->insert('security_logs', $data);
    }

    // Parse email template
    private function _parse_template($template, $data) {
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }
} 