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
    $this->load->model('Mjabatan');
  }


  public function export($text, $instansi, $uk, $subuker, $status)
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
    $cari->instansi = ($instansi == 'ALL') ? '': $instansi;
    $cari->uk = ($uk == 'ALL') ? '': $uk;
    $cari->subuker = ($subuker == 'ALL') ? '': $subuker;
    $cari->status = ($status == 'ALL') ? '': $status; 
    $data_jabatan = $this->Mjabatan->get_daftar_master_jabatan_cetak($cari);
    // dd($data_jabatan->result);
    $this->cetak_jabatan($data_jabatan, $spreadsheet);
    

    // --- end excel content --- //

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    //setup file meta
    $filename = 'Jabatan'.date('dmyGi').'.xlsx';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // ------ save and export to folder download

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    echo "Excel is generated";
  }


  public function cetak_jabatan($data_jabatan, $spreadsheet)
  {
    $spreadsheet->setActiveSheetIndex(0);
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Sheet1');
    $data = $data_jabatan->result;
    if(count($data) > 0){
      $startRow = 2;
      $no = 1;
      foreach($data as $item){
        $bidang = $item->BDG_NAMA;
        
        $inst = $item->INST_NAMA;
        $uk = $item->UK_NAMA;
        $suk = $item->SUK_NAMA;
        $jab = $item->NAMA_JABATAN;
        $eselon = strtoupper($item->ESELON);
        
        if($item->IS_UU == 0){ $uu = 'NON UU';}else{$uu = 'UU';}
        if($item->INST_LEVEL == 1){ $tingkat = 'PUSAT';}else{ $tingkat = 'DAERAH';}
      
        $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, $bidang);
        $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, $tingkat);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $inst);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, $uk);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, $suk);
        $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $jab);
        $spreadsheet->getActiveSheet()->setCellValue('H'.$startRow, $eselon);
        $spreadsheet->getActiveSheet()->setCellValue('I'.$startRow, $uu);
             
        $startRow++;
        $no++;
      }

    }

      $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
      $spreadsheet->getActiveSheet()->setCellValue('B1', 'BIDANG');
      $spreadsheet->getActiveSheet()->setCellValue('C1', 'TINGKAT');
      $spreadsheet->getActiveSheet()->setCellValue('D1', 'NAMA INSTANSI');
      $spreadsheet->getActiveSheet()->setCellValue('E1', 'NAMA UNIT KERJA');
      $spreadsheet->getActiveSheet()->setCellValue('F1', 'NAMA SUB UNIT KERJA');
      $spreadsheet->getActiveSheet()->setCellValue('G1', 'NAMA JABATAN');
      $spreadsheet->getActiveSheet()->setCellValue('H1', 'ESELONISASI');
      $spreadsheet->getActiveSheet()->setCellValue('I1', 'UU/NON UU');
  

      $this->style_table_header('A1:I1', '00008080', $spreadsheet);

      
      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(45);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(50);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(60);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);

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
