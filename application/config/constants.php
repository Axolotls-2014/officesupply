<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);
defined('DELIVERY_CHALLAN_MODULE') OR define('DELIVERY_CHALLAN_MODULE', 'DC');
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        					OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          					OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         					OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   					OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  					OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') 					OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     					OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       					OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      					OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      					OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

// Default account groups (custom)
defined('CUSTOMER_ACCOUNT_GROUP_ID')    OR define('CUSTOMER_ACCOUNT_GROUP_ID', 4); // Sundry debtor
defined('SUPPLIER_ACCOUNT_GROUP_ID')    OR define('SUPPLIER_ACCOUNT_GROUP_ID', 5); // Sundry creditor
defined('BANK_ACCOUNT_GROUP_ID')      	OR define('BANK_ACCOUNT_GROUP_ID', 6); // Bank Account
defined('CASH_ACCOUNT_GROUP_ID')      	OR define('CASH_ACCOUNT_GROUP_ID', 9); // Bank Account


defined('BANK_ACCOUNT_GROUP') OR define('BANK_ACCOUNT_GROUP', 'OFFICE');
defined('CASH_GROUP') OR define('CASH_GROUP', 'CASH');
// defined('BANK_ACCOUNT_GROUP') 					OR define('BANK_ACCOUNT_GROUP', 'BANK ACCOUNTS'); // Bank Account group title
defined('CAPITAL_ACCOUNT_GROUP') 				OR define('CAPITAL_ACCOUNT_GROUP', 'CAPITAL ACCOUNT'); // Bank Account group title
defined('CASH_GROUP') 									OR define('CASH_GROUP', 'CASH'); // Cash group title
defined('SALARY_GROUP') 								OR define('SALARY_GROUP', 'SALARY'); // Salary group title
defined('SALES_GROUP') 									OR define('SALES_GROUP', 'SALES'); // Sales group title
defined('SALES_RETURN_GROUP') 					OR define('SALES_RETURN_GROUP', 'SALES_RETURN'); // Sales return group title
defined('OFFICE_EXPENSE_GROUP') 				OR define('OFFICE_EXPENSE_GROUP', 'OFFICE EXPENSE'); // office expense group title
defined('SUNDRY_DEBTORS_GROUP') 				OR define('SUNDRY_DEBTORS_GROUP', 'SUNDRY DEBTORS'); // Sundry debtors group title
defined('SUNDRY_CREDITORS_GROUP') 			OR define('SUNDRY_CREDITORS_GROUP', 'SUNDRY CREDITORS'); // Sundry creditor group title

defined('PURCHASE_RETURN_GROUP') 				OR define('PURCHASE_RETURN_GROUP', 'PURCHASE RETURN'); // Purchase return group title
defined('PURCHASE_GROUP') 							OR define('PURCHASE_GROUP', 'PURCHASE'); // Cash group title

defined('CASH_GROUP_LEDGER') 						OR define('CASH_GROUP_LEDGER', 1); // Cash group title

defined('SALE_LEDGER') 									OR define('SALE_LEDGER', 2); // Sale group title
// defined('SALE_LEDGER') 									OR define('SALE_LEDGER', 24); // Sale group title


defined('PURCHASE_LEDGER') 							OR define('PURCHASE_LEDGER', 4); // Purchase group title
defined('PURCHASE_RETURN_LEDGER') 			OR define('PURCHASE_RETURN_LEDGER', 7); // Purchase group title
defined('OTHER_SUPPLIER') 							OR define('OTHER_SUPPLIER', 5); // Other Supplier group title
defined('TDS_LEDGER') 									OR define('TDS_LEDGER', 6); // TDS group title

// Payment In
define('PAYMENT_IN_MODULE', 'PAYMENT_IN');
define('PAYMENT_IN_LEDGER', 1); // Set your ledger ID for payments in



// Payment Out
define('PAYMENT_OUT_MODULE', 'PAYMENT_OUT');
define('PAYMENT_OUT_LEDGER', 1); // Set your ledger ID for payments out

// Payment Modes
define('CASH_MODE', 0);
define('CREDIT_CARD_MODE', 1);
define('CHEQUE_MODE', 2);
define('NEFT_MODE', 3);

