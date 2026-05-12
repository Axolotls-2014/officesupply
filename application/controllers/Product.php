<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Add these constants at the top of your Product controller or in constants.php

class Product extends MY_Controller {
    

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
public function export_csv()
{
    $this->load->model('Product_model');
    
    $products = $this->Product_model->get_records(null); 

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=products_' . date('Ymd_His') . '.csv');

    $output = fopen('php://output', 'w');

    fputcsv($output, [
        'id',
        'pid',
        'name',
        'description',
        'product_category_id',
        'cost',
        'selling_price',
        'price',
        'product_image',
        'hsn',
        'uom_id',
        'markup',
        'alert_quantity',
        'status',
        'user_id',
        'created_date',
        'updated_date',
        'delete_status',
        'manage_inventory',
        'brand_name'
    ]);

    foreach ($products as $product) {
        fputcsv($output, [
            $product->id,
            $product->pid,
            $product->name,
            $product->description,
            $product->product_category_id,
            $product->cost,
            $product->selling_price,
            $product->price,
            $product->product_image,
            $product->hsn_code, 
            $product->uom_id,
            $product->markup,
            $product->alert_quantity,
            $product->status,
            $product->user_id,
            $product->created_date,
            $product->updated_date,
            $product->delete_status,
            $product->manage_inventory,
            $product->brand_name
        ]);
    }

    fclose($output);
    exit;
}

