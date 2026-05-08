<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
// 	            error_reporting(E_ALL);
// ini_set('display_errors', 1);
		parent::__construct();		
		$this->load->database();
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */



	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) 
		{
			show_error('You must be an administrator to view this page.');
		}
		else
		{
			redirect('auth/dashboard');
			// $this->data['title'] = $this->lang->line('index_heading');
			
			// // set the flash data error message if there is one
			// $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			// //list the users
			// $this->data['users'] = $this->ion_auth->users()->result();
			
			// //USAGE NOTE - you can do more complicated queries like this
			// //$this->data['users'] = $this->ion_auth->where('field', 'value')->users()->result();
			
			// foreach ($this->data['users'] as $k => $user)
			// {
			// 	$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			// }

			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	/**
	 * Log the user in
	 */
	 
	public function login()
	{
		$this->data['title'] = $this->lang->line('login_heading');

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				$this->session->set_userdata('logged_in', TRUE);
				
				$user_id = $this->session->userdata('user_id');
                $user_company = $this->db->select('*')->where('id', $user_id)->get('users')->row();
                
                if ($user_company) {
                    $this->session->set_userdata('company_id', $user_company->company_id);
                }   
                $this->session->set_userdata('role', $user_company->role);
                $this->session->set_userdata('clients_branch_id', $user_company->clients_branch_id);
                $this->session->set_userdata('level_id', $user_company->level_id);
                $this->session->set_userdata('dept_id', $user_company->dept_id);
				$currency_symbol = $this->company_settings_model->get_company_records()->currency_symbol;
				

				$permission = $this->permission_model->get_permission_records_by_user($this->session->userdata('user_id'));
				// exit;
				$company_setting      = $this->company_settings_model->get_company_records();

		
				$perm = array();
				foreach ($permission as $row) {
					array_push($perm, $row->name);
				}

				$perm_m = array();
				foreach ($permission as $row) {
					array_push($perm_m, $row->module);
				}


				$current_date = date('Y-m-d');

				$subscription = $this->utility_model->get_subscription_records($current_date);

				// $perm_plan_code = array();
				// foreach ($subscription as $row) {
				// 		array_push($perm_plan_code, $row->plan_code);
				// }

				// print_r($subscription);
				
				$this->session->set_userdata('plan_code',$subscription->plan_code);

			
				$this->session->set_userdata('permission',$perm);
				$this->session->set_userdata('permission_m',array_values(array_unique($perm_m)));
				$this->session->set_userdata('currency_symbol',$currency_symbol);
				$this->session->set_userdata('company_state_id',$company_setting->state_id);
				$this->session->set_userdata('instance_id',$company_setting->instance_id);
				$this->session->set_userdata('access_token',$company_setting->access_token);

	
				$this->session->set_userdata('purchase_order_prefix',$company_setting->purchase_order_prefix);
				$this->session->set_userdata('purchase_prefix',$company_setting->purchase_prefix);
				$this->session->set_userdata('purchase_return_prefix',$company_setting->purchase_return_prefix);
				$this->session->set_userdata('sale_prefix',$company_setting->sale_prefix);
				$this->session->set_userdata('sale_return_prefix',$company_setting->sale_return_prefix);
				$this->session->set_userdata('quotation_prefix',$company_setting->quotation_prefix);
				$this->session->set_userdata('credit_debit_note_prefix',$company_setting->credit_debit_note_prefix);
				$this->session->set_userdata('proforma_invoice_prefix',$company_setting->proforma_invoice_prefix);

				$this->session->set_userdata('bank_payment_prefix',$company_setting->bank_payment_prefix);
				$this->session->set_userdata('cash_payment_prefix',$company_setting->cash_payment_prefix);
				$this->session->set_userdata('bank_receipt_prefix',$company_setting->bank_receipt_prefix);
				$this->session->set_userdata('cash_receipt_prefix',$company_setting->cash_receipt_prefix);
				$this->session->set_userdata('contra_prefix',$company_setting->contra_prefix);
				$this->session->set_userdata('scrap_issue_prefix',$company_setting->scrap_issue_prefix);
				$this->session->set_userdata('scrap_entry_prefix',$company_setting->scrap_entry_prefix);
				$this->session->set_userdata('scrap_receive_prefix',$company_setting->scrap_receive_prefix);

                $this->session->set_userdata('employee_prefix',$company_setting->employee_prefix);
                $this->session->set_userdata('department_prefix',$company_setting->department_prefix);
                $this->session->set_userdata('position_prefix',$company_setting->position_prefix);
				

				$this->session->set_userdata('bank_payment_date_format',$company_setting->bank_payment_date_format);
				$this->session->set_userdata('cash_payment_date_format',$company_setting->cash_payment_date_format);
				$this->session->set_userdata('bank_receipt_date_format',$company_setting->bank_receipt_date_format);
				$this->session->set_userdata('cash_receipt_date_format',$company_setting->cash_receipt_date_format);
				$this->session->set_userdata('contra_date_format',$company_setting->contra_date_format);
				$this->session->set_userdata('scrap_issue_date_format',$company_setting->scrap_issue_date_format);
				$this->session->set_userdata('scrap_entry_date_format',$company_setting->scrap_entry_date_format);
				$this->session->set_userdata('scrap_receive_date_format',$company_setting->scrap_receive_date_format);
				
				$this->session->set_userdata('purchase_order_date_format',$company_setting->purchase_order_date_format);
				$this->session->set_userdata('purchase_date_format',$company_setting->purchase_date_format);
				$this->session->set_userdata('purchase_return_date_format',$company_setting->purchase_return_date_format);
				$this->session->set_userdata('sale_date_format',$company_setting->sale_date_format);
				$this->session->set_userdata('sale_return_date_format',$company_setting->sale_return_date_format);
				$this->session->set_userdata('quotation_date_format',$company_setting->quotation_date_format);
				$this->session->set_userdata('credit_debit_note_date_format',$company_setting->credit_debit_note_date_format);
				$this->session->set_userdata('proforma_invoice_date_format',$company_setting->proforma_invoice_date_format);

                $this->session->set_userdata('employee_date_format',$company_setting->employee_date_format);
                $this->session->set_userdata('department_date_format',$company_setting->department_date_format);
                $this->session->set_userdata('position_date_format',$company_setting->position_date_format);

				$this->session->set_userdata('purchase_order_sequence',$company_setting->purchase_order_sequence);
				$this->session->set_userdata('purchase_sequence',$company_setting->purchase_sequence);
				$this->session->set_userdata('purchase_return_sequence',$company_setting->purchase_return_sequence);
				$this->session->set_userdata('sale_sequence',$company_setting->sale_sequence);
				$this->session->set_userdata('sale_return_sequence',$company_setting->sale_return_sequence);
				$this->session->set_userdata('quotation_sequence',$company_setting->quotation_sequence);
				$this->session->set_userdata('credit_debit_note_sequence',$company_setting->credit_debit_note_sequence);
				$this->session->set_userdata('proforma_invoice_sequence',$company_setting->proforma_invoice_sequence);

				$this->session->set_userdata('bank_payment_sequence',$company_setting->bank_payment_sequence);
				$this->session->set_userdata('cash_payment_sequence',$company_setting->cash_payment_sequence);
				$this->session->set_userdata('bank_receipt_sequence',$company_setting->bank_receipt_sequence);
				$this->session->set_userdata('cash_receipt_sequence',$company_setting->cash_receipt_sequence);
				$this->session->set_userdata('contra_sequence',$company_setting->contra_sequence);
				$this->session->set_userdata('scrap_issue_sequence',$company_setting->scrap_issue_sequence);
				$this->session->set_userdata('scrap_entry_sequence',$company_setting->scrap_entry_sequence);
				$this->session->set_userdata('scrap_receive_sequence',$company_setting->scrap_receive_sequence);

                $this->session->set_userdata('employee_sequence',$company_setting->employee_sequence);
                $this->session->set_userdata('department_sequence',$company_setting->department_sequence);
                $this->session->set_userdata('position_sequence',$company_setting->position_sequence);
        				//if the login is successful
        				//redirect them back to the home page

				// $this->session->set_flashdata('message', $this->ion_auth->messages());

                if (!$this->ion_auth->in_group(CUSTOMER_GROUP_NAME))
                {
                    $user_id   = $this->session->userdata('user_id');
                    $user_role = $this->db->query("SELECT role FROM users WHERE id = ?", array($user_id))->row()->role;
                    if($user_role == 'client'||$user_role == 'clients_branch_manager'){
                        redirect('auth/dashboard2', 'refresh');
                    } else{
            			 redirect('auth/dashboard', 'refresh');
                    }
                }
				else{
				  redirect('proforma_invoice', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			];

			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
			];

			$this->data['app_name'] = $this->application_settings_model->get_application_records()->app_name;

			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'login', $this->data);
		}
	}
	/**
	 * Log the user out
	 */
    public function logout()
    {
        // Destroy the session completely
        $this->session->sess_destroy();
    
        // Log out using Ion Auth
        $this->ion_auth->logout();
    
        // Redirect to login page
        redirect('auth/login', 'refresh');
        exit;
    }


	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$id 	= $this->input->post('id');
		$user = $this->ion_auth->user($id)->row();

		if ($this->form_validation->run() === FALSE)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = [
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
			];
			$this->data['new_password'] = [
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['new_password_confirm'] = [
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['user_id'] = [
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			];

			if ($this->input->is_ajax_request()) 
			{  
				$response['code'] 		= 0;
				$response['message']	= "There is some issue";
				echo json_encode($response);
			}
			else
			{
				// render
				$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
			}
			
		}
		else
		{
			// $identity = $this->session->userdata('identity');
			$identity = $user->email;

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				if ($this->input->is_ajax_request()) 
				{  
					$response['code'] 		= 1;
					$response['message']	= $this->lang->line('success_change_password');
					echo json_encode($response);
				}
				else
				{
					//if the password was successfully changed
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					$this->logout();
				}
			}
			else
			{
				if ($this->input->is_ajax_request()) 
				{  
					$response['code'] 		= 0;
					$response['message']	= $this->ion_auth->errors();	
					echo json_encode($response);
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('auth/change_password', 'refresh');
				}
				
			}
		}
	}

	/**
	 * Forgot password
	 */
