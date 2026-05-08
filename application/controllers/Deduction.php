<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Deduction extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_deduction'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['deductions'] = $this->deduction_model->get_records();
      $data['employees']  = $this->employee_model->get_records();
      $this->load->view('deduction/index',$data);
    }
  }

  public function add($deduction_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {

      $this->form_validation->set_rules('employee_id','Employee Name','required');
      $this->form_validation->set_rules('deduction_type','Deduction Type','required');
      $this->form_validation->set_rules('deduction_date','Deduction Date','required');
      $this->form_validation->set_rules('amount','Deduction Amount','required');
     
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
          $data['deduction'] = null;
          if($deduction_id != null)
            $data['deduction'] = $this->deduction_model->get_single_record($deduction_id);

          $this->load->view('deduction/add',$data);
        }
      }
      else
      {
        $employee_id      = $this->input->post('employee_id');
        $deduction_type   = $this->input->post('deduction_type');
        $amount           = $this->input->post('amount');
       

        $this->db->trans_begin();

        $data       =   array(
                              "employee_id"     =>  $employee_id,
                              "deduction_type"  =>  $deduction_type,
                              "amount"          =>  $amount
                            );

        $deduction_date     = $this->input->post('deduction_date');

        if($deduction_date != '')
          $data['deduction_date'] = date('Y-m-d',strtotime($deduction_date));

        if($deduction_id == NULL)
        {
          if($id = $this->deduction_model->add_record($data))
          {
            // $this->payroll_model->update_net_amount();
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Deduction is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Deduction is added successfully');
              redirect('deduction','refresh');  
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
              $response['message'] = 'Deduction is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Deduction is failed to add.');
              redirect('deduction','refresh');
            }
          }   
        }
        else
        {

          $old_deduction  = $this->deduction_model->get_single_record($deduction_id);

          // $old_payroll    = $this->payroll_model->get_single_record($payroll_id);
          // $old_amount     = $old_deduction->amount;

          // $updated_payroll 		= array('net_amount' => ($old_amount + $old_payroll->net_amount));

          // $this->payroll_model->edit_record($updated_payroll,$old_payroll->payroll_id);


          if($this->deduction_model->edit_record($data,$deduction_id))
          {
            // $this->payroll_model->update_net_amount();
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Deduction is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Deduction is updated successfully');
              redirect('deduction','refresh');
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
              $response['message'] = 'Deduction is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Deduction is failed to update.');
              redirect('deduction','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['deduction']  = null;
      $data['employees'] = $this->employee_model->get_records();
     

      if($deduction_id != null)
      {
        $data['deduction']       = $this->deduction_model->get_single_record($deduction_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_deduction_modal_body'] = $this->load->view('deduction/ajax/add_deduction_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_deduction_modal_body'] = $this->load->view('deduction/ajax/add_deduction_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_deduction'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$deduction_id 							= $this->input->post('deduction_id');

			$data = array('delete_status' => 1);

			$deduction =  $this->deduction_model->get_single_record($deduction_id);

			if($this->deduction_model->edit_record($data,$deduction_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $deduction_id;
					$response['message']			= 'Deduction is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Deduction is deleted successfully');
					redirect('deduction','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Deduction is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Deduction is failed to delete');
					redirect('deduction');
				}
			}
		}
	}


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->deduction_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //View Button
      if($this->permission_model->has_permission('edit_deduction'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('deduction_edit').'" class="btn btn-info btn-xs add_deduction_modal" data-deduction_id="'.$item->deduction_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_deduction'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_deduction" data-tt="tooltip" title="'.$this->lang->line('deduction_delete').'" class="btn btn-danger btn-xs delete_deduction" data-deduction_id="'.$item->deduction_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }

      /* End Action column buttons*/

      $select_html = '<input type="checkbox" class="single_deduction" value="'.$item->deduction_id.'" data-deduction_id="'.$item->deduction_id.'"><input type="hidden" name="deduction_id" id="deduction_id" data-deduction_id="'.$item->deduction_id.'" value="'.$item->deduction_id.'">';

      $employee_name_html = '<a href="'.base_url('employee/view/'.base64_encode($item->employee_id)).'" data-tt="tooltip" title="'.$this->lang->line('employee_view').'">'.$item->employee_name.'</a>';

          

      $row = array();
   
      $row[] = $select_html;
      $row[] = $employee_name_html;
      $row[] = ($item->deduction_date != '' && $item->deduction_date != '0000-00-00') ? date('d-m-Y',strtotime($item->deduction_date)) : '';
      $row[] = $item->deduction_type;
      $row[] = $item->amount;
     
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->deduction_model->count_all(),
                    "recordsFiltered"   => $this->deduction_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function deduction_delete_confirmation()
  {
  	$deduction_id 					= $this->input->post('deduction_id');
  	$data['deduction'] 		= $this->deduction_model->get_single_record($deduction_id);

    // Get employee_id and leave_date
    $employee_id = $data['deduction']->employee_id;
    $deduction_date = $data['deduction']->deduction_date;
    $payroll_month = date('Y-m', strtotime($deduction_date));

    // Check if payroll exists for that month-year
    $data['payroll_exists'] = $this->payroll_history_model->get_employee_payroll_status($employee_id, $payroll_month);

  	$response 					= array();
  	$response['deduction_delete_modal_body'] 	= $this->load->view('deduction/ajax/deduction_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export()
  {
      // Get parameters from query string
      $deduction_ids = explode(',', $this->input->get('data'));
      $employee_id    = $this->input->get('employee_id');
      $from_date      = $this->input->get('from_date');
      $to_date        = $this->input->get('to_date');
     
  
      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'l.delete_status = 0'; 
  
      // Add conditions based on parameters
      if (!empty($deduction_ids)) {
          $whereConditions[] = 'l.deduction_id IN (' . implode(',', array_map('intval', $deduction_ids)) . ')';
      }
  
      if (!empty($employee_id)) {
          $whereConditions[] = 'l.employee_id = ' . intval($employee_id);
      }
  
      if (!empty($from_date) && !empty($to_date)) {
          // Ensure dates are in the correct format for SQL query
          $from_date = date('Y-m-d', strtotime($from_date));
          $to_date = date('Y-m-d', strtotime($to_date));
          $whereConditions[] = 'l.deduction_date BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '"';
      }
  
   
  
  
      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }
  
      // Query to fetch advance salary data
      $query = $this->db->query('SELECT 
                                      l.employee_name as "Employee Name",
                                      DATE_FORMAT(l.deduction_date, "%d-%m-%Y") as "Deduction Date",
                                      l.amount as "Amount",
                                      l.deduction_type as "Deduction Type"
                                  FROM 
                                      deduction_view l
                                  ' . $whereClause . '
                                  ORDER BY l.employee_name ASC');
  
      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');
  
      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);
  
      // Set filename for download
      $filename = 'DEDUCTION.csv';
  
      // Download the CSV file
      force_download($filename, $data);
  }

}