// Ledger IDs
define('CASH_LEDGER', 1); // Set your cash ledger ID
define('BANK_LEDGER', 2); // Set your bank ledger ID
// Product quantity filter constants
define('QUANTITY_ALL', 'all');
define('QUANTITY_GREATER_THEN_ZERO', 'greater_then_zero');
define('QUANTITY_ZERO', 'zero');
define('QUANTITY_BELOW_ZERO', 'below_zero');
define('QUANTITY_NEGATIVE', 'negative');

// Expense and Sales and Bank module
defined('SALE_MODULE') 									OR define('SALE_MODULE', 'S'); // Sale module
defined('SALE_RETURN_MODULE') 					OR define('SALE_RETURN_MODULE', 'SR'); // Sale module
defined('EXPENSE_MODULE') 							OR define('EXPENSE_MODULE', 'E'); // Expene module
defined('BANK_MODULE') 									OR define('BANK_MODULE', 'B'); // Bank module
defined('PURCHASE_MODULE') 							OR define('PURCHASE_MODULE', 'P'); // Bank module
defined('PURCHASE_RETURN_MODULE') 			OR define('PURCHASE_RETURN_MODULE', 'PRM'); // Bank module

// Transaction type
defined('PAYMENT_TRANSACTION_TYPE') 						OR define('PAYMENT_TRANSACTION_TYPE', 'P'); // Payment transaction type
defined('SALE_TRANSACTION_TYPE') 								OR define('SALE_TRANSACTION_TYPE', 'S'); //Sale transaction type
defined('SALE_RETURN_TRANSACTION_TYPE') 				OR define('SALE_RETURN_TRANSACTION_TYPE', 'SR'); //Sale transaction type
defined('TDS_TRANSACTION_TYPE') 								OR define('TDS_TRANSACTION_TYPE', 'TDS'); //TDS transaction type
defined('PURCHASE_TRANSACTION_TYPE') 						OR define('PURCHASE_TRANSACTION_TYPE', 'PR'); // Payment transaction type
defined('PURCHASE_RETURN_TRANSACTION_TYPE') 		OR define('PURCHASE_RETURN_TRANSACTION_TYPE', 'PRT'); // Purchase return transaction type
defined('EXPENSE_TRANSACTION_TYPE') 						OR define('EXPENSE_TRANSACTION_TYPE', 'E'); // Expense transaction type
defined('RECEIPT_TRANSACTION_TYPE') 						OR define('RECEIPT_TRANSACTION_TYPE', 'R'); // Receipt transaction type
defined('CONTRA_TRANSACTION_TYPE') 							OR define('CONTRA_TRANSACTION_TYPE', 'C'); // Contra transaction type
defined('REDUCE_ADJUSTMENT_TRANSACTION_TYPE') 	OR define('REDUCE_ADJUSTMENT_TRANSACTION_TYPE', 'AR'); // Reduce transaction type
defined('INCREASE_ADJUSTMENT_TRANSACTION_TYPE')	OR define('INCREASE_ADJUSTMENT_TRANSACTION_TYPE', 'AI'); // Increase transaction type



defined('TAX_INACTIVE')													OR define('TAX_INACTIVE', 0); // Inactive Tax;
defined('TAX_ACTIVE')														OR define('TAX_ACTIVE', 1); // Inactive Tax;

defined('GST_RETURN_FILE_TYPE_CSV')							OR define('GST_RETURN_FILE_TYPE_CSV', 'csv');
defined('GST_RETURN_FILE_TYPE_JSON')						OR define('GST_RETURN_FILE_TYPE_JSON', 'json');

defined('PRODUCT_STATUS_ACTIVE')								OR define('PRODUCT_STATUS_ACTIVE', 'active');
defined('PRODUCT_STATUS_INACTIVE')							OR define('PRODUCT_STATUS_INACTIVE', 'inactive');

defined('TAX_INCLUSIVE')												OR define('TAX_INCLUSIVE', 1); // Inactive Tax;
defined('TAX_EXCLUSIVE')												OR define('TAX_EXCLUSIVE', 0); // Inactive Tax;

defined('WAREHOUSE_STOCK_IN')										OR define('WAREHOUSE_STOCK_IN', 'in');
defined('WAREHOUSE_STOCK_OUT')									OR define('WAREHOUSE_STOCK_OUT', 'out');

