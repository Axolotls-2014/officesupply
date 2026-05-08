<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'smtp.gmail.com',
    'smtp_user'    => 'priyanka.saxolotls@gmail.com',
    'smtp_pass'    => 'vzno caep fnxj adwj', // Use App Password if 2-Step Verification is enabled
    'smtp_crypto' => 'ssl',
    'mailtype' => 'html', // Set to 'html' if sending HTML content
    'charset' => 'utf-8', // Recommended charset for modern emails
    'wordwrap' => TRUE,
    'newline' => "\r\n", // Important for some servers
    'smtp_timeout' => '7'
);

