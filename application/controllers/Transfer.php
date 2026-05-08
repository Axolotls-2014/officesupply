<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;

class Transfer extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_transfer') && !$this->permission_model->has_permission('list_all_transfer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['customer'] 		= $this->customer_model->get_records();
			$data['transfers'] 		= $this->transfer_model->get_transfer_records();
			
// 			$log_data = array(
//                   "user_id"     => $this->session->userdata("user_id"),
//                   "module"      => "transfer",
//                   "user_action" => 0,
//                   "description"	=> "User has viewed list of transfer."
//                 );

// 			$this->log_data_model->add_record($log_data);

			$this->load->view('transfer/list',$data);
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_transfer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('transfer_date','Transfer Date','required');
				$this->form_validation->set_rules('to_warehouse_id','To Warehouse','required');
				$this->form_validation->set_rules('from_warehouse_id','From Warehouse','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$this->load->view('transfer/add',$data);
				}
				else
				{

					$transfer_date 				= date('Y-m-d', strtotime($this->input->post('transfer_date')));
					
					
					$from_warehouse_id 		= $this->input->post('from_warehouse_id');
					$from_warehouse_name  = $this->warehouse_model->get_single_record($from_warehouse_id)->name;
					$to_warehouse_id 			= $this->input->post('to_warehouse_id');
					$to_warehouse_name    = $this->warehouse_model->get_single_record($to_warehouse_id)->name;
					$rcm 									= $this->input->post('rcm');
					$total_taxable_value	= $this->input->post('total_taxable_value');
					$tds 									= $this->input->post('tds');
					$total_discount 			= $this->input->post('total_discount');
					$total_tax 						= $this->input->post('total_tax');
					$total 								= $this->input->post('total');
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					$bank_detail 					= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

					$transfer_data = array(
										"transfer_date"				=> $transfer_date,
										"from_warehouse_id"	  => $from_warehouse_id,
										"from_warehouse_name"	  => $from_warehouse_name,
										"to_warehouse_id"	  	=> $to_warehouse_id,
										"to_warehouse_name"	  	=> $to_warehouse_name,
										"rcm"									=> $rcm,
										"total_taxable_value" => $total_taxable_value,	
										"tds" 								=> $tds,	
										"total_tax"						=> $total_tax,
										"total_discount" 			=> $total_discount,	
										"total" 							=> $total,
										"internal_note"				=> $internal_note,
										"external_note" 			=> $external_note,
										"bank_detail" 				=> $bank_detail,
										"terms_and_condition" => $terms_and_condition,
										"user_id" 						=> $this->session->userdata('user_id')
									);

					// being transaction
					$this->db->trans_begin();

					if($id = $this->transfer_model->add_transfer_record($transfer_data))
					{
						$entered_transfer 	= $this->transfer_model->get_transfer_single_record($id);

						/**************************************************************************************/

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "transfer",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"				=> json_encode((array)$entered_transfer),
											"description" => 'Stock Transferred successfully.'
										);
						$this->log_data_model->add_record($log_data);

						$transfer_items 						= $this->input->post('transfer_items');
						$transfer_items_array 			= explode("|", $this->input->post('transfer_items'));

						for ($i=0; $i < sizeof($transfer_items_array) ; $i++) { 
									
							$temp_transfer_item = (array)json_decode($transfer_items_array[$i]);
							$temp_transfer_item['transfer_id']  = $id;

							$this->transfer_model->add_transfer_item_record($temp_transfer_item);


							/****************************************************************************************************/

							$product_id 			= $temp_transfer_item['product_id'];
							$quantity 				= $temp_transfer_item['quantity'];

							$product 					= $this->product_model->get_single_record($product_id);
							$transfer 				=	$this->transfer_model->get_transfer_single_record($id);
							$transfer_item 		= $this->transfer_model->get_single_transfer_item_record($id,$product_id);
							$from_warehouse_id= $transfer->from_warehouse_id;					
							$warehouse_id 		= $transfer->to_warehouse_id;					
							$cost 						= $transfer_item->cost;
							$price 						= $transfer_item->price;


							/******************************** REMOVE PRODUCT TO WAREHOUSE **************************************/	
							
							// if product with zero price doesn't exist then check product with given price.
							$from_warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_price($from_warehouse_id,$product_id, $price);


								
							// update the product quantity
							$new_quantity = $from_warehouse_product->quantity - $quantity;
							$from_warehouse_product_data = array("quantity"=> $new_quantity);
							$this->warehouse_products_model->edit_record($from_warehouse_product_data,$from_warehouse_product->id);

							/******************************** END REMOVE PRODUCT TO WAREHOUSE **************************************/

							/******************************** ADD PRODUCT TO WAREHOUSE **************************************/	



							
							// if product with zero price doesn't exist then check product with given price.
							$warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_price($warehouse_id,$product_id, $price);

							if($warehouse_product != null)
							{
								// update the product quantity
								$new_quantity = $warehouse_product->quantity + $quantity;
								$warehouse_product_data = array("quantity"=> $new_quantity);
								$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);
							}
							else
							{
								// add the product quantity to warehouse with new price

								$warehouse_product_data = array(
																						"warehouse_id" => $transfer->to_warehouse_id,
																						"product_id"   => $product_id,
																						"cost"   			 => $cost,
																						"price"   		 => $price,
																						"quantity"   	 => $transfer_item->quantity
																					);
								$this->warehouse_products_model->add_record($warehouse_product_data);

							}
						

							/******************************** END ADD PRODUCT TO WAREHOUSE **************************************/
						}

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success',  'Transfer is added successfully.');
						redirect('transfer','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();
						
						$this->session->set_flashdata('failure',  'Transfer is failed to add.');
						redirect('transfer','refresh');
					}

				}
			}
			else
			{
				$data['warehouses'] 			= $this->warehouse_model->get_records_head();
				$data['towarehouses'] 			= $this->warehouse_model->get_records();
				$data['company_setting']	= $this->company_settings_model->get_company_records();	
				$this->load->view('transfer/add',$data);
			}
		}
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_transfer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$transfer_id = $this->input->post('id');
				$old_transfer = $this->transfer_model->get_transfer_single_record($transfer_id);

				$this->form_validation->set_rules('transfer_date','Transfer Date','required');
				$this->form_validation->set_rules('to_warehouse_id','To Warehouse','required');
				$this->form_validation->set_rules('from_warehouse_id','From Warehouse','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$this->load->view('transfer/add',$data);
				}
				else
				{
					$transfer_date 				= date('Y-m-d', strtotime($this->input->post('transfer_date')));
					$from_warehouse_id 		= $this->input->post('from_warehouse_id');
					$from_warehouse_name  = $this->warehouse_model->get_single_record($from_warehouse_id)->name;
					$to_warehouse_id 			= $this->input->post('to_warehouse_id');
					$to_warehouse_name    = $this->warehouse_model->get_single_record($to_warehouse_id)->name;

					
					$rcm 									= $this->input->post('rcm');
					$total_taxable_value 	= $this->input->post('total_taxable_value');
					$tds 									= $this->input->post('tds');
					$total_discount 			= $this->input->post('total_discount');
					$total_tax 						= $this->input->post('total_tax');
					$total 								= $this->input->post('total');
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					$bank_detail 					= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
					

					$transfer_data = array(
														"transfer_date"					=> $transfer_date,
														"from_warehouse_id"	    => $from_warehouse_id,
														"from_warehouse_name"	  => $from_warehouse_name,
														"to_warehouse_id"	  	  => $to_warehouse_id,
														"to_warehouse_name"	  	=> $to_warehouse_name,
														"rcm"										=> $rcm,
														"total_taxable_value" 	=> $total_taxable_value,	
														"tds"							 			=> $tds,	
														"total_tax"							=> $total_tax,
														"total_discount" 				=> $total_discount,	
														"total" 								=> $total,
														"internal_note"					=> $internal_note,
														"external_note" 				=> $external_note,
														"bank_detail"	 					=> $bank_detail,
														"terms_and_condition"		=> $terms_and_condition,
														"user_id" 							=> $this->session->userdata('user_id')
													);

					if($this->transfer_model->edit_transfer_record($transfer_data,$transfer_id))
					{
						$entered_transfer = $this->transfer_model->get_transfer_single_record($transfer_id);

						$log_data = array(
															"user_id" 		=> $this->session->userdata("user_id"),
															"module"			=> "transfer",
															"user_action"	=> 2,
															"b_data"			=> json_encode((array)$old_transfer),
															"data"				=> json_encode((array)$entered_transfer),
															"description" => 'Transfer is updated successfully.'
														);
						$this->log_data_model->add_record($log_data);
						$this->transfer_model->remove_transfer_item_records($transfer_id);

						$transfer_items 				= $this->input->post('transfer_items');
						$transfer_items_array 	= explode("|", $this->input->post('transfer_items'));

						for ($i=0; $i < sizeof($transfer_items_array) ; $i++) { 
									
							$temp_transfer_item = (array)json_decode($transfer_items_array[$i]);
							$temp_transfer_item['transfer_id']  = $transfer_id;

							$this->transfer_model->add_transfer_item_record($temp_transfer_item);

							

						}

						$this->session->set_flashdata('success',  'Transfer is updated successfully.');
						redirect('transfer','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure',  'Transfer is failed to update.');
						redirect('transfer','refresh');
					}
				}
			}
			else
			{
				$id 	= base64_decode($id);

				if($id != null)
				{
					$data['transfer'] 					= $this->transfer_model->get_transfer_single_record($id);

					if($data['transfer'] != null)
					{
						$data['transfer_items'] 	= $this->transfer_model->get_transfer_item_records($id);
						$data['company_setting']	= $this->company_settings_model->get_company_records();	
						$data['warehouses'] 			= $this->warehouse_model->get_records();

						$this->load->view('transfer/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('transfer','refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('transfer','refresh');
				}
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_transfer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 					= $this->input->post('id');
			$transfer 		= $this->transfer_model->get_transfer_single_record($id);

			// being transaction
			$this->db->trans_begin();


			$data = array('delete_status' => 1);

			$transfer_items = $this->transfer_model->get_transfer_item_records($id);


			if($this->transfer_model->edit_transfer_record($data,$id))
			{

				/****************************************************************************************************/

			// 	echo '<pre>';
			// print_r($data);
			// print_r($transfer_items);
			// exit;
				

				foreach ($transfer_items as $value) {

					$product_id 			= $value->product_id;
					$quantity 				= $value->quantity;

					$product 					= $this->product_model->get_single_record($product_id);
					$from_warehouse_id= $transfer->from_warehouse_id;					
					$warehouse_id 		= $transfer->to_warehouse_id;					
					$cost 						= $value->cost;
					$price 						= $value->price;


					/******************************** ADD PRODUCT TO FROM WAREHOUSE **************************************/	
					
					// if product with zero price doesn't exist then check product with given price.
					$from_warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_price($from_warehouse_id,$product_id, $price);
						
					// update the product quantity
					$new_quantity = $from_warehouse_product->quantity + $quantity;
					$from_warehouse_product_data = array("quantity"=> $new_quantity);
					$this->warehouse_products_model->edit_record($from_warehouse_product_data,$from_warehouse_product->id);					

					/******************************** END ADD PRODUCT TO FROM WAREHOUSE **************************************/

					/******************************** REMOVE  PRODUCT TO WAREHOUSE **************************************/	

					
					// if product with zero price doesn't exist then check product with given price.
					$warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_price($warehouse_id,$product_id, $price);
					
					// update the product quantity
					$new_quantity = $warehouse_product->quantity - $quantity;
					$warehouse_product_data = array("quantity"=> $new_quantity);
					$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);
					// log_message('error',$this->db->last_query());					
				
				}

				/******************************** END REMOVE PRODUCT TO WAREHOUSE **************************************/

				// echo $this->db->last_query();exit;

				// being transaction
				$this->db->trans_commit();

				$this->session->set_flashdata('success', 'Transfer is deleted successfully.');
				redirect('transfer','refresh');
			}
			else
			{
				// being transaction
				$this->db->trans_rollback();
				$this->session->set_flashdata('failure', 'Transfer is deleted successfully.');
				redirect('transfer','refresh');
			}
		}
	}

	public function view($id = null)
	{
		if(!$this->permission_model->has_permission('edit_transfer'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($id != null)
			{
				$id 										= base64_decode($id);

				$data['transfer'] 					= $this->transfer_model->get_transfer_single_record($id);

				if($data['transfer'] != null)
				{
					$data['transfer_items'] = $this->transfer_model->get_transfer_item_records($id);
					$data['company_setting']= $this->company_settings_model->get_company_records();	

					$this->load->view('transfer/view',$data);
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('transfer','refresh');
				}
			
			}
			else
			{
				$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
				redirect('transfer','refresh');
			}
		}
	}


	public function pdf($id = null)
	{
		
		if($id != null)
		{	
			$id 									= base64_decode($id);
			$data['transfer'] 				= $this->transfer_model->get_transfer_single_record($id);
			$data['transfer_items'] = $this->transfer_model->get_transfer_item_records($id);
			
			$data['discounts']			= $this->discount_model->get_records();
			$data['company_setting']	= $this->company_settings_model->get_company_records();	

			$html = $this->load->view('transfer/pdf',$data,true);
			
			$this->pdf->loadHtml($html);
			$this->pdf->render();
			$this->pdf->stream("Transfer.pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('transfer','refresh');
		}
	}


	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->transfer_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

  
										

				//View Button
			  //PDF Button
        if($this->permission_model->has_permission('pdf_transfer'))
				{	
					$table_body .= '
                            <a href="'.base_url('transfer/pdf/'.base64_encode($item->id)).'" class="btn bg-orange btn-xs" data-tt="tooltip" title="'.$this->lang->line('transfer_pdf').'">
                              <i class="far fa-file-pdf"></i> Download
                            </a>
                          ';
				}

        //view Button
        if($this->permission_model->has_permission('view_transfer'))
				{	
					$table_body .= '
                            <a href="'.base_url('transfer/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="'.$this->lang->line('transfer_view').'">
                              <i class="fas fa-eye"></i> View
                            </a>
                          ';
				}

				// Edit Button        
       	
				// if($this->permission_model->has_permission('edit_transfer'))
				// {
    //   		$table_body .= 	 '
				// 										<a href="'.base_url('transfer/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" title="Edit Transfer" data-transfer_id="'.$item->id.'">
    //                           <i class="fas fa-edit"></i> Edit
    //                         </a> 
		  //                    	';
    //     } 

				// Delete Button
				if($this->permission_model->has_permission('delete_transfer') || $this->permission_model->has_permission('delete_all_transfer'))
				{	
					$table_body .= '
		                        <a href="#"  data-toggle="modal" data-target="#delete_transfer" data-tt="tooltip" title="Delete Transfer" class="btn btn-danger btn-xs delete_transfer" data-transfer_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i> Delete
		                        </a>
													';
				}

				$from_warehouse = $this->warehouse_model->get_single_record($item->from_warehouse_id);
				$to_warehouse = $this->warehouse_model->get_single_record($item->to_warehouse_id);
				
        /* End Action column buttons*/

        $row = array();
       
	      $row[] = ($item->transfer_date != '' ) ? date('d-m-Y',strtotime($item->transfer_date)) : '';
	      $row[] = $from_warehouse->name;
	      $row[] = $to_warehouse->name;
	      $row[] = number_format($item->total,2);
        $row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->transfer_model->count_all(),
                    "recordsFiltered" => $this->transfer_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function transfer_delete_confirmation()
  {
  	$transfer_id 				= $this->input->post('transfer_id');
  	$data['transfer'] 	= $this->transfer_model->get_transfer_single_record($transfer_id);

  	$response 					= array();
  	$response['transfer_delete_modal_body'] 	= $this->load->view('transfer/ajax/transfer_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}


	/************************************** End Dynamic Datatable function ***************************************/
}
