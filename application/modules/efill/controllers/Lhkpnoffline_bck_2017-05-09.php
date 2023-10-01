<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller Lhkpnoffline
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Efill/Controllers/Lhkpnoffline
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lhkpnoffline extends CI_Controller {

    // num of records per page
    public $limit = 10;
    public $iscetak = false;

    public function __construct() {
        parent::__construct();
        $this->load->model('mglobal');
        call_user_func('ng::islogin');
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);

        // prepare search
        foreach ((array) @$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];
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

        $this->data['role'] = urlencode($this->config->item('LHKPNOFFLINE_PAGE_ROLE')[$mode]);
        switch ($mode) {
            case 'penerimaan':
                // prepare query
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
                $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.USERNAME', $this->session->userdata('USR'));

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

                if (empty($this->CARI['STATUS'])) {
                    $this->CARI['STATUS'] = '0';
                }
                $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.IS_DITERIMA_KOORD_CS = "' . $this->CARI['STATUS'] . '"', null, false);

                // }
                //  else {
                // 	($i == 0) ? $this->db->where($t_pelaporan[$i].' = '.date('Y'), null, false) : $this->db->or_where($t_pelaporan[$i].' = '.date('Y'), null, false);
                // }

                if (@$this->CARI['TEXT']) {
                    if ($this->CARI['BY'] == 'PN') {
                        $this->db->like('T_LHKPNOFFLINE_PENERIMAAN.USERNAME', $this->CARI['TEXT']);
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
                    $this->db->order_by('T_PN.NAMA', 'asc');
                }
                $this->total_rows = $this->db->get('')->num_rows();
                $query = $this->db->get('', $this->limit, $this->offset);
                $this->items = $query->result();
                $this->end = $query->num_rows();
//                echo $this->db->last_query();
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

                break;
            case 'penugasan':
                // prepare query
                $this->db->start_cache();
                // $this->db->select('T_LHKPNOFFLINE_PENUGASAN_ENTRY.*, 
                // 	T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN, 
                // 	T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN,  
                // 	YEAR(T_LHKPNOFFLINE_PENERIMAAN.TANGGAL_PELAPORAN) AS TAHUN_PELAPORAN,  
                // 	T_PN.NIK, 
                // 	T_PN.NAMA');
                // $this->db->from('T_LHKPNOFFLINE_PENUGASAN_ENTRY');
                // $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = T_LHKPNOFFLINE_PENUGASAN_ENTRY.ID_PENERIMAAN');
                // $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                // $this->db->where('1=1', null, false);
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
                break;
            case 'tugas':
                // prepare query
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

                // script cekbast yang lama 
                // $this->db->start_cache();
                // $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
                //     T_PN.NIK,
                //     T_PN.NAMA,
                //     (SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
                //     (SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
                //     (SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
                // ');
                // $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                // $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                // $this->db->where('1=1', null, false);
                // if (@$this->CARI['TEXT']) {
                //     if ($this->CARI['BY'] == 'PN') {
                //         $this->db->like('T_LHKPNOFFLINE_PENERIMAAN.USERNAME', $this->CARI['TEXT']);
                //     } else if ($this->CARI['BY'] == 'NAMA') {
                //         $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
                //     } else if ($this->CARI['BY'] == 'NIK') {
                //         $this->db->like('T_PN.NIK', $this->CARI['TEXT']);
                //     }
                // }
                // if(!isset($this->CARI['STATUS'])){
                //     $this->CARI['STATUS'] = 0;
                //     $this->db->where('IS_DITERIMA_KOORD_ENTRY', '0');
                // }else{
                //     if($this->CARI['STATUS'] == '1') {
                //         $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.USERNAME_KOORD_ENTRY', $this->session->userdata('USR'));
                //     }
                //     $this->db->where('IS_DITERIMA_KOORD_ENTRY', $this->CARI['STATUS']);
                // }
                // $this->data['id'] = $this->input->post('id');
                // $this->total_rows = $this->db->get('')->num_rows();
                // $query = $this->db->get('', $this->limit, $this->offset);
                // $this->items = $query->result();
                // $this->end = $query->num_rows();
                // $this->db->flush_cache();
                break;
            case 'historybast' :
                // prepare query
                if ($this->input->post('CARI[JENIS]') == '0') {
                    $this->db->start_cache();
                    // $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
                    //     T_PN.NIK,
                    //     T_PN.NAMA,
                    //     T_BAST_CS.ID_BAST_CS,
                    //     T_BAST_CS.NOMOR_BAST,
                    //     T_BAST_CS.USER_CS,
                    //     T_BAST_CS.USER_KOORD_CS,
                    //     T_BAST_CS.TGL_PENYERAHAN,
                    //     (SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
                    //     (SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
                    //     (SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
                    // ');
                    // $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                    // $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                    // $this->db->join('R_BAST_CS', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = R_BAST_CS.ID_PENERIMAAN');
                    // $this->db->join('T_BAST_CS', 'R_BAST_CS.ID_BAST_CS = T_BAST_CS.ID_BAST_CS');
                    // $this->db->where('1=1', null, false);
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
                    // $this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*,
                    //     T_PN.NIK,
                    //     T_PN.NAMA,
                    //     T_BAST_ENTRY.ID_BAST_ENTRI,
                    //     T_BAST_ENTRY.NOMOR_BAST,
                    //     T_BAST_ENTRY.USER_KOORD_CS,
                    //     T_BAST_ENTRY.USER_KOORD_ENTRI,
                    //     T_BAST_ENTRY.TGL_PENYERAHAN,
                    //     (SELECT M_JABATAN.NAMA_JABATAN FROM M_JABATAN WHERE M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN) AS NAMA_JABATAN,
                    //     (SELECT M_UNIT_KERJA.UK_NAMA FROM M_UNIT_KERJA WHERE M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA) AS NAMA_UNIT_KERJA,
                    //     (SELECT M_INST_SATKER.INST_NAMA FROM M_INST_SATKER WHERE M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA) AS NAMA_LEMBAGA
                    // ');
                    // $this->db->from('T_LHKPNOFFLINE_PENERIMAAN');
                    // $this->db->join('T_PN', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PN = T_PN.ID_PN');
                    // $this->db->join('R_BAST_ENTRY', 'T_LHKPNOFFLINE_PENERIMAAN.ID_PENERIMAAN = R_BAST_ENTRY.ID_PENERIMAAN');
                    // $this->db->join('T_BAST_ENTRY', 'R_BAST_ENTRY.ID_BAST_ENTRI = T_BAST_ENTRY.ID_BAST_ENTRI');
                    // $this->db->where('1=1', null, false);
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

        if ($this->iscetak) {
            ng::exportDataTo($this->data, $cetak, strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . $mode . '_' . 'cetak', $this->data['filenameCetak']);
        } else {
            // load view
            $this->load->view($mode ? strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . $mode . '_' . strtolower(__FUNCTION__) : strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $this->data);
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

                $barcode = $this->data['item']->TAHUN_PELAPORAN . '/' . ($this->data['item']->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $this->data['item']->NIK . '/' . $this->data['item']->ID_LHKPN;

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
                        'TANGGAL_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) <> '4') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN', TRUE)))) : date('Y-m-d', strtotime($this->input->post('TAHUN_PELAPORAN', TRUE) . '-12-31')),
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

                    //load_data_keluarga_by_dani
                    //       $check_exist = $this->db->get('t_lhkpn')->row();
                    //        if ($check_exist) {
                    //     $IS_COPY = '1';
                    //     $ID_LHKPN_PREV = $check_exist->ID_LHKPN;
                    // 	 }
                    // 	   if ($check_exist) {
                    //     // UPDATE KELUARGA
                    //     $this->db->where('ID_LHKPN', $ID_LHKPN_PREV);
                    //     $keluarga = $this->db->get('t_lhkpn_keluarga')->result();
                    //     if ($keluarga) {
                    //         foreach ($keluarga as $kl) {
                    //             $arr_keluarga = array(
                    //                 'ID_KELUARGA_LAMA' => $kl->ID_KELUARGA,
                    //                 'ID_LHKPN' => $ID_LHKPN_NEW,
                    //                 'NAMA' => $kl->NAMA,
                    //                 'HUBUNGAN' => $kl->HUBUNGAN,
                    //                 'STATUS_HUBUNGAN' => $kl->STATUS_HUBUNGAN,
                    //                 'TEMPAT_LAHIR' => $kl->TEMPAT_LAHIR,
                    //                 'TANGGAL_LAHIR' => $kl->TANGGAL_LAHIR,
                    //                 'JENIS_KELAMIN' => $kl->JENIS_KELAMIN,
                    //                 'TEMPAT_NIKAH' => $kl->TEMPAT_NIKAH,
                    //                 'TANGGAL_NIKAH' => $kl->TANGGAL_NIKAH,
                    //                 'TEMPAT_CERAI' => $kl->TEMPAT_CERAI,
                    //                 'TANGGAL_CERAI' => $kl->TANGGAL_CERAI,
                    //                 'PEKERJAAN' => $kl->PEKERJAAN,
                    //                 'ALAMAT_RUMAH' => $kl->ALAMAT_RUMAH,
                    //                 'NOMOR_TELPON' => $kl->NOMOR_TELPON,
                    //                 'IS_ACTIVE' => 1,
                    //                 'CREATED_TIME' => time(),
                    //                 'CREATED_BY' => $this->session->userdata('NAMA'),
                    //                 'CREATED_IP' => get_client_ip(),
                    //                 'UPDATED_TIME' => time(),
                    //                 'UPDATED_BY' => $this->session->userdata('NAMA'),
                    //                 'UPDATED_IP' => get_client_ip(),
                    //             );
                    //             $this->db->insert('t_lhkpn_keluarga', $arr_keluarga);
                    //         }
                    //     }
                    //     $this->db->where('ID_LHKPN', $ID_LHKPN_PREV);
                    // } else {
                    //     $this->loadwskeluarga($ID_LHKPN_NEW);
                    // }
                    //end load data keluarga by dani
                } else if ($this->input->post('act', TRUE) == 'doupdate') {
                    $penerimaan = array(
                        'ID_PN' => $this->input->post('ID_PN', TRUE),
                        'JENIS_LAPORAN' => $this->input->post('JENIS_LAPORAN', TRUE),
                        'TANGGAL_PELAPORAN' => ($this->input->post('JENIS_LAPORAN', TRUE) <> '4') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PELAPORAN', TRUE)))) : date('Y-m-d', strtotime($this->input->post('TAHUN_PELAPORAN', TRUE) . '-12-31')),
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
                $this->data['barcode'] = $this->data['item']->TAHUN_PELAPORAN . '/' . ($this->data['item']->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $this->data['item']->NIK . '/' . $this->data['item']->ID_LHKPN;
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

                $item = @$this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', $iniJoin, ['SUBSTRING(md5(T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN), 6, 8) =' => $id], 'T_LHKPNOFFLINE_PENERIMAAN.*, T_PN.*, M_INST_SATKER.*, M_JABATAN.*, M_BIDANG.*, M_UNIT_KERJA.*, T_LHKPN.ID_LHKPN, T_LHKPN.JENIS_LAPORAN, YEAR(T_LHKPN.TGL_LAPOR) AS TAHUN_PELAPORAN')[0];
                if (!empty($item)) {
                    $barcode = $item->TAHUN_PELAPORAN . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                } else {
                    $newitem = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN']], ['SUBSTRING(md5(ID_LHKPN), 6, 8) =' => $id, 'T_LHKPN.IS_ACTIVE' => '1'], 'TGL_LAPOR, JENIS_LAPORAN, NIK, ID_LHKPN, ENTRY_VIA, tgl_kirim_final')[0];
//                    $barcode = date('Y', strtotime($newitem->TGL_LAPOR)) . '/' . ($newitem->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $newitem->NIK . '/' . $newitem->ID_LHKPN;
                    $barcode = date('Y', strtotime($newitem->tgl_kirim_final)) . '/' . ($newitem->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $newitem->NIK . '/' . $newitem->ID_LHKPN;
                }

                $this->base_url = site_url('efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');

                $this->data = [];

                $this->db->start_cache();
                $this->db->select('*');
                $this->db->from('T_LHKPN');
                $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');

                if (isset($newitem)) {
                    $this->db->join('T_PN_JABATAN', 'T_PN.ID_PN=T_PN_JABATAN.ID_PN');
                    $this->db->join('M_INST_SATKER', 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'left');
                    $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN', 'left');
                    $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = T_PN_JABATAN.UNIT_KERJA', 'left');
                    $this->db->where('IS_CURRENT', 1);
                } else {
                    $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPN.ID_LHKPN = T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'left');
                    $this->db->join('M_INST_SATKER', 'T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'left');
                    $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'left');
                    $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'left');
                }

                $xdata = $barcode;
                $exp = explode('/', $xdata);
                $this->db->where('T_LHKPN.ID_LHKPN', $exp[3]);
                $this->db->where('T_PN.NIK', $exp[2]);

                if (!empty($item)) {
//                    $this->db->where('YEAR(T_LHKPN.TGL_LAPOR)', $exp[0]);
                    $this->db->where('YEAR(T_LHKPN.tgl_kirim_final)', $exp[0]);
                    if ($exp[1] == 'R' || $exp[1] == 'r') {
                        $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN', '4');
                    } else {
                        $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN <>', '4');
                    }
                } else {
//                    $this->db->where('T_LHKPN.TGL_LAPOR LIKE', $exp[0] . '%');
                    $this->db->where('T_LHKPN.tgl_kirim_final LIKE', $exp[0] . '%');
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
                $this->data['id'] = $barcode;

                $this->load->model('mglobal');
                $joinHist = [
                    ['table' => 'M_STATUS_LHKPN', 'on' => 'T_LHKPN_STATUS_HISTORY.ID_STATUS = M_STATUS_LHKPN.ID_STATUS'],
                    ['table' => 'T_USER A', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENGIRIM = A.USERNAME', 'join' => 'LEFT'],
                    ['table' => 'T_USER B', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENERIMA = B.USERNAME', 'join' => 'LEFT']
                ];
                $select = 'T_LHKPN_STATUS_HISTORY.DATE_INSERT, M_STATUS_LHKPN.ID_STATUS, M_STATUS_LHKPN.STATUS, A.NAMA as PENGIRIM, B.NAMA as PENERIMA';
                $this->data['getHistory'] = $this->mglobal->get_data_all('T_LHKPN_STATUS_HISTORY', $joinHist, ['ID_LHKPN' => @$this->items[0]->ID_LHKPN], $select, NULL, ['DATE_INSERT', 'asc']);

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
                            $this->db->join('M_INST_SATKER', 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'left');
                            $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                            $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN', 'left');
                            $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = T_PN_JABATAN.UNIT_KERJA', 'left');
                            $this->db->where('IS_CURRENT', 1);
                        } else {
                            $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPN.ID_LHKPN = T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'left');
                            $this->db->join('M_INST_SATKER', 'T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'left');
                            $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                            $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'left');
                            $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'left');
                        }

                        $this->db->where('T_LHKPN.ID_LHKPN', @$exp[3]);
                        $this->db->where('YEAR(T_LHKPN.TGL_LAPOR)', @$exp[0]);
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


        $this->db->start_cache();

        $this->db->select('
            P.ID_PN,
            P.NIK,
            P.NAMA,
            JAB.NAMA_JABATAN NAMA_JABATAN,
            SUK.SUK_NAMA N_SUK,
            UK.UK_NAMA N_UK,
            INTS.INST_NAMA,
            PJ.ID ID_JAB,
            PJ.UNIT_KERJA,
            PJ.SUB_UNIT_KERJA,
            U.ID_USER,
            PJ.ID_STATUS_AKHIR_JABAT', false); // parameter false wajib
        $this->db->from('T_PN P');
        $this->db->join('T_USER U', 'U.USERNAME = P.NIK');
        $this->db->join('T_PN_JABATAN PJ', 'P.ID_PN = PJ.ID_PN', 'left');
        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN', 'left');
        $this->db->join('m_sub_unit_kerja SUK', 'SUK.SUK_ID = JAB.SUK_ID', 'left');
        $this->db->join('m_unit_kerja UK', 'UK.UK_ID = JAB.UK_ID', 'left');
        $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = UK.UK_LEMBAGA_ID', 'left');

//        $this->db->where(' V2.JML_AKTIF IS NOT NULL', null, false); // parameter false wajib

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
//		 echo $this->db->last_query();
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
            $PN = @$this->mglobal->get_data_all_array('T_PN', $join, ['NIK' => $NIK, 'NAMA' => $NAMA, 'T_PN_JABATAN.IS_ACTIVE' => '1'], $select)[0];
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

}