defined('WAREHOUSE_IS_DEFAULT_YES')							OR define('WAREHOUSE_IS_DEFAULT_YES', 'yes');
defined('WAREHOUSE_IS_DEFAULT_NO')							OR define('WAREHOUSE_IS_DEFAULT_NO', 'no');

defined('INVOICE_PAID')													OR define('INVOICE_PAID', 'paid');
defined('INVOICE_UNPAID')												OR define('INVOICE_UNPAID', 'unpaid');

defined('B2B')																	OR define('B2B', 'b2b');
defined('B2CL')																	OR define('B2CL', 'b2cl');
defined('B2CS')																	OR define('B2CS', 'b2cs');
defined('CDNR')																	OR define('CDNR', 'cdnr');
defined('CDNUR')																OR define('CDNUR', 'cdnur');


defined('QUOTATION_STATUS_PENDING')							OR define('QUOTATION_STATUS_PENDING', 'pending');
defined('QUOTATION_STATUS_APPROVED')						OR define('QUOTATION_STATUS_APPROVED', 'approved');
defined('QUOTATION_STATUS_REJECTED')						OR define('QUOTATION_STATUS_REJECTED', 'rejected');

define('EXPENSE_SHARE_ALL_INDIA','all_india');
define('EXPENSE_SHARE_NO_SHARING','no_sharing');
define('EXPENSE_SHARE_STATE_WISE','state_wise');

defined('CREDIT_TRANSACTION_TYPE')	 						OR define('CREDIT_TRANSACTION_TYPE', 'CR'); // Increase transaction type
defined('INVOICE_PARTIALLY_PAID')								OR define('INVOICE_PARTIALLY_PAID', 'partially_paid');

define('PID_SEQUENCE',100000);
define('PCID_SEQUENCE',100000);
define('TID_SEQUENCE',100000);

define('CREDIT_NOTE_TAX_INCLUSIVE','inclusive');
define('CREDIT_NOTE_TAX_EXCLUSIVE','exclusive');
define('CREDIT_NOTE_TYPE_DEBIT','debit');
define('CREDIT_NOTE_TYPE_CREDIT','credit');
define('CREDIT_NOTE_TYPE_ADVANCE_REFUND_VOUCHER','advance_refund_voucher');
define('CREDIT_NOTE_TYPE_CREDIT_SUPPLIER','credit_supplier');
define('CREDIT_NOTE_TYPE_DEBIT_CUSTOMER','debit_customer');

defined('QUANTITY_ALL')					              OR define('QUANTITY_ALL', 'all');
defined('QUANTITY_GREATER_THEN_ZERO')					OR define('QUANTITY_GREATER_THEN_ZERO', 'greater_then_zero');
defined('QUANTITY_ZERO')					            OR define('QUANTITY_ZERO', 'zero');
define('PRODUCT_DELETE_STATUS_NOT_DELETED',0);
define('PRODUCT_DELETE_STATUS_DELETED',1);
define('PRODUCT_DELETE_STATUS_ALL',2);

// Default user role
defined('CUSTOMER_GROUP_NAME')    OR define('CUSTOMER_GROUP_NAME', 'customer'); // customer group name
defined('CUSTOMER_GROUP_ID')    OR define('CUSTOMER_GROUP_ID', 21); // customer group id



defined('ACCOUNT_STATUS_ACTIVE')												OR define('ACCOUNT_STATUS_ACTIVE', 1); 
defined('ACCOUNT_STATUS_INACTIVE')												OR define('ACCOUNT_STATUS_INACTIVE', 0); 

defined('PURCHASE_ORDER_MODULE') 									OR define('PURCHASE_ORDER_MODULE', 'PO'); // purchase order module




defined('SEPERATOR_SLASH')												OR define('SEPERATOR_SLASH', '/'); 
defined('SEPERATOR_UNDERSCORE')										    OR define('SEPERATOR_UNDERSCORE', '_'); 
defined('SEPERATOR_DASH')												OR define('SEPERATOR_DASH', '-'); 


defined('PROFORMA_INVOICE_MODULE') 									OR define('PROFORMA_INVOICE_MODULE', 'PI'); // PROFORMA invoice module

defined('MANAGE_INVENTORY_YES') 								OR define('MANAGE_INVENTORY_YES', 'yes'); 
defined('MANAGE_INVENTORY_NO') 									OR define('MANAGE_INVENTORY_NO', 'no'); 

