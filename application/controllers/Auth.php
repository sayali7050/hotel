<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }
    
    // Admin login page
    public function admin_login() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'admin') {
            redirect('admin/dashboard');
        }
        
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/admin_login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            $user = $this->User_model->login($username, $password);
            
            if ($user && $user->role == 'admin') {
                $this->session->set_userdata([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'logged_in' => TRUE
                ]);
                
                redirect('admin/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password');
                redirect('auth/admin_login');
            }
        }
    }
    
    // Staff login page
    public function staff_login() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'staff') {
            redirect('staff/dashboard');
        }
        
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/staff_login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            $user = $this->User_model->login($username, $password);
            
            if ($user && $user->role == 'staff') {
                $this->session->set_userdata([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'logged_in' => TRUE
                ]);
                
                redirect('staff/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password');
                redirect('auth/staff_login');
            }
        }
    }
    
    // Customer login page
    public function customer_login() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'customer') {
            redirect('customer/dashboard');
        }
        
        $this->form_validation->set_rules('username', 'Username or Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/customer_login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            $user = $this->User_model->login($username, $password);
            
            if ($user && $user->role == 'customer') {
                $this->session->set_userdata([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'logged_in' => TRUE
                ]);
                
                redirect('customer/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid username/email or password');
                redirect('auth/customer_login');
            }
        }
    }
    
    // Customer registration
    public function customer_register() {
        if ($this->session->userdata('logged_in')) {
            redirect('customer/dashboard');
        }
        
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/customer_register');
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            ];
            
            if ($this->User_model->register_user($data)) {
                $this->session->set_flashdata('success', 'Registration successful! Please login.');
                redirect('auth/customer_login');
            } else {
                $this->session->set_flashdata('error', 'Registration failed. Please try again.');
                redirect('auth/customer_register');
            }
        }
    }
    
    // Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('welcome');
    }
} 