<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Sales_return extends MY_Controller {

	private $pdf;

	public function __construct()
	{
		parent::__construct();
		$this->pdf = new Dompdf();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	public function index()
	{
		$data['warehouse'] 						= $this->warehouse_model->get_records();
		$data['sales_return'] 				= $this->sales_return_model->get_sales_return_records();

		$data['total_sales_return']		= $this->transaction_model->get_total_transaction_amount(null, SALE_RETURN_MODULE, SALE_RETURN_TRANSACTION_TYPE);
		$data['total_paid_amount'] 		= $this->transaction_model->get_total_transaction_amount(null, SALE_RETURN_MODULE, PAYMENT_TRANSACTION_TYPE);

		$log_data = array(
				              "user_id"     => $this->session->userdata("user_id"),
				              "module"      => "sales_return",
				              "user_action" => 0,
				              "description"	=> "User has viewed list of sales_return."
				            );

		$this->log_data_model->add_record($log_data);
		$this->load->view('sales_return/list',$data);
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_sale_return'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('sales_return_date','Invoice Date','required');		
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');
				$this->form_validation->set_rules('customer_id','Warehouse','required');
				$this->form_validation->set_rules('invoice_no','Invoice No','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['customer'] 				= $this->customer_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$data['sales'] 						= $this->sale_model->get_sale_records();
					
					$this->load->view('sales_return/add',$data);
				}
				else
				{
					$sales_return_date 				= date('Y-m-d', strtotime($this->input->post('sales_return_date')));
					$reference_no 				= $this->sales_return_model->get_lastest_sequence_number();
					$warehouse_id 				= $this->input->post('warehouse_id');
					$customer_id 					= $this->input->post('customer_id');

					$customer 						= $this->customer_model->get_single_record($customer_id);
					$customer_gstin 			= $customer->gstin;

					$invoice_no 					= $this->input->post('sale_id');
					$total_taxable_value 	= $this->input->post('total_taxable_value');
					$total_discount 			= $this->input->post('total_discount');
					$total_tax 						= $this->input->post('total_tax');
					$total 								= $this->input->post('total');
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					//$bank_detail 				= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

          // $sale_id 							= $this->input->post('sale_id');

					$document 						= $this->input->post("document");

          if (!is_array($document)) {
            // Convert $document to an array with a single element
            $document = array($document);
          }

          $documents = implode(',', $document);

					$sales_return_data = array(
                                // "sale_id"				      => $sale_id,
																"sales_return_date"		=> $sales_return_date,
																"reference_no"				=> $reference_no,
																"invoice_no"					=> $invoice_no,
																"warehouse_id"				=> $warehouse_id,
																"customer_id"					=> $customer_id,
																"customer_gstin"			=> $customer_gstin,
																"total_taxable_value" => $total_taxable_value,	
																"total_tax"						=> $total_tax,
																"total_discount" 			=> $total_discount,	
																"total" 							=> $total,
																"internal_note"				=> $internal_note,
																"external_note" 			=> $external_note,
																"document" 			=> $documents,
																"terms_and_condition" => $terms_and_condition,
																"user_id" 						=> $this->session->userdata('user_id')
															);
					// being transaction
					$this->db->trans_begin();	

					if($id = $this->sales_return_model->add_sales_return_record($sales_return_data))
					{
						$entered_sales_return = $this->sales_return_model->get_sales_return_single_record($id);
						$customer 						= $this->customer_model->get_single_record($entered_sales_return->customer_id);

						// update the ledgers
							
						/****************************** Start sales_return Ledger ***********************************/

						$sale_return_ledger 						= $this->ledger_model->get_single_record(SALE_RETURN_LEDGER);
						$updated_sale_return_ledger 		= array('closing_balance' => ($sale_return_ledger->closing_balance + $total));
						$this->ledger_model->edit_record($updated_sale_return_ledger,$sale_return_ledger->id);

						/****************************** End sales_return Ledger ***********************************/

						/****************************** Start customer Ledger ***********************************/

						$customer_ledger 			= $this->ledger_model->get_single_record($customer->ledger_id);
						$updated_customer_ledger    = array('closing_balance' => ($customer_ledger->closing_balance + $total));
						$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

						/****************************** End customer Ledger ***********************************/

						/****************************** Add Transaction entry *********************************/

						$transaction_header = array(
																				"entry_id"				=>  $id,
																				"module"					=>  SALE_RETURN_MODULE,
																				"type"						=>	SALE_RETURN_TRANSACTION_TYPE,
																				"amount"					=>	$total,
																				"voucher_date"		=>	$sales_return_date,
																				"from_account"		=>	$customer->ledger_id,
																				"to_account"			=>	SALE_RETURN_LEDGER,
																				"reference_no"		=>	$reference_no,
																				"warehouse_id"              =>  $warehouse_id
																			);
						if($transaction_id = $this->transaction_model->add_record($transaction_header))
						{
							$from_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> $customer->ledger_id,
																								"dr_amount"				=> $total
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

							$to_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> SALE_RETURN_LEDGER,
																							"cr_amount"				=> $total
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
						}

						/**************************************************************************************/

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "sales_return",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"				=> json_encode((array)$entered_sales_return),
											"description" => 'sales_return (Reference No. -'  .$reference_no .') is added successfully.'
										);
						$this->log_data_model->add_record($log_data);

						$sales_return_items 				= $this->input->post('sales_return_items');
						$sales_return_items_array 	= explode("|", $this->input->post('sales_return_items'));

						// echo '<pre>';
						// print_r($sales_return_items_array);
						// exit;

						for ($i=0; $i < sizeof($sales_return_items_array) ; $i++) { 
									
							$temp_sales_return_item = (array)json_decode($sales_return_items_array[$i]);
							$temp_sales_return_item['sales_return_id']  = $id;

							if($temp_sales_return_item['expiry_date'] == '' || $temp_sales_return_item['expiry_date'] == '0000-00-00')
								unset($temp_sales_return_item['expiry_date']);
							else
								$temp_sales_return_item['expiry_date'] = date('Y-m-d',strtotime($temp_sales_return_item['expiry_date']));

							if($temp_sales_return_item['mfg_date'] == '' || $temp_sales_return_item['mfg_date'] == '0000-00-00')
								unset($temp_sales_return_item['mfg_date']);
							else
								$temp_sales_return_item['mfg_date'] = date('Y-m-d',strtotime($temp_sales_return_item['mfg_date']));

							$this->sales_return_model->add_sales_return_item_record($temp_sales_return_item);
						}

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success',  'sales_return (Reference No. -'.$reference_no.') is added successfully.');
						redirect('sales_return','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						$this->session->set_flashdata('failure',  'sales_return (Reference No. -'.$reference_no.') is failed to add.');
						redirect('sales_return','refresh');
					}

				}
			}
			else
			{
				$data['warehouses'] 			= $this->warehouse_model->get_records();
				$data['customer'] 				= $this->customer_model->get_records();
				$data['company_setting']	= $this->company_settings_model->get_company_records();	
				$data['sales'] 						= $this->sale_model->get_sale_records();
				$this->load->view('sales_return/add',$data);
			}
		}	
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_sale_return'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$sales_return_id = $this->input->post('id');
				$old_sales_return = $this->sales_return_model->get_sales_return_single_record($sales_return_id);

				$this->form_validation->set_rules('sales_return_date','Invoice Date','required');		
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');
				$this->form_validation->set_rules('customer_id','customer','required');
				$this->form_validation->set_rules('invoice_no','Invoice No','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['customer'] 			= $this->customer_model->get_records();
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					
					$this->load->view('sales_return/add',$data);
				}
				else
				{
					$sales_return_date 		= date('Y-m-d', strtotime($this->input->post('sales_return_date')));
					$customer_id 					= $this->input->post('customer_id');
					
					$customer 						= $this->customer_model->get_single_record($customer_id);
					$customer_gstin 			= $customer->gstin;

					$warehouse_id 				= $this->input->post('warehouse_id');
					$reference_no 				= $this->input->post('reference_no');
					$invoice_no 					= $this->input->post('invoice_no');
					$total_taxable_value	= $this->input->post('total_taxable_value');
					$total_discount 			=	$this->input->post('total_discount');
					$total_tax 						= $this->input->post('total_tax');
					$total 								= $this->input->post('total');
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					//$bank_detail 				= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

					$document 						= $this->input->post("document");

          if (!is_array($document)) {
            // Convert $document to an array with a single element
            $document = array($document);
          }

          $documents = implode(',', $document);
					

					$sales_return_data = array(
										"sales_return_date"			=> $sales_return_date,
										"invoice_no"						=> $invoice_no,
										"customer_id"						=> $customer_id,
										"customer_gstin"				=> $customer_gstin,
										"warehouse_id"					=> $warehouse_id,
										"total_taxable_value" 	=> $total_taxable_value,	
										"total_tax"							=> $total_tax,
										"total_discount" 				=> $total_discount,	
										"total" 								=> $total,
										"internal_note"					=> $internal_note,
										"external_note" 				=> $external_note,
										"terms_and_condition"		=> $terms_and_condition,
										"document" 							=> $documents,
										"user_id" 							=> $this->session->userdata('user_id')
									);

					// being transaction
					$this->db->trans_begin();	

					if($this->sales_return_model->edit_sales_return_record($sales_return_data,$sales_return_id))
					{
						$entered_sales_return = $this->sales_return_model->get_sales_return_single_record($sales_return_id);
						$customer 				= $this->customer_model->get_single_record($entered_sales_return->customer_id);

						// update the ledgers
						
						/****************************** Start Sale Ledger ***********************************/

						$sale_return_ledger 						= $this->ledger_model->get_single_record(SALE_RETURN_LEDGER);
						$updated_sale_return_ledger 		= array('closing_balance' => ($sale_return_ledger->closing_balance  - $old_sales_return->total + $total));
						$this->ledger_model->edit_record($updated_sale_return_ledger,$sale_return_ledger->id);

						/****************************** End Sale Ledger ***********************************/

						/****************************** Start customer Ledger ***********************************/

						$customer_ledger 					= $this->ledger_model->get_single_record($customer->ledger_id);
						$updated_customer_ledger  = array('closing_balance' => ($customer_ledger->closing_balance + $total - $old_sales_return->total));
						$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

						/****************************** End customer Ledger ***********************************/

						/****************************** Add Transaction entry *********************************/

						// Remove old transaction entry

						$this->transaction_model->delete_record_by_entry_id($entered_sales_return->id,SALE_RETURN_MODULE,SALE_RETURN_TRANSACTION_TYPE);

						$transaction_header = array(
																				"entry_id"				=>  $sales_return_id,
																				"module"					=>  SALE_RETURN_MODULE,
																				"type"						=>	SALE_RETURN_TRANSACTION_TYPE,
																				"amount"					=>	$total,
																				"voucher_date"		=>	$sales_return_date,
																				"from_account"		=>	$customer->ledger_id,
																				"to_account"			=>	SALE_RETURN_LEDGER,
																				"reference_no"		=>	$entered_sales_return->reference_no
																			);
						if($transaction_id = $this->transaction_model->add_record($transaction_header))
						{
							$from_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> $customer->ledger_id,
																								"dr_amount"				=> $total
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

							$to_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> SALE_RETURN_LEDGER,
																							"cr_amount"				=> $total
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
						}

						/**************************************************************************************/

						$log_data = array(
														"user_id" 		=> $this->session->userdata("user_id"),
														"module"			=> "sales_return",
														"user_action"	=> 2,
														"b_data"			=> json_encode((array)$old_sales_return),
														"data"				=> json_encode((array)$entered_sales_return),
														"description" => 'Sales Return (Reference No. -'  .$reference_no .') is updated successfully.'
													);
						$this->log_data_model->add_record($log_data);
						$this->sales_return_model->remove_sales_return_item_records($sales_return_id);

						$sales_return_items 				= $this->input->post('sales_return_items');
						$sales_return_items_array 	= explode("|", $this->input->post('sales_return_items'));

						for ($i=0; $i < sizeof($sales_return_items_array) ; $i++) { 
									
							$temp_sales_return_item = (array)json_decode($sales_return_items_array[$i]);
							$temp_sales_return_item['sales_return_id']  = $sales_return_id;

							if($temp_sales_return_item['expiry_date'] == '' || $temp_sales_return_item['expiry_date'] == '0000-00-00')
								unset($temp_sales_return_item['expiry_date']);
							else
								$temp_sales_return_item['expiry_date'] = date('Y-m-d',strtotime($temp_sales_return_item['expiry_date']));

							if($temp_sales_return_item['mfg_date'] == '' || $temp_sales_return_item['mfg_date'] == '0000-00-00')
								unset($temp_sales_return_item['mfg_date']);
							else
								$temp_sales_return_item['mfg_date'] = date('Y-m-d',strtotime($temp_sales_return_item['mfg_date']));

							$this->sales_return_model->add_sales_return_item_record($temp_sales_return_item);
						}

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success',  'Sales Return (Reference No. -'.$reference_no .') is updated successfully.');
						redirect('sales_return','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();
						$this->session->set_flashdata('failure',  'Sales Return (Reference No. -'.$reference_no .') is failed to update.');
						redirect('sales_return','refresh');
					}
				}
			}
			else
			{
				$id 	= base64_decode($id);

				if($id != null)
				{
					$data['sales_return'] 					= $this->sales_return_model->get_sales_return_single_record($id);

					if($data['sales_return'] != null)
					{
						$data['sales_return_items'] = $this->sales_return_model->get_sales_return_item_records($id);
						$data['customer'] 					= $this->customer_model->get_records();
						$data['customer_detail']		= $this->customer_model->get_single_record($data['sales_return']->customer_id);
						$data['discounts']					= $this->discount_model->get_records();
						$data['warehouses'] 				= $this->warehouse_model->get_records();
						$data['company_setting']		= $this->company_settings_model->get_company_records();	

						$this->load->view('sales_return/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('sales_return','refresh');
					}	
					
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('sales_return','refresh');
				}
			}
		}
		
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_sale_return'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 					= $this->input->post('id');
			$sales_return = $this->sales_return_model->get_sales_return_single_record($id);
			$paid_amount 	= $this->transaction_model->get_total_transaction_amount($id,SALE_RETURN_MODULE,PAYMENT_TRANSACTION_TYPE);

			if($paid_amount == 0 || $paid_amount == '')
			{
				$data = array('delete_status' => 1);
				if($this->sales_return_model->edit_sales_return_record($data,$id))
				{
					$customer 		= $this->customer_model->get_single_record($sales_return->customer_id);

					// update the ledgers
					/****************************** Start Sale Ledger ***********************************/

					$sale_return_ledger 						= $this->ledger_model->get_single_record(SALE_RETURN_LEDGER);
					$updated_sale_return_ledger 		= array('closing_balance' => ($sale_return_ledger->closing_balance - $sales_return->total));
					$this->ledger_model->edit_record($updated_sale_return_ledger,$sale_return_ledger->id);

					/****************************** End Sale Ledger ***********************************/

					/****************************** Start customer Ledger ***********************************/

					$customer_ledger 					= $this->ledger_model->get_single_record($customer->ledger_id);
					$updated_customer_ledger  = array('closing_balance' => ($customer_ledger->closing_balance - $sales_return->total));
					$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

					/****************************** End customer Ledger ***********************************/

					// Remove old transaction entry
					$this->transaction_model->delete_record_by_entry_id($id,SALE_RETURN_MODULE);


					$this->session->set_flashdata('success', 'Sales Return (Reference No. -'.$sales_return->reference_no.') is deleted successfully.');
					redirect('sales_return','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', 'Sales Return (Reference No. -'.$sales_return->reference_no.') is failed to delete.');
					redirect('sales_return','refresh');
				}	
			}
			else
			{
				$this->session->set_flashdata('failure', 'Please delete the transaction entry to delete this Sale:'.$sales_return->reference_no);
				redirect('sales_return','refresh');
			}

			
			
		}
	
	}

	function restore($id = null)
	{
		
			$id 					= base64_decode($id);
			$sales_return = $this->sales_return_model->get_sales_return_single_record($id);
			$paid_amount 	= $this->transaction_model->get_total_transaction_amount($id,SALE_RETURN_MODULE,PAYMENT_TRANSACTION_TYPE);

			
			$data = array('delete_status' => 0);
			if($this->sales_return_model->edit_sales_return_record($data,$id))
			{
				$customer 		= $this->customer_model->get_single_record($sales_return->customer_id);

				// update the ledgers
				/****************************** Start Sale Ledger ***********************************/

				$sale_return_ledger 						= $this->ledger_model->get_single_record(SALE_RETURN_LEDGER);
				$updated_sale_return_ledger 		= array('closing_balance' => ($sale_return_ledger->closing_balance + $sales_return->total));
				$this->ledger_model->edit_record($updated_sale_return_ledger,$sale_return_ledger->id);

				/****************************** End Sale Ledger ***********************************/

				/****************************** Start customer Ledger ***********************************/

				$customer_ledger 					= $this->ledger_model->get_single_record($customer->ledger_id);
				$updated_customer_ledger  = array('closing_balance' => ($customer_ledger->closing_balance + $sales_return->total));
				$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

				/****************************** End customer Ledger ***********************************/

				/****************************** Add Transaction entry *********************************/

				$transaction_header = array(
					"entry_id"				=>  $id,
					"module"					=>  SALE_RETURN_MODULE,
					"type"						=>	SALE_RETURN_TRANSACTION_TYPE,
					"amount"					=>	$sales_return->total,
					"voucher_date"		=>	$sales_return->sales_return_date,
					"from_account"		=>	$customer->ledger_id,
					"to_account"			=>	SALE_RETURN_LEDGER,
					"reference_no"		=>	$sales_return->reference_no
				);
				if($transaction_id = $this->transaction_model->add_record($transaction_header))
				{
					$from_transaction_detail = array(
														"transaction_id" 	=> $transaction_id,
														"voucher_type"		=> 'C',
														"ledger_id"				=> $customer->ledger_id,
														"dr_amount"				=> $sales_return->total
													);
					// transaction detail record
					$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

					$to_transaction_detail = array(
													"transaction_id" 	=> $transaction_id,
													"voucher_type"		=> 'D',
													"ledger_id"				=> SALE_RETURN_LEDGER,
													"cr_amount"				=> $sales_return->total
												);
					// transaction detail record
					$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
				}

				/**************************************************************************************/


				$this->session->set_flashdata('success', 'Sales Return (Reference No. -'.$sales_return->reference_no.') is restored successfully.');
				redirect('sales_return','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Sales Return (Reference No. -'.$sales_return->reference_no.') is failed to restore.');
				redirect('sales_return','refresh');
			}	
		
	}

	public function view($id = null)
	{
		if (!$this->input->is_ajax_request()) 
		{
			if($id != null)
			{
				$id 									= base64_decode($id);

				$data['sales_return'] 			= $this->sales_return_model->get_sales_return_single_record($id);

				if($data['sales_return'] != null)
				{
					$data['sales_return_items'] 	= $this->sales_return_model->get_sales_return_item_records($id);
					$data['warehouses'] 		= $this->warehouse_model->get_records();
					$data['warehouse_detail']= $this->warehouse_model->get_single_record($data['sales_return']->warehouse_id);
					$data['customer'] 		= $this->customer_model->get_records();
					$data['customer_detail']= $this->customer_model->get_single_record($data['sales_return']->customer_id);
					$data['discounts']		= $this->discount_model->get_records();
					$data['company_setting']= $this->company_settings_model->get_company_records();
                    $warehouse_id = $data['sales_return']->warehouse_id;
                    $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);
					$this->load->view('sales_return/view',$data);
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('sales_return','refresh');
				}

				
			}
			else
			{
				$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
				redirect('sales_return','refresh');
			}
		}
		else
		{
			$response = array();

			$sales_return_view_data['sales_return'] 	= $this->sales_return_model->get_sales_return_single_record($id);
			$sales_return_view_data['customer_detail']= $this->customer_model->get_single_record($sales_return_view_data['sales_return']->customer_id);	
			$sales_return_view_data['paid_amount'] 		= $this->transaction_model->get_total_transaction_amount($id,SALE_RETURN_MODULE,PAYMENT_TRANSACTION_TYPE);
			
			$response['sales_return_view']	 					= $this->load->view('sales_return/ajax/view',$sales_return_view_data,TRUE);
			$response['due_amount']										= $sales_return_view_data['sales_return']->total - $sales_return_view_data['paid_amount'];


			/*************************** Start Add Payment View ******************************/
			
			$add_payment_data['from_account'] = $this->ledger_model->get_records(array(BANK_ACCOUNT_GROUP,CASH_GROUP));			

			$response['to_account']						= $this->customer_model->get_single_record($sales_return_view_data['sales_return']->customer_id)->ledger_id;
			$response['add_transaction']			= $this->load->view('sales_return/ajax/add_payment',$add_payment_data,TRUE);

			/*************************** End Add Payment View ******************************/

			/*************************** Start Previous Transaction ******************************/

			$previous_transaction['transactions']  = $this->transaction_model->get_records_entry_id_and_module_wise($id,SALE_RETURN_MODULE);
			$response['previous_transaction_view'] = $this->load->view('sales_return/ajax/transaction',$previous_transaction,TRUE);

			/*************************** End Previous Transaction ******************************/

			echo json_encode($response);
		}
	}

	public function view_delivery()
	{
		if ($this->input->is_ajax_request()) 
		{
			$sales_return_id 																= $this->input->post('sales_return_id');
			$delivery_detail_data['sales_return_deliveries']= $this->sales_return_delivery_model->get_delivery_records($sales_return_id);
			$delivery_detail_data['sales_return_items']			= $this->sales_return_model->get_sales_return_item_records($sales_return_id);
			$delivery_detail_data['users']							= $this->ion_auth_model->users()->result();

			$response 														= array();
			$response['add_delivery_detail'] 			= $this->load->view('sales_return/ajax/add_delivery_detail',$delivery_detail_data,TRUE);
			$response['sales_return_delivery_detail'] = $this->load->view('sales_return/ajax/view_sales_return_delivery_detail',$delivery_detail_data,TRUE);
			$response['delivery_transaction'] 		= $this->load->view('sales_return/ajax/delivery_transaction',$delivery_detail_data,TRUE);
			$response['is_all_products_delivered']= $this->is_all_sales_return_products_delivered($sales_return_id);

			echo json_encode($response);  
		}
	}

	public function add_sales_return_delivery($sales_return_id = null)
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$sales_return_id 		= $this->input->post('sales_return_id');
			$delivery_date 	= $this->input->post('delivery_date');
			$received_by 		= $this->input->post('received_by');

			$delivery_items = $this->input->post('delivery_items');



			$delivery_data = array(
														"sales_return_id"			=> $sales_return_id,
														"delivery_date" 	=> date('Y-m-d', strtotime($delivery_date)),
														"received_by"			=> $received_by,
														"user_id" 				=> $this->session->userdata('user_id')
													);

			// being transaction
			$this->db->trans_begin();	

			if($sales_return_delivery_id = $this->sales_return_delivery_model->add_delivery_record($delivery_data))
			{
				$delivery_items_array 	= explode("|", $delivery_items);

				for ($i=0; $i < sizeof($delivery_items_array) ; $i++) 
				{			
					$temp_delivery_item = (array)json_decode($delivery_items_array[$i]);

					if(sizeof($temp_delivery_item) > 0 && $temp_delivery_item['quantity'] > 0)
					{
						/******************************** ADD PRODUCT DELIVERY ******************************************/	
						
						$temp_delivery_item['sales_return_delivery_id']  = $sales_return_delivery_id;
						$this->sales_return_delivery_model->add_delivery_item_record($temp_delivery_item);

						/******************************** ADD PRODUCT TO WAREHOUSE **************************************/	
						

						$product_id 						= $temp_delivery_item['product_id'];
						$quantity 							= $temp_delivery_item['quantity'];

						$product 								= $this->product_model->get_single_record($product_id);
						$sales_return_delivery 	= $this->sales_return_delivery_model->get_delivery_single_record($sales_return_delivery_id);
						$sales_return 					=	$this->sales_return_model->get_sales_return_single_record($sales_return_delivery->sales_return_id);
						$sales_return_item 			= $this->sales_return_model->get_single_sales_return_item_record($sales_return->id,$product_id);
						$warehouse_id 					= $sales_return->warehouse_id;					
						$cost 									= $sales_return_item->cost;
						$price 									= $sales_return_item->price;

						if($product->manage_inventory == MANAGE_INVENTORY_YES)
						{
								// Check for product with zero value
								$warehouse_product = $this->warehouse_products_model->get_single_record($sales_return_item->warehouse_product_id);
								// update the product quantity
								$new_quantity = $warehouse_product->quantity + $quantity ;
								// $new_quantity = $quantity + $warehouse_product->quantity;
								$warehouse_product_data = array(
																							"warehouse_id" 	=> $warehouse_id,
																							"product_id"		=> $product_id,
																							"quantity"			=> $new_quantity
																						);
								$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);
						}

					

						/******************************** END ADD PRODUCT TO WAREHOUSE **************************************/

					}
					
				}

				// commit transaction
				$this->db->trans_commit();	

				$response = array();
				$response['code'] = 1;
				$response['message'] = 'Delivery successfully added.';
				$response['is_all_products_delivered'] = $this->is_all_sales_return_products_delivered($sales_return_id);

				echo json_encode($response);
			}
			else
			{

				// rollback transaction
				$this->db->trans_rollback();	

				$response = array();
				$response['code'] = 0;
				$response['message'] = 'Delivery is failed to add.';

				echo json_encode($response);
			}
		}
		else
		{

			$delivery_detail_data['sales_return_items'] 		= $this->sales_return_model->get_sales_return_item_records($sales_return_id);
			$delivery_detail_data['users']							= $this->ion_auth_model->users()->result();

			$response 																	= array();
			$response['add_delivery_detail'] 						= $this->load->view('sales_return/ajax/add_delivery_detail',$delivery_detail_data,TRUE);

			echo json_encode($response);
			
		}
	}

	public function delete_sales_return_delivery()
	{
		$sales_return_delivery_id 		= $this->input->post('sales_return_delivery_id');
		$sales_return_delivery 				= $this->sales_return_delivery_model->get_delivery_single_record($sales_return_delivery_id);
		$sales_return_delivery_items 	= $this->sales_return_delivery_model->get_delivery_item_records($sales_return_delivery_id);
		
		// being transaction
		$this->db->trans_begin();	

		if($this->sales_return_delivery_model->delete_delivery_record($sales_return_delivery_id))
		{
			foreach ($sales_return_delivery_items as $item) 
			{
				$this->sales_return_delivery_model->delete_delivery_item_record($sales_return_delivery_id,$item->product_id);

				/*************************** UPDATE PRODUCT QUANTITY IN WAREHOUSE ************************************/

				$delivery_quantity			= $item->quantity;

				$product 								= $this->product_model->get_single_record($item->product_id);
				$sales_return 					= $this->sales_return_model->get_sales_return_single_record($sales_return_delivery->sales_return_id);
				$sales_return_item 			= $this->sales_return_model->get_single_sales_return_item_record($sales_return->id,$item->product_id);
				$cost 									= $sales_return_item->cost;
				$price 									= $sales_return_item->price;
				$warehouse_id 					= $sales_return->warehouse_id;
				$product_id 						= $item->product_id;


				if($product->manage_inventory == MANAGE_INVENTORY_YES)
				{
					$warehouse_product = $this->warehouse_products_model->get_single_record($sales_return_item->warehouse_product_id);

					// Only reduce the quantity of product

					$new_quantity = $warehouse_product->quantity - $item->quantity;

					$warehouse_product_data = array(
																				"warehouse_id" 	=> $warehouse_id,
																				"product_id"		=> $product_id,
																				"quantity"			=> $new_quantity
																			);
					$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id); 
				}

				

				/*************************** END UPDATE PRODUCT QUANTITY IN WAREHOUSE ********************************/
			}
			
			// commit transaction
			$this->db->trans_commit();	

			$response = array();
			$response['code'] = 1;
			$response['message'] = 'Delivery successfully deleted.';
			$response['is_all_products_delivered'] = $this->is_all_sales_return_products_delivered($sales_return_delivery->sales_return_id);
			
			echo json_encode($response);
		}
		else
		{

			// rollback transaction
			$this->db->trans_rollback();	

			$response = array();
			$response['code'] = 0;
			$response['message'] = 'Delivery is failed to delete.';

			echo json_encode($response);
		}
	}

	public function pdf($id = null)
	{
		
		if($id != null)
		{
			$id 												= base64_decode($id);
			$data['sales_return'] 			= $this->sales_return_model->get_sales_return_single_record($id);
			$data['sales_return_items'] = $this->sales_return_model->get_sales_return_item_records($id);
			$data['warehouses'] 		    = $this->warehouse_model->get_records();
			$data['warehouse_detail']	  = $this->warehouse_model->get_single_record($data['sales_return']->warehouse_id);
			$data['customer'] 			    = $this->customer_model->get_records();
			$data['customer_detail']  = $this->customer_model->get_single_record($data['sales_return']->customer_id);
			$data['discounts']			    = $this->discount_model->get_records();
			$data['company_setting']	  = $this->company_settings_model->get_company_records();	
            $warehouse_id = $data['sales_return']->warehouse_id;
            $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);
            
			$html = $this->load->view('sales_return/pdf',$data,true);
			
			$this->pdf->loadHtml($html);
			$this->pdf->render();
			$this->pdf->stream("Sales Return-".$data['sales_return']->reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('sales_return','refresh');
		}
	}

	function email_invoice()
	{	
		$sales_return_id 									= $this->input->post('sales_return_id');

		$company_setting 									= $this->company_settings_model->get_company_records();	
		$sales_return  										= $this->sales_return_model->get_sales_return_single_record($sales_return_id);
		$sales_return_items 							= $this->sales_return_model->get_sales_return_item_records($sales_return_id);
		$warehouse_detail 								= $this->warehouse_model->get_single_record($sales_return->warehouse_id);

		$data['warehouse']								= $warehouse_detail;
		$data['sales_return']							= $sales_return;
		$data['company_setting']					= $company_setting;
		$data['sales_return_items'] 			= $sales_return_items;
		$data['warehouse_detail']					= $warehouse_detail;

		$mail_data 												= array();
		
		$mail_data['From'] 								= $company_setting->email;
		$mail_data['FromName'] 						= $company_setting->company_name;
		$mail_data['AddReplyTo'] 					= $company_setting->email;
		$mail_data['ConfirmReadingTo']		= $company_setting->email;
		$mail_data['To']									= $warehouse_detail->email;
		$mail_data['Subject'] 						= "sales_return #".$sales_return->reference_no." has generated by ".$company_setting->company_name;

		$mail_data['Body'] 								= $this->load->view('sales_return/pdf',$data,true);


		$response = array();

		if($warehouse_detail->email != '')
		{
			if($this->email_model->send_mail($mail_data))
			{
				$response['code'] 			= 1;
				$response['message'] 		= 'sales_return #'.$sales_return->reference_no.' has been sent to '.$warehouse_detail->warehouse_name;
				echo json_encode($response);
			}
			else
			{
				$response['code'] 			= 0;
				$response['message'] 		= 'Failed to send sales_return #'.$sales_return->reference_no.' has been sent to '.$warehouse_detail->warehouse_name;
				echo json_encode($response);
			}	
		}
		else
		{
			$response['code'] 			= 0;
			$response['message'] 		= $warehouse_detail->email.' records does not have email';
			echo json_encode($response);
		}

	}

	public function is_all_sales_return_products_delivered($sales_return_id)
	{
		$total_ordered_quantity	= $this->sales_return_delivery_model->get_total_no_of_quantity_ordered($sales_return_id);
		$total_delivered_quantity	= $this->sales_return_delivery_model->get_total_no_of_quantity_delivered($sales_return_id);

		if(($total_ordered_quantity - $total_delivered_quantity) == 0)
			return true;
		else
			return false;
		
	}

	public function send_email($sales_return_id = null)
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			
			$this->form_validation->set_rules("to_mail","To Email","required");
			$this->form_validation->set_rules("subject","Subject","required");
			$this->form_validation->set_rules("message","Message","required");

			if($this->form_validation->run()==FALSE)
			{
				$response = array(
						'code' => 0,
						'errors' => $this->form_validation->error_array() // Get validation errors
				);
				echo json_encode($response);

			}
			else
			{	

				$from_name 	= $this->input->post("from_name");
				$from_mail 	= $this->input->post("from_mail");
				$to_name 		= $this->input->post("to_name");
				$to_mail 		= $this->input->post("to_mail");
				$to_cc 			= $this->input->post("to_cc");
				$subject 		= $this->input->post("subject");
				$message 		= $this->input->post("message");

				$sales_return_id 		= $this->input->post("sales_return_id");

				$email_data     = array(
												"from_name" 	=> $from_name,
												"from_mail" 	=> $from_mail,
												"to_name" 		=> $to_name,
												"to_mail" 		=> $to_mail,
												"to_cc" 	 		=> $to_cc,	
												"subject" 	 	=> $subject,
												"message" 	 	=> $message	
												
											);


				// being transaction
				$this->db->trans_begin();
				
				if($id = $this->email_model->add_email_sent_record($email_data))
				{

					$sales_return												= $this->sales_return_model->get_sales_return_single_record($sales_return_id);
					$sales_return_items 								= $this->sales_return_model->get_sales_return_item_records($sales_return->id);
					$customer_detail						= $this->customer_model->get_single_record($sales_return->customer_id);
					$company_setting						= $this->company_settings_model->get_company_records();	
	
					$data['customers'] 					= $this->customer_model->get_records();
					$data['customer_detail']		= $customer_detail;
					$data['sales_return']								= $sales_return;
					$data['sales_return_items'] 				= $sales_return_items;
					$data['company_setting']		= $company_setting;
					$data['discounts']					= $this->discount_model->get_records();
					$data['message'] 						= $email_data['message'];

				
					$to_cc = ($email_data['to_cc'] != '') ? explode(',',  $email_data['to_cc']) : array();

					$to_cc[] = $from_mail;

					$mail_data 											= array();
					
					$mail_data['From'] 							= FROM_EMAIL;
					$mail_data['FromName'] 					= $company_setting->company_name;
					$mail_data['AddReplyTo'] 				= $from_mail;
					$mail_data['ConfirmReadingTo']	= $from_mail;
					$mail_data['To']								= $to_mail;
					$mail_data['Cc']								= $to_cc;
					$mail_data['Subject'] 					= $subject;
					$mail_data['Body'] 							= $this->load->view('email_view' , $data, true);

			
					$html = $this->load->view('sales_return/pdf', $data, true);

					$pdf_filename = "Sales return-".$data['sales_return']->reference_no.".pdf"; // Set your desired filename

          $cid = $company_setting->cid;
          $pdf_path = FCPATH . 'assets/documents/' . $cid . '/sales_return/' . $pdf_filename;

          // Create the target folder if it doesn't exist
          $targetDir = FCPATH . 'assets/documents/' . $cid . '/sales_return/';
          if (!file_exists($targetDir)) {
              mkdir($targetDir, 0777, true); // The third parameter creates nested directories if needed
          }


					// Save the PDF file directly to the given path
					//$pdf_path = FCPATH . 'assets/documents/' . $pdf_filename;
	
					
					$this->pdf->loadHtml($html);
					$this->pdf->set_paper('A4', $orientation ?? 'portrait'); 
					$this->pdf->render();

					// Save the rendered PDF content to the given path
					file_put_contents($pdf_path, $this->pdf->output());

					$response = array();

					if($to_mail != '')
					{
						if($this->email_model->send_mail($pdf_path,$mail_data))
						{
							$response['code'] 			= 1;
							$response['message'] 		= 'Email Sent successfully';
							echo json_encode($response);
						}
						else
						{
							$response['code'] 			= 0;
							$response['message'] 		= 'Failed to send email';
							echo json_encode($response);
						}	
					}
					else
					{
						$response['code'] 				= 0;
						$response['message'] 			= 'Failed to send email';
						echo json_encode($response);
					}

					$this->db->trans_commit();

				}
				else
				{
					// rollback transaction
					$this->db->trans_rollback();

					if($this->input->is_ajax_request())
					{	
						$response 											= array();
						$response['code'] 							= 0;
						$response['message']						= 'Failed to send email';
						
						echo json_encode($response);
					}
					else
					{
						$this->session->set_flashdata('failure', 'Failed to send email');
						redirect('quotation','refresh');
					}
					
				}
			}

		}
		else
		{
		
			$data['sales_return'] 	= $this->sales_return_model->get_sales_return_single_record($sales_return_id);
			$data['customer']		= $this->customer_model->get_single_record($data['sales_return']->customer_id);
			$data['company_setting']	= $this->company_settings_model->get_company_records();	
			$data['email_template'] 	= $this->email_template_model->get_record_by_module(EMAIL_TEMPLATE_MODULE_SALE_RETURN);

			$response 												= array();
	  	$response['code']  								= 1;

			   
			$cc_emails = ''; 

			
			if (strpos($data['customer']->email, ',') !== false) 
			{
				$emails = explode(',', $data['customer']->email);
				$data['customer']->email = trim($emails[0]);

				$cc_emails = implode(',', array_slice($emails, 1));
			}

			$data['cc_emails'] = $cc_emails;
			
			$response['email_modal_body'] 	= $this->load->view('sales_return/ajax/email_modal_body',$data,TRUE);

			
	  	echo json_encode($response);
		}
	}

		/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->sales_return_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

        $delivered_product_qty = $this->sales_return_delivery_model->get_total_no_of_quantity_delivered($item->id);
        $paid_amount  = $this->transaction_model->get_total_transaction_amount($item->id, SALE_RETURN_MODULE, PAYMENT_TRANSACTION_TYPE);
       

			

				if($item->delete_status == DELETED)
				{
					if($this->permission_model->has_permission('restore_sales_return'))
					{
						$table_body .= '
														<a href="'.base_url('sales_return/restore/'.base64_encode($item->id)).'"  data-tt="tooltip" title="Restore Sales return" class="btn btn-primary btn-xs">
														<i class="fas fa-redo-alt"></i> Restore
													</a>
													';
					}
				}
				else
				{
					if($this->permission_model->has_permission('email_sale_return'))
					{

						$table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('send_email').'" class="btn btn-success btn-sm text-white email-modal" data-sales_return_id="'.$item->id.'">
														<i class="fas fa-at"></i>
													</a>';
					}
				
					//View Button
					if($this->permission_model->has_permission('view_sale_return') || $this->permission_model->has_permission('view_all_sales_return'))
					{	
						$table_body .= ' 
															<a href="'.base_url('sales_return/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="View Sales Return">
																<i class="fas fa-eye"></i>
															</a>      
														';
					}
	
					// Edit Button        
					 if($this->permission_model->has_permission('edit_sale_return') || $this->permission_model->has_permission('edit_all_sales_return'))
					{	
						 if($delivered_product_qty > 0 || $paid_amount > 0)
						{
	
							$table_body .= 	 '
																	<a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-target="#sales_return_edit" data-tt="tooltip" title="Edit Sales Return" data-sales_return_id="'.$item->id.'">
																		<i class="fas fa-edit"></i> Edit
																	</a> 
																 ';
							
						}
						else
						{
							
							$table_body .= ' 
																<a href="'.base_url('sales_return/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs"  data-tt="tooltip" title="Edit Sales Return">
																	<i class="fas fa-edit"></i>
																</a>
															';
						}
						
					}
	
					// Delete Button
					if($this->permission_model->has_permission('delete_sale_return') || $this->permission_model->has_permission('delete_all_sale_return'))
					{	
						$table_body .= '
															<a href="#"  data-toggle="modal" data-target="#delete_sales_return" data-tt="tooltip" title="Delete Sales Return" class="btn btn-danger btn-xs delete_sales_return" data-sales_return_id="'.$item->id.'">
																<i class="fas fa-trash"></i>
															</a>
														';
					}
				}


				

				$ordered_quantity 	= (float)$this->sales_return_delivery_model->get_total_no_of_quantity_ordered($item->id);
        $delivered_quantity = (float)$this->sales_return_delivery_model->get_total_no_of_quantity_delivered($item->id);

        //paid amount
				$paid_amount_html 	=	'<span class="text-success">'.(($paid_amount == '') ? '0.000' : number_format_i($paid_amount)).'</span>';
				//due amount
				$due_amount_html 		=  '<span class="text-danger">'.number_format_i(($item->total-$paid_amount)).'</span>';


				//Delievery status
				$delivered_quantity_html = '';


        if($delivered_quantity == 0)
        {
      
          $delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-sales_return_id="'.$item->id.'">
																				<img src="'.base_url('assets/images/not_delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
																			</a>';
      
        }
        else if($delivered_quantity < $ordered_quantity && $delivered_quantity > 0)
        {
      
          $delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-sales_return_id="'.$item->id.'">
																				<img src="'.base_url('assets/images/partial_delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
																			</a>';
      
        }
        else if($delivered_quantity == $ordered_quantity)
        {
       
          $delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-sales_return_id="'.$item->id.'">
																				<img src="'.base_url('assets/images/delivered.png').'" data-tt="tooltip" width="40px" style="cursor: pointer">
																			</a>';
      
        }
        //Reference no
        $reference_no_html = '<a href="'.base_url('sales_return/view/'.base64_encode($item->id)).'" data-tt="tooltip" title="'.$this->lang->line('sales_return_view').'">'.$item->reference_no.'</a>';

                      
			

				/* End Action column buttons*/

        $row = array();
       
	      $row[] = $reference_no_html;
	      $row[] = ($item->delete_status == DELETED) ? '' : $item->invoice_no;
	      $row[] = ($item->delete_status == DELETED) ? '' : date('d-m-Y', strtotime($item->sales_return_date));
	      $row[] = ($item->delete_status == DELETED) ? '' : $item->name;
	      $row[] = ($item->delete_status == DELETED) ? '' : $item->customer_name;
	      $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total_discount);
	      $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total_taxable_value);
	      $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total_tax);
	      $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total);
	      $row[] = ($item->delete_status == DELETED) ? '' : $paid_amount_html;
	      $row[] = ($item->delete_status == DELETED) ? '' : $due_amount_html;
	      $row[] = ($item->delete_status == DELETED) ? '' : $delivered_quantity_html;


        $row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->sales_return_model->count_all(),
                    "recordsFiltered" => $this->sales_return_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function sales_return_delete_confirmation()
  {
  	$sales_return_id 				= $this->input->post('sales_return_id');
  	$data['sales_return'] 	= $this->sales_return_model->get_sales_return_single_record($sales_return_id);

  	$response 					= array();
  	$response['sales_return_delete_modal_body'] 	= $this->load->view('sales_return/ajax/sales_return_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	public function sales_return_edit_confirmation()
  {
  	$sales_return_id 				= $this->input->post('sales_return_id');
  	$data['sales_return'] 		= $this->sales_return_model->get_sales_return_single_record($sales_return_id);

  	$response 					= array();
  	$response['sales_return_edit_modal_body'] 	= $this->load->view('sales_return/ajax/sales_return_edit_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/


	public function get_sale_items_by_sale_id()
	{
		$sale_id 														= $this->input->post('sale_id');
		$data['sale']												= $this->sale_model->get_sale_single_record($sale_id);
		
		$data['sale_items']									= $this->sale_model->get_sale_item_records($sale_id);

  	$response 													= array();
  	$response['view_sale_items'] 	 			= $this->load->view('sales_return/ajax/view_sale_items',$data,TRUE);

  	echo json_encode($response);
	}

	public function upload_documents() 
	{
    if (isset($_FILES["upload_document"])) 
		{
			$data['company_setting'] 	= $this->company_settings_model->get_company_records();

			$cid = $data['company_setting']->cid; 

			$targetDir = "./assets/documents/$cid/sales_return/";

			if (!file_exists($targetDir)) {
					mkdir($targetDir, 0777, true);
			}

			$response = array();

			foreach ($_FILES["upload_document"]["tmp_name"] as $key => $tmp_name) {
					$uploadFilename = basename($_FILES["upload_document"]["name"][$key]);
					$microtime = microtime(true);
					$microsecondPrefix = str_replace(".", "_", $microtime);
					$newFilename = $microsecondPrefix . "_" . $uploadFilename;
					$targetFile = $targetDir . $newFilename;

					if (move_uploaded_file($tmp_name, $targetFile)) {
							$response[] = $newFilename;
					} else {
							$response[] = "Error uploading document: " . $_FILES["upload_document"]["error"][$key];
					}
			}

			echo json_encode($response);
    } 
		else 
		{
        echo json_encode(array("error" => "No document file provided."));
    }
	}

}
