<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Payment_in extends MY_Controller {

    private $pdf;
    private $is_customer;

    public function __construct() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        parent::__construct();
        $this->is_customer = $this->customer_model->is_loggedin_user_is_customer();
        $this->pdf = new Dompdf();
    }
    
    public function index() {
        $this->config->load('custom_config');
        
        if(!$this->permission_model->has_permission('list_payment_in') && !$this->permission_model->has_permission('list_all_payment_in')) {
            $this->load->view('errors/html/error_restricted'); 
        } else {
            $customer_id = null;
            if($this->is_customer) {
                $user_id = csession('user_id');
                $customer = $this->utility_model->get_records_by_field('customer','user_id',$user_id,$row = true,$check_delete_status = false);
                $customer_id = $customer->id;
            }    
            
            $data['customer'] = $this->customer_model->get_records();
            $data['payment_ins'] = $this->payment_in_model->get_payment_in_records();
            $data['total_payment_in'] = $this->transaction_model->get_total_transaction_amount_by_customer($customer_id, 'S', 'R');            
            
            // $log_data = array(
            //     "user_id" => $this->session->userdata("user_id"),
            //     "module" => "payment_in",
            //     "user_action" => 0,
            //     "description" => "User has viewed list of payment_in."
            // );
            // $this->log_data_model->add_record($log_data);

            $this->load->view('payment_in/list',$data);
        }
    }

    public function add() {
        if($this->input->server('REQUEST_METHOD') === 'POST') {
            
            $action = $this->input->post('action');
            
            if($action == 'save') {
                $this->save_payment();
            } elseif($action == 'link_payment') {
                $this->link_payment();
            }
        } else {
            $data['reference_no'] = $this->generate_reference_no();
            $data['customers'] = $this->customer_model->get_records();
            $data['bank_accounts'] = $this->ledger_model->get_records([BANK_ACCOUNT_GROUP, CASH_GROUP]);
            $this->load->view('payment_in/add', $data);
        }
    }
    
    private function save_payment() {
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('to_account', 'To Account', 'required');
        
        if($this->form_validation->run() == FALSE) {
            $data['customers'] = $this->customer_model->get_records();
            $data['bank_accounts'] = $this->ledger_model->get_records(array(BANK_ACCOUNT_GROUP, CASH_GROUP));
            $this->load->view('payment_in/add', $data);
        } else {
            
            // echo "<pre>";
            // print_r($this->input->post());
            // echo "</pre>";
            // die;
            
            // Process payment
            $payment_data = array(
                'reference_no' => $this->input->post('reference_no'),
                'payment_date' => date('Y-m-d', strtotime($this->input->post('payment_date'))),
                'customer_id' => $this->input->post('customer_id'),
                'amount' => $this->input->post('amount'),
                'payment_mode' => $this->input->post('payment_mode'),
                'notes' => $this->input->post('notes'),
                'user_id' => $this->session->userdata('user_id'),
                'is_wallet_payment' => 1 // Mark as wallet payment
            );
            
            $to_account = $this->input->post('to_account');
            $voucher_date = $payment_data['payment_date'];
            
            // Begin transaction
            $this->db->trans_begin();
            
            // Add payment record
            $payment_in_id = $this->payment_in_model->add_payment_in_record($payment_data);
            
            if($payment_in_id) {
                $customer = $this->customer_model->get_single_record($payment_data['customer_id']);
                $from_account = $customer->ledger_id;
                
                // Create transaction header for the full amount
                $transaction_header = array(
                    "entry_id" => $payment_in_id,
                    "module" => "PAYMENT_IN",
                    "type" => "WALLET_ADD",
                    "amount" => $payment_data['amount'],
                    "voucher_date" => $voucher_date,
                    "mode" => $payment_data['payment_mode'],
                    "from_account" => $from_account,
                    "to_account" => $to_account,
                    "reference_no" => $payment_data['reference_no'],
                    "warehouse_id" => $this->session->userdata('warehouse_id')
                );
                
                // Add transaction record
                if($transaction_id = $this->transaction_model->add_record($transaction_header)) {
                    // Get from ledger (customer)
                    $from_ledger = $this->ledger_model->get_single_record($from_account);
                    
                    // Add from transaction detail (customer)
                    $from_transaction_detail = array(
                        "transaction_id" => $transaction_id,
                        "voucher_type" => 'C',
                        "ledger_id" => $from_account,
                        "dr_amount" => $payment_data['amount']
                    );
                    $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
                    
                    // Update from ledger account (customer)
                    $updated_from_ledger = array('closing_balance' => ($from_ledger->closing_balance - $payment_data['amount']));
                    $this->ledger_model->edit_record($updated_from_ledger, $from_account);
                    
                    // Get to ledger (bank/cash)
                    $to_ledger = $this->ledger_model->get_single_record($to_account);
                    
                    // Add to transaction detail (bank/cash)
                    $to_transaction_detail = array(
                        "transaction_id" => $transaction_id,
                        "voucher_type" => 'D',
                        "ledger_id" => $to_account,
                        "cr_amount" => $payment_data['amount']
                    );
                    $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                    
                    // Update to ledger account (bank/cash)
                    $updated_to_ledger = array('closing_balance' => ($to_ledger->closing_balance + $payment_data['amount']));
                    $this->ledger_model->edit_record($updated_to_ledger, $to_account);
                    
                    // Update customer wallet
                    $this->customer_model->update_wallet_balance($payment_data['customer_id'], $payment_data['amount']);
                }
                
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('failure', 'Payment failed to save.');
                } else {
                    $this->db->trans_commit();
                    
                    // Log the successful transaction
                    $log_data = array(
                        "user_id" => $this->session->userdata("user_id"),
                        "module" => "payment_in",
                        "entry_id" => $payment_in_id,
                        "user_action" => 1,
                        "data" => json_encode($payment_data),
                        "description" => 'Payment entry of '.$payment_data['amount'].' is successfully saved to wallet.'
                    );
                    $this->log_data_model->add_record($log_data);
                    
                    $this->session->set_flashdata('success', 'Payment saved to customer wallet successfully.');
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('failure', 'Payment failed to save.');
            }
            
            redirect('payment_in');
        }
    }
    
    private function link_payment() {
        $customer_id = $this->input->post('customer_id');
        $amount = (float)$this->input->post('amount');
        $reference_no = $this->input->post('reference_no');
        $payment_date = $this->input->post('payment_date');
        $payment_mode = $this->input->post('payment_mode');
        $to_account = $this->input->post('to_account');
        
        // Get customer details including wallet balance
        $customer = $this->customer_model->get_single_record($customer_id);
        $wallet_balance = (float)$customer->wallet_balance;
        
        // Get customer unpaid invoices
        $invoices = $this->payment_in_model->get_customer_unpaid_invoices($customer_id);
        
        // Calculate total due amount
        $total_due_amount = 0;
        foreach($invoices as $invoice) {
            $invoice->due_amount = (float)$invoice->due_amount;
            $total_due_amount += $invoice->due_amount;
        }
        
        $data = array(
            'customer_id' => $customer_id,
            'customer_name' => $customer->customer_name,
            'amount' => $amount,
            'wallet_balance' => $wallet_balance,
            'reference_no' => $reference_no,
            'payment_date' => $payment_date,
            'payment_mode' => $payment_mode,
            'to_account' => $to_account,
            'invoices' => $invoices,
            'total_due_amount' => $total_due_amount
        );
        
        echo $this->load->view('payment_in/link_payment_modal', $data, true);
    }
        
