<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_harta_surat_berharga extends Validasi_Excel_Controller {

    protected $is_harta = TRUE;
    protected $upload_harta_location = "data_suratberharga";
    protected $posted_fields = array(
        "id_imp_xl_lhkpn_harta_surat_berharga",
        "KODE_JENIS",
        "NAMA_PENERBIT",
        "CUSTODIAN",
        "NOMOR_REKENING",
        "ATAS_NAMA",
        "ATAS_NAMA_LAINNYA",
        "ASAL_USUL",
        "FILE_BUKTI",
        "NILAI_PEROLEHAN",
        "NILAI_PELAPORAN",
    );

    public function __construct() {
        parent::__construct();
    }
    
    private function __set_asal_usul($posted_data){
        $posted_data["ASAL_USUL"] = !empty($posted_data["ASAL_USUL"]) ? implode(",", $posted_data["ASAL_USUL"]) : "";
        return $posted_data;
    }
    
    protected function before_tambah_data($posted_data) {
        return $this->__set_asal_usul($posted_data);
    }

    protected function before_update_perubahan($posted_data, $detected_primary_key_value) {
        return $this->__set_asal_usul($posted_data);
    }
    
    private function __before_load_view($data_detail = FALSE){
        return $this->set_file_list($data_detail);
    }
    
    protected function before_add_load_view() {
        return $this->__before_load_view();
    }
    
    protected function edit_before_load_view($data_detail = FALSE) {
        return $this->__before_load_view($data_detail);
    }

    protected function after_tambah_data($posted_data, $insert_id, $status) {
        if ($status) {
            $this->save_to_folder_nik($posted_data, $insert_id);
        }
        return;
    }

    protected function after_update_perubahan($posted_data, $detected_primary_key_value) {
        $this->save_to_folder_nik($posted_data, $detected_primary_key_value);
        return;
    }

}
