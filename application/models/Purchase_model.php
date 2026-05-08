<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_model extends CI_Model {

    var $table = 'purchase_view';

    var $column_order = array(
                                'id',   
                                'reference_no',
                                'invoice_no',  
                                'purchase_date',   
                                'warehouse_id',    
                                'supplier_id', 
                                'supplier_gstin',  
                                'total_taxable_value', 
                                'total_discount',  
                                'total_tax',  
                                'total',   
                                'internal_note',  
                                'external_note',   
                                'terms_and_condition', 
                                'delete_status',   
                                'user_id', 
                                'created_date',    
                                'name',    
                                'company_name'
                            ); 
    var $column_search = array(
                                'id',   
                                'reference_no',
                                'invoice_no',  
                                'purchase_date',   
                                'warehouse_id',    
                                'supplier_id', 
                                'supplier_gstin',  
                                'total_taxable_value', 
                                'total_discount',  
                                'total_tax',  
                                'total',   
                                'internal_note',  
                                'external_note',   
                                'terms_and_condition', 
                                'delete_status',   
                                'user_id', 
                                'created_date',    
                                'name',    
                                'company_name'
                            ); 
    var $order = array('id' => 'desc'); 

    function __construct() {
        parent::__construct();
    }

// public function get_purchase_records($limit = null)
// {
//     $user_id = $this->session->userdata('user_id');
//     $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();

//     $this->db->select('
//                         p.*,
//                         w.name as warehouse_name,
//                         s.company_name, s.gstin as supp_gstin,
//                         pi.*,
//                         pr.*
//                       ')
//              ->from('purchase p')
//              ->join('warehouse w', 'w.id = p.warehouse_id', 'left')
//              ->join('supplier s', 's.id = p.supplier_id', 'left')
//              ->join('purchase_items pi', 'pi.purchase_id = p.id', 'left') 
//              ->join('product pr', 'pr.id = pi.product_id', 'left')    
//              ->where('p.delete_status', 0);

//     if (isset($udata->branch_id) && $udata->branch_id) {
//         $this->db->where('p.warehouse_id', $udata->branch_id);
//     }

//     if ($limit != null) {
//         $this->db->limit($limit);
//     }

//     $this->db->order_by('p.created_date', 'desc');
//     $query = $this->db->get();
//     return $query->result();
// }

/*public function get_purchase_records($limit = null)
{
    $user_id = $this->session->userdata('user_id');
    $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();

    // ⬇️ SIMPLE FIXED VERSION ⬇️
    $this->db->select('
        p.id,
        p.reference_no,
         p.invoice_no,  
        p.supplier_id,
        p.warehouse_id,
        p.delete_status,
        w.name as warehouse_name,
        s.company_name
    ')
    ->from('purchase p')
    ->join('warehouse w', 'w.id = p.warehouse_id', 'left')
    ->join('supplier s', 's.id = p.supplier_id', 'left')
    // ⚠️ REMOVE THESE JOINS FOR NOW:
    // ->join('purchase_items pi', 'pi.purchase_id = p.id', 'left')
    // ->join('product pr', 'pr.id = pi.product_id', 'left')
    ->where('p.delete_status', 0);

    if (isset($udata->branch_id) && $udata->branch_id) {
        $this->db->where('p.warehouse_id', $udata->branch_id);
    }

    if ($limit != null) {
        $this->db->limit($limit);
    }

    $this->db->order_by('p.created_date', 'desc');
    $query = $this->db->get();
    
    
    return $query->result();
}*/

public function get_purchase_records($limit = null)
{
    $user_id = $this->session->userdata('user_id');
    $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();

    $this->db->select('
        p.id,
        p.reference_no,
        p.invoice_no,
        p.supplier_id,
        p.warehouse_id,
        p.delete_status,
        p.purchase_date,
        p.total,
        p.total_tax,
        p.total_discount,
        p.total_taxable_value,
        w.name as warehouse_name,
        s.company_name
    ')
    ->from('purchase p')
    ->join('warehouse w', 'w.id = p.warehouse_id', 'left')
    ->join('supplier s', 's.id = p.supplier_id', 'left')
    ->where('p.delete_status', 0)
    ->where('p.invoice_no IS NOT NULL')  // Only get purchases with invoice numbers
    ->where('p.invoice_no !=', '');       // Exclude empty invoice numbers

    if (isset($udata->branch_id) && $udata->branch_id) {
        $this->db->where('p.warehouse_id', $udata->branch_id);
    }

    if ($limit != null) {
        $this->db->limit($limit);
    }

    $this->db->order_by('p.created_date', 'desc');
    $query = $this->db->get();
    
    return $query->result();
}

    public function get_purchase_records_by_supplier_id($supplier_id)
    {
        return $this->db->select('
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase p')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->where('s.id',$supplier_id)
                        ->where('p.delete_status',0)
                        ->order_by('p.created_date','desc')
                        ->get()
                        ->result();
    }

    public function get_purchase_records_by_warehouse_id($warehouse_id)
    {
        return $this->db->select('
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase p')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->where('p.warehouse_id',$warehouse_id)
                        ->where('p.delete_status',0)
                        ->order_by('p.created_date','desc')
                        ->get()
                        ->result();
    }

    function get_total_purchase_amount($from = null, $to = null)
    {
        $data  = $this->db->select('
                                    SUM(total) AS total,
                                    SUM(total_tax) as total_tax, 
                                    SUM(total_discount) as total_discount, 
                                    SUM(total_taxable_value) as total_taxable_value
                                ')
                         ->from('purchase p')
                         ->where('p.delete_status',0)
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

    public function add_purchase_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');

      if($this->db->insert('purchase',$data))
      {
          return  $this->db->insert_id();
      }
      else
      {
          return FALSE;
      }
    }

    public function edit_purchase_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('purchase',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_records_by_purchase_id($customer_id)
    {
        return $this->db->select('
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase p')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->where('w.id',$warehouse_id)
                        ->where('s.id',$supplier_id)
                        ->order_by('p.created_date','desc')
                        ->get()
                        ->result();
    }

    public function get_purchase_single_record($purchase_id)
    {
        return $this->db->select('p.*,s.company_name as supplier_name')
                        ->from('purchase p')
                        ->join('supplier s','s.id = p.supplier_id')
                        // ->where('p.delete_status',0)
                        ->where('p.id',$purchase_id)
                        ->get()
                        ->row();
    }

    public function get_purchase_item_records($purchase_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    s.company_name,
                                    pr.hsn,
                                    w.name as warehouse_name
                                ')
                        ->from('purchase_items pi')
                        ->join('purchase p','p.id = pi.purchase_id')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->join('product pr','pr.id = pi.product_id','left')
                        ->where('p.id',$purchase_id)
                        ->get()
                        ->result();
    }

    public function get_purchase_item_records_by_tax_id($tax_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    s.company_name,
                                    pr.hsn,
                                    w.name as warehouse_name
                                ')
                        ->from('purchase_items pi')
                        ->join('purchase p','p.id = pi.purchase_id')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->join('product pr','pr.id = pi.product_id','left')
                        ->where('pi.tax_id',$tax_id)
                        ->where('p.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_purchase_item_records_by_product_id($product_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    s.company_name,
                                    pr.hsn,
                                    pr.description,
                                    w.name as warehouse_name,
                                    pr.name as product_name
                                ')
                        ->from('purchase_items pi')
                        ->join('purchase p','p.id = pi.purchase_id')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->join('product pr','pr.id = pi.product_id','left')
                        ->where('pi.product_id',$product_id)
                        ->where('p.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_single_purchase_item_record($purchase_id, $product_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    s.company_name,
                                    pr.hsn,
                                    pr.description,
                                    w.name as warehouse_name,
                                    pr.name as product_name
                                ')
                        ->from('purchase_items pi')
                        ->join('purchase p','p.id = pi.purchase_id')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->join('product pr','pr.id = pi.product_id','left')
                        ->where('p.id',$purchase_id)
                        ->where('pi.product_id',$product_id)
                        ->get()
                        ->row();
    }

    public function get_purchase_item_records_by_discount_id($discount_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase_items pi')
                        ->join('purchase p','p.id = pi.purchase_id')
                        ->join('warehouse w','w.id = pi.warehouse_id','left')
                        ->join('supplier s','s.id = pi.supplier_id','left')
                        ->where('pi.discount_id',$discount_id)
                        ->where('pi.delete_status',0)
                        ->get()
                        ->result();
    }

    public function add_purchase_item_record($data)
    {
        if($this->db->insert('purchase_items',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_purchase_item_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('purchase_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function remove_purchase_item_records($purchases_id)
    {   
        $this->db->where('purchase_id',$purchases_id);
        if($this->db->delete('purchase_items'))
            return true;
        else
            return false;
    }

public function purchase_by_month_year($year_month, $branch_id = null) {
    $user_id = $this->session->userdata('user_id');
    $udata   = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();

    $this->db->select('SUM(total) AS amount')
             ->from('purchase p')
             ->where('p.delete_status', 0)
             ->like('p.purchase_date', $year_month, 'both')
             ->group_by('DATE_FORMAT(p.purchase_date, "%Y-%m")');

    if ($branch_id && $branch_id !== 'all') {
        $this->db->where('p.warehouse_id', $branch_id);
    }

    if (!empty($udata->branch_id)) {
        $this->db->where('p.warehouse_id', $udata->branch_id);
    }

    $results = $this->db->get()->result_array();

   
    foreach ($results as &$result) {
        $result['amount'] = number_format($result['amount'], 2, '.', '');
    }

    return $results;
}


    public function get_purchase_tax_individual($purchase_id)
    {
        return $this->db->select('
                                    SUM(igst_tax) AS igst_tax,
                                    SUM(cgst_tax) AS cgst_tax,
                                    SUM(sgst_tax) AS sgst_tax
                                ')
                        ->from('purchase_items pi')
                        ->where('pi.purchase_id',$purchase_id)
                        ->group_by('pi.purchase_id')
                        ->get()
                        ->row();   
    }

     /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
      $this->db->select('p.*');
      $this->db->from($this->table.' p');

      if($_POST['purchase_payment'] != '')
      {
        if($_POST['purchase_payment'] == INVOICE_PAID)
        {
           $this->db->where('p.total = ((SELECT SUM(amount) FROM transaction_header WHERE entry_id = p.id AND module = "'.PURCHASE_MODULE.'" AND type IN ("'.PAYMENT_TRANSACTION_TYPE.'","'.CREDIT_TRANSACTION_TYPE.'")))');
        }
        else if($_POST['purchase_payment'] == INVOICE_PARTIALLY_PAID)
        {
          $this->db->group_start();
            $this->db->where('(p.total - (SELECT SUM(amount) FROM transaction_header WHERE entry_id = p.id AND module = "'.PURCHASE_MODULE.'" AND type IN ("'.PAYMENT_TRANSACTION_TYPE.'","'.CREDIT_TRANSACTION_TYPE.'"))) > 0', NULL, FALSE);
            $this->db->where('((SELECT SUM(amount) FROM transaction_header WHERE entry_id = p.id AND module = "'.PURCHASE_MODULE.'" AND type IN ("'.PAYMENT_TRANSACTION_TYPE.'","'.CREDIT_TRANSACTION_TYPE.'"))) > 0',NULL,FALSE);
          $this->db->group_end();
        }
        else if($_POST['purchase_payment'] == INVOICE_UNPAID)
        {
          $this->db->where('NOT EXISTS (SELECT * FROM transaction_header WHERE entry_id = p.id AND module = "P" AND type IN ("P","CR"))'); 
        }
      }
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables()
    {
       $user_id    = $this->session->userdata('user_id');
        $udata      = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
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

    public function get_purchase_record_details_by_invoice_no($invoice_no)
    {
        $this->db->select('s.*');
        $this->db->from('purchase s');
        $this->db->where('s.invoice_no',$invoice_no);

        $query = $this->db->get();

        return $query->row();
        
    }

    /*public function get_lastest_sequence_number()
    {
      $this->db->select('*');
      $this->db->from('purchase');
      // $this->db->where('delete_status',0);

      $query = $this->db->get();
      $data = $query->result();

       // Constructing the sequence number
       $sequenceNumber = count($data) + 1;

       return $sequenceNumber;
    }*/
public function get_lastest_sequence_number()
{
    $this->db->select('*');
    $this->db->from('purchase');
    
    $query = $this->db->get();
    $data = $query->result();
    
    // Constructing the sequence number
    $sequenceNumber = count($data) + 1;
    
    return $sequenceNumber;
}
    public function get_purchase_items_records_for_cdn($purchase_ids_arr)
    {
        $this->db->select('
                            si.*,
                            s.reference_no
                          ');
        $this->db->from('purchase_items si');
        $this->db->join('purchase s','s.id = si.purchase_id','left');
        $this->db->where('s.delete_status',0);
        $this->db->where_in('si.purchase_id',$purchase_ids_arr);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_trade_payables() 
    {
      // Assuming there's a column `amount` in `purchases` table representing the payable amount
      $this->db->select_sum('total');
      $this->db->where('delete_status',0);
      $query = $this->db->get('purchase');
      return $query->row()->total;  // Assuming `amount` is the column storing payable amounts
    }
    
    
public function search_record($search_term = null, $module = null, $warehouse_id = null)
{
    $user_id = $this->session->userdata("user_id"); // Replace with logged-in or selected user ID

    // Step 1: Get pricing data for the user
    $this->db->select('pricing');
    $this->db->from('users');
    $this->db->where('id', $user_id);
    $pricing_json = $this->db->get()->row('pricing');

    // Decode pricing to get product IDs
    $pricing_array = json_decode($pricing_json, true);

    if (empty($pricing_array)) {
        echo "No pricing data found.\n";
        return [];
    }

    $product_ids = array_keys($pricing_array); // Extract only the product IDs

    if (empty($product_ids)) {
        echo "No product IDs extracted from pricing.\n";
        return [];
    }

    // Step 2: Build the product search query
    $this->db->select('p.id, p.name, pc.name as product_category_name');
    $this->db->from('product p');
    $this->db->join('product_category pc', 'pc.id = p.product_category_id', 'left');
    $this->db->where_in('p.id', $product_ids);
    $this->db->where('p.manage_inventory', MANAGE_INVENTORY_YES);
    $this->db->where('p.delete_status', 0);
    $this->db->where('p.status', PRODUCT_STATUS_ACTIVE);

    if (!empty($search_term)) {
        $this->db->group_start();
        $this->db->like('p.name', $search_term);
        $this->db->or_like('pc.name', $search_term);
        $this->db->group_end();
    }

    // Fetch the products based on the query
    $products = $this->db->get()->result();
    return $products;
}

public function get_single_record($product_id)
{
    // Step 1: Get pricing data for the user (You can replace this with the logged-in user ID)
    $user_id = $this->session->userdata("user_id");  // Example user ID; replace with logged-in user ID

    // Fetch the pricing JSON from the users table
    $this->db->select('pricing');
    $this->db->from('users');
    $this->db->where('id', $user_id);
    $pricing_json = $this->db->get()->row('pricing');


    // Decode the pricing JSON into an associative array
    $pricing_array = json_decode($pricing_json, true);



    // Check if the pricing data is empty or if the product price is not available
    if (empty($pricing_array) || !isset($pricing_array[$product_id])) {
        echo "No pricing data found for this product.\n";
        return [];
    }
    
    // Fetch the price for the given product ID
    $product_price = $pricing_array[$product_id]['price'];



    // Step 2: Fetch product details, including pricing, tax, and UOM information
    $query = $this->db->select('p.*, t.cgst as cgst, t.sgst as sgst, t.igst as igst, t.id as tax_id, 
                                pc.tax_type as tax_type, pc.name as product_category_name, u.name as uom_name, 
                                u.uom as uom_uom, u.id as uom_id, pc.name as product_category_name, 
                                ' . $this->db->escape($product_price) . ' as price')
                      ->from('product p')
                      ->join('product_category pc', 'pc.id = p.product_category_id', 'LEFT')
                      ->join('uom u', 'u.id = p.uom_id', 'LEFT')
                      ->join('tax t', 't.id = pc.tax_id', 'LEFT')
                      ->where('p.id', $product_id);

    // Debug: Check the final query before execution

    // Execute the query and retrieve the product data
    $product_data = $query->get()->row();



    return $product_data;
}




}
?>
