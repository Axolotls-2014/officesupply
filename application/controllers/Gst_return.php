<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gst_return extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->load->library('zip');
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	
	public function index()
	{
		$response 								= array();
		
		if($this->permission_model->has_module_permission('gst_return'))
		{
			if($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$gst_return_type 					= $this->input->post('gst_return_type');
				$gst_return_type_quarter 	= (int)$this->input->post('gst_return_type_quarter');
				$month 										= sprintf("%02d", $this->input->post('month'));
				$year 										= (int)$this->input->post('year');

				$gst_return_file_type 		= $this->input->post('gst_return_file_type');

				if($gst_return_file_type == GST_RETURN_FILE_TYPE_CSV)
				{
					$b2b_sale 			= $this->gst_return_model->b2b_sale_csv($year, FALSE, $month);
					$b2cl_sale 			= $this->gst_return_model->b2cl_sale_csv($year, FALSE, $month);
					$b2cs_sale 			= $this->gst_return_model->b2cs_sale_csv($year, FALSE, $month);
					$cdnr 					= $this->gst_return_model->cdnr_csv($year, FALSE, $month);
					$cdnur 					= $this->gst_return_model->cdnur_csv($year, FALSE, $month);
					$gstr2 					= $this->gst_return_model->gstr2_csv($year, FALSE, $month);
					
					$b2b_file 			= fopen(FCPATH . 'assets/documents/b2b_sale.csv',"w");
					$b2cl_file			= fopen(FCPATH . 'assets/documents/b2cl_sale.csv',"w");
					$b2cs_file			= fopen(FCPATH . 'assets/documents/b2cs_sale.csv',"w");
					$cdnr_file			= fopen(FCPATH . 'assets/documents/cdnr.csv',"w");
					$cdnur_file			= fopen(FCPATH . 'assets/documents/cdnur.csv',"w");
					$cdnur_file			= fopen(FCPATH . 'assets/documents/cdnur.csv',"w");
					$gstr2_file 		= fopen(FCPATH . 'assets/documents/gstr2.csv',"w");

					$b2b_header = array(
														"GSTIN/UIN of Recipient",
														"Receiver Name",
														"Invoice Number",
														"Invoice date",
														"Invoice Value",
														"Place Of Supply",
														"Reverse Charge",
														"Invoice Type",
														"Rate",
														"Taxable Value",
														"Cess Amount"
													); 
					fputcsv($b2b_file,$b2b_header);

					$b2cl_header = array(
																"Invoice Number",
																"Invoice date",
																"Invoice Value",
																"Place Of Supply",
																"Rate",
																"Taxable Value",
																"Cess Amount",
																"E-Commerce GSTIN"
															); 
					fputcsv($b2cl_file,$b2cl_header);	

					$b2cs_header = array(
																"Type",
																"Place Of Supply",
																"Rate",
																"Taxable Value",
																"Cess Amount",
																"E-Commerce GSTIN"
															); 
					fputcsv($b2cs_file,$b2cs_header);		

					$cdnr_header = array(
																"GSTIN/UIN of Recipient",
																"Receiver Name",
																"Note Number",
																"Note Date",
																"Note Type",
																"Place Of Supply",
																"Reverse Charge",
																"Note Supply Type",
																"Note Value",
																"Rate",
																"Taxable Value",
																"Cess Amount"
															);
					fputcsv($cdnr_file,$cdnr_header);

					$cdnur_header = array(
																"UR Type",
																"Note Number",
																"Note Date",
																"Note Type",
																"Place Of Supply",
																"Note Value",
																"Rate",
																"Taxable Value",
																"Cess Amount"
															);
					fputcsv($cdnur_file,$cdnur_header);

					$gstr2_header = array(
																"GSTIN of Supplier",
																"Supplier Name",
																"Purchase Order No",
																"Invoice No",
																"Purchase Date",
																"Total Taxable Value",
																"Discount",
																"Total Tax",
																"Total"
															);
					fputcsv($gstr2_file,$gstr2_header);
					
					foreach ($b2b_sale as $key) {
					  fputcsv($b2b_file, (array) $key);
					}
					foreach ($b2cl_sale as $key) {
					  fputcsv($b2cl_file, (array) $key);
					}
					foreach ($b2cs_sale as $key) {
					  fputcsv($b2cs_file, (array) $key);
					}
					foreach ($cdnr as $key) {
					  fputcsv($cdnr_file, (array) $key);
					}
					foreach ($cdnur as $key) {
					  fputcsv($cdnur_file, (array) $key);
					}
					foreach ($gstr2 as $key) {
						fputcsv($gstr2_file, (array) $key);
					}

					fclose($b2b_file);
					fclose($b2cl_file);
					fclose($b2cs_file);
					fclose($cdnr_file);
					fclose($cdnur_file);
					fclose($gstr2_file);

					$file_name = 'GSTR1-GSTR2-' .date('m-d-Y-H-i-s').'.zip';
	        // Directory path (uploads directory stored in project root)

	        $b2b_path = FCPATH . 'assets/documents/b2b_sale.csv';
	        $b2cl_path = FCPATH . 'assets/documents/b2cl_sale.csv';
	       	$b2cs_path = FCPATH . 'assets/documents/b2cs_sale.csv';
	       	$cdnr_path = FCPATH . 'assets/documents/cdnr.csv';
	       	$cdnur_path = FCPATH . 'assets/documents/cdnur.csv';
	       	$gstr2_path = FCPATH . 'assets/documents/gstr2.csv';
	        
	       /*	echo "<pre>";
	       	print_r($b2b_path);
	       	exit;*/
	        //Add directory to zip
	        $this->zip->read_file($b2b_path);
	        $this->zip->read_file($b2cl_path);
	        $this->zip->read_file($b2cs_path);
	        $this->zip->read_file($cdnr_path);
	        $this->zip->read_file($cdnur_path);
	        $this->zip->read_file($gstr2_path);
	        
	      
	        // Save the zip file to archivefiles directory
	        $this->zip->archive('./assets/documents/'.$file_name);

	        unlink($b2b_path);
	        unlink($b2cl_path);
	        unlink($b2cs_path);
	        unlink($cdnr_path);
	        unlink($cdnur_path);
	        unlink($gstr2_path);


    	   	$response['code'] 										= 1;
					$response['message'] 									= $file_name.' is ready to download';	
					$response['path'] 										= base_url('assets/documents/'.$file_name);
					$response['file_name']								= $file_name;
			
					$response['gst_return_type'] 					= $gst_return_type;
					$response['gst_return_type_quarter']	= $gst_return_type_quarter;
					$response['month']										= $month;
					$response['year']											= $year;
				}
				else
				{
					$company_setting 					= $this->company_settings_model->get_company_records();

					if($gst_return_type == "0")
					{
						$b2b_sale 							= $this->gst_return_model->b2b_sale($year, FALSE, $month);
						$b2cs_sale 							= $this->gst_return_model->b2cs_sale($year, FALSE, $month);
						$cdnr_sales_return 			= $this->gst_return_model->cdnr_sales_return($year, FALSE, $month);

					}
					else
					{
						$b2b_sale 							= $this->gst_return_model->b2b_sale($year,$gst_return_type_quarter);
						$b2cs_sale 							= $this->gst_return_model->b2cs_sale($year,$gst_return_type_quarter);
						$cdnr_sales_return 			= $this->gst_return_model->cdnr_sales_return($year,$gst_return_type_quarter);
					}

					$b2b_query = $this->db->last_query();

					$b2b 											= array();
					$b2cs 										= array();
					$cdnr 										= array();

					foreach ($b2b_sale as $value) {

						$b2b_tmp 						= array();

						$b2b_tmp['ctin'] 		= $value->customer_gstin;

						$inv 								= array();

						$customer_state_id	= $value->customer_state_id;
						$tmp 								= array();

						$tmp['inum'] 				= $value->reference_no;
						$tmp['idt']					= date('d-m-Y',strtotime($value->invoice_date));
						$tmp['val']					= (float)$value->total;
						$tmp['pos']					= $value->state_code;
						$tmp['rchrg']				= $value->rcm;
						$tmp['inv_typ'] 		= 'R';

						$b2b_sale_items 		= $this->gst_return_model->b2b_sale_items($value->id);

						foreach ($b2b_sale_items as $si_value) 
						{
							$tmp_d 									= array();

							$tmp_d['num']						= $si_value->hsn;

							$tmp_detail 						= array();
							$tmp_detail['txval']		=	(float)$si_value->taxable_value; 
							$tmp_detail['rt']				= (float)($si_value->igst + $si_value->cgst + $si_value->sgst);

							if($customer_state_id == $company_setting->state_id)
							{
								$tmp_detail['camt']		= (float)($si_value->cgst_tax);
								$tmp_detail['samt']		= (float)($si_value->sgst_tax);	
							}
							else
							{
								$tmp_detail['iamt']		= (float)($si_value->igst_tax);		
							}

							$tmp_detail['csamt']		= 0;

							$tmp_d['itm_det'] 			= $tmp_detail; 	

							$tmp['itms'] 		= [$tmp_d];					
						}

						$b2b_tmp['ctin'] 		= $value->customer_gstin;
						$b2b_tmp['inv']			= [$tmp];

						$b2b[] 							= $b2b_tmp;
						
					}

					foreach ($cdnr_sales_return as $value) {

						$cdnr_tmp 						= array();

						$cdnr_tmp['ctin'] 		= $value->customer_gstin;

						$nt 								= array();

						$customer_state_id	= $value->customer_state_id;
						$tmp 								= array();

						$tmp['ntty'] 				= 'C';
						$tmp['nt_num']			= $value->reference_no;
						$tmp['nt_dt']				= date('d-m-Y',strtotime($value->sales_return_date));
						$tmp['p_gst'] 			= 'N';
						$tmp['inum'] 				= $value->invoice_no;

						$sale  							= $this->sale_model->get_sale_single_record_by_reference_no($value->invoice_no);
						$tmp['idt'] 				= date('d-m-Y',strtotime($sale->invoice_date));

						$tmp['val']					= (float)$value->total;
						/*$tmp['pos']					= $value->state_code;*/
						/*$tmp['rchrg']				= $value->rcm;
						$tmp['inv_typ'] 		= 'R';*/

						$cdnr_sales_return_items 		= $this->gst_return_model->cdnr_sales_return_items($value->id);

						foreach ($cdnr_sales_return_items as $si_value) 
						{
							$tmp_d 									= array();
							$tmp_d['num']						= $si_value->hsn;
							$tmp_detail 						= array();
							$tmp_detail['rt']				= (float)($si_value->igst + $si_value->cgst + $si_value->sgst);
							$tmp_detail['txval']		=	(float)$si_value->taxable_value; 
						

							if($customer_state_id == $company_setting->state_id)
							{
								$tmp_detail['camt']		= (float)($si_value->cgst_tax);
								$tmp_detail['samt']		= (float)($si_value->sgst_tax);	
							}
							else
							{
								$tmp_detail['iamt']		= (float)($si_value->igst_tax);		
							}

							$tmp_detail['csamt']		= 0;

							$tmp_d['itm_det'] 			= $tmp_detail; 	

							$tmp['itms'] 		= [$tmp_d];					
						}

						$cdnr_tmp['ctin'] 		= $value->customer_gstin;
						$cdnr_tmp['nt']				= [$tmp];

						$cdnr[] 							= $cdnr_tmp;
						
					}

					foreach ($b2cs_sale as $value) {

						$b2cs_tmp 						= array();
							$tmp 								= array();
						$tmp['pos']						= $value->state_code;
						$customer_state_id	  = $value->customer_state_id;

						$tmp 									= array();
						$tmp['txval']					=	(float)$value->taxable_value; 
						$tmp['pos']						= $value->state_code;
						
						$tmp['rt']						= (float)($value->igst + $value->cgst + $value->sgst);

						if($customer_state_id == $company_setting->state_id)
						{
							$tmp['sply_ty']		= 'INTRA';
						
						}
						else
						{
							$tmp['sply_ty']		= 'INTER';		
						}

						$tmp['typ']		=	'OE';

						if($customer_state_id == $company_setting->state_id)
						{
							$tmp['camt']		= (float)($value->cgst_tax);
							$tmp['samt']		= (float)($value->sgst_tax);	
						}
						else
						{
							$tmp['iamt']		= (float)($value->igst_tax);		
						}
						
						$tmp['csamt']		= 0;

						$b2cs[] 							= $tmp;


						/*$b2cs_tmp['ctin'] 		= $value->customer_gstin;

						$inv 								= array();

						$customer_state_id	= $value->customer_state_id;
						$tmp 								= array();

						$tmp['inum'] 				= $value->reference_no;
						$tmp['idt']					= date('d-m-Y',strtotime($value->invoice_date));
						$tmp['val']					= (float)$value->total;
						$tmp['pos']					= $value->state_code;
						$tmp['rchrg']				= $value->rcm;
						$tmp['inv_typ'] 		= 'R';*/

					/*	$b2cs_sale_items 		= $this->gst_return_model->b2cs_sale_items($value->id);*/

						
						/*$b2cs_tmp['ctin'] 		= $value->customer_gstin;*/
					/*	$b2cs_tmp['inv']			= [$tmp];*/

					
						
					}



					// gstr1

					$gstr1										= array();
					$gstr1['gstin']						= $company_setting->gstin;
					$gstr1['fp']							= strval(sprintf("%02d", $month).$year);
					$gstr1['gt']							= 0;
					$gstr1['cur_gt']					= 0;
					$gstr1['b2b']							= $b2b;
					$gstr1['b2cs']						= $b2cs;
					$gstr1['cdnr']						= $cdnr;

					
					$file_name 								= 'GSTR1_'.$month.'_'.$year.'_'.time().'.json';


					if (!write_file('./assets/gst_documents/'.$file_name, json_encode($gstr1)))
					{
						$response['code'] 		= 0;
						$response['message'] 	= 'Failed to created json file';

						$response['gst_return_type'] 					= $gst_return_type;
						$response['gst_return_type_quarter']	= $gst_return_type_quarter;
						$response['month']										= $month;
						$response['year']											= $year;
					}
					else
					{
					 	$response['code'] 										= 1;
						$response['message'] 									= $file_name.' is ready to download';	
						$response['path'] 										= base_url('assets/gst_documents/'.$file_name);
						$response['file_name']								= $file_name;
						$response['query']										= $b2b_query;

						$response['gst_return_type'] 					= $gst_return_type;
						$response['gst_return_type_quarter']	= $gst_return_type_quarter;
						$response['month']										= $month;
						$response['year']											= $year;
					}
				}

				echo json_encode($response);
			}
			else
			{
				$data['company_setting'] = $this->company_settings_model->get_company_records();
				$this->load->view('gst_return/index',$data);	
			}	
		}
		
	}
}
