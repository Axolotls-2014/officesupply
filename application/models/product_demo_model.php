<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_demo_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	public function get_product_demo_records()
	{
		return $this->db->select('*')
                         ->from('product')
                         ->where('delete_status',0)
                         ->get()
                         ->result();
	}

    public function add_record($data)
    {
         if($this->db->insert('product',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

     public function edit_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('product',$data)){
            return true;
        }
        else{
            return false;
        }
    }

    public function delete_record($data,$id)
    {
        $this->db->where('id',$id);

        if($this->db->delete('product'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_single_record($product_demo_id)
    {
        return $this->db->get_where('product', array("id" => $product_demo_id))->row();
    }

}
?>
