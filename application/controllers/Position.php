<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Position extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_position'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['positions'] = $this->position_model->get_records();
      $this->load->view('position/index',$data);
    }
  }

  public function add($position_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->form_validation->set_rules('position_title','Position Title','required');        
      
      
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
          $data['position'] = null;
          if($position_id != null)
            $data['position'] = $this->position_model->get_single_record($position_id);

          $this->load->view('position/add',$data);
        }
      }
      else
      {
        $position_title   = $this->input->post('position_title');
        $position_code 				= $this->position_model->get_lastest_sequence_number();
       
        $this->db->trans_begin();

        $data       =   array(
                              "position_title"    =>  $position_title,
                              "position_code"    =>  $position_code
                            );

        if($position_id == NULL)
        {
          if($id = $this->position_model->add_record($data))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['id']   = $id;
              $response['message'] = 'Position is added successfully';
              $response['positions'] = $this->position_model->get_records();

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Position is added successfully');
              redirect('position','refresh');  
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
              $response['message'] = 'Position is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Position is failed to add.');
              redirect('position','refresh');
            }
          }   
        }
        else
        {
          if($this->position_model->edit_record($data,$position_id))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Position is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Position is updated successfully');
              redirect('position','refresh');
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
              $response['message'] = 'Position is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Position is failed to update.');
              redirect('position','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['position'] = null;

      if($position_id != null)
      {
        $data['position']       = $this->position_model->get_single_record($position_id);
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_position_modal_body'] = $this->load->view('position/ajax/add_position_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_position_modal_body'] = $this->load->view('position/ajax/add_position_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_position'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$position_id 							= $this->input->post('position_id');

			$data = array('delete_status' => 1);

			$position =  $this->position_model->get_single_record($position_id);

			if($this->position_model->edit_record($data,$position_id))
			{
				
				if($this->input->is_ajax_request())
				{	
					$response 								= array();
					$response['code'] 				= 1;
					$response['id'] 					= $position_id;
					$response['message']			= 'Position is deleted successfully.';

					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('success', 'Position is deleted successfully');
					redirect('position','refresh');
				}
			}
			else
			{
				if($this->input->is_ajax_request())
				{	
					$response 						= array();
					$response['code'] 		= 0;
					$response['message']	= 'Position is failed to delete.';
				
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('failure', 'Position is failed to delete');
					redirect('position');
				}
			}
		}
	}




  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->position_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      
      if($this->permission_model->has_permission('edit_position'))
      {	
        $table_body .= '<a href="#" data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('position_edit').'" class="btn btn-info btn-xs add_position_modal" data-position_id="'.$item->position_id.'">
                          <i class="fas fa-edit"></i> Edit
                        </a>';
      }

      // Delete Button
      if($this->permission_model->has_permission('delete_position'))
      {	
        $table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_position" data-tt="tooltip" title="'.$this->lang->line('position_delete').'" class="btn btn-danger btn-xs delete_position" data-position_id="'.$item->position_id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>';
      }
    
      /* End Action column buttons*/

      $select_html = '<input type="checkbox" class="single_position" value="'.$item->position_id.'" data-position_id="'.$item->position_id.'"><input type="hidden" name="position_id" id="position_id" data-position_id="'.$item->position_id.'" value="'.$item->position_id.'">';

      $row = array();
   
      $row[] = $select_html;
      $row[] = $item->position_code;
      $row[] = $item->position_title;
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->position_model->count_all(),
                    "recordsFiltered"   => $this->position_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function position_delete_confirmation()
  {
  	$position_id 					= $this->input->post('position_id');
  	$data['position'] 		= $this->position_model->get_single_record($position_id);
    $data['employee']     =  $this->position_model->check_employee_exists($position_id);

  	$response 					= array();
  	$response['position_delete_modal_body'] 	= $this->load->view('position/ajax/position_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

  public function export_from_import_employee()
  {
    $query = $this->position_model->export_records();
    $this->load->dbutil();
    
    $data = $this->dbutil->csv_from_result($query);
    
    $this->load->helper('download');
    force_download("POSITION.CSV", $data);
  }

  public function export()
  {
      // Get parameters from query string
      $position_ids = explode(',', $this->input->get('data'));
    
      // Initialize where conditions array
      $whereConditions = [];
      $whereConditions[] = 'l.delete_status = 0'; // Add condition for delete_status
    
      // Add conditions based on parameters
      if (!empty($position_ids)) {
          $whereConditions[] = 'l.position_id IN (' . implode(',', array_map('intval', $position_ids)) . ')';
      }

      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }
    
      // Query to fetch data
      $query = $this->db->query('SELECT 
                                      l.position_code as "Position Code",
                                      l.position_title as "Position Title"
                                  FROM 
                                      hr_positions l
                                  ' . $whereClause . '
                                  ORDER BY l.position_title ASC');

      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');

      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);

      // Set filename for download
      $filename = 'POSITION.csv';

      // Download the CSV file
      force_download($filename, $data);
  }

 
}
