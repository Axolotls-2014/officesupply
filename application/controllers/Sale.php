<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Sale extends MY_Controller {

	private $pdf;
	private $is_customer;

	public function __construct()
	{
		parent::__construct();
		$this->is_customer = $this->customer_model->is_loggedin_user_is_customer();
		$this->pdf = new Dompdf();
// 		error_reporting(E_ALL);
//         ini_set('display_errors', 1);
	}
	
	public function index()
	{
    $this->config->load('custom_config');
    
		if(!$this->permission_model->has_permission('list_sale') && !$this->permission_model->has_permission('list_all_sale'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$customer_id = null;
			if($this->is_customer){
				$user_id = csession('user_id');
				$customer = $this->utility_model->get_records_by_field('customer','user_id',$user_id,$row = true,$check_delete_status = false);
				$customer_id = $customer->id;
			}	
			
			$data['customer'] 		= $this->customer_model->get_records();
			$data['sales'] 			= $this->sale_model->get_sale_records();
			$data['total_sale']		= $this->transaction_model->get_total_transaction_amount_by_customer($customer_id, SALE_MODULE, SALE_TRANSACTION_TYPE);			
			$data['total_amount_received']	= $this->transaction_model->get_total_transaction_amount_by_customer($customer_id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount($customer_id,SALE_MODULE,CREDIT_TRANSACTION_TYPE);
			$data['total_amount_tds']	= $this->transaction_model->get_total_transaction_amount_by_customer($customer_id, SALE_MODULE, TDS_TRANSACTION_TYPE);

// 			$log_data = array(
//                   "user_id"     => $this->session->userdata("user_id"),
//                   "module"      => "sale",
//                   "user_action" => 0,
//                   "description"	=> "User has viewed list of sale."
//                 );

// 			$this->log_data_model->add_record($log_data);

			$this->load->view('sale/list',$data);
		}
	}

public function add()
{
    
    if(!$this->permission_model->has_permission('add_sale'))
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

            $this->load->helper('common');
            $reference_no = get_next_sale_main_reference();
            $this->form_validation->set_rules('invoice_date','Invoice Date','required');        
            $this->form_validation->set_rules('customer_id','Customer','required');
            $this->form_validation->set_rules('warehouse_id','Warehouse','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['customers']          = $this->customer_model->get_records();
                $data['warehouses']         = $this->warehouse_model->get_records();
                $data['company_setting']    = $this->company_settings_model->get_company_records();    
                $this->load->view('sale/add',$data);
            }
            else
            {
                // Get profit data from form
                $profit_data            = json_decode($this->input->post('profit_data'), true);
                $total_purchase_cost    = isset($profit_data['total_purchase_cost']) ? $profit_data['total_purchase_cost'] : 0;
                $additional_cost_amount = isset($profit_data['freight_amount']) ? $profit_data['freight_amount'] : 0;
                $total_selling_price    = isset($profit_data['total_selling_price']) ? $profit_data['total_selling_price'] : 0;
                $gross_profit_loss      = isset($profit_data['gross_profit_loss']) ? $profit_data['gross_profit_loss'] : 0;
                $profit_margin          = isset($profit_data['profit_margin']) ? $profit_data['profit_margin'] : 0;
                
                $freightData = json_decode($this->input->post('freight_data'), true);
                
                if (!$freightData) {
                    $freightData = [
                        'freight_selling_price' => 0,
                        'freight_taxable_value' => 0,
                        'freight_sub_total'     => 0,
                        'freight_tax_rate'      => 0,
                        'freight_tax_amount'    => 0
                    ];
                }                
                
                $due_days                    = $this->input->post('due_days');
                $invoice_date                = date('Y-m-d', strtotime($this->input->post('invoice_date')));
                
                if ($due_days) {
                    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($invoice_date)));
                } else {
                    $due_date = NULL;
                }
                
                $payment_terms = $this->input->post('payment_terms'); 
                
                $warehouse_id                = $this->input->post('warehouse_id');
                $reference_no                = $reference_no;
                $customer_id                = $this->input->post('customer_id');
                $customer                    = $this->customer_model->get_single_record($customer_id);
                $customer_gstin             = $customer->gstin;
                $proforma_invoice_id_array  = $this->input->post('proforma_invoice_id');

                if (!is_array($proforma_invoice_id_array)) {
                    $proforma_invoice_id_array = !empty($proforma_invoice_id_array) ? explode(',', $proforma_invoice_id_array) : [];
                }

                $proforma_invoice_id                        = implode(",", $proforma_invoice_id_array);
                $selected_shipping_address_id               = $this->input->post('shipping_address_select');
                $customer_shipping_country_id               = $this->input->post('customer_shipping_country_id');
                $customer_shipping_state_id                 = $this->input->post('customer_shipping_state_id');
                $customer_shipping_city_id                     = $this->input->post('customer_shipping_city_id');
                $customer_shipping_address                     = $this->input->post('customer_shipping_address');
                $customer_shipping_pincode                     = $this->input->post('customer_shipping_pincode');
                $rcm                                         = $this->input->post('rcm');
                $total_taxable_value                        = $this->input->post('total_taxable_value');
                $tds                                         = $this->input->post('tds');
                $total_discount                             = $this->input->post('total_discount');
                $total_tax                                     = $this->input->post('total_tax');
                $total                                         = $this->input->post('total');
                $internal_note                                 = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note                                 = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $bank_detail                                 = nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition                        = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
                $lori_no                                     = $this->input->post('lori_no');
                $carrier                                     = $this->input->post('carrier');
                $vehicle_no                                 = $this->input->post('vehicle_no');
                $purchase_order_no                          = $this->input->post('purchase_order_no');
                $purchase_order_date                        = $this->input->post('purchase_order_date');
                $dispatch_date                              = $this->input->post('dispatch_date');
                $lut_no                                      = $this->input->post('lut_no');
                $ewaybill_no                                 = $this->input->post('ewaybill_no');
                $additional_case                             = $this->input->post('additional_case');
                $ewaybill_dispatch_from                      = $this->input->post('ewaybill_dispatch_from');
                $ewaybill_mode_of_transportation             = $this->input->post('ewaybill_mode_of_transportation');
                $ewaybill_subtype                             = $this->input->post('ewaybill_subtype');
                $ewaybill_doctype                             = $this->input->post('ewaybill_doctype');
                $ewaybill_transporter_name                     = $this->input->post('ewaybill_transporter_name');
                $ewaybill_transporter_gstin                  = $this->input->post('ewaybill_transporter_gstin');
                $ewaybill_distance_of_transportation         = $this->input->post('ewaybill_distance_of_transportation');
                $ewaybill_transporter_doc_no                 = $this->input->post('ewaybill_transporter_doc_no');
                $ewaybill_vehicle_no                         = $this->input->post('vehicle_no');
                $ewaybill_vehicle_type                         = $this->input->post('ewaybill_vehicle_type');
                $available_credit                             = $this->input->post('available_credit');
                $document                                     = $this->input->post("document");
                $no_of_boxes                                  = $this->input->post('no_of_boxes', 1);
                $no_of_boxes                                  = max(1, (int)$no_of_boxes); // Ensure at least 1 box


                if (!is_array($document)){
                    $document = array($document);
                }
              
                require_once FCPATH . 'application/libraries/phpqrcode/qrlib.php'; 
                 
                $last_sale_id = $this->db->select_max('id')->get('sale')->row()->id;
                
                $new_sale_id = $last_sale_id + 1;
                
                $encoded_id = base64_encode($new_sale_id);
                
                $qr_data = base_url('Qr_sale/view_qr_sale/' . $encoded_id);
                
                $qr_filename = uniqid('qr_') . '.png';
                $qr_path = FCPATH . 'uploads/qr_codes/' . $qr_filename;
                
                if (!is_dir(FCPATH . 'uploads/qr_codes')) {
                    if (!mkdir(FCPATH . 'uploads/qr_codes', 0777, true)) {
                        log_message('error', 'Failed to create uploads/qr_codes directory');
                        exit;
                    }
                }
                
                QRcode::png($qr_data, $qr_path, QR_ECLEVEL_L, 10, 2);
                
               if (file_exists($qr_path)) {
                    $image_info = getimagesize($qr_path); // Alternative to exif_imagetype()
                    
                    if ($image_info && $image_info[2] === IMAGETYPE_PNG) { // [2] contains the image type
                        log_message('info', "QR Code successfully generated and saved: $qr_path");
                        $sale_data['qr'] = $qr_filename;
                    } else {
                        log_message('error', 'QR Code generation failed or is not a valid image.');
                    }
                }
                
            $box_qr_data = [];
            for ($i = 1; $i <= $no_of_boxes; $i++) {
                $random_no = mt_rand(100000, 999999); // 6-digit random number
                $box_encoded_id = base64_encode($new_sale_id.'_'.$random_no);
                
                $box_qr_data[] = [
                    'box_number' => $i,
                    'random_no' => $random_no,
                    'qr_filename' => uniqid('box_qr_').'.png'
                ];
                
                // Generate QR code
                $box_qr_path = FCPATH.'uploads/qr_codes/boxes/'.$box_qr_data[$i-1]['qr_filename'];
                QRcode::png(
                    base_url('Qr_sale/view_box/'.$box_encoded_id), 
                    $box_qr_path, 
                    QR_ECLEVEL_L, 
                    10, 
                    2
                );
            }
                
                // Serialize the box QR codes array for storage
                $sale_data['box_qr_codes'] = serialize($box_qr_codes);
                $sale_data['no_of_boxes'] = $no_of_boxes;
                $documents = implode(',', $document);

                $sale_data = array(
                    "invoice_date"                    => $invoice_date,
                    "due_date"                        => $due_date,
                    "payment_terms"                   => $payment_terms,
                    "reference_no"                    => $reference_no,
                    "proforma_invoice_id"            => $proforma_invoice_id,
                    "warehouse_id"                    => $warehouse_id,
                    "customer_id"                    => $customer_id,
                    "customer_gstin"                => $customer_gstin,
                    
                    "selected_shipping_address_id" => $selected_shipping_address_id,
                    "customer_shipping_country_id"            => $customer_shipping_country_id,
                    "customer_shipping_state_id"                => $customer_shipping_state_id,
                    "customer_shipping_city_id"                    => $customer_shipping_city_id,
                    "customer_shipping_address"                    => $customer_shipping_address,
                    "customer_shipping_pincode"                    => $customer_shipping_pincode,
                    "rcm"                                    => $rcm,
                    "total_taxable_value" => $total_taxable_value,    
                    "tds"                                 => $tds,    
                    "total_tax"                        => $total_tax,
                    "total_discount"                 => $total_discount,    
                    "total"                                 => $total,
                    "internal_note"                => $internal_note,
                    "external_note"                 => $external_note,
                    "bank_detail"                 => $bank_detail,
                    "terms_and_condition" => $terms_and_condition,
                    "document"                 => $documents,
                    "lori_no"                         => $lori_no,
                    "carrier"                         => $carrier,
                    
                    "vehicle_no"                     => $vehicle_no,
                    "purchase_order_no" => $purchase_order_no,
                    "purchase_order_date" => $purchase_order_date,
                    "dispatch_date"     => $dispatch_date,
                    "due_days"                      => $due_days,
                    'lut_no'                        => $lut_no,
                    "ewaybill_no"                 => $ewaybill_no,
                    "additional_case"         => $additional_case,
                    "qr" => $qr_filename, // Main QR code
                    'no_of_boxes' => $no_of_boxes,
                    'box_qr_data' => json_encode($box_qr_data), // Number of boxes

                    "additional_cost_type" => $this->input->post('additional_cost_type'),
                    "additional_cost_amount" => $additional_cost_amount,
                    "total_purchase_cost"    => $total_purchase_cost,
                    "total_selling_price"    => $total_selling_price,
                    "gross_profit_loss"     => $gross_profit_loss,
                    "profit_margin"         => $profit_margin,
                    "user_id"                         => $this->session->userdata('user_id'),
                    
                    'freight_selling_price' => $freightData['freight_selling_price'],
                    'freight_taxable_value' => $freightData['freight_taxable_value'],
                    'freight_sub_total' => $freightData['freight_sub_total'],
                    'freight_tax_rate' => $freightData['freight_tax_rate'],
                    'freight_tax_amount' => $freightData['freight_tax_amount']                  
                    
                );

                if (!empty($ewaybill_dispatch_from)) {
                    $sale_data['ewaybill_dispatch_from'] = $ewaybill_dispatch_from;
                }
                
                if (!empty($ewaybill_mode_of_transportation)) {
                    $sale_data['ewaybill_mode_of_transportation'] = $ewaybill_mode_of_transportation;
                }
                
                if (!empty($ewaybill_subtype)) {
                    $sale_data['ewaybill_subtype'] = $ewaybill_subtype;
                }
                
                if (!empty($ewaybill_doctype)) {
                    $sale_data['ewaybill_doctype'] = $ewaybill_doctype;
                }
                
                if (!empty($ewaybill_transporter_name)) {
                    $sale_data['ewaybill_transporter_name'] = $ewaybill_transporter_name;
                }
                
                if (!empty($ewaybill_transporter_gstin)) {
                    $sale_data['ewaybill_transporter_gstin'] = $ewaybill_transporter_gstin;
                }
                
                if (!empty($ewaybill_distance_of_transportation)) {
                    $sale_data['ewaybill_distance_of_transportation'] = $ewaybill_distance_of_transportation;
                }
                
                if (!empty($ewaybill_transporter_doc_no)) {
                    $sale_data['ewaybill_transporter_doc_no'] = $ewaybill_transporter_doc_no;
                }
                
                if (!empty($ewaybill_vehicle_no)) {
                    $sale_data['ewaybill_vehicle_no'] = $ewaybill_vehicle_no;
                }
                
                if (!empty($ewaybill_vehicle_type)) {
                    $sale_data['ewaybill_vehicle_type'] = $ewaybill_vehicle_type;
                }

                $lori_date                 = $this->input->post('lori_date');
                if($lori_date != '')
                    $sale_data['lori_date'] = date('Y-m-d',strtotime($lori_date));

                $ewaybill_transporter_doc_date                 = $this->input->post('ewaybill_transporter_doc_date');
                if($ewaybill_transporter_doc_date != '')
                    $sale_data['ewaybill_transporter_doc_date'] = date('Y-m-d',strtotime($ewaybill_transporter_doc_date));

                // begin transaction
                $this->db->trans_begin();
                
                if($id = $this->sale_model->add_sale_record($sale_data))
                {
                    for ($i=0; $i < sizeof($proforma_invoice_id_array); $i++) { 
                        $this->proforma_invoice_model->edit_proforma_invoice_record(array('sale_id'=>$id),$proforma_invoice_id_array[$i]);    
                    }
                    $entered_sale        = $this->sale_model->get_sale_single_record($id);
                    $customer             = $this->customer_model->get_single_record($entered_sale->customer_id);

                    // update the ledgers
                    
                    /****************************** Start Sale Ledger ***********************************/

                    $sale_ledger                         = $this->ledger_model->get_single_record(SALE_LEDGER);
                    $updated_sale_ledger                 = array('closing_balance' => ($sale_ledger->closing_balance + $total));
                    $this->ledger_model->edit_record($updated_sale_ledger,$sale_ledger->id);

                    /****************************** End Sale Ledger ***********************************/

                    /****************************** Start Customer Ledger ***********************************/

                    $customer_ledger             = $this->ledger_model->get_single_record($customer->ledger_id);
                    $updated_customer_ledger     = array('closing_balance' => ($customer_ledger->closing_balance + $total));
                    $this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

                    /****************************** End Customer Ledger ***********************************/

                    /****************************** Start TDS Ledger ***********************************/

                    if($tds > 0)
                    {
                        $tds_ledger             = $this->ledger_model->get_single_record(TDS_LEDGER);
                        $updated_tds_ledger     = array('closing_balance' => ($tds_ledger->closing_balance + $tds));
                        $this->ledger_model->edit_record($updated_tds_ledger,$tds_ledger->id);

                        $customer_ledger                 = $this->ledger_model->get_single_record($customer->ledger_id);
                        $updated_customer_ledger         = array('closing_balance' => ($customer_ledger->closing_balance - $tds));
                        $this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);
                    }

                    
                    /****************************** End TDS Ledger ***********************************/

                    /****************************** Add Transaction entry *********************************/
                     
                    $transaction_header = array(
                        "entry_id"                    =>  $id,
                        "module"                      =>  SALE_MODULE, //S
                        "type"                        =>    SALE_TRANSACTION_TYPE, //S
                        "amount"                      =>    $total,
                        "voucher_date"                =>    $invoice_date,
                        "from_account"                =>    SALE_LEDGER, //2
                        "to_account"                  =>    $customer->ledger_id,
                        "reference_no"                =>    $reference_no,
                        "warehouse_id"                =>  $warehouse_id
                    );
                    
                    
                    if($transaction_id = $this->transaction_model->add_record($transaction_header))
                    {
                        $from_transaction_detail = array(
                            "transaction_id"          => $transaction_id,
                            "voucher_type"             => 'C', //Credit
                            "ledger_id"                => SALE_LEDGER, //2
                            "dr_amount"                => $total
                        );
                        // transaction detail record
                        $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                        $to_transaction_detail = array(
                            "transaction_id"     => $transaction_id,
                            "voucher_type"        => 'D', //DEBIT
                            "ledger_id"                => $customer->ledger_id,
                            "cr_amount"                => $total
                        );
                        // transaction detail record
                        $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                    }

                    /**************************************************************************************/


                    /****************************** Add TDS Transaction entry *********************************/

                    if($tds > 0)
                    {
                        $transaction_header = array(
                            "entry_id"                      =>  $id,
                            "module"                    =>  SALE_MODULE,
                            "type"                        =>    TDS_TRANSACTION_TYPE,
                            "amount"                    =>    $tds,
                            "voucher_date"                =>    $invoice_date,
                            "from_account"                =>    $customer->ledger_id,
                            "to_account"                =>    TDS_LEDGER,
                            "reference_no"                =>    $reference_no,
                            "warehouse_id"              => $warehouse_id
                        );
                        if($transaction_id = $this->transaction_model->add_record($transaction_header))
                        {
                            $from_transaction_detail = array(
                                "transaction_id"     => $transaction_id,
                                "voucher_type"        => 'C',
                                "ledger_id"                => $customer->ledger_id,
                                "dr_amount"                => $tds
                            );
                            // transaction detail record
                            $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                            $to_transaction_detail = array(
                                "transaction_id"     => $transaction_id,
                                "voucher_type"        => 'D',
                                "ledger_id"                => TDS_LEDGER,
                                "cr_amount"                => $tds
                            );
                            // transaction detail record
                            $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                        }    
                    }
                    
                    /**************************************************************************************/

                    $log_data = array(
                        "user_id"         => $this->session->userdata("user_id"),
                        "module"            => "sale",
                        "entry_id"        => $id,
                        "user_action"    => 1,
                        "data"                => json_encode((array)$entered_sale),
                        "description" => 'Sales (Reference No. -'  .$reference_no .') is added successfully.'
                    );
                    $this->log_data_model->add_record($log_data);

                    $sale_items                         = $this->input->post('sale_items');
                    $sale_items_array             = explode("|", $this->input->post('sale_items'));
                    

                    for ($i=0; $i < sizeof($sale_items_array) ; $i++) { 
                                
                        $temp_sale_item = (array)json_decode($sale_items_array[$i]);
                                        // Skip if this is a freight item (we already processed it)
                        // Skip if this is a freight item (we already processed it)
                        if (isset($temp_sale_item['is_freight']) && $temp_sale_item['is_freight']) {
                            continue;
                        }
                        
                        // Skip if quantity is 0, null, or empty
                        // if (empty($temp_sale_item['quantity']) || $temp_sale_item['quantity'] <= 0) {
                        //     continue;
                        // }
                        if (!isset($temp_sale_item['quantity']) && isset($temp_sale_item['qty'])) {
    $temp_sale_item['quantity'] = $temp_sale_item['qty'];
}
                        
                        if (!isset($temp_sale_item['quantity']) || (float)$temp_sale_item['quantity'] <= 0) {
                            continue;
                        }
                        
                        //  $temp_sale_item['sale_id'] = $sale_id;
                        $temp_sale_item['sale_id'] = $id;  // Use $id instead of $sale_id

// ✅ Always use saved sale record (NOT POST)
$oss_id = (int)$entered_sale->proforma_invoice_id;
$temp_sale_item['proforma_invoice_idd'] = $oss_id;

/* ================= DEBUG 1 ================= */
log_message(
    'error',
    'DEBUG-1 SALE ITEM => sale_id='.$id.
    ' | oss_id='.$oss_id.
    ' | product_id='.$temp_sale_item['product_id']
);
/* =========================================== */

// Only query if OSS exists
if ($oss_id > 0) {

    $oss_price = $this->db
        ->select('price')
        ->from('sale_request_items')
        ->where('sale_id', $oss_id)
        ->where('product_id', (int)$temp_sale_item['product_id'])
        ->order_by('id', 'DESC')
        ->limit(1)
        ->get()
        ->row('price');

    /* ================= DEBUG 2 ================= */
    log_message(
        'error',
        'DEBUG-2 PRICE FETCH => oss_id='.$oss_id.
        ' | product_id='.$temp_sale_item['product_id'].
        ' | price='.($oss_price === null ? 'NULL' : $oss_price)
    );
    /* =========================================== */

    if ($oss_price !== null && $oss_price > 0) {
        $temp_sale_item['purchase_cost'] = (float)$oss_price;
        $temp_sale_item['selling_price'] = (float)$oss_price;
    } else {
        // Explicit fallback
        $temp_sale_item['purchase_cost'] = 0;
        $temp_sale_item['selling_price'] = 0;
    }
}

                        if($temp_sale_item['expiry_date'] == '' || $temp_sale_item['expiry_date'] == '0000-00-00')
                            unset($temp_sale_item['expiry_date']);
                        else
                            $temp_sale_item['expiry_date'] = date('Y-m-d',strtotime($temp_sale_item['expiry_date']));

                        if($temp_sale_item['mfg_date'] == '' || $temp_sale_item['mfg_date'] == '0000-00-00')
                            unset($temp_sale_item['mfg_date']);
                        else
                            $temp_sale_item['mfg_date'] = date('Y-m-d',strtotime($temp_sale_item['mfg_date']));

                        if($temp_sale_item['proforma_invoice_idd'] == '')
                            unset($temp_sale_item['proforma_invoice_idd']);
                            
                        if (!isset($temp_sale_item['product_remark'])) {
                            $temp_sale_item['product_remark'] = '';
                        }    
                        
                        // Add supplier and cost data to sale items
                        if(isset($temp_sale_item['supplier_id'])) {
                            $temp_sale_item['supplier_id'] = $temp_sale_item['supplier_id'];
                        }
                        if(isset($temp_sale_item['purchase_cost'])) {
                            $temp_sale_item['purchase_cost'] = $temp_sale_item['purchase_cost'];
                        }
                        if(isset($temp_sale_item['uom_id'])) {
                            $temp_sale_item['uom_id'] = $temp_sale_item['uom_id'];
                        }
                        if(isset($temp_sale_item['uom_name'])) {
                            $temp_sale_item['uom_name'] = $temp_sale_item['uom_name'];
                        }
                        if(isset($temp_sale_item['uom_uom'])) {
                            $temp_sale_item['uom_uom'] = $temp_sale_item['uom_uom'];
                        }
                        

                        $this->sale_model->add_sale_item_record($temp_sale_item);
                    }
                    
                    // $credit_amount = 0;
                    // $available_credit = ($available_credit < 0) ? abs($available_credit) : $available_credit;

                    // if($available_credit >= ($total-$tds))             //    S         CR                                              4                                                    
                    //     $this->transaction_model->add_transaction($id,SALE_MODULE,CREDIT_TRANSACTION_TYPE,$total-$tds,$invoice_date,CREDIT_MODE,'','',$customer_ledger->id,'','');    
                    // else
                    //     $this->transaction_model->add_transaction($id,SALE_MODULE,CREDIT_TRANSACTION_TYPE,$available_credit,$invoice_date,CREDIT_MODE,'','',$customer_ledger->id,'','');    
                    
                    // commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success',  'Sales (Reference No. -'  .$reference_no .') is added successfully.');
                    redirect('sale','refresh');
                }
                else
                {
                    // rollback transaction
                    $this->db->trans_rollback();
                    
                    $this->session->set_flashdata('failure',  'Sales (Reference No. -'  .$reference_no .') is failed to add.');
                    redirect('sale','refresh');
                }
            }
        }
        else
        {
            $this->load->helper('common');
            $reference_no = get_next_sale_main_reference();
            
            $data['countries']             = $this->utility_model->get_countries();
            $data['customers']             = $this->customer_model->get_records();
            $data['suppliers']          = $this->supplier_model->get_records();
            $data['warehouses']         = $this->warehouse_model->get_records();
            $data['company_setting']    = $this->company_settings_model->get_company_records();    
            $data['states']             = $this->utility_model->get_states(101);
            $data['due_days_options']   = $this->db->where('status', 1)->get('due_days')->result();

            
            $this->load->view('sale/add',$data);
        }
    }
}

// 	public function edit($id = null)
// 	{
// 		if(!$this->permission_model->has_permission('edit_sale'))
// 		{
// 			$this->load->view('errors/html/error_restricted'); 
// 		}
// 		else
// 		{
// 			if($this->input->server('REQUEST_METHOD') === 'POST')
// 			{
// 			 //   echo "<pre>";
// 			 //   print_r($this->input->post());
// 			 //   echo "</pre>";
// 			 //   die;
			    
// 				$sale_id = $this->input->post('id');
// 				$old_sale = $this->sale_model->get_sale_single_record($sale_id);

// 				$this->form_validation->set_rules('invoice_date','Invoice Date','required');		
// 				$this->form_validation->set_rules('customer_id','Customer','required');
// 				$this->form_validation->set_rules('warehouse_id','Warehouse','required');


// 				if($this->form_validation->run()==FALSE)
// 				{
// 					$customer_shipping_country_id  			= $this->input->post('customer_shipping_country_id');
// 					$customer_shipping_state_id  			= $this->input->post('customer_shipping_state_id');

// 					$data['countries'] 		= $this->utility_model->get_countries();
					
// 					if($customer_shipping_country_id != '')
// 					{
// 						$data['states'] 		= $this->utility_model->get_states($customer_shipping_country_id);
// 					}

// 					if($customer_shipping_state_id != '')
// 					{
// 						$data['cities'] 		= $this->utility_model->get_cities($customer_shipping_state_id);
// 					}

// 					$data['warehouses'] 			= $this->warehouse_model->get_records();
// 					$data['customers'] 				= $this->customer_model->get_records();
// 					$data['company_setting']	= $this->company_settings_model->get_company_records();	
// 				// 	$this->load->view('sale/add',$data);
// 				$this->load->view('sale/edit',$data);
// 				}
// 				else
// 				{

				    
// 			    $profit_data = json_decode($this->input->post('profit_data'), true);
//                 $total_purchase_cost = isset($profit_data['total_purchase_cost']) ? $profit_data['total_purchase_cost'] : 0;
//                 $total_selling_price = isset($profit_data['total_selling_price']) ? $profit_data['total_selling_price'] : 0;
//                 $gross_profit_loss = isset($profit_data['gross_profit_loss']) ? $profit_data['gross_profit_loss'] : 0;
//                 $profit_margin = isset($profit_data['profit_margin']) ? $profit_data['profit_margin'] : 0;
				    
// 				$freightData = json_decode($this->input->post('freight_data'), true);
                
