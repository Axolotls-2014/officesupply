<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require 'vendor/autoload.php'; // Include PhpSpreadsheet library
use PhpOffice\PhpSpreadsheet\IOFactory;

if ( ! function_exists('number_format_i'))
{
    function number_format_i($amount)
    {
        $amount = round($amount,2);

        $amountArray =  explode('.', $amount);
        if(count($amountArray)==1)
        {
            $int = $amountArray[0];
            $des=00;
        }
        else {
            $int = $amountArray[0];
            $des=$amountArray[1];
        }
        if(strlen($des)==1)
        {
            $des=$des."0";
        }
        if($int>=0)
        {
            $int = numFormatIndia( $int );
            $themoney = $int.".".$des;
        }

        else
        {
            $int=abs($int);
            $int = numFormatIndia( $int );
            $themoney= "-".$int.".".$des;
        }   
        return $themoney;
    }

    function numFormatIndia($num)
    {

        $explrestunits = "";
        if(strlen($num)>3)
        {
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if($i==0) {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash; // writes the final format where $currency is the currency symbol.
    }   
}

if ( ! function_exists('clean_e_val'))
{

    function clean_e_val($str)
    {
      return ucwords(str_replace("_", " ", $str));
    }
}

if ( ! function_exists('enum_select'))
{
    function enum_select( $table , $field ){      
      $CI =& get_instance();
      $query = " SHOW COLUMNS FROM `".$table."` LIKE '".$field."' ";
      $row = $CI->db->query($query)->row()->Type;
      $regex = "/'(.*?)'/";
      preg_match_all( $regex , $row, $enum_array );
      $enum_fields = $enum_array[1];
      return $enum_fields;
    }  
}

if (!function_exists('is_csv_valid')) {
  /**
   * Verify if an uploaded CSV file has properly comma-separated values, contains a specific header,
   * and handles new lines correctly.
   *
   * @param string $file_path The path to the uploaded CSV file.
   * @param string $delimiter The delimiter used in the CSV file (usually a comma).
   * @param array $expected_header An array containing the expected header columns.
   * @return string|bool Returns an error message if the CSV is invalid or true if it's valid.
   */
  function is_csv_valid($file_path, $delimiter = ',', $expected_header = []) {
      if (!file_exists($file_path)) {
          return "File doesn't exist.";
      }

      $file = fopen($file_path, 'r');
      if ($file === false) {
          return "Unable to open the file.";
      }

      $header_row = fgetcsv($file, 0, $delimiter);

      if ($header_row === false) {
          fclose($file);
          return "Unable to read the header row.";
      }

      if (!empty($expected_header) && count($header_row) !== count($expected_header)) {
          fclose($file);
          return "Header doesn't match the expected header.";
      }

      for ($i = 0; $i < count($header_row); $i++) {
          if (!empty($expected_header) && $header_row[$i] !== $expected_header[$i]) {
              fclose($file);
              return "Header column doesn't match the expected header.";
          }
      }

      $prev_line = null;

      while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
          // Check if the row has the same number of columns as the header
          if (count($row) !== count($header_row)) {
              fclose($file);
              return "Row doesn't have the same number of columns as the header.";
          }

          // Check if the row is separated by a newline character
          // if ($prev_line !== null && $prev_line !== PHP_EOL) {
          //     fclose($file);
          //     return "Rows are not separated by a newline character.";
          // }

          // You can add additional validation checks for each row here if needed.

          $prev_line = $row[0]; // Store the first column of the current row
      }

      fclose($file);
      return true; // CSV is valid and contains the specified header, and handles new lines correctly
  }
}

if ( ! function_exists('column_unique_value'))
{
    function column_unique_value( $table, $field )
    {
      $CI =& get_instance();
      $query = "SELECT DISTINCT ".$field." FROM ".$table." WHERE ".$field." != null OR ".$field." != ''";
      $result = $CI->db->query($query)->result();

      $values = array();

      if($result != null)
      {
        foreach ($result as $row){
          $values[] = $row->$field;
        }
      }

      return $values;
    }
}

if ( ! function_exists('generate_table'))
{
  function generate_table($data) {
    // Convert input data string to array of objects

    $array = json_decode($data, true);
    // $array = explode('|', $data);
    // $objects = array();
    // foreach($array as $item) {
    //     $objects[] = json_decode($item);
    // }
    
    // Generate HTML table from data
    $table = '<table class="table table-bordered" style="background-color:#FFFDD0">
                    <thead>
                        <tr>
                            <th>Quantity</th>
                            <th>Free quantity</th>
                        </tr>
                    </thead>
                    <tbody>';
    for ($i=0; $i < sizeof($array) ; $i++) 
    { 
        // Use 'd-none' class for all rows except the first row
        // $class = ($index == 0) ? '' : 'd-none';
        $table .= '<tr>
                      <td>' . $array[$i]['quantity'] . '</td>
                      <td>' . $array[$i]['free_quantity'] . '</td>
                    </tr>';

            
    }
    $table .= '</tbody></table>';
    
    // Return generated table HTML
    return $table;
  }
}

if ( ! function_exists('csession'))
{
    function csession( $field )
    {
      $CI =& get_instance();
      return $CI->session->userdata($field);
    }
}

if ( ! function_exists('generateQRCode'))
{
  function generateQRCode($upiAddress, $referenceNo, $amount, $id) {
    if ($upiAddress != '') {
        $qrcode_library_path = APPPATH . 'libraries' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';

        if (file_exists($qrcode_library_path)) {
            include($qrcode_library_path);

            $upi_intent = "upi://pay?pa=" . urlencode($upiAddress) . "&pn={$referenceNo}&am={$amount}&cu=INR";
            $name = "{$id}_{$referenceNo}.jpg";
            $folderPath = FCPATH . '/assets/images/upi/';
            $path = $folderPath . $name;

             // Check if the "upi" folder exists, if not, create it
            if (!is_dir($folderPath)) {
              mkdir($folderPath, 0755, true);
            }
            
            // Check if the file already exists, then delete it
            if (file_exists($path)) {
              unlink($path);
          }

            QRcode::png($upi_intent, $path, QR_ECLEVEL_L, 5); // qrcode in png format
            
            return 'assets/images/upi/' . $name; // Return the generated QR code image path
        }
    }
    
    return ''; // Return an empty string if the QR code is not generated
  }

  

}


if (!function_exists('is_xlsx_valid')) {

  /**
   * Verify if an uploaded XLSX file has the expected header and structure.
   *
   * @param string $file_path The path to the uploaded XLSX file.
   * @param array $expected_header An array containing the expected header columns.
   * @return string|bool Returns an error message if the XLSX is invalid or true if it's valid.
   */
  function is_xlsx_valid($file_path, $expected_header = []) {
      if (!file_exists($file_path)) {
          return "File doesn't exist.";
      }

      // Load the XLSX file
      try {
          $spreadsheet = IOFactory::load($file_path);
      } catch (Exception $e) {
          return "Unable to open the XLSX file.";
      }

      $worksheet = $spreadsheet->getActiveSheet();
      $highestRow = $worksheet->getHighestRow();
      $highestColumn = $worksheet->getHighestColumn();
      $header = [];

      // Read the header row
      for ($col = 'A'; $col <= $highestColumn; $col++) {
          $header[] = $worksheet->getCell($col . '1')->getValue();
      }

      if (!empty($expected_header) && $header !== $expected_header) {
          return "Header doesn't match the expected header.";
      }

      $data = [];

      // Iterate through the rows and check their structure
      for ($row = 2; $row <= $highestRow; $row++) {
        $rowData = [];
        $isEmptyRow = true; // Assume the row is empty initially

        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $cellValue = $worksheet->getCell($col . $row)->getValue();
            $rowData[] = $cellValue;

            // Check if the cell has a non-empty value
            if (!empty($cellValue)) {
                $isEmptyRow = false;
            }
        }

        // If at least one cell in the row is non-empty, consider it as data
        if (!$isEmptyRow) {
            $data[] = $rowData;
        }
      }


      // Check if no records are available
      if (empty($data)) {
          return "No records available in the XLSX file.";
      }

      // You can add additional validation checks for each row here if needed.

      return true; // XLSX is valid and contains the specified header
  }
}

