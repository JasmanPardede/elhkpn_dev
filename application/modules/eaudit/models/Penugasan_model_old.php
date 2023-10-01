<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Penugasan_model_old extends CI_Model {

  function __construct()
  {
    parent::__construct();
  }

  public function get_data_lhkpn($posted_data, $offset = 0)
  {
    $status = array('0','2');
    $this->db->select('T_LHKPN.ID_LHKPN,T_LHKPN.tgl_kirim_final, T_LHKPN.JENIS_LAPORAN, T_PN.NIK, T_PN.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA');
    $this->db->from('T_LHKPN');
    $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN AND T_LHKPN_JABATAN.IS_PRIMARY = "1"', 'left');
    $this->db->join('T_LHKPN_DATA_PRIBADI', 'T_LHKPN_DATA_PRIBADI.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
    $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA', 'left');
    $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA', 'left');
    // $this->db->join('M_ESELON', 'M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON', 'left');
    // $this->db->join('T_STATUS_AKHIR_JABAT', 'T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT', 'left');
    $this->db->join('R_BA_PENGUMUMAN', 'R_BA_PENGUMUMAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
    // $this->db->join('T_BA_PENGUMUMAN', 'T_BA_PENGUMUMAN.ID_BAP = R_BA_PENGUMUMAN.ID_BAP', 'left');
    $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
    $this->db->where_not_in('T_LHKPN.STATUS', $status);
    $this->db->where('T_LHKPN.IS_ACTIVE', '1');
    $this->db->where('R_BA_PENGUMUMAN.ID_LHKPN', NULL);
    $this->db->where('T_PN.NIK !=', NULL);
    $this->db->where('T_PN.NAMA !=', '');
    // $this->db->where('T_LHKPN.entry_via <>', '2');
    // $this->db->where('YEAR(T_LHKPN.tgl_kirim_final) >=', '2017');
    //$this->db->where_in('T_LHKPN.ID_LHKPN', $wherein);
    // $where = "T_LHKPN.ID_LHKPN IN ('".$wherein."')";
    // $this->db->where($where);
    $this->db->limit(10, $offset);
    $this->db->order_by('T_PN.NAMA','ASC');
    // $this->db->order_by('T_LHKPN.tgl_kirim_final','ASC');
    $data = $this->db->get();
    // var_dump($this->db->last_query());
    // die();
    $result['0'] = $data->result();
    $result['1'] = 1000;//$data->num_rows();
    return $result;
  }

  public function get_all_pemeriksa()
  {
    $this->db->select('t_user.ID_USER, t_user.NAMA, t_user_role.ROLE');
    $this->db->from('t_user');
    // $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_user.UK_ID', 'left');
    $this->db->join('t_user_role', 't_user_role.ID_ROLE = t_user.ID_ROLE', 'left');
    $this->db->where('
      t_user.ID_ROLE IN (26,27,28)
      AND t_user.IS_BANNED = 0
      AND t_user.IS_ACTIVE = 1
    ');
    $query = $this->db->get();
    return $query->result();
  }
}
