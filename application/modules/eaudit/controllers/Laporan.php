<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index()
  {
    $id_user = $this->session->userdata('ID_USER');
    $data = array(
      'id_user' =>  $id_user
    );

    $this->load->view('laporan/laporan_index', $data);
  }

  public function tbn_harta()
  {
    $id_user = $this->session->userdata('ID_USER');

    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_TopBottomHartaPN/TopBottomHartaPN');

    $data = array(
      'id_user' =>  $id_user,
      'tbn_harta_url' => $ripot,
    );

    $this->load->view('laporan/laporan_tbn_harta', $data);
  }

  public function outlier_stdev()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_OutlierStDev/Outlier');

    $data = array(
      'id_user' =>  $id_user,
      'outlier_url' => $ripot,
    );

    $this->load->view('laporan/laporan_outlier', $data);
  }

  public function red_flag()
  {
    $id_user = $this->session->userdata('ID_USER');
//
//    // -- setting tablue
//    $this->load->helper('tableau');
//    $userTableau = $this->config->item('userTableau');
//    $serverTableau = $this->config->item('serverTableau');
//    $view_tableau = $this->config->item('view_tableau');
//
//    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_RedFlag/Flag1');
//
    $data = array(
      'id_user' =>  $id_user,
//      'redflag_url' => $ripot,
    );

    $this->load->view('laporan/laporan_redflag', $data);
  }
  public function penugasan()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_Penugasan/Penugasan');

    $data = array(
      'id_user' =>  $id_user,
      'penugasan_url' => $ripot,
    );

    $this->load->view('laporan/laporan_penugasan', $data);
  }
  public function pemeriksaan()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_Pemeriksaan_0/Pemeriksaan');

    $data = array(
      'id_user' =>  $id_user,
      'pemeriksaan_url' => $ripot,
    );

    $this->load->view('laporan/laporan_pemeriksaan', $data);
  }
  public function sebaran_wilayah_pemeriksaan()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_SebaranWilayahPemeriksaan/SebaranWilayah');

    $data = array(
      'id_user' =>  $id_user,
      'sebaran_wilayah_pemeriksaan_url' => $ripot,
    );

    $this->load->view('laporan/laporan_sebaran_wilayah_pemeriksaan', $data);
  }
  public function beban_kerja_pemeriksa()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_BebanKerjaPemeriksa/BebanKerjaPemeriksa');

    $data = array(
      'id_user' =>  $id_user,
      'beban_kerja_pemeriksa_url' => $ripot,
    );

    $this->load->view('laporan/laporan_beban_kerja_pemeriksa', $data);
  }
  
  public function ketidakwajaran_kenaikan_harta()
  {
    $id_user = $this->session->userdata('ID_USER');
    $data = array(
      'id_user' =>  $id_user,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_kenaikan_harta', $data);
  }
  
  public function ketidakwajaran_kenaikan_harta_ranking_instansi()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_RedFlag1_RankingInstansiKetidakwajaranHarta/RankingInstansi');

    $data = array(
      'id_user' =>  $id_user,
      'ketidakwajaran_kenaikan_harta_ranking_instansi_url' => $ripot,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_kenaikan_harta_ranking_instansi', $data);
  }
  
  public function ketidakwajaran_kenaikan_harta_ranking_PN()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_RedFlag1_KetidakwajaranHartaKekayaanPN/KetidakwajaranKenaikanHarta');

    $data = array(
      'id_user' =>  $id_user,
      'ketidakwajaran_kenaikan_harta_ranking_pn_url' => $ripot,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_kenaikan_harta_ranking_pn', $data);
  }
  
  public function ketidakwajaran_hibah()
  {
    $id_user = $this->session->userdata('ID_USER');
    $data = array(
      'id_user' =>  $id_user,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_hibah', $data);
  }
  
  public function ketidakwajaran_hibah_ranking_PN()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_RedFlag2_KetidakwajaranHadiahHibahWarisan/KetidakwajaranHibahHadiahWarisan');

    $data = array(
      'id_user' =>  $id_user,
      'ketidakwajaran_hibah_ranking_pn_url' => $ripot,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_hibah_ranking_pn', $data);
  }
  
  public function ketidakwajaran_hutang()
  {
    $id_user = $this->session->userdata('ID_USER');
    $data = array(
      'id_user' =>  $id_user,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_hutang', $data);
  }
  
  public function ketidakwajaran_hutang_ranking_PN()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_RedFlag3_KetidakwajaranHutang/KetidakwajaranHutang');

    $data = array(
      'id_user' =>  $id_user,
      'ketidakwajaran_hutang_ranking_pn_url' => $ripot,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_hutang_ranking_pn', $data);
  }
  
  public function ketidakwajaran_lhkpn()
  {
    $id_user = $this->session->userdata('ID_USER');
    $data = array(
      'id_user' =>  $id_user,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_lhkpn', $data);
  }
  
  public function ketidakwajaran_lhkpn_ranking_instansi()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_RedFlag4_TopInstansiKetidakwajaranLHKPN/TopInstansi');

    $data = array(
      'id_user' =>  $id_user,
      'ketidakwajaran_lhkpn_ranking_instansi_url' => $ripot,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_lhkpn_ranking_instansi', $data);
  }
  
  public function ketidakwajaran_lhkpn_ranking_PN()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eAudit_RedFlag4_KetidakwajaranLHKPN/KetidakwajaranLHKPN');

    $data = array(
      'id_user' =>  $id_user,
      'ketidakwajaran_lhkpn_ranking_pn_url' => $ripot,
    );

    $this->load->view('laporan/laporan_ketidakwajaran_lhkpn_ranking_pn', $data);
  }
  
  public function capaian_kinerja_pemeriksaan()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_CapaianKinerjaPemeriksaan_2020/CapaianKinerjaDirektur');

    $data = array(
      'id_user' =>  $id_user,
      'capaian_kinerja_pemeriksaan_url' => $ripot,
    );

    $this->load->view('laporan/laporan_capaian_kinerja_pemeriksaan', $data);
  }
  public function monitoring_pemeriksaan()
  {
    $id_user = $this->session->userdata('ID_USER');

    // -- setting tablue
    $this->load->helper('tableau');
    $userTableau = $this->config->item('userTableaulhkpn');
    $serverTableau = $this->config->item('serverTableau');
    $view_tableau = $this->config->item('view_tableau');

    $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/DashboardRiksa2021/MonitoringPemeriksaanLHKPN');

    $data = array(
      'id_user' =>  $id_user,
      'monitoring_pemeriksaan_url' => $ripot,
    );

    $this->load->view('laporan/laporan_monitoring_pemeriksaan', $data);
  }

}
