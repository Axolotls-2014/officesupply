<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday_model extends CI_model {

  var $table = 'hr_holidays';
  var $column_order = array( 
                              'holiday_date',
                              'holiday_name',
                              'created_at',
                              'updated_at',
                              'delete_status'
                             
                          ); //set column field database for datatable orderable
  var $column_search = array(
                              'holiday_date',
                              'holiday_name',
                              'created_at',
                              'updated_at',
                              'delete_status'
                              
                          ); //set column field database for datatable searchable 
  var $order = array('holiday_id' => 'desc'); // default order 

  function __construct() {
    parent::__construct();
  }

  public function get_records()
	{
		$this->db->select('*');
    $this->db->from('hr_holidays');
    $this->db->where('delete_status',NOT_DELETED);

    $query = $this->db->get();
    return $query->result();
	}

  public function add_record($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['created_by']  = $this->session->userdata('user_id');
    if($this->db->insert('hr_holidays',$data)){
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

    $this->db->where('holiday_id',$id);
    if($this->db->update('hr_holidays',$data)){
      return $id;
    }
    else{
      return FALSE;
    }
  }

  // public function delete_record($attendance_id,$soft_delete = TRUE)
  // {
  //   $data['plan_delete_status'] = 1;
  //   $data['plan_updated_date']  = date('Y-m-d H:i:s');
  //   $data['plan_updated_by']    = $this->session->userdata('user_id');

  //   if($soft_delete == TRUE)
  //   {
  //     $this->db->where('attendance_id',$attendance_id);
  //     if($this->db->update(PLAN,$data))
  //       return $attendance_id;
  //     else
  //       return FALSE;
  //   }
  //   else
  //   {
  //     $this->db->where('attendance_id',$attendance_id);
  //     if($this->db->delete(PLAN))
  //       return TRUE;
  //     else
  //       return FALSE;
  //   }
  // }

  public function get_single_record($holiday_id)
  {
    $this->db->select('*');
    $this->db->from('hr_holidays');
    $this->db->where('holiday_id',$holiday_id);
    
    $query = $this->db->get();
    return $query->row();
    
  }

 
  /************************************ Start Dynamic Datatable function ***********************************/

  private function _get_datatables_query()
  {
      /* $this->db->select*/
    $this->db->from($this->table);

    if (isset($_POST['month']) && $_POST['month'] !== '')
      $this->db->where('MONTH(holiday_date)', $_POST['month']);

    if (isset($_POST['year']) && $_POST['year'] !== '')
      $this->db->where('YEAR(holiday_date)', $_POST['year']);

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

}
?>
