<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_category_model extends CI_Model {

  var $table = 'product_category_view';
  var $column_order = array(
                            "id",
                            "name",
                            "description",
                            "tax_id",
                            "tax_type",
                            "delete_status",
                            "created_date",
                            
                          ); //set column field database for datatable orderable
  var $column_search = array(
                            "id",
                            "name",
                            "description",
                            "tax_id",
                            "tax_type",
                            "delete_status",
                            "created_date",
                            
                          ); //set column field database for datatable searchable 
  var $order = array('id' => 'desc'); // default order 

  function __construct() {
      parent::__construct();
  }

  public function get_records()
  {
        return $this->db->select('p.*,t.tax_name as tax_name')
                         ->from('product_category p')
                         ->join('tax t','t.id = p.tax_id','LEFT')
                         ->where('p.delete_status',0)
                         ->get()
                         ->result();
  }

  public function get_records_by_tax_id($tax_id)
  {
      return $this->db->select('p.*,t.tax_name as tax_name')
                       ->from('product_category p')
                       ->join('tax t','t.id = p.tax_id','LEFT')
                       ->where('t.id',$tax_id)
                       ->where('p.delete_status',0)
                       ->get()
                       ->result();
  }

  public function add_record($data)
  {
    $data['created_date'] = date('Y-m-d H:i:s');
    
      if($this->db->insert('product_category',$data)){
          return  $this->db->insert_id();
      }
      else{
          return FALSE;
      }
  }

  public function edit_record($data,$id)
  {   
      $this->db->where('id',$id);
      if($this->db->update('product_category',$data)){
          return true;
      }
      else{
          return false;
      }
  }

  public function edit_record_by_tax_id($data,$tax_id)
  {   
      $this->db->where('tax_id',$tax_id);
      if($this->db->update('product_category',$data)){
          return true;
      }
      else{
          return false;
      }
  }

  public function delete_record($data,$id)
  {
      $this->db->where('id',$id);

      if($this->db->delete('product_category'))
      {
          return true;
      }
      else
      {
          return false;
      }
  }

  public function get_single_record($product_category_id)
  {
      return $this->db->get_where('product_category', array("id" => $product_category_id))->row();
  }

  public function get_lastest_sequence_number()
  {
    $this->db->select('*');
    $this->db->from('product_category');
    // $this->db->where('delete_status',0);

    $query = $this->db->get();
    $data = $query->result();

    return (PCID_SEQUENCE+sizeof($data)+1);
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
    $this->db->where('delete_status',0);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered()
  {
    $this->_get_datatables_query();
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

  public function export_records()
  {
    return $this->db->query('
                                SELECT 
                                  pc.id as "ID",
                                  pc.name as "ProductCategory",                               
                                  t.igst as "IGST",
                                  t.cgst as "CGST",
                                  t.sgst as "SGST",
                                  pc.pcid as "PCID"
                                FROM 
                                  product_category pc 
                                LEFT JOIN
                                  tax t ON t.id = pc.tax_id
                                WHERE 
                                  pc.delete_status = 0
                                ORDER BY
                                  pc.name ASC
                              ');
  }
}
?>
