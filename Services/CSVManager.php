<?php

namespace Tom32i\CSVBundle\Services;

class CSVManager
{ 
    private $fp; 
    private $parse_header; 
    private $header; 
    private $delimiter; 
    private $length; 

    public function __construct() 
    { 

    } 

    public function __destruct() 
    { 
        if ($this->fp) 
        { 
            fclose($this->fp); 
        } 
    } 

    public function load($file_name, $parse_header = true, $delimiter = ";", $length = 0) 
    { 
        $this->fp = fopen($file_name, "r"); 
        $this->parse_header = $parse_header; 
        $this->delimiter = $delimiter; 
        $this->length = $length; 

        if ($this->parse_header) 
        { 
           $this->header = fgetcsv($this->fp, $this->length, $this->delimiter); 
        } 

    } 

    public function getHeader()
    {
        return $this->header;
    }

    public function get($max_lines = 0) 
    { 
        //if $max_lines is set to 0, then get all the data 

        $data = array(); 

        $line_count = $max_lines > 0 ? 0 : -1; // so loop limit is ignored 

        while ($line_count < $max_lines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE) 
        { 
            if ($this->parse_header) 
            { 
                foreach ($this->header as $i => $heading_i) 
                { 
                    $row_new[$heading_i] = $row[$i]; 
                } 

                $data[] = $row_new; 
            } 
            else 
            { 
                $data[] = $row; 
            } 

            if ($max_lines > 0) 
            {
                $line_count++; 
            }
        } 
        return $data; 
    } 
} 
?>