// 	public function forgot_password()
// 	{
// 		$this->data['title'] = $this->lang->line('forgot_password_heading');
		
// 		// setting validation rules by checking whether identity is username or email
// 		if ($this->config->item('identity', 'ion_auth') != 'email')
// 		{
// 			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
// 		}
// 		else
// 		{
// 			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required');
// 		}


// 		if ($this->form_validation->run() === FALSE)
// 		{
// 			$this->data['type'] = $this->config->item('identity', 'ion_auth');
// 			// setup the input
// 			$this->data['identity'] = [
// 				'name' => 'identity',
// 				'id' => 'identity',
// 			];

// 			if ($this->config->item('identity', 'ion_auth') != 'email')
// 			{
// 				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
// 			}
// 			else
// 			{
// 				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
// 			}

// 			// set any errors and display the form
// 			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
// 			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'forgot_password', $this->data);
// 		}
// 		else
// 		{
// 			$identity_column = $this->config->item('identity', 'ion_auth');
// 			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

// 			if (empty($identity))
// 			{

// 				if ($this->config->item('identity', 'ion_auth') != 'email')
// 				{
// 					$this->ion_auth->set_error('forgot_password_identity_not_found');
// 				}
// 				else
// 				{
// 					$this->ion_auth->set_error('forgot_password_email_not_found');
// 				}

// 				$this->session->set_flashdata('message', $this->ion_auth->errors());
// 				redirect("auth/forgot_password", 'refresh');
// 			}

// 			// run the forgotten password method to email an activation code to the user
// 			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

// 			if ($forgotten)
// 			{
// 				// if there were no errors
// 				$this->session->set_flashdata('message', $this->ion_auth->messages());
// 				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
// 			}
// 			else
// 			{
// 				$this->session->set_flashdata('message', $this->ion_auth->errors());
// 				redirect("auth/forgot_password", 'refresh');
// 			}
// 		}
// 	}


	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
// 	public function reset_password($code = NULL)
// 	{
// 		if (!$code)
// 		{
// 			show_404();
// 		}

// 		$this->data['title'] = $this->lang->line('reset_password_heading');
		
// 		$user = $this->ion_auth->forgotten_password_check($code);

// 		if ($user)
// 		{
// 			// if the code is valid then display the password reset form

// 			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
// 			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

// 			if ($this->form_validation->run() === FALSE)
// 			{
// 				// display the form

// 				// set the flash data error message if there is one
// 				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

// 				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
// 				$this->data['new_password'] = [
// 					'name' => 'new',
// 					'id' => 'new',
// 					'type' => 'password',
// 					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
// 				];
// 				$this->data['new_password_confirm'] = [
// 					'name' => 'new_confirm',
// 					'id' => 'new_confirm',
// 					'type' => 'password',
// 					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
// 				];
// 				$this->data['user_id'] = [
// 					'name' => 'user_id',
// 					'id' => 'user_id',
// 					'type' => 'hidden',
// 					'value' => $user->id,
// 				];
// 				$this->data['csrf'] = $this->_get_csrf_nonce();
// 				$this->data['code'] = $code;

// 				// render
// 				$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'reset_password', $this->data);
// 			}
// 			else
// 			{
// 				$identity = $user->{$this->config->item('identity', 'ion_auth')};

// 				// do we have a valid request?
// 				// if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
// 				if ($user->id != $this->input->post('user_id'))
// 				{

// 					// something fishy might be up
// 					$this->ion_auth->clear_forgotten_password_code($identity);

// 					show_error($this->lang->line('error_csrf'));

// 				}
// 				else
// 				{
// 					// finally change the password
// 					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

// 					if ($change)
// 					{
// 						// if the password was successfully changed
// 						$this->session->set_flashdata('message', $this->ion_auth->messages());
// 						redirect("auth/login", 'refresh');
// 					}
// 					else
// 					{
// 						$this->session->set_flashdata('message', $this->ion_auth->errors());
// 						redirect('auth/reset_password/' . $code, 'refresh');
// 					}
// 				}
// 			}
// 		}
// 		else
// 		{
// 			// if the code is invalid then send them back to the forgot password page
// 			$this->session->set_flashdata('message', $this->ion_auth->errors());
// 			redirect("auth/forgot_password", 'refresh');
// 		}
// 	}


// In your Auth controller



    public function forgot_password() {
        $this->data['title'] = 'Forgot Password';
        $this->load->view('auth/forgot_password', $this->data);
    }


