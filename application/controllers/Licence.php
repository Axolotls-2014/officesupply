<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Licence extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}

	public function verify()
	{
			//log_message('debug', "verify() function started.");
			
			$curl = curl_init();
			$isLocalhost = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
			
			$purchaseCode = $this->input->post('purchaseCode');
			$companyName = $this->input->post('companyName');

			//log_message('debug', "Received data - Purchase Code: $purchaseCode, Company Name: $companyName");

			// Set up the data to be sent in the POST request
			$postData = [
					'purchaseCode' => $purchaseCode,
					'companyName' => $companyName, // Include the company name in the POST data
			];

			// Change the URL to your server's verification endpoint
			$serverUrl = "https://api.zivaansolutions.com/verify"; // Replace with your server's API endpoint
			//log_message('debug', "Server URL set to: $serverUrl");

			curl_setopt_array($curl, array(
					CURLOPT_URL => $serverUrl,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST", // Use POST to send data
					CURLOPT_POSTFIELDS => http_build_query($postData), // Send the data as a POST request
					CURLOPT_HTTPHEADER => array(
							"Content-Type: application/x-www-form-urlencoded", // Ensure the content type matches the server expectation
							"User-Agent: curl"
					),
					CURLOPT_SSL_VERIFYPEER => !$isLocalhost, // SSL verification for localhost
			));

			//log_message('debug', "cURL options set. Executing cURL request...");
			
			$response = curl_exec($curl);
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
					//log_message('error', "CURL error: $err");
					echo json_encode(['success' => false, 'message' => $err]);
			}

			//log_message('debug', "cURL request completed with HTTP code $httpCode. Response: $response");

			// Decode the JSON response from the server
			$result = json_decode($response, true);

			if (isset($result['success']) && $result['success']) {
					//log_message('debug', "Success response received from server.");

					if (isset($result['fileData']) && is_array($result['fileData'])) {
							//log_message('debug', "fileData is present and valid.");

							// Define the file path
							$filePath = 'assets/js/vdata.json';
							
							// Check if the script has write permissions for the assets/js directory
							if (is_writable(dirname($filePath))) {
									//log_message('debug', "Directory is writable: " . dirname($filePath));

									// Convert array to JSON format
									$jsonData = json_encode($result['fileData'], JSON_PRETTY_PRINT);
									/* start update js file */
										$sourcePath = FCPATH . 'assets/js/envato-verify1.js';
										$destinationPath = FCPATH . 'assets/js/envato-verify.js';

										$content = str_replace('{{companyName}}', $result['fileData']['company_name'] , $content);

										file_put_contents($destinationPath, $content);
									/* end update js file */

									/* start update company name */
										$this->company_settings_model->edit_company_settings_record(array('company_name'=>$result['fileData']['company_name']),1);
									/* end update company name */

									
									if (file_put_contents($filePath, $jsonData)) {
											//log_message('debug', "Data successfully saved to $filePath");
											echo json_encode(['success' => true, 'message' => "Purchase code is activated."]);
									} else {
											//log_message('error', "Failed to save data to $filePath");
											echo json_encode(['success' => true, 'message' => "There is an issue while activating the licence. Please contact at zivaansolutions@gmail.com immediately."]);
									}
							} else {
									$directory = dirname($filePath);
									//log_message('error', "Directory is not writable: $directory");
									echo json_encode(['success' => false, 'message' => "Server does not have write permissions for the directory: " . $directory]);
							}
					} else {
							//log_message('error', "fileData is not present or invalid.");
							echo json_encode(['success' => false, 'message' => "Invalid response received from server."]);
					}
			} else {
					//log_message('error', "Failed response from server. Message: " . $result['message']);
					echo json_encode(['success' => false, 'message' => $result['message']]);
			}

			//log_message('debug', "verify() function ended.");
	}
}