//   public function save_linked_payment() {
//     header('Content-Type: application/json');
    
//     // Custom validation for amount
//     $customer_id = $this->input->post('customer_id');
//     $received_amount = (float)$this->input->post('amount');
//     $customer = $this->customer_model->get_single_record($customer_id);
//     $wallet_balance = (float)$customer->wallet_balance;
    
//     // Get total payment amount from selected invoices
//     $selected_invoices = $this->input->post('selected_invoices') ?: [];
//     $invoice_amounts = $this->input->post('invoice_amounts') ?: [];
//     $total_payment_amount = 0;
    
//     foreach($selected_invoices as $sale_id) {
//         $total_payment_amount += (float)($invoice_amounts[$sale_id] ?? 0);
//     }
    
//     // Validate that either received amount > 0 OR wallet balance is sufficient
//     if ($received_amount <= 0 && $wallet_balance < $total_payment_amount) {
//         echo json_encode([
//             'status' => 'error', 
//             'message' => '<p>The amount field must contain a number greater than 0 when wallet balance is insufficient</p>'
//         ]);
//         return;
//     }
    
//     // Rest of your validation
//     $this->form_validation->set_rules('customer_id', 'Customer', 'required');
//     $this->form_validation->set_rules('to_account', 'To Account', 'required');
//     $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'required');
//     $this->form_validation->set_rules('reference_no', 'Reference No', 'required');
//     $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
    
