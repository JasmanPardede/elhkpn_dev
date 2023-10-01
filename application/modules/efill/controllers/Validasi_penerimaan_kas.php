<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_penerimaan_kas extends Validasi_Excel_Controller {

    protected $is_harta = TRUE;
    protected $posted_fields = array(
        "id_imp_xl_lhkpn_penerimaan_kas",
    );
    
    private $unset_after = array();

    public function __construct() {
        parent::__construct();
        $this->__set_posted_fields();
    }
    
    private function __set_posted_fields(){
        $jenis_penerimaan_kas_pn = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $label = array('A', 'B', 'C');
        for ($i = 0; $i < count($jenis_penerimaan_kas_pn); $i++) {
            for ($j = 0; $j < count($jenis_penerimaan_kas_pn[$i]); $j++) {
                $code = $label[$i] . $j;
                
                $this->posted_fields[] = $code;
                $this->unset_after[] = $code;
                if ($i == 0) {
                    $PA_val = 'PA' . $j;
                    $this->posted_fields[] = $PA_val;
                    $this->unset_after[] = $PA_val;
                }
            }
        }
        unset($jenis_penerimaan_kas_pn, $label);
    }

    protected function edit_before_load_view($data_detail = FALSE) {
        $data_detail["jenis_penerimaan_kas_pn"] = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $data_detail["golongan_penerimaan_kas_pn"] = $this->config->item('golongan_penerimaan_kas_pn', 'harta');
        return $data_detail;
    }
    
    protected function before_update_perubahan($posted_data, $detected_primary_key_value) {
        
        $posted_data["NILAI_PENERIMAAN_KAS_PN"] = (object)array();
        $posted_data["NILAI_PENERIMAAN_KAS_PASANGAN"] = array();
        
        $jenis_penerimaan_kas_pn = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $label = array('A', 'B', 'C');
        
        
        for ($i = 0; $i < count($jenis_penerimaan_kas_pn); $i++) {
            
            $posted_data["NILAI_PENERIMAAN_KAS_PN"]->{$label[$i]} = array();
            
            
            for ($j = 0; $j < count($jenis_penerimaan_kas_pn[$i]); $j++) {
                $code = $label[$i] . $j;
                
                $posted_data["NILAI_PENERIMAAN_KAS_PN"]->{$label[$i]}[] = (object)array($code => $posted_data[$code]);
                unset($posted_data[$code]);
                if ($i == 0) {
                    $PA_val = 'PA' . $j;
                    
                    $posted_data["NILAI_PENERIMAAN_KAS_PASANGAN"][] = (object)array($PA_val=>$posted_data[$PA_val]);
                    unset($posted_data[$PA_val]);
                }
            }
        }
        $posted_data["NILAI_PENERIMAAN_KAS_PN"] = json_encode($posted_data["NILAI_PENERIMAAN_KAS_PN"]);
        $posted_data["NILAI_PENERIMAAN_KAS_PASANGAN"] = json_encode($posted_data["NILAI_PENERIMAAN_KAS_PASANGAN"]);
        
        return $posted_data;
    }

    protected function after_get_detail($result, $id) {
        $pn = json_decode(!is_null($result->NILAI_PENERIMAAN_KAS_PN) ? $result->NILAI_PENERIMAAN_KAS_PN : "{}");
        $pa = json_decode(!is_null($result->NILAI_PENERIMAAN_KAS_PASANGAN) ? $result->NILAI_PENERIMAAN_KAS_PASANGAN : "{}");

        $jenis_penerimaan_kas_pn = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
//        $golongan_penerimaan_kas_pn = $this->config->item('golongan_penerimaan_kas_pn', 'harta');
        $label = array('A', 'B', 'C');

        for ($i = 0; $i < count($jenis_penerimaan_kas_pn); $i++) {
            for ($j = 0; $j < count($jenis_penerimaan_kas_pn[$i]); $j++) {
                $PA_val = 'PA' . $j;
                $code = $label[$i] . $j;

                $result->{$code} = '0';

                if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
                    $result->{$code} = $pn->{$label[$i]}[$j]->$code;
                }

                if ($i == 0) {
                    $result->{$PA_val} = '0';
                    if (is_array($pa) && !empty($pa) && property_exists($pa[$j], $PA_val)) {
                        $result->{$PA_val} = $pa[$j]->{$PA_val};
                    }
                }
            }
        }
        unset($pn, $pa, $jenis_penerimaan_kas_pn);
//        property_exists($class, $property)

        return $result;
    }

}
