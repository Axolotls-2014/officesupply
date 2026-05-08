<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ax_data extends CI_Controller {

public function warehousewise_data()
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data[$this->security->get_csrf_token_name()]) || 
        $data[$this->security->get_csrf_token_name()] !== $this->security->get_csrf_hash()) {
        show_error('Invalid CSRF token', 403);
    }

    $warehouseId = $data['warehouse_id'];
    echo json_encode(['status' => 'success', 'id' => $warehouseId]);
}


}
