<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_penerimaan extends Validasi_Excel_Controller {
    
    protected $posted_fields = array(
        "ID_IMP_XL_LHKPN",
        "TANGGAL_PELAPORAN",
        "TANGGAL_PENERIMAAN",
    );

    public function __construct() {
        parent::__construct();
    }
    
    protected function get_table_name() {
        return "t_lhkpnoffline_penerimaan";
    }

    protected function get_pk_without_table_name() {
        return "ID_IMP_XL_LHKPN";
    }
    
    protected function after_update_perubahan($posted_data, $detected_primary_key_value) {
        
        $update_data = array(
            "tgl_lapor" => $posted_data["TANGGAL_PELAPORAN"],
            "tgl_kirim" => $posted_data["TANGGAL_PENERIMAAN"]
        );
        
        $result = $this->mglobal->secure_update_data("t_imp_xl_lhkpn", $update_data, $this->get_pk_without_table_name(), $detected_primary_key_value);
        
        return $result;
    }
    
    protected function before_update_perubahan($posted_data, $detected_primary_key_value) {
        $posted_data["TANGGAL_PELAPORAN"] = to_mysql_date($posted_data["TANGGAL_PELAPORAN"]);
        $posted_data["TANGGAL_PENERIMAAN"] = to_mysql_date($posted_data["TANGGAL_PENERIMAAN"]);
        return $posted_data;
    }

}
