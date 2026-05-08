<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_delivery_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	public function get_delivery_records($purchase_id)
	{
        
        return $this->db->select('
                                pd.*,
                                p.reference_no,
                                u.first_name as received_by_first_name,
                                u.last_name as received_by_last_name
                            ')
                    ->from('purchase_delivery pd')
                    ->join('purchase p','p.id = pd.purchase_id','LEFT')
                    ->join('users u','u.id = pd.received_by','LEFT')
                    ->where('pd.purchase_id',$purchase_id)
                    ->get()
                    ->result();
        
	}

    public function add_delivery_record($data)
    {
        if($this->db->insert('purchase_delivery',$data))
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
        if($this->db->update('purchase_delivery',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function delete_delivery_record($purchase_delivery_id)
    {
        $this->db->where('id',$purchase_delivery_id);
        if($this->db->delete('purchase_delivery'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_delivery_single_record($purchase_delivery_id)
    {
        return $this->db->select('pd.*')
                        ->from('purchase_delivery pd')
                        ->where('pd.id',$purchase_delivery_id)
                        ->get()
                        ->row();
    }

    public function get_delivery_item_records($purchase_delivery_id)
    {
        return $this->db->select('
                                    pdi.*,
                                    p.name as product_name
                                ')
                        ->from('purchase_delivery_items pdi')
                        ->join('product p','p.id = pdi.product_id','LEFT')
                        ->where('pdi.purchase_delivery_id',$purchase_delivery_id)
                        ->get()
                        ->result();
    }

    public function add_delivery_item_record($data)
    {
        if($this->db->insert('purchase_delivery_items',$data))
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
        if($this->db->update('purchase_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function delete_delivery_item_record($purchase_delivery_id,$product_id)
    {   
        $this->db->where('purchase_delivery_id',$purchase_delivery_id);
        $this->db->where('product_id',$product_id);

        if($this->db->delete('purchase_delivery_items'))
            return true;
        else
            return false;
    }

    // Total number of quantity delivered of specific product in purchase

    public function get_total_quantity_of_purchase_item_delivered($purchase_id,$product_id)
    {   
        $this->db->select('SUM(pdi.quantity) AS total_delivered_quantity');
        $this->db->from('purchase_delivery_items pdi');
        $this->db->join('purchase_delivery pd','pd.id = pdi.purchase_delivery_id','LEFT');
        $this->db->join('purchase p','p.id = pd.purchase_id');
        $this->db->where('pd.purchase_id',$purchase_id);
        $this->db->where('pdi.product_id',$product_id);
        $this->db->group_by('pdi.product_id');
        
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

    public function get_total_no_of_quantity_of_delivery($purchase_delivery_id)
    {
        $this->db->select('SUM(pdi.quantity) AS total_delivered_quantity');
        $this->db->from('purchase_delivery_items pdi');
        $this->db->join('purchase_delivery pd','pd.id = pdi.purchase_delivery_id','LEFT');
        $this->db->join('purchase p','p.id = pd.purchase_id');
        $this->db->where('pdi.purchase_delivery_id',$purchase_delivery_id);
        $this->db->group_by('pdi.purchase_delivery_id');
        
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

    public function get_total_no_of_quantity_ordered($purchase_id)
    {
        $this->db->select('SUM(pi.quantity) AS total_ordered_quantity');
        $this->db->from('purchase_items pi');
        $this->db->join('purchase p','p.id = pi.purchase_id');
        $this->db->where('p.id',$purchase_id);
        $this->db->group_by('p.id');
        
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

    // public function get_total_no_of_quantity_delivered($purchase_id)
    // {
    //     $this->db->select('SUM(pdi.quantity) AS total_delivered_quantity');
    //     $this->db->from('purchase_delivery_items pdi');
    //     $this->db->join('purchase_delivery pd','pd.id = pdi.purchase_delivery_id','LEFT');
    //     $this->db->join('purchase p','p.id = pd.purchase_id');
    //     $this->db->where('pd.purchase_id',$purchase_id);
    //     $this->db->group_by('pd.purchase_id');
        
    //     $query = $this->db->get();
    //     $data  = $query->row();

    //     if($data == null)
    //     {
    //         return 0;
    //     }
    //     else
    //     {
    //         return $data->total_delivered_quantity;
    //     }   
    // }
    
    public function get_total_no_of_quantity_delivered($purchase_id)
{
    $this->db->select('COALESCE(SUM(pdi.quantity), 0) AS total_delivered_quantity');
    $this->db->from('purchase_delivery pd');
    $this->db->join('purchase_delivery_items pdi', 'pd.id = pdi.purchase_delivery_id');
    $this->db->where('pd.purchase_id', $purchase_id);
    // REMOVED: $this->db->group_by('pd.purchase_id'); // Not needed for SUM with WHERE
    
    $query = $this->db->get();
    
    if($query->num_rows() > 0) {
        return $query->row()->total_delivered_quantity;
    }
    return 0;
}
}
?>
