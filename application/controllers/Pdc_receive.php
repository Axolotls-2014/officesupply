<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pdc_receive extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_pdc_receive'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['pdc_receives'] = $this->pdc_receive_model->get_records();
      $this->load->view('pdc_receive/list',$data);
    }
     
  }

  public function add($pdc_receive_id = null)
  {
    if(!$this->permission_model->has_permission('add_pdc_receive'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      if($this->input->server('REQUEST_METHOD') === 'POST')
      {
        // $this->form_validation->set_rules('sale_id[]','Sale','required');
        $this->form_validation->set_rules('ledger_id','Ledger','required');        
        $this->form_validation->set_rules('pdc_cheque_no','PDC cheque No','required');
        $this->form_validation->set_rules('pdc_cheque_date','PDC cheque date','required');        
        $this->form_validation->set_rules('pdc_deposit_date','PDC deposit date','required');  
        $this->form_validation->set_rules('pdc_amount','PDC amount','required'); 
        $this->form_validation->set_rules('pdc_deposit_to','PDC deposit','required'); 
       
        
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
            $data['pdc_receive'] = null;
            if($pdc_receive_id != null)
              $data['pdc_receive'] = $this->pdc_receive_model->get_single_record($pdc_receive_id);

            $this->load->view('pdc_receive/add',$data);
          }
        }
        else
        {
          $sale_id            = (!empty($this->input->post("sale_id"))) ? implode(",",$this->input->post("sale_id")) : '';
          $ledger_id          = $this->input->post('ledger_id');
          $pdc_cheque_no      = $this->input->post('pdc_cheque_no');
          $pdc_cheque_date           = date('Y-m-d',strtotime($this->input->post('pdc_cheque_date')));
          
          $pdc_deposit_date   = date('Y-m-d',strtotime($this->input->post('pdc_deposit_date')));
          $pdc_amount         = $this->input->post('pdc_amount');
          $pdc_deposit_to     = $this->input->post('pdc_deposit_to');

          $data       =   array(
                                "sale_id"           =>  $sale_id,
                                "ledger_id"         =>  $ledger_id,
                                "pdc_cheque_no"     =>  $pdc_cheque_no,
                                "pdc_cheque_date"          =>  $pdc_cheque_date,
                                "pdc_deposit_date"  =>  $pdc_deposit_date,
                                "pdc_amount"        =>  $pdc_amount,
                                "pdc_deposit_to"    =>  $pdc_deposit_to
                              );

         

          if($pdc_receive_id == NULL)
          {
            if($id = $this->pdc_receive_model->add_record($data))
            {
             
              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_SUCCESS;
                $response['message'] = 'PDC receive is added successfully';

                echo json_encode($response);  
              }
              else
              {
                $this->session->set_flashdata('success', 'PDC receive is added successfully');
                redirect('pdc_receive','refresh');  
              }
            }
            else
            {
             

              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_FAILURE;
                $response['message'] = 'PDC receive is failed to add.';

                echo json_encode($response);  
              }
              else
              {
                $this->session->set_flashdata('failure', 'PDC receive is failed to add.');
                redirect('pdc_receive','refresh');
              }
            }   
          }
          else
          {
            if($this->pdc_receive_model->edit_record($data,$pdc_receive_id))
            {
             

              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_SUCCESS;
                $response['message'] = 'PDC receive is updated successfully';

                echo json_encode($response);  
              }
              else
              { 
                $this->session->set_flashdata('success', 'PDC receive is updated successfully');
                redirect('pdc_receive','refresh');
              }
            }
            else
            {
             

              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_FAILURE;
                $response['message'] = 'PDC receive is failed to update.';

                echo json_encode($response);  
              }
              else
              { 
                $this->session->set_flashdata('failure', 'PDC receive is failed to update.');
                redirect('pdc_receive','refresh');
              }
            }   
          }
        }
      }
      else
      {
        $data['pdc_receive'] = null;
        $data['ledger_accounts'] 		= $this->ledger_model->get_records(array(SUNDRY_DEBTORS_GROUP));

        $data['bank_ledger_accounts'] 		= $this->ledger_model->get_records(array(BANK_ACCOUNT_GROUP));

        if($pdc_receive_id != null)
        {
          $data['pdc_receive']       = $this->pdc_receive_model->get_single_record($pdc_receive_id);

          $customer                 = $this->utility_model->get_records_by_field('customer','ledger_id',$data['pdc_receive']->ledger_id,$row = true,$check_delete_status = false);

          $data['sales']    = $this->utility_model->get_records_by_field('sale','customer_id',$customer->id,$row = false,$check_delete_status = false);
          
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['add_pdc_receive_modal_body'] = $this->load->view('pdc_receive/ajax/add_pdc_receive_modal_body',$data,TRUE);
          echo json_encode($response);
        }
        else
        {
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['add_pdc_receive_modal_body'] = $this->load->view('pdc_receive/ajax/add_pdc_receive_modal_body',$data,TRUE);
          echo json_encode($response);
        }
      }
    }

    
  }

  public function add_deposit($pdc_receive_id = null)
  {
    if(!$this->permission_model->has_permission('add_pdc_receive'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      if($this->input->server('REQUEST_METHOD') === 'POST')
      {
       
        $pay_slip_no         = $this->input->post('pay_slip_no');
        $actual_deposit_date  = ($this->input->post('actual_deposit_date') != '') ? date('Y-m-d', strtotime($this->input->post('actual_deposit_date'))) : '';
        $deposited_by         = $this->input->post('deposited_by');
        

        $data       =   array(
                              "actual_deposit_date"   =>  $actual_deposit_date,
                              "pay_slip_no"          =>  $pay_slip_no,
                              "deposited_by"          =>  $deposited_by
                              
                            );

         

        if($this->pdc_receive_model->edit_record($data,$pdc_receive_id))
        {
          

          if($this->input->is_ajax_request())
          {
            $response = array();
            $response['code'] = RESPONSE_SUCCESS;
            $response['message'] = 'PDC deposit is updated successfully';

            echo json_encode($response);  
          }
          else
          { 
            $this->session->set_flashdata('success', 'PDC deposit is updated successfully');
            redirect('pdc_receive','refresh');
          }
        }
        else
        {
          

          if($this->input->is_ajax_request())
          {
            $response = array();
            $response['code'] = RESPONSE_FAILURE;
            $response['message'] = 'PDC deposit is failed to update.';

            echo json_encode($response);  
          }
          else
          { 
            $this->session->set_flashdata('failure', 'PDC deposit is failed to update.');
            redirect('pdc_receive','refresh');
          }
        }
      
      }
      else
      {
        $data['pdc_receive'] = null;
        

        if($pdc_receive_id != null)
        {
          $data['pdc_receive']       = $this->pdc_receive_model->get_single_record($pdc_receive_id);

        
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['add_deposit_modal_body'] = $this->load->view('pdc_receive/ajax/add_deposit_modal_body',$data,TRUE);
          echo json_encode($response);
        }
        
      }
    }

    
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_pdc_receive'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$data = array('delete_status' => 1);

			$pdc_receive = $this->pdc_receive_model->get_single_record($id);

			if($this->pdc_receive_model->edit_record($data,$id))
			{
				$log_data = array(
			                      "user_id"     => $this->session->userdata("user_id"),
			                      "module"      => "pdc_receive",
			                      "user_action" => 3,
			                      "data"        => json_encode($data),
			                      "description" => 'PDC receive deleted successfully.'
			                    );
      	$this->log_data_model->add_record($log_data);     

				$this->session->set_flashdata('success', 'PDC receive is deleted successfully');
				redirect('pdc_receive','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'PDC receive is failed to delete');
				redirect('pdc_receive','refresh');
			}
		}
	}

  function get_sale_records_by_ledger()
	{
    
		$ledger_id = $this->input->post('ledger_id');
    $response['code']         = 1;
    
    $customer                 = $this->utility_model->get_records_by_field('customer','ledger_id',$ledger_id,$row = true,$check_delete_status = false);
    $response['sales']        = $this->utility_model->get_records_by_field('sale','customer_id',$customer->id,$row = false,$check_delete_status = false);

    echo json_encode($response);
	}


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->pdc_receive_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
    	/* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      if($this->permission_model->has_permission('deposit_pdc_receive'))
      {	
        $table_body .= ' 
                      <a href="#" class="btn btn-warning btn-xs add_deposit_modal"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('pdc_receive_deposit').'" data-pdc_receive_id="'.$item->id.'">
                      <i class="fas fa-money-check-alt"></i> Deposit
                      </a>
                    ';
      }

      //View Button

      if($this->permission_model->has_permission('edit_pdc_receive'))
      {	
        $table_body .= ' 
                      <a href="#" class="btn btn-info btn-xs add_pdc_receive_modal"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('pdc_receive_edit').'" data-pdc_receive_id="'.$item->id.'">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                    ';
      }

     
      if($this->permission_model->has_permission('delete_pdc_receive'))
      {	
        $table_body .= ' 
                        <a href="#"  data-toggle="modal" data-target="#delete_pdc_receive" data-tt="tooltip" title="'.$this->lang->line('pdc_receive_delete').'" class="btn btn-danger btn-xs delete_pdc_receive" data-pdc_receive_id="'.$item->id.'">
                          <i class="fas fa-trash"></i> Delete
                        </a>
                      ';
      }
      

          /* End Action column buttons*/

          

      $row = array();
   

      $row[] = $item->ledger_title;
      $row[] = $item->pdc_cheque_no;
      $row[] = date('d-m-Y', strtotime($item->pdc_cheque_date));
      $row[] = $item->pdc_amount;
      $row[] = $item->deposit_to_title;
      $row[] =  date('d-m-Y', strtotime($item->pdc_deposit_date));
      
      $row[] = $table_header.$table_body.$table_footer;    

      $data[] = $row;
    }

    $output = array(  
                    "draw"              => $_POST['draw'],
                    "recordsTotal"      => $this->pdc_receive_model->count_all(),
                    "recordsFiltered"   => $this->pdc_receive_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function pdc_receive_delete_confirmation()
  {
  	$pdc_receive_id 					= $this->input->post('pdc_receive_id');
  	$data['pdc_receive'] 		= $this->pdc_receive_model->get_single_record($pdc_receive_id);

  	$response 					= array();
  	$response['pdc_receive_delete_modal_body'] 	= $this->load->view('pdc_receive/ajax/delete_pdc_receive_modal_body',$data,TRUE);

  	echo json_encode($response);
	}
}