define('RESPONSE_SUCCESS',1);
define('RESPONSE_FAILURE',0);

defined('EMAIL_TEMPLATE_MODULE_QUOTATION') 								OR define('EMAIL_TEMPLATE_MODULE_QUOTATION', 'quotation'); 
defined('EMAIL_TEMPLATE_MODULE_SALE') 									  OR define('EMAIL_TEMPLATE_MODULE_SALE', 'sale'); 
defined('EMAIL_TEMPLATE_MODULE_SALE_RETURN') 							OR define('EMAIL_TEMPLATE_MODULE_SALE_RETURN', 'sale_return'); 
defined('EMAIL_TEMPLATE_MODULE_PURCHASE') 								OR define('EMAIL_TEMPLATE_MODULE_PURCHASE', 'purchase'); 
defined('EMAIL_TEMPLATE_MODULE_PURCHASE_RETURN') 					OR define('EMAIL_TEMPLATE_MODULE_PURCHASE_RETURN', 'purchase_return'); 
defined('EMAIL_TEMPLATE_MODULE_PROFORMA_INVOICE') 				OR define('EMAIL_TEMPLATE_MODULE_PROFORMA_INVOICE', 'proforma_invoice'); 

defined('FROM_EMAIL') 									OR define('FROM_EMAIL', 'zivaansolutions@gmail.com'); 


defined('CREDIT_DEBIT_NOTE_MODULE') 		OR define('CREDIT_DEBIT_NOTE_MODULE', 'CDN'); // Credit Note module
define('DELETED', 1);
define('NOT_DELETED', 0);
define('CREDIT_DEBIT_NOTE_SEQUENCE',500000);

defined('CREDIT_NOTE_TRANSACTION_TYPE')	 			  OR define('CREDIT_NOTE_TRANSACTION_TYPE', 'CN'); // Increase transaction type
defined('DEBIT_NOTE_TRANSACTION_TYPE')	 				OR define('DEBIT_NOTE_TRANSACTION_TYPE', 'DN'); // Increase transaction type


define('HSN_GROUPING_COMBINED','combined');
define('HSN_GROUPING_INDIVIDUAL','individual');

define('SCRAP_ISSUE_SEQUENCE',100000);
define('SCRAP_ENTRY_SEQUENCE',100000);
define('SCRAP_RECEIVE_SEQUENCE',100000);



defined('SCRAP_ISSUE_MODULE') 									OR define('SCRAP_ISSUE_MODULE', 'SIM'); 
defined('SCRAP_RECEIVE_MODULE') 								OR define('SCRAP_RECEIVE_MODULE', 'SRM'); 

defined('EXPENSE_TYPE_RELATED_SCRAP') 					OR define('EXPENSE_TYPE_RELATED_SCRAP', 'related_scrap'); 
defined('EXPENSE_TYPE_NON_RELATED_SCRAP') 			OR define('EXPENSE_TYPE_NON_RELATED_SCRAP', 'non_related_scrap'); 

defined('CASH_BANK_MODULE') 		                OR define('CASH_BANK_MODULE', 'CB'); // Cash bank module
defined('CASH_BANK_ENTRY_SEQUENCE')			        OR define('CASH_BANK_ENTRY_SEQUENCE', 900000);

defined('BANK_PAYMENT_TRANSACTION_TYPE')	 			OR define('BANK_PAYMENT_TRANSACTION_TYPE', 'BP'); // Increase transaction type
defined('BANK_RECEIPT_TRANSACTION_TYPE')	 			OR define('BANK_RECEIPT_TRANSACTION_TYPE', 'BR'); // Increase transaction type
defined('CASH_PAYMENT_TRANSACTION_TYPE')	 			OR define('CASH_PAYMENT_TRANSACTION_TYPE', 'CP'); // Increase transaction type
defined('CASH_RECEIPT_TRANSACTION_TYPE')	 			OR define('CASH_RECEIPT_TRANSACTION_TYPE', 'CR'); // Increase transaction type
defined('CONTRA_TRANSACTION_TYPE')	 			      OR define('CONTRA_TRANSACTION_TYPE', 'CON'); // Increase transaction type

