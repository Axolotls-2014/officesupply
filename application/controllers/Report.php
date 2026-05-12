<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Report extends MY_Controller 
{
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
		$data['sales'] = $this->sale_model->get_sale_records();
		$this->load->view('report/sale',$data);
	}

	// start Customer payment due report
	public function customer_payment_due()
  {
        $data['customers'] 			=	$this->customer_model->get_records();
		$this->load->view('report/customer_payment_due',$data);
  }

	function view_sale_list_by_customer_id()
	{
			$data['customer_id'] 	= $this->input->post('customer_id');
			$data['sales'] 				= $this->sale_model->get_sale_records_by_customer_id($data['customer_id']);
      $data['customer']     = $this->customer_model->get_single_record($data['customer_id']);
		
			$response = array();
			$response['sale_list_view'] = $this->load->view('report/ajax/customer_table_html',$data,TRUE);
			echo json_encode($response);
	}
	// end Customer payment due report

	// start Supplier payment due report
	public function supplier_payment_due()
	{
		$data['suppliers'] 			=	$this->supplier_model->get_records();
		$this->load->view('report/supplier_payment_due',$data);
	}

	function view_purchase_list_by_supplier_id()
	{
		$data['supplier_id'] 	      = $this->input->post('supplier_id');
		$data['purchases'] 				  = $this->purchase_model->get_purchase_records_by_supplier_id($data['supplier_id']);
		$data['supplier']           = $this->supplier_model->get_single_record($data['supplier_id']);
		$response                   = array();
		$response['purchase_list_view'] = $this->load->view('report/ajax/supplier_table_html',$data,TRUE);
		echo json_encode($response);
	}
	public function stock_movement_summary()
{
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
        // Clear any previous output
        ob_clean();
        
        // Set JSON header
        $this->output->set_content_type('application/json');
        
        $from_date = ($this->input->post('from_date') == '') ? null : date('Y-m-d', strtotime($this->input->post('from_date')));
        $to_date = ($this->input->post('to_date') == '') ? null : date('Y-m-d', strtotime($this->input->post('to_date')));
        $product_id = $this->input->post('product_id');
        $warehouse_id = $this->input->post('warehouse_id');
        $action_type = $this->input->post('action_type');
        
        // Get movement data
        $movements = $this->report_model->get_stock_movement_summary($from_date, $to_date, $product_id, $warehouse_id);
        
        // Get opening balances
        $opening_balances = $this->report_model->get_opening_stock_balance($from_date, $product_id, $warehouse_id);
        
        // Get alert quantities
        $alert_qty = $this->report_model->get_alert_qty($product_id);
        
        // Group opening balances by product
        $opening_map = [];
        foreach($opening_balances as $ob) {
            $key = $ob->product_id;
            $opening_map[$key] = $ob;
        }
        
        // Process data to include opening and alert quantities
        $report_data = [];
        $current_product = null;
        $opening_qty = 0;
        $opening_value = 0;
        
        foreach($movements as $row) {
            if($current_product != $row->product_name) {
                if(isset($opening_map[$row->product_id])) {
                    $opening_qty = $opening_map[$row->product_id]->opening_qty;
                    $opening_value = $opening_map[$row->product_id]->opening_value;
                } else {
                    $opening_qty = 0;
                    $opening_value = 0;
                }
                $current_product = $row->product_name;
            }
            
            $row->opening_qty = $opening_qty;
            $row->opening_value = $opening_value;
            $row->alert_qty = isset($alert_qty[$row->product_id]) ? $alert_qty[$row->product_id] : 0;
            
            $opening_qty = $row->closing_qty;
            $opening_value = $row->closing_value;
            
            $report_data[] = $row;
        }
        
        // Calculate totals
        $totals = [
            'total_in_qty' => array_sum(array_column($movements, 'in_qty')),
            'total_out_qty' => array_sum(array_column($movements, 'out_qty')),
            'total_purchase_amt' => array_sum(array_column($movements, 'purchase_amount')),
            'total_sale_amt' => array_sum(array_column($movements, 'sale_amount')),
        ];
        
        if($action_type == 'export') {
            $this->export_stock_movement_csv($report_data, $totals, $from_date, $to_date);
            return;
        } else if($action_type == 'pdf') {
            $this->export_stock_movement_pdf($report_data, $totals, $from_date, $to_date);
            return;
        } else {
            $data['movements'] = $report_data;
            $data['totals'] = $totals;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            
            // Generate HTML
            $html = $this->load->view('report/ajax/stock_movement_summary', $data, true);
            
            // Send JSON response - make sure this is the ONLY output
            $response = array('html' => $html);
            echo json_encode($response);
            return;
        }
    }
    else
    {
        $data['products'] = $this->product_core_model->get_records();
        $data['warehouses'] = $this->warehouse_model->get_records();
        $this->load->view('report/stock_movement_summary', $data);
    }
}
private function export_stock_movement_csv($movements, $totals, $from_date, $to_date)
{
    $file_name = 'Stock_Movement_Report_' . date("Y-m-d H-i-s") . '.csv';
    
    $csv_content = "Date,Transaction Type,Product Name,Branch,Opening Qty,Alert Qty,In Qty,Purchase Amount,Out Qty,Sale Amount,Closing Stock,Closing Value\n";
    
    foreach($movements as $row) {
        $csv_content .= date('d-m-Y', strtotime($row->transaction_date)) . ",";
        $csv_content .= $row->transaction_type . ",";
        $csv_content .= $row->product_name . ",";
        $csv_content .= ($row->branch_name ?? '-') . ",";
        $csv_content .= number_format($row->opening_qty, 2) . ",";
        $csv_content .= number_format($row->alert_qty, 2) . ",";
        $csv_content .= number_format($row->in_qty, 2) . ",";
        $csv_content .= number_format($row->purchase_amount, 2) . ",";
        $csv_content .= number_format($row->out_qty, 2) . ",";
        $csv_content .= number_format($row->sale_amount, 2) . ",";
        $csv_content .= number_format($row->closing_qty, 2) . ",";
        $csv_content .= number_format($row->closing_value, 2) . "\n";
    }
    
    // Add totals row
    $csv_content .= "\nTOTALS,,,,,,," . number_format($totals['total_in_qty'], 2) . ",";
    $csv_content .= number_format($totals['total_purchase_amt'], 2) . ",";
    $csv_content .= number_format($totals['total_out_qty'], 2) . ",";
    $csv_content .= number_format($totals['total_sale_amt'], 2) . ",,\n";
    
    $this->load->helper('download');
    force_download($file_name, $csv_content);
    exit();
}

private function export_stock_movement_pdf($movements, $totals, $from_date, $to_date)
{
    $file_name = 'Stock_Movement_Report_' . date("Y-m-d H-i-s") . '.pdf';
    
    $data['movements'] = $movements;
    $data['totals'] = $totals;
    $data['from_date'] = $from_date;
    $data['to_date'] = $to_date;
    $data['company_setting'] = $this->company_settings_model->get_company_records();
    
    $html = $this->load->view('report/pdf/stock_movement_pdf', $data, true);
    
    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'landscape');
    $this->pdf->render();
    $this->pdf->stream($file_name, array("Attachment" => 1));
    exit();
}
	public function stock_value()
  {
    $data['products'] 			=	$this->report_model->stock_value_report();
		$this->load->view('report/stock_value',$data);
  }

/*	public function closing_stock()
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
		{

      $data['from_date']   = $this->input->post('from_date');
      $data['to_date']     = $this->input->post('to_date');
      // $data['pid']		     = $this->input->post('pid');
      $data['products']    = $this->product_core_model->get_records();

      // echo '<pre>';
      // print_r($data);
      // exit;
      
      $this->load->view('report/closing_stock',$data);
    }
    else
    {
      $data['from_date']   = null;
      $data['to_date']     = Date('Y-m-d');
      $data['products']    = null;
      // $data['pid']		     = null;
      $this->load->view('report/closing_stock',$data);
      
    }
    
  }
*/
// public function closing_stock()
// {
//     if($this->input->server('REQUEST_METHOD') === 'POST')
//     {
//         $from_date = $this->input->post('from_date');
//         $to_date = $this->input->post('to_date');
        
//         // Convert dates to proper format
//         $from_date_formatted = ($from_date != '') ? date('Y-m-d', strtotime($from_date)) : null;
//         $to_date_formatted = ($to_date != '') ? date('Y-m-d', strtotime($to_date)) : date('Y-m-d');
        
//         // Get all products
//         $products = $this->product_core_model->get_records();
        
//         // Process all products data in controller
//         $report_data = array();
//         $total_opening_quantity = 0;
//         $total_opening_cost = 0;
//         $total_closing_quantity = 0;
//         $total_closing_cost = 0;
//         $total_pur = 0;
//         $total_pur_vl = 0;
//         $total_sale = 0;
//         $total_sale_vl = 0;
//         $total_stock_in = 0;
//         $total_stock_in_vl = 0;
//         $total_stock_out = 0;
//         $total_stock_out_vl = 0;
        
//         if ($products != null && count($products) > 0) {
//             $counter = 1;
            
//             foreach ($products as $product) {
//                 // Get from_date data if provided
//                 $from_data = null;
//                 if ($from_date_formatted != null) {
//                     $from_data = $this->report_model->closing_stock_report($product->pid, $from_date_formatted);
//                 }
                
//                 // Get to_date data
//                 $to_data = $this->report_model->closing_stock_report($product->pid, $to_date_formatted);
                
//                 // Calculate opening stock
//                 $opening_stock = 0;
//                 $opening_stock_cost_value = 0;
//                 if ($from_data != null) {
//                     $opening_stock = (
//                         ($from_data['purchase_delivery_items'] ?? 0)
//                         + ($from_data['sales_return_delivery_items'] ?? 0)
//                         + ($from_data['stock_in'] ?? 0)
//                     ) - (
//                         ($from_data['sale_items'] ?? 0)
//                         + ($from_data['purchase_return_delivery_items'] ?? 0)
//                         + ($from_data['stock_out'] ?? 0)
//                     );
                    
//                     $opening_stock_cost_value = (
//                         ($from_data['purchase_delivery_items_cost_value'] ?? 0)
//                         + ($from_data['sales_return_delivery_items_cost_value'] ?? 0)
//                         + ($from_data['stock_in_cost_value'] ?? 0)
//                     ) - (
//                         ($from_data['sale_items_cost_value'] ?? 0)
//                         + ($from_data['purchase_return_delivery_items_cost_value'] ?? 0)
//                         + ($from_data['stock_out_cost_value'] ?? 0)
//                     );
                    
//                     $total_opening_quantity += $opening_stock;
//                     $total_opening_cost += $opening_stock_cost_value;
//                 }
                
//                 // Calculate closing stock
//                 $closing_stock = 0;
//                 $closing_stock_cost_value = 0;
//                 if ($to_data != null) {
//                     $closing_stock = (
//                         ($to_data['purchase_delivery_items'] ?? 0)
//                         + ($to_data['sales_return_delivery_items'] ?? 0)
//                         + ($to_data['stock_in'] ?? 0)
//                     ) - (
//                         ($to_data['sale_items'] ?? 0)
//                         + ($to_data['purchase_return_delivery_items'] ?? 0)
//                         + ($to_data['stock_out'] ?? 0)
//                     );
                    
//                     $closing_stock_cost_value = (
//                         ($to_data['purchase_delivery_items_cost_value'] ?? 0)
//                         + ($to_data['sales_return_delivery_items_cost_value'] ?? 0)
//                         + ($to_data['stock_in_cost_value'] ?? 0)
//                     ) - (
//                         ($to_data['sale_items_cost_value'] ?? 0)
//                         + ($to_data['purchase_return_delivery_items_cost_value'] ?? 0)
//                         + ($to_data['stock_out_cost_value'] ?? 0)
//                     );
                    
//                     $total_closing_quantity += $closing_stock;
//                     $total_closing_cost += $closing_stock_cost_value;
//                 }
                
//                 // Calculate period transactions
//                 $pur = 0;
//                 $pur_vl = 0;
//                 $sale = 0;
//                 $sale_vl = 0;
//                 $stock_in = 0;
//                 $stock_in_vl = 0;
//                 $stock_out = 0;
//                 $stock_out_vl = 0;
                
