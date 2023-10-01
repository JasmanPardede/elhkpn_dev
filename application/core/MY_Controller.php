<?php

use phpDocumentor\Reflection\PseudoTypes\False_;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//include_once dirname(__FILE__) . "KPK_Member_Controller.php";
//class MY_Controller extends KPK_Member_Controller {
class MY_Controller extends CI_Controller {

    const DIR_TEMP_UPLOAD = './uploads/lhkpn_import_excel/temp/';
    const DIR_TEMP_SKM_UPLOAD = './uploads/lhkpn_import_excel/temp/skm/';
    const DIR_TEMP_SKUASA_UPLOAD = './uploads/lhkpn_import_excel/temp/sk/';
    const DIR_TEMP_IKHTISAR_UPLOAD = './uploads/lhkpn_import_excel/temp/ikhtisar/';
    const DIR_IMP_UPLOAD = './uploads/lhkpn_import_excel/final/';
    const DIR_SKM_UPLOAD = './uploads/data_skm/';
    const DIR_SKUASA_UPLOAD = './uploads/data_sk/';
    const DIR_IKHTISAR_UPLOAD = './uploads/ikhtisar/';
    const ENABLE_LOG_MESSAGE = FALSE;

    public $instansi;
    public $username;
    public $ID_USER;
    public $role;
    protected $need_uk_id = TRUE;
    protected $upload_harta_location = "";
    protected $is_lhkpnoffline = FALSE;
    // admin aplikasi
    // 
    // admin kpk
    // - bisa add user apapun
    // 
    // admin instansi
    // - bisa add user hanya di instansinya saja
    // - hanya bisa add role 4 dan 5
    // 
    // admin unit kerja
    // - hanya bisa add role 5
    protected $is_validation = FALSE;
    protected $field_id_lhkpn = 'ID_LHKPN';
    protected $table_lhkpn = 't_lhkpn';
    protected $is_ever = TRUE;

    private $sess_template_data = [
        "id_user" => NULL,
        "pesan" => NULL,
        "subject" => NULL,
        "word_location" => NULL,
        "is_trusted" => TRUE
    ];
    private $sess_template_kirim_lhkpn = [
        "data" => [], //see $this->sess_template_data
        "id_lhkpn" => NULL,
    ];
    protected $__role_map = array(
        1 => [],
        2 => [0],
        3 => [4, 5],
        4 => [5]
    );

    protected function get_download_lhkpn_import_location($is_temp = TRUE) {
        $download_location = self::DIR_IMP_UPLOAD;
        if ($is_temp) {
            $download_location = self::DIR_TEMP_UPLOAD;
        }

        return str_replace("./", "", $download_location);
    }

    public function __construct() {
        parent::__construct();

        if ($this->input->get('show_profiler')) {
            $this->output->enable_profiler(TRUE);
        }

//        $this->__check_another_db_trans();

        $this->username = $this->session->userdata('USERNAME');
        $this->instansi = $this->session->userdata('INST_SATKERKD');
        $this->ID_USER = $this->session->userdata('ID_USER');
        $this->NIK = $this->session->userdata('USR');

        $this->user_instansi_id = FALSE;
        $this->user_uk_id = FALSE;
        $this->user_id_pn = FALSE;

        /**
         * big process bray
         */
        if ($this->need_uk_id) {
            $this->__get_uk_id();
        }

        $this->role = $this->session->userdata('ID_ROLE');

        $this->config->load('harta');

        if ($this->is_ever) {
            $this->load->model('mlhkpnkeluarga');
        }
    }

