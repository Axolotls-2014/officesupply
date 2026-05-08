<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_category extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_product_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$this->load->view('product_category/list');	
		}
	}

	public function add($id = NULL)
	{

		if(!$this->permission_model->has_permission('add_product_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				
				$this->form_validation->set_rules("name","Name","required");
        $this->form_validation->set_rules('tax_id','Tax','required');		

				if($this->form_validation->run()==FALSE)
				{
					$data['tax'] = $this->tax_model->get_records();
					$this->load->view('product_category/add',$data);
				}
				else
				{	

					$name 				= 	$this->input->post('name');
					$description 	= 	$this->input->post('description');
					$tax_type 		= 	$this->input->post('tax_type');

					$tax_id 			= 	$this->input->post('tax_id');
					
					if($tax_id == 'new_tax')
					{
						$tax_name = $this->input->post('tax_name');
						$igst 		= $this->input->post('igst');
						$cgst 		= $this->input->post('cgst');
						$sgst 		= $this->input->post('sgst');

						$tax_data = array(
															"tax_name"	=>	$tax_name,
															"sgst"			=>	$sgst,
															"cgst"			=>	$cgst,
															"igst"			=>	$igst,
															"user_id"		=> 	$this->session->userdata('user_id')
														);

						$tax_id 	= $this->tax_model->add_record($tax_data);
					}

					if($tax_type == null)
					{
						$tax_type = 0;
					}

					

					$data			=	array(
														"name"				=> 	$name,
													
														"description"	=>	$description,
														"tax_id"			=>	$tax_id,
														"tax_type"		=>  $tax_type,
														"user_id" => $this->session->userdata('user_id'),
													);

					$tax = $this->tax_model->get_single_record($tax_id);
				

					$data['igst'] = $tax->igst;
					$data['cgst'] = $tax->cgst;
					$data['sgst'] = $tax->sgst;

					// being transaction
					$this->db->trans_begin();
					
					if($id = $this->product_category_model->add_record($data))
					{

						$pcid = PCID_SEQUENCE+$id;

						$this->product_category_model->edit_record(array('pcid'=>$pcid),$id);

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'Product Category is added successfully.';
							$response['product_categories'] = $this->product_category_model->get_records();
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Product Category is added successfully');
							redirect('product_category','refresh');
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
							$response['message']						= 'Product Category is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Product Category is failed to add.');
							redirect('product_category','refresh');
						}
						
					}
				}

			}
			else
			{
				$data 											= array();
				$data['tax'] 								= $this->tax_model->get_records();

				if($this->input->is_ajax_request()) 
				{
					
					$response 									= array();
					$response['code']						= 1; 						
			  	$response['add_product_category_modal_body'] 	= $this->load->view('product_category/ajax/add_product_category_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('product_category/add',$data);
				}
			}
		}
	}

	
	public function edit($id = null)
	{

		if(!$this->permission_model->has_permission('edit_product_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');

				$this->form_validation->set_rules("name","Name","required");
        $this->form_validation->set_rules('tax_id','Tax','required');
        
			
				if($this->form_validation->run()==FALSE)
				{
					$data['product_category'] = $this->product_category_model->get_single_record($id);
					$data['tax'] 							= $this->tax_model->get_records();
					$this->load->view('product_category/edit',$data);
				}
				else
				{
					$name 				= 	$this->input->post('name');
					$description 	= 	$this->input->post('description');
					$tax_type 		= 	$this->input->post('tax_type');

					$tax_id 			= 	$this->input->post('tax_id');

					if($tax_id == 'new_tax')
					{
						$tax_name = $this->input->post('tax_name');
						$igst 		= $this->input->post('igst');
						$cgst 		= $this->input->post('cgst');
						$sgst 		= $this->input->post('sgst');

						$tax_data = array(
															"tax_name"	=>	$tax_name,
															"sgst"			=>	$sgst,
															"cgst"			=>	$cgst,
															"igst"			=>	$igst,
															"user_id"		=> 	$this->session->userdata('user_id')
														);

						$tax_id 	= $this->tax_model->add_record($tax_data);
					}		


					if($tax_type == null)
					{
						$tax_type = 0;
					}

					$tax = $this->tax_model->get_single_record($tax_id);

					$data			=	array(
											"name"				=> 	$name,
											"description"	=>	$description,
											"tax_id"			=>	$tax_id,
											"tax_type"		=>  $tax_type,
											"igst"				=>  $tax->igst,
											"cgst"				=>  $tax->cgst,
											"sgst"				=>  $tax->sgst,
											"user_id"			=>  $this->session->userdata('user_id')
									);


					if($this->product_category_model->edit_record($data,$id))
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code'] = 1;
							$response['message'] = 'Product Category is updated successfully';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Product Category is updated successfully');
							redirect('product_category','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code']  		= 0;
							$response['message'] 	= 'Product Category is failed to update';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Product Category is failed to update');
							redirect('product_category','refresh');	
						}
						
					}
				}
				
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($id);
					
					$data['product_category'] 	= $this->product_category_model->get_single_record($id);
					$data['tax'] 								= $this->tax_model->get_records();
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['edit_product_category_modal_body'] = $this->load->view('product_category/ajax/edit_product_category_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['product_category'] 		= $this->product_category_model->get_single_record($id);
					$data['tax'] 									= $this->tax_model->get_records();

					if($data['product_category'] != null)
					{

						$this->load->view('product_category/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('product_category/');
					}
				}
				
			}
		}
	}

	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_product_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 							= $this->input->post('id');

			$data = array('delete_status' => 1);

			$product_category =  $this->product_category_model->get_single_record($id);

			if($this->product_category_model->edit_record($data,$id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= 'Product Category is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Product Category is deleted successfully');
					redirect('product_category','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Product Category is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Product Category is failed to delete');
					redirect('product_category');
				}
			}
		}
	}

	// public function import_product_category()
	// {
	// 	if(!$this->permission_model->has_permission('import_product_category'))
	// 	{
	// 		$this->load->view('errors/html/error_restricted'); 
	// 	}
	// 	else
	// 	{
	// 		if($this->input->server('REQUEST_METHOD') === 'POST')
	// 		{	
				
				
	// 			$filename = $this->upload_model->do_single_upload($_FILES['csvfile']);	
	// 			$file_path = './assets/import/'.$filename;

	// 			$query = sprintf(
	// 												"
	// 													LOAD DATA LOCAL INFILE '%s' INTO TABLE product_category
	// 							            FIELDS TERMINATED BY ',' 
	// 							            LINES TERMINATED BY '\r\n'
	// 							            IGNORE 1 LINES (name, description, cgst, sgst, igst);
	// 						           	", 
	// 						          addslashes($file_path));

	// 			if($number = $this->db->query($query))
	// 			{
	// 				$no_imported_product_categories = $this->db->affected_rows();

	// 				$import_product_categories = $this->db->query('SELECT * FROM `product_category` `c` ORDER BY id DESC LIMIT '.$no_imported_product_categories)->result();

	// 				foreach ($import_product_categories as $value) {

	// 					$tax = $this->tax_model->get_single_record_by_tax_value($value->igst, $value->sgst, $value->cgst);
	// 					$tax_id = '';

	// 					if($tax == null)
	// 					{
	// 						$data = array(
	// 													'tax_name' 		=> 'GST '.$value->igst,
	// 													'igst' 				=> $value->igst, 
	// 													'cgst'				=> $value->cgst, 
	// 													'sgst' 				=> $value->sgst,
	// 													'user_id' 		=> $this->session->userdata('user_id')
	// 												);
	// 						$tax_id = $this->tax_model->add_record($data);
	// 					}
	// 					else
	// 					{
	// 						$tax_id = $tax->id;
	// 					}

						
	// 					$data = array(
	// 												"tax_id"  => $tax_id,
	// 												"user_id" => $this->session->userdata('user_id'),
	// 											);

	// 					$this->product_category_model->edit_record($data, $value->id);
	// 				}

	// 				$this->session->set_flashdata('success', $no_imported_product_categories.' Products are imported successfully');
	// 				redirect('product_category','refresh');
	// 			}
	// 			else
	// 			{
	// 				$this->session->set_flashdata('success', $no_imported_product_categories.' Products are failed to import.');
	// 				redirect('product_category','refresh');
	// 			}
	// 		}
	// 		else
	// 		{
	// 			if($this->input->is_ajax_request()) 
	// 			{
	// 				$response 									= array();
	// 		  	$response['code']  					= 1;
	// 				$response['import_product_category_modal_body'] = $this->load->view('product_category/ajax/import_product_category_modal_body',null,TRUE);

	// 		  	echo json_encode($response);
	// 			}
	// 			else
	// 			{
	// 				$this->load->view('errors/html/error_restricted'); 
	// 			}
	// 		}
	// 	}
	// }

	public function import_product_category()
	{
		if(!$this->permission_model->has_permission('import_product_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
				$isUpdateproductCategoryChecked 	= $this->input->post('update_product_category') === '1';
				$isUpdateCreateNewIfExistChecked 	= $this->input->post('create_product_category') === '1';

        $product_category = $this->product_category_model->get_records();
				$taxes = $this->tax_model->get_records();

				$expectedHeaders = [
						'TID',
						'ProductCategoryName',
						'Description',
						'CGST(%)',
						'SGST(%)',
						'IGST(%)',
						'PCID',
				];

				$isCSVFileValid = is_csv_valid(
															$_FILES["csvfile"]["tmp_name"],
															',',
															$expectedHeaders
													);


				$file_path = $_FILES["csvfile"]["tmp_name"];
				$file = fopen($file_path, 'r');
													

				$header_row = fgetcsv($file, 0, $delimiter= ",");

				// var_dump($header_row); // Debugging statement to display the header row
        // var_dump($expectedHeaders);

				//  print_r($isCSVFileValid);
        // exit;

				if ($isCSVFileValid !== true)
				{
					$this->session->set_flashdata('failure', $isCSVFileValid);
					redirect('product_category','refresh');
				}
				else
				{
					
					if(is_uploaded_file($_FILES['csvfile']['tmp_name']))
					{
						// Parse data from CSV file
						$csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);
	
						if(!empty($csvData))
						{
							$product_with_unknown_tid = array();
	
								foreach($csvData as $row)
								{ 
	
									$matching_category = null;
	
										foreach ($taxes as $category) {
												if ($row['TID'] == $category->tid) {
														$matching_category = $category;
														break;
												}
										}
	
									if ($matching_category !== null) {
	
										$tax_category = $this->utility_model->get_records_by_field('tax','tid',$row['TID']);
	
										 $product_category_data = array(
	
																					'tax_id' 					=> $tax_category->id,
																					'name'           => $row['ProductCategoryName'],
																					'description'    => $row['Description'],
																					'cgst'           => $row['CGST(%)'],
																					'sgst'  			   => $row['SGST(%)'],
																					'igst'           => $row['IGST(%)']
																					
																			);
	
									
										// Check whether name already exists in the database
										$exist_product_categories = $this->utility_model->get_records_by_field('product_category','name',$row['ProductCategoryName'],false,true);
	
										if($exist_product_categories == null || $isUpdateCreateNewIfExistChecked)
										{
											$product_category_id = $this->product_category_model->add_record($product_category_data);
											$product_category 	 = $this->product_category_model->get_single_record($product_category_id);
	
										
											$tax = $this->tax_model->get_single_record_by_tax_value($row['IGST(%)'], $row['SGST(%)'], $row['CGST(%)']);
											$tax_id = '';
					
											if($tax == null)
											{
												$data = array(
																			'tax_name' 		=> 'GST '.$row['IGST(%)'],
																			'igst' 				=> $row['IGST(%)'], 
																			'cgst'				=> $row['CGST(%)'], 
																			'sgst' 				=> $row['SGST(%)'],
																			'user_id' 		=> $this->session->userdata('user_id')
																		);
												$tax_id = $this->tax_model->add_record($data);
											}
											else
											{
												$tax_id = $tax->id;
											}
										
																
											$data = array(
																		// "tax_id"  => $tax_id,
																		"user_id" => $this->session->userdata('user_id'),
																	);
					
											$this->product_category_model->edit_record($data, $product_category->id);
											
										}
										else
										{
	
											if ($isUpdateproductCategoryChecked) 
											{
												foreach ($exist_product_categories as $value) 
												{
														// Update product category data
														$this->product_category_model->edit_record($product_category_data, $value->id);
	
														// Update tax data
														$tax = $this->tax_model->get_single_record_by_tax_value($row['IGST(%)'], $row['SGST(%)'], $row['CGST(%)']);
														$tax_id = '';
	
														if ($tax == null) 
														{
																$data = array(
																		'tax_name' => 'GST ' . $row['IGST(%)'],
																		'igst' => $row['IGST(%)'],
																		'cgst' => $row['CGST(%)'],
																		'sgst' => $row['SGST(%)'],
																		'user_id' => $this->session->userdata('user_id')
																);
																$tax_id = $this->tax_model->add_record($data);
														} 
														else 
														{
																$tax_id = $tax->id;
														}
	
														$data = array(
																// "tax_id" => $tax_id,
																"user_id" => $this->session->userdata('user_id'),
														);
	
														$this->product_category_model->edit_record($data, $value->id);
												}
											}
											
										}
									} 
									else
									{
										$product_with_unknown_tid[] = $row['ProductCategoryName'];
									}
	
								}
								
							 
								$success_message = 'Product category imported successfully.';
	
								if(sizeof($product_with_unknown_tid) > 0)
									$success_message .= ' And '.implode(",",$product_with_unknown_tid).' is failed to update or add because of unknown of TID value';
								
								$this->session->set_flashdata('success', $success_message);
								redirect('product_category','refresh');
									
						}
					}
					else
					{
							$this->session->set_flashdata('failure', 'Failed to import Product categories.');
							redirect('product_category','refresh');
					}
				}



			
			}
			else
			{
				$data = array();
				if($this->input->is_ajax_request()) 
				{
					$response 									= array();
			  	$response['code']  					= 1;
					$response['import_product_category_modal_body'] = $this->load->view('product_category/ajax/import_product_category_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

  public function get_import_product_category()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') 
		{
		  if (isset($_FILES["csvfile"]))
		  {
        $fileTmpPath = $_FILES["csvfile"]["tmp_name"];
        $fileName = $_FILES["csvfile"]["name"];

        $csvRecords = array();
       
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) 
        {
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
          {
              /*$csvData[] = $data;*/
              $productCategoryNames[] = $data[2];
              $csvRecords[] = $data;
          }
          fclose($handle);
        }

       
       /* print_r($csvRecords);*/
       
     		// Escape the product names to avoid SQL injection
        $escapedproductCategoryNames = array_map(array($this->db, "escape_str"), $productCategoryNames);
        $inClause = "'" . implode("','", $escapedproductCategoryNames) . "'";

        // Fetch existing product names from the database
        $query = $this->db->query("SELECT * FROM product_category WHERE name IN ($inClause) AND delete_status = 0");
        //$query = $this->supplier_model->check_email_availability($inClause);

        $existingproductCategories = $query->result_array();

        // Perform the comparison
        $matchedproductCategories  = array();
        $unmatchedproductCategories  = array();

				// Return comparison result as JSON
	       foreach ($productCategoryNames as $productCategoryName) {
			        foreach ($existingproductCategories as $productcategory) {
			            if ($productcategory['name'] === $productCategoryName) {
			                $matchedproductCategories[] = $productcategory;
			            }
			        }
			    }

        
			    header('Content-Type: application/json');
					echo json_encode($matchedproductCategories);

	    } 
	    else 
	    {
	        // Handle file upload errors
          $error = $this->upload->display_errors();
          echo "Error: $error";
	    }
		}
	}


  public function product_category_delete_confirmation()
  {
  	$product_category_id 				= $this->input->post('product_category_id');
  	$data['product_category']		= $this->product_category_model->get_single_record($product_category_id);

  	$response 																		= array();
  	$response['delete_product_category_modal_body'] 	= $this->load->view('product_category/ajax/delete_product_category_modal_body',$data,TRUE);

  	echo json_encode($response);
	}

	public function ajax_list()
  {
    $list 		= $this->product_category_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

     // Edit Button        
      if($this->permission_model->has_permission('edit_product_category'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_product_category_modal" data-tt="tooltip" title="'.$this->lang->line('product_category_edit').'" class="mr-2 btn btn-info btn-xs edit_product_category_modal" data-product_category_id="'.base64_encode($item->id).'">
	                          <i class="fas fa-edit"></i> 
	                        </a>';
			}

			// Delete Button
			if($this->permission_model->has_permission('delete_product_category'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_product_category_modal" data-tt="tooltip" title="'.$this->lang->line('product_category_delete').'" class="btn btn-danger btn-xs delete_product_category_modal" data-product_category_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i> 
	                        </a>';
			}

		

			/* End Action column buttons*/

      $row = array();

     	$row[] = $item->name;
      $row[] = $item->description;
      $row[] = $item->tax_name.'<br/>'.($item->tax_type == TAX_EXCLUSIVE ? " <span class='badge badge-warning'>Exclusive</span>" : "<span class='badge badge-primary'>Inclusive</span>");
                      
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->product_category_model->count_all(),
                    "recordsFiltered" => $this->product_category_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

	public function export()
  {
    $query = $this->product_category_model->export_records();
    $this->load->dbutil();
    
    $data = $this->dbutil->csv_from_result($query);
    
    $this->load->helper('download');
    force_download("PRODUCT_CATEGORY_EXPORTED.CSV", $data);
  }

  
	
}
