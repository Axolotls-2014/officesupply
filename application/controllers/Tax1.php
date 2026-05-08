<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax extends MY_Controller 
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
		if($this->input->is_ajax_request())
		{
			$response 					= array();
			$response['taxes']			= $this->tax_model->get_tax_records();
			echo json_encode($response);	
		}
		else
		{
			if(!$this->permission_model->has_permission('list_tax'))
			{
				$this->load->view('errors/html/error_restricted'); 
			}
			else
			{   
				$data['tax'] = $this->tax_model->get_tax_records();

				$log_data = array(
                  "user_id"     => $this->session->userdata("user_id"),
                  "module"      => "tax",
                  "user_action" => 0,
                  "description"	=> "User has viewed list of tax."
                );

				$this->log_data_model->add_record($log_data); 

				$this->load->view('tax/list',$data);
			}
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_tax'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{

				$this->form_validation->set_rules('tax_name','Tax name','required');	
				$this->form_validation->set_rules('sgst','SGST','required');
				$this->form_validation->set_rules('cgst','CGST','required');
				$this->form_validation->set_rules('igst','IGST','required');

				if($this->form_validation->run()==FALSE)
				{
					$this->load->view('tax/add');
				}
				else
				{
					$tax_name   =   $this->input->post('tax_name');
					$sgst 			=		$this->input->post('sgst');
					$cgst 			=		$this->input->post('cgst');
					$igst 			=		$this->input->post('igst');
					$status 		=		$this->input->post('status');

					$data		=	array(
										"tax_name"			=>	$tax_name,
										"sgst"				=>	$sgst,
										"cgst"				=>	$cgst,
										"igst"				=>	$igst,
										"status"			=>	$status
									);

					// being transaction
					$this->db->trans_begin();
				
					if($id = $this->tax_model->add_record($data))
					{
						$entered_tax = $this->tax_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "tax",
											"entry_id"		=> $id,
											"user_action"	=> 1,
											"data"			=> json_encode((array)$entered_tax),
											"description" 	=> $tax_name.' successfully added.'
										);
						$this->log_data_model->add_record($log_data);
						// commit transaction
						$this->db->trans_commit();

						if($this->input->is_ajax_request())
						{
							
							$response 					= array();
							$response['code'] 			= 1;
							$response['id'] 			= $id;
							$response['message']		= $tax_name.' added successfully.';
							$response['taxes']			= $this->tax_model->get_tax_records();

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', $tax_name.' is added successfully');
							redirect('tax','refresh');
						}
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();

						if($this->input->is_ajax_request())
						{
							$response 					= array();
							$response['code'] 			= 0;
							$response['message']		= $tax_name.' failed to add.';

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', $tax_name.' is failed to add.');
							redirect('tax','refresh');
						}
					}
				}
			}
			else
			{
				$this->load->view('tax/add');
			}
		}
		
	}	

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_tax'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$id = $this->input->post('id');
				$old_tax = $this->tax_model->get_single_record($id);

				$this->form_validation->set_rules('tax_name','Tax name','required');		
				$this->form_validation->set_rules('sgst','SGST','required');
				$this->form_validation->set_rules('cgst','CGST','required');
				$this->form_validation->set_rules('igst','IGST','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['tax'] = $this->tax_model->get_single_record($id);
					$this->load->view('tax/edit',$data);
				}
				else
				{
					$tax_name   =   $this->input->post('tax_name');
					$sgst 		=	$this->input->post('sgst');
					$cgst 		=	$this->input->post('cgst');
					$igst 		=	$this->input->post('igst');
					$status 	=	$this->input->post('status');

					$data		=	array(
													"tax_name"			=>	$tax_name,
													"sgst"					=>	$sgst,
													"cgst"					=>	$cgst,
													"igst"					=>	$igst,
													"status"				=>	$status,
													"user_id"		=> 	$this->session->userdata('user_id')
												);
				
				

				
					if($this->tax_model->edit_record($data,$id))
					{
						$entered_tax = $this->tax_model->get_single_record($id);

						$log_data = array(
															"user_id" 			=> $this->session->userdata("user_id"),
															"module"				=> "tax",
															"user_action"		=> 2,
															"b_data"				=> json_encode((array)$old_tax),
															"data"					=> json_encode((array)$entered_tax),
															"description" 	=> $tax_name.' successfully updated.'
														);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $tax_name.' is updated successfully');
						redirect('tax','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $tax_name.' is failed to update');
						redirect('tax/edit','refresh');
					}
				}
			}
			else
			{
				$id 									= base64_decode($id);

				if($id != null)
				{
					$data['tax'] = $this->tax_model->get_single_record($id);
					if($data['tax'] != null)
					{
						$this->load->view('tax/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('tax','refresh');
					}
					
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('tax','refresh');
				}
				
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_tax'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			
			$data 	= array(
									'delete_status' => 1
								);

			$tax 	= $this->tax_model->get_single_record($id);

			if($this->tax_model->edit_record($data,$id))
			{
				$log_data = array(
		                      "user_id"     => $this->session->userdata("user_id"),
		                      "module"      => "tax",
		                      "user_action" => 3,
		                      "data"        => json_encode($data),
		                      "description" => $tax->tax_name.' deleted successfully.'
		                    );
          		$this->log_data_model->add_record($log_data);     

				$this->session->set_flashdata('success', $tax->tax_name.' is deleted successfully');
				redirect('tax','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', $tax->tax_name.' is failed to delete');
				redirect('tax','refresh');
			}
		}
	}

		/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->tax_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];


    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header 	= '<table><tr>';
        $table_footer 	= '</tr></table>';
        $table_body   	= '';

        $sale_items  		= $this->sale_model->get_sale_item_records_by_tax_id($item->id);
      	$purchase_items = $this->purchase_model->get_purchase_item_records_by_tax_id($item->id);


				// Edit Button        
        if($this->permission_model->has_permission('edit_tax'))
				{	
					if($sale_items == null && $purchase_items == null)
          {
          	$table_body .= 	'<td class="p-2">
															<a href="'.base_url('tax/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" data-tt="tooltip" title="'.$this->lang->line('tax_edit').'">
			                          <i class="fas fa-edit"></i>
			                        </a>  
				                    </td>';
          }
          else
          {
          	$table_body .= 	 '<td class="p-2">
																<a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-target="#tax_edit" data-tt="tooltip" title="'.$this->lang->line('tax_edit').'" data-tax_id="'.$item->id.'">
	                                <i class="fas fa-edit"></i>
	                              </a> 
				                     	</td>';

          }
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_tax'))
				{	
					$table_body .= '<td class="p-2">
		                        <a href="#"  data-toggle="modal" data-target="#delete_tax" data-tt="tooltip" title="'.$this->lang->line('tax_delete').'" class="btn btn-danger btn-xs delete_product" data-tax_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i>
		                        </a>
													</td>';
				}

			

				/* End Action column buttons*/

				//Tax Status
					$status = '';
				 	if($item->status == TAX_ACTIVE)
          {
            $status .= '<span class="badge badge-success" style="font-size:12px">'.$this->lang->line('tax_status_active').'</span>';
          }
          else if($item->status == TAX_INACTIVE)
          {
            $status .= '<span class="badge badge-danger" style="font-size:12px">'.$this->lang->line('tax_status_inactive').'</span>'; 
          }

	        $row = array();

	        $row[] = $item->tax_name;
	        $row[] = $item->sgst;
	        $row[] = $item->cgst;
	        $row[] = $item->igst;
	        $row[] = $status;
	       
	        $row[] = $table_header.$table_body.$table_footer;    
					$data[] = $row;
	    }

    	$output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->tax_model->count_all(),
                    "recordsFiltered" => $this->tax_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function tax_edit_confirmation()
  {
  	$tax_id 				= $this->input->post('tax_id');
  	$data['tax'] 		= $this->tax_model->get_single_record($tax_id);

  	$response 					= array();
  	$response['tax_edit_modal_body'] 	= $this->load->view('tax/ajax/tax_edit_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	public function tax_delete_confirmation()
  {
  	$tax_id 				= $this->input->post('tax_id');
  	$data['tax'] 		= $this->tax_model->get_single_record($tax_id);

  	$response 					= array();
  	$response['tax_delete_modal_body'] 	= $this->load->view('tax/ajax/tax_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}


	/************************************** End Dynamic Datatable function ***************************************/

		
}
