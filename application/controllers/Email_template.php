<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_template extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    if (!$this->ion_auth->logged_in())
    {
      redirect('login', 'refresh');
    }
    
  }

  public function index()
  {
    if(!$this->permission_model->has_permission('list_email_template'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['email_templates'] = $this->email_template_model->get_records();
      $this->load->view('email_template/list',$data);
    }
     
  }

  public function add($email_template_id = null)
  {
    if(!$this->permission_model->has_permission('add_email_template'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      if($this->input->server('REQUEST_METHOD') === 'POST')
      {
        $this->form_validation->set_rules('template_name','Template Name','required');
        $this->form_validation->set_rules('from_name','From Name','required');        
        $this->form_validation->set_rules('from_email','From Email','required');
        $this->form_validation->set_rules('module','Module','required');        
        $this->form_validation->set_rules('subject','Subject','required');  
        $this->form_validation->set_rules('mail_body','Mail Body','required');  
        
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
            $data['email_template'] = null;
            if($email_template_id != null)
              $data['email_template'] = $this->email_template_model->get_single_record($email_template_id);

            $this->load->view('email_template/add',$data);
          }
        }
        else
        {
          $template_name    = $this->input->post('template_name');
          $from_name        = $this->input->post('from_name');
          $from_email       = $this->input->post('from_email');
          $module           = $this->input->post('module');
          $subject          = $this->input->post('subject');
          $mail_header      = $this->input->post('mail_header');
          $mail_body        = $this->input->post('mail_body');
          $mail_footer      = $this->input->post('mail_footer');

          $this->db->trans_begin();

          $data       =   array(
                                "template_name" =>  $template_name,
                                "from_name"     =>  $from_name,
                                "from_email"    =>  $from_email,
                                "module"        =>  $module,
                                "subject"       =>  $subject,
                                "mail_header"   =>  $mail_header,
                                "mail_body"     =>  $mail_body,
                                "mail_footer"   =>  $mail_footer
                              );

       
          if($email_template_id == NULL)
          {
            if($id = $this->email_template_model->add_record($data))
            {
              // commit transaction
              $this->db->trans_commit();

              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_SUCCESS;
                $response['message'] = 'Email template is added successfully';

                echo json_encode($response);  
              }
              else
              {
                $this->session->set_flashdata('success', 'Email template is added successfully');
                redirect('email_template','refresh');  
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
                $response['message'] = 'Email template is failed to add.';

                echo json_encode($response);  
              }
              else
              {
                $this->session->set_flashdata('failure', 'Email template is failed to add.');
                redirect('email_template','refresh');
              }
            }   
          }
          else
          {
            if($this->email_template_model->edit_record($data,$email_template_id))
            {
              // commit transaction
              $this->db->trans_commit();

              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_SUCCESS;
                $response['message'] = 'Email template is updated successfully';

                echo json_encode($response);  
              }
              else
              { 
                $this->session->set_flashdata('success', 'Email template is updated successfully');
                redirect('email_template','refresh');
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
                $response['message'] = 'Email Template is failed to update.';

                echo json_encode($response);  
              }
              else
              { 
                $this->session->set_flashdata('failure', 'Email Template is failed to update.');
                redirect('email_template','refresh');
              }
            }   
          }
        }
      }
      else
      {
        $data['email_templates'] = $this->email_template_model->get_records();
        $data['email_template'] = null;

        if($email_template_id != null)
        {
          $data['email_template']       = $this->email_template_model->get_single_record($email_template_id);
          
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['add_email_template_modal_body'] = $this->load->view('email_template/ajax/add_email_template_modal_body',$data,TRUE);
          echo json_encode($response);
        }
        else
        {
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['add_email_template_modal_body'] = $this->load->view('email_template/ajax/add_email_template_modal_body',$data,TRUE);
          echo json_encode($response);
        }
      }
    }

    
  }

  public function copy($email_template_id = null)
  {
    if(!$this->permission_model->has_permission('add_email_template'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      if($this->input->server('REQUEST_METHOD') === 'POST')
      {
        $this->form_validation->set_rules('template_name','Template Name','required');
        $this->form_validation->set_rules('from_name','From Name','required');        
        $this->form_validation->set_rules('from_email','From Email','required');
        $this->form_validation->set_rules('module','Module','required');        
        $this->form_validation->set_rules('subject','Subject','required');  
        $this->form_validation->set_rules('mail_body','Mail Body','required');  

        $module = $this->input->post('module');

        if ($this->email_template_model->is_module_exists($module, $email_template_id)) {
          // Module already exists, return error
          $response = array(
              'code' => 3,
              'message' => 'Email Template for this module already exists.'
          );
          echo json_encode($response);
          return; // Stop further execution
      }

        
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
            $data['email_template'] = null;
            if($email_template_id != null)
              $data['email_template'] = $this->email_template_model->get_single_record($email_template_id);

            $this->load->view('email_template/add',$data);
          }
        }
        else
        {
          $template_name    = $this->input->post('template_name');
          $from_name        = $this->input->post('from_name');
          $from_email       = $this->input->post('from_email');
          $module           = $this->input->post('module');
          $subject          = $this->input->post('subject');
          $mail_header      = $this->input->post('mail_header');
          $mail_body        = $this->input->post('mail_body');
          $mail_footer      = $this->input->post('mail_footer');

          $this->db->trans_begin();

          $data       =   array(
                                "template_name" =>  $template_name,
                                "from_name"     =>  $from_name,
                                "from_email"    =>  $from_email,
                                "module"        =>  $module,
                                "subject"       =>  $subject,
                                "mail_header"   =>  $mail_header,
                                "mail_body"     =>  $mail_body,
                                "mail_footer"   =>  $mail_footer
                              );

       
          if($id = $this->email_template_model->add_record($data))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Email template is copied successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Email template is copied successfully');
              redirect('email_template','refresh');  
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
              $response['message'] = 'Email template is failed to copied.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Email template is failed to copied.');
              redirect('email_template','refresh');
            }
          } 
        }
      }
      else
      {
        $data['email_templates'] = $this->email_template_model->get_records();
        $data['email_template'] = null;

        if($email_template_id != null)
        {
          $data['email_template']       = $this->email_template_model->get_single_record($email_template_id);
          
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['copy_email_template_modal_body'] = $this->load->view('email_template/ajax/copy_email_template_modal_body',$data,TRUE);
          echo json_encode($response);
        }
        else
        {
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['copy_email_template_modal_body'] = $this->load->view('email_template/ajax/copy_email_template_modal_body',$data,TRUE);
          echo json_encode($response);
        }
      }
    }

    
  }



  function delete()
	{
		if(!$this->permission_model->has_permission('delete_email_template'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$data = array('delete_status' => 1);

			$email_template = $this->email_template_model->get_single_record($id);

			if($this->email_template_model->edit_record($data,$id))
			{
				$log_data = array(
			                      "user_id"     => $this->session->userdata("user_id"),
			                      "module"      => "email_template",
			                      "user_action" => 3,
			                      "data"        => json_encode($data),
			                      "description" => $email_template->template_name.' deleted successfully.'
			                    );
      	$this->log_data_model->add_record($log_data);     

				$this->session->set_flashdata('success', $email_template->template_name.' is deleted successfully');
				redirect('email_template','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', $email_template->template_name.' is failed to delete');
				redirect('email_template','refresh');
			}
		}
	}


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->email_template_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
    	/* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //View Button

      if($this->permission_model->has_permission('edit_email_template'))
      {	
        $table_body .= ' 
                      <a href="#" class="btn btn-info btn-xs add_email_template_modal"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('email_template_edit').'" data-email_template_id="'.$item->id.'">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                    ';

        $table_body .= ' 
                      <a href="#" class="btn bg-purple btn-xs copy_email_template_modal"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('email_template_copy').'" data-email_template_id="'.$item->id.'">
                        <i class="fas fa-copy"></i> Copy
                      </a>
                    ';
      }

     
      if($this->permission_model->has_permission('delete_email_template'))
      {	
        $table_body .= ' 
                        <a href="#"  data-toggle="modal" data-target="#delete_email_template" data-tt="tooltip" title="'.$this->lang->line('email_template_delete').'" class="btn btn-danger btn-xs delete_email_template" data-email_template_id="'.$item->id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>
                      ';
      }
      

          /* End Action column buttons*/

          

      $row = array();
   

      $row[] = $item->template_name;
      $row[] = $item->from_name;
      $row[] = $item->from_email;
      $row[] = clean_e_val($item->module);
      $row[] = date('d-m-Y h:i:s', strtotime($item->created_date));
      
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->email_template_model->count_all(),
                    "recordsFiltered"   => $this->email_template_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function email_template_delete_confirmation()
  {
  	$email_template_id 					= $this->input->post('email_template_id');
  	$data['email_template'] 		= $this->email_template_model->get_single_record($email_template_id);

  	$response 					= array();
  	$response['email_template_delete_modal_body'] 	= $this->load->view('email_template/ajax/delete_email_template_modal_body',$data,TRUE);

  	echo json_encode($response);
	}
}
