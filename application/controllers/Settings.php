<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	public function index()
	{
		if(!$this->permission_model->has_permission('edit_company_setting'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$old_company_setting = $this->company_settings_model->get_company_records();

				$this->form_validation->set_rules('company_name','Company name','required');		
				$this->form_validation->set_rules('email','Email','required');
				$this->form_validation->set_rules('mobile','Mobile','required');
				$this->form_validation->set_rules('country_id','Country','required');
				$this->form_validation->set_rules('state_id','State','required');
				$this->form_validation->set_rules('city_id','City','required');
				$this->form_validation->set_rules('pincode','Pincode','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['countries'] 			= $this->utility_model->get_countries();
					$data['company_setting'] 	= $this->company_settings_model->get_company_records();

					if($data['company_setting']->country_id != '')
						$data['states'] 		= $this->utility_model->get_states($data['company_setting']->country_id);

					if($data['company_setting']->state_id != '')
						$data['cities'] 		= $this->utility_model->get_cities($data['company_setting']->state_id);

					$this->load->view('settings/company_setting',$data);
				}
				else
				{
					$company_name  					=  $this->input->post('company_name');
					$gem_seller_id  				=  $this->input->post('gem_seller_id');
					$gstin 	 								=  $this->input->post('gstin');
					$gst_registration_type 	=  $this->input->post('gst_registration_type');
					$email  								=  $this->input->post('email');
					$mobile  								=  $this->input->post('mobile');
					
					$address_line1  				=  $this->input->post('address_line1');
					$address_line2  				=  $this->input->post('address_line2');
					$country_id  						=  $this->input->post('country_id');
					$state_id  							=  $this->input->post('state_id');
					$city_id 								=  $this->input->post('city_id');
					$pincode 	 							=  $this->input->post('pincode');
					$bank_detail  					=  nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition		=  nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
					$currency_id 						=  $this->input->post('currency_id');

					$access_token 					= $this->input->post('access_token');
          $upi_address 						= $this->input->post('upi_address');
          $upi_status 						= $this->input->post('upi_status');
          $qr_type 								= $this->input->post('qr_type');
					$show_qr_code_in_proforma = $this->input->post('show_qr_code_in_proforma');

					

					$seperator 									= $this->input->post('seperator');
					$sale_prefix 								= $this->input->post('sale_prefix');
					$purchase_prefix 						= $this->input->post('purchase_prefix');
					$sale_return_prefix 				= $this->input->post('sale_return_prefix');
					$purchase_return_prefix 		= $this->input->post('purchase_return_prefix');
					$proforma_invoice_prefix 		= $this->input->post('proforma_invoice_prefix');
					$quotation_prefix 					= $this->input->post('quotation_prefix');
          $credit_debit_note_prefix 	= $this->input->post('credit_debit_note_prefix');
					$purchase_order_prefix 			= $this->input->post('purchase_order_prefix');
          $employee_prefix 								= $this->input->post('employee_prefix');
          $department_prefix 								= $this->input->post('department_prefix');
          $position_prefix 								= $this->input->post('position_prefix');

          $bank_payment_prefix 			= $this->input->post('bank_payment_prefix');
          $cash_payment_prefix 			= $this->input->post('cash_payment_prefix');
          $bank_receipt_prefix 			= $this->input->post('bank_receipt_prefix');
          $cash_receipt_prefix 			= $this->input->post('cash_receipt_prefix');
          $contra_prefix 			      = $this->input->post('contra_prefix');
          $scrap_issue_prefix 			= $this->input->post('scrap_issue_prefix');
          $scrap_entry_prefix 			= $this->input->post('scrap_entry_prefix');
          $scrap_receive_prefix 		= $this->input->post('scrap_receive_prefix');

					$sale_date_format 							= $this->input->post('sale_date_format');
					$purchase_date_format 					= $this->input->post('purchase_date_format');
					$sale_return_date_format 				= $this->input->post('sale_return_date_format');
					$purchase_return_date_format 		= $this->input->post('purchase_return_date_format');
					$proforma_invoice_date_format 		= $this->input->post('proforma_invoice_date_format');
					$quotation_date_format 					= $this->input->post('quotation_date_format');
          $credit_debit_note_date_format 	= $this->input->post('credit_debit_note_date_format');
					$purchase_order_date_format 		= $this->input->post('purchase_order_date_format');

          $employee_date_format 							= $this->input->post('employee_date_format');
          $department_date_format 							= $this->input->post('department_date_format');
          $position_date_format 							= $this->input->post('position_date_format');

          $bank_payment_date_format 		= $this->input->post('bank_payment_date_format');
          $cash_payment_date_format 		= $this->input->post('cash_payment_date_format');
          $bank_receipt_date_format 		= $this->input->post('bank_receipt_date_format');
          $cash_receipt_date_format 		= $this->input->post('cash_receipt_date_format');
          $contra_date_format 		      = $this->input->post('contra_date_format');
          $scrap_issue_date_format 		  = $this->input->post('scrap_issue_date_format');
          $scrap_entry_date_format 		  = $this->input->post('scrap_entry_date_format');
          $scrap_receive_date_format 		= $this->input->post('scrap_receive_date_format');

					$sale_sequence 								= $this->input->post('sale_sequence');
					$purchase_sequence 						= $this->input->post('purchase_sequence');
					$sale_return_sequence 				= $this->input->post('sale_return_sequence');
					$purchase_return_sequence 		= $this->input->post('purchase_return_sequence');
					$proforma_invoice_sequence 		= $this->input->post('proforma_invoice_sequence');
					$quotation_sequence 					= $this->input->post('quotation_sequence');
          $credit_debit_note_sequence 	= $this->input->post('credit_debit_note_sequence');
					$purchase_order_sequence 			= $this->input->post('purchase_order_sequence');

          $employee_sequence 								= $this->input->post('employee_sequence');
          $department_sequence 								= $this->input->post('department_sequence');
          $position_sequence 								= $this->input->post('position_sequence');

          $bank_payment_sequence 			= $this->input->post('bank_payment_sequence');
          $cash_payment_sequence 			= $this->input->post('cash_payment_sequence');
          $bank_receipt_sequence 			= $this->input->post('bank_receipt_sequence');
          $cash_receipt_sequence 			= $this->input->post('cash_receipt_sequence');
          $contra_sequence 			= $this->input->post('contra_sequence');
          $scrap_issue_sequence 			= $this->input->post('scrap_issue_sequence');
          $scrap_entry_sequence 			= $this->input->post('scrap_entry_sequence');
          $scrap_receive_sequence 			= $this->input->post('scrap_receive_sequence');

					$payment_duration_for_sale 					= $this->input->post('payment_duration_for_sale');
					$payment_duration_for_purchase 			= $this->input->post('payment_duration_for_purchase');

          $instance_id 			= $this->input->post('instance_id');
          $access_token 			= $this->input->post('access_token');

          $whatsapp_no 	= 	$this->input->post('whatsapp_no');
          $whatsapp_country_code 	= 	$this->input->post('whatsapp_country_code');
					

					$data	=	array(
												"company_name"					=>	$company_name,
												"gem_seller_id"					=>	$gem_seller_id,
												"gstin"									=>	$gstin,
												"gst_registration_type"	=>	$gst_registration_type,
												"email"									=>	$email,
												"mobile"								=>	$mobile,
												"address_line1"					=>	$address_line1,
												"address_line2"					=>	$address_line2,
												"country_id"						=>	$country_id,
												"state_id"							=>	$state_id,
												"city_id"								=>	$city_id,
												"pincode"								=>	$pincode,
												"bank_detail"						=>  $bank_detail,
												"terms_and_condition"   =>  $terms_and_condition,
												"currency_id"						=>	$currency_id,
												"upi_address" 		      =>	$upi_address,
                        "upi_status" 		      	=>	$upi_status,
                        "qr_type" 		      		=>	$qr_type,

												"show_qr_code_in_proforma" 	=>	$show_qr_code_in_proforma,

												"seperator"									=>	$seperator,
												"sale_prefix"								=>	$sale_prefix,
												"purchase_prefix"						=>	$purchase_prefix,
												"sale_return_prefix"				=>	$sale_return_prefix,
												"purchase_return_prefix"		=>	$purchase_return_prefix,
												"proforma_invoice_prefix"		=>	$proforma_invoice_prefix,
												"quotation_prefix"					=>	$quotation_prefix,
                        "credit_debit_note_prefix"	=>	$credit_debit_note_prefix,
												"purchase_order_prefix"			=>	$purchase_order_prefix,

                        "employee_prefix"								=>	$employee_prefix,
                        "department_prefix"								=>	$department_prefix,
                        "position_prefix"								=>	$position_prefix,
                        

                        "bank_payment_prefix"			=>	$bank_payment_prefix,
                        "cash_payment_prefix"			=>	$cash_payment_prefix,
                        "bank_receipt_prefix"			=>	$bank_receipt_prefix,
                        "cash_receipt_prefix"			=>	$cash_receipt_prefix,
                        "contra_prefix"			      =>	$contra_prefix,
                        "scrap_issue_prefix"			=>	$scrap_issue_prefix,
                        "scrap_entry_prefix"			=>	$scrap_entry_prefix,
                        "scrap_receive_prefix"		=>	$scrap_receive_prefix,

												"sale_date_format"							=>	$sale_date_format,
												"purchase_date_format"					=>	$purchase_date_format,
												"sale_return_date_format"				=>	$sale_return_date_format,
												"purchase_return_date_format"		=>	$purchase_return_date_format,
												"proforma_invoice_date_format"		=>	$proforma_invoice_date_format,
												"quotation_date_format"					=>	$quotation_date_format,
                        "credit_debit_note_date_format"	=>	$credit_debit_note_date_format,
												"purchase_order_date_format"		=>	$purchase_order_date_format,

                        "employee_date_format"							=>	$employee_date_format,
                        "department_date_format"							=>	$department_date_format,
                        "position_date_format"							=>	$position_date_format,

                        "bank_payment_date_format"		=>	$bank_payment_date_format,
                        "cash_payment_date_format"		=>	$cash_payment_date_format,
                        "bank_receipt_date_format"		=>	$bank_receipt_date_format,
                        "cash_receipt_date_format"		=>	$cash_receipt_date_format,
                        "contra_date_format"		=>	$contra_date_format,
                        "scrap_issue_date_format"		=>	$scrap_issue_date_format,
                        "scrap_entry_date_format"		=>	$scrap_entry_date_format,
                        "scrap_receive_date_format"		=>	$scrap_receive_date_format,
                        
												"sale_sequence"								=>	$sale_sequence,
												"purchase_sequence"						=>	$purchase_sequence,
												"sale_return_sequence"				=>	$sale_return_sequence,
												"purchase_return_sequence"		=>	$purchase_return_sequence,
												"proforma_invoice_sequence"		=>	$proforma_invoice_sequence,
												"quotation_sequence"					=>	$quotation_sequence,
                        "credit_debit_note_sequence"	=>	$credit_debit_note_sequence,
												"purchase_order_sequence"			=>	$purchase_order_sequence,

                        "employee_sequence"								=>	$employee_sequence,
                        "department_sequence"								=>	$department_sequence,
                        "position_sequence"								=>	$position_sequence,

                        "bank_payment_sequence"			=>	$bank_payment_sequence,
                        "cash_payment_sequence"			=>	$cash_payment_sequence,
                        "bank_receipt_sequence"			=>	$bank_receipt_sequence,
                        "cash_receipt_sequence"			=>	$cash_receipt_sequence,
                        "contra_sequence"			      =>	$contra_sequence,
                        "scrap_issue_sequence"			=>	$scrap_issue_sequence,
                        "scrap_entry_sequence"			=>	$scrap_entry_sequence,
                        "scrap_receive_sequence"		=>	$scrap_receive_sequence,

												"payment_duration_for_sale" 		=>	$payment_duration_for_sale,
												"payment_duration_for_purchase" =>	$payment_duration_for_purchase,
                        "instance_id" 		=>	$instance_id,
												"access_token" =>	$access_token,
                        "whatsapp_no"		=>	$whatsapp_no,
                        "whatsapp_country_code"		=>	$whatsapp_country_code
											);

									
					// upload logo file
		     	if(!empty($_FILES['logo']['name']))
		    	{
						$logo 	= $this->do_upload($_FILES['logo']);
						$data['logo'] 	= 	$logo;
					}
					else
					{
						if($old_company_setting->logo == '')
						{
							$logo 			=	'no_image.png';
							
						}
					}

					// upload logo file
		     	if(!empty($_FILES['favicon']['name']))
		    	{
						$favicon 	= $this->do_upload($_FILES['favicon']);
						$data['favicon']	= 	$favicon;
					}
					else
					{
						if($old_company_setting->favicon == '')
						{
							$favicon 			=	'no_image.png';	
							
						}
					}

					if(!empty($_FILES['signature']['name']))
		    	{
						$signature 	= $this->do_upload($_FILES['signature']);
						$data['signature']	= 	$signature;
					}
					else
					{
						if($old_company_setting->signature == '')
						{
							$signature 			=	'no_image.png';	
							
						}
					}
					
				
					if($this->company_settings_model->edit_company_settings_record($data,1))
					{
						$entered_company_setting = $this->company_settings_model->get_company_records();

						$this->session->set_userdata('currency_symbol',$entered_company_setting->currency_symbol);
						
						$this->session->set_userdata('seperator',$entered_company_setting->seperator);
						$this->session->set_userdata('purchase_order_prefix',$entered_company_setting->purchase_order_prefix);
						$this->session->set_userdata('purchase_prefix',$entered_company_setting->purchase_prefix);
						$this->session->set_userdata('purchase_return_prefix',$entered_company_setting->purchase_return_prefix);
						$this->session->set_userdata('proforma_invoice_prefix',$entered_company_setting->proforma_invoice_prefix);
						$this->session->set_userdata('sale_prefix',$entered_company_setting->sale_prefix);
						$this->session->set_userdata('sale_return_prefix',$entered_company_setting->sale_return_prefix);
						$this->session->set_userdata('quotation_prefix',$entered_company_setting->quotation_prefix);
						$this->session->set_userdata('credit_debit_note_prefix',$entered_company_setting->credit_debit_note_prefix);

            $this->session->set_userdata('bank_payment_prefix',$entered_company_setting->bank_payment_prefix);
            $this->session->set_userdata('cash_payment_prefix',$entered_company_setting->cash_payment_prefix);
            $this->session->set_userdata('bank_receipt_prefix',$entered_company_setting->bank_receipt_prefix);
            $this->session->set_userdata('cash_receipt_prefix',$entered_company_setting->cash_receipt_prefix);
            $this->session->set_userdata('contra_prefix',$entered_company_setting->contra_prefix);
            $this->session->set_userdata('scrap_issue_prefix',$entered_company_setting->scrap_issue_prefix);
            $this->session->set_userdata('scrap_entry_prefix',$entered_company_setting->scrap_entry_prefix);
            $this->session->set_userdata('scrap_receive_prefix',$entered_company_setting->scrap_receive_prefix);

            $this->session->set_userdata('employee_prefix',$entered_company_setting->employee_prefix);
            $this->session->set_userdata('department_prefix',$entered_company_setting->department_prefix);
            $this->session->set_userdata('position_prefix',$entered_company_setting->position_prefix);
						
						$this->session->set_userdata('purchase_order_date_format',$entered_company_setting->purchase_order_date_format);
						$this->session->set_userdata('purchase_date_format',$entered_company_setting->purchase_date_format);
						$this->session->set_userdata('purchase_return_date_format',$entered_company_setting->purchase_return_date_format);
						$this->session->set_userdata('proforma_invoice_date_format',$entered_company_setting->proforma_invoice_date_format);
						$this->session->set_userdata('sale_date_format',$entered_company_setting->sale_date_format);
						$this->session->set_userdata('sale_return_date_format',$entered_company_setting->sale_return_date_format);
						$this->session->set_userdata('quotation_date_format',$entered_company_setting->quotation_date_format);
						$this->session->set_userdata('credit_debit_note_date_format',$entered_company_setting->credit_debit_note_date_format);

            $this->session->set_userdata('bank_payment_date_format',$entered_company_setting->bank_payment_date_format);
            $this->session->set_userdata('cash_payment_date_format',$entered_company_setting->cash_payment_date_format);
            $this->session->set_userdata('bank_receipt_date_format',$entered_company_setting->bank_receipt_date_format);
            $this->session->set_userdata('cash_receipt_date_format',$entered_company_setting->cash_receipt_date_format);
            $this->session->set_userdata('contra_date_format',$entered_company_setting->contra_date_format);
            $this->session->set_userdata('scrap_issue_date_format',$entered_company_setting->scrap_issue_date_format);
            $this->session->set_userdata('scrap_entry_date_format',$entered_company_setting->scrap_entry_date_format);
            $this->session->set_userdata('scrap_receive_date_format',$entered_company_setting->scrap_receive_date_format);

            $this->session->set_userdata('employee_date_format',$entered_company_setting->employee_date_format);
            $this->session->set_userdata('department_date_format',$entered_company_setting->department_date_format);
            $this->session->set_userdata('position_date_format',$entered_company_setting->position_date_format);

						$this->session->set_userdata('purchase_order_sequence',$entered_company_setting->purchase_order_sequence);
						$this->session->set_userdata('purchase_sequence',$entered_company_setting->purchase_sequence);
						$this->session->set_userdata('purchase_return_sequence',$entered_company_setting->purchase_return_sequence);
						$this->session->set_userdata('proforma_invoice_sequence',$entered_company_setting->proforma_invoice_sequence);
						$this->session->set_userdata('sale_sequence',$entered_company_setting->sale_sequence);
						$this->session->set_userdata('sale_return_sequence',$entered_company_setting->sale_return_sequence);
						$this->session->set_userdata('quotation_sequence',$entered_company_setting->quotation_sequence);
						$this->session->set_userdata('credit_debit_note_sequence',$entered_company_setting->credit_debit_note_sequence);

            $this->session->set_userdata('bank_payment_sequence',$entered_company_setting->bank_payment_sequence);
            $this->session->set_userdata('cash_payment_sequence',$entered_company_setting->cash_payment_sequence);
            $this->session->set_userdata('bank_receipt_sequence',$entered_company_setting->bank_receipt_sequence);
            $this->session->set_userdata('cash_receipt_sequence',$entered_company_setting->cash_receipt_sequence);
            $this->session->set_userdata('contra_sequence',$entered_company_setting->contra_sequence);
            $this->session->set_userdata('scrap_issue_sequence',$entered_company_setting->scrap_issue_sequence);
            $this->session->set_userdata('scrap_entry_sequence',$entered_company_setting->scrap_entry_sequence);
            $this->session->set_userdata('scrap_receive_sequence',$entered_company_setting->scrap_receive_sequence);

            $this->session->set_userdata('employee_sequence',$entered_company_setting->employee_sequence);
            $this->session->set_userdata('department_sequence',$entered_company_setting->department_sequence);
            $this->session->set_userdata('position_sequence',$entered_company_setting->position_sequence);

						
            $this->company_settings_model->update_company_upi_image($entered_company_setting->upi_address);
						

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "company_setting",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_company_setting),
											"data"			=> json_encode((array)$entered_company_setting),
											"description" 	=> $company_name.' successfully updated.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $company_name.' is updated successfully');
						redirect('settings','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $company_name.' is failed to update');
						redirect('settings','refresh');
					}
				}
			}
			else
			{
				
				$data['currencies'] 		= $this->currency_model->get_records();
				$data['countries'] 			= $this->utility_model->get_countries();
				$data['company_setting'] 	= $this->company_settings_model->get_company_records();
				$data['date_formats'] 	= $this->config->item('date_format'); // Fetch date formats


				if($data['company_setting']->country_id != '')
					$data['states'] 		= $this->utility_model->get_states($data['company_setting']->country_id);

				if($data['company_setting']->state_id != '')
					$data['cities'] 		= $this->utility_model->get_cities($data['company_setting']->state_id);

				$this->load->view('settings/company_setting',$data);
			}
		}
	}

	private function do_upload($image)
	{
			if(!empty($image))
			{
				$data['company_setting'] 	= $this->company_settings_model->get_company_records();

				$cid = $data['company_setting']->cid; 

				$type = explode('.',$image["name"]);
				$type = $type[count($type)-1];
				$name = uniqid(rand()).'.'.$type;
				$url = './assets/images/'.$cid.'/'.$name;

				// echo $url;
				// exit;

				// Create the directory if it doesn't exist
				$targetDir = dirname($url);
				if (!file_exists($targetDir)) {
						mkdir($targetDir, 0777, true);
				}

				if(in_array($type,array("jpg","jpeg","gif","png")))
				{
						if(is_uploaded_file($image["tmp_name"]))
						{
								if(move_uploaded_file($image["tmp_name"],$url))
								{
										return $name;
								}
						}    
				}
				return  "";     
			}
	}
}