//                 if (!$freightData) {
//                     $freightData = [
//                         'freight_selling_price' => 0,
//                         'freight_taxable_value' => 0,
//                         'freight_sub_total' => 0,
//                         'freight_tax_rate' => 0,
//                         'freight_tax_amount' => 0
//                     ];
//                 }    
				    
				    
// 					$invoice_date 				= date('Y-m-d', strtotime($this->input->post('invoice_date')));
// 					$warehouse_id 				= $this->input->post('warehouse_id');
// 					$customer_id 					= $this->input->post('customer_id');

// 					$customer 						= $this->customer_model->get_single_record($customer_id);
// 					$customer_gstin 			= $customer->gstin;

// 				// 	$proforma_invoice_id_array = $this->input->post('proforma_invoice_id');

// 				// 	if (!is_array($proforma_invoice_id_array)) {
// 				// 			$proforma_invoice_id_array = explode(',', $proforma_invoice_id_array);
// 				// 	}

// 				// 	  $proforma_invoice_id = implode(",", $proforma_invoice_id_array);
				
// 			$posted_proforma = $this->input->post('proforma_invoice_id');

// if (!empty($posted_proforma)) {
//     if (!is_array($posted_proforma)) {
//         $posted_proforma = explode(',', $posted_proforma);
//     }

//     // clean values
//     $posted_proforma = array_filter(array_map('trim', $posted_proforma));

//     $proforma_invoice_id = implode(",", $posted_proforma);
// } else {
//     $proforma_invoice_id = $old_sale->proforma_invoice_id;
// }

                      
//                       $selected_shipping_address_id = $this->input->post('shipping_address_select');
// 					  $customer_shipping_country_id 			= $this->input->post('customer_shipping_country_id');
//                       $customer_shipping_state_id 				= $this->input->post('customer_shipping_state_id');
//                       $customer_shipping_city_id 					= $this->input->post('customer_shipping_city_id');
//                       $customer_shipping_address 					= $this->input->post('customer_shipping_address');
//                       $customer_shipping_pincode 					= $this->input->post('customer_shipping_pincode');

// 					$reference_no 				= $this->input->post('reference_no');
// 					$rcm 									= $this->input->post('rcm');
// 					$total_taxable_value 	= $this->input->post('total_taxable_value');
// 					$tds 									= $this->input->post('tds');
// 					$total_discount 			= $this->input->post('total_discount');
// 					$total_tax 						= $this->input->post('total_tax');
// 					$total 								= $this->input->post('total');
// 					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
// 					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
// 					$bank_detail 					= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
// 					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
//                     $lori_no 							= $this->input->post('lori_no');
// 					$carrier 							= $this->input->post('carrier');
// 					$vehicle_no 					= $this->input->post('vehicle_no');
// 					$ewaybill_no 					= $this->input->post('ewaybill_no');
// 					$additional_case 			= $this->input->post('additional_case');

//                     $ewaybill_dispatch_from 							= $this->input->post('ewaybill_dispatch_from');
// 					$ewaybill_mode_of_transportation 			= $this->input->post('ewaybill_mode_of_transportation');
// 					$ewaybill_subtype 										= $this->input->post('ewaybill_subtype');
// 					$ewaybill_doctype 										= $this->input->post('ewaybill_doctype');
// 					$ewaybill_transporter_name 						= $this->input->post('ewaybill_transporter_name');
// 					$ewaybill_transporter_gstin 					= $this->input->post('ewaybill_transporter_gstin');
// 					$ewaybill_distance_of_transportation 	= $this->input->post('ewaybill_distance_of_transportation');
// 					$ewaybill_transporter_doc_no 					= $this->input->post('ewaybill_transporter_doc_no');
// 					$ewaybill_vehicle_no 									= $this->input->post('ewaybill_vehicle_no');
// 					$ewaybill_vehicle_type 								= $this->input->post('ewaybill_vehicle_type');

//                     $due_days = $this->input->post('due_days');
//                     $payment_terms = $this->input->post('payment_terms');
//                     $due_date = $this->input->post('due_date') ? date('Y-m-d', strtotime($this->input->post('due_date'))) : NULL;
                    
// 					$document 						= $this->input->post("document");

//           if (!is_array($document)) {
//             // Convert $document to an array with a single element
//             $document = array($document);
//           }

//           $documents = implode(',', $document);
					

// 					$sale_data = array(
// 														"invoice_date"					=> $invoice_date,
// 														"proforma_invoice_id"		=> $proforma_invoice_id,
// 														"warehouse_id"	  			=> $warehouse_id,
// 														"customer_id"						=> $customer_id,
// 														"customer_gstin"				=> $customer_gstin,
														
// 														"selected_shipping_address_id" => $selected_shipping_address_id,
// 														"customer_shipping_country_id"			=> $customer_shipping_country_id,
//                                                         "customer_shipping_state_id"				=> $customer_shipping_state_id,
//                                                         "customer_shipping_city_id"					=> $customer_shipping_city_id,
//                                                         "customer_shipping_address"					=> $customer_shipping_address,
//                                                         "customer_shipping_pincode"					=> $customer_shipping_pincode,
// 														"rcm"										=> $rcm,
// 														"total_taxable_value" 	=> $total_taxable_value,	
// 														"tds"							 			=> $tds,	
// 														"total_tax"							=> $total_tax,
// 														"total_discount" 				=> $total_discount,	
// 														"total" 								=> $total,
// 														"internal_note"					=> $internal_note,
// 														"external_note" 				=> $external_note,
// 														"bank_detail"	 					=> $bank_detail,
// 														"terms_and_condition"		=> $terms_and_condition,
                                                        
//                                                         "due_days" => $due_days,
//                                                         "payment_terms" => $payment_terms,
//                                                         "due_date" => $due_date, // Add this line
//                                                         "purchase_order_no" => $this->input->post('purchase_order_no'), // Add this line
//                                                         "purchase_order_date" => $this->input->post('purchase_order_date'),
//                                                         "dispatch_date" => $this->input->post('dispatch_date'),
//                                                          "lori_no" 						=> $lori_no,
// 														"carrier" 						=> $carrier,
// 														"vehicle_no" 					=> $vehicle_no,
// 														"lut_no"                        => $this->input->post('lut_no'),
// 														"ewaybill_no" 				=> $ewaybill_no,
// 														"additional_case" 		=> $additional_case,
// 														"document" 						=> $documents,
// 													    "additional_cost_type" => $this->input->post('additional_cost_type'),
//                                                         "additional_cost_amount" => $this->input->post('additional_cost_amount'),
//                                                         "total_purchase_cost"    => $total_purchase_cost,
//                                                         "total_selling_price"    => $total_selling_price,
//                                                         "gross_profit_loss"     => $gross_profit_loss,
//                                                         "profit_margin"         => $profit_margin,
//                                                         'freight_selling_price' => $freightData['freight_selling_price'],
//                                                         'freight_taxable_value' => $freightData['freight_taxable_value'],
//                                                         'freight_sub_total' => $freightData['freight_sub_total'],
//                                                         'freight_tax_rate' => $freightData['freight_tax_rate'],
//                                                         'freight_tax_amount' => $freightData['freight_tax_amount'],
// 														"user_id" 						=> $this->session->userdata('user_id')
// 													);

//           if (!empty($ewaybill_dispatch_from)) {
//               $sale_data['ewaybill_dispatch_from'] = $ewaybill_dispatch_from;
//           }
          
//           if (!empty($ewaybill_mode_of_transportation)) {
//               $sale_data['ewaybill_mode_of_transportation'] = $ewaybill_mode_of_transportation;
//           }
          
//           if (!empty($ewaybill_subtype)) {
//               $sale_data['ewaybill_subtype'] = $ewaybill_subtype;
//           }
          
//           if (!empty($ewaybill_doctype)) {
//               $sale_data['ewaybill_doctype'] = $ewaybill_doctype;
//           }
          
//           if (!empty($ewaybill_transporter_name)) {
//               $sale_data['ewaybill_transporter_name'] = $ewaybill_transporter_name;
//           }
          
//           if (!empty($ewaybill_transporter_gstin)) {
//               $sale_data['ewaybill_transporter_gstin'] = $ewaybill_transporter_gstin;
//           }
          
//           if (!empty($ewaybill_distance_of_transportation)) {
//               $sale_data['ewaybill_distance_of_transportation'] = $ewaybill_distance_of_transportation;
//           }
          
//           if (!empty($ewaybill_transporter_doc_no)) {
//               $sale_data['ewaybill_transporter_doc_no'] = $ewaybill_transporter_doc_no;
//           }
          
//           if (!empty($ewaybill_vehicle_no)) {
//               $sale_data['ewaybill_vehicle_no'] = $ewaybill_vehicle_no;
//           }
          
//           if (!empty($ewaybill_vehicle_type)) {
//               $sale_data['ewaybill_vehicle_type'] = $ewaybill_vehicle_type;
//           }

//           $ewaybill_transporter_doc_date 				= $this->input->post('ewaybill_transporter_doc_date');
//           if($ewaybill_transporter_doc_date != '')
//             $sale_data['ewaybill_transporter_doc_date'] = date('Y-m-d',strtotime($ewaybill_transporter_doc_date));

//           $lori_date 				= $this->input->post('lori_date');
//           if($lori_date != '')
//             $sale_data['lori_date'] = date('Y-m-d',strtotime($lori_date));


//                     if ($this->sale_model->edit_sale_record($sale_data, $sale_id)) {
//                         $entered_sale = $this->sale_model->get_sale_single_record($sale_id);
//                         $customer = $this->customer_model->get_single_record($entered_sale->customer_id);
                    
//                         /****************************** Start Sale Ledger ***********************************/
//                         $sale_ledger = $this->ledger_model->get_single_record(SALE_LEDGER);
//                         $updated_sale_ledger = array(
//                             'closing_balance' => ($sale_ledger->closing_balance - $old_sale->total + $total)
//                         );
//                         $this->ledger_model->edit_record($updated_sale_ledger, $sale_ledger->id);
//                         /****************************** End Sale Ledger *************************************/
                    
//                         /****************************** Start Customer Ledger *******************************/
//                         $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
//                         $updated_customer_ledger = array(
//                             'closing_balance' => ($customer_ledger->closing_balance + $total - $old_sale->total)
//                         );
//                         $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);
//                         /****************************** End Customer Ledger *********************************/
                    
//                         /****************************** Start TDS Ledger ************************************/
//                         if ($tds > 0) {
//                             $tds_ledger = $this->ledger_model->get_single_record(TDS_LEDGER);
//                             $updated_tds_ledger = array(
//                                 'closing_balance' => ($tds_ledger->closing_balance + $tds - $old_sale->tds)
//                             );
//                             $this->ledger_model->edit_record($updated_tds_ledger, $tds_ledger->id);
                    
//                             $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
//                             $updated_customer_ledger = array(
//                                 'closing_balance' => ($customer_ledger->closing_balance - $tds + $old_sale->tds)
//                             );
//                             $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);
//                         }
//                         /****************************** End TDS Ledger **************************************/
                    
//                         /****************************** Add Transaction entry *******************************/
//                         $this->transaction_model->delete_record_by_entry_id($entered_sale->id, SALE_MODULE);
                    
//                         $transaction_header = array(
//                             "entry_id" => $sale_id,
//                             "module" => SALE_MODULE,
//                             "type" => SALE_TRANSACTION_TYPE,
//                             "amount" => $total,
//                             "voucher_date" => $invoice_date,
//                             "from_account" => SALE_LEDGER,
//                             "to_account" => $customer->ledger_id,
//                             "reference_no" => $entered_sale->reference_no
//                         );
                    
//                         if ($transaction_id = $this->transaction_model->add_record($transaction_header)) {
//                             $from_transaction_detail = array(
//                                 "transaction_id" => $transaction_id,
//                                 "voucher_type" => 'C',
//                                 "ledger_id" => SALE_LEDGER,
//                                 "dr_amount" => $total
//                             );
//                             $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
                    
//                             $to_transaction_detail = array(
//                                 "transaction_id" => $transaction_id,
//                                 "voucher_type" => 'D',
//                                 "ledger_id" => $customer->ledger_id,
//                                 "cr_amount" => $total
//                             );
//                             $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
//                         }
//                         /************************************************************************************/
                    
//                         /****************************** Add TDS Transaction entry ***************************/
//                         if ($tds > 0) {
//                             $transaction_header = array(
//                                 "entry_id" => $sale_id,
//                                 "module" => SALE_MODULE,
//                                 "type" => TDS_TRANSACTION_TYPE,
//                                 "amount" => $tds,
//                                 "voucher_date" => $invoice_date,
//                                 "from_account" => $customer->ledger_id,
//                                 "to_account" => TDS_LEDGER,
//                                 "reference_no" => $reference_no
//                             );
                    
//                             if ($transaction_id = $this->transaction_model->add_record($transaction_header)) {
//                                 $from_transaction_detail = array(
//                                     "transaction_id" => $transaction_id,
//                                     "voucher_type" => 'C',
//                                     "ledger_id" => $customer->ledger_id,
//                                     "dr_amount" => $tds
//                                 );
//                                 $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
                    
//                                 $to_transaction_detail = array(
//                                     "transaction_id" => $transaction_id,
//                                     "voucher_type" => 'D',
//                                     "ledger_id" => TDS_LEDGER,
//                                     "cr_amount" => $tds
//                                 );
//                                 $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
//                             }
//                         }
//                         /************************************************************************************/
                    
//                         /****************************** Add Log Entry ***************************************/
//                         $log_data = array(
//                             "user_id" => $this->session->userdata("user_id"),
//                             "module" => "sale",
//                             "user_action" => 2,
//                             "b_data" => json_encode((array)$old_sale),
//                             "data" => json_encode((array)$entered_sale),
//                             "description" => 'Sales (Reference No. -' . $reference_no . ') is updated successfully.'
//                         );
//                         $this->log_data_model->add_record($log_data);
//                         /************************************************************************************/
                    
//                         /****************************** Update Sale Items ***********************************/
//                         $this->sale_model->remove_sale_item_records($sale_id);
                    
//                         $sale_items = $this->input->post('sale_items');
//                         if (!empty($sale_items)) {
//                             $sale_items_array = explode("|", $sale_items);
                   
//                             foreach ($sale_items_array as $item_json) {
//                                 $temp_sale_item = (array)json_decode($item_json);
                            
//                             //   $temp_sale_item['sale_id'] = $id;
//                             $temp_sale_item['sale_id'] = $sale_id;

// // ✅ Always use saved sale record (NOT POST)
// $oss_id = (int)$entered_sale->proforma_invoice_id;
// $temp_sale_item['proforma_invoice_idd'] = $oss_id;

// /* ================= DEBUG 1 ================= */
// log_message(
//     'error',
//     'DEBUG-1 SALE ITEM => sale_id='.$id.
//     ' | oss_id='.$oss_id.
//     ' | product_id='.$temp_sale_item['product_id']
// );
// /* =========================================== */

// // Only query if OSS exists
// if ($oss_id > 0) {

//     $oss_price = $this->db
//         ->select('price')
//         ->from('sale_request_items')
//         ->where('sale_id', $oss_id)
//         ->where('product_id', (int)$temp_sale_item['product_id'])
//         ->order_by('id', 'DESC')
//         ->limit(1)
//         ->get()
//         ->row('price');

//     /* ================= DEBUG 2 ================= */
//     log_message(
//         'error',
//         'DEBUG-2 PRICE FETCH => oss_id='.$oss_id.
//         ' | product_id='.$temp_sale_item['product_id'].
//         ' | price='.($oss_price === null ? 'NULL' : $oss_price)
//     );
//     /* =========================================== */

//     if ($oss_price !== null && $oss_price > 0) {
//         $temp_sale_item['purchase_cost'] = (float)$oss_price;
//         $temp_sale_item['selling_price'] = (float)$oss_price;
//     } else {
//         // Explicit fallback
//         $temp_sale_item['purchase_cost'] = 0;
//         $temp_sale_item['selling_price'] = 0;
//     }
// }

                    
//                                 if (isset($temp_sale_item['product_remark'])) {
//                                     $temp_sale_item['product_remark'] = $temp_sale_item['product_remark'];
//                                 }
                    
//                                 if (empty($temp_sale_item['expiry_date']) || $temp_sale_item['expiry_date'] == '0000-00-00') {
//                                     unset($temp_sale_item['expiry_date']);
//                                 } else {
//                                     $temp_sale_item['expiry_date'] = date('Y-m-d', strtotime($temp_sale_item['expiry_date']));
//                                 }
                    
//                                 if (empty($temp_sale_item['mfg_date']) || $temp_sale_item['mfg_date'] == '0000-00-00') {
//                                     unset($temp_sale_item['mfg_date']);
//                                 } else {
//                                     $temp_sale_item['mfg_date'] = date('Y-m-d', strtotime($temp_sale_item['mfg_date']));
//                                 }
                    
//                                 if (empty($temp_sale_item['proforma_invoice_idd'])) {
//                                     unset($temp_sale_item['proforma_invoice_idd']);
//                                 }
                                
//                                 $this->sale_model->add_sale_item_record($temp_sale_item);
//                             }
                            
//                         }
//                         /************************************************************************************/
                    
//                         $this->session->set_flashdata('success', 'Sales (Reference No. -' . $reference_no . ') is updated successfully.');
//                         redirect('sale', 'refresh');
//                     }

// 					else
// 					{
// 						$this->session->set_flashdata('failure',  'Sales (Reference No. -'  .$reference_no .') is failed to update.');
// 						redirect('sale','refresh');
// 					}
// 				}
// 			}
// 			else
// 			{
// 				$id 	= base64_decode($id);

// 				if($id != null)
// 				{
// 					$data['sale'] 						= $this->sale_model->get_sale_single_record($id);

// 					if($data['sale'] != null)
// 					{

//             			$data['countries'] 		= $this->utility_model->get_countries();
            
//                         if($data['sale']->customer_shipping_country_id != '')
//                           $data['states'] 		= $this->utility_model->get_states($data['sale']->customer_shipping_country_id);
            
//                         if($data['sale']->customer_shipping_state_id != '')
//                           $data['cities'] 		= $this->utility_model->get_cities($data['sale']->customer_shipping_state_id);

// 						$data['sale_items'] 			= $this->sale_model->get_sale_item_records($id);
					
// 					$this->load->model('supplier_model');
//                                 foreach($data['sale_items'] as &$item) {
//                                     $item->product_vendors = $this->supplier_model->get_vendors_by_product($item->product_id);
//                                 }
        
					
// 						$data['customers'] 				= $this->customer_model->get_records();
// 						$data['customer_detail']	= $this->customer_model->get_single_record($data['sale']->customer_id);
// 						$data['discounts']				= $this->discount_model->get_records();
// 						$data['company_setting']	= $this->company_settings_model->get_company_records();	
// 						$data['customer_shipping_addresses'] = $this->customer_model->get_customer_shipping_addresses($data['sale']->customer_id);
//                         $data['states'] 					= $this->utility_model->get_states(101);
// 						$data['warehouses'] 			= $this->warehouse_model->get_records();

// 						$data['proforma_invoices']				= $this->proforma_invoice_model->get_proforma_invoice_records_by_customer_id($data['sale']->customer_id);

//                     $data['suppliers']          = $this->supplier_model->get_records();
//                     $data['due_days_options']   = $this->db->where('status', 1)->get('due_days')->result();

// 						$this->load->view('sale/edit',$data);
// 				// 		echo "<pre>";
// 				// 		print_r($data);
// 				// 		echo "</pre>";
// 				// 		die;
// 					}
// 					else
// 					{
// 						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
// 						redirect('sale','refresh');
// 					}
				