//                 if($from_data != null && $to_data != null) {
//                     $pur = ($to_data['purchase_delivery_items'] ?? 0) - ($from_data['purchase_delivery_items'] ?? 0);
//                     $pur_vl = ($to_data['purchase_delivery_items_value'] ?? 0) - ($from_data['purchase_delivery_items_value'] ?? 0);
//                     $sale = ($to_data['sale_items'] ?? 0) - ($from_data['sale_items'] ?? 0);
//                     $sale_vl = ($to_data['sale_items_value'] ?? 0) - ($from_data['sale_items_value'] ?? 0);
//                     $stock_in = ($to_data['stock_in'] ?? 0) - ($from_data['stock_in'] ?? 0);
//                     $stock_in_vl = ($to_data['stock_in_value'] ?? 0) - ($from_data['stock_in_value'] ?? 0);
//                     $stock_out = ($to_data['stock_out'] ?? 0) - ($from_data['stock_out'] ?? 0);
//                     $stock_out_vl = ($to_data['stock_out_value'] ?? 0) - ($from_data['stock_out_value'] ?? 0);
//                 } elseif($to_data != null) {
//                     $pur = $to_data['purchase_delivery_items'] ?? 0;
//                     $pur_vl = $to_data['purchase_delivery_items_value'] ?? 0;
//                     $sale = $to_data['sale_items'] ?? 0;
//                     $sale_vl = $to_data['sale_items_value'] ?? 0;
//                     $stock_in = $to_data['stock_in'] ?? 0;
//                     $stock_in_vl = $to_data['stock_in_value'] ?? 0;
//                     $stock_out = $to_data['stock_out'] ?? 0;
//                     $stock_out_vl = $to_data['stock_out_value'] ?? 0;
//                 }
                
//                 $total_pur += $pur;
//                 $total_pur_vl += $pur_vl;
//                 $total_sale += $sale;
//                 $total_sale_vl += $sale_vl;
//                 $total_stock_in += $stock_in;
//                 $total_stock_in_vl += $stock_in_vl;
//                 $total_stock_out += $stock_out;
//                 $total_stock_out_vl += $stock_out_vl;
                
//                 // Store product data for view
//                 $report_data[] = array(
//                     'sr_no' => $counter++,
//                     'product_category_name' => $product->product_category_name ?? '',
//                     'description' => $product->description ?? '',
//                     'name' => $product->name ?? '',
//                     'hsn' => $product->hsn ?? '',
//                     'uom_name' => $product->uom_name ?? '',
//                     'pid' => $product->pid,
//                     'pur' => $pur,
//                     'pur_vl' => $pur_vl,
//                     'sale' => $sale,
//                     'sale_vl' => $sale_vl,
//                     'stock_in' => $stock_in,
//                     'stock_in_vl' => $stock_in_vl,
//                     'stock_out' => $stock_out,
//                     'stock_out_vl' => $stock_out_vl,
//                     'closing_stock' => $closing_stock,
//                     'closing_stock_cost_value' => $closing_stock_cost_value
//                 );
//             }
//         }
        
//         $data = array(
//             'from_date' => $from_date,
//             'to_date' => $to_date,
//             'products' => $products,
//             'report_data' => $report_data,
//             'total_opening_quantity' => $total_opening_quantity,
//             'total_opening_cost' => $total_opening_cost,
//             'total_closing_quantity' => $total_closing_quantity,
//             'total_closing_cost' => $total_closing_cost,
//             'total_pur' => $total_pur,
//             'total_pur_vl' => $total_pur_vl,
//             'total_sale' => $total_sale,
//             'total_sale_vl' => $total_sale_vl,
//             'total_stock_in' => $total_stock_in,
//             'total_stock_in_vl' => $total_stock_in_vl,
//             'total_stock_out' => $total_stock_out,
//             'total_stock_out_vl' => $total_stock_out_vl
//         );
        
//         $this->load->view('report/closing_stock', $data);
//     }
//     else
//     {
//         $data['from_date'] = null;
//         $data['to_date'] = date('Y-m-d');
//         $data['products'] = $this->product_core_model->get_records();
//         $data['report_data'] = array();
//         $data['total_opening_quantity'] = 0;
//         $data['total_opening_cost'] = 0;
//         $data['total_closing_quantity'] = 0;
//         $data['total_closing_cost'] = 0;
//         $data['total_pur'] = 0;
//         $data['total_pur_vl'] = 0;
//         $data['total_sale'] = 0;
//         $data['total_sale_vl'] = 0;
//         $data['total_stock_in'] = 0;
//         $data['total_stock_in_vl'] = 0;
//         $data['total_stock_out'] = 0;
//         $data['total_stock_out_vl'] = 0;
        
//         $this->load->view('report/closing_stock', $data);
//     }
// }
	/*public function closing_stock()
{
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
        $data['from_date']   = $this->input->post('from_date');
        $data['to_date']     = $this->input->post('to_date');
        $data['products']    = $this->product_core_model->get_records();
        
        $this->load->view('report/closing_stock', $data);
    }
    else
    {
        $data['from_date']   = null;
        $data['to_date']     = Date('Y-m-d');
        $data['products']    = $this->product_core_model->get_records();
        
        $this->load->view('report/closing_stock', $data);
    }
}*/

/*	public function inventory_product_added()
  {
    if($this->input->server('REQUEST_METHOD') === 'POST')
		{
      // $data['expiry_date']   = $this->input->post('expiry_date');
      $data['from_date']   = $this->input->post('from_date');
      $data['to_date']     = $this->input->post('to_date');
      $data['products']    = $this->product_core_model->get_records();

      $this->load->view('report/inventory_product_added',$data);
    }
    else
    {
      // $data['expiry_date']   = null;
      $data['from_date']   = null;
      $data['to_date']     = Date('Y-m-d');
      $data['products']    = $this->product_core_model->get_records();
    
      $this->load->view('report/inventory_product_added',$data);
      
    }
    
  }*/
  
//   public function closing_stock()
// {
//     $from_date = $this->input->post('from_date');
//     $to_date = $this->input->post('to_date');
//     $pid = $this->input->post('pid');
//     $warehouse_id = $this->input->post('warehouse_id');

//     // Default dates if empty
//     $to_date_db = ($to_date) ? date('Y-m-d', strtotime($to_date)) : date('Y-m-d');
//     $from_date_db = ($from_date) ? date('Y-m-d', strtotime($from_date)) : date('Y-m-d', strtotime('-1 month'));

//     // Fetch movements
//     $report_data = $this->report_model->get_stock_movements($from_date_db, $to_date_db, $pid, $warehouse_id);

//     // Calculate running totals for the table
//     foreach ($report_data as $row) {
//         // Here we assume Opening is 0 for the start of the selected period 
//         // to keep the logic simple and prevent crashes.
//         $row->opening_qty = 0; 
//         $row->closing_stock = $row->in_qty - $row->out_qty;
//         $row->closing_value = $row->closing_stock * $row->cost;
//     }

//     $data = array(
//         'from_date' => $from_date,
//         'to_date' => $to_date,
//         'report_data' => $report_data,
//         'products' => $this->product_core_model->get_records(),
//         'warehouses' => $this->warehouse_model->get_records(),
//         'title' => 'Stock Movement Summary'
//     );
    
//     $this->load->view('report/closing_stock', $data);
// }
  
//   public function closing_stock()
// {
//     $from_date = $this->input->post('from_date');
//     $to_date = $this->input->post('to_date');
//     $pid = $this->input->post('pid');
//     $warehouse_id = $this->input->post('warehouse_id');

//     // Default dates if empty
//     $to_date_db = ($to_date) ? date('Y-m-d', strtotime($to_date)) : date('Y-m-d');
//     $from_date_db = ($from_date) ? date('Y-m-d', strtotime($from_date)) : date('Y-m-d', strtotime('-1 month'));

//     // Fetch movements
//     $report_data = $this->report_model->get_stock_movements($from_date_db, $to_date_db, $pid, $warehouse_id);

//     // Calculate running totals for the table
//     foreach ($report_data as $row) {
//         // Here we assume Opening is 0 for the start of the selected period 
//         // to keep the logic simple and prevent crashes.
//         $row->opening_qty = 0; 
//         $row->closing_stock = $row->in_qty - $row->out_qty;
//         $row->closing_value = $row->closing_stock * $row->cost;
//     }

//     $data = array(
//         'from_date' => $from_date,
//         'to_date' => $to_date,
//         'sel_pid'      => $pid,          // Add this
//           'sel_wh'       => $warehouse_id, // Add this
//         'report_data' => $report_data,
//         'products' => $this->product_core_model->get_records(),
//         'warehouses' => $this->warehouse_model->get_records(),
//         'title' => 'Stock Movement Summary'
//     );
    
