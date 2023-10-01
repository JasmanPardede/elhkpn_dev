<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_pengeluaran_kas extends Validasi_Excel_Controller {

    protected $is_harta = TRUE;
    protected $posted_fields = array(
        "id_imp_xl_lhkpn_pengeluaran_kas",
    );
    private $unset_after = array();

    public function __construct() {
        parent::__construct();
        $this->__set_posted_fields();
    }
    
    private function __set_posted_fields(){
        $jenis_pengeluaran_kas_pn = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $label = array('A', 'B', 'C');
        for ($i = 0; $i < count($jenis_pengeluaran_kas_pn); $i++) {
            for ($j = 0; $j < count($jenis_pengeluaran_kas_pn[$i]); $j++) {
                $code = $label[$i] . $j;
                
                $this->posted_fields[] = $code;
                $this->unset_after[] = $code;
            }
        }
        unset($jenis_pengeluaran_kas_pn, $label);
    }

    protected function edit_before_load_view($data_detail = FALSE) {
        $data_detail["jenis_pengeluaran_kas_pn"] = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $data_detail["golongan_pengeluaran_kas_pn"] = $this->config->item('golongan_pengeluaran_kas_pn', 'harta');
        return $data_detail;
    }
    
    protected function before_update_perubahan($posted_data, $detected_primary_key_value) {
        
        $posted_data["NILAI_PENGELUARAN_KAS"] = (object)array();
        
        $jenis_penerimaan_kas_pn = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $label = array('A', 'B', 'C');
        
        
        for ($i = 0; $i < count($jenis_penerimaan_kas_pn); $i++) {
            
            $posted_data["NILAI_PENGELUARAN_KAS"]->{$label[$i]} = array();
            
            
            for ($j = 0; $j < count($jenis_penerimaan_kas_pn[$i]); $j++) {
                $code = $label[$i] . $j;
                
                $posted_data["NILAI_PENGELUARAN_KAS"]->{$label[$i]}[] = (object)array($code => $posted_data[$code]);
                unset($posted_data[$code]);
            }
        }
        $posted_data["NILAI_PENGELUARAN_KAS"] = json_encode($posted_data["NILAI_PENGELUARAN_KAS"]);
        
        return $posted_data;
    }

    protected function after_get_detail($result, $id) {
        
        $pn = json_decode(!is_null($result->NILAI_PENGELUARAN_KAS) ? $result->NILAI_PENGELUARAN_KAS : "{}");

        $jenis_pengeluaran_kas_pn = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $label = array('A', 'B', 'C');

        for ($i = 0; $i < count($jenis_pengeluaran_kas_pn); $i++) {
            for ($j = 0; $j < count($jenis_pengeluaran_kas_pn[$i]); $j++) {
                $code = $label[$i] . $j;

                $result->{$code} = '0';

                if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
                    $result->{$code} = $pn->{$label[$i]}[$j]->$code;
                }
            }
        }
        unset($pn, $pa, $jenis_pengeluaran_kas_pn);
        return $result;
    }

}
