<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uom extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_uom'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$this->load->view('uom/list');	
		}
	}

	public function add($id = NULL)
	{
		if(!$this->permission_model->has_permission('add_uom'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				
				$this->form_validation->set_rules("uom","Uom","required");
        $this->form_validation->set_rules("name","Name","required");
        

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('uom/add');
				}
				else
				{	

					$uom = $this->input->post("uom");
          $name = $this->input->post("name");
          
				
					$data     = array(
                          "uom" => $uom,
                          "name" => $name,
                        );

					// being transaction
					$this->db->trans_begin();
					
					if($id = $this->uom_model->add_record($data))
					{

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'uom is added successfully.';
							$response['uoms'] 							= $this->uom_model->get_records();
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'uom is added successfully');
							redirect('uom','refresh');
						}
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 0;
							$response['message']						= 'uom is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'uom is failed to add.');
							redirect('uom','refresh');
						}
					}
				}
			}
			else
			{
				$data 											= array();

				if($this->input->is_ajax_request()) 
				{	
					$response 									= array();
					
					$response['code']						= 1; 						
			  	$response['add_uom_modal_body'] 	= $this->load->view('uom/ajax/add_uom_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{	
					$this->load->view('uom/add',$data);
				}
			}
		}
	}

	
	public function edit($id = null)
	{

		if(!$this->permission_model->has_permission('edit_uom'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');

				$this->form_validation->set_rules("uom","Uom","required");
        $this->form_validation->set_rules("name","Name","required");
        
			
				if($this->form_validation->run()==FALSE)
				{
					$data['uom'] = $this->uom_model->get_single_record($id);
					$this->load->view('uom/edit',$data);

				}
				else
				{
					$uom = $this->input->post("uom");
          $name = $this->input->post("name");
          
				
					$data     = array(
                          "uom" => $uom,
                          "name" => $name,
                        );

					if($this->uom_model->edit_record($data,$id))
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code'] = 1;
							$response['message'] = 'uom is updated successfully';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'uom is updated successfully');
							redirect('uom','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request()) 
						{
							$response['code']  		= 0;
							$response['message'] 	= 'uom is failed to update';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'uom is failed to update');
							redirect('uom','refresh');	
						}
						
					}
				}
				
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($id);
					
					$data['uom'] 		= $this->uom_model->get_single_record($id);
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['edit_uom_modal_body'] = $this->load->view('uom/ajax/edit_uom_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['uom'] 		= $this->uom_model->get_single_record($id);

					if($data['uom'] != null)
					{

						$this->load->view('uom/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('uom/');
					}
				}
				
			}
		}
	}

	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_uom'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 	= $this->input->post('id');

			$data = array('delete_status' => 1);

			$uom =  $this->uom_model->get_single_record($id);

			if($this->uom_model->edit_record($data,$id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= 'Uom is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Uom is deleted successfully');
					redirect('uom','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Uom is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Uom is failed to delete');
					redirect('uom');
				}
			}
		}
	}


  public function uom_delete_confirmation()
  {
  	$uom_id 				= $this->input->post('uom_id');
  	$data['uom']		= $this->uom_model->get_single_record($uom_id);

  	$response 																		= array();
  	$response['delete_uom_modal_body'] 	= $this->load->view('uom/ajax/delete_uom_modal_body',$data,TRUE);

  	echo json_encode($response);
	}

	public function ajax_list()
  {
    $list 		= $this->uom_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

     // Edit Button        
      if($this->permission_model->has_permission('edit_uom'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_uom_modal" data-tt="tooltip" title="'.$this->lang->line('uom_edit').'" class="btn btn-info btn-xs edit_uom_modal" data-uom_id="'.base64_encode($item->id).'">
	                          <i class="fas fa-edit"></i> Edit
	                        </a>';
			}

			// Delete Button
			if($this->permission_model->has_permission('delete_uom'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_uom_modal" data-tt="tooltip" title="'.$this->lang->line('uom_delete').'" class="btn btn-danger btn-xs delete_uom_modal" data-uom_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i> Delete
	                        </a>';
			}

		

			/* End Action column buttons*/

      $row = array();

     	$row[] = $item->uom;
                      $row[] = $item->name;
                      
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->uom_model->count_all(),
                    "recordsFiltered" => $this->uom_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

	public function export()
  {
    $query = $this->uom_model->export_records();
    $this->load->dbutil();
    
    $data = $this->dbutil->csv_from_result($query);
    
    $this->load->helper('download');
    force_download("UOM_EXPORTED.CSV", $data);
  }
	
}
