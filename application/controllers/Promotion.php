<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    if (!$this->ion_auth->logged_in())
    {
      redirect('login', 'refresh');
    }
  }

  public function index()
  {
      $data['promotions'] = $this->promotion_model->get_records();
      $this->load->view('promotion/index',$data);
  }

  public function add($promotion_id = null)
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      if($promotion_id == null)
        $this->form_validation->set_rules('customer_id[]','Customer','required');        
      else
        $this->form_validation->set_rules('customer_id','Customer','required');        

      if($promotion_id == null)
        $this->form_validation->set_rules('product_id[]','Product','required');        
      else
        $this->form_validation->set_rules('product_id','Product','required');   
           
      $this->form_validation->set_rules('configuration','Configuration','required');
      
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
          $data['promotion'] = null;
          if($promotion_id != null)
            $data['promotion'] = $this->promotion_model->get_single_record($promotion_id);

          $this->load->view('promotion/add',$data);
        }
      }
      else
      {
        $customer_id                  = $this->input->post('customer_id');
        $product_id                   = $this->input->post('product_id');
        $configuration                = $this->input->post('configuration');
        $percentage                   = $this->input->post('percentage');
        $promotion_type               = $this->input->post('promotion_type');

        $this->db->trans_begin();

        if(($promotion_id == NULL))
        { 
          foreach ($customer_id as $single_customer_id) 
          {
            foreach ($product_id as $single_product_id) 
            {
              $data_temp = array(
                  "customer_id" => $single_customer_id,
                  "product_id" => $single_product_id,
                  "configuration" => $configuration,
                  "percentage" => $percentage,
                  "promotion_type" => $promotion_type
              );
      
              // Insert data into the database table
              $this->promotion_model->add_record($data_temp);
            }
          }
          

          
          if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_FAILURE;
              $response['message'] = 'Configuration is failed to add.';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('failure', 'Configuration is failed to add.');
              redirect('promotion','refresh');
            }
          }
          else
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Configuration is added successfully';

              echo json_encode($response);  
            }
            else
            {
              $this->session->set_flashdata('success', 'Configuration is added successfully');
              redirect('promotion','refresh');  
            }
          }  
        }
        else
        {
          $data = array(
            "customer_id"     =>  $customer_id,
            "product_id"      =>  $product_id,
            "configuration"   =>  $configuration,
            "percentage"      =>  $percentage,
            "promotion_type"  =>  $promotion_type
          );

          if($this->promotion_model->edit_record($data,$promotion_id))
          {
            // commit transaction
            $this->db->trans_commit();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_SUCCESS;
              $response['message'] = 'Configuration is updated successfully';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('success', 'Configuration is updated successfully');
              redirect('promotion','refresh');
            }
          }
          else
          {
            log_message('error','edit query '.print_last_query());
            // rollback transaction
            $this->db->trans_rollback();

            if($this->input->is_ajax_request())
            {
              $response = array();
              $response['code'] = RESPONSE_FAILURE;
              $response['message'] = 'Configuration is failed to update.';

              echo json_encode($response);  
            }
            else
            { 
              $this->session->set_flashdata('failure', 'Configuration is failed to update.');
              redirect('promotion','refresh');
            }
          }   
        }
      }
    }
    else
    {
      $data['promotion'] = null;

      if($promotion_id != null)
      {        
        $data['promotion']       				 = $this->promotion_model->get_single_record($promotion_id);
        $data['promotion_id']            = $promotion_id;
        $data['products']                = $this->product_core_model->get_records();
        $data['customers']               = $this->customer_model->get_records();
        
        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_promotion_modal_body'] = $this->load->view('promotion/ajax/add_promotion_modal_body',$data,TRUE);
        echo json_encode($response);
      }
      else
      {
        $data['promotion']       				 = $this->promotion_model->get_single_record(1);
        $data['promotion_id']            = $promotion_id;
        $data['products']                = $this->product_core_model->get_records();
        $data['customers']               = $this->customer_model->get_records();
        

        $response                        = array();
        $response['code']                = RESPONSE_SUCCESS;             
        $response['add_promotion_modal_body'] = $this->load->view('promotion/ajax/add_promotion_modal_body',$data,TRUE);
        echo json_encode($response);
      }
    }
  }

  public function action()
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $promotion_id     = $this->input->post('promotion_id');

      if($this->promotion_model->delete_record($promotion_id))
      {
        if ($this->input->is_ajax_request()) 
        {
          $response['code'] = 1;
          $response['message'] = 'Configuration is deleted successfully.';

          echo json_encode($response);
        }
        else
        {
          $this->session->set_flashdata('success', 'Configuration is deleted successfully.');
          redirect('promotion','refresh');  
        }
      } 
      else
      {
        if ($this->input->is_ajax_request()) 
        {
          $response['code'] = 0;
          $response['message'] = 'Configuration is failed to delete.';

          echo json_encode($response);
        }
        else
        {
          $this->session->set_flashdata('failure', 'Configuration is failed to delete');
          redirect('promotion','refresh');
        }
      }
    }
    else
    {
      $this->session->set_flashdata('failure', 'You are trying to access the broken link.');
      redirect('promotion','refresh');
    }
  }

  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->promotion_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];
    $query      = $this->db->last_query();

    foreach ($list as $item){  
      /* Begin Action column buttons*/
      $table_header = '';
      $table_footer = '';
      $table_body   = '';

      //View Button

      $table_body .= ' 
                <button class="btn btn-sm btn-info add_promotion_modal" data-promotion_id="'.$item->promotion_id.'">
                  <i class="fas fa-edit"></i>
                </button>
              ';
      
      $table_body .= ' 
                <button class="btn btn-sm btn-danger delete-warning" data-promotion_id="'.$item->promotion_id.'">
                  <i class="fas fa-trash"></i>
                </button>
              ';
      /* End Action column buttons*/

      $row = array();

      $row[] = $item->customer_name;
      $row[] = ($item->product_id == 0) ? 'All' : $item->product_name ;
      $row[] = $item->promotion_type;
      $row[] = $item->percentage;
      $row[] = generate_table($item->configuration);
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->promotion_model->count_all(),
                    "recordsFiltered"   => $this->promotion_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/
}
