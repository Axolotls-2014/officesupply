<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos extends MY_Controller {

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
		if(!$this->permission_model->has_permission('add_sale'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				// $this->form_validation->set_rules('invoice_date','Invoice Date','required');
				$this->form_validation->set_rules('customer_id','Customer','required');
				// $this->form_validation->set_rules('warehouse_id','Warehouse','required');
				// $this->form_validation->set_rules('reference_no','Reference no','callback_is_active_reference_no_exist');

				if($this->form_validation->run()==FALSE)
				{
					$data['customers']          = $this->customer_model->get_records();
					$data['product_categories'] = $this->product_category_model->get_records();
					$data['warehouse_products'] = $this->warehouse_products_model->get_records();
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					// $data['promotions']				= $this->promotion_model->get_records();	
					$this->load->view('pos/list',$data);
				}
				else
				{

					$warehouse = $this->warehouse_model->get_record_by_is_default();

					$invoice_date 				= date('Y-m-d');
					$reference_no 				= $this->sale_model->get_lastest_sequence_number();
					$warehouse_id 				= $warehouse->id;
					$customer_id 					= $this->input->post('customer_id');
					// $pack_slip_id_array   = $this->input->post('pack_slip_id');
					// $pack_slip_id 				= implode(",", $pack_slip_id_array);
					
					$customer 						= $this->customer_model->get_single_record($customer_id);
					$customer_gstin 			= $customer->gstin;

					$rcm 									= $this->input->post('rcm');
					$total_taxable_value	= $this->input->post('total_taxable_value');
					$tds 									= $this->input->post('tds');
					$total_discount 			= $this->input->post('total_discount');
					$total_tax 						= $this->input->post('total_tax');
					$total 								= $this->input->post('total');
					$internal_note 				= '';
					$external_note 				= '';
					$bank_detail 					= '';
					$terms_and_condition 	= '';


					$due_date 						= "";
					$lori_no 							= "";
					$lori_date 						= "";
					$carrier 							= "";
					$vehicle_no 					= "";
					$ewaybill_no 					= "";
					$additional_case 			= 0;


					$ewaybill_dispatch_from 						= "";
					$ewaybill_mode_of_transportation 						= "";
					$ewaybill_subtype 						= "";
					$ewaybill_doctype 						= "";
					$ewaybill_transporter_name 						= "";
					$ewaybill_transporter_gstin 						= "";
					$ewaybill_distance_of_transportation 						= "";
					$ewaybill_transporter_doc_no 						= "";
					$ewaybill_vehicle_no 						= "";
					$ewaybill_vehicle_type 						= "";
					$ewaybill_transporter_doc_date 						= "";

					$available_credit 		= $this->input->post('available_credit');
				
          $doctor_name 								= $this->input->post('doctor_name');

          // $round_off 	= (round($total) - $total);
					// $total  		= round($total);
					


					$sale_data = array(
										"invoice_date"				=> $invoice_date,
										"reference_no"				=> $reference_no,
										"warehouse_id"	  		=> $warehouse_id,
										"customer_id"					=> $customer_id,
										"customer_gstin"			=> $customer_gstin,
										"rcm"									=> $rcm,
										"total_taxable_value" => $total_taxable_value,	
										"tds" 								=> $tds,	
										"total_tax"						=> $total_tax,
										"total_discount" 			=> $total_discount,	
										"total" 							=> $total,
                    // "round_off" 					=> $round_off,
										"internal_note"				=> $internal_note,
										"external_note" 			=> $external_note,
										"bank_detail" 				=> $bank_detail,
										"terms_and_condition" => $terms_and_condition,
                    "doctor_name"			=> $doctor_name,
										// "due_date" 						=> $due_date,
										// "cheque_no" 					=> $cheque_no,
										// "booked_at" 					=> $booked_at,
										// "lori_no" 						=> $lori_no,
										// "lori_date" 					=> $lori_date,
										// "carrier" 						=> $carrier,
										// "vehicle_no" 					=> $vehicle_no,
										// "ewaybill_no" 				=> $ewaybill_no,
										"additional_case" 		=> $additional_case,
										"user_id" 						=> $this->session->userdata('user_id')
									);

					// echo '<pre>';
					// print_r($this->input->post('pack_slip_id'));
					// echo $this->input->post('pack_slip_id');
					// print_r($sale_data);
					// exit;

					// being transaction
					$this->db->trans_begin();

					if($id = $this->sale_model->add_sale_record($sale_data))
					{
						// for ($i=0; $i < sizeof($pack_slip_id_array); $i++) { 
						// 	$this->pack_slip_model->edit_pack_slip_record(array('sale_id'=>$id),$pack_slip_id_array[$i]);	
						// }
						

						$entered_sale 	= $this->sale_model->get_sale_single_record($id);
						$customer 			= $this->customer_model->get_single_record($entered_sale->customer_id);

						// update the ledgers
						
							/****************************** Start Sale Ledger ***********************************/

							$sale_ledger 								= $this->ledger_model->get_single_record(SALE_LEDGER);
							$updated_sale_ledger 				= array('closing_balance' => ($sale_ledger->closing_balance + $total));
							$this->ledger_model->edit_record($updated_sale_ledger,$sale_ledger->id);
	
							/****************************** End Sale Ledger ***********************************/
	
							/****************************** Start Customer Ledger ***********************************/
	
							$customer_ledger 						= $this->ledger_model->get_single_record($customer->ledger_id);
							$updated_customer_ledger    = array('closing_balance' => ($customer_ledger->closing_balance + $total));
							$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);
	
							/****************************** End Customer Ledger ***********************************/
	
							/****************************** Start TDS Ledger ***********************************/
	
							if($tds > 0)
							{
								$tds_ledger 						= $this->ledger_model->get_single_record(TDS_LEDGER);
								$updated_tds_ledger    	= array('closing_balance' => ($tds_ledger->closing_balance + $tds));
								$this->ledger_model->edit_record($updated_tds_ledger,$tds_ledger->id);
	
								$customer_ledger 						= $this->ledger_model->get_single_record($customer->ledger_id);
								$updated_customer_ledger 		= array('closing_balance' => ($customer_ledger->closing_balance - $tds));
								$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);
							}
	
							
							/****************************** End TDS Ledger ***********************************/
	
							/****************************** Add Transaction entry *********************************/
	
							$transaction_header = array(
																					"entry_id"				=>  $id,
																					"module"					=>  SALE_MODULE,
																					"type"						=>	SALE_TRANSACTION_TYPE,
																					"amount"					=>	$total,
																					"voucher_date"		=>	$invoice_date,
																					"from_account"		=>	SALE_LEDGER,
																					"to_account"			=>	$customer->ledger_id,
																					"reference_no"		=>	$reference_no
																				);
							if($transaction_id = $this->transaction_model->add_record($transaction_header))
							{
								$from_transaction_detail = array(
																									"transaction_id" 	=> $transaction_id,
																									"voucher_type"		=> 'C',
																									"ledger_id"				=> SALE_LEDGER,
																									"dr_amount"				=> $total
																								);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($from_transaction_detail);
	
								$to_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'D',
																								"ledger_id"				=> $customer->ledger_id,
																								"cr_amount"				=> $total
																							);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
							}
	
							/**************************************************************************************/
	
	
							/****************************** Add TDS Transaction entry *********************************/
	
							if($tds > 0)
							{
								$transaction_header = array(
																						"entry_id"				=>  $id,
																						"module"					=>  SALE_MODULE,
																						"type"						=>	TDS_TRANSACTION_TYPE,
																						"amount"					=>	$tds,
																						"voucher_date"		=>	$invoice_date,
																						"from_account"		=>	$customer->ledger_id,
																						"to_account"			=>	TDS_LEDGER,
																						"reference_no"		=>	$reference_no
																					);
								if($transaction_id = $this->transaction_model->add_record($transaction_header))
								{
									$from_transaction_detail = array(
																										"transaction_id" 	=> $transaction_id,
																										"voucher_type"		=> 'C',
																										"ledger_id"				=> $customer->ledger_id,
																										"dr_amount"				=> $tds
																									);
									// transaction detail record
									$this->transaction_model->add_transaction_detail_record($from_transaction_detail);
	
									$to_transaction_detail = array(
																									"transaction_id" 	=> $transaction_id,
																									"voucher_type"		=> 'D',
																									"ledger_id"				=> TDS_LEDGER,
																									"cr_amount"				=> $tds
																								);
									// transaction detail record
									$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
								}	
							}
							
							/**************************************************************************************/
						
						/**************************************************************************************/

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "sale",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"				=> json_encode((array)$entered_sale),
											"description" => 'Sales (Reference No. -'  .$reference_no .') is added successfully.'
										);
						$this->log_data_model->add_record($log_data);

						$sale_items 						= $this->input->post('sale_items');
						$sale_items_array 			= explode("|", $this->input->post('sale_items'));

						
						for ($i=0; $i < sizeof($sale_items_array) ; $i++) { 
									
							$temp_sale_item = (array)json_decode($sale_items_array[$i]);
							$temp_sale_item['sale_id']  = $id;

							$this->sale_model->add_sale_item_record($temp_sale_item);

							/* reduce quantity from warehouse */
							
							$warehouse_product = $this->warehouse_products_model->get_single_record($temp_sale_item['warehouse_product_id']);
							$product 					 = $this->product_model->get_single_record($warehouse_product->product_id);

						
							$quantity = $warehouse_product->quantity - ($temp_sale_item['quantity']);
							
							$warehouse_product_data = array(
																						
																							"quantity"	=> $quantity
																						
																						);
							$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);	
						
						}

						/* mark as delivered start */
						// if(isset($_POST['mark_as_delivered']))
						// {
						// 	$delivery_data = array(
						// 												"sale_id"					=> $id,
						// 												"delivery_date" 	=> date('Y-m-d'),
						// 												"delivered_by"		=> $this->session->userdata('user_id'),
						// 												"user_id" 				=> $this->session->userdata('user_id')
						// 											);

						// 	if($sale_delivery_id = $this->sale_delivery_model->add_delivery_record($delivery_data))
						// 	{
								
						// 		for ($i=0; $i < sizeof($sale_items_array) ; $i++) 
						// 		{
						// 				$temp_sale_item = (array)json_decode($sale_items_array[$i]);
						// 				/******************************** ADD PRODUCT DELIVERY ******************************************/	
						// 				$temp_delivery_item = array();
						// 				$temp_delivery_item['warehouse_product_id'] = $temp_sale_item['warehouse_product_id'];
						// 				$temp_delivery_item['product_id'] = $temp_sale_item['product_id'];
						// 				$temp_delivery_item['quantity'] = $temp_sale_item['quantity'];
						// 				$temp_delivery_item['sale_delivery_id']  = $sale_delivery_id;

						// 				$this->sale_delivery_model->add_delivery_item_record($temp_delivery_item);

						// 				/******************************** ADD PRODUCT TO WAREHOUSE **************************************/	
										
						// 				// if product with zero cost doesn't exist then check product with given cost.
						// 				$warehouse_product = $this->warehouse_products_model->get_single_record($temp_delivery_item['warehouse_product_id']);

										
						// 				// update the product quantity
						// 				$new_quantity = $warehouse_product->quantity - $temp_delivery_item['quantity'];
						// 				$warehouse_product_data = array(
						// 																					"quantity"	=> $new_quantity
						// 																				);
						// 				$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);

						// 				/******************************** END ADD PRODUCT TO WAREHOUSE **************************************/

						// 		}
						// 	}

						// }
						/* mark as delivered end */

						
            if($available_credit > 0)
						{
							$credit_amount = 0;

							if($available_credit >= ($total-$tds))
								$this->transaction_model->add_transaction($id,SALE_MODULE,CREDIT_TRANSACTION_TYPE,$total-$tds,$invoice_date,CREDIT_MODE,'','',$customer_ledger->id,'','');	
							else
								$this->transaction_model->add_transaction($id,SALE_MODULE,CREDIT_TRANSACTION_TYPE,$available_credit,$invoice_date,CREDIT_MODE,'','',$customer_ledger->id,'','');	

						}

						// commit transaction
						$this->db->trans_commit();

						if (!$this->input->is_ajax_request()) 
						{
							$this->session->set_flashdata('success',  'Sales (Reference No. -'  .$reference_no .') is added successfully.');
							redirect('pos/index','refresh');	
						}
						else
						{
							$response = array();
							$response['code'] = 1;
							$response['message'] = 'Sales (Reference No. -'  .$reference_no .') is added successfully.';
							$response['sale_id'] = base64_encode($id);
							$response['d_sale_id'] = $id;

							echo json_encode($response);
						}

						
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();
						
						
						if (!$this->input->is_ajax_request()) 
						{
							$this->session->set_flashdata('failure',  'Sales (Reference No. -'  .$reference_no .') is failed to add.');
							redirect('pos/index','refresh');

						}
						else
						{
							$response = array();
							$response['code'] = 1;
							$response['message'] = 'Sales (Reference No. -'  .$reference_no .') is failed to add.';

							echo json_encode($response);
						}
					}
				}
			}
			else
			{
				$data['customers']          = $this->customer_model->get_records();
				$data['product_categories'] = $this->product_category_model->get_records();
				$data['warehouse_products'] = $this->warehouse_products_model->get_records();
				$data['warehouses'] 			= $this->warehouse_model->get_records();
				$data['company_setting']	= $this->company_settings_model->get_company_records();	
				// $data['promotions']				= $this->promotion_model->get_records();	
				$this->load->view('pos/list',$data);
			}
		}
	}
	
	public function print($sale_id)
	{	
		$id = base64_decode($sale_id);

		$data['sale'] 						= $this->sale_model->get_sale_single_record($id);
		$data['sale_items'] 			= $this->sale_model->get_sale_item_records($id);
		$data['customer_detail']	= $this->customer_model->get_single_record($data['sale']->customer_id);
		$data['discounts']				= $this->discount_model->get_records();
		$data['company_setting']	= $this->company_settings_model->get_company_records();	
		
	
		$this->load->view('pos/print',$data);
	}
}
