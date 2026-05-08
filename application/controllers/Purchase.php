<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Purchase extends MY_Controller {

	private $pdf;

	public function __construct()
	{
		parent::__construct();
		$this->pdf = new Dompdf();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	public function view_requests()
    {
        $data['orders'] = $this->db->get('order_requests')->result_array();
        $this->load->view('purchase/order_requests_view', $data);
    }
	public function index()
	{
		$data['warehouse'] 		= $this->warehouse_model->get_records();
		$data['purchases'] 		= $this->purchase_model->get_purchase_records();
		$data['total_purchase']	   = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);
		$data['total_paid_amount'] = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);

// 		$log_data = array(
// 				              "user_id"     => $this->session->userdata("user_id"),
// 				              "module"      => "purchase",
// 				              "user_action" => 0,
// 				              "description"	=> "User has viewed list of purchase."
// 				            );

// 		$this->log_data_model->add_record($log_data);
		$this->load->view('purchase/list', $data);
	}
	
//dicount not fetch in this add funtion 05-05-2026
public function add0505()
{
    if(!$this->permission_model->has_permission('add_purchase'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            // echo "<pre>";
            // print_r($this->input->post());
            // echo "</pre>";
            // die;
            
            $this->form_validation->set_rules('purchase_date','Invoice Date','required');        
            $this->form_validation->set_rules('warehouse_id','Warehouse','required');
            $this->form_validation->set_rules('supplier_id','Supplier','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['warehouses']             = $this->warehouse_model->get_records();
                $data['supplier']                 = $this->supplier_model->get_records();
                $data['company_setting']    = $this->company_settings_model->get_company_records();    
                $this->load->view('purchase/add',$data);
            }
            else
            {
                $purchase_date     = date('Y-m-d', strtotime($this->input->post('purchase_date')));
                $reference_no                 = $this->purchase_model->get_lastest_sequence_number();
                $warehouse_id                 = $this->input->post('warehouse_id');
                $supplier_id                     = $this->input->post('supplier_id');

                $supplier                         = $this->supplier_model->get_single_record($supplier_id);
                $supplier_gstin             = $supplier->gstin;

                // Get freight data
                $freightData = json_decode($this->input->post('freight_data'), true);
                if (!$freightData) {
                    $freightData = [
                        'freight_amount' => 0,
                        'freight_taxable_value' => 0,
                        'freight_sub_total' => 0
                    ];
                }

                // $due_days                                     = $this->input->post('due_days');
                // $payment_terms                                = $this->input->post('payment_terms_cond');
                
                // if ($due_days) {
                //     $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($invoice_date)));
                // } else {
                //     $due_date = NULL;
                // }

                $due_days      = $this->input->post('due_days');
$payment_terms = $this->input->post('payment_terms');

if ($due_days) {
    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($purchase_date)));
} else {
    $due_date = NULL;
}
                $total_taxable_value     = $this->input->post('total_taxable_value');
                $total_discount             = $this->input->post('total_discount');
                $total_tax                     = $this->input->post('total_tax');
                $total                             = $this->input->post('total');
                $internal_note             = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note             = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition     = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
                $additional_cost_type   = $this->input->post('additional_cost_type');
                $additional_cost_amount = $freightData['freight_amount'] ?? 0;
                $document                         = $this->input->post("document");
                if (!is_array($document)) {
                    $document = array($document);
                }

                $documents = implode(',', $document);

                $purchase_data = array(
                    "purchase_date"               => $purchase_date,
                    "invoice_no"                  =>$this->input->post("invoice_no"),
                    "reference_no"                => $reference_no,
                    "warehouse_id"                => $warehouse_id,
                    "payment_terms"                   => $payment_terms,
                    "due_date"                        => $due_date,
                    "due_days"                      => $due_days,
                    "supplier_id"                    => $supplier_id,
                    "supplier_gstin"            => $supplier_gstin,
                    "total_taxable_value" => $total_taxable_value,    
                    "total_tax"                    => $total_tax,
                    "total_discount"         => $total_discount,    
                    "total"                         => $total,
                    "internal_note"            => $internal_note,
                    "external_note"         => $external_note,
                    "document"                     => $documents,
                    "terms_and_condition" => $terms_and_condition,
                    "user_id"                     => $this->session->userdata('user_id'),
                    "additional_cost_type" => $additional_cost_type,
                    "additional_cost_amount" => $additional_cost_amount,
                    "freight_amount" => $freightData['freight_amount'],
                    "freight_taxable_value" => $freightData['freight_taxable_value'],
                    "freight_sub_total" => $freightData['freight_sub_total'],
                    "purchase_order_id" => $this->input->post('purchase_order_id'),
                   
                );

                // Begin transaction
                $this->db->trans_begin();    

                if($id = $this->purchase_model->add_purchase_record($purchase_data))
                {
                    $entered_purchase = $this->purchase_model->get_purchase_single_record($id);
                    $supplier                 = $this->supplier_model->get_single_record($entered_purchase->supplier_id);

                    // update the ledgers
                        
                    /****************************** Start Purchase Ledger ***********************************/

                    $purchase_ledger                       = $this->ledger_model->get_single_record(PURCHASE_LEDGER);

                    $updated_purchase_ledger       = array('closing_balance' => ($purchase_ledger->closing_balance+$total));

                    $this->ledger_model->edit_record($updated_purchase_ledger,$purchase_ledger->id);

                    /****************************** End Purchase Ledger ***********************************/

                    /****************************** Start Supplier Ledger ***********************************/

                    $supplier_ledger           = $this->ledger_model->get_single_record($supplier->ledger_id);
                    $updated_supplier_ledger    = array('closing_balance' => ($supplier_ledger->closing_balance+$total));
                    $this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

                    /****************************** End Supplier Ledger ***********************************/

                    /****************************** Add Transaction entry *********************************/

                    $transaction_header = array(
                                                                            "entry_id"              =>  $id,
                                                                            "module"                    =>  PURCHASE_MODULE,
                                                                            "type"                      =>  PURCHASE_TRANSACTION_TYPE,
                                                                            "amount"                    =>  $total,
                                                                            "voucher_date"      =>  $purchase_date,
                                                                            "from_account"      =>  $supplier->ledger_id,
                                                                            "to_account"            =>  PURCHASE_LEDGER,
                                                                            "reference_no"      =>  $reference_no
                                                                        );
                    if($transaction_id = $this->transaction_model->add_record($transaction_header))
                    {
                        $from_transaction_detail = array(
                                                                                                    "transaction_id"   => $transaction_id,
                                                                                                    "voucher_type"     => 'C',
                                                                                                    "ledger_id"            => $supplier->ledger_id,
                                                                                                    "dr_amount"            => $total
                                                                                                );
                        // transaction detail record
                        $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                        $to_transaction_detail = array(
                                                                                                "transaction_id"   => $transaction_id,
                                                                                                "voucher_type"     => 'D',
                                                                                                "ledger_id"            => PURCHASE_LEDGER,
                                                                                                "cr_amount"            => $total
                                                                                            );
                        // transaction detail record
                        $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                    }

                    /**************************************************************************************/

                    $log_data = array(
                                        "user_id"       => $this->session->userdata("user_id"),
                                        "module"            => "purchase",
                                        "entry_id"      => $id,
                                        "user_action"   => 1,
                                        "data"              => json_encode((array)$entered_purchase),
                                        "description" => 'Purchase (Reference No. -'  .$reference_no .') is added successfully.'
                                    );
                    $this->log_data_model->add_record($log_data);

                    $purchase_items           = $this->input->post('purchase_items');
                    $purchase_items_array     = explode("|", $this->input->post('purchase_items'));

                    for ($i=0; $i < sizeof($purchase_items_array) ; $i++) { 
                                
                        $temp_purchase_item = (array)json_decode($purchase_items_array[$i]);
                        $temp_purchase_item['purchase_id']  = $id;

                        if($temp_purchase_item['expiry_date'] == '' || $temp_purchase_item['expiry_date'] == '0000-00-00')
                            unset($temp_purchase_item['expiry_date']);
                        else
                            $temp_purchase_item['expiry_date'] = date('Y-m-d',strtotime($temp_purchase_item['expiry_date']));

                        if($temp_purchase_item['mfg_date'] == '' || $temp_purchase_item['mfg_date'] == '0000-00-00')
                            unset($temp_purchase_item['mfg_date']);
                        else
                            $temp_purchase_item['mfg_date'] = date('Y-m-d',strtotime($temp_purchase_item['mfg_date']));

                        $this->purchase_model->add_purchase_item_record($temp_purchase_item);
                    }

                    // commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success',  'Purchase (Reference No. -'.$reference_no.') is added successfully.');
                    redirect('purchase','refresh');
                }
                else
                {
                    // rollback transaction
                    $this->db->trans_rollback();

                    $this->session->set_flashdata('failure',  'Purchase (Reference No. -'.$reference_no.') is failed to add.');
                    redirect('purchase','refresh');
                }
            }
        }
        else
        {
            $data['warehouses']             = $this->warehouse_model->get_records();
            $data['supplier']                 = $this->supplier_model->get_records();
            $data['company_setting']    = $this->company_settings_model->get_company_records();   
            $data['due_days_options']   = $this->db->where('status', 1)->get('due_days')->result();
            $this->load->view('purchase/add',$data);
        }
    }    
}
public function add()
{
    if(!$this->permission_model->has_permission('add_purchase'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $this->form_validation->set_rules('purchase_date','Invoice Date','required');        
            $this->form_validation->set_rules('warehouse_id','Warehouse','required');
            $this->form_validation->set_rules('supplier_id','Supplier','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['warehouses']             = $this->warehouse_model->get_records();
                $data['supplier']                 = $this->supplier_model->get_records();
                $data['company_setting']    = $this->company_settings_model->get_company_records();    
                $this->load->view('purchase/add',$data);
            }
            else
            {
                $purchase_date     = date('Y-m-d', strtotime($this->input->post('purchase_date')));
                $reference_no                 = $this->purchase_model->get_lastest_sequence_number();
                $warehouse_id                 = $this->input->post('warehouse_id');
                $supplier_id                     = $this->input->post('supplier_id');

                $supplier                         = $this->supplier_model->get_single_record($supplier_id);
                $supplier_gstin             = $supplier->gstin;

                // Get freight data
                $freightData = json_decode($this->input->post('freight_data'), true);
                if (!$freightData) {
                    $freightData = [
                        'freight_amount' => 0,
                        'freight_taxable_value' => 0,
                        'freight_sub_total' => 0
                    ];
                }

                $due_days      = $this->input->post('due_days');
                $payment_terms = $this->input->post('payment_terms');

                if ($due_days) {
                    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($purchase_date)));
                } else {
                    $due_date = NULL;
                }
                
                $total_taxable_value     = $this->input->post('total_taxable_value');
                $total_tax                     = $this->input->post('total_tax');
                $total                             = $this->input->post('total');
                $internal_note             = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note             = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition     = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
                $additional_cost_type   = $this->input->post('additional_cost_type');
                $additional_cost_amount = $freightData['freight_amount'] ?? 0;
                $document                         = $this->input->post("document");
                if (!is_array($document)) {
                    $document = array($document);
                }

                $documents = implode(',', $document);

                // ========== FIX: CALCULATE TOTAL DISCOUNT FROM ITEMS ==========
                $purchase_items_array = explode("|", $this->input->post('purchase_items'));
                $calculated_total_discount = 0;
                
                for ($i = 0; $i < sizeof($purchase_items_array); $i++) {
                    $temp_purchase_item = (array)json_decode($purchase_items_array[$i]);
                    if (isset($temp_purchase_item['discount_amount'])) {
                        $calculated_total_discount += floatval($temp_purchase_item['discount_amount']);
                    }
                }
                
                // Use calculated discount instead of POST value
                $total_discount = $calculated_total_discount;
                // ========== END OF FIX ==========

                $purchase_data = array(
                    "purchase_date"               => $purchase_date,
                    "invoice_no"                  => $this->input->post("invoice_no"),
                    "reference_no"                => $reference_no,
                    "warehouse_id"                => $warehouse_id,
                    "payment_terms"               => $payment_terms,
                    "due_date"                    => $due_date,
                    "due_days"                    => $due_days,
                    "supplier_id"                 => $supplier_id,
                    "supplier_gstin"              => $supplier_gstin,
                    "total_taxable_value"         => $total_taxable_value,    
                    "total_tax"                   => $total_tax,
                    "total_discount"              => $total_discount,  // Now using calculated value
                    "total"                       => $total,
                    "internal_note"               => $internal_note,
                    "external_note"               => $external_note,
                    "document"                    => $documents,
                    "terms_and_condition"         => $terms_and_condition,
                    "user_id"                     => $this->session->userdata('user_id'),
                    "additional_cost_type"        => $additional_cost_type,
                    "additional_cost_amount"      => $additional_cost_amount,
                    "freight_amount"              => $freightData['freight_amount'],
                    "freight_taxable_value"       => $freightData['freight_taxable_value'],
                    "freight_sub_total"           => $freightData['freight_sub_total'],
                    "purchase_order_id"           => $this->input->post('purchase_order_id'),
                );

                // Begin transaction
                $this->db->trans_begin();    

                if($id = $this->purchase_model->add_purchase_record($purchase_data))
                {
                    $entered_purchase = $this->purchase_model->get_purchase_single_record($id);
                    $supplier                 = $this->supplier_model->get_single_record($entered_purchase->supplier_id);

                    // update the ledgers
                        
                    /****************************** Start Purchase Ledger ***********************************/

                    $purchase_ledger                       = $this->ledger_model->get_single_record(PURCHASE_LEDGER);

                    $updated_purchase_ledger       = array('closing_balance' => ($purchase_ledger->closing_balance+$total));

                    $this->ledger_model->edit_record($updated_purchase_ledger,$purchase_ledger->id);

                    /****************************** End Purchase Ledger ***********************************/

                    /****************************** Start Supplier Ledger ***********************************/

                    $supplier_ledger           = $this->ledger_model->get_single_record($supplier->ledger_id);
                    $updated_supplier_ledger    = array('closing_balance' => ($supplier_ledger->closing_balance+$total));
                    $this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

                    /****************************** End Supplier Ledger ***********************************/

                    /****************************** Add Transaction entry *********************************/

                    $transaction_header = array(
                        "entry_id"              => $id,
                        "module"                => PURCHASE_MODULE,
                        "type"                  => PURCHASE_TRANSACTION_TYPE,
                        "amount"                => $total,
                        "voucher_date"          => $purchase_date,
                        "from_account"          => $supplier->ledger_id,
                        "to_account"            => PURCHASE_LEDGER,
                        "reference_no"          => $reference_no
                    );
                    
                    if($transaction_id = $this->transaction_model->add_record($transaction_header))
                    {
                        $from_transaction_detail = array(
                            "transaction_id"   => $transaction_id,
                            "voucher_type"     => 'C',
                            "ledger_id"        => $supplier->ledger_id,
                            "dr_amount"        => $total
                        );
                        $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                        $to_transaction_detail = array(
                            "transaction_id"   => $transaction_id,
                            "voucher_type"     => 'D',
                            "ledger_id"        => PURCHASE_LEDGER,
                            "cr_amount"        => $total
                        );
                        $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                    }

                    /**************************************************************************************/

                    $log_data = array(
                        "user_id"       => $this->session->userdata("user_id"),
                        "module"        => "purchase",
                        "entry_id"      => $id,
                        "user_action"   => 1,
                        "data"          => json_encode((array)$entered_purchase),
                        "description"   => 'Purchase (Reference No. -' . $reference_no . ') is added successfully.'
                    );
                    $this->log_data_model->add_record($log_data);

                    // Save purchase items (reuse the already parsed array)
                    for ($i = 0; $i < sizeof($purchase_items_array); $i++) { 
                                
                        $temp_purchase_item = (array)json_decode($purchase_items_array[$i]);
                        $temp_purchase_item['purchase_id']  = $id;

                        if($temp_purchase_item['expiry_date'] == '' || $temp_purchase_item['expiry_date'] == '0000-00-00')
                            unset($temp_purchase_item['expiry_date']);
                        else
                            $temp_purchase_item['expiry_date'] = date('Y-m-d',strtotime($temp_purchase_item['expiry_date']));

                        if($temp_purchase_item['mfg_date'] == '' || $temp_purchase_item['mfg_date'] == '0000-00-00')
                            unset($temp_purchase_item['mfg_date']);
                        else
                            $temp_purchase_item['mfg_date'] = date('Y-m-d',strtotime($temp_purchase_item['mfg_date']));

                        $this->purchase_model->add_purchase_item_record($temp_purchase_item);
                    }

                    // commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success',  'Purchase (Reference No. -'.$reference_no.') is added successfully.');
                    redirect('purchase','refresh');
                }
                else
                {
                    // rollback transaction
                    $this->db->trans_rollback();

                    $this->session->set_flashdata('failure',  'Purchase (Reference No. -'.$reference_no.') is failed to add.');
                    redirect('purchase','refresh');
                }
            }
        }
        else
        {
            $data['warehouses']             = $this->warehouse_model->get_records();
            $data['supplier']               = $this->supplier_model->get_records();
            $data['company_setting']        = $this->company_settings_model->get_company_records();   
            $data['due_days_options']       = $this->db->where('status', 1)->get('due_days')->result();
            $this->load->view('purchase/add', $data);
        }
    }    
}

