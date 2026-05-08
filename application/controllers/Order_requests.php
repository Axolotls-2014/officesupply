<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Order_requests extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('company_settings_model');
    }

    public function update_status()
    {
        $order_id = $this->input->post('order_id');
        $action = $this->input->post('action');
    
        $status_map = [
            "approve" => "Shipping",
            "reject" => "Cancelled",
            "out_for_delivery" => "Out For Delivery",
            "delivered" => "Delivered"
        ];
    
        if (!isset($status_map[$action])) {
            echo json_encode(["error" => "Invalid action"]);
            return;
        }
    
        $new_status = $status_map[$action];
    
        if (!$order_id || !$new_status) {
            echo json_encode(["error" => "Missing order ID or status"]);
            return;
        }
    
        $order_exists = $this->db->get_where('order_requests', ['id' => $order_id])->row();
        if (!$order_exists) {
            echo json_encode(["error" => "Order not found"]);
            return;
        }
    
        $this->db->where('order_id', $order_id);
        $update = $this->db->update('order_requests', ['status' => $new_status]);
    
        if ($update) {
            echo json_encode(["success" => true, "new_status" => $new_status]);
        } else {
            echo json_encode(["error" => "Database update failed", "db_error" => $this->db->error()]);
        }
    }

    public function invoice($order_id)
    {
        $data['company_setting'] = $this->company_settings_model->get_company_records();
        $data['orders'] = $this->db->where('order_id', $order_id)->get('order_requests')->result_array();
        $this->load->view('purchase/invoice', $data); 
    }
    
    public function download_pdf($id) 
    {   
        $id = base64_decode($id);
        $this->load->library('pdf');
        $sale = $this->db->get_where('sale_requests', ['id' => $id])->row();
    
        if (!$sale) {
            show_404();
        }
    
        $data['sale'] = $sale;
        $data['sale_items'] = $this->db->get_where('sale_request_items', ['sale_id' => $sale->id])->result();
    
    if ($sale->level_id == 0) {
        $company = $this->db->get_where('clients_company', ['id' => $sale->company_id])->row();
        $data['customer'] = (object)[
            
            'company_name'     => $company->company_name ?? '',
            'gstin'            => $company->gstin ?? 'N/A',
            'billing_details'  => $company->address ?? 'N/A',
            'shipping_details' => $company->shipping_address ?? 'N/A',
        ];
    } else {
        $branch = $this->db->get_where('clients_branch', ['id' => $sale->branch_id])->row();
        $company = $this->db->get_where('clients_company', ['id' => $sale->company_id])->row();
        $user   = $this->db->get_where('users', ['id' => $sale->added_by])->row();
        $data['customer'] = (object)[
            'first_name'       => $user->first_name ?? '',
            'last_name'        => $user->last_name ?? '',
            'phone'            => $user->phone ?? 'N/A',
            'company_name'     => ($company->company_name ?? ''),
            'company_gst'      => $company->gstin ?? 'N/A',
            'company_add'      => $company->address ?? 'N/A',
            'gstin'            => $branch->gstin ?? 'N/A',
            'address'          => $user->address ?? 'N/A',
            'branch'           => $branch->branch_name  ?? 'N/A',
            'billing_details'  => $branch->billing_details ?? 'N/A',
            'shipping_details' => $branch->shipping_details ?? 'N/A',
        ];
    }
        $data['company_setting'] = $this->company_settings_model->get_company_records();
    
        $html = $this->load->view('purchase_requests/tax_invoice_pdf', $data, true);
    
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
    
        $this->pdf->stream("purchase_request_{$sale->reference_no}.pdf", ["Attachment" => true]);
    }
    
    public function download_pdf1($id)
    {
        $id = base64_decode($id);
    
        $this->load->library('pdf');
    
        $sale = $this->db->get_where('sale_requests', ['id' => $id])->row();
    
        if (!$sale) {
            show_404();
        }
    
        $data['sale'] = $sale;
        $data['sale_items'] = $this->db->get_where('sale_request_items', ['sale_id' => $sale->id])->result();
    
    if ($sale->level_id == 0) {

    $company = $this->db->get_where('clients_company', ['id' => $sale->company_id])->row();
    $user = $this->db->get_where('users', ['id' => $sale->added_by])->row();

    $data['customer'] = (object)[
        'first_name'       => $user->first_name ?? '',
        'last_name'        => $user->last_name ?? '',
        'company_name'     => $company->company_name ?? '',
        'gstin'            => $company->gstin ?? 'N/A',
        'billing_details'  => $company->address ?? 'N/A',
        'shipping_details' => $company->shipping_address ?? 'N/A',
    ];
}
 else {
        $branch = $this->db->get_where('clients_branch', ['id' => $sale->branch_id])->row();
        $company = $this->db->get_where('clients_company', ['id' => $sale->company_id])->row();
        $user   = $this->db->get_where('users', ['id' => $sale->added_by])->row();
        $data['customer'] = (object)[
            'first_name'       => $user->first_name ?? '',
            'last_name'        => $user->last_name ?? '',
            'phone'            => $user->phone ?? 'N/A',
            'company_name'     => ($company->company_name ?? ''),
            'company_gst'      => $company->gstin ?? 'N/A',
            'company_add'      => $company->address ?? 'N/A',
            'gstin'            => $branch->gstin ?? 'N/A',
            'address'          => $user->address ?? 'N/A',
            'branch'           => $branch->branch_name  ?? 'N/A',
            'billing_details'  => $branch->billing_details ?? 'N/A',
            'shipping_details' => $branch->shipping_details ?? 'N/A',
        ];
    }
        $data['company_setting'] = $this->company_settings_model->get_company_records();
    
        // Load the HTML content from the PDF-specific view
        $html = $this->load->view('purchase_requests/invoice_pdf', $data, true);
    
        // Generate PDF
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
    
        $this->pdf->stream("purchase_request_{$sale->reference_no}.pdf", ["Attachment" => true]);
    }

}
