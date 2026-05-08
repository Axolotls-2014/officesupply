<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday extends MY_Controller
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

    if(!$this->permission_model->has_permission('list_holiday'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['holidays'] = $this->holiday_model->get_records();
      $this->load->view('holiday/index',$data);
    }
  }
  public function add($holiday_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('holiday_name','Holiday Name','required');        
      $this->form_validation->set_rules('holiday_date','Holiday Date','required|callback_is_unique_date[' . $holiday_id . ']');
      
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
          $data['holiday'] = null;
          if($holiday_id != null)
            $data['holiday'] = $this->holiday_model->get_single_record($holiday_id);

          $this->load->view('holiday/add',$data);
        }
      }
      else
      {
      

        $holiday_name       = $this->input->post('holiday_name');
       

        $this->db->trans_begin();

        $data       =   array(
                            //  "holiday_date"   =>  $holiday_date,
                              "holiday_name"  =>  $holiday_name
                              
                            );

        $holiday_date        = $this->input->post('holiday_date');

        if($holiday_date != '')
            $data['holiday_date'] = date('Y-m-d',strtotime($holiday_date));

        if($holiday_id == NULL)
        {
          if($id = $this->holiday_model->add_record($data))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Holiday is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Holiday is added successfully');
              redirect('holiday','refresh');  
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
              $response['message'] = 'Holiday is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Holiday is failed to add.');
              redirect('holiday','refresh');
            }
          }   
        }
        else
        {
          if($this->holiday_model->edit_record($data,$holiday_id))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Holiday is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Holiday is updated successfully');
              redirect('holiday','refresh');
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
              $response['message'] = 'Holiday is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Holiday is failed to update.');
              redirect('holiday','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['holiday'] = null;

      if($holiday_id != null)
      {
        $data['holiday']       = $this->holiday_model->get_single_record($holiday_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_holiday_modal_body'] = $this->load->view('holiday/ajax/add_holiday_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_holiday_modal_body'] = $this->load->view('holiday/ajax/add_holiday_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_holiday'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$holiday_id 							= $this->input->post('holiday_id');

			$data = array('delete_status' => 1);

			$holiday =  $this->holiday_model->get_single_record($holiday_id);

			if($this->holiday_model->edit_record($data,$holiday_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $holiday_id;
					$response['message']			= 'Holiday is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Holiday is deleted successfully');
					redirect('holiday','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Holiday is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Holiday is failed to delete');
					redirect('holiday');
				}
			}
		}
	}

  public function is_unique_date($holiday_date, $holiday_id = null)
  {
    $holiday_date = date('Y-m-d', strtotime($holiday_date));
    $holiday = $this->utility_model->get_records_by_field('hr_holidays', 'holiday_date', $holiday_date, $row = true, $check_delete_status = true);

    if ($holiday != null) 
    {
        if ($holiday->holiday_id == $holiday_id)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('is_unique_date', 'Holiday date already exists.');
            return false;
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
    $list       = $this->holiday_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      if($this->permission_model->has_permission('edit_holiday'))
      {	
        $table_body .= '<a href="#" data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('holiday_edit').'" class="btn btn-info btn-xs add_holiday_modal" data-holiday_id="'.$item->holiday_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_holiday'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_holiday" data-tt="tooltip" title="'.$this->lang->line('holiday_delete').'" class="btn btn-danger btn-xs delete_holiday" data-holiday_id="'.$item->holiday_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }

    
     
          /* End Action column buttons*/

      $select_html = '<input type="checkbox" class="single_holiday" value="'.$item->holiday_id.'" data-holiday_id="'.$item->holiday_id.'"><input type="hidden" name="holiday_id" id="holiday_id" data-holiday_id="'.$item->holiday_id.'" value="'.$item->holiday_id.'">';

          

      $row = array();
   
      $row[] = $select_html;
      $row[] = ($item->holiday_date != '' && $item->holiday_date != '0000-00-00') ? date('d-m-Y',strtotime($item->holiday_date)) : '';
      $row[] = $item->holiday_name;
     
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->holiday_model->count_all(),
                    "recordsFiltered"   => $this->holiday_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function holiday_delete_confirmation()
  {
  	$holiday_id 				= $this->input->post('holiday_id');
  	$data['holiday'] 		= $this->holiday_model->get_single_record($holiday_id);

    $holiday_date = $data['holiday']->holiday_date;
    $payroll_month = date('Y-m', strtotime($holiday_date));

    // Check if payroll exists for that month-year
    $data['payroll_exists'] = $this->payroll_history_model->get_employee_date_month_payroll_status($payroll_month);

  	$response 					= array();
  	$response['holiday_delete_modal_body'] 	= $this->load->view('holiday/ajax/holiday_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export()
  {
      // Get parameters from query string
      $holiday_ids = explode(',', $this->input->get('data'));
      
      $month = $this->input->get('month');
      $year = $this->input->get('year');
      
      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'a.delete_status = 0';

      // Add conditions based on parameters
      if (!empty($holiday_ids)) {
          $whereConditions[] = 'a.holiday_id IN (' . implode(',', $holiday_ids) . ')';
      }

     

      if (!empty($month)) {
          $whereConditions[] = 'MONTH(a.holiday_date) = ' . $month;
      }

      if (!empty($year)) {
          $whereConditions[] = 'YEAR(a.holiday_date) = ' . $year;
      }

      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }

      // Query to fetch attendance data
      $query = $this->db->query('SELECT 
                                      a.holiday_name as "Holiday Name",
                                      DATE_FORMAT(a.holiday_date, "%d-%m-%Y") as "Holiday Date"
                                     
                                  FROM 
                                      hr_holidays a
                                  ' . $whereClause . '
                                  ORDER BY a.holiday_name ASC');

      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');

      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);

      // Set filename for download
      $filename = 'HOLIDAY.csv';

      // Download the CSV file
      force_download($filename, $data);
  }
 
}