/*04-05-2026
public function add_from_purchase_order($id = null)
 {
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {

      $id = $this->input->post('id');
      
      if($id != null)
      {

      //  $data['id'] = $id;

        $data['purchase_order'] 			= $this->purchase_order_model->get_purchase_order_single_record($id);
        if($data['purchase_order'] != null)
        {
          $data['purchase_order_items'] 	= $this->purchase_order_model->get_purchase_order_item_records($id);
          $data['warehouses'] 			= $this->warehouse_model->get_records();
          $data['warehouse_detail']	= $this->warehouse_model->get_single_record($data['purchase_order']->warehouse_id);
          $data['supplier'] 				= $this->supplier_model->get_records();
          $data['supplier_detail']	= $this->supplier_model->get_single_record($data['purchase_order']->supplier_id);
          $data['discounts']				= $this->discount_model->get_records();
          $data['company_setting']	= $this->company_settings_model->get_company_records();
           $data['due_days_options']   = $this->db->where('status', 1)->get('due_days')->result();
           $data['selected_due_days'] = $data['purchase_order']->due_days ?? '';
$data['payment_terms']    = $data['purchase_order']->payment_terms ?? '';
$data['due_date']         = $data['purchase_order']->due_date ?? '';
          $this->load->view('purchase/add_from_purchase_order',$data);
        }
        else
        {
          $this->session->set_flashdata('failure',  'You have tried to access broken URL.');
          redirect('purchase_order','refresh');
        }

      
      }
      else
      {
        $this->session->set_flashdata('failure',  'You have tried to access broken URL.');
        redirect('purchase_order','refresh');
      }
    }
    else
    {
      $data['purchase_order']          = $this->purchase_order_model->get_purchase_order_single_record($id);
        
      $response                        = array();
      $response['code']                = RESPONSE_SUCCESS; 
        $data['due_days_options']   = $this->db->where('status', 1)->get('due_days')->result();
      $response['generate_purchase_modal_body'] = $this->load->view('purchase_order/ajax/generate_purchase_modal_body',$data,TRUE);
      echo json_encode($response);
    }
		

	}
*/
public function add_from_purchase_order($id = null)
{
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
        $id = $this->input->post('id');
        
        if($id != null)
        {
            $data['purchase_order'] = $this->purchase_order_model->get_purchase_order_single_record($id);
            if($data['purchase_order'] != null)
            {
                $data['purchase_order_items'] = $this->purchase_order_model->get_purchase_order_item_records($id);
                $data['warehouses'] = $this->warehouse_model->get_records();
                $data['warehouse_detail'] = $this->warehouse_model->get_single_record($data['purchase_order']->warehouse_id);
                $data['supplier'] = $this->supplier_model->get_records();
                $data['supplier_detail'] = $this->supplier_model->get_single_record($data['purchase_order']->supplier_id);
                $data['discounts'] = $this->discount_model->get_records();
                $data['company_setting'] = $this->company_settings_model->get_company_records();
                $data['due_days_options'] = $this->db->where('status', 1)->get('due_days')->result();
                $data['selected_due_days'] = $data['purchase_order']->due_days ?? '';
                $data['payment_terms'] = $data['purchase_order']->payment_terms ?? '';
                $data['due_date'] = $data['purchase_order']->due_date ?? '';
                
                $this->load->view('purchase/add_from_purchase_order', $data);
            }
            else
            {
                $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                redirect('purchase_order', 'refresh');
            }
        }
        else
        {
            $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
            redirect('purchase_order', 'refresh');
        }
    }
    else
    {
        $data['purchase_order'] = $this->purchase_order_model->get_purchase_order_single_record($id);
        $data['due_days_options'] = $this->db->where('status', 1)->get('due_days')->result();
        $response = array();
        $response['code'] = RESPONSE_SUCCESS; 
        $response['generate_purchase_modal_body'] = $this->load->view('purchase_order/ajax/generate_purchase_modal_body', $data, TRUE);
        echo json_encode($response);
    }
}
//edit funtion in this discount not fetching 05-05-05
	public function edit0505($id = null)
	{
		if(!$this->permission_model->has_permission('edit_purchase'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
			   
				$purchase_id = $this->input->post('id');
				$old_purchase = $this->purchase_model->get_purchase_single_record($purchase_id);

				$this->form_validation->set_rules('purchase_date','Invoice Date','required');		
				$this->form_validation->set_rules('supplier_id','supplier','required');
				// $this->form_validation->set_rules('invoice_no','Invoice No','required');

				if($this->form_validation->run()==FALSE)
				{
					$data['supplier'] 			= $this->supplier_model->get_records();
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$this->load->view('purchase/add',$data);
				}
				else
				{
					$purchase_date 				= date('Y-m-d', strtotime($this->input->post('purchase_date')));
					$supplier_id 					= $this->input->post('supplier_id');
					
					$supplier 						= $this->supplier_model->get_single_record($supplier_id);
					$supplier_gstin 			= $supplier->gstin;

					$warehouse_id 				= $this->input->post('warehouse_id');
					$reference_no 				= $this->input->post('reference_no');
					
    				// Get freight data
                    $freightData = json_decode($this->input->post('freight_data'), true);
                    if (!$freightData) {
                        $freightData = [
                            'freight_amount' => 0,
                            'freight_taxable_value' => 0,
                            'freight_sub_total' => 0
                        ];
                    }
					
					$invoice_no 					= $this->input->post('invoice_no');
					$total_taxable_value	= $this->input->post('total_taxable_value');
					$total_discount 			=	$this->input->post('total_discount');
					$total_tax 						= $this->input->post('total_tax');
					$total 								= $this->input->post('total');
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					//$bank_detail 				= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

				// 	$due_date 						= ($this->input->post('due_date') != NULL && $this->input->post('due_date') != '') ? date('Y-m-d',strtotime($this->input->post('due_date'))) : NULL;
					$due_days = $this->input->post('due_days');
                    $payment_terms = $this->input->post('payment_terms');
                    $due_date = $this->input->post('due_date') ? date('Y-m-d', strtotime($this->input->post('due_date'))) : NULL;

					$document 						= $this->input->post("document");

          if (!is_array($document)) {
            // Convert $document to an array with a single element
            $document = array($document);
          }

          $documents = implode(',', $document);
                $additional_cost_type   = $this->input->post('additional_cost_type');
                $additional_cost_amount = $freightData['freight_amount'] ?? 0;
					

					$purchase_data = array(
										"purchase_date"					=> $purchase_date,
									 "due_days" => $due_days,
                                                        "payment_terms" => $payment_terms,
                                                        "due_date" => $due_date, // Add this line
										"invoice_no"						=> $invoice_no,
										"supplier_id"						=> $supplier_id,
										"supplier_gstin"				=> $supplier_gstin,
										"warehouse_id"					=> $warehouse_id,
										"total_taxable_value" 	=> $total_taxable_value,	
										"total_tax"							=> $total_tax,
										"total_discount" 				=> $total_discount,	
										"total" 								=> $total,
										"internal_note"					=> $internal_note,
										"external_note" 				=> $external_note,
										"terms_and_condition"		=> $terms_and_condition,
										"document" 							=> $documents,
										"user_id" 							=> $this->session->userdata('user_id'),
										"additional_cost_type" => $additional_cost_type,
                                        "additional_cost_amount" => $additional_cost_amount,
                                        "freight_amount" => $freightData['freight_amount'],
                                        "freight_taxable_value" => $freightData['freight_taxable_value'],
                                        "freight_sub_total" => $freightData['freight_sub_total'],
                                        
                                        
                                        
									);

					// being transaction
					$this->db->trans_begin();	

					if($this->purchase_model->edit_purchase_record($purchase_data,$purchase_id))
					{
						$entered_purchase = $this->purchase_model->get_purchase_single_record($purchase_id);
						$supplier 				= $this->supplier_model->get_single_record($entered_purchase->supplier_id);

						// update the ledgers
						
						/****************************** Start Sale Ledger ***********************************/

						$purchase_ledger 						= $this->ledger_model->get_single_record(PURCHASE_LEDGER);
						$updated_purchase_ledger 		= array('closing_balance' => ($purchase_ledger->closing_balance  - $old_purchase->total + $total));
						$this->ledger_model->edit_record($updated_purchase_ledger,$purchase_ledger->id);

						/****************************** End Sale Ledger ***********************************/

						/****************************** Start Customer Ledger ***********************************/

						$supplier_ledger 					= $this->ledger_model->get_single_record($supplier->ledger_id);
						$updated_supplier_ledger  = array('closing_balance' => ($supplier_ledger->closing_balance + $total - $old_purchase->total));
						$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

						/****************************** End Customer Ledger ***********************************/

						/****************************** Add Transaction entry *********************************/

						// Remove old transaction entry

						$this->transaction_model->delete_record_by_entry_id($entered_purchase->id,PURCHASE_MODULE,PURCHASE_TRANSACTION_TYPE);

						$transaction_header = array(
																				"entry_id"				=>  $purchase_id,
																				"module"					=>  PURCHASE_MODULE,
																				"type"						=>	PURCHASE_TRANSACTION_TYPE,
																				"amount"					=>	$total,
																				"voucher_date"		=>	$purchase_date,
																				"from_account"		=>	$supplier->ledger_id,
																				"to_account"			=>	PURCHASE_LEDGER,
																				"reference_no"		=>	$entered_purchase->reference_no
																			);
						if($transaction_id = $this->transaction_model->add_record($transaction_header))
						{
							$from_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> $supplier->ledger_id,
																								"dr_amount"				=> $total
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

							$to_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> PURCHASE_LEDGER,
																							"cr_amount"				=> $total
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
						}

						/**************************************************************************************/

						$log_data = array(
														"user_id" 		=> $this->session->userdata("user_id"),
														"module"			=> "purchase",
														"user_action"	=> 2,
														"b_data"			=> json_encode((array)$old_purchase),
														"data"				=> json_encode((array)$entered_purchase),
														"description" => 'Purchase (Reference No. -'  .$reference_no .') is updated successfully.'
													);
						$this->log_data_model->add_record($log_data);
						$this->purchase_model->remove_purchase_item_records($purchase_id);

						$purchase_items 				= $this->input->post('purchase_items');
						$purchase_items_array 	= explode("|", $this->input->post('purchase_items'));

						for ($i=0; $i < sizeof($purchase_items_array) ; $i++) { 
									
							$temp_purchase_item = (array)json_decode($purchase_items_array[$i]);
							$temp_purchase_item['purchase_id']  = $purchase_id;

							if($temp_purchase_item['expiry_date'] == '' || $temp_purchase_item['expiry_date'] == '0000-00-00')
								unset($temp_purchase_item['expiry_date']);
							else
								$temp_purchase_item['expiry_date'] = date('Y-m-d',strtotime($temp_purchase_item['expiry_date']));

							if($temp_purchase_item['mfg_date'] == '' || $temp_purchase_item['mfg_date'] == '0000-00-00')
								unset($temp_purchase_item['mfg_date']);
							else
								$temp_purchase_item['mfg_date'] = date('Y-m-d',strtotime($temp_purchase_item['mfg_date']));

                            // Add product remark to item data
                            if(isset($temp_purchase_order_item['product_remark'])) {
                                $temp_purchase_order_item['product_remark'] = $temp_purchase_order_item['product_remark'];
                            }
                            
							$this->purchase_model->add_purchase_item_record($temp_purchase_item);
						}

						// commit transaction
						$this->db->trans_commit();

						$this->session->set_flashdata('success',  'Purchase (Reference No. -'  .$reference_no .') is updated successfully.');
						redirect('purchase','refresh');
					}
					else
					{
						// rollback transaction
						$this->db->trans_rollback();
						$this->session->set_flashdata('failure',  'Purchase (Reference No. -'  .$reference_no .') is failed to update.');
						redirect('purchase','refresh');
					}
				}
			}
			else
			{
				$id = base64_decode($id);

				if($id != null)
				{
					$data['purchase'] 					= $this->purchase_model->get_purchase_single_record($id);

					if($data['purchase'] != null)
					{
						$data['purchase_items'] 		= $this->purchase_model->get_purchase_item_records($id);
						$data['supplier'] 					= $this->supplier_model->get_records();
						$data['supplier_detail']		= $this->supplier_model->get_single_record($data['purchase']->supplier_id);
						$data['discounts']					= $this->discount_model->get_records();
						$data['warehouses'] 				= $this->warehouse_model->get_records();
						$data['company_setting']		= $this->company_settings_model->get_company_records();	
						$data['due_days_options']   = $this->db->where('status', 1)->get('due_days')->result();

						$this->load->view('purchase/edit',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('purchase','refresh');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('purchase','refresh');
				}
			}
		}
		
	}
	public function edit($id = null)
{
    if(!$this->permission_model->has_permission('edit_purchase'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $purchase_id = $this->input->post('id');
            $old_purchase = $this->purchase_model->get_purchase_single_record($purchase_id);

            $this->form_validation->set_rules('purchase_date','Invoice Date','required');		
            $this->form_validation->set_rules('supplier_id','supplier','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['supplier']               = $this->supplier_model->get_records();
                $data['warehouses']             = $this->warehouse_model->get_records();
                $data['company_setting']        = $this->company_settings_model->get_company_records();	
                $this->load->view('purchase/add', $data);
            }
            else
            {
                $purchase_date              = date('Y-m-d', strtotime($this->input->post('purchase_date')));
                $supplier_id                = $this->input->post('supplier_id');
                
                $supplier                   = $this->supplier_model->get_single_record($supplier_id);
                $supplier_gstin             = $supplier->gstin;

                $warehouse_id               = $this->input->post('warehouse_id');
                $reference_no               = $this->input->post('reference_no');
                
                // Get freight data
                $freightData = json_decode($this->input->post('freight_data'), true);
                if (!$freightData) {
                    $freightData = [
                        'freight_amount' => 0,
                        'freight_taxable_value' => 0,
                        'freight_sub_total' => 0
                    ];
                }
                
                $invoice_no                 = $this->input->post('invoice_no');
                $total_taxable_value        = $this->input->post('total_taxable_value');
                $total_tax                  = $this->input->post('total_tax');
                $total                      = $this->input->post('total');
                $internal_note              = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note              = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition        = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

                $due_days = $this->input->post('due_days');
                $payment_terms = $this->input->post('payment_terms');
                $due_date = $this->input->post('due_date') ? date('Y-m-d', strtotime($this->input->post('due_date'))) : NULL;

                $document = $this->input->post("document");
                if (!is_array($document)) {
                    $document = array($document);
                }
                
                $documents = implode(',', $document);
                $additional_cost_type = $this->input->post('additional_cost_type');
                $additional_cost_amount = $freightData['freight_amount'] ?? 0;

                // ========== FIX: CALCULATE TOTAL DISCOUNT FROM ITEMS ==========
                $purchase_items_array = explode("|", $this->input->post('purchase_items'));
                $calculated_total_discount = 0;
                
                for ($i = 0; $i < sizeof($purchase_items_array); $i++) {
                    $temp_purchase_item = (array)json_decode($purchase_items_array[$i]);
                    if (isset($temp_purchase_item['discount_amount'])) {
                        $calculated_total_discount += floatval($temp_purchase_item['discount_amount']);
                    }
                }
                
                // Use calculated discount instead of POST value
                $total_discount = $calculated_total_discount;
                // ========== END OF FIX ==========

                $purchase_data = array(
                    "purchase_date"               => $purchase_date,
                    "due_days"                    => $due_days,
                    "payment_terms"               => $payment_terms,
                    "due_date"                    => $due_date,
                    "invoice_no"                  => $invoice_no,
                    "supplier_id"                 => $supplier_id,
                    "supplier_gstin"              => $supplier_gstin,
                    "warehouse_id"                => $warehouse_id,
                    "total_taxable_value"         => $total_taxable_value,	
                    "total_tax"                   => $total_tax,
                    "total_discount"              => $total_discount,  // Now using calculated value
                    "total"                       => $total,
                    "internal_note"               => $internal_note,
                    "external_note"               => $external_note,
                    "terms_and_condition"         => $terms_and_condition,
                    "document"                    => $documents,
                    "user_id"                     => $this->session->userdata('user_id'),
                    "additional_cost_type"        => $additional_cost_type,
                    "additional_cost_amount"      => $additional_cost_amount,
                    "freight_amount"              => $freightData['freight_amount'],
                    "freight_taxable_value"       => $freightData['freight_taxable_value'],
                    "freight_sub_total"           => $freightData['freight_sub_total'],
                );

                // being transaction
                $this->db->trans_begin();	

                if($this->purchase_model->edit_purchase_record($purchase_data, $purchase_id))
                {
                    $entered_purchase = $this->purchase_model->get_purchase_single_record($purchase_id);
                    $supplier = $this->supplier_model->get_single_record($entered_purchase->supplier_id);

                    // update the ledgers
                    
                    /****************************** Start Sale Ledger ***********************************/
                    $purchase_ledger = $this->ledger_model->get_single_record(PURCHASE_LEDGER);
                    $updated_purchase_ledger = array('closing_balance' => ($purchase_ledger->closing_balance - $old_purchase->total + $total));
                    $this->ledger_model->edit_record($updated_purchase_ledger, $purchase_ledger->id);
                    /****************************** End Sale Ledger ***********************************/

                    /****************************** Start Customer Ledger ***********************************/
                    $supplier_ledger = $this->ledger_model->get_single_record($supplier->ledger_id);
                    $updated_supplier_ledger = array('closing_balance' => ($supplier_ledger->closing_balance + $total - $old_purchase->total));
                    $this->ledger_model->edit_record($updated_supplier_ledger, $supplier->ledger_id);
                    /****************************** End Customer Ledger ***********************************/

                    /****************************** Add Transaction entry *********************************/
                    $this->transaction_model->delete_record_by_entry_id($entered_purchase->id, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);

                    $transaction_header = array(
                        "entry_id"          => $purchase_id,
                        "module"            => PURCHASE_MODULE,
                        "type"              => PURCHASE_TRANSACTION_TYPE,
                        "amount"            => $total,
                        "voucher_date"      => $purchase_date,
                        "from_account"      => $supplier->ledger_id,
                        "to_account"        => PURCHASE_LEDGER,
                        "reference_no"      => $entered_purchase->reference_no
                    );
                    
                    if($transaction_id = $this->transaction_model->add_record($transaction_header))
                    {
                        $from_transaction_detail = array(
                            "transaction_id" => $transaction_id,
                            "voucher_type"   => 'C',
                            "ledger_id"      => $supplier->ledger_id,
                            "dr_amount"      => $total
                        );
                        $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                        $to_transaction_detail = array(
                            "transaction_id" => $transaction_id,
                            "voucher_type"   => 'D',
                            "ledger_id"      => PURCHASE_LEDGER,
                            "cr_amount"      => $total
                        );
                        $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                    }
                    /**************************************************************************************/

                    $log_data = array(
                        "user_id"       => $this->session->userdata("user_id"),
                        "module"        => "purchase",
                        "user_action"   => 2,
                        "b_data"        => json_encode((array)$old_purchase),
                        "data"          => json_encode((array)$entered_purchase),
                        "description"   => 'Purchase (Reference No. -' . $reference_no . ') is updated successfully.'
                    );
                    $this->log_data_model->add_record($log_data);
                    
                    $this->purchase_model->remove_purchase_item_records($purchase_id);

                    // Save purchase items (reuse the already parsed array)
                    for ($i = 0; $i < sizeof($purchase_items_array); $i++) { 
                                    
                        $temp_purchase_item = (array)json_decode($purchase_items_array[$i]);
                        $temp_purchase_item['purchase_id'] = $purchase_id;

                        if($temp_purchase_item['expiry_date'] == '' || $temp_purchase_item['expiry_date'] == '0000-00-00')
                            unset($temp_purchase_item['expiry_date']);
                        else
                            $temp_purchase_item['expiry_date'] = date('Y-m-d', strtotime($temp_purchase_item['expiry_date']));

                        if($temp_purchase_item['mfg_date'] == '' || $temp_purchase_item['mfg_date'] == '0000-00-00')
                            unset($temp_purchase_item['mfg_date']);
                        else
                            $temp_purchase_item['mfg_date'] = date('Y-m-d', strtotime($temp_purchase_item['mfg_date']));

                        if(isset($temp_purchase_item['product_remark'])) {
                            $temp_purchase_item['product_remark'] = $temp_purchase_item['product_remark'];
                        }
                        
                        $this->purchase_model->add_purchase_item_record($temp_purchase_item);
                    }

                    // commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success', 'Purchase (Reference No. -' . $reference_no . ') is updated successfully.');
                    redirect('purchase', 'refresh');
                }
                else
                {
                    // rollback transaction
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('failure', 'Purchase (Reference No. -' . $reference_no . ') is failed to update.');
                    redirect('purchase', 'refresh');
                }
            }
        }
        else
        {
            $id = base64_decode($id);

            if($id != null)
            {
                $data['purchase'] = $this->purchase_model->get_purchase_single_record($id);

                if($data['purchase'] != null)
                {
                    $data['purchase_items']     = $this->purchase_model->get_purchase_item_records($id);
                    $data['supplier']           = $this->supplier_model->get_records();
                    $data['supplier_detail']    = $this->supplier_model->get_single_record($data['purchase']->supplier_id);
                    $data['discounts']          = $this->discount_model->get_records();
                    $data['warehouses']         = $this->warehouse_model->get_records();
                    $data['company_setting']    = $this->company_settings_model->get_company_records();	
                    $data['due_days_options']   = $this->db->where('status', 1)->get('due_days')->result();

                    $this->load->view('purchase/edit', $data);
                }
                else
                {
                    $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                    redirect('purchase', 'refresh');
                }
            }
            else
            {
                $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                redirect('purchase', 'refresh');
            }
        }
    }
}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_purchase'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 					= $this->input->post('id');
			$purchase 		= $this->purchase_model->get_purchase_single_record($id);
			$paid_amount 	= $this->transaction_model->get_total_transaction_amount($id,PURCHASE_MODULE,PAYMENT_TRANSACTION_TYPE);

			if($paid_amount == 0 || $paid_amount == '')
			{
				$data = array('delete_status' => 1);
				if($this->purchase_model->edit_purchase_record($data,$id))
				{
					$supplier 		= $this->supplier_model->get_single_record($purchase->supplier_id);

					// update the ledgers
					/****************************** Start Sale Ledger ***********************************/

					$purchase_ledger 						= $this->ledger_model->get_single_record(PURCHASE_LEDGER);
					$updated_purchase_ledger 		= array('closing_balance' => ($purchase_ledger->closing_balance - $purchase->total));
					$this->ledger_model->edit_record($updated_purchase_ledger,$purchase_ledger->id);

					/****************************** End Sale Ledger ***********************************/

					/****************************** Start Customer Ledger ***********************************/

					$supplier_ledger 					= $this->ledger_model->get_single_record($supplier->ledger_id);
					$updated_supplier_ledger  = array('closing_balance' => ($supplier_ledger->closing_balance - $purchase->total));
					$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

					/****************************** End Customer Ledger ***********************************/

					// Remove old transaction entry
					$this->transaction_model->delete_record_by_entry_id($id,PURCHASE_MODULE);


					$this->session->set_flashdata('success', 'Purchase (Reference No. -'.$purchase->reference_no.') is deleted successfully.');
					redirect('purchase','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', 'Purchase (Reference No. -'.$purchase->reference_no.') is failed to delete.');
					redirect('purchase','refresh');
				}	
			}
			else
			{
				$this->session->set_flashdata('failure', 'Please delete the transaction entry to delete this Sale:'.$purchase->reference_no);
				redirect('purchase','refresh');
			}

			
			
		}
	
	}

	function restore()
	{
			$id 					= $this->input->post('id');
			// $id 					= base64_decode($id);
			$purchase 		= $this->purchase_model->get_purchase_single_record($id);
			$paid_amount 	= $this->transaction_model->get_total_transaction_amount($id,PURCHASE_MODULE,PAYMENT_TRANSACTION_TYPE);

			$data = array('delete_status' => 0);
			if($this->purchase_model->edit_purchase_record($data,$id))
			{
				$supplier 		= $this->supplier_model->get_single_record($purchase->supplier_id);

				// update the ledgers
				/****************************** Start Sale Ledger ***********************************/

				$purchase_ledger 						= $this->ledger_model->get_single_record(PURCHASE_LEDGER);
				$updated_purchase_ledger 		= array('closing_balance' => ($purchase_ledger->closing_balance + $purchase->total));
				$this->ledger_model->edit_record($updated_purchase_ledger,$purchase_ledger->id);

				/****************************** End Sale Ledger ***********************************/

				/****************************** Start Customer Ledger ***********************************/

				$supplier_ledger 					= $this->ledger_model->get_single_record($supplier->ledger_id);
				$updated_supplier_ledger  = array('closing_balance' => ($supplier_ledger->closing_balance + $purchase->total));
				$this->ledger_model->edit_record($updated_supplier_ledger,$supplier->ledger_id);

				/****************************** End Customer Ledger ***********************************/

				/****************************** Add Transaction entry *********************************/

				$transaction_header = array(
					"entry_id"				=>  $id,
					"module"					=>  PURCHASE_MODULE,
					"type"						=>	PURCHASE_TRANSACTION_TYPE,
					"amount"					=>	$purchase->total,
					"voucher_date"		=>	$purchase->purchase_date,
					"from_account"		=>	$supplier->ledger_id,
					"to_account"			=>	PURCHASE_LEDGER,
					"reference_no"		=>	$purchase->reference_no
				);
				if($transaction_id = $this->transaction_model->add_record($transaction_header))
				{
				$from_transaction_detail = array(
													"transaction_id" 	=> $transaction_id,
													"voucher_type"		=> 'C',
													"ledger_id"				=> $supplier->ledger_id,
													"dr_amount"				=> $purchase->total
												);
				// transaction detail record
				$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

				$to_transaction_detail = array(
												"transaction_id" 	=> $transaction_id,
												"voucher_type"		=> 'D',
												"ledger_id"				=> PURCHASE_LEDGER,
												"cr_amount"				=> $purchase->total
											);
				// transaction detail record
				$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
				}

				/**************************************************************************************/


				$this->session->set_flashdata('success', 'Purchase (Reference No. -'.$purchase->reference_no.') is restored successfully.');
				redirect('purchase/view/'.base64_encode($id).'','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Purchase (Reference No. -'.$purchase->reference_no.') is failed to restore.');
				redirect('purchase','refresh');
			}	
		
	}

	public function view($id = null)
	{
		if (!$this->input->is_ajax_request()) 
		{
			if($id != null)
			{

				$id = base64_decode($id);

				$data['purchase'] 			= $this->purchase_model->get_purchase_single_record($id);

				if($data['purchase'] != null)
				{
					$data['purchase_items'] 	= $this->purchase_model->get_purchase_item_records($id);
					$data['warehouses'] 		= $this->warehouse_model->get_records();
					$data['warehouse_detail']= $this->warehouse_model->get_single_record($data['purchase']->warehouse_id);
					$data['supplier'] 		= $this->supplier_model->get_records();
					$data['supplier_detail']= $this->supplier_model->get_single_record($data['purchase']->supplier_id);
					$data['discounts']		= $this->discount_model->get_records();
					$data['company_setting']= $this->company_settings_model->get_company_records();
                    $warehouse_id = $data['purchase']->warehouse_id;
                    $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);
					$this->load->view('purchase/view',$data);
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('purchase','refresh');
				}

			
			}
			else
			{
				$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
				redirect('purchase','refresh');
			}
		}
		else
		{
			$response = array();

			$purchase_view_data['purchase'] 				= $this->purchase_model->get_purchase_single_record($id);
			$purchase_view_data['supplier_detail'] 	= $this->customer_model->get_single_record($purchase_view_data['purchase']->supplier_id);	
			$purchase_view_data['paid_amount'] 			= $this->transaction_model->get_total_transaction_amount($id,PURCHASE_MODULE,PAYMENT_TRANSACTION_TYPE);
			
			$response['purchase_view']	 						= $this->load->view('purchase/ajax/view',$purchase_view_data,TRUE);
			$response['due_amount']									= $purchase_view_data['purchase']->total - $purchase_view_data['paid_amount'];


			/*************************** Start Add Payment View ******************************/
			
			$add_payment_data['from_account'] = $this->ledger_model->get_records(array(BANK_ACCOUNT_GROUP,CASH_GROUP));	
			
			$response['to_account']						= $this->supplier_model->get_single_record($purchase_view_data['purchase']->supplier_id)->ledger_id;
			$response['add_transaction']			= $this->load->view('purchase/ajax/add_payment',$add_payment_data,TRUE);

			/*************************** End Add Payment View ******************************/

			/*************************** Start Previous Transaction ******************************/

			$previous_transaction['transactions']  = $this->transaction_model->get_records_entry_id_and_module_wise($id,PURCHASE_MODULE,PAYMENT_TRANSACTION_TYPE);
			$response['previous_transaction_view'] = $this->load->view('purchase/ajax/transaction',$previous_transaction,TRUE);

			/*************************** End Previous Transaction ******************************/

			echo json_encode($response);
		}
	}

	public function view_delivery()
	{
		if ($this->input->is_ajax_request()) 
		{
			$purchase_id 																= $this->input->post('purchase_id');
			$delivery_detail_data['purchase_deliveries']= $this->purchase_delivery_model->get_delivery_records($purchase_id);
			$delivery_detail_data['purchase_items']			= $this->purchase_model->get_purchase_item_records($purchase_id);
			$delivery_detail_data['users']							= $this->ion_auth_model->users()->result();

			$response 														= array();
			$response['add_delivery_detail'] 			= $this->load->view('purchase/ajax/add_delivery_detail',$delivery_detail_data,TRUE);
			$response['purchase_delivery_detail'] = $this->load->view('purchase/ajax/view_purchase_delivery_detail',$delivery_detail_data,TRUE);
			$response['delivery_transaction'] 		= $this->load->view('purchase/ajax/delivery_transaction',$delivery_detail_data,TRUE);
			$response['is_all_products_delivered']= $this->is_all_purchase_products_delivered($purchase_id);

			echo json_encode($response);  
		}
	}

	public function add_purchase_delivery($purchase_id = null)
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$purchase_id 		= $this->input->post('purchase_id');
			$delivery_date 	= $this->input->post('delivery_date');
			$received_by 		= $this->input->post('received_by');

			$delivery_items = $this->input->post('delivery_items');



			$delivery_data = array(
														"purchase_id"			=> $purchase_id,
														"delivery_date" 	=> date('Y-m-d', strtotime($delivery_date)),
														"received_by"			=> $received_by,
														"user_id" 				=> $this->session->userdata('user_id')
													);

			// being transaction
			$this->db->trans_begin();	

			if($purchase_delivery_id = $this->purchase_delivery_model->add_delivery_record($delivery_data))
			{
				$delivery_items_array 	= explode("|", $delivery_items);

				// echo '<pre>';
				// print_r($delivery_items_array);
				// exit;

				for ($i=0; $i < sizeof($delivery_items_array) ; $i++) 
				{			
					$temp_delivery_item = (array)json_decode($delivery_items_array[$i]);

					if(sizeof($temp_delivery_item) > 0 && $temp_delivery_item['quantity'] > 0)
					{
						/******************************** ADD PRODUCT DELIVERY ******************************************/	
						
						$temp_delivery_item['purchase_delivery_id']  = $purchase_delivery_id;
						$expiry_date = $temp_delivery_item['expiry_date'];
						$mfg_date = $temp_delivery_item['mfg_date'];

						if($temp_delivery_item['expiry_date'] == '' || $temp_delivery_item['expiry_date'] == '0000-00-00')
							unset($temp_delivery_item['expiry_date']);
						else
							$temp_delivery_item['expiry_date'] = date('Y-m-d',strtotime($temp_delivery_item['expiry_date']));

						if($temp_delivery_item['mfg_date'] == '' || $temp_delivery_item['mfg_date'] == '0000-00-00')
							unset($temp_delivery_item['mfg_date']);
						else
							$temp_delivery_item['mfg_date'] = date('Y-m-d',strtotime($temp_delivery_item['mfg_date']));


						$this->purchase_delivery_model->add_delivery_item_record($temp_delivery_item);

						/******************************** ADD PRODUCT TO WAREHOUSE **************************************/	
						

						$product_id 			= $temp_delivery_item['product_id'];
						$quantity 				= $temp_delivery_item['quantity'];

						$product 					= $this->product_model->get_single_record($product_id);
						$purchase_delivery= $this->purchase_delivery_model->get_delivery_single_record($purchase_delivery_id);
						$purchase 				=	$this->purchase_model->get_purchase_single_record($purchase_delivery->purchase_id);
						$purchase_item 		= $this->purchase_model->get_single_purchase_item_record($purchase->id,$product_id);
						$warehouse_id 		= $purchase->warehouse_id;					
						$cost 						= $purchase_item->cost;

						if($purchase_item->price == 0)
							$price 						= ($cost + ($cost*$product->markup)/100);
						else
							$price 						= $purchase_item->price;

            	$warehouse_product = $this->warehouse_products_model->get_single_product_by_warehouse_id_product_id_batch_no($warehouse_id,$product_id,$temp_delivery_item['batch_no']);

              if($warehouse_product != null  && ($warehouse_product->batch_no == $temp_delivery_item['batch_no']))
              {
                
                $new_quantity = $warehouse_product->quantity + $quantity;
                $warehouse_product_data = array(
                                                "warehouse_id" 	=> $warehouse_id,
                                                "product_id" 		=> $temp_delivery_item['product_id'],
                                                "quantity" 			=> $new_quantity,
                                                "batch_no" 			=> $temp_delivery_item['batch_no'],
                                                "cost"					=> $temp_delivery_item['cost'],
                                                "price"					=> $temp_delivery_item['price'],	
																								"selling_price"	=> $temp_delivery_item['selling_price'],
																								"mfg_date"			=> $expiry_date,	
																								"expiry_date"		=> $mfg_date
																							
                                                
                                              );

								if($expiry_date == '' || $expiry_date == '0000-00-00')
									unset($warehouse_product_data['expiry_date']);
								else
									$warehouse_product_data['expiry_date'] = date('Y-m-d',strtotime($warehouse_product_data['expiry_date']));

								if($mfg_date == '' || $mfg_date == '0000-00-00')
									unset($warehouse_product_data['mfg_date']);
								else
									$warehouse_product_data['mfg_date'] = date('Y-m-d',strtotime($warehouse_product_data['mfg_date']));


                $this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);

              }
              else
              {
                $warehouse_product_data = array(
                                              "warehouse_id" 	=> $warehouse_id,
                                              "product_id"		=> $product_id,
                                            
                                              "quantity" 			=> $temp_delivery_item['quantity'],
                                              "batch_no" 			=> $temp_delivery_item['batch_no'],
                                              "cost"					=> $temp_delivery_item['cost'],
                                              "price"					=> $temp_delivery_item['price']	,
																							"selling_price"	=> $temp_delivery_item['selling_price'],
																							"mfg_date"			=> $expiry_date,	
																							"expiry_date"		=> $mfg_date
																							
                                              
                                              );

								if($expiry_date == '' || $expiry_date == '0000-00-00')
									unset($warehouse_product_data['expiry_date']);
								else
									$warehouse_product_data['expiry_date'] = date('Y-m-d',strtotime($warehouse_product_data['expiry_date']));

								if($mfg_date == '' || $mfg_date == '0000-00-00')
									unset($warehouse_product_data['mfg_date']);
								else
									$warehouse_product_data['mfg_date'] = date('Y-m-d',strtotime($warehouse_product_data['mfg_date']));

                $this->warehouse_products_model->add_record($warehouse_product_data);
              }



						// Check for product with zero value
						// $warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost($warehouse_id,$product_id, 0.00);

						// if($warehouse_product != null)
						// {

						// 	//echo 'in';
						// 	// update the existing warehouse product
						// 	$warehouse_product_data = array(
						// 																"warehouse_id" 	=> $warehouse_id,
						// 																"product_id"		=> $product_id,
						// 																"cost"					=> $cost,
						// 																"price"					=> $price,
						// 																"quantity"			=> $quantity
						// 															);


            //                               //print_r($warehouse_product_data);
                                      
						// 	$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);
						// }
						// else
						// {
						// 	// echo 'out';
						// 	// exit;
						// 	// if product with zero cost doesn't exist then check product with given cost.
						// 	$warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id, $cost, $price);

						// 	if($warehouse_product != null)
						// 	{
						// 		// update the product quantity
						// 		$new_quantity = $quantity +  $warehouse_product->quantity + $quantity;
						// 		$warehouse_product_data = array(
						// 																	"warehouse_id" 	=> $warehouse_id,
						// 																	"product_id"		=> $product_id,
						// 																	"quantity"			=> $new_quantity
						// 																);
						// 		$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);
						// 	}
						// 	else
						// 	{
						// 		// add product with new price
						// 		$warehouse_product_data = array(
						// 																	"warehouse_id" 	=> $warehouse_id,
						// 																	"product_id"		=> $product_id,
						// 																	"cost"					=> $cost,
						// 																	"price"					=> $price,
						// 																	"quantity"			=> $quantity
						// 																);
						// 		$this->warehouse_products_model->add_record($warehouse_product_data);
						// 	}
						// }

						/******************************** END ADD PRODUCT TO WAREHOUSE **************************************/

					}
					
				}

				// commit transaction
				$this->db->trans_commit();	

				$response = array();
				$response['code'] = 1;
				$response['message'] = 'Delivery successfully added.';
				$response['is_all_products_delivered'] = $this->is_all_purchase_products_delivered($purchase_id);

				echo json_encode($response);
			}
			else
			{

				// rollback transaction
				$this->db->trans_rollback();	

				$response = array();
				$response['code'] = 0;
				$response['message'] = 'Delivery is failed to add.';

				echo json_encode($response);
			}
		}
		else
		{

			$delivery_detail_data['purchase_items'] 		= $this->purchase_model->get_purchase_item_records($purchase_id);
			$delivery_detail_data['users']							= $this->ion_auth_model->users()->result();

			$response 																	= array();
			$response['add_delivery_detail'] 						= $this->load->view('purchase/ajax/add_delivery_detail',$delivery_detail_data,TRUE);

			echo json_encode($response);
			
		}
	}

	public function delete_purchase_delivery()
	{
		$purchase_delivery_id 		= $this->input->post('purchase_delivery_id');
		$purchase_delivery 				= $this->purchase_delivery_model->get_delivery_single_record($purchase_delivery_id);
		$purchase_delivery_items 	= $this->purchase_delivery_model->get_delivery_item_records($purchase_delivery_id);
		
		// being transaction
		$this->db->trans_begin();	

		if($this->purchase_delivery_model->delete_delivery_record($purchase_delivery_id))
		{
			foreach ($purchase_delivery_items as $item) 
			{
				$this->purchase_delivery_model->delete_delivery_item_record($purchase_delivery_id,$item->product_id);

				/*************************** UPDATE PRODUCT QUANTITY IN WAREHOUSE ************************************/

				$delivery_quantity	= $item->quantity;

				$product 						= $this->product_model->get_single_record($item->product_id);
				$purchase 					= $this->purchase_model->get_purchase_single_record($purchase_delivery->purchase_id);
				$purchase_item 			= $this->purchase_model->get_single_purchase_item_record($purchase->id,$item->product_id);
				$cost 							= $purchase_item->cost;
				$warehouse_id 			= $purchase->warehouse_id;
				$product_id 				= $item->product_id;

				if($purchase_item->price == 0)
					$price 						= ($cost + ($cost*$product->markup)/100);
				else
					$price 						= $purchase_item->price;

          $warehouse_product 			= $this->warehouse_products_model->get_single_product_by_warehouse_id_product_id_batch_no($warehouse_id,$product_id,$purchase_item->batch_no);

          $new_quantity					 	= $warehouse_product->quantity - $delivery_quantity;
          $warehouse_product_data = array("quantity"			=> $new_quantity);
  
          // warehouse product quantity update
          $this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id); 



				// $warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost_price($warehouse_id,$product_id, $cost, $price);

				// if($delivery_quantity == $warehouse_product->quantity)
				// {
				// 	// Set the cost to 0.00 and quantity to 0 of product
					
				// 	$warehouse_product_data = array(
				// 																"warehouse_id" 	=> $warehouse_id,
				// 																"product_id"		=> $product_id,
				// 																"quantity"			=> 0
				// 															);
				// 	$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);
				// }
				// else
				// {
				// 	// Only reduce the quantity of product

				// 	$new_quantity = $warehouse_product->quantity - $item->quantity;

				// 	$warehouse_product_data = array(
				// 																"warehouse_id" 	=> $warehouse_id,
				// 																"product_id"		=> $product_id,
				// 																"quantity"			=> $new_quantity
				// 															);
				// 	$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id); 
				// }

				/*************************** END UPDATE PRODUCT QUANTITY IN WAREHOUSE ********************************/
			}
			
			// commit transaction
			$this->db->trans_commit();	

			$response = array();
			$response['code'] = 1;
			$response['message'] = 'Delivery successfully deleted.';
			$response['is_all_products_delivered'] = $this->is_all_purchase_products_delivered($purchase_delivery->purchase_id);
			
			echo json_encode($response);
		}
		else
		{

			// rollback transaction
			$this->db->trans_rollback();	

			$response = array();
			$response['code'] = 0;
			$response['message'] = 'Delivery is failed to delete.';

			echo json_encode($response);
		}
	}

	public function pdf($id = null)
	{
		
		if($id != null)
		{
			$id 										= base64_decode($id);		
			$data['purchase'] 			= $this->purchase_model->get_purchase_single_record($id);
			$data['purchase_items'] 	= $this->purchase_model->get_purchase_item_records($id);
			$data['warehouses'] 		= $this->warehouse_model->get_records();
			$data['warehouse_detail']	= $this->warehouse_model->get_single_record($data['purchase']->warehouse_id);
			$data['supplier'] 			= $this->supplier_model->get_records();
				$data['supplier_detail']= $this->supplier_model->get_single_record($data['purchase']->supplier_id);
			$data['discounts']			= $this->discount_model->get_records();
			$data['company_setting']	= $this->company_settings_model->get_company_records();	
			 $warehouse_id = $data['purchase']->warehouse_id;
        $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);

			$html = $this->load->view('purchase/pdf',$data,true);

			// echo $html;
			// exit;
			
			$this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait');  // ADD THIS LINE

			$this->pdf->render();
			$this->pdf->stream("Purchase-".$data['purchase']->reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('purchase','refresh');
		}
	}

	function email_invoice()
	{	
		$purchase_id 									= $this->input->post('purchase_id');

		$company_setting 								= $this->company_settings_model->get_company_records();	
		$purchase  										= $this->purchase_model->get_purchase_single_record($purchase_id);
		$purchase_items 								= $this->purchase_model->get_purchase_item_records($purchase_id);
		$warehouse_detail 								= $this->warehouse_model->get_single_record($purchase->warehouse_id);

		$data['warehouse']								= $warehouse_detail;
		$data['purchase']								= $purchase;
		$data['company_setting']						= $company_setting;
		$data['purchase_items'] 						= $purchase_items;
		$data['warehouse_detail']						= $warehouse_detail;

		$mail_data 										= array();
		
		$mail_data['From'] 								= $company_setting->email;
		$mail_data['FromName'] 							= $company_setting->company_name;
		$mail_data['AddReplyTo'] 						= $company_setting->email;
		$mail_data['ConfirmReadingTo']					= $company_setting->email;
		$mail_data['To']								= $warehouse_detail->email;
		$mail_data['Subject'] 							= "Purchase #".$purchase->reference_no." has generated by ".$company_setting->company_name;

		$mail_data['Body'] 								= $this->load->view('purchase/pdf',$data,true);


		$response = array();

		if($warehouse_detail->email != '')
		{
			if($this->email_model->send_mail($mail_data))
			{
				$response['code'] 			= 1;
				$response['message'] 		= 'Purchase #'.$purchase->reference_no.' has been sent to '.$warehouse_detail->warehouse_name;
				echo json_encode($response);
			}
			else
			{
				$response['code'] 			= 0;
				$response['message'] 		= 'Failed to send Purchase #'.$purchase->reference_no.' has been sent to '.$warehouse_detail->warehouse_name;
				echo json_encode($response);
			}	
		}
		else
		{
			$response['code'] 			= 0;
			$response['message'] 		= $warehouse_detail->email.' records does not have email';
			echo json_encode($response);
		}

	}

	public function is_all_purchase_products_delivered($purchase_id)
	{
		$total_ordered_quantity	= $this->purchase_delivery_model->get_total_no_of_quantity_ordered($purchase_id);
		$total_delivered_quantity	= $this->purchase_delivery_model->get_total_no_of_quantity_delivered($purchase_id);

		if(($total_ordered_quantity - $total_delivered_quantity) == 0)
			return true;
		else
			return false;
		
	}

	public function send_email($purchase_id = null)
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			
			$this->form_validation->set_rules("to_mail","To Email","required");
			$this->form_validation->set_rules("subject","Subject","required");
			$this->form_validation->set_rules("message","Message","required");

			if($this->form_validation->run()==FALSE)
			{
				$response = array(
						'code' => 0,
						'errors' => $this->form_validation->error_array() // Get validation errors
				);
				echo json_encode($response);

			}
			else
			{	

				$from_name 	= $this->input->post("from_name");
				$from_mail 	= $this->input->post("from_mail");
				$to_name 		= $this->input->post("to_name");
				$to_mail 		= $this->input->post("to_mail");
				$to_cc 			= $this->input->post("to_cc");
				$subject 		= $this->input->post("subject");
				$message 		= $this->input->post("message");

				$purchase_id 		= $this->input->post("purchase_id");

				$email_data     = array(
												"from_name" 	=> $from_name,
												"from_mail" 	=> $from_mail,
												"to_name" 		=> $to_name,
												"to_mail" 		=> $to_mail,
												"to_cc" 	 		=> $to_cc,	
												"subject" 	 	=> $subject,
												"message" 	 	=> $message	
												
											);


				// being transaction
				$this->db->trans_begin();
				
				if($id = $this->email_model->add_email_sent_record($email_data))
				{

					$purchase												= $this->purchase_model->get_purchase_single_record($purchase_id);
					$purchase_items 								= $this->purchase_model->get_purchase_item_records($purchase->id);
					$supplier_detail						= $this->supplier_model->get_single_record($purchase->supplier_id);
					$company_setting						= $this->company_settings_model->get_company_records();	
	
					$data['supplier'] 					= $this->supplier_model->get_records();
					$data['supplier_detail']		= $supplier_detail;
					$data['purchase']								= $purchase;
					$data['purchase_items'] 				= $purchase_items;
					$data['company_setting']		= $company_setting;
					$data['discounts']					= $this->discount_model->get_records();
					$data['message'] 						= $email_data['message'];

				
					$to_cc = ($email_data['to_cc'] != '') ? explode(',',  $email_data['to_cc']) : array();
					$to_cc[] = $from_mail;

					$mail_data 											= array();
					
					$mail_data['From'] 							= FROM_EMAIL;
					$mail_data['FromName'] 					= $company_setting->company_name;
					$mail_data['AddReplyTo'] 				= $from_mail;
					$mail_data['ConfirmReadingTo']	= $from_mail;
					$mail_data['To']								= $to_mail;
					$mail_data['Cc']								= $to_cc;
					$mail_data['Subject'] 					= $subject;
					$mail_data['Body'] 							= $this->load->view('email_view' , $data, true);

			
					$html = $this->load->view('purchase/pdf', $data, true);

					$pdf_filename = "Purchase-".$data['purchase']->reference_no.".pdf"; // Set your desired filename

					
          $cid = $company_setting->cid;
          $pdf_path = FCPATH . 'assets/documents/' . $cid . '/purchase/' . $pdf_filename;

          // Create the target folder if it doesn't exist
          $targetDir = FCPATH . 'assets/documents/' . $cid . '/purchase/';
          if (!file_exists($targetDir)) {
              mkdir($targetDir, 0777, true); // The third parameter creates nested directories if needed
          }


	
					
					$this->pdf->loadHtml($html);
					$this->pdf->set_paper('A4', $orientation ?? 'portrait'); 
					$this->pdf->render();

					// Save the rendered PDF content to the given path
					file_put_contents($pdf_path, $this->pdf->output());

					$response = array();

					if($to_mail != '')
					{
						if($this->email_model->send_mail($pdf_path,$mail_data))
						{
							$response['code'] 			= 1;
							$response['message'] 		= 'Email Sent successfully';
							echo json_encode($response);
						}
						else
						{
							$response['code'] 			= 0;
							$response['message'] 		= 'Failed to send email';
							echo json_encode($response);
						}	
					}
					else
					{
						$response['code'] 				= 0;
						$response['message'] 			= 'Failed to send email';
						echo json_encode($response);
					}


					$this->db->trans_commit();

				
				}
				else
				{
					// rollback transaction
					$this->db->trans_rollback();

					if($this->input->is_ajax_request())
					{	
						$response 											= array();
						$response['code'] 							= 0;
						$response['message']						= 'Failed to send email';
						
						echo json_encode($response);
					}
					else
					{
						$this->session->set_flashdata('failure', 'Failed to send email');
						redirect('quotation','refresh');
					}
					
				}
			}

		}
		else
		{
		
			$data['purchase'] 	= $this->purchase_model->get_purchase_single_record($purchase_id);
			$data['supplier']		= $this->supplier_model->get_single_record($data['purchase']->supplier_id);
			$data['company_setting']	= $this->company_settings_model->get_company_records();	
			$data['email_template'] 	= $this->email_template_model->get_record_by_module(EMAIL_TEMPLATE_MODULE_PURCHASE);

			$response 												= array();
	  	$response['code']  								= 1;

			   
			$cc_emails = ''; 

			
			if (strpos($data['supplier']->email, ',') !== false) 
			{
				$emails = explode(',', $data['supplier']->email);
				$data['supplier']->email = trim($emails[0]);

				$cc_emails = implode(',', array_slice($emails, 1));
			}

			$data['cc_emails'] = $cc_emails;
			
			$response['email_modal_body'] 	= $this->load->view('purchase/ajax/email_modal_body',$data,TRUE);

			
	  	echo json_encode($response);
		}
	}

		/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->purchase_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];
    $purchase_payment = $_POST['purchase_payment'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

        $delivered_product_qty = $this->purchase_delivery_model->get_total_no_of_quantity_delivered($item->id);
        $paid_amount  = $this->transaction_model->get_total_transaction_amount($item->id, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);
       
				
				if($item->delete_status == DELETED)
        {
					// if($this->permission_model->has_permission('restore_purchase'))
					// {
          		$table_body .= '
																<a href="#"  data-toggle="modal" data-target="#restore_purchase" data-tt="tooltip" title="Restore Purchase" class="btn btn-primary btn-xs restore_purchase" data-purchase_id="'.$item->id.'">
																	<i class="fas fa-redo-alt"></i>
																</a>
													';
					// }
        }
				else
				{
						if($this->permission_model->has_permission('email_purchase'))
						{
		
							$table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('send_email').'" class="btn btn-success btn-sm text-white email-modal" data-purchase_id="'.$item->id.'">
								<i class="fas fa-at"></i> 
							</a>';
						}


						//View Button
						if($this->permission_model->has_permission('view_purchase') || $this->permission_model->has_permission('view_all_purchase'))
						{	
							$table_body .= ' 
												<a href="'.base_url('purchase/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="View purchase">
													<i class="fas fa-eye"></i> 
												</a>      
											';
						}

						// Edit Button        
						if($this->permission_model->has_permission('edit_purchase') || $this->permission_model->has_permission('edit_all_purchase'))
						{	
							if($delivered_product_qty > 0 || $paid_amount > 0)
							{

							$table_body .= 	 '
															<a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-target="#purchase_edit" data-tt="tooltip" title="Edit purchase" data-purchase_id="'.$item->id.'">
																<i class="fas fa-edit"></i> 
															</a> 
														';

							}
							else
							{

							$table_body .= ' 
														<a href="'.base_url('purchase/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs"  data-tt="tooltip" title="Edit purchase">
															<i class="fas fa-edit"></i> 
														</a>
													';
							}

						}

						// Delete Button
						if($this->permission_model->has_permission('delete_purchase') || $this->permission_model->has_permission('delete_all_purchase'))
						{	
						$table_body .= '
												<a href="#"  data-toggle="modal" data-target="#delete_purchase" data-tt="tooltip" title="Delete purchase" class="btn btn-danger btn-xs delete_purchase" data-purchase_id="'.$item->id.'">
													<i class="fas fa-trash"></i> 
												</a>
											';
						}
				}

			

				$ordered_quantity 	= (float)$this->purchase_delivery_model->get_total_no_of_quantity_ordered($item->id);
        $delivered_quantity = (float)$this->purchase_delivery_model->get_total_no_of_quantity_delivered($item->id);

        //paid amount
				$paid_amount_html 	=	'<span class="text-success">'.(($paid_amount == '') ? '0.000' : number_format_i($paid_amount)).'</span>';
				//due amount
				$due_amount_html 		=  '<span class="text-danger">'.number_format_i(($item->total-$paid_amount)).'</span>';


				//Delievery status
			 
               

//Delievery status - FONTAWESOME ICONS + TEXT
//Delievery status - CLEANER VERSION
$delivered_quantity_html = '';

if($delivered_quantity == 0)
{
    $delivered_quantity_html .= '<div class="text-center">
                                    <button type="button" class="btn btn-xs btn-danger open_delivery_modal" data-target="#delivery-modal" data-toggle="modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_id="'.$item->id.'">
                                        Pending
                                    </button>
                                    <div class="mt-1">
                                        <img src="'.base_url('assets/images/not_delivered.png').'" width="40">
                                    </div>
                                </div>';
}
else if($delivered_quantity < $ordered_quantity && $delivered_quantity > 0)
{
    $delivered_quantity_html .= '<div class="text-center">
                                    <button type="button" class="btn btn-xs btn-warning open_delivery_modal" data-target="#delivery-modal" data-toggle="modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_id="'.$item->id.'">
                                        Partial
                                    </button>
                                    <div class="mt-1">
                                        <img src="'.base_url('assets/images/partial_delivered.png').'" width="40">
                                    </div>
                                </div>';
}
else if($delivered_quantity == $ordered_quantity)
{
    $delivered_quantity_html .= '<div class="text-center">
                                    <button type="button" class="btn btn-xs btn-success open_delivery_modal" data-target="#delivery-modal" data-toggle="modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_id="'.$item->id.'">
                                        Delivered
                                    </button>
                                    <div class="mt-1">
                                        <img src="'.base_url('assets/images/delivered.png').'" width="40">
                                    </div>
                                </div>';
}
        // if($delivered_quantity == 0)
        // {
        //   $delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_id="'.$item->id.'">
								// 												<img src="'.base_url('assets/images/not_delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
								// 											</a>';
        // }
        // else if($delivered_quantity < $ordered_quantity && $delivered_quantity > 0)
        // {
        //   $delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_id="'.$item->id.'">
								// 												<img src="'.base_url('assets/images/partial_delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
								// 											</a>';
        // }
        // else if($delivered_quantity == $ordered_quantity)
        // {
        //   $delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-purchase_id="'.$item->id.'">
								// 												<img src="'.base_url('assets/images/delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
								// 											</a>';
        // }

        $reference_no_html = '';

        if($item->delete_status == DELETED)
        {
          $reference_no_html .= $item->reference_no;
        }
        else
        {
          //Reference no
          $reference_no_html = '<a href="'.base_url('purchase/view/'.base64_encode($item->id)).'" data-tt="tooltip" title="'.$this->lang->line('purchase_view').'">'.$item->reference_no.'</a>';
        }

        

				/* End Action column buttons*/

				
	        $row = array();
	       
		      $row[] = $reference_no_html;
		      $row[] = ($item->delete_status == DELETED) ? '' : $item->invoice_no;
		      $row[] = ($item->delete_status == DELETED) ? '' : date('d-m-Y', strtotime($item->purchase_date));
		      $row[] = ($item->delete_status == DELETED) ? '' : $item->name;
		      $row[] = ($item->delete_status == DELETED) ? '' : $item->company_name;
		     $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total_discount);
		      $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total_taxable_value);
		      $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total_tax);
		      $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total);
		      $row[] = ($item->delete_status == DELETED) ? '' : $paid_amount_html;
		      $row[] = ($item->delete_status == DELETED) ? '' : $due_amount_html;
		      $row[] = ($item->delete_status == DELETED) ? '' : $delivered_quantity_html;


	        $row[] = $table_header.$table_body.$table_footer;    
					$data[] = $row;
			
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->purchase_model->count_all(),
                    "recordsFiltered" => $this->purchase_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function purchase_delete_confirmation()
  {
  	$purchase_id 				= $this->input->post('purchase_id');
  	$data['purchase'] 	= $this->purchase_model->get_purchase_single_record($purchase_id);

  	$response 					= array();
  	$response['purchase_delete_modal_body'] 	= $this->load->view('purchase/ajax/purchase_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	public function purchase_restore_confirmation()
  {
  	$purchase_id 				= $this->input->post('purchase_id');
  	$data['purchase'] 	= $this->purchase_model->get_purchase_single_record($purchase_id);

  	$response 					= array();
  	$response['purchase_restore_modal_body'] 	= $this->load->view('purchase/ajax/purchase_restore_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	public function purchase_edit_confirmation()
  {
  	$purchase_id 				= $this->input->post('purchase_id');
  	$data['purchase'] 		= $this->purchase_model->get_purchase_single_record($purchase_id);

  	$response 					= array();
  	$response['purchase_edit_modal_body'] 	= $this->load->view('purchase/ajax/purchase_edit_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/

	function get_record_detail_by_invoice_no()
	{
		$invoice_no  	= $this->input->post('invoice_no');
		$module 			= $this->input->post('module');
		$product_id 	= $this->input->post('product_id');

		$purchase 				= $this->purchase_model->get_purchase_record_details_by_invoice_no($invoice_no);

		$purchase_items 	= array();
		$purchase_items 	= $this->warehouse_products_model->get_single_record_for_sales($product_id);

		
		$response = array();

		if($purchase != null)
		{
			$response['code'] 				= 0;
			$response['message'] 			= $invoice_no.' is already exists.';
			$response['purchase']					= $purchase;
			$response['purchase_items']		= $purchase_items;
			//$response['sale_items'] =	$sale_item;

			echo json_encode($response);
		}
		else
		{
			$response['code'] = 1;
			$response['message'] = 'Purchase does not exist with this'.$invoice_no;

			echo json_encode($response);	
		}
	}

	public function upload_documents() 
	{
    if (isset($_FILES["upload_document"])) 
		{
			$data['company_setting'] 	= $this->company_settings_model->get_company_records();

			$cid = $data['company_setting']->cid; 

			$targetDir = "./assets/documents/$cid/purchase/";

			if (!file_exists($targetDir)) {
					mkdir($targetDir, 0777, true);
			}

			$response = array();

			foreach ($_FILES["upload_document"]["tmp_name"] as $key => $tmp_name) {
					$uploadFilename = basename($_FILES["upload_document"]["name"][$key]);
					$microtime = microtime(true);
					$microsecondPrefix = str_replace(".", "_", $microtime);
					$newFilename = $microsecondPrefix . "_" . $uploadFilename;
					$targetFile = $targetDir . $newFilename;

					if (move_uploaded_file($tmp_name, $targetFile)) {
							$response[] = $newFilename;
					} else {
							$response[] = "Error uploading document: " . $_FILES["upload_document"]["error"][$key];
					}
			}

			echo json_encode($response);
    } 
		else 
		{
        echo json_encode(array("error" => "No document file provided."));
    }
	}
}
