<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}
	}

	public function get_attachment_detail_ajax($attachment_id)
	{
		$data['attachment'] = $this->expense_model->getAttachmentById($attachment_id);
		echo json_encode($data);
	}

	public function file_upload()
    { 
    if(!empty($_FILES['file']['name']))
    {   
      $data['company_setting'] 	= $this->company_settings_model->get_company_records();

	  $cid = $data['company_setting']->cid; 
      // Set preference
      $config['upload_path']      = 'assets/documents/'.$cid.'/expense/';
      $config['allowed_types']    = 'jpeg|jpg|png|gif|doc|docx|ppt|pptx|xls|xlsx|txt|html|zip|rar|csv|pdf';
      $config['max_size']         = '51200'; // max_size in kb

      // Create the directory if it doesn't exist
      $targetDir = $config['upload_path'];
      if (!file_exists($targetDir)) {
          mkdir($targetDir, 0777, true);
      }

      $file_name_without_ext      = uniqid();
      $file_name_ext              = explode(".", $_FILES['file']['name'])[1]; 

      $config['file_name']        = $file_name_without_ext.'.'.$file_name_ext;
              
      //Load upload library
      $this->load->library('upload');         
      $this->upload->initialize($config);
          
      // File upload
      if($this->upload->do_upload('file'))
      {
        // Get data about the file
        $uploadData = $this->upload->data();

        if($this->session->userdata('attachment_array'))
        {
          $attachment_array = explode(',',$this->session->userdata('attachment_array'));
          $attachment_array[] = $config['file_name'];
          $this->session->set_userdata('attachment_array',implode(',',$attachment_array));
        }
        else
        {
          $attachment_array[] = $config['file_name'];
          $this->session->set_userdata('attachment_array',implode(",", $attachment_array)); 
        }
      }
    }   
  }

	public function get_attachment_from_session()
	{
    $attachment_array = $this->session->userdata('attachment_array');

    if($attachment_array != '' || $attachment_array != null)
    {
   		$data['data'] = $attachment_array;
			echo json_encode($data);	
   	}
	}

	function remove_attachment_by_session($id)
	{
		// $attachment_array 		  = $this->session->userdata('attachment_array');
		// $deleted_session_data[]   = $id;

		// if($deleted_session_data)
		// {
		//     foreach($attachment_array as $k => $session)
		//     {
		//          if(isset($session[$deleted_session_data]))
		//          {
		//             unset($attachment_array[$k][$deleted_session_data]);
		//          }
		//     }

		//     $attachment_array = array_values($attachment_array);
		//     $this->session->set_userdata('attachment_array', $attachment_array);
		// }

		$this->session->unset_userdata('attachment_array');
	}

	public function download_attachment($file_name)
  {
  	$path 			= 	base_url()."assets/documents/".$file_name;

  	$file_name_without_ext 	= explode(".", $file_name)[0];
  	$file_ext 				= explode(".", $file_name)[1];

  	$actual_file_name 		= substr($file_name_without_ext, 0, -14).'.'.$file_ext;

		$this->load->helper('download');
		$data = file_get_contents($path); // Read the file's contents
		force_download($actual_file_name, $data); 
    exit;
  }

  public function download($file_name)
  {

    $data['company_setting'] 	= $this->company_settings_model->get_company_records();

    $cid = $data['company_setting']->cid; 


  	$file = getcwd()."/assets/documents/$cid/expense/".$file_name;
  	if (is_file($file))
    {
      $targetDir = dirname($file);
      if (!file_exists($targetDir)) {
          mkdir($targetDir, 0777, true);
      }
      
      $the_content_type = mime_content_type($file);
      $this->sendHeaders($file, $the_content_type, $file_name);
      $chunkSize = 1024 * 1024;
      $handle = fopen($file, 'rb');
      while (!feof($handle))
      {
        $buffer = fread($handle, $chunkSize);
        echo $buffer;
        ob_flush();
        flush();
      }
      fclose($handle);
      exit;
    }
  }

  public function sendHeaders($file, $type, $name=NULL)
  {
    if (empty($name))
    {
      $name = basename($file);
    }
    
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Transfer-Encoding: binary');
    header('Content-Disposition: attachment; filename="'.$name.'";');
    header('Content-Type: ' . $type);
    header('Content-Length: ' . filesize($file));
  }
}