//     $this->load->view('report/closing_stock', $data);
// }

    // public function closing_stock()
    // {
    //     $from_date = $this->input->post('from_date');
    //     $to_date = $this->input->post('to_date');
    //     $pid = $this->input->post('pid');
    //     $warehouse_id = $this->input->post('warehouse_id');
    
    //     $to_date_db = ($to_date) ? date('Y-m-d', strtotime($to_date)) : date('Y-m-d');
    //     $from_date_db = ($from_date) ? date('Y-m-d', strtotime($from_date)) : date('Y-m-d', strtotime('-1 month'));
    
    //     $report_data = $this->report_model->get_stock_movements($from_date_db, $to_date_db, $pid, $warehouse_id);
    
    //     // --- START CALCULATION LOGIC ---
    //     $total_opening_quantity = 0;
    //     $total_opening_cost = 0;
    //     $total_closing_quantity = 0;
    //     $total_closing_cost = 0;
    
    //     $running_balances = array();
    //     $final_state_per_product = array();
    
    //     if (!empty($report_data)) {
    //         foreach ($report_data as $row) {
    //             $p_id = $row->product_id;
    
    //             // 1. If first time seeing this product, get its balance before the start date
    //             if (!isset($running_balances[$p_id])) {
    //                 $opening = $this->report_model->get_opening_stock_before_date($from_date_db, $p_id, $warehouse_id);
    //                 $running_balances[$p_id] = $opening;
    
    //                 // Add to footer Opening totals
    //                 $total_opening_quantity += $opening;
    //                 $total_opening_cost += ($opening * (float)$row->cost);
    //             }
    
    //             // 2. Set row opening and calculate closing
    //             $row->opening_qty = $running_balances[$p_id];
    //             $row->closing_stock = $row->opening_qty + (float)$row->in_qty - (float)$row->out_qty;
    //             $row->closing_value = $row->closing_stock * (float)$row->cost;
    
    //             // 3. Update running balance for the next row of this product
    //             $running_balances[$p_id] = $row->closing_stock;
    
    //             // 4. Store the current row as the "latest" state for this product
    //             $final_state_per_product[$p_id] = [
    //                 'qty' => $row->closing_stock,
    //                 'val' => $row->closing_value
    //             ];
    //         }
    
    //         // 5. Calculate footer Closing totals from the last known state of each product
    //         foreach ($final_state_per_product as $final) {
    //             $total_closing_quantity += $final['qty'];
    //             $total_closing_cost += $final['val'];
    //         }
    //     }
    //     // --- END CALCULATION LOGIC ---
    
    //     $data = array(
    //         'from_date' => $from_date,
    //         'to_date' => $to_date,
    //         'sel_pid' => $pid,
    //         'sel_wh' => $warehouse_id,
    //         'report_data' => $report_data,
    //         'products' => $this->product_core_model->get_records(),
    //         'warehouses' => $this->warehouse_model->get_records(),
    //         'title' => 'Stock Movement Summary',
    //         // Variables for the View Footer
    //         'total_opening_quantity' => $total_opening_quantity,
    //         'total_opening_cost' => $total_opening_cost,
    //         'total_closing_quantity' => $total_closing_quantity,
    //         'total_closing_cost' => $total_closing_cost
    //     );
        
    //     $this->load->view('report/closing_stock', $data);
    // }

    public function closing_stock()
        {
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
            $pid = $this->input->post('pid');
            $warehouse_id = $this->input->post('warehouse_id');
        
            $to_date_db = ($to_date) ? date('Y-m-d', strtotime($to_date)) : date('Y-m-d');
            $from_date_db = ($from_date) ? date('Y-m-d', strtotime($from_date)) : date('Y-m-d', strtotime('-1 month'));
        
            // Fetch movements (Ensure your Model SQL includes 'pur_amt' and 'in_qty' for purchases)
            $report_data = $this->report_model->get_stock_movements($from_date_db, $to_date_db, $pid, $warehouse_id);
        
            // --- START CALCULATION LOGIC ---
            $total_opening_quantity = 0;
            $total_opening_cost = 0;
            $total_closing_quantity = 0;
            $total_closing_cost = 0;
        
            $running_balances = array();
            $current_product_rate = array(); // To track the actual purchase rate per product
            $final_state_per_product = array();
        
            if (!empty($report_data)) {
                foreach ($report_data as $row) {
                    $p_id = $row->product_id;
        
                    // 1. DETERMINE TRANSACTION RATE (Purchase Cost)
                    // If row is a Purchase, calculate rate from invoice (Total / Qty)
                    // If it's Stock In/Return, use the cost entered in that module
                    // Fallback to Master Product Cost if no transaction rate is found
                    $unit_rate = (float)$row->cost; 
        
                    if ($row->type == 'Purchase' && (float)$row->in_qty > 0) {
                        $unit_rate = (float)$row->pur_amt / (float)$row->in_qty;
                        $current_product_rate[$p_id] = $unit_rate; // Update the 'latest' rate
                    } elseif (isset($row->tran_unit_cost) && $row->tran_unit_cost > 0) {
                        $unit_rate = (float)$row->tran_unit_cost;
                        $current_product_rate[$p_id] = $unit_rate;
                    } elseif (isset($current_product_rate[$p_id])) {
                        $unit_rate = $current_product_rate[$p_id];
                    }
        
                    // 2. Handle Opening Balance logic
                    if (!isset($running_balances[$p_id])) {
                        $opening = $this->report_model->get_opening_stock_before_date($from_date_db, $p_id, $warehouse_id);
                        $running_balances[$p_id] = $opening;
        
                        // Add to footer Opening totals
                        $total_opening_quantity += $opening;
                        $total_opening_cost += ($opening * $unit_rate);
                    }
        
                    // 3. Assign Row Values
                    $row->opening_qty = $running_balances[$p_id];
                    $row->closing_stock = $row->opening_qty + (float)$row->in_qty - (float)$row->out_qty;
                    
                    // VALUATION: Multiply stock by the calculated transaction rate
                    $row->closing_value = $row->closing_stock * $unit_rate;
        
                    // 4. Update running trackers
                    $running_balances[$p_id] = $row->closing_stock;
                    $final_state_per_product[$p_id] = [
                        'qty' => $row->closing_stock,
                        'val' => $row->closing_value
                    ];
                }
                $report_data = array_reverse($report_data); 

                // 5. Calculate footer Closing totals from the last known state of each product
                foreach ($final_state_per_product as $final) {
                    $total_closing_quantity += $final['qty'];
                    $total_closing_cost += $final['val'];
                }
            }
            // --- END CALCULATION LOGIC ---
        
            $data = array(
                'from_date' => $from_date,
                'to_date' => $to_date,
                'sel_pid' => $pid,
                'sel_wh' => $warehouse_id,
                'report_data' => $report_data,
                'products' => $this->product_core_model->get_records(),
                'warehouses' => $this->warehouse_model->get_records(),
                'title' => 'Stock Movement Summary',
                'total_opening_quantity' => $total_opening_quantity,
                'total_opening_cost' => $total_opening_cost,
                'total_closing_quantity' => $total_closing_quantity,
                'total_closing_cost' => $total_closing_cost
            );
            
            $this->load->view('report/closing_stock', $data);
        }

	public function inventory_product_added()
{
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
        // Convert dates to proper format for the model
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        
        // Store formatted dates for the view
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        
        // Get all products
        $data['products'] = $this->product_core_model->get_records();
        
        // Pre-fetch inventory data for each product
        $data['inventory_data'] = array();
        if($data['products'] && !empty($data['products'])) {
            foreach($data['products'] as $product) {
                $inventory_data = $this->report_model->inventory_product_added_report(
                    $product->pid,
                    $to_date,
                    $from_date
                );
                if(!empty($inventory_data)) {
                    $data['inventory_data'][$product->pid] = $inventory_data;
                }
            }
        }
        
        $this->load->view('report/inventory_product_added', $data);
    }
    else
    {
        $data['from_date'] = null;
        $data['to_date'] = date('Y-m-d');
        $data['products'] = $this->product_core_model->get_records();
        $data['inventory_data'] = array();
        
        $this->load->view('report/inventory_product_added', $data);
    }
}

	public function receivable($action_type = null)
	{
		if($action_type != null)
		{
			if($action_type == 'pdf')
			{
				
				$file_name 							= 'Receivable_Report_'.date("Y-m-d h-i-s").'.pdf';

				$data['customers'] 			= $this->customer_model->get_records();
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html 									= $this->load->view('report/pdf/receivable',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$sale_data['customers'] 			= $this->customer_model->get_records();
				$sale_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/receivable',$sale_data);
			}
		}
		else
		{
			$data['customers'] 			=	$this->customer_model->get_records();
			
			$this->load->view('report/receivable',$data);
		}
	}
	public function payable($action_type = null)
	{
		if($action_type != null)
		{
			if($action_type == 'pdf')
			{
				
				$file_name 							= 'Payable_Report_'.date("Y-m-d h-i-s").'.pdf';

				$data['suppliers'] 			= $this->supplier_model->get_records();
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html 									= $this->load->view('report/pdf/payable',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$sale_data['suppliers'] 			= $this->supplier_model->get_records();
				$sale_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/payable',$sale_data);
			}
		}
		else
		{
			$data['suppliers'] 			=	$this->supplier_model->get_records();
			
			$this->load->view('report/payable',$data);
		}
	}

	public function hsn_sale()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
			$sale_id 				= $this->input->post('sale_id');
			$hsn_grouping 	= $this->input->post('hsn_grouping');
			$action_type 		= $this->input->post('action_type');

			if($action_type == 'export')
			{
				$file_name 	= 'HSN_SALE_REPORT_'.date("Y-m-d h-i-s").'.csv';
		    $query 			= $this->report_model->hsn_sale_report($from_date,$to_date,$sale_id,$hsn_grouping);

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);

        $data = str_replace("<br />", "", $data);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 							= 'HSN_SALE_REPORT_'.date("Y-m-d h-i-s").'.pdf';

				$data['products'] 			= $this->report_model->hsn_sale_report($from_date,$to_date,$sale_id,$hsn_grouping)->result();
				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html 									= $this->load->view('report/pdf/product',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$product_data['products'] 					= $this->report_model->hsn_sale_report($from_date,$to_date,$sale_id,$hsn_grouping)->result();
				$product_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$product_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$product_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/product',$product_data);
			}
			else
			{
				$product_data['sale_items'] 	= $this->report_model->hsn_sale_report($from_date,$to_date,$sale_id,$hsn_grouping)->result();
				$data['products'] 				= $this->load->view('report/ajax/hsn_sale',$product_data,true);

				echo json_encode($data);
			}
		}
		else
		{
			$data['sales'] 			=	$this->sale_model->get_sale_records();
			$data['products'] 			=	$this->product_core_model->get_records();
			$this->load->view('report/hsn_sale',$data);
		}
	}

	public function hsn_purchase()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
			$purchase_id 				= $this->input->post('purchase_id');
			$hsn_grouping 	= $this->input->post('hsn_grouping');
			$action_type 		= $this->input->post('action_type');

			if($action_type == 'export')
			{
				$file_name 	= 'HSN_PURCHASE_REPORT_'.date("Y-m-d h-i-s").'.csv';
		    $query 			= $this->report_model->hsn_purchase_report($from_date,$to_date,$purchase_id,$hsn_grouping);

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);

        $data = str_replace("<br />", "", $data);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 							= 'HSN_PURCHASE_REPORT_'.date("Y-m-d h-i-s").'.pdf';

				$data['products'] 					= $this->report_model->hsn_purchase_report($from_date,$to_date,$purchase_id,$hsn_grouping)->result();
				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html 									= $this->load->view('report/pdf/product',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$product_data['products'] 					= $this->report_model->hsn_purchase_report($from_date,$to_date,$purchase_id,$hsn_grouping)->result();
				$product_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$product_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$product_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/product',$product_data);
			}
			else
			{
				$product_data['purchase_items'] 	= $this->report_model->hsn_purchase_report($from_date,$to_date,$purchase_id,$hsn_grouping)->result();
				$data['products'] 				= $this->load->view('report/ajax/hsn_purchase',$product_data,true);

				echo json_encode($data);
			}
		}
		else
		{
			$data['purchases'] 			=	$this->purchase_model->get_purchase_records();
			$data['products'] 			=	$this->product_core_model->get_records();
			$this->load->view('report/hsn_purchase',$data);
		}
	}

	public function stock()
	{
		$stock_report = $this->report_model->stock_report();
		$this->load->view('report/stock',$stock_report);
	}

	public function product()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$product_id 		= $this->input->post('product_id');
			$action_type 		= $this->input->post('action_type');

			if($action_type == 'export')
			{
				$file_name 	= 'Product_Report_'.date("Y-m-d h-i-s").'.csv';
		    $query 			= $this->report_model->product_report($product_id);

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);

        $data = str_replace("<br />", "", $data);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 							= 'Product_Report_'.date("Y-m-d h-i-s").'.pdf';
				$data['products'] 			= $this->report_model->product_report($product_id)->result();
				$data['company_setting']= $this->company_settings_model->get_company_records();
				$html 									= $this->load->view('report/pdf/product',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$product_data['products'] 					= $this->report_model->product_report($product_id)->result();
				$product_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/product',$product_data);
			}
			else
			{
				$product_data['products'] 	= $this->report_model->product_report($product_id)->result();
				$data['products'] 				= $this->load->view('report/ajax/product',$product_data,true);

				echo json_encode($data);
			}
		}
		else
		{
			$data['customers'] 			=	$this->customer_model->get_records();
			$data['products'] 			=	$this->product_core_model->get_records();
			$this->load->view('report/product',$data);
		}
	}

	public function product_sale()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
			$customer_id 		= $this->input->post('customer_id');
			$product_id 		= $this->input->post('product_id');
			$action_type 		= $this->input->post('action_type');

			if($action_type == 'export')
			{
				$file_name 	= 'Product_Sale_Report_'.date("Y-m-d h-i-s").'.csv';
		    $query 			= $this->report_model->product_sale_report($from_date,$to_date,$customer_id,$product_id,$action_type);

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);

        $data = str_replace("<br />", "", $data);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 							= 'Product_Sale_Report_'.date("Y-m-d h-i-s").'.pdf';

				$data['products'] 					= $this->report_model->product_sale_report($from_date,$to_date,$customer_id,$product_id,$action_type)->result();
				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html 									= $this->load->view('report/pdf/product_sale',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$product_data['products'] 					= $this->report_model->product_sale_report($from_date,$to_date,$customer_id,$product_id,$action_type)->result();
				$product_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$product_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$product_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/product_sale',$product_data);
			}
			else
			{
				$product_data['sale_items'] 	= $this->report_model->product_sale_report($from_date,$to_date,$customer_id,$product_id,$action_type)->result();
				$data['products'] 				= $this->load->view('report/ajax/product_sale',$product_data,true);

				echo json_encode($data);
			}
		}
		else
		{
			$data['customers'] 			=	$this->customer_model->get_records();
			$data['products'] 			=	$this->product_core_model->get_records();
			$this->load->view('report/product_sale',$data);
		}
	}

	public function product_purchase()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
			$supplier_id 		= $this->input->post('supplier_id');
			$product_id 		= $this->input->post('product_id');
			$action_type 		= $this->input->post('action_type');

			if($action_type == 'export')
			{
				$file_name 	= 'Product_Purchase_Report_'.date("Y-m-d h-i-s").'.csv';
		    $query 			= $this->report_model->product_purchase_report($from_date,$to_date,$supplier_id,$product_id,$action_type);

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);

        $data = str_replace("<br />", "", $data);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 							= 'Product_Purchase_Report_'.date("Y-m-d h-i-s").'.pdf';

				$data['products'] 					= $this->report_model->product_purchase_report($from_date,$to_date,$supplier_id,$product_id,$action_type)->result();
				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html 									= $this->load->view('report/pdf/product_purchase',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$product_data['products'] 					= $this->report_model->product_purchase_report($from_date,$to_date,$supplier_id,$product_id,$action_type)->result();
				$product_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$product_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$product_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/product_purchase',$product_data);
			}
			else
			{
				$product_data['purchase_items'] 	= $this->report_model->product_purchase_report($from_date,$to_date,$supplier_id,$product_id,$action_type)->result();
        $data['qeury'] = $this->db->last_query();
				$data['products'] 				= $this->load->view('report/ajax/product_purchase',$product_data,true);

				echo json_encode($data);
			}
		}
		else
		{
			$data['suppliers'] 			=	$this->supplier_model->get_records();
			$data['products'] 			=	$this->product_core_model->get_records();
			$this->load->view('report/product_purchase',$data);
		}
	}


	/*public function sale()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
			$customer_id 		= $this->input->post('customer_id');
			$warehouse_id 	= $this->input->post('warehouse_id');
			$action_type 		= $this->input->post('action_type');

			if($action_type == 'export')
			{
				$file_name 	= 'Sales_Report_'.date("Y-m-d h-i-s").'.csv';
		    $query 			= $this->report_model->sale_report($from_date,$to_date,$customer_id,$warehouse_id);

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 							= 'Sales_Report_'.date("Y-m-d h-i-s").'.pdf';

				$data['sales'] 					= $this->report_model->sale_report($from_date,$to_date,$customer_id, $warehouse_id)->result();
				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html 									= $this->load->view('report/pdf/sale',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$sale_data['sales'] 					= $this->report_model->sale_report($from_date,$to_date,$customer_id,$warehouse_id)->result();
				$sale_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$sale_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$sale_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/sale',$sale_data);
			}
			else
			{
				$sale_data['sales'] 	    = $this->report_model->sale_report($from_date,$to_date,$customer_id,$warehouse_id)->result();
				$data['sales'] 				= $this->load->view('report/ajax/sale',$sale_data,true);

				echo json_encode($data);
			}
		}
		else
		{
			$data['customers'] 			=	$this->customer_model->get_records();
			$data['warehouse'] 			=	$this->warehouse_model->get_records();
			$data['sales'] 				=	$this->sale_model->get_sale_records();
			$this->load->view('report/sale',$data);
		}
	}*/
	/*public function sale()
{
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
        $from_date      = ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
        $to_date        = ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
        $customer_id    = $this->input->post('customer_id');
        $warehouse_id   = $this->input->post('warehouse_id');
        $action_type    = $this->input->post('action_type');

        if($action_type == 'export')
        {
            $file_name = 'Sales_Report_'.date("Y-m-d h-i-s").'.csv';
            $query = $this->report_model->sale_report($from_date,$to_date,$customer_id,$warehouse_id);

            $this->load->dbutil();
            $data = $this->dbutil->csv_from_result($query);
            
            $this->load->helper('download');
            force_download($file_name, $data);  
            exit();
        }
        else if($action_type == 'pdf')
        {
            $file_name = 'Sales_Report_'.date("Y-m-d h-i-s").'.pdf';

            $data['sales'] = $this->report_model->sale_report($from_date,$to_date,$customer_id, $warehouse_id)->result();
            $data['from_date'] = ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
            $data['to_date'] = ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
            $data['company_setting'] = $this->company_settings_model->get_company_records();
            
            $html = $this->load->view('report/pdf/sale',$data,true);
        
            $this->pdf->loadHtml($html);
            $this->pdf->render();
            $this->pdf->stream($file_name, array("Attachment"=>1));
        }
        else if($action_type == 'print')
        {   
            $sale_data['sales'] = $this->report_model->sale_report($from_date,$to_date,$customer_id,$warehouse_id)->result();
            $sale_data['from_date'] = ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
            $sale_data['to_date'] = ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
            $sale_data['company_setting'] = $this->company_settings_model->get_company_records();

            $this->load->view('report/print/sale',$sale_data);
        }
        else
        {
            $sale_data['sales'] = $this->report_model->sale_report($from_date,$to_date,$customer_id,$warehouse_id)->result();
            $data['sales'] = $this->load->view('report/ajax/sale',$sale_data,true);

            echo json_encode($data);
        }
    }
    else
    {
        // ✅ FIXED: Get ACTIVE users (customers) for filter dropdown
        $data['customers'] = $this->db->select('id, first_name, last_name, email, phone')
            ->from('users')
            ->where('active', 1)  // 1 = active user
            ->get()
            ->result();
        
        $data['warehouse'] = $this->warehouse_model->get_records();
        $data['sales'] = $this->sale_model->get_sale_records();
        $this->load->view('report/sale', $data);
    }
}*/
public function sale()
{
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
        $from_date      = ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
        $to_date        = ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
        $customer_id    = $this->input->post('customer_id');
        $warehouse_id   = $this->input->post('warehouse_id');
        $action_type    = $this->input->post('action_type');

        if($action_type == 'export')
        {
            $file_name = 'Sales_Report_'.date("Y-m-d h-i-s").'.csv';
            $query = $this->report_model->sale_report($from_date,$to_date,$customer_id,$warehouse_id);

            $this->load->dbutil();
            $data = $this->dbutil->csv_from_result($query);
            
            $this->load->helper('download');
            force_download($file_name, $data);  
            exit();
        }
        else if($action_type == 'pdf')
        {
            $file_name = 'Sales_Report_'.date("Y-m-d h-i-s").'.pdf';

            $data['sales'] = $this->report_model->sale_report($from_date,$to_date,$customer_id, $warehouse_id)->result();
            $data['from_date'] = ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
            $data['to_date'] = ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
            $data['company_setting'] = $this->company_settings_model->get_company_records();
            
            $html = $this->load->view('report/pdf/sale',$data,true);
        
            $this->pdf->loadHtml($html);
            $this->pdf->render();
            $this->pdf->stream($file_name, array("Attachment"=>1));
        }
        else if($action_type == 'print')
        {   
            $sale_data['sales'] = $this->report_model->sale_report($from_date,$to_date,$customer_id,$warehouse_id)->result();
            $sale_data['from_date'] = ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
            $sale_data['to_date'] = ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
            $sale_data['company_setting'] = $this->company_settings_model->get_company_records();

            $this->load->view('report/print/sale',$sale_data);
        }
        else
        {
            // ✅ FIXED: Get the result as array/object properly
            $query = $this->report_model->sale_report($from_date,$to_date,$customer_id,$warehouse_id);
            $sale_data['sales'] = $query->result();
            
            // Debug - uncomment to see what's being returned
            // echo "<pre>";
            // print_r($sale_data['sales']);
            // echo "</pre>";
            // die();
            
            $data['sales'] = $this->load->view('report/ajax/sale',$sale_data,true);
            echo json_encode($data);
        }
    }
    else
    {
        // ✅ Get initial data for page load
        $query = $this->report_model->sale_report('', '', '', '');
        $data['sales'] = $query->result();
        
        // Get active users for customer dropdown
        $data['customers'] = $this->db->select('id, first_name, last_name, email, phone')
            ->from('users')
            ->where('active', 1)
            ->get()
            ->result();
        
        $data['warehouse'] = $this->warehouse_model->get_records();
        
        // Debug - uncomment to see what's being returned
        // echo "<pre>";
        // echo "Number of sales: " . count($data['sales']) . "\n";
        // print_r($data['sales']);
        // echo "</pre>";
        // die();
        
        $this->load->view('report/sale', $data);
    }
}

