<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->db->query('SET SESSION sql_mode = ""');
        $this->db->query('SET SESSION sql_mode =
                            REPLACE(
                                REPLACE(
                                    REPLACE(@@sql_mode,"ONLY_FULL_GROUP_BY,", ""),
                                ",ONLY_FULL_GROUP_BY", ""),
                            "ONLY_FULL_GROUP_BY", "")'
                    );
    }

    public function stock_value_report()
    {
      return $this->db->select('*')
                      ->from('product_detail_view')
                      ->where('available_quantity IS NOT NULL', null, false) 
                      ->where('manage_inventory', MANAGE_INVENTORY_YES)
                      ->get()
                      ->result();    
    }

    /*public function closing_stock_report($pid,$to_date = null)
    {
      // $to_date = ($to_date == null) ?  date('Y-m-d') : date('Y-m-d', strtotime($to_date . ' +1 day'));
      $to_date = ($to_date == null) ?  date('Y-m-d') : date('Y-m-d', strtotime($to_date));

      $data = array(); 

      $this->db->select('
                          p.pid, 
                          SUM(pdi.quantity) as total_quantity,
                          SUM(wp.selling_price * pdi.quantity) as stock_value,
                          SUM(pdi.cost * pdi.quantity) as cost_value
                        ');
      $this->db->from('purchase_delivery_items pdi');
      $this->db->join('product p', 'p.id = pdi.product_id', 'left');
      $this->db->join('purchase_delivery pd', 'pd.id = pdi.purchase_delivery_id', 'left');

      $this->db->join('warehouse_products wp','wp.batch_no = pdi.batch_no AND wp.product_id = pdi.product_id','left');
     
      $this->db->where('p.pid',$pid);
      $this->db->where('p.manage_inventory', MANAGE_INVENTORY_YES);

      $this->db->where('pd.delivery_date <=',$to_date);
      $this->db->group_by('p.pid');

      $query = $this->db->get();

      $data['purchase_delivery_items']        = ($query->row() != null) ? $query->row()->total_quantity : 0;
      $data['purchase_delivery_items_value']  = ($query->row() != null) ? $query->row()->stock_value : 0;
      $data['purchase_delivery_items_cost_value']  = ($query->row() != null) ? $query->row()->cost_value : 0;

      $this->db->select('
                          p.pid, 
                          SUM(prdi.quantity) as total_quantity,
                          SUM(wp1.selling_price * prdi.quantity) as stock_value,
                          SUM(wp1.cost * prdi.quantity) as cost_value
                        ');
      $this->db->from('purchase_return_delivery_items prdi');
      $this->db->join('product p','p.id = prdi.product_id','left');
      $this->db->join('purchase_return_delivery pd','pd.id = prdi.purchase_return_delivery_id','LEFT');

      $this->db->join('warehouse_products wp1','wp1.product_id = prdi.product_id','left'); 
      $this->db->join('warehouse_products wp2','wp2.batch_no = prdi.batch_no','left'); 
      $this->db->join('warehouse_products wp3','wp3.warehouse_id = prdi.warehouse_id','left'); 
      $this->db->where('p.pid',$pid);
      $this->db->where('p.manage_inventory', MANAGE_INVENTORY_YES);

      $this->db->where('pd.delivery_date <=',$to_date);

      $this->db->group_by('p.pid');

      $query = $this->db->get();

      $data['purchase_return_delivery_items']         = ($query->row() != null) ? $query->row()->total_quantity : 0;
      $data['purchase_return_delivery_items_value']   = ($query->row() != null) ? $query->row()->stock_value : 0;
      $data['purchase_return_delivery_items_cost_value']   = ($query->row() != null) ? $query->row()->cost_value : 0;

      $this->db->select('
                          p.pid, 
                          SUM(si.quantity) as total_quantity,
                          SUM(wp.selling_price * si.quantity) as stock_value,
                          SUM(wp.cost * (si.quantity)) as cost_value
                        ');
      $this->db->from('sale_items si');
      $this->db->join('product p','p.id = si.product_id','left');
      $this->db->join('sale s','s.id = si.sale_id');

      $this->db->join('warehouse_products wp','wp.id = si.warehouse_product_id','left');
      $this->db->where('s.delete_status',0);
      $this->db->where('p.pid',$pid);
      $this->db->where('p.manage_inventory', MANAGE_INVENTORY_YES);

      $this->db->where('s.invoice_date <=',$to_date);
      $this->db->group_by('p.pid');

      $query = $this->db->get();

      $data['sale_items']         = ($query->row() != null) ? $query->row()->total_quantity : 0;
      $data['sale_items_value']   = ($query->row() != null) ? $query->row()->stock_value : 0;
      $data['sale_items_cost_value']   = ($query->row() != null) ? $query->row()->cost_value : 0;
      
      $this->db->select('
                          p.pid, 
                          SUM(srdi.quantity) as total_quantity,
                          SUM(wp.selling_price * srdi.quantity) as stock_value,
                          SUM(wp.cost * srdi.quantity) as cost_value
                        ');
      $this->db->from('sales_return_delivery_items srdi');
      $this->db->join('product p','p.id = srdi.product_id','left');
      $this->db->join('sales_return_delivery srd','srd.id = srdi.sales_return_delivery_id','LEFT');

      $this->db->join('warehouse_products wp','wp.id = srdi.warehouse_product_id','left'); 
      $this->db->where('p.pid',$pid);
      $this->db->where('p.manage_inventory', MANAGE_INVENTORY_YES);

      $this->db->where('srd.delivery_date <=',$to_date);
      $this->db->group_by('p.pid');

      $query = $this->db->get();

      $data['sales_return_delivery_items']          = ($query->row() != null) ? $query->row()->total_quantity : 0;
      $data['sales_return_delivery_items_value']    = ($query->row() != null) ? $query->row()->stock_value : 0;
      $data['sales_return_delivery_items_cost_value']    = ($query->row() != null) ? $query->row()->cost_value : 0;
      
    

      $this->db->select('
                          p.pid, 
                          SUM(s.quantity) as total_quantity,
                          SUM(wp.selling_price * s.quantity) as stock_value,
                          SUM(wp.cost * s.quantity) as cost_value
                        ');
      $this->db->from('stock s');
      $this->db->join('warehouse_products wp','wp.product_id = s.product_id','left');
      $this->db->join('warehouse_products wp1','wp1.warehouse_id = s.warehouse_id','left');
      $this->db->join('warehouse_products wp2','wp2.batch_no = s.batch_no','left');
      $this->db->join('product p','p.id = wp.product_id','left');
     
      $this->db->where('s.delete_status',0);
      $this->db->where('s.entry_type','in');
      $this->db->where('p.pid',$pid);
      $this->db->where('p.manage_inventory', MANAGE_INVENTORY_YES);

      $this->db->where('s.created_date <=',$to_date);
      $this->db->group_by('p.pid');

      $query = $this->db->get();

      $data['stock_in']         = ($query->row() != null) ? $query->row()->total_quantity : 0;
      $data['stock_in_value']   = ($query->row() != null) ? $query->row()->stock_value : 0;
      $data['stock_in_cost_value']   = ($query->row() != null) ? $query->row()->cost_value : 0;

      $this->db->select('
                          p.pid, 
                          SUM(s.quantity) as total_quantity,
                          SUM(wp.selling_price * s.quantity) as stock_value,
                          SUM(wp.cost * s.quantity) as cost_value
                        ');
      $this->db->from('stock s');
      $this->db->join('warehouse_products wp','wp.product_id = s.product_id','left');
      $this->db->join('warehouse_products wp1','wp1.warehouse_id = s.warehouse_id','left');
      $this->db->join('warehouse_products wp2','wp2.batch_no = s.batch_no','left');
      $this->db->join('product p','p.id = wp.product_id','left');

   
      $this->db->where('s.delete_status',0);
      $this->db->where('s.entry_type','out');
      $this->db->where('p.pid',$pid);
      $this->db->where('p.manage_inventory', MANAGE_INVENTORY_YES);

      $this->db->where('s.created_date <=',$to_date);
      $this->db->group_by('p.pid');

      $query = $this->db->get();

      $data['stock_out']          = ($query->row() != null) ? $query->row()->total_quantity : 0;
      $data['stock_out_value']    = ($query->row() != null) ? $query->row()->stock_value : 0;
      $data['stock_out_cost_value']    = ($query->row() != null) ? $query->row()->cost_value : 0;

      // print_r($data);
      // exit;

      return $data;
    }*/
public function closing_stock_report($pid, $to_date = null)
{
    $to_date = ($to_date == null) ? date('Y-m-d') : date('Y-m-d', strtotime($to_date));

    $data = array(
        'purchase_delivery_items' => 0,
        'purchase_delivery_items_cost_value' => 0,

        'purchase_return_delivery_items' => 0,
        'purchase_return_delivery_items_cost_value' => 0,

        'sale_items' => 0,
        'sale_items_cost_value' => 0,

        'sales_return_delivery_items' => 0,
        'sales_return_delivery_items_cost_value' => 0,

        'stock_in' => 0,
        'stock_in_cost_value' => 0,

        'stock_out' => 0,
        'stock_out_cost_value' => 0
    );

    // ✅ 1. Purchase
    $sql = "SELECT 
                SUM(pdi.quantity) as qty,
                SUM(pdi.cost * pdi.quantity) as val
            FROM purchase_delivery_items pdi
            INNER JOIN purchase_delivery pd ON pd.id = pdi.purchase_delivery_id
            INNER JOIN product p ON p.id = pdi.product_id
            WHERE p.pid = '$pid' AND pd.delivery_date <= '$to_date'";

    $row = $this->db->query($sql)->row();
    $data['purchase_delivery_items'] = floatval($row->qty ?? 0);
    $data['purchase_delivery_items_cost_value'] = floatval($row->val ?? 0);

    // ✅ 2. Purchase Return
    $sql = "SELECT 
                SUM(prdi.quantity) as qty,
                SUM(prdi.cost * prdi.quantity) as val
            FROM purchase_return_delivery_items prdi
            INNER JOIN purchase_return_delivery prd ON prd.id = prdi.purchase_return_delivery_id
            INNER JOIN product p ON p.id = prdi.product_id
            WHERE p.pid = '$pid' AND prd.delivery_date <= '$to_date'";

    $row = $this->db->query($sql)->row();
    $data['purchase_return_delivery_items'] = floatval($row->qty ?? 0);
    $data['purchase_return_delivery_items_cost_value'] = floatval($row->val ?? 0);

    // ✅ 3. Sale
    $sql = "SELECT 
                SUM(si.quantity) as qty,
                SUM(si.cost * si.quantity) as val
            FROM sale_items si
            INNER JOIN sale s ON s.id = si.sale_id
            INNER JOIN product p ON p.id = si.product_id
            WHERE p.pid = '$pid'
            AND s.invoice_date <= '$to_date'
            AND s.delete_status = 0";

    $row = $this->db->query($sql)->row();
    $data['sale_items'] = floatval($row->qty ?? 0);
    $data['sale_items_cost_value'] = floatval($row->val ?? 0);

    // ✅ 4. Sales Return
    $sql = "SELECT 
                SUM(srdi.quantity) as qty,
                SUM(srdi.cost * srdi.quantity) as val
            FROM sales_return_delivery_items srdi
            INNER JOIN sales_return_delivery srd ON srd.id = srdi.sales_return_delivery_id
            INNER JOIN product p ON p.id = srdi.product_id
            WHERE p.pid = '$pid' AND srd.delivery_date <= '$to_date'";

    $row = $this->db->query($sql)->row();
    $data['sales_return_delivery_items'] = floatval($row->qty ?? 0);
    $data['sales_return_delivery_items_cost_value'] = floatval($row->val ?? 0);

    // ✅ 5. Stock In
    $sql = "SELECT 
                SUM(quantity) as qty,
                SUM(product_cost * quantity) as val
            FROM stock s
            INNER JOIN product p ON p.id = s.product_id
            WHERE p.pid = '$pid'
            AND s.entry_type = 'in'
            AND s.created_date <= '$to_date'
            AND s.delete_status = 0";

    $row = $this->db->query($sql)->row();
    $data['stock_in'] = floatval($row->qty ?? 0);
    $data['stock_in_cost_value'] = floatval($row->val ?? 0);

    // ✅ 6. Stock Out
    $sql = "SELECT 
                SUM(quantity) as qty,
                SUM(product_cost * quantity) as val
            FROM stock s
            INNER JOIN product p ON p.id = s.product_id
            WHERE p.pid = '$pid'
            AND s.entry_type = 'out'
            AND s.created_date <= '$to_date'
            AND s.delete_status = 0";

    $row = $this->db->query($sql)->row();
    $data['stock_out'] = floatval($row->qty ?? 0);
    $data['stock_out_cost_value'] = floatval($row->val ?? 0);

    return $data;
}
    /*public function inventory_product_added_report($pid,$to_date = null,$from_date = null,$expiry_date = null)
    {
      $from_date    = ($from_date != null) ?  date('Y-m-d', strtotime($from_date . ' -1 day')) : null;
      $expiry_date  = ($expiry_date != null) ?  date('Y-m-d', strtotime($expiry_date)) : null;
      $to_date      = ($to_date == null) ?  date('Y-m-d') : date('Y-m-d', strtotime($to_date . ' +1 day'));

      $data = array(); 

      $this->db->select('p.pid, SUM(pdi.quantity) as total_quantity,SUM(wp.selling_price * pdi.quantity) as stock_value, pdi.batch_no');
      $this->db->from('purchase_delivery_items pdi');
      $this->db->join('product p', 'p.id = pdi.product_id', 'left');
      $this->db->join('purchase_delivery pd', 'pd.id = pdi.purchase_delivery_id', 'left');

      $this->db->join('warehouse_products wp','wp.batch_no = pdi.batch_no AND wp.product_id = pdi.product_id','left');
     
      $this->db->where('p.pid',$pid);

      if($expiry_date != null)
        $this->db->where('pdi.expiry_date',$expiry_date);

      if($from_date != null)
        $this->db->where('pd.created_date >=',$from_date);

      $this->db->where('pd.created_date <=',$to_date);
      $this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');
      $this->db->group_by('p.pid, wp.batch_no');
      

      $query = $this->db->get();

     
      return $query->result();
    }*/
