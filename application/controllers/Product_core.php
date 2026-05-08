<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_core extends MY_Controller {

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
		
		$data = $this->product_core_model->search_record($search_term,$module,$warehouse_id);

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
			$data['product'] = $this->product_core_model->get_single_record($product_id);
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
			$data['products'] = $this->product_core_model->get_records();
			$data['warehouses'] = $this->warehouse_model->get_records();
			$this->load->view('product_core/list',$data);	
		}
	}
	
public function add_product($id = NULL)
{
    if(!$this->permission_model->has_permission('add_product'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            // Form Validation
            $this->form_validation->set_rules('name','Name','required');
            $this->form_validation->set_rules('uom_id','Unit of Measure','required');
            $this->form_validation->set_rules('product_category_id','Product Category','required');
            $this->form_validation->set_rules('cost','Cost','required|numeric');
            // $this->form_validation->set_rules('quantity','Initial Quantity','required|numeric');
            $this->form_validation->set_rules('quantity','Purchase Qty','numeric'); // Removed 'required'
            $this->form_validation->set_rules('product_code','Product Code','required');
    
            if($this->form_validation->run() == FALSE)
            {
                $data['product_categories'] = $this->product_category_model->get_records();
                $data['tax'] = $this->tax_model->get_records();
                $data['uoms'] = $this->uom_model->get_records();
                
                if($this->input->is_ajax_request())
                {
                    $response = array();
                    $response['code'] = 1;
                    $response['add_product_modal_body'] = $this->load->view('product_core/ajax/add_product_modal_body',$data,TRUE);
                    echo json_encode($response);
                }
                else
                {
                    $this->load->view('product/add',$data);
                }
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
                $product_image = $this->input->post('product_image');
                $product_code = $this->input->post('product_code');
                $manage_inventory = $this->input->post('manage_inventory');
                $warehouse_id = $this->input->post('warehouse_id');
                
                // Get quantity from form field
                $quantity = $this->input->post('quantity');
                
                // Ensure quantity is numeric and not empty
                if($quantity === '' || $quantity === NULL) {
                    $quantity = 0;
                } else {
                    $quantity = floatval($quantity);
                }

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
                    "product_image" => $product_image,
                    "product_code" => $product_code,
                    "manage_inventory" => $manage_inventory,
                    "user_id" => $this->session->userdata('user_id')
                );

                // Begin transaction
                $this->db->trans_begin();
                
                if($product_id = $this->product_core_model->add_record($data))
                {
                    $pid = PID_SEQUENCE + $product_id;
                    $this->product_core_model->edit_record(array('pid'=>$pid), $product_id);
                    $product = $this->product_core_model->get_single_record($product_id);

                    // Get UOM details
                    $uom = $this->uom_model->get_single_record($uom_id);

                    // FIXED: Always add quantity to warehouse_products regardless of manage_inventory
                    if($warehouse_id && $warehouse_id != '') {
                        // Add to specific warehouse with the actual quantity
                        $warehouse = $this->warehouse_model->get_single_record($warehouse_id);
                        
                        $warehouse_products = array(
                            "warehouse_id" => $warehouse_id,
                            "product_id" => $product_id,
                            "quantity" => $quantity,  // ← FIXED: Always save the actual quantity
                            "cost" => $cost,
                            "price" => $price,
                            "selling_price" => $selling_price,
                            "batch_no" => $pid
                        );
                        
                        $this->warehouse_products_model->add_record($warehouse_products);
                        
                        // Create stock entry only if quantity > 0 AND manage_inventory is 'no'
                        if($manage_inventory == MANAGE_INVENTORY_NO && $quantity > 0) {
                            $stock_data = array(
                                "product_id" => $product_id,
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
                        // Add to ALL warehouses with the actual quantity
                        $warehouses = $this->warehouse_model->get_records();
                        foreach ($warehouses as $warehouse) {
                            $warehouse_products = array(
                                "warehouse_id" => $warehouse->id,
                                "product_id" => $product_id,
                                "quantity" => $quantity,  // ← FIXED: Always save the actual quantity
                                "cost" => $cost,
                                "price" => $price,
                                "selling_price" => $selling_price,
                                "batch_no" => $pid
                            );
                            $this->warehouse_products_model->add_record($warehouse_products);
                            
                            // Create stock entry for each warehouse
                            if($manage_inventory == MANAGE_INVENTORY_NO && $quantity > 0) {
                                $stock_data = array(
                                    "product_id" => $product_id,
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
                        $response['id'] = $product_id;
                        $response['quantity'] = $quantity;
                        $response['message'] = 'Product added successfully with quantity: ' . $quantity;
                        $response['product'] = $product;
                        
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
            
            $warehouse_id = $this->input->get('warehouse_id');
            $data['selected_warehouse_id'] = $warehouse_id;

            if($this->input->is_ajax_request()) 
            {   
                $response = array();
                $response['code'] = 1;                        
                $response['add_product_modal_body'] = $this->load->view('product_core/ajax/add_product_modal_body',$data,TRUE);
                echo json_encode($response);
            }
            else
            {   
                $this->load->view('product/add',$data);
            }
        }
    }
}

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
                $product_image = $this->input->post('product_image');
                $product_code = $this->input->post('product_code');
                $manage_inventory = $this->input->post('manage_inventory');
                $warehouse_id = $this->input->post('warehouse_id');
                
                // FIXED: Get quantity from form input, NOT from alert_quantity
                $quantity = $this->input->post('quantity');
                if(empty($quantity) || $quantity == '') {
                    $quantity = 0;
                }

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
                    "product_image" => $product_image,
                    "product_code" => $product_code,
                    "manage_inventory" => $manage_inventory,
                    "user_id" => $this->session->userdata('user_id')
                );

                // Begin transaction
                $this->db->trans_begin();
                
                if($id = $this->product_core_model->add_record($data))
                {
                    $pid = PID_SEQUENCE + $id;
                    $this->product_core_model->edit_record(array('pid'=>$pid), $id);
                    $product = $this->product_core_model->get_single_record($id);

                    // Get UOM details
                    $uom = $this->uom_model->get_single_record($uom_id);

                    // Add product to warehouse(s)
                    if($warehouse_id && $warehouse_id != '') {
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
                $response['add_product_modal_body'] = $this->load->view('product_core/ajax/add_product_modal_body',$data,TRUE);
                echo json_encode($response);
            }
            else
            {   
                $this->load->view('product/add',$data);
            }
        }
    }
}*/

  

  public function copy($id = NULL)
	{
		if(!$this->permission_model->has_permission('edit_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				
				$this->form_validation->set_rules('name','Name','required');
				 $this->form_validation->set_rules('hsn','HSN','required|numeric|min_length[4]');		
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
					$product_image 				  = 	$this->input->post('product_image');
          $manage_inventory 		  = 	$this->input->post('manage_inventory');


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
																		"product_image"				=>  $product_image,
                                    "manage_inventory"		=>  $manage_inventory,
																		"user_id"							=>  $this->session->userdata('user_id')
																);

					// being transaction
					$this->db->trans_begin();
					
					if($id = $this->product_core_model->add_record($data))
					{
						$pid = PID_SEQUENCE+$id;

						$this->product_core_model->edit_record(array('pid'=>$pid),$id);

            $product = $this->product_core_model->get_single_record($id);

						// Add products to warehouse with zero quantity
						// $warehouses = $this->warehouse_model->get_records();
						// foreach ($warehouses as $value) {
              if($product->manage_inventory == MANAGE_INVENTORY_NO)
              {
                $warehouse_products = array(
                  "warehouse_id"	=> 0,
                  "product_id"	  => $id,
                  "cost"	        => $product->cost,
                  "price"	        => $product->price,
                  "selling_price"	=> $product->selling_price,
                  "batch_no"	    => $pid
                );
                
                $this->warehouse_products_model->add_record($warehouse_products);
              }
							
						// }

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'product is copied successfully.';
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'product is copied successfully');
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
							$response['message']						= 'product is failed to copy.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'product is failed to copy.');
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
					
					$data['product'] 							= $this->product_core_model->get_single_record($id);
					$data['product_categories'] 	= $this->product_category_model->get_records();
					$data['uoms'] 								= $this->uom_model->get_records();
					$data['tax']	 								= $this->tax_model->get_records();
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['copy_product_modal_body'] = $this->load->view('product_core/ajax/copy_product_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['product'] 							= $this->product_core_model->get_single_record($id);
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

            // SAME VALIDATION AS ADD PRODUCT
            $this->form_validation->set_rules('name','Name','required');        
            $this->form_validation->set_rules('product_category_id','Product Category','required');        
            $this->form_validation->set_rules('uom_id','Unit of Measure','required');    
            $this->form_validation->set_rules('cost','Cost','required|numeric'); // ADD THIS
            $this->form_validation->set_rules('product_code','Product Code','required'); // ADD THIS
          //  $this->form_validation->set_rules('quantity','Quantity','required|numeric'); // ADD THIS
            // HSN is optional now, remove required validation
            // $this->form_validation->set_rules('hsn','HSN','required|numeric|min_length[4]');
        
            if($this->form_validation->run() == FALSE)
            {
                if($this->input->is_ajax_request())
                {
                    $response = array();
                    $response['code'] = 0;
                    $response['message'] = validation_errors();
                    echo json_encode($response);
                    return;
                }
                
                $data['product'] = $this->product_core_model->get_single_record($id);
                $data['product_categories'] = $this->product_category_model->get_records();
                $data['uoms'] = $this->uom_model->get_records();
                $this->load->view('product_core/edit', $data);
            }
            else
            {
                // Get all form data
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
                $product_image = $this->input->post('product_image');
                $product_code = $this->input->post('product_code');
                $manage_inventory = $this->input->post('manage_inventory');
              //  $warehouse_id = $this->input->post('warehouse_id');
                $new_quantity = $this->input->post('quantity');
                //
                // Get existing product and warehouse data
                $existing_product = $this->product_core_model->get_single_record($id);
                
                // Get existing warehouse product
                $existing_warehouse_product = null;
                if($warehouse_id && $warehouse_id != '') {
                    $existing_warehouse_product = $this->db->get_where('warehouse_products', [
                        'product_id' => $id, 
                        'warehouse_id' => $warehouse_id
                    ])->row();
                } else {
                    $existing_warehouse_product = $this->warehouse_products_model->get_single_record($id);
                }
                
                $old_quantity = ($existing_warehouse_product) ? $existing_warehouse_product->quantity : 0;
                $quantity_difference = $new_quantity - $old_quantity;

                // Prepare product data
                $data = array(
                    "name" => $name,
                    "description" => $description,
                    "product_category_id" => $product_category_id,
                    "uom_id" => $uom_id,
                    "hsn" => $hsn,
                    "cost" => $cost,
                    "price" => $price,
                    "selling_price" => $selling_price,
                    "markup" => $markup,
                    "alert_quantity" => $alert_quantity,
                    "product_image" => $product_image,
                    "status" => $status,
                    "product_code" => $product_code,
                );

                if($manage_inventory != '')
                    $data['manage_inventory'] = $manage_inventory;

                // Begin transaction
                $this->db->trans_begin();
                
                // Update product core
                if($this->product_core_model->edit_record($data, $id))
                {
                    // Get UOM details
                    $uom = $this->uom_model->get_single_record($uom_id);
                    
                    // Update warehouse_products
                    if($warehouse_id && $warehouse_id != '') 
                    {
                        // Update specific warehouse
                        $warehouse_product = $this->db->get_where('warehouse_products', [
                            'product_id' => $id, 
                            'warehouse_id' => $warehouse_id
                        ])->row();
                        
                        if($warehouse_product) {
                            $wp_data = array(
                                "quantity" => $new_quantity,
                                "cost" => $cost,
                                "price" => $price,
                                "selling_price" => $selling_price
                            );
                            $this->db->where('id', $warehouse_product->id)->update('warehouse_products', $wp_data);
                        } else {
                            $pid = PID_SEQUENCE + $id;
                            $wp_data = array(
                                "warehouse_id" => $warehouse_id,
                                "product_id" => $id,
                                "quantity" => $new_quantity,
                                "cost" => $cost,
                                "price" => $price,
                                "selling_price" => $selling_price,
                                "batch_no" => $pid
                            );
                            $this->db->insert('warehouse_products', $wp_data);
                        }
                    } 
                    else 
                    {
                        // Update ALL warehouses
                        $warehouse_products = $this->db->get_where('warehouse_products', ['product_id' => $id])->result();
                        foreach($warehouse_products as $wp) {
                            $wp_data = array(
                                "quantity" => $new_quantity,
                                "cost" => $cost,
                                "price" => $price,
                                "selling_price" => $selling_price
                            );
                            $this->db->where('id', $wp->id)->update('warehouse_products', $wp_data);
                        }
                    }
                    
                    // Create stock entry if quantity changed
                    if($manage_inventory == MANAGE_INVENTORY_NO && $quantity_difference != 0) 
                    {
                        $warehouse = ($warehouse_id) ? $this->warehouse_model->get_single_record($warehouse_id) : null;
                        $warehouse_name = ($warehouse) ? $warehouse->name : 'All Warehouses';
                        
                        $stock_entry_type = ($quantity_difference > 0) ? WAREHOUSE_STOCK_IN : WAREHOUSE_STOCK_OUT;
                        
                        $stock_data = array(
                            "product_id" => $id,
                            "warehouse_id" => ($warehouse_id) ? $warehouse_id : 0,
                            "warehouse_name" => $warehouse_name,
                            "product_name" => $name,
                            "quantity" => abs($quantity_difference),
                            "product_price" => $price,
                            "product_cost" => $cost,
                            "selling_price" => $selling_price,
                            "product_uom" => ($uom) ? $uom->uom : '',
                            "entry_type" => $stock_entry_type,
                            "batch_no" => $existing_product->pid,
                            "user_id" => $this->session->userdata('user_id'),
                            "notes" => "Quantity adjusted from " . $old_quantity . " to " . $new_quantity . " via product edit"
                        );
                        $this->stock_model->add_record($stock_data);
                    }

                    // Commit transaction
                    $this->db->trans_commit();

                    $message = 'Product updated successfully.';
                    if($quantity_difference != 0) {
                        $message .= ' Quantity changed from ' . $old_quantity . ' to ' . $new_quantity;
                    }

                    if($this->input->is_ajax_request()) 
                    {
                        $response['code'] = 1;
                        $response['message'] = $message;
                        echo json_encode($response);
                    }
                    else
                    {
                        $this->session->set_flashdata('success', $message);
                        redirect('product', 'refresh');
                    }
                }
                else
                {
                    // Rollback transaction
                    $this->db->trans_rollback();
                    
                    if($this->input->is_ajax_request()) 
                    {
                        $response['code'] = 0;
                        $response['message'] = 'Product failed to update.';
                        echo json_encode($response);
                    }
                    else
                    {
                        $this->session->set_flashdata('failure', 'Product failed to update.');
                        redirect('product', 'refresh');    
                    }
                }
            }
        }
        else
        {
            if($this->input->is_ajax_request()) 
            {
                $id = base64_decode($id);
                
                $data['product'] = $this->product_core_model->get_single_record($id);
                $data['product_categories'] = $this->product_category_model->get_records();
                $data['uoms'] = $this->uom_model->get_records();
                $data['tax'] = $this->tax_model->get_records();
                $data['warehouses'] = $this->warehouse_model->get_records(); // ADD THIS
                
                // Get current warehouse product quantity
                $warehouse_product = $this->warehouse_products_model->get_single_record($id);
                $data['current_quantity'] = ($warehouse_product) ? $warehouse_product->quantity : 0;
                $data['selected_warehouse_id'] = ($warehouse_product) ? $warehouse_product->warehouse_id : '';
                
                $response = array();
                $response['code'] = 1;
                $response['edit_product_modal_body'] = $this->load->view('product_core/ajax/edit_product_modal_body', $data, TRUE);
                echo json_encode($response);
            }
            else
            {
                $id = base64_decode($id);
                $data['product'] = $this->product_core_model->get_single_record($id);
                $data['product_categories'] = $this->product_category_model->get_records();
                $data['uoms'] = $this->uom_model->get_records();
                $data['tax'] = $this->tax_model->get_records();

                if($data['product'] != null)
                {
                    $this->load->view('product/list', $data);
                }
                else
                {
                    $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                    redirect('product/');
                }
            }
        }
    }
}
/*public function edit0505($id = null)
{
    if(!$this->permission_model->has_permission('edit_product'))
    {
        $this->load->view('errors/html/error_restricted'); 
        return;
    }
    
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
        $id = $this->input->post('id');
        
        // Form validation
        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('uom_id','Unit of Measure','required');
        $this->form_validation->set_rules('product_category_id','Product Category','required');
        $this->form_validation->set_rules('product_code','Product Code','required');
        
        if($this->form_validation->run() == FALSE)
        {
            if($this->input->is_ajax_request())
            {
                $response = array();
                $response['code'] = 0;
                $response['message'] = validation_errors();
                echo json_encode($response);
                return;
            }
        }
        
        // Get form data
        $name = $this->input->post('name');
        $description = $this->input->post('description');
        $product_category_id = $this->input->post('product_category_id');
        $uom_id = $this->input->post('uom_id');
        $hsn = $this->input->post('hsn');
        $cost = floatval($this->input->post('cost') ?: 0);
        $price = floatval($this->input->post('price') ?: 0);
        $selling_price = floatval($this->input->post('selling_price') ?: 0);
        $markup = floatval($this->input->post('markup') ?: 0);
        $alert_quantity = floatval($this->input->post('alert_quantity') ?: 1);
        $product_image = $this->input->post('product_image');
        $status = $this->input->post('status') ?: 'active';
        $product_code = $this->input->post('product_code');
        $manage_inventory = $this->input->post('manage_inventory');
        $new_quantity = floatval($this->input->post('opening_quantity') ?: 0); // Changed from 'quantity' to 'opening_quantity'
        
        // Update product data
        $update_data = [
            'name' => $name,
            'description' => $description,
            'product_category_id' => $product_category_id,
            'uom_id' => $uom_id,
            'hsn' => $hsn,
            'cost' => $cost,
            'price' => $price,
            'selling_price' => $selling_price,
            'markup' => $markup,
            'alert_quantity' => $alert_quantity,
            'product_image' => $product_image,
            'status' => $status,
            'product_code' => $product_code,
            'updated_date' => date('Y-m-d H:i:s')
        ];
        
        if($manage_inventory != '')
            $update_data['manage_inventory'] = $manage_inventory;
        
        // Get existing product and its quantity
        $existing_product = $this->product_core_model->get_single_record($id);
        
        // Get old quantity from warehouse_products (if exists)
        $old_quantity = 0;
        $existing_wp = $this->db->select('*')
                                 ->where('product_id', $id)
                                 ->get('warehouse_products')
                                 ->row();
        if($existing_wp) {
            $old_quantity = floatval($existing_wp->quantity);
        }
        
        // Update product table (ALWAYS update)
        $product_updated = $this->product_core_model->edit_record($update_data, $id);
        
        if($product_updated)
        {
            // FIXED: Logic for warehouse_products based on quantity
            if($new_quantity > 0)
            {
                // Quantity is greater than 0 - Update or Insert warehouse_products
                if($existing_wp) {
                    // Update existing warehouse_products record
                    $wp_data = [
                        "quantity" => $new_quantity,
                        "cost" => $cost,
                        "price" => $price,
                        "selling_price" => $selling_price
                    ];
                    $this->db->where('product_id', $id)->update('warehouse_products', $wp_data);
                } else {
                    // Create new warehouse_products record
                    $pid = PID_SEQUENCE + $id;
                    $wp_data = [
                        "warehouse_id" => 1, // Default warehouse, modify as needed
                        "product_id" => $id,
                        "quantity" => $new_quantity,
                        "cost" => $cost,
                        "price" => $price,
                        "selling_price" => $selling_price,
                        "batch_no" => $pid
                    ];
                    $this->db->insert('warehouse_products', $wp_data);
                }
                
                // Create stock entry if quantity changed AND manage_inventory is NO
                $quantity_difference = $new_quantity - $old_quantity;
                if($manage_inventory == MANAGE_INVENTORY_NO && $quantity_difference != 0)
                {
                    $uom = $this->uom_model->get_single_record($uom_id);
                    $stock_entry_type = ($quantity_difference > 0) ? WAREHOUSE_STOCK_IN : WAREHOUSE_STOCK_OUT;
                    
                    $stock_data = [
                        "product_id" => $id,
                        "warehouse_id" => ($existing_wp) ? $existing_wp->warehouse_id : 1,
                        "warehouse_name" => "Default Warehouse",
                        "product_name" => $name,
                        "quantity" => abs($quantity_difference),
                        "product_price" => $price,
                        "product_cost" => $cost,
                        "selling_price" => $selling_price,
                        "product_uom" => ($uom) ? $uom->uom : '',
                        "entry_type" => $stock_entry_type,
                        "batch_no" => $existing_product->pid,
                        "user_id" => $this->session->userdata('user_id'),
                        "notes" => "Quantity adjusted from " . $old_quantity . " to " . $new_quantity . " via product edit",
                        "created_date" => date('Y-m-d H:i:s')
                    ];
                    $this->db->insert('stock', $stock_data);
                }
            }
            else
            {
                // NEW LOGIC: Quantity is 0 or less - DELETE from warehouse_products if exists
                if($existing_wp) {
                    $this->db->where('product_id', $id)->delete('warehouse_products');
                    
                    // Optional: Add a stock adjustment note for removal
                    if($manage_inventory == MANAGE_INVENTORY_NO && $old_quantity > 0)
                    {
                        $uom = $this->uom_model->get_single_record($uom_id);
                        $stock_data = [
                            "product_id" => $id,
                            "warehouse_id" => $existing_wp->warehouse_id,
                            "warehouse_name" => "Default Warehouse",
                            "product_name" => $name,
                            "quantity" => $old_quantity,
                            "product_price" => $price,
                            "product_cost" => $cost,
                            "selling_price" => $selling_price,
                            "product_uom" => ($uom) ? $uom->uom : '',
                            "entry_type" => WAREHOUSE_STOCK_OUT,
                            "batch_no" => $existing_product->pid,
                            "user_id" => $this->session->userdata('user_id'),
                            "notes" => "Stock removed - quantity set to 0",
                            "created_date" => date('Y-m-d H:i:s')
                        ];
                        $this->db->insert('stock', $stock_data);
                    }
                }
            }
            
            // Prepare success message
            $message = 'Product updated successfully.';
            if($new_quantity > 0) {
                $message .= ' Quantity: ' . $new_quantity;
            } else {
                $message .= ' Stock removed (quantity set to 0).';
            }
            
            if($this->input->is_ajax_request())
            {
                $response['code'] = 1;
                $response['message'] = $message;
                echo json_encode($response);
            }
            else
            {
                $this->session->set_flashdata('success', $message);
                redirect('product', 'refresh');
            }
        }
        else
        {
            if($this->input->is_ajax_request()) 
            {
                $response['code'] = 0;
                $response['message'] = 'Product failed to update.';
                echo json_encode($response);
            }
            else
            {
                $this->session->set_flashdata('failure', 'Product failed to update.');
                redirect('product', 'refresh');    
            }
        }
    }
    else // GET request
    {
        if($this->input->is_ajax_request()) 
        {
            $id = base64_decode($id);
            
            // Get product with quantity
            $product = $this->db->select('p.*, COALESCE(wp.quantity, 0) as opening_quantity')
                                ->from('product p')
                                ->join('warehouse_products wp', 'wp.product_id = p.id', 'left')
                                ->where('p.id', $id)
                                ->where('p.delete_status', 0)
                                ->get()
                                ->row();
            
            if(!$product)
            {
                echo json_encode(['code' => 0, 'message' => 'Product not found']);
                return;
            }
            
            // Cache lookup data
            static $product_categories = null;
            static $uoms = null;
            static $tax = null;
            
            if($product_categories === null) {
                $product_categories = $this->product_category_model->get_records();
                $uoms = $this->uom_model->get_records();
                $tax = $this->tax_model->get_records();
            }
            
            $data = [
                'product' => $product,
                'product_categories' => $product_categories,
                'uoms' => $uoms,
                'tax' => $tax,
                'opening_quantity' => $product->opening_quantity
            ];
            
            $response = [
                'code' => 1,
                'edit_product_modal_body' => $this->load->view('product_core/ajax/edit_product_modal_body', $data, TRUE)
            ];
            echo json_encode($response);
        }
        else
        {
            show_404();
        }
    }
}*/
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
				// $markup 							= $this->input->post('markup');
				$alert_quantity 			= $this->input->post('alert_quantity');
				$uom_id 							= $this->input->post('uom_id');
				$status 							= $this->input->post('status');
				$cost 								= $this->input->post('cost');
				$price 								= $this->input->post('price');
				$selling_price 				= $this->input->post('selling_price');
				

				$data = array();

				if($product_category_id != '')
					$data['product_category_id'] = $product_category_id;				

				if($hsn != '')
					$data['hsn'] = $hsn;	
				
				if($cost != '')
					$data['cost'] = $cost;
				
				if($price != '')
					$data['price'] = $price;	
				
				if($selling_price != '')
					$data['selling_price'] = $selling_price;				




				// if($markup != '')
				// 	$data['markup'] = $markup;				

				if($alert_quantity != '')
					$data['alert_quantity'] = $alert_quantity;				

				if($uom_id != '')
					$data['uom_id'] = $uom_id;		

				if($status != '')
					$data['status'] = $status;		

				$product_id_array = explode(",", $product_ids);

				for ($i=0; $i < sizeof($product_id_array); $i++) { 
					$this->product_core_model->edit_record($data,$product_id_array[$i]);			
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
					redirect('product_core','refresh');
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
					$response['bulk_edit_modal_body'] = $this->load->view('product_core/ajax/bulk_edit_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

	// public function import_product()
	// {
	// 	if(!$this->permission_model->has_permission('import_product'))
	// 	{
	// 		$this->load->view('errors/html/error_restricted'); 
	// 	}
	// 	else
	// 	{
	// 		if($this->input->server('REQUEST_METHOD') === 'POST')
	// 		{	
	// 			$product_category_id 	= $this->input->post('product_category_id');
	// 			$uom_id 							= $this->input->post('uom_id');
				
	// 			$filename = $this->upload_model->do_single_upload($_FILES['csvfile']);	
	// 			$file_path = './assets/import/'.$filename;

	// 			$query = sprintf(
	// 												"
	// 													LOAD DATA LOCAL INFILE '%s' INTO TABLE product 
	// 							            FIELDS TERMINATED BY ',' 
	// 							            LINES TERMINATED BY '\r\n'
	// 							            IGNORE 1 LINES (name, description, markup, hsn, alert_quantity);
	// 						           	", 
	// 						          addslashes($file_path));

	// 			if($number = $this->db->query($query))
	// 			{
	// 				$data = array(
	// 												'user_id' 						=> $this->session->userdata('user_id'),
	// 												'uom_id'  						=> $uom_id,
	// 												'product_category_id' => $product_category_id
	// 										);
	// 				$no_imported_products = $this->db->affected_rows();

	// 				if($this->product_core_model->get_records_by_last_import($no_imported_products, $data)){
	// 					$this->session->set_flashdata('success', $no_imported_products.' Products are imported successfully');
	// 					redirect('product','refresh');
	// 				}
	// 				else{
	// 					$this->session->set_flashdata('success', $no_imported_products.' Products are failed to import.');
	// 					redirect('product','refresh');
	// 				}
	// 			}
	// 			else
	// 			{
	// 				$this->session->set_flashdata('success', $no_imported_products.' Products are failed to import.');
	// 				redirect('product','refresh');
	// 			}
	// 		}
	// 		else
	// 		{
	// 			if($this->input->is_ajax_request()) 
	// 			{
	// 				$data['product_categories'] 	= $this->product_category_model->get_records();
	// 				$data['uoms'] 								= $this->uom_model->get_records();
					
	// 		  	$response 									= array();
	// 		  	$response['code']  					= 1;
	// 				$response['import_product_modal_body'] = $this->load->view('product/ajax/import_product_modal_body',$data,TRUE);

	// 		  	echo json_encode($response);
	// 			}
	// 			else
	// 			{
	// 				$this->load->view('errors/html/error_restricted'); 
	// 			}
	// 		}
	// 	}
	// }

	/*public function get_import_product()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') 
		{
		  if (isset($_FILES["csvfile"]))
		  {
        $fileTmpPath = $_FILES["csvfile"]["tmp_name"];
        $fileName = $_FILES["csvfile"]["name"];

				$csvData = array();
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) 
        {
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
          {
              $productNames[] = $data[3];
              $csvRecords[] = $data;
          }
          fclose($handle);
        }
       
     		// Escape the product names to avoid SQL injection
        $escapedProductNames = array_map(array($this->db, "escape_str"), $productNames);
        $inClause = "'" . implode("','", $escapedProductNames) . "'";

        // Fetch existing product names from the database
        $query = $this->db->query(" SELECT p.*, pc.name as product_category_name FROM product p
                             JOIN product_category pc ON p.product_category_id = pc.id
                             WHERE p.name IN ($inClause) AND p.delete_status = 0");
        $existingProducts  = $query->result_array();

         
        // Perform the comparison
        $matchedProducts  = array();
        $unmatchedProducts  = array();

				// Return comparison result as JSON
	       foreach ($productNames as $productName) {
			        foreach ($existingProducts as $product) {
			            if ($product['name'] === $productName) {
			                $matchedProducts[] = $product;
			            }
			        }
			    }

			    header('Content-Type: application/json');
					echo json_encode($matchedProducts);

	    } 
	    else 
	    {
	        echo "Error: No file uploaded.";
	    }
		}
	}*/

	/* import product 2804
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
              $isUpdateProductChecked = $this->input->post('update_product') === '1';
              $isUpdateCreateNewIfExistChecked = $this->input->post('create_product') === '1';

              $product = $this->product_core_model->get_records();
              $product_categories = $this->product_category_model->get_records();

              $expectedHeaders = [
                  'ManageInventory',
                  'PCID',
                  'ProductName',
                  'ProductDescription',
                  'UOM',
                  'Markup',
                  'HSN',
                  'Cost',
                  'Price',
                  'SellingPrice',
              ];

              $isCSVFileValid = is_csv_valid(
                  $_FILES["csvfile"]["tmp_name"],
                  ',',
                  $expectedHeaders
              );

              $file_path = $_FILES["csvfile"]["tmp_name"];
              $file = fopen($file_path, 'r');

              $header_row = fgetcsv($file, 0, $delimiter= ",");

              if ($isCSVFileValid !== true)
              {
                  $this->session->set_flashdata('failure', $isCSVFileValid);
                  redirect('product_core','refresh');
              }
              else
              {
                  if(is_uploaded_file($_FILES['csvfile']['tmp_name']))
                  {
                      // Parse data from CSV file
                      $csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);

                      // Initialize the counter
                      $total_product_imported = 0;

                      // Insert/update CSV data into database
                      if(!empty($csvData))
                      {
                          $product_with_unknown_pcid = array();
                          foreach($csvData as $row)
                          { 
                              $matching_category = null;

                              foreach ($product_categories as $category) {
                                  if ($row['PCID'] == $category->pcid) {
                                      $matching_category = $category;
                                      break;
                                  }
                              }

                              if ($matching_category !== null) {

                                  // Prepare data for DB insertion
                                  $uom = $this->utility_model->get_records_by_field('uom','uom',$row['UOM']);
                                  $product_category = $this->utility_model->get_records_by_field('product_category','pcid',$row['PCID']);

                                  $product_data = array(
                                      'manage_inventory' => $row['ManageInventory'],
                                      'product_category_id' => $product_category->id,
                                      'name' => $row['ProductName'],
                                      'description' => $row['ProductDescription'],
                                      'uom_id' => $uom->id,
                                      'markup' => $row['Markup'],
                                      'hsn' => $row['HSN'],
                                      'cost' => $row['Cost'],
                                      'price' => $row['Price'],
                                      'selling_price' => $row['SellingPrice']
                                  );

                                  // Check whether product already exists in the database
                                  $exist_products = $this->utility_model->get_records_by_field('product','name',$row['ProductName'],false,true);

                                  if($exist_products == null || $isUpdateCreateNewIfExistChecked)
                                  {   
                                      $this->db->trans_begin();
                                      $product_id = $this->product_model->add_record($product_data);
                                      $pid = PID_SEQUENCE + $product_id;
                                      $this->product_core_model->edit_record(array("pid"=>$pid),$product_id);
                                      
                                      $product = $this->product_core_model->get_single_record($product_id);

                                      // Add products to warehouse with zero quantity if manage_inventory is no
                                      if($product->manage_inventory == MANAGE_INVENTORY_NO)
                                      {
                                          $warehouse_products = array(
                                              "warehouse_id" => 0,
                                              "product_id" => $product_id,
                                              "cost" => $product->cost,
                                              "price" => $product->price,
                                              "selling_price" => $product->selling_price,
                                              "batch_no" => $pid
                                          );
                                          
                                          $this->warehouse_products_model->add_record($warehouse_products);
                                      }

                                      $this->db->trans_commit();
                                      $total_product_imported++;
                                  }
                                  else
                                  {
                                      if($isUpdateProductChecked)
                                      {
                                          foreach ($exist_products as $value) {
                                              $this->product_core_model->edit_record($product_data,$value->id);    
                                          }
                                      }
                                  }
                              } 
                              else
                              {
                                  $product_with_unknown_pcid[] = $row['ProductName'];
                              }
                          }

                          $success_message = 'Products imported successfully.';

                          if(sizeof($product_with_unknown_pcid) > 0)
                              $success_message .= ' And '.implode(",",$product_with_unknown_pcid).' is failed to update or add because of unknown of PCID value';

                          $this->session->set_flashdata('success', $success_message);
                          redirect('product_core','refresh');

                      }
                  }
                  else
                  {
                      $this->session->set_flashdata('failure', 'Failed to import products.');
                      redirect('product_core','refresh');
                  }
              }
          }
          else
          {
              if($this->input->is_ajax_request()) 
              {
                  $data['product_categories'] = $this->product_category_model->get_records();
                  $data['uoms'] = $this->uom_model->get_records();
                  $data['suppliers'] = $this->supplier_model->get_records();

                  $response = array();
                  $response['code'] = 1;
                  $response['import_product_modal_body'] = $this->load->view('product_core/ajax/import_product_modal_body',$data,TRUE);

                  echo json_encode($response);
              }
              else
              {
                  $this->load->view('errors/html/error_restricted'); 
              }
          }
      }
  }*/
/*public function get_import_product()
{
    if ($this->input->server('REQUEST_METHOD') === 'POST') 
    {
        if (isset($_FILES["csvfile"]))
        {
            $fileTmpPath = $_FILES["csvfile"]["tmp_name"];
            $fileName = $_FILES["csvfile"]["name"];

            $csvData = array();
            if (($handle = fopen($fileTmpPath, "r")) !== FALSE) 
            {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
                {
                    $productNames[] = $data[3]; // Assuming ProductName is at index 3
                    $csvRecords[] = $data;
                }
                fclose($handle);
            }
           
            // Escape the product names to avoid SQL injection
            $escapedProductNames = array_map(array($this->db, "escape_str"), $productNames);
            $inClause = "'" . implode("','", $escapedProductNames) . "'";

            // UPDATED: Fetch existing products with all fields
            $query = $this->db->query(" SELECT p.*, pc.name as product_category_name, pc.pci_id
                                        FROM product p
                                        JOIN product_category pc ON p.product_category_id = pc.id
                                        WHERE p.name IN ($inClause) AND p.delete_status = 0");
            $existingProducts = $query->result_array();
            
            // Perform the comparison
            $matchedProducts = array();
            $unmatchedProducts = array();

            foreach ($productNames as $productName) {
                foreach ($existingProducts as $product) {
                    if ($product['name'] === $productName) {
                        $matchedProducts[] = $product;
                    }
                }
            }

            header('Content-Type: application/json');
            echo json_encode($matchedProducts);
        } 
        else 
        {
            echo "Error: No file uploaded.";
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
            $isUpdateProductChecked = $this->input->post('update_product') === '1';
            $isUpdateCreateNewIfExistChecked = $this->input->post('create_product') === '1';
            $warehouse_id = $this->input->post('warehouse_id'); // Get warehouse from form if needed

            $product_categories = $this->product_category_model->get_records();

            // UPDATED: New expected headers with all fields
            $expectedHeaders = [
                'ManageInventory',
                'ProductCode',        // NEW
                'ProductName',
                'ProductDescription',
                'ProductCategoryPCID', // Changed from PCID
                'HSN',
                'Cost',
                'SellingPrice',
                'Price',
                'Markup',
                'InitialQuantity',    // NEW
                'AlertQuantity',
                'UOM',
                'Status'              // NEW
            ];

            // Validate CSV file
            $isCSVFileValid = is_csv_valid(
                $_FILES["csvfile"]["tmp_name"],
                ',',
                $expectedHeaders
            );

            if ($isCSVFileValid !== true)
            {
                $this->session->set_flashdata('failure', $isCSVFileValid);
                redirect('product_core','refresh');
            }
            else
            {
                if(is_uploaded_file($_FILES['csvfile']['tmp_name']))
                {
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);
                    $total_product_imported = 0;
                    $product_with_unknown_pcid = array();

                    if(!empty($csvData))
                    {
                        foreach($csvData as $row)
                        { 
                            $matching_category = null;

                            foreach ($product_categories as $category) {
                                if ($row['ProductCategoryPCID'] == $category->pci_id) {
                                    $matching_category = $category;
                                    break;
                                }
                            }

                            if ($matching_category !== null) {
                                // Get UOM record
                                $uom = $this->utility_model->get_records_by_field('uom','uom',$row['UOM']);
                                
                                // Map status
                                $status = ($row['Status'] == 'active') ? PRODUCT_STATUS_ACTIVE : PRODUCT_STATUS_INACTIVE;
                                
                                // Map manage inventory
                                $manage_inventory = ($row['ManageInventory'] == 'yes') ? MANAGE_INVENTORY_YES : MANAGE_INVENTORY_NO;
                                
                                // Get initial quantity
                                $initial_quantity = isset($row['InitialQuantity']) ? floatval($row['InitialQuantity']) : 0;

                                // Prepare product data
                                $product_data = array(
                                    'manage_inventory' => $manage_inventory,
                                    'product_category_id' => $matching_category->id,
                                    'name' => $row['ProductName'],
                                    'description' => $row['ProductDescription'],
                                    'uom_id' => $uom->id,
                                    'markup' => $row['Markup'],
                                    'hsn' => $row['HSN'],
                                    'cost' => $row['Cost'],
                                    'price' => $row['Price'],
                                    'selling_price' => $row['SellingPrice'],
                                    'product_code' => $row['ProductCode'], // NEW
                                    'alert_quantity' => $row['AlertQuantity'],
                                    'status' => $status,
                                    'user_id' => $this->session->userdata('user_id')
                                );

                                // Check whether product already exists
                                $exist_products = $this->utility_model->get_records_by_field('product','name',$row['ProductName'],false,true);

                                if($exist_products == null || $isUpdateCreateNewIfExistChecked)
                                {   
                                    $this->db->trans_begin();
                                    $product_id = $this->product_model->add_record($product_data);
                                    $pid = PID_SEQUENCE + $product_id;
                                    $this->product_core_model->edit_record(array("pid"=>$pid), $product_id);
                                    
                                    $product = $this->product_core_model->get_single_record($product_id);

                                    // Add products to warehouse with initial quantity
                                    if($product->manage_inventory == MANAGE_INVENTORY_NO)
                                    {
                                        // Use specified warehouse or default (0)
                                        $target_warehouse_id = ($warehouse_id && $warehouse_id != '') ? $warehouse_id : 0;
                                        
                                        $warehouse_products = array(
                                            "warehouse_id" => $target_warehouse_id,
                                            "product_id" => $product_id,
                                            "quantity" => $initial_quantity, // Use InitialQuantity from CSV
                                            "cost" => $product->cost,
                                            "price" => $product->price,
                                            "selling_price" => $product->selling_price,
                                            "batch_no" => $pid
                                        );
                                        
                                        $this->warehouse_products_model->add_record($warehouse_products);
                                        
                                        // Create stock entry if quantity > 0
                                        if($initial_quantity > 0) {
                                            $warehouse = $this->warehouse_model->get_single_record($target_warehouse_id);
                                            $uom_details = $this->uom_model->get_single_record($uom->id);
                                            
                                            $stock_data = array(
                                                "product_id" => $product_id,
                                                "warehouse_id" => $target_warehouse_id,
                                                "warehouse_name" => ($warehouse ? $warehouse->name : 'Default'),
                                                "product_name" => $row['ProductName'],
                                                "quantity" => $initial_quantity,
                                                "product_price" => $row['Price'],
                                                "product_cost" => $row['Cost'],
                                                "selling_price" => $row['SellingPrice'],
                                                "product_uom" => ($uom_details ? $uom_details->uom : ''),
                                                "entry_type" => WAREHOUSE_STOCK_IN,
                                                "batch_no" => $pid,
                                                "user_id" => $this->session->userdata('user_id')
                                            );
                                            $this->stock_model->add_record($stock_data);
                                        }
                                    }

                                    $this->db->trans_commit();
                                    $total_product_imported++;
                                }
                                else
                                {
                                    if($isUpdateProductChecked)
                                    {
                                        foreach ($exist_products as $value) {
                                            $this->product_core_model->edit_record($product_data, $value->id);    
                                        }
                                    }
                                }
                            } 
                            else
                            {
                                $product_with_unknown_pcid[] = $row['ProductName'];
                            }
                        }

                        $success_message = $total_product_imported . ' Products imported successfully.';
                        if(sizeof($product_with_unknown_pcid) > 0)
                            $success_message .= ' And ' . implode(",", $product_with_unknown_pcid) . ' failed due to unknown PCID value';

                        $this->session->set_flashdata('success', $success_message);
                        redirect('product_core','refresh');
                    }
                }
                else
                {
                    $this->session->set_flashdata('failure', 'Failed to import products.');
                    redirect('product_core','refresh');
                }
            }
        }
        else
        {
            if($this->input->is_ajax_request()) 
            {
                $data['product_categories'] = $this->product_category_model->get_records();
                $data['uoms'] = $this->uom_model->get_records();
                $data['suppliers'] = $this->supplier_model->get_records();
                $data['warehouses'] = $this->warehouse_model->get_records(); // Add warehouses for selection

                $response = array();
                $response['code'] = 1;
                $response['import_product_modal_body'] = $this->load->view('product_core/ajax/import_product_modal_body', $data, TRUE);
                echo json_encode($response);
            }
            else
            {
                $this->load->view('errors/html/error_restricted'); 
            }
        }
    }
}*/

public function get_import_product()
{
    if ($this->input->server('REQUEST_METHOD') === 'POST') 
    {
        if (isset($_FILES["csvfile"]))
        {
            $fileTmpPath = $_FILES["csvfile"]["tmp_name"];
            
            $productNames = array();
            if (($handle = fopen($fileTmpPath, "r")) !== FALSE) 
            {
                $header = fgetcsv($handle, 1000, ",");
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
                {
                    $row = array_combine($header, $data);
                    if(isset($row['ProductName'])) {
                        $productNames[] = $row['ProductName'];
                    }
                }
                fclose($handle);
            }
           
            if(!empty($productNames)) {
                $escapedProductNames = array_map(array($this->db, "escape_str"), $productNames);
                $inClause = "'" . implode("','", $escapedProductNames) . "'";

                // FIXED: Use pc.pcid instead of pc.pci_id
                $query = $this->db->query(" SELECT p.*, pc.name as product_category_name, pc.pcid 
                                            FROM product p
                                            JOIN product_category pc ON p.product_category_id = pc.id
                                            WHERE p.name IN ($inClause) AND p.delete_status = 0");
                $existingProducts = $query->result_array();
                
                header('Content-Type: application/json');
                echo json_encode($existingProducts);
            } else {
                echo json_encode([]);
            }
        } 
        else 
        {
            echo json_encode(["error" => "No file uploaded."]);
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
            $isUpdateProductChecked = $this->input->post('update_product') === '1';
            $isUpdateCreateNewIfExistChecked = $this->input->post('create_product') === '1';
            $warehouse_id = $this->input->post('warehouse_id');

            $product_categories = $this->product_category_model->get_records();

            $expectedHeaders = [
                'ManageInventory',
                'ProductCode',
                'ProductName',
                'ProductDescription',
                'ProductCategoryPCID',
                'HSN',
                'Cost',
                'SellingPrice',
                'Price',
                'Markup',
                'InitialQuantity',
                'AlertQuantity',
                'UOM',
                'Status'
            ];

            $isCSVFileValid = is_csv_valid(
                $_FILES["csvfile"]["tmp_name"],
                ',',
                $expectedHeaders
            );

            if ($isCSVFileValid !== true)
            {
                $this->session->set_flashdata('failure', $isCSVFileValid);
                redirect('product_core','refresh');
            }
            else
            {
                if(is_uploaded_file($_FILES['csvfile']['tmp_name']))
                {
                    $csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);
                    $total_product_imported = 0;
                    $product_with_unknown_pcid = array();
                    $product_with_unknown_uom = array();

                    if(!empty($csvData))
                    {
                        foreach($csvData as $row)
                        { 
                            $matching_category = null;

                            // FIXED: Use pcid (not pci_id)
                            foreach ($product_categories as $category) {
                                if ((string)$row['ProductCategoryPCID'] == (string)$category->pcid) {
                                    $matching_category = $category;
                                    break;
                                }
                            }

                            if ($matching_category !== null) {
                                // Get UOM record - try by uom code first
                                $uom = $this->db->select('*')
                                               ->from('uom')
                                               ->where('UOM', $row['UOM'])
                                               ->get()
                                               ->row();
                                
                                // If not found, try by name
                                if(!$uom) {
                                    $uom = $this->db->select('*')
                                                   ->from('uom')
                                                   ->where('name', $row['UOM'])
                                                   ->get()
                                                   ->row();
                                }
                                
                                if(!$uom) {
                                    $product_with_unknown_uom[] = $row['ProductName'] . " (UOM: " . $row['UOM'] . ")";
                                    continue;
                                }
                                
                                $status = (strtolower(trim($row['Status'])) == 'active') ? 'active' : 'inactive';
                                $manage_inventory = (strtolower(trim($row['ManageInventory'])) == 'yes') ? 'no' : 'yes';
                                $initial_quantity = isset($row['InitialQuantity']) ? floatval($row['InitialQuantity']) : 0;

                              $product_data = array(
    'manage_inventory' => $manage_inventory,
    'product_category_id' => $matching_category->id,
    'name' => $row['ProductName'],
    'description' => $row['ProductDescription'],
    'uom_id' => $uom->id,
    'markup' => $row['Markup'],
    'hsn' => $row['HSN'],
    'cost' => $row['Cost'],
    'price' => $row['Price'],
    'selling_price' => $row['SellingPrice'],
    'product_code' => $row['ProductCode'],
    'alert_quantity' => $row['AlertQuantity'],  // FIXED: Use alert_quantity
    'quantity' => $initial_quantity,
    'status' => $status,
    'user_id' => $this->session->userdata('user_id'),
    'created_date' => date('Y-m-d H:i:s')
);

                                // Check if product already exists
                                $exist_products = $this->db->select('*')
                                                         ->from('product')
                                                         ->where('name', $row['ProductName'])
                                                         ->where('delete_status', 0)
                                                         ->get()
                                                         ->result();

                                if(empty($exist_products) || $isUpdateCreateNewIfExistChecked)
                                {   
                                    $this->db->trans_begin();
                                    $this->db->insert('product', $product_data);
                                    $product_id = $this->db->insert_id();
                                    
                                    $pid = PID_SEQUENCE + $product_id;
                                    $this->db->where('id', $product_id)->update('product', array('pid' => $pid));
                                    
                                    if($manage_inventory == 'no')
                                    {
                                        $target_warehouse_id = ($warehouse_id && $warehouse_id != '') ? $warehouse_id : 1;
                                        
                                        $warehouse_products_data = array(
                                            "warehouse_id" => $target_warehouse_id,
                                            "product_id" => $product_id,
                                            "quantity" => $initial_quantity,
                                            "cost" => $row['Cost'],
                                            "price" => $row['Price'],
                                            "selling_price" => $row['SellingPrice'],
                                            "batch_no" => $pid
                                        );
                                        $this->db->insert('warehouse_products', $warehouse_products_data);
                                    }

                                    $this->db->trans_commit();
                                    $total_product_imported++;
                                }
                                elseif($isUpdateProductChecked && !empty($exist_products))
                                {
                                    foreach ($exist_products as $value) {
                                        $this->db->where('id', $value->id)->update('product', $product_data);
                                    }
                                    $total_product_imported++;
                                }
                            } 
                            else
                            {
                                $product_with_unknown_pcid[] = $row['ProductName'] . " (PCID: " . $row['ProductCategoryPCID'] . ")";
                            }
                        }

                        $success_message = $total_product_imported . ' Products imported successfully.';
                        if(!empty($product_with_unknown_pcid)) {
                            $success_message .= ' Failed (Invalid PCID): ' . implode(", ", $product_with_unknown_pcid);
                        }
                        if(!empty($product_with_unknown_uom)) {
                            $success_message .= ' Failed (Invalid UOM): ' . implode(", ", $product_with_unknown_uom);
                        }

                        $this->session->set_flashdata('success', $success_message);
                        redirect('product_core','refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata('failure', 'No data found in CSV file.');
                        redirect('product_core','refresh');
                    }
                }
                else
                {
                    $this->session->set_flashdata('failure', 'Failed to upload file.');
                    redirect('product_core','refresh');
                }
            }
        }
        else
        {
            if($this->input->is_ajax_request()) 
            {
                $data['product_categories'] = $this->product_category_model->get_records();
                $data['uoms'] = $this->uom_model->get_records();
                $data['suppliers'] = $this->supplier_model->get_records();
                $data['warehouses'] = $this->warehouse_model->get_records();

                $response = array();
                $response['code'] = 1;
                $response['import_product_modal_body'] = $this->load->view('product_core/ajax/import_product_modal_body', $data, TRUE);
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

			$product =  $this->product_core_model->get_single_record($id);

			if($this->product_core_model->edit_record($data,$id))
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
  	$data['product']		= $this->product_core_model->get_single_record($product_id);

  	$response 																		= array();
  	$response['delete_product_modal_body'] 	= $this->load->view('product_core/ajax/delete_product_modal_body',$data,TRUE);

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

	function view($id)
  {
		$id 												= base64_decode($id);
		$data['product']  					=	$this->product_core_model->get_single_record($id);
    
    $data['warehouse_products'] = $this->warehouse_products_model->get_records_by_product_id($id);
		$data['product_categories'] = $this->product_category_model->get_records();
		$data['uoms'] 							= $this->uom_model->get_records();
		$data['suppliers']					= $this->supplier_model->get_records();

		$this->load->view('product_core/view',$data);
  }

	public function upload_image()
	{
	    if (isset($_FILES["upload_image"])) 
	    {
        $targetDir = "./assets/product_images/"; // Replace with your desired folder path

        // Create the target folder if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // The third parameter creates nested directories if needed
        }

        $uploadFilename = basename($_FILES["upload_image"]["name"]);

        // Check the file extension
       /* $allowedExtensions = array("jpg", "jpeg", "png");

        $fileExtension = strtolower(pathinfo($uploadFilename, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            $response = array("error" => "Only JPG, JPEG, and PNG files are allowed.");
            echo json_encode($response);
            return;
        }*/

        // Generate the microsecond timestamp
        $microtime = microtime(true);
        $microsecondPrefix = str_replace(".", "_", $microtime);

        // Append the microsecond timestamp as a prefix to the filename
        $newFilename = $microsecondPrefix . "_" . $uploadFilename;

        $targetFile = $targetDir . $newFilename;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $targetFile)) 
        {
            $response = array();
            $response['filename'] = $newFilename;
            echo json_encode($response);
        } 
        else 
        {
            $response = array("error" => "Error uploading image.");
            echo json_encode($response);
        }
    } 
    else 
    {
        echo json_encode(array("error" => "No image file provided."));
    }
	}

	public function delete_uploaded_image()
	{
		if (isset($_POST["filename"])) {
		    $filename = $_POST["filename"];
		    $imagePath = "./assets/product_images/" . $filename;

		    
		    if (file_exists($imagePath)) 
		    {
	        // Delete the image file
	        if (unlink($imagePath)) 
	        {
            echo json_encode(array("success" => true));
            exit;
	        }
		    }
		}
	}
/*0205 with sudhakar sir discussion*/
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
          $cost 									= 	$this->input->post('cost');
          $price 									= 	$this->input->post('price');
          $selling_price 					= 	$this->input->post('selling_price');
					$markup 								= 	$this->input->post('markup');
					$alert_quantity 				= 	$this->input->post('alert_quantity');
					$uom_id 								= 	$this->input->post('uom_id');
					$status 								= 	$this->input->post('status');
					$product_image 				  = 	$this->input->post('product_image');
					$product_code							= 	$this->input->post('product_code');
          $manage_inventory 		  = 	$this->input->post('manage_inventory');


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
																		"product_image"				=>  $product_image,
																		"product_code"	=>  $product_code,
                                    "manage_inventory"		=>  $manage_inventory,
																		"user_id"							=>  $this->session->userdata('user_id')
																);

					// being transaction
					$this->db->trans_begin();
					
					if($id = $this->product_core_model->add_record($data))
					{
						$pid = PID_SEQUENCE+$id;

				    $this->product_core_model->edit_record(array('pid'=>$pid),$id);

            $product = $this->product_core_model->get_single_record($id);

						// Add products to warehouse with zero quantity
						// $warehouses = $this->warehouse_model->get_records();
						// foreach ($warehouses as $value) {
              if($product->manage_inventory == MANAGE_INVENTORY_NO)
              {
                $warehouse_products = array(
                  "warehouse_id"	=> 0,
                  "product_id"	  => $id,
                  "cost"	        => $product->cost,
                  "price"	        => $product->price,
                  "selling_price"	=> $product->selling_price,
                  "batch_no"	    => $pid
                );
                
                $this->warehouse_products_model->add_record($warehouse_products);
              }		
							
						// }

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
			  	$response['add_product_modal_body'] 	= $this->load->view('product_core/ajax/add_product_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{	
					$this->load->view('product/add',$data);
				}
			}
		}
	}
	
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
            // Form Validation
            $this->form_validation->set_rules('name','Name','required');
            $this->form_validation->set_rules('uom_id','Unit of Measure','required');
            $this->form_validation->set_rules('product_category_id','Product Category','required');
            $this->form_validation->set_rules('product_code','Product Code','required');
    
            if($this->form_validation->run() == FALSE)
            {
                if($this->input->is_ajax_request())
                {
                    $response = array();
                    $response['code'] = 0;
                    $response['message'] = validation_errors();
                    echo json_encode($response);
                }
                else
                {
                    $data['product_categories'] = $this->product_category_model->get_records();
                    $data['uoms'] = $this->uom_model->get_records();
                    $this->load->view('product/add',$data);
                }
                return;
            }
            
            // Get form data
            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $product_category_id = $this->input->post('product_category_id');
            $hsn = $this->input->post('hsn');
            
            $cost = floatval($this->input->post('cost') ?: 0);
            $price = floatval($this->input->post('price') ?: 0);
            $selling_price = floatval($this->input->post('selling_price') ?: 0);
            $markup = floatval($this->input->post('markup') ?: 0);
            $alert_quantity = floatval($this->input->post('alert_quantity') ?: 1);
            $opening_quantity = floatval($this->input->post('opening_quantity') ?: 0); // Changed from 'quantity' to 'opening_quantity'
            
            $uom_id = $this->input->post('uom_id');
            $status = $this->input->post('status') ?: PRODUCT_STATUS_ACTIVE;
            $product_image = $this->input->post('product_image');
            $product_code = $this->input->post('product_code');
            $manage_inventory = $this->input->post('manage_inventory') ?: MANAGE_INVENTORY_NO;
            $warehouse_id = $this->input->post('warehouse_id');

            // Prepare product data
            $product_data = array(
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
                "product_image" => $product_image,
                "product_code" => $product_code,
                "manage_inventory" => $manage_inventory,
                "user_id" => $this->session->userdata('user_id'),
                "created_date" => date('Y-m-d H:i:s')
            );

            // Insert product into product table
            $product_id = $this->product_core_model->add_record($product_data);
            
            if($product_id)
            {
                $pid = PID_SEQUENCE + $product_id;
                $this->product_core_model->edit_record(array('pid' => $pid), $product_id);
                
                // FIXED: Only save in warehouse_products table if quantity is GREATER THAN 0
                if($opening_quantity > 0)
                {
                    // Determine warehouse(s) to add product to
                    $warehouses_to_add = array();
                    if($warehouse_id && $warehouse_id != '') {
                        $warehouses_to_add[] = $warehouse_id;
                    } else {
                        $all_warehouses = $this->warehouse_model->get_records();
                        foreach($all_warehouses as $wh) {
                            $warehouses_to_add[] = $wh->id;
                        }
                    }
                    
                    // Insert into warehouse_products
                    foreach($warehouses_to_add as $wh_id) {
                        $warehouse_products_data = array(
                            "warehouse_id" => $wh_id,
                            "product_id" => $product_id,
                            "quantity" => $opening_quantity,
                            "cost" => $cost,
                            "price" => $price,
                            "selling_price" => $selling_price,
                            "batch_no" => $pid
                        );
                        $this->db->insert('warehouse_products', $warehouse_products_data);
                    }
                    
                    // Create stock entry only if manage_inventory is NO
                    if($manage_inventory == MANAGE_INVENTORY_NO)
                    {
                        $first_warehouse_id = $warehouses_to_add[0];
                        $warehouse = $this->warehouse_model->get_single_record($first_warehouse_id);
                        $uom = $this->uom_model->get_single_record($uom_id);
                        
                        $stock_data = array(
                            "product_id" => $product_id,
                            "warehouse_id" => $first_warehouse_id,
                            "warehouse_name" => $warehouse ? $warehouse->name : 'Default',
                            "product_name" => $name,
                            "quantity" => $opening_quantity,
                            "product_price" => $price,
                            "product_cost" => $cost,
                            "selling_price" => $selling_price,
                            "product_uom" => $uom ? $uom->uom : '',
                            "entry_type" => WAREHOUSE_STOCK_IN,
                            "batch_no" => $pid,
                            "notes" => "Opening stock",
                            "user_id" => $this->session->userdata('user_id'),
                            "created_date" => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('stock', $stock_data);
                    }
                }
                // ELSE: quantity is 0 or less - ONLY product table is saved, no warehouse_products entry

                if($this->input->is_ajax_request())
                {   
                    $response = array();
                    $response['code'] = 1;
                    $response['id'] = $product_id;
                    $response['quantity'] = $opening_quantity;
                    $response['message'] = $opening_quantity > 0 ? 'Product added successfully with quantity: ' . $opening_quantity : 'Product added successfully (No stock)';
                    echo json_encode($response);
                }
                else
                {
                    $message = $opening_quantity > 0 ? 'Product added successfully with quantity: ' . $opening_quantity : 'Product added successfully (No stock)';
                    $this->session->set_flashdata('success', $message);
                    redirect('product','refresh');
                }
            }
            else
            {
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
        else
        {
            $data = array();
            $data['product_categories'] = $this->product_category_model->get_records();
            $data['uoms'] = $this->uom_model->get_records();
            $data['tax'] = $this->tax_model->get_records();
            
            $warehouse_id = $this->input->get('warehouse_id');
            $data['selected_warehouse_id'] = $warehouse_id;

            if($this->input->is_ajax_request()) 
            {   
                $response = array();
                $response['code'] = 1;                        
                $response['add_product_modal_body'] = $this->load->view('product_core/ajax/add_product_modal_body',$data,TRUE);
                echo json_encode($response);
            }
            else
            {   
                $this->load->view('product/add',$data);
            }
        }
    }
}
	
	public function add0505($id = NULL)
{
    if(!$this->permission_model->has_permission('add_product'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            // Simplified validation - only required fields
            $this->form_validation->set_rules('name','Name','required');
            $this->form_validation->set_rules('uom_id','Unit of Measure','required');
            $this->form_validation->set_rules('product_category_id','Product Category','required');
            $this->form_validation->set_rules('product_code','Product Code','required');
    
            if($this->form_validation->run() == FALSE)
            {
                if($this->input->is_ajax_request())
                {
                    $response = array();
                    $response['code'] = 0;
                    $response['message'] = validation_errors();
                    echo json_encode($response);
                }
                else
                {
                    $data['product_categories'] = $this->product_category_model->get_records();
                    $data['uoms'] = $this->uom_model->get_records();
                    $this->load->view('product/add',$data);
                }
                return;
            }
            
            // Get and sanitize form data
            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $product_category_id = $this->input->post('product_category_id');
            $hsn = $this->input->post('hsn');
            
            // Set default values for empty price fields (convert empty to 0)
            $cost = floatval($this->input->post('cost') ?: 0);
            $price = floatval($this->input->post('price') ?: 0);
            $selling_price = floatval($this->input->post('selling_price') ?: 0);
            $markup = floatval($this->input->post('markup') ?: 0);
            $alert_quantity = floatval($this->input->post('alert_quantity') ?: 1);
            $opening_quantity = floatval($this->input->post('opening_quantity') ?: 0);
            
            $uom_id = $this->input->post('uom_id');
            $status = $this->input->post('status') ?: PRODUCT_STATUS_ACTIVE;
            $product_image = $this->input->post('product_image');
            $product_code = $this->input->post('product_code');
            $manage_inventory = $this->input->post('manage_inventory') ?: MANAGE_INVENTORY_NO;
            $warehouse_id = $this->input->post('warehouse_id');

            // Prepare product data
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
                "product_image" => $product_image,
                "product_code" => $product_code,
                "manage_inventory" => $manage_inventory,
                "user_id" => $this->session->userdata('user_id'),
                "created_date" => date('Y-m-d H:i:s')
            );

            // Insert product directly - no transaction needed for single insert
            $product_id = $this->product_core_model->add_record($data);
            
            if($product_id)
            {
                $pid = PID_SEQUENCE + $product_id;
                $this->product_core_model->edit_record(array('pid' => $pid), $product_id);
                
                // Determine warehouse(s) to add product to
                $warehouses_to_add = array();
                if($warehouse_id && $warehouse_id != '') {
                    $warehouses_to_add[] = $warehouse_id;
                } else {
                    $all_warehouses = $this->warehouse_model->get_records();
                    foreach($all_warehouses as $wh) {
                        $warehouses_to_add[] = $wh->id;
                    }
                }
                
                // Quick insert into warehouse_products (only if opening_quantity > 0 OR prices are set)
                $wp_inserted = false;
                foreach($warehouses_to_add as $wh_id) {
                    $warehouse_products = array(
                        "warehouse_id" => $wh_id,
                        "product_id" => $product_id,
                        "quantity" => $opening_quantity,
                        "cost" => $cost,
                        "price" => $price,
                        "selling_price" => $selling_price,
                        "batch_no" => $pid
                    );
                    $this->db->insert('warehouse_products', $warehouse_products);
                    $wp_inserted = true;
                }
                
                // Create stock entry only if opening_quantity > 0 AND manage_inventory is NO
                if($wp_inserted && $opening_quantity > 0 && $manage_inventory == MANAGE_INVENTORY_NO)
                {
                    // Get first warehouse for stock entry
                    $first_warehouse_id = $warehouses_to_add[0];
                    $warehouse = $this->warehouse_model->get_single_record($first_warehouse_id);
                    $uom = $this->uom_model->get_single_record($uom_id);
                    
                    $stock_data = array(
                        "product_id" => $product_id,
                        "warehouse_id" => $first_warehouse_id,
                        "warehouse_name" => $warehouse ? $warehouse->name : 'Default',
                        "product_name" => $name,
                        "quantity" => $opening_quantity,
                        "product_price" => $price,
                        "product_cost" => $cost,
                        "selling_price" => $selling_price,
                        "product_uom" => $uom ? $uom->uom : '',
                        "entry_type" => WAREHOUSE_STOCK_IN,
                        "batch_no" => $pid,
                        "notes" => "Opening stock",
                        "user_id" => $this->session->userdata('user_id'),
                        "created_date" => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('stock', $stock_data);
                }

                if($this->input->is_ajax_request())
                {   
                    $response = array();
                    $response['code'] = 1;
                    $response['id'] = $product_id;
                    $response['message'] = 'Product added successfully';
                    echo json_encode($response);
                }
                else
                {
                    $this->session->set_flashdata('success', 'Product added successfully');
                    redirect('product','refresh');
                }
            }
            else
            {
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
        else
        {
            $data = array();
            $data['product_categories'] = $this->product_category_model->get_records();
            $data['uoms'] = $this->uom_model->get_records();
            $data['tax'] = $this->tax_model->get_records();
            
            $warehouse_id = $this->input->get('warehouse_id');
            $data['selected_warehouse_id'] = $warehouse_id;

            if($this->input->is_ajax_request()) 
            {   
                $response = array();
                $response['code'] = 1;                        
                $response['add_product_modal_body'] = $this->load->view('product_core/ajax/add_product_modal_body',$data,TRUE);
                echo json_encode($response);
            }
            else
            {   
                $this->load->view('product/add',$data);
            }
        }
    }
}*/


/*code end*/
public function ajax_list()
  {
    $list 		= $this->product_core_model->get_datatables();

		$query = $this->db->last_query();
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
	                          <i class="fas fa-edit"></i>
	                        </a>';

        $table_body .= '<a href="#"  data-toggle="modal" data-target="#copy_product_modal" data-tt="tooltip" title="'.$this->lang->line('product_copy').'" class="btn bg-purple btn-xs copy_product_modal" data-product_id="'.base64_encode($item->id).'">
	                          <i class="fas fa-copy"></i>
	                        </a>';

        // $table_body .= ' 
        //                   <a href="#" class="btn bg-purple btn-xs copy_product_modal"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('product_copy').'" data-product_id="'.base64_encode($item->id).'">
        //                     <i class="fas fa-copy"></i> Copy
        //                   </a>
        //                 ';
			}


			  // View product
        if($this->permission_model->has_permission('view_product'))
        {	
          $table_body .= '<a href="'.base_url('product_core/view/'.base64_encode($item->id)).'" title="Product View" class="btn btn-success btn-xs">
                              <i class="fas fa-eye"></i></a>';
        }

			// Delete Button
			if($this->permission_model->has_permission('delete_product'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_product_modal" data-tt="tooltip" title="'.$this->lang->line('product_delete').'" class="btn btn-danger btn-xs delete_product_modal" data-product_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i>
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

				$product_image = '';
      
				if($item->product_image != '')
        {
        	$product_image .= '<img src="'.base_url('assets/product_images/'.$item->product_image).'" style="height:50px;weight:50px;">';
        }
        else
        {
        	$product_image .= '<img src="'.base_url('assets/images/download.jpg').'" style="height:50px;weight:50px;">';
        }


        $product_status = '';

        if($item->status == PRODUCT_STATUS_ACTIVE)
        	$product_status .= '<span class="badge badge-success">'.ucfirst(PRODUCT_STATUS_ACTIVE).'</span>';

        if($item->status == PRODUCT_STATUS_INACTIVE)
        	$product_status .= '<span class="badge badge-danger">'.ucfirst(PRODUCT_STATUS_INACTIVE).'</span>';


          $manage_inventory = '';

          if($item->manage_inventory == MANAGE_INVENTORY_YES)
            $manage_inventory .= '<span class="badge badge-success">'.ucfirst(MANAGE_INVENTORY_YES).'</span>';
  
          if($item->manage_inventory == MANAGE_INVENTORY_NO)
            $manage_inventory .= '<span class="badge badge-danger">'.ucfirst(MANAGE_INVENTORY_NO).'</span>';


        $select_product_html = '<input type="checkbox" class="single_product"><input type="hidden" name="product_id" id="product_id" value="'.$item->id.'">';



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
				$row[] = $product_image;
        $row[] = $item->name.'<br/><span style="font-size:12px;font-weight:bolder">'.$item->description.'<br/><span style="font-size:12px;font-weight:bolder">'.strtoupper( $category_name).'</span>';
        // $row[] = $item->product_category_name;
        $row[] = $item->product_code; 
        $row[] = $uom_name;
        $row[] = $manage_inventory;
        $row[] = $item->hsn;
        $row[] = number_format($item->price,2);

        $row[] = number_format($item->cost,2);
        $row[] = number_format($item->selling_price,2);

        $row[] = $product_status;
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->product_core_model->count_all(),
                    "recordsFiltered" => $this->product_core_model->count_filtered(),
										"query"						=> $query,
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }		
	
}
