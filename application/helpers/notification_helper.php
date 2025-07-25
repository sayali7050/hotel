<?php
if (!function_exists('render_email_template')) {
    function render_email_template($view, $vars = []) {
        $CI =& get_instance();
        return $CI->load->view($view, $vars, true);
    }
}

if (!function_exists('send_booking_notification')) {
    function send_booking_notification($to, $subject, $message, $template = null, $vars = []) {
        $CI =& get_instance();
        $CI->load->library('email');
        $CI->email->from('yourname@gmail.com', 'Your Hotel Name'); // Update with your hotel name
        $CI->email->to($to);
        $CI->email->subject($subject);
        if ($template) {
            $body = render_email_template($template, $vars);
            $CI->email->message($body);
        } else {
            $body = $message;
            $CI->email->message($body);
        }
        $CI->email->send();
        // Log communication
        $user_id = isset($vars['user_id']) ? $vars['user_id'] : null;
        $CI->db->insert('communication_logs', [
            'user_id' => $user_id,
            'email' => $to,
            'type' => 'booking_notification',
            'subject' => $subject,
            'body' => $body,
            'sent_at' => date('Y-m-d H:i:s')
        ]);
    }
} 