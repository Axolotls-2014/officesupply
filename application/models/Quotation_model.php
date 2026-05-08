<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_model extends CI_Model {

    var $table = 'quotation_view';
    var $column_order = array(
                                'reference_no',
                                'quotation_date',
                                'customer_company_name',
                                'customer_name',
                                'total_taxable_value',
                                'total_discount',
                                'total_tax',
                                'total',
                                'status
                              ');
    var $column_search = array(
                                'reference_no',
                                'quotation_date',
                                'customer_company_name',
                                'customer_name',
                                'total_taxable_value',
                                'total_discount',
                                'total_tax',
                                'total',
                                'status'
                              );  
    var $order = array('id' => 'desc');  

    function __construct() {
        parent::__construct();
    }

//  public function get_quotation_records($limit = null, $branch_id)
//c.customer_name')
//              ->from('quotation s')
//              ->join('customer c', 'c.id = s.customer_id', 'left')
//              ->where('s.delete_status', 0);
             
   
//     if ($branch_id) {
//         $this->db->where('s.warehouse_id', $branch_id);
//     }

//     if ($limit) {
//         $this->db->limit($limit);
//     }

//     $this->db->order_by('s.created_date', 'desc');

//     return $this->db->get()->result();
// }
public function get_quotation_records($limit = null, $branch_id = null)
{
    $this->db->select('s.*, c.customer_name, c.customer_company_name')
             ->from('quotation s')
             ->join('customer c', 'c.id = s.customer_id', 'left')
             ->where('s.delete_status', 0);

    if (!empty($branch_id)) {
        $this->db->where('s.warehouse_id', $branch_id);
    }

    if (!empty($limit)) {
        $this->db->limit($limit);
    }

    $this->db->order_by('s.created_date', 'desc');

    return $this->db->get()->result();
}

    public function get_quotation_records_by_customer_id($customer_id)
    {
        
            return $this->db->select('
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('quotation s')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.customer_id',$customer_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
       
    }
    
    public function get_quotation_records_by_warehouse_id($warehouse_id)
    {
        
            return $this->db->select('
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('quotation s')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('s.delete_status',0)
                        ->where('s.warehouse_id',$warehouse_id)
                        ->order_by('s.created_date','desc')
                        ->get()
                        ->result();
       
    }

    function get_total_quotation_amount($from = null, $to = null)
    {
        $data  = $this->db->select('
                                    SUM(total) AS total,
                                    SUM(total_tax) as total_tax, 
                                    SUM(total_discount) as total_discount, 
                                    SUM(total_taxable_value) as total_taxable_value
                                ')
                         ->from('quotation s')
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

    public function add_quotation_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
        if($this->db->insert('quotation',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_quotation_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('quotation',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_quotation_single_record($quotation_id)
    {
        return $this->db->select('*')
                        ->from('quotation s')
                        ->where('s.delete_status',0)
                        ->where('s.id',$quotation_id)
                        ->get()
                        ->row();
    }

    public function get_quotation_item_records($quotation_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name,
                                    p.hsn,
                                    p.name as product_name,
                                    p.description as product_description
                                ')
                        ->from('quotation_items si')
                        ->join('quotation s','s.id = si.quotation_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->join('product p','p.id = si.product_id','left')
                        ->where('s.id',$quotation_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_quotation_item_records_by_discount_id($discount_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('quotation_items si')
                        ->join('quotation s','s.id = si.quotation_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('si.discount_id',$discount_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function get_quotation_item_records_by_product_id($product_id)
    {
        return $this->db->select('
                                    si.*,
                                    s.*,
                                    c.customer_name
                                ')
                        ->from('quotation_items si')
                        ->join('quotation s','s.id = si.quotation_id')
                        ->join('customer c','c.id = s.customer_id','left')
                        ->where('si.product_id',$product_id)
                        ->where('s.delete_status',0)
                        ->get()
                        ->result();
    }

    public function add_quotation_item_record($data)
    {
        if($this->db->insert('quotation_items',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function edit_quotation_item_record($data,$id)
    {   
        $this->db->where('id',$id);
        if($this->db->update('quotation_items',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function remove_quotation_item_records($quotations_id)
    {   
        $this->db->where('quotation_id',$quotations_id);
        if($this->db->delete('quotation_items'))
            return true;
        else
            return false;
    }

    public function quotation_by_year($year)
    {


        $data = $this->db->query('
                                    SELECT 
                                        SUM(IF(month_year = "1-?", total, 0)) AS "1",
                                        SUM(IF(month_year = "2-?", total, 0)) AS "2",
                                        SUM(IF(month_year = "3-?", total, 0)) AS "3",
                                        SUM(IF(month_year = "4-?", total, 0)) AS "4",
                                        SUM(IF(month_year = "5-?", total, 0)) AS "5",
                                        SUM(IF(month_year = "6-?", total, 0)) AS "6",
                                        SUM(IF(month_year = "7-?", total, 0)) AS "7",
                                        SUM(IF(month_year = "8-?", total, 0)) AS "8",
                                        SUM(IF(month_year = "9-?", total, 0)) AS "9",
                                        SUM(IF(month_year = "10-?", total, 0)) AS "10",
                                        SUM(IF(month_year = "11-?", total, 0)) AS "11",
                                        SUM(IF(month_year = "12-?", total, 0)) AS "12"
                                        FROM (
                                                SELECT DATE_FORMAT(quotation_date, "%c-%Y") AS month_year,  SUM(total) as total
                                                FROM quotation
                                                GROUP BY  DATE_FORMAT(quotation_date, "%Y-%m") DESC
                                        ) as sub
                               ',
                                    array(
                                            $year,
                                            $year,
                                            $year,
                                            $year,
                                            $year,
                                            $year,
                                            $year,
                                            $year,
                                            $year,
                                            $year,
                                            $year,
                                            $year
                                        ))->result_array();

        // echo $this->db->last_query();
        // exit;

        return $data;       
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
        $user_id    = $this->session->userdata('user_id');
        $udata      = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);

        if($_POST['status'] != '')
          $this->db->where('status',$_POST['status']);        

        $this->db->where('delete_status',0); 
        if($udata->branch_id){
            $this->db->where('warehouse_id', $udata->branch_id); 
        }
        
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();

        if($_POST['status'] != '')
          $this->db->where('status',$_POST['status']);        

        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);

        if($_POST['status'] != '')
          $this->db->where('status',$_POST['status']);        
        
        return $this->db->count_all_results();
    }

    /*********************************** End Dynamic Datatable function *****************************************/
    public function get_lastest_sequence_number()
    {
      $this->db->select('*');
      $this->db->from('quotation');
      // $this->db->where('delete_status',0);

      $query = $this->db->get();
      $data = $query->result();

     // Retrieve the date format from session
     $dateformat = csession('quotation_date_format');  // This is expected to be a valid date format string

     // Validate the date format and format the date
     $testDate = date_create_from_format($dateformat, '2024-01-01'); // Use any valid date for testing
     if ($testDate === false) {
         $date = ''; // If the date format is invalid, do not include a date
     } else {
         $date = date($dateformat);
     }

     // Constructing the sequence number
     $sequenceNumber = csession('quotation_sequence') + count($data) + 1;
     $formattedSequence = csession('quotation_prefix') . csession('seperator') . $date . csession('seperator') . $sequenceNumber;

     return $formattedSequence;
    }

    public function get_quotation_tax_individual($quotation_id)
    {
        return $this->db->select('
                                    SUM(igst_tax) AS igst_tax,
                                    SUM(cgst_tax) AS cgst_tax,
                                    SUM(sgst_tax) AS sgst_tax
                                ')
                        ->from('quotation_items pi')
                        ->where('pi.quotation_id',$quotation_id)
                        ->group_by('pi.quotation_id')
                        ->get()
                        ->row();   
    }

}
?>
