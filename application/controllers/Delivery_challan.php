<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Delivery_challan extends MY_Controller {

	private $pdf;
	public function __construct()
	{
	    
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);

		parent::__construct();
		$this->pdf = new Dompdf();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	public function index()
	{
			$data['customer'] 		= $this->customer_model->get_records();
			$data['delivery_challans'] 				= $this->delivery_challan_model->get_delivery_challan_records();
            $data['total_delivery_challan']		= $this->delivery_challan_model->get_total_delivery_challan();
			
// 			$log_data = array(
//                   "user_id"     => $this->session->userdata("user_id"),
//                   "module"      => "delivery_challan",
//                   "user_action" => 0,
//                   "description"	=> "User has viewed list of Delivery challan."
//                 );

// 			$this->log_data_model->add_record($log_data);

// 			echo "<pre>";
// 			print_r($data);
// 			echo "</pre>";
// 			die;

			$this->load->view('delivery_challan/list',$data);
		}
	
public function add()
{
    if(!$this->permission_model->has_permission('add_delivery_challan'))
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
            $this->form_validation->set_rules('invoice_date','Invoice Date','required');        
            $this->form_validation->set_rules('customer_id','Customer','required');
            $this->form_validation->set_rules('warehouse_id','Warehouse','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['customers'] = $this->customer_model->get_records();
                $data['warehouses'] = $this->warehouse_model->get_records();
                $data['suppliers'] = $this->supplier_model->get_records();
                $data['company_setting'] = $this->company_settings_model->get_company_records();    
                $this->load->view('delivery_challan/add',$data);
            }
            else
            {
                // Process profit data
                $profit_data = json_decode($this->input->post('profit_data'), true);
                $total_purchase_cost = isset($profit_data['total_purchase_cost']) ? $profit_data['total_purchase_cost'] : 0;
                $additional_cost_amount = isset($profit_data['freight_amount']) ? $profit_data['freight_amount'] : 0;
                $total_selling_price = isset($profit_data['total_selling_price']) ? $profit_data['total_selling_price'] : 0;
                $gross_profit_loss = isset($profit_data['gross_profit_loss']) ? $profit_data['gross_profit_loss'] : 0;
                $profit_margin = isset($profit_data['profit_margin']) ? $profit_data['profit_margin'] : 0;
                $transport_type = isset($profit_data['transport_type']) ? $profit_data['transport_type'] : null;

                // Process freight data
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

                $invoice_date = date('Y-m-d', strtotime($this->input->post('invoice_date')));
                $reference_no = $this->delivery_challan_model->get_lastest_sequence_number();
        
                $warehouse_id = $this->input->post('warehouse_id');
                $customer_id = $this->input->post('customer_id');
                
                $selected_shipping_address_id = $this->input->post('shipping_address_select');
                $customer_shipping_country_id = $this->input->post('customer_shipping_country_id');
                $customer_shipping_state_id = $this->input->post('customer_shipping_state_id');
                $customer_shipping_city_id = $this->input->post('customer_shipping_city_id');
                $customer_shipping_address = $this->input->post('customer_shipping_address');
                $customer_shipping_pincode = $this->input->post('customer_shipping_pincode');                    

                $purchase_order_no = $this->input->post('purchase_order_no');
                
                $customer = $this->customer_model->get_single_record($customer_id);
                $customer_gstin = $customer->gstin;
                $rcm = $this->input->post('rcm');
                $total_taxable_value = $this->input->post('total_taxable_value');
                $tds = $this->input->post('tds');
                $total_discount = $this->input->post('total_discount');
                $total_tax = $this->input->post('total_tax');
                $total = $this->input->post('total');
                $internal_note = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $bank_detail = nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

                $lori_no = $this->input->post('lori_no');
                $carrier = $this->input->post('carrier');
                $vehicle_no = $this->input->post('vehicle_no');
                $ewaybill_no = $this->input->post('ewaybill_no');
                $additional_case = $this->input->post('additional_case');

                $ewaybill_dispatch_from = $this->input->post('ewaybill_dispatch_from');
                $ewaybill_mode_of_transportation = $this->input->post('ewaybill_mode_of_transportation');
                $ewaybill_subtype = $this->input->post('ewaybill_subtype');
                $ewaybill_doctype = $this->input->post('ewaybill_doctype');
                $ewaybill_transporter_name = $this->input->post('ewaybill_transporter_name');
                $ewaybill_transporter_gstin = $this->input->post('ewaybill_transporter_gstin');
                $ewaybill_distance_of_transportation = $this->input->post('ewaybill_distance_of_transportation');
                $ewaybill_transporter_doc_no = $this->input->post('ewaybill_transporter_doc_no');
                $ewaybill_vehicle_no = $this->input->post('ewaybill_vehicle_no');
                $ewaybill_vehicle_type = $this->input->post('ewaybill_vehicle_type');
                $available_credit = $this->input->post('available_credit');
                $margin = $this->input->post('margin');
                $document = $this->input->post("document");
                   $due_days                                     = $this->input->post('due_days');
                $payment_terms                                = $this->input->post('payment_terms');
                
                if ($due_days) {
                    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($invoice_date)));
                } else {
                    $due_date = NULL;
                }
                if (!is_array($document)) {
                    $document = array($document);
                }
                $documents = implode(',', $document);

                $delivery_challan_data = array(
                    "invoice_date" => $invoice_date,
                    "reference_no" => $reference_no,
                    "warehouse_id" => $warehouse_id,
                    "customer_id" => $customer_id,
                    "selected_shipping_address_id" => $selected_shipping_address_id,
                    "customer_shipping_country_id" => $customer_shipping_country_id,
                    "customer_shipping_state_id" => $customer_shipping_state_id,
                    "customer_shipping_city_id" => $customer_shipping_city_id,
                    "customer_shipping_address" => $customer_shipping_address,
                    "customer_shipping_pincode" => $customer_shipping_pincode,
                    "customer_gstin" => $customer_gstin,
                    "rcm" => $rcm,
                    "total_taxable_value" => $total_taxable_value,    
                    "tds" => $tds,    
                    "total_tax" => $total_tax,
                    "total_discount" => $total_discount,    
                    "total" => $total,
                    "internal_note" => $internal_note,
                    "external_note" => $external_note,
                    "bank_detail" => $bank_detail,
                    "terms_and_condition" => $terms_and_condition,
                    "lori_no" => $lori_no,
                    "carrier" => $carrier,
                    "vehicle_no" => $vehicle_no,
                    "ewaybill_no" => $ewaybill_no,
                    "additional_case" => $additional_case,
                    "document" => $documents,
                    "user_id" => $this->session->userdata('user_id'),
                    // Add new fields for profit/loss and freight
                    "additional_cost_type" => $this->input->post('additional_cost_type'),
                    "total_purchase_cost" => $total_purchase_cost,
                    "total_selling_price" => $total_selling_price,
                    "gross_profit_loss" => $gross_profit_loss,
                    "profit_margin" => $profit_margin,
                    // "transport_type" => $transport_type,
                    "additional_cost_amount" => $additional_cost_amount,
                    "freight_selling_price" => $freightData['freight_selling_price'],
                    "freight_taxable_value" => $freightData['freight_taxable_value'],
                    "freight_sub_total" => $freightData['freight_sub_total'],
                    "freight_tax_rate" => $freightData['freight_tax_rate'],
                    "freight_tax_amount" => $freightData['freight_tax_amount'],
                     "payment_terms"                   => $payment_terms,
                    "due_date"                        => $due_date,
                    "due_days"                      => $due_days,
                );

                // Add optional ewaybill fields if they exist
                $optional_fields = [
                    'ewaybill_dispatch_from',
                    'ewaybill_mode_of_transportation',
                    'ewaybill_subtype',
                    'ewaybill_doctype',
                    'ewaybill_transporter_name',
                    'ewaybill_transporter_gstin',
                    'ewaybill_distance_of_transportation',
                    'ewaybill_transporter_doc_no',
                    'ewaybill_vehicle_no',
                    'ewaybill_vehicle_type'
                ];

                foreach ($optional_fields as $field) {
                    if (!empty($this->input->post($field))) {
                        $delivery_challan_data[$field] = $this->input->post($field);
                    }
                }

                $lori_date = $this->input->post('lori_date');
                if($lori_date != '') {
                    $delivery_challan_data['lori_date'] = date('Y-m-d',strtotime($lori_date));
                }

                $ewaybill_transporter_doc_date = $this->input->post('ewaybill_transporter_doc_date');
                if($ewaybill_transporter_doc_date != '') {
                    $delivery_challan_data['ewaybill_transporter_doc_date'] = date('Y-m-d',strtotime($ewaybill_transporter_doc_date));
                }

                // Begin transaction
                $this->db->trans_begin();

                if($id = $this->delivery_challan_model->add_delivery_challan_record($delivery_challan_data))
                {
                    $entered_delivery_challan = $this->delivery_challan_model->get_delivery_challan_single_record($id);
                    $customer = $this->customer_model->get_single_record($entered_delivery_challan->customer_id);

                    $log_data = array(
                        "user_id" => $this->session->userdata("user_id"),
                        "module" => "delivery_challan",
                        "entry_id" => $id,
                        "user_action" => 1,
                        "data" => json_encode((array)$entered_delivery_challan),
                        "description" => 'Delivery challan (Reference No. -' .$reference_no .') is added successfully.'
                    );
                    $this->log_data_model->add_record($log_data);

                    $delivery_challan_items = $this->input->post('delivery_challan_items');
                    $delivery_challan_items_array = explode("|", $this->input->post('delivery_challan_items'));

                    for ($i=0; $i < sizeof($delivery_challan_items_array); $i++) { 
                        $temp_delivery_challan_item = (array)json_decode($delivery_challan_items_array[$i]);
                        
                        // Skip if this is a freight item (we already processed it)
                        if (isset($temp_delivery_challan_item['freight_id']) && $temp_delivery_challan_item['freight_id'] === 'freight') {
                            continue;
                        }
                        
                        $temp_delivery_challan_item['delivery_challan_id'] = $id;

                        // Handle date fields
                        if(isset($temp_delivery_challan_item['expiry_date']) && 
                          ($temp_delivery_challan_item['expiry_date'] == '' || $temp_delivery_challan_item['expiry_date'] == '0000-00-00')) {
                            unset($temp_delivery_challan_item['expiry_date']);
                        } elseif(isset($temp_delivery_challan_item['expiry_date'])) {
                            $temp_delivery_challan_item['expiry_date'] = date('Y-m-d',strtotime($temp_delivery_challan_item['expiry_date']));
                        }

                        if(isset($temp_delivery_challan_item['mfg_date']) && 
                          ($temp_delivery_challan_item['mfg_date'] == '' || $temp_delivery_challan_item['mfg_date'] == '0000-00-00')) {
                            unset($temp_delivery_challan_item['mfg_date']);
                        } elseif(isset($temp_delivery_challan_item['mfg_date'])) {
                            $temp_delivery_challan_item['mfg_date'] = date('Y-m-d',strtotime($temp_delivery_challan_item['mfg_date']));
                        }

                        $this->delivery_challan_model->add_delivery_challan_item_record($temp_delivery_challan_item);
                    }

                    // Commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success', 'Delivery challan (Reference No. -' .$reference_no .') is added successfully.');
                    redirect('delivery_challan','refresh');
                }
                else
                {
                    // Rollback transaction
                    $this->db->trans_rollback();
                    
                    $this->session->set_flashdata('failure', 'Delivery challan (Reference No. -' .$reference_no .') is failed to add.');
                    redirect('delivery_challan','refresh');
                }
            }
        }
        else
        {
            $user_id = $this->session->userdata('user_id');
            $user = $this->ion_auth->user($user_id)->row()->email; 
            $customer = $this->utility_model->get_records_by_field('customer','email',$user,$row = true,$check_delete_status = false);

            if(delivery_challan_RESTRICTION == 'YES' && $customer != null)
            {
                $morning_start = strtotime('08:00');
                $morning_end = strtotime('10:30');
                $evening_start = strtotime('12:01');
                $evening_end = strtotime('15:00');

                $current_time = strtotime(date('H:i'));

                $allow_add = false;
                if (($current_time >= $morning_start && $current_time <= $morning_end) ||
                    ($current_time >= $evening_start && $current_time <= $evening_end)) {
                    $allow_add = true;
                }
                
                if (!$allow_add) {
                    $this->session->set_flashdata('failure', 'TODAY order '.date('H:i',$morning_start).' AM to '.date('H:i',$morning_end).' AM & NEXT DAY order '.date('H:i',$evening_start).' PM to '.date('H:i',$evening_end).' PM.');
                    redirect('delivery_challan','refresh');
                }
                else
                {
                    $data['countries'] = $this->utility_model->get_countries();
                    $data['customers'] = $this->customer_model->get_records();
                    $data['warehouses'] = $this->warehouse_model->get_records();
                    $data['suppliers'] = $this->supplier_model->get_records();
                    $data['company_setting'] = $this->company_settings_model->get_company_records();    
                    $data['states'] = $this->utility_model->get_states(101);
                    $data['promotions'] = $this->promotion_model->get_records();    
                    $this->load->view('delivery_challan/add',$data);
                }
            }
            else
            {    
                $data['countries'] = $this->utility_model->get_countries();
                $data['customers'] = $this->customer_model->get_records();
                $data['warehouses'] = $this->warehouse_model->get_records();
                $data['suppliers'] = $this->supplier_model->get_records();
                $data['company_setting'] = $this->company_settings_model->get_company_records();    
                $data['states'] = $this->utility_model->get_states(101);
                $data['promotions'] = $this->promotion_model->get_records(); 
                  $data['due_days_options']   = $this->db->where('status', 1)->get('due_days')->result();
                $this->load->view('delivery_challan/add',$data);
            }
        }
    }
}