// 				}
// 				else
// 				{
// 					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
// 					redirect('sale','refresh');
// 				}
// 			}
// 		}
// 	}
	
	public function edit($id = null)
	{
		if(!$this->permission_model->has_permission('edit_sale'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
			 //   echo "<pre>";
			 //   print_r($this->input->post());
			 //   echo "</pre>";
			 //   die;
			    
				$sale_id = $this->input->post('id');
				$old_sale = $this->sale_model->get_sale_single_record($sale_id);

				$this->form_validation->set_rules('invoice_date','Invoice Date','required');		
				$this->form_validation->set_rules('customer_id','Customer','required');
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');


				if($this->form_validation->run()==FALSE)
				{
					$customer_shipping_country_id  			= $this->input->post('customer_shipping_country_id');
					$customer_shipping_state_id  			= $this->input->post('customer_shipping_state_id');

					$data['countries'] 		= $this->utility_model->get_countries();
					
					if($customer_shipping_country_id != '')
					{
						$data['states'] 		= $this->utility_model->get_states($customer_shipping_country_id);
					}

					if($customer_shipping_state_id != '')
					{
						$data['cities'] 		= $this->utility_model->get_cities($customer_shipping_state_id);
					}

					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['customers'] 				= $this->customer_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
				// 	$this->load->view('sale/add',$data);
				$this->load->view('sale/edit',$data);
				}
				else
				{

				    
			    $profit_data = json_decode($this->input->post('profit_data'), true);
                $total_purchase_cost = isset($profit_data['total_purchase_cost']) ? $profit_data['total_purchase_cost'] : 0;
                $total_selling_price = isset($profit_data['total_selling_price']) ? $profit_data['total_selling_price'] : 0;
                $gross_profit_loss = isset($profit_data['gross_profit_loss']) ? $profit_data['gross_profit_loss'] : 0;
                $profit_margin = isset($profit_data['profit_margin']) ? $profit_data['profit_margin'] : 0;
				    
				$freightData = json_decode($this->input->post('freight_data'), true);
                
                if (!$freightData) {
                    $freightData = [
                        'freight_selling_price' => 0,
                        'freight_taxable_value' => 0,
                        'freight_sub_total' => 0,
                        'freight_tax_rate' => 0,
                        'freight_tax_amount' => 0
                    ];
                }    
				    
				    
					$invoice_date 				= date('Y-m-d', strtotime($this->input->post('invoice_date')));
					$warehouse_id 				= $this->input->post('warehouse_id');
					$customer_id 					= $this->input->post('customer_id');

					$customer 						= $this->customer_model->get_single_record($customer_id);
					$customer_gstin 			= $customer->gstin;

				// 	$proforma_invoice_id_array = $this->input->post('proforma_invoice_id');

				// 	if (!is_array($proforma_invoice_id_array)) {
				// 			$proforma_invoice_id_array = explode(',', $proforma_invoice_id_array);
				// 	}

				// 	  $proforma_invoice_id = implode(",", $proforma_invoice_id_array);
				
			$posted_proforma = $this->input->post('proforma_invoice_id');

if (!empty($posted_proforma)) {
    if (!is_array($posted_proforma)) {
        $posted_proforma = explode(',', $posted_proforma);
    }

    // clean values
    $posted_proforma = array_filter(array_map('trim', $posted_proforma));

    $proforma_invoice_id = implode(",", $posted_proforma);
} else {
    $proforma_invoice_id = $old_sale->proforma_invoice_id;
}

                      
                      $selected_shipping_address_id = $this->input->post('shipping_address_select');
					  $customer_shipping_country_id 			= $this->input->post('customer_shipping_country_id');
                      $customer_shipping_state_id 				= $this->input->post('customer_shipping_state_id');
                      $customer_shipping_city_id 					= $this->input->post('customer_shipping_city_id');
                      $customer_shipping_address 					= $this->input->post('customer_shipping_address');
                      $customer_shipping_pincode 					= $this->input->post('customer_shipping_pincode');

					$reference_no 				= $this->input->post('reference_no');
					$rcm 									= $this->input->post('rcm');
					$total_taxable_value 	= $this->input->post('total_taxable_value');
					$tds 									= $this->input->post('tds');
					$total_discount 			= $this->input->post('total_discount');
					$total_tax 						= $this->input->post('total_tax');
					$total 								= $this->input->post('total');
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					$bank_detail 					= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
                    $lori_no 							= $this->input->post('lori_no');
					$carrier 							= $this->input->post('carrier');
					$vehicle_no 					= $this->input->post('vehicle_no');
					$ewaybill_no 					= $this->input->post('ewaybill_no');
					$additional_case 			= $this->input->post('additional_case');

                    $ewaybill_dispatch_from 							= $this->input->post('ewaybill_dispatch_from');
					$ewaybill_mode_of_transportation 			= $this->input->post('ewaybill_mode_of_transportation');
					$ewaybill_subtype 										= $this->input->post('ewaybill_subtype');
					$ewaybill_doctype 										= $this->input->post('ewaybill_doctype');
					$ewaybill_transporter_name 						= $this->input->post('ewaybill_transporter_name');
					$ewaybill_transporter_gstin 					= $this->input->post('ewaybill_transporter_gstin');
					$ewaybill_distance_of_transportation 	= $this->input->post('ewaybill_distance_of_transportation');
					$ewaybill_transporter_doc_no 					= $this->input->post('ewaybill_transporter_doc_no');
					$ewaybill_vehicle_no 									= $this->input->post('ewaybill_vehicle_no');
					$ewaybill_vehicle_type 								= $this->input->post('ewaybill_vehicle_type');

                    $due_days = $this->input->post('due_days');
                    $payment_terms = $this->input->post('payment_terms');
                    $due_date = $this->input->post('due_date') ? date('Y-m-d', strtotime($this->input->post('due_date'))) : NULL;
                    
					$document 						= $this->input->post("document");

          if (!is_array($document)) {
            // Convert $document to an array with a single element
            $document = array($document);
          }

          $documents = implode(',', $document);
					

					$sale_data = array(
														"invoice_date"					=> $invoice_date,
														"proforma_invoice_id"		=> $proforma_invoice_id,
														"warehouse_id"	  			=> $warehouse_id,
														"customer_id"						=> $customer_id,
														"customer_gstin"				=> $customer_gstin,
														
														"selected_shipping_address_id" => $selected_shipping_address_id,
														"customer_shipping_country_id"			=> $customer_shipping_country_id,
                                                        "customer_shipping_state_id"				=> $customer_shipping_state_id,
                                                        "customer_shipping_city_id"					=> $customer_shipping_city_id,
                                                        "customer_shipping_address"					=> $customer_shipping_address,
                                                        "customer_shipping_pincode"					=> $customer_shipping_pincode,
														"rcm"										=> $rcm,
														"total_taxable_value" 	=> $total_taxable_value,	
														"tds"							 			=> $tds,	
														"total_tax"							=> $total_tax,
														"total_discount" 				=> $total_discount,	
														"total" 								=> $total,
														"internal_note"					=> $internal_note,
														"external_note" 				=> $external_note,
														"bank_detail"	 					=> $bank_detail,
														"terms_and_condition"		=> $terms_and_condition,
                                                        
                                                        "due_days" => $due_days,
                                                        "payment_terms" => $payment_terms,
                                                        "due_date" => $due_date, // Add this line
                                                        "purchase_order_no" => $this->input->post('purchase_order_no'), // Add this line
                                                        "purchase_order_date" => $this->input->post('purchase_order_date'),
                                                        "dispatch_date" => $this->input->post('dispatch_date'),
                                                         "lori_no" 						=> $lori_no,
														"carrier" 						=> $carrier,
														"vehicle_no" 					=> $vehicle_no,
														"lut_no"                        => $this->input->post('lut_no'),
														"ewaybill_no" 				=> $ewaybill_no,
														"additional_case" 		=> $additional_case,
														"document" 						=> $documents,
													    "additional_cost_type" => $this->input->post('additional_cost_type'),
                                                        "additional_cost_amount" => $this->input->post('additional_cost_amount'),
                                                        "total_purchase_cost"    => $total_purchase_cost,
                                                        "total_selling_price"    => $total_selling_price,
                                                        "gross_profit_loss"     => $gross_profit_loss,
                                                        "profit_margin"         => $profit_margin,
                                                        'freight_selling_price' => $freightData['freight_selling_price'],
                                                        'freight_taxable_value' => $freightData['freight_taxable_value'],
                                                        'freight_sub_total' => $freightData['freight_sub_total'],
                                                        'freight_tax_rate' => $freightData['freight_tax_rate'],
                                                        'freight_tax_amount' => $freightData['freight_tax_amount'],
														"user_id" 						=> $this->session->userdata('user_id')
													);

          if (!empty($ewaybill_dispatch_from)) {
              $sale_data['ewaybill_dispatch_from'] = $ewaybill_dispatch_from;
          }
          
          if (!empty($ewaybill_mode_of_transportation)) {
              $sale_data['ewaybill_mode_of_transportation'] = $ewaybill_mode_of_transportation;
          }
          
          if (!empty($ewaybill_subtype)) {
              $sale_data['ewaybill_subtype'] = $ewaybill_subtype;
          }
          
          if (!empty($ewaybill_doctype)) {
              $sale_data['ewaybill_doctype'] = $ewaybill_doctype;
          }
          
          if (!empty($ewaybill_transporter_name)) {
              $sale_data['ewaybill_transporter_name'] = $ewaybill_transporter_name;
          }
          
          if (!empty($ewaybill_transporter_gstin)) {
              $sale_data['ewaybill_transporter_gstin'] = $ewaybill_transporter_gstin;
          }
          
          if (!empty($ewaybill_distance_of_transportation)) {
              $sale_data['ewaybill_distance_of_transportation'] = $ewaybill_distance_of_transportation;
          }
          
          if (!empty($ewaybill_transporter_doc_no)) {
              $sale_data['ewaybill_transporter_doc_no'] = $ewaybill_transporter_doc_no;
          }
          
          if (!empty($ewaybill_vehicle_no)) {
              $sale_data['ewaybill_vehicle_no'] = $ewaybill_vehicle_no;
          }
          
          if (!empty($ewaybill_vehicle_type)) {
              $sale_data['ewaybill_vehicle_type'] = $ewaybill_vehicle_type;
          }

          $ewaybill_transporter_doc_date 				= $this->input->post('ewaybill_transporter_doc_date');
          if($ewaybill_transporter_doc_date != '')
            $sale_data['ewaybill_transporter_doc_date'] = date('Y-m-d',strtotime($ewaybill_transporter_doc_date));

          $lori_date 				= $this->input->post('lori_date');
          if($lori_date != '')
            $sale_data['lori_date'] = date('Y-m-d',strtotime($lori_date));


                    if ($this->sale_model->edit_sale_record($sale_data, $sale_id)) {
                        $entered_sale = $this->sale_model->get_sale_single_record($sale_id);
                        $customer = $this->customer_model->get_single_record($entered_sale->customer_id);
                    
                        /****************************** Start Sale Ledger ***********************************/
                        $sale_ledger = $this->ledger_model->get_single_record(SALE_LEDGER);
                        $updated_sale_ledger = array(
                            'closing_balance' => ($sale_ledger->closing_balance - $old_sale->total + $total)
                        );
                        $this->ledger_model->edit_record($updated_sale_ledger, $sale_ledger->id);
                        /****************************** End Sale Ledger *************************************/
                    
                        /****************************** Start Customer Ledger *******************************/
                        $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
                        $updated_customer_ledger = array(
                            'closing_balance' => ($customer_ledger->closing_balance + $total - $old_sale->total)
                        );
                        $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);
                        /****************************** End Customer Ledger *********************************/
                    
                        /****************************** Start TDS Ledger ************************************/
                        if ($tds > 0) {
                            $tds_ledger = $this->ledger_model->get_single_record(TDS_LEDGER);
                            $updated_tds_ledger = array(
                                'closing_balance' => ($tds_ledger->closing_balance + $tds - $old_sale->tds)
                            );
                            $this->ledger_model->edit_record($updated_tds_ledger, $tds_ledger->id);
                    
                            $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
                            $updated_customer_ledger = array(
                                'closing_balance' => ($customer_ledger->closing_balance - $tds + $old_sale->tds)
                            );
                            $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);
                        }
                        /****************************** End TDS Ledger **************************************/
                    
                        /****************************** Add Transaction entry *******************************/
                        $this->transaction_model->delete_record_by_entry_id($entered_sale->id, SALE_MODULE);
                    
                        $transaction_header = array(
                            "entry_id" => $sale_id,
                            "module" => SALE_MODULE,
                            "type" => SALE_TRANSACTION_TYPE,
                            "amount" => $total,
                            "voucher_date" => $invoice_date,
                            "from_account" => SALE_LEDGER,
                            "to_account" => $customer->ledger_id,
                            "reference_no" => $entered_sale->reference_no
                        );
                    
                        if ($transaction_id = $this->transaction_model->add_record($transaction_header)) {
                            $from_transaction_detail = array(
                                "transaction_id" => $transaction_id,
                                "voucher_type" => 'C',
                                "ledger_id" => SALE_LEDGER,
                                "dr_amount" => $total
                            );
                            $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
                    
                            $to_transaction_detail = array(
                                "transaction_id" => $transaction_id,
                                "voucher_type" => 'D',
                                "ledger_id" => $customer->ledger_id,
                                "cr_amount" => $total
                            );
                            $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                        }
                        /************************************************************************************/
                    
                        /****************************** Add TDS Transaction entry ***************************/
                        if ($tds > 0) {
                            $transaction_header = array(
                                "entry_id" => $sale_id,
                                "module" => SALE_MODULE,
                                "type" => TDS_TRANSACTION_TYPE,
                                "amount" => $tds,
                                "voucher_date" => $invoice_date,
                                "from_account" => $customer->ledger_id,
                                "to_account" => TDS_LEDGER,
                                "reference_no" => $reference_no
                            );
                    
                            if ($transaction_id = $this->transaction_model->add_record($transaction_header)) {
                                $from_transaction_detail = array(
                                    "transaction_id" => $transaction_id,
                                    "voucher_type" => 'C',
                                    "ledger_id" => $customer->ledger_id,
                                    "dr_amount" => $tds
                                );
                                $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
                    
                                $to_transaction_detail = array(
                                    "transaction_id" => $transaction_id,
                                    "voucher_type" => 'D',
                                    "ledger_id" => TDS_LEDGER,
                                    "cr_amount" => $tds
                                );
                                $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                            }
                        }
                        /************************************************************************************/
                    
                        /****************************** Add Log Entry ***************************************/
                        $log_data = array(
                            "user_id" => $this->session->userdata("user_id"),
                            "module" => "sale",
                            "user_action" => 2,
                            "b_data" => json_encode((array)$old_sale),
                            "data" => json_encode((array)$entered_sale),
                            "description" => 'Sales (Reference No. -' . $reference_no . ') is updated successfully.'
                        );
                        $this->log_data_model->add_record($log_data);
                        /************************************************************************************/
                    
                        /****************************** Update Sale Items ***********************************/
                        $this->sale_model->remove_sale_item_records($sale_id);
                    
                        $sale_items = $this->input->post('sale_items');
                        if (!empty($sale_items)) {
                            $sale_items_array = explode("|", $sale_items);
                   
                            foreach ($sale_items_array as $item_json) {
                                $temp_sale_item = (array)json_decode($item_json);
                            
                            //   $temp_sale_item['sale_id'] = $id;
                            $temp_sale_item['sale_id'] = $sale_id;

// ✅ Always use saved sale record (NOT POST)
$oss_id = (int)$entered_sale->proforma_invoice_id;
$temp_sale_item['proforma_invoice_idd'] = $oss_id;

/* ================= DEBUG 1 ================= */
log_message(
    'error',
    'DEBUG-1 SALE ITEM => sale_id='.$id.
    ' | oss_id='.$oss_id.
    ' | product_id='.$temp_sale_item['product_id']
);
/* =========================================== */

// Only query if OSS exists
if ($oss_id > 0) {

    $oss_price = $this->db
        ->select('price')
        ->from('sale_request_items')
        ->where('sale_id', $oss_id)
        ->where('product_id', (int)$temp_sale_item['product_id'])
        ->order_by('id', 'DESC')
        ->limit(1)
        ->get()
        ->row('price');

    /* ================= DEBUG 2 ================= */
    log_message(
        'error',
        'DEBUG-2 PRICE FETCH => oss_id='.$oss_id.
        ' | product_id='.$temp_sale_item['product_id'].
        ' | price='.($oss_price === null ? 'NULL' : $oss_price)
    );
    /* =========================================== */

    if ($oss_price !== null && $oss_price > 0) {
        $temp_sale_item['purchase_cost'] = (float)$oss_price;
        $temp_sale_item['selling_price'] = (float)$oss_price;
    } else {
        // Explicit fallback
        $temp_sale_item['purchase_cost'] = 0;
        $temp_sale_item['selling_price'] = 0;
    }
}

                    
                                if (isset($temp_sale_item['product_remark'])) {
                                    $temp_sale_item['product_remark'] = $temp_sale_item['product_remark'];
                                }
                    
                                if (empty($temp_sale_item['expiry_date']) || $temp_sale_item['expiry_date'] == '0000-00-00') {
                                    unset($temp_sale_item['expiry_date']);
                                } else {
                                    $temp_sale_item['expiry_date'] = date('Y-m-d', strtotime($temp_sale_item['expiry_date']));
                                }
                    
                                if (empty($temp_sale_item['mfg_date']) || $temp_sale_item['mfg_date'] == '0000-00-00') {
                                    unset($temp_sale_item['mfg_date']);
                                } else {
                                    $temp_sale_item['mfg_date'] = date('Y-m-d', strtotime($temp_sale_item['mfg_date']));
                                }
                    
                                if (empty($temp_sale_item['proforma_invoice_idd'])) {
                                    unset($temp_sale_item['proforma_invoice_idd']);
                                }
                                
                                $this->sale_model->add_sale_item_record($temp_sale_item);
                            }
                            
                        }
                        /************************************************************************************/
                    
                        $this->session->set_flashdata('success', 'Sales (Reference No. -' . $reference_no . ') is updated successfully.');
                        redirect('sale', 'refresh');
                    }

					else
					{
						$this->session->set_flashdata('failure',  'Sales (Reference No. -'  .$reference_no .') is failed to update.');
						redirect('sale','refresh');
					}
				}
			}
			else
			{
				$id 	= base64_decode($id);

				if($id != null)
				{
					$data['sale'] 						= $this->sale_model->get_sale_single_record($id);

					if($data['sale'] != null)
					{

            			$data['countries'] 		= $this->utility_model->get_countries();
            
                        if($data['sale']->customer_shipping_country_id != '')
                          $data['states'] 		= $this->utility_model->get_states($data['sale']->customer_shipping_country_id);
            
                        if($data['sale']->customer_shipping_state_id != '')
                          $data['cities'] 		= $this->utility_model->get_cities($data['sale']->customer_shipping_state_id);

						$data['sale_items'] 			= $this->sale_model->get_sale_item_records($id);
					
					$this->load->model('supplier_model');
                                foreach($data['sale_items'] as &$item) {
                                    $item->product_vendors = $this->supplier_model->get_vendors_by_product($item->product_id);
                                }
        
					
						$data['customers'] 				= $this->customer_model->get_records();
						$data['customer_detail']	= $this->customer_model->get_single_record($data['sale']->customer_id);
						$data['discounts']				= $this->discount_model->get_records();
						$data['company_setting']	= $this->company_settings_model->get_company_records();	
						$data['customer_shipping_addresses'] = $this->customer_model->get_customer_shipping_addresses($data['sale']->customer_id);
                        $data['states'] 					= $this->utility_model->get_states(101);
						$data['warehouses'] 			= $this->warehouse_model->get_records();

						$data['proforma_invoices']				= $this->proforma_invoice_model->get_proforma_invoice_records_by_customer_id($data['sale']->customer_id);

                    $data['suppliers']          = $this->supplier_model->get_records();
                    $data['due_days_options']   = $this->db->where('status', 1)->get('due_days')->result();

						$this->load->view('sale/edit',$data);
				// 		echo "<pre>";
				// 		print_r($data);
				// 		echo "</pre>";
				// 		die;
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('sale','refresh');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('sale','refresh');
				}
			}
		}
	}
	

	
	
public function get_reference_no()
{
            $this->load->helper('common');
            $reference_no = get_next_sale_main_reference();// false = get next sale number
    
    echo json_encode(array(
        'status' => true,
        'reference_no' => $reference_no
    ));
}


    public function get_latest_purchase_cost()
    {
        $product_id = $this->input->post('product_id');
        $supplier_id = $this->input->post('supplier_id');
        
        $this->db->select('pdi.cost');
        $this->db->from('purchase_delivery_items pdi');
        $this->db->join('purchase_delivery pd', 'pd.id = pdi.purchase_delivery_id');
        $this->db->join('purchase p', 'p.id = pd.purchase_id');
        $this->db->where('pdi.product_id', $product_id);
        $this->db->where('p.supplier_id', $supplier_id);
        $this->db->order_by('p.purchase_date', 'DESC');
        $this->db->order_by('p.created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            $row = $query->row();
            echo json_encode(['success' => true, 'cost' => $row->cost]);
        } else {
            echo json_encode(['success' => false, 'cost' => '0.00']);
        }
    }

	public function regenerate($id = null)
	{
		if(!$this->permission_model->has_permission('edit_sale'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$sale_id = $this->input->post('id');
				$old_sale = $this->sale_model->get_sale_single_record($sale_id);

				$this->form_validation->set_rules('invoice_date','Invoice Date','required');		
				$this->form_validation->set_rules('customer_id','Customer','required');
				$this->form_validation->set_rules('warehouse_id','Warehouse','required');


				if($this->form_validation->run()==FALSE)
				{
					$data['warehouses'] 			= $this->warehouse_model->get_records();
					$data['customers'] 				= $this->customer_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
					$this->load->view('sale/regenerate',$data);
				}
				else
				{
					$invoice_date 				= date('Y-m-d', strtotime($this->input->post('invoice_date')));
					$warehouse_id 				= $this->input->post('warehouse_id');
					$customer_id 					= $this->input->post('customer_id');

					$customer 						= $this->customer_model->get_single_record($customer_id);
					$customer_gstin 			= $customer->gstin;

					$proforma_invoice_id_array = $this->input->post('proforma_invoice_id');

					if (!is_array($proforma_invoice_id_array)) {
							$proforma_invoice_id_array = explode(',', $proforma_invoice_id_array);
					}

					$proforma_invoice_id = implode(",", $proforma_invoice_id_array);

					$reference_no 				= $this->input->post('reference_no');
					$rcm 									= $this->input->post('rcm');
					$total_taxable_value 	= $this->input->post('total_taxable_value');
					$tds 									= $this->input->post('tds');
					$total_discount 			= $this->input->post('total_discount');
					$total_tax 						= $this->input->post('total_tax');
					$total 								= $this->input->post('total');
					$internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
					$external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
					$bank_detail 					= nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
					$terms_and_condition 	= nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

          $lori_no 							= $this->input->post('lori_no');
					$carrier 							= $this->input->post('carrier');
					$vehicle_no 					= $this->input->post('vehicle_no');
					$ewaybill_no 					= $this->input->post('ewaybill_no');
					$additional_case 			= $this->input->post('additional_case');

          $ewaybill_dispatch_from 							= $this->input->post('ewaybill_dispatch_from');
					$ewaybill_mode_of_transportation 			= $this->input->post('ewaybill_mode_of_transportation');
					$ewaybill_subtype 										= $this->input->post('ewaybill_subtype');
					$ewaybill_doctype 										= $this->input->post('ewaybill_doctype');
					$ewaybill_transporter_name 						= $this->input->post('ewaybill_transporter_name');
					$ewaybill_transporter_gstin 					= $this->input->post('ewaybill_transporter_gstin');
					$ewaybill_distance_of_transportation 	= $this->input->post('ewaybill_distance_of_transportation');
					$ewaybill_transporter_doc_no 					= $this->input->post('ewaybill_transporter_doc_no');
					$ewaybill_vehicle_no 									= $this->input->post('ewaybill_vehicle_no');
					$ewaybill_vehicle_type 								= $this->input->post('ewaybill_vehicle_type');

					$due_date 							= ($this->input->post('due_date') != NULL && $this->input->post('due_date') != '') ? date('Y-m-d',strtotime($this->input->post('due_date'))) : NULL;

					$document 						= $this->input->post("document");

          if (!is_array($document)) {
            // Convert $document to an array with a single element
            $document = array($document);
          }

          $documents = implode(',', $document);
					

					$sale_data = array(
														"invoice_date"					=> $invoice_date,
														"due_date"							=> $due_date,
														"proforma_invoice_id"		=> $proforma_invoice_id,
														"warehouse_id"	  			=> $warehouse_id,
														"customer_id"						=> $customer_id,
														"customer_gstin"				=> $customer_gstin,
														"rcm"										=> $rcm,
														"total_taxable_value" 	=> $total_taxable_value,	
														"tds"							 			=> $tds,	
														"total_tax"							=> $total_tax,
														"total_discount" 				=> $total_discount,	
														"total" 								=> $total,
														"internal_note"					=> $internal_note,
														"external_note" 				=> $external_note,
														"bank_detail"	 					=> $bank_detail,
														"terms_and_condition"		=> $terms_and_condition,
														"delete_status" 				=> NOT_DELETED,

                            "lori_no" 						=> $lori_no,
														"carrier" 						=> $carrier,
														"vehicle_no" 					=> $vehicle_no,
														"ewaybill_no" 				=> $ewaybill_no,
														"additional_case" 		=> $additional_case,
														"document" 						=> $documents,
														"user_id" 						=> $this->session->userdata('user_id')
													);

          if (!empty($ewaybill_dispatch_from)) {
              $sale_data['ewaybill_dispatch_from'] = $ewaybill_dispatch_from;
          }
          
          if (!empty($ewaybill_mode_of_transportation)) {
              $sale_data['ewaybill_mode_of_transportation'] = $ewaybill_mode_of_transportation;
          }
          
          if (!empty($ewaybill_subtype)) {
              $sale_data['ewaybill_subtype'] = $ewaybill_subtype;
          }
          
          if (!empty($ewaybill_doctype)) {
              $sale_data['ewaybill_doctype'] = $ewaybill_doctype;
          }
          
          if (!empty($ewaybill_transporter_name)) {
              $sale_data['ewaybill_transporter_name'] = $ewaybill_transporter_name;
          }
          
          if (!empty($ewaybill_transporter_gstin)) {
              $sale_data['ewaybill_transporter_gstin'] = $ewaybill_transporter_gstin;
          }
          
          if (!empty($ewaybill_distance_of_transportation)) {
              $sale_data['ewaybill_distance_of_transportation'] = $ewaybill_distance_of_transportation;
          }
          
          if (!empty($ewaybill_transporter_doc_no)) {
              $sale_data['ewaybill_transporter_doc_no'] = $ewaybill_transporter_doc_no;
          }
          
          if (!empty($ewaybill_vehicle_no)) {
              $sale_data['ewaybill_vehicle_no'] = $ewaybill_vehicle_no;
          }
          
          if (!empty($ewaybill_vehicle_type)) {
              $sale_data['ewaybill_vehicle_type'] = $ewaybill_vehicle_type;
          }

          $ewaybill_transporter_doc_date 				= $this->input->post('ewaybill_transporter_doc_date');
          if($ewaybill_transporter_doc_date != '')
            $sale_data['ewaybill_transporter_doc_date'] = date('Y-m-d',strtotime($ewaybill_transporter_doc_date));

          $lori_date 				= $this->input->post('lori_date');
          if($lori_date != '')
            $sale_data['lori_date'] = date('Y-m-d',strtotime($lori_date));

					if($this->sale_model->edit_sale_record($sale_data,$sale_id))
					{
						
						$entered_sale = $this->sale_model->get_sale_single_record($sale_id);
						$customer 		= $this->customer_model->get_single_record($entered_sale->customer_id);

						// update the ledgers
						
						/****************************** Start Sale Ledger ***********************************/

						$sale_ledger 						= $this->ledger_model->get_single_record(SALE_LEDGER);
						$updated_sale_ledger 		= array('closing_balance' => ($sale_ledger->closing_balance  - $old_sale->total + $total));
						$this->ledger_model->edit_record($updated_sale_ledger,$sale_ledger->id);

						/****************************** End Sale Ledger *************************************/

						/****************************** Start Customer Ledger *******************************/

						$customer_ledger 					= $this->ledger_model->get_single_record($customer->ledger_id);
						$updated_customer_ledger  = array('closing_balance' => ($customer_ledger->closing_balance + $total - $old_sale->total  ));
						$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

						/****************************** End Customer Ledger *********************************/

						/****************************** Start TDS Ledger *******************************/

						if($tds > 0)
						{
							$tds_ledger 					= $this->ledger_model->get_single_record(TDS_LEDGER);
							$updated_tds_ledger  = array('closing_balance' => ($tds_ledger->closing_balance + $tds - $old_sale->tds));
							$this->ledger_model->edit_record($updated_tds_ledger,$tds_ledger->id);

							$customer_ledger 					= $this->ledger_model->get_single_record($customer->ledger_id);
							$updated_customer_ledger = array('closing_balance' => ($customer_ledger->closing_balance - $tds + $old_sale->tds));
							$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);
						}

						
						/****************************** End TDS Ledger *********************************/

						/****************************** Add Transaction entry *******************************/

						// Remove old transaction entry

						$this->transaction_model->delete_record_by_entry_id($entered_sale->id,SALE_MODULE);

						$transaction_header = array(
																				"entry_id"				=>  $sale_id,
																				"module"					=>  SALE_MODULE,
																				"type"						=>	SALE_TRANSACTION_TYPE,
																				"amount"					=>	$total,
																				"voucher_date"		=>	$invoice_date,
																				"from_account"		=>	SALE_LEDGER,
																				"to_account"			=>	$customer->ledger_id,
																				"reference_no"		=>	$entered_sale->reference_no
																			);
						if($transaction_id = $this->transaction_model->add_record($transaction_header))
						{
							$from_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'C',
																								"ledger_id"				=> SALE_LEDGER,
																								"dr_amount"				=> $total
																							);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

							$to_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'D',
																							"ledger_id"				=> $customer->ledger_id,
																							"cr_amount"				=> $total
																						);
							// transaction detail record
							$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
						}

						/**************************************************************************************/

						/****************************** Add TDS Transaction entry *********************************/

						if($tds > 0)
						{
							$transaction_header = array(
																					"entry_id"				=>  $sale_id,
																					"module"					=>  SALE_MODULE,
																					"type"						=>	TDS_TRANSACTION_TYPE,
																					"amount"					=>	$tds,
																					"voucher_date"		=>	$invoice_date,
																					"from_account"		=>	$customer->ledger_id,
																					"to_account"			=>	TDS_LEDGER,
																					"reference_no"		=>	$reference_no
																				);
							if($transaction_id = $this->transaction_model->add_record($transaction_header))
							{
								$from_transaction_detail = array(
																									"transaction_id" 	=> $transaction_id,
																									"voucher_type"		=> 'C',
																									"ledger_id"				=> $customer->ledger_id,
																									"dr_amount"				=> $tds
																								);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

								$to_transaction_detail = array(
																								"transaction_id" 	=> $transaction_id,
																								"voucher_type"		=> 'D',
																								"ledger_id"				=> TDS_LEDGER,
																								"cr_amount"				=> $tds
																							);
								// transaction detail record
								$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
							}	
						}

						

						/**************************************************************************************/



						$log_data = array(
											"user_id" 		=> $this->session->userdata("user_id"),
											"module"		=> "sale",
											"user_action"	=> 2,
											"b_data"		=> json_encode((array)$old_sale),
											"data"			=> json_encode((array)$entered_sale),
											"description" 	=> 'Sales (Reference No. -'  .$reference_no .') is updated successfully.'
										);
						$this->log_data_model->add_record($log_data);
						$this->sale_model->remove_sale_item_records($sale_id);

						$sale_items 		= $this->input->post('sale_items');
						$sale_items_array 	= explode("|", $this->input->post('sale_items'));

						for ($i=0; $i < sizeof($sale_items_array) ; $i++) { 
									
							$temp_sale_item = (array)json_decode($sale_items_array[$i]);
							$temp_sale_item['sale_id']  = $sale_id;

							if($temp_sale_item['expiry_date'] == '' || $temp_sale_item['expiry_date'] == '0000-00-00')
								unset($temp_sale_item['expiry_date']);
							else
								$temp_sale_item['expiry_date'] = date('Y-m-d',strtotime($temp_sale_item['expiry_date']));

							if($temp_sale_item['mfg_date'] == '' || $temp_sale_item['mfg_date'] == '0000-00-00')
								unset($temp_sale_item['mfg_date']);
							else
								$temp_sale_item['mfg_date'] = date('Y-m-d',strtotime($temp_sale_item['mfg_date']));

							if($temp_sale_item['proforma_invoice_idd'] == '')
								unset($temp_sale_item['proforma_invoice_idd']);

							$this->sale_model->add_sale_item_record($temp_sale_item);
						}

						$this->session->set_flashdata('success',  'Sales (Reference No. -'  .$reference_no .') is updated successfully.');
						redirect('sale','refresh');
					}
					else
					{
						$this->session->set_flashdata('failure',  'Sales (Reference No. -'  .$reference_no .') is failed to update.');
						redirect('sale','refresh');
					}
				}
			}
			else
			{
				$id 	= base64_decode($id);

				if($id != null)
				{
					$data['sale'] 						= $this->sale_model->get_sale_single_record($id);

					if($data['sale'] != null)
					{

            $data['countries'] 		= $this->utility_model->get_countries();

            if($data['sale']->customer_shipping_country_id != '')
              $data['states'] 		= $this->utility_model->get_states($data['sale']->customer_shipping_country_id);

            if($data['sale']->customer_shipping_state_id != '')
              $data['cities'] 		= $this->utility_model->get_cities($data['sale']->customer_shipping_state_id);
            
						$data['sale_items'] 			= $this->sale_model->get_sale_item_records($id);
						$data['customers'] 				= $this->customer_model->get_records();
						$data['customer_detail']	= $this->customer_model->get_single_record($data['sale']->customer_id);
						$data['discounts']				= $this->discount_model->get_records();
						$data['company_setting']	= $this->company_settings_model->get_company_records();	
            $data['states'] 					= $this->utility_model->get_states(101);
						$data['warehouses'] 			= $this->warehouse_model->get_records();

						$data['proforma_invoices']				= $this->proforma_invoice_model->get_proforma_invoice_records_by_customer_id($data['sale']->customer_id);



						$this->load->view('sale/regenerate',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('sale','refresh');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('sale','refresh');
				}
			}
		}
	}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_sale'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 					= $this->input->post('id');
			$sale 				= $this->sale_model->get_sale_single_record($id);
			$paid_amount 	= $this->transaction_model->get_total_transaction_amount($id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount($id,SALE_MODULE,CREDIT_TRANSACTION_TYPE);

			if($paid_amount == 0 || $paid_amount == '')
			{
				$data = array('delete_status' => 1,'proforma_invoice_id' => '');
				if($this->sale_model->edit_sale_record($data,$id))
				{
					$proforma_invoice_id_array = explode(",", $sale->proforma_invoice_id);

					for ($i=0; $i < sizeof($proforma_invoice_id_array); $i++) { 
						$this->proforma_invoice_model->edit_proforma_invoice_record(array('sale_id'=>NULL),$proforma_invoice_id_array[$i]);

						// log_message('error',$this->db->last_query());
					}

					$customer 		= $this->customer_model->get_single_record($sale->customer_id);

					// update the ledgers
					/****************************** Start Sale Ledger ***********************************/

					$sale_ledger 						= $this->ledger_model->get_single_record(SALE_LEDGER);
					$updated_sale_ledger 		= array('closing_balance' => ($sale_ledger->closing_balance - $sale->total));
					$this->ledger_model->edit_record($updated_sale_ledger,$sale_ledger->id);

					/****************************** End Sale Ledger ***********************************/

					/****************************** Start Customer Ledger ***********************************/

					$customer_ledger 					= $this->ledger_model->get_single_record($customer->ledger_id);
					$updated_customer_ledger  = array('closing_balance' => ($customer_ledger->closing_balance - $sale->total));
					$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

					/****************************** End Customer Ledger ***********************************/

					/****************************** Start TDS Ledger ***********************************/

					$tds_ledger 					= $this->ledger_model->get_single_record(TDS_LEDGER);
					$updated_tds_ledger  = array('closing_balance' => ($tds_ledger->closing_balance - $sale->tds));
					$this->ledger_model->edit_record($updated_tds_ledger,$tds_ledger->id);

					/****************************** End TDS Ledger ***********************************/

					// Remove old transaction entry
					$this->transaction_model->delete_record_by_entry_id($id,SALE_MODULE);


					$this->session->set_flashdata('success', 'Sales (Reference No. -'.$sale->reference_no.') is deleted successfully.');
					redirect('sale','refresh');
				}
				else
				{
					$this->session->set_flashdata('failure', 'Sales (Reference No. -'.$sale->reference_no.') is failed to delete.');
					redirect('sale','refresh');
				}	
			}
			else
			{
				$this->session->set_flashdata('failure', 'Please delete the transaction entry to delete this Sale:'.$sale->reference_no);
				redirect('sale','refresh');
			}
	
		}
	}
	


