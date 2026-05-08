<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function add_record($data)
    {
        if($this->db->insert('attachment',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

    public function get_single_record($attachment_id)
    {
        return $this->db->get_where('attachment', array("id" => $attachment_id))->row();
    }

    function remove_attachment_by_session()
    {
        $this->session->unset_userdata('attachment_array');
    }
}
?>
