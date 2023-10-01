<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once dirname(__FILE__) . "/../../../core/Core_Ikhtisar.php";

class Ikthisar extends Core_Ikhtisar {
    /**
     * Kontroller ini adalah kontroller Everifikasi
     * @var bool 
     */
    protected $is_ever = TRUE;

    function __Construct() {
        parent::__Construct();
    }

}
