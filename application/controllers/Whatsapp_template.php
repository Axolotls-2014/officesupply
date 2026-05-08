<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_template extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_whatsapp_template'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['whatsapp_templates'] = $this->whatsapp_template_model->get_records();
      $this->load->view('whatsapp_template/index',$data);
    }
  }

  public function add($wm_id = null)
  {

    $this->config->load('custom_config');
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('wm_type','Type','required'); 
      $this->form_validation->set_rules('wm_message','Message','required');        
      
      
      if($this->form_validation->run()==FALSE)
      {   
        if($this->input->is_ajax_request())
        {
          $data['code'] = 2;
          $data['errors'] = $this->form_validation->error_array();
          echo json_encode($data);
        }
        else
        { 
          $data['whatsapp_template'] = null;
          if($wm_id != null)
            $data['whatsapp_template'] = $this->whatsapp_template_model->get_single_record($wm_id);

          $this->load->view('whatsapp_template/add',$data);
        }
      }
      else
      {
        $wm_type      = $this->input->post('wm_type');
        $wm_message   =  $this->input->post('wm_message');
       
        $this->db->trans_begin();

        $data       =   array(
                              "wm_type"    =>  $wm_type,
                              "wm_message" =>  $wm_message
                            );

        if($wm_id == NULL)
        {
          if($id = $this->whatsapp_template_model->add_record($data))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Whatsapp template is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Whatsapp template is added successfully');
              redirect('whatsapp_template','refresh');  
            }
          }
          else
          {
            // rollback transaction
            $this->db->trans_rollback();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_FAILURE;
              $response['message'] = 'Whatsapp template is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Whatsapp template is failed to add.');
              redirect('whatsapp_template','refresh');
            }
          }   
        }
        else
        {
          if($this->whatsapp_template_model->edit_record($data,$wm_id))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Whatsapp template is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Whatsapp template is updated successfully');
              redirect('whatsapp_template','refresh');
            }
          }
          else
          {
            // rollback transaction
            $this->db->trans_rollback();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_FAILURE;
              $response['message'] = 'Whatsapp template is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Whatsapp template is failed to update.');
              redirect('whatsapp_template','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['whatsapp_templates'] = $this->whatsapp_template_model->get_records();
      $data['whatsapp_template'] = null;

      if($wm_id != null)
      {
        $data['whatsapp_template']       = $this->whatsapp_template_model->get_single_record($wm_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_whatsapp_template_modal_body'] = $this->load->view('whatsapp_template/ajax/add_whatsapp_template_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_whatsapp_template_modal_body'] = $this->load->view('whatsapp_template/ajax/add_whatsapp_template_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_whatsapp_template'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$wm_id 	= $this->input->post('wm_id');

			$data = array('delete_status' => 1);

			$whatsapp_template =  $this->whatsapp_template_model->get_single_record($wm_id);
    

			if($this->whatsapp_template_model->edit_record($data,$wm_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $wm_id;
					$response['message']			= 'Whatsapp template is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Whatsapp template is deleted successfully');
					redirect('whatsapp_template','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Whatsapp template is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Whatsapp template is failed to delete');
					redirect('whatsapp_template');
				}
			}
		}
	}

  function get_record_detail_by_type($wm_type = null)
  {
    $response = array();

    if($wm_type != null)
    {
      $template = $this->whatsapp_template_model->get_template_by_type($wm_type);

      if ($template) 
      {
        $response['code'] = 1;
        $response['message'] = $template->wm_message;
      } 
      else {
        $response['code'] = 0;
        $response['message'] = 'Template not found !';          
      }

      echo json_encode($response);
    }
  }

  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->whatsapp_template_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //View Button
      if($this->permission_model->has_permission('edit_whatsapp_template'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('whatsapp_template_edit').'" class="btn btn-info btn-xs add_whatsapp_template_modal" data-wm_id="'.$item->wm_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_whatsapp_template'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_whatsapp_template" data-tt="tooltip" title="'.$this->lang->line('whatsapp_template_delete').'" class="btn btn-danger btn-xs delete_whatsapp_template" data-wm_id="'.$item->wm_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }

   
          

      $row = array();
   
   
      $row[] = clean_e_val($item->wm_type);
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->whatsapp_template_model->count_all(),
                    "recordsFiltered"   => $this->whatsapp_template_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function whatsapp_template_delete_confirmation()
  {
  	$wm_id 					= $this->input->post('wm_id');
  	$data['whatsapp_template'] 		= $this->whatsapp_template_model->get_single_record($wm_id);
   
  	$response 					    = array();
  	$response['whatsapp_template_delete_modal_body'] 	= $this->load->view('whatsapp_template/ajax/whatsapp_template_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

 
}
