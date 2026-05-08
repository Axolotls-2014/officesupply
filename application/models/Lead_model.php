<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_model extends CI_Model {

    var $table = 'lead';
    var $column_order = array('lead_name','gstin','email','phone','country_name','state_name','address','city_name');
    var $column_search = array('lead_name','gstin','email','phone','country_name','state_name','address','city_name');
    var $order = array('id' => 'desc');


    function __construct() {
        parent::__construct();
    }

    public function get_records($customer_id_array = null)
    {
      $this->db->select('
                          c.*, 
                          cs.name as country_name, 
                          css.name as state_name, 
                          ccs.name as city_name
                        ');
      $this->db->from('lead c');
      $this->db->join('countries cs', 'cs.id = c.country_id', 'LEFT');
      $this->db->join('states css', 'css.id = c.state_id', 'LEFT');
      $this->db->join('cities ccs', 'ccs.id = c.city_id', 'LEFT');
      if($customer_id_array != null)
        $this->db->where_in('c.id',$customer_id_array);
  
      $this->db->group_by('c.id');
      $this->db->order_by('c.id', 'DESC'); // This will order the results by id in descending order
      $query = $this->db->get();
      return $query->result();
    }

    public function add_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
         if($this->db->insert('lead',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

     public function edit_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('lead',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_single_record($customer_id)
    {
        return $this->db->select('c.*,
                                    cs.name as country_name,
                                    css.name as state_name, 
                                    ccs.name as city_name,
                                  
                                ')
                         ->from('lead c')
                         ->join('countries cs','cs.id = c.country_id','LEFT')
                         ->join('states css','css.id = c.state_id','LEFT')
                         ->join('cities ccs','ccs.id = c.city_id','LEFT')
                         ->where('c.delete_status',0)
                         ->where('c.id',$customer_id)
                         ->get()
                         ->row();
    }

    public function get_single_record_by_ledger_id($id)
    {
        return $this->db->select('  
                                    c.*,
                                    cs.name as country_name,
                                    css.name as state_name, 
                                    ccs.name as city_name,
                                    css.state_code as state_code
                                ')
                         ->from('customer c')
                        
                         ->join('ledger l','l.id = c.ledger_id','LEFT')
                         ->join('countries cs','cs.id = c.country_id','LEFT')
                         ->join('states css','css.id = c.state_id','LEFT')
                         ->join('cities ccs','ccs.id = c.city_id','LEFT')
                         ->where('c.delete_status',0)
                         ->where('c.ledger_id',$id)
                         ->get()
                         ->row();
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    /*********************************** End Dynamic Datatable function *****************************************/


    public function is_loggedin_user_is_customer($user_id = null)
		{
			// $user_id = ($user_id == null) ? $this->session->userdata('user_id') : $user_id;
			// $user = $this->ion_auth->user($user_id)->row()->email; 
			// $customer = $this->utility_model->get_records_by_field('customer','email',$user,$row = true,$check_delete_status = false);
			if ($this->ion_auth->in_group(CUSTOMER_GROUP_NAME))
        return true;
			else 
				return false;
			// if($customer != null)
			// 	return true;
			// else 
			// 	return false;
		}
   
   
       public function get_task_by_lead_id($id)
    {
        return $this->db->select('*')
                         ->from('lead_tasks')
                         ->where('lead_id',$id)
                         ->order_by('task_id', 'DESC')
                         ->get()
                         ->result();
    }

       public function get_reminder_by_lead_id($id)
    {
        return $this->db->select('*')
                         ->from('customer_reminders')
                         ->where('customer_id',$id)
                         ->order_by('reminder_id', 'DESC')
                         ->get()
                         ->result();
    }
    
    //       public function get_followups_by_lead_id($id)
    // {
    //     return $this->db->select('*')
    //                      ->from('lead_followups')
    //                      ->where('lead_id',$id)
    //                      ->order_by('id', 'DESC')
    //                      ->get()
    //                      ->result();
    // }
    
    public function get_followups_by_lead_id($id)
{
    return $this->db->select('lead_followups.*, hr_employees.first_name, hr_employees.last_name')
                    ->from('lead_followups')
                    ->join('hr_employees', 'hr_employees.employee_id = lead_followups.created_by', 'left')
                    ->where('lead_followups.lead_id', $id)
                    ->order_by('lead_followups.id', 'DESC')
                    ->get()
                    ->result();
}

    
    
    //     public function get_feedback()
    // {
    //     return $this->db->select('*')
    //                      ->from('FeedbackForm')
    //                      ->order_by('id', 'DESC')
    //                      ->get()
    //                      ->result();
    // }
    
    

    public function get_feedback($customer_id_array = null)
{
    // Start building the query
    $this->db->select('f.*, 
                       cs.first_name as receptionist_name,
                       css.first_name as salesman_name')
             ->from('FeedbackForm f')
             ->join('hr_employees cs', 'cs.employee_id = f.receptionist', 'LEFT')
             ->join('hr_employees css', 'css.employee_id = f.salesman', 'LEFT');
    
    // Add condition for customer_id_array if provided
    if ($customer_id_array != null) {
        $this->db->where_in('f.id', $customer_id_array);
    }
    
    // Group and order results
    $this->db->group_by('f.id');
    $this->db->order_by('f.id', 'DESC'); // Order results by id in descending order
    
    // Execute the query
    $query = $this->db->get();
    
    // Return the results
    return $query->result();
}


    
    
        public function add_feedback($data)
    {

         if($this->db->insert('FeedbackForm',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }
    
    
        public function get_single_record_feedback($customer_id)
    {
        return $this->db->select('f.*,
                                    cs.first_name as receptionist_name,
                                    css.first_name as salesman_name, 

                                ')
                         ->from('FeedbackForm f')
                         ->join('hr_employees cs','cs.employee_id = f.receptionist','LEFT')
                         ->join('hr_employees css','css.employee_id = f.salesman','LEFT')
                         ->where('f.id',$customer_id)
                         ->get()
                         ->row();
    }
    
         public function edit_recordfeedback($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('FeedbackForm',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


}
?>
