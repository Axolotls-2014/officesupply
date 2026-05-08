<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Scrap_receive extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_scrap_receive'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['customers'] 				= $this->customer_model->get_records();
			$log_data = array(
                  "user_id"     => $this->session->userdata("user_id"),
                  "module"      => "sale",
                  "user_action" => 0,
                  "description"	=> "User has viewed list of scrap receive."
                );

			$this->log_data_model->add_record($log_data);

			$this->load->view('scrap_receive/list',$data);
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_scrap_receive'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('scrap_receive_date','Scrap receive Date','required');		
				$this->form_validation->set_rules('customer_id','customer','required');
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['customers'] 				= $this->customer_model->get_records();
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					
					$this->load->view('scrap_receive/add',$data);
				}
				else
				{
					$scrap_receive_date 			= date('Y-m-d', strtotime($this->input->post('scrap_receive_date')));
					$reference_no 				= $this->scrap_receive_model->get_lastest_sequence_number();
					$warehouse_id 				= $this->input->post('warehouse_id');
					$customer_id 					= $this->input->post('customer_id');
					
					$customer 						= $this->customer_model->get_single_record($customer_id);
					$customer_gstin 			= $customer->gstin;

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

					$scrap_receive_data = array(
										"scrap_receive_date"			=> $scrap_receive_date,
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
										"internal_note"				=> $internal_note,
										"external_note" 			=> $external_note,
										"bank_detail" 				=> $bank_detail,
										"terms_and_condition" => $terms_and_condition,
										"user_id" 						=> $this->session->userdata('user_id')
									);

					// being transaction
					$this->db->trans_begin();

					if($id = $this->scrap_receive_model->add_scrap_receive_record($scrap_receive_data))
					{
						$entered_scrap_receive 	= $this->scrap_receive_model->get_scrap_receive_single_record($id);
						$customer 			= $this->customer_model->get_single_record($entered_scrap_receive->customer_id);
						
						/**************************************************************************************/

						$log_data = array(
														"user_id" 		=> $this->session->userdata("user_id"),
														"module"			=> "scrap_receive",
														"entry_id"		=> $id,
														"user_action"	=> 1,
														"data"				=> json_encode((array)$entered_scrap_receive),
														"description" => 'Scrap receive (Reference No. -'  .$reference_no .') is added successfully.'
													);
						$this->log_data_model->add_record($log_data);

						$scrap_receive_items 						= $this->input->post('scrap_receive_items');
						$scrap_receive_items_array 			= explode("|", $this->input->post('scrap_receive_items'));

						
						for ($i=0; $i < sizeof($scrap_receive_items_array) ; $i++) { 
									
							$temp_scrap_receive_item = (array)json_decode($scrap_receive_items_array[$i]);
							$temp_scrap_receive_item['scrap_receive_id']  = $id;
							//$temp_scrap_receive_item['expiry_date']  = ($temp_scrap_receive_item['expiry_date'] != '' && $temp_scrap_receive_item['expiry_date'] != '') ? date('Y-m-d',strtotime($temp_scrap_receive_item['expiry_date'])) : '';

              if($temp_scrap_receive_item['expiry_date'] == '' || $temp_scrap_receive_item['expiry_date'] == '0000-00-00')
                unset($temp_scrap_receive_item['expiry_date']);
              else
                $temp_scrap_receive_item['expiry_date'] = date('Y-m-d',strtotime($temp_scrap_receive_item['expiry_date']));

							$this->scrap_receive_model->add_scrap_receive_item_record($temp_scrap_receive_item);
						}

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success',  'Scrap receive (Reference No. -'.$reference_no .') is added successfully.');
						redirect('scrap_receive','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();
						
						$this->session->set_flashdata('failure',  'Scrap receive (Reference No. -'  .$reference_no .') is failed to add.');
						redirect('scrap_receive','refresh');
					}

				}
			}
			else
			{
				$data['customers'] 				= $this->customer_model->get_records();
				$data['warehouses'] 			= $this->warehouse_model->get_records();
				$data['company_setting']	= $this->company_settings_model->get_company_records();	
				

				$scrap_receive_date 			= date('Y-m-d', strtotime($this->input->post('scrap_receive_date')));
				$reference_no 				= $this->scrap_receive_model->get_lastest_sequence_number();

				$scrap_receive_data = array(
										"scrap_receive_date"			=> date('Y-m-d'),
										"reference_no"				=> $reference_no,
										"delete_status"				=> 0,
										"user_id" 						=> $this->session->userdata('user_id')
									);

				$id = $this->scrap_receive_model->add_scrap_receive_record($scrap_receive_data);

				redirect('scrap_receive/edit/'.base64_encode($id),'refresh');
				
				// $this->load->view('scrap_receive/add',$data);
			}
		}
	}

	public function edit($id = null,$customer_id = null)
	{
		if(!$this->permission_model->has_permission('edit_scrap_receive'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$scrap_receive_id = $this->input->post('id');
				$old_scrap_receive = $this->scrap_receive_model->get_scrap_receive_single_record($scrap_receive_id);

				$this->form_validation->set_rules('scrap_receive_date','Scrap receive Date','required');		
				$this->form_validation->set_rules('customer_id','customer','required');
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');


				if($this->form_validation->run()==FALSE)
				{
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['customers'] 				= $this->customer_model->get_records();
					
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$this->load->view('scrap_receive/add',$data);
				}
				else
				{
					//$scrap_receive_date 			= date('Y-m-d', strtotime($this->input->post('scrap_receive_date')));
					$warehouse_id 				= $this->input->post('warehouse_id');
					$customer_id 					= $this->input->post('customer_id');

					$customer 						= $this->customer_model->get_single_record($customer_id);
					$customer_gstin 			= $customer->gstin;

					$reference_no 				= $this->input->post('reference_no');
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
         

					$scrap_receive_data = array(
														
														"warehouse_id"	  			=> $warehouse_id,
														"customer_id"						=> $customer_id,
														"customer_gstin"				=> $customer_gstin,
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

          $scrap_receive_date 				= $this->input->post('scrap_receive_date');
          if($scrap_receive_date != '')
            $scrap_receive_data['scrap_receive_date'] = date('Y-m-d',strtotime($scrap_receive_date));

            // echo '<pre>';
            // print_r($scrap_receive_data);
            // exit;

					if($this->scrap_receive_model->edit_scrap_receive_record($scrap_receive_data,$scrap_receive_id))
					{
						$entered_scrap_receive = $this->scrap_receive_model->get_scrap_receive_single_record($scrap_receive_id);
						$customer 		= $this->customer_model->get_single_record($entered_scrap_receive->customer_id);

						

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "scrap_receive",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_scrap_receive),
											"data"			=> json_encode((array)$entered_scrap_receive),
											"description" 	=> 'Scrap receive (Reference No. -'  .$reference_no .') is updated successfully.'
										);
						$this->log_data_model->add_record($log_data);
						$this->scrap_receive_model->remove_scrap_receive_item_records($scrap_receive_id);

						$scrap_receive_items 		= $this->input->post('scrap_receive_items');
						$scrap_receive_items_array 	= explode("|", $this->input->post('scrap_receive_items'));

						for ($i=0; $i < sizeof($scrap_receive_items_array) ; $i++) { 
									
							$temp_scrap_receive_item = (array)json_decode($scrap_receive_items_array[$i]);
							$temp_scrap_receive_item['scrap_receive_id']  = $scrap_receive_id;
						
							$this->scrap_receive_model->add_scrap_receive_item_record($temp_scrap_receive_item);
							
							/* update ptd value of specific warehouse instance.*/
							$warehouse_product = $this->warehouse_products_model->get_single_record($temp_scrap_receive_item['warehouse_product_id']);

              $scrap_product = $this->scrap_products_model->get_single_record_by_warehouse_product_id($temp_scrap_receive_item['warehouse_product_id']);

            
							// $new_quantity = $scrap_product->quantity + $temp_scrap_receive_item['quantity'];
							// $scrap_product_data = array(
							// 																	"quantity"	=> $new_quantity
							// 																);
							// $this->scrap_products_model->edit_record($scrap_product_data,$scrap_product->id);

              if($scrap_product && $scrap_product->warehouse_product_id == $temp_scrap_receive_item['warehouse_product_id'])
              {
                
                $new_quantity = $scrap_product->quantity + $temp_scrap_receive_item['quantity'];
                $scrap_product_data = array(
                                                  "quantity"	=> $new_quantity
                                                );
                $this->scrap_products_model->edit_record($scrap_product_data,$scrap_product->id);
              }
              else
              {
               
                $scrap_product_data = array(
                                              "warehouse_id"	=> $warehouse_product->warehouse_id,
                                              "product_id"	  => $warehouse_product->product_id,
                                             
                                              "batch_no"	=> $warehouse_product->batch_no,
                                            
                                              "cost"	=> $warehouse_product->cost,
                                              "price"	=> $warehouse_product->price,
                                              "selling_price"	=> $warehouse_product->selling_price,
                                             
                                              "quantity"	=> $temp_scrap_receive_item['quantity'],                                            
                                              
                                              "warehouse_product_id"	=> $warehouse_product->id                                              
                                            );
                $this->scrap_products_model->add_record($scrap_product_data);
              }

						}

						if($this->input->is_ajax_request())
						{
							$response = array();
							$response['code'] = 1;
							$response['message'] = 'Scrap receive is automatically saved.';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success',  'Scrap receive (Reference No. -'  .$reference_no .') is updated successfully.');
							redirect('scrap_receive','refresh');	
						}
					}
					else
					{
						if($this->input->is_ajax_request())
						{
							$response = array();
							$response['code'] = 1;
							$response['message'] = 'Scrap receive is failed to save automatically.';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure',  'Scrap receive (Reference No. -'  .$reference_no .') is failed to update.');
							redirect('scrap_receive','refresh');
						}
					}
				}
			}
			else
			{
				$id 	= base64_decode($id);

				if($id != null)
				{
					$data['scrap_receive'] 						= $this->scrap_receive_model->get_scrap_receive_single_record($id);

					if($data['scrap_receive'] != null)
					{
						$data['scrap_receive_items'] 			= $this->scrap_receive_model->get_scrap_receive_item_records($id);
						$data['customers'] 				= $this->customer_model->get_records();
						$data['customer_detail']	= $this->customer_model->get_single_record($data['scrap_receive']->customer_id);
						$data['discounts']				= $this->discount_model->get_records();
						
						$data['company_setting']	= $this->company_settings_model->get_company_records();	
						$data['warehouses'] 			= $this->warehouse_model->get_records();

            $this->load->view('scrap_receive/edit',$data);	
						

						// $this->load->view('scrap_receive/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('scrap_receive','refresh');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('scrap_receive','refresh');
				}
			}
		}
	}
	

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_scrap_receive'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 					= $this->input->post('id');
			$scrap_receive 		= $this->scrap_receive_model->get_scrap_receive_single_record($id);
			
			$data = array('delete_status' => 1);
			if($this->scrap_receive_model->edit_scrap_receive_record($data,$id))
			{
				$this->session->set_flashdata('success', 'Scrap receive (Reference No. -'.$scrap_receive->reference_no.') is deleted successfully.');
				redirect('scrap_receive','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Scrap receive (Reference No. -'.$scrap_receive->reference_no.') is failed to delete.');
				redirect('scrap_receive','refresh');
			}	
			
		}
	}

	public function view($id = null)
	{
		if(!$this->permission_model->has_permission('edit_scrap_receive'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{

			if (!$this->input->is_ajax_request()) 
			{
				if($id != null)
				{
					$id 										= base64_decode($id);

					$data['scrap_receive'] 					= $this->scrap_receive_model->get_scrap_receive_single_record($id);

					if($data['scrap_receive'] != null)
					{
						$data['scrap_receive_items'] 		= $this->scrap_receive_model->get_scrap_receive_item_records($id);
						$data['customers'] 			= $this->customer_model->get_records();
						$data['customer_detail']= $this->customer_model->get_single_record($data['scrap_receive']->customer_id);
						$data['discounts']			= $this->discount_model->get_records();
						$data['company_setting']= $this->company_settings_model->get_company_records();	

						$this->load->view('scrap_receive/view',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('scrap_receive','refresh');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('scrap_receive','refresh');
				}
			}
			else
			{
				$response = array();

				$sale_view_data['scrap_receive'] 						= $this->scrap_receive_model->get_scrap_receive_single_record($id);
				$sale_view_data['customer_detail'] 	= $this->customer_model->get_single_record($sale_view_data['scrap_receive']->customer_id);	
				// $sale_view_data['paid_amount'] 			= $this->transaction_model->get_total_transaction_amount($id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount($id,SALE_MODULE,TDS_TRANSACTION_TYPE);
				
				$response['sale_view']	 						= $this->load->view('scrap_receive/ajax/view',$sale_view_data,TRUE);
				// $response['due_amount']							= $sale_view_data['scrap_receive']->total - $sale_view_data['paid_amount'];


		

				echo json_encode($response);
			}
			
		}
	}

	public function pdf($id = null)
	{
			
		if($id != null)
		{	
			$id 									= base64_decode($id);
			$data['scrap_receive'] 				= $this->scrap_receive_model->get_scrap_receive_single_record($id);
			$data['scrap_receive_items'] 		= $this->scrap_receive_model->get_scrap_receive_item_records($id);
			$data['customer_detail']	= $this->customer_model->get_single_record($data['scrap_receive']->customer_id);
			$data['discounts']			= $this->discount_model->get_records();
			$data['company_setting']	= $this->company_settings_model->get_company_records();	

			$html = $this->load->view('scrap_receive/pdf_l',$data,true);
			// $this->load->view('scrap_receive/pdf',$data);
			
			$this->pdf->loadHtml($html);
      $this->pdf->set_paper('A4' , 'landscape'); 
			$this->pdf->render();
			$this->pdf->stream("ScrapReceive-".$data['scrap_receive']->reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('scrap_receive','refresh');
		}
	}

	public function get_records_by_customer_id($customer_id)
	{
		$response = array();
		$response['scrap_receive_items'] = $this->scrap_receive_model->get_scrap_receive_records_by_customer_id($customer_id);
		$response['discounts']		= $this->discount_model->get_valid_records();	

		echo json_encode($response);
	}

	public function get_scrap_receive_items()
	{
		$scrap_receive_ids 			= $this->input->post('scrap_receive_ids');
    $customer_id        = $this->input->post('customer_id');

    date_default_timezone_set('Asia/Kolkata');
		

    $lock_by       = $this->session->userdata('user_id');
    $lock_datetime = date('Y-m-d H:i:s');// Set the new lock_datetime

		

    $data = array(
                  "lock_by"       => $lock_by,
                  "lock_datetime" => $lock_datetime
    );
		
    foreach ($scrap_receive_ids as $scrap_receive_id) {
     $this->scrap_receive_model->edit_scrap_receive_record($data,$scrap_receive_id);
    }

    
    // $data['customer_detail'] = $this->customer_model->get_single_record($customer_id);
    // $data['company_setting'] = $this->company_settings_model->get_company_records();
    $data['discounts']       = $this->discount_model->get_valid_records();	
    $data['scrap_receive_items'] = $this->scrap_receive_model->get_scrap_receive_item_records($scrap_receive_ids,true);

    
    

		// $response['product_table_body'] = $this->load->view('sale/ajax/scrap_receive_items',$data,TRUE);

		echo json_encode($data);
	}





	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->scrap_receive_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];
    // $sales_payment = $_POST['sales_payment'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

				//View Button
			  if($this->permission_model->has_permission('view_scrap_receive') )
				{	
					$table_body .= ' 
                            <a href="'.base_url('scrap_receive/view/'.base64_encode($item->id)).'" target="_blank" class="btn btn-default btn-xs" data-tt="tooltip" title="View Scrap receive">
                              <i class="fas fa-eye"></i> View
                            </a>      
                          ';

          $table_body .= '
          									<a href="'.base_url('scrap_receive/pdf/'.base64_encode($item->id)).'" target="_blank" class="btn bg-orange btn-sm" data-tt="tooltip" title="Download Scrap receive">
						                  <i class="far fa-file-pdf"></i> Download
						                </a>
          							';
				}


      
				// Edit Button        
       	if(($this->permission_model->has_permission('edit_scrap_receive')))
				{	
					          	
          	$table_body .= ' 
          										<a href="'.base_url('scrap_receive/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs"  data-tt="tooltip" title="Edit Scrap receive">
                                <i class="fas fa-edit"></i> Edit
                              </a>
                            ';
          
					
				}

				// Delete Button
				if(($this->permission_model->has_permission('delete_scrap_receive')))
				{	
					$table_body .= '
		                        <a href="#"  data-toggle="modal" data-target="#delete_scrap_receive" data-tt="tooltip" title="Delete Sale" class="btn btn-danger btn-xs delete_scrap_receive" data-scrap_receive_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i> Delete
		                        </a>
													';
				}
        
        //Reference No
        $reference_no_html = '<a href="'.base_url('scrap_receive/view/'.base64_encode($item->id)).'" target="_blank" data-tt="tooltip" title="'.$this->lang->line('scrap_receive_view').'">'.$item->reference_no.'</a>';


        //customer Name
        $customer_name_html = '<a href="'.base_url('customer/view/'.base64_encode($item->customer_id)).'" target="_blank" data-tt="tooltip" title="'.$this->lang->line('Scrap receive_view_customer_detail').'">'.$item->customer_name.'</a>';
                      
				/* End Action column buttons*/


				$select_scrap_receive_html = '<input type="checkbox" class="single_scrap_receive"><input type="hidden" name="scrap_receive_id" id="scrap_receive_id"  value="'.$item->id.'">';

        $row = array();
       	
       	// $row[] = $select_scrap_receive_html;
	      $row[] = $reference_no_html;
	      // $row[] = $item->purchase_invoice_no;
	      $row[] = $item->total;
	      $row[] = $item->total_product;
	      $row[] = $item->total_quantity;
	     
	      $row[] = date('d-m-Y', strtotime($item->scrap_receive_date));
	      $row[] = $customer_name_html;
	      //$row[] = ($item->sale_id != '') ? '<span class="badge badge-success">'.strtoupper(clean_e_val(PROCESSED)).'</span>' : '<span class="badge badge-warning">'.strtoupper(clean_e_val(NOT_PROCESSED)).'</span>';
        $row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;

    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->scrap_receive_model->count_all(),
                    "recordsFiltered" => $this->scrap_receive_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function scrap_receive_delete_confirmation()
  {
  	$scrap_receive_id 				= $this->input->post('scrap_receive_id');
  	$data['scrap_receive'] 	= $this->scrap_receive_model->get_scrap_receive_single_record($scrap_receive_id);

  	$response 					= array();
  	$response['scrap_receive_delete_modal_body'] 	= $this->load->view('scrap_receive/ajax/scrap_receive_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	

	/************************************** End Dynamic Datatable function ***************************************/

	

  public function export($scrap_receive_id)
  {
  		$scrap_receive_id = base64_decode($scrap_receive_id);
  		$scrap_receive = $this->scrap_receive_model->get_scrap_receive_single_record($scrap_receive_id);
  		$customer = $this->customer_model->get_single_record($scrap_receive->customer_id);
								  
								  
			$this->db->select('psl.product_name as "Product Name",
							   psl.pack as Pack,psl.packing_type as "Packing Type",
							   psl.batch_no as "Batch no",
							   psl.expiry_date as "Expiry date",
							   psl.case_size as "Case size",
							   psl.free_quantity as "Free quantity",
							   psl.total_quantity as "Total quantity",
							   psl.no_of_box as "No of box",
							   psl.no_of_case as "No of case",
							   psl.uom_uom as "Uom Uom",
							   psl.uom_name as "Uom name",
							   psl.description as "Description",
							   psl.quantity as "Quantity",
							   psl.cost as "Cost",
							   psl.price as "Price",
							   psl.taxable_value as "Taxable value",
							   tax.tax_name as  "Tax name",
							   psl.igst as  "IGST",
							   psl.igst_tax as  "IGST Tax",
							   psl.cgst as  "CGST",
							   psl.cgst_tax as  "CGST Tax",
							   psl.sgst as  "SGST",
							   psl.sgst_tax as  "SGST Tax",
							   psl.sub_total as "Sub total",
							   '); 
			//$this->db->select(''); 
			$this->db->from('scrap_receive_items psl');
			$this->db->join('scrap_receive ps', 'ps.id = psl.scrap_receive_id');  
			$this->db->join('tax', 'tax.id = psl.tax_id');  
			//$this->db->join('warehouse_products wp', 'wp.id = psl.warehouse_product_id');  
			//$this->db->join('warehouse w', 'w.id = wp.warehouse_id');  
			
			$this->db->where('psl.scrap_receive_id',$scrap_receive_id);
			$query = $this->db->get();		  

      $this->load->dbutil();
      $data = $this->dbutil->csv_from_result($query);
      
      $this->load->helper('download');
      force_download($scrap_receive->reference_no."_".strtoupper(str_replace(' ', '_', $customer->customer_name)).".CSV", $data);
  }

  
}