    /**
     * @return void
     */
    private function __check_another_db_trans() {
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    protected function __check_has_action($new_user_role = FALSE, $inst_destination = FALSE) {

        if (!($new_user_role || $inst_destination)) {
            return FALSE;
        }
        $response = FALSE;

        switch ($this->role) {
            case '1':
            case 1:
                break;
            case '2':
            case 2:

                $response = TRUE;
                break;
            case '3':
            case 3:
            case '4':
            case 4:
                $response = ($this->instansi == $inst_destination && in_array($new_user_role, $this->__role_map[$this->role]));
                break;
            case '31':
            case 31:

                $response = TRUE;
                break;
            default:
                break;
        }
        return $response;
    }

    protected function check_akses() {
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
//        $this->makses->initialize();
//        $this->makses->check_is_read();   
    }

    private function __get_uk_id() {
        $this->load->model('mglobal');

        $detail_pn = $this->mglobal->get_lembaga_uk_id_pn_by_nik($this->NIK);

        if ($detail_pn) {

            $this->user_instansi_id = $detail_pn->LEMBAGA;
            $this->user_uk_id = $detail_pn->UK_ID;
            $this->user_id_pn = $detail_pn->ID_PN;
        }

        unset($detail_pn);
    }

    protected function log_message($type, $message) {
        if (static::ENABLE_LOG_MESSAGE) {
            log_message($type, $message);
        }
    }

    protected function is_instansi() {
        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_INSTANSI, IS_USER_INSTANSI');

        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;

            if ($inst == '1' || $user == '1') {
                $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
                return $INST_SATKERKD;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function is_unit_kerja() {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');
        if ($is_uk == 1) {
            $uk = $this->mglobal->get_by_id('m_unit_kerja', 'UK_ID', $id_uk);
            return $id_uk;
        } else {
            return false;
        }
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

    public function do_reset_password() { 
        $id_user = $this->input->post('ID_USER');
        if (!empty($id_user)) {
            $this->makses->check_is_write();
            $this->load->model('muser');
            $new_password = $this->muser->createRandomPassword(7);
            $data_user = $this->muser->get_by_id($id_user)->row();

            $salt = $this->muser->get_by_id($data_user->ID_USER)->row()->SALT;
            if($salt == null){
                $salt = random_word(8);
            };
            $new_pwd_hash = md5($new_password);
            $password = sha1(md5($new_pwd_hash.$salt));

            if ($data_user && $data_user->IS_ACTIVE == '1') {
                //$kirim_info = $this->muser->kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $password, 'Pemberitahuan Reset Password');
                $kirim_info = $this->muser->kirim_info_lupa_password($data_user->EMAIL, $data_user->USERNAME, $new_password);
                if ($kirim_info) {
                    $data_update = array(
                        'SALT' => $salt,
                        'password' => $password,
                        'IS_FIRST' => '1',
                        'UPDATED_BY' => $this->session->userdata('USERNAME'),
                        'UPDATED_TIME' => time(),
                    );

                    $this->muser->update($id_user, $data_update);

//                echo $this->db->last_query();
                    ng::logActivity('Reset Password Untuk Admin Instansi dengan, username = ' . $this->input->post('USERNAME', TRUE));
                    echo 1;
                } else {
                    echo 0;
//                    exit();
                }
            } else {
                echo '2';
            }
        } else {
            echo '0';
        }
    }

    public function dokirimaktivasi() { 
        $id_user = $this->input->post('ID_USER');
        if (!empty($id_user)) {
            $this->makses->check_is_write();
            $this->load->model('muser');
            $new_password = $this->muser->createRandomPassword(7);
            $data_user = $this->muser->get_by_id($id_user)->row();

            $salt = $this->muser->get_by_id($data_user->ID_USER)->row()->SALT;
            if($salt == null){
                $salt = random_word(8);
            };
            $new_pwd_hash = md5($new_password);
            $password = sha1(md5($new_pwd_hash.$salt)); 

            $user = array(
                'SALT' => $salt,
                'PASSWORD' => $password
            );
            if ($data_user && $data_user->IS_ACTIVE == '0') {
                $this->muser->update($id_user, $user);
                $this->muser->old_kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $new_password);
                ng::logActivity('Kirim Ulang Aktivasi dengan, username = ' . $data_user->USERNAME);
                // ng::logActivity('Kirim Ulang Aktivasi dengan, id_user = ' . $this->input->post('ID_USER', TRUE));
                echo '1';
            } else {
                echo '2';
            }
        } else {
            echo '0';
        }
    }
    
    public function aktivasiakun($id_user) { 
        
        if (!empty($id_user)) {
            $this->makses->check_is_write();
            $this->load->model('muser');
            $new_password = $this->input->post('content');
            // $password = $this->muser->createRandomPassword(6);
            $data_user = $this->muser->get_by_id($id_user)->row();
            $salt = $this->muser->get_by_id($id_user)->row()->SALT;  
            if($salt == null){
                $salt = random_word(8);
            }
            
            $new_pwd_hash = md5($new_password);
            $password = sha1(md5($new_pwd_hash.$salt));
            $user = array(
                'SALT' => $salt,
                'PASSWORD' => $password
            );
            if ($data_user && $data_user->IS_ACTIVE == '0') {
                $this->muser->update($id_user, $user);
                $this->muser->old_kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $new_password);
//                ng::logActivity('Kirim Ulang Aktivasi dengan, username = ' . $data_user->USERNAME);
                // ng::logActivity('Kirim Ulang Aktivasi dengan, id_user = ' . $this->input->post('ID_USER', TRUE));
                echo '1';
            } else {
                echo '2';
            }
        } else {
            echo '0';
        }
    }

    protected function get_param_load_paging_default($load_paging_area = FALSE) {
        $load_paging_area = $load_paging_area ? $load_paging_area : 'default';
        return $this->_param_load_paging_default($load_paging_area);
    }

    protected function check_type_of_admin() {
        $idRole = $this->session->userdata('ID_ROLE');
        return [
            "isAdminInstansi" => $idRole == '3',
            "isAdminUnitKerja" => $idRole == '4',
            "isAdminKPK" => $idRole == '2',
            "isAdminAplikasi" => $idRole == '1',
            "isSuperadmin" => $idRole == '31',
        ];
    }

    function get_instansi_daftar_pn_wl_via_excel($get_role = FALSE, $debug = FALSE) {
        $ins = $this->instansi;
        $instansi_found = TRUE;

        if (is_null($ins) && ($this->session->userdata['ID_ROLE'] <= '2' || $this->session->userdata['ID_ROLE'] == '31')) {
            $ins = $this->input->get('CARI[INSTANSI]');
            $instansi_found = FALSE;
        }

        if ($debug) {
            var_dump($this->session->userdata['ID_ROLE'], $this->session->userdata);
            exit;
        }

        $uk = $this->input->get('CARI[UNIT_KERJA]');
        if ($this->session->userdata['ID_ROLE'] == '4') {
            $uk = $this->session->userdata['UK_ID'];
        }

//        gwe_dump($uk, TRUE);

        if ($get_role) {
            return array(
                "instansi_found" => $instansi_found,
                "id_role" => $this->session->userdata['ID_ROLE'],
                "ins" => $ins,
                "uk" => $uk
            );
        }
        return $ins;
    }

    public function to_json($data) {

        if ($this->input->get('show_profiler')) {
            return;
        }

        //$this->output->set_content_type('json');
        //$this->output->set_output(json_encode($data));

        echo json_encode($data);
        exit;
    }

    /**
     * 
     * @param type $return_uk_id
     * @param type $id_user very rarely used call lahir wisada <lahirwisada@gmail.com>
     * @return type
     */
    protected function __get_current_uk_nama($return_uk_id = FALSE, $id_user = FALSE) {
        $this->load->model('mpn');

        log_message('info', 'masuk backGroundDownloadFileExcel sesion_id_user ' . $this->session->userdata('ID_USER'));

        $i_param = $id_user != FALSE ? $id_user : $this->session->userdata('ID_USER');

        $un_ker = $this->mpn->get_uk($i_param);
        $uk_nama = ($un_ker != NULL) ? $un_ker->UK_NAMA : NULL;
        $uk_id = ($un_ker != NULL) ? $un_ker->UK_ID : NULL;

        unset($un_ker);
        if ($return_uk_id) {
            return array($uk_id, $uk_nama);
        }
        return $uk_nama;
    }

    function curl_call_background($url, $curl_timeout = 1, $curl_connectiontimeout = FALSE) {
        header("HTTP/1.1 200 OK");

        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

        log_message('info', 'curl_call_background prepare curl');

        $ch = curl_init();

// set URL and other appropriate options

        log_message('info', 'curl_call_background set opt');
        curl_setopt($ch, CURLOPT_URL, $url);
        log_message('info', 'curl_call_background prepare access url ' . $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        curl_setopt($ch, CURLOPT_NOPROGRESS, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, $curl_timeout);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);

        $headers = array(
            'Content-type: text/html',
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($curl_connectiontimeout !== FALSE) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $curl_connectiontimeout);
        }

        log_message('info', 'curl_call_background after set opt');

        log_message('info', 'curl_call_background execute curl');
// grab URL and pass it to the browser
        curl_exec($ch);

        log_message('info', 'curl_call_background close curl');

// close cURL resource, and free up system resources
        curl_close($ch);
    }

    protected function get_lhkpn_penerimaan_by_id_temp_lhkpn($id_imp_lhkpn = FALSE, $is_secure = FALSE) {

        if (!$is_secure) {
            $id_imp_lhkpn = make_secure_text($id_imp_lhkpn);
        }

        return $this->mglobal->secure_get_by_id("t_lhkpnoffline_penerimaan", "ID_IMP_XL_LHKPN", "ID_IMP_XL_LHKPN", $id_imp_lhkpn);
    }

    public function lhkpn_temp_file_bukti($id_lhkpn = FALSE, $is_secure = FALSE) {

        if (!$id_lhkpn || is_null($id_lhkpn)) {
            return array();
        }

        $this->data['lhkpn_penerimaan'] = $this->get_lhkpn_penerimaan_by_id_temp_lhkpn($id_lhkpn, $is_secure);
        if ($this->data['lhkpn_penerimaan']) {
            $this->data['rand_id'] = $this->data['lhkpn_penerimaan']->RAND_ID;
            $file_list = scandir(self::DIR_TEMP_UPLOAD . $this->data['lhkpn_penerimaan']->RAND_ID);
//            $file_list = array();
            unset($file_list[0], $file_list[1]);
        }

        $this->data['file_list'] = $file_list;
        return $file_list;
    }

    /**
     * 
     * Jika $is_preview bernilai TRUE maka tabel yang digunakan adalah tabel Import (tabel Temp)
     * Jika $is_preview bernilai FALSE maka tabel yang digunakan adalah tabel T_LHKPN
     * 
     * @param type $ID_LHKPN
     * @param type $also_redirect
     * @param type $is_preview
     * @return type
     */
    protected function get_data_pn_by_id_lhkpn($ID_LHKPN, $also_redirect = TRUE, $is_preview = FALSE) {

        $arr_tables = [
            [
                "T_LHKPN" => ["tname" => "T_LHKPN", "field" => "ID_LHKPN"],
                "T_LHKPN_JABATAN" => ["tname" => "T_LHKPN_JABATAN", "field" => "ID_LHKPN"],
            ],
            [
                "T_LHKPN" => ["tname" => "t_imp_xl_LHKPN", "field" => "id_imp_xl_lhkpn"],
                "T_LHKPN_JABATAN" => ["tname" => "t_imp_xl_LHKPN_JABATAN", "field" => "id_imp_xl_lhkpn"],
            ],
        ];

        $use_table = $is_preview ? $arr_tables[1] : $arr_tables[0];
        $id_lhkpn_prev = $is_preview ? '' : ', ID_LHKPN_PREV';

        $T_LHKPN = $use_table["T_LHKPN"]["tname"] . '.' . $use_table["T_LHKPN"]["field"];
        $T_LHKPN_NAME = $use_table["T_LHKPN"]["tname"];
        $T_LHKPN_JABATAN = $use_table["T_LHKPN_JABATAN"]["tname"] . '.' . $use_table["T_LHKPN_JABATAN"]["field"];
        $T_LHKPN_JABATAN_NAME = $use_table["T_LHKPN_JABATAN"]["tname"];

        $wherepn = [$T_LHKPN => $ID_LHKPN, 'IS_PRIMARY' => '1'];
        if ($this->is_lhkpnoffline) {
            $wherepn = [$T_LHKPN => $ID_LHKPN];
        }
        // $wherepn = ['T_USER.IS_ACTIVE' => '1', $T_LHKPN => $ID_LHKPN, 'IS_PRIMARY' => '1'];
        // if (!$also_redirect) {
        //     $wherepn = ['T_USER.IS_ACTIVE' => '1', $T_LHKPN => $ID_LHKPN];
        // }
        $datapn = @$this->mglobal->get_data_all('T_PN', [
                    ['table' => $T_LHKPN_NAME, 'on' => 'T_PN.ID_PN = ' . $T_LHKPN_NAME . '.ID_PN'],
                    ['table' => $T_LHKPN_JABATAN_NAME, 'on' => $T_LHKPN_JABATAN . ' = ' . $T_LHKPN],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = ' . $T_LHKPN_JABATAN_NAME . '.ID_JABATAN'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
                    ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID'],
//                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = ' . $T_LHKPN_JABATAN_NAME . '.LEMBAGA'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD'],
                    ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID']
                        ], $wherepn, 'T_LHKPN.STATUS, T_PN.NIK, T_PN.ID_PN, T_PN.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_UNIT_KERJA.UK_NAMA, M_SUB_UNIT_KERJA.SUK_NAMA, M_BIDANG.BDG_NAMA, ' . $T_LHKPN_NAME . '.JENIS_LAPORAN, ' . $T_LHKPN_NAME . '.TGL_LAPOR, T_PN.EMAIL'. $id_lhkpn_prev)[0];
        return $datapn;
    }

    protected function get_tanggal_lapor_by_datapn($datapn, $id_imp_xl_lhkpn = FALSE) {
        $tgl_lapor = $datapn->TGL_LAPOR;
        if (!empty($id_imp_xl_lhkpn)) {
            $tgl_lapor_old = $this->mglobal->get_tahun_pelaporan($id_imp_xl_lhkpn);
            if ($tgl_lapor_old->TAHUN_PELAPORAN != '' && $tgl_lapor_old->JENIS_LAPORAN == 4) {
                $tgl_lapor = $tgl_lapor_old->TAHUN_PELAPORAN.'-12-30';
            }
            else{
                $tgl_lapor = $tgl_lapor_old->TANGGAL_PELAPORAN;
            }

        }
        else{
            if ($datapn->TGL_LAPOR == '0000-00-00') {
                $tgl_lapor = date('Y-m-d');
            }
        }

        return get_format_tanggal_lapor_lhkpn($datapn->JENIS_LAPORAN, $tgl_lapor);
    }

    function preview_lp($id_imp_xl_lhkpn, $datapn = FALSE, $is_cetak = FALSE) {
        $data["datapn"] = $datapn ? $datapn : $this->get_data_pn_by_id_lhkpn($id_imp_xl_lhkpn, TRUE, TRUE);
        $data["tahun"] = empty($id_imp_xl_lhkpn) ? $this->get_tanggal_lapor_by_datapn($data["datapn"], FALSE) : $this->get_tanggal_lapor_by_datapn($data["datapn"], $id_imp_xl_lhkpn);

        $this->db->select('STATUS');
        $this->db->where("ID_LHKPN = ". $data["datapn"]->ID_LHKPN_PREV);
        $check_status_lhkpn_prev = $this->db->get('T_LHKPN')->row();
        $data["STATUS_LHKPN_PREV"] = $check_status_lhkpn_prev->STATUS;

        if($is_cetak){
            return $data;
        }

        if ($datapn) {
            return $this->load->view("efill/lhkpn/lhkpn_preview_lp", $data, TRUE);
        }

        $this->load->view("efill/lhkpn/lhkpn_preview_lp", $data);
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
        exit;
    }

    function jumlahHarta($ID_LHKPN) {
        $data = $this->harta();
        $result = array();
        foreach ($data as $z) {
            if ($z == 't_lhkpn_harta_kas') {
                $key = 'NILAI_EQUIVALEN';
            } else if ($z == 't_lhkpn_hutang') {
                $key = 'SALDO_HUTANG';
            } else {
                $key = 'NILAI_PELAPORAN';
            }
            $this->db->select_sum($key);
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            if ($z != 't_lhkpn_hutang') {
                $this->db->where('IS_PELEPASAN <> \'1\' ');
            }
            $this->db->where('IS_ACTIVE', '1');
            $hasil = $this->db->get($z)->result();
            if ($hasil[0]->$key) {
                $result[] = $hasil[0]->$key;
            } else {
                $result[] = 0;
            }
        }

        return $result;
    }

    /**
     * Kirim dari LHKPNOFFLINE ==> parameternya adalah $id_lhkpn, FALSE, TRUE, $id_imp_xl_lhkpn
     * 
     * @param type $ID_LHKPN
     * @param type $also_redirect
     * @param type $send_to_mail
     * @param type $id_imp_xl_lhkpn
     */
    function kirim_lhkpn($ID_LHKPN, $also_redirect = TRUE, $send_to_mail = TRUE, $id_imp_xl_lhkpn = FALSE) {
        $usr_name = $this->session->userdata('USERNAME');
        $wherepn = ['T_USER.IS_ACTIVE' => '1', 'T_LHKPN.ID_LHKPN' => $ID_LHKPN, 'IS_PRIMARY' => '1'];
        if (!$also_redirect) {
            $wherepn = ['T_USER.IS_ACTIVE' => '1', 'T_LHKPN.ID_LHKPN' => $ID_LHKPN];
        }

        /**
         * @deprecated since 29 nopember 2017
         * @author lahirwisada@gmail.com
         * @see $this->get_data_pn_by_id_lhkpn
         */
//        $datapn = @$this->mglobal->get_data_all('T_USER', [
//                    ['table' => 'T_PN', 'on' => 'T_PN.NIK = T_USER.USERNAME'],
//                    ['table' => 'T_LHKPN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'],
//                    ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
//                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
//                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA'],
//                    ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID']
//                        ], $wherepn, 'STATUS, ID_USER, T_PN.NIK, T_USER.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_BIDANG.BDG_NAMA, T_LHKPN.JENIS_LAPORAN, T_LHKPN.TGL_LAPOR, T_PN.EMAIL')[0];
//                        
//                        
//        $datapn = $this->get_data_pn_by_id_lhkpn($ID_LHKPN, $also_redirect, $send_to_mail);
//        
        //NILAI PARAMETER KETIKA JIKA FALSE, maka ambil dari T_IMP_LHKPN (table temporary)
        //NILAI PARAMETER KETIKA JIKA TRUE, maka ambil dari T_LHKPN
        $datapn = $this->get_data_pn_by_id_lhkpn($ID_LHKPN, $also_redirect, FALSE);
        /**
          if(!valid_email($datapn->EMAIL)){
          $this->session->set_flashdata('success_message', 'success_message');
          //                $this->session->set_flashdata('message', 'Data LHKPN Tahun <b>' . $tahun . '</b> atas nama <b>' . strtoupper($pn->NAMA) . '</b> berhasil di kirim.');
          $this->session->set_flashdata('message', 'Data LHKPN atas nama <b>' . strtoupper($pn->NAMA) . '</b> belum berhasil di kirim. format email salah.');

          redirect('portal/filing');
          }
         * 
         */
//        echo $this->db->last_query();exit;
        
        //insert new t_pn_jabatan with tahun_wl curr year
        $this->load->model('mlhkpn');
        $check_thn_wl = $this->mlhkpn->getFieldWhere('t_pn_jabatan','ID','where ID_PN = '."'$datapn->ID_PN'".' and IS_CURRENT = 1 and IS_ACTIVE = 1 and ID_STATUS_AKHIR_JABAT IN (0,5) and tahun_wl = '.substr($datapn->TGL_LAPOR,0,4));
        $this->db->where('ID_PN', $datapn->ID_PN);
        $this->db->where('IS_CURRENT', '1');
        $this->db->where('IS_ACTIVE', '1');
        $this->db->where('ID_STATUS_AKHIR_JABAT <>', '1');
        $this->db->order_by('tahun_wl', 'desc');
        $this->db->limit(1);
        $getmax_thnwl = $this->db->get('t_pn_jabatan');

        $is_lhkpn_exist = $this->mglobal->get_by_id('t_lhkpn','ID_LHKPN',$ID_LHKPN);
        $is_manual_verification = true;

         ///// jika sudah pernah lapor lhkpn dan status laporan sekarang draft /////
        if($is_lhkpn_exist->STATUS=="0" && $is_lhkpn_exist){
 
            $where['T_LHKPN.ID_LHKPN ='] = $ID_LHKPN;
            $select = "T_LHKPN.ID_LHKPN, CASE WHEN fu_flag_sk_pn(T_LHKPN.ID_LHKPN) = '1' THEN CASE WHEN fu_flag_sk_pasangan(T_LHKPN.ID_LHKPN) NOT LIKE '%0%' 
                OR fu_flag_sk_pasangan(T_LHKPN.ID_LHKPN) IS NULL THEN CASE WHEN `fu_flag_sk_anak_tanggungan`(
                T_LHKPN.ID_LHKPN, T_LHKPN.tgl_lapor
                ) NOT LIKE '%0%' 
                OR `fu_flag_sk_anak_tanggungan`(
                T_LHKPN.ID_LHKPN, T_LHKPN.tgl_lapor
                ) IS NULL THEN 'Lengkap' ELSE 'Tidak Lengkap' END ELSE 'Tidak Lengkap' END ELSE 'Tidak Lengkap' END AS Status_Kelengkapan";
            $kelengkapan_SK = $this->mglobal->get_data_all('T_LHKPN', NULL, $where, $select)[0]->Status_Kelengkapan;

            $jml_harta = $this->jumlahHarta($ID_LHKPN);

            $harta_pn = [
                "total_harta_tidak_bergerak" => $jml_harta[0],
                "total_alat_transportasi"=> $jml_harta[1],
                "total_harta_bergerak_lain"=> $jml_harta[2],
                "total_surat_berharga"=> $jml_harta[3],
                "total_kas"=> (int)$jml_harta[4],
                "total_harta_lainnya"=> $jml_harta[5],
                "total_hutang"=> $jml_harta[6],
                "total_harta_kekayaan"=> $jml_harta[0]+$jml_harta[1]+$jml_harta[2]+$jml_harta[3]+$jml_harta[4]+$jml_harta[5]-$jml_harta[6]
            ];

            $tahun_wl = substr($datapn->TGL_LAPOR,0,4);

            list($rs1, $rs2) = $this->query_data_lhkpn($tahun_wl, $datapn->ID_PN);

            $data_params = [
                "tahun_wl"=> (int) $tahun_wl,
                "kelompok"=> (int) end(explode(' ', $rs2->kelompok)),
                "id_pn"=> (int) $rs2->id_pn,
                "id_lhkpn"=> $ID_LHKPN,
                "tingkat"=> $rs2->tingkat,
                "bidang"=> $rs2->bidang,
                "eselon"=> $rs2->eselon,
                "jabatan"=> '-',
                    'current_year'=>
                    [
                        "total_harta_tidak_bergerak" => (int) $harta_pn['total_harta_tidak_bergerak'],
                        "total_alat_transportasi"=> (int) $harta_pn['total_alat_transportasi'],
                        "total_harta_bergerak_lain"=> (int) $harta_pn['total_harta_bergerak_lain'],
                        "total_surat_berharga"=>  (int) $harta_pn['total_surat_berharga'],
                        "total_kas"=>  (int) $harta_pn['total_kas'],
                        "total_harta_lainnya"=> (int) $harta_pn['total_harta_lainnya'],
                        "total_hutang"=> (int) $harta_pn['total_hutang'],
                        "total_harta_kekayaan"=>  (int) $harta_pn['total_harta_kekayaan']
                    ],
                    "previous_year" =>
                    [
                        "total_harta_tidak_bergerak"=> (int) $rs2->total_harta_tidak_bergerak,
                        "total_alat_transportasi"=> (int) $rs2->total_alat_transportasi,
                        "total_harta_bergerak_lain"=> (int) $rs2->total_harta_bergerak_lain,
                        "total_surat_berharga"=> (int) $rs2->total_surat_berharga,
                        "total_kas"=> (int) $rs2->total_kas,
                        "total_harta_lainnya"=> (int) $rs2->total_harta_lainnya,
                        "total_hutang"=> (int) $rs2->total_hutang,
                        "total_harta_kekayaan"=> (int) $rs2->total_harta_kekayaan
                    ]
            ];

            $params_json = json_encode($data_params);
            
            $response_ai = $this->cek_data_lhkpn_by_ai($params_json);

            $result_ai = json_decode($response_ai);

            /// -- insert to t_lhkpn_ai_log -- ///
            $log_ai = [
                "id_lhkpn" => $ID_LHKPN,
                "http_response_code" => http_response_code(),
                "created_at" => date('Y-m-d H:i:s')
            ];
            $this->mglobal->insert('t_lhkpn_ai_log', $log_ai);
            
            ///// ---- jika response dari AI sukses ---- /////
            if(http_response_code() == 200 && isset($result_ai->manual_verification)){
          
                $is_manual_verification = $result_ai->manual_verification;

                $data_to_save = [
                    "id_lhkpn" => $ID_LHKPN,
                    "request" => $params_json,
                    "response" => $response_ai,
                    "kelengkapan_sk" => $kelengkapan_SK
                ];

                $is_exist = $this->mglobal->get_by_id('t_lhkpn_ai', 'id_lhkpn', $ID_LHKPN);

                if($is_exist){
                    $update_time = [
                        "updated_at" => date('Y-m-d H:i:s')
                    ];

                    $this->mglobal->update('t_lhkpn_ai', array_merge($data_to_save, $update_time), ['ID_LHKPN' => $ID_LHKPN]);

                }else{
                    $create_time = [
                        "created_at" => date('Y-m-d H:i:s')
                    ];

                    $this->mglobal->insert('t_lhkpn_ai', array_merge($data_to_save, $create_time));
                }

            }

            /* cek kelengkapan SK  dan lolos dari pengecekan AI 
                $is_manual_verification == FALSE (masuk ke verif otomatis) */
            if($kelengkapan_SK == "Lengkap" && $is_manual_verification == FALSE){

                /// --- ByPass proses penugasan & verifikasi --- /// 
                $this->db->trans_begin();

                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $this->db->update('t_lhkpn', array('STATUS' => '1', 'tgl_kirim_final' => date('Y-m-d'), 'entry_via' => 0));
                $this->db->flush_cache();

                $this->bypass_penugasan($ID_LHKPN, date('Y'));
               
                $this->bypass_verification($ID_LHKPN, date('Y'), $is_manual_verification);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();

                    $this->session->set_flashdata('error_message', 'error_message');
                    $this->session->set_flashdata('message', 'Mohon Maaf, data LHKPN atas nama <b>' . strtoupper($datapn->NAMA) . '</b> gagal di kirim.');
                    
                    redirect('portal/filing');

                } else {
                    $this->db->trans_commit();
                   
                }

                /// --- End ByPass --- /// 

            }else{
                /// verif manual ///
                $is_manual_verification = TRUE;
            }

        }


        if ($datapn->STATUS == '0' && $also_redirect){
            if (!$check_thn_wl){
                if ($getmax_thnwl->num_rows() == 1){
                    $getmax_thnwl = $getmax_thnwl->row();
                    $insert_fields = array(
                        'ID_PN' => $getmax_thnwl->ID_PN,
                        'ID_JABATAN' => $getmax_thnwl->ID_JABATAN,
                        'DESKRIPSI_JABATAN' => $getmax_thnwl->DESKRIPSI_JABATAN,
                        'ESELON' => $getmax_thnwl->ESELON,
                        'NAMA_LEMBAGA' => $getmax_thnwl->NAMA_LEMBAGA,
                        'LEMBAGA' => $getmax_thnwl->LEMBAGA,
                        'UNIT_KERJA' => $getmax_thnwl->UNIT_KERJA,
                        'SUB_UNIT_KERJA' => $getmax_thnwl->SUB_UNIT_KERJA,
                        'ALAMAT_KANTOR' => $getmax_thnwl->ALAMAT_KANTOR,
                        'EMAIL_KANTOR' => $getmax_thnwl->EMAIL_KANTOR,
                        'CREATED_TIME' => date('Y-m-d H:i:s'),
                        'CREATED_BY' => 'system_elhkpn',
                        'CREATED_IP' => '10.102.2.6',
                        'UPDATED_TIME' => date('Y-m-d H:i:s'),
                        'UPDATED_BY' => 'system_elhkpn',
                        'UPDATED_IP' => '10.102.2.6',
                        'IS_PRIMARY' => $getmax_thnwl->IS_PRIMARY,
                        'ID_STATUS_AKHIR_JABAT' => '0',
                        'KETERANGAN_AKHIR_JABAT' => $getmax_thnwl->KETERANGAN_AKHIR_JABAT,
                        'IS_CALON' => $getmax_thnwl->IS_CALON,
                        'IS_ACTIVE' => $getmax_thnwl->IS_ACTIVE,
                        'IS_DELETED' => $getmax_thnwl->IS_DELETED,
                        'IS_CURRENT' => $getmax_thnwl->IS_CURRENT,
                        'is_wl' => $getmax_thnwl->is_wl,
                        'tahun_wl' => substr($datapn->TGL_LAPOR,0,4),
                        'ALASAN_NON_WL' => $getmax_thnwl->ALASAN_NON_WL
                      );
                    $this->db->insert('t_pn_jabatan', $insert_fields);
                }
            }
        }
        //end insert new t_pn_jabatan with tahun_wl curr year
        switch ($datapn->JENIS_LAPORAN) {
            case 1:
                $j_lap = 'Khusus, Calon Penyelenggara';
                break;
            case 2:
                break;
                $j_lap = 'Khusus, Awal Menjabat';
            case 3:
                break;
                $j_lap = 'Khusus, Akhir Menjabat';
            default:
                $j_lap = 'Periodik';
                break;
        }

//        $date_now = date('Y-m-d');
//        if ($datapn->TGL_LAPOR == '0000-00-00') {
//            $tgl_lapor = $date_now;
//        } else {
//            $tgl_lapor = $datapn->TGL_LAPOR;
//        }
//
//        $tahun = get_format_tanggal_lapor_lhkpn($datapn->JENIS_LAPORAN, $tgl_lapor);
        $tahun = $this->get_tanggal_lapor_by_datapn($datapn);
    
        if ($ID_LHKPN) {
            $curr_date = date('Y-m-d');

            /**
             * jika dari lhkpnoffline
             * maka $curr_date akan direplace dengan tanggal penerimaan dokumen
             */
            $detail_penerimaan_lhkpnoffline = FALSE;
            if ($id_imp_xl_lhkpn) {
                $detail_penerimaan_lhkpnoffline = $this->mglobal->get_by_id("t_lhkpnoffline_penerimaan", "ID_IMP_XL_LHKPN", $id_imp_xl_lhkpn);
                if ($detail_penerimaan_lhkpnoffline) {
                    $curr_date = $detail_penerimaan_lhkpnoffline->TANGGAL_PENERIMAAN;
                }
            }
            $this->db->where('ID_LHKPN', $ID_LHKPN);



            $entry_via = $also_redirect ? '0' : '1';

            /**
             * $datapn->STATUS
             * yang dibaca adalah STATUS di tabel t_lhkpn
             */ 
            
            $update = null;
            $flag_is_manual_verif= $this->session->userdata('session_flag_is_manual_verif');

            if(!is_null($flag_is_manual_verif) && $flag_is_manual_verif == FALSE){
                $is_manual_verification = $flag_is_manual_verif;
            }


            if((isset($flag_is_manual_verif) && $flag_is_manual_verif == TRUE) || $is_manual_verification == TRUE){
                if ($datapn->STATUS == '0' || $datapn->STATUS == '7') {
                    $update = $this->db->update('t_lhkpn', array('STATUS' => '1', 'tgl_kirim_final' => $curr_date, 'entry_via' => $entry_via));
    
                    $id_status_history = $also_redirect ? '2' : '4';
    
                    /*
                    * add by eko
                    * insert status submit lhkpn ke history status lhkpn
                    */
                    $history = [
                        'ID_LHKPN' => $ID_LHKPN,
                        'ID_STATUS' => $id_status_history,
                        'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                        'USERNAME_PENERIMA' => 'Koordinator Verifikasi',
                        'DATE_INSERT' => date('Y-m-d H:i:s'),
                        'CREATED_IP' => $this->input->ip_address()
                    ];
    
                    $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
    
                } else if ($datapn->STATUS == '2') {
    
                    $data_update = array('STATUS' => '1', 'entry_via' => $entry_via);
                    if (!$also_redirect) {
                        $data_update["tgl_kirim_final"] = $curr_date;
                        $data_update["IS_ACTIVE"] = '1';
                    }
    
                    $update = $this->db->update('t_lhkpn', $data_update);
    
                    $history = [
                        'ID_LHKPN' => $ID_LHKPN,
                        'ID_STATUS' => 8,
                        'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                        'USERNAME_PENERIMA' => '',
                        'DATE_INSERT' => date('Y-m-d H:i:s'),
                        'CREATED_IP' => $this->input->ip_address()
                    ];
    
                    $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                }
            }else{ 
                
                /// verif otomatis ///
                $cek_session_verif_otomotis = $this->session->userdata('session_verif_otomatis');

                if($datapn->STATUS == '3' && $cek_session_verif_otomotis){
                    goto next_process;
                }

            }


            if ($update != null) {

                next_process :

                $pesan_valid = $also_redirect ? $this->preview_lp(FALSE, $datapn) : $this->preview_lp($id_imp_xl_lhkpn, $datapn);

                //proses cetak ikhtisar
                $create_ikhtisar = $this->cetak_ikhtisar($ID_LHKPN, 0, 1);
                $sess_kirim_lhkpn = $this->session->userdata('sess_kirim_lhkpn');
                
                $file_data = array();
                $file_data = end($sess_kirim_lhkpn->data)->word_location;
                
                if ($sess_kirim_lhkpn && $sess_kirim_lhkpn->id_lhkpn == $ID_LHKPN && is_array($sess_kirim_lhkpn->data)) {
                    array_pop($sess_kirim_lhkpn->data);
                    foreach ($sess_kirim_lhkpn->data as $sess_data) {
                            if($sess_data->subject != "Lampiran Surat Kuasa Mengumumkan LHKPN"){
                            $this->__send_to_mailbox($sess_data->id_user, $sess_data->pesan, $sess_data->subject, $sess_data->word_location, $sess_data->is_trusted, '1', $ID_LHKPN);
                            }
                    }
                }
                $this->session->unset_userdata('sess_kirim_lhkpn');
                unset($sess_kirim_lhkpn);

                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN');
                $pn = $this->db->get('t_lhkpn')->row();
                $tahun = substr($pn->TGL_LAPOR, 0, 4);

                $data = array(
                    'ID_PENGIRIM' => 1,
                    'ID_PENERIMA' => $this->session->userdata('ID_USER'),
                    'SUBJEK' => 'Lembar Penyerahan dan Ikhtisar LHKPN',
                    'PESAN' => utf8_encode($pesan_valid),
                        'FILE' => NULL, //$file_data,
                    'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
                    'ID_LHKPN' => $ID_LHKPN,
                    'IS_ACTIVE' => '1'
                );

                $send = $this->db->insert('T_PESAN_KELUAR', $data);

                if ($send) {
                    $data2 = array(
                        'ID_PENGIRIM' => 1,
                        'ID_PENERIMA' => $this->session->userdata('ID_USER'),
                        'SUBJEK' => 'Lembar Penyerahan dan Ikhtisar LHKPN',
                        'PESAN' => utf8_encode($pesan_valid),
                            'FILE' => NULL, //$file_data,
                        'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                        'ID_LHKPN' => $ID_LHKPN,
                        'IS_ACTIVE' => '1'
                    );
                    $send2 = $this->db->insert('T_PESAN_MASUK', $data2);

                    }
                    $pengirim = "E-Filling LHKPN";
                    $idUser = 1;
                    $penerima = $datapn->EMAIL;

                $penerima_email = $penerima;
                /**
                 * Eksekusi disini
                 */
                // $sess_kirim_lhkpn = $this->session->userdata('sess_kirim_lhkpn');

                // if ($sess_kirim_lhkpn &&
                //         $sess_kirim_lhkpn->id_lhkpn == $ID_LHKPN &&
                //         is_array($sess_kirim_lhkpn->data)) {

                //     foreach ($sess_kirim_lhkpn->data as $sess_data) {
                //         $this->__send_to_mailbox($sess_data->id_user, $sess_data->pesan, $sess_data->subject, $sess_data->word_location, $sess_data->is_trusted);
                //     }
                // }
                // $this->session->unset_userdata('sess_kirim_lhkpn');
                // unset($sess_kirim_lhkpn);

                
                if ($send_to_mail) {
                    $file_data_attachment = explode('/',$file_data,4);
                    log_message('error', 'LHKPN KIRIM untuk verifikasi : send to mail');
                    $send_lembar_penyerahan = ng::mail_send($penerima_email, 'Lembar Penyerahan E-Filling LHKPN', $pesan_valid, null, $file_data_attachment[3]);
//                    CallURLPage('http://api.multichannel.id:8088/sms/sync/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=e-LHKPN+Saudara+Berhasil+Terkirim,+silahkan+cek+Lembar+Penyerahan+dan+Ikhtisar+Harta+yang+dikirim+ke+email+Saudara+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198&msisdn='.$pn->NO_HP.'');
                    CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=e-LHKPN%20Saudara%20Berhasil%20Terkirim,%20silahkan%20cek%20Lembar%20Penyerahan%20dan%20Ikhtisar%20Harta%20yang%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$pn->NO_HP.'');
                        ng::logSmsActivity($this->session->userdata('ID_USER'),$datapn->ID_ROLE,$datapn->NO_HP, 'e-LHKPN Bapak/Ibu Berhasil Terkirim, silahkan cek Lembar Penyerahan dan Ikhtisar Harta yang dikirim ke email Saudara Info : elhkpn@kpk.go.id atau 198', 'Kirim LHKPN');
                        // $send_lembar_penyerahan = ng::mail_send_queue($penerima_email, 'Lembar Penyerahan E-Filling LHKPN', $pesan_valid, NULL, NULL,$file_data_attachment[3], NULL, NULL, NULL, NULL, TRUE);
//                    CallURLPage('http://sms.citrahost.com/citra-sms.api.php?action=send&outhkey=f9f26aac2547d8831638643c0f4471da&secret=30212ddf7cb857d67e08e131aa9670e0&pesan=e-LHKPN%20Bapak/Ibu%20Berhasil%20Terkirim,%20silahkan%20cek%20Lembar%20Penyerahan%20dan%20Ikhtisar%20Harta%20yang%20dikirim%20ke%20email%20Saudara%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&to='.$pn->NO_HP.'');
                        //  unlink($file_data_attachment[3]);


                        if(isset($cek_session_verif_otomotis) && $send_lembar_penyerahan == true){
                            $data = $cek_session_verif_otomotis;

                            sleep(2);

                            /// kirim Tanda Terima ( Verifikasi ) ////
                            ng::mail_send_queue($data['to'], $data['subject'], $data['message'], NULL, NULL, $data['attach'], NULL, NULL, NULL, NULL, $data['is_unlink_attach']);

                            $this->session->unset_userdata('session_verif_otomatis');
                            $this->session->unset_userdata('session_flag_is_manual_verif');
                        }
                } else {
                    log_message('info', 'no send to mail');
                }

                $this->session->set_flashdata('success_message', 'success_message');
//                $this->session->set_flashdata('message', 'Data LHKPN Tahun <b>' . $tahun . '</b> atas nama <b>' . strtoupper($pn->NAMA) . '</b> berhasil di kirim.');
                $this->session->set_flashdata('message', 'Data LHKPN atas nama <b>' . strtoupper($pn->NAMA) . '</b> berhasil di kirim.');
                if ($also_redirect) {
                    redirect('portal/filing');
                }
            }
        } else {
            if ($also_redirect) {
                redirect('portal/filing');
            }
        }
    }

    protected function get_id_imp_lhkpn($record = FALSE) {
        if ($record) {
            if (property_exists($record, 'ID_imp_xl_LHKPN')) {
                return $record->ID_imp_xl_LHKPN;
            }
            if (property_exists($record, 'id_imp_xl_lhkpn')) {
                return $record->id_imp_xl_lhkpn;
            }
        }
        return FALSE;
    }

    function query_data_lhkpn($tahun_wl, $ID_PN){
        $sql = "SELECT `Tahun WL` AS tahun_wl, 
            `Id Pn` AS id_pn, 
            `id_lhkpn`, 
            `Tingkat` AS tingkat, 
            `Bidang` AS bidang, 
            `Eselon` AS eselon, 
            CASE WHEN Jabatan LIKE 'Hakim%' THEN 'Hakim' WHEN (
            `Unit Kerja` LIKE '%komisaris%' 
            OR `jabatan` LIKE '%komisaris%'
            ) 
            AND bidang = 'BUMN/BUMD' THEN 'Komisaris' WHEN (`Unit Kerja` LIKE '%Direksi%') 
            AND bidang = 'BUMN/BUMD' THEN 'Direksi' WHEN `Unit Kerja` LIKE '%Dewan Pengawas%' 
            AND bidang = 'BUMN/BUMD' THEN 'Dewan Pengawas' WHEN `Nama Instansi` = 'DEWAN PERWAKILAN RAKYAT (DPR)' THEN 'Anggota DPR' WHEN `Nama Instansi` LIKE 'DEWAN PERWAKILAN RAKYAT DAERAH%' 
            THEN 'Anggota DPRD' ELSE '' END AS jabatan, 
            CAST(
            `Total Harta Tidak Bergerak` AS DECIMAL(20)
            ) AS total_harta_tidak_bergerak, 
            CAST(
            `Total Alat Transportasi` AS DECIMAL(20)
            ) AS total_alat_transportasi, 
            CAST(
            `Total Harta Bergerak Lain` AS DECIMAL(20)
            ) AS total_harta_bergerak_lain, 
            CAST(
            `Total Surat Berharga` AS DECIMAL(20)
            ) AS total_surat_berharga, 
            CAST(`Total Kas` AS DECIMAL(20)) AS total_kas, 
            CAST(`Total Harta Lainnya` AS DECIMAL(20)) AS total_harta_lainnya, 
            CAST(`Total Hutang` AS DECIMAL(20)) AS total_hutang, 
            CAST(
            `Total Harta Tidak Bergerak` + `Total Alat Transportasi` + `Total Harta Bergerak Lain` + `Total Surat Berharga` + `Total Kas` + `Total Harta Lainnya` - `Total Hutang` AS DECIMAL(20)
            ) AS total_harta_kekayaan, 
            CASE WHEN Eselon = '0' 
            AND bidang NOT IN ('LEGISLATIF', 'BUMN/BUMD') THEN 'Kelompok 1' WHEN Tingkat LIKE '%Pusat%' 
            AND Bidang IN ('EKSEKUTIF', 'YUDIKATIF') 
            AND Eselon = 'I' THEN 'Kelompok 2' WHEN Tingkat LIKE '%Pusat%' 
            AND Bidang IN ('EKSEKUTIF') 
            AND Eselon = 'II' THEN 'Kelompok 3' WHEN Tingkat LIKE '%Daerah%' 
            AND Bidang IN ('EKSEKUTIF') 
            AND Eselon = 'I' THEN 'Kelompok 4' WHEN Tingkat LIKE '%Daerah%' 
            AND Bidang IN ('EKSEKUTIF') 
            AND Eselon = 'II' THEN 'Kelompok 5' WHEN Bidang IN ('YUDIKATIF') 
            AND Eselon = 'II' THEN 'Kelompok 6' WHEN Tingkat LIKE '%Pusat%' 
            AND Bidang IN ('YUDIKATIF') 
            AND Jabatan LIKE 'Hakim%' THEN 'Kelompok 7' WHEN Tingkat LIKE '%Pusat%' 
            AND Bidang IN ('LEGISLATIF') THEN 'Kelompok 8' WHEN Tingkat LIKE '%Daerah%' 
            AND Bidang IN ('LEGISLATIF') 
            AND `Nama Instansi` LIKE 'DEWAN PERWAKILAN RAKYAT DAERAH%' THEN 'Kelompok 9' WHEN Tingkat LIKE '%Pusat%' 
            AND Bidang IN ('BUMN/BUMD') 
            AND (
            (
                `Unit Kerja` LIKE '%komisaris%' 
                OR `jabatan` LIKE '%komisaris%'
            ) 
            OR (`Unit Kerja` LIKE '%Direksi%') 
            OR (
                `Unit Kerja` LIKE '%Dewan Pengawas%'
            )
            ) THEN 'Kelompok 10' WHEN Tingkat LIKE '%Daerah%' 
            AND Bidang IN ('BUMN/BUMD') 
            AND (
            (
                `Unit Kerja` LIKE '%komisaris%' 
                OR `jabatan` LIKE '%komisaris%'
            ) 
            OR (`Unit Kerja` LIKE '%Direksi%') 
            OR (
                `Unit Kerja` LIKE '%Dewan Pengawas%'
            )
            ) THEN 'Kelompok 11' ELSE 'Kelompok 12' END AS kelompok FROM 
            `mart_data_elhkpn_kepatuhan_v1` 
            WHERE";
            
            $query_one = $sql . "
               `nilai_rangkap` = 1 
                AND `Tahun WL` = $tahun_wl 
                AND `Id Pn` = $ID_PN;";

            $query_two = $sql . "
                id_lhkpn IS NOT NULL 
                AND `nilai_rangkap` = 1 
                AND `Tahun WL` = $tahun_wl-1 
                AND `Id Pn` = $ID_PN;";

        $rs1 = $this->db->query($query_one)->result()[0];
        $rs2 = $this->db->query($query_two)->result()[0];

        return array($rs1, $rs2);
    }

    function cek_data_lhkpn_by_ai($params){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://172.16.10.166:5000/api/lhkpn/verifikasi',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_PROXY => '',
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$params,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        
        curl_close($curl);

        return $response;   
    }

    /**
     * 
     * @param type $data_detail
     * @param type $detected_id
     * @param type $column_name
     * @param array $table_info ["table_name"], ["primary_key"], ["pk_without_table_name"]
     * @return boolean
     */
    protected function save_to_folder_nik($data_detail = FALSE, $detected_id = FALSE, $column_name = "FILE_BUKTI", $table_info = array(), $data_detail_empty = TRUE) {
        if (!$data_detail_empty) {
            if (!$data_detail || !$detected_id || empty($table_info)) {
                return FALSE;
            }
        }

        if ($data_detail[$column_name] == '') {
            return FALSE;
        }

        if ($data_detail_empty) {
            $detail_data_harta = $this->mglobal->secure_get_by_id($table_info["table_name"], $table_info["primary_key"], $table_info["pk_without_table_name"], $detected_id);

            if (!$detail_data_harta) {
                return FALSE;
            }
        }

        $data_penerimaan = $this->get_lhkpn_penerimaan_by_id_temp_lhkpn($this->get_id_imp_lhkpn($detail_data_harta));
        unset($detail_data_harta);

        if (!$data_penerimaan) {
            return FALSE;
        }

        $rand_id = $data_penerimaan->RAND_ID;
        $data_pn = $this->mglobal->get_by_id("T_PN", "ID_PN", $data_penerimaan->ID_PN);
        unset($data_penerimaan);

        if (!$data_pn) {
            return FALSE;
        }

        $encrypted_username = encrypt_username($data_pn->NIK, 'e');

        // Make destination directory
        if (!is_dir("./uploads/" . $this->upload_harta_location . "/" . $encrypted_username . "/")) {
            mkdir("./uploads/" . $this->upload_harta_location . "/" . $encrypted_username . "/", 0777, TRUE);
        }

        copyr(self::DIR_TEMP_UPLOAD . $rand_id . "/" . $data_detail[$column_name], "./uploads/" . $this->upload_harta_location . "/" . $encrypted_username . "/" . $data_detail[$column_name]);

        unset($encrypted_username, $data_pn, $rand_id);
        return TRUE;
    }

    //////////////////// start cetak ikhtisar //////////////////////
    
    function cetak_ikhtisar($ID_LHKPN = FALSE, $is_validation = FALSE, $send_session = NULL) {
        $this->load->model('mlhkpnkeluarga');
        $cek_id_user = FALSE;
        $data = array();
        if ($is_validation) {
            $data = $this->cetak_ikhtisar_validation($ID_LHKPN);
            $cek_id_user = TRUE;
        } else {
            $cek_id_user = $this->lhkpn($ID_LHKPN);
        }
        $id_is_ok_and_ready_print = $this->is_ever ? (bool) $ID_LHKPN : ($ID_LHKPN && $cek_id_user);

        // if ($ID_LHKPN && $cek_id_user) {
        if ($id_is_ok_and_ready_print) {
            $lhkpn_ver = FALSE;
            if ($this->is_ever) {
                $lhkpn_ver = $this->mlhkpnkeluarga->get_lhkpn_version($ID_LHKPN);
            }
            if (!$is_validation) {
                $data = $this->__get_data_cetak($ID_LHKPN);
            }
            $is_copy = NULL;
            if ($data['LHKPN'] && property_exists($data['LHKPN'], 'IS_COPY')) {
                $is_copy = $data['LHKPN']->IS_COPY;
            }
            list($BIDANG, $LEMBAGA, $JABATAN, $SUK, $UK) = $this->__set_data_cetak_jabatan($data['JABATAN']);
            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));
            $template_file = "../file/template/IkhtisarLHKPNTemplate.docx";
            /*
              $_temp_dir = FALSE, $_model_qr = FALSE, $_callable_model_function = FALSE
             */
            /*
             * penulisan qrcode

              $this->load->library('lws_qr', [
              "model_qr" => "Cqrcode",
              "callable_model_function" => "insert_cqrcode_with_filename"
              ]);
             */
            $this->_cnt_nilai_pelaporan_harta_bergerak($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_tidak_bergerak($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_bergerak_lain($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_surat_berharga($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_kas($ID_LHKPN, 1);
            $this->_cnt_nilai_pelaporan_harta_lainnya($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_hutang($ID_LHKPN);
            $this->_cnt_penerimaan_gaji($ID_LHKPN);
            $this->_cnt_penerimaan_usaha($ID_LHKPN);
            $this->_cnt_penerimaan_lainnya($ID_LHKPN);
            $this->_cnt_pengeluaran_rutin($ID_LHKPN);
            $this->_cnt_pengeluaran_harta($ID_LHKPN);
            $this->_cnt_pengeluaran_lainnya($ID_LHKPN);

            $total_harta = $this->jumlah_htb + $this->jumlah_hb + $this->jumlah_hbl + $this->jumlah_sb +$this->jumlah_kas + $this->jumlah_hl;
            $total_hutang = $this->jumlah_shtg;
            $total_harta_kekayaan = $total_harta - $total_hutang;
            $catatan = "Rincian harta kekayaan dalam ikhtisar LHKPN merupakan dokumen yang dicetak secara otomatis dari elhkpn.kpk.go.id. Seluruh data dan informasi yang tercantum dalam Dokumen ini sesuai dengan LHKPN yang diisi dan dikirimkan sendiri oleh Penyelenggara Negara yang bersangkutan melalui elhkpn.kpk.go.id serta tidak dapat dijadikan dasar oleh Penyelenggara Negara atau siapapun juga untuk menyatakan bahwa harta yang bersangkutan tidak terkait tindak pidana. \n\nApabila dikemudian hari terdapat harta kekayaan milik Penyelenggara Negara dan/atau Keluarganya yang tidak dilaporkan dalam LHKPN, maka Penyelenggara Negara wajib untuk bertanggung jawab sesuai dengan peraturan perundang-undangan yang berlaku.";

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "model_qr_prefix_nomor" => "PHK-ELHKPN-",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
            ]);

            $qr_content_data = json_encode((object) [
                "data" => [
                    (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $data['PRIBADI']->NAMA_LENGKAP],
                    (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data['PRIBADI']->NHK == NULL ? '-' : $data['PRIBADI']->NHK],
                    (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $BIDANG],
                    (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $JABATAN."-".$SUK."-".$UK],
                    (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $LEMBAGA],
                    (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($data['LHKPN']->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus') . " - " . show_jenis_laporan_khusus($data['LHKPN']->JENIS_LAPORAN, $data['LHKPN']->tgl_lapor, tgl_format($data['LHKPN']->tgl_lapor))],
                    (object) ["tipe" => '1', "judul" => "Tanggal Pelaporan", "isi" => tgl_format($data['LHKPN']->tgl_lapor)],
                    (object) ["tipe" => '1', "judul" => "Tanah dan Bangunan", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_htb), "-")],
                    (object) ["tipe" => '1', "judul" => "Alat Transportasi dan Mesin", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hb), "-")],
                    (object) ["tipe" => '1', "judul" => "Harta Bergerak Lainnya", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hbl), "-")],
                    (object) ["tipe" => '1', "judul" => "Surat Berharga", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_sb), "-")],
                    (object) ["tipe" => '1', "judul" => "Kas dan Setara Kas", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_kas), "-")],
                    (object) ["tipe" => '1', "judul" => "Harta Lainnya", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hl), "-")],
                    (object) ["tipe" => '1', "judul" => "Hutang", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_hutang), "-")],
                    (object) ["tipe" => '1', "judul" => "Total Harta Kekayaan", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_harta_kekayaan), "-")],
                    (object) ["tipe" => '1', "judul" => "Catatan", "isi" => $catatan],
                ],
                "encrypt_data" => $data['PRIBADI']->ID_LHKPN . "phk",
                "id_lhkpn" => $ID_LHKPN,
                "judul" => "Pengumuman/Ikhtisar Harta Kekayaan Penyelenggara Negara",
                "tgl_surat" => date('Y-m-d'),
            ]);

            $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $data['LHKPN']->ID_LHKPN . ".png");
            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image1.jpeg" => $qr_image_location));

            if (!$load_template_success) {
                throw new Exception("Gagal Mencetak Data.");
                exit;
            }
            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";
            if ($load_template_success) {
                /**
                 * DATA PRIBADI
                 */
                if ($data['PRIBADI']->JENIS_KELAMIN != ''){
                    if ($data['PRIBADI']->JENIS_KELAMIN == '1') {
                        $jenkel = 'Laki Laki';
                    }
                    elseif ($data['PRIBADI']->JENIS_KELAMIN == '2') {
                        $jenkel = 'Perempuan';
                    }
                    else{
                        $jenkel = $data['PRIBADI']->JENIS_KELAMIN;
                    }
                }
                $alamat_rumah = $this->__is_cetak_var_not_blank($data['PRIBADI']->ALAMAT_RUMAH, "-").", ".$this->__is_cetak_var_not_blank($data['PRIBADI']->KECAMATAN, "-").", ".$this->__is_cetak_var_not_blank($data['PRIBADI']->KABKOT, "-").", ".$this->__is_cetak_var_not_blank($data['PRIBADI']->PROVINSI, "-");
                $this->lwphpword->set_value("NAMA_LENGKAP", $this->__is_cetak_var_not_blank($data['PRIBADI']->NAMA_LENGKAP, "-"));
                $this->lwphpword->set_value("NHK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NHK, "-"));
                $this->lwphpword->set_value("NIK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NIK, "-"));
                $this->lwphpword->set_value("KK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NO_KK, "-"));
                $this->lwphpword->set_value("NPWP", $this->__is_cetak_var_not_blank($data['PRIBADI']->NPWP, "-"));
                $this->lwphpword->set_value("JK", $this->__is_cetak_var_not_blank($jenkel, "-"));
                $this->lwphpword->set_value("TTL", $this->__is_cetak_var_not_blank($data['PRIBADI']->TEMPAT_LAHIR, "-").'/'.$this->__is_cetak_var_not_blank(tgl_format($data['PRIBADI']->TANGGAL_LAHIR), "-"));
                $this->lwphpword->set_value("STATUS", $this->__is_cetak_var_not_blank($data['PRIBADI']->STATUS_PERKAWINAN, "-"));
                $this->lwphpword->set_value("AGAMA", $this->__is_cetak_var_not_blank($data['PRIBADI']->AGAMA, "-"));
                $this->lwphpword->set_value("ALAMAT", $this->__is_cetak_var_not_blank($alamat_rumah, "-"));
                $this->lwphpword->set_value("NOHP", $this->__is_cetak_var_not_blank($data['PRIBADI']->HP, "-"));
                $this->lwphpword->set_value("EMAIL", $this->__is_cetak_var_not_blank($data['PRIBADI']->EMAIL_PRIBADI, "-"));
                $this->lwphpword->set_value("BIDANG", $this->__is_cetak_var_not_blank($BIDANG, "-"));
                $this->lwphpword->set_value("LEMBAGA", $this->__is_cetak_var_not_blank($LEMBAGA, "-"));
                $this->lwphpword->set_value("JABATAN", $this->__is_cetak_var_not_blank($JABATAN, "-"));
                $this->lwphpword->set_value("SUK", $this->__is_cetak_var_not_blank($SUK, "-"));
                $this->lwphpword->set_value("UK", $this->__is_cetak_var_not_blank($UK, "-"));

                
                $this->lwphpword->set_value("TGL_LAPOR", $this->__is_cetak_var_not_blank(tgl_format($data['LHKPN']->tgl_lapor), "-"));
                $this->lwphpword->set_value("TGL_KIRIM", $this->__is_cetak_var_not_blank(tgl_format($data['LHKPN']->tgl_kirim_final), "-"));

                $tgl_cetak = date('d/m/Y');
                $thn_lapor = date_format(date_create($data['LHKPN']->tgl_lapor),'Y');
                if($data['LHKPN']->JENIS_LAPORAN==1){
                    $jenis_laporan = "Khusus (Calon PN)";
                }elseif($data['LHKPN']->JENIS_LAPORAN==2){
                    $jenis_laporan = "Khusus (Awal Menjabat)";
                }elseif($data['LHKPN']->JENIS_LAPORAN==3){
                    $jenis_laporan = "Khusus (Akhir Menjabat)";
                }elseif($data['LHKPN']->JENIS_LAPORAN==4){
                    $jenis_laporan = "Periodik";
                }else{
                    $jenis_laporan = "-";
                }

                if($data['LHKPN']->STATUS==0){
                    $status_lhkpn = "Draft";
                }elseif($data['LHKPN']->STATUS==1){
                    $status_lhkpn = "Proses Verifikasi";
                }elseif($data['LHKPN']->STATUS==2){
                    $status_lhkpn = "Perlu Perbaikan";
                }elseif($data['LHKPN']->STATUS==3){
                    $status_lhkpn = "Terverifikasi Lengkap";
                }elseif($data['LHKPN']->STATUS==4){
                    $status_lhkpn = "Diumumkan Lengkap";
                }elseif($data['LHKPN']->STATUS==5){
                    $status_lhkpn = "Terverifikasi Tidak Lengkap";
                }elseif($data['LHKPN']->STATUS==6){
                    $status_lhkpn = "Diumumkan Tidak Lengkap";
                }elseif($data['LHKPN']->STATUS==7){
                    $status_lhkpn = "Ditolak";
                }else{
                    $status_lhkpn = "-";
                }

                $this->lwphpword->set_value("STATUS_LHKPN", $status_lhkpn);
                $this->lwphpword->set_value("JENIS_LAPORAN", $jenis_laporan);
                $this->lwphpword->set_value("TAHUN_LAPOR", $this->__is_cetak_var_not_blank($thn_lapor, "-"));
                $this->lwphpword->set_value("HEADER_TAHUN_LAPOR", $this->__is_cetak_var_not_blank($thn_lapor, "-"));
                $this->lwphpword->set_value("TGL_CETAK", $tgl_cetak);
                
                /**
                 * DATA KELUARGA
                 */
                $get_data_keluarga = $this->write_table_data_keluarga($data['KELUARGA'],$lhkpn_ver);
                $this->lwphpword->set_xml_value("table_data_keluarga", $get_data_keluarga);
                /**
                 * DATA JABATAN
                 */
                $get_data_jabatan = $this->write_table_data_jabatan($data['JABATAN']);
                $this->lwphpword->set_xml_value("table_data_jabatan", $get_data_jabatan);

                /**
                 * DATA HARTA TIDAK BERGERAK
                 */
                $get_data_htb = $this->write_table_data_htb($data['HARTA_TDK_BEGERAK'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_htb), "-"));
                $this->lwphpword->set_xml_value("table_data_htb", $get_data_htb);
                
                /**
                 * DATA HARTA BERGERAK
                 */
                $get_data_hb = $this->write_table_data_hb($data['HARTA_BERGERAK'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hb), "-"));
                $this->lwphpword->set_xml_value("table_data_hb", $get_data_hb);
                /**
                 * DATA HARTA BERGERAK LAINNYA
                 */
                $get_data_hbl = $this->write_table_data_hbl($data['HARTA_BERGERAK_LAIN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hbl), "-"));
                $this->lwphpword->set_xml_value("table_data_hbl", $get_data_hbl);

                /**
                 * DATA HARTA SURAT BERHARGA ------------------ (terdapat kendala di local)
                 */
                $get_data_sb = $this->write_table_data_sb($data['HARTA_SURAT_BERHARGA'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_sb), "-"));
                $this->lwphpword->set_xml_value("table_data_sb", $get_data_sb);

                /**
                 * DATA HARTA KAS DAN SETARA KAS
                 */
                $get_data_kas = $this->write_table_data_kas($data['HARTA_KAS'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_kas), "-"));
                $this->lwphpword->set_xml_value("table_data_kas", $get_data_kas);

                /**
                 * DATA HARTA LAINNYA
                 */
                $get_data_hl = $this->write_table_data_hl($data['HARTA_LAINNYA'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hl), "-"));
                $this->lwphpword->set_xml_value("table_data_hl", $get_data_hl);

                /**
                 * DATA HUTANG
                 */
                $get_data_htg = $this->write_table_data_htg($data['HUTANG'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_ahtg), "-"),$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_shtg), "-"));
                $this->lwphpword->set_xml_value("table_data_htg", $get_data_htg);

                /**
                 * DATA PENERIMAAN GAJI
                 */
                $get_data_penerimaan_pekerjaan = $this->write_table_data_penerimaan_pekerjaan($data['PENERIMAAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pg_pn), "-"),$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pg_ps), "-"));
                $this->lwphpword->set_xml_value("table_data_penerimaan_pekerjaan", $get_data_penerimaan_pekerjaan);

                /**
                 * DATA PENERIMAAN USAHA
                 */
                $get_data_penerimaan_usaha = $this->write_table_data_penerimaan_usaha($data['PENERIMAAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pu_pn), "-"));
                $this->lwphpword->set_xml_value("table_data_penerimaan_usaha", $get_data_penerimaan_usaha);

                /**
                 * DATA PENERIMAAN LAINNYA
                 */
                $get_data_penerimaan_lainnya = $this->write_table_data_penerimaan_lainnya($data['PENERIMAAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pl_pn), "-"));
                $this->lwphpword->set_xml_value("table_data_penerimaan_lainnya", $get_data_penerimaan_lainnya);

                /**
                 * DATA PENGELUARAN RUTIN
                 */
                $get_data_pengeluaran_rutin = $this->write_table_data_pengeluaran_rutin($data['PENGELUARAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pr), "-"));
                $this->lwphpword->set_xml_value("table_data_pengeluaran_rutin", $get_data_pengeluaran_rutin);

                /**
                 * DATA PENGELUARAN HARTA
                 */
                $get_data_pengeluaran_harta = $this->write_table_data_pengeluaran_harta($data['PENGELUARAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_ph), "-"));
                $this->lwphpword->set_xml_value("table_data_pengeluaran_harta", $get_data_pengeluaran_harta);

                /**
                 * DATA PENGELUARAN LAINNYA
                 */
                $get_data_pengeluaran_lainnya = $this->write_table_data_pengeluaran_lainnya($data['PENGELUARAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pl), "-"));
                $this->lwphpword->set_xml_value("table_data_pengeluaran_lainnya", $get_data_pengeluaran_lainnya);

                /**
                 * DATA LAMPIRAN FASILITAS
                 */
                $get_data_lampiran_fasilitas = $this->write_table_data_lampiran_fasilitas($data['FASILITAS'],$is_copy);
                $this->lwphpword->set_xml_value("table_data_lampiran_fasilitas", $get_data_lampiran_fasilitas);

                /**
                 * DATA TOTAL HARTA KEKAYAAN
                 */
                $get_data_total_harta = $this->write_table_data_total_harta($total_harta,$total_hutang,$total_harta_kekayaan);
                $this->lwphpword->set_xml_value("table_data_total_harta", $get_data_total_harta);
                
                $this->lwphpword->set_value("catatan", $catatan);

                if(empty($send_session)) {
                    $save_document_success = $this->lwphpword->save_document();
                    if ($save_document_success) {
                        $output_filename = "IkhtisarHarta" . date('d-F-Y H:i:s') . $ID_LHKPN;
                        $this->lwphpword->download($save_document_success, $output_filename);
                    }
                    unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
                }
                else {
                    $download_filename = "IkhtisarHarta-" . date('dmYhi') . $ID_LHKPN. ".docx";
                    $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $download_filename);

                    if ($save_document_success) {
                        $subject = null;
                        $pesan = null;
                        
                        $ikhtisar_harta = (object) $this->sess_template_data;
                        $ikhtisar_harta->id_user = $this->session->userdata('ID_USER');
                        $ikhtisar_harta->pesan = $pesan;
                        $ikhtisar_harta->subject = $subject;
                        $ikhtisar_harta->word_location = "../../../file/wrd_gen/" . $save_document_success->document_name;


                        $this->__send_session_kirim_lhkpn($ID_LHKPN, $ikhtisar_harta);
                        unset($ikhtisar_harta);
                    }
                }
            }
            $temp_dir = APPPATH."../../images/qrcode/";
            $qr_image = "tes_qr2-" . $data['LHKPN']->ID_LHKPN . ".png";
            unlink($temp_dir.$qr_image);
            return true;
        } else {
            redirect('portal/filing');
            return false;
        }
    }

    protected function __is_cetak_var_not_blank($val, $default_value = "", $bool = FALSE) {
        $val = trim($val);
        if ($val != "" && $val != NULL && $val != FALSE) {
            return $bool ? TRUE : htmlspecialchars($val);
        }
        return $bool ? FALSE : $default_value;
    }

    function cetak_ikhtisar_validation($id_imp_xl_lhkpn = FALSE) {
        $data_imp_xl_lhkpn = $this->imp_xl_lhkpn($id_imp_xl_lhkpn);

        $this->is_validation = TRUE;
        $this->field_id_lhkpn = 'id_imp_xl_lhkpn';
        $this->table_lhkpn = 't_imp_xl_lhkpn';

        if ($id_imp_xl_lhkpn && $data_imp_xl_lhkpn) {
            // $data = $this->__get_data_cetak($id_imp_xl_lhkpn);
            $data = $this->__get_data_cetak_imp_xl_lhkpn($id_imp_xl_lhkpn);

            $data['LHKPN'] = $data_imp_xl_lhkpn;
        }
        return $data;
    }

    function imp_xl_lhkpn($imp_xl_lhkpn) {
        $this->db->where('t_imp_xl_lhkpn.id_imp_xl_lhkpn', $imp_xl_lhkpn);
        $data = $this->db->get('t_imp_xl_lhkpn')->row();
        return $data;
    }

    protected function __get_data_cetak_imp_xl_lhkpn($id_imp_xl_lhkpn) {

        $data = array();
        $data['PRIBADI'] = $this->pribadi($id_imp_xl_lhkpn);
        $data['KELUARGA'] = $this->keluarga($id_imp_xl_lhkpn);
        $data['JABATAN'] = $this->jabatan($id_imp_xl_lhkpn);

        $data['HARTA_TDK_BEGERAK'] = $this->harta_tidak_bergerak($id_imp_xl_lhkpn);
        $data['HARTA_BERGERAK'] = $this->harta_bergerak($id_imp_xl_lhkpn);

        $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($id_imp_xl_lhkpn);
        $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($id_imp_xl_lhkpn);
        $data['HARTA_LAINNYA'] = $this->harta_lainnya($id_imp_xl_lhkpn);
        $data['HARTA_KAS'] = $this->harta_kas($id_imp_xl_lhkpn);
        $data['HUTANG'] = $this->hutang($id_imp_xl_lhkpn);

        $data['PENERIMAAN'] = $this->penerimaan($id_imp_xl_lhkpn);
        $data['PENGELUARAN'] = $this->pengeluaran($id_imp_xl_lhkpn);
        $data['FASILITAS'] = $this->fasilitas($id_imp_xl_lhkpn);

        return $data;
    }

    protected function __get_data_cetak($ID_LHKPN) {
        $data = array();
        $data['LHKPN'] = $this->lhkpn($ID_LHKPN);
        $data['PRIBADI'] = $this->pribadi($ID_LHKPN);
        $data['KELUARGA'] = $this->keluarga($ID_LHKPN);
        $data['JABATAN'] = $this->jabatan($ID_LHKPN);
        $data['HARTA_TDK_BEGERAK'] = $this->harta_tidak_bergerak($ID_LHKPN);
        $data['HARTA_BERGERAK'] = $this->harta_bergerak($ID_LHKPN);

        $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($ID_LHKPN);
        $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($ID_LHKPN);
        $data['HARTA_LAINNYA'] = $this->harta_lainnya($ID_LHKPN);
        $data['HARTA_KAS'] = $this->harta_kas($ID_LHKPN);
        $data['HUTANG'] = $this->hutang($ID_LHKPN);

        $data['PENERIMAAN'] = $this->penerimaan($ID_LHKPN);
        $data['PENGELUARAN'] = $this->pengeluaran($ID_LHKPN);
        $data['FASILITAS'] = $this->fasilitas($ID_LHKPN);

        return $data;
    }

    protected function __get_asal_usul_harta($au) {
        $asalusul = ['1' => 'HASIL SENDIRI', '2' => 'WARISAN', '3' => 'HIBAH DENGAN AKTA', '4' => 'HIBAH TANPA AKTA', '5' => 'HADIAH', '6' => 'LAINNYA'];
        $asal_usul = array();
        $asal_usul = explode(',', $au);
        if (count($asal_usul) > 0) {
            $banyak_asalusul = count($asal_usul);
            $i = 1;
            $asalusuls = '';
            foreach ($asal_usul as $row) {
                if (array_key_exists($row, $asalusul)) {
                    $pisah = ($banyak_asalusul === $i ? "" : ", ");
                    $asalusuls .= $asalusul[$row] . $pisah;
                    $i++;
                }
            }
        } else {
            echo "----";
        }
        return $asalusuls;
    }

    protected function __get_pemanfaatan_harta($pf) {
        $pemanfaatan = ['1' => 'TEMPAT TINGGAL', '2' => 'DISEWAKAN', '3' => 'PERTANIAN / PERKEBUNAN / PERIKANAN / PERTAMBANGAN', '4' => 'LAINNYA'];
        $manfaat = array();
        $manfaat = explode(',', $pf);
        if (count($manfaat) > 0) {
            $banyak = count($manfaat);
            $i = 1;
            $manfaats = '';
            foreach ($manfaat as $row) {
                if (array_key_exists($row, $pemanfaatan)) {
                    $pisah = ($banyak === $i ? "" : ", ");
                    $manfaats .= $pemanfaatan[$row] . $pisah;
                    $i++;
                }
            }
        } else {
            echo "----";
        }

        return $manfaats;
    }

    protected function __set_data_cetak_jabatan($data_jabatan) {

        $BIDANG = "-";
        $LEMBAGA = "-";
        $JABATAN = "-";
        $SUK = "-";
        $UK = "-";
        //echo count($data['JABATAN'], COUNT_RECURSIVE).'<br/><pre>';
        //print_r($data['JABATAN'][0]->BDG_NAMA);
        $jb = $data_jabatan;
        if ($data_jabatan) {
            if (count($data_jabatan) == '1') {
                $BIDANG = $jb[0]->BDG_NAMA;
                $LEMBAGA = $jb[0]->INST_NAMA;
                $JABATAN = $jb[0]->NAMA_JABATAN;
                $SUK = $jb[0]->SUK_NAMA;
                $UK = $jb[0]->UK_NAMA;
            } else {
                foreach ($data_jabatan as $jb) {
                    if ($jb->IS_PRIMARY == "1") {
                        $BIDANG = $jb->BDG_NAMA;
                        $LEMBAGA = $jb->INST_NAMA;
                        $JABATAN = $jb->NAMA_JABATAN;
                        $SUK = $jb->SUK_NAMA;
                        $UK = $jb->UK_NAMA;
                        //break;
                    }
                }
            }
        }

        return array($BIDANG, $LEMBAGA, $JABATAN, $SUK, $UK);
    }

    protected function __get_cetak_hubungan_keluarga($index_hub_keluarga, $lhkpn_ver = FALSE) {
        $HUBUNGAN_KELUARGA = array();
        if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11' || $lhkpn_ver == '2.1') {
            $HUBUNGAN_KELUARGA[1] = '-';
            $HUBUNGAN_KELUARGA[3] = 'ISTRI';
            $HUBUNGAN_KELUARGA[2] = 'SUAMI';
            $HUBUNGAN_KELUARGA[4] = 'ANAK TANGGUNGAN';
            $HUBUNGAN_KELUARGA[5] = 'ANAK BUKAN TANGGUNGAN';
            $HUBUNGAN_KELUARGA[6] = 'LAINNYA';
            $HUBUNGAN_KELUARGA[''] = '-';
            $HUBUNGAN_KELUARGA[0] = '-';
        } else {
            $HUBUNGAN_KELUARGA[1] = 'ISTRI';
            $HUBUNGAN_KELUARGA[2] = 'SUAMI';
            $HUBUNGAN_KELUARGA[3] = 'ANAK TANGGUNGAN';
            $HUBUNGAN_KELUARGA[4] = 'ANAK BUKAN TANGGUNGAN';
            $HUBUNGAN_KELUARGA[5] = 'LAINNYA';
            $HUBUNGAN_KELUARGA[''] = '-';
            $HUBUNGAN_KELUARGA[0] = '-';
        }

        return $HUBUNGAN_KELUARGA[$index_hub_keluarga];
    }
    
    protected function __get_cetak_jenis_kelamin($jenis_kelamin = FALSE) {
        if (map_jenis_kelamin_to_bin($jenis_kelamin, (is_numeric($jenis_kelamin) ? 'num' : 'txt')) == 1) {
            return "Laki-Laki";
        }
        if (map_jenis_kelamin_to_bin($jenis_kelamin, (is_numeric($jenis_kelamin) ? 'num' : 'txt')) == 0) {
            return "Perempuan";
        }
        return "";
    }

    protected function __get_cetak_atas_nama_v2($index_atas_nama,$pasangan_anak = false,$ket_lainnya = false){
        $get_atas_nama = $index_atas_nama;
        $atas_nama = '';
        $get_atas_nama = check_atas_nama($get_atas_nama);
        if(strstr($get_atas_nama, "5")){
            $atas_nama = substr($get_atas_nama,2);
        }
        if(strstr($get_atas_nama, "1")){
            $atas_nama = 'PN YANG BERSANGKUTAN';
        }
        if(strstr($get_atas_nama, "2")){

            $pasangan_array = explode(',', $pasangan_anak);
            $get_list_pasangan = '';
            $loop_first_pasangan = 0;
            foreach($pasangan_array as $ps){
                $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                if($loop_first_pasangan==0){
                    $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                }else{
                    $get_list_pasangan = $get_list_pasangan.', '.$data_pasangan_anak[0]['NAMA'];
                }
                $loop_first_pasangan++;
            }
            $show_pasangan = $get_list_pasangan;
            if($atas_nama==''){
                $atas_nama = $atas_nama.'PASANGAN/ANAK ('.$show_pasangan.')';
            }else{
                $atas_nama = $atas_nama.', PASANGAN/ANAK ('.$show_pasangan.')';
            }
        }
        if(strstr($get_atas_nama, "3")){
            if($atas_nama==''){
                $atas_nama = $atas_nama.'LAINNYA ('.$ket_lainnya.')';
            }else{
                $atas_nama = $atas_nama.', LAINNYA ('.$ket_lainnya.')' ;
            }
        }
        return $atas_nama;
    }

    protected function __get_cetak_keterangan($is_pelepasan, $jenis_pelepasan, $status_harta, $is_copy) {
        if ($is_pelepasan == '1') {
            $output = $jenis_pelepasan ? $jenis_pelepasan : 'LEPAS';
            return $output;
        }
        return $this->__get_cetak_status_harta($status_harta, $is_copy);
    }

    protected function __get_cetak_status_harta($index_status_harta, $is_copy) {
        $STATUS_HARTA = array();
        $STATUS_HARTA[1] = 'TETAP';
        $STATUS_HARTA[2] = 'UBAH';
        $STATUS_HARTA[3] = '-';
        $STATUS_HARTA[4] = 'LAPOR';
        $STATUS_HARTA[''] = '-';

        if ($this->__get_cetak_is_copy($is_copy)) {
            $STATUS_HARTA[3] = 'BARU';
        }

        return $STATUS_HARTA[$index_status_harta];
    }
    
    protected function __get_cetak_is_copy($is_copy) {
        return $is_copy == '1' ? TRUE : FALSE;
    }
    
    function lhkpn($ID_LHKPN) {
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);

        /**
         * jika bukan everifikasi (dalam hal ini adalah efill),
         * maka harus dibatasi berdasarkan ID_PN yang sedang Login
         */
        if (!$this->is_ever) {
            $this->db->where('t_lhkpn.ID_PN', $this->session->userdata('ID_PN'));
        }
        $data = $this->db->get('t_lhkpn')->row();
        return $data;
    }

    protected function _q_pribadi($ID_LHKPN, $table_name, $primary_key) {
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->group_by($table_name . '.' . $primary_key);
        $this->db->order_by($table_name . '.' . $primary_key, 'DESC');
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $this->db->join('m_negara', 'm_negara.KODE_ISO3 = ' . $table_name . '.KD_ISO3_NEGARA', 'LEFT');
        $this->db->join('m_area_prov', 'm_area_prov.NAME = ' . $table_name . '.PROVINSI', 'LEFT');
        $this->db->join('m_area_kab', 'm_area_kab.NAME_KAB = ' . $table_name . '.KABKOT', 'LEFT');
        $this->db->join($this->table_lhkpn, $this->table_lhkpn . '.' . $this->field_id_lhkpn . ' = ' . $table_name . '.' . $this->field_id_lhkpn);
        $this->db->join('t_pn', 't_pn.ID_PN = ' . $this->table_lhkpn . '.ID_PN');
    }

    public function _cnt_pribadi($ID_LHKPN, $table_name, $primary_key) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_pribadi();
        $this->db->select('count(' . $primary_key . ') as jumlah_data');
        $this->_q_pribadi($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    private function __conf_pribadi() {
        $table_name = 't_lhkpn_data_pribadi';
        $primary_key = 'ID';

        if ($this->is_validation) {
            $table_name = 't_imp_xl_lhkpn_data_pribadi';
            $primary_key = 'id_imp_xl_lhkpn_data_pribadi';
        }
        return array($table_name, $primary_key);
    }

    function pribadi($ID_LHKPN, $offset = 0, $limit = 0) {

        list($table_name, $primary_key) = $this->__conf_pribadi();
        $this->db->select($table_name . '.*,m_negara.KODE_ISO3,.m_negara.NAMA_NEGARA,m_area_prov.*,m_area_kab.*,t_pn.NHK');
        $this->_q_pribadi($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();
        return $data;
    }

    public function _cnt_keluarga($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_keluarga();
        $this->db->select('count(' . $primary_key . ') as jumlah_data');
        $this->_q_keluarga($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    protected function _q_keluarga($ID_LHKPN, $table_name, $primary_key) {
        $this->db->where($this->field_id_lhkpn, $ID_LHKPN);
    }

    private function __conf_keluarga() {
        $table_name = 't_lhkpn_keluarga';
        $primary_key = 'ID_KELUARGA';

        if ($this->is_validation) {
            $table_name = 't_imp_xl_lhkpn_keluarga';
            $primary_key = 'id_imp_xl_lhkpn_keluarga';
        }
        return array($table_name, $primary_key);
    }

    function keluarga($ID_LHKPN, $offset = 0, $limit = 0) {

        list($table_name, $primary_key) = $this->__conf_keluarga();

        $this->_q_keluarga($ID_LHKPN, $table_name, $primary_key);
        $this->db->limit($limit, $offset);
        $data = $this->db->get($table_name)->result();
        return $data;
    }

    public function _cnt_jabatan($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_jabatan();
        $this->db->select('count(' . $primary_key . ') as jumlah_data');
        $this->_q_jabatan($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    protected function _q_jabatan($ID_LHKPN, $table_name, $primary_key) {
        $this->db->where($this->field_id_lhkpn, $ID_LHKPN);
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = ' . $table_name . '.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = m_jabatan.UK_ID', 'left');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_ID = m_jabatan.SUK_ID', 'left');
        $this->db->join('m_bidang', ',m_bidang.BDG_ID = m_inst_satker.INST_BDG_ID', 'left');
        $this->db->group_by($table_name . '.' . $primary_key);
    }

    private function __conf_jabatan() {
        $table_name = 't_lhkpn_jabatan';
        $primary_key = 'ID';

        if ($this->is_validation) {
            $table_name = 't_imp_xl_lhkpn_jabatan';
            $primary_key = 'id_imp_xl_lhkpn_jabatan';
        }
        return array($table_name, $primary_key);
    }

    function jabatan($ID_LHKPN, $offset = 0, $limit = 0) {

        list($table_name, $primary_key) = $this->__conf_jabatan();

        $this->_q_jabatan($ID_LHKPN, $table_name, $primary_key);
        $this->db->limit($limit, $offset);
        $data = $this->db->get($table_name)->result();
        return $data;
    }

    public function _cnt_harta_tidak_bergerak($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_harta_tidak_bergerak();
        $this->db->select('count('.$table_name.'.' . $primary_key . ') as jumlah_data');
        $this->_q_harta_tidak_bergerak($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    protected function _q_harta_tidak_bergerak($ID_LHKPN, $table_name, $primary_key, $prefix) {
        $PK = $primary_key;
        $TABLE = 't_' . $prefix . 'lhkpn_harta_tidak_bergerak';
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
        $this->db->join('m_negara', 'm_negara.ID = t_' . $prefix . 'lhkpn_harta_tidak_bergerak.ID_NEGARA', 'left');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
    }

    private function __conf_harta_tidak_bergerak() {
        $table_name = 't_lhkpn_harta_tidak_bergerak';
        $primary_key = 'ID';
        $prefix = "";

        if ($this->is_validation) {
            $table_name = 't_imp_xl_lhkpn_harta_tidak_bergerak';
            $primary_key = 'id_imp_xl_lhkpn_harta_tidak_bergerak';
            $prefix = "imp_xl_";
        }
        return array($table_name, $primary_key, $prefix);
    }

    function harta_tidak_bergerak($ID_LHKPN, $offset = 0, $limit = 0) {

        list($table_name, $primary_key, $prefix) = $this->__conf_harta_tidak_bergerak();

        $this->db->select('
            m_jenis_bukti.*,
            m_mata_uang.*,
            m_negara.NAMA_NEGARA,
            ' . $table_name . '.*,
            ' . $table_name . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $table_name . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $table_name . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            (SELECT NILAI_PELAPORAN FROM ' . $table_name . ' p WHERE p.ID = ' . $table_name . '.Previous_ID) AS NILAI_LAMA,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $table_name . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (' . $table_name . '.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
            m_jenis_bukti.JENIS_BUKTI AS JENIS_BUKTI_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_' . $prefix . 'lhkpn_pelepasan_harta_tidak_bergerak WHERE t_' . $prefix . 'lhkpn_pelepasan_harta_tidak_bergerak.ID_HARTA = ' . $table_name . '.' . $primary_key . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN
        ');

        $this->_q_harta_tidak_bergerak($ID_LHKPN, $table_name, $primary_key, $prefix);
        $this->db->limit($limit, $offset);
        $data = $this->db->get('t_' . $prefix . 'lhkpn_harta_tidak_bergerak')->result();
        return $data;
    }


    public function _cnt_harta_bergerak($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_harta_bergerak();
        $this->db->select('count('.$table_name.'.' . $primary_key . ') as jumlah_data');
        $this->_q_harta_bergerak($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    public function _cnt_nilai_pelaporan_harta_bergerak($ID_LHKPN) {
        $this->jumlah_hb = 0;
        $table_name = 't_lhkpn_harta_bergerak';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_hb');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_hb = $data->jumlah_hb;
        }
        return $data->jumlah_hb;
    }

    public function _cnt_nilai_pelaporan_harta_tidak_bergerak($ID_LHKPN) {
        $this->jumlah_htb = 0;
        $table_name = 't_lhkpn_harta_tidak_bergerak';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_htb');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_htb = $data->jumlah_htb;
        }
        return $data->jumlah_htb;
    }

    public function _cnt_nilai_pelaporan_harta_bergerak_lain($ID_LHKPN) {
        $this->jumlah_hbl = 0;
        $table_name = 't_lhkpn_harta_bergerak_lain';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_hbl');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_hbl = $data->jumlah_hbl;
        }
        return $data->jumlah_hbl;
    }

    public function _cnt_nilai_pelaporan_harta_surat_berharga($ID_LHKPN) {
        $this->jumlah_sb = 0;
        $table_name = 't_lhkpn_harta_surat_berharga';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_sb');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_sb = $data->jumlah_sb;
        }
        return $data->jumlah_sb;
    }

    public function _cnt_nilai_pelaporan_harta_kas($ID_LHKPN, $is_klarif = 0) {
        $this->jumlah_kas = 0;
        $table_name = 't_lhkpn_harta_kas';
        $primary_key_1 = 'NILAI_EQUIVALEN';

        if($is_klarif){
            $this->db->select('SUM('.$table_name.'.' . $primary_key_1 . ') as jumlah_kas');
            $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
            $this->db->where($table_name . '.IS_ACTIVE', '1');
            $this->db->where($table_name . '.MATA_UANG', '1');
            $this->db->where($table_name . '.IS_PELEPASAN <>', '1');
            $data_1 = $this->db->get($table_name)->row()->jumlah_kas;

            $primary_key_2 = 'NILAI_EQUIVALEN_OLD';
            $this->db->select('SUM('.$table_name.'.' . $primary_key_2 . ') as jumlah_kas');
            $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
            $this->db->where($table_name . '.IS_ACTIVE', '1');
            $this->db->where($table_name . '.MATA_UANG <>', '1');
            $this->db->where($table_name . '.IS_PELEPASAN <>', '1');
            $data_2 = $this->db->get($table_name)->row()->jumlah_kas;

            $total = 0;
            if ($data_1 || $data_2) {

                if(!is_null($data_1) && !is_null($data_2)){ 
                    $total = $data_1 + $data_2;
                }else if(!is_null($data_1) && is_null($data_2)){
                    $total = $data_1;
                }else if(!is_null($data_2) && is_null($data_1)){
                    $total = $data_2;
                }
            }
        }else{
            $this->db->select('SUM('.$table_name.'.' . $primary_key_1 . ') as jumlah_kas');
            $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
            $this->db->where($table_name . '.IS_ACTIVE', '1');
            $data = $this->db->get($table_name)->row()->jumlah_kas;
        }

        return $this->jumlah_kas = $is_klarif?$total:$data;
    }

    public function _cnt_nilai_pelaporan_harta_lainnya($ID_LHKPN) {
        $this->jumlah_hl = 0;
        $table_name = 't_lhkpn_harta_lainnya';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_hl');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_hl = $data->jumlah_hl;
        }
        return $data->jumlah_hl;
    }

    public function _cnt_nilai_pelaporan_harta_hutang($ID_LHKPN) {
        $this->jumlah_shtg = 0;
        $this->jumlah_ahtg = 0;
        $table_name = 't_lhkpn_hutang';
        $saldo_hutang = 'SALDO_HUTANG';
        $awal_hutang = 'AWAL_HUTANG';
        $this->db->select('SUM('.$table_name.'.' . $saldo_hutang . ') as jumlah_shtg, SUM('.$table_name.'.' . $awal_hutang . ') as jumlah_ahtg');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_shtg = $data->jumlah_shtg;
            $this->jumlah_ahtg = $data->jumlah_ahtg;
        }
        return $data;
    }

    public function _cnt_penerimaan_gaji($ID_LHKPN) {
        $null = 'NULL';
        $this->jumlah_pg_pn = 0;
        $this->jumlah_pg_ps = 0;
        $table_name = 't_lhkpn_penerimaan_kas2';
        $pn = 'PN';
        $ps = 'PASANGAN';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.'.$pn.') AS jumlah_pg_pn, SUM('.$table_name.'.'.$ps.') AS jumlah_pg_ps',false);
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'A');
        $data = $this->db->get($table_name)->row();
        if ($data) {
            $this->jumlah_pg_pn = $data->jumlah_pg_pn;
            $this->jumlah_pg_ps = $data->jumlah_pg_ps;
        }
        return $data;
    }

    public function _cnt_penerimaan_usaha($ID_LHKPN) {
        $this->jumlah_pu_pn = 0;
        $table_name = 't_lhkpn_penerimaan_kas2';
        $pn = 'PN';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_pu_pn');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'B');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_pu_pn = $data->jumlah_pu_pn;
        }
        return $data;
    }

    public function _cnt_penerimaan_lainnya($ID_LHKPN) {
        $this->jumlah_pl_pn = 0;
        $table_name = 't_lhkpn_penerimaan_kas2';
        $pn = 'PN';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_pl_pn');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'C');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_pl_pn = $data->jumlah_pl_pn;
        }
        return $data;
    }

    public function _cnt_pengeluaran_rutin($ID_LHKPN) {
        $this->jumlah_pr = 0;
        $table_name = 't_lhkpn_pengeluaran_kas2';
        $pn = 'JML';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_pr');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'A');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_pr = $data->jumlah_pr;
        }
        return $data;
    }

    public function _cnt_pengeluaran_harta($ID_LHKPN) {
        $this->jumlah_ph = 0;
        $table_name = 't_lhkpn_pengeluaran_kas2';
        $pn = 'JML';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_ph');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'B');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_ph = $data->jumlah_ph;
        }
        return $data;
    }

    public function _cnt_pengeluaran_lainnya($ID_LHKPN) {
        $this->jumlah_pl = 0;
        $table_name = 't_lhkpn_pengeluaran_kas2';
        $pn = 'JML';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_pl');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'C');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_pl = $data->jumlah_pl;
        }
        return $data;
    }

    protected function _q_harta_bergerak($ID_LHKPN, $table_name, $primary_key, $prefix) {
        $PK = $primary_key;
        $TABLE = 't_' . $prefix . 'lhkpn_harta_bergerak';
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
    }

    private function __conf_harta_bergerak() {
        $primary_key = 'ID';
        $prefix = "";
        $PK = 'ID';

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_bergerak';
            $prefix = "imp_xl_";
        }
        return array($table_name, $primary_key, $prefix);
    }

    public function _cnt_harta_bergerak_lain($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_harta_bergerak_lain();
        $this->db->select('count('.$table_name.'.' . $primary_key . ') as jumlah_data');
        $this->_q_harta_bergerak_lain($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    protected function _q_harta_bergerak_lain($ID_LHKPN, $table_name, $primary_key, $prefix) {
        $PK = $primary_key;
        $TABLE = 't_' . $prefix . 'lhkpn_harta_bergerak';
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
    }

    private function __conf_harta_bergerak_lain() {
        $primary_key = 'ID';
        $prefix = "";
        $PK = 'ID';

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_bergerak';
            $prefix = "imp_xl_";
        }
        return array($table_name, $primary_key, $prefix);
    }

    function harta_bergerak($ID_LHKPN, $offset = 0, $limit = 0) {
        list($table_name, $primary_key, $prefix) = $this->__conf_harta_bergerak();
        $TABLE = 't_' . $prefix . 'lhkpn_harta_bergerak';
        $this->db->select('
            m_jenis_harta.*,
            m_jenis_bukti.*,
            m_jenis_bukti.JENIS_BUKTI AS N_JENIS_BUKTI,
            ' . $TABLE . '.*,
            m_jenis_harta.NAMA AS JENIS_HARTA,
            ' . $TABLE . '.STATUS as STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            (SELECT NILAI_PELAPORAN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (' . $TABLE . '.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_' . $prefix . 'lhkpn_pelepasan_harta_bergerak WHERE t_' . $prefix . 'lhkpn_pelepasan_harta_bergerak.ID_HARTA = ' . $TABLE . '.' . $primary_key . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN
        ');
        $this->_q_harta_bergerak($ID_LHKPN, $table_name, $primary_key, $prefix);
        $this->db->limit($limit, $offset);
        $data = $this->db->get($TABLE)->result();
        // echo $this->db->last_query();exit;
        return $data;
    }

    function harta_bergerak_lain($ID_LHKPN) {
        $primary_key = 'ID';
        $prefix = "";
        $PK = 'ID';

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_bergerak_lain';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_harta_bergerak_lain';
        $this->db->select('
            m_jenis_harta.*,
            ' . $TABLE . '.*,
            ' . $TABLE . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            m_jenis_harta.NAMA as JENIS_HARTA,
            (SELECT NILAI_PELAPORAN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (' . $TABLE . '.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_bergerak_lain WHERE t_lhkpn_pelepasan_harta_bergerak_lain.ID_HARTA = ' . $TABLE . '.' . $PK . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function harta_surat_berharga($ID_LHKPN) {

        $PK = 'ID';

        $primary_key = 'ID';
        $prefix = "";

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_surat_berharga';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_harta_surat_berharga';

        $this->db->select('
            m_jenis_harta.*,
            ' . $TABLE . '.*,
            ' . $TABLE . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            (SELECT NILAI_PELAPORAN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_surat_berharga WHERE t_lhkpn_pelepasan_harta_surat_berharga.ID_HARTA = ' . $TABLE . '.' . $PK . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function harta_lainnya($ID_LHKPN) {

        $PK = 'ID';

        $primary_key = 'ID';
        $prefix = "";

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_lainnya';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_harta_lainnya';

        $this->db->select('
            m_jenis_harta.*,
            ' . $TABLE . '.*,
            ' . $TABLE . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            m_jenis_harta.NAMA AS NAMA_JENIS,
            (SELECT NILAI_PELAPORAN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_lainnya WHERE t_lhkpn_pelepasan_harta_lainnya.ID_HARTA = ' . $TABLE . '.' . $PK . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN ) AS JENIS_PELEPASAN
        ');
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function harta_kas($ID_LHKPN) {
        $PK = 'ID';
        $primary_key = 'ID';
        $prefix = "";

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_kas';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_harta_kas';

        $this->db->select('
            m_jenis_harta.*,
            m_mata_uang.*,
            ' . $TABLE . '.*,
            ' . $TABLE . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_SALDO`
                END `NILAI_SALDO`,
            (SELECT NILAI_EQUIVALEN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_kas WHERE t_lhkpn_pelepasan_harta_kas.ID_HARTA = ' . $TABLE . '.' . $PK . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function hutang($ID_LHKPN) {

        $PK = 'ID_HUTANG';

        $primary_key = 'ID_HUTANG';
        $prefix = "";

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_hutang';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_hutang';

        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_hutang', 'm_jenis_hutang.ID_JENIS_HUTANG = ' . $TABLE . '.KODE_JENIS');
        $this->db->join($this->table_lhkpn, $this->table_lhkpn . '.' . $this->field_id_lhkpn . ' = ' . $TABLE . '.' . $this->field_id_lhkpn);
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function penerimaan($ID_LHKPN) {
        $prefix = "";

        if ($this->is_validation) {

            $this->load->model('mglobal');

            $data_detail = $this->mglobal->secure_get_by_id("t_imp_xl_lhkpn_penerimaan_kas", "id_imp_xl_lhkpn", "id_imp_xl_lhkpn_penerimaan_kas", make_secure_text($ID_LHKPN));

            $pn = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PN) ? $data_detail->NILAI_PENERIMAAN_KAS_PN : "{}");
            $pa = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PASANGAN) ? $data_detail->NILAI_PENERIMAAN_KAS_PASANGAN : "{}");

            $jenis_penerimaan_kas_pn = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
            $golongan_penerimaan_kas_pn = $this->config->item('golongan_penerimaan_kas_pn', 'harta');

            $label = array('A', 'B', 'C');

            $data_arr = array();

            $k = 0;
            for ($i = 0; $i < count($jenis_penerimaan_kas_pn); $i++) {
                for ($j = 0; $j < count($jenis_penerimaan_kas_pn[$i]); $j++) {
                    $PA_val = 'PA' . $j;
                    $code = $label[$i] . $j;

                    $data_arr[$k] = array(
                        "ID_LHKPN" => $id_lhkpn,
                        "GROUP_JENIS" => $label[$i],
                        "KODE_JENIS" => $code,
                        "JENIS_PENERIMAAN" => $jenis_penerimaan_kas_pn[$i][$j],
                        "PN" => 0,
                        "PASANGAN" => 0,
                    );
                    if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
                        $data_arr[$k]["PN"] = $pn->{$label[$i]}[$j]->$code;
                    }

                    if ($i == 0) {
                        if (is_array($pa) && !empty($pa) && property_exists($pa[$j], $PA_val)) {
                            $data_arr[$k]["PASANGAN"] = $pa[$j]->{$PA_val};
                        }
                    }

                    $k++;
                }
            }
            unset($pn, $pa, $jenis_penerimaan_kas_pn);
            return $data_arr;

            $prefix = "imp_xl_";
        }

        $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_' . $prefix . 'lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
        $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
        $this->db->where('t_' . $prefix . 'lhkpn_penerimaan_kas2.' . $this->field_id_lhkpn, $ID_LHKPN);
        $data = $this->db->get('t_' . $prefix . 'lhkpn_penerimaan_kas2')->result();
        return $data;
    }

    function pengeluaran($ID_LHKPN) {

        $prefix = "";

        if ($this->is_validation) {

            $this->load->model('mglobal');

            $data_detail = $this->mglobal->secure_get_by_id("t_imp_xl_lhkpn_pengeluaran_kas", "id_imp_xl_lhkpn", "id_imp_xl_lhkpn_pengeluaran_kas", make_secure_text($ID_LHKPN));

            $pn = json_decode(!is_null($data_detail->NILAI_PENGELUARAN_KAS) ? $data_detail->NILAI_PENGELUARAN_KAS : "{}");

            $jenis_pengeluaran_kas_pn = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
            $label = array('A', 'B', 'C');

            $data_arr = array();

            $k = 0;
            for ($i = 0; $i < count($jenis_pengeluaran_kas_pn); $i++) {
                for ($j = 0; $j < count($jenis_pengeluaran_kas_pn[$i]); $j++) {
                    $code = $label[$i] . $j;

                    $data_arr[$k] = array(
                        "ID_LHKPN" => $ID_LHKPN,
                        "GROUP_JENIS" => $label[$i],
                        "KODE_JENIS" => $code,
                        "JENIS_PENGELUARAN" => $jenis_pengeluaran_kas_pn[$i][$j],
                        "JML" => 0,
                    );

                    if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
                        $data_arr[$k]["JML"] = $pn->{$label[$i]}[$j]->$code;
                    }
                    $k++;
                }
            }
            // unset($pn, $jenis_pengeluaran_kas_pn);
            return $data_arr;

            $prefix = "imp_xl_";
        }

        $this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_' . $prefix . 'lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
        $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
        $this->db->where('t_' . $prefix . 'lhkpn_pengeluaran_kas2.' . $this->field_id_lhkpn, $ID_LHKPN);
        $data = $this->db->get('t_' . $prefix . 'lhkpn_pengeluaran_kas2')->result();
        return $data;
    }

    function fasilitas($ID_LHKPN) {

        $prefix = "";

        if ($this->is_validation) {
            $prefix = "imp_xl_";
        }

        $this->db->where($this->field_id_lhkpn, $ID_LHKPN);
        $data = $this->db->get('t_' . $prefix . 'lhkpn_fasilitas')->result();
        return $data;
    }

    protected function write_table_data_keluarga($data_keluarga, $lhkpn_ver = FALSE){
        $put_data_row = "";
        $no = 1;
        foreach ($data_keluarga as $key => $fam) {

            $tgl_lahir = null;
            if ($fam->TANGGAL_LAHIR) {
                $tgl_lahir = tgl_format($fam->TANGGAL_LAHIR);
            }
            $jenis_kelamin = $this->is_ever ? $this->__is_cetak_var_not_blank($this->__get_cetak_jenis_kelamin($fam->JENIS_KELAMIN), "-") : $this->__is_cetak_var_not_blank($fam->JENIS_KELAMIN, "-");
            $ttl_fam = $this->__is_cetak_var_not_blank($fam->TEMPAT_LAHIR, "-") . ' , ' . $tgl_lahir . ' / ' . $jenis_kelamin;
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537" /> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="535" w:type="dxa" /><w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00935947" w:rsidRDefault="00246808" w:rsidP="0038393B"><w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> </w:pPr> <w:r> <w:t>'.$no++ .'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2692" w:type="dxa" /><w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00935947" w:rsidRDefault="00246808" w:rsidP="0038393B"><w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($fam->NAMA, "-").'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1959" w:type="dxa" /><w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00935947" w:rsidRDefault="00246808" w:rsidP="0038393B"><w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_hubungan_keluarga($fam->HUBUNGAN, $lhkpn_ver).'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3416" w:type="dxa" /> <w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00935947" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr><w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$ttl_fam.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2232" w:type="dxa" /><w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00926FED" w:rsidRDefault="00246808" w:rsidP="0038393B"><w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($fam->PEKERJAAN, "-").'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="4520" w:type="dxa" /> <w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00926FED" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr><w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($fam->ALAMAT_RUMAH, "-").'</w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat header dan masukan data yg telah dibuat
        $data = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>DATA KELUARGA</w:t></w:r> </w:p> <w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="0" w:type="auto"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders><w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="535"/> <w:gridCol w:w="2692"/> <w:gridCol w:w="1959"/> <w:gridCol w:w="3416"/> <w:gridCol w:w="2232"/> <w:gridCol w:w="4520"/> </w:tblGrid><w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr><w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="535" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2692" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders><w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr><w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NAMA</w:t> </w:r> </w:p> </w:tc> <w:tc><w:tcPr> <w:tcW w:w="1959" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40" /> <w:vAlign w:val="center" /> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>HUBUNGAN DENGAN PN</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3416" w:type="dxa" /> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40" /> <w:vAlign w:val="center" /> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>TEMPAT DAN TANGGAL LAHIR / JENIS KELAMIN</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2232" w:type="dxa" /> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40" /> <w:vAlign w:val="center" /> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>PEKERJAAN</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="4520" w:type="dxa" /> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40" /> <w:vAlign w:val="center" /> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>ALAMAT RUMAH</w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.'</w:tbl>';
       return $data;
    }

    protected function write_table_data_jabatan($jabatan) {
        $put_data_row = "";
        $no = 1;
        foreach ($jabatan as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="534" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00397078" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'. $no++ .'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="4057" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00894B24" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_JABATAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3455" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00AC1554" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->INST_NAMA, "-").'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3686" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00174823" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->UK_NAMA, "-").'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3622" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DF2C40" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->SUK_NAMA, "-").'</w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat header dan masukan data yg telah dibuat
        $data = '<w:p w:rsidR="00246808" w:rsidRPr="00FD1E82" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:spacing w:after="0" w:line="240" w:lineRule="auto" /> <w:ind w:left="360" /> <w:rPr> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> </w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:numPr> <w:ilvl w:val="0" /> <w:numId w:val="1" /> </w:numPr> <w:spacing w:after="0" w:line="240" w:lineRule="auto" /> <w:ind w:left="360" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00FD1E82"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>JABATAN </w:t> </w:r> </w:p> <w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid" /> <w:tblW w:w="0" w:type="auto" /> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto" /> </w:tblBorders> <w:tblLayout w:type="fixed" /> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1" /> </w:tblPr> <w:tblGrid> <w:gridCol w:w="534" /> <w:gridCol w:w="4057" /> <w:gridCol w:w="3455" /> <w:gridCol w:w="3686" /> <w:gridCol w:w="3622" /> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537" /> <w:tblHeader /> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="535" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO</w:t> </w:r> </w:p> </w:tc>  <w:tc> <w:tcPr> <w:tcW w:w="4057" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>J </w:t> </w:r> <w:r w:rsidRPr="008E115A"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ABATAN - DESKRIPSI JABATAN / </w:t> </w:r> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve"> </w:t> </w:r> <w:r w:rsidRPr="008E115A"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ESELON </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3455" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>LEMBAGA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3686" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>UNIT KERJA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3622" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>SUB UNIT KERJA </w:t> </w:r> </w:p> </w:tc> </w:tr> '.$put_data_row.' </w:tbl>';
        return $data;
    }

    protected function write_table_data_htb($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>DATA HARTA</w:t></w:r> </w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>TANAH / BANGUNAN</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00266A7F" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00266A7F" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Jalan / No </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JALAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Kel. / Desa </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KEL, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Kecamatan </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KEC, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Kab. / Kota </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KAB_KOT, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Prov. / Negara </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->PROV, "-") . ' / ' . $this->__is_cetak_var_not_blank($row->NAMA_NEGARA, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="600" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tanah: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank(number_format($row->LUAS_TANAH, 0, ",", "."), "-").' m </w:t> </w:r> <w:r> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> <w:t>2 </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Bangunan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank(number_format($row->LUAS_BANGUNAN, 0, ",", "."), "-").' m </w:t> </w:r> <w:r> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> <w:t>2 </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1061" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis Bukti: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JENIS_BUKTI_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Nomor Bukti: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NOMOR_BUKTI, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Atas Nama: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Asal Usul Harta: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Pemanfaatan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_pemanfaatan_harta($row->PEMANFAATAN), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'. $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2989" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="005E7E13" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="501"/> <w:gridCol w:w="3511"/> <w:gridCol w:w="1813"/> <w:gridCol w:w="3206"/> <w:gridCol w:w="2372"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>LOKASI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="600" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>LUAS </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1061" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KEPEMILIKAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr> '.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_hb($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>ALAT TRANSPORTASI / MESIN</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="339" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++ .' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1430" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00346CCA" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="000F5A47" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Merk : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->MEREK, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="0014742E" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Model : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->MODEL, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="0014742E" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Pembuatan : </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->TAHUN_PEMBUATAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00DB454A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">No. Pol. / Registrasi : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NOPOL_REGISTRASI, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1220" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="001F7877" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis Bukti: </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->N_JENIS_BUKTI, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00C85B29" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Asal Usul Harta: '.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00AC21C6" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Atas Nama: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00A0008A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Pemanfaatan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->PEMANFAATAN_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="002D47FA" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Lainnya: </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->KET_LAINNYA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="001A5C3A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003913DA" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="004D4569" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2990" w:type="pct"/> <w:gridSpan w:val="3"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1024"/> <w:gridCol w:w="4321"/> <w:gridCol w:w="3689"/> <w:gridCol w:w="2369"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="339" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1430" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1220" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KEPEMILIKAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_hbl($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>HARTA BERGERAK LAINNYA</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="355" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++ .' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1414" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="004D52F0" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="001B323A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jumlah : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JUMLAH, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="004D3AD2" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Satuan : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->SATUAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="002706F6" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Ket. Lainnya : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1221" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00E11B19" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00AD40F9" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="008C7439" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="0076399D" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2990" w:type="pct"/> <w:gridSpan w:val="3"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1072"/> <w:gridCol w:w="4273"/> <w:gridCol w:w="3689"/> <w:gridCol w:w="2369"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="355" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1414" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1221" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_sb($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>SURAT BERHARGA</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            if($row->NAMA){
                $jenis_surat = $row->NAMA;
            }else{
                $jenis_surat = "-";
            }
            if (strlen($row->NOMOR_REKENING) >= 32){
                $decrypt_norek = encrypt_username($row->NOMOR_REKENING,'d');
            } else {
                $decrypt_norek = $row->NOMOR_REKENING;
            }
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00FA4860" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++ .' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="004D52F0" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'.$jenis_surat.' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="001B323A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve"> : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Penerbit / Perusahaan </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve"> : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_PENERBIT, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="0086543E" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Cutodian / Sekuritas : '.$this->__is_cetak_var_not_blank($row->CUSTODIAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="692" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB6885" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$this->__is_cetak_var_not_blank($decrypt_norek, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="969" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="001E31B4" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00A15F40" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00D46384" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="006A08B0" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2989" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="005E7E13" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="005E7E13" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="501"/> <w:gridCol w:w="3511"/> <w:gridCol w:w="2091"/> <w:gridCol w:w="2928"/> <w:gridCol w:w="2372"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="692" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">NO. REKENING / </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO. NASABAH </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="969" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_kas($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>KAS / SETARA KAS</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            // with keterangan
            // $put_data = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="394" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1379" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00F5660E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->NAMA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="0078573A" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Keterangan : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank(trim($row->KETERANGAN), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="006E4B65" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Nama Bank / Lembaga : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_BANK, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1216" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Nomor : '.$this->__is_cetak_var_not_blank($row->NOMOR_REKENING, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama : '.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA_REKENING,$row->PASANGAN_ANAK,$row->ATAS_NAMA_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="00505AAB" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Keterangan : '.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00692894" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00C91A00" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_EQUIVALEN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009A0D8D" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            if (strlen($row->NAMA_BANK) >= 32){
                $decrypt_namabank = encrypt_username($row->NAMA_BANK,'d');
            } else {
                $decrypt_namabank = $row->NAMA_BANK;
            }
            if (strlen($row->NOMOR_REKENING) >= 32){
                $decrypt_norek = encrypt_username($row->NOMOR_REKENING,'d');
            } else {
                $decrypt_norek = $row->NOMOR_REKENING;
            }
            //without keterangan
            $put_data = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="394" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1379" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00F5660E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->NAMA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="0078573A" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Keterangan : </w:t> </w:r> <w:r> <w:t>-</w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="006E4B65" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Nama Bank / Lembaga : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($decrypt_namabank, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1216" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Nomor : '.$this->__is_cetak_var_not_blank($decrypt_norek, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama : '.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA_REKENING,$row->PASANGAN_ANAK,$row->ATAS_NAMA_LAINNYA), "-").' </w:t> </w:r> </w:p></w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00692894" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00C91A00" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_EQUIVALEN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009A0D8D" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="3774" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1210"/> <w:gridCol w:w="4235"/> <w:gridCol w:w="3734"/> <w:gridCol w:w="2411"/> <w:gridCol w:w="2269"/> <w:gridCol w:w="1495"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="394" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1379" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1216" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>INFORMASI REKENING </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_hl($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>HARTA LAINNYA</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="317" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00165890" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1451" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Jenis : '.$this->__is_cetak_var_not_blank($row->NAMA_JENIS, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="00D77743" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Keterangan : '.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1222" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009D77F3" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="007C1ED1" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="004E42E8" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="000A58A6" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2990" w:type="pct"/> <w:gridSpan w:val="3"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="973"/> <w:gridCol w:w="4456"/> <w:gridCol w:w="3753"/> <w:gridCol w:w="2408"/> <w:gridCol w:w="2269"/> <w:gridCol w:w="1495"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="317" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1451" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1222" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_htg($data, $is_copy,$total,$saldo) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>HUTANG</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B25150" w:rsidTr="009927CC"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="344" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00326241" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1246" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Jenis : '.$this->__is_cetak_var_not_blank($row->NAMA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00B25150" w:rsidRPr="008F01DB" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama : '.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1123" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00C70782" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_KREDITUR, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="800" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00E06AF6" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->AGUNAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="755" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00352172" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->AWAL_HUTANG).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="732" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="002F3325" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->SALDO_HUTANG).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="3513" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="755" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="732" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00636F13" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$saldo.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1056"/> <w:gridCol w:w="3826"/> <w:gridCol w:w="3449"/> <w:gridCol w:w="2457"/> <w:gridCol w:w="2318"/> <w:gridCol w:w="2248"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="344" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1246" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1123" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NAMA KREDITUR </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="800" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>BENTUK AGUNAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="755" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI AWAL HUTANG </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="732" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI SALDO HUTANG </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_penerimaan_pekerjaan($data, $is_copy, $total_pn, $total_ps) {
        $PN_A = array();
        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'A') {
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                    "PASANGAN" => $PM->PASANGAN,
                ];
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN</w:t></w:r> </w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN DARI PEKERJAAN</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="337" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1968" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="000A5177" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row["JENIS_PENERIMAAN"].' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="005609C7" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="005609C7" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PASANGAN"]).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2305" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total_pn.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00636F13" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total_ps.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1036"/> <w:gridCol w:w="6044"/> <w:gridCol w:w="4082"/> <w:gridCol w:w="4192"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="337" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1968" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENERIMAAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1329" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>PENYELENGGARA NEGARA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>PASANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_penerimaan_usaha($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'B') {
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                ];
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN DARI USAHA DAN KEKAYAAN</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:t>'.$row["JENIS_PENERIMAAN"].' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00391C63" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1045"/> <w:gridCol w:w="6927"/> <w:gridCol w:w="7382"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENERIMAAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL PENERIMAAN KAS </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_penerimaan_lainnya($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'C') {
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                ];
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN LAINNYA</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:t>'.$row["JENIS_PENERIMAAN"].' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00391C63" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1045"/> <w:gridCol w:w="6927"/> <w:gridCol w:w="7382"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENERIMAAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL PENERIMAAN KAS </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }


    protected function write_table_data_pengeluaran_rutin($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PNG) {
            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }
            if ($PNG->GROUP_JENIS == 'A') {
                $PN_A[] = $PNG;
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN</w:t></w:r> </w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN RUTIN</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row->JENIS_PENGELUARAN.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->JML).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1121"/> <w:gridCol w:w="6888"/> <w:gridCol w:w="7345"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:lastRenderedPageBreak/> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENGELUARAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL NILAI PENGELUARAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_pengeluaran_harta($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PNG) {
            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }
            if ($PNG->GROUP_JENIS == 'B') {
                $PN_A[] = $PNG;
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN HARTA</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row->JENIS_PENGELUARAN.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->JML).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1121"/> <w:gridCol w:w="6888"/> <w:gridCol w:w="7345"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:lastRenderedPageBreak/> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENGELUARAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL NILAI PENGELUARAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_pengeluaran_lainnya($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PNG) {
            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }
            if ($PNG->GROUP_JENIS == 'C') {
                $PN_A[] = $PNG;
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN LAINNYA</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row->JENIS_PENGELUARAN.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->JML).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1121"/> <w:gridCol w:w="6888"/> <w:gridCol w:w="7345"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:lastRenderedPageBreak/> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENGELUARAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL NILAI PENGELUARAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_lampiran_fasilitas($data, $is_copy) {
        $put_data_row = "";
        $no = 1;
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>LAMPIRAN FASILITAS</w:t></w:r> </w:p>';
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="006A0D67" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Jenis : '.$this->__is_cetak_var_not_blank($row->JENIS_FASILITAS, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00B56D76" w:rsidRPr="0008004D" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Keterangan : '.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00C83E31" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->PEMBERI_FASILITAS, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="0049016F" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1011"/> <w:gridCol w:w="4599"/> <w:gridCol w:w="4975"/> <w:gridCol w:w="4769"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NAMA PIHAK PEMBERI FASILITAS </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_total_harta($total_harta, $total_hutang,$total_harta_kekayaan) {
        $put_data_row = "";
        $no = 1;
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>TOTAL HARTA KEKAYAAN</w:t></w:r> </w:p>';
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $put_data_row = '<w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> <w:t>1 </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="00C07D7C" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> <w:t>'.$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_harta), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="00C07D7C" w:rsidP="00C07D7C"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> <w:t>'.$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_hutang), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="00C07D7C" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> <w:t>'.$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_harta_kekayaan), "-").' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1010"/> <w:gridCol w:w="4600"/> <w:gridCol w:w="4975"/> <w:gridCol w:w="4769"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> <w:t>TOTAL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> <w:t>TOTAL HUTANG </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> <w:t>TOTAL HARTA KEKAYAAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.' </w:tbl>';
        return $data_output;
    }

    private function __send_session_kirim_lhkpn($ID_LHKPN, $sess_template_data, $lampiran_3 = FALSE) {
        /**
         * check kemudian remove session terlebih dahulu
         * dengan maksud agar tidak tumpang tindih
         */
        if ($this->session->userdata('sess_kirim_lhkpn') && $lampiran_3) {
            $this->session->unset_userdata('sess_kirim_lhkpn');

            $this->session->set_userdata('sess_kirim_lhkpn', (object) $this->sess_template_kirim_lhkpn);
        }


        $sess_kirim_lhkpn = $this->session->userdata('sess_kirim_lhkpn');
        $sess_kirim_lhkpn->data[] = $sess_template_data;
        $sess_kirim_lhkpn->id_lhkpn = $ID_LHKPN;

        $this->session->set_userdata('sess_kirim_lhkpn', $sess_kirim_lhkpn);

        unset($sess_kirim_lhkpn);
    }

    //////////////////// end cetak ikhtisar //////////////////////


    protected function bypass_penugasan($ID_LHKPN, $tahun_verif){

        $penugasan = array(
            'ID_LHKPN' => $ID_LHKPN,
            'USERNAME' => 'system<'.$tahun_verif.'>',
            'TANGGAL_PENUGASAN' => date('Y-m-d'),
            'DUE_DATE' => date('Y-m-d'),
            'STAT' => 2,
            'IS_ACTIVE' => 1,
            'UPDATED_TIME' => time(),
            'UPDATED_BY' => 'system<'.$tahun_verif.'>',
            'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
        );

        $exist = $this->mglobal->get_data_all('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', NULL, NULL, '*', "ID_LHKPN = '$ID_LHKPN'");

        if (!empty($exist)) {
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', $penugasan);
        } else {
            $this->db->insert('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', $penugasan);
        }

        $this->db->flush_cache();

        $history = [
            [
                'ID_LHKPN' => $ID_LHKPN,
                'ID_STATUS' => 2,
                'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                'USERNAME_PENERIMA' => 'system<'.$tahun_verif.'>',
                'DATE_INSERT' => date('Y-m-d H:i:s'),
                'CREATED_IP' => $this->input->ip_address(),
            ],
            [
                'ID_LHKPN' => $ID_LHKPN,
                'ID_STATUS' => 5,
                'USERNAME_PENGIRIM' =>'system<'.$tahun_verif.'>',
                'USERNAME_PENERIMA' => 'system<'.$tahun_verif.'>',
                'DATE_INSERT' => date('Y-m-d H:i:s'),
                'CREATED_IP' => $this->input->ip_address()
            ]
           
        ];

        $this->db->insert_batch('T_LHKPN_STATUS_HISTORY', $history);
    }

    protected function bypass_verification($ID_LHKPN, $tahun_verif, $is_manual_verification){

        //data pribadi
        $cek = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, 'KD_ISO3_NEGARA', "ID_LHKPN = '$ID_LHKPN'")[0];
        $joinDATA_PRIBADI = [];
        $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*, T_LHKPN.*';
        if (@$cek->KD_ISO3_NEGARA == '') {
            $joinDATA_PRIBADI = [
                ['table' => 'M_AREA', 'on' => 'M_AREA.IDPROV = T_LHKPN_DATA_PRIBADI.PROVINSI AND M_AREA.IDKOT = CAST(T_LHKPN_DATA_PRIBADI.KABKOT AS UNSIGNED) AND M_AREA.IDKEC = T_LHKPN_DATA_PRIBADI.KECAMATAN AND M_AREA.IDKEL = ""'],
                ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_DATA_PRIBADI.ID_LHKPN'],
            ];
            $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*, T_LHKPN.*';
        } else {
            $joinDATA_PRIBADI = [
                ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.KODE_ISO3 = T_LHKPN_DATA_PRIBADI.KD_ISO3_NEGARA'],
                ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_DATA_PRIBADI.ID_LHKPN'],
            ];
            $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*,M_NEGARA.NAMA_NEGARA as KD_ISO3_NEGARA, T_LHKPN.*';
        }
       
       $DATA_PRIBADI = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', $joinDATA_PRIBADI, NULL, $selectDATA_PRIBADI, "T_LHKPN_DATA_PRIBADI.ID_LHKPN = '$ID_LHKPN'")[0];

       //data jabatan
       $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*,M_BIDANG.BDG_NAMA, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN, M_SUB_UNIT_KERJA.SUK_NAMA';
       $joinJabatan = [
           ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
           ['table' => 'M_INST_SATKER', 'on' => 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],
           ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
           ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID'],
           ['table' => 'M_BIDANG', 'on' => 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID'],
       ];
        $DATA_JABATANS = @$this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$ID_LHKPN' ", ['IS_PRIMARY', 'DESC'])[0];

        $date = date('Y-m-d');
        $TGL_VER = date('d-m-Y', strtotime($date. ' + 14 days')); 

        $full_name = @$DATA_PRIBADI->NAMA_LENGKAP;
        $ins_name = @$DATA_JABATANS->INST_NAMA;
        $jab_name = @$DATA_JABATANS->NAMA_JABATAN;
        $bid_name = @$DATA_JABATANS->BDG_NAMA;
        $thn_laporan = substr(@$DATA_PRIBADI->tgl_lapor, 0,4);
        $msg_ver = null;
        $data = array();

        $msg_ver = '
            <table>
                <tr>
                    <td>
                        Yth. Sdr. '.$full_name.' <br/>
                        '.$ins_name.'
                        <br/>
                        Di Tempat
                    </td>
                </tr>
            </table>
                                                                
                    Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan Lengkap dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
            <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">		
            <tbody class="body-table">
                <tr>
                    <td width="20%" valign="top"><b>Atas Nama</b></td>
                    <td width="5%" valign="top"><b>:</b></td>
                    <td>'.$full_name.'</td>
                </tr>
                <tr>
                    <td width="20%" valign="top"><b>Jabatan</b></td>
                    <td width="5%" valign="top"><b>:</td>
                    <td>'.$jab_name.'</td>
                </tr>
                <tr>
                    <td width="20%" valign="top"><b>Bidang</b></td>
                    <td width="5%" valign="top"><b>:</b></td>
                    <td>'.$bid_name.'</td>
                </tr>
                <tr>
                    <td width="20%" valign="top"><b>Lembaga</b></td>
                    <td width="5%" valign="top"><b>:</b></td>
                    <td>'.$ins_name.'</td>
                </tr>
                <tr>
                    <td width="20%" valign="top"><b>Tanggal / Tahun Pelaporan</b></td>
                    <td width="5%" valign="top"><b>:</b></td>
                    <td>'.$thn_laporan.'</td>
                </tr>
            </tbody>
            </table>
                <p>Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id atau call center 198.
                    <table><table>
                <tr>
                    <td>                                                      
                        Terima kasih<br/>
            
                        Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                        --------------------------------------------------------------<br/>
                        Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                        &copy; 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                    </td>
                </tr>
            </table>';

        $hasil_verif_lengkap = '{"VAL":{"DATAPRIBADI":"1","JABATAN":"1","KELUARGA":"1","HARTATIDAKBERGERAK":"1","HARTABERGERAK":"1","HARTABERGERAK2":"1","SURATBERHARGA":"1","KAS":"1","HARTALAINNYA":"1","HUTANG":"1","PENERIMAANKAS":"1","PENGELUARANKAS":"1","PELEPASANHARTA":"1","PENERIMAANHIBAH":"1","PENERIMAANFASILITAS":"1","SURATKUASAMENGUMUMKAN":"1"},"MSG":{"DATAPRIBADI":"","JABATAN":"","KELUARGA":"","HARTATIDAKBERGERAK":"","HARTABERGERAK":"","HARTABERGERAK2":"","SURATBERHARGA":"","KAS":"","HARTALAINNYA":"","HUTANG":"","PENERIMAANKAS":"","PENGELUARANKAS":"","PELEPASANHARTA":"","PENERIMAANHIBAH":"","PENERIMAANFASILITAS":"","SURATKUASAMENGUMUMKAN":""}}';

        $msg_verif_ins = "Daftar kekurangan kelengkapan yang harus diisi dan dilengkapi oleh Sdr. $full_name, $jab_name $ins_name :";

        $data = array(
            'TANGGAL' => date('Y-m-d'),
            'HASIL_VERIFIKASI' => $hasil_verif_lengkap, 
            'MSG_VERIFIKASI' => $msg_ver,
            'MSG_VERIFIKASI_INSTANSI' => $msg_verif_ins,
            'STATUS_VERIFIKASI' => '1',
            'IS_ACTIVE' => '1',
         );

        $CountData = $this->mglobal->count_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $ID_LHKPN]);

        if ($CountData == 0) {
            $data['ID_LHKPN'] = $ID_LHKPN;
            $data['CREATED_TIME'] = time();
            $data['CREATED_BY'] = 'system<'.$tahun_verif.'>';
            $data['CREATED_IP'] = $_SERVER["REMOTE_ADDR"];
            $this->db->insert('T_VERIFICATION', $data);

            /*
             * insert status proses verifikasi ke history status lhkpn
             */
            $history = [
                'ID_LHKPN' => $ID_LHKPN,
                'ID_STATUS' => 6,
                'USERNAME_PENGIRIM' => 'system<'.$tahun_verif.'>',
                'USERNAME_PENERIMA' => 'system<'.$tahun_verif.'>',
                'DATE_INSERT' => date('Y-m-d H:i:s'),
                'CREATED_IP' => $this->input->ip_address()
            ];

            $do_act = $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
        }else{
            $data['UPDATED_BY'] = 'system<'.$tahun_verif.'>';
            $do_act = $this->db->update('T_VERIFICATION', $data, ['ID_LHKPN' => $ID_LHKPN]);
        }

        $history = [
            'ID_LHKPN' => $ID_LHKPN,
            'ID_STATUS' => 17,
            'USERNAME_PENGIRIM' => 'system<'.$tahun_verif.'>',
            'USERNAME_PENERIMA' => 'system<'.$tahun_verif.'>',
            'DATE_INSERT' => date('Y-m-d H:i:s'),
            'CREATED_IP' => $this->input->ip_address()
        ];

        $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);

        $res = [];
        $res['STATUS'] = '3';  // terverifikasi lengkap
        $result = $this->db->update('T_LHKPN', $res, ['ID_LHKPN' => $ID_LHKPN]);

        if($result){
            $history = [
                'ID_LHKPN' => $ID_LHKPN,
                'ID_STATUS' => '11',
                'USERNAME_PENGIRIM' => 'system<'.$tahun_verif.'>',
                'USERNAME_PENERIMA' => 'koordinator_announcement',
                'DATE_INSERT' => date('Y-m-d H:i:s'),
                'CREATED_IP' => $this->input->ip_address()
            ];

            $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
            $this->mglobal->update('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', ['IS_ACTIVE' => '0'], ['ID_LHKPN' => $ID_LHKPN]);
            
            $this->pesan_pdf_verif_cepat($ID_LHKPN, $TGL_VER, $msg_verif_ins, FALSE, $is_manual_verification);
        }

    }

    function pesan_pdf_verif_cepat($ID_LHKPN, $TGL_VER, $MSG_VERIFIKASI_ALASAN, $is_verif_cepat, $is_manual_verification) { 

        $this->db->trans_begin();

        $this->load->model('mlhkpn', '', TRUE);
        $this->load->model('ever/Verification_model', '', TRUE);
        $entry_via = $this->mlhkpn->get_by_id($ID_LHKPN)->result()[0]->entry_via;
        
        $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);
        $usernames = $datapn->USERNAME;
        $idRoles = $datapn->ID_ROLE;
        if ($entry_via == '1'){
            $datapn = @$this->mglobal->get_detail_pn_lhkpn_excel($ID_LHKPN, TRUE, TRUE);
        }
        
        $history = $this->Verification_model->get_history_verification($ID_LHKPN)->DATE_INSERT;
        $penugas = @$this->mglobal->get_data_all('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', NULL, ['ID_LHKPN' => $ID_LHKPN], 'UPDATED_BY')[0];
        $petugas = @$this->mglobal->get_data_all('T_USER', NULL, ['USERNAME' => $penugas->UPDATED_BY], 'NAMA, ID_ROLE')[0];
        $role = @$this->mglobal->get_data_all('t_user_role', NULL, ['ID_ROLE' => $petugas->ID_ROLE], 'ROLE, DESCRIPTION')[0];
        $verif = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $ID_LHKPN])[0]->HASIL_VERIFIKASI);
        
        $arr_condition_verif_isian = array(
            "DATAPRIBADI",
            "JABATAN",
            "KELUARGA",
            "HARTATIDAKBERGERAK",
            "HARTABERGERAK",
            "HARTABERGERAK2",
            "SURATBERHARGA",
            "KAS",
            "HARTALAINNYA",
            "HUTANG",
            "PENERIMAANKAS",
            "PENGELUARANKAS",
            "PELEPASANHARTA",
            "PENERIMAANHIBAH",
            "PENERIMAANFASILITAS"
        );

        $verif_isian_ok = FALSE;
        foreach ($arr_condition_verif_isian as $val_property) {
            if ($verif->VAL->{$val_property} == "-1") {
                $verif_isian_ok = TRUE;
            }
        }

        if ($datapn->STATUS == 2) {
            $isi_pesan = $MSG_VERIFIKASI_ALASAN;
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara belum lengkap, hubungi call center 198", "idModem":6}';
            ng::logSmsActivity($usernames,$idRoles, $datapn->NO_HP, 'e-LHKPN Bapak/Ibu Memerlukan Perbaikan, silahkan cek rincian perbaikan yang dikirim ke email Saudara. Info : elhkpn@kpk.go.id atau 198', 'Verifikasi Cepat');
        } else if ($datapn->STATUS == 7) {
            $isi_pesan = 'Yth. Sdr. ' . $datapn->NAMA . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa pelaporan LHKPN atas nama Saudara setelah dilakukan verifikasi administratif dinyatakan ditolak dikarenakan tidak memenuhi kriteria yang telah ditetapkan dalam pelaporan LHKPN.<br><br>Sehubungan dengan hal tersebut silakan mengisi dan menyampaikan LHKPN sesuai petunjuk pengisian kepada Komisi Pemberantasan Korupsi dalam waktu tidak melampaui tanggal ' . $TGL_VER . '.<br><br>Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198';
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara ditolak, hubungi call center 198 untuk keterangan lebih lanjut", "idModem":6}';
            ng::logSmsActivity($usernames,$idRoles, $datapn->NO_HP, 'Status e-LHKPN Bapak/Ibu Dikembalikan ke Draft, silahkan melaporkan e-LHKPN kembali melalui elhkpn.kpk.go.id Info : elhkpn@kpk.go.id atau 198', 'Verifikasi Cepat');
            //            CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
        } else if ($datapn->STATUS == 5) {
            $isi_pesan = '<center><b>KOMISI PEMBERANTASAN KORUPSI<br>REPUBLIK INDONESIA</b><br>Jl. Kuningan Persada Kav. 4, Setiabudi<br>Jakarta 12920<br><br><b>TANDA TERIMA PENYERAHAN FORMULIR LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</b></center><br><br><table style="width: 100%;"><tr><td width="105px">Atas Nama</td><td width="10px">:</td><td>' . $datapn->NAMA . '</td></tr><tr><td>Jabatan</td><td>:</td><td>' . $datapn->NAMA_JABATAN . '</td></tr><tr><td>Bidang</td><td>:</td><td>' . $datapn->BDG_NAMA . '</td></tr><tr><td>Lembaga</td><td>:</td><td>' . $datapn->INST_NAMA . '</td></tr><tr><td>Tahun Pelaporan</td><td>:</td><td>' . date('Y', strtotime($datapn->tgl_lapor)) . '</td></tr></table><br><br><div style="text-align: right;">Jakarta, ' . date('d F Y') . '</div>';
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara Terverifikasi Tidak Lengkap dan segera diumumkan", "idModem":6}';
            ng::logSmsActivity($usernames,$idRoles, $datapn->NO_HP, 'Status e-LHKPN Bapak/Ibu Terverifikasi Tidak Lengkap, Tanda Terima telah dikirim ke email Saudara. Info : elhkpn@kpk.go.id atau 198', 'Verifikasi Cepat');
            //            CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
        } else { 
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara telah Lengkap dan segera diumumkan", "idModem":6}';
            $isi_pesan = '<center><b>KOMISI PEMBERANTASAN KORUPSI<br>REPUBLIK INDONESIA</b><br>Jl. Kuningan Persada Kav. 4, Setiabudi<br>Jakarta 12920<br><br><b>TANDA TERIMA PENYERAHAN FORMULIR LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</b></center><br><br><table style="width: 100%;"><tr><td width="105px">Atas Nama</td><td width="10px">:</td><td>' . $datapn->NAMA . '</td></tr><tr><td>Jabatan</td><td>:</td><td>' . $datapn->NAMA_JABATAN . '</td></tr><tr><td>Bidang</td><td>:</td><td>' . $datapn->BDG_NAMA . '</td></tr><tr><td>Lembaga</td><td>:</td><td>' . $datapn->INST_NAMA . '</td></tr><tr><td>Tahun Pelaporan</td><td>:</td><td>' . date('Y', strtotime($datapn->tgl_lapor)) . '</td></tr></table><br><br><div style="text-align: right;">Jakarta, ' . date('d F Y') . '</div>';
            ng::logSmsActivity($usernames,$idRoles, $datapn->NO_HP,'Status e-LHKPN Bapak/Ibu Terverifikasi Lengkap, Tanda Terima telah dikirim ke email Saudara. Info : elhkpn@kpk.go.id atau 198', 'Verifikasi Cepat');
        }

        if ($datapn->STATUS == 2) {
            $subjek = 'Daftar Kekurangan LHKPN';
        }else{
            $subjek = 'Tanda Terima ( Verifikasi )';
        }

        $pengirim = array(
            'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
            'ID_PENERIMA' => $datapn->ID_USER,
            'SUBJEK' => $subjek,
            'PESAN' => $isi_pesan,
            'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
            'IS_ACTIVE' => '1',
            'ID_LHKPN' => $ID_LHKPN
        );

        $kirim = $this->mglobal->insert('T_PESAN_KELUAR', $pengirim);
        

        if ($kirim) {

            $output_filename = "Tanda_Terima_LHKPN_" . date('d-F-Y') . ".pdf";
            if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                $output_filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y') . ".pdf";
            }

            $penerima = array(
                'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
                'ID_PENERIMA' => $datapn->ID_USER,
                'SUBJEK' => $subjek,
                'PESAN' => $isi_pesan,
                'FILE' => "../../../uploads/pdf/" . $datapn->NIK . '/' . $output_filename,
                'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                'IS_ACTIVE' => '1',
                'ID_LHKPN' => $ID_LHKPN
            );

            $this->mglobal->insert('T_PESAN_MASUK', $penerima);

            // create file
            $time = time();
            $dataPDF = array(
                'NAMA' => $datapn->NAMA,
                'JABATAN' => $datapn->NAMA_JABATAN,
                'BDG_NAMA' => $datapn->BDG_NAMA,
                'LEMBAGA' => $datapn->INST_NAMA,
                'STATUS' => $datapn->STATUS,
                'LAPOR' => date('Y', strtotime($datapn->TGL_LAPOR)),
                'PETUGAS' => $petugas->NAMA,
                'TUGAS_PETUGAS' => $role->ROLE
            );

