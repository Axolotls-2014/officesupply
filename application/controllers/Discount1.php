<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('discount_model');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	
	public function index()
	{
		if(!$this->permission_model->has_permission('list_discount'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['discount'] = $this->discount_model->get_records();

			$log_data = array(
                  "user_id"     => $this->session->userdata("user_id"),
                  "module"      => "discount",
                  "user_action" => 0,
                  "description"	=> "User has viewed list of discount."
                );

			$this->log_data_model->add_record($log_data); 

			$this->load->view('discount/list',$data);
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_discount'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('name','Name','required');		
				$this->form_validation->set_rules('type','Discount type','required');
				$this->form_validation->set_rules('value','Discount value','required');
				$this->form_validation->set_rules('valid_from','valid from','required');
				$this->form_validation->set_rules('valid_to','Valid to','required');

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('discount/add');
				}
				else
				{
					$name 				=   $this->input->post('name');
					$type 				=   $this->input->post('type');
					$value 				=   $this->input->post('value');
					$valid_from 	= 	date('Y-m-d', strtotime($this->input->post('valid_from')));
					$valid_to 		= 	date('Y-m-d', strtotime($this->input->post('valid_to')));
					$description 	=   $this->input->post('description');


					$data			=	array(
										
												"name"			=>	$name,
												"type"			=>	$type,
												"value"			=>	$value,
												"valid_from"	=>	$valid_from,
												"valid_to"		=>	$valid_to,
												"description"	=>	$description
											);

				
					if($id = $this->discount_model->add_record($data))
					{
						$entered_discount = $this->discount_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "discount",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"			=> json_encode((array)$entered_discount),
											"description" 	=> $name.' successfully added.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is added successfully');
						redirect('discount','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to add.');
						redirect('discount','refresh');
					}
				}
				
			}
			else
			{
				$this->load->view('discount/add');
			}
		}
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_discount'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');
				$old_discount = $this->discount_model->get_single_record($id);

				$this->form_validation->set_rules('name','Name','required');		
				$this->form_validation->set_rules('type','Discount type','required');
				$this->form_validation->set_rules('value','Discount value','required');
				$this->form_validation->set_rules('valid_from','Valid from','required');
				$this->form_validation->set_rules('valid_to','Valid to','required');
				
				if($this->form_validation->run()==FALSE)
				{
					$data['discount'] = $this->discount_model->get_single_record($id);
					$this->load->view('discount/edit',$data);
				}
				else
				{
					$name 			=   $this->input->post('name');
					$type 			=   $this->input->post('type');
					$value 			=   $this->input->post('value');
					$valid_from 	= 	date('Y-m-d', strtotime($this->input->post('valid_from')));
					$valid_to 		= 	date('Y-m-d', strtotime($this->input->post('valid_to')));
					$description 	=   $this->input->post('description');


					$data			=	array(
										
												"name"			=>	$name,
												"type"			=>	$type,
												"value"			=>	$value,
												"valid_from"	=>	$valid_from,
												"valid_to"		=>	$valid_to,
												"description"	=>	$description
											);

					

					
					if($this->discount_model->edit_record($data,$id))
					{
						$entered_discount = $this->discount_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "discount",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_discount),
											"data"			=> json_encode((array)$entered_discount),
											"description" 	=> $name.' successfully updated.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is updated successfully');
						redirect('discount','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to update');
						redirect('discount/edit','refresh');
					}
				}

				
			}
			else
			{
				$id = base64_decode($id);

				if($id != null)
				{
					$data['discount'] = $this->discount_model->get_single_record($id);

					if($data['discount'] != null)
					{
						$this->load->view('discount/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('discount','refresh');
					}
					
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('discount','refresh');
				}
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_discount'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$data = array(
											'delete_status' => 1
										);

			$discount = $this->discount_model->get_single_record($id);

			if($this->sale_model->get_sale_item_records_by_discount_id($id) == null)
			{

				if($this->discount_model->edit_record($data,$id))
				{

					$log_data = array(
				                      "user_id"     => $this->session->userdata("user_id"),
				                      "module"      => "discount",
				                      "user_action" => 3,
				                      "data"        => json_encode($data),
				                      "description" => $discount->name.' deleted successfully.'
				                    );
		          	$this->log_data_model->add_record($log_data);    

					$this->session->set_flashdata('success', $discount->name.' is deleted successfully');
					redirect('discount','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', $discount->name.' is failed to delete');
					redirect('discount','refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('failure', $this->lang->line('discount_used_in_sale'));
				redirect('discount','refresh');	
			}
		}
	}

	function get_record_detail()
	{
		$discount_id 		= $this->input->post('discount_id');
		$data['discount'] 	= $this->discount_model->get_single_record($discount_id);
		
		print_r(json_encode($data,true));
	}

	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->discount_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<table><tr>';
        $table_footer = '</tr></table>';
        $table_body   = '';

     
				// Edit Button        
        if($this->permission_model->has_permission('edit_discount'))
				{	
					$table_body .= '<td class="p-2">
		                        <a href="'.base_url('discount/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" title="'.$this->lang->line('discount_edit').'">
                              <i class="fas fa-edit"></i>
                            </a>        
		                      </td>';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_discount'))
				{	
					$table_body .= '<td class="p-2">
		                         <a href="#"  data-toggle="modal" data-target="#delete_discount" data-tt="tooltip" title="'.$this->lang->line('discount_delete').'" class="btn btn-danger btn-xs delete_discount" data-discount_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i>
		                        </a>
													</td>';
				}

			

				/* End Action column buttons*/

				//Discount Status
					$status = '';
				 	if($item->status == 1)
          {
            $status .= '<span class="badge badge-success" style="font-size:12px">'.$this->lang->line('tax_status_active').'</span>';
          }
          else
          {
            $status .= '<span class="badge badge-danger" style="font-size:12px">'.$this->lang->line('tax_status_inactive').'</span>'; 
          }


				
        $row = array();
       
        $row[] = $item->name;
	      $row[] = ($item->type == 0) ? 'Fixed' : 'Percentage';
	      $row[] = $item->value;
        $row[] = date('d-m-Y', strtotime($item->valid_from));
        $row[] = date('d-m-Y', strtotime($item->valid_to));
        $row[] = $item->description;
        $row[] = $status;
                     
      	$row[] = $table_header.$table_body.$table_footer;    

        


        
     

        $data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->discount_model->count_all(),
                    "recordsFiltered" => $this->discount_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function discount_delete_confirmation()
  {
  	$discount_id 				= $this->input->post('discount_id');
  	$data['discount_id'] = $discount_id;

  	$response 					= array();
  	$response['discount_delete_modal_body'] 	= $this->load->view('discount/ajax/discount_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/
		
}
