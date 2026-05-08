<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Application_settings extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	public function index()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$old_application_setting = $this->application_settings_model->get_application_records();

			$this->form_validation->set_rules('app_name','App name','required');		
			// $this->form_validation->set_rules('app_version','App version','required');
			// $this->form_validation->set_rules('app_language','App language','required');
			// $this->form_validation->set_rules('app_timezone','App timezone','required');
			// $this->form_validation->set_rules('sidebar_menu_text_size','Sidebarmenu text size','required');
			// $this->form_validation->set_rules('sidebar_menu_flat_style','Sidebarmenu flat style','required');
			// $this->form_validation->set_rules('sidebar_nav_legacy_style','Sidebarnav legacy style','required');
			// $this->form_validation->set_rules('sidebar_nav_compact','Sidebarnav compact','required');
			// $this->form_validation->set_rules('application_text_size','Application text size','required');

			if($this->form_validation->run()==FALSE)
			{
				$data['application_settings'] 	= $this->application_settings_model->get_application_records();
				$this->load->view('application_settings/application_settings',$data);
			}
			else
			{
				$app_name 					= 	$this->input->post('app_name');
				$app_version 				= 	$this->input->post('app_version');
				// $app_language 				= 	$this->input->post('app_language');
				// $app_timezone 				= 	$this->input->post('app_timezone');
				$sidebar_menu_text_size 	= 	$this->input->post('sidebar_menu_text_size');
				$sidebar_menu_flat_style	= 	$this->input->post('sidebar_menu_flat_style');
				$sidebar_nav_legacy_style 	= 	$this->input->post('sidebar_nav_legacy_style');
				$application_text_size 		= 	$this->input->post('application_text_size');
				$sidebar_theme 				=	$this->input->post('sidebar_theme');
				$navbar_theme 				= 	$this->input->post('navbar_theme');

				$data						=	array(
														"app_name"							=>	$app_name ,
														"app_version"						=>	$app_version,
														// "app_language"						=>	$app_language,
														// "app_timezone"						=>	$app_timezone,
														"sidebar_menu_text_size"			=>	$sidebar_menu_text_size,
														"sidebar_menu_flat_style"			=>	$sidebar_menu_flat_style,
														"sidebar_nav_legacy_style"			=>	$sidebar_nav_legacy_style,
														"application_text_size"				=>	$application_text_size,
														"sidebar_theme"						=>  $sidebar_theme ,
														"navbar_theme"						=>  $navbar_theme 
													);

				if($this->application_settings_model->edit_record($data))
				{
					$entered_application_setting = $this->application_settings_model->get_application_records();

					$log_data = array(
														"user_id" 		=> $this->session->userdata("user_id"),
														"module"			=> "application_setting",
														"user_action"	=> 2,
														"b_data"			=> json_encode((array)$old_application_setting),
														"data"				=> json_encode((array)$entered_application_setting),
														"description" => $app_name.' successfully updated.'
													);
					$this->log_data_model->add_record($log_data);

					$this->session->set_flashdata('success', $app_name.' is updated successfully');
					redirect('application_settings','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', $app_name.' is failed to update');
					redirect('application_settings','refresh');
				}
			}
		}
		else
		{	
			if($this->input->is_ajax_request())
			{
				$response = array();
				$response['application_settings'] = $this->application_settings_model->get_application_records();

				echo json_encode($response);
			}
			else
			{
				$data['application_settings'] 	= $this->application_settings_model->get_application_records();
				$this->load->view('application_settings/application_settings',$data);	
			}
		}
	}

	public function activate()
	{
		$response 			= array();

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$activation_key = $this->input->post('activation_key');
			$data 					= array('activation_key' => $activation_key);
			$app_code 			= $this->application_settings_model->get_application_records()->app_code;

    	if($this->application_settings_model->edit_record($data))
			{
				$SEPERATOR = $this->utility_model->get_seperator();
				if(file_exists(APPPATH.'views'.$SEPERATOR.'layout'.$SEPERATOR.'footer-backup.php'))
				{
					copy(APPPATH.'views'.$SEPERATOR.'layout'.$SEPERATOR.'footer-backup.php', APPPATH.'views'.$SEPERATOR.'layout'.$SEPERATOR.'footer.php');
					unlink(APPPATH.'views'.$SEPERATOR.'layout'.$SEPERATOR.'footer-backup.php');
				}
				$response['code'] 		= 1;
				$response['message'] 	= 'Activation Key is successfully updated to system.';
			}
			else
			{
				$response['code'] 		= 0;
				$response['message'] 	= 'Activation Key is failed to update in system.';
			}

			echo json_encode($response);
		}
		else
		{
			$response['code'] 		= 3;
			$response['message'] 	= 'Invalid request';

			echo json_encode($response);	
		}
	}

	function clean_dummy_data()
	{
		if($this->application_settings_model->clean_dummy_data())
		{
			$this->session->set_flashdata('success', 'Dummy data from System is cleared.');
			redirect('auth/dashboard','refresh');
		}
	}

	function restore_dummy_data()
	{
		if($this->application_settings_model->restore_database_for_teseting())
		{
			$this->session->set_flashdata('success', 'Dummy data has been restored successfully.');
			redirect('auth/dashboard','refresh');	
		}
	}

	function update_application()
	{
		$SEPERATOR 	= $this->utility_model->get_seperator();

		$myfiles 		= scandir(FCPATH.$SEPERATOR.'assets'.$SEPERATOR.'update');

		if(sizeof($myfiles) > 2)
		{
			$update_file_path_array = explode(".", $myfiles[2]);

			$update_file_path = '';
			$sql_file_path 		= '';

			if($update_file_path_array[2] == 'zip')
				$update_file_path 	= FCPATH.$SEPERATOR.'assets'.$SEPERATOR.'update'.$SEPERATOR.$myfiles[2];
			else if($update_file_path_array[2] == 'sql')
				$sql_file_path 			= FCPATH.$SEPERATOR.'assets'.$SEPERATOR.'update'.$SEPERATOR.$myfiles[2];

			if($update_file_path_array[3] == 'zip')
				$update_file_path 	= FCPATH.$SEPERATOR.'assets'.$SEPERATOR.'update'.$SEPERATOR.$myfiles[2];
			else if($update_file_path_array[3] == 'sql')
				$sql_file_path 			= FCPATH.$SEPERATOR.'assets'.$SEPERATOR.'update'.$SEPERATOR.$myfiles[2];

			$zip 				= new ZipArchive;
	   	$res 				= $zip->open($update_file_path);

	   	$response 	= array();

	   	if ($res === TRUE) 
	   	{
				$zip->extractTo(FCPATH.$SEPERATOR.'application');
		   	$zip->close();

		   	unlink($update_file_path);

		   	$this->session->set_flashdata('success', 'Application is updated successfully.');
				redirect('application_settings','refresh');
	   	}
	   	else
	   	{
	   		$this->session->set_flashdata('failure', 'Application is failed to update. Please contact the develper.');
				redirect('application_settings','refresh');		
	   	}	
		}

		
	}
}
