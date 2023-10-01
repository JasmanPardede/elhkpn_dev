<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cqrcode extends CI_Model {

    protected $qrcode = 'qrcode';
    
    public $prefix_nomor = "SKM-ELHKPN-";

    function insert_cqrcode($data) {
        $qrcode = $this->load->database('qrcode', TRUE);
        
        $qrcode->where('no_qrcode', $data["no_qrcode"]);
        $check = $qrcode->get('qrcode')->row();

        if ($check != NULL){
            $insert = $qrcode->where('no_qrcode', $data["no_qrcode"]);
            $insert .= $qrcode->update($this->qrcode, $data);
        }else{
            $insert = $qrcode->insert($this->qrcode, $data);
        }

        if ($insert):
            return true;
        else:
            return false;
        endif;
    }
    
    /**
     * 
     * @param string $data json
     * @param string $filename
     * @return mixed
     */
    function insert_cqrcode_with_filename($data, $filename){
        
        
        $data_obj = json_decode($data);
        if(!$data_obj){
            /**
             * $data not json
             */
            return FALSE;
        }
        
        if(!property_exists($data_obj, "no_qrcode")){
            /**
             * no_qr_code not found
             */
            return FALSE;
        }
        
        if(!property_exists($data_obj, "data")){
            /**
             * data not found
             */
            return FALSE;
        }
        
        $data_insert["filename"] = $filename;
        $data_insert["jenis_surat"] = $data_obj->judul;
        $data_insert["no_surat"] = $this->prefix_nomor.$data_obj->id_lhkpn;
        $data_insert["tgl_surat"] = $data_obj->tgl_surat;
        $data_insert["isi_surat"] = json_encode((object)["data" => $data_obj->data]);
        $data_insert["no_qrcode"] = $data_obj->no_qrcode;
        
//        gwe_dump(array("data"=>$data_insert), TRUE);
        
        return $this->insert_cqrcode($data_insert);
    }

}

?>