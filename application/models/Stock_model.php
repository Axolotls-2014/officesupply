<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model {

  var $table = 'stock';
  var $column_order = array(
                            "warehouse_name",
                            "product_name",
                            "quantity",
                            "product_uom",
                            "product_cost",
                            "product_price",
                            "entry_type",
                            "id",
                            "warehouse_id",
                            "product_id",
                           
                            "created_date",
                            "user_id",
                            "delete_status",
                            
                            
                          ); //set column field database for datatable orderable
  var $column_search = array(
                            "warehouse_name",
                            "product_name",
                            "quantity",
                            "product_uom",
                            "product_cost",
                            "product_price",
                            "entry_type",
                            "id",
                            "warehouse_id",
                            "product_id",
                           
                            "created_date",
                            "user_id",
                            "delete_status",
                            
                          ); //set column field database for datatable searchable 
  var $order = array('id' => 'desc'); // default order 

  function __construct() {
      parent::__construct();
  }

  public function get_records()
  {
    $this->db->select('*');
    $this->db->from('stock');
    $this->db->where('delete_status',0);

    $query = $this->db->get();
    return $query->result();
  }

  public function add_record($data)
  {
    $data['created_date'] = date('Y-m-d H:i:s');
    
    if($this->db->insert('stock',$data))
      return  $this->db->insert_id();
    else
      return FALSE;
  }

  public function edit_record($data,$id)
  {   
    $this->db->where('id',$id);

    if($this->db->update('stock',$data))
      return true;
    else
      return false;
  }

  public function get_single_record($stock_id)
  {
    $this->db->select('*');
    $this->db->from('stock');
    $this->db->where('id',$stock_id);

    $query = $this->db->get();
    return $query->row();
  }

  
  /************************************ Start Dynamic Datatable function ***********************************/

  private function _get_datatables_query($from_date = null, $to_date = null)
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

    // Date filter
    if($from_date && $to_date){
      $this->db->where('created_date >=', $from_date . ' 00:00:00');
      $this->db->where('created_date <=', $to_date . ' 23:59:59');
    } elseif($from_date){
      $this->db->where('created_date >=', $from_date . ' 00:00:00');
    } elseif($to_date){
      $this->db->where('created_date <=', $to_date . ' 23:59:59');
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

  function get_datatables($from_date = null, $to_date = null)
  {
        $user_id    = $this->session->userdata('user_id');
        $udata      = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
        $this->_get_datatables_query($from_date, $to_date);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        // $this->db->where('delete_status',0); 
        if($udata->branch_id){
            $this->db->where('warehouse_id', $udata->branch_id); 
        }
        $query = $this->db->get();
        return $query->result();
  }

  function count_filtered($from_date = null, $to_date = null)
  {
    $this->_get_datatables_query($from_date, $to_date);
    $this->db->where('delete_status',0);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    $this->db->from($this->table);
    $this->db->where('delete_status',0);
    return $this->db->count_all_results();
  }

  /*********************************** End Dynamic Datatable function *****************************************/
}
?>