public function view($id = null)
{
   $user_role = $this->session->userdata('role'); // ✅ FIXED KEY

if ($user_role !== 'client') {
    if (!$this->permission_model->has_permission('view_sale')) {
        $this->load->view('errors/html/error_restricted');
        return;
    }
}
 else {
    // Client – allow ONLY own sales
    $client_customer_id = $this->session->userdata('customer_id');

    if ($data['sale']->customer_id != $client_customer_id) {
        $this->load->view('errors/html/error_restricted');
        return;
    }
}
    // if(!$this->permission_model->has_permission('view_sale'))
    // {
    //     $this->load->view('errors/html/error_restricted'); 
    // }
    // else
    // {
    //     if (!$this->input->is_ajax_request()) 
    //     {
    //         if($id != null)
    //         {
    //             $id = base64_decode($id);

    //             $data['sale'] = $this->sale_model->get_sale_single_record($id);
    
    
    if (!$this->input->is_ajax_request()) 
    {
        if ($id != null) 
        {
            $id = base64_decode($id);

            if (!$id) {
                $this->session->set_flashdata('failure', 'Invalid or broken URL.');
                redirect('sale', 'refresh');
            }

            $data['sale'] = $this->sale_model->get_sale_single_record($id);

            if (!$data['sale']) {
                $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
                redirect('sale', 'refresh');
            }

            /**
             * =========================================================
             * CLIENT SECURITY CHECK (VERY IMPORTANT)
             * =========================================================
             * Client can view ONLY their own company sale
             */
//           if ($user_role === 'client') {
//     $client_customer_id = $this->session->userdata('customer_id');

//     if ($data['sale']->customer_id != $client_customer_id) {
//         $this->load->view('errors/html/error_restricted');
//         return;
//     }
// }
                // $data['proforma_invoice'] = $this->proforma_invoice_model->get_proforma_invoice_records_by_sale_id($data['sale']->id);
                
                $data['proforma_invoice'] = [];
if (!empty($data['sale'])) {
    $data['proforma_invoice'] =
        $this->proforma_invoice_model
            ->get_proforma_invoice_records_by_sale_id($data['sale']->id);
}
                //new code 
                $data['transport'] = null;
if (!empty($data['sale']->proforma_invoice_id)) {
    $data['transport'] = $this->db
        ->where('id', $data['sale']->proforma_invoice_id)
        ->get('sale_requests')
        ->row();
}
//ends here
                if($data['sale'] != null)
                {
                    $data['sale_items'] = $this->sale_model->get_sale_item_records($id);
                    $data['customers'] = $this->customer_model->get_records();
                    $data['customer_detail'] = $this->customer_model->get_single_record($data['sale']->customer_id);
                    $data['discounts'] = $this->discount_model->get_records();
                    $data['company_setting'] = $this->company_settings_model->get_company_records();    
                    $warehouse_id = $data['sale']->warehouse_id;
                    $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);

                    // Get shipping address details
                    // if($data['sale']->selected_shipping_address_id) {
                    //     // Get the specific shipping address selected for this sale
                    //     $data['shipping_address'] = $this->customer_model->get_shipping_address_by_id(
                    //         $data['sale']->selected_shipping_address_id,
                    //         $data['sale']->customer_id
                    //     );
                    // } else {
                    //     // Fallback to customer's default shipping address if none selected
                    //     $data['shipping_address'] = $this->customer_model->get_default_shipping_address($data['sale']->customer_id);
                        
                    //     // If still no address, use customer's main address as fallback
                    //     if(!$data['shipping_address']) {
                    //         $data['shipping_address'] = (object)[
                    //             'shipping_name' => $data['customer_detail']->customer_name,
                    //             'shipping_address' => $data['customer_detail']->address,
                    //             'shipping_pincode' => $data['customer_detail']->pincode,
                    //             'state_name' => $data['customer_detail']->state_name,
                    //             'city_name' => $data['customer_detail']->city_name
                    //         ];
                    //     }
                    // }
                    
                    // =====================================================
 
// =====================================================
// SHIPPING ADDRESS (SALE LEVEL) — FIXED (NO DB QUERY)
// =====================================================
// =====================================================
// SHIPPING ADDRESS — SALE LEVEL (ADDRESS + PIN + STATE + CITY)
// =====================================================
if (!empty($data['sale']->customer_shipping_address)) {

    // Fetch state name
    $state = $this->db
        ->select('name')
        ->where('id', $data['sale']->customer_shipping_state_id)
        ->get('states')        // 🔁 CHANGE IF NEEDED
        ->row();

    // Fetch city name
    $city = $this->db
        ->select('name')
        ->where('id', $data['sale']->customer_shipping_city_id)
        ->get('cities')        // 🔁 CHANGE IF NEEDED
        ->row();

    $data['shipping_address'] = (object)[
        'shipping_name'    => $data['customer_detail']->customer_name,
        'shipping_address' => $data['sale']->customer_shipping_address,
        'shipping_pincode' => $data['sale']->customer_shipping_pincode,
        'state_name'       => $state->name ?? '',
        'city_name'        => $city->name ?? ''
    ];

} elseif ($data['sale']->selected_shipping_address_id) {

    $data['shipping_address'] =
        $this->customer_model->get_shipping_address_by_id(
            $data['sale']->selected_shipping_address_id,
            $data['sale']->customer_id
        );

} else {

    $data['shipping_address'] =
        $this->customer_model->get_default_shipping_address(
            $data['sale']->customer_id
        );

    if (!$data['shipping_address']) {
        $data['shipping_address'] = (object)[
            'shipping_name'    => $data['customer_detail']->customer_name,
            'shipping_address' => $data['customer_detail']->address,
            'shipping_pincode' => $data['customer_detail']->pincode,
            'state_name'       => $data['customer_detail']->state_name,
            'city_name'        => $data['customer_detail']->city_name
        ];
    }
}


                    
                    // echo "<pre>";
                    // print_r($data);
                    // echo "</pre>";
                    // die;

                    $this->load->view('sale/view',$data);
                }
                else
                {
                    $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                    redirect('sale','refresh');
                }
            }
            else
            {
                $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                redirect('sale','refresh');
            }
        }
        else
        {
            
            $response = array();

            $sale_view_data['sale'] = $this->sale_model->get_sale_single_record($id);
            $sale_view_data['customer_detail'] = $this->customer_model->get_single_record($sale_view_data['sale']->customer_id);    
            $sale_view_data['paid_amount'] = $this->transaction_model->get_total_transaction_amount($id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE) + 
                                           $this->transaction_model->get_total_transaction_amount($id,SALE_MODULE,CREDIT_TRANSACTION_TYPE) + 
                                           $this->transaction_model->get_total_transaction_amount($id,SALE_MODULE,TDS_TRANSACTION_TYPE);
            
            // Get shipping address for AJAX response if needed
            if($sale_view_data['sale']->selected_shipping_address_id) {
                $sale_view_data['shipping_address'] = $this->customer_model->get_shipping_address_by_id(
                    $sale_view_data['sale']->selected_shipping_address_id,
                    $sale_view_data['sale']->customer_id
                );
            } else {
                $sale_view_data['shipping_address'] = $this->customer_model->get_default_shipping_address($sale_view_data['sale']->customer_id);
            }


        

            $response['sale_view'] = $this->load->view('sale/ajax/view',$sale_view_data,TRUE);
            $response['due_amount'] = $sale_view_data['sale']->total - $sale_view_data['paid_amount'];

            /*************************** Start Add Payment View ******************************/
            $add_payment_data['to_account'] = $this->ledger_model->get_records(array(BANK_ACCOUNT_GROUP,CASH_GROUP));            
            $response['from_account'] = $this->customer_model->get_single_record($sale_view_data['sale']->customer_id)->ledger_id;
            $response['add_transaction'] = $this->load->view('sale/ajax/add_payment',$add_payment_data,TRUE);
            /*************************** End Add Payment View ******************************/

            /*************************** Start Previous Transaction ******************************/
            $previous_transaction['transactions'] = $this->transaction_model->get_records_entry_id_and_module_wise($id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE);
            $response['previous_transaction_view'] = $this->load->view('sale/ajax/transaction',$previous_transaction,TRUE);
            /*************************** End Previous Transaction ******************************/

            echo json_encode($response);
        }
    }




	public function view_delivery()
	{
		if ($this->input->is_ajax_request()) 
		{
			$sale_id 																= $this->input->post('sale_id');
			$delivery_detail_data['sale_deliveries']= $this->sale_delivery_model->get_delivery_records($sale_id);
			$delivery_detail_data['sale_items']			= $this->sale_model->get_sale_item_records($sale_id);
			$delivery_detail_data['users']							= $this->ion_auth_model->users()->result();

			$response 														= array();
			$response['add_delivery_detail'] 			= $this->load->view('sale/ajax/add_delivery_detail',$delivery_detail_data,TRUE);
			$response['sale_delivery_detail'] = $this->load->view('sale/ajax/view_sale_delivery_detail',$delivery_detail_data,TRUE);
			$response['delivery_transaction'] 		= $this->load->view('sale/ajax/delivery_transaction',$delivery_detail_data,TRUE);
			$response['is_all_products_delivered']= $this->is_all_sale_products_delivered($sale_id);

			echo json_encode($response);  
		}
	}

	public function add_sale_delivery($sale_id = null)
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$sale_id 		= $this->input->post('sale_id');
			$delivery_date 	= $this->input->post('delivery_date');
			$delivered_by 		= $this->input->post('delivered_by');

			$delivery_items = $this->input->post('delivery_items');



			$delivery_data = array(
														"sale_id"					=> $sale_id,
														"delivery_date" 	=> date('Y-m-d', strtotime($delivery_date)),
														"delivered_by"		=> $delivered_by,
														"user_id" 				=> $this->session->userdata('user_id')
													);

			// being transaction
			$this->db->trans_begin();	

			if($sale_delivery_id = $this->sale_delivery_model->add_delivery_record($delivery_data))
			{
				$delivery_items_array 	= explode("|", $delivery_items);

				for ($i=0; $i < sizeof($delivery_items_array) ; $i++) 
				{			
					$temp_delivery_item = (array)json_decode($delivery_items_array[$i]);

					if(sizeof($temp_delivery_item) > 0 && $temp_delivery_item['quantity'] > 0)
					{
						/******************************** ADD PRODUCT DELIVERY ******************************************/	
						
						$temp_delivery_item['sale_delivery_id']  = $sale_delivery_id;
						$this->sale_delivery_model->add_delivery_item_record($temp_delivery_item);

						/******************************** ADD PRODUCT TO WAREHOUSE **************************************/	
						

						$product_id 			= $temp_delivery_item['product_id'];
						$quantity 				= $temp_delivery_item['quantity'];

						$product 					= $this->product_model->get_single_record($product_id);
						$sale_delivery 		= $this->sale_delivery_model->get_delivery_single_record($sale_delivery_id);
						$sale 						=	$this->sale_model->get_sale_single_record($sale_delivery->sale_id);
						$sale_item 				= $this->sale_model->get_single_sale_item_record($sale->id,$product_id);
						$warehouse_id 		= $sale->warehouse_id;					
						$cost 						= $sale_item->cost;
						$price 						= $sale_item->price;


						if($product->manage_inventory == MANAGE_INVENTORY_YES)
						{
									// if product with zero cost doesn't exist then check product with given cost.
							$warehouse_product = $this->warehouse_products_model->get_single_record($sale_item->warehouse_product_id);

							
							// update the product quantity
							$new_quantity = $warehouse_product->quantity - $quantity;
							$warehouse_product_data = array(
																						"quantity"			=> $new_quantity
																					);
							$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id);
						}
						
						/******************************** END ADD PRODUCT TO WAREHOUSE **************************************/

					}
					
				}

				// commit transaction
				$this->db->trans_commit();	

				$response = array();
				$response['code'] = 1;
				$response['message'] = 'Delivery successfully added.';
				$response['is_all_products_delivered'] = $this->is_all_sale_products_delivered($sale_id);

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

			$delivery_detail_data['sale_items'] 		= $this->sale_model->get_sale_item_records($sale_id);
	 
// 			$delivery_detail_data['users']							= $this->ion_auth_model->users()->result();
 
             // Direct database query to get users from groups 1, 24, and 26
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->join('users_groups', 'users_groups.user_id = users.id');
        $this->db->where_in('users_groups.group_id', [1, 24, 26]);
        $this->db->where('users.active', 1); // Only active users
        $this->db->group_by('users.id'); // Prevent duplicates if user has multiple groups
        $delivery_detail_data['users'] = $this->db->get()->result();            
			$response 																	= array();
			$response['add_delivery_detail'] 						= $this->load->view('sale/ajax/add_delivery_detail',$delivery_detail_data,TRUE);

			echo json_encode($response);
			
		}
	}

	public function delete_sale_delivery()
	{
		$sale_delivery_id 		= $this->input->post('sale_delivery_id');
		$sale_delivery 				= $this->sale_delivery_model->get_delivery_single_record($sale_delivery_id);
		$sale_delivery_items 	= $this->sale_delivery_model->get_delivery_item_records($sale_delivery_id);
		
		// being transaction
		$this->db->trans_begin();	

		if($this->sale_delivery_model->delete_delivery_record($sale_delivery_id))
		{
			foreach ($sale_delivery_items as $item) 
			{
				$this->sale_delivery_model->delete_delivery_item_record($sale_delivery_id,$item->product_id);

				/*************************** UPDATE PRODUCT QUANTITY IN WAREHOUSE ************************************/

				$delivery_quantity	= $item->quantity;

				$product 						= $this->product_model->get_single_record($item->product_id);
				$sale 							= $this->sale_model->get_sale_single_record($sale_delivery->sale_id);
				$sale_item 					= $this->sale_model->get_single_sale_item_record($sale->id,$item->product_id);
				$cost 							= $sale_item->cost;
				$price 							= $sale_item->price;
				$selling_price 							= $sale_item->selling_price;
				$warehouse_id 			= $sale->warehouse_id;
				$product_id 				= $item->product_id;


				if($product->manage_inventory == MANAGE_INVENTORY_YES)
				{
					$warehouse_product = $this->warehouse_products_model->get_single_record($sale_item->warehouse_product_id);
					// Only reduce the quantity of product

					$new_quantity = $warehouse_product->quantity + $item->quantity;

					$warehouse_product_data = array(
																				"quantity"			=> $new_quantity
																			);
					$this->warehouse_products_model->edit_record($warehouse_product_data,$warehouse_product->id); 
				}


				
				

				/*************************** END UPDATE PRODUCT QUANTITY IN WAREHOUSE ********************************/
			}
			
			// commit transaction
			$this->db->trans_commit();	

			$response = array();
			$response['code'] = 1;
			$response['message'] = 'Delivery successfully deleted.';
			$response['is_all_products_delivered'] = $this->is_all_sale_products_delivered($sale_delivery->sale_id);
			
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
    if ($id !== null) {
        $id = base64_decode($id);
        $data['sale'] = $this->sale_model->get_sale_single_record($id);
        $data['transport'] = $data['sale'];
        $data['sale_items'] = $this->sale_model->get_sale_item_records($id);
        $data['customer_detail'] = $this->customer_model->get_single_record($data['sale']->customer_id);
        $data['discounts'] = $this->discount_model->get_records();
        $data['company_setting'] = $this->company_settings_model->get_company_records();
        $data['proforma_invoice'] = $this->proforma_invoice_model->get_proforma_invoice_records_by_sale_id($data['sale']->id);
        
        $warehouse_id = $data['sale']->warehouse_id;
        $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);
                // Get shipping address details
                    // if($data['sale']->selected_shipping_address_id) {
                    //     // Get the specific shipping address selected for this sale
                    //     $data['shipping_address'] = $this->customer_model->get_shipping_address_by_id(
                    //         $data['sale']->selected_shipping_address_id,
                    //         $data['sale']->customer_id
                    //     );
                    // } else {
                    //     // Fallback to customer's default shipping address if none selected
                    //     $data['shipping_address'] = $this->customer_model->get_default_shipping_address($data['sale']->customer_id);
                        
                    //     // If still no address, use customer's main address as fallback
                    //     if(!$data['shipping_address']) {
                    //         $data['shipping_address'] = (object)[
                    //             'shipping_name' => $data['customer_detail']->customer_name,
                    //             'shipping_address' => $data['customer_detail']->address,
                    //             'shipping_pincode' => $data['customer_detail']->pincode,
                    //             'state_name' => $data['customer_detail']->state_name,
                    //             'city_name' => $data['customer_detail']->city_name
                    //         ];
                    //     }
                    // }
                    
                    // =====================================================
// SHIPPING ADDRESS — SALE LEVEL (PDF)
// =====================================================
if (!empty($data['sale']->customer_shipping_address)) {

    // Fetch state name
    $state = $this->db
        ->select('name')
        ->where('id', $data['sale']->customer_shipping_state_id)
        ->get('states')   // 🔁 change table name if needed
        ->row();

    // Fetch city name
    $city = $this->db
        ->select('name')
        ->where('id', $data['sale']->customer_shipping_city_id)
        ->get('cities')   // 🔁 change table name if needed
        ->row();

    $data['shipping_address'] = (object)[
        'shipping_name'    => $data['customer_detail']->customer_name,
        'shipping_address' => $data['sale']->customer_shipping_address,
        'shipping_pincode' => $data['sale']->customer_shipping_pincode,
        'state_name'       => $state->name ?? '',
        'city_name'        => $city->name ?? ''
    ];

} elseif ($data['sale']->selected_shipping_address_id) {

    $data['shipping_address'] =
        $this->customer_model->get_shipping_address_by_id(
            $data['sale']->selected_shipping_address_id,
            $data['sale']->customer_id
        );

} else {

    $data['shipping_address'] =
        $this->customer_model->get_default_shipping_address(
            $data['sale']->customer_id
        );

    if (!$data['shipping_address']) {
        $data['shipping_address'] = (object)[
            'shipping_name'    => $data['customer_detail']->customer_name,
            'shipping_address' => $data['customer_detail']->address,
            'shipping_pincode' => $data['customer_detail']->pincode,
            'state_name'       => $data['customer_detail']->state_name,
            'city_name'        => $data['customer_detail']->city_name
        ];
    }
}

        // Add full server path for QR code
        if(!empty($data['sale']->qr)) {
            $qr_path = FCPATH . 'uploads/qr_codes/' . $data['sale']->qr;
            $data['qr_code_path'] = file_exists($qr_path) ? $qr_path : null;
        } else {
            $data['qr_code_path'] = null;
        }
        
        $html = $this->load->view('sale/pdf', $data, true);

        // Load HTML content into Dompdf
        $this->pdf->loadHtml($html);
        
        // Set paper size and orientation
        $this->pdf->setPaper('A4', 'portrait');
        
        // Render the PDF
        $this->pdf->render();
        
        // Stream the PDF to the browser
        $this->pdf->stream("Sale-{$data['sale']->reference_no}.pdf", array("Attachment" => 1));
    } else {
        $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
        redirect('sale', 'refresh');
    }
}

