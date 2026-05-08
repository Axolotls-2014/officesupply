<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_model {

  var $table = 'hr_employees';
  var $column_order = array( 
                              'employee_code',
                              'first_name',
                              'last_name',
                              'email',
                              'department_name',
                              'position_title',
                              'hire_date',
                              'salary',
                              'created_at',
                             
                              'delete_status'
                             
                          ); //set column field database for datatable orderable
  var $column_search = array(
                              'employee_code',
                              'first_name',
                              'last_name',
                              'email',
                              'department_name',
                              'position_title',
                              'hire_date',
                              'salary',
                              'created_at',
                            
                              'delete_status'
                              
                          ); //set column field database for datatable searchable 
  var $order = array('employee_id' => 'desc'); // default order 

  function __construct() {
    parent::__construct();
  }

  public function get_records()
	{
		$this->db->select('*');
        $this->db->from('hr_employees');
        // $this->db->where('delete_status',NOT_DELETED);
    
        $query = $this->db->get();
        return $query->result();
	}

  public function add_record($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['created_by']  = $this->session->userdata('user_id');
    if($this->db->insert('hr_employees',$data)){
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

    $this->db->where('employee_id',$id);
    if($this->db->update('hr_employees',$data)){
      return $id;
    }
    else{
      return FALSE;
    }
  }

  // public function delete_record($employee_id,$soft_delete = TRUE)
  // {
  //   $data['plan_delete_status'] = 1;
  //   $data['plan_updated_date']  = date('Y-m-d H:i:s');
  //   $data['plan_updated_by']    = $this->session->userdata('user_id');

  //   if($soft_delete == TRUE)
  //   {
  //     $this->db->where('employee_id',$employee_id);
  //     if($this->db->update(PLAN,$data))
  //       return $employee_id;
  //     else
  //       return FALSE;
  //   }
  //   else
  //   {
  //     $this->db->where('employee_id',$employee_id);
  //     if($this->db->delete(PLAN))
  //       return TRUE;
  //     else
  //       return FALSE;
  //   }
  // }

  public function get_single_record($employee_id)
  {
    $this->db->select('e.*,d.department_name as department_name,p.position_title as position_title');
    $this->db->from('hr_employees e');
    $this->db->join('hr_departments d','d.department_id = e.department_id','LEFT');
    $this->db->join('hr_positions p','p.position_id = e.position_id','LEFT');
    $this->db->where('employee_id',$employee_id);
    
    $query = $this->db->get();
    return $query->row();
    
  }

  public function check_employee_has_entries($employee_id) 
  {
      $tables = ['hr_attendance', 'hr_leaves', 'hr_advance_salaries', 'hr_bonuses', 'hr_deductions', 'hr_tax_deductions'];

      foreach ($tables as $table) {
          $this->db->where('employee_id', $employee_id);
          $this->db->from($table);
          if ($this->db->count_all_results() > 0) {
              return true;
          }
      }
      
      return false;
  }


 
  /************************************ Start Dynamic Datatable function ***********************************/

//   private function _get_datatables_query()
//   {
//       /* $this->db->select*/
//     $this->db->from($this->table);
//     $i = 0;

//     foreach ($this->column_search as $item) // loop column 
//     {
//       if($_POST['search']['value']) // if datatable send POST for search
//       {
//         if($i===0) // first loop
//         {
//           $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
//           $this->db->like($item, $_POST['search']['value']);
//         }
//         else
//         {
//           $this->db->or_like($item, $_POST['search']['value']);
//         }

//         if(count($this->column_search) - 1 == $i) //last loop
//           $this->db->group_end(); //close bracket
//       }
//       $i++;
//     }
     
//     if(isset($_POST['order'])) // here order processing
//     {
//       $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
//     } 
//     else if(isset($this->order))
//     {
//       $order = $this->order;
//       $this->db->order_by(key($order), $order[key($order)]);
//     }
//   }

    private function _get_datatables_query()
    {
        // 1. Select employee fields PLUS the names from joined tables
        $this->db->select('e.*, d.department_name, p.position_title');
        $this->db->from('hr_employees e'); // 'e' is an alias for hr_employees
    
        // 2. Add LEFT JOINs to get Department and Position names
        $this->db->join('hr_departments d', 'd.department_id = e.department_id', 'left');
        $this->db->join('hr_positions p', 'p.position_id = e.position_id', 'left');
    
        $i = 0;
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {
                if($i===0) 
                {
                    $this->db->group_start(); 
                    // Prefix with 'e.' to avoid "ambiguous column" errors
                    $this->db->like('e.'.$item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like('e.'.$item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

//   function get_datatables()
//   {
//     $this->_get_datatables_query();
//     if($_POST['length'] != -1)
//     $this->db->limit($_POST['length'], $_POST['start']);
    
//     $this->db->where('delete_status',NOT_DELETED);    
//     $query = $this->db->get();
//     return $query->result();
//   }

//   function count_filtered()
//   {
//     $this->_get_datatables_query();

//     $this->db->where('delete_status',NOT_DELETED);       

//     $query = $this->db->get();
//     return $query->num_rows();
//   }

//   public function count_all()
//   {
//     $this->db->from($this->table);
    
//     $this->db->where('delete_status',NOT_DELETED);       
//     return $this->db->count_all_results();
//   }

 function get_datatables()
  {
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    
    // FIX: Explicitly use e. to avoid Ambiguous column error
    $this->db->where('e.delete_status', NOT_DELETED);    
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered()
  {
    $this->_get_datatables_query();
    // FIX: Explicitly use e.
    $this->db->where('e.delete_status', NOT_DELETED);       
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    $this->db->from($this->table);
    // FIX: Even in count_all, specify table to be safe
    $this->db->where('delete_status', NOT_DELETED);       
    return $this->db->count_all_results();
  }
  /*********************************** End Dynamic Datatable function *****************************************/

  public function get_lastest_sequence_number()
  {
    $this->db->select('*');
    $this->db->from('hr_employees');
    // $this->db->where('delete_status',0);

    $query = $this->db->get();
    $data = $query->result();

    // Retrieve the date format from session
    $dateformat = csession('employee_date_format');  // This is expected to be a valid date format string

    // Validate the date format and format the date
    $testDate = date_create_from_format($dateformat, '2024-01-01'); // Use any valid date for testing
    if ($testDate === false) {
        $date = ''; // If the date format is invalid, do not include a date
    } else {
        $date = date($dateformat);
    }

    // Constructing the sequence number
    $sequenceNumber     = csession('employee_sequence') + count($data) + 1;
    $formattedSequence  = csession('employee_prefix') . csession('seperator') . $date . csession('seperator') . $sequenceNumber;

    return $formattedSequence;
  }

  public function get_employees_by_ids($employee_ids)
  {
      if (empty($employee_ids)) {
          return [];
      }

      $this->db->where_in('employee_id', $employee_ids);
      $query = $this->db->get('hr_employees'); // Replace 'employees' with your employee table name
      return $query->result();
  }

}
?>
