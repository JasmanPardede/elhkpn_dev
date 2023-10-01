<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_fasilitas extends Validasi_Excel_Controller {

    protected $posted_fields = array(
        "id_imp_xl_lhkpn_fasilitas",
        "JENIS_FASILITAS",
        "PEMBERI_FASILITAS",
        "KETERANGAN",
        "KETERANGAN_LAIN",
    );
    
    public function __construct() {
        parent::__construct();
    }

}
