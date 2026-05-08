<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    if(!$this->permission_model->has_permission('list_employee'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
        $data['employees'] = $this->employee_model->get_records();
        $this->load->view('employee/index',$data);
       }
  }

  public function add($employee_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('first_name','First Name','required');        
      $this->form_validation->set_rules('last_name','Last Name','required');
      $this->form_validation->set_rules('department_id','Department Name','required');
      $this->form_validation->set_rules('position_id','Position Name','required');
      $this->form_validation->set_rules('salary','Salary','required');   
      
      
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
          $data['employee'] = null;
          if($employee_id != null)
            $data['employee'] = $this->employee_model->get_single_record($employee_id);

          $this->load->view('employee/add',$data);
        }
      }
      else
      {
        $first_name        = $this->input->post('first_name');
        $last_name         = $this->input->post('last_name');
        $email             = $this->input->post('email');
        $department_id     = $this->input->post('department_id');
        $position_id       = $this->input->post('position_id');
        $salary            = $this->input->post('salary');
        $bank_name         = $this->input->post('bank_name');
        $account_number    = $this->input->post('account_number');
        $branch            = $this->input->post('branch');
        $ifsc_code         = $this->input->post('ifsc_code');

        $employee_code 				= $this->employee_model->get_lastest_sequence_number();
       

        $this->db->trans_begin();

        $data       =   array(
                              "first_name"    =>  $first_name,
                              "last_name"     =>  $last_name,
                              "email"         =>  $email,
                              "department_id" =>  $department_id,
                              "position_id"   =>  $position_id,
                              "salary"        =>  $salary,
                              "bank_name"       =>  $bank_name,
                              "account_number"  =>  $account_number,
                              "branch"          =>  $branch,
                              "ifsc_code"       =>  $ifsc_code,
                              "employee_code"				=> $employee_code,
                              
                            );

        $date_of_birth        = $this->input->post('date_of_birth');
        $hire_date            = $this->input->post('hire_date');
        $termination_date     = $this->input->post('termination_date');

        if($date_of_birth != '')
          $data['date_of_birth'] = date('Y-m-d',strtotime($date_of_birth));

        if($hire_date != '')
          $data['hire_date'] = date('Y-m-d',strtotime($hire_date));

        if($termination_date != '')
          $data['termination_date'] = date('Y-m-d',strtotime($termination_date));

        if($employee_id == NULL)
        {
          if($id = $this->employee_model->add_record($data))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Employee is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Employee is added successfully');
              redirect('employee','refresh');  
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
              $response['message'] = 'Employee is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Employee is failed to add.');
              redirect('employee','refresh');
            }
          }   
        }
        else
        {
          if($this->employee_model->edit_record($data,$employee_id))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Employee is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Employee is updated successfully');
              redirect('employee','refresh');
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
              $response['message'] = 'Employee is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Employee is failed to update.');
              redirect('employee','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['employee'] = null;
      $data['departments']  = $this->department_model->get_records();
      $data['positions']    = $this->position_model->get_records();

      if($employee_id != null)
      {
        $data['employee']       = $this->employee_model->get_single_record($employee_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_employee_modal_body'] = $this->load->view('employee/ajax/add_employee_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_employee_modal_body'] = $this->load->view('employee/ajax/add_employee_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_employee'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$employee_id 							= $this->input->post('employee_id');

			$data = array('delete_status' => 1);

			$employee =  $this->employee_model->get_single_record($employee_id);

			if($this->employee_model->edit_record($data,$employee_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $employee_id;
					$response['message']			= 'Employee is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Employee is deleted successfully');
					redirect('employee','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Employee is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Employee is failed to delete');
					redirect('employee');
				}
			}
		}
	}

  public function view($employee_id = null)
	{
		if(!$this->permission_model->has_permission('view_employee'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{	
			if($employee_id != null)
			{
				$employee_id = base64_decode($employee_id);

				$data['employee'] 			= $this->employee_model->get_single_record($employee_id);

				if($data['employee'] != null)
				{
          $data['attendances'] 	      = $this->attendance_model->get_records_by_employee_id($employee_id);
          $data['leaves'] 	          = $this->leave_model->get_records_by_employee_id($employee_id);
          $data['advance_salaries'] 	= $this->advance_salary_model->get_records_by_employee_id($employee_id);
          $data['bonuses'] 	          = $this->bonus_model->get_records_by_employee_id($employee_id);
          $data['deductions'] 	      = $this->deduction_model->get_records_by_employee_id($employee_id);
          $data['tax_deductions'] 	  = $this->tax_deduction_model->get_records_by_employee_id($employee_id);
          $data['payrolls'] 	        = $this->payroll_model->get_records_by_employee_id($employee_id);
				  $this->load->view('employee/view',$data);
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('employee','refresh');
				}

			
			}
			else
			{
				$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
				redirect('employee','refresh');
			}
		}
	}

  public function get_import_employee()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') 
		{
		  if (isset($_FILES["csvfile"]))
		  {
        $fileTmpPath = $_FILES["csvfile"]["tmp_name"];
        $fileName = $_FILES["csvfile"]["name"];

				$csvData = array();
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) 
        {
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
          {
               /*$csvData[] = $data;*/
               $employeeEmails[] = $data[2];
               $csvRecords[] = $data;
          }
          fclose($handle);
        }
       
     		// Escape the product names to avoid SQL injection
         $escapedEmployeeEmails = array_map(array($this->db, "escape_str"), $employeeEmails);
         $inClause = "'" . implode("','", $escapedEmployeeEmails) . "'";

        // Fetch existing employee details from the database
        $query = $this->db->query("SELECT e.*, d.department_name as department_name, p.position_title as position_title 
                                    FROM hr_employees e
                                    JOIN hr_departments d ON e.department_id = d.department_id
                                    JOIN hr_positions p ON e.position_id = p.position_id
                                    WHERE e.email IN ($inClause) AND e.delete_status = 0");
        $existingEmployees = $query->result_array();

         
        // Perform the comparison
        $matchedEmployees  = array();
        $unmatchedEmployees  = array();

				// Return comparison result as JSON
	       foreach ($employeeEmails as $employeeEmail) {
			        foreach ($existingEmployees as $employee) {
			            if ($employee['email'] === $employeeEmail) {
			                $matchedEmployees[] = $employee;
			            }
			        }
			    }

			    header('Content-Type: application/json');
					echo json_encode($matchedEmployees);

	    } 
	    else 
	    {
	        echo "Error: No file uploaded.";
	    }
		}
	}

	public function import_employee()
  {
      if(!$this->permission_model->has_permission('import_employee'))
      {
          $this->load->view('errors/html/error_restricted'); 
      }
      else
      {
          if($this->input->server('REQUEST_METHOD') === 'POST')
          {   
              $isUpdateEmployeeChecked = $this->input->post('update_employee') === '1';
              $isUpdateCreateNewIfExistChecked = $this->input->post('create_employee') === '1';

              $employees = $this->employee_model->get_records();
              $departments = $this->department_model->get_records();
              $positions = $this->position_model->get_records();

              $expectedHeaders = [
                  'FirstName',
                  'LastName',
                  'Email',
                  'DateOfBirth',
                  'DepartmentCode',
                  'PositionCode',
                  'HireDate',
                  'TerminationDate',
                  'Salary',
                  'BankName',
                  'AccountNumber',
                  'Branch',
                  'IFSC',
              ];

              $isCSVFileValid = is_csv_valid(
                  $_FILES["csvfile"]["tmp_name"],
                  ',',
                  $expectedHeaders
              );

              $file_path = $_FILES["csvfile"]["tmp_name"];
              $file = fopen($file_path, 'r');

              $header_row = fgetcsv($file, 0, $delimiter= ",");

              if ($isCSVFileValid !== true)
              {
                  $this->session->set_flashdata('failure', $isCSVFileValid);
                  redirect('employee','refresh');
              }
              else
              {
                  if(is_uploaded_file($_FILES['csvfile']['tmp_name']))
                  {
                      // Parse data from CSV file
                      $csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);

                      // Initialize the counter
                      $total_employee_imported = 0;

                      // Insert/update CSV data into database
                      if(!empty($csvData))
                      {
                          $employees_with_unknown_code = array();
                          foreach($csvData as $row)
                          { 
                              $matching_department = null;
                              $matching_position = null;

                              foreach ($departments as $department) {
                                  if ($row['DepartmentCode'] == $department->department_code) {
                                      $matching_department = $department;
                                      break;
                                  }
                              }

                              foreach ($positions as $position) {
                                  if ($row['PositionCode'] == $position->position_code) {
                                      $matching_position = $position;
                                      break;
                                  }
                              }

                              if ($matching_department !== null && $matching_position !== null) {
                                  // Validate and format date fields
                                  $dateOfBirth = false !== strtotime($row['DateOfBirth']) ? date('Y-m-d', strtotime($row['DateOfBirth'])) : null;
                                  $hireDate = false !== strtotime($row['HireDate']) ? date('Y-m-d', strtotime($row['HireDate'])) : null;
                                  $terminationDate = false !== strtotime($row['TerminationDate']) ? date('Y-m-d', strtotime($row['TerminationDate'])) : null;


                                  // Prepare data for DB insertion
                                  $employee_data = array(
                                      'employee_code' => $this->employee_model->get_lastest_sequence_number(),
                                      'first_name' => $row['FirstName'],
                                      'last_name' => $row['LastName'],
                                      'email' => $row['Email'],
                                      'date_of_birth' => $dateOfBirth,
                                      'department_id' => $matching_department->department_id,
                                      'position_id' => $matching_position->position_id,
                                      'hire_date' => $hireDate,
                                      'termination_date' => $terminationDate,
                                      'salary' => $row['Salary'],
                                      'bank_name' => $row['BankName'],
                                      'account_number' => $row['AccountNumber'],
                                      'branch' => $row['Branch'],
                                      'ifsc_code' => $row['IFSC']
                                  );

                                  // Check whether employee already exists in the database
                                  $exist_employees = $this->utility_model->get_records_by_field('hr_employees', 'email', $row['Email'], false, true);

                                  if($exist_employees == null || $isUpdateCreateNewIfExistChecked)
                                  {   
                                      $this->db->trans_begin();
                                      $employee_id = $this->employee_model->add_record($employee_data);

                                      $this->db->trans_commit();
                                      $total_employee_imported++;
                                  } 
                                  else
                                  {
                                      if($isUpdateEmployeeChecked)
                                      {
                                          foreach ($exist_employees as $value) {
                                              $this->employee_model->edit_record($employee_data, $value->employee_id);    
                                          }
                                      }
                                  }
                              } 
                              else
                              {
                                  $employees_with_unknown_code[] = $row['FirstName'];
                              }
                          }

                          $success_message = 'Employees imported successfully.';

                          if(sizeof($employees_with_unknown_code) > 0)
                              $success_message .= ' And '.implode(",", $employees_with_unknown_code).' failed to update or add because of unknown DepartmentCode or PositionCode value';

                          $this->session->set_flashdata('success', $success_message);
                          redirect('employee','refresh');
                      }
                  }
                  else
                  {
                      $this->session->set_flashdata('failure', 'Failed to import employees.');
                      redirect('employee','refresh');
                  }
              }
          }
          else
          {
              if($this->input->is_ajax_request()) 
              {
                  $data['departments'] = $this->department_model->get_records();
                  $data['positions'] = $this->position_model->get_records();

                  $response = array();
                  $response['code'] = 1;
                  $response['import_employee_modal_body'] = $this->load->view('employee/ajax/import_employee_modal_body', $data, TRUE);

                  echo json_encode($response);
              }
              else
              {
                  $this->load->view('errors/html/error_restricted'); 
              }
          }
      }
  }



  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->employee_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //view Button
      if($this->permission_model->has_permission('view_employee'))
      {	
        $table_body .= '
                          <a href="'.base_url('employee/view/'.base64_encode($item->employee_id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="'.$this->lang->line('employee_view').'">
                            <i class="fas fa-eye"></i> View
                          </a>
                        ';
      }

      //Edit Button
      if($this->permission_model->has_permission('edit_employee'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('employee_edit').'" class="btn btn-info btn-xs add_employee_modal" data-employee_id="'.$item->employee_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_employee'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_employee" data-tt="tooltip" title="'.$this->lang->line('employee_delete').'" class="btn btn-danger btn-xs delete_employee" data-employee_id="'.$item->employee_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }

          /* End Action column buttons*/

      $select_html = '<input type="checkbox" class="single_employee" value="'.$item->employee_id.'" data-employee_id="'.$item->employee_id.'"><input type="hidden" name="employee_id" id="employee_id" data-employee_id="'.$item->employee_id.'" value="'.$item->employee_id.'">';

          

      $row = array();
   
      $row[] = $select_html;
      $row[] = $item->first_name;
      $row[] = $item->last_name;
      $row[] = $item->employee_code;
      $row[] = $item->email;
      $row[] = $item->department_name;
      $row[] = $item->position_title;
      $row[] = ($item->hire_date != '' && $item->hire_date != '0000-00-00') ? date('d-m-Y',strtotime($item->hire_date)) : '';
      $row[] = $item->salary;
     
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->employee_model->count_all(),
                    "recordsFiltered"   => $this->employee_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function employee_delete_confirmation()
  {
  	$employee_id 					= $this->input->post('employee_id');
  	$data['employee'] 		= $this->employee_model->get_single_record($employee_id);
    $data['employee_entries_exist'] = $this->employee_model->check_employee_has_entries($employee_id);


  	$response 					= array();
  	$response['employee_delete_modal_body'] 	= $this->load->view('employee/ajax/employee_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export()
  {
      // Get parameters from query string
      $employee_ids = explode(',', $this->input->get('data'));
     
      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'a.delete_status = 0';

      // Add conditions based on parameters
      if (!empty($employee_ids)) {
          $whereConditions[] = 'a.employee_id IN (' . implode(',', $employee_ids) . ')';
      }

     
      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }

      // Query to fetch attendance data
      $query = $this->db->query('SELECT 
                                      a.employee_code as "Employee Code",
                                      a.first_name as "First Name",
                                      a.last_name as "Last Name",
                                      a.email as "Email",
                                       DATE_FORMAT(a.date_of_birth, "%d-%m-%Y") as "Date of Birth",
                                      a.department_name as "Department Name",
                                      a.position_title as "Position Title",
                                      DATE_FORMAT(a.hire_date, "%d-%m-%Y") as "Hire Date",
                                      DATE_FORMAT(a.termination_date, "%d-%m-%Y") as "Termination Date",
                                      a.salary as "Salary",
                                      a.bank_name as "Bank Name",
                                      a.account_number as "Account Number",
                                      a.branch as "Branch",
                                      a.ifsc_code as "IFSC"
                                     
                                  FROM 
                                      employee_view a
                                  ' . $whereClause . '
                                  ORDER BY a.first_name ASC');

      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');

      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);

      // Set filename for download
      $filename = 'EMPLOYEE.csv';

      // Download the CSV file
      force_download($filename, $data);
  }

 
}
