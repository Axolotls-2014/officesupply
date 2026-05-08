<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tax_deduction extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_tax_deduction'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['tax_deductions'] = $this->tax_deduction_model->get_records();
      $data['employees']  = $this->employee_model->get_records();
      $this->load->view('tax_deduction/index',$data);
    }
  }

  public function add($tax_deduction_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      
      $this->form_validation->set_rules('employee_id','Employee Name','required');
      $this->form_validation->set_rules('tax_deduction_date','Tax Deduction date','required');
      $this->form_validation->set_rules('tax_type','Tax Type','required');
      $this->form_validation->set_rules('amount','Tax Amount','required');
     
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
          $data['tax_deduction'] = null;
          if($tax_deduction_id != null)
            $data['tax_deduction'] = $this->tax_deduction_model->get_single_record($tax_deduction_id);

          $this->load->view('tax_deduction/add',$data);
        }
      }
      else
      {
       
        $employee_id      = $this->input->post('employee_id');
        $tax_type         = $this->input->post('tax_type');
        $amount           = $this->input->post('amount');
       

        $this->db->trans_begin();

        $data       =   array(
                              
                              "employee_id"     =>  $employee_id,
                              "tax_type"        =>  $tax_type,
                              "amount"          =>  $amount
                              
                            );

        $tax_deduction_date     = $this->input->post('tax_deduction_date');

        if($tax_deduction_date != '')
          $data['tax_deduction_date'] = date('Y-m-d',strtotime($tax_deduction_date));

        if($tax_deduction_id == NULL)
        {
          if($id = $this->tax_deduction_model->add_record($data))
          {

            // $this->tax_deduction_model->update_payrolls_with_tax_deductions();
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Tax deduction is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Tax deduction is added successfully');
              redirect('tax_deduction','refresh');  
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
              $response['message'] = 'Tax deduction is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Tax deduction is failed to add.');
              redirect('tax_deduction','refresh');
            }
          }   
        }
        else
        {

          $old_tax_deduction  = $this->tax_deduction_model->get_single_record($tax_deduction_id);
          // $old_payroll        = $this->payroll_model->get_single_record($payroll_id);

          
          // $updated_payroll = array('net_amount' => ($old_payroll->net_amount + $old_tax_deduction->amount));
          // $this->payroll_model->edit_record($updated_payroll, $old_payroll->payroll_id);



          if($this->tax_deduction_model->edit_record($data,$tax_deduction_id))
          {
            // $this->tax_deduction_model->update_payrolls_with_tax_deductions();
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Tax deduction is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Tax deduction is updated successfully');
              redirect('tax_deduction','refresh');
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
              $response['message'] = 'Tax deduction is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Tax deduction is failed to update.');
              redirect('tax_deduction','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['tax_deduction'] = null;

      $data['employees']    = $this->employee_model->get_records();

      if($tax_deduction_id != null)
      {
        $data['tax_deduction']       = $this->tax_deduction_model->get_single_record($tax_deduction_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_tax_deduction_modal_body'] = $this->load->view('tax_deduction/ajax/add_tax_deduction_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_tax_deduction_modal_body'] = $this->load->view('tax_deduction/ajax/add_tax_deduction_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  
  function delete()
	{
		if(!$this->permission_model->has_permission('delete_tax_deduction'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$tax_deduction_id 							= $this->input->post('tax_deduction_id');

			$data = array('delete_status' => 1);

			$tax_deduction =  $this->tax_deduction_model->get_single_record($tax_deduction_id);

			if($this->tax_deduction_model->edit_record($data,$tax_deduction_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $tax_deduction_id;
					$response['message']			= 'Tax deduction is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Tax deduction is deleted successfully');
					redirect('tax_deduction','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Tax deduction is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Tax deduction is failed to delete');
					redirect('tax_deduction');
				}
			}
		}
	}


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->tax_deduction_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //View Button
      if($this->permission_model->has_permission('edit_tax_deduction'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('tax_deduction_edit').'" class="btn btn-info btn-xs add_tax_deduction_modal" data-tax_deduction_id="'.$item->tax_deduction_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_tax_deduction'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_tax_deduction" data-tt="tooltip" title="'.$this->lang->line('tax_deduction_delete').'" class="btn btn-danger btn-xs delete_tax_deduction" data-tax_deduction_id="'.$item->tax_deduction_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }


          /* End Action column buttons*/

        $select_html = '<input type="checkbox" class="single_tax_deduction" value="'.$item->tax_deduction_id.'" data-tax_deduction_id="'.$item->tax_deduction_id.'"><input type="hidden" name="tax_deduction_id" id="tax_deduction_id" data-tax_deduction_id="'.$item->tax_deduction_id.'" value="'.$item->tax_deduction_id.'">';

        $employee_name_html = '<a href="'.base_url('employee/view/'.base64_encode($item->employee_id)).'" data-tt="tooltip" title="'.$this->lang->line('employee_view').'">'.$item->employee_name.'</a>';

          

      $row = array();
   
      $row[] = $select_html;
      $row[] = $employee_name_html;
      $row[] = ($item->tax_deduction_date != '' && $item->tax_deduction_date != '0000-00-00') ? date('d-m-Y',strtotime($item->tax_deduction_date)) : '';
      $row[] = $item->tax_type;
      $row[] = $item->amount;
     
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->tax_deduction_model->count_all(),
                    "recordsFiltered"   => $this->tax_deduction_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function tax_deduction_delete_confirmation()
  {
  	$tax_deduction_id 					= $this->input->post('tax_deduction_id');
  	$data['tax_deduction'] 		= $this->tax_deduction_model->get_single_record($tax_deduction_id);

    // Get employee_id and leave_date
    $employee_id = $data['tax_deduction']->employee_id;
    $tax_deduction_date = $data['tax_deduction']->tax_deduction_date;
    $payroll_month = date('Y-m', strtotime($tax_deduction_date));

    // Check if payroll exists for that month-year
    $data['payroll_exists'] = $this->payroll_history_model->get_employee_payroll_status($employee_id, $payroll_month);

  	$response 					= array();
  	$response['tax_deduction_delete_modal_body'] 	= $this->load->view('tax_deduction/ajax/tax_deduction_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export()
  {
      // Get parameters from query string
      $tax_deduction_ids = explode(',', $this->input->get('data'));
      $employee_id    = $this->input->get('employee_id');
      $from_date      = $this->input->get('from_date');
      $to_date        = $this->input->get('to_date');
     
  
      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'l.delete_status = 0'; 
  
      // Add conditions based on parameters
      if (!empty($tax_deduction_ids)) {
          $whereConditions[] = 'l.tax_deduction_id IN (' . implode(',', array_map('intval', $tax_deduction_ids)) . ')';
      }
  
      if (!empty($employee_id)) {
          $whereConditions[] = 'l.employee_id = ' . intval($employee_id);
      }
  
      if (!empty($from_date) && !empty($to_date)) {
          // Ensure dates are in the correct format for SQL query
          $from_date = date('Y-m-d', strtotime($from_date));
          $to_date = date('Y-m-d', strtotime($to_date));
          $whereConditions[] = 'l.tax_deduction_date BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '"';
      }
  
   
  
  
      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }
  
      // Query to fetch advance salary data
      $query = $this->db->query('SELECT 
                                      l.employee_name as "Employee Name",
                                      DATE_FORMAT(l.tax_deduction_date, "%d-%m-%Y") as "Tax Deduction Date",
                                      l.amount as "Amount",
                                      l.tax_type as "Tax Type"
                                  FROM 
                                      tax_deduction_view l
                                  ' . $whereClause . '
                                  ORDER BY l.employee_name ASC');
  
      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');
  
      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);
  
      // Set filename for download
      $filename = 'TAX DEDUCTION.csv';
  
      // Download the CSV file
      force_download($filename, $data);
  }


 
}