if (!function_exists('process_bank_statement_name')) {
  function process_bank_statement_name($inputString) 
  {
      $separator = "_";
      $array = explode($separator, $inputString);
      
      unset($array[0]);
      unset($array[1]);
      unset($array[2]);
      
      $array = array_values($array);
      
      $resultString = implode($separator, $array);
      
      return $resultString;
  }
}

if (!function_exists('generate_batch_no')) {
	function generate_batch_no($cost, $selling_price, $price, $product_id, $warehouse_id) 
	{
		$strCost = str_replace('.', '', (string)$cost);
		$strSellingPrice = str_replace('.', '', (string)$selling_price);
		$strPrice = str_replace('.', '', (string)$price);

		$concatenatedString = $strCost . $strSellingPrice . $strPrice . $product_id . $warehouse_id;

		return $concatenatedString;
	}
}

if (!function_exists('get_next_sale_reference')) {
    function get_next_sale_reference() {
        $CI =& get_instance();

        $query = $CI->db->query("SELECT id FROM sale_requests ORDER BY id DESC LIMIT 1");
        $last_id = $query->row() ? $query->row()->id : 0;

        $next_ref = 'OSS' . ($last_id + 1);

        return $next_ref;
    }
}


if (!function_exists('get_next_sale_main_reference')) {
    function get_next_sale_main_reference() {
        $CI =& get_instance();

        $query = $CI->db->query("SELECT id FROM sale ORDER BY id DESC LIMIT 1");
        $last_id = $query->row() ? $query->row()->id : 0;

        $next_ref = 'OSS' . ($last_id + 1);

        return $next_ref;
    }
}




