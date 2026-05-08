<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Log_data extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('log_data_model','permission_model'));
		
		$this->load->model('permission_model');
		$this->load->model('ion_auth_model');
		$this->load->library(array('ion_auth','form_validation'));

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
	}

	/*
		Display All purchase Details
	*/

	public function index()
{
    $data['log']      = $this->log_data_model->get_records();
    $data['users']    = $this->ion_auth->users()->result();
    $data['modules']  = $this->permission_model->get_distinct_module();

    // Just load the view, no logging here
    $this->load->view('log_data/list', $data);
}


	public function create_full_csv()
  {
        if(!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
        	$where = '';
        	$delete_where = array();

					$module 	= $this->input->post('module');
					$user_id 	= $this->input->post('user_id');
					$delete 	= $this->input->post('delete');
					$from_date 	= $this->input->post('from_date');
					$to_date 	= $this->input->post('to_date');
					
					if(isset($module)){
						if($module != ''){
							$where .= 'WHERE ld.module = "'.$module.'" ';
						}	
					}

					if(isset($user_id)){				
						if($user_id != ''){
							if($where == ''){
								$where .= "WHERE ld.user_id =".$user_id;
							}else{
								$where .= " AND ld.user_id =".$user_id;
							}
						}
					}

					if($from_date != ''){
						$from_date = date("Y-m-d H:i:s", strtotime($from_date));
						if($where == ''){
							$where .= "WHERE ld.date_created >='".$from_date."'";
						}else{
							$where .= " AND ld.date_created >= '".$from_date."'";
						}
					}

					if($to_date != ''){
						$to_date = date("Y-m-d H:i:s", strtotime($to_date));
						if($where == ''){
							$where .= "WHERE ld.date_created <='".$to_date."'";
						}else{
							$where .= " AND ld.date_created <='".$to_date."'";
						}
					}

				
					$sql = 'SELECT 
								CONCAT(u.first_name," ",u.last_name) as "User Name",
								ld.module,
								ld.description,
								ld.referer_url,
								ld.browser,
								ld.ip_address,
								DATE_FORMAT(ld.date_created,"%d-%m-%Y %h:%i:%s") AS "Datetime",
								ld.b_data AS "Before",
								ld.data AS "After"
	                                FROM 
	                            log_data ld LEFT JOIN 
	                            users u ON u.id = ld.user_id 
	                    	'.$where;

					$query = $this->db->query($sql);

	        $this->load->dbutil();
	        
	        $data = $this->dbutil->csv_from_result($query);
	        
	        if(isset($delete))
	        {

	        	$this->db->where(str_replace(array("WHERE","ld."), array("",""), $where));
	        	$this->db->delete('log_data');
	        }

	        $this->load->helper('download');

	        if($this->input->post('from_date') == '' && $this->input->post('to_date') == '')
	        {
	        	if($user_id != '')
	        	{
	        		$user = $this->ion_auth_model->user($user_id)->row();
	        		$user_name = (ucwords($user->first_name).ucwords($user->last_name));

	        		force_download($user_name."_HistoryLog_till_".date("dmY").".csv", $data);	
	        	}
	        	else
	        	{
	        		force_download("HistoryLog_till_".date("dmY").".csv", $data);		
	        	}
	        	
	        }
	        else
	        {
	        	if($user_id != ''){

	        		$user = $this->ion_auth_model->user($user_id)->row();
	        		$user_name = (ucwords($user->first_name).ucwords($user->last_name));

	        		force_download($user_name."_HistoryLog_".str_replace("-","",$this->input->post('from_date'))."_To_".str_replace("-","",$this->input->post('to_date')).".csv", $data);	
	        	}
	        	else
	        	{
	        		force_download("HistoryLog_".str_replace("-","",$this->input->post('from_date'))."_To_".str_replace("-","",$this->input->post('to_date')).".csv", $data);		
	        	}
	        }

		        
        }
    }

	

	

}