<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_model extends CI_model {

  var $table = 'payroll_view';
  var $column_order = array( 
                              'employee_id',
                              'employee_name',
                              'base_salary',
                              'total_tax',
                              'total_bonuses',
                              'total_deductions',
                             
                              'total_advance',
                              'total_leaves',
                              'leave_deduction_amount',
                              'total_leaves',
                              'net_salary',
                              'payroll_month'
                             
                          ); //set column field database for datatable orderable
  var $column_search = array(
                              'employee_id',
                              'employee_name',
                              'base_salary',
                              'total_tax',
                              'total_bonuses',
                              'total_deductions',
                             
                              'total_advance',
                              'total_leaves',
                              'leave_deduction_amount',
                              'total_leaves',
                              'net_salary',
                              'payroll_month'
                              
                          ); //set column field database for datatable searchable 
  var $order = array('employee_id' => 'desc'); // default order 

  function __construct() {
    parent::__construct();
  }

  public function get_records()
	{
		$this->db->select('*');
    $this->db->from('payroll_view');
    // $this->db->where('delete_status',NOT_DELETED);

    $query = $this->db->get();
    return $query->result();
	}

  // public function add_record($data)
  // {
  //   // $data['plan_created_by']  = $this->session->userdata('user_id');
  //   if($this->db->insert('payrolls',$data)){
  //     return  $this->db->insert_id();
  //   }
  //   else{
  //     return FALSE;
  //   }
  // }

  // public function edit_record($data,$id)
  // {
  //   $data['updated_at'] = date('Y-m-d H:i:s');
    

  //   $this->db->where('payroll_id',$id);
  //   if($this->db->update('payrolls',$data)){
  //     return $id;
  //   }
  //   else{
  //     return FALSE;
  //   }
  // }

  
  public function get_single_record($employee_id)
  {
    $this->db->select('*');
    $this->db->from('payroll_view');
    $this->db->where('employee_id',$employee_id);
    
    $query = $this->db->get();
    return $query->row();
  }

  // public function update_net_amount() {
  //   // Subquery to get total deductions per payroll
  //   $sql = "
  //             UPDATE payrolls p
  //             JOIN (
  //                 SELECT 
  //                     payroll_id, 
  //                     COALESCE(SUM(amount), 0) AS total_deductions
  //                 FROM 
  //                     deductions
  //                 GROUP BY 
  //                     payroll_id
  //             ) d ON p.payroll_id = d.payroll_id
  //             SET 
  //                 p.net_amount = p.net_amount - d.total_deductions
  //           ";

  //   $this->db->query($sql);
  // }

 
  /************************************ Start Dynamic Datatable function ***********************************/

  private function _get_datatables_query()
  {
      /* $this->db->select*/
    $this->db->from($this->table);

    if($_POST['employee_id'] != '')
      $this->db->where('employee_id',$_POST['employee_id']); 

      if (!empty($_POST['month'])) {
        $this->db->where('SUBSTRING(payroll_month, 6, 2) =', str_pad($_POST['month'], 2, '0', STR_PAD_LEFT));
      }

      if (!empty($_POST['year'])) {
          $this->db->where('SUBSTRING(payroll_month, 1, 4) =', $_POST['year']);
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
    
    //$this->db->where('delete_status',NOT_DELETED);    
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered()
  {
    $this->_get_datatables_query();

   // $this->db->where('delete_status',NOT_DELETED);       

    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    $this->db->from($this->table);
    
    // $this->db->where('delete_status',NOT_DELETED);       
    return $this->db->count_all_results();
  }

  /*********************************** End Dynamic Datatable function *****************************************/

  public function get_records_by_employee_id($employee_id)
  {
      return $this->db->select('
                                  p .*,
                                   CONCAT(e.first_name, " ", e.last_name) as employee_name
                              ')
                      ->from('payroll_view p')
                      ->join('hr_employees e','e.employee_id = p.employee_id','left')
                     
                      ->where('p.employee_id',$employee_id)
                    
                      ->get()
                      ->result();
  }

  public function payroll_exists($employee_id, $month) 
  {
    $this->db->where('employee_id', $employee_id);
    $this->db->where('payroll_month', $month);
    $query = $this->db->get('hr_payroll_history');
    return $query->num_rows() > 0;
  }


}
?>
