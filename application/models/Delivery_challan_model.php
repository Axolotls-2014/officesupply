<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_challan_model extends CI_Model {

    var $table = 'delivery_challan_view';
    var $column_order = array('reference_no','invoice_date','customer_name','total_discount','total_taxable_value','tds','total_tax','total'); //set column field database for datatable orderable
    var $column_search = array('reference_no','invoice_date','customer_name','total_discount','total_taxable_value','tds','total_tax','total'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

    var $is_customer = false;

    function __construct() {
      
        parent::__construct();

        if ($this->ion_auth->in_group(CUSTOMER_GROUP_NAME))
          $this->is_customer = true;
    }

	public function get_delivery_challan_records($limit = null)
   {
       
    //   echo "model";
    //   die;
    $user_id = $this->session->userdata('user_id');
    
    $branch_id_query = $this->db->select('branch_id')
                                ->from('users')
                                ->where('id', $user_id)
                                ->get();
  
    $branch_id = ($branch_id_query->num_rows() > 0) ? $branch_id_query->row()->branch_id : null; // Get branch_id if exists

    if($this->permission_model->has_permission('list_all_delivery_challan'))
    {
     
        if($limit == null)
        {
            return $this->db->select('s.*, c.customer_name')
                             ->from('delivery_challan s')
                             ->join('customer c', 'c.id = s.customer_id', 'left')
                             ->where('s.delete_status', 0)
                             ->order_by('s.created_date', 'desc')
                             ->get()
                             ->result();
        }
        else
        {
            return $this->db->select('s.*, c.customer_name')
                             ->from('delivery_challan s')
                             ->join('customer c', 'c.id = s.customer_id', 'left')
                             ->where('s.delete_status', 0)
                             ->limit($limit)
                             ->order_by('s.created_date', 'desc')
                             ->get()
                             ->result();
        }
    }
    else
    {
        if ($branch_id) {
           
            $this->db->select('s.*, c.customer_name')
                     ->from('delivery_challan s')
                     ->join('customer c', 'c.id = s.customer_id', 'left')
                     ->where('s.delete_status', 0)
                     ->where('s.warehouse_id', $branch_id)  
                     ->order_by('s.created_date', 'desc');

            if($limit != null) {
                $this->db->limit($limit);
            }

            $query = $this->db->get();
            return $query->result();
        } else {
            
            if($limit == null) {
                $this->db->select('s.*, c.customer_name')
                         ->from('delivery_challan s')
                         ->join('customer c', 'c.id = s.customer_id', 'left')
                         ->where('s.delete_status', 0)
                         ->order_by('s.created_date', 'desc');

                $query = $this->db->get();
                return $query->result();
            } else {
                $this->db->select('s.*, c.customer_name')
                         ->from('delivery_challan s')
                         ->join('customer c', 'c.id = s.customer_id', 'left')
                         ->where('s.delete_status', 0)
                         ->limit($limit)
                         ->order_by('s.created_date', 'desc');

                $query = $this->db->get();
                return $query->result();
            }
        }
    }
}

    public function get_delivery_challan_single_record_by_quotation_id($quotation_id)
    {
        return $this->db->select('s.*,c.customer_name')
                        ->from('delivery_challan s')
                        ->join('customer c','c.id = s.customer_id','LEFT')
                        ->where('s.delete_status',0)
                        ->where('s.quotation_id',$quotation_id)
                        ->get()
                        ->row();
    }
    
    public function get_delivery_challan_records_by_customer_id($customer_id)
    {
        return $this->db->select('
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('delivery_challan s')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.customer_id',$customer_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
    }

    public function get_delivery_challan_records_by_sale_id($sale_id)
    {
        return $this->db->select('
                                    s.*
                                   
                                ')
                        ->from('delivery_challan s')
                        // ->join('customer c','c.id = s.customer_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.sale_id',$sale_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
    }

    public function get_delivery_challan_records_by_warehouse_id($warehouse_id)
    {
        return $this->db->select('
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('delivery_challan s')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.warehouse_id',$warehouse_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
    }

    function get_total_delivery_challan_amount($from = null, $to = null)
    {
        $data  = $this->db->select('
                                    SUM(total) AS total,
                                    SUM(total_tax) as total_tax, 
                                    SUM(total_discount) as total_discount, 
                                    SUM(total_taxable_value) as total_taxable_value
                                ')
                         ->from('delivery_challan s')
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

    public function add_delivery_challan_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
        if($this->db->insert('delivery_challan',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_delivery_challan_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('delivery_challan',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

  

    public function get_delivery_challan_single_record($delivery_challan_id)
    {
        return $this->db->select('s.*,c.customer_name')
                        ->from('delivery_challan s')
                        ->join('customer c','c.id = s.customer_id','LEFT')
                        //->where('s.delete_status',0)
                        ->where('s.id',$delivery_challan_id)
                        ->get()
                        ->row();
    }

    public function get_delivery_challan_single_record_by_reference_no($reference_no)
    {
        return $this->db->select('s.*,c.customer_name')
                        ->from('delivery_challan s')
                        ->join('customer c','c.id = s.customer_id','LEFT')
                        // ->where('s.delete_status',0)
                        ->where('s.reference_no',$reference_no)
                        ->get()
                        ->row();
    }

    // public function get_delivery_challan_item_records($delivery_challan_id)
    // {
    //     return $this->db->select('
    //                                 si.*,
    //                                 s.*,
    //                                 c.customer_name,
    //                                 p.hsn
                                    
    //                             ')
    //                     ->from('delivery_challan_items si')
    //                     ->join('delivery_challan s','s.id = si.delivery_challan_id')
    //                     ->join('customer c','c.id = s.customer_id','left')
    //                     ->join('product p','p.id = si.product_id','left')
    //                     // ->join('warehouse_products wp','wp.warehouse_id = s.warehouse_id','left')
    //                     // ->join('warehouse_products wp1','wp1.product_id = p.id','left')
    //                     // ->join('warehouse_products wp2','wp2.cost = si.cost','left')
    //                     ->where('s.id',$delivery_challan_id)
    //                     ->where('s.delete_status',0)
    //                     ->get()
    //                     ->result();
    // }

    public function get_delivery_challan_item_records($delivery_challan_id, $is_array = false)
    {
      //$this->db->select('si.*,s.*,c.customer_name,p.hsn,p.supplier_id as supplier_name,wp.ptr,wp.ptd,wp.mfg_date,wp.item_code,(SELECT pi.cost FROM purchase_items pi WHERE pi.warehouse_product_id = si.warehouse_product_id AND pi.batch_no = si.batch_no LIMIT 1) as purchase_cost');
      
      $this->db->select('
                          si.*,
                          s.*,
                          c.customer_name,
                          p.hsn,
                          p.id as product_id,
                          p.name
                        ');
                      
      $this->db->from('delivery_challan_items si'); 
      $this->db->join('delivery_challan s','s.id = si.delivery_challan_id');
      $this->db->join('customer c','c.id = s.customer_id','left');
      $this->db->join('product p','p.id = si.product_id','left');
      // $this->db->join('supplier sp','sp.id = p.supplier_id','left');
     $this->db->join('warehouse_products wp','wp.id = si.warehouse_product_id','left');

      if($is_array == false)
        $this->db->where('s.id',$delivery_challan_id);
      else
        $this->db->where_in('s.id',$delivery_challan_id);

      $this->db->where('s.delete_status',0);
      // $this->db->order_by('si.product_name', "asc");
      
      $query = $this->db->get();
      return $query->result();
    }

    public function get_single_delivery_challan_item_record($delivery_challan_id, $product_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name,
                                    pr.hsn,
                                    w.name as warehouse_name
                                ')
                        ->from('delivery_challan_items si')
                        ->join('delivery_challan s','s.id = si.delivery_challan_id')
                        ->join('warehouse w','w.id = s.warehouse_id','left')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->join('product pr','pr.id = si.product_id','left')
                        ->where('s.id',$delivery_challan_id)
                        ->where('si.product_id',$product_id)
                        ->get()
                        ->row();
    }

    public function get_delivery_challan_tax_individual_and_tot_cost_and_total_price($delivery_challan_id)
    {
        return $this->db->select('
                                    SUM(igst_tax) AS igst_tax,
                                    SUM(cgst_tax) AS cgst_tax,
                                    SUM(sgst_tax) AS sgst_tax,
                                    SUM(quantity*cost) AS total_cost,
                                    SUM((quantity*price)-discount_amount) AS total_price
                                ')
                        ->from('delivery_challan_items si')
                        ->where('si.delivery_challan_id',$delivery_challan_id)
                        ->group_by('si.delivery_challan_id')
                        ->get()
                        ->row();   
    }

    public function get_delivery_challan_tax_individual($delivery_challan_id)
    {
        return $this->db->select('
                                    SUM(igst_tax) AS igst_tax,
                                    SUM(cgst_tax) AS cgst_tax,
                                    SUM(sgst_tax) AS sgst_tax
                                ')
                        ->from('delivery_challan_items pi')
                        ->where('pi.delivery_challan_id',$delivery_challan_id)
                        ->group_by('pi.delivery_challan_id')
                        ->get()
                        ->row();   
    }

    public function get_cost_by_delivery_challan_id($delivery_challan_id)
    {
        return $this->db->select('
                                    SUM(quantity*cost) AS total_cost
                                ')
                        ->from('delivery_challan_items si')
                        ->where('si.delivery_challan_id',$delivery_challan_id)
                        ->group_by('si.delivery_challan_id')
                        ->get()
                        ->row();   
    }

    public function get_delivery_challan_item_records_by_discount_id($discount_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('delivery_challan_items si')
                        ->join('delivery_challan s','s.id = si.delivery_challan_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('si.discount_id',$discount_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_delivery_challan_item_records_by_product_id($product_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('delivery_challan_items si')
                        ->join('delivery_challan s','s.id = si.delivery_challan_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('si.product_id',$product_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_delivery_challan_item_records_by_tax_id($tax_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('delivery_challan_items si')
                        ->join('delivery_challan s','s.id = si.delivery_challan_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('si.tax_id',$tax_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function add_delivery_challan_item_record($data)
    {
        if($this->db->insert('delivery_challan_items',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_delivery_challan_item_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('delivery_challan_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function remove_delivery_challan_item_records($delivery_challans_id)
    {   
        $this->db->where('delivery_challan_id',$delivery_challans_id);
        if($this->db->delete('delivery_challan_items'))
            return true;
        else
            return false;
    }

  
    /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->ion_auth->user($user_id)->row()->email; 
        $customer = $this->utility_model->get_records_by_field('customer','email',$user,$row = true,$check_delete_status = false);


        // echo $this->db->last_query();
        // exit;

        $this->db->select('s.*');
        $this->db->from($this->table.' s');

        if($this->is_customer == true)
        {
          $this->db->where('s.customer_id',$customer->id);
					$this->db->or_where('s.user_id',$user_id);
        }
        // else
        // {
        //   $this->db->where('s.user_id',$user_id);
        // }

        $this->db->where('s.delete_status',0);    

 
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
    $user_id = $this->session->userdata('user_id');
    $udata = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();

    $this->_get_datatables_query();

    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }

    if ($_POST['status'] != '') {
        $this->db->where('status', $_POST['status']);
    }

    $this->db->where('delete_status', 0);

    if ($udata && $udata->branch_id) {
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
   


		public function get_hold_quantity_by_product_id($warehouse_product_id)
		{
        $sold_quantity = 0;
        $delivered_quantity = 0;

				$this->db->select('SUM(si.quantity) as sold_quantity');
				$this->db->from('delivery_challan_items si');
        $this->db->join('delivery_challan s','s.id = si.delivery_challan_id');
				$this->db->where('si.warehouse_product_id', $warehouse_product_id);				
				$this->db->where('s.delete_status', 0);				
				$this->db->group_by('si.warehouse_product_id');

				$query = $this->db->get();

        if($query->row() != NULL)
				  $sold_quantity =  $query->row()->sold_quantity;

				$this->db->select('SUM(si.quantity) as delivered_quantity');
				$this->db->from('delivery_challan_delivery_items si');
				$this->db->where('si.warehouse_product_id', $warehouse_product_id);				
				$this->db->group_by('si.warehouse_product_id');

				$query = $this->db->get();

        if($query->row() != NULL)
				  $delivered_quantity =  $query->row()->delivered_quantity;

				$hold_quantity = $sold_quantity - $delivered_quantity;

				return ($hold_quantity);

		}

  
    public function get_lastest_sequence_number()
    {
      $this->db->select('*');
      $this->db->from('delivery_challan');
      // $this->db->where('delete_status',0);

      $query = $this->db->get();
      $data = $query->result();

       // Retrieve the date format from session
       $dateformat = csession('delivery_challan_date_format');  // This is expected to be a valid date format string

       // Validate the date format and format the date
       $testDate = date_create_from_format($dateformat, '2024-01-01'); // Use any valid date for testing
       if ($testDate === false) {
           $date = ''; // If the date format is invalid, do not include a date
       } else {
           $date = date($dateformat);
       }

       // Constructing the sequence number
       $sequenceNumber = csession('delivery_challan_sequence') + count($data) + 1;
       $formattedSequence = csession('delivery_challan_prefix') . csession('seperator') . $date . csession('seperator') . $sequenceNumber;

       return $formattedSequence;
    }

    public function get_total_delivery_challan() 
{
    $user_id = $this->session->userdata('user_id');
    $user = $this->ion_auth->user($user_id)->row()->email;
    $customer = $this->utility_model->get_records_by_field('customer', 'email', $user, $row = true, $check_delete_status = false);
    
    $branch = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
    $branch_id = $branch ? $branch->branch_id : null;

    $this->db->where('delete_status', 0);

    if ($this->is_customer == true) {
        $this->db->group_start();
            $this->db->where('customer_id', $customer->id);
            $this->db->or_where('user_id', $user_id);
        $this->db->group_end();
    }

    if (!empty($branch_id)) {
        $this->db->where('warehouse_id', $branch_id);
    }

    $query = $this->db->get('delivery_challan');
    return $query->num_rows();
}



    
}
?>