//     if($this->form_validation->run() == FALSE) {
//         echo json_encode([
//             'status' => 'error', 
//             'message' => validation_errors()
//         ]);
//         return;
//     }

//     try {
//         error_reporting(E_ALL);
//         ini_set('display_errors', 0);
//         ini_set('log_errors', 1);
        
//         // Prepare payment data
//         $date = DateTime::createFromFormat('d-m-Y', $this->input->post('payment_date'));
//         if (!$date) {
//             throw new Exception('Invalid payment date format');
//         }
        
//         $payment_data = [
//             'reference_no' => $this->input->post('reference_no'),
//             'payment_date' => $date->format('Y-m-d'),
//             'customer_id' => $customer_id,
//             'amount' => $received_amount,
//             'payment_mode' => $this->input->post('payment_mode'),
//             'notes' => $this->input->post('notes'),
//             'user_id' => $this->session->userdata('user_id'),
//             'is_wallet_payment' => 0
//         ];
        
//         $to_account = $this->input->post('to_account');
//         $from_account = $customer->ledger_id;
//         $voucher_date = $payment_data['payment_date'];
        
//         // Begin transaction
//         $this->db->trans_begin();
        
//         // Add payment record
//         $payment_in_id = $this->payment_in_model->add_payment_in_record($payment_data);
        
//         if(!$payment_in_id) {
//             throw new Exception('Failed to create payment record');
//         }

//         // FIRST: Record the full received amount to bank account
//         if ($received_amount > 0) {
//             $bank_transaction_header = [
//                 "entry_id" => $payment_in_id,
//                 "module" => "PAYMENT_IN",
//                 "type" => "R",
//                 "amount" => $received_amount,
//                 "voucher_date" => $voucher_date,
//                 "mode" => $payment_data['payment_mode'],
//                 "from_account" => $from_account,
//                 "to_account" => $to_account,
//                 "reference_no" => $payment_data['reference_no'],
//                 "warehouse_id" => $this->session->userdata('warehouse_id')
//             ];
            
//             $bank_transaction_id = $this->transaction_model->add_record($bank_transaction_header);
//             if(!$bank_transaction_id) {
//                 throw new Exception('Failed to create bank transaction record');
//             }
            
//             // Get ledgers
//             $from_ledger = $this->ledger_model->get_single_record($from_account);
//             $to_ledger = $this->ledger_model->get_single_record($to_account);
            
//             if (!$from_ledger || !$to_ledger) {
//                 throw new Exception('Ledger accounts not found');
//             }
            
//             // Customer debit
//             $this->transaction_model->add_transaction_detail_record([
//                 "transaction_id" => $bank_transaction_id,
//                 "voucher_type" => 'C',
//                 "ledger_id" => $from_account,
//                 "dr_amount" => $received_amount
//             ]);
            
//             // Bank credit
//             $this->transaction_model->add_transaction_detail_record([
//                 "transaction_id" => $bank_transaction_id,
//                 "voucher_type" => 'D',
//                 "ledger_id" => $to_account,
//                 "cr_amount" => $received_amount
//             ]);
            
//             // Update ledger balances
//             $this->ledger_model->edit_record(
//                 ['closing_balance' => $from_ledger->closing_balance - $received_amount], 
//                 $from_account
//             );
            
