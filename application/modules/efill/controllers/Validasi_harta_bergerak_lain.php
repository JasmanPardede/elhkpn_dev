<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_harta_bergerak_lain extends Validasi_Excel_Controller {

    protected $is_harta = TRUE;
    
    protected $posted_fields = array(
        "id_imp_xl_lhkpn_harta_bergerak_lain",
        "KODE_JENIS",
        "JUMLAH",
        "KETERANGAN",
        "SATUAN",
        "ASAL_USUL",
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

}
