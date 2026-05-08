<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_attendance'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['attendances'] = $this->attendance_model->get_records();
      $data['employees']        = $this->employee_model->get_records();
      $this->load->view('attendance/index',$data);
    }
  }

  public function add($attendance_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('employee_id','Employee Name','required');        
      $this->form_validation->set_rules('attendance_date','Attendance Date','required|callback_is_unique_date[' . $attendance_id . ']');
     
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
          $data['attendance'] = null;
          if($attendance_id != null)
            $data['attendance'] = $this->attendance_model->get_single_record($attendance_id);

          $this->load->view('attendance/add',$data);
        }
      }
      else
      {
        $employee_id        = $this->input->post('employee_id');
        $attendance_date    =  date('Y-m-d',strtotime($this->input->post('attendance_date')));
        $attendance_status  = $this->input->post('attendance_status');
       

        $this->db->trans_begin();

        $data  =    array(
                        "employee_id"       =>  $employee_id,
                        "attendance_date"   =>  $attendance_date,
                        "attendance_status" =>  $attendance_status
                    );

        // if($attendance_date != '')
        //   $data['attendance_date'] = date('Y-m-d',strtotime($attendance_date));
        
        $attendance = $this->utility_model->get_records_by_field_value('hr_attendance','employee_id','attendance_date',$employee_id,$attendance_date,$row = true,$check_delete_status = false);


        if($attendance_id == NULL && $attendance == NULL)
        {

          if($id = $this->attendance_model->add_record($data))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = ($attendance == null) ? 'Attendance is added successfully' : 'Attendance is updated successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Attendance is added successfully');
              redirect('attendance','refresh');  
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
              $response['message'] = 'Attendance is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Attendance is failed to add.');
              redirect('attendance','refresh');
            }
          }   
        }
        else
        {
          $attendance_id = ($attendance_id != null) ? $attendance_id : $attendance->attendance_id;
          
          if($attendance_date != '')
          {
            if($attendance_status != '')
            {
              if($this->attendance_model->edit_record($data,$attendance_id))
              {
                // commit transaction
                $this->db->trans_commit();

                if($this->input->is_ajax_request())
                {
                  $response = array();
                  $response['code'] = RESPONSE_SUCCESS;
                  $response['message'] = 'Attendance is updated successfully';

                  echo json_encode($response);  
                }
                else
                { 
                  $this->session->set_flashdata('success', 'Attendance is updated successfully');
                  redirect('attendance','refresh');
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
                  $response['message'] = 'Attendance is failed to update.';

                  echo json_encode($response);  
                }
                else
                { 
                  $this->session->set_flashdata('failure', 'Attendance is failed to update.');
                  redirect('attendance','refresh');
                }
              }
            }
            else
            {
              if($this->attendance_model->delete_record($attendance->attendance_id,$soft_delete = FALSE))
              {
                $response = array();
                $response['code'] = RESPONSE_SUCCESS;
                $response['message'] = 'Attendance is updated successfully';

                echo json_encode($response);  
              }
              else
              {
                $response = array();
                $response['code'] = RESPONSE_FAILURE;
                $response['message'] = 'Attendance is failed to update.';

                echo json_encode($response);  
              }
            }
            
          }
          else
          {
            if($this->attendance_model->delete_record($attendance->attendance_id,$soft_delete = FALSE))
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Attendance is updated successfully';

              echo json_encode($response);  
            }
            else
            {
              $response = array();
              $response['code'] = RESPONSE_FAILURE;
              $response['message'] = 'Attendance is failed to update.';

              echo json_encode($response);  
            }
            
          }
             
        }
      }
    }
    else
    {
      $data['attendance'] = null;
      $data['employees'] = $this->employee_model->get_records();

      if($attendance_id != null)
      {
        $data['attendance']       = $this->attendance_model->get_single_record($attendance_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_attendance_modal_body'] = $this->load->view('attendance/ajax/add_attendance_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_attendance_modal_body'] = $this->load->view('attendance/ajax/add_attendance_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_attendance'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$attendance_id 							= $this->input->post('attendance_id');

			$data = array('delete_status' => 1);

			$attendance =  $this->attendance_model->get_single_record($attendance_id);

			if($this->attendance_model->edit_record($data,$attendance_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $attendance_id;
					$response['message']			= 'Attendance is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Attendance is deleted successfully');
					redirect('attendance','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Attendance is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Attendance is failed to delete');
					redirect('attendance');
				}
			}
		}
	}

  public function is_unique_date($attendance_date, $attendance_id = null)
  {
      $attendance_date = date('Y-m-d', strtotime($attendance_date));
      $attendance = $this->utility_model->get_records_by_field('hr_attendance', 'attendance_date', $attendance_date, $row = true, $check_delete_status = true);
      $employee_id = $this->input->post('employee_id');
  
      if ($attendance != null) 
      {
          if ($attendance->attendance_id == $attendance_id)
          {
              return true;
          }
          else
          {
            if($employee_id == $attendance->employee_id)
            {
              $this->form_validation->set_message('is_unique_date', 'Attendance date already exists.');
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

  public function get_calendar() 
  {
    $employee_id = $this->input->post('employee_id');
    $year = $this->input->post('year');
    $month = $this->input->post('month');

    // echo $employee_id;
    // echo $month;
    // echo $year;

    $data['attendance'] = $this->attendance_model->getAttendance($employee_id, $year, $month);
    $data['month'] = $month;
    $data['year'] = $year;
    $data['employee_id'] = $employee_id;


    // Debug: Output fetched data
    // echo "<pre>";
    //  print_r($data['attendance']); 
    //  echo "</pre>";
    //  exit;
    // Format the payroll_month as 'YYYY-MM'
    $data['payroll_month'] = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);

    //$data['payroll_exists'] = $this->payroll_history_model->get_employee_payroll_status($employee_id, $payroll_month);

    // Load the calendar view and return it as JSON
    $calendar_html = $this->load->view('attendance/ajax/calendar_view', $data, TRUE);

    $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(array('calendar_html' => $calendar_html)));
  }


  public function bulk_attendance()
  {
      if(!$this->permission_model->has_permission('add_attendance'))
      {
          $this->load->view('errors/html/error_restricted'); 
      }
      else
      {
        if($this->input->is_ajax_request()) 
        {
            $data['employees'] = $this->employee_model->get_records();
            
            $response = array();
            $response['code'] = 1;
            $response['bulk_attendance_modal_body'] = $this->load->view('attendance/ajax/bulk_attendance_modal_body', $data, TRUE);

            echo json_encode($response);
        }
        else
        {
            $this->load->view('errors/html/error_restricted'); 
        } 
      }
  }


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->attendance_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //View Button
      if($this->permission_model->has_permission('edit_attendance'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('attendance_edit').'" class="btn btn-info btn-xs add_attendance_modal" data-attendance_id="'.$item->attendance_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_attendance'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_attendance" data-tt="tooltip" title="'.$this->lang->line('attendance_delete').'" class="btn btn-danger btn-xs delete_attendance" data-attendance_id="'.$item->attendance_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }

          /* End Action column buttons*/

        $attendance_status = strtoupper($item->attendance_status);

        $attendanceStatusColor = '';

        if ($attendance_status == ATTENDANCE_STATUS_PRESENT) 
        {
          $attendanceStatusColor = '<span style="color: green;">PRESENT</span>';
        } 
        elseif ($attendance_status == ATTENDANCE_STATUS_ABSENT) 
        {
          $attendanceStatusColor = '<span style="color: red;">ABSENT</span>';
        } 
        elseif ($attendance_status == ATTENDANCE_STATUS_HALF_LEAVE) 
        {
          $attendanceStatusColor = '<span style="color: blue;">HALF LEAVE</span>';
        } 
        elseif ($attendance_status == ATTENDANCE_STATUS_THIRD_FOURTH_LEAVE) 
        {
          $attendanceStatusColor = '<span style="color: purple;">THIRD FOURTH LEAVE</span>';
        } 
        // elseif ($attendance_status == ATTENDANCE_STATUS_QUARTER_LEAVE) 
        // { // Corrected spelling from 'qurter' to 'quarter'
        //   $attendanceStatusColor = '<span style="color: orange;">QUARTER LEAVE</span>';
        // }

        $select_html = '<input type="checkbox" class="single_attendance" value="'.$item->attendance_id.'" data-attendance_id="'.$item->attendance_id.'"><input type="hidden" name="attendance_id" id="attendance_id" data-attendance_id="'.$item->attendance_id.'" value="'.$item->attendance_id.'">';

        $employee_name_html = '<a href="'.base_url('employee/view/'.base64_encode($item->employee_id)).'" data-tt="tooltip" title="'.$this->lang->line('employee_view').'">'.$item->employee_name.'</a>';     

      $row = array();
   
      $row[] = $select_html;
      $row[] =  $employee_name_html;
      $row[] = ($item->attendance_date != '' && $item->attendance_date != '0000-00-00') ? date('d-m-Y',strtotime($item->attendance_date)) : '';
      //$row[] = $item->attendance_status;
      $row[] = $attendanceStatusColor;
     
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->attendance_model->count_all(),
                    "recordsFiltered"   => $this->attendance_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function attendance_delete_confirmation()
  {
  	$attendance_id 					= $this->input->post('attendance_id');
  	$data['attendance'] 		= $this->attendance_model->get_single_record($attendance_id);

    // Get employee_id and leave_date
    $employee_id = $data['attendance']->employee_id;
    $attendance_date = $data['attendance']->attendance_date;
    $payroll_month = date('Y-m', strtotime($attendance_date));

    // Check if payroll exists for that month-year
    $data['payroll_exists'] = $this->payroll_history_model->get_employee_payroll_status($employee_id, $payroll_month);

  	$response 					= array();
  	$response['attendance_delete_modal_body'] 	= $this->load->view('attendance/ajax/attendance_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export()
  {
      // Get parameters from query string
      $attendance_ids = explode(',', $this->input->get('data'));
      $employee_id = $this->input->get('employee_id');
      $month = $this->input->get('month');
      $year = $this->input->get('year');
      $status = $this->input->get('status');

      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'a.delete_status = 0';

      // Add conditions based on parameters
      if (!empty($attendance_ids)) {
          $whereConditions[] = 'a.attendance_id IN (' . implode(',', $attendance_ids) . ')';
      }

      if (!empty($employee_id)) {
          $whereConditions[] = 'a.employee_id = ' . $employee_id;
      }

      if (!empty($month)) {
          $whereConditions[] = 'MONTH(a.attendance_date) = ' . $month;
      }

      if (!empty($year)) {
          $whereConditions[] = 'YEAR(a.attendance_date) = ' . $year;
      }

      if (!empty($status)) {
          $whereConditions[] = 'a.attendance_status = "' . $status . '"';
      }

      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }

      // Query to fetch attendance data
      $query = $this->db->query('SELECT 
                                      a.employee_name as "Employee Name",
                                      a.attendance_date as "Attendance Date",
                                      CASE
                                          WHEN a.attendance_status = "'. ATTENDANCE_STATUS_PRESENT .'" THEN "PRESENT"
                                          WHEN a.attendance_status = "'. ATTENDANCE_STATUS_ABSENT .'" THEN "ABSENT"
                                          WHEN a.attendance_status = "'. ATTENDANCE_STATUS_HALF_LEAVE .'" THEN "HALF LEAVE"
                                          WHEN a.attendance_status = "'. ATTENDANCE_STATUS_THIRD_FOURTH_LEAVE .'" THEN "THIRD FOURTH LEAVE"
                                          WHEN a.attendance_status = "'. ATTENDANCE_STATUS_QUARTER_LEAVE .'" THEN "QUARTER LEAVE"
                                          ELSE ""
                                      END as "Attendance Status"
                                  FROM 
                                      attendance_view a
                                  ' . $whereClause . '
                                  ORDER BY a.employee_name ASC');

      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');

      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);

      // Set filename for download
      $filename = 'ATTENDANCE.csv';

      // Download the CSV file
      force_download($filename, $data);
  }

  
  

}
