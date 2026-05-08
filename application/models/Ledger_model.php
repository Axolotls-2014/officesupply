<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ledger_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_records($account_group_title_array = null)
    {
        if(empty($account_group_title_array) || $account_group_title_array == null)
        {
            return $this->db->select('l.*,ag.group_title, ba.active')
                         ->from('ledger l')
                         ->join('account_group ag','ag.id = l.account_group_id','LEFT')
                         ->join('bank_account ba', 'ba.ledger_id = l.id', 'LEFT')
                         ->get()
                         ->result();    
        }
        else
        {
            return $this->db->select('l.*,ag.group_title, ba.active')
                         ->from('ledger l')
                         ->join('account_group ag','ag.id = l.account_group_id','LEFT')
                         ->join('bank_account ba', 'ba.ledger_id = l.id', 'LEFT')
                         ->where_in('ag.group_title',$account_group_title_array)
                         ->get()
                         ->result();    
        }
    }

    public function get_records_by_account_group_category($category = array())
    {
    
        return $this->db->select('l.*,ag.group_title')
                     ->from('ledger l')
                     ->join('account_group ag','ag.id = l.account_group_id')
                     ->where_in('ag.category',$category)
                     ->get()
                     ->result();    
        
    }

    public function get_records_by_account_group_id($account_group_id,$year_ending = null)
    {
    
        if($year_ending == null)
        {
            // get current year's ledger with opening balance & closing balance
            return $this->db->select('l.*,ag.group_title')
                     ->from('ledger l')
                     ->join('account_group ag','ag.id = l.account_group_id')
                     ->where('l.account_group_id',$account_group_id)
                     ->get()
                     ->result();        
        }
        else
        {
            // get specific year's ledger with opening balance & closing balance
        }
        
        
    }

    public function add_record($data)
    {
        if($this->db->insert('ledger',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

    public function edit_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('ledger',$data)){
            return  $id;
        }
        else{
            return FALSE;
        }
    }

    public function get_single_record($ledger_id)
    {
        return $this->db->select('l.*,ag.group_title as group_title')
                         ->from('ledger l')
                         ->join('account_group ag','ag.id = l.account_group_id','LEFT')
                         ->where('l.id',$ledger_id)
                         ->get()
                         ->row();    
    }

    public function get_opening_balance_of_ledger($ledger_id, $from_date = null, $to_date = null)
    {
        return $this->db->select('l.*')
                     ->from('ledger l')
                     ->where('l.id',$ledger_id)
                     ->get()
                     ->row()
                     ->opening_balance;           
    }

    public function add_capital_account_entry($data)
    {
        if($this->db->insert('capital_account_entries',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }   
    }

    public function get_capital_account_entries()
    {
        return $this->db->select('cae.*')
                     ->from('capital_account_entries cae')
                     ->get()
                     ->result();
    }
    
    /**
 * Get ledger details with party information (customer or supplier)
 * @param int $ledger_id
 * @return object|null
 */
public function get_ledger_with_party_info($ledger_id)
{
    // Get ledger details
    $ledger = $this->get_single_record($ledger_id);
    
    if(!$ledger) return null;
    
    // Check if this ledger belongs to a customer
    $this->db->where('ledger_id', $ledger_id);
    $customer = $this->db->get('customer')->row();
    
    // Check if this ledger belongs to a supplier
    $this->db->where('ledger_id', $ledger_id);
    $supplier = $this->db->get('supplier')->row();
    
    // Attach party info to ledger object
    if($customer) {
        $ledger->party_type = 'Customer';
        $ledger->party_data = $customer;
    } elseif($supplier) {
        $ledger->party_type = 'Supplier';
        $ledger->party_data = $supplier;
    } else {
        $ledger->party_type = null;
        $ledger->party_data = null;
    }
    
    return $ledger;
}

   
}
?>
