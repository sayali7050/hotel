<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_template_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get template by name
    public function get_template($name) {
        $this->db->where('name', $name);
        $this->db->where('active', 1);
        return $this->db->get('email_templates')->row();
    }
    
    // Get all templates
    public function get_all_templates() {
        return $this->db->order_by('name')->get('email_templates')->result();
    }
    
    // Create new template
    public function create_template($data) {
        $this->db->insert('email_templates', $data);
        return $this->db->insert_id();
    }
    
    // Update template
    public function update_template($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('email_templates', $data);
    }
    
    // Delete template
    public function delete_template($id) {
        return $this->db->delete('email_templates', ['id' => $id]);
    }
    
    // Get template by ID
    public function get_template_by_id($id) {
        return $this->db->get_where('email_templates', ['id' => $id])->row();
    }
    
    // Parse template with variables
    public function parse_template($template, $variables) {
        $subject = $template->subject;
        $body = $template->body;
        
        foreach ($variables as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }
        
        return [
            'subject' => $subject,
            'body' => $body
        ];
    }
    
    // Get available variables for a template
    public function get_template_variables($name) {
        $template = $this->get_template($name);
        if ($template && $template->variables) {
            return json_decode($template->variables, true);
        }
        return [];
    }
    
    // Activate/deactivate template
    public function toggle_template_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('email_templates', ['active' => $status]);
    }
}