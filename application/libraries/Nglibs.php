<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Libraries
 * 
 * @author Gunaones - SKELETON-2015 - PT.Mitreka Solusi Indonesia 
 * @package Libraries/Nglibs
 */
?>
<?php

//ini_set('max_execution_time', 300);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//class Nglibs extends CI_Controller {
class Nglibs extends MY_Controller {

    // num of records per page
    public $limit = 10;
    public $data = [];
    public $templateDir = 'templates/adminlte/';
    public $display = '';
    public $iscetak = false;
    public $viaLib = false;
    protected $called_class = NULL;

    public function __construct() {
        parent::__construct();
        $this->load->model('mglobal');
        call_user_func('ng::islogin');
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);

        // prepare search
        foreach ((array) @$this->input->post('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];
        }

        $this->act = $this->input->post('act', TRUE);
        $this->remapSegment();
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

    protected function is_not_cetak($cetak, $method) {
        if (!is_int($cetak)) {
            $this->$method('reff');
        }
    }

    public function index($method = '', $cetak = false) {
        $this->viaLib = true;
        $this->display = 'list';
        // prepare paging
        $this->base_url = @$this->segmentTo[4];
        
        if (in_array($cetak, ['pdf', 'excel', 'word'])) {
            $this->iscetak = true;
            $this->limit = 0;
        }

        $this->called_class = $method; 

        $this->data['title'] = $method ? ucwords(str_replace('_', ' ', $method)) : ucwords(str_replace('_', ' ', @$this->segmentName[2]));
        $this->data['icon'] = 'fa-list';
        $this->data['filterCetak'] = '';
        $this->data['urlAdd'] = str_replace('/' . __FUNCTION__ . '/', '/add/', @$this->segmentTo[4]);
        $this->data['urlEdit'] = str_replace('/' . __FUNCTION__ . '/', '/edit/', @$this->segmentTo[4]);
        $this->data['urlDelete'] = str_replace('/' . __FUNCTION__ . '/', '/delete/', @$this->segmentTo[4]);
        $this->data['urlDetail'] = str_replace('/' . __FUNCTION__ . '/', '/detail/', @$this->segmentTo[4]);
        $this->data['urlDisplay'] = str_replace('/' . __FUNCTION__ . '/', '/display/', @$this->segmentTo[4]);
        $this->data['urlKembalikan'] = str_replace('/' . __FUNCTION__ . '/', '/kembalikan/', @$this->segmentTo[4]);
        $this->data['thisPageUrl'] = $this->base_url;
        $this->data['linkCetak'] = @$this->segmentTo[4];
        $this->data['titleCetak'] = get_called_class() . ' ' . ucwords(str_replace('_', ' ', $method));
        $this->data['filenameCetak'] = strtolower(get_called_class());
        $this->data['cetak'] = $cetak;
        $this->data['CARI'] = @$this->CARI;

        $breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
        $breadcrumbitem[] = [ucwords(strtolower(get_called_class())) => @$this->segmentTo[2]];
        if ($method) {
            $breadcrumbitem[] = [$this->data['title'] => @$this->segmentTo[4]];
        }

        $breadcrumbdata = [];
        foreach ($breadcrumbitem as $list) {
            $breadcrumbdata = array_merge($breadcrumbdata, $list);
        }

        $this->data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);

//var_dump($this->methodExists($method), $method);exit;
        if ($this->methodExists($method)) {
            $this->beforeQuery($method);
            $this->$method('list');
            
            $this->afterQuery($method);

            $this->is_not_cetak($cetak, $method);
        }

        $properties_dictionary = array(
            "total_rows",
            "offset",
            "items",
            "end",
        );

        foreach ($properties_dictionary as $property) {
            if (!property_exists($this, $property)) {
                $this->{$property} = NULL;
            }
        }

        $this->data['total_rows'] = @$this->total_rows;
        $this->data['offset'] = @$this->offset;
        $this->data['items'] = @$this->items;

        $this->data['start'] = @$this->offset + 1;

        $this->data['end'] = @$this->offset + @$this->end;
        $this->data['pagination'] = call_user_func('ng::genPagination');
//echo $this->templateDir . 'template_paging';exit;
        $this->data['content_paging'] = $this->load->view($this->templateDir . 'template_paging', $this->data, true);
        $this->data['content_js'] = $this->load->view($this->templateDir . 'template_js', $this->data, true);

        // load view
        if ($this->iscetak) {
            ng::exportDataTo($this->data, $cetak, strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . $method . '_' . 'cetak', $this->data['filenameCetak']);
        } else {

            $this->data['content_list'] = $this->load->view($method ? strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . $method . '_' . strtolower(__FUNCTION__) : strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . strtolower(__FUNCTION__), $this->data, true);
            $this->load->view($this->templateDir . 'template_display', $this->data);
        }
    }

