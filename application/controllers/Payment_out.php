<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class payment_out extends MY_Controller {

    private $pdf;
    private $is_supplier;

    public function __construct() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        parent::__construct();
       // $this->is_supplier = $this->supplier_model->is_loggedin_user_is_supplier();
        $this->pdf = new Dompdf();
    }
    
public function index() {
    if(!$this->permission_model->has_permission('list_payment_out')) {
        $this->load->view('errors/html/error_restricted'); 
    } else {
        $data['payment_outs'] = $this->payment_out_model->get_payment_out_records();
        $data['total_payment_out'] = $this->transaction_model->get_total_transaction_amount(null, 'PAYMENT_OUT', 'P');
        
        // $log_data = array(
        //     "user_id" => $this->session->userdata("user_id"),
        //     "module" => "payment_out",
        //     "user_action" => 0,
        //     "description" => "User has viewed list of payment_out."
        // );
        // $this->log_data_model->add_record($log_data);

        $this->load->view('payment_out/list', $data);
    }
}

// public function add() {
//     if(!$this->permission_model->has_permission('add_payment_out')) {
//         $this->load->view('errors/html/error_restricted'); 
//     } else {
//         if($this->input->server('REQUEST_METHOD') === 'POST') {
//             $action = $this->input->post('action');
            
//             if($action == 'save') {
//                 $this->save_payment();
//             } elseif($action == 'link_payment') {
//                 $this->link_payment();
//             }
//         } else {
//             $data['reference_no'] = $this->generate_reference_no();
//             $data['suppliers'] = $this->supplier_model->get_records();
//             $data['bank_accounts'] = $this->ledger_model->get_records([BANK_ACCOUNT_GROUP, CASH_GROUP]);
//             $this->load->view('payment_out/add', $data);
//         }
//     }
// }

public function add() {
    if(!$this->permission_model->has_permission('add_payment_out')) {
        $this->load->view('errors/html/error_restricted'); 
    } else {
        if($this->input->server('REQUEST_METHOD') === 'POST') {
            $action = $this->input->post('action');
            
            if($action == 'save') {
                $this->save_payment();
            } elseif($action == 'link_payment') {
                $this->link_payment();
            }
        } else {
            $data['reference_no'] = $this->generate_reference_no();
            $data['suppliers'] = $this->supplier_model->get_records();
            
            // ============ FIXED CODE ============
            // Get ONLY the 4 bank accounts
            $this->db->select('l.id, l.title, ba.account_number, ba.bank_name');
            $this->db->from('bank_account ba');
            $this->db->join('ledger l', 'l.id = ba.ledger_id');
            $this->db->where('ba.active', 1);
            $this->db->order_by('ba.account_name', 'asc');
            $data['bank_accounts'] = $this->db->get()->result();
            // ============ END FIX ============
            
            $this->load->view('payment_out/add', $data);
        }
    }
}

