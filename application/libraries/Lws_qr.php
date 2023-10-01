<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "phpqrcode" . DIRECTORY_SEPARATOR . "qrlib.php";
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lws_qr
 *
 * @author nurfadillah
 */
class Lws_qr {

    CONST ENCRYPTED = 1;

    private $keycode = 'kpkqrcode0123456';
    private $acode = 'H*';
    private $bcode = 'basQRov';
    private $data_url = 'https://ceksurat.kpk.go.id/get_datasurat/';
    
    private $qr_level = 'L';
    private $qr_size = 3;

    private $encrypted = NULL;

    public $temp_dir;
    public $model_qr = FALSE;
    public $model_qr_prefix_nomor = FALSE;
    public $callable_model_function = FALSE;

    private function __set_temp_dir($_temp_dir = FALSE) {
        if (!$_temp_dir) {
            $this->temp_dir = APPROOT . '_assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'qr_temp' . DIRECTORY_SEPARATOR;
        } else {
            $this->temp_dir = $_temp_dir;
        }
    }

    public function __construct($_arr_parameters = FALSE) {


        if (!$_arr_parameters) {
            $this->temp_dir = APPROOT . '_assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'qr_temp' . DIRECTORY_SEPARATOR;
        } else {
            if (is_array($_arr_parameters)) {
                extract($_arr_parameters);
            }

            if (isset($temp_dir)) {
                $this->__set_temp_dir($temp_dir);
            }

            if(isset($model_qr) && $model_qr){
                $this->model_qr = $model_qr;

                if(isset($model_qr_prefix_nomor)){
                    $this->model_qr_prefix_nomor = $model_qr_prefix_nomor;
                }

                if($this->model_qr && isset($callable_model_function) && $callable_model_function){
                    $this->callable_model_function = $callable_model_function;
                }
            }

            if(isset($keycode)){
                $this->keycode = $keycode;
            }
            if(isset($acode)){
                $this->acode = $acode;
            }
            if(isset($bcode)){
                $this->bcode = $bcode;
            }
            if(isset($data_url)){
                $this->data_url = $data_url;
            }
            if(isset($level)){
                $this->qr_level = $level;
            }
            if(isset($size)){
                $this->qr_size = $size;
            }
        }
    }

    private function __encrypt_data($key, $iv, $content_data){
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $content_data, MCRYPT_MODE_CBC, $iv));
        return preg_replace('/[^A-Za-z0-9\-]/', '', $encrypted);
    }

    private function __encode_data($content_data = ""){
        $iv = $this->keycode;
        $key = pack($this->acode, md5($this->bcode));



        $encrypted = $this->__encrypt_data($key, $iv, $content_data);

        return $encrypted;
//        $data_qrcode['no_qrcode'] = $encrypted;
//
////        $insert_cqrcode = $this->Cqrcode->insert_cqrcode($data_qrcode);
//        $params['data'] = $this->data_url . $encrypted;
//        $params['level'] = 'L';
//        $params['size'] = 3;
    }

    /**
     *
     * @param type $code_content
     * @param type $filename
     * @param type $outer_frame
     * @param type $pixel_per_point
     * @param type $jpeg_quality
     * @return type string FILE NAME
     */
    public function create($code_content = "", $filename = "", $outer_frame = 4, $pixel_per_point = 5, $jpeg_quality = 95, $return_full_path = TRUE) {
        //generate frame

        $qr_content = $code_content;
        if(self::ENCRYPTED){
            $obj_code_content = json_decode($code_content);
            // if(!$obj_code_content || ($obj_code_content && !property_exists($obj_code_content, "encrypt_data"))){
            //     log_message("error", "Lws_qr.php ENCRYPT TRUE but \$code_content is not json. or the code is json but we cannot find encrypt_data property.");
            //     return FALSE;
            // }

            if($obj_code_content || ($obj_code_content && property_exists($obj_code_content, "encrypt_data"))){
                $no_qrcode = $this->__encode_data($obj_code_content->encrypt_data);
                $qr_content = $this->data_url . $no_qrcode;

                $obj_code_content->no_qrcode = $no_qrcode;
                $code_content = json_encode($obj_code_content);
            }

        }

        $frame = QRcode::text($qr_content, FALSE, QR_ECLEVEL_M);

        // rendering frame with GD2 (that should be function by real impl.!!!)
        $h = count($frame);
        $w = strlen($frame[0]);

        $imgW = $w + 2 * $outer_frame;
        $imgH = $h + 2 * $outer_frame;

        $base_image = imagecreate($imgW, $imgH);

        $col[0] = imagecolorallocate($base_image, 255, 255, 255); // BG, white
        $col[1] = imagecolorallocate($base_image, 0, 0, 0);     // FG, blue

        imagefill($base_image, 0, 0, $col[0]);

        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                if ($frame[$y][$x] == '1') {
                    imagesetpixel($base_image, $x + $outer_frame, $y + $outer_frame, $col[1]);
                }
            }
        }

        // saving to file
        $target_image = imagecreate($imgW * $pixel_per_point, $imgH * $pixel_per_point);

        imagecopyresized(
                $target_image, $base_image, 0, 0, 0, 0, $imgW * $pixel_per_point, $imgH * $pixel_per_point, $imgW, $imgH
        );

        imagedestroy($base_image);

        if ($filename !== "") {
            imagejpeg($target_image, $this->temp_dir . $filename, $jpeg_quality);
        } else {
            header('Content-Type: image/jpeg');
            imagejpeg($target_image);
            return FALSE;
        }

        imagedestroy($target_image);

        $response = $filename;

        if ($return_full_path) {
            $response = $this->temp_dir . $filename;
        }

        $this->__call_model($code_content, $response);

        return $response;
    }

    /**
     *
     * @param string $data json
     * @param string $filename
     * @return void
     */
    private function __call_model($data = "", $filename = "") {
        if ($this->model_qr && $this->callable_model_function) {
            $ci = &get_instance();

            $ci->load->model($this->model_qr);

            $ci->{$this->model_qr}->prefix_nomor = $this->model_qr_prefix_nomor;
            $ci->{$this->model_qr}->{$this->callable_model_function}($data, $filename);
        }
        return;
    }

}
