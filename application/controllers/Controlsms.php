<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Controlsms extends CI_Controller {

    private $dummy_data = array('SEND' => '{"tujuan":"085640763677","isiPesan":"Kode Token Pengiriman LHKPN adalah 234234", "idModem":6}');

    function __Construct() {
        parent::__Construct();
    }

    function connect() {
        echo "test";exit;
    }

    function getUrlContent() {

        $ch = curl_init();
        
        $curl_data = $this->dummy_data;
        $url = 'http://10.102.0.70:3333/sendSMS';
        
        $options = array(
            CURLOPT_PORT => 3333,
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "POST", // Atur type request, get atau post
            CURLOPT_POST => TRUE, // Atur menjadi GET
            CURLOPT_FOLLOWLOCATION => TRUE, // Follow redirect aktif
            CURLOPT_CONNECTTIMEOUT => 120, // Atur koneksi timeout
            CURLOPT_TIMEOUT => 120, // Atur response timeout
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE=>TRUE,
        );
        
        if(!empty($curl_data)){
            $curl_data = http_build_query ($curl_data);
            $options[CURLOPT_POSTFIELDS] = $curl_data;
        }
        
        $this->curl_setopt_array($ch, $options);
        
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response = "no response";
        if(($httpcode >= 200 && $httpcode < 300)){
            $response = $data;
        }
        
//        var_dump($response, $httpcode);exit;
    }
    
    function getTestWeb() {

        $ch = curl_init();
        
//        $curl_data = $this->dummy_data;
        $curl_data = array();
        $url = 'http://www.google.com';
        
        $options = array(
            CURLOPT_URL => $url,
//            CURLOPT_CUSTOMREQUEST => "POST", // Atur type request, get atau post
            CURLOPT_POST => TRUE, // Atur menjadi GET
            CURLOPT_FOLLOWLOCATION => TRUE, // Follow redirect aktif
            CURLOPT_CONNECTTIMEOUT => 120, // Atur koneksi timeout
            CURLOPT_TIMEOUT => 120, // Atur response timeout
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE=>TRUE,
        );
        
        if(!empty($curl_data)){
            $options[CURLOPT_POSTFIELDS] = $curl_data;
        }
        
        $this->curl_setopt_array($ch, $options);
        
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response = "no response";
        if(($httpcode >= 200 && $httpcode < 300)){
            $response = $data;
        }
        
        var_dump($response, $httpcode);exit;
    }

    function curl_setopt_array(&$ch, $curl_options) {
        foreach ($curl_options as $option => $value) {
            if (!curl_setopt($ch, $option, $value)) {
                return false;
            }
        }
        return true;
    }

}
