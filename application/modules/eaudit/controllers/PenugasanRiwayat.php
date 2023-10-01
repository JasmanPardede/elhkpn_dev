<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenugasanRiwayat extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('eaudit/Penugasan_riwayat_model');
    $this->load->model('Mglobal');
  }

  public function modal_riwayat($id_lhkpn)
  {
    // code...
   $item = $this->Penugasan_riwayat_model->get_detail_pn_lhkpn($id_lhkpn,true,true);
    
    $id_pn = $item->ID_PN;
    $nama_pn = $item->NAMA;
    $nik = $item->NIK;
    // semua data lhkpn oleh pn
    $data_lhkpn = $this->Mglobal->get_data_all('t_lhkpn', null, ['id_pn' => $item->ID_PN, 'is_active' => '1']);
    $data_riwayat = array();
    $baru =  array();
    $b = array();
    foreach($data_lhkpn as $lhkpn){
      //audit lhkpn
      $riwayat = $this->Penugasan_riwayat_model->get_riwayat_penugasan($lhkpn->ID_LHKPN);

      foreach($riwayat as $r){
          $no++;
        if($r){
          array_push( $baru,$r);
          $item_baru = $this->Penugasan_riwayat_model->get_detail_pn_lhkpn($r->id_lhkpn,true,true);
          $agenda = date('Y', strtotime($item_baru->tgl_lapor)) . '/' . ($item_baru->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->nik_benar . '/' . $item_baru->ID_LHKPN;
          
          $new_array = (object) [
            "tgl_mulai_periksa"=>$r->tgl_mulai_periksa,
            "agenda"=>$agenda,
            "tgl_selesai_periksa"=>$r->tgl_selesai_periksa,
            "tgl_kirim_final"=>$r->tgl_kirim_final,
            "jenis_penugasan"=> $r->jenis_penugasan,
            "jenis_pemeriksaan"=> $r->jenis_pemeriksaan,
            "nomor_surat_tugas"=> $r->nomor_surat_tugas,
            "status_periksa"=> $r->status_periksa,
            "creator"=> $r->creator,
            "pemeriksa"=> $r->pemeriksa,
            "pic"=> $r->pic
          ];
          array_push( $data_riwayat,$new_array);
        }
      }
    }
        //sorting berdasarkan riwayat audit terakhir
    rsort($data_riwayat);
    $data['title'] = '<b>'.$nama_pn.'</b> ('.$item->nik_benar.')';
    $data['daftar_riwayat'] = $data_riwayat;

    $this->load->view('penugasan_new/penugasan_riwayat', $data);
  }

}