private function save_payment() {
    $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
    $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
    $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
    $this->form_validation->set_rules('from_account', 'From Account', 'required');
    
    // ONLY FOR NEFT/RTGS validation
if($this->input->post('payment_mode') == '3') {
    $this->form_validation->set_rules('utr_number', 'UTR Number', 'required');
    $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
}
    
    if($this->form_validation->run() == FALSE) {
        $data['suppliers'] = $this->supplier_model->get_records();
        // $data['bank_accounts'] = $this->ledger_model->get_records([BANK_ACCOUNT_GROUP, CASH_GROUP]);
        // Get ONLY the 4 bank accounts (same as add() method)
$this->db->select('l.id, l.title, ba.account_number, ba.bank_name');
$this->db->from('bank_account ba');
$this->db->join('ledger l', 'l.id = ba.ledger_id');
$this->db->where('ba.active', 1);
$this->db->order_by('ba.account_name', 'asc');
$data['bank_accounts'] = $this->db->get()->result();
        $this->load->view('payment_out/add', $data);
    } else {
        // Process payment
        $document = $this->input->post("document");
if (!is_array($document)) {
    $document = array($document);
}
$documents = implode(',', $document);

        $payment_data = array(
            'reference_no' => $this->input->post('reference_no'),
            'payment_date' => date('Y-m-d', strtotime($this->input->post('payment_date'))),
            'supplier_id' => $this->input->post('supplier_id'),
            'amount' => $this->input->post('amount'),
            'payment_mode' => $this->input->post('payment_mode'),
            'notes' => $this->input->post('notes'),
             'document' => $documents, // Add this line
             'utr_number' => $this->input->post('utr_number'), // ADD THIS
    'bank_name' => $this->input->post('bank_name'),   // ADD THIS
            'user_id' => $this->session->userdata('user_id')
        );
        
        $from_account = $this->input->post('from_account');
        $voucher_date = $payment_data['payment_date'];
        
        // Begin transaction
        $this->db->trans_begin();
        
        // Add payment record
        $payment_out_id = $this->payment_out_model->add_payment_out_record($payment_data);
        
        if($payment_out_id) {
            $supplier = $this->supplier_model->get_single_record($payment_data['supplier_id']);
            $to_account = $supplier->ledger_id;
            
            // Create transaction header
            $transaction_header = array(
                "entry_id" => $payment_out_id,
                "module" => "PAYMENT_OUT",
                "type" => "P",
                "amount" => $payment_data['amount'],
                "voucher_date" => $voucher_date,
                "mode" => $payment_data['payment_mode'],
                "from_account" => $from_account,
                "to_account" => $to_account,
                "reference_no" => $payment_data['reference_no'],
                "warehouse_id" => $this->session->userdata('warehouse_id')
            );
            
            if($payment_data['payment_mode'] == '3') {
            $transaction_header['utr_number'] = $this->input->post('utr_number');
            $transaction_header['bank_name'] = $this->input->post('bank_name');
        }
            
            // Add transaction record
            if($transaction_id = $this->transaction_model->add_record($transaction_header)) {
                // Get from ledger (bank/cash)
                $from_ledger = $this->ledger_model->get_single_record($from_account);
                
                // Add from transaction detail (bank/cash)
                $from_transaction_detail = array(
                    "transaction_id" => $transaction_id,
                    "voucher_type" => 'C',
                    "ledger_id" => $from_account,
                    "dr_amount" => $payment_data['amount']
                );
                $this->transaction_model->add_transaction_detail_record($from_transaction_detail);
                
                // Update from ledger account (bank/cash)
                $updated_from_ledger = array('closing_balance' => ($from_ledger->closing_balance - $payment_data['amount']));
                $this->ledger_model->edit_record($updated_from_ledger, $from_account);
                
                // Get to ledger (supplier)
                $to_ledger = $this->ledger_model->get_single_record($to_account);
                
                // Add to transaction detail (supplier)
                $to_transaction_detail = array(
                    "transaction_id" => $transaction_id,
                    "voucher_type" => 'D',
                    "ledger_id" => $to_account,
                    "cr_amount" => $payment_data['amount']
                );
                $this->transaction_model->add_transaction_detail_record($to_transaction_detail);
                
                // Update to ledger account (supplier)
                $updated_to_ledger = array('closing_balance' => ($to_ledger->closing_balance + $payment_data['amount']));
                $this->ledger_model->edit_record($updated_to_ledger, $to_account);
            }
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('failure', 'Payment failed to save.');
            } else {
                $this->db->trans_commit();
                
                // Log the successful transaction
                $log_data = array(
                    "user_id" => $this->session->userdata("user_id"),
                    "module" => "payment_out",
                    "entry_id" => $payment_out_id,
                    "user_action" => 1,
                    "data" => json_encode($payment_data),
                    "description" => 'Payment out entry of '.$payment_data['amount'].' is successfully saved.'
                );
                $this->log_data_model->add_record($log_data);
                
                $this->session->set_flashdata('success', 'Payment saved successfully.');
            }
        } else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('failure', 'Payment failed to save.');
        }
        
        redirect('payment_out');
    }
}

// private function link_payment() {
//     $supplier_id = $this->input->post('supplier_id');
//     $amount = (float)$this->input->post('amount');
//     $reference_no = $this->input->post('reference_no');
//     $payment_date = $this->input->post('payment_date');
//     $payment_mode = $this->input->post('payment_mode');
//     $from_account = $this->input->post('from_account');
    
//     // Get supplier details
//     $supplier = $this->supplier_model->get_single_record($supplier_id);
    
//     // Get supplier unpaid invoices
//     $invoices = $this->payment_out_model->get_supplier_unpaid_invoices($supplier_id);
    
//     // Calculate total due amount
//     $total_due_amount = 0;
//     foreach($invoices as $invoice) {
//         $invoice->due_amount = (float)$invoice->due_amount;
//         $total_due_amount += $invoice->due_amount;
//     }
    
//     $data = array(
//         'supplier_id' => $supplier_id,
//         'supplier_name' => $supplier->company_name,
//         'amount' => $amount,
//         'reference_no' => $reference_no,
//         'payment_date' => $payment_date,
//         'payment_mode' => $payment_mode,
//         'from_account' => $from_account,
//         'invoices' => $invoices,
//         'total_due_amount' => $total_due_amount
//     );
    
//     echo $this->load->view('payment_out/link_payment_modal', $data, true);
// }

private function link_payment() {
    
    //   echo "<div style='background:yellow; padding:10px; margin:10px;'>";
    // echo "<h4>DEBUG: link_payment() received:</h4>";
    // echo "UTR: " . ($this->input->post('utr_number') ?: 'EMPTY') . "<br>";
    // echo "Bank: " . ($this->input->post('bank_name') ?: 'EMPTY') . "<br>";
    // echo "Mode: " . $this->input->post('payment_mode') . "<br>";
    // echo "</div>";
    $supplier_id = $this->input->post('supplier_id');
    $amount = (float)$this->input->post('amount');
    $reference_no = $this->input->post('reference_no');
    $payment_date = $this->input->post('payment_date');
    $payment_mode = $this->input->post('payment_mode');
    $from_account = $this->input->post('from_account');
    
    // GET UTR AND BANK FROM FORM (ADD THESE 2 LINES)
    $utr_number = $this->input->post('utr_number');
    $bank_name = $this->input->post('bank_name');
    
    // Get supplier details
    $supplier = $this->supplier_model->get_single_record($supplier_id);
    
    // Get supplier unpaid invoices
    $invoices = $this->payment_out_model->get_supplier_unpaid_invoices($supplier_id);
    
    // Calculate total due amount
    $total_due_amount = 0;
    foreach($invoices as $invoice) {
        $invoice->due_amount = (float)$invoice->due_amount;
        $total_due_amount += $invoice->due_amount;
    }
    
    $data = array(
        'supplier_id' => $supplier_id,
        'supplier_name' => $supplier->company_name,
        'amount' => $amount,
        'reference_no' => $reference_no,
        'payment_date' => $payment_date,
        'payment_mode' => $payment_mode,
        'from_account' => $from_account,
        'invoices' => $invoices,
        'total_due_amount' => $total_due_amount,
        // ADD THESE 2 LINES TO PASS TO MODAL:
        'utr_number' => $utr_number,
        'bank_name' => $bank_name,
         'document' => $this->input->post('document') // Add this line
    );
    
    echo $this->load->view('payment_out/link_payment_modal', $data, true);
}

