<?php

defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * Save data hasil upload Excel Pegawai Negeri Wajib Lapor.
 * 
 * Version: 1.1.10
 * @author Lahir Wisada Santoso <lahirwisada@gmail.com>, 2017.
 *
 * This library is intended to be compatible with CI 2.x.
 */
require_once APPPATH . "/libraries/Lexcel.php";

define('EXCEL_UPLOAD_PATH', BASEPATH . '../uploads/data_pn_xls/');

class Save_pn_wl_excel {

    const JML_ROW_LEGEND = 16;

    private $excellib;
    private $enable_debugging = TRUE;
    public $excel_filename = FALSE;
    private $catch_error = FALSE;
    private $old_error_message = "";

    private function __set_debugging() {
        if ($this->enable_debugging) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }
    }

    public function get_filename() {
        return $this->excel_filename;
    }

    public function get_full_path() {
        return EXCEL_UPLOAD_PATH . $this->excel_filename;
    }

    private function __set_filename($filename = FALSE) {
        if ($filename) {
            $this->excel_filename = $filename;
        }
    }

    private function __configure_library($config) {
        if (is_array($config) && !empty($config)) {
            if (in_array("filename", array_keys($config))) {
                $this->__set_filename($config["filename"]);
            }
        }
    }

    public function __construct($config = array()) {
        $this->__set_debugging();

        $this->__configure_library($config);

        $this->excellib = new Lexcel();

        $this->catch_error = FALSE;
    }

    function myErrorHandler($errno, $errstr, $errfile, $errline) {
        $this->catch_error = TRUE;

        $this->old_error_message = $errstr;

        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }

        /* Don't execute PHP internal error handler */
        return TRUE;
    }

    public function read_excel() {
        $load_excel_data_success = FALSE;
        try {
            $response = $this->excellib->load($this->get_full_path(), TRUE, array('HTML', 'CSV', 'Excel2003XML'));

            $load_excel_data_success = TRUE;
        } catch (Exception $ex) {
            $load_excel_data_success = FALSE;
        }

        $arr_test = NULL;
        $array_response = array(
            "total_row_num" => 0,
            "records" => array(),
            "jml_row_legend" => self::JML_ROW_LEGEND,
            "load_data_success" => FALSE,
            "old_error_message" => "",
        );

        if (!$response && $load_excel_data_success) {

            $old_error_handler = set_error_handler(array($this, "myErrorHandler"));
            $this->excellib->load_using_XML2003($this->get_full_path(), 0, TRUE);

            if (!$this->catch_error) {
//                $arr_test = $this->excellib->XML2003_get_table_data();
//                var_dump($arr_test);exit;
                $array_response = array(
                    "total_row_num" => $this->excellib->XML2003_get_total_row_num(),
                    "records" => $this->excellib->XML2003_get_table_data(),
                    "jml_row_legend" => self::JML_ROW_LEGEND,
                    "load_data_success" => TRUE,
                    "old_error_message" => "",
                );
            } elseif (strpos($this->old_error_message, 'simplexml_load_file') !== false) {
                $array_response = array(
                    "total_row_num" => 0,
                    "records" => array(),
                    "jml_row_legend" => self::JML_ROW_LEGEND,
                    "load_data_success" => FALSE,
                    "old_error_message" => "Sistem kesulitan membaca file yang anda upload. coba untuk membuka file tersebut dan kemudian simpan ulang dengan format XML Spreadsheet 2003 dengan ekstensi .xml . ",
                );
            }
        }

        $this->excellib->XML2003_destroy();
        $this->excellib = NULL;

        return (object) $array_response;
    }

}
