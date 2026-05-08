<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_leave'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['leaves'] = $this->leave_model->get_records();
      $data['employees']        = $this->employee_model->get_records();
      $this->load->view('leave/index',$data);
    }
  }

  public function add($leave_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('employee_id','Employee Name','required');
      $this->form_validation->set_rules('leave_date','Leave Date','required|callback_is_unique_date[' . $leave_id . ']');
      $this->form_validation->set_rules('leave_type','Leave Type','required');
      $this->form_validation->set_rules('status','Status','required');
     
      
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
          $data['leave'] = null;
          if($leave_id != null)
            $data['leave'] = $this->leave_model->get_single_record($leave_id);

          $this->load->view('leave/add',$data);
        }
      }
      else
      {
        $employee_id     = $this->input->post('employee_id');
        $leave_type      = $this->input->post('leave_type');
        $status          = $this->input->post('status');

        $this->db->trans_begin();

        $data       =   array(
                              "employee_id"   =>  $employee_id,
                              "leave_type"    =>  $leave_type,
                              "status"        =>  $status
                              
                            );

        $leave_date     = $this->input->post('leave_date');

        if($leave_date != '')
          $data['leave_date'] = date('Y-m-d',strtotime($leave_date));

        if($leave_id == NULL)
        {
          if($id = $this->leave_model->add_record($data))
          {

            $leave = $this->leave_model->get_single_record($id);

						if ($leave->status == 'approved') {
              $attendance_status = '';

              if ($leave->leave_type == LEAVE_TYPE_HALF) {
                  $attendance_status = '0.5';
              } elseif ($leave->leave_type == LEAVE_TYPE_FULL) {
                  $attendance_status = '0';
              } elseif ($leave->leave_type == LEAVE_TYPE_ONE_FORTH_LEAVE) {
                  $attendance_status = '0.75';
              }

             

              $attendance = array(
                  "employee_id" => $leave->employee_id,
                  "attendance_date" => $leave->leave_date,
                  "attendance_status" => $attendance_status
              );

              
              $this->attendance_model->add_record($attendance);

            }

            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Leave is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Leave is added successfully');
              redirect('leave','refresh');  
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
              $response['message'] = 'Leave is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Leave is failed to add.');
              redirect('leave','refresh');
            }
          }   
        }
        else
        {
          if($this->leave_model->edit_record($data,$leave_id))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Leave is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Leave is updated successfully');
              redirect('leave','refresh');
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
              $response['message'] = 'Leave is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Leave is failed to update.');
              redirect('leave','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['leave'] = null;
      $data['employees'] = $this->employee_model->get_records();

      if($leave_id != null)
      {
        $data['leave']       = $this->leave_model->get_single_record($leave_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_leave_modal_body'] = $this->load->view('leave/ajax/add_leave_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_leave_modal_body'] = $this->load->view('leave/ajax/add_leave_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_leave'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$leave_id 							= $this->input->post('leave_id');

			$data = array('delete_status' => 1);

			$leave =  $this->leave_model->get_single_record($leave_id);

			if($this->leave_model->edit_record($data,$leave_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $leave_id;
					$response['message']			= 'Leave is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Leave is deleted successfully');
					redirect('leave','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Leave is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Leave is failed to delete');
					redirect('leave');
				}
			}
		}
	}

  public function is_unique_date($leave_date, $leave_id = null)
  {
      $leave_date = date('Y-m-d', strtotime($leave_date));
      $leave = $this->utility_model->get_records_by_field('hr_leaves', 'leave_date', $leave_date, $row = true, $check_delete_status = true);
      $employee_id = $this->input->post('employee_id');
  
      if ($leave != null) 
      {
          if ($leave->leave_id == $leave_id)
          {
              return true;
          }
          else
          {
            if($employee_id == $leave->employee_id)
            {
              $this->form_validation->set_message('is_unique_date', 'Leave date already exists.');
              return false;
            }
            else
            {
              return true;
            }
              
          }
      }
      else 
      {
          return true;
      }
  }
  
  


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->leave_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //View Button
      if($this->permission_model->has_permission('edit_leave'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('leave_edit').'" class="btn btn-info btn-xs add_leave_modal" data-leave_id="'.$item->leave_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_leave'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_leave" data-tt="tooltip" title="'.$this->lang->line('leave_delete').'" class="btn btn-danger btn-xs delete_leave" data-leave_id="'.$item->leave_id.'">
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


        $leaveType = strtoupper($item->leave_type);

        $leaveTypeColor = '';

        if ($leaveType == 'FULL') 
        {
          $leaveTypeColor = '<span style="color: green;">' . $leaveType . '</span>';
        } 
        elseif ($leaveType == 'HALF') 
        {
          $leaveTypeColor = '<span style="color: blue;">' . $leaveType . '</span>';
        } 
        elseif ($leaveType == 'ONE FORTH LEAVE') 
        { // Corrected spelling from 'qurter' to 'quarter'
          $leaveTypeColor = '<span style="color: orange;">' . $leaveType . '</span>';
        }

        $select_html = '<input type="checkbox" class="single_leave" value="'.$item->leave_id.'" data-leave_id="'.$item->leave_id.'"><input type="hidden" name="leave_id" id="leave_id" data-leave_id="'.$item->leave_id.'" value="'.$item->leave_id.'">';

        $employee_name_html = '<a href="'.base_url('employee/view/'.base64_encode($item->employee_id)).'" data-tt="tooltip" title="'.$this->lang->line('employee_view').'">'.$item->employee_name.'</a>';   
          

      $row = array();

      
      $row[] = $select_html;
      $row[] = $employee_name_html;
      $row[] = ($item->leave_date != '' && $item->leave_date != '0000-00-00') ? date('d-m-Y',strtotime($item->leave_date)) : '';
      $row[] = $leaveTypeColor;
      $row[] = $statusBadge;
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->leave_model->count_all(),
                    "recordsFiltered"   => $this->leave_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function leave_delete_confirmation()
  {
  	$leave_id 					= $this->input->post('leave_id');
  	$data['leave'] 		= $this->leave_model->get_single_record($leave_id);

    // Get employee_id and leave_date
    $employee_id = $data['leave']->employee_id;
    $leave_date = $data['leave']->leave_date;
    $payroll_month = date('Y-m', strtotime($leave_date));

    // Check if payroll exists for that month-year
    $data['payroll_exists'] = $this->payroll_history_model->get_employee_payroll_status($employee_id, $payroll_month);

    $response 					= array();
  	$response['leave_delete_modal_body'] 	= $this->load->view('leave/ajax/leave_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export()
  {
      // Get parameters from query string
      $leave_ids = explode(',', $this->input->get('data'));
      $employee_id    = $this->input->get('employee_id');
      $from_date      = $this->input->get('from_date');
      $to_date        = $this->input->get('to_date');
      $leave_type     = $this->input->get('leave_type');
      $status         = $this->input->get('status');
  
      // Initialize where conditions array
      $whereConditions = [];
  
      // Add conditions based on parameters
      if (!empty($leave_ids)) {
          $whereConditions[] = 'l.leave_id IN (' . implode(',', array_map('intval', $leave_ids)) . ')';
      }
  
      if (!empty($employee_id)) {
          $whereConditions[] = 'l.employee_id = ' . intval($employee_id);
      }
  
      if (!empty($from_date) && !empty($to_date)) {
          // Ensure dates are in the correct format for SQL query
          $from_date = date('Y-m-d', strtotime($from_date));
          $to_date = date('Y-m-d', strtotime($to_date));
          $whereConditions[] = 'l.leave_date BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '"';
      }
  
      if (!empty($leave_type)) {
          $whereConditions[] = 'l.leave_type = "' . $this->db->escape_str($leave_type) . '"';
      }
  
      if (!empty($status)) {
          $whereConditions[] = 'l.status = "' . $this->db->escape_str($status) . '"';
      }
  
      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }
  
      // Query to fetch leave data
      $query = $this->db->query('SELECT 
                                      l.employee_name as "Employee Name",
                                      DATE_FORMAT(l.leave_date, "%d-%m-%Y") as "Leave Date",
                                      l.leave_type as "Leave Type",
                                      l.status as "Leave Status"
                                  FROM 
                                      leave_view l
                                  ' . $whereClause . '
                                  ORDER BY l.employee_name ASC');
  
      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');
  
      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);
  
      // Set filename for download
      $filename = 'LEAVE.csv';
  
      // Download the CSV file
      force_download($filename, $data);
  }
  

 
}
