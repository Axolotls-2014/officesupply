<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_department'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['departments'] = $this->department_model->get_records();
      $this->load->view('department/index',$data);
    }
  }

  public function add($department_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('department_name','Department Name','required');        
      
      
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
          $data['department'] = null;
          if($department_id != null)
            $data['department'] = $this->department_model->get_single_record($department_id);

          $this->load->view('department/add',$data);
        }
      }
      else
      {
        $department_name   = $this->input->post('department_name');

        $department_code 				= $this->department_model->get_lastest_sequence_number();
       
        $this->db->trans_begin();

        $data       =   array(
                              "department_name"    =>  $department_name,
                              "department_code"    =>  $department_code
                            );

        if($department_id == NULL)
        {
          if($id = $this->department_model->add_record($data))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['id']   = $id;
              $response['message'] = 'Department is added successfully';
              $response['departments'] = $this->department_model->get_records();

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Department is added successfully');
              redirect('department','refresh');  
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
              $response['message'] = 'Department is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Department is failed to add.');
              redirect('department','refresh');
            }
          }   
        }
        else
        {
          if($this->department_model->edit_record($data,$department_id))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Department is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Department is updated successfully');
              redirect('department','refresh');
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
              $response['message'] = 'Department is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Department is failed to update.');
              redirect('department','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['department'] = null;

      if($department_id != null)
      {
        $data['department']       = $this->department_model->get_single_record($department_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_department_modal_body'] = $this->load->view('department/ajax/add_department_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_department_modal_body'] = $this->load->view('department/ajax/add_department_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_department'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$department_id 							= $this->input->post('department_id');

			$data = array('delete_status' => 1);

			$department =  $this->department_model->get_single_record($department_id);
    

			if($this->department_model->edit_record($data,$department_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $department_id;
					$response['message']			= 'Department is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Department is deleted successfully');
					redirect('department','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Department is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Department is failed to delete');
					redirect('department');
				}
			}
		}
	}

  

  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->department_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //View Button
      if($this->permission_model->has_permission('edit_department'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('department_edit').'" class="btn btn-info btn-xs add_department_modal" data-department_id="'.$item->department_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_department'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_department" data-tt="tooltip" title="'.$this->lang->line('department_delete').'" class="btn btn-danger btn-xs delete_department" data-department_id="'.$item->department_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }

      /* End Action column buttons*/

      $select_html = '<input type="checkbox" class="single_department" value="'.$item->department_id.'" data-department_id="'.$item->department_id.'"><input type="hidden" name="department_id" id="department_id" data-department_id="'.$item->department_id.'" value="'.$item->department_id.'">';

          

      $row = array();
   
      $row[] = $select_html;
      $row[] = $item->department_code;
      $row[] = $item->department_name;
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->department_model->count_all(),
                    "recordsFiltered"   => $this->department_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function department_delete_confirmation()
  {
  	$department_id 					= $this->input->post('department_id');
  	$data['department'] 		= $this->department_model->get_single_record($department_id);
    $data['employee']               =  $this->department_model->check_employee_exists($department_id);

  	$response 					    = array();
  	$response['department_delete_modal_body'] 	= $this->load->view('department/ajax/department_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export_from_import_employee()
  {
    $query = $this->department_model->export_records();
    $this->load->dbutil();
    
    $data = $this->dbutil->csv_from_result($query);
    
    $this->load->helper('download');
    force_download("DEPARTMENT.CSV", $data);
  }

  public function export()
  {
      // Get parameters from query string
      $department_ids = explode(',', $this->input->get('data'));
    
      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'l.delete_status = 0'; // Add condition for delete_status
    
      // Add conditions based on parameters
      if (!empty($department_ids)) {
          $whereConditions[] = 'l.department_id IN (' . implode(',', array_map('intval', $department_ids)) . ')';
      }

      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }
    
      // Query to fetch data
      $query = $this->db->query('SELECT 
                                      l.department_code as "Department Code",
                                      l.department_name as "Department Name"
                                  FROM 
                                      hr_departments l
                                  ' . $whereClause . '
                                  ORDER BY l.department_name ASC');

      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');

      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);

      // Set filename for download
      $filename = 'DEPARTMENT.csv';

      // Download the CSV file
      force_download($filename, $data);
  }
 
}
