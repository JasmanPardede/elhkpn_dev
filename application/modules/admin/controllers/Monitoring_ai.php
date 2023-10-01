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
 * @author Rizki Nanda Mustaqim - PT. Akhdani Reka Solusi
 * @package Controllers
 */
?>
<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


Class Monitoring_ai extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->load->model('Mglobal', '', TRUE);
        $this->load->model('Mapi_ai_log', '', TRUE);
    }

    public function index(){
        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard'
            )),
        );    
        $this->load->view('monitoring_ai_index', $data);
    }

    public function load_ajax_monitoring_ai(){
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        $response = $this->Mapi_ai_log->list_all($currentpage, $keyword, $rowperpage, true);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

}