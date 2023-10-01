<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



/**
 * Description of Validasi_data_keluarga
 *
 * @author nurfadillah
 */
//E:\www\KPK\2017\elhkpndev\application\modules\efill\controllers
include_once dirname(__FILE__) . "/../../../core/Verifikasi_Excel_Controller.php";

class Verifikasi_keluarga extends Verifikasi_Excel_Controller {

    protected $posted_fields = array(
        "NIK",
        "NAMA",
        "HUBUNGAN",
        "TEMPAT_LAHIR",
        "TANGGAL_LAHIR",
        "PEKERJAAN",
        "NOMOR_TELPON",
        "ALAMAT_RUMAH",
    );

    public function __construct() {
        parent::__construct();
    }

    private function __set_mysql_format_tanggal_lahir($posted_data){
        $posted_data["TANGGAL_LAHIR"] = to_mysql_date($posted_data["TANGGAL_LAHIR"]);
        return $posted_data;
    }

    protected function before_tambah_data($posted_data) {
        return $this->__set_mysql_format_tanggal_lahir($posted_data);
    }

    protected function before_update_perubahan($posted_data, $detected_primary_key_value) {
        return $this->__set_mysql_format_tanggal_lahir($posted_data);
    }

}
