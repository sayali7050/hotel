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
        
        // Load email configuration
        $CI->load->config('email');
        $CI->load->library('email');
        
        // Get hotel configuration
        $CI->load->model('Settings_model');
        $hotel_config = $CI->Settings_model->get_hotel_config();
        
        // Set from address
        $CI->email->from($hotel_config['email'], $hotel_config['name']);
        $CI->email->to($to);
        $CI->email->subject($subject);
        
        // Prepare email body
        if ($template) {
            $body = render_email_template($template, $vars);
        } else {
            $body = $message;
        }
        
        // Convert plain text to HTML if needed
        if (strpos($body, '<html>') === false && strpos($body, '<body>') === false) {
            $body = convert_text_to_html($body);
        }
        
        $CI->email->message($body);
        
        // Send email
        $sent = $CI->email->send();
        
        // Log communication
        $user_id = isset($vars['user_id']) ? $vars['user_id'] : null;
        $status = $sent ? 'sent' : 'failed';
        
        $log_data = [
            'user_id' => $user_id,
            'email' => $to,
            'type' => 'notification',
            'subject' => $subject,
            'body' => $body,
            'status' => $status,
            'sent_at' => $sent ? date('Y-m-d H:i:s') : null,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $CI->db->insert('communication_logs', $log_data);
        
        // Log any email errors
        if (!$sent) {
            log_message('error', 'Email sending failed to: ' . $to . ' | Error: ' . $CI->email->print_debugger());
        }
        
        return $sent;
    }
}

if (!function_exists('convert_text_to_html')) {
    function convert_text_to_html($text) {
        $CI =& get_instance();
        $CI->load->model('Settings_model');
        $hotel_config = $CI->Settings_model->get_hotel_config();
        
        // Convert line breaks to HTML
        $text = nl2br($text);
        
        // Wrap in basic HTML structure
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email from ' . htmlspecialchars($hotel_config['name']) . '</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-bottom: 3px solid #007bff; }
        .content { padding: 20px; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>' . htmlspecialchars($hotel_config['name']) . '</h2>
    </div>
    <div class="content">
        ' . $text . '
    </div>
    <div class="footer">
        <p>' . htmlspecialchars($hotel_config['name']) . '</p>
        <p>Email: ' . htmlspecialchars($hotel_config['email']) . ' | Phone: ' . htmlspecialchars($hotel_config['phone']) . '</p>
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>';
        
        return $html;
    }
}

if (!function_exists('send_bulk_email')) {
    function send_bulk_email($recipients, $subject, $message, $template = null, $vars = []) {
        $CI =& get_instance();
        $CI->load->library('email');
        $CI->load->config('email');
        
        $sent_count = 0;
        $failed_count = 0;
        
        foreach ($recipients as $recipient) {
            $individual_vars = array_merge($vars, ['recipient' => $recipient]);
            
            if (send_booking_notification($recipient['email'], $subject, $message, $template, $individual_vars)) {
                $sent_count++;
            } else {
                $failed_count++;
            }
            
            // Small delay to prevent overwhelming the SMTP server
            usleep(100000); // 0.1 second delay
        }
        
        return [
            'sent' => $sent_count,
            'failed' => $failed_count,
            'total' => count($recipients)
        ];
    }
}

if (!function_exists('queue_email')) {
    function queue_email($to, $subject, $message, $template = null, $vars = [], $send_at = null) {
        $CI =& get_instance();
        
        // For now, we'll send immediately, but this could be extended to use a proper queue system
        if ($send_at && strtotime($send_at) > time()) {
            // In a real implementation, you'd store this in a queue table and process it later
            log_message('info', 'Email queued for later delivery to: ' . $to);
            return true;
        } else {
            return send_booking_notification($to, $subject, $message, $template, $vars);
        }
    }
}

if (!function_exists('send_template_email')) {
    function send_template_email($template_name, $to, $vars = []) {
        $CI =& get_instance();
        $CI->load->model('Email_template_model');
        
        $template = $CI->Email_template_model->get_template($template_name);
        
        if (!$template) {
            log_message('error', 'Email template not found: ' . $template_name);
            return false;
        }
        
        $parsed = $CI->Email_template_model->parse_template($template, $vars);
        
        return send_booking_notification($to, $parsed['subject'], $parsed['body'], null, $vars);
    }
} 