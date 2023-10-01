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

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    // $this->load->library('Excel');
    $this->load->model('ever/Verification_model');
  }


  public function export($stat, $entry, $aktifasi, $petugas, $lembaga, $uk, $tahun, $tahunKirimFinal, $eselon, $uu, $rangkap, $nama, $belum_ditugaskan, $sudah_ditugaskan, $status_lhkpn_sebelumnya, $status_lhkpn)
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
    $cari->stat = ($stat == 'ALL') ? '': $stat;
    $cari->entry = ($entry == 'ALL') ? '': $entry;
    $cari->aktifasi = ($aktifasi == 'ALL') ? '': $aktifasi;
    $cari->petugas = ($petugas == 'ALL') ? '': $petugas;
    $cari->lembaga = ($lembaga == 'ALL') ? '': $lembaga;
    $cari->uk = ($uk == 'ALL') ? '': $uk;
    $cari->tahun = ($tahun == 'ALL') ? '': $tahun;
    $cari->tahunKirimFinal = ($tahunKirimFinal == 'ALL') ? '': $tahunKirimFinal;
    $cari->eselon = ($eselon == 'ALL') ? '': $eselon;
    $cari->uu = ($uu == 'ALL') ? '': $uu;
    $cari->rangkap = ($rangkap == 'ALL') ? '': $rangkap;
    $cari->nama = ($nama == 'ALL') ? '': $nama;
    $cari->belum_ditugaskan = ($belum_ditugaskan == 'ALL') ? '': $belum_ditugaskan;
    $cari->sudah_ditugaskan = ($sudah_ditugaskan == 'ALL') ? '': $sudah_ditugaskan;
    $cari->status_lhkpn_sebelumnya = ($status_lhkpn_sebelumnya == 'ALL') ? '': $status_lhkpn_sebelumnya;
    $cari->status_lhkpn = ($status_lhkpn == 'ALL') ? '': $status_lhkpn;

    $data_verifikasi = $this->Verification_model->penugasan_verifikasi_cetak($cari);

    $this->cetak_verifikasi($data_verifikasi, $spreadsheet);
    

    // --- end excel content --- //

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    //setup file meta
    $filename = 'Verifikasi_Penugasan'.date('dmyGi').'.xlsx';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // ------ save and export to folder download

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    return "Excel is generated";
  }


  public function cetak_verifikasi($data_verifikasi, $spreadsheet)
  {
    $spreadsheet->setActiveSheetIndex(0);
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Verifikasi Penugasan');
    $data = $this->data_verifikasi->result;
    
    if(count($data_verifikasi) > 0){
      $startRow = 2;
      $no = 1;  
      foreach($data_verifikasi as $item){
        $agenda = date('Y', strtotime($item->tgl_lapor)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
        $tanggal = date('d M Y', strtotime($item->tgl_kirim_final));
        $aStatus = ['0' => 'Draft', '1' => 'Proses Verifikasi', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi Lengkap', '4' => 'Diumumkan Lengkap', '5' => 'Terverifikasi Tidak Lengkap', '6' => 'Diumumkan Tidak Lengkap', '7' => 'Ditolak'];
        if ($item->STAT == '' && ($item->STATUS == 1 || $item->STATUS == 2)) {
          $stat_penugasan = 'Belum Ditugaskan';
        } else {
            if ($item->STAT == 2 || $item->STATUS == 3) {
              $stat_penugasan ='Sudah Ditugaskan';
              $tgl_penugasan = date('d M Y', strtotime($item->TANGGAL_PENUGASAN));
             
              if(isset($item->DATE_INSERT) && !is_null($item->DATE_INSERT)){
                $tgl_verif_perlu_perbaikan = date('d M Y', strtotime($item->DATE_INSERT));
              }else{
                $tgl_verif_perlu_perbaikan = '';
              }

            } else {
              $stat_penugasan = $item->STAT;
            }
        }
        if($item->IS_FORMULIR_EFILLING==0){
          $statusEfill = 'Belum Diterima';
        }elseif($item->IS_FORMULIR_EFILLING==1){
          $statusEfill = 'Sudah Diterima';
        }elseif($item->IS_FORMULIR_EFILLING==null){ 
          $statusEfill = '';
        }

        if($item->STATUS_LHKPN_SEBELUMNYA != null){
          $stat_lhkpn_sebelumnya =  $aStatus[$item->STATUS_LHKPN_SEBELUMNYA];
        }else{
          $stat_lhkpn_sebelumnya = "-";
        }

        if($item->STATUS == 1 && $item->ALASAN != null){
            $status_lhkpn = 'Sudah Diperbaiki';
        }else{
            $status_lhkpn = $aStatus[$item->STATUS];
        }

        $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, $item->NAMA_LENGKAP);
        $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, $agenda);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $statusEfill);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, $item->NAMA_JABATAN);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, $item->RANGKAP);
        $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $item->ESELON);
        $spreadsheet->getActiveSheet()->setCellValue('H'.$startRow, $item->UK_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('I'.$startRow, $item->INST_NAMA);
        $spreadsheet->getActiveSheet()->setCellValue('J'.$startRow, $tanggal);
        $spreadsheet->getActiveSheet()->setCellValue('K'.$startRow, $status_lhkpn);
        $spreadsheet->getActiveSheet()->setCellValue('L'.$startRow, $stat_penugasan);
        if($item->STATUS_LHKPN_SEBELUMNYA != null){
            $spreadsheet->getActiveSheet()->setCellValue('M'.$startRow, $stat_lhkpn_sebelumnya);
            if ($item->STAT == 2 || $item->STATUS == 3) {
                $spreadsheet->getActiveSheet()->setCellValue('N'.$startRow, $item->USERNAME);
                $spreadsheet->getActiveSheet()->setCellValue('O'.$startRow, $tgl_penugasan);
                $spreadsheet->getActiveSheet()->setCellValue('P'.$startRow, $tgl_verif_perlu_perbaikan);
            }
        }else{
            if ($item->STAT == 2 || $item->STATUS == 3) {
                $spreadsheet->getActiveSheet()->setCellValue('M'.$startRow, $item->USERNAME);
                $spreadsheet->getActiveSheet()->setCellValue('N'.$startRow, $tgl_penugasan);
                $spreadsheet->getActiveSheet()->setCellValue('O'.$startRow, $tgl_verif_perlu_perbaikan);
            }
        }

        
        $startRow++;
        $no++;
      }

    }

      $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
      $spreadsheet->getActiveSheet()->setCellValue('B1', 'NAMA');
      $spreadsheet->getActiveSheet()->setCellValue('C1', 'NO. AGENDA');
      $spreadsheet->getActiveSheet()->setCellValue('D1', 'FORMULIR AKTIFASI');
      $spreadsheet->getActiveSheet()->setCellValue('E1', 'JABATAN');
      $spreadsheet->getActiveSheet()->setCellValue('F1', 'RANGKAP JABATAN');
      $spreadsheet->getActiveSheet()->setCellValue('G1', 'ESELON');
      $spreadsheet->getActiveSheet()->setCellValue('H1', 'UNIT KERJA');
      $spreadsheet->getActiveSheet()->setCellValue('I1', 'LEMBAGA');
      $spreadsheet->getActiveSheet()->setCellValue('J1', 'TANGGAL KIRIM FINAL');
      $spreadsheet->getActiveSheet()->setCellValue('K1', 'STATUS LHKPN');
      $spreadsheet->getActiveSheet()->setCellValue('L1', 'STATUS PENUGASAN');

      if($item->STATUS_LHKPN_SEBELUMNYA != null){
          $spreadsheet->getActiveSheet()->setCellValue('M1', 'STATUS LHKPN SEBELUMNYA');
          if ($item->STAT == 2 || $item->STATUS == 3) {
            $spreadsheet->getActiveSheet()->setCellValue('N1', 'VERIFIKATOR');
            $spreadsheet->getActiveSheet()->setCellValue('O1', 'TANGGAL PENUGASAN');
            $spreadsheet->getActiveSheet()->setCellValue('P1', 'TANGGAL VERIF PERLU PERBAIKAN');
            $this->style_table_header('A1:P1', '00008080', $spreadsheet);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(17);
          }
          else{
            $this->style_table_header('A1:M1', '00008080', $spreadsheet);
          }
      }else{
          if ($item->STAT == 2 || $item->STATUS == 3) {
            $spreadsheet->getActiveSheet()->setCellValue('M1', 'VERIFIKATOR');
            $spreadsheet->getActiveSheet()->setCellValue('N1', 'TANGGAL PENUGASAN');
            $spreadsheet->getActiveSheet()->setCellValue('O1', 'TANGGAL VERIF PERLU PERBAIKAN');
            $this->style_table_header('A1:O1', '00008080', $spreadsheet);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(17);
          }
          else{
            $this->style_table_header('A1:M1', '00008080', $spreadsheet);
          }
      }
      
      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(54);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(50);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(31);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(17);
      $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(17);
      if($item->STATUS_LHKPN_SEBELUMNYA != null){
          $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(22);
      }
      
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

  

  

 
}
