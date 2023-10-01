<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Filing extends MY_Controller {

    var $obj;
    var $iTotalRecords;
    var $iTotalDisplayRecords;
    var $iDisplayStart;
    var $iDisplayLength;
    var $iSortingCols;
    var $sSearch;
    var $sEcho;

    function __Construct() {
        parent::__Construct();
        call_user_func('ng::islogin');
        $this->load->model(array('mglobal', 'mlhkpn'));
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function staus_lhkpn($index) {
        $data = array();
        $data[0] = '<label class="label label-success">Draft</label>';
        $data[1] = '<label class="label label-primary">Terkirim</label>';
        $data[2] = '<label class="label label-danger">Perlu Perbaikan</label>';
        $data[3] = '<label class="label label-warning">Terverifikasi</label>';
        $data[4] = '<label class="label label-inverse">Diumumkan</label>';
        $data[5] = '<label class="label label-inverse">Terverifikasi Tidak Lengkap</label>';
        $data[6] = '<label class="label label-inverse">Diumumkan Tidak Lengkap</label>';
        return $data[$index];
    }

    function tipe_pelaporan($index) {
        $data = array();
        $data[1] = 'Khusus, Calon PN';
        $data[2] = 'Khusus, Awal Menjabat';
        $data[3] = 'Khusus, Akhir Menjabat';
        $data[4] = 'Periodik';
        return $data[$index];
    }

    function get_jabatan($id_pn) {
        $this->db->where('ID_PN', $id_pn);
        $this->db->where('t_pn_jabatan.IS_CURRENT', '1');
        $this->db->order_by('t_pn_jabatan.ID', 'DESC');
        $this->db->limit(1);
        $data = $this->db->get('t_pn_jabatan')->row();
        //header('Content-Type: application/json');
        //echo json_encode($data);
        return $data->DESKRIPSI_JABATAN;
    }

    function get_jabatan_all($id_lhkpn) {
        $this->db->where('t_lhkpn_jabatan.ID_LHKPN', $id_lhkpn);
        $this->db->order_by('t_lhkpn_jabatan.ID', 'DESC');
        $data = $this->db->get('t_lhkpn_jabatan')->result();
        //header('Content-Type: application/json');
        //echo json_encode($data);
        return $data;
    }

    /**
     * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
     */
    function TableFiling() {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        $this->load->model('mlhkpn');
        $record_set = $this->mlhkpn->get_table_filling($currentpage, $rowperpage);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($record_set->total_rows),
            "iTotalDisplayRecords" => intval($record_set->total_rows),
            "aaData" => $record_set->result
        );

        $this->to_json($dtable_output);
    }

    function test_add() {
        $this->load->view('filing/v_index/v_modal_buat_lhkpn_baru');
    }

    function index() {
        $this->load->model('mlhkpn');

        $ID_PN = $this->session->userdata('ID_PN');

        if($ID_PN){
            $DT_JAB = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $ID_PN], NULL, NULL, ['ID', 'DESC'])[0];
            // $DT_PN = $this->mglobal->get_data_all('t_pn', [['table' => 't_pn_jabatan', 'on' => 't_pn_jabatan.ID_PN = t_pn.ID_PN']], ['t_pn.ID_PN' => $ID_PN, 't_pn.IS_ACTIVE' => '1', 't_pn_jabatan.ID_STATUS_AKHIR_JABAT' => '0', 't_pn_jabatan.is_wl' => '1', 't_pn_jabatan.IS_CURRENT' => '1'], '*,t_pn.IS_ACTIVE as is_act', NULL, ['t_pn.ID_PN', 'DESC'])[0];
            $DT_PN = $this->mglobal->get_data_all('t_pn', [['table' => 't_pn_jabatan', 'on' => 't_pn_jabatan.ID_PN = t_pn.ID_PN']], ['t_pn.ID_PN' => $ID_PN, 't_pn.IS_ACTIVE' => '1', 't_pn_jabatan.ID_STATUS_AKHIR_JABAT' => '0', 't_pn_jabatan.IS_CURRENT' => '1', 't_pn_jabatan.IS_WL' => '1'], '*,t_pn.IS_ACTIVE as is_act', NULL, ['t_pn_jabatan.ID', 'DESC'])[0];
            
            //cek status draft
            $check_lhkpn_draft = $this->db->where("ID_PN = ". $ID_PN ." AND IS_ACTIVE = 1 AND JENIS_LAPORAN <> '5' AND entry_via = '0' AND (STATUS = '0' OR STATUS = '2')")->count_all_results("t_lhkpn");

            $history = $this->mlhkpn->get_by_id_pn($ID_PN);
            $history_limit = $this->mlhkpn->get_by_id_pn($ID_PN, true);

            $MAX_WL = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $ID_PN, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1', 'IS_WL' => '1'], 'TAHUN_WL', NULL, ['TAHUN_WL', 'DESC'])[0]->TAHUN_WL; 

            $CEK_WL_NOW = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $ID_PN, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1', 'ID_STATUS_AKHIR_JABAT' => '0', 'is_wl' => '1', 'tahun_wl' => date('Y')-1], 'TAHUN_WL')[0]->TAHUN_WL;

            $wl_tahun_now = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $ID_PN, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1', 'ID_STATUS_AKHIR_JABAT' => '0', 'is_wl' => '1', 'tahun_wl' => date('Y')], 'TAHUN_WL')[0];
            $wl_thn_minus_1 = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $ID_PN, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1', 'ID_STATUS_AKHIR_JABAT' => '0', 'is_wl' => '1', 'tahun_wl' => date('Y')-1], 'TAHUN_WL')[0];

        }else{
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , Silahkan hubungi Admin LHKPN di Instansi Anda.');
        }
        
        $count_elhkpn = 0;
        $count_jenis_laporan = 0;   //pengecekan data laporan Periodik / Khusus Awal Menjabat
        if(Count($history)==1){
            foreach($history as $h_state){
                if($h_state->STATUS== 1 || $h_state->STATUS==3 || $h_state->STATUS==4 || $h_state->STATUS==5 || $h_state->STATUS==6 || $h_state->STATUS==7){
                    // no condition
                }else{
                    $count_elhkpn++;
                }
            }
        }
        foreach($history as $his) {
            if($his->JENIS_LAPORAN == '2' || $his->JENIS_LAPORAN == '4') {
                $count_jenis_laporan++;
            }
        }
        if($history[0]->JENIS_LAPORAN == '3'){
            $count_jenis_laporan = 0;
        }

        $NOW = NULL;
        $LAST = NULL;
        $dateInput = NULL;
        $dateInput_1 = NULL;
        $dateInput_2 = NULL;
        $tahun_1 = "";
        $tahun_2 = "";
        $YEAR = array();
        if ($history) {
            if (count($history) > 1) {
                $NOW = $history[0]->ID_LHKPN;
                $LAST = $history[1]->ID_LHKPN;
                $dateInput_1 = explode('-', $history[0]->tgl_lapor);
                $dateInput_2 = explode('-', $history[1]->tgl_lapor);
                $tahun_1 = $dateInput_1[0];
                $tahun_2 = $dateInput_2[0];
            } else {
                $NOW = $history[0]->ID_LHKPN;
                $LAST = NULL;
                $dateInput = explode('-', $history[0]->tgl_lapor);
                $tahun_1 = $dateInput[0];
                $tahun_2 = "";
            }
        }

        $draft_num = $this->mlhkpn->count_select_draft_for_get_table_filling();

        $options = array(
            'title' => 'e-Filing',
            'HISTORY' => $history,
            'HISTORY_LIMIT'=> $history_limit,
            'count_elhkpn'=>$count_elhkpn,
            'draft_num' => (int) $draft_num,
            'NOW' => $NOW,
            'LAST' => $LAST,
            'YEAR' => $YEAR,
            'dateInput' => $dateInput,
            'dateInput_1' => $dateInput_1,
            'dateInput_2' => $dateInput_2,
            'tahun_1' => $tahun_1,
            'tahun_2' => $tahun_2,
            'STS_JAB' => $DT_PN->ID_STATUS_AKHIR_JABAT,
            'is_wl' => $DT_PN->is_wl,
            'is_active_pn' => $DT_PN->is_act,
            'check_lhkpn_draft' => $check_lhkpn_draft,
            'check_lhkpn_jenis' => $count_jenis_laporan,
            'max_tahun_wl' => $MAX_WL,
            'cek_wl_now' => $CEK_WL_NOW,
            'wl_tahun_now' => $wl_tahun_now ? 1 : 0,
            'wl_thn_minus_1' => $wl_thn_minus_1 ? 1 : 0,
        );


        $this->load->view('template/header', $options);
        $opt_js = $options;

        $options['v_modal_buat_lhkpn_baru'] = $this->load->view('filing/v_index/v_modal_buat_lhkpn_baru', $opt_js, TRUE);

        $options['js_page'][] = $this->load->view('filing/js/filling_index_js', $opt_js, TRUE);
        $options['js_page'][] = $this->load->view('filing/js/filling_index_chart_js', $opt_js, TRUE);
        $options['js_page'][] = $this->load->view('filing/js/filling_index_barchart1_js', $opt_js, TRUE);
        $options['js_page'][] = $this->load->view('filing/js/filling_index_barchart2_js', $opt_js, TRUE);
        $options['js_page'][] = $this->load->view('filing/js/filling_index_barchart3_js', $opt_js, TRUE);

        $this->load->view('filing/index', $options);
        $this->load->view('template/footer', $options);
    }

    function get($col, $table, $value, $return) {
        $result = FALSE;
        $this->db->like($col, $value);
        $this->db->limit(1);
        $data = $this->db->get($table)->row();
        if ($data) {
            $result = $data->$return;
        }
        return $result;
    }

    function UpdateJabatan($ID_LHKPN) {

        $ID_PN = $this->session->userdata('ID_PN');
        $this->db->select(
                't_pn_jabatan.*,
			m_inst_satker.INST_SATKERKD,
			m_inst_satker.INST_NAMA,
			m_unit_kerja.UK_ID,
			m_unit_kerja.UK_NAMA,
			m_sub_unit_kerja.SUK_ID,
			m_sub_unit_kerja.SUK_NAMA,
			m_eselon.*'
        );
        $this->db->where('t_pn_jabatan.IS_CURRENT', 1);
        $this->db->where('t_pn_jabatan.IS_ACTIVE', 1);
        $this->db->where('t_pn_jabatan.IS_DELETED', 0);
        $this->db->where('t_pn_jabatan.ID_STATUS_AKHIR_JABAT', 0);
        $this->db->where('t_pn_jabatan.ID_STATUS_AKHIR_JABAT <>', 1);
        $this->db->where('t_pn_jabatan.ID_PN', $ID_PN);
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_pn_jabatan.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = m_jabatan.UK_ID', 'left');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_ID = m_jabatan.SUK_ID', 'left');
        $this->db->join('m_eselon', 'm_eselon.ID_ESELON = m_jabatan.KODE_ESELON', 'LEFT');
        $this->db->group_by('t_pn_jabatan.TAHUN_WL');
        $this->db->order_by('t_pn_jabatan.TAHUN_WL','desc');
        $this->db->limit(1);
        $PN_JABATAN = $this->db->get('t_pn_jabatan')->result();

        $is_primary = $ROW->IS_PRIMARY;
        if (count($PN_JABATAN) == 1) {
            $is_primary = '1';
        } else {
            $is_primary;
        }

        if ($PN_JABATAN) {
            foreach ($PN_JABATAN AS $ROW) {
                $add_new = array(
                    'ID_JABATAN' => $ROW->ID_JABATAN,
                    'ID_LHKPN' => $ID_LHKPN,
                    'DESKRIPSI_JABATAN' => $ROW->DESKRIPSI_JABATAN,
                    'ESELON' => $ROW->ID_ESELON,
                    'LEMBAGA' => $ROW->LEMBAGA,
                    'UNIT_KERJA' => $ROW->UK_ID,
                    'SUB_UNIT_KERJA' => $ROW->SUK_ID,
                    'TMT' => $ROW->TMT,
                    'SD' => $ROW->SD,
                    'ALAMAT_KANTOR' => $ROW->ALAMAT_KANTOR,
                    'EMAIL_KANTOR' => $ROW->EMAIL_KANTOR,
                    'FILE_SK' => $ROW->FILE_SK,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('NAMA'),
                    'CREATED_IP' => get_client_ip(),
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                    'UPDATED_IP' => get_client_ip(),
                    'ID_STATUS_AKHIR_JABAT' => $ROW->ID_STATUS_AKHIR_JABAT,
                    'IS_PRIMARY' => $is_primary,
                );

                $this->db->insert('t_lhkpn_jabatan', $add_new);
            }
        }
    }

