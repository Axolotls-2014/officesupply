<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_category extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_expense_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$this->load->view('expense_category/list');	
		}
	}

	public function add($id = NULL)
	{

		if(!$this->permission_model->has_permission('add_expense_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				
				$this->form_validation->set_rules("name","Name","required");
        $this->form_validation->set_rules("description","Description","required");
        

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('expense_category/add');
				}
				else
				{	

					$name 						= $this->input->post("name");
          $description 			= $this->input->post("description");
          $account_group_id = $this->input->post('account_group_id');
          
				
					$data     = array(
                          "name" 				=> $name,
                          "description" => $description,
                        );

					// being transaction
					$this->db->trans_begin();

					$ledger_data = array(
																"title" 						=> strtoupper($name),
																"account_group_id"	=> $account_group_id
															);
					
					$ledger_id = $this->ledger_model->add_record($ledger_data);

					$data['ledger_id'] = $ledger_id;
					
					if($id = $this->expense_category_model->add_record($data))
					{

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= 'Expense Category is added successfully.';
							$response['expense_categories'] = $this->expense_category_model->get_records();

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Expense Category is added successfully');
							redirect('expense_category','refresh');
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
							$response['message']						= 'Expense Category is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Expense Category is failed to add.');
							redirect('expense_category','refresh');
						}
						
					}
				}

			}
			else
			{
				$data 											= array();

				if($this->input->is_ajax_request()) 
				{
					
					$data['account_groups']			= $this->account_group_model->get_records("Expense");
					
					$response 									= array();
					$response['code']						= 1; 						
			  	$response['add_expense_category_modal_body'] 	= $this->load->view('expense_category/ajax/add_expense_category_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$data['account_groups']				= $this->account_group_model->get_records("Expense");
					$this->load->view('expense_category/add',$data);
				}
			}
		}
	}

	
	public function edit($id = null)
	{

		if(!$this->permission_model->has_permission('edit_expense_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');

				$this->form_validation->set_rules("name","Name","required");
        $this->form_validation->set_rules("description","Description","required");
        
			
				if($this->form_validation->run()==FALSE)
				{
					$data['expense_category'] = $this->expense_category_model->get_single_record($id);
					$this->load->view('expense_category/edit',$data);
				}
				else
				{
					$name 						= $this->input->post("name");
          $description 			= $this->input->post("description");
          $account_group_id = $this->input->post('account_group_id');
          
					// being transaction
					$this->db->trans_begin();

					$data     = array(
                          "name" => $name,
                          "description" => $description,
                        );

					if($this->expense_category_model->edit_record($data,$id))
					{
						$entered_expense_category = $this->expense_category_model->get_single_record($id);

						$ledger_data = array(
																	"title" 						=> strtoupper($name),
																	"account_group_id" 	=> $account_group_id
																);

						$this->ledger_model->edit_record($ledger_data,$entered_expense_category->ledger_id);

						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request()) 
						{
							$response['code'] = 1;
							$response['message'] = 'Expense Category is updated successfully';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', 'Expense Category is updated successfully');
							redirect('expense_category','refresh');
						}
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						if($this->input->is_ajax_request()) 
						{
							$response['code']  		= 0;
							$response['message'] 	= 'Expense Category is failed to update';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', 'Expense Category is failed to update');
							redirect('expense_category','refresh');	
						}
						
					}
				}
				
			}
			else
			{
				if($this->input->is_ajax_request()) 
				{
					$id = base64_decode($id);
					
					$data['account_groups']			= $this->account_group_model->get_records("Expense");
					$data['expense_category'] 	= $this->expense_category_model->get_single_record($id);
					$data['ledger']							= $this->ledger_model->get_single_record($data['expense_category']->ledger_id);
					
			  	$response 									= array();
			  	$response['code']  					= 1;
					$response['edit_expense_category_modal_body'] = $this->load->view('expense_category/ajax/edit_expense_category_modal_body',$data,TRUE);

			  	echo json_encode($response);
				}
				else
				{
					$id = base64_decode($id);
				
					$data['account_groups']			= $this->account_group_model->get_records("Expense");
					$data['expense_category'] 	= $this->expense_category_model->get_single_record($id);
					$data['ledger']							= $this->ledger_model->get_single_record($data['expense_category']->ledger_id);

					if($data['expense_category'] != null)
					{

						$this->load->view('expense_category/list',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('expense_category/');
					}
				}
				
			}
		}
	}

	
	function delete()
	{
		if(!$this->permission_model->has_permission('delete_expense_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 							= $this->input->post('id');

			$data = array('delete_status' => 1);

			$expense_category =  $this->expense_category_model->get_single_record($id);

			if($this->expense_category_model->edit_record($data,$id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= 'Expense Category is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Expense Category is deleted successfully');
					redirect('expense_category','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Expense Category is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Expense Category is failed to delete');
					redirect('expense_category');
				}
			}
		}
	}


  public function expense_category_delete_confirmation()
  {
  	$expense_category_id 				= $this->input->post('expense_category_id');
  	$data['expense_category']		= $this->expense_category_model->get_single_record($expense_category_id);

  	$response 																		= array();
  	$response['delete_expense_category_modal_body'] 	= $this->load->view('expense_category/ajax/delete_expense_category_modal_body',$data,TRUE);

  	echo json_encode($response);
	}

	public function ajax_list()
  {
    $list 		= $this->expense_category_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

     // Edit Button        
      if($this->permission_model->has_permission('edit_expense_category'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_expense_category_modal" data-tt="tooltip" title="'.$this->lang->line('expense_category_edit').'" class="btn btn-info btn-xs edit_expense_category_modal" data-expense_category_id="'.base64_encode($item->id).'">
	                          <i class="fas fa-edit"></i> Edit
	                        </a>';
			}

			// Delete Button
			if($this->permission_model->has_permission('delete_expense_category'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_expense_category_modal" data-tt="tooltip" title="'.$this->lang->line('expense_category_delete').'" class="btn btn-danger btn-xs delete_expense_category_modal" data-expense_category_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i> Delete
	                        </a>';
			}

		

			/* End Action column buttons*/

      $row = array();

     	$row[] = $item->name;
      $row[] = $item->description;
      $row[] = $item->account_group;
                      
     	
     	$row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->expense_category_model->count_all(),
                    "recordsFiltered" => $this->expense_category_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	
	
}
