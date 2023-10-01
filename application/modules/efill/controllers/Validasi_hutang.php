<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_hutang extends Validasi_Excel_Controller {
    
    protected $is_harta = TRUE;
    protected $posted_fields = array(
        "id_imp_xl_lhkpn_hutang",
        "ATAS_NAMA",
        "KODE_JENIS",
        "NAMA_KREDITUR",
        "AGUNAN",
        "AWAL_HUTANG",
        "SALDO_HUTANG",
        "ATAS_NAMA_LAINNYA",
    );

    public function __construct() {
        parent::__construct();
    }
    
    
    protected function edit_before_load_view($data) {
        $this->load->view('lhkpn/v_include_js');
        return $data;
    }

}
