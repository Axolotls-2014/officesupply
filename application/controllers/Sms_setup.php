<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_setup extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('sms_setup_model');


		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

	}
	
	public function index()
	{
		if(!$this->permission_model->has_permission('change_sms_configuration'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$old_sms_setup = $this->sms_setup_model->get_sms_records();

				$this->form_validation->set_rules('api_url','Tax name','required');		
				$this->form_validation->set_rules('route','Route','required');
				$this->form_validation->set_rules('auth_key','Auth key','required');
				$this->form_validation->set_rules('unicode','Unicode','required');
				$this->form_validation->set_rules('country','Country','required');
				$this->form_validation->set_rules('sender','Sender','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['sms_setup'] 	= $this->sms_setup_model->get_sms_records();
					$this->load->view('sms_setup/sms_setup',$data);
				}
				else
				{
					$api_url  	=  $this->input->post('api_url');
					$route  	=  $this->input->post('route');
					$auth_key  	=  $this->input->post('auth_key');
					$unicode  	=  $this->input->post('unicode');
					$country  	=  $this->input->post('country');
					$sender  	=  $this->input->post('sender');

					$data		=	array(
											"api_url"				=>	$api_url,
											"route"					=>	$route,
											"auth_key"				=>	$auth_key,
											"unicode"				=>	$unicode,
											"country"				=>	$country,
											"sender"				=>	$sender
										);

					if($this->sms_setup_model->edit_sms_setup_record($data))
					{
						$entered_sms_setup = $this->sms_setup_model->get_sms_records();

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "sms_setup",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_sms_setup),
											"data"			=> json_encode((array)$entered_sms_setup),
											"description" 	=> 'SMS setup updated successfully'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', 'SMS setup updated successfully');
						redirect('sms_setup','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', 'SMS setup failed to update');
						redirect('sms_setup','refresh');
					}
				}
				
			}
			else
			{
				
				$data['sms_setup'] 	= $this->sms_setup_model->get_sms_records();
				$this->load->view('sms_setup/sms_setup',$data);
			}
		}
	}
}