public function download_qr_codes($encoded_id)
{
    if ($encoded_id !== null) {
        $id = base64_decode($encoded_id);
        $data['sale'] = $this->sale_model->get_sale_single_record($id);
        
        if (!$data['sale'] || empty($data['sale']->box_qr_data)) {
            $this->session->set_flashdata('failure', 'No QR codes found for this sale.');
            redirect('sale/view/'.$encoded_id);
        }
        
        $box_data = json_decode($data['sale']->box_qr_data, true);
        
        // Add full filesystem paths to each box data
        foreach ($box_data as &$box) {
            $box['qr_full_path'] = FCPATH.'uploads/qr_codes/boxes/'.$box['qr_filename'];
        }
        
        $data['box_data'] = $box_data;
        $html = $this->load->view('sale/qr_codes_pdf', $data, true);

        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        
        // Enable remote images
        $this->pdf->set_option('isRemoteEnabled', true);
        
        $this->pdf->render();
        $this->pdf->stream("QR_Codes_Sale_{$data['sale']->reference_no}.pdf", array("Attachment" => 1));
    } else {
        $this->session->set_flashdata('failure', 'You have tried to access a broken URL.');
        redirect('sale', 'refresh');
    }
}

  public function download_payment_receipt($id = null)
	{
		
		if($id != null)
		{	
			$id 									= base64_decode($id);

      $transaction_id = $this->input->get('transaction_id'); 

			$data['sale'] 				= $this->sale_model->get_sale_single_record($id);
			$data['sale_items'] 		= $this->sale_model->get_sale_item_records($id);
			$data['customer_detail']	= $this->customer_model->get_single_record($data['sale']->customer_id);
			//$data['discounts']			= $this->discount_model->get_records();
			$data['company_setting']	= $this->company_settings_model->get_company_records();	

      $data['transaction'] =  $this->transaction_model->get_single_record($transaction_id);
    

			$html = $this->load->view('sale/download_receipt',$data,true);

			// echo $html;
			// exit;
			
			$this->pdf->loadHtml($html);
			$this->pdf->render();
			$this->pdf->stream("PaymentReceipt.pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('sale','refresh');
		}
	}

	public function delivery_challan($id = null)
	{
		
		if($id != null)
		{	
			$id 									= base64_decode($id);
			$data['sale'] 				= $this->sale_model->get_sale_single_record($id);
			$data['sale_items'] 		= $this->sale_model->get_sale_item_records($id);
			$data['customer_detail']	= $this->customer_model->get_single_record($data['sale']->customer_id);
			$data['discounts']			= $this->discount_model->get_records();
			$data['company_setting']	= $this->company_settings_model->get_company_records();	

			$html = $this->load->view('sale/delivery_challan',$data,true);
			
			$this->pdf->loadHtml($html);
			$this->pdf->render();
			$this->pdf->stream("Delivery_Challan-".$data['sale']->reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('sale','refresh');
		}
	}

	function email_invoice()
	{	
		$sale_id 												= $this->input->post('sale_id');

		$company_setting 								= $this->company_settings_model->get_company_records();	
		$sale  													= $this->sale_model->get_sale_single_record($sale_id);
		$sale_items 										= $this->sale_model->get_sale_item_records($sale_id);
		$customer_detail 								= $this->customer_model->get_single_record($sale->customer_id);

		$data['customer']								= $customer_detail;
		$data['sale']										= $sale;
		$data['company_setting']				= $company_setting;
		$data['sale_items'] 						= $sale_items;
		$data['customer_detail']				= $customer_detail;

		$mail_data 											= array();
		
		$mail_data['From'] 							= $company_setting->email;
		$mail_data['FromName'] 					= $company_setting->company_name;
		$mail_data['AddReplyTo'] 				= $company_setting->email;
		$mail_data['ConfirmReadingTo']	= $company_setting->email;
		$mail_data['To']								= $customer_detail->email;
		$mail_data['Subject'] 					= "Invoice #".$sale->reference_no." has generated by ".$company_setting->company_name;

		$mail_data['Body'] 							= $this->load->view('sale/pdf',$data,true);


		$response = array();

		if($customer_detail->email != '')
		{
			if($this->email_model->send_mail($mail_data))
			{
				$response['code'] 			= 1;
				$response['message'] 		= 'Invoice #'.$sale->reference_no.' has been sent to '.$customer_detail->customer_name;
				echo json_encode($response);
			}
			else
			{
				$response['code'] 			= 0;
				$response['message'] 		= 'Failed to send Invoice #'.$sale->reference_no.' has been sent to '.$customer_detail->customer_name;
				echo json_encode($response);
			}	
		}
		else
		{
			$response['code'] 				= 0;
			$response['message'] 			= $customer_detail->email.' records does not have email';
			echo json_encode($response);
		}
	}

	public function is_all_sale_products_delivered($sale_id)
	{
		$total_ordered_quantity			= $this->sale_delivery_model->get_total_no_of_quantity_ordered($sale_id);
		$total_delivered_quantity		= $this->sale_delivery_model->get_total_no_of_quantity_delivered($sale_id);

		if(($total_ordered_quantity - $total_delivered_quantity) == 0)
			return true;
		else
			return false;
	}

	// function convert_from_quotation($quotation_id,$po_no = NULL,$po_date = NULL)
	// {	
	// 	$quotation_id 		= base64_decode($quotation_id);

	// 	$sale 						= $this->sale_model->get_sale_single_record_by_quotation_id($quotation_id);

	// 	if($sale == null)
	// 	{
	// 		$quotation 				= $this->quotation_model->get_quotation_single_record($quotation_id);
	// 		$quotation_items 	= $this->quotation_model->get_quotation_item_records($quotation_id);
	// 		$customer 				= $this->customer_model->get_single_record($quotation->customer_id);
			


	// 		$sale_data = array(
	// 												"invoice_date"				=> date('Y-m-d'),
	// 												"reference_no"				=> time(),
	// 												"quotation_id"				=> $quotation->id,
	// 												"warehouse_id"	  		=> $quotation->warehouse_id,
	// 												"customer_id"					=> $quotation->customer_id,
	// 												"customer_gstin"			=> $customer->gstin,
	// 												"rcm"									=> $quotation->rcm,
	// 												"total_taxable_value" => $quotation->total_taxable_value,	
	// 												"tds" 								=> 0,	
	// 												"total_tax"						=> $quotation->total_tax,
	// 												"total_discount" 			=> $quotation->total_discount,	
	// 												"total" 							=> $quotation->total,
	// 												"internal_note"				=> $quotation->internal_note,
	// 												"external_note" 			=> $quotation->external_note,
	// 												"bank_detail" 				=> $quotation->bank_detail,
	// 												"terms_and_condition" => $quotation->terms_and_condition,
	// 												"user_id" 						=> $this->session->userdata('user_id')
	// 											);

	// 		// being transaction
	// 		$this->db->trans_begin();

	// 		if($id = $this->sale_model->add_sale_record($sale_data))
	// 		{
	// 			$entered_sale 	= $this->sale_model->get_sale_single_record($id);
	// 			$customer 			= $this->customer_model->get_single_record($entered_sale->customer_id);

	// 			// update the ledgers
				
	// 			/****************************** Start Sale Ledger ***********************************/

	// 			$sale_ledger 								= $this->ledger_model->get_single_record(SALE_LEDGER);
	// 			$updated_sale_ledger 				= array('closing_balance' => ($sale_ledger->closing_balance + $sale_data['total']));
	// 			$this->ledger_model->edit_record($updated_sale_ledger,$sale_ledger->id);

	// 			/****************************** End Sale Ledger ***********************************/

	// 			/****************************** Start Customer Ledger ***********************************/

	// 			$customer_ledger 						= $this->ledger_model->get_single_record($customer->ledger_id);
	// 			$updated_customer_ledger    = array('closing_balance' => ($customer_ledger->closing_balance + $sale_data['total']));
	// 			$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

	// 			/****************************** End Customer Ledger ***********************************/

	// 			/****************************** Start TDS Ledger ***********************************/

	// 			if($sale_data['tds'] > 0)
	// 			{
	// 				$tds_ledger 						= $this->ledger_model->get_single_record(TDS_LEDGER);
	// 				$updated_tds_ledger    	= array('closing_balance' => ($tds_ledger->closing_balance + $sale_data['tds']));
	// 				$this->ledger_model->edit_record($updated_tds_ledger,$tds_ledger->id);

	// 				$customer_ledger 						= $this->ledger_model->get_single_record($customer->ledger_id);
	// 				$updated_customer_ledger 		= array('closing_balance' => ($customer_ledger->closing_balance - $sale_data['tds']));
	// 				$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);
	// 			}

				
	// 			/****************************** End TDS Ledger ***********************************/

	// 			/****************************** Add Transaction entry *********************************/

	// 			$transaction_header = array(
	// 																	"entry_id"				=>  $id,
	// 																	"module"					=>  SALE_MODULE,
	// 																	"type"						=>	SALE_TRANSACTION_TYPE,
	// 																	"amount"					=>	$sale_data['total'],
	// 																	"voucher_date"		=>	$sale_data['invoice_date'],
	// 																	"from_account"		=>	SALE_LEDGER,
	// 																	"to_account"			=>	$customer->ledger_id,
	// 																	"reference_no"		=>	$sale_data['reference_no']
	// 																);
	// 			if($transaction_id = $this->transaction_model->add_record($transaction_header))
	// 			{
	// 				$from_transaction_detail = array(
	// 																					"transaction_id" 	=> $transaction_id,
	// 																					"voucher_type"		=> 'C',
	// 																					"ledger_id"				=> SALE_LEDGER,
	// 																					"dr_amount"				=> $sale_data['total']
	// 																				);
	// 				// transaction detail record
	// 				$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

	// 				$to_transaction_detail = array(
	// 																				"transaction_id" 	=> $transaction_id,
	// 																				"voucher_type"		=> 'D',
	// 																				"ledger_id"				=> $customer->ledger_id,
	// 																				"cr_amount"				=> $sale_data['total']
	// 																			);
	// 				// transaction detail record
	// 				$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
	// 			}

	// 			/**************************************************************************************/


	// 			/****************************** Add TDS Transaction entry *********************************/

	// 			if($sale_data['tds'] > 0)
	// 			{
	// 				$transaction_header = array(
	// 																		"entry_id"				=>  $id,
	// 																		"module"					=>  SALE_MODULE,
	// 																		"type"						=>	TDS_TRANSACTION_TYPE,
	// 																		"amount"					=>	$sale_data['tds'],
	// 																		"voucher_date"		=>	$sale_data['invoice_date'],
	// 																		"from_account"		=>	$customer->ledger_id,
	// 																		"to_account"			=>	TDS_LEDGER,
	// 																		"reference_no"		=>	$sale_data['reference_no']
	// 																	);
	// 				if($transaction_id = $this->transaction_model->add_record($transaction_header))
	// 				{
	// 					$from_transaction_detail = array(
	// 																						"transaction_id" 	=> $transaction_id,
	// 																						"voucher_type"		=> 'C',
	// 																						"ledger_id"				=> $customer->ledger_id,
	// 																						"dr_amount"				=> $sale_data['tds']
	// 																					);
	// 					// transaction detail record
	// 					$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

	// 					$to_transaction_detail = array(
	// 																					"transaction_id" 	=> $transaction_id,
	// 																					"voucher_type"		=> 'D',
	// 																					"ledger_id"				=> TDS_LEDGER,
	// 																					"cr_amount"				=> $sale_data['tds']
	// 																				);
	// 					// transaction detail record
	// 					$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
	// 				}	
	// 			}
				
	// 			/**************************************************************************************/

	// 			$log_data = array(
	// 								"user_id" 		=> $this->session->userdata("user_id"),
	// 								"module"			=> "sale",
	// 								"entry_id"		=> $id,
	// 								"user_action"	=> 1,
	// 								"data"				=> json_encode((array)$entered_sale),
	// 								"description" => 'Sales (Reference No. -'  .$sale_data['reference_no'] .') is added successfully.'
	// 							);
	// 			$this->log_data_model->add_record($log_data);
				
	// 			foreach ($quotation_items as $value) {
					
	// 				$product = $this->product_model->get_single_record($value->product_id);
	// 				$uom = $this->uom_model->get_single_record($product->uom_id);

	// 				$temp_sale_item = array(
	// 																'product_id'       => $value->product_id,
	// 										            'product_name'     => $value->product_name,
	// 										            'description'      => $value->description,
	// 										            'quantity'         => $value->quantity,
	// 										            'price'            => $value->price,
	// 										            'cost'             => $value->cost,
	// 										            'taxable_value'    => $value->taxable_value,
	// 										            'discount_id'      => $value->discount_id,
	// 										            'discount_type'    => $value->discount_type,
	// 										            'discount_value'   => $value->discount_value,
	// 										            'discount_amount'  => $value->discount_amount,
	// 										            'uom_id'           => $uom->id,
	// 										            'uom_name'         => $uom->name,
	// 										            'uom_uom'          => $uom->uom,
	// 										            'tax_id'           => $value->tax_id,
	// 										            'tax_type'         => $value->tax_type,
	// 										            'igst'             => $value->igst,
	// 										            'igst_tax'         => $value->igst_tax,
	// 										            'cgst'             => $value->cgst,
	// 										            'cgst_tax'         => $value->cgst_tax,
	// 										            'sgst'             => $value->sgst,
	// 										            'sgst_tax'         => $value->sgst_tax,
	// 										            'sub_total'        => $value->sub_total,
	// 										            'sale_id' 				 => $id
	//  															);

	// 				$this->sale_model->add_sale_item_record($temp_sale_item);
	// 			}

	// 			// commit transaction
	// 			$this->db->trans_commit();

	// 			$this->session->set_flashdata('success',  'Sales (Reference No. -'  .$sale_data['reference_no'] .') is added successfully.');
	// 			redirect('sale/view/'.base64_encode($id),'refresh');
	// 		}
	// 		else
	// 		{
	// 			// rollback transaction
	// 			$this->db->trans_rollback();
				
	// 			$this->session->set_flashdata('failure',  'Sales (Reference No. -'  .$sale_data['reference_no'] .') is failed to add.');
	// 			redirect('sale/view/'.base64_encode($id),'refresh');
	// 		}	
	// 	}
	// 	else
	// 	{
	// 		$this->session->set_flashdata('success',  'Sale (Reference No. -'  .$sale->reference_no.') is already exist');
	// 		redirect('sale/view/'.base64_encode($sale->id),'refresh');
	// 	}
	// }

	function ewaybill($sale_id = null,$multiple = false)
	{
		$company_setting 	= $this->company_settings_model->get_company_records();
		$ewaybill_array   = array();

		if($sale_id != null && $multiple == false)
		{
			$ewaybill 	= array();
			$sale_id 		= base64_decode($sale_id);
			$sale 			= $this->sale_model->get_sale_single_record($sale_id);
			$customer   = $this->customer_model->get_single_record($sale->customer_id);
			$sale_items = $this->sale_model->get_sale_item_records_for_ewaybill($sale_id);
			$sale_tax   = $this->sale_model->get_tax_calculation_by_sale_id($sale_id);
			$mainHsnCode= $this->sale_model->get_mainhsncode_by_sale_id($sale_id);

			// echo '<pre>';
			// print_r($sale_items);
			// exit;


			foreach ($sale_items as $key => $object) 
			{
		    $sale_items[$key]->taxableAmount = (float)number_format(round($object->taxableAmount,2), 2, '.', '');
		    $sale_items[$key]->igstRate = (float)number_format(round($object->igstRate,2), 2, '.', '');
		    $sale_items[$key]->cgstRate = (float)number_format(round($object->cgstRate,2), 2, '.', '');
		    $sale_items[$key]->sgstRate = (float)number_format(round($object->sgstRate,2), 2, '.', '');
			}


			
			$saleData 	= array();
			$saleData["userGstin"] = $company_setting->gstin;
			$saleData["supplyType"] = "O" ;

			if($sale->ewaybill_subtype == "supply")
				$saleData["subSupplyType"] = 1;
			else if($sale->ewaybill_subtype == "import")
				$saleData["subSupplyType"] = 2;
			else if($sale->ewaybill_subtype == "export")
				$saleData["subSupplyType"] = 3;
			else if($sale->ewaybill_subtype == "job_work")
				$saleData["subSupplyType"] = 4;
			else if($sale->ewaybill_subtype == "for_own_use")
				$saleData["subSupplyType"] = 5;
			else if($sale->ewaybill_subtype == "job_work_returns")
				$saleData["subSupplyType"] = 6;
			else if($sale->ewaybill_subtype == "sales_return")
				$saleData["subSupplyType"] = 7;
			else if($sale->ewaybill_subtype == "others")
				$saleData["subSupplyType"] = 8;
			else if($sale->ewaybill_subtype == "skd/ckd")
				$saleData["subSupplyType"] = 9;
			else if($sale->ewaybill_subtype == "line_sales")
				$saleData["subSupplyType"] = 10;
			else if($sale->ewaybill_subtype == "receipient_not_known")
				$saleData["subSupplyType"] = 11;
			else if($sale->ewaybill_subtype == "exhibition_or_fairs")
				$saleData["subSupplyType"] = 12;
			
			$saleData["subSupplyDesc"] = '';

			if($sale->ewaybill_doctype == "tax_invoice")
				$saleData["docType"] = "INV";
			else
				$saleData["docType"] = "BIL";

			$saleData["docNo"] = $sale->reference_no;
			$saleData["docDate"] = ($sale->invoice_date != '' && $sale->invoice_date != '0000-00-00') ? date('d/m/Y',strtotime($sale->invoice_date)) : '';

			if($sale->ewaybill_transportation_type == 'regular')
				$saleData["transType"] = 1;			
			else if($sale->ewaybill_transportation_type == 'bill_to_ship_to')
				$saleData["transType"] = 2;			
			else if($sale->ewaybill_transportation_type == 'bill_from_dispatch_from')
				$saleData["transType"] = 3;			
			else if($sale->ewaybill_transportation_type == 'combination_of_2_and_3')
				$saleData["transType"] = 4;			
			
			$saleData["fromTrdName"] = $company_setting->company_name;
			$saleData["fromGstin"] = $company_setting->gstin;
			$saleData["fromAddr1"] = $company_setting->address_line1;
			$saleData["fromAddr2"] = $company_setting->address_line2;
			$saleData["fromPlace"] = $company_setting->city_name;
			$saleData["fromPincode"] = $company_setting->pincode;
			$saleData["fromStateCode"] = $company_setting->state_code;
			$saleData["actualFromStateCode"] = $company_setting->state_code;

			$saleData["toTrdName"] = $customer->customer_name;
			$saleData["toGstin"] = $sale->customer_gstin;
			$saleData["toAddr1"] = $customer->address;
			$saleData["toAddr2"] = "";

			$saleData["toPlace"] = $customer->shipping_city_name;
			$saleData["toPincode"] = $customer->shipping_pincode;
			$saleData["toStateCode"] = $customer->shipping_state_code;
			$saleData["actualToStateCode"] = $customer->shipping_state_code;
			$saleData["totalValue"] = (float)number_format($sale->total_taxable_value, 2, '.', '');
			$saleData["cgstValue"] =  (float)number_format($sale_tax->sum_cgst, 2, '.', '');
			$saleData["sgstValue"] =  (float)number_format($sale_tax->sum_sgst, 2, '.', '');
			$saleData["igstValue"] =  (float)number_format($sale_tax->sum_igst, 2, '.', '');
			$saleData["cessValue"] = 0;
			$saleData["TotNonAdvolVal"] = 0;
			$saleData["OthValue"] = 0;
			$saleData["mainHsnCode"] = $mainHsnCode;
			$saleData["totInvValue"] = (float)number_format($sale->total, 2, '.', '');

			if($sale->ewaybill_mode_of_transportation == 'road')
				$saleData["transMode"] = 1;			
			else if($sale->ewaybill_mode_of_transportation == 'rail')
				$saleData["transMode"] = 2;			
			else if($sale->ewaybill_mode_of_transportation == 'air')
				$saleData["transMode"] = 3;			
			else if($sale->ewaybill_mode_of_transportation == 'ship')
				$saleData["transMode"] = 4;		

			$saleData["transDistance"] = $sale->ewaybill_distance_of_transportation;
			$saleData["transporterName"] = $sale->ewaybill_transporter_name;
			$saleData["transporterId"] = $sale->ewaybill_transporter_gstin;
			$saleData["transDocNo"] = $sale->ewaybill_transporter_doc_no;
			$saleData["transDocDate"] = ($sale->ewaybill_transporter_doc_date != '' && $sale->ewaybill_transporter_doc_date != '0000-00-00') ? date('d/m/Y',strtotime($sale->ewaybill_transporter_doc_date)) : '';

			$saleData["vehicleNo"] = $sale->ewaybill_vehicle_no;
			
			if($sale->ewaybill_vehicle_type == 'regular')
				$saleData["vehicleType"] = "R";			
			else
				$saleData["vehicleType"] = "O";

			$saleData["itemList"]  = $sale_items;

			$singleSale = array();
			$singleSale[] = $saleData;

			$ewaybill["version"]   = "1.0.0621";
			$ewaybill["billLists"] = $singleSale;

      $json_data = json_encode($ewaybill, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

	    // Set the download filename
	    $filename = 'Ewaybill_'.$sale->reference_no.'.json';

	    // Set the JSON content type
	    $this->output->set_content_type('application/json');

	    // Set the response headers for downloading
	    $this->output->set_header('Content-Disposition: attachment; filename="' . $filename . '"');

	    // Output the JSON data

	    $this->output->set_output($json_data);

	    // Set the success status code (200 OK)
        $this->output->set_status_header(200);

	    // Force the download
	    $this->output->get_output();
		}
		else
		{
			$sale_ids_array    = explode('-',$sale_id);

			$sales = $this->sale_model->get_sale_records_by_current_month($sale_ids_array);

			/*print_r($sale);*/

			$singleSale = array();

			foreach($sales as $sale)
			{
				$ewaybill 	= array();
				$sale_id 		= $sale->id;
				$sale 			= $this->sale_model->get_sale_single_record($sale_id);
				$customer   = $this->customer_model->get_single_record($sale->customer_id);
				$sale_items = $this->sale_model->get_sale_item_records_for_ewaybill($sale_id);
				$sale_tax   = $this->sale_model->get_tax_calculation_by_sale_id($sale_id);
				$mainHsnCode= $this->sale_model->get_mainhsncode_by_sale_id($sale_id);

				foreach ($sale_items as $key => $object) 
				{
			    $sale_items[$key]->taxableAmount = number_format($object->taxableAmount, 2, '.', '');
			    $sale_items[$key]->igstRate = number_format($object->igstRate, 2, '.', '');
			    $sale_items[$key]->cgstRate = number_format($object->cgstRate, 2, '.', '');
			    $sale_items[$key]->sgstRate = number_format($object->sgstRate, 2, '.', '');
				}

				
				$saleData 	= array();
				$saleData["userGstin"] 				= $company_setting->gstin;
				$saleData["supplyType"] 			= "O" ;


				if($sale->ewaybill_subtype == "supply")
					$saleData["subSupplyType"] = 1;
				else if($sale->ewaybill_subtype == "import")
					$saleData["subSupplyType"] = 2;
				else if($sale->ewaybill_subtype == "export")
					$saleData["subSupplyType"] = 3;
				else if($sale->ewaybill_subtype == "job_work")
					$saleData["subSupplyType"] = 4;
				else if($sale->ewaybill_subtype == "for_own_use")
					$saleData["subSupplyType"] = 5;
				else if($sale->ewaybill_subtype == "job_work_returns")
					$saleData["subSupplyType"] = 6;
				else if($sale->ewaybill_subtype == "sales_return")
					$saleData["subSupplyType"] = 7;
				else if($sale->ewaybill_subtype == "others")
					$saleData["subSupplyType"] = 8;
				else if($sale->ewaybill_subtype == "skd/ckd")
					$saleData["subSupplyType"] = 9;
				else if($sale->ewaybill_subtype == "line_sales")
					$saleData["subSupplyType"] = 10;
				else if($sale->ewaybill_subtype == "receipient_not_known")
					$saleData["subSupplyType"] = 11;
				else if($sale->ewaybill_subtype == "exhibition_or_fairs")
					$saleData["subSupplyType"] = 12;

				$saleData["subSupplyDesc"] 		= '';

				if($sale->ewaybill_doctype == "tax_invoice")
					$saleData["docType"] = "INV";
				else
					$saleData["docType"] = "BIL";

				$saleData["docNo"] = $sale->reference_no;
				$saleData["docDate"] = ($sale->invoice_date != '' && $sale->invoice_date != '0000-00-00') ? date('d/m/Y',strtotime($sale->invoice_date)) : '';

				if($sale->ewaybill_transportation_type == 'regular')
					$saleData["transType"] = 1;			
				else if($sale->ewaybill_transportation_type == 'bill_to_ship_to')
					$saleData["transType"] = 2;			
				else if($sale->ewaybill_transportation_type == 'bill_from_dispatch_from')
					$saleData["transType"] = 3;			
				else if($sale->ewaybill_transportation_type == 'combination_of_2_and_3')
					$saleData["transType"] = 4;			
				
				$saleData["fromTrdName"] 					= $company_setting->company_name;
				$saleData["fromGstin"] 						= $company_setting->gstin;
				$saleData["fromAddr1"] 						= $company_setting->address_line1;
				$saleData["fromAddr2"] 						= $company_setting->address_line2;
				$saleData["fromPlace"] 						= $company_setting->city_name;
				$saleData["fromPincode"] 					= $company_setting->pincode;
				$saleData["fromStateCode"] 				= $company_setting->state_code;
				$saleData["actualFromStateCode"] 	= $company_setting->state_code;

				$saleData["toTrdName"] = $customer->customer_name;
				$saleData["toGstin"] = $sale->customer_gstin;
				$saleData["toAddr1"] = $customer->address;
				$saleData["toAddr2"] = "";

				$saleData["toPlace"] = $customer->shipping_city_name;
				$saleData["toPincode"] = $customer->shipping_pincode;
				$saleData["toStateCode"] = $customer->shipping_state_code;
				$saleData["actualToStateCode"] = $customer->shipping_state_code;
				$saleData["totalValue"] = number_format($sale->total_taxable_value, 2, '.', '');
				$saleData["cgstValue"] = number_format($sale_tax->sum_cgst, 2, '.', '');
				$saleData["sgstValue"] = number_format($sale_tax->sum_sgst, 2, '.', '');
				$saleData["igstValue"] = number_format($sale_tax->sum_igst, 2, '.', '');
				$saleData["cessValue"] = 0;
				$saleData["TotNonAdvolVal"] = 0;
				$saleData["OthValue"] = 0;
				$saleData["mainHsnCode"] = $mainHsnCode;
				$saleData["totInvValue"] = number_format($sale->total, 2, '.', '');

				if($sale->ewaybill_mode_of_transportation == 'road')
					$saleData["transMode"] = 1;			
				else if($sale->ewaybill_mode_of_transportation == 'rail')
					$saleData["transMode"] = 2;			
				else if($sale->ewaybill_mode_of_transportation == 'air')
					$saleData["transMode"] = 3;			
				else if($sale->ewaybill_mode_of_transportation == 'ship')
					$saleData["transMode"] = 4;		

				$saleData["transDistance"] = $sale->ewaybill_distance_of_transportation;
				$saleData["transporterName"] = $sale->ewaybill_transporter_name;
				$saleData["transporterId"] = $sale->ewaybill_transporter_gstin;
				$saleData["transDocNo"] = $sale->ewaybill_transporter_doc_no;
				$saleData["transDocDate"] = ($sale->ewaybill_transporter_doc_date != '' && $sale->ewaybill_transporter_doc_date != '0000-00-00') ? date('d/m/Y',strtotime($sale->ewaybill_transporter_doc_date)) : '';

				$saleData["vehicleNo"] = $sale->ewaybill_vehicle_no;

				if($sale->ewaybill_vehicle_type == 'regular')
					$saleData["vehicleType"] = "R";			
				else
					$saleData["vehicleType"] = "O";			

				$saleData["itemList"]  = $sale_items;
				
				$singleSale[] = $saleData;
			}

			$ewaybill["version"]   = "1.0.0621";
			$ewaybill["billLists"] = $singleSale;

		
			$individualJsonFiles = array();

			$temp_dir = FCPATH.'assets/documents/ewaybill_temp';

	
			if (!is_dir($temp_dir)) {
				mkdir($temp_dir, 0777, true);
			}

			foreach ($singleSale as $sale) {
					// Generate the JSON data for each sale
					$json_data = json_encode($sale, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
			
					// Generate a unique filename for each JSON file
					$json_filename = 'Ewaybill_' . $sale['docNo'] . '.json';
			
					// Save each JSON file to the temporary directory
					$json_filepath = $temp_dir . '/' . $json_filename;
					file_put_contents($json_filepath, $json_data);
			
					// Add the JSON file to the array
					$individualJsonFiles[] = $json_filepath;
								// Print the filename for each sale
			}
			
			// Create a ZIP archive
			$zip = new ZipArchive();
			$zip_filename = 'Ewaybill_' . date('dmY') . '_' . uniqid() . '.zip';
			$zip_filepath = $temp_dir . '/' . $zip_filename;
			
			if ($zip->open($zip_filepath, ZipArchive::CREATE) === TRUE) 
			{
					// Add each individual JSON file to the ZIP archive
					foreach ($individualJsonFiles as $json_filepath) {
							$json_filename = basename($json_filepath);
							$zip->addFile($json_filepath, $json_filename);
					}
					$zip->close();
			} 
			else 
			{
				die('Could not create ZIP file');
			}
			
			// Set headers for downloading the ZIP file
			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
			header('Content-Length: ' . filesize($zip_filepath));
			
			// Output the ZIP file
			readfile($zip_filepath);
			
			// Cleanup: Delete the temporary files and directory
			foreach ($individualJsonFiles as $json_filepath) {
					unlink($json_filepath);
			}
			
			if (file_exists($zip_filepath)) {
					// Delete the ZIP file
					unlink($zip_filepath);
			}
			
			// Check if the directory is empty before removing it
			if (is_dir($temp_dir) && count(scandir($temp_dir)) == 2) {
					rmdir($temp_dir);
			}

		}

	}

  // function convert_from_proforma_invoice_list($proforma_invoice_ids)
  // {   
  //     // Decode the IDs

  //    // echo $proforma_invoice_ids;
  //     //exit;

  //     $proforma_invoice_ids = explode("-", $proforma_invoice_ids);
      
  //     foreach ($proforma_invoice_ids as $proforma_invoice_id) {
  //         $proforma_invoice_id = intval($proforma_invoice_id); // Convert to integer for safety
      
  //         $sale = $this->sale_model->get_sale_single_record_by_proforma_invoice_id($proforma_invoice_id);

  //         // print_r($sale);
  //         // exit;

  //         if($sale == null)
  //         {
  //             $proforma_invoice = $this->proforma_invoice_model->get_proforma_invoice_single_record($proforma_invoice_id);
  //             $proforma_invoice_items = $this->proforma_invoice_model->get_proforma_invoice_item_records($proforma_invoice_id);
  //             $customer = $this->customer_model->get_single_record($proforma_invoice->customer_id);
      
  //             $reference_no = $this->sale_model->get_lastest_sequence_number();


  //             // echo $reference_no;
  //             // exit;

  //             $sale_data = array(
  //                 "invoice_date"               => date('Y-m-d'),
  //                 "reference_no"               => $reference_no,
  //                 "proforma_invoice_id"        => $proforma_invoice->id,
  //                 "warehouse_id"               => $proforma_invoice->warehouse_id,
  //                 "customer_id"                => $proforma_invoice->customer_id,
  //                 "customer_gstin"             => $customer->gstin,
  //                 "customer_shipping_country_id" => $customer->country_id,
  //                 "customer_shipping_state_id" => $customer->state_id,
  //                 "customer_shipping_city_id"  => $customer->city_id,
  //                 "customer_shipping_address"  => $customer->address,
  //                 "customer_shipping_pincode"  => $customer->pincode,
  //                 "rcm"                        => $proforma_invoice->rcm,
  //                 "total_taxable_value"        => $proforma_invoice->total_taxable_value,    
  //                 "tds"                        => 0,    
  //                 "total_tax"                  => $proforma_invoice->total_tax,
  //                 "total_discount"             => $proforma_invoice->total_discount,    
  //                 "total"                      => $proforma_invoice->total,
  //                 "internal_note"              => $proforma_invoice->internal_note,
  //                 "external_note"              => $proforma_invoice->external_note,
  //                 "bank_detail"                => $proforma_invoice->bank_detail,
  //                 "terms_and_condition"        => $proforma_invoice->terms_and_condition,
  //                 "user_id"                    => $this->session->userdata('user_id')
  //             );

  //             // Begin transaction
  //             $this->db->trans_begin();

  //             if($id = $this->sale_model->add_sale_record($sale_data))
  //             {
  //                 $entered_sale = $this->sale_model->get_sale_single_record($id);
  //                 $customer = $this->customer_model->get_single_record($entered_sale->customer_id);

  //                 // Update the ledgers
  //                 $sale_ledger = $this->ledger_model->get_single_record(SALE_LEDGER);
  //                 $updated_sale_ledger = array('closing_balance' => ($sale_ledger->closing_balance + $sale_data['total']));
  //                 $this->ledger_model->edit_record($updated_sale_ledger, $sale_ledger->id);

  //                 $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
  //                 $updated_customer_ledger = array('closing_balance' => ($customer_ledger->closing_balance + $sale_data['total']));
  //                 $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);

  //                 if($sale_data['tds'] > 0)
  //                 {
  //                     $tds_ledger = $this->ledger_model->get_single_record(TDS_LEDGER);
  //                     $updated_tds_ledger = array('closing_balance' => ($tds_ledger->closing_balance + $sale_data['tds']));
  //                     $this->ledger_model->edit_record($updated_tds_ledger, $tds_ledger->id);

  //                     $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
  //                     $updated_customer_ledger = array('closing_balance' => ($customer_ledger->closing_balance - $sale_data['tds']));
  //                     $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);
  //                 }

  //                 // Add transaction entry
  //                 $transaction_header = array(
  //                     "entry_id"          => $id,
  //                     "module"            => SALE_MODULE,
  //                     "type"              => SALE_TRANSACTION_TYPE,
  //                     "amount"            => $sale_data['total'],
  //                     "voucher_date"      => $sale_data['invoice_date'],
  //                     "from_account"      => SALE_LEDGER,
  //                     "to_account"        => $customer->ledger_id,
  //                     "reference_no"      => $sale_data['reference_no']
  //                 );
  //                 if($transaction_id = $this->transaction_model->add_record($transaction_header))
  //                 {
  //                     $from_transaction_detail = array(
  //                         "transaction_id"    => $transaction_id,
  //                         "voucher_type"      => 'C',
  //                         "ledger_id"         => SALE_LEDGER,
  //                         "dr_amount"         => $sale_data['total']
  //                     );
  //                     $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

  //                     $to_transaction_detail = array(
  //                         "transaction_id"    => $transaction_id,
  //                         "voucher_type"      => 'D',
  //                         "ledger_id"         => $customer->ledger_id,
  //                         "cr_amount"         => $sale_data['total']
  //                     );
  //                     $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
  //                 }

  //                 if($sale_data['tds'] > 0)
  //                 {
  //                     $transaction_header = array(
  //                         "entry_id"          => $id,
  //                         "module"            => SALE_MODULE,
  //                         "type"              => TDS_TRANSACTION_TYPE,
  //                         "amount"            => $sale_data['tds'],
  //                         "voucher_date"      => $sale_data['invoice_date'],
  //                         "from_account"      => $customer->ledger_id,
  //                         "to_account"        => TDS_LEDGER,
  //                         "reference_no"      => $sale_data['reference_no']
  //                     );
  //                     if($transaction_id = $this->transaction_model->add_record($transaction_header))
  //                     {
  //                         $from_transaction_detail = array(
  //                             "transaction_id"    => $transaction_id,
  //                             "voucher_type"      => 'C',
  //                             "ledger_id"         => $customer->ledger_id,
  //                             "dr_amount"         => $sale_data['tds']
  //                         );
  //                         $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

  //                         $to_transaction_detail = array(
  //                             "transaction_id"    => $transaction_id,
  //                             "voucher_type"      => 'D',
  //                             "ledger_id"         => TDS_LEDGER,
  //                             "cr_amount"         => $sale_data['tds']
  //                         );
  //                         $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
  //                     }   
  //                 }

  //                 $log_data = array(
  //                     "user_id"       => $this->session->userdata("user_id"),
  //                     "module"        => "sale",
  //                     "entry_id"      => $id,
  //                     "user_action"   => 1,
  //                     "data"          => json_encode((array)$entered_sale),
  //                     "description"   => 'Sales (Reference No. -'  .$sale_data['reference_no'] .') is added successfully.'
  //                 );
  //                 $this->log_data_model->add_record($log_data);
                  
  //                 foreach ($proforma_invoice_items as $value) {
                      
  //                     $product = $this->product_model->get_single_record($value->product_id);
  //                     $uom = $this->uom_model->get_single_record($product->uom_id);

  //                     $temp_sale_item = array(
  //                         'product_id'           => $value->product_id,
  //                         'warehouse_product_id' => $value->warehouse_product_id,
  //                         'product_name'         => $value->product_name,
  //                         'description'          => $value->description,
  //                         'quantity'             => $value->quantity,
  //                         'price'                => $value->price,
  //                         'cost'                 => $value->cost,
  //                         'selling_price'        => $value->selling_price,
  //                         'taxable_value'        => $value->taxable_value,
  //                         'discount_id'          => $value->discount_id,
  //                         'discount_type'        => $value->discount_type,
  //                         'discount_value'       => $value->discount_value,
  //                         'discount_amount'      => $value->discount_amount,
  //                         'uom_id'               => $uom->id,
  //                         'uom_name'             => $uom->name,
  //                         'uom_uom'              => $uom->uom,
  //                         'tax_id'               => $value->tax_id,
  //                         'tax_type'             => $value->tax_type,
  //                         'igst'                 => $value->igst,
  //                         'igst_tax'             => $value->igst_tax,
  //                         'cgst'                 => $value->cgst,
  //                         'cgst_tax'             => $value->cgst_tax,
  //                         'sgst'                 => $value->sgst,
  //                         'sgst_tax'             => $value->sgst_tax,
  //                         'sub_total'            => $value->sub_total,
  //                         'sale_id'              => $id
  //                     );

  //                     $this->sale_model->add_sale_item_record($temp_sale_item);
  //                 }

  //                 $this->proforma_invoice_model->edit_proforma_invoice_record(array('sale_id'=>$id),$proforma_invoice->id);

  //                 // Commit transaction
  //                 $this->db->trans_commit();

  //                 $this->session->set_flashdata('success',  'Sales (Reference No. -'  .$sale_data['reference_no'] .') is added successfully.');
  //             }
  //             else
  //             {
  //                 // Rollback transaction
  //                 $this->db->trans_rollback();
                  
  //                 $this->session->set_flashdata('failure',  'Sales (Reference No. -'  .$sale_data['reference_no'] .') is failed to add.');
  //             }   
  //         }
  //         else
  //         {
  //             $this->session->set_flashdata('success',  'Sale (Reference No. -'  .$sale->reference_no.') already exists');
  //         }
  //     }
  //     // Redirect to the sales view page (or another page as needed)
  //     redirect('proforma_invoice','refresh');
  // }

  public function convert_from_proforma_invoice_list($proforma_invoice_ids = null)
  {
      if ($this->input->server('REQUEST_METHOD') === 'POST') 
      {
          $response = array('code' => 0, 'message' => 'Failed to process request.');
  
          $proforma_invoice_ids = $this->input->post('proforma_invoice_ids');
          $proforma_invoice_ids = explode("-", $proforma_invoice_ids);
//   var_dump('Proforma Invoice IDs POST:', $proforma_invoice_ids);
//         die();console.log($proforma_invoice_ids);
          foreach ($proforma_invoice_ids as $proforma_invoice_id) {
              $proforma_invoice_id = intval($proforma_invoice_id);
  
              $sale = $this->sale_model->get_sale_single_record_by_proforma_invoice_id($proforma_invoice_id);
  
              if ($sale == null) {
                  $proforma_invoice = $this->proforma_invoice_model->get_proforma_invoice_single_record($proforma_invoice_id);
                  $proforma_invoice_items = $this->proforma_invoice_model->get_proforma_invoice_item_records($proforma_invoice_id);
                  $customer = $this->customer_model->get_single_record($proforma_invoice->customer_id);
  
                  $reference_no = $this->sale_model->get_lastest_sequence_number($proforma_invoice->warehouse_id);
                      require_once FCPATH . 'application/libraries/phpqrcode/qrlib.php'; 
         
        $last_sale_id = $this->db->select_max('id')->get('sale')->row()->id;
        
        $new_sale_id = $last_sale_id + 1;
        
        $encoded_id = base64_encode($new_sale_id);
        
        $qr_data = base_url('Qr_sale/view_qr_sale/' . $encoded_id);
        
        $qr_filename = uniqid('qr_') . '.png';
        $qr_path = FCPATH . 'uploads/qr_codes/' . $qr_filename;
        
        if (!is_dir(FCPATH . 'uploads/qr_codes')) {
            if (!mkdir(FCPATH . 'uploads/qr_codes', 0777, true)) {
                log_message('error', 'Failed to create uploads/qr_codes directory');
                exit;
            }
        }
        
        QRcode::png($qr_data, $qr_path, QR_ECLEVEL_L, 10, 2);
        
        if (file_exists($qr_path) && exif_imagetype($qr_path) === IMAGETYPE_PNG) {
            log_message('info', "QR Code successfully generated and saved: $qr_path");
            $sale_data['qr'] = $qr_filename;
        } else {
            log_message('error', 'QR Code generation failed or is not a valid image.');
        }

                  $sale_data = array(
                      "invoice_date"                 => date('Y-m-d'),
                      "reference_no"                 => $reference_no,
                      "proforma_invoice_id"          => $proforma_invoice->id,
                      "warehouse_id"                 => $proforma_invoice->warehouse_id,
                      "customer_id"                  => $proforma_invoice->customer_id,
                      "customer_gstin"               => $customer->gstin,
                      "customer_shipping_country_id" => $customer->country_id,
                      "customer_shipping_state_id"   => $customer->state_id,
                      "customer_shipping_city_id"    => $customer->city_id,
                      "customer_shipping_address"    => $customer->address,
                      "customer_shipping_pincode"     => $customer->pincode,
                      "rcm" => $proforma_invoice->rcm,
                      "total_taxable_value" => $proforma_invoice->total_taxable_value,
                      "tds" => 0,
                      "total_tax" => $proforma_invoice->total_tax,
                      "total_discount" => $proforma_invoice->total_discount,
                      "total" => $proforma_invoice->total,
                      "internal_note" => $proforma_invoice->internal_note,
                      "external_note" => $proforma_invoice->external_note,
                      "bank_detail" => $proforma_invoice->bank_detail,
                      "terms_and_condition" => $proforma_invoice->terms_and_condition,
                      "user_id" => $this->session->userdata('user_id'),
                        "qr"                    => $sale_data['qr'],
                  );

                  // Begin transaction
                  $this->db->trans_begin();
  
                  if ($id = $this->sale_model->add_sale_record($sale_data)) {
                      $entered_sale = $this->sale_model->get_sale_single_record($id);
                      $customer = $this->customer_model->get_single_record($entered_sale->customer_id);
  
                      // Update the ledgers
                      $sale_ledger = $this->ledger_model->get_single_record(SALE_LEDGER);
                      $updated_sale_ledger = array('closing_balance' => ($sale_ledger->closing_balance + $sale_data['total']));
                      $this->ledger_model->edit_record($updated_sale_ledger, $sale_ledger->id);
  
                      $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
                      $updated_customer_ledger = array('closing_balance' => ($customer_ledger->closing_balance + $sale_data['total']));
                      $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);
  
                      if ($sale_data['tds'] > 0) {
                          $tds_ledger = $this->ledger_model->get_single_record(TDS_LEDGER);
                          $updated_tds_ledger = array('closing_balance' => ($tds_ledger->closing_balance + $sale_data['tds']));
                          $this->ledger_model->edit_record($updated_tds_ledger, $tds_ledger->id);
  
                          $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
                          $updated_customer_ledger = array('closing_balance' => ($customer_ledger->closing_balance - $sale_data['tds']));
                          $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);
                      }
  
                      // Add transaction entry
                      $transaction_header = array(
                          "entry_id" => $id,
                          "module" => SALE_MODULE,
                          "type" => SALE_TRANSACTION_TYPE,
                          "amount" => $sale_data['total'],
                          "voucher_date" => $sale_data['invoice_date'],
                          "from_account"              => SALE_LEDGER,
                          "to_account"                => $customer->ledger_id,
                          "reference_no"              => $sale_data['reference_no'],
                          "warehouse_id"              => $proforma_invoice->warehouse_id,
                      );
  
                      if ($transaction_id = $this->transaction_model->add_record($transaction_header)) {
                          $from_transaction_detail = array(
                              "transaction_id" => $transaction_id,
                              "voucher_type" => 'C',
                              "ledger_id" => SALE_LEDGER,
                              "dr_amount" => $sale_data['total']
                          );
                          $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
  
                          $to_transaction_detail = array(
                              "transaction_id" => $transaction_id,
                              "voucher_type" => 'D',
                              "ledger_id" => $customer->ledger_id,
                              "cr_amount" => $sale_data['total']
                          );
                          $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                      }
  
                      if ($sale_data['tds'] > 0) {
                          $transaction_header = array(
                              "entry_id" => $id,
                              "module" => SALE_MODULE,
                              "type" => TDS_TRANSACTION_TYPE,
                              "amount" => $sale_data['tds'],
                              "voucher_date" => $sale_data['invoice_date'],
                              "from_account" => $customer->ledger_id,
                              "to_account" => TDS_LEDGER,
                              "reference_no" => $sale_data['reference_no'],
                               "warehouse_id"              => $proforma_invoice->warehouse_id,
                          );
  
                          if ($transaction_id = $this->transaction_model->add_record($transaction_header)) {
                              $from_transaction_detail = array(
                                  "transaction_id" => $transaction_id,
                                  "voucher_type" => 'C',
                                  "ledger_id" => $customer->ledger_id,
                                  "dr_amount" => $sale_data['tds']
                              );
  
                              $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
  
                              $to_transaction_detail = array(
                                  "transaction_id" => $transaction_id,
                                  "voucher_type" => 'D',
                                  "ledger_id" => TDS_LEDGER,
                                  "cr_amount" => $sale_data['tds']
                              );
  
                              $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                          }
                      }
  
                      $log_data = array(
                          "user_id" => $this->session->userdata("user_id"),
                          "module" => "sale",
                          "entry_id" => $id,
                          "user_action" => 1,
                          "data" => json_encode((array)$entered_sale),
                          "description" => 'Sales (Reference No. -' . $sale_data['reference_no'] . ') is added successfully.'
                      );
  
                      $this->log_data_model->add_record($log_data);
  
                      foreach ($proforma_invoice_items as $value) {
                          $product = $this->product_model->get_single_record($value->product_id);
                          $uom = $this->uom_model->get_single_record($product->uom_id);
  
                          $temp_sale_item = array(
                              'product_id' => $value->product_id,
                              'warehouse_product_id' => $value->warehouse_product_id,
                              'product_name' => $value->product_name,
                              'description' => $value->description,
                              'quantity' => $value->quantity,
                              'price' => $value->selling_price,
                              'cost' => $value->cost,
                              'selling_price' => $value->price,
                              'taxable_value' => $value->taxable_value,
                              'discount_id' => $value->discount_id,
                              'discount_type' => $value->discount_type,
                              'discount_value' => $value->discount_value,
                              'discount_amount' => $value->discount_amount,
                              'uom_id' => $uom->id,
                              'uom_name' => $uom->name,
                              'uom_uom' => $uom->uom,
                              'tax_id' => $value->tax_id,
                              'tax_type' => $value->tax_type,
                              'igst' => $value->igst,
                              'igst_tax' => $value->igst_tax,
                              'cgst' => $value->cgst,
                              'cgst_tax' => $value->cgst_tax,
                              'sgst' => $value->sgst,
                              'sgst_tax' => $value->sgst_tax,
                              'sub_total' => $value->sub_total,
                              'sale_id' => $id,
                              'proforma_invoice_idd' => $value->id,
                          );
  
                          $this->sale_model->add_sale_item_record($temp_sale_item);
                      }
  
                      $this->proforma_invoice_model->edit_proforma_invoice_record(array('sale_id' => $id), $proforma_invoice->id);
  
                      // Commit transaction
                      $this->db->trans_commit();
  
                      $response = array('code' => 1, 'message' => 'Sales (Reference No. -' . $sale_data['reference_no'] . ') is added successfully.');
                  } else {
                      // Rollback transaction
                      $this->db->trans_rollback();
  
                      $response = array('code' => 0, 'message' => 'Sales (Reference No. -' . $sale_data['reference_no'] . ') failed to add.');
                  }
              } else {
                  $response = array('code' => 1, 'message' => 'Sale (Reference No. -' . $sale->reference_no . ') already exists.');
              }
          }
  
          echo json_encode($response);
      } 
      else 
      {
          $response = array();
          $response['generate_proforma_invoice_modal_body'] = $this->load->view('sale/ajax/generate_proforma_invoice_modal_view', NULL, TRUE);
  
          echo json_encode($response);
      }
  }

function convert_from_proforma_invoice($proforma_invoice_id) {   
    $proforma_invoice_id = base64_decode($proforma_invoice_id);
    $sale = $this->sale_model->get_sale_single_record_by_proforma_invoice_id($proforma_invoice_id);

    if($sale == null) {
        $proforma_invoice = $this->proforma_invoice_model->get_proforma_invoice_single_record($proforma_invoice_id);
        $proforma_invoice_items = $this->proforma_invoice_model->get_proforma_invoice_item_records($proforma_invoice_id);
        $customer = $this->customer_model->get_single_record($proforma_invoice->customer_id);
        
        // Generate reference number with warehouse code
            $this->load->helper('common');
            $reference_no = get_next_sale_main_reference();      

        // QR code generation
        require_once FCPATH . 'application/libraries/phpqrcode/qrlib.php';
        $last_sale_id = $this->db->select_max('id')->get('sale')->row()->id;
        $new_sale_id = $last_sale_id + 1;
        $encoded_id = base64_encode($new_sale_id);
        $qr_data = base_url('Qr_sale/view_qr_sale/' . $encoded_id);
        $qr_filename = uniqid('qr_') . '.png';
        $qr_path = FCPATH . 'uploads/qr_codes/' . $qr_filename;
        QRcode::png($qr_data, $qr_path);

        $sale_data = array(
            "invoice_date" => date('Y-m-d'),
            "reference_no" => $reference_no,
            "proforma_invoice_id" => $proforma_invoice->id,
            "warehouse_id" => $proforma_invoice->warehouse_id,
            "customer_id" => $proforma_invoice->customer_id,
            "customer_gstin" => $customer->gstin,
            "customer_shipping_country_id" => $customer->country_id,
            "customer_shipping_state_id" => $customer->state_id,
            "customer_shipping_city_id" => $customer->city_id,
            "customer_shipping_address" => $customer->address,
            "customer_shipping_pincode" => $customer->pincode,
            "selected_shipping_address_id" => $proforma_invoice->selected_shipping_address_id,
            "rcm" => $proforma_invoice->rcm,
            "total_taxable_value" => $proforma_invoice->total_taxable_value,    
            "tds" => 0,    
            "total_tax" => $proforma_invoice->total_tax,
            "total_discount" => $proforma_invoice->total_discount,    
            "total" => $proforma_invoice->total,
            "internal_note" => $proforma_invoice->internal_note,
            "external_note" => $proforma_invoice->external_note,
            "bank_detail" => $proforma_invoice->bank_detail,
            "terms_and_condition" => $proforma_invoice->terms_and_condition,
            "user_id" => $this->session->userdata('user_id'),
            "qr" => $qr_filename,
            "additional_cost_type"  =>$proforma_invoice->additional_cost_type,
            "additional_cost_amount"  =>$proforma_invoice->additional_cost_amount,
            "total_purchase_cost" => $proforma_invoice->total_purchase_cost,
            "total_selling_price" => $proforma_invoice->total_selling_price,
            "gross_profit_loss" => $proforma_invoice->gross_profit_loss,
            "profit_margin" => $proforma_invoice->profit_margin,
            
                    'freight_selling_price' => $proforma_invoice->freight_selling_price,
                    'freight_taxable_value' => $proforma_invoice->freight_taxable_value,
                    'freight_sub_total' => $proforma_invoice->freight_sub_total,
                    'freight_tax_rate' => $proforma_invoice->freight_tax_rate,
                    'freight_tax_amount' => $proforma_invoice->freight_tax_amount   
        );

        // Begin transaction
        $this->db->trans_begin();

        if($id = $this->sale_model->add_sale_record($sale_data)) {
            $entered_sale = $this->sale_model->get_sale_single_record($id);
            
	$customer 			= $this->customer_model->get_single_record($entered_sale->customer_id);

				// update the ledgers
				
				/****************************** Start Sale Ledger ***********************************/

				$sale_ledger 								= $this->ledger_model->get_single_record(SALE_LEDGER);
				$updated_sale_ledger 				= array('closing_balance' => ($sale_ledger->closing_balance + $sale_data['total']));
				$this->ledger_model->edit_record($updated_sale_ledger,$sale_ledger->id);

				/****************************** End Sale Ledger ***********************************/

				/****************************** Start Customer Ledger ***********************************/

				$customer_ledger 						= $this->ledger_model->get_single_record($customer->ledger_id);
				$updated_customer_ledger    = array('closing_balance' => ($customer_ledger->closing_balance + $sale_data['total']));
				$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

				/****************************** End Customer Ledger ***********************************/

				/****************************** Start TDS Ledger ***********************************/

				if($sale_data['tds'] > 0)
				{
					$tds_ledger 						= $this->ledger_model->get_single_record(TDS_LEDGER);
					$updated_tds_ledger    	= array('closing_balance' => ($tds_ledger->closing_balance + $sale_data['tds']));
					$this->ledger_model->edit_record($updated_tds_ledger,$tds_ledger->id);

					$customer_ledger 						= $this->ledger_model->get_single_record($customer->ledger_id);
					$updated_customer_ledger 		= array('closing_balance' => ($customer_ledger->closing_balance - $sale_data['tds']));
					$this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);
				}

				
				/****************************** End TDS Ledger ***********************************/

				/****************************** Add Transaction entry *********************************/

				$transaction_header = array(
																		"entry_id"				=>  $id,
																		"module"					=>  SALE_MODULE,
																		"type"						=>	SALE_TRANSACTION_TYPE,
																		"amount"					=>	$sale_data['total'],
																		"voucher_date"		=>	$sale_data['invoice_date'],
																		"from_account"		=>	SALE_LEDGER,
																		"to_account"			=>	$customer->ledger_id,
																		"reference_no"		=>	$sale_data['reference_no'],
																		 "warehouse_id"              => $proforma_invoice->warehouse_id,
																	);
				if($transaction_id = $this->transaction_model->add_record($transaction_header))
				{
					$from_transaction_detail = array(
																						"transaction_id" 	=> $transaction_id,
																						"voucher_type"		=> 'C',
																						"ledger_id"				=> SALE_LEDGER,
																						"dr_amount"				=> $sale_data['total']
																					);
					// transaction detail recordpro
					$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

					$to_transaction_detail = array(
																					"transaction_id" 	=> $transaction_id,
																					"voucher_type"		=> 'D',
																					"ledger_id"				=> $customer->ledger_id,
																					"cr_amount"				=> $sale_data['total']
																				);
					// transaction detail record
					$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
				}

				/**************************************************************************************/


				/****************************** Add TDS Transaction entry *********************************/

				if($sale_data['tds'] > 0)
				{
					$transaction_header = array(
																			"entry_id"				=>  $id,
																			"module"					=>  SALE_MODULE,
																			"type"						=>	TDS_TRANSACTION_TYPE,
																			"amount"					=>	$sale_data['tds'],
																			"voucher_date"		=>	$sale_data['invoice_date'],
																			"from_account"		=>	$customer->ledger_id,
																			"to_account"			=>	TDS_LEDGER,
																			"reference_no"		=>	$sale_data['reference_no'],
																			 "warehouse_id"              => $proforma_invoice->warehouse_id,
																		);
					if($transaction_id = $this->transaction_model->add_record($transaction_header))
					{
						$from_transaction_detail = array(
																							"transaction_id" 	=> $transaction_id,
																							"voucher_type"		=> 'C',
																							"ledger_id"				=> $customer->ledger_id,
																							"dr_amount"				=> $sale_data['tds']
																						);
						// transaction detail record
						$this->transaction_model->add_transaction_detail_record($from_transaction_detail);

						$to_transaction_detail = array(
																						"transaction_id" 	=> $transaction_id,
																						"voucher_type"		=> 'D',
																						"ledger_id"				=> TDS_LEDGER,
																						"cr_amount"				=> $sale_data['tds']
																					);
						// transaction detail record
						$this->transaction_model->add_transaction_detail_record($to_transaction_detail);
					}	
				}
				
				/**************************************************************************************/

				$log_data = array(
									"user_id" 		=> $this->session->userdata("user_id"),
									"module"			=> "sale",
									"entry_id"		=> $id,
									"user_action"	=> 1,
									"data"				=> json_encode((array)$entered_sale),
									"description" => 'Sales (Reference No. -'  .$sale_data['reference_no'] .') is added successfully.'
								);
				$this->log_data_model->add_record($log_data);

            foreach ($proforma_invoice_items as $value) {
                $product = $this->product_model->get_single_record($value->product_id);
                $uom = $this->uom_model->get_single_record($product->uom_id);

                $temp_sale_item = array(
                    'product_id' => $value->product_id,
                    'warehouse_product_id' => $value->warehouse_product_id,
                    'product_name' => $value->product_name,
                    'product_remark' => $value->product_remark,
                    'description' => $value->description,
                    'quantity' => $value->quantity,
                    'price' => $value->price,
                    'cost' => $value->cost,
                    'selling_price' => $value->selling_price,
                    'taxable_value' => $value->taxable_value,
                    'discount_id' => $value->discount_id,
                    'discount_type' => $value->discount_type,
                    'discount_value' => $value->discount_value,
                    'discount_amount' => $value->discount_amount,
                    'uom_id' => $uom->id,
                    'uom_name' => $uom->name,
                    'uom_uom' => $uom->uom,
                    'tax_id' => $value->tax_id,
                    'tax_type' => $value->tax_type,
                    'igst' => $value->igst,
                    'igst_tax' => $value->igst_tax,
                    'cgst' => $value->cgst,
                    'cgst_tax' => $value->cgst_tax,
                    'sgst'     => $value->sgst,
                    'sgst_tax' => $value->sgst_tax,
                    'igst_rate'=> $value->igst_rate,
                    'cgst_rate'=> $value->cgst_rate,
                    'sgst_rate'     => $value->sgst_rate,
                    'sub_total' => $value->sub_total,
                    'sale_id' => $id,
                    'purchase_cost' => $value->purchase_cost,
                    'supplier_id' => $value->supplier_id,
                    'batch_no' => $value->batch_no,
                    'mfg_date' => $value->mfg_date,
                    'expiry_date' => $value->expiry_date
                );

                $this->sale_model->add_sale_item_record($temp_sale_item);
            }

            $this->proforma_invoice_model->edit_proforma_invoice_record(array('sale_id'=>$id),$proforma_invoice->id);

            // Commit transaction
            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Sales (Reference No. -' .$reference_no .') is added successfully.');
            redirect('sale/view/'.base64_encode($id),'refresh');
        } else {
            // Rollback transaction
            $this->db->trans_rollback();
            $this->session->set_flashdata('failure', 'Sales (Reference No. -' .$reference_no .') failed to add.');
            redirect('sale/view/'.base64_encode($id),'refresh');
        }    
    } else {
        $this->session->set_flashdata('success', 'Sale (Reference No. -' .$sale->reference_no.') already exists');
        redirect('sale/view/'.base64_encode($sale->id),'refresh');
    }
}

function convert_from_delivery_challan($delivery_challan_id) {   
    $delivery_challan_id = base64_decode($delivery_challan_id);
    $sale = $this->sale_model->get_sale_single_record_by_delivery_challan_id($delivery_challan_id);

    if($sale == null) {
        $delivery_challan = $this->delivery_challan_model->get_delivery_challan_single_record($delivery_challan_id);
        $delivery_challan_items = $this->delivery_challan_model->get_delivery_challan_item_records($delivery_challan_id);
        $customer = $this->customer_model->get_single_record($delivery_challan->customer_id);
        
        // Generate reference number with warehouse code
                    $this->load->helper('common');
            $reference_no = get_next_sale_main_reference();

        // QR code generation
        require_once FCPATH . 'application/libraries/phpqrcode/qrlib.php';
        $last_sale_id = $this->db->select_max('id')->get('sale')->row()->id;
        $new_sale_id = $last_sale_id + 1;
        $encoded_id = base64_encode($new_sale_id);
        $qr_data = base_url('Qr_sale/view_qr_sale/' . $encoded_id);
        $qr_filename = uniqid('qr_') . '.png';
        $qr_path = FCPATH . 'uploads/qr_codes/' . $qr_filename;
        QRcode::png($qr_data, $qr_path);

        $sale_data = array(
            "invoice_date" => date('Y-m-d'),
            "reference_no" => $reference_no,
            "delivery_challan_id" => $delivery_challan->id,
            "warehouse_id" => $delivery_challan->warehouse_id,
            "customer_id" => $delivery_challan->customer_id,
            "customer_gstin" => $customer->gstin,
            "customer_shipping_country_id" => $customer->country_id,
            "customer_shipping_state_id" => $customer->state_id,
            "customer_shipping_city_id" => $customer->city_id,
            "customer_shipping_address" => $customer->address,
            "customer_shipping_pincode" => $customer->pincode,
            "selected_shipping_address_id" => $delivery_challan->selected_shipping_address_id,
            "rcm" => $delivery_challan->rcm,
            "total_taxable_value" => $delivery_challan->total_taxable_value,    
            "tds" => 0,    
            "total_tax" => $delivery_challan->total_tax,
            "total_discount" => $delivery_challan->total_discount,    
            "total" => $delivery_challan->total,
            "internal_note" => $delivery_challan->internal_note,
            "external_note" => $delivery_challan->external_note,
            "bank_detail" => $delivery_challan->bank_detail,
            "terms_and_condition" => $delivery_challan->terms_and_condition,
            "user_id" => $this->session->userdata('user_id'),
            "qr" => $qr_filename,
            "additional_cost_type"  => $delivery_challan->additional_cost_type,
            "additional_cost_amount"  => $delivery_challan->additional_cost_amount,
            "total_purchase_cost" => $delivery_challan->total_purchase_cost,
            "total_selling_price" => $delivery_challan->total_selling_price,
            "gross_profit_loss" => $delivery_challan->gross_profit_loss,
            "profit_margin" => $delivery_challan->profit_margin,
            
            'freight_selling_price' => $delivery_challan->freight_selling_price,
            'freight_taxable_value' => $delivery_challan->freight_taxable_value,
            'freight_sub_total' => $delivery_challan->freight_sub_total,
            'freight_tax_rate' => $delivery_challan->freight_tax_rate,
            'freight_tax_amount' => $delivery_challan->freight_tax_amount   
        );

        // Begin transaction
        $this->db->trans_begin();

        if($id = $this->sale_model->add_sale_record($sale_data)) {
            $entered_sale = $this->sale_model->get_sale_single_record($id);
            
            $customer = $this->customer_model->get_single_record($entered_sale->customer_id);

            // update the ledgers
            
            /****************************** Start Sale Ledger ***********************************/

            $sale_ledger = $this->ledger_model->get_single_record(SALE_LEDGER);
            $updated_sale_ledger = array('closing_balance' => ($sale_ledger->closing_balance + $sale_data['total']));
            $this->ledger_model->edit_record($updated_sale_ledger,$sale_ledger->id);

            /****************************** End Sale Ledger ***********************************/

            /****************************** Start Customer Ledger ***********************************/

            $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
            $updated_customer_ledger = array('closing_balance' => ($customer_ledger->closing_balance + $sale_data['total']));
            $this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);

            /****************************** End Customer Ledger ***********************************/

            /****************************** Start TDS Ledger ***********************************/

            if($sale_data['tds'] > 0)
            {
                $tds_ledger = $this->ledger_model->get_single_record(TDS_LEDGER);
                $updated_tds_ledger = array('closing_balance' => ($tds_ledger->closing_balance + $sale_data['tds']));
                $this->ledger_model->edit_record($updated_tds_ledger,$tds_ledger->id);

                $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
                $updated_customer_ledger = array('closing_balance' => ($customer_ledger->closing_balance - $sale_data['tds']));
                $this->ledger_model->edit_record($updated_customer_ledger,$customer->ledger_id);
            }

            
            /****************************** End TDS Ledger ***********************************/

            /****************************** Add Transaction entry *********************************/

            $transaction_header = array(
                "entry_id" => $id,
                "module" => SALE_MODULE,
                "type" => SALE_TRANSACTION_TYPE,
                "amount" => $sale_data['total'],
                "voucher_date" => $sale_data['invoice_date'],
                "from_account" => SALE_LEDGER,
                "to_account" => $customer->ledger_id,
                "reference_no" => $sale_data['reference_no'],
                "warehouse_id" => $delivery_challan->warehouse_id,
            );
            if($transaction_id = $this->transaction_model->add_record($transaction_header))
            {
                $from_transaction_detail = array(
                    "transaction_id" => $transaction_id,
                    "voucher_type" => 'C',
                    "ledger_id" => SALE_LEDGER,
                    "dr_amount" => $sale_data['total']
                );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                $to_transaction_detail = array(
                    "transaction_id" => $transaction_id,
                    "voucher_type" => 'D',
                    "ledger_id" => $customer->ledger_id,
                    "cr_amount" => $sale_data['total']
                );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
            }

            /**************************************************************************************/


            /****************************** Add TDS Transaction entry *********************************/

            if($sale_data['tds'] > 0)
            {
                $transaction_header = array(
                    "entry_id" => $id,
                    "module" => SALE_MODULE,
                    "type" => TDS_TRANSACTION_TYPE,
                    "amount" => $sale_data['tds'],
                    "voucher_date" => $sale_data['invoice_date'],
                    "from_account" => $customer->ledger_id,
                    "to_account" => TDS_LEDGER,
                    "reference_no" => $sale_data['reference_no'],
                    "warehouse_id" => $delivery_challan->warehouse_id,
                );
                if($transaction_id = $this->transaction_model->add_record($transaction_header))
                {
                    $from_transaction_detail = array(
                        "transaction_id" => $transaction_id,
                        "voucher_type" => 'C',
                        "ledger_id" => $customer->ledger_id,
                        "dr_amount" => $sale_data['tds']
                    );
                    // transaction detail record
                    $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                    $to_transaction_detail = array(
                        "transaction_id" => $transaction_id,
                        "voucher_type" => 'D',
                        "ledger_id" => TDS_LEDGER,
                        "cr_amount" => $sale_data['tds']
                    );
                    // transaction detail record
                    $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                }   
            }
            
            /**************************************************************************************/

            $log_data = array(
                "user_id" => $this->session->userdata("user_id"),
                "module" => "sale",
                "entry_id" => $id,
                "user_action" => 1,
                "data" => json_encode((array)$entered_sale),
                "description" => 'Sales (Reference No. -'  .$sale_data['reference_no'] .') is added successfully.'
            );
            $this->log_data_model->add_record($log_data);

            foreach ($delivery_challan_items as $value) {
                $product = $this->product_model->get_single_record($value->product_id);
                $uom = $this->uom_model->get_single_record($product->uom_id);

                $temp_sale_item = array(
                    'product_id' => $value->product_id,
                    'warehouse_product_id' => $value->warehouse_product_id,
                    'product_name' => $value->product_name,
                    'product_remark' => $value->product_remark,
                    'description' => $value->description,
                    'quantity' => $value->quantity,
                    'price' => $value->price,
                    'cost' => $value->cost,
                    'selling_price' => $value->selling_price,
                    'taxable_value' => $value->taxable_value,
                    'discount_id' => $value->discount_id,
                    'discount_type' => $value->discount_type,
                    'discount_value' => $value->discount_value,
                    'discount_amount' => $value->discount_amount,
                    'uom_id' => $uom->id,
                    'uom_name' => $uom->name,
                    'uom_uom' => $uom->uom,
                    'tax_id' => $value->tax_id,
                    'tax_type' => $value->tax_type,
                    'igst' => $value->igst,
                    'igst_tax' => $value->igst_tax,
                    'cgst' => $value->cgst,
                    'cgst_tax' => $value->cgst_tax,
                    'sgst' => $value->sgst,
                    'sgst_tax' => $value->sgst_tax,
                    'igst_rate'=> $value->igst_rate,
                    'cgst_rate'=> $value->cgst_rate,
                    'sgst'     => $value->sgst_rate,
                    'sub_total' => $value->sub_total,
                    'sale_id' => $id,
                    'purchase_cost' => $value->purchase_cost,
                    'supplier_id' => $value->supplier_id,
                    'batch_no' => $value->batch_no,
                    'mfg_date' => $value->mfg_date,
                    'expiry_date' => $value->expiry_date
                );

                $this->sale_model->add_sale_item_record($temp_sale_item);
            }

            $this->delivery_challan_model->edit_delivery_challan_record(array('sale_id'=>$id),$delivery_challan->id);

            // Commit transaction
            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Sales (Reference No. -' .$reference_no .') is added successfully.');
            redirect('sale/view/'.base64_encode($id),'refresh');
        } else {
            // Rollback transaction
            $this->db->trans_rollback();
            $this->session->set_flashdata('failure', 'Sales (Reference No. -' .$reference_no .') failed to add.');
            redirect('sale/view/'.base64_encode($id),'refresh');
        }    
    } else {
        $this->session->set_flashdata('success', 'Sale (Reference No. -' .$sale->reference_no.') already exists');
        redirect('sale/view/'.base64_encode($sale->id),'refresh');
    }
}

