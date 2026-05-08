<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Scrap_entry extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_scrap_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
		
// 			$log_data = array(
//                   "user_id"     => $this->session->userdata("user_id"),
//                   "module"      => "scrap_entry",
//                   "user_action" => 0,
//                   "description"	=> "User has viewed list of Scrap entry."
//                 );

			$this->log_data_model->add_record($log_data);

			$this->load->view('scrap_entry/list');
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_scrap_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('scrap_entry_date','Scrap entry Date','required');		
				

				if($this->form_validation->run()==FALSE)
				{
					
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					
					$this->load->view('scrap_entry/add',$data);
				}
				else
				{
					$scrap_entry_date 			= date('Y-m-d', strtotime($this->input->post('scrap_entry_date')));
					$reference_no 				= $this->scrap_entry_model->get_lastest_sequence_number();
					
					$rcm 									= $this->input->post('rcm');
					$total_taxable_value	= $this->input->post('total_taxable_value');
					$tds 									= $this->input->post('tds');
					$total_discount 			= $this->input->post('total_discount');
				
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					$bank_detail 					= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
					$warehouse_id         = $this->input->post('warehouse_id');

					$scrap_entry_data = array(
										"scrap_entry_date"			=> $scrap_entry_date,
										"reference_no"				=> $reference_no,
										"warehouse_id"				=> $warehouse_id,
										"rcm"									=> $rcm,
										"total_taxable_value" => $total_taxable_value,	
										"tds" 								=> $tds,	
										
										"total_discount" 			=> $total_discount,	
										
										"internal_note"				=> $internal_note,
										"external_note" 			=> $external_note,
										"bank_detail" 				=> $bank_detail,
										"terms_and_condition" => $terms_and_condition,
									
										"user_id" 						=> $this->session->userdata('user_id')
									);

					// being transaction
					$this->db->trans_begin();

					if($id = $this->scrap_entry_model->add_scrap_entry_record($scrap_entry_data))
					{
						$entered_scrap_entry 	= $this->scrap_entry_model->get_scrap_entry_single_record($id);
					
						/**************************************************************************************/

						$log_data = array(
														"user_id" 		=> $this->session->userdata("user_id"),
														"module"			=> "scrap_entry",
														"entry_id"		=> $id,
														"user_action"	=> 1,
														"data"				=> json_encode((array)$entered_scrap_entry),
														"description" => 'Scrap entry (Reference No. -'  .$reference_no .') is added successfully.'
													);
						$this->log_data_model->add_record($log_data);

						$scrap_entry_items 						= $this->input->post('scrap_entry_items');
						$scrap_entry_items_array 			= explode("|", $this->input->post('scrap_entry_items'));

						
						for ($i=0; $i < sizeof($scrap_entry_items_array) ; $i++) { 
									
							$temp_scrap_entry_item = (array)json_decode($scrap_entry_items_array[$i]);
							$temp_scrap_entry_item['scrap_entry_id']  = $id;
							//$temp_scrap_entry_item['expiry_date']  = ($temp_scrap_entry_item['expiry_date'] != '' && $temp_scrap_entry_item['expiry_date'] != '') ? date('Y-m-d',strtotime($temp_scrap_entry_item['expiry_date'])) : '';

              if($temp_scrap_entry_item['expiry_date'] == '' || $temp_scrap_entry_item['expiry_date'] == '0000-00-00')
                unset($temp_scrap_entry_item['expiry_date']);
              else
                $temp_scrap_entry_item['expiry_date'] = date('Y-m-d',strtotime($temp_scrap_entry_item['expiry_date']));

							$this->scrap_entry_model->add_scrap_entry_item_record($temp_scrap_entry_item);
						}

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success',  'Scrap entry (Reference No. -'.$reference_no .') is added successfully.');
						redirect('scrap_entry','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();
						
						$this->session->set_flashdata('failure',  'Scrap entry (Reference No. -'  .$reference_no .') is failed to add.');
						redirect('scrap_entry','refresh');
					}

				}
			}
			else
			{
				
				$data['company_setting']	= $this->company_settings_model->get_company_records();	
				$data['warehouses'] 			= $this->warehouse_model->get_records();
				

				$scrap_entry_date 			= date('Y-m-d', strtotime($this->input->post('scrap_entry_date')));
					$reference_no 				= $this->scrap_entry_model->get_lastest_sequence_number();

				$scrap_entry_data = array(
										"scrap_entry_date"			=> date('Y-m-d'),
										"reference_no"				=> $reference_no,
										"delete_status"				=> 0,
									   
										"user_id" 						=> $this->session->userdata('user_id')
									);

				$id = $this->scrap_entry_model->add_scrap_entry_record($scrap_entry_data);

				redirect('scrap_entry/edit/'.base64_encode($id),'refresh');
				
				// $this->load->view('scrap_entry/add',$data);
			}
		}
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_scrap_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$scrap_entry_id = $this->input->post('id');
				$old_scrap_entry = $this->scrap_entry_model->get_scrap_entry_single_record($scrap_entry_id);

				$this->form_validation->set_rules('scrap_entry_date','Scrap entry Date','required');		
			
				if($this->form_validation->run()==FALSE)
				{
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['suppliers'] 				= $this->supplier_model->get_records();
					
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$this->load->view('scrap_entry/add',$data);
				}
				else
				{
					//$scrap_entry_date 			= date('Y-m-d', strtotime($this->input->post('scrap_entry_date')));
				
          $reference_no 				= $this->input->post('reference_no');
					$rcm 									= $this->input->post('rcm');
					$total_taxable_value 	= $this->input->post('total_taxable_value');
					$tds 									= $this->input->post('tds');
					$total_discount 			= $this->input->post('total_discount');
				
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					$bank_detail 					= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
          $sale_request_id      = $this->input->post('sale_request_id');
					$warehouse_id         = $this->input->post('warehouse_id');
					

					$scrap_entry_data = array(
													
														"rcm"										=> $rcm,
														"total_taxable_value" 	=> $total_taxable_value,
														"warehouse_id" 	=> $warehouse_id,		
														"tds"							 			=> $tds,	
													  "total_discount" 				=> $total_discount,	
													  "internal_note"					=> $internal_note,
														"external_note" 				=> $external_note,
														"bank_detail"	 					=> $bank_detail,
														"terms_and_condition"		=> $terms_and_condition,
														"delete_status" 				=> 0,
														"status"								=> SCRAP_ENTRY_STATUS_COMPLETED,
														"user_id" 							=> $this->session->userdata('user_id')
													);

          $scrap_entry_date 				= $this->input->post('scrap_entry_date');
          if($scrap_entry_date != '')
            $scrap_entry_data['scrap_entry_date'] = date('Y-m-d',strtotime($scrap_entry_date));

            // echo '<pre>';
            // print_r($scrap_entry_data);
            // exit;

					if($this->scrap_entry_model->edit_scrap_entry_record($scrap_entry_data,$scrap_entry_id))
					{
						$entered_scrap_entry = $this->scrap_entry_model->get_scrap_entry_single_record($scrap_entry_id);
						
						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "scrap_entry",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_scrap_entry),
											"data"			=> json_encode((array)$entered_scrap_entry),
											"description" 	=> 'Scrap entry (Reference No. -'  .$reference_no .') is updated successfully.'
										);
						$this->log_data_model->add_record($log_data);
						$this->scrap_entry_model->remove_scrap_entry_item_records($scrap_entry_id);

						$scrap_entry_items 		= $this->input->post('scrap_entry_items');
						$scrap_entry_items_array 	= explode("|", $this->input->post('scrap_entry_items'));

						for ($i=0; $i < sizeof($scrap_entry_items_array) ; $i++) { 
									
							$temp_scrap_entry_item = (array)json_decode($scrap_entry_items_array[$i]);
							$temp_scrap_entry_item['scrap_entry_id']  = $scrap_entry_id;
						
							$this->scrap_entry_model->add_scrap_entry_item_record($temp_scrap_entry_item);
							
							/* update ptd value of specific warehouse instance.*/
							$warehouse_product = $this->warehouse_products_model->get_single_record($temp_scrap_entry_item['warehouse_product_id']);

              /* reduce quantity from warehouse */
							
              $new_quantity = $warehouse_product->quantity - $temp_scrap_entry_item['quantity'];
              $warehouse_product_data = array(
                                                "quantity"	=> $new_quantity
                                              );
              $this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);
              

              $scrap_product = $this->scrap_products_model->get_single_record_by_warehouse_product_id($temp_scrap_entry_item['warehouse_product_id']);

              if($scrap_product && $scrap_product->warehouse_product_id == $temp_scrap_entry_item['warehouse_product_id'])
              {
                
                $new_quantity = $scrap_product->quantity + $temp_scrap_entry_item['quantity'];
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
                                             	"batch_no"			=> $warehouse_product->batch_no,
                                             	"cost"					=> $warehouse_product->cost,
                                              "price"					=> $warehouse_product->price,
                                              "selling_price"	=> $warehouse_product->selling_price,
                                             	"quantity"			=> $temp_scrap_entry_item['quantity'],                                            
                                            	"warehouse_product_id"	=> $warehouse_product->id                                              
                                            );

																			
                $this->scrap_products_model->add_record($scrap_product_data);
              }
						}

						if($this->input->is_ajax_request())
						{
							$response = array();
							$response['code'] = 1;
							$response['message'] = 'Scrap entry is automatically saved.';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success',  'Scrap entry (Reference No. -'  .$reference_no .') is updated successfully.');
							redirect('scrap_entry','refresh');	
						}
					}
					else
					{
						if($this->input->is_ajax_request())
						{
							$response = array();
							$response['code'] = 1;
							$response['message'] = 'Scrap entry is failed to save automatically.';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure',  'Scrap entry (Reference No. -'  .$reference_no .') is failed to update.');
							redirect('scrap_entry','refresh');
						}
					}
				}
			}
			else
			{
				$id 	= base64_decode($id);

				if($id != null)
				{
					$data['scrap_entry'] 						= $this->scrap_entry_model->get_scrap_entry_single_record($id);
					$data['warehouses'] 			= $this->warehouse_model->get_records();

					if($data['scrap_entry'] != null)
					{
						$data['scrap_entry_items'] 			= $this->scrap_entry_model->get_scrap_entry_item_records($id);
			
						$data['discounts']				= $this->discount_model->get_records();
						
						$data['company_setting']	= $this->company_settings_model->get_company_records();	
						
            $this->load->view('scrap_entry/edit',$data);	

						// $this->load->view('scrap_entry/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('scrap_entry','refresh');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('scrap_entry','refresh');
				}
			}
		}
	}
	

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_scrap_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 					= $this->input->post('id');
			$scrap_entry 		= $this->scrap_entry_model->get_scrap_entry_single_record($id);
			
			$data = array('delete_status' => 1);
			if($this->scrap_entry_model->edit_scrap_entry_record($data,$id))
			{
				$this->session->set_flashdata('success', 'Scrap entry (Reference No. -'.$scrap_entry->reference_no.') is deleted successfully.');
				redirect('scrap_entry','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Scrap entry (Reference No. -'.$scrap_entry->reference_no.') is failed to delete.');
				redirect('scrap_entry','refresh');
			}	
			
		}
	}

	public function view($id = null)
	{
		if(!$this->permission_model->has_permission('edit_scrap_entry'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{

			if($id != null)
      {
        $id 										= base64_decode($id);

        $data['scrap_entry'] 					= $this->scrap_entry_model->get_scrap_entry_single_record($id);

        if($data['scrap_entry'] != null)
        {
          $data['scrap_entry_items'] 		= $this->scrap_entry_model->get_scrap_entry_item_records($id);
          
          $data['discounts']			= $this->discount_model->get_records();
          $data['company_setting']= $this->company_settings_model->get_company_records();	

          $this->load->view('scrap_entry/view',$data);
        }
        else
        {
          $this->session->set_flashdata('failure',  'You have tried to access broken URL.');
          redirect('scrap_entry','refresh');
        }
      
      }
      else
      {
        $this->session->set_flashdata('failure',  'You have tried to access broken URL.');
        redirect('scrap_entry','refresh');
      }
			
		}
	}

	public function pdf($id = null)
	{
			
		if($id != null)
		{	
			$id 									= base64_decode($id);
			$data['scrap_entry'] 				= $this->scrap_entry_model->get_scrap_entry_single_record($id);
			$data['scrap_entry_items'] 		= $this->scrap_entry_model->get_scrap_entry_item_records($id);
		
			$data['discounts']			= $this->discount_model->get_records();
			$data['company_setting']	= $this->company_settings_model->get_company_records();	

			$html = $this->load->view('scrap_entry/pdf_l',$data,true);
			// $this->load->view('scrap_entry/pdf',$data);
			
			$this->pdf->loadHtml($html);
      $this->pdf->set_paper('A4' , 'landscape'); 
			$this->pdf->render();
			$this->pdf->stream("ScrapEntry-".$data['scrap_entry']->reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('scrap_entry','refresh');
		}
	}

  public function import_scrap_entry_items()
  {
      if (!$this->permission_model->has_permission('import_scrap_entry_items')) 
      {
          $this->load->view('errors/html/error_restricted');
      } 
      else 
      {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
          // Check if 'csvfile' file input exists
          if (isset($_FILES['csvfile'])) {
              // Your CSV parsing code here
              if (is_uploaded_file($_FILES['csvfile']['tmp_name'])) {
                  $csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);
      
                  $warehouseProductData = array();
                  $customer_id = $this->input->post('customer_id'); // Get the customer_id from the form data

                  $discount = $this->discount_model->get_valid_records();
      
                  $data['hold_quantity']  = array(); // Initialize hold_quantity array
                  $data['new_ptd']        = array(); // Initialize new_ptd array
                  $data['quantity']       = array(); // Initialize quantity array
                  $data['last_ptd']       = array(); // Initialize last_ptd array

                  $i = 0;
                  $no_of_row_in_csv = sizeof($csvData);
                  $not_matched_products = array();
      
                  if (!empty($csvData)) 
                  {   
                    //$no_of_row_in_csv = sizeof($csvData);
                    foreach ($csvData as $row) 
                    {
                      $matchedProduct = $this->warehouse_products_model->get_records_by_pid_batch_no($row['PID'], $row['BatchNo']);
  
                      // Print matched products (for debugging purposes)
                      if ($matchedProduct != null) 
                      {
                        $i++;
                        $warehouse_product = $this->warehouse_products_model->get_single_record_for_sales($matchedProduct->id);
                        $warehouseProductData[] = $warehouse_product;

                        
                        $data['new_ptd'][$matchedProduct->id] = $warehouse_product->wp_ptd;

                        

                        // $data['discount'][$matchedProduct->id] = $this->discount_model->get_valid_records();

                        $hold_quantity = ($this->scrap_entry_model->get_hold_quantity_by_product_id($matchedProduct->id) != null) ? $this->scrap_entry_model->get_hold_quantity_by_product_id($matchedProduct->id)->hold_quantity : '0';

                        // Add hold_quantity to the array for this matched product
                        $data['hold_quantity'][$matchedProduct->id] = $hold_quantity;
                        $data['quantity'][$matchedProduct->id] = $row['QTY'];
                      }
                      else
                      {
                        $not_matched_products[] = $row['Name'].'_'.$row['PID'].'_'.$row['BatchNo'];                        
                      }
                    }
                    
                    if($i > 0)
                    {
                      $responseData = array(
                          'code' => 1,
                          'warehouseProductData' => $warehouseProductData,
                          'hold_quantity' => $data['hold_quantity'],
                          'newPtd' => $data['new_ptd'],
                          
                          'quantity' => $data['quantity'],
                          'discount' => $discount,
                          'message' => ''
                      );

                      if($i != $no_of_row_in_csv)
                        $responseData['message'] = implode("<br/>",$not_matched_products).' <br/> products are not exist in system';
                    
                    }
                    else
                    {
                      $responseData = array(
                          'code' => 0,
                          'message' => 'No record(s) are found in system. Please check BatchNo and PID.'
                      );
                    }
                    

                    
                    // Send matched product IDs along with hold quantities and newPtd as a response
                    echo json_encode($responseData);
                  } 
                  else 
                  {

                    echo json_encode(array('code' => 0, 'message' => 'No record(s) are found in system. Please check BatchNo and PID.'));
                  }
              } 
              else 
              {
                echo json_encode(array('code' => 0, 'message' => 'Failed to upload CSV file.'));
              }
          } 
          else 
          {
            echo json_encode(array('code' => 0, 'message' => 'CSV file not present.'));
          }
        }
        else 
        {
          if ($this->input->is_ajax_request()) 
          {
            $data['product_categories'] = $this->product_category_model->get_records();
            $data['uoms'] = $this->uom_model->get_records();

            $response = array();
            $response['code'] = 1;
            $response['import_scrap_entry_items_modal_body'] = $this->load->view('scrap_entry/ajax/import_scrap_entry_items_modal_body', $data, TRUE);

            echo json_encode($response);
          } 
          else 
          {
            $this->load->view('errors/html/error_restricted');
          }
        }
      }
  }

	
	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->scrap_entry_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];
    // $sales_payment = $_POST['sales_payment'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

				//View Button
			  if($this->permission_model->has_permission('view_scrap_entry') )
				{	
					$table_body .= ' 
                            <a href="'.base_url('scrap_entry/view/'.base64_encode($item->id)).'" target="_blank" class="btn btn-default btn-xs" data-tt="tooltip" title="View Scrap entry">
                              <i class="fas fa-eye"></i> View
                            </a>      
                          ';

          $table_body .= '
          									<a href="'.base_url('scrap_entry/pdf/'.base64_encode($item->id)).'" target="_blank" class="btn bg-orange btn-sm" data-tt="tooltip" title="Download Scrap entry">
						                  <i class="far fa-file-pdf"></i> Download
						                </a>
          							';
				}


      
				// Edit Button        
       	if(($this->permission_model->has_permission('edit_scrap_entry')))
				{	
					          	
          	$table_body .= ' 
          										<a href="'.base_url('scrap_entry/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs"  data-tt="tooltip" title="Edit Scrap entry">
                                <i class="fas fa-edit"></i> Edit
                              </a>
                            ';
          
					
				}

				// Delete Button
				if(($this->permission_model->has_permission('delete_scrap_entry')))
				{	
					$table_body .= '
		                        <a href="#"  data-toggle="modal" data-target="#delete_scrap_entry" data-tt="tooltip" title="Delete Sale" class="btn btn-danger btn-xs delete_scrap_entry" data-scrap_entry_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i> Delete
		                        </a>
													';
				}
        
        //Reference No
        $reference_no_html = '<a href="'.base_url('scrap_entry/view/'.base64_encode($item->id)).'" target="_blank" data-tt="tooltip" title="'.$this->lang->line('scrap_entry_view').'">'.$item->reference_no.'</a>';


                     
				/* End Action column buttons*/


				$select_scrap_entry_html = '<input type="checkbox" class="single_scrap_entry"><input type="hidden" name="scrap_entry_id" id="scrap_entry_id"  value="'.$item->id.'">';

        $row = array();
       	
       	// $row[] = $select_scrap_entry_html;
	      $row[] = $reference_no_html;
	      // $row[] = $item->purchase_invoice_no;
        $row[] = $item->total_taxable_value;
	      $row[] = $item->total_product;
	      $row[] = $item->total_quantity;
	     
	      $row[] = date('d-m-Y', strtotime($item->scrap_entry_date));
	      $row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;

    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->scrap_entry_model->count_all(),
                    "recordsFiltered" => $this->scrap_entry_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function scrap_entry_delete_confirmation()
  {
  	$scrap_entry_id 				= $this->input->post('scrap_entry_id');
  	$data['scrap_entry'] 	= $this->scrap_entry_model->get_scrap_entry_single_record($scrap_entry_id);

  	$response 					= array();
  	$response['scrap_entry_delete_modal_body'] 	= $this->load->view('scrap_entry/ajax/scrap_entry_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}



	/************************************** End Dynamic Datatable function ***************************************/

	

  
}
