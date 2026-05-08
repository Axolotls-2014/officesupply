<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;

class Credit_debit_note extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_credit_debit_note'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$this->load->view('credit_debit_note/list');	
		}
	}

	public function add($id = NULL)
	{

		if(!$this->permission_model->has_permission('add_credit_debit_note'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{	
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				
				$this->form_validation->set_rules("cdn_note_type","Note Type","required");
        // $this->form_validation->set_rules("cdn_tax_id","Tax","required");

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('credit_debit_note/add');
				}
				else
				{	
					$cdn_date 							= date('Y-m-d',strtotime($this->input->post('cdn_date')));
					$cdn_note_type 					= $this->input->post("cdn_note_type");
					$cdn_description 				= $this->input->post("cdn_description");
					$cdn_ledger_id 					= $this->input->post("cdn_ledger_id");
					$cdn_taxable_amount 		= $this->input->post("cdn_taxable_amount");
					$cdn_tax_id 						= $this->input->post("cdn_tax_id");
					$cdn_tax_type 					= $this->input->post("cdn_tax_type");
					$cdn_igst 							= $this->input->post("cdn_igst");
					$cdn_igst_tax 					= $this->input->post("cdn_igst_tax");
					$cdn_cgst 							= $this->input->post("cdn_cgst");
					$cdn_cgst_tax 					= $this->input->post("cdn_cgst_tax");
					$cdn_sgst 							= $this->input->post("cdn_sgst");
					$cdn_sgst_tax 					= $this->input->post("cdn_sgst_tax");
					$cdn_amount 						= $this->input->post("cdn_amount");

          $cdn_document 						= $this->input->post("cdn_document");

          $cdn_sale_ids             = (!empty($this->input->post("cdn_sale_ids"))) ? implode(",",$this->input->post("cdn_sale_ids")) : '';
          $cdn_purchase_ids         = (!empty($this->input->post("cdn_purchase_ids"))) ? implode(",",$this->input->post("cdn_purchase_ids")) : '';

          $cdn_sale_return_ids      = (!empty($this->input->post("cdn_sale_return_ids"))) ? implode(",",$this->input->post("cdn_sale_return_ids")) : '';
          $cdn_purchase_return_ids  = (!empty($this->input->post("cdn_purchase_return_ids"))) ? implode(",",$this->input->post("cdn_purchase_return_ids")) : '';

					$cdn_reference_no 			= $this->credit_debit_note_model->get_lastest_sequence_number($cdn_note_type);
          $credit_debit_note_items= $this->input->post("credit_debit_note_items");
				
					$data     = array(
													"cdn_date" 			          => $cdn_date,
													"cdn_reference_no" 			  => $cdn_reference_no,
                          "cdn_note_type" 		 		  => $cdn_note_type,
                          "cdn_description" 			  => $cdn_description,
                          "cdn_ledger_id" 	 			  => $cdn_ledger_id,	
                          "cdn_sale_ids" 	 			    => $cdn_sale_ids,	
                          "cdn_purchase_ids" 	 		  => $cdn_purchase_ids,	

                          "cdn_sale_return_ids" 	  => $cdn_sale_return_ids,	
                          "cdn_purchase_return_ids" => $cdn_purchase_return_ids,	

                          "cdn_taxable_amount" 		  => $cdn_taxable_amount,
                          "cdn_tax_id" 						  => $cdn_tax_id,
                          "cdn_tax_type" 					  => $cdn_tax_type,
                          "cdn_igst" 							  => $cdn_igst,
                          "cdn_igst_tax" 					  => $cdn_igst_tax,
                          "cdn_cgst" 							  => $cdn_cgst,
                          "cdn_cgst_tax" 					  => $cdn_cgst_tax,
                          "cdn_sgst" 							  => $cdn_sgst,
                          "cdn_sgst_tax" 					  => $cdn_sgst_tax,
                          "cdn_amount" 						  => $cdn_amount,
                          "cdn_document" 						=> $cdn_document
                        );


            // echo '<pre>';
            // print_r($data);
            // exit;

					// being transaction
					$this->db->trans_begin();
					
					if($id = $this->credit_debit_note_model->add_record($data))
					{
            
            if($credit_debit_note_items != '')
            {
              $creditDebitNoteItems = json_decode($credit_debit_note_items, true);

              foreach ($creditDebitNoteItems as $item) {
                $temp_data = array(
                    'credit_debit_note_id' => $id,
                    'entry_id' => $item['entry_id'],
                    'entry_item_id' => $item['entry_item_id'],
                    'entry_type' => $item['entry_type'],
                    'reference_no' => $item['reference_no'],
                    'product_name' => $item['product_name'],
                    'tax_rate' => $item['tax_rate'],
                    'quantity' => $item['quantity'],
                    'old_price' => $item['old_price'],
                    'old_taxable_value' => $item['old_taxable_value'],
                    'r_igst' => $item['r_igst'],
                    'r_cgst' => $item['r_cgst'],
                    'r_sgst' => $item['r_sgst'],
                    'old_tax' => $item['old_tax'],
                    'old_sub_total' => $item['old_sub_total'],
                    'new_price' => $item['new_price'],
                    'new_taxable_value' => $item['new_taxable_value'],
                    'new_tax' => $item['new_tax'],
                    'new_sub_total' => $item['new_sub_total']
                );
    
                $this->credit_debit_note_model->add_item_record($temp_data);
              }
            }
            


						/****************************** Start Transaction ***********************************/

						$ledger 						= $this->ledger_model->get_single_record($cdn_ledger_id);

						if($cdn_note_type == CREDIT_NOTE_TYPE_CREDIT || $cdn_note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
						{
							$updated_ledger    = array('closing_balance' => ($ledger->closing_balance - $cdn_amount));
							$this->ledger_model->edit_record($updated_ledger,$cdn_ledger_id);	

							$transaction_header = array(
																					"entry_id"				=>  $id,
																					"module"					=>  CREDIT_DEBIT_NOTE_MODULE,
																					"type"						=>	CREDIT_NOTE_TRANSACTION_TYPE,
																					"amount"					=>	$cdn_amount,
																					"voucher_date"		=>	$cdn_date,
																					"from_account"		=>	"",
																					"to_account"			=>	$cdn_ledger_id,
																					"reference_no"		=>	$cdn_reference_no
																				);
							if($transaction_id = $this->transaction_model->add_record($transaction_header))
							{
								$from_transaction_detail = array(
																									"transaction_id" 	=> $transaction_id,
																									"voucher_type"		=> 'C',
																									"ledger_id"				=> $cdn_ledger_id,
																									"dr_amount"				=> $cdn_amount
																								);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($from_transaction_detail);
							}
						}
						else
						{
							$updated_ledger    = array('closing_balance' => ($ledger->closing_balance - $cdn_amount));
							$this->ledger_model->edit_record($updated_ledger,$cdn_ledger_id);

							$transaction_header = array(
																					"entry_id"				=>  $id,
																					"module"					=>  CREDIT_DEBIT_NOTE_MODULE,
																					"type"						=>	DEBIT_NOTE_TRANSACTION_TYPE,
																					"amount"					=>	$cdn_amount,
																					"voucher_date"		=>	$cdn_date,
																					"from_account"		=>	$cdn_ledger_id,
																					"to_account"			=>	'',
																					"reference_no"		=>	$cdn_reference_no
																				);
							if($transaction_id = $this->transaction_model->add_record($transaction_header))
							{
								$to_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'D',
																								"ledger_id"				=> $cdn_ledger_id,
																								"cr_amount"				=> $cdn_amount
																							);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
							}
						}

						/****************************** Start Transaction ***********************************/

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'Credit debit note is added successfully.';
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Credit debit note is added successfully');
							redirect('credit_debit_note','refresh');
						}
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 0;
							$response['message']						= 'Credit debit note is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Credit debit note is failed to add.');
							redirect('credit_debit_note','refresh');
						}
						
					}
				}
			}
			else
			{
				$data 											= array();
				$data['cdn_id'] 						= $id;
				$data['ledger_accounts'] 		= $this->ledger_model->get_records(array(SUNDRY_DEBTORS_GROUP,SUNDRY_CREDITORS_GROUP));
				$data['taxes'] 							= $this->tax_model->get_records();
				$data['company_setting'] 		= $this->company_settings_model->get_company_records();

				if($this->input->is_ajax_request()) 
				{
					$response 										= array();
					$response['code']							= 1; 						
			  	$response['add_credit_debit_note_modal_body'] 	= $this->load->view('credit_debit_note/ajax/add_credit_debit_note_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('credit_debit_note/add',$data);
				}
			}
		}
	}
	
	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_credit_debit_note'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{

				$id = $this->input->post('id');
				$old_credit_debit_note = $this->credit_debit_note_model->get_single_record($id);

				$this->form_validation->set_rules("cdn_note_type","Note Type","required");
        // $this->form_validation->set_rules("cdn_tax_id","Tax","required");
			
				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('credit_debit_note/edit');
				}
				else
				{
					$cdn_date 							= date('Y-m-d',strtotime($this->input->post('cdn_date')));
					$cdn_note_type 					= $this->input->post("cdn_note_type");
					$cdn_description 				= $this->input->post("cdn_description");
					$cdn_ledger_id 					= $this->input->post("cdn_ledger_id");
					$cdn_taxable_amount 		= $this->input->post("cdn_taxable_amount");
					$cdn_tax_id 						= $this->input->post("cdn_tax_id");
					$cdn_tax_type 					= $this->input->post("cdn_tax_type");
					$cdn_igst 							= $this->input->post("cdn_igst");
					$cdn_igst_tax 					= $this->input->post("cdn_igst_tax");
					$cdn_cgst 							= $this->input->post("cdn_cgst");
					$cdn_cgst_tax 					= $this->input->post("cdn_cgst_tax");
					$cdn_sgst 							= $this->input->post("cdn_sgst");
					$cdn_sgst_tax 					= $this->input->post("cdn_sgst_tax");
					$cdn_amount 						= $this->input->post("cdn_amount");

          $cdn_document 						= $this->input->post("cdn_document");


          $cdn_sale_ids           = (!empty($this->input->post("cdn_sale_ids"))) ? implode(",",$this->input->post("cdn_sale_ids")) : '';
          $cdn_purchase_ids       = (!empty($this->input->post("cdn_purchase_ids"))) ? implode(",",$this->input->post("cdn_purchase_ids")) : '';

          $cdn_sale_return_ids           = (!empty($this->input->post("cdn_sale_return_ids"))) ? implode(",",$this->input->post("cdn_sale_return_ids")) : '';
          $cdn_purchase_return_ids       = (!empty($this->input->post("cdn_purchase_return_ids"))) ? implode(",",$this->input->post("cdn_purchase_return_ids")) : '';

          $credit_debit_note_items= $this->input->post("credit_debit_note_items");

				
					$data     = array(
                          "cdn_date" 					=> $cdn_date,
                          "cdn_note_type" 		=> $cdn_note_type,
                          "cdn_description" 	=> $cdn_description,
                          "cdn_ledger_id" 		=> $cdn_ledger_id,	
                          "cdn_sale_ids" 	 		=> $cdn_sale_ids,	
                          "cdn_purchase_ids" 	=> $cdn_purchase_ids,	

                          "cdn_sale_return_ids" 	 		=> $cdn_sale_return_ids,	
                          "cdn_purchase_return_ids" 	=> $cdn_purchase_return_ids,	

                          "cdn_taxable_amount"=> $cdn_taxable_amount,
                          "cdn_tax_id" 				=> $cdn_tax_id,
                          "cdn_tax_type" 			=> $cdn_tax_type,
                          "cdn_igst" 					=> $cdn_igst,
                          "cdn_igst_tax" 			=> $cdn_igst_tax,
                          "cdn_cgst" 					=> $cdn_cgst,
                          "cdn_cgst_tax" 			=> $cdn_cgst_tax,
                          "cdn_sgst" 					=> $cdn_sgst,
                          "cdn_sgst_tax" 			=> $cdn_sgst_tax,
                          "cdn_amount" 				=> $cdn_amount,
                          "cdn_document" 				=> $cdn_document
                        );

					if($this->credit_debit_note_model->edit_record($data,$id))
					{

            $this->credit_debit_note_model->remove_item_records($id);

            if($credit_debit_note_items != '')
            {
              $creditDebitNoteItems = json_decode($credit_debit_note_items, true);

              foreach ($creditDebitNoteItems as $item) {
                $temp_data = array(
                    'credit_debit_note_id' => $id,
                    'entry_id' => $item['entry_id'],
                    'entry_item_id' => $item['entry_item_id'],
                    'entry_type' => $item['entry_type'],
                    'reference_no' => $item['reference_no'],
                    'product_name' => $item['product_name'],
                    'tax_rate' => $item['tax_rate'],
                    'quantity' => $item['quantity'],
                    'old_price' => $item['old_price'],
                    'old_taxable_value' => $item['old_taxable_value'],
                    'r_igst' => $item['r_igst'],
                    'r_cgst' => $item['r_cgst'],
                    'r_sgst' => $item['r_sgst'],
                    'old_tax' => $item['old_tax'],
                    'old_sub_total' => $item['old_sub_total'],
                    'new_price' => $item['new_price'],
                    'new_taxable_value' => $item['new_taxable_value'],
                    'new_tax' => $item['new_tax'],
                    'new_sub_total' => $item['new_sub_total']
                );
    
                $this->credit_debit_note_model->add_item_record($temp_data);
              }
            }
            

						/****************************** Restore Start Transaction ***********************************/

						$ledger 						= $this->ledger_model->get_single_record($old_credit_debit_note->cdn_ledger_id);

						if($old_credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT || $old_credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
						{
							$updated_ledger    = array('closing_balance' => ($ledger->closing_balance + $old_credit_debit_note->cdn_amount));
							$this->ledger_model->edit_record($updated_ledger,$old_credit_debit_note->cdn_ledger_id);	

							$transaction_header = array(
																					"entry_id"				=>  $old_credit_debit_note->cdn_id,
																					"module"					=>  CREDIT_DEBIT_NOTE_MODULE,
																					"type"						=>	CREDIT_NOTE_TRANSACTION_TYPE,
																					"amount"					=>	$old_credit_debit_note->cdn_amount,
																					"voucher_date"		=>	$old_credit_debit_note->cdn_date,
																					"from_account"		=>	"",
																					"to_account"			=>	$old_credit_debit_note->cdn_ledger_id,
																					"reference_no"		=>	$old_credit_debit_note->cdn_reference_no
																				);
							if($transaction_id = $this->transaction_model->add_record($transaction_header))
							{
								$from_transaction_detail = array(
																									"transaction_id" 	=> $transaction_id,
																									"voucher_type"		=> 'C',
																									"ledger_id"				=> $old_credit_debit_note->cdn_ledger_id,
																									"dr_amount"				=> $old_credit_debit_note->cdn_amount
																								);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($from_transaction_detail);
							}
						}
						else
						{
							$updated_ledger    = array('closing_balance' => ($ledger->closing_balance + $old_credit_debit_note->cdn_amount));
							$this->ledger_model->edit_record($updated_ledger,$cdn_ledger_id);

							$transaction_header = array(
																					"entry_id"				=>  $old_credit_debit_note->cdn_id,
																					"module"					=>  CREDIT_DEBIT_NOTE_MODULE,
																					"type"						=>	DEBIT_NOTE_TRANSACTION_TYPE,
																					"amount"					=>	$old_credit_debit_note->cdn_amount,
																					"voucher_date"		=>	$old_credit_debit_note->cdn_date,
																					"from_account"		=>	$old_credit_debit_note->cdn_ledger_id,
																					"to_account"			=>	'',
																					"reference_no"		=>	$old_credit_debit_note->cdn_reference_no
																				);
							if($transaction_id = $this->transaction_model->add_record($transaction_header))
							{
								$to_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'D',
																								"ledger_id"				=> $old_credit_debit_note->cdn_ledger_id,
																								"cr_amount"				=> $old_credit_debit_note->cdn_amount
																							);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
							}
						}

						// Remove old transaction entry
						$this->transaction_model->delete_record_by_entry_id($old_credit_debit_note->cdn_id,CREDIT_DEBIT_NOTE_MODULE);

						/****************************** Restore Transaction ***********************************/

						/****************************** Start Transaction ***********************************/

						$ledger 						= $this->ledger_model->get_single_record($cdn_ledger_id);

						if($cdn_note_type == CREDIT_NOTE_TYPE_CREDIT || $cdn_note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
						{
							$updated_ledger    = array('closing_balance' => ($ledger->closing_balance - $cdn_amount));
							$this->ledger_model->edit_record($updated_ledger,$cdn_ledger_id);	

							$transaction_header = array(
																					"entry_id"				=>  $id,
																					"module"					=>  CREDIT_DEBIT_NOTE_MODULE,
																					"type"						=>	CREDIT_NOTE_TRANSACTION_TYPE,
																					"amount"					=>	$cdn_amount,
																					"voucher_date"		=>	$cdn_date,
																					"from_account"		=>	"",
																					"to_account"			=>	$cdn_ledger_id,
																					"reference_no"		=>	$old_credit_debit_note->cdn_reference_no
																				);
							if($transaction_id = $this->transaction_model->add_record($transaction_header))
							{
								$from_transaction_detail = array(
																									"transaction_id" 	=> $transaction_id,
																									"voucher_type"		=> 'C',
																									"ledger_id"				=> $cdn_ledger_id,
																									"dr_amount"				=> $cdn_amount
																								);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($from_transaction_detail);
							}
						}
						else
						{
							$updated_ledger    = array('closing_balance' => ($ledger->closing_balance - $cdn_amount));
							$this->ledger_model->edit_record($updated_ledger,$cdn_ledger_id);

							$transaction_header = array(
																					"entry_id"				=>  $id,
																					"module"					=>  CREDIT_DEBIT_NOTE_MODULE,
																					"type"						=>	DEBIT_NOTE_TRANSACTION_TYPE,
																					"amount"					=>	$cdn_amount,
																					"voucher_date"		=>	$cdn_date,
																					"from_account"		=>	$cdn_ledger_id,
																					"to_account"			=>	'',
																					"reference_no"		=>	$old_credit_debit_note->cdn_reference_no
																				);
							if($transaction_id = $this->transaction_model->add_record($transaction_header))
							{
								$to_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'D',
																								"ledger_id"				=> $cdn_ledger_id,
																								"cr_amount"				=> $cdn_amount
																							);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
							}
						}

						/****************************** Start Transaction ***********************************/


						if($this->input->is_ajax_request()) 
						{
							$response['code'] = 1;
							$response['message'] = 'Credit debit note is updated successfully';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Credit debit note is updated successfully');
							redirect('credit_debit_note','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code']  		= 0;
							$response['message'] 	= 'Credit debit note is failed to update';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Credit debit note is failed to update');
							redirect('credit_debit_note','refresh');	
						}
						
					}
				}
				
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($id);
					
					$data['credit_debit_note'] 	= $this->credit_debit_note_model->get_single_record($id);
          
					$data['cdn_id'] 						= $id;
          $data['sales']              = null;
          $data['purchases']          = null;

          $data['sales_return']       = null;
          $data['purchase_return']    = null;

					if($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT || $data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER){
            $data['ledger_accounts'] 		= $this->supplier_model->get_records();
            $supplier                 = $this->utility_model->get_records_by_field('supplier','ledger_id',$data['credit_debit_note']->cdn_ledger_id,$row = true,$check_delete_status = false);
            $data['purchases']    = $this->utility_model->get_records_by_field('purchase','supplier_id',$supplier->id,$row = false,$check_delete_status = false);

            $data['purchase_return']    = $this->utility_model->get_records_by_field('purchase_return','supplier_id',$supplier->id,$row = false,$check_delete_status = false);
          }
					else{
            $data['ledger_accounts'] 		= $this->customer_model->get_records();
            $customer                 = $this->utility_model->get_records_by_field('customer','ledger_id',$data['credit_debit_note']->cdn_ledger_id,$row = true,$check_delete_status = false);
            $data['sales']    = $this->utility_model->get_records_by_field('sale','customer_id',$customer->id,$row = false,$check_delete_status = false);

            $data['sales_return']    = $this->utility_model->get_records_by_field('sales_return','customer_id',$customer->id,$row = false,$check_delete_status = false);
          }
						

					$data['taxes'] 							= $this->tax_model->get_records();
					$data['company_setting'] 		= $this->company_settings_model->get_company_records();
          $data['records']            = $this->credit_debit_note_model->get_item_records($id);
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['edit_credit_debit_note_modal_body'] = $this->load->view('credit_debit_note/ajax/edit_credit_debit_note_modal_body',$data,TRUE);
					$response['ledger_accounts'] = $data['ledger_accounts'];

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['credit_debit_note'] 		= $this->credit_debit_note_model->get_single_record($id);

					if($data['credit_debit_note'] != null)
					{

						$this->load->view('credit_debit_note/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('credit_debit_note/');
					}
				}
				
			}
		}
	}
	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_credit_debit_note'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 							= $this->input->post('id');

			$data = array('cdn_delete_status' => 1);

			$credit_debit_note = $this->credit_debit_note_model->get_single_record($id);

			if($this->credit_debit_note_model->edit_record($data,$id))
			{

				/****************************** Start Transaction ***********************************/

				$ledger 						= $this->ledger_model->get_single_record($credit_debit_note->cdn_ledger_id);

				if($credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT || $credit_debit_note->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
				{
					$updated_ledger    = array('closing_balance' => ($ledger->closing_balance + $credit_debit_note->cdn_amount));
					$this->ledger_model->edit_record($updated_ledger,$credit_debit_note->cdn_ledger_id);	
				}
				else
				{
					$updated_ledger    = array('closing_balance' => ($ledger->closing_balance + $credit_debit_note->cdn_amount));
					$this->ledger_model->edit_record($updated_ledger,$credit_debit_note->cdn_ledger_id);
				}

				// Remove old transaction entry
				$this->transaction_model->delete_record_by_entry_id($credit_debit_note->cdn_id,CREDIT_DEBIT_NOTE_MODULE);

				/****************************** Start Transaction ***********************************/
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= 'Credit debit note is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Credit debit note is deleted successfully');
					redirect('credit_debit_note','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Credit debit note is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Credit debit note is failed to delete');
					redirect('credit_debit_note');
				}
			}
		}
	}

	function get_record_detail()
	{
		$cdn_id 			= $this->input->post('cdn_id');
		$data['credit_debit_note'] = $this->credit_debit_note_model->get_single_record($cdn_id);

		echo json_encode($data);
	}


  
  function get_sale_purchase_records($note_type = null)
	{
    
		if($note_type == null && !in_array($note_type,array(CREDIT_NOTE_TYPE_DEBIT,CREDIT_NOTE_TYPE_CREDIT))  && $this->input->server('REQUEST_METHOD') !== 'POST')
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

      $ledger_id = $this->input->post('cdn_ledger_id');
			if($note_type == CREDIT_NOTE_TYPE_DEBIT || $note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
			{
				$response['code']         = 1;
        $response['note_type']    = $note_type;

        $supplier                 = $this->utility_model->get_records_by_field('supplier','ledger_id',$ledger_id,$row = true,$check_delete_status = false);
        $response['purchases']    = $this->utility_model->get_records_by_field('purchase','supplier_id',$supplier->id,$row = false,$check_delete_status = false);

        $response['purchase_return']    = $this->utility_model->get_records_by_field('purchase_return','supplier_id',$supplier->id,$row = false,$check_delete_status = false);

				echo json_encode($response);
			}
			else
			{
				$response['code']         = 1;
        $response['note_type']    = $note_type;

        $customer                 = $this->utility_model->get_records_by_field('customer','ledger_id',$ledger_id,$row = true,$check_delete_status = false);
        $response['sales']        = $this->utility_model->get_records_by_field('sale','customer_id',$customer->id,$row = false,$check_delete_status = false);

        $response['sales_return']        = $this->utility_model->get_records_by_field('sales_return','customer_id',$customer->id,$row = false,$check_delete_status = false);

				echo json_encode($response);
			}
		}
	}

	function get_ledger_accounts($note_type = null)
	{
		if($note_type == null && !in_array($note_type,array(CREDIT_NOTE_TYPE_DEBIT,CREDIT_NOTE_TYPE_CREDIT)))
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
			if($note_type == CREDIT_NOTE_TYPE_DEBIT || $note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER)
			{
				$response['code'] = 1;
				$response['record_type']  = SUNDRY_CREDITORS_GROUP;
				$response['records']      = $this->supplier_model->get_records();

				echo json_encode($response);
			}
			else
			{
				$response['code'] = 1;
				$response['record_type'] = SUNDRY_DEBTORS_GROUP;
				$response['records'] = $this->customer_model->get_records();	

				echo json_encode($response);
			}
		}
	}

  public function credit_debit_note_delete_confirmation()
  {
  	$cdn_id 				= $this->input->post('cdn_id');
  	$data['credit_debit_note']		= $this->credit_debit_note_model->get_single_record($cdn_id);

  	$response 																		= array();
  	$response['delete_credit_debit_note_modal_body'] 	= $this->load->view('credit_debit_note/ajax/delete_credit_debit_note_modal_body',$data,TRUE);

  	echo json_encode($response);
	}

	public function pdf($id = null, $orientation = null)
	{
			
		if($id != null)
		{	
			$id 											= base64_decode($id);
			$data['credit_debit_note'] 	= $this->credit_debit_note_model->get_single_record($id);
      $data['credit_debit_note_items']  = $this->credit_debit_note_model->get_item_records($id);
			$data['company_setting']			= $this->company_settings_model->get_company_records();	

      
      $data['customer'] 	= $this->customer_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);
      $data['supplier'] 	= $this->supplier_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);
			$cdn_note_type = '';

			if ($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT || $data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT_SUPPLIER) {
					$cdn_note_type = "CREDIT NOTE";
			} 
			else if ($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT || $data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT_CUSTOMER) {
					$cdn_note_type = "DEBIT NOTE";
			} 
			else {
					$cdn_note_type = "Advance Refund Voucher";
			}
		
      if($orientation != null)
        $html = $this->load->view('credit_debit_note/pdf',$data,true);	

			$this->pdf->loadHtml($html);
			$this->pdf->set_paper('A4', $orientation); 
			$this->pdf->render();
			$this->pdf->stream($cdn_note_type .'-'.$data['credit_debit_note']->cdn_reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('credit_debit_note','refresh');
		}
	}

  // public function view($id = null)
	// {
			
	// 	if($id != null)
	// 	{	
	// 		$id 											= base64_decode($id);
	// 		$data['credit_debit_note'] 	= $this->credit_debit_note_model->get_single_record($id);
	// 		$data['company_setting']			= $this->company_settings_model->get_company_records();	

      
  //     $data['customer'] 	= $this->customer_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);
  //     $data['supplier'] 	= $this->supplier_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);
		
	// 		$this->load->view('credit_debit_note/view',$data);
  //   }
	// 	else
	// 	{
	// 		$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
	// 		redirect('credit_debit_note','refresh');
	// 	}
	// }

  public function view($id = null)
	{
		if(!$this->permission_model->has_permission('view_credit_debit_note'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{

			if($id != null)
      {
        $id 											        = base64_decode($id);
        $data['credit_debit_note'] 	      = $this->credit_debit_note_model->get_single_record($id);
        $data['credit_debit_note_items']  = $this->credit_debit_note_model->get_item_records($id);
        $data['company_setting']			    = $this->company_settings_model->get_company_records();	

        $data['customer'] 	              = $this->customer_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);
        $data['supplier'] 	              = $this->supplier_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);

        $this->load->view('credit_debit_note/view',$data);
      }
      else
      {
        $this->session->set_flashdata('failure',  'You have tried to access broken URL.');
        redirect('credit_debit_note','refresh');
      }
      
    }
   
	}

	public function whatsapp_pdf($id = null)
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$id = $this->input->post('id');
			
			$data['credit_debit_note'] 	= $this->credit_debit_note_model->get_single_record($id);
			if ($data['credit_debit_note'] == null) {
        // Handle the case when the record is not found
        echo json_encode(array('error' => 'Credit/Debit note record not found.'));
	    }

			$data['credit_debit_note_type'] = '';


			if ($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT) {
		    $data['credit_debit_note_type'] = $this->customer_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);
			} elseif ($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT) {
		    $data['credit_debit_note_type'] = $this->supplier_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);
			}

			// Include phone number in the response
			if ($data['credit_debit_note_type']) {
			    $data['phone_number'] = ($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT)
			        ? $data['credit_debit_note_type']->phone
			        : $data['credit_debit_note_type']->phone;
			}	

			$data['company_setting']					= $this->company_settings_model->get_company_records();	

			$html = $this->load->view('credit_debit_note/pdf',$data,true);	
  		$this->pdf->loadHtml($html);
			$this->pdf->render();
			$filename = "Credit_debit_note_" . $data['credit_debit_note']->cdn_reference_no . ".pdf";
			$pdfFilePath = './assets/documents/' . $filename; 

			// Save the PDF file on the server
			file_put_contents($pdfFilePath, $this->pdf->output());

			// Get the mobile numbers from the AJAX request and split them into an array
	    $mobileNumbers = $this->input->post('mobile_numbers');
    	$mobileNumbers = explode(',', $mobileNumbers);

  
			// Include ledger name in the response
			if ($data['credit_debit_note_type']) {
			   $name = ($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT)
			        ? $data['credit_debit_note_type']->customer_name
			        : $data['credit_debit_note_type']->company_name;
			}	

			$totalAmount = round($data['credit_debit_note']->cdn_amount, 2);

			$message = "Hi " . $name . "\n\n" .
								  "I hope you're doing well. Here's the information regarding recent " . ucfirst($data['credit_debit_note']->cdn_note_type) . ": " . $data['credit_debit_note']->cdn_reference_no . "\n\n" .
    								ucfirst($data['credit_debit_note']->cdn_note_type) . " Summary:\n" .
								    "Total Amount: " . $totalAmount . "\n\n" .
								    "If you have any questions or concerns regarding the " . $data['credit_debit_note']->cdn_note_type . ", feel free to reach out to us. We are always here to assist you.\n\n" .
								    "Thank you for choosing our services. We truly value your business.\n" .
								    "Powered by Sarvaaushadhi Store \n https://sarvaaushadhi.store";

			         
			$media_url = base_url('assets/documents') . "/" . $filename; // Corrected media URL without extra double quotes

			// API URL
			$api_url = WHATSAPP_API_URL;

			$access_token = $data['company_setting']->access_token;

      $instance_id 	= $data['company_setting']->whatsapp_instance_id;

       // Initialize an array to track successful insertions
	    $data = array();

	    // Loop through the mobile numbers and process each
	    foreach ($mobileNumbers as $whatsapp_number) {
	        // Skip if the number is blank or null
	        if (empty($whatsapp_number)) {
	            continue;
	        }

	        // Prepare data for insertion
	        $insertData = array(
	            "access_token" => $access_token,
	            "instance_id" => $instance_id,
	            "recipient_no" => $whatsapp_number,
	            "message" => $message,
	            "document_path" => $media_url,
	            "api_url" => $api_url,
              "module" => 'credit_debit_note',
              "entry_id" => $id
	        );

					// Attempt to add record to the database
	        $insertedId = $this->whatsapp_cron_job_model->add_record($insertData);

	        if ($insertedId) {
	            $data[] = $insertedId;
	        }
	    }

	    if (!empty($data)) 
	    {
        $this->db->trans_commit();

				if($this->input->is_ajax_request())
				{	
					$response 											= array();
					$response['code'] 							= 1;
					$response['id'] 								= $id;
					$response['message']						= 'Whatsapp cron job is added successfully.';
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Whatsapp cron job is added successfully');
					redirect('credit_debit_note','refresh');
				}
    	} 
    	else 
    	{
    		$this->db->trans_commit();

				if($this->input->is_ajax_request())
				{	
					$response 											= array();
					$response['code'] 							= 0;
					$response['id'] 								= $id;
					$response['message']						= 'Whatsapp cron job is failed to add.';
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Whatsapp cron job is failed to add.');
					redirect('credit_debit_note','refresh');
				}

        // Rollback transaction if no successful inserts
      }

      // if($instance_id != '')
      // {
      //   $success = true;
      //   foreach ($mobileNumbers as $whatsapp_number) {
      //     // API parameters for WhatsApp message
      //     $params = array(
      //         'number' => '91'.$whatsapp_number,
      //         'type' => 'image', // WhatsApp treats PDFs as images when sent as media.
      //         'message' => $message,
      //         'media_url' => $media_url,
      //         'instance_id' => $instance_id, // Replace with the actual instance_id.
      //         'access_token' => '64c91b0deb85e', // Replace with the actual access_token.
      //     );

      //     // Initialize cURL session
      //     $ch = curl_init($api_url);

      //     // Set cURL options
      //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //     curl_setopt($ch, CURLOPT_POST, true);
      //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

      //     // Execute cURL session and get the response
      //     $response = curl_exec($ch);

      //     // Check if the cURL request was successful
      //     if ($response === false) {
      //         // cURL request failed, handle the error here
      //         $error_message = curl_error($ch);
      //         // You can log the error or set the flag to indicate failure
      //         $success = false;
      //     }

      //     // Close cURL session
      //     curl_close($ch);
      //   }

      //   if (file_exists($media_url)) {
      //       unlink($media_url);
      //   }
        
      //   // Send the API response back to the frontend as JSON
      //   header('Content-Type: application/json');
      //   if ($success) {
      //       echo json_encode(array('status' => 'success', 'message' => 'PDF sent successfully via WhatsApp!'));
      //   } else {
      //       echo json_encode(array('status' => 'error', 'message' => 'Failed to send PDF via WhatsApp. Please try again later.'));
      //   }
      // }

      // else
      // {
      //   echo json_encode(array('status' => 'error', 'message' => 'Whatsapp instance ID not available. Go to company settings and set whatsapp instance ID.'));
      // }
		}
		else
		{
		
			$data['credit_debit_note'] 	= $this->credit_debit_note_model->get_single_record($id);


	    if (!$data['credit_debit_note']) {
	        // Handle the case when the record is not found
	        echo json_encode(array('status' => 'error', 'message' => 'Credit/Debit note record not found.'));
	    }

			$data['credit_debit_note_type'] = '';


			if ($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT) {
			    $data['credit_debit_note_type'] = $this->customer_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);
			} elseif ($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_DEBIT) {
			    $data['credit_debit_note_type'] = $this->supplier_model->get_single_record_by_ledger_id($data['credit_debit_note']->cdn_ledger_id);
			}

			//

			// Include phone number in the response
			if ($data['credit_debit_note_type']) {
			    $data['phone_number'] = ($data['credit_debit_note']->cdn_note_type == CREDIT_NOTE_TYPE_CREDIT)
			        ? $data['credit_debit_note_type']->phone
			        : $data['credit_debit_note_type']->phone;
			}	

			$data['company_setting']					= $this->company_settings_model->get_company_records();	

			$response 												= array();
	  	$response['code']  								= 1;
			$response['whatsapp_modal_body'] 	= $this->load->view('credit_debit_note/ajax/whatsapp_modal_body',$data,TRUE);

			
	  	echo json_encode($response);
		}
	}

  public function ajax_list()
  {
    $list 		= $this->credit_debit_note_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      // $table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('send_pdf').'" class="btn btn-success btn-sm text-white whatsapp-modal" data-cdn_id="'.$item->cdn_id.'">
	    //                       <i class="fab fa-whatsapp"></i> Send PDF
	    //                     </a>';

			if($this->permission_model->has_permission('download_pdf_credit_debit_note'))
			{


      //generate PDF
      	$table_body .= '
      									<a href="'.base_url('credit_debit_note/pdf/'.base64_encode($item->cdn_id)).'/landscape" class="btn bg-orange btn-sm '.(($item->cdn_delete_status == DELETED) ? " d-none" : "").'" data-tt="tooltip" title="Download Invoice">
				                  <i class="far fa-file-pdf"></i> Download
				                </a>
          						';
			}

      if($this->permission_model->has_permission('view_credit_debit_note'))
      {	
        $table_body .= ' 
                          <a href="'.base_url('credit_debit_note/view/'.base64_encode($item->cdn_id)).'" target="_blank" class="btn btn-default btn-xs" data-tt="tooltip" title="View Credit Debit Note">
                            <i class="fas fa-eye"></i> View
                          </a>      
                        ';
      }

     // Edit Button        
      if($this->permission_model->has_permission('edit_credit_debit_note'))
			{	
				$table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('credit_debit_note_edit').'" class="btn btn-info btn-xs edit_credit_debit_note_modal" data-cdn_id="'.base64_encode($item->cdn_id).'">
	                          <i class="fas fa-edit"></i> Edit
	                        </a>';
			}

			// Delete Button
			if($this->permission_model->has_permission('delete_credit_debit_note'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_credit_debit_note_modal" data-tt="tooltip" title="'.$this->lang->line('credit_debit_note_delete').'" class="btn btn-danger btn-xs delete_credit_debit_note_modal" data-cdn_id="'.$item->cdn_id.'">
	                          <i class="fas fa-trash"></i> Delete
	                        </a>';
			}

			/* End Action column buttons*/

				
        $row = array();
       
        $row[] = date('d-m-Y',strtotime($item->cdn_date));
        $row[] = $item->cdn_reference_no;
        $row[] = clean_e_val($item->cdn_note_type);
        $row[] = $item->ledger_name;
	      $row[] = $item->cdn_description;
	      $row[] = $item->cdn_taxable_amount;
	      $row[] = $item->cdn_tax_type;
	      $row[] = $item->cdn_igst;
	      $row[] = $item->cdn_igst_tax;
	      $row[] = $item->cdn_cgst;
	      $row[] = $item->cdn_cgst_tax;
	      $row[] = $item->cdn_sgst;
	      $row[] = $item->cdn_sgst_tax;
	      $row[] = $item->cdn_amount;
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->credit_debit_note_model->count_all(),
                    "recordsFiltered" => $this->credit_debit_note_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function upload_document()
	{
	    if (isset($_FILES["cdn_document_upload"])) 
	    {
       
        $data['company_setting'] 	= $this->company_settings_model->get_company_records();

        $cid = $data['company_setting']->cid; 

        $targetDir = "./assets/documents/$cid/credit_debit_note/";

        // Create the target folder if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // The third parameter creates nested directories if needed
        }

        $uploadFilename = basename($_FILES["cdn_document_upload"]["name"]);

       

        // Generate the microsecond timestamp
        $microtime = microtime(true);
        $microsecondPrefix = str_replace(".", "_", $microtime);

        // Append the microsecond timestamp as a prefix to the filename
        $newFilename = $microsecondPrefix . "_" . $uploadFilename;

        $targetFile = $targetDir . $newFilename;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["cdn_document_upload"]["tmp_name"], $targetFile)) 
        {
            $response = array();
            $response['filename'] = $newFilename;
            echo json_encode($response);
        } 
        else 
        {
            $response = array("error" => "Error uploading document.");
            echo json_encode($response);
        }
    } 
    else 
    {
        echo json_encode(array("error" => "No document file provided."));
    }
	}

  function get_sale_items()
  {
    $sale_ids = $this->input->post('sale_ids');
    $sale_ids_array = explode(',',$sale_ids);

    $data['records'] = $this->sale_model->get_sale_items_records_for_cdn($sale_ids_array);

    $response 					= array();
  	$response['credit_debit_note_items'] 	= $this->load->view('credit_debit_note/ajax/sale_credit_debit_note_items',$data,TRUE);

    echo json_encode($response);

  }

  function get_purchase_items()
  {
    $purchase_ids = $this->input->post('purchase_ids');
    $purchase_ids_array = explode(',',$purchase_ids);

    $data['records'] = $this->purchase_model->get_purchase_items_records_for_cdn($purchase_ids_array);

    $response 					= array();
  	$response['credit_debit_note_items'] 	= $this->load->view('credit_debit_note/ajax/purchase_credit_debit_note_items',$data,TRUE);

    echo json_encode($response);

  }
	
}
