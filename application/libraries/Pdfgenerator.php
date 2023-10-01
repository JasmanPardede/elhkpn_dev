<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once("./vendor/dompdf/dompdf/autoload.inc.php");
use Dompdf\Dompdf;

class Pdfgenerator {

  public function generate($html, $filename='', $method, $paper = 'A4', $orientation = "portrait",$path_filename = "uploads/pdf_default/")
  {
    $dompdf = new DOMPDF();
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);
    $dompdf->render();
    if ($method=="stream") {
        $dompdf->stream($filename.".pdf", array("Attachment" => 0));
    }else if($method=="store"){
       $output = $dompdf->output();
       file_put_contents($path_filename.$filename.'.pdf', $output);
       return 1;
    } else {
        return $dompdf->output();
    }
  }
}
