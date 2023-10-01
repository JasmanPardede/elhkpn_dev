<?php

/*
___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller Penyelenggara Negara
 *
 * @author Rizky Awlia Fajrin (Evan Sumangkut) - PT.Waditra Reka Cipta
 * @package Controllers
 */
?>
<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();

    }

    public function index()
    {
        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'Peran Serta Masyarakat' => 'index.php/psm/dashboard',
                'Dashboard Pengaduan' => 'index.php/psm/dashboard',
            )),
        );
        $this->load->view(strtolower(__CLASS__) . '/dashboard_index', $data);
    }


}
