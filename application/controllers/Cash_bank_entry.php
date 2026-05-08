<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_bank_entry extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_cash_bank_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['cash_bank_entry'] = $this->cash_bank_entry_model->get_records();

// 			$log_data = array(
//                   "user_id"     => $this->session->userdata("user_id"),
//                   "module"      => "cash_bank_entry",
//                   "user_action" => 0,
//                   "description"	=> "User has viewed list of cash bank entry."
//                 );

// 			$this->log_data_model->add_record($log_data);

			$this->load->view('cash_bank_entry/list',$data);
		}
	}

	public function add($id = NULL)
	{
		if(!$this->permission_model->has_permission('add_cash_bank_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('voucher_type','Voucher type','required');		
        $this->form_validation->set_rules('from_account_id','From Account','required');		
        $this->form_validation->set_rules('to_account_id','To Account','required');		

				if($this->form_validation->run()==FALSE)
				{
          if($this->input->is_ajax_request())
          {
            $data['code'] = 2;
            $data['errors'] = $this->form_validation->error_array();
            echo json_encode($data);
          }
          else
          {
            $this->load->view('cash_bank_entry/add');
          }
				}
				else
				{
					$voucher_type 		= 	$this->input->post('voucher_type');
          $reference_no 		=   $this->cash_bank_entry_model->get_lastest_sequence_number($voucher_type);
          $from_account_id 	= 	$this->input->post('from_account_id');
					$to_account_id 	  = 	$this->input->post('to_account_id');
          $amount 	        = 	$this->input->post('amount');
          $narration 	      = 	$this->input->post('narration');

					$data		=	array(
                          "voucher_type"		  => 	$voucher_type,
                          "reference_no"	    =>	$reference_no,
                          "from_account_id"		=> 	$from_account_id,
                          "to_account_id"		  => 	$to_account_id,
                          "amount"	          =>	$amount,
                          "narration"	        =>	$narration
                        );

          // print_r($data);
          // exit;

          $voucher_date 				= $this->input->post('voucher_date');
          if($voucher_date != '')
            $data['voucher_date'] = date('Y-m-d',strtotime($voucher_date));
        
					if($id = $this->cash_bank_entry_model->add_record($data))
					{

            // update the ledgers

            if(in_array($voucher_type,array(VOUCHER_TYPE_BANK_RECEIPT,VOUCHER_TYPE_CASH_RECEIPT,VOUCHER_TYPE_CONTRA)))
            {
              /****************************** Start From Ledger ***********************************/

              $from_ledger 						= $this->ledger_model->get_single_record($from_account_id);
              $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance + $amount));
              $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);

              /****************************** End From Ledger *************************************/

              /****************************** Start To Ledger *******************************/

              $to_ledger 						= $this->ledger_model->get_single_record($to_account_id);
              $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance - $amount));
              $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);

              /****************************** End To Ledger *********************************/
            }
            else
            {
              /****************************** Start From Ledger ***********************************/

              $from_ledger 						= $this->ledger_model->get_single_record($from_account_id);
              $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance - $amount));
              $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);

              /****************************** End From Ledger *************************************/

              /****************************** Start To Ledger *******************************/

              $to_ledger 						= $this->ledger_model->get_single_record($to_account_id);
              $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance + $amount));
              $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);

              /****************************** End To Ledger *********************************/
            }


            /****************************** Add Transaction entry *********************************/

            $transaction_type = '';
            if($voucher_type == VOUCHER_TYPE_BANK_PAYMENT)
              $transaction_type = PAYMENT_TRANSACTION_TYPE;
            else if($voucher_type == VOUCHER_TYPE_BANK_RECEIPT)
              $transaction_type = RECEIPT_TRANSACTION_TYPE;
            else if($voucher_type == VOUCHER_TYPE_CASH_RECEIPT)
              $transaction_type = RECEIPT_TRANSACTION_TYPE;
            else if($voucher_type == VOUCHER_TYPE_CASH_PAYMENT)
              $transaction_type = PAYMENT_TRANSACTION_TYPE;
            else if($voucher_type == VOUCHER_TYPE_CONTRA)
              $transaction_type = CONTRA_TRANSACTION_TYPE;

						$transaction_header = array(
              "entry_id"				=>  $id,
              "module"					=>  CASH_BANK_MODULE,
              "type"						=>	$transaction_type,
              "amount"					=>	$amount,
              "voucher_date"		=>	$voucher_date,
              "from_account"		=>	$from_account_id,
              "to_account"			=>	$to_account_id,
              "reference_no"		=>	$reference_no
            );

            if($transaction_id = $this->transaction_model->add_record($transaction_header))
            {
              if(in_array($voucher_type,array(VOUCHER_TYPE_BANK_RECEIPT,VOUCHER_TYPE_CASH_RECEIPT,VOUCHER_TYPE_CONTRA)))
              {
                $from_transaction_detail = array(
                                  "transaction_id" 	=> $transaction_id,
                                  "voucher_type"		=> 'C',
                                  "ledger_id"				=> $from_account_id,
                                  "dr_amount"				=> $amount
                                );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                $to_transaction_detail = array(
                                "transaction_id" 	=> $transaction_id,
                                "voucher_type"		=> 'D',
                                "ledger_id"				=> $to_account_id,
                                "cr_amount"				=> $amount
                              );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
              }
              else
              {
                $from_transaction_detail = array(
                                  "transaction_id" 	=> $transaction_id,
                                  "voucher_type"		=> 'D',
                                  "ledger_id"				=> $from_account_id,
                                  "dr_amount"				=> $amount
                                );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                $to_transaction_detail = array(
                                "transaction_id" 	=> $transaction_id,
                                "voucher_type"		=> 'C',
                                "ledger_id"				=> $to_account_id,
                                "cr_amount"				=> $amount
                              );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
              }
            }

            /**************************************************************************************/
            
						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= str_replace('_', ' ', $voucher_type).' is added successfully.';
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', str_replace('_', ' ', $voucher_type).' is added successfully');
						  redirect('cash_bank_entry','refresh');
						}
						
					}
					else
					{
            if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 0;
							$response['message']						= str_replace('_', ' ', $voucher_type).' is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', str_replace('_', ' ', $voucher_type).' is failed to add.');
						  redirect('cash_bank_entry','refresh');
						}

						
					}
				}
			}
			else
			{
				$data 											= array();
				$data['cash_bank_entry_id'] = $id;
				// $data['ledger_accounts'] 		= $this->ledger_model->get_records(array(SUNDRY_DEBTORS_GROUP,SUNDRY_CREDITORS_GROUP));
				$data['company_setting'] 		= $this->company_settings_model->get_company_records();

				if($this->input->is_ajax_request()) 
				{
					$response 										= array();
					$response['code']							= 1; 						
			  	$response['add_cash_bank_entry_modal_body'] 	= $this->load->view('cash_bank_entry/ajax/add_cash_bank_entry_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('cash_bank_entry/add',$data);
				}
			}
		}
		
	}

  public function bulk_entry($id = NULL)
	{
		if(!$this->permission_model->has_permission('add_cash_bank_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
					$cash_bank_entries 				= $this->input->post('cash_bank_entries');
					$cash_bank_entry_items_array 	= explode("|", $this->input->post('cash_bank_entries'));


          for ($i=0; $i < sizeof($cash_bank_entry_items_array) ; $i++) { 
                
            $temp_cash_bank_entry_item = (array)json_decode($cash_bank_entry_items_array[$i]);
            // $temp_cash_bank_entry_item['id']  = $id;

            
            $voucher_type = $temp_cash_bank_entry_item['voucher_type'];
            $reference_no = $this->cash_bank_entry_model->get_lastest_sequence_number($temp_cash_bank_entry_item['voucher_type']);
            $temp_cash_bank_entry_item['reference_no']  = $reference_no;

            $voucher_date = date('Y-m-d',strtotime($temp_cash_bank_entry_item['voucher_date']));
            $temp_cash_bank_entry_item['voucher_date'] 			=  $voucher_date;

           
            if($id = $this->cash_bank_entry_model->add_record($temp_cash_bank_entry_item))
            {
             
              $voucher_type 			= $temp_cash_bank_entry_item['voucher_type'];
             
              $from_account_id 		= $temp_cash_bank_entry_item['from_account_id'];
              $to_account_id 			= $temp_cash_bank_entry_item['to_account_id'];
              $amount 			      = $temp_cash_bank_entry_item['amount'];
              $narration 			    = $temp_cash_bank_entry_item['narration'];
              // update the ledgers
  
              if(in_array($voucher_type,array(VOUCHER_TYPE_BANK_RECEIPT,VOUCHER_TYPE_CASH_RECEIPT,VOUCHER_TYPE_CONTRA)))
              {
                /****************************** Start From Ledger ***********************************/
  
                $from_ledger 						= $this->ledger_model->get_single_record($from_account_id);
                $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance + $amount));
                $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);
  
                /****************************** End From Ledger *************************************/
  
                /****************************** Start To Ledger *******************************/
  
                $to_ledger 						= $this->ledger_model->get_single_record($to_account_id);
                $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance - $amount));
                $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);
  
                /****************************** End To Ledger *********************************/
              }
              else
              {
                /****************************** Start From Ledger ***********************************/
  
                $from_ledger 						= $this->ledger_model->get_single_record($from_account_id);
                $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance - $amount));
                $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);
  
                /****************************** End From Ledger *************************************/
  
                /****************************** Start To Ledger *******************************/
  
                $to_ledger 						= $this->ledger_model->get_single_record($to_account_id);
                $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance + $amount));
                $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);
  
                /****************************** End To Ledger *********************************/
              }
  
  
              /****************************** Add Transaction entry *********************************/
  
              $transaction_type = '';
              if($voucher_type == VOUCHER_TYPE_BANK_PAYMENT)
                $transaction_type = PAYMENT_TRANSACTION_TYPE;
              else if($voucher_type == VOUCHER_TYPE_BANK_RECEIPT)
                $transaction_type = RECEIPT_TRANSACTION_TYPE;
              else if($voucher_type == VOUCHER_TYPE_CASH_RECEIPT)
                $transaction_type = RECEIPT_TRANSACTION_TYPE;
              else if($voucher_type == VOUCHER_TYPE_CASH_PAYMENT)
                $transaction_type = PAYMENT_TRANSACTION_TYPE;
              else if($voucher_type == VOUCHER_TYPE_CONTRA)
                $transaction_type = CONTRA_TRANSACTION_TYPE;
  
              $transaction_header = array(
                "entry_id"				=>  $id,
                "module"					=>  CASH_BANK_MODULE,
                "type"						=>	$transaction_type,
                "amount"					=>	$amount,
                "voucher_date"		=>	$voucher_date,
                "from_account"		=>	$from_account_id,
                "to_account"			=>	$to_account_id,
                "reference_no"		=>	$reference_no
              );
  
              if($transaction_id = $this->transaction_model->add_record($transaction_header))
              {
                if(in_array($voucher_type,array(VOUCHER_TYPE_BANK_RECEIPT,VOUCHER_TYPE_CASH_RECEIPT,VOUCHER_TYPE_CONTRA)))
                {
                  $from_transaction_detail = array(
                                    "transaction_id" 	=> $transaction_id,
                                    "voucher_type"		=> 'C',
                                    "ledger_id"				=> $from_account_id,
                                    "dr_amount"				=> $amount
                                  );
                  // transaction detail record
                  $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
  
                  $to_transaction_detail = array(
                                  "transaction_id" 	=> $transaction_id,
                                  "voucher_type"		=> 'D',
                                  "ledger_id"				=> $to_account_id,
                                  "cr_amount"				=> $amount
                                );
                  // transaction detail record
                  $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                }
                else
                {
                  $from_transaction_detail = array(
                                    "transaction_id" 	=> $transaction_id,
                                    "voucher_type"		=> 'D',
                                    "ledger_id"				=> $from_account_id,
                                    "dr_amount"				=> $amount
                                  );
                  // transaction detail record
                  $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
  
                  $to_transaction_detail = array(
                                  "transaction_id" 	=> $transaction_id,
                                  "voucher_type"		=> 'C',
                                  "ledger_id"				=> $to_account_id,
                                  "cr_amount"				=> $amount
                                );
                  // transaction detail record
                  $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                }
              }
  
              /**************************************************************************************/
              
            
              
            }
            // else
            // {
            //   if($this->input->is_ajax_request())
            //   {	
            //     $response 											= array();
            //     $response['code'] 							= 0;
            //     $response['message']						= 'Cash bank entry are failed to add.';
                
            //     echo json_encode($response);
            //   }
            //   else
            //   {
            //     $this->session->set_flashdata('failure', 'Cash bank entry are failed to add.');
            //     redirect('cash_bank_entry','refresh');
            //   }
  
              
            // }
          }

          if($this->input->is_ajax_request())
          {	
            $response 											= array();
            $response['code'] 							= 1;
            $response['id'] 								= $id;
            $response['message']						= 'Cash bank entry are added successfully.';
            echo json_encode($response);
          }
          else
          {
            $this->session->set_flashdata('success', 'Cash bank entry are added successfully');
            redirect('cash_bank_entry','refresh');
          }
        
				
			}
			else
			{
				$data 											= array();
				$data['cash_bank_entry_id'] = $id;
				// $data['ledger_accounts'] 		= $this->ledger_model->get_records(array(SUNDRY_DEBTORS_GROUP,SUNDRY_CREDITORS_GROUP));
				$data['company_setting'] 		= $this->company_settings_model->get_company_records();

				if($this->input->is_ajax_request()) 
				{
					$response 										= array();
					$response['code']							= 1; 						
			  	$response['bulk_cash_bank_entry_modal_body'] 	= $this->load->view('cash_bank_entry/ajax/bulk_cash_bank_entry_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('cash_bank_entry/add',$data);
				}
			}
		}
		
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_cash_bank_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{

				$id = $this->input->post('id');
				$old_cash_bank_entry = $this->cash_bank_entry_model->get_single_record($id);

				$this->form_validation->set_rules('voucher_type','Voucher type','required');		
				
			
				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('cash_bank_entry/edit');
				}
				else
				{
					$voucher_type 		= 	$this->input->post('voucher_type');
          $reference_no 		=   $this->cash_bank_entry_model->get_lastest_sequence_number($voucher_type);
					//$voucher_date 	  = 	$this->input->post('voucher_date');
          // $reference_no 	  = 	$this->input->post('reference_no');
          $from_account_id 	= 	$this->input->post('from_account_id');
					$to_account_id 	  = 	$this->input->post('to_account_id');
          $amount 	        = 	$this->input->post('amount');
          $narration 	      = 	$this->input->post('narration');

					$data		=	array(
											"voucher_type"		  => 	$voucher_type,
                      // "voucher_date"		  => 	$voucher_date,
											"reference_no"	    =>	$reference_no,
                      "from_account_id"		=> 	$from_account_id,
                      "to_account_id"		  => 	$to_account_id,
											"amount"	          =>	$amount,
                      "narration"	        =>	$narration
					        );

          $voucher_date 				= $this->input->post('voucher_date');
          if($voucher_date != '')
            $data['voucher_date'] = date('Y-m-d',strtotime($voucher_date));

				
					if($this->cash_bank_entry_model->edit_record($data,$id))
					{
            // delete old transacttion and update the ledger
            $this->transaction_model->delete_record_by_entry_id($old_cash_bank_entry->id,CASH_BANK_MODULE);

            // restore ledger

            if(in_array($voucher_type,array(VOUCHER_TYPE_BANK_RECEIPT,VOUCHER_TYPE_CASH_RECEIPT,VOUCHER_TYPE_CONTRA)))
            {
              /****************************** Start From Ledger ***********************************/

              $from_ledger 						= $this->ledger_model->get_single_record($old_cash_bank_entry->from_account_id);
              $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance - $amount));
              $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);

              /****************************** End From Ledger *************************************/

              /****************************** Start To Ledger *******************************/

              $to_ledger 						= $this->ledger_model->get_single_record($old_cash_bank_entry->to_account_id);
              $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance + $amount));
              $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);

              /****************************** End To Ledger *********************************/
            }
            else
            {
              /****************************** Start From Ledger ***********************************/

              $from_ledger 						= $this->ledger_model->get_single_record($old_cash_bank_entry->from_account_id);
              $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance + $amount));
              $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);

              /****************************** End From Ledger *************************************/

              /****************************** Start To Ledger *******************************/

              $to_ledger 						= $this->ledger_model->get_single_record($old_cash_bank_entry->to_account_id);
              $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance - $amount));
              $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);

              /****************************** End To Ledger *********************************/
            }

            // update the ledgers

            if(in_array($voucher_type,array(VOUCHER_TYPE_BANK_RECEIPT,VOUCHER_TYPE_CASH_RECEIPT,VOUCHER_TYPE_CONTRA)))
            {
              /****************************** Start From Ledger ***********************************/

              $from_ledger 						= $this->ledger_model->get_single_record($from_account_id);
              $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance + $amount));
              $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);

              /****************************** End From Ledger *************************************/

              /****************************** Start To Ledger *******************************/

              $to_ledger 						= $this->ledger_model->get_single_record($to_account_id);
              $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance - $amount));
              $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);

              /****************************** End To Ledger *********************************/
            }
            else
            {
              /****************************** Start From Ledger ***********************************/

              $from_ledger 						= $this->ledger_model->get_single_record($from_account_id);
              $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance - $amount));
              $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);

              /****************************** End From Ledger *************************************/

              /****************************** Start To Ledger *******************************/

              $to_ledger 						= $this->ledger_model->get_single_record($to_account_id);
              $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance + $amount));
              $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);

              /****************************** End To Ledger *********************************/
            }




            /****************************** Add Transaction entry *********************************/

            $transaction_type = '';
            if($voucher_type == VOUCHER_TYPE_BANK_PAYMENT)
              $transaction_type = PAYMENT_TRANSACTION_TYPE;
            else if($voucher_type == VOUCHER_TYPE_BANK_RECEIPT)
              $transaction_type = RECEIPT_TRANSACTION_TYPE;
            else if($voucher_type == VOUCHER_TYPE_CASH_RECEIPT)
              $transaction_type = RECEIPT_TRANSACTION_TYPE;
            else if($voucher_type == VOUCHER_TYPE_CASH_PAYMENT)
              $transaction_type = PAYMENT_TRANSACTION_TYPE;
            else if($voucher_type == VOUCHER_TYPE_CONTRA)
              $transaction_type = CONTRA_TRANSACTION_TYPE;

						$transaction_header = array(
              "entry_id"				=>  $id,
              "module"					=>  CASH_BANK_MODULE,
              "type"						=>	$transaction_type,
              "amount"					=>	$amount,
              "voucher_date"		=>	$voucher_date,
              "from_account"		=>	$from_account_id,
              "to_account"			=>	$to_account_id,
              "reference_no"		=>	$reference_no
            );

            if($transaction_id = $this->transaction_model->add_record($transaction_header))
            {
              if(in_array($voucher_type,array(VOUCHER_TYPE_BANK_RECEIPT,VOUCHER_TYPE_CASH_RECEIPT,VOUCHER_TYPE_CONTRA)))
              {
                $from_transaction_detail = array(
                                  "transaction_id" 	=> $transaction_id,
                                  "voucher_type"		=> 'C',
                                  "ledger_id"				=> $from_account_id,
                                  "dr_amount"				=> $amount
                                );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                $to_transaction_detail = array(
                                "transaction_id" 	=> $transaction_id,
                                "voucher_type"		=> 'D',
                                "ledger_id"				=> $to_account_id,
                                "cr_amount"				=> $amount
                              );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
              }
              else
              {
                $from_transaction_detail = array(
                                  "transaction_id" 	=> $transaction_id,
                                  "voucher_type"		=> 'D',
                                  "ledger_id"				=> $from_account_id,
                                  "dr_amount"				=> $amount
                                );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                $to_transaction_detail = array(
                                "transaction_id" 	=> $transaction_id,
                                "voucher_type"		=> 'C',
                                "ledger_id"				=> $to_account_id,
                                "cr_amount"				=> $amount
                              );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
              }
            }

            /**************************************************************************************/

						if($this->input->is_ajax_request()) 
						{
							$response['code'] = 1;
							$response['message'] = str_replace('_', ' ', $voucher_type).' is updated successfully';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', str_replace('_', ' ', $voucher_type).' is updated successfully');
							redirect('cash_bank_entry','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code']  		= 0;
							$response['message'] 	= str_replace('_', ' ', $voucher_type).' is failed to update';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', str_replace('_', ' ', $voucher_type).' is failed to update');
							redirect('cash_bank_entry','refresh');	
						}
						
					}
				}
				
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($id);
					
					$data['cash_bank_entry'] 	  = $this->cash_bank_entry_model->get_single_record($id);
					$data['cash_bank_entry_id'] = $id;

          if($data['cash_bank_entry']->voucher_type == VOUCHER_TYPE_BANK_PAYMENT)
          {
            $data['from_account_records']  = $this->ledger_model->get_records_by_account_group_id(BANK_ACCOUNT_GROUP_ID);
            $data['to_account_records']    = $this->ledger_model->get_records_by_account_group_id(SUPPLIER_ACCOUNT_GROUP_ID);

          }
					elseif($data['cash_bank_entry']->voucher_type == VOUCHER_TYPE_CASH_PAYMENT)
          {
            $data['from_account_records']  = $this->ledger_model->get_records_by_account_group_id(CASH_ACCOUNT_GROUP_ID);
            $data['to_account_records']    = $this->ledger_model->get_records_by_account_group_id(SUPPLIER_ACCOUNT_GROUP_ID);
          }

          elseif($data['cash_bank_entry']->voucher_type == VOUCHER_TYPE_BANK_RECEIPT)
          {
            $data['from_account_records']  = $this->ledger_model->get_records_by_account_group_id(CUSTOMER_ACCOUNT_GROUP_ID);
            $data['to_account_records']    = $this->ledger_model->get_records_by_account_group_id(BANK_ACCOUNT_GROUP_ID);
          }
          
          elseif($data['cash_bank_entry']->voucher_type == VOUCHER_TYPE_CASH_RECEIPT)
          {
            $data['from_account_records']  = $this->ledger_model->get_records_by_account_group_id(CUSTOMER_ACCOUNT_GROUP_ID);
            $data['to_account_records']    = $this->ledger_model->get_records_by_account_group_id(CASH_ACCOUNT_GROUP_ID);
          }

				  $response 									                  = array();
			  	$response['code']  					                  = 1;
					$response['edit_cash_bank_entry_modal_body']  = $this->load->view('cash_bank_entry/ajax/edit_cash_bank_entry_modal_body',$data,TRUE);
					$response['from_account_records']             = $data['from_account_records'];
          $response['to_account_records']               = $data['to_account_records'];

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['cash_bank_entry'] 		= $this->cash_bank_entry_model->get_single_record($id);

          if($data['cash_bank_entry'] != null)
					{
            $this->load->view('cash_bank_entry/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('cash_bank_entry');
					}
				}
				
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_cash_bank_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$data = array('delete_status' => 1);
			$cash_bank_entry = $this->cash_bank_entry_model->get_single_record($id);

      $voucher_type = $cash_bank_entry->voucher_type;
      $amount = $cash_bank_entry->amount;

			if($this->cash_bank_entry_model->edit_record($data,$id))
			{
        // delete old transacttion and update the ledger
        $this->transaction_model->delete_record_by_entry_id($cash_bank_entry->id,CASH_BANK_MODULE);

        // restore ledger

        if(in_array($voucher_type,array(VOUCHER_TYPE_BANK_RECEIPT,VOUCHER_TYPE_CASH_RECEIPT,VOUCHER_TYPE_CONTRA)))
        {
          /****************************** Start From Ledger ***********************************/

          $from_ledger 						= $this->ledger_model->get_single_record($cash_bank_entry->from_account_id);
          $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance - $amount));
          $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);

          /****************************** End From Ledger *************************************/

          /****************************** Start To Ledger *******************************/

          $to_ledger 						= $this->ledger_model->get_single_record($cash_bank_entry->to_account_id);
          $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance + $amount));
          $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);

          /****************************** End To Ledger *********************************/
        }
        else
        {
          /****************************** Start From Ledger ***********************************/

          $from_ledger 						= $this->ledger_model->get_single_record($cash_bank_entry->from_account_id);
          $from_ledger_data 		  = array('closing_balance' => ($from_ledger->closing_balance + $amount));
          $this->ledger_model->edit_record($from_ledger_data,$from_ledger->id);

          /****************************** End From Ledger *************************************/

          /****************************** Start To Ledger *******************************/

          $to_ledger 						= $this->ledger_model->get_single_record($cash_bank_entry->to_account_id);
          $to_ledger_data 		  = array('closing_balance' => ($to_ledger->closing_balance - $amount));
          $this->ledger_model->edit_record($to_ledger_data,$to_ledger->id);

          /****************************** End To Ledger *********************************/
        }

				$this->session->set_flashdata('success', str_replace('_', ' ', $cash_bank_entry->voucher_type).' is deleted successfully.');
				redirect('cash_bank_entry','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', str_replace('_', ' ', $cash_bank_entry->voucher_type).' failed to delete.');
				redirect('cash_bank_entry','refresh');
			}
		}
	}

  public function export()
  {
      // Get parameters from query string
      $id = explode(',', $this->input->get('data'));
     
      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'a.delete_status = 0';

      // Add conditions based on parameters
      if (!empty($id)) {
          $whereConditions[] = 'a.id IN (' . implode(',', $id) . ')';
      }

     
      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }

      $query = $this->db->query('SELECT 
                                  REPLACE(a.voucher_type, "_", " ") as "Voucher Type",
                                  ledger_from.title as "From Account Name",
                                  ledger_to.title as "To Account Name",
                                  a.amount as "Amount"
                              FROM 
                                  cash_bank_entry a
                              LEFT JOIN ledger ledger_from ON ledger_from.id = a.from_account_id
                              LEFT JOIN ledger ledger_to ON ledger_to.id = a.to_account_id
                              ' . $whereClause . '
                              ORDER BY a.voucher_type ASC');

      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');

      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);

      // Set filename for download
      $filename = 'CASH_BANK_ENTRY.csv';

      // Download the CSV file
      force_download($filename, $data);
  }


	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->cash_bank_entry_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
        $table_footer = '</div>';
        $table_body   = '';

				// Edit Button        
        if($this->permission_model->has_permission('edit_cash_bank_entry'))
				{	
					$table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('cash_bank_entry_edit').'" class="btn btn-info btn-xs edit_cash_bank_entry_modal" data-cash_bank_entry_id="'.base64_encode($item->id).'">
                              <i class="fas fa-edit"></i> Edit
                            </a>        
		                      ';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_cash_bank_entry'))
				{	
					$table_body .= '  <a href="#"  data-toggle="modal" data-target="#delete_cash_bank_entry" data-tt="tooltip" title="'.$this->lang->line('cash_bank_entry_delete').'" class="btn btn-danger btn-xs delete_cash_bank_entry" data-cash_bank_entry_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i> Delete
		                        </a>
													';
				}


        $select_html = '<input type="checkbox" class="single_cash_bank_entry" value="'.$item->id.'" data-id="'.$item->id.'"><input type="hidden" name="id" id="id" data-id="'.$item->id.'" value="'.$item->id.'">';
			

				/* End Action column buttons*/

        $row = array();
       
        $row[] = $select_html;
        $row[] = $item->reference_no;
        $row[] = str_replace('_', ' ', $item->voucher_type);
        $row[] = $item->from_account_name;
        $row[] = $item->to_account_name;
        $row[] = $item->amount;
        
      	$row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->cash_bank_entry_model->count_all(),
                    "recordsFiltered" => $this->cash_bank_entry_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function cash_bank_entry_delete_confirmation()
  {
  	$cash_bank_entry_id 					= $this->input->post('cash_bank_entry_id');
  	$data['cash_bank_entry'] 		= $this->cash_bank_entry_model->get_single_record($cash_bank_entry_id);

  	$response 					= array();
  	$response['cash_bank_entry_delete_modal_body'] 	= $this->load->view('cash_bank_entry/ajax/cash_bank_entry_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/

  function get_ledger_accounts($voucher_type = null)
	{
		if($voucher_type == null && !in_array($voucher_type,array(VOUCHER_TYPE_BANK_PAYMENT,VOUCHER_TYPE_BANK_RECEIPT,VOUCHER_TYPE_CASH_PAYMENT,VOUCHER_TYPE_CASH_RECEIPT)))
		{
			if(!$this->input->is_ajax_request()) 
			{
				$this->load->view('errors/html/error_restricted'); 
			}
			else
			{
				$response['code']	 		= 0;
				$response['message'] 	= 'You are trying to access broken url';
				echo json_encode($response);
			}
		}
		else
		{
      $response = array();

			if($voucher_type == VOUCHER_TYPE_BANK_PAYMENT)
			{
				$response['code'] = 1;
        $response['from_account_record_type'] = BANK_ACCOUNT_GROUP;
				$response['to_account_record_type']   = SUNDRY_CREDITORS_GROUP;
				$response['from_account_records']  = $this->ledger_model->get_records_by_account_group_id(BANK_ACCOUNT_GROUP_ID);
        $response['to_account_records']    = $this->ledger_model->get_records_by_account_group_id(SUPPLIER_ACCOUNT_GROUP_ID);
			}

      elseif($voucher_type == VOUCHER_TYPE_CASH_PAYMENT)
			{
				$response['code'] = 1;
        $response['from_account_record_type'] = CASH_GROUP;
				$response['to_account_record_type']   = SUNDRY_CREDITORS_GROUP;
				$response['from_account_records']  = $this->ledger_model->get_records_by_account_group_id(CASH_ACCOUNT_GROUP_ID);
        $response['to_account_records']    = $this->ledger_model->get_records_by_account_group_id(SUPPLIER_ACCOUNT_GROUP_ID);
			}

      elseif($voucher_type == VOUCHER_TYPE_BANK_RECEIPT)
			{
				$response['code'] = 1;
        $response['from_account_record_type'] = SUNDRY_DEBTORS_GROUP;
				$response['to_account_record_type']   = BANK_ACCOUNT_GROUP;
				$response['from_account_records']  = $this->ledger_model->get_records_by_account_group_id(CUSTOMER_ACCOUNT_GROUP_ID);
        $response['to_account_records']    = $this->ledger_model->get_records_by_account_group_id(BANK_ACCOUNT_GROUP_ID);
			}
      
			elseif($voucher_type == VOUCHER_TYPE_CASH_RECEIPT)
			{
				$response['code'] = 1;
        $response['from_account_record_type'] = SUNDRY_DEBTORS_GROUP;
				$response['to_account_record_type']   = CASH_GROUP;
				$response['from_account_records']  = $this->ledger_model->get_records_by_account_group_id(CUSTOMER_ACCOUNT_GROUP_ID);
        $response['to_account_records']    = $this->ledger_model->get_records_by_account_group_id(CASH_ACCOUNT_GROUP_ID);
			}

      elseif($voucher_type == VOUCHER_TYPE_CONTRA)
			{
				$response['code'] = 1;
        $response['from_account_record_type'] = BANK_ACCOUNT_GROUP;
				$response['to_account_record_type']   = BANK_ACCOUNT_GROUP;
				$response['from_account_records']     = $this->ledger_model->get_records_by_account_group_id(BANK_ACCOUNT_GROUP_ID);
        $response['to_account_records']       = $this->ledger_model->get_records_by_account_group_id(BANK_ACCOUNT_GROUP_ID);
			}
      echo json_encode($response);
		}
	}
		
}
