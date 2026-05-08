<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position_model extends CI_model {

  var $table = 'hr_positions';
  var $column_order = array( 
                              'position_code',
                              'position_title',
                              'created_at',
                              'updated_at',
                              'delete_status'
                             
                          ); //set column field database for datatable orderable
  var $column_search = array(
                              'position_code',
                              'position_title',
                              'created_at',
                              'updated_at',
                              'delete_status'
                              
                          ); //set column field database for datatable searchable 
  var $order = array('position_id' => 'desc'); // default order 

  function __construct() {
    parent::__construct();
  }

  public function get_records()
	{
		$this->db->select('*');
    $this->db->from('hr_positions');
    $this->db->where('delete_status',NOT_DELETED);

    $query = $this->db->get();
    return $query->result();
	}

  public function add_record($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['created_by']  = $this->session->userdata('user_id');
    if($this->db->insert('hr_positions',$data)){
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

    $this->db->where('position_id',$id);
    if($this->db->update('hr_positions',$data)){
      return $id;
    }
    else{
      return FALSE;
    }
  }

  // public function delete_record($position_id,$soft_delete = TRUE)
  // {
  //   $data['plan_delete_status'] = 1;
  //   $data['plan_updated_date']  = date('Y-m-d H:i:s');
  //   $data['plan_updated_by']    = $this->session->userdata('user_id');

  //   if($soft_delete == TRUE)
  //   {
  //     $this->db->where('position_id',$position_id);
  //     if($this->db->update(PLAN,$data))
  //       return $position_id;
  //     else
  //       return FALSE;
  //   }
  //   else
  //   {
  //     $this->db->where('position_id',$position_id);
  //     if($this->db->delete(PLAN))
  //       return TRUE;
  //     else
  //       return FALSE;
  //   }
  // }

  public function get_single_record($position_id)
  {
    $this->db->select('*');
    $this->db->from('hr_positions');
    $this->db->where('position_id',$position_id);
    
    $query = $this->db->get();
    return $query->row();
    
  }

  public function check_employee_exists($position_id) 
  {
    $this->db->where('position_id', $position_id);
    $this->db->from('hr_employees');
    return $this->db->count_all_results() > 0;
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

  public function get_lastest_sequence_number()
  {
    $this->db->select('*');
    $this->db->from('hr_positions');
    // $this->db->where('delete_status',0);

    $query = $this->db->get();
    $data = $query->result();

    // Retrieve the date format from session
    $dateformat = csession('position_date_format');  // This is expected to be a valid date format string

    // Validate the date format and format the date
    $testDate = date_create_from_format($dateformat, '2024-01-01'); // Use any valid date for testing
    if ($testDate === false) {
        $date = ''; // If the date format is invalid, do not include a date
    } else {
        $date = date($dateformat);
    }

    // Constructing the sequence number
    $sequenceNumber = csession('position_sequence') + count($data) + 1;
    $formattedSequence = csession('position_prefix') . csession('seperator') . $date . csession('seperator') . $sequenceNumber;

    return $formattedSequence;
  }

  public function export_records()
  {
    return $this->db->query('
                                SELECT 
                                  p.position_code as "PositionCode",
                                  p.position_title as "PositionTitle"                             
                                
                                FROM 
                                  hr_positions p 
                                
                                WHERE 
                                  p.delete_status = 0
                                ORDER BY
                                  p.position_title ASC
                              ');
  }

}
?>