//             $this->ledger_model->edit_record(
//                 ['closing_balance' => $to_ledger->closing_balance + $received_amount], 
//                 $to_account
//             );
//         }

//         $total_distributed = 0;
//         $total_wallet_used = 0;
//         $total_cash_applied = 0;
        
//         // Process each selected invoice
//         foreach($selected_invoices as $sale_id) {
//             $amount = (float)($invoice_amounts[$sale_id] ?? 0);
//             if($amount <= 0) continue;
            
//             // Determine how much to use from wallet vs received amount
//             $wallet_amount = min($amount, $wallet_balance);
//             $cash_amount = $amount - $wallet_amount;
            
//             // Add payment distribution
//             if(!$this->payment_in_model->add_payment_distribution([
//                 'payment_in_id' => $payment_in_id,
//                 'sale_id' => $sale_id,
//                 'amount' => $amount,
//                 'wallet_amount' => $wallet_amount,
//                 'cash_amount' => $cash_amount
//             ])) {
//                 throw new Exception('Failed to add payment distribution');
//             }
            
//             $total_distributed += $amount;
//             $total_wallet_used += $wallet_amount;
//             $total_cash_applied += $cash_amount;
//             $wallet_balance -= $wallet_amount;
            
//             // Create internal transaction for invoice payment
//             $invoice_transaction_header = [
//                 "entry_id" => $sale_id,
//                 "module" => "S",
//                 "type" => "R",
//                 "amount" => $amount,
//                 "voucher_date" => $voucher_date,
//                 "mode" => $payment_data['payment_mode'],
//                 "from_account" => $from_account,
//                 "to_account" => $to_account, // Internal to same account
//                 "reference_no" => $payment_data['reference_no'],
//                 "warehouse_id" => $this->session->userdata('warehouse_id')
//             ];
            
//             $invoice_transaction_id = $this->transaction_model->add_record($invoice_transaction_header);
//             if(!$invoice_transaction_id) {
//                 throw new Exception('Failed to create invoice payment transaction');
//             }
            
//             // Customer debit (for invoice payment)
//             $this->transaction_model->add_transaction_detail_record([
//                 "transaction_id" => $invoice_transaction_id,
//                 "voucher_type" => 'C',
//                 "ledger_id" => $from_account,
//                 "dr_amount" => $amount
//             ]);
            
//             // Customer credit (for invoice payment)
//             $this->transaction_model->add_transaction_detail_record([
//                 "transaction_id" => $invoice_transaction_id,
//                 "voucher_type" => 'D',
//                 "ledger_id" => $from_account,
//                 "cr_amount" => $amount
//             ]);
            
//             // Deduct from wallet for this invoice
//             if($wallet_amount > 0) {
//                 $this->customer_model->update_wallet_balance($payment_data['customer_id'], -$wallet_amount);
//             }
//         }
        
//         // Handle remaining received amount (overpayment)
//         $remaining_received = $received_amount - $total_cash_applied;
//         if($remaining_received > 0) {
//             // Add to wallet
//             $this->customer_model->update_wallet_balance($payment_data['customer_id'], $remaining_received);
            
//             // Create transaction for the wallet addition
//             $wallet_transaction_header = [
//                 "entry_id" => $payment_in_id,
//                 "module" => "PAYMENT_IN",
//                 "type" => "WALLET_ADD",
//                 "amount" => $remaining_received,
//                 "voucher_date" => $voucher_date,
//                 "mode" => $payment_data['payment_mode'],
//                 "from_account" => $from_account,
//                 "to_account" => $from_account,
//                 "reference_no" => $payment_data['reference_no'],
//                 "warehouse_id" => $this->session->userdata('warehouse_id')
//             ];
            
//             $wallet_transaction_id = $this->transaction_model->add_record($wallet_transaction_header);
//             if(!$wallet_transaction_id) {
//                 throw new Exception('Failed to create wallet addition transaction');
//             }
            
