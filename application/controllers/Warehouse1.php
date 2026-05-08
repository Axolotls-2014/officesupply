<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends MY_Controller {

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
		if(!$this->permission_model->has_permission('list_warehouse'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['warehouse'] = $this->warehouse_model->get_records();

			$log_data = array(
                  "user_id"     => $this->session->userdata("user_id"),
                  "module"      => "warehouse",
                  "user_action" => 0,
                  "description"	=> "User has viewed list of warehouse."
                );

			$this->log_data_model->add_record($log_data);

			$this->load->view('warehouse/list',$data);
		}
	}

	public function add()
	{
		if(!$this->permission_model->has_permission('add_warehouse'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$this->form_validation->set_rules('name','Warehouse name','required');	
				$this->form_validation->set_rules('code','Warehouse code','required|is_unique[`warehouse`.code]', array(
                'required'      => '%s is required',
                'is_unique'     => '%s is already exists.'
        ));		
				
				if($this->form_validation->run()==FALSE)
				{	
					$this->load->view('warehouse/add');
				}
				else
				{
					$name 				= 	$this->input->post('name');
					$description 	= 	$this->input->post('description');
					$code 				= 	$this->input->post('code');
			
					$data			=	array(
														"name"				=>	$name,
														"code"				=>  $code,
														"description"	=>	$description
													);
			
					if($id = $this->warehouse_model->add_record($data))
					{
						
						// Add products to warehouse with zero quantity
						$products = $this->product_model->get_records();
						foreach ($products as $value) {
							$warehouse_products = array(
																					"warehouse_id"	=> $id,
																					"product_id"	=> $value->id
																				);
							$this->warehouse_products_model->add_record($warehouse_products);
						}

						$entered_warehouse = $this->warehouse_model->get_single_record($id);

						$log_data = array(
														"user_id" 			=> $this->session->userdata("user_id"),
														"module"				=> "warehouse",
														"entry_id"			=> $id,
														"user_action"		=> 1,
														"data"					=> json_encode((array)$entered_warehouse),
														"description" 	=> $name.' successfully added.'
													);
						$this->log_data_model->add_record($log_data);

						if($this->input->is_ajax_request())
						{	
							$response 							= array();
							$response['code'] 			= 1;
							$response['id'] 				= $id;
							$response['message']		= $name.' is added successfully.';
							$response['warehouse']	= $this->warehouse_model->get_records();

							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('success', $name.' is added successfully');
							redirect('warehouse','refresh');
						}
					}
					else
					{
						if($this->input->is_ajax_request())
						{	
							$response 						= array();
							$response['code'] 		= 0;
							$response['message']	= $name.' is failed to add.';
						
							echo json_encode($response);
						}
						else
						{
							$this->session->set_flashdata('failure', $name.' is failed to add');
							redirect('warehouse');
						}
					}
				}
			}
			else
			{
				
				$this->load->view('warehouse/add');
			}
		}
	}

	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_warehouse'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{

				$id = $this->input->post('id');
				$old_warehouse = $this->warehouse_model->get_single_record($id);

				$this->form_validation->set_rules('name','Warehouse Name','required');	
				$this->form_validation->set_rules('code','Warehouse Code','required|edit_unique[`warehouse`.code.'.$id.']');		
				
				if($this->form_validation->run()==FALSE)
				{
					$data['warehouse'] = $this->warehouse_model->get_single_record($id);
					$this->load->view('warehouse/edit',$data);
				}
				else
				{
					$name 			= 	$this->input->post('name');
					$code 			= 	$this->input->post('code');
					$description 	= 	$this->input->post('description');

					$data			=	array(
											"name"		=> 	$name,
											"code"		=> 	$code,
											"description"	=>	$description
										);

				
					if($this->warehouse_model->edit_record($data,$id))
					{
						$entered_warehouse = $this->warehouse_model->get_single_record($id);

						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "warehouse",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_warehouse),
											"data"			=> json_encode((array)$entered_warehouse),
											"description" 	=> $name.' successfully updated.'
										);
						$this->log_data_model->add_record($log_data);

						$this->session->set_flashdata('success', $name.' is updated successfully.');
						redirect('warehouse','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $name.' is failed to update.');
						redirect('warehouse','refresh');
					}
				}
			}
			else
			{
				$id 									= base64_decode($id);

				if($id != null)
				{
					$data['warehouse'] = $this->warehouse_model->get_single_record($id);
					if($data['warehouse'] != null)
					{
						$this->load->view('warehouse/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('warehouse');
					}
					
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('warehouse');
				}
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_warehouse'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$data = array('delete_status' => 1);
			$warehouse = $this->warehouse_model->get_single_record($id);

			if($this->warehouse_model->edit_record($data,$id))
			{
				$log_data = array(
		                      "user_id"     => $this->session->userdata("user_id"),
		                      "module"      => "warehouse",
		                      "user_action" => 3,
		                      "data"        => json_encode($data),
		                      "description" => $warehouse->name.' deleted successfully.'
		                    );
	      		$this->log_data_model->add_record($log_data);     

				$this->session->set_flashdata('success', $warehouse->name.' is deleted successfully.');
				redirect('warehouse','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', $warehouse->name.' failed to delete.');
				redirect('warehouse','refresh');
			}
		}
	}
	
	function get_record_details()
	{
		$warehouse_id = $this->input->post('warehouse_id');
		$data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);
		echo json_encode($data);
	}

	function get_record_detail_by_code()
	{
		$warehouse_code = $this->input->post('code');
		$data['warehouse'] = $this->warehouse_model->get_single_record_by_code($warehouse_code);

		$response = array();

		if($data['warehouse'] == null)
		{
			$response['code'] = 0;
			$response['message'] = 'Warehouse code is available.';
		}
		else
		{
			$response['code'] = 1;
			$response['message'] = "Warehouse code is not available to use. Please use different warehouse code.";
		}
		echo json_encode($response);	
	}

		/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->warehouse_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<table><tr>';
        $table_footer = '</tr></table>';
        $table_body   = '';

        //view Button
        $table_body .=	'<td class="p-2">
                           <a href="#" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#product_quantity_warehouse_wise" data-tt="tooltip" title="'.$this->lang->line('product_quantity_warehouse_wise').'" data-warehouse_id="'.$item->id.'">
                              <i class="fas fa-cubes"></i>
                            </a>
                        </td>';

				// Edit Button        
        if($this->permission_model->has_permission('edit_warehouse'))
				{	
					$table_body .= '<td class="p-2">
                            <a href="'.base_url('warehouse/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" title="'.$this->lang->line('warehouse_edit').'">
                              <i class="fas fa-edit"></i>
                            </a>      
                          </td>';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_warehouse'))
				{	
					$table_body .= '<td class="p-2">
		                        <a href="#"  data-toggle="modal" data-target="#delete_warehouse" data-tt="tooltip" title="'.$this->lang->line('warehouse_delete').'" class="btn btn-danger btn-xs delete_warehouse" data-warehouse_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i>
		                        </a>
													</td>';
				}

			

				/* End Action column buttons*/

				// Product Quantity
				$products  = $this->warehouse_products_model->get_records_by_warehouse_id($item->id);
        $product_quantity = 0;

        if(sizeof($products) > 0)
        {
          foreach ($products as $product) {
            $product_quantity += $product->quantity; 
          } 
        }


        $row = array();
       
        $row[] = $item->name;
        $row[] = $item->code;
        $row[] = $item->description;
    		$row[] = $product_quantity;


        $row[] = $table_header.$table_body.$table_footer;    
				$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->warehouse_model->count_all(),
                    "recordsFiltered" => $this->warehouse_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function warehouse_delete_confirmation()
  {
  	$warehouse_id 				= $this->input->post('warehouse_id');
  	$data['warehouse'] 		= $this->warehouse_model->get_single_record($warehouse_id);

  	$response 					= array();
  	$response['warehouse_delete_modal_body'] 	= $this->load->view('warehouse/ajax/warehouse_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/



		
}
