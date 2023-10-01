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
        $DT_JAB = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $ID_PN], NULL, NULL, ['ID', 'DESC'])[0];

        $history = $this->mlhkpn->get_by_id_pn($ID_PN);

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
            'draft_num' => (int) $draft_num,
            'NOW' => $NOW,
            'LAST' => $LAST,
            'YEAR' => $YEAR,
            'dateInput' => $dateInput,
            'dateInput_1' => $dateInput_1,
            'dateInput_2' => $dateInput_2,
            'tahun_1' => $tahun_1,
            'tahun_2' => $tahun_2,
            'STS_JAB' => $DT_JAB->ID_STATUS_AKHIR_JABAT,
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
        $this->db->where('t_pn_jabatan.ID_PN', $ID_PN);
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_pn_jabatan.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_pn_jabatan.LEMBAGA', 'left');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_NAMA = t_pn_jabatan.UNIT_KERJA', 'left');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_NAMA = t_pn_jabatan.SUB_UNIT_KERJA', 'left');
        $this->db->join('m_eselon', 'm_eselon.KODE_ESELON = t_pn_jabatan.ESELON', 'LEFT');
        $this->db->group_by('t_pn_jabatan.ID');
        $PN_JABATAN = $this->db->get('t_pn_jabatan')->result();

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
                    'IS_PRIMARY' => $ROW->IS_PRIMARY,
                );
                $this->db->insert('t_lhkpn_jabatan', $add_new);
            }
        }
    }

