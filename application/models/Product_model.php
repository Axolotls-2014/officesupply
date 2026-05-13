<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    var $table = 'product_view';
    var $column_order = array(
                              'name',
                              'description',
                              'product_category_name',
                              'hsn',
                              'batch_no',
                              'markup',
                              'uom_name',                              
                              'alert_quantity',
                              'warehouse_name',
                              'product_cost',
                              'product_price',
                              'warehouse_id',
                              'warehouse_name',
                              'quantity',
                              'status',
                              'manage_inventory'
                            ); //set column field database for datatable orderable
    var $column_search = array(
                              'name',
                              'description',
                              'product_category_name',
                              'hsn',
                              'batch_no',
                              'markup',
                              'uom_name',                             
                              'alert_quantity',
                              'warehouse_name',
                              'product_cost',
                              'product_price',
                              'warehouse_id',
                              'warehouse_name',
                              'quantity',
                              'status',
                              'manage_inventory'
                            ); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 


    function __construct() {
        parent::__construct();
    }

    public function get_records($limit = null)
    {
      if($limit == null)
      {
          return $this->db->select('p.*,pc.name as product_category_name,u.name as uom_name,u.uom as uom,t.tax_name,
                                  t.igst,
                                  t.cgst,
                                  t.sgst')
                           ->from('product p')
                           ->join('product_category pc','pc.id = p.product_category_id','LEFT')
                           ->join('tax t','t.id = pc.tax_id','LEFT')
                           ->join('uom u','u.id = p.uom_id','LEFT')
                           ->where('p.delete_status',0)
                           ->get()
                           ->result();
      }
      else
      {
          return $this->db->select('p.*,pc.name as product_category_name,u.name as uom_name,u.uom as uom,t.tax_name,
                                  t.igst,
                                  t.cgst,
                                  t.sgst')
                           ->from('product p')
                           ->join('product_category pc','pc.id = p.product_category_id','LEFT')
                           ->join('tax t','t.id = pc.tax_id','LEFT')
                           ->join('uom u','u.id = p.uom_id','LEFT')
                           ->where('p.delete_status',0)
                           ->limit($limit)
                           ->get()
                           ->result();
      }
    }

    public function get_records_by_last_import($limit = null, $data = null)
    {
      $import_products = $this->db->query('SELECT * FROM `product` `p` ORDER BY id DESC LIMIT '.$limit)->result();

      $product_id_array = array();

      foreach ($import_products as $value) {
        $product_id_array[] = $value->id;

        // Add products to warehouse with zero quantity
        $warehouses = $this->warehouse_model->get_records();
        foreach ($warehouses as $warehouse) {
            $warehouse_products = array(
                                        "warehouse_id"  => $warehouse->id,
                                        "product_id"    => $value->id
                                      );
            $this->warehouse_products_model->add_record($warehouse_products);
        }
      }

      // update records 
      $this->db->where_in('id',$product_id_array);
      if($this->db->update('product',$data))
      { 
        return true;
      }
      else
      {
        return false;
      }
    }

    public function get_records_by_status($product_status)
    {
      return $this->db->select('p.*,pc.name as product_category_name,u.name as uom_name,u.uom as uom,t.tax_name,
                                  t.igst,
                                  t.cgst,
                                  t.sgst')
                           ->from('product p')
                           ->join('product_category pc','pc.id = p.product_category_id','LEFT')
                           ->join('tax t','t.id = pc.tax_id','LEFT')
                           ->join('uom u','u.id = p.uom_id','LEFT')
                           ->where('p.delete_status',0)
                           ->where('p.status',$product_status)
                           ->get()
                           ->result();
    }

    public function get_records_by_product_category($product_category_id)
    {     
      return $this->db->select('p.*,pc.name as product_category_name,u.name as uom_name,u.uom as uom,t.tax_name,
                                    t.igst,
                                    t.cgst,
                                    t.sgst')
                         ->from('product p')
                         ->join('product_category pc','pc.id = p.product_category_id','LEFT')
                         ->join('tax t','t.id = pc.tax_id','LEFT')
                         ->join('uom u','u.id = p.uom_id','LEFT')
                         ->where('pc.id',$product_category_id)
                         ->where('p.delete_status',0)
                         ->get()
                         ->result();        
    }

    public function add_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');

        if($this->db->insert('product',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

     public function edit_record($data,$id)
    {   
      $data['updated_date'] = date('Y-m-d H:i:s');
        $this->db->where('id',$id);
        if($this->db->update('product',$data)){
            return true;
        }
        else{
            return false;
        }
    }

    public function delete_record($data,$id)
    {
        $this->db->where('id',$id);

        if($this->db->delete('product'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function search_record($search_term = null, $module = null, $warehouse_id = null)
    {
      if($module == SALE_MODULE || $module == SALE_RETURN_MODULE || $module == PROFORMA_INVOICE_MODULE || $module == SCRAP_MODULE || $module == DELIVERY_CHALLAN_MODULE)
      {
          
        $this->db->select('wp.id,p.name,wp.price,wp.id as warehouse_products_id,pc.name as product_category_name,pc.igst as tax_rate,wp.batch_no,wp.selling_price');
        $this->db->from('product p');
        $this->db->join('product_category pc','pc.id = p.product_category_id','left');
        $this->db->join('warehouse_products wp','wp.product_id = p.id');

        // if($module == SALE_MODULE)
        // {
        //   $this->db->where('wp.price > ','0'); 
        // }
        $this->db->group_start();

        $this->db->where('wp.warehouse_id',$warehouse_id);
          
        if($module != SCRAP_MODULE)
        {
          $this->db->or_where('wp.warehouse_id',0);
        }

        $this->db->or_where('p.manage_inventory',MANAGE_INVENTORY_NO);
         

        $this->db->group_end();

        $this->db->where('p.delete_status',0);
        $this->db->where('p.status',PRODUCT_STATUS_ACTIVE);

        $this->db->group_start();
        $this->db->like('LOWER(p.name)', strtolower($search_term),'both');
        $this->db->or_like('LOWER(pc.name)', strtolower($search_term),'both');
        $this->db->group_end();

        $query = $this->db->get();
        return $query->result();
      }
      else
      {
          
        return $this->db->select('p.id,p.name,pc.name as product_category_name')
                        ->from('product p')
                        ->join('product_category pc','pc.id = p.product_category_id','left')
                        ->where('p.manage_inventory', MANAGE_INVENTORY_YES)
                        ->where('p.delete_status',0)
                        ->where('p.status',PRODUCT_STATUS_ACTIVE)
                        ->group_start()
                        ->like('p.name', $search_term)
                        ->or_like('pc.name', $search_term)
                        ->group_end()
                        ->get()
                        ->result();
      }
    }
    
    public function get_single_record($product_id)
    {
      return $this->db->select('p.*,t.cgst as cgst,t.sgst as sgst,t.igst as igst,t.id as tax_id,pc.tax_type as tax_type,pc.name as product_category_name,u.name as uom_name,u.uom as uom_uom,u.id as uom_id,pc.name as product_category_name')
                         ->from('product p')
                         ->join('product_category pc','pc.id = p.product_category_id','LEFT')
                         ->join('uom u','u.id = p.uom_id','LEFT')
                         ->join('tax t','t.id = pc.tax_id','LEFT')
                         ->where('p.id',$product_id)
                         ->get()
                         ->row();

        
    }




      /************************************ Start Dynamic Datatable function ***********************************/

    // private function _get_datatables_query()
    // {
    //     /* $this->db->select*/
    //     $this->db->from($this->table);

    //     if($_POST['warehouse_id'] != ''){
    //       $this->db->group_start();
    //         $this->db->where('warehouse_id',$_POST['warehouse_id']);     
    //         $this->db->or_where('warehouse_id',0);  
    //       $this->db->group_end();
    //     }
          
        
    //     // $this->db->or_group_start();
       
    //     if($_POST['manage_inventory'] != '')  
    //       $this->db->where('manage_inventory',$_POST['manage_inventory']);     
          
    //     // $this->db->group_end();

    //     if($_POST['product_id'] != '')
    //       $this->db->where('id',$_POST['product_id']);   
        

    //     if (isset($_POST['quantity']) && $_POST['quantity'] == QUANTITY_GREATER_THEN_ZERO) {
    //       $this->db->where($this->table.'.quantity >', 0);
    //     }
    
    //     if (isset($_POST['quantity']) && $_POST['quantity'] == QUANTITY_ZERO) {
    //         $this->db->where($this->table.'.quantity =', 0);
    //     }
  
  
    //     if(isset($_POST['product_status']) && $_POST['product_status'] == PRODUCT_DELETE_STATUS_ALL)  
    //       $this->db->where($this->table.'*',PRODUCT_DELETE_STATUS_ALL);   
        
          
  
  
    //     if(isset($_POST['product_status']) && $_POST['product_status'] == PRODUCT_STATUS_INACTIVE)  
    //       $this->db->where($this->table.'.status',PRODUCT_STATUS_INACTIVE); 
  
    //     if(isset($_POST['product_status']) && $_POST['product_status'] == PRODUCT_STATUS_ACTIVE)  
    //       $this->db->where($this->table.'.status',PRODUCT_STATUS_ACTIVE); 

    //     $this->db->where('delete_status',0);    
 
    //     $i = 0;
     
    //     foreach ($this->column_search as $item) // loop column 
    //     {
    //         if($_POST['search']['value']) // if datatable send POST for search
    //         {
                 
    //             if($i===0) // first loop
    //             {
    //                 $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
    //                 $this->db->like($item, $_POST['search']['value']);
    //             }
    //             else
    //             {
    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }
 
    //             if(count($this->column_search) - 1 == $i) //last loop
    //                 $this->db->group_end(); //close bracket
    //         }
    //         $i++;
    //     }
         
    //     if(isset($_POST['order'])) // here order processing
    //     {
    //         $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    //     } 
    //     else if(isset($this->order))
    //     {
    //         $order = $this->order;
    //         $this->db->order_by(key($order), $order[key($order)]);
    //     }
    // }
    
    private function _get_datatables_query()
{
    // 1. SELECT: We get everything from the view + master prices + tax type
    $this->db->select('pv.*, p.cost as master_cost, p.price as master_price, pc.tax_type, t.igst, t.cgst, t.sgst');
    $this->db->from($this->table . ' pv'); // pv = product_view
    
    // 2. JOINS: Needed to fetch fallback prices and tax settings
    $this->db->join('product p', 'p.id = pv.id', 'left');
    $this->db->join('product_category pc', 'pc.id = p.product_category_id', 'left');
    $this->db->join('tax t', 't.id = pc.tax_id', 'left');

    // 3. WAREHOUSE FILTER
    if($_POST['warehouse_id'] != ''){
      $this->db->group_start();
        $this->db->where('pv.warehouse_id', $_POST['warehouse_id']);     
        $this->db->or_where('pv.warehouse_id', 0);  
      $this->db->group_end();
    }
      
    // 4. MANAGE INVENTORY FILTER
    if($_POST['manage_inventory'] != '')  
      $this->db->where('pv.manage_inventory', $_POST['manage_inventory']);     

    // 5. SPECIFIC PRODUCT FILTER
    if($_POST['product_id'] != '')
      $this->db->where('pv.id', $_POST['product_id']);   
    
    // 6. QUANTITY FILTERS
    if (isset($_POST['quantity']) && $_POST['quantity'] == QUANTITY_GREATER_THEN_ZERO) {
      $this->db->where('pv.quantity >', 0);
    }

    if (isset($_POST['quantity']) && $_POST['quantity'] == QUANTITY_ZERO) {
        $this->db->where('pv.quantity =', 0);
    }

    // 7. STATUS FILTERS
    if(isset($_POST['product_status']) && $_POST['product_status'] == PRODUCT_STATUS_INACTIVE)  
      $this->db->where('pv.status', PRODUCT_STATUS_INACTIVE); 

    if(isset($_POST['product_status']) && $_POST['product_status'] == PRODUCT_STATUS_ACTIVE)  
      $this->db->where('pv.status', PRODUCT_STATUS_ACTIVE); 

    // 8. DELETE STATUS
    $this->db->where('pv.delete_status', 0);    

    // 9. SEARCH LOGIC (Adding pv. to prevent ambiguity)
    $i = 0;
    foreach ($this->column_search as $item) 
    {
        if($_POST['search']['value']) 
        {
            if($i===0) 
            {
                $this->db->group_start(); 
                $this->db->like('pv.'.$item, $_POST['search']['value']);
            }
            else
            {
                $this->db->or_like('pv.'.$item, $_POST['search']['value']);
            }

            if(count($this->column_search) - 1 == $i) 
                $this->db->group_end(); 
        }
        $i++;
    }
     
    // 10. ORDERING LOGIC (Adding pv. to prevent ambiguity)
    if(isset($_POST['order'])) 
    {
        $this->db->order_by('pv.'.$this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
        $order = $this->order;
        $this->db->order_by('pv.'.key($order), $order[key($order)]);
    }
}
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
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
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    /*********************************** End Dynamic Datatable function *****************************************/


    public function export_records()
    {
        $this->db->select('
                             p.pid as "PID",
                             p.name as "ProductName",
                             p.description as "ProductDescription",
                             u.uom as "UOM",
                             p.markup as "Markup",
                             p.hsn as "HSN",
                             wp.cost as "Cost",
                             wp.price as "Price",
                             wp.selling_price as "SellingPrice",
                             wp.batch_no as "BatchNo",
                             wp.quantity as "Quantity",
                          ');
        $this->db->from('warehouse_products wp');
        $this->db->join('product p', 'p.id = wp.product_id', 'left');
        $this->db->join('uom u', 'u.id = p.uom_id', 'left');
        $this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');
        
        $query = $this->db->get();
        return $query;
    }

//   public function get_all_movements_ledger($target_date = null, $pid = null) {
//                 $this->db->query("SET SESSION sql_mode = ''");
                
//                 $sql = "SELECT m.*, p.name as p_name, p.pid as p_code, p.hsn as p_hsn, p.status as p_status, p.alert_quantity as p_alert, u.uom as p_uom, p.price as p_mrp, p.cost as p_master_cost
//                         FROM (
//                             /* 1. Initial Stock from Product Creation */
//                             SELECT created_date as date, 'Initial Stock' as type, 'SYSTEM' as ref, product_id, quantity as in_qty, 0 as out_qty, cost as pur_price, 0 as sale_price FROM warehouse_products
                            
//                             UNION ALL
//                             /* 2. Purchase - ONLY Delivered */
//                             SELECT pd.delivery_date as date, 'Purchase' as type, p.invoice_no as ref, pdi.product_id, pdi.quantity as in_qty, 0 as out_qty, pdi.cost as pur_price, 0 as sale_price 
//                             FROM purchase_delivery_items pdi 
//                             JOIN purchase_delivery pd ON pd.id = pdi.purchase_delivery_id 
//                             JOIN purchase p ON p.id = pd.purchase_id WHERE p.delete_status = 0
            
//                             UNION ALL
//                             /* 3. Sale - ONLY Delivered */
//                             SELECT sd.delivery_date as date, 'Sale' as type, s.reference_no as ref, sdi.product_id, 0 as in_qty, sdi.quantity as out_qty, 0 as pur_price, sdi.selling_price as sale_price 
//                             FROM sale_delivery_items sdi 
//                             JOIN sale_delivery sd ON sd.id = sdi.sale_delivery_id 
//                             JOIN sale s ON s.id = sd.sale_id WHERE s.delete_status = 0
            
//                             UNION ALL
//                             /* 4. Purchase Return - ONLY Delivered */
//                             SELECT prd.delivery_date as date, 'Pur. Return' as type, pr.reference_no as ref, prdi.product_id, 0 as in_qty, prdi.quantity as out_qty, prdi.cost as pur_price, 0 as sale_price 
//                             FROM purchase_return_delivery_items prdi 
//                             JOIN purchase_return_delivery prd ON prd.id = prdi.purchase_return_delivery_id 
//                             JOIN purchase_return pr ON pr.id = prd.purchase_return_id WHERE pr.delete_status = 0
            
//                             UNION ALL
//                             /* 5. Sales Return - ONLY Delivered */
//                             SELECT srd.delivery_date as date, 'Sale Return' as type, sr.reference_no as ref, srdi.product_id, srdi.quantity as in_qty, 0 as out_qty, 0 as pur_price, srdi.cost as sale_price 
//                             FROM sales_return_delivery_items srdi 
//                             JOIN sales_return_delivery srd ON srd.id = srdi.sales_return_delivery_id 
//                             JOIN sales_return sr ON sr.id = srd.sales_return_id WHERE sr.delete_status = 0
//                         ) as m 
//                         LEFT JOIN product p ON p.id = m.product_id 
//                         LEFT JOIN uom u ON u.id = p.uom_id";
            
//                 $where = [];
//                 // if($target_date) $where[] = "DATE(m.date) <= " . $this->db->escape(date('Y-m-d', strtotime($target_date)));
//                 if($target_date) {
//                     // Just use the passed string, don't use strtotime here again
//                     $where[] = "DATE(m.date) <= " . $this->db->escape($target_date);
//                 }
//                 if($pid) $where[] = "m.product_id = " . $this->db->escape($pid);
                
//                 if(!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);
                
//                 $sql .= " ORDER BY m.date ASC"; // Needed for running balance math
//                 return $this->db->query($sql)->result();
//             }

public function get_all_movements_ledger($target_date = null, $pid = null) {
    $this->db->query("SET SESSION sql_mode = ''");
    
    // We add 'trans_mrp' to the subquery to capture the price at the time of transaction
    $sql = "SELECT m.*, 
                   p.name as p_name, p.pid as p_code, p.hsn as p_hsn, p.status as p_status, 
                   p.alert_quantity as p_alert, u.uom as p_uom, 
                   IF(m.trans_mrp > 0, m.trans_mrp, p.price) as p_mrp, /* Use transaction MRP if available, else master */
                   p.cost as p_master_cost
            FROM (
                /* 1. Initial Stock from Product Creation */
                /*SELECT created_date as date, 'Initial Stock' as type, 'SYSTEM' as ref, product_id, quantity as in_qty, 0 as out_qty, cost as pur_price, 0 as sale_price, price as trans_mrp 
                FROM warehouse_products
                
                UNION ALL */
                /* 2. Purchase - ONLY Delivered */
                SELECT pd.delivery_date as date, 'Purchase' as type, p.invoice_no as ref, pdi.product_id, pdi.quantity as in_qty, 0 as out_qty, pdi.cost as pur_price, 0 as sale_price, 0 as trans_mrp 
                FROM purchase_delivery_items pdi 
                JOIN purchase_delivery pd ON pd.id = pdi.purchase_delivery_id 
                JOIN purchase p ON p.id = pd.purchase_id WHERE p.delete_status = 0

                UNION ALL
                /* 3. Sale - ONLY Delivered */
                SELECT sd.delivery_date as date, 'Sale' as type, s.reference_no as ref, sdi.product_id, 0 as in_qty, sdi.quantity as out_qty, 0 as pur_price, sdi.selling_price as sale_price, 0 as trans_mrp 
                FROM sale_delivery_items sdi 
                JOIN sale_delivery sd ON sd.id = sdi.sale_delivery_id 
                JOIN sale s ON s.id = sd.sale_id WHERE s.delete_status = 0

                UNION ALL
                /* 4. Manual Stock Entries (THE MISSING PIECE FROM YOUR SCREENSHOT) */
                /* product_price in 'stock' table is the MRP */
                SELECT created_date as date, 
                       IF(entry_type='in', 'Stock In', 'Stock Out') as type, 
                       'MANUAL' as ref, 
                       product_id, 
                       IF(entry_type='in', quantity, 0) as in_qty, 
                       IF(entry_type='out', quantity, 0) as out_qty, 
                       product_cost as pur_price, 
                       selling_price as sale_price, 
                       product_price as trans_mrp 
                FROM stock 
                WHERE delete_status = 0

                UNION ALL
                /* 5. Purchase Return */
                SELECT prd.delivery_date as date, 'Pur. Return' as type, pr.reference_no as ref, prdi.product_id, 0 as in_qty, prdi.quantity as out_qty, prdi.cost as pur_price, 0 as sale_price, 0 as trans_mrp 
                FROM purchase_return_delivery_items prdi 
                JOIN purchase_return_delivery prd ON prd.id = prdi.purchase_return_delivery_id 
                JOIN purchase_return pr ON pr.id = prd.purchase_return_id WHERE pr.delete_status = 0

                UNION ALL
                /* 6. Sales Return */
                SELECT srd.delivery_date as date, 'Sale Return' as type, sr.reference_no as ref, srdi.product_id, srdi.quantity as in_qty, 0 as out_qty, 0 as pur_price, srdi.cost as sale_price, 0 as trans_mrp 
                FROM sales_return_delivery_items srdi 
                JOIN sales_return_delivery srd ON srd.id = srdi.sales_return_delivery_id 
                JOIN sales_return sr ON sr.id = srd.sales_return_id WHERE sr.delete_status = 0
            ) as m 
            LEFT JOIN product p ON p.id = m.product_id 
            LEFT JOIN uom u ON u.id = p.uom_id";

    $where = [];
    if($target_date) {
        $where[] = "DATE(m.date) <= " . $this->db->escape($target_date);
    }
    if($pid) {
        $where[] = "m.product_id = " . $this->db->escape($pid);
    }
    
    if(!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);
    
    $sql .= " ORDER BY m.date ASC"; 
    return $this->db->query($sql)->result();
}

}
?>
