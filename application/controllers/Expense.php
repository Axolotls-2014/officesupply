<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_expense'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['expense'] 						= $this->expense_model->get_records();

			$data['total_expense']			= $this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, EXPENSE_TRANSACTION_TYPE);
			$data['total_amount_paid']	= $this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);

// 			$log_data = array(
// 			                  "user_id"     => $this->session->userdata("user_id"),
// 			                  "module"      => "expense",
// 			                  "user_action" => 0,
// 			                  "description"	=> "User has viewed list of expense."
// 			                );

// 			$this->log_data_model->add_record($log_data); 

			$this->load->view('expense/list',$data);
		}
	}
	private function do_upload($image)
	{
		if(!empty($image))
		{
			$type 	= explode('.',$image["name"]);
			$type 	= $type[count($type)-1];
			$name 	= uniqid(rand()).'.'.$type;
			$url 	= './assets/expense/'.$name;

			if(in_array($type,array("pdf","doc","jpg","jpeg","gif","png")))
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

// 	public function add()
// 	{
// 	      $user_id  = $this->session->userdata('user_id');
//           $branchid = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
//           $warehouse_id = $branchid->branch_id ?? null;
// 		if(!$this->permission_model->has_permission('add_expense'))
// 		{
// 			$this->load->view('errors/html/error_restricted'); 
// 		}
// 		else
// 		{

// 			if($this->input->server('REQUEST_METHOD') === 'POST')
// 			{
// 				$this->form_validation->set_rules('date','Expense date','required');		
// 				$this->form_validation->set_rules('amount','Expense amount','required');
// 				$this->form_validation->set_rules('cgst','CGST','required');
// 				$this->form_validation->set_rules('sgst','SGST','required');
// 				$this->form_validation->set_rules('igst','IGST','required');
// 				$this->form_validation->set_rules('total_amount','Expense total amount','required');
// 				// $this->form_validation->set_rules('itc','ITC','required');

// 				if($this->form_validation->run()==FALSE)
// 				{
// 					$data['suppliers'] 						= $this->supplier_model->get_records();
// 					$data['expense_categories'] 	= $this->expense_category_model->get_records();
// 					$data['states']								= $this->utility_model->get_states(101);
// 					$this->load->view('expense/add',$data);
// 				}
// 				else
// 				{
// 					$supplier_id  					=   $this->input->post('supplier_id');
// 					$supplier_name  				=   $this->input->post('supplier_name');
// 					$expense_category_id  	=   $this->input->post('expense_category_id');
// 					$date  									=   date('Y-m-d', strtotime($this->input->post('date')));
// 					$amount 	 							=   $this->input->post('amount');
// 					$cgst_tax 							=   $this->input->post('cgst');
// 					$sgst_tax 							= 	$this->input->post('sgst');
// 					$igst_tax 							= 	$this->input->post('igst');
// 					$total_amount  					=   $this->input->post('total_amount');
// 					$remarks  							=   $this->input->post('remarks');
// 					$document 							=		$this->input->post('attached_files');

// 					$expense_share 					=		$this->input->post('expense_share');
// 					$expense_share_state_id =		$this->input->post('expense_share_state_id');
// 					$expense_share_state_id =		(!empty($expense_share_state_id)) ? implode(",", $expense_share_state_id) : '';

// 					$user_id  							=   $this->session->userdata('user_id');

// 					if($this->input->post('itc') != null)
// 						$itc =  $this->input->post('itc');
// 					else
// 						$itc = 	0;

// 					$data					=	array(
// 																"supplier_id"							=>	$supplier_id,
// 																"supplier_name"						=>	$supplier_name,
// 																"expense_category_id"			=>	$expense_category_id,
// 																"date"										=>	$date,
// 																"amount"									=>	$amount,
// 																"cgst_tax"								=>	$cgst_tax,
// 																"sgst_tax"								=>	$sgst_tax,
// 																"igst_tax"								=>  $igst_tax ,
// 																"total_amount"						=>	$total_amount,
// 																"remarks"									=>	$remarks,
// 																"itc"											=>	$itc ,
// 																"document"								=> 	$document,
// 																"expense_share"						=> 	$expense_share,
// 																"expense_share_state_id"	=> 	$expense_share_state_id,
// 																"user_id"									=>  $user_id,
// 																"warehouse_id"    =>  $warehouse_id
// 															);

// 					// being transaction
// 					$this->db->trans_begin();

// 					if($id = $this->expense_model->add_record($data))
// 					{

// 						$entered_expense 	= $this->expense_model->get_single_record($id);
// 						$expense_category = $this->expense_category_model->get_single_record($expense_category_id);
// 						$supplier 				= $this->supplier_model->get_single_record($supplier_id);

// 						// update the ledgers
							
// 						/****************************** Start Purchase Ledger ***********************************/

// 						if($expense_category_id != "")
// 						{
// 							$expense_ledger 				= $this->ledger_model->get_single_record($expense_category->ledger_id);
// 							$updated_expense_ledger = array('closing_balance' => ($expense_ledger->closing_balance + $total_amount));
// 							$this->ledger_model->edit_record($updated_expense_ledger,$expense_category->ledger_id);
// 						}

// 						/****************************** End Purchase Ledger ***********************************/

// 						/****************************** Start Supplier Ledger ***********************************/

// 						$supplier_ledger_id = '';

// 						if($supplier_id != '')
// 						{
// 							$supplier_ledger 				= $this->ledger_model->get_single_record($supplier->ledger_id);
// 							$supplier_ledger_id 		= $supplier_ledger->id;
// 							$updated_supplier_ledger= array('closing_balance' => ($supplier_ledger->closing_balance + $total_amount));
// 							$this->ledger_model->edit_record($updated_supplier_ledger,$supplier_ledger->id);
// 						}	
// 						else
// 						{
// 							$supplier_ledger 				= $this->ledger_model->get_single_record(OTHER_SUPPLIER);
// 							$supplier_ledger_id 		= $supplier_ledger->id;
// 							$updated_supplier_ledger= array('closing_balance' => ($supplier_ledger->closing_balance + $total_amount));
// 							$this->ledger_model->edit_record($updated_supplier_ledger,$supplier_ledger->id);
// 						}

// 						/****************************** End Supplier Ledger ***********************************/

// 						/****************************** Add Transaction entry *********************************/

// 						$transaction_header = array(
// 																				"entry_id"				  =>  $id,
// 																				"module"					=>  EXPENSE_MODULE,
// 																				"type"						=>	EXPENSE_TRANSACTION_TYPE,
// 																				"amount"					=>	$total_amount,
// 																				"voucher_date"		=>	$date,
// 																				"from_account"		=>	$supplier_ledger_id,
// 																				"to_account"			=>	$expense_ledger->id,
// 																				"reference_no"		=>	$remarks,
// 																			    "warehouse_id"    =>  $warehouse_id
// 																			);
// 						if($transaction_id = $this->transaction_model->add_record($transaction_header))
// 						{
							
// 							$from_transaction_detail = array(
// 																								"transaction_id" 	=> $transaction_id,
// 																								"voucher_type"		=> 'C',
// 																								"ledger_id"				=> $supplier_ledger_id,
// 																								"dr_amount"				=> $total_amount
// 																							);
// 							// transaction detail record
// 							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

// 							$to_transaction_detail = array(
// 																							"transaction_id" 	=> $transaction_id,
// 																							"voucher_type"		=> 'D',
// 																							"ledger_id"				=> $expense_ledger->id,
// 																							"cr_amount"				=> $total_amount
// 																						);
// 							// transaction detail record
// 							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
// 						}

// 						/**************************************************************************************/			

// 						$log_data = array(
// 											"user_id" 		=> $this->session->userdata("user_id"),
// 											"module"			=> "expense",
// 											"entry_id"		=> $id,
// 											"user_action"	=> 1,
// 											"data"				=> json_encode((array)$entered_expense),
// 											"description" => $expense_category->name.' successfully added.'
// 										);
// 						$this->log_data_model->add_record($log_data);
						
// 						$this->attachment_model->remove_attachment_by_session();

// 						// save expense items into tables

// 						$expense_items 						= $this->input->post('expense_items');
// 						$expense_items_array 			= explode("|", $this->input->post('expense_items'));
						
// 						for ($i=0; $i < sizeof($expense_items_array) ; $i++) { 
									
// 							$temp_expense_item = (array)json_decode($expense_items_array[$i]);
// 							$temp_expense_item['expense_id']  = $id;
							
// 							$this->expense_item_model->add_record($temp_expense_item);
// 						}

// 						// commit transaction
// 						$this->db->trans_commit();

// 						$this->session->set_flashdata('success', $expense_category->name.' is added successfully');
// 						redirect('expense','refresh');
// 					}
// 					else
// 					{
// 						// rollback transaction
// 						$this->db->trans_rollback();
						
// 						$this->session->set_flashdata('failure', $expense_category->name.' is  failed to add');
// 						redirect('expense','refresh');
// 					}
// 				}
// 			}
// 			else
// 			{
// 				$data['suppliers'] 						= $this->supplier_model->get_records();
// 				$data['expense_categories'] 	= $this->expense_category_model->get_records();
// 				$data['items'] 								= $this->item_model->get_records();
// 				$data['account_groups']				= $this->account_group_model->get_records("Expense");
// 				$data['states']								= $this->utility_model->get_states(101);
// 				$this->load->view('expense/add',$data);
// 			}
// 		}
		
// 	}
	public function add()
	{
	      $user_id  = $this->session->userdata('user_id');
          $branchid = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
          $warehouse_id = $branchid->branch_id ?? null;
          
		if(!$this->permission_model->has_permission('add_expense'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{

			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('date','Expense date','required');		
				$this->form_validation->set_rules('amount','Expense amount','required');
				$this->form_validation->set_rules('cgst','CGST','required');
				$this->form_validation->set_rules('sgst','SGST','required');
				$this->form_validation->set_rules('igst','IGST','required');
				$this->form_validation->set_rules('total_amount','Expense total amount','required');
				// $this->form_validation->set_rules('itc','ITC','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['suppliers'] 						= $this->supplier_model->get_records();
					$data['expense_categories'] 	= $this->expense_category_model->get_records();
					$data['states']								= $this->utility_model->get_states(101);
					$this->load->view('expense/add',$data);
				}
				else
				{
					$supplier_id  					=   $this->input->post('supplier_id');
					$supplier_name  				=   $this->input->post('supplier_name');
					$expense_category_id  	=   $this->input->post('expense_category_id');
					$date  									=   date('Y-m-d', strtotime($this->input->post('date')));
					$amount 	 							=   $this->input->post('amount');
					$cgst_tax 							=   $this->input->post('cgst');
					$sgst_tax 							= 	$this->input->post('sgst');
					$igst_tax 							= 	$this->input->post('igst');
					$total_amount  					=   $this->input->post('total_amount');
					$remarks  							=   $this->input->post('remarks');
					$document 							=		$this->input->post('attached_files');

					$expense_share 					=		$this->input->post('expense_share');
					$expense_share_state_id =		$this->input->post('expense_share_state_id');
					$expense_share_state_id =		(!empty($expense_share_state_id)) ? implode(",", $expense_share_state_id) : '';

					$user_id  							=   $this->session->userdata('user_id');

					if($this->input->post('itc') != null)
						$itc =  $this->input->post('itc');
					else
						$itc = 	0;

					$data					=	array(
																"supplier_id"							=>	$supplier_id,
																"supplier_name"						=>	$supplier_name,
																"expense_category_id"			=>	$expense_category_id,
																"date"										=>	$date,
																"amount"									=>	$amount,
																"cgst_tax"								=>	$cgst_tax,
																"sgst_tax"								=>	$sgst_tax,
																"igst_tax"								=>  $igst_tax ,
																"total_amount"						=>	$total_amount,
																"remarks"									=>	$remarks,
																"itc"											=>	$itc ,
																"document"								=> 	$document,
																"expense_share"						=> 	$expense_share,
																"expense_share_state_id"	=> 	$expense_share_state_id,
																	"user_id"									=>  $user_id,
																"warehouse_id"    =>  $warehouse_id
															);

					// being transaction
					$this->db->trans_begin();

					if($id = $this->expense_model->add_record($data))
					{

						$entered_expense 	= $this->expense_model->get_single_record($id);
						$expense_category = $this->expense_category_model->get_single_record($expense_category_id);
						$supplier 				= $this->supplier_model->get_single_record($supplier_id);

						// update the ledgers
							
						/****************************** Start Purchase Ledger ***********************************/

						if($expense_category_id != "")
						{
							$expense_ledger 				= $this->ledger_model->get_single_record($expense_category->ledger_id);
							$updated_expense_ledger = array('closing_balance' => ($expense_ledger->closing_balance + $total_amount));
							$this->ledger_model->edit_record($updated_expense_ledger,$expense_category->ledger_id);
						}

						/****************************** End Purchase Ledger ***********************************/

						/****************************** Start Supplier Ledger ***********************************/

						$supplier_ledger_id = '';

						if($supplier_id != '')
						{
							$supplier_ledger 				= $this->ledger_model->get_single_record($supplier->ledger_id);
							$supplier_ledger_id 		= $supplier_ledger->id;
							$updated_supplier_ledger= array('closing_balance' => ($supplier_ledger->closing_balance + $total_amount));
							$this->ledger_model->edit_record($updated_supplier_ledger,$supplier_ledger->id);
						}	
						else
						{
							$supplier_ledger 				= $this->ledger_model->get_single_record(OTHER_SUPPLIER);
							$supplier_ledger_id 		= $supplier_ledger->id;
							$updated_supplier_ledger= array('closing_balance' => ($supplier_ledger->closing_balance + $total_amount));
							$this->ledger_model->edit_record($updated_supplier_ledger,$supplier_ledger->id);
						}

						/****************************** End Supplier Ledger ***********************************/

						/****************************** Add Transaction entry *********************************/

						$transaction_header = array(
																				"entry_id"				=>  $id,
																				"module"					=>  EXPENSE_MODULE,
																				"type"						=>	EXPENSE_TRANSACTION_TYPE,
																				"amount"					=>	$total_amount,
																				"voucher_date"		=>	$date,
																				"from_account"		=>	$supplier_ledger_id,
																				"to_account"			=>	$expense_ledger->id,
																				"reference_no"		=>	$remarks,
																				  "warehouse_id"    =>  $warehouse_id
																			);
						if($transaction_id = $this->transaction_model->add_record($transaction_header))
						{
							
							$from_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> $supplier_ledger_id,
																								"dr_amount"				=> $total_amount
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

							$to_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> $expense_ledger->id,
																							"cr_amount"				=> $total_amount
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
						}

						/**************************************************************************************/			

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "expense",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"				=> json_encode((array)$entered_expense),
											"description" => $expense_category->name.' successfully added.'
										);
						$this->log_data_model->add_record($log_data);
						
						$this->attachment_model->remove_attachment_by_session();

						// save expense items into tables

						$expense_items 						= $this->input->post('expense_items');
						$expense_items_array 			= explode("|", $this->input->post('expense_items'));
						
						for ($i=0; $i < sizeof($expense_items_array) ; $i++) { 
									
							$temp_expense_item = (array)json_decode($expense_items_array[$i]);
							$temp_expense_item['expense_id']  = $id;
							
							$this->expense_item_model->add_record($temp_expense_item);
						}

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success', $expense_category->name.' is added successfully');
						redirect('expense','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();
						
						$this->session->set_flashdata('failure', $expense_category->name.' is  failed to add');
						redirect('expense','refresh');
					}
				}
			}
			else
			{
				$data['suppliers'] 						= $this->supplier_model->get_records();
				$data['expense_categories'] 	= $this->expense_category_model->get_records();
				$data['items'] 								= $this->item_model->get_records();
				$data['account_groups']				= $this->account_group_model->get_records("Expense");
				$data['states']								= $this->utility_model->get_states(101);
				$this->load->view('expense/add',$data);
			}
		}
		
	}
	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_expense'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');
				$old_expense = $this->expense_model->get_single_record($id);

				$this->form_validation->set_rules('date','Expense date','required');		
				$this->form_validation->set_rules('amount','Expense amount','required');
				$this->form_validation->set_rules('cgst','CGST','required');
				$this->form_validation->set_rules('sgst','SGST','required');
				$this->form_validation->set_rules('igst','IGST','required');
				$this->form_validation->set_rules('total_amount','Expense total amount','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['expense']					    = $this->expense_model->get_single_record($id);
					$data['suppliers'] 						= $this->supplier_model->get_records();
					$data['states']								= $this->utility_model->get_states(101);
					$data['expense_categories'] 	= $this->expense_category_model->get_records();
				
					$this->load->view('expense/edit',$data);
				}
				else
				{
					
					$supplier_id  					=   $this->input->post('supplier_id');
					$supplier_name  				=   $this->input->post('supplier_name');
					$expense_category_id  	=   $this->input->post('expense_category_id');
					$date  									=   date('Y-m-d', strtotime($this->input->post('date')));
					$amount 	 							=   $this->input->post('amount');
					$cgst_tax 							=   $this->input->post('cgst');
					$sgst_tax 							= 	$this->input->post('sgst');
					$igst_tax 							= 	$this->input->post('igst');
					$total_amount  					=   $this->input->post('total_amount');
					$remarks  							=   $this->input->post('remarks');
					$document 							= 	$this->input->post('attached_files');
					$expense_share 					=		$this->input->post('expense_share');
					$expense_share_state_id =		$this->input->post('expense_share_state_id');
					$expense_share_state_id =		(!empty($expense_share_state_id)) ? implode(",", $expense_share_state_id) : '';

					if($this->input->post('itc') != null)
						$itc =  $this->input->post('itc');
					else
						$itc = 	0;

					$data					=	array(
														"supplier_id"							=>	$supplier_id,
														"supplier_name"						=>	$supplier_name,
														"expense_category_id"			=>	$expense_category_id,
														"date"										=>	$date,
														"amount"									=>	$amount,
														"cgst_tax"								=>	$cgst_tax,
														"sgst_tax"								=>	$sgst_tax,
														"igst_tax"								=>  $igst_tax ,
														"total_amount"						=>	$total_amount,
														"remarks"									=>	$remarks,
														"itc"											=>	$itc,
														"document"								=>	$document,
														"expense_share"						=> 	$expense_share,
														"expense_share_state_id"	=> 	$expense_share_state_id
													);

					// being transaction
					$this->db->trans_begin();	

					if($this->expense_model->edit_record($data,$id))
					{
						$entered_expense 	= $this->expense_model->get_single_record($id);
						$expense_category = $this->expense_category_model->get_single_record($expense_category_id);
						$supplier 				= $this->supplier_model->get_single_record($supplier_id);


						// update the ledgers
							
						/****************************** Start Purchase Ledger ***********************************/

						if($expense_category_id != "")
						{
							$expense_ledger 				= $this->ledger_model->get_single_record($expense_category->ledger_id);
							$updated_expense_ledger = array('closing_balance' => ($expense_ledger->closing_balance + $total_amount - $old_expense->total_amount));
							$this->ledger_model->edit_record($updated_expense_ledger,$expense_category->ledger_id);
						}

						/****************************** End Purchase Ledger ***********************************/

						/****************************** Start Supplier Ledger ***********************************/

						$supplier_ledger_id = '';

						if($supplier_id != "")
						{
							$supplier_ledger 				= $this->ledger_model->get_single_record($supplier->ledger_id);
							$supplier_ledger_id 		= $supplier_ledger->id;
							$updated_supplier_ledger= array('closing_balance' => ($supplier_ledger->closing_balance + $total_amount - $old_expense->total_amount));
							$this->ledger_model->edit_record($updated_supplier_ledger,$supplier_ledger->id);
						}	
						else
						{
							$supplier_ledger 				= $this->ledger_model->get_single_record(OTHER_SUPPLIER);
							$supplier_ledger_id 		= $supplier_ledger->id;
							$updated_supplier_ledger= array('closing_balance' => ($supplier_ledger->closing_balance + $total_amount - $old_expense->total_amount));
							$this->ledger_model->edit_record($updated_supplier_ledger,$supplier_ledger->id);
						}

						/****************************** End Supplier Ledger ***********************************/

						/****************************** Add Transaction entry *********************************/

						$this->transaction_model->delete_record_by_entry_id($entered_expense->id,EXPENSE_MODULE,EXPENSE_TRANSACTION_TYPE);

						$transaction_header = array(
																				"entry_id"				=>  $id,
																				"module"					=>  EXPENSE_MODULE,
																				"type"						=>	EXPENSE_TRANSACTION_TYPE,
																				"amount"					=>	$total_amount,
																				"voucher_date"		=>	$date,
																				"from_account"		=>	$supplier_ledger_id,
																				"to_account"			=>	$expense_ledger->id,
																				"reference_no"		=>	$remarks
																			);
						if($transaction_id = $this->transaction_model->add_record($transaction_header))
						{
							
							$from_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> $supplier_ledger_id,
																								"dr_amount"				=> $total_amount
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);


							$to_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> $expense_ledger->id,
																							"cr_amount"				=> $total_amount
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
						}

						/**************************************************************************************/			

						$log_data = array(
															"user_id" 		=> $this->session->userdata("user_id"),
															"module"			=> "expense",
															"user_action"	=> 2,
															"b_data"			=> json_encode((array)$old_expense),
															"data"				=> json_encode((array)$entered_expense),
															"description" => $expense_category->name.' successfully updated.'
														);
						$this->log_data_model->add_record($log_data);

						
						$this->attachment_model->remove_attachment_by_session();	


						$this->expense_item_model->remove_expense_item_records($id);


						$expense_items 						= $this->input->post('expense_items');
						$expense_items_array 			= explode("|", $this->input->post('expense_items'));
						
						for ($i=0; $i < sizeof($expense_items_array) ; $i++) { 
									
							$temp_expense_item = (array)json_decode($expense_items_array[$i]);
							$temp_expense_item['expense_id']  = $id;
							
							$this->expense_item_model->add_record($temp_expense_item);
						}

						// commit transaction
						$this->db->trans_commit();



						$this->session->set_flashdata('success', $expense_category->name.' is updated successfully');
						redirect('expense','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						$this->session->set_flashdata('failure', $expense_category->name.' is failed to update');
						redirect('expense','refresh');
					}
				}
			}
			else
			{
				$id = base64_decode($id);
				
				if($id != null)
				{
					$data['expense']			 			= $this->expense_model->get_single_record($id);

					$data['expense_items'] = $this->expense_item_model->get_records($id);

					// echo '<pre>';
					// print_r($this->expense_item_model->get_records($id));exit;
					
					if($data['expense'] != null)
					{
						$data['expense_categories'] 	= $this->expense_category_model->get_records();
						$data['suppliers'] 						= $this->supplier_model->get_records();
						$data['supplier'] 						= $this->supplier_model->get_single_record($data['expense']->supplier_id);
						$data['items'] 								= $this->item_model->get_records();
						$data['company_setting']			= $this->company_settings_model->get_company_records();
						$data['states']								= $this->utility_model->get_states(101);
						$this->load->view('expense/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('expense','refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('expense','refresh');
				}
			}
		}
	}

	public function view($id = null)
	{
		if(!$this->permission_model->has_permission('view_expense'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if (!$this->input->is_ajax_request()) 
			{
				if($id != null)
				{
					$id 									= base64_decode($id);

					$data['expense']	= $this->expense_model->get_single_record($id);
					if($data['expense'] != null)
					{
							$data['transactions']	= $this->transaction_model->get_records_entry_id_and_module_wise($id,EXPENSE_MODULE);
							$data['expense_items'] = $this->expense_item_model->get_records($id);
							$this->load->view('expense/view',$data);
					}
					else
					{	
						$this->session->set_flashdata('failure', "You are trying to access invalid URL.");
						redirect('expense','refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('failure', "You are trying to access invalid URL.");
					redirect('expense','refresh');
				}
			}
			else
			{
				$response = array();

				$expense_view_data['expense']					= $this->expense_model->get_single_record($id);
				$expense_view_data['paid_amount'] 		= $this->transaction_model->get_total_transaction_amount($id,EXPENSE_MODULE,PAYMENT_TRANSACTION_TYPE);
				
				$response['expense_view'] 				= $this->load->view('expense/ajax/view',$expense_view_data,TRUE);
				$response['due_amount']						= $expense_view_data['expense']->total_amount - $expense_view_data['paid_amount'];


				/*************************** Start Add Payment View ******************************/
				$add_payment_data['from_account']	= $this->ledger_model->get_records(array(BANK_ACCOUNT_GROUP,CASH_GROUP));

				if($expense_view_data['expense']->supplier_id == 0)
				{
					$response['to_account']		= OTHER_SUPPLIER;	
				}
				else
				{
					$response['to_account']		= $this->supplier_model->get_single_record($expense_view_data['expense']->supplier_id)->ledger_id;		
				}
				
				$response['add_transaction']			= $this->load->view('expense/ajax/add_payment',$add_payment_data,TRUE);

				/*************************** End Add Payment View ******************************/

				/*************************** Start Previous Transaction ******************************/

				$previous_transaction['transactions']  = $this->transaction_model->get_records_entry_id_and_module_wise($id,EXPENSE_MODULE);
				$response['previous_transaction_view'] = $this->load->view('expense/ajax/transaction',$previous_transaction,TRUE);

				/*************************** End Previous Transaction ******************************/

				echo json_encode($response);
			}
			
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_expense'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$data = array('delete_status' => 1);

			$old_expense 			= $this->expense_model->get_single_record($id);
			$paid_amount 			= $this->transaction_model->get_total_transaction_amount($id,EXPENSE_MODULE,PAYMENT_TRANSACTION_TYPE);
			$expense_category = $this->expense_category_model->get_single_record($old_expense->expense_category_id);
			
			if($paid_amount == '' || $paid_amount == 0)
			{
				if($this->expense_model->edit_record($data,$id))
				{
					$supplier 				= $this->supplier_model->get_single_record($old_expense->supplier_id);

					// update the ledgers

					if($old_expense->expense_category_id != "")
					{
						$expense_ledger 				= $this->ledger_model->get_single_record($expense_category->ledger_id);
						$updated_expense_ledger = array('closing_balance' => ($expense_ledger->closing_balance - $old_expense->total_amount));
						$this->ledger_model->edit_record($updated_expense_ledger,$expense_category->ledger_id);
					}

					if($old_expense->supplier_id != 0)
					{
						$supplier_ledger 				= $this->ledger_model->get_single_record($supplier->ledger_id);
						$updated_supplier_ledger= array('closing_balance' => ($supplier_ledger->closing_balance - $old_expense->total_amount));
						$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);
					}		
					else
					{
						$supplier_ledger 				= $this->ledger_model->get_single_record(OTHER_SUPPLIER);
						$updated_supplier_ledger= array('closing_balance' => ($supplier_ledger->closing_balance - $old_expense->total_amount));
						$this->ledger_model->edit_record($updated_supplier_ledger,$supplier_ledger->id);
					}		

					// Remove old transaction entry
					$this->transaction_model->delete_record_by_entry_id($id,EXPENSE_MODULE);

					$log_data = array(
			                      "user_id"     => $this->session->userdata("user_id"),
			                      "module"      => "expense",
			                      "user_action" => 3,
			                      "data"        => json_encode($data),
			                      "description" => $expense_category->name.' deleted successfully.'
			                    );
      		$this->log_data_model->add_record($log_data);

					$this->session->set_flashdata('success', $expense_category->name.' is deleted successfully');
					redirect('expense','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', $expense_category->name.' is  failed to delete');
					redirect('expense','refresh');
				}	
			}
			else
			{
				$this->session->set_flashdata('failure', 'Please delete the transaction entry of Expense category :'.$expense_category->name);
				redirect('expense','refresh');
			}
			
		}
	}

	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->expense_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
				$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

        $paid_amount = $this->transaction_model->get_total_transaction_amount($item->id, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);
  			($paid_amount == '') ? $paid_amount = 0 : $paid_amount = $paid_amount;

        //view Button
        if($this->permission_model->has_permission('view_expense'))
        {
        	$table_body .=	'
                            <a href="'.base_url('expense/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="View Expense">
                              <i class="fas fa-eye"></i> View
                            </a>';
	      }

	      //manage Transaction
	      if($this->permission_model->has_permission('manage_transaction'))
        {
        	$table_body .=	'<a href="#transaction-modal" data-toggle="modal" class="btn bg-purple btn-xs" data-tt="tooltip" title="Make Payment" data-expense_id="'.$item->id.'"> 
														<i class="fas fa-rupee-sign"></i> Make Payment
													</a>';
	      }

				// Edit Button        
        if($this->permission_model->has_permission('edit_expense'))
				{	
					if($paid_amount > 0)
          {
          	
		        $table_body .= 	 '<a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-target="#expense_edit" data-tt="tooltip" title="'.$this->lang->line('expense_edit').'" data-expense_id="'.$item->id.'">
																<i class="fas fa-edit"></i> Edit
															</a>';
          }
          else
          {
          	$table_body .= '<a href="'.base_url('expense/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" data-tt="tooltip" title="'.$this->lang->line('expense_edit').'">
															<i class="fas fa-edit"></i> Edit
														</a>';
          }
					
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_expense'))
				{	
					$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_expense" data-tt="tooltip" title="'.$this->lang->line('expense_delete').'" class="btn btn-danger btn-xs delete_expense" data-expense_id="'.$item->id.'">
														<i class="fas fa-trash"></i> Delete
													</a>';
				}

			

				/* End Action column buttons*/

				//Download attachment
				$document ='';
				$document_array ='';
				$document_header = '<div id="document_list">';
				$document_body 	 = '';
				$document_footer = '</div>';
				

				if($item->document != '' || $item->document != null)
			  {
			     $document_array = explode(",", $item->document);
	          for ($i=0; $i < sizeof($document_array); $i++) 
	          {
	            $file_name_without_ext  = explode(".", $document_array[$i])[0];
	            $file_ext         = explode(".", $document_array[$i])[1];
							$actual_file_name     = explode(".", $document_array[$i])[0].'.'.$file_ext;

							$document_body .= '<div class="btn-group" style="padding-bottom: 10px;">
					                        <a target="_blank" class="btn btn-warning btn-xs" id="'.$document_array[$i].'" href="'.base_url('attachment/download/'.$document_array[$i]).'" data-tt="tooltip" title="'.$actual_file_name.'">
					                            <i class="fa fa-paperclip"></i>
					                          </a>
					                       </div>';
            }
        }

				//paid amount
				$paid_amount = $this->transaction_model->get_total_transaction_amount($item->id, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);
				

				$paid_amount_html = '<span class="text-success">'.(($paid_amount == '') ? $paid_amount = 0 : number_format_i($paid_amount = $paid_amount)).'</span>';

				$due_amount_html 	=	'<span class="text-danger">'.number_format_i(($item->total_amount-$paid_amount)).'</span>';



        $row = array();
       
        $row[] = date('d-m-Y', strtotime($item->date));;
        $row[] = $this->db->query("SELECT name FROM expense_category WHERE id = {$item->expense_category_id}")->row()->name ?? '';
        $row[] = $item->supplier_name;
       		
        $row[] = $document_header.$document_body.$document_footer;
        $row[] = $item->remarks;
        $row[] = number_format_i($item->total_amount);
        $row[] = $paid_amount_html;
        $row[] = $due_amount_html;
       


        $row[] = $table_header.$table_body.$table_footer;    

        $data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->expense_model->count_all(),
                    "recordsFiltered" => $this->expense_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function expense_delete_confirmation()
  {
  	$expense_id 				= $this->input->post('expense_id');
  	$data['expense'] 		= $this->expense_model->get_single_record($expense_id);

  	$response 					= array();
  	$response['expense_delete_modal_body'] 	= $this->load->view('expense/ajax/expense_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	 public function expense_edit_confirmation()
  {
  	$expense_id 				= $this->input->post('expense_id');
  	$data['expense'] 		= $this->expense_model->get_single_record($expense_id);

  	$response 					= array();
  	$response['expense_edit_modal_body'] 	= $this->load->view('expense/ajax/expense_edit_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/
}
