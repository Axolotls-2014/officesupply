<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_Model {

     var $table = 'supplier';
    var $column_order = array('company_name','company_name','gstin','email','phone','contact_person_name','contact_person_designation','website','bank_name','ifsc_code','account_number','gst_registration_type'); //set column field database for datatable orderable
    var $column_search = array('company_name','company_name','gstin','email','phone','contact_person_name','contact_person_designation','website','bank_name','ifsc_code','account_number','gst_registration_type'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 


    function __construct() {
        parent::__construct();
    }


    public function is_loggedin_user_is_supplier($user_id = null)
		{
			// $user_id = ($user_id == null) ? $this->session->userdata('user_id') : $user_id;
			// $user = $this->ion_auth->user($user_id)->row()->email; 
			// $customer = $this->utility_model->get_records_by_field('customer','email',$user,$row = true,$check_delete_status = false);
			if ($this->ion_auth->in_group(SUPPLIER_GROUP_NAME))
        return true;
			else 
				return false;
			// if($customer != null)
			// 	return true;
			// else 
			// 	return false;
		}

	public function get_records()
	{
		return $this->db->select('c.*, cs.name as country_name, css.name as state_name, ccs.name as city_name, l.closing_balance as closing_balance, COALESCE(SUM(p.total), 0) AS total_purchase_amount')
                      ->from('supplier c')
                      ->join('countries cs', 'cs.id = c.country_id', 'LEFT')
                      ->join('states css', 'css.id = c.state_id', 'LEFT')
                      ->join('cities ccs', 'ccs.id = c.city_id', 'LEFT')
                      ->join('ledger l', 'l.id = c.ledger_id', 'LEFT')
                      ->join('purchase p', 'p.supplier_id = c.id AND p.delete_status = 0', 'LEFT') // Added AND condition for delete_status
                      ->where('c.delete_status', 0)
                      ->group_by('c.id')
                      ->get()
                      ->result();
	}
	
	

    public function add_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
         if($this->db->insert('supplier',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

     public function edit_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('supplier',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_single_record($supplier_id)
    {
        return $this->db->select('c.*,cs.name as country_name,css.name as state_name, ccs.name as city_name')
                         ->from('supplier c')
                         ->join('countries cs','cs.id = c.country_id','LEFT')
                         ->join('states css','css.id = c.state_id','LEFT')
                         ->join('cities ccs','ccs.id = c.city_id','LEFT')
                         ->where('c.id',$supplier_id)
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
                         ->from('supplier c')
                        
                         ->join('ledger l','l.id = c.ledger_id','LEFT')
                         ->join('countries cs','cs.id = c.country_id','LEFT')
                         ->join('states css','css.id = c.state_id','LEFT')
                         ->join('cities ccs','ccs.id = c.city_id','LEFT')
                         ->where('c.delete_status',0)
                         ->where('c.ledger_id',$id)
                         ->get()
                         ->row();
    }
    
    
public function get_vendors_by_product($product_id)
{
    return $this->db->select('s.id, s.company_name, s.contact_person_name')
                    ->distinct()  // Use distinct() method instead of including in select
                    ->from('supplier s')
                    ->join('purchase p', 'p.supplier_id = s.id')
                    ->join('purchase_items pi', 'pi.purchase_id = p.id')
                    ->where('pi.product_id', $product_id)
                    ->where('p.delete_status', 0)
                    ->where('s.delete_status', 0)
                    ->order_by('s.company_name', 'ASC')
                    ->get()
                    ->result();
}
    

         /************************************ Start Dynamic Datatable function ***********************************/

    // private function _get_datatables_query()
    // {
    //   $this->db->select;
    //     $this->db->from($this->table);
 
    //     $i = 0;
     
    //     foreach ($this->column_search as $item) // loop column 
    //     {
    //         if($_POST['search']['value']) // if datatable send POST for search
    //         {
                 
    //             if($i===0) // first loop
    //             {
    //                 $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
    //                 $this->db->like($item, $_POST['search']['value']);
    //             }
    //             else
    //             {
    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }
 
    //             if(count($this->column_search) - 1 == $i) //last loop
    //                 $this->db->group_end(); //close bracket
    //         }
    //         $i++;
    //     }
         
    //     if(isset($_POST['order'])) // here order processing
    //     {
    //         $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    //     } 
    //     else if(isset($this->order))
    //     {
    //         $order = $this->order;
    //         $this->db->order_by(key($order), $order[key($order)]);
    //     }
    // }
 
 
    private function _get_datatables_query()
{
    // Correct selection with Aliases for names
    $this->db->select('supplier.*, css.name as state_name, cs.name as country_name, ccs.name as city_name');
    $this->db->from($this->table);
    
    // Joins to get the actual names
    $this->db->join('countries cs', 'cs.id = supplier.country_id', 'LEFT');
    $this->db->join('states css', 'css.id = supplier.state_id', 'LEFT');
    $this->db->join('cities ccs', 'ccs.id = supplier.city_id', 'LEFT');

    $i = 0;
    foreach ($this->column_search as $item) 
    {
        if($_POST['search']['value']) 
        {
            if($i===0) 
            {
                $this->db->group_start(); 
                $this->db->like('supplier.'.$item, $_POST['search']['value']);
            }
            else
            {
                $this->db->or_like('supplier.'.$item, $_POST['search']['value']);
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

function get_datatables()
{
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
    
    // CRITICAL: Specify supplier table to avoid Ajax Error
    $this->db->where('supplier.delete_status', 0);    
    $query = $this->db->get();
    return $query->result();
}

    // function get_datatables()
    // {
    //     $this->_get_datatables_query();
    //     if($_POST['length'] != -1)
    //     $this->db->limit($_POST['length'], $_POST['start']);
    //     $this->db->where('delete_status',0);    
    //     $query = $this->db->get();
    //     return $query->result();
    // }
 
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

}
?>
