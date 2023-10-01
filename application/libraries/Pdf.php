<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Pdf {
    
    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($param = NULL)
    {
        include_once APPPATH.'third_party/mpdf/mpdf.php';
         
        if ($param == NULL)
        {
            $param = '"en-GB-x","A4-L","","",10,10,10,10,6,3';
        }

        return new mPDF($param);
    }

    function loadP($p1 = 'en-GB-x', $p2 = 'A4')
    {
        include_once APPPATH.'third_party/mpdf6.0/mpdf.php';

        return new mPDF($p1, $p2);
    }
}