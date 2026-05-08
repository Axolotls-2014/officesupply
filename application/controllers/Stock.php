<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_stock'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$this->load->view('stock/list');	
		}
	}

	public function add($warehouse_product_id = NULL)
	{

		if(!$this->permission_model->has_permission('add_stock'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
			
    //     		$this->form_validation->set_rules("product_price","Price","required");
    //             $this->form_validation->set_rules("product_cost","Cost","required");
    //     		$this->form_validation->set_rules("selling_price","Selling Price","required");
    //             $this->form_validation->set_rules("product_quantity","Quantity","required");
    //             $this->form_validation->set_rules("product_uom","UOM","required");
    //             $this->form_validation->set_rules("entry_type","Entry Type","required");
				// $this->form_validation->set_rules("batch_no","Batch No","required");
				
				
				$this->form_validation->set_rules("product_price","Price","numeric");
                $this->form_validation->set_rules("product_cost","Cost","numeric");
                $this->form_validation->set_rules("selling_price","Selling Price","numeric");
                $this->form_validation->set_rules("product_quantity","Quantity","numeric");
                $this->form_validation->set_rules("product_uom","UOM","trim");
                $this->form_validation->set_rules("entry_type","Entry Type","trim");
                $this->form_validation->set_rules("batch_no","Batch No","trim");

				if($this->form_validation->run()==FALSE)
				{
					$data = array();
					
					$data["warehouses"] = $this->warehouse_model->get_records();
              $data["products"] = $this->product_model->get_records();
              $data["uoms"] = $this->uom_model->get_records();

					$this->load->view('stock/add',$data);
				}
				else
				{	

					$warehouse_id 		= $this->input->post("warehouse_id");

					// $warehouse_product_record = $this->warehouse_products_model->get_single_record($warehouse_product_id);

					// // $warehouse_id  						= $warehouse_product_record->warehouse_id;
					// $product_id  							= $warehouse_product_record->product_id;


					$product_id 							= $this->input->post("product_id");
					$warehouse_name						= $this->input->post("warehouse_name");
          
         	$product_name 						= $this->input->post("product_name");
          $product_quantity 				= $this->input->post("product_quantity");
          $product_price 						= $this->input->post("product_price");
          $product_cost 						= $this->input->post("product_cost");
					$selling_price 						= $this->input->post("selling_price");
          $product_uom 							= $this->input->post("product_uom");
          $entry_type 							= $this->input->post("entry_type");
					$batch_no 								= $this->input->post("batch_no");
				
					$data     = array(
                          "product_id" 						=> $product_id,
                          "warehouse_name" 				=> $warehouse_name,
													"warehouse_id" 					=> $warehouse_id,
                          "product_name" 					=> $product_name,
                          "quantity" 							=> $product_quantity,
                          "product_price" 				=> $product_price,
                          "product_cost" 					=> $product_cost,
													"selling_price" 				=> $selling_price,
                          "product_uom" 					=> $product_uom,
                          "entry_type" 						=> $entry_type,
													"batch_no" 							=> $batch_no,
                          "user_id" 							=> $this->session->userdata('user_id')
                        );


											// echo '<pre>';
											// print_r($data);
											// exit;

					// being transaction
					$this->db->trans_begin();
					
					if($id = $this->stock_model->add_record($data))
					{

						/* Start Increase product quantity */

						$warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id,$batch_no,$product_cost,$product_price,$selling_price);

						if($warehouse_product != null)
						{
							$old_product_quantity = $warehouse_product->quantity;

							if($entry_type == WAREHOUSE_STOCK_IN)
								$new_product_quantity = $old_product_quantity + $product_quantity;
							else
								$new_product_quantity = $old_product_quantity - $product_quantity;	
							
							$temp_warehouse_product = array("quantity" => $new_product_quantity);

							$this->warehouse_products_model->edit_record($temp_warehouse_product,$warehouse_product->id);	
						}
						else
						{
							$product = $this->product_model->get_single_record($product_id);
							$cost = $product_cost;
							$temp_warehouse_product = array(
																							"warehouse_id" => $warehouse_id,
																							"product_id" => $product_id,
																							"quantity" => $product_quantity,
																							"price" => $product_price,
																							"selling_price" => $selling_price,
																							"cost" => $cost,
																							"batch_no" => $batch_no
																						);
							$this->warehouse_products_model->add_record($temp_warehouse_product);		
						}

						/* End Increase product quantity */

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= $product_name.' is added successfully.';
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', $product_name.' is added successfully');
							redirect('stock','refresh');
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
							$response['message']						= ucwords(str_replace("_", " ", "stock")).' is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', ucwords(str_replace("_", " ", "stock")).' is failed to add.');
							redirect('stock','refresh');
						}
						
					}
				}

			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = $warehouse_product_id;

					

					if($warehouse_product_id != null)
					{
						$data["warehouses"] = $this->warehouse_model->get_records();
						$data["products"] 	= $this->product_model->get_records();

						$data['warehouse_product'] = $this->warehouse_products_model->get_single_record($id);
						$query = $this->db->last_query();

						$response 					= array();
						$response['code']		= 1; 						
						$response['add_stock_modal_body'] 	= $this->load->view('stock/ajax/add_stock_modal_body',$data,TRUE);
						$response['warehouse_product'] = $data['warehouse_product'];
						$response['query'] = $query;

						// $response['stock_entry_modal_body'] 	= $this->load->view('stock/ajax/stock_entry_modal_body',$data,TRUE);

						echo json_encode($response);
					}
					else
					{
						$data["warehouses"] = $this->warehouse_model->get_records();
						$data["products"] 	= $this->product_model->get_records();
						$data['warehouse_product'] = null;
						

						$response 					= array();
						$response['code']		= 1; 						
						$response['add_stock_modal_body'] 	= $this->load->view('stock/ajax/add_stock_modal_body',$data,TRUE);
						// $response['stock_entry_modal_body'] 	= $this->load->view('stock/ajax/stock_entry_modal_body',$data,TRUE);

						echo json_encode($response);
					}

					
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('stock/'.base64_encode($id),'refresh');
				}
			}
		}
	}
