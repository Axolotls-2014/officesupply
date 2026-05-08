<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['wt_keys'] = array(
    'sale_confirmation' => array(
                            'customer_name',
                            'invoice_no',
                            'invoice_date',
                            'invoice_amount',
                            // 'customer_billing_address',
                            // 'customer_shipping_address',
                            'company_name'
                          ),
    'payment_confirmation' => array(
                            'payment_date',
                            'amount_received',
                            'pending_amount',
                            // 'payment_method',
                            'invoice_no',
                            'company_name',
                            'invoice_date',
                            'invoice_amount',
                            'customer_name'
                          ),
    'salary_payslip' => array(
                            'employee_name',                            
                            'employee_code',
                            'month_year',
                            'department',
                            'position',
                            'company_name',
                            'month_year',                            
                            'basic_salary',
                            'earnings',
                            'deduction',
                            'net_salary'                                                   
                          ),
    'payment_reminder' => array(
                            'customer_name',
                            'invoice_no',
                            'invoice_date',
                            'pending_amount',
                            // 'due_date',
                            // 'due_days',
                            'company_name',
                            // 'bank_name',
                            // 'bank_account_number',
                            // 'ifsc_code'
                          )
);
$config['date_format'] = array('Y-m','m-Y','Y-m-d','d-m-Y','Ym','mY','Ymd','dmY');
$config['whatsapp_api_url'] = 'https://wa.gladminds.one/api/send';
$config['whatsapp_api_reconnect_url'] = 'https://wa.gladminds.one/api/send';


                      
