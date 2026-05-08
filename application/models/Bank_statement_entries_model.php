<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_statement_entries_model extends CI_Model {

    function __construct() {
        parent::__construct();
        
    }

    public function get_records($bank_statement_id)
    {
        return $this->db->select('
                                    bse.*,
                                    b.*,
                                ')
                        ->from('bank_statement_entries bse')
                        ->join('bank_statement_view b','b.bank_statement_id = bse.bank_statement_id')
                        ->where('b.bank_statement_id',$bank_statement_id)
                        ->where('b.delete_status',0)
                        ->get()
                        ->result();
    }

  public function add_record($data)
  {
    $data['created_by']  = $this->session->userdata('user_id');
    if($this->db->insert('bank_statement_entries',$data)){
        return  $this->db->insert_id();
    }
    else{
        return FALSE;
    }
  }
  

    public function edit_record($data,$id)
    {   
      $data['updated_date'] = date('Y-m-d H:i:s');
      $data['updated_by']   = $this->session->userdata('user_id');

        $this->db->where('id',$id);
        if($this->db->update('bank_statement_entries',$data)){
            return true;
        }
        else{
            return false;
        }
    }

    // public function delete_record($data,$id)
    // {
    //     $this->db->where('id',$id);

    //     if($this->db->delete('bank_statement'))
    //     {
    //         return true;
    //     }
    //     else
    //     {
    //         return false;
    //     }
    // }

    public function get_single_record($bank_statement_entry_id)
    {
        return $this->db->get_where('bank_statement_entries', array("id" => $bank_statement_entry_id))->row();
    }

   
}
?>