//             // Customer debit (for wallet addition)
//             $this->transaction_model->add_transaction_detail_record([
//                 "transaction_id" => $wallet_transaction_id,
//                 "voucher_type" => 'C',
//                 "ledger_id" => $from_account,
//                 "dr_amount" => $remaining_received
//             ]);
            
//             // Customer credit (for wallet addition)
//             $this->transaction_model->add_transaction_detail_record([
//                 "transaction_id" => $wallet_transaction_id,
//                 "voucher_type" => 'D',
//                 "ledger_id" => $from_account,
//                 "cr_amount" => $remaining_received
//             ]);
//         }
        
//         // Check transaction status
//         if ($this->db->trans_status() === FALSE) {
//             throw new Exception('Database transaction failed');
//         }
        
//         $this->db->trans_commit();
        
//         // Log the successful transaction
//         $log_data = [
//             "user_id" => $this->session->userdata("user_id"),
//             "module" => "payment_in",
//             "entry_id" => $payment_in_id,
//             "user_action" => 1,
//             "data" => json_encode($payment_data),
//             "description" => 'Payment entry of '.$payment_data['amount'].' linked to invoices'
//         ];
//         $this->log_data_model->add_record($log_data);
        
//         echo json_encode([
//             'status' => 'success', 
//             'message' => 'Payment successfully processed', 
//             'payment_id' => $payment_in_id
//         ]);
//         return;
        
//     } catch (Exception $e) {
//         $this->db->trans_rollback();
//         error_log('Payment processing error: ' . $e->getMessage());
        
//         // Return proper JSON error response
//         echo json_encode([
//             'status' => 'error', 
//             'message' => $e->getMessage()
//         ]);
//         return;
//     }
// }

