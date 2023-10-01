<?php
/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller LHKPN
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Efill/Controllers/Lhkpn
 */
?>
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lhkpn extends CI_Controller {

    // num of records per page
    public $limit = 10;
    public $username;

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
        $this->username = $this->session->userdata('USERNAME');
        $this->makses->initialize();
        $this->makses->check_is_read();
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);

        // prepare search
        foreach ((array) @$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];

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

    /** LKHPN List
     * 
     * @return html LKHPN List
     */
    public function index($offset = 0) {
        // load model
        $this->load->model('mlhkpn', '', TRUE);

        // prepare paging
        $this->base_url = 'index.php/efill/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/';
        $this->uri_segment = 4;
        $this->offset = $this->uri->segment($this->uri_segment);

        if ($this->session->userdata('IS_PN')) {
            $nik = $this->session->userdata('NIK');

            $sql = "SELECT ID_PN FROM T_PN WHERE NIK = $nik";
            $data = $this->db->query($sql)->result()[0];
            // $this->db->where(['T_LHKPN.ID_PN' => $data->ID_PN]);            
        }

        $this->db->start_cache();

        /*
          @todo dalam process perubahan
         */
        if ($this->isDataEntry()) {
            $this->db->select("T_LHKPNOFFLINE_PENERIMAAN.*
                ,D.*
                ,JABATAN.*
                ,T_PN.*
                ,T_LHKPN.*
                ,CONCAT(YEAR(TGL_LAPOR),IF(T_LHKPN.JENIS_LAPORAN = '4','R','K'),NIK,T_LHKPN.ID_LHKPN) AS AGENDA", FALSE);
        } else {
            $this->db->select("T_LHKPNOFFLINE_PENERIMAAN.*
                ,JABATAN.*
                ,T_PN.*
                ,T_LHKPN.*
                ,CONCAT(YEAR(TGL_LAPOR),IF(T_LHKPN.JENIS_LAPORAN = '4','R','K'),NIK,T_LHKPN.ID_LHKPN) AS AGENDA", FALSE);
        }

        $this->db->from('T_LHKPN');
        // $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
        $this->db->join('(
                select  ID_LHKPN AS ID_LHKPN_DIJABATAN, 
                    group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN, 
                    group_concat(
                    CONCAT(
                        IFNULL(T_LHKPN_JABATAN.ID,""),":58:",
                        IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                        IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                        IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":58:",
                        IFNULL(M_JABATAN.NAMA_JABATAN,"")," ",
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
        $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
        $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPN.ID_LHKPN=T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'LEFT');
        // $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = T_LHKPN.LEMBAGA', 'left');
//        if($this->isDataEntry()){
        $this->db->join('T_LHKPNOFFLINE_PENUGASAN_ENTRY D', 'D.ID_LHKPN = T_LHKPN.ID_LHKPN', 'LEFT');
        $this->db->where('(T_LHKPNOFFLINE_PENERIMAAN.USERNAME_KOORD_ENTRY = "' . $this->session->userdata('USERNAME') . '" OR USERNAME_ENTRI = "' . $this->session->userdata('USERNAME') . '" OR T_LHKPN.ID_PN = "' . @$this->session->userdata('ID_PN') . '")', NULL, FALSE);
//        }

        if (@$this->CARI['TAHUN']) {
            $this->db->where('YEAR(TGL_LAPOR)', $this->CARI['TAHUN']);
        }

        if (@$this->CARI['LEMBAGA']) {
            $this->db->like('NAMA_JABATAN', $this->CARI['LEMBAGA']);
        }

        if (@$this->CARI['NAMA']) {
            $this->db->like('T_PN.NAMA', $this->CARI['NAMA']);
        }

        if (@$this->CARI['JENIS']) {
            $this->db->where('T_LHKPN.JENIS_LAPORAN', $this->CARI['JENIS']);
        }

        if (@$this->CARI['VIA'] != '') {
            $this->db->where('T_LHKPN.ENTRY_VIA', $this->CARI['VIA']);
        }

        if (@$this->CARI['STATUS'] != '') {
            $status = $this->CARI['STATUS'];
            $this->db->where('T_LHKPN.STATUS', $status);
        }

        if ($this->session->userdata('IS_PN')) {
            $this->db->where(['T_LHKPN.ID_PN' => $data->ID_PN]);
        }

        $this->db->order_by("TGL_LAPOR", 'desc');

        $this->total_rows = $this->db->get('')->num_rows();

        $query = $this->db->get('', $this->limit, $this->offset);
        // display($this->db->last_query());exit();
        $this->items = $query->result();
        $this->end = $query->num_rows();
        // echo "<pre>";
        // print_r ($query);
        // echo "</pre>";
        $this->db->flush_cache();

        $data = array(
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'CARI' => @$this->CARI,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E-Filling' => 'index.php/dashboard/efilling',
                'Lhkpn' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'isPN' => @$this->session->userdata('IS_PN'),
            'pagination' => call_user_func('ng::genPagination'),
        );

        print_r(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__));
        // load view
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    /** Process Insert, Update, Delete Lhkpn
     * 
     * @return boolean process Lhkpn
     */
    function savelhkpn() {
        if ($this->input->post('act') == 'dodelete') {
            display($_POST);
            $this->db->trans_begin();
            $ID_LHKPN = $this->input->post('ID_LHKPN');
            $this->mglobal->delete('T_LHKPN', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_STATUS_HISTORY', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_DATA_PRIBADI', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_KELUARGA', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_JABATAN', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_HARTA_TIDAK_BERGERAK', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_HARTA_BERGERAK', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_HARTA_BERGERAK_LAIN', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_HARTA_KAS', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_HARTA_SURAT_BERHARGA', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_HARTA_LAINNYA', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_HUTANG', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_PENERIMAAN_KAS', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_PENGELUARAN_KAS', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_FASILITAS', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_DOK_PENDUKUNG', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_PENJUALAN', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_PELEPASAN_HARTA_KAS', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");
            $this->mglobal->delete('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA', '1=1', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$ID_LHKPN'");

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            echo intval($this->db->trans_status());
            return;
        }

        $result = FALSE;
        
        $this->db->trans_begin();
        $this->load->model('mlhkpn', '', TRUE);

        $pn = $this->input->post('ID_PN');
        $jenis = $this->input->post('JENIS_LAPORAN');
        if ($jenis == '4') {
            $tahun = $this->input->post('TAHUN_PELAPORAN');
        } else {
            $tahun = date('Y', strtotime($this->input->post('TANGGAL_PELAPORAN')));
        }

        $this->load->library('lhkpn_lib');

        $result = $this->lhkpn_lib->copylhkpn($pn, $tahun, '0');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        if ($result == false) {
            echo '0';
        } else {
            echo substr(md5($result), 5, 8);
        }
    }

    /** Process Submit LHKPN
     * 
     * @return boolean process Lhkpn
     */
    function submitlhkpn($mode = false) {
        $this->load->model('mglobal');

        if ($this->input->post('act') != 'doverify') {
            echo 0;
            return;
        }

        $this->db->trans_begin();

        if ($mode == 'announ') {
            $data = [
                'STATUS' => '1',
                'ALASAN' => NULL
            ];
        } else {
            $data = array(
                'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                'STATUS' => '1'
            );
        }

        $this->db->where('ID_LHKPN', $this->input->post('ID_LHKPN'));
        $this->db->update('T_LHKPN', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            // History
            if ($mode != 'announ') {
                $is_kpk = $this->session->userdata('IS_KPK');

                $id_lhkpn = $this->input->post('ID_LHKPN');
                if ($is_kpk == '1') {
                    $status = '17';
                } else {
                    $status = '10';
                }

                $check = $this->mglobal->count_data_all('T_LHKPN_STATUS_HISTORY', null, ['ID_LHKPN' => $id_lhkpn, 'ID_STATUS' => '17']);
                if ($check == '0') {
                    $history = [
                        'ID_LHKPN' => $id_lhkpn,
                        'ID_STATUS' => $status,
                        'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                        'USERNAME_PENERIMA' => '',
                        'DATE_INSERT' => date('Y-m-d H:i:s'),
                        'CREATED_IP' => $this->input->ip_address()
                    ];

                    $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                }
            }

            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    function pesan_pdf() {
        $this->db->trans_begin();

        $ID_LHKPN = $this->input->post('ID_LHKPN');
        $ENTRY_VIA = $this->input->post('ENTRY_VIA');

        if ($ENTRY_VIA == '0') {
            $datapn = @$this->mglobal->get_data_all('T_USER', [
                            ['table' => 'T_PN', 'on' => 'T_PN.NIK = T_USER.USERNAME'],
                            ['table' => 'T_LHKPN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'],
                            ], ['T_USER.IS_ACTIVE' => '1', 'T_LHKPN.ID_LHKPN' => $ID_LHKPN], 'T_USER.NAMA, T_PN.EMAIL')[0];

            // create file
            $this->load->library('pdf');
            $time = time();
            $dataPDF = array('NAMA' => $datapn->NAMA);
            $html = $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_ttspdf', $dataPDF, true);
            $pdf = $this->pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output('uploads/pdf/' . $time . '-LHKPN(' . $datapn->NAMA . '-' . $ID_LHKPN . ')-fileTTS.pdf', 'F');

            // $admin = $this->mglobal->get_data_all('T_USER', null, ['USERNAME = ' => 'admin_kpk'],'ID_USER, NAMA,EMAIL')[0];
            // ng::mail_send($datapn->EMAIL, 'Tanda Terima Sementara LHKPN', 'File Tanda Terima Sementara Laporan Harta Kekayaan Penyelenggara Negara',NULL,'uploads/pdf/'.$time.'-LHKPN('.$datapn->NAMA.'-'.$ID_LHKPN.')-fileTTS.pdf', $admin->EMAIL);

        }
        if($this->db->trans_status() !== FALSE){
            $this->db->trans_rollback();
            echo '1';
        } else {
            $this->db->trans_rollback();
            echo '0';
        }

        // kirim email

        $this->db->trans_commit();

        intval($this->db->trans_status());
    }

    function saveexcel() {
        $file = 'file';

        $config['upload_path'] = './file/excel/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = '5000';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($file)) {
            $error = array('error' => $this->upload->display_errors());
            return false;
        } else {
            $upload = array('upload_data' => $this->upload->data());
            $this->load->library('PHPExcel');

            $file = $upload['upload_data']['full_path'];

            try {
                $inputFileType = PHPExcel_IOFactory::identify($file);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $excel = $objReader->load($file);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }

            $aSheet = [
                    ['sheet' => 'Data Pribadi', 'type' => '0'],
                    ['sheet' => 'Data Keluarga', 'type' => '1'],
                    ['sheet' => 'Harta Tidak Bergerak', 'type' => '1'],
                    ['sheet' => 'Harta Bergerak', 'type' => '1'],
                    ['sheet' => 'Harta Bergerak Lain', 'type' => '1'],
                    ['sheet' => 'Surat Berharga', 'type' => '1'],
                    ['sheet' => 'Kas', 'type' => '1'],
                    ['sheet' => 'Hutang', 'type' => '1'],
                    ['sheet' => 'Penerimaan Kas', 'type' => '1'],
                    ['sheet' => 'Pengeluaran Kas', 'type' => '1']
            ];

            $data = [];
            $data['file'] = $upload['upload_data']['orig_name'];
            $data['sheet'] = [];

            $error = [];
            $error['sheet'] = [];
            foreach ($aSheet as $row) {
                $namaSheet = $row['sheet'];
                $sheet = $excel->getSheetByName($namaSheet);
                if (empty($sheet)) {
                    $error['sheet'][] = 'Sheet ' . $namaSheet . ' tidak tersedia!';
                } else {
                    switch ($namaSheet) {
                        case 'Data Pribadi':
                            $aColumn = [
                                    ['nama' => 'Nama Lengkap', 'field' => 'NAMA_LENGKAP'],
                                    ['nama' => 'Gelar', 'field' => 'GELAR_BELAKANG'],
                                    ['nama' => 'Jenis Kelamin', 'field' => 'JENIS_KELAMIN'],
                                    ['nama' => 'Tempat Lahir', 'field' => 'TEMPAT_LAHIR'],
                                    ['nama' => 'Tanggal Lahir', 'field' => 'TANGGAL_LAHIR'],
                                    ['nama' => 'NIK', 'field' => 'NIK'],
                                    ['nama' => 'NPWP', 'field' => 'NPWP'],
                                    ['nama' => 'Status Perkawinan', 'field' => 'STATUS_PERKAWINAN'],
                                    ['nama' => 'Agama', 'field' => 'AGAMA'],
                                    ['nama' => 'Kerja', 'field' => 'JABATAN'],
                                // ['nama' => 'Jabatan',               'field' => 'JABATAN'],
                                // ['nama' => 'Eselon',                'field' => ''],
                                // ['nama' => 'Lembaga',               'field' => ''],
                                // ['nama' => 'Unit Kerja',            'field' => ''],
                                // ['nama' => 'Alamat Kantor',         'field' => ''],
                                // ['nama' => 'Email Kantor',          'field' => ''],
                                // ['nama' => 'Jabatan',               'field' => ''],
                                // ['nama' => 'Eselon',                'field' => ''],
                                // ['nama' => 'Lembaga',               'field' => ''],
                                // ['nama' => 'Unit Kerja',            'field' => ''],
                                // ['nama' => 'Alamat Kantor',         'field' => ''],
                                // ['nama' => 'Email Kantor',          'field' => ''],
                                ['nama' => 'Alamat Rumah', 'field' => 'ALAMAT_RUMAH'],
                                    ['nama' => 'Provinsi', 'field' => 'PROVINSI'],
                                    ['nama' => 'Kota', 'field' => 'KABKOT'],
                                    ['nama' => 'Kecamatan', 'field' => 'KECAMATAN'],
                                    ['nama' => 'Telp Rumah', 'field' => 'TELPON_RUMAH'],
                                    ['nama' => 'Email Pribadi', 'field' => 'EMAIL_PRIBADI'],
                                    ['nama' => 'No HP', 'field' => 'HP']
                            ];

                            $type = $row['type'];
                            $highestRow = $sheet->getHighestRow();

                            $data['sheet'][$namaSheet] = $this->parseExcel($sheet, $namaSheet, $type, $aColumn, $highestRow);
                            break;
                        case 'Data Keluarga':
                            $aColumn = ['Nama', 'Hubungan', 'Tempat Lahir', 'Tanggal Lahir', 'Tempat Nikah', 'Tanggal Nikah', 'Pekerjaan', 'Alamat Rumah', 'Telpon'];

                            $type = $row['type'];
                            $highestRow = $sheet->getHighestRow();

                            $data['sheet'][$namaSheet] = $this->parseExcel($sheet, $namaSheet, $type, $aColumn, $highestRow, 'J');
                            break;
                    }
                }
            }

            $data['error'] = $error;

            echo json_encode($data);
        }
    }

    private function parseExcel($sheet, $namaSheet, $type, $aColumn, $highestRow, $highestColumn = NULL) {
        $data = [];

        if ($type == '0') {
            $data['type'] = $type;
            $sheet = $rowData = $sheet->rangeToArray('A4:' . 'C' . $highestRow);
            $nama = array_column($sheet, 0);

            $data['error']['column'] = [];
            foreach ($aColumn as $key => $row) {
                $cNama = $row['nama'];

                if ($cNama == 'Kerja') {
                    $tmpC = ['Jabatan', 'Eselon', 'Unit Kerja', 'Alamat Kantor', 'Email Kantor'];
                    $tmp = [];
                    foreach ($tmpC as $keys => $rows) {
                        $input = preg_quote($rows, '~');
                        $result = preg_grep('~' . $input . '~', $nama);
                        $i = 0;
                        foreach ($result as $key1 => $value1) {
                            $tmp[$i][$rows] = $sheet[$key1][2];
                            $i++;
                        }
                    }

                    $data['data']['kerja'] = $tmp;
                } else {
                    $index = array_search($cNama, $nama);

                    if (!is_null($index)) {
                        $data['data'][] = ['name' => $cNama, 'value' => $sheet[$index][2]];
                    } else {
                        $data['error']['column'][] = 'Tidak Terdapat field ' . $cNama;
                    }
                }
            }
        } else if ($type == '1') {
            $data['type'] = $type;
            $data['error']['column'] = [];
            $sheet = $rowData = $sheet->rangeToArray('A4:' . $highestColumn . $highestRow);
            if (!isset($sheet[0])) {
                $data['error']['column'][] = 'Sheet Kosong';
            } else {
                $jumlah = count($aColumn);
                $header = $sheet[0];
                $tmp = [];

                $aIndex = [];
                $tmp[0] = [];
                foreach ($aColumn as $key => $value) {
                    $tmp[0][] = $value;
                    $index = array_search($value, $header);

                    if (!empty($index)) {
                        $aIndex[] = $index;
                    } else {
                        $data['error']['column'][] = 'Tidak Terdapat Column ' . $value;
                    }
                }

                foreach ($sheet as $key => $value) {
                    if ($key != 0) {
                        if ($value[0] != '') {
                            for ($i = 1; $i <= $jumlah; $i++) {
                                if (in_array($i, $aIndex)) {
                                    $tmp[$key][$i] = $sheet[$key][$i];
                                } else {
                                    $tmp[$key][$i] = NULL;
                                }
                            }
                        }
                    }
                }

                $data['data'] = $tmp;
            }
        }

        return $data;
    }

    /** Form Tambah Lhkpn
     * 
     * @return html form tambah Lhkpn
     */
    function addlhkpn() {
        $this->load->model('mlhkpn', '', TRUE);
        $data = array(
            'form' => 'add',
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    /** Form Import Excel
     * 
     * @return html form Form Excel
     */
    function importexcel() {
        $this->load->model('mlhkpn', '', TRUE);
        $data = array(
            'form' => 'add',
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form_excel', $data);
    }

    /** Form Edit Lhkpn
     * 
     * @return html form edit Lhkpn
     */
    function editlhkpn($id) {
        $this->load->model('mlhkpn', '', TRUE);
        $data = array(
            'form' => 'edit',
            'item' => $this->mlhkpn->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    /** Form Konfirmasi Hapus Lhkpn
     * 
     * @return html form konfirmasi hapus Lhkpn
     */
    function deletelhkpn($id) {
        $this->load->model('mlhkpn', '', TRUE);
        $data = array(
            'form' => 'delete',
            'item' => $this->mglobal->get_data_all('T_LHKPN', NULL, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'")[0],
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    /** Detail Lhkpn
     * 
     * @return html detail Lhkpn
     */
    function detaillhkpn($id) {
        $this->load->model('mlhkpn', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'")[0],
                //$this->mlhkpn->get_by_id($id)->row(),
        );
        echo $this->db->last_query();
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    public function forCek($table, $id) {
        switch ($table) {
            case '1':
                $table = 'T_LHKPN_HARTA_TIDAK_BERGERAK';
                break;
            case '2':
                $table = 'T_LHKPN_HARTA_BERGERAK';
                break;
            case '3':
                $table = 'T_LHKPN_HARTA_BERGERAK_LAIN';
                break;
            case '4':
                $table = 'T_LHKPN_HARTA_LAINNYA';
                break;
            case '5':
                $table = 'T_LHKPN_HARTA_SURAT_BERHARGA';
                break;
            case '6':
                $table = 'T_LHKPN_HARTA_KAS';
                break;
        }
        $status = $this->mglobal->update($table, ['IS_CHECKED' => '1'], ['ID' => $id]);
        echo $status;
    }

    public function removeHarta($idTable) {
        switch ($idTable) {
            case '4':
                $table = 'T_LHKPN_HARTA_TIDAK_BERGERAK';
                break;
            case '5':
                $table = 'T_LHKPN_HARTA_BERGERAK';
                break;
            case '6':
                $table = 'T_LHKPN_HARTA_BERGERAK_LAIN';
                break;
            case '7':
                $table = 'T_LHKPN_HARTA_SURAT_BERHARGA';
                break;
            case '8':
                $table = 'T_LHKPN_HARTA_KAS';
                break;
            case '9':
                $table = 'T_LHKPN_HARTA_LAINNYA';
                break;
        }

        $id = implode(',', $this->input->post('chk'));

        $status = $this->mglobal->delete($table, ['1' => '1'], 'ID IN (' . $id . ')');

        echo json_encode(['table' => $idTable, 'status' => $status]);
    }

    function entry($id_lhkpn = null, $mode, $show = false) {
        $this->load->model('mlhkpnkeluarga');
        $this->load->model('mlhkpn', '', TRUE);
        $this->load->model('mlhkpn_lampiran2', '', TRUE);
        $this->load->model('mlhkpndokpendukung', '', TRUE);

        $check = @$this->mglobal->get_data_all('T_LHKPNOFFLINE_PENUGASAN_ENTRY', null, ['IS_ACTIVE' => '1', 'SUBSTRING(md5(ID_LHKPN), 6, 8) =' => $id_lhkpn], 'ID_TUGAS, IS_READ,ID_LHKPN')[0];

        if (!empty($check)) {
            if ($check->IS_READ == '0') {
                $data = array(
                    'IS_READ' => '1'
                );
                $datas = array(
                    'STAT' => '3'
                );
                $this->mglobal->update('T_LHKPNOFFLINE_PENUGASAN_ENTRY', $data, ['ID_TUGAS  ' => $check->ID_TUGAS]);
                $this->mglobal->update('T_LHKPNOFFLINE_PENERIMAAN', $datas, ['ID_LHKPN  ' => $check->ID_LHKPN]);
            }
        }

        // cek jika $id_lhkpn null
        if ($id_lhkpn == null) {
            show_error('invalid url', 404);
            die('invalid url');
        }

        $this->data['icon'] = 'fa-book';
        $this->data['is_pn'] = $this->session->userdata('IS_PN');
        $this->data['title'] = 'Entry LHKPN';
        $breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
        $breadcrumbitem[] = ['E Filling' => 'index.php/dashboard/efilling'];
        $breadcrumbitem[] = [ucwords(strtolower(__CLASS__)) => $this->segmentTo[2]];
        $breadcrumbitem[] = [$this->data['title'] => @$this->segmentTo[4]];
        $breadcrumbdata = [];
        foreach ($breadcrumbitem as $list) {
            $breadcrumbdata = array_merge($breadcrumbdata, $list);
        }
        $joinMATA_UANG = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                // ['table' => 'M_JENIS_HARTA'      , 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_KAS.KODE_JENIS', 'join' => 'left'],
        ];
        $joinHARTA_TIDAK_BERGERAK = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.ID = ID_NEGARA', 'join' => 'left'],
                ['table' => 'M_AREA as area', 'on' => 'area.IDKOT = ID_NEGARA AND area.IDPROV = data.PROV', 'join' => 'left'],
            // ['table' => 'M_KABKOT as kabkot'      , 'on' => 'kabkot.IDKOT   = data.KAB_KOT' ,   'join'  =>  'left'],
            // ['table' => 'M_PROVINSI as provinsi'  , 'on' => 'provinsi.IDPROV = data.PROV'   ,   'join'  =>  'left']
            ['table' => 'T_VERIFICATION_ITEM', 'on' => 'T_VERIFICATION_ITEM.ID_LHKPN = data.ID_LHKPN AND T_VERIFICATION_ITEM.ID = data.ID', 'join' => 'left']
        ];
        $joinHARTA_BERGERAK = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'T_VERIFICATION_ITEM', 'on' => 'T_VERIFICATION_ITEM.ID_LHKPN = T_LHKPN_HARTA_BERGERAK.ID_LHKPN AND T_VERIFICATION_ITEM.ID = T_LHKPN_HARTA_BERGERAK.ID', 'join' => 'left']
        ];
        $joinHARTA_PERABOTAN = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_BERGERAK_LAIN.KODE_JENIS', 'join' => 'left'],
                ['table' => 'T_VERIFICATION_ITEM', 'on' => 'T_VERIFICATION_ITEM.ID_LHKPN = T_LHKPN_HARTA_BERGERAK_LAIN.ID_LHKPN AND T_VERIFICATION_ITEM.ID = T_LHKPN_HARTA_BERGERAK_LAIN.ID', 'join' => 'left']
        ];
        $joinHARTA_SURAT = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_SURAT_BERHARGA.KODE_JENIS', 'join' => 'left'],
                ['table' => 'T_VERIFICATION_ITEM', 'on' => 'T_VERIFICATION_ITEM.ID_LHKPN = T_LHKPN_HARTA_SURAT_BERHARGA.ID_LHKPN AND T_VERIFICATION_ITEM.ID = T_LHKPN_HARTA_SURAT_BERHARGA.ID', 'join' => 'left']
        ];
        $joinHARTA_KAS = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_KAS.KODE_JENIS', 'join' => 'left'],
                ['table' => 'T_VERIFICATION_ITEM', 'on' => 'T_VERIFICATION_ITEM.ID_LHKPN = T_LHKPN_HARTA_KAS.ID_LHKPN AND T_VERIFICATION_ITEM.ID = T_LHKPN_HARTA_KAS.ID', 'join' => 'left']
        ];
        $joinHARTA_LAINNYA = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_LAINNYA.KODE_JENIS', 'join' => 'left'],
                ['table' => 'T_VERIFICATION_ITEM', 'on' => 'T_VERIFICATION_ITEM.ID_LHKPN = T_LHKPN_HARTA_LAINNYA.ID_LHKPN AND T_VERIFICATION_ITEM.ID = T_LHKPN_HARTA_LAINNYA.ID', 'join' => 'left']
        ];
        $joinHARTA_HUTANG = [
                ['table' => 'T_VERIFICATION_ITEM', 'on' => 'T_VERIFICATION_ITEM.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN AND T_VERIFICATION_ITEM.ID = T_LHKPN_HUTANG.ID_HUTANG', 'join' => 'left']
        ];
        $where_eHARTA_TIDAK_BERGERAK = "SUBSTRING(md5(data.ID_LHKPN), 6, 8) = '$id_lhkpn'";
        // $where_eHARTA_TIDAK_BERGERAK= "(provinsi.IDPROV = kabkot.IDPROV OR data.NEGARA = '1') and SUBSTRING(md5(data.ID_LHKPN), 6, 8) = '$id_lhkpn'";
        $KABKOT = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as KAB_KOT";
        $PROV = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND '' = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as PROV";
        $selectHARTA_TIDAK_BERGERAK = 'IS_CHECKED, data.NEGARA AS ID_NEGARA, NAMA_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEC as KEC, data.KEL as KEL,' . $KABKOT . ',' . $PROV . ', data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
        // $selectHARTA_TIDAK_BERGERAK = 'data.NEGARA AS ID_NEGARA, NAMA_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, (SELECT NAME from M_AREA where IDPROV = data.PROV AND CAST(IDKOT as UNSIGNED) = data.KAB_KOT AND IDKEC = data.KEC AND IDKEL = data.KEL) as KEL, (SELECT NAME from M_AREA where IDPROV = data.PROV AND CAST(IDKOT as UNSIGNED) = data.KAB_KOT AND IDKEC = data.KEC LIMIT 1) AS KEC, kabkot.NAME as KAB_KOT, provinsi.NAME as PROV,  data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
        //jenis bukti
        $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI', NULL, ['ID_JENIS_BUKTI', 'DESC']);
        $list_bukti = [];
        foreach ($jenis_bukti as $key) {
            $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
        }
        //jenis Harta
        $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA', NULL, ['ID_JENIS_HARTA', 'DESC']);
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

        $this->data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);
        $data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
        $data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');

        $this->data['list_harta'] = $list_harta;
        $this->data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'")[0];
        $this->data['id_lhkpn'] = $this->data['LHKPN']->ID_LHKPN;
        $this->data['status_lhkpn'] = $this->data['LHKPN']->STATUS;
        $this->data['hartirak'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_TIDAK_BERGERAK', 'NILAI_PELAPORAN', 'sum_hartirak');
        $this->data['harger'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_BERGERAK', 'NILAI_PELAPORAN', 'sum_harger');
        $this->data['harger2'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_BERGERAK_LAIN', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harger2');
        $this->data['suberga'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_SURAT_BERHARGA', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_suberga');
        $this->data['kaseka'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_KAS', "REPLACE(NILAI_EQUIVALEN,'.','')", 'sum_kaseka');
        $this->data['harlin'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HARTA_LAINNYA', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harlin');
        $this->data['_hutang'] = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'], 'T_LHKPN_HUTANG', 'SALDO_HUTANG', 'sum_hutang');
        $this->data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
        $this->data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
        $this->data['DATA_PRIBADI'] = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'")[0];
        $this->data['KAS'] = @$this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', NULL, "FILE_BUKTI <> '' AND KODE_JENIS <> '1'", '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // echo $this->db->last_query();
        // echo '<pre>';
        // print_r($this->data['tmpData']);
        // exit();

        if ($this->data['status_lhkpn'] == '2') {
            
        }

        $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN';
        $joinJabatan = [
                ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
                ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
        ];
        $this->data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "SUBSTRING(md5(T_LHKPN_JABATAN.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // echo '<pre>';
        // print_r($this->data['REKENINGS']);
        // exit();
        $this->data['lembaga'] = @$this->mglobal->get_data_all('M_INST_SATKER', NULL, NULL, '*', NULL);
        // $this->data['jenisharta']               = @$this->mglobal->get_data_all('M_JENIS_HARTA', NULL, ['GOLONGAN' => '5'], '*', NULL);
        $this->data['rinci_keluargas'] = $this->mlhkpnkeluarga->get_rincian($this->data['id_lhkpn']);
        $this->data['KELUARGAS'] = $this->mglobal->get_data_all('T_LHKPN_KELUARGA', NULL, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_TIDAK_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, '(ITEMVER = "hartatidakbergerak" OR ITEMVER IS NULL)', [$selectHARTA_TIDAK_BERGERAK, FALSE], $where_eHARTA_TIDAK_BERGERAK, ['HASIL', 'ASC']);
        $this->data['HARTA_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinHARTA_BERGERAK, '(ITEMVER = "hartabergerak" OR ITEMVER IS NULL)', '*, T_LHKPN_HARTA_BERGERAK.ID as ID_HARTA_BERGERAK', "SUBSTRING(md5(T_LHKPN_HARTA_BERGERAK.ID_LHKPN), 6, 8) = '$id_lhkpn'", ['HASIL', 'ASC']);
        $this->data['HARTA_BERGERAK_LAINS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinHARTA_PERABOTAN, '(ITEMVER = "hartabergerakperabot" OR ITEMVER IS NULL)', '*, T_LHKPN_HARTA_BERGERAK_LAIN.ID as ID_HARTA_BERGERAK2', "SUBSTRING(md5(T_LHKPN_HARTA_BERGERAK_LAIN.ID_LHKPN), 6, 8) = '$id_lhkpn'", ['HASIL', 'ASC']);
        $this->data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinHARTA_SURAT, '(ITEMVER = "suratberharga" OR ITEMVER IS NULL)', "*,REPLACE(NILAI_PELAPORAN,'.','') as PELAPORAN, T_LHKPN_HARTA_SURAT_BERHARGA.ID as ID_SURAT_BERHARGA", "SUBSTRING(md5(T_LHKPN_HARTA_SURAT_BERHARGA.ID_LHKPN), 6, 8) = '$id_lhkpn'", ['HASIL', 'ASC']);
        // echo $this->db->last_query();
        $this->data['HARTA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinHARTA_KAS, '(ITEMVER = "kas" OR ITEMVER IS NULL)', '*, T_LHKPN_HARTA_KAS.ID as ID_KAS', "SUBSTRING(md5(T_LHKPN_HARTA_KAS.ID_LHKPN), 6, 8) = '$id_lhkpn'", ['HASIL', 'ASC']);
        $this->data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinHARTA_LAINNYA, '(ITEMVER = "hartalainnya" OR ITEMVER IS NULL)', '*,T_LHKPN_HARTA_LAINNYA.ID as ID_HARTA_LAINNYA', "SUBSTRING(md5(T_LHKPN_HARTA_LAINNYA.ID_LHKPN), 6, 8) = '$id_lhkpn'", ['HASIL', 'ASC']);
        $this->data['HUTANGS'] = $this->mglobal->get_data_all('T_LHKPN_HUTANG', $joinHARTA_HUTANG, '(ITEMVER = "hutang" OR ITEMVER IS NULL)', '*', "SUBSTRING(md5(T_LHKPN_HUTANG.ID_LHKPN), 6, 8) = '$id_lhkpn'", ['HASIL', 'ASC']);
        $this->data['PENERIMAAN_KASS'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
        $this->data['PENGELUARAN_KASS'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
        $this->data['lamp2s'] = $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['keluargas'] = $this->mlhkpnkeluarga->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $id_lhkpn))->result();
        $this->data['dokpendukungs'] = $this->mlhkpndokpendukung->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $id_lhkpn))->result();
        $this->data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
        $this->data['list_bukti'] = $list_bukti;
        $this->data['pemanfaatan1'] = $this->daftar_pemanfaatan(1);
        $this->data['pemanfaatan2'] = $this->daftar_pemanfaatan(2);

        // SELECT item verification
        $verificationItem = $this->mglobal->get_data_all('T_VERIFICATION_ITEM', null, null, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        foreach ($verificationItem as $key) {
            $this->data['verifItem'][$key->ITEMVER][$key->ID] = ['hasil' => $key->HASIL, 'catatan' => $key->CATATAN];
        }
        // SELECT hasil verifikasi
        $this->data['hasilVerifikasi'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1'], 'HASIL_VERIFIKASI', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'")[0]->HASIL_VERIFIKASI);

        //select lampiran pelepasan
        $selectlampiranpelepasan = 'A.JENIS_PELEPASAN_HARTA, A.TANGGAL_TRANSAKSI as TANGGAL_TRANSAKSI, A.NILAI_PELEPASAN as NILAI_PELEPASAN, A.NAMA as NAMA, A.ALAMAT as ALAMAT';
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
        $pelepasanmanual = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_MANUAL as A', NULL, NULL, '*', "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasan = [];

        //packing hasil calling data lampiran pelepasan
        if (!empty($pelepasanhartatidakbergerak)) {
            foreach ($pelepasanhartatidakbergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "Tanah/Bangunan , Atas Nama " . @$key->ATAS_NAMA . " dengan luas tanah " . @$key->LUAS_TANAH . " dan luas bangunan " . @$key->LUAS_BANGUNAN . " dengan bukti berupa " . $list_bukti[$key->JENIS_BUKTI] . " dengan nomor bukti " . @$key->NOMOR_BUKTI,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                    'STATUS' => '0'
                ];
            }
        }
        if (!empty($pelepasanhartabergerak)) {
            foreach ($pelepasanhartabergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "Sebuah " . $list_harta[@$key->KODE_JENIS] . " , Atas Nama " . @$key->ATAS_NAMA . " , merek " . @$key->MEREK . " dengan nomor registrasi " . $key->NOPOL_REGISTRASI . " dan nomor bukti " . @$key->NOMOR_BUKTI,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                    'STATUS' => '0'
                ];
            }
        }
        if (!empty($pelepasanhartabergeraklain)) {
            foreach ($pelepasanhartabergeraklain as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => $list_harta_berhenti[@$key->KODE_JENIS] . " bernama " . @$key->NAMA_HARTA . " , Atas nama " . @$key->ATAS_NAMA . " dengan jumlah " . @$key->JUMLAH . ' ' . @$key->SATUAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                    'STATUS' => '0'
                ];
            }
        }
        if (!empty($pelepasansuratberharga)) {
            foreach ($pelepasansuratberharga as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => $list_harta_surat[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' berupa surat ' . @$key->NAMA_SURAT . ' dengan jumlah ' . @$key->JUMLAH . ' ' . @$key->SATUAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                    'STATUS' => '0'
                ];
            }
        }
        if (!empty($pelepasankas)) {
            foreach ($pelepasankas as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "KAS berupa " . $list_harta_kas[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' pada bank ' . @$key->NAMA_BANK . ' dengan nomor rekening ' . @$key->NOMOR_REKENING,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                    'STATUS' => '0'
                ];
            }
        }
        if (!empty($pelepasanhartalainnya)) {
            foreach ($pelepasanhartalainnya as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "Harta lain berupa " . $list_harta_lain[@$key->KODE_JENIS] . ' dengan nama harta ' . @$key->NAMA_HARTA . ' atas nama ' . @$key->ATAS_NAMA,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                    'STATUS' => '0'
                ];
            }
        }

        if (!empty($pelepasanmanual)) {
            foreach ($pelepasanmanual as $key) {
                $pelepasan[] = [
                    'ID' => $key->ID,
                    'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => $key->URAIAN_HARTA,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                    'STATUS' => '1'
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
        $this->data['mode'] = $mode;

        // echo "<pre>";
        // print_r ($this->data['getPenka']);
        // echo "</pre>";
        // $this->data['']  = $this->mglobal->get_data_all('T_LHKPN_PENERIMAAN_KAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // $this->data['PENGELUARAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENGELUARAN_KAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // load view

        $agenda = date('Y', strtotime($this->data['LHKPN']->TGL_LAPOR)) . '/' . ($this->data['LHKPN']->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $this->data['LHKPN']->NIK . '/' . $this->data['LHKPN']->ID_LHKPN;
        $this->data['title'] = 'Entry LHKPN';

        $this->data['tampil'] = 'LHKPN : ' . @$this->data['DATA_PRIBADI']->NAMA_LENGKAP . ' (' . @$this->data['DATA_PRIBADI']->NIK . ') - ' . $agenda;

        $this->data['lampiran_hibah'] = $this->_lampiran_hibah($id_lhkpn);
        $this->data['show'] = $show;
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__) . '2', $this->data);
    }

    private function _lampiran_hibah($id_lhkpn, $where = NULL) {
        if (is_null($where)) {
            $where = '';
        }
        $result = $this->db->query("
          SELECT
            'Tanah / Bangunan' as kode,
            TANGGAL_TRANSAKSI as tgl,
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
            TANGGAL_TRANSAKSI as tgl,
            CONCAT('Sebuah ',C.NAMA,' , Atas Nama ',A.NAMA,' , merek ',MEREK,' dengan nomor polisi ',NOPOL_REGISTRASI,' dan nomor bukti ',NOMOR_BUKTI) as uraian,
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
            TANGGAL_TRANSAKSI as tgl,
            CONCAT(
              CASE
                WHEN KODE_JENIS LIKE '%1%' THEN 'Perabotan Rumah Tangga'
                WHEN KODE_JENIS LIKE '%2%' THEN 'Barang Elektronik'
                WHEN KODE_JENIS LIKE '%3%' THEN 'Perhiasan & Logam / Batu Mulia'
                WHEN KODE_JENIS LIKE '%4%' THEN 'Barang Seni'
                WHEN KODE_JENIS LIKE '%5%' THEN 'Persediaan'
                WHEN KODE_JENIS LIKE '%6%' THEN 'Harta Bergerak Lainnya'
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
            TANGGAL_TRANSAKSI as tgl,
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
            TANGGAL_TRANSAKSI as tgl,
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
            TANGGAL_TRANSAKSI as tgl,
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

    function tandaterima() {
        $data = '';
        // load view
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    /* ############################### DATA KELUARGA INTI #################################### */

    /** Form Tambah Keluarga */
    function addkeluarga($id_lhkpn = null) {
        $data = array(
            'form' => 'add',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_keluarga_form', $data);
    }

    /** Form Edit Keluarga */
    function editkeluarga($id) {
        $this->load->model('mlhkpnkeluarga', '', TRUE);
        $item = $this->mlhkpnkeluarga->get_by_id($id)->row();
        $nik = 'nik';
        $join = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $item->ID_LHKPN;
        $select = 'pn.NIK';
        $dataPN = @$this->mglobal->get_data_all('T_LHKPN as lhkpn', $join, $where, $select)[0];
        if (@$dataPN->NIK != '') {
            $nik = $dataPN->NIK;
        }

        $data = array(
            'form' => 'edit',
            'NIK' => $nik,
            'item' => $item,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_keluarga_form', $data);
    }

    /** Form Konfirmasi Hapus Keluarga */
    function deletekeluarga($id) {
        $this->load->model('mlhkpnkeluarga', '', TRUE);
        $data = array(
            'form' => 'delete',
            'item' => $this->mlhkpnkeluarga->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_keluarga_form', $data);
    }

    /** Detail Keluarga */
    function detailkeluarga($id) {
        $this->load->model('mlhkpnkeluarga', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mlhkpnkeluarga->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_keluarga_form', $data);
    }

    //upload KK
    function saveKK() {
        $this->db->trans_begin();
        $type_file = array('.jpg', '.png', '.jpeg', '.pdf', '.jpeg');
        $id = 'unknown';
        $url = '';
        $maxsize = 2000000;
        $id_lhkpn = $this->input->post('id_lhkpn', TRUE);

        $user = $this->session->userdata('USR');
        $join = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $id_lhkpn;
        $select = 'pn.NIK';
        $dataPN = $this->mglobal->get_data_all('T_LHKPN as lhkpn', $join, $where, $select)[0];

        if (@$dataPN->NIK != '') {
            $id = $dataPN->NIK;
        }

        $filename = 'uploads/data_keluarga/' . $id . '/readme.txt';

        if (!file_exists($filename)) {

            $dir = './uploads/data_keluarga/' . $id . '/';

            $file_to_write = 'readme.txt';
            $content_to_write = "KK Dari " . $user . ' dengan nik ' . $id;

            if (is_dir($dir) === false) {
                mkdir($dir);
            }

            $file = fopen($dir . '/' . $file_to_write, "w");

            fwrite($file, $content_to_write);

            // closes the file
            fclose($file);
        }
        $filependukung = (isset($_FILES['FILE_FOTO'])) ? $_FILES['FILE_FOTO'] : '';
        $extension = strtolower(@substr(@$filependukung['name'], -4));
        $del = FALSE;

        $url_file = $this->input->post('OLD_FILE', TRUE);
        if ($url_file == '' && $filependukung['error'] == 0) {
            if (in_array($extension, $type_file) && $maxsize >= $filependukung['size']) {
                $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/data_keluarga/" . $id . "/", 0, 10000);
                if ($filependukung['size'] == '') {
                    $url = '';
                } else {
                    $url = time() . "-" . trim($filependukung['name']);
                }
                $data['FILE_KK'] = $url;
                $result = $this->mglobal->update('T_LHKPN', $data, ['id_lhkpn' => $id_lhkpn]);
            }
            //update LHKPN
        } elseif ($url_file != '' && $filependukung['error'] == 0) {
            if ($filependukung['error'] == 0) {
                if (in_array($extension, $type_file) && $maxsize >= $filependukung['size']) {
                    $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/data_keluarga/" . $id . "/", 0, 10000);
                    $url = time() . "-" . trim($filependukung['name']);
                    $data['FILE_KK'] = @$url;
                    $result = $this->mglobal->update('T_LHKPN', $data, ['id_lhkpn' => $id_lhkpn]);
                    unlink("./uploads/data_keluarga/" . $id . "/$url_file");
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Process Insert, Update, Delete Keluarga */
    function savekeluarga() {
        $this->load->model('mlhkpnkeluarga', 'Mlhkpnkeluarga', TRUE);
        $this->db->trans_begin();
        if ($this->input->post('act', TRUE) == 'doinsert') {

            $keluarga = array(
                'ID_KELUARGA' => $this->input->post('ID_KELUARGA', TRUE),
                'ID_KELUARGA_LAMA' => $this->input->post('ID_KELUARGA_LAMA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'NAMA' => $this->input->post('NAMA', TRUE),
                'HUBUNGAN' => $this->input->post('HUBUNGAN', TRUE),
                'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR', TRUE),
                'TANGGAL_LAHIR' => $this->input->post('TANGGAL_LAHIR', TRUE),
                'JENIS_KELAMIN' => $this->input->post('JENIS_KELAMIN', TRUE),
                'PEKERJAAN' => $this->input->post('PEKERJAAN', TRUE),
                'ALAMAT_RUMAH' => $this->input->post('ALAMAT_RUMAH', TRUE),
                'NOMOR_TELPON' => $this->input->post('NOMOR_TELPON', TRUE),
                'IS_ACTIVE' => 1,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    // 'UPDATED_TIME'     => time(),
                    // 'UPDATED_BY'     => $this->session->userdata('USR'),
                    // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
            );
            if ($this->input->post('HUBUNGAN') == '1' || $this->input->post('HUBUNGAN') == '2') {
                if ($this->input->post('STATUS_HUBUNGAN') == '1') {
                    $keluarga['STATUS_HUBUNGAN'] = $this->input->post('STATUS_HUBUNGAN', TRUE);
                    $keluarga['TEMPAT_NIKAH'] = $this->input->post('TEMPAT_NIKAH', TRUE);
                    $keluarga['TANGGAL_NIKAH'] = $this->input->post('TANGGAL_NIKAH', TRUE);
                } else {
                    $keluarga['STATUS_HUBUNGAN'] = $this->input->post('STATUS_HUBUNGAN', TRUE);
                    $keluarga['TEMPAT_NIKAH'] = $this->input->post('TEMPAT_NIKAH', TRUE);
                    $keluarga['TANGGAL_NIKAH'] = $this->input->post('TANGGAL_NIKAH', TRUE);
                    $keluarga['TEMPAT_CERAI'] = $this->input->post('TEMPAT_CERAI', TRUE);
                    $keluarga['TANGGAL_CERAI'] = $this->input->post('TANGGAL_CERAI', TRUE);
                }
            }
            $this->Mlhkpnkeluarga->save($keluarga);
        } else if ($this->input->post('act', TRUE) == 'doupdate') {

            $keluarga = array(
                'ID_KELUARGA_LAMA' => $this->input->post('ID_KELUARGA_LAMA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'NAMA' => $this->input->post('NAMA', TRUE),
                'HUBUNGAN' => $this->input->post('HUBUNGAN', TRUE),
                'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR', TRUE),
                'TANGGAL_LAHIR' => $this->input->post('TANGGAL_LAHIR', TRUE),
                'JENIS_KELAMIN' => $this->input->post('JENIS_KELAMIN', TRUE),
                'PEKERJAAN' => $this->input->post('PEKERJAAN', TRUE),
                'ALAMAT_RUMAH' => $this->input->post('ALAMAT_RUMAH', TRUE),
                'NOMOR_TELPON' => $this->input->post('NOMOR_TELPON', TRUE),
                'IS_ACTIVE' => 1,
                //'CREATED_TIME'     	=> time(),
                //'CREATED_BY'     	=> $this->session->userdata('USR'),
                //'CREATED_IP'    	=> $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            if ($this->input->post('HUBUNGAN') == '1' || $this->input->post('HUBUNGAN') == '2') {
                if ($this->input->post('STATUS_HUBUNGAN') == '1') {
                    $keluarga['STATUS_HUBUNGAN'] = $this->input->post('STATUS_HUBUNGAN', TRUE);
                    $keluarga['TEMPAT_NIKAH'] = $this->input->post('TEMPAT_NIKAH', TRUE);
                    $keluarga['TANGGAL_NIKAH'] = $this->input->post('TANGGAL_NIKAH', TRUE);
                } else {
                    $keluarga['STATUS_HUBUNGAN'] = $this->input->post('STATUS_HUBUNGAN', TRUE);
                    $keluarga['TEMPAT_NIKAH'] = $this->input->post('TEMPAT_NIKAH', TRUE);
                    $keluarga['TANGGAL_NIKAH'] = $this->input->post('TANGGAL_NIKAH', TRUE);
                    $keluarga['TEMPAT_CERAI'] = $this->input->post('TEMPAT_CERAI', TRUE);
                    $keluarga['TANGGAL_CERAI'] = $this->input->post('TANGGAL_CERAI', TRUE);
                }
            }
            $keluarga['ID_KELUARGA'] = $this->input->post('ID_KELUARGA', TRUE);

            $this->Mlhkpnkeluarga->update($keluarga['ID_KELUARGA'], $keluarga);
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $keluarga['ID_KELUARGA'] = $this->input->post('ID_KELUARGA', TRUE);
            $this->Mlhkpnkeluarga->delete($keluarga['ID_KELUARGA']);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /* ###################### END DATA KELUARGA INTI ######################## */

    /** Process Insert, Update, Delete Harta Tidak Bergerak
     * 
     * @return boolean process Harta Tidak Bergerak
     */
    function savehartatidakbergerak() {
        $this->load->library('form_validation');
        $this->db->trans_begin();
        $this->load->model('mlhkpnharta', '', TRUE);
        $tabel = 'T_LHKPN_HARTA_TIDAK_BERGERAK';
        if ($this->input->post('NEGARA') == '1') {
            $ID_NEGARA = 96;
        } else {
            $ID_NEGARA = $this->input->post('KD_ISO3_NEGARA');
        }

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $this->form_validation->set_rules('JALAN', 'JALAN', 'trim|required');
            if ($this->form_validation->run() === FALSE) {
                $data = array(
                    'form' => 'add',
                );
                $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartatidakbergerak_form', $data);
            } else {
                $lhkpnharta = array(
                    'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                    'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                    'NEGARA' => $this->input->post('NEGARA', TRUE),
                    // 'ID_NEGARA'                 => $this->input->post('KD_ISO3_NEGARA', TRUE),
                    'ID_NEGARA' => $ID_NEGARA,
                    'JALAN' => $this->input->post('JALAN', TRUE),
                    'KEL' => $this->input->post('KEL', TRUE),
                    'KEC' => $this->input->post('KEC', TRUE),
                    'KAB_KOT' => $this->input->post('KAB_KOT', TRUE),
                    'PROV' => $this->input->post('PROV', TRUE),
                    'LUAS_TANAH' => str_replace('.', '', $this->input->post('LUAS_TANAH', TRUE)),
                    'LUAS_BANGUNAN' => str_replace('.', '', $this->input->post('LUAS_BANGUNAN', TRUE)),
                    'KETERANGAN' => $this->input->post('KETERANGAN', TRUE),
                    'JENIS_BUKTI' => $this->input->post('JENIS_BUKTI', TRUE),
                    'NOMOR_BUKTI' => $this->input->post('NOMOR_BUKTI', TRUE),
                    'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                    'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                    'PEMANFAATAN' => implode(',', $this->input->post('PEMANFAATAN', TRUE)),
                    'KET_LAINNYA' => $this->input->post('KET_LAINNYA', TRUE),
                    'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                    'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                    'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                    'NILAI_PEROLEHAN' => str_replace(".", "", $this->input->post('NILAI_PEROLEHAN', TRUE)),
                    'NILAI_PELAPORAN' => str_replace(".", "", $this->input->post('NILAI_PELAPORAN', TRUE)),
                    'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                    'IS_ACTIVE' => 1,
                    'IS_CHECKED' => 1,
                    'STATUS' => 3,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                        // 'UPDATED_TIME'     => time(),
                        // 'UPDATED_BY'     => $this->session->userdata('USR'),
                        // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
                );
                $id = $this->mlhkpnharta->save($lhkpnharta, $tabel);

                $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
                $tmp = [];
                foreach ($aList as $row) {
                    $tmp[] = $row['ID_ASAL_USUL'];
                }
                $aList = $tmp;

                $asal_usul = $this->input->post('ASAL_USUL', TRUE);
                $i = 0;
                foreach ($asal_usul as $key => $row) {
                    if (in_array($row, $aList)) {
                        $data = [
                            'ID_HARTA' => $id,
                            'ID_ASAL_USUL' => $row,
                            'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                            'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                            'NAMA' => $this->input->post('NAMA')[$i],
                            'ALAMAT' => $this->input->post('ALAMAT')[$i],
                            'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                        ];

                        $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK', $data);
                        $i++;
                    }
                }
            }
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $negara = $this->input->post('NEGARA', TRUE);
            $lhkpnharta = array(
                'ID' => $this->input->post('ID', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'NEGARA' => $negara,
                'ID_NEGARA' => ($negara == '1' ? $this->input->post('KD_ISO3_NEGARA', TRUE) : NULL ),
                'JALAN' => $this->input->post('JALAN', TRUE),
                'KEL' => ($negara == '1' ? NULL : $this->input->post('KEL', TRUE)),
                'KEC' => ($negara == '1' ? NULL : $this->input->post('KEC', TRUE)),
                'KAB_KOT' => ($negara == '1' ? NULL : $this->input->post('KAB_KOT', TRUE)),
                'PROV' => ($negara == '1' ? NULL : $this->input->post('PROV', TRUE)),
                'LUAS_TANAH' => str_replace('.', '', $this->input->post('LUAS_TANAH', TRUE)),
                'LUAS_BANGUNAN' => str_replace('.', '', $this->input->post('LUAS_BANGUNAN', TRUE)),
                'KETERANGAN' => $this->input->post('KETERANGAN', TRUE),
                'JENIS_BUKTI' => $this->input->post('JENIS_BUKTI', TRUE),
                'NOMOR_BUKTI' => $this->input->post('NOMOR_BUKTI', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                'PEMANFAATAN' => implode(',', $this->input->post('PEMANFAATAN', TRUE)),
                'KET_LAINNYA' => $this->input->post('KET_LAINNYA', TRUE),
                'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'NILAI_PEROLEHAN' => str_replace(".", "", $this->input->post('NILAI_PEROLEHAN', TRUE)),
                'NILAI_PELAPORAN' => str_replace(".", "", $this->input->post('NILAI_PELAPORAN', TRUE)),
                'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                'IS_ACTIVE' => 1,
                'IS_CHECKED' => 1,
                // 'CREATED_TIME'              => time(),
                // 'CREATED_BY'                => $this->session->userdata('USR'),
                // 'CREATED_IP'                => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );

            $id = $this->input->post('ID', TRUE);
            $rec = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK', NULL, ['ID' => $id], 'STATUS')[0];
            if ($rec->STATUS == '1') {
                $lhkpnharta['STATUS'] = '2';
            }

            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->update($lhkpnharta['ID'], $lhkpnharta, $tabel);

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $this->mglobal->delete('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK', ['ID_HARTA' => $id]);
            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];
                    // echo "<pre>";
                    // print_r ($data);
                    // echo "</pre>";
                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->delete($lhkpnharta['ID'], $tabel);
        } else if ($this->input->post('act') == 'dopelepasan') {
            $type = $this->input->post('type');
            if ($type == 'insert') {
                $data = [
                    'ID_HARTA' => $this->input->post('ID_HARTA'),
                    'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $result = $this->mglobal->insert('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK', $data);
                $this->mglobal->update('T_LHKPN_HARTA_TIDAK_BERGERAK', ['IS_PELEPASAN' => '1', 'NILAI_PELAPORAN' => '0', 'IS_CHECKED' => '1'], ['ID' => $this->input->post('ID_HARTA')]);
            } else {
                $data = [
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $this->mglobal->update('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK', $data, ['ID' => $this->input->post('ID')]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah Harta Tidak Bergerak
     * 
     * @return html form tambah Harta Tidak Bergerak
     */
    function addhartatidakbergerak($id_lhkpn = null) {
        $this->load->model('mrasalusul', '', TRUE);
        $uang = $this->daftar_uang();
        $manfaat = $this->daftar_pemanfaatan(1);
        $bukti = $this->daftar_bukti(1);
        $data = array(
            'form' => 'add',
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'id_lhkpn' => $id_lhkpn,
            'uang' => $uang,
            'manfaat' => $manfaat,
            'bukti' => $bukti,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartatidakbergerak_form', $data);
    }

    /** Form Edit Harta Tidak Bergerak
     * 
     * @return html form edit Harta Tidak Bergerak
     */
    function edithartatidakbergerak($id) {
        $this->load->model('mrasalusul', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $uang = $this->daftar_uang();
        $manfaat = $this->daftar_pemanfaatan(1);
        $bukti = $this->daftar_bukti(1);
        $join = [
                ['table' => 'M_ASAL_USUL', 'on' => 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK.ID_ASAL_USUL=M_ASAL_USUL.ID_ASAL_USUL']
        ];

        $data = array(
            'form' => 'edit',
            'item' => $this->mlhkpnharta->get_by_id($id)->row(),
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'pelaporan' => $this->mglobal->get_data_all('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK', $join, ['ID_HARTA' => $id]),
            'uang' => $uang,
            'manfaat' => $manfaat,
            'bukti' => $bukti,
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartatidakbergerak_form', $data);
    }

    /** Form Konfirmasi Hapus Harta Tidak Bergerak
     * 
     * @return html form konfirmasi hapus Harta Tidak Bergerak
     */
    function deletehartatidakbergerak($id) {
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);

        $join = [
                ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.ID = ID_NEGARA', 'join' => 'left'],
                ['table' => 'M_AREA as provinsi', 'on' => 'provinsi.IDPROV = ' . 'data.PROV and provinsi.IDKOT = "" and provinsi.IDKEC = "" and provinsi.IDKEL = ""', 'join' => 'left'],
                ['table' => 'M_AREA as kabkot', 'on' => 'kabkot.IDKOT   = ' . 'data.KAB_KOT and provinsi.IDPROV = ' . 'data.PROV and kabkot.IDKEC = "" and kabkot.IDKEL = ""', 'join' => 'left'],
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
        ];
        // $where['data.ID_LHKPN' ]        = $id_lhkpn;
        // $data['LHKPN']                  = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN'      , 'on' => 'T_LHKPN.ID_PN   = '.'T_PN.ID_PN']], NULL, '*',  "ID_LHKPN = '$id_lhkpn'")[0];
        // $data['asalusul']               = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER',  NULL);
        // $select                         = 'data.NEGARA AS ID_NEGARA, NAMA_NEGARA, IS_PELEPASAN, data.STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEL as KEL, data.KEC AS KEC, kabkot.NAME as KAB_KOT, provinsi.NAME as PROV,  data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
        // $join = [
        //                           // ['table' => 'M_KABKOT as kabkot'      , 'on' => 'kabkot.IDKOT   = data.KAB_KOT'],
        //                           ['table' => 'M_AREA as provinsi'        , 'on' => 'provinsi.IDPROV = '.'data.PROV and provinsi.IDKOT = "" and provinsi.IDKEC = "" and provinsi.IDKEL = ""','join' => 'left'],
        //                           ['table' => 'M_AREA as area'  , 'on' => 'area.IDPROV = data.PROV AND area.IDKOT = CAST(data.KAB_KOT AS UNSIGNED) AND area.IDKEC = data.KEC AND area.IDKEL = data.KEL'],
        //                       ];

        $where['data.ID'] = $id;
        // $where_e                   = 'provinsi.IDPROV = area.IDPROV';
        $KABKOT = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as KAB_KOT";
        // $KEC    = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND data.KEC = area.IDKEC AND '' = area.IDKEL) as KEC";
        // $KEL    = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND data.KEC = area.IDKEC AND data.KEL = area.IDKEL) as KEL";
        $select = 'data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.NEGARA as NEGARA, data.JALAN as JALAN, data.KEC, data.KEL, ' . $KABKOT . ', provinsi.NAME as PROV,  data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP, M_NEGARA.NAMA_NEGARA as NAMA_NEGARA';
        $item = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $join, $where, $select);
        // echo "<pre>".$this->db->last_query();
        // print_r ($item);
        // echo "</pre>";
        $data = array(
            'form' => 'delete',
            'item' => $item,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartatidakbergerak_form', $data);
    }

    //hapus pelepasan
    function deletepelepasantanahbangunan($id_harta, $id_pelepasan) {
        $this->db->trans_begin();
        $this->mglobal->update('T_LHKPN_HARTA_TIDAK_BERGERAK', ['IS_PELEPASAN' => '0'], ['ID' => $id_harta]);
        $this->mglobal->delete('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK', ['ID' => $id_pelepasan]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Konfirmasi Pelepasan Harta Tidak Bergerak
     *
     * @return html form konfirmasi pelepasan Harta Tidak Bergerak
     */
    function pelepasanhartatidakbergerak($id, $idlhkpn) {
        $this->load->model('mglobal', '', TRUE);

        $check = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK', NULL, ['ID_HARTA' => $id]);
        if (!empty($check)) {
            $data = $check[0];
            $do = 'update';
        } else {
            $do = 'insert';
            $data = [];
        }

        $data = array(
            'id_lhkpn' => $idlhkpn,
            'id' => $id,
            'form' => 'pelepasan',
            'do' => $do,
            'data' => $data,
            'nipor' => $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK', NULL, ['ID' => $id], 'NILAI_PELAPORAN')[0]
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartatidakbergerak_form', $data);
    }

    /** Form Konfirmasi Pelepasan Harta Bergerak
     *
     * @return html form konfirmasi pelepasan Harta Bergerak
     */
    function pelepasanhartabergerak($id, $id_lhkpn) {
        $this->load->model('mglobal', '', TRUE);

        $check = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK', NULL, ['ID_HARTA' => $id]);
        if (!empty($check)) {
            $data = $check[0];
            $do = 'update';
        } else {
            $do = 'insert';
            $data = [];
        }

        $data = array(
            'id_lhkpn' => $id_lhkpn,
            'id' => $id,
            'form' => 'pelepasan',
            'do' => $do,
            'data' => $data,
            'nipor' => $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', NULL, ['ID' => $id], 'NILAI_PELAPORAN')[0]
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak_form', $data);
    }

    //hapus pelepasan
    function deletepelepasanhartabergerak($id_harta, $id_pelepasan) {
        $this->db->trans_begin();
        $this->mglobal->update('T_LHKPN_HARTA_BERGERAK', ['IS_PELEPASAN' => '0'], ['ID' => $id_harta]);
        $this->mglobal->delete('T_LHKPN_PELEPASAN_HARTA_BERGERAK', ['ID' => $id_pelepasan]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    function pelepasanhartalainnya($id) {
        $this->load->model('mglobal', '', TRUE);

        $check = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_LAINNYA', NULL, ['ID_HARTA' => $id]);
        if (!empty($check)) {
            $data = $check[0];
            $do = 'update';
        } else {
            $do = 'insert';
            $data = [];
        }

        $data = array(
            'id' => $id,
            'form' => 'pelepasan',
            'do' => $do,
            'data' => $data,
            'nipor' => $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', NULL, ['ID' => $id], 'NILAI_PELAPORAN')[0]
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartalain_form', $data);
    }

    /** Detail Harta Tidak Bergerak
     * 
     * @return html detail Harta Tidak Bergerak
     */
    function detailhartatidakbergerak($id) {
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $join = [
                ['table' => 'M_KABKOT as kabkot', 'on' => 'kabkot.IDKOT   = ' . 'data.KAB_KOT'],
                ['table' => 'M_PROVINSI as provinsi', 'on' => 'provinsi.IDPROV = ' . 'data.PROV'],
        ];

        $where['data.ID'] = $id;
        $where_e = 'provinsi.IDPROV = kabkot.IDPROV';
        $select = 'data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.NEGARA as NEGARA, data.JALAN as JALAN, data.KEL as KEL, data.KEC as KEC, kabkot.NAME as KAB_KOT, provinsi.NAME as PROV,  data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';

        $data = array(
            'form' => 'detail',
            'item' => $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $join, $where, $select, $where_e),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartatidakbergerak_form', $data);
    }

    function perbandinganhartatidakbergerak($id, $tgl_lapor) {
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $join = [
                ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.ID = ID_NEGARA', 'join' => 'left'],
                ['table' => 'M_AREA as provinsi', 'on' => 'provinsi.IDPROV = ' . 'data.PROV and provinsi.IDKOT = "" and provinsi.IDKEC = "" and provinsi.IDKEL = ""', 'join' => 'left'],
                ['table' => 'M_AREA as kabkot', 'on' => 'kabkot.IDKOT   = ' . 'data.KAB_KOT and provinsi.IDPROV = ' . 'data.PROV and kabkot.IDKEC = "" and kabkot.IDKEL = ""', 'join' => 'left'],
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'T_LHKPN as lhkpn', 'on' => 'lhkpn.ID_LHKPN = ' . 'data.ID_LHKPN'],
        ];

        $where1['data.ID'] = $id;
        $where1['lhkpn.TGL_LAPOR'] = $tgl_lapor;

        $KABKOT = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as KAB_KOT";
        $select = 'lhkpn.TGL_LAPOR as LAPOR, lhkpn.ID_LHKPN as ID_LHKPN, lhkpn.ID_PN as ID_PN, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.NEGARA as NEGARA, data.JALAN as JALAN, data.KEC, data.KEL, ' . $KABKOT . ', provinsi.NAME as PROV,  data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP, M_NEGARA.NAMA_NEGARA as NAMA_NEGARA';
        $itemA = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $join, $where1, $select);
        $where2['data.ID_HARTA'] = $itemA[0]->ID_HARTA;
        $where2['lhkpn.ID_LHKPN <'] = $itemA[0]->ID_LHKPN;
        $where2['lhkpn.ID_PN'] = $itemA[0]->ID_PN;
        $itemB = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $join, $where2, $select, NULL, ['lhkpn.TGL_LAPOR', 'DESC']);
        $data = array(
            'form' => 'perbandingan',
            'itemA' => $itemA,
            'itemB' => $itemB,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartatidakbergerak_form', $data);
    }

    function perbandinganhartabergerak($id, $tgl_lapor) {
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mrasalusul', '', TRUE);
        $join = [['table' => 'T_LHKPN as lhkpn', 'on' => 'lhkpn.ID_LHKPN = ' . 'data.ID_LHKPN']];

        $where1['data.ID'] = $id;
        $where1['lhkpn.TGL_LAPOR'] = $tgl_lapor;

        // echo date('Y', strtotime($tgl_lapor))-1;
        $select = 'lhkpn.TGL_LAPOR as LAPOR, lhkpn.ID_LHKPN as ID_LHKPN, lhkpn.ID_PN as ID_PN, data.NAMA, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2';
        $itemA = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK as data', $join, $where1, $select, NULL);
        $where2['data.ID'] = $itemA[0]->ID_HARTA;
        $itemB = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK as data', $join, $where2, $select, NULL, ['lhkpn.TGL_LAPOR', 'DESC']);

        $uang = $this->daftar_uang();
        $manfaat = $this->daftar_pemanfaatan(2);
        $bukti = $this->daftar_bukti(2);
        $harta = $this->daftar_harta(2);
        $joins = [
                ['table' => 'M_ASAL_USUL', 'on' => 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK.ID_ASAL_USUL=M_ASAL_USUL.ID_ASAL_USUL']
        ];
        $data = array(
            'form' => 'perbandingan',
            'itemA' => $itemA,
            'itemB' => $itemB,
            'uang' => $uang,
            'manfaat' => $manfaat,
            'bukti' => $bukti,
            'harta' => $harta,
            'pelaporan' => $this->mglobal->get_data_all('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK', $joins, ['ID_HARTA' => $id]),
            'asalusuls' => $this->mrasalusul->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak_form', $data);
    }

    function perbandinganhartalainnya($id = '', $tgl_lapor = '') {
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mrasalusul', '', TRUE);
        $join = [['table' => 'T_LHKPN as lhkpn', 'on' => 'lhkpn.ID_LHKPN = ' . 'data.ID_LHKPN']];

        $where1['data.ID'] = $id;
        $where1['lhkpn.TGL_LAPOR'] = $tgl_lapor;

        // echo date('Y', strtotime($tgl_lapor))-1;
        $select = ' lhkpn.TGL_LAPOR as LAPOR, 
                    lhkpn.ID_LHKPN as ID_LHKPN, 
                    lhkpn.ID_PN as ID_PN,
                    data.NAMA, 
                    data.ID as ID, 
                    data.ID_HARTA as ID_HARTA, 
                    data.ID_LHKPN as ID_LHKPN, 
                    data.ASAL_USUL as ASAL_USUL, 
                    data.KODE_JENIS, 
                    data.KUANTITAS, 
                    data.ATAS_NAMA,
                    data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, 
                    data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, 
                    data.MATA_UANG as MATA_UANG, 
                    data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, 
                    data.NILAI_PELAPORAN as NILAI_PELAPORAN, 
                    data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, 
                    data.IS_ACTIVE as IS_ACTIVE, 
                    data.JENIS_LEPAS as JENIS_LEPAS, 
                    data.TGL_TRANSAKSI as TGL_TRANSAKSI, 
                    data.NILAI_JUAL as NILAI_JUAL, 
                    data.NAMA_PIHAK2 as NAMA_PIHAK2, 
                    data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2
                ';
        $itemA = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA as data', $join, $where1, $select, NULL);
        $where2['data.ID'] = $itemA[0]->ID_HARTA;
        $itemB = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA as data', $join, $where2, $select, NULL, ['lhkpn.TGL_LAPOR', 'DESC']);
        $uang = $this->daftar_uang();
        $data = array(
            'form' => 'perbandingan',
            'uang' => $uang,
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'itemA' => $itemA,
            'itemB' => $itemB,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartalain_form', $data);
    }

    /** Process Insert, Update, Delete Harta Bergerak
     * 
     * @return boolean process Harta Bergerak
     */
    function savehartabergerak() {
        $this->db->trans_begin();
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $tabel = 'T_LHKPN_HARTA_BERGERAK';

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $lhkpnharta = array(
                'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'MEREK' => $this->input->post('MEREK', TRUE),
                'MODEL' => $this->input->post('MODEL', TRUE),
                'TAHUN_PEMBUATAN' => $this->input->post('TAHUN_PEMBUATAN', TRUE),
                'NOPOL_REGISTRASI' => $this->input->post('NOPOL_REGISTRASI', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                // 'NAMA'      			=> $this->input->post('NAMAA', TRUE),
                'JENIS_BUKTI' => $this->input->post('JENIS_BUKTI', TRUE),
                // 'NOMOR_BUKTI'       	=> $this->input->post('NOMOR_BUKTI', TRUE),
                'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                'PEMANFAATAN' => $this->input->post('PEMANFAATAN', TRUE),
                'KET_LAINNYA' => $this->input->post('KET_LAINNYA', TRUE),
                'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'NILAI_PEROLEHAN' => str_replace(".", "", $this->input->post('NILAI_PEROLEHAN', TRUE)),
                'NILAI_PELAPORAN' => str_replace(".", "", $this->input->post('NILAI_PELAPORAN', TRUE)),
                'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                'IS_ACTIVE' => 1,
                'IS_CHECKED' => 1,
                'STATUS' => 3,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    // 'UPDATED_TIME'     => time(),
                    // 'UPDATED_BY'     => $this->session->userdata('USR'),
                    // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
            );
            $id = $this->mlhkpnharta->save($lhkpnharta, $tabel);

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $lhkpnharta = array(
                'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'MEREK' => $this->input->post('MEREK', TRUE),
                'MODEL' => $this->input->post('MODEL', TRUE),
                'TAHUN_PEMBUATAN' => $this->input->post('TAHUN_PEMBUATAN', TRUE),
                'NOPOL_REGISTRASI' => $this->input->post('NOPOL_REGISTRASI', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                // 'NAMA'      			=> $this->input->post('NAMAA', TRUE),
                'JENIS_BUKTI' => $this->input->post('JENIS_BUKTI', TRUE),
                // 'NOMOR_BUKTI'       	=> $this->input->post('NOMOR_BUKTI', TRUE),
                'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                'PEMANFAATAN' => $this->input->post('PEMANFAATAN', TRUE),
                'KET_LAINNYA' => $this->input->post('KET_LAINNYA', TRUE),
                'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'NILAI_PEROLEHAN' => str_replace(".", "", $this->input->post('NILAI_PEROLEHAN', TRUE)),
                'NILAI_PELAPORAN' => str_replace(".", "", $this->input->post('NILAI_PELAPORAN', TRUE)),
                'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                'IS_CHECKED' => 1,
                // 'IS_ACTIVE'         	=> $this->input->post('IS_ACTIVE', TRUE),
                // 'CREATED_TIME'    	=> time(),
                // 'CREATED_BY'     	=> $this->session->userdata('USR'),
                // 'CREATED_IP'     	=> $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );

            $id = $this->input->post('ID', TRUE);
            $rec = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', NULL, ['ID' => $id], 'STATUS')[0];
            if ($rec->STATUS == '1') {
                $lhkpnharta['STATUS'] = '2';
            }

            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->update($lhkpnharta['ID'], $lhkpnharta, $tabel);

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $this->mglobal->delete('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK', ['ID_HARTA' => $id]);
            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->delete($lhkpnharta['ID'], $tabel);
        } else if ($this->input->post('act') == 'dopelepasan') {
            $type = $this->input->post('type');
            if ($type == 'insert') {
                $data = [
                    'ID_HARTA' => $this->input->post('ID_HARTA'),
                    'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $result = $this->mglobal->insert('T_LHKPN_PELEPASAN_HARTA_BERGERAK', $data);
                $this->mglobal->update('T_LHKPN_HARTA_BERGERAK', ['IS_PELEPASAN' => '1', 'NILAI_PELAPORAN' => '0', 'IS_CHECKED' => '1'], ['ID' => $this->input->post('ID_HARTA')]);
            } else {
                $data = [
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $this->mglobal->update('T_LHKPN_PELEPASAN_HARTA_BERGERAK', $data, ['ID' => $this->input->post('ID')]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah Harta Bergerak
     * 
     * @return html form tambah Harta Bergerak
     */
    function addhartabergerak($id_lhkpn = null) {
        $this->load->model('mrasalusul', '', TRUE);
        $uang = $this->daftar_uang();
        $manfaat = $this->daftar_pemanfaatan(2);
        $bukti = $this->daftar_bukti(2);
        $harta = $this->daftar_harta(2);
        $data = array(
            'form' => 'add',
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'id_lhkpn' => $id_lhkpn,
            'uang' => $uang,
            'manfaat' => $manfaat,
            'bukti' => $bukti,
            'harta' => $harta,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak_form', $data);
    }

    /** Form Edit Harta Bergerak
     * 
     * @return html form edit Harta Bergerak
     */
    function edithartabergerak($id) {
        $this->load->model('mrasalusul', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $uang = $this->daftar_uang();
        $manfaat = $this->daftar_pemanfaatan(2);
        $bukti = $this->daftar_bukti(2);
        $harta = $this->daftar_harta(2);
        $join = [
                ['table' => 'M_ASAL_USUL', 'on' => 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK.ID_ASAL_USUL=M_ASAL_USUL.ID_ASAL_USUL']
        ];

        $data = array(
            'form' => 'edit',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_BERGERAK', $id)->row(),
            'pelaporan' => $this->mglobal->get_data_all('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK', $join, ['ID_HARTA' => $id]),
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'uang' => $uang,
            'manfaat' => $manfaat,
            'bukti' => $bukti,
            'harta' => $harta,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak_form', $data);
    }

    /** Form Konfirmasi Hapus Harta Bergerak
     * 
     * @return html form konfirmasi hapus Harta Bergerak
     */
    function deletehartabergerak($id) {
        $this->load->model('mlhkpnharta', '', TRUE);
        $data = array(
            'form' => 'delete',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_BERGERAK', $id)->row()
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak_form', $data);
    }

    /** Detail Harta Bergerak
     * 
     * @return html detail Harta Bergerak
     */
    function detailhartabergerak($id) {
        $this->load->model('mlhkpnharta', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_BERGERAK', $id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak_form', $data);
    }

    /** Process Insert, Update, Delete Harta Bergerak 2
     * 
     * @return boolean process Harta Bergerak 2
     */
    function savehartabergerak2() {
        $this->db->trans_begin();
        $this->load->model('mlhkpnharta', '', TRUE);
        $tabel = 'T_LHKPN_HARTA_BERGERAK_LAIN';

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $lhkpnharta = array(
                'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'NAMA' => $this->input->post('NAMAA', TRUE),
                'JUMLAH' => $this->input->post('JUMLAH', TRUE),
                'SATUAN' => $this->input->post('SATUAN', TRUE),
                'KETERANGAN' => $this->input->post('KETERANGAN', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                'PEMANFAATAN' => implode(',', $this->input->post('PEMANFAATAN', TRUE)),
                'KET_LAINNYA' => $this->input->post('KET_LAINNYA', TRUE),
                'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'NILAI_PEROLEHAN' => str_replace(".", "", $this->input->post('NILAI_PEROLEHAN', TRUE)),
                'NILAI_PELAPORAN' => str_replace(".", "", $this->input->post('NILAI_PELAPORAN', TRUE)),
                'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                'IS_ACTIVE' => 1,
                'IS_CHECKED' => 1,
                'STATUS' => '3',
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    // 'UPDATED_TIME'     => time(),
                    // 'UPDATED_BY'     => $this->session->userdata('USR'),
                    // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
            );
            $id = $this->mlhkpnharta->save($lhkpnharta, $tabel);

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $lhkpnharta = array(
                'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'NAMA' => $this->input->post('NAMAA', TRUE),
                'JUMLAH' => $this->input->post('JUMLAH', TRUE),
                'SATUAN' => $this->input->post('SATUAN', TRUE),
                'KETERANGAN' => $this->input->post('KETERANGAN', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                'PEMANFAATAN' => implode(',', $this->input->post('PEMANFAATAN', TRUE)),
                'KET_LAINNYA' => $this->input->post('KET_LAINNYA', TRUE),
                'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'NILAI_PEROLEHAN' => str_replace(".", "", $this->input->post('NILAI_PEROLEHAN', TRUE)),
                'NILAI_PELAPORAN' => str_replace(".", "", $this->input->post('NILAI_PELAPORAN', TRUE)),
                'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                'IS_CHECKED' => 1,
                // 'IS_ACTIVE'         	=> $this->input->post('IS_ACTIVE', TRUE),
                // 'CREATED_TIME'     	=> time(),
                // 'CREATED_BY'     	=> $this->session->userdata('USR'),
                // 'CREATED_IP'     	=> $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $id = $this->input->post('ID', TRUE);
            $rec = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', NULL, ['ID' => $id], 'STATUS')[0];
            if ($rec->STATUS == '1') {
                $lhkpnharta['STATUS'] = '2';
            }
            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->update($lhkpnharta['ID'], $lhkpnharta, $tabel);

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $this->mglobal->delete('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN', ['ID_HARTA' => $id]);
            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->delete($lhkpnharta['ID'], $tabel);
        } else if ($this->input->post('act') == 'dopelepasan') {
            $type = $this->input->post('type');
            if ($type == 'insert') {
                $data = [
                    'ID_HARTA' => $this->input->post('ID_HARTA'),
                    'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];
                // echo($this->input->post('ID_HARTA'));
                // exit();
                $result = $this->mglobal->insert('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN', $data);
                $this->mglobal->update('T_LHKPN_HARTA_BERGERAK_LAIN', ['IS_PELEPASAN' => '1', 'NILAI_PELAPORAN' => '0', 'IS_CHECKED' => '1'], ['ID' => $this->input->post('ID_HARTA')]);
            } else {
                $data = [
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $this->mglobal->update('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN', $data, ['ID' => $this->input->post('ID')]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah Harta Bergerak 2
     * 
     * @return html form tambah Harta Bergerak 2
     */
    function addhartabergerak2($id_lhkpn = null) {
        $this->load->model('mrasalusul', '', TRUE);
        $uang = $this->daftar_uang();
        $hartabergeraklain = $this->jenis_bergerak_lain();
        $manfaat = $this->daftar_pemanfaatan(3);
        $data = array(
            'form' => 'add',
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'id_lhkpn' => $id_lhkpn,
            'uang' => $uang,
            'hartabergeraklain' => $hartabergeraklain,
            'manfaat' => $manfaat,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak2_form', $data);
    }

    function deletepelepasanhartabergerak2($id_harta, $id_pelepasan) {
        $this->db->trans_begin();
        $this->mglobal->update('T_LHKPN_HARTA_BERGERAK_LAIN', ['IS_PELEPASAN' => '0'], ['ID' => $id_harta]);
        $this->mglobal->delete('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN', ['ID' => $id_pelepasan]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    function pelepasanhartabergerak2($id, $id_lhkpn) {
        $this->load->model('mglobal', '', TRUE);

        $check = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN', NULL, ['ID_HARTA' => $id]);
        if (!empty($check)) {
            $data = $check[0];
            $do = 'update';
        } else {
            $do = 'insert';
            $data = [];
        }

        $data = array(
            'id_lhkpn' => $id_lhkpn,
            'id' => $id,
            'form' => 'pelepasan',
            'do' => $do,
            'data' => $data,
            'nipor' => $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', NULL, ['ID' => $id], 'NILAI_PELAPORAN')[0]
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak2_form', $data);
    }

    /** Form Edit Harta Bergerak 2
     * 
     * @return html form edit Harta Bergerak 2
     */
    function edithartabergerak2($id) {
        $this->load->model('mrasalusul', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $uang = $this->daftar_uang();
        $hartabergeraklain = $this->jenis_bergerak_lain();
        $manfaat = $this->daftar_pemanfaatan(3);
        $join = [
                ['table' => 'M_ASAL_USUL', 'on' => 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN.ID_ASAL_USUL=M_ASAL_USUL.ID_ASAL_USUL']
        ];

        $data = array(
            'form' => 'edit',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_BERGERAK_LAIN', $id)->row(),
            'pelaporan' => $this->mglobal->get_data_all('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN', $join, ['ID_HARTA' => $id]),
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'uang' => $uang,
            'hartabergeraklain' => $hartabergeraklain,
            'manfaat' => $manfaat,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak2_form', $data);
    }

    /** Form Konfirmasi Hapus Harta Bergerak 2
     * 
     * @return html form konfirmasi hapus Harta Bergerak 2
     */
    function deletehartabergerak2($id) {
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mrasalusul', '', TRUE);
        $uang = $this->daftar_uang();
        $hartabergeraklain = $this->jenis_bergerak_lain();
        $data = array(
            'form' => 'delete',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_BERGERAK_LAIN', $id)->row(),
            'manfaat' => $this->daftar_pemanfaatan(2),
            'hartabergeraklain' => $hartabergeraklain,
            'uang' => $uang
        );

        $data['asal_usul'] = $this->mrasalusul->get_by_id($data['item']->ASAL_USUL)->row();
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak2_form', $data);
    }

    /** Detail Harta Bergerak 2
     * 
     * @return html detail Harta Bergerak 2
     */
    function detailhartabergerak2($id) {
        $this->load->model('mrasalusul', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_BERGERAK_LAIN', $id)->row(),
        );

        $data['asal_usul'] = $this->mrasalusul->get_by_id($data['item']->ASAL_USUL)->row();
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak2_form', $data);
    }

    function perbandinganhartabergerak2($id, $tgl_lapor) {
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mrasalusul', '', TRUE);
        $join = [
                ['table' => 'T_LHKPN as lhkpn', 'on' => 'lhkpn.ID_LHKPN = ' . 'data.ID_LHKPN'],
        ];

        $where1['data.ID'] = $id;
        $where1['lhkpn.TGL_LAPOR'] = $tgl_lapor;

        // echo date('Y', strtotime($tgl_lapor))-1;
        $select = 'data.KETERANGAN as KETERANGAN,data.JUMLAH as JUMLAH,data.SATUAN as SATUAN,data.KODE_JENIS as KODE_JENIS,data.nama as NAMA,lhkpn.TGL_LAPOR as LAPOR, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.KETERANGAN as KETERANGAN, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
        $itemA = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN as data', $join, $where1, $select, NULL);
        $where2['data.ID'] = $itemA[0]->ID_HARTA;
        $itemB = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN as data', $join, $where2, $select, NULL, ['lhkpn.TGL_LAPOR', 'DESC']);

        $uang = $this->daftar_uang();
        $manfaat = $this->daftar_pemanfaatan(3);
        $joins = [
                ['table' => 'M_ASAL_USUL', 'on' => 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN.ID_ASAL_USUL=M_ASAL_USUL.ID_ASAL_USUL']
        ];

        $data = array(
            'form' => 'perbandingan',
            'itemA' => $itemA,
            'itemB' => $itemB,
            'pelaporan' => $this->mglobal->get_data_all('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN', $joins, ['ID_HARTA' => $id]),
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'uang' => $uang,
            'manfaat' => $manfaat,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartabergerak2_form', $data);
    }

    /** Process upload image to temppdf
     * 
     * @return file name
     */
    public function uploadTotemppdf($FILE) {
        $target_dir = "images/temppdf/";
        $target_file = $target_dir . basename($_FILES[$FILE]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if (!empty($_FILES[$FILE]["tmp_name"])) {
            $check = getimagesize($_FILES[$FILE]["tmp_name"]);
            if ($check !== false) {
                // echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                // echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        // if (file_exists($target_file)) {
        //     echo "Sorry, file already exists.";
        //     $uploadOk = 0;
        // }
        // Check file size
        if ($_FILES[$FILE]["size"] > 500000) {
            // echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES[$FILE]["tmp_name"], $target_file) == true) {
                // echo "The file ". basename( $_FILES[$FILE]["name"]). " has been uploaded.";
                if ($_FILES[$FILE]["name"] != '') {
                    $_POST[$FILE] = $_FILES[$FILE]["name"];
                    return basename($_FILES[$FILE]["name"]);
                }
            } else {
                // echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    /** Process Insert, Update, Delete Surat Berharga
     * 
     * @return boolean process Surat Berharga
     */
    function savesuratberharga() {
        $type_fileA = $this->input->post('type_file');
        $this->db->trans_begin();
        $this->load->model('mlhkpnharta', '', TRUE);
        $tabel = 'T_LHKPN_HARTA_SURAT_BERHARGA';

        $type_file = array('jpg', 'png', 'jpeg', 'pdf');
        $id = 'unknown';
        $url = '';

        $user = $this->session->userdata('USR');
        $join = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $this->input->post('ID_LHKPN', TRUE);
        $select = 'pn.NIK';
        $dataPN = $this->mglobal->get_data_all('T_LHKPN as lhkpn', $join, $where, $select)[0];

        if (@$dataPN->NIK != '') {
            $id = $dataPN->NIK;
        }

        $filename = 'uploads/data_suratberharga/' . $id . '/readme.txt';

        if (!file_exists($filename)) {

            $dir = './uploads/data_suratberharga/' . $id . '/';

            $file_to_write = 'readme.txt';
            $content_to_write = "Bukti Surat Berharga Dari " . $user . ' dengan nik ' . $id;

            if (is_dir($dir) === false) {
                mkdir($dir);
            }

            $file = fopen($dir . '/' . $file_to_write, "w");

            fwrite($file, $content_to_write);

            // closes the file
            fclose($file);
        }

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $filependukung = @$_FILES['FILE_FOTO'];

            if ($type_fileA == '1') {
                $this->load->library('Uploadimagetopdf');
                $url = $this->uploadimagetopdf->upload($id, $filependukung, 'data_suratberharga');
            } else {
                $fileSK = $_FILES['FILE_FOTO'];
                $tmpEx = explode('.', $fileSK['name'][0]);
                $extension = end($tmpEx);
                $maxsize = 500000;

                if ($fileSK['size'][0] <= $maxsize) {
                    if (in_array($extension, $type_file)) {
                        $url = @save_file($fileSK['tmp_name'][0], $fileSK['name'][0], $fileSK['size'][0], "./uploads/data_suratberharga/" . $id, 1, 500)['nama_file'];
                    }
                }
            }


            $lhkpnharta = array(
                'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'JUMLAH' => $this->input->post('JUMLAH', TRUE),
                'SATUAN' => $this->input->post('SATUAN', TRUE),
                'NOMOR_REKENING' => $this->input->post('NOMOR_REKENING', TRUE),
                'NAMA_SURAT_BERHARGA' => $this->input->post('NAMA_SURAT_BERHARGA', TRUE),
                'NAMA_PENERBIT' => $this->input->post('NAMA_PENERBIT', TRUE),
                'CUSTODIAN' => $this->input->post('CUSTODIAN'),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'NILAI_PEROLEHAN' => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN', TRUE)),
                'NILAI_PELAPORAN' => str_replace('.', '', $this->input->post('NILAI_PELAPORAN', TRUE)),
                'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                'STATUS' => '3',
                'FILE_BUKTI' => $url,
                'IS_ACTIVE' => 1,
                'IS_CHECKED' => '1',
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    /* 'UPDATED_TIME'          => time(),
                      'UPDATED_BY'            => $this->session->userdata('USR'),
                      'UPDATED_IP'            => $_SERVER["REMOTE_ADDR"], */
            );
            $id = $this->mlhkpnharta->save($lhkpnharta, 'T_LHKPN_HARTA_SURAT_BERHARGA');

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            if ($type_fileA == '1') {
                $this->load->library('Uploadimagetopdf');
                $tmp = $this->uploadimagetopdf->upload($id, $_FILES['FILE_FOTO'], 'data_suratberharga');
                $url = $tmp == false ? $this->input->post('OLD_FILE', TRUE) : $tmp;
            } else {
                $tmp = true;
                $url = $this->input->post('OLD_FILE', TRUE);
                $fileSK = $_FILES['FILE_FOTO'];
                if ($fileSK['name'][0] != '') {
                    $tmpEx = explode('.', $fileSK['name'][0]);
                    $extension = end($tmpEx);
                    $maxsize = 500000;

                    if ($fileSK['size'][0] <= $maxsize) {
                        if (in_array($extension, $type_file)) {
                            $url = save_file($fileSK['tmp_name'][0], $fileSK['name'][0], $fileSK['size'][0], "./uploads/data_suratberharga/" . $id, 1, 500)['nama_file'];
                            $tmp = false;
                        }
                    }
                }
            }


            $lhkpnharta = array(
                'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'JUMLAH' => $this->input->post('JUMLAH', TRUE),
                'SATUAN' => $this->input->post('SATUAN', TRUE),
                'NAMA_SURAT_BERHARGA' => $this->input->post('NAMA_SURAT_BERHARGA', TRUE),
                'NAMA_PENERBIT' => $this->input->post('NAMA_PENERBIT', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'NILAI_PEROLEHAN' => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN', TRUE)),
                'NILAI_PELAPORAN' => str_replace('.', '', $this->input->post('NILAI_PELAPORAN', TRUE)),
                'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                // 'IS_ACTIVE'          => $this->input->post('IS_ACTIVE', TRUE),
                // 'CREATED_TIME'       => time(),
                // 'CREATED_BY'         => $this->session->userdata('USR'),
                // 'CREATED_IP'         => $_SERVER["REMOTE_ADDR"],
                'IS_CHECKED' => '1',
                'FILE_BUKTI' => $url,
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );

            if ($tmp != false) {
                $url_file = $this->input->post('OLD_FILE', TRUE);
                $fileOld = "./uploads/data_suratberharga/" . $id . "/$url_file";
                if (file_exists($fileOld)) {
                    unlink($fileOld);
                }
            }

            $id = $this->input->post('ID', TRUE);
            $rec = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, ['ID' => $id], 'STATUS')[0];
            if ($rec->STATUS == '1') {
                $lhkpnharta['STATUS'] = '2';
            }

            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->update($lhkpnharta['ID'], $lhkpnharta, $tabel);

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $this->mglobal->delete('T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA', ['ID_HARTA' => $id]);
            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->delete($lhkpnharta['ID'], $tabel);
            $url_file = $this->input->post('OLD_FILE', TRUE);
            unlink("./uploads/data_suratberharga/" . $id . "/$url_file");
        } else if ($this->input->post('act') == 'dopelepasan') {
            $type = $this->input->post('type');
            if ($type == 'insert') {
                $data = [
                    'ID_HARTA' => $this->input->post('ID_HARTA'),
                    'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $result = $this->mglobal->insert('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA', $data);
                $this->mglobal->update('T_LHKPN_HARTA_SURAT_BERHARGA', ['IS_PELEPASAN' => '1', 'NILAI_PELAPORAN' => '0', 'IS_CHECKED' => '1'], ['ID' => $this->input->post('ID_HARTA')]);
            } else {
                $data = [
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $this->mglobal->update('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA', $data, ['ID' => $this->input->post('ID')]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah Surat Berharga
     * 
     * @return html form tambah Surat Berharga
     */
    function addsuratberharga($id_lhkpn = null) {
        $this->load->model('mrasalusul', '', TRUE);
        $uang = $this->daftar_uang();
        $jenissurat = $this->jenis_surat_berharga();
        $data = array(
            'form' => 'add',
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'id_lhkpn' => $id_lhkpn,
            'uang' => $uang,
            'jenissurat' => $jenissurat,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_suratberharga_form', $data);
    }

    /** Form Edit Surat Berharga
     * 
     * @return html form edit Surat Berharga
     */
    function editsuratberharga($id) {
        $this->load->model('mrasalusul', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $uang = $this->daftar_uang();
        $jenissurat = $this->jenis_surat_berharga();

        $item = $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_SURAT_BERHARGA', $id)->row();

        $join = [
                ['table' => 'M_ASAL_USUL', 'on' => 'T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA.ID_ASAL_USUL=M_ASAL_USUL.ID_ASAL_USUL']
        ];

        $join2 = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $item->ID_LHKPN;
        $select = 'pn.NIK';
        $dataPN = $this->mglobal->get_data_all('T_LHKPN as lhkpn', $join2, $where, $select)[0];

        $data = array(
            'nik' => $dataPN->NIK,
            'form' => 'edit',
            'item' => $item,
            'pelaporan' => $this->mglobal->get_data_all('T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA', $join, ['ID_HARTA' => $id]),
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'uang' => $uang,
            'jenissurat' => $jenissurat,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_suratberharga_form', $data);
    }

    /** Form Konfirmasi Hapus Surat Berharga
     * 
     * @return html form konfirmasi hapus Surat Berharga
     */
    function deletesuratberharga($id) {
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mrasalusul', '', TRUE);
        $data = array(
            'form' => 'delete',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_SURAT_BERHARGA', $id)->row(),
        );

        $data['asal_usul'] = $this->mrasalusul->get_by_id($data['item']->ASAL_USUL)->row();
        $data['mata_uang'] = $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_suratberharga_form', $data);
    }

    /** Detail Surat Berharga
     * 
     * @return html detail Surat Berharga
     */
    function detailsuratberharga($id) {
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mrasalusul', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_SURAT_BERHARGA', $id)->row(),
        );

        $data['asal_usul'] = $this->mrasalusul->get_by_id($data['item']->ASAL_USUL)->row();
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_suratberharga_form', $data);
    }

    function perbandingansuratberharga($id, $tgl_lapor) {
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mrasalusul', '', TRUE);
        $join = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'T_LHKPN as lhkpn', 'on' => 'lhkpn.ID_LHKPN = ' . 'data.ID_LHKPN'],
        ];

        $where1['data.ID'] = $id;
        $where1['lhkpn.TGL_LAPOR'] = $tgl_lapor;

        $select = '*';
        $itemA = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA as data', $join, $where1, $select);
        $where2['data.ID'] = $itemA[0]->ID_HARTA;
        $itemB = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA as data', $join, $where2, $select, NULL, ['lhkpn.TGL_LAPOR', 'DESC']);
        $uang = $this->daftar_uang();
        $jenissurat = $this->jenis_surat_berharga();
        $data = array(
            'form' => 'perbandingan',
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'uang' => $uang,
            'jenissurat' => $jenissurat,
            'itemA' => $itemA,
            'itemB' => $itemB,
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_suratberharga_form', $data);
    }

    //hapus pelepasan
    function deletepelepasansuratberharga($id_harta, $id_pelepasan) {
        $this->db->trans_begin();
        $this->mglobal->update('T_LHKPN_HARTA_SURAT_BERHARGA', ['IS_PELEPASAN' => '0'], ['ID' => $id_harta]);
        $this->mglobal->delete('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA', ['ID' => $id_pelepasan]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Konfirmasi Pelepasan Harta Surat Berharga
     *
     * @return html form konfirmasi pelepasan Harta Surat Berharga
     */
    function pelepasansuratberharga($id, $id_lhkpn) {
        $this->load->model('mglobal', '', TRUE);

        $check = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA', NULL, ['ID_HARTA' => $id]);
        if (!empty($check)) {
            $data = $check[0];
            $do = 'update';
        } else {
            $do = 'insert';
            $data = [];
        }

        $data = array(
            'id_lhkpn' => $id_lhkpn,
            'id' => $id,
            'form' => 'pelepasan',
            'do' => $do,
            'data' => $data,
            'nipor' => $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, ['ID' => $id], 'NILAI_PELAPORAN')[0]
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_suratberharga_form', $data);
    }

    /** Process Insert, Update, Delete Kas
     * 
     * @return boolean process Kas
     */
    function savekas() {
        $type_fileA = $this->input->post('type_file');
        $this->db->trans_begin();
        $this->load->model('mlhkpnharta', '', TRUE);
        $tabel = 'T_LHKPN_HARTA_KAS';

        $type_file = array('jpg', 'png', 'jpeg', 'pdf');
        $id = 'unknown';
        $url = '';

        $user = $this->session->userdata('USR');
        $join = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $this->input->post('ID_LHKPN', TRUE);
        $select = 'pn.NIK';
        $dataPN = $this->mglobal->get_data_all('T_LHKPN as lhkpn', $join, $where, $select)[0];

        if (@$dataPN->NIK != '') {
            $id = $dataPN->NIK;
        }

        $filename = 'uploads/data_kas/' . $id . '/readme.txt';

        if (!file_exists($filename)) {

            $dir = './uploads/data_kas/' . $id . '/';

            $file_to_write = 'readme.txt';
            $content_to_write = "Bukti Kas Dari " . $user . ' dengan nik ' . $id;

            if (is_dir($dir) === false) {
                mkdir($dir);
            }

            $file = fopen($dir . '/' . $file_to_write, "w");

            fwrite($file, $content_to_write);

            // closes the file
            fclose($file);
        }

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $filependukung = @$_FILES['FILE_FOTO'];

            if ($type_fileA == '1') {
                $this->load->library('Uploadimagetopdf');
                $url = $this->uploadimagetopdf->upload($id, $filependukung, 'data_kas');
                $url = ($url == false ? NULL : $url);
            } else {
                $fileSK = $_FILES['FILE_FOTO'];
                $tmpEx = explode('.', $fileSK['name'][0]);
                $extension = end($tmpEx);
                $maxsize = 500000;

                if ($fileSK['size'][0] <= $maxsize) {
                    if (in_array($extension, $type_file)) {
                        $url = @save_file($fileSK['tmp_name'][0], $fileSK['name'][0], $fileSK['size'][0], "./uploads/data_kas/" . $id, 1, 500)['nama_file'];
                    }
                }
            }

            $lhkpnharta = array(
                'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'ATAS_NAMA_REKENING' => $this->input->post('ATAS_NAMA', TRUE),
                'TAHUN_BUKA_REKENING' => $this->input->post('TAHUN_BUKA_REKENING', TRUE),
                'NAMA_BANK' => $this->input->post('NAMA_BANK', TRUE),
                'NOMOR_REKENING' => $this->input->post('NOMOR_REKENING', TRUE),
                'NILAI_SALDO' => str_replace('.', '', $this->input->post('NILAI_SALDO', TRUE)),
                'NILAI_KURS' => str_replace('.', '', $this->input->post('NILAI_KURS', TRUE)),
                'NILAI_EQUIVALEN' => str_replace('.', '', $this->input->post('NILAI_EQUIVALEN', TRUE)),
                'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'FILE_BUKTI' => $url,
                'IS_ACTIVE' => 1,
                'IS_CHECKED' => '1',
                'STATUS' => '3',
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    /* 'UPDATED_TIME'         => time(),
                      'UPDATED_BY'           => $this->session->userdata('USR'),
                      'UPDATED_IP'           => $_SERVER["REMOTE_ADDR"], */
            );

            $id = $this->mlhkpnharta->save($lhkpnharta, 'T_LHKPN_HARTA_KAS');

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_KAS', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            if ($type_fileA == '1') {
                $this->load->library('Uploadimagetopdf');
                $tmp = $this->uploadimagetopdf->upload($id, $_FILES['FILE_FOTO'], 'data_kas');
                $url = $tmp == false ? $this->input->post('OLD_FILE', TRUE) : $tmp;
            } else {
                $tmp = true;
                $url = $this->input->post('OLD_FILE', TRUE);
                $fileSK = $_FILES['FILE_FOTO'];
                if ($fileSK['name'][0] != '') {
                    $tmpEx = explode('.', $fileSK['name'][0]);
                    $extension = end($tmpEx);
                    $maxsize = 500000;

                    if ($fileSK['size'][0] <= $maxsize) {
                        if (in_array($extension, $type_file)) {
                            $url = save_file($fileSK['tmp_name'][0], $fileSK['name'][0], $fileSK['size'][0], "./uploads/data_suratberharga/" . $id, 1, 500)['nama_file'];
                            $tmp = false;
                        }
                    }
                }
            }

            $lhkpnharta = array(
                'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'ATAS_NAMA_REKENING' => $this->input->post('ATAS_NAMA', TRUE),
                'NAMA_BANK' => $this->input->post('NAMA_BANK', TRUE),
                'NOMOR_REKENING' => $this->input->post('NOMOR_REKENING', TRUE),
                'NILAI_SALDO' => str_replace('.', '', $this->input->post('NILAI_SALDO', TRUE)),
                'TAHUN_BUKA_REKENING' => $this->input->post('TAHUN_BUKA_REKENING', TRUE),
                'NILAI_EQUIVALEN' => str_replace('.', '', $this->input->post('NILAI_EQUIVALEN', TRUE)),
                'ASAL_USUL' => implode(',', $this->input->post('ASAL_USUL', TRUE)),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'IS_CHECKED' => '1',
                'FILE_BUKTI' => $url,
                /* 'IS_ACTIVE'            => 1,
                  'CREATED_TIME'         => time(),
                  'CREATED_BY'           => $this->session->userdata('USR'),
                  'CREATED_IP'           => $_SERVER["REMOTE_ADDR"], */
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );

            $url_file = $this->input->post('OLD_FILE', TRUE);
            if ($tmp != false AND $this->input->post('OLD_FILE', TRUE) != '' AND file_exists("./uploads/data_kas/" . $id . "/" . $this->input->post('OLD_FILE', TRUE))) {
                unlink("./uploads/data_kas/" . $id . "/" . $this->input->post('OLD_FILE', TRUE));
            }

            $id = $this->input->post('ID', TRUE);
            $rec = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', NULL, ['ID' => $id], 'STATUS')[0];
            if ($rec->STATUS == '1') {
                $lhkpnharta['STATUS'] = '2';
            }
            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->update($lhkpnharta['ID'], $lhkpnharta, $tabel);

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $this->mglobal->delete('T_LHKPN_ASAL_USUL_PELEPASAN_KAS', ['ID_HARTA' => $id]);
            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_KAS', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->delete($lhkpnharta['ID'], $tabel);
            $url_file = $this->input->post('OLD_FILE', TRUE);
            unlink("./uploads/data_kas/" . $id . "/$url_file");
        } else if ($this->input->post('act') == 'dopelepasan') {
            $type = $this->input->post('type');
            if ($type == 'insert') {
                $data = [
                    'ID_HARTA' => $this->input->post('ID_HARTA'),
                    'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];
                // echo($this->input->post('ID_HARTA'));
                // exit();
                $result = $this->mglobal->insert('T_LHKPN_PELEPASAN_HARTA_KAS', $data);
                $this->mglobal->update('T_LHKPN_HARTA_KAS', ['IS_PELEPASAN' => '1', 'NILAI_EQUIVALEN' => '0', 'IS_CHECKED' => '1'], ['ID' => $this->input->post('ID_HARTA')]);
            } else {
                $data = [
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $this->mglobal->update('T_LHKPN_PELEPASAN_HARTA_KAS', $data, ['ID' => $this->input->post('ID')]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah Kas
     * 
     * @return html form tambah Kas
     */
    function addkas($id_lhkpn = null) {
        $this->load->model('mrasalusul', '', TRUE);
        $uang = $this->daftar_uang();
        $jenisharta = $this->jenis_harta();
        $data = array(
            'form' => 'add',
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'id_lhkpn' => $id_lhkpn,
            'uang' => $uang,
            'jenisharta' => $jenisharta,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    function pelepasankas($id, $id_lhkpn) {
        $this->load->model('mglobal', '', TRUE);

        $check = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_KAS', NULL, ['ID_HARTA' => $id]);
        if (!empty($check)) {
            $data = $check[0];
            $do = 'update';
        } else {
            $do = 'insert';
            $data = [];
        }

        $data = array(
            'id_lhkpn' => $id_lhkpn,
            'id' => $id,
            'form' => 'pelepasan',
            'do' => $do,
            'data' => $data,
            'nipor' => $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', NULL, ['ID' => $id], 'NILAI_EQUIVALEN')[0]
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    function deletepelepasankas($id_harta, $id_pelepasan) {
        $this->db->trans_begin();
        $this->mglobal->update('T_LHKPN_HARTA_KAS', ['IS_PELEPASAN' => '0'], ['ID' => $id_harta]);
        $this->mglobal->delete('T_LHKPN_PELEPASAN_HARTA_KAS', ['ID' => $id_pelepasan]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    function perbandingankas($id, $tgl_lapor) {
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mrasalusul', '', TRUE);
        $join = [
                ['table' => 'T_LHKPN as lhkpn', 'on' => 'lhkpn.ID_LHKPN = ' . 'data.ID_LHKPN'],
        ];

        $where1['data.ID'] = $id;
        $where1['lhkpn.TGL_LAPOR'] = $tgl_lapor;

        // echo date('Y', strtotime($tgl_lapor))-1;
        $select = 'data.NILAI_SALDO, lhkpn.ID_LHKPN as ID_LHKPN, lhkpn.ID_PN as ID_PN, data.NILAI_EQUIVALEN as NILAI_PEROLEHAN,data.ATAS_NAMA_REKENING as ATAS_NAMA,data.NAMA_BANK as NAMA_BANK,data.NOMOR_REKENING as REKENING,data.TAHUN_BUKA_REKENING as TAHUN_BUKA,data.KODE_JENIS as KODE_JENIS,lhkpn.TGL_LAPOR as LAPOR, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.ASAL_USUL as ASAL_USUL, data.MATA_UANG as MATA_UANG, data.IS_ACTIVE as IS_ACTIVE, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
        $itemA = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS as data', $join, $where1, $select, NULL);
        $where2['data.ID'] = $itemA[0]->ID_HARTA;
        $itemB = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS as data', $join, $where2, $select, NULL, ['lhkpn.TGL_LAPOR', 'DESC']);
        $uang = $this->daftar_uang();
        $jenisharta = $this->jenis_harta();
        $data = array(
            'form' => 'perbandingan',
            'itemA' => $itemA,
            'itemB' => $itemB,
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'uang' => $uang,
            'jenisharta' => $jenisharta,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    /** Form Edit Kas
     * 
     * @return html form edit Kas
     */
    function editkas($id) {
        $this->load->model('mrasalusul', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);

        $item = $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_KAS', $id)->row();
        // display($item);

        $join = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $item->ID_LHKPN;
        $select = 'pn.NIK';
        $dataPN = $this->mglobal->get_data_all('T_LHKPN as lhkpn', $join, $where, $select)[0];
        // echo "<pre>";
        // print_r ($dataPN);
        // echo "</pre>";exit();

        $uang = $this->daftar_uang();
        $jenisharta = $this->jenis_harta();
        $join = [
                ['table' => 'M_ASAL_USUL', 'on' => 'T_LHKPN_ASAL_USUL_PELEPASAN_KAS.ID_ASAL_USUL=M_ASAL_USUL.ID_ASAL_USUL']
        ];

        $data = array(
            'nik' => $dataPN->NIK,
            'form' => 'edit',
            'item' => $item,
            'pelaporan' => $this->mglobal->get_data_all('T_LHKPN_ASAL_USUL_PELEPASAN_KAS', $join, ['ID_HARTA' => $id]),
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'uang' => $uang,
            'jenisharta' => $jenisharta,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    /** Form Konfirmasi Hapus Kas
     * 
     * @return html form konfirmasi hapus Kas
     */
    function deletekas($id) {
        $this->load->model('mlhkpnharta', '', TRUE);
        $data = array(
            'form' => 'delete',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_KAS', $id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    /** Detail Kas
     * 
     * @return html detail Kas
     */
    // function detailkas($id){
    //     $this->load->model('mlhkpnharta', '', TRUE);
    //     $data = array(
    //         'form'  => 'detail',
    //         'item'      => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_KAS', $id)->row(),
    //     );
    //     $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_kas_form', $data);
    // }

    function kasKK($id) {
        $this->load->model('mlhkpnharta', '', TRUE);
        $data = array(
            'form' => 'kaskk',
            'item' => $this->mlhkpnharta->get_data_kk('T_LHKPN_HARTA_KAS a', $id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    /** Process Insert, Update, Delete Harta Lain
     * 
     * @return boolean process Harta Lain
     */
    function savehartalain() {
        $this->load->model('mlhkpnharta', '', TRUE);

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $this->db->trans_begin();
            $asalusul = '';
            foreach ($this->input->post('ASAL_USUL', TRUE) as $key) {
                $asalusul .= $key . ',';
            }
            $lhkpnharta = array(
                'ID_HARTA' => $this->input->post('ID_HARTA', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'NAMA' => $this->input->post('NAMAA', TRUE),
                'KUANTITAS' => $this->input->post('KUANTITAS', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                'ASAL_USUL' => $asalusul,
                'KUANTITAS' => $this->input->post('KUANTITAS', TRUE),
                'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'NILAI_PEROLEHAN' => str_replace(".", "", $this->input->post('NILAI_PEROLEHAN', TRUE)),
                'NILAI_PELAPORAN' => str_replace(".", "", $this->input->post('NILAI_PELAPORAN', TRUE)),
                'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                'IS_ACTIVE' => 1,
                'IS_CHECKED' => 1,
                'STATUS' => 3,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $id = $this->mlhkpnharta->save($lhkpnharta, 'T_LHKPN_HARTA_LAINNYA');

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $this->db->trans_begin();
            $asalusul = '';
            foreach ($this->input->post('ASAL_USUL', TRUE) as $key) {
                $asalusul .= $key . ',';
            }
            $lhkpnharta = array(
                // 'ID_HARTA'              => $this->input->post('ID_HARTA', TRUE),
                // 'ID_LHKPN'              => $this->input->post('ID_LHKPN', TRUE),
                'KODE_JENIS' => $this->input->post('ID_JENIS_HARTA', TRUE),
                'NAMA' => $this->input->post('NAMAA', TRUE),
                'KUANTITAS' => $this->input->post('KUANTITAS', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                'ASAL_USUL' => substr($asalusul, 0, -1),
                'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL', TRUE),
                'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR', TRUE),
                'MATA_UANG' => $this->input->post('MATA_UANG', TRUE),
                'NILAI_PEROLEHAN' => str_replace(".", "", $this->input->post('NILAI_PEROLEHAN', TRUE)),
                'NILAI_PELAPORAN' => str_replace(".", "", $this->input->post('NILAI_PELAPORAN', TRUE)),
                'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN', TRUE),
                'IS_ACTIVE' => 1,
                'IS_CHECKED' => 1,
                // 'IS_ACTIVE'         => $this->input->post('IS_ACTIVE', TRUE),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );

            $id = $this->input->post('ID', TRUE);
            $rec = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', NULL, ['ID' => $id], 'STATUS')[0];
            if ($rec->STATUS == '1') {
                $lhkpnharta['STATUS'] = '2';
            }

            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->update($lhkpnharta['ID'], $lhkpnharta, 'T_LHKPN_HARTA_LAINNYA');

            $aList = $this->mglobal->get_data_all_array('M_ASAL_USUL', NULL, ['IS_OTHER' => '1'], 'ID_ASAL_USUL');
            $tmp = [];
            foreach ($aList as $row) {
                $tmp[] = $row['ID_ASAL_USUL'];
            }
            $aList = $tmp;

            $this->mglobal->delete('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA', ['ID_HARTA' => $id]);
            $asal_usul = $this->input->post('ASAL_USUL', TRUE);
            $i = 0;
            foreach ($asal_usul as $key => $row) {
                if (in_array($row, $aList)) {
                    $data = [
                        'ID_HARTA' => $id,
                        'ID_ASAL_USUL' => $row,
                        'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')[$i]))),
                        'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')[$i]),
                        'NAMA' => $this->input->post('NAMA')[$i],
                        'ALAMAT' => $this->input->post('ALAMAT')[$i],
                        'URAIAN_HARTA' => $this->input->post('KETERANGAN_PELEPASAN')[$i]
                    ];

                    $this->mglobal->insert('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA', $data);
                    $i++;
                }
            }
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $this->db->trans_begin();
            $lhkpnharta['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpnharta->delete($lhkpnharta['ID'], 'T_LHKPN_HARTA_LAINNYA');
        } else if ($this->input->post('act') == 'dopelepasan') {
            $type = $this->input->post('type');
            if ($type == 'insert') {
                $data = [
                    'ID_HARTA' => $this->input->post('ID_HARTA'),
                    'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $result = $this->mglobal->insert('T_LHKPN_PELEPASAN_HARTA_LAINNYA', $data);
                $this->mglobal->update('T_LHKPN_HARTA_LAINNYA', ['IS_PELEPASAN' => '1', 'NILAI_PELAPORAN' => '0', 'IS_CHECKED' => '1'], ['ID' => $this->input->post('ID_HARTA')]);
            } else {
                $data = [
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                    'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                    'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                    'NAMA' => $this->input->post('NAMA'),
                    'ALAMAT' => $this->input->post('ALAMAT'),
                    'URAIAN_HARTA' => $this->input->post('KETERANGAN')
                ];

                $this->mglobal->update('T_LHKPN_PELEPASAN_HARTA_LAINNYA', $data, ['ID' => $this->input->post('ID')]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah Harta Lain
     * 
     * @return html form tambah Harta Lain
     */
    function addhartalain($id_lhkpn = null) {
        $this->load->model('mrasalusul', '', TRUE);
        $uang = $this->daftar_uang();
        $hartalain = $this->jenis_harta_lain();
        $data = array(
            'form' => 'add',
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'id_lhkpn' => $id_lhkpn,
            'uang' => $uang,
            'hartalain' => $hartalain,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartalain_form', $data);
    }

    /** Form Edit Harta Lain
     * 
     * @return html form edit Harta Lain
     */
    function edithartalain($id) {
        $this->load->model('mrasalusul', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $uang = $this->daftar_uang();
        $hartalain = $this->jenis_harta_lain();
        $join = [
                ['table' => 'M_ASAL_USUL', 'on' => 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA.ID_ASAL_USUL=M_ASAL_USUL.ID_ASAL_USUL']
        ];
        $data = array(
            'form' => 'edit',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_LAINNYA', $id)->row(),
            'pelaporan' => $this->mglobal->get_data_all('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA', $join, ['ID_HARTA' => $id]),
            'asalusuls' => $this->mrasalusul->list_all()->result(),
            'hartalain' => $hartalain,
            'uang' => $uang
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartalain_form', $data);
    }

    /** Form Konfirmasi Hapus Harta Lain
     * 
     * @return html form konfirmasi hapus Harta Lain
     */
    function deletehartalain($id) {
        $this->load->model('mlhkpnharta', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $data = array(
            'form' => 'delete',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_LAINNYA', $id)->row(),
            'asalusul' => $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL)
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartalain_form', $data);
    }

    /** Detail Harta Lain
     * 
     * @return html detail Harta Lain
     */
    function detailhartalain($id) {
        $this->load->model('mlhkpnharta', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mlhkpnharta->get_data_by('T_LHKPN_HARTA_LAINNYA', $id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hartalain_form', $data);
    }

    /** Process Insert, Update, Delete Hutang
     * 
     * @return boolean process Hutang
     */
    function savehutang() {
        $this->db->trans_begin();
        $this->load->model('mlhkpnhutang', '', TRUE);

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $lhkpnhutang = array(
                'ID_HUTANG' => $this->input->post('ID_HUTANG', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                'AWAL_HUTANG' => $this->input->post('AWAL_HUTANG', TRUE),
                'NAMA_KREDITUR' => $this->input->post('NAMA_KREDITUR', TRUE),
                'TANGGAL_TRANSAKSI' => $this->input->post('TANGGAL_TRANSAKSI', TRUE),
                'TANGGAL_JATUH_TEMPO' => $this->input->post('TANGGAL_JATUH_TEMPO', TRUE),
                'AGUNAN' => $this->input->post('AGUNAN', TRUE),
                'SALDO_HUTANG' => str_replace(".", "", $this->input->post('SALDO_HUTANG', TRUE)),
                'STATUS' => '3',
                'IS_ACTIVE' => 1,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $this->mlhkpnhutang->save($lhkpnhutang);
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $lhkpnhutang = array(
                'ID_HUTANG' => $this->input->post('ID_HUTANG', TRUE),
                'KODE_JENIS' => $this->input->post('KODE_JENIS', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'ATAS_NAMA' => $this->input->post('ATAS_NAMA', TRUE),
                'AWAL_HUTANG' => $this->input->post('AWAL_HUTANG', TRUE),
                'NAMA_KREDITUR' => $this->input->post('NAMA_KREDITUR', TRUE),
                'TANGGAL_TRANSAKSI' => $this->input->post('TANGGAL_TRANSAKSI', TRUE),
                'TANGGAL_JATUH_TEMPO' => $this->input->post('TANGGAL_JATUH_TEMPO', TRUE),
                'AGUNAN' => $this->input->post('AGUNAN', TRUE),
                'SALDO_HUTANG' => str_replace(".", "", $this->input->post('SALDO_HUTANG', TRUE)),
                // 'IS_ACTIVE'         => $this->input->post('IS_ACTIVE', TRUE),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $id = $this->input->post('ID_HUTANG', TRUE);
            $rec = $this->mglobal->get_data_all('T_LHKPN_HUTANG', NULL, ['ID_HUTANG' => $id], 'STATUS')[0];
            if ($rec->STATUS == '1') {
                $lhkpnhutang['STATUS'] = '2';
            }
            $lhkpnhutang['ID_HUTANG'] = $this->input->post('ID_HUTANG', TRUE);
            $this->mlhkpnhutang->update($lhkpnhutang['ID_HUTANG'], $lhkpnhutang);
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $lhkpnhutang['ID_HUTANG'] = $this->input->post('ID_HUTANG', TRUE);
            $this->mlhkpnhutang->delete($lhkpnhutang['ID_HUTANG']);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah EDIT DETAIL DELETE Dokumen Pendukung */
    function add_dokpendukung($id_lhkpn = null) {
        $data = array(
            'form' => 'add',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_dokpendukung_form', $data);
    }

    function edit_dokpendukung($id) {
        $this->load->model('mlhkpndokpendukung', '', TRUE);
        $item = $this->mlhkpndokpendukung->get_by_id($id)->row();
        $nik = 'nik';
        $join = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $item->ID_LHKPN;
        $select = 'pn.NIK';
        $dataPN = @$this->mglobal->get_data_all('T_LHKPN as lhkpn', $join, $where, $select)[0];
        if (@$dataPN->NIK != '') {
            $nik = $dataPN->NIK;
        }

        $data = array(
            'form' => 'edit',
            'item' => $item,
            'nik' => $nik,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_dokpendukung_form', $data);
    }

    function delete_dokpendukung($id) {
        $this->load->model('mlhkpndokpendukung', '', TRUE);
        $item = $this->mlhkpndokpendukung->get_by_id($id)->row();
        $nik = 'nik';
        $join = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $item->ID_LHKPN;
        $select = 'pn.NIK';
        $dataPN = @$this->mglobal->get_data_all('T_LHKPN as lhkpn', $join, $where, $select)[0];
        if (@$dataPN->NIK != '') {
            $nik = $dataPN->NIK;
        }


        $data = array(
            'form' => 'delete',
            'item' => $item,
            'nik' => $nik
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_dokpendukung_form', $data);
    }

    function detail_dokpendukung($id) {
        $this->load->model('mlhkpndokpendukung', '', TRUE);
        $item = $this->mlhkpndokpendukung->get_by_id($id)->row();
        $nik = 'nik';
        $join = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $item->ID_LHKPN;
        $select = 'pn.NIK';
        $dataPN = @$this->mglobal->get_data_all('T_LHKPN as lhkpn', $join, $where, $select)[0];
        if (@$dataPN->NIK != '') {
            $nik = $dataPN->NIK;
        }

        $data = array(
            'form' => 'detail',
            'item' => $item,
            'nik' => $nik
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_dokpendukung_form', $data);
    }

    /** SAVE UPDATE DELETE Dokumen Pendukung */
    function save_dokpendukung() {
        $this->db->trans_begin();
        $this->load->model('mlhkpndokpendukung', '', TRUE);
        $filename = '';
        $id = 'nik';
        $join = [
                ['table' => 'T_PN as pn', 'on' => 'lhkpn.ID_PN   = pn.ID_PN'],
        ];
        $where['lhkpn.ID_LHKPN ='] = $this->input->post('ID_LHKPN', TRUE);
        $select = 'pn.NIK';
        $dataPN = $this->mglobal->get_data_all('T_LHKPN as lhkpn', $join, $where, $select)[0];
        //  echo $this->db->last_query();exit;    
        // echo "<pre>";
        // print_r ($dataPN);
        // echo "</pre>"; exit();
        if (@$dataPN->NIK != '') {
            $id = $dataPN->NIK;
        }

        if ($this->input->post('act', TRUE) == 'doinsert') {

            // echo $id;
            $user = $this->session->userdata('USR');

            $filename = 'uploads/lhkpn/' . $id . '/readme.txt';

            if (!file_exists($filename)) {

                $dir = './uploads/lhkpn/' . $id . '/';

                $file_to_write = 'readme.txt';
                $content_to_write = "Foto Album Dari " . $user . ' dengan nik ' . $id;

                if (is_dir($dir) === false) {
                    mkdir($dir);
                }

                $file = fopen($dir . '/' . $file_to_write, "w");

                // a different way to write content into
                // fwrite($file,"Hello World.");

                fwrite($file, $content_to_write);

                // closes the file
                fclose($file);

                // this will show the created file from the created folder on screen
                // include $dir . '/' . $file_to_write;
            }

            // $foto = $judul.'.'.$nama[$ext];

            $filependukung = @$_FILES['FILE_PENDUKUNG'];

            $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/lhkpn/" . $id . "/", 0, 10000);
            if ($filependukung['size'] == '') {
                $url = '';
            } else {
                $url = time() . "-" . trim($filependukung['name']);
            }
            $lhkpndokpendukung = array(
                'ID_DOKUMEN_PENDUKUNG' => $this->input->post('ID_DOKUMEN_PENDUKUNG', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'NAMA_DOKUMEN' => $this->input->post('NAMA_DOKUMEN', TRUE),
                'LOKASI_DOKUMEN' => $url,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    // 'UPDATED_TIME'     => time(),
                    // 'UPDATED_BY'     => $this->session->userdata('USR'),
                    // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
            );
            $this->mlhkpndokpendukung->save($lhkpndokpendukung);
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $filependukung = (isset($_FILES['FILE_PENDUKUNG'])) ? $_FILES['FILE_PENDUKUNG'] : '';
            $del = FALSE;

            if ($filependukung['error'] == 0) {
                $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/lhkpn/" . $id . "/", 0, 10000);
                $url = time() . "-" . trim($filependukung['name']);
            }

            $lhkpndokpendukung = array(
                'ID_DOKUMEN_PENDUKUNG' => $this->input->post('ID_DOKUMEN_PENDUKUNG', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'NAMA_DOKUMEN' => $this->input->post('NAMA_DOKUMEN', TRUE),
                //'LOKASI_DOKUMEN'     	=> $url,
                //'CREATED_TIME'     	=> time(),
                //'CREATED_BY'     		=> $this->session->userdata('USR'),
                //'CREATED_IP'     		=> $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $url_file = $this->input->post('OLD_FILE');
            if ($filependukung['error'] == 0) {
                $lhkpndokpendukung['LOKASI_DOKUMEN'] = @$url;
                $del = TRUE;
                unlink("./uploads/lhkpn/" . $id . "/$url_file");
            }
            $url_file = $this->input->post('OLD_FILE', TRUE);
            $this->mlhkpndokpendukung->update($lhkpndokpendukung['ID_DOKUMEN_PENDUKUNG'], $lhkpndokpendukung);
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $lhkpndokpendukung['ID_DOKUMEN_PENDUKUNG'] = $this->input->post('ID_DOKUMEN_PENDUKUNG', TRUE);
            $url_file = $this->input->post('OLD_FILE', TRUE);
            $this->mlhkpndokpendukung->delete($lhkpndokpendukung['ID_DOKUMEN_PENDUKUNG']);
            unlink("./uploads/lhkpn/" . $id . "/$url_file");
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

/** END SAVE UPDATE DELETE Dokumen Pendukung */

    /** Form Tambah Hutang
     * 
     * @return html form tambah Hutang
     */
    function addhutang($id_lhkpn = null) {
        $hutang = $this->daftar_hutang(2);
        $data = array(
            'form' => 'add',
            'id_lhkpn' => $id_lhkpn,
            'hutang' => $hutang,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hutang_form', $data);
    }

    /** Form Edit Hutang
     * 
     * @return html form edit Hutang
     */
    function edithutang($id) {
        $this->load->model('mlhkpnhutang', '', TRUE);
        $data = array(
            'form' => 'edit',
            'item' => $this->mlhkpnhutang->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hutang_form', $data);
    }

    /** Form Konfirmasi Hapus Hutang
     * 
     * @return html form konfirmasi hapus Hutang
     */
    function deletehutang($id) {
        $this->load->model('mlhkpnhutang', '', TRUE);
        $data = array(
            'form' => 'delete',
            'item' => $this->mlhkpnhutang->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hutang_form', $data);
    }

    /** Detail Hutang
     * 
     * @return html detail Hutang
     */
    function detailhutang($id) {
        $this->load->model('mlhkpnhutang', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mlhkpnhutang->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hutang_form', $data);
    }

    function perbandinganhutang($id, $tgl_lapor) {
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);
        $join = [
                ['table' => 'T_LHKPN as lhkpn', 'on' => 'lhkpn.ID_LHKPN = ' . 'data.ID_LHKPN'],
        ];

        $where1['data.ID_HUTANG'] = $id;
        $where1['lhkpn.TGL_LAPOR'] = $tgl_lapor;

        $select = '*';
        $itemA = $this->mglobal->get_data_all('T_LHKPN_HUTANG as data', $join, $where1, $select);
        $where2['data.ID_HUTANG'] = $itemA[0]->ID_HARTA;
        $itemB = $this->mglobal->get_data_all('T_LHKPN_HUTANG as data', $join, $where2, $select, NULL, ['lhkpn.TGL_LAPOR', 'DESC']);
        $data = array(
            'form' => 'perbandingan',
            'itemA' => $itemA,
            'itemB' => $itemB,
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hutang_form', $data);
    }

    //hapus pelepasan
    function deletepelepasanhutang($id_harta, $id_pelepasan) {
        $this->db->trans_begin();
        $this->mglobal->update('T_LHKPN_HUTANG', ['IS_PELEPASAN' => '0'], ['ID_HUTANG' => $id_harta]);
        $this->mglobal->delete('T_LHKPN_PELEPASAN_HUTANG', ['ID_HARTA' => $id_pelepasan]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Konfirmasi Pelepasan Harta Surat Berharga
     *
     * @return html form konfirmasi pelepasan Harta Surat Berharga
     */
    function pelepasanhutang($id, $id_lhkpn) {
        $this->load->model('mglobal', '', TRUE);

        $check = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HUTANG', NULL, ['ID_HARTA' => $id]);
        if (!empty($check)) {
            $data = $check[0];
            $do = 'update';
        } else {
            $do = 'insert';
            $data = [];
        }

        $data = array(
            'id_lhkpn' => $id_lhkpn,
            'id' => $id,
            'form' => 'pelepasan',
            'do' => $do,
            'data' => $data,
        );

        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_hutang_form', $data);
    }

    /* -------------------------------------------- */

    function showTable($id, $id_lhkpn = null, $mode = null) {
        // cek jika $id_lhkpn null
        if ($id_lhkpn == null) {
            die('invalid url');
        }
        // $id_lhkpn = 1;

        $array = [
            1 => 'ringkasan',
            2 => 'pribadi',
            3 => 'keluarga',
            4 => 'harta_bangunan',
            5 => 'harta_alat',
            6 => 'harta_perabotan',
            7 => 'surat',
            8 => 'kas',
            9 => 'harta_lainnya',
            10 => 'hutang',
            11 => 'penerimaan_kas',
            12 => 'pengeluaran_kas',
            13 => 'lampiran_1',
            14 => 'lampiran_2',
            15 => 'lampiran_3',
            16 => 'lampiran_4',
            17 => 'dokumen_pendukung',
            18 => 'jabatan',
            19 => 'lampiran_1_1',
        ];

        $data = [];

        $filter = '';
        $this->offset = 0;

        $data['id_lhkpn'] = $id_lhkpn;
        $joinMATA_UANG = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
        ];
        $joinMU_JENIS_BUKTI = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_KAS.KODE_JENIS', 'join' => 'left'],
        ];
        $joinsurrat = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_SURAT_BERHARGA.KODE_JENIS', 'join' => 'left'],
        ];
        $joinHB_LAIN = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_BERGERAK_LAIN.KODE_JENIS', 'join' => 'left'],
        ];
        $joinHARTA_LAIN = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_LAINNYA.KODE_JENIS', 'join' => 'left'],
        ];

        $where = NULL;
        $cari = @$this->input->post('cari');

        //jenis bukti
        $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI');
        $list_bukti = [];
        foreach ($jenis_bukti as $key) {
            $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
        }
        $data['list_bukti'] = $list_bukti;
        $data['pemanfaatan1'] = $this->daftar_pemanfaatan(1);
        $data['pemanfaatan2'] = $this->daftar_pemanfaatan(2);

        $data['status_lhkpn'] = @$this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], ['ID_LHKPN' => $id_lhkpn], 'STATUS')[0]->STATUS;

        // SELECT item verification
        $verificationItem = $this->mglobal->get_data_all('T_VERIFICATION_ITEM', null, ['ID_LHKPN' => $id_lhkpn]);
        foreach ($verificationItem as $key) {
            $data['verifItem'][$key->ITEMVER][$key->ID] = ['hasil' => $key->HASIL, 'catatan' => $key->CATATAN];
        }

        // SELECT hasil verifikasi
        $data['hasilVerifikasi'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $id_lhkpn], 'HASIL_VERIFIKASI')[0]->HASIL_VERIFIKASI);

        switch ($id) {
            case 1:
                $this->load->model('mlhkpn', '', TRUE);

                //perhitunganpengeluaran kas
                $whereperhitunganpengeluaran = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn'";
                $data['getPenka'] = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS', $whereperhitunganpengeluaran);

                //perhitunganpemaasukan kas
                $whereperhitunganpemaasukan = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn' ";
                $data['getPemka'] = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $whereperhitunganpemaasukan);

                $data['mode'] = $mode;

                $data['ringkasan'] = $this->mlhkpn->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $id_lhkpn))->result();
                $data['hartirak'] = $this->mlhkpn->summaryHarta($id_lhkpn, 'T_LHKPN_HARTA_TIDAK_BERGERAK', 'NILAI_PELAPORAN', 'sum_hartirak');
                $data['harger'] = $this->mlhkpn->summaryHarta($id_lhkpn, 'T_LHKPN_HARTA_BERGERAK', 'NILAI_PELAPORAN', 'sum_harger');
                $data['harger2'] = $this->mlhkpn->summaryHarta($id_lhkpn, 'T_LHKPN_HARTA_BERGERAK_LAIN', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harger2');
                $data['suberga'] = $this->mlhkpn->summaryHarta($id_lhkpn, 'T_LHKPN_HARTA_SURAT_BERHARGA', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_suberga');
                $data['kaseka'] = $this->mlhkpn->summaryHarta($id_lhkpn, 'T_LHKPN_HARTA_KAS', "REPLACE(NILAI_EQUIVALEN,'.','')", 'sum_kaseka');
                $data['harlin'] = $this->mlhkpn->summaryHarta($id_lhkpn, 'T_LHKPN_HARTA_LAINNYA', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harlin');
                $data['_hutang'] = $this->mlhkpn->summaryHarta($id_lhkpn, 'T_LHKPN_HUTANG', 'SALDO_HUTANG', 'sum_hutang');

                $data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
                $data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');

                break;
            case 2:
                $this->load->model('Mlhkpndatapribadi', '', TRUE);
                $data['mode'] = $mode;
                $data['lembaga'] = @$this->mglobal->get_data_all('M_INST_SATKER', NULL, NULL, '*', NULL);
                $data['DATA_PRIBADI'] = @$this->Mlhkpndatapribadi->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $id_lhkpn))->result()[0];
                break;
            case 3:
                $this->load->model('mlhkpnkeluarga', '', TRUE);
                $data['mode'] = $mode;
                $data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "ID_LHKPN = '$id_lhkpn'")[0];
                $data['KELUARGAS'] = $this->mlhkpnkeluarga->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $id_lhkpn))->result();
                $data['rinci_keluargas'] = $this->mlhkpnkeluarga->get_rincian($id_lhkpn);
                break;
            case 4:
                $param = '';
                $where_e = '';
                $cari_by = $this->input->post('cari_by');
                if ($cari_by == 'atas_nama' || $cari_by == '') {
                    $param = 'data.ATAS_NAMA';
                    if ($cari != '') {
                        $where_e = " AND $param LIKE '%$cari%'";
                    } else {
                        // $where_e = " and (data.NEGARA = '1')" ;
                    }
                } else if ($cari_by == 'no_bukti') {
                    $param = 'data.NOMOR_BUKTI';
                    if ($cari != '') {
                        $where_e = " AND $param LIKE '%$cari%'";
                    } else {
                        // $where_e = " and (data.NEGARA = '1')" ;
                    }
                } else {
                    $where_e = "AND CONCAT(IFNULL(KAB_KOT, ''), IFNULL(PROV, ''), IFNULL(NAMA_NEGARA, ''), JALAN, KEC, KEL) LIKE '%$cari%'";
                }

                $joinHARTA_TIDAK_BERGERAK = [
                        ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                        ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.ID = ID_NEGARA', 'join' => 'left'],
                        ['table' => 'M_AREA as area', 'on' => 'area.IDKOT = ID_NEGARA AND area.IDPROV = data.PROV', 'join' => 'left'],
                        // ['table' => 'M_KABKOT as kabkot'      , 'on' => 'kabkot.IDKOT   = data.KAB_KOT' ,   'join'  =>  'left'],
                        // ['table' => 'M_PROVINSI as provinsi'  , 'on' => 'provinsi.IDPROV = data.PROV'   ,   'join'  =>  'left']
                ];
                $KABKOT = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as KAB_KOT";
                $PROV = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND '' = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as PROV";
                $selectHARTA_TIDAK_BERGERAK = 'IS_CHECKED, data.NEGARA AS ID_NEGARA, NAMA_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEC as KEC, data.KEL as KEL,' . $KABKOT . ',' . $PROV . ', data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
                $where_eHARTA_TIDAK_BERGERAK = "data.ID_LHKPN = '$id_lhkpn'";
                $data['HARTA_TIDAK_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, NULL, [$selectHARTA_TIDAK_BERGERAK, FALSE], $where_eHARTA_TIDAK_BERGERAK . $where_e);
                // echo $this->db->last_query();
                // $join = [
                //             ['table' => 'M_NEGARA'                  , 'on' => 'M_NEGARA.ID = ID_NEGARA'       ,   'join'  =>  'left'],
                //             ['table' => 'M_AREA as provinsi'        , 'on' => 'provinsi.IDPROV = '.'data.PROV and provinsi.IDKOT = "" and provinsi.IDKEC = "" and provinsi.IDKEL = ""','join' => 'left'],
                //             ['table' => 'M_AREA as kabkot'          , 'on' => 'kabkot.IDKOT   = '.'data.KAB_KOT and provinsi.IDPROV = '.'data.PROV and kabkot.IDKEC = "" and kabkot.IDKEL = ""','join' => 'left'],
                //             ['table' => 'M_MATA_UANG'               , 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                //         ];
                // $where['data.ID_LHKPN' ]        = $id_lhkpn;
                // $KABKOT = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as KAB_KOT";
                // $data['LHKPN']                  = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN'      , 'on' => 'T_LHKPN.ID_PN   = '.'T_PN.ID_PN']], NULL, '*',  "ID_LHKPN = '$id_lhkpn'")[0];
                $data['mode'] = $mode;
                $data['cari'] = [$cari, $cari_by];
                $data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
                // $select                         = 'data.NEGARA AS ID_NEGARA, NAMA_NEGARA, IS_PELEPASAN, data.STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEL as KEL, data.KEC AS KEC, '.$KABKOT.', provinsi.NAME as PROV,  data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
                // $data['HARTA_TIDAK_BERGERAKS']  = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $join, $where, $select, $where_e);
                break;
            case 5:
                if ($cari != '') {
                    $data['cari'] = $cari;
                    $data['CARI_BY'] = @$this->input->post('CARI_BY');
                    if ($data['CARI_BY'] == 'MEREK') {
                        $where = "T_LHKPN_HARTA_BERGERAK.MEREK LIKE '%$cari%'";
                    } else if ($data['CARI_BY'] == 'NOPOL') {
                        $where = "T_LHKPN_HARTA_BERGERAK.NOPOL_REGISTRASI LIKE '%$cari%'";
                    } else if ($data['CARI_BY'] == 'ATASNAMA') {
                        $where = "T_LHKPN_HARTA_BERGERAK.NAMA LIKE '%$cari%'";
                    } else {
                        $where = "T_LHKPN_HARTA_BERGERAK.NAMA LIKE '%$cari%'";
                    }
                }

                $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA');
                $list_harta = [];
                foreach ($jenis_HARTA as $key) {
                    $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
                }

                $data['mode'] = $mode;

                $data['list_harta'] = $list_harta;
                $data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
                $data['HARTA_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinMATA_UANG, ['ID_LHKPN' => $id_lhkpn], '*', $where);
                break;
            case 6:
                if ($cari != '') {
                    $data['cari'] = $cari;
                    $data['CARI_BY'] = @$this->input->post('CARI_BY');
                    if ($data['CARI_BY'] == 'HARTA') {
                        $where = "T_LHKPN_HARTA_BERGERAK_LAIN.NAMA LIKE '%$cari%'";
                    } else if ($data['CARI_BY'] == 'PEMILIK') {
                        $where = "T_LHKPN_HARTA_BERGERAK_LAIN.ATAS_NAMA LIKE '%$cari%'";
                    } else {
                        $where = "T_LHKPN_HARTA_BERGERAK_LAIN.NAMA LIKE '%$cari%'";
                    }
                }

                $data['mode'] = $mode;

                $data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "ID_LHKPN= '$id_lhkpn'")[0];
                $data['HARTA_BERGERAK_LAINS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinHB_LAIN, ['ID_LHKPN' => $id_lhkpn], '*', $where, ['KODE_JENIS, NAMA', null, false]);
                $data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
                break;
            case 7:
                if ($cari != '') {
                    $data['cari'] = $cari;
                    $data['cariby'] = $this->input->post('cariby');

                    if ($data['cariby'] == 'Nama') {
                        $where = "T_LHKPN_HARTA_SURAT_BERHARGA.NAMA_SURAT_BERHARGA LIKE '%$cari%'";
                        // $this->db->like('T_LHKPNOFFLINE_PENERIMAAN.USERNAME', $this->CARI['TEXT']);
                    } else if ($data['cariby'] == 'Penerbit') {
                        $where = "T_LHKPN_HARTA_SURAT_BERHARGA.NAMA_PENERBIT LIKE '%$cari%'";
                        // $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
                    } else if ($data['cariby'] == 'AtasNama') {
                        $where = "T_LHKPN_HARTA_SURAT_BERHARGA.ATAS_NAMA LIKE '%$cari%'";
                        // $this->db->like('T_PN.NIK', $this->CARI['TEXT']);
                    } else {
                        $where = "T_LHKPN_HARTA_SURAT_BERHARGA.NAMA_SURAT_BERHARGA LIKE '%$cari%'";
                    }
                }
                // if($cari != ''){
                //     $where = "T_LHKPN_HARTA_SURAT_BERHARGA.NAMA_SURAT_BERHARGA LIKE '%$cari%'";
                //     $data['cari']   = $cari;
                // }

                $data['mode'] = $mode;

                $data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "ID_LHKPN = '$id_lhkpn'")[0];
                $data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinsurrat, ['ID_LHKPN' => $id_lhkpn], "*,REPLACE(NILAI_PELAPORAN,'.','') as PELAPORAN", $where);
                $data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
                break;
            case 8:
                if ($cari != '') {
                    if ($this->input->post('cariby') == '1') {
                        $where = "ATAS_NAMA_REKENING LIKE '%$cari%'";
                    } elseif ($this->input->post('cariby') == '2') {
                        $where = "NOMOR_REKENING LIKE '%$cari%'";
                    }
                }

                $data['mode'] = $mode;

                $data['cari'] = $cari;
                $data['cariby'] = $this->input->post('cariby');
                $data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "ID_LHKPN = '$id_lhkpn'")[0];
                $data['HARTA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMU_JENIS_BUKTI, ['ID_LHKPN' => $id_lhkpn], '*', $where);
                $data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
                break;
            case 9:
                if ($cari != '') {
                    if ($this->input->post('cariby') == '1') {
                        $where = "T_LHKPN_HARTA_LAINNYA.ATAS_NAMA LIKE '%$cari%'";
                    } elseif ($this->input->post('cariby') == '2') {
                        $where = "T_LHKPN_HARTA_LAINNYA.NAMA LIKE '%$cari%'";
                    }
                }

                $data['mode'] = $mode;

                $data['cari'] = $cari;
                $data['cariby'] = $this->input->post('cariby');
                $data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinHARTA_LAIN, ['ID_LHKPN' => $id_lhkpn], '*', $where);
                $data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
                break;
            case 10:
                $data['mode'] = $mode;

                $data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "ID_LHKPN = '$id_lhkpn'")[0];
                $data['HUTANGS'] = $this->mglobal->get_data_all('T_LHKPN_HUTANG', NULL, ['ID_LHKPN' => $id_lhkpn]);
                $data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
                break;
            case 11:
                $this->load->model('mlhkpn', '', TRUE);
                // $data['PENERIMAAN_KASS'] = $this->mlhkpn->get_paged_list($this->limit, $this->offset, array('ID_LHKPN'=>$id_lhkpn))->result();
                $data['ID_LHKPN'] = $id_lhkpn;

                $data['mode'] = $mode;

                $data['PENERIMAAN_KASS'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
                break;
            case 12:
                $this->load->model('mlhkpn', '', TRUE);

                $data['mode'] = $mode;

                $data['ID_LHKPN'] = $id_lhkpn;
                $data['PENGELUARAN_KASS'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
                break;
            case 13:

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

                //search

                if ($cari != '') {
                    $where = "A.NAMA LIKE '%$cari%'";
                    $data['cari'] = $cari;
                }
                //select lampiran pelepasan
                $selectlampiranpelepasan = 'A.JENIS_PELEPASAN_HARTA, A.TANGGAL_TRANSAKSI as TANGGAL_TRANSAKSI, A.NILAI_PELEPASAN as NILAI_PELEPASAN, A.NAMA as NAMA, A.ALAMAT as ALAMAT';
                $selectpelepasanhartatidakbergerak = ', B.ATAS_NAMA as ATAS_NAMA, B.LUAS_TANAH as LUAS_TANAH, B.LUAS_BANGUNAN as LUAS_BANGUNAN, B.NOMOR_BUKTI as NOMOR_BUKTI, B.JENIS_BUKTI as JENIS_BUKTI ';
                $selectpelepasanhartabergerak = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA as ATAS_NAMA, B.MEREK as MEREK, B.NOPOL_REGISTRASI as NOPOL_REGISTRASI, B.NOMOR_BUKTI as NOMOR_BUKTI';
                $selectpelepasanhartabergeraklain = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, ATAS_NAMA as ATAS_NAMA';
                $selectpelepasansuratberharga = ', B.KODE_JENIS as KODE_JENIS, B.NAMA_SURAT_BERHARGA as NAMA_SURAT,  B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, B.ATAS_NAMA as ATAS_NAMA';
                $selectpelepasankas = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA_REKENING as ATAS_NAMA, B.NAMA_BANK as NAMA_BANK, B.NOMOR_REKENING as NOMOR_REKENING';
                $selectpelepasanhartalainnya = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.ATAS_NAMA as ATAS_NAMA';

                // call data lampiran pelepasan
                $pelepasanhartatidakbergerak = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK as A', [['table' => 'T_LHKPN_HARTA_TIDAK_BERGERAK as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], $where, $selectlampiranpelepasan . $selectpelepasanhartatidakbergerak, "A.ID_LHKPN = '$id_lhkpn'");
                $pelepasanhartabergerak = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK as A', [['table' => 'T_LHKPN_HARTA_BERGERAK as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], $where, $selectlampiranpelepasan . $selectpelepasanhartabergerak, "A.ID_LHKPN = '$id_lhkpn'");
                $pelepasanhartabergeraklain = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN as A', [['table' => 'T_LHKPN_HARTA_BERGERAK_LAIN as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], $where, $selectlampiranpelepasan . $selectpelepasanhartabergeraklain, "A.ID_LHKPN = '$id_lhkpn'");
                $pelepasansuratberharga = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA as A', [['table' => 'T_LHKPN_HARTA_SURAT_BERHARGA as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], $where, $selectlampiranpelepasan . $selectpelepasansuratberharga, "A.ID_LHKPN = '$id_lhkpn'");
                $pelepasankas = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_KAS as A', [['table' => 'T_LHKPN_HARTA_KAS as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], $where, $selectlampiranpelepasan . $selectpelepasankas, "A.ID_LHKPN = '$id_lhkpn'");
                $pelepasanhartalainnya = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_LAINNYA as A', [['table' => 'T_LHKPN_HARTA_LAINNYA as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], $where, $selectlampiranpelepasan . $selectpelepasanhartalainnya, "A.ID_LHKPN = '$id_lhkpn'");
                $pelepasanmanual = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_MANUAL as A', NULL, NULL, '*', "A.ID_LHKPN = '$id_lhkpn'");
                $pelepasan = [];

                //packing hasil calling data lampiran pelepasan
                if (!empty($pelepasanhartatidakbergerak)) {
                    foreach ($pelepasanhartatidakbergerak as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => "Tanah/Bangunan , Atas Nama " . @$key->ATAS_NAMA . " dengan luas tanah " . @$key->LUAS_TANAH . " dan luas bangunan " . @$key->LUAS_BANGUNAN . " dengan bukti berupa " . $list_bukti[$key->JENIS_BUKTI] . " dengan nomor bukti " . @$key->NOMOR_BUKTI,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                            'STATUS' => '0'
                        ];
                    }
                }
                if (!empty($pelepasanhartabergerak)) {
                    foreach ($pelepasanhartabergerak as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => "Sebuah " . $list_harta[@$key->KODE_JENIS] . " , Atas Nama " . @$key->ATAS_NAMA . " , merek " . @$key->MEREK . " dengan nomor registrasi " . $key->NOPOL_REGISTRASI . " dan nomor bukti " . @$key->NOMOR_BUKTI,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                            'STATUS' => '0'
                        ];
                    }
                }
                if (!empty($pelepasanhartabergeraklain)) {
                    foreach ($pelepasanhartabergeraklain as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => $list_harta_berhenti[@$key->KODE_JENIS] . " bernama " . @$key->NAMA_HARTA . " , Atas nama " . @$key->ATAS_NAMA . " dengan jumlah " . @$key->JUMLAH . ' ' . @$key->SATUAN,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                            'STATUS' => '0'
                        ];
                    }
                }
                if (!empty($pelepasansuratberharga)) {
                    foreach ($pelepasansuratberharga as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => $list_harta_surat[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' berupa surat ' . @$key->NAMA_SURAT . ' dengan jumlah ' . @$key->JUMLAH . ' ' . @$key->SATUAN,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                            'STATUS' => '0'
                        ];
                    }
                }
                if (!empty($pelepasankas)) {
                    foreach ($pelepasankas as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => "KAS berupa " . $list_harta_kas[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' pada bank ' . @$key->NAMA_BANK . ' dengan nomor rekening ' . @$key->NOMOR_REKENING,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                            'STATUS' => '0'
                        ];
                    }
                }
                if (!empty($pelepasanhartalainnya)) {
                    foreach ($pelepasanhartalainnya as $key) {
                        $pelepasan[] = [
                            'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => "Harta lain berupa " . $list_harta_lain[@$key->KODE_JENIS] . ' dengan nama harta ' . @$key->NAMA_HARTA . ' atas nama ' . @$key->ATAS_NAMA,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                            'STATUS' => '0'
                        ];
                    }
                }

                if (!empty($pelepasanmanual)) {
                    foreach ($pelepasanmanual as $key) {
                        $pelepasan[] = [
                            'ID' => $key->ID,
                            'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya'),
                            'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                            'URAIAN_HARTA' => $key->URAIAN_HARTA,
                            'ALAMAT' => $key->ALAMAT,
                            'NILAI' => $key->NILAI_PELEPASAN,
                            'PIHAK_DUA' => $key->NAMA,
                            'STATUS' => '1'
                        ];
                    }
                }

                $data['mode'] = $mode;

                $data['lampiran_pelepasan'] = $pelepasan;
                break;
            case 14:
                if ($cari != '') {
                    $where = " and T_LHKPN_FASILITAS.NAMA_FASILITAS LIKE '%$cari%'";
                    $data['cari'] = $cari;
                }
                $data['mode'] = $mode;
                $data['lamp2s'] = $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*', "ID_LHKPN = '$id_lhkpn'" . @$where);
                break;
            case 15:
                $this->load->model('mlhkpnkeluarga', '', TRUE);

                $data['mode'] = $mode;
                $data['keluargas'] = $this->mlhkpnkeluarga->get_paged_list($this->limit, $this->offset, array('ID_LHKPN' => $id_lhkpn))->result();
                break;
            case 17:
                $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN';
                $joinJabatan = [
                        ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                        ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
                        ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                ];
                $data['mode'] = $mode;
                $data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$id_lhkpn'");
                $data['LHKPN'] = @$this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "ID_LHKPN = '$id_lhkpn'")[0];
                $data['DATA_PRIBADI'] = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, '*', "ID_LHKPN = '$id_lhkpn'")[0];
                $data['KAS'] = @$this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', NULL, "FILE_BUKTI <> '' AND KODE_JENIS <> '1'", '*', "ID_LHKPN = '$id_lhkpn'");
                break;
            case 18:
                $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN';
                $joinJabatan = [
                        ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                        ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
                        ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                ];
                $data['mode'] = $mode;
                $data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$id_lhkpn'");
                break;
            case 19:

                $where = NULL;
                if ($cari != '') {
                    $where = $cari;
                }

                $data['mode'] = $mode;
                $data['cari'] = $cari;
                $data['lampiran_hibah'] = $this->_lampiran_hibah(substr(md5($id_lhkpn), 5, 8), $where);
                break;
        };

        $data['LHKPN'] = @$this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], ["ID_LHKPN" => $id_lhkpn])[0];
        $data['is_pn'] = $this->session->userdata('IS_PN');
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_table_' . $array[$id], $data);
    }

    //provinsi
    function daftar_provinsi() {

        //$data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $provinsi = $this->mlhkpnharta->get_provinsi();

        $data[] = array('ID_PROV' => '', 'PROV' => '-Pilih Provinsi-');
        $num = 1;
        foreach ($provinsi as $key) {

            $data[] = array('ID_PROV' => $key->ID_PROV, 'PROV' => $key->NAME);
        }

        echo json_encode($data);
    }

    //provinsi
    function daftar_negara() {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $negara = $this->mlhkpnharta->get_negara();
        $data[''] = '-Pilih Negara-';
        foreach ($negara as $key) {
            $data[$key->KODE_ISO3] = $key->NAMA_NEGARA;
        }
        echo json_encode($data);
    }

    //sama dengam PN
    function samaPN($id) {
        $join = [
                ['table' => 'M_NEGARA', 'on' => 'T_LHKPN_DATA_PRIBADI.KD_ISO3_NEGARA = M_NEGARA.ID', 'join' => 'left'],
        ];
        $getAlamat = $this->mglobal->get_data_all("T_LHKPN_DATA_PRIBADI", $join, "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
        // display($getAlamat);
        if (!empty($getAlamat)) {
            $getAlamat = $getAlamat[0];
            if ($getAlamat->NEGARA == '2') {
                echo $getAlamat->ALAMAT_NEGARA . ', ' . $getAlamat->NAMA_NEGARA;
            } else {
                $kabkot = strtolower(getArea($getAlamat->PROVINSI, $getAlamat->KABKOT)[0]->NAME);
                $provinsi = strtolower(getArea($getAlamat->PROVINSI)[0]->NAME);
                echo $getAlamat->ALAMAT_RUMAH .
                ', Kelurahan ' . $getAlamat->KELURAHAN .
                ', Kecamatan ' . $getAlamat->KECAMATAN .
                ', Kabupaten/Kota ' . ucwords($kabkot) .
                ', Provinsi ' . ucwords($provinsi) .
                ($getAlamat->TELPON_RUMAH != '' ? ', No Telp Rumah ' . $getAlamat->TELPON_RUMAH : '');
            }
        }
    }

    function daftar_uang() {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $provinsi = $this->mlhkpnharta->get_MONEY();
        $data[''] = '-Pilih Mata Uang-';
        foreach ($provinsi as $key) {
            $data[$key->ID_MATA_UANG] = $key->SINGKATAN;
        }
        return $data;
    }

    function daftar_kabkot($id_prov) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $kabkot = $this->mlhkpnharta->get_kabkot($id_prov);
        foreach ($kabkot as $key) {
            if (substr($key->IDKOT, 0, 1) == 7) {
                // kota
                $data[$key->IDKOT] = ucwords(strtolower('Kota ' . $key->NAME));
            } else {
                $data[$key->IDKOT] = ucwords(strtolower('Kabupaten ' . $key->NAME));
            }
        }
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        echo json_encode($data);
    }

    function daftar_kec($id_prov, $id_kabkot) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $kec = $this->mlhkpnharta->get_kec($id_prov, $id_kabkot);
        $data[''] = '-Pilih Kecamatan-';
        foreach ($kec as $key) {
            $data[$key->IDKEC] = $key->NAME;
        }
        echo json_encode($data);
    }

    function daftar_kel($id_prov, $id_kabkot, $id_kec) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $kec = $this->mlhkpnharta->get_kel($id_prov, $id_kabkot, $id_kec);
        $data[''] = '-Pilih Kelurahan-';
        foreach ($kec as $key) {
            $data[$key->IDKEL] = $key->NAME;
        }
        echo json_encode($data);
    }

    function daftar_UK($id = '') {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $UK = $this->mlhkpnharta->get_unit($id);
        $data[''] = '-Pilih Unit Kerja-';
        foreach ($UK as $key) {
            $data[$key->UK_ID] = $key->UK_NAMA;
        }
        echo json_encode($data);
    }

    /* PENERIMAAN KAS */

    function add_penerimaan_pekerjaan($id_lhkpn = null) {
        $data = array(
            'form' => 'addPenerimaanPekerjaan',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    function add_penerimaan_kekayaan($id_lhkpn = null) {
        $data = array(
            'form' => 'addPenerimaanKekayaan',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    function add_penerimaan_lainnya($id_lhkpn = null) {
        $data = array(
            'form' => 'addPenerimaanLainnya',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    /* END PENERIMAAN KAS */

    /* PENGELUARAN KAS */

    function add_pengeluaran_umum($id_lhkpn = null) {
        $data = array(
            'form' => 'addPengeluaranUmum',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    function add_pengeluaran_harta($id_lhkpn) {
        $data = array(
            'form' => 'addPengeluaranHarta',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    function add_pengeluaran_lainnya($id_lhkpn = null) {
        $data = array(
            'form' => 'addPengeluaranLainnya',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_kas_form', $data);
    }

    /* END PENGELUARAN KAS */

    function savedatapribadi() {
        $this->db->trans_begin();
        $this->load->model('mlhkpndatapribadi', 'pribadi', TRUE);

        $type_file = array('.jpg', '.png', '.jpeg', '.pdf');
        $maxsize = 500000;
        $id = 'unknown';
        $url = '';
        $url_KTP = '';
        $url_NPWP = '';

        $user = $this->session->userdata('USR');
        if ($this->input->post('NIK', TRUE) != '') {
            $id = $this->input->post('NIK', TRUE);
        }

        //upload foto
        $filename = 'uploads/data_pribadi/' . $id . '/readme.txt';

        if (!file_exists($filename)) {

            $dir = './uploads/data_pribadi/' . $id . '/';

            $file_to_write = 'readme.txt';
            $content_to_write = "Foto Dari " . $user . ' dengan nik ' . $id;

            if (is_dir($dir) === false) {
                mkdir($dir);
            }

            $file = fopen($dir . '/' . $file_to_write, "w");

            fwrite($file, $content_to_write);

            // closes the file
            fclose($file);
        }

        $filependukung = @$_FILES['FILE_FOTO'];
        $extension = strtolower(@substr(@$filependukung['name'], -4));
        if (in_array($extension, $type_file) && $maxsize >= $filependukung['size']) {
            $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/data_pribadi/" . $id . "/", 0, 10000);
            if ($filependukung['size'] == '') {
                $url = '';
            } else {
                $url = time() . "-" . trim($filependukung['name']);
            }
        }


        //uploadKTP

        $filependukungKTP = @$_FILES['FILE_KTP'];
        $extension = strtolower(@substr(@$filependukungKTP['name'], -4));
        if (in_array($extension, $type_file) && $maxsize >= $filependukungKTP['size']) {
            $c = save_file($filependukungKTP['tmp_name'], $filependukungKTP['name'], $filependukungKTP['size'], "./uploads/data_pribadi/" . $id . "/", 0, 10000);
            if ($filependukungKTP['size'] == '') {
                $url_KTP = '';
            } else {
                $url_KTP = time() . "-" . trim($filependukungKTP['name']);
            }
        }

        //uploadNPWP

        $filependukungNPWP = @$_FILES['FILE_NPWP'];
        $extension = strtolower(@substr(@$filependukungNPWP['name'], -4));
        if (in_array($extension, $type_file) && $maxsize >= $filependukungNPWP['size']) {
            $c = save_file($filependukungNPWP['tmp_name'], $filependukungNPWP['name'], $filependukungNPWP['size'], "./uploads/data_pribadi/" . $id . "/", 0, 10000);
            if ($filependukungNPWP['size'] == '') {
                $url_NPWP = '';
            } else {
                $url_NPWP = time() . "-" . trim($filependukungNPWP['name']);
            }
        }


        //prosses save
        $jabatan = array(
            'JABATAN' => $this->input->post('JABATAN1', TRUE),
            'ESELON' => $this->input->post('ESELON1', TRUE),
            'LEMBAGA' => $this->input->post('LEMBAGA1', TRUE),
            'UNIT_KERJA' => $this->input->post('UNIT_KERJA1', TRUE),
            'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR1', TRUE),
            'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR1', TRUE),
        );
        $jabatan2 = array(
            'JABATAN' => $this->input->post('JABATAN2', TRUE),
            'ESELON' => $this->input->post('ESELON2', TRUE),
            'LEMBAGA' => $this->input->post('LEMBAGA2', TRUE),
            'UNIT_KERJA' => $this->input->post('UNIT_KERJA2', TRUE),
            'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR2', TRUE),
            'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR2', TRUE),
        );

        $hp_etc = array(
            'HP_ETC1' => $this->input->post('HP1', TRUE),
            'HP_ETC2' => $this->input->post('HP2', TRUE),
            'HP_ETC3' => $this->input->post('HP3', TRUE),
        );

        $ngr = $this->input->post('NEGARA', TRUE);
        if ($ngr == 2) {
            $negara = $this->input->post('NEGARA', TRUE);
            $kd_iso3 = $this->input->post('KD_ISO3_NEGARA', TRUE);
            $alamat_negara = $this->input->post('ALAMAT_NEGARA', TRUE);
            $alamat_rumah = NULL;
            $provinsi = NULL;
            $kabkot = NULL;
            $kecamatan = NULL;
        } else {
            $negara = $this->input->post('NEGARA', TRUE);
            $kd_iso3 = NULL;
            $alamat_negara = NULL;
            $alamat_rumah = $this->input->post('alamat_rumah', TRUE);
            $provinsi = $this->input->post('PROVINSI', TRUE);
            $kabkot = $this->input->post('KAB_KOT', TRUE);
            $kecamatan = $this->input->post('KECAMATAN', TRUE);
        }

        $lhkpndatapribadi = array(
            'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
            'GELAR_DEPAN' => $this->input->post('gelar_depan', TRUE),
            'GELAR_BELAKANG' => $this->input->post('gelar_belakang', TRUE),
            'NAMA_LENGKAP' => $this->input->post('nama_lengkap', TRUE),
            'JENIS_KELAMIN' => $this->input->post('JENIS_KELAMIN', TRUE),
            'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR', TRUE),
            'TANGGAL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL_LAHIR', TRUE)))),
            'NIK' => $this->input->post('NIK', TRUE),
            'NPWP' => $this->input->post('NPWP', TRUE),
            'STATUS_PERKAWINAN' => $this->input->post('STATUS', TRUE),
            'AGAMA' => $this->input->post('AGAMA_RADIO', TRUE),
            // 'JABATAN'                   => json_encode($jabatan),
            // 'JABATAN_LAINNYA'           => json_encode($jabatan2),     
            'ALAMAT_RUMAH' => $this->input->post('alamat_rumah', TRUE),
            'EMAIL_PRIBADI' => $this->input->post('EMAIL_PRIBADI', TRUE),
            'PROVINSI' => $provinsi,
            'KABKOT' => $kabkot,
            'KECAMATAN' => $kecamatan,
            'TELPON_RUMAH' => $this->input->post('TELP_RUMAH', TRUE),
            'HP' => $this->input->post('HP', TRUE),
            'HP_LAINNYA' => json_encode($hp_etc),
            'NEGARA' => $negara,
            'KD_ISO3_NEGARA' => $kd_iso3,
            'ALAMAT_NEGARA' => $alamat_negara,
            'IS_ACTIVE' => '1',
        );
        $lhkpndatapribadi['FOTO'] = @$url;
        $lhkpndatapribadi['FILE_KTP'] = @$url_KTP;
        $lhkpndatapribadi['FILE_NPWP'] = @$url_NPWP;

        $this->pribadi->save($lhkpndatapribadi);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('message', 'Berhasil');
            // redirect(base_url('#index.php/efill/lhkpn/entry/'.$this->input->post('ID_LHKPN', TRUE)), 'refresh');
        }
        echo intval($this->db->trans_status());
    }

    function updatedatapribadi() {
        $this->db->trans_begin();
        $this->load->model('mlhkpndatapribadi', 'pribadi', TRUE);

        $id = 'unknown';
        $type_file = array('.jpg', '.png', '.jpeg', '.pdf');
        $maxsize = 500000;
        $user = $this->session->userdata('USR');
        if ($this->input->post('NIK', TRUE) != '') {
            $id = $this->input->post('NIK', TRUE);
        }

        //update foto
        $filename = 'uploads/data_pribadi/' . $id . '/readme.txt';

        if (!file_exists($filename)) {

            $dir = './uploads/data_pribadi/' . $id . '/';

            $file_to_write = 'readme.txt';
            $content_to_write = "Foto Dari " . $user . ' dengan nik ' . $id;

            if (is_dir($dir) === false) {
                mkdir($dir);
            }

            $file = fopen($dir . '/' . $file_to_write, "w");

            fwrite($file, $content_to_write);

            // closes the file
            fclose($file);
        }
        $url = '';
        $url_NPWP = '';
        $url_ktp = '';

        $filependukung = (isset($_FILES['FILE_FOTO'])) ? $_FILES['FILE_FOTO'] : '';
        $del = FALSE;


        if ($filependukung['error'] == 0) {
            $extension = strtolower(@substr(@$filependukung['name'], -4));
            if (in_array($extension, $type_file) && $filependukung['size'] <= $maxsize) {
                $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/data_pribadi/" . $id . "/", 0, 10000);
                $url = time() . "-" . trim($filependukung['name']);
            }
        }

        //update ktp

        $filependukungktp = (isset($_FILES['FILE_KTP'])) ? $_FILES['FILE_KTP'] : '';
        $del = FALSE;

        // if($filependukungktp == ''){
        //     echo 0;exit();
        // }

        if ($filependukungktp['error'] == 0) {
            $extension = strtolower(@substr(@$filependukungktp['name'], -4));
            if (in_array($extension, $type_file) && $filependukungktp['size']) {
                $c = save_file($filependukungktp['tmp_name'], $filependukungktp['name'], $filependukungktp['size'], "./uploads/data_pribadi/" . $id . "/", 0, 10000);
                $url_ktp = time() . "-" . trim($filependukungktp['name']);
            }
        }

        //update npwp

        $filependukungnpwp = (isset($_FILES['FILE_NPWP'])) ? $_FILES['FILE_NPWP'] : '';
        $del = FALSE;
        $del_NPWP = FALSE;
        $del_KTP = FALSE;

        // if($filependukungnpwp == ''){
        //     echo 0;exit();
        // }

        if ($filependukungnpwp['error'] == 0) {
            $extension = strtolower(@substr(@$filependukungnpwp['name'], -4));
            if (in_array($extension, $type_file) && $filependukungnpwp['size']) {
                $c = save_file($filependukungnpwp['tmp_name'], $filependukungnpwp['name'], $filependukungnpwp['size'], "./uploads/data_pribadi/" . $id . "/", 0, 10000);
                $url_npwp = time() . "-" . trim($filependukungnpwp['name']);
            }
        }


        // echo $id;

        $jabatan = array(
            'JABATAN' => $this->input->post('JABATAN1', TRUE),
            'ESELON' => $this->input->post('ESELON1', TRUE),
            'LEMBAGA' => $this->input->post('LEMBAGA1', TRUE),
            'UNIT_KERJA' => $this->input->post('UNIT_KERJA1', TRUE),
            'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR1', TRUE),
            'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR1', TRUE),
        );
        $jabatan2 = array(
            'JABATAN' => $this->input->post('JABATAN2', TRUE),
            'ESELON' => $this->input->post('ESELON2', TRUE),
            'LEMBAGA' => $this->input->post('LEMBAGA2', TRUE),
            'UNIT_KERJA' => $this->input->post('UNIT_KERJA2', TRUE),
            'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR2', TRUE),
            'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR2', TRUE),
        );

        $hp_etc = array(
            'HP_ETC1' => $this->input->post('HP1', TRUE),
            'HP_ETC2' => $this->input->post('HP2', TRUE),
            'HP_ETC3' => $this->input->post('HP3', TRUE),
        );

        $ngr = $this->input->post('NEGARA', TRUE);
        if ($ngr == 2) {
            $negara = $this->input->post('NEGARA', TRUE);
            $kd_iso3 = $this->input->post('KD_ISO3_NEGARA', TRUE);
            $alamat_negara = $this->input->post('ALAMAT_NEGARA', TRUE);
            $alamat_rumah = NULL;
            $provinsi = NULL;
            $kabkot = NULL;
            $kecamatan = NULL;
            $Kelurahan = NULL;
        } else {
            $negara = $this->input->post('NEGARA', TRUE);
            $kd_iso3 = NULL;
            $alamat_negara = NULL;
            $alamat_rumah = $this->input->post('alamat_rumah', TRUE);
            $provinsi = $this->input->post('PROVINSI', TRUE);
            $kabkot = $this->input->post('KAB_KOT', TRUE);
            $kecamatan = $this->input->post('KECAMATAN', TRUE);
            $kelurahan = $this->input->post('KELURAHAN', TRUE);
        }

        $lhkpndatapribadi = array(
            'GELAR_DEPAN' => $this->input->post('gelar_depan', TRUE),
            'GELAR_BELAKANG' => $this->input->post('gelar_belakang', TRUE),
            'NAMA_LENGKAP' => $this->input->post('nama_lengkap', TRUE),
            'JENIS_KELAMIN' => $this->input->post('JENIS_KELAMIN', TRUE),
            'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR', TRUE),
            'TANGGAL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL_LAHIR', TRUE)))),
            // 'NIK'                       => $this->input->post('NIK', TRUE),
            'NPWP' => $this->input->post('NPWP', TRUE),
            'STATUS_PERKAWINAN' => $this->input->post('STATUS', TRUE),
            'AGAMA' => $this->input->post('AGAMA_RADIO', TRUE),
            // 'JABATAN'                   => json_encode($jabatan),
            // 'JABATAN_LAINNYA'           => json_encode($jabatan2),     
            'ALAMAT_RUMAH' => $this->input->post('alamat_rumah', TRUE),
            'EMAIL_PRIBADI' => $this->input->post('EMAIL_PRIBADI', TRUE),
            'PROVINSI' => $provinsi,
            'KABKOT' => $kabkot,
            'KECAMATAN' => $kecamatan,
            'KELURAHAN' => $kelurahan,
            'TELPON_RUMAH' => $this->input->post('TELP_RUMAH', TRUE),
            'HP' => $this->input->post('HP', TRUE),
            'HP_LAINNYA' => json_encode($hp_etc),
            'NEGARA' => $negara,
            'KD_ISO3_NEGARA' => $kd_iso3,
            'ALAMAT_NEGARA' => $alamat_negara
        );

        //save foto
        $url_file = $this->input->post('OLD_FILE', TRUE);
        if ($filependukung['error'] == 0) {
            $extension = strtolower(@substr(@$filependukung['name'], -4));
            if (in_array($extension, $type_file) && $filependukung['size'] <= $maxsize) {
                $lhkpndatapribadi['FOTO'] = @$url;
                $del = TRUE;
                if ($url_file !== '') {
                    @unlink("./uploads/data_pribadi/" . $id . "/$url_file");
                }
            }
        }
        //SAVE KTP
        $url_file_KTP = $this->input->post('OLD_FILE_KTP', TRUE);
        if ($filependukungktp['error'] == 0) {
            $extension = strtolower(@substr(@$filependukungktp['name'], -4));
            if (in_array($extension, $type_file) && $filependukungktp['size']) {
                $lhkpndatapribadi['FILE_KTP'] = @$url_ktp;
                $del_KTP = TRUE;
                if ($url_file_KTP !== '') {
                    @unlink("./uploads/data_pribadi/" . $id . "/$url_file_KTP");
                }
            }
        }
        //SAVE NPWP
        $url_file_NPWP = $this->input->post('OLD_FILE_NPWP', TRUE);
        if ($filependukungnpwp['error'] == 0) {
            $extension = strtolower(@substr(@$filependukungnpwp['name'], -4));
            if (in_array($extension, $type_file) && $filependukungnpwp['size']) {
                $lhkpndatapribadi['FILE_NPWP'] = @$url_npwp;
                $del_NPWP = TRUE;
                if ($url_file_NPWP !== '') {
                    @unlink("./uploads/data_pribadi/" . $id . "/$url_file_NPWP");
                }
            }
        }
        $this->pribadi->update($this->input->post('id_pribadi', TRUE), $lhkpndatapribadi);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->load->model('mglobal');
            $id_pn = $this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', [['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN=T_LHKPN_DATA_PRIBADI.ID_LHKPN']], ['T_LHKPN_DATA_PRIBADI.ID' => $this->input->post('id_pribadi', TRUE)])[0]->ID_PN;

            $data = [];
            // $data['NIK']            = $this->input->post('NIK', TRUE);
            $data['NAMA'] = $this->input->post('nama_lengkap', TRUE);
            $data['TEMPAT_LAHIR'] = $this->input->post('TEMPAT_LAHIR', TRUE);
            $data['TGL_LAHIR'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL_LAHIR', TRUE))));
            $data['ID_AGAMA'] = $this->input->post('AGAMA_RADIO', TRUE);
            $data['NPWP'] = $this->input->post('NPWP', TRUE);
            $data['NO_HP'] = $this->input->post('HP', TRUE);
            $data['JNS_KEL'] = $this->input->post('JENIS_KELAMIN', TRUE);
            $data['TGL_LAHIR'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL_LAHIR', TRUE))));
            $data['ALAMAT_TINGGAL'] = $this->input->post('alamat_rumah', TRUE);
            $data['EMAIL'] = $this->input->post('EMAIL_PRIBADI', TRUE);
            $data['PROV'] = $provinsi;
            $data['KEC'] = $kecamatan;
            $data['KEL'] = $kelurahan;
            $data['KAB_KOT'] = $kabkot;
            $data['NEGARA'] = (!empty($kd_iso3)) ? $kd_iso3 : $ngr;
            $data['LOKASI_NEGARA'] = $alamat_negara;

            if (!empty($url) || $url !== '') {
                $data['FOTO'] = $url;
            }

            $result = $this->mglobal->update('T_PN', $data, ['ID_PN' => $id_pn]);

            $data2['HANDPHONE'] = $this->input->post('HP', TRUE);
            $data2['EMAIL'] = $this->input->post('EMAIL_PRIBADI', TRUE);
            $data2['NAMA'] = $this->input->post('nama_lengkap', TRUE);

            if (!empty($url) || $url !== '') {
                $data2['PHOTO'] = $url;
            }

            $result2 = $this->mglobal->update('T_USER', $data2, ['IS_ACTIVE' => '1', 'EMAIL' => $this->input->post('old_email'), 'USERNAME' => $this->input->post('NIK')]);

            if ($result && $result2) {
                $this->db->trans_commit();
                $this->session->set_flashdata('message', 'Berhasil');
            } else {
                $this->db->trans_rollback();
            }
            // redirect(base_url('#index.php/efill/lhkpn/entry/'.$this->input->post('ID_LHKPN', TRUE)), 'refresh');
        }
        echo intval($this->db->trans_status());
    }

    /* LAMPIRAN 1 */

    function add_lampiran1($id_lhkpn = null) {
        $data = array(
            'form' => 'add',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_lampiran1_form', $data);
    }

    function edit_lampiran1($id) {
        $data = array(
            'form' => 'edit',
            'item' => $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_MANUAL', NULL, ['SUBSTRING(md5(ID), 6, 8) =' => $id])[0],
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_lampiran1_form', $data);
    }

    function delete_lampiran1($id) {
        $data = array(
            'form' => 'delete',
            'item' => $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_MANUAL', NULL, ['SUBSTRING(md5(ID), 6, 8) =' => $id])[0]
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_lampiran1_form', $data);
    }

    function detail_lampiran1($id) {
        $this->load->model('mlhkpn_lampiran1', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mlhkpn_lampiran1->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_lampiran1_form', $data);
    }

    /* END LAMPIRAN 1 */

    /* LAMPIRAN 2 */

    function add_lampiran2($id_lhkpn = null) {
        $data = array(
            'form' => 'add',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_lampiran2_form', $data);
    }

    function edit_lampiran2($id) {
        $this->load->model('mlhkpn_lampiran2', '', TRUE);
        $data = array(
            'form' => 'edit',
            'item' => $this->mlhkpn_lampiran2->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_lampiran2_form', $data);
    }

    function delete_lampiran2($id) {
        $this->load->model('mlhkpn_lampiran2', '', TRUE);
        $data = array(
            'form' => 'delete',
            'item' => $this->mlhkpn_lampiran2->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_lampiran2_form', $data);
    }

    function detail_lampiran2($id) {
        $this->load->model('mlhkpn_lampiran2', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mlhkpn_lampiran2->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_lampiran2_form', $data);
    }

    function save_lampiran1() {
        $this->db->trans_begin();
        $this->load->model('mlhkpn_lampiran2', '', TRUE);

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $data = [
                'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                'NAMA' => $this->input->post('NAMA'),
                'ALAMAT' => $this->input->post('ALAMAT'),
                'URAIAN_HARTA' => $this->input->post('KETERANGAN')
            ];

            $result = $this->mglobal->insert('T_LHKPN_PELEPASAN_MANUAL', $data);
        } else if ($this->input->post('act', true) == 'doupdate') {
            $data = [
                'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS'),
                'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL')))),
                'NILAI_PELEPASAN' => str_replace('.', '', $this->input->post('NILAI')),
                'NAMA' => $this->input->post('NAMA'),
                'ALAMAT' => $this->input->post('ALAMAT'),
                'URAIAN_HARTA' => $this->input->post('KETERANGAN')
            ];
            $data['ID'] = $this->input->post('ID', true);
            $this->mglobal->update('T_LHKPN_PELEPASAN_MANUAL', $data, ['ID' => $data['ID']]);
        } else {
            $ID = $this->input->post('ID');
            $this->mglobal->delete('T_LHKPN_PELEPASAN_MANUAL', ['ID' => $ID]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    function save_lampiran2() {
        $this->db->trans_begin();
        $this->load->model('mlhkpn_lampiran2', '', TRUE);

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $lhkpnLamp2 = array(
                'JENIS_FASILITAS' => $this->input->post('JENIS_FASILITAS', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'NAMA_FASILITAS' => $this->input->post('NAMA_FASILITAS', TRUE),
                'PEMBERI_FASILITAS' => $this->input->post('PEMBERI_FASILITAS', TRUE),
                'KETERANGAN' => $this->input->post('KETERANGAN', TRUE),
                'KETERANGAN_LAIN' => $this->input->post('KETERANGAN_LAIN'),
                'IS_ACTIVE' => 1,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $this->mlhkpn_lampiran2->save($lhkpnLamp2);
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $lhkpnLamp2 = array(
                'JENIS_FASILITAS' => $this->input->post('JENIS_FASILITAS', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                'NAMA_FASILITAS' => $this->input->post('NAMA_FASILITAS', TRUE),
                'PEMBERI_FASILITAS' => $this->input->post('PEMBERI_FASILITAS', TRUE),
                'KETERANGAN' => $this->input->post('KETERANGAN', TRUE),
                'IS_ACTIVE' => 1,
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $lhkpnLamp2['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpn_lampiran2->update($lhkpnLamp2['ID'], $lhkpnLamp2);
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $lhkpnLamp2['ID'] = $this->input->post('ID', TRUE);
            $this->mlhkpn_lampiran2->delete($lhkpnLamp2['ID']);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /* END LAMPIRAN 2 */

    function _savePK($ID_LHKPN, $GROUP_JENIS, $KODE_JENIS, $PENERIMAANPN, $PENERIMAANPASANGAN) {
        $a = array('A0' => 'Gaji dan tunjangan',
            'A1' => 'Penghasilan dari profesi / keahlian',
            'A2' => 'Honorarium',
            'A3' => 'Tantiem, bonus, jasa produksi, THR',
            'A4' => 'Penerimaan dari pekerjaan lainnya : lainnya ',
            'B0' => 'Hasil investasi dalam surat berharga',
            'B1' => 'Hasil usaha',
            'B2' => 'Bunga tabungan, bunga deposito, dan lainnya',
            'B3' => 'Hasil sewa',
            'B4' => 'Penjualan atau pelepasan harta',
            'B5' => 'Penerimaan dari pekerjaan lainnya : lainnya ',
            'C0' => 'Perolehan hutang',
            'C1' => 'Penerimaan warisan',
            'C2' => 'Penerimaan hibah / hadiah',
            'C3' => 'Penerimaan dari pekerjaan lainnya : lainnya ',);

        $JENIS_PENERIMAAN = $a[$KODE_JENIS];

        $this->db->from('T_LHKPN_PENERIMAAN_KAS2');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where('KODE_JENIS', $KODE_JENIS);
        if ($this->db->get('')->num_rows()) {
            $this->db->flush_cache();
            // update
            $data = array(
                'ID_LHKPN' => $ID_LHKPN,
                'GROUP_JENIS' => $GROUP_JENIS,
                'KODE_JENIS' => $KODE_JENIS,
                'JENIS_PENERIMAAN' => $JENIS_PENERIMAAN,
                'PN' => str_replace('.', '', $PENERIMAANPN),
                'PASANGAN' => str_replace('.', '', $PENERIMAANPASANGAN),
            );
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->where('KODE_JENIS', $KODE_JENIS);
            $this->db->update('T_LHKPN_PENERIMAAN_KAS2', $data);
        } else {
            $this->db->flush_cache();
            // insert
            $data = array(
                'ID_LHKPN' => $ID_LHKPN,
                'GROUP_JENIS' => $GROUP_JENIS,
                'KODE_JENIS' => $KODE_JENIS,
                'JENIS_PENERIMAAN' => $JENIS_PENERIMAAN,
                'PN' => str_replace('.', '', $PENERIMAANPN),
                'PASANGAN' => str_replace('.', '', $PENERIMAANPASANGAN),
            );
            $this->db->insert('T_LHKPN_PENERIMAAN_KAS2', $data);
        }
    }

    function _savePK2($ID_LHKPN, $GROUP_JENIS, $KODE_JENIS, $JML) {
        $a = array('A0' => 'Biaya Rumah Tangga',
            'A1' => 'Biaya Transportasi',
            'A2' => 'Biaya Pendidikan',
            'A3' => 'Biaya Kesehatan',
            'A4' => 'Biaya Keagamaan/Adat',
            'A5' => 'Biaya Rekreasi',
            'A6' => 'Pembayaran Pajak',
            'B0' => 'Pembelian atau Perolehan Harta Baru',
            'B1' => 'Rehabilitasi / Renovasi / Modifikasi Harta',
            'C0' => 'Biaya Pengurusan Waris/hibah/hadiah',
            'C1' => 'Pelunasan/Angsuran Hutang',);

        $JENIS_PENGELUARAN = $a[$KODE_JENIS];

        $this->db->from('T_LHKPN_PENGELUARAN_KAS2');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where('KODE_JENIS', $KODE_JENIS);
        if ($this->db->get('')->num_rows()) {
            $this->db->flush_cache();
            // update
            $data = array(
                'ID_LHKPN' => $ID_LHKPN,
                'GROUP_JENIS' => $GROUP_JENIS,
                'KODE_JENIS' => $KODE_JENIS,
                'JENIS_PENGELUARAN' => $JENIS_PENGELUARAN,
                'JML' => str_replace('.', '', $JML),
            );
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->where('KODE_JENIS', $KODE_JENIS);
            $this->db->update('T_LHKPN_PENGELUARAN_KAS2', $data);
        } else {
            $this->db->flush_cache();
            // insert
            $data = array(
                'ID_LHKPN' => $ID_LHKPN,
                'GROUP_JENIS' => $GROUP_JENIS,
                'KODE_JENIS' => $KODE_JENIS,
                'JENIS_PENGELUARAN' => $JENIS_PENGELUARAN,
                'JML' => str_replace('.', '', $JML),
            );
            $this->db->insert('T_LHKPN_PENGELUARAN_KAS2', $data);
        }
    }

    function savePK() {
        $label = array('A', 'B', 'C');
        $pn_A = $this->input->post('f_fild_PN_A');
        $pn_B = $this->input->post('f_fild_PN_B');
        $pn_C = $this->input->post('f_fild_PN_C');
        $pa = $this->input->post('f_fild_PASANGAN');
        $lain = $this->input->post('LAINNYA');
        $data_A = [];
        $data_B = [];
        $data_C = [];
        $PA = [];
        $LAIN = [];
        $ID_LHKPN = $this->input->post('ID_LHKPN');

        for ($i = 0; $i < count($pn_A); $i++) {
            $data_A['A' . $i] = $pn_A[$i];
            $this->_savePK($ID_LHKPN, 'A', 'A' . $i, $pn_A[$i], $pa[$i]);
        }
        for ($i = 0; $i < count($pn_B); $i++) {
            $data_B['B' . $i] = $pn_B[$i];
            $this->_savePK($ID_LHKPN, 'B', 'B' . $i, $pn_B[$i], 0);
        }
        for ($i = 0; $i < count($pn_C); $i++) {
            $data_C['C' . $i] = $pn_C[$i];
            $this->_savePK($ID_LHKPN, 'C', 'C' . $i, $pn_C[$i], 0);
        }
        for ($i = 0; $i < count($pa); $i++) {
            $PA['PA' . $i] = $pa[$i];
        }
        for ($i = 0; $i < count($lain); $i++) {
            $LAIN[$label[$i]] = $lain[$i];
        }
        $json = array(
            'A' => $data_A,
            'B' => $data_B,
            'C' => $data_C
        );
        $this->db->trans_begin();
        $this->load->model('mlhkpn', '', TRUE);

        $post = $this->input->post();
        $ID = $post['ID_LHKPN'];
        $arr = array();
        $getData = $this->mlhkpn->getData('T_LHKPN_PENERIMAAN_KAS', $ID);
        if ($getData != $arr) {
            $deletId = $this->mlhkpn->delData('T_LHKPN_PENERIMAAN_KAS', $ID);
            // for($i   = 0; $i < count($post['id_jenis']); $i++){
            $param['ID_LHKPN'] = $ID;
            $param['IS_ACTIVE'] = '1';
            $param['LAINNYA'] = json_encode($LAIN);
            // $param['ID_JENIS_PENERIMAAN_KAS']           = $post['id_jenis'][$i];
            $param['NILAI_PENERIMAAN_KAS_PN'] = json_encode($json);
            $param['NILAI_PENERIMAAN_KAS_PASANGAN'] = json_encode($PA);
            $this->mlhkpn->insertPK('T_LHKPN_PENERIMAAN_KAS', $param);
            // }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                // $this->session->set_flashdata('message', 'Berhasil');
                // redirect(base_url('#index.php/efill/lhkpn/entry/'.$this->input->post('ID_LHKPN', TRUE)), 'refresh');
            }
            echo intval($this->db->trans_status());
        } else {
            // for($i   = 0; $i < count($post['id_jenis']); $i++){
            $param['ID_LHKPN'] = $ID;
            $param['IS_ACTIVE'] = '1';
            $param['LAINNYA'] = json_encode($LAIN);
            // $param['ID_JENIS_PENERIMAAN_KAS']           = $post['id_jenis'][$i];
            $param['NILAI_PENERIMAAN_KAS_PN'] = json_encode($json);
            $param['NILAI_PENERIMAAN_KAS_PASANGAN'] = json_encode($PA);
            $this->mlhkpn->insertPK('T_LHKPN_PENERIMAAN_KAS', $param);
            // }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                // $this->session->set_flashdata('message', 'Berhasil');
                // redirect(base_url('#index.php/efill/lhkpn/entry/'.$this->input->post('ID_LHKPN', TRUE)), 'refresh');
            }
            echo intval($this->db->trans_status());
        }
    }

    // function savePK2(){
    //     $this->db->trans_begin();
    //     $this->load->model('mlhkpn', '', TRUE);
    //     $post    = $this->input->post();
    //     $ID      = $post['ID_LHKPN'];
    //     $arr     = array();
    //     $getData = $this->mlhkpn->getData('T_LHKPN_PENGELUARAN_KAS',$ID);
    //     if($getData != $arr){
    //         $deletId = $this->mlhkpn->delData('T_LHKPN_PENERIMAAN_KAS',$ID);
    //         for($i   = 0; $i < count($post['id_jenis']); $i++){
    //             $param['ID_LHKPN']                 = $ID;
    //             $param['IS_ACTIVE']                = '1';
    //             $param['ID_JENIS_PENGELUARAN_KAS']  = $post['id_jenis'][$i];
    //             $param['NILAI_PENGELUARAN_KAS']     = $post['f_fild'][$i];
    //             $this->mlhkpn->insertPK('T_LHKPN_PENGELUARAN_KAS',$param);
    //         }
    //         if ($this->db->trans_status() === FALSE){
    //             $this->db->trans_rollback();
    //         }else{
    //             $this->db->trans_commit();
    //             // $this->session->set_flashdata('message', 'Berhasil');
    //             // redirect(base_url('#index.php/efill/lhkpn/entry/'.$this->input->post('ID_LHKPN', TRUE)), 'refresh');
    //         }
    //         echo intval($this->db->trans_status());
    //     }else{
    //         for($i   = 0; $i < count($post['id_jenis']); $i++){
    //             $param['ID_LHKPN']                 = $ID;
    //             $param['IS_ACTIVE']                = '1';
    //             $param['ID_JENIS_PENGELUARAN_KAS']  = $post['id_jenis'][$i];
    //             $param['NILAI_PENGELUARAN_KAS']     = $post['f_fild'][$i];
    //             $this->mlhkpn->insertPK('T_LHKPN_PENGELUARAN_KAS',$param);
    //         }
    //         if ($this->db->trans_status() === FALSE){
    //             $this->db->trans_rollback();
    //         }else{
    //             $this->db->trans_commit();
    //             // $this->session->set_flashdata('message', 'Berhasil');
    //             // redirect(base_url('#index.php/efill/lhkpn/entry/'.$this->input->post('ID_LHKPN', TRUE)), 'refresh');
    //         }
    //         echo intval($this->db->trans_status());
    //     }
    // }

    function savePK2() {
        $label = array('A', 'B', 'C');
        $pn_A = $this->input->post('f_fild_A');
        $pn_B = $this->input->post('f_fild_B');
        $pn_C = $this->input->post('f_fild_C');
        $lain = $this->input->post('LAINNYA');
        $data_A = [];
        $data_B = [];
        $data_C = [];
        $LAIN = [];
        $ID_LHKPN = $this->input->post('ID_LHKPN');

        for ($i = 0; $i < count($pn_A); $i++) {
            $data_A['A' . $i] = $pn_A[$i];
            $this->_savePK2($ID_LHKPN, 'A', 'A' . $i, $pn_A[$i]);
        }
        for ($i = 0; $i < count($pn_B); $i++) {
            $data_B['B' . $i] = $pn_B[$i];
            $this->_savePK2($ID_LHKPN, 'B', 'B' . $i, $pn_A[$i]);
        }
        for ($i = 0; $i < count($pn_C); $i++) {
            $data_C['C' . $i] = $pn_C[$i];
            $this->_savePK2($ID_LHKPN, 'C', 'C' . $i, $pn_A[$i]);
        }
        for ($i = 0; $i < count($lain); $i++) {
            $LAIN[$label[$i]] = $lain[$i];
        }
        $json = array(
            'A' => $data_A,
            'B' => $data_B,
            'C' => $data_C
        );
        $this->db->trans_begin();
        $this->load->model('mlhkpn', '', TRUE);

        $post = $this->input->post();
        $ID = $post['ID_LHKPN'];
        $arr = array();
        $getData = $this->mlhkpn->getData('T_LHKPN_PENGELUARAN_KAS', $ID);
        if ($getData != $arr) {
            $deletId = $this->mlhkpn->delData('T_LHKPN_PENGELUARAN_KAS', $ID);
            // for($i   = 0; $i < count($post['id_jenis']); $i++){
            $param['ID_LHKPN'] = $ID;
            $param['IS_ACTIVE'] = '1';
            $param['LAINNYA'] = json_encode($LAIN);
            // $param['ID_JENIS_PENERIMAAN_KAS']           = $post['id_jenis'][$i];
            $param['NILAI_PENGELUARAN_KAS'] = json_encode($json);
            $this->mlhkpn->insertPK('T_LHKPN_PENGELUARAN_KAS', $param);
            // }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                // $this->session->set_flashdata('message', 'Berhasil');
                // redirect(base_url('#index.php/efill/lhkpn/entry/'.$this->input->post('ID_LHKPN', TRUE)), 'refresh');
            }
            echo intval($this->db->trans_status());
        } else {
            // for($i   = 0; $i < count($post['id_jenis']); $i++){
            $param['ID_LHKPN'] = $ID;
            $param['IS_ACTIVE'] = '1';
            $param['LAINNYA'] = json_encode($LAIN);
            // $param['ID_JENIS_PENERIMAAN_KAS']           = $post['id_jenis'][$i];
            $param['NILAI_PENGELUARAN_KAS'] = json_encode($json);
            $this->mlhkpn->insertPK('T_LHKPN_PENGELUARAN_KAS', $param);
            // }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                // $this->session->set_flashdata('message', 'Berhasil');
                // redirect(base_url('#index.php/efill/lhkpn/entry/'.$this->input->post('ID_LHKPN', TRUE)), 'refresh');
            }
            echo intval($this->db->trans_status());
        }
    }

    //daftar pemanfaatan by golongan

    function daftar_pemanfaatan($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $pemanfaatan = $this->mlhkpnharta->get_pemanfaatan($gol);
        foreach ($pemanfaatan as $key) {
            $data[$key->ID_PEMANFAATAN] = $key->NOMOR_KODE . '. ' . $key->PEMANFAATAN;
        }
        return $data;
    }

    // daftar jenis bukti by golongan
    function daftar_bukti($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $bukti = $this->mlhkpnharta->get_bukti($gol);
        foreach ($bukti as $key) {
            $data[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
        }
        return $data;
    }

    //daftar harta by golongan
    function daftar_harta($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $harta = $this->mlhkpnharta->get_harta($gol);
        foreach ($harta as $key) {
            $data[$key->ID_JENIS_HARTA] = $key->NAMA;
        }
        return $data;
    }

    function jenis_harta($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $gol = 5;
        $harta = $this->mlhkpnharta->get_harta($gol);
        foreach ($harta as $key) {
            $data[$key->ID_JENIS_HARTA] = $key->NAMA;
        }
        return $data;
    }

    function jenis_surat_berharga($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $gol = 4;
        $harta = $this->mlhkpnharta->get_harta($gol);
        foreach ($harta as $key) {
            $data[$key->ID_JENIS_HARTA] = $key->NAMA;
        }
        return $data;
    }

    function jenis_bergerak_lain($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $gol = 3;
        $harta = $this->mlhkpnharta->get_harta($gol);
        foreach ($harta as $key) {
            $data[$key->ID_JENIS_HARTA] = $key->NAMA;
        }
        return $data;
    }

    function jenis_harta_lain($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $gol = 6;
        $harta = $this->mlhkpnharta->get_harta($gol);
        foreach ($harta as $key) {
            $data[$key->ID_JENIS_HARTA] = $key->NAMA;
        }
        return $data;
    }

    function daftar_hutang() {
        $data = [];
        $this->load->model('mlhkpnhutang', '', TRUE);
        $hutang = $this->mlhkpnhutang->get_hutang();
        foreach ($hutang as $key) {
            $data[$key->ID_JENIS_HUTANG] = $key->NAMA;
        }
        return $data;
    }

    /** Form Tambah Lhkpn
     * 
     * @return html form tambah Lhkpn
     */
    function getUserAgreement($mode = false) {
        $id = $this->input->post('id');
        $id2 = $this->input->post('id2');
        $sql = $this->db->query("SELECT STATUS_PERBAIKAN_NASKAH, NAMA, ALAMAT_TINGGAL, TGL_LAHIR, TEMPAT_LAHIR, NIK from T_LHKPN INNER JOIN T_PN on T_LHKPN.ID_PN=T_PN.ID_PN where ID_LHKPN = '$id'");
        $tmp = $sql->row();

        $data = array(
            'form' => 'add',
            'id' => $id,
            'id2' => $id2,
            'res' => $tmp,
            'mode' => $mode
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_agreement', $data);
    }

    public function getUser($id = NULL) {
        $this->load->model('mglobal', '', TRUE);
        $join = NULL;
        if (is_null($id)) {
            $q = $_GET['q'];

            $where = ['T_PN.IS_ACTIVE' => '1', 'M_INST_SATKER.IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('T_PN', $join, $where, 'ID_PN, NIK, NAMA', "NAMA LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PN, 'name' => $row->NIK . ' - ' . $row->NAMA];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['T_PN.IS_ACTIVE' => '1', 'M_INST_SATKER.IS_ACTIVE' => '1', 'T_PN.ID_PN' => $id];

            $result = $this->mglobal->get_data_all('T_PN', $join, $where, 'ID_PN, NIK, NAMA', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PN, 'name' => $row->NIK . ' - ' . $row->NAMA];
            }

            echo json_encode($res);
        }
    }

    public function getLembaga($id = NULL) {
        // $join = [
        //            ['table' => 'M_INST_SATKER'      , 'on' => 'INST_SATKERKD  = LEMBAGA'],
        //         ];
        if (is_null($id)) {
            $q = $_GET['q'];

            $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA', "INST_NAMA LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['IS_ACTIVE' => '1', 'INST_SATKERKD' => $id];

            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA', null, null, null, '15');

            $res = [];
            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_INST_SATKER', null, null,
            //     'INST_SATKERKD, INST_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }

            echo json_encode($res);
        }
    }

    public function getProvinsi($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];


            $result = $this->mglobal->get_data_all('M_AREA', null, ['IS_ACTIVE' => '1', 'LEVEL' => '1'], 'IDPROV,NAME', "NAME LIKE '%$q%'", ['NAME', 'ASC'], null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDPROV, 'name' => strtoupper($row->NAME)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $where = ['IDPROV' => $id, 'IS_ACTIVE' => '1'];


            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDPROV, NAME', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDPROV, 'name' => strtoupper($row->NAME)];
            }

            echo json_encode($res);
        }
    }

    public function getProvinsiNew($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];


            $result = $this->mglobal->get_data_all('M_AREA_PROV', null, ['IS_ACTIVE' => '1'], 'ID_PROV,NAME', "NAME LIKE '%$q%'", ['NAME', 'ASC'], null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PROV, 'name' => strtoupper($row->NAME)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $where = ['ID_PROV' => $id, 'IS_ACTIVE' => '1'];


            $result = $this->mglobal->get_data_all('M_AREA_PROV', null, $where, 'ID_PROV, NAME', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PROV, 'name' => strtoupper($row->NAME)];
            }

            echo json_encode($res);
        }
    }

    public function getEselon($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];


            $result = $this->mglobal->get_data_all('M_ESELON');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_ESELON, 'name' => $row->KODE_ESELON];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $where = ['ID_ESELON' => $id,];


            $result = $this->mglobal->get_data_all('M_ESELON');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_ESELON, 'name' => $row->KODE_ESELON];
            }

            echo json_encode($res);
        }
    }

    public function getInstansi($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];

            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, NULL, 'INST_SATKERKD,INST_NAMA', "INST_NAMA LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['INST_SATKERKD' => $id];

            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }

            echo json_encode($res);
        }
    }

    public function getKabupaten($prov = NULL, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $prov = $_GET['prov'];


            $where = ['IDPROV' => $prov, 'LEVEL' => '2'];


            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKOT,NAME', "NAME LIKE '%$q%'", null, null, '15');


            $res = [];
            foreach ($result as $row) {
                if (substr($row->IDKOT, 0, 1) == 7) {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower($row->NAME))];
                } else {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower('Kabupaten ' . $row->NAME))];
                }
            }


            $data = ['item' => $res];


            echo json_encode($data);
        } else {
            $where = ['IDPROV' => $prov, 'IDKOT' => $id, 'LEVEL' => '2'];


            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKOT, NAME', null, null, null, '15');


            $res = [];
            foreach ($result as $row) {
                if (substr($row->IDKOT, 0, 1) == 7) {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower($row->NAME))];
                } else {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower('Kabupaten ' . $row->NAME))];
                }
            }


            echo json_encode($res);
        }
    }

    public function getKecamatan($prov = NULL, $kab = NULL, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $prov = $_GET['prov'];
            $kab = $_GET['kab'];

            $where = [
                'IDPROV' => $prov,
                'IDKEC !=' => '',
                'IDKEL' => '',
                'IDKEC <>' => ''
            ];

            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKEC,NAME', "IDKEL = '' AND CAST(IDKOT as UNSIGNED) = '$kab' AND NAME LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDKEC, 'name' => strtoupper($row->NAME)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = [
                'IDPROV' => $prov,
                'IDKEC' => $id,
                'IDKEL' => '',
            ];

            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKEC, NAME', "CAST(IDKOT as UNSIGNED) = '$kab'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDKEC, 'name' => strtoupper($row->NAME)];
            }

            echo json_encode($res);
        }
    }

    public function getKelurahan($prov = NULL, $kab = NULL, $kec = NULL, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $prov = $_GET['prov'];
            $kab = $_GET['kab'];
            $kec = $_GET['kec'];

            $where = ['IDPROV' => $prov, 'IDKEC' => $kec, 'IDKEL <>' => ''];

            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKEL,NAME', "CAST(IDKOT as UNSIGNED) = '$kab' AND NAME LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDKEL, 'name' => strtoupper($row->NAME)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['IDPROV' => $prov, 'IDKEC' => $kec, 'IDKEL' => $id];

            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKEL, NAME', "CAST(IDKOT as UNSIGNED) = '$kab'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDKEL, 'name' => strtoupper($row->NAME)];
            }

            echo json_encode($res);
        }
    }

    // public function getKecamatan($id = NULL)
    // {
    //     if(is_null($id)){
    //         $q = $_GET['q'];
    //         $result = $this->mglobal->get_data_all('M_AREA', null, NULL, 'ID_AREA,NAME', "IDKEC LIKE '%$q%'", null , null , '15');
    //         $res = [];
    //         foreach ($result as $row)
    //         {
    //             $res[] = ['id' => $row->ID_AREA, 'name' => $row->NAME];
    //         }
    //         $data = ['item' => $res];
    //         echo json_encode($data);
    //     }else{
    //         $where = ['ID_AREA' => $id];
    //         $result = $this->mglobal->get_data_all('M_AREA', null, $where,
    //             'ID_AREA, NAME', null, null , null , '15');
    //         $res = [];
    //         foreach ($result as $row)
    //         {
    //             $res[] = ['id' => $row->ID_AREA, 'name' => $row->NAME];
    //         }
    //         echo json_encode($res);
    //     }
    // }
    // public function getKelurahan($id = NULL)
    // {
    //     if(is_null($id)){
    //         $q = $_GET['q'];
    //         $result = $this->mglobal->get_data_all('M_AREA', null, ['IDKEL <>' => ''], 'ID_AREA,NAME', "IDKEC LIKE '%$q%'", null , null , '15');
    //         $res = [];
    //         foreach ($result as $row)
    //         {
    //             $res[] = ['id' => $row->ID_AREA, 'name' => $row->NAME];
    //         }
    //         $data = ['item' => $res];
    //         echo json_encode($data);
    //     }else{
    //         $where = ['ID_AREA' => $id];
    //         $result = $this->mglobal->get_data_all('M_AREA', null, $where,
    //             'ID_AREA, NAME', null, null , null , '15');
    //         $res = [];
    //         foreach ($result as $row)
    //         {
    //             $res[] = ['id' => $row->ID_AREA, 'name' => $row->NAME];
    //         }
    //         echo json_encode($res);
    //     }
    // }

    public function getNegara($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];

            $result = $this->mglobal->get_data_all('M_NEGARA', null, NULL, '*', "NAMA_NEGARA LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID, 'name' => strtoupper($row->NAMA_NEGARA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['id' => $id];

            $result = $this->mglobal->get_data_all('M_NEGARA', null, $where, '*', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID, 'name' => strtoupper($row->NAMA_NEGARA)];
            }

            echo json_encode($res);
        }
    }

    public function getUnitKerja($lembaga = NULL, $id = NULL) {
        // $join = [
        //            ['table' => 'M_INST_SATKER'      , 'on' => 'INST_SATKERKD  = LEMBAGA'],
        //         ];
        if (is_null($id)) {
            $q = $_GET['q'];

            $where = ['UK_LEMBAGA_ID' => $lembaga];
            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, $where, 'UK_ID, UK_NAMA', "UK_NAMA LIKE '%$q%'", null, null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->UK_ID, 'name' => strtoupper($row->UK_NAMA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['UK_ID' => $id, 'UK_LEMBAGA_ID' => $lembaga];

            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, $where, 'UK_ID, UK_NAMA', null, null, null, '15');

            $res = [];
            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, null,
            //     'UK_ID, UK_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row) {
                $res[] = ['id' => $row->UK_ID, 'name' => strtoupper($row->UK_NAMA)];
            }

            echo json_encode($res);
        }
    }

    public function getJabatan($id = NULL) {
        // $join = [
        //            ['table' => 'M_INST_SATKER'      , 'on' => 'INST_SATKERKD  = LEMBAGA'],
        //         ];
        if (is_null($id)) {
            $q = $_GET['q'];

            $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('M_JABATAN', null, $where, 'ID_JABATAN, NAMA_JABATAN', "NAMA_JABATAN LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper($row->NAMA_JABATAN)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['IS_ACTIVE' => '1', 'ID_JABATAN' => $id];

            $result = $this->mglobal->get_data_all('M_JABATAN', null, $where, 'ID_JABATAN, NAMA_JABATAN', null, null, null, '15');

            $res = [];
            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_INST_SATKER', null, null,
            //     'INST_SATKERKD, INST_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper($row->NAMA_JABATAN)];
            }

            echo json_encode($res);
        }
    }

    public function addjabatan($id_lhkpn = null) {
        $data = array(
            'form' => 'add',
            'id_lhkpn' => $id_lhkpn,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    public function editjabatan($id = null) {
        $joinJabatan = [
                ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD']
        ];
        $data = array(
            'form' => 'edit',
            'item' => $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, '*', "ID = '$id'")[0],
            'src' => @$this->input->get('src')
        );
        $data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], ['ID_LHKPN' => $data['item']->ID_LHKPN], '*')[0];
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    public function deletejabatan($id = null) {
        $joinJabatan = [
                ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                ['table' => 'M_UNIT_KERJA', 'on' => 'T_LHKPN_JABATAN.UNIT_KERJA = M_UNIT_KERJA.UK_ID'],
                ['table' => 'M_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN']
        ];

        $data = array(
            'form' => 'delete',
            'item' => $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, '*', "ID = '$id'")[0],
            'src' => @$this->input->get('src')
        );
        $data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], ['ID_LHKPN' => $data['item']->ID_LHKPN], '*')[0];
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    public function editprimary($id = null) {
        $joinJabatan = [
                ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                ['table' => 'M_UNIT_KERJA', 'on' => 'T_LHKPN_JABATAN.UNIT_KERJA = M_UNIT_KERJA.UK_ID'],
                ['table' => 'M_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN']
        ];

        $data = array(
            'form' => 'editprimary',
            'item' => $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, '*', "ID = '$id'")[0],
            'src' => @$this->input->get('src')
        );
        $data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], ['ID_LHKPN' => $data['item']->ID_LHKPN], '*')[0];
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    public function SetPrimary($id, $ID_LHKPN = null) {
        // $ID = $this->input->post('ID');
        //$ID_LHKPN = $this->input->post('ID_LHKPN');
        $this->db->trans_begin();
        $dataall = array(
            'IS_PRIMARY' => '0'
        );
        $data = array(
            'IS_PRIMARY' => '1'
        );

        $this->mglobal->update_data('t_lhkpn_jabatan', $dataall, 'ID_LHKPN', $ID_LHKPN);
        $this->mglobal->update_data('t_lhkpn_jabatan', $data, 'ID', $id);
//        $this->db->where('ID', $ID);
//        $result = $this->db->update('t_lhkpn_jabatan', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    public function detailjabatan($id = null) {
        $joinJabatan = [
                ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD']
        ];
        $data = array(
            'form' => 'detail',
            'item' => $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, '*', "ID = '$id'")[0],
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    public function savejabatan() {
        $type_file = $this->input->post('type_file');
        $allowedExts = array("jpeg", "jpg", "png", "pdf");
        $ID_LHKPN = $this->input->post('ID_LHKPN', TRUE);
        $LHKPN = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "ID_LHKPN = '$ID_LHKPN'")[0];
        $NIK = $LHKPN->NIK;
        $filename = 'uploads/data_jabatan/' . $NIK . '/readme.txt';

        $this->db->trans_begin();
        if ($this->input->post('act', TRUE) == 'doinsert') {

            //upload sk
            if (!file_exists($filename)) {
                $dir = './uploads/data_jabatan/' . $NIK . '/';
                $file_to_write = 'readme.txt';
                $content_to_write = "SK Dari nik " . $NIK;
                if (is_dir($dir) === false) {
                    mkdir($dir);
                }
                $file = fopen($dir . '/' . $file_to_write, "w");
                fwrite($file, $content_to_write);
                // closes the file
                fclose($file);
            }

            if ($type_file == '1') {
                $this->load->library('Uploadimagetopdf');
                $FILE_SK = $this->uploadimagetopdf->upload($NIK, $_FILES['FILE_SK'], 'data_jabatan');
            } else {
                $fileSK = $_FILES['FILE_SK'];
                $tmpEx = explode('.', $fileSK['name'][0]);
                $extension = end($tmpEx);
                $maxsize = 500000;

                if ($fileSK['size'][0] <= $maxsize) {
                    if (in_array($extension, $allowedExts)) {
                        $FILE_SK = save_file($fileSK['tmp_name'][0], $fileSK['name'][0], $fileSK['size'][0], "./uploads/data_jabatan/" . $NIK . "/", 1, 500)['nama_file'];
                    }
                }
            }

            $jabatan = array(
                'ID' => $this->input->post('ID', TRUE),
                'ID_JABATAN' => $this->input->post('JABATAN', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                // 'JABATAN'           => $this->input->post('JABATAN', TRUE), deprecated
                'DESKRIPSI_JABATAN' => strtoupper($this->input->post('DESKRIPSI_JABATAN', TRUE)),
                'ESELON' => $this->input->post('ESELON', TRUE),
                'LEMBAGA' => $this->input->post('LEMBAGA', TRUE),
                'UNIT_KERJA' => $this->input->post('UNIT_KERJA', TRUE),
                'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR', TRUE),
                'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR', TRUE),
                'FILE_SK' => $FILE_SK,
                'TMT' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
                // 'SD'                => date('Y-m-d',strtotime($this->input->post('SD', TRUE))),
                // 'IS_ACTIVE'      => 1,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $this->db->insert('T_LHKPN_JABATAN', $jabatan);
        } else if ($this->input->post('act', TRUE) == 'doupdate') {

            //upload sk
            if (!file_exists($filename)) {
                $dir = './uploads/data_jabatan/' . $NIK . '/';
                $file_to_write = 'readme.txt';
                $content_to_write = "SK Dari nik " . $NIK;
                if (is_dir($dir) === false) {
                    mkdir($dir);
                }
                $file = fopen($dir . '/' . $file_to_write, "w");
                fwrite($file, $content_to_write);
                // closes the file
                fclose($file);
            }

            if ($type_file == '1') {
                $this->load->library('Uploadimagetopdf');
                $tmp = $this->uploadimagetopdf->upload($NIK, $_FILES['FILE_SK'], 'data_jabatan');
                $FILE_SK = $tmp == false ? $this->input->post('FILE_SK_OLD', TRUE) : $tmp;
            } else {
                $FILE_SK = $this->input->post('FILE_SK_OLD', TRUE);

                $fileSK = $_FILES['FILE_SK'];
                if ($fileSK['name'][0] != '') {
                    $tmpEx = explode('.', $fileSK['name'][0]);
                    $extension = end($tmpEx);
                    $maxsize = 500000;

                    if ($fileSK['size'][0] <= $maxsize) {
                        if (in_array($extension, $allowedExts)) {
                            $FILE_SK = save_file($fileSK['tmp_name'][0], $fileSK['name'][0], $fileSK['size'][0], "./uploads/data_jabatan/" . $NIK . "/", 1, 500)['nama_file'];
                        }
                    }
                }
            }

            $jabatan = array(
                // 'ID'              	=> $this->input->post('ID_LHKPN', TRUE),
                // 'ID_JABATAN'        => $this->input->post('ID_LHKPN', TRUE),
                'ID' => $this->input->post('ID', TRUE),
                'ID_JABATAN' => $this->input->post('JABATAN', TRUE),
                'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                // 'JABATAN'           => $this->input->post('JABATAN', TRUE), deprecated
                'DESKRIPSI_JABATAN' => strtoupper($this->input->post('DESKRIPSI_JABATAN', TRUE)),
                'ESELON' => $this->input->post('ESELON', TRUE),
                'LEMBAGA' => $this->input->post('LEMBAGA', TRUE),
                'UNIT_KERJA' => $this->input->post('UNIT_KERJA', TRUE),
                'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR', TRUE),
                'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR', TRUE),
                'FILE_SK' => $FILE_SK,
                'TMT' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
                'SD' => $this->input->post('SD', TRUE) == '' ? '0000-00-00' : date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD', TRUE)))),
                // 'IS_ACTIVE'      => 1,        	
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $jabatan['ID'] = $this->input->post('ID', TRUE);
            $this->db->where('ID', $jabatan['ID']);
            $this->db->update('T_LHKPN_JABATAN', $jabatan);
            // echo $this->db->last_query();
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $lhkpnjabatan['ID'] = $this->input->post('ID', TRUE);
            $this->db->delete('T_LHKPN_JABATAN', $lhkpnjabatan);
            $item = @$this->mglobal->get_data_all('T_LHKPN_JABATAN', NULL, ['ID' => $this->input->post('ID', TRUE)], '*')[0];
            $LHKPN = @$this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], ['ID_LHKPN' => $item->ID_LHKPN], '*')[0];
            $url_file = $this->input->post('OLD_FILE', TRUE);
            unlink(base_url('uploads/data_jabatan/' . $LHKPN->NIK . '/' . $url_file));
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    public function getBidang($id = NULL) {
        // $join = [
        //            ['table' => 'M_INST_SATKER'      , 'on' => 'INST_SATKERKD  = LEMBAGA'],
        //         ];
        if (is_null($id)) {
            $q = $_GET['q'];

            $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('M_BIDANG', null, $where, 'BDG_ID, BDG_NAMA', "BDG_NAMA LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->BDG_ID, 'name' => strtoupper($row->BDG_NAMA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['IS_ACTIVE' => '1', 'BDG_ID' => $id];

            $result = $this->mglobal->get_data_all('M_BIDANG', null, $where, 'BDG_ID, BDG_NAMA', null, null, null, '15');

            $res = [];
            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_INST_SATKER', null, null,
            //     'INST_SATKERKD, INST_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row) {
                $res[] = ['id' => $row->BDG_ID, 'name' => strtoupper($row->BDG_NAMA)];
            }

            echo json_encode($res);
        }
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

    private function isDataEntry() {
        $role = $this->session->userdata('ID_ROLE');
        if ($role == '6') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cek KK
     * 
     * @return 1 = jika KK belum ada, 2 = jika KK sudah ada, 0 = jika data tidak ada
     */
    public function cek_kk() {
        $this->load->model('mglobal');
        $id = $this->input->post('id');

        $data = $this->mglobal->get_data_all('T_LHKPN', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $id]);

        if (!empty($data)) {
            if ($data[0]->FILE_KK == NULL || $data[0]->FILE_KK == '') {
                echo '1';
            } else {
                echo '2';
            }
        } else {
            echo '0';
        }
    }

}
