<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		if (!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}
	}
	
	
	public function index()
	{
		if($this->input->is_ajax_request())
		{
			$response 					= array();
			$response['suppliers']		= $this->supplier_model->get_records();
			echo json_encode($response);	
		}
		else
		{
			if(!$this->permission_model->has_permission('list_supplier'))
			{
				$this->load->view('errors/html/error_restricted'); 
			}
			else
			{
				$data['suppliers'] 			= $this->supplier_model->get_records();

				// $log_data = array(
    //               "user_id"     => $this->session->userdata("user_id"),
    //               "module"      => "supplier",
    //               "user_action" => 0,
    //               "description"	=> "User has viewed list of supplier."
    //             );

				// $this->log_data_model->add_record($log_data);

				$this->load->view('supplier/list',$data);
			}
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_supplier'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('company_name','Company name','required');		
				// $this->form_validation->set_rules('phone','Phone','required');
				$this->form_validation->set_rules('country_id','Country','required');
				$this->form_validation->set_rules('state_id','State','required');
			

				
				if($this->form_validation->run()==FALSE)
				{
					$country_id  			= $this->input->post('country_id');
					$state_id  				= $this->input->post('state_id');
					$data['countries'] 		= $this->utility_model->get_countries();

					if($country_id != '')
					{
						$data['states'] 	= $this->utility_model->get_states($country_id);
					}

					if($state_id != '')
					{
						$data['cities'] 	= $this->utility_model->get_cities($state_id);
					}

					$this->load->view('supplier/add',$data);
				}
				else
				{
					$contact_person_name				=   $this->input->post('contact_person_name');
					$contact_person_designation =   $this->input->post('contact_person_designation');
					$company_name   						=   $this->input->post('company_name');
					$gstin   										=   $this->input->post('gstin');
					$email   										=   $this->input->post('email');
					$bank_name                                   	=   $this->input->post('bank_name');
					$ifsc                                   	=   $this->input->post('ifsc');
					$acc_num                                  	=   $this->input->post('account_number');
					$phone   										=   $this->input->post('phone');
					$country_id   							=   $this->input->post('country_id');
					$state_id   								=   $this->input->post('state_id');
					$address   									=   $this->input->post('address');
					$website   									=   $this->input->post('website');
					$city_id   									=   $this->input->post('city_id');
					$gst_registration_type			= 	$this->input->post('gst_registration_type');
					$payment_duration   				=   $this->input->post('payment_duration');

                    $whatsapp_no 	= 	$this->input->post('whatsapp_no');
                    $whatsapp_country_code 	= 	$this->input->post('whatsapp_country_code');

					$ledger_data = array(
																"title" 						=> strtoupper($company_name),
																"account_group_id"	=> SUPPLIER_ACCOUNT_GROUP_ID
															);
					
					$ledger_id = $this->ledger_model->add_record($ledger_data);

					$data			=	array(
												"contact_person_name"					=>	$contact_person_name,
												"payment_duration"						=>	$payment_duration,
												"contact_person_designation"	=>	$contact_person_designation,
												"company_name"								=>	$company_name,
												"gst_registration_type"				=>  $gst_registration_type,
												"gstin"												=>	$gstin,
												"email"												=>	$email,
												"phone"												=>	$phone,
												"country_id"									=>	$country_id,
												"state_id"										=>	$state_id,
												"address"											=>	$address,
												"website"											=>	$website,
												"city_id"											=>	$city_id,
												"ledger_id"										=> 	$ledger_id,
												"bank_name"                                    	=> 	$bank_name,
												"ifsc_code"                                    	=> 	$ifsc,
												"account_number"                                    	=> 	$acc_num,
                        "whatsapp_no"		=>	$whatsapp_no,
                        "whatsapp_country_code"		=>	$whatsapp_country_code,
												"user_id"											=>  $this->session->userdata('user_id')
											);

			
					if($id = $this->supplier_model->add_record($data))
					{
						$entered_supplier = $this->supplier_model->get_single_record($id);

						$log_data = array(
											"user_id" 			=> $this->session->userdata("user_id"),
											"module"				=> "supplier",
											"entry_id"			=> $id,
											"user_action"		=> 1,
											"data"					=> json_encode((array)$entered_supplier),
											"description" 	=> $company_name.' successfully added.'
										);
						$this->log_data_model->add_record($log_data);

						if($this->input->is_ajax_request())
						{	
							$response 							= array();
							$response['code'] 			= 1;
							$response['id'] 				= $id;
							$response['message']		= $company_name.' is added successfully.';
							$response['suppliers']	= $this->supplier_model->get_records();
							$response['supplier']   = $this->supplier_model->get_single_record($id);

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', $company_name.' is added successfully');
							redirect('supplier','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request())
						{	
							$response 								= array();
							$response['code'] 				= 0;
							$response['message']			= $company_name.' is failed to add.';
						
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', $company_name.' is failed to add.');
							redirect('supplier',$data);
						}
					}
				}
			}
			else
			{	
				$company_setting = $this->company_settings_model->get_company_records();
				$data['countries'] 			= $this->utility_model->get_countries();
				$data['states']         = $this->utility_model->get_states($company_setting->country_id);
				$data['cities']         = $this->utility_model->get_cities($company_setting->state_id);
				$this->load->view('supplier/add',$data);
			}
		}
		
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_supplier'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');
				$old_supplier = $this->supplier_model->get_single_record($id);

				$this->form_validation->set_rules('company_name','Company name','required');
				$this->form_validation->set_rules('phone','Phone','required');
				$this->form_validation->set_rules('country_id','Country','required');
				$this->form_validation->set_rules('state_id','State','required');
				
				if($this->form_validation->run()==FALSE)
				{
					$country_id  			= $this->input->post('country_id');
					$state_id  				= $this->input->post('state_id');
					$data['countries'] 		= $this->utility_model->get_countries();
					$data['supplier'] 		= $this->supplier_model->get_single_record($id);

					if($country_id != '')
					{
						$data['states'] 		= $this->utility_model->get_states($country_id);
					}

					if($state_id != '')
					{
						$data['cities'] 		= $this->utility_model->get_cities($state_id);
					}

					$this->load->view('supplier/edit',$data);
				}
				else
				{
					$contact_person_name   		=   $this->input->post('contact_person_name');
					$contact_person_designation =   $this->input->post('contact_person_designation');
					$company_name   			=   $this->input->post('company_name');
					$gstin   					=   $this->input->post('gstin');
					$email   					=   $this->input->post('email');
					$phone   					=   $this->input->post('phone');
					$country_id   				=   $this->input->post('country_id');
					$state_id   				=   $this->input->post('state_id');
					$address   					=   $this->input->post('address');
					$website   					=   $this->input->post('website');
					$city_id   					=   $this->input->post('city_id');
					$gst_registration_type		= $this->input->post('gst_registration_type');
					$payment_duration   			=   $this->input->post('payment_duration');

          $whatsapp_no 	= 	$this->input->post('whatsapp_no');
          $whatsapp_country_code 	= 	$this->input->post('whatsapp_country_code');


					$data			=	array(
												"contact_person_name"		=>	$contact_person_name,
												"payment_duration"			=>	$payment_duration,
												"contact_person_designation"=>	$contact_person_designation,
												"company_name"				=>	$company_name,
												"gst_registration_type"		=>  $gst_registration_type,
												"gstin"						=>	$gstin,
												"email"						=>	$email,
												"phone"						=>	$phone,
												"country_id"				=>	$country_id,
												"state_id"					=>	$state_id,
												"address"					=>	$address,
												"website"					=>	$website,
												"city_id"					=>	$city_id,
                        "whatsapp_no"		=>	$whatsapp_no,
                        "whatsapp_country_code"		=>	$whatsapp_country_code
											);

					
					if($this->supplier_model->edit_record($data,$id))
					{
						$entered_supplier = $this->supplier_model->get_single_record($id);

						$ledger_data = array(
																	"title" 						=> strtoupper($company_name)
																);

						$this->ledger_model->edit_record($ledger_data,$entered_supplier->ledger_id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "supplier",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_supplier),
											"data"			=> json_encode((array)$entered_supplier),
											"description" 	=> $company_name.' successfully updated.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $company_name.' is updated successfully');
						redirect('supplier','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $company_name.' is failed to update');
						redirect('supplier','refresh');
					}
				}
			}
			else
			{
				$id = base64_decode($id);
				
				if($id != null)
				{
					$data['supplier'] 		= $this->supplier_model->get_single_record($id);
					
					if($data['supplier'] != null)
					{
						$data['countries'] 		= $this->utility_model->get_countries();
						if($data['supplier']->country_id != '')
							$data['states'] 	= $this->utility_model->get_states($data['supplier']->country_id);

						if($data['supplier']->state_id != '')
							$data['cities'] 	= $this->utility_model->get_cities($data['supplier']->state_id);
						$this->load->view('supplier/edit',$data);	
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('supplier','refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('supplier','refresh');
				}
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_supplier'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = 	$this->input->post('id');
			
			$data = array(
							'delete_status' => 1
						);

			$supplier =  $this->supplier_model->get_single_record($id);

			if($this->supplier_model->edit_record($data,$id))
			{
				$log_data = array(
		                      "user_id"     => $this->session->userdata("user_id"),
		                      "module"      => "supplier",
		                      "user_action" => 3,
		                      "data"        => json_encode($data),
		                      "description" => $supplier->company_name.' deleted successfully.'
		                    );
          		$this->log_data_model->add_record($log_data);

				$this->session->set_flashdata('success', $supplier->company_name.' is deleted successfully');
				redirect('supplier','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', $supplier->company_name.' is failed to delete');
				redirect('supplier','refresh');
			}
		}
	}

	public function view($id = null)
	{
		if($id != null)
		{
			$id 									= base64_decode($id);
			$data['supplier'] 		= $this->supplier_model->get_single_record($id);

			if($data['supplier'] != null)
			{
				$data['expenses'] 		= $this->expense_model->get_records_by_supplier_id($id);
				$data['purchases']		= $this->report_model->purchase_report_supplier(null,null,$id)->result();
				$data['purchase_orders']		= $this->purchase_order_model->get_purchase_order_records_by_supplier_id($id);
				$data['purchase_returns']		= $this->purchase_return_model->get_purchase_return_records_by_supplier_id($id);
				
				$this->load->view('supplier/view',$data);	
				
			}
			else
			{
				$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
				redirect('supplier','refresh');		
			}
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('supplier','refresh');
		}
	}
	
	public function get_record_detail()
	{
		$id 		= $this->input->post('supplier_id');
		$data['supplier'] 	= $this->supplier_model->get_single_record($id);

		$data['company_setting']	= $this->company_settings_model->get_company_records();	

		$data['futureDate'] = '';

		if($data['supplier']->payment_duration == 0)
		{
			$data['futureDate']  = date('d-m-Y', strtotime($data['company_setting']->payment_duration_for_purchase.' days')); 
		}
		else
		{
			$data['currentDate'] = date('d-m-Y');
			$data['futureDate']  = date('d-m-Y', strtotime('+'.$data['supplier']->payment_duration.' days', strtotime($data['currentDate'])));
		}

		echo json_encode($data);
	}

	public function import_supplier()
	{
		if(!$this->permission_model->has_permission('import_supplier'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
				$filename = $this->upload_model->do_single_upload($_FILES['csvfile']);	
				$file_path = './assets/import/'.$filename;

				$query = sprintf(
													"
														LOAD DATA LOCAL INFILE '%s' INTO TABLE supplier 
								            FIELDS TERMINATED BY ',' 
								            LINES TERMINATED BY '\r\n'
								            IGNORE 1 LINES (company_name, gstin, email, phone, contact_person_name, contact_person_designation, website, country_name, state_name, city_name, address);
							           	", 
							          addslashes($file_path));

				if($number = $this->db->query($query))
				{
					$no_imported_suppliers = $this->db->affected_rows();

					$imported_suppliers = $this->db->query('SELECT * FROM `supplier` `c` ORDER BY id DESC LIMIT '.$no_imported_suppliers)->result();

					foreach ($imported_suppliers as $value) {
						$ledger_data = array(
																"title" 						=> strtoupper($value->company_name),
																"account_group_id"	=> SUPPLIER_ACCOUNT_GROUP_ID
															);
					
						$ledger_id = $this->ledger_model->add_record($ledger_data);			

						$data = array(
													"ledger_id" => $ledger_id,
													"user_id" => $this->session->userdata('user_id'),
													"country_id" => $this->utility_model->get_country_by_name($value->country_name)->id,
													"state_id" => $this->utility_model->get_state_by_name($value->state_name)->id,
													"city_id" => $this->utility_model->get_city_by_name($value->city_name)->id
												);

						if($value->gstin != '')
							$data['gst_registration_type'] = 1;

						$this->supplier_model->edit_record($data, $value->id);
					}

					$this->session->set_flashdata('success', $no_imported_suppliers.' Suppliers are imported successfully');
					redirect('supplier','refresh');
				}
				else
				{
					$this->session->set_flashdata('success', $no_imported_suppliers.' Suppliers are failed to import.');
					redirect('supplier','refresh');
				}
			}
			else
			{
				$data = array();
				if($this->input->is_ajax_request()) 
				{
					$response 									= array();
			  	$response['code']  					= 1;
					$response['import_supplier_modal_body'] = $this->load->view('supplier/ajax/import_supplier_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

	/************************************** Start Dynamic Datatable function *************************************/

// 	public function ajax_list()
//   {
//     $list 		= $this->supplier_model->get_datatables();
//     $data 		= array();
//     $no 			= $_POST['start'];

//     foreach ($list as $item){  

//   		/* Begin Action column buttons*/
//   		$table_header = '<div class="btn-group">';
//       $table_footer = '</div>';
//       $table_body   = '';

//       //view Button
//       if($this->permission_model->has_permission('view_supplier'))
//       {
//       	$table_body .=	'
//                           <a href="'.base_url('supplier/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="'.$this->lang->line('supplier_view').'">
//                             <i class="fas fa-eye"></i>
//                           </a>
//                         ';
//       }

// 			// Edit Button        
//       if($this->permission_model->has_permission('edit_supplier'))
// 			{	
// 				$table_body .= '
// 	                        <a href="'.base_url('supplier/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" data-tt="tooltip" title="'.$this->lang->line('supplier_edit').'">
// 	                          <i class="fas fa-edit"></i>
// 	                        </a>        
// 	                      ';
// 			}

// 			// Delete Button
// 			if($this->permission_model->has_permission('delete_supplier'))
// 			{	
// 				$table_body .= '
// 	                        <a href="#"  data-toggle="modal" data-target="#delete_supplier" data-tt="tooltip" title="'.$this->lang->line('supplier_delete').'" class="btn btn-danger btn-xs delete_supplier" data-supplier_id="'.$item->id.'">
// 	                          <i class="fas fa-trash"></i>
// 	                        </a>
// 												';
// 			}

		

// 			/* End Action column buttons*/

// 			/* Start gst registration type */
// 			// $gst_registration_type = '';

// 		 	// if($item->gst_registration_type == 0)
//     //   {
//     //     $gst_registration_type =  $this->lang->line('gst_reg_type_not_reg');
//     //   }
//     //   else if($item->gst_registration_type == 2)
//     //   {
//     //     $gst_registration_type = $this->lang->line('gst_reg_type_composite'); 
//     //   }
//     //   else if($item->gst_registration_type == 1)
//     //   {
//     //     $gst_registration_type = $this->lang->line('gst_reg_type_reg'); 
//     //   }

//       /* End gst registration type */

//       $row = array();
     
//       $row[] = $item->company_name;
//       $gst_types = [
//     0 => 'Not Registered',
//     1 => 'Registered',
//     2 => 'Composite'
// ];

// $row[] = isset($gst_types[$item->gst_registration_type]) ? $gst_types[$item->gst_registration_type] : 'Unknown';

//       $row[] = $item->gstin;
//       $row[] = $item->email;
//       $row[] = $item->phone;
//       $row[] = $item->contact_person_name;
//       $row[] = $item->contact_person_designation;
//     //   $row[] = $item->website;
//       $row[] = $item->bank_name;
//       $row[] = $item->ifsc_code;
//       $row[] = $item->account_number;
//       $row[] = $item->state_name;
//     //   $row[] = $item->city_name;
//       $row[] = $item->country_name;


//       $row[] = $table_header.$table_body.$table_footer;    
// 			$data[] = $row;
//     }

//     $output = array(
//                     "draw" 						=> $_POST['draw'],
//                     "recordsTotal" 		=> $this->supplier_model->count_all(),
//                     "recordsFiltered" => $this->supplier_model->count_filtered(),
//                     "data" 						=> $data,
//             			);
   
//     echo json_encode($output);
//   }	

public function ajax_list()
{
    $list = $this->supplier_model->get_datatables();
    $data = array();
    $no = $_POST['start'];

    foreach ($list as $item) {  
        $table_header = '<div class="btn-group">';
        $table_footer = '</div>';
        $table_body   = '';

        if($this->permission_model->has_permission('view_supplier')) {
            $table_body .= '<a href="'.base_url('supplier/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" title="View"><i class="fas fa-eye"></i></a>';
        }
        if($this->permission_model->has_permission('edit_supplier')) {	
            $table_body .= '<a href="'.base_url('supplier/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-edit"></i></a>';
        }
        if($this->permission_model->has_permission('delete_supplier')) {	
            $table_body .= '<a href="#" data-toggle="modal" data-target="#delete_supplier" class="btn btn-danger btn-xs" data-supplier_id="'.$item->id.'"><i class="fas fa-trash"></i></a>';
        }

        $gst_types = [0 => 'Not Registered', 1 => 'Registered', 2 => 'Composite'];
        $reg_type = isset($gst_types[$item->gst_registration_type]) ? $gst_types[$item->gst_registration_type] : 'Unknown';

        $row = array();
        $row[] = $item->company_name;               // 0
        $row[] = $reg_type;                         // 1
        $row[] = $item->gstin;                      // 2
        $row[] = $item->email;                      // 3
        $row[] = $item->phone;                      // 4
        $row[] = $item->contact_person_name;        // 5
        $row[] = $item->contact_person_designation; // 6
        $row[] = $item->bank_name;                  // 7
        $row[] = $item->ifsc_code;                  // 8
        $row[] = $item->account_number;             // 9
        $row[] = $item->state_name;                 // 10
        $row[] = $item->country_name;               // 11
        $row[] = $table_header.$table_body.$table_footer; // 12 (Action)

        $data[] = $row;
    }

    echo json_encode([
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->supplier_model->count_all(),
        "recordsFiltered" => $this->supplier_model->count_filtered(),
        "data" => $data,
    ]);
}

  public function supplier_delete_confirmation()
  {
  	$supplier_id 				= $this->input->post('supplier_id');
  	$data['supplier']		= $this->supplier_model->get_single_record($supplier_id);

  	$response 																= array();
  	$response['supplier_delete_modal_body'] 	= $this->load->view('supplier/ajax/supplier_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/
		
}
