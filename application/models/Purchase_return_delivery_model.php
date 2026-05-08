<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class purchase_return_delivery_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	public function get_delivery_records($purchase_return_id)
	{
        
        return $this->db->select('
                                pd.*,
                                p.reference_no,
                                u.first_name as received_by_first_name,
                                u.last_name as received_by_last_name
                            ')
                    ->from('purchase_return_delivery pd')
                    ->join('purchase_return p','p.id = pd.purchase_return_id','LEFT')
                    ->join('users u','u.id = pd.received_by','LEFT')
                    ->where('pd.purchase_return_id',$purchase_return_id)
                    ->get()
                    ->result();
        
	}

    public function add_delivery_record($data)
    {
        if($this->db->insert('purchase_return_delivery',$data))
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
        if($this->db->update('purchase_return_delivery',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function delete_delivery_record($purchase_return_delivery_id)
    {
        $this->db->where('id',$purchase_return_delivery_id);
        if($this->db->delete('purchase_return_delivery'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_delivery_single_record($purchase_return_delivery_id)
    {
        return $this->db->select('pd.*')
                        ->from('purchase_return_delivery pd')
                        ->where('pd.id',$purchase_return_delivery_id)
                        ->get()
                        ->row();
    }

    public function get_delivery_item_records($purchase_return_delivery_id)
    {
        return $this->db->select('
                                    pdi.*,
                                    p.name as product_name
                                ')
                        ->from('purchase_return_delivery_items pdi')
                        ->join('product p','p.id = pdi.product_id','LEFT')
                        ->where('pdi.purchase_return_delivery_id',$purchase_return_delivery_id)
                        ->get()
                        ->result();
    }

    public function add_delivery_item_record($data)
    {
        if($this->db->insert('purchase_return_delivery_items',$data))
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
        if($this->db->update('purchase_return_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function delete_delivery_item_record($purchase_return_delivery_id,$product_id)
    {   
        $this->db->where('purchase_return_delivery_id',$purchase_return_delivery_id);
        $this->db->where('product_id',$product_id);

        if($this->db->delete('purchase_return_delivery_items'))
            return true;
        else
            return false;
    }

    // Total number of quantity delivered of specific product in purchase_return

    public function get_total_quantity_of_purchase_return_item_delivered($purchase_return_id,$product_id,$batch_no)
    {   
        $this->db->select('SUM(pdi.quantity) AS total_delivered_quantity');
        $this->db->from('purchase_return_delivery_items pdi');
        $this->db->join('purchase_return_delivery pd','pd.id = pdi.purchase_return_delivery_id','LEFT');
        $this->db->join('purchase_return p','p.id = pd.purchase_return_id');
        $this->db->where('pd.purchase_return_id',$purchase_return_id);
        $this->db->where('pdi.product_id',$product_id);
        $this->db->where('pdi.batch_no',$batch_no);
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

    public function get_total_no_of_quantity_of_delivery($purchase_return_delivery_id)
    {
        $this->db->select('SUM(pdi.quantity) AS total_delivered_quantity');
        $this->db->from('purchase_return_delivery_items pdi');
        $this->db->join('purchase_return_delivery pd','pd.id = pdi.purchase_return_delivery_id','LEFT');
        $this->db->join('purchase_return p','p.id = pd.purchase_return_id');
        $this->db->where('pdi.purchase_return_delivery_id',$purchase_return_delivery_id);
        $this->db->group_by('pdi.purchase_return_delivery_id');
        
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

    // Total number of quantity ordered from specific purchase_return

    public function get_total_no_of_quantity_ordered($purchase_return_id)
    {
        $this->db->select('SUM(pi.quantity) AS total_ordered_quantity');
        $this->db->from('purchase_return_items pi');
        $this->db->join('purchase_return p','p.id = pi.purchase_return_id');
        $this->db->where('p.id',$purchase_return_id);
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

    // Total number of quantity delivered from specific purchase_return

    public function get_total_no_of_quantity_delivered($purchase_return_id)
    {
        $this->db->select('SUM(pdi.quantity) AS total_delivered_quantity');
        $this->db->from('purchase_return_delivery_items pdi');
        $this->db->join('purchase_return_delivery pd','pd.id = pdi.purchase_return_delivery_id','LEFT');
        $this->db->join('purchase_return p','p.id = pd.purchase_return_id');
        $this->db->where('pd.purchase_return_id',$purchase_return_id);
        $this->db->group_by('pd.purchase_return_id');
        
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

    public function get_total_no_of_quantity_purchase_return_created($purchase_id,$product_id,$batch_no)
    {
      $this->db->select('SUM(sri.quantity) AS total_purchase_return_quantity');
      $this->db->from('purchase_return_items sri');
      $this->db->join('purchase_return sr','sr.id = sri.purchase_return_id');
      $this->db->where('sr.invoice_no',$purchase_id);
      $this->db->where('sri.product_id',$product_id);
      $this->db->where('sri.batch_no',$batch_no);
      $this->db->where('sr.delete_status',0);
      $this->db->group_by('sr.invoice_no');


      $query = $this->db->get();
      $data  = $query->row();

      if($data == null)
      {
          return 0;
      }
      else
      {
          return $data->total_purchase_return_quantity;
      }
    }
}
?>