public function save_linked_payment() {
    header('Content-Type: application/json');
    
    // Custom validation for amount
    $customer_id = $this->input->post('customer_id');
    $received_amount = (float)$this->input->post('amount');
    $customer = $this->customer_model->get_single_record($customer_id);
    $wallet_balance = (float)$customer->wallet_balance;
    
    // Get total payment amount from selected invoices
    $selected_invoices = $this->input->post('selected_invoices') ?: [];
    $invoice_amounts = $this->input->post('invoice_amounts') ?: [];
    $total_payment_amount = 0;
    
    foreach($selected_invoices as $sale_id) {
        $total_payment_amount += (float)($invoice_amounts[$sale_id] ?? 0);
    }
    
    // Validate that either received amount > 0 OR wallet balance is sufficient
    if ($received_amount <= 0 && $wallet_balance < $total_payment_amount) {
        echo json_encode([
            'status' => 'error', 
            'message' => '<p>The amount field must contain a number greater than 0 when wallet balance is insufficient</p>'
        ]);
        return;
    }
    
    // Rest of your validation
    $this->form_validation->set_rules('customer_id', 'Customer', 'required');
    $this->form_validation->set_rules('to_account', 'To Account', 'required');
    $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'required');
    $this->form_validation->set_rules('reference_no', 'Reference No', 'required');
    $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
    
    if($this->form_validation->run() == FALSE) {
        echo json_encode([
            'status' => 'error', 
            'message' => validation_errors()
        ]);
        return;
    }

    try {
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        
        // Prepare payment data
        $date = DateTime::createFromFormat('d-m-Y', $this->input->post('payment_date'));
        if (!$date) {
            throw new Exception('Invalid payment date format');
        }
        
        $payment_data = [
            'reference_no' => $this->input->post('reference_no'),
            'payment_date' => $date->format('Y-m-d'),
            'customer_id' => $customer_id,
            'amount' => $total_payment_amount, // Use total payment amount
            'payment_mode' => $this->input->post('payment_mode'),
            'notes' => $this->input->post('notes'),
            'user_id' => $this->session->userdata('user_id'),
            'is_wallet_payment' => 0
        ];
        
        $to_account = $this->input->post('to_account');
        $from_account = $customer->ledger_id;
        $voucher_date = $payment_data['payment_date'];
        
        // Begin transaction
        $this->db->trans_begin();
        
        // Add payment record
        $payment_in_id = $this->payment_in_model->add_payment_in_record($payment_data);
        
        if(!$payment_in_id) {
            throw new Exception('Failed to create payment record');
        }

        $total_distributed = 0;
        $total_wallet_used = 0;
        $total_cash_applied = 0;
        
        // Process each selected invoice to create payment distributions
        foreach($selected_invoices as $sale_id) {
            $amount = (float)($invoice_amounts[$sale_id] ?? 0);
            if($amount <= 0) continue;
            
            // Determine how much to use from wallet vs received amount
            $wallet_amount = min($amount, $wallet_balance);
            $cash_amount = $amount - $wallet_amount;
            
            // Add payment distribution
            if(!$this->payment_in_model->add_payment_distribution([
                'payment_in_id' => $payment_in_id,
                'sale_id' => $sale_id,
                'amount' => $amount,
                'wallet_amount' => $wallet_amount,
                'cash_amount' => $cash_amount,
                'created_date' => date('Y-m-d H:i:s')
            ])) {
                throw new Exception('Failed to add payment distribution');
            }
            
            $total_distributed += $amount;
            $total_wallet_used += $wallet_amount;
            $total_cash_applied += $cash_amount;
            $wallet_balance -= $wallet_amount;
            
            // Create transaction for each invoice payment (module "S" and type "R")
            $invoice_transaction_header = [
                "entry_id" => $sale_id, // Sale ID as entry_id
                "module" => "S",       // Module as "S" for Sale
                "type" => "R",          // Type as "R" for Receipt
                "amount" => $amount,
                "voucher_date" => $voucher_date,
                "mode" => $payment_data['payment_mode'],
                "from_account" => $from_account,
                "to_account" => $to_account,
                "reference_no" => $payment_data['reference_no'],
                "warehouse_id" => $this->session->userdata('warehouse_id')
            ];
            
            $invoice_transaction_id = $this->transaction_model->add_record($invoice_transaction_header);
            if(!$invoice_transaction_id) {
                throw new Exception('Failed to create invoice payment transaction');
            }
            
            // Customer debit (for invoice payment)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $invoice_transaction_id,
                "voucher_type" => 'C',
                "ledger_id" => $from_account,
                "dr_amount" => $amount
            ]);
            
            // Bank/Cash credit (for invoice payment)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $invoice_transaction_id,
                "voucher_type" => 'D',
                "ledger_id" => $to_account,
                "cr_amount" => $amount
            ]);
        }
        
        // Create MAIN payment transaction (module "PAYMENT_IN" and type "R")
        if($total_payment_amount > 0) {
            $payment_transaction_header = [
                "entry_id" => $payment_in_id,
                "module" => "PAYMENT_IN",
                "type" => "R",
                "amount" => $total_payment_amount,
                "voucher_date" => $voucher_date,
                "mode" => $payment_data['payment_mode'],
                "from_account" => $from_account,
                "to_account" => $to_account,
                "reference_no" => $payment_data['reference_no'],
                "warehouse_id" => $this->session->userdata('warehouse_id')
            ];
            
            $payment_transaction_id = $this->transaction_model->add_record($payment_transaction_header);
            if(!$payment_transaction_id) {
                throw new Exception('Failed to create payment transaction');
            }
            
            // Customer debit (for total payment)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $payment_transaction_id,
                "voucher_type" => 'C',
                "ledger_id" => $from_account,
                "dr_amount" => $total_payment_amount
            ]);
            
            // Bank/Cash credit (for total payment)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $payment_transaction_id,
                "voucher_type" => 'D',
                "ledger_id" => $to_account,
                "cr_amount" => $total_payment_amount
            ]);
            
            // Update ledger balances (only once for the total amount)
            $from_ledger = $this->ledger_model->get_single_record($from_account);
            $to_ledger = $this->ledger_model->get_single_record($to_account);
            
            $this->ledger_model->edit_record(
                ['closing_balance' => $from_ledger->closing_balance - $total_payment_amount], 
                $from_account
            );
            
            $this->ledger_model->edit_record(
                ['closing_balance' => $to_ledger->closing_balance + $total_payment_amount], 
                $to_account
            );
        }
        
        // Handle wallet deductions (if any)
        if($total_wallet_used > 0) {
            $this->customer_model->update_wallet_balance($payment_data['customer_id'], -$total_wallet_used);
            
            // Create transaction for total wallet deduction
            $wallet_transaction_header = [
                "entry_id" => $payment_in_id,
                "module" => "PAYMENT_IN",
                "type" => "WALLET_DEDUCT",
                "amount" => $total_wallet_used,
                "voucher_date" => $voucher_date,
                "mode" => $payment_data['payment_mode'],
                "from_account" => $from_account,
                "to_account" => $from_account, // Internal to same account
                "reference_no" => $payment_data['reference_no'],
                "warehouse_id" => $this->session->userdata('warehouse_id')
            ];
            
            $wallet_transaction_id = $this->transaction_model->add_record($wallet_transaction_header);
            if(!$wallet_transaction_id) {
                throw new Exception('Failed to create wallet deduction transaction');
            }
            
            // Customer debit (for wallet deduction)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $wallet_transaction_id,
                "voucher_type" => 'C',
                "ledger_id" => $from_account,
                "dr_amount" => $total_wallet_used
            ]);
            
            // Customer credit (for wallet deduction)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $wallet_transaction_id,
                "voucher_type" => 'D',
                "ledger_id" => $from_account,
                "cr_amount" => $total_wallet_used
            ]);
        }
        
        // Handle remaining received amount (overpayment)
        $remaining_received = $received_amount - $total_cash_applied;
        if($remaining_received > 0) {
            // Add to wallet
            $this->customer_model->update_wallet_balance($payment_data['customer_id'], $remaining_received);
            
            // Create transaction for the wallet addition
            $wallet_transaction_header = [
                "entry_id" => $payment_in_id,
                "module" => "PAYMENT_IN",
                "type" => "WALLET_ADD",
                "amount" => $remaining_received,
                "voucher_date" => $voucher_date,
                "mode" => $payment_data['payment_mode'],
                "from_account" => $from_account,
                "to_account" => $from_account,
                "reference_no" => $payment_data['reference_no'],
                "warehouse_id" => $this->session->userdata('warehouse_id')
            ];
            
            $wallet_transaction_id = $this->transaction_model->add_record($wallet_transaction_header);
            if(!$wallet_transaction_id) {
                throw new Exception('Failed to create wallet addition transaction');
            }
            
            // Customer debit (for wallet addition)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $wallet_transaction_id,
                "voucher_type" => 'C',
                "ledger_id" => $from_account,
                "dr_amount" => $remaining_received
            ]);
            
            // Customer credit (for wallet addition)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $wallet_transaction_id,
                "voucher_type" => 'D',
                "ledger_id" => $from_account,
                "cr_amount" => $remaining_received
            ]);
        }
        
        // Check transaction status
        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Database transaction failed');
        }
        
        $this->db->trans_commit();
                 $this->session->set_flashdata('success', 'Payment of ₹' . number_format($received_amount, 2) . ' has been successfully processed.');

        // Log the successful transaction
        $log_data = [
            "user_id" => $this->session->userdata("user_id"),
            "module" => "payment_in",
            "entry_id" => $payment_in_id,
            "user_action" => 1,
            "data" => json_encode($payment_data),
            "description" => 'Payment entry of '.$payment_data['amount'].' linked to invoices'
        ];
        $this->log_data_model->add_record($log_data);
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Payment successfully processed', 
            //'payment_id' => $payment_in_id
        'redirect' => base_url('payment_in'), // Add redirect URL in response
            'payment_id' => base64_encode($payment_out_id)
            ]);
        return;
        
    } catch (Exception $e) {
        $this->db->trans_rollback();
        error_log('Payment processing error: ' . $e->getMessage());
        
        // Return proper JSON error response
        echo json_encode([
            'status' => 'error', 
            'message' => $e->getMessage()
        ]);
        return;
    }
}
    