// insert data pribadi untuk laporan kedua

    function UpdateDataPribadi($ID_LHKPN) {


        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
        // $this->db->where('JENIS_LAPORAN <>', $ID_LHKPN);
        $lhkpn = $this->db->get('t_lhkpn')->row();
        $ID_PN = $this->session->userdata('ID_PN');

        if ($lhkpn->IS_COPY == '1') {
            $this->db->select('t_lhkpn_data_pribadi.STORAGE_MINIO as storage_data_pribadi, t_lhkpn_data_pribadi.*, t_lhkpn.*');
            $this->db->where('t_lhkpn.ID_PN', $ID_PN);
            $this->db->where('t_lhkpn.ID_LHKPN !=', $ID_LHKPN);
            $this->db->where('t_lhkpn.IS_ACTIVE', '1');
            $this->db->where('t_lhkpn.STATUS !=', '0');
            $this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
            // $this->db->where('t_lhkpn.JENIS_LAPORAN <>', '5');
            $this->db->order_by('t_lhkpn.ID_LHKPN', 'DESC');
            $this->db->limit(1);
            $this->db->join('t_lhkpn', 't_lhkpn_data_pribadi.ID_LHKPN = t_lhkpn.ID_LHKPN');
            $pn = $this->db->get('t_lhkpn_data_pribadi')->row();

            /**
             * add by eko
             * ambil NIK dari t_pn
             */
            $ID_PN_nik = $this->session->userdata('ID_PN');
            $this->db->where('ID_PN', $ID_PN_nik);
            $pn_nik = $this->db->get('t_pn')->row();

            $new = array(
                'ID_LHKPN' => $ID_LHKPN,
                'GELAR_DEPAN' => $pn->GELAR_DEPAN,
                'GELAR_BELAKANG' => $pn->GELAR_BELAKANG,
                'NAMA_LENGKAP' => $pn->NAMA_LENGKAP,
                'JENIS_KELAMIN' => $pn->JENIS_KELAMIN,
                'TEMPAT_LAHIR' => $pn->TEMPAT_LAHIR,
                'TANGGAL_LAHIR' => $pn->TANGGAL_LAHIR,
                'STATUS_PERKAWINAN' => $pn->STATUS_PERKAWINAN,
                'NIK' => $pn_nik->NIK,
                'NIP' => $pn->NIP,
                'NPWP' => $pn->NPWP,
                'AGAMA' => $pn->AGAMA,
                'ALAMAT_RUMAH' => $pn->ALAMAT_RUMAH,
                'EMAIL_PRIBADI' => $pn->EMAIL_PRIBADI,
                'PROVINSI' => $pn->PROVINSI,
                'KABKOT' => $pn->KABKOT,
                'KECAMATAN' => $pn->KECAMATAN,
                'KELURAHAN' => $pn->KELURAHAN,
                'HP' => $pn->HP,
                'HP_LAINNYA' => $pn->HP_LAINNYA,
                'FOTO' => $pn->FOTO,
                'IS_ACTIVE' => 1,
                'CREATED_TIME' => date("Y-m-d H:i:s"),
                'CREATED_BY' => $this->session->userdata('NAMA'),
                'CREATED_IP' => get_client_ip(),
                'KD_ISO3_NEGARA' => $pn->KD_ISO3_NEGARA,
                'NEGARA' => $pn->NEGARA,
                'ALAMAT_NEGARA' => $pn->ALAMAT_NEGARA,
                'FLAG_SK' => $pn->FLAG_SK,
                'NO_KK' => $pn->NO_KK,
                'STORAGE_MINIO' => $pn->storage_data_pribadi
            );
        } else {
            $ID_PN = $this->session->userdata('ID_PN');
            $this->db->where('ID_PN', $ID_PN);
            $pn = $this->db->get('t_pn')->row();

            if ($pn->NEGARA == 'INDONESIA') {
                $negara = 1;
            } else {
                $negara = 2;
            }

            $new = array(
                'ID_LHKPN' => $ID_LHKPN,
                'GELAR_DEPAN' => $pn->GELAR_DEPAN,
                'GELAR_BELAKANG' => $pn->GELAR_BELAKANG,
                'NAMA_LENGKAP' => $pn->NAMA,
                'JENIS_KELAMIN' => $pn->JNS_KEL,
                'TEMPAT_LAHIR' => $pn->TEMPAT_LAHIR,
                'TANGGAL_LAHIR' => $pn->TGL_LAHIR,
                'STATUS_PERKAWINAN' => $this->get('ID_STATUS', 'm_status_nikah', $pn->ID_STATUS_NIKAH, 'STATUS_NIKAH'),
                'NIK' => $pn->NIK,
                'NIP' => $pn->NIP_NRP,
                'NPWP' => $pn->NPWP,
                'AGAMA' => $this->get('ID_AGAMA', 'm_agama', $pn->ID_AGAMA, 'AGAMA'),
                'ALAMAT_RUMAH' => $pn->ALAMAT_TINGGAL,
                'EMAIL_PRIBADI' => $pn->EMAIL,
                'PROVINSI' => $pn->PROV,
                'KABKOT' => $pn->KAB_KOT,
                'KECAMATAN' => $pn->KEC,
                'KELURAHAN' => $pn->KEL,
                'HP' => $pn->NO_HP,
                'HP_LAINNYA' => $pn->NO_HP,
                'FOTO' => $pn->FOTO,
                'IS_ACTIVE' => 1,
                'CREATED_TIME' => date("Y-m-d H:i:s"),
                'CREATED_BY' => $this->session->userdata('NAMA'),
                'CREATED_IP' => get_client_ip(),
                'KD_ISO3_NEGARA' => $this->get('NAMA_NEGARA', 'm_negara', $pn->NEGARA, 'KODE_ISO3'),
                'NEGARA' => $pn->NEGARA,
                'ALAMAT_NEGARA' => $pn->LOKASI_NEGARA,
            );
        }

        $this->db->insert('t_lhkpn_data_pribadi', $new);
    }

    function add() {
//        if ((int) $this->mlhkpn->count_select_for_get_table_filling() > 0) {
//            redirect('portal/filing');
//        }
        if ($_POST) {
            $ID_PN = $this->session->userdata('ID_PN');
            $jenis_laporan = $this->input->post('jenis_laporan');
//            $tahun_pelaporan = $this->input->post('tahun_pelaporan');
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

//            $this->db->where('ID_PN', $ID_PN);
//            $this->db->where('TGL_LAPOR >=', $start);
//            $this->db->where('TGL_LAPOR <=', $end);
//            $this->db->where('IS_ACTIVE', 1);
//            $this->db->where('JENIS_LAPORAN', 4);
//            $check = $this->db->get('t_lhkpn')->result();

            $this->load->model('mlhkpn');
            $check = $this->mlhkpn->get_lhkpn_pn_by_id_pn($ID_PN, 4, array(
                "TGL_LAPOR >=" => $start,
                "TGL_LAPOR <=" => $end,
            ));
//            var_dump($check);exit;



            if ($jenis_laporan == 4 && $check) {
                $this->session->set_flashdata('error_message', 'error_message');
                $this->session->set_flashdata('message', 'Mohon Maaf , Data LHKPN Tahun <b>' . $tahun_pelaporan . '</b> sudah ada !! ');
                redirect('portal/filing');
            } else {
                $jenis = $this->input->post('jenis_laporan');


                $JENIS_LAPOR = NULL;
                $TGL_LAPOR = NULL;
                if ($jenis == '4') { // PERIODIK
                    $JENIS_LAPOR = 4;
                    $TGL_LAPOR = $year . '-12-31';
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
                    }
                }

                $IS_COPY = '0';
                $A = explode('-', $TGL_LAPOR);
                $B = $A[0];
                $DATE_REPORT = $A[0] . '-12-31';
//                $this->db->where('ID_PN', $ID_PN);
//                $this->db->where('TGL_LAPOR <', $DATE_REPORT);
//                $this->db->limit(1);
//                $this->db->order_by('TGL_LAPOR', 'DESC');
//                $check_exist = $this->mlhkpn->get_lhkpn_pn_by_id_pn($ID_PN, FALSE, array(), 'row');
//                $check_exist = $this->db->get('t_lhkpn')->row();

                $check_exist = $this->mlhkpn->get_lhkpn_pn_by_id_pn($ID_PN, FALSE, array(
                    "TGL_LAPOR < " => "'".$DATE_REPORT."'",
//                    'CASE WHEN JENIS_LAPORAN = 5 THEN STATUS > \'2\' ELSE TRUE END' => NULL
                     "JENIS_LAPORAN <> " => '5',
                     "STATUS <> " => "'7'"
                        ), 1, 'TGL_LAPOR DESC', TRUE);
                if ($check_exist) {
                    $IS_COPY = '1';
                    $ID_LHKPN_PREV = $check_exist->ID_LHKPN;
                }


                $lhkpn = array(
                    'JENIS_LAPORAN' => $JENIS_LAPOR,
                    'ID_PN' => $this->session->userdata('ID_PN'),
                    'TGL_LAPOR' => $TGL_LAPOR,
                    'TGL_KIRIM' => date('Y-m-d'),
                    'ENTRY_VIA' => '0',
                    'STATUS' => '0',
                    'IS_ACTIVE' => '1',
                    'IS_COPY' => $IS_COPY,
                    'STATUS_PERBAIKAN_NASKAH' => '0',
                    'STATUS_CETAK_SURAT_KUASA' => $check_exist->STATUS_CETAK_SURAT_KUASA,
                    'CETAK_SURAT_KUASA_TIME' => $check_exist->CETAK_SURAT_KUASA_TIME,
                    'SURAT_KUASA' => $check_exist->SURAT_KUASA,
                    'STATUS_SURAT_UMUMKAN' => $check_exist->STATUS_SURAT_UMUMKAN,
                    'CETAK_SURAT_UMUMKAN_TIME' => $check_exist->CETAK_SURAT_UMUMKAN_TIME,
                    'SURAT_UMUMKAN' => $check_exist->SURAT_UMUMKAN,
                    'ID_LHKPN_PREV' => $ID_LHKPN_PREV != NULL || $ID_LHKPN_PREV != '' ? $ID_LHKPN_PREV : NULL,
                    'USERNAME_ENTRI' => $this->session->userdata('NAMA'),
                    'CREATED_TIME' => date('Y-m-d'),
                    'CREATED_IP' => $this->get_client_ip(),
                    'FILE_BUKTI_SKM' => $check_exist->FILE_BUKTI_SKM,
                    'FILE_BUKTI_SK' => $check_exist->FILE_BUKTI_SK
                );
                if ($is_update == 'update') {
                    $get_id_pn = $this->session->userdata['ID_PN'];
                    $sql_check_2 = "SELECT * FROM t_lhkpn WHERE ID_PN = ".$get_id_pn." AND tgl_lapor >= '".$TGL_LAPOR."' AND ID_LHKPN != ".$id_lhkpn." AND IS_ACTIVE = 1";
                    $act_check_2 = $this->db->query($sql_check_2)->result();
                    if ($act_check_2) {
                        $this->session->set_flashdata('error_message', 'error_message');
                        $this->session->set_flashdata('message', 'Mohon Maaf , Tanggal Pelaporan Harus Lebih Besar dari Tanggal Pelaporan Terakhir !! ');
                        redirect('portal/filing');
                    }


                    if (($lhkpn['JENIS_LAPORAN'] == NULL || $lhkpn['JENIS_LAPORAN'] == '') || ($lhkpn['TGL_LAPOR'] == NULL || $lhkpn['TGL_LAPOR'] == '' || $lhkpn['TGL_LAPOR'] == '1970-01-01')){
                        $this->session->set_flashdata('error_message', 'error_message');
                        $this->session->set_flashdata('message', 'Mohon Maaf , Jenis Laporan atau Tanggal/Tahun Laporan tidak boleh kosong !! ');
                        redirect('portal/filing');
                    }else{
                        $create_username = $this->session->userdata('NAMA');
                        $created_time = date('Y-m-d h:i:s');
                        $created_ip = $this->input->ip_address();

                        $this->db->where('ID_LHKPN', $id_lhkpn);
                        $this->db->update('t_lhkpn', array(
                            'JENIS_LAPORAN' => $JENIS_LAPOR,
                            'TGL_LAPOR' => $TGL_LAPOR,
                            'UPDATED_TIME' => $created_time,
                            'UPDATED_BY' => $create_username,
                            'UPDATED_IP' => $created_ip
                        ));
                        redirect('portal/filing');
                    }
                } else {
                    $get_id_pn = $this->session->userdata['ID_PN'];
                    $sql_check_2 = "SELECT * FROM t_lhkpn WHERE ID_PN = ".$get_id_pn." AND tgl_lapor >= '".$TGL_LAPOR."'  AND IS_ACTIVE = 1";
                    $act_check_2 = $this->db->query($sql_check_2)->result();
                    if ($act_check_2) {
                        $this->session->set_flashdata('error_message', 'error_message');
                        $this->session->set_flashdata('message', 'Mohon Maaf , Tanggal Pelaporan Harus Lebih Besar dari Tanggal Pelaporan Terakhir !! ');
                        redirect('portal/filing');
                    }
                    if (($lhkpn['JENIS_LAPORAN'] == NULL || $lhkpn['JENIS_LAPORAN'] == '') || ($lhkpn['TGL_LAPOR'] == NULL || $lhkpn['TGL_LAPOR'] == '' || $lhkpn['TGL_LAPOR'] == '1970-01-01')){
                        $this->session->set_flashdata('error_message', 'error_message');
                        $this->session->set_flashdata('message', 'Mohon Maaf , Jenis Laporan atau Tanggal/Tahun Laporan tidak boleh kosong !! ');
                        redirect('portal/filing');
                    }else{
                        $this->db->insert('t_lhkpn', $lhkpn);
                        $ID_LHKPN_NEW = $this->db->insert_id();
                    }


                    /*
                     * add by eko
                     * insert status draft ke history status lhkpn
                     */
                    if ($ID_LHKPN_NEW) {
                        $history = [
                            'ID_LHKPN' => $ID_LHKPN_NEW,
                            'ID_STATUS' => 1,
                            'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                            'USERNAME_PENERIMA' => '',
                            'DATE_INSERT' => date('Y-m-d H:i:s'),
                            'CREATED_IP' => $this->input->ip_address()
                        ];

                        $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                    }

                    if ($check_exist) {
                        // UPDATE KELUARGA
                        $this->db->where('ID_LHKPN', $ID_LHKPN_PREV);
                        $this->db->where('IS_ACTIVE', 1);
                        $keluarga = $this->db->get('t_lhkpn_keluarga')->result();
                        if ($keluarga) {
                            foreach ($keluarga as $kl) {
                                $arr_keluarga = array(
                                    'ID_KELUARGA_LAMA' => $kl->ID_KELUARGA,
                                    'ID_LHKPN' => $ID_LHKPN_NEW,
                                    'NIK' => $kl->NIK,
                                    'NAMA' => $kl->NAMA,
                                    'HUBUNGAN' => $kl->HUBUNGAN,
                                    'STATUS_HUBUNGAN' => $kl->STATUS_HUBUNGAN,
                                    'TEMPAT_LAHIR' => $kl->TEMPAT_LAHIR,
                                    'TANGGAL_LAHIR' => $kl->TANGGAL_LAHIR,
                                    'JENIS_KELAMIN' => $kl->JENIS_KELAMIN,
                                    'TEMPAT_NIKAH' => $kl->TEMPAT_NIKAH,
                                    'TANGGAL_NIKAH' => $kl->TANGGAL_NIKAH,
                                    'TEMPAT_CERAI' => $kl->TEMPAT_CERAI,
                                    'TANGGAL_CERAI' => $kl->TANGGAL_CERAI,
                                    'PEKERJAAN' => $kl->PEKERJAAN,
                                    'ALAMAT_RUMAH' => $kl->ALAMAT_RUMAH,
                                    'NOMOR_TELPON' => $kl->NOMOR_TELPON,
                                    'IS_ACTIVE' => 1,
                                    'STATUS_CETAK_SURAT_KUASA' => $kl->STATUS_CETAK_SURAT_KUASA,
                                    'CETAK_SURAT_KUASA_TIME' => $kl->CETAK_SURAT_KUASA_TIME,
                                    'SURAT_KUASA' => $kl->SURAT_KUASA,
                                    'CREATED_TIME' => time(),
                                    'CREATED_BY' => $this->session->userdata('NAMA'),
                                    'CREATED_IP' => get_client_ip(),
                                    'UPDATED_TIME' => time(),
                                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                                    'UPDATED_IP' => get_client_ip(),
                                    'FLAG_SK' => $kl->FLAG_SK,
                                );
                                $this->db->insert('t_lhkpn_keluarga', $arr_keluarga);
                            }
                        }

                        // UPDATE FASILITAS
                        $this->db->where('ID_LHKPN', $ID_LHKPN_PREV);
                        $fasilitas = $this->db->get('t_lhkpn_fasilitas')->result();
                        if ($fasilitas) {
                            foreach ($fasilitas as $fl) {
                                $arr_fasilitas = array(
                                    'ID_LHKPN' => $ID_LHKPN_NEW,
                                    'JENIS_FASILITAS' => $fl->JENIS_FASILITAS,
                                    'NAMA_FASILITAS' => $fl->NAMA_FASILITAS,
                                    'PEMBERI_FASILITAS' => $fl->PEMBERI_FASILITAS,
                                    'KETERANGAN' => $fl->KETERANGAN,
                                    'KETERANGAN_LAIN' => $fl->KETERANGAN_LAIN,
                                    'IS_ACTIVE' => 1,
                                    'CREATED_TIME' => time(),
                                    'CREATED_BY' => $this->session->userdata('NAMA'),
                                    'CREATED_IP' => get_client_ip(),
                                    'UPDATED_TIME' => time(),
                                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                                    'UPDATED_IP' => get_client_ip(),
                                );
                                $this->db->insert('t_lhkpn_fasilitas', $arr_fasilitas);
                            }
                        }
                    } else {
                        $this->loadwskeluarga($ID_LHKPN_NEW);
                    }

                    $this->UpdateDataPribadi($ID_LHKPN_NEW);
                    $this->loadAdminduk($ID_LHKPN_NEW);
                    $this->UpdateJabatan($ID_LHKPN_NEW);
                    redirect('portal/filing/entry/' . $ID_LHKPN_NEW);
                }
            }
        } else {
            redirect('portal/filing');
        }
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

    function loadAdminduk($ID_LHKPN_NEW) {
        $dt_ws = 'http://localhost/lhkpn/file/json/pn_pribadi.json';
        $json = file_get_contents($dt_ws);
        $json_pn = json_decode($json, true);
        $NIK_LOG = $this->session->userdata('NIK');

        if ($json_pn) {
            foreach ($json_pn as $pn_js) {
                if ($pn_js['NIK'] == $NIK_LOG) {
                    $arr_pn = array(
                        'NAMA_LENGKAP' => $pn_js['nama_lengkap'],
                        'AGAMA' => $pn_js['Agama'],
                        'KK' => $pn_js['KK'],
                        'TEMPAT_LAHIR' => $pn_js['tempat_lahir'],
                        'TANGGAL_LAHIR' => $pn_js['tanggal_lahir'],
                        'JENIS_KELAMIN' => $pn_js['jenis_kelamin'],
                        'NEGARA' => $pn_js['Negara'],
                        'PROVINSI' => $pn_js['provinsi'],
                        'KABKOT' => $pn_js['kabupaten_kota'],
                        'KECAMATAN' => $pn_js['kecamatan'],
                        'KELURAHAN' => $pn_js['kelurahan_desa'],
                        'ALAMAT_RUMAH' => $pn_js['alamat_rumah']
                    );
                    $this->db->where('ID_LHKPN', $ID_LHKPN_NEW);
                    $this->db->update('t_lhkpn_data_pribadi', $arr_pn);
                }
            }
        }
    }

    private function __entry_get_last_id_and_year_lhkpn($id, $id_pn) {

        $lhkpn_pn = $this->mlhkpn->get_lhkpn_pn($id, $id_pn);
        $LAST_ID_LHKPN = 0;

        $LAST_YEARS = NULL;
        if ($lhkpn_pn) {
            $LAST_ID_LHKPN = $lhkpn_pn->ID_LHKPN;
            if ($lhkpn_pn->tgl_lapor) {
                $LAST_YEARS = substr($lhkpn_pn->tgl_lapor, 0, 4);
            }
        }

        unset($lhkpn_pn);

        return [$LAST_ID_LHKPN, $LAST_YEARS];
    }

    function entry($id = NULL, $on_ajax = FALSE) { 
        if ($id) {

            $this->load->model('mjenispelepasanharta');
            date_default_timezone_set('Asia/Jakarta');
            $ID_PN = $this->session->userdata('ID_PN');

            $check = $this->mlhkpn->get_detail_by_pn($id, $ID_PN);

            $datapn = $this->mglobal->get_detail_pn_lhkpn($id,TRUE,FALSE);

            //cek status draft
            $check_lhkpn_draft = $this->db->where("ID_PN = ". $ID_PN ." AND IS_ACTIVE = 1 AND JENIS_LAPORAN <> '5' AND entry_via = '0' AND (STATUS = '0' OR STATUS = '2')")->count_all_results("t_lhkpn");

            //cek status laporan sebelumnya
            $this->db->select('STATUS, TGL_LAPOR');
            $this->db->where("ID_LHKPN = ". $datapn->ID_LHKPN_PREV);
            $check_status_lhkpn_prev = $this->db->get('T_LHKPN')->row();
            $TGL_LAPOR_LHKPN_PREV = tgl_format($check_status_lhkpn_prev->TGL_LAPOR);
            
            //============================================================
            //Rian Ipdate
            $this->db->select('*');
            $this->db->where('ID_LHKPN', $id);
            $datapribadi = $this->db->get('t_lhkpn_data_pribadi')->result();
            if ($datapribadi) {
                foreach ($datapribadi AS $ROW) {
                    $alamatrumah = $ROW->ALAMAT_RUMAH;
                }
            }

            //echo $this->db->last_query();exit;
            //End
            //============================================================

            list($LAST_ID_LHKPN, $LAST_YEARS) = $this->__entry_get_last_id_and_year_lhkpn($id, $ID_PN);


            $kuasa = $this->mlhkpn->cetak_surat_kuasa_time_by_pn($ID_PN);

            $IS_PRINT = 0;
            $NOW_YEARS = substr($check->tgl_lapor, 0, 4);

            $date1 = $kuasa->CETAK_SURAT_KUASA_TIME;

            unset($kuasa);

            $date2 = date('Y-m-d H:i:s');
            $diff = abs(strtotime($date2) - strtotime($date1));
            $years = floor($diff / (365 * 60 * 60 * 24));

            if ((int) $years > 5) {
                $IS_PRINT = 0;
            } else {
                $IS_PRINT = $check->STATUS_CETAK_SURAT_KUASA;
            }

            if ($check) {
                if($check->IS_ACTIVE==0 || $check->IS_ACTIVE==-1){
                     redirect('portal/filing');
                }
                $options = array(
                    'title' => 'e-Filing',
                    'ID_LHKPN' => $id,
                    'LAST_ID_LHKPN' => $LAST_ID_LHKPN,
                    'IS_COPY' => $check->IS_COPY,
                    'VIA_VIA' => $check->entry_via,
                    'IS_PRINT' => $IS_PRINT,
                    'NOW_YEARS' => $NOW_YEARS,
                    'LAST_YEARS' => $LAST_YEARS,
                    'TGL_LAPOR' => $check->tgl_lapor,
                    'STATUS' => $check->STATUS,
                    'TOKEN_PENGIRIMAN' => $check->TOKEN_PENGIRIMAN,
                    'USERNAME' => $this->session->userdata('USERNAME'),
                    'JENIS_PELEPASAN_HARTA' => $this->mjenispelepasanharta->get_all(),
                    'JENIS_PELAPORAN' => $check->JENIS_LAPORAN,
                    'NO_HP' => $datapn->HP,
                    'EMAIL' => $datapn->EMAIL_PRIBADI,
                    'skip_to_review_harta' => FALSE,
                    'alamatrumah' => $alamatrumah,
                    'check_lhkpn_draft' => $check_lhkpn_draft,
                    'STATUS_LHKPN_PREV' => $check_status_lhkpn_prev->STATUS,
                    'TGL_LAPOR_LHKPN_PREV' => $TGL_LAPOR_LHKPN_PREV,
                );
                $skip_to_review_harta = $this->input->get('strh');

                if ($skip_to_review_harta && $skip_to_review_harta = 'ok_do_it') {
                    $options['skip_to_review_harta'] = TRUE;
                }

                if ($on_ajax !== FALSE) {
                    $arr_return = array("status" => 1, "msg" => "mess");
                    echo json_encode($arr_return);
                    exit;
                }

                $view = $this->load->view('template/header', $options, TRUE);
                $view .= $this->load->view('filing/main', $options, TRUE);
                $view .= $this->load->view('filing/v_index/v_include_js', $options, TRUE);
                $view .= $this->load->view('template/footer', $options, TRUE);

                echo $view;
                exit;
            } else {
                
                redirect('portal/filing');
            }
        }
        
        if ($on_ajax !== FALSE) {
            $arr_return = array("status" => 0, "msg" => "mess");
            echo json_encode($arr_return);
            exit;
        } else {
            redirect('portal/filing');
        }
    }

    function add_old() {
        $options = array(
            'title' => 'e-Filing'
        );
        $this->load->view('template/header', $options);
        $this->load->view('filing/add_old', $options);
        $this->load->view('template/footer', $options);
    }

    function GetNegara() {
        $key = $this->input->post_get('q');
        $this->db->limit(10);
        $this->db->where('ID !=', '2');
        $this->db->like('NAMA_NEGARA', $key);
        $this->db->order_by('NAMA_NEGARA', 'ASC');
        $result = $this->db->get('m_negara')->result();
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->KODE_ISO3,
                'text' => $row->NAMA_NEGARA
            );
        }
        header('Content-type: application/json');
        echo json_encode($array);
    }

    /**
     * @uses Model marea_prov
     */
    public function GetProvinsi() {
        $key = $this->input->post_get('q');
        $this->db->limit(10);
        $this->db->like('NAME', $key);
//        $this->db->order_by('NAME', 'ASC');
        $this->db->where('IS_ACTIVE', '1');
        $result = $this->db->get('m_area_prov')->result();
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->ID_PROV,
                'text' => $row->NAME
            );
        }
