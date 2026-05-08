<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends MY_Controller 
{
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
		if($this->input->is_ajax_request())
		{
			$response 					= array();
			$response['services']			= $this->service_model->get_records();
			echo json_encode($response);	
		}
		else
		{
			if(!$this->permission_model->has_permission('list_service'))
			{
				$this->load->view('errors/html/error_restricted'); 
			}
			else
			{
				$data['service'] = $this->service_model->get_records();

				$log_data = array(
                  "user_id"     => $this->session->userdata("user_id"),
                  "module"      => "service",
                  "user_action" => 0,
                  "description"	=> "User has viewed list of service."
                );

				$this->log_data_model->add_record($log_data);

				$this->load->view('service/list',$data);
			}
		}
	}

	public function search($search_term = null)
	{
		$data = $this->service_model->search_record($search_term);
		print_r(json_encode($data,true));
	}

	public function get_record_detail($service_id)
	{
		$data['service'] 	= $this->service_model->get_single_record($service_id);
		$data['discount']	= $this->discount_model->get_records("valid_discount");
		$data['query']		= $this->db->last_query();
		print_r(json_encode($data,true));
	}

	public function add()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$this->form_validation->set_rules('name','Name','required');		
			$this->form_validation->set_rules('description','Description','required');
			// $this->form_validation->set_rules('sac_code','SAC Code','required');
			$this->form_validation->set_rules('tax_id','Tax','required');

			if($this->form_validation->run()==FALSE)
			{
				$data['taxes'] = $this->tax_model->get_tax_records();
				$this->load->view('service/add',$data);
			}
			else
			{
				$name 			= $this->input->post('name');
				$description 	= $this->input->post('description');
				$tax_id 		= $this->input->post('tax_id');
				$sac_code 		= $this->input->post('sac_code');
				$price 			= $this->input->post('price');
				$tax_type 		= $this->input->post('tax_type');

				if($tax_type == null)
				{
					$tax_type = 0;
				}

				$data			=	array(
											"name"			=>	$name,
											"tax_id"		=>	$tax_id,
											"sac_code"		=>	$sac_code,
											"description"	=>	$description,
											"price"			=>	$price,
											"tax_type"		=>	$tax_type
										);
				if($id = $this->service_model->add_record($data))
				{
					$entered_service = $this->service_model->get_single_record($id);

					$log_data = array(
										"user_id" 		=> $this->session->userdata("user_id"),
										"module"		=> "service",
										"entry_id"		=> $id,
										"user_action"	=> 1,
										"data"			=> json_encode((array)$entered_service),
										"description" 	=> $data['name'].' successfully added.'
									);
					$this->log_data_model->add_record($log_data);

					if($this->input->is_ajax_request())
					{	
						$response 						= array();
						$response['code'] 				= 1;
						$response['id'] 				= $id;
						$response['message']			= $name.' is added successfully.';
						$response['services']			= $this->service_model->get_records();

						echo json_encode($response);
					}
					else
					{
						$this->session->set_flashdata('success', $name.' is added successfully');
						redirect('service','refresh');
					}
				}
				else
				{
					if($this->input->is_ajax_request())
					{	
						$response 						= array();
						$response['code'] 				= 0;
						$response['message']			= $name.' is failed to add.';
					
						echo json_encode($response);
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to add');
						redirect('service','refresh');
					}
				}
			}		
		}
		else
		{
			if(!$this->permission_model->has_permission('add_service'))
			{
				$this->load->view('errors/html/error_restricted'); 
			}
			else
			{
				$data['taxes'] = $this->tax_model->get_tax_records();
				$this->load->view('service/add',$data);
			}
		}
		
	}

	public function edit($id = null)
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$id = $this->input->post('id');
			$old_service = $this->service_model->get_single_record($id);

			$this->form_validation->set_rules('name','Name','required');		
			$this->form_validation->set_rules('description','Description','required');

			if($this->form_validation->run()==FALSE)
			{
				$this->load->view('service/edit');
			}
			else
			{
				
				$name 				= $this->input->post('name');
				$description 	= $this->input->post('description');
				$tax_id 			= $this->input->post('tax_id');
				$sac_code 		= $this->input->post('sac_code');
				$price 				= $this->input->post('price');
				$tax_type 		= $this->input->post('tax_type');

				$data			=	array(
													"name"				=>	$name,
													"tax_id"			=>	$tax_id,
													"sac_code"		=>	$sac_code,
													"description"	=>	$description,
													"price"				=>	$price,
													"tax_type"		=>	$tax_type
												);
			
				if($this->service_model->edit_record($data,$id))
				{
					$entered_service = $this->service_model->get_single_record($id);

					$log_data = array(
														"user_id" 		=> $this->session->userdata("user_id"),
														"module"			=> "service",
														"user_action"	=> 2,
														"b_data"			=> json_encode((array)$old_service),
														"data"				=> json_encode((array)$entered_service),
														"description" => $name.' successfully updated.'
													);
					$this->log_data_model->add_record($log_data);

					$this->session->set_flashdata('success', $name.' is updated successfully');
					redirect('service','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', $name.' is failed to update');
					redirect('service','refresh');
				}
			}

		}
		else
		{
			if(!$this->permission_model->has_permission('edit_service'))
			{
				$this->load->view('errors/html/error_restricted'); 
			}
			else
			{
				if($id != null)
				{
					$data['service'] 	= $this->service_model->get_single_record($id);
					$data['taxes'] 		= $this->tax_model->get_tax_records();
					$this->load->view('service/edit',$data);
				}
				else
				{
					redirect('service','refresh');
				}
			}
		}
	}

	function delete($id)
	{

		if(!$this->permission_model->has_permission('delete_service'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data = array(
							'delete_status' => 1
						);
			$service = $this->service_model->get_single_record($id);



			if($this->service_model->edit_record($data,$id))
			{
				$log_data = array(
		                      "user_id"     => $this->session->userdata("user_id"),
		                      "module"      => "service",
		                      "user_action" => 3,
		                      "data"        => json_encode($data),
		                      "description" => $service->name.' deleted successfully.'
		                    );
          	 $this->log_data_model->add_record($log_data);    

				$this->session->set_flashdata('success', $service->name.' is deleted successfully');
				redirect('service','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', $service->name.' is failed to delete');
				redirect('service','refresh');
			}
		}
	}
		
}
