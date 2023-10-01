<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)


 * Controller Lhkpnoffline
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Efill/Controllers/Lhkpnoffline
 */
// call php-spreadsheet library
require_once APPPATH.'/third_party/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lhkpnoffline extends MY_Controller {

    // num of records per page
    public $limit = 10;
    public $iscetak = false;
    private $default_imp_xl_foto_pribadi = NULL;
    private $imported_data = NULL;
    public $templateDir = 'templates/adminlte/';

    public function __construct() {
        parent::__construct();
        $this->load->model('mglobal');
        $this->config->load('harta');
        call_user_func('ng::islogin');
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);

        // prepare search
        foreach ((array) @$this->input->post('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];
        }


        $this->load->model(array(
            "Mlhkpnofflinepenerimaan",
            "Mimpxllhkpn",
            "Magama"
        ));
    }

    private function __index_penerimaan() {
        $this->db->start_cache();
        $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
					T_PN.NIK,
					T_PN.NAMA, IS_DITERIMA_KOORD_CS,
					(SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
					(SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
					(SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
				');
        $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
        $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
        $this->db->join('T_USER', 'T_LHKPNOFFLINE_PENERIMAAN.CREATED_BY = T_USER.USERNAME', 'LEFT');
        // $this->db->where('1=1', null, false);
        //$this->db->where('T_LHKPNOFFLINE_PENERIMAAN.USERNAME', $this->session->userdata('USR'));

        $t_pelaporan = ['YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN)', 'T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN'];

//				for ($i=0; $i < 1; $i++) {

        if (@$this->CARI['IS_ACTIVE'] == 99) {
            // all
        } else if (@$this->CARI['IS_ACTIVE'] == -1) {
            // deleted
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_ACTIVE', -1);
        } else if (@$this->CARI['IS_ACTIVE']) {
            // by status
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_ACTIVE', $this->CARI['IS_ACTIVE']);
        } else {
            // active
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_ACTIVE', 1);
        }

        $this->db->where("T_LHKPNOFFLINE_PENERIMAAN.IS_REPLACED <> '1'");

        /**
          if (!empty($this->CARI['BULAN'])) {
          if ($this->CARI['BULAN'] != 99) {
          $this->db->where('MONTH(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN) = ' . $this->CARI['BULAN'], null, false);
          }
          } else {
          $this->db->where('MONTH(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN) = ' . date('m'), null, false);
          }

          if (!empty($this->CARI['TAHUN'])) {
          if ($this->CARI['TAHUN'] != 99) {
          $this->db->where('YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN) = ' . $this->CARI['TAHUN'], null, false);
          }
          } else {
          $this->db->where('YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN) = ' . date('Y'), null, false);
          }



          if (!empty($this->CARI['TAHUN_LAPOR'])) {
          if ($this->CARI['TAHUN_LAPOR'] != 99) {
          $this->db->where('YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN) = ' . $this->CARI['TAHUN_LAPOR'], null, false);
          }
          }
         */
        if (!empty($this->CARI['LEMBAGA'])) {
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA', $this->CARI['LEMBAGA']);
        }

        if (empty($this->CARI['STATUS'])) {
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_SEND', 0);
        }
        else{
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_SEND', $this->CARI['STATUS']);
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN !=', NULL);
        }
        // $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_DITERIMA_KOORD_CS = "' . $this->CARI['STATUS'] . '"', null, false);

        // }
        //  else {
        // 	($i == 0) ? $this->db->where($t_pelaporan[$i].' = '.date('Y'), null, false) : $this->db->or_where($t_pelaporan[$i].' = '.date('Y'), null, false);
        // }

        if (@$this->CARI['TEXT']) {
            if ($this->CARI['BY'] == 'PN') {
                $this->db->like('T_LHKPNOFFLINE_PENERIMAAN.USERNAME', $this->CARI['TEXT']);
            } else if ($this->CARI['BY'] == 'CREATED_BY') {
                $sql_where_by_created_by = "(t_user.username LIKE '%" . $this->CARI['TEXT'] . "%' OR "
                        . "t_user.nama LIKE '%" . $this->CARI['TEXT'] . "%')";
//                $this->db->like('T_USER.USERNAME', $this->CARI['TEXT']);
                $this->db->where($sql_where_by_created_by);
            } else if ($this->CARI['BY'] == 'NAMA') {
                $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
            } else if ($this->CARI['BY'] == 'NIK') {
                $this->db->like('T_PN.NIK', $this->CARI['TEXT']);
            }
        }
//				}

        if (!empty($this->CARI['ORDER'])) {
            if ($this->CARI['ORDER'] == 'NAMA') {
                $this->db->order_by('T_PN.NAMA', $this->CARI['ORDER_TYPE']);
            } else if ($this->CARI['ORDER'] == 'NIK') {
                $this->db->order_by('T_PN.NIK', $this->CARI['ORDER_TYPE']);
            } else if ($this->CARI['ORDER'] == 'TGL_TERIMA') {
                $this->db->order_by('T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN', $this->CARI['ORDER_TYPE']);
            }
        } else {
            $this->db->order_by('T_LHKPNOFFLINE_PENERIMAAN.CREATED_TIME', 'desc');
        }

        $this->db->where('(T_LHKPNOFFLINE_PENERIMAAN.IS_ACTIVE = 1 OR T_LHKPNOFFLINE_PENERIMAAN.IS_ACTIVE IS NULL)');

        $this->total_rows = $this->db->get('')->num_rows();
        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();

        $this->end = $query->num_rows();

        $this->db->flush_cache();

        $this->data['id'] = $this->input->post('id');

        if ($this->data['id'] != '') {
            $this->db->start_cache();

            $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
					T_PN.NIK,
					T_PN.NAMA, IS_DITERIMA_KOORD_CS,
					(SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
					(SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
					(SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
				');
            $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
            $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
            $this->db->where('1=1', null, false);
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN IN (' . $this->data['id'] . ')');
            $query = $this->db->get('');
            $this->data['item_selected'] = $query->result();
            $this->db->flush_cache();
        }

        $this->data["lhkpn_offline_melalui"] = array_flip($this->config->item('lhkpn_offline_melalui', 'harta'));

        /**
         * View ada di lhkpnoffline/lhkpnoffline_penerimaan_index
         */
    }

    private function listingsk()
    {
        $this->base_url = 'index.php/efill/lhkpnoffline/index/listingsk/';

        $cari = $this->input->post('CARI[NAMA]');
        $this->data = [];
        $this->data['title'] = 'Listing SK';
        $this->data['icon'] = 'fa-list';
        $this->data['thisPageUrl'] = $this->base_url;
        $this->data['content_js'] = $this->load->view($this->templateDir . 'template_js', $this->data, true);
        $this->data['content_list'] = $this->load->view(strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_listingsk', $this->data, true);
    }

    private function __index_listingsk()
    {
        $this->base_url = 'index.php/efill/lhkpnoffline/index/listingsk/';

        $cari = $this->input->post('CARI[NAMA]');
    
        $this->data = [];
        $this->data['title'] = 'Listing SK';
        $this->data['icon'] = 'fa-list';
        $this->data['thisPageUrl'] = $this->base_url;

        if (empty($cari)) {
            $this->total_rows = 0;
        }else{
        $this->load->model('Mlhkpn');
        $getPN =  $this->Mlhkpn->listingSK($cari);
        // display($getPN,1);
        $this->db->select('
                    t_lhkpn.STATUS,
                    t_lhkpn.entry_via,
                    t_lhkpn.ID_LHKPN,
                    t_pn.NIK,
                    t_pn.NAMA,
                    -- CEIL(DATEDIFF(NOW(),t_pn.TGL_LAHIR)/365)-1 AS UMUR,
                    -- CEIL(DATEDIFF(t_lhkpn.TGL_KIRIM_FINAL,t_pn.TGL_LAHIR)/365)-1 AS UMUR_LAPOR
                    TIMESTAMPDIFF(YEAR,t_pn.TGL_LAHIR,NOW()) AS UMUR,
                    TIMESTAMPDIFF(YEAR,t_pn.TGL_LAHIR,t_lhkpn.tgl_lapor) AS UMUR_LAPOR,
                    t_lhkpn_data_pribadi.FLAG_SK
                ', false);

                $this->db->join('t_lhkpn', 't_lhkpn.ID_PN = t_pn.ID_PN', 'left');
                $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_data_pribadi.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
                $this->db->where('t_lhkpn.ID_LHKPN', $getPN->ID_LHKPN);
                $data_pn = $this->db->get('t_pn')->result();
                
                $this->db->select('
                t_lhkpn_keluarga.NIK,
                t_lhkpn_keluarga.NAMA,
                t_lhkpn_keluarga.ID_KELUARGA,
                -- CEIL(DATEDIFF(NOW(),t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1 AS UMUR,
                -- CEIL(DATEDIFF(t_lhkpn.TGL_KIRIM_FINAL,t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1 AS UMUR_LAPOR
                TIMESTAMPDIFF(YEAR,t_lhkpn_keluarga.TANGGAL_LAHIR,NOW()) AS UMUR,
                TIMESTAMPDIFF(YEAR,t_lhkpn_keluarga.TANGGAL_LAHIR,t_lhkpn.tgl_lapor) AS UMUR_LAPOR,
                CASE hubungan 
                    WHEN 1 THEN "ISTRI"
                    WHEN 2 THEN "SUAMI"
                    WHEN 3 THEN "ANAK TANGGUNGAN"
                    WHEN 4 THEN "ANAK BUKAN TANGGUNGAN"
                    ELSE "LAINNYA"
                END AS HUBUNGAN_DESC,
                t_lhkpn_keluarga.HUBUNGAN,
                t_lhkpn_keluarga.FLAG_SK,
                t_lhkpn.STATUS,
                t_lhkpn.entry_via
            ', false);
            $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_keluarga.ID_LHKPN', 'left');
            $this->db->where('t_lhkpn.ID_LHKPN', $getPN->ID_LHKPN);
            $skb  = $this->db->get('t_lhkpn_keluarga')->result();
            $this->data['results'] = array_merge($data_pn, $skb );
            $this->data['results'][0]->HUBUNGAN_DESC = 'PN';
            $this->data['results'][0]->HUBUNGAN = 'PN';
        }
        $this->data['content_js'] = $this->load->view($this->templateDir . 'template_js', $this->data, true);
        $this->data['content_list'] = $this->load->view(strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_listingsk', $this->data, true);
    }

    private function __index_tracking() {

        $this->base_url = 'index.php/efill/lhkpnoffline/index/tracking/';

                $this->data = [];
                $this->data['title'] = 'Tracking';
                $this->data['icon'] = 'fa-list';
                $this->data['thisPageUrl'] = $this->base_url;

                foreach ((array) @$this->input->post('CARI') as $k => $v)
                $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];

                if (empty($this->CARI['NAMA']) && empty($this->CARI['LEMBAGA']) && empty($this->CARI['UNIT_KERJA']) && empty($this->CARI['TGL_LAHIR']) && empty($this->CARI['NHK'])) {
                    $this->total_rows = 0;
                } else {
//                    $xdata = $this->CARI['KODE'];
//                    $exp = explode('/', $xdata);
                    $this->load->model('Mlhkpn');

                    list($this->items_all, $this->total_rows_all) = $this->Mlhkpn->list_tracking_lhkpn_all($this->CARI); //untuk mendapatkan semua data hasil pencarian

                    list($this->items, $this->total_rows) = $this->Mlhkpn->list_tracking_lhkpn($this->CARI, $this->limit, $this->offset);

                    $data_items = $this->items_all;

                    $MAX_WL_arr = [];

                    foreach($data_items as $k => $v){
                        $MAX_WL_arr[] = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $v->ID_PN, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1'], 'TAHUN_WL', NULL, ['TAHUN_WL', 'DESC'])[0]->TAHUN_WL;
                    }

                    $this->end = count($this->items);
                    $this->data['title'] = 'LHKPN Masuk';
                    $this->data['total_rows'] = @$this->total_rows;
                    $this->data['offset'] = @$this->offset;
                    $this->data['items'] = @$this->items;
                    $this->data['start'] = @$this->offset + 1;
                    $this->data['end'] = @$this->offset + @$this->end;
                    $this->data['pagination'] = call_user_func('ng::genPagination');
                    $this->data['max_tahun_wls'] =  $MAX_WL_arr;


//                    $data = array(
//                        'CARI' => @$this->CARI,
//                        'nobap' => $this->nobap,
//                        'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
//                            'Dashboard' => 'index.php/welcome/dashboard',
//                            'E-Annoncement' => 'index.php/dashboard/eano',
//                            'List Announcement' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
//                        )),
//                    );

//                     $this->data['v_modal_buat_lhkpn_baru'] = $this->load->view('../../portal/views/filing/v_index/v_modal_buat_lhkpn_baru', $this->data, TRUE);
                    $this->data['content_paging'] = $this->load->view($this->templateDir . 'template_paging', $this->data, true);
                    $this->data['content_js'] = $this->load->view($this->templateDir . 'template_js', $this->data, true);
                    $this->data['content_list'] = $this->load->view(strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_tracking_index', $this->data, true);
                }
    }

    private function __index_penugasan() {
        $this->db->start_cache();
        $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN,T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN,T_LHKPNOFFLINE_PENERIMAAN.USERNAME as user_penerima,T_LHKPNOFFLINE_PENERIMAAN.JENIS_DOKUMEN ,T_LHKPNOFFLINE_PENERIMAAN.MELALUI, T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN, T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN,
					T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN, T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN, YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN) AS TANGGAL_PELAPORAN,  T_LHKPNOFFLINE_PENERIMAAN.STAT,
					T_PN.NAMA, T_PN.NIK, T_LHKPNOFFLINE_PENERIMAAN.DESKRIPSI_JABATAN,
					(SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
					(SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
					(SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA,
					T_LHKPNOFFLINE_PENUGASAN_ENTRY.ID_TUGAS,T_LHKPNOFFLINE_PENUGASAN_ENTRY.USERNAME,T_LHKPNOFFLINE_PENUGASAN_ENTRY.IS_READ,
					T_LHKPNOFFLINE_PENUGASAN_ENTRY.TANGGAL_PENUGASAN, T_LHKPNOFFLINE_PENUGASAN_ENTRY.DUE_DATE, T_LHKPNOFFLINE_PENUGASAN_ENTRY.KETERANGAN,
					YEAR(T_LHKPN.TGL_LAPOR) AS TAHUN_PELAPORAN,
					T_LHKPN.JENIS_LAPORAN');
        $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
        $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
        // $this->db->join('T_PN_JABATAN', 'T_PN_JABATAN.ID_PN = T_LHKPNOFFLINE_PENERIMAAN.ID_PN');
        $this->db->join('T_LHKPNOFFLINE_PENUGASAN_ENTRY', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = T_LHKPNOFFLINE_PENUGASAN_ENTRY.ID_PENERIMAAN', 'left');
        $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN');
        $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_DITERIMA_KOORD_CS', '1');
        $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_DITERIMA_KOORD_ENTRY', '1');

        if (@$this->CARI['TEXT']) {
            if ($this->CARI['BY'] == 'PN') {
                $this->db->like('T_LHKPNOFFLINE_PENERIMAAN.USERNAME', $this->CARI['TEXT']);
            } else if ($this->CARI['BY'] == 'NAMA') {
                $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
            } else if ($this->CARI['BY'] == 'NIK') {
                $this->db->like('T_PN.NIK', $this->CARI['TEXT']);
            }
        }

        if (@$this->CARI['IS_ACTIVE'] == 99) {
            // all
        } else if (@$this->CARI['IS_ACTIVE'] == -1) {
            // deleted
            $this->db->where('T_LHKPNOFFLINE_PENUGASAN_ENTRY.IS_ACTIVE', -1);
        } else if (@$this->CARI['IS_ACTIVE']) {
            // by status
            $this->db->where('T_LHKPNOFFLINE_PENUGASAN_ENTRY.IS_ACTIVE', $this->CARI['IS_ACTIVE']);
        } else {
            // active
            // $this->db->where('T_LHKPNOFFLINE_PENUGASAN_ENTRY.IS_ACTIVE', 1);
        }
        if (@$this->CARI['STATUS']) {
            if (@$this->CARI['STATUS'] != -99) {
                $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.STAT', $this->CARI['STATUS']);
            } else {
                // $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.STAT', '1');
                // $this->db->where('T_LHKPNOFFLINE_PENUGASAN_ENTRY.IS_READ', 0);
            }
        } else {
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.STAT', '1');
            $this->CARI['STATUS'] = 1;
        }

        if (@$this->CARI['PETUGAS']) {
            if (@$this->CARI['PETUGAS'] != -99) {
                $this->db->where('T_LHKPNOFFLINE_PENUGASAN_ENTRY.USERNAME', $this->CARI['PETUGAS']);
            }
        }

        if (@$this->CARI['KODE']) {
            if (@$this->CARI['KODE'] != '' && substr_count($this->CARI['KODE'], "/") == '3') {
                // echo $this->CARI['KODE'];
                $kode = explode('/', $this->CARI['KODE']);
                $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN', $kode[0]);

                if ($kode[1] == 'R') {
                    $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN', '4');
                } else {
                    $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN !=', '4');
                }

                $this->db->where('T_PN.NIK', $kode[2]);
                $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', $kode[3]);
            }
        }

        if (!empty($this->CARI['TAHUN'])) {
            if ($this->CARI['TAHUN'] != 99) {
                $this->db->where('YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN) = ' . $this->CARI['TAHUN'], null, false);
            }
        } else {
            $this->db->where('YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN) = ' . date('Y'), null, false);
        }

        if (@$this->CARI['TEXT']) {
            // $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
            $this->db->like('T_PN.NIK', $this->CARI['TEXT']);
            $this->db->or_like('T_PN.NAMA', $this->CARI['TEXT']);
        }

        $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_DITERIMA_KOORD_ENTRY', '1');

        $this->db->order_by('T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN', 'desc');
        $this->db->order_by('T_PN.NAMA', 'asc');
        $this->total_rows = $this->db->get('')->num_rows();
        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();
        $this->end = $query->num_rows();
        $this->db->flush_cache();

        $this->data['id'] = $this->input->post('id');

        if ($this->data['id'] != '') {
            $this->db->start_cache();

            $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN,T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN,T_LHKPNOFFLINE_PENERIMAAN.USERNAME as user_penerima,T_LHKPNOFFLINE_PENERIMAAN.JENIS_DOKUMEN ,T_LHKPNOFFLINE_PENERIMAAN.MELALUI, T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN, T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN,
					T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN, T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN, YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN) AS TANGGAL_PELAPORAN,  T_LHKPNOFFLINE_PENERIMAAN.STAT,
					T_PN.NAMA, T_PN.NIK, T_LHKPNOFFLINE_PENERIMAAN.DESKRIPSI_JABATAN,
					(SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
					(SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
					(SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA,
					T_LHKPNOFFLINE_PENUGASAN_ENTRY.ID_TUGAS,T_LHKPNOFFLINE_PENUGASAN_ENTRY.USERNAME,T_LHKPNOFFLINE_PENUGASAN_ENTRY.IS_READ,
					T_LHKPNOFFLINE_PENUGASAN_ENTRY.TANGGAL_PENUGASAN, T_LHKPNOFFLINE_PENUGASAN_ENTRY.DUE_DATE, T_LHKPNOFFLINE_PENUGASAN_ENTRY.KETERANGAN,
					YEAR(T_LHKPN.TGL_LAPOR) AS TAHUN_PELAPORAN,
					T_LHKPN.JENIS_LAPORAN');
            $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
            $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
            $this->db->join('T_LHKPNOFFLINE_PENUGASAN_ENTRY', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = T_LHKPNOFFLINE_PENUGASAN_ENTRY.ID_PENERIMAAN', 'left');
            $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN');
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_DITERIMA_KOORD_CS', '1');
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_DITERIMA_KOORD_ENTRY', '1');

            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN IN (' . $this->data['id'] . ')');

            $query = $this->db->get('');
            $this->data['item_selected'] = $query->result();
            $this->db->flush_cache();
        }
    }

    private function __index_tugas() {
        $this->db->start_cache();
        $this->db->select('T_LHKPNOFFLINE_PENUGASAN_ENTRY.*,
					T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN,
					T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN,
					YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN) AS TAHUN_PELAPORAN,
					T_PN.NIK,
					T_PN.NAMA');
        $this->db->from('T_LHKPNOFFLINE_PENUGASAN_ENTRY');
        $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = T_LHKPNOFFLINE_PENUGASAN_ENTRY.ID_PENERIMAAN');
        $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
        $this->db->where('T_LHKPNOFFLINE_PENUGASAN_ENTRY.USERNAME', $this->session->userdata('USR'));
        $this->db->where('1=1', null, false);

        if (@$this->CARI['TEXT']) {
            $this->db->like('T_LHKPNOFFLINE_PENUGASAN_ENTRY.USERNAME', $this->CARI['TEXT']);
            $this->db->or_like('T_PN.NIK', $this->CARI['TEXT']);
            $this->db->or_like('T_PN.NAMA', $this->CARI['TEXT']);
        }

        if (@$this->CARI['IS_ACTIVE'] == 99) {
            // all
        } else if (@$this->CARI['IS_ACTIVE'] == -1) {
            // deleted
            $this->db->where('T_LHKPNOFFLINE_PENUGASAN_ENTRY.IS_ACTIVE', -1);
        } else if (@$this->CARI['IS_ACTIVE']) {
            // by status
            $this->db->where('T_LHKPNOFFLINE_PENUGASAN_ENTRY.IS_ACTIVE', $this->CARI['IS_ACTIVE']);
        } else {
            // active
            $this->db->where('T_LHKPNOFFLINE_PENUGASAN_ENTRY.IS_ACTIVE', 1);
        }

        $this->total_rows = $this->db->get('')->num_rows();
        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();
        $this->end = $query->num_rows();
        // echo $this->db->last_query();
        $this->db->flush_cache();
    }

    public function index($mode = '', $cetak = false) { 
        // prepare paging

         $this->base_url = site_url('efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/' . $mode);

        if (in_array($cetak, ['pdf', 'excel', 'word'])) {
            $this->iscetak = true;
            $this->limit = 0;
        }

        $this->data = [];
        $this->data['title'] = ucfirst($mode);
        $this->data['icon'] = 'fa-list';

        if(isset($this->config->item('LHKPNOFFLINE_PAGE_ROLE')[$mode])){
            $this->data['role'] = urlencode($this->config->item('LHKPNOFFLINE_PAGE_ROLE')[$mode]);
        } 

        switch ($mode) {
            case 'tracking':
                // prepare query
                $this->__index_tracking();
                break;
            case 'penerimaan':
                // prepare query
                $this->__index_penerimaan();
                break;
            case 'penugasan':
                // prepare query
                $this->__index_penugasan();
                break;
            case 'tugas':
                // prepare query
                $this->__index_tugas();
                break;
            case 'listingsk':
                $this->__index_listingsk();
                break;
            case 'bast':
                // prepare query
                $this->db->start_cache();
                $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
                    CONCAT(TAHUN_PELAPORAN,\'/\',IF(JENIS_LAPORAN = \'4\', \'R\', \'K\'),\'/\',NIK,\'/\',ID_LHKPN ) as AGENDA,
                    T_PN.NIK,
                    T_PN.NAMA,
                    (SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
                    (SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
                    (SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
                ', false);
                $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                $this->db->where('1=1', null, false);
//                    $this->db->where('USERNAME_CS', $this->CARI['CS']);

                if (isset($this->CARI['CS']) AND $this->CARI['CS'] != '0') {
                    $this->db->where('USERNAME_CS', $this->CARI['CS']);
                } else {
                    $this->CARI['CS'] = '0';
                }

                if (@$this->CARI['STATUS'] == '0') {
                    $this->db->where('IS_DITERIMA_KOORD_CS', '0');
                }

                if (@$this->CARI['STATUS'] == '1') {
                    $this->db->where('IS_DITERIMA_KOORD_CS', '1');
                }

                if (@$this->CARI['TEXT']) {
                    if ($this->CARI['BY'] == 'AGENDA') {
                        $this->db->having("AGENDA LIKE '%" . $this->CARI['TEXT'] . "%'");
                    } else if ($this->CARI['BY'] == 'NAMA') {
                        $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
                    } else if ($this->CARI['BY'] == 'NIK') {
                        $this->db->like('T_PN.NIK', $this->CARI['TEXT']);
                    }
                }

                if (@$this->CARI['TANGGAL']) {
                    $this->db->where('TGL_BAST_KOORD_CS', date('Y-m-d', strtotime(str_replace('/', '-', $this->CARI['TANGGAL']))));
                }

                $this->data['id'] = $this->input->post('id');

                $this->total_rows = $this->db->get('')->num_rows();

                $query = $this->db->get('', $this->limit, $this->offset);

                $this->items = $query->result();
                $this->end = $query->num_rows();

                $this->db->flush_cache();
                break;
            case 'bastentry':
                // prepare query
//                $barcode = $this->data['item']->TAHUN_PELAPORAN.'/'.($this->data['item']->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$this->data['item']->NIK.'/'.$this->data['item']->ID_LHKPN;
                $this->db->start_cache();
                $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
                    CONCAT(TAHUN_PELAPORAN,\'/\',IF(JENIS_LAPORAN = \'4\', \'R\', \'K\'),\'/\',NIK,\'/\',ID_LHKPN ) as AGENDA,
                    T_PN.NIK,
                    T_PN.NAMA,
                    (SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
                    (SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
                    (SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
                ', false);
                $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                $this->db->where('1=1', null, false);
                $this->db->where('USERNAME_KOORD_CS', $this->session->userdata('USR'));

                if (@$this->CARI['TEXT']) {
                    if ($this->CARI['BY'] == 'AGENDA') {
                        $this->db->having("AGENDA LIKE '%" . $this->CARI['TEXT'] . "%'");
                    } else if ($this->CARI['BY'] == 'NAMA') {
                        $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
                    } else if ($this->CARI['BY'] == 'NIK') {
                        $this->db->like('T_PN.NIK', $this->CARI['TEXT']);
                    }
                }

                if (@$this->CARI['STATUS'] == '0') {
                    $this->db->where('IS_DITERIMA_KOORD_ENTRY', '0');
                }

                if (@$this->CARI['STATUS'] == '1') {
                    $this->db->where('IS_DITERIMA_KOORD_ENTRY', '1');
                }

                $this->total_rows = $this->db->get('')->num_rows();
                $query = $this->db->get('', $this->limit, $this->offset);
                $this->items = $query->result();

                $this->end = $query->num_rows();

                $this->db->flush_cache();

                $this->data['id'] = $this->input->post('id');

                if ($this->data['id'] != '') {
                    $this->db->start_cache();

                    $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
                    T_PN.NIK,
                    T_PN.NAMA,
                    (SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
                    (SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
                    (SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
                ');
                    $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                    $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                    $this->db->where('1=1', null, false);
                    $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN IN (' . $this->data['id'] . ')');
                    $query = $this->db->get('');
                    $this->data['item_selected'] = $query->result();
                    $this->db->flush_cache();
                }

                break;
            case 'cekbast':
                // prepare query

                $this->db->start_cache();
                if (isset($this->CARI['CS']) AND $this->CARI['CS'] != '0') {
                    $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
            	        T_PN.NIK,
            	        T_PN.NAMA,
            	        (SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
            	        (SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
            	        (SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
            	    ');
                    $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                    $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                    $this->db->where('1=1', null, false);
                    $this->db->where('USERNAME_KOORD_CS', $this->CARI['CS']);

                    if (@$this->CARI['STATUS'] == '0') {
                        $this->db->where('IS_DITERIMA_KOORD_ENTRY', '0');
                    }
                    if (@$this->CARI['STATUS'] == '1') {
                        $this->db->where('IS_DITERIMA_KOORD_ENTRY', '1');
                    }

                    if (@$this->CARI['TEXT']) {
                        if ($this->CARI['BY'] == 'PN') {
                            $this->db->like('T_LHKPNOFFLINE_PENERIMAAN.USERNAME', $this->CARI['TEXT']);
                        } else if ($this->CARI['BY'] == 'NAMA') {
                            $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
                        } else if ($this->CARI['BY'] == 'NIK') {
                            $this->db->like('T_PN.NIK', $this->CARI['TEXT']);
                        }
                    }

                    if (@$this->CARI['TANGGAL']) {
                        $this->db->where('TGL_BAST_KOORD_ENTRY', date('Y-m-d', strtotime(str_replace('/', '-', $this->CARI['TANGGAL']))));
                    }

                    $this->data['id'] = $this->input->post('id');

                    $this->total_rows = $this->db->get('')->num_rows();
                    $query = $this->db->get('', $this->limit, $this->offset);
                    $this->items = $query->result();
                    $this->end = $query->num_rows();
                }

                $this->db->flush_cache();

                break;
            case 'historybast' :
                // prepare query
                if ($this->input->post('CARI[JENIS]') == '0') {
                    $this->db->start_cache();
                    $this->db->select('*');
                    $this->db->from('T_BAST_CS');
                    $this->db->where('1=1', null, false);

                    if (@$this->CARI['PENGIRIM']) {
                        $this->db->where('USER_CS', $this->CARI['PENGIRIM']);
                    }

                    if (@$this->CARI['PENERIMA']) {
                        $this->db->where('USER_KOORD_CS', $this->CARI['PENERIMA']);
                    }

                    if (@$this->CARI['TAHUN']) {
                        $this->db->where('YEAR(T_BAST_CS.TGL_PENYERAHAN) = ' . $this->CARI['TAHUN']);
                    }

                    if (@$this->CARI['BULAN']) {
                        $this->db->where('MONTH(T_BAST_CS.TGL_PENYERAHAN) = ' . $this->CARI['BULAN']);
                    }

                    $this->total_rows = $this->db->get('')->num_rows();
                    $query = $this->db->get('', $this->limit, $this->offset);
                    // echo $this->db->last_query();
                    $this->items = $query->result();
                    $this->end = $query->num_rows();
                    $this->db->flush_cache();
                } else if ($this->input->post('CARI[JENIS]') == '1') {
                    $this->db->start_cache();
                    $this->db->select('*');
                    $this->db->from('T_BAST_ENTRY');
                    $this->db->where('1=1', null, false);

                    if (@$this->CARI['PENGIRIM']) {
                        $this->db->where('USER_KOORD_CS', $this->CARI['PENGIRIM']);
                    }

                    if (@$this->CARI['PENERIMA']) {
                        $this->db->where('USER_KOORD_ENTRI', $this->CARI['PENERIMA']);
                    }

                    if (@$this->CARI['TAHUN']) {
                        $this->db->where('YEAR(T_BAST_ENTRY.TGL_PENYERAHAN) = ' . $this->CARI['TAHUN']);
                    }

                    if (@$this->CARI['BULAN']) {
                        $this->db->where('MONTH(T_BAST_ENTRY.TGL_PENYERAHAN) = ' . $this->CARI['BULAN']);
                    }

                    $this->total_rows = $this->db->get('')->num_rows();
                    $query = $this->db->get('', $this->limit, $this->offset);
                    // echo $this->db->last_query();
                    $this->items = $query->result();
                    $this->end = $query->num_rows();
                    $this->db->flush_cache();
                }

                break;
            default :

                break;
        }

        $this->data['breadcrumb'] = call_user_func('ng::genBreadcrumb', [
            'Dashboard' => 'index.php/welcome/dashboard',
            'E filling' => 'index.php/dashboard/efilling/',
            ucfirst(@$mode) => $this->base_url,
        ]);
        $this->data['urlEdit'] = site_url('efill/' . strtolower(__CLASS__) . '/' . 'edit' . '/' . @$mode);
        $this->data['thisPageUrl'] = $this->base_url;
        $this->data['linkCetak'] = site_url('efill/' . strtolower(__CLASS__) . '/' . 'index' . '/' . @$mode . '/' . $cetak);
        $this->data['titleCetak'] = get_called_class() . ' ' . ucwords(str_replace('_', ' ', $mode));
        $this->data['filenameCetak'] = strtolower(get_called_class());
        $this->data['CARI'] = @$this->CARI;
        $this->data['total_rows'] = @$this->total_rows;
        $this->data['offset'] = @$this->offset;
        $this->data['items'] = @$this->items;
        $this->data['start'] = @$this->offset + 1;
        $this->data['end'] = @$this->offset + @$this->end;
        $this->data['pagination'] = call_user_func('ng::genPagination');

        $this->data['efillmenu'] = make_secure_text(strtolower(trim('e-FILING')));

        if ($this->iscetak) {
            ng::exportDataTo($this->data, $cetak, strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . $mode . '_' . 'cetak', $this->data['filenameCetak']);
        } else {
            // load view
            $this->load->view($mode ? 'lhkpnoffline/lhkpnoffline_' . $mode . '_index' : 'lhkpnoffline/lhkpnoffline_index', $this->data);
        }
    }

    private function __get_existing_penerimaan_form_data() {

        $idimpxllhkpn = $this->input->post("impxllhkpn");

        if ($idimpxllhkpn) {
            return $this->Mlhkpnofflinepenerimaan->find_record([" T_LHKPNOFFLINE_PENERIMAAN.ID_IMP_XL_LHKPN = " => trim($idimpxllhkpn)]);
        }

        return FALSE;
    }

    public function add() {
        $this->data['act'] = 'doinsert';
        $this->data['random_id'] = md5(uniqid(rand(), true));

        $iniJoin = [
            ['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPNOFFLINE_PENERIMAAN.ID_PN', 'join' => 'left'],
            ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA', 'join' => 'left'],
            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'join' => 'left'],
            ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'join' => 'left']
        ];

        $this->data["form_data"] = $this->__get_existing_penerimaan_form_data();

        $data_js = $this->data;
        $this->data['js_page'][] = $this->load->view('lhkpnoffline/penerimaan/js/js_lwsupload', $data_js, TRUE);
        $this->data['js_page'][] = $this->load->view('lhkpnoffline/penerimaan/js/js_form_add', $data_js, TRUE);

        $this->load->model('mpn', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('minstansi', '', TRUE);

        $data_js = array_merge($data_js, array(
            'iscln' => 2,
            'status' => 'daftarindividu',
            'form' => 'add',
            'agama' => $this->mglobal->get_data_all('M_AGAMA', null, ['IS_ACTIVE' => 1]),
            'sttnikah' => $this->mglobal->get_data_all('M_STATUS_NIKAH'),
            'penhir' => $this->mglobal->get_data_all('M_PENDIDIKAN', null, ['IS_ACTIVE' => 1]),
            'uk_id' => $this->session->userdata('UK_ID'),
            'is_uk' => $this->is_unit_kerja(),
            'isInstansi' => $this->is_instansi()
        ));

        $this->load->view('lhkpnoffline/penerimaan/lhkpnoffline_penerimaan_form_add', $this->data);
    }

    public function temp_upload() {
        $file_id = $this->input->post('file_id');
        if (!empty($_POST) && !empty($_FILES) && array_key_exists("file_import_excel_temp", $_FILES) && $file_id) {

            $inputfile_name = 'file_import_excel_temp';

            $dir = self::DIR_TEMP_UPLOAD . "$file_id/";

            $nik = $this->input->get("ikin");
            $id_lhkpn = $this->input->get("inpkhl");
            $upload_type = $this->input->get("upt"); //upload type if any then TEMP else FALSE
            $replace_is_exist = $this->input->post('replace_is_exist');

            $upl = $this->input->get("upl");
            if (!$upload_type) {

                if ($nik && $id_lhkpn && $upl && $upl == "skm") {
                    //////////SISTEM KEAMANAN///////////
                    $post_nama_file = 'file_import_excel_temp';
                    $extension_diijinkan = array("pdf", "jpg", "png","jpeg","tif","tiff","doc","docx");
                    $check_protect = protectionDocument($post_nama_file,$extension_diijinkan);
                    if($check_protect){
                        $method = __METHOD__;
                        $this->load->model('mglobal');
                        $this->mglobal->recordLogAttacker($check_protect,$method);
                        echo 'INGAT DOSA WAHAI PARA HACKER';
                        exit;
                    }
                    //////////SISTEM KEAMANAN///////////
                    $dir = self::DIR_SKM_UPLOAD . "$nik/$id_lhkpn/";
                } elseif ($nik && $id_lhkpn && $upl && $upl == "sk") {
                    //////////SISTEM KEAMANAN///////////
                    $post_nama_file = 'file_import_excel_temp';
                    $extension_diijinkan = array("pdf", "jpg", "png","jpeg","tif","tiff","doc","docx");
                    $check_protect = protectionDocument($post_nama_file,$extension_diijinkan);
                    if($check_protect){
                        $method = __METHOD__;
                        $this->load->model('mglobal');
                        $this->mglobal->recordLogAttacker($check_protect,$method);
                        echo 'INGAT DOSA WAHAI PARA HACKER';
                        exit;
                    }
                    //////////SISTEM KEAMANAN///////////
                    $dir = self::DIR_SKUASA_UPLOAD . "$nik/$id_lhkpn/";
                }else{
                    //////////SISTEM KEAMANAN///////////
                    $post_nama_file = 'file_import_excel_temp';
                    $extension_diijinkan = array("xls", "xlsx", "xlsm");
                    $check_protect = protectionDocument($post_nama_file,$extension_diijinkan);
                    if($check_protect){
                        $method = __METHOD__;
                        $this->load->model('mglobal');
                        $this->mglobal->recordLogAttacker($check_protect,$method);
                        echo 'INGAT DOSA WAHAI PARA HACKER';
                        exit;
                    }
                    //////////SISTEM KEAMANAN///////////
                }
            } else {
                if ($nik && $id_lhkpn && $upl && $upl == "skm") {

                    $dir = self::DIR_TEMP_SKM_UPLOAD . "$nik/";
                } elseif ($nik && $id_lhkpn && $upl && ($upl == "sk" || $upl == "skb")) {

                    $dir = self::DIR_TEMP_SKUASA_UPLOAD . "$nik/";
                } elseif ($nik && $id_lhkpn && $upl && $upl == "ikhtisar") {
                    $dir = self::DIR_TEMP_IKHTISAR_UPLOAD . "$nik/";
                } elseif ($nik && $id_lhkpn && $upl && $upl == "dok") {
                    $dir = self::DIR_TEMP_UPLOAD . "$file_id/";
                }
            }

            $file_received = @$_FILES[$inputfile_name];

            $extension = strtolower(@substr($file_received['name'], -4));

            if ($replace_is_exist && file_exists($dir . "/" . $file_received['name'])) {
                $new_name = $dir . "/" . $file_received['name'] . "_" . rand(1, 2000);
                rename($dir . "/" . $file_received['name'], $new_name);
                unlink($new_name);
            }
//            var_dump($replace_is_exist, file_exists($dir.$file_received['name']), $dir.$file_received['name'], $resunlink);exit;
            
            $dir_ch = self::DIR_SKUASA_UPLOAD . "$nik/";
            if (is_dir($dir_ch) === false) {
                mkdir($dir_ch, 0777, TRUE);
                chmod($dir_ch, 0777);
            }
            
            if (is_dir($dir) === false) {
                mkdir($dir, 0777, TRUE);
                chmod($dir, 0777);
            }

            $allowedFileType = [
                "application/vnd.ms-excel",
                "application/vnd.ms-excel.sheet.macroEnabled.12",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "text/xml",
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "application/pdf",
                "application/x-zip-compressed",
                "text/plain",
                "image/jpeg",
                "image/png",
                "image/bmp",
                "image/gif",
                "image/tiff",
            ];

            if ($file_received['error'] == 0) {
                $extension = strtolower(@substr($file_received['name'], -4));
                if (in_array($file_received['type'], $allowedFileType) && $file_received['size'] != '') {
                    $c = save_file($file_received['tmp_name'], $file_received['name'], $file_received['size'], $dir, 0, 0, FALSE, FALSE, TRUE);

                    if (is_array($c) && $c['error'] == 1) {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 415 Unsupported Media Type', true, 415);
                        exit;
                    }

                    header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
                    exit;
                } else {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 415 Unsupported Media Type', true, 415);
                    exit;
                }
            }
        }
        header($_SERVER['SERVER_PROTOCOL'] . ' 204 No Content', true, 204);
        exit;
    }

    private function __get_t_pn_jabatan($id_pn = FALSE) {
        if ($id_pn) {
            $this->db->where('t_pn_jabatan.ID_PN', $id_pn);
            // $this->db->where('IS_PRIMARY','1');
            $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_pn_jabatan.ID_JABATAN', 'left');
            $this->db->join('t_pn', 't_pn.ID_PN = t_pn_jabatan.ID_PN');
            $q = $this->db->get('t_pn_jabatan');
            
            if ($q) {
                return $q->row();
            }
        }

        return FALSE;
    }

    private function move_lhkpn_import($random_id, $ID_PN, $tahun, $show_log = FALSE) {
        return copyr(self::DIR_TEMP_UPLOAD . $random_id, self::DIR_IMP_UPLOAD . $ID_PN . "/" . $tahun . "/" . $random_id, $show_log);
    }

    private function read_import_excel($random_id, $ID_PN, $tahun, $file_excel = FALSE, $is_data_double = FALSE) {
        if ($file_excel) {

            $filepath = self::DIR_IMP_UPLOAD . $ID_PN . "/" . $tahun . "/" . $random_id;
            $filepath_destination = self::DIR_TEMP_UPLOAD . $random_id;

//            echo $filepath . "/".$file_excel;exit;
            //image files
            $list_of_files = UnzipSubFolder($filepath, $file_excel, realpath($filepath_destination), 'xl/media', 'image3');

            if (!empty($list_of_files)) {
                foreach ($list_of_files as $filename) {
                    if (strstr($filename, 'image3')) {
                        $this->default_imp_xl_foto_pribadi = $filename;
                        break;
                    }
                }
            }

            if ($is_data_double && !file_exists($filepath . "/" . $file_excel)) {
                $this->move_lhkpn_import($random_id, $ID_PN, $tahun);
            }

            $this->load->library('save_import_lhkpn_excel', [
                "filename" => $file_excel,
                "upload_import_lhkpn_excel_path" => $filepath . "/",
            ]);

            return $this->save_import_lhkpn_excel->read_excel();
        }
        return FALSE;
    }

    public function removeFileLampiran() {
        $fnm = $this->input->post('fnm');
        $id_lhkpn = $this->input->get('inpkhl'); //secured
        $nik = $this->input->get("ikin");
        $upl = $this->input->get("upl");
        $rid = $this->input->post("rid");

        $origin = $this->input->post('origin');

        $field_names = [
            "skm" => "FILE_BUKTI_SKM",
            "skb" => "FILE_BUKTI_SK",
            "ikhtisar" => "FILE_BUKTI_IKHTISAR",
            "dok" => FALSE,
        ];
        $field_name = $upl ? $field_names[$upl] : FALSE;

        $dir = FALSE;

        if ($nik && $id_lhkpn && $this->input->get("upl") && $this->input->get("upl") == "skm") {
            $dir = self::DIR_TEMP_SKM_UPLOAD . "$nik/$id_lhkpn/";
            if ($origin == 'xlvalidation') {
                $dir = self::DIR_TEMP_SKM_UPLOAD . "$nik/";
            }
        } elseif ($nik && $id_lhkpn && $this->input->get("upl") && $this->input->get("upl") == "skb") {
            $dir = self::DIR_TEMP_SKUASA_UPLOAD . "$nik/$id_lhkpn/";
            if ($origin == 'xlvalidation') {
                $dir = self::DIR_TEMP_SKUASA_UPLOAD . "$nik/";
            }
        } elseif ($nik && $id_lhkpn && $this->input->get("upl") && $this->input->get("upl") == "ikhtisar") {
            $dir = self::DIR_TEMP_IKHTISAR_UPLOAD . "$nik/$id_lhkpn/";
            if ($origin == 'xlvalidation') {
                $dir = self::DIR_TEMP_IKHTISAR_UPLOAD . "$nik/";
            }
        } elseif ($nik && $id_lhkpn && $this->input->get("upl") && $this->input->get("upl") == "dok") {
            $dir = self::DIR_TEMP_UPLOAD . "$nik/$id_lhkpn/";
            if ($origin == 'xlvalidation') {
                $dir = self::DIR_TEMP_UPLOAD . $rid . "/";
            }
        }

        $lhkpn_found = $this->mglobal->secure_get_by_id("t_imp_xl_lhkpn", "id_imp_xl_lhkpn", "id_imp_xl_lhkpn", $id_lhkpn);

        if ($lhkpn_found && property_exists($lhkpn_found, $field_name)) {
            $stored_filenames = explode(", ", $lhkpn_found->{$field_name});
            $key_fnm = array_search($fnm, $stored_filenames);
            unset($stored_filenames[$key_fnm]);

            $str_filenames = count($stored_filenames) > 1 ? implode(", ", $stored_filenames) : current($stored_filenames);
            if (empty($str_filenames)) {
                $str_filenames = NULL;
            }

            $this->mglobal->update("t_imp_xl_lhkpn", [$field_name => $str_filenames], " id_imp_xl_lhkpn='" . $lhkpn_found->id_imp_xl_lhkpn . "' ");
        }

        $fpath = $dir . $fnm;

        $response = "0";

        if ($dir && is_dir($dir) && file_exists($fpath)) {
            $response = unlink($fpath) ? "1" : "0";
        }

        echo $response;
    }

    private function find_penerimaan_offline($posted_penerimaan, $not_is_active = 'any', $is_double_confirmed = FALSE) {

//        var_dump($posted_penerimaan);
//        exit;


        $record_found = TRUE;
        $arr_condition = [
            " T_LHKPNOFFLINE_PENERIMAAN.ID_PN = " => "'" . $posted_penerimaan["ID_PN"] . "'",
            " T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN = " => "'" . $posted_penerimaan["JENIS_LAPORAN"] . "'",
            " T_LHKPNOFFLINE_PENERIMAAN.IS_REPLACED <> " => "'1'",
        ];

        if ($not_is_active != 'any') {
            $arr_condition[" T_LHKPNOFFLINE_PENERIMAAN.IS_ACTIVE <> "] = "'" . $not_is_active . "'";
        }

        if ($posted_penerimaan["JENIS_LAPORAN"] == '4') {
            if (!is_null($posted_penerimaan["TAHUN_PELAPORAN"])) {
                $arr_condition[" T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN"] = "'" . $posted_penerimaan["TAHUN_PELAPORAN"] . "'";
            }
        } else {
            if (!is_null($posted_penerimaan["TANGGAL_PELAPORAN"])) {
                $arr_condition[" T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN"] = "'" . $posted_penerimaan["TANGGAL_PELAPORAN"] . "'";
            }
        }

        if (!$is_double_confirmed) {
            $record_found = $this->Mlhkpnofflinepenerimaan->find_record($arr_condition);

            if ($record_found) {
                $this->penerimaan_data_found($record_found, $posted_penerimaan);
                exit;
            }
        } else {
            $this->Mlhkpnofflinepenerimaan->replace_old_record($arr_condition);
        }

        return [
            "record_found" => $record_found,
            "condition" => $arr_condition,
        ];
    }

    protected function penerimaan_data_found($record_found, $posted_penerimaan) {

        $posted_penerimaan["j"] = $posted_penerimaan["SCREENING_SUCCESS"];

        $response = array(
            "msg" => "failed",
            "tmp_idx" => FALSE,
            "data_may_double" => TRUE,
            "posted_data_penerimaan" => $posted_penerimaan,
            "file_excel" => $this->input->post('isfileexcel'),
            "record_found" => $record_found,
        );

        header("Content-type:application/json");
        echo json_encode((object) $response);
        exit; //Force Exit;
    }

    public function data_penerimaan($i = FALSE, $scr = FALSE) {
        if (!$i) {
            show_404();
        }

        $this->config->load('harta');

        $record_found = $this->Mlhkpnofflinepenerimaan->get_detail($i);

        $data['input_baru'] = (object) $_POST;
        $data['js_page'][] = $this->load->view('lhkpnoffline/penerimaan/js/js_form_double', $data, TRUE);
//        var_dump($record_found, $i, $scr, $_POST);exit;
//        $data["URAIAN_SCREENING"] = [];
//        if($record_found && !is_null($record_found["URAIAN_SCREENING"]) && strlen($record_found["URAIAN_SCREENING"])){
////            $data["URAIAN_SCREENING"] = json_decode($record_found["URAIAN_SCREENING"]);
//        }

        $breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
        $breadcrumbitem[] = ['E Filling' => 'index.php/dashboard/efilling'];
        $breadcrumbitem[] = [ucwords(strtolower(__CLASS__)) => $this->segmentTo[2]];
        $breadcrumbitem[] = [$this->data['title'] => @$this->segmentTo[4]];
        $breadcrumbdata = [];
        foreach ($breadcrumbitem as $list) {
            $breadcrumbdata = array_merge($breadcrumbdata, $list);
        }
        $data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);
        $data["detail_info"] = $record_found;

        $data["lhkpn_offline_melalui"] = array_flip($this->config->item('lhkpn_offline_melalui', 'harta'));


        $this->load->view('lhkpnoffline/lhkpnoffline_form_double', $data);
    }

    protected function data_is_exist() {
        $is_double_confirmed = $this->input->post("data_is_exist");

        if ($is_double_confirmed) {
            $is_double_confirmed = TRUE;
        } else {
            $is_double_confirmed = FALSE;
        }
        return $is_double_confirmed;
    }

    public function simpan_penerimaan_add() {

        $ID_PN = $this->input->post('ID_PN', TRUE);

        if (!$ID_PN) {
            echo '0';
            return FALSE;
        }

        $response = array(
            "msg" => "failed",
            "tmp_idx" => FALSE,
            "data_may_double" => FALSE,
        );

        $is_double_confirmed = $this->data_is_exist();

        $rand_form_name = "random_id";
        if (!array_key_exists("random_id", $_POST)) {
            $rand_form_name = "RAND_ID";
        }

        $random_id = $this->input->post($rand_form_name, TRUE);

        $screening_valid_form_name = !array_key_exists("SCREENING_VALID", $_POST) ? "SCREENING_SUCCESS" : "SCREENING_VALID";
        $screening_valid = $this->input->post($screening_valid_form_name);
        $email_screening_to = $this->input->post("EMAIL");
        $uraian_screening = $this->input->post("URAIAN_SCREENING");

        $nama_pn = $this->input->post("NAMA");

        $T_PN_JABATAN = $this->__get_t_pn_jabatan($this->input->post('ID_PN', TRUE));

        $arr_kirim_melalui = $this->config->item('lhkpn_offline_melalui', 'harta');
        $posted_melalui = $this->input->post('MELALUI', TRUE);
        $melalui = NULL;
        if ($posted_melalui && array_key_exists($posted_melalui, $arr_kirim_melalui)) {
            $melalui = $arr_kirim_melalui[$posted_melalui];
        }
        unset($posted_melalui, $arr_kirim_melalui);

        $nama_lembaga = $T_PN_JABATAN->NAMA_LEMBAGA;

        $penerimaan = array(
            'ID_PN' => $ID_PN,
//            'JENIS_LAPORAN' => $this->input->post('JENIS_LAPORAN', TRUE),
//            'TANGGAL_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) <> '4') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN', TRUE)))) : date('Y-m-d', strtotime($this->input->post('TAHUN_PELAPORAN', TRUE) . '-12-31')),
//            'TAHUN_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) == '4') ? $this->input->post('TAHUN_PELAPORAN', TRUE) : date('Y', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN', TRUE)))),
            'JABATAN' => $T_PN_JABATAN->ID_JABATAN,
            'DESKRIPSI_JABATAN' => $T_PN_JABATAN->DESKRIPSI_JABATAN,
            'LEMBAGA' => $T_PN_JABATAN->LEMBAGA,
            'UNIT_KERJA' => $T_PN_JABATAN->UK_ID,
            'JENIS_DOKUMEN' => $this->input->post('JENIS_DOKUMEN', TRUE),
            'MELALUI' => $melalui,
            'USERNAME' => $this->session->userdata('USR'),
            'USERNAME_CS' => $this->session->userdata('USR'),
            'TANGGAL_PENERIMAAN' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PENERIMAAN', TRUE)))),
            'STAT' => '1',
            'IS_ACTIVE' => 1,
            'RAND_ID' => $random_id,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            'URAIAN_SCREENING' => $uraian_screening,
            'EMAIL_SCREENING_TO' => $email_screening_to,
            'SCREENING_SUCCESS' => $screening_valid,
            'UPLOADED_DOCUMENT' => $this->input->post('isfileexcel'),
        );

        $this->load->library('lhkpn_lib');
//
        $jenis = $this->input->post('JENIS_LAPORAN');
        if ($jenis == '4') {
            $tahun = $this->input->post('TAHUN_PELAPORAN');
        } else {
            $tahun = date('Y', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN'))));
        }

        $success_move_import = TRUE;
//            if (!$is_double_confirmed) {
        $success_move_import = $this->move_lhkpn_import($random_id, $ID_PN, $tahun);
//            }

        if ($screening_valid) {

            $penerimaan["URAIAN_SCREENING"] = NULL;

            $imported_data = FALSE;

            if ($success_move_import) {

                $imported_data = $this->read_import_excel($random_id, $ID_PN, $tahun, $this->input->post('isfileexcel'), $is_double_confirmed);

                if (!$this->save_import_lhkpn_excel->excel_readable) {
                    header("Content-type:application/json");
                    echo json_encode((object) array(
                                "msg" => "Excel dengan versi " . $this->save_import_lhkpn_excel->detected_version . " belum didukung. Versi Excel yang didukung adalah " . implode(",", $this->save_import_lhkpn_excel->version_compatible),
                                "tmp_idx" => FALSE
                    ));
                    exit; // ini memang stop disini;
                }
            }

//        var_dump($imported_data->response_form_dua);exit;
            $imported_data_valid = TRUE;
            $temp_lhkpn_id = FALSE;
            /**
             * set data dari Excel
             */
//        $this->save_imported_data($imported_data);
//        var_dump($T_PN_JABATAN, $imported_data, $imported_data_valid);exit;

            if ($T_PN_JABATAN && $imported_data && $imported_data_valid) {

//            $penerimaan['ID_LHKPN'] = $result;

                /**
                 * INSERT TO IMP_XL_LHKPN and PENERIMAAN
                 */
                $penerimaan["JENIS_LAPORAN"] = map_jenis_laporan_imp_xl($imported_data->response_form_satu["jenis_laporan"]);
                $penerimaan["TANGGAL_PELAPORAN"] = NULL;
                if (array_key_exists("tanggal_pelaporan_khusus", $imported_data->response_form_satu)) {
                    $penerimaan["TANGGAL_PELAPORAN"] = trim($imported_data->response_form_satu["tanggal_pelaporan_khusus"]) == '' ? NULL : $imported_data->response_form_satu["tanggal_pelaporan_khusus"];
                }
                $penerimaan["TAHUN_PELAPORAN"] = $imported_data->response_form_satu["tahun_pelaporan_periodik"];
                $penerimaan["VERSI_EXCEL"] = $this->save_import_lhkpn_excel->detected_version;


                $this->imported_data = $imported_data;
                $record_set_found = $this->find_penerimaan_offline($penerimaan, '0', $is_double_confirmed);
                $this->imported_data = NULL;

//                $temp_lhkpn_id = $this->Mimpxllhkpn->add_new($penerimaan["JENIS_LAPORAN"], $penerimaan["ID_PN"], $penerimaan["TANGGAL_PELAPORAN"], $penerimaan["TANGGAL_PENERIMAAN"], $T_PN_JABATAN->NIP_NRP, $penerimaan["MELALUI"], $penerimaan["STAT"], $penerimaan["USERNAME"]);
                $temp_lhkpn_id = $this->Mimpxllhkpn->add_new($penerimaan["JENIS_LAPORAN"], $penerimaan["ID_PN"], $imported_data->response_form_satu["tgl_lapor"], $penerimaan["TANGGAL_PENERIMAAN"], $T_PN_JABATAN->NIP_NRP, '1', $penerimaan["STAT"], $penerimaan["USERNAME"]);
                $penerimaan['id_imp_xl_lhkpn'] = $temp_lhkpn_id;
                $this->Mlhkpnofflinepenerimaan->add_new($penerimaan);

//            copy_file_to_nik_folder(self::DIR_TEMP_UPLOAD,);

                $data_pn = $this->mglobal->get_by_id("T_PN", "ID_PN", $ID_PN);
                $encrypted_username = encrypt_username($data_pn->NIK, 'e');

                if (!is_dir("./uploads/data_pribadi/" . $encrypted_username . "/")) {
                    mkdir("./uploads/data_pribadi/" . $encrypted_username . "/", 0777, TRUE);
                }

                copyr(self::DIR_TEMP_UPLOAD . $random_id . "/" . $this->default_imp_xl_foto_pribadi, "./uploads/data_pribadi/" . $encrypted_username . "/" . $this->default_imp_xl_foto_pribadi);


                /**
                 * Response Jenis Laporan
                 */
                $response_data_pribadi = $this->Mimpxllhkpn->add_data_pribadi($imported_data->response_form_dua, $temp_lhkpn_id, $this->default_imp_xl_foto_pribadi);

                $this->load->model(array('Minstansi', 'Munitkerja', 'Msubunitkerja', 'Mjabatan'));

                /**
                 * @deprecated since 28-desember-2017
                 */
//                $response_data_jabatan = $this->Mimpxllhkpn->add_data_jabatan($imported_data->response_form_satu, $temp_lhkpn_id);
                $response_data_jabatan = $this->Mimpxllhkpn->add_data_jabatan($T_PN_JABATAN, $imported_data->response_form_satu, $temp_lhkpn_id);

                $response_data_keluarga = $this->Mimpxllhkpn->add_data_keluarga($imported_data->response_data_keluarga, $temp_lhkpn_id);
                $response_data_harta_tidak_bergerak = $this->Mimpxllhkpn->add_data_harta_tidak_bergerak($imported_data->response_data_tidak_bergerak, $temp_lhkpn_id);
                $response_data_harta_bergerak = $this->Mimpxllhkpn->add_data_harta_bergerak($imported_data->response_data_bergerak, $temp_lhkpn_id);
                $response_data_harta_bergerak_lainnya = $this->Mimpxllhkpn->add_data_harta_bergerak_lainnya($imported_data->response_data_bergerak_lainnya, $temp_lhkpn_id);
                $response_data_surat_berharga = $this->Mimpxllhkpn->add_data_surat_berharga($imported_data->response_data_surat_berharga, $temp_lhkpn_id);
                $response_data_kas_dan_setara_kas = $this->Mimpxllhkpn->add_data_kas_dan_setara_kas($imported_data->response_data_kas_setara_kas, $temp_lhkpn_id);
                $response_data_harta_lainnya = $this->Mimpxllhkpn->add_data_harta_lainnya($imported_data->response_data_harta_lainnya, $temp_lhkpn_id);
                $response_data_hutang = $this->Mimpxllhkpn->add_data_hutang($imported_data->response_data_hutang, $temp_lhkpn_id);

                $response_data_penerimaan_tunai = $this->Mimpxllhkpn->add_data_penerimaan_tunai($imported_data->response_data_penerimaan_kas, $imported_data->response_data_penerimaan_kas_pasangan, $temp_lhkpn_id);
                $response_data_pengeluaran_tunai = $this->Mimpxllhkpn->add_data_pengeluaran_tunai($imported_data->response_data_pengeluaran_kas, $temp_lhkpn_id);
                $response_data_fasilitas = $this->Mimpxllhkpn->add_data_fasilitas($imported_data->response_data_fasilitas, $temp_lhkpn_id);
                $response_data_pelepasan = $this->Mimpxllhkpn->add_data_pelepasan($imported_data->response_data_pelepasan, $temp_lhkpn_id);
                $response = array(
                    "msg" => "success",
                    "tmp_idx" => $temp_lhkpn_id
                );
            }
        }// check screening valid end
        else {


            /**
             * INSERT TO IMP_XL_LHKPN and PENERIMAAN
             */
            $penerimaan["JENIS_LAPORAN"] = $this->input->post("JENIS_LAPORAN");

            $penerimaan["TANGGAL_PELAPORAN"] = $penerimaan["JENIS_LAPORAN"] != '4' ? $this->input->post("TANGGAL_PELAPORAN") : NULL;
            $penerimaan["TAHUN_PELAPORAN"] = $penerimaan["JENIS_LAPORAN"] == '4' ? $this->input->post("TAHUN_PELAPORAN") : NULL;
            $penerimaan["VERSI_EXCEL"] = NULL;

            if (!is_null($penerimaan["TANGGAL_PELAPORAN"])) {
                $penerimaan["TANGGAL_PELAPORAN"] = to_mysql_date($penerimaan["TANGGAL_PELAPORAN"]);
            }

            $record_set_found = $this->find_penerimaan_offline($penerimaan, 'any', $is_double_confirmed);

            $temp_lhkpn_id = $this->Mimpxllhkpn->add_new($penerimaan["JENIS_LAPORAN"], $penerimaan["ID_PN"], $penerimaan["TANGGAL_PELAPORAN"], $penerimaan["TANGGAL_PENERIMAAN"], $T_PN_JABATAN->NIP_NRP, '1', $penerimaan["STAT"], $penerimaan["USERNAME"]);
            $penerimaan['id_imp_xl_lhkpn'] = $temp_lhkpn_id;
            $this->Mlhkpnofflinepenerimaan->add_new($penerimaan);

            $response = array(
                "msg" => "success",
                "tmp_idx" => 0
            );
        }


        if ($screening_valid == '0' && strlen(trim($email_screening_to)) > 0 && strlen(trim($uraian_screening)) > 0) {

            $file_excel = self::DIR_TEMP_UPLOAD . $random_id . '/' . $penerimaan['UPLOADED_DOCUMENT'];

            $uraian_screening = $this->screening_email_preview([$nama_pn, $nama_lembaga, str_replace(PHP_EOL, "<br>&emsp;&emsp;&emsp;", $uraian_screening)]);

//            $uraian_screening = '<p>Yth. Sdr. <b>Ir. WAHYU WIDODO SIRINGO RINGO, M.Phil</b><br />
//            <b>KOMISI PEMBERANTASAN KORUPSI (KPK)</b><br />
//            Di Tempat<br />
//            <br />
//                &emsp;&emsp;&emsp;Berdasarkan hasil verifikasi awal terhadap LHKPN Format Excel yang telah Saudara sampaikan, LHKPN Saudara dinyatakan tidak dapat diproses dikarenakan tidak memenuhi kriteria yang telah ditetapkan. Untuk pemrosesan lebih lanjut, Saudara diminta untuk mengirimkan kembali LHKPN Format Excel ke Komisi Pemberantasan Korupsi dan melengkapi data LHKPN sebelumnya berupa data sebagai berikut :</p>&emsp;&emsp;&emsp;' .
//                    str_replace(PHP_EOL, "<br>&emsp;&emsp;&emsp;", $uraian_screening) .
//                    '<p>&emsp;&emsp;&emsp;Email pemberitahuan ini tidak dapat digunakan sebagai tanda terima LHKPN. Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id atau call center 198.<br /><br />
//                Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br /><br />
//                Direktorat Pendaftaran dan Pemeriksaan LHKPN<br />
//                --------------------------------------------------------------<br />
//                Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br />
//                © 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</p>';
            ng::mail_send($email_screening_to, 'Hasil Screening LHKPN Offline', $uraian_screening, NULL, $file_excel);
        }

//        $username = $this->mglobal->get_data_all('T_PN', null, ['ID_PN' => $this->input->post('ID_PN')], 'NIK')[0]->NIK;
//        $history = [
//            'ID_LHKPN' => $temp_lhkpn_id,
////                        'ID_STATUS' => '11',
//            'ID_STATUS' => '1',
//            'USERNAME_PENGIRIM' => $username,
//            'USERNAME_PENERIMA' => $this->session->userdata('USR'),
//            'DATE_INSERT' => date('Y-m-d H:i:s'),
//            'CREATED_IP' => $this->input->ip_address()
//        ];
//
//        $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);


        header("Content-type:application/json");
        echo json_encode((object) $response);
    }

    public function excel_verification() {
        echo "halo";
        exit;
    }

    public function simpan_upload() {

        $id_lhkpn_secured = $this->input->post('inpkhl');
        $fnm = $this->input->post('fnm');
        $dtp = $this->input->post('dtp');

        $lhkpn_found = $this->mglobal->secure_get_by_id("t_imp_xl_lhkpn", "id_imp_xl_lhkpn", "id_imp_xl_lhkpn", $id_lhkpn_secured);

        $upload_data_type = array("skm" => "FILE_BUKTI_SKM", "skb" => "FILE_BUKTI_SK", "ikhtisar" => "FILE_BUKTI_IKHTISAR", "dok" => FALSE);

        if ($lhkpn_found && array_key_exists($dtp, $upload_data_type) && $dtp != FALSE) {
            $update_field = $upload_data_type[$dtp];
            if ($update_field) {
                $arr_file = explode(", ", trim($lhkpn_found->{$update_field}));

                if (!is_array($arr_file) || ((is_array($arr_file) && current($arr_file) == ""))) {
                    $arr_file = array();
                }

                array_push($arr_file, $fnm);

                $update_file = implode(", ", $arr_file);
                $this->mglobal->update("t_imp_xl_lhkpn", array($update_field => $update_file), " id_imp_xl_lhkpn = '" . $lhkpn_found->id_imp_xl_lhkpn . "' ");
                unset($arr_file, $update_file, $lhkpn_found);
            }
            $dtp = 'dok';
        }

        if ($dtp == 'dok') {
            echo '1';
            return;
        }
        echo '0';
        return;
    }

    public function simpan_add() {
        /* echo 'penugasan';
          echo '<pre>';
          print_r($_POST);
          echo '</pre>'; */

        $T_PN_JABATAN = $this->__get_t_pn_jabatan($this->input->post('ID_PN', TRUE));

        if ($this->input->post('act', TRUE) == 'doinsert') {
            /**
             * Penerimaan Add
             */
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $penerimaan = array(
                'ID_PN' => $this->input->post('ID_PN', TRUE),
                'JENIS_LAPORAN' => $this->input->post('JENIS_LAPORAN', TRUE),
                'TANGGAL_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) <> '4') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN', TRUE)))) : date('Y-m-d', strtotime($this->input->post('TAHUN_PELAPORAN', TRUE) . '-12-30')),
                'TAHUN_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) == '4') ? $this->input->post('TAHUN_PELAPORAN', TRUE) : '0',
                'JABATAN' => $T_PN_JABATAN->NAMA_JABATAN,
                'DESKRIPSI_JABATAN' => $T_PN_JABATAN->DESKRIPSI_JABATAN,
                'LEMBAGA' => $T_PN_JABATAN->NAMA_LEMBAGA,
                'UNIT_KERJA' => $T_PN_JABATAN->UNIT_KERJA,
                'JENIS_DOKUMEN' => $this->input->post('JENIS_DOKUMEN', TRUE),
                'MELALUI' => $this->input->post('MELALUI', TRUE),
                'USERNAME' => $this->session->userdata('USR'),
                'TANGGAL_PENERIMAAN' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PENERIMAAN', TRUE)))),
                'STAT' => 1,
                'IS_ACTIVE' => 1,
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );

            $penerimaan['ID_PENERIMAAN'] = $this->input->post('ID_PENERIMAAN', TRUE);
            $this->db->where('ID_PENERIMAAN', $penerimaan['ID_PENERIMAAN']);
            $this->db->update('T_LHKPNOFFLINE_PENERIMAAN', $penerimaan);
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            /* yang lama menghapus=menonaktifkan
              $penerimaan = array(
              'IS_ACTIVE'			=> -1,
              'UPDATED_TIME'		=> time(),
              'UPDATED_BY'		=> $this->session->userdata('USR'),
              'UPDATED_IP'		=> $_SERVER["REMOTE_ADDR"],
              );
              $penerimaan['ID_PENERIMAAN']    = $this->input->post('ID_PENERIMAAN', TRUE);
              $this->db->where('ID_PENERIMAAN', $penerimaan['ID_PENERIMAAN']);
              $this->db->update('T_LHKPNOFFLINE_PENERIMAAN', $penerimaan);
             */
            //yang baru, menghapus=mengahpus dari database

            $penerimaan['ID_PENERIMAAN'] = $this->input->post('ID_PENERIMAAN', TRUE);
            $this->db->select('*');
            $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
            $this->db->where('ID_PENERIMAAN', $penerimaan['ID_PENERIMAAN']);
            $qGet = $this->db->get()->row();
            $id_pns = $qGet->ID_PN;
            $id_lhkpns = $qGet->ID_LHKPN;
            //hapus penerimaan
            $this->db->delete('T_LHKPNOFFLINE_PENERIMAAN', array('ID_PENERIMAAN' => $penerimaan['ID_PENERIMAAN']));
            //hapus penugasan entry
            $this->db->delete('T_LHKPNOFFLINE_PENUGASAN_ENTRY', array('ID_LHKPN' => $id_lhkpns));
            //hapus penugasan verifikasi
            $this->db->delete('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus fasilitas
            $this->db->delete('T_LHKPN_FASILITAS', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus harta bergerak
            $this->db->delete('T_LHKPN_HARTA_BERGERAK', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus harta bergerak lain
            $this->db->delete('T_LHKPN_HARTA_BERGERAK_LAIN', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus harta kas
            $this->db->delete('T_LHKPN_HARTA_KAS', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus harta lainnya
            $this->db->delete('T_LHKPN_HARTA_LAINNYA', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus harta surat berharga
            $this->db->delete('T_LHKPN_HARTA_SURAT_BERHARGA', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus harta tidak bergerak
            $this->db->delete('T_LHKPN_HARTA_TIDAK_BERGERAK', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus hutang
            $this->db->delete('T_LHKPN_HUTANG', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus penerimaan kas
            $this->db->delete('T_LHKPN_PENERIMAAN_KAS', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus pengeluaran kas
            $this->db->delete('T_LHKPN_PENGELUARAN_KAS', array('ID_LHKPN' => $id_lhkpns));
            $this->db->flush_cache();
            //hapus penjualan
            $this->db->delete('T_LHKPN_PENJUALAN', array('ID_LHKPN' => $id_lhkpns));
        }
    }

    public function edit($mode = '', $id = '', $display = '') {
        $this->data['urlSave'] = site_url('efill/' . strtolower(__CLASS__) . '/' . 'save' . '/' . $mode);
        $this->data['target'] = $this->input->post('CEK_PENERIMAAN');
        if ($id) {
            if ($display == 'detail') {
                $this->data['form'] = $display;
                $this->data['act'] = '';
            } else if ($display == 'delete') {
                $this->data['form'] = $display;
                $this->data['act'] = 'dodelete';
            } else if ($display) {
                $this->data['form'] = $display;
                $this->data['act'] = 'do' . $display;
            } else if ($display == '') {
                $this->data['form'] = 'edit';
                $this->data['act'] = 'doupdate';
            }
            if ($id == 'editcheck') {
                $this->data['form'] = 'editcheck';
                $this->data['act'] = 'doupdatecek';
            }
        } else {
            $this->data['form'] = 'add';
            $this->data['act'] = 'doinsert';
        }

        switch ($mode) {
            case 'penerimaan':
                $iniJoin = [
                    ['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPNOFFLINE_PENERIMAAN.ID_PN', 'join' => 'left'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA', 'join' => 'left'],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'join' => 'left'],
                    ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'join' => 'left']
                ];
                $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPNOFFLINE_PENERIMAAN', $iniJoin, ['ID_PENERIMAAN' => $id])[0] : '';

//                $this->insertHistoryLHKPN(@$this->data['item']->ID_LHKPN, '12', $this->session->userdata('USR'));
                $this->insertHistoryLHKPN(@$this->data['item']->ID_LHKPN, '2', $this->session->userdata('USR'));
                break;
            case 'penugasan':
                $this->data['role'] = urlencode($this->config->item('LHKPNOFFLINE_PAGE_ROLE')[$mode]);
                if ($id != 'editcheck') {
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPNOFFLINE_PENUGASAN_ENTRY', NULL, ['ID_TUGAS' => $id])[0] : '';
                }
                break;
            case 'tugas':
                $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPNOFFLINE_PENUGASAN_ENTRY', NULL, ['ID_TUGAS' => $id])[0] : '';
                break;

            case 'bast':
                $iniJoin = [
                    ['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPNOFFLINE_PENERIMAAN.ID_PN', 'join' => 'left'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA', 'join' => 'left'],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'join' => 'left'],
                    ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'join' => 'left'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID= T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'join' => 'left']
                ];
                $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPNOFFLINE_PENERIMAAN', $iniJoin, ['ID_PENERIMAAN' => $id])[0] : '';

                $barcode = $this->data['item']->TAHUN_PELAPORAN . '/' . ($this->data['item']->JENIS_LAPORAN == '4' ? 'R' : ($this->data['item']->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $this->data['item']->NIK . '/' . $this->data['item']->ID_LHKPN;

                $this->data['barcode'] = $barcode;
                $this->data['form'] = 'coversheet';
                break;

            default:
                # code...
                break;
        }

        $data_form = $this->data;

        $this->data['form_view'] = $this->load->view(strtolower(__CLASS__) . '/penerimaan/' . strtolower(__CLASS__) . '_penerimaan_form_' . $this->data['form'], $data_form, TRUE);

        // load view
        if ($display == 'printitem') {

            $sql = "SELECT
            CORE_SETTING.`OWNER`,
            CORE_SETTING.SETTING,
            CORE_SETTING.`VALUE`
            FROM
            CORE_SETTING
            WHERE
            CORE_SETTING.`OWNER` = 'app.lhkpn'
            AND CORE_SETTING.SETTING = 'tts'";

            $row = $this->db->query($sql)->row();
            $s = FALSE;
            if ($row) {
                $s = json_decode(@$row->VALUE);
            }
            $this->data['s'] = $s;
            unset($row);

            $html = $this->load->view($mode ? strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . $mode . '_form' : strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $this->data, true);
            //$this->load->library('pdf');
            //$pdf = $this->pdf->load();
            //$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
            if ($mode != 'penerimaan' AND $mode != 'bast') {
                $pdf->SetFooter('|{PAGENO}|');
            }
            try {
                include_once APPPATH . 'third_party/TCPDF/tcpdf.php';
                $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
                $pdf->SetFont('dejavusans', '', 9);
                $pdf->AddPage();
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->lastPage();
                $pdf->Output('cetak.pdf', 'I');
            } catch (Exception $e) {

            }

            //$pdf->WriteHTML($html); // write the HTML into the PDF
            // $pdf->Output($pdfFilePath, 'F'); // save to file because we can
            //$pdf->Output();
        } else {
            $this->load->view($mode ? strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . $mode . '_form' : strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $this->data);
        }
    }

    public function save($mode = '') {
        $this->db->trans_begin();
        switch ($mode) {
            case 'pn':
                // echo 'pn';
                // echo '<pre>';
                // print_r($_POST);
                // echo '</pre>';

                if ($this->input->post('act', TRUE) == 'doinsertpn') {
                    $pn = array(
                        // 'ID_PN' 			=> $this->input->post('ID_PN', TRUE),
                        'NIK' => $this->input->post('NIK', TRUE),
                        'NAMA' => $this->input->post('NAMA_LENGKAP', TRUE),
                        'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR', TRUE),
                        'TGL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL_LAHIR', TRUE)))),
                        // 'ID_AGAMA' 			=> $this->input->post('ID_AGAMA', TRUE),
                        // 'ID_STATUS_NIKAH' 	=> $this->input->post('ID_STATUS_NIKAH', TRUE),
                        // 'ID_PENDIDIKAN' 	=> $this->input->post('ID_PENDIDIKAN', TRUE),
                        // 'NPWP' 				=> $this->input->post('NPWP', TRUE),
                        // 'ALAMAT_TINGGAL' 	=> $this->input->post('ALAMAT_TINGGAL', TRUE),
                        'EMAIL' => $this->input->post('EMAIL', TRUE),
                        'NO_HP' => $this->input->post('NO_HP', TRUE),
                        // 'JABATAN' 			=> $this->input->post('JABATAN', TRUE),
                        // 'BIDANG' 			=> $this->input->post('BIDANG', TRUE),
                        // 'LEMBAGA' 			=> $this->input->post('LEMBAGA', TRUE),
                        // 'TINGKAT' 			=> $this->input->post('TINGKAT', TRUE),
                        // 'UNIT_KERJA' 		=> $this->input->post('UNIT_KERJA', TRUE),
                        // 'ALAMAT_KANTOR' 	=> $this->input->post('ALAMAT_KANTOR', TRUE),
                        'IS_ACTIVE' => '1',
                        'CREATED_TIME' => time(),
                        'CREATED_BY' => 'admin', //$this->session->userdata('USR'),
                        'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                            // 'UPDATED_TIME'     => time(),
                            // 'UPDATED_BY'     => $this->session->userdata('USR'),
                            // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   en
                    );
                    $this->db->insert('T_PN', $pn);
                    ng::logActivity('Add PN via Penerimaan');
                }
                break;
            case 'penerimaan':
                /* echo 'penugasan';
                  echo '<pre>';
                  print_r($_POST);
                  echo '</pre>'; */

                $this->db->where('ID_PN', $this->input->post('ID_PN', TRUE));
                // $this->db->where('IS_PRIMARY','1');
                $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_pn_jabatan.ID_JABATAN');
                $T_PN_JABATAN = $this->db->get('t_pn_jabatan')->row();

                if ($this->input->post('act', TRUE) == 'doinsert') {

                    $penerimaan = array(
                        'ID_PN' => $this->input->post('ID_PN', TRUE),
                        'JENIS_LAPORAN' => $this->input->post('JENIS_LAPORAN', TRUE),
                        'TANGGAL_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) <> '4') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN', TRUE)))) : date('Y-m-d', strtotime($this->input->post('TAHUN_PELAPORAN', TRUE) . '-12-30')),
                        'TAHUN_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) == '4') ? $this->input->post('TAHUN_PELAPORAN', TRUE) : date('Y', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN', TRUE)))),
                        'JABATAN' => $T_PN_JABATAN->ID_JABATAN,
                        'DESKRIPSI_JABATAN' => $T_PN_JABATAN->DESKRIPSI_JABATAN,
                        'LEMBAGA' => $T_PN_JABATAN->LEMBAGA,
                        'UNIT_KERJA' => $T_PN_JABATAN->UK_ID,
                        'JENIS_DOKUMEN' => $this->input->post('JENIS_DOKUMEN', TRUE),
                        'MELALUI' => $this->input->post('MELALUI', TRUE),
                        'USERNAME' => $this->session->userdata('USR'),
                        'USERNAME_CS' => $this->session->userdata('USR'),
                        'TANGGAL_PENERIMAAN' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PENERIMAAN', TRUE)))),
                        'STAT' => 1,
                        'IS_ACTIVE' => 1,
                        'CREATED_TIME' => time(),
                        'CREATED_BY' => $this->session->userdata('USR'),
                        'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    );

                    $this->load->library('lhkpn_lib');
//
                    $jenis = $this->input->post('JENIS_LAPORAN');
                    if ($jenis == '4') {
                        $tahun = $this->input->post('TAHUN_PELAPORAN');
                    } else {
                        $tahun = date('Y', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN'))));
                    }

                    $result = $this->lhkpn_lib->copylhkpn($this->input->post('ID_PN'), $tahun, '1', 'penerimaan');

                    if ($result != false || $result == '1') {
                        $penerimaan['ID_LHKPN'] = $result;
                        $this->db->insert('T_LHKPNOFFLINE_PENERIMAAN', $penerimaan);
                    } else {
                        $this->db->trans_rollback();
                        return '0';
                    }

                    $username = $this->mglobal->get_data_all('T_PN', null, ['ID_PN' => $this->input->post('ID_PN')], 'NIK')[0]->NIK;
                    $history = [
                        'ID_LHKPN' => $result,
//                        'ID_STATUS' => '11',
                        'ID_STATUS' => '1',
                        'USERNAME_PENGIRIM' => $username,
                        'USERNAME_PENERIMA' => $this->session->userdata('USR'),
                        'DATE_INSERT' => date('Y-m-d H:i:s'),
                        'CREATED_IP' => $this->input->ip_address()
                    ];

                    $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                } else if ($this->input->post('act', TRUE) == 'doupdate') {
                    $penerimaan = array(
                        'ID_PN' => $this->input->post('ID_PN', TRUE),
                        'JENIS_LAPORAN' => $this->input->post('JENIS_LAPORAN', TRUE),
                        'TANGGAL_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) <> '4') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN', TRUE)))) : date('Y-m-d', strtotime($this->input->post('TAHUN_PELAPORAN', TRUE) . '-12-30')),
                        'TAHUN_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) == '4') ? $this->input->post('TAHUN_PELAPORAN', TRUE) : '0',
                        'JABATAN' => $T_PN_JABATAN->NAMA_JABATAN,
                        'DESKRIPSI_JABATAN' => $T_PN_JABATAN->DESKRIPSI_JABATAN,
                        'LEMBAGA' => $T_PN_JABATAN->NAMA_LEMBAGA,
                        'UNIT_KERJA' => $T_PN_JABATAN->UNIT_KERJA,
                        'JENIS_DOKUMEN' => $this->input->post('JENIS_DOKUMEN', TRUE),
                        'MELALUI' => $this->input->post('MELALUI', TRUE),
                        'USERNAME' => $this->session->userdata('USR'),
                        'TANGGAL_PENERIMAAN' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PENERIMAAN', TRUE)))),
                        'STAT' => 1,
                        'IS_ACTIVE' => 1,
                        'UPDATED_TIME' => time(),
                        'UPDATED_BY' => $this->session->userdata('USR'),
                        'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                    );

                    $penerimaan['ID_PENERIMAAN'] = $this->input->post('ID_PENERIMAAN', TRUE);
                    $this->db->where('ID_PENERIMAAN', $penerimaan['ID_PENERIMAAN']);
                    $this->db->update('T_LHKPNOFFLINE_PENERIMAAN', $penerimaan);
                } else if ($this->input->post('act', TRUE) == 'dodelete') {
                    /* yang lama menghapus=menonaktifkan
                      $penerimaan = array(
                      'IS_ACTIVE'			=> -1,
                      'UPDATED_TIME'		=> time(),
                      'UPDATED_BY'		=> $this->session->userdata('USR'),
                      'UPDATED_IP'		=> $_SERVER["REMOTE_ADDR"],
                      );
                      $penerimaan['ID_PENERIMAAN']    = $this->input->post('ID_PENERIMAAN', TRUE);
                      $this->db->where('ID_PENERIMAAN', $penerimaan['ID_PENERIMAAN']);
                      $this->db->update('T_LHKPNOFFLINE_PENERIMAAN', $penerimaan);
                     */
                    //yang baru, menghapus=mengahpus dari database

                    $penerimaan['ID_PENERIMAAN'] = $this->input->post('ID_PENERIMAAN', TRUE);
                    $this->db->select('*');
                    $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                    $this->db->where('ID_PENERIMAAN', $penerimaan['ID_PENERIMAAN']);
                    $qGet = $this->db->get()->row();
                    $id_pns = $qGet->ID_PN;
                    $id_lhkpns = $qGet->ID_LHKPN;
                    //hapus penerimaan
                    $this->db->delete('T_LHKPNOFFLINE_PENERIMAAN', array('ID_PENERIMAAN' => $penerimaan['ID_PENERIMAAN']));
                    //hapus penugasan entry
                    $this->db->delete('T_LHKPNOFFLINE_PENUGASAN_ENTRY', array('ID_LHKPN' => $id_lhkpns));
                    //hapus penugasan verifikasi
                    $this->db->delete('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus fasilitas
                    $this->db->delete('T_LHKPN_FASILITAS', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus harta bergerak
                    $this->db->delete('T_LHKPN_HARTA_BERGERAK', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus harta bergerak lain
                    $this->db->delete('T_LHKPN_HARTA_BERGERAK_LAIN', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus harta kas
                    $this->db->delete('T_LHKPN_HARTA_KAS', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus harta lainnya
                    $this->db->delete('T_LHKPN_HARTA_LAINNYA', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus harta surat berharga
                    $this->db->delete('T_LHKPN_HARTA_SURAT_BERHARGA', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus harta tidak bergerak
                    $this->db->delete('T_LHKPN_HARTA_TIDAK_BERGERAK', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus hutang
                    $this->db->delete('T_LHKPN_HUTANG', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus penerimaan kas
                    $this->db->delete('T_LHKPN_PENERIMAAN_KAS', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus pengeluaran kas
                    $this->db->delete('T_LHKPN_PENGELUARAN_KAS', array('ID_LHKPN' => $id_lhkpns));
                    $this->db->flush_cache();
                    //hapus penjualan
                    $this->db->delete('T_LHKPN_PENJUALAN', array('ID_LHKPN' => $id_lhkpns));
                }
                break;
            case 'penugasan':
                // echo 'penugasan';
                // echo '<pre>';
                // print_r($_POST);
                // echo '</pre>';

                if ($this->input->post('act', TRUE) == 'doinsert') {
                    $ID_PENERIMAANS = explode(',', $this->input->post('id'));
                    if (count($ID_PENERIMAANS)) {
                        for ($i = 0; $i < count($ID_PENERIMAANS); $i++) {
                            $ID_PENERIMAAN = $ID_PENERIMAANS[$i];
                            $ID_LHKPN = $this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', NULL, ['ID_PENERIMAAN' => $ID_PENERIMAAN], 'ID_LHKPN')[0]->ID_LHKPN;

                            // insert penugasan
                            $penugasan = array(
                                'ID_PENERIMAAN' => $ID_PENERIMAAN,
                                'USERNAME' => $this->input->post('PETUGAS', TRUE),
                                'ID_LHKPN' => $ID_LHKPN,
                                'TANGGAL_PENUGASAN' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PENUGASAN', TRUE)))),
                                'DUE_DATE' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('DUE_DATE', TRUE)))),
                                'KETERANGAN' => $this->input->post('KETERANGAN', TRUE),
                                'STAT' => 1,
                                'IS_ACTIVE' => 1,
                                'CREATED_TIME' => time(),
                                'CREATED_BY' => $this->session->userdata('USR'),
                                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                                    // 'UPDATED_TIME'   => time(),
                                    // 'UPDATED_BY'     => $this->session->userdata('USR'),
                                    // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],
                            );
                            $this->db->insert('T_LHKPNOFFLINE_PENUGASAN_ENTRY', $penugasan);
                            $this->db->flush_cache();
                            // set penerimaan as ditugaskan
                            $penerimaan['STAT'] = 2;
                            $this->db->where('ID_PENERIMAAN', $ID_PENERIMAAN);
                            $this->db->update('T_LHKPNOFFLINE_PENERIMAAN', $penerimaan);

                            $this->insertHistoryLHKPN($ID_LHKPN, '16', $this->session->userdata('USR'), $this->input->post('PETUGAS', TRUE));

                            $lhkpn_data = [
                                'USERNAME_ENTRI' => $this->input->post('PETUGAS', TRUE),
                            ];
                            $this->db->where('ID_LHKPN', $ID_LHKPN);
                            $this->db->update('T_LHKPN', $lhkpn_data);
                        }
                    }
                } else if ($this->input->post('act', TRUE) == 'doupdate') {
                    $penugasan = array(
                        'ID_PENERIMAAN' => $this->input->post('ID_PENERIMAAN', TRUE),
                        'USERNAME' => $this->input->post('USERNAME', TRUE),
                        'TANGGAL_PENUGASAN' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PENUGASAN', TRUE)))),
                        'DUE_DATE' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('DUE_DATE', TRUE)))),
                        'KETERANGAN' => $this->input->post('KETERANGAN', TRUE),
                        // 'STAT'         => $this->input->post('STAT', TRUE),
                        // 'IS_ACTIVE'        => $this->input->post('IS_ACTIVE', TRUE),
                        // 'CREATED_TIME'     => time(),
                        // 'CREATED_BY'     => $this->session->userdata('USR'),
                        // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                        'UPDATED_TIME' => time(),
                        'UPDATED_BY' => $this->session->userdata('USR'),
                        'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                    );
                    $penugasan['ID_TUGAS'] = $this->input->post('ID_TUGAS', TRUE);
                    $this->db->where('ID_TUGAS', $penugasan['ID_TUGAS']);
                    $this->db->update('T_LHKPNOFFLINE_PENUGASAN_ENTRY', $penugasan);
                } else if ($this->input->post('act', TRUE) == 'doupdatecek') {
                    $target = explode(',', $this->input->post('id'));
                    $penugasan = array(
                        // 'ID_PENERIMAAN'     => $this->input->post('ID_PENERIMAAN', TRUE),
                        'USERNAME' => $this->input->post('USERNAME', TRUE),
                        'TANGGAL_PENUGASAN' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PENUGASAN', TRUE)))),
                        'DUE_DATE' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('DUE_DATE', TRUE)))),
                        'KETERANGAN' => $this->input->post('KETERANGAN', TRUE),
                        // 'STAT'         => $this->input->post('STAT', TRUE),
                        // 'IS_ACTIVE'        => $this->input->post('IS_ACTIVE', TRUE),
                        // 'CREATED_TIME'     => time(),
                        // 'CREATED_BY'     => $this->session->userdata('USR'),
                        // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                        'UPDATED_TIME' => time(),
                        'UPDATED_BY' => $this->session->userdata('USR'),
                        'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                    );
                    foreach ($target as $key) {
                        $this->db->where('ID_PENERIMAAN', $key);
                        $this->db->update('T_LHKPNOFFLINE_PENUGASAN_ENTRY', $penugasan);
                    }
                } else if ($this->input->post('act', TRUE) == 'dodelete') {
                    $penugasan = array(
                        'IS_ACTIVE' => -1,
                        // 'CREATED_TIME'     => time(),
                        // 'CREATED_BY'     => $this->session->userdata('USR'),
                        // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                        'UPDATED_TIME' => time(),
                        'UPDATED_BY' => $this->session->userdata('USR'),
                        'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                    );
                    $penugasan['ID_TUGAS'] = $this->input->post('ID_TUGAS', TRUE);
                    $this->db->where('ID_TUGAS', $penugasan['ID_TUGAS']);
                    $this->db->update('T_LHKPNOFFLINE_PENUGASAN_ENTRY', $penugasan);
                }
                break;
            case 'tugas':

                break;
            case 'bast':

                $id = explode(',', $this->input->post('id'));
                $petugas = $this->input->post('PETUGAS');
                // $tanggal    = date('Y-m-d', strtotime($this->input->post('TANGGAL')));

                $this->db->trans_begin();

                if ($this->input->post('terima')) {
                    $data = [
                        'IS_DITERIMA_KOORD_CS' => '1',
                        'USERNAME_KOORD_CS' => $this->session->userdata('USERNAME'),
                            // 'TGL_BAST_KOORD_CS'     => $tanggal
                    ];
                } else if ($this->input->post('tolak')) {
                    $data = [
                        'IS_DITERIMA_KOORD_CS' => '0',
                        'USERNAME_KOORD_CS' => '',
                        'TGL_BAST_KOORD_CS' => ''
                    ];
                }

                $result = $this->mglobal->update('T_LHKPNOFFLINE_PENERIMAAN', $data, NULL, "ID_PENERIMAAN IN (" . $this->input->post('id') . ")");

                // History
                $tmp = $this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', null, null, 'ID_LHKPN, USERNAME_CS');
                foreach ($tmp as $row):
                    $check = $this->mglobal->count_data_all('T_LHKPN_STATUS_HISTORY', null, ['ID_LHKPN' => $row->ID_LHKPN, 'ID_STATUS' => '14']);
                    if ($check == '0') {
                        $history = [
                            'ID_LHKPN' => $row->ID_LHKPN,
                            'ID_STATUS' => '14',
                            'USERNAME_PENGIRIM' => $row->USERNAME_CS,
                            'USERNAME_PENERIMA' => $petugas,
                            'DATE_INSERT' => date('Y-m-d H:i:s'),
                            'CREATED_IP' => $this->input->ip_address()
                        ];

                        $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                    }
                endforeach;

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                if ($tmp[0])
                    $this->load->library('pdf');

                $this->db->start_cache();
                $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
                        T_PN.NIK,
                        T_PN.NAMA,
                        (SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
                        (SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
                        (SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
                    ');
                $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                $this->db->where('1=1', null, false);
                $this->db->where("ID_PENERIMAAN IN (" . $this->input->post('id') . ")", NULL, FALSE);
                $query = $this->db->get('', $this->limit, $this->offset);
                $result = $this->items = $query->result();

                $this->db->flush_cache();

                $join = [
                    ['table' => 'T_PN', 'on' => 'USERNAME=NIK']
                ];
                $dataA = [
                    'form' => 'bast',
                    // 'tanggal'       => tgl_format($tanggal),
                    'data' => $result,
                    'menerima' => $this->mglobal->get_data_all('T_USER', NULL, ['USERNAME' => $petugas], 'NAMA'),
                    'menyerahkan' => $this->mglobal->get_data_all('T_USER', NULL, ['USERNAME' => $result[0]->USERNAME], 'NAMA')
                ];

                $html = $this->load->view('lhkpnoffline/lhkpnoffline_bast_form', $dataA, true);
                $pdf = $this->pdf->load();
                //$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
                $pdf->SetFooter('|{PAGENO}|');
                $pdf->WriteHTML($html); // write the HTML into the PDF

                $time = time();
                $pdf->Output('uploads/pdf/' . $time . '.pdf', 'F'); // save to file because we can

                echo base_url('uploads/pdf/' . $time . '.pdf');
                die;
                break;
            case 'bastcetak':

                $id = explode(',', $this->input->post('id'));
                $petugas = $this->input->post('PETUGAS');
                // $tanggal    = date('Y-m-d', strtotime($this->input->post('TANGGAL')));

                $this->db->trans_begin();

                if ($this->input->post('terima')) {
                    $data = [
                        'IS_DITERIMA_KOORD_CS' => '1',
                        'USERNAME_KOORD_CS' => $this->session->userdata('USERNAME'),
                            // 'TGL_BAST_KOORD_CS'     => $tanggal
                    ];
                } else if ($this->input->post('tolak')) {
                    $data = [
                        'IS_DITERIMA_KOORD_CS' => '0',
                        'USERNAME_KOORD_CS' => '',
                        'TGL_BAST_KOORD_CS' => ''
                    ];
                }

                $result = $this->mglobal->update('T_LHKPNOFFLINE_PENERIMAAN', $data, NULL, "ID_PENERIMAAN IN (" . $this->input->post('id') . ")");

                // History
                $tmp = $this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', null, null, 'ID_LHKPN, USERNAME_CS');
                foreach ($tmp as $row):
                    $check = $this->mglobal->count_data_all('T_LHKPN_STATUS_HISTORY', null, ['ID_LHKPN' => $row->ID_LHKPN, 'ID_STATUS' => '14']);
                    if ($check == '0') {
                        $history = [
                            'ID_LHKPN' => $row->ID_LHKPN,
                            'ID_STATUS' => '14',
                            'USERNAME_PENGIRIM' => $row->USERNAME_CS,
                            'USERNAME_PENERIMA' => $petugas,
                            'DATE_INSERT' => date('Y-m-d H:i:s'),
                            'CREATED_IP' => $this->input->ip_address()
                        ];

                        $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                    }
                endforeach;

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                if ($tmp[0])
                    $this->load->library('pdf');

                $this->db->start_cache();
                $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
                        T_PN.NIK,
                        T_PN.NAMA,
                        (SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
                        (SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
                        (SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
                    ');
                $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                $this->db->where('1=1', null, false);
                $this->db->where("ID_PENERIMAAN IN (" . $this->input->post('id') . ")", NULL, FALSE);
                $query = $this->db->get('', $this->limit, $this->offset);
                $result = $this->items = $query->result();

                $this->db->flush_cache();

                $join = [
                    ['table' => 'T_PN', 'on' => 'USERNAME=NIK']
                ];
                $dataA = [
                    'form' => 'bast',
                    // 'tanggal'       => tgl_format($tanggal),
                    'data' => $result,
                    'menerima' => $this->mglobal->get_data_all('T_USER', NULL, ['USERNAME' => $petugas], 'NAMA'),
                    'menyerahkan' => $this->mglobal->get_data_all('T_USER', NULL, ['USERNAME' => $result[0]->USERNAME], 'NAMA')
                ];

                $html = $this->load->view('lhkpnoffline/lhkpnoffline_bast_form', $dataA, true);
                $pdf = $this->pdf->load();
                //$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
                $pdf->SetFooter('|{PAGENO}|');
                $pdf->WriteHTML($html); // write the HTML into the PDF

                $time = time();
                $pdf->Output('uploads/pdf/' . $time . '.pdf', 'F'); // save to file because we can

                echo base_url('uploads/pdf/' . $time . '.pdf');
                die;
                break;
            case 'bastcetakpenerimaan':
                $id = explode(',', $this->input->post('id'));
                $petugas = $this->input->post('PETUGAS');
                $tanggal = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL'))));
                $this->db->trans_begin();

                $data = [
                    'IS_DITERIMA_KOORD_CS' => '1',
                    'USERNAME_KOORD_CS' => $petugas,
                    'TGL_BAST_KOORD_CS' => $tanggal
                ];


                // --> UDAH OK
                $result = $this->mglobal->update('T_LHKPNOFFLINE_PENERIMAAN', $data, NULL, "ID_PENERIMAAN IN (" . $this->input->post('id') . ")");


                // History --> UDAH OK
                $tmp = $this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', null, null, 'ID_PENERIMAAN, ID_LHKPN, USERNAME_CS', "ID_PENERIMAAN IN (" . $this->input->post('id') . ")");
                $maxcounter_bast_cs = $this->mglobal->get_data_all('T_BAST_CS', NULL, NULL, "max(counter) as maxcounter")[0]->maxcounter;
                $m = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $nomor = ($maxcounter_bast_cs + 1) . '/T1/' . ($m[date('m') - 1]) . '/' . date('Y');
                $counter = ($maxcounter_bast_cs + 1);



                foreach ($tmp as $row):
                    $USERNAME_CS = $row->USERNAME_CS;
                endforeach;

                // insert header --> UDAH OK
                $bast_cs = [
                    'COUNTER' => $counter,
                    'NOMOR_BAST' => $nomor,
                    'TGL_PENYERAHAN' => $tanggal,
                    'USER_CS' => $USERNAME_CS,
                    'USER_KOORD_CS' => $petugas,
                ];

                $this->mglobal->insert('T_BAST_CS', $bast_cs);
                $id_bast = $this->db->insert_id();
                $ID_BAST_CS = $id_bast;


                // insert child
                foreach ($tmp as $row):
                    $check = $this->mglobal->count_data_all('T_LHKPN_STATUS_HISTORY', null, ['ID_LHKPN' => $row->ID_LHKPN, 'ID_STATUS' => '14']);
                    if ($check == '0') {
                        $history = [
                            'ID_LHKPN' => $row->ID_LHKPN,
//                            'ID_STATUS' => '14',
                            'ID_STATUS' => '5',
                            'USERNAME_PENGIRIM' => $row->USERNAME_CS,
                            'USERNAME_PENERIMA' => $petugas,
                            'DATE_INSERT' => date('Y-m-d H:i:s'),
                            'CREATED_IP' => $this->input->ip_address()
                        ];

                        $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                    }

                    $R_bast_cs = [
                        'ID_BAST_CS' => $id_bast,
                        'ID_PENERIMAAN' => $row->ID_PENERIMAAN,
                    ];

                    $this->mglobal->insert('R_BAST_CS', $R_bast_cs);
                endforeach;

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

//                $this->load->library('pdf');
                $this->db->select('
							R_BAST_CS.ID,
							R_BAST_CS.ID_BAST_CS,
							R_BAST_CS.ID_PENERIMAAN,
							T_LHKPNOFFLINE_PENERIMAAN.ID_PN,
							T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN,
							T_BAST_CS.COUNTER,
							T_BAST_CS.NOMOR_BAST,
							T_BAST_CS.TGL_PENYERAHAN,
							T_BAST_CS.USER_CS,
							T_BAST_CS.USER_KOORD_CS,
							T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN,
							T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN,
							T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN,
							T_LHKPNOFFLINE_PENERIMAAN.DESKRIPSI_JABATAN,
							T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA,
							T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA,
							T_LHKPNOFFLINE_PENERIMAAN.JENIS_DOKUMEN,
							T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN,
							M_INST_SATKER.INST_NAMA,
							M_UNIT_KERJA.UK_NAMA,
							T_PN.NAMA,
							T_PN.NIK,
							M_BIDANG.BDG_NAMA,
							T_USER_CS.NAMA AS NAMA_CS,
							T_USER_KOORD_CS.NAMA AS NAMA_KOORD_CS,
							M_JABATAN.NAMA_JABATAN
				    	');
                $this->db->from('T_BAST_CS');
                $this->db->join('R_BAST_CS', 'R_BAST_CS.ID_BAST_CS = T_BAST_CS.ID_BAST_CS');
                $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = R_BAST_CS.ID_PENERIMAAN');
                $this->db->join('M_INST_SATKER', 'T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'LEFT');
                $this->db->join('M_UNIT_KERJA', 'T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA = M_UNIT_KERJA.UK_ID', 'LEFT');
                $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                $this->db->join('M_BIDANG', 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID', 'LEFT');
                $this->db->join('T_USER AS T_USER_CS', 'T_USER_CS.USERNAME = T_BAST_CS.USER_CS');
                $this->db->join('T_USER AS T_USER_KOORD_CS', 'T_USER_KOORD_CS.USERNAME = T_BAST_CS.USER_KOORD_CS');
                $this->db->join('M_JABATAN', 'T_LHKPNOFFLINE_PENERIMAAN.JABATAN = M_JABATAN.ID_JABATAN', 'LEFT');
                $this->db->where('1=1', null, false);
                $this->db->where("T_BAST_CS.ID_BAST_CS", $ID_BAST_CS, NULL, FALSE);

                $query = $this->db->get('', $this->limit, $this->offset);
                $result = $this->items = $query->result();


                $this->db->flush_cache();

                $join = [
                    ['table' => 'T_PN', 'on' => 'USERNAME=NIK']
                ];
                $dataA = [
                    'form' => 'bast',
                    'data' => $result,
                ];

                $html = $this->load->view('lhkpnoffline/lhkpnoffline_bast_form', $dataA, true);
                $time = time();
//                var_dump($dataA);exit;
                try {
                    include_once APPPATH . 'third_party/TCPDF/tcpdf.php';
                    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
                    $pdf->SetFont('dejavusans', '', 9);
//                    $pdf->SetFooter('|{PAGENO}|');
                    $pdf->AddPage();
                    $pdf->writeHTML($html, true, false, true, false, '');
                    $pdf->lastPage();
//                    $pdf->Output('cetak.pdf', 'I');
                    $pdf->Output($_SERVER['DOCUMENT_ROOT'] . 'elhkpndev/uploads/pdf/' . $time . '.pdf', 'F');
                } catch (Exception $e) {

                }
//                $pdf = $this->pdf->load();
                //$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
//                $pdf->SetFooter('|{PAGENO}|'); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
//                $pdf->WriteHTML($html); // write the HTML into the PDF
//                $time = time();
//                $pdf->Output('uploads/pdf/' . $time . '.pdf', 'F'); // save to file because we can

                echo base_url('uploads/pdf/' . $time . '.pdf');
                die;
                break;
            case 'bastentry':
                $id = explode(',', $this->input->post('id'));
                $petugas = $this->input->post('PETUGAS');
                $tanggal = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL'))));

                $this->db->trans_begin();

                $data = [
                    'IS_DITERIMA_KOORD_ENTRY' => '1',
                    'USERNAME_KOORD_ENTRY' => $petugas,
                    'TGL_BAST_KOORD_ENTRY' => $tanggal
                ];

                $result = $this->mglobal->update('T_LHKPNOFFLINE_PENERIMAAN', $data, NULL, "ID_PENERIMAAN IN (" . $this->input->post('id') . ")");

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }


                // History
                $tmp = $this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', null, null, 'ID_PENERIMAAN, ID_LHKPN, USERNAME_CS, USERNAME_KOORD_CS', "ID_PENERIMAAN IN (" . $this->input->post('id') . ")");
                $maxcounter_bast_entry = $this->mglobal->get_data_all('T_BAST_ENTRY', NULL, NULL, "max(counter) as maxcounter")[0]->maxcounter;
                $m = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $nomor = ($maxcounter_bast_entry + 1) . '/T2/' . ($m[date('m') - 1]) . '/' . date('Y');
                $counter = ($maxcounter_bast_entry + 1);

                foreach ($tmp as $row):
                    // $USERNAME_CS = $row->USERNAME_CS;
                    $USERNAME_KOORD_CS = $row->USERNAME_KOORD_CS;
                endforeach;

                // insert header
                $bast_entry = [
                    'COUNTER' => $counter,
                    'NOMOR_BAST' => $nomor,
                    'TGL_PENYERAHAN' => $tanggal,
                    // 'USER_KOORD_CS' 	=> $USERNAME_CS,
                    'USER_KOORD_CS' => $USERNAME_KOORD_CS,
                    'USER_KOORD_ENTRI' => $petugas,
                ];

                $this->mglobal->insert('T_BAST_ENTRY', $bast_entry);
                $id_bast = $this->db->insert_id();
                $ID_BAST_ENTRI = $id_bast;

                // insert child
                foreach ($tmp as $row):
                    $this->insertHistoryLHKPN($row->ID_LHKPN, '15', $this->session->userdata('USR'), $petugas);

                    $R_bast_entry = [
                        'ID_BAST_ENTRI' => $id_bast,
                        'ID_PENERIMAAN' => $row->ID_PENERIMAAN,
                    ];

                    $this->mglobal->insert('R_BAST_ENTRY', $R_bast_entry);
                endforeach;

                $this->load->library('pdf');
                $this->db->start_cache();
                $this->db->select('
								R_BAST_ENTRY.ID,
								R_BAST_ENTRY.ID_BAST_ENTRI,
								R_BAST_ENTRY.ID_PENERIMAAN,
								T_LHKPNOFFLINE_PENERIMAAN.ID_PN,
								T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN,
								T_BAST_ENTRY.COUNTER,
								T_BAST_ENTRY.NOMOR_BAST,
								T_BAST_ENTRY.TGL_PENYERAHAN,
								T_BAST_ENTRY.USER_KOORD_CS,
								T_BAST_ENTRY.USER_KOORD_ENTRI,
								T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN,
								T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN,
								T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN,
								T_LHKPNOFFLINE_PENERIMAAN.DESKRIPSI_JABATAN,
								T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA,
								T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA,
								T_LHKPNOFFLINE_PENERIMAAN.JENIS_DOKUMEN,
								T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN,
								M_INST_SATKER.INST_NAMA,
								M_UNIT_KERJA.UK_NAMA,
								T_PN.NAMA,
								T_PN.NIK,
								M_BIDANG.BDG_NAMA,
								T_USER_KOORD_CS.NAMA AS NAMA_KOORD_CS,
								T_USER_KOORD_ENTRI.NAMA AS NAMA_KOORD_ENTRI,
								M_JABATAN.NAMA_JABATAN
					    	');
                $this->db->from('T_BAST_ENTRY');
                $this->db->join('R_BAST_ENTRY', 'R_BAST_ENTRY.ID_BAST_ENTRI = T_BAST_ENTRY.ID_BAST_ENTRI', 'LEFT');
                $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = R_BAST_ENTRY.ID_PENERIMAAN', 'LEFT');
                $this->db->join('M_INST_SATKER', 'T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'LEFT');
                $this->db->join('M_UNIT_KERJA', 'T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA = M_UNIT_KERJA.UK_ID', 'LEFT');
                $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN', 'LEFT');
                $this->db->join('M_BIDANG', 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID', 'LEFT');
                $this->db->join('T_USER AS T_USER_KOORD_CS', 'T_USER_KOORD_CS.USERNAME = T_BAST_ENTRY.USER_KOORD_CS', 'LEFT');
                $this->db->join('T_USER AS T_USER_KOORD_ENTRI', 'T_USER_KOORD_ENTRI.USERNAME = T_BAST_ENTRY.USER_KOORD_ENTRI', 'LEFT');
                $this->db->join('M_JABATAN', 'T_LHKPNOFFLINE_PENERIMAAN.JABATAN = M_JABATAN.ID_JABATAN', 'LEFT');
                $this->db->where('1=1', null, false);
                $this->db->where("T_BAST_ENTRY.ID_BAST_ENTRI", $ID_BAST_ENTRI, NULL, FALSE);

                $query = $this->db->get('', $this->limit, $this->offset);
                // echo $this->db->last_query();
                $result = $this->items = $query->result();

                $this->db->flush_cache();

                $join = [
                    ['table' => 'T_PN', 'on' => 'USERNAME=NIK']
                ];
                $dataA = [
                    'form' => 'bast',
                    'data' => $result,
                ];

                //echo $this->db->last_query();

                $html = $this->load->view('lhkpnoffline/lhkpnoffline_bastentry_form', $dataA, true);
                $pdf = $this->pdf->load();
                //$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
                $pdf->SetFooter('|{PAGENO}|');
                $pdf->WriteHTML($html); // write the HTML into the PDF

                $time = time();
                $pdf->Output('uploads/pdf/' . $time . '.pdf', 'F'); // save to file because we can

                echo base_url('uploads/pdf/' . $time . '.pdf');
                die;
                break;
            case 'cekbast':
                $id = explode(',', $this->input->post('id'));
                $status = $this->input->post('status');
                $tanggal = date('Y-m-d');

                $this->db->trans_begin();

                if ($this->input->post('terima')) {
                    $data = [
                        'IS_DITERIMA_KOORD_ENTRY' => '1',
                        'USERNAME_KOORD_ENTRY' => $this->session->userdata('USR'),
                        'TGL_BAST_KOORD_ENTRY' => $tanggal
                    ];
                } else if ($this->input->post('tolak')) {
                    $data = [
                        'IS_DITERIMA_KOORD_ENTRY' => '0',
                        'USERNAME_KOORD_ENTRY' => NULL,
                        'TGL_BAST_KOORD_ENTRY' => NULL
                    ];
                }

                $result = $this->mglobal->update('T_LHKPNOFFLINE_PENERIMAAN', $data, NULL, "ID_PENERIMAAN IN (" . $this->input->post('id') . ")");

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
                die;
                break;
            default:
                # code...
                break;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    public function cetak($mode = '', $id = '') {
        $this->data = [];
    }

    function loadwskeluarga($ID_LHKPN_NEW) {
        $dt_ws = 'http://localhost/lhkpn/file/json/pn_keluarga.json';
        $json = file_get_contents($dt_ws);
        $json_kel = json_decode($json, true);

        if ($json_kel) {
            foreach ($json_kel as $kel_js) {
                $arr_keluarga = array(
                    'ID_LHKPN' => $ID_LHKPN_NEW,
                    'NAMA' => $kel_js['nama_lengkap'],
                    'NIK' => $kel_js['NIK'],
                    'HUBUNGAN' => $kel_js['hubungan'],
                    'TEMPAT_LAHIR' => $kel_js['tempat_lahir'],
                    'TANGGAL_LAHIR' => $kel_js['tanggal_lahir'],
                    'JENIS_KELAMIN' => $kel_js['jenis_kelamin'],
                    'PEKERJAAN' => $kel_js['pekerjaan'],
                    'ALAMAT_RUMAH' => $kel_js['alamat_rumah'],
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('NAMA'),
                    'CREATED_IP' => get_client_ip()
                );
                $this->db->insert('t_lhkpn_keluarga', $arr_keluarga);
            }
        }
    }

    public function cari_tracking() {
//        $this->data['act'] = 'doinsert';
//        $this->data['random_id'] = md5(uniqid(rand(), true));
//        $iniJoin = [
//            ['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPNOFFLINE_PENERIMAAN.ID_PN', 'join' => 'left'],
//            ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA', 'join' => 'left'],
//            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'join' => 'left'],
//            ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'join' => 'left']
//        ];
//
//        $data_js = $this->data;
//        $this->data['js_page'][] = $this->load->view('lhkpnoffline/penerimaan/js/js_lwsupload', $data_js, TRUE);
//        $this->data['js_page'][] = $this->load->view('lhkpnoffline/penerimaan/js/js_form_add', $data_js, TRUE);
//
//        $this->load->model('mpn', '', TRUE);
//        $this->load->model('mglobal', '', TRUE);
//        $this->load->model('minstansi', '', TRUE);
//
//        $data_js = array_merge($data_js, array(
//            'iscln' => 2,
//            'status' => 'daftarindividu',
//            'form' => 'add',
//            'agama' => $this->mglobal->get_data_all('M_AGAMA', null, ['IS_ACTIVE' => 1]),
//            'sttnikah' => $this->mglobal->get_data_all('M_STATUS_NIKAH'),
//            'penhir' => $this->mglobal->get_data_all('M_PENDIDIKAN', null, ['IS_ACTIVE' => 1]),
//            'uk_id' => $this->session->userdata('UK_ID'),
//            'is_uk' => $this->is_unit_kerja(),
//            'isInstansi' => $this->is_instansi()
//        ));

        $this->load->view('lhkpnoffline/lhkpnoffline_tracking_cari', $this->data);
    }

    public function hasiltrackingpn($offset = 0) {
        $this->offset = $offset;
        $this->base_url = site_url('efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->limit = 5;
        $this->uri_segment = 4;


        $this->load->model('Mmpnwn');
//        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        $this->Mmpnwn->set_additional_join_wl_aktif = TRUE;
        $this->Mmpnwn->ins = NULL;
        $this->Mmpnwn->is_lhkpn_offline = TRUE;
        $this->Mmpnwn->is_tracking_lhkpn = TRUE;

//        $page_mode = $this->input->get('page_mode');
//        $response = $this->Mmpnwn->load_page_PL_AKTIF($this->instansi, $this->offset, $this->CARI['TEXT'], $this->limit, '', 1);

        $response = $this->Mmpnwn->load_page_PL_AKTIF(NULL, $this->offset, $this->CARI, $this->limit, "", 1);

        $this->data['CARI'] = @$this->CARI;
        $this->data['total_rows'] = intval($response->total_rows);
        $this->data['offset'] = @$this->offset;
//        $this->data['items'] = @$this->items;
        $this->data['items'] = $response->result;
        $this->data['start'] = @$this->offset + 1;
        $this->data['end'] = @$this->offset + @$this->end;

        $this->total_rows = intval($response->total_rows);
        $this->limit = 5;

        $this->data['pagination'] = call_user_func('ng::genPagination');
        $this->Mmpnwn->is_tracking_lhkpn = FALSE;
        $this->load->view("lhkpnoffline/lhkpnoffline_hasiltrackingpn", $this->data);
    }

    public function tracking($action = NULL, $id = NULL) {
        switch ($action) {
            case 'printitem':
                $this->load->model('mglobal');
                $iniJoin = [
                    ['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPNOFFLINE_PENERIMAAN.ID_PN', 'join' => 'left'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA', 'join' => 'left'],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'join' => 'left'],
                    ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'join' => 'left'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID= T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'join' => 'left']
                ];

                $joinHist = [
                    ['table' => 'M_STATUS_LHKPN', 'on' => 'T_LHKPN_STATUS_HISTORY.ID_STATUS = M_STATUS_LHKPN.ID_STATUS'],
                    ['table' => 'T_USER A', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENGIRIM = A.USERNAME', 'join' => 'LEFT'],
                    ['table' => 'T_USER B', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENERIMA = B.USERNAME', 'join' => 'LEFT']
                ];

                $select = 'T_LHKPN_STATUS_HISTORY.DATE_INSERT, M_STATUS_LHKPN.ID_STATUS, M_STATUS_LHKPN.STATUS, A.NAMA as PENGIRIM, B.NAMA as PENERIMA';

                $this->data['item'] = $this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', $iniJoin, ['SUBSTRING(md5(ID_LHKPN), 6, 8) =' => $id])[0];
                $this->data['subitem'] = $this->mglobal->get_data_all('T_LHKPN_STATUS_HISTORY', $joinHist, ['SUBSTRING(md5(ID_LHKPN), 6, 8) =' => $id], $select, NULL, ['DATE_INSERT', 'asc']);
                $this->data['barcode'] = $this->data['item']->TAHUN_PELAPORAN . '/' . ($this->data['item']->JENIS_LAPORAN == '4' ? 'R' : ($this->data['item']->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $this->data['item']->NIK . '/' . $this->data['item']->ID_LHKPN;
                $html = $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_tracking_print', $this->data, true);

                $this->load->library('pdf');
                $pdf = $this->pdf->load();
                $pdf->SetFooter('|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
                $pdf->WriteHTML($html); // write the HTML into the PDF
                // $pdf->Output($pdfFilePath, 'F'); // save to file because we can
                $pdf->Output();
                break;
            case 'show':
                $iniJoin = [
                    ['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPNOFFLINE_PENERIMAAN.ID_PN', 'join' => 'left'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA', 'join' => 'left'],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'join' => 'left'],
                    ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'join' => 'left'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID= T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'join' => 'left'],
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN= T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'join' => 'left']
                ];

                $item = @$this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', $iniJoin, ['T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN =' => $id], 'T_LHKPNOFFLINE_PENERIMAAN.*, T_PN.*, M_INST_SATKER.*, M_JABATAN.*, M_BIDANG.*, M_UNIT_KERJA.*, T_LHKPN.ID_LHKPN, T_LHKPN.JENIS_LAPORAN, YEAR(T_LHKPN.TGL_LAPOR) AS TAHUN_PELAPORAN')[0];
                if (!empty($item)) {
                    $barcode = $item->TAHUN_PELAPORAN . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : ($item->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                } else {
                    $newitem = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN']], ['ID_LHKPN =' => $id, 'T_LHKPN.IS_ACTIVE' => '1'], 'TGL_LAPOR, JENIS_LAPORAN, NIK, ID_LHKPN, ENTRY_VIA, tgl_kirim_final')[0];
                    $barcode = date('Y', strtotime($newitem->TGL_LAPOR)) . '/' . ($newitem->JENIS_LAPORAN == '4' ? 'R' : ($newitem->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $newitem->NIK . '/' . $newitem->ID_LHKPN;
//                    $barcode = date('Y', strtotime($newitem->tgl_kirim_final)) . '/' . ($newitem->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $newitem->NIK . '/' . $newitem->ID_LHKPN;
                }

                $this->base_url = site_url('efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');

                $this->data = [];

                $this->db->start_cache();
                $this->db->select('*');
                $this->db->from('T_LHKPN');
                $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');

                if (isset($newitem)) {
                    $this->db->join('T_PN_JABATAN', 'T_PN.ID_PN=T_PN_JABATAN.ID_PN');
                    $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN.ID_LHKPN=T_LHKPN_JABATAN.ID_LHKPN');
                    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
                    $this->db->join('M_INST_SATKER', 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD', 'left');
                    $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                    $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID', 'left');
                    $this->db->join('M_SUB_UNIT_KERJA', 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID', 'left');
                    $this->db->join('T_LHKPN_DATA_PRIBADI', 'T_LHKPN_DATA_PRIBADI.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
                    $this->db->where('IS_CURRENT', 1);
                } else {
                    $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPN.ID_LHKPN = T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'left');
                    $this->db->join('M_INST_SATKER', 'T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'left');
                    $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'left');
                    $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'left');
                    $this->db->join('T_LHKPN_DATA_PRIBADI', 'T_LHKPN_DATA_PRIBADI.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
                }

                $xdata = $barcode;
                $exp = explode('/', $xdata);
                $this->db->where('T_LHKPN.ID_LHKPN', $exp[3]);
                $this->db->where('T_PN.NIK', $exp[2]);

                if (!empty($item)) {
                    $this->db->where('YEAR(T_LHKPN.TGL_LAPOR)', $exp[0]);
//                    $this->db->where('YEAR(T_LHKPN.tgl_kirim_final)', $exp[0]);
                    if ($exp[1] == 'R' || $exp[1] == 'r') {
                        $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN', '4');
                    } else {
                        $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN <>', '4');
                    }
                } else {
                    $this->db->where('T_LHKPN.TGL_LAPOR LIKE', $exp[0] . '%');
//                    $this->db->where('T_LHKPN.tgl_kirim_final LIKE', $exp[0] . '%');
                    if ($exp[1] == 'R' || $exp[1] == 'r') {
                        $this->db->where('T_LHKPN.JENIS_LAPORAN', '4');
                    } else {
                        $this->db->where('T_LHKPN.JENIS_LAPORAN <>', '4');
                    }
                }

                $this->total_rows = $this->db->get('')->num_rows();

                $query = $this->db->get();
                $this->items = $query->result();
                $this->end = $query->num_rows();
                // echo $this->db->last_query();
                $this->db->flush_cache();


                $this->data['items'] = $this->items;
                // $this->data['end'] = $this->end;
                $this->data['id'] = $barcode;

                $this->db->start_cache();
                $status = array('0','7');
                $this->db->where('ID_PN',@$this->items[0]->ID_PN);
                $this->db->where('IS_ACTIVE', 1);
                $this->db->where_not_in('STATUS', $status);
                $query = $this->db->get('t_lhkpn');
                $this->total = $query->num_rows();
                $this->db->flush_cache();
                $this->data['getTotalLapor'] = $this->total;

                $this->load->model('mglobal');
                $this->load->model('mlhkpnkeluarga');
                $this->data['path1'] = encrypt_username($this->data['items'][0]->NIK, 'e');
                $this->db->flush_cache();
                $joinHist = [
                    ['table' => 'M_STATUS_LHKPN', 'on' => 'T_LHKPN_STATUS_HISTORY.ID_STATUS = M_STATUS_LHKPN.ID_STATUS'],
                    ['table' => 'T_USER A', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENGIRIM = A.USERNAME', 'join' => 'LEFT'],
                    ['table' => 'T_USER B', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENERIMA = B.USERNAME', 'join' => 'LEFT']
                ];
                $select = 'T_LHKPN_STATUS_HISTORY.DATE_INSERT, M_STATUS_LHKPN.ID_STATUS, M_STATUS_LHKPN.STATUS, A.NAMA as PENGIRIM, B.NAMA as PENERIMA, ALASAN_BTD, T_LHKPN_STATUS_HISTORY.USERNAME_PENGIRIM, T_LHKPN_STATUS_HISTORY.USERNAME_PENERIMA';
                $this->data['getHistory'] = $this->mglobal->get_data_all('T_LHKPN_STATUS_HISTORY', $joinHist, ['ID_LHKPN' => @$this->items[0]->ID_LHKPN], $select, NULL, ['DATE_INSERT', 'asc']);
                $this->data['getVerification'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => @$this->items[0]->ID_LHKPN])[0]->HASIL_VERIFIKASI);
                $this->data['getStatusVerification'] = $this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => @$this->items[0]->ID_LHKPN])[0]->STATUS_VERIFIKASI;
                $joinKeluarga = [
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN_KELUARGA.ID_LHKPN = T_LHKPN.ID_LHKPN', 'JOIN' => 'LEFT']
                ];
                $joinJabatan = [
                    ['table' => 'M_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN', 'JOIN' => 'LEFT'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD', 'JOIN' => 'LEFT'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_JABATAN.UK_ID = M_UNIT_KERJA.UK_ID', 'JOIN' => 'LEFT'],
                    ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_JABATAN.SUK_ID = M_SUB_UNIT_KERJA.SUK_ID', 'JOIN' => 'LEFT']
                ];
                $selectKeluarga = 'T_LHKPN_KELUARGA.ID_KELUARGA, T_LHKPN_KELUARGA.ID_LHKPN, T_LHKPN_KELUARGA.NAMA, T_LHKPN_KELUARGA.HUBUNGAN, T_LHKPN_KELUARGA.NIK, FLOOR(DATEDIFF(T_LHKPN.TGL_LAPOR,(T_LHKPN_KELUARGA.TANGGAL_LAHIR))/365)-1 AS UMUR, TIMESTAMPDIFF(YEAR,t_lhkpn_keluarga.TANGGAL_LAHIR,t_lhkpn.tgl_lapor) AS UMUR2, T_LHKPN.STATUS, T_LHKPN.ENTRY_VIA, FLAG_SK';
                $selectJabatan = 'M_JABATAN.NAMA_JABATAN AS JABATAN, M_INST_SATKER.INST_NAMA AS INSTANSI, M_UNIT_KERJA.UK_NAMA AS UK, M_SUB_UNIT_KERJA.SUK_NAMA AS SUK';
                $this->data['getKeluarga'] = $this->mglobal->get_data_all('T_LHKPN_KELUARGA', $joinKeluarga, ['T_LHKPN_KELUARGA.ID_LHKPN' => $exp[3], 'T_LHKPN_KELUARGA.IS_ACTIVE' => '1'], $selectKeluarga, NULL, ['T_LHKPN_KELUARGA.ID_KELUARGA','ASC']);
                $this->data['getJabatan'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, ['T_LHKPN_JABATAN.ID_LHKPN' => $exp[3], 'T_LHKPN_JABATAN.IS_PRIMARY' => '0'], $selectJabatan);

                /////////////////////////////////// DATA FROM ELO ///////////////////////////////////
                $nik = $this->data['items'][0]->NIK;
                $this->load->helper('elo_helper');
                $key = $this->config->item('KPKkey');
                $LoginAs = '';
                $ELOuser = $this->config->item('ELOUser');
                $ELOpass = $this->config->item('ELOPass');
                $IXURL = $this->config->item('IXURL');
                $ELOws = $this->config->item('ELOws');
                $result_elo = FindELODoc('Basic Entry', $nik, "", "", "","", $LoginAs,$ELOuser,$ELOpass,$IXURL,$ELOws);
                $put_elo = [];
                foreach($result_elo as $r){
                    $r['URL'] = GetDocURL($r['ID'],$LoginAs,$ELOuser,$ELOpass,$IXURL,$ELOws);
                    array_push($put_elo,$r);
                }
                $this->data['data_sk_elo'] = json_encode($put_elo);
                /////////////////////////////////// DATA FROM ELO ///////////////////////////////////
                
                foreach($this->data['getKeluarga'] as $key => $item) {
                    if($item->NIK) {
                        $cek_pn = $this->db->query("SELECT COUNT(id_pn) AS numrows, id_pn FROM t_pn WHERE nik = '".$item->NIK."'");

                        if($cek_pn->result()[0]->numrows > 0){
                            $this->data['getKeluarga'][$key]->ID_PN = TRUE;

                            $cek_wl = $this->db->query("SELECT IS_WL, ID_PN, ID, DESKRIPSI_JABATAN, NAMA_LEMBAGA FROM T_PN_JABATAN WHERE ID_PN = '".$cek_pn->result()[0]->id_pn."' AND IS_ACTIVE = 1 AND IS_CURRENT = 1 AND IS_DELETED = 0 AND ID_STATUS_AKHIR_JABAT <> 1 ORDER BY TAHUN_WL DESC LIMIT 1");
                            $jabatan_wl = $this->db->query("SELECT * FROM T_PN_JABATAN WHERE ID_PN = '".$cek_pn->result()[0]->id_pn."' AND IS_ACTIVE = 1 AND IS_CURRENT = 1 AND IS_DELETED = 0 AND ID_STATUS_AKHIR_JABAT <> 1 ORDER BY TAHUN_WL DESC LIMIT 1");
                            $this->data['getKeluarga'][$key]->IS_WL = $cek_wl->result()[0]->IS_WL;

                            $get_pn = $this->db->query("SELECT * FROM t_pn WHERE nik = '".$item->NIK."'");
                            $this->data['getKeluarga'][$key]->DESKRIPSI_JABATAN = $cek_wl->result()[0]->DESKRIPSI_JABATAN;
                            $this->data['getKeluarga'][$key]->NAMA_LEMBAGA = $cek_wl->result()[0]->NAMA_LEMBAGA;
                        } else {
                            $this->data['getKeluarga'][$key]->IS_WL = FALSE;
                            $this->data['getKeluarga'][$key]->ID_PN = FALSE;
                        }
                    } else {
                         $this->data['getKeluarga'][$key]->IS_WL = FALSE;
                         $this->data['getKeluarga'][$key]->ID_PN = FALSE;
                    }
                }
                $this->data['lhkpn_ver'] = $this->mlhkpnkeluarga->get_lhkpn_version(@$this->items[0]->ID_LHKPN);
                // $id_pn = $this->data['items'][0]->ID_PN;
                // $this->data['log_file_bukti_sk'] = $this->mglobal->get_data_all('T_LHKPN', NULL, NULL, '*', "ID_PN = '$id_pn' and IS_ACTIVE =1");

                // foreach($this->data['log_file_bukti_sk'] as $log_file) {
                //     $check_log_sk = trim($log_file->FILE_BUKTI_SK);
                //     if($check_log_sk!='' && $check_log_sk!=null && $check_log_sk!='[""]' && $check_log_sk!="['']" && $check_log_sk!="null"){       
                //         $file_sk_log[] = $log_file->FILE_BUKTI_SK;
                //         $id_lhkpn_sk_log[] = encrypt_username($log_file->ID_LHKPN, 'e');
                //         $this->db->flush_cache();
                //     }

                //     $check_log_skm = trim($log_file->FILE_BUKTI_SKM);
                //     if($check_log_skm!='' && $check_log_skm!=null && $check_log_skm!='[""]' && $check_log_skm!="['']" && $check_log_skm!="null"){       
                //         $file_skm_log[] = $log_file->FILE_BUKTI_SKM;
                //         $id_lhkpn_skm_log[] = encrypt_username($log_file->ID_LHKPN, 'e');
                //         $this->db->flush_cache();
                //     }
                // }

                // $this->data['dataSKM'] = array_combine($id_lhkpn_skm_log, $file_skm_log);
                // $this->data['dataSK'] = array_combine($id_lhkpn_sk_log, $file_sk_log);
                $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_tracking_show', $this->data);
                break;
            default:
                $this->base_url = site_url('efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');

                $this->data = [];
                $this->data['title'] = 'Tracking';
                $this->data['icon'] = 'fa-list';
                $this->data['thisPageUrl'] = $this->base_url;


                if (empty($this->CARI['KODE'])) {
                    $this->total_rows = 0;
                } else {
                    $xdata = $this->CARI['KODE'];
                    $exp = explode('/', $xdata);

                    $tmp = @$this->mglobal->get_data_all('T_LHKPN', NULL, ['ID_LHKPN' => $exp[3]], 'ENTRY_VIA')[0];
                    if (!empty($tmp)) {
                        $this->db->start_cache();
                        $this->db->select('*');
                        $this->db->from('T_LHKPN');
                        $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');

                        if ($tmp->ENTRY_VIA == '0') {
                            $this->db->join('T_PN_JABATAN', 'T_PN.ID_PN=T_PN_JABATAN.ID_PN');
                            $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN', 'left');
                            $this->db->join('M_INST_SATKER', 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD', 'left');
                            $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                            $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID', 'left');
                            $this->db->where('IS_CURRENT', 1);
                        } else {
                            $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPN.ID_LHKPN = T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'left');
                            $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'left');
                            $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID', 'left');
                            $this->db->join('M_INST_SATKER', 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD', 'left');
                            $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                        }

                        $this->db->where('T_LHKPN.ID_LHKPN', @$exp[3]);
                        $this->db->where('YEAR(T_LHKPN.tgl_lapor)', @$exp[0]);
                        $this->db->where('T_PN.NIK', @$exp[2]);
                        if (@$exp[1] == 'R' || @$exp[1] == 'r') {
                            $this->db->where('T_LHKPN.JENIS_LAPORAN', '4');
                        } else {
                            $this->db->where('T_LHKPN.JENIS_LAPORAN <>', '4');
                        }
                        $this->total_rows = $this->db->get('')->num_rows();

                        $query = $this->db->get();
                        $this->items = $query->result();
                        $this->end = $query->num_rows();
                        $this->db->flush_cache();

                        $this->load->model('mglobal');
                        $joinHist = [
                            ['table' => 'M_STATUS_LHKPN', 'on' => 'T_LHKPN_STATUS_HISTORY.ID_STATUS = M_STATUS_LHKPN.ID_STATUS'],
                            ['table' => 'T_USER A', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENGIRIM = A.USERNAME', 'join' => 'LEFT'],
                            ['table' => 'T_USER B', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENERIMA = B.USERNAME', 'join' => 'LEFT']
                        ];
                        $select = 'T_LHKPN_STATUS_HISTORY.DATE_INSERT, M_STATUS_LHKPN.ID_STATUS, M_STATUS_LHKPN.STATUS, A.NAMA as PENGIRIM, B.NAMA as PENERIMA';
                        $this->data['getHistory'] = $this->mglobal->get_data_all('T_LHKPN_STATUS_HISTORY', $joinHist, ['ID_LHKPN' => @$this->items[0]->ID_LHKPN], $select, NULL, ['DATE_INSERT', 'asc']);
//                        $this->data['getVerification'] = $this->mglobal->get_data_all('T_VERIFICATION', NULL, ['ID_LHKPN' => @$this->items[0]->ID_LHKPN], '*', NULL);
                        $this->data['getVerification'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => @$this->items[0]->ID_LHKPN])[0]->HASIL_VERIFIKASI);
                    }
                }

                $this->data['breadcrumb'] = call_user_func('ng::genBreadcrumb', [
                    'Dashboard' => 'index.php/welcome/dashboard',
                    'E filling' => 'index.php/dashboard/efilling/',
                    'Tracking' => $this->base_url,
                        ]
                );

                $this->data['thisPageUrl'] = $this->base_url;
                $this->data['CARI'] = @$this->CARI;
                $this->data['total_rows'] = @$this->total_rows;
                $this->data['offset'] = @$this->offset;
                $this->data['items'] = @$this->items;
                $this->data['start'] = @$this->offset + 1;
                $this->data['end'] = @$this->offset + @$this->end;
                $this->data['pagination'] = call_user_func('ng::genPagination');

                $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_tracking_index', $this->data);
                break;
        }
    }

    public function hasilcaripn_old($offset = 0) {
        $this->offset = $offset;
        $this->base_url = site_url('efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->limit = 5;
        $this->uri_segment = 4;


        $this->db->start_cache();

        $this->db->select('
            P.ID_PN,
            P.NIK,
            P.NAMA,
            U.ID_USER,
            V1.ID_PN AS ID_PN_DIJABATAN,
            group_concat(
                CONCAT( CONVERT( REPEAT( "0",( 5 - LENGTH( V1.LEMBAGA ) ) ) USING latin1 ), V1.LEMBAGA ) SEPARATOR ","
            ) AS LEMBAGA,
            group_concat(
                CONCAT( REPEAT( "0",( 5 - LENGTH( V1.ID_JABATAN ) ) ), V1.ID_JABATAN ) SEPARATOR ","
            ) AS JABATAN,
            group_concat(
                CONCAT( REPEAT( "0",( 5 - LENGTH( V1.IS_CALON ) ) ), V1.IS_CALON ) SEPARATOR ","
            ) AS IS_CALON,
            group_concat(
                CONCAT( ifnull( V1.ID, "" ), ":||:", ifnull( V1.ID_STATUS_AKHIR_JABAT, "" ), ":||:", CONVERT( ifnull( V1.STATUS, "" ) USING utf8 ), ":||:", ifnull( V1.ID_PN_JABATAN, "NULL" ), ":||:", CONVERT( ifnull( V1.MIC_NAMA, "" ) USING utf8 ), ":||:", ifnull( V1.MJC_NAMA, "" ), " - ", CONVERT( ifnull( V1.DESKRIPSI_JABATAN, "" ) USING utf8 ), " - ", "<span style=\"font-weight : bold\">", CONVERT( ifnull( V1.MUC_NAMA, "" ) USING utf8 ), " - ", ifnull( V1.MIC_AKRONIM, V1.MIC_NAMA ), "</span>", ":||:", ifnull( V1.TMT, "" ), ":||:", CONVERT( ifnull( V1.SD, "" ) USING utf8 ), ":||:", ifnull( V1.IS_CALON, "" ), ":||:", ifnull( V1.MIT_NAMA, "" ) ) SEPARATOR ":|||:"
            ) AS NAMA_JABATAN,
            V1.*,
            V2.JML_AKTIF,
            V3.JML_NON_AKTIF', false); // parameter false wajib
        $this->db->from('T_PN P');
        $this->db->join('T_USER U', 'U.USERNAME = P.NIK');
        $this->db->join('
        	(
        		SELECT * FROM V_JABATAN_CURRENT_EXC_CALON
        		UNION
        		SELECT * FROM V_JABATAN_CURRENT_INC_CALON
        	)
         V1', 'V1.ID_PN = P.ID_PN');
        $this->db->join('
        	(
				SELECT ID_PN, JML_AKTIF FROM V_PN_JML_JABATAN_AKTIF_EXC_CALON
				UNION
				SELECT ID_PN, JML_AKTIF FROM V_PN_JML_JABATAN_IS_CALON
        	)
         V2', 'V2.ID_PN = V1.ID_PN', 'left');
        $this->db->join('V_PN_JML_JABATAN_NONAKTIF_EXC_CALON V3', 'V3.ID_PN = V1.ID_PN', 'left');

        $this->db->where(' V2.JML_AKTIF IS NOT NULL', null, false); // parameter false wajib

        if (@$this->CARI['TEXT']) {
            $this->db->where('(P.NIK like "%' . $this->CARI['TEXT'] . '%"
				OR P.NAMA like "%' . $this->CARI['TEXT'] . '%"
				OR P.EMAIL like "%' . $this->CARI['TEXT'] . '%"
				OR P.NO_HP like "%' . $this->CARI['TEXT'] . '%")', null, false);
        }

        $this->db->order_by('P.NAMA', 'asc');
        $this->db->group_by('P.ID_PN'); // parameter false wajib

        $this->total_rows = $this->db->get('')->num_rows();
        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();
        $this->end = $query->num_rows();
        // echo $this->db->last_query();
        $this->db->flush_cache();

        $this->data['CARI'] = @$this->CARI;
        $this->data['total_rows'] = @$this->total_rows;
        $this->data['offset'] = @$this->offset;
        $this->data['items'] = @$this->items;
        $this->data['start'] = @$this->offset + 1;
        $this->data['end'] = @$this->offset + @$this->end;
        $this->data['pagination'] = call_user_func('ng::genPagination');
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $this->data);
    }

    public function hasilcaripn($offset = 0) {
        $this->offset = $offset;
        $this->base_url = site_url('efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->limit = 5;
        $this->uri_segment = 4;


        $this->load->model('Mmpnwn');
//        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        $this->Mmpnwn->set_additional_join_wl_aktif = TRUE;
        $this->Mmpnwn->ins = NULL;
        $this->Mmpnwn->is_lhkpn_offline = TRUE;
        $this->Mmpnwn->is_tracking_lhkpn = TRUE;

//        $page_mode = $this->input->get('page_mode');
//        $response = $this->Mmpnwn->load_page_PL_AKTIF($this->instansi, $this->offset, $this->CARI['TEXT'], $this->limit, '', 1);
//        $response = $this->Mmpnwn->load_page_PL_AKTIF(NULL, $this->offset, $this->CARI['TEXT'], $this->limit, "", 1);
        $response = $this->Mmpnwn->load_page_PL_AKTIF(NULL, $this->offset, $this->CARI, $this->limit, "", 1, TRUE);

        $this->data['CARI'] = @$this->CARI;
        $this->data['total_rows'] = intval($response->total_rows);
        $this->data['offset'] = @$this->offset;
//        $this->data['items'] = @$this->items;
        $this->data['items'] = $response->result;
        $this->data['start'] = @$this->offset + 1;
        $this->data['end'] = @$this->offset + @$this->end;

        $this->total_rows = intval($response->total_rows);
        $this->limit = 5;

        $this->data['pagination'] = call_user_func('ng::genPagination');
        $this->Mmpnwn->is_tracking_lhkpn = FALSE;
        $this->load->view("lhkpnoffline/lhkpnoffline_hasilcaripn", $this->data);
    }

    public function infopn() {
        $NIK = $this->input->post('NIK', TRUE);
        if (!empty($NIK)) {
            $select = 'T_PN.ID_PN,
            	T_PN.NIK,
            	T_PN.NAMA,
            	T_PN.TEMPAT_LAHIR,
            	T_PN.TGL_LAHIR,
            	T_PN.EMAIL,
            	T_PN.NO_HP';
            $PN = @$this->mglobal->get_data_all_array('T_PN', NULL, ['NIK' => $NIK], $select)[0];
            // echo $this->db->last_query();
            if ($PN != '') {
                echo json_encode(array('error' => '0', 'msg' => '', 'data' => (object) $PN));
                return;
            }
        }
        echo json_encode(array('error' => '1', 'msg' => 'Silahkan Pilih PN'));
    }

    public function hasilcaripenerimaan($offset = 0) {
        $this->offset = $offset;
        $this->base_url = site_url('efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->limit = 5;
        $this->uri_segment = 4;


        $this->db->start_cache();

        $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*, T_PN.NIK, T_PN.NAMA, T_LHKPNOFFLINE_PENUGASAN_ENTRY.STAT AS STAT_PENUGASAN');
        $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
        $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
        $this->db->join('T_LHKPNOFFLINE_PENUGASAN_ENTRY', 'T_LHKPNOFFLINE_PENUGASAN_ENTRY.ID_PENERIMAAN = T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN', 'left');
        $this->db->where('1=1', null, false);

        if (@$this->CARI['TEXT']) {
            $this->db->like('T_LHKPNOFFLINE_PENERIMAAN.USERNAME', $this->CARI['TEXT']);
            $this->db->or_like('T_PN.NAMA', $this->CARI['TEXT']);
            $this->db->or_like('T_PN.NIK', $this->CARI['TEXT']);
        }

        if (@$this->CARI['IS_ACTIVE'] == 99) {
            // all
        } else if (@$this->CARI['IS_ACTIVE'] == -1) {
            // deleted
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_ACTIVE', -1);
        } else if (@$this->CARI['IS_ACTIVE']) {
            // by status
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_ACTIVE', $this->CARI['IS_ACTIVE']);
        } else {
            // active
            $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_ACTIVE', 1);
        }

        $this->total_rows = $this->db->get('')->num_rows();
        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();
        $this->end = $query->num_rows();
        // echo $this->db->last_query();
        $this->db->flush_cache();

        $this->data['CARI'] = @$this->CARI;
        $this->data['total_rows'] = @$this->total_rows;
        $this->data['offset'] = @$this->offset;
        $this->data['items'] = @$this->items;
        $this->data['start'] = @$this->offset + 1;
        $this->data['end'] = @$this->offset + @$this->end;
        $this->data['pagination'] = call_user_func('ng::genPagination');
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $this->data);
    }

    public function hasilcarilhkpn($offset = 0) {
        $this->offset = $offset;
        $this->base_url = site_url('efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->limit = 5;
        $this->uri_segment = 4;


        $this->db->start_cache();

        // $this->db->select('*');
        // $this->db->from('T_LHKPN');
        // $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
        // $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = T_PN.LEMBAGA');
        $this->db->select('*');
        $this->db->from('T_LHKPN');
        // $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
        $this->db->join('(
	                select  ID_LHKPN AS ID_LHKPN_DIJABATAN,
	                    group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN,
	                    group_concat(
	                    CONCAT(
	                        IFNULL(T_LHKPN_JABATAN.ID,""),":||:",
	                        IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":||:",
	                        IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":||:",
	                        IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":||:",
	                        IFNULL(M_JABATAN.NAMA_JABATAN,""),
	                        "(", IFNULL(M_ESELON.ESELON,""), ") - ",
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

        $this->total_rows = $this->db->get('')->num_rows();
        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();
        $this->end = $query->num_rows();
        // echo $this->db->last_query();
        $this->db->flush_cache();

        $this->data['CARI'] = @$this->CARI;
        $this->data['total_rows'] = @$this->total_rows;
        $this->data['offset'] = @$this->offset;
        $this->data['items'] = @$this->items;
        $this->data['start'] = @$this->offset + 1;
        $this->data['end'] = @$this->offset + @$this->end;
        $this->data['pagination'] = call_user_func('ng::genPagination');
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $this->data);
    }

    public function GetPNInfo() {
        $NIK = $this->input->post('NIK', true);
        $NAMA = $this->input->post('NAMA', true);
        $PN_ID = $this->input->post('NPDI');

        $array_condition = ['NIK' => $NIK, 'NAMA' => $NAMA, 'T_PN_JABATAN.IS_ACTIVE' => '1'];

        if ($PN_ID) {
            $array_condition = ['ID_PN' => $PN_ID, 'T_PN_JABATAN.IS_ACTIVE' => '1'];
        }

        if (!empty($NIK)) {
            $select = 'T_PN.ID_PN,
            	T_PN.NIK,
            	T_PN.NAMA,
            	T_PN.TEMPAT_LAHIR,
            	T_PN.TGL_LAHIR,
            	T_PN.EMAIL,
            	T_PN.NO_HP,
            	T_PN_JABATAN.ID_JABATAN,
            	T_PN_JABATAN.LEMBAGA,
            	T_PN_JABATAN.UNIT_KERJA,
            	T_PN_JABATAN.DESKRIPSI_JABATAN';
            $join = [['table' => 'T_PN_JABATAN', 'on' => 'T_PN_JABATAN.ID_PN = T_PN.ID_PN']];
            $PN = @$this->mglobal->get_data_all_array('T_PN', $join, $array_condition, $select)[0];
            if ($PN != '') {
                echo json_encode(array('error' => '0', 'msg' => '', 'data' => (object) $PN));
                return;
            }
        }
        echo json_encode(array('error' => '1', 'msg' => 'Silahkan Pilih PN'));
    }

    private function insertHistoryLHKPN($idLhkpn, $status, $pengirim = NULL, $penerima = NULL) {
        $cek = $this->mglobal->count_data_all('T_LHKPN_STATUS_HISTORY', NULL, ['ID_LHKPN' => $idLhkpn, 'ID_STATUS' => $status]);
        if ($cek == '0') {
            $history = [
                'ID_LHKPN' => $idLhkpn,
                'ID_STATUS' => $status,
                'USERNAME_PENGIRIM' => $pengirim,
                'USERNAME_PENERIMA' => $penerima,
                'DATE_INSERT' => date('Y-m-d H:i:s'),
                'CREATED_IP' => $this->input->ip_address()
            ];

            $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
        }
    }

    public function cetakbast($ID_BAST_CS) {

        $this->db->start_cache();
        $this->db->select('
				R_BAST_CS.ID,
				R_BAST_CS.ID_BAST_CS,
				R_BAST_CS.ID_PENERIMAAN,
				T_LHKPNOFFLINE_PENERIMAAN.ID_PN,
				T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN,
				T_BAST_CS.COUNTER,
				T_BAST_CS.NOMOR_BAST,
				T_BAST_CS.TGL_PENYERAHAN,
				T_BAST_CS.USER_CS,
				T_BAST_CS.USER_KOORD_CS,
				T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN,
				T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN,
				T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN,
				T_LHKPNOFFLINE_PENERIMAAN.DESKRIPSI_JABATAN,
				T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA,
				T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA,
				T_LHKPNOFFLINE_PENERIMAAN.JENIS_DOKUMEN,
				T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN,
				M_INST_SATKER.INST_NAMA,
				M_UNIT_KERJA.UK_NAMA,
				T_PN.NAMA,
				T_PN.NIK,
				M_BIDANG.BDG_NAMA,
				T_USER_CS.NAMA AS NAMA_CS,
				T_USER_KOORD_CS.NAMA AS NAMA_KOORD_CS,
				M_JABATAN.NAMA_JABATAN
	    	');
        $this->db->from('T_BAST_CS');
        $this->db->join('R_BAST_CS', 'R_BAST_CS.ID_BAST_CS = T_BAST_CS.ID_BAST_CS');
        $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = R_BAST_CS.ID_PENERIMAAN');
        $this->db->join('M_INST_SATKER', 'T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD');
        $this->db->join('M_UNIT_KERJA', 'T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA = M_UNIT_KERJA.UK_ID');
        $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
        $this->db->join('M_BIDANG', 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID');
        $this->db->join('T_USER AS T_USER_CS', 'T_USER_CS.USERNAME = T_BAST_CS.USER_CS');
        $this->db->join('T_USER AS T_USER_KOORD_CS', 'T_USER_KOORD_CS.USERNAME = T_BAST_CS.USER_KOORD_CS');
        $this->db->join('M_JABATAN', 'T_LHKPNOFFLINE_PENERIMAAN.JABATAN = M_JABATAN.ID_JABATAN');
        $this->db->where('1=1', null, false);
        $this->db->where("T_BAST_CS.ID_BAST_CS", $ID_BAST_CS, NULL, FALSE);

        $query = $this->db->get('', $this->limit, $this->offset);
        $result = $this->items = $query->result();

        $this->db->flush_cache();

        $join = [
            ['table' => 'T_PN', 'on' => 'USERNAME=NIK']
        ];
        $dataA = [
            'form' => 'bast',
            'data' => $result,
        ];

        $html = $this->load->view('lhkpnoffline/lhkpnoffline_bast_form', $dataA, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        //$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->SetFooter('|{PAGENO}|'); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->WriteHTML($html); // write the HTML into the PDF
        //$time = time();
        $pdf->Output(); // save to file because we can
        // echo base_url('uploads/pdf/'.$time.'.pdf');
        // die;
    }

    public function cetakbast2($ID_BAST_ENTRI) {

        $this->db->start_cache();
        $this->db->select('
				R_BAST_ENTRY.ID,
				R_BAST_ENTRY.ID_BAST_ENTRI,
				R_BAST_ENTRY.ID_PENERIMAAN,
				T_LHKPNOFFLINE_PENERIMAAN.ID_PN,
				T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN,
				T_BAST_ENTRY.COUNTER,
				T_BAST_ENTRY.NOMOR_BAST,
				T_BAST_ENTRY.TGL_PENYERAHAN,
				T_BAST_ENTRY.USER_KOORD_CS,
				T_BAST_ENTRY.USER_KOORD_ENTRI,
				T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN,
				T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN,
				T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN,
				T_LHKPNOFFLINE_PENERIMAAN.DESKRIPSI_JABATAN,
				T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA,
				T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA,
				T_LHKPNOFFLINE_PENERIMAAN.JENIS_DOKUMEN,
				T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PENERIMAAN,
				M_INST_SATKER.INST_NAMA,
				M_UNIT_KERJA.UK_NAMA,
				T_PN.NAMA,
				T_PN.NIK,
				M_BIDANG.BDG_NAMA,
				T_USER_KOORD_CS.NAMA AS NAMA_KOORD_CS,
				T_USER_KOORD_ENTRI.NAMA AS NAMA_KOORD_ENTRI,
				M_JABATAN.NAMA_JABATAN
	    	');
        $this->db->from('T_BAST_ENTRY');
        $this->db->join('R_BAST_ENTRY', 'R_BAST_ENTRY.ID_BAST_ENTRI = T_BAST_ENTRY.ID_BAST_ENTRI');
        $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = R_BAST_ENTRY.ID_PENERIMAAN');
        $this->db->join('M_INST_SATKER', 'T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD');
        $this->db->join('M_UNIT_KERJA', 'T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA = M_UNIT_KERJA.UK_ID');
        $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
        $this->db->join('M_BIDANG', 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID');
        $this->db->join('T_USER AS T_USER_KOORD_CS', 'T_USER_KOORD_CS.USERNAME = T_BAST_ENTRY.USER_KOORD_CS');
        $this->db->join('T_USER AS T_USER_KOORD_ENTRI', 'T_USER_KOORD_ENTRI.USERNAME = T_BAST_ENTRY.USER_KOORD_ENTRI');
        $this->db->join('M_JABATAN', 'T_LHKPNOFFLINE_PENERIMAAN.JABATAN = M_JABATAN.ID_JABATAN');
        $this->db->where('1=1', null, false);
        $this->db->where("T_BAST_ENTRY.ID_BAST_ENTRI", $ID_BAST_ENTRI, NULL, FALSE);

        $query = $this->db->get('', $this->limit, $this->offset);
        // echo $this->db->last_query();
        $result = $this->items = $query->result();

        $this->db->flush_cache();

        $join = [
            ['table' => 'T_PN', 'on' => 'USERNAME=NIK']
        ];
        $dataA = [
            'form' => 'bast',
            'data' => $result,
        ];

        $html = $this->load->view('lhkpnoffline/lhkpnoffline_bastentry_form', $dataA, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        //$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->SetFooter('|{PAGENO}|'); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->WriteHTML($html); // write the HTML into the PDF
        //$time = time();
        $pdf->Output(); // save to file because we can
        // echo base_url('uploads/pdf/'.$time.'.pdf');
        // die;
    }

    /**
     * @author Wahyu Widodo Siringo Ringo
     * @param array $arr_param array([NAMA], [INST_NAMA], [URAIAN]) default FALSE
     * @return View
     */
    function screening_email_preview($arr_param = FALSE) {
        $data['PAR'] = FALSE;
        if ($arr_param) {
            $data['NAMA'] = $arr_param[0];
            $data['INST_NAMA'] = $arr_param[1];
            $data['URAIAN_SCREENING'] = $arr_param[2];
        } else {
            $data['PAR'] = TRUE;
            $data['NAMA'] = $this->input->post('nama');
            $data['INST_NAMA'] = $this->input->post('inst_nama');
            $data['URAIAN_SCREENING'] = $this->input->post('body');
        }

        $view_model = $this->load->view('lhkpnoffline/lhkpnoffline_email_screening_preview', $data, TRUE);
        if ($arr_param) {
            return $view_model;
        }
        echo $view_model;
        exit;
    }

    function test() {
        echo get_base_url_reff_lembaga();
        exit;
    }





    ///////////////////////////////////////////////////////// SAVE EDIT FROM TRACKING LHKPN //////////////////////////////
    function save_tracking_lhkpn() {
        if ($_POST) {

            $ID_PN = $this->input->post('id_pn');
            $jenis_laporan = $this->input->post('jenis_laporan');
//             $tahun_pelaporan = $this->input->post('tahun_pelaporan');
            $tahun_pelaporan = date('Y')-1;
            $status = $this->input->post('status');
            $tgl_pelaporan = $this->input->post('tgl_pelaporan');
            $is_update = $this->input->post('is_update');
            $id_lhkpn = $this->input->post('id_lhkpn');
            $year = NULL;
            if ($tahun_pelaporan) {
                $year = $tahun_pelaporan;
                $start = $year . '-01-01';
                $end = $year . '-12-31';
            } else {
                $year = date('Y', strtotime(str_replace('/', '-', $tgl_pelaporan)));
                $start = $year . '-01-01';
                $end = $year . '-12-31';
            }

            $this->load->model('mlhkpn');
            $check = $this->mlhkpn->get_lhkpn_pn_by_id_pn($ID_PN, 4, array(
                "TGL_LAPOR >=" => $start,
                "TGL_LAPOR <=" => $end,
            ));

            if ($jenis_laporan == 4 && $check) {
                $result = array(
                    'status' => 0,
                    'msg' => 'Mohon Maaf , Data LHKPN Tahun <b>' . $tahun_pelaporan . '</b> sudah ada !! '
                );
            } else {
                $jenis = $this->input->post('jenis_laporan');
                $JENIS_LAPOR = NULL;
                $TGL_LAPOR = NULL;
                if ($jenis == '4') { // PERIODIK
                    $JENIS_LAPOR = 4;
                    $TGL_LAPOR = $year . '-12-31';

                    $sql_check_2 = "SELECT * FROM t_lhkpn WHERE ID_PN = ".$ID_PN." AND tgl_lapor = '".$TGL_LAPOR."' AND ID_LHKPN != ".$id_lhkpn." AND IS_ACTIVE = 1";
                    $act_check_2 = $this->db->query($sql_check_2)->result();

                    if ($act_check_2) {
                        $result = array(
                        'status' => 0,
                        'msg' => 'Mohon Maaf , Tanggal Pelaporan Harus Lebih Besar dari Tanggal Pelaporan Terakhir !! '
                        );
                        echo json_encode($result);
                        return;
                    }
                } else {
                    if ($this->input->post('status')) {
                        $st = $this->input->post('status');
                        if ($st == '1') {
                            $JENIS_LAPOR = 1;
                        } else if ($st == '2') {
                            $JENIS_LAPOR = 2;
                        } else {
                            $JENIS_LAPOR = 3;
                        }
                        $tgl_pelaporan = $this->input->post('tgl_pelaporan');


                        $TGL_LAPOR = date('Y-m-d', strtotime(str_replace('/', '-', $tgl_pelaporan)));


                        $sql_check_2 = "SELECT * FROM t_lhkpn WHERE ID_PN = ".$ID_PN." AND tgl_lapor >= '".$TGL_LAPOR."' AND ID_LHKPN != ".$id_lhkpn." AND IS_ACTIVE = 1";
                        $act_check_2 = $this->db->query($sql_check_2)->result();

                        if ($act_check_2) {
                            $result = array(
                            'status' => 0,
                            'msg' => 'Mohon Maaf , Tanggal Pelaporan Harus Lebih Besar dari Tanggal Pelaporan Terakhir !! '
                            );
                            echo json_encode($result);
                            return;
                        }

                    }
                }

                        if($jenis == 4){
                            $TAHUN_LAPOR_POST = $tahun_pelaporan;
                            $TANGGAL_LAPOR_POST = $tahun_pelaporan . '-12-31';
                        }else{
                            $TAHUN_LAPOR_POST = date('Y', strtotime(str_replace('/', '-', $tgl_pelaporan)));
                            $A_2 = explode('-', $TAHUN_LAPOR_POST);
                            $B_2 = $A_2[0];
                            $DATE_REPORT_2 = $A_2[0] . '-12-31';
                            $TANGGAL_LAPOR_POST = $DATE_REPORT_2;
                        }

                        $sql_check_t_pn_jabatan = "SELECT * FROM t_pn_jabatan WHERE id_pn = ".$ID_PN." AND is_active = 1 AND is_current = 1 AND is_deleted = 0 AND id_status_akhir_jabat <> 1 AND tahun_wl = '".$TAHUN_LAPOR_POST."'";

                        $act_check_t_pn_jabatan = $this->db->query($sql_check_t_pn_jabatan)->result();

                        foreach($act_check_t_pn_jabatan as $state_pn_jabat){
                            $setState_pn_jabat = $state_pn_jabat;
                        }



                $IS_COPY = '0';
                $A = explode('-', $TGL_LAPOR);
                $B = $A[0];
                $DATE_REPORT = $A[0] . '-12-31';





                if ($is_update == 'update') {
                    $this->db->where('ID_LHKPN', $id_lhkpn);
                    $update_result_hasil = $this->db->get('t_lhkpn')->row();
                    $tahun_yg_diubah = substr($update_result_hasil->tgl_lapor,0,4);

                    if($TGL_LAPOR > $update_result_hasil->tgl_kirim_final){
                        $result = array(
                            'status' => 0,
                            'msg' => 'Mohon Maaf , Tanggal Pelaporan tidak bisa melebihi Tanggal Kirim Final '
                        );
                        echo json_encode($result);
                        return;
                    }

                    $create_username = $this->session->userdata('USERNAME');
                    $created_time = date('Y-m-d h:i:s');
                    $created_ip = $this->input->ip_address();


                    $this->db->where('ID_LHKPN', $id_lhkpn);
                    $update_result = $this->db->update('t_lhkpn', array(
                        'JENIS_LAPORAN' => $JENIS_LAPOR,
                        'TGL_LAPOR' => $TGL_LAPOR,
                        'UPDATED_TIME' => $created_time,
                        'UPDATED_BY' => $create_username,
                        'UPDATED_IP' => $created_ip,
                    ));

                    $this->db->where('ID_LHKPN', $id_lhkpn);
                    $update_result_hasil = $this->db->get('t_lhkpn')->row();

                    function isTimestamp( $string ) {
                        return ( 1 === preg_match( '~^[1-9][0-9]*$~', $string ) );
                    }

                    if($setState_pn_jabat){


                        $this->db->where('ID', $setState_pn_jabat->ID);
                        $this->db->update('t_pn_jabatan', array(
                            'tgl_kirim' => $update_result_hasil->tgl_kirim_final
                        ));

                        if($tahun_yg_diubah < $TAHUN_LAPOR_POST){

                            $sql_check_min_t_pn_jabatan = "SELECT  * FROM t_pn_jabatan WHERE id_pn = ".$ID_PN." AND is_active = 1 AND is_current = 1 AND is_deleted = 0 AND id_status_akhir_jabat <> 1 ORDER BY tahun_wl ASC LIMIT 1";
                            $act_check_min_t_pn_jabatan = $this->db->query($sql_check_min_t_pn_jabatan)->result();
                            foreach($act_check_min_t_pn_jabatan as $state_min_pn_jabat){
                                $create_lhkpn = $state_min_pn_jabat;
                            }

                            $send_tahun_to_check = $create_lhkpn->tahun_wl.'-12-31';
                            $check_apakah_lhkpn_digunakan_sql = "SELECT * FROM t_lhkpn WHERE id_pn = ".$ID_PN." AND is_active = 1 AND tgl_lapor <= '$send_tahun_to_check'";
                            $check_apakah_lhkpn_digunakan = $this->db->query($check_apakah_lhkpn_digunakan_sql)->result();
                            if($check_apakah_lhkpn_digunakan){
                                foreach($check_apakah_lhkpn_digunakan as $state_lhkpn_digunakan){
                                    $get_old_lhkpn = $state_lhkpn_digunakan;
                                }

                                $this->db->where('ID', $create_lhkpn->ID);
                                        $this->db->update('t_pn_jabatan', array(
                                       'status_lapor' =>$get_old_lhkpn->STATUS,'tgl_kirim'=>$get_old_lhkpn->tgl_kirim_final
                                ));
                            }else{
                                $this->db->where('ID', $create_lhkpn->ID);
                                $check_created_at = $this->db->get('t_pn_jabatan')->row();

                                $check_unixtime = isTimestamp($check_created_at->CREATED_TIME);
                                if($check_unixtime){
                                    $date_convert = gmdate("Y-m-d", $check_created_at->CREATED_TIME);
                                }else{
                                    $date_convert = date_format(date_create($check_created_at->CREATED_TIME),'Y-m-d');
                                }

                                if($date_convert >= '2017-01-01'){
                                    $this->db->where('ID', $create_lhkpn->ID);
                                    $this->db->update('t_pn_jabatan', array(
                                        'IS_ACTIVE' => 0,'IS_DELETED'=>1,'IS_CURRENT'=>0
                                    ));
                                }else{
                                    $this->db->where('ID', $create_lhkpn->ID);
                                    $this->db->update('t_pn_jabatan', array(
                                        'status_lapor' => null,'tgl_kirim'=>null
                                    ));
                                }
                            }
                        }else{

                            $sql_check_max_t_pn_jabatan = "SELECT  * FROM t_pn_jabatan WHERE id_pn = ".$ID_PN." AND is_active = 1 AND is_current = 1 AND is_deleted = 0 AND id_status_akhir_jabat <> 1 ORDER BY tahun_wl DESC LIMIT 1";
                            $act_check_max_t_pn_jabatan = $this->db->query($sql_check_max_t_pn_jabatan)->result();
                            foreach($act_check_max_t_pn_jabatan as $state_max_pn_jabat){
                                $create_lhkpn = $state_max_pn_jabat;
                            }

                            $send_tahun_to_check = $tahun_yg_diubah.'-01-01';
                            $check_apakah_lhkpn_digunakan_sql = "SELECT * FROM t_lhkpn WHERE id_pn = ".$ID_PN." AND is_active = 1 AND tgl_lapor >= '$send_tahun_to_check'";
                            $check_apakah_lhkpn_digunakan = $this->db->query($check_apakah_lhkpn_digunakan_sql)->result();

                            if(!$check_apakah_lhkpn_digunakan){
                                $this->db->where('ID', $create_lhkpn->ID);
                                $this->db->update('t_pn_jabatan', array(
                                    'IS_ACTIVE' => 0,'IS_DELETED'=>1,'IS_CURRENT'=>0
                                ));
                            }
                        }

                    }else{
                        $create_username = $this->session->userdata('USERNAME');

                        $created_time = date('Y-m-d h:i:s');

                        $created_ip = $this->input->ip_address();

                        if($tahun_yg_diubah > $TAHUN_LAPOR_POST){

                            $sql_check_max_t_pn_jabatan = "SELECT  * FROM t_pn_jabatan WHERE id_pn = ".$ID_PN." AND is_active = 1 AND is_current = 1 AND is_deleted = 0 AND id_status_akhir_jabat <> 1 ORDER BY tahun_wl DESC LIMIT 1";
                            $act_check_max_t_pn_jabatan = $this->db->query($sql_check_max_t_pn_jabatan)->result();
                            foreach($act_check_max_t_pn_jabatan as $state_max_pn_jabat){
                                $create_lhkpn = $state_max_pn_jabat;
                            }


                            $send_tahun_to_check = $tahun_yg_diubah.'-01-01';
                            $check_apakah_lhkpn_digunakan_sql = "SELECT * FROM t_lhkpn WHERE id_pn = ".$ID_PN." AND is_active = 1 AND tgl_lapor >= '$send_tahun_to_check'";
                            $check_apakah_lhkpn_digunakan = $this->db->query($check_apakah_lhkpn_digunakan_sql)->result();

                            if($check_apakah_lhkpn_digunakan){

                                foreach($check_apakah_lhkpn_digunakan as $state_lhkpn_digunakan){
                                    $get_old_lhkpn = $state_lhkpn_digunakan;
                                 }

                                $this->db->where('ID', $create_lhkpn->ID);
                                $this->db->update('t_pn_jabatan', array(
                                    'status_lapor' =>$get_old_lhkpn->STATUS,'tgl_kirim'=>$get_old_lhkpn->tgl_kirim_final
                                ));



                                $sql_insert_to_tpnjabatan = "INSERT INTO t_pn_jabatan (ID, ID_PN, ID_JABATAN,DESKRIPSI_JABATAN,ESELON,NAMA_LEMBAGA,LEMBAGA, UNIT_KERJA,SUB_UNIT_KERJA,ALAMAT_KANTOR,EMAIL_KANTOR,CREATED_TIME,CREATED_BY,CREATED_IP,UPDATED_TIME,UPDATED_BY,
                                UPDATED_IP,IS_PRIMARY,TMT,SD,FILE_SK,ID_STATUS_AKHIR_JABAT,KETERANGAN_AKHIR_JABAT,IS_CALON,IS_ACTIVE,IS_DELETED,IS_CURRENT,tgl_kirim,status_lapor,pnJabatanID_migrasi,lembagaid_migrasi,
                                unitkerjaID_migrasi,pnid_migrasi,id_instansi_bkn,id_unit_kerja_bkn,id_sub_unit_kerja_bkn,id_unor_bkn_st,id_jabatan_bkn_jfu,id_unor_jfu,is_wl,tahun_wl,ALASAN_NON_WL)
                                VALUES (null, $create_lhkpn->ID_PN, $create_lhkpn->ID_JABATAN,'$create_lhkpn->DESKRIPSI_JABATAN',$create_lhkpn->ESELON,'$create_lhkpn->NAMA_LEMBAGA',
                                $create_lhkpn->LEMBAGA, '$create_lhkpn->UNIT_KERJA','$create_lhkpn->SUB_UNIT_KERJA',null,null,
                                '$created_time','$create_username','$created_ip','$created_time','$create_username',
                                '$created_ip','$create_lhkpn->IS_PRIMARY',null,null,null,'$create_lhkpn->ID_STATUS_AKHIR_JABAT',
                                null,'$create_lhkpn->IS_CALON','$create_lhkpn->IS_ACTIVE','$create_lhkpn->IS_DELETED','$create_lhkpn->IS_CURRENT','$update_result_hasil->tgl_kirim_final',
                                '$update_result_hasil->STATUS',null,null,null,null,null,null,null,null,null,null,'$create_lhkpn->is_wl','$TAHUN_LAPOR_POST',null)";
                                $this->db->query($sql_insert_to_tpnjabatan);
                            }else{
                                $sql_insert_to_tpnjabatan = "INSERT INTO t_pn_jabatan (ID, ID_PN, ID_JABATAN,DESKRIPSI_JABATAN,ESELON,NAMA_LEMBAGA,LEMBAGA, UNIT_KERJA,SUB_UNIT_KERJA,ALAMAT_KANTOR,EMAIL_KANTOR,CREATED_TIME,CREATED_BY,CREATED_IP,UPDATED_TIME,UPDATED_BY,
                                UPDATED_IP,IS_PRIMARY,TMT,SD,FILE_SK,ID_STATUS_AKHIR_JABAT,KETERANGAN_AKHIR_JABAT,IS_CALON,IS_ACTIVE,IS_DELETED,IS_CURRENT,tgl_kirim,status_lapor,pnJabatanID_migrasi,lembagaid_migrasi,
                                unitkerjaID_migrasi,pnid_migrasi,id_instansi_bkn,id_unit_kerja_bkn,id_sub_unit_kerja_bkn,id_unor_bkn_st,id_jabatan_bkn_jfu,id_unor_jfu,is_wl,tahun_wl,ALASAN_NON_WL)
                                VALUES (null, $create_lhkpn->ID_PN, $create_lhkpn->ID_JABATAN,'$create_lhkpn->DESKRIPSI_JABATAN',$create_lhkpn->ESELON,'$create_lhkpn->NAMA_LEMBAGA',
                                $create_lhkpn->LEMBAGA, '$create_lhkpn->UNIT_KERJA','$create_lhkpn->SUB_UNIT_KERJA',null,null,
                                '$created_time','$create_username','$created_ip','$created_time','$create_username',
                                '$created_ip','$create_lhkpn->IS_PRIMARY',null,null,null,'$create_lhkpn->ID_STATUS_AKHIR_JABAT',
                                null,'$create_lhkpn->IS_CALON','$create_lhkpn->IS_ACTIVE','$create_lhkpn->IS_DELETED','$create_lhkpn->IS_CURRENT','$update_result_hasil->tgl_kirim_final',
                                '$update_result_hasil->STATUS',null,null,null,null,null,null,null,null,null,null,'$create_lhkpn->is_wl','$TAHUN_LAPOR_POST',null)";
                                $this->db->query($sql_insert_to_tpnjabatan);

                                $this->db->where('ID', $create_lhkpn->ID);
                                $this->db->update('t_pn_jabatan', array(
                                    'IS_ACTIVE' => 0,'IS_DELETED'=>1,'IS_CURRENT'=>0
                                ));
                            }

                        }else if($tahun_yg_diubah < $TAHUN_LAPOR_POST){


                            $sql_check_min_t_pn_jabatan = "SELECT  * FROM t_pn_jabatan WHERE id_pn = ".$ID_PN." AND is_active = 1 AND is_current = 1 AND is_deleted = 0 AND id_status_akhir_jabat <> 1 ORDER BY tahun_wl ASC LIMIT 1";
                            $act_check_min_t_pn_jabatan = $this->db->query($sql_check_min_t_pn_jabatan)->result();
                            foreach($act_check_min_t_pn_jabatan as $state_min_pn_jabat){
                                $create_lhkpn = $state_min_pn_jabat;
                            }

                            $send_tahun_to_check = $create_lhkpn->tahun_wl.'-12-31';
                            $check_apakah_lhkpn_digunakan_sql = "SELECT * FROM t_lhkpn WHERE id_pn = ".$ID_PN." AND is_active = 1 AND tgl_lapor <= '$send_tahun_to_check'";
                            $check_apakah_lhkpn_digunakan = $this->db->query($check_apakah_lhkpn_digunakan_sql)->result();


                            if($check_apakah_lhkpn_digunakan){

                                foreach($check_apakah_lhkpn_digunakan as $state_lhkpn_digunakan){
                                    $get_old_lhkpn = $state_lhkpn_digunakan;
                                 }

                                $this->db->where('ID', $create_lhkpn->ID);
                                $this->db->update('t_pn_jabatan', array(
                                    'status_lapor' =>$get_old_lhkpn->STATUS,'tgl_kirim'=>$get_old_lhkpn->tgl_kirim_final
                                ));

                                $sql_insert_to_tpnjabatan = "INSERT INTO t_pn_jabatan (ID, ID_PN, ID_JABATAN,DESKRIPSI_JABATAN,ESELON,NAMA_LEMBAGA,LEMBAGA, UNIT_KERJA,SUB_UNIT_KERJA,ALAMAT_KANTOR,EMAIL_KANTOR,CREATED_TIME,CREATED_BY,CREATED_IP,UPDATED_TIME,UPDATED_BY,
                                UPDATED_IP,IS_PRIMARY,TMT,SD,FILE_SK,ID_STATUS_AKHIR_JABAT,KETERANGAN_AKHIR_JABAT,IS_CALON,IS_ACTIVE,IS_DELETED,IS_CURRENT,tgl_kirim,status_lapor,pnJabatanID_migrasi,lembagaid_migrasi,
                                unitkerjaID_migrasi,pnid_migrasi,id_instansi_bkn,id_unit_kerja_bkn,id_sub_unit_kerja_bkn,id_unor_bkn_st,id_jabatan_bkn_jfu,id_unor_jfu,is_wl,tahun_wl,ALASAN_NON_WL)
                                VALUES (null, $create_lhkpn->ID_PN, $create_lhkpn->ID_JABATAN,'$create_lhkpn->DESKRIPSI_JABATAN',$create_lhkpn->ESELON,'$create_lhkpn->NAMA_LEMBAGA',
                                $create_lhkpn->LEMBAGA, '$create_lhkpn->UNIT_KERJA','$create_lhkpn->SUB_UNIT_KERJA',null,null,
                                '$created_time','$create_username','$created_ip','$created_time','$create_username',
                                '$created_ip','$create_lhkpn->IS_PRIMARY',null,null,null,'$create_lhkpn->ID_STATUS_AKHIR_JABAT',
                                null,'$create_lhkpn->IS_CALON','$create_lhkpn->IS_ACTIVE','$create_lhkpn->IS_DELETED','$create_lhkpn->IS_CURRENT','$update_result_hasil->tgl_kirim_final',
                                '$update_result_hasil->STATUS',null,null,null,null,null,null,null,null,null,null,'$create_lhkpn->is_wl','$TAHUN_LAPOR_POST',null)";
                                $this->db->query($sql_insert_to_tpnjabatan);
                            }else{

                                $this->db->where('ID', $create_lhkpn->ID);
                                $check_created_at = $this->db->get('t_pn_jabatan')->row();

                                $check_unixtime = isTimestamp($check_created_at->CREATED_TIME);
                                if($check_unixtime){
                                    $date_convert = gmdate("Y-m-d", $check_created_at->CREATED_TIME);
                                }else{
                                    $date_convert = date_format(date_create($check_created_at->CREATED_TIME),'Y-m-d');
                                }

                                if($date_convert >= '2017-01-01'){
                                    $this->db->where('ID', $create_lhkpn->ID);
                                    $this->db->update('t_pn_jabatan', array(
                                        'IS_ACTIVE' => 0,'IS_DELETED'=>1,'IS_CURRENT'=>0
                                    ));
                                }else{
                                    $this->db->where('ID', $create_lhkpn->ID);
                                    $this->db->update('t_pn_jabatan', array(
                                        'status_lapor' => null,'tgl_kirim'=>null
                                    ));
                                }




                                $sql_insert_to_tpnjabatan = "INSERT INTO t_pn_jabatan (ID, ID_PN, ID_JABATAN,DESKRIPSI_JABATAN,ESELON,NAMA_LEMBAGA,LEMBAGA, UNIT_KERJA,SUB_UNIT_KERJA,ALAMAT_KANTOR,EMAIL_KANTOR,CREATED_TIME,CREATED_BY,CREATED_IP,UPDATED_TIME,UPDATED_BY,
                                UPDATED_IP,IS_PRIMARY,TMT,SD,FILE_SK,ID_STATUS_AKHIR_JABAT,KETERANGAN_AKHIR_JABAT,IS_CALON,IS_ACTIVE,IS_DELETED,IS_CURRENT,tgl_kirim,status_lapor,pnJabatanID_migrasi,lembagaid_migrasi,
                                unitkerjaID_migrasi,pnid_migrasi,id_instansi_bkn,id_unit_kerja_bkn,id_sub_unit_kerja_bkn,id_unor_bkn_st,id_jabatan_bkn_jfu,id_unor_jfu,is_wl,tahun_wl,ALASAN_NON_WL)
                                VALUES (null, $create_lhkpn->ID_PN, $create_lhkpn->ID_JABATAN,'$create_lhkpn->DESKRIPSI_JABATAN',$create_lhkpn->ESELON,'$create_lhkpn->NAMA_LEMBAGA',
                                $create_lhkpn->LEMBAGA, '$create_lhkpn->UNIT_KERJA','$create_lhkpn->SUB_UNIT_KERJA',null,null,
                                '$created_time','$create_username','$created_ip','$created_time','$create_username',
                                '$created_ip','$create_lhkpn->IS_PRIMARY',null,null,null,'$create_lhkpn->ID_STATUS_AKHIR_JABAT',
                                null,'$create_lhkpn->IS_CALON','$create_lhkpn->IS_ACTIVE','$create_lhkpn->IS_DELETED','$create_lhkpn->IS_CURRENT','$update_result_hasil->tgl_kirim_final',
                                '$update_result_hasil->STATUS',null,null,null,null,null,null,null,null,null,null,'$create_lhkpn->is_wl','$TAHUN_LAPOR_POST',null)";
                                $this->db->query($sql_insert_to_tpnjabatan);


                            }
                        }
                    }
                    $result = array(
                        'status' => 1,
                        'msg' => 'Data Berhasil Diupdate '
                    );
                    echo json_encode($result);
                    return;
                    $update_result = "1";
                }
                if ($update_result) {
                    $result = array(
                        'status' => 1,
                        'msg' => 'Data Berhasil Diupdate '
                    );
                }
                else{
                    $result = array(
                        'status' => 0,
                        'msg' => 'Data Gagal Diupdate'
                    );
                }
            }
            echo json_encode($result);
        } else {
            $result = array(
                'status' => 0,
                'msg' => 'Data Gagal Diupdate'
            );
        echo json_encode($result);
        }
    }

    public function export_tracking_lhkpn($lembaga, $uk, $tgl_lahir, $nhk, $nama)
    {  
        $spreadsheet = new Spreadsheet();
        
        $styleArray = [
            'font'  => [
                'size'  => 11,
                'name'  => 'Calibri'
                ]
            ];
        
        $nama = urldecode($nama);
            
        $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);
        $this->load->model('Mlhkpn');
            
        $cari = new stdClass();
        $cari->lembaga = ($lembaga == 'ALL') ? '' : $lembaga;
        $cari->uk = ($uk == 'ALL') ? '' : $uk;
        $cari->tgl_lahir = ($tgl_lahir == 'ALL') ? '' : $tgl_lahir;
        $cari->nhk = ($nhk == 'ALL') ? '' : $nhk;
        $cari->nama = ($nama == 'ALL') ? '' : $nama;
        $data_tracking_lhkpn = $this->Mlhkpn->tracking_lhkpn_cetak($cari);
        
        $this->cetak_tracking_lhkpn($data_tracking_lhkpn, $spreadsheet);
         // --- end excel content --- //

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        //setup file meta
        $filename = 'Tracking_LHKPN'.date('dmyGi').'.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        // ------ save and export to folder download

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        return;
            
    }

    public function cetak_tracking_lhkpn($data_tracking_lhkpn, $spreadsheet)
    {
        $spreadsheet->getActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('Tracking LHKPN');
        $data_arr = $data_tracking_lhkpn;

        $jumlah_data = count($data_arr);
        
        if($jumlah_data > 0){
            $startRow = 2;
            $no = 1;

            foreach($data_arr as $item){

                $tahun_lapor = date('Y', strtotime($item->tgl_lapor));
                $tgl_lapor = date('d/m/Y', strtotime($item->tgl_lapor));
                $tgl_kirim = date('d/m/Y', strtotime($item->tgl_kirim_final));
                $aStatus = ['0' => 'Draft', '1' => 'Proses Verifikasi', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi Lengkap', '4' => 'Diumumkan Lengkap', '5' => 'Terverifikasi Tidak Lengkap', '6' => 'Diumumkan Tidak Lengkap', '7' => 'Dikembalikan'];
                $total_harta = ($item->hartirak + $item->suberga + $item->harlin + (int)$item->kaseka + $item->harger + $item->harger2) - $item->hutang;

                $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
                $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, $item->NAMA);
                $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, "'".$item->NIK);
                $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $item->NAMA_JABATAN);
                $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, $item->SUK_NAMA);
                $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, $item->UK_NAMA);
                $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $item->INST_NAMA);
                $spreadsheet->getActiveSheet()->setCellValue('H'.$startRow, $tahun_lapor);
                $spreadsheet->getActiveSheet()->setCellValue('I'.$startRow, $tgl_lapor);
                $spreadsheet->getActiveSheet()->setCellValue('J'.$startRow, $tgl_kirim);
                $spreadsheet->getActiveSheet()->setCellValue('L'.$startRow, strtoupper($aStatus[$item->STATUS]));
                
                if($total_harta < 0 || $item->hartirak < 0 || $item->suberga < 0 || $item->harlin < 0 || $item->kaseka < 0 || $item->harger < 0 || $item->harger2 < 0 || $item->hutang < 0  ){  // format minus in rupiah //
                    $spreadsheet->getActiveSheet()->getStyle('K'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* -#,##0_-;-* -#,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('M'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('N'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('O'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('P'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('Q'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('R'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('S'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                }else{
                    $spreadsheet->getActiveSheet()->getStyle('K'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('M'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('N'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('O'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('P'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('Q'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('R'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                    $spreadsheet->getActiveSheet()->getStyle('S'.$startRow)->getNumberFormat()
                    ->setFormatCode('Rp _-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                }
                
                $spreadsheet->getActiveSheet()->setCellValue('K'.$startRow, abs($total_harta));
                $spreadsheet->getActiveSheet()->setCellValue('M'.$startRow, abs($item->hartirak));
                $spreadsheet->getActiveSheet()->setCellValue('N'.$startRow, abs($item->harger));
                $spreadsheet->getActiveSheet()->setCellValue('O'.$startRow, abs($item->harger2));
                $spreadsheet->getActiveSheet()->setCellValue('P'.$startRow, abs($item->suberga));
                $spreadsheet->getActiveSheet()->setCellValue('Q'.$startRow, abs($item->kaseka));
                $spreadsheet->getActiveSheet()->setCellValue('R'.$startRow, abs($item->harlin));
                $spreadsheet->getActiveSheet()->setCellValue('S'.$startRow, abs($item->hutang));

                $startRow++;
                $no++;
            }
        }

        $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'NAMA');
        $spreadsheet->getActiveSheet()->setCellValue('C1', 'NIK');
        $spreadsheet->getActiveSheet()->setCellValue('D1', 'JABATAN');
        $spreadsheet->getActiveSheet()->setCellValue('E1', 'SUB UNIT KERJA');
        $spreadsheet->getActiveSheet()->setCellValue('F1', 'UNIT KERJA');
        $spreadsheet->getActiveSheet()->setCellValue('G1', 'INSTANSI');
        $spreadsheet->getActiveSheet()->setCellValue('H1', 'TAHUN LAPOR');
        $spreadsheet->getActiveSheet()->setCellValue('I1', 'TANGGAL LAPOR');
        $spreadsheet->getActiveSheet()->setCellValue('J1', 'TANGGAL KIRIM');
        $spreadsheet->getActiveSheet()->setCellValue('K1', 'TOTAL HARTA KEKAYAAN');
        $spreadsheet->getActiveSheet()->setCellValue('L1', 'STATUS');
        $spreadsheet->getActiveSheet()->setCellValue('M1', 'HARTA TIDAK BERGERAK');
        $spreadsheet->getActiveSheet()->setCellValue('N1', 'TRANSPORTASI');
        $spreadsheet->getActiveSheet()->setCellValue('O1', 'HARTA BERGERAK LAINNYA');
        $spreadsheet->getActiveSheet()->setCellValue('P1', 'SURAT BERHARGA');
        $spreadsheet->getActiveSheet()->setCellValue('Q1', 'KAS/SETARA KAS');
        $spreadsheet->getActiveSheet()->setCellValue('R1', 'HARTA LAINNYA');
        $spreadsheet->getActiveSheet()->setCellValue('S1', 'HUTANG');

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(28);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(28);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(28);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(28);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(28);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(28);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(28);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(28);

        $this->style_table_header('A1:S1', 'cddafd', $spreadsheet);
        $spreadsheet->getActiveSheet()->getStyle("A1:M1")->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle('B2:B'.(1+$jumlah_data))->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('D2:G'.(1+$jumlah_data))->getAlignment()->setWrapText(true);
        
        $spreadsheet->getActiveSheet()->getStyle('A2:A'.(1+$jumlah_data))
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:A'.(1+$jumlah_data))
        ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('B2:L'.(1+$jumlah_data))
        ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('I2:J'.(1+$jumlah_data))
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $spreadsheet->getActiveSheet()->getStyle('L2:L'.(1+$jumlah_data))
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
    }

    public function update_flag_sk()
    {
        
        $action = @$this->input->post('FLAG', TRUE);

        if($action=='PN')
        {
            $id = @$this->input->post('ID_LHKPN', TRUE);
            $username = $this->mglobal->get_by_id('t_lhkpn_data_pribadi','ID_LHKPN',$id);
            ng::logActivity('Merubah Flag SK PN, username = ' . $username->NAMA_LENGKAP.', IDLHKPN = '.$id);
            $this->mglobal->update('t_lhkpn_data_pribadi',['FLAG_SK'=>'1'],['ID_LHKPN' => $id]);
            echo '1';
        }else{
            $id = @$this->input->post('ID_KELUARGA', TRUE);
            $username = $this->mglobal->get_by_id('t_lhkpn_keluarga','ID_KELUARGA',$id);
            ng::logActivity('Merubah Flag SK Keluarga, username = ' . $username->NAMA.', ID_KELUARGA = '.$id);
            $this->mglobal->update('t_lhkpn_keluarga',['FLAG_SK'=>'1'],['ID_KELUARGA' => $id]);
            echo '1';
        }
        
    }

    private function style_table_header($startEndCell, $bgcolor, $spreadsheet, $text_align = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, $fontcolor = \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK, $isBold = true, $isWrapText = true)
    {
        # code...
        if ($bgcolor != '') {
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getFill()->getStartColor()->setARGB($bgcolor);
        }

        $styleArray = [
        'borders' => [
            'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => $this->color_grayborder],
            ],
        ],
        ];

        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getFont()->getColor()->setARGB($fontcolor);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getAlignment()->setHorizontal($text_align);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getFont()->setBold($isBold);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)->getAlignment()->setWrapText($isWrapText);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)->applyFromArray($styleArray);

    }


}
