<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// include_once('soaplib/nusoap.php');
include_once './vendor/econea/nusoap/src/nusoap.php';
function evan(){
  return 'evanss';
}

function testwsdl() {
    $wsdl = "http://pasumpahan/elowebservice/WebService1.asmx";
    $elo = new nusoap_client($wsdl, true);
    $err = $elo->getError();

    if ($err) {
        return "ada masalah";
    } else {
        return "koneksi ke webservice berhasil q(*(oo)*)p";
    }
}

function FindELODoc($Keywording, $DocumentName, $DocumentDate, $FilingDate, $OwnerName, $KeywordingFields, $LoginAs, $ELOuser, $ELOpass, $IXURL, $ELOws) {

    $wsdl = $ELOws;
    $elo = new nusoap_client($wsdl, 'wsdl');
    $elo->soap_defencoding = 'UTF-8';
    $elo->decode_utf8 = FALSE;

    $err = $elo->getError();
    $ELOdocs = [];

    if ($err) {
        return "koneksi soap gagal : " . $err;
    } else {

        $params = array(
            'Keywording' => $Keywording,
            'DocumentName' => $DocumentName,
            'DocumentDate' => $DocumentDate,
            'FilingDate' => $FilingDate,
            'OwnerName' => $OwnerName,
            'KeywordingFields' => $KeywordingFields,
            'LoginAs' => $LoginAs,
            'user' => $ELOuser,
            'pass' => $ELOpass,
            'IXURL' => $IXURL
        );

        $result = $elo->call('GetDocuments', $params);
        if ($result != null) {//(array_key_exists('ID', $result['GetDocumentsResult']))//

           if(isset($result['GetDocumentsResult']['DataTable']['diffgram']['ELOds'])) {
               if (array_key_exists('ID', $result['GetDocumentsResult']['DataTable']['diffgram']['ELOds']['DOCUMENT']))
               {
                   //$docitem = array('ID' => $result['GetDocumentsResult']['diffgram']['ELOds']['DOCUMENT']['ID'],
                   $docitem = array('ID' => $result['GetDocumentsResult']['DataTable']['diffgram']['ELOds']['DOCUMENT']['ID'],
                       //'NAME' => $result['GetDocumentsResult']['diffgram']['ELOds']['DOCUMENT']['Name']
                       'NAME' => $result['GetDocumentsResult']['DataTable']['diffgram']['ELOds']['DOCUMENT']['Name']
                   );
    
                   $ELOdocs[] = $docitem;
               } else {
                   //foreach ($result['GetDocumentsResult']['diffgram']['ELOds']['DOCUMENT'] as $document) {
                   foreach ($result['GetDocumentsResult']['DataTable']['diffgram']['ELOds']['DOCUMENT'] as $document) {
                       $docitem = array('ID' => $document['ID'],
                           'NAME' => $document['Name']
                       );
                       $ELOdocs[] = $docitem;
                   }
               }
           }

        }
        return $ELOdocs;
    }
}

function GetDocURL($DocID, $LoginAs, $ELOuser, $ELOpass, $IXURL, $ELOws) {


    //include('soaplib/nusoap.php');

    $wsdl = $ELOws;
    $elo = new nusoap_client($wsdl, true);

    $err = $elo->getError();

    if ($err) {
        return "koneksi soap gagal : " . $err;
    } else {
        $result = $elo->call('GetDocUrl', array(
            'DocID' => $DocID,
            'LoginAs' => $LoginAs,
            'user' => $ELOuser,
            'pass' => $ELOpass,
            'IXURL' => $IXURL
        ));


        return $result["GetDocUrlResult"];
    }
}

?>
