<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_category extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('expense_category_model');

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
			$data['expense_category'] = $this->expense_category_model->get_records();

			$log_data = array(
                  "user_id"     => $this->session->userdata("user_id"),
                  "module"      => "expense_category",
                  "user_action" => 0,
                  "description"	=> "User has viewed list of expense category."
                );

			$this->log_data_model->add_record($log_data);

			$this->load->view('expense_category/list',$data);
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_expense_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('name','Name','required');		
				$this->form_validation->set_rules('description','Description','required');

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('expense_category/add');
				}
				else
				{
					$name   					=   $this->input->post('name');
					$description   		=   $this->input->post('description');
					$account_group_id = 	$this->input->post('account_group_id');

					$data			=	array(
														"name"				=>	$name,
														"description"	=>	$description
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
						$entered_expense_category = $this->expense_category_model->get_single_record($id);

						

						$log_data = array(
											"user_id" 			=> $this->session->userdata("user_id"),
											"module"				=> "expense_category",
											"entry_id"			=> $id,
											"user_action"		=> 1,
											"data"					=> json_encode((array)$entered_expense_category),
											"description" 	=> $name.' successfully added.'
										);
						$this->log_data_model->add_record($log_data);

						// commit transaction
						$this->db->trans_commit();
					
						if($this->input->is_ajax_request())
						{
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= $name.' is added successfully.';
							$response['expense_categories']	= $this->expense_category_model->get_records();

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', $name.' is added successfully');
							redirect('expense_category','refresh');
						}
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						if($this->input->is_ajax_request())
						{
							$response 						= array();
							$response['code'] 				= 0;
							$response['message']			= $name.' is failed to add';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', $name.' is failed to add');
							redirect('expense_category','refresh');
						}
					}
				}
			}
			else
			{
				$data['account_groups']	= $this->account_group_model->get_records("Expense");
				$this->load->view('expense_category/add',$data);
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
				$old_expense_category = $this->expense_category_model->get_single_record($id);


				$this->form_validation->set_rules('name','Name','required');		
				$this->form_validation->set_rules('description','Description','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['expense_category'] = $this->expense_category_model->get_single_record($id);
					$this->load->view('expense_category/edit',$data);
				}
				else
				{
					$name   					=   $this->input->post('name');
					$description   		=   $this->input->post('description');
					$account_group_id =   $this->input->post('account_group_id');

					// being transaction
					$this->db->trans_begin();

					$data			=	array(
														"name"				=>	$name,
														"description"	=>	$description
													);
				
					if($this->expense_category_model->edit_record($data,$id))
					{
						$entered_expense_category = $this->expense_category_model->get_single_record($id);

						$ledger_data = array(
																	"title" 						=> strtoupper($name),
																	"account_group_id" 	=> $account_group_id
																);

						$this->ledger_model->edit_record($ledger_data,$entered_expense_category->ledger_id);

						$log_data = array(
															"user_id" 		=> $this->session->userdata("user_id"),
															"module"			=> "expense_category",
															"user_action"	=> 2,
															"b_data"			=> json_encode((array)$old_expense_category),
															"data"				=> json_encode((array)$entered_expense_category),
															"description" => $name.' successfully updated.'
														);
						$this->log_data_model->add_record($log_data);

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success', $name.' is updated successfully');
						redirect('expense_category','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						$this->session->set_flashdata('failure', $name.' is failed to update');
						redirect('expense_category','refresh');
					}
				}
			}
			else
			{
				$id 									= base64_decode($id);

				if($id != null)
				{
					$data['expense_category'] = $this->expense_category_model->get_single_record($id);

					if($data['expense_category'] != null)
					{
						$data['account_groups']		= $this->account_group_model->get_records("Expense");
						$data['ledger']						= $this->ledger_model->get_single_record($data['expense_category']->ledger_id);

						$this->load->view('expense_category/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('expense_category','refresh');
					}
					
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('expense_category','refresh');
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
			$id 	= $this->input->post('id');
			$data = array('delete_status' => 1);

			$expense_category = $this->expense_category_model->get_single_record($id);

			if($this->expense_model->get_records_by_expense_category_id($id) == null)
			{
				if($this->expense_category_model->edit_record($data,$id))
				{
					$log_data = array(
			                      "user_id"     => $this->session->userdata("user_id"),
			                      "module"      => "expense_category",
			                      "user_action" => 3,
			                      "data"        => json_encode($data),
			                      "description" => $expense_category->name.' deleted successfully.'
			                    );
      		$this->log_data_model->add_record($log_data);  

					$this->session->set_flashdata('success', $expense_category->name.' is deleted successfully');
					redirect('expense_category','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', $expense_category->name.' is failed to delete');
					redirect('expense_category','refresh');
				}	
			}
			else
			{
				$this->session->set_flashdata('failure', $this->lang->line('expense_category_used_in_expense'));
					redirect('expense_category','refresh');
				}
		}
	}

	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->expense_category_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<table><tr>';
        $table_footer = '</tr></table>';
        $table_body   = '';

				// Edit Button        
        if($this->permission_model->has_permission('edit_expense_category'))
				{	
					$table_body .= '<td class="p-2">
				 										<a href="'.base_url('expense_category/edit/'.base64_encode($item->id)).'"  data-tt="tooltip" title="'.$this->lang->line('expense_category_edit').'" class="btn btn-info btn-xs">
                              <i class="fas fa-edit"></i>
                            </a>
			                    </td>';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_expense_category'))
				{	
					$table_body .= '<td class="p-2">
					 									<a href="#"  data-toggle="modal" data-target="#delete_expense_category" data-tt="tooltip" title="'.$this->lang->line('expense_category_delete').'" class="btn btn-danger btn-xs delete_expense_category" data-expense_category_id="'.$item->id.'">
			                          <i class="fas fa-trash"></i>
		                        </a>
												  </td>';
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

  public function expense_category_delete_confirmation()
  {
  	$expense_category_id 										= $this->input->post('expense_category_id');
  	$data['expense_category_id'] 						= $expense_category_id;

  	$response 															= array();
  	$response['expense_category_delete_modal_body'] 	= $this->load->view('expense_category/ajax/expense_category_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/
}