public function edit($id = null)
{
    if(!$this->permission_model->has_permission('edit_delivery_challan'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $delivery_challan_id = $this->input->post('id');
            $old_delivery_challan = $this->delivery_challan_model->get_delivery_challan_single_record($delivery_challan_id);

            $this->form_validation->set_rules('invoice_date','Invoice Date','required');        
            $this->form_validation->set_rules('customer_id','Customer','required');
            $this->form_validation->set_rules('warehouse_id','Warehouse','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['warehouses'] = $this->warehouse_model->get_records();
                $data['customers'] = $this->customer_model->get_records();
                $data['company_setting'] = $this->company_settings_model->get_company_records();    
                $this->load->view('delivery_challan/add',$data);
            }
            else
            {
                // Process profit data
                $profit_data = json_decode($this->input->post('profit_data'), true);
                $total_purchase_cost = isset($profit_data['total_purchase_cost']) ? $profit_data['total_purchase_cost'] : 0;
                $additional_cost_amount = isset($profit_data['freight_amount']) ? $profit_data['freight_amount'] : 0;
                $total_selling_price = isset($profit_data['total_selling_price']) ? $profit_data['total_selling_price'] : 0;
                $gross_profit_loss = isset($profit_data['gross_profit_loss']) ? $profit_data['gross_profit_loss'] : 0;
                $profit_margin = isset($profit_data['profit_margin']) ? $profit_data['profit_margin'] : 0;
                $transport_type = isset($profit_data['transport_type']) ? $profit_data['transport_type'] : null;

                // Process freight data
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

                $invoice_date = date('Y-m-d', strtotime($this->input->post('invoice_date')));
                $warehouse_id = $this->input->post('warehouse_id');
                $customer_id = $this->input->post('customer_id');
                
                $selected_shipping_address_id = $this->input->post('shipping_address_select');
                $customer_shipping_country_id = $this->input->post('customer_shipping_country_id');
                $customer_shipping_state_id = $this->input->post('customer_shipping_state_id');
                $customer_shipping_city_id = $this->input->post('customer_shipping_city_id');
                $customer_shipping_address = $this->input->post('customer_shipping_address');
                $customer_shipping_pincode = $this->input->post('customer_shipping_pincode');                    

                $purchase_order_no = $this->input->post('purchase_order_no');
                
                $customer = $this->customer_model->get_single_record($customer_id);
                $customer_gstin = $customer->gstin;
                $rcm = $this->input->post('rcm');
                $total_taxable_value = $this->input->post('total_taxable_value');
                $tds = $this->input->post('tds');
                $total_discount = $this->input->post('total_discount');
                $total_tax = $this->input->post('total_tax');
                $total = $this->input->post('total');
                $internal_note = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $bank_detail = nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));

                $lori_no = $this->input->post('lori_no');
                $carrier = $this->input->post('carrier');
                $vehicle_no = $this->input->post('vehicle_no');
                $ewaybill_no = $this->input->post('ewaybill_no');
                $additional_case = $this->input->post('additional_case');

                $ewaybill_dispatch_from = $this->input->post('ewaybill_dispatch_from');
                $ewaybill_mode_of_transportation = $this->input->post('ewaybill_mode_of_transportation');
                $ewaybill_subtype = $this->input->post('ewaybill_subtype');
                $ewaybill_doctype = $this->input->post('ewaybill_doctype');
                $ewaybill_transporter_name = $this->input->post('ewaybill_transporter_name');
                $ewaybill_transporter_gstin = $this->input->post('ewaybill_transporter_gstin');
                $ewaybill_distance_of_transportation = $this->input->post('ewaybill_distance_of_transportation');
                $ewaybill_transporter_doc_no = $this->input->post('ewaybill_transporter_doc_no');
                $ewaybill_vehicle_no = $this->input->post('ewaybill_vehicle_no');
                $ewaybill_vehicle_type = $this->input->post('ewaybill_vehicle_type');
                $margin = $this->input->post('margin');
                $document = $this->input->post("document");
                $due_days = $this->input->post('due_days');
                $payment_terms = $this->input->post('payment_terms');
                
                if ($due_days) {
                    $due_date = date('Y-m-d', strtotime("+$due_days days", strtotime($invoice_date)));
                } else {
                    $due_date = NULL;
                }
                
                if (!is_array($document)) {
                    $document = array($document);
                }
                $documents = implode(',', $document);

                $delivery_challan_data = array(
                    "invoice_date" => $invoice_date,
                    "warehouse_id" => $warehouse_id,
                    "customer_id" => $customer_id,
                    "selected_shipping_address_id" => $selected_shipping_address_id,
                    "customer_shipping_country_id" => $customer_shipping_country_id,
                    "customer_shipping_state_id" => $customer_shipping_state_id,
                    "customer_shipping_city_id" => $customer_shipping_city_id,
                    "customer_shipping_address" => $customer_shipping_address,
                    "customer_shipping_pincode" => $customer_shipping_pincode,
                    "customer_gstin" => $customer_gstin,
                    "rcm" => $rcm,
                    "total_taxable_value" => $total_taxable_value,    
                    "tds" => $tds,    
                    "total_tax" => $total_tax,
                    "total_discount" => $total_discount,    
                    "total" => $total,
                    "internal_note" => $internal_note,
                    "external_note" => $external_note,
                    "bank_detail" => $bank_detail,
                    "terms_and_condition" => $terms_and_condition,
                    "lori_no" => $lori_no,
                    "carrier" => $carrier,
                    "vehicle_no" => $vehicle_no,
                    "ewaybill_no" => $ewaybill_no,
                    "additional_case" => $additional_case,
                    "document" => $documents,
                    "user_id" => $this->session->userdata('user_id'),
                    // Add new fields for profit/loss and freight
                    "additional_cost_type" => $this->input->post('additional_cost_type'),
                    "total_purchase_cost" => $total_purchase_cost,
                    "total_selling_price" => $total_selling_price,
                    "gross_profit_loss" => $gross_profit_loss,
                    "profit_margin" => $profit_margin,
                    "additional_cost_amount" => $additional_cost_amount,
                    "freight_selling_price" => $freightData['freight_selling_price'],
                    "freight_taxable_value" => $freightData['freight_taxable_value'],
                    "freight_sub_total" => $freightData['freight_sub_total'],
                    "freight_tax_rate" => $freightData['freight_tax_rate'],
                    "freight_tax_amount" => $freightData['freight_tax_amount'],
                    "payment_terms" => $payment_terms,
                    "due_date" => $due_date,
                    "due_days" => $due_days,
                );

                // Add optional ewaybill fields if they exist
                $optional_fields = [
                    'ewaybill_dispatch_from',
                    'ewaybill_mode_of_transportation',
                    'ewaybill_subtype',
                    'ewaybill_doctype',
                    'ewaybill_transporter_name',
                    'ewaybill_transporter_gstin',
                    'ewaybill_distance_of_transportation',
                    'ewaybill_transporter_doc_no',
                    'ewaybill_vehicle_no',
                    'ewaybill_vehicle_type'
                ];

                foreach ($optional_fields as $field) {
                    if (!empty($this->input->post($field))) {
                        $delivery_challan_data[$field] = $this->input->post($field);
                    }
                }

                $lori_date = $this->input->post('lori_date');
                if($lori_date != '') {
                    $delivery_challan_data['lori_date'] = date('Y-m-d',strtotime($lori_date));
                }

                $ewaybill_transporter_doc_date = $this->input->post('ewaybill_transporter_doc_date');
                if($ewaybill_transporter_doc_date != '') {
                    $delivery_challan_data['ewaybill_transporter_doc_date'] = date('Y-m-d',strtotime($ewaybill_transporter_doc_date));
                }

                // Begin transaction
                $this->db->trans_begin();

                if($this->delivery_challan_model->edit_delivery_challan_record($delivery_challan_data,$delivery_challan_id))
                {
                    $entered_delivery_challan = $this->delivery_challan_model->get_delivery_challan_single_record($delivery_challan_id);
                    $customer = $this->customer_model->get_single_record($entered_delivery_challan->customer_id);

                    $log_data = array(
                        "user_id" => $this->session->userdata("user_id"),
                        "module" => "delivery_challan",
                        "user_action" => 2,
                        "b_data" => json_encode((array)$old_delivery_challan),
                        "data" => json_encode((array)$entered_delivery_challan),
                        "description" => 'Delivery challan (Reference No. -' .$old_delivery_challan->reference_no .') is updated successfully.'
                    );
                    $this->log_data_model->add_record($log_data);
                    
                    // Remove existing delivery challan items
                    $this->delivery_challan_model->remove_delivery_challan_item_records($delivery_challan_id);

                    $delivery_challan_items = $this->input->post('delivery_challan_items');
                    $delivery_challan_items_array = explode("|", $this->input->post('delivery_challan_items'));

                    for ($i=0; $i < sizeof($delivery_challan_items_array); $i++) { 
                        $temp_delivery_challan_item = (array)json_decode($delivery_challan_items_array[$i]);
                        
                        // Skip if this is a freight item (we already processed it)
                        if (isset($temp_delivery_challan_item['freight_id']) && $temp_delivery_challan_item['freight_id'] === 'freight') {
                            continue;
                        }
                        
                        $temp_delivery_challan_item['delivery_challan_id'] = $delivery_challan_id;

                        // Handle purchase cost and supplier data
                        if(isset($temp_delivery_challan_item['purchase_cost'])) {
                            $temp_delivery_challan_item['purchase_cost'] = $temp_delivery_challan_item['purchase_cost'];
                        }
                        
                        if(isset($temp_delivery_challan_item['supplier_id'])) {
                            $temp_delivery_challan_item['supplier_id'] = $temp_delivery_challan_item['supplier_id'];
                        }

                        // Handle date fields
                        if(isset($temp_delivery_challan_item['expiry_date']) && 
                          ($temp_delivery_challan_item['expiry_date'] == '' || $temp_delivery_challan_item['expiry_date'] == '0000-00-00')) {
                            unset($temp_delivery_challan_item['expiry_date']);
                        } elseif(isset($temp_delivery_challan_item['expiry_date'])) {
                            $temp_delivery_challan_item['expiry_date'] = date('Y-m-d',strtotime($temp_delivery_challan_item['expiry_date']));
                        }

                        if(isset($temp_delivery_challan_item['mfg_date']) && 
                          ($temp_delivery_challan_item['mfg_date'] == '' || $temp_delivery_challan_item['mfg_date'] == '0000-00-00')) {
                            unset($temp_delivery_challan_item['mfg_date']);
                        } elseif(isset($temp_delivery_challan_item['mfg_date'])) {
                            $temp_delivery_challan_item['mfg_date'] = date('Y-m-d',strtotime($temp_delivery_challan_item['mfg_date']));
                        }

                        $this->delivery_challan_model->add_delivery_challan_item_record($temp_delivery_challan_item);
                    }

                    // Commit transaction
                    $this->db->trans_commit();

                    $this->session->set_flashdata('success', 'Delivery challan (Reference No. -' .$old_delivery_challan->reference_no .') is updated successfully.');
                    redirect('delivery_challan','refresh');
                }
                else
                {
                    // Rollback transaction
                    $this->db->trans_rollback();
                    
                    $this->session->set_flashdata('failure', 'Delivery challan (Reference No. -' .$old_delivery_challan->reference_no .') is failed to update.');
                    redirect('delivery_challan','refresh');
                }
            }
        }
        else
        {
            $id = base64_decode($id);

            if($id != null)
            {
                $data['delivery_challan'] = $this->delivery_challan_model->get_delivery_challan_single_record($id);
                $data['promotions'] = $this->promotion_model->get_records();    

                if($data['delivery_challan'] != null)
                {
                    $data['countries'] = $this->utility_model->get_countries();

                    if($data['delivery_challan']->customer_shipping_country_id != '')
                        $data['states'] = $this->utility_model->get_states($data['delivery_challan']->customer_shipping_country_id);
        
                    if($data['delivery_challan']->customer_shipping_state_id != '')
                        $data['cities'] = $this->utility_model->get_cities($data['delivery_challan']->customer_shipping_state_id);                       

                    $data['delivery_challan_items'] = $this->delivery_challan_model->get_delivery_challan_item_records($id);
                    
                    // Load vendor data for each product
                    $this->load->model('supplier_model');
                    foreach($data['delivery_challan_items'] as &$item) {
                        $item->product_vendors = $this->supplier_model->get_vendors_by_product($item->product_id);
                        // Get product details for image and code
                        $product_details = $this->product_model->get_single_record($item->product_id);
                        if($product_details) {
                            $item->product_image = $product_details->product_image;
                            $item->product_code = $product_details->product_code;
                        } else {
                            $item->product_image = 'default.png';
                            $item->product_code = 'N/A';
                        }
                    }
                    
                    $data['customers'] = $this->customer_model->get_records();
                    $data['customer_detail'] = $this->customer_model->get_single_record($data['delivery_challan']->customer_id);
                    $data['discounts'] = $this->discount_model->get_records();
                    $data['company_setting'] = $this->company_settings_model->get_company_records();    
                    $data['customer_shipping_addresses'] = $this->customer_model->get_customer_shipping_addresses($data['delivery_challan']->customer_id);
                    $data['warehouses'] = $this->warehouse_model->get_records();
                    $data['due_days_options'] = $this->db->where('status', 1)->get('due_days')->result();

                    $this->load->view('delivery_challan/edit',$data);
                }
                else
                {
                    $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                    redirect('delivery_challan','refresh');
                }
            
            }
            else
            {
                $this->session->set_flashdata('failure', 'You have tried to access broken URL.');
                redirect('delivery_challan','refresh');
            }
        }
    }
}

