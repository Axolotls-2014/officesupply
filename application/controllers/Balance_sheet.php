<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance_sheet extends MY_Controller {

    public function __construct() {
        parent::__construct();

    }

    public function index() {
        // Fetch data from models
        $data['bank_accounts']  = $this->bank_account_model->get_records();
        $data['sales']          = $this->sale_model->get_sale_records();
        // $data['purchases']      = $this->purchase_model->get_purchase_records();
        // Fetch other module data as needed

        $trade_payables = $this->purchase_model->get_trade_payables();
        $pdc_payable    = $this->pdc_payment_model->get_pdc_payable();

        // Process data to create balance sheet
        $data['cash_in_hand']       = $this->calculate_cash_in_hand();
        $data['bank_account_total'] = $this->calculate_bank_account_total($data['bank_accounts']);
        $data['trade_receivables']  = $this->calculate_trade_receivables($data['sales']);
        $data['other_receivables']  = $this->calculate_other_receivables();
        $data['inventory']          = $this->calculate_inventory();
        $data['prepaid_expenses']   = $this->calculate_prepaid_expenses();
        $data['pdc_receivable']     = $this->calculate_pdc_receivable();

        $data['total_current_assets'] = $data['cash_in_hand'] + $data['bank_account_total'] + $data['trade_receivables'] + $data['other_receivables'] + $data['inventory'] + $data['prepaid_expenses'] + $data['pdc_receivable'];

        $data['property_plant_equipment'] = $this->calculate_property_plant_equipment();
        $data['intangible_assets']        = $this->calculate_intangible_assets();
        $data['investments']              = $this->calculate_investments();
        $data['other_long_term_assets']   = $this->calculate_other_long_term_assets();

        $data['total_non_current_assets'] = $data['property_plant_equipment'] + $data['intangible_assets'] + $data['investments'] + $data['other_long_term_assets'];

        // $data['total_assets']             = $data['total_current_assets'] + $data['total_non_current_assets'];

        // $data['trade_payables']           = $this->calculate_trade_payables($data['purchases']);
        $data['other_payables']           = $this->calculate_other_payables();
        // $data['pdc_payable']              = $this->calculate_pdc_payable();
        $data['accrued_expenses']         = $this->calculate_accrued_expenses();
        $data['short_term_loans']         = $this->calculate_short_term_loans();

        $data['total_current_liabilities']    = $data['trade_payables'] + $data['other_payables'] + $data['pdc_payable'] + $data['accrued_expenses'] + $data['short_term_loans'];

        $data['long_term_loans']              = $this->calculate_long_term_loans();
        $data['deferred_tax_liabilities']     = $this->calculate_deferred_tax_liabilities();
        $data['other_long_term_liabilities']  = $this->calculate_other_long_term_liabilities();

        $data['total_non_current_liabilities'] = $data['long_term_loans'] + $data['deferred_tax_liabilities'] + $data['other_long_term_liabilities'];

        $data['total_liabilities']              = $data['total_current_liabilities'] + $data['total_non_current_liabilities'];

        $data['share_capital']                  = $this->calculate_share_capital();
        $data['retained_earnings']              = $this->calculate_retained_earnings();
        $data['additional_paid_in_capital']     = $this->calculate_additional_paid_in_capital();
        $data['other_reserves']                 = $this->calculate_other_reserves();

        $data['total_equity']                   = $data['share_capital'] + $data['retained_earnings'] + $data['additional_paid_in_capital'] + $data['other_reserves'];

        $data['total_liabilities_equity']       = $data['total_liabilities'] + $data['total_equity'];

        // Load the view with the data
        $this->load->view('report/balance_sheet', $data);
    }

    private function calculate_cash_in_hand() {
        // Calculate cash in hand
        return 0; // Replace with actual calculation
    }

    private function calculate_bank_account_total($bank_accounts) {
        $total = 0;
        foreach ($bank_accounts as $account) {
            $total += $account->opening_balance; // Assuming 'balance' is the column name
        }
        return $total;
    }

    private function calculate_trade_receivables($sales) {
        $total = 0;
        foreach ($sales as $sale) {
            $total += $sale->total; // Assuming 'total' is the column name
        }
        return $total;
    }

    private function calculate_other_receivables() {
        // Calculate other receivables
        return 0; // Replace with actual calculation
    }

    private function calculate_inventory() {
        // Calculate inventory
        return 0; // Replace with actual calculation
    }

    private function calculate_prepaid_expenses() {
        // Calculate prepaid expenses
        return 0; // Replace with actual calculation
    }

    private function calculate_pdc_receivable() {
        // Calculate PDC receivable
        return 0; // Replace with actual calculation
    }

    private function calculate_property_plant_equipment() {
        // Calculate property, plant, and equipment
        return 0; // Replace with actual calculation
    }

    private function calculate_intangible_assets() {
        // Calculate intangible assets
        return 0; // Replace with actual calculation
    }

    private function calculate_investments() {
        // Calculate investments
        return 0; // Replace with actual calculation
    }

    private function calculate_other_long_term_assets() {
        // Calculate other long-term assets
        return 0; // Replace with actual calculation
    }

    // private function calculate_trade_payables($purchases) {
    //     $total = 0;
    //     foreach ($purchases as $purchase) {
    //         $total += $purchase->total; // Assuming 'total' is the column name
    //     }
    //     return $total;
    // }

    private function calculate_other_payables() {
        // Calculate other payables
        return 0; // Replace with actual calculation
    }

    // private function calculate_pdc_payable() {
    //     // Calculate PDC payable
    //     return 0; // Replace with actual calculation
    // }

    private function calculate_accrued_expenses() {
        // Calculate accrued expenses
        return 0; // Replace with actual calculation
    }

    private function calculate_short_term_loans() {
        // Calculate short-term loans
        return 0; // Replace with actual calculation
    }

    private function calculate_long_term_loans() {
        // Calculate long-term loans
        return 0; // Replace with actual calculation
    }

    private function calculate_deferred_tax_liabilities() {
        // Calculate deferred tax liabilities
        return 0; // Replace with actual calculation
    }

    private function calculate_other_long_term_liabilities() {
        // Calculate other long-term liabilities
        return 0; // Replace with actual calculation
    }

    private function calculate_share_capital() {
        // Calculate share capital
        return 0; // Replace with actual calculation
    }

    private function calculate_retained_earnings() {
        // Calculate retained earnings
        return 0; // Replace with actual calculation
    }

    private function calculate_additional_paid_in_capital() {
        // Calculate additional paid-in capital
        return 0; // Replace with actual calculation
    }

    private function calculate_other_reserves() {
        // Calculate other reserves
        return 0; // Replace with actual calculation
    }
}
?>
