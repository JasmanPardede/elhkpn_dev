<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penugasan_last extends CI_Controller {

    // num of records per page
    public $limit = 10;
    public $username;
    public $templateDir = 'templates/adminlte/';

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
        $this->username = $this->session->userdata('USERNAME');
        // $this->makses->initialize();
        // $this->makses->check_is_read();
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);

        // prepare search
        foreach ((array) @$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];

        $this->act = $this->input->post('act', TRUE);
        $this->remapSegment();
        $this->load->model('eaudit/Penugasan_model');
    }

    private function isDataEntry() {
        $role = $this->session->userdata('ID_ROLE');
        if ($role == '6') {
            return true;
        } else {
            return false;
        }
    }

    private function remapSegment() {
        $segs = $this->uri->segment_array();
        $i = 0;
        $map[] = 'index.php';
        foreach ($segs as $segment) {
            ++$i;
            $map[] = $segment;
            $this->segmentName[$i] = $segment;
            $this->segmentTo[$i] = implode('/', $map) . '/';
        }
    }

    /**
     * Penyelenggara Negara List
     * 
     * @return html Penyelenggara Negara List
     */
    public function index($offset = 0) {
        $this->base_url = 'index.php/eaudit/penugasan/index';
        $this->load->model('Mlhkpn');
        list($this->items, $this->total_rows) = $this->Penugasan_model->get_data_lhkpn($this->CARI, $offset);
        $this->end = count($this->items);
        $maxcounter_bast_cs = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, NULL, "max(NOMOR_BAP) as maxcounter")[0]->maxcounter;
        $this->nobap = '0' . ($maxcounter_bast_cs + 1);
        $this->data['total_rows'] = $this->total_rows;
        $this->data['offset'] = $offset;
        $this->data['items'] = $this->items;
        $this->data['start'] = $offset + 1;
        $this->data['end'] = $offset + $this->end;
        $this->data['pagination']  = call_user_func('ng::genPagination');
                $data = array(
//                    'items' => $this->items,
//                    'total_rows' => $this->total_rows,
                   // 'offset' => $offset,
//                    'start' => $this->offset + 1,
//                    'end' => $this->offset + $this->end,
                    'CARI' => @$this->CARI,
                    'nobap' => $this->nobap,
                    'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                        'Dashboard' => 'index.php/welcome/dashboard',
                        'E-Audit' => '#index.php/eaudit',
                        'Daftar Penugasan' => '#index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
                    )),
//                    'pagination' => call_user_func('ng::genPagination'),
                );
                $this->data['content_paging'] = $this->load->view($this->templateDir . 'template_paging', $this->data, true);
                // $jancuk = $this->load->view($this->templateDir . 'template_paging', $this->data, true);
                // var_dump($jancuk);
                // die();
                $this->data['content_js'] = $this->load->view($this->templateDir . 'template_js', $this->data, true);
                $this->data['content_list'] = $this->load->view('penugasan/penugasan_index', $this->data, true);

        // load view

    	$data['users_admin'] = $this->Penugasan_model->get_all_pemeriksa();
        $data['cuks'] = $this->CARI;
        $this->load->view('penugasan/penugasan_index', $data);
        // $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . $mode, $data);
        // $this->load->view('announ/announ_'.$mode, $data);
    }


}