function copy($sale_id)
{
    $sale_id = base64_decode($sale_id);
    $original_sale = $this->sale_model->get_sale_single_record($sale_id);
    
    if(!$original_sale) {
        $this->session->set_flashdata('failure', 'Sale not found');
        redirect('sale');
    }

    $sale_items = $this->sale_model->get_sale_item_records($sale_id);
    $customer = $this->customer_model->get_single_record($original_sale->customer_id);
            $this->load->helper('common');
            $reference_no = get_next_sale_main_reference();        

    // QR code generation for main sale
    require_once FCPATH . 'application/libraries/phpqrcode/qrlib.php';
    $last_sale_id = $this->db->select_max('id')->get('sale')->row()->id;
    $new_sale_id = $last_sale_id + 1;
    $encoded_id = base64_encode($new_sale_id);
    $qr_data = base_url('Qr_sale/view_qr_sale/' . $encoded_id);
    $qr_filename = uniqid('qr_') . '.png';
    $qr_path = FCPATH . 'uploads/qr_codes/' . $qr_filename;
    QRcode::png($qr_data, $qr_path);

    // Box QR codes generation if no_of_boxes exists
    $box_qr_codes = null;
    $box_qr_data = null;
    if($original_sale->no_of_boxes > 0) {
        $box_qr_codes = array();
        $box_qr_data = array();
        
        // Create boxes directory if it doesn't exist
        if (!is_dir(FCPATH . 'uploads/qr_codes/boxes')) {
            mkdir(FCPATH . 'uploads/qr_codes/boxes', 0755, true);
        }
        
        for($i = 1; $i <= $original_sale->no_of_boxes; $i++) {
            $box_qr_filename = uniqid('box_qr_') . '.png';
            $box_qr_path = FCPATH . 'uploads/qr_codes/boxes/' . $box_qr_filename;
            $box_qr_content = $qr_data . '?box=' . $i;
            QRcode::png($box_qr_content, $box_qr_path);
            
            $box_qr_codes[] = $box_qr_filename;
            $box_qr_data[] = array(
                'box_number' => $i,
                'qr_code' => $box_qr_filename,
                'qr_content' => $box_qr_content,
                'products' => array() // Can be populated if you track products per box
            );
        }
        
        $box_qr_codes = implode(',', $box_qr_codes);
        $box_qr_data = json_encode($box_qr_data);
    }

    $sale_data = array(
        "quotation_id" => $original_sale->quotation_id,
        "reference_no" => $reference_no,
        "invoice_date" => date('Y-m-d'),
        "warehouse_id" => $original_sale->warehouse_id,
        "customer_id" => $original_sale->customer_id,
        "customer_gstin" => $original_sale->customer_gstin,
        "customer_shipping_country_id" => $original_sale->customer_shipping_country_id,
        "customer_shipping_state_id" => $original_sale->customer_shipping_state_id,
        "customer_shipping_city_id" => $original_sale->customer_shipping_city_id,
        "customer_shipping_address" => $original_sale->customer_shipping_address,
        "customer_shipping_pincode" => $original_sale->customer_shipping_pincode,
        "selected_shipping_address_id" => $original_sale->selected_shipping_address_id,
        "rcm" => $original_sale->rcm,
        "total_taxable_value" => $original_sale->total_taxable_value,
        "tds" => $original_sale->tds,
        "total_discount" => $original_sale->total_discount,
        "total_tax" => $original_sale->total_tax,
        "total" => $original_sale->total,
        "internal_note" => $original_sale->internal_note,
        "external_note" => $original_sale->external_note,
        "bank_detail" => $original_sale->bank_detail,
        "ecommerce_gstin" => $original_sale->ecommerce_gstin,
        "terms_and_condition" => $original_sale->terms_and_condition,
        "payment_terms" => $original_sale->payment_terms,
        "delete_status" => NOT_DELETED,
        "user_id" => $this->session->userdata('user_id'),
        "created_date" => date('Y-m-d H:i:s'),
        "lori_no" => $original_sale->lori_no,
        "lori_date" => $original_sale->lori_date,
        "carrier" => $original_sale->carrier,
        "vehicle_no" => $original_sale->vehicle_no,
        "ewaybill_no" => NULL,
        "additional_case" => $original_sale->additional_case,
        "ewaybill_dispatch_from" => $original_sale->ewaybill_dispatch_from,
        "ewaybill_mode_of_transportation" => $original_sale->ewaybill_mode_of_transportation,
        "ewaybill_subtype" => $original_sale->ewaybill_subtype,
        "ewaybill_doctype" => $original_sale->ewaybill_doctype,
        "ewaybill_transporter_name" => $original_sale->ewaybill_transporter_name,
        "ewaybill_transporter_gstin" => $original_sale->ewaybill_transporter_gstin,
        "ewaybill_distance_of_transportation" => $original_sale->ewaybill_distance_of_transportation,
        "ewaybill_transporter_doc_no" => $original_sale->ewaybill_transporter_doc_no,
        "ewaybill_transporter_doc_date" => $original_sale->ewaybill_transporter_doc_date,
        "ewaybill_vehicle_no" => $original_sale->ewaybill_vehicle_no,
        "ewaybill_vehicle_type" => $original_sale->ewaybill_vehicle_type,
        "purchase_order_no" => $original_sale->purchase_order_no,
        "purchase_order_date" => $original_sale->purchase_order_date,
        "dispatch_date" => NULL,
        "ewaybill_transportation_type" => $original_sale->ewaybill_transportation_type,
        "lut_no" => $original_sale->lut_no,
        "proforma_invoice_id" => $original_sale->proforma_invoice_id,
        "delivery_challan_id" => $original_sale->delivery_challan_id,
        "due_date" => $original_sale->due_date,
        "due_days" => $original_sale->due_days,
        "doctor_name" => $original_sale->doctor_name,
        "document" => NULL,
        "qr" => $qr_filename,
        "no_of_boxes" => $original_sale->no_of_boxes,
        "box_qr_codes" => $box_qr_codes,
        "total_purchase_cost" => $original_sale->total_purchase_cost,
        "additional_cost_type" => $original_sale->additional_cost_type,
        "additional_cost_amount" => $original_sale->additional_cost_amount,
        "total_selling_price" => $original_sale->total_selling_price,
        "gross_profit_loss" => $original_sale->gross_profit_loss,
        "profit_margin" => $original_sale->profit_margin,
        "status" => 'pending',
        "sale_receipt" => NULL,
        "freight_selling_price" => $original_sale->freight_selling_price,
        "freight_taxable_value" => $original_sale->freight_taxable_value,
        "freight_sub_total" => $original_sale->freight_sub_total,
        "freight_tax_rate" => $original_sale->freight_tax_rate,
        "freight_tax_amount" => $original_sale->freight_tax_amount,
        "box_qr_data" => $box_qr_data
    );

    // Begin transaction
    $this->db->trans_begin();

    if($new_sale_id = $this->sale_model->add_sale_record($sale_data)) {
        $entered_sale = $this->sale_model->get_sale_single_record($new_sale_id);
        $customer = $this->customer_model->get_single_record($entered_sale->customer_id);

        // Update ledgers
        /****************************** Start Sale Ledger ***********************************/
        $sale_ledger = $this->ledger_model->get_single_record(SALE_LEDGER);
        $updated_sale_ledger = array('closing_balance' => ($sale_ledger->closing_balance + $sale_data['total']));
        $this->ledger_model->edit_record($updated_sale_ledger, $sale_ledger->id);
        /****************************** End Sale Ledger ***********************************/

        /****************************** Start Customer Ledger ***********************************/
        $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
        $updated_customer_ledger = array('closing_balance' => ($customer_ledger->closing_balance + $sale_data['total']));
        $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);
        /****************************** End Customer Ledger ***********************************/

        /****************************** Start TDS Ledger ***********************************/
        if($sale_data['tds'] > 0) {
            $tds_ledger = $this->ledger_model->get_single_record(TDS_LEDGER);
            $updated_tds_ledger = array('closing_balance' => ($tds_ledger->closing_balance + $sale_data['tds']));
            $this->ledger_model->edit_record($updated_tds_ledger, $tds_ledger->id);

            $customer_ledger = $this->ledger_model->get_single_record($customer->ledger_id);
            $updated_customer_ledger = array('closing_balance' => ($customer_ledger->closing_balance - $sale_data['tds']));
            $this->ledger_model->edit_record($updated_customer_ledger, $customer->ledger_id);
        }
        /****************************** End TDS Ledger ***********************************/

        // Add transaction entry
        $transaction_header = array(
            "entry_id" => $new_sale_id,
            "module" => SALE_MODULE,
            "type" => SALE_TRANSACTION_TYPE,
            "amount" => $sale_data['total'],
            "voucher_date" => $sale_data['invoice_date'],
            "from_account" => SALE_LEDGER,
            "to_account" => $customer->ledger_id,
            "reference_no" => $sale_data['reference_no'],
            "warehouse_id" => $sale_data['warehouse_id'],
        );

        if($transaction_id = $this->transaction_model->add_record($transaction_header)) {
            $from_transaction_detail = array(
                "transaction_id" => $transaction_id,
                "voucher_type" => 'C',
                "ledger_id" => SALE_LEDGER,
                "dr_amount" => $sale_data['total']
            );
            $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

            $to_transaction_detail = array(
                "transaction_id" => $transaction_id,
                "voucher_type" => 'D',
                "ledger_id" => $customer->ledger_id,
                "cr_amount" => $sale_data['total']
            );
            $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
        }

        // Add TDS Transaction entry if applicable
        if($sale_data['tds'] > 0) {
            $transaction_header = array(
                "entry_id" => $new_sale_id,
                "module" => SALE_MODULE,
                "type" => TDS_TRANSACTION_TYPE,
                "amount" => $sale_data['tds'],
                "voucher_date" => $sale_data['invoice_date'],
                "from_account" => $customer->ledger_id,
                "to_account" => TDS_LEDGER,
                "reference_no" => $sale_data['reference_no'],
                "warehouse_id" => $sale_data['warehouse_id'],
            );

            if($transaction_id = $this->transaction_model->add_record($transaction_header)) {
                $from_transaction_detail = array(
                    "transaction_id" => $transaction_id,
                    "voucher_type" => 'C',
                    "ledger_id" => $customer->ledger_id,
                    "dr_amount" => $sale_data['tds']
                );
                $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                $to_transaction_detail = array(
                    "transaction_id" => $transaction_id,
                    "voucher_type" => 'D',
                    "ledger_id" => TDS_LEDGER,
                    "cr_amount" => $sale_data['tds']
                );
                $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
            }
        }

        // Add log data
        $log_data = array(
            "user_id" => $this->session->userdata("user_id"),
            "module" => "sale",
            "entry_id" => $new_sale_id,
            "user_action" => 1,
            "data" => json_encode((array)$entered_sale),
            "description" => 'Sales (Reference No. -' . $sale_data['reference_no'] . ') is copied from Sale ID: ' . $sale_id
        );
        $this->log_data_model->add_record($log_data);

        // Copy sale items
        foreach ($sale_items as $item) {
            $product = $this->product_model->get_single_record($item->product_id);
            $uom = $this->uom_model->get_single_record($product->uom_id);

            $temp_sale_item = array(
                'product_id' => $item->product_id,
                'warehouse_product_id' => $item->warehouse_product_id,
                'product_name' => $item->product_name,
                'product_remark' => $item->product_remark,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'cost' => $item->cost,
                'selling_price' => $item->selling_price,
                'taxable_value' => $item->taxable_value,
                'discount_id' => $item->discount_id,
                'discount_type' => $item->discount_type,
                'discount_value' => $item->discount_value,
                'discount_amount' => $item->discount_amount,
                'uom_id' => $uom->id,
                'uom_name' => $uom->name,
                'uom_uom' => $uom->uom,
                'tax_id' => $item->tax_id,
                'tax_type' => $item->tax_type,
                'igst' => $item->igst,
                'igst_tax' => $item->igst_tax,
                'cgst' => $item->cgst,
                'cgst_tax' => $item->cgst_tax,
                'sgst' => $item->sgst,
                'sgst_tax' => $item->sgst_tax,
                'igst_rate' => $item->igst_rate,
                'cgst_rate' => $item->cgst_rate,
                'sgst_rate' => $item->sgst_rate,
                'sub_total' => $item->sub_total,
                'sale_id' => $new_sale_id,
                'purchase_cost' => $item->purchase_cost,
                'supplier_id' => $item->supplier_id,
                'batch_no' => $item->batch_no,
                'mfg_date' => $item->mfg_date,
                'expiry_date' => $item->expiry_date
            );

            $this->sale_model->add_sale_item_record($temp_sale_item);
        }

        // Commit transaction
        $this->db->trans_commit();

        $this->session->set_flashdata('success', 'Sale (Reference No. -' . $reference_no . ') copied successfully with ' . $original_sale->no_of_boxes . ' box QR codes.');
        redirect('sale/view/' . base64_encode($new_sale_id), 'refresh');
    } else {
        // Rollback transaction - also delete any generated QR codes if the transaction fails
        if(isset($box_qr_codes)) {
            $box_qr_files = explode(',', $box_qr_codes);
            foreach($box_qr_files as $file) {
                @unlink(FCPATH . 'uploads/qr_codes/boxes/' . $file);
            }
        }
        @unlink(FCPATH . 'uploads/qr_codes/' . $qr_filename);
        
        $this->db->trans_rollback();
        $this->session->set_flashdata('failure', 'Failed to copy sale.');
        redirect('sale', 'refresh');
    }
}

	public function send_email($sale_id = null)
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

				$sale_id 		= $this->input->post("sale_id");

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

					$sale												= $this->sale_model->get_sale_single_record($sale_id);

					$sale_items 								= $this->sale_model->get_sale_item_records($sale->id);
					$customer_detail						= $this->customer_model->get_single_record($sale->customer_id);
					$company_setting						= $this->company_settings_model->get_company_records();	

					$data['proforma_invoice'] = $this->proforma_invoice_model->get_proforma_invoice_records_by_sale_id($sale->id);
	
					$data['customers'] 					= $this->customer_model->get_records();
					$data['customer_detail']		= $customer_detail;
					$data['sale']								= $sale;
					$data['sale_items'] 				= $sale_items;
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

			
					$html = $this->load->view('sale/pdf', $data, true);

					
          $pdf_filename = "Sale-" . $data['sale']->reference_no . ".pdf"; // Set your desired filename

          $cid = $company_setting->cid;
          $pdf_path = FCPATH . 'assets/documents/' . $cid . '/sale/' . $pdf_filename;

          // Create the target folder if it doesn't exist
          $targetDir = FCPATH . 'assets/documents/' . $cid . '/sale/';
          if (!file_exists($targetDir)) {
              mkdir($targetDir, 0777, true); // The third parameter creates nested directories if needed
          }


					// Save the PDF file directly to the given path
					//$pdf_path = FCPATH . 'assets/documents/' . $pdf_filename;
	
					
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


						// $mail_data['Body'] 							= $this->load->view('sale/pdf',$data,true);
					// commit transaction
					$this->db->trans_commit();

					// if($this->input->is_ajax_request())
					// {	
					// 	$response 											= array();
					// 	$response['code'] 							= 1;
					// 	$response['id'] 								= $id;
					// 	$response['message']						= 'Email Sent successfully.';
					// 	echo json_encode($response);
					// }
					// else
					// {
					// 	$this->session->set_flashdata('success', 'Email Sent successfully.');
					// 	redirect('quotation','refresh');
					// }
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
		
      //echo $sale_id;
      // exit;
			$data['sale'] 	= $this->sale_model->get_sale_single_record($sale_id);

      // print_r($data['sale']);
      // exit;
			$data['customer']		= $this->customer_model->get_single_record($data['sale']->customer_id);
			$data['company_setting']	= $this->company_settings_model->get_company_records();	
			$data['email_template'] 	= $this->email_template_model->get_record_by_module(EMAIL_TEMPLATE_MODULE_SALE);

			$response 												= array();
	  	$response['code']  								= 1;

			   
			$cc_emails = ''; 

			
			if (strpos($data['customer']->email, ',') !== false) 
			{
				$emails = explode(',', $data['customer']->email);
				$data['customer']->email = trim($emails[0]);

				$cc_emails = implode(',', array_slice($emails, 1));
			}

			$data['cc_emails'] = $cc_emails;
			
			$response['email_modal_body'] 	= $this->load->view('sale/ajax/email_modal_body',$data,TRUE);

			
	  	echo json_encode($response);
		}
	}

	function generate_sale_document_file($sale_id, $abs_path = true)
	{
			$sale												= $this->sale_model->get_sale_single_record($sale_id);
			$sale_items 								= $this->sale_model->get_sale_item_records($sale->id);
			$customer_detail						= $this->customer_model->get_single_record($sale->customer_id);
			$company_setting						= $this->company_settings_model->get_company_records();	

			$data['proforma_invoice'] = $this->proforma_invoice_model->get_proforma_invoice_records_by_sale_id($sale->id);

			$data['customers'] 					= $this->customer_model->get_records();
			$data['customer_detail']		= $customer_detail;
			$data['sale']								= $sale;
			$data['sale_items'] 				= $sale_items;
			$data['company_setting']		= $company_setting;
			$data['discounts']					= $this->discount_model->get_records();
	
			$html = $this->load->view('sale/pdf', $data, true);

			
			$pdf_filename = "Sale-" . $data['sale']->reference_no . ".pdf"; // Set your desired filename

			$cid = $company_setting->cid;
			$abs_pdf_path = FCPATH . 'assets/documents/' . $cid . '/sale/' . $pdf_filename;
			$rel_pdf_path = base_url('assets/documents/' . $cid . '/sale/' . $pdf_filename);

			// Create the target folder if it doesn't exist
			$targetDir = FCPATH . 'assets/documents/' . $cid . '/sale/';
			if (!file_exists($targetDir)) {
					mkdir($targetDir, 0777, true); // The third parameter creates nested directories if needed
			}
			
			$this->pdf->loadHtml($html);
			$this->pdf->set_paper('A4', $orientation ?? 'portrait'); 
			$this->pdf->render();

			// Save the rendered PDF content to the given path
			file_put_contents($abs_pdf_path, $this->pdf->output());

			if(!$abs_path)
				return $rel_pdf_path;
			else
				return $abs_pdf_path;
			
	}
	
	


	/************************************** Start Dynamic Datatable function *************************************/

