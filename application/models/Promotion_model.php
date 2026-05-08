<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion_model extends CI_Model {

  var $table = 'promotion';
  var $view = 'promotion_view';
  var $column_order = array( 
                              'customer_name',
                              'product_name',
                              'promotion_type',
                              'percentage',
                              'configuration',
                              'created_date',
                              'updated_date',
                              'delete_status',
                              'promotion_id',
                              'product_id'
                          ); //set column field database for datatable orderable
  var $column_search = array(
                              'customer_name',
                              'product_name',
                              'promotion_type',
                              'percentage',
                              'configuration',
                              'created_date',
                              'updated_date',
                              'delete_status',
                              'promotion_id',
                              'product_id'
                          ); //set column field database for datatable searchable 
  var $order = array('promotion_id' => 'desc'); // default order 

  function __construct() {
    parent::__construct();
  }

  public function get_records()
  {
    $this->db->select('*');
    $this->db->from('promotion');
    $this->db->where('delete_status',NOT_DELETED);

    $query = $this->db->get();
    return $query->result();
  }

  public function add_record($data)
  {
    $data['created_by']  = $this->session->userdata('user_id');
    if($this->db->insert("promotion",$data)){
      return  $this->db->insert_id();
    }
    else{
      return FALSE;
    }
  }

  public function edit_record($data,$id)
  {
    $data['updated_date'] = date('Y-m-d H:i:s');
    $data['updated_by']   = $this->session->userdata('user_id');

    $this->db->where('promotion_id',$id);
    if($this->db->update("promotion",$data)){
      return $id;
    }
    else{
      return FALSE;
    }
  }

  public function delete_record($promotion_id,$soft_delete = TRUE)
  {
    $data['delete_status'] = DELETED;
    $data['updated_date']  = date('Y-m-d H:i:s');
    $data['updated_by']    = $this->session->userdata('user_id');

    if($soft_delete == TRUE)
    {
      $this->db->where('promotion_id',$promotion_id);
      if($this->db->update("promotion",$data))
        return $promotion_id;
      else
        return FALSE;
    }
    else
    {
      $this->db->where('promotion_id',$promotion_id);
      if($this->db->delete("promotion"))
        return TRUE;
      else
        return FALSE;
    }
  }

  public function get_single_record($promotion_id)
  {
    $this->db->select('*');
    $this->db->from("promotion");
    $this->db->where('promotion_id',$promotion_id);

    $query = $this->db->get();
    return $query->row();
    
  }

  /************************************ Start Dynamic Datatable function ***********************************/

  private function _get_datatables_query()
  {
    $this->db->select('*');
    $this->db->from($this->view);
    $this->db->where('delete_status',NOT_DELETED);  
      

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

    // if($_POST['search']['value'] != ''){
    //   $this->db->or_like('stockiest.first_name', $_POST['search']['value']);
    //   $this->db->or_like('stockiest.last_name', $_POST['search']['value']);
    // }
     
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
}
?>