defined('VOUCHER_TYPE_BANK_PAYMENT')			OR define('VOUCHER_TYPE_BANK_PAYMENT', 'bank_payment');
defined('VOUCHER_TYPE_BANK_RECEIPT')			OR define('VOUCHER_TYPE_BANK_RECEIPT', 'bank_receipt');
defined('VOUCHER_TYPE_CASH_PAYMENT')			OR define('VOUCHER_TYPE_CASH_PAYMENT', 'cash_payment');
defined('VOUCHER_TYPE_CASH_RECEIPT')			OR define('VOUCHER_TYPE_CASH_RECEIPT', 'cash_receipt');
defined('VOUCHER_TYPE_CONTRA')			OR define('VOUCHER_TYPE_CONTRA', 'contra');


defined('SCRAP_MODULE') 									OR define('SCRAP_MODULE', 'S'); // Sale module

defined('SCRAP_ENTRY_STATUS_DRAFT')			OR define('SCRAP_ENTRY_STATUS_DRAFT', 'draft');
defined('SCRAP_ENTRY_STATUS_COMPLETED')			OR define('SCRAP_ENTRY_STATUS_COMPLETED', 'completed');

defined('BANK_RECONCILE_STATUS_PENDING')							OR define('BANK_RECONCILE_STATUS_PENDING', 'pending');
defined('BANK_RECONCILE_STATUS_COMPLETED')							OR define('BANK_RECONCILE_STATUS_COMPLETED', 'completed');


define('CASH_MODE',0);
define('CREDIT_CARD_MODE',1);
define('CHEQUE_MODE',2);
define('NEFT_MODE',3);
define('CREDIT_MODE',4);


defined('PROMOTION_TYPE_QUANTITY')						OR define('PROMOTION_TYPE_QUANTITY', 'quantity');
defined('PROMOTION_TYPE_PERCENTAGE')					OR define('PROMOTION_TYPE_PERCENTAGE', 'percentage');

defined('DATE_FORMAT_Y_M')											OR define('DATE_FORMAT_Y_M', 'Y-m'); 
defined('DATE_FORMAT_M_Y')											OR define('DATE_FORMAT_M_Y', 'm-Y'); 
defined('DATE_FORMAT_Y_M_D')										OR define('DATE_FORMAT_Y_M_D', 'Y-m-d'); 
defined('DATE_FORMAT_D_M_Y')										OR define('DATE_FORMAT_D_M_Y', 'd-m-Y'); 
defined('DATE_FORMAT_YM')												OR define('DATE_FORMAT_YM', 'Ym'); 
defined('DATE_FORMAT_MY')												OR define('DATE_FORMAT_MY', 'mY'); 
defined('DATE_FORMAT_YMD')											OR define('DATE_FORMAT_YMD', 'Ymd'); 
defined('DATE_FORMAT_DMY')											OR define('DATE_FORMAT_DMY', 'dmY'); 

defined('PROFORMA_INVOICE_RESTRICTION')					OR define('PROFORMA_INVOICE_RESTRICTION', 'YES'); 

defined('STATUS_PENDING')	    OR define('STATUS_PENDING','pending');
defined('STATUS_APPROVED')	    OR define('STATUS_APPROVED','approved');
defined('STATUS_REJECTED')	    OR define('STATUS_REJECTED','rejected');

defined('ATTENDANCE_STATUS_PRESENT')	    OR define('ATTENDANCE_STATUS_PRESENT', 1);
defined('ATTENDANCE_STATUS_ABSENT')	    OR define('ATTENDANCE_STATUS_ABSENT', 0);
defined('ATTENDANCE_STATUS_HALF_LEAVE')	    OR define('ATTENDANCE_STATUS_HALF_LEAVE', 0.5);
defined('ATTENDANCE_STATUS_ONE_FOURTH_LEAVE')	    OR define('ATTENDANCE_STATUS_ONE_FOURTH_LEAVE',0.25);
defined('ATTENDANCE_STATUS_THIRD_FOURTH_LEAVE')	    OR define('ATTENDANCE_STATUS_THIRD_FOURTH_LEAVE',0.75);



defined('LEAVE_TYPE_FULL')	    OR define('LEAVE_TYPE_FULL','full');
defined('LEAVE_TYPE_HALF')	    OR define('LEAVE_TYPE_HALF','half');
defined('LEAVE_TYPE_QUARTER')	    OR define('LEAVE_TYPE_QUARTER','quarter');