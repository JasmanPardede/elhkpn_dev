<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Announcement extends CI_Controller{
	
	function __Construct(){
		parent::__Construct();
		call_user_func('ng::islogin');
		
	}
	
	function index(){
		
	}
	
}