public function view($id) {
    if(!$this->permission_model->has_permission('view_payment_in')) {
        $this->load->view('errors/html/error_restricted');
        return;
    }
    
    try {
        // First try raw ID (in case it wasn't encoded)
        $payment_id = $id;
       
        // If it doesn't look like a normal ID, try decoding
        if(!is_numeric($id)) {
            $decoded = base64_decode($id);
            if($decoded && is_numeric($decoded)) {
                $payment_id = $decoded;
            }
        }

        if(!is_numeric($payment_id)) {
            throw new Exception('Invalid payment ID: '.$id);
        }
        $data['payment_in'] = $this->payment_in_model->get_payment_in_single_record($payment_id);
      
        if(!$data['payment_in']) {
            throw new Exception('Payment not found for ID: '.$payment_id);
        }
        $data['company_setting']    = $this->company_settings_model->get_company_records(); 
        $data['distributions'] = $this->payment_in_model->get_payment_distributions($payment_id);
        $this->load->view('payment_in/view', $data);
        
    } catch (Exception $e) {
        log_message('error', 'Payment view error: '.$e->getMessage());
        $this->session->set_flashdata('error', $e->getMessage());
        redirect('payment_in');
    }
}

public function generate_pdf($id) {
    if(!$this->permission_model->has_permission('view_payment_in')) {
        show_error('You are not authorized to view this page', 403);
    }
    
    try {
        // First try raw ID (in case it wasn't encoded)
        $payment_id = $id;
        
        // If it doesn't look like a normal ID, try decoding
        if(!is_numeric($id)) {
            $decoded = base64_decode($id);
            if($decoded && is_numeric($decoded)) {
                $payment_id = $decoded;
            }
        }
        
        if(!is_numeric($payment_id)) {
            throw new Exception('Invalid payment ID: '.$id);
        }
        
        $data['payment_in'] = $this->payment_in_model->get_payment_in_single_record($payment_id);
        
        if(!$data['payment_in']) {
            throw new Exception('Payment not found for ID: '.$payment_id);
        }
        
        $data['distributions'] = $this->payment_in_model->get_payment_distributions($payment_id);
        
        // Load the HTML content
        $html = $this->load->view('payment_in/pdf_receipt', $data, true);
        
        // Generate PDF
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        
        // Output the PDF for download
        $this->pdf->stream("Payment_Receipt_".$data['payment_in']->reference_no.".pdf", array("Attachment" => true));
        
    } catch (Exception $e) {
        log_message('error', 'PDF generation error: '.$e->getMessage());
        $this->session->set_flashdata('error', $e->getMessage());
        redirect('payment_in');
    }
}

    public function get_customer_due_amount() {
        $customer_id = $this->input->post('customer_id');
        $invoices = $this->payment_in_model->get_customer_unpaid_invoices($customer_id);
        $due_amount = array_sum(array_column($invoices, 'due_amount'));
        echo json_encode(['due_amount' => $due_amount]);
    }

    private function generate_reference_no() {
        $prefix = "PAY-";
        $last_payment = $this->db->select('reference_no')
                                ->from('payment_in')
                                ->like('reference_no', $prefix, 'after')
                                ->order_by('id', 'desc')
                                ->limit(1)
                                ->get()
                                ->row();
        
        return $last_payment ? $prefix . str_pad((int)str_replace($prefix, '', $last_payment->reference_no) + 1, 5, '0', STR_PAD_LEFT) 
                            : $prefix . '00001';
    }

    
    public function view_transactions($payment_in_id) {
        if(!$this->permission_model->has_permission('view_payment_in')) {
            $this->load->view('errors/html/error_restricted'); 
        } else {
            $payment_in_id = base64_decode($payment_in_id);
            $data['transactions'] = $this->transaction_model->get_transactions_by_payment_in($payment_in_id);
            $data['payment_in'] = $this->payment_in_model->get_payment_in_single_record($payment_in_id);
            
            $this->load->view('payment_in/transactions', $data);
        }
    }
    
    public function get_customer_invoices() {
        $customer_id = $this->input->post('customer_id');
        $invoices = $this->payment_in_model->get_customer_unpaid_invoices($customer_id);
        echo json_encode($invoices);
    }
    
 
}