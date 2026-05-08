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
			$this->load->view('warehouse/list');	
		}
	}

	public function add($id = NULL)
{
    if(!$this->permission_model->has_permission('add_warehouse'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {   
            $this->form_validation->set_rules("code", "Code", "required|callback_is_unique_code");
            $this->form_validation->set_rules("name", "Name", "required");

            if($this->form_validation->run() == FALSE)
            {
                if($this->input->is_ajax_request())
                {
                    $data['code'] = 2;
                    $data['errors'] = $this->form_validation->error_array();
                    echo json_encode($data);
                }
                else
                { 
                    $this->load->view('warehouse/add');
                }
            }
            else
            {   
                $code               = $this->input->post("code");
                $name               = $this->input->post("name");
                $description        = $this->input->post("description");
                $is_default         = $this->input->post("is_default");
                $license_no         = $this->input->post("license_no");
                $country_id         = $this->input->post("country_id");
                $state_id           = $this->input->post("state_id");
                $city_id            = $this->input->post("city_id");
                $address_line1      = $this->input->post("address_line1");
                $address_line2      = $this->input->post("address_line2");
                $pincode            = $this->input->post("pincode");
                $check              = $this->input->post("is_head_office"); // 1 = Yes, 0 = No

                if ($check == 'yes') { 
                 
                    $this->db->where('is_head_office', 'yes');
                    $head_office_count = $this->db->count_all_results('warehouse'); 

                    
                    if ($head_office_count >= 3) {
                        if($this->input->is_ajax_request()) {
                            $response = array();
                            $response['code'] = 0;
                            $response['message'] = 'Head office limit reached. You can only have 3 head offices.';
                            echo json_encode($response);
                        } else {
                            $this->session->set_flashdata('failure', 'Head office limit reached. You can only have 3 head offices.');
                            redirect('warehouse', 'refresh');
                        }
                        return; 
                    }
                }

                if($is_default == WAREHOUSE_IS_DEFAULT_YES)
                    $this->warehouse_model->remove_default();
                
                $data = array(
                    "code"               => $code,
                    "is_head_office"     => $check,
                    "name"               => $name,
                    "description"        => $description,
                    "is_default"         => $is_default,
                    "license_no"         => $license_no,
                    "country_id"         => $country_id,
                    "state_id"           => $state_id,
                    "city_id"            => $city_id,
                    "address_line1"      => $address_line1,
                    "address_line2"      => $address_line2,
                    "pincode"            => $pincode,
                    "user_id"            => $this->session->userdata('user_id')
                );

                // Begin transaction
                $this->db->trans_begin();
                
                if($id = $this->warehouse_model->add_record($data))
                {
                    // Commit transaction
                    $this->db->trans_commit();

                    if($this->input->is_ajax_request())
                    {   
                        $response = array();
                        $response['code'] = 1;
                        $response['id'] = $id;
                        $response['message'] = 'Branch is added successfully.';
                        $response['warehouses'] = $this->warehouse_model->get_records();
                        echo json_encode($response);
                    }
                    else
                    {
                        $this->session->set_flashdata('success', 'Branch is added successfully');
                        redirect('warehouse', 'refresh');
                    }
                }
                else
                {
                    // Rollback transaction
                    $this->db->trans_rollback();

                    if($this->input->is_ajax_request())
                    {   
                        $response = array();
                        $response['code'] = 0;
                        $response['message'] = 'Branch is failed to add.';
                        echo json_encode($response);
                    }
                    else
                    {
                        $this->session->set_flashdata('failure', 'Branch is failed to add.');
                        redirect('warehouse', 'refresh');
                    }
                }
            }
        }
        else
        {
            $data = array();
            $country_id = $this->company_settings_model->get_company_records()->country_id;
            $state_id = $this->company_settings_model->get_company_records()->state_id;

            $data['countries'] = $this->utility_model->get_countries();
            $data['states'] = $this->utility_model->get_states($country_id);
            $data['cities'] = $this->utility_model->get_cities($state_id);
            
            if($this->input->is_ajax_request()) 
            {
                $response = array();
                $response['code'] = 1;                        
                $response['add_warehouse_modal_body'] = $this->load->view('warehouse/ajax/add_warehouse_modal_body', $data, TRUE);
                echo json_encode($response);
            }
            else
            {
                $this->load->view('warehouse/add', $data);
            }
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
            $this->form_validation->set_rules("code","Code","required|callback_is_unique_code[$id]");
            $this->form_validation->set_rules("name","Name","required");

            if($this->form_validation->run() == FALSE)
            {
                if($this->input->is_ajax_request())
                {
                    $data['code'] = 2;
                    $data['errors'] = $this->form_validation->error_array();
                    echo json_encode($data);
                }
                else
                { 
                    $this->load->view('warehouse/edit');
                }
            }
            else
            {
                $code = $this->input->post("code");
                $name = $this->input->post("name");
                $description = $this->input->post("description");
                $is_default = $this->input->post("is_default");
                $license_no  = $this->input->post("license_no");
                $country_id = $this->input->post("country_id");
                $state_id = $this->input->post("state_id");
                $city_id = $this->input->post("city_id");
                $address_line1 = $this->input->post("address_line1");
                $address_line2 = $this->input->post("address_line2");
                $pincode = $this->input->post("pincode");
 
                if($is_default == WAREHOUSE_IS_DEFAULT_YES)
                    $this->warehouse_model->remove_default();

                $data = array(
                    "code"          => $code,
                    "name"          => $name,
                    "description"   => $description,
                    "is_default"    => $is_default,
                    "country_id"    => $country_id,
                    "state_id"      => $state_id,
                    "city_id"       => $city_id,
                    "address_line1" => $address_line1,
                    "address_line2" => $address_line2,
                    "pincode"       => $pincode,
                    "license_no"    =>$license_no,
                    "user_id"       => $this->session->userdata('user_id')
                );

                if($this->warehouse_model->edit_record($data, $id))
                {
                    if($this->input->is_ajax_request()) 
                    {
                        $response['code'] = 1;
                        $response['message'] = 'Branch is updated successfully';
                        echo json_encode($response);
                    }
                    else
                    {
                        $this->session->set_flashdata('success', 'Branch is updated successfully');
                        redirect('warehouse', 'refresh');
                    }
                }
                else
                {
                    if($this->input->is_ajax_request()) 
                    {
                        $response['code'] = 0;
                        $response['message'] = 'Branch is failed to update';
                        echo json_encode($response);
                    }
                    else
                    {
                        $this->session->set_flashdata('failure', 'Branch is failed to update');
                        redirect('warehouse', 'refresh');    
                    }
                }
            }
        }
        else
        {
            if($this->input->is_ajax_request()) 
            {
                $id = base64_decode($id);
                $data['warehouse'] = $this->warehouse_model->get_single_record($id);
                $data['countries'] = $this->utility_model->get_countries();
                if($data['warehouse']->country_id != '')
                    $data['states'] = $this->utility_model->get_states($data['warehouse']->country_id);
                if($data['warehouse']->state_id != '')
                    $data['cities'] = $this->utility_model->get_cities($data['warehouse']->state_id);
                
                $response = array();
                $response['code'] = 1;
                $response['edit_warehouse_modal_body'] = $this->load->view('warehouse/ajax/edit_warehouse_modal_body', $data, TRUE);
                echo json_encode($response);
            }
            else
            {
                $id = base64_decode($id);
                $data['warehouse'] = $this->warehouse_model->get_single_record($id);
                if($data['warehouse'] != null)
                {
                    $data['countries'] = $this->utility_model->get_countries();
                    if($data['warehouse']->country_id != '')
                        $data['states'] = $this->utility_model->get_states($data['warehouse']->country_id);
                    if($data['warehouse']->state_id != '')
                        $data['cities'] = $this->utility_model->get_cities($data['warehouse']->state_id);
                    $this->load->view('warehouse/list', $data);
                }
                else
                {
                    $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                    redirect('warehouse/');
                }
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
			$id 							= $this->input->post('id');

			$data = array('delete_status' => 1);

			$warehouse =  $this->warehouse_model->get_single_record($id);

			if($this->warehouse_model->edit_record($data,$id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $id;
					$response['message']			= 'Branch is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Branch is deleted successfully');
					redirect('warehouse','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Branch is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Branch is failed to delete');
					redirect('warehouse');
				}
			}
		}
	}

	function get_record_details()
	{
		$warehouse_id = $this->input->post('warehouse_id');

		$response = array();
		$response['code'] = 1;
		$response['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);

		echo json_encode($response);
	}


  public function warehouse_delete_confirmation()
  {
  	$warehouse_id 				= $this->input->post('warehouse_id');
  	$data['warehouse']		= $this->warehouse_model->get_single_record($warehouse_id);

  	$response 																		= array();
  	$response['delete_warehouse_modal_body'] 	= $this->load->view('warehouse/ajax/delete_warehouse_modal_body',$data,TRUE);

  	echo json_encode($response);
	}

  public function is_unique_code($code, $id)
{
    $warehouse = $this->utility_model->get_records_by_field('warehouse', 'code', $code, $row = true, $check_delete_status = true);
   
    if ($warehouse != null) 
    {
        if ($id == $warehouse->id)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('is_unique_code', 'Branch code already exists.');
            return false;
        }
    }
    else 
    {
        return true;
    }
}



	// public function is_unique_code($id)
	// {
	// 	$code = $this->input->post('code');
	// 	$warehouses = $this->utility_model->get_records_by_field('warehouse', 'code', $code, false, true);

  //   if ($warehouses == null) {
  //       return true;
  //   } else {
  //       foreach ($warehouses as $warehouse) {
  //           if ($warehouse->id != $id) {
  //               $this->form_validation->set_message('is_unique_code', 'Warehouse code is already exist.');
  //               return false;
  //           }
  //       }
  //       return true;
  //   }
	// }
	public function ajax_list()
  {
    $list 		= $this->warehouse_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		$table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

     // Edit Button        
      if($this->permission_model->has_permission('edit_warehouse'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_warehouse_modal" data-tt="tooltip" title="'.$this->lang->line('warehouse_edit').'" class="btn btn-info btn-xs edit_warehouse_modal mr-2" data-warehouse_id="'.base64_encode($item->id).'">
	                          <i class="fas fa-edit"></i>
	                        </a>';
			}

			// Delete Button
			if($this->permission_model->has_permission('delete_warehouse'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_warehouse_modal" data-tt="tooltip" title="'.$this->lang->line('warehouse_delete').'" class="btn btn-danger btn-xs delete_warehouse_modal" data-warehouse_id="'.$item->id.'">
	                          <i class="fas fa-trash"></i> 
	                        </a>';
			}

			/* End Action column buttons*/

     	$products  = $this->warehouse_products_model->get_records_by_warehouse_id($item->id);
     	$product_quantity = 0;

      if(sizeof($products) > 0)
      {
        foreach ($products as $product) {
          $product_quantity += $product->quantity; 
        } 
      }

			$is_default = ($item->is_default == WAREHOUSE_IS_DEFAULT_YES) ? '<span class="badge badge-success">DEFAULT</span>' : '';      


      $row = array();
     
      $row[] = $item->name.'<br/>'.$is_default;
      $row[] = $item->code;
      $row[] = $item->description;
  	  $row[] = $product_quantity;
   	  $row[] = ($item->is_head_office == 'yes') ? '<span style="color: green;">' . $item->is_head_office . '</span>' : $item->is_head_office;
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


  /* warehouse product warehouse wise.  */

  public function warehouse_products_by_warehouse_id($warehouse_id)
  {
  	$data['warehouse_products'] = $this->warehouse_products_model->get_records_by_warehouse_id($warehouse_id);
  	echo json_encode($data['warehouse_products']);
  }
	
}
