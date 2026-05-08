<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MailTest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load email library
        $this->load->library('email');
    }

    // Function to send mail manually
    public function send_manual_email() {
        // Recipient
        $to = 'sakshi.axolotls@gmail.com';

        // Subject and message
        $subject = 'Test Email from CI';
        $message = '<h3>Hello!</h3><p>This is a test email sent manually from CodeIgniter.</p>';

        // Set email parameters
        $this->email->from('sakshifulari107@gmail.com', 'sak'); // sender
        $this->email->to($to); // recipient
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->set_mailtype("html"); // for HTML email

        // Send email
        if ($this->email->send()) {
            echo 'Email sent successfully!';
        } else {
            echo 'Email failed to send.<br>';
            // Display debug info
            echo $this->email->print_debugger();
        }
    }
}
