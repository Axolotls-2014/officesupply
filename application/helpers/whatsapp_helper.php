<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('send_whatsapp_message')) {
	function send_whatsapp_message($number, $message, $file = '') {
			// Get CI instance
			$CI =& get_instance();

			// Load the database library
			$CI->load->database();

			// Load custom config file
			$CI->config->load('custom_config');

			// Load company setting model
			$CI->load->model('Company_settings_model');

			$access_token = $CI->company_settings_model->get_company_records()->access_token;
			$instance_id = $CI->company_settings_model->get_company_records()->instance_id;

			// Retrieve the WhatsApp API URL from custom config
			$url = $CI->config->item('whatsapp_api_url');

			// log_message('debug', 'WhatsApp API URL: ' . $url); // Log the URL to check it


			if ($access_token == '' || $access_token == '') {
					return "Error: Unable to retrieve instance ID and access token.";
			}

			$payload = '';

			if($file == '')
			{
				$payload = array(
					'number' => $number,
					'type' => 'text',
					'message' => $message,
					'instance_id' => $instance_id,
					'access_token' => $access_token
				);
			}
			else
			{
				$payload = array(
					'number' => $number,
					'type' => 'text',
					'message' => $message,
					'media_url' => $file,
					'instance_id' => $instance_id,
					'access_token' => $access_token
				);
			}
		

			// Initialize cURL session
			$ch = curl_init($url);

			// Set cURL options
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

			// Check if running on localhost and adjust SSL settings
			if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
					// Disable SSL verification on localhost
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Turn off the server and peer verification
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 0 to not check the names, 2 to check the existence and match of both name and certificate
			}


			// Execute cURL session
			$response = curl_exec($ch);

			// Check for errors
			if (curl_errno($ch)) {
					$error_msg = curl_error($ch);
			}

			// Close cURL session
			curl_close($ch);

			if (isset($error_msg)) {
					return $error_msg;
			}

			return $response;
	}
}


if (!function_exists('reconnect_whatsapp')) {
	function reconnect_whatsapp() {
			// Get CI instance
			$CI =& get_instance();

			// Load the database library
			$CI->load->database();

			// Load custom config file
			$CI->config->load('custom_config');

			// Load company setting model
			$CI->load->model('Company_settings_model');

			$access_token = $CI->company_settings_model->get_company_records()->access_token;
			$instance_id = $CI->company_settings_model->get_company_records()->instance_id;

			// Retrieve the WhatsApp API URL from custom config
			$url = $CI->config->item('whatsapp_api_reconnect_url');

			// log_message('debug', 'WhatsApp API URL: ' . $url); // Log the URL to check it

			if ($access_token == '' || $access_token == '') {
					return "Error: Unable to retrieve instance ID and access token.";
			}

			$url = 'https://va.gladminds.one/api/reconnect';
        
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
					'instance_id' => $instance_id,
					'access_token' => $access_token
			)));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json'
			));
			
			$result = curl_exec($ch);
			if (curl_errno($ch)) {
					$result = curl_error($ch);
			}
			curl_close($ch);

			return $result;
	}
}
