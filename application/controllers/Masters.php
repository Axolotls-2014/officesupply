<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Masters extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');

	}
	
	public function list_sources()
   {
    
        $data['sources'] = $this->Master_model->get_records();
        
        $this->load->view('master/sources_list', $data);
    }
    
    public function add() {
    
        
        $data = [
            'name' => $this->input->post('source_name')
        ];

        $result = $this->Master_model->insert_source($data);

        if ($result) {
            $this->session->set_flashdata('success', 'Source added successfully');
        } else {
            $this->session->set_flashdata('error', 'Error adding source');
        }

        redirect('masters/list_sources'); 
    
     }
 public function add_lead_status() {
    
        
        $data = [
            'name' => $this->input->post('source_name')
        ];

        $result = $this->Master_model->insert_lead_status($data);

        if ($result) {
            $this->session->set_flashdata('success', 'Lead status added successfully');
        } else {
            $this->session->set_flashdata('error', 'Error adding lead status');
        }

        redirect('masters/list_lead_status'); 
    
     }
    public function edit() {

            $source_id = $this->input->post('source_id');
            $data = [
                'name' => $this->input->post('source_name')
            ];

            $result = $this->Master_model->update_source($source_id, $data);

           if ($result) {
            $this->session->set_flashdata('success', 'Source udated successfully');
            } else {
                $this->session->set_flashdata('error', 'Error updating source');
            }
             redirect('masters/list_sources'); 
    }

    
        public function delete() {
    
        $source_id = $this->input->post('source_id');
    
        $result = $this->Master_model->delete($source_id);
    
        if ($result) {
            $this->session->set_flashdata('success', 'Source Deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Error deleting source');
        }
    
          redirect('masters/list_sources');
    }
  public function lead_edit() {

            $source_id = $this->input->post('source_id');
            $data = [
                'name' => $this->input->post('source_name')
            ];

            $result = $this->Master_model->update_lead_status($source_id, $data);

           if ($result) {
            $this->session->set_flashdata('success', 'Lead Status udated successfully');
            } else {
                $this->session->set_flashdata('error', 'Error updating status lead');
            }
             redirect('masters/list_lead_status'); 
    }

    
        public function lead_delete() {
    
        $source_id = $this->input->post('source_id');
    
        $result = $this->Master_model->delete_lead($source_id);
    
        if ($result) {
            $this->session->set_flashdata('success', 'lead status Deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Error deleting lead status');
        }
    
          redirect('masters/list_lead_status');
    }
    	public function list_lead_status()
     {
        $data['status'] = $this->Master_model->get_status_records();
        $this->load->view('master/lead_status', $data);
     }
 }

?>
