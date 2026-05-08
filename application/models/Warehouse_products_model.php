<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_products_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	// public function get_records()
	// {
	// 	return $this->db->select('*')
  //                   ->from('warehouse_products')
  //                   ->get()
  //                   ->result();
	// }

  public function get_records($withBatchNoOnly = false)
	{
		$this->db->select('wp.*,p.name,w.name as warehouse_name,pc.name as product_category_name, u.uom as product_uom,p.id as product_id');
    $this->db->from('warehouse_products wp');
    $this->db->join('product p','p.id = wp.product_id','left');
    $this->db->join('product_category pc','pc.id = p.product_category_id','left');
    $this->db->join('uom u','u.id = p.uom_id','left');
    $this->db->join('warehouse w','w.id = wp.warehouse_id','left');

    if($withBatchNoOnly == true)
    
      $this->db->where('wp.batch_no !=', '');

    $this->db->where('p.delete_status',0);
    $query = $this->db->get();
    return $query->result();
	}

    public function get_records_by_warehouse_id($warehouse_id)
    {
        return $this->db->select('wp.*, p.name as product_name, pc.name as product_category_name, u.uom as product_uom,p.id as product_id')
                        ->from('warehouse_products wp')
                        ->join('product p','p.id = wp.product_id','left')
                        ->join('product_category pc','pc.id = p.product_category_id','left')
                        ->join('uom u','u.id = p.uom_id','left')
                        ->where('wp.warehouse_id',$warehouse_id)
                        // ->where('wp.quantity >',0)
                        ->get()
                        ->result();
    }

    public function get_records_by_product_id_warehouse_id($warehouse_id, $product_id)
    {
        return $this->db->select('wp.*')
                         ->from('warehouse_products wp')
                         ->where('wp.product_id',$product_id)
                         ->where('wp.warehouse_id',$warehouse_id)
                         ->get()
                         ->result();   
    }

    public function get_records_by_product_id_warehouse_id_cost($warehouse_id, $product_id, $cost)
    {
        return $this->db->select('wp.*')
                        ->from('warehouse_products wp')
                        ->where('wp.product_id',$product_id)
                        ->where('wp.warehouse_id',$warehouse_id)
                        ->where('wp.cost',$cost)
                        ->get()
                        ->row();   
    }
    public function get_records_by_product_id_warehouse_id_price($warehouse_id, $product_id, $price)
    {
        return $this->db->select('wp.*')
                        ->from('warehouse_products wp')
                        ->where('wp.product_id',$product_id)
                        ->where('wp.warehouse_id',$warehouse_id)
                        ->where('wp.price',$price)
                        ->get()
                        ->row();   
    }

    public function get_records_by_product_id_warehouse_id_cost_price($warehouse_id, $product_id, $batch_no, $cost = null, $price = null,$selling_price = null)
    {
      $this->db->select('wp.*');
      $this->db->from('warehouse_products wp');
      $this->db->where('wp.product_id',$product_id);
      $this->db->where('wp.warehouse_id',$warehouse_id);

      if($cost != NULL)
        $this->db->where('wp.cost',$cost);

      if($price != NULL)
        $this->db->where('wp.price',$price);

      if($selling_price != NULL)
        $this->db->where('wp.selling_price',$selling_price);
      
      $this->db->where('wp.batch_no',$batch_no);

      $query = $this->db->get();
      return $query->row();   
    }
    

    public function get_records_by_product_id($product_id)
    {
        return $this->db->select('wp.*,w.name as warehouse_name, p.name as product_name')
                         ->from('warehouse_products wp')
                         ->join('warehouse w','w.id = wp.warehouse_id')
                         ->join('product p','p.id = wp.product_id')
                         ->where('wp.product_id',$product_id)
                          ->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""')
                         ->get()
                         ->result();   
    }

    public function get_records_by_product_alerts()
    {
        return $this->db->select('wp.*,w.name as warehouse_name, p.name as product_name')
                         ->from('warehouse_products wp')
                         ->join('warehouse w','w.id = wp.warehouse_id')
                         ->join('product p','p.id = wp.product_id')
                         ->where('wp.quantity < p.alert_quantity')
                         ->where('p.delete_status',0)
                         ->where('wp.cost >',0)
                         ->get()
                         ->result(); 
    }

    public function add_record($data)
    {
        if($this->db->insert('warehouse_products',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

     public function edit_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('warehouse_products',$data)){
            return true;
        }
        else{
            return false;
        }
    }

    public function delete_record($data,$id)
    {
        $this->db->where('id',$id);

        if($this->db->delete('warehouse_products'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_single_record($warehouse_products_id)
    {
        return $this->db->get_where('warehouse_products', array("id" => $warehouse_products_id))->row();
    }

    public function get_single_record_for_sales($product_id)
    {
        return $this->db->select('p.*,t.cgst as cgst,t.sgst as sgst,t.igst as igst,t.id as tax_id,pc.tax_type as tax_type,wp.price as price,wp.id as warehouse_products_id,wp.cost as cost,u.name as uom_name,u.uom as uom_uom,u.id as uom_id,wp.quantity as quantity,wp.batch_no as batch_no')
                        ->from('warehouse_products wp')
                        ->join('product p','p.id = wp.product_id')
                        ->join('product_category pc','pc.id = p.product_category_id','LEFT')
                        ->join('uom u','u.id = p.uom_id','LEFT')
                        ->join('tax t','t.id = pc.tax_id','LEFT')
                        ->where('wp.id',$product_id)
                        ->get()
                        ->row();
    }

    public function get_warehouse_wise_product_quantity($product_id)
    {
        return $this->db->select('SUM(wp.quantity) as quantity, wp.warehouse_id, wp.product_id, wp.cost')
                         ->from('warehouse_products wp')
                         ->where('wp.product_id',$product_id)
                         ->group_by(array('wp.warehouse_id','wp.product_id','wp.cost'))
                         ->get()
                         ->result(); 
    }

    public function get_product_quantity_warehouse_wise($warehouse_id)
    {
        return $this->db->select('SUM(wp.quantity) as quantity, wp.warehouse_id, wp.product_id, wp.cost')
                         ->from('warehouse_products wp')
                         ->where('wp.warehouse_id',$warehouse_id)
                         ->group_by(array('wp.product_id','wp.cost','wp.warehouse_id'))
                         ->get()
                         ->result(); 
    }

    public function get_single_product_by_warehouse_id_product_id_batch_no($warehouse_id, $product_id, $batch_no)
    {
      $this->db->select('wp.*');
      $this->db->from('warehouse_products wp');
      $this->db->where('wp.warehouse_id',$warehouse_id);
      $this->db->where('wp.product_id',$product_id);
      $this->db->where('wp.batch_no',$batch_no);
      
      $query = $this->db->get();
      return $query->row();
    }

    public function get_batch_nos_by_product_id($product_id)
    {
      $this->db->select('wp.*');
      $this->db->from('warehouse_products wp');
      $this->db->where('wp.product_id',$product_id);
      // $this->db->where('wp.delete_status',0);
      $this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');

      $query = $this->db->get();

      return $query->result();

    }

    public function get_records_by_pid_batch_no($pid,$batch_no)
    {
      $this->db->select('wp.*, p.name as product_name, pc.name as product_category_name, u.uom as product_uom, p.id as product_id, p.pid as pid');
      $this->db->from('warehouse_products wp');
      $this->db->join('product p', 'p.id = wp.product_id', 'left');
      $this->db->join('product_category pc', 'pc.id = p.product_category_id', 'left');
      $this->db->join('uom u', 'u.id = p.uom_id', 'left');
      $this->db->where('p.pid', $pid);
      $this->db->where('wp.batch_no', $batch_no);
      $this->db->where('wp.quantity >', 0);
      $this->db->order_by('wp.quantity', 'desc'); // Order by quantity in descending order
      $this->db->limit(1); // Limit the result to a single record
      $query = $this->db->get();
      
      return $query->row(); // Get a single row
    }

    public function get_single_warehouse_product_record($warehouse_products_id)
    {
      $this->db->select('sp.*,p.name,p.description,u.id as uom_id,u.name as uom_name,u.uom as uom_uom,t.cgst as cgst,t.sgst as sgst,t.igst as igst,t.id as tax_id,pc.tax_type as tax_type');
      $this->db->from('warehouse_products sp');
      $this->db->join('product p','p.id = sp.product_id','left');
      $this->db->join('product_category pc','pc.id = p.product_category_id','LEFT');
      $this->db->join('tax t','t.id = pc.tax_id','LEFT');
      $this->db->join('uom u','u.id = p.uom_id','LEFT');
      $this->db->where('sp.id',$warehouse_products_id);
      $query = $this->db->get();
      return $query->row(); 
    }
      public function get_record_by_warehouse_product($warehouse_id, $product_id) {
        $this->db->where('warehouse_id', $warehouse_id);
        $this->db->where('product_id', $product_id);
        $query = $this->db->get($this->table);
        return $query->row();
    }
    
    public function update_stock($warehouse_id, $product_id, $quantity, $operation = 'add') {
        $existing = $this->get_record_by_warehouse_product($warehouse_id, $product_id);
        
        if($existing) {
            if($operation == 'add') {
                $new_quantity = $existing->quantity + $quantity;
            } else {
                $new_quantity = $existing->quantity - $quantity;
            }
            
            $this->db->where('id', $existing->id);
            return $this->db->update($this->table, ['quantity' => $new_quantity]);
        } else {
            $data = array(
                'warehouse_id' => $warehouse_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'created_date' => date('Y-m-d H:i:s')
            );
            return $this->db->insert($this->table, $data);
        }
    }

    // public function get_warehouse_stock_value($warehouse_id = null)
    // {
    //     if($warehouse_id == null)
    //     {
            
    //     }
    // }
}
?>
