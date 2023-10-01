<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kkp_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function get_biodata_pn($id_lhkpn, $is_bak=0) { //is_bak = sudah isi BAK (tgl_klarifikasi)
    // echo "string";die();

    $this->db->select('
    t_lhkpn.ID_LHKPN,
    t_lhkpn.JENIS_LAPORAN,
    t_lhkpn.tgl_lapor,
    t_lhkpn.TGL_KLARIFIKASI,
    t_lhkpn.CATATAN_PEMERIKSAAN,
    t_lhkpn_data_pribadi.NAMA_LENGKAP,
    t_lhkpn_data_pribadi.NIK,
    t_pn.NHK,
    t_pn.NIP_NRP,
    t_lhkpn_data_pribadi.NPWP,
    t_lhkpn_data_pribadi.TEMPAT_LAHIR,
    t_lhkpn_data_pribadi.TANGGAL_LAHIR,
    t_lhkpn_data_pribadi.AGAMA,
    t_lhkpn_data_pribadi.ALAMAT_RUMAH,
    t_lhkpn_data_pribadi.KELURAHAN,
    t_lhkpn_data_pribadi.KECAMATAN,
    t_lhkpn_data_pribadi.KABKOT,
    t_lhkpn_data_pribadi.HP,
    t_lhkpn_data_pribadi.EMAIL_PRIBADI,
    t_lhkpn_data_pribadi.NO_KK,
    t_lhkpn_data_pribadi.JENIS_KELAMIN,
    m_jabatan.NAMA_JABATAN,
    m_unit_kerja.UK_NAMA,
    m_inst_satker.INST_NAMA,
    t_lhkpn_jabatan.ALAMAT_KANTOR,
    t_lhkpn_keluarga.NAMA as K_NAMA,
    t_lhkpn_keluarga.HUBUNGAN as K_HUBUNGAN,
    t_lhkpn_keluarga.STATUS_HUBUNGAN as K_STATUS_HUBUNGAN,
    t_lhkpn_keluarga.TEMPAT_LAHIR as K_TEMPAT_LAHIR,
    t_lhkpn_keluarga.TANGGAL_LAHIR as K_TANGGAL_LAHIR,
    t_lhkpn_keluarga.JENIS_KELAMIN as K_JENIS_KELAMIN,
    t_lhkpn_keluarga.PEKERJAAN as K_PEKERJAAN,
    t_lhkpn.ID_LHKPN_PREV,
    t_lhkpn.ID_PN,
    ');
    $this->db->from('t_lhkpn');
    $this->db->join('t_pn', ' t_pn.ID_PN = t_lhkpn.ID_PN', 'left');
    $this->db->join('t_lhkpn_data_pribadi', ' t_lhkpn_data_pribadi.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
    $this->db->join('t_lhkpn_jabatan', ' t_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN and t_lhkpn_jabatan.IS_PRIMARY = "1"', 'left');
    $this->db->join('m_jabatan', ' m_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
    $this->db->join('m_unit_kerja', 'm_jabatan.UK_ID = m_unit_kerja.UK_ID', 'left');
    $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
    $this->db->join('t_lhkpn_keluarga', 't_lhkpn_keluarga.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
    if($is_bak){
      $this->db->where('t_lhkpn.ID_LHKPN_PREV = "'.$id_lhkpn.'"');
      $this->db->where('t_lhkpn.JENIS_LAPORAN = 5');
      $this->db->where('t_lhkpn.IS_ACTIVE = 1');
    }else{
      $this->db->where('t_lhkpn.ID_LHKPN = "'.$id_lhkpn.'"');
    }
    $q = $this->db->get();

    return $q->result();
  }


  public function get_riwayat_pelaporan($id_lhkpn, $is_jabatan_utama = 1)
  {
    //get id_Pn
    // $id_pn = $this->db->select('t_lhkpn.ID_PN')
    //           ->from('t_lhkpn')
    //           ->where('t_lhkpn.ID_LHKPN =', $id_lhkpn )
    //           ->get()
    //           ->result();
    //
    // //get all
    // $riwayat_lapor = $this->db->select('
    //   t_lhkpn.ID_LHKPN,
    //   t_lhkpn.ID_PN,
    //   t_pn_jabatan.DESKRIPSI_JABATAN,
    //   t_pn_jabatan.NAMA_LEMBAGA,
    //   t_lhkpn.JENIS_LAPORAN,
    //   t_lhkpn.tgl_lapor
    // ')
    // ->from('t_lhkpn')
    // ->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left')
    // ->join('t_pn_jabatan', 't_pn_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left')
    // // ->where('t_lhkpn.ID_PN = '.$id_pn[0]->ID_PN.' AND t_lhkpn.IS_ACTIVE = 1')
    // ->where('t_lhkpn_jabatan.ID_LHKPN', $id_lhkpn)
    // ->group_by('t_lhkpn.ID_LHKPN')
    // ->order_by('t_lhkpn.tgl_lapor', 'DESC')
    // ->get();

    $this->db->select('
    m_jabatan.NAMA_JABATAN,
    t_lhkpn_jabatan.LEMBAGA,
    m_inst_satker.INST_NAMA,
    t_lhkpn.JENIS_LAPORAN,
    t_lhkpn.tgl_lapor')
    ->from('t_lhkpn_jabatan')
    ->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_jabatan.ID_LHKPN', 'left')
    ->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_lhkpn_jabatan.LEMBAGA', 'left')
    ->where('t_lhkpn_jabatan.id_lhkpn', $id_lhkpn);

    if ($is_jabatan_utama) {
      $this->db->where('t_lhkpn_jabatan.IS_PRIMARY', '1');
    }
    else {
      $this->db->where('t_lhkpn_jabatan.ID_STATUS_AKHIR_JABAT', '1');
    }

    $jabatan = $this->db->get();

    return $jabatan->result();
  }

  public function get_riwayat_sebelumnya($id_lhkpn)
  {
      $id_pn = $this->db->select('t_lhkpn.ID_PN')
      ->from('t_lhkpn')
      ->where('t_lhkpn.ID_LHKPN =', $id_lhkpn)
      ->get()
      ->result();

      //get all
      $riwayat_lapor = $this->db->select('
      t_lhkpn.ID_LHKPN,
      t_lhkpn.ID_PN,
      t_pn_jabatan.DESKRIPSI_JABATAN,
      t_pn_jabatan.NAMA_LEMBAGA,
      t_lhkpn.JENIS_LAPORAN,
      t_lhkpn.tgl_lapor
      ')
      ->from('t_lhkpn')
      ->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left')
      ->join('t_pn_jabatan', 't_pn_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left')
      ->where('t_lhkpn.ID_PN = '.$id_pn[0]->ID_PN.'')
      ->where('t_lhkpn_jabatan.ID_LHKPN <', $id_lhkpn)
      ->where('t_lhkpn.IS_ACTIVE =', 1)
      ->where('t_lhkpn.STATUS >', 0)
      ->group_by('t_lhkpn.ID_LHKPN')
      ->order_by('t_lhkpn.tgl_lapor', 'DESC');

      $jabatan = $this->db->get();
  
      return $jabatan->result();
  }

  public function get_data_keluarga($id_lhkpn)
  {
    $data_keluarga = $this->db->select('
      t_lhkpn.tgl_lapor,
      t_lhkpn.JENIS_LAPORAN,
      t_lhkpn_keluarga.NAMA,
      t_lhkpn_keluarga.NIK,
      t_lhkpn_keluarga.HUBUNGAN,
      t_lhkpn_keluarga.STATUS_HUBUNGAN,
      t_lhkpn_keluarga.TEMPAT_LAHIR,
      t_lhkpn_keluarga.TANGGAL_LAHIR,
      t_lhkpn_keluarga.JENIS_KELAMIN,
      t_lhkpn_keluarga.PEKERJAAN,
      t_lhkpn_keluarga.NOMOR_TELPON,
      t_lhkpn_keluarga.ALAMAT_RUMAH
    ')
    ->from('t_lhkpn_keluarga')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_keluarga.ID_LHKPN')
    ->where('t_lhkpn_keluarga.ID_LHKPN', $id_lhkpn)
    ->order_by('t_lhkpn_keluarga.HUBUNGAN', 'asc')
    ->get();

    return $data_keluarga->result();
  }

  public function get_data_tanah_bangunan($id_lhkpn, $is_new = 0) //is_new ==> update terbaru untuk sheet 1-9
  {
    // t_lhkpn_harta_tidak_bergerak.jalan,

    if($is_new){
      $select = $this->db->select("
      CONCAT( IFNULL(t_lhkpn_harta_tidak_bergerak.jalan, 'Jalan -'), ', ', IFNULL(t_lhkpn_harta_tidak_bergerak.KEL,'KEL. -'), ', ',  IFNULL(t_lhkpn_harta_tidak_bergerak.KEC,'KEC. -'), ', ',  IFNULL(t_lhkpn_harta_tidak_bergerak.KAB_KOT,'KAB. -'), ', ',  IFNULL(t_lhkpn_harta_tidak_bergerak.PROV,'PROV. -')) AS jalan,
      t_lhkpn_harta_tidak_bergerak.LUAS_TANAH,
      t_lhkpn_harta_tidak_bergerak.LUAS_BANGUNAN,
      m_jenis_bukti.JENIS_BUKTI,
      t_lhkpn_harta_tidak_bergerak.NOMOR_BUKTI,
      t_lhkpn_harta_tidak_bergerak.ATAS_NAMA,
      t_lhkpn_harta_tidak_bergerak.ASAL_USUL,
      m_pemanfaatan.PEMANFAATAN,
      t_lhkpn_harta_tidak_bergerak.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_tidak_bergerak.STATUS,
      t_lhkpn_harta_tidak_bergerak.NILAI_PEROLEHAN,
      t_lhkpn_harta_tidak_bergerak.NILAI_PELAPORAN,
      t_lhkpn_harta_tidak_bergerak.NILAI_PELAPORAN_OLD,
      t_lhkpn_harta_tidak_bergerak.IS_PELEPASAN,
      t_lhkpn.tgl_lapor,
      t_lhkpn.JENIS_LAPORAN
      ", false);
    }else{
      $select = $this->db->select("
      CONCAT( IFNULL(t_lhkpn_harta_tidak_bergerak.jalan, 'Jalan -'), ', ', IFNULL(t_lhkpn_harta_tidak_bergerak.KEL,'KEL. -'), ', ',  IFNULL(t_lhkpn_harta_tidak_bergerak.KEC,'KEC. -'), ', ',  IFNULL(t_lhkpn_harta_tidak_bergerak.KAB_KOT,'KAB. -'), ', ',  IFNULL(t_lhkpn_harta_tidak_bergerak.PROV,'PROV. -')) AS jalan,
      t_lhkpn_harta_tidak_bergerak.LUAS_TANAH,
      t_lhkpn_harta_tidak_bergerak.LUAS_BANGUNAN,
      m_jenis_bukti.JENIS_BUKTI,
      t_lhkpn_harta_tidak_bergerak.NOMOR_BUKTI,
      t_lhkpn_harta_tidak_bergerak.ATAS_NAMA,
      t_lhkpn_harta_tidak_bergerak.ASAL_USUL,
      m_pemanfaatan.PEMANFAATAN,
      t_lhkpn_harta_tidak_bergerak.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_tidak_bergerak.NILAI_PEROLEHAN,
      t_lhkpn_harta_tidak_bergerak.NILAI_PELAPORAN,
      t_lhkpn_harta_tidak_bergerak.NILAI_PELAPORAN_OLD,
      t_lhkpn.tgl_lapor,
      t_lhkpn.JENIS_LAPORAN
      ", false);
    }
    $data_tnh = $select;
    $this->db->from('t_lhkpn_harta_tidak_bergerak')
      ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_harta_tidak_bergerak.ID_LHKPN')
      ->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_harta_tidak_bergerak.ASAL_USUL','left')
      ->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = t_lhkpn_harta_tidak_bergerak.JENIS_BUKTI','left')
      ->join('m_pemanfaatan', 'm_pemanfaatan.ID_PEMANFAATAN = t_lhkpn_harta_tidak_bergerak.PEMANFAATAN','left')
      ->where('t_lhkpn_harta_tidak_bergerak.ID_LHKPN', $id_lhkpn)
      ->where('t_lhkpn_harta_tidak_bergerak.IS_ACTIVE = 1')
      ->where('t_lhkpn_harta_tidak_bergerak.IS_PELEPASAN = 0')
      ->order_by('t_lhkpn_harta_tidak_bergerak.ID', 'desc');
    $data_tnh = $this->db->get();
    
    return $data_tnh->result();
  }

  public function get_data_bergerak($id_lhkpn, $is_new = 0)
  {
    if($is_new){
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_bergerak.MEREK,
      t_lhkpn_harta_bergerak.MODEL,
      t_lhkpn_harta_bergerak.TAHUN_PEMBUATAN,
      t_lhkpn_harta_bergerak.NOPOL_REGISTRASI,
      m_jenis_bukti.JENIS_BUKTI,
      t_lhkpn_harta_bergerak.ASAL_USUL,
      t_lhkpn_harta_bergerak.ATAS_NAMA,
      m_pemanfaatan.PEMANFAATAN,
      t_lhkpn_harta_bergerak.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_bergerak.KET_LAINNYA,
      t_lhkpn_harta_bergerak.STATUS,
      t_lhkpn_harta_bergerak.NILAI_PEROLEHAN,
      t_lhkpn_harta_bergerak.NILAI_PELAPORAN,
      t_lhkpn_harta_bergerak.IS_PELEPASAN,
      t_lhkpn_harta_bergerak.ID_LHKPN,
      t_lhkpn.tgl_lapor,
      t_lhkpn.JENIS_LAPORAN,
      ');
    }else{
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_bergerak.MEREK,
      t_lhkpn_harta_bergerak.MODEL,
      t_lhkpn_harta_bergerak.TAHUN_PEMBUATAN,
      t_lhkpn_harta_bergerak.NOPOL_REGISTRASI,
      m_jenis_bukti.JENIS_BUKTI,
      t_lhkpn_harta_bergerak.ASAL_USUL,
      t_lhkpn_harta_bergerak.ATAS_NAMA,
      m_pemanfaatan.PEMANFAATAN,
      t_lhkpn_harta_bergerak.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_bergerak.KET_LAINNYA,
      t_lhkpn_harta_bergerak.NILAI_PEROLEHAN,
      t_lhkpn_harta_bergerak.NILAI_PELAPORAN,
      t_lhkpn_harta_bergerak.ID_LHKPN,
      t_lhkpn.tgl_lapor,
      t_lhkpn.JENIS_LAPORAN,
      ');
    }
    
    $data_harta_bergerak = $select;
    $this->db->from('t_lhkpn_harta_bergerak')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_harta_bergerak.ID_LHKPN')
    ->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_bergerak.KODE_JENIS','left')
    ->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = t_lhkpn_harta_bergerak.JENIS_BUKTI','left')
    ->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_harta_bergerak.ASAL_USUL','left')
    ->join('m_pemanfaatan', 'm_pemanfaatan.ID_PEMANFAATAN = t_lhkpn_harta_bergerak.PEMANFAATAN','left')
    ->where('t_lhkpn_harta_bergerak.ID_LHKPN', $id_lhkpn)
    ->where('t_lhkpn_harta_bergerak.IS_ACTIVE = 1')
    ->where('t_lhkpn_harta_bergerak.IS_PELEPASAN = 0')
    ->order_by('t_lhkpn_harta_bergerak.ID', 'desc');
    $data_harta_bergerak = $this->db->get();

    return $data_harta_bergerak->result();
  }

  public function get_data_bergerak_lainnya($id_lhkpn, $is_new = 0)
  {
    if($is_new){
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_bergerak_lain.JUMLAH,
      t_lhkpn_harta_bergerak_lain.SATUAN,
      t_lhkpn_harta_bergerak_lain.KETERANGAN,
      t_lhkpn_harta_bergerak_lain.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_bergerak_lain.ASAL_USUL,
      t_lhkpn_harta_bergerak_lain.STATUS,
      t_lhkpn_harta_bergerak_lain.NILAI_PEROLEHAN,
      t_lhkpn_harta_bergerak_lain.NILAI_PELAPORAN,
      t_lhkpn_harta_bergerak_lain.NILAI_PELAPORAN_OLD,
      t_lhkpn_harta_bergerak_lain.IS_PELEPASAN,
      t_lhkpn.tgl_lapor,
      t_lhkpn.JENIS_LAPORAN ');
    }else{
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_bergerak_lain.JUMLAH,
      t_lhkpn_harta_bergerak_lain.SATUAN,
      t_lhkpn_harta_bergerak_lain.KETERANGAN,
      t_lhkpn_harta_bergerak_lain.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_bergerak_lain.ASAL_USUL,
      t_lhkpn_harta_bergerak_lain.NILAI_PEROLEHAN,
      t_lhkpn_harta_bergerak_lain.NILAI_PELAPORAN,
      t_lhkpn_harta_bergerak_lain.NILAI_PELAPORAN_OLD,
      t_lhkpn.tgl_lapor,
      t_lhkpn.JENIS_LAPORAN ');
    }
    
    $data_harta_bergerak_lainnya = $select;
    $this->db->from('t_lhkpn_harta_bergerak_lain')
    ->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_bergerak_lain.KODE_JENIS')
    ->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_harta_bergerak_lain.ASAL_USUL','left')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_harta_bergerak_lain.ID_LHKPN')
    ->where('t_lhkpn_harta_bergerak_lain.ID_LHKPN ='.$id_lhkpn.' and t_lhkpn_harta_bergerak_lain.IS_ACTIVE = 1')
    ->where('t_lhkpn_harta_bergerak_lain.IS_PELEPASAN = 0')
    ->order_by('t_lhkpn_harta_bergerak_lain.ID', 'desc');
    $data_harta_bergerak_lainnya = $this->db->get();

    return $data_harta_bergerak_lainnya->result();

  }

  public function get_surat_berharga($id_lhkpn, $is_new = 0)
  {
    if($is_new){
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_surat_berharga.ATAS_NAMA,
      t_lhkpn_harta_surat_berharga.NAMA_PENERBIT,
      t_lhkpn_harta_surat_berharga.CUSTODIAN,
      t_lhkpn_harta_surat_berharga.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_surat_berharga.NOMOR_REKENING,
      t_lhkpn_harta_surat_berharga.ASAL_USUL,
      t_lhkpn_harta_surat_berharga.STATUS,
      t_lhkpn_harta_surat_berharga.NILAI_PEROLEHAN,
      t_lhkpn_harta_surat_berharga.NILAI_PELAPORAN,
      t_lhkpn_harta_surat_berharga.NILAI_PELAPORAN_OLD,
      t_lhkpn_harta_surat_berharga.IS_PELEPASAN,
      t_lhkpn.JENIS_LAPORAN,
      t_lhkpn.tgl_lapor
      ');
    }else{
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_surat_berharga.ATAS_NAMA,
      t_lhkpn_harta_surat_berharga.NAMA_PENERBIT,
      t_lhkpn_harta_surat_berharga.CUSTODIAN,
      t_lhkpn_harta_surat_berharga.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_surat_berharga.NOMOR_REKENING,
      t_lhkpn_harta_surat_berharga.ASAL_USUL,
      t_lhkpn_harta_surat_berharga.NILAI_PEROLEHAN,
      t_lhkpn_harta_surat_berharga.NILAI_PELAPORAN,
      t_lhkpn_harta_surat_berharga.NILAI_PELAPORAN_OLD,
      t_lhkpn.JENIS_LAPORAN,
      t_lhkpn.tgl_lapor
      ');
    }
    
    $data_surat_berharga = $select;
    $this->db->from('t_lhkpn_harta_surat_berharga')
    ->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_surat_berharga.KODE_JENIS')
    ->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_harta_surat_berharga.ASAL_USUL','left')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_harta_surat_berharga.ID_LHKPN')
    ->where('t_lhkpn_harta_surat_berharga.IS_ACTIVE = 1')
    ->where('t_lhkpn_harta_surat_berharga.ID_LHKPN =', $id_lhkpn)
    ->where('t_lhkpn_harta_surat_berharga.IS_PELEPASAN = 0')
    ->order_by('t_lhkpn_harta_surat_berharga.ID', 'desc');
    $data_surat_berharga = $this->db->get();

    return $data_surat_berharga->result();
  }

  public function get_data_kas($id_lhkpn, $is_new = 0)
  {
    //TODO: ganti keterangan di baris ke-2 dengan informasi sebenarnya
    // t_lhkpn_harta_kas.KETERANGAN,

    //
    // t_lhkpn_harta_kas.NILAI_SALDO, ubah menjadi nilai equivalen

    if($is_new){
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_kas.KETERANGAN as URAIAN_KETERANGAN,
      t_lhkpn_harta_kas.NAMA_BANK,
      t_lhkpn_harta_kas.NOMOR_REKENING,
      t_lhkpn_harta_kas.ATAS_NAMA_REKENING,
      t_lhkpn_harta_kas.KETERANGAN,
      t_lhkpn_harta_kas.TAHUN_BUKA_REKENING,
      t_lhkpn_harta_kas.ASAL_USUL,
      t_lhkpn_harta_kas.STATUS,
      t_lhkpn_harta_kas.NILAI_EQUIVALEN,
      t_lhkpn_harta_kas.NILAI_EQUIVALEN_OLD,
      t_lhkpn_harta_kas.IS_PELEPASAN,
      t_lhkpn.tgl_lapor,
      t_lhkpn.JENIS_LAPORAN
      ');
    }else{
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_kas.KETERANGAN as URAIAN_KETERANGAN,
      t_lhkpn_harta_kas.NAMA_BANK,
      t_lhkpn_harta_kas.NOMOR_REKENING,
      t_lhkpn_harta_kas.ATAS_NAMA_REKENING,
      t_lhkpn_harta_kas.KETERANGAN,
      t_lhkpn_harta_kas.TAHUN_BUKA_REKENING,
      t_lhkpn_harta_kas.ASAL_USUL,
      t_lhkpn_harta_kas.NILAI_EQUIVALEN,
      t_lhkpn_harta_kas.NILAI_EQUIVALEN_OLD,
      t_lhkpn.tgl_lapor,
      t_lhkpn.JENIS_LAPORAN
      ');
    }
    $data_kas = $select;
    $this->db->from('t_lhkpn_harta_kas')
    ->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_kas.KODE_JENIS')
    ->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_harta_kas.ASAL_USUL','left')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_harta_kas.ID_LHKPN')
    ->where('t_lhkpn_harta_kas.ID_LHKPN', $id_lhkpn)
    ->where('t_lhkpn_harta_kas.IS_ACTIVE = 1')
    ->where('t_lhkpn_harta_kas.IS_PELEPASAN = 0')
    ->order_by('t_lhkpn_harta_kas.ID', 'desc');
    $data_kas = $this->db->get();

    return $data_kas->result();
  }

  public function get_harta_lainnya($id_lhkpn, $is_new = 0)
  {
    if($is_new){
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_lainnya.KETERANGAN,
      t_lhkpn_harta_lainnya.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_lainnya.ASAL_USUL,
      t_lhkpn_harta_lainnya.STATUS,
      t_lhkpn_harta_lainnya.NILAI_PEROLEHAN,
      t_lhkpn_harta_lainnya.NILAI_PELAPORAN,
      t_lhkpn_harta_lainnya.NILAI_PELAPORAN_OLD,
      t_lhkpn_harta_lainnya.IS_PELEPASAN,
      t_lhkpn.JENIS_LAPORAN,
      t_lhkpn.tgl_lapor');
    }else{
      $select = $this->db->select('
      m_jenis_harta.NAMA,
      t_lhkpn_harta_lainnya.KETERANGAN,
      t_lhkpn_harta_lainnya.TAHUN_PEROLEHAN_AWAL,
      t_lhkpn_harta_lainnya.ASAL_USUL,
      t_lhkpn_harta_lainnya.NILAI_PEROLEHAN,
      t_lhkpn_harta_lainnya.NILAI_PELAPORAN,
      t_lhkpn_harta_lainnya.NILAI_PELAPORAN_OLD,
      t_lhkpn.JENIS_LAPORAN,
      t_lhkpn.tgl_lapor');
    }
    $harta_lainnya = $select;
    $this->db->from('t_lhkpn_harta_lainnya')
    ->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_lainnya.KODE_JENIS')
    ->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_harta_lainnya.ASAL_USUL','left')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_harta_lainnya.ID_LHKPN')
    ->where('t_lhkpn_harta_lainnya.ID_LHKPN = ', $id_lhkpn)
    ->where('t_lhkpn_harta_lainnya.IS_ACTIVE = 1')
    ->where('t_lhkpn_harta_lainnya.IS_PELEPASAN = 0')
    ->order_by('t_lhkpn_harta_lainnya.ID', 'desc');
    $harta_lainnya = $this->db->get();

    return $harta_lainnya->result();
  }

  public function get_data_hutang($id_lhkpn)
  {
    $data_hutang = $this->db->select('
      m_jenis_hutang.NAMA,
      t_lhkpn_hutang.ATAS_NAMA,
      t_lhkpn_hutang.NAMA_KREDITUR,
      t_lhkpn_hutang.AGUNAN,
      t_lhkpn_hutang.AWAL_HUTANG,
      t_lhkpn_hutang.SALDO_HUTANG,
      t_lhkpn_hutang.SALDO_HUTANG_OLD,
      t_lhkpn.JENIS_LAPORAN,
      t_lhkpn.tgl_lapor
    ')
    ->from('t_lhkpn_hutang')
    ->join('m_jenis_hutang', 'm_jenis_hutang.ID_JENIS_HUTANG = t_lhkpn_hutang.KODE_JENIS')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_hutang.ID_LHKPN')
    ->where('t_lhkpn_hutang.ID_LHKPN =', $id_lhkpn)
    ->where(' t_lhkpn_hutang.IS_ACTIVE = 1')
    ->where(' t_lhkpn_hutang.KODE_JENIS <>', NULL)
    ->order_by('t_lhkpn_hutang.ID_HUTANG', 'desc')
    ->get();

    return $data_hutang->result();
  }

  public function get_data_penerimaan_tunai($id_lhkpn)
  {
    $data_penerimaan_tunai = $this->db->select('
      SUM(t_lhkpn_penerimaan_kas2.PN) AS NILAI_PENERIMAAN_KAS_PN,
      SUM(t_lhkpn_penerimaan_kas2.PASANGAN) AS NILAI_PENERIMAAN_KAS_PASANGAN,
      SUM(t_lhkpn_penerimaan_kas2.PN_OLD) AS NILAI_PENERIMAAN_KAS_PN_OLD,
      SUM(t_lhkpn_penerimaan_kas2.PASANGAN_OLD) AS NILAI_PENERIMAAN_KAS_PASANGAN_OLD,
      t_lhkpn_penerimaan_kas2.KODE_JENIS AS JENIS_PENERIMAAN,
      t_lhkpn.tgl_lapor
    ')
    ->from('t_lhkpn_penerimaan_kas2')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_penerimaan_kas2.ID_LHKPN')
    ->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $id_lhkpn)
    ->group_by('t_lhkpn_penerimaan_kas2.KODE_JENIS','t_lhkpn.tgl_lapor')
    ->order_by('t_lhkpn_penerimaan_kas2.ID_PENERIMAAN_KAS', 'asc')
    ->get();
    // $data_penerimaan_tunai = $this->db->select('
    //   t_lhkpn_penerimaan_kas.NILAI_PENERIMAAN_KAS_PN,
    //   t_lhkpn_penerimaan_kas.NILAI_PENERIMAAN_KAS_PASANGAN,
    //   t_lhkpn.JENIS_LAPORAN,
    //   t_lhkpn.tgl_lapor
    // ')
    // ->from('t_lhkpn_penerimaan_kas')
    // ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_penerimaan_kas.ID_LHKPN')
    // ->where('t_lhkpn_penerimaan_kas.ID_LHKPN', '555838')
    // ->where('t_lhkpn_penerimaan_kas.IS_ACTIVE = 1')
    // ->order_by('t_lhkpn_penerimaan_kas.ID_PENERIMAAN_KAS', 'desc')
    // ->get();

    return $data_penerimaan_tunai->result();
  }

  public function get_data_pengeluaran_tunai($id_lhkpn)
  {
    $data_pengeluaran_tunai = $this->db->select('
      SUM(t_lhkpn_pengeluaran_kas2.JML) AS NILAI_PENGELUARAN_KAS,
      SUM(t_lhkpn_pengeluaran_kas2.JML_OLD) AS NILAI_PENGELUARAN_KAS_OLD,
      t_lhkpn_pengeluaran_kas2.KODE_JENIS AS JENIS_PENGELUARAN,
      t_lhkpn.tgl_lapor
    ')
    ->from('t_lhkpn_pengeluaran_kas2')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_pengeluaran_kas2.ID_LHKPN')
    ->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN', $id_lhkpn)
    ->group_by('t_lhkpn_pengeluaran_kas2.KODE_JENIS','t_lhkpn.tgl_lapor')
    ->order_by('t_lhkpn_pengeluaran_kas2.ID_PENGELUARAN_KAS', 'asc')
    ->get();
    // $data_pengeluaran_tunai = $this->db->select('
    //   t_lhkpn_pengeluaran_kas.NILAI_PENGELUARAN_KAS,
    //   t_lhkpn.JENIS_LAPORAN,
    //   t_lhkpn.tgl_lapor
    // ')
    // ->from('t_lhkpn_pengeluaran_kas')
    // ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_pengeluaran_kas.ID_LHKPN')
    // ->where('t_lhkpn_pengeluaran_kas.ID_LHKPN', $id_lhkpn)
    // ->where('t_lhkpn_pengeluaran_kas.IS_ACTIVE = 1')
    // ->order_by('t_lhkpn_pengeluaran_kas.ID_PENGELUARAN_KAS', 'desc')
    // ->get();

    return $data_pengeluaran_tunai->result();

  }
}
