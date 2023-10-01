<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// call php-spreadsheet library
require APPPATH.'/third_party/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Cetak extends CI_Controller {

  // CONST for color
  var $color_green = '00008080';
  var $color_red = '00632523';
  var $color_yellow = '00ffc000';
  var $color_black = '00000000';
  var $color_lightyellow = '00ffff66';
  var $color_blue = '001f497d';
  var $color_gray = '00eeece1';
  var $color_darkgray = '00d9d9d9';
  var $color_lightgray = '00d6d6d6';
  var $color_white = '00ffffff';
  var $color_grayborder = '009c9c9c';

  // data variables
  public $data_pn = '';
  public $daftar_pn = '';
  public $data_wl = '';

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    // $this->load->library('Excel');
    $this->load->model('mpn');
    $this->load->model('Mmpnwn');
    $this->load->model('Muser');
    $this->load->model('Mlhkpn');
  }


  public function export($tipe, $text, $jenis, $instansi, $unit_kerja)
  {
    $spreadsheet = new Spreadsheet();

    $styleArray = array(
    'font'  => array(
        'size'  => 10,
        'name'  => 'Calibri'
    ));
    $spreadsheet->getDefaultStyle()
      ->applyFromArray($styleArray);
    $cari = new StdClass();
    $cari->unit_kerja = $unit_kerja;
    $cari->text = $text;
    $cari->jenis = $jenis;
    $cari->instansi = $instansi;
    if($instansi == 'ALL'){$cari->instansi = '';}
    if($unit_kerja == 'ALL'){$cari->unit_kerja = '';}
    if($text == 'ALL'){$cari->text = '';}
    if($jenis == 'ALL'){$cari->jenis = '';}

    
    // initiate the database
    $this->daftar_pn                       = $this->mpn->get_daftar_pn_individual_two_ctk($instansi, NULL, $cari, NULL, NULL);
    $this->daftar_perubahan                = $this->mpn->get_daftar_pn_individual_two_ctk($instansi, NULL, $cari, NULL, $tipe);
    $this->daftar_wl                       = $this->mpn->get_daftar_wl_ctk($instansi, NULL, $cari, NULL, NULL);
    $this->daftar_nwl                       = $this->mpn->get_daftar_nwl_ctk($instansi, NULL, $cari, NULL, NULL);

        // --- start excel content, the magic goes here --- //
    $this->cetak_add($tipe, $spreadsheet);

    // --- end excel content --- //

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    //setup file meta
    $filename = 'Verifikasi_Daftar_Individual '.date('dmyGi').'.xlsx';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // ------ save and export to folder download

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    echo "Excel is generated";
  }

  public function export_wl($text, $tahun, $instansi, $uk, $is_wl)
  {
    $spreadsheet = new Spreadsheet();

    $styleArray = array(
    'font'  => array(
        'size'  => 10,
        'name'  => 'Calibri'
    ));
    $spreadsheet->getDefaultStyle()
      ->applyFromArray($styleArray);

  
    $cari = new StdClass();
    $cari->text = ($text == 'ALL') ? '': $text;
    $cari->tahun = ($tahun == 'ALL') ? '': $tahun;
    $cari->instansi = ($instansi == 'ALL') ? '': $instansi;
    $cari->uk = ($uk == 'ALL') ? '': $uk;
    $cari->is_wl = ($is_wl == 'ALL') ? '': $is_wl;

     //// admin unit kerja ////
    if ($this->session->userdata('ID_ROLE') == "4"){ 
        $this->load->model("Mglobal", "mglobal");

        $uk_id = $this->mglobal->get_data_all('T_USER', NULL, ['ID_USER' => $this->session->userdata('ID_USER')], 'UK_ID')[0]->UK_ID;
        $cari->uk = $uk_id;
    }

    ///////////////////////////////////////////////////////
    $this->daftar_wl                       = $this->Mmpnwn->cetak_page_PL_AKTIF($instansi, NULL, $cari, NULL, 1, NULL);


        // --- start excel content, the magic goes here --- //
        
    $this->cetak_wl($this->daftar_wl, $spreadsheet);
    
    // --- end excel content --- //

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    //setup file meta
    $filename = 'Daftar Wajib Lapor '.date('dmyGi').'.xlsx';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // ------ save and export to folder download

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    return "Excel is generated";
  }

  public function export_pn_wl_online($text, $tahun, $instansi, $uk, $is_wl)
  {

    $spreadsheet = new Spreadsheet();

    $styleArray = array(
    'font'  => array(
        'size'  => 10,
        'name'  => 'Calibri'
    ));
    $spreadsheet->getDefaultStyle()
      ->applyFromArray($styleArray);

  
    $cari = new StdClass();
    $cari->text = ($text == 'ALL') ? '': $text;
    $cari->tahun = ($tahun == 'ALL') ? '': $tahun;
    $cari->instansi = ($instansi == 'ALL') ? '': $instansi;
    $cari->uk = ($uk == 'ALL') ? '': $uk;
    $cari->is_wl = ($is_wl == 'ALL') ? '': $is_wl;

    ///////////////////////////////////////////////////////
    
    $this->daftar_pn_wl_online                = $this->Mmpnwn->cetak_page_PN_wl_online($instansi, NULL, $cari, NULL, NULL);
    
   
        // --- start excel content, the magic goes here --- //
        
    $this->cetak_pn_wl_online($daftar_pn_wl_online, $spreadsheet);

    // --- end excel content --- //

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    //setup file meta
    $filename = 'Daftar PN WL Online '.date('dmyGi').'.xlsx';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // ------ save and export to folder download

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    echo "Excel is generated";
  }

  public function export_ai($instansi, $status, $text)
  {
    
    $spreadsheet = new Spreadsheet();

    $styleArray = array(
    'font'  => array(
        'size'  => 10,
        'name'  => 'Calibri'
    ));
    $spreadsheet->getDefaultStyle()
      ->applyFromArray($styleArray);
    $cari = new StdClass();
    $cari->text = $text;
    $cari->status = $status;
    $cari->instansi = $instansi;
    
    if($instansi == 'ALL'){$cari->instansi = '';}
    if($text == 'ALL'){$cari->text = '';}
    if($status == 'ALL'){$cari->status = '';}
    elseif($status == '1'){$cari->status = '1,0';}
    $instansi = $cari->instansi;
    
    $this->daftar_ai                          = $this->Muser->admininstansi_ctk($instansi, NULL, $cari, NULL, NULL);
        // --- start excel content, the magic goes here --- //
    $this->cetak_ai($spreadsheet);

    // --- end excel content --- //

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    //setup file meta
    $filename = 'Daftar Admin Instansi '.date('dmyGi').'.xlsx';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // ------ save and export to folder download

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    return "Excel is generated";
  }

  public function export_auk($instansi, $unit_kerja, $status, $text)
  {
    
    $spreadsheet = new Spreadsheet();

    $styleArray = array(
    'font'  => array(
        'size'  => 10,
        'name'  => 'Calibri'
    ));
    $spreadsheet->getDefaultStyle()
      ->applyFromArray($styleArray);
    $cari = new StdClass();
    $cari->text = $text;
    $cari->status = $status;
    $cari->instansi = $instansi;
    $cari->unit_kerja = $unit_kerja;
    
    if($instansi == 'ALL'){$cari->instansi = '';}
    if($unit_kerja == 'ALL'){$cari->unit_kerja = '';}
    if($text == 'ALL'){$cari->text = '';}
    if($status == 'ALL'){$cari->status = '';}
    elseif($status == '1'){$cari->status = '1,0';}
    $instansi = $cari->instansi;
    
    $this->daftar_auk                          = $this->Muser->adminauk_ctk($instansi, NULL, $cari, NULL, NULL);
            // --- start excel content, the magic goes here --- //
    $this->cetak_auk($spreadsheet);

    // --- end excel content --- //

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    //setup file meta
    $filename = 'Daftar Admin Unit Kerja '.date('dmyGi').'.xlsx';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // ------ save and export to folder download

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    echo "Excel is generated";
  }

  public function cetak_add($tipe, $spreadsheet)
  {
    $spreadsheet->setActiveSheetIndex(0);
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Daftar Individual');
    if($tipe == '0'){
      $data = $this->daftar_pn->result;
    }elseif($tipe == '4'){
      $data = $this->daftar_wl->result;
    }
    elseif($tipe == '5'){
      $data = $this->daftar_nwl->result;
    }
    else{
      $data = $this->daftar_perubahan->result;
    }
    
    if(count($data) > 0){
      
      $startRow = 2;
      $no = 1;
      foreach($data as $d){
        $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, "'".$d->NIK);
        $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, $d->NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $d->NAMA_JABATAN);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, $d->INST_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, $d->UK_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $d->SUK_NAMA);
        $startRow++;
        $no++;
      }

    }

    $cari = new StdClass();
    $cari->unit_kerja = $unit_kerja;
    $cari->text = $text;
    $cari->jenis = $jenis;
    $cari->instansi = $instansi;
    if($instansi == 'ALL'){$cari->instansi = '';}
    if($unit_kerja == 'ALL'){$cari->unit_kerja = '';}
    if($text == 'ALL'){$cari->text = '';}


      $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
      $spreadsheet->getActiveSheet()->setCellValue('B1', 'NIK');
      $spreadsheet->getActiveSheet()->setCellValue('C1', 'NAMA');
      $spreadsheet->getActiveSheet()->setCellValue('D1', 'JABATAN');
      $spreadsheet->getActiveSheet()->setCellValue('E1', 'LEMBAGA');
      $spreadsheet->getActiveSheet()->setCellValue('F1', 'UNIT KERJA');
      $spreadsheet->getActiveSheet()->setCellValue('G1', 'SUB UNIT KERJA');

    $this->style_table_header('A1:G1', '00008080', $spreadsheet);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(54);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(21);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(50);
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
  }

  public function cetak_wl($daftar_wl, $spreadsheet)
  {
    $spreadsheet->setActiveSheetIndex(0);
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Daftar Wajib Lapor');

    $data = $this->daftar_wl->result;
         
    if(count($data) > 0){

      $startRow = 2;
      $no = 1;
      foreach($data as $d){

        $status_lhkpn = $d->STATUS;
        $statLaporan = "Belum Lapor";
        switch($status_lhkpn){
            case '0' : $statLaporan = "Draft"; break;
            case '1' : $statLaporan = "Proses Verifikasi"; break;
            case '2' : $statLaporan = "Perlu Perbaikan"; break;
            case '3' : $statLaporan = "Terverifikasi Lengkap"; break;
            case '4' : $statLaporan = "Diumumkan Lengkap"; break;
            case '5' : $statLaporan = "Terverifikasi Tidak Lengkap"; break;
            case '6' : $statLaporan = "Diumumkan Tidak Lengkap"; break;
            case '7' : $statLaporan = "Dikembalikan"; break;
        }

        $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, "'".$d->NIK);
        $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, $d->NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $d->TEMPAT_LAHIR);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, tgl_format($d->TGL_LAHIR));
        $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, $d->NAMA_JABATAN);
        $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $d->SUK_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('H'.$startRow, $d->UK_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('I'.$startRow, $d->INST_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('J'.$startRow, $d->IS_WL);
        $spreadsheet->getActiveSheet()->setCellValue('K'.$startRow, $d->TAHUN_WL);
        $spreadsheet->getActiveSheet()->setCellValue('L'.$startRow, ($status_lhkpn=='0')?'Belum':$d->STATUS_KIRIM_FINAL);
        $spreadsheet->getActiveSheet()->setCellValue('M'.$startRow, $statLaporan);
        $startRow++;
        $no++;
      }

    }

      $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
      $spreadsheet->getActiveSheet()->setCellValue('B1', 'NIK');
      $spreadsheet->getActiveSheet()->setCellValue('C1', 'NAMA');
      $spreadsheet->getActiveSheet()->setCellValue('D1', 'TEMPAT LAHIR');
      $spreadsheet->getActiveSheet()->setCellValue('E1', 'TANGGAL LAHIR');
      $spreadsheet->getActiveSheet()->setCellValue('F1', 'JABATAN');
      $spreadsheet->getActiveSheet()->setCellValue('G1', 'SUB UNIT KERJA');
      $spreadsheet->getActiveSheet()->setCellValue('H1', 'UNIT KERJA');
      $spreadsheet->getActiveSheet()->setCellValue('I1', 'INSTANSI');
      $spreadsheet->getActiveSheet()->setCellValue('J1', 'STATUS ON/OFF');
      $spreadsheet->getActiveSheet()->setCellValue('K1', 'WL TAHUN');
      $spreadsheet->getActiveSheet()->setCellValue('L1', 'Status Lapor');
      $spreadsheet->getActiveSheet()->setCellValue('M1', 'Status LHKPN');

    $this->style_table_header('A1:M1', '00008080', $spreadsheet);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(54);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(54);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(54);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(54);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(7);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(25);
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
  }

  public function cetak_pn_wl_online($daftar_wl, $spreadsheet)
  {
    $spreadsheet->setActiveSheetIndex(0);
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Daftar PN WL Online');

    
    $data = $this->daftar_pn_wl_online->result;

    if(count($data) > 0){
      $startRow = 2;
      $no = 1;
      foreach($data as $d){
        if ($d->IS_FORMULIR_EFILLING == 0) {
          $Status_Efilling = 'Formulir Aktifasi Efilling Belum Diterima';
      }
      else if ($d->IS_FORMULIR_EFILLING == 1) {
          $Status_Efilling = 'Formulir Aktifasi Efilling Sudah Diterima';
      }
        $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, "'".$d->NIK);
        $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, $d->NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $d->NAMA_JABATAN);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, $d->SUK_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, $d->UK_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $Status_Efilling);
        $spreadsheet->getActiveSheet()->setCellValue('H'.$startRow, $d->TAHUN_WL);
        $startRow++;
        $no++;
      }

    }

  


      $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
      $spreadsheet->getActiveSheet()->setCellValue('B1', 'NIK');
      $spreadsheet->getActiveSheet()->setCellValue('C1', 'NAMA');
      $spreadsheet->getActiveSheet()->setCellValue('D1', 'JABATAN');
      $spreadsheet->getActiveSheet()->setCellValue('E1', 'INSTANSI');
      $spreadsheet->getActiveSheet()->setCellValue('F1', 'UNIT KERJA');
      $spreadsheet->getActiveSheet()->setCellValue('G1', 'STATUS E-FILLING');
      $spreadsheet->getActiveSheet()->setCellValue('H1', 'WL TAHUN');

    $this->style_table_header('A1:H1', '00008080', $spreadsheet);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(33);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(7);
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
  }

  public function cetak_ai($spreadsheet)
  {
    $spreadsheet->setActiveSheetIndex(0);
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Daftar Admin Instansi');

    $data = $this->daftar_ai->result_object;
    if(count($data) > 0){
      
      $startRow = 2;
      $no = 1;
      foreach($data as $d){
        $ll = ($d->LAST_LOGIN != '') ? date('d-m-Y h:i:s', $d->LAST_LOGIN) : "";
        $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, $d->USERNAME);
        $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, $d->NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $ll);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, $d->EMAIL);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, $d->INST_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $d->UNIT_KERJA_ADMIN);
        $spreadsheet->getActiveSheet()->setCellValue('H'.$startRow, $d->SUB_UNIT_KERJA_ADMIN);
        $spreadsheet->getActiveSheet()->setCellValue('I'.$startRow, $d->JABATAN_ADMIN);
        $spreadsheet->getActiveSheet()->setCellValue('J'.$startRow, $d->HANDPHONE);
        $startRow++;
        $no++;
      }

    }


      $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
      $spreadsheet->getActiveSheet()->setCellValue('B1', 'USERNAME');
      $spreadsheet->getActiveSheet()->setCellValue('C1', 'NAMA');
      $spreadsheet->getActiveSheet()->setCellValue('D1', 'LAST LOGIN');
      $spreadsheet->getActiveSheet()->setCellValue('E1', 'EMAIL');
      $spreadsheet->getActiveSheet()->setCellValue('F1', 'INSTANSI');
      $spreadsheet->getActiveSheet()->setCellValue('G1', 'UNIT KERJA ADMIN');
      $spreadsheet->getActiveSheet()->setCellValue('H1', 'SUB UNIT KERJA ADMIN');
      $spreadsheet->getActiveSheet()->setCellValue('I1', 'JABATAN ADMIN');
      $spreadsheet->getActiveSheet()->setCellValue('J1', 'No HP');

    $this->style_table_header('A1:J1', '00008080', $spreadsheet);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(31);
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
  }

  public function cetak_auk($spreadsheet)
  {
    $spreadsheet->setActiveSheetIndex(0);
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Daftar Admin Unit Kerja');

    $data = $this->daftar_auk->result_object;
    if(count($data) > 0){
      
      $startRow = 2;
      $no = 1;
      foreach($data as $d){
        $ll = ($d->LAST_LOGIN != '') ? date('d-m-Y h:i:s', $d->LAST_LOGIN) : "";
        $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, $d->USERNAME);
        $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, $d->NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $ll);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, $d->EMAIL);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, $d->HANDPHONE);
        $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $d->INST_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('H'.$startRow, $d->UK_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('I'.$startRow, $d->UNIT_KERJA_ADMIN);
        $spreadsheet->getActiveSheet()->setCellValue('J'.$startRow, $d->SUB_UNIT_KERJA_ADMIN);
        $spreadsheet->getActiveSheet()->setCellValue('K'.$startRow, $d->JABATAN_ADMIN);
        $startRow++;
        $no++;
      }

    }


      $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
      $spreadsheet->getActiveSheet()->setCellValue('B1', 'USERNAME');
      $spreadsheet->getActiveSheet()->setCellValue('C1', 'NAMA');
      $spreadsheet->getActiveSheet()->setCellValue('D1', 'LAST LOGIN');
      $spreadsheet->getActiveSheet()->setCellValue('E1', 'EMAIL');
      $spreadsheet->getActiveSheet()->setCellValue('F1', 'HANDPHONE');
      $spreadsheet->getActiveSheet()->setCellValue('G1', 'INSTANSI');
      $spreadsheet->getActiveSheet()->setCellValue('H1', 'UNIT KERJA');
      $spreadsheet->getActiveSheet()->setCellValue('I1', 'UNIT KERJA ADMIN');
      $spreadsheet->getActiveSheet()->setCellValue('J1', 'SUB UNIT KERJA ADMIN');
      $spreadsheet->getActiveSheet()->setCellValue('K1', 'JABATAN ADMIN');

    $this->style_table_header('A1:K1', '00008080', $spreadsheet);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(17);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(31);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(31);
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
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

  public function export_analisa_kas()
  { 
    
    $spreadsheet = new Spreadsheet();

    $styleArray = array(
    'font'  => array(
        'size'  => 10,
        'name'  => 'Calibri'
    ));
    $spreadsheet->getDefaultStyle()
      ->applyFromArray($styleArray);
    
    $this->daftar_kas                          = $this->Mlhkpn->cetak_analisa_kas();
        // --- start excel content, the magic goes here --- //
    $this->cetak_ak($spreadsheet);

    // --- end excel content --- //

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    //setup file meta
    $filename = 'Daftar Analisa Kas '.date('dmyGi').'.xlsx';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // ------ save and export to folder download

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    echo "Excel is generated";
  }

  public function cetak_ak($spreadsheet)
  {
    $spreadsheet->setActiveSheetIndex(0);
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Daftar Analisa Harta Kas');

    $spreadsheet->getActiveSheet()->getStyle('G1:G1048576')->getNumberFormat()->setFormatCode('#');

    $data = $this->daftar_kas->result_object;

    if(count($data) > 0){
      
      $startRow = 2;
      $no = 1;
      foreach($data as $d){
        $ll = ($d->LAST_LOGIN != '') ? date('d-m-Y h:i:s', $d->LAST_LOGIN) : "";
        $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, $d->Jenis_LHKPN);
        $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, $d->Nama_Jenis);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $d->ASAL_USUL);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, $d->ATAS_NAMA_REKENING);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, $d->nama_bank_dekrip);
        $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $d->no_rekening_dekrip);
        $spreadsheet->getActiveSheet()->setCellValue('H'.$startRow, $d->KETERANGAN);
        $spreadsheet->getActiveSheet()->setCellValue('I'.$startRow, $d->TAHUN_BUKA_REKENING);
        $spreadsheet->getActiveSheet()->setCellValue('J'.$startRow, $d->Status_Harta);
        $spreadsheet->getActiveSheet()->setCellValue('K'.$startRow, $d->NAMA_MATA_UANG);
        $spreadsheet->getActiveSheet()->setCellValue('L'.$startRow, $d->NILAI_SALDO);
        $spreadsheet->getActiveSheet()->setCellValue('M'.$startRow, $d->NILAI_KURS);
        $spreadsheet->getActiveSheet()->setCellValue('N'.$startRow, $d->NILAI_EQUIVALEN);
        $startRow++;
        $no++;
      }

    }

      $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
      $spreadsheet->getActiveSheet()->setCellValue('B1', 'Jenis LHKPN');
      $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nama Jenis');
      $spreadsheet->getActiveSheet()->setCellValue('D1', 'Asal Usul');
      $spreadsheet->getActiveSheet()->setCellValue('E1', 'Atas Nama Rekening');
      $spreadsheet->getActiveSheet()->setCellValue('F1', 'Nama Bank');
      $spreadsheet->getActiveSheet()->setCellValue('G1', 'Nomor Rekening');
      $spreadsheet->getActiveSheet()->setCellValue('H1', 'Keterangan');
      $spreadsheet->getActiveSheet()->setCellValue('I1', 'Tahun Buka Rekening');
      $spreadsheet->getActiveSheet()->setCellValue('J1', 'Status Harta');
      $spreadsheet->getActiveSheet()->setCellValue('K1', 'Mata Uang');
      $spreadsheet->getActiveSheet()->setCellValue('L1', 'Nilai Saldo');
      $spreadsheet->getActiveSheet()->setCellValue('M1', 'Nilai Kurs');
      $spreadsheet->getActiveSheet()->setCellValue('N1', 'Nilai Ekuivalen');

      $this->style_table_header('A1:N1', '00008080', $spreadsheet);
      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(25);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(13);
      $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(10);
      $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
      $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
  }

  
}
