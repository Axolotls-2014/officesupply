<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_setup_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function edit_email_setup_record($data,$id)
    {
        $this->db->where('id',$id);

        if($this->db->update('email_setup',$data))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function get_email_records()
    {
         return $this->db->get_where('email_setup', array("id" => 1))->row();
    }
    
}
?>
