<?php

class Cuk extends CI_Controller{
    
    function index(){
        echo 'cuk';
    }

    function url(){
      echo base_url();
    }
    
    function token($nik = null) {
        if($nik == null){
            $result = array(
                'error' => 'true',
                'data' => 'NULL'
            );
        }
        else{
            $sql = "SELECT t_lhkpn.ID_LHKPN, NIK,
                    USERNAME_ENTRI AS NAMA,
                    TOKEN_PENGIRIMAN,
                    CASE
                      WHEN `status` = '0'
                      THEN 'Draft'
                      WHEN `status` = '1'
                      THEN 'Proses Verifikasi'
                      WHEN `status` = '2'
                      THEN 'Perlu Perbaikan'
                      WHEN `status` = '3'
                      THEN 'Terverifikasi Lengkap'
                      WHEN `status` = '4'
                      THEN 'Diumumkan Lengkap'
                      WHEN `status` = '5'
                      THEN 'Terverifikasi tidak lengkap'
                      WHEN `status` = '6'
                      THEN 'Diumumkan tidak lengkap'
                      WHEN `status` = '7'
                      THEN 'ditolak'
                    END AS CUK FROM t_lhkpn LEFT JOIN t_lhkpn_data_pribadi ON t_lhkpn_data_pribadi.`ID_LHKPN` = t_lhkpn.`ID_LHKPN` WHERE t_lhkpn.IS_ACTIVE = '1' AND id_pn IN (SELECT ID_PN FROM t_pn WHERE NIK = '".$nik."'  and is_active = '1') ORDER BY t_lhkpn.ID_LHKPN DESC LIMIT 1";
            $data = $this->db->query($sql);
            if($data->num_rows() == 1){
                $ress = $data->row();
                $server_code = base64_encode($ress->TOKEN_PENGIRIMAN);
                $result = array(
                    'error' => 'false',
                    'ID_LHKPN'=> $ress->ID_LHKPN,
                    'NIK'=> $ress->NIK,
                    'NAMA'=> $ress->NAMA,
                    'token'=> $ress->TOKEN_PENGIRIMAN,
                    'server code'=> strtoupper($server_code),
                    'status' => $ress->CUK
                );
            }
            else{
                $result = array(
                    'error' => 'true',
                    'data' => 'NIK Not Found'
                );
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }
}