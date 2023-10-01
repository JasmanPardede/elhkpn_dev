<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller Announ
 *
 * @author Gunaones - SKELETON-2015b - PT.Mitreka Solusi Indonesia
 * @package Controllers
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Announ extends CI_Controller {

    // num of records per page
    public $limit = 10;
    public $username;
    public $templateDir = 'templates/adminlte/';

    public function __construct($skip_akses = FALSE) {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
        $this->username = $this->session->userdata('USERNAME');
        if (!$skip_akses) {
            $this->makses->initialize();
            $this->makses->check_is_read();
        }
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);

        // prepare search
        foreach ((array) @$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];

        $this->act = $this->input->post('act', TRUE);
        $this->remapSegment();
        if ($this->input->get('show_profiler')) {
            $this->output->enable_profiler(TRUE);
        }
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
    public function index($mode = '', $offset = 0, $cetak = false) {
        if (in_array($cetak, ['pdf', 'excel', 'word'])) {
            $this->iscetak = true;
            $this->limit = 0;
        }

        $usr = $this->session->userdata('USR');
        $roles = $this->session->userdata('ID_ROLE');

        // prepare paging
        $this->base_url = 'index.php/eano/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/' . $mode . '/';
//        $this->uri_segment = 4;
//        $this->offset = $this->uri->segment($this->uri_segment);

        switch ($mode) {
            case 'list' :
                if (empty($this->CARI['TAHUN']) && empty($this->CARI['JENIS']) && empty($this->CARI['LEMBAGA']) && empty($this->CARI['UNIT_KERJA']) && empty($this->CARI['NAMA'])) {
                    $this->data['no_filter'] = 1;
                } else {
                    $this->load->model('Mlhkpn');
                    list($this->items, $this->total_rows) = $this->Mlhkpn->list_announ_lhkpn_pnwl($this->CARI, $this->limit, $this->offset);
                    $this->end = count($this->items);
                    $this->data['title'] = 'LHKPN Masuk';
                    $this->data['total_rows'] = @$this->total_rows;
                    $this->data['offset'] = @$this->offset;
                    $this->data['items'] = @$this->items;
                    $this->data['start'] = @$this->offset + 1;
                    $this->data['end'] = @$this->offset + @$this->end;
                    $this->data['pagination'] = call_user_func('ng::genPagination');
    
                    $data = array(
                        'CARI' => @$this->CARI,
                        'nobap' => $this->nobap,
                        'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                            'Dashboard' => 'index.php/welcome/dashboard',
                            'E-Annoncement' => 'index.php/dashboard/eano',
                            'List Announcement' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
                        )),
                    );
                }
                $this->data['content_paging'] = $this->load->view($this->templateDir . 'template_paging', $this->data, true);
                $this->data['content_js'] = $this->load->view($this->templateDir . 'template_js', $this->data, true);
                $this->data['content_list'] = $this->load->view(strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_list', $this->data, true);
                break;
            case 'terverifikasi' :
                $this->db->start_cache();
                $this->db->select('*');
                $this->db->from('T_LHKPN');
                $this->db->join('(
                        select  ID_LHKPN AS ID_LHKPN_DIJABATAN,
                            group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN,
                            group_concat(
                                CONCAT(
                                    IFNULL(T_LHKPN_JABATAN.ID,""),":58:",
                                    IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                                    IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                                    IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":58:",

                                    IFNULL(T_LHKPN_JABATAN.DESKRIPSI_JABATAN,"")," - ",

                                    IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ",
                                    IFNULL(M_INST_SATKER.INST_AKRONIM,"")
                                )
                            ) as NAMA_JABATAN
                			from T_LHKPN_JABATAN
                            LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                            LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA
                            LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA
                            LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
                            LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT
                            GROUP BY T_LHKPN_JABATAN.ID_LHKPN
                        ) JABATAN', 'JABATAN.ID_LHKPN_DIJABATAN = T_LHKPN.ID_LHKPN', 'left');
                $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
                $this->db->join('T_PENUGASAN_ANNOUN', 'T_PENUGASAN_ANNOUN.ID_LHKPN = T_LHKPN.ID_LHKPN');
                $this->db->where('STATUS', '3');

                if (@$this->CARI['TAHUN']) {
                    $this->db->where('YEAR(TGL_LAPOR)', $this->CARI['TAHUN']);
                }

                if (@$this->CARI['LEMBAGA']) {
                    $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN');
                    $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA');
                    $this->db->like('M_INST_SATKER.INST_NAMA', $this->CARI['LEMBAGA']);
                }

                if (@$this->CARI['NAMA']) {
                    $this->db->like('T_PN.NAMA', $this->CARI['NAMA']);
                }

                if (@$this->CARI['JENIS']) {
                    $this->db->where('T_LHKPN.JENIS_LAPORAN', $this->CARI['JENIS']);
                }

                if (@$this->CARI['PERIKSA'] != '') {
                    if ($this->CARI['PERIKSA'] == '0') {
                        $this->db->where('T_LHKPN.CATATAN_PERBAIKAN_NASKAH', $this->CARI['PERIKSA']);
                    } else {
                        $this->db->where('T_LHKPN.CATATAN_PERBAIKAN_NASKAH <>', '0');
                    }
                }

                if (@$this->CARI['STATUS'] != '') {
                    $status = $this->CARI['STATUS'];
                    $this->db->where('T_LHKPN.STATUS', $status);
                }
                $this->data['title'] = 'LHKPN Masuk';

                $this->db->order_by('T_PN.NAMA', 'asc');
                $this->total_rows = $this->db->get('')->num_rows();
                $this->db->where('T_PENUGASAN_ANNOUN.USERNAME', $this->session->userdata('USERNAME'));
                $this->db->or_where('T_PENUGASAN_ANNOUN.UPDATED_BY', $this->session->userdata('USERNAME'));

                $query = $this->db->get('', $this->limit, $this->offset);
                $this->items = $query->result();
                // echo $this->db->last_query();
                $this->end = $query->num_rows();
                $this->db->flush_cache();

                $data = array(
                    'items' => $this->items,
                    'total_rows' => $this->total_rows,
                    'offset' => $this->offset,
                    'CARI' => @$this->CARI,
                    'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                        'Dashboard' => 'index.php/welcome/dashboard',
                        'E-Annoncement' => 'index.php/dashboard/eano',
                        'List Announcement' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
                    )),
                    'pagination' => call_user_func('ng::genPagination'),
                );
                break;
            case 'publikasi' :
