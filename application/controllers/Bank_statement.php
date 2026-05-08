<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php'; // Include the Composer autoloader

use PhpOffice\PhpSpreadsheet\IOFactory;


class Bank_statement extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_bank_statement'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['bank_statement'] = $this->bank_statement_model->get_records();

// 			$log_data = array(
//                   "user_id"     => $this->session->userdata("user_id"),
//                   "module"      => "bank_statement",
//                   "user_action" => 0,
//                   "description"	=> "User has viewed list of bank statement."
//                 );

// 			$this->log_data_model->add_record($log_data);

			$this->load->view('bank_statement/list',$data);
		}
	}

  public function upload_statement()
	{
    $response   = array();

    if (isset($_FILES["upload_statement"])) 
	  { 

      $company_setting = $this->company_settings_model->get_company_records();
      $bank_account_id = $this->input->post('bank_account_id');
      
      $cid = $company_setting->cid; 

      $targetDir = "./assets/documents/$cid/bank_statement/";


      //$targetDir  = "./assets/company/"; // Replace with your desired folder path

      // Create the target folder if it doesn't exist
      if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // The third parameter creates nested directories if needed
      }

      $uploadFilename = basename($_FILES["upload_statement"]["name"]);
    

      // Append the microsecond timestamp as a prefix to the filename
      $newFilename = time()."_". $uploadFilename;

      $targetFile = $targetDir . $newFilename;

      $isExcelFileValid = is_xlsx_valid($_FILES["upload_statement"]["tmp_name"],['TransactionDate','ChequeNo','Particulars','Withdraw','Deposit']); 

      if($isExcelFileValid === true)
      {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["upload_statement"]["tmp_name"], $targetFile)) 
        {
          $response['code'] = 1;
          $response['statement'] = $newFilename;
          $response['isExcelFileValid'] = $isExcelFileValid;
          $response['message']   = "Statement is uploaded successfully.";
        } 
        else 
        {
          $response['code']     = 0;
          $response['statement'] = '';
          $response['message']  = 'There is while uploading statement. Please contact the administrator.';
        }
      }
      else{
        $response['code']     = 0;
        $response['statement'] = '';
        $response['message']  = $isExcelFileValid;
      }

      echo json_encode($response);
    } 
    else 
    {
      $response['code']     = 0;
      $response['message']  = "You must have to select the file."; 
      echo json_encode($response);
    }
	}
  
  // public function upload_statement()
	// {
  //   $response   = array();

  //   if (isset($_FILES["upload_statement"])) 
	//   { 
  //     $targetDir  = "./assets/accounts/"; // Replace with your desired folder path

  //     // Create the target folder if it doesn't exist
  //     if (!file_exists($targetDir)) {
  //       mkdir($targetDir, 0777, true); // The third parameter creates nested directories if needed
  //     }

  //     $uploadFilename = basename($_FILES["upload_statement"]["name"]);
  //     $company_setting = $this->company_settings_model->get_company_records();
  //     $bank_account_id = $this->input->post('bank_account_id');
      
  //     $cid = $company_setting->cid;

  //     // Append the microsecond timestamp as a prefix to the filename
  //     $newFilename = $cid . "_" . $bank_account_id . "_".time()."_". $uploadFilename;

  //     $targetFile = $targetDir . $newFilename;

  //     $isCsvValid = is_csv_valid($_FILES["upload_statement"]["tmp_name"],",",['TransactionDate','ChequeNo','Particulars','Withdraw','Deposit']); 

  //     if($isCsvValid === true)
  //     {
  //       // Move the uploaded file to the target directory
  //       if (move_uploaded_file($_FILES["upload_statement"]["tmp_name"], $targetFile)) 
  //       {
  //         $response['code'] = 1;
  //         $response['statement'] = $newFilename;
  //         $response['message']   = "Statement is uploaded successfully.";
  //       } 
  //       else 
  //       {
  //         $response['code']     = 0;
  //         $response['statement'] = '';
  //         $response['message']  = 'There is while uploading statement. Please contact the administrator.';
  //       }
  //     }
  //     else{
  //       $response['code']     = 0;
  //       $response['statement'] = '';
  //       $response['message']  = $isCsvValid;
  //     }

  //     echo json_encode($response);
  //   } 
  //   else 
  //   {
  //     $response['code']     = 0;
  //     $response['message']  = "You must have to select the file."; 
  //     echo json_encode($response);
  //   }
	// }

	public function add($id = NULL)
	{
		if(!$this->permission_model->has_permission('add_bank_statement'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('bank_account_id','Bank account','required');		
				

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('bank_statement/add');
				}
				else
				{
					$bank_account_id 		= 	$this->input->post('bank_account_id');
					$statement 		= 	$this->input->post('statement');

					$data		=	array(
											"bank_account_id"		=> 	$bank_account_id,
                      "statement"		      => 	$statement
                  );

          if($id = $this->bank_statement_model->add_record($data))
					{
            $company_setting = $this->company_settings_model->get_company_records();
          
            // $cid = $company_setting->cid;

            $file_path = './assets/company/'.$data['statement'];
            $csvData = $this->csvreader->parse_excel($file_path);

            // $response['code'] = 1;
            // $response['data'] = $csvData;

            // echo json_encode($response);

            // echo '<pre>';
            // echo $file_path;
            // print_r($csvData);
            // exit;

            // Insert/update CSV data into database
            if(!empty($csvData))
            {
              foreach($csvData as $row)
              { 
                $bank_statement_data = array(
                  'bank_statement_id' => $id,
                  'transaction_date'  => date('Y-m-d', strtotime($row['TransactionDate'])),
                  'cheque_no'         => $row['ChequeNo'],
                  'particulars'       => $row['Particulars'],
                  'withdraw'          => $row['Withdraw'],
                  'deposit'           => $row['Deposit']
                );
                
                $this->bank_statement_entries_model->add_record($bank_statement_data);
              }
            }

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'Bank statement is added successfully.';
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Bank statement is added successfully');
						  redirect('bank_statement','refresh');
						}

						
					}
					else
					{
            if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 0;
							$response['message']						= 'Bank statement is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Bank statement is failed to add.');
						  redirect('bank_statement','refresh');
						}
					}
				}
			}
			else
			{
				$data 										= array();
				$data['bank_statement_id']  = $id;
				$data['bank_accounts'] 		= $this->bank_account_model->get_records();

				if($this->input->is_ajax_request()) 
				{
					$response 										= array();
					$response['code']							= 1; 						
			  	$response['add_bank_statement_modal_body'] 	= $this->load->view('bank_statement/ajax/add_bank_statement_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('bank_statement/add',$data);
				}
			}
		}
	}

	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_bank_statement'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id               = $this->input->post('id');
			$data             = array('delete_status' => 1);
			$bank_statement   = $this->bank_statement_model->get_single_record($id);
      $response         = array();

			if($this->bank_statement_model->edit_record($data,$id))
			{
        if($this->input->is_ajax_request())
        {
          $response['code'] = 1;
          $response['message'] = 'Bank statement is deleted successfully.';

          echo json_encode($response);
        }
        else
        {
          $this->session->set_flashdata('success', 'Bank statement is deleted successfully.');
				  redirect('bank_statement','refresh');
        }
				
			}
			else
			{
        if($this->input->is_ajax_request())
        {
          $response['code'] = 1;
          $response['message'] = 'Bank statement is failed to delete.';

          echo json_encode($response);
        }
        else
        {
          $this->session->set_flashdata('failure', 'Bank statement is failed to delete.');
          redirect('bank_statement','refresh');
        }
			}
		}
	}

  public function view($id = null)
	{
		if(!$this->permission_model->has_permission('view_bank_statement'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      if($id != null)
      {
        $id 										    = base64_decode($id);
        $data['bank_statement'] 		= $this->bank_statement_model->get_single_record($id);

        if($data['bank_statement'] != null)
        {
          $data['bank_statement_entries'] 		= $this->bank_statement_entries_model->get_records($id);
          $this->load->view('bank_statement/view',$data);
        }
        else
        {
          $this->session->set_flashdata('failure',  'You have tried to access broken URL.');
          redirect('bank_statement','refresh');
        }
      
      }
      else
      {
        $this->session->set_flashdata('failure',  'You have tried to access broken URL.');
        redirect('bank_statement','refresh');
      }
		}
	}

	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->bank_statement_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    $statusCounts = array();

    foreach ($list as $item){  

    
    

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
        $table_footer = '</div>';
        $table_body   = '';


        
        // Edit Button        
        if($this->permission_model->has_permission('view_bank_statement'))
				{	
					$table_body .= '
                            <a href="'.base_url('bank_statement/view/'.base64_encode($item->bank_statement_id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="View Bank statement">
                              <i class="fas fa-eye"></i> View
                            </a>           
		                      ';
				}

        $table_body .= '
                            <a href="#" data-toggle="modal" data-target="#reconcile_bank_statement_modal" data-bank_statement_id="'.$item->bank_statement_id.'" class="btn btn-primary btn-xs" data-tt="tooltip" title="Reconcile Bank Statement">
                              <i class="fas fa-sync"></i> Reconcile
                            </a>           
		                      ';

				

				// Delete Button
				if($this->permission_model->has_permission('delete_bank_statement'))
				{	
					$table_body .= '
		                        <a href="#"  data-toggle="modal" data-target="#delete_bank_statement_modal" data-tt="tooltip" title="'.$this->lang->line('bank_statement_delete').'" class="btn btn-danger btn-xs delete_bank_statement" data-bank_statement_id="'.$item->bank_statement_id.'">
		                          <i class="fas fa-trash"></i> Delete
		                        </a>
													';
				}

        $company_setting = $this->company_settings_model->get_company_records();
          
        $cid = $company_setting->cid;
        
        $statement_link = '
                            <a href="'.base_url('assets/documents/' . $cid . '/bank_statement/' . $item->statement).'">
                              <i class="fas fa-arrow-circle-down"></i>
                            </a>
                          ';
			

				/* End Action column buttons*/

        $bank_statement_entries = $this->bank_statement_entries_model->get_records($item->bank_statement_id);

        // Track the reconcile status for each bank statement
        foreach ($bank_statement_entries as $entry) {
            $statusCounts[$item->bank_statement_id][$entry->reconcile_status] = true;
        }
    
        // Determine the overall status for this bank statement
        $status = '';
        
        foreach ($statusCounts as $bankStatementId => $statuses) {
          // Determine the status for each bank_statement_id
          if (isset($statuses['pending']) && !isset($statuses['completed'])) 
          {
            $status = '<span class="badge badge-danger">Pending</span>';
          } 
          elseif (!isset($statuses['pending']) && isset($statuses['completed'])) 
          {
            $status = '<span class="badge badge-success">Completed</span>';
          } 
          else 
          {
            $status = '<span class="badge badge-warning">Partially Completed</span>';
          }
        }
        $row = array();
       
        $row[] = $item->account_name;
        $row[] = $item->account_number;
        $row[] = $item->account_type;
        $row[] = $item->bank_name;
        $row[] = process_bank_statement_name($item->statement). ' '.$statement_link;
        $row[] = $status;
      	$row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->bank_statement_model->count_all(),
                    "recordsFiltered" => $this->bank_statement_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function bank_statement_delete_confirmation()
  {
  	$bank_statement_id 					= $this->input->post('bank_statement_id');
  	$data['bank_statement'] 		= $this->bank_statement_model->get_single_record($bank_statement_id);

  	$response = array();
  	$response['bank_statement_delete_modal_body'] = $this->load->view('bank_statement/ajax/bank_statement_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/

  public function reconcile($bank_statement_id = null)
  {
    if(!$this->permission_model->has_permission('view_bank_statement'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{  
      $data['bank_statement_entries']    = $this->bank_statement_entries_model->get_records($bank_statement_id);

      $response 					                              = array();
      $response['code'] 	                              = 1;
      $response['reconcile_bank_statement_modal_body'] 	= $this->load->view('bank_statement/ajax/reconcile_bank_statement_modal_view',$data,TRUE);

      echo json_encode($response);
      
		}
  }

  public function reconcile_entry($bank_statement_entry_id = null)
  {
    if(!$this->permission_model->has_permission('view_bank_statement'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{  

     
      $data['transaction_entries'] = $this->transaction_model->get_transaction_by_transaction_type(array(
        PAYMENT_TRANSACTION_TYPE,
        RECEIPT_TRANSACTION_TYPE,
        REDUCE_ADJUSTMENT_TRANSACTION_TYPE,
        INCREASE_ADJUSTMENT_TRANSACTION_TYPE
      ));

      $data['bank_statement_entry'] = $this->bank_statement_entries_model->get_single_record($bank_statement_entry_id);

      $response 					              = array();
      $response['code'] 	              = 1;
      $response['transaction_entries'] 	= $data['transaction_entries'];
      $response['transaction_entries_table'] 	= $this->load->view('bank_statement/ajax/transaction_entries_table',$data,TRUE);


      echo json_encode($response);
      
		}
  }

  public function update_reconcile($bank_statement_entry_id = null)
  {
    $transaction_ids = implode(',',$this->input->post('selectedTransactions'));
    $remarks = $this->input->post('remarks');
    $date =   ( $this->input->post('reconcile_date') != NULL &&  $this->input->post('reconcile_date') != '') ? date('Y-m-d',strtotime( $this->input->post('reconcile_date'))) : NULL;


    $bank_statement_entries_data = array(
                                          'transaction_entries' => $transaction_ids,
                                          'remarks'             => $remarks,
                                          'reconcile_datetime'  => $date,
                                          'reconcile_status'    => BANK_RECONCILE_STATUS_COMPLETED
                                        );

                       
    
    $bank_statement_entry = $this->bank_statement_entries_model->edit_record($bank_statement_entries_data,$bank_statement_entry_id);

    // Prepare the response
    $response = array();
    if ($bank_statement_entry) {
        $response['code'] = 1;
        $response['reconcile_status'] = BANK_RECONCILE_STATUS_COMPLETED;
        $response['message'] = 'Reconcile entries saved successfully.';
    } else {
        $response['code'] = 0;
        $response['message'] = 'Failed to save reconcile entries.';
    }

    // Send JSON response back to the client
    echo json_encode($response);
   
          
  }


  
		
}
