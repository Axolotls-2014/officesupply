<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_setup_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function edit_sms_setup_record($data)
    {
        if($this->db->update('sms_setting',$data))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function get_sms_records()
    {
         return $this->db->get_where('sms_setting')->row();
    }
    
}
?>