// 	public function sales_return()
// 	{
// 		if($this->input->server('REQUEST_METHOD') === 'POST')
// 		{
// 			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
// 			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
// 			$warehouse_id 	= $this->input->post('warehouse_id');
// 			$customer_id 		= $this->input->post('customer_id');
// 			$action_type 		= $this->input->post('action_type');

// 			if($action_type == 'export')
// 			{
// 				$file_name 	= 'SalesReturn_Report_'.date("Y-m-d h-i-s").'.csv';
// 		    $query 			= $this->report_model->sales_return_report($from_date,$to_date,$warehouse_id,$customer_id);

// 		    $this->load->dbutil();

// 		    $data 			= $this->dbutil->csv_from_result($query);
		    
// 		    $this->load->helper('download');
// 		    force_download($file_name, $data);  
// 		    exit();
// 			}
// 			else if($action_type == 'pdf')
// 			{
// 				$file_name 							= 'SalesReturn_Report_'.date("Y-m-d h-i-s").'.pdf';

// 				$data['sales_return'] 					= $this->report_model->sales_return_report($from_date,$to_date,$warehouse_id,$customer_id)->result();
// 				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
// 				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
// 				$data['company_setting']= $this->company_settings_model->get_company_records();
				
// 				$html 									= $this->load->view('report/pdf/sales_return',$data,true);
			
// 				$this->pdf->loadHtml($html);
// 				$this->pdf->render();
// 				$this->pdf->stream($file_name, array("Attachment"=>1));
// 			}
// 			else if($action_type == 'print')
// 			{	
// 				$sales_return_data['sales_return'] 		= $this->report_model->sales_return_report($from_date,$to_date,$warehouse_id,$customer_id)->result();
// 				$sales_return_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
// 				$sales_return_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
// 				$sales_return_data['company_setting'] = $this->company_settings_model->get_company_records();

// 				$this->load->view('report/print/sales_return',$sales_return_data);
// 			}
// 			else
// 			{
// 				$sales_return_data['sales_return'] 	= $this->report_model->sales_return_report($from_date,$to_date,$warehouse_id,$customer_id)->result();
// 				$data['sales_return'] 			= $this->load->view('report/ajax/sales_return',$sales_return_data,true);

// 				echo json_encode($data);
// 			}
// 		}
// 		else
// 		{
// 			$data['warehouses'] 		=	$this->warehouse_model->get_records();
// 			$data['warehouse'] 			=	$this->warehouse_model->get_records();
// 			$data['customers'] 			=	$this->customer_model->get_records();
// 			$data['sales_return'] 	=	$this->sales_return_model->get_sales_return_records();
// 			$this->load->view('report/sales_return',$data);
// 		}
// 	}

         public function sales_return()
            {
                if($this->input->server('REQUEST_METHOD') === 'POST')
                {
                    // 1. Date Formatting (Database format: YYYY-MM-DD)
                    $from_date = ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
                    $to_date   = ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
                    $warehouse_id = $this->input->post('warehouse_id');
                    $customer_id  = $this->input->post('customer_id');
                    $action_type  = $this->input->post('action_type');
            
                    // 2. Export to CSV Logic
                    if($action_type == 'export')
                    {
                        $file_name = 'Sales_Return_Report_'.date("Y-m-d_H-i-s").'.csv';
                        $query = $this->report_model->sales_return_report($from_date, $to_date, $warehouse_id, $customer_id);
            
                        $this->load->dbutil();
                        $data = $this->dbutil->csv_from_result($query);
                        $data = str_replace("<br />", "", $data);
                        
                        $this->load->helper('download');
                        force_download($file_name, $data);  
                        exit();
                    }
                    // 3. Export to PDF or Print Logic
                    else if($action_type == 'pdf' || $action_type == 'print')
                    {
                        $report_data['sales_return'] = $this->report_model->sales_return_report($from_date, $to_date, $warehouse_id, $customer_id)->result();
                        $report_data['from_date'] = ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
                        $report_data['to_date']   = ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
                        $report_data['company_setting'] = $this->company_settings_model->get_company_records();
                        
                        if($action_type == 'pdf') {
                            $html = $this->load->view('report/pdf/sales_return', $report_data, true);
                            $this->pdf->loadHtml($html);
                            $this->pdf->render();
                            $this->pdf->stream("Sales_Return_Report.pdf", array("Attachment" => 1));
                        } else {
                            $this->load->view('report/print/sales_return', $report_data);
                        }
                    }
                    // 4. AJAX Search (Triggers when user clicks 'Search' on page)
                    else
                    {
                        $query_result = $this->report_model->sales_return_report($from_date, $to_date, $warehouse_id, $customer_id);
                        $ajax_data['sales_return'] = $query_result->result();
                        
                        // Returns ONLY the table HTML for the AJAX container
                        $data['sales_return'] = $this->load->view('report/ajax/sales_return', $ajax_data, true);
            
                        echo json_encode($data);
                    }
                }
                else
                {
                    // 5. Initial Page Load (GET Request)
                    // CRITICAL FIX: Use Report Model instead of standard model to populate all 10 columns
                    $data['sales_return'] = $this->report_model->sales_return_report('', '', '', '')->result();
                    
                    $data['warehouses']   = $this->warehouse_model->get_records();
                    $data['warehouse']    = $data['warehouses']; 
                    $data['customers']    = $this->customer_model->get_records();
                    
                    // Default dates for the filter boxes
                    $data['from_date']    = '';
                    $data['to_date']      = date('d-m-Y');
            
                    $this->load->view('report/sales_return', $data);
                }
            }

	/*public function purchase()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
			$supplier_id 		= $this->input->post('supplier_id');
			$warehouse_id 	= $this->input->post('warehouse_id');
			$action_type 		= $this->input->post('action_type');

			if($action_type == 'export')
			{
				$file_name 	= 'Purchase_Report_'.date("Y-m-d h-i-s").'.csv';
		    $query 			= $this->report_model->purchase_report($from_date,$to_date,$supplier_id,$warehouse_id);

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 							= 'Purchase_Report_'.date("Y-m-d h-i-s").'.pdf';

				$data['purchases'] 					= $this->report_model->purchase_report($from_date,$to_date,$supplier_id,$warehouse_id)->result();
				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html 									= $this->load->view('report/pdf/purchase',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$sale_data['purchases'] 					= $this->report_model->purchase_report($from_date,$to_date,$supplier_id,$warehouse_id)->result();
				$sale_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$sale_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$sale_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/purchase',$sale_data);
			}
			else
			{
				$sale_data['purchases'] 	= $this->report_model->purchase_report($from_date,$to_date,$supplier_id,$warehouse_id)->result();
				$data['purchases'] 				= $this->load->view('report/ajax/purchase',$sale_data,true);

				echo json_encode($data);
			}
		}
		else
		{
			$data['suppliers'] 			=	$this->supplier_model->get_records();
			$data['warehouse'] 			=	$this->warehouse_model->get_records();
			$data['purchases'] 			=	$this->purchase_model->get_purchase_records();
			$this->load->view('report/purchase',$data);
		}
	}*/

	public function purchase()
{
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
        $from_date      = ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
        $to_date        = ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
        $supplier_id    = $this->input->post('supplier_id');
        $warehouse_id   = $this->input->post('warehouse_id');
        $action_type    = $this->input->post('action_type');

        if($action_type == 'export')
        {
            $file_name = 'Purchase_Report_'.date("Y-m-d h-i-s").'.csv';
            $query = $this->report_model->purchase_report($from_date, $to_date, $supplier_id, $warehouse_id);

            $this->load->dbutil();
            $data = $this->dbutil->csv_from_result($query);
            $data = str_replace("<br />", "", $data);
            
            $this->load->helper('download');
            force_download($file_name, $data);  
            exit();
        }
        else if($action_type == 'pdf')
        {
            $file_name = 'Purchase_Report_'.date("Y-m-d h-i-s").'.pdf';

            $data['purchases'] = $this->report_model->purchase_report($from_date, $to_date, $supplier_id, $warehouse_id)->result();
            $data['from_date'] = ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
            $data['to_date'] = ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
            $data['company_setting'] = $this->company_settings_model->get_company_records();
            
            $html = $this->load->view('report/pdf/purchase', $data, true);
        
            $this->pdf->loadHtml($html);
            $this->pdf->render();
            $this->pdf->stream($file_name, array("Attachment"=>1));
        }
        else if($action_type == 'print')
        {   
            $sale_data['purchases'] = $this->report_model->purchase_report($from_date, $to_date, $supplier_id, $warehouse_id)->result();
            $sale_data['from_date'] = ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
            $sale_data['to_date'] = ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
            $sale_data['company_setting'] = $this->company_settings_model->get_company_records();

            $this->load->view('report/print/purchase', $sale_data);
        }
        else
        {
            $sale_data['purchases'] = $this->report_model->purchase_report($from_date, $to_date, $supplier_id, $warehouse_id)->result();
            $data['purchases'] = $this->load->view('report/ajax/purchase', $sale_data, true);

            echo json_encode($data);
        }
    }
    else
    {
        // For initial page load - get all purchases with items
        $data['purchases'] = $this->report_model->purchase_report('', '', '', '')->result();
        $data['suppliers'] = $this->supplier_model->get_records();
        $data['warehouse'] = $this->warehouse_model->get_records();
        $this->load->view('report/purchase', $data);
    }
}

