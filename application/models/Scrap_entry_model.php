<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scrap_entry_model extends CI_Model {

    var $table = 'scrap_entry_view';
    var $column_order = array(
                                'reference_no',
                                                          
                                'total_product',
                                'total_quantity',
                                
                                'scrap_entry_date',
                                
                                'total_discount',
                                'total_taxable_value',
                                
                              ); //set column field database for datatable orderable
    var $column_search = array(
                                'reference_no',
                                                      
                                'total_product',
                                'total_quantity',
                                
                                'scrap_entry_date',
                                
                                'total_discount',
                                'total_taxable_value',
                                
                              ); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
    }

  	public function get_scrap_entry_records($limit = null)
  	{
      if($this->permission_model->has_permission('list_all_purchase'))
      {
        if($limit == null)
        {
            return $this->db->select('
                                    s.*,
                                    c.company_name
                                ')
                        ->from('scrap_entry s')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('s.delete_status',0)
                        ->or_where('s.status','completed')
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
        }
        else
        {
            return $this->db->select('
                                    s.*,
                                    c.company_name
                                ')
                        ->from('scrap_entry s')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('s.delete_status',0)
                        ->or_where('s.status','completed')
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
                        ->from('scrap_entry s')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('s.delete_status',0)
                        ->or_where('s.status','completed')
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
                        ->from('scrap_entry s')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('s.delete_status',0)
                        ->or_where('s.status','completed')
                        ->limit($limit)
                        ->order_by('s.created_date','desc')
                        ->where('s.user_id',$user_id)
                        ->get()
                        ->result();
        }
      }
  	}

    
    public function get_scrap_entry_records_by_supplier_id($supplier_id)
    {
        return $this->db->select('
                                    s.*,
                                    c.company_name
                                ')
                        ->from('scrap_entry s')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.supplier_id',$supplier_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
    }



    public function get_scrap_entry_records_by_warehouse_id($warehouse_id)
    {
        return $this->db->select('
                                    s.*,
                                    c.company_name
                                ')
                        ->from('scrap_entry s')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.warehouse_id',$warehouse_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
    }

    function get_total_scrap_entry_amount($from = null, $to = null)
    {
      $data  = $this->db->select('
                                  SUM(total) AS total,
                                  SUM(total_tax) as total_tax, 
                                  SUM(total_discount) as total_discount, 
                                  SUM(total_taxable_value) as total_taxable_value
                              ')
                       ->from('scrap_entry s')
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

    function get_total_no_of_case($scrap_entry = array())
    {
      $total_no_of_case = 0;

      $scrap_entry_items = $this->get_scrap_entry_item_records($scrap_entry,true);

      foreach ($scrap_entry_items as $value) {
        $total_no_of_case += $value->no_of_case;
      }

      return $total_no_of_case;
    }

    public function add_scrap_entry_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
        if($this->db->insert('scrap_entry',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_scrap_entry_record($data,$id)
    {   
      $this->db->where('id',$id);
      if($this->db->update('scrap_entry',$data))
      {
          return true;
      }
      else
      {
          return false;
      }
    }

  

    public function get_scrap_entry_single_record($id)
    {
      return $this->db->select('s.*')
                      ->from('scrap_entry s')
                    
                      ->where('s.delete_status',0)
                      ->where('s.id',$id)
                      ->get()
                      ->row();
    }

    public function get_scrap_entry_single_record_by_view($id)
    {
      return $this->db->select('*')
                      ->from('scrap_entry_view')
                      ->where('id',$id)
                      ->get()
                      ->row();
    }

    



    public function get_scrap_entry_single_record_by_reference_no($reference_no)
    {
      return $this->db->select('s.*,c.company_name')
                      ->from('scrap_entry s')
                      ->join('supplier c','c.id = s.supplier_id','LEFT')
                      
                      ->where('s.reference_no',$reference_no)
                      ->get()
                      ->row();
    }

    public function get_scrap_entry_item_records($scrap_entry_id, $is_array = false)
    {
      //$this->db->select('si.*,s.*,c.company_name,p.hsn,p.supplier_id as supplier_name,wp.ptr,wp.ptd,wp.mfg_date,wp.item_code,(SELECT pi.cost FROM purchase_items pi WHERE pi.warehouse_product_id = si.warehouse_product_id AND pi.batch_no = si.batch_no LIMIT 1) as purchase_cost');
      
      $this->db->select('
                        si.*,
                        s.*,
                      
                        p.hsn,
                       
                      
                       
                      ');
                      
      $this->db->from('scrap_entry_items si'); 
      $this->db->join('scrap_entry s','s.id = si.scrap_entry_id');
     
      $this->db->join('product p','p.id = si.product_id','left');
     
      $this->db->join('warehouse_products wp','wp.id = si.warehouse_product_id','left');

      if($is_array == false)
        $this->db->where('s.id',$scrap_entry_id);
      else
        $this->db->where_in('s.id',$scrap_entry_id);

      $this->db->where('s.delete_status',0);
      $this->db->order_by('si.product_name', "asc");
      
      $query = $this->db->get();
      return $query->result();
    }

    public function get_single_scrap_entry_item_record($scrap_entry_id, $product_id)
    {
      return $this->db->select('
                                  si.*,
                                  s.*,
                                  c.company_name,
                                  pr.hsn,
                                  w.name as warehouse_name
                              ')
                      ->from('scrap_entry_items si')
                      ->join('scrap_entry s','s.id = si.scrap_entry_id')
                      ->join('warehouse w','w.id = s.warehouse_id','left')
                      ->join('supplier c','c.id = s.supplier_id','left')
                      ->join('product pr','pr.id = si.product_id','left')
                      ->where('s.id',$scrap_entry_id)
                      ->where('si.product_id',$product_id)
                      ->get()
                      ->row();
    }

    public function get_scrap_entry_tax_individual_and_tot_cost_and_total_price($scrap_entry_id)
    {
      return $this->db->select('
                                  SUM(igst_tax) AS igst_tax,
                                  SUM(cgst_tax) AS cgst_tax,
                                  SUM(sgst_tax) AS sgst_tax,
                                  SUM(quantity*cost) AS total_cost,
                                  SUM((quantity*price)-discount_amount) AS total_price
                              ')
                      ->from('scrap_entry_items si')
                      ->where('si.scrap_entry_id',$scrap_entry_id)
                      ->group_by('si.scrap_entry_id')
                      ->get()
                      ->row();   
    }

    public function get_cost_by_scrap_entry_id($scrap_entry_id)
    {
      return $this->db->select('
                                  SUM(quantity*cost) AS total_cost
                              ')
                      ->from('scrap_entry_items si')
                      ->where('si.scrap_entry_id',$scrap_entry_id)
                      ->group_by('si.scrap_entry_id')
                      ->get()
                      ->row();   
    }

    public function get_scrap_entry_item_records_by_discount_id($discount_id)
    {
      return $this->db->select('
                                  si.*,
                                  s.*,
                                  c.company_name
                              ')
                      ->from('scrap_entry_items si')
                      ->join('scrap_entry s','s.id = si.scrap_entry_id')
                      ->join('supplier c','c.id = s.supplier_id','left')
                      ->where('si.discount_id',$discount_id)
                      ->where('s.delete_status',0)
                      ->get()
                      ->result();
    }

    public function get_scrap_entry_item_records_by_product_id($product_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.company_name
                                ')
                        ->from('scrap_entry_items si')
                        ->join('scrap_entry s','s.id = si.scrap_entry_id')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('si.product_id',$product_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_scrap_entry_item_records_by_tax_id($tax_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.company_name
                                ')
                        ->from('scrap_entry_items si')
                        ->join('scrap_entry s','s.id = si.scrap_entry_id')
                        ->join('supplier c','c.id = s.supplier_id','left')
                        ->where('si.tax_id',$tax_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function add_scrap_entry_item_record($data)
    {
        if($this->db->insert('scrap_entry_items',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_scrap_entry_item_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('scrap_entry_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function remove_scrap_entry_item_records($scrap_entrys_id)
    {   
        $this->db->where('scrap_entry_id',$scrap_entrys_id);
        if($this->db->delete('scrap_entry_items'))
            return true;
        else
            return false;
    }

    public function scrap_entry_by_month_year($year_month)
    {   

        $data = $this->db->select('
                                    SUM(total) AS amount
                                ')
                        ->from('scrap_entry s')
                        ->where('s.delete_status',0)
                        ->like('s.invoice_date',$year_month,'both')
                        ->group_by('DATE_FORMAT(s.invoice_date,"'.$year_month.'")')
                        ->get()
                        ->result_array();
        return $data;       
    }

    /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
       /* $this->db->select*/
        $this->db->from($this->table);
        
        // $this->db->group_start();
        // $this->db->where('delete_status',0);  
        $this->db->where("(status = 'completed' AND delete_status = 0)");
        // $this->db->group_end();
 
        $i = 0;
     
        $searchValue = str_replace(' ', '', $_POST['search']['value']); // Remove spaces from the search term

        foreach ($this->column_search as $item) // loop column 
        {
            if($searchValue) // if the search term exists after removing spaces
            {
                if($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket for OR clauses
                    $this->db->like("REPLACE($item, ' ', '')", $searchValue);
                }
                else
                {
                    $this->db->or_like("REPLACE($item, ' ', '')", $searchValue);
                }
        
                if(count($this->column_search) - 1 == $i) // last loop
                    $this->db->group_end(); // close bracket
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
        $this->_get_datatables_query();
        
        return $this->db->count_all_results();
    }

    /*********************************** End Dynamic Datatable function *****************************************/
    public function get_scrap_entry_record_details_by_reference_no($reference_no)
    {
        $this->db->select('s.*');
        $this->db->from('scrap_entry s');
        $this->db->where('s.reference_no',$reference_no);

        $query = $this->db->get();

        return $query->row();
    }

    public function get_hold_quantity_by_product_id($warehouse_product_id)
    {
      $this->db->select('
                          SUM(si.total_quantity) as hold_quantity
                      ');
      $this->db->from('scrap_entry_items si');
      $this->db->join('scrap_entry s','s.id = si.scrap_entry_id');
      $this->db->where('si.warehouse_product_id',$warehouse_product_id);
      $this->db->where('s.delete_status',0);

     
      
      $this->db->group_by('si.warehouse_product_id');

      $query = $this->db->get();
      return $query->row();
    }

    public function get_single_record_from_view($id)
    {
       return $this->db->select('s.*')
                        ->from('scrap_entry_view s')
                        ->where('s.delete_status',0)
                        ->where('s.id',$id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->row();
    }

    public function get_lastest_sequence_number()
    {
      $this->db->select('*');
      $this->db->from('scrap_entry');
      // $this->db->where('delete_status',0);

      $query = $this->db->get();
      $data = $query->result();

      $dateformat = csession('scrap_entry_date_format');
      
      $date = '';


      if($dateformat == DATE_FORMAT_YM)
        $date = date('Y-m');

      else if($dateformat == DATE_FORMAT_YM)
        $date = date('Y-m');

      else if($dateformat == DATE_FORMAT_YMD)
        $date = date('Y-m-d');

      else if($dateformat == DATE_FORMAT_DMY)
        $date = date('d-m-Y');

      return (csession('scrap_entry_prefix').csession('seperator').$date.csession('seperator').(csession('scrap_entry_sequence')+sizeof($data)+1));
    }

}
?>
