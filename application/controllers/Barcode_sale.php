<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barcode_sale extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('sale_model');
    }

    public function view_box($box_identifier) {
        // Extract sale ID and box number from the identifier
        $parts = explode('-', $box_identifier);
        if (count($parts) !== 3 || $parts[0] !== 'BOX') {
            show_404();
        }
        
        $sale_id = $parts[1];
        $box_number = $parts[2];
        
        $sale = $this->sale_model->get_sale_single_record($sale_id);
        
        if($sale) {
            $box_data = json_decode($sale->box_qr_data, true);
            $box_info = null;
            
            foreach($box_data as $box) {
                if($box['box_number'] == $box_number) {
                    $box_info = $box;
                    break;
                }
            }
            
            if($box_info) {
                $data['sale'] = $sale;
                $data['box_info'] = $box_info;
                $this->load->view('sale/box_barcode_view', $data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }
}