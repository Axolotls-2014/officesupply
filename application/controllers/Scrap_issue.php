<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Scrap_issue extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_scrap_issue'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['suppliers'] 				= $this->supplier_model->get_records();
// 			$log_data = array(
//                   "user_id"     => $this->session->userdata("user_id"),
//                   "module"      => "sale",
//                   "user_action" => 0,
//                   "description"	=> "User has viewed list of scrap issue."
//                 );

// 			$this->log_data_model->add_record($log_data);

			$this->load->view('scrap_issue/list',$data);
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_scrap_issue'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('scrap_issue_date','Scrap issue Date','required');		
				$this->form_validation->set_rules('supplier_id','Supplier','required');
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['suppliers'] 				= $this->supplier_model->get_records();
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					
					$this->load->view('scrap_issue/add',$data);
				}
				else
				{
					$scrap_issue_date 			= date('Y-m-d', strtotime($this->input->post('scrap_issue_date')));
					$reference_no 				= $this->scrap_issue_model->get_lastest_sequence_number();
					$warehouse_id 				= $this->input->post('warehouse_id');
					$supplier_id 					= $this->input->post('supplier_id');
					
					$supplier 						= $this->supplier_model->get_single_record($supplier_id);
					$supplier_gstin 			= $supplier->gstin;

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

					$scrap_issue_data = array(
										"scrap_issue_date"			=> $scrap_issue_date,
										"reference_no"				=> $reference_no,
										"warehouse_id"	  		=> $warehouse_id,
										"supplier_id"					=> $supplier_id,
										"supplier_gstin"			=> $supplier_gstin,
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

					if($id = $this->scrap_issue_model->add_scrap_issue_record($scrap_issue_data))
					{
						$entered_scrap_issue 	= $this->scrap_issue_model->get_scrap_issue_single_record($id);
						$supplier 			= $this->supplier_model->get_single_record($entered_scrap_issue->supplier_id);
						
						/**************************************************************************************/

						$log_data = array(
														"user_id" 		=> $this->session->userdata("user_id"),
														"module"			=> "scrap_issue",
														"entry_id"		=> $id,
														"user_action"	=> 1,
														"data"				=> json_encode((array)$entered_scrap_issue),
														"description" => 'Scrap issue (Reference No. -'  .$reference_no .') is added successfully.'
													);
						$this->log_data_model->add_record($log_data);

						$scrap_issue_items 						= $this->input->post('scrap_issue_items');
						$scrap_issue_items_array 			= explode("|", $this->input->post('scrap_issue_items'));

						
						for ($i=0; $i < sizeof($scrap_issue_items_array) ; $i++) { 
									
							$temp_scrap_issue_item = (array)json_decode($scrap_issue_items_array[$i]);
							$temp_scrap_issue_item['scrap_issue_id']  = $id;
							//
              if($temp_scrap_issue_item['expiry_date'] == '' || $temp_scrap_issue_item['expiry_date'] == '0000-00-00')
                unset($temp_scrap_issue_item['expiry_date']);
              else
                $temp_scrap_issue_item['expiry_date'] = date('Y-m-d',strtotime($temp_scrap_issue_item['expiry_date']));

							$this->scrap_issue_model->add_scrap_issue_item_record($temp_scrap_issue_item);
						}

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success',  'Scrap issue (Reference No. -'.$reference_no .') is added successfully.');
						redirect('scrap_issue','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();
						
						$this->session->set_flashdata('failure',  'Scrap issue (Reference No. -'  .$reference_no .') is failed to add.');
						redirect('scrap_issue','refresh');
					}

				}
			}
			else
			{
				$data['suppliers'] 				= $this->supplier_model->get_records();
				$data['warehouses'] 			= $this->warehouse_model->get_records();
				$data['company_setting']	= $this->company_settings_model->get_company_records();	
				

				$scrap_issue_date 			= date('Y-m-d', strtotime($this->input->post('scrap_issue_date')));
				$reference_no 				= $this->scrap_issue_model->get_lastest_sequence_number();

				$scrap_issue_data = array(
										"scrap_issue_date"			=> date('Y-m-d'),
										"reference_no"				=> $reference_no,
										"delete_status"				=> 0,
										"user_id" 						=> $this->session->userdata('user_id')
									);

				$id = $this->scrap_issue_model->add_scrap_issue_record($scrap_issue_data);

				redirect('scrap_issue/edit/'.base64_encode($id),'refresh');
				
				// $this->load->view('scrap_issue/add',$data);
			}
		}
	}

	public function edit($id = null,$sale_request_id = null,$supplier_id = null)
	{
		if(!$this->permission_model->has_permission('edit_scrap_issue'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$scrap_issue_id = $this->input->post('id');
				$old_scrap_issue = $this->scrap_issue_model->get_scrap_issue_single_record($scrap_issue_id);

				$this->form_validation->set_rules('scrap_issue_date','Scrap issue Date','required');		
				$this->form_validation->set_rules('supplier_id','supplier','required');
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');


				if($this->form_validation->run()==FALSE)
				{
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['suppliers'] 				= $this->supplier_model->get_records();
					
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$this->load->view('scrap_issue/add',$data);
				}
				else
				{
					//$scrap_issue_date 			= date('Y-m-d', strtotime($this->input->post('scrap_issue_date')));
					$warehouse_id 				= $this->input->post('warehouse_id');
					$supplier_id 					= $this->input->post('supplier_id');

					$supplier 						= $this->supplier_model->get_single_record($supplier_id);
					$supplier_gstin 			= $supplier->gstin;

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
          $sale_request_id      = $this->input->post('sale_request_id');
					

					$scrap_issue_data = array(
														"sale_request_id"				=> $sale_request_id,
														"warehouse_id"	  			=> $warehouse_id,
														"supplier_id"						=> $supplier_id,
														"supplier_gstin"				=> $supplier_gstin,
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

          $scrap_issue_date 				= $this->input->post('scrap_issue_date');
          if($scrap_issue_date != '')
            $scrap_issue_data['scrap_issue_date'] = date('Y-m-d',strtotime($scrap_issue_date));

            // echo '<pre>';
            // print_r($scrap_issue_data);
            // exit;

					if($this->scrap_issue_model->edit_scrap_issue_record($scrap_issue_data,$scrap_issue_id))
					{
						$entered_scrap_issue = $this->scrap_issue_model->get_scrap_issue_single_record($scrap_issue_id);
						$supplier 		= $this->supplier_model->get_single_record($entered_scrap_issue->supplier_id);

						

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "scrap_issue",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_scrap_issue),
											"data"			=> json_encode((array)$entered_scrap_issue),
											"description" 	=> 'Scrap issue (Reference No. -'  .$reference_no .') is updated successfully.'
										);
						$this->log_data_model->add_record($log_data);
						$this->scrap_issue_model->remove_scrap_issue_item_records($scrap_issue_id);

						$scrap_issue_items 		= $this->input->post('scrap_issue_items');
						$scrap_issue_items_array 	= explode("|", $this->input->post('scrap_issue_items'));

						for ($i=0; $i < sizeof($scrap_issue_items_array) ; $i++) { 
									
							$temp_scrap_issue_item = (array)json_decode($scrap_issue_items_array[$i]);
							$temp_scrap_issue_item['scrap_issue_id']  = $scrap_issue_id;
							
							$this->scrap_issue_model->add_scrap_issue_item_record($temp_scrap_issue_item);
							
							/* update ptd value of specific warehouse instance.*/
							$warehouse_product = $this->warehouse_products_model->get_single_record($temp_scrap_issue_item['warehouse_product_id']);

              $scrap_product = $this->scrap_products_model->get_single_record_by_warehouse_product_id($temp_scrap_issue_item['warehouse_product_id']);

							$new_quantity = $scrap_product->quantity - $temp_scrap_issue_item['quantity'];
							$scrap_product_data = array(
																								"quantity"	=> $new_quantity
																							);
							$this->scrap_products_model->edit_record($scrap_product_data,$scrap_product->id);
						}

						if($this->input->is_ajax_request())
						{
							$response = array();
							$response['code'] = 1;
							$response['message'] = 'Scrap issue is automatically saved.';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success',  'Scrap issue (Reference No. -'  .$reference_no .') is updated successfully.');
							redirect('scrap_issue','refresh');	
						}
					}
					else
					{
						if($this->input->is_ajax_request())
						{
							$response = array();
							$response['code'] = 1;
							$response['message'] = 'Scrap issue is failed to save automatically.';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure',  'Scrap issue (Reference No. -'  .$reference_no .') is failed to update.');
							redirect('scrap_issue','refresh');
						}
					}
				}
			}
			else
			{
				$id 	= base64_decode($id);

				if($id != null)
				{
					$data['scrap_issue'] 						= $this->scrap_issue_model->get_scrap_issue_single_record($id);

					if($data['scrap_issue'] != null)
					{
						$data['scrap_issue_items'] 			= $this->scrap_issue_model->get_scrap_issue_item_records($id);
						$data['suppliers'] 				= $this->supplier_model->get_records();
						$data['supplier_detail']	= $this->supplier_model->get_single_record($data['scrap_issue']->supplier_id);
						$data['discounts']				= $this->discount_model->get_records();
						
						$data['company_setting']	= $this->company_settings_model->get_company_records();	
						$data['warehouses'] 			= $this->warehouse_model->get_records();

            if($sale_request_id == null)
						{
							$this->load->view('scrap_issue/edit',$data);	
						}
						else
						{
              //$data['sale_request_id']    = base64_decode($sale_request_id);
              $data['sale_request']       = $this->sale_request_model->get_single_record(base64_decode($sale_request_id));
							$data['sale_request_items'] = $this->sale_request_item_model->get_records(base64_decode($sale_request_id));
              $this->load->view('scrap_issue/sale_request_items',$data);		
						}

						// $this->load->view('scrap_issue/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('scrap_issue','refresh');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('scrap_issue','refresh');
				}
			}
		}
	}
	

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_scrap_issue'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 					= $this->input->post('id');
			$scrap_issue 		= $this->scrap_issue_model->get_scrap_issue_single_record($id);

			$scrap_issue_items   = $this->scrap_issue_model->get_scrap_issue_item_records($id);

			
			
			$data = array('delete_status' => 1);
			if($this->scrap_issue_model->edit_scrap_issue_record($data,$id))
			{

				foreach ($scrap_issue_items as $item) 
					{
						$scrap_product = $this->scrap_products_model->get_single_record_by_warehouse_product_id($item->warehouse_product_id);
				
						
						// Only reduce the quantity of product

						$new_quantity = $scrap_product->quantity + $item->quantity;
					
						$scrap_product_data = array(
																							"quantity"=> $new_quantity,
																					
																						);
						$this->scrap_products_model->edit_record($scrap_product_data,$scrap_product->id); 
					}



				$this->session->set_flashdata('success', 'Scrap issue (Reference No. -'.$scrap_issue->reference_no.') is deleted successfully.');
				redirect('scrap_issue','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Scrap issue (Reference No. -'.$scrap_issue->reference_no.') is failed to delete.');
				redirect('scrap_issue','refresh');
			}	
			
		}
	}

	public function view($id = null)
	{
		if(!$this->permission_model->has_permission('edit_scrap_issue'))
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

					$data['scrap_issue'] 					= $this->scrap_issue_model->get_scrap_issue_single_record($id);

					if($data['scrap_issue'] != null)
					{
						$data['scrap_issue_items'] 		= $this->scrap_issue_model->get_scrap_issue_item_records($id);
						$data['suppliers'] 			= $this->supplier_model->get_records();
						$data['supplier_detail']= $this->supplier_model->get_single_record($data['scrap_issue']->supplier_id);
						$data['discounts']			= $this->discount_model->get_records();
						$data['company_setting']= $this->company_settings_model->get_company_records();	

						$this->load->view('scrap_issue/view',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('scrap_issue','refresh');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('scrap_issue','refresh');
				}
			}
			else
			{
				$response = array();

				$sale_view_data['scrap_issue'] 						= $this->scrap_issue_model->get_scrap_issue_single_record($id);
				$sale_view_data['supplier_detail'] 	= $this->supplier_model->get_single_record($sale_view_data['scrap_issue']->supplier_id);	
				// $sale_view_data['paid_amount'] 			= $this->transaction_model->get_total_transaction_amount($id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount($id,SALE_MODULE,TDS_TRANSACTION_TYPE);
				
				$response['sale_view']	 						= $this->load->view('scrap_issue/ajax/view',$sale_view_data,TRUE);
				// $response['due_amount']							= $sale_view_data['scrap_issue']->total - $sale_view_data['paid_amount'];


		

				echo json_encode($response);
			}
			
		}
	}

	public function pdf($id = null)
	{
			
		if($id != null)
		{	
			$id 									= base64_decode($id);
			$data['scrap_issue'] 				= $this->scrap_issue_model->get_scrap_issue_single_record($id);
			$data['scrap_issue_items'] 		= $this->scrap_issue_model->get_scrap_issue_item_records($id);
			$data['supplier_detail']	= $this->supplier_model->get_single_record($data['scrap_issue']->supplier_id);
			$data['discounts']			= $this->discount_model->get_records();
			$data['company_setting']	= $this->company_settings_model->get_company_records();	

			$html = $this->load->view('scrap_issue/pdf_l',$data,true);
			// $this->load->view('scrap_issue/pdf',$data);
			
			$this->pdf->loadHtml($html);
      $this->pdf->set_paper('A4' , 'landscape'); 
			$this->pdf->render();
			$this->pdf->stream("Scrap issue-".$data['scrap_issue']->reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('scrap_issue','refresh');
		}
	}

	public function get_records_by_supplier_id($supplier_id)
	{
		$response = array();
		$response['scrap_issue_items'] = $this->scrap_issue_model->get_scrap_issue_records_by_supplier_id($supplier_id);
		$response['discounts']		= $this->discount_model->get_valid_records();	

		echo json_encode($response);
	}

	public function get_scrap_issue_items()
	{
		$scrap_issue_ids 			= $this->input->post('scrap_issue_ids');
    $supplier_id        = $this->input->post('supplier_id');

    date_default_timezone_set('Asia/Kolkata');
		

    $lock_by       = $this->session->userdata('user_id');
    $lock_datetime = date('Y-m-d H:i:s');// Set the new lock_datetime

		

    $data = array(
                  "lock_by"       => $lock_by,
                  "lock_datetime" => $lock_datetime
    );
		
    foreach ($scrap_issue_ids as $scrap_issue_id) {
     $this->scrap_issue_model->edit_scrap_issue_record($data,$scrap_issue_id);
    }

    
    // $data['supplier_detail'] = $this->supplier_model->get_single_record($supplier_id);
    // $data['company_setting'] = $this->company_settings_model->get_company_records();
    $data['discounts']       = $this->discount_model->get_valid_records();	
    $data['scrap_issue_items'] = $this->scrap_issue_model->get_scrap_issue_item_records($scrap_issue_ids,true);

    
    

		// $response['product_table_body'] = $this->load->view('sale/ajax/scrap_issue_items',$data,TRUE);

		echo json_encode($data);
	}





	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->scrap_issue_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];
    // $sales_payment = $_POST['sales_payment'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

				//View Button
			  if($this->permission_model->has_permission('view_scrap_issue') )
				{	
					$table_body .= ' 
                            <a href="'.base_url('scrap_issue/view/'.base64_encode($item->id)).'" target="_blank" class="btn btn-default btn-xs" data-tt="tooltip" title="View Scrap issue">
                              <i class="fas fa-eye"></i> View
                            </a>      
                          ';

          $table_body .= '
          									<a href="'.base_url('scrap_issue/pdf/'.base64_encode($item->id)).'" target="_blank" class="btn bg-orange btn-sm" data-tt="tooltip" title="Download Scrap issue">
						                  <i class="far fa-file-pdf"></i> Download
						                </a>
          							';
				}


      
				// Edit Button        
       	if(($this->permission_model->has_permission('edit_scrap_issue')))
				{	
					          	
          	$table_body .= ' 
          										<a href="'.base_url('scrap_issue/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs"  data-tt="tooltip" title="Edit Scrap issue">
                                <i class="fas fa-edit"></i> Edit
                              </a>
                            ';
          
					
				}

				// Delete Button
				if(($this->permission_model->has_permission('delete_scrap_issue')))
				{	
					$table_body .= '
		                        <a href="#"  data-toggle="modal" data-target="#delete_scrap_issue" data-tt="tooltip" title="Delete Sale" class="btn btn-danger btn-xs delete_scrap_issue" data-scrap_issue_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i> Delete
		                        </a>
													';
				}
        
        //Reference No
        $reference_no_html = '<a href="'.base_url('scrap_issue/view/'.base64_encode($item->id)).'" target="_blank" data-tt="tooltip" title="'.$this->lang->line('scrap_issue_view').'">'.$item->reference_no.'</a>';


        //supplier Name
        $supplier_name_html = '<a href="'.base_url('supplier/view/'.base64_encode($item->supplier_id)).'" target="_blank" data-tt="tooltip" title="'.$this->lang->line('Scrap issue_view_supplier_detail').'">'.$item->company_name.'</a>';
                      
				/* End Action column buttons*/


				$select_scrap_issue_html = '<input type="checkbox" class="single_scrap_issue"><input type="hidden" name="scrap_issue_id" id="scrap_issue_id"  value="'.$item->id.'">';

        $row = array();
       	
       	// $row[] = $select_scrap_issue_html;
	      $row[] = $reference_no_html;
	      // $row[] = $item->purchase_invoice_no;
	      $row[] = $item->total;
	      $row[] = $item->total_product;
	      $row[] = $item->total_quantity;
	     
	      $row[] = date('d-m-Y', strtotime($item->scrap_issue_date));
	      $row[] = $supplier_name_html;
	      //$row[] = ($item->sale_id != '') ? '<span class="badge badge-success">'.strtoupper(clean_e_val(PROCESSED)).'</span>' : '<span class="badge badge-warning">'.strtoupper(clean_e_val(NOT_PROCESSED)).'</span>';
        $row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;

    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->scrap_issue_model->count_all(),
                    "recordsFiltered" => $this->scrap_issue_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function scrap_issue_delete_confirmation()
  {
  	$scrap_issue_id 				= $this->input->post('scrap_issue_id');
  	$data['scrap_issue'] 	= $this->scrap_issue_model->get_scrap_issue_single_record($scrap_issue_id);

  	$response 					= array();
  	$response['scrap_issue_delete_modal_body'] 	= $this->load->view('scrap_issue/ajax/scrap_issue_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	public function sale_edit_confirmation()
  {
  	$sale_id 				= $this->input->post('sale_id');
  	$data['scrap_issue'] 		= $this->scrap_issue_model->get_scrap_issue_single_record($sale_id);

  	$response 					= array();
  	$response['sale_edit_modal_body'] 	= $this->load->view('scrap_issue/ajax/sale_edit_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/

		/*check invoice no*/

	function get_record_detail_by_reference_no()
	{
		$reference_no = $this->input->post('reference_no');
		$module 			= $this->input->post('module');
		$product_id 	= $this->input->post('product_id');

		$sale 				= $this->scrap_issue_model->get_sale_record_details_by_reference_no($reference_no);

		$scrap_issue_items 	= array();


		$scrap_issue_items 	= $this->warehouse_products_model->get_single_record_for_sales($product_id);

		/*if($module == SALE_MODULE || $module == SALE_RETURN_MODULE)
		{
			$product 	= $this->warehouse_products_model->get_single_record_for_sales($product_id);
			$data['discount']	= $this->discount_model->get_records();	
		}
		else
		{
			$product = $this->product_model->get_single_record($product_id);
			$data['discount']= $this->discount_model->get_records();	
		}*/

		

		$response = array();

		if($sale != null)
		{
			$response['code'] 				= 0;
			$response['message'] 			= $reference_no.' is already exists.';
			$response['scrap_issue']					= $sale;
			$response['scrap_issue_items']		= $scrap_issue_items;
			//$response['scrap_issue_items'] =	$scrap_issue_item;

			echo json_encode($response);
		}
		else
		{
			$response['code'] = 1;
			$response['message'] = 'Sale does not exist with this'.$reference_no;

			echo json_encode($response);	
		}
	}

  public function export($scrap_issue_id)
  {
  		$scrap_issue_id = base64_decode($scrap_issue_id);
  		$scrap_issue = $this->scrap_issue_model->get_scrap_issue_single_record($scrap_issue_id);
  		$supplier = $this->supplier_model->get_single_record($scrap_issue->supplier_id);
								  
								  
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
			$this->db->from('scrap_issue_items psl');
			$this->db->join('scrap_issue ps', 'ps.id = psl.scrap_issue_id');  
			$this->db->join('tax', 'tax.id = psl.tax_id');  
			//$this->db->join('warehouse_products wp', 'wp.id = psl.warehouse_product_id');  
			//$this->db->join('warehouse w', 'w.id = wp.warehouse_id');  
			
			$this->db->where('psl.scrap_issue_id',$scrap_issue_id);
			$query = $this->db->get();		  

      $this->load->dbutil();
      $data = $this->dbutil->csv_from_result($query);
      
      $this->load->helper('download');
      force_download($scrap_issue->reference_no."_".strtoupper(str_replace(' ', '_', $supplier->supplier_name)).".CSV", $data);
  }

  public function export_warehouse_products()
  {
  		$this->db->select('p.pid as "PID",
							   p.name as Name,
							   wp.batch_no as "BatchNo",
							   "" as "QTY",
                 wp.quantity as "AvailableQuantity"
							   '); 

			$this->db->from('warehouse_products wp');
			$this->db->join('product p', 'p.id = wp.product_id');  
		
			$this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');
      $this->db->order_by('p.pid' , 'asc');
			$query = $this->db->get();		  

      $this->load->dbutil();
      $data = $this->dbutil->csv_from_result($query);
      
      $this->load->helper('download');
      force_download("AllProductWithPIDAndBatch.CSV", $data);
  }

  
}
