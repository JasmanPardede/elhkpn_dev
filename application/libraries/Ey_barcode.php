<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Load the files we need:

require_once 'Picqer/BarcodeGeneratorJPG.php';

class Ey_barcode {
    
    public $temp_dir;
    
    public $resImgWidth;
    public $resImgHeight;
    
    public function __construct($_arr_parameters = FALSE){
        if (!$_arr_parameters) {
            $this->temp_dir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'barcode' . DIRECTORY_SEPARATOR;
        } else {
            if (is_array($_arr_parameters)) {
                extract($_arr_parameters);
            }

            if (isset($temp_dir)) {
                $this->__set_temp_dir($temp_dir);
            }
        }
    }
    
    private function __set_temp_dir($_temp_dir = FALSE) {
        if (!$_temp_dir) {
            $this->temp_dir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'barcode' . DIRECTORY_SEPARATOR;
        } else {
            $this->temp_dir = $_temp_dir;
        }
    }
    
    public function generate($content, $filename = "", $type=FALSE, $jpeg_quality = 95, $return_full_path = TRUE){
        
        $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
        if(!$type){
            $type = $generator::TYPE_CODE_93;
        }
        
//        echo $this->temp_dir.$filename;exit;
        $bc = $generator->getBarcode($content, $type, 2, 30, array(0, 0, 0), $this->temp_dir.$filename);
        

        $response = $filename;

        if ($return_full_path) {
            $response = $this->temp_dir . $filename;
        }

        return $response;
        
        
    }
}