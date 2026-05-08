<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_settings_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function edit_company_settings_record($data,$id)
    {
        $this->db->where('id',$id);

        if($this->db->update('company_setting',$data))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function get_company_records()
    {
      return $this->db->select('
                                cs.*,
                                c.name as currency_name, 
                                cntry.phonecode as phonecode, 
                                cntry.name as country_name, 
                                stte.name as state_name, 
                                cites.name as city_name, 
                                c.symbol as currency_symbol,
                                stte.state_code as state_code
                            ')
                      ->from('company_setting cs')
                      ->join('currency c','c.id = cs.currency_id','left')
                      ->join('countries cntry','cntry.id = cs.country_id','left')
                      ->join('states stte','stte.id = cs.state_id','left')
                      ->join('cities cites','cites.id = cs.city_id','left')
                      ->where('cs.id',1)
                      ->get()
                      ->row();
      // return $this->db->get_where('company_setting', array("id" => 1))->row();
    }

    public function get_single_record($id)
    {
        return $this->db->select('
                                    cs.*,
                                    c.name as currency_name, 
                                    cntry.name as country_name, 
                                    stte.name as state_name, 
                                    cites.name as city_name, 
                                    c.symbol as currency_symbol
                                ')
                         ->from('company_setting cs')
                         ->join('currency c','c.id = cs.currency_id','left')
                         ->join('countries cntry','cntry.id = cs.country_id','left')
                         ->join('states stte','stte.id = cs.state_id','left')
                         ->join('cities cites','cites.id = cs.city_id','left')
                         ->where('cs.id',$id)
                         ->get()
                         ->row();
    }

		public function update_company_upi_image($upi_address)
		{

      $entered_company_setting = $this->company_settings_model->get_company_records();

			if ($entered_company_setting->upi_address != '') {
				$qrcode_library_path = APPPATH . 'libraries' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
						
				if (file_exists($qrcode_library_path)) {
						include($qrcode_library_path);
		
					
            // Update the UPI intent without specifying amount
            $upi_intent = "upi://pay?pa=" . urlencode($entered_company_setting->upi_address) . "&pn=" . urlencode($entered_company_setting->company_name) . "&cu=INR";


            $data['company_setting'] 	= $this->company_settings_model->get_company_records();

            $cid = $data['company_setting']->cid; 
    
         
		
						// Create the "upi" folder if it doesn't exist
						$folderPath = './assets/images/' . $cid . '/upi/';

						if (!is_dir($folderPath)) {
								mkdir($folderPath, 0755, true);
						}

		
						$name = $entered_company_setting->id . '.png';
						$path = $folderPath . $name;
		
						// Check if the file already exists, then delete it
						if (file_exists($path)) {
								unlink($path);
						}
		
						QRcode::png($upi_intent, $path, QR_ECLEVEL_L, 5); // qrcode in png format
				}
			}
		}
    
}
?>
