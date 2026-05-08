<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	// public function index()
	// {
	// 	if(!$this->permission_model->has_permission('list_expense_category'))
	// 	{
	// 		$this->load->view('errors/html/error_restricted'); 
	// 	}
	// 	else
	// 	{
	// 		$data['expense_category'] = $this->expense_category_model->get_records();

	// 		$log_data = array(
	// 		                  "user_id"     => $this->session->userdata("user_id"),
	// 		                  "module"      => "expense_category",
	// 		                  "user_action" => 0,
	// 		                  "description"	=> "User has viewed list of expense category."
	// 		                );

	// 		$this->log_data_model->add_record($log_data);

	// 		$this->load->view('expense_category/list',$data);
	// 	}
	// }

	public function add()   
	{
	  
		// if(!$this->permission_model->has_permission('add_expense_transaction') && !$this->permission_model->has_permission('add_sale_transaction'))
		// {
		// 	$this->load->view('errors/html/error_restricted'); 
		// }
		// else
		// {
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
					$entry_id 						= $this->input->post('entry_id');
					$module 							= $this->input->post('module'); //P
					$type 								= $this->input->post('transaction_type'); //P
					$amount								= $this->input->post('transaction_amount');
					//$voucher_date 				= date('Y-m-d');
					$mode									= $this->input->post('payment_mode');
					$cheque_no						= $this->input->post('cheque_no');
					$credit_card_no				= $this->input->post('credit_card_no');
					$from_account					= $this->input->post('from_account');
					$to_account						= $this->input->post('to_account');
					$reference_no					= $this->input->post('reference_no');
					$warehouse_id= $this->input->post('warehouse_id');
					
					$transaction_header		=	array(
																				"entry_id"				    =>  $entry_id,
																				"module"					=>  $module,
																				"type"						=>	$type,
																				"amount"					=>	$amount,
																				// "voucher_date"		    =>	$voucher_date,
																				"mode"						=>	$mode,
																				"cheque_no"				    =>	$cheque_no,
																				"credit_card_no"	        =>	$credit_card_no,
																				"from_account"	         	=>	$from_account,
																				"to_account"		    	=>	$to_account,
																				"reference_no"	        	=>	$reference_no,
																				"warehouse_id"              =>  $warehouse_id,
																			);

          $voucher_date 				= $this->input->post('voucher_date');
          if($voucher_date != '')
            $transaction_header['voucher_date'] = date('Y-m-d',strtotime($voucher_date));

					// being transaction
					$this->db->trans_begin();

					if($id = $this->transaction_model->add_record($transaction_header))
					{

						// Transaction header and detail entry

						$entered_transaction = $this->transaction_model->get_single_record($id);

						/************************************ From ledger *********************************/

						if($from_account != '')
						{
							$from_ledger 		= $this->ledger_model->get_single_record($from_account);

							$from_transaction_detail = array(
																								"transaction_id" 	=> $id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> $from_account,
																								"dr_amount"				=> $amount
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

							// update from ledger account
							$updated_from_ledger 	= array('closing_balance' => ($from_ledger->closing_balance - $amount));
							$this->ledger_model->edit_record($updated_from_ledger,$from_account);	
						}
						
						/*********************************************************************************/

						/************************************ To ledger **********************************/

						if($to_account != '')
						{
							$to_ledger 			= $this->ledger_model->get_single_record($to_account);
							$to_transaction_detail = array(
																							"transaction_id" 	=> $id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> $to_account,
																							"cr_amount"				=> $amount
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
							
							// update from ledger account
							if($type == CONTRA_TRANSACTION_TYPE || $type == RECEIPT_TRANSACTION_TYPE || $type == INCREASE_ADJUSTMENT_TRANSACTION_TYPE)
							{
								$updated_to_ledger 		= array('closing_balance' => ($to_ledger->closing_balance + $amount));
								$this->ledger_model->edit_record($updated_to_ledger,$to_account);		
							}
							else
							{
								$updated_to_ledger 		= array('closing_balance' => ($to_ledger->closing_balance - $amount));
								$this->ledger_model->edit_record($updated_to_ledger,$to_account);			
							}
						}

						/********************************************************************************/

						$log_data = array(
										"user_id" 			=> $this->session->userdata("user_id"),
										"module"				=> "transaction",
										"entry_id"			=> $id,
										"user_action"		=> 1,
										"data"					=> json_encode((array)$entered_transaction),
										"description" 	=> 'Transaction entry of '.$amount.' is successfully saved.'
									);
						$this->log_data_model->add_record($log_data);

						// commit transaction
						$this->db->trans_commit();
					
						if($this->input->is_ajax_request())
						{
							$response 					= array();
							$response['code'] 			= 1;
							$response['message']		= 'Transaction entry of '.$amount.' is successfully saved.';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Transaction entry of '.$amount.' is successfully saved.');
							redirect('expense_category','refresh');
						}
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						if($this->input->is_ajax_request())
						{
							$response 												= array();
							$response['code'] 								= 0;
							$response['message']							= 'Transaction entry of '.$amount.' is failed saved.';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Transaction entry of '.$amount.' is failed saved.');
							redirect('expense_category','refresh');
						}
					}
			}
			else
			{
				$data['account_groups']	= $this->account_group_model->get_records("Expense");
				$this->load->view('expense_category/add',$data);
			}
		// }
	}

	public function delete()
	{
		$response = array();

		// if(!$this->permission_model->has_permission('delete_transaction'))
		// {
		// 		$this->load->view('errors/html/error_restricted'); 			
		// }
		// else
		// {
			$transaction_id 	= $this->input->post('transaction_id');
			$transaction 			= $this->transaction_model->get_single_record($transaction_id);

			if($this->transaction_model->delete_record($transaction_id))
			{
				
				// Reverse amount from From Ledger
				if($transaction->from_account != '')
				{
					$from_ledger 					= $this->ledger_model->get_single_record($transaction->from_account);
					$updated_from_ledger	= array('closing_balance' => $from_ledger->closing_balance+$transaction->amount);
					$this->ledger_model->edit_record($updated_from_ledger,$from_ledger->id);				
				}

				// Reverse amount from To Ledger
				if($transaction->to_account != '')
				{
					$to_ledger 				= $this->ledger_model->get_single_record($transaction->to_account);

					if($transaction->type == CONTRA_TRANSACTION_TYPE || $transaction->type == RECEIPT_TRANSACTION_TYPE || $transaction->type == INCREASE_ADJUSTMENT_TRANSACTION_TYPE)
					{
						$updated_to_ledger 		= array('closing_balance' => ($to_ledger->closing_balance - $transaction->amount));
						$this->ledger_model->edit_record($updated_to_ledger,$to_ledger->id);		
					}
					else
					{
						$updated_to_ledger 		= array('closing_balance' => ($to_ledger->closing_balance + $transaction->amount));
						$this->ledger_model->edit_record($updated_to_ledger,$to_ledger->id);		
					}
				}

				$response['code'] = 1;
				$response['message'] = "Successfully deleted.";
			}
			else
			{
				$response['code'] = 0;
				$response['message'] = "Failed to delete";	
			}

			echo json_encode($response);
		// }
	}
		
}