public function edit($id = null)
{
	if(!$this->permission_model->has_permission('edit_stock'))
	{
		$this->load->view('errors/html/error_restricted'); 
	}
	else
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$id = $this->input->post('id');

// 			$this->form_validation->set_rules("product_quantity","Quantity","required");
// 			$this->form_validation->set_rules("product_uom","UOM","required");
// 			$this->form_validation->set_rules("entry_type","Entry Type","required");
// 			$this->form_validation->set_rules("batch_no","Batch No","required");

            $this->form_validation->set_rules("product_quantity","Quantity","numeric");
            $this->form_validation->set_rules("product_uom","UOM","trim");
            $this->form_validation->set_rules("entry_type","Entry Type","trim");
            $this->form_validation->set_rules("batch_no","Batch No","trim");
		
			if($this->form_validation->run()==FALSE)
			{
				$data = array();
				
				$data["warehouses"] = $this->warehouse_model->get_records();
				$data["products"] = $this->product_model->get_records();
				$data['stock'] = $this->stock_model->get_single_record($id);

				$this->load->view('stock/edit',$data);
			}
			else
			{
				// Begin transaction
				$this->db->trans_begin();
				
				$stock = $this->stock_model->get_single_record($id);

				$warehouse_id  	= $this->input->post("warehouse_id");
				$product_id  		= $this->input->post("product_id");
				$product_quantity = $this->input->post("product_quantity");
				$product_price 		= $this->input->post("product_price");
				$product_cost 		= $this->input->post("product_cost");
				$selling_price 		= $this->input->post("selling_price");
				$product_uom 			= $this->input->post("product_uom");
				$entry_type 			= $this->input->post("entry_type");
				$batch_no 				= $this->input->post("batch_no");
			
				$data = array(
					"warehouse_id" 				=> $warehouse_id,
					"product_id" 					=> $product_id,
					"quantity" 						=> $product_quantity,
					"product_price" 			=> $product_price,
					"product_cost" 				=> $product_cost,
					"selling_price" 			=> $selling_price,
					"product_uom" 				=> $product_uom,
					"entry_type" 					=> $entry_type,
					"batch_no" 						=> $batch_no,
					"user_id" 						=> $this->session->userdata('user_id')
				);

				// Get the current warehouse product record
				$current_warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price(
					$stock->warehouse_id, 
					$stock->product_id, 
					$stock->batch_no, 
					$stock->product_cost, 
					$stock->product_price, 
					$stock->selling_price
				);

				if($current_warehouse_product != null)
				{
					// Remove the old stock quantity from warehouse product
					if($stock->entry_type == WAREHOUSE_STOCK_IN)
						$new_old_quantity = $current_warehouse_product->quantity - $stock->quantity;
					else
						$new_old_quantity = $current_warehouse_product->quantity + $stock->quantity;
					
					$this->warehouse_products_model->edit_record(
						array("quantity" => $new_old_quantity),
						$current_warehouse_product->id
					);
				}

				// Get or create new warehouse product record with updated details
				$new_warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price(
					$warehouse_id, 
					$product_id, 
					$batch_no, 
					$product_cost, 
					$product_price, 
					$selling_price
				);

				if($new_warehouse_product != null)
				{
					if($entry_type == WAREHOUSE_STOCK_IN)
						$new_quantity = $new_warehouse_product->quantity + $product_quantity;
					else
						$new_quantity = $new_warehouse_product->quantity - $product_quantity;
					
					$this->warehouse_products_model->edit_record(
						array("quantity" => $new_quantity),
						$new_warehouse_product->id
					);
				}
				else
				{
					$temp_warehouse_product = array(
						"warehouse_id" => $warehouse_id,
						"product_id" => $product_id,
						"quantity" => $product_quantity,
						"price" => $product_price,
						"selling_price" => $selling_price,
						"cost" => $product_cost,
						"batch_no" => $batch_no
					);
					$this->warehouse_products_model->add_record($temp_warehouse_product);
				}

				if($this->stock_model->edit_record($data,$id))
				{
					$this->db->trans_commit();

					if($this->input->is_ajax_request()) 
					{
						$response['code'] = 1;
						$response['message'] = 'Stock is updated successfully';
						echo json_encode($response);
					}
					else
					{
						$this->session->set_flashdata('success', 'Stock is updated successfully');
						redirect('stock','refresh');
					}
				}
				else
				{
					$this->db->trans_rollback();
					
					if($this->input->is_ajax_request()) 
					{
						$response['code'] = 0;
						$response['message'] = 'Stock is failed to update';
						echo json_encode($response);
					}
					else
					{
						$this->session->set_flashdata('failure', 'Stock is failed to update');
						redirect('stock','refresh');	
					}
				}
			}
		}
		else
		{
			if($this->input->is_ajax_request()) 
			{
				$id = base64_decode($id);
				$data['stock'] = $this->stock_model->get_single_record($id);
				$data["warehouses"] = $this->warehouse_model->get_records();
				$data["products"] = $this->product_model->get_records();
				
				$response = array();
				$response['code'] = 1;
				$response['edit_stock_modal_body'] = $this->load->view('stock/ajax/edit_stock_modal_body',$data,TRUE);
				echo json_encode($response);
			}
			else
			{
				$id = base64_decode($id);
				$data = array();
				$data['stock'] = $this->stock_model->get_single_record($id);
				$data["warehouses"] = $this->warehouse_model->get_records();
				$data["products"] = $this->product_model->get_records();

				if($data['stock'] != null)
				{
					$this->load->view('stock/list',$data);
				}
				else
				{
					$this->session->set_flashdata('failure', 'You have tried to access broken URL.');
					redirect('stock');
				}
			}
		}
	}
}
	
	// public function edit($id = null)
	// {
	// 	if(!$this->permission_model->has_permission('edit_stock'))
	// 	{
	// 		$this->load->view('errors/html/error_restricted'); 
	// 	}
	// 	else
	// 	{
	// 		if($this->input->server('REQUEST_METHOD') === 'POST')
	// 		{
	// 			$id = $this->input->post('id');

	// 			// $this->form_validation->set_rules("warehouse_id","Warehouse","required");
	// 			// $this->form_validation->set_rules("product_id","Product","required");
  //       $this->form_validation->set_rules("product_price","Price","required");
  //       $this->form_validation->set_rules("product_cost","Cost","required");
  //       $this->form_validation->set_rules("product_quantity","Quantity","required");
  //       $this->form_validation->set_rules("product_uom","UOM","required");
  //       $this->form_validation->set_rules("entry_type","Entry Type","required");
	// 			$this->form_validation->set_rules("selling_price","Selling Price","required");
	// 			$this->form_validation->set_rules("batch_no","Batch No","required");
        
			
	// 			if($this->form_validation->run()==FALSE)
	// 			{
	// 				$data = array();
					
	// 				$data["warehouses"] = $this->warehouse_model->get_records();
  //         $data["products"] = $this->product_model->get_records();
          
					
	// 				$data['stock'] = $this->stock_model->get_single_record($id);

	// 				$this->load->view('stock/edit',$data);
	// 			}
	// 			else
	// 			{
	// 				$stock = $this->stock_model->get_single_record($id);

	// 				$warehouse_product_id 		= $this->input->post("warehouse_product_id");

	// 				$warehouse_product_record = $this->warehouse_products_model->get_single_record($warehouse_product_id);

	// 				$warehouse_id  						= $warehouse_product_record->warehouse_id;
	// 				$product_id  							= $warehouse_product_record->product_id;

	// 				$warehouse_name						= $this->input->post("warehouse_name");
          
  //        	$product_name 						= $this->input->post("product_name");
  //         $product_quantity 				= $this->input->post("product_quantity");
  //         $product_price 						= $this->input->post("product_price");
  //         $product_cost 						= $this->input->post("product_cost");
	// 				$selling_price 						= $this->input->post("selling_price");
  //         $product_uom 							= $this->input->post("product_uom");
  //         $entry_type 							= $this->input->post("entry_type");
	// 				$batch_no 								= $this->input->post("batch_no");
				
	// 				$data     = array(
  //                         "warehouse_product_id" 	=> $warehouse_product_id,
  //                         "warehouse_name" 				=> $warehouse_name,
                        
  //                         "product_name" 					=> $product_name,
  //                         "quantity" 							=> $product_quantity,
  //                         "product_price" 				=> $product_price,
  //                         "product_cost" 					=> $product_cost,
	// 												"selling_price" 				=> $selling_price,
  //                         "product_uom" 					=> $product_uom,
  //                         "entry_type" 						=> $entry_type,
	// 												"batch_no" 							=> $batch_no,
  //                         "user_id" 							=> $this->session->userdata('user_id')
  //                       );

	// 											// echo '<pre>';
	// 											// print_r($data);
	// 											// exit;

	// 				/* Start Increase product quantity */

	// 				$warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id,$product_cost,$product_price,$selling_price,$batch_no);
					
	// 				// if($warehouse_product->quantity >= $product_quantity)
	// 				// {
	// 					if($warehouse_product != null)
	// 					{
	// 						if($stock->product_price == $product_price && $stock->product_cost == $product_cost && $stock->selling_price == $selling_price && $stock->batch_no == $batch_no)
	// 						{
	// 							$old_product_quantity = $warehouse_product->quantity;

	// 							if($entry_type == WAREHOUSE_STOCK_IN)
	// 								$new_product_quantity = $old_product_quantity + $product_quantity - $stock->quantity;
	// 							else
	// 								$new_product_quantity = $old_product_quantity - $product_quantity + $stock->quantity;	
								
	// 							$temp_warehouse_product = array("quantity" => $new_product_quantity);

	// 							$this->warehouse_products_model->edit_record($temp_warehouse_product,$warehouse_product->id);		
	// 						}
	// 						else
	// 						{

	// 							// Privious product 
	// 							$previous_warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id,$stock->product_cost,$stock->product_price,$stock->selling_price,$stock->batch_no);
								
	// 							if($entry_type == WAREHOUSE_STOCK_IN)
	// 								$new_previous_product_quantity = $previous_warehouse_product->quantity-$stock->quantity;
	// 							else
	// 								$new_previous_product_quantity = $previous_warehouse_product->quantity+$stock->quantity;

	// 							$temp_previous_warehouse_product = array("quantity" => $new_previous_product_quantity);
	// 							$this->warehouse_products_model->edit_record($temp_previous_warehouse_product,$previous_warehouse_product->id);		

	// 							// New product

	// 							$new_warehouse_product 			= $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id,$product_cost,$product_price,$selling_price,$batch_no);

	// 							if($entry_type == WAREHOUSE_STOCK_IN)
	// 								$new_product_quantity = $new_warehouse_product->quantity+$stock->quantity;
	// 							else
	// 								$new_product_quantity = $new_warehouse_product->quantity-$stock->quantity;

	// 							$temp_new_warehouse_product = array("quantity" => $new_product_quantity);
	// 							$this->warehouse_products_model->edit_record($temp_new_warehouse_product,$new_warehouse_product->id);		
	// 						}
	// 					}
	// 					else
	// 					{

	// 						// Privious product 
	// 						$previous_warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id,$stock->product_cost,$stock->product_price,$stock->selling_price,$stock->batch_no);
							
	// 						if($entry_type == WAREHOUSE_STOCK_IN)
	// 							$new_previous_product_quantity = $previous_warehouse_product->quantity-$stock->quantity;
	// 						else
	// 							$new_previous_product_quantity = $previous_warehouse_product->quantity+$stock->quantity;

	// 						$temp_previous_warehouse_product = array("quantity" => $new_previous_product_quantity);
	// 						$this->warehouse_products_model->edit_record($temp_previous_warehouse_product,$previous_warehouse_product->id);		


	// 						$product = $this->product_model->get_single_record($product_id);
	// 						$cost = $product_cost;
	// 						$temp_warehouse_product = array(
	// 																						"warehouse_id" => $warehouse_id,
	// 																						"product_id" => $product_id,
	// 																						"quantity" => $product_quantity,
	// 																						"price" => $product_price,
	// 																						"cost" => $cost,
	// 																						"selling_price" => $selling_price,
	// 																						"batch_no" => $batch_no
	// 																					);
	// 						$this->warehouse_products_model->add_record($temp_warehouse_product);	
	// 					}

	// 					/* End Increase product quantity */

	// 					if($this->stock_model->edit_record($data,$id))
	// 					{

	// 						// commit transaction
	// 						$this->db->trans_commit();

	// 						if($this->input->is_ajax_request()) 
	// 						{
	// 							$response['code'] = 1;
	// 							$response['message'] = 'stock is updated successfully';

	// 							echo json_encode($response);
	// 						}
	// 						else
	// 						{
	// 							$this->session->set_flashdata('success', $product_name.' is updated successfully');
	// 							redirect('stock','refresh');
	// 						}
	// 					}
	// 					else
	// 					{
	// 						if($this->input->is_ajax_request()) 
	// 						{
	// 							$response['code']  		= 0;
	// 							$response['message'] 	= 'stock is failed to update';

	// 							echo json_encode($response);
	// 						}
	// 						else
	// 						{
	// 							$this->session->set_flashdata('failure', $product_name.' is failed to update');
	// 							redirect('stock','refresh');	
	// 						}
	// 					}
	// 				// }
	// 				// else
	// 				// {
	// 				// 	if($this->input->is_ajax_request()) 
	// 				// 	{
	// 				// 		$response['code']  		= 0;
	// 				// 		$response['message'] 	= "Sufficient quantity of ".$product_name." is not available to delete this stock entry.";

	// 				// 		echo json_encode($response);
	// 				// 	}
	// 				// 	else
	// 				// 	{
	// 				// 		$this->session->set_flashdata('failure', "Sufficient quantity of ".$product_name." is not available to delete this stock entry.");
	// 				// 		redirect('stock','refresh');	
	// 				// 	}
	// 				// }

	// 			}
				
	// 		}
	// 		else
	// 		{
	// 			if($this->input->is_ajax_request()) 
	// 			{
	// 				$id = base64_decode($id);
					
	// 				$data['stock'] 		= $this->stock_model->get_single_record($id);

	// 				$data["warehouses"] = $this->warehouse_model->get_records();
	// 				$data["warehouse_products"] 	= $this->warehouse_products_model->get_records();
  //         $data["products"] = $this->product_model->get_records();
          
					
	// 		  	$response 									= array();
	// 		  	$response['code']  					= 1;
	// 				$response['edit_stock_modal_body'] = $this->load->view('stock/ajax/edit_stock_modal_body',$data,TRUE);

	// 		  	echo json_encode($response);
	// 			}
	// 			else
	// 			{
	// 				$id 											= base64_decode($id);
	// 				$data 										= array();
	// 				$data['stock'] 		= $this->stock_model->get_single_record($id);

	// 				$data["warehouses"] = $this->warehouse_model->get_records();
	// 				$data["warehouse_products"] 	= $this->warehouse_products_model->get_records();
  //         $data["products"] = $this->product_model->get_records();
          

	// 				if($data['stock'] != null)
	// 				{
	// 					$this->load->view('stock/list',$data);
	// 				}
	// 				else
	// 				{
	// 					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
	// 					redirect('stock');
	// 				}
	// 			}
				
	// 		}
	// 	}
	// }

	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_stock'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 							= $this->input->post('id');

			$data = array('delete_status' => 1);

			$stock =  $this->stock_model->get_single_record($id);

			//$warehouse_record = $this->warehouse_model->get_single_record($stock->warehouse_id);

			$warehouse_id  						= $stock->warehouse_id;
			$product_id  							= $stock->product_id;

			if($this->stock_model->edit_record($data,$id))
			{
				$warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id,$stock->batch_no,$stock->product_cost,$stock->product_price,$stock->selling_price);

				if($stock->entry_type == WAREHOUSE_STOCK_IN)
				{
					$old_product_quantity = $warehouse_product->quantity;
					$new_product_quantity = $old_product_quantity - $stock->quantity;
					$temp_warehouse_product = array("quantity" => $new_product_quantity);

					$this->warehouse_products_model->edit_record($temp_warehouse_product,$warehouse_product->id);		
				}
				else
				{
					$old_product_quantity = $warehouse_product->quantity;
					$new_product_quantity = $old_product_quantity + $stock->quantity;
					$temp_warehouse_product = array("quantity" => $new_product_quantity);

					$this->warehouse_products_model->edit_record($temp_warehouse_product,$warehouse_product->id);		
				}	
				
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= $stock->product_name.' is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', $stock->product_name.' is deleted successfully');
					redirect('stock','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= $stock->product_name.' is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', $stock->product_name.' is failed to delete');
					redirect('stock');
				}
			}
		}
	}


  public function stock_delete_confirmation()
  {
  	$stock_id 				= $this->input->post('stock_id');
  	$data['stock']		= $this->stock_model->get_single_record($stock_id);

  	$response 																		= array();
  	$response['delete_stock_modal_body'] 	= $this->load->view('stock/ajax/delete_stock_modal_body',$data,TRUE);

  	echo json_encode($response);
	}
	
		public function ajax_list()
  {
    $from_date = $this->input->post('from_date');
    $to_date = $this->input->post('to_date');

    $list 		= $this->stock_model->get_datatables($from_date, $to_date);
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';
// In the action column section of your list page, uncomment the edit button code
if($this->permission_model->has_permission('edit_stock'))
{	
	$table_body .= '<a href="#" data-toggle="modal" data-target="#edit_stock_modal" data-tt="tooltip" title="'.$this->lang->line('stock_edit').'" class="btn btn-info btn-xs edit_stock_modal" data-stock_id="'.base64_encode($item->id).'">
                      <i class="fas fa-edit"></i> Edit
                    </a>';
}
     // Edit Button        
      // if($this->permission_model->has_permission('edit_stock'))
			// {	
			// 	$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_stock_modal" data-tt="tooltip" title="'.$this->lang->line('stock_edit').'" class="btn btn-info btn-xs edit_stock_modal" data-stock_id="'.base64_encode($item->id).'">
	    //                       <i class="fas fa-edit"></i> Edit
	    //                     </a>';
			// }

			// Delete Button
			if($this->permission_model->has_permission('delete_stock'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_stock_modal" data-tt="tooltip" title="'.$this->lang->line('stock_delete').'" class="btn btn-danger btn-xs delete_stock_modal" data-stock_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i>
	                        </a>';
			}

		

			/* End Action column buttons*/

      $row = array();

      $row[] = $item->warehouse_name;
      $row[] = $item->product_name;
      $row[] = $this->db->select('hsn')->where('id', $item->product_id)->get('product')->row('hsn');
      $row[] = $item->batch_no;
      $row[] = $item->quantity;
      $row[] = $item->product_uom;
      $row[] = number_format($item->product_cost,2);
      $row[] = number_format($item->selling_price,2);
      $row[] = ($item->entry_type == WAREHOUSE_STOCK_IN) ? '<span class="badge badge-success">'.strtoupper($item->entry_type).'</span>' : '<span class="badge badge-danger">'.strtoupper($item->entry_type).'</span>';
      $row[] = date('d-m-Y',strtotime($item->created_date));
                      
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->stock_model->count_all(),
                    "recordsFiltered" => $this->stock_model->count_filtered(),
                    "data" 						=> $data,
            			);


   
    echo json_encode($output);
  }	


	public function ajax_list2704()
  {
    $list 		= $this->stock_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

     // Edit Button        
      // if($this->permission_model->has_permission('edit_stock'))
			// {	
			// 	$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_stock_modal" data-tt="tooltip" title="'.$this->lang->line('stock_edit').'" class="btn btn-info btn-xs edit_stock_modal" data-stock_id="'.base64_encode($item->id).'">
	    //                       <i class="fas fa-edit"></i> Edit
	    //                     </a>';
			// }

			// Delete Button
			if($this->permission_model->has_permission('delete_stock'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_stock_modal" data-tt="tooltip" title="'.$this->lang->line('stock_delete').'" class="btn btn-danger btn-xs delete_stock_modal" data-stock_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i>
	                        </a>';
			}

		

			/* End Action column buttons*/

      $row = array();

      $row[] = $item->warehouse_name;
      $row[] = $item->product_name;
      $row[] = $this->db->select('hsn')->where('id', $item->product_id)->get('product')->row('hsn');
      $row[] = $item->batch_no;
      $row[] = $item->quantity;
      $row[] = $item->product_uom;
      $row[] = number_format($item->product_cost,2);
      $row[] = number_format($item->selling_price,2);
      $row[] = ($item->entry_type == WAREHOUSE_STOCK_IN) ? '<span class="badge badge-success">'.strtoupper($item->entry_type).'</span>' : '<span class="badge badge-danger">'.strtoupper($item->entry_type).'</span>';
      $row[] = date('d-m-Y',strtotime($item->created_date));
                      
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->stock_model->count_all(),
                    "recordsFiltered" => $this->stock_model->count_filtered(),
                    "data" 						=> $data,
            			);


   
    echo json_encode($output);
  }	

  function calculate_sqm($product_size)
  {
  	$product_size_array = explode("x", $product_size);
  	return ((float)$product_size_array[0] * (float)$product_size_array[1]);
  }
	
}
