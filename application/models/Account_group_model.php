<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_group_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_records($category = null)
    {
        if($category == null)
        {
            return $this->db->select('*')
                         ->from('account_group')
                         ->get()
                         ->result();    
        }
        else
        {
            return $this->db->select('*')
                         ->from('account_group')
                         ->where('category',$category)
                         ->get()
                         ->result();    
        }
        
    }

    public function add_record($data)
    {
        if($this->db->insert('account_group',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

    public function get_single_record($account_group_id)
    {
        return $this->db->get_where('account_group', array("id" => $account_group_id))->row();
    }
}
?>
