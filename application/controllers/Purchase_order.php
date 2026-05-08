<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Purchase_order extends MY_Controller {

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
	
	
	public function index()
	{
		$data['warehouse'] 							= $this->warehouse_model->get_records();
		$data['purchase_order'] 				= $this->purchase_order_model->get_purchase_order_records();
		$data['total_purchase_order']		= $this->purchase_order_model->get_total_purchase_order();
			


// 		$log_data = array(
// 				              "user_id"     => $this->session->userdata("user_id"),
// 				              "module"      => "purchase_order",
// 				              "user_action" => 0,
// 				              "description"	=> "User has viewed list of purchase order."
// 				            );

// 		$this->log_data_model->add_record($log_data);
		$this->load->view('purchase_order/list',$data);
	}
	
    /*Transport value not fetching in this function
    public function add()
    {
        if(!$this->permission_model->has_permission('add_purchase_order'))
        {
            $this->load->view('errors/html/error_restricted'); 
        }
        else
        {
            if($this->input->server('REQUEST_METHOD') === 'POST')
            {
                    //                 echo "<pre>";
                    // print_r($this->input->post());
                    // echo "</pre>";
                    // die;
                
                $this->form_validation->set_rules('purchase_order_date','Invoice Date','required');        
                $this->form_validation->set_rules('warehouse_id','Warehouse','required');
                $this->form_validation->set_rules('supplier_id','Supplier','required');
    
                if($this->form_validation->run()==FALSE)
                {
                    $data['warehouses']             = $this->warehouse_model->get_records();
                    $data['supplier']                 = $this->supplier_model->get_records();
                    $data['company_setting']    = $this->company_settings_model->get_company_records();    
                    $this->load->view('purchase_order/add',$data);
                }
                else
                {
                    $purchase_order_date     = date('Y-m-d', strtotime($this->input->post('purchase_order_date')));
                    $reference_no                 = $this->purchase_order_model->get_lastest_sequence_number();
                    $warehouse_id                 = $this->input->post('warehouse_id');
                    $supplier_id                     = $this->input->post('supplier_id');
    
                    $supplier                         = $this->supplier_model->get_single_record($supplier_id);
                    $supplier_gstin             = $supplier->gstin;
                    $due_days       = $this->input->post('due_days');
$payment_terms = $this->input->post('payment_terms');
if ($due_days) {
    $due_date = date(
        'Y-m-d',
        strtotime("+$due_days days", strtotime($purchase_order_date))
    );
} else {
    $due_date = NULL;
}
    
                    // Get freight data
                    $freightData = json_decode($this->input->post('freight_data'), true);
                    if (!$freightData) {
                        $freightData = [
                            'freight_amount' => 0,
                            'freight_taxable_value' => 0,
                            'freight_sub_total' => 0
                        ];
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
    
                    $purchase_order_data = array(
                        "purchase_order_date"    => $purchase_order_date,
                        "reference_no"                => $reference_no,
                        "warehouse_id"                => $warehouse_id,
                        "supplier_id"                    => $supplier_id,
                        "payment_terms" => $payment_terms,
"due_days"      => $due_days,
"due_date"      => $due_date,
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
                        "freight_sub_total" => $freightData['freight_sub_total']
                    );
    
                    // Begin transaction
                    $this->db->trans_begin();    
    
                    if($id = $this->purchase_order_model->add_purchase_order_record($purchase_order_data))
                    {
                        $entered_purchase_order = $this->purchase_order_model->get_purchase_order_single_record($id);
                        $supplier                 = $this->supplier_model->get_single_record($entered_purchase_order->supplier_id);
    
                        $log_data = array(
                            "user_id"         => $this->session->userdata("user_id"),
                            "module"            => "purchase_order",
                            "entry_id"        => $id,
                            "user_action"    => 1,
                            "data"                => json_encode((array)$entered_purchase_order),
                            "description" => 'Purchase Order (Reference No. -'  .$reference_no .') is added successfully.'
                        );
                        $this->log_data_model->add_record($log_data);
    
                        $purchase_order_items                 = $this->input->post('purchase_order_items');
                        $purchase_order_items_array     = explode("|", $this->input->post('purchase_order_items'));
    
                        for ($i=0; $i < sizeof($purchase_order_items_array) ; $i++) { 
                                    
                            $temp_purchase_order_item = (array)json_decode($purchase_order_items_array[$i]);
                            $temp_purchase_order_item['purchase_order_id']  = $id;
    
                            if($temp_purchase_order_item['expiry_date'] == '' || $temp_purchase_order_item['expiry_date'] == '0000-00-00')
                                unset($temp_purchase_order_item['expiry_date']);
                            else
                                $temp_purchase_order_item['expiry_date'] = date('Y-m-d',strtotime($temp_purchase_order_item['expiry_date']));
    
                            if($temp_purchase_order_item['mfg_date'] == '' || $temp_purchase_order_item['mfg_date'] == '0000-00-00')
                                unset($temp_purchase_order_item['mfg_date']);
                            else
                                $temp_purchase_order_item['mfg_date'] = date('Y-m-d',strtotime($temp_purchase_order_item['mfg_date']));
    
                            // Add product remark to item data
                            if(isset($temp_purchase_order_item['product_remark'])) {
                                $temp_purchase_order_item['product_remark'] = $temp_purchase_order_item['product_remark'];
                            }
    
                            $this->purchase_order_model->add_purchase_order_item_record($temp_purchase_order_item);
                        }
    
                        // commit transaction
                        $this->db->trans_commit();
    
                        $this->session->set_flashdata('success',  'Purchase Order (Reference No. -'.$reference_no.') is added successfully.');
                        redirect('purchase_order','refresh');
                    }
                    else
                    {
                        // rollback transaction
                        $this->db->trans_rollback();
    
                        $this->session->set_flashdata('failure',  'Purchase Order (Reference No. -'.$reference_no.') is failed to add.');
                        redirect('purchase_order','refresh');
                    }
                }
            }
            else
            {
                $data['warehouses']             = $this->warehouse_model->get_records();
                $data['supplier']                 = $this->supplier_model->get_records();
                $data['company_setting']    = $this->company_settings_model->get_company_records();
                $data['due_days_options'] = $this->db
    ->where('status', 1)
    ->get('due_days')
    ->result();
                $this->load->view('purchase_order/add',$data);
            }
        }    
    }

public function edit($id = null)
{
    if(!$this->permission_model->has_permission('edit_purchase_order'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            
                        //                         echo "<pre>";
                        // print_r($this->input->post());
                        // echo "</pre>";
                        // die;
            
            $purchase_order_id = $this->input->post('id');
            $old_purchase_order = $this->purchase_order_model->get_purchase_order_single_record($purchase_order_id);

            $this->form_validation->set_rules('purchase_order_date','Invoice Date','required');        
            $this->form_validation->set_rules('supplier_id','supplier','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['supplier']             = $this->supplier_model->get_records();
                $data['warehouses']             = $this->warehouse_model->get_records();
                $data['company_setting']    = $this->company_settings_model->get_company_records();
                
                $this->load->view('purchase_order/add',$data);
            }
            else
            {
                $purchase_order_date                 = date('Y-m-d', strtotime($this->input->post('purchase_order_date')));
                $due_days      = $this->input->post('due_days');
                $payment_terms = $this->input->post('payment_terms');
                
// $payment_terms = $this->input->post('payment_terms_cond');

if ($due_days) {
    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($purchase_order_date)));
} else {
    $due_date = NULL;
}

                $supplier_id                     = $this->input->post('supplier_id');
                
                $supplier                         = $this->supplier_model->get_single_record($supplier_id);
                $supplier_gstin             = $supplier->gstin;

                $warehouse_id                 = $this->input->post('warehouse_id');
                $reference_no                 = $this->input->post('reference_no');
            
                // Get freight data
                $freightData = json_decode($this->input->post('freight_data'), true);
                if (!$freightData) {
                    $freightData = [
                        'freight_amount' => 0,
                        'freight_taxable_value' => 0,
                        'freight_sub_total' => 0
                    ];
                }

                $total_taxable_value     = $this->input->post('total_taxable_value');
                $total_discount             =    $this->input->post('total_discount');
                $total_tax                     = $this->input->post('total_tax');
                $total                             = $this->input->post('total');
                $internal_note             = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note             = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition     = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

                $document                         = $this->input->post("document");

                if (!is_array($document)) {
                    $document = array($document);
                }

                $documents = implode(',', $document);

                $additional_cost_type   = $this->input->post('additional_cost_type');
                $additional_cost_amount = $freightData['freight_amount'] ?? 0;

                $purchase_order_data = array(
                                    "purchase_order_date"        => $purchase_order_date,
                                    
                                    "supplier_id"                        => $supplier_id,
                                    "supplier_gstin"                => $supplier_gstin,
                                    "warehouse_id"                    => $warehouse_id,
                                     "payment_terms" => $payment_terms,
    "due_days"      => $due_days,
    "due_date"      => $due_date,
                                    "total_taxable_value"     => $total_taxable_value,    
                                    "total_tax"                            => $total_tax,
                                    "total_discount"                 => $total_discount,    
                                    "total"                                 => $total,
                                    "internal_note"                    => $internal_note,
                                    "external_note"                 => $external_note,
                                    "terms_and_condition"        => $terms_and_condition,
                                    "document"                             => $documents,
                                    "user_id"                             => $this->session->userdata('user_id'),
                                    "additional_cost_type" => $additional_cost_type,
                                    "additional_cost_amount" => $additional_cost_amount,
                                    "freight_amount" => $freightData['freight_amount'],
                                    "freight_taxable_value" => $freightData['freight_taxable_value'],
                                    "freight_sub_total" => $freightData['freight_sub_total']
                                );

                // being transaction
                $this->db->trans_begin();    

                if($this->purchase_order_model->edit_purchase_order_record($purchase_order_data,$purchase_order_id))
                {
                    $entered_purchase_order = $this->purchase_order_model->get_purchase_order_single_record($purchase_order_id);
                    $supplier                 = $this->supplier_model->get_single_record($entered_purchase_order->supplier_id);

                    
                    $log_data = array(
                                                    "user_id"         => $this->session->userdata("user_id"),
                                                    "module"            => "purchase_order",
                                                    "user_action"    => 2,
                                                    "b_data"            => json_encode((array)$old_purchase_order),
                                                    "data"                => json_encode((array)$entered_purchase_order),
                                                    "description" => 'Purchase Order (Reference No. -'  .$reference_no .') is updated successfully.'
                                                );
                    $this->log_data_model->add_record($log_data);
                    $this->purchase_order_model->remove_purchase_order_item_records($purchase_order_id);

                    $purchase_order_items                 = $this->input->post('purchase_order_items');
                    $purchase_order_items_array     = explode("|", $this->input->post('purchase_order_items'));

                    for ($i=0; $i < sizeof($purchase_order_items_array) ; $i++) { 
                                
                        $temp_purchase_order_item = (array)json_decode($purchase_order_items_array[$i]);
                        $temp_purchase_order_item['purchase_order_id']  = $purchase_order_id;

                        if($temp_purchase_order_item['expiry_date'] == '' || $temp_purchase_order_item['expiry_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['expiry_date']);
                        else
                            $temp_purchase_order_item['expiry_date'] = date('Y-m-d',strtotime($temp_purchase_order_item['expiry_date']));

                        if($temp_purchase_order_item['mfg_date'] == '' || $temp_purchase_order_item['mfg_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['mfg_date']);
                        else
                            $temp_purchase_order_item['mfg_date'] = date('Y-m-d',strtotime($temp_purchase_order_item['mfg_date']));

                        // Add product remark to item data
                        if(isset($temp_purchase_order_item['product_remark'])) {
                            $temp_purchase_order_item['product_remark'] = $temp_purchase_order_item['product_remark'];
                        }

                        $this->purchase_order_model->add_purchase_order_item_record($temp_purchase_order_item);
                    }

                    // commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success',  'Purchase Order(Reference No. -'  .$reference_no .') is updated successfully.');
                    redirect('purchase_order','refresh');
                }
                else
                {
                    // rollback transaction
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('failure',  'Purchase Order (Reference No. -'  .$reference_no .') is failed to update.');
                    redirect('purchase_order','refresh');
                }
            }
        }
        else
        {
            $id = base64_decode($id);

            if($id != null)
            {
                $data['purchase_order']                     = $this->purchase_order_model->get_purchase_order_single_record($id);

                if($data['purchase_order'] != null)
                {
                    $data['purchase_order_items']         = $this->purchase_order_model->get_purchase_order_item_records($id);
                    $data['supplier']                     = $this->supplier_model->get_records();
                    $data['supplier_detail']        = $this->supplier_model->get_single_record($data['purchase_order']->supplier_id);
                    $data['discounts']                    = $this->discount_model->get_records();
                    $data['warehouses']                 = $this->warehouse_model->get_records();
                    $data['company_setting']        = $this->company_settings_model->get_company_records(); 
                    $data['due_days_options'] = $this->db
    ->where('status', 1)
    ->get('due_days')
    ->result();
                    
// 			echo "<pre>";
// 					print_r($data['purchase_order_items']);
// 					echo "</pre>";
// 					die;

                    $this->load->view('purchase_order/edit',$data);
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
    }
    
}*/
public function add()
{
    if(!$this->permission_model->has_permission('add_purchase_order'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $this->form_validation->set_rules('purchase_order_date','Invoice Date','required');        
            $this->form_validation->set_rules('warehouse_id','Warehouse','required');
            $this->form_validation->set_rules('supplier_id','Supplier','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['warehouses']             = $this->warehouse_model->get_records();
                $data['supplier']               = $this->supplier_model->get_records();
                $data['company_setting']        = $this->company_settings_model->get_company_records();    
                $this->load->view('purchase_order/add', $data);
            }
            else
            {
                $purchase_order_date = date('Y-m-d', strtotime($this->input->post('purchase_order_date')));
                $reference_no = $this->purchase_order_model->get_lastest_sequence_number();
                $warehouse_id = $this->input->post('warehouse_id');
                $supplier_id = $this->input->post('supplier_id');

                $supplier = $this->supplier_model->get_single_record($supplier_id);
                $supplier_gstin = $supplier->gstin;
                $due_days = $this->input->post('due_days');
                $payment_terms = $this->input->post('payment_terms');
                
                if ($due_days) {
                    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($purchase_order_date)));
                } else {
                    $due_date = NULL;
                }

                // Get purchase order items and calculate total discount
                $purchase_order_items_array = explode("|", $this->input->post('purchase_order_items'));
                $calculated_total_discount = 0;
                
                for ($i = 0; $i < sizeof($purchase_order_items_array); $i++) {
                    $temp_purchase_order_item = (array)json_decode($purchase_order_items_array[$i]);
                    if (isset($temp_purchase_order_item['discount_amount'])) {
                        $calculated_total_discount += floatval($temp_purchase_order_item['discount_amount']);
                    }
                }

                // Use calculated discount
                $total_discount = $calculated_total_discount;

                // Get freight data
                $freight_amount = $this->input->post('freight_amount') ?: 0;
                $freight_taxable_value = $this->input->post('freight_taxable_value') ?: 0;
                $freight_sub_total = $this->input->post('freight_sub_total') ?: 0;
                $freight_tax_rate = $this->input->post('freight_tax_rate') ?: 0;
                $freight_tax_amount = $this->input->post('freight_tax_amount') ?: 0;

                $total_taxable_value = $this->input->post('total_taxable_value');
                $total_tax = $this->input->post('total_tax');
                $total = $this->input->post('total');
                $internal_note = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
                $additional_cost_type = $this->input->post('additional_cost_type');
                $additional_cost_amount = $freight_amount;

                $document = $this->input->post("document");

                if (!is_array($document)) {
                    $document = array($document);
                }

                $documents = implode(',', $document);

                $purchase_order_data = array(
                    "purchase_order_date"    => $purchase_order_date,
                    "reference_no"           => $reference_no,
                    "warehouse_id"           => $warehouse_id,
                    "supplier_id"            => $supplier_id,
                    "payment_terms"          => $payment_terms,
                    "due_days"               => $due_days,
                    "due_date"               => $due_date,
                    "supplier_gstin"         => $supplier_gstin,
                    "total_taxable_value"    => $total_taxable_value,    
                    "total_tax"              => $total_tax,
                    "total_discount"         => $total_discount,  // Using calculated value
                    "total"                  => $total,
                    "internal_note"          => $internal_note,
                    "external_note"          => $external_note,
                    "document"               => $documents,
                    "terms_and_condition"    => $terms_and_condition,
                    "user_id"                => $this->session->userdata('user_id'),
                    "additional_cost_type"   => $additional_cost_type,
                    "additional_cost_amount" => $additional_cost_amount,
                    "freight_amount"         => $freight_amount,
                    "freight_taxable_value"  => $freight_taxable_value,
                    "freight_sub_total"      => $freight_sub_total,
                    "freight_tax_rate"       => $freight_tax_rate,
                    "freight_tax_amount"     => $freight_tax_amount
                );

                // Begin transaction
                $this->db->trans_begin();    

                if($id = $this->purchase_order_model->add_purchase_order_record($purchase_order_data))
                {
                    $entered_purchase_order = $this->purchase_order_model->get_purchase_order_single_record($id);
                    $supplier = $this->supplier_model->get_single_record($entered_purchase_order->supplier_id);

                    $log_data = array(
                        "user_id"     => $this->session->userdata("user_id"),
                        "module"      => "purchase_order",
                        "entry_id"    => $id,
                        "user_action" => 1,
                        "data"        => json_encode((array)$entered_purchase_order),
                        "description" => 'Purchase Order (Reference No. -' . $reference_no . ') is added successfully.'
                    );
                    $this->log_data_model->add_record($log_data);

                    // Save purchase order items with proper discount_amount
                    for ($i = 0; $i < sizeof($purchase_order_items_array); $i++) { 
                        $temp_purchase_order_item = (array)json_decode($purchase_order_items_array[$i]);
                        $temp_purchase_order_item['purchase_order_id'] = $id;

                        // CRITICAL: Ensure discount_amount is set correctly
                        if (!isset($temp_purchase_order_item['discount_amount']) || $temp_purchase_order_item['discount_amount'] == '') {
                            // Calculate discount_amount if not set
                            $quantity = isset($temp_purchase_order_item['quantity']) ? floatval($temp_purchase_order_item['quantity']) : 1;
                            $cost = isset($temp_purchase_order_item['cost']) ? floatval($temp_purchase_order_item['cost']) : 0;
                            $discount_type = isset($temp_purchase_order_item['discount_type']) ? intval($temp_purchase_order_item['discount_type']) : 0;
                            $discount_value = isset($temp_purchase_order_item['discount_value']) ? floatval($temp_purchase_order_item['discount_value']) : 0;
                            
                            $subtotal = $quantity * $cost;
                            
                            if ($discount_value > 0) {
                                if ($discount_type == 1) {
                                    // Percentage discount
                                    $temp_purchase_order_item['discount_amount'] = ($subtotal * $discount_value) / 100;
                                } else {
                                    // Fixed discount
                                    $temp_purchase_order_item['discount_amount'] = $discount_value;
                                }
                            } else {
                                $temp_purchase_order_item['discount_amount'] = 0;
                            }
                        }

                        if($temp_purchase_order_item['expiry_date'] == '' || $temp_purchase_order_item['expiry_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['expiry_date']);
                        else
                            $temp_purchase_order_item['expiry_date'] = date('Y-m-d', strtotime($temp_purchase_order_item['expiry_date']));

                        if($temp_purchase_order_item['mfg_date'] == '' || $temp_purchase_order_item['mfg_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['mfg_date']);
                        else
                            $temp_purchase_order_item['mfg_date'] = date('Y-m-d', strtotime($temp_purchase_order_item['mfg_date']));

                        if(isset($temp_purchase_order_item['product_remark'])) {
                            $temp_purchase_order_item['product_remark'] = $temp_purchase_order_item['product_remark'];
                        }

                        $this->purchase_order_model->add_purchase_order_item_record($temp_purchase_order_item);
                    }

                    // commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success', 'Purchase Order (Reference No. -' . $reference_no . ') is added successfully.');
                    redirect('purchase_order', 'refresh');
                }
                else
                {
                    // rollback transaction
                    $this->db->trans_rollback();

                    $this->session->set_flashdata('failure', 'Purchase Order (Reference No. -' . $reference_no . ') failed to add.');
                    redirect('purchase_order', 'refresh');
                }
            }
        }
        else
        {
            $data['warehouses']          = $this->warehouse_model->get_records();
            $data['supplier']            = $this->supplier_model->get_records();
            $data['company_setting']     = $this->company_settings_model->get_company_records();
            $data['due_days_options']    = $this->db->where('status', 1)->get('due_days')->result();
            $this->load->view('purchase_order/add', $data);
        }
    }    
}
public function edit($id = null)
{
    if(!$this->permission_model->has_permission('edit_purchase_order'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $purchase_order_id = $this->input->post('id');
            $old_purchase_order = $this->purchase_order_model->get_purchase_order_single_record($purchase_order_id);

            $this->form_validation->set_rules('purchase_order_date','Invoice Date','required');        
            $this->form_validation->set_rules('supplier_id','Supplier','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['supplier']        = $this->supplier_model->get_records();
                $data['warehouses']      = $this->warehouse_model->get_records();
                $data['company_setting'] = $this->company_settings_model->get_company_records();
                $this->load->view('purchase_order/edit', $data);
            }
            else
            {
                $purchase_order_date = date('Y-m-d', strtotime($this->input->post('purchase_order_date')));
                $due_days = $this->input->post('due_days');
                $payment_terms = $this->input->post('payment_terms');
                
                if ($due_days) {
                    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($purchase_order_date)));
                } else {
                    $due_date = NULL;
                }

                $supplier_id = $this->input->post('supplier_id');
                $supplier = $this->supplier_model->get_single_record($supplier_id);
                $supplier_gstin = $supplier->gstin;
                $warehouse_id = $this->input->post('warehouse_id');
                $reference_no = $this->input->post('reference_no');
            
                // ========== CRITICAL: Get purchase order items and calculate total discount ==========
                $purchase_order_items_array = explode("|", $this->input->post('purchase_order_items'));
                $calculated_total_discount = 0;
                
                for ($i = 0; $i < sizeof($purchase_order_items_array); $i++) {
                    $temp_purchase_order_item = (array)json_decode($purchase_order_items_array[$i]);
                    
                    // Calculate discount_amount if not set or if needed
                    if (isset($temp_purchase_order_item['discount_value']) && floatval($temp_purchase_order_item['discount_value']) > 0) {
                        $quantity = isset($temp_purchase_order_item['quantity']) ? floatval($temp_purchase_order_item['quantity']) : 1;
                        $cost = isset($temp_purchase_order_item['cost']) ? floatval($temp_purchase_order_item['cost']) : 0;
                        $discount_type = isset($temp_purchase_order_item['discount_type']) ? intval($temp_purchase_order_item['discount_type']) : 0;
                        $discount_value = floatval($temp_purchase_order_item['discount_value']);
                        
                        $subtotal = $quantity * $cost;
                        
                        if ($discount_type == 1) {
                            // Percentage discount
                            $discount_amount = ($subtotal * $discount_value) / 100;
                        } else {
                            // Fixed amount discount
                            $discount_amount = $discount_value;
                        }
                        
                        $temp_purchase_order_item['discount_amount'] = $discount_amount;
                        $calculated_total_discount += $discount_amount;
                    } elseif (isset($temp_purchase_order_item['discount_amount'])) {
                        $calculated_total_discount += floatval($temp_purchase_order_item['discount_amount']);
                    }
                }

                // Use calculated discount instead of POST value
                $total_discount = $calculated_total_discount;
                // ========== END OF DISCOUNT CALCULATION ==========

                // Get freight data
                $freight_amount = $this->input->post('freight_amount') ?: 0;
                $freight_taxable_value = $this->input->post('freight_taxable_value') ?: 0;
                $freight_sub_total = $this->input->post('freight_sub_total') ?: 0;
                $freight_tax_rate = $this->input->post('freight_tax_rate') ?: 0;
                $freight_tax_amount = $this->input->post('freight_tax_amount') ?: 0;

                $total_taxable_value = $this->input->post('total_taxable_value');
                $total_tax = $this->input->post('total_tax');
                $total = $this->input->post('total');
                $internal_note = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

                $document = $this->input->post("document");

                if (!is_array($document)) {
                    $document = array($document);
                }

                $documents = implode(',', $document);

                $additional_cost_type = $this->input->post('additional_cost_type');
                $additional_cost_amount = $freight_amount;

                $purchase_order_data = array(
                    "purchase_order_date"    => $purchase_order_date,
                    "supplier_id"            => $supplier_id,
                    "supplier_gstin"         => $supplier_gstin,
                    "warehouse_id"           => $warehouse_id,
                    "payment_terms"          => $payment_terms,
                    "due_days"               => $due_days,
                    "due_date"               => $due_date,
                    "total_taxable_value"    => $total_taxable_value,    
                    "total_tax"              => $total_tax,
                    "total_discount"         => $total_discount,  // Using calculated value
                    "total"                  => $total,
                    "internal_note"          => $internal_note,
                    "external_note"          => $external_note,
                    "terms_and_condition"    => $terms_and_condition,
                    "document"               => $documents,
                    "user_id"                => $this->session->userdata('user_id'),
                    "additional_cost_type"   => $additional_cost_type,
                    "additional_cost_amount" => $additional_cost_amount,
                    "freight_amount"         => $freight_amount,
                    "freight_taxable_value"  => $freight_taxable_value,
                    "freight_sub_total"      => $freight_sub_total,
                    "freight_tax_rate"       => $freight_tax_rate,
                    "freight_tax_amount"     => $freight_tax_amount
                );

                // begin transaction
                $this->db->trans_begin();    

                if($this->purchase_order_model->edit_purchase_order_record($purchase_order_data, $purchase_order_id))
                {
                    $entered_purchase_order = $this->purchase_order_model->get_purchase_order_single_record($purchase_order_id);
                    $supplier = $this->supplier_model->get_single_record($entered_purchase_order->supplier_id);

                    $log_data = array(
                        "user_id"     => $this->session->userdata("user_id"),
                        "module"      => "purchase_order",
                        "user_action" => 2,
                        "b_data"      => json_encode((array)$old_purchase_order),
                        "data"        => json_encode((array)$entered_purchase_order),
                        "description" => 'Purchase Order (Reference No. -' . $reference_no . ') is updated successfully.'
                    );
                    $this->log_data_model->add_record($log_data);
                    
                    // Remove old items
                    $this->purchase_order_model->remove_purchase_order_item_records($purchase_order_id);

                    // Save purchase order items with correct discount_amount
                    for ($i = 0; $i < sizeof($purchase_order_items_array); $i++) { 
                        $temp_purchase_order_item = (array)json_decode($purchase_order_items_array[$i]);
                        $temp_purchase_order_item['purchase_order_id'] = $purchase_order_id;

                        // CRITICAL: Ensure discount_amount is set correctly
                        if (isset($temp_purchase_order_item['discount_value']) && floatval($temp_purchase_order_item['discount_value']) > 0) {
                            $quantity = isset($temp_purchase_order_item['quantity']) ? floatval($temp_purchase_order_item['quantity']) : 1;
                            $cost = isset($temp_purchase_order_item['cost']) ? floatval($temp_purchase_order_item['cost']) : 0;
                            $discount_type = isset($temp_purchase_order_item['discount_type']) ? intval($temp_purchase_order_item['discount_type']) : 0;
                            $discount_value = floatval($temp_purchase_order_item['discount_value']);
                            
                            $subtotal = $quantity * $cost;
                            
                            if ($discount_type == 1) {
                                // Percentage discount
                                $temp_purchase_order_item['discount_amount'] = ($subtotal * $discount_value) / 100;
                            } else {
                                // Fixed amount discount
                                $temp_purchase_order_item['discount_amount'] = $discount_value;
                            }
                        } elseif (!isset($temp_purchase_order_item['discount_amount'])) {
                            $temp_purchase_order_item['discount_amount'] = 0;
                        }

                        if($temp_purchase_order_item['expiry_date'] == '' || $temp_purchase_order_item['expiry_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['expiry_date']);
                        else
                            $temp_purchase_order_item['expiry_date'] = date('Y-m-d', strtotime($temp_purchase_order_item['expiry_date']));

                        if($temp_purchase_order_item['mfg_date'] == '' || $temp_purchase_order_item['mfg_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['mfg_date']);
                        else
                            $temp_purchase_order_item['mfg_date'] = date('Y-m-d', strtotime($temp_purchase_order_item['mfg_date']));

                        if(isset($temp_purchase_order_item['product_remark'])) {
                            $temp_purchase_order_item['product_remark'] = $temp_purchase_order_item['product_remark'];
                        }

                        $this->purchase_order_model->add_purchase_order_item_record($temp_purchase_order_item);
                    }

                    // commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success', 'Purchase Order (Reference No. -' . $reference_no . ') is updated successfully.');
                    redirect('purchase_order', 'refresh');
                }
                else
                {
                    // rollback transaction
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('failure', 'Purchase Order (Reference No. -' . $reference_no . ') failed to update.');
                    redirect('purchase_order', 'refresh');
                }
            }
        }
        else
        {
            $id = base64_decode($id);

            if($id != null)
            {
                $data['purchase_order'] = $this->purchase_order_model->get_purchase_order_single_record($id);

                if($data['purchase_order'] != null)
                {
                    $data['purchase_order_items'] = $this->purchase_order_model->get_purchase_order_item_records($id);
                    $data['supplier'] = $this->supplier_model->get_records();
                    $data['supplier_detail'] = $this->supplier_model->get_single_record($data['purchase_order']->supplier_id);
                    $data['discounts'] = $this->discount_model->get_records();
                    $data['warehouses'] = $this->warehouse_model->get_records();
                    $data['company_setting'] = $this->company_settings_model->get_company_records(); 
                    $data['due_days_options'] = $this->db->where('status', 1)->get('due_days')->result();

                    $this->load->view('purchase_order/edit', $data);
                }
                else
                {
                    $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
                    redirect('purchase_order', 'refresh');
                }
            }
            else
            {
                $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
                redirect('purchase_order', 'refresh');
            }
        }
    }
}
public function add0505()
{
    if(!$this->permission_model->has_permission('add_purchase_order'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $this->form_validation->set_rules('purchase_order_date','Invoice Date','required');        
            $this->form_validation->set_rules('warehouse_id','Warehouse','required');
            $this->form_validation->set_rules('supplier_id','Supplier','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['warehouses']             = $this->warehouse_model->get_records();
                $data['supplier']               = $this->supplier_model->get_records();
                $data['company_setting']        = $this->company_settings_model->get_company_records();    
                $this->load->view('purchase_order/add',$data);
            }
            else
            {
                $purchase_order_date     = date('Y-m-d', strtotime($this->input->post('purchase_order_date')));
                $reference_no            = $this->purchase_order_model->get_lastest_sequence_number();
                $warehouse_id            = $this->input->post('warehouse_id');
                $supplier_id             = $this->input->post('supplier_id');

                $supplier                = $this->supplier_model->get_single_record($supplier_id);
                $supplier_gstin          = $supplier->gstin;
                $due_days                = $this->input->post('due_days');
                $payment_terms           = $this->input->post('payment_terms');
                
                if ($due_days) {
                    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($purchase_order_date)));
                } else {
                    $due_date = NULL;
                }

                // FIXED: Get freight data from individual fields (not JSON)
                $freight_amount          = $this->input->post('freight_amount') ?: 0;
                $freight_taxable_value   = $this->input->post('freight_taxable_value') ?: 0;
                $freight_sub_total       = $this->input->post('freight_sub_total') ?: 0;
                $freight_tax_rate        = $this->input->post('freight_tax_rate') ?: 0;
                $freight_tax_amount      = $this->input->post('freight_tax_amount') ?: 0;

                $total_taxable_value     = $this->input->post('total_taxable_value');
                $total_discount          = $this->input->post('total_discount');
                $total_tax               = $this->input->post('total_tax');
                $total                   = $this->input->post('total');
                $internal_note           = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note           = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition     = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
                $additional_cost_type    = $this->input->post('additional_cost_type');
                $additional_cost_amount  = $freight_amount;

                $document                = $this->input->post("document");

                if (!is_array($document)) {
                    $document = array($document);
                }

                $documents = implode(',', $document);

                $purchase_order_data = array(
                    "purchase_order_date"    => $purchase_order_date,
                    "reference_no"           => $reference_no,
                    "warehouse_id"           => $warehouse_id,
                    "supplier_id"            => $supplier_id,
                    "payment_terms"          => $payment_terms,
                    "due_days"               => $due_days,
                    "due_date"               => $due_date,
                    "supplier_gstin"         => $supplier_gstin,
                    "total_taxable_value"    => $total_taxable_value,    
                    "total_tax"              => $total_tax,
                    "total_discount"         => $total_discount,    
                    "total"                  => $total,
                    "internal_note"          => $internal_note,
                    "external_note"          => $external_note,
                    "document"               => $documents,
                    "terms_and_condition"    => $terms_and_condition,
                    "user_id"                => $this->session->userdata('user_id'),
                    "additional_cost_type"   => $additional_cost_type,
                    "additional_cost_amount" => $additional_cost_amount,
                    "freight_amount"         => $freight_amount,
                    "freight_taxable_value"  => $freight_taxable_value,
                    "freight_sub_total"      => $freight_sub_total,
                    "freight_tax_rate"       => $freight_tax_rate,
                    "freight_tax_amount"     => $freight_tax_amount
                );

                // Begin transaction
                $this->db->trans_begin();    

                if($id = $this->purchase_order_model->add_purchase_order_record($purchase_order_data))
                {
                    $entered_purchase_order = $this->purchase_order_model->get_purchase_order_single_record($id);
                    $supplier = $this->supplier_model->get_single_record($entered_purchase_order->supplier_id);

                    $log_data = array(
                        "user_id"     => $this->session->userdata("user_id"),
                        "module"      => "purchase_order",
                        "entry_id"    => $id,
                        "user_action" => 1,
                        "data"        => json_encode((array)$entered_purchase_order),
                        "description" => 'Purchase Order (Reference No. -' . $reference_no . ') is added successfully.'
                    );
                    $this->log_data_model->add_record($log_data);

                    $purchase_order_items = $this->input->post('purchase_order_items');
                    $purchase_order_items_array = explode("|", $this->input->post('purchase_order_items'));

                    for ($i = 0; $i < sizeof($purchase_order_items_array); $i++) { 
                        $temp_purchase_order_item = (array)json_decode($purchase_order_items_array[$i]);
                        $temp_purchase_order_item['purchase_order_id'] = $id;

                        if($temp_purchase_order_item['expiry_date'] == '' || $temp_purchase_order_item['expiry_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['expiry_date']);
                        else
                            $temp_purchase_order_item['expiry_date'] = date('Y-m-d', strtotime($temp_purchase_order_item['expiry_date']));

                        if($temp_purchase_order_item['mfg_date'] == '' || $temp_purchase_order_item['mfg_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['mfg_date']);
                        else
                            $temp_purchase_order_item['mfg_date'] = date('Y-m-d', strtotime($temp_purchase_order_item['mfg_date']));

                        if(isset($temp_purchase_order_item['product_remark'])) {
                            $temp_purchase_order_item['product_remark'] = $temp_purchase_order_item['product_remark'];
                        }

                        $this->purchase_order_model->add_purchase_order_item_record($temp_purchase_order_item);
                    }

                    // commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success', 'Purchase Order (Reference No. -' . $reference_no . ') is added successfully.');
                    redirect('purchase_order', 'refresh');
                }
                else
                {
                    // rollback transaction
                    $this->db->trans_rollback();

                    $this->session->set_flashdata('failure', 'Purchase Order (Reference No. -' . $reference_no . ') failed to add.');
                    redirect('purchase_order', 'refresh');
                }
            }
        }
        else
        {
            $data['warehouses']          = $this->warehouse_model->get_records();
            $data['supplier']            = $this->supplier_model->get_records();
            $data['company_setting']     = $this->company_settings_model->get_company_records();
            $data['due_days_options']    = $this->db->where('status', 1)->get('due_days')->result();
            $this->load->view('purchase_order/add', $data);
        }
    }    
}

public function edit0505($id = null)
{
    if(!$this->permission_model->has_permission('edit_purchase_order'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $purchase_order_id = $this->input->post('id');
            $old_purchase_order = $this->purchase_order_model->get_purchase_order_single_record($purchase_order_id);

            $this->form_validation->set_rules('purchase_order_date','Invoice Date','required');        
            $this->form_validation->set_rules('supplier_id','Supplier','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['supplier']        = $this->supplier_model->get_records();
                $data['warehouses']      = $this->warehouse_model->get_records();
                $data['company_setting'] = $this->company_settings_model->get_company_records();
                $this->load->view('purchase_order/edit', $data);
            }
            else
            {
                $purchase_order_date = date('Y-m-d', strtotime($this->input->post('purchase_order_date')));
                $due_days = $this->input->post('due_days');
                $payment_terms = $this->input->post('payment_terms');
                
                if ($due_days) {
                    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($purchase_order_date)));
                } else {
                    $due_date = NULL;
                }

                $supplier_id = $this->input->post('supplier_id');
                $supplier = $this->supplier_model->get_single_record($supplier_id);
                $supplier_gstin = $supplier->gstin;
                $warehouse_id = $this->input->post('warehouse_id');
                $reference_no = $this->input->post('reference_no');
            
                // FIXED: Get freight data from individual fields (not JSON)
                $freight_amount = $this->input->post('freight_amount') ?: 0;
                $freight_taxable_value = $this->input->post('freight_taxable_value') ?: 0;
                $freight_sub_total = $this->input->post('freight_sub_total') ?: 0;
                $freight_tax_rate = $this->input->post('freight_tax_rate') ?: 0;
                $freight_tax_amount = $this->input->post('freight_tax_amount') ?: 0;

                $total_taxable_value = $this->input->post('total_taxable_value');
                $total_discount = $this->input->post('total_discount');
                $total_tax = $this->input->post('total_tax');
                $total = $this->input->post('total');
                $internal_note = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

                $document = $this->input->post("document");

                if (!is_array($document)) {
                    $document = array($document);
                }

                $documents = implode(',', $document);

                $additional_cost_type = $this->input->post('additional_cost_type');
                $additional_cost_amount = $freight_amount;

                $purchase_order_data = array(
                    "purchase_order_date"    => $purchase_order_date,
                    "supplier_id"            => $supplier_id,
                    "supplier_gstin"         => $supplier_gstin,
                    "warehouse_id"           => $warehouse_id,
                    "payment_terms"          => $payment_terms,
                    "due_days"               => $due_days,
                    "due_date"               => $due_date,
                    "total_taxable_value"    => $total_taxable_value,    
                    "total_tax"              => $total_tax,
                    "total_discount"         => $total_discount,    
                    "total"                  => $total,
                    "internal_note"          => $internal_note,
                    "external_note"          => $external_note,
                    "terms_and_condition"    => $terms_and_condition,
                    "document"               => $documents,
                    "user_id"                => $this->session->userdata('user_id'),
                    "additional_cost_type"   => $additional_cost_type,
                    "additional_cost_amount" => $additional_cost_amount,
                    "freight_amount"         => $freight_amount,
                    "freight_taxable_value"  => $freight_taxable_value,
                    "freight_sub_total"      => $freight_sub_total,
                    "freight_tax_rate"       => $freight_tax_rate,
                    "freight_tax_amount"     => $freight_tax_amount
                );

                // begin transaction
                $this->db->trans_begin();    

                if($this->purchase_order_model->edit_purchase_order_record($purchase_order_data, $purchase_order_id))
                {
                    $entered_purchase_order = $this->purchase_order_model->get_purchase_order_single_record($purchase_order_id);
                    $supplier = $this->supplier_model->get_single_record($entered_purchase_order->supplier_id);

                    $log_data = array(
                        "user_id"     => $this->session->userdata("user_id"),
                        "module"      => "purchase_order",
                        "user_action" => 2,
                        "b_data"      => json_encode((array)$old_purchase_order),
                        "data"        => json_encode((array)$entered_purchase_order),
                        "description" => 'Purchase Order (Reference No. -' . $reference_no . ') is updated successfully.'
                    );
                    $this->log_data_model->add_record($log_data);
                    $this->purchase_order_model->remove_purchase_order_item_records($purchase_order_id);

                    $purchase_order_items = $this->input->post('purchase_order_items');
                    $purchase_order_items_array = explode("|", $this->input->post('purchase_order_items'));

                    for ($i = 0; $i < sizeof($purchase_order_items_array); $i++) { 
                        $temp_purchase_order_item = (array)json_decode($purchase_order_items_array[$i]);
                        $temp_purchase_order_item['purchase_order_id'] = $purchase_order_id;

                        if($temp_purchase_order_item['expiry_date'] == '' || $temp_purchase_order_item['expiry_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['expiry_date']);
                        else
                            $temp_purchase_order_item['expiry_date'] = date('Y-m-d', strtotime($temp_purchase_order_item['expiry_date']));

                        if($temp_purchase_order_item['mfg_date'] == '' || $temp_purchase_order_item['mfg_date'] == '0000-00-00')
                            unset($temp_purchase_order_item['mfg_date']);
                        else
                            $temp_purchase_order_item['mfg_date'] = date('Y-m-d', strtotime($temp_purchase_order_item['mfg_date']));

                        if(isset($temp_purchase_order_item['product_remark'])) {
                            $temp_purchase_order_item['product_remark'] = $temp_purchase_order_item['product_remark'];
                        }

                        $this->purchase_order_model->add_purchase_order_item_record($temp_purchase_order_item);
                    }

                    // commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success', 'Purchase Order (Reference No. -' . $reference_no . ') is updated successfully.');
                    redirect('purchase_order', 'refresh');
                }
                else
                {
                    // rollback transaction
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('failure', 'Purchase Order (Reference No. -' . $reference_no . ') failed to update.');
                    redirect('purchase_order', 'refresh');
                }
            }
        }
        else
        {
            $id = base64_decode($id);

            if($id != null)
            {
                $data['purchase_order'] = $this->purchase_order_model->get_purchase_order_single_record($id);

                if($data['purchase_order'] != null)
                {
                    $data['purchase_order_items'] = $this->purchase_order_model->get_purchase_order_item_records($id);
                    $data['supplier'] = $this->supplier_model->get_records();
                    $data['supplier_detail'] = $this->supplier_model->get_single_record($data['purchase_order']->supplier_id);
                    $data['discounts'] = $this->discount_model->get_records();
                    $data['warehouses'] = $this->warehouse_model->get_records();
                    $data['company_setting'] = $this->company_settings_model->get_company_records(); 
                    $data['due_days_options'] = $this->db->where('status', 1)->get('due_days')->result();

                    $this->load->view('purchase_order/edit', $data);
                }
                else
                {
                    $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
                    redirect('purchase_order', 'refresh');
                }
            }
            else
            {
                $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
                redirect('purchase_order', 'refresh');
            }
        }
    }
}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_purchase_order'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 								= $this->input->post('id');
			$purchase_order 		= $this->purchase_order_model->get_purchase_order_single_record($id);
			
			$data = array('delete_status' => 1);
			if($this->purchase_order_model->edit_purchase_order_record($data,$id))
			{
				$supplier 		= $this->supplier_model->get_single_record($purchase_order->supplier_id);

				$this->session->set_flashdata('success', 'Purchase Order (Reference No. -'.$purchase_order->reference_no.') is deleted successfully.');
				redirect('purchase_order','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Purchase Order (Reference No. -'.$purchase_order->reference_no.') is failed to delete.');
				redirect('purchase_order','refresh');
			}	

			
			
		}
	
	}

	public function view($id = null)
	{
		if (!$this->input->is_ajax_request()) 
		{
			if($id != null)
			{

				$id = base64_decode($id);

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
					
		

					$this->load->view('purchase_order/view',$data);
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
			$response = array();

			$purchase_order_view_data['purchase_order'] 				= $this->purchase_order_model->get_purchase_order_single_record($id);
			$purchase_order_view_data['supplier_detail'] 	= $this->customer_model->get_single_record($purchase_order_view_data['purchase_order']->supplier_id);	
			
			$response['purchase_order_view']	 						= $this->load->view('purchase_order/ajax/view',$purchase_order_view_data,TRUE);
		
			echo json_encode($response);
		}
	}


	public function pdf($id = null)
	{
		
		if($id != null)
		{
			$id 														= base64_decode($id);		
			$data['purchase_order'] 				= $this->purchase_order_model->get_purchase_order_single_record($id);
			$data['purchase_order_items'] 	= $this->purchase_order_model->get_purchase_order_item_records($id);
			$data['warehouses'] 						= $this->warehouse_model->get_records();
			$data['warehouse_detail']				= $this->warehouse_model->get_single_record($data['purchase_order']->warehouse_id);
			$data['supplier'] 							= $this->supplier_model->get_records();
			$data['supplier_detail']				= $this->supplier_model->get_single_record($data['purchase_order']->supplier_id);
			$data['discounts']							= $this->discount_model->get_records();
			$data['company_setting']				= $this->company_settings_model->get_company_records();	

			$html = $this->load->view('purchase_order/pdf',$data,true);
			
			$this->pdf->loadHtml($html);
			$this->pdf->render();
			$this->pdf->stream("Purchase Order-".$data['purchase_order']->reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('purchase_order','refresh');
		}
	}

  public function import_product()
	{
			if (!$this->permission_model->has_permission('import_product')) {
					$this->load->view('errors/html/error_restricted');
			} else {
					if ($this->input->server('REQUEST_METHOD') === 'POST') {
							// Check if 'csvfile' file input exists
							if (isset($_FILES['csvfile'])) {
									if (is_uploaded_file($_FILES['csvfile']['tmp_name'])) {
											// Parse CSV
											$csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);
											// Clean the keys from BOM and other unwanted characters
											$csvData = $this->cleanKeys($csvData);

											$ProductData = array();
											$supplier_id = $this->input->post('supplier_id'); 
											$warehouse_id = $this->input->post('warehouse_id'); 
											$discount = $this->discount_model->get_valid_records();
											
											$i = 0;
											$no_of_row_in_csv = sizeof($csvData);
											$not_matched_products = array();
											
											if (!empty($csvData)) {
													foreach ($csvData as $row) {
															$matchedProduct = $this->product_core_model->get_records_by_pid($row['PID']);
															if ($matchedProduct != null) {
																	$i++;
																	$product = $this->product_core_model->get_matched_single_record($matchedProduct->id);
																	$uom = $this->utility_model->get_records_by_field('uom', 'uom', $row['UOM']);
																	
																	// Populate product data with imported values
																	$product->cost = $row['Cost'];
																	$product->price = $row['Price'];
																	$product->selling_price = $row['SellingPrice'];
																	$product->quantity = $row['QTY'];
																	$product->uom_id = $uom->id;

																	if($row['QTY'] != '' && $row['QTY'] > 0)
																		$ProductData[] = $product;
															} else {
																	$not_matched_products[] = $row['Name'].'_'.$row['PID'];                        
															}
													}
													
													if ($i > 0) {
															$responseData = array(
																	'code' => 1,
																	'ProductData' => $ProductData,
																	'discount' => $discount,
																	'message' => ''
															);

															if ($i != $no_of_row_in_csv) {
																	$responseData['message'] = implode("<br/>", $not_matched_products) . ' <br/> products are not exist in system';
															}
													} else {
															$responseData = array(
																	'code' => 0,
																	'message' => 'No record(s) are found in system. Please check PID.'
															);
													}
													echo json_encode($responseData);
											} else {
													echo json_encode(array('code' => 0, 'message' => 'No record(s) are found in system. Please check PID.'));
											}
									} else {
											echo json_encode(array('code' => 0, 'message' => 'Failed to upload CSV file.'));
									}
							} else {
									echo json_encode(array('code' => 0, 'message' => 'CSV file not present.'));
							}
					} else {
							if ($this->input->is_ajax_request()) {
									$data['product_categories'] = $this->product_category_model->get_records();
									$data['uoms'] = $this->uom_model->get_records();

									$response = array(
											'code' => 1,
											'import_product_modal_body' => $this->load->view('purchase_order/ajax/import_product_modal_body', $data, TRUE)
									);

									echo json_encode($response);
							} else {
									$this->load->view('errors/html/error_restricted');
							}
					}
			}
	}

	private function cleanKeys($dataArray) {
			$cleanedArray = [];
			foreach ($dataArray as $row) {
					$cleanedRow = [];
					foreach ($row as $key => $value) {
							$cleanKey = preg_replace('/\xEF\xBB\xBF/', '', $key); // Stripping BOM
							$cleanedRow[$cleanKey] = $value;
					}
					$cleanedArray[] = $cleanedRow;
			}
			return $cleanedArray;
	}


	public function export_products()
  {
      $this->db->select('p.pid as "PID",
                        p.name as Name,
                        p.cost as Cost,
                        p.price as Price,
                        p.selling_price as SellingPrice,
                        "" as "QTY",
                        (SELECT COALESCE(SUM(wp.quantity), 0) 
                          FROM warehouse_products wp 
                          WHERE wp.product_id = p.id) as "AvailableQuantity",
                        u.uom as "UOM"'); // Assuming 'name' is the field in uom table representing the unit of measure

      $this->db->from('product p');
      $this->db->join('uom u', 'u.id = p.uom_id', 'left'); // Adjust according to your actual foreign key relationship
      // $this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');
      $this->db->order_by('p.pid', 'asc');
      $query = $this->db->get();

      $this->load->dbutil();
      $data = $this->dbutil->csv_from_result($query);

      $this->load->helper('download');
      force_download("AllProductWithPID.CSV", $data);
  }


	public function send_email($purchase_order_id = null)
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

				$purchase_order_id 		= $this->input->post("purchase_order_id");

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

					$purchase_order									= $this->purchase_order_model->get_purchase_order_single_record($purchase_order_id);
					$purchase_order_items 					= $this->purchase_order_model->get_purchase_order_item_records($purchase_order->id);
					$supplier_detail								= $this->supplier_model->get_single_record($purchase_order->supplier_id);
					$company_setting								= $this->company_settings_model->get_company_records();	
	
					$data['supplier'] 							= $this->supplier_model->get_records();
					$data['supplier_detail']				= $supplier_detail;
					$data['purchase_order']					= $purchase_order;
					$data['purchase_order_items'] 	= $purchase_order_items;
					$data['company_setting']				= $company_setting;
					$data['discounts']							= $this->discount_model->get_records();
					$data['message'] 								= $email_data['message'];

				
					$to_cc = ($email_data['to_cc'] != '') ? explode(',',  $email_data['to_cc']) : array();

					$mail_data 											= array();
					
					$mail_data['From'] 							= 'zivaansolutions@gmail.com';
					$mail_data['FromName'] 					= $company_setting->company_name;
					$mail_data['AddReplyTo'] 				= 'zivaansolutions@gmail.com';
					$mail_data['ConfirmReadingTo']	= 'zivaansolutions@gmail.com';
					$mail_data['To']								= $to_mail;
					$mail_data['Cc']								= $to_cc;
					$mail_data['Subject'] 					= $subject;
					$mail_data['Body'] 							= $this->load->view('email_view' , $data, true);

			
					$html = $this->load->view('purchase_order/pdf', $data, true);

					$pdf_filename = "Purchase order-".$data['purchase_order']->reference_no.".pdf"; // Set your desired filename

					// Save the PDF file directly to the given path
					$pdf_path = FCPATH . 'assets/documents/' . $pdf_filename;
	
					
					$this->pdf->loadHtml($html);
					$this->pdf->set_paper('A4', $orientation ?? 'portrait'); 
					$this->pdf->render();

					// Save the rendered PDF content to the given path
					file_put_contents($pdf_path, $this->pdf->output());

					$response = array();

					if($to_mail != '')
					{
						if($this->email_model->send_mail($pdf_filename,$mail_data))
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
		
			$data['purchase_order'] 	= $this->purchase_order_model->get_purchase_order_single_record($purchase_order_id);
			$data['supplier']		= $this->supplier_model->get_single_record($data['purchase_order']->supplier_id);
			$data['company_setting']	= $this->company_settings_model->get_company_records();	

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
			
			$response['email_modal_body'] 	= $this->load->view('purchase_order/ajax/email_modal_body',$data,TRUE);

			
	  	echo json_encode($response);
		}
	}
	

		/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->purchase_order_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];
   

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

				if($this->permission_model->has_permission('send_mail'))
				{

      		$table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('send_email').'" class="btn btn-success btn-sm text-white email-modal" data-purchase_order_id="'.$item->id.'">
													<i class="fas fa-at"></i> Send mail
												</a>';
				}
				

				//View Button
			  if($this->permission_model->has_permission('view_purchase_order') || $this->permission_model->has_permission('view_all_purchase_order'))
				{	
					$table_body .= ' 
                            <a href="'.base_url('purchase_order/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="View purchase order">
                              <i class="fas fa-eye"></i> View
                            </a>      
                          ';
				}

				// Edit Button        
       	if($this->permission_model->has_permission('edit_purchase_order') || $this->permission_model->has_permission('edit_all_purchase_order'))
				{	
					$table_body .= ' 
          										<a href="'.base_url('purchase_order/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs"  data-tt="tooltip" title="Edit purchase order">
                                <i class="fas fa-edit"></i> Edit
                              </a>
                            ';
					
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_purchase_order') || $this->permission_model->has_permission('delete_all_purchase_order'))
				{	
					$table_body .= '
		                        <a href="#"  data-toggle="modal" data-target="#delete_purchase_order" data-tt="tooltip" title="Delete purchase order" class="btn btn-danger btn-xs delete_purchase_oder" data-purchase_order_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i> Delete
		                        </a>
													';
				}


        //Reference no
        $reference_no_html = '<a href="'.base_url('purchase_order/view/'.base64_encode($item->id)).'" data-tt="tooltip" title="'.$this->lang->line('purchase_order_view').'">'.$item->reference_no.'</a>';

				/* End Action column buttons*/

				
	        $row = array();
	       
		      $row[] = $reference_no_html;
		    
		      $row[] = date('d-m-Y', strtotime($item->purchase_order_date));
		      $row[] = $item->name;
		      $row[] = $item->company_name;
		      $row[] = number_format_i($item->total_discount);
		      $row[] = number_format_i($item->total_taxable_value);
		      $row[] = number_format_i($item->total_tax);
		      $row[] = number_format_i($item->total);
		    
					$row[] = $table_header.$table_body.$table_footer;    
					$data[] = $row;
			
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->purchase_order_model->count_all(),
                    "recordsFiltered" => $this->purchase_order_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

  public function purchase_order_delete_confirmation()
  {
  	$purchase_order_id 				= $this->input->post('purchase_order_id');
  	$data['purchase_order'] 	= $this->purchase_order_model->get_purchase_order_single_record($purchase_order_id);

  	$response 					= array();
  	$response['purchase_order_delete_modal_body'] 	= $this->load->view('purchase_order/ajax/purchase_order_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	
	/************************************** End Dynamic Datatable function ***************************************/

	public function upload_documents() 
	{
    if (isset($_FILES["upload_document"])) 
		{
			$data['company_setting'] 	= $this->company_settings_model->get_company_records();

			$cid = $data['company_setting']->cid; 

			$targetDir = "./assets/documents/$cid/purchase_order/";

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

