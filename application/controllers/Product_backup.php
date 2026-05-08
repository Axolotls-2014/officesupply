<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}

	public function search()
	{
		$search_term 	= $this->input->post('term');
		$module 			= $this->input->post('module');
		$warehouse_id = null;

		if($module == SALE_MODULE || $module == SALE_RETURN_MODULE)
		{
			$warehouse_id = $this->input->post('warehouse_id');
		}
		
		$data = $this->product_model->search_record($search_term,$module,$warehouse_id);

		print_r(json_encode($data,true));
	}

	public function get_record_detail()
	{
		$module 		= $this->input->post('module');
    
		$product_id = $this->input->post('product_id');
    $warehouse_product = $this->warehouse_products_model->get_single_record($product_id);

		if($module == SALE_MODULE || $module == SALE_RETURN_MODULE || $module == PURCHASE_RETURN_MODULE)
		{
			$data['product'] 	= $this->warehouse_products_model->get_single_record_for_sales($product_id);
			$data['discount']	= $this->discount_model->get_valid_records();	
		}
		else
		{
			$data['product'] = $this->product_model->get_single_record($product_id);
			$data['discount']= $this->discount_model->get_valid_records();	
		}

    $data['batch_nos'] = $this->warehouse_products_model->get_batch_nos_by_product_id($product_id);

		// echo $this->db->last_query();
		// exit;
		
		print_r(json_encode($data,true));
	}

	public function index()
	{
		if(!$this->permission_model->has_permission('list_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['products'] = $this->product_model->get_records();
			$data['warehouses'] = $this->warehouse_model->get_records();
			$this->load->view('product/list',$data);	
		}
	}

	public function add($id = NULL)
	{
		if(!$this->permission_model->has_permission('add_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				
				$this->form_validation->set_rules('name','Name','required');
				// $this->form_validation->set_rules('hsn','HSN','required');		
				$this->form_validation->set_rules('uom_id','Unit of Measure','required');
				$this->form_validation->set_rules('product_category_id','Product Category','required');
        

				if($this->form_validation->run()==FALSE)
				{
					$data['product_categories'] = $this->product_category_model->get_records();
					$data['tax'] 								= $this->tax_model->get_records();
					$data['uoms'] 							= $this->uom_model->get_records();
					$this->load->view('product/add',$data);
				}
				else
				{	
					$name 									= 	$this->input->post('name');
					$description 						= 	$this->input->post('description');
					$product_category_id 		= 	$this->input->post('product_category_id');
					$hsn 										= 	$this->input->post('hsn');
          $cost 									= 	$this->input->post('cost');
          $price 									= 	$this->input->post('price');
          $selling_price 					= 	$this->input->post('selling_price');
					$markup 								= 	$this->input->post('markup');
					$alert_quantity 				= 	$this->input->post('alert_quantity');
					$uom_id 								= 	$this->input->post('uom_id');
					$status 								= 	$this->input->post('status');


					$data						=	array(
																		"name"								=> 	$name,
																		"description"					=>	$description,
																		"product_category_id"	=>	$product_category_id,
																		"hsn"									=>	$hsn,
                                    "cost"								=>	$cost,
                                    "price"								=>	$price,
                                    "selling_price"				=>	$selling_price,
																		"markup"							=>  $markup,
																		"alert_quantity"			=>  $alert_quantity,
																		"uom_id"							=>  $uom_id,
																		"status"							=>  $status,
																		"user_id"							=>  $this->session->userdata('user_id')
																);

					// being transaction
					$this->db->trans_begin();
					
					if($id = $this->product_model->add_record($data))
					{
						// Add products to warehouse with zero quantity
						$warehouses = $this->warehouse_model->get_records();
						foreach ($warehouses as $value) {
							$warehouse_products = array(
																					"warehouse_id"	=> $value->id,
																					"product_id"	=> $id
																				);
							$this->warehouse_products_model->add_record($warehouse_products);
						}

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'product is added successfully.';
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'product is added successfully');
							redirect('product','refresh');
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
							$response['message']						= 'product is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'product is failed to add.');
							redirect('product','refresh');
						}
					}
				}
			}
			else
			{
				$data 												= array();
				$data['product_categories'] 	= $this->product_category_model->get_records();
				$data['uoms'] 								= $this->uom_model->get_records();
				$data['tax']	 								= $this->tax_model->get_records();

				if($this->input->is_ajax_request()) 
				{	
					$response 									= array();

					$response['code']						= 1; 						
			  	$response['add_product_modal_body'] 	= $this->load->view('product/ajax/add_product_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{	
					$this->load->view('product/add',$data);
				}
			}
		}
	}

	
	public function edit($id = null)
	{

		if(!$this->permission_model->has_permission('edit_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');

				$this->form_validation->set_rules('name','Name','required');		
				$this->form_validation->set_rules('product_category_id','Product Category','required');		
				$this->form_validation->set_rules('uom_id','Unit of Measure','required');	
				// $this->form_validation->set_rules('hsn','HSN','required');
        
			
				if($this->form_validation->run()==FALSE)
				{
					$data['product'] 						=	$this->product_model->get_single_record($id);
					$data['product_categories'] = $this->product_category_model->get_records();
					$data['uoms'] 							= $this->uom_model->get_records();
					$this->load->view('product/edit',$data);

				}
				else
				{
					$name 									= 	$this->input->post('name');
					$description 						= 	$this->input->post('description');
					$product_category_id 		= 	$this->input->post('product_category_id');
					$markup 								= 	$this->input->post('markup');
					$hsn 										= 	$this->input->post('hsn');
          $hsn 										= 	$this->input->post('hsn');
          $cost 									= 	$this->input->post('cost');
          $price 									= 	$this->input->post('price');
          $selling_price 					= 	$this->input->post('selling_price');
					$uom_id 								= 	$this->input->post('uom_id');
					$alert_quantity 				= 	$this->input->post('alert_quantity');
					$status 								= 	$this->input->post('status');

					$data						=	array(
																		"name"								=> 	$name,
																		"description"					=>	$description,
																		"product_category_id"	=>	$product_category_id,
																		"uom_id"							=>  $uom_id,
																		"hsn"									=>	$hsn,
                                    "cost"								=>	$cost,
                                    "price"								=>	$price,
                                    "selling_price"				=>	$selling_price,
																		"markup"							=>  $markup,
																		"alert_quantity"			=>  $alert_quantity,
																		"status"							=>  $status
																);

					if($this->product_model->edit_record($data,$id))
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code'] = 1;
							$response['message'] = 'Product is updated successfully';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'product is updated successfully');
							redirect('product','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code']  		= 0;
							$response['message'] 	= 'Product is failed to update';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'product is failed to update');
							redirect('product','refresh');	
						}
						
					}
				}
				
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($id);
					
					$data['product'] 		= $this->product_model->get_single_record($id);
					$data['product_categories'] 	= $this->product_category_model->get_records();
					$data['uoms'] 								= $this->uom_model->get_records();
					$data['tax']	 								= $this->tax_model->get_records();
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['edit_product_modal_body'] = $this->load->view('product/ajax/edit_product_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['product'] 							= $this->product_model->get_single_record($id);
					$data['product_categories'] 	= $this->product_category_model->get_records();
					$data['uoms'] 								= $this->uom_model->get_records();
					$data['tax']	 								= $this->tax_model->get_records();

					if($data['product'] != null)
					{

						$this->load->view('product/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('product/');
					}
				}
				
			}
		}
	}

	public function bulk_edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
				$product_ids = $this->input->post('product_ids');

				$product_category_id 	= $this->input->post('product_category_id');
				$hsn 									= $this->input->post('hsn');
				$markup 							= $this->input->post('markup');
				$alert_quantity 			= $this->input->post('alert_quantity');
				$uom_id 							= $this->input->post('uom_id');
				$status 							= $this->input->post('status');

				$data = array();

				if($product_category_id != '')
					$data['product_category_id'] = $product_category_id;				

				if($hsn != '')
					$data['hsn'] = $hsn;				

				if($markup != '')
					$data['markup'] = $markup;				

				if($alert_quantity != '')
					$data['alert_quantity'] = $alert_quantity;				

				if($uom_id != '')
					$data['uom_id'] = $uom_id;		

				if($status != '')
					$data['status'] = $status;		

				$product_id_array = explode(",", $product_ids);

				for ($i=0; $i < sizeof($product_id_array); $i++) { 
					$this->product_model->edit_record($data,$product_id_array[$i]);			
				}

				if($this->input->is_ajax_request()) 
				{
					$response 						= array();
					$response['code']	 		= 1;
					$response['message'] 	= sizeof($product_id_array). ' Products are successfully updated';
					echo json_encode($response);
				}
				else
				{	
					redirect('product','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$data['product_categories'] 	= $this->product_category_model->get_records();
					$data['uoms'] 								= $this->uom_model->get_records();
					$data['tax']	 								= $this->tax_model->get_records();
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['bulk_edit_modal_body'] = $this->load->view('product/ajax/bulk_edit_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

	public function import_product()
	{
		if(!$this->permission_model->has_permission('import_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
				$product_category_id 	= $this->input->post('product_category_id');
				$uom_id 							= $this->input->post('uom_id');
				
				$filename = $this->upload_model->do_single_upload($_FILES['csvfile']);	
				$file_path = './assets/import/'.$filename;

				$query = sprintf(
													"
														LOAD DATA LOCAL INFILE '%s' INTO TABLE product 
								            FIELDS TERMINATED BY ',' 
								            LINES TERMINATED BY '\r\n'
								            IGNORE 1 LINES (name, description, markup, hsn, alert_quantity);
							           	", 
							          addslashes($file_path));

				if($number = $this->db->query($query))
				{
					$data = array(
													'user_id' 						=> $this->session->userdata('user_id'),
													'uom_id'  						=> $uom_id,
													'product_category_id' => $product_category_id
											);
					$no_imported_products = $this->db->affected_rows();

					if($this->product_model->get_records_by_last_import($no_imported_products, $data)){
						$this->session->set_flashdata('success', $no_imported_products.' Products are imported successfully');
						redirect('product','refresh');
					}
					else{
						$this->session->set_flashdata('success', $no_imported_products.' Products are failed to import.');
						redirect('product','refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('success', $no_imported_products.' Products are failed to import.');
					redirect('product','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$data['product_categories'] 	= $this->product_category_model->get_records();
					$data['uoms'] 								= $this->uom_model->get_records();
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['import_product_modal_body'] = $this->load->view('product/ajax/import_product_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 	= $this->input->post('id');

			$data = array('delete_status' => 1);

			$product =  $this->product_model->get_single_record($id);

			if($this->product_model->edit_record($data,$id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= 'Product is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Product is deleted successfully');
					redirect('product','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Product is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Product is failed to delete');
					redirect('product');
				}
			}
		}
	}


  public function product_delete_confirmation()
  {
  	$product_id 				= $this->input->post('product_id');
  	$data['product']		= $this->product_model->get_single_record($product_id);

  	$response 																		= array();
  	$response['delete_product_modal_body'] 	= $this->load->view('product/ajax/delete_product_modal_body',$data,TRUE);

  	echo json_encode($response);
	}

	function warehouse_wise_product_quantity()
	{
		$product_id 												= $this->input->post('product_id');
		$data['product_quantity_data'] 			= $this->warehouse_products_model->get_warehouse_wise_product_quantity($product_id);
		$response['product_quantity_data']	= $this->load->view('product/ajax/warehouse_wise_product_list',$data,TRUE);

		echo json_encode($response);
	}

	function product_quantity_warehouse_wise()
	{
		$warehouse_id 											= $this->input->post('warehouse_id');
		$data['product_quantity_data'] 			= $this->warehouse_products_model->get_product_quantity_warehouse_wise($warehouse_id);
		$response['product_quantity_data']	= $this->load->view('warehouse/ajax/product_quantity_warehouse_wise',$data,TRUE);

		echo json_encode($response);
	}

	public function ajax_list()
  {
    $list 		= $this->product_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //view Button
        // $table_body .=	'<td class="p-2">
        //                   <a href="#" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#warehouse_wise_product" data-tt="tooltip" title="'.$this->lang->line('product_quantity_warehouse_wise').'" data-product_id="'.$item->id.'">
        //                     <i class="fas fa-cubes"></i> Warehouse
        //                   </a>
        //                 </td>';

     // Edit Button        
      if($this->permission_model->has_permission('edit_product'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_product_modal" data-tt="tooltip" title="'.$this->lang->line('product_edit').'" class="btn btn-info btn-xs edit_product_modal" data-product_id="'.base64_encode($item->id).'">
	                          <i class="fas fa-edit"></i> Edit
	                        </a>';
			}

			// Delete Button
			if($this->permission_model->has_permission('delete_product'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_product_modal" data-tt="tooltip" title="'.$this->lang->line('product_delete').'" class="btn btn-danger btn-xs delete_product_modal" data-product_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i> Delete
	                        </a>';
			}

		

			/* End Action column buttons*/
			// Product Quantity
				$products 				= $this->warehouse_products_model->get_records_by_product_id($item->id);
				$product_quantity = 0;
				if(sizeof($products) > 0)
        {
          foreach ($products as $product) {
            $product_quantity += $product->quantity; 
          } 
        }

        $product_status = '';

        if($item->status == PRODUCT_STATUS_ACTIVE)
        	$product_status .= '<span class="badge badge-success">'.ucfirst(PRODUCT_STATUS_ACTIVE).'</span>';

        if($item->status == PRODUCT_STATUS_INACTIVE)
        	$product_status .= '<span class="badge badge-danger">'.ucfirst(PRODUCT_STATUS_INACTIVE).'</span>';

        $select_product_html = '<input type="checkbox" class="single_product"><input type="hidden" name="product_id" id="product_id" value="'.$item->id.'">';

        $row = array();
       
        $row[] = $select_product_html;
        $row[] = $item->warehouse_name;
        $row[] = $item->name.'<br/><span style="font-size:12px;font-weight:bolder">'.strtoupper($item->product_category_name).'</span>';
        $row[] = $item->hsn;
        $row[] = number_format($item->quantity,0);
        $row[] = $item->uom_name;
        $row[] = number_format($item->alert_quantity,0);
        $row[] = number_format($item->product_cost,2);
        $row[] = number_format($item->product_cost*$item->quantity,2);
        $row[] = number_format($item->product_price,2);;
        $row[] = number_format($item->product_price*$item->quantity,2);
        
        $row[] = $product_status;
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->product_model->count_all(),
                    "recordsFiltered" => $this->product_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	
	
}
