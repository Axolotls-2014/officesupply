<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class EnvatoVerify {

    public function checkLogin() {
        $CI =& get_instance();
        //log_message('debug', 'Entering checkLogin function in EnvatoVerify.');

        // Log the state of the user's logged-in status
        //log_message('debug', 'User logged_in status: ' . $CI->session->userdata('logged_in'));

        if ($CI->session->userdata('logged_in')) {
            if ($this->shouldIncludeJs()) {
                $this->createOrUpdateEnvatoVerifyJs();  // Generate the JavaScript file if conditions are met
            }
        }

        //log_message('debug', 'Exiting checkLogin function in EnvatoVerify.');
    }

    private function shouldIncludeJs() {
        $CI =& get_instance();
        //log_message('debug', 'Entering shouldIncludeJs function in EnvatoVerify.');

        $filePath = FCPATH . 'assets/js/vdata.json';

        if (!file_exists($filePath)) {
            //log_message('debug', 'v_data.json does not exist, returning true.');
            return true; // Include JS if JSON file does not exist
        }

        $data = json_decode(file_get_contents($filePath), true);
        $jsonCompanyName = $data['company_name'] ?? '';
        $jsonPurchaseCode = $data['purchase_code'] ?? '';
        $jsonHash = $data['hash'] ?? '';

       
        $dbCompanyName = $this->getDbCompanyName();

				//log_message('debug', 'Db Company name : '.$dbCompanyName.' json company name '.$jsonCompanyName);

        if ($jsonCompanyName === $dbCompanyName && $this->isValidPurchaseCode($jsonPurchaseCode, $jsonCompanyName, $dbCompanyName, $jsonHash)) {
            //log_message('debug', 'Exiting shouldIncludeJs function in EnvatoVerify with false.');
            return false; // Do not include JS if company names match and the purchase code is valid
        }

        //log_message('debug', 'Exiting shouldIncludeJs function in EnvatoVerify with true.');
        return true; // Include JS if company names do not match or purchase code is invalid
    }

    private function isValidPurchaseCode($purchaseCode,$jsonCompanyName, $dbCompanyName, $hash) {
        //log_message('debug', 'Entering isValidPurchaseCode function in EnvatoVerify.');
        $isValid = $this->verifyPurchaseCode($purchaseCode, $jsonCompanyName, $dbCompanyName, $hash);
        //log_message('debug', 'Exiting isValidPurchaseCode function in EnvatoVerify with ' . ($isValid ? 'true' : 'false'));
        return $isValid;
    }

		private function verifyPurchaseCode($purchaseCode, $jsonCompanyName, $dbCompanyName, $hash) {
		    //log_message('debug', 'Entering verifyPurchaseCode function in EnvatoVerify.');

		    // Hashes of excluded domains
		    $excludedHashes = [
		        '2f2347ae5df530935a7d206839e69dcf9d29ef76e314a495302b2d1bb1c178ce', 
		        '7c8c5ae82719f91e9e372d3b5e2df0b70bdf6b7ef716e4cfa15e3a0346cbdf4f', 
		        'd1d6d0c2a9f97b10d5a582a6d7c3d5b7c1adbf5ed5e96d4c59f707ad1b1ed524', 
		        '1a755f4cbaf0bb78faaff1c9c0a342ed5d9fdd0f6dbb3b0283ff1ddfae3de497'  
		    ];
		    $currentDomainHash = hash('sha256', $_SERVER['HTTP_HOST']);

		    if (in_array($currentDomainHash, $excludedHashes)) {
		        //log_message('debug', 'Domain ' . $_SERVER['HTTP_HOST'] . ' is excluded from verification.');
		        return true;
		    }

		    $dbHash = hash('sha256', $dbCompanyName . $purchaseCode);
				$jsonHash = hash('sha256', $jsonCompanyName . $purchaseCode);
				

		    //log_message('error', 'hash comparison -  db hash:' . $dbHash . ' json hash: ' . $jsonHash);
				//log_message('error', 'Company  ' . $companyName . ' purchase code: ' . $purchaseCode);

				$this->createOrUpdateEnvatoVerifyJs();

		    if ($dbHash != $jsonHash) {
		        return false;
		    } else {
		        return true;
		    }
		}

    private function createOrUpdateEnvatoVerifyJs() {
        //log_message('debug', 'Entering createOrUpdateEnvatoVerifyJs function in EnvatoVerify.');
        $sourcePath = FCPATH . 'assets/js/envato-verify1.js';
        $destinationPath = FCPATH . 'assets/js/envato-verify.js';
        $jsonPath = FCPATH . 'assets/js/v_data.json';   


				$dbCompanyName = $this->getDbCompanyName();
				$companyName = '';     
				$purchaseCode = '';
				$hash = '';

        // Check if the JSON file exists and read it
        if (file_exists($jsonPath)) {
            $jsonData 		= json_decode(file_get_contents($jsonPath), true);
            $companyName 	= $jsonData['company_name'] ?? $companyName;
            $purchaseCode = $jsonData['purchase_code'] ?? $purchaseCode;
						$hash 				= $jsonData['hash'] ?? $hash;
        }

        // Read the source file content
        $content = file_get_contents($sourcePath);
        if ($content === false) {
            //log_message('error', "Failed to read JavaScript content from {$sourcePath}");
            return;
        }

        // Replace placeholders with actual values
        $content = str_replace('{{companyName}}', $dbCompanyName , $content);
				
        // $content = str_replace('{{purchaseCode}}', $purchaseCode, $content);

        // Write the modified content to the destination file
        if (file_put_contents($destinationPath, $content) === false) {
            //log_message('error', "Failed to write JavaScript content to {$destinationPath}");
        } else {
            //log_message('debug', "Successfully copied JavaScript content from {$sourcePath} to {$destinationPath} with replacements");
        }

        //log_message('debug', 'Exiting createOrUpdateEnvatoVerifyJs function in EnvatoVerify.');
    }

		private function getDbCompanyName()
		{
			$CI =& get_instance();

			$CI->load->database();
			$query = $CI->db->get_where('company_setting', array('id' => 1));
			$company = $query->row();

			return ($company ? $company->company_name : '');
		}
}
?>
