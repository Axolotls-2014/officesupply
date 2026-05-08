<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_delivery_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	public function get_delivery_records($sale_id)
	{
        
        return $this->db->select('
                                sd.*,
                                s.reference_no,
                                u.first_name as delivered_by_first_name,
                                u.last_name as delivered_by_last_name
                            ')
                    ->from('sale_delivery sd')
                    ->join('sale s','s.id = sd.sale_id','LEFT')
                    ->join('users u','u.id = sd.delivered_by','LEFT')
                    ->where('sd.sale_id',$sale_id)
                    ->get()
                    ->result();
        
	}

    public function add_delivery_record($data)
    {
        if($this->db->insert('sale_delivery',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_delivery_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('sale_delivery',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function delete_delivery_record($sale_delivery_id)
    {
        $this->db->where('id',$sale_delivery_id);
        if($this->db->delete('sale_delivery'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_delivery_single_record($sale_delivery_id)
    {
        return $this->db->select('sd.*')
                        ->from('sale_delivery sd')
                        ->where('sd.id',$sale_delivery_id)
                        ->get()
                        ->row();
    }

    public function get_delivery_item_records($sale_delivery_id)
    {
        return $this->db->select('
                                    sdi.*,
                                    p.name as product_name
                                ')
                        ->from('sale_delivery_items sdi')
                        ->join('product p','p.id = sdi.product_id','LEFT')
                        ->where('sdi.sale_delivery_id',$sale_delivery_id)
                        ->get()
                        ->result();
    }

    public function add_delivery_item_record($data)
    {
        if($this->db->insert('sale_delivery_items',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_delivery_item_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('sale_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function delete_delivery_item_record($sale_delivery_id,$product_id)
    {   
        $this->db->where('sale_delivery_id',$sale_delivery_id);
        $this->db->where('product_id',$product_id);

        if($this->db->delete('sale_delivery_items'))
            return true;
        else
            return false;
    }

    // Total number of quantity delivered of specific product in purchase

    public function get_total_quantity_of_sale_item_delivered($sale_id,$product_id)
    {   
        $this->db->select('SUM(sdi.quantity) AS total_delivered_quantity');
        $this->db->from('sale_delivery_items sdi');
        $this->db->join('sale_delivery sd','sd.id = sdi.sale_delivery_id','LEFT');
        $this->db->join('sale s','s.id = sd.sale_id');
        $this->db->where('sd.sale_id',$sale_id);
        $this->db->where('sdi.product_id',$product_id);
        $this->db->group_by('sdi.product_id');
        
        $query = $this->db->get();
        $data  = $query->row();

        if($data == null)
        {
            return 0;
        }
        else
        {
            return $data->total_delivered_quantity;
        }
    }

    // Total number of quantity delivered from specific delivery

    public function get_total_no_of_quantity_of_delivery($sale_delivery_id)
    {
        $this->db->select('SUM(sdi.quantity) AS total_delivered_quantity');
        $this->db->from('sale_delivery_items sdi');
        $this->db->join('sale_delivery sd','sd.id = sdi.sale_delivery_id','LEFT');
        $this->db->join('sale s','s.id = sd.sale_id');
        $this->db->where('sdi.sale_delivery_id',$sale_delivery_id);
        $this->db->group_by('sdi.sale_delivery_id');
        
        $query = $this->db->get();
        $data  = $query->row();

        if($data == null)
        {
            return 0;
        }
        else
        {
            return $data->total_delivered_quantity;
        }   
    }

    // Total number of quantity ordered from specific purchase

    public function get_total_no_of_quantity_ordered($sale_id)
    {
        $this->db->select('SUM(si.quantity) AS total_ordered_quantity');
        $this->db->from('sale_items si');
        $this->db->join('sale s','s.id = si.sale_id');
        $this->db->where('s.id',$sale_id);
        $this->db->group_by('s.id');
        
        $query = $this->db->get();
        $data  = $query->row();

        if($data == null)
        {
            return 0;
        }
        else
        {
            return $data->total_ordered_quantity;
        }   
    }

    // Total number of quantity delivered from specific purchase

    public function get_total_no_of_quantity_delivered($sale_id)
    {
        $this->db->select('SUM(sdi.quantity) AS total_delivered_quantity');
        $this->db->from('sale_delivery_items sdi');
        $this->db->join('sale_delivery sd','sd.id = sdi.sale_delivery_id','LEFT');
        $this->db->join('sale s','s.id = sd.sale_id');
        $this->db->where('sd.sale_id',$sale_id);
        $this->db->group_by('sd.sale_id');
        
        $query = $this->db->get();
        $data  = $query->row();

        if($data == null)
        {
            return 0;
        }
        else
        {
            return $data->total_delivered_quantity;
        }   
    }
}
?>
