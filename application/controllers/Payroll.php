<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Payroll extends MY_Controller
{
  private $pdf;

  public function __construct()
  {
    parent::__construct();

    $this->pdf = new Dompdf();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
    
  }

  public function index()
  {
    if(!$this->permission_model->has_permission('list_payroll'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['payrolls']   = $this->payroll_model->get_records();
      $data['employees']  = $this->employee_model->get_records();
      $this->load->view('payroll/index',$data);
    }
  }

  // public function add($payroll_id = null)
  // {
  //   if($this->input->server('REQUEST_METHOD') === 'POST')
  //   {
  //     $this->form_validation->set_rules('employee_id','Employee Name','required');
  //     $this->form_validation->set_rules('payroll_date','Payroll Date','required');        
     
  //     if($this->form_validation->run()==FALSE)
  //     {   
  //       if($this->input->is_ajax_request())
  //       {
  //         $data['code'] = 2;
  //         $data['errors'] = $this->form_validation->error_array();
  //         echo json_encode($data);
  //       }
  //       else
  //       { 
  //         $data['payroll'] = null;
  //         if($payroll_id != null)
  //           $data['payroll'] = $this->payroll_model->get_single_record($payroll_id);

  //         $this->load->view('payroll/add',$data);
  //       }
  //     }
  //     else
  //     {
  //       $employee_id        = $this->input->post('employee_id');
  //       $gross_amount       = $this->input->post('gross_amount');
  //       $net_amount         = $this->input->post('net_amount');
        

  //       $this->db->trans_begin();

  //       $data       =   array(
  //                             "employee_id"    =>  $employee_id,
  //                             "gross_amount"   =>  $gross_amount,
  //                             "net_amount"     =>  $net_amount
                             
  //                           );

  //       $payroll_date        = $this->input->post('payroll_date');
       
  //       if($payroll_date != '')
  //         $data['payroll_date'] = date('Y-m-d',strtotime($payroll_date));

       

  //       if($payroll_id == NULL)
  //       {
  //         if($id = $this->payroll_model->add_record($data))
  //         {
            
  //           // commit transaction
  //           $this->db->trans_commit();

  //           if($this->input->is_ajax_request())
  //           {
  //             $response = array();
  //             $response['code'] = RESPONSE_SUCCESS;
  //             $response['message'] = 'Payroll is added successfully';

  //             echo json_encode($response);  
  //           }
  //           else
  //           {
  //             $this->session->set_flashdata('success', 'Payroll is added successfully');
  //             redirect('payroll','refresh');  
  //           }
  //         }
  //         else
  //         {
  //           // rollback transaction
  //           $this->db->trans_rollback();

  //           if($this->input->is_ajax_request())
  //           {
  //             $response = array();
  //             $response['code'] = RESPONSE_FAILURE;
  //             $response['message'] = 'Payroll is failed to add.';

  //             echo json_encode($response);  
  //           }
  //           else
  //           {
  //             $this->session->set_flashdata('failure', 'Payroll is failed to add.');
  //             redirect('payroll','refresh');
  //           }
  //         }   
  //       }
  //       else
  //       {
  //         if($this->payroll_model->edit_record($data,$payroll_id))
  //         {
  //           // commit transaction
  //           $this->db->trans_commit();

  //           if($this->input->is_ajax_request())
  //           {
  //             $response = array();
  //             $response['code'] = RESPONSE_SUCCESS;
  //             $response['message'] = 'Payroll is updated successfully';

  //             echo json_encode($response);  
  //           }
  //           else
  //           { 
  //             $this->session->set_flashdata('success', 'Payroll is updated successfully');
  //             redirect('payroll','refresh');
  //           }
  //         }
  //         else
  //         {
  //           // rollback transaction
  //           $this->db->trans_rollback();

  //           if($this->input->is_ajax_request())
  //           {
  //             $response = array();
  //             $response['code'] = RESPONSE_FAILURE;
  //             $response['message'] = 'Payroll is failed to update.';

  //             echo json_encode($response);  
  //           }
  //           else
  //           { 
  //             $this->session->set_flashdata('failure', 'Payroll is failed to update.');
  //             redirect('payroll','refresh');
  //           }
  //         }   
  //       }
  //     }
  //   }
  //   else
  //   {
  //     $data['payroll'] = null;
  //     $data['employees']  = $this->employee_model->get_records();
      
  //     if($payroll_id != null)
  //     {
  //       $data['payroll']       = $this->payroll_model->get_single_record($payroll_id);
        
  //       $response                        = array();
  //       $response['code']                = RESPONSE_SUCCESS;             
  //       $response['add_payroll_modal_body'] = $this->load->view('payroll/ajax/add_payroll_modal_body',$data,TRUE);
  //       echo json_encode($response);
  //     }
  //     else
  //     {
  //       $response                        = array();
  //       $response['code']                = RESPONSE_SUCCESS;             
  //       $response['add_payroll_modal_body'] = $this->load->view('payroll/ajax/add_payroll_modal_body',$data,TRUE);
  //       echo json_encode($response);
  //     }
  //   }
  // }

  // function delete()
	// {
	// 	if(!$this->permission_model->has_permission('delete_payroll'))
	// 	{
	// 		$this->load->view('errors/html/error_restricted'); 
	// 	}
	// 	else
	// 	{
	// 		$payroll_id 							= $this->input->post('payroll_id');

	// 		$data = array('delete_status' => 1);

	// 		$payroll =  $this->payroll_model->get_single_record($payroll_id);

	// 		if($this->payroll_model->edit_record($data,$payroll_id))
	// 		{
				
	// 			if($this->input->is_ajax_request())
	// 			{	
	// 				$response 								= array();
	// 				$response['code'] 				= 1;
	// 				$response['id'] 					= $payroll_id;
	// 				$response['message']			= 'Payroll is deleted successfully.';

	// 				echo json_encode($response);
	// 			}
	// 			else
	// 			{
	// 				$this->session->set_flashdata('success', 'Payroll is deleted successfully');
	// 				redirect('payroll','refresh');
	// 			}
	// 		}
	// 		else
	// 		{
	// 			if($this->input->is_ajax_request())
	// 			{	
	// 				$response 						= array();
	// 				$response['code'] 		= 0;
	// 				$response['message']	= 'Payroll is failed to delete.';
				
	// 				echo json_encode($response);
	// 			}
	// 			else
	// 			{
	// 				$this->session->set_flashdata('failure', 'Payroll is failed to delete');
	// 				redirect('payroll');
	// 			}
	// 		}
	// 	}
	// }

  public function view($employee_id = null)
  {
    if(!$this->permission_model->has_permission('view_payroll'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{  
      $data['payroll']         = $this->payroll_model->get_single_record($employee_id);
      $data['company_setting']	= $this->company_settings_model->get_company_records();

      $response 					                              = array();
      $response['code'] 	                              = 1;
      $response['view_payroll_modal_body'] 	= $this->load->view('payroll/ajax/view_payroll_modal_view',$data,TRUE);

      echo json_encode($response);
      
		}
  }

  public function pdf($employee_id = null)
  {
      // Get payroll and company setting records
      $data['payroll'] = $this->payroll_model->get_single_record($employee_id);
      $data['company_setting'] = $this->company_settings_model->get_company_records();
  
      // Load the view and generate the HTML content
      $html = $this->load->view('payroll/pdf', $data, true);
  
      // Extract employee name and payroll month for the file name
      $employee_name = str_replace(' ', '_', strtoupper($data['payroll']->employee_name)); // Convert spaces to underscores and make uppercase
      $payroll_month = strtoupper(date('F_Y', strtotime($data['payroll']->payroll_month))); // Format the payroll month and make uppercase
  
      // Create the file name
      $file_name = "{$employee_name}_{$payroll_month}.pdf";
  
      // Generate and stream the PDF
      $this->pdf->loadHtml($html);
      $this->pdf->render();
      $this->pdf->stream($file_name, array("Attachment" => 1));
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_payroll'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$payroll_history_id 							= $this->input->post('payroll_history_id');

			$data = array('delete_status' => 1);

			$payroll =  $this->payroll_history_model->get_single_record($payroll_history_id);
    

			if($this->payroll_history_model->edit_record($data,$payroll_history_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $payroll_history_id;
					$response['message']			= 'Payroll is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Payroll is deleted successfully');
					redirect('payroll','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Payroll is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Payroll is failed to delete');
					redirect('payroll');
				}
			}
		}
	}

  public function payroll_delete_confirmation()
  {
  	$payroll_history_id 					  = $this->input->post('payroll_history_id');
  	$data['payroll'] 		    = $this->payroll_history_model->get_single_record($payroll_history_id);
   
  	$response 					    = array();
  	$response['payroll_delete_modal_body'] 	= $this->load->view('payroll/ajax/payroll_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->payroll_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';


      $payroll_history = $this->payroll_history_model->get_single_record_by_employee_id($item->employee_id);

      //View Button
      // if($this->permission_model->has_permission('edit_payroll'))
      // {	
      //   $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('payroll_edit').'" class="btn btn-info btn-xs add_payroll_modal" data-payroll_id="'.$item->payroll_id.'">
      //                     <i class="fas fa-trash"></i> Edit
      //                   </a>';
      // }

      // // Delete Button
      // if($this->permission_model->has_permission('delete_payroll'))
      // {	
      //   $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_payroll" data-tt="tooltip" title="'.$this->lang->line('payroll_delete').'" class="btn btn-danger btn-xs delete_payroll" data-payroll_id="'.$item->payroll_id.'">
      //                     <i class="fas fa-trash"></i> Delete
      //                   </a>';
      // }

      // Delete Button
     


      $employee_id = $item->employee_id;
      $payroll_month = $item->payroll_month;

      if (!$this->payroll_history_model->get_employee_payroll_status($employee_id, $payroll_month)) 
      {
          $table_body .= '
              <a href="#" data-toggle="modal" data-target="#create_payroll" data-employee_id="'.$employee_id.'" class="btn btn-info btn-xs create_payroll" data-tt="tooltip" title="Create Payroll">
                  Create Payroll
              </a>    
          ';
      }
      else
      {
        if($this->permission_model->has_permission('delete_payroll') && $payroll_history->delete_status == 0)
        {	
          $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_payroll" data-tt="tooltip" title="'.$this->lang->line('payroll_delete').'" class="btn btn-danger btn-xs delete_payroll" data-payroll_history_id="'.$payroll_history->payroll_history_id.'">
                            <i class="fas fa-trash"></i> Delete
                          </a>';
        }
        // elseif($payroll_history->delete_status)
        // {

        // }
      }


       //view Button
       if($this->permission_model->has_permission('view_payroll'))
       {	
         $table_body .=	'
                           <a href="#" data-toggle="modal" data-target="#view_payroll_modal" data-employee_id="'.$item->employee_id.'" class="btn btn-default btn-xs" data-tt="tooltip" title="View Payroll">
                              <i class="fas fa-eye"></i> View
                            </a>    
                         ';
       }

          /* End Action column buttons*/

      $select_html = '<input type="checkbox" class="single_payroll" value="'.$item->employee_id.'" data-employee_id="'.$item->employee_id.'"><input type="hidden" name="employee_id" id="employee_id" data-employee_id="'.$item->employee_id.'" value="'.$item->employee_id.'">';    

      $employee_name_html = '<a href="'.base_url('employee/view/'.base64_encode($item->employee_id)).'" data-tt="tooltip" title="'.$this->lang->line('employee_view').'">'.$item->employee_name.'</a>';

      $row = array();
   
      $row[] = $select_html;
      $row[] = $employee_name_html;
      $row[] = $item->base_salary;
      $row[] = $item->total_bonuses;
      $row[] = $item->total_deductions;
      $row[] = $item->total_tax;
      $row[] = $item->total_advance;
      // $row[] = $item->total_leaves;
      $row[] = $item->leave_deduction_amount;
      $row[] = $item->total_leaves;
      $row[] = $item->net_salary;
      
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->payroll_model->count_all(),
                    "recordsFiltered"   => $this->payroll_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  // public function payroll_delete_confirmation()
  // {
  // 	$payroll_id 					= $this->input->post('payroll_id');
  // 	$data['payroll'] 		= $this->payroll_model->get_single_record($payroll_id);

  // 	$response 					= array();
  // 	$response['payroll_delete_modal_body'] 	= $this->load->view('payroll/ajax/payroll_delete_modal_view',$data,TRUE);

  // 	echo json_encode($response);
	// }

  public function export()
  {
      // Get parameters from query string
      $employee_ids = explode(',', $this->input->get('data'));
      $employee_id = $this->input->get('employee_id');
      $month = $this->input->get('month');
      $year = $this->input->get('year');
  
      // Initialize where conditions array
      $whereConditions = [];
      //$whereConditions[] = 'a.delete_status = 0'; // Ensure to filter out deleted records
  
      // Add conditions based on parameters
      if (!empty($employee_ids)) {
          $whereConditions[] = 'a.employee_id IN (' . implode(',', array_map('intval', $employee_ids)) . ')';
      }
  
      if (!empty($employee_id)) {
          $whereConditions[] = 'a.employee_id = ' . intval($employee_id);
      }
  
      if (!empty($month)) {
        $whereConditions[] = "SUBSTRING(a.payroll_month, 6, 2) = '" . str_pad($month, 2, '0', STR_PAD_LEFT) . "'";
    }

    if (!empty($year)) {
        $whereConditions[] = "SUBSTRING(a.payroll_month, 1, 4) = '" . $year . "'";
    }

  
      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }
  
      // Output for debugging
      echo "Generated SQL query: SELECT ... " . $whereClause . "<br>";
  
      // Query to fetch payroll data
      $query = $this->db->query('SELECT 
                                      a.employee_name as "Employee Name",
                                      a.base_salary as "Base Salary",
                                      a.total_bonuses as "Total Bonus",
                                      a.total_deductions as "Total Deduction",
                                      a.total_tax as "Total Tax",
                                      a.total_advance as "Total Advance",
                                      a.leave_deduction_amount as "Leave Deduction Amount",
                                      a.total_leaves as "Total Leaves",
                                      a.net_salary as "Net Salary"
                                  FROM 
                                      payroll_view a
                                  ' . $whereClause . '
                                  ORDER BY a.employee_name ASC');
  
      // Check if query execution was successful
      if ($query) {
          // Load necessary helpers
          $this->load->dbutil();
          $this->load->helper('download');
  
          // Generate CSV from query result
          $data = $this->dbutil->csv_from_result($query);
  
          // Set filename for download
          $filename = 'PAYROLL.csv';
  
          // Download the CSV file
          force_download($filename, $data);
      } else {
          // Handle query execution failure
          echo "Query execution failed or no records found.";
      }
  }

  public function create_payroll_confirmation($employee_id = null)
  {
    //$employee_id 					= $this->input->post('employee_id');
  	$data['payroll'] 		  = $this->payroll_model->get_single_record($employee_id);

  	$response 					= array();
  	$response['create_payroll_modal_body'] 	= $this->load->view('payroll/ajax/create_payroll_modal_view',$data,TRUE);

  	echo json_encode($response);
  }

  public function bulk_payroll_form()
  {
      $employee_ids = $this->input->get('employee_ids');
      $data['employees'] = $this->employee_model->get_employees_by_ids(explode('-', $employee_ids));

      $response 					= array();
      $response['bulk_payroll_modal_body'] 	= $this->load->view('payroll/ajax/bulk_payroll_modal_view',$data,TRUE);

      echo json_encode($response);
  }

  public function create_payroll($employee_id = null)
  {
      if ($this->input->server('REQUEST_METHOD') === 'POST') 
      {
          $employee_ids = $this->input->post('employee_ids') ?? $employee_id;
  
          if (!is_array($employee_ids)) {
              $employee_ids = explode('-', $employee_ids);
          }
  
          $this->db->trans_begin();
          $current_month = date('Y-m'); 
  
          foreach ($employee_ids as $id) {
              if ($this->payroll_model->payroll_exists($id, $current_month)) {
                  continue; // Skip this employee
              }
  
              $employee = $this->payroll_model->get_single_record($id);
  
              $data = array(
                  "employee_id" => $employee->employee_id,
                  "employee_name" => $employee->employee_name,
                  "base_salary" => $employee->base_salary,
                  "total_tax" => $employee->total_tax,
                  "total_bonuses" => $employee->total_bonuses,
                  "total_deductions" => $employee->total_deductions,
                  "total_advance" => $employee->total_advance,
                  "total_leaves" => $employee->total_leaves,
                  "leave_deduction_amount" => $employee->leave_deduction_amount,
                  "net_salary" => $employee->net_salary,
                  "bank_name" => $employee->bank_name,
                  "account_number" => $employee->account_number,
                  "branch" => $employee->branch,
                  "ifsc_code" => $employee->ifsc_code,
                  "employee_created_by" => $employee->employee_created_by,
                  "employee_updated_by" => $employee->employee_updated_by,
                  "tax_created_by" => $employee->tax_created_by,
                  "tax_updated_by" => $employee->tax_updated_by,
                  "bonus_created_by" => $employee->bonus_created_by,
                  "bonus_updated_by" => $employee->bonus_updated_by,
                  "deduction_created_by" => $employee->deduction_created_by,
                  "deduction_updated_by" => $employee->deduction_updated_by,
                  "advance_created_by" => $employee->advance_created_by,
                  "advance_updated_by" => $employee->advance_updated_by,
                  "leave_created_by" => $employee->leave_created_by,
                  "leave_updated_by" => $employee->leave_updated_by,
                  "payroll_month" => $current_month
              );
  
              if (!$this->payroll_history_model->add_record($data)) {
                  $this->db->trans_rollback();
  
                  $response = array();
                  $response['code'] = RESPONSE_FAILURE;
                  $response['message'] = 'Payroll history failed to add.';
  
                  echo json_encode($response);
                  return;
              }
          }
  
          $this->db->trans_commit();
  
          $response = array();
          $response['code'] = RESPONSE_SUCCESS;
          $response['message'] = 'Payroll history added successfully';
  
          echo json_encode($response);
      }
  }
  




  // public function create_payroll($employee_id = null)
  // {
  //   if($this->input->server('REQUEST_METHOD') === 'POST')
  //   {
     
  //     $employee = $this->payroll_model->get_single_record($employee_id);

  //     $employee_id        = $employee->employee_id;
  //     $employee_name        = $employee->employee_name;
  //     $base_salary          = $employee->base_salary;
  //     $total_tax            = $employee->total_tax;
  //     $total_bonuses        = $employee->total_bonuses;
  //     $total_deductions     = $employee->total_deductions;
  //     $total_advance        = $employee->total_advance;
  //     $total_leaves         = $employee->total_leaves;
  //     $leave_deduction_amount = $employee->leave_deduction_amount;
  //     $net_salary           = $employee->net_salary;
  //     $bank_name            = $employee->bank_name;
  //     $account_number       = $employee->account_number;
  //     $branch               = $employee->branch;
  //     $ifsc_code            = $employee->ifsc_code;
  //     $employee_created_by  = $employee->employee_created_by;
  //     $employee_updated_by  = $employee->employee_updated_by;
  //     $tax_created_by       = $employee->tax_created_by;
  //     $tax_updated_by       = $employee->tax_updated_by;
  //     $bonus_created_by     = $employee->bonus_created_by;
  //     $bonus_updated_by     = $employee->bonus_updated_by;
  //     $deduction_created_by = $employee->deduction_created_by;
  //     $deduction_updated_by = $employee->deduction_updated_by;
  //     $advance_created_by   = $employee->advance_created_by;
  //     $advance_updated_by   = $employee->advance_updated_by;
  //     $leave_created_by     = $employee->leave_created_by;
  //     $leave_updated_by     = $employee->leave_updated_by;
  //     $payroll_month        = $employee->payroll_month;
      
      
  //     $data     =   array(
  //                           "employee_id"               =>  $employee_id,
  //                           "employee_name"             =>  $employee_name,
  //                           "base_salary"               =>  $base_salary,
  //                           "total_tax"                 =>  $total_tax,
  //                           "total_bonuses"             =>  $total_bonuses,
  //                           "total_deductions"          =>  $total_deductions,
  //                           "total_advance"             =>  $total_advance,
  //                           "total_leaves"              =>  $total_leaves,
  //                           "leave_deduction_amount"    =>  $leave_deduction_amount,
  //                           "net_salary"                =>  $net_salary,
  //                           "bank_name"                 =>  $bank_name,
  //                           "account_number"            =>  $account_number,
  //                           "branch"                    =>  $branch,
  //                           "ifsc_code"                 =>  $ifsc_code,
  //                           "employee_created_by"       =>  $employee_created_by,
  //                           "employee_updated_by"       =>  $employee_updated_by,
  //                           "tax_created_by"            =>  $tax_created_by,
  //                           "tax_updated_by"            =>  $tax_updated_by,
  //                           "bonus_created_by"          =>  $bonus_created_by,
  //                           "bonus_updated_by"          =>  $bonus_updated_by,
  //                           "deduction_created_by"      =>  $deduction_created_by,
  //                           "deduction_updated_by"      =>  $deduction_updated_by,
  //                           "advance_created_by"        =>  $advance_created_by,
  //                           "advance_updated_by"        =>  $advance_updated_by,
  //                           "leave_created_by"          =>  $leave_created_by,
  //                           "leave_updated_by"          =>  $leave_updated_by,
  //                           "payroll_month"             =>  $payroll_month
                            
  //                         );

     
       
  //         if($id = $this->payroll_history_model->add_record($data))
  //         {
            
  //           // commit transaction
  //           $this->db->trans_commit();

  //           if($this->input->is_ajax_request())
  //           {
  //             $response = array();
  //             $response['code'] = RESPONSE_SUCCESS;
  //             $response['message'] = 'Payroll history is added successfully';

  //             echo json_encode($response);  
  //           }
  //           else
  //           {
  //             $this->session->set_flashdata('success', 'Payroll history is added successfully');
  //             redirect('payroll','refresh');  
  //           }
  //         }
  //         else
  //         {
  //           // rollback transaction
  //           $this->db->trans_rollback();

  //           if($this->input->is_ajax_request())
  //           {
  //             $response = array();
  //             $response['code'] = RESPONSE_FAILURE;
  //             $response['message'] = 'Payroll history is failed to add.';

  //             echo json_encode($response);  
  //           }
  //           else
  //           {
  //             $this->session->set_flashdata('failure', 'Payroll history is failed to add.');
  //             redirect('payroll','refresh');
  //           }
  //         }   
        
  //   }
   
  // }
  

 
}