public function save_linked_payment() {
    header('Content-Type: application/json');
    
     
    // Custom validation for amount
    $supplier_id = $this->input->post('supplier_id');
    $paid_amount = (float)$this->input->post('amount');
    
    // Get total payment amount from selected invoices
    $selected_invoices = $this->input->post('selected_invoices') ?: [];
    $invoice_amounts = $this->input->post('invoice_amounts') ?: [];
    $total_payment_amount = 0;
    
    foreach($selected_invoices as $purchase_id) {
        $total_payment_amount += (float)($invoice_amounts[$purchase_id] ?? 0);
    }
    
    // Validate that amount > 0
    if ($paid_amount <= 0) {
        echo json_encode([
            'status' => 'error', 
            'message' => '<p>The amount field must contain a number greater than 0</p>'
        ]);
        return;
    }
    
    // Rest of validation
    $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
    $this->form_validation->set_rules('from_account', 'From Account', 'required');
    $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'required');
    $this->form_validation->set_rules('reference_no', 'Reference No', 'required');
    $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
    
    if($this->input->post('payment_mode') == '3') {
    $this->form_validation->set_rules('utr_number', 'UTR Number', 'required');
    $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
}
    
    if($this->form_validation->run() == FALSE) {
        echo json_encode([
            'status' => 'error', 
            'message' => validation_errors()
        ]);
        return;
    }

    try {
        // Prepare payment data
        $date = DateTime::createFromFormat('d-m-Y', $this->input->post('payment_date'));
        if (!$date) {
            throw new Exception('Invalid payment date format');
        }
        
        $document = $this->input->post("document");
if (!is_array($document)) {
    $document = array($document);
}
$documents = implode(',', $document);
        
        $payment_data = [
            'reference_no' => $this->input->post('reference_no'),
            'payment_date' => $date->format('Y-m-d'),
            'supplier_id' => $supplier_id,
            'amount' => $paid_amount,
            'payment_mode' => $this->input->post('payment_mode'),
            'notes' => $this->input->post('notes'),
              'document' => $documents,  // Add this line
             'utr_number' => $this->input->post('utr_number'), // ADD THIS
    'bank_name' => $this->input->post('bank_name'),   // ADD THIS
            'user_id' => $this->session->userdata('user_id')
        ];
        
        $from_account = $this->input->post('from_account');
        $supplier = $this->supplier_model->get_single_record($supplier_id);
        $to_account = $supplier->ledger_id;
        $voucher_date = $payment_data['payment_date'];
        
        // Begin transaction
        $this->db->trans_begin();
        
        // Add payment record
        $payment_out_id = $this->payment_out_model->add_payment_out_record($payment_data);
        
        if(!$payment_out_id) {
            throw new Exception('Failed to create payment record');
        }

        // Record the full paid amount from bank account
        if ($paid_amount > 0) {
            $bank_transaction_header = [
                "entry_id" => $payment_out_id,
                "module" => "PAYMENT_OUT",
                "type" => "R",
                "amount" => $paid_amount,
                "voucher_date" => $voucher_date,
                "mode" => $payment_data['payment_mode'],
                "from_account" => $from_account,
                "to_account" => $to_account,
                "reference_no" => $payment_data['reference_no'],
                "warehouse_id" => $this->session->userdata('warehouse_id')
            ];
            
    
            if($payment_data['payment_mode'] == '3') {
    $bank_transaction_header['utr_number'] = $this->input->post('utr_number');
    $bank_transaction_header['bank_name'] = $this->input->post('bank_name');
}
            $bank_transaction_id = $this->transaction_model->add_record($bank_transaction_header);
            
            // ONLY FOR NEFT/RTGS

            if(!$bank_transaction_id) {
                throw new Exception('Failed to create bank transaction record');
            }
            
            // Get ledgers
            $from_ledger = $this->ledger_model->get_single_record($from_account);
            $to_ledger = $this->ledger_model->get_single_record($to_account);
            
            if (!$from_ledger || !$to_ledger) {
                throw new Exception('Ledger accounts not found');
            }
            
            // Bank debit
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $bank_transaction_id,
                "voucher_type" => 'C',
                "ledger_id" => $from_account,
                "dr_amount" => $paid_amount
            ]);
            
            // Supplier credit
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $bank_transaction_id,
                "voucher_type" => 'D',
                "ledger_id" => $to_account,
                "cr_amount" => $paid_amount
            ]);
            
            // Update ledger balances
            $this->ledger_model->edit_record(
                ['closing_balance' => $from_ledger->closing_balance - $paid_amount], 
                $from_account
            );
            
            $this->ledger_model->edit_record(
                ['closing_balance' => $to_ledger->closing_balance + $paid_amount], 
                $to_account
            );
        }

        $total_distributed = 0;
        
        // Process each selected invoice
        foreach($selected_invoices as $purchase_id) {
            $amount = (float)($invoice_amounts[$purchase_id] ?? 0);
            if($amount <= 0) continue;
            
            // Add payment distribution
            if(!$this->payment_out_model->add_payment_distribution([
                'payment_out_id' => $payment_out_id,
                'purchase_id' => $purchase_id,
                'amount' => $amount
            ])) {
                throw new Exception('Failed to add payment distribution');
            }
            
            $total_distributed += $amount;
            
            // Create internal transaction for invoice payment
            $invoice_transaction_header = [
                "entry_id" => $purchase_id,
                "module" => "P",
                "type" => "P",
                "amount" => $amount,
                "voucher_date" => $voucher_date,
                "mode" => $payment_data['payment_mode'],
                "from_account" => $from_account,
                "to_account" => $to_account,
                "reference_no" => $payment_data['reference_no'],
                "warehouse_id" => $this->session->userdata('warehouse_id')
            ];
            
            if($payment_data['payment_mode'] == '3') {
    $invoice_transaction_header['utr_number'] = $this->input->post('utr_number');
    $invoice_transaction_header['bank_name'] = $this->input->post('bank_name');
}
            $invoice_transaction_id = $this->transaction_model->add_record($invoice_transaction_header);
            
            
            if(!$invoice_transaction_id) {
                throw new Exception('Failed to create invoice payment transaction');
            }
            
            // Bank debit (for invoice payment)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $invoice_transaction_id,
                "voucher_type" => 'C',
                "ledger_id" => $from_account,
                "dr_amount" => $amount
            ]);
            
            // Supplier credit (for invoice payment)
            $this->transaction_model->add_transaction_detail_record([
                "transaction_id" => $invoice_transaction_id,
                "voucher_type" => 'D',
                "ledger_id" => $to_account,
                "cr_amount" => $amount
            ]);
        }
        
        // Check transaction status
        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Database transaction failed');
        }
        
        $this->db->trans_commit();
         $this->session->set_flashdata('success', 'Payment of ₹' . number_format($paid_amount, 2) . ' has been successfully processed.');
        // Log the successful transaction
        $log_data = [
            "user_id" => $this->session->userdata("user_id"),
            "module" => "payment_out",
            "entry_id" => $payment_out_id,
            "user_action" => 1,
            "data" => json_encode($payment_data),
            "description" => 'Payment out entry of '.$payment_data['amount'].' linked to invoices'
        ];
        $this->log_data_model->add_record($log_data);
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Payment successfully processed', 
            //'payment_id' => $payment_out_id
          //  'payment_id' => base64_encode($payment_out_id)  // Encode the ID
             'redirect' => base_url('payment_out'), // Add redirect URL in response
            'payment_id' => base64_encode($payment_out_id)

            ]);
        return;
        
    } catch (Exception $e) {
        $this->db->trans_rollback();
        error_log('Payment processing error: ' . $e->getMessage());
        
        echo json_encode([
            'status' => 'error', 
            'message' => $e->getMessage()
        ]);
        return;
    }
}

