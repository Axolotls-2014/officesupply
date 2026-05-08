<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uom_model extends CI_Model {

  var $table = 'uom';
  var $column_order = array(
                            "id",
                            "uom",
                            "name",
                            "delete_status",
                            
                          ); //set column field database for datatable orderable
  var $column_search = array(
                            "id",
                            "uom",
                            "name",
                            "delete_status",
                            
                          ); //set column field database for datatable searchable 
  var $order = array('id' => 'desc'); // default order 

  function __construct() {
      parent::__construct();
  }

  public function get_records()
  {
    $this->db->select('*');
    $this->db->from('uom');
    $this->db->where('delete_status',0);

    $query = $this->db->get();
    return $query->result();
  }

  public function add_record($data)
  {
    if($this->db->insert('uom',$data))
      return  $this->db->insert_id();
    else
      return FALSE;
  }

  public function edit_record($data,$id)
  {   
    $this->db->where('id',$id);

    if($this->db->update('uom',$data))
      return true;
    else
      return false;
  }

  public function get_single_record($uom_id)
  {
    $this->db->select('*');
    $this->db->from('uom');
    $this->db->where('id',$uom_id);

    $query = $this->db->get();
    return $query->row();
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
                                  u.uom as "UOM",
                                  u.name as "UOM NAME"
                                FROM 
                                  uom u 
                                ORDER BY
                                  u.name ASC
                              ');
  }
}
?>