//                $this->db->start_cache();
////                $this->db->select('T_PN.*, JABATAN.*, T_LHKPN.*');
//                $this->db->select('*, T_LHKPN.ID_LHKPN AS ID_LHKPN_DIJABATAN,T_LHKPN.ID_LHKPN,T_LHKPN_JABATAN.ID_LHKPN, T_LHKPN.STATUS');
//                $this->db->from('T_LHKPN');
//                /* $this->db->join('(
//                  select  ID_LHKPN AS ID_LHKPN_DIJABATAN,
//                  group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN,
//                  group_concat(
//                  CONCAT(
//                  IFNULL(T_LHKPN_JABATAN.ID,""),":58:",
//                  IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
//                  IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
//                  IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":58:",
//                  IFNULL(M_JABATAN.NAMA_JABATAN,"")," - ",
//
//                  IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ",
//                  IFNULL(M_INST_SATKER.INST_AKRONIM,"")
//                  )SEPARATOR "|"
//                  ) as NAMA_JABATAN
//                  from T_LHKPN_JABATAN
//                  LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
//                  LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA
//                  LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA
//                  LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
//                  LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT
//                  GROUP BY T_LHKPN_JABATAN.ID_LHKPN
//                  ) JABATAN', 'JABATAN.ID_LHKPN_DIJABATAN = T_LHKPN.ID_LHKPN', 'left'); */
//                $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN AND T_LHKPN_JABATAN.IS_PRIMARY = "1"', 'left');
//                $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
//                $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA', 'left');
//                $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA', 'left');
//                $this->db->join('M_ESELON', 'M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON', 'left');
//                $this->db->join('T_STATUS_AKHIR_JABAT', 'T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT', 'left');
//                $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
//                $this->db->join('R_BA_PENGUMUMAN', 'R_BA_PENGUMUMAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
//                $this->db->where("T_LHKPN.STATUS IN ('5', '3')");
////                $this->db->or_where('STATUS', '3');
//                // $this->db->where('STATUS_PERBAIKAN_NASKAH', '1');
//                $this->db->where("T_LHKPN.ID_LHKPN NOT IN (SELECT ID_LHKPN FROM R_BA_PENGUMUMAN)");
//                $this->db->where('R_BA_PENGUMUMAN.ID_LHKPN IS NULL', null, false);
//
//                if (@$this->CARI['TAHUN']) {
//                    $this->db->where('YEAR(TGL_LAPOR)', $this->CARI['TAHUN']);
//                }
//
//                if (@$this->CARI['LEMBAGA']) {
//                    $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN');
//                    $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA');
//                    $this->db->like('M_INST_SATKER.INST_NAMA', $this->CARI['LEMBAGA']);
//                }
//
//                if (@$this->CARI['NAMA']) {
//                    $this->db->like('T_PN.NAMA', $this->CARI['NAMA']);
//                }
//
//                if (@$this->CARI['JENIS']) {
//                    $this->db->where('T_LHKPN.JENIS_LAPORAN', $this->CARI['JENIS']);
//                }
//
//                if (@$this->CARI['VIA'] != '') {
//                    $this->db->where('T_LHKPN.ENTRY_VIA', $this->CARI['VIA']);
//                }
//
//                if (@$this->CARI['STATUS'] != '') {
//                    $status = $this->CARI['STATUS'];
//                    $this->db->where('T_LHKPN.STATUS', $status);
//                }
//
//                $this->db->order_by('T_PN.NAMA', 'asc');
//                $this->total_rows = $this->db->get('')->num_rows();
//
//                $query = $this->db->get('', $this->limit, $this->offset);
//                $this->items = $query->result();
////                display($this->db->last_query());
//                $this->end = $query->num_rows();
//                $this->db->flush_cache();
//
//                $this->data['title'] = 'LHKPN Masuk';

                $this->data['id'] = $this->input->post('id');
                $setlimit = $this->input->post('limit');
                $limit = (is_null($setlimit)) ? '10' : $setlimit;
                $this->limit = $limit;

                if ($this->data['id'] != '') {
                    $stat = array('3', '5');
                    $wherein = str_replace(",", "','", $this->data['id']);

                    $this->db->select('T_LHKPN_JABATAN.*, 
                                    T_LHKPN_DATA_PRIBADI.*,
                                    M_JABATAN.*,
                                    M_INST_SATKER.*,
                                    M_UNIT_KERJA.*,
                                    T_PN.*,
                                    R_BA_PENGUMUMAN.*,
                                    T_LHKPN.ID_LHKPN,
                                    T_LHKPN.ALASAN,
                                    T_LHKPN.entry_via,
                                    T_LHKPN.ID_PN,
                                    T_LHKPN.IS_ACTIVE,
                                    T_LHKPN.JENIS_LAPORAN,
                                    T_LHKPN.STATUS,
                                    T_LHKPN.tgl_kirim,
                                    T_LHKPN.tgl_kirim_final,
                                    T_LHKPN.tgl_lapor,
                                    T_LHKPN.USERNAME_ENTRI,
                                    T_LHKPN.back_to_draft,
                                    T_LHKPN.ID_LHKPN AS ID_ID_LHKPN');
                    $this->db->from('T_LHKPN');
                    /* $this->db->join('(
                      select  ID_LHKPN AS ID_LHKPN_DIJABATAN,
                      group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN,
                      group_concat(
                      CONCAT(
                      IFNULL(T_LHKPN_JABATAN.ID,""),":58:",
                      IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                      IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                      IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":58:",
                      IFNULL(M_JABATAN.NAMA_JABATAN,"")," - ",
                      IFNULL(T_LHKPN_JABATAN.DESKRIPSI_JABATAN,"")," - ",
                      -- "(", IFNULL(M_ESELON.ESELON,""), ") - ", --
                      IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ",
                      IFNULL(M_INST_SATKER.INST_AKRONIM,"")
                      )
                      ) as NAMA_JABATAN
                      from T_LHKPN_JABATAN
                      LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                      LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA
                      LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA
                      LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
                      LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT
                      GROUP BY T_LHKPN_JABATAN.ID_LHKPN
                      ) JABATAN', 'JABATAN.ID_LHKPN_DIJABATAN = T_LHKPN.ID_LHKPN', 'left'); */
                    $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN AND T_LHKPN_JABATAN.IS_PRIMARY = "1"', 'left');
                    $this->db->join('T_LHKPN_DATA_PRIBADI', 'T_LHKPN_DATA_PRIBADI.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
                    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN AND M_JABATAN.IS_ACTIVE <> 0', 'left');
                    $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD', 'left');
                    $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID', 'left');
                    // $this->db->join('M_ESELON', 'M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON', 'left');
                    // $this->db->join('T_STATUS_AKHIR_JABAT', 'T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT', 'left');
                    $this->db->join('R_BA_PENGUMUMAN', 'R_BA_PENGUMUMAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
                    $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN', 'left');
                    $this->db->where_in('T_LHKPN.STATUS', $stat);
                    $this->db->where('T_LHKPN.IS_ACTIVE', '1');
                    $this->db->where('R_BA_PENGUMUMAN.ID_LHKPN', NULL);
                    $this->db->where('T_LHKPN.entry_via <>', '2');
                    $this->db->where('T_LHKPN.JENIS_LAPORAN <>', '5');
                    $this->db->where('YEAR(T_LHKPN.tgl_kirim_final) >=', '2017');
                    //$this->db->where_in('T_LHKPN.ID_LHKPN', $wherein);
                    $where = "T_LHKPN.ID_LHKPN IN ('" . $wherein . "')";
                    $this->db->where($where);
                    $this->db->order_by('T_LHKPN.tgl_kirim_final', 'ASC');
//                    $this->db->or_where('T_LHKPN.STATUS', '3');
                    // $this->db->where('STATUS_PERBAIKAN_NASKAH', '1');
                        
                    $query = $this->db->get('');
                    $this->data['item_selected'] = $query->result();
                    $this->db->flush_cache();
                }

                $this->load->model('Mlhkpn');

                list($this->items, $this->total_rows) = $this->Mlhkpn->list_penugasan_announ_lhkpn($this->CARI, $this->limit, $this->offset);

                $this->end = count($this->items);

                $maxcounter_bast_cs = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, NULL, "max(NOMOR_BAP) as maxcounter")[0]->maxcounter;
//                $maxcounter_bast_cs = 1;
//                $this->nobap = ($maxcounter_bast_cs + 1) . '/' . date('m') . '/' . date('Y');
//                $this->nobap = '0' . ($maxcounter_bast_cs + 1);
                $this->nobap = ($maxcounter_bast_cs + 1);

                $this->data['total_rows'] = @$this->total_rows;
                $this->data['offset'] = @$this->offset;
                $this->data['items'] = @$this->items;
                $this->data['start'] = @$this->offset + 1;
                $this->data['end'] = @$this->offset + @$this->end;
                $this->data['pagination'] = call_user_func('ng::genPagination');

                $data = array(
//                    'items' => $this->items,
//                    'total_rows' => $this->total_rows,
//                    'offset' => $this->offset,
//                    'start' => $this->offset + 1,
//                    'end' => $this->offset + $this->end,
                    'CARI' => @$this->CARI,
                    'nobap' => $this->nobap,
                    'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                        'Dashboard' => 'index.php/welcome/dashboard',
                        'E-Annoncement' => 'index.php/dashboard/eano',
                        'List Announcement' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
                    )),
//                    'pagination' => call_user_func('ng::genPagination'),
                    'limit' => $this->limit,
                );
                $this->data['content_paging'] = $this->load->view($this->templateDir . 'template_paging', $this->data, true);
                $this->data['content_js'] = $this->load->view($this->templateDir . 'template_js', $this->data, true);
                $this->data['content_list'] = $this->load->view(strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_publikasi', $this->data, true);

                break;
            case 'bap' :
                $this->db->start_cache();

                $this->db->select('*');
                $this->db->from('T_BA_PENGUMUMAN');
                $this->db->where('1=1', null, false);

                if (@$this->CARI['TAHUN']) {
                    $this->db->where('YEAR(T_BA_PENGUMUMAN.TGL_BA_PENGUMUMAN) = ' . $this->CARI['TAHUN']);
                }

                if (@$this->CARI['BULAN']) {
                    $this->db->where('MONTH(T_BA_PENGUMUMAN.TGL_BA_PENGUMUMAN) = ' . $this->CARI['BULAN']);
                }

                $this->total_rows = $this->db->get('')->num_rows();
                $query = $this->db->get('', $this->limit, $this->offset);
                // echo $this->db->last_query();
                $this->items = $query->result();
                $this->end = $query->num_rows();
                $this->db->flush_cache();

                $data = array(
                    'icon' => 'fa-list',
                    'thisPageUrl' => $this->base_url . 'bap',
                    'items' => @$this->items,
                    'total_rows' => @$this->total_rows,
                    'offset' => @$this->offset,
                    'CARI' => @$this->CARI,
                    'start' => @$this->offset + 1,
                    'end' => @$this->offset + @$this->end,
                    'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                        'Dashboard' => 'index.php/welcome/dashboard',
                        'E-Annoncement' => 'index.php/eano/announ/',
                        'List Announcement' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
                    )),
                    'pagination' => call_user_func('ng::genPagination'),
                );
                break;
            case 'perbaikan' :
                $this->db->start_cache();

                $this->db->select('*');
                $this->db->from('T_LHKPN');
                $this->db->join('(
                        select  ID_LHKPN AS ID_LHKPN_DIJABATAN,
                            group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN,
                            group_concat(
                                CONCAT(
                                    IFNULL(T_LHKPN_JABATAN.ID,""),":58:",
                                    IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                                    IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                                    IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":58:",
                                    IFNULL(M_JABATAN.NAMA_JABATAN,"")," - ",
                                    IFNULL(T_LHKPN_JABATAN.DESKRIPSI_JABATAN,"")," - ",
                                    -- "(", IFNULL(M_ESELON.ESELON,""), ") - ", --
                                    IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ",
                                    IFNULL(M_INST_SATKER.INST_AKRONIM,"")
                                )
                            ) as NAMA_JABATAN
                			from T_LHKPN_JABATAN
                            LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                            LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA
                            LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA
                            LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
                            LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT
                            GROUP BY T_LHKPN_JABATAN.ID_LHKPN
                        ) JABATAN', 'JABATAN.ID_LHKPN_DIJABATAN = T_LHKPN.ID_LHKPN', 'left');
                $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
                $this->db->where('1=1', null, false);
                $this->db->where('STATUS_PERBAIKAN_NASKAH', 2);

                if (@$this->CARI['TAHUN']) {
                    $this->db->where('YEAR(T_LHKPN.TGL_LAPOR) = ' . $this->CARI['TAHUN']);
                }

                if (@$this->CARI['NAMA']) {
                    $this->db->where("T_PN.NAMA LIKE '%" . $this->CARI['NAMA'] . "%'", null, true);
                }

                $this->total_rows = $this->db->get('')->num_rows();
                $query = $this->db->get('', $this->limit, $this->offset);
                // echo $this->db->last_query();
                $this->items = $query->result();
                $this->end = $query->num_rows();
                $this->db->flush_cache();
                $data = array(
                    'icon' => 'fa-list',
                    'thisPageUrl' => $this->base_url . 'perbaikan',
                    'items' => @$this->items,
                    'total_rows' => @$this->total_rows,
                    'offset' => @$this->offset,
                    'CARI' => @$this->CARI,
                    'start' => @$this->offset + 1,
                    'end' => @$this->offset + @$this->end,
                    'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                        'Dashboard' => 'index.php/welcome/dashboard',
                        'E-Annoncement' => 'index.php/eano/announ/',
                        'Perbaikan Naskah' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/' . $mode,
                    )),
                    'pagination' => call_user_func('ng::genPagination'),
                );
                break;
            default :

                break;
        }

        // load view
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . $mode, $data);
        // $this->load->view('announ/announ_'.$mode, $data);
    }

    public function genpdf($id_lhkpn = null) {
        $this->load->model('mlhkpnkeluarga');
        $this->load->model('mlhkpn', '', TRUE);
        $this->load->model('mlhkpn_lampiran2', '', TRUE);
        $this->load->model('mlhkpndokpendukung', '', TRUE);

        // cek jika $id_lhkpn null
        if ($id_lhkpn == null) {
            show_error('invalid url', 404);
            die('invalid url');
        }

        $joinMATA_UANG = [
            ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
            ['table' => 'M_JENIS_HARTA', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA']
        ];
        $joinMU = [['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG']];
        $joinHARTA_TIDAK_BERGERAK = [
            ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG']
        ];
        $where_eHARTA_TIDAK_BERGERAK = "SUBSTRING(md5(data.ID_LHKPN), 6, 8) = '$id_lhkpn'";
        $selectHARTA_TIDAK_BERGERAK = 'IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, (SELECT NAME from M_AREA where IDPROV = data.PROV AND CAST(IDKOT as UNSIGNED) = data.KAB_KOT AND IDKEC = data.KEC AND IDKEL = data.KEL) as KEL, (SELECT NAME from M_AREA where IDPROV = data.PROV AND CAST(IDKOT as UNSIGNED) = data.KAB_KOT AND IDKEC = data.KEC LIMIT 1) AS KEC, data.KAB_KOT, data.PROV, data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';

        //jenis bukti
        $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI');
        $list_bukti = [];
        foreach ($jenis_bukti as $key) {
            $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
        }
        //jenis Harta
        $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA');
        $list_harta = [];
        foreach ($jenis_HARTA as $key) {
            $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
        }
        //jenis harta bergerak lain
        $list_harta_berhenti = [
            '1' => 'Perabotan Rumah Tangga',
            '2' => 'Barang Elektronik',
            '3' => 'Perhiasan & Logam / Batu Mulia',
            '4' => 'Barang Seni / Antik',
            '5' => 'Persediaan',
            '6' => 'Harta Bergerak Lainnya',
        ];
        //jenis harta surat berharga
        $list_harta_surat = [
            '1' => 'Penyertaan Modal pada Badan Hukum',
            '2' => 'Investasi',
        ];
        //jenis harta kas
        $list_harta_kas = [
            '1' => 'Uang Tunai',
            '2' => 'Deposite',
            '3' => 'Giro',
            '4' => 'Tabungan',
            '5' => 'Lainnya',
        ];
        //jenis harta lainnya
        $list_harta_lain = [
            '1' => 'Piutang',
            '2' => 'Kerjasama Usaha yang Tidak Berbadan Hukum',
            '3' => 'Hak Kekayaan Intelektual',
            '4' => 'Sewa Jangaka Panjang Dibayar Dimuka',
            '5' => 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan',
        ];

        $data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
        $data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');

        $this->data['list_harta'] = $list_harta;
        $this->data['LHKPN'] = @$this->mglobal->get_data_all(
                        'T_LHKPN', [
                    ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'jbt.LEMBAGA   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'jbt.UNIT_KERJA   =  unke.UK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'unke.UK_BIDANG_ID =  bdg.BDG_ID']
                        ], NULL, '*', "SUBSTRING(md5(T_LHKPN.ID_LHKPN), 6, 8) = '$id_lhkpn'"
                )[0];
        // display($this->data['LHKPN']);exit();
        $this->data['lhkpn_jbtn'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', [['table' => 'M_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN']], NULL, 'NAMA_JABATAN', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['id_lhkpn'] = $this->data['LHKPN']->ID_LHKPN;

        $this->data['hartirak'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_TIDAK_BERGERAK', 'NILAI_PELAPORAN', 'sum_hartirak')[0];
        $this->data['harger'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_BERGERAK', 'NILAI_PELAPORAN', 'sum_harger')[0];
        $this->data['harger2'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_BERGERAK_LAIN', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harger2')[0];
        $this->data['suberga'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_SURAT_BERHARGA', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_suberga')[0];
        $this->data['kaseka'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_KAS', "REPLACE(NILAI_EQUIVALEN,'.','')", 'sum_kaseka')[0];
        $this->data['harlin'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_LAINNYA', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harlin')[0];
        $this->data['_hutang'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HUTANG', 'SALDO_HUTANG', 'sum_hutang')[0];
        $this->data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
        $this->data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
        $this->data['DATA_PRIBADI'] = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'")[0];

        $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN';
        $joinJabatan = [
            ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
        ];
        $this->data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "SUBSTRING(md5(T_LHKPN_JABATAN.ID_LHKPN), 6, 8) = '$id_lhkpn'");

        $this->data['lembaga'] = @$this->mglobal->get_data_all('M_INST_SATKER', NULL, NULL, '*', NULL);
        $this->data['rinci_keluargas'] = $this->mlhkpnkeluarga->get_rincian($this->data['id_lhkpn']);
        $this->data['KELUARGAS'] = $this->mglobal->get_data_all('T_LHKPN_KELUARGA', NULL, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_TIDAK_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, NULL, [$selectHARTA_TIDAK_BERGERAK, FALSE], $where_eHARTA_TIDAK_BERGERAK);
        $this->data['HARTA_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinMATA_UANG, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_BERGERAK_LAINS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinMU, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinMATA_UANG, NULL, "*,REPLACE(NILAI_PELAPORAN,'.','') as PELAPORAN", "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMATA_UANG, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinMU, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // display($this->data['HARTA_LAINNYAS']);exit();
        $this->data['HUTANGS'] = $this->mglobal->get_data_all('T_LHKPN_HUTANG', NULL, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['PENERIMAAN_KASS'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
        $this->data['PENGELUARAN_KASS'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
        $this->data['lamp2s'] = $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['keluargas'] = $this->mlhkpnkeluarga->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $id_lhkpn))->result();
        $this->data['dokpendukungs'] = $this->mlhkpndokpendukung->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $id_lhkpn))->result();
        $this->data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);

        //select lampiran pelepasan
        $selectlampiranpelepasan = 'A.TANGGAL_TRANSAKSI as TANGGAL_TRANSAKSI, A.NILAI_PELEPASAN as NILAI_PELEPASAN, A.NAMA as NAMA, A.ALAMAT as ALAMAT';
        $selectpelepasanhartatidakbergerak = ', B.ATAS_NAMA as ATAS_NAMA, B.LUAS_TANAH as LUAS_TANAH, B.LUAS_BANGUNAN as LUAS_BANGUNAN, B.NOMOR_BUKTI as NOMOR_BUKTI, B.JENIS_BUKTI as JENIS_BUKTI ';
        $selectpelepasanhartabergerak = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA as ATAS_NAMA, B.MEREK as MEREK, B.NOPOL_REGISTRASI as NOPOL_REGISTRASI, B.NOMOR_BUKTI as NOMOR_BUKTI';
        $selectpelepasanhartabergeraklain = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, ATAS_NAMA as ATAS_NAMA';
        $selectpelepasansuratberharga = ', B.KODE_JENIS as KODE_JENIS, B.NAMA_SURAT_BERHARGA as NAMA_SURAT,  B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, B.ATAS_NAMA as ATAS_NAMA';
        $selectpelepasankas = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA_REKENING as ATAS_NAMA, B.NAMA_BANK as NAMA_BANK, B.NOMOR_REKENING as NOMOR_REKENING';
        $selectpelepasanhartalainnya = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.ATAS_NAMA as ATAS_NAMA';

        // call data lampiran pelepasan
        $pelepasanhartatidakbergerak = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK as A', [['table' => 'T_LHKPN_HARTA_TIDAK_BERGERAK as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartatidakbergerak, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasanhartabergerak = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK as A', [['table' => 'T_LHKPN_HARTA_BERGERAK as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartabergerak, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasanhartabergeraklain = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN as A', [['table' => 'T_LHKPN_HARTA_BERGERAK_LAIN as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartabergeraklain, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasansuratberharga = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA as A', [['table' => 'T_LHKPN_HARTA_SURAT_BERHARGA as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasansuratberharga, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasankas = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_KAS as A', [['table' => 'T_LHKPN_HARTA_KAS as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasankas, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasanhartalainnya = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_LAINNYA as A', [['table' => 'T_LHKPN_HARTA_LAINNYA as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartalainnya, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasan = [];

        //packing hasil calling data lampiran pelepasan
        if (!empty($pelepasanhartatidakbergerak)) {
            foreach ($pelepasanhartatidakbergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => 'Tanah / Bangunan',
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "Tanah/Bangunan , Atas Nama " . @$key->ATAS_NAMA . " dengan luas tanah " . @$key->LUAS_TANAH . " dan luas bangunan " . @$key->LUAS_BANGUNAN . " dengan bukti berupa " . $list_bukti[$key->JENIS_BUKTI] . " dengan nomor bukti " . @$key->NOMOR_BUKTI,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasanhartabergerak)) {
            foreach ($pelepasanhartabergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => 'Mesin / Alat transport',
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "Sebuah " . $list_harta[@$key->KODE_JENIS] . " , Atas Nama " . @$key->ATAS_NAMA . " , merek " . @$key->MEREK . " dengan nomor registrasi " . $key->NOPOL_REGISTRASI . " dan nomor bukti " . @$key->NOMOR_BUKTI,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasanhartabergeraklain)) {
            foreach ($pelepasanhartabergeraklain as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => 'Harta bergerak',
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => $list_harta_berhenti[@$key->KODE_JENIS] . " bernama " . @$key->NAMA_HARTA . " , Atas nama " . @$key->ATAS_NAMA . " dengan jumlah " . @$key->JUMLAH . ' ' . @$key->SATUAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasansuratberharga)) {
            foreach ($pelepasansuratberharga as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => 'Surat berharga',
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => $list_harta_surat[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' berupa surat ' . @$key->NAMA_SURAT . ' dengan jumlah ' . @$key->JUMLAH . ' ' . @$key->SATUAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasankas)) {
            foreach ($pelepasankas as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => 'KAS / Setara KAS',
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "KAS berupa " . $list_harta_kas[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' pada bank ' . @$key->NAMA_BANK . ' dengan nomor rekening ' . @$key->NOMOR_REKENING,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasanhartalainnya)) {
            foreach ($pelepasanhartalainnya as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => 'Harta lainnya',
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "Harta lain berupa " . $list_harta_lain[@$key->KODE_JENIS] . ' dengan nama harta ' . @$key->NAMA_HARTA . ' atas nama ' . @$key->ATAS_NAMA,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }

        $this->data['lampiran_pelepasan'] = $pelepasan;

        //perhitunganpengeluaran kas
        $whereperhitunganpengeluaran = "WHERE IS_ACTIVE = '1' AND SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'";
        $this->data['getPenka'] = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS', $whereperhitunganpengeluaran);

        //perhitunganpemaasukan kas
        $whereperhitunganpemaasukan = "WHERE IS_ACTIVE = '1' AND SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn' ";
        $this->data['getPemka'] = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $whereperhitunganpemaasukan);

        // echo "<pre>";
        // print_r ($this->data['getPenka']);
        // echo "</pre>";
        // $this->data['']  = $this->mglobal->get_data_all('T_LHKPN_PENERIMAAN_KAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // $this->data['PENGELUARAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENGELUARAN_KAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // load view

        $this->data['lampiran_hibah'] = $this->_lampiran_hibah($id_lhkpn);

        $html = $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $this->data, true);
        $html2 = $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__) . '2', $this->data, true);

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
//        $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->SetFooter(FALSE);
        $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->AddPage();
        $pdf->WriteHTML($html2);
        // $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $pdf->Output();
    }

    private function _lampiran_hibah($id_lhkpn, $where = NULL) {
        if (is_null($where)) {
            $where = '';
        }
        $result = $this->db->query("
          SELECT
            'Tanah / Bangunan' as kode,
            TGL_TRANSAKSI as tgl,
            CONCAT('Tanah/Bangunan , Atas Nama ',ATAS_NAMA,' dengan luas tanah ',LUAS_TANAH,' dan luas bangunan ',LUAS_BANGUNAN,' dengan bukti berupa ',
            C.JENIS_BUKTI,' dengan nomor bukti ',NOMOR_BUKTI) as uraian,
            NILAI_PELEPASAN as nilai,
            D.ASAL_USUL as jenis,
            B.ALAMAT as almat,
            B.NAMA as nama

            from T_LHKPN_HARTA_TIDAK_BERGERAK A
            INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK B ON A.ID=B.ID_HARTA
            INNER JOIN M_JENIS_BUKTI C ON A.JENIS_BUKTI=C.ID_JENIS_BUKTI
            INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
            WHERE SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'
            UNION

          SELECT
            'Mesin / Alat Transport' as kode,
            TGL_TRANSAKSI as tgl,
            CONCAT('Sebuah ',C.NAMA,' , Atas Nama ',ATAS_NAMA,' , merek ',MEREK,' dengan nomor registrasi ',NOPOL_REGISTRASI,' dan nomor bukti ',NOMOR_BUKTI) as uraian,
            NILAI_PELEPASAN as nilai,
            D.ASAL_USUL as jenis,
            B.ALAMAT as almat,
            B.NAMA as nama

            from T_LHKPN_HARTA_BERGERAK A
            INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK B ON A.ID=B.ID_HARTA
            INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
            INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
            WHERE SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'
            UNION

          SELECT
            'Harta bergerak' as kode,
            TGL_TRANSAKSI as tgl,
            CONCAT(
              CASE
                WHEN KODE_JENIS LIKE '%1%' THEN 'Perabotan Rumah Tangga'
                WHEN KODE_JENIS LIKE '%2%' THEN 'Barang Elektronik'
                WHEN KODE_JENIS LIKE '%3%' THEN 'Perhiasan & Logam / Batu Mulia'
                WHEN KODE_JENIS LIKE '%4%' THEN 'Persediaan'
                WHEN KODE_JENIS LIKE '%5%' THEN 'Harta Bergerak Lainnya'
              END,
              ' bernama ',A.NAMA,' , Atas nama ',ATAS_NAMA,' dengan jumlah ',JUMLAH,' ',SATUAN) as uraian,
            NILAI_PELEPASAN as nilai,
            D.ASAL_USUL as jenis,
            B.ALAMAT as almat,
            B.NAMA as nama

            from T_LHKPN_HARTA_BERGERAK_LAIN A
            INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN B ON A.ID=B.ID_HARTA
            INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
            INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
            WHERE SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'
            UNION

          SELECT
            'Surat Berharga' as kode,
            TGL_TRANSAKSI as tgl,
            CONCAT(
              CASE
                WHEN KODE_JENIS LIKE '%1%' THEN 'Penyertaan Modal pada Badan Hukum'
                WHEN KODE_JENIS LIKE '%2%' THEN 'Investasi'
              END,
              ', Atas nama ',ATAS_NAMA,' berupa surat ',NAMA_SURAT_BERHARGA,' dengan jumlah ',JUMLAH,' ',SATUAN) as uraian,
            NILAI_PELEPASAN as nilai,
            D.ASAL_USUL as jenis,
            B.ALAMAT as almat,
            B.NAMA as nama

            from T_LHKPN_HARTA_SURAT_BERHARGA A
            INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA B ON A.ID=B.ID_HARTA
            INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
            INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
            WHERE SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'
            UNION

          SELECT
            'Kas / Setara Kas' as kode,
            '' as tgl,
            CONCAT('KAS berupa ',
              CASE
                WHEN KODE_JENIS LIKE '%1%' THEN 'Uang Tunai'
                WHEN KODE_JENIS LIKE '%2%' THEN 'Deposite'
                WHEN KODE_JENIS LIKE '%3%' THEN 'Giro'
                WHEN KODE_JENIS LIKE '%4%' THEN 'Tabungan'
                WHEN KODE_JENIS LIKE '%5%' THEN 'Lainnya'
              END,
              ', Atas nama ',ATAS_NAMA_REKENING,' pada bank ',NAMA_BANK,' dengan nomor rekening ',NOMOR_REKENING) as uraian,
            NILAI_PELEPASAN as nilai,
            D.ASAL_USUL as jenis,
            B.ALAMAT as almat,
            B.NAMA as nama

            from T_LHKPN_HARTA_KAS A
            INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_KAS B ON A.ID=B.ID_HARTA
            INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
            INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
            WHERE SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'
            UNION

          SELECT
            'Harta Lainnya' as kode,
            TGL_TRANSAKSI as tgl,
            CONCAT('Harta lain berupa ',
              CASE
                WHEN KODE_JENIS LIKE '%1%' THEN 'Piutang'
                WHEN KODE_JENIS LIKE '%2%' THEN 'Kerjasama Usaha yang Tidak Berbadan Hukum'
                WHEN KODE_JENIS LIKE '%3%' THEN 'Hak Kekayaan Intelektual'
                WHEN KODE_JENIS LIKE '%4%' THEN 'Sewa Jangaka Panjang Dibayar Dimuka'
                WHEN KODE_JENIS LIKE '%5%' THEN 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan'
              END,
            ' dengan nama harta ',A.NAMA,' atas nama ',ATAS_NAMA) as uraian,
            NILAI_PELEPASAN as nilai,
            D.ASAL_USUL as jenis,
            B.ALAMAT as almat,
            B.NAMA as nama

            from T_LHKPN_HARTA_LAINNYA A
            INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA B ON A.ID=B.ID_HARTA
            INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
            INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
            WHERE SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn' AND B.NAMA LIKE '%$where%'")->result();

        return $result;
    }

    /**
     * Info pn
     *
     * @return html detail pn yg diakses dari lhkpn
     */
    public function getInfoPn($id) {
        $this->load->model('mglobal');
        $tmp = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id], 'FOTO, NAMA, NIK, JNS_KEL, TEMPAT_LAHIR, TGL_LAHIR, NPWP, ALAMAT_TINGGAL, EMAIL, NO_HP')[0];

        $data['data'] = $tmp;
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_detailpn', $data);
    }

    public function periksaNaskah($id) {
        $this->load->model('mglobal');
        $data = [
            'data' => $this->mglobal->get_data_all('T_LHKPN', NULL, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'")[0],
            'id' => $id
        ];

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_periksa_naskah', $data);
    }

    public function catatanPerbaikan() {
        $msg = $this->input->post('msg');
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        if ($status != '1') {
            $data = [
                'CATATAN_PERBAIKAN_NASKAH' => $msg,
            ];
        }

        if ($status != '') {
            $data['STATUS_PERBAIKAN_NASKAH'] = $status;
        }

        $this->load->model('mglobal');
        $update = $this->mglobal->update('T_LHKPN', $data, NULL, "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");

        echo $update;
    }

    public function save() {
        if ($this->input->post('act') == 'doinsert') {

            $maxcounter_bast_cs = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, NULL, "max(COUNTER) as maxcounter")[0]->maxcounter;
            $nobap = $maxcounter_bast_cs + 1;

            $tanggal = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL_BA_PENGUMUMAN'))));
            $tanggalpnri = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL_PNRI'))));

            $data = [
                'COUNTER' => $nobap,
                'NOMOR_BAP' => $this->input->post('NOBAP'),
                'TGL_BA_PENGUMUMAN' => $tanggal,
                'NOMOR_PNRI' => $this->input->post('NOPNRI'),
                'TGL_PNRI' => $tanggalpnri,
                'KETERANGAN' => $this->input->post('KETERANGAN')
            ];

            $result = $this->mglobal->insert('T_BA_PENGUMUMAN', $data);

            $ID_LHKPNS = explode(',', $this->input->post('id'));

            $id = $this->db->insert_id();

            for ($i = 0; $i < count($ID_LHKPNS); $i++) {
                $data2 = [
                    'ID_BAP' => $id,
                    'ID_LHKPN' => $ID_LHKPNS[$i]
                ];

                $result2 = $this->mglobal->insert('R_BA_PENGUMUMAN', $data2);
            }

            if ($result) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }

    public function cetakrekap($ID_BAP) {
        $head = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, NULL, '*', "SUBSTRING(md5(T_BA_PENGUMUMAN.ID_BAP), 6, 8) = '$ID_BAP'")[0];
        $id = $head->ID_BAP;
        $items = $this->mglobal->get_data_all('R_BA_PENGUMUMAN', [
            ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = R_BA_PENGUMUMAN.ID_LHKPN'],
            ['table' => 'T_LHKPN_HUTANG', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN'],
            ['table' => 'T_LHKPN_HARTA_KAS', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HARTA_KAS.ID_LHKPN'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN']
                ], NULL, '*', "SUBSTRING(md5(R_BA_PENGUMUMAN.ID_BAP), 6, 8) = '$ID_BAP'", ["T_LHKPN.TGL_LAPOR", "ASC"]);

        $ID_LHKPN = $items->ID_LHKPN;
        // $this->base_url = site_url('portal/review_harta/jumlah/1');
        $total_kekayaan = "'.base_url().'index.php/portal/review_harta/jumlah/'.$ID_LHKPN.'";
        $total = file_get_contents($total_kekayaan);


        $bidangpn = $this->mglobal->get_data_all(
                        'R_BA_PENGUMUMAN', [
                    ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'jbt.LEMBAGA   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'jbt.UNIT_KERJA   =  unke.UK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
//                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                        ], NULL, 'bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA,COUNT(bdg.BDG_NAMA) AS jum', "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1'"
                )[0];
//          display($this->db->last_query());
        $join = [
            ['table' => 'T_PN', 'on' => 'VALUE=ID_PN']
        ];


        // echo$total_rows;
        $data = [
            'head' => $head,
            'items' => $items,
            'bidangpn' => $bidangpn,
            'total' => $total,
            'direkturpp' => $this->mglobal->get_data_all('CORE_SETTING', $join, ['OWNER' => 'app.lhkpn', 'SETTING' => 'direkturpp'], 'NAMA')[0],
            'direkturpencegahan' => $this->mglobal->get_data_all('CORE_SETTING', $join, ['OWNER' => 'app.lhkpn', 'SETTING' => 'direkturpencegahan'], 'NAMA')[0]
        ];
//        var_dump($data);exit;

        $html = $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data, TRUE);

        try {
            include_once APPPATH . 'third_party/TCPDF/tcpdf.php';
            $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetFont('dejavusans', '', 9);
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->lastPage();
            $pdf->Output('ikhtisar.pdf', 'I');
        } catch (Exception $e) {

        }

//        $this->load->library('pdf');
//        $pdf = $this->pdf->loadP('c', 'A4');
//        $pdf->WriteHTML($html); // write the HTML into the PDF
//        $pdf->Output();
    }

    public function cetakdaftar($ID_BAP) {
        $head = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, NULL, '*', "T_BA_PENGUMUMAN.ID_BAP = '$ID_BAP'")[0];
        $id = $head->ID_BAP;
        $items = $this->mglobal->get_data_all('R_BA_PENGUMUMAN', [
            ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = R_BA_PENGUMUMAN.ID_LHKPN'],
            ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
            ['table' => 'T_LHKPN_HUTANG', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN']
                ], NULL, 'jbt.DESKRIPSI_JABATAN, T_PN.NHK,T_PN.NAMA, SUM(T_LHKPN_HUTANG.SALDO_HUTANG) AS jumhut, T_LHKPN.TGL_LAPOR', "R_BA_PENGUMUMAN.ID_BAP = '$ID_BAP'", ["T_LHKPN.TGL_LAPOR", "ASC"]);

        $join = [
            ['table' => 'T_PN', 'on' => 'VALUE=ID_PN']
        ];

        $c_bidang = $this->mglobal->get_data_all(
                        'R_BA_PENGUMUMAN', [
                    ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
//                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                        ], NULL, 'bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA,COUNT(bdg.BDG_NAMA) AS jum', "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1'"
                )[0];

        $bidangpn = $this->mglobal->get_data_all(
                'R_BA_PENGUMUMAN', [
            ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
            ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
            ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
            ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
            ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
//            ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                ], NULL, "T_PN.ID_PN, mj.NAMA_JABATAN,inst.INST_NAMA, T_PN.NHK,T_PN.NAMA, (SELECT SUM(T_LHKPN_HUTANG.SALDO_HUTANG) FROM T_LHKPN_HUTANG WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') AS jumhut, T_LHKPN.TGL_LAPOR, T_LHKPN.STATUS, bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA, ba.NOMOR_BAP, ba.NOMOR_PNRI, (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
(SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
(SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
(SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
(SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
(SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6 ", "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1'", ['inst.INST_NAMA', 'ASC'], 0, NULL, NULL
        );
//        echo $this->db->last_query();exit;
        $join = [
            ['table' => 'T_PN', 'on' => 'VALUE=ID_PN']
        ];

//        $data = [
//            'head' => $head,
//            'items' => $items,
//            'bidangpn' => $bidangpn,
//            'c_bidang' => $c_bidang,
//            'total' => $total,
//            'mbidang' => $this->mglobal->get_data_all('m_bidang'),
//            'direkturpp' => $this->mglobal->get_data_all('CORE_SETTING', $join, ['OWNER' => 'app.lhkpn', 'SETTING' => 'direkturpp'], 'NAMA')[0],
//            'direkturpencegahan' => $this->mglobal->get_data_all('CORE_SETTING', $join, ['OWNER' => 'app.lhkpn', 'SETTING' => 'direkturpencegahan'], 'NAMA')[0]
//        ];

//        $t_bid = array();
//        $t_ins = array();
//        foreach ($bidangpn as $rows) {
//            $t_bid[] = $rows->BDG_NAMA;
//            $t_ins[] = $rows->INST_NAMA;
//        }
//        $r_bid = array_unique($t_bid);
//        $r_ins = array_unique($t_ins);

        $array_message = array_filter((array) $bidangpn);
//        foreach ($array_message as $key => $rows){
//            if ($rows->NHK != NULL) {
//                $max_nhk = $bidangpn->NHK;
//            } else {
//                $max_nhk = $this->mglobal->getMaxNHK('t_pn', 'NHK');
//                    $max_nhk = $max_nhk != NULL ? $max_nhk + 1 : 1;
//                    $data_pn = array(
//                        'NHK' => $max_nhk
//                    );
//                    $update_pn = $this->mglobal->update('t_pn', $data_pn, NULL, "ID_PN = $rows->ID_PN");
//            }
//        }


        $nomor_bap = str_replace('/', '-', $head->NOMOR_BAP);

        $this->load->library('lwphpword/lwphpword', array(
            "base_path" => APPPATH . "../uploads/lembar_persetujuan/",
            "base_url" => base_url() . "../uploads/lembar_persetujuan/",
            "base_root" => base_url(),
        ));

        $output_filename = "Lembar_Persetujuan_" . $nomor_bap . ".docx";
        $template_file = "../file/template/DraftLembarPersetujuan.docx";

        $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

        $this->lwphpword->save_path = APPPATH . "../uploads/lembar_persetujuan/";

//        foreach ($r_bid as $bid) {
//            $this->lwphpword->set_value("bidang", $bid);
//            foreach ($r_ins as $ins) {
//                $this->lwphpword->set_value("inst_nama", $ins);
//                foreach ($bidangpn as $data) {
//                    if ($bid == $data->BDG_NAMA) {
//                        if ($ins == $data->INST_NAMA) {
        $this->lwphpword->set_value("jml_eksekutif", $c_bidang->BDG_KODE == "E" ? $c_bidang->jum : "-");
        $this->lwphpword->set_value("jml_legislatif", $c_bidang->BDG_KODE == "L" ? $c_bidang->jum : "-");
        $this->lwphpword->set_value("jml_yudikatif", $c_bidang->BDG_KODE == "Y" ? $c_bidang->jum : "-");
        $this->lwphpword->set_value("jml_bumnd", $c_bidang->BDG_KODE == "B" ? $c_bidang->jum : "-");
        $this->lwphpword->set_value("jumlah_pn", count($bidangpn));
        $this->lwphpword->set_value("date_now", tgl_format(date('d-F-Y')));
        /**
         * formatnya => Nomor Berita Acara/LHK.03/Nomor Pengumuman/Bulan Berjalan/Tahun Berjalan (01/LHK.03/111/10/2017
         */
        $nomor = $bidangpn[0]->NOMOR_BAP . '/LHK.03/' . $bidangpn[0]->NOMOR_PNRI . '/' . date('m') . '/' . date('Y');
        $this->lwphpword->set_value("nomor", $nomor);
        $this->set_data_pn($bidangpn, $this->lwphpword);
//                        }
//                    }
//                }
//            }
//        }
        $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE);
//        $save_document_success = $this->lwphpword->save_document(1, '', FALSE, $output_filename);
        $this->lwphpword->download($save_document_success->document_path, $output_filename);
        unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
    }

    public function formperbaikan($ID_LHKPN) {
        $this->load->library('session');
        modules::run('efill/lhkpn/entry', $ID_LHKPN, 'perbaikan');
        echo 'Perbaikan';
    }

    public function bap_detail($param) {
        $join = [
            ['table' => 'T_BA_PENGUMUMAN', 'on' => 'R_BA_PENGUMUMAN.ID_BAP = T_BA_PENGUMUMAN.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'R_BA_PENGUMUMAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
            ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN AND T_LHKPN_JABATAN.IS_PRIMARY = "1"'],
            ['table' => 'T_LHKPN_DATA_PRIBADI', 'on' => 'T_LHKPN_DATA_PRIBADI.ID_LHKPN = T_LHKPN.ID_LHKPN'],
            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
            ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
            ['table' => 'M_ESELON', 'on' => 'M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON'],
            ['table' => 'T_STATUS_AKHIR_JABAT', 'on' => 'T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT'],
                /* ['table' => '(
                  select  ID_LHKPN AS ID_LHKPN_DIJABATAN,
                  group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN,
                  group_concat(
                  CONCAT(
                  IFNULL(T_LHKPN_JABATAN.ID,""),":58:",
                  IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                  IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                  IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":58:",
                  IFNULL(M_JABATAN.NAMA_JABATAN,""),
                  "(", IFNULL(M_ESELON.ESELON,""), ") - ",
                  IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ",
                  IFNULL(M_INST_SATKER.INST_AKRONIM,""),":58:", IFNULL(T_LHKPN_JABATAN.IS_PRIMARY,"")
                  )
                  ) as NAMA_JABATAN
                  from T_LHKPN_JABATAN
                  LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                  LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA
                  LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA
                  LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
                  LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT

                  GROUP BY T_LHKPN_JABATAN.ID_LHKPN
                  ) JABATAN', 'on' => 'JABATAN.ID_LHKPN_DIJABATAN = T_LHKPN.ID_LHKPN', 'join' => 'LEFT'] */
        ];
//        $this->data['lhkpn_jbtn'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', [['table' => 'M_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN']], NULL, '*', "IS_PRIMARY = '1'");
        $getData = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, ["SUBSTRING(md5(T_BA_PENGUMUMAN.ID_BAP), 6, 8) =" => $param])[0];
        $data['mode'] = 'viewBap';
        $data['ID_BAP'] = $getData->ID_BAP;
        $data['item'] = $this->mglobal->get_data_all('R_BA_PENGUMUMAN', $join, ['R_BA_PENGUMUMAN.ID_BAP' => $getData->ID_BAP], '*,T_LHKPN.ID_LHKPN AS ID_LHKPN_DIJABATAN, M_JABATAN.NAMA_JABATAN,t_lhkpn.STATUS,R_BA_PENGUMUMAN.ID');

//         display ($data['item']);
        @$data['agenda'] = date('Y', strtotime($data['item'][0]->TGL_LAPOR)) . '/' . ($data['item'][0]->JENIS_LAPORAN == '4' ? 'R' : ($data['item'][0]->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $data['item'][0]->NIK . '/' . $data['item'][0]->ID_LHKPN;

        $this->load->view('announ/announ_bap_detail', $data);
    }

    public function pdf_detail($param) {
        $join = [
            ['table' => 'T_BA_PENGUMUMAN', 'on' => 'R_BA_PENGUMUMAN.ID_BAP = T_BA_PENGUMUMAN.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'R_BA_PENGUMUMAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
            ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN AND T_LHKPN_JABATAN.IS_PRIMARY = "1"'],
            ['table' => 'T_LHKPN_DATA_PRIBADI', 'on' => 'T_LHKPN_DATA_PRIBADI.ID_LHKPN = T_LHKPN.ID_LHKPN'],
            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
            ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA'],
            ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
            ['table' => 'M_ESELON', 'on' => 'M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON'],
            ['table' => 'T_STATUS_AKHIR_JABAT', 'on' => 'T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT'],
                /* ['table' => '(
                  select  ID_LHKPN AS ID_LHKPN_DIJABATAN,
                  group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN,
                  group_concat(
                  CONCAT(
                  IFNULL(T_LHKPN_JABATAN.ID,""),":58:",
                  IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                  IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                  IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":58:",
                  IFNULL(M_JABATAN.NAMA_JABATAN,""),
                  "(", IFNULL(M_ESELON.ESELON,""), ") - ",
                  IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ",
                  IFNULL(M_INST_SATKER.INST_AKRONIM,""),":58:", IFNULL(T_LHKPN_JABATAN.IS_PRIMARY,"")
                  )
                  ) as NAMA_JABATAN
                  from T_LHKPN_JABATAN
                  LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                  LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA
                  LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA
                  LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
                  LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT

                  GROUP BY T_LHKPN_JABATAN.ID_LHKPN
                  ) JABATAN', 'on' => 'JABATAN.ID_LHKPN_DIJABATAN = T_LHKPN.ID_LHKPN', 'join' => 'LEFT'] */
        ];
//        $this->data['lhkpn_jbtn'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', [['table' => 'M_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN']], NULL, '*', "IS_PRIMARY = '1'");
        $getData = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, ["SUBSTRING(md5(T_BA_PENGUMUMAN.ID_BAP), 6, 8) =" => $param])[0];
        $data['mode'] = 'viewBap';
        $data['ID_BAP'] = $getData->ID_BAP;
        $data['item'] = $this->mglobal->get_data_all('R_BA_PENGUMUMAN', $join, ['R_BA_PENGUMUMAN.ID_BAP' => $getData->ID_BAP], '*,T_LHKPN.ID_LHKPN AS ID_LHKPN_DIJABATAN, M_JABATAN.NAMA_JABATAN,t_lhkpn.STATUS');

//        display($this->db->last_query());
        @$data['agenda'] = date('Y', strtotime($data['item'][0]->TGL_LAPOR)) . '/' . ($data['item'][0]->JENIS_LAPORAN == '4' ? 'R' : ($data['item'][0]->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $data['item'][0]->NIK . '/' . $data['item'][0]->ID_LHKPN;

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    public function showFormBap($tipe, $param) {
        if ($tipe == '1') {
            $getData = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, ["SUBSTRING(md5(ID_BAP), 6, 8) =" => $param])[0];
            $data['ID_BAP'] = $getData->ID_BAP;
            $data['IDBAP'] = $param;
            $data['mode'] = 'addBap';
            $joinn = [
                ['table' => 'R_BA_PENGUMUMAN', 'on' => 'T_LHKPN.ID_LHKPN = R_BA_PENGUMUMAN.ID_LHKPN', 'join' => 'LEFT'],
                ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN', 'join' => 'LEFT']
            ];
            $data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', $joinn, ['T_LHKPN.STATUS_PERBAIKAN_NASKAH' => '1', 'T_LHKPN.STATUS' => '3'], '*,T_LHKPN.ID_LHKPN AS IDLHKPN', 'R_BA_PENGUMUMAN.ID_BAP IS NULL');
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_bap_detail', $data);
        } else {
            $join = [
                ['table' => 'T_BA_PENGUMUMAN', 'on' => 'R_BA_PENGUMUMAN.ID_BAP = T_BA_PENGUMUMAN.ID_BAP'],
                ['table' => 'T_LHKPN', 'on' => 'R_BA_PENGUMUMAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
                ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN']
            ];
            $data['mode'] = 'deleteBap';
            $data['item'] = $this->mglobal->get_data_all('R_BA_PENGUMUMAN', $join, ['SUBSTRING(md5(R_BA_PENGUMUMAN.ID), 6, 8) =' => $param])[0];
//            display($this->db->last_query());exit;
            $data['agenda'] = date('Y', strtotime($data['item']->tgl_kirim_final)) . '/' . ($data['item']->JENIS_LAPORAN == '4' ? 'R' : ($data['item']->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $data['item']->NIK . '/' . $data['item']->ID_LHKPN;
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_bap_detail', $data);
        }
    }

    public function saveBap() {
        $tipe = $this->input->post('act');
        if ($tipe == 'doinsert') {
            $post = $this->input->post();
            for ($i = 0; $i < count($post['cekIDLhkpn']); $i++) {
                $data['ID_BAP'] = $post['ID_BAP'];
                $data['ID_LHKPN'] = $post['cekIDLhkpn'][$i];

                $tambah = $this->mglobal->insert('R_BA_PENGUMUMAN', $data);
            }
            if ($tambah) {
                echo '1';
            } else {
                echo '0';
            }
        } else {
            $ID = $this->input->post('ID');
            $delete = $this->mglobal->delete("R_BA_PENGUMUMAN", "1=1", "SUBSTRING(md5(ID), 6, 8) = '$ID'");
//            $getData = $this->mglobal->get_data_all('R_BA_PENGUMUMAN', NULL, "SUBSTRING(md5(ID), 6, 8) = '$ID'")[0];
//            $data_ba = array(
//                'STATUS' => '1'
//            );
//            $update = $this->mglobal->update('T_LHKPN', $data_ba, NULL, "ID_LHKPN = $getData->ID_LHKPN");

            if ($delete) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }

    public function lihat_cttnprbaikan($param) {
        $getData = $this->mglobal->get_data_all('T_LHKPN', NULL, ["substr(md5(ID_LHKPN),6,8) =" => $param])[0];
        echo '<i>"Berikut Beberapa Catatan Untuk Perbaikan Naskah"</i> <br><br> <textarea readonly rows="5" cols="45">' . html_entity_decode(strip_tags($getData->CATATAN_PERBAIKAN_NASKAH)) . '</textarea>';
        // $this->load->view('View File', $data, FALSE);
    }

    public function CreatePdfFinalALL($idBap) {
        $databap = $this->mglobal->get_data_all('r_ba_pengumuman', NULL, "ID_BAP = $idBap");
        $this->db->trans_begin();
        foreach ($databap as $row) {
            if($row->is_kirim_email == NULL) {
                $this->CreatePdfFinal($row->ID_LHKPN);
                flush();
                ob_flush();
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo 0;
        } else {
            $this->db->trans_commit();
            echo 1;
        }
    }

    function harta() {
        $data = array();
        $data[1] = 't_lhkpn_harta_tidak_bergerak';
        $data[2] = 't_lhkpn_harta_bergerak';
        $data[3] = 't_lhkpn_harta_bergerak_lain';
        $data[4] = 't_lhkpn_harta_surat_berharga';
        $data[5] = 't_lhkpn_harta_kas';
        $data[6] = 't_lhkpn_harta_lainnya';
        $data[7] = 't_lhkpn_hutang';
        return $data;
    }

    protected function __is_cetak_var_not_blank($val, $default_value = "", $bool = FALSE) {
        $val = trim($val);
        if ($val != "" && $val != NULL && $val != FALSE) {
            return $bool ? TRUE : $val;
        }
        return $bool ? FALSE : $default_value;
    }

    private function set_data_pn($bidangpn, $obj) {
        $array_message = array_filter((array) $bidangpn);

        $jumlah_data = count($array_message);

        if ($jumlah_data < 0) {
            $obj->clone_row('no', 0);
        } else {
            $obj->clone_row('no', $jumlah_data);
            $i = 1;
            foreach ($array_message as $key => $row) {

                $template_string_no = 'no#' . ($key + 1);

                $template_string_nhk = 'NHK#' . ($key + 1);
                $template_string_nama = 'nama_jabatan#' . ($key + 1);
                $template_string_tgl_lapor = 'tgl_lapor#' . ($key + 1);
                $template_string_t_htb = 't_htb#' . ($key + 1);
                $template_string_t_ht = 't_ht#' . ($key + 1);
                $template_string_t_hbl = 't_hbl#' . ($key + 1);
                $template_string_t_sb = 't_sb#' . ($key + 1);
                $template_string_t_kas = 't_kas#' . ($key + 1);
                $template_string_t_lainnya = 't_lainnya#' . ($key + 1);
                $template_string_t_harta = 't_harta#' . ($key + 1);
                $template_string_t_hutang = 'htg#' . ($key + 1);
                $template_string_t_nhk = 't_nhk#' . ($key + 1);

                $obj->set_value($template_string_no, ($key + 1));

                $obj->set_value($template_string_nhk, $this->__is_cetak_var_not_blank($row->NHK, "-"));
                $obj->set_value($template_string_nama, $this->__is_cetak_var_not_blank($row->NAMA . ' / ' . $row->NAMA_JABATAN . ' - ' . $row->UK_NAMA . ' - ' . $row->INST_NAMA, "-"));
                $obj->set_value($template_string_tgl_lapor, $this->__is_cetak_var_not_blank(tgl_format($row->TGL_LAPOR), "-"));
                $obj->set_value($template_string_t_htb, $this->__is_cetak_var_not_blank(number_rupiah($row->T1), "-"));
                $obj->set_value($template_string_t_ht, $this->__is_cetak_var_not_blank(number_rupiah($row->T6), "-"));
                $obj->set_value($template_string_t_hbl, $this->__is_cetak_var_not_blank(number_rupiah($row->T5), "-"));
                $obj->set_value($template_string_t_sb, $this->__is_cetak_var_not_blank(number_rupiah($row->T2), "-"));
                $obj->set_value($template_string_t_kas, $this->__is_cetak_var_not_blank(number_rupiah($row->T4), "-"));
                $obj->set_value($template_string_t_lainnya, $this->__is_cetak_var_not_blank(number_rupiah($row->T3), "-"));
                $obj->set_value($template_string_t_harta, $this->__is_cetak_var_not_blank(number_rupiah($row->T1 + $row->T2 + $row->T3 + $row->T4 + $row->T5 + $row->T6), "-"));
                $obj->set_value($template_string_t_hutang, $this->__is_cetak_var_not_blank(number_rupiah($row->jumhut), "-"));
                $obj->set_value($template_string_t_nhk, $this->__is_cetak_var_not_blank(number_rupiah(($row->T1 + $row->T2 + $row->T3 + $row->T4 + $row->T5 + $row->T6) - $row->jumhut), "-"));
                $i++;
            }
        }
        return FALSE;
    }

    private function set_data_harta_bergerak($obj_dhb, $obj) {
        $array_message = array_filter((array) $obj_dhb);

        $jumlah_data = count($array_message);

        if ($jumlah_data < 0) {
            $obj->clone_row('no_hb', 0);
        } else {
            $obj->clone_row('no_hb', $jumlah_data);
            $i = 1;
            foreach ($array_message as $key => $row) {

                $template_string_no_hb = 'no_hb#' . ($key + 1);

                $template_string_hb = 'DHB#' . $i;

                $obj->set_value($template_string_no_hb, ($key + 1));
                $obj->set_value($template_string_hb, $row);
                $i++;
            }
        }
        return FALSE;
    }

    private function set_data_harta_tidak_bergerak($obj_dhtb, $obj) {
        $array_message = array_filter((array) $obj_dhtb);

        $jumlah_data = count($array_message);

        if ($jumlah_data < 0) {
            $obj->clone_row('no_htb', 0);
        } else {
            $obj->clone_row('no_htb', $jumlah_data);
            $i = 1;
            foreach ($array_message as $key => $row) {

                $template_string_no_thb = 'no_htb#' . ($key + 1);

                $template_string_thb = 'DHTB#' . $i;

                $obj->set_value($template_string_no_thb, ($key + 1));
                $obj->set_value($template_string_thb, $row);
                $i++;
            }
        }
        return FALSE;
    }

    public function CreatePdfFinal($id_lhkpn, $id_bap = NULL) {
        $datapn = $this->mglobal->get_data_all(
                        'R_BA_PENGUMUMAN', [
                    ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
                    ['table' => 't_lhkpn_data_pribadi', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 't_lhkpn_data_pribadi.ID_LHKPN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_JABATAN m_jbt', 'on' => 'm_jbt.ID_JABATAN   =  jbt.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'm_jbt.INST_SATKERKD   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'm_jbt.UK_ID   =  unke.UK_ID'],
                    ['table' => 'M_SUB_UNIT_KERJA subunke', 'on' => 'm_jbt.SUK_ID   =  subunke.SUK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
                    ['table' => 'T_LHKPN_HUTANG', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN AND T_LHKPN_HUTANG.IS_ACTIVE = 1'],
                    ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
                    ['table' => 'T_USER', 'on' => 'T_USER.USERNAME = T_PN.NIK'],
                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                        ], NULL, "t_lhkpn_data_pribadi.*, jbt.ALAMAT_KANTOR, jbt.DESKRIPSI_JABATAN, m_jbt.NAMA_JABATAN, T_PN.NIK, T_PN.ID_PN,  inst.INST_NAMA, T_PN.TGL_LAHIR, T_PN.NHK,T_PN.NAMA, SUM(T_LHKPN_HUTANG.SALDO_HUTANG) AS jumhut, T_LHKPN.TGL_LAPOR, T_LHKPN.tgl_kirim_final, T_LHKPN.JENIS_LAPORAN, T_LHKPN.STATUS, bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA, ba.NOMOR_PNRI, ba.TGL_PNRI, ba.NOMOR_BAP, ba.TGL_BA_PENGUMUMAN, T_USER.ID_USER, IF (T_LHKPN.JENIS_LAPORAN = '4', 'Periodik', IF (T_LHKPN.JENIS_LAPORAN = '5', 'Klarifikasi', 'Khusus')) AS JENIS, T_USER.EMAIL, subunke.SUK_NAMA, T_LHKPN.TGL_KLARIFIKASI, R_BA_PENGUMUMAN.STATUS_CETAK_PENGUMUMAN_PDF, R_BA_PENGUMUMAN.is_kirim_email, 
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
                        (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6,
                        (SELECT SUM(SALDO_HUTANG) FROM t_lhkpn_hutang WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') T7 ", "T_LHKPN.ID_LHKPN = '$id_lhkpn' AND jbt.IS_PRIMARY = '1'", NULL, 0, NULL, "T_LHKPN.ID_LHKPN"
                )[0];
        if ($datapn->TGL_LAPOR == '1970-01-01' || $datapn->TGL_LAPOR == '' || $datapn->TGL_LAPOR == '-') {
            $tgl_lapor_new = $datapn->tgl_kirim_final;
        }
        else{
            $tgl_lapor_new = $datapn->TGL_LAPOR;
        }
        $this->data['dt_harta_tidak_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_tidak_bergerak", [
            ['table' => 'm_negara ', 'on' => 'm_negara.ID   = ' . 't_lhkpn_harta_tidak_bergerak.ID_NEGARA'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_tidak_bergerak.ASAL_USUL'],
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   IN ' . '(t_lhkpn_harta_tidak_bergerak.PEMANFAATAN)']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_tidak_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_tidak_bergerak.IS_ACTIVE = '1'", "*, GROUP_CONCAT(DISTINCT m_pemanfaatan.PEMANFAATAN) as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_tidak_bergerak.ID");

        $this->data['dt_harta_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_bergerak", [
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   = t_lhkpn_harta_bergerak.PEMANFAATAN'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_bergerak.ASAL_USUL'],
            ['table' => 'm_jenis_harta ', 'on' => 'm_jenis_harta.ID_JENIS_HARTA   = t_lhkpn_harta_bergerak.KODE_JENIS']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_bergerak.IS_ACTIVE = '1'", "*, m_pemanfaatan.PEMANFAATAN as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_bergerak.ID");

        $this->data['datapn'] = $datapn;
        $th = date('Y');

        $arr_dhb = array();
        $arr_dhtb = array();
        foreach ($this->data['dt_harta_bergerak'] as $data) {
            $arr_dhb[] = $data->NAMA . ', ' . $data->MEREK . ' ' . $data->MODEL . ' Tahun ' . $data->TAHUN_PEMBUATAN . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
        }
        foreach ($this->data['dt_harta_tidak_bergerak'] as $data) {
            $tmp = $data->NEGARA == 2 ? $data->JALAN . ', ' . $data->NAMA_NEGARA : $data->KAB_KOT;
            if ($data->LUAS_TANAH == NULL || $data->LUAS_TANAH == '') {
                $luas_tanah = '-';
            } else {
                $luas_tanah = $data->LUAS_TANAH;
            }
            if ($data->LUAS_BANGUNAN == NULL || $data->LUAS_BANGUNAN == '') {
                $luas_bangunan = '-';
            } else {
                $luas_bangunan = $data->LUAS_BANGUNAN;
            }
            if ($data->LUAS_BANGUNAN !== "0" && $data->LUAS_TANAH !== "0") {
                $arr_dhtb[] = 'Tanah dan Bangunan Seluas ' . $luas_tanah . ' m2/' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else if ($data->LUAS_TANAH !== "0" && $data->LUAS_BANGUNAN == "0") {
                $arr_dhtb[] = 'Tanah Seluas ' . $luas_tanah . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else {
                $arr_dhtb[] = 'Bangunan Seluas ' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            }
        }

        $arr_all_data = array(
            'nama' => $datapn->NAMA,
            'jabatan' => $datapn->DESKRIPSI_JABATAN,
            'nhk' => $datapn->NHK,
            'tempat_tgl_lahir' => $datapn->TGL_LAHIR,
            'alamat_kantor' => $datapn->ALAMAT_KANTOR,
            'tgl_pelaporan' => $datapn->TGL_LAPOR,
            'nilai_hutang' => $datapn->jumhut,
            'nilai_hl' => $datapn->T3,
            'nilai_kas' => $datapn->T4,
            'nilai_surga' => $datapn->T2,
            'hbl' => $datapn->T5,
            'hb' => $arr_dhb,
            'htb' => $arr_dhtb,
        );

        $obj_dhb = (object) $arr_dhb;
        $obj_dhtb = (object) $arr_dhtb;
//        var_dump('<pre>');
//        $json_pn = json_encode($arr_all_data);
//        $url = 'http://localhost/testing/?pengumuman=' . $json_pn;
//        CallURLPage($url);

        $this->db->trans_begin();

        if ($datapn->STATUS == '3' || $datapn->STATUS == '4')
            $sts = '4';
        else if ($datapn->STATUS == '5' || $datapn->STATUS == '6')
            $sts = '6';

        $data_lhkpn = array('STATUS' => $sts);
        $update_lhkpn = $this->mglobal->update('T_LHKPN', $data_lhkpn, NULL, "T_LHKPN.ID_LHKPN = '$id_lhkpn'");


        if ($datapn->NHK != NULL) {
            $max_nhk = $datapn->NHK;
        } else {
            $max_nhk = $this->mglobal->getMaxNHK('t_pn', 'NHK');
            $max_nhk = $max_nhk != NULL ? $max_nhk + 1 : 1;
        }

        $data_ba = array(
            'STATUS_CETAK_PENGUMUMAN_PDF' => 1
        );


        $this->data['nhk'] = $max_nhk;
        $data_pn = array(
            'NHK' => $max_nhk
        );
        $update = $this->mglobal->update('r_ba_pengumuman', $data_ba, NULL, "ID_LHKPN = '$id_lhkpn'");
        $update_pn = $this->mglobal->update('t_pn', $data_pn, NULL, "ID_PN = $datapn->ID_PN");

        $message_pengumuman_pn = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   Di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <br>
                        <table>
                             <tr>
                                 <td>
                                    <ol>
                                        <li value="1">Undang-Undang Nomor 28 Tahun 1999 tentang Penyelenggaraan Negara yang Bersih dan Bebas dari Korupsi, Kolusi dan Nepotisme;</li>
                                        <li>Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi, sebagaimana diubah dengan Undang-Undang Nomor 10 Tahun 2015 tentang Penetapan Peraturan Pemerintah Pengganti Undang-Undang Nomor 1 Tahun 2015 tentang Perubahan atas Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Korupsi menjadi Undang-Undang;</li>
                                        <li>Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata Cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara sebagaimana diubah dengan Peraturan Komisi Pemberantasan Korupsi Nomor 02 Tahun 2020 tentang Perubahan atas Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata Cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara.</li>
                                    </ol>
                                 </td>
                            </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Berdasarkan ketentuan di atas, setiap Wajib LHKPN berkewajiban untuk melaporkan dan mengumumkan harta kekayaannya sebelum dan setelah menjabat. Bersama ini disampaikan bahwa kami telah mengumumkan harta kekayaan Saudara dalam Lembar Pengumuman (terlampir) sebagai berikut:
                                 </td>
                            </tr>
                        </table>
                        <br>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="40%" valign="top"><b>NIK</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NIK . '</td>
                                            </tr>
                                             <tr>
                                                <td width="40%" valign="top"><b>Jenis Laporan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->JENIS . '</td>
                                            </tr>
                                            <tr>
                                                <td width="40%" valign="top"><b>Tanggal Kirim</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->tgl_kirim_final . '</td>
                                            </tr>
                                            <tr>
                                                <td width="40%" valign="top"><b>Nomor Harta Kekayaaan (NHK)</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NHK . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                            <table>
                                             <tr>
                                                 <td>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br/>
                                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                 </td>
                                            </tr>
                                            </table>';

        $pengirim = array(
            'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
            'ID_PENERIMA' => $datapn->ID_USER,
            'SUBJEK' => 'Pengumuman Harta Kekayaan PN',
            'PESAN' => $message_pengumuman_pn,
            'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
            'IS_ACTIVE' => '1',
            'ID_LHKPN' => $id_lhkpn
        );
        $kirim = $this->mglobal->insert('T_PESAN_KELUAR', $pengirim);

        if ($kirim) {

            $no_bap = str_replace("/", "_", $datapn->NOMOR_BAP);
            $output_filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NHK . ".pdf";
            //        $fileqrcode = 'uploads/FINAL_LHKPN/' . $datapn->NIK . "/qrcode.png";
            $penerima = array(
                'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
                'ID_PENERIMA' => $datapn->ID_USER,
                'SUBJEK' => $this->input->post('SUBJEK'),
                'SUBJEK' => 'Pengumuman Harta Kekayaan PN',
                'PESAN' => $message_pengumuman_pn,
                'FILE' => "../../../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NHK . '/' . $output_filename,
                'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                'IS_ACTIVE' => '1',
                'ID_LHKPN' => $id_lhkpn
            );
            if($datapn->STATUS_CETAK_PENGUMUMAN_PDF == '0') {
                $this->mglobal->insert('T_PESAN_MASUK', $penerima);
            }

            /////////word di comment//////////
            // $this->load->library('lwphpword/lwphpword', array(
            //     "base_path" => APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            //     "base_url" => base_url() . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            //     "base_root" => base_url(),
            // ));

            // if ($datapn->JENIS_LAPORAN == '5') {
            //     $template_file = "../file/template/FormatPengumuman-Pemeriksaan.docx";
            // } else {
            //     $template_file = "../file/template/FormatPengumuman.docx";
            // }
            /////////tutup word di comment//////////

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "model_qr_prefix_nomor" => "PHK-ELHKPN-",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir" => APPPATH . "../images/qrcode/" //hanya untuk production
            ]);

            $filename_bap = 'uploads/FINAL_LHKPN/' . $no_bap . "/" . $datapn->NHK;
            $dir_bap = './uploads/FINAL_LHKPN/' . $no_bap . '/';

            if (!is_dir($filename_bap)) {

                if (is_dir($dir_bap) === false) {
                    mkdir($dir_bap);
                }
            }

//            if (is_dir($dir_bap) == TRUE) {
            $filename = $dir_bap . $datapn->NHK . "/$output_filename";

//                if (!file_exists($filename)) {
            $dir = $dir_bap . $datapn->NHK . '/';

            if (is_dir($dir) === false) {
                mkdir($dir);
            }
            $qr_content_data = json_encode((object) [
                        "data" => [
                            (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $datapn->NAMA_LENGKAP],
                            (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data_pn["NHK"]],
                            (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $datapn->BDG_NAMA],
                            (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $datapn->NAMA_JABATAN],
                            (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $datapn->INST_NAMA],
                            (object) ["tipe" => '1', "judul" => "SUB_UNIT_KERJA", "isi" => $datapn->SUK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus')) . " - " . show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->TGL_LAPOR, tgl_format($datapn->TGL_LAPOR))],
                            (object) ["tipe" => '1', "judul" => "Tanggal Pelaporan", "isi" => tgl_format($datapn->TGL_LAPOR)],
                            (object) ["tipe" => '1', "judul" => "Tanggal Kirim Final", "isi" => tgl_format($datapn->tgl_kirim_final)],
                            (object) ["tipe" => '1', "judul" => "Tanah dan Bangunan", "isi" => $datapn->T1 == NULL ? "----" : number_rupiah($datapn->T1)],
                            (object) ["tipe" => '1', "judul" => "Alat Transportasi dan Mesin", "isi" => $datapn->T6 == NULL ? "----" : number_rupiah($datapn->T6)],
                            (object) ["tipe" => '1', "judul" => "Harta Bergerak Lainnya", "isi" => $datapn->T5 == NULL ? "----" : number_rupiah($datapn->T5)],
                            (object) ["tipe" => '1', "judul" => "Surat Berharga", "isi" => $datapn->T2 == NULL ? "----" : number_rupiah($datapn->T2)],
                            (object) ["tipe" => '1', "judul" => "Kas dan Setara Kas", "isi" => $datapn->T4 == NULL ? "----" : number_rupiah($datapn->T4)],
                            (object) ["tipe" => '1', "judul" => "Harta Lainnya", "isi" => $datapn->T3 == NULL ? "----" : number_rupiah($datapn->T3)],
                            (object) ["tipe" => '1', "judul" => "Hutang", "isi" => $datapn->T7 == NULL ? "----" : number_rupiah($datapn->T7)],
                            (object) ["tipe" => '1', "judul" => "Total Harta Kekayaan", "isi" => number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7) == NULL ? "----" : number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7)],
                        ],
                        "encrypt_data" => $id_lhkpn . "phk",
                        "id_lhkpn" => $id_lhkpn,
                        "judul" => "Pengumuman Harta Kekayaan Penyelenggara Negara",
                        "tgl_surat" => date('Y-m-d'),
            ]);

            $qr_file = "tes_qr2-" . $id_lhkpn . "-" . date('Y-m-d_H-i-s') . ".png";
            $qr_image_location = $this->lws_qr->create($qr_content_data, $qr_file);

            /////////word di comment//////////
    //         $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image1.jpeg" => $qr_image_location));

    //         $this->lwphpword->save_path = APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/";

    //         $this->lwphpword->set_value("NHK", $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]);
    //         $this->lwphpword->set_value("NAMA_LENGKAP", $this->__is_cetak_var_not_blank($datapn->NAMA_LENGKAP,'-'));
    //         $this->lwphpword->set_value("LEMBAGA", $this->__is_cetak_var_not_blank($datapn->INST_NAMA,'-'));
    //         $this->lwphpword->set_value("BIDANG", $this->__is_cetak_var_not_blank($datapn->BDG_NAMA,'-'));
    //         $this->lwphpword->set_value("JABATAN", $this->__is_cetak_var_not_blank($datapn->NAMA_JABATAN,'-'));
    //         $this->lwphpword->set_value("UNIT_KERJA", $this->__is_cetak_var_not_blank($datapn->UK_NAMA,'-'));
    //         $this->lwphpword->set_value("SUB_UNIT_KERJA", $this->__is_cetak_var_not_blank($datapn->SUK_NAMA,'-'));
    //         $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus'));
    //         $this->lwphpword->set_value("KHUSUS", $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-'));
    //         $this->lwphpword->set_value("TANGGAL", $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-'));
    //         $this->lwphpword->set_value("TAHUN", $this->__is_cetak_var_not_blank(substr($tgl_lapor_new, 0, 4),'-'));
    // //                    $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
    //         $this->lwphpword->set_value("TGL_BN", tgl_format($datapn->TGL_PNRI));
    //         $this->lwphpword->set_value("NO_BN", $datapn->NOMOR_PNRI);
    //         $this->lwphpword->set_value("PENGESAHAN", tgl_format($datapn->TGL_BA_PENGUMUMAN));
    //         $this->lwphpword->set_value("STATUS", $datapn->STATUS == '3' || $datapn->STATUS == '4' ? "LENGKAP" : "TIDAK LENGKAP");
    //         $this->lwphpword->set_value("TANGGAL_BAK", $this->__is_cetak_var_not_blank(tgl_format($datapn->TGL_KLARIFIKASI),'-'));

    //         $this->lwphpword->set_value("HTB", $datapn->T1 == NULL || $datapn->T1 == '0' ? "----" : number_rupiah($datapn->T1));
    //         $this->lwphpword->set_value("HB", $datapn->T6 == NULL || $datapn->T6 == '0' ? "----" : number_rupiah($datapn->T6));
    //         $this->lwphpword->set_value("HBL", $datapn->T5 == NULL || $datapn->T5 == '0' ? "----" : number_rupiah($datapn->T5));
    //         $this->lwphpword->set_value("SB", $datapn->T2 == NULL || $datapn->T2 == '0' ? "----" : number_rupiah($datapn->T2));
    //         $this->lwphpword->set_value("KAS", $datapn->T4 == NULL || $datapn->T4 == '0' ? "----" : number_rupiah($datapn->T4));
    //         $this->lwphpword->set_value("HL", $datapn->T3 == NULL || $datapn->T3 == '0' ? "----" : number_rupiah($datapn->T3));
    //         $this->lwphpword->set_value("HUTANG", $datapn->T7 == NULL || $datapn->T7 == '0' ? "----" : number_rupiah($datapn->T7));
    //         $this->lwphpword->set_value("TOTAL", number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7));
    //         $this->lwphpword->set_value("subtotal", $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6),'-'));

    //         $this->set_data_harta_bergerak($obj_dhb, $this->lwphpword);
    //         $this->set_data_harta_tidak_bergerak($obj_dhtb, $this->lwphpword);

//             $output_filename2 = "Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NIK;
    //         $save_document_success = $this->lwphpword->save_document_announ(FALSE, '', FALSE, $output_filename2);
            /////////tutup word di comment//////////

            /////////////////////////////PDF GENERATOR///////////////////////////

            $exp_tgl_kirim = explode('-', $datapn->tgl_kirim_final);
            $thn_kirim = $exp_tgl_kirim[0];

            $data = array(
                "DHB" => $obj_dhb,
                "DHTB" => $obj_dhtb,
                "NHK" => $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"],
                "NAMA_LENGKAP" => $this->__is_cetak_var_not_blank($datapn->NAMA_LENGKAP,'-'),
                "LEMBAGA" => $this->__is_cetak_var_not_blank($datapn->INST_NAMA,'-'),
                "BIDANG" => $this->__is_cetak_var_not_blank($datapn->BDG_NAMA,'-'),
                "JABATAN" => $this->__is_cetak_var_not_blank($datapn->NAMA_JABATAN,'-'),
                "UNIT_KERJA" => $this->__is_cetak_var_not_blank($datapn->UK_NAMA,'-'),
                "SUB_UNIT_KERJA" => $this->__is_cetak_var_not_blank($datapn->SUK_NAMA,'-'),
                "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus'),
                "KHUSUS" => $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-'),
                "TANGGAL" => $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-'),
                "TAHUN" =>  $this->__is_cetak_var_not_blank(substr($tgl_lapor_new, 0, 4),'-'),
                "TGL_BN" => tgl_format($datapn->TGL_PNRI),
                "NO_BN" => $datapn->NOMOR_PNRI,
                "PENGESAHAN" => $this->__is_cetak_var_not_blank( tgl_format($datapn->TGL_BA_PENGUMUMAN),'-'),
                "STATUS" => $datapn->STATUS == '3' || $datapn->STATUS == '4' ? "LENGKAP" : "TIDAK LENGKAP",
                "HTB" => $datapn->T1 == NULL || $datapn->T1 == '0' ? "----" : number_rupiah($datapn->T1),
                "HB" => $datapn->T6 == NULL || $datapn->T6 == '0' ? "----" : number_rupiah($datapn->T6),
                "HBL" => $datapn->T5 == NULL || $datapn->T5 == '0' ? "----" : number_rupiah($datapn->T5),
                "SB" => $datapn->T2 == NULL || $datapn->T2 == '0' ? "----" : number_rupiah($datapn->T2),
                "KAS" => $datapn->T4 == NULL || $datapn->T4 == '0' ? "----" : number_rupiah($datapn->T4),
                "HL" => $datapn->T3 == NULL || $datapn->T3 == '0' ? "----" : number_rupiah($datapn->T3),
                "HUTANG" => $datapn->T7 == NULL || $datapn->T7 == '0' ? "----" : number_rupiah($datapn->T7),
                "TOTAL" => number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7),
                "subtotal" => $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6),'-'),
                "qr_code_name" => $qr_image_location,
                "TANGGAL_BAK" => $this->__is_cetak_var_not_blank(tgl_format($datapn->TGL_KLARIFIKASI),'-'),
                "TAHUN_KIRIM" => $thn_kirim
             );

            $this->load->library('pdfgenerator');


            $html = $this->load->view('announ/export_pdf/pengumuman', $data, true);
            $filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $data['NHK'];
            $method = "store";
            $path_pdf = $dir_bap . $datapn->NHK . "/";
            $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait',$path_pdf);
            if($save_document_success) {
                $data_file = array(
                    'is_kirim_email' => 1
                );
                $update = $this->mglobal->update('r_ba_pengumuman', $data_file, NULL, "ID_LHKPN = '$id_lhkpn'");
            }
            /////////////////////////////TUTUP PDF GENERATOR///////////////////////////
            $temp_dir = APPPATH."../images/qrcode/";
            unlink($temp_dir.$qr_file);

            $history = [
                'ID_LHKPN' => $id_lhkpn,
                'ID_STATUS' => '15',
                'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                'USERNAME_PENERIMA' => $datapn->NIK,
                'DATE_INSERT' => date('Y-m-d H:i:s'),
                'CREATED_IP' => $this->input->ip_address()
            ];

            $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);

//            $save_done = FALSE;
//            $generating_document = FALSE;
//            $save_document_success = FALSE;
//            $mail_sent = FALSE;
//            while (!$save_done) {
//                if (!$generating_document) {
//                    $save_document_success = $this->lwphpword->save_document(1, '', FALSE, $output_filename);
//                    $generating_document = TRUE;
//                }
//                if ($save_document_success) {
//                    $save_done = TRUE;
//                    rename($save_document_success, APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/" . $output_filename);
//                            while (!$mail_sent) {
//                                if ("Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NIK . "_" . date('d-F-Y') . ".docx" == $output_filename) {
//                                    ob_start();
//                                    $NGHelper = new ng();
//                                    $NGHelper::mail_send($datapn->EMAIL, 'Pengumuman Harta Kekayaan PN', $message_pengumuman_pn, NULL, 'uploads/FINAL_LHKPN/' . $no_bap . '/' . $datapn->NIK . '/' . $output_filename);
//                                    $mail_sent = TRUE;
//                                    unset($NGHelper);
//                                    ob_end_clean();
//                                    flush();
//                                    ob_flush();
//                                }
//                            }
//                    $history = [
//                        'ID_LHKPN' => $id_lhkpn,
//                        'ID_STATUS' => '15',
//                        'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
//                        'USERNAME_PENERIMA' => $datapn->NIK,
//                        'DATE_INSERT' => date('Y-m-d H:i:s'),
//                        'CREATED_IP' => $this->input->ip_address()
//                    ];
//
//                    $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
//                    ng::mail_send($datapn->EMAIL, 'Pengumuman Harta Kekayaan PN', $message_pengumuman_pn, NULL, 'uploads/FINAL_LHKPN/' . $no_bap . '/' . $datapn->NIK . '/' . $output_filename);
//                    if ($datapn->STATUS == 4) {
//                        $curl_data = 'SEND={"tujuan":"' . $datapn->HP . '","isiPesan":"LHKPN Saudara telah diumumkan lengkap", "idModem":6}';
//                        CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
//                    } else if ($datapn->STATUS == 6) {
//                        $curl_data = 'SEND={"tujuan":"' . $datapn->HP . '","isiPesan":"LHKPN Saudara telah diumumkan tidak lengkap", "idModem":6}';
//                        CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
//                    }
//                }
//            }
//                }
//            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo 0;
        } else {
            $this->db->trans_commit();
            echo $id_lhkpn;
        }
    }

    public function deleteFilesFolder($directory) {
        chmod($directory,0777);
        $recursive = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($recursive, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($directory);
    }

    public function CreatePdfFinalWithEmail($id_lhkpn, $id_bap = NULL) {
        $datapn = $this->mglobal->get_data_all(
                        'R_BA_PENGUMUMAN', [
                    ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
                    ['table' => 't_lhkpn_data_pribadi', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 't_lhkpn_data_pribadi.ID_LHKPN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_JABATAN m_jbt', 'on' => 'm_jbt.ID_JABATAN   =  jbt.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'm_jbt.INST_SATKERKD   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'm_jbt.UK_ID   =  unke.UK_ID'],
                    ['table' => 'M_SUB_UNIT_KERJA subunke', 'on' => 'm_jbt.SUK_ID   =  subunke.SUK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
                    ['table' => 'T_LHKPN_HUTANG', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN AND T_LHKPN_HUTANG.IS_ACTIVE = 1'],
                    ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
                    ['table' => 'T_USER', 'on' => 'T_USER.USERNAME = T_PN.NIK'],
                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                        ], NULL, "t_lhkpn_data_pribadi.*, jbt.ALAMAT_KANTOR, jbt.DESKRIPSI_JABATAN, m_jbt.NAMA_JABATAN,, T_PN.NIK, T_PN.ID_PN,  inst.INST_NAMA, T_PN.TGL_LAHIR, T_PN.NHK,T_PN.NAMA, SUM(T_LHKPN_HUTANG.SALDO_HUTANG) AS jumhut, T_LHKPN.TGL_LAPOR, T_LHKPN.tgl_kirim_final, T_LHKPN.JENIS_LAPORAN, T_LHKPN.STATUS, bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA, ba.NOMOR_PNRI, ba.TGL_PNRI, ba.NOMOR_BAP, ba.TGL_BA_PENGUMUMAN, T_USER.ID_USER, IF (T_LHKPN.JENIS_LAPORAN = '4', 'Periodik', IF (T_LHKPN.JENIS_LAPORAN = '5', 'Klarifikasi', 'Khusus')) AS JENIS, T_USER.EMAIL, subunke.SUK_NAMA, T_LHKPN.TGL_KLARIFIKASI,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
                        (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6,
                        (SELECT SUM(SALDO_HUTANG) FROM t_lhkpn_hutang WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') T7 ", "T_LHKPN.ID_LHKPN = '$id_lhkpn' AND jbt.IS_PRIMARY = '1'", NULL, 0, NULL, "T_LHKPN.ID_LHKPN"
                )[0];
        if ($datapn->TGL_LAPOR == '1970-01-01' || $datapn->TGL_LAPOR == '' || $datapn->TGL_LAPOR == '-') {
            $tgl_lapor_new = $datapn->tgl_kirim_final;
        }
        else{
            $tgl_lapor_new = $datapn->TGL_LAPOR;
        }
        $this->data['dt_harta_tidak_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_tidak_bergerak", [
            ['table' => 'm_negara ', 'on' => 'm_negara.ID   = ' . 't_lhkpn_harta_tidak_bergerak.ID_NEGARA'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_tidak_bergerak.ASAL_USUL'],
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   IN ' . '(t_lhkpn_harta_tidak_bergerak.PEMANFAATAN)']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_tidak_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_tidak_bergerak.IS_ACTIVE = '1'", "*, GROUP_CONCAT(DISTINCT m_pemanfaatan.PEMANFAATAN) as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_tidak_bergerak.ID");

        $this->data['dt_harta_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_bergerak", [
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   = t_lhkpn_harta_bergerak.PEMANFAATAN'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_bergerak.ASAL_USUL'],
            ['table' => 'm_jenis_harta ', 'on' => 'm_jenis_harta.ID_JENIS_HARTA   = t_lhkpn_harta_bergerak.KODE_JENIS']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_bergerak.IS_ACTIVE = '1'", "*, m_pemanfaatan.PEMANFAATAN as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_bergerak.ID");

        $this->data['datapn'] = $datapn;
        $th = date('Y');

        $arr_dhb = array();
        $arr_dhtb = array();
        foreach ($this->data['dt_harta_bergerak'] as $data) {
            $arr_dhb[] = $data->NAMA . ', ' . $data->MEREK . ' ' . $data->MODEL . ' Tahun ' . $data->TAHUN_PEMBUATAN . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
        }
        foreach ($this->data['dt_harta_tidak_bergerak'] as $data) {
            $tmp = $data->NEGARA == 2 ? $data->JALAN . ', ' . $data->NAMA_NEGARA : $data->KAB_KOT;
            if ($data->LUAS_TANAH == NULL || $data->LUAS_TANAH == '') {
                $luas_tanah = '-';
            } else {
                $luas_tanah = $data->LUAS_TANAH;
            }
            if ($data->LUAS_BANGUNAN == NULL || $data->LUAS_BANGUNAN == '') {
                $luas_bangunan = '-';
            } else {
                $luas_bangunan = $data->LUAS_BANGUNAN;
            }
            if ($data->LUAS_BANGUNAN !== "0" && $data->LUAS_TANAH !== "0") {
                $arr_dhtb[] = 'Tanah dan Bangunan Seluas ' . $luas_tanah . ' m2/' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else if ($data->LUAS_TANAH !== "0" && $data->LUAS_BANGUNAN == "0") {
                $arr_dhtb[] = 'Tanah Seluas ' . $luas_tanah . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else {
                $arr_dhtb[] = 'Bangunan Seluas ' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            }
        }

        $arr_all_data = array(
            'nama' => $datapn->NAMA,
            'jabatan' => $datapn->DESKRIPSI_JABATAN,
            'nhk' => $datapn->NHK,
            'tempat_tgl_lahir' => $datapn->TGL_LAHIR,
            'alamat_kantor' => $datapn->ALAMAT_KANTOR,
            'tgl_pelaporan' => $datapn->TGL_LAPOR,
            'nilai_hutang' => $datapn->jumhut,
            'nilai_hl' => $datapn->T3,
            'nilai_kas' => $datapn->T4,
            'nilai_surga' => $datapn->T2,
            'hbl' => $datapn->T5,
            'hb' => $arr_dhb,
            'htb' => $arr_dhtb,
        );

        $obj_dhb = (object) $arr_dhb;
        $obj_dhtb = (object) $arr_dhtb;
//        var_dump('<pre>');
//        $json_pn = json_encode($arr_all_data);
//        $url = 'http://localhost/testing/?pengumuman=' . $json_pn;
//        CallURLPage($url);

        $this->db->trans_begin();

        if ($datapn->STATUS == '3' || $datapn->STATUS == '4')
            $sts = '4';
        else if ($datapn->STATUS == '5' || $datapn->STATUS == '6')
            $sts = '6';

        $data_lhkpn = array('STATUS' => $sts);
        $update_lhkpn = $this->mglobal->update('T_LHKPN', $data_lhkpn, NULL, "T_LHKPN.ID_LHKPN = '$id_lhkpn'");


        if ($datapn->NHK != NULL) {
            $max_nhk = $datapn->NHK;
        } else {
            $max_nhk = $this->mglobal->getMaxNHK('t_pn', 'NHK');
            $max_nhk = $max_nhk != NULL ? $max_nhk + 1 : 1;
        }

        $data_ba = array(
            'STATUS_CETAK_PENGUMUMAN_PDF' => 1
        );


        $this->data['nhk'] = $max_nhk;
        $data_pn = array(
            'NHK' => $max_nhk
        );
        $update = $this->mglobal->update('r_ba_pengumuman', $data_ba, NULL, "ID_LHKPN = '$id_lhkpn'");
        $update_pn = $this->mglobal->update('t_pn', $data_pn, NULL, "ID_PN = $datapn->ID_PN");

        $message_pengumuman_pn = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   Di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <br>
                        <table>
                             <tr>
                                 <td>
                                    <ol>
                                        <li value="1">Undang-Undang Nomor 28 Tahun 1999 tentang Penyelenggaraan Negara yang Bersih dan Bebas dari Korupsi, Kolusi dan Nepotisme;</li>
                                        <li>Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi sebagaimana telah dua kali diubah dengan perubahan terakhir Undang-Undang Nomor 19 Tahun 2019 tentang Perubahan Kedua Atas Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi.;</li>
                                        <li>Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata Cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara sebagaimana diubah dengan Peraturan Komisi Pemberantasan Korupsi Nomor 02 Tahun 2020 tentang Perubahan atas Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata Cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara.</li>
                                    </ol>
                                 </td>
                            </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Berdasarkan ketentuan di atas, setiap Wajib LHKPN berkewajiban untuk melaporkan dan mengumumkan harta kekayaannya sebelum dan setelah menjabat. Bersama ini disampaikan bahwa kami telah mengumumkan harta kekayaan Saudara dalam Lembar Pengumuman (terlampir) sebagai berikut:
                                 </td>
                            </tr>
                        </table>
                        <br>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="40%" valign="top"><b>NIK</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NIK . '</td>
                                            </tr>
                                             <tr>
                                                <td width="40%" valign="top"><b>Jenis Laporan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->JENIS . '</td>
                                            </tr>
                                            <tr>
                                                <td width="40%" valign="top"><b>Tanggal Kirim</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->tgl_kirim_final . '</td>
                                            </tr>
                                            <tr>
                                                <td width="40%" valign="top"><b>Nomor Harta Kekayaaan (NHK)</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NHK . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                            <table>
                                             <tr>
                                                 <td>
                                                    Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br/>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br/>
                                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                 </td>
                                            </tr>
                                            </table>';

        $pengirim = array(
            'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
            'ID_PENERIMA' => $datapn->ID_USER,
            'SUBJEK' => 'Pengumuman Harta Kekayaan PN',
            'PESAN' => $message_pengumuman_pn,
            'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
            'IS_ACTIVE' => '1',
            'ID_LHKPN' => $id_lhkpn
        );
        $kirim = $this->mglobal->insert('T_PESAN_KELUAR', $pengirim);

        if ($kirim) {

            $no_bap = str_replace("/", "_", $datapn->NOMOR_BAP);
            $output_filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NHK . ".pdf";
            //        $fileqrcode = 'uploads/FINAL_LHKPN/' . $datapn->NIK . "/qrcode.png";
            $penerima = array(
                'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
                'ID_PENERIMA' => $datapn->ID_USER,
                'SUBJEK' => $this->input->post('SUBJEK'),
                'SUBJEK' => 'Pengumuman Harta Kekayaan PN',
                'PESAN' => $message_pengumuman_pn,
                'FILE' => "../../../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NHK . '/' . $output_filename,
                'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                'IS_ACTIVE' => '1',
                'ID_LHKPN' => $id_lhkpn
            );
            $this->mglobal->insert('T_PESAN_MASUK', $penerima);

            //bakal di comment
            // $this->load->library('lwphpword/lwphpword', array(
            //     "base_path" => APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            //     "base_url" => base_url() . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            //     "base_root" => base_url(),
            // ));

            // if ($datapn->JENIS_LAPORAN == '5') {
            //     $template_file = "../file/template/FormatPengumuman-Pemeriksaan.docx";
            // } else {
            //     $template_file = "../file/template/FormatPengumuman.docx";
            // }
            //tutup bakal di comment

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "model_qr_prefix_nomor" => "PHK-ELHKPN-",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir" => APPPATH . "../images/qrcode/" //hanya untuk production
            ]);

            $filename_bap = 'uploads/FINAL_LHKPN/' . $no_bap . "/" . $datapn->NHK;
            $dir_bap = './uploads/FINAL_LHKPN/' . $no_bap . '/';

            if (!is_dir($filename_bap)) {

                if (is_dir($dir_bap) === false) {
                    mkdir($dir_bap);
                }
            }

//            if (is_dir($dir_bap) == TRUE) {
            $filename = $dir_bap . $datapn->NHK . "/$output_filename";

//                if (!file_exists($filename)) {
            $dir = $dir_bap . $datapn->NHK . '/';

            if (is_dir($dir) === false) {
                mkdir($dir);
            }
            $qr_content_data = json_encode((object) [
                        "data" => [
                            (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $datapn->NAMA_LENGKAP],
                            (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data_pn["NHK"]],
                            (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $datapn->BDG_NAMA],
                            (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $datapn->NAMA_JABATAN],
                            (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $datapn->INST_NAMA],
                            (object) ["tipe" => '1', "judul" => "SUB_UNIT_KERJA", "isi" => $datapn->SUK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus')) . " - " . show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->TGL_LAPOR, tgl_format($datapn->TGL_LAPOR))],
                            (object) ["tipe" => '1', "judul" => "Tanggal Pelaporan", "isi" => tgl_format($datapn->TGL_LAPOR)],
                            (object) ["tipe" => '1', "judul" => "Tanggal Kirim Final", "isi" => tgl_format($datapn->tgl_kirim_final)],
                            (object) ["tipe" => '1', "judul" => "Tanah dan Bangunan", "isi" => $datapn->T1 == NULL ? "----" : number_rupiah($datapn->T1)],
                            (object) ["tipe" => '1', "judul" => "Alat Transportasi dan Mesin", "isi" => $datapn->T6 == NULL ? "----" : number_rupiah($datapn->T6)],
                            (object) ["tipe" => '1', "judul" => "Harta Bergerak Lainnya", "isi" => $datapn->T5 == NULL ? "----" : number_rupiah($datapn->T5)],
                            (object) ["tipe" => '1', "judul" => "Surat Berharga", "isi" => $datapn->T2 == NULL ? "----" : number_rupiah($datapn->T2)],
                            (object) ["tipe" => '1', "judul" => "Kas dan Setara Kas", "isi" => $datapn->T4 == NULL ? "----" : number_rupiah($datapn->T4)],
                            (object) ["tipe" => '1', "judul" => "Harta Lainnya", "isi" => $datapn->T3 == NULL ? "----" : number_rupiah($datapn->T3)],
                            (object) ["tipe" => '1', "judul" => "Hutang", "isi" => $datapn->T7 == NULL ? "----" : number_rupiah($datapn->T7)],
                            (object) ["tipe" => '1', "judul" => "Total Harta Kekayaan", "isi" => number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7) == NULL ? "----" : number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7)],
                        ],
                        "encrypt_data" => $id_lhkpn . "phk",
                        "id_lhkpn" => $id_lhkpn,
                        "judul" => "Pengumuman Harta Kekayaan Penyelenggara Negara",
                        "tgl_surat" => date('Y-m-d'),
            ]);

            $qr_file = "tes_qr2-" . $id_lhkpn . "-" . date('Y-m-d_H-i-s') . ".png";
            $qr_image_location = $this->lws_qr->create($qr_content_data, $qr_file);

            //bakal di comment
    //         $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image1.jpeg" => $qr_image_location));

    //         $this->lwphpword->save_path = APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/";

    //         $this->lwphpword->set_value("NHK", $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]);
    //         $this->lwphpword->set_value("NAMA_LENGKAP", $this->__is_cetak_var_not_blank($datapn->NAMA_LENGKAP,'-'));
    //         $this->lwphpword->set_value("LEMBAGA", $this->__is_cetak_var_not_blank($datapn->INST_NAMA,'-'));
    //         $this->lwphpword->set_value("BIDANG", $this->__is_cetak_var_not_blank($datapn->BDG_NAMA,'-'));
    //         $this->lwphpword->set_value("JABATAN", $this->__is_cetak_var_not_blank($datapn->NAMA_JABATAN,'-'));
    //         $this->lwphpword->set_value("UNIT_KERJA", $this->__is_cetak_var_not_blank($datapn->UK_NAMA,'-'));
    //         $this->lwphpword->set_value("SUB_UNIT_KERJA", $this->__is_cetak_var_not_blank($datapn->SUK_NAMA,'-'));
    //         $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus'));
    //         $this->lwphpword->set_value("KHUSUS", $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-'));
    //         $this->lwphpword->set_value("TANGGAL", $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-'));
    //         $this->lwphpword->set_value("TAHUN", $this->__is_cetak_var_not_blank(substr($tgl_lapor_new, 0, 4),'-'));
    // //                    $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
    //         $this->lwphpword->set_value("TGL_BN", tgl_format($datapn->TGL_PNRI));
    //         $this->lwphpword->set_value("NO_BN", $datapn->NOMOR_PNRI);
    //         $this->lwphpword->set_value("PENGESAHAN", tgl_format($datapn->TGL_BA_PENGUMUMAN));
    //         $this->lwphpword->set_value("STATUS", $datapn->STATUS == '3' || $datapn->STATUS == '4' ? "LENGKAP" : "TIDAK LENGKAP");
    //         $this->lwphpword->set_value("TANGGAL_BAK", $this->__is_cetak_var_not_blank(tgl_format($datapn->TGL_KLARIFIKASI),'-'));

    //         $this->lwphpword->set_value("HTB", $datapn->T1 == NULL || $datapn->T1 == '0' ? "----" : number_rupiah($datapn->T1));
    //         $this->lwphpword->set_value("HB", $datapn->T6 == NULL || $datapn->T6 == '0' ? "----" : number_rupiah($datapn->T6));
    //         $this->lwphpword->set_value("HBL", $datapn->T5 == NULL || $datapn->T5 == '0' ? "----" : number_rupiah($datapn->T5));
    //         $this->lwphpword->set_value("SB", $datapn->T2 == NULL || $datapn->T2 == '0' ? "----" : number_rupiah($datapn->T2));
    //         $this->lwphpword->set_value("KAS", $datapn->T4 == NULL || $datapn->T4 == '0' ? "----" : number_rupiah($datapn->T4));
    //         $this->lwphpword->set_value("HL", $datapn->T3 == NULL || $datapn->T3 == '0' ? "----" : number_rupiah($datapn->T3));
    //         $this->lwphpword->set_value("HUTANG", $datapn->T7 == NULL || $datapn->T7 == '0' ? "----" : number_rupiah($datapn->T7));
    //         $this->lwphpword->set_value("TOTAL", number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7));
    //         $this->lwphpword->set_value("subtotal", $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6),'-'));

    //         $this->set_data_harta_bergerak($obj_dhb, $this->lwphpword);
    //         $this->set_data_harta_tidak_bergerak($obj_dhtb, $this->lwphpword);

//             $output_filename2 = "Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NIK;
    //         $save_document_success = $this->lwphpword->save_document_announ(FALSE, '', FALSE, $output_filename2);
            //tutup bakal di comment

            $history = [
                'ID_LHKPN' => $id_lhkpn,
                'ID_STATUS' => '15',
                'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                'USERNAME_PENERIMA' => $datapn->NIK,
                'DATE_INSERT' => date('Y-m-d H:i:s'),
                'CREATED_IP' => $this->input->ip_address()
            ];

            /////////////////////////////PDF GENERATOR///////////////////////////

            $exp_tgl_kirim = explode('-', $datapn->tgl_kirim_final);
            $thn_kirim = $exp_tgl_kirim[0];

            $data = array(
                "DHB" => $obj_dhb,
                "DHTB" => $obj_dhtb,
                "NHK" => $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"],
                "NAMA_LENGKAP" => $this->__is_cetak_var_not_blank($datapn->NAMA_LENGKAP,'-'),
                "LEMBAGA" => $this->__is_cetak_var_not_blank($datapn->INST_NAMA,'-'),
                "BIDANG" => $this->__is_cetak_var_not_blank($datapn->BDG_NAMA,'-'),
                "JABATAN" => $this->__is_cetak_var_not_blank($datapn->NAMA_JABATAN,'-'),
                "UNIT_KERJA" => $this->__is_cetak_var_not_blank($datapn->UK_NAMA,'-'),
                "SUB_UNIT_KERJA" => $this->__is_cetak_var_not_blank($datapn->SUK_NAMA,'-'),
                "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus'),
                "KHUSUS" => $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-'),
                "TANGGAL" => $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-'),
                "TAHUN" =>  $this->__is_cetak_var_not_blank(substr($tgl_lapor_new, 0, 4),'-'),
                "TGL_BN" => tgl_format($datapn->TGL_PNRI),
                "NO_BN" => $datapn->NOMOR_PNRI,
                "PENGESAHAN" => $this->__is_cetak_var_not_blank( tgl_format($datapn->TGL_BA_PENGUMUMAN),'-'),
                "STATUS" => $datapn->STATUS == '3' || $datapn->STATUS == '4' ? "LENGKAP" : "TIDAK LENGKAP",
                "HTB" => $datapn->T1 == NULL || $datapn->T1 == '0' ? "----" : number_rupiah($datapn->T1),
                "HB" => $datapn->T6 == NULL || $datapn->T6 == '0' ? "----" : number_rupiah($datapn->T6),
                "HBL" => $datapn->T5 == NULL || $datapn->T5 == '0' ? "----" : number_rupiah($datapn->T5),
                "SB" => $datapn->T2 == NULL || $datapn->T2 == '0' ? "----" : number_rupiah($datapn->T2),
                "KAS" => $datapn->T4 == NULL || $datapn->T4 == '0' ? "----" : number_rupiah($datapn->T4),
                "HL" => $datapn->T3 == NULL || $datapn->T3 == '0' ? "----" : number_rupiah($datapn->T3),
                "HUTANG" => $datapn->T7 == NULL || $datapn->T7 == '0' ? "----" : number_rupiah($datapn->T7),
                "TOTAL" => number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7),
                "subtotal" => $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6),'-'),
                "qr_code_name" => $qr_image_location,
                "TANGGAL_BAK" => $this->__is_cetak_var_not_blank(tgl_format($datapn->TGL_KLARIFIKASI),'-'),
                "TAHUN_KIRIM" => $thn_kirim
             );

            $this->load->library('pdfgenerator');


            $html = $this->load->view('announ/export_pdf/pengumuman', $data, true);
            $filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $data['NHK'];
            $method = "store";
            $path_pdf = $dir_bap . $datapn->NHK . "/";
            $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait',$path_pdf);
            $output_filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $data['NHK'] . ".pdf";
            /////////////////////////////TUTUP PDF GENERATOR///////////////////////////
            $temp_dir = APPPATH."../images/qrcode/";
            unlink($temp_dir.$qr_file);

            $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
            ng::mail_send($datapn->EMAIL, 'Pengumuman Harta Kekayaan PN', $message_pengumuman_pn, NULL, 'uploads/FINAL_LHKPN/' . $no_bap . '/' . $datapn->NHK . '/' . $output_filename);
            $this->deleteFilesFolder('./uploads/FINAL_LHKPN/' . $no_bap . '/');
//            $save_done = FALSE;
//            $generating_document = FALSE;
//            $save_document_success = FALSE;
//            $mail_sent = FALSE;
//            while (!$save_done) {
//                if (!$generating_document) {
//                    $save_document_success = $this->lwphpword->save_document(1, '', FALSE, $output_filename);
//                    $generating_document = TRUE;
//                }
//                if ($save_document_success) {
//                    $save_done = TRUE;
//                    rename($save_document_success, APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/" . $output_filename);
//                            while (!$mail_sent) {
//                                if ("Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NIK . "_" . date('d-F-Y') . ".docx" == $output_filename) {
//                                    ob_start();
//                                    $NGHelper = new ng();
//                                    $NGHelper::mail_send($datapn->EMAIL, 'Pengumuman Harta Kekayaan PN', $message_pengumuman_pn, NULL, 'uploads/FINAL_LHKPN/' . $no_bap . '/' . $datapn->NIK . '/' . $output_filename);
//                                    $mail_sent = TRUE;
//                                    unset($NGHelper);
//                                    ob_end_clean();
//                                    flush();
//                                    ob_flush();
//                                }
//                            }
//                    $history = [
//                        'ID_LHKPN' => $id_lhkpn,
//                        'ID_STATUS' => '15',
//                        'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
//                        'USERNAME_PENERIMA' => $datapn->NIK,
//                        'DATE_INSERT' => date('Y-m-d H:i:s'),
//                        'CREATED_IP' => $this->input->ip_address()
//                    ];
//
//                    $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
//                    ng::mail_send($datapn->EMAIL, 'Pengumuman Harta Kekayaan PN', $message_pengumuman_pn, NULL, 'uploads/FINAL_LHKPN/' . $no_bap . '/' . $datapn->NIK . '/' . $output_filename);
//                    if ($datapn->STATUS == 4) {
//                        $curl_data = 'SEND={"tujuan":"' . $datapn->HP . '","isiPesan":"LHKPN Saudara telah diumumkan lengkap", "idModem":6}';
//                        CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
//                    } else if ($datapn->STATUS == 6) {
//                        $curl_data = 'SEND={"tujuan":"' . $datapn->HP . '","isiPesan":"LHKPN Saudara telah diumumkan tidak lengkap", "idModem":6}';
//                        CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
//                    }
//                }
//            }
//                }
//            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo 0;
        } else {
            $this->db->trans_commit();
            echo $id_lhkpn;
        }
    }

    public function PreviewAnnoun($id_lhkpn, $id_bap = NULL) {
        $datapn = $this->mglobal->get_data_all(
                        'R_BA_PENGUMUMAN', [
                    ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
                    ['table' => 't_lhkpn_data_pribadi', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 't_lhkpn_data_pribadi.ID_LHKPN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_JABATAN m_jbt', 'on' => 'm_jbt.ID_JABATAN   =  jbt.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'm_jbt.INST_SATKERKD   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'm_jbt.UK_ID   =  unke.UK_ID'],
                    ['table' => 'M_SUB_UNIT_KERJA subunke', 'on' => 'm_jbt.SUK_ID   =  subunke.SUK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
                    ['table' => 'T_LHKPN_HUTANG', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN AND T_LHKPN_HUTANG.IS_ACTIVE = 1'],
                    ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
                    ['table' => 'T_USER', 'on' => 'T_USER.USERNAME = T_PN.NIK'],
                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                        ], NULL, "t_lhkpn.id_lhkpn_prev, t_lhkpn_data_pribadi.*, jbt.ALAMAT_KANTOR, jbt.DESKRIPSI_JABATAN, m_jbt.NAMA_JABATAN,, T_PN.NIK, T_PN.ID_PN,  inst.INST_NAMA, T_PN.TGL_LAHIR, T_PN.NHK,T_PN.NAMA, SUM(T_LHKPN_HUTANG.SALDO_HUTANG) AS jumhut, T_LHKPN.TGL_LAPOR, T_LHKPN.tgl_kirim_final, T_LHKPN.JENIS_LAPORAN, T_LHKPN.STATUS, bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA, ba.NOMOR_PNRI, ba.TGL_PNRI, ba.NOMOR_BAP, ba.TGL_BA_PENGUMUMAN, T_USER.ID_USER, IF (T_LHKPN.JENIS_LAPORAN = '4', 'Periodik', IF (T_LHKPN.JENIS_LAPORAN = '5', 'Klarifikasi', 'Khusus')) AS JENIS, T_USER.EMAIL,  subunke.SUK_NAMA, T_LHKPN.TGL_KLARIFIKASI,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
                        (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6,
                        (SELECT SUM(SALDO_HUTANG) FROM t_lhkpn_hutang WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') T7 ", "T_LHKPN.ID_LHKPN = '$id_lhkpn' AND jbt.IS_PRIMARY = '1'", NULL, 0, NULL, "T_LHKPN.ID_LHKPN"
                )[0];
        if($datapn->JENIS_LAPORAN=='5'){
            $data_lhkpn_prev = $this->mglobal->get_data_by_id('t_lhkpn','id_lhkpn',$datapn->id_lhkpn_prev)[0];
            if ($data_lhkpn_prev->TGL_LAPOR == '1970-01-01' || $data_lhkpn_prev->TGL_LAPOR == '' || $data_lhkpn_prev->TGL_LAPOR == '-') {
                $tgl_lapor_new = $data_lhkpn_prev->tgl_kirim_final;
            }
            else{
                $tgl_lapor_new = $data_lhkpn_prev->TGL_LAPOR;
            }
            $state_tanggal = $this->__is_cetak_var_not_blank(tgl_format($data_lhkpn_prev->tgl_kirim_final),'-');
            $state_jenis = $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($data_lhkpn_prev->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus');
            $state_khusus = $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($data_lhkpn_prev->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-');
            $jenis_laporan = 'Klarifikasi';
        }else{
            if ($datapn->TGL_LAPOR == '1970-01-01' || $datapn->TGL_LAPOR == '' || $datapn->TGL_LAPOR == '-') {
                $tgl_lapor_new = $datapn->tgl_kirim_final;
            }
            else{
                $tgl_lapor_new = $datapn->TGL_LAPOR;
            }
            $state_tanggal = $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-');
            $state_jenis = $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus');
            $state_khusus = $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-');
            $jenis_laporan = 'LAINNYA';
        }
        $this->data['dt_harta_tidak_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_tidak_bergerak", [
            ['table' => 'm_negara ', 'on' => 'm_negara.ID   = ' . 't_lhkpn_harta_tidak_bergerak.ID_NEGARA'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_tidak_bergerak.ASAL_USUL'],
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   IN ' . '(t_lhkpn_harta_tidak_bergerak.PEMANFAATAN)']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_tidak_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_tidak_bergerak.IS_ACTIVE = '1'", "*, GROUP_CONCAT(DISTINCT m_pemanfaatan.PEMANFAATAN) as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_tidak_bergerak.ID");

        $this->data['dt_harta_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_bergerak", [
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   = t_lhkpn_harta_bergerak.PEMANFAATAN'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_bergerak.ASAL_USUL'],
            ['table' => 'm_jenis_harta ', 'on' => 'm_jenis_harta.ID_JENIS_HARTA   = t_lhkpn_harta_bergerak.KODE_JENIS']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_bergerak.IS_ACTIVE = '1'", "*, m_pemanfaatan.PEMANFAATAN as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_bergerak.ID");

        $this->data['datapn'] = $datapn;
        $th = date('Y');

        $arr_dhb = array();
        $arr_dhtb = array();
        foreach ($this->data['dt_harta_bergerak'] as $data) {
            $arr_dhb[] = $data->NAMA . ', ' . $data->MEREK . ' ' . $data->MODEL . ' Tahun ' . $data->TAHUN_PEMBUATAN . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
        }
        foreach ($this->data['dt_harta_tidak_bergerak'] as $data) {
            // $tmp = $data->NEGARA == 2 ? $data->JALAN . ', ' . $data->NAMA_NEGARA : $data->KAB_KOT;
            $tmp = $data->NEGARA == 2 ? 'NEGARA '.$data->NAMA_NEGARA : 'KAB / KOTA '.$data->KAB_KOT;
            if ($data->LUAS_TANAH == NULL || $data->LUAS_TANAH == '') {
                $luas_tanah = '-';
            } else {
                $luas_tanah = $data->LUAS_TANAH;
            }
            if ($data->LUAS_BANGUNAN == NULL || $data->LUAS_BANGUNAN == '') {
                $luas_bangunan = '-';
            } else {
                $luas_bangunan = $data->LUAS_BANGUNAN;
            }
            if ($data->LUAS_BANGUNAN !== "0" && $data->LUAS_TANAH !== "0") {
                $arr_dhtb[] = 'Tanah dan Bangunan Seluas ' . $luas_tanah . ' m2/' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else if ($data->LUAS_TANAH !== "0" && $data->LUAS_BANGUNAN == "0") {
                $arr_dhtb[] = 'Tanah Seluas ' . $luas_tanah . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else {
                $arr_dhtb[] = 'Bangunan Seluas ' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            }
        }

        $arr_all_data = array(
            'nama' => $datapn->NAMA,
            'jabatan' => $datapn->DESKRIPSI_JABATAN,
            'nhk' => $datapn->NHK,
            'tempat_tgl_lahir' => $datapn->TGL_LAHIR,
            'alamat_kantor' => $datapn->ALAMAT_KANTOR,
            'tgl_pelaporan' => $datapn->TGL_LAPOR,
            'nilai_hutang' => $datapn->jumhut,
            'nilai_hl' => $datapn->T3,
            'nilai_kas' => $datapn->T4,
            'nilai_surga' => $datapn->T2,
            'hbl' => $datapn->T5,
            'hb' => $arr_dhb,
            'htb' => $arr_dhtb,
        );

        $obj_dhb = (object) $arr_dhb;
        $obj_dhtb = (object) $arr_dhtb;

        $this->db->trans_begin();

        if ($datapn->STATUS == '3' || $datapn->STATUS == '4')
            $sts = '4';
        else if ($datapn->STATUS == '5' || $datapn->STATUS == '6')
            $sts = '6';

        $data_lhkpn = array('STATUS' => $sts);
//        $update_lhkpn = $this->mglobal->update('T_LHKPN', $data_lhkpn, NULL, "T_LHKPN.ID_LHKPN = '$id_lhkpn'");
//        if ($datapn->NHK != NULL) {
        $max_nhk = $datapn->NHK;
//        } else {
//            $max_nhk = $this->mglobal->getMaxNHK('t_pn', 'NHK');
//            $max_nhk = $max_nhk != NULL ? $max_nhk + 1 : 1;
//        }

        $data_ba = array(
            'STATUS_CETAK_PENGUMUMAN_PDF' => 1
        );


        $this->data['nhk'] = $max_nhk;
        $data_pn = array(
            'NHK' => $max_nhk
        );
//        $update = $this->mglobal->update('r_ba_pengumuman', $data_ba, NULL, "ID_LHKPN = '$id_lhkpn'");
//        $update_pn = $this->mglobal->update('t_pn', $data_pn, NULL, "ID_PN = $datapn->ID_PN");
//        $message_pengumuman_pn = '<table>
//                           <tr>
//                                <td>
//                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
//                                   ' . $datapn->INST_NAMA . '<br/>
//                                   Di Tempat<br/>
//                                </td>
//                           </tr>
//                        </table>
//                        <br>
//                        <table>
//                             <tr>
//                                 <td>
//                                    <ol>
//                                        <li value="1">Undang-Undang Nomor 28 Tahun 1999 tentang Penyelenggaraan Negara yang Bersih dan Bebas dari Korupsi, Kolusi dan Nepotisme;</li>
//                                        <li>Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi, sebagaimana diubah dengan Undang-Undang Nomor 10 Tahun 2015 tentang Penetapan Peraturan Pemerintah Pengganti Undang-Undang Nomor 1 Tahun 2015 tentang Perubahan atas Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Korupsi menjadi Undang-Undang;</li>
//                                        <li>Peraturan Komisi Pemberantasan Korupsi Republik Indonesia Nomor 07 Tahun 2016 Tentang Tata Cara Pendaftaran, Pengumuman, dan Pemeriksaan Harta Kekayaan Penyelenggara Negara.</li>
//                                    </ol>
//                                 </td>
//                            </tr>
//                        </table>
//                        <table>
//                             <tr>
//                                 <td>
//                                Berdasarkan ketentuan di atas, setiap Wajib LHKPN berkewajiban untuk melaporkan dan mengumumkan harta kekayaannya sebelum dan setelah menjabat. Bersama ini disampaikan bahwa kami telah mengumumkan harta kekayaan Saudara dalam Lembar Pengumuman (terlampir) sebagai berikut:
//                                 </td>
//                            </tr>
//                        </table>
//                        <br>
//                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
//                            <tbody class="body-table">
//
//                                            <tr>
//                                                <td width="40%" valign="top"><b>NIK</b></td>
//                                                <td width="5%" valign="top"><b>:</b></td>
//                                                <td>' . $datapn->NIK . '</td>
//                                            </tr>
//                                             <tr>
//                                                <td width="40%" valign="top"><b>Jenis Laporan</b></td>
//                                                <td width="5%" valign="top"><b>:</td>
//                                                <td >' . $datapn->JENIS . '</td>
//                                            </tr>
//                                            <tr>
//                                                <td width="40%" valign="top"><b>Tanggal Kirim</b></td>
//                                                <td width="5%" valign="top"><b>:</b></td>
//                                                <td>' . $datapn->tgl_kirim_final . '</td>
//                                            </tr>
//                                            <tr>
//                                                <td width="40%" valign="top"><b>Nomor Harta Kekayaaan (NHK)</b></td>
//                                                <td width="5%" valign="top"><b>:</b></td>
//                                                <td>' . $datapn->NHK . '</td>
//                                            </tr>
//                                        </tbody>
//                                    </table>
//                                    <br>
//                                            <table>
//                                             <tr>
//                                                 <td>
//                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
//                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
//                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
//                                                    --------------------------------------------------------------<br/>
//                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br/>
//                                                    © ' . date('Y') . ' Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
//                                                 </td>
//                                            </tr>
//                                            </table>';
//        $pengirim = array(
//            'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
//            'ID_PENERIMA' => $datapn->ID_USER,
//            'SUBJEK' => 'Pengumuman Harta Kekayaan PN',
//            'PESAN' => $message_pengumuman_pn,
//            'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
//            'IS_ACTIVE' => '1',
//            'ID_LHKPN' => $id_lhkpn
//        );
//        $kirim = $this->mglobal->insert('T_PESAN_KELUAR', $pengirim);
//        if ($kirim) {

        $no_bap = str_replace("/", "_", $datapn->NOMOR_BAP);
        $output_filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NHK . ".pdf";
        //        $fileqrcode = 'uploads/FINAL_LHKPN/' . $datapn->NIK . "/qrcode.png";
//            $penerima = array(
//                'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
//                'ID_PENERIMA' => $datapn->ID_USER,
//                'SUBJEK' => $this->input->post('SUBJEK'),
//                'SUBJEK' => 'Pengumuman Harta Kekayaan PN',
//                'PESAN' => $message_pengumuman_pn,
//                'FILE' => "../../../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . '/' . $output_filename,
//                'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
//                'IS_ACTIVE' => '1'
//            );
//            $this->mglobal->insert('T_PESAN_MASUK', $penerima);

        ////////////word di tutup////////////
        // $this->load->library('lwphpword/lwphpword', array(
        //     "base_path" => APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
        //     "base_url" => base_url() . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
        //     "base_root" => base_url(),
        // ));
        // if ($datapn->JENIS_LAPORAN == '5') {
        //     $template_file = "../file/template/FormatPengumuman-Pemeriksaan.docx";
        // } else {
        //     $template_file = "../file/template/FormatPengumuman.docx";
        // }
        ////////////word di tutup////////////

        $this->load->library('lws_qr', [
            "model_qr" => "Cqrcode",
            "model_qr_prefix_nomor" => "PHK-ELHKPN-",
            "callable_model_function" => "insert_cqrcode_with_filename",
            "temp_dir" => APPPATH . "../images/qrcode/" //hanya untuk production
        ]);

        $filename_bap = 'uploads/FINAL_LHKPN/' . $no_bap . "/" . $datapn->NIK;
        $dir_bap = './uploads/FINAL_LHKPN/' . $no_bap . '/';

        // if (!is_dir($filename_bap)) {

        //     if (is_dir($dir_bap) === false) {
        //         mkdir($dir_bap);
        //     }
        // }

//            if (is_dir($dir_bap) == TRUE) {
        $filename = $dir_bap . $datapn->NIK . "/$output_filename";

//                if (!file_exists($filename)) {
        $dir = $dir_bap . $datapn->NIK . '/';

        if (is_dir($dir) === false) {
            mkdir($dir);
        }
        $qr_content_data = json_encode((object) [
                    "data" => [
                        (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $datapn->NAMA_LENGKAP],
                        (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]],
                        (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $datapn->BDG_NAMA],
                        (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $datapn->NAMA_JABATAN],
                        (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $datapn->INST_NAMA],
                        (object) ["tipe" => '1', "judul" => "SUB_UNIT_KERJA", "isi" => $datapn->SUK_NAMA],
                        (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus')) . " - " . show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->TGL_LAPOR, tgl_format($datapn->TGL_LAPOR))],
                        (object) ["tipe" => '1', "judul" => "Tanggal Pelaporan", "isi" => tgl_format($datapn->TGL_LAPOR)],
                        (object) ["tipe" => '1', "judul" => "Tanggal Kirim Final", "isi" => tgl_format($datapn->tgl_kirim_final)],
                        (object) ["tipe" => '1', "judul" => "Tanah dan Bangunan", "isi" => $datapn->T1 == NULL ? "----" : number_rupiah($datapn->T1)],
                        (object) ["tipe" => '1', "judul" => "Alat Transportasi dan Mesin", "isi" => $datapn->T6 == NULL ? "----" : number_rupiah($datapn->T6)],
                        (object) ["tipe" => '1', "judul" => "Harta Bergerak Lainnya", "isi" => $datapn->T5 == NULL ? "----" : number_rupiah($datapn->T5)],
                        (object) ["tipe" => '1', "judul" => "Surat Berharga", "isi" => $datapn->T2 == NULL ? "----" : number_rupiah($datapn->T2)],
                        (object) ["tipe" => '1', "judul" => "Kas dan Setara Kas", "isi" => $datapn->T4 == NULL ? "----" : number_rupiah($datapn->T4)],
                        (object) ["tipe" => '1', "judul" => "Harta Lainnya", "isi" => $datapn->T3 == NULL ? "----" : number_rupiah($datapn->T3)],
                        (object) ["tipe" => '1', "judul" => "Hutang", "isi" => $datapn->T7 == NULL ? "----" : number_rupiah($datapn->T7)],
                        (object) ["tipe" => '1', "judul" => "Total Harta Kekayaan", "isi" => number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7) == NULL ? "----" : number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7)],
                    ],
                    "encrypt_data" => $id_lhkpn . "phk",
                    "id_lhkpn" => $id_lhkpn,
                    "judul" => "Pengumuman Harta Kekayaan Penyelenggara Negara",
                    "tgl_surat" => date('Y-m-d'),
        ]);

        $qr_file = "tes_qr2-" . $id_lhkpn . "-" . date('Y-m-d_H-i-s') . ".png";
        $qr_image_location = $this->lws_qr->create($qr_content_data, $qr_file);

        ////////////word di tutup////////////
//         $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image1.jpeg" => $qr_image_location));

//         $this->lwphpword->save_path = APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/";

//         $this->lwphpword->set_value("NHK", $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]);
//         $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA_LENGKAP);
//         $this->lwphpword->set_value("LEMBAGA", $datapn->INST_NAMA);
//         $this->lwphpword->set_value("BIDANG", $datapn->BDG_NAMA);
//         $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
//         $this->lwphpword->set_value("UNIT_KERJA", $datapn->UK_NAMA);
//         $this->lwphpword->set_value("SUB_UNIT_KERJA", $this->__is_cetak_var_not_blank($datapn->SUK_NAMA,'-'));
//         $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus'));
//         $this->lwphpword->set_value("KHUSUS", show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)));
//         $this->lwphpword->set_value("TANGGAL", tgl_format($datapn->tgl_kirim_final));
//         $this->lwphpword->set_value("TAHUN", substr($tgl_lapor_new, 0, 4));
// //                    $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
//         $this->lwphpword->set_value("TGL_BN", tgl_format($datapn->TGL_PNRI));
//         $this->lwphpword->set_value("NO_BN", $datapn->NOMOR_PNRI);
//         $this->lwphpword->set_value("PENGESAHAN", tgl_format($datapn->TGL_BA_PENGUMUMAN));
//         $this->lwphpword->set_value("STATUS", $datapn->STATUS == '3' || $datapn->STATUS == '4' ? "LENGKAP" : "TIDAK LENGKAP");
//         $this->lwphpword->set_value("TANGGAL_BAK", $this->__is_cetak_var_not_blank(tgl_format($datapn->TGL_KLARIFIKASI),'-'));

//         $this->lwphpword->set_value("HTB", $datapn->T1 == NULL ? "----" : number_rupiah($datapn->T1));
//         $this->lwphpword->set_value("HB", $datapn->T6 == NULL ? "----" : number_rupiah($datapn->T6));
//         $this->lwphpword->set_value("HBL", $datapn->T5 == NULL ? "----" : number_rupiah($datapn->T5));
//         $this->lwphpword->set_value("SB", $datapn->T2 == NULL ? "----" : number_rupiah($datapn->T2));
//         $this->lwphpword->set_value("KAS", $datapn->T4 == NULL ? "----" : number_rupiah($datapn->T4));
//         $this->lwphpword->set_value("HL", $datapn->T3 == NULL ? "----" : number_rupiah($datapn->T3));
//         $this->lwphpword->set_value("HUTANG", $datapn->T7 == NULL ? "----" : number_rupiah($datapn->T7));
//         $this->lwphpword->set_value("TOTAL", number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7));
//         $this->lwphpword->set_value("subtotal", $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6),'-'));

//         $this->set_data_harta_bergerak($obj_dhb, $this->lwphpword);
//         $this->set_data_harta_tidak_bergerak($obj_dhtb, $this->lwphpword);

//         $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $output_filename);
//         $this->lwphpword->download($save_document_success->document_path, $output_filename);
        ////////////tutup word di tutup////////////

        if($datapn->JENIS_LAPORAN=='5'){
            $data_lhkpn_prev = $this->mglobal->get_data_by_id('t_lhkpn','id_lhkpn',$datapn->id_lhkpn_prev);
            $state_tanggal = $this->__is_cetak_var_not_blank(tgl_format($data_lhkpn_prev->tgl_kirim_final),'-');
            $state_jenis = $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($data_lhkpn_prev->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus');
            $state_khusus = $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($data_lhkpn_prev->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-');
            $jenis_laporan = 'Klarifikasi';
        }else{
            $state_tanggal = $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-');
            $state_jenis = $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus');
            $state_khusus = $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-');
            $jenis_laporan = 'LAINNYA';
        }

        /////////////////////////////PDF GENERATOR///////////////////////////

            $exp_tgl_kirim = explode('-', $datapn->tgl_kirim_final);
            $thn_kirim = $exp_tgl_kirim[0];

            $data = array(
                "DHB" => $obj_dhb,
                "DHTB" => $obj_dhtb,
                "NHK" => $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"],
                "NAMA_LENGKAP" => $this->__is_cetak_var_not_blank($datapn->NAMA_LENGKAP,'-'),
                "LEMBAGA" => $this->__is_cetak_var_not_blank($datapn->INST_NAMA,'-'),
                "BIDANG" => $this->__is_cetak_var_not_blank($datapn->BDG_NAMA,'-'),
                "JABATAN" => $this->__is_cetak_var_not_blank($datapn->NAMA_JABATAN,'-'),
                "UNIT_KERJA" => $this->__is_cetak_var_not_blank($datapn->UK_NAMA,'-'),
                "SUB_UNIT_KERJA" => $this->__is_cetak_var_not_blank($datapn->SUK_NAMA,'-'),
                "JENIS" => $state_jenis,
                "KHUSUS" => $state_khusus,
                "TANGGAL" => $state_tanggal,
                "JENIS_LAPORAN" => $jenis_laporan,
                "TAHUN" =>  $this->__is_cetak_var_not_blank(substr($tgl_lapor_new, 0, 4),'-'),
                "TGL_BN" => tgl_format($datapn->TGL_PNRI),
                "NO_BN" => $datapn->NOMOR_PNRI,
                "PENGESAHAN" => $this->__is_cetak_var_not_blank( tgl_format($datapn->TGL_BA_PENGUMUMAN),'-'),
                "STATUS" => $datapn->STATUS == '3' || $datapn->STATUS == '4' ? "LENGKAP" : "TIDAK LENGKAP",
                "HTB" => $datapn->T1 == NULL || $datapn->T1 == '0' ? "----" : number_rupiah($datapn->T1),
                "HB" => $datapn->T6 == NULL || $datapn->T6 == '0' ? "----" : number_rupiah($datapn->T6),
                "HBL" => $datapn->T5 == NULL || $datapn->T5 == '0' ? "----" : number_rupiah($datapn->T5),
                "SB" => $datapn->T2 == NULL || $datapn->T2 == '0' ? "----" : number_rupiah($datapn->T2),
                "KAS" => $datapn->T4 == NULL || $datapn->T4 == '0' ? "----" : number_rupiah($datapn->T4),
                "HL" => $datapn->T3 == NULL || $datapn->T3 == '0' ? "----" : number_rupiah($datapn->T3),
                "HUTANG" => $datapn->T7 == NULL || $datapn->T7 == '0' ? "----" : number_rupiah($datapn->T7),
                "TOTAL" => number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7),
                "subtotal" => $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6),'-'),
                "qr_code_name" => $qr_image_location,
                "TANGGAL_BAK" => $this->__is_cetak_var_not_blank(tgl_format($datapn->TGL_KLARIFIKASI),'-'),
                "TAHUN_KIRIM" => $thn_kirim
             );

            $this->load->library('pdfgenerator');

            $html = $this->load->view('announ/export_pdf/pengumuman', $data, true);
            $filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $data['NHK'];
            $method = "stream";
            $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait');
        /////////////////////////////TUTUP PDF GENERATOR///////////////////////////
            $temp_dir = APPPATH."../images/qrcode/";
            unlink($temp_dir.$qr_file);

//                    $save_done = FALSE;
//                    $generating_document = FALSE;
//                    $save_document_success = FALSE;
//                    $mail_sent = FALSE;
//                    while (!$save_done) {
//                        if (!$generating_document) {
//                            $save_document_success = $this->lwphpword->save_document(1, '', FALSE, $output_filename);
//                            $generating_document = TRUE;
//                        }
//                        if ($save_document_success) {
//                            $save_done = TRUE;
//                            rename($save_document_success, APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/" . $output_filename);
//                            $this->lwphpword->download($save_document_success->document_path, $output_filename);
//                            while (!$mail_sent) {
//                                if ("Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NIK . "_" . date('d-F-Y') . ".docx" == $output_filename) {
//                                    ob_start();
//                                    $NGHelper = new ng();
//                                    $NGHelper::mail_send($datapn->EMAIL, 'Pengumuman Harta Kekayaan PN', $message_pengumuman_pn, NULL, 'uploads/FINAL_LHKPN/' . $no_bap . '/' . $datapn->NIK . '/' . $output_filename);
//                                    $mail_sent = TRUE;
//                                    unset($NGHelper);
//                                    ob_end_clean();
//                                    flush();
//                                    ob_flush();
//                                }
//                            }
//                            $history = [
//                                        'ID_LHKPN'          => $id_lhkpn,
//                                        'ID_STATUS'         => '15',
//                                        'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
//                                        'USERNAME_PENERIMA' => $datapn->NIK,
//                                        'DATE_INSERT'       => date('Y-m-d H:i:s'),
//                                        'CREATED_IP'        => $this->input->ip_address()
//                                        ];
//                            $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
//                            ng::mail_send($datapn->EMAIL, 'Pengumuman Harta Kekayaan PN', $message_pengumuman_pn, NULL, 'uploads/FINAL_LHKPN/' . $no_bap . '/' . $datapn->NIK . '/' . $output_filename);
//                        }
//                    }
//                }
//            }
//        }
//        if ($this->db->trans_status() === FALSE) {
//            $this->db->trans_rollback();
//            echo 0;
//        } else {
//            $this->db->trans_commit();
//            echo $id_lhkpn;
//        }
    }

    public function genpdff($ID_BAP) {
        $this->load->model('mlhkpnkeluarga');
        $this->load->model('mlhkpn', '', TRUE);
        $this->load->model('mlhkpn_lampiran2', '', TRUE);
        $this->load->model('mlhkpndokpendukung', '', TRUE);

        $this->db->trans_begin();

        $head = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, NULL, '*', "SUBSTRING(md5(T_BA_PENGUMUMAN.ID_BAP), 6, 8) = '$ID_BAP'")[0];

        if ($head) {

            $nama = str_replace('/', '-', $head->NOMOR_BAP);

            $dir = './uploads/FINAL_LHKPN/' . $nama . '/';

            if (is_dir($dir) === false) {
                mkdir($dir);
            }

            $data = $this->mglobal->get_data_all('R_BA_PENGUMUMAN', NULL, NULL, '*', "SUBSTRING(md5(R_BA_PENGUMUMAN.ID_BAP), 6, 8) = '$ID_BAP'");

            $time = time();

            foreach ($data as $keyy) {

                if ($keyy->ID_LHKPN == null) {
                    show_error('invalid url', 404);
                    die('invalid url');
                }

                $joinMATA_UANG = [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'M_JENIS_HARTA', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA']
                ];
                $joinMU = [['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG']];
                $joinHARTA_TIDAK_BERGERAK = [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG']
                ];
                $where_eHARTA_TIDAK_BERGERAK = "SUBSTRING(md5(data.ID_LHKPN), 6, 8) = '$keyy->ID_LHKPN'";
                $selectHARTA_TIDAK_BERGERAK = 'ID_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, (SELECT NAME from M_AREA where IDPROV = data.PROV AND CAST(IDKOT as UNSIGNED) = data.KAB_KOT AND IDKEC = data.KEC AND IDKEL = data.KEL) as KEL, (SELECT NAME from M_AREA where IDPROV = data.PROV AND CAST(IDKOT as UNSIGNED) = data.KAB_KOT AND IDKEC = data.KEC LIMIT 1) AS KEC, data.KAB_KOT, data.PROV, data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';

                //jenis bukti
                $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI');
                $list_bukti = [];
                foreach ($jenis_bukti as $key) {
                    $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
                }
                //jenis Harta
                $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA');
                $list_harta = [];
                foreach ($jenis_HARTA as $key) {
                    $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
                }
                //jenis harta bergerak lain
                $list_harta_berhenti = [
                    '1' => 'Perabotan Rumah Tangga',
                    '2' => 'Barang Elektronik',
                    '3' => 'Perhiasan & Logam / Batu Mulia',
                    '4' => 'Barang Seni / Antik',
                    '5' => 'Persediaan',
                    '6' => 'Harta Bergerak Lainnya',
                ];
                //jenis harta surat berharga
                $list_harta_surat = [
                    '1' => 'Penyertaan Modal pada Badan Hukum',
                    '2' => 'Investasi',
                ];
                //jenis harta kas
                $list_harta_kas = [
                    '1' => 'Uang Tunai',
                    '2' => 'Deposite',
                    '3' => 'Giro',
                    '4' => 'Tabungan',
                    '5' => 'Lainnya',
                ];
                //jenis harta lainnya
                $list_harta_lain = [
                    '1' => 'Piutang',
                    '2' => 'Kerjasama Usaha yang Tidak Berbadan Hukum',
                    '3' => 'Hak Kekayaan Intelektual',
                    '4' => 'Sewa Jangaka Panjang Dibayar Dimuka',
                    '5' => 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan',
                ];

                $data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
                $data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');

                $this->data['list_harta'] = $list_harta;
                $this->data['LHKPN'] = @$this->mglobal->get_data_all(
                                'T_LHKPN', [
                            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN'],
                            ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                            ['table' => 'M_INST_SATKER inst', 'on' => 'jbt.LEMBAGA   =  inst.INST_SATKERKD'],
                            ['table' => 'M_UNIT_KERJA unke', 'on' => 'jbt.UNIT_KERJA   =  unke.UK_ID'],
                            ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID']
                                ], NULL, '*', "T_LHKPN.ID_LHKPN = '$keyy->ID_LHKPN'"
                        )[0];
                // display($this->data['LHKPN']);exit();
                $this->data['lhkpn_jbtn'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', [['table' => 'M_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN']], NULL, 'NAMA_JABATAN, TEXT_JABATAN_PUBLISH, IS_PRIMARY', "ID_LHKPN = '$keyy->ID_LHKPN'");
                $this->data['id_lhkpn'] = $this->data['LHKPN']->ID_LHKPN;

                $getPN = $this->mglobal->get_data_all('T_LHKPN', NULL, ['ID_LHKPN =' => $keyy->ID_LHKPN], '*, YEAR(TGL_LAPOR) AS THN')[0];
                $this->data['cekLHKPN'] = @$this->mglobal->get_data_all('T_LHKPN', NULL, ['ID_PN' => $getPN->ID_PN, 'YEAR(TGL_LAPOR) <' => $getPN->THN], '*', NULL, ['TGL_LAPOR', 'desc'])[0];
                // harta sebelumnya
                $this->data['hartirakSeb'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN], 'sum(NILAI_PELAPORAN) as sum_hartirakSeb')[0];
                $this->data['hargerSeb'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN], 'sum(NILAI_PELAPORAN) as sum_hargerSeb')[0];
                $this->data['harger3Seb'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN], 'sum(NILAI_PELAPORAN) as sum_harger3Seb')[0];
                $this->data['subergaSeb'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN], 'sum(NILAI_PELAPORAN) as sum_subergaSeb')[0];
                $this->data['kasekaSeb'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN], 'sum(NILAI_EQUIVALEN) as sum_kasekaSeb')[0];
                $this->data['harlinSeb'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN], 'sum(NILAI_PELAPORAN) as sum_harlinSeb')[0];
                $this->data['hutangSeb'] = $this->mglobal->get_data_all('T_LHKPN_HUTANG', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN], 'sum(SALDO_HUTANG) as sum_hutangSeb')[0];
                // echo $this->db->last_query();exit;
                // harta sesudahnya
                $this->data['hartirak'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_TIDAK_BERGERAK', 'NILAI_PELAPORAN', 'sum_hartirak')[0];
                $this->data['harger'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_BERGERAK', 'NILAI_PELAPORAN', 'sum_harger')[0];
                $this->data['harger2'] = @$this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_LAINNYA', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harger2')[0];
                // echo $this->db->last_query();exit;
                $this->data['harger3'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_BERGERAK_LAIN', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harger3')[0];
                $this->data['suberga'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_SURAT_BERHARGA', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_suberga')[0];
                $this->data['kaseka'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_KAS', "REPLACE(NILAI_EQUIVALEN,'.','')", 'sum_kaseka')[0];
                $this->data['harlin'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_LAINNYA', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harlin')[0];
                $this->data['_hutang'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HUTANG', 'SALDO_HUTANG', 'sum_hutang')[0];
                $this->data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
                $this->data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
                $this->data['DATA_PRIBADI'] = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, '*', "ID_LHKPN = '$keyy->ID_LHKPN'")[0];

                $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN';
                $joinJabatan = [
                    ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                ];
                $this->data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$keyy->ID_LHKPN'");

                $this->data['lembaga'] = @$this->mglobal->get_data_all('M_INST_SATKER', NULL, NULL, '*', NULL);
                $this->data['rinci_keluargas'] = $this->mlhkpnkeluarga->get_rincian($this->data['id_lhkpn']);
                $this->data['KELUARGAS'] = $this->mglobal->get_data_all('T_LHKPN_KELUARGA', NULL, NULL, '*', "ID_LHKPN = '$keyy->ID_LHKPN'");
                $this->data['HARTA_TIDAK_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, NULL, [$selectHARTA_TIDAK_BERGERAK, FALSE], $where_eHARTA_TIDAK_BERGERAK);
                $this->data['HARTA_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$keyy->ID_LHKPN'");
                $this->data['HARTA_BERGERAK_LAINS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinMU, NULL, '*', "ID_LHKPN = '$keyy->ID_LHKPN'");
                $this->data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinMATA_UANG, NULL, "*,REPLACE(NILAI_PELAPORAN,'.','') as PELAPORAN", "ID_LHKPN = '$keyy->ID_LHKPN'");
                $this->data['HARTA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$keyy->ID_LHKPN'");
                $this->data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinMU, NULL, '*', "ID_LHKPN = '$keyy->ID_LHKPN'");
                // display($this->data['HARTA_LAINNYAS']);exit();
                $this->data['HUTANGS'] = $this->mglobal->get_data_all('T_LHKPN_HUTANG', NULL, NULL, '*', "ID_LHKPN = '$keyy->ID_LHKPN'");
                $this->data['PENERIMAAN_KASS'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
                $this->data['PENGELUARAN_KASS'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
                $this->data['lamp2s'] = $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*', "ID_LHKPN = '$keyy->ID_LHKPN'");
                $this->data['keluargas'] = $this->mlhkpnkeluarga->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $keyy->ID_LHKPN))->result();
                $this->data['dokpendukungs'] = $this->mlhkpndokpendukung->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $keyy->ID_LHKPN))->result();
                $this->data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);

                //select lampiran pelepasan
                $selectlampiranpelepasan = 'A.TANGGAL_TRANSAKSI as TANGGAL_TRANSAKSI, A.NILAI_PELEPASAN as NILAI_PELEPASAN, A.NAMA as NAMA, A.ALAMAT as ALAMAT';
                $selectpelepasanhartatidakbergerak = ', B.ATAS_NAMA as ATAS_NAMA, B.LUAS_TANAH as LUAS_TANAH, B.LUAS_BANGUNAN as LUAS_BANGUNAN, B.NOMOR_BUKTI as NOMOR_BUKTI, B.JENIS_BUKTI as JENIS_BUKTI ';
                $selectpelepasanhartabergerak = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA as ATAS_NAMA, B.MEREK as MEREK, B.NOPOL_REGISTRASI as NOPOL_REGISTRASI, B.NOMOR_BUKTI as NOMOR_BUKTI';
                $selectpelepasanhartabergeraklain = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, ATAS_NAMA as ATAS_NAMA';
                $selectpelepasansuratberharga = ', B.KODE_JENIS as KODE_JENIS, B.NAMA_SURAT_BERHARGA as NAMA_SURAT,  B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, B.ATAS_NAMA as ATAS_NAMA';
                $selectpelepasankas = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA_REKENING as ATAS_NAMA, B.NAMA_BANK as NAMA_BANK, B.NOMOR_REKENING as NOMOR_REKENING';
                $selectpelepasanhartalainnya = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.ATAS_NAMA as ATAS_NAMA';

                // call data lampiran pelepasan
                $pelepasanhartatidakbergerak = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK as A', [['table' => 'T_LHKPN_HARTA_TIDAK_BERGERAK as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartatidakbergerak, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$keyy->ID_LHKPN'");
                $pelepasanhartabergerak = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK as A', [['table' => 'T_LHKPN_HARTA_BERGERAK as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartabergerak, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$keyy->ID_LHKPN'");
                $pelepasanhartabergeraklain = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN as A', [['table' => 'T_LHKPN_HARTA_BERGERAK_LAIN as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartabergeraklain, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$keyy->ID_LHKPN'");
                $pelepasansuratberharga = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA as A', [['table' => 'T_LHKPN_HARTA_SURAT_BERHARGA as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasansuratberharga, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$keyy->ID_LHKPN'");
                $pelepasankas = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_KAS as A', [['table' => 'T_LHKPN_HARTA_KAS as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasankas, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$keyy->ID_LHKPN'");
                $pelepasanhartalainnya = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_LAINNYA as A', [['table' => 'T_LHKPN_HARTA_LAINNYA as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartalainnya, "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$keyy->ID_LHKPN'");
                $pelepasan = [];

                //packing hasil calling data lampiran pelepasan
                if (!empty($pelepasanhartatidakbergerak)) {
                    foreach ($pelepasanhartatidakbergerak as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => 'Tanah / Bangunan',
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => "Tanah/Bangunan , Atas Nama " . @$key->ATAS_NAMA . " dengan luas tanah " . @$key->LUAS_TANAH . " dan luas bangunan " . @$key->LUAS_BANGUNAN . " dengan bukti berupa " . $list_bukti[$key->JENIS_BUKTI] . " dengan nomor bukti " . @$key->NOMOR_BUKTI,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                        ];
                    }
                }
                if (!empty($pelepasanhartabergerak)) {
                    foreach ($pelepasanhartabergerak as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => 'Mesin / Alat transport',
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => "Sebuah " . $list_harta[@$key->KODE_JENIS] . " , Atas Nama " . @$key->ATAS_NAMA . " , merek " . @$key->MEREK . " dengan nomor registrasi " . $key->NOPOL_REGISTRASI . " dan nomor bukti " . @$key->NOMOR_BUKTI,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                        ];
                    }
                }
                if (!empty($pelepasanhartabergeraklain)) {
                    foreach ($pelepasanhartabergeraklain as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => 'Harta bergerak',
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => $list_harta_berhenti[@$key->KODE_JENIS] . " bernama " . @$key->NAMA_HARTA . " , Atas nama " . @$key->ATAS_NAMA . " dengan jumlah " . @$key->JUMLAH . ' ' . @$key->SATUAN,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                        ];
                    }
                }
                if (!empty($pelepasansuratberharga)) {
                    foreach ($pelepasansuratberharga as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => 'Surat berharga',
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => $list_harta_surat[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' berupa surat ' . @$key->NAMA_SURAT . ' dengan jumlah ' . @$key->JUMLAH . ' ' . @$key->SATUAN,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                        ];
                    }
                }
                if (!empty($pelepasankas)) {
                    foreach ($pelepasankas as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => 'KAS / Setara KAS',
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => "KAS berupa " . $list_harta_kas[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' pada bank ' . @$key->NAMA_BANK . ' dengan nomor rekening ' . @$key->NOMOR_REKENING,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                        ];
                    }
                }
                if (!empty($pelepasanhartalainnya)) {
                    foreach ($pelepasanhartalainnya as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => 'Harta lainnya',
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => "Harta lain berupa " . $list_harta_lain[@$key->KODE_JENIS] . ' dengan nama harta ' . @$key->NAMA_HARTA . ' atas nama ' . @$key->ATAS_NAMA,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                        ];
                    }
                }

                $this->data['lampiran_pelepasan'] = $pelepasan;

                //perhitunganpengeluaran kas
                $whereperhitunganpengeluaran = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$keyy->ID_LHKPN'";
                $this->data['getPenka'] = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS', $whereperhitunganpengeluaran);

                //perhitunganpemaasukan kas
                $whereperhitunganpemaasukan = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$keyy->ID_LHKPN' ";
                $this->data['getPemka'] = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $whereperhitunganpemaasukan);

                $this->data['lampiran_hibah'] = $this->_lampiran_hibah($keyy->ID_LHKPN);

                $html = $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_genpdf', $this->data, true);
                $html2 = $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_genpdf' . '2', $this->data, true);

                ob_clean();

                $this->load->library('pdf');

                $pdf = $this->pdf->load();

//                $pdf->SetFooter('|{PAGENO}|'); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
                $pdf->SetFooter(FALSE);
                $pdf->WriteHTML($html); // write the HTML into the PDF
                $pdf->AddPage();
                $pdf->WriteHTML($html2);

                // $pdf->Output('uploads/FINAL_LHKPN/'.$dir,'F');

                $pdf->Output('uploads/FINAL_LHKPN/' . $nama . '/' . $time . '-' . $keyy->ID_LHKPN . '.pdf', '');
            }
        }
        if ($this->db->trans_status() !== FALSE) {

            $this->db->trans_commit();

            $arr = array('hasil' => 1, 'name' => $nama);
        } else {
            $this->db->trans_rollback();
            $arr = array('hasil' => 0);
        }

        echo json_encode($arr);

        // intval($this->db->trans_status());
    }

    public function BeforeAnnoun($id_lhkpn, $id_bap = NULL) {
        $datapn = $this->mglobal->get_data_all(
                        'T_LHKPN', [
                    ['table' => 't_lhkpn_data_pribadi', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 't_lhkpn_data_pribadi.ID_LHKPN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_JABATAN m_jbt', 'on' => 'm_jbt.ID_JABATAN   =  jbt.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'm_jbt.INST_SATKERKD   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'm_jbt.UK_ID   =  unke.UK_ID'],
                    ['table' => 'M_SUB_UNIT_KERJA subunke', 'on' => 'm_jbt.SUK_ID   =  subunke.SUK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
                    ['table' => 'T_LHKPN_HUTANG', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN AND T_LHKPN_HUTANG.IS_ACTIVE = 1'],
                    ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
                    ['table' => 'T_USER', 'on' => 'T_USER.USERNAME = T_PN.NIK'],
                    ['table' => 'r_ba_pengumuman rba', 'on' => 'T_LHKPN.ID_LHKPN = rba.ID_LHKPN'],
                    ['table' => 't_ba_pengumuman tba', 'on' => 'rba.ID_BAP = tba.ID_BAP'],
                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                        ], NULL, "t_lhkpn.id_lhkpn_prev, t_lhkpn_data_pribadi.*, jbt.ALAMAT_KANTOR, jbt.DESKRIPSI_JABATAN, m_jbt.NAMA_JABATAN,, T_PN.NIK, T_PN.ID_PN,  inst.INST_NAMA, T_PN.TGL_LAHIR, T_PN.NHK,T_PN.NAMA, SUM(T_LHKPN_HUTANG.SALDO_HUTANG) AS jumhut, T_LHKPN.TGL_LAPOR, T_LHKPN.tgl_kirim_final, T_LHKPN.JENIS_LAPORAN, T_LHKPN.STATUS, bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA, T_USER.ID_USER, IF (T_LHKPN.JENIS_LAPORAN = '4', 'Periodik', 'Khusus') AS JENIS, T_USER.EMAIL, tba.TGL_BA_PENGUMUMAN, subunke.SUK_NAMA, T_LHKPN.TGL_KLARIFIKASI,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
                        (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6,
                        (SELECT SUM(SALDO_HUTANG) FROM t_lhkpn_hutang WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') T7 ", "T_LHKPN.ID_LHKPN = '$id_lhkpn' AND jbt.IS_PRIMARY = '1'", NULL, 0, NULL, "T_LHKPN.ID_LHKPN"
                )[0];
        if($datapn->JENIS_LAPORAN=='5'){
            $data_lhkpn_prev = $this->mglobal->get_data_by_id('t_lhkpn','id_lhkpn',$datapn->id_lhkpn_prev)[0];
            if ($data_lhkpn_prev->TGL_LAPOR == '1970-01-01' || $data_lhkpn_prev->TGL_LAPOR == '' || $data_lhkpn_prev->TGL_LAPOR == '-') {
                $tgl_lapor_new = $data_lhkpn_prev->tgl_kirim_final;
            }
            else{
                $tgl_lapor_new = $data_lhkpn_prev->TGL_LAPOR;
            }
            $state_tanggal = $this->__is_cetak_var_not_blank(tgl_format($data_lhkpn_prev->tgl_kirim_final),'-');
            $state_jenis = $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($data_lhkpn_prev->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus');
            $state_khusus = $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($data_lhkpn_prev->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-');
            $jenis_laporan = 'Klarifikasi';
        }else{
            if ($datapn->TGL_LAPOR == '1970-01-01' || $datapn->TGL_LAPOR == '' || $datapn->TGL_LAPOR == '-') {
                $tgl_lapor_new = $datapn->tgl_kirim_final;
            }
            else{
                $tgl_lapor_new = $datapn->TGL_LAPOR;
            }
            $state_tanggal = $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-');
            $state_jenis = $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus');
            $state_khusus = $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-');
            $jenis_laporan = 'LAINNYA';
        }
        $this->data['dt_harta_tidak_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_tidak_bergerak", [
            ['table' => 'm_negara ', 'on' => 'm_negara.ID   = ' . 't_lhkpn_harta_tidak_bergerak.ID_NEGARA'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_tidak_bergerak.ASAL_USUL'],
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   IN ' . '(t_lhkpn_harta_tidak_bergerak.PEMANFAATAN)']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_tidak_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_tidak_bergerak.IS_ACTIVE = '1'", "*, GROUP_CONCAT(DISTINCT m_pemanfaatan.PEMANFAATAN) as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_tidak_bergerak.ID");

        $this->data['dt_harta_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_bergerak", [
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   = t_lhkpn_harta_bergerak.PEMANFAATAN'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_bergerak.ASAL_USUL'],
            ['table' => 'm_jenis_harta ', 'on' => 'm_jenis_harta.ID_JENIS_HARTA   = t_lhkpn_harta_bergerak.KODE_JENIS']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_bergerak.IS_ACTIVE = '1'", "*, m_pemanfaatan.PEMANFAATAN as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_bergerak.ID");

        $this->data['datapn'] = $datapn;
        $th = date('Y');

        $arr_dhb = array();
        $arr_dhtb = array();
        foreach ($this->data['dt_harta_bergerak'] as $data) {
            $arr_dhb[] = $data->NAMA . ', ' . $data->MEREK . ' ' . $data->MODEL . ' Tahun ' . $data->TAHUN_PEMBUATAN . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
        }
        foreach ($this->data['dt_harta_tidak_bergerak'] as $data) {
            // $tmp = $data->NEGARA == 2 ? $data->JALAN . ', ' . $data->NAMA_NEGARA : $data->KAB_KOT;
            $tmp = $data->NEGARA == 2 ? 'NEGARA '.$data->NAMA_NEGARA : 'KAB / KOTA '.$data->KAB_KOT;
            if ($data->LUAS_TANAH == NULL || $data->LUAS_TANAH == '') {
                $luas_tanah = '-';
            } else {
                $luas_tanah = number_format($data->LUAS_TANAH, 0, ",", ".");
            }
            if ($data->LUAS_BANGUNAN == NULL || $data->LUAS_BANGUNAN == '') {
                $luas_bangunan = '-';
            } else {
                $luas_bangunan = number_format($data->LUAS_BANGUNAN, 0, ",", ".");
            }
            if ($data->LUAS_BANGUNAN !== "0" && $data->LUAS_TANAH !== "0") {
                $arr_dhtb[] = 'Tanah dan Bangunan Seluas ' . $luas_tanah . ' m2/' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else if ($data->LUAS_TANAH !== "0" && $data->LUAS_BANGUNAN == "0") {
                $arr_dhtb[] = 'Tanah Seluas ' . $luas_tanah . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else {
                $arr_dhtb[] = 'Bangunan Seluas ' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            }
        }

        $arr_all_data = array(
            'nama' => $datapn->NAMA,
            'jabatan' => $datapn->DESKRIPSI_JABATAN,
            'nhk' => $datapn->NHK,
            'tempat_tgl_lahir' => $datapn->TGL_LAHIR,
            'alamat_kantor' => $datapn->ALAMAT_KANTOR,
            'tgl_pelaporan' => $datapn->TGL_LAPOR,
            'nilai_hutang' => $datapn->jumhut,
            'nilai_hl' => $datapn->T3,
            'nilai_kas' => $datapn->T4,
            'nilai_surga' => $datapn->T2,
            'hbl' => $datapn->T5,
            'hb' => $arr_dhb,
            'htb' => $arr_dhtb,
        );

        $obj_dhb = (object) $arr_dhb;
        $obj_dhtb = (object) $arr_dhtb;

        $this->db->trans_begin();

        if ($datapn->STATUS == '3' || $datapn->STATUS == '4')
            $sts = '4';
        else if ($datapn->STATUS == '5' || $datapn->STATUS == '6')
            $sts = '6';

        $data_lhkpn = array('STATUS' => $sts);
        $max_nhk = $datapn->NHK;

        $data_ba = array(
            'STATUS_CETAK_PENGUMUMAN_PDF' => 1
        );


        $this->data['nhk'] = $max_nhk;
        $data_pn = array(
            'NHK' => $max_nhk
        );

        $no_bap = str_replace("/", "_", $datapn->NOMOR_BAP);
        $output_filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NHK . ".pdf";

        // $this->load->library('lwphpword/lwphpword', array(
        //     "base_path" => APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
        //     "base_url" => base_url() . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
        //     "base_root" => base_url(),
        // ));
        // if ($datapn->JENIS_LAPORAN == '5') {
        //     $template_file = "../file/template/FormatPengumuman-Pemeriksaan.docx"; 
        // } else {
        //     $template_file = "../file/template/FormatPengumuman.docx";
        // }

        $this->load->library('lws_qr', [
            "model_qr" => "Cqrcode",
            "model_qr_prefix_nomor" => "PHK-ELHKPN-",
            "callable_model_function" => "insert_cqrcode_with_filename",
            "temp_dir" => APPPATH . "../images/qrcode/" //hanya untuk production
        ]);

        $filename_bap = 'uploads/FINAL_LHKPN/' . $no_bap . "/" . $datapn->NIK;
        $dir_bap = './uploads/FINAL_LHKPN/' . $no_bap . '/';

        if (!is_dir($filename_bap)) {
            if (is_dir($dir_bap) === false) {
                mkdir($dir_bap);
            }
        }

//            if (is_dir($dir_bap) == TRUE) {
        $filename = $dir_bap . $datapn->NIK . "/$output_filename";

//                if (!file_exists($filename)) {
        $dir = $dir_bap . $datapn->NIK . '/';

        // if (is_dir($dir) === false) {
        //     mkdir($dir);
        // }
        $qr_content_data = json_encode((object) [
                    "data" => [
                        (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $datapn->NAMA_LENGKAP],
                        (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]],
                        (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $datapn->BDG_NAMA],
                        (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $datapn->NAMA_JABATAN],
                        (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $datapn->INST_NAMA],
                        (object) ["tipe" => '1', "judul" => "SUB_UNIT_KERJA", "isi" => $datapn->SUK_NAMA],
                        (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus')) . " - " . show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->TGL_LAPOR, tgl_format($datapn->TGL_LAPOR))],
                        (object) ["tipe" => '1', "judul" => "Tanggal Pelaporan", "isi" => tgl_format($datapn->TGL_LAPOR)],
                        (object) ["tipe" => '1', "judul" => "Tanggal Kirim Final", "isi" => tgl_format($datapn->tgl_kirim_final)],
                        (object) ["tipe" => '1', "judul" => "Tanah dan Bangunan", "isi" => $datapn->T1 == NULL ? "----" : number_rupiah($datapn->T1)],
                        (object) ["tipe" => '1', "judul" => "Alat Transportasi dan Mesin", "isi" => $datapn->T6 == NULL ? "----" : number_rupiah($datapn->T6)],
                        (object) ["tipe" => '1', "judul" => "Harta Bergerak Lainnya", "isi" => $datapn->T5 == NULL ? "----" : number_rupiah($datapn->T5)],
                        (object) ["tipe" => '1', "judul" => "Surat Berharga", "isi" => $datapn->T2 == NULL ? "----" : number_rupiah($datapn->T2)],
                        (object) ["tipe" => '1', "judul" => "Kas dan Setara Kas", "isi" => $datapn->T4 == NULL ? "----" : number_rupiah($datapn->T4)],
                        (object) ["tipe" => '1', "judul" => "Harta Lainnya", "isi" => $datapn->T3 == NULL ? "----" : number_rupiah($datapn->T3)],
                        (object) ["tipe" => '1', "judul" => "Hutang", "isi" => $datapn->T7 == NULL ? "----" : number_rupiah($datapn->T7)],
                        (object) ["tipe" => '1', "judul" => "Total Harta Kekayaan", "isi" => number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7) == NULL ? "----" : number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7)],
                    ],
                    "encrypt_data" => $id_lhkpn . "phk",
                    "id_lhkpn" => $id_lhkpn,
                    "judul" => "Pengumuman Harta Kekayaan Penyelenggara Negara",
                    "tgl_surat" => date('Y-m-d'),
        ]);

        $qr_file = "tes_qr2-" . $id_lhkpn . "-" . date('Y-m-d_H-i-s') . ".png";
        $qr_image_location = $this->lws_qr->create($qr_content_data, $qr_file);

        /////////////////////////////WORD GENERATOR///////////////////////////
//         $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image1.jpeg" => $qr_image_location));

//         $this->lwphpword->save_path = APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/";

//         $this->lwphpword->set_value("NHK", $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]);
//         $this->lwphpword->set_value("NAMA_LENGKAP", $this->__is_cetak_var_not_blank($datapn->NAMA_LENGKAP,'-'));
//         $this->lwphpword->set_value("LEMBAGA", $this->__is_cetak_var_not_blank($datapn->INST_NAMA,'-'));
//         $this->lwphpword->set_value("BIDANG", $this->__is_cetak_var_not_blank($datapn->BDG_NAMA,'-'));
//         $this->lwphpword->set_value("JABATAN", $this->__is_cetak_var_not_blank($datapn->NAMA_JABATAN,'-'));
//         $this->lwphpword->set_value("UNIT_KERJA", $this->__is_cetak_var_not_blank($datapn->UK_NAMA,'-'));
//         $this->lwphpword->set_value("SUB_UNIT_KERJA", $this->__is_cetak_var_not_blank($datapn->SUK_NAMA,'-'));
//         $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus'));
//         $this->lwphpword->set_value("KHUSUS", $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-'));
//         $this->lwphpword->set_value("TANGGAL", $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-'));
//         $this->lwphpword->set_value("TAHUN", $this->__is_cetak_var_not_blank(substr($tgl_lapor_new, 0, 4),'-'));
// //                    $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
//         $this->lwphpword->set_value("TGL_BN", tgl_format($datapn->TGL_PNRI));
//         $this->lwphpword->set_value("NO_BN", $datapn->NOMOR_PNRI);
//         $this->lwphpword->set_value("PENGESAHAN", tgl_format($datapn->TGL_BA_PENGUMUMAN));
//         $this->lwphpword->set_value("STATUS", $datapn->STATUS == '3' || $datapn->STATUS == '4' ? "LENGKAP" : "TIDAK LENGKAP");
//         $this->lwphpword->set_value("TANGGAL_BAK", $this->__is_cetak_var_not_blank(tgl_format($datapn->TGL_KLARIFIKASI),'-'));

//         $this->lwphpword->set_value("HTB", $datapn->T1 == NULL || $datapn->T1 == '0' ? "----" : number_rupiah($datapn->T1));
//         $this->lwphpword->set_value("HB", $datapn->T6 == NULL || $datapn->T6 == '0' ? "----" : number_rupiah($datapn->T6));
//         $this->lwphpword->set_value("HBL", $datapn->T5 == NULL || $datapn->T5 == '0' ? "----" : number_rupiah($datapn->T5));
//         $this->lwphpword->set_value("SB", $datapn->T2 == NULL || $datapn->T2 == '0' ? "----" : number_rupiah($datapn->T2));
//         $this->lwphpword->set_value("KAS", $datapn->T4 == NULL || $datapn->T4 == '0' ? "----" : number_rupiah($datapn->T4));
//         $this->lwphpword->set_value("HL", $datapn->T3 == NULL || $datapn->T3 == '0' ? "----" : number_rupiah($datapn->T3));
//         $this->lwphpword->set_value("HUTANG", $datapn->T7 == NULL || $datapn->T7 == '0' ? "----" : number_rupiah($datapn->T7));
//         $this->lwphpword->set_value("TOTAL", number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7));
//         $this->lwphpword->set_value("subtotal", $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6),'-'));

//         $this->set_data_harta_bergerak($obj_dhb, $this->lwphpword);
//         $this->set_data_harta_tidak_bergerak($obj_dhtb, $this->lwphpword);

//         $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $output_filename);
//         $this->lwphpword->download($save_document_success->document_path, $output_filename);
        /////////////////////////////TUTUP WORD GENERATOR///////////////////////////

        /////////////////////////////PDF GENERATOR///////////////////////////

            $exp_tgl_kirim = explode('-', $datapn->tgl_kirim_final);
            $thn_kirim = $exp_tgl_kirim[0];
        
            $data = array(
                "DHB" => $obj_dhb,
                "DHTB" => $obj_dhtb,
                "NHK" => $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"],
                "NAMA_LENGKAP" => $this->__is_cetak_var_not_blank($datapn->NAMA_LENGKAP,'-'),
                "LEMBAGA" => $this->__is_cetak_var_not_blank($datapn->INST_NAMA,'-'),
                "BIDANG" => $this->__is_cetak_var_not_blank($datapn->BDG_NAMA,'-'),
                "JABATAN" => $this->__is_cetak_var_not_blank($datapn->NAMA_JABATAN,'-'),
                "UNIT_KERJA" => $this->__is_cetak_var_not_blank($datapn->UK_NAMA,'-'),
                "SUB_UNIT_KERJA" => $this->__is_cetak_var_not_blank($datapn->SUK_NAMA,'-'),
                "JENIS" => $state_jenis,
                "KHUSUS" => $state_khusus,
                "TANGGAL" => $state_tanggal,
                "JENIS_LAPORAN" => $jenis_laporan,
                "TAHUN" =>  $this->__is_cetak_var_not_blank(substr($tgl_lapor_new, 0, 4),'-'),
                "TGL_BN" => tgl_format($datapn->TGL_PNRI),
                "NO_BN" => $datapn->NOMOR_PNRI,
                "PENGESAHAN" => $this->__is_cetak_var_not_blank( tgl_format($datapn->TGL_BA_PENGUMUMAN),'-'),
                "STATUS" => $datapn->STATUS == '3' || $datapn->STATUS == '4' ? "LENGKAP" : "TIDAK LENGKAP",
                "HTB" => $datapn->T1 == NULL || $datapn->T1 == '0' ? "----" : number_rupiah($datapn->T1),
                "HB" => $datapn->T6 == NULL || $datapn->T6 == '0' ? "----" : number_rupiah($datapn->T6),
                "HBL" => $datapn->T5 == NULL || $datapn->T5 == '0' ? "----" : number_rupiah($datapn->T5),
                "SB" => $datapn->T2 == NULL || $datapn->T2 == '0' ? "----" : number_rupiah($datapn->T2),
                "KAS" => $datapn->T4 == NULL || $datapn->T4 == '0' ? "----" : number_rupiah($datapn->T4),
                "HL" => $datapn->T3 == NULL || $datapn->T3 == '0' ? "----" : number_rupiah($datapn->T3),
                "HUTANG" => $datapn->T7 == NULL || $datapn->T7 == '0' ? "----" : number_rupiah($datapn->T7),
                "TOTAL" => number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7),
                "subtotal" => $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6),'-'),
                "qr_code_name" => $qr_image_location,
                "TANGGAL_BAK" => $this->__is_cetak_var_not_blank(tgl_format($datapn->TGL_KLARIFIKASI),'-'),
                "TAHUN_KIRIM" => $thn_kirim
             );

            $this->load->library('pdfgenerator');

            $html = $this->load->view('announ/export_pdf/pengumuman', $data, true);
            $filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $data['NHK'];
            $method = "stream";
            $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait');
        /////////////////////////////TUTUP PDF GENERATOR///////////////////////////
            $temp_dir = APPPATH."../images/qrcode/";
            unlink($temp_dir.$qr_file);

    }

}