public function upload_documents() 
{
    if (isset($_FILES["upload_document"])) 
    {
        $company_setting = $this->company_settings_model->get_company_records();
        $cid = $company_setting->cid; 

        $targetDir = "./assets/documents/$cid/payment_out/";

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $response = array();

        foreach ($_FILES["upload_document"]["tmp_name"] as $key => $tmp_name) {
            $uploadFilename = basename($_FILES["upload_document"]["name"][$key]);
            $microtime = microtime(true);
            $microsecondPrefix = str_replace(".", "_", $microtime);
            $newFilename = $microsecondPrefix . "_" . $uploadFilename;
            $targetFile = $targetDir . $newFilename;

            if (move_uploaded_file($tmp_name, $targetFile)) {
                $response[] = $newFilename;
            } else {
                $response[] = "Error uploading document: " . $_FILES["upload_document"]["error"][$key];
            }
        }

        echo json_encode($response);
    } 
    else 
    {
        echo json_encode(array("error" => "No document file provided."));
    }
}
    
    public function view($id) {
        if(!$this->permission_model->has_permission('view_payment_out')) {
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
            $data['payment_out'] = $this->payment_out_model->get_payment_out_single_record($payment_id);
      
            if(!$data['payment_out']) {
                throw new Exception('Payment not found for ID: '.$payment_id);
            }
            
            $data['distributions'] = $this->payment_out_model->get_payment_distributions($payment_id);
            
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // die;
             $data['company_setting']    = $this->company_settings_model->get_company_records(); 
            $this->load->view('payment_out/view', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Payment view error: '.$e->getMessage());
            $this->session->set_flashdata('error', $e->getMessage());
            redirect('payment_out');
        }
    }

    public function edit($id) {
        if(!$this->permission_model->has_permission('edit_payment_out')) {
            $this->load->view('errors/html/error_restricted'); 
        } else {
            if($this->input->server('REQUEST_METHOD') === 'POST') {
                $action = $this->input->post('action');
                
                if($action == 'update') {
                    $this->update_payment();
                }
            } else {
                try {
                    // Decode ID if necessary
                    $payment_id = $id;
                    if(!is_numeric($id)) {
                        $decoded = base64_decode($id);
                        if($decoded && is_numeric($decoded)) {
                            $payment_id = $decoded;
                        }
                    }
                    
                    $data['payment_out'] = $this->payment_out_model->get_payment_out_single_record($payment_id);
                    
                    if(!$data['payment_out']) {
                        throw new Exception('Payment not found');
                    }
                    
                    // Get from_account from transaction header
                    $transaction = $this->db->select('from_account')
                                            ->from('transaction_header')
                                            ->where('entry_id', $payment_id)
                                            ->where('module', 'PAYMENT_OUT')
                                            ->get()
                                            ->row();
                    
                    $data['payment_out']->from_account = $transaction ? $transaction->from_account : null;
                    
                    $data['suppliers'] = $this->supplier_model->get_records();
                    $this->db->select('l.id, l.title, ba.account_number, ba.bank_name');
                    $this->db->from('bank_account ba');
                    $this->db->join('ledger l', 'l.id = ba.ledger_id');
                    $this->db->where('ba.active', 1);
                    $this->db->order_by('ba.account_name', 'asc');
                    $data['bank_accounts'] = $this->db->get()->result();
                    
                    $this->load->view('payment_out/edit', $data);
                    
                } catch (Exception $e) {
                    log_message('error', 'Payment edit error: '.$e->getMessage());
                    $this->session->set_flashdata('error', $e->getMessage());
                    redirect('payment_out');
                }
            }
        }
    }

    private function update_payment() {
        $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('from_account', 'From Account', 'required');
        
        if($this->input->post('payment_mode') == '3') {
            $this->form_validation->set_rules('utr_number', 'UTR Number', 'required');
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
        }
        
        if($this->form_validation->run() == FALSE) {
            $data['suppliers'] = $this->supplier_model->get_records();
            $this->db->select('l.id, l.title, ba.account_number, ba.bank_name');
            $this->db->from('bank_account ba');
            $this->db->join('ledger l', 'l.id = ba.ledger_id');
            $this->db->where('ba.active', 1);
            $this->db->order_by('ba.account_name', 'asc');
            $data['bank_accounts'] = $this->db->get()->result();
            
            $payment_id = base64_decode($this->input->post('payment_id'));
            $data['payment_out'] = $this->payment_out_model->get_payment_out_single_record($payment_id);
            
            // Get from_account from transaction header
            $transaction = $this->db->select('from_account')
                                    ->from('transaction_header')
                                    ->where('entry_id', $payment_id)
                                    ->where('module', 'PAYMENT_OUT')
                                    ->get()
                                    ->row();
            
            $data['payment_out']->from_account = $transaction ? $transaction->from_account : null;
            
            $this->load->view('payment_out/edit', $data);
        } else {
            try {
                $payment_id = base64_decode($this->input->post('payment_id'));
                $old_payment = $this->payment_out_model->get_payment_out_single_record($payment_id);
                
                $document = $this->input->post("document");
                if (!is_array($document)) {
                    $document = array($document);
                }
                $documents = implode(',', $document);
                
                $update_data = array(
                    'reference_no' => $this->input->post('reference_no'),
                    'payment_date' => date('Y-m-d', strtotime($this->input->post('payment_date'))),
                    'supplier_id' => $this->input->post('supplier_id'),
                    'amount' => $this->input->post('amount'),
                    'payment_mode' => $this->input->post('payment_mode'),
                    'notes' => $this->input->post('notes'),
                    'document' => $documents,
                    'utr_number' => $this->input->post('utr_number'),
                    'bank_name' => $this->input->post('bank_name')
                );
                
                $this->db->trans_begin();
                
                // Update payment record
                if($this->payment_out_model->edit_payment_out_record($payment_id, $update_data)) {
                    
                    // If amount changed, need to update ledger balances
                    if($old_payment->amount != $update_data['amount']) {
                        $amount_difference = $update_data['amount'] - $old_payment->amount;
                        
                        $from_account = $this->input->post('from_account');
                        $supplier = $this->supplier_model->get_single_record($update_data['supplier_id']);
                        $to_account = $supplier->ledger_id;
                        
                        $from_ledger = $this->ledger_model->get_single_record($from_account);
                        $to_ledger = $this->ledger_model->get_single_record($to_account);
                        
                        // Update ledger balances based on amount difference
                        $this->ledger_model->edit_record(
                            ['closing_balance' => $from_ledger->closing_balance - $amount_difference], 
                            $from_account
                        );
                        
                        $this->ledger_model->edit_record(
                            ['closing_balance' => $to_ledger->closing_balance + $amount_difference], 
                            $to_account
                        );
                    }
                    
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('failure', 'Payment update failed.');
                    } else {
                        $this->db->trans_commit();
                        
                        // Log the update
                        $log_data = array(
                            "user_id" => $this->session->userdata("user_id"),
                            "module" => "payment_out",
                            "entry_id" => $payment_id,
                            "user_action" => 2,
                            "data" => json_encode($update_data),
                            "description" => 'Payment out entry of '.$update_data['amount'].' is successfully updated.'
                        );
                        $this->log_data_model->add_record($log_data);
                        
                        $this->session->set_flashdata('success', 'Payment updated successfully.');
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('failure', 'Payment update failed.');
                }
                
                redirect('payment_out');
                
            } catch (Exception $e) {
                log_message('error', 'Payment update error: '.$e->getMessage());
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Error: '.$e->getMessage());
                redirect('payment_out');
            }
        }
    }

    public function delete($id) {
        if(!$this->permission_model->has_permission('delete_payment_out')) {
            $this->load->view('errors/html/error_restricted'); 
        } else {
            if($this->input->server('REQUEST_METHOD') === 'POST') {
                $action = $this->input->post('action');
                
                if($action == 'confirm_delete') {
                    $this->delete_payment_record();
                }
            } else {
                try {
                    // Decode ID if necessary
                    $payment_id = $id;
                    if(!is_numeric($id)) {
                        $decoded = base64_decode($id);
                        if($decoded && is_numeric($decoded)) {
                            $payment_id = $decoded;
                        }
                    }
                    
                    $data['payment_out'] = $this->payment_out_model->get_payment_out_single_record($payment_id);
                    
                    if(!$data['payment_out']) {
                        throw new Exception('Payment not found');
                    }
                    
                    $data['distributions'] = $this->payment_out_model->get_payment_distributions($payment_id);
                    
                    $this->load->view('payment_out/delete', $data);
                    
                } catch (Exception $e) {
                    log_message('error', 'Payment delete error: '.$e->getMessage());
                    $this->session->set_flashdata('error', $e->getMessage());
                    redirect('payment_out');
                }
            }
        }
    }

    /*private function delete_payment_record() {
        try {
            $payment_id = base64_decode($this->input->post('payment_id'));
            $payment = $this->payment_out_model->get_payment_out_single_record($payment_id);
            
            if(!$payment) {
                throw new Exception('Payment not found');
            }
            
            $this->db->trans_begin();
            
            // Get the from and to accounts from transactions
            $transaction = $this->db->select('id, from_account, to_account, amount')
                                    ->from('transaction_header')
                                    ->where('entry_id', $payment_id)
                                    ->where('module', 'PAYMENT_OUT')
                                    ->get()
                                    ->result();
            if(!empty($transactions)) {
    foreach($transactions as $transaction) {
           
                // Reverse ledger entries
                $from_ledger = $this->ledger_model->get_single_record($transaction->from_account);
                $to_ledger = $this->ledger_model->get_single_record($transaction->to_account);
                
                // Reverse ledger balances
                $this->ledger_model->edit_record(
                    ['closing_balance' => $from_ledger->closing_balance + $transaction->amount], 
                    $transaction->from_account
                );
                
                $this->ledger_model->edit_record(
                    ['closing_balance' => $to_ledger->closing_balance - $transaction->amount], 
                    $transaction->to_account
                );
                
                // Delete transaction details
                $this->db->where('transaction_id', $transaction->id);
                $this->db->delete('transaction_detail');
                
                // Delete transaction header
                $this->db->where('id', $transaction->id);
                $this->db->delete('transaction_header');
            }
            }
            // Delete payment distributions
            $this->db->where('payment_out_id', $payment_id);
            $this->db->delete('payment_out_distribution');
            
            // Delete payment record
            if($this->payment_out_model->delete_payment_out_record($payment_id)) {
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('failure', 'Payment deletion failed.');
                } else {
                    $this->db->trans_commit();
                    
                    // Log the deletion
                    $log_data = array(
                        "user_id" => $this->session->userdata("user_id"),
                        "module" => "payment_out",
                        "entry_id" => $payment_id,
                        "user_action" => 3,
                        "data" => json_encode($payment),
                        "description" => 'Payment out entry of '.$payment->amount.' is successfully deleted.'
                    );
                    $this->log_data_model->add_record($log_data);
                    
                    $this->session->set_flashdata('success', 'Payment deleted successfully.');
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('failure', 'Payment deletion failed.');
            }
            
            redirect('payment_out');
            
        } catch (Exception $e) {
            log_message('error', 'Payment deletion error: '.$e->getMessage());
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Error: '.$e->getMessage());
            redirect('payment_out');
        }
    }*/
        /*private function delete_payment_record() {
    try {
        $payment_id = base64_decode($this->input->post('payment_id'));
        $payment = $this->payment_out_model->get_payment_out_single_record($payment_id);
        
        if(!$payment) {
            throw new Exception('Payment not found');
        }
        
        $this->db->trans_begin();
        
        // FIRST: Delete payment distributions
        $this->db->where('payment_out_id', $payment_id);
        $this->db->delete('payment_out_distribution');
        
        // SECOND: Get ALL transaction headers for this payment
        $transactions = $this->db->select('id, from_account, to_account, amount')
                                ->from('transaction_header')
                                ->where('entry_id', $payment_id)
                                ->where('module', 'PAYMENT_OUT')
                                ->get()
                                ->result();
        
        if(!empty($transactions)) {
            foreach($transactions as $transaction) {
                // Reverse ledger balances for each transaction
                $from_ledger = $this->ledger_model->get_single_record($transaction->from_account);
                $to_ledger = $this->ledger_model->get_single_record($transaction->to_account);
                
                if($from_ledger && $to_ledger) {
                    // Reverse ledger balances
                    $this->ledger_model->edit_record(
                        ['closing_balance' => $from_ledger->closing_balance + $transaction->amount], 
                        $transaction->from_account
                    );
                    
                    $this->ledger_model->edit_record(
                        ['closing_balance' => $to_ledger->closing_balance - $transaction->amount], 
                        $transaction->to_account
                    );
                }
                
                // Delete transaction details for this transaction
                $this->db->where('transaction_id', $transaction->id);
                $this->db->delete('transaction_detail');
            }
            
            // Delete all transaction headers for this payment
            $this->db->where('entry_id', $payment_id);
            $this->db->where('module', 'PAYMENT_OUT');
            $this->db->delete('transaction_header');
        }
        
        // FINALLY: Delete the payment record
        if($this->payment_out_model->delete_payment_out_record($payment_id)) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('failure', 'Payment deletion failed.');
            } else {
                $this->db->trans_commit();
                
                // Log the deletion
                $log_data = array(
                    "user_id" => $this->session->userdata("user_id"),
                    "module" => "payment_out",
                    "entry_id" => $payment_id,
                    "user_action" => 3,
                    "data" => json_encode($payment),
                    "description" => 'Payment out entry of '.$payment->amount.' is successfully deleted.'
                );
                $this->log_data_model->add_record($log_data);
                
                $this->session->set_flashdata('success', 'Payment deleted successfully.');
            }
        } else {
            throw new Exception('Failed to delete payment record');
        }
        
        redirect('payment_out');
        
    } catch (Exception $e) {
        log_message('error', 'Payment deletion error: '.$e->getMessage());
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', 'Error: '.$e->getMessage());
        redirect('payment_out');
    }
}*/

private function delete_payment_record() {
    try {
        $payment_id = base64_decode($this->input->post('payment_id'));
        
        // Get payment details first
        $payment = $this->payment_out_model->get_payment_out_single_record($payment_id);
        
        if(!$payment) {
            throw new Exception('Payment not found for ID: ' . $payment_id);
        }
        
        $this->db->trans_begin();
        
        // 1. Delete payment distributions first
        $this->db->where('payment_out_id', $payment_id);
        $this->db->delete('payment_out_distribution');
        log_message('debug', 'Deleted distributions for payment: ' . $payment_id);
        
        // 2. Get ALL transaction headers for this payment
        // FIXED: Use transaction_id as the primary key
        $transactions = $this->db->select('transaction_id, from_account, to_account, amount')
                                ->from('transaction_header')
                                ->where('entry_id', $payment_id)
                                ->where('module', 'PAYMENT_OUT')
                                ->get()
                                ->result();
        
        log_message('debug', 'Found ' . count($transactions) . ' transactions for payment: ' . $payment_id);
        
        if(!empty($transactions)) {
            foreach($transactions as $transaction) {
                // Reverse ledger balances for each transaction
                $from_ledger = $this->ledger_model->get_single_record($transaction->from_account);
                $to_ledger = $this->ledger_model->get_single_record($transaction->to_account);
                
                if($from_ledger && $to_ledger) {
                    // Reverse ledger balances
                    $this->ledger_model->edit_record(
                        ['closing_balance' => $from_ledger->closing_balance + $transaction->amount], 
                        $transaction->from_account
                    );
                    
                    $this->ledger_model->edit_record(
                        ['closing_balance' => $to_ledger->closing_balance - $transaction->amount], 
                        $transaction->to_account
                    );
                }
                
                // Delete transaction details for this transaction
                // FIXED: Use transaction_id instead of id
                $this->db->where('transaction_id', $transaction->transaction_id);
                $this->db->delete('transaction_detail');
                log_message('debug', 'Deleted transaction details for transaction_id: ' . $transaction->transaction_id);
            }
            
            // Delete all transaction headers for this payment
            $this->db->where('entry_id', $payment_id);
            $this->db->where('module', 'PAYMENT_OUT');
            $this->db->delete('transaction_header');
            log_message('debug', 'Deleted transaction headers for payment: ' . $payment_id);
        }
        
        // 3. Finally delete the payment record
        $delete_result = $this->payment_out_model->delete_payment_out_record($payment_id);
        
        if($delete_result) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $error = $this->db->error();
                log_message('error', 'Transaction failed: ' . print_r($error, true));
                $this->session->set_flashdata('failure', 'Payment deletion failed: Database transaction error.');
            } else {
                $this->db->trans_commit();
                
                // Log the deletion
                $log_data = array(
                    "user_id" => $this->session->userdata("user_id"),
                    "module" => "payment_out",
                    "entry_id" => $payment_id,
                    "user_action" => 3,
                    "data" => json_encode($payment),
                    "description" => 'Payment out entry of ' . $payment->amount . ' is successfully deleted.'
                );
                $this->log_data_model->add_record($log_data);
                
                $this->session->set_flashdata('success', 'Payment deleted successfully.');
            }
        } else {
            throw new Exception('Failed to delete payment record from database');
        }
        
        redirect('payment_out');
        
    } catch (Exception $e) {
        log_message('error', 'Payment deletion error: ' . $e->getMessage());
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
        redirect('payment_out');
    }
}
    
    public function generate_pdf($id) {
        if(!$this->permission_model->has_permission('view_payment_out')) {
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
            
            $data['payment_out'] = $this->payment_out_model->get_payment_out_single_record($payment_id);
            
            if(!$data['payment_out']) {
                throw new Exception('Payment not found for ID: '.$payment_id);
            }
            
            $data['distributions'] = $this->payment_out_model->get_payment_distributions($payment_id);
            
            // Load the HTML content
            $html = $this->load->view('payment_out/pdf_receipt', $data, true);
            
            // Generate PDF
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->render();
            
            // Output the PDF for download
            $this->pdf->stream("Payment_Receipt_".$data['payment_out']->reference_no.".pdf", array("Attachment" => true));
            
        } catch (Exception $e) {
            log_message('error', 'PDF generation error: '.$e->getMessage());
            $this->session->set_flashdata('error', $e->getMessage());
            redirect('payment_out');
        }
    }

   /*public function get_supplier_due_amount() {
    $supplier_id = $this->input->post('supplier_id');
    $invoices = $this->payment_out_model->get_supplier_unpaid_invoices($supplier_id);
    $due_amount = array_sum(array_column($invoices, 'due_amount'));
    echo json_encode(['due_amount' => $due_amount]);
}*/
// public function get_supplier_due_amount() {
//     $supplier_id = $this->input->post('supplier_id');
    