// 	public function purchase_return()
// 	{
// 		if($this->input->server('REQUEST_METHOD') === 'POST')
// 		{
// 			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
// 			$to_date 			= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
// 			$supplier_id 		= $this->input->post('supplier_id');
// 			$warehouse_id 		= $this->input->post('warehouse_id');
// 			$action_type 		= $this->input->post('action_type');

// 			if($action_type == 'export')
// 			{
// 				$file_name 	= 'PurchaseReturn_Report_'.date("Y-m-d h-i-s").'.csv';
// 		    $query 			= $this->report_model->purchase_return_report($from_date,$to_date,$supplier_id,$warehouse_id);

// 		    $this->load->dbutil();

// 		    $data 			= $this->dbutil->csv_from_result($query);
		    
// 		    $this->load->helper('download');
// 		    force_download($file_name, $data);  
// 		    exit();
// 			}
// 			else if($action_type == 'pdf')
// 			{
// 				$file_name 							= 'PurchaseReturn_Report_'.date("Y-m-d h-i-s").'.pdf';

// 				$data['purchase_return'] 					= $this->report_model->purchase_return_report($from_date,$to_date,$supplier_id,$warehouse_id)->result();
// 				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
// 				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
// 				$data['company_setting']= $this->company_settings_model->get_company_records();
				
// 				$html 									= $this->load->view('report/pdf/purchase_return',$data,true);
			
// 				$this->pdf->loadHtml($html);
// 				$this->pdf->render();
// 				$this->pdf->stream($file_name, array("Attachment"=>1));
// 			}
// 			else if($action_type == 'print')
// 			{	
// 				$sale_data['purchase_return'] 					= $this->report_model->purchase_return_report($from_date,$to_date,$supplier_id,$warehouse_id)->result();
// 				$sale_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
// 				$sale_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
// 				$sale_data['company_setting'] = $this->company_settings_model->get_company_records();

// 				$this->load->view('report/print/purchase_return',$sale_data);
// 			}
// 			else
// 			{
// 				$sale_data['purchase_return'] 	= $this->report_model->purchase_return_report($from_date,$to_date,$supplier_id,$warehouse_id)->result();
// 				$data['purchase_return'] 				= $this->load->view('report/ajax/purchase_return',$sale_data,true);

// 				echo json_encode($data);
// 			}
// 		}
// 		else
// 		{
// 			$data['suppliers'] 			=	$this->supplier_model->get_records();
// 			$data['warehouse'] 			=	$this->warehouse_model->get_records();
// 			$data['purchase_return'] 			=	$this->purchase_return_model->get_purchase_return_records();
// 			$this->load->view('report/purchase_return',$data);
// 		}
// 	}


    public function purchase_return()
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $from_date      = ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
            $to_date        = ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
            $supplier_id    = $this->input->post('supplier_id');
            $warehouse_id   = $this->input->post('warehouse_id');
            $action_type    = $this->input->post('action_type');
    
            if($action_type == 'export')
            {
                $file_name = 'Purchase_Return_Report_'.date("Y-m-d h-i-s").'.csv';
                $query = $this->report_model->purchase_return_report($from_date, $to_date, $supplier_id, $warehouse_id);
                $this->load->dbutil();
                $data = $this->dbutil->csv_from_result($query);
                $this->load->helper('download');
                force_download($file_name, $data);  
                exit();
            }
            else if($action_type == 'pdf' || $action_type == 'print')
            {
                $report_data['purchase_return'] = $this->report_model->purchase_return_report($from_date, $to_date, $supplier_id, $warehouse_id)->result();
                $report_data['from_date'] = $from_date;
                $report_data['to_date'] = $to_date;
                $report_data['company_setting'] = $this->company_settings_model->get_company_records();
                
                if($action_type == 'pdf'){
                    $html = $this->load->view('report/pdf/purchase_return', $report_data, true);
                    $this->pdf->loadHtml($html);
                    $this->pdf->render();
                    $this->pdf->stream("Purchase_Return.pdf", array("Attachment"=>1));
                } else {
                    $this->load->view('report/print/purchase_return', $report_data);
                }
            }
            else
            {
                // AJAX SEARCH FIX: Load only the table HTML
                $sale_data['purchase_return'] = $this->report_model->purchase_return_report($from_date, $to_date, $supplier_id, $warehouse_id)->result();
                $data['purchase_return'] = $this->load->view('report/ajax/purchase_return', $sale_data, true);
                echo json_encode($data);
            }
        }
        else
        {
            // INITIAL LOAD FIX: Use report_model instead of purchase_return_model
            $data['purchase_return'] = $this->report_model->purchase_return_report('', '', '', '')->result();
            $data['suppliers'] = $this->supplier_model->get_records();
            $data['warehouse'] = $this->warehouse_model->get_records();
            $this->load->view('report/purchase_return', $data);
        }
    }
    
    
	public function expense()
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date 						= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date 							= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
			$supplier_id 					= $this->input->post('supplier_id');
			$expense_category_id 	= $this->input->post('expense_category_id');
			$action_type 					= $this->input->post('action_type');

			if($action_type == 'export')
			{
				$file_name 	= 'Expense_Report_'.date("Y-m-d h-i-s").'.csv';
		    $query 			= $this->report_model->expense_report($from_date,$to_date,$supplier_id,$expense_category_id);

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 								= 'Expense_Report_'.date("Y-m-d h-i-s").'.pdf';
				$data['expenses'] 				= $this->report_model->expense_report($from_date,$to_date,$supplier_id,$expense_category_id)->result();
				$data['expense_category'] = $this->expense_category_model->get_records();
				
				$data['from_date'] 				= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$data['to_date'] 					= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$data['company_setting'] 	= $this->company_settings_model->get_company_records();
				$html 										= $this->load->view('report/pdf/expense',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$expense_data['expenses'] 				= $this->report_model->expense_report($from_date,$to_date,$supplier_id,$expense_category_id)->result();
				
				$expense_data['from_date'] 				= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$expense_data['to_date'] 					= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$expense_data['company_setting'] 	= $this->company_settings_model->get_company_records();
				
				$this->load->view('report/print/expense',$expense_data);
			}
			else
			{
				$expense_data['expenses'] = $this->report_model->expense_report($from_date,$to_date,$supplier_id,$expense_category_id)->result();
				$data['expenses'] 				= $this->load->view('report/ajax/expense',$expense_data,true);

				echo json_encode($data);
			}
		}
		else
		{
			$data['expenses']				 			= $this->expense_model->get_records();
			$data['suppliers'] 						= $this->supplier_model->get_records();
			$data['expense_categories'] 	= $this->expense_category_model->get_records();

			$this->load->view('report/expense',$data);
		}
	}

	public function profit_and_loss()
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$year_ending 		= $this->input->post('year_ending');
			$action_type 		= $this->input->post('action_type');

			if($action_type == 'pdf')
			{
				$file_name 							= 'Profit_and_Loss_Report_'.date("Y-m-d h-i-s").'.pdf';

				$data['year_ending']		= $year_ending;
				$data['transactions'] 	= $this->report_model->profit_and_loss_report($year_ending)->result();
				$data['company_setting']= $this->company_settings_model->get_company_records();
				$html 									= $this->load->view('report/pdf/profit_and_loss',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				$data['year_ending']		= $year_ending;
				$data['transactions'] 	= $this->report_model->profit_and_loss_report($year_ending)->result();
				$data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/profit_and_loss',$data);
			}
			else
			{
				$pl_data['year_ending']		= $year_ending;
				$pl_data['transactions'] 	= $this->report_model->profit_and_loss_report($year_ending)->result();


				$data['transaction']			= $pl_data['transactions'];
				$data['profit_and_loss'] 	= $this->load->view('report/ajax/profit_and_loss',$pl_data,true);
				$data['transactions'] 		= $pl_data['transactions'];

				echo json_encode($data);
			}
		}
		else
		{
			$data['company_setting'] 	= $this->company_settings_model->get_single_record(1); 
			$this->load->view('report/profit_and_loss',$data);
		}
	}

	// public function balance_sheet()
	// {
	// 	if($this->input->server('REQUEST_METHOD') === 'POST')
	// 	{
	// 		$year_ending 		= $this->input->post('year_ending');
	// 		$action_type 		= $this->input->post('action_type');

	// 		if($action_type == 'pdf')
	// 		{
	// 			$file_name 							= 'Balance_Sheet_Report_'.date("Y-m-d h-i-s").'.pdf';

	// 			$data['year_ending']		= $year_ending;
	// 			$data['transactions'] 	= $this->report_model->balance_sheet_report($year_ending);
	// 			$data['company_setting']= $this->company_settings_model->get_company_records();
				
	// 			$html 									= $this->load->view('report/pdf/balance_sheet',$data,true);
			
	// 			$this->pdf->loadHtml($html);
	// 			$this->pdf->render();
	// 			$this->pdf->stream($file_name, array("Attachment"=>1));
	// 		}
	// 		else if($action_type == 'print')
	// 		{	
	// 			$data['year_ending']			= $year_ending;
	// 			$data['transactions'] 		= $this->report_model->balance_sheet_report($year_ending);
	// 			$data['company_setting'] 	= $this->company_settings_model->get_company_records();

	// 			$this->load->view('report/print/balance_sheet',$data);
	// 		}
	// 		else
	// 		{
	// 			$balance_sheet_data['year_ending']		= $year_ending;
	// 			$balance_sheet_data['bl_sheet'] 			= $this->report_model->balance_sheet_report($year_ending);

	// 			/*$data['last_query'] 			= $this->db->last_query();*/
	// 			// $data['transaction']		= $balance_sheet_data['transactions'];
	// 			$data['balance_sheet'] 		= $this->load->view('report/ajax/balance_sheet',$balance_sheet_data,true);
	// 			$data['data'] 						= $balance_sheet_data['bl_sheet'];
	// 			// $data['query']						= $this

	// 			echo json_encode($data);
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$data['company_setting'] 	= $this->company_settings_model->get_single_record(1); 
	// 		$this->load->view('report/balance_sheet',$data);
	// 	}
	// }

