<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/
$autoload['packages'] = array('composer_autoload');

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory, with the addition of the
| 'database' library, which is somewhat of a special case.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'email', 'session');
|
| You can also supply an alternative library name to be assigned
| in the controller:
|
|	$autoload['libraries'] = array('user_agent' => 'ua');
*/
$autoload['libraries'] = array('database','session','ion_auth','form_validation','numbertowords','upload','zip','CSVReader');

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| These classes are located in system/libraries/ or in your
| application/libraries/ directory, but are also placed inside their
| own subdirectory and they extend the CI_Driver_Library class. They
| offer multiple interchangeable driver options.
|
| Prototype:
|
|	$autoload['drivers'] = array('cache');
|
| You can also supply an alternative property name to be assigned in
| the controller:
|
|	$autoload['drivers'] = array('cache' => 'cch');
|
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/
$autoload['helper'] = array(
							'url',
							'file',
							'form',
							'html',
							'text',
							'common_helper',
							'whatsapp_helper'
						);

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/
$autoload['config'] = array('custom_config');

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/
$autoload['language'] = array(	
								'user',
								'user_role',
								'ion_auth',
								'auth',
								'sidebar_menu',
								'company_setting',
								'currency',
								'expense_category',
								'service',
								'customer',
								'email_setup',
								'sms_setup',
								'message',
								'discount',
								'tax',
								'supplier',
								'expense',
								'application_settings',
								'sale',
								'dashboard',
								'common',
								'log_data',
								'quotation',
								'reports',
								'gst_return',
								'bank_account',
								'transaction',
								'ledger',
								'warehouse',
								'product',
								'product_category',
								'purchase',
								'sales_return',
								'purchase_return',
								'stock',
								'transfer',
                'item',
                'credit_debit_note',
								'purchase_order',
								'proforma_invoice',
								'email_template',
								'cash_bank_entry',
                'pdc_payment',
                'pdc_receive',
                'scrap_issue',
                'scrap_entry',
                'scrap_receive',
								'bank_statement',
								'custom_field',
                'employee',
                'department',
                'position',
                'attendance',
                
                'deduction',
                'bonus',
                'holiday',
                'weekend',
                'tax_deduction',
                'payroll',
                'advance_salary',
                'leave',
                'whatsapp_template',
                'whatsapp_message',
                'delivery_challan','lead'
							);

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('first_model', 'second_model');
|
| You can also supply an alternative model name to be assigned
| in the controller:
|
|	$autoload['model'] = array('first_model' => 'first');
*/
$autoload['model'] = array(
								'db_model',
								'utility_model',
								'expense_category_model',
								'company_settings_model',
								'currency_model',
								'customer_model',
								'discount_model',
								'email_setup_model',
								'sms_setup_model',
								'ion_auth_model',
								'product_model',
								'tax_model',
								'service_model',
								'expense_model',
								'application_settings_model',
								'sale_model',
								'attachment_model',
								'permission_model',
								'directory_model',
								'supplier_model',
								'log_data_model',
								'quotation_model',
								'report_model',
								'email_model',
								'gst_return_model',
								'bank_account_model',
								'ledger_model',
								'account_group_model',
								'transaction_model',
								'warehouse_model',
								'warehouse_products_model',
								'product_model',
								'product_category_model',
								'purchase_model',
								'purchase_delivery_model',							
								'sale_delivery_model',
								'uom_model',
								'sales_return_model',
								'sales_return_delivery_model',
								'purchase_return_model',
								'purchase_return_delivery_model',
								'upload_model',
								'stock_model',
								'transfer_model',
                'expense_item_model',
                'item_model',
                'product_core_model',
                'credit_debit_note_model',
								'purchase_order_model',
								'proforma_invoice_model',
								'email_template_model',
								'cash_bank_entry_model',
                'pdc_payment_model',
                'pdc_receive_model',
                'scrap_issue_model',
                'scrap_entry_model',
                'scrap_receive_model',
                'scrap_products_model',
								'bank_statement_model',
								'bank_statement_entries_model',
								'custom_field_model',
                'promotion_model',
                'employee_model',
                'department_model',
                'position_model',
                'attendance_model',
               
                'deduction_model',
                'bonus_model',
                'holiday_model',
                'weekend_model',
                'tax_deduction_model',
                'payroll_model',
                'advance_salary_model',
                'leave_model',
                'payroll_history_model',
                'whatsapp_template_model',
                'whatsapp_message_model',
                'delivery_challan_model',
                'payment_in_model',
                'payment_out_model','Lead_model'
							 );
