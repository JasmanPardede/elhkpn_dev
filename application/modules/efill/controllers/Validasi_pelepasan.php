<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_pelepasan extends Validasi_Excel_Controller {

    private $pelepasan_function_list = [];
    private $function_name;
    private $jenis_pelepasan;
    protected $is_harta = TRUE;
    protected $posted_fields = array(
        "JENIS_PELEPASAN_HARTA",
        "URAIAN_HARTA",
        "NILAI_PELEPASAN",
        "NAMA",
        "ALAMAT",
    );

    public function __construct() {
        parent::__construct();
        $this->read_function_list();
    }

    private function read_function_list() {
        if (empty($this->pelepasan_function_list)) {
            $this->config->load('harta');
            $this->pelepasan_function_list = $this->config->item('pelepasan_table_postfix', 'harta');
        }
    }

    private function map_function_list($index_function_list = FALSE) {
        $this->function_name = FALSE;
        if ($index_function_list) {
            $this->read_function_list();

            $key_function_name = array_keys($this->pelepasan_function_list);
//            
            if (in_array($index_function_list, $key_function_name)) {
                $this->function_name = "pelepasan_harta_" . $this->pelepasan_function_list[$index_function_list];
            }
            unset($key_function_name);
        }
    }

    protected function detect_postfix() {
        return $this->function_name;
    }

    private function __before_load_view($data = array()) {
        $data["jenis_pelepasan"] = make_secure_text($this->jenis_pelepasan);
        $data["primary_key"] = $this->get_pk_without_table_name();
        return $data;
    }

    protected function before_add_load_view() {
        $data = $this->__before_load_view();
        $data["array_jenis_pelepasan"] = $this->get_array_jenis_pelepasan();
        return $data;
    }

    protected function edit_before_load_view($data) {
        return $this->__before_load_view($data);
    }

    private function get_array_jenis_pelepasan() {
        return [
            make_secure_text("harta_bergerak") => "harta bergerak",
            make_secure_text("harta_bergerak_lainnya") => "harta bergerak lainnya",
            make_secure_text("harta_lainnya") => "harta lainnya",
            make_secure_text("harta_tidak_bergerak") => "harta tidak bergerak",
            make_secure_text("kas_dan_setara_kas") => "kas dan setara kas",
            make_secure_text("surat_berharga") => "surat berharga"
        ];
    }

    private function find_jenis_pelepasan($formget_jenis_pelepasan) {
        $jenis_pelepasan = FALSE;
        switch ($formget_jenis_pelepasan) {
            case make_secure_text("harta_bergerak"):
                $jenis_pelepasan = "harta_bergerak";
                break;
            case make_secure_text("harta_bergerak_lainnya"):
                $jenis_pelepasan = "harta_bergerak_lainnya";
                break;
            case make_secure_text("harta_lainnya"):
                $jenis_pelepasan = "harta_lainnya";
                break;
            case make_secure_text("harta_tidak_bergerak"):
                $jenis_pelepasan = "harta_tidak_bergerak";
                break;
            case make_secure_text("kas_dan_setara_kas"):
                $jenis_pelepasan = "kas_dan_setara_kas";
                break;
            case make_secure_text("surat_berharga"):
                $jenis_pelepasan = "surat_berharga";
                break;
            default:
                $jenis_pelepasan = FALSE;
                break;
        }
        $this->jenis_pelepasan = $jenis_pelepasan;
        return $jenis_pelepasan;
    }

    private function detect_jenis_harta_pelepasan($id, $formget_jenis_pelepasan) {
        $jenis_pelepasan = $this->find_jenis_pelepasan($formget_jenis_pelepasan);

        if (!($id && $jenis_pelepasan)) {
            $id = FALSE;
        } else {
            $this->map_function_list($jenis_pelepasan);
        }

        return $id;
    }

    protected function get_detail($id = FALSE, $select_fields = "*") {
        $formget_jenis_pelepasan = $this->input->get("jpl");
        $id = $this->detect_jenis_harta_pelepasan($id, $formget_jenis_pelepasan);
        return parent::get_detail($id, $select_fields);
    }

    private function __before_check_post() {
        $formget_jenis_pelepasan = $this->input->post("jpl");
        $id = $this->detect_jenis_harta_pelepasan(TRUE, $formget_jenis_pelepasan);
        $this->posted_fields[] = $this->get_pk_without_table_name();
    }

    protected function tambah_data_before_check_post() {
        $this->__before_check_post();
    }

    protected function update_perubahan_before_check_post() {
        $this->__before_check_post();
    }

    protected function get_postfix() {
        return $this->function_name;
    }

    public function hapus($id = FALSE) {
        if ($id) {

            $formget_jenis_pelepasan = $this->input->get("jpl");
            $this->detect_jenis_harta_pelepasan($id, $formget_jenis_pelepasan);
            extract($this->collect_form_properties(TRUE));
            $this->mglobal->secure_delete_data($table_name, $pk_without_table_name, $id);
            echo "1";
            exit;
        }
        echo "0";
        exit;
    }

}
