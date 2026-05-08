<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scrap_product extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}

	public function search()
	{
		$search_term 	= $this->input->post('term');
		$module 			= $this->input->post('module');
		$warehouse_id = 1;

		if($module == SCRAP_ISSUE_MODULE)
		{
			$warehouse_id = $this->input->post('warehouse_id');
		}
		
		$data = $this->scrap_products_model->search_record($search_term,$module,$warehouse_id);

    print_r(json_encode($data,true));
	}

  public function warehouse_product_search()
	{
		$search_term 	= $this->input->post('term');
		$module 			= $this->input->post('module');
		$warehouse_id = 1;

		if($module == SCRAP_RECEIVE_MODULE)
		{
			$warehouse_id = $this->input->post('warehouse_id');
		}
		
		$data = $this->scrap_products_model->search_warehouse_product_record($search_term,$module,$warehouse_id);

    print_r(json_encode($data,true));
	}


	public function get_record_detail()
	{
		$module 		= $this->input->post('module');
		$id = $this->input->post('id');
		//$warehouse_product = $this->warehouse_products_model->get_single_record($warehouse_product_id);
    
		if($module == SCRAP_ISSUE_MODULE)
		{
      $data['discount']= $this->discount_model->get_valid_records();	
			$data['product'] 	= $this->scrap_products_model->get_single_record($id);
			
		}
		
    print_r(json_encode($data,true));
	}

  public function get_warehouse_product_record_detail()
	{
		$module 		= $this->input->post('module');
		$warehouse_product_id = $this->input->post('warehouse_product_id');
		//$warehouse_product = $this->warehouse_products_model->get_single_record($warehouse_product_id);
    
		if($module == SCRAP_RECEIVE_MODULE)
		{
      $data['discount']= $this->discount_model->get_valid_records();	
			$data['product'] 	= $this->warehouse_products_model->get_single_warehouse_product_record($warehouse_product_id);
			
		}
		
    print_r(json_encode($data,true));
	}

	public function index()
	{
		if(!$this->permission_model->has_permission('list_scrap_product'))
		{
			$this->load->view('errors/html/error_restricted'); 
		}
		else
		{
			$data['scrap_products'] = $this->scrap_products_model->get_records();
			$data['warehouses'] = $this->warehouse_model->get_records();
			$this->load->view('scrap_product/list',$data);	
		}
	}


	// public function is_product_name_unique()
	// {
	// 	$name = $this->input->post('name');
	// 	$id 	= $this->input->post('id');

	// 	$product 			= $this->utility_model->get_records_by_field('product','name',$name,TRUE);
		
 	// 	if($product == null || ($product->id == $id))
 	// 	{
 	// 		return true;
 	// 	}
 	// 	else 
	// 	{
	// 		$this->form_validation->set_message('is_product_name_unique','Product name must be unique.');
	// 		return false;
	// 	}
	// }
	


  public function export()
  {
    
    if($_GET['data'] != '')
    { 
    	$id    = chop($_GET['data'],',');

   
      $quantity = $_GET['quantity'] ?? '';

      $whereConditions = '';

      if ($id) {
          $id = implode(',', explode(',', $id)); // Ensure the IDs are comma-separated
          $whereConditions .= " WHERE p.warehouse_product_id IN ($id)";
      }

      


      // Add a condition to filter by quantity
      if ($whereConditions != '') 
      {
        if($quantity !== '')
        {
          if($quantity == QUANTITY_GREATER_THEN_ZERO)
          {
            $whereConditions .= " AND (
               quantity
            ) > 0";
          }

          else if($quantity == QUANTITY_ZERO)
          {
            $whereConditions .= " AND (
               quantity
            ) = 0";
          }
        }
      }
      else {
        if($quantity == QUANTITY_GREATER_THEN_ZERO)
        {
          $whereConditions .= " WHERE (
              quantity
          ) > 0";
        }
        else if($quantity == QUANTITY_ZERO)
        {
          $whereConditions .= " WHERE (
              quantity
          ) = 0";
        }
      }

      // Add a condition to filter by records with non-null batch_no

      if ($whereConditions != '') 
      {
        $whereConditions .= " AND p.batch_no IS NOT NULL AND p.batch_no != ''";
      }
      else
      {
        
        $whereConditions .= " WHERE p.batch_no IS NOT NULL AND p.batch_no != ''";
      }

       
      

      $query = $this->db->query('SELECT 
                                    p.name as "Name",
                                   
                                    p.batch_no as "Batch No",
                                    p.quantity as "Quantity",
                                    p.cost as "Cost",
                                    p.price as "Price",
                                    p.selling_price as "Selling Price",
                                    p.warehouse_name as "Warehouse Name"
                                    
                                      FROM 
                                    scrap_product_view p 
                                    ' . $whereConditions . '
                                    ORDER BY p.name ASC');
                                  //    WHERE 
                                  //   p.warehouse_product_delete_status = 0 AND 
                                  //    p.warehouse_product_id IN ('.$id.')
                                  //   - ORDER BY p.name ASC
                                  // ');

    
    	$this->load->dbutil();
      
      $data = $this->dbutil->csv_from_result($query);
      
      $this->load->helper('download');
      force_download("SCRAP_PRODUCT_EXPORTED.CSV", $data);
    }
    else
    {
      
     
      $whereConditions = '';
      $quantity = $_GET['quantity'] ?? '';

     
      // Add a condition to filter by quantity
      if ($whereConditions != '') 
      {
        if($quantity !== '')
        {
          echo 'in condition';
          if($quantity == QUANTITY_GREATER_THEN_ZERO)
          {
             $whereConditions .= " AND (
                quantity
              
            ) > 0";
          }

          else if($quantity == QUANTITY_ZERO)
          {
            $whereConditions .= " AND (
               quantity
               
            ) = 0";
          }
        }
      }
      else {
        if($quantity == QUANTITY_GREATER_THEN_ZERO)
        {
          $whereConditions .= " WHERE (
              quantity
             
          ) > 0";
        }
        else if($quantity == QUANTITY_ZERO)
        {
          $whereConditions .= " WHERE (
              quantity
             
          ) = 0";
        }
      }

       // Add a condition to filter by records with non-null batch_no
      if ($whereConditions != '') 
      {
        $whereConditions .= " AND p.batch_no IS NOT NULL AND p.batch_no != ''";
      }
      else
      {
        
        $whereConditions .= " WHERE p.batch_no IS NOT NULL AND p.batch_no != ''";
      }
       
    	$query = $this->db->query('SELECT 
                                   p.name as "Name",
                                   
                                    p.batch_no as "Batch No",
                                    p.quantity as "Quantity",
                                    p.cost as "Cost",
                                    p.price as "Price",
                                    p.selling_price as "Selling Price",
                                    p.warehouse_name as "Warehouse Name"
                                    
                                      FROM 
                                    scrap_product_view p 
                                    ' . $whereConditions . '
                                    ORDER BY p.name ASC');
                                  //    WHERE 
                                  //   p.warehouse_product_delete_status = 0 AND 
                                  //    p.warehouse_product_id IN ('.$id.')
                                  //   - ORDER BY p.name ASC
                                  // ');

    	$this->load->dbutil();
      
      $data = $this->dbutil->csv_from_result($query);
    
      $this->load->helper('download');
      force_download("SCRAP_PRODUCT_EXPORTED.CSV", $data);
    } 
  }


	public function ajax_list()
  {
    $list 		= $this->scrap_products_model->get_datatables();
    $data 		= array();
    $no 			= $_POST['start'];

    foreach ($list as $item){  

  		/* Begin Action column buttons*/
  		// $table_header = '<div class="btn-group">';
      // $table_footer = '</div>';
      // $table_body   = '';

      // $table_body .= '<a href="#" data-tt="tooltip" title="Variant Pricing" class="btn btn-warning btn-xs variant_pricing_modal" data-warehouse_product_id="'.$item->warehouse_product_id.'" data-product_id="'.$item->id.'">
      //                     <i class="fas fa-hand-holding-usd"></i> Pricing
      //                   </a>';

      // if($this->permission_model->has_permission('add_stock'))
      // {
      // 	$table_body .= '<a href="#"  data-toggle="modal" data-target="#add_stock_modal" data-tt="tooltip" title="Stock In" data-entry_type="in" class="btn btn-primary btn-xs add_stock_modal" data-warehouse_product_id="'.$item->warehouse_product_id.'">
      //                     <i class="far fa-plus-square"></i> StockIn
      //                   </a>';

      //   $table_body .= '<a href="#"  data-toggle="modal" data-target="#add_stock_modal" data-tt="tooltip" title="Stock Out" data-entry_type="out" class="btn btn-success btn-xs add_stock_modal" data-warehouse_product_id="'.$item->warehouse_product_id.'">
      //                     <i class="far fa-minus-square"></i> StockOut
      //                   </a>';

      // }
               
     // Edit Button        
    /*  if($this->permission_model->has_permission('edit_product'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#edit_product_modal" data-tt="tooltip" title="'.$this->lang->line('product_edit').'" class="btn btn-info btn-xs edit_product_modal" data-product_id="'.base64_encode($item->id).'" data-warehouse_product_id="'.$item->warehouse_product_id.'">
	                          <i class="fas fa-edit"></i> Edit
	                        </a>';
			}*/

			// Delete Button
			/*if($this->permission_model->has_permission('delete_product'))
			{	
				$table_body .= '<a href="#"  data-toggle="modal" data-target="#delete_product_modal" data-tt="tooltip" title="'.$this->lang->line('product_delete').'" class="btn btn-danger btn-xs delete_product_modal" data-product_id="'.$item->id.'" data-warehouse_product_id="'.$item->warehouse_product_id.'">
	                          <i class="fas fa-trash"></i> Delete
	                        </a>';
			}*/

		

			/* End Action column buttons*/
			// Product Quantity
				// $products 				= $this->warehouse_products_model->get_records_by_product_id($item->id);
				// $product_quantity = 0;
				// if(sizeof($products) > 0)
        // {
        //   foreach ($products as $product) {
        //     $product_quantity += $product->quantity; 
        //   } 
        // }

        $select_product_html = '<input type="checkbox" class="single_scrap_product" data-warehouse_product_id="'.$item->warehouse_product_id.'" data-scrap_product_id="'.$item->id.'"><input type="hidden" name="scrap_product_id" id="scrap_product_id" data-warehouse_product_id="'.$item->warehouse_product_id.'" value="'.$item->id.'">';

       

        $row = array();
       
        $row[] = $select_product_html;
        $row[] = $item->name;
        $row[] = $item->batch_no;
        $row[] = $item->quantity;
        $row[] = $item->cost;
        $row[] = $item->price;
        $row[] = $item->selling_price;
        $row[] = $item->warehouse_name;
        
        
      //   $row[] = $product_status;
     	
     	// $row[] = $table_header.$table_body.$table_footer;    
			$data[] = $row;
    }

    $output = array(
                    "draw" 						=> $_POST['draw'],
                    "recordsTotal" 		=> $this->scrap_products_model->count_all(),
                    "recordsFiltered" => $this->scrap_products_model->count_filtered(),
                    // "query" 					=> $this->db->last_query(),
                    "data" 						=> $data,
            			);
   
    echo json_encode($output);
  }	

	
}
