<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once dirname(__FILE__) . "/../../../core/Core_Ikhtisar.php";

class Ikthisar extends Core_Ikhtisar {
    
    /**
     * Kontroller ini bukan kontroller Everifikasi, melainkan kontroller Efill
     * @var bool 
     */
    protected $is_ever = FALSE;

    function __Construct() {
        parent::__Construct();
    }

}
