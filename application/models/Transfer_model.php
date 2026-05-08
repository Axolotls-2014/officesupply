<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_model extends CI_Model {

    var $table = 'transfer';
    var $column_order = array(
                              'transfer_date',
                              'from_warehouse_id',
                              'from_warehouse_name',
                              'to_warehouse_id',
                              'to_warehouse_name',
                              'total'
                            ); //set column field database for datatable orderable
    var $column_search = array(
                              'transfer_date',
                              'from_warehouse_id',
                              'from_warehouse_name',
                              'to_warehouse_id',
                              'to_warehouse_name',
                              'total'
                            ); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
    }

	public function get_transfer_records($limit = null)
	{
        if($this->permission_model->has_permission('list_all_transfer'))
        {
            if($limit == null)
            {
                return $this->db->select('
                                        s.*,
                                        c.customer_name
                                    ')
                            ->from('transfer s')
                            ->join('customer c','c.id = s.customer_id','left')
                            ->where('s.delete_status',0)
                            ->order_by('s.created_date','desc')
                            ->get()
                            ->result();
            }
            else
            {
                return $this->db->select('
                                        s.*,
                                        c.customer_name
                                    ')
                            ->from('transfer s')
                            ->join('customer c','c.id = s.customer_id','left')
                            ->where('s.delete_status',0)
                            ->limit($limit)
                            ->order_by('s.created_date','desc')
                            ->get()
                            ->result();
            }
        }
        else
        {
            $user_id = $this->session->userdata('user_id');

            if($limit == null)
            {
                return $this->db->select('
                                        s.*
                                    ')
                            ->from('transfer s')
                            ->where('s.delete_status',0)
                            ->order_by('s.created_date','desc')
                            ->where('s.user_id',$user_id)
                            ->get()
                            ->result();
            }
            else
            {
                return $this->db->select('
                                        s.*
                                    ')
                            ->from('transfer s')
                            ->where('s.delete_status',0)
                            ->limit($limit)
                            ->order_by('s.created_date','desc')
                            ->where('s.user_id',$user_id)
                            ->get()
                            ->result();
            }
        }
	}    

    public function add_transfer_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
        if($this->db->insert('transfer',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_transfer_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('transfer',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

  

    public function get_transfer_single_record($transfer_id)
    {
        return $this->db->select('s.*')
                        ->from('transfer s')
                        ->where('s.delete_status',0)
                        ->where('s.id',$transfer_id)
                        ->get()
                        ->row();
    }

    

    public function get_transfer_item_records($transfer_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*
                                ')
                        ->from('transfer_items si')
                        ->join('transfer s','s.id = si.transfer_id')
                        ->join('product p','p.id = si.product_id','left')
                        // ->join('warehouse_products wp','wp.warehouse_id = s.warehouse_id','left')
                        // ->join('warehouse_products wp1','wp1.product_id = p.id','left')
                        // ->join('warehouse_products wp2','wp2.cost = si.cost','left')
                        ->where('s.id',$transfer_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_single_transfer_item_record($transfer_id, $product_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*
                                ')
                        ->from('transfer_items si')
                        ->join('transfer s','s.id = si.transfer_id')
                        ->join('product pr','pr.id = si.product_id','left')
                        ->where('s.id',$transfer_id)
                        ->where('si.product_id',$product_id)
                        ->get()
                        ->row();
    }

    public function add_transfer_item_record($data)
    {
        if($this->db->insert('transfer_items',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_transfer_item_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('transfer_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function remove_transfer_item_records($transfer_id)
    {   
        $this->db->where('transfer_id',$transfer_id);
        if($this->db->delete('transfer_items'))
            return true;
        else
            return false;
    }

    

    /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
       /* $this->db->select*/
        $this->db->from($this->table);
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->where('delete_status',0);    
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    /*********************************** End Dynamic Datatable function *****************************************/
}
?>
