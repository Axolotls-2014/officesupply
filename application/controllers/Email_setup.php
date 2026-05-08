<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_setup extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('email_setup_model');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

	}
	
	public function index()
	{
		if(!$this->permission_model->has_permission('change_email_configuration'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('protocol','Protocol','required');		
				$this->form_validation->set_rules('encryption','Encryption','required');
				$this->form_validation->set_rules('host','Host','required');
				$this->form_validation->set_rules('port','Port','required');
				$this->form_validation->set_rules('email','Email','required');
				$this->form_validation->set_rules('username','Username','required');
				$this->form_validation->set_rules('password','Password','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['email_setup'] 	= $this->email_setup_model->get_email_records();
					$this->load->view('email_setup/email_setup',$data);
				}
				else
				{
					$old_email_setup = $this->email_setup_model->get_email_records();

					$protocol 		= 	$this->input->post('protocol');
					$encryption 	= 	$this->input->post('encryption');
					$host 			= 	$this->input->post('host');
					$port 			= 	$this->input->post('port');
					$email 			= 	$this->input->post('email');
					$username 		= 	$this->input->post('username');
					$password 		= 	$this->input->post('password');


					$data			=	array(
												"protocol"			=>	$protocol,
												"encryption"		=>	$encryption,
												"host"				=>  $host ,
												"port"				=>	$port ,
												"email"				=>  $email ,
												"username"			=>	$username,
												"password"			=>	$password
											);

					if($this->email_setup_model->edit_email_setup_record($data,1))
					{
						$entered_email_setup = $this->email_setup_model->get_email_records();

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "email_setup",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_email_setup),
											"data"			=> json_encode((array)$entered_email_setup),
											"description" 	=> 'Email setup updated successfully'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', 'Email setup updated successfully');
						redirect('email_setup','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', 'Email setup failed to update');
						redirect('email_setup','refresh');
					}
				}
				
			}
			else
			{
				$data['email_setup'] 	= $this->email_setup_model->get_email_records();
				$this->load->view('email_setup/email_setup',$data);
			}
		}
	}
}
