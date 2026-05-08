<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Upload_model extends CI_Model
{
    function __construct() {
        parent::__construct();
        
    }


    
    public function do_multiple_upload($files,$field_name)
    { 
        $filesCount     = count($_FILES[$field_name]['name']); 
        $fileArray      = array();

        // File upload configuration 
        $uploadPath = './assets/documents/'; 
        $config['upload_path'] = $uploadPath; 
        $config['allowed_types'] = 'jpeg|jpg|png|gif|doc|docx|ppt|pptx|xls|xlsx|zip|rar|csv|pdf'; 
        $config['encrypt_name'] = FALSE; 

        $this->load->library('upload'); 

        for($i = 0; $i < $filesCount; $i++)
        { 
            // Create new file array
            $_FILES['file']['name']         = $_FILES[$field_name]['name'][$i]; 
            $_FILES['file']['type']         = $_FILES[$field_name]['type'][$i]; 
            $_FILES['file']['tmp_name']     = $_FILES[$field_name]['tmp_name'][$i]; 
            $_FILES['file']['error']        = $_FILES[$field_name]['error'][$i]; 
            $_FILES['file']['size']         = $_FILES[$field_name]['size'][$i]; 
              
            $file_name_without_ext          = rand().time();

              // We need to do this to handle fils with multiple "." in file name
            $temp_file_array                = explode(".", $_FILES['file']['name']);

            $file_name_ext                  = $temp_file_array[sizeof($temp_file_array)-1]; 
            $new_name                       = $file_name_without_ext.'.'.$file_name_ext;

            $config['file_name']            = $new_name;

              
            $this->upload->initialize($config); 
               
            // Upload file to server 
            if($this->upload->do_upload('file'))
            {
              $temp = array();
              $temp['original_name']    = $_FILES['file']['name'];
              $temp['encrypted_name']   = $new_name;
              $temp['file_extension']   = $file_name_ext;
              $temp['field_name']       = $field_name;

              $fileArray[]  = $temp;

              // Uploaded file data 
              $fileData       = $this->upload->data(); 
            }
        } 

        if(sizeof($fileArray) == 0)
        {
            return null;
        }
        else 
        {
            return json_encode($fileArray);
        }
        
    }

    public function do_single_upload($file)
    {
        // manage main image file upload.
        $config['upload_path']      = './assets/import'; 
        $config['encrypt_name']     = TRUE;
        $config['allowed_types']    = 'jpeg|jpg|png|gif|doc|docx|ppt|pptx|xls|xlsx|zip|rar|csv|pdf'; 
        $config['max_size']      = 10240; 
        // $config['max_width']     = 1024; 
        // $config['max_height']    = 768;  

        $this->load->library('upload');
        $this->upload->initialize($config);

        $uploaded_file = '';

        if($this->upload->do_upload('csvfile'))
        {   
            $uploaded_file = $this->upload->data()['file_name'];
            // print_r($this->upload->data());
        }

        return $uploaded_file;
    }
}
?>