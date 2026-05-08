<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Purchase_return extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_purchase_return') && !$this->permission_model->has_permission('list_all_purchase_return'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['warehouse'] 							= $this->warehouse_model->get_records();
			//$data['supplier'] 						= $this->supplier_model->get_records();
			$data['purchase_return'] 				= $this->purchase_return_model->get_purchase_return_records();
			
			$data['total_purchase_return']	= $this->transaction_model->get_total_transaction_amount(null, PURCHASE_RETURN_MODULE, PURCHASE_RETURN_TRANSACTION_TYPE);
			$data['total_paid_amount'] 			= $this->transaction_model->get_total_transaction_amount(null, PURCHASE_RETURN_MODULE, PAYMENT_TRANSACTION_TYPE);

// 			$log_data = array(
//                   "user_id"     => $this->session->userdata("user_id"),
//                   "module"      => "purchase_return",
//                   "user_action" => 0,
//                   "description"	=> "User has viewed list of purchase return."
//                 );
// 			$this->log_data_model->add_record($log_data);

			$this->load->view('purchase_return/list',$data);
		}
	}

	public function add()
	{

		if(!$this->permission_model->has_permission('add_purchase_return'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('purchase_return_date','Invoice Date','required');		
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');
				$this->form_validation->set_rules('supplier_id','Warehouse','required');
				$this->form_validation->set_rules('invoice_no','Invoice No','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['supplier'] 				= $this->supplier_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$data['purchases']				= $this->purchase_model->get_purchase_records();	
					$this->load->view('purchase_return/add',$data);
				}
				else
				{
					$purchase_return_date = date('Y-m-d', strtotime($this->input->post('purchase_return_date')));
					$reference_no 				= $this->purchase_return_model->get_lastest_sequence_number();
					$warehouse_id 				= $this->input->post('warehouse_id');
					$supplier_id 					= $this->input->post('supplier_id');

					$supplier 						= $this->supplier_model->get_single_record($supplier_id);
					$supplier_gstin 			= $supplier->gstin;

					$invoice_no 					= $this->input->post('invoice_no');
					$total_taxable_value 	= $this->input->post('total_taxable_value');
					$total_discount 			= $this->input->post('total_discount');
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

					$purchase_return_data = array(
																"purchase_return_date"	=> $purchase_return_date,
																"reference_no"					=> $reference_no,
																"invoice_no"						=> $invoice_no,
																"warehouse_id"					=> $warehouse_id,
																"supplier_id"						=> $supplier_id,
																"supplier_gstin"				=> $supplier_gstin,
																"total_taxable_value" 	=> $total_taxable_value,	
																"total_tax"							=> $total_tax,
																"total_discount" 				=> $total_discount,	
																"total" 								=> $total,
																"internal_note"					=> $internal_note,
																"external_note" 				=> $external_note,
																"document" 				=> $documents,
																"terms_and_condition" 	=> $terms_and_condition,
																"user_id" 							=> $this->session->userdata('user_id')
															);
					// being transaction
					$this->db->trans_begin();	

					if($id = $this->purchase_return_model->add_purchase_return_record($purchase_return_data))
					{
						$entered_purchase_return = $this->purchase_return_model->get_purchase_return_single_record($id);
						$supplier 				= $this->supplier_model->get_single_record($entered_purchase_return->supplier_id);

						// update the ledgers
							
						/****************************** Start purchase_return Ledger ***********************************/

						$purchase_return_ledger 						= $this->ledger_model->get_single_record(PURCHASE_RETURN_LEDGER);
						$updated_purchase_return_ledger 		= array('closing_balance' => ($purchase_return_ledger->closing_balance + $total));
						$this->ledger_model->edit_record($updated_purchase_return_ledger,$purchase_return_ledger->id);

						/****************************** End purchase_return Ledger ***********************************/

						/****************************** Start Supplier Ledger ***********************************/

						$supplier_ledger 			= $this->ledger_model->get_single_record($supplier->ledger_id);
						$updated_supplier_ledger    = array('closing_balance' => ($supplier_ledger->closing_balance + $total));
						$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

						/****************************** End Supplier Ledger ***********************************/

						/****************************** Add Transaction entry *********************************/

						$transaction_header = array(
																				"entry_id"				=>  $id,
																				"module"					=>  PURCHASE_RETURN_MODULE,
																				"type"						=>	PURCHASE_RETURN_TRANSACTION_TYPE,
																				"amount"					=>	$total,
																				"voucher_date"		=>	$purchase_return_date,
																				"from_account"		=>	PURCHASE_RETURN_LEDGER,
																				"to_account"			=>	$supplier->ledger_id,
																				"reference_no"		=>	$reference_no
																			);
						if($transaction_id = $this->transaction_model->add_record($transaction_header))
						{
							$from_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> PURCHASE_RETURN_LEDGER,
																								"dr_amount"				=> $total
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

							$to_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> $supplier->ledger_id,
																							"cr_amount"				=> $total
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
						}

						/**************************************************************************************/

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "purchase_return",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"				=> json_encode((array)$entered_purchase_return),
											"description" => 'purchase_return (Invoice No. -'  .$invoice_no .') is added successfully.'
										);
						$this->log_data_model->add_record($log_data);

						$purchase_return_items 				= $this->input->post('purchase_return_items');
						$purchase_return_items_array 	= explode("|", $this->input->post('purchase_return_items'));

						for ($i=0; $i < sizeof($purchase_return_items_array) ; $i++) { 
									
							$temp_purchase_return_item = (array)json_decode($purchase_return_items_array[$i]);
							$temp_purchase_return_item['purchase_return_id']  = $id;

							if($temp_purchase_return_item['expiry_date'] == '' || $temp_purchase_return_item['expiry_date'] == '0000-00-00')
								unset($temp_purchase_return_item['expiry_date']);
							else
								$temp_purchase_return_item['expiry_date'] = date('Y-m-d',strtotime($temp_purchase_return_item['expiry_date']));

							if($temp_purchase_return_item['mfg_date'] == '' || $temp_purchase_return_item['mfg_date'] == '0000-00-00')
								unset($temp_purchase_return_item['mfg_date']);
							else
								$temp_purchase_return_item['mfg_date'] = date('Y-m-d',strtotime($temp_purchase_return_item['mfg_date']));

							$this->purchase_return_model->add_purchase_return_item_record($temp_purchase_return_item);
						}

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success',  'Purchase return (Invoice No. -'.$invoice_no.') is added successfully.');
						redirect('purchase_return','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						$this->session->set_flashdata('failure',  'Purchase Return (Invoice No. -'.$invoice_no.') is failed to add.');
						redirect('purchase_return','refresh');
					}
				}
			}
			else
			{
				$data['warehouses'] 			= $this->warehouse_model->get_records();
				$data['supplier'] 				= $this->supplier_model->get_records();
				$data['company_setting']	= $this->company_settings_model->get_company_records();	
				$data['purchases']				= $this->purchase_model->get_purchase_records();
				
				
				
				$this->load->view('purchase_return/add',$data);
			}
		}	
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_purchase_return'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$purchase_return_id = $this->input->post('id');
				$old_purchase_return = $this->purchase_return_model->get_purchase_return_single_record($purchase_return_id);

				$this->form_validation->set_rules('purchase_return_date','Purchase Return Date','required');		
				$this->form_validation->set_rules('supplier_id','Supplier','required');
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');
				$this->form_validation->set_rules('invoice_no','Invoice No','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['purchase_return'] 						= $this->purchase_return_model->get_purchase_return_single_record($id);

					$data['purchase_return_items'] 			= $this->purchase_return_model->get_purchase_return_item_records($id);
					$data['supplier'] 				= $this->supplier_model->get_records();
					$data['supplier_detail']	= $this->supplier_model->get_single_record($data['purchase_return']->supplier_id);
					$data['discounts']				= $this->discount_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					
					$this->load->view('purchase_return/edit',$data);
				}
				else
				{
					$purchase_return_date = date('Y-m-d', strtotime($this->input->post('purchase_return_date')));
					$warehouse_id 				= $this->input->post('warehouse_id');
					$supplier_id 					= $this->input->post('supplier_id');

					$supplier 						= $this->supplier_model->get_single_record($supplier_id);
					$supplier_gstin 			= $supplier->gstin;

					$reference_no 				= $this->input->post('reference_no');
					$total_taxable_value 	= $this->input->post('total_taxable_value');
					$total_discount 			= $this->input->post('total_discount');
					$total_tax 						= $this->input->post('total_tax');
					$total 								= $this->input->post('total');
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

					$document 						= $this->input->post("document");

          if (!is_array($document)) {
            // Convert $document to an array with a single element
            $document = array($document);
          }

          $documents = implode(',', $document);
					

					$purchase_return_data = array(
																				"purchase_return_date"	=> $purchase_return_date,
																				"warehouse_id"	  			=> $warehouse_id,
																				"supplier_id"						=> $supplier_id,
																				"supplier_gstin"				=> $supplier_gstin,
																				"total_taxable_value" 	=> $total_taxable_value,	
																				"total_tax"							=> $total_tax,
																				"total_discount" 				=> $total_discount,	
																				"total" 								=> $total,
																				"internal_note"					=> $internal_note,
																				"external_note" 				=> $external_note,
																				"terms_and_condition"		=> $terms_and_condition,
																				"document" 				=> $documents,
																				"user_id" 							=> $this->session->userdata('user_id')
																			);

					if($this->purchase_return_model->edit_purchase_return_record($purchase_return_data,$purchase_return_id))
					{
						$entered_purchase_return = $this->purchase_return_model->get_purchase_return_single_record($purchase_return_id);
						$supplier 		= $this->supplier_model->get_single_record($entered_purchase_return->supplier_id);

						// update the ledgers
						
						/****************************** Start purchase_return Ledger ***********************************/

						$purchase_return_ledger 						= $this->ledger_model->get_single_record(PURCHASE_RETURN_LEDGER);
						$updated_purchase_return_ledger 		= array('closing_balance' => ($purchase_return_ledger->closing_balance  - $old_purchase_return->total + $total));
						$this->ledger_model->edit_record($updated_purchase_return_ledger,$purchase_return_ledger->id);

						/****************************** End purchase_return Ledger *************************************/

						/****************************** Start supplier Ledger *******************************/

						$supplier_ledger 					= $this->ledger_model->get_single_record($supplier->ledger_id);
						$updated_supplier_ledger  = array('closing_balance' => ($supplier_ledger->closing_balance + $total - $old_purchase_return->total  ));
						$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

						/****************************** End supplier Ledger *********************************/

						/****************************** Start TDS Ledger *******************************/

						// if($tds > 0)
						// {
						// 	$tds_ledger 					= $this->ledger_model->get_single_record(TDS_LEDGER);
						// 	$updated_tds_ledger  = array('closing_balance' => ($tds_ledger->closing_balance + $tds - $old_purchase_return->tds));
						// 	$this->ledger_model->edit_record($updated_tds_ledger,$tds_ledger->id);

						// 	$supplier_ledger 					= $this->ledger_model->get_single_record($supplier->ledger_id);
						// 	$updated_supplier_ledger = array('closing_balance' => ($supplier_ledger->closing_balance - $tds + $old_purchase_return->tds));
						// 	$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);
						// }

						
						/****************************** End TDS Ledger *********************************/

						/****************************** Add Transaction entry *******************************/

						// Remove old transaction entry

						$this->transaction_model->delete_record_by_entry_id($entered_purchase_return->id,PURCHASE_RETURN_MODULE);

						$transaction_header = array(
																				"entry_id"				=>  $purchase_return_id,
																				"module"					=>  PURCHASE_RETURN_MODULE,
																				"type"						=>	PURCHASE_RETURN_TRANSACTION_TYPE,
																				"amount"					=>	$total,
																				"voucher_date"		=>	$purchase_return_date,
																				"from_account"		=>	PURCHASE_RETURN_LEDGER,
																				"to_account"			=>	$supplier->ledger_id,
																				"reference_no"		=>	$entered_purchase_return->reference_no
																			);
						if($transaction_id = $this->transaction_model->add_record($transaction_header))
						{
							$from_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> PURCHASE_RETURN_LEDGER,
																								"dr_amount"				=> $total
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

							$to_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> $supplier->ledger_id,
																							"cr_amount"				=> $total
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
						}

						/**************************************************************************************/

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "purchase_return",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_purchase_return),
											"data"			=> json_encode((array)$entered_purchase_return),
											"description" 	=> 'purchase_return (Invoice No. -'  .$invoice_no .') is updated successfully.'
										);
						$this->log_data_model->add_record($log_data);
						$this->purchase_return_model->remove_purchase_return_item_records($purchase_return_id);

						$purchase_return_items 		= $this->input->post('purchase_return_items');
						$purchase_return_items_array 	= explode("|", $this->input->post('purchase_return_items'));

						for ($i=0; $i < sizeof($purchase_return_items_array) ; $i++) { 
									
							$temp_purchase_return_item = (array)json_decode($purchase_return_items_array[$i]);
							$temp_purchase_return_item['purchase_return_id']  = $purchase_return_id;

							if($temp_purchase_return_item['expiry_date'] == '' || $temp_purchase_return_item['expiry_date'] == '0000-00-00')
								unset($temp_purchase_return_item['expiry_date']);
							else
								$temp_purchase_return_item['expiry_date'] = date('Y-m-d',strtotime($temp_purchase_return_item['expiry_date']));

							if($temp_purchase_return_item['mfg_date'] == '' || $temp_purchase_return_item['mfg_date'] == '0000-00-00')
								unset($temp_purchase_return_item['mfg_date']);
							else
								$temp_purchase_return_item['mfg_date'] = date('Y-m-d',strtotime($temp_purchase_return_item['mfg_date']));

							$this->purchase_return_model->add_purchase_return_item_record($temp_purchase_return_item);
						}

						$this->session->set_flashdata('success',  'Purchase return (Invoice No. -'  .$invoice_no .') is updated successfully.');
						redirect('purchase_return','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure',  'Purchase return (Invoice No. -'  .$invoice_no .') is failed to update.');
						redirect('purchase_return','refresh');
					}
				}
			}
			else
			{
				$id = base64_decode($id);

				if($id != null)
				{
					$data['purchase_return'] 						= $this->purchase_return_model->get_purchase_return_single_record($id);

					if($data['purchase_return'] != null)
					{
						$data['purchase_return_items'] 			= $this->purchase_return_model->get_purchase_return_item_records($id);
						$data['supplier'] 				= $this->supplier_model->get_records();
						$data['supplier_detail']	= $this->supplier_model->get_single_record($data['purchase_return']->supplier_id);
						$data['discounts']				= $this->discount_model->get_records();
						$data['company_setting']	= $this->company_settings_model->get_company_records();	
						$data['warehouses'] 			= $this->warehouse_model->get_records();


						$this->load->view('purchase_return/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('purchase_return','refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('purchase_return','refresh');
				}
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_purchase_return'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 					= $this->input->post('id');
			$purchase_return 		= $this->purchase_return_model->get_purchase_return_single_record($id);
			$paid_amount 	= $this->transaction_model->get_total_transaction_amount($id,PURCHASE_RETURN_MODULE,PAYMENT_TRANSACTION_TYPE);

			if($paid_amount == 0 || $paid_amount == '')
			{
				$data = array('delete_status' => 1);
				if($this->purchase_return_model->edit_purchase_return_record($data,$id))
				{
					$supplier 		= $this->supplier_model->get_single_record($purchase_return->supplier_id);

					// update the ledgers
					/****************************** Start Sale Ledger ***********************************/

					$purchase_return_ledger 						= $this->ledger_model->get_single_record(PURCHASE_RETURN_LEDGER);
					$updated_purchase_return_ledger 		= array('closing_balance' => ($purchase_return_ledger->closing_balance - $purchase_return->total));
					$this->ledger_model->edit_record($updated_purchase_return_ledger,$purchase_return_ledger->id);

					/****************************** End Sale Ledger ***********************************/

					/****************************** Start Customer Ledger ***********************************/

					$supplier_ledger 					= $this->ledger_model->get_single_record($supplier->ledger_id);
					$updated_supplier_ledger  = array('closing_balance' => ($supplier_ledger->closing_balance - $purchase_return->total));
					$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

					/****************************** End Customer Ledger ***********************************/

					// Remove old transaction entry
					$this->transaction_model->delete_record_by_entry_id($id,PURCHASE_RETURN_MODULE);


					$this->session->set_flashdata('success', 'Purchase (Invoice No. -'.$purchase_return->invoice_no.') is deleted successfully.');
					redirect('purchase_return','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', 'Purchase (Invoice No. -'.$purchase_return->invoice_no.') is failed to delete.');
					redirect('purchase_return','refresh');
				}	
			}
			else
			{
				$this->session->set_flashdata('failure', 'Please delete the transaction entry to delete this Sale:'.$purchase_return->invoice_no);
				redirect('purchase_return','refresh');
			}

			
			
		}
	}

	function restore($id = null)
	{
		
			$id 					= base64_decode($id);
			$purchase_return 		= $this->purchase_return_model->get_purchase_return_single_record($id);
			$paid_amount 	= $this->transaction_model->get_total_transaction_amount($id,PURCHASE_RETURN_MODULE,PAYMENT_TRANSACTION_TYPE);

			
			$data = array('delete_status' => 0);
			if($this->purchase_return_model->edit_purchase_return_record($data,$id))
			{
				$supplier 		= $this->supplier_model->get_single_record($purchase_return->supplier_id);

				// update the ledgers
				/****************************** Start Sale Ledger ***********************************/

				$purchase_return_ledger 						= $this->ledger_model->get_single_record(PURCHASE_RETURN_LEDGER);
				$updated_purchase_return_ledger 		= array('closing_balance' => ($purchase_return_ledger->closing_balance + $purchase_return->total));
				$this->ledger_model->edit_record($updated_purchase_return_ledger,$purchase_return_ledger->id);

				/****************************** End Sale Ledger ***********************************/

				/****************************** Start Customer Ledger ***********************************/

				$supplier_ledger 					= $this->ledger_model->get_single_record($supplier->ledger_id);
				$updated_supplier_ledger  = array('closing_balance' => ($supplier_ledger->closing_balance + $purchase_return->total));
				$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

				/****************************** End Customer Ledger ***********************************/

				/****************************** Add Transaction entry *********************************/

				$transaction_header = array(
					"entry_id"				=>  $id,
					"module"					=>  PURCHASE_RETURN_MODULE,
					"type"						=>	PURCHASE_RETURN_TRANSACTION_TYPE,
					"amount"					=>	$purchase_return->total,
					"voucher_date"		=>	$purchase_return->purchase_return_date,
					"from_account"		=>	PURCHASE_RETURN_LEDGER,
					"to_account"			=>	$supplier->ledger_id,
					"reference_no"		=>	$purchase_return->reference_no
				);
				if($transaction_id = $this->transaction_model->add_record($transaction_header))
				{
					$from_transaction_detail = array(
														"transaction_id" 	=> $transaction_id,
														"voucher_type"		=> 'C',
														"ledger_id"				=> PURCHASE_RETURN_LEDGER,
														"dr_amount"				=> $purchase_return->total
													);
					// transaction detail record
					$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

					$to_transaction_detail = array(
													"transaction_id" 	=> $transaction_id,
													"voucher_type"		=> 'D',
													"ledger_id"				=> $supplier->ledger_id,
													"cr_amount"				=> $purchase_return->total
												);
					// transaction detail record
					$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
				}

				/**************************************************************************************/


				$this->session->set_flashdata('success', 'Purchase return(Invoice No. -'.$purchase_return->invoice_no.') is restored successfully.');
				redirect('purchase_return','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Purchase return(Invoice No. -'.$purchase_return->invoice_no.') is failed to restore.');
				redirect('purchase_return','refresh');
			}	
		
	}

	public function view($id = null)
	{
		if(!$this->permission_model->has_permission('edit_purchase_return'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{

			if (!$this->input->is_ajax_request()) 
			{
				if($id != null)
				{
					$id = base64_decode($id);

					$data['purchase_return'] 					= $this->purchase_return_model->get_purchase_return_single_record($id);

					if($data['purchase_return'] != null)
					{
						$data['purchase_return_items'] 		= $this->purchase_return_model->get_purchase_return_item_records($id);
						$data['supplier'] 			= $this->supplier_model->get_records();
						$data['supplier_detail']= $this->supplier_model->get_single_record($data['purchase_return']->supplier_id);
						$data['discounts']			= $this->discount_model->get_records();
						$data['company_setting']= $this->company_settings_model->get_company_records();	

						$this->load->view('purchase_return/view',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('purchase_return','refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('purchase_return','refresh');
				}
			}
			else
			{
				$response = array();

				$purchase_return_view_data['purchase_return'] = $this->purchase_return_model->get_purchase_return_single_record($id);
				$purchase_return_view_data['supplier_detail'] = $this->supplier_model->get_single_record($purchase_return_view_data['purchase_return']->supplier_id);	
				$purchase_return_view_data['paid_amount'] 		= $this->transaction_model->get_total_transaction_amount($id,PURCHASE_RETURN_MODULE,RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount($id,PURCHASE_RETURN_MODULE,TDS_TRANSACTION_TYPE);
				
				$response['purchase_return_view']	= $this->load->view('purchase_return/ajax/view',$purchase_return_view_data,TRUE);
				$response['due_amount']						= $purchase_return_view_data['purchase_return']->total - $purchase_return_view_data['paid_amount'];


				/*************************** Start Add Payment View ******************************/
				
				$add_payment_data['to_account'] 	= $this->ledger_model->get_records(array(BANK_ACCOUNT_GROUP,CASH_GROUP));			

				$response['from_account']		= $this->supplier_model->get_single_record($purchase_return_view_data['purchase_return']->supplier_id)->ledger_id;
				$response['add_transaction']= $this->load->view('purchase_return/ajax/add_payment',$add_payment_data,TRUE);

				/*************************** End Add Payment View ******************************/

				/*************************** Start Previous Transaction ******************************/

				$previous_transaction['transactions']  = $this->transaction_model->get_records_entry_id_and_module_wise($id,PURCHASE_RETURN_MODULE);
				$response['previous_transaction_view'] = $this->load->view('purchase_return/ajax/transaction',$previous_transaction,TRUE);

				/*************************** End Previous Transaction ******************************/

				echo json_encode($response);
			}
			
		}
	}

	public function view_delivery()
	{
		if ($this->input->is_ajax_request()) 
		{
			$purchase_return_id 																= $this->input->post('purchase_return_id');
			$delivery_detail_data['purchase_return_deliveries']= $this->purchase_return_delivery_model->get_delivery_records($purchase_return_id);
			$delivery_detail_data['purchase_return_items']			= $this->purchase_return_model->get_purchase_return_item_records($purchase_return_id);
			$delivery_detail_data['users']							= $this->ion_auth_model->users()->result();

			$response 														= array();
			$response['add_delivery_detail'] 			= $this->load->view('purchase_return/ajax/add_delivery_detail',$delivery_detail_data,TRUE);
			$response['purchase_return_delivery_detail'] = $this->load->view('purchase_return/ajax/view_purchase_return_delivery_detail',$delivery_detail_data,TRUE);
			$response['delivery_transaction'] 		= $this->load->view('purchase_return/ajax/delivery_transaction',$delivery_detail_data,TRUE);
			$response['is_all_products_delivered']= $this->is_all_purchase_return_products_delivered($purchase_return_id);

			echo json_encode($response);  
		}
	}

	public function add_purchase_return_delivery($purchase_return_id = null)
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$purchase_return_id 		= $this->input->post('purchase_return_id');
			$delivery_date 	= $this->input->post('delivery_date');
			$received_by 		= $this->input->post('received_by');

			$delivery_items = $this->input->post('delivery_items');



			$delivery_data = array(
														"purchase_return_id"			=> $purchase_return_id,
														"delivery_date" 	=> date('Y-m-d', strtotime($delivery_date)),
														"received_by"			=> $received_by,
														"user_id" 				=> $this->session->userdata('user_id')
													);

			// being transaction
			$this->db->trans_begin();	

			if($purchase_return_delivery_id = $this->purchase_return_delivery_model->add_delivery_record($delivery_data))
			{
				$delivery_items_array 	= explode("|", $delivery_items);

				for ($i=0; $i < sizeof($delivery_items_array) ; $i++) 
				{			
					$temp_delivery_item = (array)json_decode($delivery_items_array[$i]);

					if(sizeof($temp_delivery_item) > 0 && $temp_delivery_item['quantity'] > 0)
					{
						/******************************** ADD PRODUCT DELIVERY ******************************************/	
						
						$temp_delivery_item['purchase_return_delivery_id']  = $purchase_return_delivery_id;
						$this->purchase_return_delivery_model->add_delivery_item_record($temp_delivery_item);

						/******************************** ADD PRODUCT TO WAREHOUSE **************************************/	
						

						$product_id 			= $temp_delivery_item['product_id'];
						$quantity 				= $temp_delivery_item['quantity'];

						$product 					= $this->product_model->get_single_record($product_id);
						$purchase_return_delivery= $this->purchase_return_delivery_model->get_delivery_single_record($purchase_return_delivery_id);
						$purchase_return 				=	$this->purchase_return_model->get_purchase_return_single_record($purchase_return_delivery->purchase_return_id);
						$purchase_return_item 		= $this->purchase_return_model->get_single_purchase_return_item_record($purchase_return->id,$product_id);
						$warehouse_id 		= $purchase_return->warehouse_id;					
						$cost 						= $purchase_return_item->cost;


						$price 						= $purchase_return_item->price;


						// Check for product with zero value
						$warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id, $purchase_return_item->batch_no,$cost = null,$price = null);
						

						// Update the product quantity
						$new_quantity = $warehouse_product->quantity - $quantity ;
						$warehouse_product_data = array(
																					"warehouse_id" 	=> $warehouse_id,
																					"product_id"		=> $product_id,
																					"quantity"			=> $new_quantity
																				);
						$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);

						/******************************** END ADD PRODUCT TO WAREHOUSE **************************************/

					}
					
				}

				// commit transaction
				$this->db->trans_commit();	

				$response = array();
				$response['code'] = 1;
				$response['message'] = 'Delivery successfully added.';
				$response['is_all_products_delivered'] = $this->is_all_purchase_return_products_delivered($purchase_return_id);

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

			$delivery_detail_data['purchase_return_items'] 		= $this->purchase_return_model->get_purchase_return_item_records($purchase_return_id);
			$delivery_detail_data['users']							= $this->ion_auth_model->users()->result();

			$response 																	= array();
			$response['add_delivery_detail'] 						= $this->load->view('purchase_return/ajax/add_delivery_detail',$delivery_detail_data,TRUE);

			echo json_encode($response);
			
		}
	}

	public function delete_purchase_return_delivery()
	{
		$purchase_return_delivery_id 		= $this->input->post('purchase_return_delivery_id');
		$purchase_return_delivery 				= $this->purchase_return_delivery_model->get_delivery_single_record($purchase_return_delivery_id);
		$purchase_return_delivery_items 	= $this->purchase_return_delivery_model->get_delivery_item_records($purchase_return_delivery_id);
		
		// being transaction
		$this->db->trans_begin();	

		if($this->purchase_return_delivery_model->delete_delivery_record($purchase_return_delivery_id))
		{
			foreach ($purchase_return_delivery_items as $item) 
			{
				$this->purchase_return_delivery_model->delete_delivery_item_record($purchase_return_delivery_id,$item->product_id);

				/*************************** UPDATE PRODUCT QUANTITY IN WAREHOUSE ************************************/

				$delivery_quantity	= $item->quantity;

				$product 						= $this->product_model->get_single_record($item->product_id);
				$purchase_return 					= $this->purchase_return_model->get_purchase_return_single_record($purchase_return_delivery->purchase_return_id);
				$purchase_return_item 			= $this->purchase_return_model->get_single_purchase_return_item_record($purchase_return->id,$item->product_id);
				$cost 							= $purchase_return_item->cost;
				$price 							= $purchase_return_item->price;
				$warehouse_id 			= $purchase_return->warehouse_id;
				$product_id 				= $item->product_id;



				$warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id, $purchase_return_item->batch_no, $cost = null, $price = null);

				if($delivery_quantity == $warehouse_product->quantity)
				{
					// Set the cost to 0.00 and quantity to 0 of product
					
					$warehouse_product_data = array(
																				"warehouse_id" 	=> $warehouse_id,
																				"product_id"		=> $product_id,
																				"quantity"			=> 0
																			);
					$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);
				}
				else
				{
					// Only increase the quantity of product on delivery deleted.

					$new_quantity = $warehouse_product->quantity + $item->quantity;

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
			$response['is_all_products_delivered'] = $this->is_all_purchase_return_products_delivered($purchase_return_delivery->purchase_return_id);
			
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
			$id 														= base64_decode($id);
			$data['purchase_return'] 				= $this->purchase_return_model->get_purchase_return_single_record($id);
			$data['purchase_return_items'] 		= $this->purchase_return_model->get_purchase_return_item_records($id);
			$data['supplier_detail']	= $this->supplier_model->get_single_record($data['purchase_return']->supplier_id);
			$data['discounts']			= $this->discount_model->get_records();
			$data['company_setting']	= $this->company_settings_model->get_company_records();	

			$html = $this->load->view('purchase_return/pdf',$data,true);

			// echo $html;
			// exit;
			
			$this->pdf->loadHtml($html);
			$this->pdf->render();
			$this->pdf->stream("Purchase return-".$data['purchase_return']->reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('Purchase Return-'.$data['purchase_return']->reference_no.'.pdf','refresh');
		}
	}

	function email_invoice()
	{	
		$purchase_return_id 												= $this->input->post('purchase_return_id');

		$company_setting 								= $this->company_settings_model->get_company_records();	
		$purchase_return  													= $this->purchase_return_model->get_purchase_return_single_record($purchase_return_id);
		$purchase_return_items 										= $this->purchase_return_model->get_purchase_return_item_records($purchase_return_id);
		$supplier_detail 								= $this->supplier_model->get_single_record($purchase_return->supplier_id);

		$data['supplier']								= $supplier_detail;
		$data['purchase_return']										= $purchase_return;
		$data['company_setting']				= $company_setting;
		$data['purchase_return_items'] 						= $purchase_return_items;
		$data['supplier_detail']				= $supplier_detail;

		$mail_data 											= array();
		
		$mail_data['From'] 							= $company_setting->email;
		$mail_data['FromName'] 					= $company_setting->company_name;
		$mail_data['AddReplyTo'] 				= $company_setting->email;
		$mail_data['ConfirmReadingTo']	= $company_setting->email;
		$mail_data['To']								= $supplier_detail->email;
		$mail_data['Subject'] 					= "Invoice #".$purchase_return->reference_no." has generated by ".$company_setting->company_name;

		$mail_data['Body'] 							= $this->load->view('purchase_return/pdf',$data,true);


		$response = array();

		if($supplier_detail->email != '')
		{
			if($this->email_model->send_mail($mail_data))
			{
				$response['code'] 			= 1;
				$response['message'] 		= 'Invoice #'.$purchase_return->reference_no.' has been sent to '.$supplier_detail->company_name;
				echo json_encode($response);
			}
			else
			{
				$response['code'] 			= 0;
				$response['message'] 		= 'Failed to send Invoice #'.$purchase_return->reference_no.' has been sent to '.$supplier_detail->company_name;
				echo json_encode($response);
			}	
		}
		else
		{
			$response['code'] 				= 0;
			$response['message'] 			= $supplier_detail->email.' records does not have email';
			echo json_encode($response);
		}
	}

	public function is_all_purchase_return_products_delivered($purchase_return_id)
	{
		$total_ordered_quantity			= $this->purchase_return_delivery_model->get_total_no_of_quantity_ordered($purchase_return_id);
		$total_delivered_quantity		= $this->purchase_return_delivery_model->get_total_no_of_quantity_delivered($purchase_return_id);

		if(($total_ordered_quantity - $total_delivered_quantity) == 0)
			return true;
		else
			return false;
	}

	public function send_email($purchase_return_id = null)
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

				$purchase_return_id 		= $this->input->post("purchase_return_id");

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

					$purchase_return									= $this->purchase_return_model->get_purchase_return_single_record($purchase_return_id);
					$purchase_return_items 					= $this->purchase_return_model->get_purchase_return_item_records($purchase_return->id);
					$supplier_detail								= $this->supplier_model->get_single_record($purchase_return->supplier_id);
					$company_setting								= $this->company_settings_model->get_company_records();	
	
					$data['supplier'] 							= $this->supplier_model->get_records();
					$data['supplier_detail']				= $supplier_detail;
					$data['purchase_return']					= $purchase_return;
					$data['purchase_return_items'] 	= $purchase_return_items;
					$data['company_setting']				= $company_setting;
					$data['discounts']							= $this->discount_model->get_records();
					$data['message'] 								= $email_data['message'];

				
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

			
					$html = $this->load->view('purchase_return/pdf', $data, true);

					$pdf_filename = "Purchase return-".$data['purchase_return']->reference_no.".pdf"; // Set your desired filename

          $cid = $company_setting->cid;
          $pdf_path = FCPATH . 'assets/documents/' . $cid . '/purchase_return/' . $pdf_filename;

          // Create the target folder if it doesn't exist
          $targetDir = FCPATH . 'assets/documents/' . $cid . '/purchase_return/';
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
		
			$data['purchase_return'] 	= $this->purchase_return_model->get_purchase_return_single_record($purchase_return_id);
			$data['supplier']					= $this->supplier_model->get_single_record($data['purchase_return']->supplier_id);
			$data['company_setting']	= $this->company_settings_model->get_company_records();	
			$data['email_template'] 	= $this->email_template_model->get_record_by_module(EMAIL_TEMPLATE_MODULE_PURCHASE_RETURN);

			$response 												= array();
	  	$response['code']  								= 1;

			   
			$cc_emails = ''; 

			
			if (strpos($data['supplier']->email, ',') !== false) 
			{
				$emails = explode(',', $data['supplier']->email);
				$data['supplier']->email = trim($emails[0]);

				$cc_emails = implode(',', array_slice($emails, 1));
			}

			$data['cc_emails'] = $cc_emails;
			
			$response['email_modal_body'] 	= $this->load->view('purchase_return/ajax/email_modal_body',$data,TRUE);

			
	  	echo json_encode($response);
		}
	}


	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->purchase_return_model->get_datatables();
    $data 		= array();
   // $no 			= $_POST['start'];
$no = ($this->purchase_return_model->count_filtered() - $_POST['start']) + 1;
    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

        $delivered_product_qty = $this->purchase_return_delivery_model->get_total_no_of_quantity_delivered($item->id);
        $paid_amount = $this->transaction_model->get_total_transaction_amount($item->id, PURCHASE_RETURN_MODULE, RECEIPT_TRANSACTION_TYPE);

        //Email Button
        if($this->permission_model->has_permission('email_purchase_return'))
				{	
					// $table_body .= '
     //                      <a href="#" class="btn bg-olive btn-xs email_invoice" data-tt="tooltip" title="Send Invoice to supplier" ddata-purchase_return_id="'.$item->id.'">
     //                          <i class="fas fa-at email_icon"></i>
     //                        </a>  
     //                    ';
				}
				if($item->delete_status == DELETED)
        {
					if($this->permission_model->has_permission('restore_purchase_return'))
					{	
          $table_body .= '
                            <a href="'.base_url('purchase_return/restore/'.base64_encode($item->id)).'"  data-tt="tooltip" title="Restore Purchase return" class="btn btn-primary btn-xs">
                            <i class="fas fa-redo-alt"></i> 
                          </a>
													';
					}
        }
				else
				{
					if($this->permission_model->has_permission('email_purchase_return'))
					{	
						$table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('send_email').'" class="btn btn-success btn-sm text-white email-modal" data-purchase_return_id="'.$item->id.'">
													<i class="fas fa-at"></i> 
												</a>';
					
					}


				
					//View Button
					if($this->permission_model->has_permission('view_purchase_return') || $this->permission_model->has_permission('view_all_purchase_return'))
					{	
						$table_body .= ' 
															<a href="'.base_url('purchase_return/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="View purchase_return">
																<i class="fas fa-eye"></i> 
															</a>      
														';
					}

					// Edit Button        
					if($this->permission_model->has_permission('edit_purchase_return') || $this->permission_model->has_permission('edit_all_purchase_return'))
					{	
						if($paid_amount > 0 || $delivered_product_qty > 0)
						{

							$table_body .= 	 '
																	<a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-target="#purchase_return_edit" data-tt="tooltip" title="Edit purchase_return" data-purchase_return_id="'.$item->id.'">
																		<i class="fas fa-edit"></i> 
																	</a> 
																';
							
						}
						else
						{
							
							$table_body .= ' 
																<a href="'.base_url('purchase_return/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs"  data-tt="tooltip" title="Edit purchase_return">
																	<i class="fas fa-edit"></i> 
																</a>
															';
						}
						
					}

					// Delete Button
					if($this->permission_model->has_permission('delete_purchase_return') || $this->permission_model->has_permission('delete_all_purchase_return'))
					{	
						$table_body .= '
															<a href="#"  data-toggle="modal" data-target="#delete_purchase_return" data-tt="tooltip" title="Delete purchase_return" class="btn btn-danger btn-xs delete_purchase_return" data-purchase_return_id="'.$item->id.'">
																<i class="fas fa-trash"></i> 
															</a>
														';
					}
				}

				

				$ordered_quantity 	= (float)$this->purchase_return_delivery_model->get_total_no_of_quantity_ordered($item->id);
        		$delivered_quantity = (float)$this->purchase_return_delivery_model->get_total_no_of_quantity_delivered($item->id);

        		//paid amount
				$paid_amount_html 	=	'<span class="text-success">'.(($paid_amount == '') ? '0.000' : number_format_i($paid_amount)).'</span>';
				//due amount
				$due_amount_html 		=  '<span class="text-danger">'.number_format_i(($item->total-$paid_amount)).'</span>';

//Delievery status - FONTAWESOME ICONS + TEXT
//Delievery status - CLEANER VERSION
$delivered_quantity_html = '';

if($delivered_quantity == 0)
{
    $delivered_quantity_html .= '<div class="text-center">
                                    <button type="button" class="btn btn-xs btn-danger open_delivery_modal" data-target="#delivery-modal" data-toggle="modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_return_id="'.$item->id.'">
                                        Pending
                                    </button>
                                    <div class="mt-1">
                                        <img src="'.base_url('assets/images/not_delivered.png').'" width="40">
                                    </div>
                                </div>';
}
else if($delivered_quantity < $ordered_quantity && $delivered_quantity > 0)
{
    $delivered_quantity_html .= '<div class="text-center">
                                    <button type="button" class="btn btn-xs btn-warning open_delivery_modal" data-target="#delivery-modal" data-toggle="modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_return_id="'.$item->id.'">
                                        Partial
                                    </button>
                                    <div class="mt-1">
                                        <img src="'.base_url('assets/images/partial_delivered.png').'" width="40">
                                    </div>
                                </div>';
}
else if($delivered_quantity == $ordered_quantity)
{
    $delivered_quantity_html .= '<div class="text-center">
                                    <button type="button" class="btn btn-xs btn-success open_delivery_modal" data-target="#delivery-modal" data-toggle="modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_return_id="'.$item->id.'">
                                        Delivered
                                    </button>
                                    <div class="mt-1">
                                        <img src="'.base_url('assets/images/delivered.png').'" width="40">
                                    </div>
                                </div>';
}
				// //Delievery status
				// $delivered_quantity_html = '';




    //     if($delivered_quantity == 0)
    //     {
      
    //       $delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_return_id="'.$item->id.'">
				// 																<img src="'.base_url('assets/images/not_delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
				// 															</a>';
      
    //     }
    //     else if($delivered_quantity < $ordered_quantity && $delivered_quantity > 0)
    //     {
      
    //       $delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_return_id="'.$item->id.'">
				// 																<img src="'.base_url('assets/images/partial_delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
				// 															</a>';
      
    //     }
    //     else if($delivered_quantity == $ordered_quantity)
    //     {
					
    //       $delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_return_id="'.$item->id.'">
				// 																<img src="'.base_url('assets/images/delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
				// 															</a>';
      
    //     }

	
        //Reference No
        $reference_no_html = '<a href="'.base_url('purchase_return/view/'.base64_encode($item->id)).'" data-tt="tooltip" title="'.$this->lang->line('purchase_return_view').'">'.$item->reference_no.'</a>';

         //supplier Name
        $supplier_html = '<a href="'.base_url('supplier/view/'.base64_encode($item->supplier_id)).'" data-tt="tooltip" title="'.$this->lang->line('purchase_return_view_supplier_detail').'">'.$item->company_name.'</a>';
                      
			

				/* End Action column buttons*/

        $row = array();
		$no--;
 $row[] = $no;
	      //$row[] = $reference_no_html;
	      $row[] = ($item->delete_status == DELETED) ? '' : $item->invoice_no;
	      $row[] = ($item->delete_status == DELETED) ? '' : date('d-m-Y', strtotime($item->purchase_return_date));
	   		$row[] = ($item->delete_status == DELETED) ? '' : $item->name;
	      $row[] = ($item->delete_status == DELETED) ? '' : $supplier_html;
	    //  $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total_discount);
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
                    "recordsTotal" 		=> $this->purchase_return_model->count_all(),
                    "recordsFiltered" => $this->purchase_return_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function purchase_return_delete_confirmation()
  {
  	$purchase_return_id 														= $this->input->post('purchase_return_id');
  	$data['purchase_return'] 												= $this->purchase_return_model->get_purchase_return_single_record($purchase_return_id);

  	$response 																			= array();
  	$response['purchase_return_delete_modal_body'] 	= $this->load->view('purchase_return/ajax/purchase_return_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	public function purchase_return_edit_confirmation()
  {
  	$purchase_return_id 													= $this->input->post('purchase_return_id');
  	$data['purchase_return'] 											= $this->purchase_return_model->get_purchase_return_single_record($purchase_return_id);

  	$response 																		= array();
  	$response['purchase_return_edit_modal_body'] 	= $this->load->view('purchase_return/ajax/purchase_return_edit_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/

	public function get_purchase_items_by_purchase_id()
	{
		$purchase_id 											= $this->input->post('purchase_id');
		$data['purchase']									= $this->purchase_model->get_purchase_single_record($purchase_id);
		$data['purchase_items']						= $this->purchase_model->get_purchase_item_records($purchase_id);

  	$response 												= array();
  	$response['view_purchase_items'] 	= $this->load->view('purchase_return/ajax/view_purchase_items',$data,TRUE);

  	echo json_encode($response);
	}

	public function upload_documents() 
	{
    if (isset($_FILES["upload_document"])) 
		{
			$data['company_setting'] 	= $this->company_settings_model->get_company_records();

			$cid = $data['company_setting']->cid; 

			$targetDir = "./assets/documents/$cid/purchase_return/";

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
