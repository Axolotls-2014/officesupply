<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utility extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}
	}

	public function get_countries()
	{
		$data = $this->utility_model->get_countries();
		print_r(json_encode($data,true));
	}

	public function get_states($country_id)
	{
		$data = $this->utility_model->get_states($country_id);
		print_r(json_encode($data,true));
	}

	public function get_cities($state_id)
	{
		$data = $this->utility_model->get_cities($state_id);
		print_r(json_encode($data,true));
	}
}
