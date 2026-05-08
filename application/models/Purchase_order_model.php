<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order_model extends CI_Model {

    var $table = 'purchase_order_view';

    var $column_order = array(
                                'id',   
                                'reference_no',
                                'invoice_no',  
                                'purchase_order_date',   
                                'warehouse_id',    
                                'supplier_id', 
                                'supplier_gstin',  
                                'total_taxable_value', 
                                'total_discount',  
                                'total_tax',  
                                'total',   
                                'internal_note',  
                                'external_note',   
                                'terms_and_condition', 
                                'delete_status',   
                                'user_id', 
                                'created_date',    
                                'name',    
                                'company_name'
                            ); //set column field database for datatable orderable
    var $column_search = array(
                                'id',   
                                'reference_no',
                                'invoice_no',  
                                'purchase_order_date',   
                                'warehouse_id',    
                                'supplier_id', 
                                'supplier_gstin',  
                                'total_taxable_value', 
                                'total_discount',  
                                'total_tax',  
                                'total',   
                                'internal_note',  
                                'external_note',   
                                'terms_and_condition', 
                                'delete_status',   
                                'user_id', 
                                'created_date',    
                                'name',    
                                'company_name'
                            ); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
    }

	public function get_purchase_order_records($limit = null)
	{
        if($limit == null)
        {
            return $this->db->select('
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase_order p')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->where('p.delete_status',0)
                        ->order_by('p.created_date','desc')
                        ->get()
                        ->result();
        }
        else
        {
             return $this->db->select('
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase_order p')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->where('p.delete_status',0)
                        ->limit($limit)
                        ->order_by('p.created_date','desc')
                        ->get()
                        ->result();
        }
	}

    public function get_purchase_order_records_by_supplier_id($supplier_id)
    {
        return $this->db->select('
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase_order p')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->where('s.id',$supplier_id)
                        ->where('p.delete_status',0)
                        ->order_by('p.created_date','desc')
                        ->get()
                        ->result();
    }

    public function get_purchase_order_records_by_warehouse_id($warehouse_id)
    {
        return $this->db->select('
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase_order p')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->where('p.warehouse_id',$warehouse_id)
                        ->where('p.delete_status',0)
                        ->order_by('p.created_date','desc')
                        ->get()
                        ->result();
    }

    function get_total_purchase_order_amount($from = null, $to = null)
    {
        $data  = $this->db->select('
                                    SUM(total) AS total,
                                    SUM(total_tax) as total_tax, 
                                    SUM(total_discount) as total_discount, 
                                    SUM(total_taxable_value) as total_taxable_value
                                ')
                         ->from('purchase_order p')
                         ->where('p.delete_status',0)
                         ->get();

        if($data->num_rows() > 0)
        {
            $res = $data->row_array();
            return $res;
        }
        else
        {
            $res                            = array();
            $res['total']                   = 0.0;
            $res['total_tax']               = 0.0;
            $res['total_discount']          = 0.0;
            $res['total_taxable_value']     = 0.0;

            return $res;
        }
    }

    public function add_purchase_order_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
        if($this->db->insert('purchase_order',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_purchase_order_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('purchase_order',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_records_by_purchase_order_id($customer_id)
    {
        return $this->db->select('
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase_order p')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->where('w.id',$warehouse_id)
                        ->where('s.id',$supplier_id)
                        ->order_by('p.created_date','desc')
                        ->get()
                        ->result();
    }

    public function get_purchase_order_single_record($purchase_order_id)
    {
        return $this->db->select('p.*,s.company_name as supplier_name')
                        ->from('purchase_order p')
                        ->join('supplier s','s.id = p.supplier_id')
                        ->where('p.delete_status',0)
                        ->where('p.id',$purchase_order_id)
                        ->get()
                        ->row();
    }

    public function get_purchase_order_item_records($purchase_order_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    s.company_name,
                                    pr.hsn,
                                    w.name as warehouse_name
                                ')
                        ->from('purchase_order_items pi')
                        ->join('purchase_order p','p.id = pi.purchase_order_id')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->join('product pr','pr.id = pi.product_id','left')
                        ->where('p.id',$purchase_order_id)
                        ->get()
                        ->result();
    }

    public function get_purchase_order_item_records_by_tax_id($tax_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    s.company_name,
                                    pr.hsn,
                                    w.name as warehouse_name
                                ')
                        ->from('purchase_order_items pi')
                        ->join('purchase_order p','p.id = pi.purchase_order_id')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->join('product pr','pr.id = pi.product_id','left')
                        ->where('pi.tax_id',$tax_id)
                        ->where('p.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_purchase_order_item_records_by_product_id($product_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    s.company_name,
                                    pr.hsn,
                                    pr.description,
                                    w.name as warehouse_name,
                                    pr.name as product_name
                                ')
                        ->from('purchase_order_items pi')
                        ->join('purchase_order p','p.id = pi.purchase_order_id')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->join('product pr','pr.id = pi.product_id','left')
                        ->where('pi.product_id',$product_id)
                        ->where('p.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_single_purchase_order_item_record($purchase_order_id, $product_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    s.company_name,
                                    pr.hsn,
                                    pr.description,
                                    w.name as warehouse_name,
                                    pr.name as product_name
                                ')
                        ->from('purchase_order_items pi')
                        ->join('purchase_order p','p.id = pi.purchase_order_id')
                        ->join('warehouse w','w.id = p.warehouse_id','left')
                        ->join('supplier s','s.id = p.supplier_id','left')
                        ->join('product pr','pr.id = pi.product_id','left')
                        ->where('p.id',$purchase_order_id)
                        ->where('pi.product_id',$product_id)
                        ->get()
                        ->row();
    }

    public function get_purchase_order_item_records_by_discount_id($discount_id)
    {
        return $this->db->select('
                                    pi.*,
                                    p.*,
                                    w.name,
                                    s.company_name
                                ')
                        ->from('purchase_order_items pi')
                        ->join('purchase_order p','p.id = pi.purchase_order_id')
                        ->join('warehouse w','w.id = pi.warehouse_id','left')
                        ->join('supplier s','s.id = pi.supplier_id','left')
                        ->where('pi.discount_id',$discount_id)
                        ->where('pi.delete_status',0)
                        ->get()
                        ->result();
    }

    public function add_purchase_order_item_record($data)
    {
        if($this->db->insert('purchase_order_items',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_purchase_order_item_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('purchase_order_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function remove_purchase_order_item_records($purchases_id)
    {   
        $this->db->where('purchase_order_id',$purchases_id);
        if($this->db->delete('purchase_order_items'))
            return true;
        else
            return false;
    }

 

    public function get_purchase_order_tax_individual($purchase_order_id)
    {
        return $this->db->select('
                                    SUM(igst_tax) AS igst_tax,
                                    SUM(cgst_tax) AS cgst_tax,
                                    SUM(sgst_tax) AS sgst_tax
                                ')
                        ->from('purchase_order_items pi')
                        ->where('pi.purchase_order_id',$purchase_order_id)
                        ->group_by('pi.purchase_order_id')
                        ->get()
                        ->row();   
    }

     /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
      $this->db->select('p.*');
      $this->db->from($this->table.' p');

     
 
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
        $user_id    = $this->session->userdata('user_id');
        $udata      = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        if($udata->branch_id){
            $this->db->where('warehouse_id', $udata->branch_id); 
        }
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

  

    public function get_total_purchase_order() 
    {
      $this->db->where('delete_status', 0);
        $query = $this->db->get('purchase_order');
        return $query->num_rows();
    }

    public function get_lastest_sequence_number()
    {
      $this->db->select('*');
      $this->db->from('purchase_order');
      // $this->db->where('delete_status',0);

      $query = $this->db->get();
      $data = $query->result();

      // Retrieve the date format from session
      $dateformat = csession('purchase_order_date_format');  // This is expected to be a valid date format string

      // Validate the date format and format the date
      $testDate = date_create_from_format($dateformat, '2024-01-01'); // Use any valid date for testing
      if ($testDate === false) {
          $date = ''; // If the date format is invalid, do not include a date
      } else {
          $date = date($dateformat);
      }

      // Constructing the sequence number
      $sequenceNumber = csession('purchase_order_sequence') + count($data) + 1;
      $formattedSequence = csession('purchase_order_prefix') . csession('seperator') . $date . csession('seperator') . $sequenceNumber;

      return $formattedSequence;
    }


}
?>
