<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_message extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_whatsapp_message'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['whatsapp_messages'] = $this->whatsapp_message_model->get_records();
      $this->load->view('whatsapp_message/index',$data);
    }
  }

  public function add()
  {
      if ($this->input->server('REQUEST_METHOD') === 'POST')
      {
          $this->form_validation->set_rules('wm_message', 'Message', 'required');
  
          if ($this->form_validation->run() == FALSE)
          {
              if ($this->input->is_ajax_request())
              {
                  $data['code'] = 2;
                  $data['errors'] = $this->form_validation->error_array();
                  echo json_encode($data);
              }
              else
              {
                  $this->load->view('whatsapp_template/add', $data);
              }
          }
          else
          {
              $wm_message = $this->input->post('wm_message');
              $wm_to = $this->input->post('wm_to');
  
              $phone_numbers = explode(',', $wm_to);
              
              $this->db->trans_begin();
  
              $errors = [];
              foreach ($phone_numbers as $phone_number)
              {
                  $data = array(
                      "wm_message" => $wm_message,
                      "wm_to" => trim($phone_number)
                  );
  
                  if (!$this->whatsapp_message_model->add_record($data))
                  {
                      $errors[] = "Failed to add record for phone number: " . trim($phone_number);
                  }
              }
  
              if (empty($errors))
              {
                  // commit transaction
                  $this->db->trans_commit();
  
                  if ($this->input->is_ajax_request())
                  {
                      $response = array();
                      $response['code'] = RESPONSE_SUCCESS;
                      $response['message'] = 'Whatsapp messages are scheduled to send.';
  
                      echo json_encode($response);
                  }
                  else
                  {
                      $this->session->set_flashdata('success', 'Whatsapp messages are scheduled to send.');
                      redirect('whatsapp_message', 'refresh');
                  }
              }
              else
              {
                  // rollback transaction
                  $this->db->trans_rollback();
  
                  if ($this->input->is_ajax_request())
                  {
                      $response = array();
                      $response['code'] = RESPONSE_FAILURE;
                      $response['message'] = implode(', ', $errors);
  
                      echo json_encode($response);
                  }
                  else
                  {
                      $this->session->set_flashdata('failure', 'Whatsapp messages are failed to schedule.');
                      redirect('whatsapp_message', 'refresh');
                  }
              }
          }
      }
  }

  public function export()
  {
      // Get parameters from query string
      $wm_ids = explode(',', $this->input->get('data'));

      // Initialize where conditions array
      $whereConditions = [];

      // Add conditions based on parameters
      if (!empty($wm_ids)) {
          $whereConditions[] = 'w.wm_id IN (' . implode(',', array_map('intval', $wm_ids)) . ')';
      }

      // Construct the WHERE clause
      $whereClause = '';
      if (!empty($whereConditions)) {
          $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
      }

      // Query to fetch leave data
      $query = $this->db->query('SELECT 
                                      w.wm_message as "Whatsapp message",
                                      w.wm_to as "To",
                                      w.wm_status as "Whatsapp Status"
                                  FROM 
                                      whatsapp_messages w
                                  ' . $whereClause . '
                                  ORDER BY w.wm_status ASC');

      // Load necessary helpers
      $this->load->dbutil();
      $this->load->helper('download');

      // Generate CSV from query result
      $data = $this->dbutil->csv_from_result($query);

      // Set filename for download
      $filename = 'WHATSAPP-MESSAGES.csv';

      // Download the CSV file
      force_download($filename, $data);
  }


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->whatsapp_message_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      $select_html = '<input type="checkbox" class="single_whatsapp_message" value="'.$item->wm_id.'" data-wm_id="'.$item->wm_id.'"><input type="hidden" name="wm_id" id="wm_id" data-wm_id="'.$item->wm_id.'" value="'.$item->wm_id.'">';

      $wm_status = '';
      if ($item->wm_status == 'pending') 
      {
          $wm_status = '<span class="badge badge-warning">Pending</span>';
      } 
      elseif ($item->wm_status == 'sent') 
      {
          $wm_status = '<span class="badge badge-success">Sent</span>';
      } 
    

      
      $row = array();

      $row[] = $select_html;
   
   
      // Truncate the message to 30 characters
      $truncated_message = (strlen($item->wm_message) > 30) ? substr($item->wm_message, 0, 30) . '...' : $item->wm_message;

      // Add a data attribute to store the full message
      $row[] = '<span class="truncated-message">' . $truncated_message . '</span>' .
               '<span class="full-message" style="display:none;">' . $item->wm_message . '</span>' .
               '<a href="javascript:void(0);" class="read-more-link">Read More</a>';


   

      $row[] = $item->wm_to;
      $row[] = $wm_status;
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->whatsapp_message_model->count_all(),
                    "recordsFiltered"   => $this->whatsapp_message_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/



 
}
