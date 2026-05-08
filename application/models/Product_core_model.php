<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_core_model extends CI_Model {

    var $table = 'product';
    var $column_order = array(
                              'product_image',
                              'name',
                              'uom_id',
                              'manage_inventory',
                              'product_category_id',
                              'description',
                              'hsn',
                              'cost',
                              'price',
                              'selling_price',
                              'status'
                            ); //set column field database for datatable orderable
    var $column_search = array(
                              'product_image',
                              'name',
                              'uom_id',
                              'manage_inventory',
                              'product_category_id',
                              'description',
                              'hsn',
                              'cost',
                              'price',
                              'selling_price',
                              'status'
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
// Add these methods to your product_core_model.php

/**
 * Get records by product name (exact match)
 */
public function get_records_by_name($product_name)
{
    $this->db->where('name', $product_name);
    $this->db->where('delete_status', 0);
    $query = $this->db->get('product');
    
    if ($query->num_rows() > 0) {
        return $query->row();
    }
    return null;
}

/**
 * Get records by product name (case-insensitive)
 */
public function get_records_by_name_case_insensitive($product_name)
{
    $this->db->where('LOWER(name)', strtolower($product_name));
    $this->db->where('delete_status', 0);
    $query = $this->db->get('product');
    
    if ($query->num_rows() > 0) {
        return $query->row();
    }
    return null;
}
    public function get_records_by_last_import($limit = null, $data  = null)
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
      if($module == SALE_MODULE || $module == SALE_RETURN_MODULE)
      {
          
        $this->db->select('wp.id,p.name,wp.price,wp.id as warehouse_products_id,pc.name as product_category_name');
        $this->db->from('product p');
        $this->db->join('product_category pc','pc.id = p.product_category_id','left');
        $this->db->join('warehouse_products wp','wp.product_id = p.id');

        if($module == SALE_MODULE)
        {
          $this->db->where('wp.price > ','0'); 
        }

        $this->db->where('wp.warehouse_id',$warehouse_id);
        $this->db->where('p.delete_status',0);
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
                        ->where('p.delete_status',0)
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

    public function get_records_by_pid($pid)
    {
      $this->db->select('wp.quantity, p.*');
      $this->db->from('warehouse_products wp');
      $this->db->join('product p', 'p.id = wp.product_id', 'left');
      $this->db->join('product_category pc', 'pc.id = p.product_category_id', 'left');
      $this->db->join('uom u', 'u.id = p.uom_id', 'left');
      $this->db->where('p.pid', $pid);
      // $this->db->where('wp.batch_no', $batch_no);
      // $this->db->where('wp.quantity >', 0);
      $this->db->order_by('wp.quantity', 'desc'); // Order by quantity in descending order
      $this->db->limit(1); // Limit the result to a single record
      $query = $this->db->get();
      
      return $query->row(); // Get a single row
    }

    public function get_matched_single_record($product_id)
    {
        return $this->db->select('p.*,t.cgst as cgst,t.sgst as sgst,t.igst as igst,t.id as tax_id,pc.tax_type as tax_type,wp.price as price,wp.id as warehouse_products_id,wp.cost as cost,u.name as uom_name,u.uom as uom_uom,u.id as uom_id,wp.quantity as quantity')
                        ->from('warehouse_products wp')
                        ->join('product p','p.id = wp.product_id')
                        ->join('product_category pc','pc.id = p.product_category_id','LEFT')
                        ->join('uom u','u.id = p.uom_id','LEFT')
                        ->join('tax t','t.id = pc.tax_id','LEFT')
                        ->where('p.id',$product_id)
                        ->get()
                        ->row();
    }

}
?>
