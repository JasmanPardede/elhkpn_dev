<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Apicontrol extends MY_Controller{
    
    protected $need_uk_id = FALSE;
    
    public function __construct(){
        parent::__construct();
    }
    
    private function __get_provinsi_model($key){
        $this->db->limit(10);
        $this->db->like('NAME', $key);
//        $this->db->order_by('NAME', 'ASC');
        $this->db->where('IS_ACTIVE', '1');
        return $this->db->get('m_area_prov')->result();
    }
    
    public function GetProvinsi() {
        $key = $this->input->post_get('q');
        $result = $this->__get_provinsi_model($key);
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->ID_PROV,
                'text' => $row->NAME
            );
        }

        $this->to_json($array);
    }
    
    private function __get_kota_model($ID_PROPINSI, $key){
        
        $this->db->where('ID_PROV', $ID_PROPINSI);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->limit(10);
        $this->db->like('NAME_KAB', $key);
        $this->db->order_by('NAME_KAB', 'ASC');
        return $this->db->get('m_area_kab')->result();
    }
    
    function GetKota($ID_PROPINSI) {
        $key = $this->input->post_get('q');
        $result = $this->__get_kota_model($ID_PROPINSI, $key);
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->ID_KAB,
                'text' => $row->NAME_KAB
            );
        }
        $this->to_json($array);
    }
    
    private function __get_negara_model($key){
        $this->db->limit(10);
        $this->db->where('ID !=', '2');
        $this->db->like('NAMA_NEGARA', $key);
        $this->db->order_by('NAMA_NEGARA', 'ASC');
        return $this->db->get('m_negara')->result();
    }
    
    function GetNegara() {
        $key = $this->input->post_get('q');
        $result = $this->__get_negara_model($key);
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->KODE_ISO3,
                'text' => $row->NAMA_NEGARA
            );
        }
        $this->to_json($array);
    }
}