<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary models, libraries, etc.        
    }

    // The common function that will be called by your cron job
    public function run() {
			$this->send_whatsapp_messages();
    }

    private function send_whatsapp_messages() {
			// Retrieve all pending messages
			$this->db->where('wm_status', 'pending');
			$messages = $this->db->get('whatsapp_messages')->result();
	
			foreach ($messages as $message) {
					// Send each message via WhatsApp
					$response = send_whatsapp_message($message->wm_to, $message->wm_message, $message->wm_file);
					
					// Debugging output to console or log file
					log_message('info', 'Response for message ID ' . $message->wm_id . ': ' . $response);
					
					// Decode response to check status
					$response_data = json_decode($response, true);
	
					// Check if response_data is an array and the status is 'success'
					if (is_array($response_data) && isset($response_data['status']) && $response_data['status'] == 'success') {
							$status = 'sent';
					} else {
							// If the response indicates failure or is not as expected, log it and mark as pending
							log_message('error', 'Failed or unexpected response for message ID ' . $message->wm_id . ': ' . $response);
							$status = 'pending';
					}

					if($status == "pending")	
						reconnect_whatsapp();
	
					// Update message status in the database
					$this->db->where('wm_id', $message->wm_id);
					$this->db->update('whatsapp_messages', ['wm_status' => $status]);
			}
		}
	
}
