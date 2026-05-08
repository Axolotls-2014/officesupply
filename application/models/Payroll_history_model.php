<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_history_model extends CI_model {

  
  function __construct() {
    parent::__construct();
  }

 

  public function add_record($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['created_by']  = $this->session->userdata('user_id');
    if($this->db->insert('hr_payroll_history',$data)){
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

    $this->db->where('payroll_history_id',$id);
    if($this->db->update('hr_payroll_history',$data)){
      return $id;
    }
    else{
      return FALSE;
    }
  }
 
  
  public function get_single_record($payroll_history_id)
  {
    $this->db->select('*');
    $this->db->from('hr_payroll_history');
    $this->db->where('payroll_history_id',$payroll_history_id);
    
    $query = $this->db->get();
    return $query->row();
  }

  public function get_single_record_by_employee_id($employee_id)
  {
    $this->db->select('*');
    $this->db->from('hr_payroll_history');
    $this->db->where('employee_id',$employee_id);
    
    $query = $this->db->get();
    return $query->row();
  }

  public function get_employee_payroll_status($employee_id, $payroll_month) 
  {
    $this->db->select('payroll_history_id');
    $this->db->from('hr_payroll_history');
    $this->db->where('employee_id', $employee_id);
    $this->db->where('payroll_month', $payroll_month);
    $query = $this->db->get();
    
    if ($query->num_rows() > 0) {
        return true; // Record exists
    } else {
        return false; // Record does not exist
    }
  }

  public function get_employee_date_month_payroll_status($payroll_month) 
  {
    $this->db->select('payroll_history_id');
    $this->db->from('hr_payroll_history');
    $this->db->where('payroll_month', $payroll_month);
    $query = $this->db->get();
    
    if ($query->num_rows() > 0) {
        return true; // Record exists
    } else {
        return false; // Record does not exist
    }
  }

  

  
  /************************************ Start Dynamic Datatable function ***********************************/

 

}
?>
