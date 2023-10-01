<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * Controller User
 *
 * @author Arif Kurniawan - PT.Mitreka Solusi Indonesia
 * @package Controllers
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exum extends CI_Controller {
    public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->load->model('mglobal');
    }

    public function index($offset = 0)
    {
        // load and packing data
        $this->base_url	   = site_url('/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
        $this->uri_segment = 4;
        $this->offset	   = $this->uri->segment($this->uri_segment);

        $data = array(
            'komposisi'     => $this->_chart(1),
            'kepatuhan'     => $this->_chart(2),
            'breadcrumb'	=> call_user_func('ng::genBreadcrumb',
                array(
                    'Dashboard'	 => 'index.php/welcome/dashboard',
                    'E Reporting'     => 'index.php/dashboard/ereport',
                    'Executive Summary'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                ))
        );

        // load view
        $this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }

    public function chart($id){
        echo $this->_chart($id);
    }

    private function _chart($id){
        switch($id){
            case '1':
                $rasio = $this->input->post('rasio');
                if($rasio == 2){
                    $order = 'asc';
                }else{
                    $order = 'desc';
                }

                $limitA = $this->input->post('limit');
                if($limitA == ''){
                    $limit = 0;
                    $limitA = 0;
                }else{
                    $limit = $limitA;
                }

                $item = $this->db->query("SELECT A.INST_SATKERKD, A.INST_AKRONIM, ( SELECT count(*) FROM T_USER B INNER JOIN T_USER_ROLE D ON FIND_IN_SET(D.ID_ROLE, B.ID_ROLE) WHERE B.INST_SATKERKD = A.INST_SATKERKD ) AS jml, ( CONCAT( '{ \"Admin\" : ', ( SELECT count(*) FROM T_USER C INNER JOIN T_USER_ROLE D ON FIND_IN_SET(D.ID_ROLE, C.ID_ROLE) WHERE C.INST_SATKERKD = A.INST_SATKERKD AND D.IS_INSTANSI = 1 ), ', \"User\" : ', ( SELECT count(*) FROM T_USER C INNER JOIN T_USER_ROLE D ON FIND_IN_SET(D.ID_ROLE, C.ID_ROLE) WHERE C.INST_SATKERKD = A.INST_SATKERKD AND D.IS_USER_INSTANSI = 1 ), ', \"PN\" : ', ( SELECT count(*) FROM T_USER C INNER JOIN T_USER_ROLE D ON FIND_IN_SET(D.ID_ROLE, C.ID_ROLE) WHERE C.INST_SATKERKD = A.INST_SATKERKD AND D.IS_PN = 1 ), '}' )) AS role FROM M_INST_SATKER A WHERE A.IS_ACTIVE = 1 order by jml ".$order." LIMIT $limit, 10 ")->result_array();

                $count = $this->db->query("SELECT count(*) as jml FROM T_USER A INNER JOIN T_USER_ROLE B ON FIND_IN_SET(B.ID_ROLE, A.ID_ROLE) WHERE A.IS_ACTIVE = 1")->result();
                $count = $count[0]->jml;
                $tmp = array_column($item, 'jml');

                $inst = array_column($item, 'INST_AKRONIM');

                $jml = [];
                foreach($tmp as $row){
                    $jml[] = (int)(($row / $count) * 100);
                }

                $data = array(
                    'instansi'      => $inst,
                    'item'          => $item,
                    'jml'           => $jml,
                    'limit'         => ($limitA+10),
                    'rasio'         => $rasio
                );

                $html = $this->load->view(strtolower(__CLASS__).'_komposisi', $data, TRUE);
                return $html;
                break;

            case '2':
                $tahunstart = $this->input->post('tahun_start');
                if($tahunstart == ''){
                    $tahunstart = (date('Y') - 10);
                }

                $tahunend   = $this->input->post('tahun_end');
                if($tahunend == ''){
                    $tahunend = date('Y');
                }

                $inst = $this->mglobal->get_data_all('M_INST_SATKER', NULL, ['IS_ACTIVE' => '1'], 'INST_SATKERKD as id, INST_NAMA as name');
                $item = $this->db->query("SELECT DISTINCT YEAR(TGL_LAPOR) as tahun, count(*) as jml FROM T_LHKPN where YEAR(TGL_LAPOR) BETWEEN '$tahunstart' AND '$tahunend' AND STATUS  != '0' group by YEAR(TGL_LAPOR)")->result_array();

                $pn =[];
                $lhkpn =[];
                $tahun = [];
                for($i = $tahunstart; $i <= $tahunend; $i++){
                    $tmp = $this->db->query("SELECT YEAR(from_unixtime(CREATED_TIME)) as tahun, count(*) as jml from T_PN where YEAR(from_unixtime(CREATED_TIME)) = '$i' group by tahun")->result();
                    if(empty($tmp)){
                        $tmp = '0';
                    }else{
                        $tmp = $tmp[0]->jml;
                    }
                    $pn[$i]  = $tmp;
                    $lhkpn[$i]  = '0';
                    $tahun[]    = $i;
                 }

                foreach($item as $row){
                    $lhkpn[$row['tahun']] = $row['jml'];
                }

                $data = array(
                    'inst'          => $inst,
                    'lhkpn'         => $lhkpn,
                    'pn'            => $pn,
                    'tahunstart'    => $tahunstart,
                    'tahunend'      => $tahunend,
                    'tahun'         => $tahun
                );

                $html = $this->load->view(strtolower(__CLASS__).'_kepatuhan', $data, TRUE);
                return $html;
                break;
        }
    }
}
