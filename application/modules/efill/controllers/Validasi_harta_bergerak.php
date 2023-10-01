<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_harta_bergerak extends Validasi_Excel_Controller {

    protected $is_harta = TRUE;
    protected $posted_fields = array(
        "id_imp_xl_lhkpn_harta_bergerak",
        "KODE_JENIS",
        "MEREK",
        "MODEL",
        "TAHUN_PEMBUATAN",
        "NOPOL_REGISTRASI",
        "JENIS_BUKTI",
        "NOMOR_BUKTI",
        "ATAS_NAMA",
        "ASAL_USUL",
        "PEMANFAATAN",
        "NILAI_PEROLEHAN",
        "NILAI_PELAPORAN",
        "ATAS_NAMA_LAINNYA"
    );

    public function __construct() {
        parent::__construct();
    }
    
    private function __set_asal_usul_dan_pemanfaatan($posted_data){
        $posted_data["ASAL_USUL"] = !empty($posted_data["ASAL_USUL"]) ? implode(",", $posted_data["ASAL_USUL"]) : "";
        $posted_data["PEMANFAATAN"] = !empty($posted_data["PEMANFAATAN"]) ? implode(",", $posted_data["PEMANFAATAN"]) : "";
        return $posted_data;
    }
    
    protected function before_tambah_data($posted_data) {
        return $this->__set_asal_usul_dan_pemanfaatan($posted_data);
    }

    protected function before_update_perubahan($posted_data, $detected_primary_key_value) {
        return $this->__set_asal_usul_dan_pemanfaatan($posted_data);
    }

}