//     // Set JSON header
//     $this->output->set_content_type('application/json');
    
//     if(empty($supplier_id)) {
//         echo json_encode(['due_amount' => 0]);
//         return;
//     }
    
//     // Get total purchases amount (using 'total' column)
//     $query = $this->db->query("
//         SELECT SUM(total) as total_purchases 
//         FROM purchase 
//         WHERE supplier_id = " . (int)$supplier_id . " 
//         AND delete_status = 0
//     ");
    
//     $total_purchases = $query->row()->total_purchases ?? 0;
    
//     // Get total payments made to this supplier
//     $payment_query = $this->db->query("
//         SELECT SUM(amount) as total_payments 
//         FROM payment_out 
//         WHERE supplier_id = " . (int)$supplier_id
//     );
    
//     $total_payments = $payment_query->row()->total_payments ?? 0;
    
//     // Calculate due amount
//     $due_amount = max(0, $total_purchases - $total_payments);
    
//     echo json_encode([
//         'due_amount' => $due_amount,
//         'total_purchases' => $total_purchases,
//         'total_payments' => $total_payments,
//         'status' => 'success'
//     ]);
// }

public function get_supplier_due_amount() {
    $supplier_id = $this->input->post('supplier_id');
    
    // Set JSON header
    $this->output->set_content_type('application/json');
    
    if(empty($supplier_id)) {
        echo json_encode(['due_amount' => 0]);
        return;
    }
    
    /**
     * FIX: Use the model function that already filters by 
     * 'Successfully Delivered' (total_delivered >= total_ordered)
     */
    $delivered_invoices = $this->payment_out_model->get_supplier_unpaid_invoices($supplier_id);
    
    $total_due = 0;
    if (!empty($delivered_invoices)) {
        foreach ($delivered_invoices as $invoice) {
            // due_amount is calculated inside the model function
            $total_due += (float)$invoice->due_amount;
        }
    }
    
    // Optional: If you still need the total payments for debug/info
    $payment_query = $this->db->query("
        SELECT SUM(amount) as total_payments 
        FROM payment_out 
        WHERE supplier_id = " . (int)$supplier_id . " 
        AND delete_status = 0"
    );
    $total_payments = $payment_query->row()->total_payments ?? 0;

    echo json_encode([
        'due_amount' => $total_due,
        'total_payments' => $total_payments,
        'status' => 'success',
        'message' => 'Showing due for delivered items only'
    ]);
}

private function generate_reference_no() {
    $prefix = "PAYOUT-";
    $last_payment = $this->db->select('reference_no')
                            ->from('payment_out')
                            ->like('reference_no', $prefix, 'after')
                            ->order_by('id', 'desc')
                            ->limit(1)
                            ->get()
                            ->row();
    
    return $last_payment ? $prefix . str_pad((int)str_replace($prefix, '', $last_payment->reference_no) + 1, 5, '0', STR_PAD_LEFT) 
                        : $prefix . '00001';
}

public function view_transactions($payment_out_id) {
    if(!$this->permission_model->has_permission('view_payment_out')) {
        $this->load->view('errors/html/error_restricted'); 
    } else {
        $payment_out_id = base64_decode($payment_out_id);
        $data['transactions'] = $this->transaction_model->get_transactions_by_payment_out($payment_out_id);
        $data['payment_out'] = $this->payment_out_model->get_payment_out_single_record($payment_out_id);
        
        $this->load->view('payment_out/transactions', $data);
    }
}

public function get_supplier_invoices() {
    $supplier_id = $this->input->post('supplier_id');
    $invoices = $this->payment_out_model->get_supplier_unpaid_invoices($supplier_id);
    echo json_encode($invoices);
}
    
 
}