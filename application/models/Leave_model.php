<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_model extends CI_model {

  var $table = 'leave_view';
  var $column_order = array( 
                              'leave_id',
                              'employee_name',
                              'leave_date',
                              'leave_type',
                              'status',
                              'created_at',
                              'updated_at',
                              'delete_status'
                             
                          ); //set column field database for datatable orderable
  var $column_search = array(
                              'leave_id',
                              'employee_name',
                              'leave_date',
                              'leave_type',
                              'status',
                              'created_at',
                              'updated_at',
                              'delete_status'
                              
                          ); //set column field database for datatable searchable 
  var $order = array('leave_id' => 'desc'); // default order 

  function __construct() {
    parent::__construct();
  }

  public function get_records()
	{
		$this->db->select('*');
    $this->db->from('hr_leaves');
    $this->db->where('delete_status',NOT_DELETED);

    $query = $this->db->get();
    return $query->result();
	}

  public function add_record($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['created_by']  = $this->session->userdata('user_id');
    if($this->db->insert('hr_leaves',$data)){
      return  $this->db->insert_id();
    }
    else{
      return FALSE;
    }
  }

  public function edit_record($data,$id)
  {
    $data['updated_at'] = date('Y-m-d H:i:s');
    $data['updated_by']   = $this->session->userdata('user_id');

    $this->db->where('leave_id',$id);
    if($this->db->update('hr_leaves',$data)){
      return $id;
    }
    else{
      return FALSE;
    }
  }

  public function get_single_record($leave_id)
  {
    $this->db->select('*');
    $this->db->from('hr_leaves');
    $this->db->where('leave_id',$leave_id);
    
    $query = $this->db->get();
    return $query->row();
    
  }

 
  /************************************ Start Dynamic Datatable function ***********************************/

  private function _get_datatables_query()
  {
      /* $this->db->select*/
    $this->db->from($this->table);

    if($_POST['employee_id'] != '')
    $this->db->where('employee_id',$_POST['employee_id']); 

    if(isset($_POST['status']) && $_POST['status'] == STATUS_PENDING)  
      $this->db->where($this->table.'.status',STATUS_PENDING); 

    if(isset($_POST['status']) && $_POST['status'] == STATUS_APPROVED)  
      $this->db->where($this->table.'.status',STATUS_APPROVED); 

    if(isset($_POST['status']) && $_POST['status'] == STATUS_REJECTED)  
      $this->db->where($this->table.'.status',STATUS_REJECTED); 

      if(isset($_POST['leave_type']) && $_POST['leave_type'] == LEAVE_TYPE_FULL)  
      $this->db->where($this->table.'.leave_type',LEAVE_TYPE_FULL); 

    if(isset($_POST['leave_type']) && $_POST['leave_type'] == LEAVE_TYPE_HALF)  
      $this->db->where($this->table.'.leave_type',LEAVE_TYPE_HALF); 

    if(isset($_POST['leave_type']) && $_POST['leave_type'] == LEAVE_TYPE_QUARTER)  
      $this->db->where($this->table.'.leave_type',LEAVE_TYPE_QUARTER); 

    if(isset($_POST['from_date']) && !empty($_POST['from_date'])) 
    {
      $from_date = date('Y-m-d', strtotime($_POST['from_date']));
      $this->db->where('leave_date >=', $from_date);
    }

    if(isset($_POST['to_date']) && !empty($_POST['to_date'])) 
    {
      $to_date = date('Y-m-d', strtotime($_POST['to_date']));
      $this->db->where('leave_date <=', $to_date);
    }

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
    
    $this->db->where('delete_status',NOT_DELETED);    
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered()
  {
    $this->_get_datatables_query();

    $this->db->where('delete_status',NOT_DELETED);       

    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    $this->db->from($this->table);
    
    $this->db->where('delete_status',NOT_DELETED);       
    return $this->db->count_all_results();
  }

  /*********************************** End Dynamic Datatable function *****************************************/

  public function get_records_by_employee_id($employee_id)
  {
      return $this->db->select('
                                  l .*,
                                  CONCAT(e.first_name, " ", e.last_name) as employee_name
                              ')
                      ->from('hr_leaves l')
                      ->join('hr_employees e','e.employee_id = l.employee_id','left')
                      ->where('l.delete_status',0)
                      ->where('l.employee_id',$employee_id)
                      ->order_by('l.created_at','desc')
                      ->get()
                      ->result();
  }

}
?>
