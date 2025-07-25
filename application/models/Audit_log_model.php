<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_log_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Add an audit log entry
     * @param int $user_id
     * @param string $role
     * @param string $action
     * @param string $entity_type
     * @param int $entity_id
     * @param string $details
     */
    public function add_log($user_id, $role, $action, $entity_type, $entity_id, $details = null) {
        $data = [
            'user_id' => $user_id,
            'role' => $role,
            'action' => $action,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('audit_logs', $data);
    }
} 