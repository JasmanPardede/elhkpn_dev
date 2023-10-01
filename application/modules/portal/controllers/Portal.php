<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portal extends CI_Controller{

	function __Construct(){
		parent::__Construct();
		//$this->load->helper('captcha');
	}

	function index(){
        if ($this->session->userdata('logged_in') != true || $this->session->userdata('productkey') != productkey) {
            redirect('portal/user/login');
        }
        else{
            redirect('portal/home');
        }
		
	}

	
}