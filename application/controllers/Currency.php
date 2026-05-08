<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_currency'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['currency'] = $this->currency_model->get_records();

			$log_data = array(
                  "user_id"     => $this->session->userdata("user_id"),
                  "module"      => "currency",
                  "user_action" => 0,
                  "description"	=> "User has viewed list of currency."
                );

			$this->log_data_model->add_record($log_data);

			$this->load->view('currency/list',$data);
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_currency'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('name','Name','required');		
				$this->form_validation->set_rules('symbol','Symbol','required');

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('currency/add');
				}
				else
				{
					$name 		= 	$this->input->post('name');
					$symbol 	= 	$this->input->post('symbol');

					$data		=	array(
											"name"		=> 	$name,
											"symbol"	=>	$symbol
									);
				
					if($id = $this->currency_model->add_record($data))
					{
						$entered_currency = $this->currency_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "currency",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"			=> json_encode((array)$entered_currency),
											"description" 	=> $name.' successfully added.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is added successfully');
						redirect('currency','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to add.');
						redirect('currency','refresh');
					}
				}
			}
			else
			{
				$this->load->view('currency/add');
			}
		}
		
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_currency'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{

				$id = $this->input->post('id');
				$old_currency = $this->currency_model->get_single_record($id);

				$this->form_validation->set_rules('name','Name','required');		
				$this->form_validation->set_rules('symbol','Symbol','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['currency'] = $this->currency_model->get_single_record($id);
					$this->load->view('currency/edit',$data);
				}
				else
				{
					$name 		= 	$this->input->post('name');
					$symbol 	= 	$this->input->post('symbol');

					$data		=	array(
											"name"		=> 	$name,
											"symbol"	=>	$symbol
									);

				
					if($this->currency_model->edit_record($data,$id))
					{
						$entered_currency = $this->currency_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "currency",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_currency),
											"data"			=> json_encode((array)$entered_currency),
											"description" 	=> $name.' successfully updated.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is updated successfully.');
						redirect('currency','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to update.');
						redirect('currency','refresh');
					}
				}
			}
			else
			{
				$id = base64_decode($id);

				if($id != null)
				{
					$data['currency'] = $this->currency_model->get_single_record($id);

					if($data['currency'] != null)
					{
						$this->load->view('currency/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('currency');
					}
					
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('currency');
				}
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_currency'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$data = array('delete_status' => 1);
			$currency = $this->currency_model->get_single_record($id);

			if($this->currency_model->edit_record($data,$id))
			{
				$log_data = array(
		                      "user_id"     => $this->session->userdata("user_id"),
		                      "module"      => "currency",
		                      "user_action" => 3,
		                      "data"        => json_encode($data),
		                      "description" => $currency->name.' deleted successfully.'
		                    );
          		$this->log_data_model->add_record($log_data);     

				$this->session->set_flashdata('success', $currency->name.' is deleted successfully.');
				redirect('currency','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', $currency->name.' failed to delete.');
				redirect('currency','refresh');
			}
		}
	}

	public function import_currency()
	{
		if(!$this->permission_model->has_permission('import_currency'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{	
				$isUpdatecurrencyChecked 	= $this->input->post('update_currency') === '1';
				$isUpdateCreateNewIfExistChecked 	= $this->input->post('create_currency') === '1';

        $tax = $this->tax_model->get_records();
			
				$expectedHeaders = [
						
						'CurrencyName',
						'Symbol',
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
					redirect('currency','refresh');
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
									$currency_data = array(
																			'name'    => $row['CurrencyName'],
																			'symbol'  => $row['Symbol']
																	);

							
								// Check whether name already exists in the database
								$exist_currency = $this->utility_model->get_records_by_field('currency','name',$row['CurrencyName'],false,true);

								if($exist_currency == null || $isUpdateCreateNewIfExistChecked)
								{
									$this->currency_model->add_record($currency_data);
								}
								else
								{

									if($isUpdatecurrencyChecked)
									{
										foreach ($exist_currency as $value) {
											$this->currency_model->edit_record($currency_data,$value->id);	
										}
									}
									
								}
									
							}

							$this->session->set_flashdata('success', 'Currency imported successfully.');
							redirect('currency','refresh');
						
						}
					}
					else
					{
							$this->session->set_flashdata('failure', 'Failed to import currency.');
							redirect('currency','refresh');
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
					$response['import_currency_modal_body'] = $this->load->view('currency/ajax/import_currency_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
		}
	}

  public function get_import_currency()
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
              $currencyNames[] = $data[0];
              $csvRecords[] = $data;
          }
          fclose($handle);
        }

       
       /* print_r($csvRecords);*/
       
     		// Escape the product names to avoid SQL injection
        $escapedcurrencyNames = array_map(array($this->db, "escape_str"), $currencyNames);
        $inClause = "'" . implode("','", $escapedcurrencyNames) . "'";

        // Fetch existing product names from the database
        $query = $this->db->query("SELECT * FROM currency WHERE name IN ($inClause) AND delete_status = 0");
        //$query = $this->supplier_model->check_email_availability($inClause);

        $existingCurrency  = $query->result_array();

        // Perform the comparison
        $matchedCurrency  = array();
        $unmatchedCurrency  = array();

				// Return comparison result as JSON
	       foreach ($currencyNames as $currencyName) {
			        foreach ($existingCurrency as $currency) {
			            if ($currency['name'] === $currencyName) {
			                $matchedCurrency[] = $currency;
			            }
			        }
			    }

        
			    header('Content-Type: application/json');
					echo json_encode($matchedCurrency);

	    } 
	    else 
	    {
	        // Handle file upload errors
          $error = $this->upload->display_errors();
          echo "Error: $error";
	    }
		}
	}

	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->currency_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
				$table_header = '<div class="btn-group">';
				$table_footer = '</div>';
				$table_body   = '';

				// Edit Button        
        if($this->permission_model->has_permission('edit_currency'))
				{	
					$table_body .= '<a href="'.base_url('currency/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" data-tt="tooltip" title="'.$this->lang->line('currency_edit').'">
														<i class="fas fa-edit"></i> Edit
													</a>';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_currency'))
				{	
					$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_currency" data-tt="tooltip" title="'.$this->lang->line('currency_delete').'" class="btn btn-danger btn-xs delete_currency" data-currency_id="'.$item->id.'">
														<i class="fas fa-trash"></i> Delete
													</a>';
				}

			

				/* End Action column buttons*/

        $row = array();
       
        $row[] = $item->name;
        $row[] = $item->symbol;
        
      	$row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->currency_model->count_all(),
                    "recordsFiltered" => $this->currency_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function currency_delete_confirmation()
  {
  	$currency_id 					= $this->input->post('currency_id');
  	$data['currency'] 		= $this->currency_model->get_single_record($currency_id);

  	$response 					= array();
  	$response['currency_delete_modal_body'] 	= $this->load->view('currency/ajax/currency_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/

		
}
