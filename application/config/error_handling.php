<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Error Handling Configuration (Production Safe)
|--------------------------------------------------------------------------
|
| Compatible with PHP 7.4+
| - Suppresses deprecated warnings
| - Keeps critical errors active
| - Does NOT override CodeIgniter error handling
|
*/

// Report all errors except deprecated and strict
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

// Do not display errors to users (security)
ini_set('display_errors', 0);

// Enable logging of errors
ini_set('log_errors', 1);

// Optional: define custom error log file
// ini_set('error_log', APPPATH . 'logs/php-error.log');