<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// call php-spreadsheet library
require APPPATH.'/third_party/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CetakKKP extends CI_Controller {

  // CONST for color
  var $color_green = '00008080';
  var $color_green_other = '0000b894';
  var $color_red = '00632523';
  var $color_red_other = '00d63031';
  var $color_yellow = '00ffc000';
  var $color_black = '00000000';
  var $color_lightyellow = '00ffff66';
  var $color_blue = '001f497d';
  var $color_gray = '00eeece1';
  var $color_darkgray = '00d9d9d9';
  var $color_darkgray_other = '0095a5a6';
  var $color_lightgray = '00d6d6d6';
  var $color_white = '00ffffff';
  var $color_grayborder = '009c9c9c';
  var $color_brown = '00FFCC99';

  // data variables
  public $data_pn = '';
  public $riwayat = '';
  public $data_keluarga = '';
  public $data_tanah_bangunan = '';
  public $data_tanah_bangunan_new = '';
  public $data_harta_bergerak = '';
  public $data_harta_bergerak_new = '';
  public $data_harta_bergerak_lainnya = '';
  public $data_surat_berharga = '';
  public $data_kas = '';
  public $data_harta_lainnya = '';
  public $data_hutang = '';
  public $data_penerimaan_tunai = '';
  public $data_pengeluaran_tunai = '';

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    // $this->load->library('Excel');

    $this->load->model('eaudit/Kkp_model');
    $this->load->model('mglobal');
  }


  public function export_kkp($id_lhkpn, $id_audit = 0)
  {
    $this->load->model('eaudit/Periksa_model');
    $spreadsheet = new Spreadsheet();

    $styleArray = array(
    'font'  => array(
        'size'  => 10,
        'name'  => 'Calibri'
    ));
    $spreadsheet->getDefaultStyle()
      ->applyFromArray($styleArray);

    $new_lhkpn = $this->Periksa_model->get_new_lhkpn($id_lhkpn); 
    
    // initiate the database

    if(count($new_lhkpn) > 0 && $new_lhkpn[0]->is_active = 1){ //cek jika ada tgl_klarif (BAK)
       $this->data_pn                      = $this->Kkp_model->get_biodata_pn($id_lhkpn, 1);
    }else{
       $this->data_pn                      = $this->Kkp_model->get_biodata_pn($id_lhkpn);
    }
    
    $this->riwayat                      = $this->Kkp_model->get_riwayat_pelaporan($id_lhkpn, 1);
    $this->riwayat_lain                 = $this->Kkp_model->get_riwayat_pelaporan($id_lhkpn, 0);
    $this->riwayat_sebelumnya           = $this->Kkp_model->get_riwayat_sebelumnya($id_lhkpn);
    $this->data_keluarga                = $this->Kkp_model->get_data_keluarga($id_lhkpn); 
    $this->data_tanah_bangunan          = $this->Kkp_model->get_data_tanah_bangunan($id_lhkpn);
    $this->data_harta_bergerak          = $this->Kkp_model->get_data_bergerak($id_lhkpn);
    $this->data_harta_bergerak_lainnya  = $this->Kkp_model->get_data_bergerak_lainnya($id_lhkpn);
    $this->data_surat_berharga          = $this->Kkp_model->get_surat_berharga($id_lhkpn);
    $this->data_kas                     = $this->Kkp_model->get_data_kas($id_lhkpn);
    $this->data_harta_lainnya           = $this->Kkp_model->get_harta_lainnya($id_lhkpn);
    $this->data_hutang                  = $this->Kkp_model->get_data_hutang($id_lhkpn);
    $this->data_penerimaan_tunai        = $this->Kkp_model->get_data_penerimaan_tunai($id_lhkpn);
    $this->data_pengeluaran_tunai       = $this->Kkp_model->get_data_pengeluaran_tunai($id_lhkpn);

    $this->data_tanah_bangunan_new      = $this->Kkp_model->get_data_tanah_bangunan($id_lhkpn, 1);
    $this->data_harta_bergerak_new      = $this->Kkp_model->get_data_bergerak($id_lhkpn, 1);
    $this->data_harta_bergerak_lainnya_new  = $this->Kkp_model->get_data_bergerak_lainnya($id_lhkpn, 1);
    $this->data_surat_berharga_new      = $this->Kkp_model->get_surat_berharga($id_lhkpn, 1);

    $this->data_kas_new                 = $this->Kkp_model->get_data_kas($id_lhkpn, 1);
    $this->data_harta_lainnya_new       = $this->Kkp_model->get_harta_lainnya($id_lhkpn, 1);
    $this->data_hutang_new              = $this->Kkp_model->get_data_hutang($id_lhkpn, 1);

    // dd($this->data_harta_lainnya_new);

    // --- start excel content, the magic goes here --- //
    $this->cetak_tujuan($id_lhkpn, $spreadsheet);
    $this->cetak_persiapan($id_lhkpn, $spreadsheet);
    $this->cetak_pelaksanaan($id_lhkpn, $spreadsheet);
    $this->cetak_penyelesaian($id_lhkpn, $spreadsheet);
    $this->cetak_profil($id_lhkpn, $id_audit, $spreadsheet); 
    $this->cetak_ringkasan_harta($id_lhkpn, $spreadsheet); 
    $this->cetak_riwayat_jabatan($id_lhkpn, $spreadsheet);  
    $this->cetak_data_keluarga($id_lhkpn, $spreadsheet);   
    $this->cetak_surat($id_lhkpn, $spreadsheet);  
    $this->cetak_I($id_lhkpn, $spreadsheet);
    $this->cetak_II($id_lhkpn, $spreadsheet); 
    $this->cetak_III($id_lhkpn, $spreadsheet); 
    $this->cetak_IV($id_lhkpn, $spreadsheet);  
    $this->cetak_V($id_lhkpn, $spreadsheet);  
    $this->cetak_VI($id_lhkpn, $spreadsheet);  
    $this->cetak_VII($id_lhkpn, $spreadsheet);  
    $this->cetak_VIII($id_lhkpn, $spreadsheet);    // ada salah disini
    $this->cetak_IX($id_lhkpn, $spreadsheet);  
    $this->cetak_BAK($id_lhkpn, $id_audit, $spreadsheet);  
    $this->cetak_1($id_lhkpn, $spreadsheet);  
    $this->cetak_2($id_lhkpn, $spreadsheet);  
    $this->cetak_3($id_lhkpn, $spreadsheet);  
    $this->cetak_4($id_lhkpn, $spreadsheet);  
    $this->cetak_5($id_lhkpn, $spreadsheet);  
    $this->cetak_6($id_lhkpn, $spreadsheet);  
    $this->cetak_7($id_lhkpn, $spreadsheet);  
    $this->cetak_8($id_lhkpn, $spreadsheet);  
    $this->cetak_9($id_lhkpn, $spreadsheet);  
    $this->cetak_cashflow($id_lhkpn, $spreadsheet);
    $this->cetak_CIA($id_lhkpn, $spreadsheet);  
    $this->cetak_STR($id_lhkpn, $spreadsheet);     
    $this->cetak_TRX($id_lhkpn, $spreadsheet);  
    $this->cetak_PROFIL_INDIV($id_lhkpn, $spreadsheet);  
    $this->cetak_PROFIL_BAHU($id_lhkpn, $spreadsheet);  
    $this->cetak_Lamp1($id_lhkpn, $spreadsheet); 
    $this->cetak_Lamp2($id_lhkpn, $spreadsheet); 
    $this->cetak_CL($id_lhkpn, $spreadsheet); 

    // hide sheet
    $sheet_name_arr = ['I','II','III','IV','V','VI','VII','VIII','IX','STR'];
    foreach($sheet_name_arr as $sheet_name){
      $spreadsheet->getSheetByName($sheet_name)->setSheetState('veryHidden');
    }

    // --- end excel content --- //

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    //setup file meta
    $filename = 'kkp_'.$id_lhkpn.'_'.date('dmyGi').'.xlsx';
    
    header('Content-Type: application/vnd.ms-excel');
    
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // ------ save and export to folder download

    $writer = new Xlsx($spreadsheet);
    // ob_end_clean();
    $writer->save('php://output');
    exit;
    // $writer->save('./download/kkp/test.xlsx');

    // echo "Excel is generated";
  }

  public function cetak_profil($id_lhkpn = 0, $id_audit = 0, $spreadsheet)
  {
    $spreadsheet->setActiveSheetIndex(4);

    //prepare data model
    // $data_keluarga = $this->Kkp_model->get_biodata_pn($id_lhkpn);
    $couples = [];
    $childs = [];
    $childs_n = [];
    $lainnya = [];

    $jabatan_lain = "";
    if (count($this->riwayat_lain) > 0) {
      foreach ($this->riwayat_lain as $rl) {
        $jabatan_lain .= ".".$rl->NAMA_JABATAN." (".$rl->INST_NAMA.")\n";
      }
    }
    else {
      $jabatan_lain = "-";
    }

    foreach ($this->data_pn as $k) {
      // status hubungan 1: istri, 2:suami, 3: anak
      $person = new stdClass();
      $person->nama = $k->K_NAMA;
      $person->status_hubungan = $k->K_STATUS_HUBUNGAN;
      $person->jenis_kelamin = $k->K_JENIS_KELAMIN;
      $person->pekerjaan = $k->K_PEKERJAAN;
      $person->tempat_lahir = $k->K_TEMPAT_LAHIR;
      $person->tanggal_lahir = $k->K_TANGGAL_LAHIR;

      if ($k->K_HUBUNGAN == 1 || $k->K_HUBUNGAN == 2) {
        $couples[] = $person;
      }
      else if ($k->K_HUBUNGAN == 3) {
        $childs[] = $person;
      }
      else if($k->K_HUBUNGAN == 4) {
        $childs_n[] = $person;
      }
      else {
        $lainnya[] = $person;
      }
    }

    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('PROFIL');

    //Header profil
    $spreadsheet->getActiveSheet()
      ->setCellValue('A2', 'BIODATA')
      ->mergeCells('A2:O2');

    $this->style_table_header('A2', '00008080', $spreadsheet);

    // process jenis Kelamin
    $jenis_kelamin_pn = '-';
    switch ($this->data_pn[0]->JENIS_KELAMIN) {
      case '1':
        $jenis_kelamin_pn = 'Laki-laki';
        break;

      case '2':
        $jenis_kelamin_pn = 'Perempuan';
        break;

      default:
        $jenis_kelamin_pn = '-';
        break;
    }

    // start informasi profil B4
    $labels = array(
      'Jenis Laporan' => $this->var_is_null($this->data_pn) ? "-" : $this->data_pn[0]->JENIS_LAPORAN == 4 ? 'Periodik' : ($this->data_pn[0]->JENIS_LAPORAN == 6 ? 'Dummy' : 'Khusus'),
      'Tahun Lapor' => $this->var_is_null($this->data_pn) ? "-" : strval(date('Y', strtotime($this->data_pn[0]->tgl_lapor))),
      'Tgl Lapor' => $this->var_is_null($this->data_pn) ? "-" : date('d M Y', strtotime($this->data_pn[0]->tgl_lapor)),
      'Nama' => $this->cek_is_null($this->data_pn[0]->NAMA_LENGKAP),
      'Jenis Kelamin' => $jenis_kelamin_pn,
      'NIK' => "'". $this->cek_is_null($this->data_pn[0]->NIK),
      'NIP' => "'".$this->cek_is_null($this->data_pn[0]->NIP_NRP),
      'NHK' => "'".$this->cek_is_null($this->data_pn[0]->NHK),
      'No. KK' => "'".$this->var_is_null($this->data_pn) ? "-" : ($this->data_pn[0]->NO_KK = '' ? '-' : $this->data_pn[0]->NO_KK),
      'No. NPWP' => "'".$this->cek_is_null($this->data_pn[0]->NPWP),
      'Tempat/Tgl Lahir' => $this->var_is_null($this->data_pn) ? "-" : $this->data_pn[0]->TEMPAT_LAHIR.' / '.date('d-M-Y',strtotime($this->data_pn[0]->TANGGAL_LAHIR)),
      'Agama' => $this->cek_is_null($this->data_pn[0]->AGAMA),
      'Alamat Rumah' => $this->cek_is_null($this->data_pn[0]->ALAMAT_RUMAH),
      'Desa/Kelurahan' => $this->cek_is_null($this->data_pn[0]->KELURAHAN),
      'Kecamatan' => $this->cek_is_null($this->data_pn[0]->KECAMATAN),
      'Kabupaten/Kota' => $this->cek_is_null($this->data_pn[0]->KABKOT),
      'Nomor HP' => $this->cek_is_null($this->data_pn[0]->HP),
      'Email' => $this->cek_is_null($this->data_pn[0]->EMAIL_PRIBADI),
      'Jabatan Saat Ini' => $this->cek_is_null($this->data_pn[0]->NAMA_JABATAN).' / '.$this->cek_is_null($this->data_pn[0]->UK_NAMA),
      'Instansi Saat Ini' => $this->cek_is_null($this->data_pn[0]->INST_NAMA),
      'Jabatan Lainnya' => $jabatan_lain, //TODO: jabatan lainnya
      'Alamat Kantor' => $this->cek_is_null($this->data_pn[0]->ALAMAT_KANTOR),
      'Nama Istri/Suami' => count($couples) ? $couples[0]->nama : '-',
      'Tempat/Tanggal Lahir' => count($couples) ? $couples[0]->tempat_lahir.' / '.date('d-M-Y',strtotime($couples[0]->tanggal_lahir)) : '-',
      'Pekerjaan Istri/Suami' => count($couples) ? $this->cek_is_null($couples[0]->pekerjaan) : '-',
    );

    $startCol = 4;
    foreach ($labels as $key => $value) {
      # code...
      $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, $startCol, $key);
      $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, $startCol, ':');
      $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4, $startCol, $value);
      $startCol++;
    }

    // khusus untuk kolom anak
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, $startCol, 'Anak Tanggungan');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, $startCol, ':');
    foreach ($childs as $c) {
      # code...
      $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,$startCol, $c->nama.' / '.$c->tempat_lahir.', '.date('d-M-Y',strtotime($c->tanggal_lahir)).' / '.$c->pekerjaan.' / '.$c->jenis_kelamin);
      $startCol++;
    }
    //anak bukan tanggungan
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, $startCol, 'Anak Bukan Tanggungan');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, $startCol, ':');
    if (count($childs_n) < 0) {
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4, $startCol, '-');
        $startCol++;
    }
    else {
      foreach ($childs_n as $c) {
        # code...
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,$startCol, $c->nama.' / '.$c->tempat_lahir.', '.date('d-M-Y',strtotime($c->tanggal_lahir)).' / '.$c->pekerjaan.' / '.$c->jenis_kelamin);
        $startCol++;
      }
    }

    // lainnya
    /*
    $startCol++;
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, $startCol, 'Lainnya');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, $startCol, ':');
    if (count($lainnya) < 0) {
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4, $startCol, '-');
        $startCol++;
    }
    else {
      foreach ($lainnya as $c) {
        # code...
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,$startCol, $c->nama.' / '.$c->tempat_lahir.', '.date('d-M-Y',strtotime($c->tanggal_lahir)).' / '.$c->pekerjaan.' / '.$c->jenis_kelamin);
        $startCol++;
      }
    }
   */
    // 'Anak Tanggungan' => $childs,
    // 'Anak Bukan Tanggungan' => 0,
    // 'Lainnya' => 0

    // field pemeriksa
    /*
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(12, 27, 'Pemeriksa 1');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(12, 28, 'Pemeriksa 2');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(12, 29, 'Tgl Klarifikasi');

    //warna pemeriksa
    $spreadsheet->getActiveSheet()->getStyle('M27:O29')
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle('M27:O29')
      ->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
    $spreadsheet->getActiveSheet()->getStyle('M27:O29')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    */

    // line pembatas
    $spreadsheet->getActiveSheet()->mergeCells('A33:O33');
    $this->style_table_header('A33', '00008080', $spreadsheet);

    $join = [
      ['table' => 'm_unit_kerja_eaudit', 'on' => 'T_LHKPN_AUDIT.id_uk_eaudit  = m_unit_kerja_eaudit.id_uk_eaudit AND m_unit_kerja_eaudit.IS_ACTIVE = "1"'],
    ];

    $where = [
      'T_LHKPN_AUDIT.id_audit' => $id_audit
    ];
    $data_audit = $this->mglobal->get_data_all("T_LHKPN_AUDIT", $join, $where)[0]; 
    $nama_pic = $this->get_nama_pic($data_audit->id_pic);
    $periode_periksa = date('d-M-Y', strtotime($data_audit->periode_pemeriksaan_awal)).' s.d. '.date('d-M-Y', strtotime($data_audit->periode_pemeriksaan_akhir));

    $tgl_klarif = null;
    if($data_audit->jenis_pemeriksaan == 0){ //terbuka 
        if($this->data_pn[0]->TGL_KLARIFIKASI != NULL){
          $tgl_klarif = date('d-M-Y', strtotime($this->data_pn[0]->TGL_KLARIFIKASI));
        }
    }

    $tgl_st = null;
    if($data_audit->tgl_mulai_periksa != null){
       $tgl_st = date('d-M-Y', strtotime($data_audit->tgl_mulai_periksa));
    }

    // field informasi pemeriksaan
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, 35, 'Surat Tugas');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 35, ':');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4, 35, $this->cek_is_null($data_audit->nomor_surat_tugas));
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, 36, 'PIC');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 36, ':');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4, 36, $this->cek_is_null($nama_pic));
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, 37, 'Periode Pemeriksaan');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 37, ':');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4, 37, $this->cek_is_null($periode_periksa));
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, 38, 'Latar Belakang');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 38, ':');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, 39, 'Dasar Penugasan');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 39, ':');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4, 39, $this->cek_is_null($data_audit->NAMA_UK_EAUDIT));

    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(10, 35, 'Tanggal ST');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(11, 35, ':');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(12, 35, $this->cek_is_null($tgl_st));

    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(10, 36, 'Tanggal Klarifikasi');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(11, 36, ':');
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(12, 36, $this->cek_is_null($tgl_klarif));

    // line pembatas
    $spreadsheet->getActiveSheet()->mergeCells('A41:O41');
    $this->style_table_header('A41', '00008080', $spreadsheet);

    $spreadsheet->getActiveSheet()->getStyle('D4:D50')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    // width
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(21);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(2);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(13);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(2);
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(6);
    $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(26);
    $spreadsheet->getActiveSheet()->getRowDimension('33')->setRowHeight(7.5);
    $spreadsheet->getActiveSheet()->getRowDimension('41')->setRowHeight(7.5);
  }

  private function get_nama_pic($id_pic) {
    $this->db->select('t_user.NAMA');
    $this->db->from('t_user');
    $this->db->where('id_user =', $id_pic);
    $query = $this->db->get();
    $res = $query->result();
    if (!empty($res)) {
      return $res[0]->NAMA;
    } else {
      return '-';
    }
    
  }

  public function cetak_ringkasan_harta($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(5);
    $spreadsheet->getActiveSheet()->setTitle('RH');

    //set header title
    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'RINGKASAN HARTA');

    // setup
    $startRow = 7;  //start row sama pada sheet  I-IX
    //count all data for positioning
    $count_lhkpn = [];
    $count_lhkpn[] = count($this->data_tanah_bangunan);
    $count_lhkpn[] = count($this->data_harta_bergerak);
    $count_lhkpn[] = count($this->data_harta_bergerak_lainnya);
    $count_lhkpn[] = count($this->data_surat_berharga);
    $count_lhkpn[] = count($this->data_kas);
    $count_lhkpn[] = count($this->data_harta_lainnya);
    // $count_lhkpn[] = count($this->Kkp_model->get_harta_lainnya($id_lhkpn));
    $count_lhkpn[] = count($this->data_hutang);
    $count_lhkpn[] = count($this->data_penerimaan_tunai);
    $count_lhkpn[] = count($this->data_pengeluaran_tunai);
    //
    // echo "<pre>";
    // print_r(json_encode($count_lhkpn));
    // echo "</pre>";
    // die();
    //set header
    $spreadsheet->getActiveSheet()
      ->setCellValue('A4', 'No.')
      ->mergeCells('A4:A6')
      ->setCellValue('B4', 'Jenis Harta')
      ->mergeCells('B4:C6')
      ->setCellValue('D4', 'Menurut PN')
      ->mergeCells('D4:E4')
      ->setCellValue('D5', '=PROFIL!D4')
      ->mergeCells('D5:E5')
      ->setCellValue('D6', 'Qty')
      ->setCellValue('E6', '=PROFIL!D6');

    $this->style_table_header('A4:E6', $this->color_green , $spreadsheet);


    $spreadsheet->getActiveSheet()
      ->setCellValue('A7', 'A')
      ->setCellValue('B7', 'Harta Tidak Bergerak (Tanah dan/atau Bangunan)')
      ->setCellValue('B8', 'A. 1')
      ->setCellValue('C8', 'Tanah Bangunan')
      ->setCellValue('D8', $count_lhkpn[0])
      ->setCellValue('E8', '=I!L'.($startRow + $count_lhkpn[0]));

    $spreadsheet->getActiveSheet()
      ->setCellValue('A9', 'B')
      ->setCellValue('B9', 'Harta Bergerak (Alat Transportasi dan Mesin)')
      ->setCellValue('B10', 'B. 1')
      ->setCellValue('C10', 'Mobil')
      ->setCellValue('D10','=COUNTIF(II!B7:B'.($startRow + $count_lhkpn[1]).', "MOBIL")')
      ->setCellValue('E10','=SUMPRODUCT(II!N7:N'.($startRow + $count_lhkpn[1]).'*(II!B7:B'.($startRow + $count_lhkpn[1]).'="MOBIL"))')
      ->setCellValue('B11', 'B. 2')
      ->setCellValue('C11', 'Motor')
      ->setCellValue('D11','=COUNTIF(II!B7:B'.($startRow + $count_lhkpn[1]).', "MOTOR")')
      ->setCellValue('E11','=SUMPRODUCT(II!N7:N'.($startRow + $count_lhkpn[1]).'*(II!B7:B'.($startRow + $count_lhkpn[1]).'="MOTOR"))')
      ->setCellValue('B12', 'B. 3')
      ->setCellValue('C12', 'Kapal Laut/Perahu')
      ->setCellValue('D12','=COUNTIF(II!B7:B'.($startRow + $count_lhkpn[1]).', "KAPAL LAUT/PERAHU")')
      ->setCellValue('E12','=SUMPRODUCT(II!N7:N'.($startRow + $count_lhkpn[1]).'*(II!B7:B'.($startRow + $count_lhkpn[1]).'="KAPAL LAUT/PERAHU"))')
      ->setCellValue('B13', 'B. 4')
      ->setCellValue('C13', 'Pesawat Terbang')
      ->setCellValue('D13','=COUNTIF(II!B7:B'.($startRow + $count_lhkpn[1]).', "PESAWAT TERBANG")')
      ->setCellValue('E13','=SUMPRODUCT(II!N7:N'.($startRow + $count_lhkpn[1]).'*(II!B7:B'.($startRow + $count_lhkpn[1]).'="PESAWAT TERBANG"))')
      ->setCellValue('B14', 'B. 5')
      ->setCellValue('C14', 'Lainnya')
      ->setCellValue('D14', '=COUNTIF(II!B7:B'.($startRow + $count_lhkpn[1]).', "LAINNYA")')
      ->setCellValue('E14','=SUMPRODUCT(II!N7:N'.($startRow + $count_lhkpn[1]).'*(II!B7:B'.($startRow + $count_lhkpn[1]).'="LAINNYA"))');

      $spreadsheet->getActiveSheet()
        ->setCellValue('A15', 'C')
        ->setCellValue('B15', 'Harta Bergerak Lainnya')
        ->setCellValue('B16', 'C. 1')
        ->setCellValue('C16', 'Perabotan Rumah Tangga')
        ->setCellValue('D16', '=COUNTIF(III!B7:B'.($startRow + $count_lhkpn[2]).', "PERABOTAN RUMAH TANGGA")')
        ->setCellValue('E16', '=SUMPRODUCT(III!I7:I'.($startRow + $count_lhkpn[2]).'*(III!B7:B'.($startRow + $count_lhkpn[2]).'="PERABOTAN RUMAH TANGGA"))')
        ->setCellValue('B17', 'C. 2')
        ->setCellValue('C17', 'Barang Elektronik')
        ->setCellValue('D17', '=COUNTIF(III!B7:B'.($startRow + $count_lhkpn[2]).', "BARANG ELEKTRONIK")')
        ->setCellValue('E17', '=SUMPRODUCT(III!I7:I'.($startRow + $count_lhkpn[2]).'*(III!B7:B'.($startRow + $count_lhkpn[2]).'="BARANG ELEKTRONIK"))')
        ->setCellValue('B18', 'C. 3')
        ->setCellValue('C18', 'Perhiasan & Logam/Batu Mulia')
        ->setCellValue('D18', '=COUNTIF(III!B7:B'.($startRow + $count_lhkpn[2]).', "PERHIASAN & LOGAM/BATU MULIA")')
        ->setCellValue('E18', '=SUMPRODUCT(III!I7:I'.($startRow + $count_lhkpn[2]).'*(III!B7:B'.($startRow + $count_lhkpn[2]).'="PERHIASAN & LOGAM/BATU MULIA"))')
        ->setCellValue('B19', 'C. 4')
        ->setCellValue('C19', 'Barang Seni/Antik')
        ->setCellValue('D19', '=COUNTIF(III!B7:B'.($startRow + $count_lhkpn[2]).', "BARANG SENI/ANTIK")')
        ->setCellValue('E19', '=SUMPRODUCT(III!I7:I'.($startRow + $count_lhkpn[2]).'*(III!B7:B'.($startRow + $count_lhkpn[2]).'="BARANG SENI/ANTIK"))')
        ->setCellValue('B20', 'C. 5')
        ->setCellValue('C20', 'Persediaan')
        ->setCellValue('D20', '=COUNTIF(III!B7:B'.($startRow + $count_lhkpn[2]).', "PERSEDIAAN")')
        ->setCellValue('E20', '=SUMPRODUCT(III!I7:I'.($startRow + $count_lhkpn[2]).'*(III!B7:B'.($startRow + $count_lhkpn[2]).'="PERSEDIAAN"))')
        ->setCellValue('B21', 'C. 6')
        ->setCellValue('C21', 'Harta Bergerak Lainnya')
        ->setCellValue('D21', '=COUNTIF(III!B7:B'.($startRow + $count_lhkpn[2]).', "HARTA BERGERAK LAINNYA")')
        ->setCellValue('E21', '=SUMPRODUCT(III!I7:I'.($startRow + $count_lhkpn[2]).'*(III!B7:B'.($startRow + $count_lhkpn[2]).'="HARTA BERGERAK LAINNYA"))');

      $spreadsheet->getActiveSheet()
        ->setCellValue('A22', 'D')
        ->setCellValue('B22', 'Surat Berharga')
        ->setCellValue('B23', 'D. 1')
        ->setCellValue('C23', 'Efek Yang Diperdagangkan Di Bursa (Listing)')
        ->setCellValue('D23', '=COUNTIF(IV!B7:B'.($startRow + $count_lhkpn[3]).', "EFEK YANG DIPERDAGANGKAN DI BURSA (LISTING)")')
        ->setCellValue('E23', '=SUMPRODUCT(IV!J7:J'.($startRow + $count_lhkpn[3]).'*(IV!B7:B'.($startRow + $count_lhkpn[3]).'="EFEK YANG DIPERDAGANGKAN DI BURSA (LISTING)"))')
        ->setCellValue('B24', 'D. 2')
        ->setCellValue('C24', 'Kepemilikan/Penyertaan Di Perusahaan Non-Listing')
        ->setCellValue('D24', '=COUNTIF(IV!B7:B'.($startRow + $count_lhkpn[3]).', "KEPEMILIKAN/PENYERTAAN DI PERUSAHAAN NON-LISTING")')
        ->setCellValue('E24', '=SUMPRODUCT(IV!J7:J'.($startRow + $count_lhkpn[3]).'*(IV!B7:B'.($startRow + $count_lhkpn[3]).'="KEPEMILIKAN/PENYERTAAN DI PERUSAHAAN NON-LISTING"))');

      $spreadsheet->getActiveSheet()
        ->setCellValue('A25', 'E')
        ->setCellValue('B25', 'Kas dan Setara Kas')
        ->setCellValue('B26', 'E. 1')
        ->setCellValue('C26', 'Uang Tunai')
        ->setCellValue('D26', '=COUNTIF(V!B7:B'.($startRow + $count_lhkpn[4]).', "UANG TUNAI")')
        ->setCellValue('E26', '=SUMPRODUCT(V!J7:J'.($startRow + $count_lhkpn[4]).'*(V!B7:B'.($startRow + $count_lhkpn[4]).'="UANG TUNAI"))')
        ->setCellValue('B27', 'E. 2')
        ->setCellValue('C27', 'Deposito')
        ->setCellValue('D27', '=COUNTIF(V!B7:B'.($startRow + $count_lhkpn[4]).', "DEPOSITO")')
        ->setCellValue('E27', '=SUMPRODUCT(V!J7:J'.($startRow + $count_lhkpn[4]).'*(V!B7:B'.($startRow + $count_lhkpn[4]).'="DEPOSITO"))')
        ->setCellValue('B28', 'E. 3')
        ->setCellValue('C28', 'Giro')
        ->setCellValue('D28', '=COUNTIF(V!B7:B'.($startRow + $count_lhkpn[4]).', "GIRO")')
        ->setCellValue('E28', '=SUMPRODUCT(V!J7:J'.($startRow + $count_lhkpn[4]).'*(V!B7:B'.($startRow + $count_lhkpn[4]).'="GIRO"))')
        ->setCellValue('B29', 'E. 4')
        ->setCellValue('C29', 'Tabungan')
        ->setCellValue('D29', '=COUNTIF(V!B7:B'.($startRow + $count_lhkpn[4]).', "TABUNGAN")')
        ->setCellValue('E29', '=SUMPRODUCT(V!J7:J'.($startRow + $count_lhkpn[4]).'*(V!B7:B'.($startRow + $count_lhkpn[4]).'="TABUNGAN"))')
        ->setCellValue('B30', 'E. 5')
        ->setCellValue('C30', 'Lainnya')
        ->setCellValue('D30', '=COUNTIF(V!B7:B'.($startRow + $count_lhkpn[4]).', "LAINNYA")')
        ->setCellValue('E30', '=SUMPRODUCT(V!J7:J'.($startRow + $count_lhkpn[4]).'*(V!B7:B'.($startRow + $count_lhkpn[4]).'="LAINNYA"))');

      $spreadsheet->getActiveSheet()
        ->setCellValue('A31', 'F')
        ->setCellValue('B31', 'Harta Lainnya')
        ->setCellValue('B32', 'F. 1')
        ->setCellValue('C32', 'Piutang')
        ->setCellValue('D32', '=COUNTIF(VI!B7:B'.($startRow + $count_lhkpn[5]).', "PIUTANG")')
        ->setCellValue('E32', '=SUMPRODUCT(VI!G7:G'.($startRow + $count_lhkpn[5]).'*(VI!B7:B'.($startRow + $count_lhkpn[5]).'="PIUTANG"))')
        ->setCellValue('B33', 'F. 2')
        ->setCellValue('C33', 'Kerjasama Usaha Yang Tidak Berbadan Hukum')
        ->setCellValue('D33', '=COUNTIF(VI!B7:B'.($startRow + $count_lhkpn[5]).', "KERJASAMA USAHA YANG TIDAK BERBADAN HUKUM")')
        ->setCellValue('E33', '=SUMPRODUCT(VI!G7:G'.($startRow + $count_lhkpn[5]).'*(VI!B7:B'.($startRow + $count_lhkpn[5]).'="KERJASAMA USAHA YANG TIDAK BERBADAN HUKUM"))')
        ->setCellValue('B34', 'F. 3')
        ->setCellValue('C34', 'Hak Kekayaan Intelektual')
        ->setCellValue('D34', '=COUNTIF(VI!B7:B'.($startRow + $count_lhkpn[5]).', "HAK KEKAYAAN INTELEKTUAL")')
        ->setCellValue('E34', '=SUMPRODUCT(VI!G7:G'.($startRow + $count_lhkpn[5]).'*(VI!B7:B'.($startRow + $count_lhkpn[5]).'="HAK KEKAYAAN INTELEKTUAL"))')
        ->setCellValue('B35', 'F. 4')
        ->setCellValue('C35', 'Dana Pensiun/Tabungan Hari Tua')
        ->setCellValue('D35', '=COUNTIF(VI!B7:B'.($startRow + $count_lhkpn[5]).', "DANA PENSIUN/TABUNGAN HARI TUA")')
        ->setCellValue('E35', '=SUMPRODUCT(VI!G7:G'.($startRow + $count_lhkpn[5]).'*(VI!B7:B'.($startRow + $count_lhkpn[5]).'="DANA PENSIUN/TABUNGAN HARI TUA"))')
        ->setCellValue('B36', 'F. 5')
        ->setCellValue('C36', 'Unitlink')
        ->setCellValue('D36', '=COUNTIF(VI!B7:B'.($startRow + $count_lhkpn[5]).', "UNITLINK")')
        ->setCellValue('E36', '=SUMPRODUCT(VI!G7:G'.($startRow + $count_lhkpn[5]).'*(VI!B7:B'.($startRow + $count_lhkpn[5]).'="UNITLINK"))')
        ->setCellValue('B37', 'F. 6')
        ->setCellValue('C37', 'Sewa Jangka Panjang Dibayar Dimuka')
        ->setCellValue('D37', '=COUNTIF(VI!B7:B'.($startRow + $count_lhkpn[5]).', "SEWA JANGKA PANJANG DIBAYAR DIMUKA")')
        ->setCellValue('E37', '=SUMPRODUCT(VI!G7:G'.($startRow + $count_lhkpn[5]).'*(VI!B7:B'.($startRow + $count_lhkpn[5]).'="SEWA JANGKA PANJANG DIBAYAR DIMUKA"))')
        ->setCellValue('B38', 'F. 7')
        ->setCellValue('C38', 'Hak Pengelolaan/Pengusahaan Yang Dimiliki Perorangan')
        ->setCellValue('D38', '=COUNTIF(VI!B7:B'.($startRow + $count_lhkpn[5]).', "HAK PENGELOLAAN/PENGUSAHAAN YANG DIMILIKI PERORANGAN")')
        ->setCellValue('E38', '=SUMPRODUCT(VI!G7:G'.($startRow + $count_lhkpn[5]).'*(VI!B7:B'.($startRow + $count_lhkpn[5]).'="HAK PENGELOLAAN/PENGUSAHAAN YANG DIMILIKI PERORANGAN"))')
        ->setCellValue('B39', 'F. 8')
        ->setCellValue('C39', 'Lainnya')
        ->setCellValue('D39', '=COUNTIF(VI!B7:B'.($startRow + $count_lhkpn[5]).', "LAINNYA")')
        ->setCellValue('E39', '=SUMPRODUCT(VI!G7:G'.($startRow + $count_lhkpn[5]).'*(VI!B7:B'.($startRow + $count_lhkpn[5]).'="LAINNYA"))')
        ->setCellValue('A40', 'TOTAL')->mergeCells('A40:C40')->setCellValue('E40', '=SUM(E7:E39)')
        ->setCellValue('A41', 'G')
        ->setCellValue('B41', 'HUTANG')->setCellValue('E41', "='VII'!G".(count($this->data_hutang)+7))
        ->setCellValue('A42', 'TOTAL HARTA KEKAYAAN')->mergeCells('A42:C42')->setCellValue('E42', "=E40-E41");

      $spreadsheet->getActiveSheet()->getStyle('E7:E50')->getNumberFormat()
        ->setFormatCode('#,##0');
      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(4);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(14);
  }

  public function cetak_riwayat_jabatan($id_lhkpn, $spreadsheet)
  {

    // $riwayat = $this->Kkp_model->get_riwayat_pelaporan($id_lhkpn);
    // echo "<pre>";
    // echo "------ jabatan utama ------<br>";
    // print_r($this->riwayat);
    // echo "------ jabatan lain -------<br>";
    // print_r($this->riwayat_lain);
    // echo "</pre>";
    // die();


    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(6);

    $spreadsheet->getActiveSheet()->setTitle('RJ');

    //set title
    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'RIWAYAT PELAPORAN LHKPN');

    // set table header
    $spreadsheet->getActiveSheet()
      ->setCellValue('A4', 'No')
      ->setCellValue('B4', 'Jabatan')
      ->setCellValue('C4', 'Jabatan Lainnya/Rangkap')
      ->setCellValue('D4', 'Lembaga')
      ->setCellValue('E4', 'Jenis Laporan')
      ->setCellValue('F4', 'Tahun Lapor')
      ->setCellValue('G4', 'Tanggal Lapor')
      ->setCellValue('H4', 'Analisis Kepatuhan');

    $this->style_table_header('A4:H4', '00008080', $spreadsheet);
    // column width
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);

    $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(32);

    // TODO: tarik data riwayat jabatan
    $startRow = 5;
    $nomor = 1;
    foreach ($this->riwayat as $key => $value) {
      # code...
      $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $nomor++)
      ->setCellValue('B'.$startRow, $value->NAMA_JABATAN)
      // ->setCellValue('C'.$startRow, '-')  // todo cari info cara dapet jabatan lainnya
      ->setCellValue('D'.$startRow, $value->INST_NAMA)
      ->setCellValue('E'.$startRow, $value->JENIS_LAPORAN == 4 ? 'Periodik' : 'Khusus')
      ->setCellValue('F'.$startRow, date('Y',strtotime($value->tgl_lapor)))
      ->setCellValue('G'.$startRow, date('d M Y',strtotime($value->tgl_lapor)))
      ->setCellValue('H'.$startRow, '');

      $jabatan_lain = "";
      foreach ($this->riwayat_lain as $rl) {
        $jabatan_lain .= ".".$rl->NAMA_JABATAN." (".$rl->INST_NAMA.")\n";
      }
      $spreadsheet->getActiveSheet()
        ->setCellValue('C'.$startRow, "".$jabatan_lain);

      $startRow++;
    }

    $startRow = 6;
    $nomor = 2;
    foreach ($this->riwayat_sebelumnya as $key => $value) {
      # code...
      $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $nomor++)
      ->setCellValue('B'.$startRow, $value->DESKRIPSI_JABATAN)
      // ->setCellValue('C'.$startRow, '-')  // todo cari info cara dapet jabatan lainnya
      ->setCellValue('D'.$startRow, $value->NAMA_LEMBAGA)
      ->setCellValue('E'.$startRow, $value->JENIS_LAPORAN == 4 ? 'Periodik' : 'Khusus')
      ->setCellValue('F'.$startRow, date('Y',strtotime($value->tgl_lapor)))
      ->setCellValue('G'.$startRow, date('d M Y',strtotime($value->tgl_lapor)))
      ->setCellValue('H'.$startRow, '');

      $jabatan_lain = "";
      foreach ($this->riwayat_lain as $rl) {
        $jabatan_lain .= ".".$rl->NAMA_JABATAN." (".$rl->INST_NAMA.")\n";
      }
      $spreadsheet->getActiveSheet()
        ->setCellValue('C'.$startRow, "".$jabatan_lain);

      $startRow++;

    }
    
  }

  public function cetak_data_keluarga($id_lhkpn, $spreadsheet)
  {
    // -- instantiate sheet
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(7);

    $spreadsheet->getActiveSheet()->setTitle('DK');
    //set title
    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'DATA KELUARGA');

    // $data_keluarga = $this->Kkp_model->get_data_keluarga($id_lhkpn);

    //set header
    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A5')
      ->setCellValue('B4','Deskripsi')
      ->mergeCells('B4:B5')
      ->setCellValue('C4', $this->data_keluarga[0]->JENIS_LAPORAN == 4 ? 'Periodik' : 'Khusus')
      ->setCellValue('C5', date('d M Y', strtotime($this->data_keluarga[0]->tgl_lapor) ))
      ->setCellValue('D4','Keterangan')
      ->mergeCells('D4:D5');

    //get content data keluarga
    // ID HUBUNGAN --> 1 = Istri, 2 = Suami, 3 = Anak Tanggungan, 4 = Anak Bukan Tanggungan, 5 = Lainnya
    $kategori_hubungan = array();
    foreach ($this->data_keluarga as $key => $dk) {
      $kategori_hubungan[$dk->HUBUNGAN][] = $dk;
    }

    //put family information on cell
    $startRow = 6;
    foreach ($kategori_hubungan as $key => $kh) {
      // suami / istri
      switch ($key) {
        case 1:
          $title = 'Istri/Suami';
          break;
        case 2:
          $title = 'Istri/Suami';
          break;
        case 3:
          $title = 'Anak Tanggungan';
          break;
        case 4:
          $title = 'Anak Bukan Tanggungan';
          break;
        case 5:
          $title = 'Lainnya';
          break;
      }

      $spreadsheet->getActiveSheet()
        ->setCellValue('A'.$startRow, $title);
      $startRow++;
      foreach ($kh as $key => $k) {
        $spreadsheet->getActiveSheet()
          ->setCellValue('A'.$startRow, $key+1)
          ->setCellValue('B'.$startRow, 'Nama')
          ->setCellValue('C'.$startRow++, $k->NAMA)
          ->setCellValue('B'.$startRow, 'NIK')
          ->setCellValue('C'.$startRow++, $k->NIK)
          ->setCellValue('B'.$startRow, 'Tempat & Tanggal Lahir')
          ->setCellValue('C'.$startRow++, $k->TEMPAT_LAHIR.' / '.date('d M Y', strtotime($k->TANGGAL_LAHIR)))
          ->setCellValue('B'.$startRow, 'Jenis Kelamin')
          ->setCellValue('C'.$startRow++, $k->JENIS_KELAMIN)
          ->setCellValue('B'.$startRow, 'Pekerjaan')
          ->setCellValue('C'.$startRow++, $k->PEKERJAAN)
          ->setCellValue('B'.$startRow, 'No. Telepon')
          ->setCellValue('C'.$startRow++, $k->NOMOR_TELPON)
          ->setCellValue('B'.$startRow, 'Alamat Rumah')
          ->setCellValue('C'.$startRow++, $k->ALAMAT_RUMAH);
      }

      $startRow++;
    }

    //styling
    $this->style_table_header('A4:E5', '00008080', $spreadsheet);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(3.3);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(23);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(23);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(23);
  }

  public function cetak_surat($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(8);

    $spreadsheet->getActiveSheet()->setTitle('SURAT');

    //set title
    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'REKAPITULASI PERMINTAAN DATA');

    //set HEADER
    $spreadsheet->getActiveSheet()
      ->setCellValue('B4', 'NO')
      ->setCellValue('C4', 'KODE')
      ->setCellValue('D4', 'INSTANSI')
      ->setCellValue('E4', 'NAMA')
      ->setCellValue('F4', 'SURAT KELUAR')
      ->setCellValue('G4', 'TANGGAL')
      ->setCellValue('H4', 'SURAT BALASAN')
      ->setCellValue('I4', 'TANGGAL')
      ->setCellValue('J4', 'KETERANGAN');

    $this->style_table_header('B4:J4', '00000000', $spreadsheet);

    $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(2);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(26);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(23);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(9);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(25);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(9);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);
  }

  public function cetak_I($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(9);

    //
    $spreadsheet->getActiveSheet()->setTitle('I');

    // //set header title
    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'TANAH DAN BANGUNAN ');

    //set header
    // $data_tanah_bangunan = $this->Kkp_model->get_data_tanah_bangunan($id_lhkpn);

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A6')
      ->setCellValue('B4','Lokasi')
      ->mergeCells('B4:B6')
      ->setCellValue('C4','Luas (M2)')
      ->mergeCells('C4:D4')
      ->setCellValue('C5','Tanah')
      ->mergeCells('C5:C6')
      ->setCellValue('D5','Bangunan')
      ->mergeCells('D5:D6')
      ->setCellValue('E4','Kepemilikan')
      ->mergeCells('E4:I4')
      ->setCellValue('E5','Jenis Bukti')
      ->mergeCells('E5:E6')
      ->setCellValue('F5','Nomor Bukti')
      ->mergeCells('F5:F6')
      ->setCellValue('G5','Atas Nama')
      ->mergeCells('G5:G6')
      ->setCellValue('H5','Asal Usul Harta')
      ->mergeCells('H5:H6')
      ->setCellValue('I5','Pemanfaatan')
      ->mergeCells('I5:I6')
      ->setCellValue('J4','Tahun Perolehan')
      ->mergeCells('J4:J6')
      ->setCellValue('K4','Nilai Perolehan')
      ->mergeCells('K4:K6')
      ->setCellValue('L4','Menurut PN')
      ->setCellValue('L5', '=PROFIL!D4')
      ->setCellValue('L6', '=PROFIL!D6' );

    //add content here
    $startRow = 7;
    foreach ($this->data_tanah_bangunan as $key_dtb => $dtb) {
      $startCol = 1;
      $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow($startCol++, $startRow, $key_dtb+1);
      foreach ($dtb as $key => $d) {

        // set input untuk field atas nama
        if ($key == 'ATAS_NAMA') {
          if ($d == 1)
            $d = 'PN YANG BERSANGKUTAN';
          else if ($d == 2)
            $d = 'PASANGAN / ANAK';
          else if ( $d == 3 )
            $d = 'LAINNYA';
        }

        if ($key == 'ASAL_USUL') {
          if ($d == NULL)
            $d = '-';
          else
            $d = $this->__get_asal_usul_harta($d);
        }

        if ($key == 'jalan')
          $d = '=TRIM("'.$d.'")';

        if ($key == 'NILAI_PELAPORAN'){
            $d = (float) $d;
        }

        // disable tgl_lapor & Jenis laporan
        if ( ($key == 'tgl_lapor') || ($key == 'JENIS_LAPORAN') )
          $d = '';

        $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow($startCol, $startRow, $d);

        $startCol++;
      }
      $startRow++;
    }

    // print total
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, 'SUBTOTAL')
      ->mergeCells('A'.$startRow.':J'.$startRow);
    $last_row = $startRow-1;
    $spreadsheet->getActiveSheet()
      ->setCellValue('K'.$startRow, '=SUM(K7:K'.$last_row.')');
    $spreadsheet->getActiveSheet()
      ->setCellValue('L'.$startRow, '=SUM(L7:L'.$last_row.')');

    $this->style_table_header(
      'A'.$startRow.':L'.$startRow,
      '00ffff66',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    //styles
    $this->style_table_header('A4:L6', '00008080', $spreadsheet);
    // $this->set_col_numberFormat('J7', 'J'.(7+count($this->data_tanah_bangunan)), $spreadsheet);
    // $this->set_col_numberFormat('K7', 'K'.(7+count($this->data_tanah_bangunan)), $spreadsheet);
    $this->set_col_numberFormat('K7', 'K'.$startRow, $spreadsheet);
    $this->set_col_numberFormat('L7', 'L'.$startRow, $spreadsheet);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(18);

  }

  public function cetak_II($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(10);

    //
    $spreadsheet->getActiveSheet()->setTitle('II');

    // //set header title
    // $data_harta_bergerak = $this->Kkp_model->get_data_bergerak($id_lhkpn);

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'ALAT TRANSPORTASI DAN MESIN');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A6')
      ->setCellValue('B4','Uraian') // group header
      ->mergeCells('B4:F4')
      ->setCellValue('B5','Jenis')
      ->mergeCells('B5:B6')
      ->setCellValue('C5','Merek')
      ->mergeCells('C5:C6')
      ->setCellValue('D5','Tipe')
      ->mergeCells('D5:D6')
      ->setCellValue('E5','Tahun Pembuatan')
      ->mergeCells('E5:E6')
      ->setCellValue('F5','No Pol. / Registrasi')
      ->mergeCells('F5:F6')
      ->setCellValue('G4','Kepemilikan') // group header
      ->mergeCells('G4:K4')
      ->setCellValue('G5','Jenis Bukti')
      ->mergeCells('G5:G6')
      ->setCellValue('H5','Asal Usul Harta')
      ->mergeCells('H5:H6')
      ->setCellValue('I5','Atas Nama')
      ->mergeCells('I5:I6')
      ->setCellValue('J5','Pemanfaatan')
      ->mergeCells('J5:J6')
      ->setCellValue('K5','Tahun Perolehan')
      ->mergeCells('K5:K6')
      ->setCellValue('L5','Keterangan Lainnya')
      ->mergeCells('L5:L6')
      ->setCellValue('M4','Nilai Perolehan')
      ->mergeCells('M4:M6')
      ->setCellValue('N4','Menurut PN')
      ->setCellValue('N5', '="PROFIL"!D4')
      ->setCellValue('N6', "='PROFIL'!D6" );

      // list data bergerak
      $startRow = 7;
      foreach ($this->data_harta_bergerak as $i => $harta_bergerak) {

        //row number
        $startCol = 1;
        $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow($startCol++, $startRow, $i+1);
        foreach ($harta_bergerak as $prop => $hb) {

          // set input untuk field atas nama
          if ($prop == 'ATAS_NAMA') {
            if ($hb == 1)
              $hb = 'PN YANG BERSANGKUTAN';
            else if ($hb == 2)
              $hb = 'PASANGAN / ANAK';
            else if ( $hb == 3 )
              $hb = 'LAINNYA';
          }

          if ($prop == 'ASAL_USUL') {
            if ($hb == NULL)
              $hb = '-';
            else
              $hb = $this->__get_asal_usul_harta($hb);
          }

          if ($prop == 'NILAI_PELAPORAN'){
            $hb = (float) $hb;
          }

          // disable tgl_lapor & Jenis laporan
          if ( ($prop == 'tgl_lapor') || ($prop == 'JENIS_LAPORAN') || ($prop == 'ID_LHKPN'))
            $hb = '';

          $spreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow($startCol, $startRow, $hb);
          $startCol++;

        }
        $startRow++;

      }

      // TOTAL
      $spreadsheet->getActiveSheet()
        ->setCellValue('A'.$startRow, 'SUBTOTAL')
        ->mergeCells('A'.$startRow.':L'.$startRow);
      $last_row = $startRow-1;
      $spreadsheet->getActiveSheet()
        ->setCellValue('M'.$startRow, '=SUM(M7:M'.$last_row.')');
      $spreadsheet->getActiveSheet()
        ->setCellValue('N'.$startRow, '=SUM(N7:N'.$last_row.')');

      $this->style_table_header(
        'A'.$startRow.':N'.$startRow,
        '00ffff66',
        $spreadsheet,
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      //styles
      $this->style_table_header('A4:N6', '00008080', $spreadsheet);
      $this->set_col_numberFormat('M7', 'M'.(7+count($this->data_harta_bergerak)), $spreadsheet);
      $this->set_col_numberFormat('N7', 'N'.(7+count($this->data_harta_bergerak)), $spreadsheet);

      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(13);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(6);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(12);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(16);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(13);
      $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
      $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(11);
      $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);

  }

  public function cetak_III($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(11);

    //
    $spreadsheet->getActiveSheet()->setTitle('III');

    // //set header title
    // $data_harta_bergerak_lainnya = $this->Kkp_model->get_data_bergerak_lainnya($id_lhkpn);

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'HARTA BERGERAK LAINNYA');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A6')
      ->setCellValue('B4','Uraian') // group header
      ->mergeCells('B4:F4')
      ->setCellValue('B5','Jenis')
      ->mergeCells('B5:B6')
      ->setCellValue('C5','Jumlah')
      ->mergeCells('C5:C6')
      ->setCellValue('D5','Satuan')
      ->mergeCells('D5:D6')
      ->setCellValue('E5','Keterangan Lainnya')
      ->mergeCells('E5:E6')
      ->setCellValue('F5','Tahun Perolehan')
      ->mergeCells('F5:F6')
      ->setCellValue('G4','Asal Usul Harta')
      ->mergeCells('G4:G6')
      ->setCellValue('H4','Nilai Perolehan')
      ->mergeCells('H4:H6')
      ->setCellValue('I4','Menurut PN')
      ->setCellValue('I5', "='PROFIL'!D4")
      ->setCellValue('I6', "='PROFIL'!D6");

      // list data bergerak
      $startRow = 7;
      foreach ($this->data_harta_bergerak_lainnya as $i => $harta_bergerak_lainnya) {

        //row number
        $startCol = 1;
        $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow($startCol++, $startRow, $i+1);
        foreach ($harta_bergerak_lainnya as $prop => $hbl) {

          // set input untuk field atas nama
          if ($prop == 'ATAS_NAMA') {
            if ($hbl == 1)
              $hbl = 'PN YANG BERSANGKUTAN';
            else if ($hbl == 2)
              $hbl = 'PASANGAN / ANAK';
            else if ( $hbl == 3 )
              $hbl = 'LAINNYA';
          }

          if ($prop == 'ASAL_USUL') {
            if ($hbl == NULL)
              $hbl = '-';
            else
              $hbl = $this->__get_asal_usul_harta($hbl);
          }
          
          // disable tgl_lapor & Jenis laporan
          if ( ($prop == 'tgl_lapor') || ($prop == 'JENIS_LAPORAN') || ($prop == 'ID_LHKPN'))
            $hbl = '';

          $spreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow($startCol, $startRow, $hbl);
          $startCol++;

        }
        $startRow++;

      }

      // print total
      $spreadsheet->getActiveSheet()
        ->setCellValue('A'.$startRow, 'SUBTOTAL')
        ->mergeCells('A'.$startRow.':G'.$startRow);
      $last_row = $startRow-1;
      $spreadsheet->getActiveSheet()
        ->setCellValue('H'.$startRow, '=SUM(H7:H'.$last_row.')')
        ->setCellValue('I'.$startRow, '=SUM(I7:I'.$last_row.')');
      //
      $this->style_table_header(
        'A'.$startRow.':I'.$startRow,
        '00ffff66',
        $spreadsheet,
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      //styles
      $this->style_table_header('A4:I6', '00008080', $spreadsheet);
      $this->set_col_numberFormat('H7', 'H'.(7+count($this->data_harta_bergerak_lainnya)), $spreadsheet);
      $this->set_col_numberFormat('I7', 'I'.(7+count($this->data_harta_bergerak_lainnya)), $spreadsheet);

      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(28);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(9);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(16);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(16);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(16);

  }

  public function cetak_IV($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(12);
    //
    $spreadsheet->getActiveSheet()->setTitle('IV');

    // $data_surat_berharga = $this->Kkp_model->get_surat_berharga($id_lhkpn);

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'SURAT BERHARGA');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A6')
      ->setCellValue('B4','Uraian') // group header
      ->mergeCells('B4:F4')
      ->setCellValue('B5','Jenis')
      ->mergeCells('B5:B6')
      ->setCellValue('C5','Atas Nama')
      ->mergeCells('C5:C6')
      ->setCellValue('D5','Penerbit / Perusahaan')
      ->mergeCells('D5:D6')
      ->setCellValue('E5','Kustodian / Sekuritas')
      ->mergeCells('E5:E6')
      ->setCellValue('F5','Tahun Perolehan')
      ->mergeCells('F5:F6')
      ->setCellValue('G4','No. Rekening / ID Nasabah')
      ->mergeCells('G4:G6')
      ->setCellValue('H4','Asal Usul Harta')
      ->mergeCells('H4:H6')
      ->setCellValue('I4','Nilai Perolehan')
      ->mergeCells('I4:I6')
      ->setCellValue('J4','Menurut PN')
      ->setCellValue('J5', "='PROFIL'!D4")
      ->setCellValue('J6', "='PROFIL'!D6" );

      // list surat berharga
      $startRow = 7;
      foreach ($this->data_surat_berharga as $i => $surat_berharga) {

        //row number
        $startCol = 1;
        $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow($startCol++, $startRow, $i+1);
        foreach ($surat_berharga as $prop => $sb) {

          // set input untuk field atas nama
          if ($prop == 'ATAS_NAMA') {
            if ($sb == 1)
              $sb = 'PN YANG BERSANGKUTAN';
            else if ($sb == 2)
              $sb = 'PASANGAN / ANAK';
            else if ( $sb == 3 )
              $sb = 'LAINNYA';
          }

          if ($prop == 'ASAL_USUL') {
            if ($sb == NULL)
              $sb = '-';
            else
              $sb = $this->__get_asal_usul_harta($sb);
          }
          
          // disable tgl_lapor & Jenis laporan
          if ( ($prop == 'tgl_lapor') || ($prop == 'JENIS_LAPORAN') || ($prop == 'ID_LHKPN'))
            $sb = '';

          if ($prop == 'NILAI_PELAPORAN'){
            $sb = (float) $sb;
          }

          $spreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow($startCol, $startRow, $sb);
          $startCol++;

        }
        $startRow++;
      }

      // // print total
      $spreadsheet->getActiveSheet()
        ->setCellValue('A'.$startRow, 'SUBTOTAL')
        ->mergeCells('A'.$startRow.':H'.$startRow);
      $last_row = $startRow-1;
      $spreadsheet->getActiveSheet()
        ->setCellValue('I'.$startRow, '=SUM(I7:I'.$last_row.')')
        ->setCellValue('J'.$startRow, '=SUM(J7:J'.$last_row.')');
      //
      $this->style_table_header(
        'A'.$startRow.':J'.$startRow,
        '00ffff66',
        $spreadsheet,
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      //styles
      $this->style_table_header('A4:J6', '00008080', $spreadsheet);
      $this->set_col_numberFormat('I7', 'I'.(7+count($this->data_surat_berharga)), $spreadsheet);
      $this->set_col_numberFormat('J7', 'J'.(7+count($this->data_surat_berharga)), $spreadsheet);

      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(28);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(17);
  }

  public function cetak_V($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(13);
    //
    $spreadsheet->getActiveSheet()->setTitle('V');

    // $data_kas = $this->Kkp_model->get_data_kas($id_lhkpn);

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'KAS DAN SETARA KAS');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A6')
      ->setCellValue('B4','Uraian') // group header
      ->mergeCells('B4:D4')
      ->setCellValue('B5','Jenis')
      ->mergeCells('B5:B6')
      ->setCellValue('C5','Keterangan')
      ->mergeCells('C5:C6')
      ->setCellValue('D5','Nama Bank / Lembaga')
      ->mergeCells('D5:D6')
      ->setCellValue('E4','Info Rekening')
      ->mergeCells('E4:H4')
      ->setCellValue('E5','Nomor')
      ->mergeCells('E5:E6')
      ->setCellValue('F5','Atas Nama')
      ->mergeCells('F5:F6')
      ->setCellValue('G5','Keterangan')
      ->mergeCells('G5:G6')
      ->setCellValue('H5','Tahun Buka Rekening')
      ->mergeCells('H5:H6')
      ->setCellValue('I4','Asal Usul Harta')
      ->mergeCells('I4:I6')
      ->setCellValue('J4','Menurut PN')
      ->setCellValue('J5', "='PROFIL'!D4")
      ->setCellValue('J6', "='PROFIL'!D6" );

      $startRow = 7;
      foreach ($this->data_kas as $i => $kas) {

        //row number
        $startCol = 1;
        $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow($startCol++, $startRow, $i+1);

        foreach ($kas as $prop => $k) {
          // set input untuk field atas nama
          if ($prop == 'ATAS_NAMA_REKENING') {
            if ($k == '1')
            $k = 'PN YANG BERSANGKUTAN';
            else if ($k == '2')
            $k = 'PASANGAN / ANAK';
            else if ( $k == '3' )
            $k = 'LAINNYA';
          }

//           echo "<pre>";
//           echo $prop.' - '.$k.'<br>';
//           echo "</pre>";

          if ($prop == 'ASAL_USUL') {
            if ($k == NULL)
              $k = '-';
            else
              $k = $this->__get_asal_usul_harta($k);
          }
          
          // disable tgl_lapor & Jenis laporan
          if ( ($prop == 'tgl_lapor') || ($prop == 'JENIS_LAPORAN') || ($prop == 'ID_LHKPN')){
            $k = '';
          }

          if ($prop == 'NAMA_BANK' || $prop == 'NOMOR_REKENING' ){
//              decrypt
            if (strlen(trim($k)) >= 32){
                $k = encrypt_username($k,'d');
            }
          }

          $spreadsheet->getActiveSheet()
          ->setCellValueByColumnAndRow($startCol, $startRow, $k);
          $startCol++;

        }

//         die();
        $startRow++;
      }

      // print total
      $spreadsheet->getActiveSheet()
        ->setCellValue('A'.$startRow, 'SUBTOTAL')
        ->mergeCells('A'.$startRow.':I'.$startRow);
      $last_row = $startRow-1;
      $spreadsheet->getActiveSheet()
        ->setCellValue('J'.$startRow, '=SUM(J7:J'.$last_row.')');
      //
      $this->style_table_header(
        'A'.$startRow.':J'.$startRow,
        '00ffff66',
        $spreadsheet,
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      //styles
      $this->style_table_header('A4:J6', '00008080', $spreadsheet);
      $this->set_col_numberFormat('J7', 'J'.(7+count($this->data_kas)), $spreadsheet);

      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(14);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(22);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(22);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(11);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(17);
  }

  public function cetak_VI($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(14);
    //
    $spreadsheet->getActiveSheet()->setTitle('VI');

    // $data_harta_lainnya = $this->Kkp_model->get_harta_lainnya($id_lhkpn);

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'HARTA LAINNYA');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A6')
      ->setCellValue('B4','Uraian') // group header
      ->mergeCells('B4:D4')
      ->setCellValue('B5','Jenis')
      ->mergeCells('B5:B6')
      ->setCellValue('C5','Keterangan')
      ->mergeCells('C5:C6')
      ->setCellValue('D5','Tahun Perolehan')
      ->mergeCells('D5:D6')
      ->setCellValue('E4','Asal Usul Harta')
      ->mergeCells('E4:E6')
      ->setCellValue('F4','Nilai Awal Hutang')
      ->mergeCells('F4:F6')
      ->setCellValue('G4','Menurut PN')
      ->setCellValue('G5', '=PROFIL!D4')
      ->setCellValue('G6', '=PROFIL!D6' );

    //print list harta lainnya
    $startRow = 7;
    foreach ($this->data_harta_lainnya as $i => $harta_lain) {

      //row number
      $startCol = 1;
      $spreadsheet->getActiveSheet()
      ->setCellValueByColumnAndRow($startCol++, $startRow, $i+1);
      foreach ($harta_lain as $prop => $hl) {

        // set input untuk field atas nama
        if ($prop == 'ATAS_NAMA') {
          if ($hl == 1)
          $hl = 'PN YANG BERSANGKUTAN';
          else if ($hl == 2)
          $hl = 'PASANGAN / ANAK';
          else if ( $hl == 3 )
          $hl = 'LAINNYA';
        }

        if ($prop == 'ASAL_USUL') {
          if ($hl == NULL)
            $hl = '-';
          else
            $hl = $this->__get_asal_usul_harta($hl);
        }
          
        // disable tgl_lapor & Jenis laporan
        if ( ($prop == 'tgl_lapor') || ($prop == 'JENIS_LAPORAN') || ($prop == 'ID_LHKPN'))
        $hl = '';

        if ($prop == 'NILAI_PELAPORAN'){
          $hl = (float) $hl;
        }

        $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow($startCol, $startRow, $hl);
        $startCol++;

      }
      $startRow++;
    }

    // print total
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, 'SUBTOTAL')
      ->mergeCells('A'.$startRow.':E'.$startRow);
    $last_row = $startRow-1;
    $spreadsheet->getActiveSheet()
      ->setCellValue('F'.$startRow, '=SUM(F7:F'.$last_row.')')
      ->setCellValue('G'.$startRow, '=SUM(G7:G'.$last_row.')');
    //
    $this->style_table_header(
      'A'.$startRow.':G'.$startRow,
      '00ffff66',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    //styles
    $this->style_table_header('A4:G6', '00008080', $spreadsheet);
    $this->set_col_numberFormat('F7', 'F'.(7+count($this->data_harta_lainnya)), $spreadsheet);
    $this->set_col_numberFormat('G7', 'G'.(7+count($this->data_harta_lainnya)), $spreadsheet);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(38);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(18);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(18);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(18);
  }

  public function cetak_VII($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(15);
    $spreadsheet->getActiveSheet()->setTitle('VII');

    // $data_hutang = $this->Kkp_model->get_data_hutang($id_lhkpn);

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'HUTANG');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A6')
      ->setCellValue('B4','Uraian') // group header
      ->mergeCells('B4:C4')
      ->setCellValue('B5','Jenis')
      ->mergeCells('B5:B6')
      ->setCellValue('C5','Atas Nama')
      ->mergeCells('C5:C6')
      ->setCellValue('D4','Nama Kreditur')
      ->mergeCells('D4:D6')
      ->setCellValue('E4','Bentuk Angunan')
      ->mergeCells('E4:E6')
      ->setCellValue('F4','Nilai Awal Hutang')
      // ->setCellValue('F4','Nilai Perolehan')
      ->mergeCells('F4:F6')
      ->setCellValue('G4','Menurut PN')
      ->setCellValue('G5', '=PROFIL!D4')
      ->setCellValue('G6', '=PROFIL!D6');

      //print content
      $startRow = 7;
      foreach ($this->data_hutang as $i => $dh) {

        //row number
        $startCol = 1;
        $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow($startCol++, $startRow, $i+1);
        foreach ($dh as $prop => $h) {

          // set input untuk field atas nama
          if ($prop == 'ATAS_NAMA') {
            if ($h == 1)
            $h = 'PN YANG BERSANGKUTAN';
            else if ($h == 2)
            $h = 'PASANGAN / ANAK';
            else if ( $h == 3 )
            $h = 'LAINNYA';
          }

          // disable tgl_lapor & Jenis laporan
          if ( ($prop == 'tgl_lapor') || ($prop == 'JENIS_LAPORAN') || ($prop == 'ID_LHKPN'))
          $h = '';

          $spreadsheet->getActiveSheet()
          ->setCellValueByColumnAndRow($startCol, $startRow, $h);
          $startCol++;

        }
        $startRow++;
      }

      // print total
      $spreadsheet->getActiveSheet()
        ->setCellValue('A'.$startRow, 'SUBTOTAL')
        ->mergeCells('A'.$startRow.':E'.$startRow);
      $last_row = $startRow-1;
      $spreadsheet->getActiveSheet()
        ->setCellValue('F'.$startRow, '=SUM(F7:F'.$last_row.')')
        ->setCellValue('G'.$startRow, '=SUM(G7:G'.$last_row.')');
      //
      $this->style_table_header(
        'A'.$startRow.':G'.$startRow,
        '00ffff66',
        $spreadsheet,
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      // //styles
      $this->style_table_header('A4:G6', '00008080', $spreadsheet);
      $this->set_col_numberFormat('F7', 'F'.(7+count($this->data_hutang)), $spreadsheet);
      $this->set_col_numberFormat('G7', 'G'.(7+count($this->data_hutang)), $spreadsheet);

      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(38);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(17);
  }

  public function cetak_VIII($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(16);
    $spreadsheet->getActiveSheet()->setTitle('VIII');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'INFORMASI PENERIMAAN TUNAI');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A7')
      ->setCellValue('B4','Jenis Penerimaan') // group header
      ->mergeCells('B4:B7')
      ->setCellValue('B5','Jenis')
      ->mergeCells('B5:B6')
      ->setCellValue('C4','Menurut PN')
      ->mergeCells('C4:E4')
      ->setCellValue('C5','="PROFIL"!D4')
      ->mergeCells('C5:E5')
      ->setCellValue('C6', '="PROFIL"!D6')
      ->mergeCells('C6:E6')
      ->setCellValue('C7','PN')
      ->setCellValue('D7','Pasangan PN')
      ->setCellValue('E7','Total Penerimaan');

      $pn = new stdClass();
      $ps = new stdClass();
      foreach ($this->data_penerimaan_tunai as $p) {
        $jenis = $p->JENIS_PENERIMAAN;
        $pn->$jenis = $p->NILAI_PENERIMAAN_KAS_PN;
        $ps->$jenis = $p->NILAI_PENERIMAAN_KAS_PASANGAN;
      }
      
    $spreadsheet->getActiveSheet()
      ->setCellValue('A8','A. Penerimaan Dari Pekerjaan')
      ->setCellValue('A9','1')
      ->setCellValue('B9','Gaji dan tunjangan')
      ->setCellValue('C9',(empty($pn->A0)) ? '0' : $pn->A0)->setCellValue('D9',(empty($ps->A0)) ? '0' : $ps->A0)->setCellValue('E9','=SUM(C9:D9)')
      ->setCellValue('A10','2')
      ->setCellValue('B10','Penghasilan dari profesi/keahlian')
      ->setCellValue('C10',(empty($pn->A1)) ? '0' : $pn->A1)->setCellValue('D10',(empty($ps->A1)) ? '0' : $ps->A1)->setCellValue('E10','=SUM(C10:D10)')
      ->setCellValue('A11','3')
      ->setCellValue('B11','Honorarium')
      ->setCellValue('C11',(empty($pn->A2)) ? '0' : $pn->A2)->setCellValue('D11',(empty($ps->A2)) ? '0' : $ps->A2)->setCellValue('E11','=SUM(C11:D11)')
      ->setCellValue('A12','4')
      ->setCellValue('B12','Tantiem, bonus, jasa produksi, THR')
      ->setCellValue('C12',(empty($pn->A3)) ? '0' : $pn->A3)->setCellValue('D12',(empty($ps->A3)) ? '0' : $ps->A3)->setCellValue('E12','=SUM(C12:D12)')
      ->setCellValue('A13','5')
      ->setCellValue('B13','Penerimaan dari pekerjaan lainnya')
      ->setCellValue('C13',(empty($pn->A4)) ? '0' : $pn->A4)->setCellValue('D13',(empty($ps->A4)) ? '0' : $ps->A4)->setCellValue('E13','=SUM(C13:D13)')
      ->setCellValue('A14','SUB TOTAL')
      ->mergeCells('A14:B14')
      ->setCellValue('C14','=SUM(C9:C13)')->setCellValue('D14','=SUM(D9:D13)')->setCellValue('E14','=SUM(E9:E13)');


    $spreadsheet->getActiveSheet()
      ->setCellValue('A15','B. Penerimaan Dari Usaha dan Kekayaan')
      ->setCellValue('A16','1')
      ->setCellValue('B16','Hasil investasi dalam surat berharga')
      ->setCellValue('C16',(empty($pn->B0)) ? '0' : $pn->B0)->setCellValue('D16',(empty($ps->B0)) ? '0' : $ps->B0)->setCellValue('E16','=SUM(C16:D16)')
      ->setCellValue('A17','2')
      ->setCellValue('B17','Hasil usaha/Sewa')
      ->setCellValue('C17',(empty($pn->B1)) ? '0' : $pn->B1)->setCellValue('D17',(empty($ps->B1)) ? '0' : $ps->B1)->setCellValue('E17','=SUM(C17:D17)')
      ->setCellValue('A18','3')
      ->setCellValue('B18','Bunga tabungan/deposito, dan lainnya')
      ->setCellValue('C18',(empty($pn->B2)) ? '0' : $pn->B2)->setCellValue('D18',(empty($ps->B2)) ? '0' : $ps->B2)->setCellValue('E18','=SUM(C18:D18)')
      ->setCellValue('A19','4')
      ->setCellValue('B19','Penjualan atau pelepasan harta')
      ->setCellValue('C19',(empty($pn->B3)) ? '0' : $pn->B3)->setCellValue('D19',(empty($ps->B3)) ? '0' : $ps->B3)->setCellValue('E19','=SUM(C19:D19)')
      ->setCellValue('A20','5')
      ->setCellValue('B20','Penerimaan lainnya')
      ->setCellValue('C20',(empty($pn->B4)) ? '0' : $pn->B4)->setCellValue('D20',(empty($ps->B4)) ? '0' : $ps->B4)->setCellValue('E20','=SUM(C20:D20)')
      ->setCellValue('A21','SUB TOTAL')
      ->mergeCells('A21:B21')
      ->setCellValue('C21','=SUM(C16:C20)')->setCellValue('D21','=SUM(D16:D20)')->setCellValue('E21','=SUM(E16:E20)');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A22','C. Penerimaan Lainnya')
      ->setCellValue('A23','1')
      ->setCellValue('B23','Penerimaan hutang')
      ->setCellValue('C23',(empty($pn->C0)) ? '0' : $pn->C0)->setCellValue('D23',(empty($ps->C0)) ? '0' : $ps->C0)->setCellValue('E23','=SUM(C23:D23)')
      ->setCellValue('A24','2')
      ->setCellValue('B24','Penerimaan warisan')
      ->setCellValue('C24',(empty($pn->C1)) ? '0' : $pn->C1)->setCellValue('D24',(empty($ps->C1)) ? '0' : $ps->C1)->setCellValue('E24','=SUM(C24:D24)')
      ->setCellValue('A25','3')
      ->setCellValue('B25','Penerimaan hibah/hadiah')
      ->setCellValue('C25',(empty($pn->C2)) ? '0' : $pn->C2)->setCellValue('D25',(empty($ps->C2)) ? '0' : $ps->C2)->setCellValue('E25','=SUM(C25:D25)')
      ->setCellValue('A26','4')
      ->setCellValue('B26','Lainnya')
      ->setCellValue('C26',(empty($pn->C3)) ? '0' : $pn->C3)->setCellValue('D26',(empty($ps->C3)) ? '0' : $ps->C3)->setCellValue('E26','=SUM(C26:D26)')
      ->setCellValue('A27','SUB TOTAL')
      ->mergeCells('A27:B27')
      ->setCellValue('C27','=SUM(C23:C26)')->setCellValue('D27','=SUM(D23:D26)')->setCellValue('E27','=SUM(E23:E26)');

    // $arr_length = count($this->data_penerimaan_tunai);
    // if ($arr_length > 0) {

    //   $kas_pn = (array) json_decode($this->data_penerimaan_tunai[0]->NILAI_PENERIMAAN_KAS_PN);
    //   $kas_pasangan = (array) json_decode($this->data_penerimaan_tunai[0]->NILAI_PENERIMAAN_KAS_PASANGAN);

    //   // print kas pasangan
    //   $startRow = 9;
    //   foreach ($kas_pasangan as $i_kas_pasangan => $sub_kas) {

    //     $sub_kas = str_replace(".","",reset($sub_kas));
    //     $spreadsheet->getActiveSheet()
    //     ->setCellValue('D'.$startRow, $sub_kas);
    //     $startRow++;

    //   }
    //   //count SUBTOTAL
    //   $last_row = $startRow-1;
    //   $first_sub_row = $startRow - count($kas_pasangan);
    //   $spreadsheet->getActiveSheet()
    //   ->setCellValue('D'.$startRow, '=SUM(D'.$first_sub_row.':D'.$last_row.')');

    //   // print kas pn  & count column Total Penerimaan
    //   $startRow = 9;
    //   foreach ($kas_pn as $i_kas_pn => $sub_kas) {

    //     foreach ($sub_kas as $i_sub_kas => $value) {
    //       $value = str_replace(".","",reset($value));
    //       $spreadsheet->getActiveSheet()
    //       ->setCellValue('C'.$startRow, $value)
    //       ->setCellValue('E'.$startRow, '=SUM(C'.$startRow.':D'.$startRow.')'); // add count on right
    //       $startRow++;
    //     }

    //     //count SUBTOTAL
    //     $last_row = $startRow-1;
    //     $first_sub_row = $startRow - count($sub_kas);
    //     $spreadsheet->getActiveSheet()
    //     ->setCellValue('C'.$startRow, '=SUM(C'.$first_sub_row.':C'.$last_row.')')
    //     ->setCellValue('E'.$startRow, '=SUM(C'.$startRow.':D'.$startRow.')'); // add count on right

    //     $startRow += 2;
    //   }
    // }

    // ----- SUBTOTAL PENERIMAAN
    $spreadsheet->getActiveSheet()
      ->setCellValue('B28','SUBTOTAL PENERIMAAN  (A + B + C)')
      ->setCellValue('C28','=C14+C21+C27')
      ->setCellValue('E28','=E14+E21+E27');

    // ----- TOTAL
    $spreadsheet->getActiveSheet()
      ->setCellValue('B29','TOTAL PENERIMAAN')
      ->setCellValue('E29','=E28')
      ->setCellValue('B30','TOTAL PENGELUARAN') // TODO: integrate with page IX
      ->setCellValue('E30','=IX!C23')
      ->setCellValue('B31','TOTAL PENGHASILAN BERSIH')
      ->setCellValue('E31','=E29-E30');

    // styles
    $this->style_table_header('A4:E7', '00008080', $spreadsheet);
    $this->set_col_numberFormat('C7', 'C31', $spreadsheet);
    $this->set_col_numberFormat('D7', 'D31', $spreadsheet);
    $this->set_col_numberFormat('E7', 'E31', $spreadsheet);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(42);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(16);
    $this->style_table_header(
      'A14:E14',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A21:E21',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A27:E27',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A28:E28',
      '00ffff66',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    $this->style_table_header(
      'A29:B31',
      '00000000',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $this->style_table_header(
      'E29:E31',
      '00000000',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

  }

  public function cetak_IX($id_lhkpn, $spreadsheet)
  {

    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(17);
    $spreadsheet->getActiveSheet()->setTitle('IX');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')
      ->setCellValue('A3', 'INFORMASI PENGELUARAN TUNAI');


    $spreadsheet->getActiveSheet()
      ->setCellValue('A4','No.')
      ->mergeCells('A4:A6')
      ->setCellValue('B4','Jenis Penerimaan') // group header
      ->mergeCells('B4:B6')
      ->setCellValue('C4','Menurut PN')
      ->setCellValue('C5', '=VIII!C5')
      ->setCellValue('C6', '=VIII!C6');

      $pn = new stdClass();
      foreach ($this->data_pengeluaran_tunai as $p) {
        $jenis = $p->JENIS_PENGELUARAN;
        $pn->$jenis = $p->NILAI_PENGELUARAN_KAS;
      }

      $spreadsheet->getActiveSheet()
      ->setCellValue('A7','A. Pengeluaran Rutin')
      ->setCellValue('A8','1')
      ->setCellValue('B8','Biaya rumah tangga (termasuk transportasi, pendidikan, kesehatan, rekreasi, pembayaran kartu kredit)' )
      ->setCellValue('C8',(empty($pn->A0)) ? '0' : $pn->A0)
      ->setCellValue('A9','2')
      ->setCellValue('B9','Biaya sosial (keagamaan, adat, zakat, infaq, sumbangan lain)' )
      ->setCellValue('C9',(empty($pn->A1)) ? '0' : $pn->A1)
      ->setCellValue('A10','3')
      ->setCellValue('B10','Pembayaran Pajak (PBB, kendaraan, pajak daerah, pajak lain)' )
      ->setCellValue('C10',(empty($pn->A2)) ? '0' : $pn->A2)
      ->setCellValue('A11','4')
      ->setCellValue('B11','Pengeluaran rutin lainnya' )
      ->setCellValue('C11',(empty($pn->A3)) ? '0' : $pn->A3)
      ->setCellValue('B12','SUB TOTAL' )->setCellValue('C12','=SUM(C8:C11)')
      ->setCellValue('A13','B. Pengeluaran Non-Rutin')
      ->setCellValue('A14','1' )
      ->setCellValue('B14','Pembelian/perolehan harta baru' )
      ->setCellValue('C14',(empty($pn->B0)) ? '0' : $pn->B0)
      ->setCellValue('A15','2' )
      ->setCellValue('B15','Pemeliharaan/modifikasi/rehabilitasi harta' )
      ->setCellValue('C15',(empty($pn->B1)) ? '0' : $pn->B1)
      ->setCellValue('A16','3' )
      ->setCellValue('B16','Pengeluaran non-rutin lainnya' )
      ->setCellValue('C16',(empty($pn->B2)) ? '0' : $pn->B2)
      ->setCellValue('B17','SUB TOTAL' )->setCellValue('C17','=SUM(C14:C16)')
      ->setCellValue('A18','C. Pengeluaran Lainnya')
      ->setCellValue('A19','1' )
      ->setCellValue('B19','Biaya pengurusan waris/hibah/hadiah' )
      ->setCellValue('C19',(empty($pn->C0)) ? '0' : $pn->C0)
      ->setCellValue('A20','2' )
      ->setCellValue('B20','Pelunasan/angsuran hutang' )
      ->setCellValue('C20',(empty($pn->C1)) ? '0' : $pn->C1)
      ->setCellValue('A21','3' )
      ->setCellValue('B21','Pengeluaran Lainnya' )
      ->setCellValue('C21',(empty($pn->C2)) ? '0' : $pn->C2)
      ->setCellValue('B22','SUB TOTAL' )->setCellValue('C22','=SUM(C19:C21)')
      ->setCellValue('B23','TOTAL PENGELUARAN  (A + B + C)' )
      ->setCellValue('C23','=C12+C17+C22' );

    // echo "die here ---<br>";
    // echo "length ".$arr_length;
    // echo "<pre>";
    // print_r($this->data_pengeluaran_tunai);
    // echo "<pre>";
    // die();
    // $arr_length = count($this->data_pengeluaran_tunai);
    // if ($arr_length > 0) {
    //   $pengeluaran_kas = (array) json_decode($this->data_pengeluaran_tunai[0]->NILAI_PENGELUARAN_KAS);

    //   $startRow = 8;
    //   foreach ($pengeluaran_kas as $i_pengeluaran_kas => $sub_kas) {

    //     foreach ($sub_kas as $i => $value) {
    //       $value = str_replace(".","",reset($value));
    //       $spreadsheet->getActiveSheet()
    //       ->setCellValue('C'.$startRow, $value);
    //       $startRow++;
    //     }
    //     //count SUBTOTAL
    //     $last_row = $startRow-1;
    //     $first_sub_row = $startRow - count($sub_kas);
    //     $spreadsheet->getActiveSheet()
    //     ->setCellValue('C'.$startRow, '=SUM(C'.$first_sub_row.':C'.$last_row.')');

    //     $startRow += 2;
    //   }
    // }

    // styles
    $this->style_table_header('A4:C6', $this->color_green, $spreadsheet);
    $this->set_col_numberFormat('C7', 'C23', $spreadsheet);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(50);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(16);
    $this->style_table_header(
      'A12:C12',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A17:C17',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A22:C22',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A23:C23',
      '00ffff66',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

  }

  public function cetak_BAK($id_lhkpn, $id_audit=0, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(18);
    $spreadsheet->getActiveSheet()->setTitle('BAK');

    //image logo kpk
    // echo "<img src=".base_url()."img/stop.png>";
    // die();

    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Logo');
    $drawing->setPath('./img/logo.png');
    $drawing->setCoordinates('B2');
    $drawing->setHeight(40);

    $drawing->setWorksheet($spreadsheet->getActiveSheet());

    $join = [
      ['table' => 'm_unit_kerja_eaudit', 'on' => 'T_LHKPN_AUDIT.id_uk_eaudit  = m_unit_kerja_eaudit.id_uk_eaudit AND m_unit_kerja_eaudit.IS_ACTIVE = "1"'],
    ];

    $where = [
      'T_LHKPN_AUDIT.id_audit' => $id_audit
    ];
    
    $data_audit = $this->mglobal->get_data_all("T_LHKPN_AUDIT", $join, $where)[0]; 
    $nama_pic = $this->get_nama_pic($data_audit->id_pic);

    $tgl_st = null;
    if($data_audit->tgl_mulai_periksa != null){
       $tgl_st = date('d-M-Y', strtotime($data_audit->tgl_mulai_periksa));
    }

    //title
    $spreadsheet->getActiveSheet()
      ->setCellValue('A4', 'BERITA ACARA KLARIFIKASI LHKPN')->mergeCells('A4:G4')
      ->setCellValue('A7', 'Pada hari ini,  <tanggal  bulan tahun> , bertempat di <Jl. nama jalan> , Pukul <00.00> WIB, kami Pemeriksa LHKPN:')
      ->setCellValue('A9', '-------------')->setCellValue('C9', '1. Nama Pemeriksa')->setCellValue('D9', ': '.$this->cek_is_null($nama_pic))
      ->setCellValue('A10', '-------------')->setCellValue('C10', '    Jabatan')->setCellValue('D10', ': Spesialis Pendaftaran dan Pemeriksaan PP LHKPN')
      ->setCellValue('A11', '-------------')->setCellValue('C11', '2. Nama Pemeriksa')->setCellValue('D11', ':')
      ->setCellValue('A12', '-------------')->setCellValue('C12', '    Jabatan')->setCellValue('D12', ': Spesialis Pendaftaran dan Pemeriksaan PP LHKPN')
      ->setCellValue('A13', 'Berdasarkan Surat  Tugas  Nomor : '.$this->cek_is_null($data_audit->nomor_surat_tugas).' tanggal '. $this->cek_is_null($tgl_st).' telah melakukan  klarifikasi atas Laporan Harta Kekayaan Penyelenggara Negara sebagai berikut:')->mergeCells('A13:G13')
      ->setCellValue('A16', '-------------')->setCellValue('C16', 'Nama PN')->setCellValue('D16', ': '.$this->data_pn[0]->NAMA_LENGKAP.'')
      ->setCellValue('A17', '-------------')->setCellValue('C17', 'NIK')->setCellValue('D17', ': '.$this->cek_is_null($this->data_pn[0]->NIK).'')
      ->setCellValue('A18', '-------------')->setCellValue('C18', 'Tempat/Tanggal Lahir')->setCellValue('D18', ': '.$this->data_pn[0]->TEMPAT_LAHIR.' / '.date('d-M-Y',strtotime($this->data_pn[0]->TANGGAL_LAHIR)).'')
      ->setCellValue('A19', '-------------')->setCellValue('C19', 'Jabatan Saat Ini')->setCellValue('D19', ': '.$this->cek_is_null($this->data_pn[0]->NAMA_JABATAN).' / '.$this->cek_is_null($this->data_pn[0]->UK_NAMA).'')
      ->setCellValue('A20', '-------------')->setCellValue('C20', 'Instansi')->setCellValue('D20', ': '.$this->cek_is_null($this->data_pn[0]->INST_NAMA).'')
      ->setCellValue('A21', '-------------')->setCellValue('C21', 'Jenis  Laporan')->setCellValue('D21', ': '.$this->data_pn[0]->JENIS_LAPORAN == 4 ? ': Periodik' : ($this->data_pn[0]->JENIS_LAPORAN == 6 ? ': Dummy' : ': Khusus').'')
      ->setCellValue('A22', '-------------')->setCellValue('C22', 'Tanggal Lapor')->setCellValue('D22', ': '.date('d M Y', strtotime($this->data_pn[0]->tgl_lapor)).'')
      ->setCellValue('A23', '-------------')->setCellValue('C23', 'No. Telp/HP')->setCellValue('D23', ': '.$this->cek_is_null($this->data_pn[0]->HP).'')
      ->setCellValue('A26', 'dengan rincian Harta Kekayaan, Penerimaan dan Pengeluaran setelah Klarifikasi sebagai berikut :')
      ->setCellValue('A28', 'I. HARTA KEKAYAAN')
      ->setCellValue('A29', 'Berikut adalah ringkasan mengenai Harta Kekayaan menurut PN dan menurut Hasil Klarifikasi LHKPN. Uraian mengenai perbedaan antara nilai Harta Kekayaan sebelum dan sesudah Klarifikasi tercantum dalam Lampiran Berita Acara Klarifikasi LHKPN yang merupakan satu kesatuan dan bagian tidak terpisahkan dari Berita Acara ini.-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------')
      ->mergeCells('A29:G29')
      ->setCellValue('A30', 'No')->mergeCells('A30:A31')
      ->setCellValue('B30', 'JENIS HARTA')->mergeCells('B30:D31')
      ->setCellValue('E30', 'MENURUT PN')
      ->setCellValue('E31', "='PROFIL'!D6")
      ->setCellValue('F30', 'HASIL KLARIFIKASI')
      ->setCellValue('F31', "='PROFIL'!M29")
      ->setCellValue('G30', "KOREKSI");

      // $spreadsheet->getActiveSheet()
      //   ->setCellValue('A32', 'A')
      //   ->setCellValue('B32', 'HARTA TIDAK BERGERAK (TANAH DAN/ATAU BANGUNAN)')
      //   ->setCellValue('B33', 'A. 1')
      //   ->setCellValue('C33', 'TANAH BANGUNAN')->setCellValue('E33', "='RH'!E8")
      //   ->setCellValue('A34', 'B')
      //   ->setCellValue('B34', 'HARTA BERGERAK (ALAT TRANSPORTASI DAN MESIN)')
      //   ->setCellValue('B35', 'B. 1')->setCellValue('C35', 'MOBIL')->setCellValue('E35', "='RH'!E10")
      //   ->setCellValue('B36', 'B. 2')->setCellValue('C36', 'MOTOR')->setCellValue('E36', "='RH'!E11")
      //   ->setCellValue('B37', 'B. 3')->setCellValue('C37', 'KAPAL LAUT/PERAHU')->setCellValue('E37', "='RH'!E12")
      //   ->setCellValue('B38', 'B. 4')->setCellValue('C38', 'PESAWAT TERBANG')->setCellValue('E38', "='RH'!E13")
      //   ->setCellValue('B39', 'B. 5')->setCellValue('C39', 'LAINNYA')->setCellValue('E39', "='RH'!E14")
      //   ->setCellValue('A40', 'C')
      //   ->setCellValue('B40', 'HARTA BERGERAK LAINNYA')
      //   ->setCellValue('B41', 'C. 1')->setCellValue('C41', 'PERABOTAN RUMAH TANGGA')->setCellValue('E41', "='RH'!E16")
      //   ->setCellValue('B42', 'C. 2')->setCellValue('C42', 'BARANG ELEKTRONIK')->setCellValue('E42', "='RH'!E17")
      //   ->setCellValue('B43', 'C. 3')->setCellValue('C43', 'PERHIASAN & LOGAM/BATU MULIA')->setCellValue('E43', "='RH'!E18")
      //   ->setCellValue('B44', 'C. 4')->setCellValue('C44', 'BARANG SENI/ANTIK')->setCellValue('E44', "='RH'!E19")
      //   ->setCellValue('B45', 'C. 5')->setCellValue('C45', 'PERSEDIAAN')->setCellValue('E45', "='RH'!E20")
      //   ->setCellValue('B46', 'C. 6')->setCellValue('C46', 'HARTA BERGERAK LAINNYA')->setCellValue('E46', "='RH'!E21")
      //   ->setCellValue('A47', 'D')
      //   ->setCellValue('B47', 'SURAT BERHARGA')
      //   ->setCellValue('B48', 'D. 1')->setCellValue('C48', 'EFEK YANG DIPERDAGANGKAN DI BURSA (LISTING)')->setCellValue('E48', "='RH'!E23")
      //   ->setCellValue('B49', 'D. 2')->setCellValue('C49', 'KEPEMILIKAN/PENYERTAAN DI PERUSAHAAN NON-LISTING')->setCellValue('E49', "='RH'!E24")
      //   ->setCellValue('A50', 'E')
      //   ->setCellValue('B50', 'KAS DAN SETARA KAS')
      //   ->setCellValue('B51', 'E. 1')->setCellValue('C51', 'UANG TUNAI')->setCellValue('E51', "='RH'!E26")
      //   ->setCellValue('B52', 'E. 2')->setCellValue('C52', 'DEPOSITO')->setCellValue('E52', "='RH'!E27")
      //   ->setCellValue('B53', 'E. 3')->setCellValue('C53', 'GIRO')->setCellValue('E53', "='RH'!E28")
      //   ->setCellValue('B54', 'E. 4')->setCellValue('C54', 'TABUNGAN')->setCellValue('E54', "='RH'!E29")
      //   ->setCellValue('B55', 'E. 5')->setCellValue('C55', 'LAINNYA')->setCellValue('E55', "='RH'!E30")
      //   ->setCellValue('A56', 'F')
      //   ->setCellValue('B56', 'HARTA LAINNYA')
      //   ->setCellValue('B57', 'F. 1')->setCellValue('C57', 'PIUTANG')->setCellValue('E57', "='RH'!E32")
      //   ->setCellValue('B58', 'F. 2')->setCellValue('C58', 'KERJASAMA USAHA YANG TIDAK BERBADAN HUKUM')->setCellValue('E58', "='RH'!E33")
      //   ->setCellValue('B59', 'F. 3')->setCellValue('C59', 'HAK KEKAYAAN INTELEKTUAL')->setCellValue('E59', "='RH'!E34")
      //   ->setCellValue('B60', 'F. 4')->setCellValue('C60', 'DANA PENSIUN/TABUNGAN HARI TUA')->setCellValue('E60', "='RH'!E35")
      //   ->setCellValue('B61', 'F. 5')->setCellValue('C61', 'UNITLINK')->setCellValue('E61', "='RH'!E36")
      //   ->setCellValue('B62', 'F. 6')->setCellValue('C62', 'SEWA JANGKA PANJANG DIBAYAR DIMUKA')->setCellValue('E62', "='RH'!E37")
      //   ->setCellValue('B63', 'F. 7')->setCellValue('C63', 'HAK PENGELOLAAN/PENGUSAHAAN YANG DIMILIKI PERORANGAN')->setCellValue('E63', "='RH'!E38")
      //   ->setCellValue('B64', 'F. 8')->setCellValue('C64', 'LAINNYA')->setCellValue('E64', "='RH'!E39")
      //   ->setCellValue('D65', 'TOTAL HARTA')->setCellValue('E65', "='RH'!E40")
      //   ->setCellValue('A66', 'G')->setCellValue('B66', 'HUTANG')->setCellValue('E66', "='RH'!E41")
      //   ->setCellValue('D67', 'TOTAL HARTA KEKAYAAN')->setCellValue('E67', "='RH'!E42")
      //   ->setCellValue('A68', 'II. PENERIMAAN DAN PENGELUARAN');
        $totalRowA = 12 + count($this->data_tanah_bangunan) + 9;
        $totalRowB = 12 + count($this->data_harta_bergerak) + 9;
        $totalRowC = 12 + count($this->data_harta_bergerak_lainnya) + 9;
        $totalRowD = 12 + count($this->data_surat_berharga) + 9;
        $totalRowE = 12 + count($this->data_kas) + 9;
        $totalRowF = 12 + count($this->data_harta_lainnya) + 9;
        $totalRowG = 12 + count($this->data_hutang) + 9;

        $spreadsheet->getActiveSheet()
        ->setCellValue('A32', 'A')
        ->setCellValue('B32', 'HARTA TIDAK BERGERAK (TANAH DAN/ATAU BANGUNAN)')->setCellValue('E32', "='1'!M".$totalRowA)->setCellValue('F32', "='1'!N".$totalRowA)->setCellValue('G32', "=F32-E32")
        ->setCellValue('A33', 'B')
        ->setCellValue('B33', 'HARTA BERGERAK (ALAT TRANSPORTASI DAN MESIN)')->setCellValue('E33', "='2'!O".$totalRowB)->setCellValue('F33', "='2'!P".$totalRowB)->setCellValue('G33', "=F33-E33")
        ->setCellValue('A34', 'C')
        ->setCellValue('B34', 'HARTA BERGERAK LAINNYA')->setCellValue('E34', "='3'!J".$totalRowC)->setCellValue('F34', "='3'!K".$totalRowC)->setCellValue('G34', "=F34-E34")
        ->setCellValue('A35', 'D')
        ->setCellValue('B35', 'SURAT BERHARGA')->setCellValue('E35', "='4'!K".$totalRowD)->setCellValue('F35', "='4'!L".$totalRowD)->setCellValue('G35', "=F35-E35")
        ->setCellValue('A36', 'E')
        ->setCellValue('B36', 'KAS DAN SETARA KAS')->setCellValue('E36', "='5'!K".$totalRowE)->setCellValue('F36', "='5'!L".$totalRowE)->setCellValue('G36', "=F36-E36")
        ->setCellValue('A37', 'F')
        ->setCellValue('B37', 'HARTA LAINNYA')->setCellValue('E37', "='6'!H".$totalRowF)->setCellValue('F37', "='6'!I".$totalRowF)->setCellValue('G37', "=F37-E37")
        ->setCellValue('D38', 'TOTAL HARTA')->setCellValue('E38', "=SUM(E32:E37)")->setCellValue('F38', "=SUM(F32:F37)")->setCellValue('G38', "=F38-E38")
        ->setCellValue('A39', 'G')->setCellValue('B39', 'HUTANG')->setCellValue('E39', "='7'!G".$totalRowG)->setCellValue('F39', "='7'!H".$totalRowG)->setCellValue('G39', "=F39-E39")
        ->setCellValue('D40', 'TOTAL HARTA KEKAYAAN')->setCellValue('E40', "=E38-E39")->setCellValue('F40', "=F38-F39")->setCellValue('G40', "=F40-E40")
        ->setCellValue('A41', 'II. PENERIMAAN DAN PENGELUARAN');

      // $spreadsheet->getActiveSheet()
      //   ->setCellValue('A69', 'Berikut adalah ringkasan mengenai Penerimaan dan Pengeluaran menurut PN serta menurut Hasil Klarifikasi LHKPN. Uraian mengenai perbedaan antara nilai Penerimaan dan Pengeluaran sebelum dan sesudah Klarifikasi tercantum dalam Lampiran Berita Acara Klarifikasi LHKPN yang merupakan satu kesatuan dan bagian tidak terpisahkan dari Berita Acara ini.--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------')
      //   ->mergeCells('A69:G69')
      //   ->setCellValue('A70', 'NO.')->mergeCells('A70:A72')
      //   ->setCellValue('B70', 'JENIS PENERIMAAN')->mergeCells('B70:D72')
      //   ->setCellValue('E70', 'NILAI PENERIMAAN')->mergeCells('E70:F70')
      //   ->setCellValue('E71', 'MENURUT LHKPN')->mergeCells('E71:E72')
      //   ->setCellValue('F71', 'HASIL KLARIFIKASI')->mergeCells('F71:F72')
      //   ->setCellValue('G70', 'KOREKSI')->mergeCells('G70:G72')
      //   ->setCellValue('A73', '1')
      //   ->setCellValue('B73', 'A.      PENGHASILAN PN (PER TAHUN)')->mergeCells('B73:G73')
      //   ->setCellValue('C74', 'Penerimaan Dari Pekerjaan')->setCellValue('E74', "='8'!C19")
      //   ->setCellValue('C75', 'Penerimaan Dari Usaha dan Kekayaan')->setCellValue('E75', "='8'!C26")
      //   ->setCellValue('C76', 'Penerimaan Lainnya')->setCellValue('E76', "='8'!C32")
      //   ->setCellValue('B77', 'B.      PENGHASILAN ISTRI/SUAMI (PER TAHUN)')->mergeCells('B77:G77')
      //   ->setCellValue('C78', 'Penerimaan Dari Pekerjaan')->setCellValue('E76', "='8'!D19")
      //   ->setCellValue('A79', '2')
      //   ->setCellValue('B79', 'PENGELUARAN (PER TAHUN)')->mergeCells('B79:G79')
      //   ->setCellValue('C80', 'Pengeluaran')
      //   ->setCellValue('E80', "='9'!C27")
      //   ->setCellValue('F80', "='9'!D27")
      //   ->setCellValue('G80', "=F80-E80")
      //   ->setCellValue('A82', 'Menurut Penyelenggara Negara, tidak ada lagi Harta Kekayaan, Penerimaan dan Pengeluaran yang dimiliki dan/atau diterima dan/atau dikeluarkan, baik atas nama yang bersangkutan atau keluarga atau atas nama pihak lain yang belum atau tidak dilaporkan. -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------')
      //   ->mergeCells('A82:G82')
      //   ->setCellValue('A84', 'Klarifikasi semata-mata dilakukan terhadap LHKPN per tanggal tersebut di atas serta berdasarkan keterangan dan dokumen pendukung yang disampaikan oleh Penyelenggara Negara. -------------------------------------------------------------------------------------------------------------------------------')
      //   ->mergeCells('A84:G84')
      //   ->setCellValue('A86', 'Hasil Klarifikasi yang tercantum dalam Berita Acara Klarifikasi LHKPN ini akan diumumkan melalui media pengumuman yang telah ditetapkan oleh Komisi Pemberantasan Korupsi dan untuk selanjutnya dilaporkan oleh Penyelenggara Negara dalam penyampaian LHKPN berikutnya. --------------------------------------------------------------------------------------------------------------------------------------------------')
      //   ->mergeCells('A86:G86')
      //   ->setCellValue('A88', 'Berita Acara Klarifikasi ini tidak dapat dijadikan sebagai dasar oleh Penyelenggara Negara dan/atau siapapun juga untuk menyatakan bahwa Penyelenggara Negara yang bersangkutan bebas dari tindak pidana korupsi dan/atau tisebagaimana diatur dalam peraturan perundang-undangan yang berlaku maupun tindak pidana lainnya. ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------')
      //   ->mergeCells('A88:G88')
      //   ->setCellValue('A90', 'Catatan :')
      //   ->setCellValue('A91', '<Tidak ada catatan>')
      //   ->setCellValue('A93', '=TEXT(TODAY(),"DD MMMM YYYY")')
      //   ->setCellValue('A95', 'Penyelenggara Negara')
      //   ->setCellValue('F95', '         Tim Klarifikasi,')->mergeCells('F95:G95')
      //   ->setCellValue('A100', '=PROFIL!D7')
      //   ->setCellValue('F100', '=PROFIL!M27')
      //   ->setCellValue('G100', '=PROFIL!M28');
      
        $spreadsheet->getActiveSheet()
        ->setCellValue('A42', 'Berikut adalah ringkasan mengenai Penerimaan dan Pengeluaran menurut PN serta menurut Hasil Klarifikasi LHKPN. Uraian mengenai perbedaan antara nilai Penerimaan dan Pengeluaran sebelum dan sesudah Klarifikasi tercantum dalam Lampiran Berita Acara Klarifikasi LHKPN yang merupakan satu kesatuan dan bagian tidak terpisahkan dari Berita Acara ini.--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------')
        ->mergeCells('A42:G42')
        ->setCellValue('A43', 'NO.')->mergeCells('A43:A45')
        ->setCellValue('B43', 'JENIS PENERIMAAN')->mergeCells('B43:D45')
        ->setCellValue('E43', 'NILAI PENERIMAAN')->mergeCells('E43:F43')
        ->setCellValue('E44', 'MENURUT LHKPN')->mergeCells('E44:E45')
        ->setCellValue('F44', 'HASIL KLARIFIKASI')->mergeCells('F44:F45')
        ->setCellValue('G43', 'KOREKSI')->mergeCells('G43:G45')
        ->setCellValue('A46', '1')
        ->setCellValue('B46', 'A.      PENGHASILAN PN (PER TAHUN)')->mergeCells('B46:G46')
        ->setCellValue('C47', 'Penerimaan Dari Pekerjaan')->setCellValue('E47', "='8'!C19")->setCellValue('F47', "='8'!F19")->setCellValue('G47', "=F47-E47")
        ->setCellValue('C48', 'Penerimaan Dari Usaha dan Kekayaan')->setCellValue('E48', "='8'!C26")->setCellValue('F48', "='8'!F26")->setCellValue('G48', "=F48-E48")
        ->setCellValue('C49', 'Penerimaan Lainnya')->setCellValue('E49', "='8'!C32")->setCellValue('F49', "='8'!F32")->setCellValue('G49', "=F49-E49")
        ->setCellValue('B50', 'B.      PENGHASILAN ISTRI/SUAMI (PER TAHUN)')->mergeCells('B50:G50')
        ->setCellValue('C51', 'Penerimaan Dari Pekerjaan')->setCellValue('E51', "='8'!D19")->setCellValue('F51', "='8'!G19")->setCellValue('G51', "=F51-E51")
        ->setCellValue('A52', '2')
        ->setCellValue('B52', 'PENGELUARAN (PER TAHUN)')->mergeCells('B52:G52')
        ->setCellValue('C53', 'Pengeluaran')
        ->setCellValue('E53', "='9'!C27")->setCellValue('F53', "='9'!D27")->setCellValue('G53', "=F53-E53")
        ->setCellValue('A55', 'Menurut Penyelenggara Negara, tidak ada lagi Harta Kekayaan, Penerimaan dan Pengeluaran yang dimiliki dan/atau diterima dan/atau dikeluarkan, baik atas nama yang bersangkutan atau keluarga atau atas nama pihak lain yang belum atau tidak dilaporkan. -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------')
          ->mergeCells('A55:G55')
        ->setCellValue('A57', 'Klarifikasi semata-mata dilakukan terhadap LHKPN per tanggal tersebut di atas serta berdasarkan keterangan dan dokumen pendukung yang disampaikan oleh Penyelenggara Negara. -------------------------------------------------------------------------------------------------------------------------------')
          ->mergeCells('A57:G57')
        ->setCellValue('A59', 'Hasil Klarifikasi yang tercantum dalam Berita Acara Klarifikasi LHKPN ini selanjutnya dilaporkan oleh Penyelenggara Negara dalam penyampaian LHKPN berikutnya. --------------------------------------------------------------------------------------------------------------------------------------------------')
          ->mergeCells('A59:G59')
        ->setCellValue('A61', 'Berita Acara Klarifikasi ini tidak dapat dijadikan sebagai dasar oleh Penyelenggara Negara dan/atau siapapun juga untuk menyatakan bahwa Penyelenggara Negara yang bersangkutan bebas dari tindak pidana korupsi dan/atau sebagaimana diatur dalam peraturan perundang-undangan yang berlaku maupun tindak pidana lainnya. ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------')
          ->mergeCells('A61:G61')
        ->setCellValue('A63', 'Catatan :')
        ->setCellValue('A64', '<Tidak ada catatan>')
        ->setCellValue('A66', '=TEXT(TODAY(),"DD MMMM YYYY")')
        ->setCellValue('A68', 'Penyelenggara Negara')
        ->setCellValue('F68', '         Tim Klarifikasi,')->mergeCells('F68:G68')
        ->setCellValue('A73', '=PROFIL!D7')
        ->setCellValue('F73', '=PROFIL!M27')
        ->setCellValue('G73', '=PROFIL!M28');

    // style
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(24);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(27);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);

    $spreadsheet->getActiveSheet()->getRowDimension('7')->setRowHeight(30);
    $spreadsheet->getActiveSheet()->getRowDimension('14')->setRowHeight(23);
    $spreadsheet->getActiveSheet()->getRowDimension('29')->setRowHeight(53);
    $spreadsheet->getActiveSheet()->getRowDimension('42')->setRowHeight(55);
    $spreadsheet->getActiveSheet()->getRowDimension('55')->setRowHeight(44);
    $spreadsheet->getActiveSheet()->getRowDimension('57')->setRowHeight(32);
    $spreadsheet->getActiveSheet()->getRowDimension('59')->setRowHeight(44);
    $spreadsheet->getActiveSheet()->getRowDimension('61')->setRowHeight(57);

    $spreadsheet->getActiveSheet()->getStyle('A13')->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('A29')->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('A42')->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('A55')->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('A57')->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('A59')->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('A61')->getAlignment()->setWrapText(true);

    $this->set_col_numberFormat('E32', 'G40', $spreadsheet);
    $this->set_col_numberFormat('E47', 'G53', $spreadsheet);

    $this->style_table_header('A4:G4', '', $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK
    );
    $this->style_table_header('A30:G31', '00008080', $spreadsheet);
    $this->style_table_header('A40:G40', '00ffff66', $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK
    );
    $this->style_table_header('A43:G45', '00008080', $spreadsheet);
    // #ffff66
    // #008080
  }

  public function cetak_1($id_lhkpn, $spreadsheet)
  {
    // Header Lampiran
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(19);
    $spreadsheet->getActiveSheet()->setTitle('1');

    $this->lampiran_header('LAMPIRAN 1. HARTA TIDAK BERGERAK', $spreadsheet);
    // -- bagian 1 - Dilaporkan
    $this->lampiran_table_header('DILAPORKAN', 8, $spreadsheet, '00008080');
    // $data_tanah_bangunan = $this->Kkp_model->get_data_tanah_bangunan($id_lhkpn);
    $startRow = 12;

    $this->set_col_numberFormat('L'.$startRow, 'O'.($startRow + count($this->data_tanah_bangunan_new) + 500), $spreadsheet);

    $offset0 = $startRow;
    $this->print_list_data($this->data_tanah_bangunan_new, $startRow, $spreadsheet);
    $this->set_hasil_klarifikasi($startRow, $this->data_tanah_bangunan_new, $spreadsheet, 'N');
    $this->print_koreksi($startRow, $this->data_tanah_bangunan_new, $spreadsheet, 'M', 'N', 'O');

    // print total
    $startRow += count($this->data_tanah_bangunan_new);
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, 'SUBTOTAL')
      ->mergeCells('A'.$startRow.':K'.$startRow);
    $last_row = $startRow-1;
    $spreadsheet->getActiveSheet()
    ->setCellValue('L'.$startRow, '=SUM(L'.$offset0.':L'.$last_row.')');
    $spreadsheet->getActiveSheet()
    ->setCellValue('M'.$startRow, '=SUM(M'.$offset0.':M'.$last_row.')');
    $spreadsheet->getActiveSheet()
    ->setCellValue('N'.$startRow, '=SUM(N'.$offset0.':N'.$last_row.')');

    $this->style_table_header(
      'A'.$startRow.':N'.$startRow,
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 2
    $startRow += 2;
    $this->lampiran_table_header('TIDAK DILAPORKAN', $startRow, $spreadsheet, '00632523');
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':K'.($startRow+6))
    ->setCellValue('L'.($startRow+6), '=SUM(L'.($startRow+4).':L'.($startRow+5).')')
    ->setCellValue('M'.($startRow+6), '=SUM(M'.($startRow+4).':M'.($startRow+5).')')
    ->setCellValue('N'.($startRow+6), '=SUM(N'.($startRow+4).':N'.($startRow+5).')')
    ->setCellValue('A'.($startRow+7), 'GRAND TOTAL')->mergeCells('A'.($startRow+7).':K'.($startRow+7))
    ->setCellValue('K'.($startRow+7), '=SUM(L'.(count($this->data_tanah_bangunan_new) + $offset0).',L'.($startRow+6).')')
    ->setCellValue('M'.($startRow+7), '=SUM(M'.(count($this->data_tanah_bangunan_new) + $offset0).',M'.($startRow+6).')')
    ->setCellValue('N'.($startRow+7), '=SUM(N'.(count($this->data_tanah_bangunan_new) + $offset0).',N'.($startRow+6).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'M', 'N', 'O', true);

    $this->style_table_header('A'.($startRow+6).':O'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header('A'.($startRow+7).':O'.($startRow+7), $this->color_black,
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // -- bagian 3
    $startRow += 9;
    $this->lampiran_table_header("DIPEROLEH SETELAH TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':K'.($startRow+6))
    ->setCellValue('L'.($startRow+6), '=SUM(L'.($startRow+4).':L'.($startRow+5).')')
    ->setCellValue('M'.($startRow+6), '=SUM(M'.($startRow+4).':M'.($startRow+5).')')
    ->setCellValue('N'.($startRow+6), '=SUM(N'.($startRow+4).':N'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'M', 'N', 'O');

    $this->style_table_header('A'.($startRow+6).':O'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 4
    $startRow += 8;
    $this->lampiran_table_header("PELEPASAN SEBELUM TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':K'.($startRow+6))
    ->setCellValue('L'.($startRow+6), '=SUM(L'.($startRow+4).':L'.($startRow+5).')')
    ->setCellValue('M'.($startRow+6), '=SUM(M'.($startRow+4).':M'.($startRow+5).')')
    ->setCellValue('N'.($startRow+6), '=SUM(N'.($startRow+4).':N'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'M', 'N', 'O');

    $this->style_table_header('A'.($startRow+6).':O'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(44);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(48);

  }

  public function cetak_2($id_lhkpn, $spreadsheet)
  {
    // Header Lampiran
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(20);
    $spreadsheet->getActiveSheet()->setTitle('2');

    $this->lampiran_header('LAMPIRAN 2. ALAT TRANSPORTASI DAN MESIN', $spreadsheet);
    $this->lampiran_table_header_2('DILAPORKAN', 8, $spreadsheet, '00008080');
    // list data bergerak
    // $data_harta_bergerak = $this->Kkp_model->get_data_bergerak($id_lhkpn);
    $startRow = 12;
    $this->set_col_numberFormat('N'.$startRow, 'Q'.($startRow + count($this->data_harta_bergerak_new) + 500), $spreadsheet);
    $offset0 = $startRow;
    $this->print_list_data($this->data_harta_bergerak_new, $startRow, $spreadsheet);
    $this->set_hasil_klarifikasi($startRow, $this->data_harta_bergerak_new, $spreadsheet, 'P');
    $this->print_koreksi($startRow, $this->data_harta_bergerak_new, $spreadsheet, 'O', 'P', 'Q');

    // TOTAL
    $startRow += count($this->data_harta_bergerak_new);
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, 'SUBTOTAL')
      ->mergeCells('A'.$startRow.':M'.$startRow);
    $last_row = $startRow-1;
    $spreadsheet->getActiveSheet()
    ->setCellValue('N'.$startRow, '=SUM(N'.$offset0.':N'.$last_row.')');
    $spreadsheet->getActiveSheet()
    ->setCellValue('O'.$startRow, '=SUM(O'.$offset0.':O'.$last_row.')');
    $spreadsheet->getActiveSheet()
    ->setCellValue('P'.$startRow, '=SUM(P'.$offset0.':P'.$last_row.')');

    $this->style_table_header(
      'A'.$startRow.':Q'.$startRow,
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- Bagian 2 :
    $startRow += 2;
    $this->lampiran_table_header_2('TIDAK DILAPORKAN', $startRow, $spreadsheet, '00632523');
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.($startRow+4), '1')
      ->setCellValue('A'.($startRow+5), '2')
      ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':M'.($startRow+6))
      ->setCellValue('N'.($startRow+6), '=SUM(N'.($startRow+4).':N'.($startRow+5).')')
      ->setCellValue('O'.($startRow+6), '=SUM(O'.($startRow+4).':O'.($startRow+5).')')
      ->setCellValue('P'.($startRow+6), '=SUM(P'.($startRow+4).':P'.($startRow+5).')')
      ->setCellValue('A'.($startRow+7), 'GRAND TOTAL')->mergeCells('A'.($startRow+7).':M'.($startRow+7))
      ->setCellValue('M'.($startRow+7), '=SUM(M'.(count($this->data_harta_bergerak_new) + $offset0).',M'.($startRow+6).')')
      ->setCellValue('N'.($startRow+7), '=SUM(N'.(count($this->data_harta_bergerak_new) + $offset0).',N'.($startRow+6).')')
      ->setCellValue('O'.($startRow+7), '=SUM(O'.(count($this->data_harta_bergerak_new) + $offset0).',O'.($startRow+6).')')
      ->setCellValue('P'.($startRow+7), '=SUM(P'.(count($this->data_harta_bergerak_new) + $offset0).',P'.($startRow+6).')');

    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'O', 'P', 'Q', true);

    $this->style_table_header('A'.($startRow+6).':Q'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header('A'.($startRow+7).':Q'.($startRow+7), $this->color_black,
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // -- bagian 3
    $startRow += 9;
    $this->lampiran_table_header_2("DIPEROLEH SETELAH TANGGAL LAPOR", $startRow, $spreadsheet, '00ffc000');
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.($startRow+4), '1')
      ->setCellValue('A'.($startRow+5), '2')
      ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':M'.($startRow+6))
      ->setCellValue('N'.($startRow+6), '=SUM(N'.($startRow+4).':N'.($startRow+5).')')
      ->setCellValue('O'.($startRow+6), '=SUM(O'.($startRow+4).':O'.($startRow+5).')')
      ->setCellValue('P'.($startRow+6), '=SUM(P'.($startRow+4).':P'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'O', 'P', 'Q');
    $this->style_table_header('A'.($startRow+6).':Q'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 4
    $startRow += 8;
    $this->lampiran_table_header_2("PELEPASAN SEBELUM TANGGAL LAPOR", $startRow, $spreadsheet, '00ffc000');
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.($startRow+4), '1')
      ->setCellValue('A'.($startRow+5), '2')
      ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':M'.($startRow+6))
      ->setCellValue('N'.($startRow+6), '=SUM(N'.($startRow+4).':N'.($startRow+5).')')
      ->setCellValue('O'.($startRow+6), '=SUM(O'.($startRow+4).':O'.($startRow+5).')')
      ->setCellValue('P'.($startRow+6), '=SUM(P'.($startRow+4).':P'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'O', 'P', 'Q');
    $this->style_table_header('A'.($startRow+6).':Q'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // styles
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(13);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(9);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(6);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(13);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(13);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(38);

  }

  public function cetak_3($id_lhkpn, $spreadsheet)
  {
    // Header Lampiran
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(21);
    $spreadsheet->getActiveSheet()->setTitle('3');

    $this->lampiran_header('LAMPIRAN 3. HARTA BERGERAK LAINNYA', $spreadsheet);
    $this->lampiran_table_header_3('DILAPORKAN', 8, $spreadsheet, '00008080');
    // $this->data_harta_bergerak_lainnya = $this->Kkp_model->get_data_bergerak_lainnya($id_lhkpn);

    $startRow = 12;
    $this->set_col_numberFormat('I'.$startRow, 'L'.($startRow + count($this->data_harta_bergerak_lainnya) + 500), $spreadsheet);
    // dd($this->data_harta_bergerak_lainnya_new);
    $offset0 = $startRow;
    $this->print_list_data($this->data_harta_bergerak_lainnya_new, $startRow, $spreadsheet);
    $this->set_hasil_klarifikasi($startRow, $this->data_harta_bergerak_lainnya_new, $spreadsheet, 'K');
    $this->print_koreksi($startRow, $this->data_harta_bergerak_lainnya_new, $spreadsheet, 'J', 'K', 'L');

    // TOTAL
    $startRow += count($this->data_harta_bergerak_lainnya_new);
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, 'SUBTOTAL')
      ->mergeCells('A'.$startRow.':H'.$startRow);
    $last_row = $startRow-1;
    $spreadsheet->getActiveSheet()
    ->setCellValue('I'.$startRow, '=SUM(I'.$offset0.':I'.$last_row.')')
    ->setCellValue('J'.$startRow, '=SUM(J'.$offset0.':J'.$last_row.')')
    ->setCellValue('K'.$startRow, '=SUM(K'.$offset0.':K'.$last_row.')');

    $this->style_table_header(
      'A'.$startRow.':L'.$startRow,
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 2
    $startRow += 2;
    $this->lampiran_table_header_3('TIDAK DILAPORKAN', $startRow, $spreadsheet, $this->color_red);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':H'.($startRow+6))
    ->setCellValue('I'.($startRow+6), '=SUM(I'.($startRow+4).':I'.($startRow+5).')')
    ->setCellValue('J'.($startRow+6), '=SUM(J'.($startRow+4).':J'.($startRow+5).')')
    ->setCellValue('K'.($startRow+6), '=SUM(K'.($startRow+4).':K'.($startRow+5).')')
    ->setCellValue('A'.($startRow+7), 'GRAND TOTAL')->mergeCells('A'.($startRow+7).':H'.($startRow+7))
    ->setCellValue('I'.($startRow+7), '=SUM(I'.(count($this->data_harta_bergerak_lainnya_new) + $offset0).',I'.($startRow+6).')')
    ->setCellValue('J'.($startRow+7), '=SUM(J'.(count($this->data_harta_bergerak_lainnya_new) + $offset0).',J'.($startRow+6).')')
    ->setCellValue('K'.($startRow+7), '=SUM(K'.(count($this->data_harta_bergerak_lainnya_new) + $offset0).',K'.($startRow+6).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'J', 'K', 'L', true);

    $this->style_table_header('A'.($startRow+6).':L'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header('A'.($startRow+7).':L'.($startRow+7), $this->color_black,
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // -- bagian 3
    $startRow += 9;
    $this->lampiran_table_header_3("DIPEROLEH SETELAH TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':H'.($startRow+6))
    ->setCellValue('I'.($startRow+6), '=SUM(I'.($startRow+4).',I'.($startRow+5).')')
    ->setCellValue('J'.($startRow+6), '=SUM(J'.($startRow+4).',J'.($startRow+5).')')
    ->setCellValue('K'.($startRow+6), '=SUM(K'.($startRow+4).',K'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'J', 'K', 'L');

    $this->style_table_header('A'.($startRow+6).':L'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian L
    $startRow += 8;
    $this->lampiran_table_header_3("PELEPASAN SEBELUM TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':H'.($startRow+6))
    ->setCellValue('I'.($startRow+6), '=SUM(I'.($startRow+4).',I'.($startRow+5).')')
    ->setCellValue('J'.($startRow+6), '=SUM(J'.($startRow+4).',J'.($startRow+5).')')
    ->setCellValue('K'.($startRow+6), '=SUM(K'.($startRow+4).',K'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'J', 'K', 'L');

    $this->style_table_header('A'.($startRow+6).':L'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // styles
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(27);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(9);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(40);
  }

  public function cetak_4($id_lhkpn, $spreadsheet)
  {
    // Header Lampiran
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(22);
    $spreadsheet->getActiveSheet()->setTitle('4');

    $this->lampiran_header('LAMPIRAN 4. SURAT BERHARGA', $spreadsheet);
    $this->lampiran_table_header_4('DILAPORKAN', 8, $spreadsheet, $this->color_green);
    // $this->data_surat_berharga = $this->Kkp_model->get_surat_berharga($id_lhkpn);
    $startRow = 12;
    $this->set_col_numberFormat('J'.$startRow, 'M'.($startRow + count($this->data_surat_berharga_new) + 500), $spreadsheet);

    $offset0 = $startRow;
    $this->print_list_data($this->data_surat_berharga_new, $startRow, $spreadsheet);
    $this->set_hasil_klarifikasi($startRow, $this->data_surat_berharga_new, $spreadsheet, 'L');
    $this->print_koreksi($startRow, $this->data_surat_berharga_new, $spreadsheet, 'K', 'L', 'M');

    // TOTAL
    $startRow += count($this->data_surat_berharga_new);
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, 'SUBTOTAL')
      ->mergeCells('A'.$startRow.':I'.$startRow);

    $last_row = $startRow-1;
    $spreadsheet->getActiveSheet()
      ->setCellValue('J'.$startRow, '=SUM(J'.$offset0.':J'.$last_row.')')
      ->setCellValue('K'.$startRow, '=SUM(K'.$offset0.':K'.$last_row.')')
      ->setCellValue('L'.$startRow, '=SUM(L'.$offset0.':L'.$last_row.')');

    $this->style_table_header(
      'A'.$startRow.':M'.$startRow,
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 2
    $startRow += 2;
    $this->lampiran_table_header_4('TIDAK DILAPORKAN', $startRow, $spreadsheet, $this->color_red);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':I'.($startRow+6))
    ->setCellValue('J'.($startRow+6), '=SUM(J'.($startRow+4).':J'.($startRow+5).')')
    ->setCellValue('K'.($startRow+6), '=SUM(K'.($startRow+4).':K'.($startRow+5).')')
    ->setCellValue('L'.($startRow+6), '=SUM(L'.($startRow+4).':L'.($startRow+5).')')
    ->setCellValue('A'.($startRow+7), 'GRAND TOTAL')->mergeCells('A'.($startRow+7).':I'.($startRow+7))
    ->setCellValue('J'.($startRow+7), '=SUM(J'.(count($this->data_surat_berharga_new) + $offset0).',J'.($startRow+6).')')
    ->setCellValue('K'.($startRow+7), '=SUM(K'.(count($this->data_surat_berharga_new) + $offset0).',K'.($startRow+6).')')
    ->setCellValue('L'.($startRow+7), '=SUM(L'.(count($this->data_surat_berharga_new) + $offset0).',L'.($startRow+6).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'K', 'L', 'M', true);

    $this->style_table_header('A'.($startRow+6).':M'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header('A'.($startRow+7).':M'.($startRow+7), $this->color_black,
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // -- bagian 3
    $startRow += 9;
    $this->lampiran_table_header_4("DIPEROLEH SETELAH TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':I'.($startRow+6))
    ->setCellValue('J'.($startRow+6), '=SUM(J'.($startRow+4).',J'.($startRow+5).')')
    ->setCellValue('K'.($startRow+6), '=SUM(K'.($startRow+4).',K'.($startRow+5).')')
    ->setCellValue('L'.($startRow+6), '=SUM(L'.($startRow+4).',L'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'K', 'L', 'M');

    $this->style_table_header('A'.($startRow+6).':M'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 4
    $startRow += 8;
    $this->lampiran_table_header_4("PELEPASAN SEBELUM TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':I'.($startRow+6))
    ->setCellValue('J'.($startRow+6), '=SUM(J'.($startRow+4).',J'.($startRow+5).')')
    ->setCellValue('K'.($startRow+6), '=SUM(K'.($startRow+4).',K'.($startRow+5).')')
    ->setCellValue('L'.($startRow+6), '=SUM(L'.($startRow+4).',L'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'K', 'L', 'M');

    $this->style_table_header('A'.($startRow+6).':M'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- styles
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(30);
  }

  public function cetak_5($id_lhkpn, $spreadsheet)
  {
    // Header Lampiran
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(23);
    $spreadsheet->getActiveSheet()->setTitle('5');

    $this->lampiran_header('LAMPIRAN 5. KAS DAN SETARA KAS', $spreadsheet);
    $this->lampiran_table_header_5a('DILAPORKAN', 8, $spreadsheet, $this->color_green);
    // $this->data_kas = $this->Kkp_model->get_data_kas($id_lhkpn);
    $startRow = 12;
    $this->set_col_numberFormat('K'.$startRow, 'M'.($startRow + count($this->data_kas_new) + 500), $spreadsheet);

    $offset0 = $startRow;
    $this->print_list_data($this->data_kas_new, $startRow, $spreadsheet);
    $this->set_hasil_klarifikasi($startRow, $this->data_kas_new, $spreadsheet, 'L');
    $this->print_koreksi($startRow, $this->data_kas_new, $spreadsheet, 'K', 'L', 'M');
    // TOTAL
    $startRow += count($this->data_kas_new);
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, 'SUBTOTAL')
      ->mergeCells('A'.$startRow.':J'.$startRow);
    $last_row = $startRow-1;
    $spreadsheet->getActiveSheet()
    ->setCellValue('K'.$startRow, '=SUM(K'.$offset0.':K'.$last_row.')')
    ->setCellValue('L'.$startRow, '=SUM(L'.$offset0.':L'.$last_row.')');

    $this->style_table_header(
      'A'.$startRow.':M'.$startRow,
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 2
    $startRow += 2;
    $this->lampiran_table_header_5b('TIDAK DILAPORKAN', $startRow, $spreadsheet, $this->color_red);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':J'.($startRow+6))
    ->setCellValue('K'.($startRow+6), '=SUM(K'.($startRow+4).',K'.($startRow+5).')')
    ->setCellValue('L'.($startRow+6), '=SUM(L'.($startRow+4).',L'.($startRow+5).')')
    ->setCellValue('A'.($startRow+7), 'GRAND TOTAL')->mergeCells('A'.($startRow+7).':J'.($startRow+7))
    ->setCellValue('K'.($startRow+7), '=SUM(K'.(count($this->data_kas_new) + $offset0).',K'.($startRow+6).')')
    ->setCellValue('L'.($startRow+7), '=SUM(L'.(count($this->data_kas_new) + $offset0).',L'.($startRow+6).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'K', 'L', 'M', true);

    $this->style_table_header('A'.($startRow+6).':M'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header('A'.($startRow+7).':M'.($startRow+7), $this->color_black,
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // -- bagian 3
    $startRow += 9;
    $this->lampiran_table_header_5b("DIPEROLEH SETELAH TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':J'.($startRow+6))
    ->setCellValue('K'.($startRow+6), '=SUM(K'.($startRow+4).':K'.($startRow+5).')')
    ->setCellValue('L'.($startRow+6), '=SUM(L'.($startRow+4).':L'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'K', 'L', 'M');

    $this->style_table_header('A'.($startRow+6).':M'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 4
    $startRow += 8;
    $this->lampiran_table_header_5b("PELEPASAN SEBELUM TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':J'.($startRow+6))
    ->setCellValue('K'.($startRow+6), '=SUM(K'.($startRow+4).':K'.($startRow+5).')')
    ->setCellValue('L'.($startRow+6), '=SUM(L'.($startRow+4).':L'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'K', 'L', 'M');

    $this->style_table_header('A'.($startRow+6).':M'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    //styles
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(19);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(30);
    $spreadsheet->getActiveSheet()->getStyle('E1:E97')->getNumberFormat()->setFormatCode('@'); //format number is text

    
  }

  public function cetak_6($id_lhkpn, $spreadsheet)
  {
    // Header Lampiran
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(24);
    $spreadsheet->getActiveSheet()->setTitle('6');

    $this->lampiran_header('LAMPIRAN 6. HARTA LAINNYA', $spreadsheet);
    $this->lampiran_table_header_6('DILAPORKAN', 8, $spreadsheet, $this->color_green);
    // $this->data_harta_lainnya = $this->Kkp_model->get_harta_lainnya($id_lhkpn);

    $startRow = 12;
    $this->set_col_numberFormat('G'.$startRow, 'J'.($startRow + count($this->data_harta_lainnya_new) + 500), $spreadsheet);

    $offset0 = $startRow;
    $this->print_list_data($this->data_harta_lainnya_new, $startRow, $spreadsheet);
    $this->set_hasil_klarifikasi($startRow, $this->data_harta_lainnya_new, $spreadsheet, 'I');
    $this->print_koreksi($startRow, $this->data_harta_lainnya_new, $spreadsheet, 'H', 'I', 'J');
    // TOTAL
    $startRow += count($this->data_harta_lainnya_new);
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, 'SUBTOTAL')
      ->mergeCells('A'.$startRow.':F'.$startRow);
    $last_row = $startRow-1;
    $spreadsheet->getActiveSheet()
    ->setCellValue('G'.$startRow, '=SUM(G'.$offset0.':G'.$last_row.')')
    ->setCellValue('H'.$startRow, '=SUM(H'.$offset0.':H'.$last_row.')')
    ->setCellValue('I'.$startRow, '=SUM(I'.$offset0.':I'.$last_row.')');

    $this->style_table_header(
      'A'.$startRow.':J'.$startRow,
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 2
    $startRow += 2;
    $this->lampiran_table_header_6('TIDAK DILAPORKAN', $startRow, $spreadsheet, $this->color_red);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':F'.($startRow+6))
    ->setCellValue('G'.($startRow+6), '=SUM(G'.($startRow+4).':G'.($startRow+5).')')
    ->setCellValue('H'.($startRow+6), '=SUM(H'.($startRow+4).':H'.($startRow+5).')')
    ->setCellValue('I'.($startRow+6), '=SUM(I'.($startRow+4).':I'.($startRow+5).')')
    ->setCellValue('A'.($startRow+7), 'GRAND TOTAL')->mergeCells('A'.($startRow+7).':F'.($startRow+7))
    ->setCellValue('G'.($startRow+7), '=SUM(G'.(count($this->data_harta_lainnya_new) + $offset0).',G'.($startRow+6).')')
    ->setCellValue('H'.($startRow+7), '=SUM(H'.(count($this->data_harta_lainnya_new) + $offset0).',H'.($startRow+6).')')
    ->setCellValue('I'.($startRow+7), '=SUM(I'.(count($this->data_harta_lainnya_new) + $offset0).',I'.($startRow+6).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'H', 'I', 'J',  true);

    $this->style_table_header('A'.($startRow+6).':J'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header('A'.($startRow+7).':J'.($startRow+7), $this->color_black,
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // -- bagian 3
    $startRow += 9;
    $this->lampiran_table_header_6("DIPEROLEH SETELAH TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':F'.($startRow+6))
    ->setCellValue('G'.($startRow+6), '=SUM(G'.($startRow+4).':G'.($startRow+5).')')
    ->setCellValue('H'.($startRow+6), '=SUM(H'.($startRow+4).':H'.($startRow+5).')')
    ->setCellValue('I'.($startRow+6), '=SUM(I'.($startRow+4).':I'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'H', 'I', 'J');

    $this->style_table_header('A'.($startRow+6).':J'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 4
    $startRow += 8;
    $this->lampiran_table_header_6("PELEPASAN SEBELUM TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':F'.($startRow+6))
    ->setCellValue('G'.($startRow+6), '=SUM(G'.($startRow+4).':G'.($startRow+5).')')
    ->setCellValue('H'.($startRow+6), '=SUM(H'.($startRow+4).':H'.($startRow+5).')')
    ->setCellValue('I'.($startRow+6), '=SUM(I'.($startRow+4).':I'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'H', 'I', 'J');

    $this->style_table_header('A'.($startRow+6).':J'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    //styles
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(37);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(19);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(30);
  }

  public function cetak_7($id_lhkpn, $spreadsheet)
  {
    // Header Lampiran
    // echo "------------------------------- sheet 7 ---<br>";

    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(25);
    $spreadsheet->getActiveSheet()->setTitle('7');

    $this->lampiran_header('LAMPIRAN 7. HUTANG', $spreadsheet);
    $this->lampiran_table_header_7('DILAPORKAN', 8, $spreadsheet, $this->color_green);
    // $this->data_hutang = $this->Kkp_model->get_data_hutang($id_lhkpn);
    $startRow = 12;
    $this->set_col_numberFormat('F'.$startRow, 'I'.($startRow + count($this->data_hutang) + 500), $spreadsheet);

    $offset0 = $startRow;
    // echo "<pre>";
    // print_r($this->data_hutang);
    // echo "</pre>";
    // die();

    $this->print_list_data($this->data_hutang, $startRow, $spreadsheet);
    // echo "--------------- list data printed, sheet 7 --- ";
    // die();

    $this->set_hasil_klarifikasi($startRow, $this->data_tanah_bangunan, $spreadsheet, 'H');
    $this->print_koreksi($startRow, $this->data_hutang, $spreadsheet, 'G', 'H', 'I');
    // TOTAL
    $startRow += count($this->data_hutang);
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, 'SUBTOTAL')
      ->mergeCells('A'.$startRow.':E'.$startRow);

    $last_row = $startRow-1;
    $spreadsheet->getActiveSheet()
    ->setCellValue('F'.$startRow, '=SUM(F'.$offset0.':F'.$last_row.')')
    ->setCellValue('G'.$startRow, '=SUM(G'.$offset0.':G'.$last_row.')')
    ->setCellValue('H'.$startRow, '=SUM(H'.$offset0.':H'.$last_row.')');

    $this->style_table_header(
      'A'.$startRow.':I'.$startRow,
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 2
    $startRow += 2;
    $this->lampiran_table_header_7('TIDAK DILAPORKAN', $startRow, $spreadsheet, $this->color_red);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':E'.($startRow+6))
    ->setCellValue('F'.($startRow+6), '=SUM(F'.($startRow+4).':F'.($startRow+5).')')
    ->setCellValue('G'.($startRow+6), '=SUM(G'.($startRow+4).':G'.($startRow+5).')')
    ->setCellValue('H'.($startRow+6), '=SUM(H'.($startRow+4).':H'.($startRow+5).')')
    ->setCellValue('A'.($startRow+7), 'GRAND TOTAL')->mergeCells('A'.($startRow+7).':E'.($startRow+7))
    ->setCellValue('F'.($startRow+7), '=SUM(F'.(count($this->data_hutang) + $offset0).',F'.($startRow+6).')')
    ->setCellValue('G'.($startRow+7), '=SUM(G'.(count($this->data_hutang) + $offset0).',G'.($startRow+6).')')
    ->setCellValue('H'.($startRow+7), '=SUM(H'.(count($this->data_hutang) + $offset0).',H'.($startRow+6).')');

    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'G', 'H', 'I',  true);

    $this->style_table_header('A'.($startRow+6).':I'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header('A'.($startRow+7).':I'.($startRow+7), $this->color_black,
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // -- bagian 3
    $startRow += 9;
    $this->lampiran_table_header_7("DIPEROLEH SETELAH TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':E'.($startRow+6))
    ->setCellValue('F'.($startRow+6), '=SUM(F'.($startRow+4).':F'.($startRow+5).')')
    ->setCellValue('G'.($startRow+6), '=SUM(G'.($startRow+4).':G'.($startRow+5).')')
    ->setCellValue('H'.($startRow+6), '=SUM(H'.($startRow+4).':H'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'G', 'H', 'I');

    $this->style_table_header('A'.($startRow+6).':I'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian 4
    $startRow += 8;
    $this->lampiran_table_header_7("PELUNASAN SEBELUM TANGGAL LAPOR", $startRow, $spreadsheet, $this->color_yellow);
    $spreadsheet->getActiveSheet()
    ->setCellValue('A'.($startRow+4), '1')
    ->setCellValue('A'.($startRow+5), '2')
    ->setCellValue('A'.($startRow+6), 'SUBTOTAL')->mergeCells('A'.($startRow+6).':E'.($startRow+6))
    ->setCellValue('F'.($startRow+6), '=SUM(F'.($startRow+4).':F'.($startRow+5).')')
    ->setCellValue('G'.($startRow+6), '=SUM(G'.($startRow+4).':G'.($startRow+5).')')
    ->setCellValue('H'.($startRow+6), '=SUM(H'.($startRow+4).':H'.($startRow+5).')');
    // koreksi -- use dummy array
    $this->print_koreksi($startRow+4, ['1', '2'], $spreadsheet, 'G', 'H', 'I');

    $this->style_table_header('A'.($startRow+6).':I'.($startRow+6), '',
    $spreadsheet,
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    // -- bagian pernyataan
    $startRow += 8;
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.($startRow), 'Demikian seluruh rincian Harta Kekayaan, Penerimaan dan Pengeluaran serta has
      il Klarifikasi LHKPN atas: ')
      ->setCellValue('B'.($startRow+2), 'Nama')->setCellValue('C'.($startRow+2), '=": "&PROFIL!D7')
      ->setCellValue('B'.($startRow+3), 'Jabatan')->setCellValue('C'.($startRow+3), '=": "&PROFIL!D22')
      ->setCellValue('A'.($startRow+5), 'dibuat, diketahui dan disetujui oleh yang bersangkutan')
      ->setCellValue('A'.($startRow+7), '="Jakarta, "&TEXT(PROFIL!M29,"DD MMMM YYYY")')
      ->setCellValue('A'.($startRow+9), 'Penyelenggara Negara')
      ->setCellValue('A'.($startRow+12), '=PROFIL!D7')
      ->setCellValue('D'.($startRow+9), 'Tim Klarifikasi')
      ->setCellValue('D'.($startRow+12), '=PROFIL!D36');
      // ->setCellValue('F'.($startRow+12), '=PROFIL!M28');

    //styles
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(29);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(37);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(19);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);
  }

  public function cetak_8($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(26);
    $spreadsheet->getActiveSheet()->setTitle('8');

    $this->lampiran_header('LAMPIRAN 8. INFORMASI PENERIMAAN TUNAI', $spreadsheet);
    $this->lampiran_table_header_8('', 8, $spreadsheet, $this->color_green);
    // pendapatan PN
    $this->print_subtotal_8($spreadsheet, 14, 9, 5);
    $this->print_subtotal_8($spreadsheet, 21, 16, 5);
    $this->print_subtotal_8($spreadsheet, 28, 23, 4);

    $this->set_col_numberFormat('C8', 'K50', $spreadsheet);


    // total penerimaan A+B+C
    $spreadsheet->getActiveSheet()
      ->setCellValue('B33','TOTAL PENERIMAAN  (A + B + C)')
      ->setCellValue('C33','=C19+C26+C32')
      ->setCellValue('D33','=D19+C26+C32')
      ->setCellValue('E33','=E19+E26+E32')
      ->setCellValue('F33','=F19+F26+F32')
      ->setCellValue('G33','=G19+G26+G32')
      ->setCellValue('H33','=H19+H26+H32')
      ->setCellValue('I33','=I19+I26+I32')
      ->setCellValue('J33','=J19+J26+J32')
      ->setCellValue('K33','=K19+K26+K32');

    //styles
    $this->style_table_header(
      'A19:K19',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A26:K26',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A32:K32',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A33:K33',
      $this->color_black,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(37);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(14);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(24);

  }

  public function cetak_9($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(27);
    $spreadsheet->getActiveSheet()->setTitle('9');

    $this->lampiran_header('LAMPIRAN 9. INFORMASI PENGELUARAN TUNAI', $spreadsheet);
    $this->lampiran_table_header_9('', 7, $spreadsheet, $this->color_green);

    $spreadsheet->getActiveSheet()
      ->setCellValue('A11','A. Pengeluaran Rutin')
      ->setCellValue('A12','1')
      ->setCellValue('B12','Biaya rumah tangga (termasuk transportasi, pendidikan, kesehatan, rekreasi, pembayaran kartu kredit)' )
      ->setCellValue('A13','2')
      ->setCellValue('B13','Biaya sosial (keagamaan, adat, zakat, infaq, sumbangan lain)' )
      ->setCellValue('A14','3')
      ->setCellValue('B14','Pembayaran Pajak (PBB, kendaraan, pajak daerah, pajak lain)' )
      ->setCellValue('A15','4')
      ->setCellValue('B15','Pengeluaran rutin lainnya' )
      ->setCellValue('B16','SUB TOTAL' )
      ->setCellValue('A17','B. Pengeluaran Non-Rutin')
      ->setCellValue('A18','1' )
      ->setCellValue('B18','Pembelian/perolehan harta baru' )
      ->setCellValue('A19','2' )
      ->setCellValue('B19','Pemeliharaan/modifikasi/rehabilitasi harta' )
      ->setCellValue('A20','3' )
      ->setCellValue('B20','Pengeluaran non-rutin lainnya' )
      ->setCellValue('B21','SUB TOTAL' )
      ->setCellValue('A22','C. Pengeluaran Lainnya')
      ->setCellValue('A23','1' )
      ->setCellValue('B23','Biaya pengurusan waris/hibah/hadiah' )
      ->setCellValue('A24','2' )
      ->setCellValue('B24','Pelunasan/angsuran hutang' )
      ->setCellValue('A25','3' )
      ->setCellValue('B25','Pengeluaran Lainnya' )
      ->setCellValue('B26','SUB TOTAL' )
      ->setCellValue('B27','TOTAL PENGELUARAN  (A + B + C)' )
      ->setCellValue('C27','=C16+C21+C26' )
      ->setCellValue('D27','=D16+D21+D26' );

    // copy the content from ix
    $startRow_disini = 11;
    $startRow_disana = 7;
    for ($i=0; $i <= 16; $i++) {
      $spreadsheet->getActiveSheet()
        ->setCellValue('C'.($startRow_disini+$i), '=IX!C'.($startRow_disana+$i))
        ->setCellValue('E'.($startRow_disini+$i), '=D'.($startRow_disini+$i).'-C'.($startRow_disini+$i)); // koreksi
    }

    // hasil klarifikasi subtotal & total
    $spreadsheet->getActiveSheet()
      ->setCellValue('D16', '=SUM(D12:D15)')
      ->setCellValue('D21', '=SUM(D18:D20)')
      ->setCellValue('D26', '=SUM(D23:D25)');

    //clean koreksi on title
    $spreadsheet->getActiveSheet()
      ->setCellValue('E11', '')
      ->setCellValue('E17', '')
      ->setCellValue('E22', '');

    // styles
    $this->style_table_header(
      'A16:E16',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A21:E21',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A26:E26',
      '',
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $this->style_table_header(
      'A27:E27',
      $this->color_black,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    $this->set_col_numberFormat('C8', 'F50', $spreadsheet);


    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(50);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
  }

  public function cetak_CIA($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(29);
    $spreadsheet->getActiveSheet()->setTitle('CIA');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')->mergeCells('A1:D1')
      ->setCellValue('A4', 'CONCEALED INCOME ANALYSIS (CIA)');

    $tgl_lapor_sebelumnya = " - ";
    $tgl_lapor = " - ";
  
    if(!$this->var_is_null($this->data_pn)){
        $data_pn = $this->data_pn[0];
        $tgl_lapor = $data_pn->tgl_lapor;
        
        $where = [
          'T_LHKPN.id_pn' => $data_pn->ID_PN,
          'T_LHKPN.is_active' => 1,
          'T_LHKPN.tgl_lapor <' => $tgl_lapor,
          'T_LHKPN.jenis_laporan <>' => 5,
        ];

        $order = [
          'T_LHKPN.id_lhkpn DESC'
        ];
        
        $tgl_lapor_prev = $this->mglobal->get_data_all("T_LHKPN", NULL, $where, 'tgl_lapor', NULL, $order, 0, 1)[0]->tgl_lapor;
        $tgl_lapor_sebelumnya = is_null($tgl_lapor_prev)?'-':date('d M Y', strtotime($tgl_lapor_prev));
    }    
      
      //TABLE HEADER
      $spreadsheet->getActiveSheet()
      ->setCellValue('A5', 'Dana Tersedia')->mergeCells('A5:A6')
      ->setCellValue('B5', 'Menurut PN')->mergeCells('B5:B6')
      ->setCellValue('C5', 'Hasil Klarifikasi')->mergeCells('C5:C6')
      ->setCellValue('E5', 'Dana Digunakan')->mergeCells('E5:E6')
      ->setCellValue('F5', 'Menurut PN')->mergeCells('F5:F6')
      ->setCellValue('G5', 'Hasil Klarifikasi')->mergeCells('G5:G6');

      //TABLE BODY
      $spreadsheet->getActiveSheet()
      ->setCellValue('A8', 'Saldo Kas dan Setara Kas per '.$tgl_lapor_sebelumnya.' ')
      ->setCellValue('E8', 'Saldo Kas dan Setara Kas per '.date('d M Y', strtotime($tgl_lapor)).'')
      ->setCellValue('F8', '=BAK!E36')
      ->setCellValue('G8', '=BAK!F36')
      ->setCellValue('A9', 'Penghasilan dari Pekerjaan Pegawai dan pasangan')
      ->setCellValue('B9', '=8!E19')
      ->setCellValue('C9', '=8!H19')
      ->setCellValue('E9', 'Pengeluaran Rutin')
      ->setCellValue('F9', '=9!C16')
      ->setCellValue('G9', '=9!D16')
      ->setCellValue('A10', 'Penghasilan dari Usaha dan Kekayaan Pegawai dan pasangan')
      ->setCellValue('B10', '=8!E26')
      ->setCellValue('C10', '=8!H26')
      ->setCellValue('E10', 'Pengeluaran Harta dan Non Rutin Lainnya')
      ->setCellValue('F10', '=9!C21')
      ->setCellValue('G10', '=9!D21')
      ->setCellValue('A11', 'Penghasilan Lainnya Pegawai dan pasangan')
      ->setCellValue('B11', '=8!E32')
      ->setCellValue('C11', '=8!H32')
      ->setCellValue('E11', 'Pengeluaran Lainnya')
      ->setCellValue('F11', '=9!C26')
      ->setCellValue('G11', '=9!D26')
      ->setCellValue('A13', 'Total Dana Tersedia')
      ->setCellValue('B13', '=SUM(B8:B11)')
      ->setCellValue('C13', '=SUM(C8:C11)')
      ->setCellValue('E13', 'Total Dana Digunakan')
      ->setCellValue('F13', '=SUM(F8:F11)')
      ->setCellValue('G13', '=SUM(G8:G11)');

      $spreadsheet->getActiveSheet()
      ->setCellValue('A15', 'CONCEALED INCOME ANALYSIS')->mergeCells('A15:C15')
      ->setCellValue('A16', 'Total Dana yang tersedia')
      ->setCellValue('B16', '=B13')
      ->setCellValue('C16', '=C13')
      ->setCellValue('A17', 'Total Dana yang digunakan')
      ->setCellValue('B17', '=F13')
      ->setCellValue('C17', '=G13')
      ->setCellValue('A18', 'SURPLUS/DEFISIT')
      ->setCellValue('B18', '=B16-B17')
      ->setCellValue('C18', '=C16-C17');

      $spreadsheet->getActiveSheet()
      ->setCellValue('E15', '*) Indikasi surplus menunjukkan kemungkinan adanya dana yang belum jelas penggunaanya atau terdapat harta yang belum dilaporkan.
*) Indikasi defisit menunjukkan kemungkinan adanya penghasilan yang belum dilaporkan atau pengeluaran yang belum jelas sumber dananya.
      ')->mergeCells('E15:G18')
      ->setCellValue('A20', 'Isian CIA berdasarkan LHKPN :')
      ->setCellValue('A21', 'Surplus karena PN tidak mencantumkan hasil penjualan kedalam kolom penghasilan')->mergeCells('A21:C21')
      ->setCellValue('A23', 'Isian CIA berdasarkan hasil Klarifikasi :')
      ->setCellValue('A24', 'Defisit karena PN tidak mencantumkan penerimaan dari usaha kekayaan atau tidak mencantumkan perolehan harta baru')->mergeCells('A24:F24');
    
    $this->set_col_numberFormat('B9', 'C18', $spreadsheet);     
    $this->set_col_numberFormat('F8', 'G13', $spreadsheet);     
    $spreadsheet->getActiveSheet()->getStyle("E15")->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('E15')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $spreadsheet->getActiveSheet()->getStyle('E15')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

    $cell_header_arr = ['A5:C6','A15','E5:G6'];

    foreach($cell_header_arr as $cell_header){
      $this->style_table_header($cell_header, $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    }
   
    $this->style_table_header('A18', $this->color_brown, $spreadsheet, 
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    $this->style_table_header('B18:C18', $this->color_brown, $spreadsheet, 
    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    $spreadsheet->getActiveSheet()->getStyle("A8:A11")->getAlignment()->setWrapText(true);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(2);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);

    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle("A13:F13")->getFont()->setBold(true);

    $spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->setSize(12);
    $spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->getColor()->setARGB($this->color_blue);

  }

  public function cetak_PROFIL_INDIV($id_lhkpn, $spreadsheet){
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(32);
    $spreadsheet->getActiveSheet()->setTitle('PROFIL INDIV');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')->mergeCells('A1:D1')
      ->setCellValue('A3', 'PROFIL PIHAK TERKAIT TRANSAKSI, HARTA & KEUANGAN');

    $spreadsheet->getActiveSheet()
    ->setCellValue('A4', '(Foto)')->mergeCells('A4:A9')
    ->setCellValue('B4', 'Nama')
    ->setCellValue('C4', ':')
    ->setCellValue('D4', '')
    ->setCellValue('B5', 'NIK')
    ->setCellValue('C5', ':')
    ->setCellValue('D5', '')
    ->setCellValue('B6', 'Tempat, Tanggal Lahir')
    ->setCellValue('C6', ':')
    ->setCellValue('D6', '')
    ->setCellValue('B7', 'Pekerjaan')
    ->setCellValue('C7', ':')
    ->setCellValue('D7', '')
    ->setCellValue('B8', 'Ibu Kandung')
    ->setCellValue('C8', ':')
    ->setCellValue('D8', '')
    ->setCellValue('B9', 'Alamat')
    ->setCellValue('C9', ':')
    ->setCellValue('D9', '');

    $spreadsheet->getActiveSheet()
    ->setCellValue('A10', 'Kaitan penerima/penyetor dana dengan PN :')->mergeCells('A10:D10')
    ->setCellValue('A11', 'Informasi Lainnya :')->mergeCells('A11:D12');

    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle('A4')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A4')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A11')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(3);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);

    $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setSize(12);
    $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->getColor()->setARGB($this->color_blue);

    $spreadsheet->getActiveSheet()->getStyle("B4:B9")->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle("A10:B12")->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle("A4:A9")->getFont()->setItalic(true);

    $styleArray = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => $this->color_black],
        ],
      ]
    ];

    $spreadsheet->getActiveSheet()->getStyle('A4:D12')->applyFromArray($styleArray);
  }

  public function cetak_PROFIL_BAHU($id_lhkpn, $spreadsheet){
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(33);
    $spreadsheet->getActiveSheet()->setTitle('PROFIL BAHU');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')->mergeCells('A1:D1')
      ->setCellValue('A3', 'PROFIL BADAN HUKUM');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4', 'No.')
      ->setCellValue('B4', 'Nama Perseroan')
      ->setCellValue('C4', 'Bidang Usaha')
      ->setCellValue('D4', 'No Akta')
      ->setCellValue('E4', 'Nama Notaris')
      ->setCellValue('F4', 'Alamat Perseroan')
      ->setCellValue('G4', 'Jumlah Saham (lbr)')
      ->setCellValue('H4', 'Jumlah Penyertaan (Rp)')
      ->setCellValue('I4', 'Nama Pengurus')
      ->setCellValue('J4', 'Jabatan')
      ->setCellValue('K4', 'Keterangan');

    $this->style_table_header(
      'A4:K4',
      $this->color_gray,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    
      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(18);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(12);

      $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

      $spreadsheet->getActiveSheet()->getStyle('A4:K4')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('A4:K4')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

      $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setSize(12);
      $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
      $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->getColor()->setARGB($this->color_blue);

      $styleArray = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => $this->color_black],
          ],
        ]
      ];
  
      $spreadsheet->getActiveSheet()->getStyle('A4:K9')->applyFromArray($styleArray);
  }

  public function cetak_STR($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(30);
    $spreadsheet->getActiveSheet()->setTitle('STR');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')->mergeCells('A1:D1')
      ->setCellValue('A3', 'SUSPICIOUS TRANSACTION REPORT (STR)');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4', 'No.')->mergeCells('A4:A5')
      ->setCellValue('B4', 'Nama Bank')->mergeCells('B4:B5')
      ->setCellValue('C4', 'Nomor Rekening')->mergeCells('C4:C5')
      ->setCellValue('D4', 'Pemilik Rekening')->mergeCells('D4:D5')
      ->setCellValue('E4', 'Periode')->mergeCells('E4:F5')
      ->setCellValue('G4', 'Transaksi')->mergeCells('G4:J4')
      ->setCellValue('G5', 'Debit')
      ->setCellValue('H5', 'Frek')
      ->setCellValue('I5', 'Kredit')
      ->setCellValue('J5', 'Frek')
      ->setCellValue('K5', 'Keterangan');

    $spreadsheet->getActiveSheet()
      ->setCellValue('F15', 'Total')
      ->setCellValue('G15', '=SUM(G6:G14)')
      ->setCellValue('H15', '=SUM(H6:H14)')
      ->setCellValue('I15', '=SUM(I6:I14)')
      ->setCellValue('J15', '=SUM(J6:J14)');

      $this->style_table_header(
        'A4:K5',
        $this->color_green,
        $spreadsheet);
      $this->style_table_header(
        'A15:K15',
        $this->color_lightyellow,
        $spreadsheet,
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(16);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(16);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(27);

      $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

      $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setSize(12);
      $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
      $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->getColor()->setARGB($this->color_blue);
  }

  public function cetak_TRX($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(31);
    $spreadsheet->getActiveSheet()->setTitle('TRX');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')->mergeCells('A1:D1')
      ->setCellValue('A4', 'DAFTAR TRANSAKSI');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A5', 'No.')
      ->setCellValue('B5', 'Nama Bank')
      ->setCellValue('C5', 'Nomor Rekening')
      ->setCellValue('D5', 'Pemilik Rekening')
      ->setCellValue('E5', 'Tanggal')
      ->setCellValue('F5', 'Uraian')
      ->setCellValue('G5', 'Mata Uang')
      ->setCellValue('H5', 'D/K')
      ->setCellValue('I5', 'Nilai')
      ->setCellValue('J5', 'Saldo')
      ->setCellValue('K5', 'Jenis Transaksi')
      ->setCellValue('L5', 'Nama Penerima / Pengirim')
      ->setCellValue('M5', 'Peruntukkan')
      ->setCellValue('N5', 'Voucher (Y/N)')
      ->setCellValue('O5', 'STR (Y/N)');

    $this->style_table_header('A5:O5', $this->color_green, $spreadsheet);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(28);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(11);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(23);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(25);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(10);

    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(12);
    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

    $spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->setSize(12);
    $spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->getColor()->setARGB($this->color_blue);
  }

  public function cetak_Lamp1($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(34);
    $spreadsheet->getActiveSheet()->setTitle('Lamp1 Jual Lepas');

    $spreadsheet->getActiveSheet()
    ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')->mergeCells('A1:D1');

    $this->template_table_header_lamp1('PENJUALAN/PELEPASAN HARTA DAN PENERIMAAN/PEMBERIAN HIBAH DALAM SETAHUN', 3, $spreadsheet, $this->color_green );
    $this->template_table_header_lamp1('HASIL KLARIFIKASI', 11, $spreadsheet, $this->color_green );

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(16);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(27);

    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
  }

  public function cetak_Lamp2($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(35);
    $spreadsheet->getActiveSheet()->setTitle('Lamp2 Fasilitas');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'KERTAS KERJA PEMERIKSAAN')->mergeCells('A1:D1')
      ->setCellValue('A3', 'INFORMASI PENERIMAAN FASILITAS/BENEFIT DALAM SETAHUN');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A4', 'No.')->mergeCells('A4:A6')
      ->setCellValue('B4', 'URAIAN')->mergeCells('B4:B6')
      ->setCellValue('C4', 'Menurut PN')
      ->setCellValue('C5', '=PROFIL!D4')
      ->setCellValue('C6', '=PROFIL!D6');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A7', '1')->setCellValue('B7', 'Jenis')
      ->setCellValue('A8', '2')->setCellValue('B8', 'Keterangan')
      ->setCellValue('A9', '3')->setCellValue('B9', 'Nama Pihak Pemberi Fasilitas')
      ->setCellValue('A10', '4')->setCellValue('B10', 'Keterangan');

    $this->style_table_header(
      'A4:C6',
      $this->color_green,
      $spreadsheet);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(32);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(36);

    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

    $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setSize(12);
    $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle("A3")->getFont()->getColor()->setARGB($this->color_blue);
  }

  public function cetak_CL($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(36);
    $spreadsheet->getActiveSheet()->setTitle('CL');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A2', 'DAFTAR PEMERIKSAAN LAPANGAN ')->mergeCells('A2:AM2');
    $spreadsheet->getActiveSheet()->getStyle("A2")->getFont()->setSize(22);
    $spreadsheet->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle("A2")->getFont()->getColor()->setARGB($this->color_blue);
    $spreadsheet->getActiveSheet()->getStyle('A2')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $startRow = 5;
    $this->lampiran_row_CL(5, $spreadsheet, 1);
    $this->lampiran_row_CL(20, $spreadsheet, 2);
    $this->lampiran_row_CL(35, $spreadsheet, 3);


    // format size
    foreach (range('A', 'Z') as $col) {
      $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth(4);
    }
    foreach (range('A', 'Y') as $col) {
      $spreadsheet->getActiveSheet()->getColumnDimension('A'.$col)->setWidth(4);
    }

  }

  public function cetak_cashflow($id_lhkpn, $spreadsheet)
  {
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(28);
    $spreadsheet->getActiveSheet()->setTitle('Cashflow');

    $nama_pn =  $this->cek_is_null($this->data_pn[0]->NAMA_LENGKAP);
    $jabatan_pn = $this->cek_is_null($this->data_pn[0]->NAMA_JABATAN);
    $instansi_pn = $this->cek_is_null($this->data_pn[0]->INST_NAMA);

    $spreadsheet->getActiveSheet()
      ->setCellValue('A1', 'PERHITUNGAN ARUS KAS')->mergeCells('A1:K1')
      ->setCellValue('A2', 'ATAS NAMA '.$nama_pn.' ('.$jabatan_pn.', '.$instansi_pn.')')->mergeCells('A2:K2');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B4', 'TAHUN')
      ->setCellValue('C4', '2012')
      ->setCellValue('D4', '2013')
      ->setCellValue('E4', '2014')
      ->setCellValue('F4', '2015')
      ->setCellValue('G4', '2016')
      ->setCellValue('H4', '2017')
      ->setCellValue('I4', '2018')
      ->setCellValue('J4', '2019')
      ->setCellValue('K4', 'Penjelasan');
    
      $spreadsheet->getActiveSheet()
      ->setCellValue('B5', 'Saldo Awal')
      ->setCellValue('C5', '0')
      ->setCellValue('D5', '=C27')
      ->setCellValue('E5', '=D27')
      ->setCellValue('F5', '=E27')
      ->setCellValue('G5', '=F27')
      ->setCellValue('H5', '=G27')
      ->setCellValue('I5', '=H27')
      ->setCellValue('J5', '=I27')
      ->setCellValue('B7', 'PENGHASILAN')
      ->setCellValue('A9', '1.')
      ->setCellValue('B9', 'Gaji')
      ->setCellValue('A10', '2.')
      ->setCellValue('B10', 'Honor kegiatan')
      ->setCellValue('A11', '3')
      ->setCellValue('B11', 'Tunjangan jabatan/Tunjangan Kinerja')
      ->setCellValue('A12', '4.')
      ->setCellValue('B12', 'Operasional PLT Direktur PDAM')
      ->setCellValue('A13', '5.')
      ->setCellValue('B13', 'Penghasilan bersih kebun kelapa sawit (10Ha)')
      ->setCellValue('A14', '6.')
      ->setCellValue('B14', 'Penerimaan dari rekanan Dinas PUPR')
      ->setCellValue('K13', 'Umur pohon 7 tahun/buah pasir')
      ->setCellValue('K14', 'Habis untuk konsumsi pribadi')
      ->setCellValue('B16', 'Total Penghasilan')
      ->setCellValue('B17', 'Total Penghasilan + Saldo Awal')
      ->setCellValue('C16', '=SUM(C9:C15)')
      ->setCellValue('D16', '=SUM(D9:D15)')
      ->setCellValue('E16', '=SUM(E9:E15)')
      ->setCellValue('F16', '=SUM(F9:F15)')
      ->setCellValue('G16', '=SUM(G9:G15)')
      ->setCellValue('H16', '=SUM(H9:H15)')
      ->setCellValue('I16', '=SUM(I9:I15)')
      ->setCellValue('J16', '=SUM(J9:J15)')
      ->setCellValue('C17', '=C16+C5')
      ->setCellValue('D17', '=D16+D5')
      ->setCellValue('E17', '=E16+E5')
      ->setCellValue('F17', '=F16+F5')
      ->setCellValue('G17', '=G16+G5')
      ->setCellValue('H17', '=H16+H5')
      ->setCellValue('I17', '=I16+I5')
      ->setCellValue('J17', '=J16+J5');

    $spreadsheet->getActiveSheet()
      ->setCellValue('C9', '-')
      ->setCellValue('D9', '-')
      ->setCellValue('E9', '-')
      ->setCellValue('F9', '-')
      ->setCellValue('G9', '-')
      ->setCellValue('H9', '-')
      ->setCellValue('I9', '-')
      ->setCellValue('J9', '-')
      ->setCellValue('C10', '-')
      ->setCellValue('D10', '-')
      ->setCellValue('E10', '-')
      ->setCellValue('F10', '-')
      ->setCellValue('G10', '-')
      ->setCellValue('H10', '-')
      ->setCellValue('I10', '-')
      ->setCellValue('J10', '-')
      ->setCellValue('C11', '-')
      ->setCellValue('D11', '-')
      ->setCellValue('E11', '-')
      ->setCellValue('F11', '-')
      ->setCellValue('G11', '-')
      ->setCellValue('H11', '-')
      ->setCellValue('I11', '-')
      ->setCellValue('J11', '-')
      ->setCellValue('C12', '-')
      ->setCellValue('D12', '-')
      ->setCellValue('E12', '-')
      ->setCellValue('F12', '-')
      ->setCellValue('G12', '-')
      ->setCellValue('H12', '-')
      ->setCellValue('I12', '-')
      ->setCellValue('J12', '-')
      ->setCellValue('C13', '-')
      ->setCellValue('D13', '-')
      ->setCellValue('E13', '-')
      ->setCellValue('F13', '-')
      ->setCellValue('G13', '-')
      ->setCellValue('H13', '-')
      ->setCellValue('I13', '-')
      ->setCellValue('J13', '-')
      ->setCellValue('C14', '-')
      ->setCellValue('D14', '-')
      ->setCellValue('E14', '-')
      ->setCellValue('F14', '-')
      ->setCellValue('G14', '-')
      ->setCellValue('H14', '-')
      ->setCellValue('I14', '-')
      ->setCellValue('J14', '-');
    
    $spreadsheet->getActiveSheet()
      ->setCellValue('C21', '-')
      ->setCellValue('D21', '-')
      ->setCellValue('E21', '-')
      ->setCellValue('F21', '-')
      ->setCellValue('G21', '-')
      ->setCellValue('H21', '-')
      ->setCellValue('I21', '-')
      ->setCellValue('J21', '-')
      ->setCellValue('C22', '-')
      ->setCellValue('D22', '-')
      ->setCellValue('E22', '-')
      ->setCellValue('F22', '-')
      ->setCellValue('G22', '-')
      ->setCellValue('H22', '-')
      ->setCellValue('I22', '-')
      ->setCellValue('J22', '-')
      ->setCellValue('C23', '-')
      ->setCellValue('D23', '-')
      ->setCellValue('E23', '-')
      ->setCellValue('F23', '-')
      ->setCellValue('G23', '-')
      ->setCellValue('H23', '-')
      ->setCellValue('I23', '-')
      ->setCellValue('J23', '-');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B19', 'PENGELUARAN')
      ->setCellValue('A21', '1.')
      ->setCellValue('B21', 'Konsumsi Keluarga')
      ->setCellValue('A22', '2.')
      ->setCellValue('B22', 'Pembelian Mobil Honda Jazz')
      ->setCellValue('A23', '3.')
      ->setCellValue('B23', 'Pembelian Mobil Toyota Innova Diesel')
      ->setCellValue('B25', 'Total Pengeluaran')
      ->setCellValue('B26', 'Penghasilan Bersih')
      ->setCellValue('B27', 'Surplus / (Defisit)')
      ->setCellValue('C25', '=SUM(C21:C24)')
      ->setCellValue('D25', '=SUM(D21:D24)')
      ->setCellValue('E25', '=SUM(E21:E24)')
      ->setCellValue('F25', '=SUM(F21:F24)')
      ->setCellValue('G25', '=SUM(G21:G24)')
      ->setCellValue('H25', '=SUM(H21:H24)')
      ->setCellValue('I25', '=SUM(I21:I24)')
      ->setCellValue('J25', '=SUM(J21:J24)')
      ->setCellValue('C26', '=C16-C25')
      ->setCellValue('D26', '=D16-D25')
      ->setCellValue('E26', '=E16-E25')
      ->setCellValue('F26', '=F16-F25')
      ->setCellValue('G26', '=G16-G25')
      ->setCellValue('H26', '=H16-H25')
      ->setCellValue('I26', '=I16-I25')
      ->setCellValue('J26', '=J16-J25')
      ->setCellValue('J30', 'Jakarta, (DD/Month/YYYY)')
      ->setCellValue('J36', $nama_pn);

      $spreadsheet->getActiveSheet()
      ->setCellValue('C27', '=C17-C25')
      ->setCellValue('D27', '=D17-D25')
      ->setCellValue('E27', '=E17-E25')
      ->setCellValue('F27', '=F17-F25')
      ->setCellValue('G27', '=G17-G25')
      ->setCellValue('H27', '=H17-H25')
      ->setCellValue('I27', '=I17-I25')
      ->setCellValue('J27', '=J17-J25');

    $this->style_table_header(
      'A4:K4',
      $this->color_darkgray,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    
    $this->style_table_header(
      'A7:K7',
      $this->color_darkgray_other,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $this->style_table_header(
      'A19:K19',
      $this->color_red_other,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    
    $this->style_table_header(
      'B5',
      $this->color_darkgray_other,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    
    $this->style_table_header(
      'B7',
      $this->color_green_other,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    
      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);
      $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(40);

      $spreadsheet->getActiveSheet()->getStyle("A1:K2")->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->getStyle("A1:K2")->getFont()->setBold(true);
      $spreadsheet->getActiveSheet()->getStyle("B16:B17")->getFont()->setBold(true);

      $spreadsheet->getActiveSheet()->getStyle('A1:K2')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('A9:A23')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C9:J14')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
      $spreadsheet->getActiveSheet()->getStyle('C21:J23')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
     
      $styleArray_1 = [
          'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => $this->color_black],
            ],
            'bottom' => [
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              'color' => ['argb' => $this->color_black],
            ],
            'horizontal' => [
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              'color' => ['argb' => $this->color_black],
            ],
          ],
      ];
      
      $styleArray_2 = [
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => [
            'argb' => $this->color_darkgray,
          ],
        ],
      ];

      $styleArray_3 = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => $this->color_black],
          ],
        ]
      ];

      $styleArray_4 = [
        'borders' => [
          'top' => [
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              'color' => ['argb' => $this->color_black],
          ],
          'bottom' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
            'color' => ['argb' => $this->color_black],
          ],
          'horizontal' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => $this->color_black],
          ],
        ],
      ];

      $spreadsheet->getActiveSheet()->getStyle('C5:J27')->getNumberFormat()
        ->setFormatCode('_-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');

      $spreadsheet->getActiveSheet()->getStyle('C5:K5')->applyFromArray($styleArray_3);
      $spreadsheet->getActiveSheet()->getStyle('A9:K14')->applyFromArray($styleArray_3);
      $spreadsheet->getActiveSheet()->getStyle('C16:J17')->applyFromArray($styleArray_4);
      $spreadsheet->getActiveSheet()->getStyle('A21:K23')->applyFromArray($styleArray_3);
      $spreadsheet->getActiveSheet()->getStyle('C25:J27')->applyFromArray($styleArray_1);
      $spreadsheet->getActiveSheet()->getStyle('C27:J27')->applyFromArray($styleArray_2);
      $spreadsheet->getActiveSheet()->getStyle("B25:B27")->getFont()->setBold(true);
      $spreadsheet->getActiveSheet()->getStyle("J30")->getFont()->setBold(true);
      $spreadsheet->getActiveSheet()->getStyle("J36")->getFont()->setBold(true);
  }

  public function cetak_tujuan($id_lhkpn, $spreadsheet){
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(0);
    $spreadsheet->getActiveSheet()->setTitle('Tujuan');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B1', 'TUJUAN DAN RUANG LINGKUP')->mergeCells('B1:F1')
      ->setCellValue('B2', 'PEMERIKSAAN LHKPN')->mergeCells('B2:F2');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B4', 'No.')
      ->setCellValue('C4', 'Uraian')->mergeCells('C4:E4')
      ->setCellValue('F4', 'Checklist (√)')
      ->setCellValue('B5', 'TUJUAN PEMERIKSAAN LHKPN :')->mergeCells('B5:F5')
      ->setCellValue('B6', '1')->mergeCells('B6:B16')
      ->setCellValue('C6', 'Memperoleh keyakinan yang memadai terhadap LHKPN yang disampaikan oleh Penyelenggara Negara dengan melakukan pengujian terhadap:')->mergeCells('C6:E6')
      ->setCellValue('C7', 'a.')
      ->setCellValue('D7', '')->mergeCells('D7:E7')
      ->setCellValue('C8', 'b.')->mergeCells('C8:C10')
      ->setCellValue('D8', 'Kesesuaian harta kekayaan, penghasilan dan pengeluaran yang dilaporkan di LHKPN dibandingkan dengan data dari pihak ketiga serta keberadaan harta kekayaan untuk mengidentifikasi:')->mergeCells('D8:E8')
      ->setCellValue('D9', '1)')
      ->setCellValue('D10', '2)')
      ->setCellValue('E9', 'Harta kekayaan, penghasilan dan pengeluaran yang disembunyikan/tidak dilaporkan di LHKPN')
      ->setCellValue('E10', 'Harta kekayaan, penghasilan dan pengeluaran yang seolah-olah dimiliki PN dan dilaporkan di LHKPN')
      ->setCellValue('C11', 'c.')
      ->setCellValue('D11', 'Ketepatan penyajian nilai harta kekayaan yang dilaporkan di LHKPN berdasarkan hasil konfirmasi kepada pihak ketiga.')->mergeCells('D11:E11')
      ->setCellValue('C12', 'd.')->mergeCells('C12:C16')
      ->setCellValue('D12', 'Kewajaran nilai harta kekayaan, penghasilan dan pengeluaran dikaitkan dengan:')->mergeCells('D12:E12')
      ->setCellValue('D13', '1)')
      ->setCellValue('D14', '2)')
      ->setCellValue('D15', '3)')
      ->setCellValue('D16', '4)')
      ->setCellValue('E13', 'Profil dan Jabatan PN;')
      ->setCellValue('E14', 'Perbandingan antara penghasilan bersih dengan kenaikan/penurunan nilai harta kekayaan dalam periode tertentu;')
      ->setCellValue('E15', '')
      ->setCellValue('E16', 'Indikasi Tindak Pidana Korupsi')
      ->setCellValue('B17', '')->mergeCells('B17:F17');
          
      $objRichText1 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
      $objRichText1->createText('Kepatuhan penyampaian LHKPN. Khusus untuk LHKPN dengan tanggal pelaporan sampai dengan 31 Desember 2016 maka pengujiannya merujuk kepada KEP/07/2005, sedangkan tanggal pelaporan setelah 31 Desember 2016 pengujiannya merujuk kepada PER KPK 07/2016 ');
      $objRed1 = $objRichText1->createTextRun('serta seluruh perubahannya.');
      $objRed1->getFont()->setItalic(true);
      $objRed1->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
      $spreadsheet->getActiveSheet()->getCell('D7')->setValue($objRichText1);

      $objRichText2 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
      $objRichText2->createText('Sumber dana perolehan harta sesuai dengan tempus pemeriksaan ');
      $objRed2 = $objRichText2->createTextRun('dan/atau ');
      $objRed2->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
      $objRichText2->createText('laporan LHKPN terakhir;');
      $spreadsheet->getActiveSheet()->getCell('E15')->setValue($objRichText2);

    $spreadsheet->getActiveSheet()
      ->setCellValue('B18', 'RUANG LINGKUP PEMERIKSAAN LHKPN :')->mergeCells('B18:F18')
      ->setCellValue('B19', '1')
      ->setCellValue('C19', 'Ruang Lingkup Harta Kekayaan yang diperiksa mencakup perolehan dan pelepasan harta kekayaan, penghasilan dan pengeluaran baik atas nama Penyelenggara Negara, pasangan, anak dalam tanggungan ataupun pihak lain, yang dikuasai/dimiliki dengan cara tunai, kredit, hibah, warisan baik dari sumber penghasilan sendiri maupun dari pihak lain')->mergeCells('C19:E19')
      ->setCellValue('B20', '2')->mergeCells('B20:B25')
      ->setCellValue('C20', 'Ruang Lingkup Pemeriksaan LHKPN mencakup identifikasi adanya:')->mergeCells('C20:E20')
      ->setCellValue('C21', 'a')
      ->setCellValue('D21', 'perolehan penghasilan, harta kekayaan atau pemberian yang disembunyikan yaitu perolehan penghasilan, harta kekayaan atau pemberian di luar penghasilan, harta kekayaan atau pemberian lainnya yang diterima penyelenggara negara tetapi tidak dilaporkan dalam LHKPN;')->mergeCells('D21:E21')
      ->setCellValue('C22', 'b')->mergeCells('C22:C24')
      ->setCellValue('D22', 'perolehan penghasilan, harta kekayaan atau pemberian yang disamarkan yaitu:')->mergeCells('D22:E22')
      ->setCellValue('D23', '1)')
      ->setCellValue('E23', 'perolehan penghasilan, harta kekayaan atau pemberian di luar penghasilan, harta kekayaan atau pemberian yang sah yang dilaporkan dalam LHKPN dengan nilai yang lebih rendah dari nilai sesungguhnya dan atau dibuat seolah-oleh sebagai perolehan penghasilan, harta kekayaan, atau pemberian yang sah;')
      ->setCellValue('D24', '2)')
      ->setCellValue('E24', 'perolehan penghasilan, harta kekayaan atau pemberian yang dilaporkan dalam LHKPN atas nama orang lain.')
      ->setCellValue('C25', 'c')
      ->setCellValue('D25', 'perolehan harta kekayaan yang diduga merupakan bagian atau berkaitan secara langsung maupun tidak langsung dengan Tindak Pidana Korupsi.')->mergeCells('D25:E25')
      ->setCellValue('B26', '')->mergeCells('B26:F26');

    $this->style_table_header(
      'B4:F4',
      $this->color_gray,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(3);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(3);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(70);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);

      $spreadsheet->getActiveSheet()->getStyle("B1:F2")->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->getStyle("B1:F2")->getFont()->setBold(true);

      $spreadsheet->getActiveSheet()->getStyle('B1:F2')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B4:F4')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B6:B20')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('F6:F25')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B1:F2')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B4:F4')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B6:E6')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C7:D7')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);      
      $spreadsheet->getActiveSheet()->getStyle('C8:D8')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C11:D12')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('D9:E9')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('D10:E10')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('D13:E16')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('B19:B20')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C21:C25')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('D23:D24')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C19:D25')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('F6:F25')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

      $spreadsheet->getActiveSheet()->getStyle('C6')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('E9:E10')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D11')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C7:C12')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C21:C25')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('D9:D10')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('D13:D16')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('D23:D24')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('D7:D8')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('E13:E16')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C19:E19')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D25')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

       $this->style_table_header('B5:E5', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $this->style_table_header('B18:E18', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->setSize(12);
      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->setBold(true);
      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->getColor()->setARGB($this->color_blue);

      $spreadsheet->getActiveSheet()->getRowDimension(6)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(7)->setRowHeight(55); 
      $spreadsheet->getActiveSheet()->getRowDimension(8)->setRowHeight(40); 
      $spreadsheet->getActiveSheet()->getRowDimension(11)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(14)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(15)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(19)->setRowHeight(52); 
      $spreadsheet->getActiveSheet()->getRowDimension(21)->setRowHeight(52); 
      $spreadsheet->getActiveSheet()->getRowDimension(23)->setRowHeight(52); 
      $spreadsheet->getActiveSheet()->getRowDimension(25)->setRowHeight(28); 

      $spreadsheet->getActiveSheet()->getStyle('C6')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('D7:D8')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('E9:E10')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('D11')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('C19')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('E13:E16')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('D21')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('E23:E24')->getAlignment()->setWrapText(true);

      $styleArray = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => $this->color_black],
          ],
        ]
      ];
  
      $spreadsheet->getActiveSheet()->getStyle('B4:F26')->applyFromArray($styleArray);
  }
  public function cetak_persiapan($id_lhkpn, $spreadsheet){
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(1);
    $spreadsheet->getActiveSheet()->setTitle('Persiapan');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B1', 'PROSEDUR PERSIAPAN')->mergeCells('B1:D1')
      ->setCellValue('B2', 'PEMERIKSAAN LHKPN')->mergeCells('B2:D2');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B4', 'No.')
      ->setCellValue('C4', 'Uraian')
      ->setCellValue('D4', 'Checklist (√)')
      ->setCellValue('B5', 'UMUM')->mergeCells('B5:D5')
      ->setCellValue('B6', '1')
      ->setCellValue('C6', 'Melakukan identifikasi jenis data dan informasi yang perlu diperoleh sesuai dengan profil penyelenggara negara dan jenis harta kekayaan yang dimiliki.')
      ->setCellValue('B7', '2')
      ->setCellValue('C7', 'Mempersiapkan dokumen yang dibutuhkan untuk memperoleh data dan informasi yang diperlukan, misalnya surat kuasa dan surat pengantar untuk memperoleh informasi dan data.')
      ->setCellValue('B8', '3')
      ->setCellValue('C8', 'Melakukan identifikasi instansi penyedia data dan informasi yang diperlukan dalam proses pemeriksaan serta menyiapkan surat permintaan data dan informasi sesuai dengan instansi yang dituju.');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B9', 'ANALISIS AWAL')->mergeCells('B9:D9')
      ->setCellValue('B10', '1')
      ->setCellValue('C10', 'Meneliti kebenaran detail identitas PN, pasangan dan keluarga yang disampaikan PN pada LHKPN yang diperiksa.')
      ->setCellValue('B11', '2')
      ->setCellValue('C11', 'Melakukan analisis terhadap harta kekayaan secara menyeluruh.')
      ->setCellValue('B12', '3')
      ->setCellValue('C12', 'Mengidentifikasi adanya sumber penghasilan lain dengan membandingkan penghasilan bersih dengan nilai perolehan harta di masing-masing tahun pelaporan.') 
      ->setCellValue('B13', '4')
      ->setCellValue('C13', 'Melakukan analisis terhadap profil PN, pasangan, anak dalam tanggungan dan anak diluar tanggungan yang meliputi informasi profesi, usaha, preferensi, social link serta lifestyle melalui berbagai sumber (web browser, media massa maupun social media).') 
      ->setCellValue('B14', '5')
      ->setCellValue('C14', 'Meneliti informasi dari Direktorat Pengaduan Masyarakat, Direktorat Gratifikasi atau unit lain di KPK (jika ada).');  

    $this->style_table_header(
      'B4:D4',
      $this->color_gray,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(70);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);

      $spreadsheet->getActiveSheet()->getStyle("B1:D2")->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->getStyle("B1:D2")->getFont()->setBold(true);

      $spreadsheet->getActiveSheet()->getStyle('B1:D2')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B4:D4')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B6:B20')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('D6:D14')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

      $spreadsheet->getActiveSheet()->getStyle('B1:D2')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B4:D4')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B6:C8')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('B10:C14')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('D6:D14')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

      $spreadsheet->getActiveSheet()->getStyle('C6:C8')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
       $this->style_table_header('B5:C5', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $this->style_table_header('B9:D9', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->setSize(12);
      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->setBold(true);
      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->getColor()->setARGB($this->color_blue);

      $spreadsheet->getActiveSheet()->getRowDimension(6)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(7)->setRowHeight(40); 
      $spreadsheet->getActiveSheet()->getRowDimension(8)->setRowHeight(40); 
      $spreadsheet->getActiveSheet()->getRowDimension(10)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(12)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(13)->setRowHeight(40); 
      $spreadsheet->getActiveSheet()->getRowDimension(14)->setRowHeight(30); 

      $spreadsheet->getActiveSheet()->getStyle('C6:C14')->getAlignment()->setWrapText(true);

      $styleArray = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => $this->color_black],
          ],
        ]
      ];
  
      $spreadsheet->getActiveSheet()->getStyle('B4:D14')->applyFromArray($styleArray);
  }

  public function cetak_pelaksanaan($id_lhkpn, $spreadsheet){
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(2);
    $spreadsheet->getActiveSheet()->setTitle('Pelaksanaan');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B1', 'PROSEDUR PELAKSANAAN')->mergeCells('B1:E1')
      ->setCellValue('B2', 'PEMERIKSAAN LHKPN')->mergeCells('B2:E2');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B4', 'No.')
      ->setCellValue('C4', 'Uraian')->mergeCells('C4:D4')
      ->setCellValue('E4', 'Checklist (√)')
      ->setCellValue('B5', 'HARTA TIDAK BERGERAK')->mergeCells('B5:E5')
      ->setCellValue('B6', '1')
      ->setCellValue('C6', 'Melakukan penelusuran data kepemilikan tanah dan/atau bangunan atas nama PN, pasangan, anak dalam tanggungan, anak diluar tanggungan dan pihak lain yang terkait.')->mergeCells('C6:D6')
      ->setCellValue('B7', '2')
      ->setCellValue('C7', 'Meneliti kebenaran isian LHKPN dengan hasil penelusuran mengenai alamat, luas tanah, bukti kepemilikan, alas hak dan tahun perolehan harta tidak bergerak.')->mergeCells('C7:D7')
      ->setCellValue('B8', '3')
      ->setCellValue('C8', 'Apabila terdapat perbedaan hasil antara data pertanahan dan LHKPN yang dilaporkan, buatkan surat permintaan data kepada Kantor Pertanahan berdasarkan wilayah pekerjaan, tempat lahir, tempat tinggal baik dari riwayat jabatan, KTP dan Pendidikan.
      ')->mergeCells('C8:D8')
      ->setCellValue('B9', '4')->mergeCells('B9:B15')
      ->setCellValue('C9', 'Melakukan observasi dan peninjauan lapangan terhadap tanah dan/atau bangunan, baik yang telah dilaporkan maupun tidak dilaporkan dalam LHKPN. Peninjauan lapangan diprioritaskan terhadap tanah dan/atau bangunan yang bernilai signifikan dan/atau yang diperoleh saat tempus pemeriksaan. Hasil peninjauan lapangan didokumentasikan dalam catatan yang memuat informasi sebagai berikut:')->mergeCells('C9:D9')
      ->setCellValue('C10', 'a.')
      ->setCellValue('D10', 'Gambar (Citra Satelit dan Foto Lokasi);')
      ->setCellValue('C11', 'b.')
      ->setCellValue('D11', 'Titik koordinat beserta detail lokasi tanah dan/atau bangunan (alamat);')
      ->setCellValue('C12', 'c.')
      ->setCellValue('D12', 'Informasi spesifik mengenai perbedaan antara detail pelaporan harta tidak bergerak dengan kondisi faktual hasil observasi/peninjauan lapangan;')
      ->setCellValue('C13', 'd.')
      ->setCellValue('D13', 'Pemanfaatan tanah dan/atau bangunan;')
      ->setCellValue('C14', 'e.')
      ->setCellValue('D14', 'Kendaraan dan asset Lainnya yang berada di lokasi tanah dan/atau bangunan;')
      ->setCellValue('C15', 'f.')
      ->setCellValue('D15', 'Informasi mengenai potensi wilayah dan perkiraan nilai harta tidak bergerak.')
      ->setCellValue('B16', '5')->mergeCells('B16:B24')
      ->setCellValue('C16', 'Meneliti dan menganalisis riwayat perolehan hak tanah dan/atau bangunan yang dilaporkan dan yang tidak dilaporkan dengan memperhatikan beberapa hal sebagai berikut:')->mergeCells('C16:D16')
      ->setCellValue('C17', 'a.')
      ->setCellValue('D17', 'Bukti kepemilikan tanah baik berupa sertifikat, warkah atau surat tanah (riwayat perpindahan hak atas tanah, tempus perolehan/pelepasan hak, luas tanah, notaris, lokasi);')
      ->setCellValue('C18', 'b.')
      ->setCellValue('D18', 'Akta jual beli (harga pengalihan, profil saksi dalam proses pengalihan hak dan pihak terkait lainnya);')
      ->setCellValue('C19', 'c.')
      ->setCellValue('D19', 'Akta atau surat keterangan waris atau hibah dalam hal alas hak berupa waris atau hibah;')
      ->setCellValue('C20', 'd.')
      ->setCellValue('D20', 'Sumber dana yang digunakan untuk perolehan tanah dan/atau bangunan (seperti riwayat transaksi melalui rekening simpanan pada periode pengalihan hak, riwayat perolehan pinjaman, dll);')
      ->setCellValue('C21', 'e.')
      ->setCellValue('D21', 'Kesesuaian antara nilai pada Akta Jual Beli dengan riwayat transaksi pada mutasi rekening yang terkait dengan penjualan dan perolehan aset;')
      ->setCellValue('C22', 'f.')
      ->setCellValue('D22', 'Tempus perolehan dan pelepasan asset yang memiliki pola tertentu (perolehan dan pelepasan dalam waktu singkat, perolehan atau pelepasan asset secara masif dalam waktu tertentu, dll);')
      ->setCellValue('C23', 'g.')
      ->setCellValue('D23', 'Detail profil pemilik hak tanah sebelum dan setelahnya guna menguji adanya potensi keterkaitan dengan jabatan)')
      ->setCellValue('C24', 'h.')
      ->setCellValue('D24', 'Apabila bukti kepemilikan tanah berupa Hak Guna Usaha (HGU), perlu dilakukan penelusuran lanjutan mengenai jenis usaha dan perizinan atas usaha yang dijalankan di atas tanah tersebut (perkebunan/hutan/pertanian/pertambangan)')
      ->setCellValue('B25', '6')
      ->setCellValue('C25', 'Melakukan permintaan data kepada developer real estate atau apartemen apabila belum ditemukan transaksi pembayaran untuk perolehan harta tidak bergerak tersebut dalam rekening simpanan lembaga keuangan.')->mergeCells('C25:D25')
      ->setCellValue('B26', '7')
      ->setCellValue('C26', 'Meneliti keterkaitan antara pemanfaatan tanah dan bangunan dengan potensi tambahan penghasilan yang diterima penyelenggara negara.')->mergeCells('C26:D26')
      ->setCellValue('B27', '8')
      ->setCellValue('C27', 'Meneliti kesesuaian antara tempus dan nilai perolehan dan/atau pelepasan harta tidak bergerak dengan isian LHKPN pada halaman Penghasilan dan Pengeluaran.')->mergeCells('C27:D27');
      

    $spreadsheet->getActiveSheet()
      ->setCellValue('B28', 'ALAT TRANSPORTASI')->mergeCells('B28:E28')
      ->setCellValue('B29', '1')
      ->setCellValue('C29', 'Melakukan Penelusuran data kepemilikan kendaraan atas nama PN, pasangan, anak dalam tanggungan, anak diluar tanggungan dan pihak lain yang terkait.')->mergeCells('C29:D29')
      ->setCellValue('B30', '2')
      ->setCellValue('C30', 'Meneliti kebenaran isian LHKPN dengan hasil penelusuran mengenai jenis, merk, nomor polisi, tahun pembuatan dan nilai perolehan alat transportasi yang dilaporkan')->mergeCells('C30:D30')
      ->setCellValue('B31', '3')->mergeCells('B31:B34')
      ->setCellValue('C31', 'Meneliti kemungkinan penyelenggara negara melaporkan nilai perolehan harta kekayaan dalam bentuk alat transportasi dan mesin lainnya dengan nilai yang tidak sesuai dengan harga sesungguhnya (lebih rendah atau lebih tinggi). Guna mengetahui hal tersebut dilakukan beberapa hal sebagai berikut:')->mergeCells('C31:D31')
      ->setCellValue('C32', 'a.')
      ->setCellValue('D32', 'Bandingkan nilai jual pada saat pelaporan dengan nilai perolehan dan analisis kewajarannya dalam hal terdapat perbedaan yang signifikan.')
      ->setCellValue('C33', 'b.')
      ->setCellValue('D33', 'Buat catatan dalam hal terdapat kemungkinan penyelenggara negara melaporkan nilai perolehan harta kekayaan tidak sesuai dengan harga sesungguhnya (lebih rendah atau lebih tinggi)')
      ->setCellValue('C34', 'c.')
      ->setCellValue('D34', 'Lakukan permintaan data ke Dinas Pendapatan Daerah dan Kementerian Perhubungan')
      ->setCellValue('B35', '4')->mergeCells('B35:B38')
      ->setCellValue('C35', 'Melakukan observasi atau peninjauan lapangan terhadap kepemilikan alat transportasi baik yang dilaporkan maupun tidak dilaporkan yang disimpan di kediaman penyelenggara negara atau lokasi lainnya berdasarkan informasi yang diperoleh. Peninjauan lapangan diprioritaskan terhadap alat transportasi yang bernilai signifikan dan/atau yang diperoleh saat tempus pemeriksaan. Hasil peninjauan lapangan didokumentasikan dalam catatan yang memuat informasi sebagai berikut:')->mergeCells('C35:D35')
      ->setCellValue('C36', 'a.')
      ->setCellValue('D36', 'Foto alat transportasi;')
      ->setCellValue('C37', 'b.')
      ->setCellValue('D37', 'Informasi jenis, merk, nomor polisi. Atas informasi tersebut selanjutnya dilakukan konfirmasi ke Dinas Pendapatan Daerah terkait;')
      ->setCellValue('C38', 'c.')
      ->setCellValue('D38', 'Informasi spesifik mengenai perbedaan antara detail pelaporan alat transportasi dalam LHKPN dengan kondisi faktual hasil observasi/peninjauan lapangan;')
      ->setCellValue('B39', '5')->mergeCells('B39:B42')
      ->setCellValue('C39', 'Meneliti perolehan harta kekayaan dalam periode jabatan baik yang telah dilaporkan maupun yang tidak dilaporkan dalam LHKPN serta mencatatkan informasi mengenai beberapa hal sebagai berikut:')->mergeCells('C39:D39')
      ->setCellValue('C40', 'a.')
      ->setCellValue('D40', 'Hasil analisis terhadap dokumen jual beli alat transportasi (faktur untuk kendaraan baru dari pabrikan, kuitansi untuk kendaraan bekas);')
      ->setCellValue('C41', 'b.')
      ->setCellValue('D41', 'Estimasi nilai perolehan dan nilai jual per tanggal pelaporan atas kepemilikan alat transportasi tersebut;')
      ->setCellValue('C42', 'c.')
      ->setCellValue('D42', 'Sumber dana yang digunakan untuk perolehan alat transportasi tersebut.')
      ->setCellValue('B43', '6')
      ->setCellValue('C43', 'Melakukan analisis terhadap kepemilikan alat transportasi baik yang diperoleh melalui kredit atau tunai yang diatasnamakan individu selain keluarga PN ataupun korporasi (analisis profil pihak terkait dan sumber dana untuk pembelian alat transportasi).')->mergeCells('C43:D43')
      ->setCellValue('B44', '7')
      ->setCellValue('C44', 'Apabila sumber dana perolehan alat transportasi tersebut dari cicilan kredit, temukan bank kustodian, nama leasing, Surat Perjanjian Kredit serta metode pembayaran cicilan kredit tersebut (tunai/transfer).
      ')->mergeCells('C44:D44')
      ->setCellValue('B45', '8')
      ->setCellValue('C45', 'Apabila pembayaran cicilan transfer belum ditemukan rekening dan transaksinya, berarti dibutuhkan perluasan data keuangan ke Perbankan lainnya. Coba lihat data balasan dari Leasing metode pembayaran cicilannya.')->mergeCells('C45:D45')
      ->setCellValue('B46', '9')
      ->setCellValue('C46', '')->mergeCells('C46:D46')
      ->setCellValue('B47', '10')
      ->setCellValue('C47', 'Meneliti kesesuaian antara tempus dan nilai perolehan dan/atau pelepasan alat transportasi dengan isian LHKPN pada halaman Penghasilan dan Pengeluaran.')->mergeCells('C47:D47');

      $objRichText3 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
      $objRichText3->createText('Membandingkan nilai pasar alat transportasi yang dilaporkan dengan nilai jual ');
      $objRed3 = $objRichText3->createTextRun('per tanggal pelaporan');
      $objRed3->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
      $spreadsheet->getActiveSheet()->getCell('C46')->setValue($objRichText3);
    
    $spreadsheet->getActiveSheet()
      ->setCellValue('B48', 'HARTA BERGERAK LAINNYA')->mergeCells('B48:E48')
      ->setCellValue('B49', '1')
      ->setCellValue('C49', 'Meneliti kepemilikan harta bergerak yang bernilai signifikan yang masuk pada periode pemeriksaan atau saat menjabat sebagai PN.')->mergeCells('C49:D49')
      ->setCellValue('B50', '2')
      ->setCellValue('C50', 'Mengidentifikasi "Persediaan" yang memiliki dampak terhadap penghasilan dan melakukan perhitungan estimasi untuk penghasilan tersebut.')->mergeCells('C50:D50')
      ->setCellValue('B51', '3')
      ->setCellValue('C51', 'Mengidentifikasi "Persediaan" yang berkaitan dengan harta tidak bergerak.')->mergeCells('C51:D51')
      ->setCellValue('B52', '4')
      ->setCellValue('C52', 'Mengidentifikasi "Persediaan" yang berkaitan dengan kepemilikan usaha/perkebunan/Perusahaan.')->mergeCells('C52:D52')
      ->setCellValue('B53', '5')
      ->setCellValue('C53', 'Melakukan permintaan data kepada instansi terkait (misal : PT Antam (Persero) untuk kepemilikan Logam Mulia) untuk penelusuran lebih lanjut mengenai kepemilikan harta bergerak lainnya yang bernilai signifikan dan/atau belum dilaporkan dalam LHKPN.')->mergeCells('C53:D53')
      ->setCellValue('B54', '6')->mergeCells('B54:B56')
      ->setCellValue('C54', 'Melakukan analisis guna menguji kemungkinan penyelenggara negara tidak melaporkan harta kekayaan dalam bentuk peternakan, perikanan, perkebunan, kehutanan, pertambangan dan usaha sejenis lainnya. Analisis yang dilakukan meliputi:')->mergeCells('C54:D54')
      ->setCellValue('C55', 'a.')
      ->setCellValue('D55', 'Meneliti pemilikan harta kekayaan dalam bentuk tanah dan identifikasi kemungkinan pemanfaatan tanah tersebut untuk usaha peternakan, perikanan, perkebunan, kehutanan, pertambangan dan usaha sejenis lainnya;')
      ->setCellValue('C56', 'b.')
      ->setCellValue('D56', 'Membuat catatan mengenai harta bergerak lainnya yang tidak dilaporkan.')
      ->setCellValue('B57', '7')
      ->setCellValue('C57', 'Buatkan simulasi dan perhitungan penghasilan dari perkebunan/pertanian dan cocokkan dengan periode perolehan tanah, harga TBS/Padi dan biaya yang dibutuhkan untuk melakukan penanaman serta rata-rata umum hasil usaha di bidang sejenis dan biaya yang dibutuhkan untuk operasional usaha pada bidang sejenis.')->mergeCells('C57:D57')
      ->setCellValue('B58', '8')
      ->setCellValue('C58', 'Melakukan observasi atau peninjauan lapangan untuk mendapatkan data dan informasi tambahan yang berkaitan dengan perkembangan hasil pemeriksaan (jika dibutuhkan).')->mergeCells('C58:D58')
      ->setCellValue('B59', '9')
      ->setCellValue('C59', 'Meneliti kesesuaian antara tempus dan nilai perolehan dan/atau pelepasan persediaan/harta bergerak lainnya dengan isian LHKPN pada halaman Penghasilan dan Pengeluaran.')->mergeCells('C59:D59');
    
    $spreadsheet->getActiveSheet()
      ->setCellValue('B60', 'SURAT BERHARGA')->mergeCells('B60:E60')
      ->setCellValue('B61', '1')
      ->setCellValue('C61', 'Melakukan penelusuran data kepemilikan surat berharga non-listing melalui data internal dan listing melalui Kustodian Sentral Efek Indonesia dan/atau Perusahaan Sekuritas')->mergeCells('C61:D61')
      ->setCellValue('B62', '2')->mergeCells('B62:B65')
      ->setCellValue('C62', '')->mergeCells('C62:D62')
      ->setCellValue('C63', 'a.')
      ->setCellValue('D63', 'Meneliti kebenaran isian LHKPN dengan hasil penelusuran mengenai jenis badan usaha, bidang usaha, tanggal perolehan dan nilai perolehan serta nilai per tanggal pelaporan.')
      ->setCellValue('C64', 'b.')
      ->setCellValue('D64', 'Melakukan penelusuran atas nama-nama pemilik dan pengurus badan usaha sampai dengan layer terakhir dan menganalisis keterkaitan antar masing-masing pihak dengan PN dan/atau Keluarga PN.')
      ->setCellValue('C65', 'c.')
      ->setCellValue('D65', 'Melakukan observasi terhadap kegiatan badan usaha dan jenis usaha yang dijalankan guna menguji apakah terdapat keterkaitan dengan kewenangan PN')
      ->setCellValue('B66', '3')->mergeCells('B66:B72')
      ->setCellValue('C66', '')->mergeCells('C66:D66')
      ->setCellValue('C67', 'a.')
      ->setCellValue('D67', 'Meneliti kebenaran isian LHKPN dengan hasil penelusuran mengenai jenis produk investasi (Obligasi, Reksadana, Sukuk, Saham), tanggal perolehan dan nilai perolehan serta nilai per tanggal pelaporan.')
      ->setCellValue('C68', 'b.')
      ->setCellValue('D68', 'Meneliti transaksi pada Rekening Dana Nasabah (RDN) dan menganalisis sumber dana masuk yang digunakan pada transaksi jual beli surat berharga')
      ->setCellValue('C69', 'c.')
      ->setCellValue('D69', 'Meneliti kesesuaian penempatan surat berharga ke Kustodian dari rekening dana nasabah sesuai dengan data penempatan surat berharga dari KSEI')
      ->setCellValue('C70', 'd.')
      ->setCellValue('D70', '')
      ->setCellValue('C71', 'e.')
      ->setCellValue('D71', 'Meneliti tempus pelepasan saham dan/atau pencairan investasi (Sukuk, Obligasi, Reksadana, dll) serta mengkonfirmasi rekening penampungan dana masuk terkait dengan pelepasan tersebut.')
      ->setCellValue('C72', 'f.')
      ->setCellValue('D72', 'Meneliti kemungkinan kegiatan insider trading yang dilakukan PN, lakukan perhitungan keuntungan yang diperoleh dari peningkatan nilai surat berharga saat penempatan sampai dengan pencairan')
      ->setCellValue('B73', '4')
      ->setCellValue('C73', 'Mencatatkan detail informasi mengenai surat berharga yang tidak dilaporkan serta yang patut diduga terkait secara langsung maupun tidak langsung dengan hasil tindak pidana korupsi.')->mergeCells('C73:D73')
      ->setCellValue('B74', '5')
      ->setCellValue('C74', 'Meneliti kesesuaian antara tempus dan nilai perolehan dan/atau pelepasan Surat Berharga dengan isian LHKPN pada halaman Penghasilan dan Pengeluaran.')->mergeCells('C74:D74');

      $objRichText4 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
      $objRichText4->createText('Melakukan analisis terhadap kepemilikan surat berharga ');
      $objBold4 = $objRichText4->createTextRun('non-listing ');
      $objBold4->getFont()->setBold(true);
      $objRichText4->createText('dengan memperhatikan beberapa hal sebagai berikut:');
      $spreadsheet->getActiveSheet()->getCell('C62')->setValue($objRichText4);

      $objRichText5 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
      $objRichText5->createText('Melakukan analisis terhadap kepemilikan surat berharga ');
      $objBold5 = $objRichText5->createTextRun('listing ');
      $objBold5->getFont()->setBold(true);
      $objRichText5->createText('dengan memperhatikan beberapa hal sebagai berikut:');
      $spreadsheet->getActiveSheet()->getCell('C66')->setValue($objRichText5);
      
      $objRichText6 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
      $objRichText6->createText('Meneliti kesesuaian antara data yang diperoleh dari KSEI dengan riwayat transaksi keuangan yang mengindikasikan kepemilikan akun pada sekuritas tertentu. Apabila terdapat perbedaan data (disebabkan belum terdapat dalam data ');
      $objItalic6a = $objRichText6->createTextRun('mirroring ');
      $objItalic6a->getFont()->setItalic(true);
      $objRichText6->createText('KSEI), maka dilakukan pendalaman analisis dengan memintakan data kepada Sekuritas terkait (Data CIF, riwayat transaksi sesuai periode pemeriksaan, portofolio, saldo dan ');
      $objItalic6b = $objRichText6->createTextRun('standing instruction ');
      $objItalic6b->getFont()->setItalic(true);
      $objRichText6->createText('per tanggal pelaporan).');
      $spreadsheet->getActiveSheet()->getCell('D70')->setValue($objRichText6);

    $spreadsheet->getActiveSheet()
      ->setCellValue('B75', 'KAS SETARA KAS')->mergeCells('B75:E75')
      ->setCellValue('B76', '1')
      ->setCellValue('C76', 'Mengidentifikasi CIF dan rekening deposito, giro, tabungan dan dana nasabah serta simpanan keuangan lainnya yang dimiliki pada periode pemeriksaan.')->mergeCells('C76:D76')
      ->setCellValue('B77', '2')
      ->setCellValue('C77', 'Meneliti kebenaran isian LHKPN dengan hasil konfirmasi mengenai jenis rekening, instansi perbankan, nomor rekening, tanggal pembukaan rekening dan nilai saldo rekening yang dilaporkan.')->mergeCells('C77:D77')
      ->setCellValue('B78', '3')->mergeCells('B78:B83')
      ->setCellValue('C78', 'Melakukan analisis terhadap kepemilikan rekening guna mendeteksi adanya dugaan transaksi yang mencurigakan dengan memperhatikan beberapa hal sebagai berikut:')->mergeCells('C78:D78')
      ->setCellValue('C79', 'a.')
      ->setCellValue('D79', 'Transaksi yang tidak sesuai dengan profil PN;')
      ->setCellValue('C80', 'b.')
      ->setCellValue('D80', 'Transaksi bernilai signifikan;')
      ->setCellValue('C81', 'c.')
      ->setCellValue('D81', 'Transaksi dengan pola tertentu dari pihak tertentu;')
      ->setCellValue('C82', 'd.')
      ->setCellValue('D82', 'Transaksi-transaksi khusus seperti pembelian traveller cheque, pembelian harta kekayaan dengan jumlah besar, transaksi kartu kredit, sewa SDB, penerimaan bunga deposito, tabungan, atau sumber penghasilan lain, transfer ke rekening bersangkutan yang tidak dilaporkan;')
      ->setCellValue('C83', 'e.')
      ->setCellValue('D83', 'Transaksi signifikan yang tidak mencantumkan keterangan atau transaksi yang memuat keterangan menggunakan istilah yang umum.')
      ->setCellValue('B84', '4')
      ->setCellValue('C84', 'Melakukan permintaan bukti transaksi dari perbankan beserta lampiran-lampiran pendukungnya sebagai bahan pendalaman analisis transaksi yang mencurigakan.')->mergeCells('C84:D84')
      ->setCellValue('B85', '5')
      ->setCellValue('C85', 'Meneliti sumber penerimaan atau sumber dana untuk pembukaan rekening simpanan.')->mergeCells('C85:D85')
      ->setCellValue('B86', '6')
      ->setCellValue('C86', 'Mengidentifikasi nama pengirim/penerima dana dalam mutasi rekening simpanan dan menganalisis keterkaitan pihak-pihak tersebut dengan penyelenggara negara (Sumber Informasi: Open Source/Media /Direktorat Monitor/Direktorat Pengaduan Masyarakat/LPSE/SITP/Unit Koordinasi dan Supervisi Pencegahan)')->mergeCells('C86:D86')
      ->setCellValue('B87', '7')
      ->setCellValue('C87', 'Membuat catatan dalam hal terdapat rekening simpanan yang tidak dilaporkan maupun yang diduga terkait secara langsung/tidak langsung hasil dari tindak pidana korupsi.')->mergeCells('C87:D87')
      ->setCellValue('B88', '8')
      ->setCellValue('C88', 'Meneliti kesesuaian antara transaksi dana masuk dan dana keluar yang terkait dengan penghasilan dan pengeluaran dengan isian Penghasilan dan Pengeluaran pada LHKPN.')->mergeCells('C88:D88');

      $spreadsheet->getActiveSheet()
      ->setCellValue('B89', 'HARTA LAINNYA (PIUTANG & ASURANSI)')->mergeCells('B89:E89')
      ->setCellValue('B90', '1')
      ->setCellValue('C90', 'Mengidentifikasi kepemilikan asuransi atau piutang jenis lainnya dengan melakukan konfirmasi pada instansi terkait.')->mergeCells('C90:D90')
      ->setCellValue('B91', '2')
      ->setCellValue('C91', 'Meneliti kebenaran isian LHKPN dengan hasil penelusuran mengenai jenis piutang/asuransi, perusahaan asuransi, nomor polis asuransi, tanggal pembukaan polis dan nilai tunai yang dilaporkan.')->mergeCells('C91:D91')
      ->setCellValue('B92', '3')
      ->setCellValue('C92', 'Meneliti nama penerima manfaat/Beneficiary atas kepemilikan Asuransi/Jenis Piutang lainnya (apakah termasuk keluarga PN atau tidak)')->mergeCells('C92:D92')
      ->setCellValue('B93', '4')
      ->setCellValue('C93', 'Meneliti dan melakukan perhitungan nilai asuransi guna menguji kesesuaian nilai dengan nilai premi/top up yang sudah dibayarkan')->mergeCells('C93:D93')
      ->setCellValue('B94', '5')
      ->setCellValue('C94', 'Meneliti metode pembayaran premi dan top up dalam dokumen CIF Asuransi')->mergeCells('C94:D94')
      ->setCellValue('B95', '6')
      ->setCellValue('C95', 'Menganalisis kesesuaian antara transaksi pembayaran premi, top up dan pencairan asuransi dengan mutasi transaksi. Apabila belum ditemukan aliran transaksi terkait, maka perlu dilakukan konfirmasi kepada Mitra Kerja Asuransi')->mergeCells('C95:D95')
      ->setCellValue('B96', '7')
      ->setCellValue('C96', 'Teliti kepemilikan asuransi yang ditanggung pemberi kerja, apakah PN memiliki rangkap pekerjaan/jabatan dan lakukan analisis sejak kapan PN rangkap jabatan. Untuk keluarga, teliti apakah pemberi kerja memiliki keterkaitan dengan pekerjaan PN')->mergeCells('C96:D96')      
      ->setCellValue('B97', '8')
      ->setCellValue('C97', 'Membuat catatan dalam hal terdapat polis asuransi atau piutang jenis lainnya yang tidak dilaporkan maupun yang diduga terkait secara langsung/tidak langsung dengan hasil dari tindak pidana korupsi.')->mergeCells('C97:D97')   
      ->setCellValue('B98', '9')
      ->setCellValue('C98', 'Meneliti kesesuaian antara pembayaran premi/top up dan pencairan premi selama periode pelaporan dengan isian Penghasilan dan Pengeluaran dalam LHKPN.')->mergeCells('C98:D98');      

    $spreadsheet->getActiveSheet()
      ->setCellValue('B99', 'HUTANG')->mergeCells('B99:E99')
      ->setCellValue('B100', '1')
      ->setCellValue('C100', 'Mengidentifikasi rekening pinjaman dan/atau fasilitas kartu kredit yang dimiliki pada periode pemeriksaan.')->mergeCells('C100:D100')
      ->setCellValue('B101', '2')
      ->setCellValue('C101', 'Meneliti kebenaran isian LHKPN dengan hasil penelusuran mengenai jenis hutang, jenis agunan, tanggal pembukaan rekening hutang dan nilai saldo hutang yang dilaporkan.')->mergeCells('C101:D101')
      ->setCellValue('B102', '3')->mergeCells('B102:B106')
      ->setCellValue('C102', 'Melakukan analisis terhadap kepemilikan rekening pinjaman dan/atau fasilitas kartu kredit dengan memperhatikan beberapa hal sebagai berikut:')->mergeCells('C102:D102')
      ->setCellValue('C103', 'a.')
      ->setCellValue('D103', 'Lembar tagihan dan pembayaran cicilan pada periode pemeriksaan')
      ->setCellValue('C104', 'b.')
      ->setCellValue('D104', 'Pola pembayaran pinjaman dan kartu kredit dibandingkan dengan penghasilan PN dan identifikasi sumber dana pelunasannya')
      ->setCellValue('C105', 'c.')
      ->setCellValue('D105', 'Jika terdapat cicilan tidak seimbang dengan penghasilan, teliti sumber dana untuk pembayaran cicilan dengan meminta data ke bank yang bersangkutan.')
      ->setCellValue('C106', 'd.')
      ->setCellValue('D106', 'Kesesuaian periode pinjaman mulai dari pencairan sampai dengan pelunasan pinjaman yang dibandingkan dengan mutasi transaksi pada rekening PN dan/atau Keluarga.')
      ->setCellValue('B107', '4')
      ->setCellValue('C107', 'Membuat catatan dalam hal terdapat fasilitas pinjaman yang tidak dilaporkan maupun yang diduga terkait secara langsung/tidak langsung dengan hasil dari tindak pidana korupsi.')->mergeCells('C107:D107')
      ->setCellValue('B108', '5')
      ->setCellValue('C108', 'Meneliti kesesuaian antara pencairan pinjaman dan pembayaran cicilan selama periode pelaporan dengan isian Penghasilan dan Pengeluaran dalam LHKPN.')->mergeCells('C108:D108');

    $this->style_table_header(
      'B4:E4',
      $this->color_gray,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(3);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(70);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);

      $spreadsheet->getActiveSheet()->getStyle("B1:E2")->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->getStyle("B1:E2")->getFont()->setBold(true);

      $spreadsheet->getActiveSheet()->getStyle('B1:E2')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B4:E4')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B6:B108')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C10:C15')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C17:C24')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C32:C34')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C36:C38')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C40:C42')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C55:C56')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C63:C65')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C67:C72')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C79:C83')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C103:C106')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('E6:E108')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

      $spreadsheet->getActiveSheet()->getStyle('B1:E2')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B4:E4')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B6:C9')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C10:D15')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C16')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('B16:B108')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C17:D27')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C29:C31')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C43:C47')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C32:D34')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C36:D38')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C40:D42')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C49:D54')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C57:D59')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C55:D56')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('C61:D108')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('E6:E108')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

      $spreadsheet->getActiveSheet()->getStyle('C6:C9')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D17:D24')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C25:C31')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C16')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D12')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C35')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C39')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D37:D38')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C43:D47')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C49:D54')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C57:D59')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D55')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C61:D62')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D63:D65')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C66')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D67:D72')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C73:D74')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C76:D78')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D82:D83')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C84:D88')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C90:D98')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C100:D102')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D104:D106')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C107:D108')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

       $this->style_table_header('B5:E5', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $this->style_table_header('B28:E28', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      
      $this->style_table_header('B48:E48', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $this->style_table_header('B60:E60', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      
      $this->style_table_header('B75:E75', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $this->style_table_header('B89:E89', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $this->style_table_header('B99:E99', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->setSize(12);
      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->setBold(true);
      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->getColor()->setARGB($this->color_blue);

      $spreadsheet->getActiveSheet()->getRowDimension(6)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(7)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(8)->setRowHeight(40); 
      $spreadsheet->getActiveSheet()->getRowDimension(9)->setRowHeight(65); 
      $spreadsheet->getActiveSheet()->getRowDimension(12)->setRowHeight(28); 
      $spreadsheet->getActiveSheet()->getRowDimension(16)->setRowHeight(40); 
      $spreadsheet->getActiveSheet()->getRowDimension(17)->setRowHeight(40); 
      $spreadsheet->getActiveSheet()->getRowDimension("20:24")->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(25)->setRowHeight(40); 
      $spreadsheet->getActiveSheet()->getRowDimension(26)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(27)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(29)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(30)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(31)->setRowHeight(52); 
      $spreadsheet->getActiveSheet()->getRowDimension(35)->setRowHeight(75); 
      $spreadsheet->getActiveSheet()->getRowDimension(37)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(38)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(39)->setRowHeight(40);
      $spreadsheet->getActiveSheet()->getRowDimension("40:41")->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(43)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(44)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(45)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(46)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(47)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(49)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(50)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(52)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(53)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(54)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(55)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(57)->setRowHeight(52);  
      $spreadsheet->getActiveSheet()->getRowDimension(58)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(59)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(61)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(62)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(66)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension("67:69")->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(70)->setRowHeight(80);  
      $spreadsheet->getActiveSheet()->getRowDimension("71:72")->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(73)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(74)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(76)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(77)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(78)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension("82:83")->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(84)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(86)->setRowHeight(52);  
      $spreadsheet->getActiveSheet()->getRowDimension(87)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(88)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(90)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(91)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(92)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(93)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(95)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(96)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(97)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(98)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(100)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(101)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(102)->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension("104:106")->setRowHeight(30);  
      $spreadsheet->getActiveSheet()->getRowDimension(107)->setRowHeight(40);  
      $spreadsheet->getActiveSheet()->getRowDimension(108)->setRowHeight(30);  
      
      $spreadsheet->getActiveSheet()->getStyle('C6:C9')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('D12')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('C16')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('D17:D24')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('C25:C27')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('C29:C31')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('D32:D33')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('D40:D41')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('C49:C54')->getAlignment()->setWrapText(true);
      $spreadsheet->getActiveSheet()->getStyle('C35')->getAlignment()->setWrapText(true);

      $styleArray = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => $this->color_black],
          ],
        ]
      ];
  
      $spreadsheet->getActiveSheet()->getStyle('B4:E108')->applyFromArray($styleArray);
  }

  public function cetak_penyelesaian($id_lhkpn, $spreadsheet){
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(3);
    $spreadsheet->getActiveSheet()->setTitle('Penyelesaian');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B1', 'PROSEDUR PENYELESAIAN')->mergeCells('B1:E1')
      ->setCellValue('B2', 'PEMERIKSAAN LHKPN')->mergeCells('B2:E2');

    $spreadsheet->getActiveSheet()
      ->setCellValue('B4', 'No.')
      ->setCellValue('C4', 'Uraian')->mergeCells('C4:D4')
      ->setCellValue('E4', 'Checklist (√)')
      ->setCellValue('B5', 'MENYUSUN SIMPULAN HASIL PEMERIKSAAN')->mergeCells('B5:E5')
      ->setCellValue('B6', '1')->mergeCells('B6:B11')
      ->setCellValue('C6', 'Apabila diperlukan, lakukan klarifikasi terhadap Penyelenggara Negara terkait seluruh harta kekayaan dan transaksi keuangan, antara lain :')->mergeCells('C6:D6')
      ->setCellValue('C7', 'a.')
      ->setCellValue('D7', 'Validitas nilai harta yang dilaporkan maupun yang tidak dilaporkan;')
      ->setCellValue('C8', 'b.')
      ->setCellValue('D8', 'Sumber dana perolehan harta;')
      ->setCellValue('C9', 'c.')
      ->setCellValue('D9', 'Transaksi aliran dana masuk (Diluar penghasilan dari jabatan)')
      ->setCellValue('C10', 'd.')
      ->setCellValue('D10', 'Kemampuan finansial PN dan Keluarga dalam memperoleh harta kekayaan dan pengeluaran')
      ->setCellValue('C11', 'e.')
      ->setCellValue('D11', 'Hubungan PN dengan pihak-pihak yang teridentifikasi dalam kepemilikan harta dan pada transaksi keuangan (Perbankan, asuransi dll)')
      ->setCellValue('B12', '2')->mergeCells('B12:B19')
      ->setCellValue('C12', 'Menyusun simpulan berdasarkan hasil analisis dari informasi, keterangan dan dokumen guna menentukan :')->mergeCells('C12:D12')
      ->setCellValue('C13', 'a.')
      ->setCellValue('D13', 'Kepatuhan Pelaporan LHKPN')
      ->setCellValue('C14', 'b.')
      ->setCellValue('D14', 'Harta kekayaan yang tidak dilaporkan (item dan nilai)')
      ->setCellValue('C15', 'c.')
      ->setCellValue('D15', 'Nilai harta kekayaan (hasil konfirmasi dan hasil klarifikasi)')
      ->setCellValue('C16', 'd.')
      ->setCellValue('D16', 'Perbandingan Penghasilan Bersih dengan perolehan harta (Berdasarkan periode pemeriksaan)')
      ->setCellValue('C17', 'e.')
      ->setCellValue('D17', 'Ringkasan atas transaksi keuangan')
      ->setCellValue('C18', 'f.')
      ->setCellValue('D18', 'Kesesuaian hasil temuan dengan informasi dugaan tindak pidana korupsi')
      ->setCellValue('C19', 'g.')
      ->setCellValue('D19', 'Keterkaitan harta kekayaan dan transaksi keuangan dengan dugaan tindak pidana korupsi atau tindak pidana lain')
      ->setCellValue('B20', '3')
      ->setCellValue('C20', 'Mendokumentasikan seluruh dokumen hasil konfirmasi dan hasil klarifikasi ke dalam ordner yang telah disediakan.')->mergeCells('C20:D20')
      ->setCellValue('B21', '4')
      ->setCellValue('C21', 'Menyimpan salinan seluruh dokumen hasil konfirmasi dan hasil klarifikasi dalam bentuk softcopy ke dalam manajemen dokumen e-audit')->mergeCells('C21:D21')
      ->setCellValue('B22', '5')
      ->setCellValue('C22', 'Mengirimkan pemberitahuan hasil pemeriksaan melalui aplikasi e-audit dan surat pemberitahuan kepada PN apabila terdapat harta kekayaan yang belum dilaporkan (Hasil Konfirmasi dan Hasil Klarifikasi). Untuk PN yang diklarifikasi terdapat prosedur tambahan yaitu diterbitkannya LHKPN Perbaikan atas hasil klarifikasi tersebut.')->mergeCells('C22:D22')
      ->setCellValue('B23', '6')
      ->setCellValue('C23', 'Klik selesai pada aplikasi e-audit dengan mengisikan nomor Laporan Hasil Pemeriksaan (LHP) dan keterangan tindak lanjut pemeriksaan (Database/Limpah)')->mergeCells('C23:D23')
      ->setCellValue('B24', '7')
      ->setCellValue('C24', 'Membuat Nota Dinas kepada Direktorat / Kedeputian terkait dalam hal terdapat adanya temuan hasil pemeriksaan yang patut ditindaklanjuti.')->mergeCells('C24:D24');

      $this->style_table_header(
      'B4:E4',
      $this->color_gray,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(3);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(70);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);

      $spreadsheet->getActiveSheet()->getStyle("B1:E2")->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->getStyle("B1:E2")->getFont()->setBold(true);

      $spreadsheet->getActiveSheet()->getStyle('B1:E2')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B4:E4')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('B6:B24')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('E6:E24')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C7:C11')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C13:C19')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

      $spreadsheet->getActiveSheet()->getStyle('B4:E4')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle('C7:C19')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('D7:D19')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('B6:C24')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
      $spreadsheet->getActiveSheet()->getStyle('E6:E24')
      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

      $spreadsheet->getActiveSheet()->getStyle('D10:D11')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('D16:D19')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C12')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
      $spreadsheet->getActiveSheet()->getStyle('C20:C24')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

       $this->style_table_header('B5:E5', $this->color_brown, $spreadsheet, 
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->setSize(12);
      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->setBold(true);
      $spreadsheet->getActiveSheet()->getStyle("B3")->getFont()->getColor()->setARGB($this->color_blue);
 
      $spreadsheet->getActiveSheet()->getRowDimension(6)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(10)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(11)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(12)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(19)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(20)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(21)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(22)->setRowHeight(55); 
      $spreadsheet->getActiveSheet()->getRowDimension(23)->setRowHeight(30); 
      $spreadsheet->getActiveSheet()->getRowDimension(24)->setRowHeight(30);

      $spreadsheet->getActiveSheet()->getStyle('C6')->getAlignment()->setWrapText(true);
      
      $styleArray = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => $this->color_black],
          ],
        ]
      ];
  
      $spreadsheet->getActiveSheet()->getStyle('B4:E24')->applyFromArray($styleArray);
  }


  // --------- private functions
  private function style_table_header($startEndCell, $bgcolor, $spreadsheet, $text_align = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, $fontcolor = \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE, $isBold = true, $isWrapText = true)
  {
    # code...
    if ($bgcolor != '') {
      $spreadsheet->getActiveSheet()->getStyle($startEndCell)
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
      $spreadsheet->getActiveSheet()->getStyle($startEndCell)
      ->getFill()->getStartColor()->setARGB($bgcolor);
    }

    $styleArray4 = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => $this->color_grayborder],
        ],
      ],
      // 'fill' => [
      //   'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
      //   'startColor' => [
      //     'argb' => $this->color_white,
      //   ],
      // ],
    ];

    $spreadsheet->getActiveSheet()->getStyle($startEndCell)
      ->getFont()->getColor()->setARGB($fontcolor);
    $spreadsheet->getActiveSheet()->getStyle($startEndCell)
      ->getAlignment()->setHorizontal($text_align);
    $spreadsheet->getActiveSheet()->getStyle($startEndCell)
      ->getFont()->setBold($isBold);
    $spreadsheet->getActiveSheet()->getStyle($startEndCell)->getAlignment()->setWrapText($isWrapText);
    $spreadsheet->getActiveSheet()->getStyle($startEndCell)->applyFromArray($styleArray4);

  }

  private function print_list_data($list_data, $startRow, $spreadsheet)
  {

    foreach ($list_data as $i => $data) {

      //row number
      $startCol = 1;
      $spreadsheet->getActiveSheet()
      ->setCellValueByColumnAndRow($startCol++, $startRow, $i+1);
      // dd($data);
      foreach ($data as $prop => $d) {

        // set input untuk field atas nama
        if ($prop == 'ATAS_NAMA' || $prop == 'ATAS_NAMA_REKENING') {
          if ($d == 1)
          $d = 'PN YANG BERSANGKUTAN';
          else if ($d == 2)
          $d = 'PASANGAN / ANAK';
          else if ( $d == 3 )
          $d = 'LAINNYA';
        }

        if ($prop == 'ASAL_USUL') {
          if ($d == NULL)
            $d = '-';
          else
            $d = $this->__get_asal_usul_harta($d);
        }
          
        // disable tgl_lapor & Jenis laporan
        if ( ($prop == 'tgl_lapor') || ($prop == 'JENIS_LAPORAN') || ($prop == 'ID_LHKPN'))
        $d = '';


        if ($prop == 'NAMA_BANK' || $prop == 'NOMOR_REKENING' ){
//              decrypt
            if (strlen(trim($d)) >= 32){
                $d = encrypt_username($d,'d');
            }
          }

        if ($prop == 'STATUS'){
            if ($d == 1)
              $d = 'Tetap';
            else if ($d == 2)
              $d = 'Ubah';
            else if ( $d == 3 )
              $d = 'Baru';
        }

        if ($prop == 'IS_PELEPASAN'){
          if ($d == 1){
             $d = 'Lepas';
          }
        }

        if ($prop == 'NILAI_PELAPORAN'){
             $d = (float) $d;
        }

        // remove field from nilai perolehan
        // if (($prop != 'NILAI_PEROLEHAN') && ($prop != 'AWAL_HUTANG')){
        //   // echo "<pre>";
        //   // echo ".".$prop;
        //   // echo "</pre>";
        //   $spreadsheet->getActiveSheet()
        //   ->setCellValueByColumnAndRow($startCol, $startRow, $d);
        //   $startCol++;
        // }
        $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow($startCol, $startRow, $d);
        $startCol++;

      }
      $startRow++;
    }
  }

  private function lampiran_header($judul_lampiran, $spreadsheet)
  {
    //
    $spreadsheet->getActiveSheet()
      ->setCellValue('B1', $judul_lampiran)
      ->setCellValue('B2', 'Nama')->setCellValue('C2', '=": "&PROFIL!D7')
      ->setCellValue('B3', 'Jabatan')->setCellValue('C3', '=": "&PROFIL!D22')
      ->setCellValue('B4', 'Jenis Laporan')->setCellValue('C4', '=": "&PROFIL!D4')
      ->setCellValue('B5', 'Tanggal Lapor')->setCellValue('C5', '=": "&PROFIL!D6');
  }

  private function lampiran_table_header($judul, $startRow, $spreadsheet, $header_color)
  {
    $offset1 = $startRow+1;
    $offset2 = $startRow+2;
    $offset3 = $startRow+3;

    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.$offset1, 'No.')->mergeCells('A'.$offset1 .':A'.$offset3)
      ->setCellValue('B'.$offset1, 'Lokasi')->mergeCells('B'.$offset1 .':B'.$offset3)
      ->setCellValue('C'.$offset1, 'Luas (M2)')->mergeCells('C'.$offset1 .':D'.$offset1)
      ->setCellValue('C'.$offset2, 'Tanah')->mergeCells('C'.$offset2 .':C'.$offset3)
      ->setCellValue('D'.$offset2, 'Bangunan')->mergeCells('D'.$offset2 .':D'.$offset3)
      ->setCellValue('E'.$offset1, 'Kepemilikan')->mergeCells('E'.$offset1 .':J'.$offset1)
      ->setCellValue('E'.$offset2, 'Jenis Bukti')->mergeCells('E'.$offset2 .':E'.$offset3)
      ->setCellValue('F'.$offset2, 'Nomor Bukti')->mergeCells('F'.$offset2 .':F'.$offset3)
      ->setCellValue('G'.$offset2, 'Atas Nama')->mergeCells('G'.$offset2 .':G'.$offset3)
      ->setCellValue('H'.$offset2, 'Asal Usul Harta')->mergeCells('H'.$offset2 .':H'.$offset3)
      ->setCellValue('I'.$offset2, 'Pemanfaatan')->mergeCells('I'.$offset2 .':I'.$offset3)
      ->setCellValue('J'.$offset2, 'Tahun Perolehan')->mergeCells('J'.$offset2 .':J'.$offset3)
      ->setCellValue('K'.$offset1, 'Status')->mergeCells('K'.$offset1 .':K'.$offset3)
      ->setCellValue('L'.$offset1, 'Nilai Perolehan')->mergeCells('L'.$offset1.':L'.$offset3)
      ->setCellValue('M'.$offset1, 'Menurut PN')
      ->setCellValue('M'.$offset2, '=PROFIL!D4')
      ->setCellValue('M'.$offset3, '=PROFIL!D6')
      ->setCellValue('N'.$offset1, 'HASIL KLARIFIKASI')->mergeCells('N'.$offset1 .':N'.$offset2)
      ->setCellValue('N'.$offset3, '=PROFIL!L36')
      ->setCellValue('O'.$offset1, 'KOREKSI')->mergeCells('O'.$offset1 .':O'.$offset3)
      ->setCellValue('P'.$offset1, 'KETERANGAN')->mergeCells('P'.$offset1 .':P'.$offset3);

      $this->style_table_header('A'.$offset1 .':P'.$offset3, $header_color, $spreadsheet);

  }

  private function lampiran_table_header_2($judul, $startRow, $spreadsheet, $header_color)
  {

    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.($startRow+1),'No.')
      ->mergeCells('A'.($startRow+1).':A'.($startRow+3))
      ->setCellValue('B'.($startRow+1),'Uraian') // group header
      ->mergeCells('B'.($startRow+1).':F'.($startRow+1))
      ->setCellValue('B'.($startRow+2),'Jenis')
      ->mergeCells('B'.($startRow+2).':B'.($startRow+3))
      ->setCellValue('C'.($startRow+2),'Merek')
      ->mergeCells('C'.($startRow+2).':C'.($startRow+3))
      ->setCellValue('D'.($startRow+2),'Tipe')
      ->mergeCells('D'.($startRow+2).':D'.($startRow+3) )
      ->setCellValue('E'.($startRow+2),'Tahun Pembuatan')
      ->mergeCells('E'.($startRow+2).':E'.($startRow+3))
      ->setCellValue('F'.($startRow+2),'No Pol. / Registrasi')
      ->mergeCells('F'.($startRow+2).':F'.($startRow+3))
      ->setCellValue('G'.($startRow+1),'Kepemilikan') // group header
      ->mergeCells('G'.($startRow+1).':L'.($startRow+1))
      ->setCellValue('G'.($startRow+2),'Jenis Bukti')
      ->mergeCells('G'.($startRow+2).':G'.($startRow+3))
      ->setCellValue('H'.($startRow+2),'Asal Usul Harta')
      ->mergeCells('H'.($startRow+2).':H'.($startRow+3))
      ->setCellValue('I'.($startRow+2),'Atas Nama')
      ->mergeCells('I'.($startRow+2).':I'.($startRow+3))
      ->setCellValue('J'.($startRow+2),'Pemanfaatan')
      ->mergeCells('J'.($startRow+2).':J'.($startRow+3))
      ->setCellValue('K'.($startRow+2),'Tahun Perolehan')
      ->mergeCells('K'.($startRow+2).':K'.($startRow+3))
      ->setCellValue('L'.($startRow+2),'Keterangan Lainnya')
      ->mergeCells('L'.($startRow+2).':L'.($startRow+3))
      ->setCellValue('M'.($startRow+1),'Status')->mergeCells('M'.($startRow+1).':M'.($startRow+3))
      ->setCellValue('N'.($startRow+1),'Nilai Perolehan')->mergeCells('N'.($startRow+1).':N'.($startRow+3))
      ->setCellValue('O'.($startRow+1),'Menurut PN')
      ->setCellValue('O'.($startRow+2), '=PROFIL!D4')
      ->setCellValue('O'.($startRow+3), '=PROFIL!D6' )
      ->setCellValue('P'.($startRow+1), 'HASIL KLARIFIKASI' )->mergeCells('P'.($startRow+1).':P'.($startRow+2))
      ->setCellValue('P'.($startRow+3), '=PROFIL!L36')
      ->setCellValue('Q'.($startRow+1), 'KOREKSI' )->mergeCells('Q'.($startRow+1).':Q'.($startRow+3))
      ->setCellValue('R'.($startRow+1), 'KETERANGAN' )->mergeCells('R'.($startRow+1).':R'.($startRow+3));

      $this->style_table_header('A'.($startRow+1) .':R'.($startRow+3), $header_color, $spreadsheet);
  }

  public function lampiran_table_header_3($judul, $startRow, $spreadsheet, $header_color)
  {
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.($startRow+1),'No.')
      ->mergeCells('A'.($startRow+1).':A'.($startRow+3))
      ->setCellValue('B'.($startRow+1),'Uraian') // group header
      ->mergeCells('B'.($startRow+1).':F'.($startRow+1))
      ->setCellValue('B'.($startRow+2),'Jenis')
      ->mergeCells('B'.($startRow+2).':B'.($startRow+3))
      ->setCellValue('C'.($startRow+2),'Jumlah')
      ->mergeCells('C'.($startRow+2).':C'.($startRow+3))
      ->setCellValue('D'.($startRow+2),'Satuan')
      ->mergeCells('D'.($startRow+2).':D'.($startRow+3))
      ->setCellValue('E'.($startRow+2), 'Keterangan Lainnya')
      ->mergeCells('E'.($startRow+2).':E'.($startRow+3))
      ->setCellValue('F'.($startRow+2), 'Tahun Perolehan')
      ->mergeCells('F'.($startRow+2).':F'.($startRow+3))
      ->setCellValue('G'.($startRow+1),'Asal Usul Harta')
      ->mergeCells('G'.($startRow+1).':G'.($startRow+3))
      ->setCellValue('H'.($startRow+1),'Status')->mergeCells('H'.($startRow+1).':H'.($startRow+3))
      ->setCellValue('I'.($startRow+1),'Nilai Perolehan')->mergeCells('I'.($startRow+1).':I'.($startRow+3))
      ->setCellValue('J'.($startRow+1),'Menurut PN')
      ->setCellValue('J'.($startRow+2), '=PROFIL!D4')
      ->setCellValue('J'.($startRow+3), '=PROFIL!D6')
      ->setCellValue('K'.($startRow+1), 'HASIL KLARIFIKASI' )->mergeCells('K'.($startRow+1).':K'.($startRow+2))
      ->setCellValue('K'.($startRow+3), '=PROFIL!L36' )
      ->setCellValue('L'.($startRow+1), 'KOREKSI' )->mergeCells('L'.($startRow+1).':L'.($startRow+3))
      ->setCellValue('M'.($startRow+1), 'KETERANGAN' )->mergeCells('M'.($startRow+1).':M'.($startRow+3));

      $this->style_table_header('A'.($startRow+1) .':M'.($startRow+3), $header_color, $spreadsheet);
  }

  public function lampiran_table_header_4($judul, $startRow, $spreadsheet, $header_color)
  {

    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.($startRow+1),'No.')->mergeCells('A'.($startRow+1).':A'.($startRow+3))
      ->setCellValue('B'.($startRow+1),'Uraian') // group header
      ->mergeCells('B'.($startRow+1).':F'.($startRow+1))
      ->setCellValue('B'.($startRow+2),'Jenis')->mergeCells('B'.($startRow+2).':B'.($startRow+3))
      ->setCellValue('C'.($startRow+2),'Atas Nama')->mergeCells('C'.($startRow+2).':C'.($startRow+3))
      ->setCellValue('D'.($startRow+2),'Penerbit / Perusahaan')->mergeCells('D'.($startRow+2).':D'.($startRow+3))
      ->setCellValue('E'.($startRow+2), 'Kustodian / Sekuritas')->mergeCells('E'.($startRow+2).':E'.($startRow+3))
      ->setCellValue('F'.($startRow+2), 'Tahun Perolehan')->mergeCells('F'.($startRow+2).':F'.($startRow+3))
      ->setCellValue('G'.($startRow+1),'No. Rekening / ID Nasabah')->mergeCells('G'.($startRow+1).':G'.($startRow+3))
      ->setCellValue('H'.($startRow+1),'Asal Usul Harta')->mergeCells('H'.($startRow+1).':H'.($startRow+3))
      ->setCellValue('I'.($startRow+1),'Status')->mergeCells('I'.($startRow+1).':I'.($startRow+3))
      ->setCellValue('J'.($startRow+1),'Nilai Perolehan')->mergeCells('J'.($startRow+1).':J'.($startRow+3))
      ->setCellValue('K'.($startRow+1),'Menurut PN')
      ->setCellValue('K'.($startRow+2), '=PROFIL!D4')
      ->setCellValue('K'.($startRow+3), '=PROFIL!D6')
      ->setCellValue('L'.($startRow+1), 'HASIL KLARIFIKASI' )->mergeCells('L'.($startRow+1).':L'.($startRow+2))
      ->setCellValue('L'.($startRow+3), '=PROFIL!L36' )
      ->setCellValue('M'.($startRow+1), 'KOREKSI' )->mergeCells('M'.($startRow+1).':M'.($startRow+3))
      ->setCellValue('N'.($startRow+1), 'KETERANGAN' )->mergeCells('N'.($startRow+1).':N'.($startRow+3));

      $this->style_table_header('A'.($startRow+1) .':N'.($startRow+3), $header_color, $spreadsheet);
  }

  public function lampiran_table_header_5a($judul, $startRow, $spreadsheet, $header_color)
  {

    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.($startRow+1),'No.')->mergeCells('A'.($startRow+1).':A'.($startRow+3))
      ->setCellValue('B'.($startRow+1),'Uraian') // group header
      ->mergeCells('B'.($startRow+1).':D'.($startRow+1))
      ->setCellValue('B'.($startRow+2),'Jenis')->mergeCells('B'.($startRow+2).':B'.($startRow+3))
      ->setCellValue('C'.($startRow+2),'Keterangan')->mergeCells('C'.($startRow+2).':C'.($startRow+3))
      ->setCellValue('D'.($startRow+2),'Nama Bank / Lembaga ')->mergeCells('D'.($startRow+2).':D'.($startRow+3))
      ->setCellValue('E'.($startRow+1), 'Info Rekening')->mergeCells('E'.($startRow+1).':H'.($startRow+1))
      ->setCellValue('E'.($startRow+2),'Nomor')->mergeCells('E'.($startRow+2).':E'.($startRow+3))
      ->setCellValue('F'.($startRow+2),'Atas Nama')->mergeCells('F'.($startRow+2).':F'.($startRow+3))
      ->setCellValue('G'.($startRow+2),'Keterangan')->mergeCells('G'.($startRow+2).':G'.($startRow+3))
      ->setCellValue('H'.($startRow+2),'Tahun Buka Rekening')->mergeCells('H'.($startRow+2).':H'.($startRow+3))
      ->setCellValue('I'.($startRow+1),'Asal Usul Harta')->mergeCells('I'.($startRow+1).':I'.($startRow+3))
      ->setCellValue('J'.($startRow+1),'Status')->mergeCells('J'.($startRow+1).':J'.($startRow+3))
      ->setCellValue('K'.($startRow+1),'Menurut PN')
      ->setCellValue('K'.($startRow+2), '=PROFIL!D4')
      ->setCellValue('K'.($startRow+3), '=PROFIL!D6')
      ->setCellValue('L'.($startRow+1), 'HASIL KLARIFIKASI' )->mergeCells('L'.($startRow+1).':L'.($startRow+2))
      ->setCellValue('L'.($startRow+3), '=PROFIL!L36' )
      ->setCellValue('M'.($startRow+1), 'KOREKSI' )->mergeCells('M'.($startRow+1).':M'.($startRow+3))
      ->setCellValue('N'.($startRow+1), 'KETERANGAN' )->mergeCells('N'.($startRow+1).':N'.($startRow+3));

      $this->style_table_header('A'.($startRow+1) .':N'.($startRow+3), $header_color, $spreadsheet);
  }

  public function lampiran_table_header_5b($judul, $startRow, $spreadsheet, $header_color)
  {

    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.($startRow+1),'No.')->mergeCells('A'.($startRow+1).':A'.($startRow+3))
      ->setCellValue('B'.($startRow+1),'Uraian') // group header
      ->mergeCells('B'.($startRow+1).':D'.($startRow+1))
      ->setCellValue('B'.($startRow+2),'Jenis')->mergeCells('B'.($startRow+2).':B'.($startRow+3))
      ->setCellValue('C'.($startRow+2),'Keterangan')->mergeCells('C'.($startRow+2).':C'.($startRow+3))
      ->setCellValue('D'.($startRow+2),'Nama Bank / Lembaga ')->mergeCells('D'.($startRow+2).':D'.($startRow+3))
      ->setCellValue('E'.($startRow+1), 'Info Rekening')->mergeCells('E'.($startRow+1).':H'.($startRow+1))
      ->setCellValue('E'.($startRow+2),'Nomor')->mergeCells('E'.($startRow+2).':E'.($startRow+3))
      ->setCellValue('F'.($startRow+2),'Atas Nama')->mergeCells('F'.($startRow+2).':F'.($startRow+3))
      ->setCellValue('G'.($startRow+2),'Tahun Buka Rekening')->mergeCells('G'.($startRow+2).':G'.($startRow+3))
      ->setCellValue('H'.($startRow+2),'Tahun Tutup Rekening')->mergeCells('H'.($startRow+2).':H'.($startRow+3))
      ->setCellValue('I'.($startRow+1),'Asal Usul Harta')->mergeCells('I'.($startRow+1).':I'.($startRow+3))
      ->setCellValue('J'.($startRow+1),'Status')->mergeCells('J'.($startRow+1).':J'.($startRow+3))
      ->setCellValue('K'.($startRow+1),'Menurut PN')
      ->setCellValue('K'.($startRow+2), '=PROFIL!D4')
      ->setCellValue('K'.($startRow+3), '=PROFIL!D6')
      ->setCellValue('L'.($startRow+1), 'HASIL KLARIFIKASI' )->mergeCells('L'.($startRow+1).':L'.($startRow+2))
      ->setCellValue('L'.($startRow+3), '=PROFIL!L36' )
      ->setCellValue('M'.($startRow+1), 'KOREKSI' )->mergeCells('M'.($startRow+1).':M'.($startRow+3))
      ->setCellValue('N'.($startRow+1), 'KETERANGAN' )->mergeCells('N'.($startRow+1).':N'.($startRow+3));

      $this->style_table_header('A'.($startRow+1) .':N'.($startRow+3), $header_color, $spreadsheet);
  }

  public function lampiran_table_header_6($judul, $startRow, $spreadsheet, $header_color)
  {

    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.($startRow+1),'No.')->mergeCells('A'.($startRow+1).':A'.($startRow+3))
      ->setCellValue('B'.($startRow+1),'Uraian') // group header
      ->mergeCells('B'.($startRow+1).':D'.($startRow+1))
      ->setCellValue('B'.($startRow+2),'Jenis')->mergeCells('B'.($startRow+2).':B'.($startRow+3))
      ->setCellValue('C'.($startRow+2),'Keterangan')->mergeCells('C'.($startRow+2).':C'.($startRow+3))
      ->setCellValue('D'.($startRow+2),'Tahun Perolehan')->mergeCells('D'.($startRow+2).':D'.($startRow+3))
      ->setCellValue('E'.($startRow+1),'Asal Usul Harta')->mergeCells('E'.($startRow+1).':E'.($startRow+3))
      ->setCellValue('F'.($startRow+1),'Status')->mergeCells('F'.($startRow+1).':F'.($startRow+3))
      ->setCellValue('G'.($startRow+1),'Nilai Perolehan')->mergeCells('G'.($startRow+1).':G'.($startRow+3))
      ->setCellValue('H'.($startRow+1),'Menurut PN')
      ->setCellValue('H'.($startRow+2), '=PROFIL!D4')
      ->setCellValue('H'.($startRow+3), '=PROFIL!D6')
      ->setCellValue('I'.($startRow+1), 'HASIL KLARIFIKASI' )->mergeCells('I'.($startRow+1).':I'.($startRow+2))
      ->setCellValue('I'.($startRow+3), '=PROFIL!L36' )
      ->setCellValue('J'.($startRow+1), 'KOREKSJ' )->mergeCells('J'.($startRow+1).':J'.($startRow+3))
      ->setCellValue('K'.($startRow+1), 'KETERANGAN' )->mergeCells('K'.($startRow+1).':K'.($startRow+3));

      $this->style_table_header('A'.($startRow+1) .':K'.($startRow+3), $header_color, $spreadsheet);
  }

  public function lampiran_table_header_7($judul, $startRow, $spreadsheet, $header_color)
  {

    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.($startRow+1),'No.')->mergeCells('A'.($startRow+1).':A'.($startRow+3))
      ->setCellValue('B'.($startRow+1),'Uraian') // group header
      ->mergeCells('B'.($startRow+1).':C'.($startRow+1))
      ->setCellValue('B'.($startRow+2),'Jenis')->mergeCells('B'.($startRow+2).':B'.($startRow+3))
      ->setCellValue('C'.($startRow+2),'Atas Nama')->mergeCells('C'.($startRow+2).':C'.($startRow+3))
      ->setCellValue('D'.($startRow+1),'Nama Kreditur')->mergeCells('D'.($startRow+1).':D'.($startRow+3))
      ->setCellValue('E'.($startRow+1),'Bentuk Agunan')->mergeCells('E'.($startRow+1).':E'.($startRow+3))
      ->setCellValue('F'.($startRow+1),'Awal Hutang')->mergeCells('F'.($startRow+1).':F'.($startRow+3))
      ->setCellValue('G'.($startRow+1),'Menurut PN (Saldo Hutang)')
      ->setCellValue('G'.($startRow+2), '=PROFIL!D4')
      ->setCellValue('G'.($startRow+3), '=PROFIL!D6')
      ->setCellValue('H'.($startRow+1), 'HASIL KLARIFIKASI' )->mergeCells('H'.($startRow+1).':H'.($startRow+2))
      ->setCellValue('H'.($startRow+3), '=PROFIL!L36' )
      ->setCellValue('I'.($startRow+1), 'KOREKSI' )->mergeCells('I'.($startRow+1).':I'.($startRow+3))
      ->setCellValue('J'.($startRow+1), 'KETERANGAN' )->mergeCells('J'.($startRow+1).':J'.($startRow+3));

      $this->style_table_header('A'.($startRow+1) .':J'.($startRow+3), $header_color, $spreadsheet);
  }

  public function lampiran_table_header_8($judul, $startRow, $spreadsheet, $header_color)
  {
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.($startRow+1),'No.')->mergeCells('A'.($startRow+1).':A'.($startRow+4))
      ->setCellValue('B'.($startRow+1),'Jenis Penerimaan')->mergeCells('B'.($startRow+1).':B'.($startRow+4))
      ->setCellValue('C'.($startRow+1),'Menurut PN')->mergeCells('C'.($startRow+1).':E'.($startRow+1))
      ->setCellValue('C'.($startRow+2),'=PROFIL!D4')->mergeCells('C'.($startRow+2).':E'.($startRow+2))
      ->setCellValue('C'.($startRow+3),'=PROFIL!D6')->mergeCells('C'.($startRow+3).':E'.($startRow+3))
      ->setCellValue('C'.($startRow+4),'PN')->setCellValue('D'.($startRow+4),'Pasangan PN')->setCellValue('E'.($startRow+4),'Total Penerimaan')
      ->setCellValue('F'.($startRow+1),'HASIL KLARIFIKASI')->mergeCells('F'.($startRow+1).':H'.($startRow+2))
      ->setCellValue('F'.($startRow+3), '=PROFIL!L36')->mergeCells('F'.($startRow+3).':H'.($startRow+3))
      ->setCellValue('F'.($startRow+4),'PN')->setCellValue('G'.($startRow+4),'Pasangan PN')->setCellValue('H'.($startRow+4),'Total Penerimaan')
      ->setCellValue('I'.($startRow+1), 'KOREKSI' )->mergeCells('I'.($startRow+1).':K'.($startRow+3))
      ->setCellValue('I'.($startRow+4),'PN')->setCellValue('J'.($startRow+4),'Pasangan PN')->setCellValue('K'.($startRow+4),'Total Penerimaan')
      ->setCellValue('L'.($startRow+1), 'KETERANGAN' )->mergeCells('L'.($startRow+1).':L'.($startRow+4));


    $spreadsheet->getActiveSheet()
      ->setCellValue('A13','A. Penerimaan Dari Pekerjaan')
      ->setCellValue('A14','1')
      ->setCellValue('B14','Gaji dan tunjangan')
      ->setCellValue('A15','2')
      ->setCellValue('B15','Penghasilan dari profesi/keahlian')
      ->setCellValue('A16','3')
      ->setCellValue('B16','Honorarium')
      ->setCellValue('A17','4')
      ->setCellValue('B17','Tantiem, bonus, jasa produksi, THR')
      ->setCellValue('A18','5')
      ->setCellValue('B18','Penerimaan dari pekerjaan lainnya')
      ->setCellValue('A19','SUB TOTAL')
      ->mergeCells('A19:B19');


    $spreadsheet->getActiveSheet()
      ->setCellValue('A20','B. Penerimaan Dari Usaha dan Kekayaan')
      ->setCellValue('A21','1')
      ->setCellValue('B21','Hasil investasi dalam surat berharga')
      ->setCellValue('A22','2')
      ->setCellValue('B22','Hasil usaha/Sewa')
      ->setCellValue('A23','3')
      ->setCellValue('B23','Bunga tabungan/deposito, dan lainnya')
      ->setCellValue('A24','4')
      ->setCellValue('B24','Penjualan atau pelepasan harta')
      ->setCellValue('A25','5')
      ->setCellValue('B25','Penerimaan lainnya')
      ->setCellValue('A26','SUB TOTAL')
      ->mergeCells('A26:B26');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A27','C. Penerimaan Lainnya')
      ->setCellValue('A28','1')
      ->setCellValue('B28','Penerimaan hutang')
      ->setCellValue('A29','2')
      ->setCellValue('B29','Penerimaan warisan')
      ->setCellValue('A30','3')
      ->setCellValue('B30','Penerimaan hibah/hadiah')
      ->setCellValue('A31','4')
      ->setCellValue('B31','Lainnya')
      ->setCellValue('A32','SUB TOTAL')
      ->mergeCells('A32:B32');

    $this->style_table_header('A'.($startRow+1) .':L'.($startRow+4), $header_color, $spreadsheet);

  }

  public function lampiran_table_header_9($judul, $startRow, $spreadsheet, $header_color)
  {
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.($startRow+1), 'No.')->mergeCells('A'.($startRow+1).':A'.($startRow+3))
      ->setCellValue('B'.($startRow+1), 'Jenis Penerimaan')->mergeCells('B'.($startRow+1).':B'.($startRow+3))
      ->setCellValue('C'.($startRow+1), 'Menurut PN')
      ->setCellValue('C'.($startRow+2), '=PROFIL!D4')
      ->setCellValue('C'.($startRow+3), '=PROFIL!D6')
      ->setCellValue('D'.($startRow+1), 'HASIL KLARIFIKASI')->mergeCells('D'.($startRow+1).':D'.($startRow+2))
      ->setCellValue('D'.($startRow+3), '=PROFIL!L36')
      ->setCellValue('E'.($startRow+1), 'KOREKSI')->mergeCells('E'.($startRow+1).':E'.($startRow+3))
      ->setCellValue('F'.($startRow+1), 'KETERANGAN')->mergeCells('F'.($startRow+1).':F'.($startRow+3));

    $this->style_table_header('A'.($startRow+1) .':F'.($startRow+3), $header_color, $spreadsheet);

  }

  private function template_table_header_lamp1($judul, $startRow, $spreadsheet, $header_color)
  {
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $judul)
      ->setCellValue('A'.($startRow+1), 'No.')->mergeCells('A'.($startRow+1).':A'.($startRow+3))
      ->setCellValue('B'.($startRow+1), 'Uraian')->mergeCells('B'.($startRow+1).':C'.($startRow+1))
      ->setCellValue('B'.($startRow+2), 'Jenis')->mergeCells('B'.($startRow+2).':B'.($startRow+3))
      ->setCellValue('C'.($startRow+2), 'Keterangan')->mergeCells('C'.($startRow+2).':C'.($startRow+3))
      ->setCellValue('D'.($startRow+1), 'Nama Harta')->mergeCells('D'.($startRow+2).':D'.($startRow+3))
      ->setCellValue('E'.($startRow+1), 'Informasi Pihak Kedua')->mergeCells('E'.($startRow+1).':F'.($startRow+1))
      ->setCellValue('E'.($startRow+2), 'Nama')->mergeCells('E'.($startRow+2).':E'.($startRow+3))
      ->setCellValue('F'.($startRow+2), 'Alamat')->mergeCells('F'.($startRow+2).':F'.($startRow+3))
      ->setCellValue('G'.($startRow+1), 'Menurut PN')
      ->setCellValue('G'.($startRow+2), '=PROFIL!D4')
      ->setCellValue('G'.($startRow+3), '=PROFIL!D6');

    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.($startRow+4), '1')
      ->setCellValue('A'.($startRow+5), '2')
      ->setCellValue('A'.($startRow+6), 'SUB TOTAL')->mergeCells('A'.($startRow+6).':F'.($startRow+6))
      ->setCellValue('G'.($startRow+6), '=SUM(G'.($startRow+4).':G'.($startRow+5).')');

    $this->style_table_header(
      'A'.($startRow+1).':G'.($startRow+3),
      $this->color_green,
      $spreadsheet);

    $this->style_table_header(
      'A'.($startRow+6).':G'.($startRow+6),
      $this->color_lightyellow,
      $spreadsheet,
      \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

    $spreadsheet->getActiveSheet()->getStyle("A".$startRow)->getFont()->setSize(12);
    $spreadsheet->getActiveSheet()->getStyle("A".$startRow)->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle("A".$startRow)->getFont()->getColor()->setARGB($this->color_blue);

  }

  private function print_koreksi($startRow, $data_lhkpn, $spreadsheet, $col_pn, $col_klarifikasi, $col_koreksi, $has_sub_total = false)
  {
    $count_data = $has_sub_total ? count($data_lhkpn) + 1 : count($data_lhkpn);
    for ($i=0; $i < $count_data; $i++) {
      # code...
      $spreadsheet->getActiveSheet()
        ->setCellValue($col_koreksi.($startRow+$i), '=IFERROR('.$col_klarifikasi.($startRow+$i).'-'.$col_pn.($startRow+$i).', 0)' );
    }
    // total koreksi
    $spreadsheet->getActiveSheet()
      ->setCellValue($col_koreksi.''.($startRow + $count_data ), '=SUM('.$col_koreksi.$startRow.':'.$col_koreksi.($startRow + $count_data -1 ).')');
  }

  private function set_hasil_klarifikasi($startRow, $data_lhkpn, $spreadsheet, $col_klarifikasi, $has_sub_total = false)
  {
    $count_data = $has_sub_total ? count($data_lhkpn) + 1 : count($data_lhkpn);

    for ($i=0; $i < $count_data; $i++) {
      # code...
      $spreadsheet->getActiveSheet()
        // ->setCellValue($col_koreksi.($startRow+$i), '=IFERROR('.$col_klarifikasi.($startRow+$i).'-'.$col_pn.($startRow+$i).', 0)' );
        ->setCellValue($col_klarifikasi.($startRow+$i), '0');
    }
  }

  private function set_col_numberFormat($colStart, $colEnd, $spreadsheet)
  {
    // echo $colStart.':'.$colEnd;
    // die();
    $spreadsheet->getActiveSheet()->getStyle($colStart.':'.$colEnd)->getNumberFormat()
      // ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
      ->setFormatCode('#,##0');
  }

  private function print_subtotal_8($spreadsheet, $startRow_disini, $startRow_disana, $part_length)
  {
    // copy value dari nilai sheet VII
    for ($i=0; $i < $part_length; $i++) {
      // menurut PN
      $spreadsheet->getActiveSheet()->setCellValue('C'.($startRow_disini+$i), '=VIII!C'.($startRow_disana+$i)); // PN
      $spreadsheet->getActiveSheet()->setCellValue('D'.($startRow_disini+$i), '=VIII!D'.($startRow_disana+$i)); // Pasangan PN
      $spreadsheet->getActiveSheet()->setCellValue('E'.($startRow_disini+$i), '=SUM(C'.($startRow_disini+$i).',D'.($startRow_disini+$i).')'); // Total PN

      // Hasil Klarfikasi
      $spreadsheet->getActiveSheet()->setCellValue('H'.($startRow_disini+$i), '=SUM(F'.($startRow_disini+$i).',G'.($startRow_disini+$i).')'); // Total PN

      // koreksi
      $spreadsheet->getActiveSheet()->setCellValue('I'.($startRow_disini+$i), '=IFERROR(F'.($startRow_disini+$i).'-C'.($startRow_disini+$i).',0)'); // PN
      $spreadsheet->getActiveSheet()->setCellValue('J'.($startRow_disini+$i), '=IFERROR(G'.($startRow_disini+$i).'-D'.($startRow_disini+$i).',0)'); // Pasangan PN
      $spreadsheet->getActiveSheet()->setCellValue('K'.($startRow_disini+$i), '=SUM(I'.($startRow_disini+$i).',J'.($startRow_disini+$i).')'); // Total PN

    }
    //subtotal --
    // menurut pn
    $spreadsheet->getActiveSheet()->setCellValue('C'.($startRow_disini+$part_length), '=SUM(C'.($startRow_disini).':C'.($startRow_disini+$part_length-1).')');
    $spreadsheet->getActiveSheet()->setCellValue('D'.($startRow_disini+$part_length), '=SUM(D'.($startRow_disini).':D'.($startRow_disini+$part_length-1).')');
    $spreadsheet->getActiveSheet()->setCellValue('E'.($startRow_disini+$part_length), '=SUM(C'.($startRow_disini+$part_length).',D'.($startRow_disini+$part_length).')');
    // hasil klarifikasi
    $spreadsheet->getActiveSheet()->setCellValue('F'.($startRow_disini+$part_length), '=SUM(F'.($startRow_disini).':F'.($startRow_disini+$part_length-1).')');
    $spreadsheet->getActiveSheet()->setCellValue('G'.($startRow_disini+$part_length), '=SUM(G'.($startRow_disini).':G'.($startRow_disini+$part_length-1).')');
    $spreadsheet->getActiveSheet()->setCellValue('H'.($startRow_disini+$part_length), '=SUM(F'.($startRow_disini+$part_length).',G'.($startRow_disini+$part_length).')');
    // koreksi
    $spreadsheet->getActiveSheet()->setCellValue('I'.($startRow_disini+$part_length), '=SUM(I'.($startRow_disini).':I'.($startRow_disini+$part_length-1).')');
    $spreadsheet->getActiveSheet()->setCellValue('J'.($startRow_disini+$part_length), '=SUM(J'.($startRow_disini).':J'.($startRow_disini+$part_length-1).')');
    $spreadsheet->getActiveSheet()->setCellValue('K'.($startRow_disini+$part_length), '=SUM(I'.($startRow_disini+$part_length).',J'.($startRow_disini+$part_length).')');
  }

  private function print_subtotal_9($spreadsheet, $startRow_disini, $startRow_disana, $part_length)
  {
    for ($i=0; $i < $part_length; $i++) {
      // konten dan subtotal
      $spreadsheet->getActiveSheet()
        ->setCellValue('C'.($startRow_disini+$part_length), '=SUM(C'.($startRow_disini).':C'.($startRow_disini+$part_length-1).')');

    }
  }

  private function lampiran_row_CL($startRow, $spreadsheet, $rowNumber)
  {
    # code...
    $spreadsheet->getActiveSheet()
      ->setCellValue('A'.$startRow, $rowNumber)
      ->setCellValue('C'.($startRow+1), 'Alamat')->mergeCells('C'.($startRow+1).':F'.($startRow+3))
      ->mergeCells('G'.($startRow+1).':N'.($startRow+3))
      ->setCellValue('C'.($startRow+4), 'Kota')->mergeCells('C'.($startRow+4).':F'.($startRow+4))
      ->mergeCells('G'.($startRow+4).':N'.($startRow+4))
      ->setCellValue('C'.($startRow+5), 'LT / LB')->mergeCells('C'.($startRow+5).':F'.($startRow+5))
      ->mergeCells('G'.($startRow+5).':N'.($startRow+5))
      ->setCellValue('C'.($startRow+6), 'No Surat')->mergeCells('C'.($startRow+6).':F'.($startRow+6))
      ->mergeCells('G'.($startRow+6).':N'.($startRow+6))
      ->setCellValue('C'.($startRow+7), 'Asal Informasi')->mergeCells('C'.($startRow+7).':F'.($startRow+7))
      ->mergeCells('G'.($startRow+7).':N'.($startRow+7))
      ->setCellValue('C'.($startRow+8), 'Atas Nama')->mergeCells('C'.($startRow+8).':F'.($startRow+8))
      ->mergeCells('G'.($startRow+8).':N'.($startRow+8))
      ->setCellValue('C'.($startRow+9), 'Tgl CF')->mergeCells('C'.($startRow+9).':F'.($startRow+9))
      ->mergeCells('G'.($startRow+9).':N'.($startRow+9))
      ->setCellValue('C'.($startRow+10), 'Koordinat')->mergeCells('C'.($startRow+10).':F'.($startRow+10))
      ->mergeCells('G'.($startRow+10).':N'.($startRow+10))
      ->setCellValue('C'.($startRow+11), 'Zona Nilai Tanah')->mergeCells('C'.($startRow+11).':F'.($startRow+11))
      ->mergeCells('G'.($startRow+11).':N'.($startRow+11));

    $spreadsheet->getActiveSheet()->getStyle('C'.($startRow+1).':C'.($startRow+11))->getFont()->setBold(true);

    $spreadsheet->getActiveSheet()
      ->setCellValue('Q'.$startRow, 'Foto')
      ->mergeCells('R'.($startRow+1).':Z'.($startRow+11));
    $spreadsheet->getActiveSheet()->getStyle('Q'.$startRow)->getFont()->setBold(true);

    $spreadsheet->getActiveSheet()
      ->setCellValue('AC'.$startRow, 'Foto')
      ->mergeCells('AD'.($startRow+1).':AL'.($startRow+11));
    $spreadsheet->getActiveSheet()->getStyle('AC'.$startRow)->getFont()->setBold(true);

    $spreadsheet->getActiveSheet()
      ->setCellValue('AO'.$startRow, 'Keterangan')
      ->mergeCells('AP'.($startRow+1).':AX'.($startRow+11));
    $spreadsheet->getActiveSheet()->getStyle('AO'.$startRow)->getFont()->setBold(true);

    $styleArray = [
      'borders' => [
        'outline' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
          'color' => ['argb' => $this->color_blue],
        ],
      ],
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
          'argb' => $this->color_gray,
        ],
      ],
    ];

    $styleArray4 = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => $this->color_lightgray],
        ],
      ],
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
          'argb' => $this->color_white,
        ],
      ],
    ];

    $styleArray2 = [
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
          'argb' => $this->color_darkgray,
        ],
      ],
    ];

    $styleArray3 = [
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
          'argb' => $this->color_white,
        ],
      ],
    ];

    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow)
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $spreadsheet->getActiveSheet()->getStyle('B'.$startRow.':O'.($startRow+12))->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C'.($startRow+1).':N'.($startRow+11))->applyFromArray($styleArray4);
    $spreadsheet->getActiveSheet()->getStyle('Q'.$startRow.':AA'.($startRow+12))->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('R'.($startRow+1).':Z'.($startRow+11))->applyFromArray($styleArray2);
    $spreadsheet->getActiveSheet()->getStyle('AC'.$startRow.':AM'.($startRow+12))->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('AD'.($startRow+1).':AL'.($startRow+11))->applyFromArray($styleArray2);
    $spreadsheet->getActiveSheet()->getStyle('AO'.$startRow.':AY'.($startRow+12))->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('AP'.($startRow+1).':AX'.($startRow+11))->applyFromArray($styleArray3);

  }

 private function cek_is_null($var)
 {
   if (is_null($var)) {
     return '-';
   } else {
     return $var;
   }
 }

 private function var_is_null($var)
 {
   if (is_null($var) || is_string($var)) {
     return TRUE;
   } else {
     return FALSE;
   }
 }

  private function __get_asal_usul_harta($au) {
    $asalusul = ['1' => 'HASIL SENDIRI', '2' => 'WARISAN', '3' => 'HIBAH DENGAN AKTA', '4' => 'HIBAH TANPA AKTA', '5' => 'HADIAH', '6' => 'LAINNYA'];
    $asal_usul = array();
    $asal_usul = explode(',', $au);
    if (count($asal_usul) > 0) {
        $banyak_asalusul = count($asal_usul);
        $i = 1;
        $asalusuls = '';
        foreach ($asal_usul as $row) {
            if (array_key_exists($row, $asalusul)) {
                $pisah = ($banyak_asalusul === $i ? "" : ", ");
                $asalusuls .= $asalusul[$row] . $pisah;
                $i++;
            }
        }
    } else {
        echo "----";
    }
    return $asalusuls;
  }

}