	public function search()
	{
		$search_term 	    = $this->input->post('term');
		$module 			= $this->input->post('module');
		$warehouse_id = null;

		if($module == SALE_MODULE || $module == SALE_RETURN_MODULE || $module == PROFORMA_INVOICE_MODULE || $module == SCRAP_MODULE || $module == DELIVERY_CHALLAN_MODULE)
		{
			$warehouse_id = $this->input->post('warehouse_id');
		}
		
		$data = $this->product_model->search_record($search_term,$module,$warehouse_id);

		print_r(json_encode($data,true));
	}
	public function get_products_by_warehouse() {
    $warehouse_id = $this->input->post('warehouse_id');
    $term = $this->input->post('term');
    
    $this->db->select('p.*')
             ->from('products p')
             ->join('warehouse_products wp', 'wp.product_id = p.id')
             ->where('wp.warehouse_id', $warehouse_id)
             ->where('p.status', PRODUCT_STATUS_ACTIVE)
             ->group_by('p.id');
    
    if(!empty($term)) {
        $this->db->group_start()
                 ->like('p.name', $term)
                 ->or_like('p.product_code', $term)
                 ->or_like('p.hsn', $term)
                 ->group_end();
    }
    
    $products = $this->db->get()->result();
    echo json_encode($products);
}
	public function get_record_detail()
	{
		$module 		= $this->input->post('module');
		$product_id = $this->input->post('product_id');
     $warehouse_product = $this->warehouse_products_model->get_single_record($product_id);
		if($module == SALE_MODULE || $module == SALE_RETURN_MODULE || $module == PROFORMA_INVOICE_MODULE || $module == DELIVERY_CHALLAN_MODULE )
		{
			// echo 'in';
			$data['product'] 	= $this->warehouse_products_model->get_single_record_for_sales($product_id);
			$data['discount']	= $this->discount_model->get_valid_records();	
		}
		else
		{
			// echo 'out';
			// exit;
			$data['product'] = $this->product_model->get_single_record($product_id);
			$data['discount']= $this->discount_model->get_valid_records();	
		}
       $data['batch_nos'] = $this->warehouse_products_model->get_batch_nos_by_product_id($product_id);

		// echo '<pre>';
		// print_r( $data['batch_nos']);
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
			$data['products']   = $this->product_model->get_records();
			$data['warehouses'] = $this->warehouse_model->get_records();
			$this->load->view('product/list',$data);	
		}
	}
	/*with sudhakar sir discussion*/
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
					$description 					    	= 	$this->input->post('description');
					$product_category_id 	            	= 	$this->input->post('product_category_id');
					$hsn 									= 	$this->input->post('hsn');
                    $cost 									= 	$this->input->post('cost');
                    $price 									= 	$this->input->post('price');
                    $selling_price 				        	= 	$this->input->post('selling_price');
					$markup 								= 	$this->input->post('markup');
					$alert_quantity 			        	= 	$this->input->post('alert_quantity');
					$uom_id 								= 	$this->input->post('uom_id');
					$status 								= 	$this->input->post('status');
					$product_code                           = 	$this->input->post('product_code');
					$data					            	=	array(
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
																		"product_code"	                    =>  $product_code,
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
							$response 									= array();
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
	
	
	
	/*code end*/
	
	
	

/*public function add($id = NULL)
{
    if(!$this->permission_model->has_permission('add_product'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            // Add quantity validation
            $this->form_validation->set_rules('name','Name','required');
            $this->form_validation->set_rules('uom_id','Unit of Measure','required');
            $this->form_validation->set_rules('product_category_id','Product Category','required');
            $this->form_validation->set_rules('quantity','Initial Quantity','required|numeric'); // ADD THIS

            if($this->form_validation->run()==FALSE)
            {
                $data['product_categories'] = $this->product_category_model->get_records();
                $data['tax'] = $this->tax_model->get_records();
                $data['uoms'] = $this->uom_model->get_records();
                $this->load->view('product/add',$data);
            }
            else
            {   
                $name = $this->input->post('name');
                $description = $this->input->post('description');
                $product_category_id = $this->input->post('product_category_id');
                $hsn = $this->input->post('hsn');
                $cost = $this->input->post('cost');
                $price = $this->input->post('price');
                $selling_price = $this->input->post('selling_price');
                $markup = $this->input->post('markup');
                $alert_quantity = $this->input->post('alert_quantity');
                $uom_id = $this->input->post('uom_id');
                $status = $this->input->post('status');
                $product_code = $this->input->post('product_code');
                $manage_inventory = $this->input->post('manage_inventory');
                $warehouse_id = $this->input->post('warehouse_id');
                $quantity = $this->input->post('quantity'); // CRITICAL: Capture quantity from form
                
                $data = array(
                    "name" => $name,
                    "description" => $description,
                    "product_category_id" => $product_category_id,
                    "hsn" => $hsn,
                    "cost" => $cost,
                    "price" => $price,
                    "selling_price" => $selling_price,
                    "markup" => $markup,
                    "alert_quantity" => $alert_quantity,
                    "uom_id" => $uom_id,
                    "status" => $status,
                    "product_code" => $product_code,
                    "manage_inventory" => $manage_inventory,
                    "user_id" => $this->session->userdata('user_id')
                );
                
                // Begin transaction
                $this->db->trans_begin();
                
                if($id = $this->product_model->add_record($data))
                {
                    // Generate PID if needed
                    $pid = PID_SEQUENCE + $id;
                    $this->product_model->edit_record(array('pid' => $pid), $id);
                    
                    // Get UOM details
                    $uom = $this->uom_model->get_single_record($uom_id);
                    
                    // Add product to warehouse(s) with quantity
                    if($warehouse_id && $warehouse_id != '')
                    {
                        // Add to specific warehouse
                        $warehouse = $this->warehouse_model->get_single_record($warehouse_id);
                        
                        $warehouse_products = array(
                            "warehouse_id" => $warehouse_id,
                            "product_id" => $id,
                            "quantity" => ($manage_inventory == MANAGE_INVENTORY_NO) ? $quantity : 0,
                            "cost" => $cost,
                            "price" => $price,
                            "selling_price" => $selling_price,
                            "batch_no" => $pid
                        );
                        $this->warehouse_products_model->add_record($warehouse_products);
                        
                        // Create stock entry if quantity > 0
                        if($manage_inventory == MANAGE_INVENTORY_NO && $quantity > 0)
                        {
                            $stock_data = array(
                                "product_id" => $id,
                                "warehouse_id" => $warehouse_id,
                                "warehouse_name" => $warehouse->name,
                                "product_name" => $name,
                                "quantity" => $quantity,
                                "product_price" => $price,
                                "product_cost" => $cost,
                                "selling_price" => $selling_price,
                                "product_uom" => ($uom ? $uom->uom : ''),
                                "entry_type" => WAREHOUSE_STOCK_IN,
                                "batch_no" => $pid,
                                "user_id" => $this->session->userdata('user_id')
                            );
                            $this->stock_model->add_record($stock_data);
                        }
                    }
                    else
                    {
                        // Add to ALL warehouses
                        $warehouses = $this->warehouse_model->get_records();
                        foreach ($warehouses as $warehouse) {
                            $warehouse_products = array(
                                "warehouse_id" => $warehouse->id,
                                "product_id" => $id,
                                "quantity" => ($manage_inventory == MANAGE_INVENTORY_NO) ? $quantity : 0,
                                "cost" => $cost,
                                "price" => $price,
                                "selling_price" => $selling_price,
                                "batch_no" => $pid
                            );
                            $this->warehouse_products_model->add_record($warehouse_products);
                            
                            // Create stock entry for each warehouse
                            if($manage_inventory == MANAGE_INVENTORY_NO && $quantity > 0)
                            {
                                $stock_data = array(
                                    "product_id" => $id,
                                    "warehouse_id" => $warehouse->id,
                                    "warehouse_name" => $warehouse->name,
                                    "product_name" => $name,
                                    "quantity" => $quantity,
                                    "product_price" => $price,
                                    "product_cost" => $cost,
                                    "selling_price" => $selling_price,
                                    "product_uom" => ($uom ? $uom->uom : ''),
                                    "entry_type" => WAREHOUSE_STOCK_IN,
                                    "batch_no" => $pid,
                                    "user_id" => $this->session->userdata('user_id')
                                );
                                $this->stock_model->add_record($stock_data);
                            }
                        }
                    }

                    // Commit transaction
                    $this->db->trans_commit();

                    if($this->input->is_ajax_request())
                    {   
                        $response = array();
                        $response['code'] = 1;
                        $response['id'] = $id;
                        $response['quantity'] = $quantity;
                        $response['message'] = 'Product added successfully with quantity: ' . $quantity;
                        echo json_encode($response);
                    }
                    else
                    {
                        $this->session->set_flashdata('success', 'Product added successfully with quantity: ' . $quantity);
                        redirect('product','refresh');
                    }
                }
                else
                {
                    // Rollback transaction
                    $this->db->trans_rollback();

                    if($this->input->is_ajax_request())
                    {   
                        $response = array();
                        $response['code'] = 0;
                        $response['message'] = 'Product failed to add.';
                        echo json_encode($response);
                    }
                    else
                    {
                        $this->session->set_flashdata('failure', 'Product failed to add.');
                        redirect('product','refresh');
                    }
                }
            }
        }
        else
        {
            $data = array();
            $data['product_categories'] = $this->product_category_model->get_records();
            $data['uoms'] = $this->uom_model->get_records();
            $data['tax'] = $this->tax_model->get_records();

            if($this->input->is_ajax_request()) 
            {   
                $response = array();
                $response['code'] = 1;                        
                $response['add_product_modal_body'] = $this->load->view('product/ajax/add_product_modal_body',$data,TRUE);
                echo json_encode($response);
            }
            else
            {   
                $this->load->view('product/add',$data);
            }
        }
    }
}*/
	/*public function add2704($id = NULL)
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
					$description 					    	= 	$this->input->post('description');
					$product_category_id 	            	= 	$this->input->post('product_category_id');
					$hsn 									= 	$this->input->post('hsn');
                    $cost 									= 	$this->input->post('cost');
                    $price 									= 	$this->input->post('price');
                    $selling_price 				        	= 	$this->input->post('selling_price');
					$markup 								= 	$this->input->post('markup');
					$alert_quantity 			        	= 	$this->input->post('alert_quantity');
					$uom_id 								= 	$this->input->post('uom_id');
					$status 								= 	$this->input->post('status');
					
					$product_code                           = 	$this->input->post('product_code');
					$data					            	=	array(
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
																		"product_code"	                    =>  $product_code,
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
							$response 									= array();
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
	}*/
	

	
	public function edit($warehouse_product_id = null)
	{

		if(!$this->permission_model->has_permission('edit_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$warehouse_product_id = $this->input->post('warehouse_product_id');
				// $warehouse_product_id = $this->input->get('warehouse_product_id');
                $cost 									= 	$this->input->post('cost');
                $price 									= 	$this->input->post('price');
                $selling_price 					        = 	$this->input->post('selling_price');
                $data				            		=	array(
                                
                                              "cost"								=>	$cost,
                                              "price"								=>	$price,
                                              "selling_price"			        	=>	$selling_price
                                
																);

        if($this->warehouse_products_model->edit_record($data,$warehouse_product_id))
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
			else
			{
				if($this->input->is_ajax_request()) 
				{
					//$id = base64_decode($id);
					
					$data['product'] 							= $this->warehouse_products_model->get_single_record($warehouse_product_id);
				
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['edit_product_modal_body'] = $this->load->view('product/ajax/edit_product_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['product'] 							= $this->warehouse_products_model->get_single_record($warehouse_product_id);
					

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

public function get_product_vendors($product_id = null)
{
    // If product_id is not in URL parameter, try to get from GET request
    if (!$product_id) {
        $product_id = $this->input->get('product_id');
    }
    
    // Log for debugging
    log_message('debug', 'Product ID received: ' . $product_id);
    
    if (!$product_id) {
        echo json_encode([]);
        return;
    }

    $this->load->model('supplier_model');
    $vendors = $this->supplier_model->get_vendors_by_product($product_id);
    
    echo json_encode($vendors);
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

	public function export()
  {
    
    if($_GET['data'] != '')
    { 
			
    	$id    					= chop($_GET['data'],',');

      $warehouse_id 	= $_GET['warehouse_id'] ?? '';
			$product_id 		= $_GET['product_id'] ?? '';
      $quantity 			= $_GET['quantity'] ?? '';
			$product_status = $_GET['product_status'] ?? '';

      $whereConditions = '';

      if ($id) {
          $id = implode(',', explode(',', $id)); // Ensure the IDs are comma-separated
					$whereConditions .= " WHERE p.warehouse_product_id IN ($id)";
      }

      if ($product_status !== '') 
      {
        if($whereConditions != '')
        {
          $whereConditions .= ' AND p.status = "'.$product_status.'"';
        }
        else
        {
          $whereConditions .= " WHERE p.status = $product_status";
        }
        
      }

			if ($product_id !== '') 
      {
        if($whereConditions != '')
        {
          $whereConditions .= ' AND p.id = "'.$product_id.'"';
        }
        else
        {
          $whereConditions .= " WHERE p.id = $product_id";
        }
        
      }

			if ($warehouse_id !== '') 
      {
        if($whereConditions != '')
        {
          $whereConditions .= ' AND p.warehouse_id = "'.$warehouse_id.'"';
        }
        else
        {
          $whereConditions .= " WHERE p.warehouse_id = $warehouse_id";
        }
        
      }


      // Add a condition to filter by quantity
    // In the export() method, update the quantity condition section
if ($whereConditions != '') 
{
    if($quantity !== '')
    {
        if($quantity == QUANTITY_GREATER_THEN_ZERO)
        {
            $whereConditions .= " AND (quantity) > 0";
        }
        else if($quantity == QUANTITY_ZERO)
        {
            $whereConditions .= " AND (quantity) = 0";
        }
        else if($quantity == QUANTITY_BELOW_ZERO || $quantity == QUANTITY_NEGATIVE)
        {
            $whereConditions .= " AND (quantity) < 0";
        }
    }
}
else {
    if($quantity == QUANTITY_GREATER_THEN_ZERO)
    {
        $whereConditions .= " WHERE (quantity) > 0";
    }
    else if($quantity == QUANTITY_ZERO)
    {
        $whereConditions .= " WHERE (quantity) = 0";
    }
    else if($quantity == QUANTITY_BELOW_ZERO || $quantity == QUANTITY_NEGATIVE)
    {
        $whereConditions .= " WHERE (quantity) < 0";
    }
}

      // Add a condition to filter by records with non-null batch_no

      if ($whereConditions != '') 
      {
        $whereConditions .= " AND p.batch_no IS NOT NULL AND p.batch_no != ''";
      }
      else
      {
        
        $whereConditions .= " WHERE p.batch_no IS NOT NULL AND p.batch_no != ''";
      }

       
      

      $query = $this->db->query('SELECT 
																		p.warehouse_name as "Warehouse Name",
                                    p.product_category_name as "Product Category",
                                    p.name as "Name",
                                    p.description as "Description",
                                   	p.hsn as "HSN",
                                    p.product_cost as "Cost",
                                    p.product_price as "Price",
                                    
                                    p.batch_no as "Batch No",
                                    p.alert_quantity as "Alert Quantity",
                                    p.uom_name as "Unit of Measurement",
                                    p.igst as "Tax Rate",
                                    p.quantity as "Quantity",
                                    p.status as "Status"
                                 
                                      FROM 
                                    product_view p 
                                    ' . $whereConditions . '
                                    ORDER BY p.name ASC');
                                  //    WHERE 
                                  //   p.warehouse_product_delete_status = 0 AND 
                                  //    p.warehouse_product_id IN ('.$id.')
                                  //   - ORDER BY p.name ASC
                                  // ');

    
    	$this->load->dbutil();
      
      $data = $this->dbutil->csv_from_result($query);
      
      $this->load->helper('download');
      force_download("WAREHOUSE_PRODUCT_EXPORTED.CSV", $data);
    }
    else
    {
      
			$warehouse_id 	= $_GET['warehouse_id'] ?? '';
			$product_id 		= $_GET['product_id'] ?? '';
      $quantity 			= $_GET['quantity'] ?? '';
			$product_status = $_GET['product_status'] ?? '';

      $whereConditions = '';

      if ($product_status !== '' && $whereConditions != '') {
        $whereConditions .= ' AND p.status = "'.$product_status.'"';
      }

			if ($product_id !== '') 
      {
        if($whereConditions != '')
        {
          $whereConditions .= ' AND p.id = "'.$product_id.'"';
        }
        else
        {
          $whereConditions .= " WHERE p.id = $product_id";
        }
        
      }

			if ($warehouse_id !== '') 
      {
        if($whereConditions != '')
        {
          $whereConditions .= ' AND p.warehouse_id = "'.$warehouse_id.'"';
        }
        else
        {
          $whereConditions .= " WHERE p.warehouse_id = $warehouse_id";
        }
        
      }

   

      // Add a condition to filter by quantity
      if ($whereConditions != '') 
      {
        if($quantity !== '')
        {
          echo 'in condition';
          if($quantity == QUANTITY_GREATER_THEN_ZERO)
          {
             $whereConditions .= " AND (
                quantity
              
            ) > 0";
          }

          else if($quantity == QUANTITY_ZERO)
          {
            $whereConditions .= " AND (
               quantity
               
            ) = 0";
          }
        }
      }
      else {
        if($quantity == QUANTITY_GREATER_THEN_ZERO)
        {
          $whereConditions .= " WHERE (
              quantity
             
          ) > 0";
        }
        else if($quantity == QUANTITY_ZERO)
        {
          $whereConditions .= " WHERE (
              quantity
             
          ) = 0";
        }
      }

       // Add a condition to filter by records with non-null batch_no
      if ($whereConditions != '') 
      {
        $whereConditions .= " AND p.batch_no IS NOT NULL AND p.batch_no != ''";
      }
      else
      {
        
        $whereConditions .= " WHERE p.batch_no IS NOT NULL AND p.batch_no != ''";
      }
       
    	$query = $this->db->query('SELECT 
                                   	p.warehouse_name as "Warehouse Name",
                                    p.product_category_name as "Product Category",
                                    p.name as "Name",
                                    p.description as "Description",
                                   	p.hsn as "HSN",
                                    p.product_cost as "Cost",
                                    p.product_price as "Price",
                                    
                                    p.batch_no as "Batch No",
                                    p.alert_quantity as "Alert Quantity",
                                    p.uom_name as "Unit of Measurement",
                                    p.igst as "Tax Rate",
                                    p.quantity as "Quantity",
                                    p.status as "Status"
                                 
                                      FROM 
                                    product_view p 
                                    ' . $whereConditions . '
                                    ORDER BY p.name ASC');
                                  //    WHERE 
                                  //   p.warehouse_product_delete_status = 0 AND 
                                  //    p.warehouse_product_id IN ('.$id.')
                                  //   - ORDER BY p.name ASC
                                  // ');

    	$this->load->dbutil();
      
      $data = $this->dbutil->csv_from_result($query);
    
      $this->load->helper('download');
      force_download("WAREHOUSE_PRODUCT_EXPORTED.CSV", $data);
    } 
  }
 public function ajax_list()
{
    $list = $this->product_model->get_datatables();
    $data = array();
    $no = $_POST['start'];

    foreach ($list as $item){  
        
        /* Begin Action column buttons*/
        $table_header = '<div class="btn-group">';
        $table_footer = '</div>';
        $table_body = '';

        // Edit Button        
        if($this->permission_model->has_permission('edit_product'))
        {   
            $table_body .= '<a href="#" data-toggle="modal" data-target="#edit_product_modal" data-tt="tooltip" title="'.$this->lang->line('product_edit').'" class="btn btn-warning btn-xs edit_product_modal mr-2" data-warehouse_product_id="'.$item->warehouse_product_id.'">
                              <i class="fas fa-edit"></i> 
                            </a>';
        }

        // Stock In/Out Buttons
        if($this->permission_model->has_permission('add_stock'))
        {
            $warehouse_product = $this->warehouse_products_model->get_single_record($item->warehouse_product_id);
            $table_body .= '<a href="#" data-toggle="modal" data-target="#add_stock_modal" data-tt="tooltip" title="Stock In" data-entry_type="in" class="btn btn-danger btn-xs add_stock_modal mr-2" data-warehouse_product_id="'.$item->warehouse_product_id.'" data-product_id="'.$warehouse_product->product_id.'" data-warehouse_id="'.$warehouse_product->warehouse_id.'">
                              <i class="far fa-plus-square"></i> 
                            </a>';
            $table_body .= '<a href="#" data-toggle="modal" data-target="#add_stock_modal" data-tt="tooltip" title="Stock Out" data-entry_type="out" class="btn btn-success btn-xs add_stock_modal" data-warehouse_product_id="'.$item->warehouse_product_id.'" data-product_id="'.$warehouse_product->product_id.'" data-warehouse_id="'.$warehouse_product->warehouse_id.'">
                              <i class="far fa-minus-square"></i> 
                            </a>';
        }

        /* End Action column buttons*/

        // Product Status Badge
        $product_status = '';
        if($item->status == PRODUCT_STATUS_ACTIVE)
            $product_status .= '<span class="badge badge-success">'.ucfirst(PRODUCT_STATUS_ACTIVE).'</span>';
        if($item->status == PRODUCT_STATUS_INACTIVE)
            $product_status .= '<span class="badge badge-danger">'.ucfirst(PRODUCT_STATUS_INACTIVE).'</span>';

        // Checkbox for bulk actions
        $select_product_html = '<input type="checkbox" class="single_product" data-warehouse_product_id="'.$item->warehouse_product_id.'" data-product_id="'.$item->id.'"><input type="hidden" name="product_id" id="product_id" data-warehouse_product_id="'.$item->warehouse_product_id.'" value="'.$item->id.'">';

        // Calculate values
        $available_qty = $item->quantity;
        $purchase_price = $item->product_cost;  // Purchase Price (Base price)
        $mrp = $item->product_price;  // MRP (Maximum Retail Price - usually includes GST)
        $tax_rate = isset($item->igst) ? $item->igst : 0; // Get GST rate from product_view
        
        // Calculation Option 1: If MRP includes GST (most common scenario)
        // Then Total Amount = MRP × Quantity
        $total_amount = $mrp * $available_qty;
        
        // Taxable Value = Total Amount ÷ (1 + GST Rate/100) if MRP includes GST
        // OR Taxable Value = Purchase Price × Quantity if that's the base price
        $taxable_value = $purchase_price * $available_qty;
        
        // Calculate GST Amount
        $gst_amount = $taxable_value * ($tax_rate / 100);
        
        // Alternative: If Total Amount should be Taxable Value + GST
        $total_amount_with_gst = $taxable_value + $gst_amount;

        // Build row array with columns in the specified order
        $row = array();
        $row[] = $select_product_html;           // Checkbox
        $row[] = $item->warehouse_name;           // Branch
        $row[] = $item->name . '<br/><span style="font-size:12px;font-weight:bolder">' . strtoupper($item->product_category_name) . '</span>'; // Product Name + Category
        $row[] = $item->hsn;                      // HSN
        $row[] = number_format($available_qty, 0); // Available Qty
        $row[] = $item->batch_no;                  // Batch No
        $row[] = $item->uom_name;                  // UOM
        $row[] = number_format($item->alert_quantity, 0); // Alert Qty
        $row[] = number_format($mrp, 2);           // MRP
        $row[] = number_format($purchase_price, 2); // Purchase Price
        $row[] = number_format($taxable_value, 2);  // Taxable Value (Base price × Qty)
        $row[] = number_format($total_amount_with_gst, 2);   // Total Amount (Taxable Value + GST)
        $row[] = $product_status;                   // Status
        $row[] = $table_header . $table_body . $table_footer; // Action Buttons
        
        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->product_model->count_all(),
        "recordsFiltered" => $this->product_model->count_filtered(),
        "data" => $data,
    );
   
    echo json_encode($output);
}
	public function ajax_list2704()
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
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_product_modal" data-tt="tooltip" title="'.$this->lang->line('product_edit').'" class="btn btn-warning btn-xs edit_product_modal mr-2" data-warehouse_product_id="'.$item->warehouse_product_id.'">
	                          <i class="fas fa-edit"></i> 
	                        </a>';
			}

			// Delete Button
			// if($this->permission_model->has_permission('delete_product'))
			// {	
			// 	$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_product_modal" data-tt="tooltip" title="'.$this->lang->line('product_delete').'" class="btn btn-danger btn-xs delete_product_modal" data-product_id="'.$item->id.'">
	    //                       <i class="fas fa-trash"></i> Delete
	    //                     </a>';
			// }

			if($this->permission_model->has_permission('add_stock'))
      {
				$warehouse_product = $this->warehouse_products_model->get_single_record($item->warehouse_product_id);
      	$table_body .= '<a href="#"  data-toggle="modal" data-target="#add_stock_modal" data-tt="tooltip" title="Stock In" data-entry_type="in" class="btn btn-danger btn-xs add_stock_modal mr-2" data-warehouse_product_id="'.$item->warehouse_product_id.'" data-product_id="'.$warehouse_product->product_id.'" data-warehouse_id="'.$warehouse_product->warehouse_id.'">
                          <i class="far fa-plus-square"></i> 
                        </a>';

        $table_body .= '<a href="#"  data-toggle="modal" data-target="#add_stock_modal" data-tt="tooltip" title="Stock Out" data-entry_type="out" class="btn btn-success btn-xs add_stock_modal" data-warehouse_product_id="'.$item->warehouse_product_id.'" data-product_id="'.$warehouse_product->product_id.'" data-warehouse_id="'.$warehouse_product->warehouse_id.'">
                          <i class="far fa-minus-square"></i> 
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

       	$select_product_html = '<input type="checkbox" class="single_product" data-warehouse_product_id="'.$item->warehouse_product_id.'" data-product_id="'.$item->id.'"><input type="hidden" name="product_id" id="product_id" data-warehouse_product_id="'.$item->warehouse_product_id.'" value="'.$item->id.'">';


        $category_query = $this->db->select('name')
                                  ->from('product_category')
                                  ->where('id', $item->product_category_id)
                                  ->get();
        $category_name = $category_query->num_rows() > 0 ? $category_query->row()->name : 'N/A';
        
        // Get UOM name directly
        $uom_query = $this->db->select('uom')
                             ->from('uom')
                             ->where('id', $item->uom_id)
                             ->get();
        $uom_name = $uom_query->num_rows() > 0 ? $uom_query->row()->uom : 'N/A';


        $row = array();
       
        $row[] = $select_product_html;
        $row[] = $item->warehouse_name;
        $row[] = $item->name.'<br/><span style="font-size:12px;font-weight:bolder">'.strtoupper($item->product_category_name).'</span>';
       
        $row[] = $item->hsn;
        
        $row[] = number_format($item->quantity,0);
        $row[] = $item->batch_no;
        $row[] = $item->uom_name;
        $row[] = number_format($item->alert_quantity,0);
        $row[] = number_format($item->product_cost,2);
        $row[] = number_format($item->product_cost*$item->quantity,2);
        // $row[] = number_format($item->product_price,2);;
        // $row[] = number_format($item->product_price*$item->quantity,2);
        
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

  public function export_existing_products()
  {
    $query = $this->product_model->export_records();
    $this->load->dbutil();
    
    $data = $this->dbutil->csv_from_result($query);
    
    $this->load->helper('download');
    force_download("WAREHOUSE_PRODUCT_EXPORTED.CSV", $data);
  }
	
}
