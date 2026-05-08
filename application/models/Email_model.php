<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Email_model extends CI_Model
{
    function __construct() {
        parent::__construct();
        //$this->load->view('class.phpmailer.php');
    }

    // public function send_mail($mail_data = array()) 
    // {
    //     $email_setup    = $this->email_setup_model->get_email_records();

    //     $mail = new PHPMailer();

    //     $mail->IsSMTP();
    //     $mail->Host = $email_setup->host;

    //     // from email setup 
    //     $mail->SMTPAuth     = true;
    //     $mail->SMTPSecure   = "ssl";
    //     $mail->Port         = $email_setup->port;
    //     $mail->Username     = $email_setup->username;
    //     $mail->Password     = $email_setup->password;

    //     $mail->From         = $mail_data['From'];
    //     $mail->FromName     = $mail_data['FromName'];

    //     $mail->AddReplyTo($mail_data['AddReplyTo']);
        
    //     $mail->IsHTML(true);

    //     $mail->ConfirmReadingTo     = $mail_data['From'];
    //     $mail->AltBody              = "Please return read receipt to me.";
    //     $mail->Wordwrap             = 80;

    //     $mail->AddCustomHeader( "X-Confirm-Reading-To: ".$mail_data['From'] );
    //     $mail->AddCustomHeader( "Return-Receipt-To: ".$mail_data['From'] );
    //     $mail->AddCustomHeader( "Disposition-Notification-To: ".$mail_data['From'] );
        
    //     $mail->AddAddress($mail_data['To']);

    //     $mail->Subject  = $mail_data['Subject'];
    //     $mail->Body     = $mail_data['Body'];

    //     return $mail->send();
    // }
    // public function send_mail($pdf_filename,$mail_data = array()) 
    // {
    //   $message = $mail_data['Body'];
    
    //   $this->email->clear();
    //   $this->email->from($mail_data['From'],$mail_data['FromName']);
    //   $this->email->to($mail_data['To']);

   
    //   $cc_recipients = $mail_data['Cc'];

      
    //   if(!empty($cc_recipients))
    //   {
    //     foreach ($cc_recipients as $cc_email) {
    //       if (!empty($cc_email) && filter_var($cc_email, FILTER_VALIDATE_EMAIL)) {
    //         $this->email->cc($cc_email);
    //       }
    //     }
    //   }
      

    //   //$this->email->cc($mail_data['Cc']);
    //   $this->email->subject($mail_data['Subject']);
    //   $this->email->message($message);
    //  // Attach the PDF content
    //   $this->email->attach('assets/documents/'.$pdf_filename);

    //   $this->email->set_header('Content-Disposition', 'attachment');
            
    //   // if ($this->email->send()) {
    //   //     return true;
    //   // } else {
    //   //     log_message('error', 'Email sending failed: ' . $this->email->print_debugger());
    //   //     return false;
    //   // }
    //     try {
    //         if (!$this->email->send()) {
    //             // Log the error message
    //             log_message('error', 'Email sending failed: ' . $this->email->print_debugger());
                
    //             // Handle the failure silently without showing it to the user
    //             // echo 'Sorry, there was an error sending your email. Please try again later.';
    //             return false;
    //         } else {
    //             // echo 'Email sent successfully.';
    //             return true;
    //         }
    //     } catch (Exception $e) {
    //         // Log the exception message
    //         log_message('error', 'Exception while sending email: ' . $e->getMessage());
            
    //         // Handle the exception silently without showing it to the user
    //         // echo 'Sorry, there was an error sending your email. Please try again later.';
    //         return false;
    //     }
       
     
    // }

    public function send_mail($pdf_path, $mail_data = array()) 
		{
				$message = $mail_data['Body'];
				
				$this->email->clear();
				$this->email->from($mail_data['From'], $mail_data['FromName']);
				$this->email->to($mail_data['To']);

				$cc_recipients = $mail_data['Cc'];

				if (!empty($cc_recipients)) {
						foreach ($cc_recipients as $cc_email) {
								if (!empty($cc_email) && filter_var($cc_email, FILTER_VALIDATE_EMAIL)) {
										$this->email->cc($cc_email);
								}
						}
				}

				$this->email->subject($mail_data['Subject']);
				$this->email->message($message);
				
				// Attach the PDF file using the full path
				$this->email->attach($pdf_path);

				$this->email->set_header('Content-Disposition', 'attachment');

				try {
						if (!$this->email->send()) {
								log_message('error', 'Email sending failed: ' . $this->email->print_debugger());
								return false;
						} else {
								return true;
						}
				} catch (Exception $e) {
						log_message('error', 'Exception while sending email: ' . $e->getMessage());
						return false;
				}
		}


    public function add_email_sent_record($data)
    {
        if($this->db->insert('email_sent',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function send_user_mail($mail_data = array()) 
    {
        // Clear previous email settings
        $this->email->clear();
        $this->email->from($mail_data['From'], $mail_data['FromName']);
        $this->email->to($mail_data['To']);
        //$this->email->cc($mail_data['Cc']);
        $this->email->subject($mail_data['Subject']);
        $this->email->message($mail_data['Body']);

        // Suppress fsockopen warnings and handle them manually
        set_error_handler(function($severity, $message, $file, $line) {
            // Handle the error
            log_message('error', "Error ($severity): $message in $file on line $line");
            // If it's a fatal error, throw an exception
            if (in_array($severity, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
                throw new ErrorException($message, 0, $severity, $file, $line);
            }
        });

        try {
            if (!$this->email->send()) {
                // Log the error message
                log_message('error', 'Email sending failed: ' . $this->email->print_debugger());
                restore_error_handler();
                return false;
            } else {
                restore_error_handler();
                return true;
            }
        } catch (Exception $e) {
            // Log the exception message
            log_message('error', 'Exception while sending email: ' . $e->getMessage());
            restore_error_handler();
            return false;
        }
    }

}
?>