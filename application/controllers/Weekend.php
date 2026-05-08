<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Weekend extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_weekend'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['weekends'] = $this->weekend_model->get_records();
      $this->load->view('weekend/index',$data);
    }
  }

  public function add($weekend_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('weekend_date', 'Weekend Date', 'required|callback_is_unique_date[' . $weekend_id . ']');   
     
      
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
          $data['weekend'] = null;
          if($weekend_id != null)
            $data['weekend'] = $this->weekend_model->get_single_record($weekend_id);

          $this->load->view('weekend/add',$data);
        }
      }
      else
      {
       //$weekend_date        = $this->input->post('weekend_date');
        $description       = $this->input->post('description');
       

        $this->db->trans_begin();

        $data       =   array(
                             //"weekend_date"   =>  $weekend_date,
                              "description"  =>  $description
                              
                            );

        $weekend_date        = $this->input->post('weekend_date');

        if($weekend_date != '')
            $data['weekend_date'] = date('Y-m-d',strtotime($weekend_date));

        if($weekend_id == NULL)
        {
          if($id = $this->weekend_model->add_record($data))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Weekend is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Weekend is added successfully');
              redirect('weekend','refresh');  
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
              $response['message'] = 'Weekend is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Weekend is failed to add.');
              redirect('weekend','refresh');
            }
          }   
        }
        else
        {
          if($this->weekend_model->edit_record($data,$weekend_id))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Weekend is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Weekend is updated successfully');
              redirect('weekend','refresh');
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
              $response['message'] = 'Weekend is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Weekend is failed to update.');
              redirect('weekend','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['weekend'] = null;

      if($weekend_id != null)
      {
        $data['weekend']       = $this->weekend_model->get_single_record($weekend_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_weekend_modal_body'] = $this->load->view('weekend/ajax/add_weekend_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_weekend_modal_body'] = $this->load->view('weekend/ajax/add_weekend_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_weekend'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$weekend_id 							= $this->input->post('weekend_id');

			$data = array('delete_status' => 1);

			$weekend =  $this->weekend_model->get_single_record($weekend_id);

			if($this->weekend_model->edit_record($data,$weekend_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $weekend_id;
					$response['message']			= 'Weekend is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Weekend is deleted successfully');
					redirect('weekend','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Weekend is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Weekend is failed to delete');
					redirect('weekend');
				}
			}
		}
	}

  public function is_unique_date($weekend_date, $weekend_id = null)
  {
    $weekend_date = date('Y-m-d', strtotime($weekend_date));
      $weekend = $this->utility_model->get_records_by_field('hr_weekends', 'weekend_date', $weekend_date, $row = true, $check_delete_status = true);
  
      if ($weekend != null) 
      {
          if ($weekend->weekend_id == $weekend_id)
          {
              return true;
          }
          else
          {
              $this->form_validation->set_message('is_unique_date', 'Weekend date already exists.');
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
    $list       = $this->weekend_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      if($this->permission_model->has_permission('edit_weekend'))
      {	
        $table_body .= '<a href="#" data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('weekend_edit').'" class="btn btn-info btn-xs add_weekend_modal" data-weekend_id="'.$item->weekend_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_weekend'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_weekend" data-tt="tooltip" title="'.$this->lang->line('weekend_delete').'" class="btn btn-danger btn-xs delete_weekend" data-weekend_id="'.$item->weekend_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }

      /* End Action column buttons*/

      $select_html = '<input type="checkbox" class="single_weekend" value="'.$item->weekend_id.'" data-weekend_id="'.$item->weekend_id.'"><input type="hidden" name="weekend_id" id="weekend_id" data-weekend_id="'.$item->weekend_id.'" value="'.$item->weekend_id.'">';

          

      $row = array();
   
      $row[] = $select_html;
      $row[] = ($item->weekend_date != '' && $item->weekend_date != '0000-00-00') ? date('d-m-Y',strtotime($item->weekend_date)) : '';
      $row[] = $item->description;
     
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->weekend_model->count_all(),
                    "recordsFiltered"   => $this->weekend_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function weekend_delete_confirmation()
  {
  	$weekend_id 				= $this->input->post('weekend_id');
  	$data['weekend'] 		= $this->weekend_model->get_single_record($weekend_id);

    $weekend_date = $data['weekend']->weekend_date;
    $payroll_month = date('Y-m', strtotime($weekend_date));

    // Check if payroll exists for that month-year
    $data['payroll_exists'] = $this->payroll_history_model->get_employee_date_month_payroll_status($payroll_month);

  	$response 					= array();
  	$response['weekend_delete_modal_body'] 	= $this->load->view('weekend/ajax/weekend_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export()
  {
      // Get parameters from query string
      $weekend_ids = explode(',', $this->input->get('data'));
     
      $month = $this->input->get('month');
      $year = $this->input->get('year');
     

      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'a.delete_status = 0';

      // Add conditions based on parameters
      if (!empty($weekend_ids)) {
          $whereConditions[] = 'a.weekend_id IN (' . implode(',', $weekend_ids) . ')';
      }

     

      if (!empty($month)) {
          $whereConditions[] = 'MONTH(a.weekend_date) = ' . $month;
      }

      if (!empty($year)) {
          $whereConditions[] = 'YEAR(a.weekend_date) = ' . $year;
      }

      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }

      // Query to fetch attendance data
      $query = $this->db->query('SELECT 
                                      a.description as "Description",
                                      DATE_FORMAT(a.weekend_date, "%d-%m-%Y") as "Weekend Date"
                                     
                                  FROM 
                                      hr_weekends a
                                  ' . $whereClause . '
                                  ORDER BY a.description ASC');

      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');

      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);

      // Set filename for download
      $filename = 'WEEKEND.csv';

      // Download the CSV file
      force_download($filename, $data);
  }

 
}
