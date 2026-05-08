<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ledger extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	
	public function index()
	{
		if($this->input->is_ajax_request())
		{
			$response			 							= array();
			$response['bank_accounts'] 	= $this->bank_account_model->get_records();

			echo json_encode($response);
		}
		else
		{	
		}
	}

	public function update_capital_ledger()
	{
		$data 						= array();
		$response  	 			= array();
		$ledger_id 				= $this->input->post('ledger_id');
		$amount 					= $this->input->post('transaction_amount');

		$ledger 					= $this->ledger_model->get_single_record($ledger_id);

		$capital_account_entries = $this->ledger_model->get_capital_account_entries();


		if($capital_account_entries == null)
		{
			$data = array(
									"opening_balance" => $amount,
									"closing_balance" => $amount
								);
		}
		else
		{
			$closing_balance = $ledger->closing_balance;

			$data = array(
										"closing_balance" => ($closing_balance + $amount) 
									);		
		}

		if($this->ledger_model->edit_record($data,$ledger_id))
		{
				$capital_entry = array(
																"amount" 	=> $amount,
																"user_id"	=> $this->session->userdata('user_id')
															);

				$this->ledger_model->add_capital_account_entry($capital_entry);

				$response['code'] 	= 1;
				$response['message']= 'Amount '.$amount.' has added to Capital Account successfully.';
		}
		else
		{
				$response['code'] 		= 0;
				$response['message'] 	= 'Amount '.$amount.' has failed to add to Capital Account successfully.';	
		}

		echo json_encode($response);
	}
	
	
		
}
