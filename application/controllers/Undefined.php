<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Undefined extends MY_Controller{
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
//        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//        echo $actual_link;exit;
        redirect('/');
    }
}