    public function to_json($data) {
        $this->output->set_content_type('json');
        $this->output->set_output(json_encode($data));
    }

    protected function _param_load_paging_default($area = FALSE) {
        if ($area != FALSE && $area != 'default')
            $area = '_' . $area;
        else
            $area = '';

        $keyword_found = $this->input->get('keyword' . $area);
        $sorting_found = $this->input->get('sort' . $area);
        $currpage_found = $this->input->get('currpage' . $area);
        $rowperpage_found = $this->input->get('rowperpage' . $area);

//        $curr_page = ($currpage_found / $rowperpage_found);
//        $currpage_found = $curr_page + 1;

        if (!$rowperpage_found || is_null($rowperpage_found)) {
            $rowperpage_found = 10;
        }

        return array(
            trim($currpage_found),
            trim($rowperpage_found),
            trim($keyword_found),
            trim($this->input->get('state_active' . $area)),
            $sorting_found
        );
    }

    public function methodExists($method) {
        if ($method) {
            if (method_exists($this, $method)) {
                return true;
            } else {
                show_error('Halaman Tidak Tersedia!', 404);
            }
        }
        return false;
    }

    public function getCaller() {
        $trace = debug_backtrace();
        $name = $trace[2]['function'];
        return empty($name) ? 'global' : $name;
    }

    public function add($method = '') {
        $this->display($method, '', 'add');
    }

    public function edit($method = '', $id = '') {
        $this->display($method, $id, 'edit');
    }

    public function delete($method = '', $id = '') {
        $this->display($method, $id, 'delete');
    }

    public function detail($method = '', $id = '') {
        $this->display($method, $id, 'detail');
    }
    
    public function kembalikan($method = '', $id = '') {
        $this->display($method, $id, 'kembalikan');
    }


    public function display($method = '', $id = '', $display = '', $tgl_klarifikasi='') {  
        
        $this->viaLib = true;
        $this->display = $display;
        $this->data['urlSave'] = str_replace('/' . $this->getCaller() . '/', '/save/', $this->segmentTo[4]);
        $this->data['form'] = $display;

        switch ($display) {
            case 'add':
                $this->data['act'] = 'doinsert';
                break;
            case 'edit':
                $this->data['act'] = 'doupdate';
                break;
            case 'delete':
                $this->data['act'] = 'dodelete';
                break;
            case 'kembalikan':
                $this->data['act'] = 'dokembalikan';
                break;
            case 'detail':
                $this->data['act'] = '';
                break;
            default:
                $this->data['act'] = 'do' . $display;
                break;
        }

        $this->data['display'] = $display;
		$this->data['tgl_klarifikasi'] = $tgl_klarifikasi;
				
        if ($this->methodExists($method)) {
            $this->$method('edit', $id);
            $this->data = array_merge($this->data, $this->data);
            if (!in_array($display, array('detail', 'delete'))) {
                $this->$method('reff', $id, $tgl_klarifikasi);
            }
        } 
		//display($method);exit;
        // load view 
        
        $this->load->view($method ? strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . $method . '_form' : strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_form', $this->data);
    }

    public function save($method = '') {
        $this->db->trans_begin();

        if ($this->methodExists($method)) {
            $this->$method('save');
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    public function prepareFilterCetak($filterCetakItem) {
        if (is_array($filterCetakItem)) {
            $showFilterLabel = 1;
            $filterCetak = '';
            foreach ($filterCetakItem as $k => $v) {
                if ($v != '') {
                    if ($showFilterLabel == 1) {
                        $filterCetak .= 'Pencarian Berdasarkan : <br>';
                        $showFilterLabel = 0;
                    }
                    $filterCetak .= $k . ' : ' . $v . '<br>';
                }
            }
            $filterCetak .= '<br>';
        }
        return $filterCetak;
    }

    protected function beforeQuery($method) {
        $this->db->start_cache();
    }

    protected function afterQuery($sql_feeder = FALSE) {
        $sql = $this->db->show_compile_select();

        $this->offset = $this->offset && !is_null($this->offset) ? $this->offset : '0';

        $sql .= "  LIMIT " . $this->offset . ", " . $this->limit . " ";


        $this->total_rows = $this->db->count_all_results();

        $this->db->limit($this->limit, $this->offset);
        $query = $this->db->query($sql);

        $this->items = array();
        $this->end = 0;


        if ($query) {
            $this->items = $query->result();
            $this->end = $query->num_rows();
        }

        // echo $this->db->last_query();
        $this->db->flush_cache();
    }

    public function getViaLib() {
        return $this->viaLib;
    }

    public function blockDirectAccessMethod() {
        if ($this->viaLib === false) {
            show_error('No direct script access allowed!');
        }
    }

    public function getModuleName() {
        return $this->router->module;
    }

}
/* End of file Nglibs.php */