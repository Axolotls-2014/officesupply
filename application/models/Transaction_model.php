<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function add_record($data)
    {
        $data['user_id'] = $this->session->userdata('user_id');
        
        if($this->db->insert('transaction_header',$data)){
            return  $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }

    public function get_single_record($transaction_id)
    {
        return $this->db->get_where('transaction_header', array("transaction_id " => $transaction_id))->row();
    }
    
    public function get_total_transaction_amount_by_customer($customer_id = null, $module = null, $transaction_type = null)
    {
        
        $user_id = $this->session->userdata('user_id');
        $user = $this->db->get_where('users', ['id' => $user_id])->row();
    
        $this->db->select_sum('amount');
        $this->db->from('transaction_header');
        $this->db->join('sale', 'sale.id = transaction_header.entry_id', 'left');
    
     
        if ($customer_id != null) {
            $this->db->where('sale.customer_id', $customer_id);
        }
    
        if ($module != null) {
            $this->db->where('module', $module);
        }
    
        if ($transaction_type != null) {
            $this->db->where('type', $transaction_type);
        }
    
        if ($user && isset($user->branch_id) && !empty($user->branch_id)) {
            $this->db->where('transaction_header.warehouse_id', $user->branch_id);
        }
    
        $query = $this->db->get();
        $data = $query->row();
    
        return ($data && $data->amount) ? $data->amount : '0.00';
    }

    public function get_total_transaction_amount_by_supplier($supplier_id = null, $module = null, $transaction_type = null)
    {
        $this->db->select_sum('amount');
        $this->db->from('transaction_header');
        $this->db->join('purchase','purchase.id = transaction_header.entry_id','left');
        

        if($supplier_id != null)
        {
            $this->db->where('purchase.supplier_id',$supplier_id);    
        }

        if($module != null)
        {
            $this->db->where('module',$module);    
        }

        if($transaction_type != null)
        {
            $this->db->where('type',$transaction_type);
        }
        
        $query = $this->db->get();
        $data  = $query->row();

        if($data == null)
        {
            return '0.00';
        }
        else
        {
            return $data->amount;
        }
    }

    public function add_transaction_detail_record($data)
    {
        if($this->db->insert('transaction_detail',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }   
    }

    public function get_transaction_detail_record_by_transaction_id_and_ledger_id($transaction_id, $ledger_id)
    {
        $data = $this->db->select('
                                    td.*,
                                    l.title AS ledger_title,
                                    ag.group_title AS account_group_title
                                ')
                         ->from('transaction_detail td')
                         ->join('ledger l','l.id = td.ledger_id','LEFT')
                         ->join('account_group ag','ag.id = l.account_group_id','LEFT')
                         ->where('td.transaction_id',$transaction_id)
                         ->where('td.ledger_id',$ledger_id)
                         ->get()
                         ->row();   
        return $data;
    }

    public function get_transaction_by_ledger_id($ledger_id, $from_date = null, $to_date = null)
    {
        $this->db->select('
                            td.*,
                            l.title AS ledger_title,
                            ag.group_title AS account_group_title,
                            th.type AS transaction_type,
                            th.module AS transaction_module,
                            th.entry_id AS entry_id,
                            th.voucher_date AS voucher_date,
                            th.from_account AS from_account,
                            th.to_account AS to_account
                        ');
        $this->db->from('transaction_detail td');
        $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
        $this->db->join('ledger l','l.id = td.ledger_id','LEFT');
        $this->db->join('account_group ag','ag.id = l.account_group_id','LEFT');
        $this->db->where('td.ledger_id',$ledger_id);

        if($from_date != null)
          $this->db->where('th.voucher_date >=', $from_date);

        if($to_date != null)
          $this->db->where('th.voucher_date <=', $to_date);

        $query = $this->db->get();
        $data  = $query->result();   

        return $data;
    }


    // This function is being used in balancesheet to get the increase and reduce capital entries in Capital Amount

    public function get_transaction_by_ledger_id_and_transaction_type($ledger_id = null, $transaction_type = array(), $year_ending = null)
    {
        $this->db->select('
                            td.*,
                            l.title AS ledger_title,
                            ag.group_title AS account_group_title,
                            th.type AS transaction_type,
                            th.module AS transaction_module,
                            th.entry_id AS entry_id,
                            th.voucher_date AS voucher_date,
                            th.from_account AS from_account,
                            th.to_account AS to_account,
                            th.amount AS transaction_amount
                        ');
        $this->db->from('transaction_detail td');
        $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
        $this->db->join('ledger l','l.id = td.ledger_id','LEFT');
        $this->db->join('account_group ag','ag.id = l.account_group_id','LEFT');
        $this->db->where_in('th.type',$transaction_type);

        if($ledger_id != null)
        {
            $this->db->where('td.ledger_id',$ledger_id);    
        }

        

        if($year_ending != null)
        {
            $this->db->group_start();
                $this->db->where('YEAR(th.voucher_date)',$year_ending);
                $this->db->where('MONTH(th.voucher_date) BETWEEN 1 AND 3');
            $this->db->group_end();
            $this->db->or_group_start();
                $this->db->where('YEAR(th.voucher_date)',$year_ending-1);
                $this->db->where('MONTH(th.voucher_date) BETWEEN 4 AND 12');
            $this->db->group_end();
        }

        $query = $this->db->get();
        $data  = $query->result();   

        return $data;
    }

    public function get_transaction_by_ledger_id_and_module($ledger_id, $transaction_type = array())
    {
        $this->db->select('
                            td.*,
                            l.title AS ledger_title,
                            ag.group_title AS account_group_title,
                            th.type AS transaction_type,
                            th.module AS transaction_module,
                            th.entry_id AS entry_id,
                            th.voucher_date AS voucher_date,
                            th.from_account AS from_account,
                            th.to_account AS to_account,
                            th.reference_no AS reference_no,
                            th.amount AS amount,
                        ');
        $this->db->from('transaction_detail td');
        $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
        $this->db->join('ledger l','l.id = td.ledger_id','LEFT');
        $this->db->join('account_group ag','ag.id = l.account_group_id','LEFT');
        $this->db->where('td.ledger_id',$ledger_id);
        $this->db->where_in('th.module',$transaction_type);

        // if($from_date != null)
        //   $this->db->where('th.voucher_date >=', $from_date);

        // if($to_date != null)
        //   $this->db->where('th.voucher_date <=', $to_date);

        $query = $this->db->get();
        $data  = $query->result();   

        return $data;
    }

    public function get_transaction_by_ledger_id_for_closing_balance($ledger_id,$from_date)
    {
      $this->db->select('
                            td.*,
                            l.title AS ledger_title,
                            ag.group_title AS account_group_title,
                            th.type AS transaction_type,
                            th.module AS transaction_module,
                            th.entry_id AS entry_id,
                            th.voucher_date AS voucher_date,
                            th.from_account AS from_account,
                            th.to_account AS to_account,
                            th.reference_no AS reference_no,
                            th.amount AS amount,
                        ');
        $this->db->from('transaction_detail td');
        $this->db->join('transaction_header th','th.transaction_id = td.transaction_id');
        $this->db->join('ledger l','l.id = td.ledger_id','LEFT');
        $this->db->join('account_group ag','ag.id = l.account_group_id','LEFT');
        $this->db->where('td.ledger_id',$ledger_id);
        $this->db->where('th.voucher_date <', $from_date);

        $query = $this->db->get();
        $data  = $query->result();   

        return $data; 
    }

    // public function get_records_entry_id_and_module_wise($entry_id,$module,$type)
    // {
    //     $data = $this->db->select('th.*,lf.title as from_ledger_title, lt.title as to_ledger_title')
    //                      ->from('transaction_header th')
    //                      ->join('ledger lf','lf.id = th.from_account','LEFT')
    //                      ->join('ledger lt','lt.id = th.to_account','LEFT')
    //                      ->where('th.entry_id',$entry_id)
    //                      ->where('th.module',$module)
    //                      ->where('th.type',$type)
    //                      ->get()
    //                      ->result();   
    //     return $data;
    // }
    
        public function get_records_entry_id_and_module_wise($entry_id,$module)
    {
        $data = $this->db->select('th.*,lf.title as from_ledger_title, lt.title as to_ledger_title')
                         ->from('transaction_header th')
                         ->join('ledger lf','lf.id = th.from_account','LEFT')
                         ->join('ledger lt','lt.id = th.to_account','LEFT')
                         ->where('th.entry_id',$entry_id)
                         ->where('th.module',$module)
                        
                         ->get()
                         ->result();   
        return $data;
    }

    /*
        entry_id : id of specific entry in give module
        module   : it can be module from sales, expense, purchase or bank 
        transaction_type  : it can be type of expense, sales, payment, receipt transaction
    */
    public function get_transactions_by_payment_in($payment_in_id) {
    $this->db->select('th.*, fl.title as from_ledger_title, tl.title as to_ledger_title');
    $this->db->from('transaction_header th');
    $this->db->join('ledger fl', 'fl.id = th.from_account', 'left');
    $this->db->join('ledger tl', 'tl.id = th.to_account', 'left');
    $this->db->where('th.entry_id', $payment_in_id);
    $this->db->where('th.module', 'PAYMENT_IN');
    $this->db->order_by('th.created_date', 'desc');
    return $this->db->get()->result();
}

public function get_total_transaction_amount($entry_id = null, $module = null, $transaction_type = null)
{
    $user_id = $this->session->userdata('user_id');
    $branch = $this->db->select('branch_id')->from('users')->where('id', $user_id)->get()->row();
    
    $this->db->select_sum('amount');
    $this->db->from('transaction_header');
    
    if (isset($branch->branch_id) && !empty($branch->branch_id)) {
        $this->db->where('warehouse_id', $branch->branch_id);
    }

    if ($entry_id != null) {
        $this->db->where('entry_id', $entry_id);    
    }

    if ($module != null) {
        $this->db->where('module', $module);    
    }

    if ($transaction_type != null) {
        $this->db->where('type', $transaction_type);
    }
    
    $query = $this->db->get();
    $data = $query->row();

    if ($data == null || $data->amount === null) {
        return '0.00';
    } else {
        return number_format($data->amount, 2, '.', '');
    }
}

    public function delete_record($transaction_id)
		{
				// Start a transaction
				$this->db->trans_begin();

				// Delete records from transaction_detail based on transaction_id
				$this->db->where('transaction_id', $transaction_id);
				$this->db->delete('transaction_detail');

				// Delete the record from transaction_header
				$this->db->where('transaction_id', $transaction_id);
				$this->db->delete('transaction_header');

				// Check if transaction was successful
				if ($this->db->trans_status() === FALSE) {
						// Rollback the transaction if something went wrong
						$this->db->trans_rollback();
						return false;
				} else {
						// Commit the transaction if everything went right
						$this->db->trans_commit();
						return true;
				}
		}


    // public function delete_record_by_entry_id($entry_id,$module,$transaction_type = null)
    // {
    //     $this->db->where('entry_id',$entry_id);

    //     if($transaction_type != null)
    //     {
    //         $this->db->where('type',$transaction_type);
    //     }
        
    //     $this->db->where('module',$module);
        
    //     if($this->db->delete('transaction_header'))
    //     {
    //       return true;
    //     }
    //     else
    //     {
    //       return false;
    //     }
    // }

		public function delete_record_by_entry_id($entry_id, $module, $transaction_type = null)
		{
				// Fetch transaction_id based on entry_id, module, and transaction_type
				$this->db->select('transaction_id');
				$this->db->from('transaction_header');
				$this->db->where('entry_id', $entry_id);
				$this->db->where('module', $module);

				if ($transaction_type != null) {
						$this->db->where('type', $transaction_type);
				}

				$query = $this->db->get();

				if ($query->num_rows() > 0) {
						// Fetch all transaction_ids
						$transaction_ids = array_column($query->result_array(), 'transaction_id');

						// Delete records from transaction_detail based on transaction_id
						$this->db->where_in('transaction_id', $transaction_ids);
						$this->db->delete('transaction_detail');

						// Delete the record from transaction_header
						$this->db->where('entry_id', $entry_id);

						if ($transaction_type != null) {
								$this->db->where('type', $transaction_type);
						}

						$this->db->where('module', $module);

						if ($this->db->delete('transaction_header')) {
								return true;
						} else {
								return false;
						}
				} else {
						return false; // No records found
				}
		}


    public function get_transaction_by_transaction_type($transaction_type = array())
    {
        $this->db->select('
                            th.*
                        ');
        $this->db->from('transaction_header th');
        
        if(!empty($transaction_type))
          $this->db->where_in('th.type',$transaction_type);

        
        $query = $this->db->get();
        $data  = $query->result();   

        return $data;
    }

    public function add_transaction($entry_id,$module,$type,$amount,$voucher_date,$mode,$cheque_no,$credit_card_no,$from_account,$to_account,$reference_no)
    {
            $user_id = $this->session->userdata('user_id');
            $user    = $this->db->get_where('users', ['id' => $user_id])->row();
            if ($user && isset($user->branch_id) && !empty($user->branch_id)) {
                $warehouse_id = $user->branch_id;
            } else{
                $warehouse_id = NULL;
            }
            $transaction_header   = array(
                                        "entry_id"        =>  $entry_id,
                                        "module"          =>  $module,
                                        "type"            =>  $type,
                                        "amount"          =>  $amount,
                                        "voucher_date"    =>  $voucher_date,
                                        "mode"            =>  $mode,
                                        "cheque_no"       =>  $cheque_no,
                                        "credit_card_no"  =>  $credit_card_no,
                                        "from_account"    =>  $from_account,
                                        "to_account"      =>  $to_account,
                                        "warehouse_id"    =>  $warehouse_id,
                                        "reference_no"    =>  $reference_no
                                      );

          // being transaction
          $this->db->trans_begin();

          if($id = $this->transaction_model->add_record($transaction_header))
          {
            if($mode != CREDIT_MODE)
            {
              // Transaction header and detail entry
              $entered_transaction = $this->transaction_model->get_single_record($id);

              /************************************ From ledger *********************************/

              if($from_account != '')
              {
                $from_ledger    = $this->ledger_model->get_single_record($from_account);

                $from_transaction_detail = array(
                                                  "transaction_id"  => $id,
                                                  "voucher_type"    => 'C',
                                                  "ledger_id"       => $from_account,
                                                  "dr_amount"       => $amount
                                                );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($from_transaction_detail);

                // update from ledger account
                $updated_from_ledger  = array('closing_balance' => ($from_ledger->closing_balance - $amount));
                $this->ledger_model->edit_record($updated_from_ledger,$from_account); 
              }
              
              /*********************************************************************************/

              /************************************ To ledger **********************************/

              if($to_account != '')
              {
                $to_ledger      = $this->ledger_model->get_single_record($to_account);
                $to_transaction_detail = array(
                                                "transaction_id"  => $id,
                                                "voucher_type"    => 'D',
                                                "ledger_id"       => $to_account,
                                                "cr_amount"       => $amount
                                              );
                // transaction detail record
                $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                
                // update from ledger account
                if($type == CONTRA_TRANSACTION_TYPE || $type == RECEIPT_TRANSACTION_TYPE || $type == INCREASE_ADJUSTMENT_TRANSACTION_TYPE)
                {
                  $updated_to_ledger    = array('closing_balance' => ($to_ledger->closing_balance + $amount));
                  $this->ledger_model->edit_record($updated_to_ledger,$to_account);   
                }
                else
                {
                  $updated_to_ledger    = array('closing_balance' => ($to_ledger->closing_balance - $amount));
                  $this->ledger_model->edit_record($updated_to_ledger,$to_account);     
                }
              }

              /*********************************************************************************/  
            }
            
          }

          // being transaction
          $this->db->trans_commit();
    }
}
?>
