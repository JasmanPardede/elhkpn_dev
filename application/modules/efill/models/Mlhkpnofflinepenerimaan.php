<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mlhkpnofflinepenerimaan extends CI_Model {

    public $table = 'T_LHKPNOFFLINE_PENERIMAAN';

    public function __construct() {
        parent::__construct();
    }

    public function add_new($data) {
        return $this->db->insert($this->table, $data);
    }

    public function find_record($data) {
        $this->db->where($data, NULL, FALSE);

        $this->db->select(' T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN as i, T_LHKPNOFFLINE_PENERIMAAN.*,
					T_PN.NIK,
					T_PN.NAMA, IS_DITERIMA_KOORD_CS,
					(SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
					(SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
					(SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
				');

        $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
        $this->db->from($this->table);

        $query = $this->db->get('');

        if ($query) {
            return $query->row();
        }
        return FALSE;
    }
    
    public function replace_old_record($condition){
        $this->db->where($condition, NULL, FALSE);
        return $this->db->update($this->table, array('IS_REPLACED'=>'1'));
    }
    
    public function get_detail($i) {
        $this->db->where(" T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = '".$i."'");

        $this->db->select(' T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN as i, T_LHKPNOFFLINE_PENERIMAAN.*,
					T_PN.NIK,
					T_PN.NAMA, IS_DITERIMA_KOORD_CS,
					(SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
					(SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
					(SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
				');

        $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
        $this->db->from($this->table);

        $query = $this->db->get('');

        if ($query) {
            return $query->row();
        }
        return FALSE;
    }

}