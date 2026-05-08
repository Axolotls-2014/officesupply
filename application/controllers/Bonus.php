<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_bonus'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['bonuses'] = $this->bonus_model->get_records();
      $data['employees']  = $this->employee_model->get_records();
      $this->load->view('bonus/index',$data);
    }
  }

  public function add($bonus_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('employee_id','Employee Name','required');
      $this->form_validation->set_rules('bonus_type','Bonus Type','required');
      $this->form_validation->set_rules('bonus_date','Bonus Date','required');
     
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
          $data['bonus'] = null;
          if($bonus_id != null)
            $data['bonus'] = $this->bonus_model->get_single_record($bonus_id);

          $this->load->view('bonus/add',$data);
        }
      }
      else
      {
        $employee_id   = $this->input->post('employee_id');
        $bonus_type   = $this->input->post('bonus_type');
        $amount       = $this->input->post('amount');
       

        $this->db->trans_begin();

        $data       =   array(
                              "employee_id"  =>  $employee_id,
                              "bonus_type"  =>  $bonus_type,
                              "amount"      =>  $amount
                              
                            );

        $bonus_date     = $this->input->post('bonus_date');

        if($bonus_date != '')
          $data['bonus_date'] = date('Y-m-d',strtotime($bonus_date));
                    

        if($bonus_id == NULL)
        {
          if($id = $this->bonus_model->add_record($data))
          {
            // $this->bonus_model->update_payrolls_with_bonuses();
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Bonus is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Bonus is added successfully');
              redirect('bonus','refresh');  
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
              $response['message'] = 'Bonus is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Bonus is failed to add.');
              redirect('bonus','refresh');
            }
          }   
        }
        else
        {
          $old_bonus  = $this->bonus_model->get_single_record($bonus_id);

          // $old_payroll    = $this->payroll_model->get_single_record($payroll_id);

          // $old_amount     = $old_bonus->amount;

          // $updated_payroll 		= array(
          //                             'net_amount'    => ($old_payroll->net_amount - $old_amount),
          //                             'gross_amount'  => ($old_payroll->gross_amount -  $old_amount)
          //                           );

          // $this->payroll_model->edit_record($updated_payroll,$old_payroll->payroll_id);

          if($this->bonus_model->edit_record($data,$bonus_id))
          {
            // $this->bonus_model->update_payrolls_with_bonuses();
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Bonus is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Bonus is updated successfully');
              redirect('bonus','refresh');
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
              $response['message'] = 'Bonus is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Bonus is failed to update.');
              redirect('bonus','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['bonus']      = null;
      $data['employees']  = $this->employee_model->get_records();

      if($bonus_id != null)
      {
        $data['bonus']       = $this->bonus_model->get_single_record($bonus_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_bonus_modal_body'] = $this->load->view('bonus/ajax/add_bonus_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_bonus_modal_body'] = $this->load->view('bonus/ajax/add_bonus_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_bonus'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$bonus_id 							= $this->input->post('bonus_id');

			$data = array('delete_status' => 1);

			$bonus =  $this->bonus_model->get_single_record($bonus_id);

			if($this->bonus_model->edit_record($data,$bonus_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $bonus_id;
					$response['message']			= 'Bonus is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Bonus is deleted successfully');
					redirect('bonus','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Bonus is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Bonus is failed to delete');
					redirect('bonus');
				}
			}
		}
	}



  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->bonus_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      //View Button
      if($this->permission_model->has_permission('edit_bonus'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('bonus_edit').'" class="btn btn-info btn-xs add_bonus_modal" data-bonus_id="'.$item->bonus_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_bonus'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_bonus" data-tt="tooltip" title="'.$this->lang->line('bonus_delete').'" class="btn btn-danger btn-xs delete_bonus" data-bonus_id="'.$item->bonus_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }

          /* End Action column buttons*/

      $select_html = '<input type="checkbox" class="single_bonus" value="'.$item->bonus_id.'" data-bonus_id="'.$item->bonus_id.'"><input type="hidden" name="bonus_id" id="bonus_id" data-bonus_id="'.$item->bonus_id.'" value="'.$item->bonus_id.'">';

      $employee_name_html = '<a href="'.base_url('employee/view/'.base64_encode($item->employee_id)).'" data-tt="tooltip" title="'.$this->lang->line('employee_view').'">'.$item->employee_name.'</a>'; 

          

      $row = array();
   
      $row[] = $select_html;
      $row[] = $employee_name_html;
      $row[] = ($item->bonus_date != '' && $item->bonus_date != '0000-00-00') ? date('d-m-Y',strtotime($item->bonus_date)) : '';
      $row[] = $item->bonus_type;
      $row[] = $item->amount;
     
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->bonus_model->count_all(),
                    "recordsFiltered"   => $this->bonus_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function bonus_delete_confirmation()
  {
  	$bonus_id 					= $this->input->post('bonus_id');
  	$data['bonus'] 		  = $this->bonus_model->get_single_record($bonus_id);

     // Get employee_id and leave_date
     $employee_id = $data['bonus']->employee_id;
     $bonus_date = $data['bonus']->bonus_date;
     $payroll_month = date('Y-m', strtotime($bonus_date));
 
     // Check if payroll exists for that month-year
     $data['payroll_exists'] = $this->payroll_history_model->get_employee_payroll_status($employee_id, $payroll_month);

  	$response 					= array();
  	$response['bonus_delete_modal_body'] 	= $this->load->view('bonus/ajax/bonus_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export()
  {
      // Get parameters from query string
      $bonus_ids = explode(',', $this->input->get('data'));
      $employee_id    = $this->input->get('employee_id');
      $from_date      = $this->input->get('from_date');
      $to_date        = $this->input->get('to_date');
      $amount     = $this->input->get('amount');
      $bonus_type         = $this->input->get('bonus_type');
  
      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'l.delete_status = 0'; 
  
      // Add conditions based on parameters
      if (!empty($bonus_ids)) {
          $whereConditions[] = 'l.bonus_id IN (' . implode(',', array_map('intval', $bonus_ids)) . ')';
      }
  
      if (!empty($employee_id)) {
          $whereConditions[] = 'l.employee_id = ' . intval($employee_id);
      }
  
      if (!empty($from_date) && !empty($to_date)) {
          // Ensure dates are in the correct format for SQL query
          $from_date = date('Y-m-d', strtotime($from_date));
          $to_date = date('Y-m-d', strtotime($to_date));
          $whereConditions[] = 'l.bonus_date BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '"';
      }
  
   
  
  
      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }
  
      // Query to fetch advance salary data
      $query = $this->db->query('SELECT 
                                      l.employee_name as "Employee Name",
                                      DATE_FORMAT(l.bonus_date, "%d-%m-%Y") as "Bonus Date",
                                      l.amount as "Amount",
                                      l.bonus_type as "Bonus Type"
                                  FROM 
                                      bonus_view l
                                  ' . $whereClause . '
                                  ORDER BY l.employee_name ASC');
  
      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');
  
      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);
  
      // Set filename for download
      $filename = 'BONUS.csv';
  
      // Download the CSV file
      force_download($filename, $data);
  }

 
}
