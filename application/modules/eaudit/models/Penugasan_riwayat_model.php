<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penugasan_riwayat_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function get_riwayat_penugasan($id_lhkpn)
  {
    $this->db->select('
      tla.id_audit,
      tla.id_lhkpn,
      tla.created_date,
      tla.tgl_mulai_periksa,
      tla.tgl_selesai_periksa,
      tla.status_periksa,
      tla.id_creator,
      tla.created_by,
      tla.nomor_surat_tugas,
      tla.jenis_penugasan,
      tla.jenis_pemeriksaan,
      GROUP_CONCAT(tu.NAMA) AS pemeriksa,
      tp.NAMA AS pic, 
      tc.NAMA AS creator')
      ->from('t_lhkpn_audit tla')
      ->join('t_user tu', 'tla.id_pemeriksa = tu.ID_USER', 'left')
      ->join('t_user tp', 'tla.id_pic = tp.ID_USER', 'left')
      ->join('t_user tc', 'tla.id_creator = tc.ID_USER', 'left')
      ->where('tla.id_lhkpn', $id_lhkpn)
      ->where('tla.is_active', '1')
      ->group_by(array('tla.id_lhkpn', 'tla.nomor_surat_tugas'))
      ->order_by('tla.created_date', 'ASC');

    $q = $this->db->get();

    return $q->result();
  }

  function get_detail_pn_lhkpn($ID_LHKPN, $include_data_pribadi = FALSE, $include_data_pn = FALSE) {
        $this->db->select('*, p.nik as nik_benar');
        $this->db->from('t_user as u');
        $this->__join_get_detail_pn_lhkpn($include_data_pribadi, $include_data_pn);
        $this->db->where('l.ID_LHKPN', $ID_LHKPN);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    private function __join_get_detail_pn_lhkpn($include_data_pribadi = FALSE, $include_data_pn = FALSE) {
        $this->db->join('t_pn as p', 'p.nik = u.username', 'LEFT');
        $this->db->join('t_lhkpn as l', 'l.id_pn = p.id_pn', 'LEFT');
        if ($include_data_pn) {
            $this->db->join('t_lhkpn_jabatan as j', 'j.id_lhkpn = l.id_lhkpn and j.IS_PRIMARY = "1"', 'LEFT');
            $this->db->join('M_JABATAN as mj', 'mj.ID_JABATAN = j.ID_JABATAN', 'LEFT');
            $this->db->join('M_INST_SATKER as mis', 'mis.INST_SATKERKD = mj.INST_SATKERKD', 'LEFT');
            $this->db->join('M_UNIT_KERJA as muk', 'muk.UK_ID = mj.UK_ID', 'LEFT');
            $this->db->join('M_SUB_UNIT_KERJA as suk', 'suk.SUK_ID = mj.SUK_ID', 'LEFT');
            $this->db->join('M_BIDANG as mb', 'mb.BDG_ID = mis.INST_BDG_ID', 'LEFT');
        }
        if ($include_data_pribadi) {
            $this->db->join('t_lhkpn_data_pribadi', 'l.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
            $this->db->join('t_pn_jabatan as pnj', 'pnj.ID_PN = l.ID_PN AND pnj.IS_CURRENT = 1', 'LEFT');
        }
    }

}
