<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_model extends CI_Model {

    var $table = 'sale_view';
    var $column_order = array('reference_no','invoice_date','customer_name','total_discount','total_taxable_value','tds','total_tax','total'); //set column field database for datatable orderable
    var $column_search = array('reference_no','invoice_date','customer_name','total_discount','total_taxable_value','tds','total_tax','total'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

    var $is_customer = false;

    function __construct() {
        parent::__construct();

        if ($this->ion_auth->in_group(CUSTOMER_GROUP_NAME))
            $this->is_customer = true;
    }	

public function get_sale_records($limit = null)
{
    $user_id = $this->session->userdata('user_id');
    $branch_data = $this->db->select('branch_id')
                            ->from('users')
                            ->where('id', $user_id)
                            ->get()
                            ->row();

    if ($branch_data && $branch_data->branch_id) {
     
        $warehouse_ids = $this->db->select('id')
                                  ->from('warehouse')
                                  ->where('id', $branch_data->branch_id)
                                  ->get()
                                  ->result_array();

        $warehouse_ids = array_column($warehouse_ids, 'id'); 
    } else {
        $warehouse_ids = []; 
    }

    if ($this->permission_model->has_permission('list_all_sale')) {
        $this->db->select('
            s.*,
            c.customer_name, c.gstin, c.state_name, c.customer_company_name,
            w.name as warehouse_name, 
            si.product_id, si.quantity, si.selling_price,
            p.name, p.description, p.product_category_id, p.hsn, p.uom_id
        ')
        ->from('sale s')
        ->join('customer c', 'c.id = s.customer_id', 'left')
        ->join('warehouse w', 'w.id = s.warehouse_id', 'left')
        ->join('sale_items si', 'si.sale_id = s.id', 'left')
        ->join('product p', 'p.id = si.product_id', 'left')
        ->where('s.delete_status', 0);

        if (!empty($warehouse_ids)) {
            $this->db->where_in('s.warehouse_id', $warehouse_ids);
        }

        $this->db->order_by('s.created_date', 'desc');

        if ($limit != null) {
            $this->db->limit($limit);
        }

        return $this->db->get()->result();
    } else {
        // Additional logic for customer/user permissions
        $user_id = csession('user_id');
        if ($is_customer) {
            $customer = $this->utility_model->get_records_by_field('customer', 'user_id', $user_id, true, false);
            $customer_id = $customer->id;

            $this->db->select('
                s.*,
                c.customer_name, c.gstin, c.state_name,c.customer_company_name,
                w.name as warehouse_name, 
                si.product_id, si.quantity, si.selling_price,
                p.name, p.description, p.product_category_id, p.hsn, p.uom_id
            ')
            ->from('sale s')
            ->join('customer c', 'c.id = s.customer_id', 'left')
            ->join('warehouse w', 'w.id = s.warehouse_id', 'left')
            ->join('sale_items si', 'si.sale_id = s.id', 'left')
            ->join('product p', 'p.id = si.product_id', 'left')
            ->where('s.delete_status', 0)
            ->group_start()
                ->where('s.customer_id', $customer_id)
                ->or_where('s.user_id', $user_id)
            ->group_end();

            if (!empty($warehouse_ids)) {
                $this->db->where_in('s.warehouse_id', $warehouse_ids);
            }

            $this->db->order_by('s.created_date', 'desc');

            if ($limit != null) {
                $this->db->limit($limit);
            }

            return $this->db->get()->result();
        } else {
            // Logic for users without customer permissions
            $this->db->select('
                s.*,
                c.customer_name, c.gstin, c.state_name,c.customer_company_name,
                w.name as warehouse_name, 
                si.product_id, si.quantity, si.selling_price,
                p.name, p.description, p.product_category_id, p.hsn, p.uom_id
            ')
            ->from('sale s')
            ->join('customer c', 'c.id = s.customer_id', 'left')
            ->join('warehouse w', 'w.id = s.warehouse_id', 'left')
            ->join('sale_items si', 'si.sale_id = s.id', 'left')
            ->join('product p', 'p.id = si.product_id', 'left')
            ->where('s.delete_status', 0)
            ->where('s.user_id', $user_id);

            if (!empty($warehouse_ids)) {
                $this->db->where_in('s.warehouse_id', $warehouse_ids);
            }

            $this->db->order_by('s.created_date', 'desc');

            if ($limit != null) {
                $this->db->limit($limit);
            }

            return $this->db->get()->result();
        }
    }
}

    public function get_delivered_sale_records()
    {
         $user_id = $this->session->userdata('user_id');
    
        $branch_data = $this->db->select('branch_id')
                                ->from('users')
                                ->where('id', $user_id)
                                ->get()
                                ->row();
    
        if ($branch_data && $branch_data->branch_id) {
    
            $warehouse_ids = $this->db->select('id')
                                      ->from('warehouse')
                                      ->where('id', $branch_data->branch_id)
                                      ->get()
                                      ->result_array();
    
            $warehouse_ids = array_column($warehouse_ids, 'id');
    
        } else {
            $warehouse_ids = [];
        }
    
        if ($this->permission_model->has_permission('list_all_sale')) {
    
            $this->db->select('
                s.*,
                c.customer_name,
                c.gstin,
                c.state_name,
                c.customer_company_name,
                w.name as warehouse_name,
                si.product_id,
                si.quantity,
                si.selling_price,
                p.name,
                p.description,
                p.product_category_id,
                p.hsn,
                p.uom_id
            ')
            ->from('sale s')
            ->join('customer c', 'c.id = s.customer_id', 'left')
            ->join('warehouse w', 'w.id = s.warehouse_id', 'left')
            ->join('sale_items si', 'si.sale_id = s.id', 'left')
            ->join('product p', 'p.id = si.product_id', 'left')
    
            // DELIVERY FILTER ADDED
            ->join('sale_delivery sd', 'sd.sale_id = s.id', 'inner')
    
            ->where('s.delete_status', 0);
    
            if (!empty($warehouse_ids)) {
                $this->db->where_in('s.warehouse_id', $warehouse_ids);
            }
    
            // PREVENT DUPLICATES
            $this->db->group_by('s.id');
    
            $this->db->order_by('s.created_date', 'desc');
    
            if ($limit != null) {
                $this->db->limit($limit);
            }
    
            return $this->db->get()->result();
    
        } else {
    
            $user_id = csession('user_id');
    
            if ($is_customer) {
    
                $customer = $this->utility_model->get_records_by_field(
                    'customer',
                    'user_id',
                    $user_id,
                    true,
                    false
                );
    
                $customer_id = $customer->id;
    
                $this->db->select('
                    s.*,
                    c.customer_name,
                    c.gstin,
                    c.state_name,
                    c.customer_company_name,
                    w.name as warehouse_name,
                    si.product_id,
                    si.quantity,
                    si.selling_price,
                    p.name,
                    p.description,
                    p.product_category_id,
                    p.hsn,
                    p.uom_id
                ')
                ->from('sale s')
                ->join('customer c', 'c.id = s.customer_id', 'left')
                ->join('warehouse w', 'w.id = s.warehouse_id', 'left')
                ->join('sale_items si', 'si.sale_id = s.id', 'left')
                ->join('product p', 'p.id = si.product_id', 'left')
    
                // DELIVERY FILTER ADDED
                ->join('sale_delivery sd', 'sd.sale_id = s.id', 'inner')
    
                ->where('s.delete_status', 0)
                ->group_start()
                    ->where('s.customer_id', $customer_id)
                    ->or_where('s.user_id', $user_id)
                ->group_end();
    
                if (!empty($warehouse_ids)) {
                    $this->db->where_in('s.warehouse_id', $warehouse_ids);
                }
    
                // PREVENT DUPLICATES
                $this->db->group_by('s.id');
    
                $this->db->order_by('s.created_date', 'desc');
    
                if ($limit != null) {
                    $this->db->limit($limit);
                }
    
                return $this->db->get()->result();
    
            } else {
    
                $this->db->select('
                    s.*,
                    c.customer_name,
                    c.gstin,
                    c.state_name,
                    c.customer_company_name,
                    w.name as warehouse_name,
                    si.product_id,
                    si.quantity,
                    si.selling_price,
                    p.name,
                    p.description,
                    p.product_category_id,
                    p.hsn,
                    p.uom_id
                ')
                ->from('sale s')
                ->join('customer c', 'c.id = s.customer_id', 'left')
                ->join('warehouse w', 'w.id = s.warehouse_id', 'left')
                ->join('sale_items si', 'si.sale_id = s.id', 'left')
                ->join('product p', 'p.id = si.product_id', 'left')
    
                // DELIVERY FILTER ADDED
                ->join('sale_delivery sd', 'sd.sale_id = s.id', 'inner')
    
                ->where('s.delete_status', 0)
                ->where('s.user_id', $user_id);
    
                if (!empty($warehouse_ids)) {
                    $this->db->where_in('s.warehouse_id', $warehouse_ids);
                }
    
                // PREVENT DUPLICATES
                $this->db->group_by('s.id');
    
                $this->db->order_by('s.created_date', 'desc');
    
                if ($limit != null) {
                    $this->db->limit($limit);
                }
    
                return $this->db->get()->result();
            }
        }
    }

    public function get_sale_single_record_by_quotation_id($quotation_id)
    {
        return $this->db->select('s.*,c.customer_name')
                        ->from('sale s')
                        ->join('customer c','c.id = s.customer_id','LEFT')
                        ->where('s.delete_status',0)
                        ->where('s.quotation_id',$quotation_id)
                        ->get()
                        ->row();
    }
    
    public function get_sale_records_by_customer_id($customer_id)
    {
        return $this->db->select('
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('sale s')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.customer_id',$customer_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
    }

    public function get_sale_records_by_warehouse_id($warehouse_id)
    {
        return $this->db->select('
                                    s.*,
                                    c.customer_name,c.customer_company_name,
                                ')
                        ->from('sale s')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.warehouse_id',$warehouse_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
    }

    function get_total_sale_amount($from = null, $to = null)
    {
        $data  = $this->db->select('
                                    SUM(total) AS total,
                                    SUM(total_tax) as total_tax, 
                                    SUM(total_discount) as total_discount, 
                                    SUM(total_taxable_value) as total_taxable_value
                                ')
                         ->from('sale s')
                         ->where('s.delete_status',0)
                         ->get();

        if($data->num_rows() > 0)
        {
            $res = $data->row_array();
            return $res;
        }
        else
        {
            $res                            = array();
            $res['total']                   = 0.0;
            $res['total_tax']               = 0.0;
            $res['total_discount']          = 0.0;
            $res['total_taxable_value']     = 0.0;

            return $res;
        }
    }

    public function add_sale_record($data)
    {

      $data['created_date'] = date('Y-m-d H:i:s');
        if($this->db->insert('sale',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_sale_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('sale',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

  

    public function get_sale_single_record($sale_id)
    {
        return $this->db->select('s.*,c.customer_name')
                        ->from('sale s')
                        ->join('customer c','c.id = s.customer_id','LEFT')
                        //->where('s.delete_status',0)
                        ->where('s.id',$sale_id)
                        ->get()
                        ->row();
    }

    public function get_sale_single_record_by_reference_no($reference_no)
    {
        return $this->db->select('s.*,c*')
                        ->from('sale s')
                        ->join('customer c','c.id = s.customer_id','LEFT')
                        // ->where('s.delete_status',0)
                        ->where('s.reference_no',$reference_no)
                        ->get()
                        ->row();
    }

    public function get_sale_item_records($sale_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name,
                                    p.hsn,
                                    p.manage_inventory
                                    
                                ')
                        ->from('sale_items si')
                        ->join('sale s','s.id = si.sale_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->join('product p','p.id = si.product_id','left')
                        // ->join('warehouse_products wp','wp.warehouse_id = s.warehouse_id','left')
                        // ->join('warehouse_products wp1','wp1.product_id = p.id','left')
                        // ->join('warehouse_products wp2','wp2.cost = si.cost','left')
                        ->where('s.id',$sale_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_single_sale_item_record($sale_id, $product_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name,
                                    pr.hsn,
                                    w.name as warehouse_name
                                ')
                        ->from('sale_items si')
                        ->join('sale s','s.id = si.sale_id')
                        ->join('warehouse w','w.id = s.warehouse_id','left')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->join('product pr','pr.id = si.product_id','left')
                        ->where('s.id',$sale_id)
                        ->where('si.product_id',$product_id)
                        ->get()
                        ->row();
    }

    public function get_sale_tax_individual_and_tot_cost_and_total_price($sale_id)
    {
        return $this->db->select('
                                    SUM(igst_tax) AS igst_tax,
                                    SUM(cgst_tax) AS cgst_tax,
                                    SUM(sgst_tax) AS sgst_tax,
                                    SUM(quantity*cost) AS total_cost,
                                    SUM((quantity*price)-discount_amount) AS total_price
                                ')
                        ->from('sale_items si')
                        ->where('si.sale_id',$sale_id)
                        ->group_by('si.sale_id')
                        ->get()
                        ->row();   
    }

    public function get_sale_tax_individual($sale_id)
    {
        return $this->db->select('
                                    SUM(igst_tax) AS igst_tax,
                                    SUM(cgst_tax) AS cgst_tax,
                                    SUM(sgst_tax) AS sgst_tax
                                ')
                        ->from('sale_items pi')
                        ->where('pi.sale_id',$sale_id)
                        ->group_by('pi.sale_id')
                        ->get()
                        ->row();   
    }

    public function get_cost_by_sale_id($sale_id)
    {
        return $this->db->select('
                                    SUM(quantity*cost) AS total_cost
                                ')
                        ->from('sale_items si')
                        ->where('si.sale_id',$sale_id)
                        ->group_by('si.sale_id')
                        ->get()
                        ->row();   
    }

    public function get_sale_item_records_by_discount_id($discount_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('sale_items si')
                        ->join('sale s','s.id = si.sale_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('si.discount_id',$discount_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_sale_item_records_by_product_id($product_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('sale_items si')
                        ->join('sale s','s.id = si.sale_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('si.product_id',$product_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_sale_item_records_by_tax_id($tax_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('sale_items si')
                        ->join('sale s','s.id = si.sale_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('si.tax_id',$tax_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function add_sale_item_record($data)
    {
                     
        if($this->db->insert('sale_items',$data))
        {
                      
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }
    
    
  public function create_sale_from_request($order, $order_items)
{
    
    // $existing_sale = $this->db
    //     ->where('proforma_invoice_id', $order->id)
    //     ->get('sale')
    //     ->row();

    // if ($existing_sale) {
    //     return $existing_sale->id;
    // }
    $existing_sale = $this->db
    ->where('proforma_invoice_id', $order->id)
    ->get('sale')
    ->row();

if ($existing_sale) {

    // 🔧 FIX MISSING REFERENCE NO
    if (empty($existing_sale->reference_no) && !empty($order->reference_no)) {
        $this->db->where('id', $existing_sale->id)
                 ->update('sale', [
                     'reference_no' => $order->reference_no
                 ]);
    }

    return $existing_sale->id;
}

    // 1. Generate invoice/reference number
    // $reference_no = $this->get_lastest_sequence_number($order->warehouse_id);
    $reference_no = !empty($order->reference_no) ? $order->reference_no : $this->get_lastest_sequence_number($order->warehouse_id);

    // 2. Prepare sale data
    $sale_data = [
        "invoice_date"          => date('Y-m-d'),
        "reference_no"          => $reference_no,
        "customer_id"           => $order->customer_id,
        "warehouse_id"          => $order->warehouse_id,
        "proforma_invoice_id"   => $order->id,
        "total_taxable_value"   => $order->total_taxable_value,
        "tds"                   => $order->tds,
        "total_discount"        => $order->total_discount,
        "total_tax"             => $order->total_tax,
        "total"                 => $order->total,
        "internal_note"         => $order->internal_note,
        "external_note"         => $order->external_note,
        "bank_detail"           => $order->bank_detail,
        "customer_shipping_country_id" => $order->customer_shipping_country_id,
        "customer_shipping_state_id"   => $order->customer_shipping_state_id,
        "customer_shipping_city_id"    => $order->customer_shipping_city_id,
        "customer_shipping_address"    => $order->customer_shipping_address,
        "customer_shipping_pincode"    => $order->customer_shipping_pincode,
        "user_id"               => $this->session->userdata('user_id')
    ];

    $this->db->trans_begin();

    // 3. Insert sale record
    $sale_id = $this->add_sale_record($sale_data);
    if (!$sale_id) {
        $this->db->trans_rollback();
        return false;
    }

    // 4. Merge duplicate products by product_id
    $unique_items = [];
    foreach ($order_items as $item) {
        $pid = $item['product_id'];
        if (!isset($unique_items[$pid])) {
            $unique_items[$pid] = $item;
        } else {
            $unique_items[$pid]['quantity'] += $item['quantity'];
            $unique_items[$pid]['taxable_value'] += $item['taxable_value'] ?? 0;
            $unique_items[$pid]['sub_total'] += $item['sub_total'] ?? 0;
            
             $unique_items[$pid]['cgst_tax'] += $item['cgst_tax'] ?? 0;
            $unique_items[$pid]['sgst_tax'] += $item['sgst_tax'] ?? 0;
            $unique_items[$pid]['igst_tax'] += $item['igst_tax'] ?? 0;
        }
    }

    // 5. Insert sale items with proper column mapping
    foreach ($unique_items as $item) {
        $unit_price = $item['price'];       // ✅ source of truth
        $sub_total  = $item['subtotal'];    // ✅ correct key

        $item_data = [
            "sale_id"       => $sale_id,
            "warehouse_product_id" => $item['warehouse_product_id'] ?? null,
            "product_id"    => $item['product_id'],
            "product_name"  => $item['product_name'],
            "hsn"           => $item['hsn'] ?? null,
            "uom_id"        => $item['uom_id'] ?? null,
            "uom_uom"       => $item['uom_uom'] ?? null,
            "uom_name"      => $item['uom_name'] ?? null,
            "description"   => $item['description'] ?? null,
            "quantity"      => $item['quantity'],
            "cost"          => $item['cost'] ?? 0,
            // "price"         => $item['price'] ?? 0,
              "price"         => $unit_price,
            "selling_price" => $unit_price,
            
            "discount_id"   => $item['discount_id'] ?? 0,
            "discount_type" => $item['discount_type'] ?? 0,
            "discount_value"=> $item['discount_value'] ?? 0,
            "discount_amount"=> $item['discount_amount'] ?? 0,
            "taxable_value" => $item['taxable_value'] ?? 0,
            "tax_id"        => $item['tax_id'] ?? null,
            // "igst"          => $item['igst'] ?? 0,
            // "igst_tax"      => $item['igst_tax'] ?? 0,
            // "cgst"          => $item['cgst'] ?? 0,
            // "cgst_tax"      => $item['cgst_tax'] ?? 0,
            // "sgst"          => $item['sgst'] ?? 0,
            // "sgst_tax"      => $item['sgst_tax'] ?? 0,
            
           // ✅ TAX RATE (%)
// OSS stores rate in *_tax columns
"igst" => $item['igst_tax'] ?? 0,
"cgst" => $item['cgst_tax'] ?? 0,
"sgst" => $item['sgst_tax'] ?? 0,

// ✅ TAX AMOUNT (₹)
"igst_tax" => isset($item['igst_tax'])
    ? round(($item['taxable_value'] * $item['igst_tax']) / 100, 2)
    : 0,

"cgst_tax" => isset($item['cgst_tax'])
    ? round(($item['taxable_value'] * $item['cgst_tax']) / 100, 2)
    : 0,

"sgst_tax" => isset($item['sgst_tax'])
    ? round(($item['taxable_value'] * $item['sgst_tax']) / 100, 2)
    : 0,


 


            "igst_rate"     => $item['igst_rate'] ?? 0,
            "cgst_rate"     => $item['cgst_rate'] ?? 0,
            "sgst_rate"     => $item['sgst_rate'] ?? 0,
            "tax_type"      => $item['tax_type'] ?? 0,
            
            // "sub_total"     => $item['sub_total'] ?? 0,   // ✅ correct column
            
               "sub_total"     => $sub_total,

            "created_date"  => date('Y-m-d H:i:s'),
            // "selling_price" => $item['selling_price'] ?? 0,
            "selling_price" => $unit_price,          // ✅ COPY FROM OSS
            "mfg_date"      => isset($item['mfg_date']) ? date('Y-m-d', strtotime($item['mfg_date'])) : null,
            "expiry_date"   => isset($item['expiry_date']) ? date('Y-m-d', strtotime($item['expiry_date'])) : null,
            "proforma_invoice_idd" => $item['proforma_invoice_idd'] ?? null,
            "free_quantity" => $item['free_quantity'] ?? 0,
            "batch_no"      => $item['batch_no'] ?? null,
            "product_remark"=> $item['product_remark'] ?? null,
            "supplier_id"   => $item['supplier_id'] ?? null,
            // "purchase_cost" => $item['purchase_cost'] ?? 0,
            "purchase_cost" => $unit_price,          // ✅ COPY FROM OSS
            "item_index"    => $item['item_index'] ?? 0
        ];

        $this->add_sale_item_record($item_data);
    }

    $this->db->trans_commit();
    return $sale_id;
}


    public function edit_sale_item_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('sale_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function remove_sale_item_records($sales_id)
    {   
        $this->db->where('sale_id',$sales_id);
        if($this->db->delete('sale_items'))
            return true;
        else
            return false;
    }

   public function sale_by_month_year($year_month, $branch_id = null) {
       
        $user_id = $this->session->userdata('user_id');
        $udata   = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
       
        $this->db->select('SUM(total) AS amount')
                 ->from('sale s')
                 ->where('s.delete_status', 0)
                 ->like('s.invoice_date', $year_month, 'both')
                 ->group_by('DATE_FORMAT(s.invoice_date, "%Y-%m")');
    
            if ($branch_id && $branch_id !== 'all') {
            $this->db->where('s.warehouse_id', $branch_id);
            }
             if (!empty($udata->branch_id)) {
                $this->db->where('s.warehouse_id', $udata->branch_id);
            }
            return $this->db->get()->result_array();
    }
    /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
{
    $user_id = $this->session->userdata('user_id');
    $user = $this->ion_auth->user($user_id)->row()->email; 
    $customer = $this->utility_model->get_records_by_field('customer','email',$user,$row = true,$check_delete_status = false);

    // Modified select to include customer_company_name
    $this->db->select('
        s.*, 
        c.customer_name,
        c.gstin,
        c.state_name,
        c.customer_company_name
    ');
    $this->db->from($this->table.' s');

    // Keep old customer check logic
    if($this->is_customer == true)
    {
        $this->db->group_start();
        $this->db->where('s.customer_id',$customer->id);
        $this->db->or_where('s.user_id',$user_id);
        $this->db->group_end();
    }

    // Keep old sales_payment filters
    if($_POST['sales_payment'] != '')
    {
        if($_POST['sales_payment'] == INVOICE_PAID)
        {
            $this->db->where('s.total = ((SELECT SUM(amount) FROM transaction_header WHERE entry_id = s.id AND module = "'.SALE_MODULE.'" AND type IN ("'.RECEIPT_TRANSACTION_TYPE.'","'.CREDIT_TRANSACTION_TYPE.'")) + s.tds)');
        }
        else if($_POST['sales_payment'] == INVOICE_PARTIALLY_PAID)
        {
            $this->db->group_start();
            $this->db->where('(s.total - (SELECT SUM(amount) FROM transaction_header WHERE entry_id = s.id AND module = "'.SALE_MODULE.'" AND type IN ("'.RECEIPT_TRANSACTION_TYPE.'","'.CREDIT_TRANSACTION_TYPE.'"))) > 0', NULL, FALSE);
            $this->db->where('((SELECT SUM(amount) FROM transaction_header WHERE entry_id = s.id AND module = "'.SALE_MODULE.'" AND type IN ("'.RECEIPT_TRANSACTION_TYPE.'","'.CREDIT_TRANSACTION_TYPE.'")) + s.tds) > 0',NULL,FALSE);
            $this->db->group_end();
        }
        else if($_POST['sales_payment'] == INVOICE_UNPAID)
        {
            $this->db->where('NOT EXISTS (SELECT * FROM transaction_header WHERE entry_id = s.id AND module = "S" AND type IN ("R","CR"))'); 
        }
    }

    // Keep old search logic
    $i = 0;
    foreach ($this->column_search as $item)
    {
        if($_POST['search']['value'])
        {
            if($i===0)
            {
                $this->db->group_start();
                $this->db->like($item, $_POST['search']['value']);
            }
            else
            {
                $this->db->or_like($item, $_POST['search']['value']);
            }

            if(count($this->column_search) - 1 == $i)
                $this->db->group_end();
        }
        $i++;
    }

    // Keep old ordering logic
    if(isset($_POST['order']))
    {
        $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
        $order = $this->order;
        $this->db->order_by(key($order), $order[key($order)]);
    }

    // Added join for customer table to fetch customer info
    $this->db->join('customer c', 'c.id = s.customer_id', 'left');
}

    function get_datatables()
    {
        $user_id    = $this->session->userdata('user_id');
        $udata      = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        // $this->db->where('delete_status',0); 
        if($udata->branch_id){
            $this->db->where('warehouse_id', $udata->branch_id); 
        }
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    /*********************************** End Dynamic Datatable function *****************************************/
    public function get_sale_record_details_by_reference_no($reference_no)
    {
        $this->db->select('s.*');
        $this->db->from('sale s');
        $this->db->where('s.reference_no',$reference_no);

        $query = $this->db->get();

        return $query->row();
        
    }

    public function get_sale_item_records_for_ewaybill($sale_id)
    {
        return $this->db->select('
                                    p.id as itemNo,
                                    si.product_name as productName,
                                    p.description as productDesc,
                                    p.hsn as hsnCode,
                                    si.quantity as quantity,
                                    si.uom_uom as qtyUnit,
                                    si.taxable_value as taxableAmount,
                                    si.igst as igstRate,
                                    si.cgst as cgstRate,
                                    si.sgst as sgstRate,
                                    "0" as cessRate,
                                    "0" as cessNonAdvol
                                ')
                        ->from('sale_items si')
                        ->join('sale s','s.id = si.sale_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->join('product p','p.id = si.product_id','left')
                        // ->join('supplier sp','sp.id = p.supplier_id','left')
                        // ->join('warehouse_products wp','wp.warehouse_id = s.warehouse_id','left')
                        // ->join('warehouse_products wp1','wp1.product_id = p.id','left')
                        // ->join('warehouse_products wp2','wp2.cost = si.cost','left')
                        ->where('s.id',$sale_id)
                        ->where('s.delete_status',0)
                        ->order_by('si.product_name','asc')
                        ->get()
                        ->result();
    }

    public function get_tax_calculation_by_sale_id($sale_id)
    {
      $this->db->select("
                          CASE
                              WHEN si.igst > 0 THEN si.igst
                              ELSE (si.cgst+si.sgst)
                          END AS tax_rate,
                          SUM(si.taxable_value) AS sum_taxable_value,
                          SUM(si.igst_tax) AS sum_igst,
                          SUM(si.cgst_tax) AS sum_cgst,
                          SUM(si.sgst_tax) AS sum_sgst,
                          (SUM(si.cgst_tax) + SUM(si.sgst_tax) + SUM(si.igst_tax)) AS subtotal
                      ");
      $this->db->from('sale_items si');
      $this->db->where('si.sale_id',$sale_id);
      $this->db->group_by('si.sale_id');

      $query = $this->db->get();

      return $query->row();
    }

    public function get_mainhsncode_by_sale_id($sale_id)
    {
      $sale_items = $this->sale_model->get_sale_item_records_for_ewaybill($sale_id);
  
      $hsnCodeMap = array();
      
      // Calculate the combined taxable value for each HSN code
      foreach ($sale_items as $product) {
          $hsnCode = $product->hsnCode;
          $taxableValue = $product->taxableAmount;
          
          if (!isset($hsnCodeMap[$hsnCode])) {
              $hsnCodeMap[$hsnCode] = $taxableValue;
          } else {
              $hsnCodeMap[$hsnCode] += $taxableValue;
          }
      }
      
      // Find the HSN code with the highest combined taxable value
      $highestHSNCode = "";
      $highestCombinedValue = 0;
      foreach ($hsnCodeMap as $hsnCode => $combinedValue) {
          if ($combinedValue > $highestCombinedValue) {
              $highestCombinedValue = $combinedValue;
              $highestHSNCode = $hsnCode;
          }
      }
      
      return (string)$highestHSNCode;

    }

		// public function get_hold_quantity_by_product_id($warehouse_product_id)
    // {
    //   $this->db->select('
    //                       SUM(si.total_quantity) as hold_quantity
    //                   ');
    //   $this->db->from('sale_items si');
    //   // $this->db->join('pack_slip s','s.id = si.pack_slip_id');
    //   $this->db->where('si.warehouse_product_id',$warehouse_product_id);
    //   $this->db->where('s.delete_status',0);

    //   $this->db->group_start();
    //     $this->db->where('s.sale_id IS NULL');
    //     $this->db->or_where('s.sale_id','');  
    //   $this->db->group_end();
      
    //   $this->db->group_by('si.warehouse_product_id');

    //   $query = $this->db->get();
    //   return $query->row();
    // }

		public function get_hold_quantity_by_product_id($warehouse_product_id)
		{
        $sold_quantity = 0;
        $delivered_quantity = 0;

				$this->db->select('SUM(si.quantity) as sold_quantity');
				$this->db->from('sale_items si');
        $this->db->join('sale s','s.id = si.sale_id');
				$this->db->where('si.warehouse_product_id', $warehouse_product_id);				
				$this->db->where('s.delete_status', 0);				
				$this->db->group_by('si.warehouse_product_id');

				$query = $this->db->get();

        if($query->row() != NULL)
				  $sold_quantity =  $query->row()->sold_quantity;

				$this->db->select('SUM(si.quantity) as delivered_quantity');
				$this->db->from('sale_delivery_items si');
				$this->db->where('si.warehouse_product_id', $warehouse_product_id);				
				$this->db->group_by('si.warehouse_product_id');

				$query = $this->db->get();

        if($query->row() != NULL)
				  $delivered_quantity =  $query->row()->delivered_quantity;

				$hold_quantity = $sold_quantity - $delivered_quantity;

				return ($hold_quantity);

		}

    public function get_sale_records_by_current_month($sale_id_array = null)
    {
      $this->db->select('s.*, c.customer_name, c.customer_company_name');
      $this->db->from('sale s');
      $this->db->join('customer c', 'c.id = s.customer_id', 'left');
      $this->db->where('s.delete_status', 0);
      $this->db->where("DATE_FORMAT(s.invoice_date, '%Y-%m') =", date('Y-m'));

      if($sale_id_array != null)
        $this->db->where_in('s.id',$sale_id_array);

      $query = $this->db->get();
      return $query->result();

    }

    public function get_lastest_sequence_number($warehouse_id)
{
    // 1. Fetch the warehouse code
    $this->db->select('code');
    $this->db->from('warehouse');
    $this->db->where('id', $warehouse_id);
    $warehouse_query = $this->db->get();
    
    if ($warehouse_query->num_rows() == 0) {
        return false;  
    }
    
    $warehouse = $warehouse_query->row();
    $warehouse_code = $warehouse->code;  

    
    $current_year = date('Y');
    $next_year = $current_year + 1;
    $year_range = substr($current_year, 2, 2) . '-' . substr($next_year, 2, 2);

   
    $this->db->like('reference_no', "OSS/{$warehouse_code}/{$year_range}/");
    $this->db->from('sale');
    $query = $this->db->get();
    $invoice_count = $query->num_rows();  
    $invoice_number = $invoice_count + 1; 
    $reference_no = "OSS/{$warehouse_code}/{$year_range}/{$invoice_number}";

    return $reference_no;
}


    // public function get_lastest_sequence_number()
    // {
    //   $this->db->select('*');
    //   $this->db->from('sale');
    //   // $this->db->where('delete_status',0);

    //   $query = $this->db->get();
    //   $data = $query->result();

    //   $dateformat = csession('sale_date_format');
      
    //   $date = '';

    //     // Validate the date format and format the date
    //     $testDate = date_create_from_format($dateformat, '2024-01-01'); // Use any valid date for testing
    //     if ($testDate === false) {
    //         $date = ''; // If the date format is invalid, do not include a date
    //     } else {
    //         $date = date($dateformat);
    //     }

    //   if($dateformat == DATE_FORMAT_YM)
    //     $date = date('Y-m');

    //   else if($dateformat == DATE_FORMAT_YM)
    //     $date = date('Y-m');

    //   else if($dateformat == DATE_FORMAT_YMD)
    //     $date = date('Y-m-d');

    //   else if($dateformat == DATE_FORMAT_DMY)
    //     $date = date('d-m-Y');

    //   return (csession('sale_prefix').csession('seperator').$date.csession('seperator').(csession('sale_sequence')+sizeof($data)+1));
    // }

    public function get_sale_single_record_by_proforma_invoice_id($proforma_invoice_id)
    {
        return $this->db->select('s.*,c.customer_name')
                        ->from('sale s')
                        ->join('customer c','c.id = s.customer_id','LEFT')
                        ->where('s.delete_status',0)
                        ->where('s.proforma_invoice_id',$proforma_invoice_id)
                        ->get()
                        ->row();
    }
    
    public function get_sale_single_record_by_delivery_challan_id($delivery_challan_id)
    {
        return $this->db->select('s.*,c.customer_name')
                        ->from('sale s')
                        ->join('customer c','c.id = s.customer_id','LEFT')
                        ->where('s.delete_status',0)
                        ->where('s.delivery_challan_id',$delivery_challan_id)
                        ->get()
                        ->row();
    }    

    public function get_sale_items_records_for_cdn($sale_ids_arr)
    {
        $this->db->select('
                            si.*,
                            s.reference_no,
                            c.customer_name
                          ');
        $this->db->from('sale_items si');
        $this->db->join('sale s','s.id = si.sale_id','left');
        $this->db->join('customer c','c.id = s.customer_id','left');
        $this->db->where('s.delete_status',0);
        $this->db->where_in('si.sale_id',$sale_ids_arr);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_trade_receivables() 
    {
      // Assuming there's a column `amount` in `purchases` table representing the payable amount
      $this->db->select_sum('total');
      $this->db->where('delete_status',0);
      $query = $this->db->get('sale');
      return $query->row()->total;  // Assuming `amount` is the column storing payable amounts
    }
    

    public function get_sales_by_ids($sale_ids)
    {
        return $this->db->select('s.*, c.id AS customer_id, c.customer_name')
                        ->from('sale s')
                        ->join('customer c', 'c.id = s.customer_id', 'LEFT')
                        ->where_in('s.id', $sale_ids)
                        ->get()
                        ->result();
    }
    


    
}
?>