//            $th = date('Y');

            $filename = 'uploads/pdf/' . $datapn->NIK . "/$output_filename";

            if (!file_exists($filename)) {
                $dir = './uploads/pdf/' . $datapn->NIK . '/';

                if (is_dir($dir) === false) {
                    mkdir($dir);
                }else{
                    chmod($dir, 0755);
                    chown($dir, "apache");
                    chgrp($dir, "apache");
                }
            }

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "model_qr_prefix_nomor" => $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? "LK-ELHKPN-" : "TT-ELHKPN-",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
            ]);

            //$this->load->library('ey_barcode');

            
            $qr_content_data = json_encode((object) [
                        "data" => [
                            (object) ["tipe" => '1', "judul" => "Atas Nama", "isi" => $datapn->NAMA_LENGKAP],
                            (object) ["tipe" => '1', "judul" => "NIK", "isi" => $datapn->NIK],
                            (object) ["tipe" => '1', "judul" => "Jabatan", "isi" => $datapn->NAMA_JABATAN],
                            (object) ["tipe" => '1', "judul" => "Lembaga", "isi" => $datapn->INST_NAMA],
                            (object) ["tipe" => '1', "judul" => "Unit Kerja", "isi" => $datapn->UK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Sub Unit Kerja", "isi" => $datapn->SUK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus')." - ".show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor))],
                            (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($datapn->tgl_kirim_final)],
                            (object) ["tipe" => '1', "judul" => "Hasil Verifikasi", "isi" => $datapn->STATUS == "3" ? "Terverifikasi Lengkap" : "Terverifikasi Tidak Lengkap"],
                        ],
                        "encrypt_data" => $ID_LHKPN . "tt",
                        "id_lhkpn" => $ID_LHKPN,
                        "judul" => $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? "Lampiran Kekurangan E-LHKPN" : "Tanda Terima E-LHKPN",
                        "tgl_surat" => date('Y-m-d'),
            ]);
            $qr_image_location = $this->lws_qr->create('$qr_content_data',"tes_qr2-" . $ID_LHKPN . ".png");
            
            $get_nik = $datapn->NIK;
            $get_nama = $datapn->NAMA;
        

            
            //$show_barcode = "'".$get_nik.chr(9).$get_nama;

            //$bc_image_location = $this->ey_barcode->generate($show_barcode, "tes_bc2-" . $ID_LHKPN . ".jpg");               
            
            $show_qr2 = "'".$get_nik.chr(9).$get_nama;
            $qr2_file = "tes_qr_new-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
            $qr2_image_location = $this->lws_qr->create($show_qr2, $qr2_file);
        
            if ($datapn->STATUS == "7") {
            }else{

                $data = array(
                    "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                    "LKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "v" : " ",
                    "TLKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? " " : "v",
                    "NIK" => $datapn->NIK,
                    "LEMBAGA" => $datapn->INST_NAMA,
                    "UNIT_KERJA" => $datapn->UK_NAMA,
                    "SUB_UNIT_KERJA" => $datapn->SUK_NAMA,
                    "JABATAN" => $datapn->NAMA_JABATAN,
                    "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus',
                    "KHUSUS" => show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)),
                    "TANGGAL" => tgl_format($datapn->tgl_kirim_final),
                    "qr_code" => $qr_image_location,
                    "TGL_VERIFIKASI" => $history,
                );

                $this->load->library('pdfgenerator');

                if (($datapn->STATUS == "2" || $datapn->STATUS == "7") && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                    $data = array(
                        "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                        "NIK" => $datapn->NIK,
                        "LEMBAGA" => $datapn->INST_NAMA,
                        "JABATAN" => $datapn->NAMA_JABATAN,
                        "QR_IMAGE_LOCATION" =>  $qr2_image_location,
                        "msg_verifikasi" => $verif,
                    );
                    $html = $this->load->view('ever/export_pdf/perlu_perbaikan', $data, true);
                    $filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y');
                }else{
                    $exp_tgl_kirim = explode('-', $datapn->tgl_kirim_final);
                    $thn_kirim = $exp_tgl_kirim[0];
                    
                    $data = array(
                        "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                        "LKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "v" : " ",
                        "TLKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? " " : "v",
                        "NIK" => $datapn->NIK,
                        "LEMBAGA" => $datapn->INST_NAMA,
                        "UNIT_KERJA" => $datapn->UK_NAMA,
                        "SUB_UNIT_KERJA" => $datapn->SUK_NAMA,
                        "JABATAN" => $datapn->NAMA_JABATAN,
                        "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus',
                        "KHUSUS" => show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)),
                        "TANGGAL" => tgl_format($datapn->tgl_kirim_final),
                        "qr_code" => $qr_image_location,
                        "TGL_VERIFIKASI" => $history,
                        "TAHUN_KIRIM" => $thn_kirim
                    );
                    $html = $this->load->view('ever/export_pdf/tanda_terima', $data, true);
                    $filename = "Tanda_Terima_LHKPN_" . date('d-F-Y');
                }

                $method = "store";
                $path_pdf = 'uploads/pdf/' . $datapn->NIK . '/';
                $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait',$path_pdf);
                $output_filename = $filename . ".pdf";

                $temp_dir = APPPATH."../images/qrcode/";
                $qr_image = "tes_qr2-" . $ID_LHKPN . ".png";
                unlink($temp_dir.$qr_image);
                unlink($temp_dir.$qr2_file);
                //$temp_dir_br = APPPATH."../uploads/barcode/";
                //$br_image = "tes_bc2-" . $ID_LHKPN . ".jpg";
                //unlink($temp_dir_br.$br_image);

                if(switchMinio()){
                    $resultMinio = uploadMultipleToMinio($path_pdf.$output_filename,'application/pdf',$output_filename,$path_pdf);
                    if($resultMinio){
                        // File asli masih dipakai untuk kirim email
                        // $deleteLocal = unlink($path_pdf.$output_filename);
                    }
                }
            }
            //tutup comment

            $message_lengkap = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   Di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan <b>Lengkap</b> dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                 </td>
                            </tr>
                        </table>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NAMA_LENGKAP . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->NAMA_JABATAN . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->BDG_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->INST_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Tahun Pelaporan</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . substr($datapn->tgl_lapor, 0, 4) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                            <table>
                                             <tr>
                                                 <td>
                                                    Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br/>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                 </td>
                                            </tr>
                                            </table>';

            $message_tidak_lengkap = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   Di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan <b>Tidak Lengkap</b> dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                 </td>
                            </tr>
                        </table>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NAMA_LENGKAP . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->NAMA_JABATAN . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->BDG_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->INST_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Tahun Pelaporan</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . substr($datapn->tgl_lapor, 0, 4) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                            <table>
                                             <tr>
                                                 <td>
                                                    Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br/>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                 </td>
                                            </tr>
                                            </table>';

            $message_perbaikan = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   di Tempat<br/><br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                Bersama ini kami sampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi ternyata masih terdapat kekurangan dalam LHKPN Saudara yang perlu dilengkapi sebagaimana daftar terlampir. Untuk pemrosesan lebih lanjut, Saudara diminta untuk melengkapi kekurangan data dan menyampaikan ke Komisi Pemberantasan Korupsi tidak melampaui tanggal ' . $TGL_VER . '.<br><br>
                                Email pemberitahuan permintaan kelengkapan ini tidak dapat digunakan sebagai tanda terima LHKPN, tanda terima akan diberikan apabila Saudara telah melengkapi daftar permintaan kelengkapan dan telah diverifikasi oleh KPK.<br><br>
                                Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br><br>
                                Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>
                            </tr>
                        </table>
                        <table>
                         <tr>
                             <td>
                                Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                --------------------------------------------------------------<br/>
                                Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br/>
                                &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                             </td>
                        </tr>
                        </table>';

            $admin = $this->mglobal->get_data_all('T_USER', null, ['USERNAME = ' => 'admin_kpk'], 'ID_USER, NAMA,EMAIL')[0];

            //if ($datapn->STATUS == "7") {
            //    ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', 'Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa LHKPN Tanggal '. tgl_format($datapn->tgl_lapor) .' atas nama Saudara dinyatakan DIKEMBALIKAN dikarenakan KPK belum menerima kekurangan dokumen kelengkapan atas nama Saudara sesuai dalam jangka waktu yang telah ditentukan.<br><br>Sehubungan dengan hal tersebut,  harap agar Saudara segera mengisi kembali LHKPN melalui elhkpn.kpk.go.id dan menyampaikannya kepada KPK.  Untuk informasi lebih lanjut, silakan menghubungi kami melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198', NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            //} else if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
            //    ng::mail_send($datapn->EMAIL, 'Daftar Kekurangan LHKPN', $message_perbaikan, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            //} else if ($datapn->STATUS == "5") {
            //    ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_tidak_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            //} else {
            //    ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            //}
            //unlink($path_pdf.$output_filename);
            $this->db->trans_commit();
            if ($datapn->STATUS == "7") {
                ng::mail_send_queue($datapn->EMAIL, 'Dikembalikan ( Verifikasi )', 'Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa LHKPN Tanggal '. tgl_format($datapn->tgl_lapor) .' atas nama Saudara dinyatakan DIKEMBALIKAN dikarenakan KPK belum menerima kekurangan dokumen kelengkapan atas nama Saudara sesuai dalam jangka waktu yang telah ditentukan.<br><br>Sehubungan dengan hal tersebut,  harap agar Saudara segera mengisi kembali LHKPN melalui elhkpn.kpk.go.id dan menyampaikannya kepada KPK.  Untuk informasi lebih lanjut, silakan menghubungi kami melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198');
            } else if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                ng::mail_send_queue($datapn->EMAIL, 'Daftar Kekurangan LHKPN', $message_perbaikan, NULL, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, NULL, NULL, NULL, NULL, TRUE);
            } else if ($datapn->STATUS == "5") {
                ng::mail_send_queue($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_tidak_lengkap, NULL, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, NULL, NULL, NULL, NULL, TRUE);
            } else {

                if($is_verif_cepat == FALSE){  /// jika verif otomatis ///

                    $data_email = [
                        'to' => $datapn->EMAIL,
                        'subject' => 'Tanda Terima ( Verifikasi )',
                        'message' => $message_lengkap,
                        'attach' => 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename,
                        'is_unlink_attach' => true
                    ];  

                    $this->session->set_userdata('session_verif_otomatis', $data_email);
                    $this->session->set_userdata('session_flag_is_manual_verif', $is_manual_verification);

                    $this->kirim_lhkpn($ID_LHKPN);
                }else{  /// verif cepat ///
                    ng::mail_send_queue($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_lengkap, NULL, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, NULL, NULL, NULL, NULL, TRUE);

                }

            }
            $this->session->set_userdata('msg_verifikasi_cepat','LHKPN Terverifikasi Lengkap ! Surat Tanda Terima (softcopy) berhasil dikirim ke '. $datapn->NAMA_LENGKAP.' ('.$datapn->NIK.')');    
        } else {
        
            $this->db->trans_rollback();

            $this->session->set_userdata('msg_verifikasi_cepat','LHKPN Terverifikasi Lengkap ! Surat Tanda Terima (softcopy) gagal dikirim ke '. $datapn->NAMA_LENGKAP.' ('.$datapn->NIK.')');
        }
        intval($this->db->trans_status());

        if($is_verif_cepat){
            redirect('index.php?dpg=c8763ad09e4afa1445e98bee98524fb3#index.php/ever/verification/index/lhkpn?sessVerif');
        }
        
    }

}

?>