// insert data pribadi untuk laporan kedua

    function UpdateDataPribadi($ID_LHKPN) {


        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $lhkpn = $this->db->get('t_lhkpn')->row();
        $ID_PN = $this->session->userdata('ID_PN');

        if ($lhkpn->IS_COPY == '1') {
            $this->db->where('t_lhkpn.ID_PN', $ID_PN);
            $this->db->where('t_lhkpn.ID_LHKPN !=', $ID_LHKPN);
            $this->db->where('t_lhkpn.IS_ACTIVE', '1');
            $this->db->where('t_lhkpn.STATUS !=', '0');
            $this->db->order_by('t_lhkpn.ID_LHKPN', 'DESC');
            $this->db->limit(1);
            $this->db->join('t_lhkpn', 't_lhkpn_data_pribadi.ID_LHKPN = t_lhkpn.ID_LHKPN');
            $pn = $this->db->get('t_lhkpn_data_pribadi')->row();

            $new = array(
                'ID_LHKPN' => $ID_LHKPN,
                'GELAR_DEPAN' => $pn->GELAR_DEPAN,
                'GELAR_BELAKANG' => $pn->GELAR_BELAKANG,
                'NAMA_LENGKAP' => $pn->NAMA_LENGKAP,
                'JENIS_KELAMIN' => $pn->JENIS_KELAMIN,
                'TEMPAT_LAHIR' => $pn->TEMPAT_LAHIR,
                'TANGGAL_LAHIR' => $pn->TANGGAL_LAHIR,
                'STATUS_PERKAWINAN' => $pn->STATUS_PERKAWINAN,
                'NIK' => $pn->NIK,
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
            $tahun_pelaporan = $this->input->post('tahun_pelaporan');
            $status = $this->input->post('status');
            $tgl_pelaporan = $this->input->post('tgl_pelaporan');

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
//                
//                $check_exist = $this->db->get('t_lhkpn')->row();

                $check_exist = $this->mlhkpn->get_lhkpn_pn_by_id_pn($ID_PN, FALSE, array(
                    "TGL_LAPOR <" => $DATE_REPORT
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
                    'USERNAME_ENTRI' => $this->session->userdata('NAMA'),
                    'CREATED_TIME' => date('Y-m-d'),
                    'CREATED_IP' => $this->get_client_ip()
                );

                $this->db->insert('t_lhkpn', $lhkpn);
                $ID_LHKPN_NEW = $this->db->insert_id();

                if ($check_exist) {
                    // UPDATE KELUARGA
                    $this->db->where('ID_LHKPN', $ID_LHKPN_PREV);
                    $keluarga = $this->db->get('t_lhkpn_keluarga')->result();
                    if ($keluarga) {
                        foreach ($keluarga as $kl) {
                            $arr_keluarga = array(
                                'ID_KELUARGA_LAMA' => $kl->ID_KELUARGA,
                                'ID_LHKPN' => $ID_LHKPN_NEW,
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
                                'CREATED_TIME' => time(),
                                'CREATED_BY' => $this->session->userdata('NAMA'),
                                'CREATED_IP' => get_client_ip(),
                                'UPDATED_TIME' => time(),
                                'UPDATED_BY' => $this->session->userdata('NAMA'),
                                'UPDATED_IP' => get_client_ip(),
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
            if ($lhkpn_pn->TGL_LAPOR) {
                $LAST_YEARS = substr($lhkpn_pn->TGL_LAPOR, 0, 4);
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


            list($LAST_ID_LHKPN, $LAST_YEARS) = $this->__entry_get_last_id_and_year_lhkpn($id, $ID_PN);


            $kuasa = $this->mlhkpn->cetak_surat_kuasa_time_by_pn($ID_PN);

            $IS_PRINT = 0;
            $NOW_YEARS = substr($check->TGL_LAPOR, 0, 4);

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
                $options = array(
                    'title' => 'e-Filing',
                    'ID_LHKPN' => $id,
                    'LAST_ID_LHKPN' => $LAST_ID_LHKPN,
                    'IS_COPY' => $check->IS_COPY,
                    'IS_PRINT' => $IS_PRINT,
                    'NOW_YEARS' => $NOW_YEARS,
                    'LAST_YEARS' => $LAST_YEARS,
                    'TGL_LAPOR' => $check->TGL_LAPOR,
                    'STATUS' => $check->STATUS,
                    'TOKEN_PENGIRIMAN' => $check->TOKEN_PENGIRIMAN,
                    'USERNAME' => $this->session->userdata('USERNAME'),
                    'JENIS_PELEPASAN_HARTA' => $this->mjenispelepasanharta->get_all()
                );

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

        $I_JS = $this->input->post('JENIS_PELEPASAN_HARTA');
        if ($I_JS == '2') {
            $JENIS_LEPAS = '2';
        } else if ($I_JS == '1') {
            $JENIS_LEPAS = '1';
        } else {
            $JENIS_LEPAS = '3';
        }

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

        if ($updated) {
            $save = $this->db->insert($TABLE, $data);
            if ($save) {
                echo "1";
            } else {
                echo "0";
            }
        } else {
            echo "0";
        }
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

        $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('STATUS !=', '0');
        $this->db->where('STATUS !=', '2');
        $this->db->where('ID_LHKPN <', $ID_LHKPN);
        $this->db->where('IS_COPY', '0');
        $this->db->limit(1);
        $this->db->order_by('TGL_LAPOR', 'DESC');
        $parent = $this->db->get('t_lhkpn')->row();
        
        $this->db->join('t_lhkpn','t_lhkpn.ID_LHKPN = '.$alias[$name][1].'.ID_LHKPN','left');
        $this->db->where('t_lhkpn.IS_ACTIVE','1');
        $this->db->where($alias[$name][1] . '.ID_LHKPN', $parent->ID_LHKPN);
        $this->db->where(' ('.$alias[$name][1] . '.IS_LOAD = \'0\' OR '.$alias[$name][1] . '.IS_LOAD IS NULL ) ', NULL, FALSE);
        $last_lhkpn = $this->db->get($alias[$name][1])->num_rows();
//        echo $this->db->last_query();exit;
        if($name == 'harta_mesin'){
            $name = 'harta_bergerak';
        }

        $data = array('ACTION' => $action, 'INDEX' => $index, 'JUMLAH_DATA' => $last_lhkpn);
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

    function TabelInput($index) {
        $data = array();
        $data[1] = 't_lhkpn_data_pribadi';
        $data[2] = 't_lhkpn_jabatan';
        $data[3] = 't_lhkpn_keluarga';
        $data[4] = 't_lhkpn_harta_bergerak';
        $data[5] = 't_lhkpn_harta_bergerak_lain';
        $data[6] = 't_lhkpn_harta_tidak_bergerak';
        $data[7] = 't_lhkpn_harta_surat_berharga';
        $data[8] = 't_lhkpn_harta_lainnya';
        $data[9] = 't_lhkpn_harta_kas';
        $data[10] = 't_lhkpn_hutang';
        $data[11] = 't_lhkpn_penerimaan_kas2';
        $data[12] = 't_lhkpn_pengeluaran_kas2';
        $data[13] = 't_lhkpn_fasilitas';
        return $data[$index];
    }

    function cekStatusInput($ID_LHKPN) {
        for ($i = 1; $i <= 13; $i++) {
            $data_tbl = $this->mglobal->get_data_all($this->TabelInput($i), NULL, ['ID_LHKPN' => $ID_LHKPN])[0];
            if (!$data_tbl->ID_LHKPN)
                $val += 0;
            else
                $val += 1;
        }
        $prosentase = ceil(($val / 13) * 100);

        $data['persen'] = $prosentase;
        $data['id'] = $ID_LHKPN;
        echo json_encode($data);
    }

}
