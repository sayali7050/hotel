<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication Controller
 * 
 * Enhanced authentication with security features:
 * - Rate limiting
 * - Secure 2FA
 * - Account lockout
 * - Audit logging
 * - Email verification
 * 
 * @package Hotel Management System
 * @version 2.0
 */
class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(['form_validation', 'session', 'security_lib', 'email']);
        $this->load->helper(['url', 'form', 'notification']);
        
        // Set security headers
        $this->security_lib->set_security_headers();
        
        // Check maintenance mode
        if ($this->security_lib->is_maintenance_mode()) {
            $this->_show_maintenance_page();
        }
    }
    
    /**
     * Admin login with enhanced security
     */
    public function admin_login() {
        // Redirect if already logged in
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'admin') {
            redirect('admin/dashboard');
        }
        
        // Check rate limiting
        if (!$this->security_lib->check_rate_limit('admin_login')) {
            $this->session->set_flashdata('error', 'Too many login attempts. Please try again later.');
            redirect('auth/admin_login');
        }
        
        // Set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules($this->security->get_csrf_token_name(), 'CSRF Token', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['csrf'] = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('auth/admin_login', $data);
        } else {
            $username = $this->security_lib->sanitize_input($this->input->post('username'), ['string', 'no_html']);
            $password = $this->input->post('password');
            
            // Authenticate user
            $auth_result = $this->User_model->authenticate($username, $password, 'username');
            
            if ($auth_result['success']) {
                $user = $auth_result['user'];
                
                // Check if user is admin
                if ($user->role !== 'admin') {
                    $this->security_lib->log_security_event('unauthorized_admin_access_attempt', $user->id, [
                        'attempted_role' => 'admin',
                        'actual_role' => $user->role
                    ]);
                    $this->session->set_flashdata('error', 'Access denied. Admin privileges required.');
                    redirect('auth/admin_login');
                }
                
                // Check if 2FA is enabled
                if ($user->two_fa_enabled) {
                    $this->_initiate_2fa($user);
                } else {
                    $this->_complete_login($user);
                }
            } else {
                $this->session->set_flashdata('error', $auth_result['error']);
                redirect('auth/admin_login');
            }
        }
    }
    
    /**
     * Staff login
     */
    public function staff_login() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'staff') {
            redirect('staff/dashboard');
        }
        
        if (!$this->security_lib->check_rate_limit('staff_login')) {
            $this->session->set_flashdata('error', 'Too many login attempts. Please try again later.');
            redirect('auth/staff_login');
        }
        
        $this->form_validation->set_rules('username', 'Username', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules($this->security->get_csrf_token_name(), 'CSRF Token', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['csrf'] = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('auth/staff_login', $data);
        } else {
            $username = $this->security_lib->sanitize_input($this->input->post('username'), ['string', 'no_html']);
            $password = $this->input->post('password');
            
            $auth_result = $this->User_model->authenticate($username, $password);
            
            if ($auth_result['success']) {
                $user = $auth_result['user'];
                
                if ($user->role !== 'staff') {
                    $this->security_lib->log_security_event('unauthorized_staff_access_attempt', $user->id);
                    $this->session->set_flashdata('error', 'Access denied. Staff privileges required.');
                    redirect('auth/staff_login');
                }
                
                if ($user->two_fa_enabled) {
                    $this->_initiate_2fa($user);
                } else {
                    $this->_complete_login($user);
                }
            } else {
                $this->session->set_flashdata('error', $auth_result['error']);
                redirect('auth/staff_login');
            }
        }
    }
    
    /**
     * Customer login
     */
    public function customer_login() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'customer') {
            redirect('customer/dashboard');
        }
        
        if (!$this->security_lib->check_rate_limit('customer_login')) {
            $this->session->set_flashdata('error', 'Too many login attempts. Please try again later.');
            redirect('auth/customer_login');
        }
        
        $this->form_validation->set_rules('username', 'Username or Email', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules($this->security->get_csrf_token_name(), 'CSRF Token', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['csrf'] = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('auth/customer_login', $data);
        } else {
            $identifier = $this->security_lib->sanitize_input($this->input->post('username'), ['string', 'no_html']);
            $password = $this->input->post('password');
            
            // Determine if identifier is email or username
            $type = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            
            $auth_result = $this->User_model->authenticate($identifier, $password, $type);
            
            if ($auth_result['success']) {
                $user = $auth_result['user'];
                
                if ($user->role !== 'customer') {
                    $this->security_lib->log_security_event('unauthorized_customer_access_attempt', $user->id);
                    $this->session->set_flashdata('error', 'Access denied. Customer account required.');
                    redirect('auth/customer_login');
                }
                
                if ($user->two_fa_enabled) {
                    $this->_initiate_2fa($user);
                } else {
                    $this->_complete_login($user);
                }
            } else {
                // Special handling for email verification
                if (isset($auth_result['user_id'])) {
                    $this->session->set_flashdata('info', $auth_result['error'] . ' <a href="' . base_url('auth/resend_verification/' . $auth_result['user_id']) . '">Resend verification email</a>');
                } else {
                    $this->session->set_flashdata('error', $auth_result['error']);
                }
                redirect('auth/customer_login');
            }
        }
    }
    
    /**
     * Customer registration
     */
    public function customer_register() {
        if ($this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        
        if (!$this->security_lib->check_rate_limit('registration')) {
            $this->session->set_flashdata('error', 'Too many registration attempts. Please try again later.');
            redirect('auth/customer_register');
        }
        
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]|max_length[50]|alpha_numeric_underscore');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[100]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('terms', 'Terms and Conditions', 'required');
        $this->form_validation->set_rules($this->security->get_csrf_token_name(), 'CSRF Token', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['csrf'] = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('auth/customer_register', $data);
        } else {
            try {
                $user_data = [
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'role' => 'customer',
                    'status' => 'active',
                    'email_verified' => 0
                ];
                
                $user_id = $this->User_model->create_user($user_data);
                
                // Send verification email
                $this->_send_verification_email($user_id);
                
                $this->session->set_flashdata('success', 'Registration successful! Please check your email to verify your account.');
                redirect('auth/customer_login');
                
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect('auth/customer_register');
            }
        }
    }
    
    /**
     * 2FA verification
     */
    public function verify_2fa() {
        $pending_user_id = $this->session->userdata('pending_2fa_user_id');
        if (!$pending_user_id) {
            redirect(base_url());
        }
        
        // Check rate limiting for 2FA attempts
        if (!$this->security_lib->check_rate_limit('2fa_verification')) {
            $this->session->set_flashdata('error', 'Too many 2FA attempts. Please try again later.');
            $this->session->unset_userdata('pending_2fa_user_id');
            redirect(base_url());
        }
        
        $this->form_validation->set_rules('code', '2FA Code', 'required|exact_length[6]|numeric');
        $this->form_validation->set_rules($this->security->get_csrf_token_name(), 'CSRF Token', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['csrf'] = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('auth/verify_2fa', $data);
        } else {
            $code = $this->input->post('code');
            $user = $this->User_model->get_user_by_id($pending_user_id);
            
            if ($user && $this->_verify_2fa_code($user, $code)) {
                $this->session->unset_userdata('pending_2fa_user_id');
                $this->_complete_login($user);
            } else {
                $this->security_lib->log_security_event('2fa_failed', $pending_user_id, ['code_attempted' => $code]);
                $this->session->set_flashdata('error', 'Invalid or expired 2FA code. Please try again.');
                redirect('auth/verify_2fa');
            }
        }
    }
    
    /**
     * Email verification
     */
    public function verify_email($token = null) {
        if (!$token) {
            show_404();
        }
        
        if ($this->User_model->verify_email($token)) {
            $this->session->set_flashdata('success', 'Email verified successfully! You can now log in.');
        } else {
            $this->session->set_flashdata('error', 'Invalid or expired verification token.');
        }
        
        redirect('auth/customer_login');
    }
    
    /**
     * Resend verification email
     */
    public function resend_verification($user_id = null) {
        if (!$user_id) {
            show_404();
        }
        
        if (!$this->security_lib->check_rate_limit('verification_resend')) {
            $this->session->set_flashdata('error', 'Too many requests. Please try again later.');
            redirect('auth/customer_login');
        }
        
        $user = $this->User_model->get_user_by_id($user_id);
        if ($user && !$user->email_verified) {
            $this->_send_verification_email($user_id);
            $this->session->set_flashdata('success', 'Verification email sent! Please check your inbox.');
        } else {
            $this->session->set_flashdata('error', 'Unable to send verification email.');
        }
        
        redirect('auth/customer_login');
    }
    
    /**
     * Forgot password
     */
    public function forgot_password() {
        if (!$this->security_lib->check_rate_limit('forgot_password')) {
            $this->session->set_flashdata('error', 'Too many password reset requests. Please try again later.');
            redirect('auth/forgot_password');
        }
        
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules($this->security->get_csrf_token_name(), 'CSRF Token', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['csrf'] = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('auth/forgot_password', $data);
        } else {
            $email = $this->security_lib->sanitize_input($this->input->post('email'), ['email']);
            $token = $this->User_model->generate_password_reset_token($email);
            
            if ($token) {
                $this->_send_password_reset_email($email, $token);
                $this->session->set_flashdata('success', 'Password reset instructions sent to your email.');
            } else {
                // Don't reveal if email exists or not for security
                $this->session->set_flashdata('success', 'If an account with that email exists, password reset instructions have been sent.');
            }
            
            redirect('auth/forgot_password');
        }
    }
    
    /**
     * Reset password
     */
    public function reset_password($token = null) {
        if (!$token) {
            show_404();
        }
        
        $this->form_validation->set_rules('password', 'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules($this->security->get_csrf_token_name(), 'CSRF Token', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['token'] = $token;
            $data['csrf'] = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('auth/reset_password', $data);
        } else {
            try {
                $new_password = $this->input->post('password');
                
                if ($this->User_model->reset_password($token, $new_password)) {
                    $this->session->set_flashdata('success', 'Password reset successfully! You can now log in with your new password.');
                    redirect('auth/customer_login');
                } else {
                    $this->session->set_flashdata('error', 'Invalid or expired reset token.');
                    redirect('auth/forgot_password');
                }
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect('auth/reset_password/' . $token);
            }
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        // Log the logout
        if ($user_id) {
            $this->security_lib->log_security_event('logout', $user_id);
        }
        
        // Destroy session
        $this->session->sess_destroy();
        
        // Redirect based on role
        switch ($role) {
            case 'admin':
                redirect('auth/admin_login');
                break;
            case 'staff':
                redirect('auth/staff_login');
                break;
            default:
                redirect('auth/customer_login');
                break;
        }
    }
    
    /**
     * Private method to initiate 2FA
     */
    private function _initiate_2fa($user) {
        // Generate 2FA code
        $code = $this->security_lib->generate_2fa_code();
        $expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        // Save code to database
        $this->db->where('id', $user->id);
        $this->db->update('users', [
            'last_2fa_code' => $code,
            'last_2fa_expires' => $expires
        ]);
        
        // Send 2FA code via email
        $this->_send_2fa_email($user, $code);
        
        // Store user ID in session for 2FA verification
        $this->session->set_userdata('pending_2fa_user_id', $user->id);
        
        $this->session->set_flashdata('info', 'A 2FA code has been sent to your email. Please enter it below.');
        redirect('auth/verify_2fa');
    }
    
    /**
     * Private method to verify 2FA code
     */
    private function _verify_2fa_code($user, $code) {
        if ($user->last_2fa_code === $code && $user->last_2fa_expires >= date('Y-m-d H:i:s')) {
            // Clear 2FA code after successful verification
            $this->db->where('id', $user->id);
            $this->db->update('users', [
                'last_2fa_code' => null,
                'last_2fa_expires' => null
            ]);
            
            $this->security_lib->log_security_event('2fa_verified', $user->id);
            return true;
        }
        
        return false;
    }
    
    /**
     * Private method to complete login
     */
    private function _complete_login($user) {
        // Regenerate session ID for security
        $this->session->sess_regenerate(TRUE);
        
        // Set session data
        $session_data = [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'logged_in' => TRUE,
            'login_time' => time()
        ];
        
        $this->session->set_userdata($session_data);
        
        // Check for intended redirect URL
        $redirect_url = $this->session->userdata('redirect_after_login');
        if ($redirect_url) {
            $this->session->unset_userdata('redirect_after_login');
            redirect($redirect_url);
        }
        
        // Default redirects based on role
        switch ($user->role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'staff':
                redirect('staff/dashboard');
                break;
            case 'customer':
                redirect('customer/dashboard');
                break;
            default:
                redirect(base_url());
                break;
        }
    }
    
    /**
     * Private method to send verification email
     */
    private function _send_verification_email($user_id) {
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user || !$user->email_verification_token) {
            return false;
        }
        
        $verification_link = base_url('auth/verify_email/' . $user->email_verification_token);
        
        $subject = 'Verify Your Email Address - Hotel Management System';
        $message = "Hello {$user->first_name} {$user->last_name},\n\n";
        $message .= "Thank you for registering with our Hotel Management System.\n\n";
        $message .= "Please click the following link to verify your email address:\n";
        $message .= $verification_link . "\n\n";
        $message .= "If you did not create this account, please ignore this email.\n\n";
        $message .= "Best regards,\nHotel Management Team";
        
        return send_booking_notification($user->email, $subject, $message, null, ['user_id' => $user->id]);
    }
    
    /**
     * Private method to send 2FA email
     */
    private function _send_2fa_email($user, $code) {
        $subject = 'Your 2FA Authentication Code - Hotel Management System';
        $message = "Hello {$user->first_name},\n\n";
        $message .= "Your 2FA authentication code is: {$code}\n\n";
        $message .= "This code will expire in 5 minutes.\n\n";
        $message .= "If you did not attempt to log in, please contact our support team immediately.\n\n";
        $message .= "Best regards,\nHotel Management Team";
        
        return send_booking_notification($user->email, $subject, $message, null, ['user_id' => $user->id]);
    }
    
    /**
     * Private method to send password reset email
     */
    private function _send_password_reset_email($email, $token) {
        $user = $this->User_model->get_user_by_identifier($email);
        if (!$user) {
            return false;
        }
        
        $reset_link = base_url('auth/reset_password/' . $token);
        
        $subject = 'Password Reset Request - Hotel Management System';
        $message = "Hello {$user->first_name} {$user->last_name},\n\n";
        $message .= "You have requested a password reset for your account.\n\n";
        $message .= "Please click the following link to reset your password:\n";
        $message .= $reset_link . "\n\n";
        $message .= "This link will expire in 1 hour.\n\n";
        $message .= "If you did not request this password reset, please ignore this email.\n\n";
        $message .= "Best regards,\nHotel Management Team";
        
        return send_booking_notification($user->email, $subject, $message, null, ['user_id' => $user->id]);
    }
    
    /**
     * Private method to show maintenance page
     */
    private function _show_maintenance_page() {
        $this->load->view('maintenance');
        exit;
    }
} 