// 	public function ledger()
// 	{
// 		if($this->input->server('REQUEST_METHOD') === 'POST')
// 		{
// 			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
// 			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
// 			$ledger_id 			= $this->input->post('ledger_id');
// 			$action_type 		= $this->input->post('action_type');

// 			if($action_type == 'pdf')
// 			{
// 				$file_name 										= 'Ledger_Report_'.date("Y-m-d h-i-s").'.pdf';

// 				$data['transaction_details'] 	= $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();

// 				$data['from_date']						= ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
// 				$data['to_date']							= ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
// 				$data['ledger_detail']				= $this->ledger_model->get_single_record($ledger_id);

				
// 				$data['from_date'] 						= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
// 				$data['to_date'] 							= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
// 				$data['company_setting']			= $this->company_settings_model->get_company_records();
// 				$data['transaction_details']	= $data['transaction_details'];
				
// 				$html 									= $this->load->view('report/pdf/ledger',$data,true);
			
// 				$this->pdf->loadHtml($html);
// 				$this->pdf->render();
// 				$this->pdf->stream($file_name, array("Attachment"=>1));
// 			}
// 			else if($action_type == 'print')
// 			{	
// 				$data['transaction_details'] 	= $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();

// 				$data['from_date']						= ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
// 				$data['to_date']							= ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
// 				$data['ledger_detail']				= $this->ledger_model->get_single_record($ledger_id);

				
// 				$data['from_date'] 						= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
// 				$data['to_date'] 							= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
// 				$data['company_setting']			= $this->company_settings_model->get_company_records();
// 				$data['transaction_details']	= $data['transaction_details'];

// 				$this->load->view('report/print/ledger',$data);
// 			}
// 			else
// 			{
// 				$ledger_data['transaction_details'] 		= $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
// 		        $ledger_data['from_date']								= ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
// 				$ledger_data['to_date']									= ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
// 				$ledger_data['ledger_detail']						= $this->ledger_model->get_single_record($ledger_id);
				
// 				$data['transaction_details']					  = $ledger_data['transaction_details'];
				
// 				// echo "<pre>";
// 				// print_r($data);
// 				// echo "</pre>";
// 				// die;
				
				
// 				$data['ledger_report'] 									= $this->load->view('report/ajax/ledger',$ledger_data,true);

// 				echo json_encode($data);
// 			}
// 		}
// 		else
// 		{
// 			$data['company_setting']			= $this->company_settings_model->get_company_records();
// 			$data['ledger_account'] 	= $this->ledger_model->get_records_by_account_group_category(array('Income','Assets','Liability','Expense'));
// 			$this->load->view('report/ledger',$data);
// 		}
// 	}

// public function ledger()
// {
//     if($this->input->server('REQUEST_METHOD') === 'POST')
//     {
//         $from_date      = ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
//         $to_date        = ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
//         $ledger_id      = $this->input->post('ledger_id');
//         $action_type    = $this->input->post('action_type');

//         // Get opening balance (transactions before from_date)
//          $opening_balance = $this->report_model->get_ledger_balance($ledger_id, $from_date);

//         if($action_type == 'pdf')
//         {
//             $file_name = 'Ledger_Report_'.date("Y-m-d h-i-s").'.pdf';

//             $data['transaction_details'] = $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
//             $data['opening_balance'] = $opening_balance;
//             $data['from_date'] = ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
//             $data['to_date'] = ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
//             $data['ledger_detail'] = $this->ledger_model->get_single_record($ledger_id);
//             $data['company_setting'] = $this->company_settings_model->get_company_records();
            
//             $html = $this->load->view('report/pdf/ledger',$data,true);
        
//             $this->pdf->loadHtml($html);
//             $this->pdf->render();
//             $this->pdf->stream($file_name, array("Attachment"=>1));
//         }
//         else if($action_type == 'print')
//         {   
//             $data['transaction_details'] = $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
//             $data['opening_balance'] = $opening_balance;
//             $data['from_date'] = ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
//             $data['to_date'] = ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
//             $data['ledger_detail'] = $this->ledger_model->get_single_record($ledger_id);
//             $data['company_setting'] = $this->company_settings_model->get_company_records();

//             $this->load->view('report/print/ledger',$data);
//         }
//         else
//         {
            
          
            
//             $ledger_data['transaction_details'] = $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
//             $ledger_data['opening_balance'] = $opening_balance;
//             $ledger_data['from_date'] = ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
//             $ledger_data['to_date'] = ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
//             $ledger_data['ledger_detail'] = $this->ledger_model->get_single_record($ledger_id);
            
//             $data['transaction_details'] = $ledger_data['transaction_details'];
//             $data['ledger_report'] = $this->load->view('report/ajax/ledger',$ledger_data,true);

//             echo json_encode($data);
//         }
//     }
//     else
//     {
//         $data['company_setting'] = $this->company_settings_model->get_company_records();
//         $data['ledger_account'] = $this->ledger_model->get_records_by_account_group_category(array('Income','Assets','Liability','Expense'));
//         $this->load->view('report/ledger',$data);
//     }
// }

//sakhsi mam code
// public function ledger()
// {
//     if($this->input->server('REQUEST_METHOD') === 'POST')
//     {
//         $from_date      = ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
//         $to_date        = ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
//         $ledger_id      = $this->input->post('ledger_id');
//         $action_type    = $this->input->post('action_type');

//         // Get opening balance (transactions before from_date)
//         $opening_balance = $this->report_model->get_ledger_balance($ledger_id, $from_date);

//         // Initialize wallet variables
//         $wallet_balance = 0;
//         $total_wallet_payments = 0;
//         $total_wallet_deposits = 0;
//         $wallet_transactions = [];

//         // Get ledger details
//         $ledger_detail = $this->ledger_model->get_single_record($ledger_id);
        
//         // Check if this is a customer ledger
//         if($ledger_detail) {
//             $customer = $this->customer_model->get_single_record_by_ledger_id($ledger_detail->id);
//             if($customer) {
//                 $wallet_balance = (float)$customer->wallet_balance;
                
//                 // Get wallet transactions
//                 $this->db->select("th.*, 
//                     CASE 
//                         WHEN th.type = 'WALLET_ADD' THEN 'Wallet Deposit'
//                         WHEN th.type = 'WALLET_PAYMENT' THEN 'Wallet Payment'
//                         WHEN th.type = 'WALLET_DEDUCT' THEN 'Wallet Deduction'
//                         ELSE th.type
//                     END as display_type");
//                 $this->db->from('transaction_header th');
//                 $this->db->where('th.from_account', $ledger_detail->id);
//                 $this->db->where_in('th.type', ['WALLET_ADD', 'WALLET_PAYMENT', 'WALLET_DEDUCT']);
                
                                
//                                 if($from_date != '') {
//                                     $this->db->where('th.voucher_date >=', $from_date);
//                                 }
//                                 if($to_date != '') {
//                                     $this->db->where('th.voucher_date <=', $to_date);
//                                 }
                                
//                                 $this->db->order_by('th.voucher_date', 'ASC');
//                                 $wallet_transactions = $this->db->get()->result();
                                
//                                 // Calculate wallet transaction totals
//                 // Calculate wallet transaction totals
//                 foreach($wallet_transactions as $txn) {
//                     if($txn->type == 'WALLET_ADD') {
//                         $total_wallet_deposits += $txn->amount;
//                     } else {
//                         $total_wallet_payments += $txn->amount;
//                     }
//                 }
//             }
//         }

//         if($action_type == 'pdf')
//         {
//             $file_name = 'Ledger_Report_'.date("Y-m-d h-i-s").'.pdf';
            
           

//             $data['transaction_details'] = $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
//             $data['opening_balance'] = $opening_balance;
//             $data['from_date'] = ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
//             $data['to_date'] = ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
//             $data['ledger_detail'] = $ledger_detail;
//             $data['company_setting'] = $this->company_settings_model->get_company_records();
            
            
             
//             // Wallet data
//             $data['wallet_balance'] = $wallet_balance;
//             $data['total_wallet_payments'] = $total_wallet_payments;
//             $data['total_wallet_deposits'] = $total_wallet_deposits;
//             $data['wallet_transactions'] = $wallet_transactions;
            
//             $html = $this->load->view('report/pdf/ledger',$data,true);
        
//             $this->pdf->loadHtml($html);
//             $this->pdf->render();
//             $this->pdf->stream($file_name, array("Attachment"=>1));
//         }
//         else if($action_type == 'print')
//         {   
//             $data['transaction_details'] = $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
//             $data['opening_balance'] = $opening_balance;
//             $data['from_date'] = ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
//             $data['to_date'] = ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
//             $data['ledger_detail'] = $ledger_detail;
//             $data['company_setting'] = $this->company_settings_model->get_company_records();
            
//             // Wallet data
//             $data['wallet_balance'] = $wallet_balance;
//             $data['total_wallet_payments'] = $total_wallet_payments;
//             $data['total_wallet_deposits'] = $total_wallet_deposits;
//             $data['wallet_transactions'] = $wallet_transactions;

//             $this->load->view('report/print/ledger',$data);
//         }
//         else
//         {
//             $ledger_data['transaction_details'] = $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
//             $ledger_data['opening_balance'] = $opening_balance;
//             $ledger_data['from_date'] = ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
//             $ledger_data['to_date'] = ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
//             $ledger_data['ledger_detail'] = $ledger_detail;
            
//             // Wallet data
//             $ledger_data['wallet_balance'] = $wallet_balance;
//             $ledger_data['total_wallet_payments'] = $total_wallet_payments;
//             $ledger_data['total_wallet_deposits'] = $total_wallet_deposits;
//             $ledger_data['wallet_transactions'] = $wallet_transactions;

//             $data['transaction_details'] = $ledger_data['transaction_details'];
//             $data['ledger_report'] = $this->load->view('report/ajax/ledger',$ledger_data,true);

//             echo json_encode($data);
//         }
//     }
//     else
//     {
//         $data['company_setting'] = $this->company_settings_model->get_company_records();
//         $data['ledger_account'] = $this->ledger_model->get_records_by_account_group_category(array('Income','Assets','Liability','Expense'));
//         $this->load->view('report/ledger',$data);
//     }
// }

