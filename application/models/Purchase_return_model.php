<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return_model extends CI_Model {

    var $table = 'purchase_return_view';

    var $column_order = array(
                                'id',
                                'reference_no',
                                'invoice_no',
                                'purchase_return_date',
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
                                'company_name',
                                'name
                            '); //set column field database for datatable orderable
    var $column_search = array(
                                'id',
                                'reference_no',
                                'invoice_no',
                                'purchase_return_date',
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
                                'company_name',
                                'name'
                            ); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
    }

	public function get_purchase_return_records($limit = null)
	{
        if($this->permission_model->has_permission('list_all_purchase_return'))
        {
            if($limit == null)
            {
                return $this->db->select('
                                        s.*,
                                        c.company_name,
                                        w.name
                                    ')
                            ->from('purchase_return s')
                            ->join('supplier c','c.id = s.supplier_id','left')
                             ->join('warehouse w','w.id = s.warehouse_id','left')
                            ->where('s.delete_status',0)
                            ->order_by('s.created_date','desc')
                            ->get()
                            ->result();
            }
            else
            {
                return $this->db->select('
                                        s.*,
                                        c.company_name,
                                        w.name
                                    ')
                            ->from('purchase_return s')
                            ->join('supplier c','c.id = s.supplier_id','left')
                             ->join('warehouse w','w.id = s.warehouse_id','left')
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
                                        s.*,
                                        c.company_name
                                    ')
                            ->from('purchase_return s')
                            ->join('supplier c','c.id = s.supplier_id','left')
                            ->where('s.delete_status',0)
                            ->order_by('s.created_date','desc')
                            ->where('s.user_id',$user_id)
                            ->get()
                            ->result();
            }
            else
            {
                return $this->db->select('
                                        s.*,
                                        c.company_name
                                    ')
                            ->from('purchase_return s')
                            ->join('supplier c','c.id = s.supplier_id','left')
                            ->where('s.delete_status',0)
                            ->limit($limit)
                            ->order_by('s.created_date','desc')
                            ->where('s.user_id',$user_id)
                            ->get()
                            ->result();
            }
        }
	}

    public function get_purchase_return_records_by_supplier_id($supplier_id)
    {
        return $this->db->select('
                                    s.*,
                                    w.name,
                                    c.company_name
                                ')
                        ->from('purchase_return s')
                        ->join('warehouse w','w.id = s.warehouse_id','left')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.supplier_id',$supplier_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
    }

    public function get_purchase_return_records_by_warehouse_id($warehouse_id)
    {
        return $this->db->select('
                                    s.*,
                                    c.company_name
                                ')
                        ->from('purchase_return s')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.warehouse_id',$warehouse_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
    }

    function get_total_purchase_return_amount($from = null, $to = null)
    {
        $data  = $this->db->select('
                                    SUM(total) AS total,
                                    SUM(total_tax) as total_tax, 
                                    SUM(total_discount) as total_discount, 
                                    SUM(total_taxable_value) as total_taxable_value
                                ')
                         ->from('purchase_return s')
                         ->where('s.delete_status',0)
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

    public function add_purchase_return_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
        if($this->db->insert('purchase_return',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_purchase_return_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('purchase_return',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

  

    public function get_purchase_return_single_record($purchase_return_id)
    {
        return $this->db->select('s.*,c.company_name')
                        ->from('purchase_return s')
                        ->join('supplier c','c.id = s.supplier_id','LEFT')
                        // ->where('s.delete_status',0)
                        ->where('s.id',$purchase_return_id)
                        ->get()
                        ->row();
    }

    // public function get_purchase_return_item_records($purchase_return_id)
    // {
    //     return $this->db->select('
    //                                 si.*,
    //                                 s.*,
    //                                 c.company_name,
    //                                 p.hsn,
    //                                 p.name as product_name,
    //                                 p.description as product_description
    //                             ')
    //                     ->from('purchase_return_items si')
    //                     ->join('purchase_return s','s.id = si.purchase_return_id')
    //                     ->join('supplier c','c.id = s.supplier_id','left')
    //                     ->join('product p','p.id = si.product_id','left')
    //                     // ->join('warehouse_products wp','wp.warehouse_id = s.warehouse_id','left')
    //                     // ->join('warehouse_products wp1','wp1.product_id = p.id','left')
    //                     // ->join('warehouse_products wp2','wp2.cost = si.cost','left')
    //                     ->where('s.id',$purchase_return_id)
    //                     ->where('s.delete_status',0)
    //                     ->get()
    //                     ->result();
    // }
    
    public function get_purchase_return_item_records($purchase_return_id)
{
    return $this->db->select('
                                si.*,
                                s.*,
                                c.company_name,
                                p.hsn,
                                p.product_code,
                                p.name as product_name,
                                p.description as product_description,
                                pc.name as category_name
                            ')
                    ->from('purchase_return_items si')
                    ->join('purchase_return s','s.id = si.purchase_return_id')
                    ->join('supplier c','c.id = s.supplier_id','left')
                    ->join('product p','p.id = si.product_id','left')
                    ->join('product_category pc','pc.id = p.product_category_id','left')
                    ->where('s.id',$purchase_return_id)
                    ->where('s.delete_status',0)
                    ->get()
                    ->result();
}

    public function get_single_purchase_return_item_record($purchase_return_id, $product_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.company_name,
                                    pr.hsn,
                                    pr.description,
                                    w.name as warehouse_name,
                                    pr.name as product_name
                                ')
                        ->from('purchase_return_items si')
                        ->join('purchase_return s','s.id = si.purchase_return_id')
                        ->join('warehouse w','w.id = s.warehouse_id','left')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->join('product pr','pr.id = si.product_id','left')
                        ->where('s.id',$purchase_return_id)
                        ->where('si.product_id',$product_id)
                        ->get()
                        ->row();
    }

    public function get_purchase_return_tax_individual_and_tot_cost_and_total_price($purchase_return_id)
    {
        return $this->db->select('
                                    SUM(igst_tax) AS igst_tax,
                                    SUM(cgst_tax) AS cgst_tax,
                                    SUM(sgst_tax) AS sgst_tax,
                                    SUM(quantity*cost) AS total_cost,
                                    SUM((quantity*price)-discount_amount) AS total_price
                                ')
                        ->from('purchase_return_items si')
                        ->where('si.purchase_return_id',$purchase_return_id)
                        ->group_by('si.purchase_return_id')
                        ->get()
                        ->row();   
    }

    public function get_cost_by_purchase_return_id($purchase_return_id)
    {
        return $this->db->select('
                                    SUM(quantity*cost) AS total_cost
                                ')
                        ->from('purchase_return_items si')
                        ->where('si.purchase_return_id',$purchase_return_id)
                        ->group_by('si.purchase_return_id')
                        ->get()
                        ->row();   
    }

    public function get_purchase_return_item_records_by_discount_id($discount_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.company_name
                                ')
                        ->from('purchase_return_items si')
                        ->join('purchase_return s','s.id = si.purchase_return_id')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('si.discount_id',$discount_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_purchase_return_item_records_by_product_id($product_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.company_name
                                ')
                        ->from('purchase_return_items si')
                        ->join('purchase_return s','s.id = si.purchase_return_id')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('si.product_id',$product_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_purchase_return_item_records_by_tax_id($tax_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.company_name
                                ')
                        ->from('purchase_return_items si')
                        ->join('purchase_return s','s.id = si.purchase_return_id')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('si.tax_id',$tax_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function add_purchase_return_item_record($data)
    {
        if($this->db->insert('purchase_return_items',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_purchase_return_item_record($data,$id)
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

    public function remove_purchase_return_item_records($purchase_returns_id)
    {   
        $this->db->where('purchase_return_id',$purchase_returns_id);
        if($this->db->delete('purchase_return_items'))
            return true;
        else
            return false;
    }

    public function purchase_return_by_month_year($year_month)
    {   

        $data = $this->db->select('
                                    SUM(total) AS amount
                                ')
                        ->from('purchase_return s')
                        ->where('s.delete_status',0)
                        ->like('s.invoice_date',$year_month,'both')
                        ->group_by('DATE_FORMAT(s.invoice_date,"'.$year_month.'")')
                        ->get()
                        ->result_array();
        return $data;       
    }

    public function get_purchase_return_tax_individual($purchase_return_id)
    {
        return $this->db->select('
                                    SUM(igst_tax) AS igst_tax,
                                    SUM(cgst_tax) AS cgst_tax,
                                    SUM(sgst_tax) AS sgst_tax
                                ')
                        ->from('purchase_return_items pi')
                        ->where('pi.purchase_return_id',$purchase_return_id)
                        ->group_by('pi.purchase_return_id')
                        ->get()
                        ->row();   
    }

        /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
       /* $this->db->select*/
     //   $this->db->from($this->table);
 $this->db->select('pr.*, w.name as warehouse_name, s.company_name');
    $this->db->from($this->table . ' pr');
    $this->db->join('warehouse w', 'w.id = pr.warehouse_id', 'left');
    $this->db->join('supplier s', 's.id = pr.supplier_id', 'left');
    $this->db->where('pr.delete_status', 0);
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

    /*public function get_lastest_sequence_number()
    {
      $this->db->select('*');
      $this->db->from('purchase_return');
      // $this->db->where('delete_status',0);

      $query = $this->db->get();
      $data = $query->result();

       // Retrieve the date format from session
       $dateformat = csession('purchase_return_date_format');  // This is expected to be a valid date format string

       // Validate the date format and format the date
       $testDate = date_create_from_format($dateformat, '2024-01-01'); // Use any valid date for testing
       if ($testDate === false) {
           $date = ''; // If the date format is invalid, do not include a date
       } else {
           $date = date($dateformat);
       }

       // Constructing the sequence number
       $sequenceNumber = csession('purchase_return_sequence') + count($data) + 1;
       $formattedSequence = csession('purchase_return_prefix') . csession('seperator') . $date . csession('seperator') . $sequenceNumber;

       return $formattedSequence;
    }*/

       public function get_lastest_sequence_number()
{
    $this->db->select('MAX(CAST(reference_no AS UNSIGNED)) as max_ref');
    $this->db->from('purchase');
    $this->db->where('delete_status', 0);
    $query = $this->db->get();
    $result = $query->row();
    
    if($result && $result->max_ref > 0) {
        $sequenceNumber = $result->max_ref + 1;
    } else {
        $sequenceNumber = 1;
    }
    
    return $sequenceNumber;
}

    
}
?>
