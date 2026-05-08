<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_item'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$this->load->view('item/list');	
		}
	}

	public function add($id = NULL)
	{

		if(!$this->permission_model->has_permission('add_item'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{			
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{				
				$this->form_validation->set_rules("item_name","Name","required");
        $this->form_validation->set_rules("item_description","Description","required");
        $this->form_validation->set_rules("tax_id","Tax","required");
        
				
				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('item/add');
				}
				else
				{	
					
					$name 						= $this->input->post("item_name");
          $description 			= $this->input->post("item_description");
          $tax_id 			= $this->input->post("tax_id");
          
				
					$data     = array(
                          "item_name" 				=> $name,
                          "item_description" => $description,
                          "tax_id" => $tax_id,
													"created_by" => $this->session->userdata('user_id')
                        );

					// being transaction
					$this->db->trans_begin();
					if($id = $this->item_model->add_record($data))
					{

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'Item is added successfully.';
							$response['items'] = $this->item_model->get_records();

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Item is added successfully');
							redirect('item','refresh');
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
							$response['message']						= 'Item is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Item is failed to add.');
							redirect('item','refresh');
						}
						
					}
				}

			}
			else
			{

				$data 											= array();

				if($this->input->is_ajax_request()) 
				{
					$data['tax']								= $this->tax_model->get_records();
					$response 									= array();
					$response['code']						= 1; 						
			  	$response['add_item_modal_body'] 	= $this->load->view('item/ajax/add_item_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$this->load->view('item/add',$data);
				}
			}
		}
	}

	
	public function edit($id = null)
	{

		if(!$this->permission_model->has_permission('edit_item'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');

				$this->form_validation->set_rules("item_name","Item Name","required");
        $this->form_validation->set_rules("item_description","Item Description","required");
        $this->form_validation->set_rules("tax_id","Tax","required");
        
			
				if($this->form_validation->run()==FALSE)
				{
					$data['item'] = $this->item_model->get_single_record($id);
					$this->load->view('item/edit',$data);
				}
				else
				{
					$name 						= $this->input->post("item_name");
          $description 			= $this->input->post("item_description");
          $tax_id 			= $this->input->post("tax_id");
          
					// being transaction
					$this->db->trans_begin();

					$data     = array(
                          "item_name" => $name,
                          "item_description" => $description,
                          "tax_id" => $tax_id,
													"updated_by" => $this->session->userdata("user_id")
                        );
					
					if($this->item_model->edit_record($data,$id))
					{
						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request()) 
						{
							$response['code'] = 1;
							$response['message'] = 'Item is updated successfully';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Item is updated successfully');
							redirect('item','refresh');
						}
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						if($this->input->is_ajax_request()) 
						{
							$response['code']  		= 0;
							$response['message'] 	= 'Item is failed to update';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Item is failed to update');
							redirect('item','refresh');	
						}
						
					}
				}
				
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($id);
					
					$data['item'] 	= $this->item_model->get_single_record($id);
					$data['tax']								= $this->tax_model->get_records();
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['edit_item_modal_body'] = $this->load->view('item/ajax/edit_item_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['item'] 	= $this->item_model->get_single_record($id);
					
					if($data['item'] != null)
					{

						$this->load->view('item/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('item/');
					}
				}
				
			}
		}
	}

	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_item'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 							= $this->input->post('id');

			$data = array('delete_status' => 1);

			$item =  $this->item_model->get_single_record($id);

			if($this->item_model->edit_record($data,$id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= 'Item is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Item is deleted successfully');
					redirect('item','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Item is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Item is failed to delete');
					redirect('item');
				}
			}
		}
	}


  public function item_delete_confirmation()
  {
  	$item_id 				= $this->input->post('item_id');
  	$data['item']		= $this->item_model->get_single_record($item_id);

  	$response 																		= array();
  	$response['delete_item_modal_body'] 	= $this->load->view('item/ajax/delete_item_modal_body',$data,TRUE);

  	echo json_encode($response);
	}

	public function ajax_list()
  {
    $list 		= $this->item_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

     // Edit Button        
      if($this->permission_model->has_permission('edit_item'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_item_modal" data-tt="tooltip" title="'.$this->lang->line('item_edit').'" class="btn btn-info btn-xs edit_item_modal" data-item_id="'.base64_encode($item->id).'">
	                          <i class="fas fa-edit"></i> Edit
	                        </a>';
			}

			// Delete Button
			if($this->permission_model->has_permission('delete_item'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_item_modal" data-tt="tooltip" title="'.$this->lang->line('item_delete').'" class="btn btn-danger btn-xs delete_item_modal" data-item_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i> Delete
	                        </a>';
			}

		

			/* End Action column buttons*/

      $row = array();

     	$row[] = $item->item_name;
      $row[] = $item->item_description;
      $row[] = $item->tax;
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->item_model->count_all(),
                    "recordsFiltered" => $this->item_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	
	
}
