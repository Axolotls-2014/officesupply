<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_field extends MY_Controller 
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
			if (!$this->permission_model->has_permission('edit_custom_field')) 
			{
					$this->load->view('errors/html/error_restricted');
			} 
			else 
			{
				if ($this->input->server('REQUEST_METHOD') === 'POST') 
				{
						$old_custom_fields = $this->custom_field_model->get_custom_field_records();
						
							// Extract field status data from POST
							$field_statuses = $this->input->post('field_status');
							$field_ids = $this->input->post('field_id');
							
							// Prepare data to update custom fields
							$data = array();
							foreach ($field_statuses as $field_id => $status) {
									$data[] = array(
											'id' => $field_id,
											'field_status' => $status
									);
							}
						// echo '<pre>';
						// print_r($data);
						// exit;

						// Update custom field records
						if ($this->custom_field_model->edit_custom_field_records($data)) {
								$entered_custom_fields = $this->custom_field_model->get_custom_field_records();

								$log_data = array(
										"user_id" => $this->session->userdata("user_id"),
										"module" => "custom_field",
										"user_action" => 2,
										"b_data" => json_encode($old_custom_fields),
										"data" => json_encode($entered_custom_fields),
										"description" => 'Custom fields successfully updated.'
								);
								$this->log_data_model->add_record($log_data);

								$this->session->set_flashdata('success', 'Custom fields are updated successfully');
								redirect('custom_field', 'refresh');
						} else {
								$this->session->set_flashdata('failure', 'Custom fields failed to update');
								redirect('custom_field', 'refresh');
						}
				} else {
						$data['custom_fields'] = $this->custom_field_model->get_custom_field_records();
						$this->load->view('settings/custom_field', $data);
				}
			}
	}

	public function edit()
	{
		$module_name = $this->input->get('module_name');

		if (!$this->permission_model->has_permission('edit_custom_field')) 
		{
			$this->load->view('errors/html/error_restricted');
		} 
		else 
		{
			// Check if it's a POST request
			if ($this->input->server('REQUEST_METHOD') === 'POST') 
			{
				if ($this->input->is_ajax_request()) 
				{
						// Extract field status data from POST
						$field_statuses = $this->input->post('field_status');
						$field_ids = $this->input->post('field_id');
						

						 // Debug output
						//  echo '<pre>';
						//  var_dump($field_statuses);
						//  var_dump($field_ids);
						//  exit;

						// Prepare data to update custom fields
						$data = array();
						foreach ($field_statuses as $field_id => $status) {
								$data[] = array(
										'id' => $field_id,
										'field_status' => $status
								);
						}
					// echo '<pre>';
					// print_r($data);
					// exit;

					// Update custom field records
					if ($this->custom_field_model->edit_custom_field_records($data))
					{
							// If successful, construct success response
							$response['code'] = 1;
							$response['message'] = 'Custom fields are updated successfully';
					} 
					else 
					{
							// If failed, construct failure response
							$response['code'] = 0;
							$response['message'] = 'Failed to update custom fields';
					}

					// Send JSON response
					echo json_encode($response);
				}
			} 
			else 
			{
				
				if ($this->input->is_ajax_request()) {
						
						$response = array();
						$response['code'] = 1;
						$response['custom_field_modal_body'] = $this->load->view('settings/ajax/custom_field_modal_body', array('module_name' => $module_name), TRUE);
						echo json_encode($response);
				}
			}
		}
	}





}
