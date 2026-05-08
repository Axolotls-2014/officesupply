<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_bank_entry_model extends CI_Model {

    var $table = 'cash_bank_entry';
    var $column_order = array(
                              'voucher_type',
                              'voucher_date',
                              'reference_no',
                              'from_account_id',
                              'to_account_id',
                              'amount',
                              'narration'
                            ); //set column field database for datatable orderable
    var $column_search = array(
                              'voucher_type',
                              'voucher_date',
                              'reference_no',
                              'from_account_id',
                              'to_account_id',
                              'amount',
                              'narration'
                            ); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
        
    }

	public function get_records()
	{
		return $this->db->select('*')
                         ->from('cash_bank_entry')
                         ->where('delete_status',0)
                         ->get()
                         ->result();
	}

    public function add_record($data)
    {
      $data['created_date'] = date('Y-m-d H:i:s');
      
      $data['created_by']  = $this->session->userdata('user_id');
      if($this->db->insert('cash_bank_entry',$data)){
          return  $this->db->insert_id();
      }
      else{
          return FALSE;
      }
    }

    public function edit_record($data,$id)
    {   
      $data['updated_date'] = date('Y-m-d H:i:s');
      $data['updated_by']   = $this->session->userdata('user_id');

        $this->db->where('id',$id);
        if($this->db->update('cash_bank_entry',$data)){
            return true;
        }
        else{
            return false;
        }
    }

    public function delete_record($data,$id)
    {
        $this->db->where('id',$id);

        if($this->db->delete('cash_bank_entry'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_single_record($cash_bank_entry_id)
    {
        return $this->db->get_where('cash_bank_entry', array("id" => $cash_bank_entry_id))->row();
    }

       /************************************ Start Dynamic Datatable function ***********************************/

    private function _get_datatables_query()
    {
       /* $this->db->select*/
       $this->db->select($this->table.'.*,ledger.title as from_account_name,l.title as to_account_name');
        $this->db->from($this->table);
        $this->db->join('ledger','ledger.id = '.$this->table.'.from_account_id','left');
        $this->db->join('ledger l','ledger l.id = '.$this->table.'.to_account_id','left');

        $this->db->where('delete_status',NOT_DELETED);
 
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

    // public function get_lastest_sequence_number()
    // {
    //   $this->db->select('*');
    //   $this->db->from('cash_bank_entry');
    //   // $this->db->where('delete_status',0);

    //   $query = $this->db->get();
    //   $data = $query->result();

    //   return BANK_PAYMENT_PREFIX.(CASH_BANK_ENTRY_SEQUENCE+sizeof($data)+1);
    // }

    // public function get_lastest_sequence_number($voucher_type)
    // {
    //     $prefix = '';

    //     switch ($voucher_type) {
    //         case 'bank_payment':
    //             $prefix = csession('bank_payment_prefix');
    //             break;
    //         case 'cash_payment':
    //             $prefix = csession('cash_payment_prefix');;
    //             break;
    //         case 'bank_receipt':
    //             $prefix = csession('bank_receipt_prefix');;
    //             break;
    //         case 'cash_receipt':
    //             $prefix = csession('cash_receipt_prefix');;
    //             break;
    //         case 'contra':
    //           $prefix = csession('contra_prefix');;
    //           break;
    //         default:
    //             // Handle other voucher types or invalid input as needed
    //             break;
    //     }

    //   $this->db->select('*');
    //   $this->db->from('cash_bank_entry');
    //   // $this->db->where('delete_status', 0);

    //   $query = $this->db->get();
    //   $data = $query->result();

    //   return $prefix . (CASH_BANK_ENTRY_SEQUENCE + sizeof($data) + 1);
    // }

    public function get_lastest_sequence_number($voucher_type)
    {

      
      $this->db->select('*');
      $this->db->from('cash_bank_entry');
      // $this->db->where('delete_status', 0);

      $query = $this->db->get();
      $data = $query->result();
      
        $prefix = '';

        switch ($voucher_type) {
          case 'bank_payment':
            $prefix = csession('bank_payment_prefix');
            break;
          case 'cash_payment':
            $prefix = csession('cash_payment_prefix');
            break;
          case 'bank_receipt':
            $prefix = csession('bank_receipt_prefix');
            break;
          case 'cash_receipt':
            $prefix = csession('cash_receipt_prefix');
            break;
          case 'contra':
            $prefix = csession('contra_prefix');
            break;
          default:
            break;
        }

       

        $dateformat = csession($voucher_type.'_date_format');

        $date = '';

        if ($dateformat == DATE_FORMAT_YM) {
            $date = date('Y-m');
        } elseif ($dateformat == DATE_FORMAT_YMD) {
            $date = date('Y-m-d');
        } elseif ($dateformat == DATE_FORMAT_DMY) {
            $date = date('d-m-Y');
        }

        return ($prefix . csession('seperator') . $date . csession('seperator') . (csession($voucher_type.'_sequence') + sizeof($data) + 1));
    }


}
?>
