<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//E:\www\KPK\2017\elhkpndev\application\modules\efill\controllers
include_once dirname(__FILE__) . "/../../../core/Validasi_Excel_Controller.php";

class Validasi_data_pribadi extends Validasi_Excel_Controller {

    protected $upload_harta_location = "data_pribadi";
    protected $posted_fields = array(
        "id_imp_xl_lhkpn_data_pribadi",
        "GELAR_DEPAN",
        "NO_KK",
        "NIK",
        "NAMA_LENGKAP",
        "GELAR_BELAKANG",
        "JENIS_KELAMIN",
        "TEMPAT_LAHIR",
        "TANGGAL_LAHIR",
        "ID_AGAMA",
        "FOTO",
        "AGAMA",
        "NPWP",
        "TELPON_RUMAH",
        "EMAIL_PRIBADI",
        "HP",
        "ALAMAT_RUMAH"
    );

    public function __construct() {
        parent::__construct();
    }
    
    protected function before_update_perubahan($posted_data, $detected_primary_key_value) {
        $posted_data["TANGGAL_LAHIR"] = to_mysql_date($posted_data["TANGGAL_LAHIR"]);
        return $posted_data;
    }

    protected function edit_before_load_view($data_detail = FALSE) {

        $data_detail = $this->set_file_list($data_detail);

        $data_detail['agama'] = $this->mglobal->get_data_all('m_agama');

        $data_detail['item_data_jabatan'] = FALSE;
        if ($data_detail['item']) {
            $this->load->model('Mimpxllhkpn');
            $this->Mimpxllhkpn->set_select_join_t_lhkpn_imp_xl_jabatan();
            $data_detail['item_data_jabatan'] = $this->mglobal->secure_get_by_secure_id('t_imp_xl_lhkpn_jabatan', 'id_imp_xl_lhkpn', 'id_imp_xl_lhkpn_jabatan', $data_detail['item']->id_imp_xl_lhkpn);
        }

        $this->load->view('validasi_data_pribadi/js/v_include_js', $data_detail);
        return $data_detail;
    }

    private function __update_perubahan_data_jabatan($posted_jabatan, $id_imp_xl_lhkpn_jabatan) {
        $this->mglobal->secure_update_data("t_imp_xl_lhkpn_jabatan", $posted_jabatan, "id_imp_xl_lhkpn_jabatan", $id_imp_xl_lhkpn_jabatan);
    }

    private function __simpan_data_jabatan($posted_jabatan) {
        $this->mglobal->insert("t_imp_xl_lhkpn_jabatan", $posted_jabatan);
    }

    private function __update_data_jabatan ($posted_data) {
        $posted_jabatan['LEMBAGA'] = $this->input->post('LEMBAGA');
        $posted_jabatan['UNIT_KERJA'] = $this->input->post('UNIT_KERJA');
        $posted_jabatan['SUB_UNIT_KERJA'] = $this->input->post('SUB_UNIT_KERJA');
        $posted_jabatan['ID_JABATAN'] = $this->input->post('ID_JABATAN');
        $posted_jabatan['ALAMAT_KANTOR'] = $this->input->post('ALAMAT_KANTOR');
        $id_imp_xl_lhkpn_jabatan = $this->input->post('id_imp_xl_lhkpn_jabatan');
        $posted_jabatan['UPDATED_BY'] = $this->username;

        foreach ($posted_jabatan as $key_jabatan => $value_jabatan) {
            if ($value_jabatan == '') {
                unset($posted_jabatan[$key_jabatan]);
            }
        }

        if ($id_imp_xl_lhkpn_jabatan) {
            $posted_jabatan['UPDATED_TIME'] = date('d-m-Y H:i:s');
            $this->__update_perubahan_data_jabatan($posted_jabatan, $id_imp_xl_lhkpn_jabatan);
        } else {
            $detail_data_pribadi = $this->get_detail($this->posted_primary_key);
            if ($detail_data_pribadi) {
                $posted_jabatan['CREATED_TIME'] = date('d-m-Y H:i:s');
                $id_imp_xl_lhkpn = $detail_data_pribadi && property_exists($detail_data_pribadi, "id_imp_xl_lhkpn") ? "id_imp_xl_lhkpn" : "ID_imp_xl_LHKPN";
                $posted_jabatan[$id_imp_xl_lhkpn] = $detail_data_pribadi->{$id_imp_xl_lhkpn};
                $this->__simpan_data_jabatan($posted_jabatan);
            }
        }

        unset($detail_data_pribadi, $posted_jabatan);
    }

    protected function after_update_perubahan($posted_data, $detected_primary_key_value) {

        $this->__update_data_jabatan($posted_data);

        $this->save_to_folder_nik($posted_data, $detected_primary_key_value, "FOTO");

        return;
    }

}
