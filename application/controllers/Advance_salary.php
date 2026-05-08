<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Advance_salary extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    if(!$this->permission_model->has_permission('list_advance_salary'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['advance_salaries'] = $this->advance_salary_model->get_records();
      $data['employees']        = $this->employee_model->get_records();
      
      $this->load->view('advance_salary/index',$data);
    }
  }

  public function add($advance_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('employee_id','Employee Name','required');
      $this->form_validation->set_rules('amount','Amount','required');        
     
      
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
          $data['advance_salary'] = null;
          if($advance_id != null)
            $data['advance_salary'] = $this->advance_salary_model->get_single_record($advance_id);

          $this->load->view('advance_salary/add',$data);
        }
      }
      else
      {
        $employee_id     = $this->input->post('employee_id');
        $amount          = $this->input->post('amount');
        $status          = $this->input->post('status');

        $this->db->trans_begin();

        $data       =   array(
                              "employee_id"   =>  $employee_id,
                              "amount"        =>  $amount,
                              "status"        =>  $status
                              
                            );

        $request_date     = $this->input->post('request_date');

        if($request_date != '')
          $data['request_date'] = date('Y-m-d',strtotime($request_date));

        if($advance_id == NULL)
        {
          if($id = $this->advance_salary_model->add_record($data))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Advance salary is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Advance salary is added successfully');
              redirect('advance_salary','refresh');  
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
              $response['message'] = 'Advance salary is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Advance salary is failed to add.');
              redirect('advance_salary','refresh');
            }
          }   
        }
        else
        {
          if($this->advance_salary_model->edit_record($data,$advance_id))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Advance salary is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Advance salary is updated successfully');
              redirect('advance_salary','refresh');
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
              $response['message'] = 'Advance salary is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Advance salary is failed to update.');
              redirect('advance_salary','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['advance_salary'] = null;
      $data['employees'] = $this->employee_model->get_records();

      if($advance_id != null)
      {
        $data['advance_salary']       = $this->advance_salary_model->get_single_record($advance_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_advance_salary_modal_body'] = $this->load->view('advance_salary/ajax/add_advance_salary_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_advance_salary_modal_body'] = $this->load->view('advance_salary/ajax/add_advance_salary_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_advance_salary'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$advance_id 							= $this->input->post('advance_id');

			$data = array('delete_status' => 1);

			$advance_salary =  $this->advance_salary_model->get_single_record($advance_id);

			if($this->advance_salary_model->edit_record($data,$advance_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $advance_id;
					$response['message']			= 'Advance salary is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Advance salary is deleted successfully');
					redirect('advance_salary','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Advance salary is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Advance salary is failed to delete');
					redirect('advance_salary');
				}
			}
		}
	}

  


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->advance_salary_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //View Button
      if($this->permission_model->has_permission('edit_advance_salary'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('advance_salary_edit').'" class="btn btn-info btn-xs add_advance_salary_modal" data-advance_id="'.$item->advance_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_advance_salary'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_advance_salary" data-tt="tooltip" title="'.$this->lang->line('advance_salary_delete').'" class="btn btn-danger btn-xs delete_advance_salary" data-advance_id="'.$item->advance_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }

          /* End Action column buttons*/

          
      $statusBadge = '';
      if ($item->status == 'pending') 
      {
          $statusBadge = '<span class="badge badge-warning">Pending</span>';
      } 
      elseif ($item->status == 'approved') 
      {
          $statusBadge = '<span class="badge badge-success">Approved</span>';
      } 
      elseif ($item->status == 'rejected') 
      {
          $statusBadge = '<span class="badge badge-danger">Rejected</span>';
      }

      $select_html = '<input type="checkbox" class="single_advance_salary" value="'.$item->advance_id.'" data-advance_id="'.$item->advance_id.'"><input type="hidden" name="advance_id" id="advance_id" data-advance_id="'.$item->advance_id.'" value="'.$item->advance_id.'">';

      $employee_name_html = '<a href="'.base_url('employee/view/'.base64_encode($item->employee_id)).'" data-tt="tooltip" title="'.$this->lang->line('employee_view').'">'.$item->employee_name.'</a>';   

      $row = array();
   
      $row[] = $select_html;
      $row[] = $employee_name_html;
      $row[] = ($item->request_date != '' && $item->request_date != '0000-00-00') ? date('d-m-Y',strtotime($item->request_date)) : '';
      $row[] = $item->amount;
      $row[] = $statusBadge;
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->advance_salary_model->count_all(),
                    "recordsFiltered"   => $this->advance_salary_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function advance_salary_delete_confirmation()
  {
  	$advance_id 					= $this->input->post('advance_id');
  	$data['advance_salary'] 		= $this->advance_salary_model->get_single_record($advance_id);

    
    // Get employee_id and leave_date
    $employee_id = $data['advance_salary']->employee_id;
    $request_date = $data['advance_salary']->request_date;
    $payroll_month = date('Y-m', strtotime($request_date));

    // Check if payroll exists for that month-year
    $data['payroll_exists'] = $this->payroll_history_model->get_employee_payroll_status($employee_id, $payroll_month);


  	$response 					= array();
  	$response['advance_salary_delete_modal_body'] 	= $this->load->view('advance_salary/ajax/advance_salary_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export()
  {
      // Get parameters from query string
      $advance_ids = explode(',', $this->input->get('data'));
      $employee_id    = $this->input->get('employee_id');
      $from_date      = $this->input->get('from_date');
      $to_date        = $this->input->get('to_date');
      $amount     = $this->input->get('amount');
      $status         = $this->input->get('status');
  
      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'l.delete_status = 0'; 
  
      // Add conditions based on parameters
      if (!empty($advance_ids)) {
          $whereConditions[] = 'l.advance_id IN (' . implode(',', array_map('intval', $advance_ids)) . ')';
      }
  
      if (!empty($employee_id)) {
          $whereConditions[] = 'l.employee_id = ' . intval($employee_id);
      }
  
      if (!empty($from_date) && !empty($to_date)) {
          // Ensure dates are in the correct format for SQL query
          $from_date = date('Y-m-d', strtotime($from_date));
          $to_date = date('Y-m-d', strtotime($to_date));
          $whereConditions[] = 'l.request_date BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '"';
      }
  
   
      if (!empty($status)) {
          $whereConditions[] = 'l.status = "' . $this->db->escape_str($status) . '"';
      }
  
      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }
  
      // Query to fetch advance salary data
      $query = $this->db->query('SELECT 
                                      l.employee_name as "Employee Name",
                                      DATE_FORMAT(l.request_date, "%d-%m-%Y") as "Request Date",
                                      l.amount as "Amount",
                                      l.status as "Status"
                                  FROM 
                                      advance_salary_view l
                                  ' . $whereClause . '
                                  ORDER BY l.employee_name ASC');
  
      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');
  
      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);
  
      // Set filename for download
      $filename = 'ADVANCE SALARY.csv';
  
      // Download the CSV file
      force_download($filename, $data);
  }

 
}
