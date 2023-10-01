<?php

class Lexcel {

    private $excel;
    private $objReader;

    public function __construct() {
        // initialise the reference to the codeigniter instance
        require_once APPPATH . "/third_party/PHPExcel.php";
        require_once APPPATH . "/libraries/XML2003Parser.php";
        $this->excel = new PHPExcel();
    }

    public function load($path, $auto_detect = FALSE, $except_reader_type = FALSE) {
        $reader_type = 'Excel5';
        if ($auto_detect) {
            $reader_type = PHPExcel_IOFactory::identify($path);
        }
        
        if ($except_reader_type) {
            if (is_array($except_reader_type) && in_array($reader_type, $except_reader_type)) {
                return FALSE;
            }
            if (is_string($except_reader_type) && strtolower($reader_type) == strtolower(trim($except_reader_type))) {
                return FALSE;
            }
        }

        $this->objReader = PHPExcel_IOFactory::createReader($reader_type);
        $this->excel = $this->objReader->load($path);
        return TRUE;
    }

    public function load_using_excel5($path) {
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->excel = $objReader->load($path);
    }

    public function load_using_2007($path) {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->excel = $objReader->load($path);
    }

    public function load_using_2003XML($path) {
        $objReader = PHPExcel_IOFactory::createReader('Excel2003XML');
        $this->excel = $objReader->load($path);
    }
    
    public function load_using_XML2003($path, $worksheet_index = 0, $read_minimum = FALSE){
        $this->excel = new XML2003Parser($path, TRUE, $worksheet_index, $read_minimum);
    }
    
    public function XML2003_destroy(){
        $this->excel = NULL;
        $this->objReader = NULL;
    }
    
    public function XML2003_get_total_row_num(){
        return $this->excel->getTotalRowNum();
    }
    
    public function XML2003_get_table_data(){
        return $this->excel->getTableData();
    }
    
    public function XML2003_get_column_data($idx){
        return $this->excel->getColumnData($idx);
    }
    
    public function XML2003_get_row_data($idx){
        return $this->excel->getRowData($idx);
    }
    
    public function XML2003_get_cell_data($idx_x, $idx_y){
        return $this->excel->getCellData($idx_x, $idx_y);
    }

    public function list_worksheet_names($filename) {
        return $this->excel->listWorksheetNames($filename);
    }

    public function get_properties() {
        return $this->excel->getProperties();
    }

    public function get_active_sheet($to_array = FALSE) {
        if ($to_array) {
            return $this->excel->getActiveSheet()->toArray(null, true, true, true);
        }
        return $this->excel->getActiveSheet();
    }

    public function setActiveSheetIndexByName($sheet_name = '') {
        try {
            return $this->excel->setActiveSheetIndexByName($sheet_name);
        } catch (PHPExcel_Exception $excel_exception) {
            /**
             * Sheet Not Found
             */
            return FALSE;
        }
        return FALSE;
    }

    public function save($path) {
        // Write out as the new file
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save($path);
    }

    public function stream($filename) {
        header('Content-type: application/ms-excel');
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        header("Cache-control: private");
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function __call($name, $arguments) {
        // make sure our child object has this method  
        if (method_exists($this->excel, $name)) {
            // forward the call to our child object  
            return call_user_func_array(array($this->excel, $name), $arguments);
        }
        return null;
    }

}

?>