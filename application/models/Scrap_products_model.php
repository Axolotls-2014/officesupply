<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class scrap_products_model extends CI_Model {

  var $table = 'scrap_product_view';
  var $column_order = array(
                           
                            'name',
                          
                            'batch_no',
                            'quantity',
                            'cost',
                            'price',
                            'selling_price',
                            'warehouse_name'
                           
                          ); //set column field database for datatable orderable
  var $column_search = array(
                           
                            'name',
                            'batch_no',
                            'quantity',
                            'cost',
                            'price',
                            'selling_price',
                            'warehouse_name'
                          ); //set column field database for datatable searchable 
  var $order = array('id' => 'desc'); // default order 



    function __construct() {
      parent::__construct();

    
}

public function search_record($search_term = null, $module = null, $warehouse_id = null)
{
  // if($module == SALE_MODULE || $module == SALE_RETURN_MODULE)
  // {
      
    $this->db->select('sp.*,p.name');


    $this->db->from('scrap_products sp');
    $this->db->join('product p','p.id = sp.product_id');

   
    if($module == SCRAP_ISSUE_MODULE)
      $this->db->where('sp.quantity > ',0);


    // $this->db->where('wp.warehouse_id',$warehouse_id);
    // $this->db->where('sp.delete_status',0);
   
    $this->db->group_start();
      $this->db->like('LOWER(p.name)', strtolower($search_term),'both');
      // $this->db->or_like('LOWER(p.hsn)', strtolower($search_term),'both');
      // $this->db->or_like('LOWER(p.description)', strtolower($search_term),'both');
      // $this->db->or_like('LOWER(wp.batch_no)', strtolower($search_term),'both');
      // $this->db->or_like('LOWER(pc.name)', strtolower($search_term),'both');
     
    $this->db->group_end();

    

    $query = $this->db->get();
    return $query->result();

}

public function search_warehouse_product_record($search_term = null, $module = null, $warehouse_id = null)
{
  // if($module == SALE_MODULE || $module == SALE_RETURN_MODULE)
  // {
      
    $this->db->select('sp.*,p.name');


    $this->db->from('warehouse_products sp');
    $this->db->join('product p','p.id = sp.product_id');

   
    // $this->db->where('wp.warehouse_id',$warehouse_id);
 
   
    $this->db->group_start();
      $this->db->like('LOWER(p.name)', strtolower($search_term),'both');
      // $this->db->or_like('LOWER(p.hsn)', strtolower($search_term),'both');
      // $this->db->or_like('LOWER(p.description)', strtolower($search_term),'both');
      // $this->db->or_like('LOWER(wp.batch_no)', strtolower($search_term),'both');
      // $this->db->or_like('LOWER(pc.name)', strtolower($search_term),'both');
    
    $this->db->group_end();

    

    $query = $this->db->get();
    return $query->result();

}

	public function get_records($withBatchNoOnly = false)
	{
		$this->db->select('wp.*,p.name AS product_name,p.pid as pid,p.description AS product_description');
    $this->db->from('scrap_products wp');
    $this->db->join('product p','p.id = wp.product_id','left');

    if($withBatchNoOnly == true)
    
      $this->db->where('wp.batch_no !=', '');

    $this->db->where('p.delete_status',0);
    $query = $this->db->get();
    return $query->result();
	}

    public function get_records_by_warehouse_id($warehouse_id)
    {
        return $this->db->select('wp.*, p.name as product_name, pc.name as product_category_name, u.uom as product_uom,p.id as product_id')
                        ->from('scrap_products wp')
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
                         ->from('scrap_products wp')
                         ->where('wp.product_id',$product_id)
                         ->where('wp.warehouse_id',$warehouse_id)
                         ->get()
                         ->result();   
    }

    public function get_records_by_product_id_warehouse_id_cost($warehouse_id, $product_id, $cost)
    {
        return $this->db->select('wp.*')
                        ->from('scrap_products wp')
                        ->where('wp.product_id',$product_id)
                        ->where('wp.warehouse_id',$warehouse_id)
                        ->where('wp.cost',$cost)
                        ->get()
                        ->row();   
    }
    public function get_records_by_product_id_warehouse_id_price($warehouse_id, $product_id, $price)
    {
        return $this->db->select('wp.*')
                        ->from('scrap_products wp')
                        ->where('wp.product_id',$product_id)
                        ->where('wp.warehouse_id',$warehouse_id)
                        ->where('wp.price',$price)
                        ->get()
                        ->row();   
    }

    public function get_records_by_product_id_warehouse_id_cost_price($warehouse_id, $product_id, $cost, $price)
    {
      return $this->db->select('wp.*')
                      ->from('scrap_products wp')
                      ->where('wp.product_id',$product_id)
                      ->where('wp.warehouse_id',$warehouse_id)
                      ->where('wp.cost',$cost)
                      ->where('wp.price',$price)
                      ->get()
                      ->row();   
    }
    

    public function get_records_by_product_id($product_id)
    {
        return $this->db->select('wp.*,w.name as warehouse_name, p.name as product_name')
                         ->from('scrap_products wp')
                         ->join('warehouse w','w.id = wp.warehouse_id')
                         ->join('product p','p.id = wp.product_id')
                         ->where('wp.product_id',$product_id)
                         ->where('wp.delete_status',NOT_DELETED)
                         ->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""')
                         ->get()
                         ->result();   
    }

    

    public function get_records_by_product_alerts()
    {
        return $this->db->select('wp.*,w.name as warehouse_name, p.name as product_name')
                         ->from('scrap_products wp')
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
        if($this->db->insert('scrap_products',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

    public function edit_record($data,$id,$update_instance_by_product_id = false)
    {   
      if($update_instance_by_product_id == false)
        $this->db->where('id',$id);
      else
        $this->db->where('product_id',$id);

      if($this->db->update('scrap_products',$data)){
        return true;
      }
      else{
        return false;
      }
    }
    

    public function delete_record($id)
    {
      $data['delete_status'] = DELETED;
      $this->db->where('id',$id);
      if($this->db->update('scrap_products',$data))
      {
        return true;
      }
      else
      {
        return false;
      }
    }

    public function delete_record_by_variant($product_id,$variant)
    {
      $data['delete_status'] = DELETED;
      $this->db->where('product_id',$product_id);

      $this->db->group_start();
        $this->db->where('variant1',$variant);
        $this->db->or_where('variant2',$variant);
        $this->db->or_where('variant3',$variant);
      $this->db->group_end();

      $this->db->where('quantity',0);

      if($this->db->update('scrap_products',$data))
      {
        return true;
      }
      else
      {
        return false;
      } 
    }

    public function get_single_record($scrap_products_id)
    {
      $this->db->select('sp.*,p.name,u.id as uom_id,u.name as uom_name,u.uom as uom_uom,t.cgst as cgst,t.sgst as sgst,t.igst as igst,t.id as tax_id,pc.tax_type as tax_type');
      $this->db->from('scrap_products sp');
      $this->db->join('product p','p.id = sp.product_id','left');
      $this->db->join('product_category pc','pc.id = p.product_category_id','LEFT');
      $this->db->join('tax t','t.id = pc.tax_id','LEFT');
      $this->db->join('uom u','u.id = p.uom_id','LEFT');
      $this->db->where('sp.id',$scrap_products_id);
      $query = $this->db->get();
      return $query->row(); 
    }

    public function get_single_record_by_product_variant($warehouse_id,$product_id,$variant1,$variant2,$variant3)
    {
      $this->db->select('wp.*');
      $this->db->from('scrap_products wp');
      $this->db->where('wp.warehouse_id',$warehouse_id);
      $this->db->where('wp.product_id',$product_id);
      $this->db->where('wp.variant1',$variant1);
      $this->db->where('wp.variant2',$variant2);
      $this->db->where('wp.variant3',$variant3);

      $query = $this->db->get();
      return $query->row();
    }

    public function get_single_record_for_sales($scrap_products_id)
    {
        return $this->db->select('p.*,t.cgst as cgst,t.sgst as sgst,t.igst as igst,t.id as tax_id,pc.tax_type as tax_type,wp.price as price,wp.id as scrap_products_id,wp.cost as cost,u.name as uom_name,u.uom as uom_uom,u.id as uom_id,wp.quantity as quantity, wp.batch_no as batch_no')
                        ->from('scrap_products wp')
                        ->join('product p','p.id = wp.product_id')
                        ->join('product_category pc','pc.id = p.product_category_id','LEFT')
                        ->join('uom u','u.id = p.uom_id','LEFT')
                        ->join('tax t','t.id = pc.tax_id','LEFT')
                        ->where('wp.id',$scrap_products_id)
                        ->get()
                        ->row();
    }

    public function get_warehouse_wise_product_quantity($product_id)
    {
        return $this->db->select('SUM(wp.quantity) as quantity, wp.warehouse_id, wp.product_id, wp.cost')
                         ->from('scrap_products wp')
                         ->where('wp.product_id',$product_id)
                         ->group_by(array('wp.warehouse_id','wp.product_id','wp.cost'))
                         ->get()
                         ->result(); 
    }

    public function get_product_quantity_warehouse_wise($warehouse_id)
    {
        return $this->db->select('SUM(wp.quantity) as quantity, wp.warehouse_id, wp.product_id, wp.cost')
                         ->from('scrap_products wp')
                         ->where('wp.warehouse_id',$warehouse_id)
                         ->group_by(array('wp.product_id','wp.cost','wp.warehouse_id'))
                         ->get()
                         ->result(); 
    }

    public function get_single_product_by_warehouse_id_product_id_batch_no($warehouse_id, $product_id, $batch_no)
    {
      $this->db->select('wp.*');
      $this->db->from('scrap_products wp');
      $this->db->where('wp.warehouse_id',$warehouse_id);
      $this->db->where('wp.product_id',$product_id);
      $this->db->where('wp.batch_no',$batch_no);
      
      $query = $this->db->get();
      return $query->row();
    }

    public function is_product_exist_with_variant($product_id,$variant,$data = false)
    {
      $this->db->select('wp.*');
      $this->db->from('scrap_products wp');
      $this->db->where('wp.product_id',$product_id);

      $this->db->group_start();
        $this->db->where('wp.variant1',$variant);
        $this->db->or_where('wp.variant2',$variant);
        $this->db->or_where('wp.variant3',$variant);
      $this->db->group_end();

      $this->db->where('wp.quantity >',0);
      
      $query = $this->db->get();
      $result = $query->result();  

      if($data == false)
      {
        if(sizeof($result) > 0)
          return true;
        else
          return false;
      }
      else
      {
        return $result;
      }
    }

    public function get_batch_nos_by_product_id($product_id)
    {
      $this->db->select('wp.batch_no,DATE_FORMAT(wp.mfg_date, "%d-%m-%Y") AS mfg_date,DATE_FORMAT(wp.expiry_date, "%d-%m-%Y") AS expiry_date');
      $this->db->from('scrap_products wp');
      $this->db->where('wp.product_id',$product_id);
      $this->db->where('wp.delete_status',0);
      $this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');

      $query = $this->db->get();

      return $query->result();

      // $batch_no_array = array();

      // foreach ($result as $value) {
      //   $batch_no_array[] = $value->batch_no;
      // }

      // return $batch_no_array;
    }

    public function get_product_quantity_by_product_id($product_id)
    {
        return $this->db->select('SUM(wp.quantity) as quantity')
                         ->from('scrap_products wp')
                         ->where('wp.product_id',$product_id)
                         ->group_by(array('wp.product_id'))
                         ->get()
                         ->row(); 
    }

    public function get_records_by_pid_batch_no($pid,$batch_no)
    {
      $this->db->select('wp.*, p.name as product_name, pc.name as product_category_name, u.uom as product_uom, p.id as product_id, p.pid as pid');
      $this->db->from('scrap_products wp');
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

    public function get_records_by_batch_nos($product_id)
    {
      $this->db->select('wp.*, p.name as product_name,p.pid,p.description');
      $this->db->from('scrap_products wp');
      $this->db->join('product p', 'p.id = wp.product_id');
      $this->db->where('wp.product_id', $product_id);
      $this->db->where('wp.delete_status', NOT_DELETED);
      $this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');
      $this->db->group_by('wp.batch_no'); // Group by batch numbers
      $query = $this->db->get();

      return $query->result();
    }

    public function get_records_by_product_id_batch_no($product_id,$batch_no)
    {
      $this->db->select('wp.*, p.name as product_name, pc.name as product_category_name, u.uom as product_uom, p.id as product_id, p.pid as pid');
      $this->db->from('scrap_products wp');
      $this->db->join('product p', 'p.id = wp.product_id', 'left');
      $this->db->join('product_category pc', 'pc.id = p.product_category_id', 'left');
      $this->db->join('uom u', 'u.id = p.uom_id', 'left');
      $this->db->where('p.id', $product_id);
      $this->db->where('wp.batch_no', $batch_no);
      // $this->db->where('wp.quantity >', 0);
      $this->db->order_by('wp.quantity', 'desc'); // Order by quantity in descending order
      $this->db->limit(1); // Limit the result to a single record
      $query = $this->db->get();
      
      return $query->row(); // Get a single row
    }    

    public function get_single_record_for_sale_by_pid($pid)
    {
        return $this->db->select('p.*,t.cgst as cgst,t.sgst as sgst,t.igst as igst,t.id as tax_id,pc.tax_type as tax_type,wp.price as price,wp.id as scrap_products_id,wp.cost as cost,u.name as uom_name,u.uom as uom_uom,u.id as uom_id,wp.quantity as quantity,wp.variant1,wp.variant2,wp.variant3, wp.pack as wp_pack, wp.batch_no as batch_no, wp.rack,wp.mfg_date,wp.expiry_date,wp.ptr as wp_ptr,wp.ptd as wp_ptd,pc.tax_type')
                        ->from('scrap_products wp')
                        ->join('product p','p.id = wp.product_id')
                        ->join('product_category pc','pc.id = p.product_category_id','LEFT')
                        ->join('uom u','u.id = p.uom_id','LEFT')
                        ->join('tax t','t.id = pc.tax_id','LEFT')
                        ->where('p.pid',$pid)
                        ->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""')
                        ->where('wp.batch_no <>','')
                        ->order_by('wp.quantity' , 'asc')
                        ->get()
                        ->result();
        
    }
    
 
     /************************************ Start Dynamic Datatable function ***********************************/

     private function _get_datatables_query()
     {
     
         $this->db->select($this->table.'.*');
         $this->db->from($this->table);
         // $this->db->join('warehouse_products','warehouse_products.id = '.$this->table.'.warehouse_product_id','LEFT');
         // $this->db->join('supplier','supplier.id = '.$this->table.'.supplier_id','LEFT');
 
         // if($_POST['warehouse_product_id'] != '')
         //   $this->db->where($this->table.'.warehouse_product_id',$_POST['warehouse_product_id']);        
 
         // if($_POST['product_with_zero_quantity'] == 'hide')
         //   $this->db->where($this->table.'.quantity > 0');      
 
         // if(isset($_POST['product_status']) && $_POST['product_status'] != '')
         //   $this->db->where($this->table.'.wp_status',$_POST['product_status']);      
 
       if (isset($_POST['quantity']) && $_POST['quantity'] == QUANTITY_GREATER_THEN_ZERO) {
         $this->db->where($this->table.'.quantity >', 0);
       }
   
       if (isset($_POST['quantity']) && $_POST['quantity'] == QUANTITY_ZERO) {
           $this->db->where($this->table.'.quantity =', 0);
       }
 
 
        
  
         $i = 0;
      
         $searchValue = str_replace(' ', '', $_POST['search']['value']); // Remove spaces from the search term
 
         foreach ($this->column_search as $item) // loop column 
         {
           if($searchValue) // if the search term exists after removing spaces
           {
               if($i === 0) // first loop
               {
                   $this->db->group_start(); // open bracket for OR clauses
                   $this->db->like("REPLACE($item, ' ', '')", $searchValue);
               }
               else
               {
                   $this->db->or_like("REPLACE($item, ' ', '')", $searchValue);
               }
       
               if(count($this->column_search) - 1 == $i) // last loop
                   $this->db->group_end(); // close bracket
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
    
     public function get_single_record_by_warehouse_product_id($warehouse_product_id)
     {
       $this->db->select('p.*');
       $this->db->from('scrap_products p');
       $this->db->where('p.warehouse_product_id',$warehouse_product_id);
       $query = $this->db->get();
       return $query->row(); 
     }
    

    
}
?>
