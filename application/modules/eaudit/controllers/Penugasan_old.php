<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Controller
 *
 * @author Gunaones - SKELETON-2015 - PT.Mitreka Solusi Indonesia
 * @package Ever/Controllers/Verification
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penugasan_old extends Nglibs {

    public function __construct() {
        parent::__construct();
        $this->makses->initialize();
        // $this->makses->check_is_read();
    }

    protected function beforeQuery($method) {

        if ($method != 'lhkpn') {
            $this->db->start_cache();
        }

    }

    protected function afterQuery($method){
        if ($method != 'lhkpn') {
            parent::afterQuery();
        }
    }

    public function lhkpn($type = '', $id = '') {
        $this->load->model('mlhkpnkeluarga');

        $this->data['tbl'] = 'T_LHKPN';
        $this->data['pk'] = 'ID_LHKPN';
        $this->data['role'] = urlencode($this->config->item('LHKPNOFFLINE_PAGE_ROLE')['verification']);


        if ($type == 'list') {

            $this->data['id'] = $this->input->post('id');

            if ($this->data['id'] != '') {
                $this->db->select('JABATAN.*,
				T_PN.*,
				T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.*,
				T_LHKPN.*');
//                $this->db->from('T_LHKPN');
                $this->db->join('(
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
                $this->db->join('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', 'T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.ID_LHKPN = T_LHKPN.ID_LHKPN and T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.IS_ACTIVE = 1', 'left');

                // $this->db->where("T_LHKPN.STATUS IN ('0', '4')");
                $this->db->where("T_LHKPN.STATUS", '5');
//                $this->db->where('STATUS <>', '0');
//                $this->db->where('STATUS <>', '4');

                $this->db->where('T_LHKPN.ID_LHKPN IN (' . $this->data['id'] . ')');

                $query = $this->db->get('T_LHKPN');


                $this->data['item_selected'] = $query->result();
                $this->db->flush_cache();
                $this->db->start_cache();
            }

            $this->load->model('Mlhkpn');

            /**
            * @todo
            * INI BUAT PEMERIKSAAN
            */

            list($this->items, $this->total_rows) = $this->Mlhkpn->list_penugasan_pemeriksaan_lhkpn($this->CARI, $this->limit, $this->offset);

            $this->end = count($this->items);

            if (@$this->CARI['STAT']) {
                //do nothing
            } else {
                $this->CARI['STAT'] = 1;
                $this->data['CARI'] = @$this->CARI;
            }
            $this->data['urlEdit'] = 'index.php/eaudit/penugasan/edit';
            $this->data['title'] = 'Data LHKPN - Audit';
            $this->data['cari_stat'] = @$this->CARI['STAT'];

//            echo $this->db->show_compile_select();exit;

//            display($this->db->last_query());exit;
        } else if ($type == 'save') {
            $this->db->trans_begin();
            if ($this->input->post('act', TRUE) == 'doinsert') {
                $ID_LHKPN = explode(',', $this->input->post('id'));
                if (count($ID_LHKPN)) {
                    for ($i = 0; $i < count($ID_LHKPN); $i++) {
                        $id = $ID_LHKPN[$i];
                        // insert penugasan
                        $penugasan = array(
                            'ID_LHKPN' => $id,
                            'USERNAME' => $this->input->post('PETUGAS', TRUE),
                            'TANGGAL_PENUGASAN' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PENUGASAN', TRUE)))),
                            'DUE_DATE' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('DUE_DATE', TRUE)))),
                            // 'KETERANGAN'        => $this->input->post('KETERANGAN', TRUE),
                            'STAT' => 2,
                            'IS_ACTIVE' => 1,
                            // 'CREATED_TIME'     	=> time(),
                            // 'CREATED_BY'     	=> $this->session->userdata('USR'),
                            // 'CREATED_IP'     	=> $_SERVER["REMOTE_ADDR"],
                            'UPDATED_TIME' => time(),
                            'UPDATED_BY' => $this->session->userdata('USR'),
                            'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                        );
                        $exist = $this->mglobal->get_data_all('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', NULL, NULL, '*', "ID_LHKPN = '$id'");
                        if (!empty($exist)) {
                            $this->db->where('ID_LHKPN', $id);
                            $this->db->update('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', $penugasan);
                        } else {
                            $this->db->insert('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', $penugasan);
                        }
                        $this->db->flush_cache();
                        // set penerimaan as ditugaskan
                        // $penerimaan['STAT']    = 2;
                        // $this->db->where('ID_PENERIMAAN', $ID_PENERIMAAN);
                        // $this->db->update('T_LHKPNOFFLINE_PENERIMAAN', $penerimaan);
                        // History
                        $history = [
                            'ID_LHKPN' => $id,
                            'ID_STATUS' => 5,
                            'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                            'USERNAME_PENERIMA' => $this->input->post('PETUGAS', TRUE),
                            'DATE_INSERT' => date('Y-m-d H:i:s'),
                            'CREATED_IP' => $this->input->ip_address()
                        ];

                        $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                    }
                }
            } else if ($this->input->post('act', TRUE) == 'doupdate') {
                $penugasan = array(
                    'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                    'USERNAME' => $this->input->post('USERNAME', TRUE),
                    'TANGGAL_PENUGASAN' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TANGGAL_PENUGASAN', TRUE)))),
                    'DUE_DATE' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('DUE_DATE', TRUE)))),
                    // 'KETERANGAN'         => $this->input->post('KETERANGAN', TRUE),
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
                $this->db->update('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', $penugasan);
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
                $this->db->update('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', $penugasan);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            echo intval($this->db->trans_status());
        }
    }

    public function edit($id = '', $display = '') {
        $this->data['urlSave'] = site_url('ever/' . strtolower(__CLASS__) . '/' . 'lhkpn' . '/' . 'save');

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
        } else {
            $this->data['form'] = 'add';
            $this->data['act'] = 'doinsert';
        }
        $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', NULL, ['ID_TUGAS' => $id])[0] : '';

        // load view
        $mode = '';
        if ($display == 'printitem') {
            $html = $this->load->view($mode ? strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . $mode . '_form' : strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $this->data, true);
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
            $pdf->WriteHTML($html); // write the HTML into the PDF
            // $pdf->Output($pdfFilePath, 'F'); // save to file because we can
            $pdf->Output();
        } else {
            $this->load->view($mode ? strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . $mode . '_form' : strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $this->data);
        }
    }

    //show file
    public function show_file($folder = 'unknown', $nik = 'unknown', $namafile = '') {
        $data = array(
            'FOLDER' => $folder,
            'NIK' => $nik,
            'FILE' => $namafile,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_modal_file', $data);
    }

    function getInfoPn($id) {
        $this->load->model('mglobal');

        $joinpnwl = [['table' => 'T_PN', 'on' => 'T_USER.USERNAME = T_PN.NIK']];
        $wherepnwl = [
            'T_USER.USERNAME' => $this->session->userdata('USERNAME'),
            'T_PN.ID_PN' => $id,
        ];
        $joininst = [
            ['table' => 'T_PN', 'on' => 'T_USER.USERNAME = T_PN.NIK'],
            ['table' => 'T_PN_JABATAN', 'on' => 'T_PN.ID_PN = T_PN_JABATAN.ID_PN']
        ];
        $id_lembaga = $this->mglobal->get_data_all('T_USER', $joininst, ['T_USER.USERNAME' => $this->session->userdata('USERNAME'), 'T_PN_JABATAN.IS_CURRENT' => '1'], 'T_PN_JABATAN.LEMBAGA');

        $array = array();
        $where_e = '1=1';
        if ($id_lembaga != $array) {
            $where_e = '( ';
            foreach ($id_lembaga as $key) {
                $where_e .= 'T_PN_JABATAN.LEMBAGA = "' . $key->LEMBAGA . '" OR ';
            }
            $where_e = substr($where_e, 0, -4) . ')';
        } else {
            $where_e = '1=1';
        }

        $whereinst = [
            'IS_CURRENT' => '1'
        ];
        $rolesession = explode(',', $this->session->userdata('ID_ROLE'));

        $IS_KPK = 'no';
        $IS_INSTANSI = 'no';
        foreach ($rolesession as $key) {
            $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, NULL, 'IS_KPK,IS_INSTANSI,IS_USER_INSTANSI', "ID_ROLE= '" . $key . "'")[0];
            if ($role->IS_KPK == '1') {
                $IS_KPK = 'yes';
            }
            if ($role->IS_INSTANSI == '1' || $role->IS_USER_INSTANSI == '1') {
                $IS_INSTANSI = 'yes';
            }
        }

        $pnwl = @$this->mglobal->get_data_all('T_USER', $joinpnwl, $wherepnwl, 'T_USER.USERNAME')[0];
        $instansi = @$this->mglobal->get_data_all('T_USER', $joininst, $whereinst, 'T_PN.ID_PN', $where_e);

        //$execute no atau yes , adalah untuk menjalankan atau tidak menjalankan script nya , biar gak banyak banyak bikin script $temp , $data , sama load nya
        $execute = 'no';

        if ($IS_KPK == 'yes') {
            $execute = 'yes';
        } else {
            if (count($pnwl) > 0) {
                $execute = 'yes';
            }
            if (@in_array($id, @$this->objact(@$instansi)) && $IS_INSTANSI == 'yes') {
                $execute = 'yes';
            }
        }

        if ($execute == 'yes') {
            $tmp = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id], 'FOTO, NAMA, NIK, JNS_KEL, TEMPAT_LAHIR, TGL_LAHIR, NPWP, ALAMAT_TINGGAL, EMAIL, NO_HP')[0];

            $data['data'] = $tmp;
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_detail', $data);
        } else {
            echo 'Mohon Maaf , anda tidak memiliki izin untuk melihat data ini !!';
        }
    }

    public function objact($arrays) {
        $array = '';
        foreach ($arrays as $key => $value) {
            $array[] = $value->ID_PN;
        }
        return $array;
    }

}
