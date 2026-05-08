<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{ 

    public function __construct()
    { 
        parent::__construct();
        if (empty($this->session->admin_id)) {
            redirect(site_url('auth'));
        } 
    } 
 
   public function index()
{
    $data['title']  = 'Home';
    $data['layout'] = 'dashboard.php';
    $data['products'] = $this->db->get('product')->result(); // Fetch all products
    $this->load->view('admin/index', $data);
}


    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('site_flash', '<div class="alert alert-info">You have been logged out !</div>');
        redirect(site_url('admin'));
    }
    
}

