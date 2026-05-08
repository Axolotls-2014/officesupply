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

    private function _get_datatables_query()
    {
        /* $this->db->select*/
        $this->db->from($this->table);

        if($_POST['warehouse_id'] != ''){
          $this->db->group_start();
            $this->db->where('warehouse_id',$_POST['warehouse_id']);     
            $this->db->or_where('warehouse_id',0);  
          $this->db->group_end();
        }
          
        
        // $this->db->or_group_start();
       
        if($_POST['manage_inventory'] != '')  
          $this->db->where('manage_inventory',$_POST['manage_inventory']);     
          
        // $this->db->group_end();

        if($_POST['product_id'] != '')
          $this->db->where('id',$_POST['product_id']);   
        

        if (isset($_POST['quantity']) && $_POST['quantity'] == QUANTITY_GREATER_THEN_ZERO) {
          $this->db->where($this->table.'.quantity >', 0);
        }
    
        if (isset($_POST['quantity']) && $_POST['quantity'] == QUANTITY_ZERO) {
            $this->db->where($this->table.'.quantity =', 0);
        }
  
  
        if(isset($_POST['product_status']) && $_POST['product_status'] == PRODUCT_DELETE_STATUS_ALL)  
          $this->db->where($this->table.'*',PRODUCT_DELETE_STATUS_ALL);   
        
          
  
  
        if(isset($_POST['product_status']) && $_POST['product_status'] == PRODUCT_STATUS_INACTIVE)  
          $this->db->where($this->table.'.status',PRODUCT_STATUS_INACTIVE); 
  
        if(isset($_POST['product_status']) && $_POST['product_status'] == PRODUCT_STATUS_ACTIVE)  
          $this->db->where($this->table.'.status',PRODUCT_STATUS_ACTIVE); 

        $this->db->where('delete_status',0);    
 
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


}
?>
