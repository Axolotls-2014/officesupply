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

		if($module == SALE_MODULE || $module == SALE_RETURN_MODULE || $module == PURCHASE_RETURN_MODULE)
		{
			$data['product'] 	= $this->warehouse_products_model->get_single_record_for_sales($product_id);
			$data['discount']	= $this->discount_model->get_records();	
		}
		else
		{
			$data['product'] = $this->product_model->get_single_record($product_id);
			$data['discount']= $this->discount_model->get_records();	
		}
		
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
			$data['product'] = $this->product_model->get_records();

			$log_data = array(
	              "user_id"     => $this->session->userdata("user_id"),
	              "module"      => "product",
	              "user_action" => 0,
	              "description"	=> "User has viewed list of product."
	            );

			$this->log_data_model->add_record($log_data);

			$this->load->view('product/list',$data);
		}
	}

	public function add()
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
				$this->form_validation->set_rules('hsn','HSN','required');		
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
					$markup 								= 	$this->input->post('markup');
					$alert_quantity 				= 	$this->input->post('alert_quantity');
					$uom_id 								= 	$this->input->post('uom_id');
					$status 								= 	$this->input->post('status');


					$data						=	array(
																		"name"								=> 	$name,
																		"description"					=>	$description,
																		"product_category_id"	=>	$product_category_id,
																		"hsn"									=>	$hsn,
																		"markup"							=>  $markup,
																		"alert_quantity"			=>  $alert_quantity,
																		"uom_id"							=>  $uom_id,
																		"status"							=>  $status,
																		"user_id"							=>  $this->session->userdata('user_id')
																);
				
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

						
						$entered_product = $this->product_model->get_single_record($id);

						$log_data = array(
															"user_id" 		=> $this->session->userdata("user_id"),
															"module"		=> "product",
															"entry_id"		=> $id,
															"user_action"	=> 1,
															"data"			=> json_encode((array)$entered_product),
															"description" 	=> $name.' successfully added.'
														);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is added successfully');
						redirect('product','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to add.');
						redirect('product','refresh');
					}
				}
			}
			else
			{
				$data['product_categories'] 	= $this->product_category_model->get_records();
				$data['uoms'] 								= $this->uom_model->get_records();
				$data['tax']	 								= $this->tax_model->get_records();
				$this->load->view('product/add',$data);
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

				$id 						= $this->input->post('id');
				$old_product 		= $this->product_model->get_single_record($id);

				$this->form_validation->set_rules('name','Name','required');		
				$this->form_validation->set_rules('product_category_id','Product Category','required');		
				$this->form_validation->set_rules('uom_id','Unit of Measure','required');	
				$this->form_validation->set_rules('hsn','HSN','required');			
				
				
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
					$uom_id 								= 	$this->input->post('uom_id');
					$alert_quantity 				= 	$this->input->post('alert_quantity');
					$status 								= 	$this->input->post('status');

					$data						=	array(
																		"name"								=> 	$name,
																		"description"					=>	$description,
																		"product_category_id"	=>	$product_category_id,
																		"uom_id"							=>  $uom_id,
																		"hsn"									=>	$hsn,
																		"markup"							=>  $markup,
																		"alert_quantity"			=>  $alert_quantity,
																		"status"							=>  $status,
																		"user_id"							=>  $this->session->userdata('user_id')
																);
				
					if($this->product_model->edit_record($data,$id))
					{
						$entered_product = $this->product_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "product",
											"user_action"	=> 2,
											"b_data"			=> json_encode((array)$old_product),
											"data"				=> json_encode((array)$entered_product),
											"description" => $name.' successfully updated.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is updated successfully.');
						redirect('product','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to update.');
						redirect('product','refresh');
					}
				}
			}
			else
			{
				$id 									= base64_decode($id);

				if($id != null)
				{
					$data['product'] 							= $this->product_model->get_single_record($id);

					if($data['product'] != null)
					{
						$data['product_categories'] 	= $this->product_category_model->get_records();
						$data['uoms'] 								= $this->uom_model->get_records();
						$data['tax']	 								= $this->tax_model->get_records();
						$this->load->view('product/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure', 'You are trying to access broken URL.');
						redirect('product');
					}
					
				}
				else
				{
					$this->session->set_flashdata('failure', 'You are trying to access broken URL.');
					redirect('product');
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
			$id = $this->input->post('id');
			$data 		= array('delete_status' => 1);
			$product 	= $this->product_model->get_single_record($id);

			if($this->product_model->edit_record($data,$id))
			{
				$log_data = array(
		                      "user_id"     => $this->session->userdata("user_id"),
		                      "module"      => "product",
		                      "user_action" => 3,
		                      "data"        => json_encode($data),
		                      "description" => $product->name.' deleted successfully.'
		                    );
    		$this->log_data_model->add_record($log_data);     

				$this->session->set_flashdata('success', $product->name.' is deleted successfully.');
				redirect('product','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', $product->name.' failed to delete.');
				redirect('product','refresh');
			}
		}
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

	function bulk_edit()
	{
		if(!$this->permission_model->has_permission('edit_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
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
					$uom_id 								= 	$this->input->post('uom_id');
					$alert_quantity 				= 	$this->input->post('alert_quantity');
					$status 								= 	$this->input->post('status');

					$data						=	array(
																		"name"								=> 	$name,
																		"description"					=>	$description,
																		"product_category_id"	=>	$product_category_id,
																		"uom_id"							=>  $uom_id,
																		"hsn"									=>	$hsn,
																		"markup"							=>  $markup,
																		"alert_quantity"			=>  $alert_quantity,
																		"status"							=>  $status,
																		"user_id"							=>  $this->session->userdata('user_id')
																);
				
					if($this->product_model->edit_record($data,$id))
					{
						$entered_product = $this->product_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "product",
											"user_action"	=> 2,
											"b_data"			=> json_encode((array)$old_product),
											"data"				=> json_encode((array)$entered_product),
											"description" => $name.' successfully updated.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is updated successfully.');
						redirect('product','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to update.');
						redirect('product','refresh');
					}
				}
			}
			else
			{
				$id 									= base64_decode($id);

				if($id != null)
				{
					$data['product'] 							= $this->product_model->get_single_record($id);

					if($data['product'] != null)
					{
						$data['product_categories'] 	= $this->product_category_model->get_records();
						$data['uoms'] 								= $this->uom_model->get_records();
						$data['tax']	 								= $this->tax_model->get_records();
						$this->load->view('product/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure', 'You are trying to access broken URL.');
						redirect('product');
					}
					
				}
				else
				{
					$this->session->set_flashdata('failure', 'You are trying to access broken URL.');
					redirect('product');
				}
			}
		}
	}

	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->product_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<table><tr>';
        $table_footer = '</tr></table>';
        $table_body   = '';

        //view Button
        $table_body .=	'<td class="p-2">
                          <a href="#" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#warehouse_wise_product" data-tt="tooltip" title="'.$this->lang->line('product_quantity_warehouse_wise').'" data-product_id="'.$item->id.'">
                            <i class="fas fa-cubes"></i>
                          </a>
                        </td>';

				// Edit Button        
        if($this->permission_model->has_permission('edit_product'))
				{	
					$table_body .= '<td class="p-2">
		                        <a href="'.base_url('product/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" data-tt="tooltip" title="'.$this->lang->line('product_edit').'">
		                          <i class="fas fa-edit"></i>
		                        </a>        
		                      </td>';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_product'))
				{	
					$table_body .= '<td class="p-2">
		                        <a href="#"  data-toggle="modal" data-target="#delete_product" data-tt="tooltip" title="'.$this->lang->line('product_delete').'" class="btn btn-danger btn-xs delete_product" data-product_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i>
		                        </a>
													</td>';
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

        $select_product_html = '<input type="checkbox" class="single_product"><input type="hidden" name="product_id" value="'.$item->id.'">';

        $row = array();
       
        $row[] = $select_product_html;
        $row[] = $item->name;
        $row[] = $item->description;
        $row[] = $item->product_category_name;
        $row[] = $item->hsn;
        $row[] = $item->markup;
        $row[] = $item->uom_name;
        $row[] = $product_quantity;
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

  public function product_delete_confirmation()
  {
  	$product_id 				= $this->input->post('product_id');
  	$data['product_id'] = $product_id;

  	$response 					= array();
  	$response['product_delete_modal_body'] 	= $this->load->view('product/ajax/product_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/
}
