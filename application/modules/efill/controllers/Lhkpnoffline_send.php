<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lhkpnoffline_send extends MY_Controller {

    public $limit = 10;
    public $iscetak = false;
    public $list_bukti = [];
    public $list_jenis_harta = [];

    public function __construct() {
        parent::__construct();
        $this->load->model(array('mglobal', 'Mimplhkpntolhkpn'));
//        $this->config->load('harta');
        call_user_func('ng::islogin');
//        $this->uri_segment = 5;
//        $this->offset = $this->uri->segment($this->uri_segment);
//
//        // prepare search
//        foreach ((array) @$this->input->post('CARI') as $k => $v) {
//            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];
//        }
    }

    public function send($id_imp_xl_lhkpn = FALSE, $send_mail = 1) {
        $array_response = array(
            "success" => 0,
            "msg" => "data tidak ditemukan"
        );
        if ($id_imp_xl_lhkpn) {

            $datapn = $this->get_data_pn_by_id_lhkpn($id_imp_xl_lhkpn, TRUE, TRUE);
            if (!valid_email($datapn->EMAIL)) {
                $array_response = array(
                    "success" => 0,
                    "msg" => "Gagal melakukan pengiriman data LHKPN. Email tidak valid."
                );
            } else {

                $this->Mimplhkpntolhkpn->DIR_TEMP_UPLOAD = self::DIR_TEMP_UPLOAD;
                $this->Mimplhkpntolhkpn->DIR_TEMP_SKM_UPLOAD = self::DIR_TEMP_SKM_UPLOAD;
                $this->Mimplhkpntolhkpn->DIR_TEMP_SKUASA_UPLOAD = self::DIR_TEMP_SKUASA_UPLOAD;
                $this->Mimplhkpntolhkpn->DIR_TEMP_IKHTISAR_UPLOAD = self::DIR_TEMP_IKHTISAR_UPLOAD;
//    
                $this->Mimplhkpntolhkpn->DIR_IMP_UPLOAD = self::DIR_IMP_UPLOAD;
                $this->Mimplhkpntolhkpn->DIR_SKM_UPLOAD = self::DIR_SKM_UPLOAD;
                $this->Mimplhkpntolhkpn->DIR_SKUASA_UPLOAD = self::DIR_SKUASA_UPLOAD;
                $this->Mimplhkpntolhkpn->DIR_IKHTISAR_UPLOAD = self::DIR_IKHTISAR_UPLOAD;

                // $response = ID_LHKPN
                $response = $this->Mimplhkpntolhkpn->copy_to_lhkpn($id_imp_xl_lhkpn);

                $array_response = array(
                    "success" => 0,
                    "msg" => "Gagal melakukan pengiriman data LHKPN."
                );
                if ($response) {

                    //PARAMETER KETIGA DARI $this->kirim_lhkpn di bawah ini 
                    //       WAJIB bernilai TRUE, jika FALSE maka hanya preview, 
                    //       tidak akan melakukan kirim email
                    
                    $this->is_lhkpnoffline = TRUE;
                    //$this->kirim_lhkpn($response, FALSE, TRUE, $id_imp_xl_lhkpn);

                    $array_response = array(
                        "success" => 1,
                        "msg" => "Berhasil melakukan pengiriman data LHKPN untuk diverifikasi."
                    );
                }
            }
        }
        $this->to_json($array_response);
    }

    /*
    Download Lembar Penyerahan 29-12-2017 by wawi
    *
    */

    function resend_lp($id_imp_xl_lhkpn)
    {
        $datapn = $this->get_data_pn_by_id_lhkpn($id_imp_xl_lhkpn, FALSE, TRUE);
        $penerima = $datapn->EMAIL;
        $pesan_valid = $this->preview_lp($id_imp_xl_lhkpn, $datapn);
        ng::mail_send($penerima, 'Lembar Penyerahan E-Filling LHKPN', $pesan_valid);
        $array_response = array(
                        "success" => 1,
                        "msg" => "Lembar Penyerahan berhasil di kirim ke ".$penerima
                    );
        $this->to_json($array_response);
    }

    public function hehehe($id_imp_xl_lhkpn)
    {
        $this->Mimplhkpntolhkpn->copy_to_lhkpn($id_imp_xl_lhkpn);
    }

}
