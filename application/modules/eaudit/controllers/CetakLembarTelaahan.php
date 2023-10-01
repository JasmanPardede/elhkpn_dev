<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// call php-spreadsheet library
require APPPATH.'third_party/phpword/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;

class CetakLembarTelaahan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    echo "--- eof ---";
  }

  function index()
  {

  }

  function download_lt()
  {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $template = $phpWord->loadTemplate('./file/template/penelaahan/LembarTelaahan-Template.doc');

    $template->setValue('NOLT', '1234567890');

    $temp_filename = 'Template_LT.doc';
    $template->saveAs($temp_filename);

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$temp_filename);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($temp_filename));
    flush();
    readfile($temp_filename);
    unlink($temp_filename);
    exit;
  }

}
