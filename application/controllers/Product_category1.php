<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_category extends MY_Controller {

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
		$data['product_category'] = $this->product_category_model->get_records();

		$log_data = array(
              "user_id"     => $this->session->userdata("user_id"),
              "module"      => "product_category",
              "user_action" => 0,
              "description"	=> "User has viewed list of product_category."
            );

		$this->log_data_model->add_record($log_data);

		$this->load->view('product_category/list',$data);
	}
	

	public function add()
	{
		if(!$this->permission_model->has_permission('add_product_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('name','Name','required');		
				$this->form_validation->set_rules('tax_id','Tax','required');		
				
				if($this->form_validation->run()==FALSE)
				{
					$data['tax'] = $this->tax_model->get_tax_records();
					$this->load->view('product_category/add',$data);
				}
				else
				{
					$name 				= 	$this->input->post('name');
					$description 	= 	$this->input->post('description');
					$tax_type 		= 	$this->input->post('tax_type');

					$tax_id 			= 	$this->input->post('tax_id');
					
					if($tax_id == 'new_tax')
					{
						$tax_name = $this->input->post('tax_name');
						$igst 		= $this->input->post('igst');
						$cgst 		= $this->input->post('cgst');
						$sgst 		= $this->input->post('sgst');

						$tax_data = array(
															"tax_name"	=>	$tax_name,
															"sgst"			=>	$sgst,
															"cgst"			=>	$cgst,
															"igst"			=>	$igst,
															"user_id"		=> 	$this->session->userdata('user_id')
														);

						$tax_id 	= $this->tax_model->add_record($tax_data);
					}					

					if($tax_type == null)
					{
						$tax_type = 0;
					}

					$data			=	array(
											"name"				=> 	$name,
											"description"	=>	$description,
											"tax_id"			=>	$tax_id,
											"tax_type"		=>  $tax_type
									);
				
					if($id = $this->product_category_model->add_record($data))
					{
						$entered_product_category = $this->product_category_model->get_records();

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"			=> "product_category",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"				=> json_encode((array)$entered_product_category),
											"description" => $name.' successfully added.'
										);
						$this->log_data_model->add_record($log_data);

						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 1;
							$response['id'] 								= $id;
							$response['message']						= $name.' is added successfully.';
							$response['product_categories']	= $this->product_category_model->get_records();

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', $name.' is added successfully');
							redirect('product_category','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request())
						{	
							$response 											= array();
							$response['code'] 							= 0;
							$response['message']						= $name.' is failed to add.';
							
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', $name.' is failed to add.');
							redirect('product_category','refresh');
						}
						
					}
				}
			}
			else
			{
				$data['tax'] = $this->tax_model->get_tax_records();
				$this->load->view('product_category/add',$data);
			}
		}
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_product_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{

				$id 										= $this->input->post('id');
				$old_product_category 	= $this->product_category_model->get_single_record($id);

				$this->form_validation->set_rules('name','Name','required');	
				$this->form_validation->set_rules('tax_id','Tax','required');			
				
				if($this->form_validation->run()==FALSE)
				{
					$data['product_category'] 	= $this->product_category_model->get_single_record($id);
					$data['tax'] 				= $this->tax_model->get_tax_records();
					$this->load->view('product_category/edit',$data);
				}
				else
				{
					$name 				= 	$this->input->post('name');
					$description 	= 	$this->input->post('description');
					$tax_type 		= 	$this->input->post('tax_type');

					$tax_id 			= 	$this->input->post('tax_id');

					if($tax_id == 'new_tax')
					{
						$tax_name = $this->input->post('tax_name');
						$igst 		= $this->input->post('igst');
						$cgst 		= $this->input->post('cgst');
						$sgst 		= $this->input->post('sgst');

						$tax_data = array(
															"tax_name"	=>	$tax_name,
															"sgst"			=>	$sgst,
															"cgst"			=>	$cgst,
															"igst"			=>	$igst,
															"user_id"		=> 	$this->session->userdata('user_id')
														);

						$tax_id 	= $this->tax_model->add_record($tax_data);
					}		


					if($tax_type == null)
					{
						$tax_type = 0;
					}

					$data			=	array(
											"name"			=> 	$name,
											"description"	=>	$description,
											"tax_id"		=>	$tax_id,
											"tax_type"		=>  $tax_type
									);

				
					if($this->product_category_model->edit_record($data,$id))
					{
						$entered_product_category = $this->product_category_model->get_single_record($id);

						$log_data = array(
															"user_id" 		=> $this->session->userdata("user_id"),
															"module"			=> "product_category",
															"user_action"	=> 2,
															"b_data"			=> json_encode((array)$old_product_category),
															"data"				=> json_encode((array)$entered_product_category),
															"description" => $name.' successfully updated.'
														);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is updated successfully.');
						redirect('product_category','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to update.');
						redirect('product_category','refresh');
					}
				}
			}
			else
			{
				$id 									= base64_decode($id);

				if($id != null)
				{
					$data['product_category'] 	= $this->product_category_model->get_single_record($id);

					if($data['product_category'] != null)
					{
						$data['tax'] 								= $this->tax_model->get_tax_records();
						$this->load->view('product_category/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('product_category');
					}
					
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('product_category');
				}
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_product_category'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 								= $this->input->post('id');
			$data 							= array('delete_status' => 1);
			$product_category 	= $this->product_category_model->get_single_record($id);
			$products 					=	$this->product_model->get_records_by_product_category($id);

			if($products == null)
			{
				if($this->product_category_model->edit_record($data,$id))
				{
					$log_data = array(
			                      "user_id"     => $this->session->userdata("user_id"),
			                      "module"      => "product_category",
			                      "user_action" => 3,
			                      "data"        => json_encode($data),
			                      "description" => $product_category->name.' deleted successfully.'
			                    );
		      		$this->log_data_model->add_record($log_data);     

					$this->session->set_flashdata('success', $product_category->name.' is deleted successfully.');
					redirect('product_category','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', $product_category->name.' failed to delete.');
					redirect('product_category','refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('failure', "Products are exist in this category so you can not delete ".$product_category->name);
				redirect('product_category','refresh');
			}
		}
	}


	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->product_category_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<table><tr>';
        $table_footer = '</tr></table>';
        $table_body   = '';

				// Edit Button        
        if($this->permission_model->has_permission('edit_product_category'))
				{	
					$table_body .= '<td class="p-2">
		                        <a href="'.base_url('product_category/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" data-tt="tooltip" title="'.$this->lang->line('product_category_edit').'">
		                          <i class="fas fa-edit"></i>
		                        </a>        
		                      </td>';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_product_category'))
				{	
					$table_body .= '<td class="p-2">
		                        <a href="#"  data-toggle="modal" data-target="#delete_product_category" data-tt="tooltip" title="'.$this->lang->line('product_category_delete').'" class="btn btn-danger btn-xs delete_product_category" data-product_category_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i>
		                        </a>
													</td>';
				}

			

				/* End Action column buttons*/

			
        $row = array();
       
        $row[] = $item->name;
        $row[] = $item->description;
        $row[] = $item->tax_name;

     		$row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->product_category_model->count_all(),
                    "recordsFiltered" => $this->product_category_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function product_category_delete_confirmation()
  {
  	$product_category_id 					= $this->input->post('product_category_id');
  	$data['product_category']	 		= $this->product_category_model->get_single_record($product_category_id);

  	$response 					= array();
  	$response['product_category_delete_modal_body'] 	= $this->load->view('product_category/ajax/product_category_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/

		
}
