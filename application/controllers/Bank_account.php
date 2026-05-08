<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_account extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_bank_account'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['bank_account'] = $this->bank_account_model->get_records();

// 			$log_data = array(
// 			                  "user_id"     => $this->session->userdata("user_id"),
// 			                  "module"      => "bank_account",
// 			                  "user_action" => 0,
// 			                  "description"	=> "User has viewed list of bank account."
// 			                );

// 			$this->log_data_model->add_record($log_data);

			$this->load->view('bank_account/list',$data);
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_bank_account'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('account_name','Account Name','required');		
				$this->form_validation->set_rules('account_number','Account Number','required');
				$this->form_validation->set_rules('bank_name','Bank Name','required');		
				// $this->form_validation->set_rules('ifsc','IFSC','required');
				$this->form_validation->set_rules('opening_balance','Opening Balance','required');

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('bank_account/add');
				}
				else
				{
					$account_name 		= 	$this->input->post('account_name');
					$account_number 	= 	$this->input->post('account_number');
					$account_type 		= 	$this->input->post('account_type');
					$bank_name 				= 	$this->input->post('bank_name');
					$ifsc 						= 	$this->input->post('ifsc');
					$description 			= 	$this->input->post('description');
					$opening_balance 	= 	$this->input->post('opening_balance');

					// being transaction
					$this->db->trans_begin();

					$ledger_data = array(
																"title" 						=> strtoupper($account_name),
																"account_group_id"	=> BANK_ACCOUNT_GROUP_ID,
																"opening_balance"		=> $opening_balance,
																"closing_balance" 	=> $opening_balance
															);

					$ledger_id = $this->ledger_model->add_record($ledger_data);

					$data		=	array(
											"account_name"		=> 	$account_name,
											"account_number"	=>	$account_number,
											"account_type"		=>	$account_type,
											"bank_name"				=>	$bank_name,
											"ifsc"						=>	$ifsc,
											"description"			=>	$description,
											"account_number"	=>	$account_number,
											"ledger_id"				=>  $ledger_id
									);

				
					if($id = $this->bank_account_model->add_record($data))
					{
						$entered_bank_account 				= $this->bank_account_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "bank_account",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"				=> json_encode((array)$entered_bank_account),
											"description" => $account_name.' successfully added.'
										);
						$this->log_data_model->add_record($log_data);

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success', $account_name.' is added successfully');
						redirect('bank_account','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						$this->session->set_flashdata('failure', $account_name.' is failed to add.');
						redirect('bank_account','refresh');
					}
				}
			}
			else
			{
				$this->load->view('bank_account/add');
			}
		}
		
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_bank_account'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{

				$id									= $this->input->post('id');
				$old_bank_account 	= $this->bank_account_model->get_single_record($id);

				$this->form_validation->set_rules('account_name','Account Name','required');		
				$this->form_validation->set_rules('account_number','Account Number','required');
				$this->form_validation->set_rules('bank_name','Bank Name','required');		
				$this->form_validation->set_rules('ifsc','IFSC','required');
				$this->form_validation->set_rules('opening_balance','Opening Balance','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['bank_account'] = $this->bank_account_model->get_single_record($id);
					$this->load->view('bank_account/edit',$data);
				}
				else
				{
					$account_name 		= 	$this->input->post('account_name');
					$account_number 	= 	$this->input->post('account_number');
					$account_type 		= 	$this->input->post('account_type');
					$bank_name 				=	 	$this->input->post('bank_name');
					$ifsc 						= 	$this->input->post('ifsc');
					$description 			= 	$this->input->post('description');
					$opening_balance 	= 	$this->input->post('opening_balance');


					$data		=	array(
													"account_name"		=> 	$account_name,
													"account_number"	=>	$account_number,
													"account_type"		=>	$account_type,
													"bank_name"				=>	$bank_name,
													"ifsc"						=>	$ifsc,
													"description"			=>	$description,
													"account_number"	=>	$account_number
												);

				
					if($this->bank_account_model->edit_record($data,$id))
					{
						$entered_bank_account = $this->bank_account_model->get_single_record($id);
						$bank_ledger 					= $this->ledger_model->get_single_record($entered_bank_account->ledger_id);

						$ledger_data = array(
																	"title" 						=> strtoupper($account_name),
																	"account_group_id" 	=> BANK_ACCOUNT_GROUP_ID
																);

						$this->ledger_model->edit_record($ledger_data,$entered_bank_account->ledger_id);

						$log_data = array(
															"user_id" 		=> $this->session->userdata("user_id"),
															"module"			=> "bank_account",
															"user_action"	=> 2,
															"b_data"			=> json_encode((array)$old_bank_account),
															"data"				=> json_encode((array)$entered_bank_account),
															"description" => $account_name.' successfully updated.'
														);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $account_name.' is updated successfully.');
						redirect('bank_account','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $account_name.' is failed to update.');
						redirect('bank_account','refresh');
					}
				}
			}
			else
			{
				$id 									= base64_decode($id);

				if($id != null)
				{
					$data['bank_account'] = $this->bank_account_model->get_single_record($id);
					if($data['bank_account'] != null)
					{
						$this->load->view('bank_account/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('bank_account');
					}
					
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('bank_account');
				}
			}
		}
	}

	public function view()
	{
		if(!$this->permission_model->has_permission('view_bank_account'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 										= $this->input->post('id');
			$data['bank_account'] 	= $this->bank_account_model->get_single_record($id);
			$data['transactions']		= $this->transaction_model->get_transaction_by_ledger_id_and_module($data['bank_account']->ledger_id,array(BANK_MODULE,));

			$response 												= array();
			$response['bank_account_detail']	= $this->load->view('bank_account/ajax/bank_account_details',$data,TRUE);
			$response['transaction_entries']	= $this->load->view('bank_account/ajax/transactions',$data,TRUE);

			echo json_encode($response);
		}
	}



	// function delete($id)
	// {
	// 	if(!$this->permission_model->has_permission('delete_bank_account'))
	// 	{
	// 		$this->load->view('errors/html/error_restricted'); 
	// 	}
	// 	else
	// 	{
	// 		$data 			= array('delete_status' => 1);
	// 		$bank_account 	= $this->bank_account_model->get_single_record($id);

	// 		if(!$this->bank_account_model->is_bank_have_transaction($id))
	// 		{
	// 			if($this->bank_account_model->edit_record($data,$id))
	// 			{
	// 				$log_data = array(
	// 		                      "user_id"     => $this->session->userdata("user_id"),
	// 		                      "module"      => "bank_account",
	// 		                      "user_action" => 3,
	// 		                      "data"        => json_encode($data),
	// 		                      "description" => $bank_account->account_name.' deleted successfully.'
	// 		                    );
	//           		$this->log_data_model->add_record($log_data);     

	// 				$this->session->set_flashdata('success', $bank_account->account_name.' is deleted successfully.');
	// 				redirect('bank_account','refresh');
	// 			}
	// 			else
	// 			{
	// 				$this->session->set_flashdata('failure', $bank_account->account_name.' failed to delete.');
	// 				redirect('bank_account','refresh');
	// 			}	
	// 		}
	// 		else
	// 		{
	// 			$this->session->set_flashdata('failure', $bank_account->account_name.' can not be delete as transactions are associated with it.');
	// 			redirect('bank_account','refresh');
	// 		}

			
	// 	}
	// }

	
  public function activate($id)
  {
    $activation = FALSE;

		$data = array('active' => 1);

		$bank_account = $this->bank_account_model->get_single_record($id);

		if ($bank_account != null)
    {
      $activation = $this->bank_account_model->edit_record($data,$id);
    }
    else
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 0;
        $response['message'] = 'You have tried to access broken URL.';

        echo json_encode($response);
      }
      else
      {
        $this->session->flashdata('failure','You have tried to access broken URL.');
        redirect('bank_account','refresh');
      }
    }

    if ($activation)
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 1;
        $response['message'] = $bank_account->account_name.' is ACTIVATED successfully.';

        echo json_encode($response);
      }
      else
      {
        // redirect them to the auth page
        $this->session->set_flashdata('failure', $bank_account->account_name.' is ACTIVATED successfully.');
        redirect("bank_account", 'refresh');
      }
      
    }
    else
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 0;
        $response['message'] = $bank_account->account_name.' is failed to ACTIVATE.';

        echo json_encode($response);
      }
      else
      {
        // redirect them to the forgot password page
        $this->session->set_flashdata('message', $bank_account->account_name.' is failed to ACTIVATE.');
        redirect("bank_account", 'refresh');
      }
      
    }
  }

 
  public function deactivate($id)
  {
    $activation = FALSE;

		$data = array('active' => 0);

		$bank_account = $this->bank_account_model->get_single_record($id);

		if ($bank_account != null)
    {
      $activation = $this->bank_account_model->edit_record($data,$id);
    }
    else
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 0;
        $response['message'] = 'You have tried to access broken URL.';

        echo json_encode($response);
      }
      else
      {
        $this->session->flashdata('failure','You have tried to access broken URL.');
        redirect('bank_account','refresh');
      }
    }

    if ($activation)
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 1;
        $response['message'] = $bank_account->account_name.' is DEACTIVATED successfully.';

        echo json_encode($response);
      }
      else
      {
        // redirect them to the auth page
        $this->session->set_flashdata('failure', $bank_account->account_name.' is DEACTIVATED successfully.');
        redirect("bank_account", 'refresh');
      }
      
    }
    else
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 0;
        $response['message'] = $bank_account->account_name.' is failed to DEACTIVATE.';

        echo json_encode($response);
      }
      else
      {
        // redirect them to the forgot password page
        $this->session->set_flashdata('message', $bank_account->account_name.' is failed to DEACTIVATE.');
        redirect("bank_account", 'refresh');
      }
      
    }
  }

	public function import_bank_account()
	{
		if(!$this->permission_model->has_permission('import_bank_account'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
				// $isUpdatebank_accountChecked 	= $this->input->post('update_bank_account') === '1';
				// $isUpdateCreateNewIfExistChecked 	= $this->input->post('create_bank_account') === '1';

        // $tax = $this->tax_model->get_records();
			
				$expectedHeaders = [
						'AccountName',
						'AccountNumber',
						'AccountType',
						'BankName',
						'IFSC',
						'Description',
						'Status',
						'OpeningBalance'
				];

				$isCSVFileValid = is_csv_valid(
															$_FILES["csvfile"]["tmp_name"],
															',',
															$expectedHeaders
													);


				$file_path = $_FILES["csvfile"]["tmp_name"];
				$file = fopen($file_path, 'r');
													

				$header_row = fgetcsv($file, 0, $delimiter= ",");

				
				if ($isCSVFileValid !== true)
				{
					$this->session->set_flashdata('failure', $isCSVFileValid);
					redirect('bank_account','refresh');
				}
				else
				{
					
					if(is_uploaded_file($_FILES['csvfile']['tmp_name']))
					{
						// Parse data from CSV file
						$csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);
	
						if(!empty($csvData))
						{
							
							foreach($csvData as $row)
							{ 
									// $bank_account_data = array(
									// 										'account_name'    => $row['AccountName'],
									// 										'account_number'  => $row['AccountNumber'],
									// 										'account_type'  	=> $row['AccountType'],
									// 										'bank_name'    		=> $row['Value'],
									// 										'ifsc'  					=> $row['IFSC'],
									// 										'description' 		=> $row['Description'],
									// 										'status'  				=> $row['Status']
																			
									// 								);

																	

							
								// Check whether name already exists in the database
								$exist_bank_account = $this->utility_model->get_records_by_field('bank_account','account_number',$row['AccountNumber'],false,false);

								if($exist_bank_account == null)
								{
										$ledger_data = array(
											"title" 						=> strtoupper($row['AccountName']),
											"account_group_id"	=> BANK_ACCOUNT_GROUP_ID,
											"opening_balance"		=> $row['OpeningBalance'],
											"closing_balance" 	=> $row['OpeningBalance']
										);

										$ledger_id = $this->ledger_model->add_record($ledger_data);

										$bank_account_data		=	array(
												"account_name"		=> 	$row['AccountName'],
												"account_number"	=>	$row['AccountNumber'],
												"account_type"		=>	$row['AccountType'],
												"bank_name"				=>	$row['BankName'],
												"ifsc"						=>	$row['IFSC'],
												"description"			=>	$row['Description'],
												"ledger_id"				=>  $ledger_id
										);


									$this->bank_account_model->add_record($bank_account_data);
								}
								else
								{

									$this->session->set_flashdata('failure', 'Imported account number already exists.');
									redirect('bank_account','refresh');
									
								}
									
							}

							$this->session->set_flashdata('success', 'Bank Account imported successfully.');
							redirect('bank_account','refresh');
						
						}
					}
					else
					{
							$this->session->set_flashdata('failure', 'Failed to import bank account.');
							redirect('bank_account','refresh');
					}
				}



			
			}
			else
			{
				$data = array();
				if($this->input->is_ajax_request()) 
				{
					$response 									= array();
			  	$response['code']  					= 1;
					$response['import_bank_account_modal_body'] = $this->load->view('bank_account/ajax/import_bank_account_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

  public function get_import_bank_account()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') 
		{
		  if (isset($_FILES["csvfile"]))
		  {
        $fileTmpPath = $_FILES["csvfile"]["tmp_name"];
        $fileName = $_FILES["csvfile"]["name"];

        $csvRecords = array();
       
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) 
        {
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
          {
              /*$csvData[] = $data;*/
              $bankaccountNames[] = $data[1];
              $csvRecords[] = $data;
          }
          fclose($handle);
        }

       
       /* print_r($csvRecords);*/
       
     		// Escape the product names to avoid SQL injection
        $escapedbankaccountNames = array_map(array($this->db, "escape_str"), $bankaccountNames);
        $inClause = "'" . implode("','", $escapedbankaccountNames) . "'";

        // Fetch existing product names from the database
        $query = $this->db->query("SELECT * FROM bank_account WHERE account_number IN ($inClause)");
        //$query = $this->supplier_model->check_email_availability($inClause);

        $existingbankaccount  = $query->result_array();

        // Perform the comparison
        $matchedbankaccount  = array();
        $unmatchedbankaccount  = array();

				// Return comparison result as JSON
	       foreach ($bankaccountNames as $bankaccountName) {
			        foreach ($existingbankaccount as $bankaccount) {
			            if ($bankaccount['account_number'] === $bankaccountName) {
			                $matchedbankaccount[] = $bankaccount;
			            }
			        }
			    }

        
			    header('Content-Type: application/json');
					echo json_encode($matchedbankaccount);

	    } 
	    else 
	    {
	        // Handle file upload errors
          $error = $this->upload->display_errors();
          echo "Error: $error";
	    }
		}
	}

	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->bank_account_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
        $table_body   = '';

        // View Button        
        if($this->permission_model->has_permission('view_bank_account'))
				{	
					$from_ledger = $this->ledger_model->get_single_record($item->ledger_id);

					$table_body .= '
                            <a href="#" data-toggle="modal" data-target="#view_bank_account_modal" class="btn bg-maroon btn-xs" data-tt="tooltip" title="'.$this->lang->line('bank_account_view').'" data-ledger_id="'.$item->ledger_id.'" data-account_name="'.$item->account_name.'" data-bank_name="'.$item->bank_name.'" data-closing_balance="'.$from_ledger->closing_balance.'" data-opening_balance="'.$from_ledger->opening_balance.'" data-id="'.$item->id.'">
                              <i class="fas fa-eye"></i> View
                            </a>
                          ';
				}

				//Transfer Bank Account
				if($this->permission_model->has_permission('transfer_bank_account'))
				{	
					$from_ledger = $this->ledger_model->get_single_record($item->ledger_id);

					$table_body .= '
                            <a href="#" data-toggle="modal" data-target="#transfer_modal" class="btn bg-teal btn-xs" data-tt="tooltip" title="'.$this->lang->line('bank_account_transfer').'" data-ledger_id="'.$item->ledger_id.'" data-account_name="'.$item->account_name.'" data-bank_name="'.$item->bank_name.'" data-closing_balance="'.$from_ledger->closing_balance.'" data-id="'.$item->id.'">
                              <i class="fas fa-exchange-alt"></i> Transfer
                            </a>
                          ';
				}

				//Deposite Bank Account
				if($this->permission_model->has_permission('deposit_bank_account'))
				{	
					$table_body .= '
                            <a href="#" data-toggle="modal" data-target="#deposit_modal" class="btn bg-purple btn-xs" data-tt="tooltip" title="'.$this->lang->line('bank_account_deposit').'" data-id="'.$item->id.'" data-account_name="'.$item->ledger_title.'" data-bank_name="'.$item->bank_name.'" data-ledger_id="'.$item->ledger_id.'">
                              <i class="fas fa-coins"></i> Deposit
                            </a>
                          ';
				}

		
				if($this->permission_model->has_permission('withdraw_bank_account'))
				{	
					$from_ledger = $this->ledger_model->get_single_record($item->ledger_id);

					$table_body .= '
                            <a href="#" data-toggle="modal" data-target="#withdraw_modal" class="btn bg-orange btn-xs" data-tt="tooltip" title="'.$this->lang->line('bank_account_withdraw').'" data-id="'.$item->id.'" data-account_name="'.$item->ledger_title.'" data-bank_name="'.$item->bank_name.'" data-ledger_id="'.$item->ledger_id.'" data-closing_balance="'.$from_ledger->closing_balance.'">
                              <strong>W</strong> Withdraw
                            </a>  
                          ';
				}

				
				if($this->permission_model->has_permission('increase_bank_account'))
				{	
					$from_ledger = $this->ledger_model->get_single_record($item->ledger_id);

					$table_body .= '
                            <a href="#" data-toggle="modal" data-target="#increase_reduce_modal" class="btn bg-navy btn-xs" data-tt="tooltip" title="'.$this->lang->line('ledger_increase_capital_amount').'" data-account_name="'.$item->ledger_title.'" data-modal_title="'.$this->lang->line('ledger_increase_capital_amount').'" data-ledger_id="'.$item->ledger_id.'" data-closing_balance="'.$from_ledger->closing_balance.'" data-transaction_type="'.INCREASE_ADJUSTMENT_TRANSACTION_TYPE.'" data-reference_no="INCREASE CAPITAL">
                              <i class="fas fa-arrow-up"></i> Increase
                            </a>  
                          ';
				}

				//Reduce Bank Account
				if($this->permission_model->has_permission('reduce_bank_account'))
				{	
					$from_ledger = $this->ledger_model->get_single_record($item->ledger_id);

					$table_body .= '
                            <a href="#" data-toggle="modal" data-target="#increase_reduce_modal" class="btn bg-pink btn-xs" data-tt="tooltip" title="'.$this->lang->line('ledger_reduce_capital_amount').'" data-account_name="'.$item->ledger_title.'" data-ledger_id="'.$item->ledger_id.'" data-modal_title="'.$this->lang->line('ledger_reduce_capital_amount').'" data-closing_balance="'.$from_ledger->closing_balance.'" data-transaction_type="'.REDUCE_ADJUSTMENT_TRANSACTION_TYPE.'" data-reference_no="REDUCE CAPITAL">
                              <i class="fas fa-arrow-down"></i> Decrease 
                            </a>  
                          ';
				}

				//Edit Bank Account
				if($this->permission_model->has_permission('edit_bank_account'))
				{	
					$from_ledger = $this->ledger_model->get_single_record($item->ledger_id);

					$table_body .= '
                            <a href="'.base_url('bank_account/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" title="'.$this->lang->line('bank_account_edit').'" data-id="'.$item->id.'"  data-account_name="'.$item->ledger_title.'" data-bank_name="'.$item->bank_name.'" data-ledger_id="'.$item->ledger_id.'" data-closing_balance="'.$from_ledger->closing_balance.'">
                              <i class="fas fa-edit"></i> Edit
                            </a>
                          ';
				}

			/* End Action column buttons*/

			
        $row = array();
       
        $row[] = $item->account_name;
	      $row[] = $item->account_number;
        $row[] = $item->bank_name;
        $row[] = $item->ifsc;
        $row[] = $item->description;
        $row[] = $item->opening_balance;
        $row[] = $item->closing_balance;
				$row[] =	($item->active == 1) ? '<a href="'.base_url('bank_account/deactivate/'.$item->id).'" class="change_status" data-tt="tooltip" title="Click here to Deactivate Bank Account"><span class="badge badge-success">Active</span></a>' : '<a href="'.base_url('bank_account/activate/'.$item->id).'" class="change_status" data-tt="tooltip" title="Click here to Activate Bank Account"><span class="badge badge-danger">Inactive</span></a>';
      

        $row[] = $table_header.$table_body.$table_footer;    

        $data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->bank_account_model->count_all(),
                    "recordsFiltered" => $this->bank_account_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	



	/************************************** End Dynamic Datatable function ***************************************/

		
}
