<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Test extends CI_Controller {
 
    public function __construct() {
        parent::__construct();
        $this->load->library('email');
    }
 
    public function send_email() {
        // Email configuration
        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'smtp.gmail.com';
        $config['smtp_user']    = 'shubham.axolotls@gmail.com'; // your Gmail address
        $config['smtp_pass']    = 'vtgjwggywqfijuvx';           // your Gmail App Password
        $config['smtp_port']    = 465;
        $config['smtp_crypto']  = 'ssl';
        $config['mailtype']     = 'html';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['wordwrap']     = TRUE;
 
        $this->email->initialize($config);
        $this->email->from('shubham.axolotls@gmail.com', 'Shubham (Axolotls)');
        $this->email->to('sakshi.axolotls@gmail.com');
        $this->email->subject('Purchase Request Update Notification');
        $this->email->message('<h3>Hi Sakshi,</h3><p>The purchase request has been updated successfully as per the system logic.</p><p>Regards,<br>System</p>');
 
        if ($this->email->send()) {
            echo 'Email sent successfully to sakshi.axolotls@gmail.com';
        } else {
            echo 'Email sending failed:<br>' . $this->email->print_debugger();
        }
    }
}