public function ajax_list()
  {
    $list 		= $this->sale_model->get_datatables();
	$query    = $this->db->last_query();
    $data 		= array();
    $no 			= $_POST['start'];
    $sales_payment = $_POST['sales_payment'];

	$is_customer = $this->customer_model->is_loggedin_user_is_customer();

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

        $delivered_product_qty = $this->sale_delivery_model->get_total_no_of_quantity_delivered($item->id);
        $paid_amount = $this->transaction_model->get_total_transaction_amount($item->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE) + $this->transaction_model->get_total_transaction_amount($item->id,SALE_MODULE,CREDIT_TRANSACTION_TYPE);

    
                      $table_body .= '<a href="#"  data-tt="tooltip" title="Click here to send whatsapp message" data-multiple="false" class="btn btn-secondary btn-sm text-white whatsapp_message_modal '.(($item->delete_status == DELETED) ? " d-none" : "").'" data-sale_id="'.$item->id.'">
                      <i class="fab fa-whatsapp"></i> 
                    </a>';

			
					if($this->permission_model->has_permission('email_sale'))
					{
				
							$table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('send_email').'" class="btn btn-success btn-sm text-white email-modal '.(($item->delete_status == DELETED) ? " d-none" : "").'" data-sale_id="'.$item->id.'">
													<i class="fas fa-at"></i>
												</a>';
				
					}
				

				//View Button
    			  if($this->permission_model->has_permission('view_sale') || $this->permission_model->has_permission('view_all_sale'))
    				{	
    					$table_body .= ' 
                                <a href="'.base_url('sale/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs '.(($item->delete_status == DELETED) ? " d-none" : "").'" data-tt="tooltip" title="View Sale">
                                  <i class="fas fa-eye"></i>
                                </a>      
                              ';
    				}


        

				// Edit Button        
       	            if($this->permission_model->has_permission('edit_sale') || $this->permission_model->has_permission('edit_all_sale'))
    				{	
    					if($paid_amount > 0 || $delivered_product_qty > 0)
                          {
                 
                          	$table_body .= 	 '<a href="#" class="btn btn-info btn-xs '.(($item->delete_status == DELETED) ? " d-none" : "").'" data-toggle="modal" data-target                    ="#sale_edit" data-tt="tooltip" title="Edit Sale" data-sale_id="'.$item->id.'">
                	                                <i class="fas fa-edit"></i>
                	                            </a>
                	                         ';
                          	
                          }
                         else
                          {
                          	
                          	$table_body .= ' <a href="'.base_url('sale/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs '.(($item->delete_status == DELETED) ? " d               -none" : "").'"  data-tt="tooltip" title="Edit Sale">
                                                <i class="fas fa-edit"></i>
                                              </a>
                                            ';
                          }
                              $table_body .= '
                                                <a href="'.base_url('sale/copy/'.base64_encode($item->id)).'" class="btn bg-purple btn-xs" data-tt="tooltip" title="Click here to Create Duplicate Sale">
                                                    <i class="far fa-copy"></i>
                                                </a>
                                            ';
    					
    				}

    				// Delete Button
    				if($this->permission_model->has_permission('delete_sale') || $this->permission_model->has_permission('delete_all_sale'))
    				{	
    					if($item->delete_status == NOT_DELETED)
    					{
    
    						$table_body .= '
    		                        <a href="#"  data-toggle="modal" data-target="#delete_sale" data-tt="tooltip" title="Delete Sale" class="btn btn-danger btn-xs delete_sale " data-sale_id="'.$item->id.'">
    		                          <i class="fas fa-trash"></i> 
    		                        </a>
    													';
    					}
    					else
    					{
    						$table_body .= '
    		                        <a href="'.base_url('sale/regenerate/'.base64_encode($item->id)).'"  data-tt="tooltip" title="Regenerate Sale" class="btn btn-primary btn-xs" data-sale_id="'.$item->id.'">
    		                          <i class="fas fa-redo-alt"></i>
    		                        </a>
    													';		
    					}
    				}
    				
    				
                           
                  			$table_body .= '
    		                        <a href="#"  data-toggle="modal" data-target="#upload_docs_modal" data-tt="tooltip" title="Upload Documents" class="btn btn-primary btn-xs upload_sale " data-sale_id="'.$item->id.'">
    		                          <i class="fas fa-upload"></i> 
    		                        </a>
    						';
                    		$table_body .= '
                                <a href="#" data-toggle="modal" data-target="#view_docs_modal" data-tt="tooltip" title="View Documents" class="btn btn-warning btn-xs view_sale_docs" data-sale_id="'.$item->id.'">
                                    <i class="fas fa-file-alt"></i> 
                                </a>
                            ';											


    				$ordered_quantity = (float)$this->sale_delivery_model->get_total_no_of_quantity_ordered($item->id);
                    $delivered_quantity = (float)$this->sale_delivery_model->get_total_no_of_quantity_delivered($item->id);
    
    				$paid_amount_html 	=	'<span class="text-success">'.(($paid_amount == '') ? '0.000' : number_format_i($paid_amount)).'</span>';
    
    				$due_amount_html 		=  '<span class="text-danger">'.number_format_i(($item->total-$paid_amount-$item->tds)).'</span>';
    
            //Delievery status
    				// $delivered_quantity_html = '';


        //             if($delivered_quantity == 0)
        //             {
        //     					if(!$is_customer){
        //     						$delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
        //     																				<img src="'.base_url('assets/images/not_delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
        //     																			</a>';
        //     					}
        //     					else{
        //     						$delivered_quantity_html .= '<a href="#"  class="btn  btn-sm" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
        //     																				<img src="'.base_url('assets/images/not_delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
        //     																			</a>';
        //     					}
        //             }
        //             else if($delivered_quantity < $ordered_quantity && $delivered_quantity > 0)
        //             {
        //     					if(!$is_customer){
        //     						$delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
        //     																				<img src="'.base_url('assets/images/partial_delivered.png').'" data-tt="tooltip" width="40px" style="cursor: pointer">
        //     																			</a>';
        //     					}
        //     					else{
        //     						$delivered_quantity_html .= '<a href="#" class="btn  btn-sm" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
        //     																				<img src="'.base_url('assets/images/partial_delivered.png').'" data-tt="tooltip" width="40px" style="cursor: pointer">
        //     																			</a>';
        //     					}
        //             }
        //             else if($delivered_quantity == $ordered_quantity)
        //             {
        //     					if(!$is_customer){
        //     						$delivered_quantity_html .= '<a href="#" data-target="#delivery-modal" data-toggle="modal" class="btn  btn-sm open_delivery_modal" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
        //     																				<img src="'.base_url('assets/images/delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
        //     																			</a>';
        //     					}else{
        //     						$delivered_quantity_html .= '<a href="#" class="btn  btn-sm" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
        //     																				<img src="'.base_url('assets/images/delivered.png').'" data-tt="tooltip"  width="40px" style="cursor: pointer">
        //     																			</a>';
        //     					}
        //             }
        
        $delivered_quantity_html = '';

if($delivered_quantity == 0)
{
    if(!$is_customer){
        $delivered_quantity_html .= '<div class="text-center">
                                        <button type="button" class="btn btn-xs btn-danger open_delivery_modal" data-target="#delivery-modal" data-toggle="modal" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
                                            Pending
                                        </button>
                                        <div class="mt-1">
                                            <img src="'.base_url('assets/images/not_delivered.png').'" width="40">
                                        </div>
                                    </div>';
    } else {
        $delivered_quantity_html .= '<div class="text-center">
                                        <button type="button" class="btn btn-xs btn-danger" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
                                            Pending
                                        </button>
                                        <div class="mt-1">
                                            <img src="'.base_url('assets/images/not_delivered.png').'" width="40">
                                        </div>
                                    </div>';
    }
}
else if($delivered_quantity < $ordered_quantity && $delivered_quantity > 0)
{
    if(!$is_customer){
        $delivered_quantity_html .= '<div class="text-center">
                                        <button type="button" class="btn btn-xs btn-warning open_delivery_modal" data-target="#delivery-modal" data-toggle="modal" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
                                            Partial
                                        </button>
                                        <div class="mt-1">
                                            <img src="'.base_url('assets/images/partial_delivered.png').'" width="40">
                                        </div>
                                    </div>';
    } else {
        $delivered_quantity_html .= '<div class="text-center">
                                        <button type="button" class="btn btn-xs btn-warning" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
                                            Partial
                                        </button>
                                        <div class="mt-1">
                                            <img src="'.base_url('assets/images/partial_delivered.png').'" width="40">
                                        </div>
                                    </div>';
    }
}
else if($delivered_quantity == $ordered_quantity)
{
    if(!$is_customer){
        $delivered_quantity_html .= '<div class="text-center">
                                        <button type="button" class="btn btn-xs btn-success open_delivery_modal" data-target="#delivery-modal" data-toggle="modal" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
                                            Delivered
                                        </button>
                                        <div class="mt-1">
                                            <img src="'.base_url('assets/images/delivered.png').'" width="40">
                                        </div>
                                    </div>';
    } else {
        $delivered_quantity_html .= '<div class="text-center">
                                        <button type="button" class="btn btn-xs btn-success" data-tt="tooltip" title="Enter Product Delivery" data-sale_id="'.$item->id.'">
                                            Delivered
                                        </button>
                                        <div class="mt-1">
                                            <img src="'.base_url('assets/images/delivered.png').'" width="40">
                                        </div>
                                    </div>';
    }
}
                // Fetch conversion status for this sale
$converted = $this->db->select('converted')
                      ->from('sale')
                      ->where('id', $item->id)
                      ->get()
                      ->row()
                      ->converted ?? 0;

$reference_no_html = '';

// Fetch conversion status for this sale
$converted = $this->db->select('converted')
                      ->from('sale')
                      ->where('id', $item->id)
                      ->get()
                      ->row()
                      ->converted ?? 0;

$reference_no_html = '';

// If deleted, show plain reference no
if ($item->delete_status == DELETED) {
    $reference_no_html .= $item->reference_no;
} else {
    // If converted, just add (Converted) text after reference
    if ($converted == 1) {
        $reference_no_html .= '<a href="' . base_url('sale/view/' . base64_encode($item->id)) . '" 
            data-tt="tooltip" title="' . $this->lang->line('sale_view') . '">'
            . $item->reference_no . ' (Req Converted)</a>';
    } else {
        $reference_no_html .= '<a href="' . base_url('sale/view/' . base64_encode($item->id)) . '" 
            data-tt="tooltip" title="' . $this->lang->line('sale_view') . '">'
            . $item->reference_no . '</a>';
    }
}


    
    //              $customer_name_html = '<a href="' . base_url('customer/view/' . base64_encode($item->customer_id)) . '" data-tt="tooltip" title="' . $this->lang->line('sale_view_customer_detail') . '">'
    // . $item->customer_name . ' (' . ($item->customer_company_name ?? '-') . ')</a>';

$displayName = !empty($item->customer_company_name) ? $item->customer_company_name : $item->customer_name;
$subName = !empty($item->customer_company_name) ? " ($item->customer_name)" : "";

$customer_name_html = '<a href="' . base_url('customer/view/' . base64_encode($item->customer_id)) . '" data-tt="tooltip" title="' . $this->lang->line('sale_view_customer_detail') . '">'
    . $displayName . '</a>';
                                  
        				/* End Action column buttons*/
        
        				$select_html = '<input type="checkbox" class="single_sale" data-sale_id="'.$item->id.'"'.(($item->delete_status == 1) ? " disabled" : "").'>';
        
        			
        
        	          $row = array();
        	       
        			  $row[] = $select_html;
        		      $row[] = $reference_no_html;
        		    
        		      $row[] = ($item->delete_status == DELETED) ? '' : date('d-m-Y', strtotime($item->invoice_date));
        		      $row[] = ($item->delete_status == DELETED) ? '' : $customer_name_html;
        		      //$row[] = '<td class="' . ($item->delete_status == DELETED ? 'd-none' : '') . '">' . number_format_i($item->total_discount) . '</td>';
        		      $row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->total_taxable_value);
        		      //$row[] = ($item->delete_status == DELETED) ? '' : number_format_i($item->tds);
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
                        "recordsTotal" 		=> $this->sale_model->count_all(),
                        "recordsFiltered" => $this->sale_model->count_filtered(),
                        "data" 						=> $data,
    										"query"           => $query
                			);
       
        echo json_encode($output);
  }	

  public function sale_delete_confirmation()
    {
      	$sale_id 				= $this->input->post('sale_id');
      	$data['sale'] 	= $this->sale_model->get_sale_single_record($sale_id);
    
      	$response 					= array();
      	$response['sale_delete_modal_body'] 	= $this->load->view('sale/ajax/sale_delete_modal_view',$data,TRUE);
    
      	echo json_encode($response);
    }
    
  public function sale_upload_confirmation()
    {
      	$sale_id 				= $this->input->post('sale_id');
      	$data['sale'] 	= $this->sale_model->get_sale_single_record($sale_id);
    
      	$response 					= array();
      	$response['sale_upload_modal_body'] 	= $this->load->view('sale/ajax/sale_upload_modal_view',$data,TRUE);
    
      	echo json_encode($response);
    }
    
    public function sale_receipt_view_confirmation()
    {
        $sale_id = $this->input->post('sale_id');
        $data['sale'] = $this->sale_model->get_sale_single_record($sale_id);
        
        if(!$data['sale']) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Sale not found'
            ]);
            return;
        }
    
        $response = [
            'status' => 'success',
            'sale_view_modal_body' => $this->load->view('sale/ajax/view_sale_receipt', $data, TRUE)
        ];
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }   

	public function sale_edit_confirmation()
  {
  	$sale_id 				= $this->input->post('sale_id');
  	$data['sale'] 		= $this->sale_model->get_sale_single_record($sale_id);

  	$response 					= array();
  	$response['sale_edit_modal_body'] 	= $this->load->view('sale/ajax/sale_edit_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	/************************************** End Dynamic Datatable function ***************************************/

		/*check invoice no*/

	function get_record_detail_by_reference_no()
	{
		$reference_no = $this->input->post('reference_no');
		$module 			= $this->input->post('module');
		$product_id 	= $this->input->post('product_id');

		$sale 				= $this->sale_model->get_sale_record_details_by_reference_no($reference_no);

		$sale_items 	= array();


		$sale_items 	= $this->warehouse_products_model->get_single_record_for_sales($product_id);

		/*if($module == SALE_MODULE || $module == SALE_RETURN_MODULE)
		{
			$product 	= $this->warehouse_products_model->get_single_record_for_sales($product_id);
			$data['discount']	= $this->discount_model->get_records();	
		}
		else
		{
			$product = $this->product_model->get_single_record($product_id);
			$data['discount']= $this->discount_model->get_records();	
		}*/

		//$sale_items   = $this->sale_model->get_sale_item_records($reference_no);

		$response = array();

		if($sale != null)
		{
			$response['code'] 				= 0;
			$response['message'] 			= $reference_no.' is already exists.';
			$response['sale']					= $sale;
			$response['sale_items']		= $sale_items;
			//$response['sale_items'] =	$sale_item;

			echo json_encode($response);
		}
		else
		{
			$response['code'] = 1;
			$response['message'] = 'Sale does not exist with this'.$reference_no;

			echo json_encode($response);	
		}
	}

	public function upload_documents() 
	{
    if (isset($_FILES["upload_document"])) 
		{
			$data['company_setting'] 	= $this->company_settings_model->get_company_records();

			$cid = $data['company_setting']->cid; 

			$targetDir = "./assets/documents/$cid/sale/";

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
	
    // public function upload_sale_receipt()
    // {
    //     $response = array(
    //         'status' => 'error',
    //         'message' => 'No document file provided.'
    //     );
    
    //     if (isset($_FILES["document_file"])) 
    //     {
    //         $sale_id = $this->input->post('sale_id');
    //         $document_type = $this->input->post('document_type');
    //         $other_title = $this->input->post('other_document_title');
            
    //         // Check if sale exists
    //         $sale = $this->db->get_where('sale', ['id' => $sale_id])->row();
    //         if (!$sale) {
    //             $response['message'] = 'Sale not found';
    //             echo json_encode($response);
    //             return;
    //         }
            
    //         // Get company settings for directory structure
    //         $data['company_setting'] = $this->company_settings_model->get_company_records();
    //         $cid = $data['company_setting']->cid; 
            
    //         // Set upload directory
    //         $targetDir = "./assets/documents/$cid/sale_receipts/";
            
    //         if (!file_exists($targetDir)) {
    //             mkdir($targetDir, 0777, true);
    //         }
            
    //         // Process file upload
    //         $uploadFilename = basename($_FILES["document_file"]["name"]);
    //         $microtime = microtime(true);
    //         $microsecondPrefix = str_replace(".", "_", $microtime);
    //         $newFilename = $microsecondPrefix . "_" . $uploadFilename;
    //         $targetFile = $targetDir . $newFilename;
            
    //         if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $targetFile)) {
    //             $file_path = "assets/documents/$cid/sale_receipts/" . $newFilename;
                
    //             // Get existing documents or initialize empty array
    //             $existing_docs = [];
    //             if (!empty($sale->sale_receipt)) {
    //                 $existing_docs = json_decode($sale->sale_receipt, true);
    //             }
                
    //             // Add new document
    //             $new_doc = [
    //                 'title' => $document_title,
    //                 'file' => $file_path,
    //                 'date' => date('Y-m-d H:i:s'),
    //                 'uploaded_by' => $this->session->userdata('user_id')
    //             ];
                
    //             $existing_docs[] = $new_doc;
                
    //             // Update sale record
    //             $this->db->where('id', $sale_id);
    //             $update_result = $this->db->update('sale', [
    //                 'sale_receipt' => json_encode($existing_docs)
    //             ]);
                
    //             if ($update_result) {
    //                 $response = [
    //                     'status' => 'success',
    //                     'message' => 'Document uploaded successfully',
    //                     'document' => $new_doc,
    //                     'file_name' => $newFilename
    //                 ];
    //             } else {
    //                 // Delete the uploaded file if DB update failed
    //                 @unlink($targetFile);
    //                 $response['message'] = 'Failed to update sale record';
    //             }
    //         } else {
    //             $response['message'] = "Error uploading document: " . $_FILES["document_file"]["error"];
    //         }
    //     }
        
    //     echo json_encode($response);
    // }



public function upload_sale_receipt()
{
    $response = array(
        'status' => 'error',
        'message' => 'No document file provided.'
    );

    if (isset($_FILES["document_file"])) 
    {
        $sale_id = $this->input->post('sale_id');
        $document_type = $this->input->post('document_type');
        $other_title = $this->input->post('other_document_title');
        
        // Validate document type
        if (empty($document_type)) {
            $response['message'] = 'Please select a document type';
            echo json_encode($response);
            return;
        }
        
        // For "Other Documents" type, validate title
        if ($document_type === 'Other Documents' && empty($other_title)) {
            $response['message'] = 'Please provide a document title for "Other Documents"';
            echo json_encode($response);
            return;
        }
        
        // Determine the document title
        $document_title = ($document_type === 'Other Documents') ? $other_title : $document_type;
        
        // Check if sale exists
        $sale = $this->db->get_where('sale', ['id' => $sale_id])->row();
        if (!$sale) {
            $response['message'] = 'Sale not found';
            echo json_encode($response);
            return;
        }
        
        // Get company settings for directory structure
        $data['company_setting'] = $this->company_settings_model->get_company_records();
        $cid = $data['company_setting']->cid; 
        
        // Set upload directory
        $targetDir = "./assets/documents/$cid/sale_receipts/";
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Validate file type and size
        $allowed_types = ['application/pdf', 'image/jpeg', 'image/png', 
                         'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        $file_type = $_FILES["document_file"]["type"];
        $file_size = $_FILES["document_file"]["size"];
        
        if (!in_array($file_type, $allowed_types)) {
            $response['message'] = 'Invalid file type. Only PDF, JPG, PNG, and DOCX are allowed.';
            echo json_encode($response);
            return;
        }
        
        if ($file_size > $max_size) {
            $response['message'] = 'File size exceeds 2MB limit.';
            echo json_encode($response);
            return;
        }
        
        // Process file upload
        $uploadFilename = basename($_FILES["document_file"]["name"]);
        $microtime = microtime(true);
        $microsecondPrefix = str_replace(".", "_", $microtime);
        $newFilename = $microsecondPrefix . "_" . $uploadFilename;
        $targetFile = $targetDir . $newFilename;
        
        if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $targetFile)) {
            $file_path = "assets/documents/$cid/sale_receipts/" . $newFilename;
            
            // Get existing documents or initialize empty array
            $existing_docs = [];
            if (!empty($sale->sale_receipt)) {
                $existing_docs = json_decode($sale->sale_receipt, true);
            }
            
            // Add new document
            $new_doc = [
                'title' => $document_title,
                'type' => $document_type,
                'file' => $file_path,
                'date' => date('Y-m-d H:i:s'),
                'uploaded_by' => $this->session->userdata('user_id')
            ];
            
            $existing_docs[] = $new_doc;
            
            // Update sale record
            $this->db->where('id', $sale_id);
            $update_result = $this->db->update('sale', [
                'sale_receipt' => json_encode($existing_docs)
            ]);
            
            if ($update_result) {
                $response = [
                    'status' => 'success',
                    'message' => 'Document uploaded successfully',
                    'document' => $new_doc,
                    'file_name' => $newFilename
                ];
                
                // Log the action
                // $log_data = array(
                //     "user_id" => $this->session->userdata("user_id"),
                //     "module" => "sale",
                //     "user_action" => 2,
                //     "description" => "Uploaded document for sale #" . $sale->reference_no
                // );
                // $this->log_data_model->add_record($log_data);
            } else {
                // Delete the uploaded file if DB update failed
                @unlink($targetFile);
                $response['message'] = 'Failed to update sale record';
            }
        } else {
            $response['message'] = "Error uploading document: " . $_FILES["document_file"]["error"];
        }
    }
    
    echo json_encode($response);
}

    // public function get_sale_documents($sale_id)
    // {
    //     $response = array(
    //         'status' => 'error',
    //         'message' => 'No documents found'
    //     );
    
    //     // Check if sale exists
    //     $sale = $this->db->get_where('sale', ['id' => $sale_id])->row();
    //     if (!$sale) {
    //         $response['message'] = 'Sale not found';
    //         echo json_encode($response);
    //         return;
    //     }
    
    //     // Get documents if they exist
    //     if (!empty($sale->sale_receipt)) {
    //         $documents = json_decode($sale->sale_receipt, true);
    //         $response = array(
    //             'status' => 'success',
    //             'documents' => $documents
    //         );
    //     }
    
    //     echo json_encode($response);
    // }
    
    public function get_sale_documents($sale_id)
    {
        $response = array(
            'status' => 'error',
            'message' => 'No documents found'
        );
    
        // Check if sale exists
        $sale = $this->db->get_where('sale', ['id' => $sale_id])->row();
        if (!$sale) {
            $response['message'] = 'Sale not found';
            echo json_encode($response);
            return;
        }
    
        // Get documents if they exist
        if (!empty($sale->sale_receipt)) {
            $documents = json_decode($sale->sale_receipt, true);
            
            // Ensure each document has a type field (for backward compatibility)
            foreach ($documents as &$doc) {
                if (!isset($doc['type'])) {
                    $doc['type'] = 'Other Documents';
                }
            }
            
            $response = array(
                'status' => 'success',
                'documents' => $documents
            );
        }
    
        echo json_encode($response);
    }

	public function send_message()
  {
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$errors = [];
			$test = [];
			$response = array();

			$this->form_validation->set_rules('wm_message', 'Message', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				if ($this->input->is_ajax_request())
				{
					$response['code'] = 2;
					$response['errors'] = $this->form_validation->error_array();
					echo json_encode($response);
				}
				else
				{
					$this->load->view('errors/html/error_restricted'); 
				}
			}
			else
			{
					$wm_type 		  = $this->input->post('wm_type');
					$wm_message 	= $this->input->post('wm_message');
					$sale_ids 		= $this->input->post('sale_ids');
			
					$sale_ids_array = explode(",",$sale_ids);
		
					for ($i=0; $i < sizeof($sale_ids_array); $i++) { 
						$sale 		= $this->sale_model->get_sale_single_record($sale_ids_array[$i]);
						$customer = $this->customer_model->get_single_record($sale->customer_id);
		
						if($customer->whatsapp_no != '' && $customer->whatsapp_country_code != '')
						{
							$wm_to 		= $customer->whatsapp_country_code.$customer->whatsapp_no;
							$wm_file 	= $this->generate_sale_document_file($sale_ids_array[$i], $abs_path = false);

							$data 	= array(
													"wm_message" 	=> $this->personalize_message($wm_type,$sale->id,$wm_message),
													"wm_to" 			=> $wm_to,
													"wm_file"     => $wm_file
												);

							if(!$this->whatsapp_message_model->add_record($data))
								$errors[] = $customer->customer_name;
							
						}
						else
						{
							$errors[] = $customer->customer_name;
						}
					}
					
					$this->db->trans_begin();

					if (empty($errors))
					{
							// commit transaction
							$this->db->trans_commit();

							if ($this->input->is_ajax_request())
							{
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
								$message = "Below customers do not have whatsapp number in system so we can not send whatsapp message to below customers : ";
								
								$response['code'] = RESPONSE_FAILURE;
								$response['message'] = $message.implode(', ', $errors);

								echo json_encode($response);
							}
							else
							{
									$this->session->set_flashdata('failure', $message.implode(', ', $errors));
									redirect('whatsapp_message', 'refresh');
							}
					}
			}
			
		}
		else 
		{ 
			$data = array();
			
      $response['whatsapp_message_modal_body'] = $this->load->view('sale/ajax/whatsapp_message_modal_view', $data, TRUE);
      echo json_encode($response);
		}
  }

	private function personalize_message($wm_type,$sale_id,$message)
	{
		$search = array(
									"{{invoice_no}}",               // Common in sale_confirmation, payment_confirmation, and payment_reminder
									"{{customer_name}}",            // Common in all, renamed to "{{recipient_name}}"
									"{{company_name}}",             // Common in all
									"{{invoice_amount}}",           // Common in sale_confirmation and payment_confirmation
									"{{pending_amount}}",           // Common in payment_confirmation and payment_reminder
									"{{invoice_date}}",             // Common in sale_confirmation, payment_confirmation, and payment_reminder
									// "{{payment_date}}",             // Unique to payment_confirmation
									"{{amount_received}}",          // Unique to payment_confirmation
									// "{{payment_method}}",           // Unique to payment_confirmation
									// "{{customer_billing_address}}", // Unique to sale_confirmation
									// "{{customer_shipping_address}}",// Unique to sale_confirmation
									// "{{due_date}}",                 // Unique to payment_reminder
									// "{{due_days}}",                 // Unique to payment_reminder
									// "{{bank_name}}",                // Unique to payment_reminder
									// "{{bank_account_number}}",      // Unique to payment_reminder
									// "{{ifsc_code}}"                 // Unique to payment_reminder
							);
	  
		$sale 				= $this->sale_model->get_sale_single_record($sale_id);
		$customer 		= $this->customer_model->get_single_record($sale->customer_id);
		$paid_amount 	= $this->transaction_model->get_total_transaction_amount($sale_id,SALE_MODULE,RECEIPT_TRANSACTION_TYPE) 
											+ $this->transaction_model->get_total_transaction_amount($sale_id,SALE_MODULE,CREDIT_TRANSACTION_TYPE);
	  $due_amount 	= $sale->total-$paid_amount;
		$total_amount = $sale->total;

		$replace = array(
											$sale->reference_no, 
											$customer->customer_name,
											$company_setting->company_name,
											$sale->total,
											$due_amount,
											date('d-m-Y',strtotime($sale->invoice_date)),
											$paid_amount
										);
		return  str_replace($search, $replace, $message);
	}

}

