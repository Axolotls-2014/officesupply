<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pdc_payment extends MY_Controller
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
    if(!$this->permission_model->has_permission('list_pdc_payment'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      $data['pdc_payments'] = $this->pdc_payment_model->get_records();
      $this->load->view('pdc_payment/list',$data);
    }
     
  }

  public function add($pdc_payment_id = null)
  {
    if(!$this->permission_model->has_permission('add_pdc_payment'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
      if($this->input->server('REQUEST_METHOD') === 'POST')
      {
        // $this->form_validation->set_rules('purchase_id[]','Sale','required');
        $this->form_validation->set_rules('ledger_id','Ledger','required');        
        $this->form_validation->set_rules('pdc_cheque_no','PDC cheque No','required');
        $this->form_validation->set_rules('pdc_cheque_date','PDC Date','required');        
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
            $data['pdc_payment'] = null;
            if($pdc_payment_id != null)
              $data['pdc_payment'] = $this->pdc_payment_model->get_single_record($pdc_payment_id);

            $this->load->view('pdc_payment/add',$data);
          }
        }
        else
        {
          $purchase_id            = (!empty($this->input->post("purchase_id"))) ? implode(",",$this->input->post("purchase_id")) : '';
          $ledger_id          = $this->input->post('ledger_id');
          $pdc_cheque_no      = $this->input->post('pdc_cheque_no');
          $pdc_cheque_date           = date('Y-m-d',strtotime($this->input->post('pdc_cheque_date')));
          
          $pdc_deposit_date   = date('Y-m-d',strtotime($this->input->post('pdc_deposit_date')));
          $pdc_amount         = $this->input->post('pdc_amount');
          $pdc_deposit_to     = $this->input->post('pdc_deposit_to');

          $data       =   array(
                                "purchase_id"           =>  $purchase_id,
                                "ledger_id"         =>  $ledger_id,
                                "pdc_cheque_no"     =>  $pdc_cheque_no,
                                "pdc_cheque_date"          =>  $pdc_cheque_date,
                                "pdc_deposit_date"  =>  $pdc_deposit_date,
                                "pdc_amount"        =>  $pdc_amount,
                                "pdc_deposit_to"    =>  $pdc_deposit_to
                              );

         

          if($pdc_payment_id == NULL)
          {
            if($id = $this->pdc_payment_model->add_record($data))
            {
             
              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_SUCCESS;
                $response['message'] = 'PDC payment is added successfully';

                echo json_encode($response);  
              }
              else
              {
                $this->session->set_flashdata('success', 'PDC payment is added successfully');
                redirect('pdc_payment','refresh');  
              }
            }
            else
            {
             

              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_FAILURE;
                $response['message'] = 'PDC payment is failed to add.';

                echo json_encode($response);  
              }
              else
              {
                $this->session->set_flashdata('failure', 'PDC payment is failed to add.');
                redirect('pdc_payment','refresh');
              }
            }   
          }
          else
          {
            if($this->pdc_payment_model->edit_record($data,$pdc_payment_id))
            {
             

              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_SUCCESS;
                $response['message'] = 'PDC payment is updated successfully';

                echo json_encode($response);  
              }
              else
              { 
                $this->session->set_flashdata('success', 'PDC payment is updated successfully');
                redirect('pdc_payment','refresh');
              }
            }
            else
            {
             

              if($this->input->is_ajax_request())
              {
                $response = array();
                $response['code'] = RESPONSE_FAILURE;
                $response['message'] = 'PDC payment is failed to update.';

                echo json_encode($response);  
              }
              else
              { 
                $this->session->set_flashdata('failure', 'PDC payment is failed to update.');
                redirect('pdc_payment','refresh');
              }
            }   
          }
        }
      }
      else
      {
        $data['pdc_payment'] = null;
        $data['ledger_accounts'] 		= $this->ledger_model->get_records(array(SUNDRY_CREDITORS_GROUP));

        $data['bank_ledger_accounts'] 		= $this->ledger_model->get_records(array(BANK_ACCOUNT_GROUP));

        if($pdc_payment_id != null)
        {
          $data['pdc_payment']       = $this->pdc_payment_model->get_single_record($pdc_payment_id);

          $supplier                 = $this->utility_model->get_records_by_field('supplier','ledger_id',$data['pdc_payment']->ledger_id,$row = true,$check_delete_status = false);

          $data['purchases']            = $this->utility_model->get_records_by_field('purchase','supplier_id',$supplier->id,$row = false,$check_delete_status = false);
          
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['add_pdc_payment_modal_body'] = $this->load->view('pdc_payment/ajax/add_pdc_payment_modal_body',$data,TRUE);
          echo json_encode($response);
        }
        else
        {
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['add_pdc_payment_modal_body'] = $this->load->view('pdc_payment/ajax/add_pdc_payment_modal_body',$data,TRUE);
          echo json_encode($response);
        }
      }
    }

    
  }

  function delete()
	{
		if(!$this->permission_model->has_permission('delete_pdc_payment'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$data = array('delete_status' => 1);

			$pdc_payment = $this->pdc_payment_model->get_single_record($id);

			if($this->pdc_payment_model->edit_record($data,$id))
			{
				$log_data = array(
			                      "user_id"     => $this->session->userdata("user_id"),
			                      "module"      => "pdc_payment",
			                      "user_action" => 3,
			                      "data"        => json_encode($data),
			                      "description" => 'PDC payment deleted successfully.'
			                    );
      	$this->log_data_model->add_record($log_data);     

				$this->session->set_flashdata('success', 'PDC payment is deleted successfully');
				redirect('pdc_payment','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'PDC payment is failed to delete');
				redirect('pdc_payment','refresh');
			}
		}
	}

  function get_purchase_records_by_ledger()
	{
    
		$ledger_id = $this->input->post('ledger_id');
    $response['code']         = 1;
    
    $supplier                 = $this->utility_model->get_records_by_field('supplier','ledger_id',$ledger_id,$row = true,$check_delete_status = false);
    $response['purchases']        = $this->utility_model->get_records_by_field('purchase','supplier_id',$supplier->id,$row = false,$check_delete_status = false);

    echo json_encode($response);
	}

  public function add_deposit($pdc_payment_id = null)
  {
    if(!$this->permission_model->has_permission('add_pdc_payment'))
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

                            // print_r($data);
                            // exit;

         

        if($this->pdc_payment_model->edit_record($data,$pdc_payment_id))
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
            redirect('pdc_payment','refresh');
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
            redirect('pdc_payment','refresh');
          }
        }
      
      }
      else
      {
        $data['pdc_payment'] = null;
        

        if($pdc_payment_id != null)
        {
          $data['pdc_payment']       = $this->pdc_payment_model->get_single_record($pdc_payment_id);

          // echo '<pre>';
          // print_r($data['pdc_payment']);
          // exit;

        
          $response                        = array();
          $response['code']                = RESPONSE_SUCCESS;             
          $response['add_deposit_modal_body'] = $this->load->view('pdc_payment/ajax/add_deposit_modal_body',$data,TRUE);
          echo json_encode($response);
        }
        
      }
    }

    
  }


  /************************************** Start Dynamic Datatable function *************************************/

  public function ajax_list()
  {
    $list       = $this->pdc_payment_model->get_datatables();
    $data       = array();
    $no         = $_POST['start'];

    foreach ($list as $item){  
      /* Begin Action column buttons*/
    	/* Begin Action column buttons*/
      $table_header = '<div class="btn-group">';
      $table_footer = '</div>';
      $table_body   = '';

      if($this->permission_model->has_permission('deposit_pdc_payment'))
      {	

        $table_body .= ' 
                        <a href="#" class="btn btn-warning btn-xs add_deposit_modal"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('pdc_payment_deposit').'" data-pdc_payment_id="'.$item->id.'">
                          <i class="fas fa-money-check-alt"></i> Deposit
                        </a>
                    ';
      }

      //View Button

      if($this->permission_model->has_permission('edit_pdc_payment'))
      {	
        $table_body .= ' 
                      <a href="#" class="btn btn-info btn-xs add_pdc_payment_modal"  data-toggle="modal" data-tt="tooltip" title="'.$this->lang->line('pdc_payment_edit').'" data-pdc_payment_id="'.$item->id.'">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                    ';
      }

     
      if($this->permission_model->has_permission('delete_pdc_payment'))
      {	
        $table_body .= ' 
                        <a href="#"  data-toggle="modal" data-target="#delete_pdc_payment" data-tt="tooltip" title="'.$this->lang->line('pdc_payment_delete').'" class="btn btn-danger btn-xs delete_pdc_payment" data-pdc_payment_id="'.$item->id.'">
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
                    "recordsTotal"      => $this->pdc_payment_model->count_all(),
                    "recordsFiltered"   => $this->pdc_payment_model->count_filtered(),
                    "data"              => $data,
                  );
   
    echo json_encode($output);
  }

  /************************************** End Dynamic Datatable function ***************************************/

  public function pdc_payment_delete_confirmation()
  {
  	$pdc_payment_id 					= $this->input->post('pdc_payment_id');
  	$data['pdc_payment'] 		= $this->pdc_payment_model->get_single_record($pdc_payment_id);

  	$response 					= array();
  	$response['pdc_payment_delete_modal_body'] 	= $this->load->view('pdc_payment/ajax/delete_pdc_payment_modal_body',$data,TRUE);

  	echo json_encode($response);
	}
}