//ps code
public function ledger()
{
    if($this->input->server('REQUEST_METHOD') === 'POST')
    {
        $from_date      = ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
        $to_date        = ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
        $ledger_id      = $this->input->post('ledger_id');
        $action_type    = $this->input->post('action_type');

        // Get opening balance (transactions before from_date)
        $opening_balance = $this->report_model->get_ledger_balance($ledger_id, $from_date);

        // Initialize wallet variables
        $wallet_balance = 0;
        $total_wallet_payments = 0;
        $total_wallet_deposits = 0;
        $wallet_transactions = [];

        // IMPORTANT: Get ledger with party info (customer or supplier) using the NEW method
        $this->load->model('ledger_model');
        $ledger_detail = $this->ledger_model->get_ledger_with_party_info($ledger_id);
        
        // Check if this is a customer ledger for wallet (using party_data from the new method)
        if($ledger_detail && isset($ledger_detail->party_type) && $ledger_detail->party_type == 'Customer') {
            $customer = $ledger_detail->party_data;
            if($customer) {
                $wallet_balance = (float)$customer->wallet_balance;
                
                // Get wallet transactions
                $this->db->select("th.*, 
                    CASE 
                        WHEN th.type = 'WALLET_ADD' THEN 'Wallet Deposit'
                        WHEN th.type = 'WALLET_PAYMENT' THEN 'Wallet Payment'
                        WHEN th.type = 'WALLET_DEDUCT' THEN 'Wallet Deduction'
                        ELSE th.type
                    END as display_type");
                $this->db->from('transaction_header th');
                $this->db->where('th.from_account', $ledger_detail->id);
                $this->db->where_in('th.type', ['WALLET_ADD', 'WALLET_PAYMENT', 'WALLET_DEDUCT']);
                
                if($from_date != '') {
                    $this->db->where('th.voucher_date >=', $from_date);
                }
                if($to_date != '') {
                    $this->db->where('th.voucher_date <=', $to_date);
                }
                
                $this->db->order_by('th.voucher_date', 'ASC');
                $wallet_transactions = $this->db->get()->result();
                
                // Calculate wallet transaction totals
                foreach($wallet_transactions as $txn) {
                    if($txn->type == 'WALLET_ADD') {
                        $total_wallet_deposits += $txn->amount;
                    } else {
                        $total_wallet_payments += $txn->amount;
                    }
                }
            }
        }

        if($action_type == 'pdf')
        {
            $file_name = 'Ledger_Report_'.date("Y-m-d h-i-s").'.pdf';

            $data['transaction_details'] = $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
            $data['opening_balance'] = $opening_balance;
            $data['from_date'] = ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
            $data['to_date'] = ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
            $data['ledger_detail'] = $ledger_detail; // This now has party_type and party_data
            $data['company_setting'] = $this->company_settings_model->get_company_records();
            
            // Wallet data
            $data['wallet_balance'] = $wallet_balance;
            $data['total_wallet_payments'] = $total_wallet_payments;
            $data['total_wallet_deposits'] = $total_wallet_deposits;
            $data['wallet_transactions'] = $wallet_transactions;
            
            $html = $this->load->view('report/pdf/ledger',$data,true);
        
            $this->pdf->loadHtml($html);
            $this->pdf->render();
            $this->pdf->stream($file_name, array("Attachment"=>1));
        }
        else if($action_type == 'print')
        {   
            $data['transaction_details'] = $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
            $data['opening_balance'] = $opening_balance;
            $data['from_date'] = ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
            $data['to_date'] = ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
            $data['ledger_detail'] = $ledger_detail; // This now has party_type and party_data
            $data['company_setting'] = $this->company_settings_model->get_company_records();
            
            // Wallet data
            $data['wallet_balance'] = $wallet_balance;
            $data['total_wallet_payments'] = $total_wallet_payments;
            $data['total_wallet_deposits'] = $total_wallet_deposits;
            $data['wallet_transactions'] = $wallet_transactions;

            $this->load->view('report/print/ledger',$data);
        }
        else
        {
            $ledger_data['transaction_details'] = $this->report_model->ledger_report($from_date,$to_date,$ledger_id)->result();
            $ledger_data['opening_balance'] = $opening_balance;
            $ledger_data['from_date'] = ($from_date == '') ? '' : date('d-m-Y',strtotime($from_date));
            $ledger_data['to_date'] = ($to_date == '') ? '' : date('d-m-Y',strtotime($to_date));
            $ledger_data['ledger_detail'] = $ledger_detail; // This now has party_type and party_data
            
            // Wallet data
            $ledger_data['wallet_balance'] = $wallet_balance;
            $ledger_data['total_wallet_payments'] = $total_wallet_payments;
            $ledger_data['total_wallet_deposits'] = $total_wallet_deposits;
            $ledger_data['wallet_transactions'] = $wallet_transactions;

            $data['transaction_details'] = $ledger_data['transaction_details'];
            $data['ledger_report'] = $this->load->view('report/ajax/ledger',$ledger_data,true);

            echo json_encode($data);
        }
    }
    else
    {
        $data['company_setting'] = $this->company_settings_model->get_company_records();
        $data['ledger_account'] = $this->ledger_model->get_records_by_account_group_category(array('Income','Assets','Liability','Expense'));
        $this->load->view('report/ledger',$data);
    }
}

	public function gstr1()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
			$customer_id 		= $this->input->post('customer_id');
			$action_type 		= $this->input->post('action_type');
			$invoice_type 		= $this->input->post('invoice_type');
			

			if($action_type == 'export')
			{
				$file_name 	= 'GSTR1_Report_'.date("Y-m-d h-i-s").'.csv';

				if($invoice_type == B2B)
					$query 			= $this->report_model->gstr1_b2b_report($from_date,$to_date,$customer_id,$invoice_type);
				else if($invoice_type == B2CL)
					$query 			= $this->report_model->gstr1_b2cl_report($from_date,$to_date,$customer_id,$invoice_type);
				else if($invoice_type == B2CS)
					$query 			= $this->report_model->gstr1_b2cs_report($from_date,$to_date,$customer_id,$invoice_type);
				else if($invoice_type == CDNR)
					$query 			= $this->report_model->gstr1_cdnr_report($from_date,$to_date,$customer_id,$invoice_type);
				else if($invoice_type == CDNUR)
					$query 			= $this->report_model->gstr1_cdnur_report($from_date,$to_date,$customer_id,$invoice_type);

		    

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 							= 'GSTR1_Report_'.date("Y-m-d h-i-s").'.pdf';

				if($invoice_type == B2B)
					$data['sales'] 		= $this->report_model->gstr1_b2b_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == B2CL)
					$data['sales'] 		= $this->report_model->gstr1_b2cl_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == B2CS)
					$data['sales'] 		= $this->report_model->gstr1_b2cs_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == CDNR)
					$data['sales'] 		= $this->report_model->gstr1_cdnr_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == CDNUR)
					$data['sales'] 		= $this->report_model->gstr1_cdnur_report($from_date,$to_date,$customer_id,$invoice_type)->result();

				
				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$data['company_setting']= $this->company_settings_model->get_company_records();

				$html = '';

				if($invoice_type == B2B)
					$html = $this->load->view('report/pdf/gstr1_b2b',$data,true);
				else if($invoice_type == B2CL)
					$html = $this->load->view('report/pdf/gstr1_b2cl',$data,true);
				else if($invoice_type == B2CS)
					$html = $this->load->view('report/pdf/gstr1_b2cs',$data,true);
				else if($invoice_type == CDNR)
					$html = $this->load->view('report/pdf/gstr1_cdnr',$data,true);
				else if($invoice_type == CDNUR)
					$html = $this->load->view('report/pdf/gstr1_cdnur',$data,true);
				
				// $html 									= $this->load->view('report/pdf/gstr1',$data,true);

				
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				if($invoice_type == B2B)
					$sale_data['sales'] = $this->report_model->gstr1_b2b_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == B2CL)
					$sale_data['sales'] = $this->report_model->gstr1_b2cl_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == B2CS)
					$sale_data['sales'] = $this->report_model->gstr1_b2cs_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == CDNR)
					$sale_data['sales'] = $this->report_model->gstr1_cdnr_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == CDNUR)
					$sale_data['sales'] = $this->report_model->gstr1_cdnur_report($from_date,$to_date,$customer_id,$invoice_type)->result();

				$sale_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$sale_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$sale_data['company_setting'] = $this->company_settings_model->get_company_records();

				if($invoice_type == B2B)
					$this->load->view('report/print/gstr1_b2b',$sale_data);
				else if($invoice_type == B2CL)
					$this->load->view('report/print/gstr1_b2cl',$sale_data);
				else if($invoice_type == B2CS)
					$this->load->view('report/print/gstr1_b2cs',$sale_data);
				else if($invoice_type == CDNR)
					$this->load->view('report/print/gstr1_cdnr',$sale_data);
				else if($invoice_type == CDNUR)
					$this->load->view('report/print/gstr1_cdnur',$sale_data);
			}
			else
			{
				if($invoice_type == B2B)
					$sale_data['sales'] = $this->report_model->gstr1_b2b_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == B2CL)
					$sale_data['sales'] = $this->report_model->gstr1_b2cl_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == B2CS)
					$sale_data['sales'] = $this->report_model->gstr1_b2cs_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == CDNR)
					$sale_data['sales'] = $this->report_model->gstr1_cdnr_report($from_date,$to_date,$customer_id,$invoice_type)->result();
				else if($invoice_type == CDNUR)
					$sale_data['sales'] = $this->report_model->gstr1_cdnur_report($from_date,$to_date,$customer_id,$invoice_type)->result();


				if($invoice_type == B2B){
					$data['gstr1'] 				= $this->load->view('report/ajax/gstr1_b2b',$sale_data,true);
					echo json_encode($data);
				}
				else if($invoice_type == B2CL){
					$data['gstr1'] 				= $this->load->view('report/ajax/gstr1_b2cl',$sale_data,true);
					echo json_encode($data);
				}
				else if($invoice_type == B2CS){
					$data['gstr1'] 				= $this->load->view('report/ajax/gstr1_b2cs',$sale_data,true);
					echo json_encode($data);
				}
				else if($invoice_type == CDNR){
					$data['gstr1'] 				= $this->load->view('report/ajax/gstr1_cdnr',$sale_data,true);
					echo json_encode($data);
				}
				else if($invoice_type == CDNUR){
					$data['gstr1'] 				= $this->load->view('report/ajax/gstr1_cdnur',$sale_data,true);
					echo json_encode($data);
				}
			}
		}
		else
		{
			$data['customers'] 			=	$this->customer_model->get_records();
			$data['sales'] 					=	$this->report_model->gstr1_report()->result();
			
			$this->load->view('report/gstr1',$data);
		}
	}

	public function gstr2()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date 			= ($this->input->post('from_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date 				= ($this->input->post('to_date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('to_date')));
			$supplier_id 		= $this->input->post('supplier_id');
			$action_type 		= $this->input->post('action_type');

			if($action_type == 'export')
			{
				$file_name 	= 'GSTR2_Report_'.date("Y-m-d h-i-s").'.csv';
				$query 			= $this->report_model->gstr2_report($from_date,$to_date,$supplier_id);

		    $this->load->dbutil();

		    $data 			= $this->dbutil->csv_from_result($query);
		    
		    $this->load->helper('download');
		    force_download($file_name, $data);  
		    exit();
			}
			else if($action_type == 'pdf')
			{
				$file_name 							= 'GSTR2_Report_'.date("Y-m-d h-i-s").'.pdf';
				$data['purchases'] 			= $this->report_model->gstr2_report($from_date,$to_date,$supplier_id)->result();
				
				$data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html = $this->load->view('report/pdf/gstr2',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
			else if($action_type == 'print')
			{	
				
				$sale_data['purchases'] = $this->report_model->gstr2_report($from_date,$to_date,$supplier_id)->result();
				
				$sale_data['from_date'] 			= ($from_date == '') ? '' : date('d-m-Y', strtotime($from_date));
				$sale_data['to_date'] 				= ($to_date == '') ? '' : date('d-m-Y', strtotime($to_date));
				$sale_data['company_setting'] = $this->company_settings_model->get_company_records();

				$this->load->view('report/print/gstr2',$sale_data);
			}
			else
			{
				
				$sale_data['purchases'] = $this->report_model->gstr2_report($from_date,$to_date,$supplier_id)->result();
				
				$data['gstr2'] 				= $this->load->view('report/ajax/gstr2',$sale_data,true);
				echo json_encode($data);
			
			}
		}
		else
		{
			$data['suppliers'] 			=	$this->supplier_model->get_records();
			$data['gstr2'] 					=	$this->report_model->gstr2_report()->result();
			
			$this->load->view('report/gstr2',$data);
		}
	}

	public function transaction()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$from_date  = ($this->input->post('from_date') == '') 
                        ? '' 
                        : date('Y-m-d', strtotime($this->input->post('from_date')));
			$to_date    = ($this->input->post('to_date') == '') 
                        ? '' 
                        : date('Y-m-d', strtotime($this->input->post('to_date')));
      $module       = $this->input->post('module'); 
      $ledger_id    = $this->input->post('ledger_id'); 
      $type         = $this->input->post('type'); 
      $reference_no = $this->input->post('reference_no'); 
      $narration    = $this->input->post('narration'); 
      $mode         = $this->input->post('mode'); 

      $transaction_data['transactions'] 	= $this->report_model->transaction_report($from_date,$to_date,$module,$ledger_id,$type,$reference_no,$narration,$mode)->result();
      $data['transaction'] = $this->load->view('report/ajax/transaction',$transaction_data,true);

      echo json_encode($data);
		}
		else
		{
			$data['transactions']   =	$this->report_model->transaction_report()->result();
      $data['ledger_account'] = $this->ledger_model->get_records_by_account_group_category(array('Income','Assets','Liability','Expense'));
			$this->load->view('report/transaction',$data);
		}
	}

  

  //Balance sheet

  public function balance_sheet($action_type = null)
	{
		if($action_type != null)
		{
			if($action_type == 'pdf')
			{
				
				$file_name 							= 'Balance_Sheet_Report_'.date("Y-m-d h-i-s").'.pdf';

				// $data['customers'] 			= $this->customer_model->get_records();
        $data['bank_accounts']  = $this->bank_account_model->get_records();
        $data['sales']          = $this->sale_model->get_sale_records();
        $data['purchases']      = $this->purchase_model->get_purchase_records();
        // Fetch other module data as needed

        // Process data to create balance sheet
        $data['cash_in_hand']       = $this->calculate_cash_in_hand();
        $data['bank_account_total'] = $this->calculate_bank_account_total($data['bank_accounts']);
        $data['trade_receivables']  = $this->calculate_trade_receivables($data['sales']);
        $data['other_receivables']  = $this->calculate_other_receivables();
        $data['inventory']          = $this->calculate_inventory();
        $data['prepaid_expenses']   = $this->calculate_prepaid_expenses();
        $data['pdc_receivable']     = $this->calculate_pdc_receivable();

        $data['total_current_assets'] = $data['cash_in_hand'] + $data['bank_account_total'] + $data['trade_receivables'] + $data['other_receivables'] + $data['inventory'] + $data['prepaid_expenses'] + $data['pdc_receivable'];

        $data['property_plant_equipment'] = $this->calculate_property_plant_equipment();
        $data['intangible_assets']        = $this->calculate_intangible_assets();
        $data['investments']              = $this->calculate_investments();
        $data['other_long_term_assets']   = $this->calculate_other_long_term_assets();

        $data['total_non_current_assets'] = $data['property_plant_equipment'] + $data['intangible_assets'] + $data['investments'] + $data['other_long_term_assets'];

        $data['total_assets']             = $data['total_current_assets'] + $data['total_non_current_assets'];

        $data['trade_payables']           = $this->calculate_trade_payables($data['purchases']);
        $data['other_payables']           = $this->calculate_other_payables();
        $data['pdc_payable']              = $this->calculate_pdc_payable();
        $data['accrued_expenses']         = $this->calculate_accrued_expenses();
        $data['short_term_loans']         = $this->calculate_short_term_loans();

        $data['total_current_liabilities']    = $data['trade_payables'] + $data['other_payables'] + $data['pdc_payable'] + $data['accrued_expenses'] + $data['short_term_loans'];

        $data['long_term_loans']              = $this->calculate_long_term_loans();
        $data['deferred_tax_liabilities']     = $this->calculate_deferred_tax_liabilities();
        $data['other_long_term_liabilities']  = $this->calculate_other_long_term_liabilities();

        $data['total_non_current_liabilities'] = $data['long_term_loans'] + $data['deferred_tax_liabilities'] + $data['other_long_term_liabilities'];

        $data['total_liabilities']              = $data['total_current_liabilities'] + $data['total_non_current_liabilities'];

        $data['share_capital']                  = $this->calculate_share_capital();
        $data['retained_earnings']              = $this->calculate_retained_earnings();
        $data['additional_paid_in_capital']     = $this->calculate_additional_paid_in_capital();
        $data['other_reserves']                 = $this->calculate_other_reserves();

        $data['total_equity']                   = $data['share_capital'] + $data['retained_earnings'] + $data['additional_paid_in_capital'] + $data['other_reserves'];

        $data['total_liabilities_equity']       = $data['total_liabilities'] + $data['total_equity'];
				$data['company_setting']= $this->company_settings_model->get_company_records();
				
				$html 									= $this->load->view('report/pdf/balance_sheet',$data,true);
			
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream($file_name, array("Attachment"=>1));
			}
		}
		else
		{
			$data['bank_accounts']  = $this->bank_account_model->get_records();
      $data['sales']          = $this->sale_model->get_sale_records();
      // $data['purchases']      = $this->purchase_model->get_purchase_records();
      // Fetch other module data as needed

      $data['trade_payables']           = $this->purchase_model->get_trade_payables();
      $data['pdc_payable']              = $this->pdc_payment_model->get_pdc_payable();
      $data['accrued_expenses']         = $this->expense_model->get_total_expenses();

      $data['total_current_liabilities']     = $data['trade_payables'] + $data['pdc_payable'] + $data['accrued_expenses'];

      $cash_ledger = $this->ledger_model->get_single_record(CASH_GROUP_LEDGER);

      $data['cash_in_hand'] = $cash_ledger->closing_balance;

      // Process data to create balance sheet
      // $data['cash_in_hand']       = $this->calculate_cash_in_hand();
      $data['bank_account_total'] = $this->calculate_bank_account_total($data['bank_accounts']);
      $data['trade_receivables']  = $this->calculate_trade_receivables($data['sales']);
      $data['other_receivables']  = $this->calculate_other_receivables();
      $data['inventory']          = $this->calculate_inventory();
      $data['prepaid_expenses']   = $this->calculate_prepaid_expenses();
      $data['pdc_receivable']     = $this->calculate_pdc_receivable();

      // $data['total_current_assets'] = $data['cash_in_hand'] + $data['bank_account_total'] + $data['trade_receivables'] + $data['other_receivables'] + $data['inventory'] + $data['prepaid_expenses'] + $data['pdc_receivable'];

      $data['property_plant_equipment'] = $this->calculate_property_plant_equipment();
      $data['intangible_assets']        = $this->calculate_intangible_assets();
      $data['investments']              = $this->calculate_investments();
      $data['other_long_term_assets']   = $this->calculate_other_long_term_assets();

      $data['total_non_current_assets'] = $data['property_plant_equipment'] + $data['intangible_assets'] + $data['investments'] + $data['other_long_term_assets'];

      // $data['total_assets']             = $data['total_current_assets'] + $data['total_non_current_assets'];

      // $data['trade_payables']           = $this->calculate_trade_payables($data['purchases']);
      $data['other_payables']           = $this->calculate_other_payables();
      // $data['pdc_payable']              = $this->calculate_pdc_payable();
      // $data['accrued_expenses']         = $this->calculate_accrued_expenses();
      $data['short_term_loans']         = $this->calculate_short_term_loans();

      // $data['total_current_liabilities']    = $data['trade_payables'] + $data['other_payables'] + $data['pdc_payable'] + $data['accrued_expenses'] + $data['short_term_loans'];

      $data['long_term_loans']              = $this->calculate_long_term_loans();
      $data['deferred_tax_liabilities']     = $this->calculate_deferred_tax_liabilities();
      $data['other_long_term_liabilities']  = $this->calculate_other_long_term_liabilities();

      $data['total_non_current_liabilities'] = $data['long_term_loans'] + $data['deferred_tax_liabilities'] + $data['other_long_term_liabilities'];

      $data['total_liabilities']              = $data['total_current_liabilities'] + $data['total_non_current_liabilities'];

      $data['share_capital']                  = $this->calculate_share_capital();
      $data['retained_earnings']              = $this->calculate_retained_earnings();
      $data['additional_paid_in_capital']     = $this->calculate_additional_paid_in_capital();
      $data['other_reserves']                 = $this->calculate_other_reserves();

      $data['total_equity']                   = $data['share_capital'] + $data['retained_earnings'] + $data['additional_paid_in_capital'] + $data['other_reserves'];

      $data['total_liabilities_equity']       = $data['total_liabilities'] + $data['total_equity'];

      // Load the view with the data
      $this->load->view('report/balance_sheet', $data);
		}
	}

  // private function calculate_cash_in_hand() {
  //     // Calculate cash in hand
  //     return 0; // Replace with actual calculation
  // }

  private function calculate_bank_account_total($bank_accounts) {
      $total = 0;
      foreach ($bank_accounts as $account) {
          $total += $account->opening_balance; // Assuming 'balance' is the column name
      }
      return $total;
  }

  private function calculate_trade_receivables($sales) {
      $total = 0;
      foreach ($sales as $sale) {
          $total += $sale->total; // Assuming 'total' is the column name
      }
      return $total;
  }

  private function calculate_other_receivables() {
      // Calculate other receivables
      return 0; // Replace with actual calculation
  }

  private function calculate_inventory() {
      // Calculate inventory
      return 0; // Replace with actual calculation
  }

  private function calculate_prepaid_expenses() {
      // Calculate prepaid expenses
      return 0; // Replace with actual calculation
  }

  private function calculate_pdc_receivable() {
      // Calculate PDC receivable
      return 0; // Replace with actual calculation
  }

  private function calculate_property_plant_equipment() {
      // Calculate property, plant, and equipment
      return 0; // Replace with actual calculation
  }

  private function calculate_intangible_assets() {
      // Calculate intangible assets
      return 0; // Replace with actual calculation
  }

  private function calculate_investments() {
      // Calculate investments
      return 0; // Replace with actual calculation
  }

  private function calculate_other_long_term_assets() {
      // Calculate other long-term assets
      return 0; // Replace with actual calculation
  }

  // private function calculate_trade_payables($purchases) {
  //     $total = 0;
  //     foreach ($purchases as $purchase) {
  //         $total += $purchase->total; // Assuming 'total' is the column name
  //     }
  //     return $total;
  // }

  private function calculate_other_payables() {
      // Calculate other payables
      return 0; // Replace with actual calculation
  }

  // private function calculate_pdc_payable() {
  //     // Calculate PDC payable
  //     return 0; // Replace with actual calculation
  // }

  // private function calculate_accrued_expenses() {
  //     // Calculate accrued expenses
  //     return 0; // Replace with actual calculation
  // }

  private function calculate_short_term_loans() {
      // Calculate short-term loans
      return 0; // Replace with actual calculation
  }

  private function calculate_long_term_loans() {
      // Calculate long-term loans
      return 0; // Replace with actual calculation
  }

  private function calculate_deferred_tax_liabilities() {
      // Calculate deferred tax liabilities
      return 0; // Replace with actual calculation
  }

  private function calculate_other_long_term_liabilities() {
      // Calculate other long-term liabilities
      return 0; // Replace with actual calculation
  }

  private function calculate_share_capital() {
      // Calculate share capital
      return 0; // Replace with actual calculation
  }

  private function calculate_retained_earnings() {
      // Calculate retained earnings
      return 0; // Replace with actual calculation
  }

  private function calculate_additional_paid_in_capital() {
      // Calculate additional paid-in capital
      return 0; // Replace with actual calculation
  }

  private function calculate_other_reserves() {
      // Calculate other reserves
      return 0; // Replace with actual calculation
  }

	public function daily()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$date 			= ($this->input->post('date') == '') ? '' : date('Y-m-d', strtotime($this->input->post('date')));
			$customer_id 			= $this->input->post('customer_id');
			$warehouse_id 		= $this->input->post('warehouse_id');
      $order_time 			= $this->input->post('order_time');
			$report_type 			= $this->input->post('report_type');
			$action_type 			= $this->input->post('action_type');

			if($report_type == 'customer_wise')
			{
				if($action_type == 'pdf')
				{
					$file_name 							= 'Daily_Report_'.date("Y-m-d h-i-s").'-'.strtoupper($order_time).'.pdf';
					$data['date']           = $date;
					$data['order_time']     = $order_time;
					$data['warehouse_id']   = $warehouse_id;
					$data['customers'] 			= $this->report_model->get_customers_with_orders_by_date_and_time($date, $warehouse_id, $customer_id, $order_time)->result();
					

					$data['company_setting']			= $this->company_settings_model->get_company_records();
					$html 												= $this->load->view('report/pdf/daily',$data,true);
				
					$this->pdf->loadHtml($html);
					$this->pdf->render();
					$this->pdf->stream($file_name, array("Attachment"=>1));
				}
				else
				{
						// Return the respective view based on order_time
						// $data['sales'] 					= $this->report_model->daily_report($date,$customer_id,$order_time)->result();
						$data['date']           = $date;
						$data['order_time']     = $order_time;
						$data['warehouse_id']   = $warehouse_id;
						$data['customers'] 			= $this->report_model->get_customers_with_orders_by_date_and_time($date, $warehouse_id, $customer_id, $order_time)->result();
						$data['customer_query'] = $this->db->last_query();
						$data['daily_report'] 	= $this->load->view('report/ajax/daily',$data,true);

						echo json_encode($data);
				}
			}
			else
			{
				if($action_type == 'pdf')
				{
					$file_name 							= 'Daily_Report_Total_Quantity_'.date("Y-m-d h-i-s").'-'.strtoupper($order_time).'.pdf';
					$data['date']           = $date;
					$data['order_time']     = $order_time;
					$data['warehouse_id']   = $warehouse_id;
					$data['customers'] 			= $this->report_model->get_customers_with_orders_by_date_and_time($date, $warehouse_id, $customer_id, $order_time)->result();
					$data['warehouses'] 		= $this->report_model->get_warehouses_with_orders_by_date_and_time($date,$order_time)->result();
					

					$data['company_setting']			= $this->company_settings_model->get_company_records();
					$html 												= $this->load->view('report/pdf/daily_total_quantity',$data,true);
				
					$this->pdf->loadHtml($html);
					$this->pdf->render();
					$this->pdf->stream($file_name, array("Attachment"=>1));
				}
				else
				{
						// Return the respective view based on order_time
						// $data['sales'] 					= $this->report_model->daily_report($date,$customer_id,$order_time)->result();
						$data['date']           = $date;
						$data['order_time']     = $order_time;
						$data['warehouse_id']   = $warehouse_id;
						$data['customers'] 			= $this->report_model->get_customers_with_orders_by_date_and_time($date, $warehouse_id, $customer_id, $order_time)->result();
						$data['warehouses'] 		= $this->report_model->get_warehouses_with_orders_by_date_and_time($date,$order_time)->result();
						$data['daily_report'] 	= $this->load->view('report/ajax/daily_total_quantity',$data,true);

						echo json_encode($data);
				}
			}
		}
		else
		{	
      $data['customers']			  = $this->customer_model->get_records();
			$data['warehouses']			  = $this->warehouse_model->get_records();
		
			$this->load->view('report/daily',$data);
		}
	}


}