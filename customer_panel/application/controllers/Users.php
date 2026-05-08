<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{ 

    public function __construct()
    { 
        parent::__construct();
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        } 
    } 
 
    public function profile($id = 0)
    {
        if($id == 0){
            $id = $this->session->admin_id;
        }
        $data['data']       = $this->db_model->select_multi('*','customer',array('id' => $id));
        $data['title']      = 'Home';
        $data['layout']     = 'users/profile.php';
        $this->load->view('admin/index', $data);
    }
    
}

