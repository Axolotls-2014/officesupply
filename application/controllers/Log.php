<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends MY_Controller {

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
        $user_id = $this->session->userdata('user_id');
        $brands = $this->db->get('log_data')->result();
        $data = [
            'brands' => $brands
        ];

        $this->load->view('clients/log_list', $data);
     }
  


}