public function get_latest_purchase_cost() {
  $product_id = $this->input->post('product_id');
  $supplier_id = $this->input->post('supplier_id');
  
  $this->db->select('cost');
  $this->db->from('purchase_items');
  $this->db->where('product_id', $product_id);
  $this->db->where('supplier_id', $supplier_id);
  $this->db->order_by('id', 'DESC');
  $this->db->limit(1);
  $query = $this->db->get();
  
  if($query->num_rows() > 0) {
    $row = $query->row();
    echo json_encode(array('success' => true, 'cost' => $row->cost));
  } else {
    echo json_encode(array('success' => false));
  }
}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_delivery_challan'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id 					= $this->input->post('id');
			$delivery_challan 				= $this->delivery_challan_model->get_delivery_challan_single_record($id);
			$data = array('delete_status' => 1);
			if($this->delivery_challan_model->edit_delivery_challan_record($data,$id))
			{
				$customer 		= $this->customer_model->get_single_record($delivery_challan->customer_id);

				$this->session->set_flashdata('success', 'Delivery challan (Reference No. -'.$delivery_challan->reference_no.') is deleted successfully.');
				redirect('delivery_challan','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Delivery challan (Reference No. -'.$delivery_challan->reference_no.') is failed to delete.');
				redirect('delivery_challan','refresh');
			}	

			
			
		}
	}

	public function view($id = null)
	{
		if(!$this->permission_model->has_permission('edit_delivery_challan'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{

			if (!$this->input->is_ajax_request()) 
			{
				if($id != null)
				{
					$id 										= base64_decode($id);

					$data['delivery_challan'] 					= $this->delivery_challan_model->get_delivery_challan_single_record($id);

					if($data['delivery_challan'] != null)
					{
						$data['delivery_challan_items'] 		= $this->delivery_challan_model->get_delivery_challan_item_records($id);
						$data['customers'] 			= $this->customer_model->get_records();
						$data['customer_detail']= $this->customer_model->get_single_record($data['delivery_challan']->customer_id);
						$data['discounts']			= $this->discount_model->get_records();
						$data['company_setting']= $this->company_settings_model->get_company_records();	
                        $warehouse_id = $data['delivery_challan']->warehouse_id;
                        $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);
                        // ---- Shipping Country ----
$data['shipping_country'] = null;
if (!empty($data['delivery_challan']->customer_shipping_country_id)) {
    $data['shipping_country'] = $this->db
        ->get_where('countries', ['id' => $data['delivery_challan']->customer_shipping_country_id])
        ->row();
}

// ---- Shipping State ----
$data['shipping_state'] = null;
if (!empty($data['delivery_challan']->customer_shipping_state_id)) {
    $data['shipping_state'] = $this->db
        ->get_where('states', ['id' => $data['delivery_challan']->customer_shipping_state_id])
        ->row();
}

// ---- Shipping City ----
$data['shipping_city'] = null;
if (!empty($data['delivery_challan']->customer_shipping_city_id)) {
    $data['shipping_city'] = $this->db
        ->get_where('cities', ['id' => $data['delivery_challan']->customer_shipping_city_id])
        ->row();
}

						$this->load->view('delivery_challan/view',$data);
					}
					else
					{
						$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
						redirect('delivery_challan','refresh');
					}
				
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('delivery_challan','refresh');
				}
			}
			else
			{
				$response = array();

				$delivery_challan_view_data['delivery_challan'] = $this->delivery_challan_model->get_delivery_challan_single_record($id);
				$delivery_challan_view_data['customer_detail'] 	= $this->customer_model->get_single_record($delivery_challan_view_data['delivery_challan']->customer_id);	
			
				$response['delivery_challan_view']	 						= $this->load->view('delivery_challan/ajax/view',$delivery_challan_view_data,TRUE);
				echo json_encode($response);
			}
			
		}
	}

  public function pdf($id = null)
	{
		
		if($id != null)
		{	
			$id 									= base64_decode($id);
			$data['delivery_challan'] 				= $this->delivery_challan_model->get_delivery_challan_single_record($id);
			$data['delivery_challan_items'] 		= $this->delivery_challan_model->get_delivery_challan_item_records($id);
			$data['customer_detail']	= $this->customer_model->get_single_record($data['delivery_challan']->customer_id);
			$data['discounts']			= $this->discount_model->get_records();
			$data['company_setting']	= $this->company_settings_model->get_company_records();	
             $warehouse_id = $data['delivery_challan']->warehouse_id;
                        $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);
                        
            $data['shipping_country'] = null;
if (!empty($data['delivery_challan']->customer_shipping_country_id)) {
    $data['shipping_country'] = $this->db
        ->get_where('countries', ['id' => $data['delivery_challan']->customer_shipping_country_id])
        ->row();
}

// ---- Shipping State ----
$data['shipping_state'] = null;
if (!empty($data['delivery_challan']->customer_shipping_state_id)) {
    $data['shipping_state'] = $this->db
        ->get_where('states', ['id' => $data['delivery_challan']->customer_shipping_state_id])
        ->row();
}

// ---- Shipping City ----
$data['shipping_city'] = null;
if (!empty($data['delivery_challan']->customer_shipping_city_id)) {
    $data['shipping_city'] = $this->db
        ->get_where('cities', ['id' => $data['delivery_challan']->customer_shipping_city_id])
        ->row();
}
			$html = $this->load->view('delivery_challan/pdf',$data,true);

			// echo $html;
			// exit;
			
			$this->pdf->loadHtml($html);
			$this->pdf->render();
			$this->pdf->stream("DeliveryChallan-".$data['delivery_challan']->reference_no.".pdf", array("Attachment"=>1));
		}
		else
		{
			$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
			redirect('delivery_challan','refresh');
		}
	}

  function convert_from_quotation($quotation_id)
	{	
		 $quotation_id 		    = base64_decode($quotation_id);
		//$quotation_id 		= $this->input->post('quotation_id');

		 $delivery_challan = $this->delivery_challan_model->get_delivery_challan_single_record_by_quotation_id($quotation_id);

		if($delivery_challan == null)
		{
			$quotation 				= $this->quotation_model->get_quotation_single_record($quotation_id);
			$quotation_items 	    = $this->quotation_model->get_quotation_item_records($quotation_id);
			$customer 				= $this->customer_model->get_single_record($quotation->customer_id);

     // print_r($customer);
      // exit;
  

			$reference_no 				= $this->delivery_challan_model->get_lastest_sequence_number();

			$delivery_challan_data = array(
													"invoice_date"				=> date('Y-m-d'),
													"reference_no"				=> $reference_no,
													"quotation_id"				=> $quotation->id,
													"warehouse_id"	  	    	=> $quotation->warehouse_id,
													"customer_id"				=> $quotation->customer_id,

                                                  // "customer_shipping_country_id"	=> $customer->shipping_country_id,
                                                  // "customer_shipping_state_id"		=> $customer->shipping_state_id,
                                                  // "customer_shipping_city_id"		=> $customer->shipping_city_id,
                                                  // "customer_shipping_address"		=> $customer->shipping_address,
                                                  // "customer_shipping_pincode"	=> $customer->shipping_pincode,

													"customer_gstin"			=> $customer->gstin,
													"rcm"									=> $quotation->rcm,
													"total_taxable_value" => $quotation->total_taxable_value,	
													"tds" 								=> 0,	
													"total_tax"					=> $quotation->total_tax,
													"total_discount" 			=> $quotation->total_discount,	
													"total" 					=> $quotation->total,
													"internal_note"				=> $quotation->internal_note,
													"external_note" 			=> $quotation->external_note,
													"bank_detail" 				=> $quotation->bank_detail,
													"terms_and_condition"       => $quotation->terms_and_condition,
													"user_id" 						=> $this->session->userdata('user_id')
												);

			// being transaction
			$this->db->trans_begin();

			if($id = $this->delivery_challan_model->add_delivery_challan_record($delivery_challan_data))
			{
				$entered_delivery_challan 	= $this->delivery_challan_model->get_delivery_challan_single_record($id);
				$customer 			            = $this->customer_model->get_single_record($entered_delivery_challan->customer_id);

			

				$log_data = array(
									"user_id" 		=> $this->session->userdata("user_id"),
									"module"		=> "delivery_challan",
									"entry_id"		=> $id,
									"user_action"	=> 1,
									"data"			=> json_encode((array)$entered_delivery_challan),
									"description"   => 'Delivery challan (Reference No. -'  .$delivery_challan_data['reference_no'] .') is added successfully.'
								);

				$this->log_data_model->add_record($log_data);
				
				foreach ($quotation_items as $value) {
					
					$product = $this->product_model->get_single_record($value->product_id);
					$uom = $this->uom_model->get_single_record($product->uom_id);

					$temp_delivery_challan_item = array(
												        'product_name'     => $value->product_name,
											            'description'      => $value->description,
											            'quantity'         => $value->quantity,
											            'price'            => $value->price,
											            'selling_price'    => $value->price,
											            'cost'             => $value->cost,
											            'taxable_value'    => $value->taxable_value,
											            'discount_id'      => $value->discount_id,
											            'discount_type'    => $value->discount_type,
											            'discount_value'   => $value->discount_value,
											            'discount_amount'  => $value->discount_amount,
											            'uom_id'           => $uom->id,
											            'uom_name'         => $uom->name,
											            'uom_uom'          => $uom->uom,
											            'tax_id'           => $value->tax_id,
											            'tax_type'         => $value->tax_type,
											            'igst'             => $value->igst,
											            'igst_tax'         => $value->igst_tax,
											            'cgst'             => $value->cgst,
											            'cgst_tax'         => $value->cgst_tax,
											            'sgst'             => $value->sgst,
											            'sgst_tax'         => $value->sgst_tax,
											            'sub_total'        => $value->sub_total,
											            'delivery_challan_id' 	=> $id,
											            'warehouse_product_id'    => $value->warehouse_product_id,
											            'product_id'            => $value->product_id,
	 															);

													// echo '<pre>';
													// print_r($temp_delivery_challan_item);
													// exit;

					$this->delivery_challan_model->add_delivery_challan_item_record($temp_delivery_challan_item);
				}

				// commit transaction
				$this->db->trans_commit();

				$this->session->set_flashdata('success',  'Delivery challan (Reference No. -'  .$delivery_challan_data['reference_no'] .') is added successfully.');
				redirect('delivery_challan/view/'.base64_encode($id),'refresh');
			}
			else
			{
				// rollback transaction
				$this->db->trans_rollback();
				
				$this->session->set_flashdata('failure',  'Delivery challan (Reference No. -'  .$delivery_challan_data['reference_no'] .') is failed to add.');
				redirect('delivery_challan/view/'.base64_encode($id),'refresh');
			}	
		}
		else
		{
			$this->session->set_flashdata('success',  'Delivery challan (Reference No. -'  .$delivery_challan->reference_no.') is already exist');
			redirect('delivery_challan/view/'.base64_encode($delivery_challan->id),'refresh');
		}
	}

  public function get_delivery_challan_items()
	{
		$delivery_challan_ids 			= $this->input->post('delivery_challan_ids');
    $customer_id        = $this->input->post('customer_id');

    date_default_timezone_set('Asia/Kolkata');
		

    $lock_by       = $this->session->userdata('user_id');
    $lock_datetime = date('Y-m-d H:i:s');// Set the new lock_datetime

		

    $data = array(
                  "lock_by"       => $lock_by,
                  "lock_datetime" => $lock_datetime
    );
		
    foreach ($delivery_challan_ids as $delivery_challan_id) {
     $this->delivery_challan_model->edit_delivery_challan_record($data,$delivery_challan_id);
    }

    
    // $data['customer_detail'] = $this->customer_model->get_single_record($customer_id);
    // $data['company_setting'] = $this->company_settings_model->get_company_records();
    $data['discounts']       = $this->discount_model->get_valid_records();	
    $data['delivery_challan_items'] = $this->delivery_challan_model->get_delivery_challan_item_records($delivery_challan_ids,true);

    
    

		// $response['product_table_body'] = $this->load->view('delivery_challan/ajax/pack_slip_items',$data,TRUE);

		echo json_encode($data);
	}

	public function import_product()
  {
      if (!$this->permission_model->has_permission('import_product')) 
      {
          $this->load->view('errors/html/error_restricted');
      } 
      else 
      {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
          // Check if 'csvfile' file input exists
          if (isset($_FILES['csvfile'])) {
             
              if (is_uploaded_file($_FILES['csvfile']['tmp_name'])) {
                  $csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);
      
                  $ProductData = array();
                  $customer_id = $this->input->post('customer_id'); 
                  $warehouse_id = $this->input->post('warehouse_id'); 

                  $discount = $this->discount_model->get_valid_records();
      

                  $i = 0;
                  $no_of_row_in_csv = sizeof($csvData);
                  $not_matched_products = array();
      
                  if (!empty($csvData)) 
                  {   
                    //$no_of_row_in_csv = sizeof($csvData);
                    foreach ($csvData as $row) 
                    {
											$matchedProduct = $this->warehouse_products_model->get_records_by_pid_batch_no($row['PID'], $row['BatchNo']);

                     	// Print matched products (for debugging purposes)
                      if ($matchedProduct != null) 
                      {
                      
                        $i++;
												$warehouse_product = $this->warehouse_products_model->get_single_warehouse_product_record($matchedProduct->id);

                        $warehouseProductData[] = $warehouse_product;

												$uom 							= $this->utility_model->get_records_by_field('uom','uom',$row['UOM']);

												$warehouse_product->uom_id =  $uom->id;
												$data['batch_nos'] = $this->warehouse_products_model->get_batch_nos_by_product_id($matchedProduct->product_id);

											}
                      else
                      {
                        $not_matched_products[] = $row['ProductName'].'_'.$row['PID'].'_'.$row['BatchNo'];                          
                      }
                    }
                    
                    if($i > 0)
                    {
                      $responseData = array(
                          'code' => 1,
                          'ProductData' => $warehouseProductData,
                          'quantity'    => $row['Quantity'],
                         	'discount' 		=> $discount,
													'batch_nos'    => $data['batch_nos'],
                         	'message' 		=> ''
                      );

                      // print_r($responseData);


                      if($i != $no_of_row_in_csv)
                        $responseData['message'] = implode("<br/>",$not_matched_products).' <br/> products are not exist in system';
                    
                    }
                    else
                    {
                      $responseData = array(
                          'code' => 0,
                          'message' => 'No record(s) are found in system. Please check BatchNo and PID.'
                      );
                    }
                    

                    
                    // Send matched product IDs along with hold quantities and newPtd as a response
                    echo json_encode($responseData);
                  } 
                  else 
                  {

                    echo json_encode(array('code' => 0, 'message' => 'No record(s) are found in system. Please check BatchNo and PID.'));
                  }
              } 
              else 
              {
                echo json_encode(array('code' => 0, 'message' => 'Failed to upload CSV file.'));
              }
          } 
          else 
          {
            echo json_encode(array('code' => 0, 'message' => 'CSV file not present.'));
          }
        }
        else 
        {
          if ($this->input->is_ajax_request()) 
          {
            $data['product_categories'] = $this->product_category_model->get_records();
            $data['uoms'] = $this->uom_model->get_records();

            $response = array();
            $response['code'] = 1;
            $response['import_product_modal_body'] = $this->load->view('delivery_challan/ajax/import_product_modal_body', $data, TRUE);

            echo json_encode($response);
          } 
          else 
          {
            $this->load->view('errors/html/error_restricted');
          }
        }
      }
  }

	// public function export_products()
	// {
	// 		$this->db->select('p.pid as "PID",
	// 											 p.name as Name,
	// 											 p.cost as Cost,
	// 											 p.price as Price,
	// 											 p.selling_price as SellingPrice,
	// 											 "" as "QTY",
	// 											 (SELECT COALESCE(SUM(wp.quantity), 0) 
	// 												FROM warehouse_products wp 
	// 												WHERE wp.product_id = p.id) as "AvailableQuantity"
	// 											 '); 
	
	// 		$this->db->from('product p');
	// 		// $this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');
	// 		$this->db->order_by('p.pid' , 'asc');
	// 		$query = $this->db->get();		  
	
	// 		$this->load->dbutil();
	// 		$data = $this->dbutil->csv_from_result($query);
			
	// 		$this->load->helper('download');
	// 		force_download("AllProductWithPIDAndBatch.CSV", $data);
	// }

	public function send_email($delivery_challan_id = null)
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

				$delivery_challan_id 		= $this->input->post("delivery_challan_id");

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

					$delivery_challan												= $this->delivery_challan_model->get_delivery_challan_single_record($delivery_challan_id);
					$delivery_challan_items 								= $this->delivery_challan_model->get_delivery_challan_item_records($delivery_challan->id);
					$customer_detail						= $this->customer_model->get_single_record($delivery_challan->customer_id);
					$company_setting						= $this->company_settings_model->get_company_records();	
	
					$data['customers'] 					= $this->customer_model->get_records();
					$data['customer_detail']		= $customer_detail;
					$data['delivery_challan']								= $delivery_challan;
					$data['delivery_challan_items'] 				= $delivery_challan_items;
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

			
					$html = $this->load->view('delivery_challan/pdf', $data, true);

					$pdf_filename = "Delivery challan-".$data['delivery_challan']->reference_no.".pdf"; // Set your desired filename

          $cid = $company_setting->cid;
          $pdf_path = FCPATH . 'assets/documents/' . $cid . '/delivery_challan/' . $pdf_filename;

          // Create the target folder if it doesn't exist
          $targetDir = FCPATH . 'assets/documents/' . $cid . '/delivery_challan/';
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


						// $mail_data['Body'] 							= $this->load->view('delivery_challan/pdf',$data,true);
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
		
			$data['delivery_challan'] 	= $this->delivery_challan_model->get_delivery_challan_single_record($delivery_challan_id);
			$data['customer']		= $this->customer_model->get_single_record($data['delivery_challan']->customer_id);
			$data['company_setting']	= $this->company_settings_model->get_company_records();	

			$data['email_template'] 	= $this->email_template_model->get_record_by_module(EMAIL_TEMPLATE_MODULE_delivery_challan);

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
			
			$response['email_modal_body'] 	= $this->load->view('delivery_challan/ajax/email_modal_body',$data,TRUE);

			
	  	echo json_encode($response);
		}
	}



	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
     
    $list 		= $this->delivery_challan_model->get_datatables();
    
    // echo "<pre>";
    // print_r($list);
    // echo "</pre>";
    // die;

    $query = $this->db->last_query();
    $data 		= array();
    $no 			= $_POST['start'];
   
    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
	      $table_body   = '';

				// if($this->permission_model->has_permission('email_delivery_challan'))
				// {

				// 	$table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('send_email').'" class="btn btn-success btn-sm text-white email-modal" data-delivery_challan_id="'.$item->id.'">
				// 									<i class="fas fa-at"></i>
				// 								</a>';
				// }
 

				//View Button
			  if($this->permission_model->has_permission('view_delivery_challan') || $this->permission_model->has_permission('view_all_delivery_challan'))
				{	
					$table_body .= ' 
                            <a href="'.base_url('delivery_challan/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="View Delivery challan">
                              <i class="fas fa-eye"></i>
                            </a>      
                          ';
				}


				// Edit Button        
       	if($this->permission_model->has_permission('edit_delivery_challan') && $item->sale_id === NULL)
				{	
            $table_body .= ' 
          										<a href="'.base_url('delivery_challan/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs"  data-tt="tooltip" title="Edit Delivery challan">
                                <i class="fas fa-edit"></i>
                              </a>
                            ';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_delivery_challan') && $item->sale_id === NULL)
				{	
          
            $table_body .= '
		                        <a href="#"  data-toggle="modal" data-target="#delete_delivery_challan" data-tt="tooltip" title="Delete Delivery challan" class="btn btn-danger btn-xs delete_delivery_challan" data-delivery_challan_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i> 
		                        </a>
													';
				}

				//Reference No
        $reference_no_html = '<a href="'.base_url('delivery_challan/view/'.base64_encode($item->id)).'" data-tt="tooltip" title="'.$this->lang->line('delivery_challan_view').'">'.$item->reference_no.'</a>';

         //Customer Name
        $customer_name_html = '<a href="'.base_url('customer/view/'.base64_encode($item->customer_id)).'" data-tt="tooltip" title="'.$this->lang->line('delivery_challan_view_customer_detail').'">'.$item->customer_name.'</a>';
                      
				/* End Action column buttons*/

        // $sale = $this->sale_model->get_sale_single_record_by_delivery_challan_id($item->id);

        $checkbox_attributes = $sale ? 'disabled readonly' : '';
        
        $select_html = '<input type="checkbox" class="single_delivery_challan" data-delivery_challan_id="'.$item->id.'" '.$checkbox_attributes.'>';
        
        
			

	        $row = array();
	       
			  $row[] = $select_html;
		      $row[] = $reference_no_html;
		      $row[] = date('d-m-Y', strtotime($item->invoice_date));
		      $row[] = $customer_name_html;
		      //$row[] = number_format_i($item->total_discount);
		      $row[] = number_format_i($item->total_taxable_value);
		      //$row[] = number_format_i($item->tds);
		      $row[] = number_format_i($item->total_tax);
		      $row[] = number_format_i($item->total);
		      

	        $row[] = $table_header.$table_body.$table_footer;    
					$data[] = $row;
				
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->delivery_challan_model->count_all(),
                    "recordsFiltered" => $this->delivery_challan_model->count_filtered(),
                    "data" 						=> $data,
                    "query"           => $query
            			);
   
    echo json_encode($output);
  }	

  public function delivery_challan_delete_confirmation()
  {
  	$delivery_challan_id 				= $this->input->post('delivery_challan_id');
  	$data['delivery_challan'] 	= $this->delivery_challan_model->get_delivery_challan_single_record($delivery_challan_id);

  	$response 					= array();
  	$response['delivery_challan_delete_modal_body'] 	= $this->load->view('delivery_challan/ajax/delivery_challan_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	
	/************************************** End Dynamic Datatable function ***************************************/

	public function upload_documents() 
	{
    if (isset($_FILES["upload_document"])) 
		{
			$data['company_setting'] 	= $this->company_settings_model->get_company_records();

			$cid = $data['company_setting']->cid; 

			$targetDir = "./assets/documents/$cid/delivery_challan/";

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
