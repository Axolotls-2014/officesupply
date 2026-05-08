<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_tax'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{

			$this->load->view('tax/list');	
		}
	}

	public function add($id = NULL)
	{
		if(!$this->permission_model->has_permission('add_tax'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				
				$this->form_validation->set_rules("tax_name","Tax name","required");
        $this->form_validation->set_rules("sgst","Sgst","required");
        $this->form_validation->set_rules("cgst","Cgst","required");
        $this->form_validation->set_rules("igst","Igst","required");
        

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('tax/add');
				}
				else
				{	

					$tax_name = $this->input->post("tax_name");
          $sgst 		= $this->input->post("sgst");
          $cgst 		= $this->input->post("cgst");
          $igst 		= $this->input->post("igst");
          $status 	= $this->input->post("status");
          
				
					$data     = array(
                          "tax_name"=> $tax_name,
                          "sgst" 		=> $sgst,
                          "cgst" 		=> $cgst,
                          "igst" 		=> $igst,
                          "status" 	=> $status,
                          "user_id" 	=> $this->session->userdata('user_id')
                        );

					// being transaction
					$this->db->trans_begin();
					
					if($id = $this->tax_model->add_record($data))
					{

						$tid = TID_SEQUENCE+$id;

						$this->tax_model->edit_record(array('tid'=>$tid),$id);

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'Tax is added successfully.';
							$response['taxes'] 							= $this->tax_model->get_records();
						
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'tax is added successfully');
							redirect('tax','refresh');
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
							$response['message']						= 'tax is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'tax is failed to add.');
							redirect('tax','refresh');
						}
					}
				}
			}
			else
			{
				$data 											= array();

				if($this->input->is_ajax_request()) 
				{	
					$response 									= array();
					$response['code']						= 1; 						
			  	$response['add_tax_modal_body'] 	= $this->load->view('tax/ajax/add_tax_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{	
					$this->load->view('tax/add',$data);
				}
			}
		}
	}

	
	public function edit($id = null)
	{

		if(!$this->permission_model->has_permission('edit_tax'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');

				$this->form_validation->set_rules("tax_name","Tax name","required");
        $this->form_validation->set_rules("sgst","Sgst","required");
        $this->form_validation->set_rules("cgst","Cgst","required");
        $this->form_validation->set_rules("igst","Igst","required");
        
			
				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('tax/edit');
				}
				else
				{
					$tax_name = $this->input->post("tax_name");
          $sgst 		= $this->input->post("sgst");
          $cgst 		= $this->input->post("cgst");
          $igst 		= $this->input->post("igst");
          $status 	= $this->input->post("status");
          
				
					$data     = array(
                          "tax_name"=> $tax_name,
                          "sgst" 		=> $sgst,
                          "cgst" 		=> $cgst,
                          "igst" 		=> $igst,
                          "status" 	=> $status,
													"user_id" 	=> $this->session->userdata('user_id')
                        );

					if($this->tax_model->edit_record($data,$id))
					{

						$this->tax_model->edit_record_by_tax_id(array(
                          "sgst" 		=> $sgst,
                          "cgst" 		=> $cgst,
                          "igst" 		=> $igst
                        ),$id);

						if($this->input->is_ajax_request()) 
						{
							$response['code'] = 1;
							$response['message'] = 'Tax is updated successfully';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Tax is updated successfully');
							redirect('tax','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code']  		= 0;
							$response['message'] 	= 'Tax is failed to update';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Tax is failed to update');
							redirect('tax','refresh');	
						}
						
					}
				}
				
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($id);
					
					$data['tax'] 		= $this->tax_model->get_single_record($id);
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['edit_tax_modal_body'] = $this->load->view('tax/ajax/edit_tax_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['tax'] 		= $this->tax_model->get_single_record($id);

					if($data['tax'] != null)
					{
						$this->load->view('tax/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('tax/');
					}
				}
				
			}
		}
	}

	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_tax'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 							= $this->input->post('id');

			$data = array('delete_status' => 1);

			$tax =  $this->tax_model->get_single_record($id);

			if($this->tax_model->edit_record($data,$id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= 'Tax is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Tax is deleted successfully');
					redirect('tax','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Tax is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Tax is failed to delete');
					redirect('tax');
				}
			}
		}
	}

	public function import_tax()
	{
		if(!$this->permission_model->has_permission('import_tax'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
				$isUpdatetaxChecked 	= $this->input->post('update_tax') === '1';
				$isUpdateCreateNewIfExistChecked 	= $this->input->post('create_tax') === '1';

        $tax = $this->tax_model->get_records();
			
				$expectedHeaders = [
						
						'TaxName',
						'SGST(%)',
						'CGST(%)',
						'IGST(%)',
						
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
					redirect('tax','refresh');
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
									$tax_data = array(

																			'tax_name'        => $row['TaxName'],
																			'sgst'           => $row['SGST(%)'],
																			'cgst'  			   => $row['CGST(%)'],
																			'igst'           => $row['IGST(%)'],
																			'user_id' 				=> $this->session->userdata('user_id')
																			
																	);

							
								// Check whether name already exists in the database
								$exist_tax = $this->utility_model->get_records_by_field('tax','tax_name',$row['TaxName'],false,true);

								if($exist_tax == null || $isUpdateCreateNewIfExistChecked)
								{
									$this->tax_model->add_record($tax_data);
								}
								else
								{

									if($isUpdatetaxChecked)
									{
										foreach ($exist_tax as $value) {
											$this->tax_model->edit_record($tax_data,$value->id);	
										}
									}
									
								}
									
							}

							$this->session->set_flashdata('success', 'Tax imported successfully.');
							redirect('tax','refresh');
						
						}
					}
					else
					{
							$this->session->set_flashdata('failure', 'Failed to import tax.');
							redirect('tax','refresh');
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
					$response['import_tax_modal_body'] = $this->load->view('tax/ajax/import_tax_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

  public function get_import_tax()
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
              $taxNames[] = $data[0];
              $csvRecords[] = $data;
          }
          fclose($handle);
        }

       
       /* print_r($csvRecords);*/
       
     		// Escape the product names to avoid SQL injection
        $escapedtaxNames = array_map(array($this->db, "escape_str"), $taxNames);
        $inClause = "'" . implode("','", $escapedtaxNames) . "'";

        // Fetch existing product names from the database
        $query = $this->db->query("SELECT * FROM tax WHERE tax_name IN ($inClause) AND delete_status = 0");
        //$query = $this->supplier_model->check_email_availability($inClause);

        $existingproductCategories = $query->result_array();

        // Perform the comparison
        $matchedTax  = array();
        $unmatchedTax  = array();

				// Return comparison result as JSON
	       foreach ($taxNames as $taxName) {
			        foreach ($existingproductCategories as $tax) {
			            if ($tax['tax_name'] === $taxName) {
			                $matchedTax[] = $tax;
			            }
			        }
			    }

        
			    header('Content-Type: application/json');
					echo json_encode($matchedTax);

	    } 
	    else 
	    {
	        // Handle file upload errors
          $error = $this->upload->display_errors();
          echo "Error: $error";
	    }
		}
	}


  public function tax_delete_confirmation()
  {
  	$tax_id 				= $this->input->post('tax_id');
  	$data['tax']		= $this->tax_model->get_single_record($tax_id);

  	$response 																		= array();
  	$response['delete_tax_modal_body'] 	= $this->load->view('tax/ajax/delete_tax_modal_body',$data,TRUE);

  	echo json_encode($response);
	}

	public function ajax_list()
  {
    $list 		= $this->tax_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

     // Edit Button        
      if($this->permission_model->has_permission('edit_tax'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_tax_modal" data-tt="tooltip" title="'.$this->lang->line('tax_edit').'" class="btn btn-info btn-xs edit_tax_modal" data-tax_id="'.base64_encode($item->id).'">
	                          <i class="fas fa-edit"></i> Edit
	                        </a>';
			}

			// Delete Button
			if($this->permission_model->has_permission('delete_tax'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_tax_modal" data-tt="tooltip" title="'.$this->lang->line('tax_delete').'" class="btn btn-danger btn-xs delete_tax_modal" data-tax_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i> Delete
	                        </a>';
			}

		

			/* End Action column buttons*/

      //Tax Status
			$status = '';
		 	if($item->status == TAX_ACTIVE)
      {
        $status .= '<span class="badge badge-success" style="font-size:12px">'.$this->lang->line('tax_status_active').'</span>';
      }
      else if($item->status == TAX_INACTIVE)
      {
        $status .= '<span class="badge badge-danger" style="font-size:12px">'.$this->lang->line('tax_status_inactive').'</span>'; 
      }

      $row = array();

      $row[] = $item->tax_name;
      $row[] = $item->sgst;
      $row[] = $item->cgst;
      $row[] = $item->igst;
      $row[] = $status;
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->tax_model->count_all(),
                    "recordsFiltered" => $this->tax_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	
	
}
