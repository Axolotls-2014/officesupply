<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_discount'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$this->load->view('discount/list');	
		}
	}

	public function add($id = NULL)
	{

		if(!$this->permission_model->has_permission('add_discount'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				
				$this->form_validation->set_rules("name","Name","required");
        $this->form_validation->set_rules("type","Type","required");
        $this->form_validation->set_rules("value","Value","required");
        $this->form_validation->set_rules("valid_from","Valid from","required");
        $this->form_validation->set_rules("valid_to","Valid to","required");
        

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('discount/add');
				}
				else
				{	

					$name 				= $this->input->post("name");
          $type 				= $this->input->post("type");
          $value 				= $this->input->post("value");
          $valid_from 	= 	date('Y-m-d', strtotime($this->input->post('valid_from')));
					$valid_to 		= 	date('Y-m-d', strtotime($this->input->post('valid_to')));
          $description 	= $this->input->post("description");
          
				
					$data     = array(
                          "name" 				=> $name,
                          "type" 				=> $type,
                          "value" 			=> $value,
                          "valid_from" 	=> $valid_from,
                          "valid_to" 		=> $valid_to,
                          "description" => $description,
                        );

					// being transaction
					$this->db->trans_begin();
					
					if($id = $this->discount_model->add_record($data))
					{

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'Discount is added successfully.';
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Discount is added successfully');
							redirect('discount','refresh');
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
							$response['message']						= 'Discount is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Discount is failed to add.');
							redirect('discount','refresh');
						}
						
					}
				}

			}
			else
			{
				$data 											= array();

				if($this->input->is_ajax_request()) 
				{
					$response 										= array();
					$response['code']							= 1; 						
			  	$response['add_discount_modal_body'] 	= $this->load->view('discount/ajax/add_discount_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('discount/add',$data);
				}
			}
		}
	}

	
	public function edit($id = null)
	{

		if(!$this->permission_model->has_permission('edit_discount'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');

				$this->form_validation->set_rules("name","Name","required");
        $this->form_validation->set_rules("type","Type","required");
        $this->form_validation->set_rules("value","Value","required");
        $this->form_validation->set_rules("valid_from","Valid from","required");
        $this->form_validation->set_rules("valid_to","Valid to","required");

			
				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('discount/edit');
				}
				else
				{
					$name 			= $this->input->post("name");
          $type 			= $this->input->post("type");
          $value 			= $this->input->post("value");
          $valid_from	= 	date('Y-m-d', strtotime($this->input->post('valid_from')));
					$valid_to 	= 	date('Y-m-d', strtotime($this->input->post('valid_to')));
          $description = $this->input->post("description");
          
				
					$data     = array(
                          "name" => $name,
                          "type" => $type,
                          "value" => $value,
                          "valid_from" => $valid_from,
                          "valid_to" => $valid_to,
                          "description" => $description,
                        );

					if($this->discount_model->edit_record($data,$id))
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code'] = 1;
							$response['message'] = 'Discount is updated successfully';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Discount is updated successfully');
							redirect('discount','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code']  		= 0;
							$response['message'] 	= 'Discount is failed to update';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Discount is failed to update');
							redirect('discount','refresh');	
						}
						
					}
				}
				
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($id);
					
					$data['discount'] 		= $this->discount_model->get_single_record($id);
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['edit_discount_modal_body'] = $this->load->view('discount/ajax/edit_discount_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['discount'] 		= $this->discount_model->get_single_record($id);

					if($data['discount'] != null)
					{

						$this->load->view('discount/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('discount/');
					}
				}
				
			}
		}
	}

	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_discount'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 							= $this->input->post('id');

			$data = array('delete_status' => 1);

			$discount =  $this->discount_model->get_single_record($id);

			if($this->discount_model->edit_record($data,$id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= 'Discount is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Discount is deleted successfully');
					redirect('discount','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Discount is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Discount is failed to delete');
					redirect('discount');
				}
			}
		}
	}

	function get_record_detail()
	{
		$discount_id 			= $this->input->post('discount_id');
		$data['discount'] = $this->discount_model->get_single_record($discount_id);

		echo json_encode($data);
	}


  public function discount_delete_confirmation()
  {
  	$discount_id 				= $this->input->post('discount_id');
  	$data['discount']		= $this->discount_model->get_single_record($discount_id);

  	$response 																		= array();
  	$response['delete_discount_modal_body'] 	= $this->load->view('discount/ajax/delete_discount_modal_body',$data,TRUE);

  	echo json_encode($response);
	}

	public function import_discount()
	{
		if(!$this->permission_model->has_permission('import_discount'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
				$isUpdatediscountChecked 	= $this->input->post('update_discount') === '1';
				$isUpdateCreateNewIfExistChecked 	= $this->input->post('create_discount') === '1';

        $tax = $this->tax_model->get_records();
			
				$expectedHeaders = [
						'Name',
						'Type',
						'Value',
						'ValidFrom',
						'ValidTo',
						'Description',
						'Status'
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
					redirect('discount','refresh');
				}
				else
				{
					
					if(is_uploaded_file($_FILES['csvfile']['tmp_name']))
					{
						// Parse data from CSV file
						$csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);
	
						if(!empty($csvData))
						{
							
							foreach($csvData as $row)
							{ 
									$discount_data = array(
																			'name'    		=> $row['Name'],
																			'type'  			=> $row['Type'],
																			'value'    		=> $row['Value'],
																			'valid_from'  => date('Y-m-d', strtotime($row['ValidFrom'])),
																			'valid_to'    => date('Y-m-d', strtotime($row['ValidTo'])),
																			'description' => $row['Description'],
																			'status'  		=> $row['Status'],
																			'user_id' 		=> $this->session->userdata('user_id')
																	);

							
								// Check whether name already exists in the database
								$exist_discount = $this->utility_model->get_records_by_field('discount','name',$row['Name'],false,true);

								if($exist_discount == null || $isUpdateCreateNewIfExistChecked)
								{
									$this->discount_model->add_record($discount_data);
								}
								else
								{

									if($isUpdatediscountChecked)
									{
										foreach ($exist_discount as $value) {
											$this->discount_model->edit_record($discount_data,$value->id);	
										}
									}
									
								}
									
							}

							$this->session->set_flashdata('success', 'Discount imported successfully.');
							redirect('discount','refresh');
						
						}
					}
					else
					{
							$this->session->set_flashdata('failure', 'Failed to import discount.');
							redirect('discount','refresh');
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
					$response['import_discount_modal_body'] = $this->load->view('discount/ajax/import_discount_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

  public function get_import_discount()
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
              $discountNames[] = $data[0];
              $csvRecords[] = $data;
          }
          fclose($handle);
        }

       
       /* print_r($csvRecords);*/
       
     		// Escape the product names to avoid SQL injection
        $escapeddiscountNames = array_map(array($this->db, "escape_str"), $discountNames);
        $inClause = "'" . implode("','", $escapeddiscountNames) . "'";

        // Fetch existing product names from the database
        $query = $this->db->query("SELECT * FROM discount WHERE name IN ($inClause) AND delete_status = 0");
        //$query = $this->supplier_model->check_email_availability($inClause);

        $existingDiscount  = $query->result_array();

        // Perform the comparison
        $matchedDiscount  = array();
        $unmatchedDiscount  = array();

				// Return comparison result as JSON
	       foreach ($discountNames as $discountName) {
			        foreach ($existingDiscount as $discount) {
			            if ($discount['name'] === $discountName) {
			                $matchedDiscount[] = $discount;
			            }
			        }
			    }

        
			    header('Content-Type: application/json');
					echo json_encode($matchedDiscount);

	    } 
	    else 
	    {
	        // Handle file upload errors
          $error = $this->upload->display_errors();
          echo "Error: $error";
	    }
		}
	}


	public function ajax_list()
  {
    $list 		= $this->discount_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

     // Edit Button        
      if($this->permission_model->has_permission('edit_discount'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_discount_modal" data-tt="tooltip" title="'.$this->lang->line('discount_edit').'" class="btn btn-info btn-xs edit_discount_modal" data-discount_id="'.base64_encode($item->id).'">
	                          <i class="fas fa-edit"></i> Edit
	                        </a>';
			}

			// Delete Button
			if($this->permission_model->has_permission('delete_discount'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_discount_modal" data-tt="tooltip" title="'.$this->lang->line('discount_delete').'" class="btn btn-danger btn-xs delete_discount_modal" data-discount_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i> Delete
	                        </a>';
			}

		

			/* End Action column buttons*/

      //Discount Status
			$status = '';
		 	if($item->status == 1)
      {
        $status .= '<span class="badge badge-success" style="font-size:12px">'.$this->lang->line('tax_status_active').'</span>';
      }
      else
      {
        $status .= '<span class="badge badge-danger" style="font-size:12px">'.$this->lang->line('tax_status_inactive').'</span>'; 
      }


				
        $row = array();
       
        $row[] = $item->name;
	      $row[] = ($item->type == 0) ? 'Fixed' : 'Percentage';
	      $row[] = $item->value;
        $row[] = date('d-m-Y', strtotime($item->valid_from));
        $row[] = date('d-m-Y', strtotime($item->valid_to));
        $row[] = $item->description;
        $row[] = $status;
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->discount_model->count_all(),
                    "recordsFiltered" => $this->discount_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	
	
}
