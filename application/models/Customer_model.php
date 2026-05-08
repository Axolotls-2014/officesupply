<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    var $table = 'customer';
    var $column_order = array('customer_name','gstin','email','phone','country_name','state_name','address','city_name'); //set column field database for datatable orderable
    var $column_search = array('customer_name','gstin','email','phone','country_name','state_name','address','city_name'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc');  


    function __construct() {
        parent::__construct();
        
    }

   public function get_records($customer_id_array = null)
   {
    $user_id = $this->session->userdata('user_id');
    $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();

    $this->db->select('
                        c.*,
                         
                        cs.name as country_name, 
                        css.name as state_name, 
                        ccs.name as city_name, 
                        scs.name as shipping_country_name, 
                        scss.name as shipping_state_name, 
                        sccs.name as shipping_city_name, 
                        l.closing_balance as closing_balance, 
                        COALESCE(SUM(p.total), 0) AS total_sale_amount
                      ');
    $this->db->from('customer c');
    $this->db->join('countries cs', 'cs.id = c.country_id', 'LEFT');
    $this->db->join('states css', 'css.id = c.state_id', 'LEFT');
    $this->db->join('cities ccs', 'ccs.id = c.city_id', 'LEFT');
    $this->db->join('countries scs', 'scs.id = c.shipping_country_id', 'LEFT');
    $this->db->join('states scss', 'scss.id = c.shipping_state_id', 'LEFT');
    $this->db->join('cities sccs', 'sccs.id = c.shipping_city_id', 'LEFT');
    $this->db->join('ledger l', 'l.id = c.ledger_id', 'LEFT');
    $this->db->join('sale p', 'p.customer_id = c.id AND p.delete_status = 0', 'LEFT'); 

    if ($customer_id_array != null) {
        $this->db->where_in('c.id', $customer_id_array);
    }

   $this->db->where('c.delete_status', 0);


    if (isset($udata->branch_id) && $udata->branch_id) {
        $this->db->where('c.branch_id', $udata->branch_id);
    }

    $this->db->group_by('c.id');
    $query = $this->db->get();
    return $query->result();
}


    public function add_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
         if($this->db->insert('customer',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

     public function edit_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('customer',$data))
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
        return $this->db->select('  
                                    c.*,
                                    cs.name as country_name,
                                    css.name as state_name, 
                                    ccs.name as city_name,
                                    scs.name as shipping_country_name,
                                    scss.name as shipping_state_name, 
                                    sccs.name as shipping_city_name, 
                                    l.closing_balance as closing_balance,
                                    scss.state_code as shipping_state_code
                                ')
                         ->from('customer c')
                         ->join('countries cs','cs.id = c.country_id','LEFT')
                         ->join('states css','css.id = c.state_id','LEFT')
                         ->join('cities ccs','ccs.id = c.city_id','LEFT')
                         ->join('countries scs','scs.id = c.shipping_country_id','LEFT')
                         ->join('states scss','scss.id = c.shipping_state_id','LEFT')
                         ->join('cities sccs','sccs.id = c.shipping_city_id','LEFT')
                         ->join('ledger l','l.id = c.ledger_id','LEFT')
                         ->where('c.delete_status', 0)
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
                         ->where('c.delete_status', 0)
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
		
        public function add_shipping_address($data) {
            $data['created_date'] = date('Y-m-d H:i:s');
            return $this->db->insert('customer_shipping_addresses', $data);
        }
        
        public function update_shipping_address($id, $data)
        {
            $this->db->where('id', $id);
            return $this->db->update('customer_shipping_addresses', $data);
        }        
        
        public function get_shipping_addresses($customer_id) {

            $this->db->select('sa.*, c.name as country_name, s.name as state_name, ci.name as city_name');
            $this->db->from('customer_shipping_addresses sa');
            $this->db->join('countries c', 'c.id = sa.shipping_country_id', 'left');
            $this->db->join('states s', 's.id = sa.shipping_state_id', 'left');
            $this->db->join('cities ci', 'ci.id = sa.shipping_city_id', 'left');
            $this->db->where('sa.customer_id', $customer_id);
            $this->db->order_by('sa.is_default', 'DESC');

             return $this->db->get()->result();
        }
        public function get_customer_shipping_addresses($customer_id)
{
    $this->db->where('customer_id', $customer_id);
    return $this->db->get('customer_shipping_addresses')->result();
}
        
        public function get_default_shipping_address($customer_id) {
            $this->db->where('customer_id', $customer_id);
            $this->db->where('is_default', 1);
            return $this->db->get('customer_shipping_addresses')->row();
        }
		public function get_shipping_address_by_id($id)
{
    $this->db->select('sa.*, c.name as country_name, s.name as state_name, ci.name as city_name');
    $this->db->from('customer_shipping_addresses sa');
    $this->db->join('countries c', 'c.id = sa.shipping_country_id', 'left');
    $this->db->join('states s', 's.id = sa.shipping_state_id', 'left');
    $this->db->join('cities ci', 'ci.id = sa.shipping_city_id', 'left');
    $this->db->where('sa.id', $id);
    return $this->db->get()->row();
}
 
 
 
 
 
 
 
 public function get_billing_details($customer_id) {
    $this->db->select('customer_name, gstin, email, phone, address, pincode, 
                      cs.name as country_name, st.name as state_name, ct.name as city_name');
    $this->db->from('customer c');
    $this->db->join('countries cs', 'cs.id = c.country_id', 'left');
    $this->db->join('states st', 'st.id = c.state_id', 'left');
    $this->db->join('cities ct', 'ct.id = c.city_id', 'left');
    $this->db->where('c.id', $customer_id);
    return $this->db->get()->row();
}

public function get_shipping_details($customer_id) {
    // Get default shipping address
    $default = $this->get_default_shipping_address($customer_id);
    
    if ($default) {
        $this->db->select('sa.*, c.name as country_name, s.name as state_name, ci.name as city_name');
        $this->db->from('customer_shipping_addresses sa');
        $this->db->join('countries c', 'c.id = sa.shipping_country_id', 'left');
        $this->db->join('states s', 's.id = sa.shipping_state_id', 'left');
        $this->db->join('cities ci', 'ci.id = sa.shipping_city_id', 'left');
        $this->db->where('sa.id', $default->id);
        return $this->db->get()->row();
    }
    
    return null;
}

public function get_existing_shipping_addresses($customer_id) {
    $this->db->select('sa.*, c.name as country_name, s.name as state_name, ci.name as city_name');
    $this->db->from('customer_shipping_addresses sa');
    $this->db->join('countries c', 'c.id = sa.shipping_country_id', 'left');
    $this->db->join('states s', 's.id = sa.shipping_state_id', 'left');
    $this->db->join('cities ci', 'ci.id = sa.shipping_city_id', 'left');
    $this->db->where('sa.customer_id', $customer_id);
    $this->db->order_by('sa.is_default', 'DESC');
    return $this->db->get()->result();
}

public function set_default_shipping_address($address_id, $customer_id) {
    // First reset all defaults for this customer
    $this->db->where('customer_id', $customer_id);
    $this->db->update('customer_shipping_addresses', array('is_default' => 0));
    
    // Then set the selected one as default
    $this->db->where('id', $address_id);
    return $this->db->update('customer_shipping_addresses', array('is_default' => 1));
}

public function select_shipping_address($address_id, $customer_id) {
    // This method would update the sale record with the selected shipping address
    // Implementation depends on your database structure
    return array('success' => true);
}
  
  public function update_wallet_balance($customer_id, $amount) {
    $this->db->set('wallet_balance', 'wallet_balance + ' . (float)$amount, false);
    $this->db->where('id', $customer_id);
    return $this->db->update('customer');
} 

}
?>