//        $limit = $this->input->post_get('pageLimit');
//        $page = $this->input->post_get('page');
//        $offset = ($page * $limit) - $limit;
//        $this->load->model('marea_prov');

        /**
         * pageLimit:10
          page:1
         */
//        $array = $this->marea_prov->get_select2_by_keyword($key, $limit, $offset);

        header('Content-type: application/json');
        echo json_encode($array);
    }

    public function GetProvinsi_old() {
        $key = $this->input->post_get('q');
        $limit = $this->input->post_get('pageLimit');
        $page = $this->input->post_get('page');

        $offset = ($page * $limit) - $limit;

        $this->load->model('marea_prov');

        /**
         * pageLimit:10
          page:1
         */
        $array = $this->marea_prov->get_select2_by_keyword($key, $limit, $offset);

        header('Content-type: application/json');
        echo json_encode($array);
    }

    /**
     * @deprecated since 12 January 2017 by Lahir Wisada Santoso
     * @see $this GetProvinsi
     */
    function GetProvinsi_deprecated() {
        $key = $this->input->post_get('q');
        $this->db->limit(10);
        $this->db->like('NAME', $key);
        $this->db->order_by('NAME', 'ASC');

        $result = $this->db->get('m_area_prov')->result();
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->ID_PROV,
                'text' => $row->NAME
            );
        }
        header('Content-type: application/json');
        echo json_encode($array);
    }

    function GetKota($ID_PROPINSI) {
        $key = $this->input->post_get('q');
        $this->db->where('ID_PROV', $ID_PROPINSI);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->limit(10);
        $this->db->like('NAME_KAB', $key);
        $this->db->order_by('NAME_KAB', 'ASC');
        $result = $this->db->get('m_area_kab')->result();
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->ID_KAB,
                'text' => $row->NAME_KAB
            );
        }
        header('Content-type: application/json');
        echo json_encode($array);
    }

    /* function GetKecamatan($ID_PROPINSI,$ID_KOTA){
      $key = $this->input->post_get('q');
      $this->db->where('LEVEL','3');
      $this->db->where('IDPROV',$ID_PROPINSI);
      $this->db->where('IDKOT',$ID_KOTA);
      $this->db->limit(10);
      $this->db->like('NAME',$key);
      $this->db->order_by('NAME','ASC');
      $result = $this->db->get('m_area')->result();
      $array = array();
      foreach($result as $row){
      $array[] = array(
      'id'=>$row->IDKEC,
      'text'=>$row->NAME
      );
      }
      header('Content-type: application/json');
      echo json_encode($array);
      } */

    /* function GetKelurahan($ID_KOTA,$ID_KECAMATAN){
      $key = $this->input->post_get('q');
      $this->db->where('LEVEL','4');
      $this->db->where('IDKOT',$ID_KOTA);
      $this->db->where('IDKEC',$ID_KECAMATAN);
      $this->db->limit(10);
      $this->db->like('NAME',$key);
      $this->db->order_by('NAME','ASC');
      $result = $this->db->get('m_area')->result();
      $array = array();
      foreach($result as $row){
      $array[] = array(
      'id'=>$row->IDKEL,
      'text'=>$row->NAME
      );
      }
      header('Content-type: application/json');
      echo json_encode($array);
      } */

    function CekWaktuLaporan($type, $value) {
        $data = array();
        $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN');
        if ($type == 'date') {
            $date = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
            $this->db->where('TGL_LAPOR', $date);
        } else {
            $date = date('Y', strtotime(str_replace('/', '-', $value)));
            $this->db->like('TGL_LAPOR', $date);
        }
        $this->db->where('t_pn.ID_PN', $this->session->userdata('ID_PN'));
        $data = $this->db->get('t_lhkpn')->result();
        header('Content-type: application/html');
        if ($data) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function delete($ID_LHKPN) {
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where('ID_PN', $this->session->userdata('ID_PN'));
        $cek = $this->db->update('t_lhkpn', array('IS_ACTIVE' => '0'));
        if ($cek) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function notif($index) {
        $data = array();
        $data[0] = '';
        $data[1] = '';
        $data[2] = '';
        $data[3] = '';
        $data[4] = '';
        $data[5] = '';
        $data[6] = '';
        $data[7] = '';
        $data[8] = '';
        $data[9] = '';
        $data[10] = '';
        $data[11] = '';
        $data[12] = '';
        return $data[$index];
    }

    function CheckData($ID_LHKPN) {

        $modul = array();

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $modul[0] = $this->db->get('t_lhkpn_data_pribadi')->row();

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $modul[1] = $this->db->get('t_lhkpn_jabatan')->result();

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $modul[2] = $this->db->get('t_lhkpn_keluarga')->result();

        $harta = array();
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $harta[0] = $this->db->get('t_lhkpn_harta_tidak_bergerak')->result();

        $this->db->where('t_lhkpn_harta_bergerak.ID_LHKPN', $ID_LHKPN);
        $harta[1] = $this->db->get('t_lhkpn_harta_bergerak')->result();

        $this->db->where('t_lhkpn_harta_bergerak_lain.ID_LHKPN', $ID_LHKPN);
        $harta[2] = $this->db->get('t_lhkpn_harta_bergerak_lain')->result();

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $harta[3] = $this->db->get('t_lhkpn_harta_kas')->result();

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $harta[4] = $this->db->get('t_lhkpn_harta_lainnya')->result();

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $harta[5] = $this->db->get('t_lhkpn_harta_surat_berharga')->result();

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $harta[6] = $this->db->get('t_lhkpn_hutang')->result();

        $v_harta = 0;
        for ($i = 0; $i < count($harta); $i++) {
            if ($harta[$i]) {
                $v_harta++;
            } else {
                $v_harta--;
            }
        }

        if ($v_harta > 0) {
            $modul[3] = 1;
        } else {
            $modul[3] = 0;
        }

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $modul[4] = $this->db->get('t_lhkpn_penerimaan_kas')->result();

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $modul[5] = $this->db->get('t_lhkpn_pengeluaran_kas')->result();

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $modul[6] = $this->db->get('t_lhkpn_fasilitas')->result();

        $result = array();
        $i = 0;
        foreach ($modul as $m) {
            if ($m) {
                $result[$i] = '1';
            } else {
                $result[$i] = '0';
            }
            $i++;
        }
        header('Content-type: application/json');
        echo json_encode($result);
    }

    function pelepasan() {
        $TABLE = $this->input->post('TABLE');
        $MAIN_TABLE = $this->input->post('MAIN_TABLE');
        $tgl_transaksi = $this->input->post("TANGGAL_TRANSAKSI");
        $uraian_harta = $this->input->post('URAIAN_HARTA');
        $nilai_lepas = $this->input->post('NILAI_PELEPASAN');
        $nama = $this->input->post('NAMA');
        $alamat = $this->input->post('ALAMAT');
        $I_JS = $this->input->post('JENIS_PELEPASAN_HARTA');
        if ($I_JS == '2') {
            $JENIS_LEPAS = '2';
        } else if ($I_JS == '1') {
            $JENIS_LEPAS = '1';
        } else {
            $JENIS_LEPAS = $I_JS;
        }


        $state_id_harta = $this->input->post('ID_HARTA');
        $state_id_lhkpn = $this->input->post('ID_LHKPN');

        switch ($MAIN_TABLE) {
            case 't_lhkpn_harta_tidak_bergerak': {
                $harta = "Harta Tidak Bergerak";
                break;
            }
            case 't_lhkpn_harta_bergerak': {
                $harta = "Harta Bergerak";
                break;
            }
            case 't_lhkpn_harta_bergerak_lain': {
                $harta = "Harta Bergerak Lain";
                break;
            }
            case 't_lhkpn_harta_surat_berharga': {
                $harta = "Harta Surat Berharga";
                break;
            }
            case 't_lhkpn_harta_kas': {
                $harta = "Harta Kas";
                break;
            }
            case 't_lhkpn_harta_lainnya': {
                $harta = "Harta Lainnya";
                break;
            }
        }
        ////////////////SISTEM KEAMANAN////////////////
        $state_id_pn = $this->session->userdata('ID_PN');
        $check_protect = protectFilling($state_id_pn,$MAIN_TABLE,$state_id_harta);
        if($check_protect){
            $method = __METHOD__;
            $this->load->model('mglobal');
            $this->mglobal->recordLogAttacker($check_protect,$method);
            echo 9;
            return;
        }   
        ////////////////SISTEM KEAMANAN////////////////
        

        ////////////////SISTEM KEAMANAN////////////////
            $state_id_pn = $this->session->userdata('ID_PN');
            $check_protect = protectLhkpn($state_id_pn,$state_id_lhkpn);  
            if($check_protect){
                $method = __METHOD__;
                $this->load->model('mglobal');
                $this->mglobal->recordLogAttacker($check_protect,$method);
                echo 9;
                return;
            }   
        ////////////////SISTEM KEAMANAN////////////////

        $data = array(
            'ID_HARTA' => $this->input->post('ID_HARTA'),
            'ID_LHKPN' => $this->input->post('ID_LHKPN'),
            'JENIS_PELEPASAN_HARTA' => $JENIS_LEPAS,
            'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post("TANGGAL_TRANSAKSI")))),
            'URAIAN_HARTA' => $this->input->post('URAIAN_HARTA'),
            'NILAI_PELEPASAN' => intval(str_replace('.', '', $this->input->post('NILAI_PELEPASAN'))),
            'NAMA' => $this->input->post('NAMA'),
            'ALAMAT' => $this->input->post('ALAMAT'),
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        );

        //print_r($data);

        if ($MAIN_TABLE == 't_lhkpn_harta_kas') {
            $NILAI = 'NILAI_EQUIVALEN';
        } else if ($MAIN_TABLE == 't_lhkpn_hutang') {
            $NILAI = 'SALDO_HUTANG';
        } else {
            $NILAI = 'NILAI_PELAPORAN';
        }

        $this->db->where('ID', $this->input->post('ID_HARTA'));
        $updated = $this->db->update($MAIN_TABLE, array(
            $NILAI => 0,
            'STATUS' => '1',
            'IS_PELEPASAN' => '1',
            'IS_CHECKED' => '1',
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        ));
        ng::logActivity("Pelepasan Data ".$harta.", ID = ".$this->input->post('ID_HARTA').", ID_LHKPN = ".$data['ID_LHKPN']);

        if ($updated && ($I_JS != NULL || $I_JS != '') && ($tgl_transaksi != NULL || $tgl_transaksi != '') && ($uraian_harta != NULL || $uraian_harta != '') && ($nilai_lepas != NULL || $nilai_lepas != '') && ($nama != NULL || $nama != '') && ($alamat != NULL || $alamat != '')) {
//        if ($updated) {
            $save = $this->db->insert($TABLE, $data);
            if ($save) {
                echo "1";
            } else {
                echo "0";
            }
        } else {
            echo "0";
        }
        /* rollback
        // create or update kas
        $this->create_or_update_kas($this->input->post('ID_LHKPN'));
        rollback */
    }

    function KIRIM() {
        $ID_LHKPN = $this->input->post('ID_LHKPN_SEND');
        redirect('portal/filing');
    }

    function form($name) {
        $this->load->view('portal/filing/form_harta/form_' . $name);
    }

    function grid($name, $ID_LHKPN) {
        $alias = array();
        $alias['harta_tidak_bergerak'] = array('1', 't_lhkpn_harta_tidak_bergerak');
        $alias['harta_bergerak'] = array('2', 't_lhkpn_harta_bergerak');
        $alias['harta_mesin'] = array('2', 't_lhkpn_harta_bergerak');
        $alias['harta_lain'] = array('3', 't_lhkpn_harta_bergerak_lain');
        $alias['surat_berharga'] = array('4', 't_lhkpn_harta_surat_berharga');
        $alias['kas'] = array('5', 't_lhkpn_harta_kas');
        $alias['lain'] = array('6', 't_lhkpn_harta_lainnya');
        $alias['hutang'] = array('7', 't_lhkpn_hutang');
        $IS_LOAD = FALSE;

        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->where($alias[$name][1] . '.IS_LOAD', '1');
        $this->db->join($alias[$name][1], $alias[$name][1] . '.ID_LHKPN = t_lhkpn.ID_LHKPN');
        $lhkpn = $this->db->get('t_lhkpn')->num_rows();
        if ((int) $lhkpn > 0) {
            $IS_LOAD = TRUE;
        } else {
            $IS_LOAD = FALSE;
        }

        $index = $alias[$name][0];

        if ($IS_LOAD) {
            $action = 1;
        } else {
            $action = 0;
        }

        $query_harta = "select(select count(*) from (SELECT
                            t_lhkpn_harta_tidak_bergerak.ID_HARTA
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_tidak_bergerak`
                              ON `t_lhkpn_harta_tidak_bergerak`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN` and `t_lhkpn_harta_tidak_bergerak`.`IS_LOAD` = '1'
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              union
                          SELECT
                            t_lhkpn_harta_bergerak.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_bergerak`
                              ON `t_lhkpn_harta_bergerak`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN` AND `t_lhkpn_harta_bergerak`.`IS_LOAD` = '1'
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_bergerak_lain.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_bergerak_lain`
                              ON `t_lhkpn_harta_bergerak_lain`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN` AND `t_lhkpn_harta_bergerak_lain`.`IS_LOAD` = '1'
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_surat_berharga.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_surat_berharga`
                              ON `t_lhkpn_harta_surat_berharga`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN` AND `t_lhkpn_harta_surat_berharga`.`IS_LOAD` = '1'
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_kas.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_kas`
                              ON `t_lhkpn_harta_kas`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN` AND `t_lhkpn_harta_kas`.`IS_LOAD` = '1'
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_lainnya.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_lainnya`
                              ON `t_lhkpn_harta_lainnya`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN` AND `t_lhkpn_harta_lainnya`.`IS_LOAD` = '1'
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                           SELECT
                            t_lhkpn_hutang.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_hutang`
                              ON `t_lhkpn_hutang`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN` AND `t_lhkpn_hutang`.`IS_LOAD` = '1'
                          WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "') as co_harta) as c_harta";
        $count_harta = $this->db->query($query_harta)->row();
        $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('STATUS !=', '0');
        $this->db->where('STATUS !=', '2');
        $this->db->where('ID_LHKPN <', $ID_LHKPN);
        $this->db->where('IS_COPY', '0');
        $this->db->limit(1);
        $this->db->order_by('TGL_LAPOR', 'DESC');
        $parent = $this->db->get('t_lhkpn')->row();

        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $alias[$name][1] . '.ID_LHKPN', 'left');
        $this->db->where('t_lhkpn.IS_ACTIVE', '1');
        $this->db->where($alias[$name][1] . '.ID_LHKPN', $parent->ID_LHKPN);
        $this->db->where(' (' . $alias[$name][1] . '.IS_LOAD = \'0\' OR ' . $alias[$name][1] . '.IS_LOAD IS NULL ) ', NULL, FALSE);
        $this->db->where(' (t_lhkpn.STATUS = \'0\' OR t_lhkpn.STATUS = \'2\' ) ', NULL, FALSE);
        $last_lhkpn = $this->db->get($alias[$name][1])->num_rows();
//        echo $this->db->last_query();exit;
        if ($name == 'harta_mesin') {
            $name = 'harta_bergerak';
        }

        $data = array('ACTION' => $action, 'INDEX' => $index, 'JUMLAH_DATA' => $last_lhkpn, 'C_HARTA' => intval($count_harta->c_harta));
        $this->load->view('portal/filing/grid_harta/grid_' . $name);
        $this->load->view('portal/filing/action', $data);
    }

    function get_web_page($url) {

        $options = array(
            CURLOPT_CUSTOMREQUEST => "GET", // Atur type request, get atau post
            CURLOPT_POST => false, // Atur menjadi GET
            CURLOPT_FOLLOWLOCATION => true, // Follow redirect aktif
            CURLOPT_CONNECTTIMEOUT => 120, // Atur koneksi timeout
            CURLOPT_TIMEOUT => 120, // Atur response timeout
        );

        $ch = curl_init($url);          // Inisialisasi Curl
        curl_setopt_array($ch, $options);    // Set Opsi
        $content = curl_exec($ch);           // Eksekusi Curl
        curl_close($ch);                     // Stop atau tutup script

        $header['content'] = $content;
        return $header;
    }

    function testCURL() {
        $result = CallURLPage('http://localhost/testing/?SEND={"idOutbox":20,"tujuan":"08111111021","isiPesan":"Test", "idModem":1, "jmlPesan":1}&a=2');
        print_r($result);
    }

    function TabelInputHarta($index) {
        $data = array();
        $data[1] = 't_lhkpn_harta_bergerak';
        $data[2] = 't_lhkpn_harta_bergerak_lain';
        $data[3] = 't_lhkpn_harta_tidak_bergerak';
        $data[4] = 't_lhkpn_harta_surat_berharga';
        $data[5] = 't_lhkpn_harta_lainnya';
        $data[6] = 't_lhkpn_harta_kas';
        $data[7] = 't_lhkpn_hutang';
        return $data[$index];
    }

    function TabelInput($index) {
        $data = array();
        $data[1] = 't_lhkpn_data_pribadi';
        $data[2] = 't_lhkpn_jabatan';
        $data[3] = 't_lhkpn';
//        $data[3] = 't_lhkpn_keluarga';
//        $data[4] = 't_lhkpn_harta_bergerak';
//        $data[5] = 't_lhkpn_harta_bergerak_lain';
//        $data[6] = 't_lhkpn_harta_tidak_bergerak';
//        $data[7] = 't_lhkpn_harta_surat_berharga';
//        $data[8] = 't_lhkpn_harta_lainnya';
//        $data[9] = 't_lhkpn_harta_kas';
//        $data[10] = 't_lhkpn_hutang';
        $data[4] = 't_lhkpn_penerimaan_kas2';
        $data[5] = 't_lhkpn_pengeluaran_kas2';
//        $data[6] = 't_lhkpn_fasilitas';
        return $data[$index];
    }

    function cekStatusInput($ID_LHKPN) {
        for ($i = 1; $i <= 5; $i++) {
            $data_tbl = $this->mglobal->get_data_all($this->TabelInput($i), NULL, ['ID_LHKPN' => $ID_LHKPN])[0];
            if (!$data_tbl->ID_LHKPN)
                $val += 0;
            else
                $val += 1;
        }
        $prosentase = ceil(($val / 5) * 100);

        $datainputharta = $this->cekStatusInputHarta($ID_LHKPN,$prosentase);

        $data['persen'] = $prosentase;
        $data['id'] = $ID_LHKPN;

        echo json_encode($data);

        exit;
    }

    function cekStatusInputHarta($ID_LHKPN, $prosentase) {
        for ($i = 1; $i <= 7; $i++) {
            $data_tbl = $this->mglobal->get_data_all($this->TabelInputHarta($i), NULL, ['ID_LHKPN' => $ID_LHKPN])[0];
            if (!$data_tbl->ID_LHKPN)
                $val += 0;
            else
                $val += 1;
        }

        $cekprosentase = ceil(($val / 7) * 100);

        $prosentaseHarta = 0;
        if ($cekprosentase > 0){
            $prosentaseHarta = ceil(100);
        }
//        var_dump($prosentaseHarta.'<br>'.$prosentase);exit;
        $jmlProsentase = ceil(($prosentase + $prosentaseHarta) / 2);
//        var_dump($jmlProsentase);exit;
        $data['persen'] = $jmlProsentase;
        $data['id'] = $ID_LHKPN;
        
        echo json_encode($data);

        exit;
    }

    function pk($TABLE) {
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        return $data[0]['Column_name'];
    }

    function edit_jenis_pelaporan($ID) {
        $TABLE = 't_lhkpn';
        $PK = $this->pk($TABLE);
        $this->db->where($PK, $ID);
        $result = $this->db->get($TABLE)->row();

        $id_pn = $result->ID_PN;
        $sql_get_all_lhkpn = "SELECT * FROM t_lhkpn WHERE id_pn = '$id_pn' AND is_active = 1";
        $act_get_all_lhkpn = $this->db->query($sql_get_all_lhkpn)->result();
        $count_all_lhkpn = Count($act_get_all_lhkpn);

        $wl_tahun_now = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $id_pn, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1', 'ID_STATUS_AKHIR_JABAT' => '0', 'is_wl' => '1', 'tahun_wl' => date('Y')], 'TAHUN_WL')[0];
        $wl_thn_minus_1 = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $id_pn, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1', 'ID_STATUS_AKHIR_JABAT' => '0', 'is_wl' => '1', 'tahun_wl' => date('Y')-1], 'TAHUN_WL')[0];
        $history = $this->mlhkpn->get_by_id_pn($id_pn);

        $dateInput_1 = NULL;
        $tahun_1 = "";
        if ($history) {
            if (count($history) > 1) {
                $dateInput_1 = explode('-', $history[1]->tgl_lapor);
                $tahun_1 = $dateInput_1[0];
            }
        }
       
        $data = array(
            'result' => $result,
            'wl_tahun_now' => $wl_tahun_now ? 1 : 0,
            'wl_thn_minus_1' => $wl_thn_minus_1 ? 1 : 0,
            'tahun_minus_1' => $tahun_1, 
            'is_update' => 'update',
            'count_all_lhkpn' => $count_all_lhkpn
        );
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function __is_cetak_var_not_blank($val, $default_value = "", $bool = FALSE) {
        $val = trim($val);
        if ($val != "" && $val != NULL && $val != FALSE) {
            return $bool ? TRUE : $val;
        }
        return $bool ? FALSE : $default_value;
    }
    
    public function BeforeAnnoun($id_lhkpn, $id_bap = NULL) {
        
        $ID_PN_SESS = $this->session->userdata('ID_PN');
        $this->db->select('ID_PN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $ID_PN_NEW = $this->db->get('t_lhkpn')->row()->ID_PN;
        
        if ($ID_PN_SESS){
            $ID_PN = $ID_PN_SESS;
        }else{
            $ID_PN = $ID_PN_NEW;
        }
        
        $check = $this->mlhkpn->get_detail_by_pn($id_lhkpn, $ID_PN);

        if ($check) {
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
            $output_filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NHK . ".docx";

            /////////////////////////////WORD GENERATOR///////////////////////////
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
            /////////////////////////////WORD GENERATOR///////////////////////////

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
            /////////////////////////////WORD GENERATOR///////////////////////////


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
                    "PENGESAHAN" => tgl_format($datapn->TGL_BA_PENGUMUMAN),
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
                $html = $this->load->view('filing/export_pdf/pengumuman', $data, true);
                $filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $data['NHK'];
                $method = "stream";
                $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait');
            /////////////////////////////TUTUP PDF GENERATOR///////////////////////////
                $temp_dir = APPPATH."../images/qrcode/";
                unlink($temp_dir.$qr_file);
        } else {
            redirect('portal/filing');
        }

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
    
    public function ajax_cetak_sk($id_lhkpn)
    {
        $this->db->select('
            t_lhkpn.STATUS,
            t_lhkpn.entry_via,
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
        $this->db->where('t_lhkpn.ID_LHKPN', $id_lhkpn);
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
        $this->db->where('t_lhkpn.ID_LHKPN', $id_lhkpn);
        $data_keluarga = $this->db->get('t_lhkpn_keluarga')->result();

        $results = array_merge($data_pn, $data_keluarga);
        $results[0]->HUBUNGAN_DESC = 'PN';
        $results[0]->HUBUNGAN = 'PN';
        for ($i=0; $i < count($results); $i++) { 
            if ($results[$i]->HUBUNGAN_DESC === 'PN') {
                $cetak_link = base_url() . 'portal/review_harta/surat_kuasa_pdf2/' . $id_lhkpn . '/1/1/1';
                $results[$i]->CETAK_LINK = $cetak_link;
            } else {
                $cetak_link = base_url() . 'portal/review_harta/cetak_surat_kuasa_individual/';
                $link = false;
                
                if($results[$i]->STATUS != 0 && $results[$i]->entry_via == '0' && $results[$i]->UMUR_LAPOR >= 17 && ($results[$i]->HUBUNGAN < 4) && $list->FLAG_SK == 0){
                    $results[$i]->CETAK_LINK = $cetak_link 
                        . $results[$i]->ID_KELUARGA
                        . '/'
                        . $id_lhkpn
                        . '/1';
                } else {
                    $results[$i]->CETAK_LINK = "";
                }
            }
        }
        echo json_encode($results);

        exit;
    }

    function hubungan($id) {
        $data = array();
        $data[1] = 'ISTRI';
        $data[2] = 'SUAMI';
        $data[3] = 'ANAK TANGGUNGAN';
        $data[4] = 'ANAK BUKAN TANGGUNGAN';
        $data[5] = 'LAINNYA';
        return $data[$id];
    }

    public function create_or_update_kas($id_lhkpn)
    {
        $result = false;
        // create or update pengeluaran kas
        $this->load->model('efill/Mpengeluaran', 'mpengeluaran');
        $pengeluaran = $this->mpengeluaran->create_or_update($id_lhkpn);
        // create or update penerimaan kas
        $this->load->model('efill/Mpenerimaan', 'mpenerimaan');
        $penerimaan = $this->mpenerimaan->create_or_update($id_lhkpn);
        if ($penerimaan && $pengeluaran) {
            $result = true;
        }

        return $result;
    }

    public function cetak_lembar_penyerahan($id_lhkpn){
        $datapn = $this->get_data_pn_by_id_lhkpn($id_lhkpn, true, FALSE);
        $data = $this->preview_lp(FALSE, $datapn, TRUE);

        $tahun = get_format_tanggal_lapor_lhkpn($data['datapn']->JENIS_LAPORAN, $data['datapn']->TGL_LAPOR);

        $tambahan_kalimat = "Berdasarkan data yang kami miliki bahwa laporan LHKPN terdahulu Saudara tercatat tidak lengkap, oleh karena itu mohon segera mencetak dan menandatangani di atas meterai setiap nama dalam Surat Kuasa yang terlampir dalam email ini dan mengirimkannya ke Direktorat Pendaftaran dan Pemeriksaan LHKPN KPK. Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta dan Data Keluarga aplikasi e-Filing LHKPN.";

        if ($data) {

            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/lembarpenyerahanformulir.docx";

            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            if ($load_template_success) {
                $this->lwphpword->set_value("NAMA_LENGKAP", isExist($data['datapn']->NAMA));
                $this->lwphpword->set_value("JABATAN", isExist($data['datapn']->NAMA_JABATAN));
                $this->lwphpword->set_value("SUK", isExist($data['datapn']->SUK_NAMA));
                $this->lwphpword->set_value("UK", isExist($data['datapn']->UK_NAMA));
                $this->lwphpword->set_value("BIDANG", isExist($data['datapn']->BDG_NAMA));
                $this->lwphpword->set_value("LEMBAGA", isExist($data['datapn']->INST_NAMA));
                $this->lwphpword->set_value("TANGGAL", isExist($tahun));
                $this->lwphpword->set_value("TAMBAHAN_KALIMAT", ($data['datapn']->ID_LHKPN_PREV == '2' || $data['datapn']->ID_LHKPN_PREV == '5' || $data['datapn']->ID_LHKPN_PREV == '6') ? $tambahan_kalimat : " ");

                $save_document_success = $this->lwphpword->save_document();

                if ($save_document_success) {
                    $output_filename = "Bukti Pengiriman LHKPN" . date('d-F-Y') . ".docx";
                    $this->lwphpword->download($save_document_success, $output_filename);
                    //delete file after download
                    unlink($save_document_success);
                }
                unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
            }
        }
    }

}