public function inventory_product_added_report($pid, $to_date = null, $from_date = null, $expiry_date = null)
{
    $from_date = ($from_date != null) ? date('Y-m-d', strtotime($from_date . ' -1 day')) : null;
    $expiry_date = ($expiry_date != null) ? date('Y-m-d', strtotime($expiry_date)) : null;
    $to_date = ($to_date == null) ? date('Y-m-d') : date('Y-m-d', strtotime($to_date . ' +1 day'));

    // Debug: Log the dates
    error_log("Product ID: $pid, From Date: $from_date, To Date: $to_date");

    $this->db->select('p.pid, SUM(pdi.quantity) as total_quantity, SUM(wp.selling_price * pdi.quantity) as stock_value, pdi.batch_no');
    $this->db->from('purchase_delivery_items pdi');
    $this->db->join('product p', 'p.id = pdi.product_id', 'left');
    $this->db->join('purchase_delivery pd', 'pd.id = pdi.purchase_delivery_id', 'left');
    $this->db->join('warehouse_products wp', 'wp.batch_no = pdi.batch_no AND wp.product_id = pdi.product_id', 'left');
    
    $this->db->where('p.pid', $pid);

    if($expiry_date != null)
        $this->db->where('pdi.expiry_date', $expiry_date);

    if($from_date != null)
        $this->db->where('pd.created_date >=', $from_date);

    $this->db->where('pd.created_date <=', $to_date);
    $this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');
    $this->db->group_by('p.pid, wp.batch_no');
    
    $query = $this->db->get();
    
    // Debug: Log the SQL query
    error_log($this->db->last_query());
    error_log("Number of results: " . $query->num_rows());
    
    return $query->result();
}
    // HSN Sale report function

    function hsn_sale_report($from_date, $to_date, $sale_id, $hsn_grouping)
    {
      if($hsn_grouping == HSN_GROUPING_COMBINED)
      {
        $this->db->select('
                            LEFT(p.hsn, 6) as hsn,
                            "" as description,
                            si.uom_uom as uom,
                            SUM(si.quantity) as total_quantity,
                            SUM(si.sub_total) as total_amount,
                            (si.igst+si.cgst+si.cgst) as tax_rate,
                            SUM(si.taxable_value) as taxable_value,
                            SUM(si.igst_tax) as igst_tax,
                            SUM(si.cgst_tax) as cgst_tax,
                            SUM(si.sgst_tax) as sgst_tax
                        ');
        $this->db->from('sale_items si');
        $this->db->join('sale s','s.id = si.sale_id','left');
        $this->db->join('customer c','c.id = s.customer_id','left');
        $this->db->join('product p','p.id = si.product_id','left');
        $this->db->join('product_category pc','pc.id = p.product_category_id','left');
        // $this->db->where('s.delete_status',0);
        // $this->db->order_by('si.product_name','asc');


        if($sale_id != '')
          $this->db->where('s.id',$sale_id);
        
        if($from_date != '')
          $this->db->where('s.invoice_date >=', $from_date);

        if($to_date != '')
          $this->db->where('s.invoice_date <=', $to_date);

        $this->db->where('s.delete_status', 0);
        $this->db->group_by('LEFT(p.hsn, 6),pc.igst');
        // $this->db->group_by(array(array('p.hsn'),array('si.igst','si.cgst','si.sgst')));

        $query = $this->db->get();

        return  $query;  
      }
      else
      {
        $this->db->select('
                            p.hsn as hsn,
                            "" as description,
                            si.uom_uom as uom,
                            SUM(si.quantity) as total_quantity,
                            SUM(si.sub_total) as total_amount,
                            (si.igst+si.cgst+si.cgst) as tax_rate,
                            SUM(si.taxable_value) as taxable_value,
                            SUM(si.igst_tax) as igst_tax,
                            SUM(si.cgst_tax) as cgst_tax,
                            SUM(si.sgst_tax) as sgst_tax
                        ');
        $this->db->from('sale_items si');
        $this->db->join('sale s','s.id = si.sale_id','left');
        $this->db->join('customer c','c.id = s.customer_id','left');
        $this->db->join('product p','p.id = si.product_id','left');
        $this->db->join('product_category pc','pc.id = p.product_category_id','left');
        // $this->db->where('s.delete_status',0);
        // $this->db->order_by('si.product_name','asc');


        if($sale_id != '')
          $this->db->where('s.id',$sale_id);
        
        if($from_date != '')
          $this->db->where('s.invoice_date >=', $from_date);

        if($to_date != '')
          $this->db->where('s.invoice_date <=', $to_date);

        $this->db->where('s.delete_status', 0);
        $this->db->group_by('p.hsn,pc.igst');
        // $this->db->group_by(array(array('p.hsn'),array('si.igst','si.cgst','si.sgst')));

        $query = $this->db->get();

        return  $query;   
      }
    }

    // HSN Purchase report function

    function hsn_purchase_report($from_date, $to_date, $purchase_id, $hsn_grouping)
    {
      if($hsn_grouping == HSN_GROUPING_COMBINED)
      {
        $this->db->select('
                            LEFT(p.hsn, 6) as hsn,
                            "" as description,
                            si.uom_uom as uom,
                            SUM(si.quantity) as total_quantity,
                            SUM(si.subtotal) as total_amount,
                            (si.igst+si.cgst+si.cgst) as tax_rate,
                            SUM(si.taxable_value) as taxable_value,
                            SUM(si.igst_tax) as igst_tax,
                            SUM(si.cgst_tax) as cgst_tax,
                            SUM(si.sgst_tax) as sgst_tax
                        ');
        $this->db->from('purchase_items si');
        $this->db->join('purchase s','s.id = si.purchase_id','left');
        $this->db->join('supplier c','c.id = s.supplier_id','left');
        $this->db->join('product p','p.id = si.product_id','left');
        $this->db->join('product_category pc','pc.id = p.product_category_id','left');
        $this->db->where('s.delete_status',0);
        // $this->db->order_by('si.product_name','asc');


        if($purchase_id != '')
          $this->db->where('s.id',$purchase_id);
        
        if($from_date != '')
          $this->db->where('s.purchase_date >=', $from_date);

        if($to_date != '')
          $this->db->where('s.purchase_date <=', $to_date);

        $this->db->where('s.delete_status', 0);
        $this->db->group_by('LEFT(p.hsn, 6),pc.igst');
        // $this->db->group_by(array(array('p.hsn'),array('si.igst','si.cgst','si.sgst')));

        $query = $this->db->get();

        return  $query;  
      }
      else
      {
        $this->db->select('
                            p.hsn as hsn,
                            "" as description,
                            si.uom_uom as uom,
                            SUM(si.quantity) as total_quantity,
                            SUM(si.subtotal) as total_amount,
                            (si.igst+si.cgst+si.cgst) as tax_rate,
                            SUM(si.taxable_value) as taxable_value,
                            SUM(si.igst_tax) as igst_tax,
                            SUM(si.cgst_tax) as cgst_tax,
                            SUM(si.sgst_tax) as sgst_tax
                        ');
        $this->db->from('purchase_items si');
        $this->db->join('purchase s','s.id = si.purchase_id','left');
        $this->db->join('supplier c','c.id = s.supplier_id','left');
        $this->db->join('product p','p.id = si.product_id','left');
        $this->db->join('product_category pc','pc.id = p.product_category_id','left');
        $this->db->where('s.delete_status',0);
        // $this->db->order_by('si.product_name','asc');


        if($purchase_id != '')
          $this->db->where('s.id',$purchase_id);
        
        if($from_date != '')
          $this->db->where('s.purchase_date >=', $from_date);

        if($to_date != '')
          $this->db->where('s.purchase_date <=', $to_date);

        $this->db->where('s.delete_status', 0);
        $this->db->group_by('p.hsn,pc.igst');
        // $this->db->group_by(array(array('p.hsn'),array('si.igst','si.cgst','si.sgst')));

        $query = $this->db->get();

        return  $query;   
      }
    }

     // stock report

     function stock_report()
     {
       $response = array();
 
       // purchase cost
       $this->db->select('SUM(si.cost*si.quantity) as total_cost,SUM(si.quantity) as total_purchase_quantity');
       $this->db->from('purchase_items si');
       $this->db->join('purchase s','s.id = si.purchase_id','left');
       $this->db->where('s.delete_status', 0);
       $query = $this->db->get();
 
       if($query->row() != null){
         $response['purchase_cost'] = $query->row()->total_cost;
         $response['purchase_quantity'] = $query->row()->total_purchase_quantity;
       }
       else{
         $response['purchase_cost'] = 0;
         $response['purchase_quantity'] = 0;
       }
 
       // purchase delivery
       $this->db->select('SUM(si.cost*si.quantity) as total_cost,SUM(si.quantity) as total_purchase_delivered_quantity');
       $this->db->from('purchase_delivery_items si');
       
       $query = $this->db->get();
 
       if($query->row() != null){
         $response['purchase_delivered_cost'] = $query->row()->total_cost;
         $response['purchase_quantity_delivered'] = $query->row()->total_purchase_delivered_quantity;
       }
       else{
         $response['purchase_delivered_cost'] = 0;
         $response['purchase_quantity_delivered'] = 0;
       }
 
       // purchase return cost
       $this->db->select('SUM(si.cost*si.quantity) as total_cost,SUM(si.quantity) as total_purchase_return_quantity');
       $this->db->from('purchase_return_items si');
       $this->db->join('purchase_return s','s.id = si.purchase_return_id','left');
       $this->db->where('s.delete_status', 0);
       $query = $this->db->get();
 
       if($query->row() != null){
         $response['purchase_return_cost'] = $query->row()->total_cost;
         $response['purchase_return_quantity'] = $query->row()->total_purchase_return_quantity;
       }
       else{
         $response['purchase_return_cost'] = 0;
         $response['purchase_return_quantity'] = 0;
       }
 
       // purchase delivery
       $this->db->select('SUM(wp.cost*si.quantity) as total_cost,SUM(si.quantity) as total_purchase_return_delivered_quantity');
       $this->db->from('purchase_return_delivery_items si');
       $this->db->join('warehouse_products wp','wp.product_id = si.product_id','left');
       
       $query = $this->db->get();
 
       if($query->row() != null){
         $response['purchase_return_delivered_cost'] = $query->row()->total_cost;
         $response['purchase_return_quantity_delivered'] = $query->row()->total_purchase_return_delivered_quantity;
       }
       else{
         $response['purchase_return_delivered_cost'] = 0;
         $response['purchase_return_quantity_delivered'] = 0;
       }
 
 
       // sale cost (not price)
       $this->db->select('
                           SUM(si.quantity*wp.cost) AS total_cost,
                         
                           SUM(si.quantity) AS total_sale_quantity,
                         
                           SUM((si.quantity*si.cost)-discount_amount) AS total_price
                       ');
       $this->db->from('sale_items si');
       $this->db->join('warehouse_products wp','wp.id = si.warehouse_product_id','left');
       $this->db->join('sale s','s.id = si.sale_id','left');
       $this->db->where('s.delete_status',0);
 
       $query = $this->db->get();
 
       if($query->row() != null){
         $response['sale_cost'] = ($query->row()->total_cost);
         $response['sale_quantity'] = ($query->row()->total_sale_quantity);
       }
       else{
         $response['sale_cost'] = 0;
         $response['sale_quantity'] = 0;
       }
 
       // sale return cost (not price)
       $this->db->select('
                           SUM(si.quantity*wp.cost) AS total_cost,
                        
                           SUM(si.quantity) AS total_sale_return_quantity,
                          
                           SUM((si.quantity*si.cost)-discount_amount) AS total_price
                       ');
       $this->db->from('sales_return_items si');
       $this->db->join('warehouse_products wp','wp.id = si.warehouse_product_id','left');
       $this->db->join('sales_return s','s.id = si.sales_return_id','left');
       $this->db->where('s.delete_status',0);
 
       $query = $this->db->get();
 
       if($query->row() != null){
         $response['sale_return_cost'] = ($query->row()->total_cost);
         $response['sale_return_quantity'] = ($query->row()->total_sale_return_quantity);
       }
       else{
         $response['sale_return_cost'] = 0;
         $response['sale_return_quantity'] = 0;
       }
 
 
       // stock cost 
       $this->db->select('
                           SUM(wp.quantity*wp.cost) AS total_cost,
                           SUM(wp.quantity) AS total_available_quantity
                       ');
       $this->db->from('warehouse_products wp');
       $this->db->join('product p','p.id = wp.product_id','left');
       $this->db->where('p.delete_status',0);
      //  $this->db->where('wp.delete_status',0);
 
       $query = $this->db->get();
 
       if($query->row() != null){
         $response['stock_cost'] = ($query->row()->total_cost);
         $response['stock_quantity'] = ($query->row()->total_available_quantity);
       }
       else{
         $response['stock_cost'] = 0;
         $response['stock_quantity'] = 0;
       }
 
       return $response;
 
     }
 

    // Sale report function

 	/*	function sale_report($from_date, $to_date, $customer_id, $warehouse_id)
{
    $this->db->select('
                        s.*,
                        c.customer_name, c.gstin,  c.state_id as customer_state_id,
                           c.email, c.phone, c.city_name, c.customer_department,
                           
                        w.name as warehouse_name, 
                        si.product_id, si.quantity, si.selling_price,
                        p.name as product_name, p.description, p.product_category_id, p.name, p.product_code, p.hsn, p.uom_id,st.name as state_name
                        
                    ');
    $this->db->from('sale s');
    $this->db->join('customer c', 'c.id = s.customer_id', 'left');
    $this->db->join('warehouse w', 'w.id = s.warehouse_id', 'left');
    $this->db->join('sale_items si', 'si.sale_id = s.id', 'left');
    $this->db->join('product p', 'p.id = si.product_id', 'left');
 $this->db->join('states st', 'st.id = c.state_id', 'left');
    // Apply filtering based on customer_id and warehouse_id
    if ($customer_id != '') {
        $this->db->where('c.id', $customer_id);
    }

    if ($warehouse_id != '') {
        $this->db->where('w.id', $warehouse_id);
    }

    // Filter based on from_date and to_date
    if ($from_date != '') {
        $this->db->where('s.invoice_date >=', $from_date);
    }

    if ($to_date != '') {
        $this->db->where('s.invoice_date <=', $to_date);
    }

    $this->db->where('s.delete_status', 0);
    $this->db->order_by('s.created_date', 'desc');

    $query = $this->db->get();
    return $query;
}*/
/*function sale_report($from_date, $to_date, $customer_id, $warehouse_id)
{
    $this->db->select('
        sr.reference_no as invoice_no,
        CONCAT(u.first_name, " ", u.last_name) as customer_name,
        u.phone as mobile_number,
        DATE_FORMAT(sr.invoice_date, "%d-%m-%Y") as purchase_date,
        sr.total_taxable_value,
        sr.total_tax,
        sr.total,
        CASE 
            WHEN sr.level_id = 0 THEN "Head Office"
            WHEN sr.level_id = 1 THEN "Level 1 - Manager"
            WHEN sr.level_id = 2 THEN "Level 2 - Senior Manager"
            WHEN sr.level_id = 3 THEN "Level 3 - Director"
            ELSE "Branch User"
        END as added_form,
        sr.status
    ');
    $this->db->from('sale_requests sr');
    $this->db->join('users u', 'u.id = sr.added_by', 'left');
    
    if ($customer_id != '') {
        $this->db->where('sr.added_by', $customer_id);
    }

    if ($warehouse_id != '') {
        $this->db->where('sr.warehouse_id', $warehouse_id);
    }

    if ($from_date != '') {
        $this->db->where('sr.invoice_date >=', $from_date);
    }

    if ($to_date != '') {
        $this->db->where('sr.invoice_date <=', $to_date);
    }

    $this->db->where('sr.delete_status', 0);
    
    // ❌ REMOVE OR COMMENT THIS LINE - IT CAUSES THE ERROR
    // $this->db->where('u.delete_status', 0);
    
    // ✅ Instead, use 'active' column if needed
    $this->db->where('u.active', 1);
    
    $this->db->order_by('sr.id', 'DESC');

    $query = $this->db->get();
    return $query;
}*/

// last working version 
// function sale_report($from_date, $to_date, $customer_id, $warehouse_id)
// {
//     $sql = "
//         SELECT 
//             sr.reference_no as invoice_no,
//             CONCAT(u.first_name, ' ', u.last_name) as customer_name,
//             u.phone as mobile_number,
//             DATE_FORMAT(sr.invoice_date, '%d-%m-%Y') as purchase_date,
//             sr.total_taxable_value,
//             sr.total_tax,
//             sr.total,
//             CASE 
//                 WHEN sr.level_id = 0 THEN 'Head Office'
//                 WHEN sr.level_id = 1 THEN 'Level 1 - Manager'
//                 WHEN sr.level_id = 2 THEN 'Level 2 - Senior Manager'
//                 WHEN sr.level_id = 3 THEN 'Level 3 - Director'
//                 ELSE 'Branch User'
//             END as added_form,
//             sr.status
//         FROM sale_requests sr
//         LEFT JOIN users u ON u.id = sr.added_by
//         WHERE sr.delete_status = 0
//         AND u.active = 1
//     ";
    
//     // Add filters
//     if ($customer_id != '') {
//         $sql .= " AND sr.added_by = " . $this->db->escape($customer_id);
//     }
    
//     if ($warehouse_id != '') {
//         $sql .= " AND sr.warehouse_id = " . $this->db->escape($warehouse_id);
//     }
    
//     if ($from_date != '') {
//         $sql .= " AND sr.invoice_date >= '" . $from_date . "'";
//     }
    
//     if ($to_date != '') {
//         $sql .= " AND sr.invoice_date <= '" . $to_date . "'";
//     }
    
//     $sql .= " ORDER BY sr.id DESC";
    
//     $query = $this->db->query($sql);
//     return $query;
// }


function sale_report($from_date, $to_date, $customer_id, $warehouse_id)
{
    $this->db->select('
        s.invoice_date,
        s.reference_no as invoice_no,
        s.id as sale_id,
        w.name as warehouse_name,
        c.customer_name,
        c.customer_company_name,
        si.product_name,
        si.quantity,
        si.taxable_value,
        si.igst_tax,
        si.cgst_tax,
        si.sgst_tax,
        si.sub_total as total_amount,
        si.selling_price as rate
    ');
    $this->db->from('sale_items si');
    $this->db->join('sale s', 's.id = si.sale_id');
    $this->db->join('customer c', 'c.id = s.customer_id', 'left');
    $this->db->join('warehouse w', 'w.id = s.warehouse_id', 'left');
    
    $this->db->where('s.delete_status', 0);

    if ($from_date != '') $this->db->where('s.invoice_date >=', $from_date);
    if ($to_date != '') $this->db->where('s.invoice_date <=', $to_date);
    if ($customer_id != '') $this->db->where('s.customer_id', $customer_id);
    if ($warehouse_id != '') $this->db->where('s.warehouse_id', $warehouse_id);

    $this->db->order_by('s.invoice_date', 'DESC');
    return $this->db->get();
}


// function sale_report($from_date, $to_date, $customer_id, $warehouse_id)
// {
//     $this->db->select('
//         s.*,
//         c.customer_name, c.gstin, c.state_name, 
//         c.phone as customer_phone,
//         c.mobile as customer_mobile,
//         c.city_name as customer_city,
//         c.customer_department,
//         c.email as customer_email,
        
//         w.name as warehouse_name, 
//         w.code as branch_code,
        
//         si.product_id, si.quantity, si.selling_price,
        
//         p.name as product_name, 
//         p.description, 
//         p.product_category_id, 
//         p.hsn, 
//         p.uom_id,
//         p.product_code,
        
//         u.username,
//         u.first_name,
//         u.last_name,
//         u.email as user_email,
//         u.phone as user_phone,
//         u.department as user_department,
//         u.employee_code,
//         u.designation,
        
//         s.carrier as delivery_partner,
//         s.vehicle_no,
//         s.dispatch_date,
//         s.po_no,
//         s.po_date,
//         s.invoice_date,
//         s.reference_no
//     ');
    
//     $this->db->from('sale s');
//     $this->db->join('customer c', 'c.id = s.customer_id', 'left');
//     $this->db->join('warehouse w', 'w.id = s.warehouse_id', 'left');
//     $this->db->join('sale_items si', 'si.sale_id = s.id', 'left');
//     $this->db->join('product p', 'p.id = si.product_id', 'left');
//     $this->db->join('users u', 'u.id = s.user_id', 'left');
    
//     // Filter conditions
//     if ($customer_id != '') {
//         $this->db->where('c.id', $customer_id);
//     }
//     if ($warehouse_id != '') {
//         $this->db->where('w.id', $warehouse_id);
//     }
//     if ($from_date != '') {
//         $this->db->where('s.invoice_date >=', $from_date);
//     }
//     if ($to_date != '') {
//         $this->db->where('s.invoice_date <=', $to_date);
//     }

//     $this->db->where('s.delete_status', 0);
//     $this->db->order_by('s.created_date', 'desc');

//     $query = $this->db->get();
//     return $query;
// }


    // Sales Return report function

    // function sales_return_report($from_date, $to_date, $warehouse_id, $customer_id)
    // {
    //   $this->db->select('
    //                       s.*,
    //                       w.name,
    //                       c.customer_name,gstin
    //                     ');
    //   $this->db->from('sales_return s');
    //   $this->db->join('customer c','c.id = s.customer_id','left');
    //   $this->db->join('warehouse w','w.id = s.warehouse_id','left');

    //   if($customer_id != '')
    //     $this->db->where('c.id',$customer_id);

    //   if($warehouse_id != '')
    //     $this->db->where('w.id',$warehouse_id);

    //   if($from_date != '')
    //     $this->db->where('s.sales_return_date >=', $from_date);

    //   if($to_date != '')
    //     $this->db->where('s.sales_return_date <=', $to_date);

    //   $this->db->where('s.delete_status', 0);
    //   $query = $this->db->get();

    //   return  $query;
    // }

   function sales_return_report($from_date, $to_date, $warehouse_id, $customer_id)
{
    $this->db->select('
        s.sales_return_date as date,
        s.reference_no as invoice_no,
        s.id as return_id,
        s.internal_note as remarks,
        c.customer_name as party_name,
        w.name as branch_name,
        sri.product_name,
        sri.quantity as qty,
        sri.cost as rate,
        sri.sub_total as amount
    ');
    $this->db->from('sales_return_items sri'); 
    $this->db->join('sales_return s', 's.id = sri.sales_return_id'); 
    $this->db->join('customer c', 'c.id = s.customer_id', 'left');
    $this->db->join('warehouse w', 'w.id = s.warehouse_id', 'left');

    if ($customer_id != '') $this->db->where('s.customer_id', $customer_id);
    if ($warehouse_id != '') $this->db->where('s.warehouse_id', $warehouse_id);
    if ($from_date != '') $this->db->where('s.sales_return_date >=', $from_date);
    if ($to_date != '') $this->db->where('s.sales_return_date <=', $to_date);

    $this->db->where('s.delete_status', 0);
    $this->db->order_by('s.sales_return_date', 'DESC');
    
    return $this->db->get();
}

    // Purchase report function

    /*public function purchase_report($from_date = null, $to_date = null, $supplier_id = null, $warehouse_id = null)
    {
        $this->db->select('
                            p.*,
                            sp.company_name, sp.gstin as supp_gstin,
                            w.name as warehouse_name,
                            pi.*,
                            pr.hsn as hsn
                          ');
        $this->db->from('purchase p');
        $this->db->join('supplier sp', 'sp.id = p.supplier_id', 'left');
        $this->db->join('warehouse w', 'w.id = p.warehouse_id', 'left');
        $this->db->join('purchase_items pi', 'pi.purchase_id = p.id', 'left'); // Join with purchase_items table
        $this->db->join('product pr', 'pr.id = pi.product_id', 'left'); // Join with product to get HSN
    
        if ($supplier_id != '' && $supplier_id != null) {
            $this->db->where('sp.id', $supplier_id);
        }
    
        if ($warehouse_id != '' && $warehouse_id != null) {
            $this->db->where('w.id', $warehouse_id);
        }
    
        if ($from_date != '' && $from_date != null) {
            $this->db->where('p.purchase_date >=', $from_date);
        }
    
        if ($to_date != '' && $to_date != null) {
            $this->db->where('p.purchase_date <=', $to_date);
        }
    
        $this->db->where('p.delete_status', 0);
        $query = $this->db->get();
    
        return $query;
    }*/

  public function purchase_report_supplier($from_date = null, $to_date = null, $supplier_id = null, $warehouse_id = null)
{
    $this->db->select('
        p.id, 
        p.reference_no as purchase_no, 
        p.purchase_date, 
        p.invoice_no, 
        p.total as grand_total,
        p.total_discount,
        sp.company_name, 
        sp.gstin as supp_gstin,
        w.name as warehouse_name,
        SUM(pi.taxable_value) as taxable_value,
        SUM(pi.igst) as igst,
        SUM(pi.cgst) as cgst,
        SUM(pi.sgst) as sgst,
        SUM(pi.subtotal) as item_total,
        GROUP_CONCAT(DISTINCT pr.hsn SEPARATOR ", ") as hsn
    ');
    $this->db->from('purchase p');
    $this->db->join('supplier sp', 'sp.id = p.supplier_id', 'left');
    $this->db->join('warehouse w', 'w.id = p.warehouse_id', 'left');
    $this->db->join('purchase_items pi', 'pi.purchase_id = p.id', 'left'); 
    $this->db->join('product pr', 'pr.id = pi.product_id', 'left'); 

    // Filters
    if (!empty($supplier_id)) $this->db->where('p.supplier_id', $supplier_id);
    if (!empty($warehouse_id)) $this->db->where('p.warehouse_id', $warehouse_id);
    if (!empty($from_date)) $this->db->where('p.purchase_date >=', $from_date);
    if (!empty($to_date)) $this->db->where('p.purchase_date <=', $to_date);

    $this->db->where('p.delete_status', 0);
    $this->db->group_by('p.id'); 
    
    return $this->db->get();
}
public function purchase_report($from_date = null, $to_date = null, $supplier_id = null, $warehouse_id = null)
{
    $this->db->select('
        p.id, 
        p.reference_no , 
        p.purchase_date, 
        p.invoice_no, 
        p.total as total,
        p.total_discount,
        p.total_taxable_value,
        sp.company_name, 
        sp.gstin as supp_gstin,
        w.name as warehouse_name,
        pi.product_name,
        pi.description,
        pi.uom_id,
        pi.hsn,
        pi.quantity,
        pi.taxable_value as total_taxable_value,
        pi.igst as igst,
        pi.cgst as cgst,
        pi.sgst as sgst,
        pi.discount_amount as total_discount,
        pi.subtotal as total
    ');
    $this->db->join('purchase_delivery pd', 'pd.purchase_id = p.id', 'inner');
    $this->db->from('purchase p');
    $this->db->join('supplier sp', 'sp.id = p.supplier_id', 'left');
    $this->db->join('warehouse w', 'w.id = p.warehouse_id', 'left');
    $this->db->join('purchase_items pi', 'pi.purchase_id = p.id', 'left'); 
    $this->db->join('product pr', 'pr.id = pi.product_id', 'left'); 

    // Filters
    if (!empty($supplier_id)) {
        $this->db->where('p.supplier_id', $supplier_id);
    }
    if (!empty($warehouse_id)) {
        $this->db->where('p.warehouse_id', $warehouse_id);
    }
    if (!empty($from_date)) {
        $this->db->where('p.purchase_date >=', $from_date);
    }
    if (!empty($to_date)) {
        $this->db->where('p.purchase_date <=', $to_date);
    }

    $this->db->where('p.delete_status', 0);
    $this->db->order_by('p.purchase_date', 'DESC');
    
    $query = $this->db->get();
    return $query;
}
    // Purchase Return report function

    // function purchase_return_report($from_date = null, $to_date = null, $supplier_id = null, $warehouse_id = null)
    // {
    //   $this->db->select('
    //                       p.*,
    //                       sp.company_name,
    //                       w.name
    //                     ');
    //   $this->db->from('purchase_return p');
    //   $this->db->join('supplier sp','sp.id = p.supplier_id','left');
    //   $this->db->join('warehouse w','w.id = p.warehouse_id','left');

    //   if($supplier_id != '' && $supplier_id != null)
    //     $this->db->where('sp.id',$supplier_id);

    //   if($warehouse_id != '' && $warehouse_id != null)
    //     $this->db->where('w.id',$warehouse_id);

    //   if($from_date != '' && $from_date != null)
    //     $this->db->where('p.purchase_return_date >=', $from_date);

    //   if($to_date != '' && $to_date != null)
    //     $this->db->where('p.purchase_return_date <=', $to_date);

    //   $this->db->where('p.delete_status', 0);
    //   $query = $this->db->get();

    //   return  $query;
    // }
    
    function purchase_return_report($from_date = null, $to_date = null, $supplier_id = null, $warehouse_id = null)
    {
        $this->db->select('
            p.purchase_return_date as date,
            p.reference_no as invoice_no,
            p.internal_note as remarks,
            p.id as return_id,
            sp.company_name as party_name,
            w.name as branch_name,
            pri.product_name,
            pri.quantity as qty,
            pri.cost as rate,
            pri.subtotal as amount
        ');
        $this->db->from('purchase_return p');
        $this->db->join('purchase_return_items pri', 'pri.purchase_return_id = p.id');
        $this->db->join('supplier sp', 'sp.id = p.supplier_id', 'left');
        $this->db->join('warehouse w', 'w.id = p.warehouse_id', 'left');
    
        if (!empty($supplier_id)) $this->db->where('p.supplier_id', $supplier_id);
        if (!empty($warehouse_id)) $this->db->where('p.warehouse_id', $warehouse_id);
        if (!empty($from_date)) $this->db->where('p.purchase_return_date >=', $from_date);
        if (!empty($to_date)) $this->db->where('p.purchase_return_date <=', $to_date);
    
        $this->db->where('p.delete_status', 0);
        $this->db->order_by('p.purchase_return_date', 'DESC');
        return $this->db->get();
    }

    // Expense report function

   	function expense_report($from_date, $to_date, $supplier_id,$expense_category_id)
   	{
       	$this->db->select('
       											e.*,
                            s.company_name,
                            ec.name as expense_category_name
                          ');
       	$this->db->from('expense e');
       	$this->db->join('supplier s','s.id = e.supplier_id','left');
       	$this->db->join('expense_category ec','ec.id = e.expense_category_id','left');

       	if($supplier_id != '')
       		$this->db->where('e.supplier_id',$supplier_id);

     		if($expense_category_id != '')
     			$this->db->where('e.expense_category_id',$expense_category_id);

       	if($from_date != '')
       		$this->db->where('e.date >=', $from_date);

       	if($to_date != '')
       		$this->db->where('e.date <=', $to_date);

       	$this->db->where('e.delete_status', 0);
       	$query = $this->db->get();

      return 	$query;
   	}

    // Profit and loss report function

    function profit_and_loss_report($year_ending)
    {
      $this->db->select('
                        th.*,
                        YEAR(th.voucher_date) AS year
                      ');
        $this->db->from('transaction_header th');
          $this->db->group_start();
            $this->db->where('th.module',SALE_MODULE);
            $this->db->or_where('th.module',EXPENSE_MODULE);
            $this->db->or_where('th.module',PURCHASE_MODULE);
          $this->db->group_end();
        
          $this->db->group_start();
            $this->db->where('th.type',SALE_TRANSACTION_TYPE);
            $this->db->or_where('th.type',PURCHASE_TRANSACTION_TYPE);
            $this->db->or_where('th.type',EXPENSE_TRANSACTION_TYPE);
          $this->db->group_end();
        
          $this->db->group_start(); 
            $this->db->group_start();
              $this->db->where('YEAR(th.voucher_date)',$year_ending);
              $this->db->where('MONTH(th.voucher_date) BETWEEN 1 AND 3');
            $this->db->group_end();

            $this->db->or_group_start();
              $this->db->where('YEAR(th.voucher_date)',$year_ending-1);
              $this->db->where('MONTH(th.voucher_date) BETWEEN 4 AND 12');
            $this->db->group_end();
          $this->db->group_end();
        
        $query = $this->db->get();

      return  $query;
    }

    // Ledger report functions

    // function ledger_report($from_date, $to_date, $ledger_id)
    // {
    //     $this->db->select('
    //                         td.*,
    //                         th.entry_id,
    //                         th.module,
    //                         th.type AS transaction_type,
    //                         th.voucher_date AS transaction_date,
    //                         (
    //                           CASE
    //                             WHEN th.mode = 0 THEN "CASH"
    //                             WHEN th.mode = 1 THEN "CREDIT CARD"
    //                             WHEN th.mode = 2 THEN "CHEQUE"
    //                             WHEN th.mode = 3 THEN "NEFT / RTGS"
    //                           END
    //                         ) AS transaction_mode,
    //                         th.cheque_no AS cheque_no,
    //                         th.credit_card_no AS credit_card_no,
    //                         th.reference_no AS reference_no,
    //                         l.title AS ledger_title,
    //                         ag.group_title AS account_group_title,
    //                         th.amount AS transaction_amount
    //                       ');
    //     $this->db->from('transaction_detail td');
    //     $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
    //     $this->db->join('ledger l','l.id = td.ledger_id');
    //     $this->db->join('account_group ag','ag.id = l.account_group_id');
    //     $this->db->where('td.ledger_id',$ledger_id);

    //     // if($supplier_id != '')
    //     //   $this->db->where('e.supplier_id',$supplier_id);

    //     // if($expense_category_id != '')
    //     //   $this->db->where('e.expense_category_id',$expense_category_id);

    //     if($from_date != '')
    //       $this->db->where('th.voucher_date >=', $from_date);

    //     if($to_date != '')
    //       $this->db->where('th.voucher_date <=', $to_date);
        
    //     $query = $this->db->get();

    //   return  $query;
    // }
    

// function ledger_report($from_date, $to_date, $ledger_id)
// {
//     $this->db->select("
//         td.*,
//         th.entry_id,
//         th.module,
//         th.type AS transaction_type,
//         th.voucher_date AS transaction_date,
//         (
//             CASE
//                 WHEN th.mode = 0 THEN 'CASH'
//                 WHEN th.mode = 1 THEN 'CREDIT CARD'
//                 WHEN th.mode = 2 THEN 'CHEQUE'
//                 WHEN th.mode = 3 THEN 'NEFT / RTGS'
//             END
//         ) AS transaction_mode,
//         th.cheque_no AS cheque_no,
//         th.credit_card_no AS credit_card_no,
//         th.reference_no AS reference_no,
//         l.title AS ledger_title,
//         ag.group_title AS account_group_title,
//         th.amount AS transaction_amount,
//         CASE 
//             WHEN th.module = 'S' AND th.type = 'S' THEN 'Sale'
//             WHEN th.module = 'PAYMENT_IN' AND th.type = 'R' THEN 'Payment-In'
//             WHEN th.module = 'PAYMENT_OUT' AND th.type = 'R' THEN 'Payment-Out'
//             WHEN th.module = 'P' AND th.type = 'PR' THEN 'Purchase'
//             WHEN th.module = 'E' THEN 'Expense'
//             ELSE th.module
//         END AS display_type,
//         CASE
//             WHEN (th.module = 'S' AND th.type = 'S') THEN th.amount
//             ELSE 0
//         END AS sale_amount,
//         CASE
//             WHEN (th.module = 'PAYMENT_IN' AND th.type = 'R') THEN th.amount
//             ELSE 0
//         END AS payment_in_amount,
//         CASE
//             WHEN (th.module = 'PAYMENT_OUT' AND th.type = 'R') THEN th.amount
//             ELSE 0
//         END AS payment_out_amount,
//         CASE
//             WHEN (th.module = 'P' AND th.type = 'PR') THEN th.amount
//             ELSE 0
//         END AS purchase_amount,
//         CASE
//             WHEN (th.module = 'E') THEN th.amount
//             ELSE 0
//         END AS expense_amount
//     ");
//     $this->db->from('transaction_detail td');
//     $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
//     $this->db->join('ledger l','l.id = td.ledger_id');
//     $this->db->join('account_group ag','ag.id = l.account_group_id');
//     $this->db->where('td.ledger_id', $ledger_id);
    
//     // Filter for transaction types
//     $this->db->group_start();
//         $this->db->group_start();
//             $this->db->where('th.module', 'S');
//             $this->db->where('th.type', 'S');
//         $this->db->group_end();
//         $this->db->or_group_start();
//             $this->db->where('th.module', 'PAYMENT_IN');
//             $this->db->where('th.type', 'R');
//         $this->db->group_end();
//         $this->db->or_group_start();
//             $this->db->where('th.module', 'PAYMENT_OUT');
//             $this->db->where('th.type', 'R');
//         $this->db->group_end();
//         $this->db->or_group_start();
//             $this->db->where('th.module', 'P');
//             $this->db->where('th.type', 'PR');
//         $this->db->group_end();
//         $this->db->or_group_start();
//             $this->db->where('th.module', 'E');
//         $this->db->group_end();
//     $this->db->group_end();

//     if($from_date != '')
//         $this->db->where('th.voucher_date >=', $from_date);

//     if($to_date != '')
//         $this->db->where('th.voucher_date <=', $to_date);
    
//     $this->db->order_by('th.voucher_date', 'ASC');
//     $this->db->order_by('th.created_date', 'ASC');
    
//     $query = $this->db->get();
//     return $query;
// }

function ledger_report($from_date, $to_date, $ledger_id)
{
    $this->db->select("
        td.*,
        th.entry_id,
        th.module,
        th.type AS transaction_type,
        th.voucher_date AS transaction_date,
        (
            CASE
                WHEN th.mode = 0 THEN 'CASH'
                WHEN th.mode = 1 THEN 'CREDIT CARD'
                WHEN th.mode = 2 THEN 'CHEQUE'
                WHEN th.mode = 3 THEN 'NEFT / RTGS'
            END
        ) AS transaction_mode,
        th.cheque_no AS cheque_no,
        th.credit_card_no AS credit_card_no,
        th.reference_no AS reference_no,
        l.title AS ledger_title,
        ag.group_title AS account_group_title,
        th.amount AS transaction_amount,
        CASE 
            WHEN th.module = 'S' AND th.type = 'S' THEN 'Sale'
            WHEN th.module = 'PAYMENT_IN' AND th.type = 'R' THEN 'Payment-In'
            WHEN th.module = 'PAYMENT_OUT' AND th.type = 'R' THEN 'Payment-Out'
            WHEN th.module = 'P' AND th.type = 'PR' THEN 'Purchase'
            WHEN th.module = 'E' AND th.type = 'E' THEN 'Expense'
            WHEN th.module = 'E' AND th.type = 'P' THEN 'Expense Payment'
            ELSE th.module
        END AS display_type,
        CASE
            WHEN (th.module = 'S' AND th.type = 'S') THEN th.amount
            ELSE 0
        END AS sale_amount,
        CASE
            WHEN (th.module = 'PAYMENT_IN' AND th.type = 'R') THEN th.amount
            ELSE 0
        END AS payment_in_amount,
        CASE
            WHEN (th.module = 'PAYMENT_OUT' AND th.type = 'R') THEN th.amount
            ELSE 0
        END AS payment_out_amount,
        CASE
            WHEN (th.module = 'P' AND th.type = 'PR') THEN th.amount
            ELSE 0
        END AS purchase_amount,
        CASE
            WHEN (th.module = 'E' AND th.type = 'E') THEN th.amount
            ELSE 0
        END AS expense_amount,
        CASE
            WHEN (th.module = 'E' AND th.type = 'P') THEN th.amount
            ELSE 0
        END AS expense_payment_amount
    ");
    $this->db->from('transaction_detail td');
    $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
    $this->db->join('ledger l','l.id = td.ledger_id');
    $this->db->join('account_group ag','ag.id = l.account_group_id');
    $this->db->where('td.ledger_id', $ledger_id);
    
    // Filter for transaction types
    $this->db->group_start();
        $this->db->group_start();
            $this->db->where('th.module', 'S');
            $this->db->where('th.type', 'S');
        $this->db->group_end();
        $this->db->or_group_start();
            $this->db->where('th.module', 'PAYMENT_IN');
            $this->db->where('th.type', 'R');
        $this->db->group_end();
        $this->db->or_group_start();
            $this->db->where('th.module', 'PAYMENT_OUT');
            $this->db->where('th.type', 'R');
        $this->db->group_end();
        $this->db->or_group_start();
            $this->db->where('th.module', 'P');
            $this->db->where('th.type', 'PR');
        $this->db->group_end();
        $this->db->or_group_start();
            $this->db->where('th.module', 'E');
            $this->db->where('th.type', 'E');
        $this->db->group_end();
        $this->db->or_group_start();
            $this->db->where('th.module', 'E');
            $this->db->where('th.type', 'P');
        $this->db->group_end();
    $this->db->group_end();

    if($from_date != '')
        $this->db->where('th.voucher_date >=', $from_date);

    if($to_date != '')
        $this->db->where('th.voucher_date <=', $to_date);
    
    $this->db->order_by('th.voucher_date', 'ASC');
    $this->db->order_by('th.created_date', 'ASC');
    
    $query = $this->db->get();
    return $query;
}




// public function get_ledger_balance($ledger_id, $as_of_date)
// {
//     $this->db->select('SUM(
//         CASE 
//             WHEN (th.module = "S" AND th.type = "S") THEN th.amount
//             WHEN (th.module = "PAYMENT_IN" AND th.type = "R") THEN -th.amount
//             ELSE 0
//         END) AS balance');
//     $this->db->from('transaction_detail td');
//     $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
//     $this->db->where('td.ledger_id', $ledger_id);
    
//     if($as_of_date != '') {
//         $this->db->where('th.voucher_date <', $as_of_date);
//     }
    
//     $query = $this->db->get();
//     $result = $query->row();
    
//     return ($result->balance !== null) ? $result->balance : 0;
// }

// public function get_ledger_balance($ledger_id, $as_of_date)
// {
//     $this->db->select('SUM(
//         CASE 
//             WHEN (th.module = "S" AND th.type = "S") THEN th.amount
//             WHEN (th.module = "P" AND th.type = "PR") THEN -th.amount
//             WHEN (th.module = "PAYMENT_IN" AND th.type = "R") THEN -th.amount
//             WHEN (th.module = "PAYMENT_OUT" AND th.type = "R") THEN th.amount
//             WHEN (th.module = "E") THEN -th.amount
//             ELSE 0
//         END) AS balance');
//     $this->db->from('transaction_detail td');
//     $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
//     $this->db->where('td.ledger_id', $ledger_id);
    
//     if($as_of_date != '') {
//         $this->db->where('th.voucher_date <', $as_of_date);
//     }
    
//     $query = $this->db->get();
//     $result = $query->row();
    
//     return ($result->balance !== null) ? $result->balance : 0;
// }

public function get_ledger_balance($ledger_id, $as_of_date)
{
    $this->db->select('SUM(
        CASE 
            WHEN (th.module = "S" AND th.type = "S") THEN th.amount
            WHEN (th.module = "P" AND th.type = "PR") THEN -th.amount
            WHEN (th.module = "PAYMENT_IN" AND th.type = "R") THEN -th.amount
            WHEN (th.module = "PAYMENT_OUT" AND th.type = "R") THEN th.amount
            WHEN (th.module = "E" AND th.type = "E") THEN -th.amount
            WHEN (th.module = "E" AND th.type = "P") THEN th.amount
            ELSE 0
        END) AS balance');
    $this->db->from('transaction_detail td');
    $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
    $this->db->where('td.ledger_id', $ledger_id);
    
    if($as_of_date != '') {
        $this->db->where('th.voucher_date <', $as_of_date);
    }
    
    $query = $this->db->get();
    $result = $query->row();
    
    return ($result->balance !== null) ? $result->balance : 0;
}


    // Balance sheet report functions

    // function balance_sheet_report($year_ending)
    // {
    //     $this->db->select('
    //                     th.*,
    //                     YEAR(th.voucher_date) AS year
    //                   ');
    //     $this->db->from('transaction_header th');
    //     $this->db->where('(th.module ="'.SALE_MODULE.'" OR th.module ="'.EXPENSE_MODULE.'" OR th.module ="'.PURCHASE_MODULE.'")', NULL, FALSE);
    //     $this->db->where('(th.type ="'.SALE_TRANSACTION_TYPE.'" OR th.type ="'.PURCHASE_TRANSACTION_TYPE.'" OR th.type ="'.EXPENSE_TRANSACTION_TYPE.'")', NULL, FALSE);
    //     $this->db->where('(YEAR(th.voucher_date) = '.$year_ending.' OR YEAR(th.voucher_date) = '.($year_ending-1).')', NULL, FALSE);

    //     $query = $this->db->get();

    //   return  $query;
    // }

    function balance_sheet_report($year_ending)
    {
      
      $year_ending                              = (int)$year_ending;
      $data                                     = array();
      
      $data['total_liability']                  = 0;
      $data['total_assets']                     = 0;

      $data['capital_account_opening_balance']  = 0;
      $data['total_profit']                     = 0;
      $data['total_loss']                       = 0;
      $data['total_profit_loss']                = 0;
      $data['current_liability_and_provision']  = 0;
      
      $data['other_current_assets']             = 0;
      $data['tds_receivable']                   = 0;
      $data['tax_receivable']                   = 0;
      
      $data['cash_bank_closing_balance']        = 0;
      $data['cash_closing_balance']             = 0;
      $data['bank_closing_balance']             = 0;

      $data['current_assets']                   = 0;
      $data['closing_stock']                    = 0;
      $data['accounts_receivable']              = 0;

      $data['inclusive_tax_amount']             = 0;


      $data['total_increment']                  = 0;
      $data['total_decrement']                  = 0;

      
      /******************* Start Opening Balance *****************/

      // Bank Opening Balance

      $this->db->select('SUM(l.opening_balance) AS opening_balance');
      $this->db->from('ledger l');
      $this->db->where('l.account_group_id',BANK_ACCOUNT_GROUP_ID);
      $this->db->group_by('l.account_group_id');

      $query = $this->db->get();

      $bank_opening_balance_data = $query->row();

      if($bank_opening_balance_data != null)
      {
        $data['capital_account_opening_balance'] += $bank_opening_balance_data->opening_balance;
      }
      else
      {
        $data['capital_account_opening_balance'] += 0;
      }

      // Cash Opening Balance
      
      $this->db->select('SUM(l.opening_balance) AS opening_balance');
      $this->db->from('ledger l');
      $this->db->or_where('l.account_group_id',CASH_ACCOUNT_GROUP_ID);
      $this->db->group_by('l.account_group_id');

      $query = $this->db->get();

      $cash_opening_balance_data = $query->row();

      if($cash_opening_balance_data != null)
      {
        $data['capital_account_opening_balance'] += $cash_opening_balance_data->opening_balance;
      }
      else
      {
        $data['capital_account_opening_balance'] += 0;
      }

      /******************* End Opening Balance *****************/

      /******************* Start Total Profit & Loss *****************/

      $this->db->select(' 
                          SUM((si.quantity*si.price)-si.discount_amount) AS total_price,
                          SUM(si.quantity*si.cost) AS total_cost
                      ');
      $this->db->from('sale_items si');
      $this->db->join('sale s','s.id = si.sale_id','LEFT');

        $this->db->group_start();
          $this->db->where('YEAR(s.invoice_date)',$year_ending);
          $this->db->where('MONTH(s.invoice_date) BETWEEN 1 AND 3');
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('YEAR(s.invoice_date)',$year_ending-1);
          $this->db->where('MONTH(s.invoice_date) BETWEEN 4 AND 12');
        $this->db->group_end();

      $this->db->group_by('s.delete_status');     
      $this->db->having('s.delete_status','0');

      $query = $this->db->get();

      $profit_data = $query->row();

      if($profit_data != null)
      {
        $data['total_profit']  = $profit_data->total_price - $profit_data->total_cost;
      }
      else
      {
        $data['total_profit']  = 0;
      }

      $data['total_profit_query'] = $this->db->last_query();

      // Loss from Expense

      $this->db->select('
                          SUM(
                                CASE 
                                  WHEN e.itc = 1 THEN e.amount
                                  ELSE e.total_amount
                                END
                              ) AS total_expense
                        '
                      );
      $this->db->from('expense e');
      
        $this->db->group_start();
          $this->db->where('YEAR(e.created_date)',$year_ending);
          $this->db->where('MONTH(e.created_date) BETWEEN 1 AND 3');
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('YEAR(e.created_date)',$year_ending-1);
          $this->db->where('MONTH(e.created_date) BETWEEN 4 AND 12');
        $this->db->group_end();

      $this->db->group_by('e.delete_status');     
      $this->db->having('e.delete_status','0');

      $query = $this->db->get();

      $expense_data = $query->row();

      if($expense_data != null)
      {
        $data['total_expense']  = $expense_data->total_expense;
      }
      else
      {
        $data['total_expense']  = 0;
      }

      $data['total_expense_query'] = $this->db->last_query();

      $data['total_profit_loss'] = $data['total_profit'] - $data['total_expense'];

      /******************* End Total Profit & Loss *****************/


      /******************* Start Sundry Creditors Closing Balance *****************/

      $this->db->select('SUM(l.closing_balance) AS closing_balance');
      $this->db->from('ledger l');
      $this->db->where('l.account_group_id',SUPPLIER_ACCOUNT_GROUP_ID);
      $this->db->group_by('l.account_group_id');

      $query = $this->db->get();

      $current_liability_and_provision_data = $query->row();

      if($current_liability_and_provision_data != null)
      {
        $data['current_liability_and_provision'] = $current_liability_and_provision_data->closing_balance;
      }
      else
      {
        $data['current_liability_and_provision'] = 0;
      }

      /******************* End Sundry Creditors Closing Balance *****************/

      
      /******************* Start Tax Payable Financial Year Wise *****************/

      $this->db->select('SUM(s.total_tax) AS total_tax');
      $this->db->from('sale s');
      
        $this->db->group_start();
          $this->db->where('YEAR(s.invoice_date)',$year_ending);
          $this->db->where('MONTH(s.invoice_date) BETWEEN 1 AND 3');
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('YEAR(s.invoice_date)',$year_ending-1);
          $this->db->where('MONTH(s.invoice_date) BETWEEN 4 AND 12');
        $this->db->group_end();

      $this->db->group_by('s.delete_status');
      $this->db->having('s.delete_status',0);
      
      $query = $this->db->get();

      $tax_payable_data = $query->row();

      if($tax_payable_data != null)
      {
        $data['tax_payable'] = $tax_payable_data->total_tax;
      }
      else
      {
        $data['tax_payable'] = 0;
      }

      // $data['tax_query'] = $this->db->last_query();
      /******************* End Tax Payable Financial Year Wise *****************/

      /******************* Start Other Current Assets Financial Year Wise *****************/

      

      // Tax from Purchase

      $this->db->select('SUM(p.total_tax) AS total_tax');
      $this->db->from('purchase p');
      
        $this->db->group_start();
          $this->db->where('YEAR(p.purchase_date)',$year_ending);
          $this->db->where('MONTH(p.purchase_date) BETWEEN 1 AND 3');
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('YEAR(p.purchase_date)',$year_ending-1);
          $this->db->where('MONTH(p.purchase_date) BETWEEN 4 AND 12');
        $this->db->group_end();

      $this->db->group_by('p.delete_status');
      $this->db->having('p.delete_status',0);
      
      $query = $this->db->get();

      $purchase_tax_data = $query->row();

      if($purchase_tax_data != null)
      {
        $data['other_current_assets'] += $purchase_tax_data->total_tax;
        $data['tax_receivable']       += $purchase_tax_data->total_tax;
      }

      // Calculate inclusive tax from purchase

      $this->db->select('SUM(pi.igst_tax + pi.cgst_tax + pi.sgst_tax) AS total_tax');
      $this->db->from('purchase_items pi');
      $this->db->join('purchase p','p.id = pi.purchase_id');
      $this->db->where('pi.tax_type',TAX_INCLUSIVE);
      
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('YEAR(p.purchase_date)',$year_ending);
            $this->db->where('MONTH(p.purchase_date) BETWEEN 1 AND 3');
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('YEAR(p.purchase_date)',$year_ending-1);
            $this->db->where('MONTH(p.purchase_date) BETWEEN 4 AND 12');
          $this->db->group_end();
        $this->db->group_end();

      $this->db->where('p.delete_status',0);
      
      $query = $this->db->get();

      $purchase_inclusive_tax_data = $query->row();
      
      $data['inclusive_data'] = $purchase_inclusive_tax_data;

      if($purchase_inclusive_tax_data != null)
      {
        $data['inclusive_tax_amount'] += $purchase_inclusive_tax_data->total_tax;
      }

      // Tax from Expense

      $this->db->select('(SUM(e.cgst_tax)+ SUM(e.sgst_tax)+ SUM(e.igst_tax)) AS total_tax');
      $this->db->from('expense e');
      $this->db->join('supplier s','s.id = e.supplier_id');
      $this->db->where('s.gst_registration_type',1);
      $this->db->where('e.itc',1);
     
        $this->db->group_start();
          $this->db->where('YEAR(e.date)',$year_ending);
          $this->db->where('MONTH(e.date) BETWEEN 1 AND 3');
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('YEAR(e.date)',$year_ending-1);
          $this->db->where('MONTH(e.date) BETWEEN 4 AND 12');
        $this->db->group_end();

      $this->db->group_by('e.delete_status');
      $this->db->having('e.delete_status',0);
      
      $query = $this->db->get();

      $expense_tax_data = $query->row();

      if($expense_tax_data != null)
      {
        $data['other_current_assets'] += $expense_tax_data->total_tax;
        $data['tax_receivable']       += $expense_tax_data->total_tax;
      }

      // TDS Receivable

      $this->db->select('l.closing_balance');
      $this->db->from('ledger l');
      $this->db->where('l.id',TDS_LEDGER);

      $query = $this->db->get();

      $tds_receivable_data = $query->row();

      if($tds_receivable_data != null)
      {
        $data['other_current_assets'] += $tds_receivable_data->closing_balance;
        $data['tds_receivable']       += $tds_receivable_data->closing_balance;
      }
      else
      {
        $data['other_current_assets'] += 0;
      }


      /******************* End Other Current Assets Financial Year Wise *****************/

      /******************* Start Cash & Bank Closing Balance *****************/

      

      // Bank Closing Balance

      $this->db->select('SUM(l.closing_balance) AS closing_balance');
      $this->db->from('ledger l');
      $this->db->where('l.account_group_id',BANK_ACCOUNT_GROUP_ID);
      $this->db->group_by('l.account_group_id');

      $query = $this->db->get();

      $bank_closing_balance_data = $query->row();

      if($bank_closing_balance_data != null)
      {
        $data['cash_bank_closing_balance'] += $bank_closing_balance_data->closing_balance;
        $data['bank_closing_balance']      += $bank_closing_balance_data->closing_balance;
      }
      else
      {
        $data['cash_bank_closing_balance'] += 0;
      }

      // Cash Closing Balance

      $this->db->select('SUM(l.closing_balance) AS closing_balance');
      $this->db->from('ledger l');
      $this->db->or_where('l.account_group_id',CASH_ACCOUNT_GROUP_ID);
      $this->db->group_by('l.account_group_id');

      $query = $this->db->get();

      $cash_closing_balance_data = $query->row();

      if($cash_closing_balance_data != null)
      {
        $data['cash_bank_closing_balance'] += $cash_closing_balance_data->closing_balance;
        $data['cash_closing_balance']      += $cash_closing_balance_data->closing_balance;
      }
      else
      {
        $data['cash_bank_closing_balance'] += 0;
      }

      /******************* End Cash & Bank Closing Balance *****************/


      /*********************** Start Current Assets ************************/

      

      // Inclusive tax Warehouse Closing Stock

      $this->db->select('(CAST(SUM(wp.quantity*wp.cost) AS Decimal(11,2))) AS closing_stock');
      $this->db->from('warehouse_products wp');
      $this->db->where('wp.quantity >',0);
      $query = $this->db->get();

      $warehouse_closing_stock_data = $query->row();

      if($warehouse_closing_stock_data != null)
      {
        $data['current_assets'] += $warehouse_closing_stock_data->closing_stock - $data['inclusive_tax_amount'];
        $data['closing_stock']  += $warehouse_closing_stock_data->closing_stock - $data['inclusive_tax_amount'];
      }
      else
      {
        $data['current_assets'] += 0;
      }

      // Accounts Receivable

      $this->db->select('SUM(l.closing_balance) AS closing_balance');
      $this->db->from('ledger l');
      $this->db->where('l.account_group_id',CUSTOMER_ACCOUNT_GROUP_ID);
      $this->db->group_by('l.account_group_id');

      $query = $this->db->get();

      $customer_data = $query->row();

      if($customer_data != null)
      {
        $data['current_assets'] += $customer_data->closing_balance;
        $data['accounts_receivable'] += $customer_data->closing_balance;
      }
      else
      {
        $data['current_assets'] += 0;
      }

      /*********************** End Current Assets ************************/

      /*********************** Start Total Increment & Decrement **********************/

      $ob_bank_ledgers_transaction = $this->transaction_model->get_transaction_by_ledger_id_and_transaction_type(null,array(REDUCE_ADJUSTMENT_TRANSACTION_TYPE,INCREASE_ADJUSTMENT_TRANSACTION_TYPE));

      foreach ($ob_bank_ledgers_transaction as $ledger_trans) 
      { 
        if($ledger_trans->transaction_type == REDUCE_ADJUSTMENT_TRANSACTION_TYPE)
        {
          $data['total_decrement'] += $ledger_trans->transaction_amount;
        }
        else if ($ledger_trans->transaction_type == INCREASE_ADJUSTMENT_TRANSACTION_TYPE) 
        {
          $data['total_increment'] += $ledger_trans->transaction_amount;
        }
      }
      
      /*********************** End Total Increment & Decrement ************************/



      $data['total_liability'] = $data['capital_account_opening_balance'] + $data['current_liability_and_provision'] + $data['tax_payable']+ $data['total_profit_loss'];
      $data['total_assets']    = $data['current_assets'] + $data['other_current_assets'] + $data['cash_bank_closing_balance'];

      return $data;
    }

    // function product_report($from_date, $to_date, $product_id, $warehouse_id, $product_search_type, $supplier, $customer_id)
    // {
    //   $this->db->select('
    //                       s.*,
    //                       c.customer_name
    //                     ');
    //   $this->db->from('sale s');
    //   $this->db->join('customer c','c.id = s.customer_id','left');

    //   if($customer_id != '')
    //     $this->db->where('c.id',$customer_id);

    //   if($from_date != '')
    //     $this->db->where('s.invoice_date >=', $from_date);

    //   if($to_date != '')
    //     $this->db->where('s.invoice_date <=', $to_date);

    //   $this->db->where('s.delete_status', 0);
    //   $query = $this->db->get();

    //   return  $query;
    // }

    function product_report($product_id)
    {
      $this->db->select('
                          p.name as product_name,
                          (
                            SELECT SUM(pi.quantity) FROM purchase_items pi 
                            LEFT JOIN 
                              purchase pr ON pr.id = pi.purchase_id
                            WHERE 
                              pi.product_id = p.id AND pr.delete_status = 0
                            GROUP BY 
                              pi.product_id
                          ) as purchase_quantity,

                          (
                            SELECT SUM(pi.quantity) FROM purchase_items pi 
                            LEFT JOIN 
                              purchase pr ON pr.id = pi.purchase_id
                            WHERE 
                              pi.product_id = p.id AND pr.delete_status = 0
                            GROUP BY 
                              pi.product_id
                          ) as purchased_quantity,
                         

                          (
                            SELECT SUM(pi.quantity) FROM purchase_delivery_items pi 
                            WHERE 
                              pi.product_id = p.id
                            GROUP BY 
                              pi.product_id
                          ) as purchase_delivered_quantity,
                          (
                            SELECT group_concat(pr.reference_no SEPARATOR ", ") FROM purchase_items pi 
                            LEFT JOIN 
                              purchase pr ON pr.id = pi.purchase_id
                            WHERE 
                              pi.product_id = p.id AND pr.delete_status = 0
                            GROUP BY 
                              pi.product_id
                          ) as purchase_invoice,
                          (
                            SELECT SUM(si.quantity) FROM sale_items si 
                            LEFT JOIN 
                              sale pr ON pr.id = si.sale_id
                            WHERE 
                              si.product_id = p.id AND pr.delete_status = 0
                            GROUP BY 
                              si.product_id
                          ) as sale_quantity,

                          (
                            SELECT SUM(si.quantity) FROM sale_items si 
                            LEFT JOIN 
                              sale pr ON pr.id = si.sale_id
                            WHERE 
                              si.product_id = p.id AND pr.delete_status = 0
                            GROUP BY 
                              si.product_id
                          ) as sold_quantity,
                          

                          (
                            SELECT SUM(si.quantity) FROM sale_delivery_items si 
                            WHERE 
                              si.product_id = p.id
                            GROUP BY 
                              si.product_id
                          ) as sale_delivered_quantity,
                          (
                            SELECT group_concat(s.reference_no SEPARATOR ", ") FROM sale_items si 
                            LEFT JOIN 
                              sale s ON s.id = si.sale_id
                            WHERE 
                              si.product_id = p.id AND s.delete_status = 0
                            GROUP BY 
                              si.product_id
                          ) as sale_invoice,
                          (
                            SELECT SUM(wp.quantity) FROM warehouse_products wp 
                            LEFT JOIN 
                              product pr ON pr.id = wp.product_id
                            WHERE 
                              wp.product_id = p.id AND p.delete_status = 0
                            GROUP BY 
                              wp.product_id
                          ) as available_quantity,
                          


                          (
                            SELECT SUM(s.quantity) FROM stock s 
                            LEFT JOIN warehouse_products wp ON wp.product_id = s.product_id
                            LEFT JOIN product pr ON pr.id = wp.product_id
                            WHERE 
                              wp.product_id = p.id AND p.delete_status = 0 AND s.delete_status = 0
                            AND
                              s.entry_type= "in"
                            GROUP BY 
                              wp.product_id
                          ) as stockin_quantity,
                          (
                            SELECT SUM(s.quantity) FROM stock s 
                            LEFT JOIN warehouse_products wp ON wp.product_id = s.product_id
                            LEFT JOIN product pr ON pr.id = wp.product_id
                            WHERE 
                              wp.product_id = p.id AND p.delete_status = 0 AND s.delete_status = 0
                            AND
                              s.entry_type= "out"
                            GROUP BY 
                              wp.product_id
                          ) as stockout_quantity,
                          (
                            SELECT SUM(si.quantity) FROM sales_return_items si 
                            LEFT JOIN 
                              sales_return pr ON pr.id = si.sales_return_id
                            WHERE 
                              si.product_id = p.id AND pr.delete_status = 0
                            GROUP BY 
                              si.product_id
                          ) as sales_return_quantity,

                          (
                            SELECT SUM(si.quantity) FROM sales_return_items si 
                            LEFT JOIN 
                              sales_return pr ON pr.id = si.sales_return_id
                            WHERE 
                              si.product_id = p.id AND pr.delete_status = 0
                            GROUP BY 
                              si.product_id
                          ) as sold_return_quantity,
                         

                          (
                            SELECT SUM(si.quantity) FROM sales_return_delivery_items si 
                            WHERE 
                              si.product_id = p.id
                            GROUP BY 
                              si.product_id
                          ) as sales_return_delivered_quantity,
                          (
                            SELECT group_concat(sr.reference_no SEPARATOR ", ") FROM sales_return_items sri 
                            LEFT JOIN 
                              sales_return sr ON sr.id = sri.sales_return_id
                            WHERE 
                              sri.product_id = p.id AND sr.delete_status = 0
                            GROUP BY 
                              sri.product_id
                          ) as sales_return_invoice,
                          (
                            SELECT SUM(si.quantity) FROM purchase_return_items si 
                            LEFT JOIN 
                              purchase_return pr ON pr.id = si.purchase_return_id
                            WHERE 
                              si.product_id = p.id AND pr.delete_status = 0
                            GROUP BY 
                              si.product_id
                          ) as purchase_return_quantity,

                          (
                            SELECT SUM(si.quantity) FROM purchase_return_items si 
                            LEFT JOIN 
                              purchase_return pr ON pr.id = si.purchase_return_id
                            WHERE 
                              si.product_id = p.id AND pr.delete_status = 0
                            GROUP BY 
                              si.product_id
                          ) as purchased_return_quantity,
                          

                          (
                            SELECT SUM(si.quantity) FROM purchase_return_delivery_items si 
                            WHERE 
                              si.product_id = p.id
                            GROUP BY 
                              si.product_id
                          ) as purchase_return_delivered_quantity,
                          (
                            SELECT group_concat(sr.reference_no SEPARATOR ", ") FROM purchase_return_items sri 
                            LEFT JOIN 
                              purchase_return sr ON sr.id = sri.purchase_return_id
                            WHERE 
                              sri.product_id = p.id AND sr.delete_status = 0
                            GROUP BY 
                              sri.product_id
                          ) as purchase_return_invoice

                      ');
      $this->db->from('product p');
      $this->db->where('p.delete_status',0);

      if(!empty($product_id))
      {
        $this->db->where_in('p.id',$product_id);  
      }
      
      $this->db->order_by('p.name','asc');
      $query = $this->db->get();

      return  $query;
    }

    // Product report function

    function product_sale_report($from_date, $to_date, $customer_id, $product_id, $action_type)
    {
      if($action_type == 'export')
      {
        $this->db->select('
                            si.product_name as "Product Name",
                            wp.batch_no as "Batch No",
                            s.invoice_date as "Invoice Date",
                            s.reference_no as "Invoice No",
                            si.quantity as "Quantity",
                            si.cost as "Cost",
                            si.sub_total as "Total Amount",
                            si.igst as "IGST",
                            si.igst_tax as "IGST Tax",
                            si.sgst as "SGST",
                            si.sgst_tax as "SGST Tax",
                            si.cgst as "CGST",
                            si.cgst_tax as "CGST Tax",
                            c.customer_name as "Customer Name"
                        ');
        $this->db->from('sale_items si');
        $this->db->join('sale s','s.id = si.sale_id','left');
        $this->db->join('customer c','c.id = s.customer_id','left');
        $this->db->join('product p','p.id = si.product_id','left');
        $this->db->join('warehouse_products wp','wp.id = si.warehouse_product_id','left');
        $this->db->order_by('si.product_name','asc');

        if($customer_id != '')
          $this->db->where('s.customer_id',$customer_id);

        if(!empty($product_id))
          $this->db->where_in('si.product_id',$product_id);

        if($from_date != '')
          $this->db->where('s.invoice_date >=', $from_date);

        if($to_date != '')
          $this->db->where('s.invoice_date <=', $to_date);

        $this->db->where('s.delete_status', 0);
        $query = $this->db->get();

        return  $query;
      }
      else
      {
        $this->db->select('
                            si.*,
                            s.*,
                            p.hsn,
                            c.customer_name,
                            wp.batch_no
                        ');
        $this->db->from('sale_items si');
        $this->db->join('sale s','s.id = si.sale_id','left');
        $this->db->join('customer c','c.id = s.customer_id','left');
        $this->db->join('product p','p.id = si.product_id','left');
        $this->db->join('warehouse_products wp','wp.id = si.warehouse_product_id','left');
        $this->db->order_by('si.product_name','asc');

        if($customer_id != '')
          $this->db->where('s.customer_id',$customer_id);

        if(!empty($product_id))
          $this->db->where_in('si.product_id',$product_id);

        if($from_date != '')
          $this->db->where('s.invoice_date >=', $from_date);

        if($to_date != '')
          $this->db->where('s.invoice_date <=', $to_date);

        $this->db->where('s.delete_status', 0);
        $query = $this->db->get();

        return  $query;
      }
      
    }

    // Product report function

    function product_purchase_report($from_date, $to_date, $supplier_id, $product_id, $action_type)
    {

      if($action_type == 'export')
      {
        $this->db->select('
                            si.product_name as "Product Name",
                            si.batch_no as "Batch No",
                            p.salt as "Composition",
                            s.purchase_date as "Purchase Date",
                            s.reference_no as "PO No",
                            si.quantity as "Billed Qty"
                           
                            si.cost as "Cost",
                            si.subtotal as "Total Amount",
                            si.igst as "IGST",
                            si.igst_tax as "IGST Tax",
                            si.sgst as "SGST",
                            si.sgst_tax as "SGST Tax",
                            si.cgst as "CGST",
                            si.cgst_tax as "CGST Tax",
                            c.company_name as "Company Name",
                            p.hsn as "HSN"
                        ');
        $this->db->from('purchase_items si');
        $this->db->join('purchase s','s.id = si.purchase_id','left');
        $this->db->join('supplier c','c.id = s.supplier_id','left');
        $this->db->join('product p','p.id = si.product_id','left');
        // $this->db->where('s.delete_status',0);
        $this->db->order_by('si.product_name','asc');

        if($supplier_id != '')
          $this->db->where('s.supplier_id',$supplier_id);

        if(!empty($product_id))
          $this->db->where_in('si.product_id',$product_id);

        if($from_date != '')
          $this->db->where('s.purchase_date >=', $from_date);

        if($to_date != '')
          $this->db->where('s.purchase_date <=', $to_date);

        $this->db->where('s.delete_status', 0);
        $query = $this->db->get();

        return  $query;
      }
      else
      {
        $this->db->select('
                            si.product_name,
                            si.batch_no,
                            s.purchase_date,
                            s.reference_no,
                            si.quantity,
                           
                            si.cost,
                            si.subtotal,
                            si.igst,
                            si.igst_tax,
                            si.sgst,
                            si.sgst_tax,
                            si.cgst,
                            si.cgst_tax,
                            c.company_name,
                            p.hsn,
                            si.purchase_id,
                           si.product_id
                        ');
        $this->db->from('purchase_items si');
        $this->db->join('purchase s','s.id = si.purchase_id','left');
        $this->db->join('supplier c','c.id = s.supplier_id','left');
        $this->db->join('product p','p.id = si.product_id','left');
        // $this->db->where('s.delete_status',0);
        $this->db->order_by('si.product_name','asc');

        if($supplier_id != '')
          $this->db->where('s.supplier_id',$supplier_id);

        if(!empty($product_id))
          $this->db->where_in('si.product_id',$product_id);

        if($from_date != '')
          $this->db->where('s.purchase_date >=', $from_date);

        if($to_date != '')
          $this->db->where('s.purchase_date <=', $to_date);

        $this->db->where('s.delete_status', 0);
        $query = $this->db->get();

        return  $query;  
      }
      
    }



    function gstr1_report($from_date = null, $to_date = null, $customer_id = null)
    {
      $this->db->select('
                          s.customer_gstin as "CustomerGSTIN",
                          c.customer_name as "CustomerName",
                          DATE_FORMAT(s.invoice_date, "%d-%b-%Y") as "InvoiceDate",
                          s.reference_no as "InvoiceNumber",
                          s.total as "InvoiceValue",
                          CONCAT(st.state_code,"-",st.name) as "PlaceOfSupply",
                          s.rcm as "RCM",
                          GREATEST(MAX(si.sgst*2), MAX(si.cgst*2), MAX(si.igst)) as "TaxRate",
                          SUM(si.taxable_value) as "TaxableValue",
                          s.ecommerce_gstin as "EcommerceGSTIN"
                        ');
      $this->db->from('sale_items si');
      $this->db->join('sale s','si.sale_id = s.id','left');
      $this->db->join('customer c','c.id = s.customer_id','left');
      $this->db->join('states st','st.id = c.shipping_state_id','left');

      if($customer_id != '' && $customer_id != NULL)
        $this->db->where('c.id',$customer_id);

      if($from_date != '' && $from_date != NULL)
        $this->db->where('s.invoice_date >=', $from_date);

      if($to_date != '' && $to_date != NULL)
        $this->db->where('s.invoice_date <=', $to_date);  
      
      $this->db->group_by(array('s.id','si.cgst','si.sgst','si.igst'));

      $this->db->where('s.delete_status', 0);
      $query = $this->db->get();

      return  $query;
    }

    function gstr1_b2b_report($from_date = null, $to_date = null, $customer_id = null)
    {
      $this->db->select('
                          s.customer_gstin as "CustomerGSTIN",
                          c.customer_name as "CustomerName",
                          DATE_FORMAT(s.invoice_date, "%d-%b-%Y") as "InvoiceDate",
                          s.reference_no as "InvoiceNumber",
                          s.total as "InvoiceValue",
                          CONCAT(st.state_code,"-",st.name) as "PlaceOfSupply",
                          s.rcm as "RCM",
                          GREATEST(MAX(si.sgst*2), MAX(si.cgst*2), MAX(si.igst)) as "TaxRate",
                          SUM(si.taxable_value) as "TaxableValue",
                          s.ecommerce_gstin as "EcommerceGSTIN",
                          si.igst as IGST,
                          si.cgst as CGST,
                          si.sgst as SGST
                        ');
      $this->db->from('sale_items si');
      $this->db->join('sale s','si.sale_id = s.id','left');
      $this->db->join('customer c','c.id = s.customer_id','left');
      $this->db->join('states st','st.id = c.shipping_state_id','left');

      if($customer_id != '' && $customer_id != NULL)
        $this->db->where('c.id',$customer_id);

      if($from_date != '' && $from_date != NULL)
        $this->db->where('s.invoice_date >=', $from_date);

      if($to_date != '' && $to_date != NULL)
        $this->db->where('s.invoice_date <=', $to_date);  
      
      $this->db->where('s.customer_gstin !=','');
      
      $this->db->group_by(array('s.id','si.cgst','si.sgst','si.igst'));

      $this->db->where('s.delete_status', 0);
      $query = $this->db->get();

      return  $query;
    }

    function gstr1_b2cl_report($from_date = null, $to_date = null, $customer_id = null)
    {
      $this->db->select('
                          s.customer_gstin as "CustomerGSTIN",
                          c.customer_name as "CustomerName",
                          DATE_FORMAT(s.invoice_date, "%d-%b-%Y") as "InvoiceDate",
                          s.reference_no as "InvoiceNumber",
                          s.total as "InvoiceValue",
                          CONCAT(st.state_code,"-",st.name) as "PlaceOfSupply",
                          s.rcm as "RCM",
                          GREATEST(MAX(si.sgst*2), MAX(si.cgst*2), MAX(si.igst)) as "TaxRate",
                          SUM(si.taxable_value) as "TaxableValue",
                          s.ecommerce_gstin as "EcommerceGSTIN"
                        ');
      $this->db->from('sale_items si');
      $this->db->join('sale s','si.sale_id = s.id','left');
      $this->db->join('customer c','c.id = s.customer_id','left');
      $this->db->join('states st','st.id = c.shipping_state_id','left');

      if($customer_id != '' && $customer_id != NULL)
        $this->db->where('c.id',$customer_id);

      if($from_date != '' && $from_date != NULL)
        $this->db->where('s.invoice_date >=', $from_date);

      if($to_date != '' && $to_date != NULL)
        $this->db->where('s.invoice_date <=', $to_date);  

      $this->db->group_start();
      $this->db->where('s.customer_gstin',""); 
      $this->db->where('st.id !=',$this->session->userdata('company_state_id')); 
      $this->db->where('s.total >=',250000); 
      $this->db->group_end();

      $this->db->group_by(array('s.id','si.cgst','si.sgst','si.igst'));

      $this->db->where('s.delete_status', 0);
      $query = $this->db->get();

      return  $query;
    }

    function gstr1_b2cs_report($from_date = null, $to_date = null, $customer_id = null)
    {
      $this->db->select('
                          s.customer_gstin as "CustomerGSTIN",
                          c.customer_name as "CustomerName",
                          DATE_FORMAT(s.invoice_date, "%d-%b-%Y") as "InvoiceDate",
                          s.reference_no as "InvoiceNumber",
                          s.total as "InvoiceValue",
                          CONCAT(st.state_code,"-",st.name) as "PlaceOfSupply",
                          s.rcm as "RCM",
                          GREATEST(MAX(si.sgst*2), MAX(si.cgst*2), MAX(si.igst)) as "TaxRate",
                          SUM(si.taxable_value) as "TaxableValue",
                          s.ecommerce_gstin as "EcommerceGSTIN"
                        ');
      $this->db->from('sale_items si');
      $this->db->join('sale s','si.sale_id = s.id','left');
      $this->db->join('customer c','c.id = s.customer_id','left');
      $this->db->join('states st','st.id = c.shipping_state_id','left');

      if($customer_id != '' && $customer_id != NULL)
        $this->db->where('c.id',$customer_id);

      if($from_date != '' && $from_date != NULL)
        $this->db->where('s.invoice_date >=', $from_date);

      if($to_date != '' && $to_date != NULL)
        $this->db->where('s.invoice_date <=', $to_date);  

      $this->db->where('s.customer_gstin',""); 
          
      $this->db->group_start();

        $this->db->group_start();
          $this->db->where('st.id',$this->session->userdata('company_state_id')); 
        $this->db->group_end();

        $this->db->or_group_start();
          $this->db->where('st.id !=',$this->session->userdata('company_state_id')); 
          $this->db->where('s.total <',250000); 
        $this->db->group_end();
      
      $this->db->group_end(); 

      $this->db->group_by(array('s.id','si.cgst','si.sgst','si.igst'));

      $this->db->where('s.delete_status', 0);
      $query = $this->db->get();

      return  $query;
    }

    function gstr1_cdnr_report($from_date = null, $to_date = null, $customer_id = null)
    {
      $this->db->select('
                          s.customer_gstin as "CustomerGSTIN",
                          c.customer_name as "CustomerName",
                          DATE_FORMAT(s.sales_return_date, "%d-%b-%Y") as "NoteDate",
                          s.reference_no as "NoteNumber",
                          "C" as "NoteType",
                          CONCAT(st.state_code,"-",st.name) as "PlaceOfSupply",
                          sl.rcm as "RCM",
                          "Regular B2B" as "NoteSupplyType",
                          s.total as "NoteValue",
                          GREATEST(MAX(sri.sgst*2), MAX(sri.cgst*2), MAX(sri.igst)) as "TaxRate",
                          SUM(sri.taxable_value) as "TaxableValue",
                          "0" as "CessAmount"
                        ');
      $this->db->from('sales_return_items sri');
      $this->db->join('sales_return s','sri.sales_return_id = s.id','left');
      $this->db->join('sale sl','sl.reference_no = s.invoice_no','left');
      $this->db->join('customer c','c.id = s.customer_id','left');
      $this->db->join('states st','st.id = c.shipping_state_id','left');

      if($customer_id != '' && $customer_id != NULL)
        $this->db->where('c.id',$customer_id);

      if($from_date != '' && $from_date != NULL)
        $this->db->where('s.sales_return_date >=', $from_date);

      if($to_date != '' && $to_date != NULL)
        $this->db->where('s.sales_return_date <=', $to_date);  
      
      $this->db->where('s.customer_gstin !=','');
      
      $this->db->group_by(array('s.id','sri.cgst','sri.sgst','sri.igst'));

      $this->db->where('s.delete_status', 0);
      $query = $this->db->get();

      return  $query;
    }

    function gstr1_cdnur_report($from_date = null, $to_date = null, $customer_id = null)
    {
      $this->db->select('
                          "B2CL" as "URType",
                          s.reference_no as "NoteNumber",
                          DATE_FORMAT(s.sales_return_date, "%d-%b-%Y") as "NoteDate",
                          "C" as "NoteType",
                          CONCAT(st.state_code,"-",st.name) as "PlaceOfSupply",
                          s.total as "NoteValue",
                          GREATEST(MAX(sri.sgst*2), MAX(sri.cgst*2), MAX(sri.igst)) as "TaxRate",
                          SUM(sri.taxable_value) as "TaxableValue",
                          "0" as "CessAmount"
                        ');
      $this->db->from('sales_return_items sri');
      $this->db->join('sales_return s','sri.sales_return_id = s.id','left');
      $this->db->join('sale sl','sl.reference_no = s.invoice_no','left');
      $this->db->join('customer c','c.id = s.customer_id','left');
      $this->db->join('states st','st.id = c.shipping_state_id','left');

      if($customer_id != '' && $customer_id != NULL)
        $this->db->where('c.id',$customer_id);

      if($from_date != '' && $from_date != NULL)
        $this->db->where('s.sales_return_date >=', $from_date);

      if($to_date != '' && $to_date != NULL)
        $this->db->where('s.sales_return_date <=', $to_date);  
      
      $this->db->where('s.customer_gstin','');
      
      $this->db->group_by(array('s.id','sri.cgst','sri.sgst','sri.igst'));

      $this->db->where('s.delete_status', 0);
      $query = $this->db->get();

      return  $query;
    }

    function gstr2_report($from_date = null, $to_date = null, $supplier_id = null)
    {
      $this->db->select('
                          s.supplier_gstin as "SupplierGSTIN",
                          c.company_name as "CompanyName",
                          DATE_FORMAT(s.purchase_date, "%d-%b-%Y") as "PurchaseDate",
                          s.reference_no as "InvoiceNumber",
                          s.total as "InvoiceValue",
                          CONCAT(st.state_code,"-",st.name) as "PlaceOfSupply",
                          GREATEST(MAX(si.sgst*2), MAX(si.cgst*2), MAX(si.igst)) as "TaxRate",
                          SUM(si.taxable_value) as "TaxableValue"
                        ');
      $this->db->from('purchase_items si');
      $this->db->join('purchase s','si.purchase_id = s.id','left');
      $this->db->join('supplier c','c.id = s.supplier_id','left');
      $this->db->join('states st','st.id = c.state_id','left');

      if($supplier_id != '' && $supplier_id != NULL)
        $this->db->where('c.id',$supplier_id);

      if($from_date != '' && $from_date != NULL)
        $this->db->where('s.purchase_date >=', $from_date);

      if($to_date != '' && $to_date != NULL)
        $this->db->where('s.purchase_date <=', $to_date);  
      
      $this->db->group_by(array('s.id','si.cgst','si.sgst','si.igst'));

      $this->db->where('s.delete_status', 0);
      $query = $this->db->get();

      return  $query;
    }

    public function transaction_report($from_date = null,$to_date = null,$module = null,$ledger_id = null,$type = null,$reference_no = null,$narration = null,$mode = null)
    {
      $this->db->select('
        th.*,
        l.title
      ');
      $this->db->from('transaction_header th');
      $this->db->join('ledger l','l.id = th.from_account','left');
      

      if($module != '')
        $this->db->where('th.module', $module);
      
      if($ledger_id != '')
      {
        $this->db->group_start();
          $this->db->where('th.from_account', $ledger_id);
          $this->db->or_where('th.to_account', $ledger_id);
        $this->db->group_end();
      }

      if($type != '')
        $this->db->where('th.type', $type);

      if($mode != '')
        $this->db->where('th.mode', $mode);

      if($reference_no != '' || $narration != '')
      {
        $this->db->group_start();
        $this->db->like('th.reference_no', $reference_no,'both');
        $this->db->or_like('th.reference_no', $reference_no,'both');
        $this->db->group_end();
      }

      if($from_date != '')
        $this->db->where('th.voucher_date >=', $from_date);

      if($to_date != '')
        $this->db->where('th.voucher_date <=', $to_date);
      
      $query = $this->db->get();

      return  $query;
    }

    public function daily_report($date, $customer_id = null, $order_time = null)
    {
        $this->db->select('
                            s.*,
                            c.customer_name
                          ');
        $this->db->from('sale s');
        $this->db->join('customer c', 'c.id = s.customer_id', 'left');

        if ($customer_id != null) {
            $this->db->where('s.customer_id', $customer_id);
        }
          
        if ($date != '') {
            $this->db->where('DATE(s.created_date)', $date);
        }

        if ($order_time == 'AM') {
            $this->db->where('TIME(s.created_date) >=', '00:00:00');
            $this->db->where('TIME(s.created_date) <', '12:00:00');
        } elseif ($order_time == 'PM') {
            $this->db->where('TIME(s.created_date) >=', '12:00:00');
            $this->db->where('TIME(s.created_date) <', '23:59:59');
        }

        $this->db->where('s.delete_status', 0);
        $query = $this->db->get();

        return $query;
    }

    public function get_customers_with_orders_by_date_and_time($date, $warehouse_id = '', $customer_id = '', $order_time = '')
    {
        $this->db->distinct('s.customer_id');
        $this->db->select('s.customer_id');
        $this->db->from('sale s');
        
        $this->db->where('s.delete_status', 0);

        if($warehouse_id != '')
          $this->db->where('s.warehouse_id', $warehouse_id);

        if($customer_id != '')
          $this->db->where('s.customer_id', $customer_id);

        $this->db->where('DATE(s.created_date)', $date);

        if ($order_time == 'AM') {
          $this->db->where('TIME(s.created_date) >=', '00:00:00');
          $this->db->where('TIME(s.created_date) <', '12:00:00');
        } elseif ($order_time == 'PM') {
          $this->db->where('TIME(s.created_date) >=', '12:00:00');
          $this->db->where('TIME(s.created_date) <', '23:59:59');
        }

        $query = $this->db->get();

        return $query;
    }

    public function get_warehouses_with_orders_by_date_and_time($date, $order_time = '')
    {
        $this->db->distinct('s.warehouse_id');
        $this->db->select('s.warehouse_id');
        $this->db->from('sale s');
        
        $this->db->where('s.delete_status', 0);
        
        $this->db->where('DATE(s.created_date)', $date);

        if ($order_time == 'AM') {
            $this->db->where('TIME(s.created_date) >=', '00:00:00');
            $this->db->where('TIME(s.created_date) <', '12:00:00');
        } elseif ($order_time == 'PM') {
            $this->db->where('TIME(s.created_date) >=', '12:00:00');
            $this->db->where('TIME(s.created_date) <', '23:59:59');
        }

        $query = $this->db->get();
        return $query;
    }

    public function get_product_details_with_totals($date, $order_time = null, $warehouse_id = null)
    {
      $this->db->select('
          si.warehouse_product_id,
          si.product_name,            
          SUM(si.quantity) as total_quantity,
          SUM(si.selling_price * si.quantity) as total_selling_price,
          SUM(si.taxable_value * si.quantity) as total_taxable_value,
          SUM(si.discount_amount) as total_discount_amount,
          SUM(si.igst_tax) as total_igst_tax,
          SUM(si.cgst_tax) as total_cgst_tax,
          SUM(si.sgst_tax) as total_sgst_tax
      ');
      $this->db->from('sale s');
      $this->db->join('sale_items si', 'si.sale_id = s.id', 'left');
      $this->db->join('product p', 'p.id = si.product_id', 'left');

      $this->db->where('s.delete_status', 0);
      $this->db->where('DATE(s.created_date)', $date);

      if ($warehouse_id != null) {
          $this->db->where('s.warehouse_id', $warehouse_id);
      }

      if ($order_time == 'AM') {
          $this->db->where('TIME(s.created_date) >=', '00:00:00');
          $this->db->where('TIME(s.created_date) <', '12:00:00');
      } elseif ($order_time == 'PM') {
          $this->db->where('TIME(s.created_date) >=', '12:00:00');
          $this->db->where('TIME(s.created_date) <=', '23:59:59'); // Adjusted this line
      }

      $this->db->group_by('si.warehouse_product_id, si.product_name');
      $query = $this->db->get();

      return $query;
    }
    /**
 * Get Stock Movement Summary with Opening and Closing Balance
 */
/**
 * Get Stock Movement Summary with Opening and Closing Balance
 */
public function get_stock_movement_summary($from_date = null, $to_date = null, $product_id = null, $warehouse_id = null)
{
    // Reset variables for each execution
    $this->db->query("SET @running_qty = 0");
    $this->db->query("SET @running_value = 0");
    
    $where_conditions = "";
    if($from_date) {
        $where_conditions .= " AND transaction_date >= '" . $from_date . "'";
    }
    if($to_date) {
        $where_conditions .= " AND transaction_date <= '" . $to_date . "'";
    }
    if($product_id && $product_id != '') {
        $where_conditions .= " AND product_id = " . $this->db->escape($product_id);
    }
    if($warehouse_id && $warehouse_id != '') {
        $where_conditions .= " AND warehouse_id = " . $this->db->escape($warehouse_id);
    }
    
    $sql = "
        SELECT 
            transaction_date,
            transaction_type,
            product_id,
            product_name,
            branch_name,
            in_qty,
            out_qty,
            purchase_amount,
            sale_amount,
            running_qty AS closing_qty,
            running_value AS closing_value
        FROM (
            SELECT 
                transaction_date,
                transaction_type,
                product_id,
                product_name,
                branch_name,
                in_qty,
                out_qty,
                purchase_amount,
                sale_amount,
                @running_qty := @running_qty + in_qty - out_qty AS running_qty,
                @running_value := @running_value + in_value - out_value AS running_value
            FROM (
                -- PURCHASE IN
                SELECT 
                    pd.delivery_date AS transaction_date,
                    'PURCHASE' AS transaction_type,
                    pr.id AS product_id,
                    pr.name AS product_name,
                    w.id AS warehouse_id,
                    w.name AS branch_name,
                    pdi.quantity AS in_qty,
                    0 AS out_qty,
                    (pdi.cost * pdi.quantity) AS purchase_amount,
                    0 AS sale_amount,
                    (pdi.cost * pdi.quantity) AS in_value,
                    0 AS out_value
                FROM purchase_delivery_items pdi
                JOIN purchase_delivery pd ON pd.id = pdi.purchase_delivery_id
                JOIN purchase p ON p.id = pd.purchase_id
                JOIN product pr ON pr.id = pdi.product_id
                LEFT JOIN warehouse w ON w.id = p.warehouse_id
                WHERE pr.delete_status = 0
                
                UNION ALL
                
                -- SALE OUT
                SELECT 
                    s.invoice_date AS transaction_date,
                    'SALE' AS transaction_type,
                    pr.id AS product_id,
                    pr.name AS product_name,
                    w.id AS warehouse_id,
                    w.name AS branch_name,
                    0 AS in_qty,
                    si.quantity AS out_qty,
                    0 AS purchase_amount,
                    (si.selling_price * si.quantity) AS sale_amount,
                    0 AS in_value,
                    (si.selling_price * si.quantity) AS out_value
                FROM sale_items si
                JOIN sale s ON s.id = si.sale_id
                JOIN product pr ON pr.id = si.product_id
                LEFT JOIN warehouse w ON w.id = s.warehouse_id
                WHERE s.delete_status = 0 AND pr.delete_status = 0
                
                UNION ALL
                
                -- SALES RETURN (Stock IN)
                SELECT 
                    srd.delivery_date AS transaction_date,
                    'SALES RETURN' AS transaction_type,
                    pr.id AS product_id,
                    pr.name AS product_name,
                    NULL AS warehouse_id,
                    NULL AS branch_name,
                    srdi.quantity AS in_qty,
                    0 AS out_qty,
                    0 AS purchase_amount,
                    0 AS sale_amount,
                    (srdi.cost * srdi.quantity) AS in_value,
                    0 AS out_value
                FROM sales_return_delivery_items srdi
                JOIN sales_return_delivery srd ON srd.id = srdi.sales_return_delivery_id
                JOIN sales_return sr ON sr.id = srd.sales_return_id
                JOIN product pr ON pr.id = srdi.product_id
                WHERE pr.delete_status = 0
                
                UNION ALL
                
                -- PURCHASE RETURN (Stock OUT)
                SELECT 
                    prd.delivery_date AS transaction_date,
                    'PURCHASE RETURN' AS transaction_type,
                    pr.id AS product_id,
                    pr.name AS product_name,
                    w.id AS warehouse_id,
                    w.name AS branch_name,
                    0 AS in_qty,
                    prdi.quantity AS out_qty,
                    0 AS purchase_amount,
                    0 AS sale_amount,
                    0 AS in_value,
                    (prdi.cost * prdi.quantity) AS out_value
                FROM purchase_return_delivery_items prdi
                JOIN purchase_return_delivery prd ON prd.id = prdi.purchase_return_delivery_id
                JOIN purchase_return pre ON pre.id = prd.purchase_return_id
                JOIN product pr ON pr.id = prdi.product_id
                LEFT JOIN warehouse w ON w.id = prdi.warehouse_id
                WHERE pr.delete_status = 0
            ) AS movements
            WHERE 1=1 $where_conditions
            ORDER BY product_name, transaction_date ASC
        ) AS running
        ORDER BY product_name, transaction_date ASC
    ";
    
    return $this->db->query($sql)->result();
}

/**
 * Get Opening Stock Balance before the from_date
 */
public function get_opening_stock_balance($from_date, $product_id = null, $warehouse_id = null)
{
    $where = "";
    if($product_id && $product_id != '') {
        $where .= " AND product_id = " . $this->db->escape($product_id);
    }
    if($warehouse_id && $warehouse_id != '') {
        $where .= " AND warehouse_id = " . $this->db->escape($warehouse_id);
    }
    
    $sql = "
        SELECT 
            product_id,
            product_name,
            warehouse_id,
            branch_name,
            SUM(in_qty) - SUM(out_qty) AS opening_qty,
            SUM(in_value) - SUM(out_value) AS opening_value
        FROM (
            -- Purchase before date
            SELECT 
                pr.id AS product_id,
                pr.name AS product_name,
                w.id AS warehouse_id,
                w.name AS branch_name,
                pdi.quantity AS in_qty,
                0 AS out_qty,
                (pdi.cost * pdi.quantity) AS in_value,
                0 AS out_value
            FROM purchase_delivery_items pdi
            JOIN purchase_delivery pd ON pd.id = pdi.purchase_delivery_id
            JOIN purchase p ON p.id = pd.purchase_id
            JOIN product pr ON pr.id = pdi.product_id
            LEFT JOIN warehouse w ON w.id = p.warehouse_id
            WHERE pd.delivery_date < '" . $from_date . "'
            AND pr.delete_status = 0
            
            UNION ALL
            
            -- Sale before date
            SELECT 
                pr.id AS product_id,
                pr.name AS product_name,
                w.id AS warehouse_id,
                w.name AS branch_name,
                0 AS in_qty,
                si.quantity AS out_qty,
                0 AS in_value,
                (si.selling_price * si.quantity) AS out_value
            FROM sale_items si
            JOIN sale s ON s.id = si.sale_id
            JOIN product pr ON pr.id = si.product_id
            LEFT JOIN warehouse w ON w.id = s.warehouse_id
            WHERE s.invoice_date < '" . $from_date . "'
            AND s.delete_status = 0 AND pr.delete_status = 0
        ) AS opening
        WHERE 1=1 $where
        GROUP BY product_id, warehouse_id
    ";
    
    return $this->db->query($sql)->result();
}

/**
 * Get Alert Qty for Products (based on reorder level)
 */
 /**
 * Get Alert Qty for Products (based on alert_quantity column)
 */
public function get_alert_qty($product_id = null)
{
    $where = "";
    if($product_id && $product_id != '') {
        $where .= " AND id = " . $this->db->escape($product_id);
    }
    
    $sql = "
        SELECT 
            id AS product_id,
            name AS product_name,
            COALESCE(alert_quantity, 0) AS alert_qty
        FROM product
        WHERE delete_status = 0
        $where
    ";
    
    $results = $this->db->query($sql)->result();
    $alert_data = [];
    foreach($results as $row) {
        $alert_data[$row->product_id] = $row->alert_qty;
    }
    return $alert_data;
}

    // public function get_stock_movements($from, $to, $pid = null, $warehouse_id = null) {
    //     $where = " WHERE 1=1 ";
    //     if($pid && $pid != '') $where .= " AND m.product_id = '$pid'";
    //     if($warehouse_id) $where .= " AND m.warehouse_id = '$warehouse_id'";
    
    //      $sql = "
    //         SELECT 
    //             m.*, 
    //             p.name AS product_name, 
    //             p.pid AS product_pid, 
    //             p.alert_quantity AS product_alert_limit,
    //             w.name AS branch_name 
    //         FROM (
    //             /* 1. Purchases - Take cost from purchase_items */
    //             SELECT pur.purchase_date as date, 'Purchase' as type, pur.reference_no as ref_no, pi.product_id, pi.quantity as in_qty, 0 as out_qty, pi.subtotal as pur_amt, 0 as sale_amt, pur.warehouse_id, 
    //             (pi.subtotal / pi.quantity) as tran_unit_cost /* <--- Actual Price Paid per unit */
    //             FROM purchase_items pi 
    //             JOIN purchase pur ON pur.id = pi.purchase_id 
    //             INNER JOIN purchase_delivery pd ON pd.purchase_id = pur.id 
    //             WHERE pur.delete_status = 0
    //             GROUP BY pi.id
        
    //             UNION ALL
        
    //             /* 2. Sales - Cost is usually 0 here, we will handle in Controller */
    //             SELECT s.invoice_date, 'Sale', s.reference_no, si.product_id, 0, si.quantity, 0, si.sub_total, s.warehouse_id, 
    //             0 as tran_unit_cost 
    //             FROM sale_items si JOIN sale s ON s.id = si.sale_id INNER JOIN sale_delivery sd ON sd.sale_id = s.id WHERE s.delete_status = 0 GROUP BY si.id
        
    //             UNION ALL
        
    //             /* 3. Manual Stock Entries - Take product_cost from stock table */
    //             SELECT created_date, CASE WHEN entry_type='in' THEN 'Stock In' ELSE 'Stock Out' END, 'Manual', product_id, CASE WHEN entry_type='in' THEN quantity ELSE 0 END, CASE WHEN entry_type='out' THEN quantity ELSE 0 END, 0, 0, warehouse_id, 
    //             product_cost as tran_unit_cost 
    //             FROM stock WHERE delete_status = 0
        
    //             UNION ALL
        
    //             /* 4. Sales Return - Take cost from items */
    //             SELECT sr.sales_return_date, 'Sales Return', sr.reference_no, sri.product_id, sri.quantity, 0, sri.sub_total, 0, sr.warehouse_id, 
    //             sri.cost as tran_unit_cost 
    //             FROM sales_return_items sri JOIN sales_return sr ON sr.id = sri.sales_return_id WHERE sr.delete_status = 0
        
    //             UNION ALL
        
    //             /* 5. Purchase Return */
    //             SELECT pr.purchase_return_date, 'Purch Return', pr.reference_no, pri.product_id, 0, pri.quantity, 0, 0, pr.warehouse_id, 
    //             pri.cost as tran_unit_cost 
    //             FROM purchase_return_items pri JOIN purchase_return pr ON pr.id = pri.purchase_return_id WHERE pr.delete_status = 0
    //         ) as m
    //         JOIN product p ON p.id = m.product_id
    //         JOIN warehouse w ON w.id = m.warehouse_id
    //         $where
    //         AND m.date BETWEEN '$from' AND '$to'
    //         ORDER BY m.date ASC, m.product_id ASC";
    
    //     return $this->db->query($sql)->result();
    // }
        
    //   public function get_opening_stock_before_date($date, $product_id, $warehouse_id = null) {
    //     $wh_query = "";
    //     if($warehouse_id) $wh_query = " AND warehouse_id = " . $this->db->escape($warehouse_id);
    
    //     $sql = "
    //     SELECT SUM(in_qty - out_qty) as balance FROM (
    //         SELECT pi.quantity as in_qty, 0 as out_qty, pur.warehouse_id, pi.product_id, pur.purchase_date as d FROM purchase_items pi JOIN purchase pur ON pur.id = pi.purchase_id INNER JOIN purchase_delivery pd ON pd.purchase_id = pur.id WHERE pur.delete_status = 0
    //         UNION ALL
    //         SELECT 0, si.quantity, s.warehouse_id, si.product_id, s.invoice_date FROM sale_items si JOIN sale s ON s.id = si.sale_id INNER JOIN sale_delivery sd ON sd.sale_id = s.id WHERE s.delete_status = 0
    //         UNION ALL
    //         SELECT CASE WHEN entry_type='in' THEN quantity ELSE 0 END, CASE WHEN entry_type='out' THEN quantity ELSE 0 END, warehouse_id, product_id, created_date FROM stock WHERE delete_status = 0
    //         UNION ALL
    //         SELECT sri.quantity, 0, sr.warehouse_id, sri.product_id, sr.sales_return_date FROM sales_return_items sri JOIN sales_return sr ON sr.id = sri.sales_return_id WHERE sr.delete_status = 0
    //         UNION ALL
    //         SELECT 0, pri.quantity, pr.warehouse_id, pri.product_id, pr.purchase_return_date FROM purchase_return_items pri JOIN purchase_return pr ON pr.id = pri.purchase_return_id WHERE pr.delete_status = 0
    //     ) as t 
    //     WHERE t.product_id = " . $this->db->escape($product_id) . " AND t.d < " . $this->db->escape($date) . " $wh_query";
        
    //     $res = $this->db->query($sql)->row();
    //     return (isset($res->balance)) ? (float)$res->balance : 0;
    // } 
    
    public function get_stock_movements($from, $to, $pid = null, $warehouse_id = null) {
    $where = " WHERE 1=1 ";
    if($pid && $pid != '') $where .= " AND m.product_id = " . $this->db->escape($pid);
    if($warehouse_id) $where .= " AND m.warehouse_id = " . $this->db->escape($warehouse_id);

    $sql = "
        SELECT 
            m.*, 
            p.name AS product_name, 
            p.pid AS product_pid, 
            p.alert_quantity AS product_alert_limit,
            COALESCE(w.name, 'Main Office') AS branch_name 
        FROM (
            /* 1. Purchases */
            SELECT pur.purchase_date as date, 'Purchase' as type, pur.reference_no as ref_no, pi.product_id, pi.quantity as in_qty, 0 as out_qty, pi.subtotal as pur_amt, 0 as sale_amt, pur.warehouse_id, (pi.subtotal / pi.quantity) as tran_unit_cost FROM purchase_items pi JOIN purchase pur ON pur.id = pi.purchase_id INNER JOIN purchase_delivery pd ON pd.purchase_id = pur.id WHERE pur.delete_status = 0 GROUP BY pi.id

            UNION ALL

            /* 2. Sales */
            SELECT s.invoice_date as date, 'Sale', s.reference_no, si.product_id, 0, si.quantity, 0, si.sub_total, s.warehouse_id, 0 as tran_unit_cost FROM sale_items si JOIN sale s ON s.id = si.sale_id INNER JOIN sale_delivery sd ON sd.sale_id = s.id WHERE s.delete_status = 0 GROUP BY si.id

            UNION ALL

            /* 3. Manual Stock Entries - FIX: Added DATE() and calculated pur_amt */
            SELECT DATE(created_date) as date, CASE WHEN entry_type='in' THEN 'Stock In' ELSE 'Stock Out' END as type, 'Manual' as ref_no, product_id, CASE WHEN entry_type='in' THEN quantity ELSE 0 END as in_qty, CASE WHEN entry_type='out' THEN quantity ELSE 0 END as out_qty, (quantity * product_cost) as pur_amt, 0 as sale_amt, warehouse_id, product_cost as tran_unit_cost 
            FROM stock 
            WHERE delete_status = 0

            UNION ALL

            /* 4. Sales Return */
            SELECT sr.sales_return_date as date, 'Sales Return', sr.reference_no, sri.product_id, sri.quantity, 0, sri.sub_total, 0, sr.warehouse_id, sri.cost as tran_unit_cost FROM sales_return_items sri JOIN sales_return sr ON sr.id = sri.sales_return_id WHERE sr.delete_status = 0

            UNION ALL

            /* 5. Purchase Return */
            SELECT pr.purchase_return_date as date, 'Purch Return', pr.reference_no, pri.product_id, 0, pri.quantity, 0, 0, pr.warehouse_id, pri.cost as tran_unit_cost FROM purchase_return_items pri JOIN purchase_return pr ON pr.id = pri.purchase_return_id WHERE pr.delete_status = 0
        ) as m
        JOIN product p ON p.id = m.product_id
        /* 🔥 THE BIG FIX: Use LEFT JOIN so rows aren't hidden if branch ID is 0 or null */
        LEFT JOIN warehouse w ON w.id = m.warehouse_id 
        $where
        AND m.date BETWEEN '$from' AND '$to'
        ORDER BY m.date ASC, m.product_id ASC";

    return $this->db->query($sql)->result();
}

public function get_opening_stock_before_date($date, $product_id, $warehouse_id = null) {
    $wh_query = "";
    if($warehouse_id) $wh_query = " AND warehouse_id = " . $this->db->escape($warehouse_id);

    $sql = "
    SELECT SUM(in_qty - out_qty) as balance FROM (
        SELECT pi.quantity as in_qty, 0 as out_qty, pur.warehouse_id, pi.product_id, pur.purchase_date as d FROM purchase_items pi JOIN purchase pur ON pur.id = pi.purchase_id INNER JOIN purchase_delivery pd ON pd.purchase_id = pur.id WHERE pur.delete_status = 0
        UNION ALL
        SELECT 0, si.quantity, s.warehouse_id, si.product_id, s.invoice_date FROM sale_items si JOIN sale s ON s.id = si.sale_id INNER JOIN sale_delivery sd ON sd.sale_id = s.id WHERE s.delete_status = 0
        UNION ALL
        SELECT CASE WHEN entry_type='in' THEN quantity ELSE 0 END, CASE WHEN entry_type='out' THEN quantity ELSE 0 END, warehouse_id, product_id, DATE(created_date) FROM stock WHERE delete_status = 0
        UNION ALL
        SELECT sri.quantity, 0, sr.warehouse_id, sri.product_id, sr.sales_return_date FROM sales_return_items sri JOIN sales_return sr ON sr.id = sri.sales_return_id WHERE sr.delete_status = 0
        UNION ALL
        SELECT 0, pri.quantity, pr.warehouse_id, pri.product_id, pr.purchase_return_date FROM purchase_return_items pri JOIN purchase_return pr ON pr.id = pri.purchase_return_id WHERE pr.delete_status = 0
        UNION ALL
        /* 🔥 ADD INITIAL STOCK FROM PRODUCT ADDITION */
        SELECT quantity as in_qty, 0 as out_qty, warehouse_id, product_id, DATE(created_date) as d FROM warehouse_products
    ) as t 
    WHERE t.product_id = " . $this->db->escape($product_id) . " AND t.d < " . $this->db->escape($date) . " $wh_query";
    
    $res = $this->db->query($sql)->row();
    return (isset($res->balance)) ? (float)$res->balance : 0;
}
    
}
?>
