<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_model extends CI_Model {

  var $table = 'warehouse';
  var $column_order = array(
                            "id",
                            "code",
                            "name",
                            "description",
                            "is_default",
                            "delete_status",
                            "created_date",
                          ); //set column field database for datatable orderable
  var $column_search = array(
                            "id",
                            "code",
                            "name",
                            "description",
                            "is_default",
                            "delete_status",
                            "created_date",
                          ); //set column field database for datatable searchable 
  var $order = array('id' => 'desc'); // default order 

  function __construct() {
      parent::__construct();
  }

  public function get_records()
  {
    $user_id    = $this->session->userdata('user_id');
    $udata      = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
    $this->db->select('*');
    $this->db->from('warehouse');
    $this->db->where('delete_status',0);
    if($udata->branch_id){
        $this->db->where('id', $udata->branch_id); 
    }
    $query = $this->db->get();
    return $query->result();
  }
    public function get_records_head()
    {
    $user_id    = $this->session->userdata('user_id');
    $udata      = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
    $this->db->select('*');
    $this->db->from('warehouse');
    $this->db->where('delete_status',0);
    $this->db->where('is_head_office','yes');
    if($udata->branch_id){
        $this->db->where('id', $udata->branch_id); 
    }
    $query = $this->db->get();
    return $query->result(); 
    }
  public function add_record($data)
  {
    $data['created_date'] = date('Y-m-d H:i:s');
    
    if($this->db->insert('warehouse',$data))
      return  $this->db->insert_id();
    else
      return FALSE;
  }

  public function edit_record($data,$id)
  {   
    $this->db->where('id',$id);

    if($this->db->update('warehouse',$data))
      return true;
    else
      return false;
  }

  public function get_single_record($warehouse_id)
  {
    $this->db->select('
                        w.*,
                        cntry.name as country_name, 
                        stte.name as state_name, 
                        cites.name as city_name, 
                      ');
    $this->db->from('warehouse w');
    $this->db->join('countries cntry','cntry.id = w.country_id','left');
    $this->db->join('states stte','stte.id = w.state_id','left');
    $this->db->join('cities cites','cites.id = w.city_id','left');
    $this->db->where('w.id',$warehouse_id);

    $query = $this->db->get();
    return $query->row();
  }

  public function remove_default()
  {
    $this->db->update('warehouse',array('is_default' => WAREHOUSE_IS_DEFAULT_NO));
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

  public function get_record_by_is_default()
  {
    $this->db->select('*');
    $this->db->from('warehouse');
    $this->db->where('is_default','yes');

    $query = $this->db->get();
    return $query->row();
  }
}
?>
