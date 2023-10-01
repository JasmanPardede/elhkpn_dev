<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_data_tidak_bergerak
 *
 * @author nurfadillah
 */
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_harta_tidak_bergerak extends Validasi_Excel_Controller {

    protected $is_harta = TRUE;
    protected $posted_fields = array(
        "id_imp_xl_lhkpn_harta_tidak_bergerak",
        "NEGARA",
        "ID_NEGARA",
        "JALAN",
        "KEL",
        "KEC",
        "KAB_KOT",
        "PROV",
        "LUAS_TANAH",
        "LUAS_BANGUNAN",
        "JENIS_BUKTI",
        "NOMOR_BUKTI",
        "ATAS_NAMA",
        "ATAS_NAMA_LAINNYA",
        "ASAL_USUL",
        "PEMANFAATAN",
        "NILAI_PEROLEHAN",
        "NILAI_PELAPORAN",
        "ID_PROV",
    );

    public function __construct() {
        parent::__construct();
    }

    protected function before_get_detail($id) {
//        $this->db->select('m_area_prov.id_prov');
        $this->db->join('m_area_prov', 't_imp_xl_lhkpn_harta_tidak_bergerak.PROV = LOWER(m_area_prov.NAME) AND m_area_prov.IS_ACTIVE = 1', 'left');
    }

    protected function edit_before_load_view($data) {
        $this->load->view('lhkpn/v_include_js');
        return $data;
    }

    private function __set_asal_usul_dan_pemanfaatan($posted_data) {
        $posted_data["ASAL_USUL"] = !empty($posted_data["ASAL_USUL"]) ? implode(",", $posted_data["ASAL_USUL"]) : "";
        $posted_data["PEMANFAATAN"] = !empty($posted_data["PEMANFAATAN"]) ? implode(",", $posted_data["PEMANFAATAN"]) : "";
        return $posted_data;
    }

    protected function before_update_perubahan($posted_data, $detected_primary_key_value) {
        return $this->__set_asal_usul_dan_pemanfaatan($posted_data);
    }

    protected function before_tambah_data($posted_data) {
        return $this->__set_asal_usul_dan_pemanfaatan($posted_data);
    }

    private function __update_provinsi($posted_data, $detected_primary_key_value, $secure = TRUE) {
        $detail = $this->mglobal->get_by_id('m_area_prov', 'ID_PROV', $posted_data["ID_PROV"]);

        $pk_without_table_name = $this->get_pk_without_table_name();

        if ($secure) {
            $this->mglobal->secure_update_data($this->get_table_name(), array(
                "PROV" => $detail->NAME,
                "ID_PROV" => $detail->ID_PROV
                    ), $pk_without_table_name, $detected_primary_key_value);
        } else {
            $this->mglobal->update($this->get_table_name(), array(
                "PROV" => $detail->NAME,
                "ID_PROV" => $detail->ID_PROV
                    ), $this->get_primary_key() . " = '" . $detected_primary_key_value . "'");
        }

        return;
    }

    protected function after_update_perubahan($posted_data, $detected_primary_key_value) {
        $this->__update_provinsi($posted_data, $detected_primary_key_value);
        return;
    }

    protected function after_tambah_data($posted_data, $insert_id, $status) {
        if ($status) {
            $this->__update_provinsi($posted_data, $insert_id, FALSE);
        }
        return;
    }

}