public function send_otp() {
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

    if ($this->form_validation->run() === FALSE) {
        echo json_encode([
            'success' => false,
            'message' => validation_errors(),
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        return;
    }

    $email = $this->input->post('email');
    $user = $this->ion_auth->where('email', $email)->users()->row();

    if (empty($user)) {
        echo json_encode([
            'success' => false,
            'message' => 'No account found with this email address.',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        return;
    }

    // Generate 6-digit OTP
    $otp = sprintf("%06d", mt_rand(1, 999999));
    $otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    // Update user record with OTP
    $data = [
        'otp' => $otp,
        'otp_expiry' => $otp_expiry
    ];
    $this->ion_auth->update($user->id, $data);

    // Load email library and configure
    $this->load->library('email');

    // Email configuration (no external file used)
    $config['protocol']     = 'smtp';
    $config['smtp_host']    = 'smtp.gmail.com';
    $config['smtp_user']    = 'axolotls.tb@gmail.com'; // your Gmail
    $config['smtp_pass']    = 'wivh nhok mkxv ivht';            // App Password
    $config['smtp_port']    = 465;
    $config['smtp_crypto']  = 'ssl';
    $config['mailtype']     = 'html';
    $config['charset']      = 'utf-8';
    $config['newline']      = "\r\n";
    $config['wordwrap']     = TRUE;

    $this->email->initialize($config);

    // Compose and send the OTP email
    $this->email->from('sakshi.axolotls@gmail.com', 'Office Supply Solution');
    $this->email->to($email);
    $this->email->subject('Password Reset OTP');
    $this->email->message("
        <h3>Hello,</h3>
        <p>Your OTP for password reset is: <strong>$otp</strong></p>
        <p>Regards,<br>Office Supply Solution</p>
    ");

    if ($this->email->send()) {
        echo json_encode([
            'success' => true,
            'message' => 'OTP sent to your email.',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    } else {
        log_message('error', $this->email->print_debugger());
        echo json_encode([
            'success' => false,
            'message' => 'Failed to send OTP. Please try again.',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }
}


    // Verify OTP
    public function verify_otp() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('otp', 'OTP', 'required|exact_length[6]|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors(),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $email = $this->input->post('email');
        $otp = $this->input->post('otp');

        $user = $this->ion_auth->where('email', $email)->users()->row();

        if (empty($user)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email address.',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Check if OTP matches and is not expired
        if ($user->otp !== $otp) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid OTP.',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        if (strtotime($user->otp_expiry) < time()) {
            echo json_encode([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // OTP is valid
        echo json_encode([
            'success' => true,
            'message' => 'OTP verified successfully.',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }

    // Resend OTP
    public function resend_otp() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors(),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $email = $this->input->post('email');
        $user = $this->ion_auth->where('email', $email)->users()->row();

        if (empty($user)) {
            echo json_encode([
                'success' => false,
                'message' => 'No account found with this email address.',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Generate new 6-digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Update user record with new OTP
        $data = [
            'otp' => $otp,
            'otp_expiry' => $otp_expiry
        ];
        $this->ion_auth->update($user->id, $data);

        // Send email with new OTP
        $this->load->library('email');
        $this->email->from('no-reply@yourdomain.com', 'Your Application');
        $this->email->to($email);
        $this->email->subject('New Password Reset OTP');
        $this->email->message("Your new OTP for password reset is: $otp\n\nThis OTP is valid for 15 minutes.");

        if ($this->email->send()) {
            echo json_encode([
                'success' => true,
                'message' => 'New OTP sent to your email.',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        }
    }

    // Reset password after OTP verification
    public function reset_password() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
        
        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors(),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $email = $this->input->post('email');
        $new_password = $this->input->post('new_password');

        $user = $this->ion_auth->where('email', $email)->users()->row();

        if (empty($user)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email address.',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Reset password
        $this->ion_auth->reset_password($user->email, $new_password);

        // Clear OTP fields
        $data = [
            'otp' => NULL,
            'otp_expiry' => NULL
        ];
        $this->ion_auth->update($user->id, $data);

        echo json_encode([
            'success' => true,
            'message' => 'Password has been reset successfully. You can now login with your new password.',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }




	public function reset_password_user_defined()
	{
		$new 					= $this->input->post('new');
		$new_confirm 	= $this->input->post('new_confirm');
		$id 					= $this->input->post('id');

		$user 				= $this->ion_auth->user($id)->row();
		$identity 		= $user->{$this->config->item('identity', 'ion_auth')};

		$change 			= $this->ion_auth->reset_password($identity, $this->input->post('new'));

		$response 		= array();

		if($change)
		{
			$response['code'] 		= 1;
			$response['message'] 	= 'Password is reset successfully.';
		}	
		else
		{
			$response['code'] 		= 0;
			$response['message'] 	= 'Password is failed to reset.';
		}

		echo json_encode($response);
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	// public function activate($id, $code = FALSE)
	// {
	// 	$activation = FALSE;

	// 	if ($code !== FALSE)
	// 	{
	// 		$activation = $this->ion_auth->activate($id, $code);
	// 	}
	// 	else if ($this->ion_auth->is_admin())
	// 	{
	// 		$activation = $this->ion_auth->activate($id);
	// 	}

	// 	if ($activation)
	// 	{
	// 		// redirect them to the auth page
	// 		$this->session->set_flashdata('message', $this->ion_auth->messages());
	// 		redirect("auth", 'refresh');
	// 	}
	// 	else
	// 	{
	// 		// redirect them to the forgot password page
	// 		$this->session->set_flashdata('message', $this->ion_auth->errors());
	// 		redirect("auth/forgot_password", 'refresh');
	// 	}
	// }

	public function activate($id, $code = FALSE)
  {
    $activation = FALSE;
    $user       = $this->ion_auth_model->user()->row();

    if ($this->ion_auth->is_admin())
    {
      $activation = $this->ion_auth->activate($id);
    }
    else
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 0;
        $response['message'] = 'You must be administrator to perform this task.';

        echo json_encode($response);
      }
      else
      {
        $this->session->flashdata('failure','You must be administrator to perform this task.');
        redirect('user','refresh');
      }
    }

    if ($activation)
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 1;
        $response['message'] = $user->first_name.' '.$user->last_name.' is ACTIVATED successfully.';

        echo json_encode($response);
      }
      else
      {
        // redirect them to the auth page
        $this->session->set_flashdata('failure', $user->first_name.' '.$user->last_name.' is ACTIVATED successfully.');
        redirect("user", 'refresh');
      }
      
    }
    else
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 0;
        $response['message'] = $user->first_name.' '.$user->last_name.' is failed to ACTIVATE.';

        echo json_encode($response);
      }
      else
      {
        // redirect them to the forgot password page
        $this->session->set_flashdata('message', $user->first_name.' '.$user->last_name.' is failed to ACTIVATE.');
        redirect("user", 'refresh');
      }
      
    }
  }

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	// public function deactivate($id = NULL)
	// {
	// 	if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
	// 	{
	// 		// redirect them to the home page because they must be an administrator to view this
	// 		show_error('You must be an administrator to view this page.');
	// 	}

	// 	$id = (int)$id;

	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
	// 	$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

	// 	if ($this->form_validation->run() === FALSE)
	// 	{
	// 		// insert csrf check
	// 		$this->data['csrf'] = $this->_get_csrf_nonce();
	// 		$this->data['user'] = $this->ion_auth->user($id)->row();

	// 		$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'deactivate_user', $this->data);
	// 	}
	// 	else
	// 	{
	// 		// do we really want to deactivate?
	// 		if ($this->input->post('confirm') == 'yes')
	// 		{
	// 			// do we have a valid request?
	// 			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
	// 			{
	// 				show_error($this->lang->line('error_csrf'));
	// 			}

	// 			// do we have the right userlevel?
	// 			if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
	// 			{
	// 				$this->ion_auth->deactivate($id);
	// 			}
	// 		}

	// 		// redirect them back to the auth page
	// 		redirect('auth', 'refresh');
	// 	}
	// }

	public function deactivate($id = NULL)
  {
    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
    {
      $message = '';
      if($this->ion_auth->is_admin())
        $message = 'You can not disable the ADMIN.';
      else
        $message = 'You must be administrator to perform this task.';

     
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 0;
        $response['message'] = $message;

        echo json_encode($response);
      }
      else
      {
        $this->session->flashdata('failure',$message);
        redirect('user','refresh');
      }
    }

    $id = (int)$id;
    $user = $this->ion_auth_model->user($id)->row();

    // do we have the right userlevel?
    if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
    {
      if($this->ion_auth->deactivate($id))
      {
        if($this->input->is_ajax_request())
        {
          $response = array();
          $response['code'] = 1;
          $response['message'] = $user->first_name.' '.$user->last_name.' is DEACTIVATED successfully.';

          echo json_encode($response);
        }
        else
        {
          $this->session->flashdata('success',$user->first_name.' '.$user->last_name.' is DEACTIVATED successfully.');
          redirect('user','refresh');
        }
      }
      else
      {
        if($this->input->is_ajax_request())
        {
          $response = array();
          $response['code'] = 0;
          $response['message'] = $user->first_name.' '.$user->last_name.' is failed to DEACTIVATE.';

          echo json_encode($response);
        }
        else
        {
          $this->session->flashdata('failure',$user->first_name.' '.$user->last_name.' is DEACTIVATED successfully.');
          redirect('user','refresh');
        }
      }  
    }
    else
    {
      if($this->input->is_ajax_request())
      {
        $response = array();
        $response['code'] = 0;
        $response['message'] = $user->first_name.' '.$user->last_name.' is failed to DEACTIVATE.';

        echo json_encode($response);
      }
      else
      {
        $this->session->flashdata('failure',$user->first_name.' '.$user->last_name.' is DEACTIVATED successfully.');
        redirect('user','refresh');
      }
    }
  }

	/**
	 * Create a new user
	 */
public function create_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
        $this->data['warehouses'] = $this->db->get('warehouse')->result();
		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        // $this->form_validation->set_rules('branch_id', 'Branch', 'required'); // Validation for branch_id

		if ($identity_column !== 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required');
		}
		else
		{
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|is_unique[' . $tables['users'] . '.email]');
		}
		// $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
		// $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
		// $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			$email    = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company_id' => $this->input->post('company_id'),
				'phone' => $this->input->post('phone'),
				'branch_id'  => $this->input->post('branch_id'), 
			];
			$group = array();
			$group[] = $this->input->post('role_id');
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group))
		{
			$this->session->set_flashdata('success', $this->ion_auth->messages());
			redirect("auth/users", 'refresh');
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = [
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			];
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			];
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			];
			// $this->data['company'] = [
			// 	'name' => 'company',
			// 	'id' => 'company',
			// 	'type' => 'text',
			// 	'value' => $this->form_validation->set_value('company'),
			// ];
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone'),
			];
			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			];
			$this->data['password_confirm'] = [
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			];

			$this->data['user_roles'] =	$this->ion_auth->groups()->result();

			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'create_user', $this->data);
		}
	}
	
	
public function insert_user()
   {
    $this->data['title'] = $this->lang->line('create_user_heading');
    
    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
    {
        redirect('auth', 'refresh');
    }
    $this->data['warehouses'] = $this->db->get('warehouse')->result();
    $tables = $this->config->item('tables', 'ion_auth');
    $identity_column = $this->config->item('identity', 'ion_auth');
    $this->data['identity_column'] = $identity_column;

    $email    = strtolower($this->input->post('email'));
    $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
    $password = $this->input->post('password');

    $additional_data = [
        'first_name' => $this->input->post('first_name'),
        'last_name'  => $this->input->post('last_name'),
        'company_name'    => $this->input->post('company'),
        'phone'      => $this->input->post('phone'),
        'gstin'      => $this->input->post('gstin'),
        'address'    => $this->input->post('address'),
        'role'       => 'client',  
        'billing_details' => $this->input->post('billing_details'),
        'shipping_details' => $this->input->post('shipping_details'),
        'state'       => $this->input->post('state_id'),
    ];

    $group = array();
    $group[] = $this->input->post('role_id');

    if ($this->ion_auth->register($identity, $password, $email, $additional_data, $group))
    {
        $this->session->set_flashdata('success', $this->ion_auth->messages());
        redirect("auth/users", 'refresh');
    }
    else
    {
        $this->data['message'] = $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message');

        $this->data['first_name'] = [
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->input->post('first_name'),
        ];
        $this->data['gstin'] = [
            'name' => 'gstin',
            'id' => 'gstin',
            'type' => 'text',
            'value' => $this->input->post('gstin'),
        ];
        $this->data['last_name'] = [
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->input->post('last_name'),
        ];
        $this->data['identity'] = [
            'name' => 'identity',
            'id' => 'identity',
            'type' => 'text',
            'value' => $this->input->post('identity'),
        ];
        $this->data['email'] = [
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->input->post('email'),
        ];
        $this->data['company'] = [
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->input->post('company'),
        ];
        $this->data['phone'] = [
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->input->post('phone'),
        ];
        $this->data['password'] = [
            'name' => 'password',
            'id' => 'password',
            'type' => 'password',
            'value' => $this->input->post('password'),
        ];
        $this->data['password_confirm'] = [
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password',
            'value' => $this->input->post('password_confirm'),
        ];

        $this->data['user_roles'] = $this->ion_auth->groups()->result();

        $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'clients', $this->data);
    }
}


	public function redirectUser(){
		if ($this->ion_auth->is_admin()){
			redirect('auth', 'refresh');
		}
		redirect('/', 'refresh');
	}

	/**
	 * Edit a user
	 *
	 * @param int|string $id
	 */
	// public function edit_user($id)
	// {
	// 	$this->data['title'] = $this->lang->line('edit_user_heading');

	// 	if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
	// 	{
	// 		redirect('auth', 'refresh');
	// 	}

	// 	$user = $this->ion_auth->user($id)->row();
	// 	$groups = $this->ion_auth->groups()->result_array();
	// 	$currentGroups = $this->ion_auth->get_users_groups($id)->result();
			
	// 	//USAGE NOTE - you can do more complicated queries like this
	// 	//$groups = $this->ion_auth->where(['field' => 'value'])->groups()->result_array();
	

	// 	// validate form input
	// 	$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
	// 	$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
	// 	$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
	// 	// $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim');

	// 	if (isset($_POST) && !empty($_POST))
	// 	{
	// 		// do we have a valid request?
	// 		// if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
	// 		// {
	// 		// 	show_error($this->lang->line('error_csrf'));
	// 		// }

	// 		// update the password if it was posted
	// 		if ($this->input->post('password'))
	// 		{
	// 			$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
	// 			$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
	// 		}

	// 		if ($this->form_validation->run() === TRUE)
	// 		{
	// 			$data = [
	// 				'first_name' => $this->input->post('first_name'),
	// 				'last_name' => $this->input->post('last_name'),
	// 				'company_id' => $this->input->post('company_id'),
	// 				'phone' => $this->input->post('phone'),
	// 				'email' => $this->input->post('email'),
	// 				'username' => $this->input->post('identity'),
	// 			];

	// 			// update the password if it was posted
	// 			if ($this->input->post('password'))
	// 			{
	// 				$data['password'] = $this->input->post('password');
	// 			}

	// 			// Only allow updating groups if user is admin
	// 			// if ($this->ion_auth->is_admin())
	// 			// {
	// 				// Update the groups user belongs to
	// 				$this->ion_auth->remove_from_group('', $id);
					
	// 				$groupData 		= array();
	// 				$groupData[] 	= $this->input->post('role_id');
	// 				if (isset($groupData) && !empty($groupData))
	// 				{
	// 					foreach ($groupData as $grp)
	// 					{
	// 						$this->ion_auth->add_to_group($grp, $id);
	// 					}

	// 				}
	// 			// }

	// 			// check to see if we are updating the user
	// 			if ($this->ion_auth->update($user->id, $data))
	// 			{
	// 				// redirect them back to the admin page if admin, or to the base url if non admin
	// 				$this->session->set_flashdata('success', $this->input->post('first_name').' '.$this->input->post('last_name').' is updated successfully.');
	// 				redirect('auth/users');

	// 			}
	// 			else
	// 			{
	// 				// redirect them back to the admin page if admin, or to the base url if non admin
	// 				$this->session->set_flashdata('message', $this->ion_auth->errors());
	// 				redirect('auth/users');

	// 			}

	// 		}
	// 	}

	// 	// display the edit user form
	// 	$this->data['csrf'] = $this->_get_csrf_nonce();

	// 	// set the flash data error message if there is one
	// 	$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

	// 	// pass the user to the view
	// 	$this->data['user'] = $user;
	// 	$this->data['groups'] = $groups;
	// 	$this->data['currentGroups'] = $currentGroups;


	// 	$this->data['first_name'] = [
	// 		'name'  => 'first_name',
	// 		'id'    => 'first_name',
	// 		'type'  => 'text',
	// 		'value' => $this->form_validation->set_value('first_name', $user->first_name),
	// 	];
	// 	$this->data['last_name'] = [
	// 		'name'  => 'last_name',
	// 		'id'    => 'last_name',
	// 		'type'  => 'text',
	// 		'value' => $this->form_validation->set_value('last_name', $user->last_name),
	// 	];
	// 	// $this->data['company'] = [
	// 	// 	'name'  => 'company',
	// 	// 	'id'    => 'company',
	// 	// 	'type'  => 'text',
	// 	// 	'value' => $this->form_validation->set_value('company', $user->company),
	// 	// ];
	// 	$this->data['phone'] = [
	// 		'name'  => 'phone',
	// 		'id'    => 'phone',
	// 		'type'  => 'text',
	// 		'value' => $this->form_validation->set_value('phone', $user->phone),
	// 	];
	// 	$this->data['password'] = [
	// 		'name' => 'password',
	// 		'id'   => 'password',
	// 		'type' => 'password'
	// 	];
	// 	$this->data['password_confirm'] = [
	// 		'name' => 'password_confirm',
	// 		'id'   => 'password_confirm',
	// 		'type' => 'password'
	// 	];

	// 	$this->data['user_roles'] 	=	$this->ion_auth->groups()->result();

	// 	$user_groups = $this->ion_auth->get_users_groups($id)->result();
	// 	$this->data['role_id']		= 	$user_groups[0];	

	// 	// echo '<pre>';
	// 	// print_r($this->data);
	// 	// exit;

	// 	$this->_render_page('auth/edit_user', $this->data);
	// }
	
public function edit_user($id)
{
    $this->data['title'] = $this->lang->line('edit_user_heading');

    if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
    {
        redirect('auth', 'refresh');
    }

    $user = $this->ion_auth->user($id)->row();
    $groups = $this->ion_auth->groups()->result_array();
    $currentGroups = $this->ion_auth->get_users_groups($id)->result();

    // validate form input
    $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
    $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
    $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
    $this->form_validation->set_rules('email', $this->lang->line('edit_user_validation_email_label'), 'trim|required|valid_email');
    $this->form_validation->set_rules('company_name', $this->lang->line('edit_user_validation_company_name_label'), 'trim|required');

    if ($this->input->post())
    {
        // update the password if it was posted
        if ($this->input->post('password'))
        {
            $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
            $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required|matches[password]');
        }

        if ($this->form_validation->run() === TRUE)
        {
            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'phone'      => $this->input->post('phone'),
                'email'      => $this->input->post('email'),
                'username'   => $this->input->post('identity'),
                'company_name'  => $this->input->post('company_name'),
            ];

            if ($this->input->post('password')) {
                $data['password'] = $this->input->post('password');
            }

            $previousRole = $this->ion_auth->get_users_groups($id)->row()->id;
            $newRole = $this->input->post('role_id');
            $previousRoleName = $this->ion_auth->group($previousRole)->row()->name;
            $newRoleName = $this->ion_auth->group($newRole)->row()->name;

            $allowUpdate = false;

            if ($newRoleName == 'customer') {
                if ($previousRoleName != 'customer') {
                    $this->session->set_flashdata('message', 'Cannot change the role to customer if the previous role was not customer.');
                    redirect('auth/users');
                    return;
                }
                $allowUpdate = true;
            } else {
                if ($previousRoleName == 'customer') {
                    $this->db->where('user_id', $id);
                    $customerExists = $this->db->get('customer')->num_rows() > 0;

                    if ($customerExists) {
                        $this->session->set_flashdata('message', 'Cannot change the role of a user who is already a customer.');
                        redirect('auth/users');
                        return;
                    } else {
                        $allowUpdate = true;
                    }
                } else {
                    $allowUpdate = true;
                }
            }

            if ($allowUpdate) {
                $this->ion_auth->remove_from_group('', $id);

                $groupData = array($newRole);
                if (!empty($groupData)) {
                    foreach ($groupData as $grp) {
                        $this->ion_auth->add_to_group($grp, $id);
                    }
                }

                if ($this->ion_auth->update($user->id, $data)) {
                    $this->session->set_flashdata('success', $this->input->post('first_name').' '.$this->input->post('last_name').' is updated successfully.');
                    redirect('auth/users');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('auth/users');
                }
            }
        }
    }

    // display the edit user form
    $this->data['csrf'] = $this->_get_csrf_nonce();
    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
    $this->data['user'] = $user;
    $this->data['groups'] = $groups;
    $this->data['currentGroups'] = $currentGroups;

    $this->data['first_name'] = [
        'name'  => 'first_name',
        'id'    => 'first_name',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('first_name', $user->first_name),
    ];
    $this->data['last_name'] = [
        'name'  => 'last_name',
        'id'    => 'last_name',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('last_name', $user->last_name),
    ];
    $this->data['phone'] = [
        'name'  => 'phone',
        'id'    => 'phone',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('phone', $user->phone),
    ];
    $this->data['password'] = [
        'name' => 'password',
        'id'   => 'password',
        'type' => 'password'
    ];
    $this->data['password_confirm'] = [
        'name' => 'password_confirm',
        'id'   => 'password_confirm',
        'type' => 'password'
    ];

    $this->data['user_roles'] = $this->ion_auth->groups()->result();
    $user_groups = $this->ion_auth->get_users_groups($id)->result();
    $this->data['role_id'] = $user_groups[0];

    $this->_render_page('auth/edit_user', $this->data);
}


	/**
	 * Create a new group
	 */
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'trim|required|alpha_dash');

		if ($this->form_validation->run() === TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if ($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
			else
            		{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
            		}			
		}
			
		// display the create group form
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->data['group_name'] = [
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name'),
		];
		$this->data['description'] = [
			'name'  => 'description',
			'id'    => 'description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('description'),
		];

		$this->_render_page('auth/create_group', $this->data);
		
	}

	/**
	 * Edit a group
	 *
	 * @param int|string $id
	 */
	public function edit_group($id)
	{
		// bail if no group id given
		if (!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'trim|required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], array(
					'description' => $_POST['group_description']
				));

				if ($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
					redirect("auth", 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}				
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$this->data['group_name'] = [
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
		];
		if ($this->config->item('admin_group', 'ion_auth') === $group->name) {
			$this->data['group_name']['readonly'] = 'readonly';
		}
		
		$this->data['group_description'] = [
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		];

		$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'edit_group', $this->data);
	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return [$key => $value];
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
			return FALSE;
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}

	/*************************************************** user defined function *******************************************/

	function dashboard()
	{

	    
		if ($this->ion_auth->logged_in())
		{
	    if(!$this->permission_model->has_module_permission('dashboard'))
	    {
				$this->load->view('errors/html/error_restricted'); 	          
			}
			else
			{

				$data['customers'] 			    	=  	$this->customer_model->get_records();
				$data['suppliers'] 				    = 	$this->supplier_model->get_records();
				$data['products'] 			    	= 	$this->product_model->get_records();
				$data['product_alerts'] 	        = 	$this->warehouse_products_model->get_records_by_product_alerts();
				// echo $this->db->last_query();
				// echo '<pre>';
				// print_r($data);
				// exit;
				$data['expenses'] 			    	= 	$this->expense_model->get_records();
				$data['sales']                      =   $this->db->query("SELECT * FROM sale")->result();
				$data['purchases'] 			    	= 	$this->purchase_model->get_purchase_records();
				$data['expense_amount']			    = 	$this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, EXPENSE_TRANSACTION_TYPE);
				$data['paid_expense_amount']        = 	$this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);
				$data['sale_amount']		    	= 	$this->transaction_model->get_total_transaction_amount(null, SALE_MODULE, SALE_TRANSACTION_TYPE);
				$data['paid_sale_amount']       	= 	$this->transaction_model->get_total_transaction_amount(null, SALE_MODULE, RECEIPT_TRANSACTION_TYPE);
				$data['purchase_amount']			= 	$this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);
				$data['paid_purchase_amount']	    = 	$this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);
				$data['default_currency']       	= 	$this->company_settings_model->get_company_records();

				$year = (int)date('Y');
				$month = (int)date('m');

				$sales_amount_array  = array();
				$expenses_amount_array  = array();
				$purchase_amount_array	= array();

        for ($i=1; $i <= 12 ; $i++) 
        { 
          if(empty($this->sale_model->sale_by_month_year($year.'-'.sprintf("%02d",$i))))
          {
          	$sales_amount_array[] = 0;
          }
          else
          {
          	$sales_amount_array[] = $this->sale_model->sale_by_month_year($year.'-'.sprintf("%02d",$i))[0]['amount'];
          }
        }

        for ($i=1; $i <= 12 ; $i++) 
        { 
          if(empty($this->expense_model->expense_by_month_year($year.'-'.sprintf("%02d",$i))))
          {
          	$expenses_amount_array[] = 0;
          }
          else
          {
          	$expenses_amount_array[] = $this->expense_model->expense_by_month_year($year.'-'.sprintf("%02d",$i))[0]['amount'];
          }
        }

        for ($i=1; $i <12 ; $i++) 
        { 
        	if(empty($this->purchase_model->purchase_by_month_year($year.'-'.sprintf("%02d",$i))))
        	{
        		$purchase_amount_array[] = 0;
        	}	
        	else
        	{
        		$purchase_amount_array[] = $this->purchase_model->purchase_by_month_year($year.'-'.sprintf("%02d",$i))[0]['amount'];
        	}
        }
                $data['warehouses']     = $this->db->where('delete_status', 0)->get('warehouse')->result_array();
				$data['sales_data']     = $sales_amount_array;
				$data['expense_data'] 	= $expenses_amount_array;
				$data['purchase_data']	= $purchase_amount_array;
				// echo '<pre>';
				// print_r($data);
				// exit;

				$this->load->view('dashboard',$data);
			}
		}
		else
		{
			redirect('auth/login');
		}
	}

	/*function dashboard2()
	{
		if ($this->ion_auth->logged_in() and $this->session->userdata('user_id') != null)
		{
				$data['customers'] 			    	=  	$this->customer_model->get_records();
				$data['suppliers'] 				    = 	$this->supplier_model->get_records();
				$data['products'] 			    	= 	$this->product_model->get_records();
				$data['product_alerts'] 	        = 	$this->warehouse_products_model->get_records_by_product_alerts();
				$data['expenses'] 			    	= 	$this->expense_model->get_records();
				$data['sales']                      =   $this->db->query("SELECT * FROM sale")->result();
				$data['purchases'] 			    	= 	$this->purchase_model->get_purchase_records();
				$data['expense_amount']			    = 	$this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, EXPENSE_TRANSACTION_TYPE);
				$data['paid_expense_amount']        = 	$this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);
				$data['sale_amount']		    	= 	$this->transaction_model->get_total_transaction_amount(null, SALE_MODULE, SALE_TRANSACTION_TYPE);
				$data['paid_sale_amount']       	= 	$this->transaction_model->get_total_transaction_amount(null, SALE_MODULE, RECEIPT_TRANSACTION_TYPE);
				$data['purchase_amount']			= 	$this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);
				$data['paid_purchase_amount']	    = 	$this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);
				$data['default_currency']       	= 	$this->company_settings_model->get_company_records();
				$year  = (int)date('Y');
				$month = (int)date('m');

				$sales_amount_array     = array();
				$expenses_amount_array  = array();
				$purchase_amount_array	= array();

                for ($i=1; $i <= 12 ; $i++) 
                { 
                    if(empty($this->sale_model->sale_by_month_year($year.'-'.sprintf("%02d",$i))))
                    {
                    	$sales_amount_array[] = 0;
                    }
                    else
                    {
                    	$sales_amount_array[] = $this->sale_model->sale_by_month_year($year.'-'.sprintf("%02d",$i))[0]['amount'];
                    }
                }
        
                for ($i=1; $i <= 12 ; $i++) 
                { 
                    if(empty($this->expense_model->expense_by_month_year($year.'-'.sprintf("%02d",$i))))
                    {
                    	$expenses_amount_array[] = 0;
                    }
                    else
                    {
                    	$expenses_amount_array[] = $this->expense_model->expense_by_month_year($year.'-'.sprintf("%02d",$i))[0]['amount'];
                    }
                }
        
                for ($i=1; $i <12 ; $i++) 
                { 
                	if(empty($this->purchase_model->purchase_by_month_year($year.'-'.sprintf("%02d",$i))))
                	{
                		$purchase_amount_array[] = 0;
                	}	
                	else
                	{
                		$purchase_amount_array[] = $this->purchase_model->purchase_by_month_year($year.'-'.sprintf("%02d",$i))[0]['amount'];
                	}
                }
                
                $data['warehouses']     = $this->db->where('delete_status', 0)->get('warehouse')->result_array();
				$data['sales_data']     = $sales_amount_array;
				$data['expense_data'] 	= $expenses_amount_array;
				$data['purchase_data']	= $purchase_amount_array;
				$this->load->view('dashboard2',$data);
		}
		else
		{
			redirect('auth/login');
		}
	}*/

	function dashboard2()
{
    if ($this->ion_auth->logged_in() and $this->session->userdata('user_id') != null)
    {
        $user_id = $this->session->userdata('user_id');
        
        // Get user IDs (self + child users)
        $child_users = $this->db->select('id')
                                ->where('added_by', $user_id)
                                ->get('users')
                                ->result_array();
        $child_ids = array_column($child_users, 'id');
        $user_ids = array_merge([$user_id], $child_ids);
        
        // =============== STATUS COUNTS USING QUERY BUILDER ===============
        $this->db->select("
            COUNT(CASE WHEN status = 'pending' OR status IS NULL THEN 1 END) as pending,
            COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved,
            COUNT(CASE WHEN status = 'create_invoice' THEN 1 END) as create_invoice,
            COUNT(CASE WHEN status = 'courier' THEN 1 END) as courier,
            COUNT(CASE WHEN status = 'in_transit' THEN 1 END) as in_transit,
            COUNT(CASE WHEN status = 'delivered' THEN 1 END) as delivered,
            COUNT(CASE WHEN status = 'awaiting_confirmation' THEN 1 END) as awaiting_confirmation,
            COUNT(CASE WHEN status = 'delivery_confirmed' THEN 1 END) as delivery_confirmed,
            COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected,
            COUNT(*) as total
        ");
        $this->db->from('sale_requests');
        $this->db->where_in('added_by', $user_ids);
        
        $status_counts = $this->db->get()->row();
        // =================================================================
        
        $data['customers']                  = $this->customer_model->get_records();
        $data['suppliers']                  = $this->supplier_model->get_records();
        $data['products']                   = $this->product_model->get_records();
        $data['product_alerts']             = $this->warehouse_products_model->get_records_by_product_alerts();
        $data['expenses']                   = $this->expense_model->get_records();
        $data['sales']                      = $this->db->query("SELECT * FROM sale")->result();
        $data['purchases']                  = $this->purchase_model->get_purchase_records();
        $data['expense_amount']             = $this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, EXPENSE_TRANSACTION_TYPE);
        $data['paid_expense_amount']        = $this->transaction_model->get_total_transaction_amount(null, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);
        $data['sale_amount']                = $this->transaction_model->get_total_transaction_amount(null, SALE_MODULE, SALE_TRANSACTION_TYPE);
        $data['paid_sale_amount']           = $this->transaction_model->get_total_transaction_amount(null, SALE_MODULE, RECEIPT_TRANSACTION_TYPE);
        $data['purchase_amount']            = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);
        $data['paid_purchase_amount']       = $this->transaction_model->get_total_transaction_amount(null, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);
        $data['default_currency']           = $this->company_settings_model->get_company_records();
        $data['status_counts']              = $status_counts;
        
        $year = (int)date('Y');
        $month = (int)date('m');

        $sales_amount_array = array();
        $expenses_amount_array = array();
        $purchase_amount_array = array();

        for ($i=1; $i <= 12 ; $i++)
        {
            if(empty($this->sale_model->sale_by_month_year($year.'-'.sprintf("%02d",$i))))
            {
                $sales_amount_array[] = 0;
            }
            else
            {
                $sales_amount_array[] = $this->sale_model->sale_by_month_year($year.'-'.sprintf("%02d",$i))[0]['amount'];
            }
        }

        for ($i=1; $i <= 12 ; $i++)
        {
            if(empty($this->expense_model->expense_by_month_year($year.'-'.sprintf("%02d",$i))))
            {
                $expenses_amount_array[] = 0;
            }
            else
            {
                $expenses_amount_array[] = $this->expense_model->expense_by_month_year($year.'-'.sprintf("%02d",$i))[0]['amount'];
            }
        }

        for ($i=1; $i <= 12 ; $i++)
        {
            if(empty($this->purchase_model->purchase_by_month_year($year.'-'.sprintf("%02d",$i))))
            {
                $purchase_amount_array[] = 0;
            }   
            else
            {
                $purchase_amount_array[] = $this->purchase_model->purchase_by_month_year($year.'-'.sprintf("%02d",$i))[0]['amount'];
            }
        }
        
        $data['warehouses'] = $this->db->where('delete_status', 0)->get('warehouse')->result_array();
        $data['sales_data'] = $sales_amount_array;
        $data['expense_data'] = $expenses_amount_array;
        $data['purchase_data'] = $purchase_amount_array;
        
        // Get user info for role check
        $data['user_info'] = $this->db->select('role')->where('id', $user_id)->get('users')->row();
        
        // Get total purchases data for the first card
        $session_user_id = $user_id;
        $head_office_count = $this->db_model->count_all('sale_requests', array('added_by' => $session_user_id));
        $branch_users = $this->db->select('id')->where('added_by', $session_user_id)->get('users')->result_array();
        $branch_user_ids = array_column($branch_users, 'id');
        
        $branch_count = 0;
        if (!empty($branch_user_ids)) {
            $this->db->where_in('added_by', $branch_user_ids);
            $branch_count = $this->db->count_all_results('sale_requests');
        }
        $total_count = $head_office_count + $branch_count;
        
        $data['total_count'] = $total_count;
        $data['head_office_count'] = $head_office_count;
        $data['branch_count'] = $branch_count;
        
        // Get branch managers and branches count if client role
        if (!empty($data['user_info']) && $data['user_info']->role == 'client') {
            $data['branch_managers_count'] = $this->db_model->count_all('users', array(
                'role' => 'clients_branch_manager',
                'added_by' => $session_user_id
            ));
            
            $data['branches_count'] = $this->db_model->count_all('clients_branch', array(
                'added_by' => $session_user_id
            ));
        }
        
        // Graph data for client role
        if (!empty($data['user_info']) && $data['user_info']->role == 'client') {
            $branch_users_graph = $this->db->select('id')->where('added_by', $session_user_id)->get('users')->result_array();
            $branch_user_ids_graph = array_column($branch_users_graph, 'id');
            $current_year = date('Y');

            $head_office_data = $this->db->query("
                SELECT MONTH(created_date) as month, COUNT(id) as total
                FROM sale_requests
                WHERE added_by = '$session_user_id' AND YEAR(created_date) = $current_year
                GROUP BY MONTH(created_date)
            ")->result();

            $branch_data_graph = [];
            if (!empty($branch_user_ids_graph)) {
                $ids_str = implode(',', array_map('intval', $branch_user_ids_graph));
                $branch_data_graph = $this->db->query("
                    SELECT MONTH(created_date) as month, COUNT(id) as total
                    FROM sale_requests
                    WHERE added_by IN ($ids_str) AND YEAR(created_date) = $current_year
                    GROUP BY MONTH(created_date)
                ")->result();
            }

            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $head_office_counts = array_fill(1, 12, 0);
            $branch_counts_graph = array_fill(1, 12, 0);

            foreach ($head_office_data as $row) {
                $head_office_counts[(int)$row->month] = (int)$row->total;
            }
            foreach ($branch_data_graph as $row) {
                $branch_counts_graph[(int)$row->month] = (int)$row->total;
            }

            $labels = $months;
            $head_data = [];
            $branch_data_arr = [];
            for ($i = 1; $i <= 12; $i++) {
                $head_data[] = $head_office_counts[$i];
                $branch_data_arr[] = $branch_counts_graph[$i];
            }

            $data['labels'] = $labels;
            $data['head_data'] = $head_data;
            $data['branch_data_arr'] = $branch_data_arr;
            
            // Top branches data
            $top_branch_by_amount = null;
            $top_branch_by_orders = null;
            $top_branches_chart = [];

            if (!empty($branch_user_ids_graph)) {
                $ids_str = implode(',', array_map('intval', $branch_user_ids_graph));

                $top_branch_by_amount = $this->db->query("
                    SELECT cb.branch_name, SUM(sr.total) as total_amount
                    FROM sale_requests sr
                    JOIN clients_branch cb ON cb.id = sr.branch_id
                    WHERE sr.added_by IN ($ids_str) AND YEAR(sr.invoice_date) = $current_year
                    GROUP BY sr.branch_id
                    ORDER BY total_amount DESC
                    LIMIT 1
                ")->row();

                $top_branch_by_orders = $this->db->query("
                    SELECT cb.branch_name, COUNT(sr.id) as total_orders
                    FROM sale_requests sr
                    JOIN clients_branch cb ON cb.id = sr.branch_id
                    WHERE sr.added_by IN ($ids_str) AND YEAR(sr.invoice_date) = $current_year
                    GROUP BY sr.branch_id
                    ORDER BY total_orders DESC
                    LIMIT 1
                ")->row();

                $top_branches_chart = $this->db->query("
                    SELECT cb.branch_name, SUM(sr.total) as total_amount
                    FROM sale_requests sr
                    JOIN clients_branch cb ON cb.id = sr.branch_id
                    WHERE sr.added_by IN ($ids_str) AND YEAR(sr.invoice_date) = $current_year
                    GROUP BY sr.branch_id
                    ORDER BY total_amount DESC
                    LIMIT 5
                ")->result();
            }

            $data['top_branch_by_amount'] = $top_branch_by_amount;
            $data['top_branch_by_orders'] = $top_branch_by_orders;
            $data['chart_labels'] = [];
            $data['chart_values'] = [];
            foreach ($top_branches_chart as $b) {
                $data['chart_labels'][] = $b->branch_name;
                $data['chart_values'][] = round($b->total_amount, 2);
            }
        }
        
        $this->load->view('dashboard2', $data);
    }
    else
    {
        redirect('auth/login');
    }
}
	
public function update_graph_data() {
    $branch_id = $this->input->post('branch_id');
    $year = (int) date('Y');

    $sales_amount_array = [];
    $expenses_amount_array = [];
    $purchase_amount_array = [];

    for ($i = 1; $i <= 12; $i++) {
        $month_year = $year . '-' . sprintf("%02d", $i);

        $sales = $this->sale_model->sale_by_month_year($month_year, $branch_id);
        $sales_amount = !empty($sales) ? (float)$sales[0]['amount'] : 0;
        $sales_amount_array[] = round($sales_amount, 2); // Ensure 2 decimal places

        $expenses = $this->expense_model->expense_by_month_year($month_year, $branch_id);
        $expense_amount = !empty($expenses) ? (float)$expenses[0]['amount'] : 0;
        $expenses_amount_array[] = round($expense_amount, 2); // Ensure 2 decimal places

        $purchases = $this->purchase_model->purchase_by_month_year($month_year, $branch_id);
        $purchase_amount = !empty($purchases) ? (float)$purchases[0]['amount'] : 0;
        $purchase_amount_array[] = round($purchase_amount, 2); // Ensure 2 decimal places
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'sales_data'    => $sales_amount_array,
            'expense_data'  => $expenses_amount_array,
            'purchase_data' => $purchase_amount_array
        ]
    ]);
}


    public function get_branch_data($warehouseId = null)
    {
        $year = (int) date('Y');
    
        $salesData = $this->sale_model->sale_by_year($year, $warehouseId);
        $expenseData = $this->expense_model->expense_by_year($year, $warehouseId);
        $purchaseData = $this->purchase_model->purchase_by_year($year, $warehouseId);
    
        echo json_encode([
            'sales_data' => $salesData,
            'expense_data' => $expenseData,
            'purchase_data' => $purchaseData
        ]);
    }

	function get_sale_expense_chart_data()
	{
		$year = (int)$this->input->post('year');
		$data['sales_data'] 		=	implode(",", array_values($this->sale_model->sale_by_year($year)[0]));
		$data['expense_data'] 	    =	implode(",", array_values($this->expense_model->expense_by_year($year)[0]));	
		$data['purchase_data'] 	    =	implode(",", array_values($this->purchase_model->purchase_by_year($year)[0]));	

		echo json_encode($data);	
	}

	function users()
	{
		if ($this->ion_auth->logged_in())
		{
			if(!$this->permission_model->has_permission('list_user'))
			{
				$this->load->view('errors/html/error_restricted'); 
			}
			else
			{
				$this->data['users'] = $this->ion_auth->users()->result();
				$this->load->view('auth/users',$this->data);	
			}
		}
		else
		{
			redirect('auth/login','refresh');
		}
	}

	function user_roles()
	{
		if ($this->ion_auth->logged_in() && $this->permission_model->has_module_permission('user_role'))
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		if(($this->input->post('id') == '') || ($this->input->post('id') == null))
    		{
    			$group_name = $this->input->post('name');
      		$description = $this->input->post('description');

      		if($id = $this->ion_auth_model->create_group($group_name,$description))
      		{
      			$this->session->set_flashdata('success', $group_name.' is added successfully.');
						redirect('auth/user_roles');	
      		}
      		else
      		{
      			$this->session->set_flashdata('failure', $group_name.' is already exist.');
						redirect('auth/add_user_role');
      		}	
    		}
    		else 
    		{
    			$id 			= $this->input->post('id');
    			$group_name 	= $this->input->post('name');
      		$description 	= $this->input->post('description');

      		$data = array(
      						"description" 	=> $description
      					);

      		if($this->ion_auth_model->update_group($id,$group_name,$data))
      		{
      			$this->session->set_flashdata('success', $group_name.' is updated successfully.');
						redirect('auth/user_roles');	
      		}
      		else
      		{
      			$this->session->set_flashdata('failure', $this->ion_auth->errors());
						redirect('auth/add_user_role/'.$id);
      		}
    		}
    	}
    	else
    	{
    		if(!$this->permission_model->has_permission('list_user_role'))
    		{
    			$this->load->view('errors/html/error_restricted'); 
    		}
    		else
    		{
    			$this->data['modules'] 	= $this->permission_model->get_distinct_module();
      		$this->data['groups'] 	= $this->ion_auth->groups()->result();
      		
					$this->load->view('auth/user_roles',$this->data);	
    		}
    	} 		
		}
		else
		{
			redirect('auth/login','refresh');
		}
	}

	function add_user_role($id = null)
	{
		if ($this->ion_auth->logged_in())
		{
			if($id == null)
			{
				$this->load->view('auth/create_group');		
			}
			else
			{
				$data['group'] = $this->ion_auth_model->group($id)->row();
				$this->load->view('auth/edit_group',$data);			
			}
			
		}
		else
		{
			redirect('auth/login','refresh');			
		}
	}

	public function old_password_verify()
	{
		// $user_id 					= $this->session->userdata('user_id');
		$id 							= $this->input->post('id');
		$current_password = $this->input->post('current_password');
		$user 						= $this->ion_auth->user($id)->row();

		$response 				= array();

		if($this->ion_auth_model->verify_password($current_password,$user->password))
		{
			$response['code'] = 1;
		}
		else
		{
			$response['code'] = 0;
		}

		echo json_encode($response);
	}

	public function profile()
	{
		$id = $this->session->userdata('user_id');

		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();
			
		//USAGE NOTE - you can do more complicated queries like this
		//$groups = $this->ion_auth->where(['field' => 'value'])->groups()->result_array();
	

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
		// $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			// if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			// {
			// 	show_error($this->lang->line('error_csrf'));
			// }

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = [
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					// 'company' => $this->input->post('company'),
					'phone' => $this->input->post('phone'),
				];

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}

				// Only allow updating groups if user is admin
				// if ($this->ion_auth->is_admin())
				// {
				// 	// Update the groups user belongs to
				// 	$this->ion_auth->remove_from_group('', $id);
					
				// 	$groupData = $this->input->post('groups');
				// 	if (isset($groupData) && !empty($groupData))
				// 	{
				// 		foreach ($groupData as $grp)
				// 		{
				// 			$this->ion_auth->add_to_group($grp, $id);
				// 		}

				// 	}
				// }

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data))
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('success', $this->input->post('first_name').' '.$this->input->post('last_name').' is updated successfully.');
					redirect('auth/profile');

				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('auth/profile');

				}

			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;
		

		$this->_render_page('auth/profile', $this->data);
	}

	public function save_permission()
	{
		$response = array();

		$group_id 	= $this->input->post('group_id');
		$permission = $this->input->post('permission');

		$group 		= $this->ion_auth_model->group($group_id)->row();
		$permission_array = explode(",", $permission);

		$message 	= '';

	$this->permission_model->remove_permission_from_group_by_group_id($group_id);

for ($i = 0; $i < sizeof($permission_array); $i++) 
{
    $this->permission_model->add_permission_to_group(array(
        "permission_id" => $permission_array[$i],
        "role_id"       => $group_id
    ));
}

$response['code'] = 1;

		
		$response['message'] = $message."Permissions are updated to ".$group->name;

		echo json_encode($response);
	}

	public function get_permission()
	{
		$group_id 	= $this->input->post('group_id');
		$permission = $this->permission_model->get_permission_by_group_id($group_id);

		$permission_array = array();

		foreach ($permission as $value) {
			$permission_array[] = $value->permission_id;
		}

		$response 				= array();
		$response['code'] 		= 1;
		$response['permission'] = implode(",", $permission_array);
		$response['group_id']	= $group_id;

		echo json_encode($response);
	}
}
