<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Qr_sale extends CI_Controller {

	private $pdf;
		public function __construct()
	{
		parent::__construct();
		$this->pdf = new Dompdf();
		
	}
	
public function view_qr_sale($id = null)
{
    // Decode the ID from base64
    $id = base64_decode($id);

    // Fetch the sale data
    $data['sale'] = $this->sale_model->get_sale_single_record($id);
    $data['proforma_invoice'] = $this->proforma_invoice_model->get_proforma_invoice_records_by_sale_id($data['sale']->id);

    // Fetch related data
    $data['sale_items'] = $this->sale_model->get_sale_item_records($id);
    $data['customers'] = $this->customer_model->get_records();
    $data['customer_detail'] = $this->customer_model->get_single_record($data['sale']->customer_id);
    $data['discounts'] = $this->discount_model->get_records();
    $data['company_setting'] = $this->company_settings_model->get_company_records();
    $warehouse_id = $data['sale']->warehouse_id;
    $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);

    // Load the invoice view directly without login check
    $this->load->view('sale/qr_view', $data);
}

public function view_box($encoded_id)
{
    $decoded = base64_decode($encoded_id);
    list($sale_id, $random_no) = explode('_', $decoded);
    
    $sale = $this->sale_model->get_sale_single_record($sale_id);
    $box_data = json_decode($sale->box_qr_data, true);
    
    $current_box = null;
    foreach($box_data as $box) {
        if($box['random_no'] == $random_no) {
            $current_box = $box;
            break;
        }
    }
    
    if(!$current_box) {
        show_404();
    }
    
    $data = [
        'sale' => $sale,
        'box' => $current_box
    ];
    
    $this->load->view('sale/box_details', $data);
}
}
	