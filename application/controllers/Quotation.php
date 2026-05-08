<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Quotation extends MY_Controller {

	private $pdf;
	public function __construct()
	{
		parent::__construct();
		$this->pdf = new Dompdf();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}
	
	public function index()
  {
    if (!$this->permission_model->has_permission('list_quotation')) {
        $this->load->view('errors/html/error_restricted'); 
    } else {    
        $user_id = $this->session->userdata('user_id');
        
        $user = $this->db->select('branch_id')
                         ->from('users')
                         ->where('id', $user_id)
                         ->get()
                         ->row();
                         
        if ($user) {
            $branch_id = $user->branch_id;
            
            $data['quotation'] = $this->quotation_model->get_quotation_records(null, $branch_id);
            $data['customer'] = $this->customer_model->get_records();
            
            // $log_data = array(
            //     "user_id"     => $user_id,
            //     "module"      => "quotation",
            //     "user_action" => 0,
            //     "description" => "User has viewed list of quotation."
            // );
            // $this->log_data_model->add_record($log_data);

            $this->load->view('quotation/list', $data);
        } else {
            show_error('User or branch not found');
        }
    }
}
public function add()
{
    if(!$this->permission_model->has_permission('add_quotation'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {	
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            
            // echo "<pre>";
            // print_r($this->input->post());
            // echo "</pre>";
            // die;
            
            $this->form_validation->set_rules('quotation_date','Quotation Date','required');		
            $this->form_validation->set_rules('customer_id','Customer','required');
            $this->form_validation->set_rules('warehouse_id','Warehouse','required');

            if($this->form_validation->run()==FALSE)
            {
                $data['customers'] 				= $this->customer_model->get_records();
                $data['company_setting']	    = $this->company_settings_model->get_company_records();	
                $data['warehouses'] 			= $this->warehouse_model->get_records();

                $this->load->view('quotation/add',$data);
            }
            else
            {
                // Get profit data from form
                $profit_data = json_decode($this->input->post('profit_data'), true);
                $total_purchase_cost = isset($profit_data['total_purchase_cost']) ? $profit_data['total_purchase_cost'] : 0;
                $additional_cost_amount = isset($profit_data['freight_amount']) ? $profit_data['freight_amount'] : 0;
                $total_selling_price = isset($profit_data['total_selling_price']) ? $profit_data['total_selling_price'] : 0;
                $gross_profit_loss = isset($profit_data['gross_profit_loss']) ? $profit_data['gross_profit_loss'] : 0;
                $profit_margin = isset($profit_data['profit_margin']) ? $profit_data['profit_margin'] : 0;
                
                $freightData = json_decode($this->input->post('freight_data'), true);
                
                if (!$freightData) {
                    $freightData = [
                        'freight_selling_price' => 0,
                        'freight_taxable_value' => 0,
                        'freight_sub_total' => 0,
                        'freight_tax_rate' => 0,
                        'freight_tax_amount' => 0
                    ];
                }

                $quotation_date 			= date('Y-m-d', strtotime($this->input->post('quotation_date')));
                $valid_days = $this->input->post('valid_days');
                $reference_no 				= $this->quotation_model->get_lastest_sequence_number();
                $customer_id 				= $this->input->post('customer_id');
                
                $selected_shipping_address_id = $this->input->post('shipping_address_select');
                $customer_shipping_country_id = $this->input->post('customer_shipping_country_id');
                $customer_shipping_state_id = $this->input->post('customer_shipping_state_id');
                $customer_shipping_city_id = $this->input->post('customer_shipping_city_id');
                $customer_shipping_address = $this->input->post('customer_shipping_address');
                $customer_shipping_pincode = $this->input->post('customer_shipping_pincode');
                
                $warehouse_id 				= $this->input->post('warehouse_id');
                $rcm 				    	= $this->input->post('rcm');
                $total_taxable_value 	    = $this->input->post('total_taxable_value');
                $total_discount 			= $this->input->post('total_discount');
                $total_tax 				    = $this->input->post('total_tax');
                $total 						= $this->input->post('total');
                $internal_note 				= nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note 				= nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $bank_detail 			    = nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition 	    = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
                $additional_cost_type       = $this->input->post('additional_cost_type');

                $quotation_data = array(
                    "quotation_date" => $quotation_date,
                    "reference_no" => $reference_no,
                    "warehouse_id" => $warehouse_id,
                    "customer_id" => $customer_id,
                    
                    "selected_shipping_address_id" => $selected_shipping_address_id,
                    "customer_shipping_country_id" => $customer_shipping_country_id,
                    "customer_shipping_state_id" => $customer_shipping_state_id,
                    "customer_shipping_city_id" => $customer_shipping_city_id,
                    "customer_shipping_address" => $customer_shipping_address,
                    "customer_shipping_pincode" => $customer_shipping_pincode,
                    
                    "rcm" => $rcm,
                    "total_taxable_value" => $total_taxable_value,
                    "total_tax" => $total_tax,
                    "total_discount" => $total_discount,
                    "total" => $total,
                    "internal_note" => $internal_note,
                    "external_note" => $external_note,
                    "bank_detail" => $bank_detail,
                    "terms_and_condition" => $terms_and_condition,
                    "user_id" => $this->session->userdata('user_id'),
                    "additional_cost_type" => $additional_cost_type,
                    "additional_cost_amount" => $additional_cost_amount,
                    "total_purchase_cost" => $total_purchase_cost,
                    "total_selling_price" => $total_selling_price,
                    "gross_profit_loss" => $gross_profit_loss,
                    "profit_margin" => $profit_margin,
                    "freight_selling_price" => $freightData['freight_selling_price'],
                    "freight_taxable_value" => $freightData['freight_taxable_value'],
                    "freight_sub_total" => $freightData['freight_sub_total'],
                    "freight_tax_rate" => $freightData['freight_tax_rate'],
                    "freight_tax_amount" => $freightData['freight_tax_amount']
                );

                if($id = $this->quotation_model->add_quotation_record($quotation_data))
                {
                    $entered_quotation = $this->quotation_model->get_quotation_single_record($id);

                    $log_data = array(
                        "user_id" => $this->session->userdata("user_id"),
                        "module" => "quotation",
                        "entry_id" => $id,
                        "user_action" => 1,
                        "data" => json_encode((array)$entered_quotation),
                        "description" => 'Quotation (Reference No. -'  .$reference_no .') is added successfully.'
                    );
                    $this->log_data_model->add_record($log_data);

                    $quotation_items = $this->input->post('quotation_items');
                    $quotation_items_array = explode("|", $this->input->post('quotation_items'));

                    for ($i=0; $i < sizeof($quotation_items_array) ; $i++) { 
                        $temp_quotation_item = (array)json_decode($quotation_items_array[$i]);
                        $temp_quotation_item['quotation_id'] = $id;

                        if($temp_quotation_item['expiry_date'] == '' || $temp_quotation_item['expiry_date'] == '0000-00-00')
                            unset($temp_quotation_item['expiry_date']);
                        else
                            $temp_quotation_item['expiry_date'] = date('Y-m-d',strtotime($temp_quotation_item['expiry_date']));

                        if($temp_quotation_item['mfg_date'] == '' || $temp_quotation_item['mfg_date'] == '0000-00-00')
                            unset($temp_quotation_item['mfg_date']);
                        else
                            $temp_quotation_item['mfg_date'] = date('Y-m-d',strtotime($temp_quotation_item['mfg_date']));

                        // Add supplier and cost data to quotation items
                        if(isset($temp_quotation_item['supplier_id'])) {
                            $temp_quotation_item['supplier_id'] = $temp_quotation_item['supplier_id'];
                        }
                        if(isset($temp_quotation_item['purchase_cost'])) {
                            $temp_quotation_item['purchase_cost'] = $temp_quotation_item['purchase_cost'];
                        }
                        if(isset($temp_quotation_item['vendor_total_with_gst'])) {
                            $temp_quotation_item['vendor_total_with_gst'] = $temp_quotation_item['vendor_total_with_gst'];
                        }

                        $this->quotation_model->add_quotation_item_record($temp_quotation_item);
                    }

                    $this->session->set_flashdata('success',  'Quotation (Reference No. -'  .$reference_no .') is added successfully.');
                    redirect('quotation','refresh');
                }
                else
                {
                    $this->session->set_flashdata('failure',  'Quotation (Reference No. -'  .$reference_no .') is failed to add.');
                    redirect('quotation','refresh');
                }
            }
        }
        else
        {
            $data['countries'] = $this->utility_model->get_countries();
            $data['states'] = $this->utility_model->get_states(101);
            $data['customers'] = $this->customer_model->get_records();
            $data['suppliers'] = $this->supplier_model->get_records();
            $data['company_setting'] = $this->company_settings_model->get_company_records();	
            $data['warehouses'] = $this->warehouse_model->get_records();
            $this->load->view('quotation/add',$data);
        }
    }
}

public function edit($id = null)
{
    if(!$this->permission_model->has_permission('edit_quotation'))
    {
        $this->load->view('errors/html/error_restricted'); 
    }
    else
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $quotation_id = $this->input->post('id');
            $old_quotation = $this->quotation_model->get_quotation_single_record($id);

            $this->form_validation->set_rules('quotation_date','quotation Date','required');        
            $this->form_validation->set_rules('customer_id','Customer','required');
            $this->form_validation->set_rules('warehouse_id','Warehouse','required');
            
            if($this->form_validation->run()==FALSE)
            {
                $data['customers']           = $this->customer_model->get_records();
                $data['company_setting']    = $this->company_settings_model->get_company_records();    
                $data['warehouses']             = $this->warehouse_model->get_records();
                $this->load->view('quotation/add',$data);
            }
            else
            {
                // Decode profit and freight data
                $profit_data = json_decode($this->input->post('profit_data'), true);
                $total_purchase_cost = isset($profit_data['total_purchase_cost']) ? $profit_data['total_purchase_cost'] : 0;
                $total_selling_price = isset($profit_data['total_selling_price']) ? $profit_data['total_selling_price'] : 0;
                $gross_profit_loss = isset($profit_data['gross_profit_loss']) ? $profit_data['gross_profit_loss'] : 0;
                $profit_margin = isset($profit_data['profit_margin']) ? $profit_data['profit_margin'] : 0;
                
                $freightData = json_decode($this->input->post('freight_data'), true);
                
                if (!$freightData) {
                    $freightData = [
                        'freight_price' => 0,
                        'freight_taxable_value' => 0,
                        'freight_sub_total' => 0,
                        'freight_tax_rate' => 0,
                        'freight_tax_amount' => 0
                    ];
                }

                $quotation_date               = date('Y-m-d', strtotime($this->input->post('quotation_date')));
                $valid_days = $this->input->post('valid_days');
                $warehouse_id                 = $this->input->post('warehouse_id');
                $customer_id                  = $this->input->post('customer_id');
                $selected_shipping_address_id = $this->input->post('shipping_address_select');
                $customer_shipping_country_id = $this->input->post('customer_shipping_country_id');
                $customer_shipping_state_id   = $this->input->post('customer_shipping_state_id');
                $customer_shipping_city_id    = $this->input->post('customer_shipping_city_id');
                $customer_shipping_address    = $this->input->post('customer_shipping_address');
                $customer_shipping_pincode    = $this->input->post('customer_shipping_pincode');                    
                $rcm                          = $this->input->post('rcm');
                $reference_no                 = $this->input->post('reference_no');
                $total_taxable_value          = $this->input->post('total_taxable_value');
                $total_discount               = $this->input->post('total_discount');
                $total_tax                    = $this->input->post('total_tax');
                $total                        = $this->input->post('total');
                $internal_note                = nl2br(htmlentities($this->input->post('internal_note'), ENT_QUOTES, 'UTF-8'));
                $external_note                = nl2br(htmlentities($this->input->post('external_note'), ENT_QUOTES, 'UTF-8'));
                $bank_detail                  = nl2br(htmlentities($this->input->post('bank_detail'), ENT_QUOTES, 'UTF-8'));
                $terms_and_condition          = nl2br(htmlentities($this->input->post('terms_and_condition'), ENT_QUOTES, 'UTF-8'));
                
                $quotation_data = array(
                    "quotation_date"                => $quotation_date,
                    "customer_id"                   => $customer_id,
                    "selected_shipping_address_id"  => $selected_shipping_address_id,
                    "customer_shipping_country_id"  => $customer_shipping_country_id,
                    "customer_shipping_state_id"    => $customer_shipping_state_id,
                    "customer_shipping_city_id"     => $customer_shipping_city_id,
                    "customer_shipping_address"     => $customer_shipping_address,
                    "customer_shipping_pincode"     => $customer_shipping_pincode,                                        
                    "warehouse_id"                  => $warehouse_id,
                    "rcm"                           => $rcm,
                    "total_taxable_value"          => $total_taxable_value,    
                    "total_tax"                     => $total_tax,
                    "total_discount"                => $total_discount,    
                    "total"                         => $total,
                    "internal_note"                => $internal_note,
                    "external_note"                 => $external_note,
                    "bank_detail"                   => $bank_detail,
                    "terms_and_condition"           => $terms_and_condition,
                    "additional_cost_type"          => $this->input->post('additional_cost_type'),
                    "additional_cost_amount"        => $this->input->post('additional_cost_amount'),
                    "total_purchase_cost"           => $total_purchase_cost,
                    "total_selling_price"           => $total_selling_price,
                    "gross_profit_loss"            => $gross_profit_loss,
                    "profit_margin"                 => $profit_margin,
                    'freight_selling_price'                => $freightData['freight_price'],
                    'freight_taxable_value'         => $freightData['freight_taxable_value'],
                    'freight_sub_total'            => $freightData['freight_sub_total'],
                    'freight_tax_rate'             => $freightData['freight_tax_rate'],
                    'freight_tax_amount'           => $freightData['freight_tax_amount'],
                    "user_id"                       => $this->session->userdata('user_id')
                );

                if($this->quotation_model->edit_quotation_record($quotation_data,$quotation_id))
                {
                    $entered_quotation = $this->quotation_model->get_quotation_single_record($quotation_id);

                    $log_data = array(
                        "user_id"       => $this->session->userdata("user_id"),
                        "module"        => "quotation",
                        "user_action"   => 2,
                        "b_data"        => json_encode((array)$old_quotation),
                        "data"          => json_encode((array)$entered_quotation),
                        "description"   => 'Quotation (Reference No. -'  .$reference_no .') is updated successfully.'
                    );
                    $this->log_data_model->add_record($log_data);
                    $this->quotation_model->remove_quotation_item_records($quotation_id);

                    $quotation_items       = $this->input->post('quotation_items');
                    $quotation_items_array = explode("|", $this->input->post('quotation_items'));

                    for ($i=0; $i < sizeof($quotation_items_array) ; $i++) { 
                                
                        $temp_quotation_item = (array)json_decode($quotation_items_array[$i]);
                        $temp_quotation_item['quotation_id']  = $quotation_id;

                        // Handle supplier_id if exists
                        if(isset($temp_quotation_item['supplier_id'])) {
                            $temp_quotation_item['supplier_id'] = $temp_quotation_item['supplier_id'];
                        }

                        // Handle purchase_cost if exists
                        if(isset($temp_quotation_item['purchase_cost'])) {
                            $temp_quotation_item['purchase_cost'] = $temp_quotation_item['purchase_cost'];
                        }

                        if($temp_quotation_item['expiry_date'] == '' || $temp_quotation_item['expiry_date'] == '0000-00-00')
                            unset($temp_quotation_item['expiry_date']);
                        else
                            $temp_quotation_item['expiry_date'] = date('Y-m-d',strtotime($temp_quotation_item['expiry_date']));

                        if($temp_quotation_item['mfg_date'] == '' || $temp_quotation_item['mfg_date'] == '0000-00-00')
                            unset($temp_quotation_item['mfg_date']);
                        else
                            $temp_quotation_item['mfg_date'] = date('Y-m-d',strtotime($temp_quotation_item['mfg_date']));

                        $this->quotation_model->add_quotation_item_record($temp_quotation_item);
                    }

                    $this->session->set_flashdata('success',  'Quotation (Reference No. -'  .$reference_no .') is updated successfully.');
                    redirect('quotation','refresh');
                }
                else
                {
                    $this->session->set_flashdata('failure',  'Quotation (Reference No. -'  .$reference_no .') is failed to update.');
                    redirect('quotation','refresh');
                }
            }
        }
        else
        {
            $id = base64_decode($id);

            if($id != null)
            {
                $data['quotation']               = $this->quotation_model->get_quotation_single_record($id);

                if($data['quotation'] != null)
                {
                   $data['countries']        = $this->utility_model->get_countries();

                    if($data['quotation']->customer_shipping_country_id != '')
                      $data['states']        = $this->utility_model->get_states($data['quotation']->customer_shipping_country_id);
        
                    if($data['quotation']->customer_shipping_state_id != '')
                      $data['cities']        = $this->utility_model->get_cities($data['quotation']->customer_shipping_state_id);                       

                    $data['quotation_items']     = $this->quotation_model->get_quotation_item_records($id);
        
                     $this->load->model('supplier_model');
                                foreach($data['quotation_items'] as &$item) {
                                    $item->product_vendors = $this->supplier_model->get_vendors_by_product($item->product_id);
                                }
        
                    $data['customers']               = $this->customer_model->get_records();
                    $data['customer_detail']    = $this->customer_model->get_single_record($data['quotation']->customer_id);
                    $data['discounts']              = $this->discount_model->get_records();
                    $data['company_setting']    = $this->company_settings_model->get_company_records();
                    $data['customer_shipping_addresses'] = $this->customer_model->get_customer_shipping_addresses($data['quotation']->customer_id);
                    
                    $data['warehouses']             = $this->warehouse_model->get_records();
                    $data['suppliers']          = $this->supplier_model->get_records();

                    $this->load->view('quotation/edit',$data);
                }
                else
                {
                    $this->session->set_flashdata('failure',  'You have tried to access broken URL.');
                    redirect('quotation','refresh');
                }
                
            }
            else
            {
                $this->session->set_flashdata('failure',  'You have tried to access broken URL.');
                redirect('quotation','refresh');
            }
        }
    }
}

	function delete()
	{
		if(!$this->permission_model->has_permission('delete_quotation'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$id = $this->input->post('id');
			$quotation = $this->quotation_model->get_quotation_single_record($id);
			$data = array('delete_status' => 1);

			if($this->quotation_model->edit_quotation_record($data,$id))
			{
				$this->session->set_flashdata('success', 'Quotation (Reference No. -'  .$quotation->reference_no .') is deleted successfully.');
				redirect('quotation','refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Quotation (Reference No. -'  .$quotation->reference_no .') is failed to delete.');
				redirect('quotation','refresh');
			}
		}
		
	}

	public function view($id = null)
	{
		if(!$this->permission_model->has_permission('view_quotation'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{	
			if($id != null)
			{
				$id = base64_decode($id);

				$data['quotation'] 			= $this->quotation_model->get_quotation_single_record($id);

				if($data['quotation'] != null)
				{
					$data['quotation_items'] 	= $this->quotation_model->get_quotation_item_records($id);
					$data['customers'] 			= $this->customer_model->get_records();
					$data['customer_detail']	= $this->customer_model->get_single_record($data['quotation']->customer_id);
					$data['discounts']			= $this->discount_model->get_records();
					$data['company_setting']	= $this->company_settings_model->get_company_records();	
                    $warehouse_id = $data['quotation']->warehouse_id;
                    $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);
                    $data['shipping_country'] = $this->db
    ->get_where('countries', ['id' => $data['quotation']->customer_shipping_country_id])
    ->row();

$data['shipping_state'] = $this->db
    ->get_where('states', ['id' => $data['quotation']->customer_shipping_state_id])
    ->row();

$data['shipping_city'] = $this->db
    ->get_where('cities', ['id' => $data['quotation']->customer_shipping_city_id])
    ->row();

					$this->load->view('quotation/view',$data);
				}
				else
				{
					$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
					redirect('quotation','refresh');
				}

			
			}
			else
			{
				$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
				redirect('quotation','refresh');
			}
		}
	}

	public function pdf($id = null)
	{
		if(!$this->permission_model->has_permission('pdf_quotation'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			if($id != null)
			{
				$id 												= base64_decode($id);
				$data['quotation'] 					= $this->quotation_model->get_quotation_single_record($id);
				$data['quotation_items'] 		= $this->quotation_model->get_quotation_item_records($id);
				$data['customers'] 					= $this->customer_model->get_records();
				$data['customer_detail']		= $this->customer_model->get_single_record($data['quotation']->customer_id);
				$data['discounts']					= $this->discount_model->get_records();
				$data['company_setting']		= $this->company_settings_model->get_company_records();	
                $warehouse_id = $data['quotation']->warehouse_id;
                $data['warehouse'] = $this->warehouse_model->get_single_record($warehouse_id);
				$html = $this->load->view('quotation/pdf',$data,true);
				// echo $html;
				// exit;
				
				$this->pdf->loadHtml($html);
				$this->pdf->render();
				$this->pdf->stream("Quotation-".preg_replace('/\s+/', '-', strtoupper($data['customer_detail']->customer_name)).'-'.$data['quotation']->reference_no.".pdf", array("Attachment"=>1));
			}
			else
			{
				$this->session->set_flashdata('failure',  'You have tried to access broken URL.');
				redirect('quotation','refresh');
			}
		}
	}

	public function import_product()
  {
      if (!$this->permission_model->has_permission('import_product')) 
      {
          $this->load->view('errors/html/error_restricted');
      } 
      else 
      {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
          // Check if 'csvfile' file input exists
          if (isset($_FILES['csvfile'])) {
             
              if (is_uploaded_file($_FILES['csvfile']['tmp_name'])) {
                  $csvData = $this->csvreader->parse_csv($_FILES['csvfile']['tmp_name']);
      
                  $ProductData = array();
                  $customer_id = $this->input->post('customer_id'); 
                  $warehouse_id = $this->input->post('warehouse_id'); 

                  $discount = $this->discount_model->get_valid_records();
      

                  $i = 0;
                  $no_of_row_in_csv = sizeof($csvData);
                  $not_matched_products = array();
      
                  if (!empty($csvData)) 
                  {   
                    //$no_of_row_in_csv = sizeof($csvData);
                    foreach ($csvData as $row) 
                    {
                      $matchedProduct = $this->product_core_model->get_records_by_pid($row['PID']);

											// Print matched products (for debugging purposes)
                      if ($matchedProduct != null) 
                      {
                      
                        $i++;
                        $product = $this->product_core_model->get_matched_single_record($matchedProduct->id);

												// Populate product data with imported values
												$product->cost = $row['Cost'];
												$product->price = $row['Price'];
											
												$product->quantity = $row['QTY'];
										

												$ProductData[] = $product;
											}
                      else
                      {
                        $not_matched_products[] = $row['Name'].'_'.$row['PID'];                        
                      }
                    }
                    
                    if($i > 0)
                    {
                      $responseData = array(
                          'code' => 1,
                          'ProductData' => $ProductData,
                         	'discount' => $discount,
                         	'message' => ''
                      );

                      if($i != $no_of_row_in_csv)
                        $responseData['message'] = implode("<br/>",$not_matched_products).' <br/> products are not exist in system';
                    
                    }
                    else
                    {
                      $responseData = array(
                          'code' => 0,
                          'message' => 'No record(s) are found in system. Please check PID.'
                      );
                    }
                    

                    
                    // Send matched product IDs along with hold quantities and newPtd as a response
                    echo json_encode($responseData);
                  } 
                  else 
                  {

                    echo json_encode(array('code' => 0, 'message' => 'No record(s) are found in system. Please check PID.'));
                  }
              } 
              else 
              {
                echo json_encode(array('code' => 0, 'message' => 'Failed to upload CSV file.'));
              }
          } 
          else 
          {
            echo json_encode(array('code' => 0, 'message' => 'CSV file not present.'));
          }
        }
        else 
        {
          if ($this->input->is_ajax_request()) 
          {
            $data['product_categories'] = $this->product_category_model->get_records();
            $data['uoms'] = $this->uom_model->get_records();

            $response = array();
            $response['code'] = 1;
            $response['import_product_modal_body'] = $this->load->view('quotation/ajax/import_product_modal_body', $data, TRUE);

            echo json_encode($response);
          } 
          else 
          {
            $this->load->view('errors/html/error_restricted');
          }
        }
      }
  }

	public function export_products()
	{
			$this->db->select('p.pid as "PID",
												 p.name as Name,
												 p.cost as Cost,
												 p.price as Price,
												 p.selling_price as SellingPrice,
												 "" as "QTY",
												 (SELECT COALESCE(SUM(wp.quantity), 0) 
													FROM warehouse_products wp 
													WHERE wp.product_id = p.id) as "AvailableQuantity"
												 '); 
	
			$this->db->from('product p');
			// $this->db->where('wp.batch_no IS NOT NULL AND wp.batch_no != ""');
			$this->db->order_by('p.pid' , 'asc');
			$query = $this->db->get();		  
	
			$this->load->dbutil();
			$data = $this->dbutil->csv_from_result($query);
			
			$this->load->helper('download');
			force_download("AllProductWithPID.CSV", $data);
	}

	public function change_status()
	{
		if(!$this->permission_model->has_permission('edit_quotation'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{	
			$quotation_id = $this->input->post('id');
			$status 			= $this->input->post('change_status');

			$data 				= array('status' => $status);

			if($this->quotation_model->edit_quotation_record($data,$quotation_id))
			{
				$this->session->set_flashdata('success',  'Quotation status is changed to '.strtoupper($status));

				redirect('quotation/view/'.base64_encode($quotation_id),'refresh');
			}
			else
			{
				$this->session->set_flashdata('success',  'Quotation status is failed to change to '.strtoupper($status));
				redirect('quotation/view/'.base64_encode($quotation_id),'refresh');
			}
		}
	}

	function copy($quotation_id)
	{	
		$quotation_id 		= base64_decode($quotation_id);

		
		$quotation 				= $this->quotation_model->get_quotation_single_record($quotation_id);
		$quotation_items 	= $this->quotation_model->get_quotation_item_records($quotation_id);
		$customer 				= $this->customer_model->get_single_record($quotation->customer_id);

    $reference_no 				= $this->quotation_model->get_lastest_sequence_number();
		


		$quotation_data = array(
												"quotation_date"				=> date('Y-m-d'),
												"reference_no"				=> $reference_no,
												"warehouse_id"	  		=> $quotation->warehouse_id,
												"customer_id"					=> $quotation->customer_id,
												"rcm"									=> $quotation->rcm,
												"total_taxable_value" => $quotation->total_taxable_value,	
												"total_tax"						=> $quotation->total_tax,
												"total_discount" 			=> $quotation->total_discount,	
												"total" 							=> $quotation->total,
												"internal_note"				=> $quotation->internal_note,
												"external_note" 			=> $quotation->external_note,
												"bank_detail" 				=> $quotation->bank_detail,
												"terms_and_condition" => $quotation->terms_and_condition,
												"user_id" 						=> $this->session->userdata('user_id')
											);

		// being transaction
		$this->db->trans_begin();

		if($id = $this->quotation_model->add_quotation_record($quotation_data))
		{
			$entered_quotation 	= $this->quotation_model->get_quotation_single_record($id);
			$customer 			= $this->customer_model->get_single_record($entered_quotation->customer_id);

		
			$log_data = array(
								"user_id" 		=> $this->session->userdata("user_id"),
								"module"			=> "quotation",
								"entry_id"		=> $id,
								"user_action"	=> 1,
								"data"				=> json_encode((array)$entered_quotation),
								"description" => 'Quotation (Reference No. -'  .$quotation_data['reference_no'] .') is added successfully.'
							);
			$this->log_data_model->add_record($log_data);
			
			foreach ($quotation_items as $value) {
				
				$product = $this->product_model->get_single_record($value->product_id);
				$uom = $this->uom_model->get_single_record($product->uom_id);

				$temp_quotation_item = array(
																'product_id'       => $value->product_id,
                                'warehouse_product_id' => $value->warehouse_product_id,
										            'product_name'     => $value->product_name,
										            'description'      => $value->description,
										            'quantity'         => $value->quantity,
										            'price'            => $value->price,
										            'cost'             => $value->cost,
										            'taxable_value'    => $value->taxable_value,
										            'discount_id'      => $value->discount_id,
										            'discount_type'    => $value->discount_type,
										            'discount_value'   => $value->discount_value,
										            'discount_amount'  => $value->discount_amount,
										            'uom_id'           => $uom->id,
										            'uom_name'         => $uom->name,
										            'uom_uom'          => $uom->uom,
										            'tax_id'           => $value->tax_id,
										            'tax_type'         => $value->tax_type,
										            'igst'             => $value->igst,
										            'igst_tax'         => $value->igst_tax,
										            'cgst'             => $value->cgst,
										            'cgst_tax'         => $value->cgst_tax,
										            'sgst'             => $value->sgst,
										            'sgst_tax'         => $value->sgst_tax,
										            'sub_total'        => $value->sub_total,
										            'quotation_id' 		 => $id
 															);

				$this->quotation_model->add_quotation_item_record($temp_quotation_item);
			}

			// commit transaction
			$this->db->trans_commit();

			$this->session->set_flashdata('success',  'Quotation (Reference No. -'  .$quotation_data['reference_no'] .') is added successfully.');
			redirect('quotation/view/'.base64_encode($id),'refresh');
		}
		else
		{
			// rollback transaction
			$this->db->trans_rollback();
			
			$this->session->set_flashdata('failure',  'Quotation (Reference No. -'  .$quotation_data['reference_no'] .') is failed to add.');
			redirect('quotation/view/'.base64_encode($id),'refresh');
		}	
	}

	public function send_email($quotation_id = null)
	{

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			
			$this->form_validation->set_rules("to_mail","To Email","required");
			$this->form_validation->set_rules("subject","Subject","required");
			$this->form_validation->set_rules("message","Message","required");

			if($this->form_validation->run()==FALSE)
			{
				$response = array(
						'code' => 0,
						'errors' => $this->form_validation->error_array() // Get validation errors
				);
				echo json_encode($response);

			}
			else
			{	

				$from_name 	= $this->input->post("from_name");
				$from_mail 	= $this->input->post("from_mail");
				$to_name 		= $this->input->post("to_name");
				$to_mail 		= $this->input->post("to_mail");
				$to_cc 			= $this->input->post("to_cc");
				$subject 		= $this->input->post("subject");
				$message 		= $this->input->post("message");

				$quotation_id 		= $this->input->post("quotation_id");

		
			
			
				$email_data     = array(
												"from_name" 	=> $from_name,
												"from_mail" 	=> $from_mail,
												"to_name" 		=> $to_name,
												"to_mail" 		=> $to_mail,
												"to_cc" 	 		=> $to_cc,	
												"subject" 	 	=> $subject,
												"message" 	 	=> $message	
												
											);


					// echo '<pre>';
					// print_r($data);
					// exit;

				// being transaction
				$this->db->trans_begin();
				
				if($id = $this->email_model->add_email_sent_record($email_data))
				{

				
					$quotation									= $this->quotation_model->get_quotation_single_record($quotation_id);

				
					$quotation_items 						= $this->quotation_model->get_quotation_item_records($quotation->id);
					$customer_detail						= $this->customer_model->get_single_record($quotation->customer_id);
					$company_setting						= $this->company_settings_model->get_company_records();	
	
					$data['customers'] 					= $this->customer_model->get_records();
					$data['customer_detail']		= $customer_detail;
					$data['quotation']					= $quotation;
					$data['quotation_items'] 		= $quotation_items;
					$data['company_setting']		= $company_setting;
					$data['discounts']					= $this->discount_model->get_records();
					$data['message'] 						= $email_data['message'];

				
					$to_cc = ($email_data['to_cc'] != '') ? explode(',',  $email_data['to_cc']) : array();

					$to_cc[] = $from_mail;

					$mail_data 											= array();
					
					$mail_data['From'] 							= FROM_EMAIL;
					$mail_data['FromName'] 					= $company_setting->company_name;
					$mail_data['AddReplyTo'] 				= $from_mail;
					$mail_data['ConfirmReadingTo']	= $from_mail;
					$mail_data['To']								= $to_mail;
					$mail_data['Cc']								= $to_cc;
					$mail_data['Subject'] 					= $subject;
					$mail_data['Body'] 							= $this->load->view('email_view' , $data, true);

					// echo '<pre>';
					// print_r($mail_data);
					// exit;
					
					$html = $this->load->view('quotation/pdf', $data, true);

					// echo $html;
					// exit;
					$pdf_filename = "Quotation-".$data['quotation']->reference_no.".pdf"; // Set your desired filename

          $cid = $company_setting->cid;
          $pdf_path = FCPATH . 'assets/documents/' . $cid . '/quotation/' . $pdf_filename;

          // Create the target folder if it doesn't exist
          $targetDir = FCPATH . 'assets/documents/' . $cid . '/quotation/';
          if (!file_exists($targetDir)) {
              mkdir($targetDir, 0777, true); // The third parameter creates nested directories if needed
          }


					// Save the PDF file directly to the given path
					//$pdf_path = FCPATH . 'assets/documents/' . $pdf_filename;
	
					
					$this->pdf->loadHtml($html);
					$this->pdf->set_paper('A4', $orientation ?? 'portrait'); 
					$this->pdf->render();

					// Save the rendered PDF content to the given path
					file_put_contents($pdf_path, $this->pdf->output());

					$response = array();

					if($to_mail != '')
					{
						if($this->email_model->send_mail($pdf_path,$mail_data))
						{
							$response['code'] 			= 1;
							$response['message'] 		= 'Email Sent successfully';
							echo json_encode($response);
						}
						else
						{
							$response['code'] 			= 0;
							$response['message'] 		= 'Failed to send email';
							echo json_encode($response);
						}	
					}
					else
					{
						$response['code'] 				= 0;
						$response['message'] 			= 'Failed to send email';
						echo json_encode($response);
					}


						// $mail_data['Body'] 							= $this->load->view('sale/pdf',$data,true);
					// commit transaction
					$this->db->trans_commit();

					// if($this->input->is_ajax_request())
					// {	
					// 	$response 											= array();
					// 	$response['code'] 							= 1;
					// 	$response['id'] 								= $id;
					// 	$response['message']						= 'Email Sent successfully.';
					// 	echo json_encode($response);
					// }
					// else
					// {
					// 	$this->session->set_flashdata('success', 'Email Sent successfully.');
					// 	redirect('quotation','refresh');
					// }
				}
				else
				{
					// rollback transaction
					$this->db->trans_rollback();

					if($this->input->is_ajax_request())
					{	
						$response 											= array();
						$response['code'] 							= 0;
						$response['message']						= 'Failed to send email';
						
						echo json_encode($response);
					}
					else
					{
						$this->session->set_flashdata('failure', 'Failed to send email');
						redirect('quotation','refresh');
					}
					
				}
			}

		}
		else
		{
		
			$data['quotation'] 				= $this->quotation_model->get_quotation_single_record($quotation_id);
			$data['customer']					= $this->customer_model->get_single_record($data['quotation']->customer_id);
			$data['company_setting']	= $this->company_settings_model->get_company_records();	
			$data['email_template'] 	= $this->email_template_model->get_record_by_module(EMAIL_TEMPLATE_MODULE_QUOTATION);

		

			$response 												= array();
	  	$response['code']  								= 1;

			   
			$cc_emails = ''; 

			
			if (strpos($data['customer']->email, ',') !== false) 
			{
				$emails = explode(',', $data['customer']->email);
				$data['customer']->email = trim($emails[0]);

				$cc_emails = implode(',', array_slice($emails, 1));
			}

			$data['cc_emails'] = $cc_emails;
			
			$response['email_modal_body'] 	= $this->load->view('quotation/ajax/email_modal_body',$data,TRUE);

			
	  	echo json_encode($response);
		}
	}

	/************************************** Start Dynamic Datatable function *************************************/

	public function ajax_list()
  {
    $list 		= $this->quotation_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

    		/* Begin Action column buttons*/
    		$table_header = '<div class="btn-group">';
	      $table_footer = '</div>';
        $table_body   = '';

				if($this->permission_model->has_permission('email_quotation'))
				{	

					$table_body .= '<a href="#"  data-tt="tooltip" title="'.$this->lang->line('send_email').'" class="btn btn-success btn-sm text-white email-modal" data-quotation_id="'.$item->id.'">
													<i class="fas fa-at"></i>
												</a>';
				}

        //PDF Button
        if($this->permission_model->has_permission('pdf_quotation'))
				{	
					$table_body .= '
                            <a href="'.base_url('quotation/pdf/'.base64_encode($item->id)).'" class="btn bg-orange btn-xs" data-tt="tooltip" title="'.$this->lang->line('quotation_pdf').'">
                              <i class="far fa-file-pdf"></i>
                            </a>
                          ';
				}

        //view Button
        if($this->permission_model->has_permission('view_quotation'))
				{	
					$table_body .= '
                            <a href="'.base_url('quotation/view/'.base64_encode($item->id)).'" class="btn btn-default btn-xs" data-tt="tooltip" title="'.$this->lang->line('quotation_view').'">
                              <i class="fas fa-eye"></i>
                            </a>
                          ';
				}

				// Edit Button        
        if($this->permission_model->has_permission('edit_quotation'))
				{	
					$table_body .= '
                            <a href="'.base_url('quotation/edit/'.base64_encode($item->id)).'" class="btn btn-info btn-xs" data-tt="tooltip" title="'.$this->lang->line('quotation_edit').'">
                              <i class="fas fa-edit"></i>
                            </a>  
                          ';

          $table_body .= '
                            <a href="'.base_url('quotation/copy/'.base64_encode($item->id)).'" class="btn bg-purple btn-xs" data-tt="tooltip" title="Click here to Create Duplicate Quotation">
                              <i class="far fa-copy"></i>
                            </a>
                          ';
				}

				// Delete Button
				if($this->permission_model->has_permission('delete_quotation'))
				{	
					$table_body .= '
		                        <a href="#"  data-toggle="modal" data-target="#delete_quotation" data-tt="tooltip" title="'.$this->lang->line('quotation_delete').'" class="btn btn-danger btn-xs delete_quotation" data-quotation_id="'.$item->id.'">
		                          <i class="fas fa-trash"></i>
		                        </a>
													';
				}

				 //Reference No
        $reference_no_html = '<a href="'.base_url('quotation/view/'.base64_encode($item->id)).'" data-tt="tooltip" title="'.$this->lang->line('quotation_view').'">'.$item->reference_no.'</a>';

         //Customer Name
        // $customer_name_html = '<a href="'.base_url('customer/view/'.base64_encode($item->customer_id)).'" data-tt="tooltip" title="'.$this->lang->line('sale_view_customer_detail').'">'.$item->customer_name.'</a>';
        
        // $customer_display_name = $item->customer_name;

        // if (!empty($item->customer_company_name)) {
        //     $customer_display_name .= " (" . $item->customer_company_name . ")";
        // }
        
        // $customer_name_html = '
        //     <a href="'.base_url('customer/view/'.base64_encode($item->customer_id)).'" 
        //       data-tt="tooltip" 
        //       title="'.$this->lang->line('sale_view_customer_detail').'">
        //       '.$customer_display_name.'
        //     </a>';

           // Company Name Link - NEW
        $company_name_html = '
            <a href="'.base_url('customer/view/'.base64_encode($item->customer_id)).'" 
               data-tt="tooltip" 
               title="'.$this->lang->line('sale_view_customer_detail').'">
               '.($item->customer_company_name ? $item->customer_company_name : 'N/A').'
            </a>';

        // Customer Name Link - NEW  
        $customer_name_html = '
            <a href="'.base_url('customer/view/'.base64_encode($item->customer_id)).'" 
               data-tt="tooltip" 
               title="'.$this->lang->line('sale_view_customer_detail').'">
               '.($item->customer_name ? $item->customer_name : 'N/A').'
            </a>';


        //Quotation Status
        if($item->status == QUOTATION_STATUS_PENDING)
        {
        	$status_html = '<span class="badge badge-warning cursor-pointer" data-toggle="modal" data-target="#change_status_quotation" data-tt="tooltip" title="Click here to Change Quotation Status" data-quotation_id="'.$item->id.'">'.$item->status.'</span>';	
        }
        else if($item->status == QUOTATION_STATUS_APPROVED)
        {
        	$status_html = '<span class="badge badge-success cursor-pointer" data-toggle="modal" data-target="#change_status_quotation" data-tt="tooltip" title="Click here to Change Quotation Status" data-quotation_id="'.$item->id.'">'.$item->status.'</span>';
        }
        else
        {
        	$status_html = '<span class="badge badge-danger cursor-pointer" data-toggle="modal" data-target="#change_status_quotation" data-tt="tooltip" title="Click here to Change Quotation Status"  data-quotation_id="'.$item->id.'">'.$item->status.'</span>';
        }
        		/* End Action column buttons*/

        $row = array();
       
        $row[] = $reference_no_html;
        $row[] = date('d-m-Y', strtotime($item->quotation_date));
         $row[] = $company_name_html;                     // Column 2: Company Name - NEW
        $row[] = $customer_name_html;                    // Column 3: Customer Name - NEW
        
        $row[] = number_format_i($item->total_taxable_value);
        // $row[] = number_format_i($item->total_discount);
        $row[] = number_format_i($item->total_tax);
        $row[] = number_format_i($item->total);
        $row[] = $status_html;

        $row[] = $table_header.$table_body.$table_footer;    

        $data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->quotation_model->count_all(),
                    "recordsFiltered" => $this->quotation_model->count_filtered(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

	/************************************** End Dynamic Datatable function ***************************************/

	public function quotation_delete_confirmation()
  {
  	$quotation_id 				= $this->input->post('quotation_id');
  	$data['quotation'] 		= $this->quotation_model->get_quotation_single_record($quotation_id);

  	$response 					= array();
  	$response['quotation_delete_modal_body'] 	= $this->load->view('quotation/ajax/quotation_delete_modal_view',$data,TRUE);

  	echo json_encode($response);
	}

	public function quotation_change_status_confirmation()
  {
  	$quotation_id 				= $this->input->post('quotation_id');
  	$data['quotation'] 		= $this->quotation_model->get_quotation_single_record($quotation_id);

  	$response 					= array();
  	$response['quotation_change_status_modal_body'] 	= $this->load->view('quotation/ajax/quotation_change_status_modal_view',$data,TRUE);

  	echo json_encode($response);
	}
	
	    public function get_latest_purchase_cost()
    {
        $product_id = $this->input->post('product_id');
        $supplier_id = $this->input->post('supplier_id');
        
        $this->db->select('pdi.cost');
        $this->db->from('purchase_delivery_items pdi');
        $this->db->join('purchase_delivery pd', 'pd.id = pdi.purchase_delivery_id');
        $this->db->join('purchase p', 'p.id = pd.purchase_id');
        $this->db->where('pdi.product_id', $product_id);
        $this->db->where('p.supplier_id', $supplier_id);
        $this->db->order_by('p.purchase_date', 'DESC');
        $this->db->order_by('p.created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            $row = $query->row();
            echo json_encode(['success' => true, 'cost' => $row->cost]);
        } else {
            echo json_encode(['success' => false, 'cost' => '0.00']);
        }
    }

}
