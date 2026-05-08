<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php'; // Include PhpSpreadsheet library
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * CSV Reader for CodeIgniter 3.x
 *
 * Library to read the CSV file. It helps to import a CSV file
 * and convert CSV data into an associative array.
 *
 * This library treats the first row of a CSV file
 * as a column header row.
 */
class CSVReader {
    
    // Columns names after parsing
    private $fields;
    // Separator used to explode each line (changed to comma for CSV)
    private $separator = ',';
    // Enclosure used to decorate each field
    private $enclosure = '"';
    // Maximum row size to be used for decoding
    private $max_row_size = 0;
    
    /**
     * Parse a CSV file and returns as an array.
     *
     * @access    public
     * @param    filepath    string    Location of the CSV file
     *
     * @return mixed|boolean
     */
    function parse_csv($filepath){
        
        // If file doesn't exist, return false
        if(!file_exists($filepath)){
            return FALSE;            
        }
        
        // Open uploaded CSV file with read-only mode
        $csvFile = fopen($filepath, 'r');
        
        if(!$csvFile){
            return FALSE;
        }
        
        // Get the header row (first row)
        $header = fgetcsv($csvFile, $this->max_row_size, $this->separator, $this->enclosure);
        
        if($header === FALSE || $header === NULL){
            fclose($csvFile);
            return FALSE;
        }
        
        // Clean header values (remove quotes, trim)
        $keys = array();
        foreach($header as $key) {
            $clean_key = trim($key, '"');
            $clean_key = trim($clean_key);
            $keys[] = $clean_key;
        }
        
        // Store CSV data in an array
        $csvData = array();
        $row_count = 0;
        
        while(($row = fgetcsv($csvFile, $this->max_row_size, $this->separator, $this->enclosure)) !== FALSE){
            // Skip empty rows
            if($row === NULL || (count($row) == 1 && empty($row[0]))){
                continue;
            }
            
            // Clean row values
            $clean_row = array();
            foreach($row as $value) {
                $clean_value = trim($value, '"');
                $clean_value = trim($clean_value);
                $clean_row[] = $clean_value;
            }
            
            // Only add if row has data
            if(count($clean_row) > 0 && !empty($clean_row[0])){
                // Combine keys with values
                if(count($keys) == count($clean_row)){
                    $csvData[$row_count] = array_combine($keys, $clean_row);
                } else {
                    // If column count mismatch, still add but with sequential keys
                    $csvData[$row_count] = $clean_row;
                }
                $row_count++;
            }
        }
        
        // Close opened CSV file
        fclose($csvFile);
        
        return !empty($csvData) ? $csvData : FALSE;
    }

    function escape_string($data){
        $result = array();
        foreach($data as $row){
            $result[] = str_replace('"', '', $row);
        }
        return $result;
    }

    /**
     * Parse an Excel file and return as an array.
     */
    function parse_excel($filepath) {
        // Open Excel file with read-only mode
        try {
            $spreadsheet = IOFactory::load($filepath);
        } catch (Exception $e) {
            return FALSE; // Unable to open the Excel file
        }

        $worksheet = $spreadsheet->getActiveSheet();
        $data = array();
        $this->fields = null;
        
        foreach ($worksheet->toArray() as $index => $row) {
            if ($this->fields === null) {
                $this->fields = $row;
            } else {
                if(count($this->fields) == count($row)){
                    $data[] = array_combine($this->fields, $row);
                }
            }
        }

        return !empty($data) ? $data : FALSE;
    } 

    function parse_data($filepath)
    {
        // Check if file exists
        if(!file_exists($filepath)){
            return FALSE;
        }
        
        // Check the file extension to determine the file type
        $fileExtension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

        if ($fileExtension === 'csv') {
            return $this->parse_csv($filepath);
        } elseif ($fileExtension === 'xlsx' || $fileExtension === 'xls') {
            return $this->parse_excel($filepath);
        }

        return FALSE; // Unsupported file type
    }
}