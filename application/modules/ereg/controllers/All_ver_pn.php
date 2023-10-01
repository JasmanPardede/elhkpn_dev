<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller User
 * 
 * @author 
 * @package Controllers
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class All_ver_pn extends MY_Controller {

    public $limit = 10;

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
        $this->load->model('Mt_pn_upload_xls_temp');
        $this->load->model('Mt_pn_ws_temp');
        $this->load->model('Minstansi');
        $this->username = $this->session->userdata('USERNAME');
        $this->instansi = $this->session->userdata('INST_SATKERKD');

        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_KPK, IS_INSTANSI, IS_USER_INSTANSI');
        $this->IS_KPK = $role[0]->IS_KPK;

        $this->makses->initialize();
//        $this->makses->check_is_read();
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);
    }

    /** Home
     * 
     * @return html Home
     */
    public function index() {
        $this->makses->check_is_first();
        $data['instansi_found'] = is_null($this->instansi) ? FALSE : $this->instansi;

        $data['INST_SATKERKD'] = $this->session->userdata('INST_SATKERKD');
        $data['UK_ID'] = $this->session->userdata('UK_ID');



//        $ins = $this->session->userdata('INST_SATKERKD');
//        if ($idRole == '3') {
//            $sUnitKerja = 1;
//        }
        $data['urlAdd'] = site_url('ereg/All_ver_pn/popUpAdd');
        $data['in_index_js_without_init_instansi_slc'] = FALSE;


        $types_of_admin = $this->check_type_of_admin();

        $data = array_merge($data, $types_of_admin);
        $data['cari_instansi_hdd'] = 'CARI_INSTANSI_HDD';
        $this->data['showUnitKerja'] = TRUE;
        $data_js = $data;
        $data['v_cb_instansi'] = $this->load->view('v_daftar_pnwl_xl/cb_instansi', $data_js, TRUE);


        $data['js_page'][] = $this->load->view('js/all_pn/v_ver_pnwl_xl/index_js_datatable', $data_js, TRUE);
        $data['js_page'][] = $this->load->view('v_daftar_pnwl_xl/js/cb_instansi_js', $data_js, TRUE);
        $data['js_page'][] = $this->load->view('js/all_pn/v_ver_pnwl_xl/index_js', $data_js, TRUE);
        $this->load->view('v_ver_pnwl_xl', $data);
    }

    public function daftar_xl() {
        $this->makses->check_is_first();

        $data["idRole"] = $this->session->userdata('ID_ROLE');

        $data['instansi_found'] = is_null($this->instansi) ? FALSE : $this->instansi;
        $data['INST_SATKERKD'] = $this->session->userdata('INST_SATKERKD');
        $data['UK_ID'] = $this->session->userdata('UK_ID');
        $data['on_data_xl'] = "daftar_xl";

        $types_of_admin = $this->check_type_of_admin();

        $data = array_merge($data, $types_of_admin);
        $this->data['showUnitKerja'] = TRUE;
        $data_js = $data;

        $data['v_cb_instansi'] = $this->load->view('v_daftar_pnwl_xl/cb_instansi', $data_js, TRUE);

        $data['js_page'][] = $this->load->view('v_daftar_pnwl_xl/js/v_daftar_pnwl_xl_dttable', $data_js, TRUE);
        $data['js_page'][] = $this->load->view('v_daftar_pnwl_xl/js/cb_instansi_js', $data_js, TRUE);
        $data['js_page'][] = $this->load->view('js/all_pn/v_daftar_pnwl_xl_js', $data_js, TRUE);

        $this->load->view('v_daftar_pnwl_xl', $data);
    }

    public function get_ver_pnwl_jabatan_two() {

        self::load_data_list_ver_pnwl();
    }

    public function get_ver_pnwl_tambahan_two() {

        self::load_data_list_ver_pnwl_tambah();
    }

    public function get_ver_pnwl_tambahan_three() {

        self::load_data_list_ver_pnwl_tambah();
    }

    public function get_ver_pnwl_inst_two() {

        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $response = $this->Mt_pn_upload_xls_temp->get_ver_pnwl_inst($this->instansi, $currentpage, $keyword, $rowperpage, true);
        $responseCount = $this->Mt_pn_upload_xls_temp->get_ver_pnwl_inst($this->instansi, $currentpage, $keyword, $rowperpage, false);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($responseCount->num_rows()),
            "iTotalDisplayRecords" => intval($responseCount->num_rows()),
            "aaData" => $response->result()
        );

        $this->to_json($dtable_output);
    }

    public function get_ver_pnwl_nonact_two() {

        self::load_data_list_ver_pnwl_non_aktif();
    }

    protected function get_param_load_paging_default($load_paging_area = FALSE) {
        $load_paging_area = $load_paging_area ? $load_paging_area : 'default';
        return $this->_param_load_paging_default($load_paging_area);
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

    function load_data_list_ver_pnwl() {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

//        $ins = $this->get_instansi_daftar_pn_wl_via_excel();

        $arr_res = $this->get_instansi_daftar_pn_wl_via_excel(TRUE);
        extract($arr_res);

//        gwe_dump(array($arr_res), TRUE);

        $this->load->model('Mt_pn_upload_xls_jabatan');


        $dtable_output = $this->Mt_pn_upload_xls_jabatan->get_ver_pnwl_jabatan_two($ins, $currentpage, $keyword, $rowperpage, TRUE, $uk);
        //var_dump("Njajal");exit;
        $this->to_json($dtable_output);
    }

    function load_data_list_ver_pnwl_ws() {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $response = $this->Mt_pn_ws_temp->get_ver_pnwl_jabatan_two($this->instansi, $currentpage, $keyword, $rowperpage, true);
        $responseCount = $this->Mt_pn_ws_temp->get_ver_pnwl_jabatan_two($this->instansi, $currentpage, $keyword, $rowperpage, false);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($responseCount->num_rows()),
            "iTotalDisplayRecords" => intval($responseCount->num_rows()),
            "aaData" => $response->result()
        );

        $this->to_json($dtable_output);
    }

//    function get_instansi_daftar_pn_wl_via_excel() {
//        $ins = $this->instansi;
//
//        if (is_null($ins) && $this->session->userdata['ID_ROLE'] == '2') {
//            $ins = $this->input->get('CARI[INSTANSI]');
//        }
//
//        return $ins;
//    }

    function get_unit_kerja_daftar_pn_wl_via_excel() {
        $is_uk = $this->session->userdata('IS_UK');
        if ($is_uk == 1) {
            
        } else {
            $unit_kerja = $this->UK_ID;
        }

        if (is_null($unit_kerja) && $this->session->userdata['ID_ROLE'] == '2' && $this->session->userdata['ID_ROLE'] == '31') {
            $unit_kerja = $this->input->get('CARI[UNIT_KERJA]');
        }

        return $ins;
    }

    function load_data_list_ver_pnwl_tambah() {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        $arr_res = $this->get_instansi_daftar_pn_wl_via_excel(TRUE);
        extract($arr_res);
        //$unit_kerja = $this->get_unit_kerja_daftar_pn_wl_via_excel();
//        gwe_dump($uk, TRUE);

        $this->load->model('Mt_pn_upload_xls_ver_pnwl_tambah');

        $dtable_output = $this->Mt_pn_upload_xls_ver_pnwl_tambah->get_ver_pnwl_tambahan_two($ins, $currentpage, $keyword, $rowperpage, $unit_kerja, $uk);

        $this->to_json($dtable_output);
    }

    function load_data_list_ver_pnwl_tambah_ws() {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        $ins = $this->get_instansi_daftar_pn_wl_via_excel();

        $response = $this->Mt_pn_ws_temp->get_ver_pnwl_tambahan_two($this->instansi, $currentpage, $keyword, $rowperpage, true);
        //$responseCount = $this->Mt_pn_ws_temp->get_ver_pnwl_tambahan_two($this->instansi, $currentpage, $keyword, $rowperpage, false);

        /*
          $dtable_output = array(
          "sEcho" => intval($this->input->get("sEcho")),
          "iTotalRecords" => intval($responseCount->num_rows()),
          "iTotalDisplayRecords" => intval($responseCount->num_rows()),
          "aaData" => $response->result()
          );
         */
        $this->to_json($dtable_output);
    }

    function load_data_list_ver_pnwl_non_aktif() {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

//        $arr_res = $this->get_instansi_daftar_pn_wl_via_excel(TRUE);
        $arr_res = $this->get_instansi_daftar_pn_wl_via_excel(TRUE);

        extract($arr_res);
//        
//        var_dump($arr_res);exit;

        $dtable_output = $this->Mt_pn_upload_xls_temp->get_ver_pnwl_nonact_two($ins, $currentpage, $keyword, $rowperpage, TRUE, $uk);
        //echo $this->db->last_query(); exit;
        $this->to_json($dtable_output);
    }

    function load_data_list_ver_pnwl_non_aktif_ws() {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $response = $this->Mt_pn_ws_temp->get_ver_pnwl_nonact_two($this->instansi, $currentpage, $keyword, $rowperpage, true);
        $responseCount = $this->Mt_pn_ws_temp->get_ver_pnwl_nonact_two($this->instansi, $currentpage, $keyword, $rowperpage, false);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->num_rows()),
            "iTotalDisplayRecords" => intval($response->num_rows()),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    public function ajax_save_add_pnwl($id, $edit = FALSE) {
        $password = createRandomPassword(6);
        $this->db->trans_begin();
        $response = $this->Mt_pn_upload_xls_temp->check_nik_exist($id);

        if (!$response) {
            $this->Mt_pn_upload_xls_temp->save_penambahan_pnwl_ver($id);
            $id_pn = $this->db->insert_id();
            $this->Mt_pn_upload_xls_temp->save_penambahan_jab_pnwl_ver($id, $id_pn);
//        $this->Mt_pn_upload_xls_temp->save_penambahan_user_pnwl_ver($id, $password);
//        $kirim_info = $this->Mt_pn_upload_xls_temp->kirim_info_pn($id, $password);

            if ($edit == TRUE)
                $fl = 2;
            else
                $fl = 1;

            $data_temp = array(
                'IS_PROCESSED' => 1,
                'JENIS_PROSES' => $fl
            );
            $this->Mt_pn_upload_xls_temp->update($id, $data_temp);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            redirect('index.php/ereg/All_ver_pn');
        }
        echo "0";
        // echo json_encode(array("status" => 0, "error" => "NIK telah terdaftar pada basis data. Lakukan pembatalan."));
    }

    public function ajax_save_add_pnwl_ws($id, $edit = FALSE) {
        $password = createRandomPassword(6);
        $this->db->trans_begin();
        $this->Mt_pn_ws_temp->save_penambahan_pnwl_ver($id);
        $id_pn = $this->db->insert_id();
        $this->Mt_pn_ws_temp->save_penambahan_jab_pnwl_ver($id, $id_pn);
        $this->Mt_pn_ws_temp->save_penambahan_user_pnwl_ver($id, $password);
        $kirim_info = $this->Mt_pn_ws_temp->kirim_info_pn($id, $password);

        if ($edit == TRUE)
            $fl = 2;
        else
            $fl = 1;

        $data_temp = array(
            'IS_PROCESSED' => 1,
            'JENIS_PROSES' => $fl
        );

        $this->Mt_pn_ws_temp->update($id, $data_temp);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        redirect('index.php/ereg/All_ver_pn/verifikasi_ws');
    }

    public function ajax_update_jabpnwl($id, $edit = FALSE) {

        $dt_temp = $this->Mt_pn_upload_xls_temp->get_temp_excel($id);
        $dt_jabatan = $this->Mt_pn_upload_xls_temp->get_jabatan_byNAME($dt_temp->NAMA_JABATAN, $dt_temp);
        $dt_pn = $this->Mt_pn_upload_xls_temp->get_pn_byNIK($dt_temp->NIK);

        $this->db->trans_begin();

        $jab = array(
            'IS_CURRENT' => '0',
            'IS_DELETED' => '1'
        );

        $idinst = $this->input->get('idinst');

        $this->db->where('ID_PN', $dt_pn->ID_PN);
        $this->db->where('LEMBAGA', $idinst);
        $this->db->where('TAHUN_WL', date("Y"));
        $this->db->update('T_PN_JABATAN', $jab);

//        $data = array(
//            'EMAIL' => $dt_temp->EMAIL,
//            'NO_HP' => $dt_temp->NO_HP,
//            'NIP_NRP' => $dt_temp->NIP_NRP
//        );
//
//        $this->db->where('ID_PN', $dt_pn->ID_PN);
//        $this->db->update('T_PN', $data);

        $jabatan = array(
            'ID_JABATAN' => $dt_jabatan->ID_JABATAN,
            'ID_PN' => $dt_pn->ID_PN,
            // 'LEMBAGA' => $lembaga,
            // 'UNIT_KERJA' => $UNIT_KERJA,
            'NAMA_LEMBAGA' => $dt_temp->NAMA_LEMBAGA,
            'LEMBAGA' => $dt_temp->ID_LEMBAGA,
            'UNIT_KERJA' => $dt_temp->NAMA_UNIT_KERJA,
            'SUB_UNIT_KERJA' => $dt_temp->NAMA_SUB_UNIT_KERJA,
            'DESKRIPSI_JABATAN' => $dt_temp->NAMA_JABATAN,
            'ESELON' => $dt_jabatan->KODE_ESELON,
            'IS_ACTIVE' => '1',
            'IS_CURRENT' => '1',
            'IS_CALON' => '0',
            'TAHUN_WL' => date('Y'),
            'IS_WL' => '1',
            'ID_STATUS_AKHIR_JABAT' => $dt_pn->ID_STATUS_AKHIR_JABAT,
            'CREATED_TIME' => time(),
            'CREATED_BY' => $this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
        );


        $this->db->insert('T_PN_JABATAN', $jabatan);
//        echo $this->db->last_query();
//        exit;

        if ($edit == TRUE)
            $fl = 2;
        else
            $fl = 1;

        $data_temp = array(
            'IS_PROCESSED' => 1,
            'JENIS_PROSES' => $fl
        );
        $this->Mt_pn_upload_xls_temp->update($id, $data_temp);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        redirect('index.php/ereg/All_ver_pn');
    }

    public function ajax_update_jabpnwl_ws($id, $edit = FALSE) {

        $dt_temp = $this->Mt_pn_ws_temp->get_temp_excel($id);
        $dt_jabatan = $this->Mt_pn_ws_temp->get_jabatan_byNAME($dt_temp->NAMA_JABATAN);
        $dt_pn = $this->Mt_pn_ws_temp->get_pn_byNIK($dt_temp->NIK);

        $this->db->trans_begin();

        $jab = array(
            'IS_CURRENT' => '0'
        );

        $this->db->where('ID_PN', $dt_pn->ID_PN);
        $this->db->where('LEMBAGA', $this->instansi);
        $this->db->update('T_PN_JABATAN', $jab);

        $jabatan = array(
            'ID_JABATAN' => $dt_jabatan->ID_JABATAN,
            'ID_PN' => $dt_pn->ID_PN,
            // 'LEMBAGA' => $lembaga,
            // 'UNIT_KERJA' => $UNIT_KERJA,
            // 'SUB_UNIT_KERJA' => $SUB_UNIT_KERJA,
            'NAMA_LEMBAGA' => $dt_temp->NAMA_LEMBAGA,
            'LEMBAGA' => $dt_temp->ID_LEMBAGA,
            'UNIT_KERJA' => $dt_temp->NAMA_UNIT_KERJA,
            'SUB_UNIT_KERJA' => $dt_temp->NAMA_SUB_UNIT_KERJA,
            'DESKRIPSI_JABATAN' => $dt_temp->NAMA_JABATAN,
            'ESELON' => $dt_jabatan->KODE_ESELON,
            'IS_ACTIVE' => '1',
            'IS_CURRENT' => '1',
            'IS_CALON' => '0',
            'CREATED_TIME' => time(),
            'CREATED_BY' => $this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
        );
        $this->db->insert('T_PN_JABATAN', $jabatan);

        if ($edit == TRUE)
            $fl = 2;
        else
            $fl = 1;

        $data_temp = array(
            'IS_PROCESSED' => 1,
            'JENIS_PROSES' => $fl
        );

        $this->Mt_pn_ws_temp->update($id, $data_temp);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        redirect('index.php/ereg/All_ver_pn/verifikasi_ws');
    }

    public function daftar_ws() {
        $this->makses->check_is_first();
        $data['list_ver_pnwl'] = $this->Mt_pn_ws_temp->get_ver_pnwl_jabatan($this->instansi);
        $data['list_ver_pnwl_tambah'] = $this->Mt_pn_ws_temp->get_ver_pnwl_tambahan($this->instansi);
        $data['list_ver_pnwl_non_aktif'] = $this->Mt_pn_ws_temp->get_ver_pnwl_nonact($this->instansi);
        $data['list_cek_temp'] = $this->Mt_pn_ws_temp->get_ver_pnwl_inst($this->instansi, $this->username);
        $this->load->view('v_daftar_pnwl_ws', $data);
    }

    public function verifikasi_ws() {
        $this->makses->check_is_first();
        $data['list_ver_pnwl'] = $this->Mt_pn_ws_temp->get_ver_pnwl_jabatan($this->instansi);
        $data['list_ver_pnwl_tambah'] = $this->Mt_pn_ws_temp->get_ver_pnwl_tambahan($this->instansi);
        $data['list_ver_pnwl_non_aktif'] = $this->Mt_pn_ws_temp->get_ver_pnwl_nonact($this->instansi);
        $data['list_cek_temp'] = $this->Mt_pn_ws_temp->get_ver_pnwl_inst($this->instansi, $this->username);
        $this->load->view('v_ver_pnwl_ws', $data);
    }

    public function save_load_ws() {
        $dt_ws = 'http://localhost/lhkpn/file/json/pn.json';

        $json = file_get_contents($dt_ws);
        $json_a = json_decode($json, true);


        $instansi = $this->Minstansi->get_by_id($this->instansi)->row();

        $cek = $this->Mt_pn_ws_temp->get_ver_pnwl_inst($this->instansi, $this->session->userdata('USR'));
        if ($cek)
            $this->Mt_pn_ws_temp->delete_instansi($this->instansi, $this->session->userdata('USR'));

        foreach ($json_a as $data):
            echo $data['NIK'] . ' - ' . $data['NIP'] . '<br/>';

            $data_xls = array(
                'NIK' => $data['NIK'],
                'NIP_NRP' => $data['NIP'],
                'NAMA' => strtoupper($data['NamaLengkap']),
                'NAMA_UNIT_KERJA' => strtoupper($data['UnitKerja']),
                'NAMA_SUB_UNIT_KERJA' => strtoupper($data['SubUnitKerja']),
                'ID_LEMBAGA' => $this->instansi,
                'NAMA_LEMBAGA' => strtoupper($instansi->INST_NAMA),
                'NAMA_JABATAN' => strtoupper($data['Jabatan']),
                'EMAIL' => $data['email'],
                'IS_PROCESSED' => '0',
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"]
            );
            $this->Mt_pn_ws_temp->save($data_xls);

        endforeach;
//        
        redirect('index.php/ereg/All_ver_pn/daftar_ws');
    }

    function popUpAdd($sts, $id, $cr, $perub = FALSE) {
        // $this->makses->check_is_write();

        $nm_db = $cr == 'xl' ? 't_pn_upload_xls_temp' : 't_pn_ws_temp';

        $this->load->model('muser', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $data_temp = $this->mglobal->get_by_id($nm_db, 'ID', $id);

        $NAMA_LEMBAGA = $data_temp->NAMA_LEMBAGA ? strtoupper($data_temp->NAMA_LEMBAGA) : FALSE;
        $lembaga = FALSE;
        if ($NAMA_LEMBAGA !== FALSE) {
            $this->db->like('INST_NAMA', $NAMA_LEMBAGA);
            $this->db->limit(1);
            $lembaga = $this->db->get('m_inst_satker')->row();
        }

        $NAMA_UNIT_KERJA = $data_temp->NAMA_UNIT_KERJA ? strtoupper($data_temp->NAMA_UNIT_KERJA) : FALSE;
        $unit_kerja = FALSE;
        if ($NAMA_UNIT_KERJA !== FALSE) {
            $this->db->like('UK_NAMA', $NAMA_UNIT_KERJA);
            $this->db->limit(1);
            $unit_kerja = $this->db->get('m_unit_kerja')->row();
        }

        $NAMA_SUB_UNIT_KERJA = $data_temp->NAMA_SUB_UNIT_KERJA ? strtoupper($data_temp->NAMA_SUB_UNIT_KERJA) : FALSE;
        $sub_unit_kerja = FALSE;
        if ($NAMA_SUB_UNIT_KERJA !== FALSE) {
            $this->db->like('SUK_NAMA', $NAMA_SUB_UNIT_KERJA);
            $this->db->limit(1);
            $sub_unit_kerja = $this->db->get('m_sub_unit_kerja')->row();
        }
//        echo $this->db->last_query();exit;

        $NAMA_JABATAN = $data_temp->NAMA_JABATAN ? strtoupper($data_temp->NAMA_JABATAN) : FALSE;
        $jabatan = FALSE;
        if ($NAMA_JABATAN !== FALSE) {
            $this->db->like('NAMA_JABATAN', $NAMA_JABATAN);
            $this->db->limit(1);
            if ($unit_kerja) {
                $this->db->where('m_jabatan.uk_id = \'' . $unit_kerja->UK_ID . '\'');
            }
            $jabatan = $this->db->get('m_jabatan')->row();
        }

        $data = array(
            'form' => 'add',
            'sts' => $sts,
            'cr' => $cr,
            'data_temp' => $data_temp,
            'lembaga' => $lembaga,
            'unit_kerja' => $unit_kerja,
            'sub_unit_kerja' => $sub_unit_kerja,
            'jabatan' => $jabatan,
            'is_perub' => $perub,
            'isInstansi' => $this->is_instansi()
                // 'combo_unit_kerja' => $this->mglobal->get_data_by_id('m_unit_kerja', 'UK_LEMBAGA_ID', $data_temp->ID_LEMBAGA),   
                // 'combo_sub_unit_kerja' => $this->mglobal->get_data_by_id('m_sub_unit_kerja', 'UK_ID', $unit_kerja->UK_ID)
        );
        $this->load->view('v_pnwl_edit', $data);
    }

    function save_edit($is_perub = FALSE) {
        $id = $this->input->post('ID_TEMP');
        $sts = $this->input->post('STS');
        $this->db->trans_begin();
        $inst = $this->mglobal->get_data_by_id('m_inst_satker', 'INST_SATKERKD', $this->input->post('INST_TUJUAN'));
        $uk = $this->mglobal->get_data_by_id('m_unit_kerja', 'UK_ID', $this->input->post('UNIT_KERJA'));
        $suk_id = $this->input->post('SUB_UNIT_KERJA');
        $suk = $this->mglobal->get_data_by_id('m_sub_unit_kerja', 'SUK_ID', $suk_id);
        $jab_id = $this->input->post('JABATAN');
        $jab = $this->mglobal->get_data_by_id('m_jabatan', 'ID_JABATAN', $jab_id);

        $data_temp = array(
            'NAMA_UNIT_KERJA' => @$uk[0]->UK_NAMA,
            'NAMA_SUB_UNIT_KERJA' => @$suk[0]->SUK_NAMA,
            'NAMA_JABATAN' => @$jab[0]->NAMA_JABATAN,
            'id_jab_temp' => $jab_id
        );

        if (!$is_perub) {
            $data_temp = array('NIK' => $this->input->post('NIK'),
                'NIP_NRP' => $this->input->post('NIP_NRP'),
                'NAMA' => $this->input->post('NAMA'),
                'ID_LEMBAGA' => $this->input->post('INST_TUJUAN'),
                'NAMA_LEMBAGA' => @$inst[0]->INST_NAMA,
                'NAMA_UNIT_KERJA' => @$uk[0]->UK_NAMA,
                'NAMA_SUB_UNIT_KERJA' => @$suk[0]->SUK_NAMA,
                'NAMA_JABATAN' => @$jab[0]->NAMA_JABATAN,
                'EMAIL' => $this->input->post('EMAIL'),
                'NO_HP' => $this->input->post('NO_HP'),
                'id_jab_temp' => $this->input->post('JABATAN')
            );
        }

        $this->Mt_pn_upload_xls_temp->update($id, $data_temp);



        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;

//        redirect('#index.php/ereg/All_ver_pn');
    }

    function save_edit_ws() {
        $id = $this->input->post('ID_TEMP');
        $sts = $this->input->post('STS');
        $this->db->trans_begin();
        $inst = $this->mglobal->get_data_by_id('m_inst_satker', 'INST_SATKERKD', $this->input->post('INST_TUJUAN'));
        $uk = $this->mglobal->get_data_by_id('m_unit_kerja', 'UK_ID', $this->input->post('UNIT_KERJA'));
        $suk = $this->mglobal->get_data_by_id('m_sub_unit_kerja', 'SUK_ID', $this->input->post('SUB_UNIT_KERJA'));
        $jab = $this->mglobal->get_data_by_id('m_jabatan', 'ID_JABATAN', $this->input->post('JABATAN'));

        $data_temp = array('NIK' => $this->input->post('NIK'),
            'NIP_NRP' => $this->input->post('NIP_NRP'),
            'NAMA' => $this->input->post('NAMA'),
            'ID_LEMBAGA' => $this->input->post('INST_TUJUAN'),
            'NAMA_LEMBAGA' => @$inst[0]->INST_NAMA,
            'NAMA_UNIT_KERJA' => @$uk[0]->UK_NAMA,
            'NAMA_SUB_UNIT_KERJA' => @$suk[0]->SUK_NAMA,
            'NAMA_JABATAN' => @$jab[0]->NAMA_JABATAN,
            'EMAIL' => $this->input->post('EMAIL')
        );

        $this->Mt_pn_ws_temp->update($id, $data_temp);

        if ($sts == '1')
            $save = $this->ajax_update_jabpnwl_ws($id, TRUE);
        elseif ($sts == '2')
            $save = $this->ajax_save_add_pnwl_ws($id, TRUE);




        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;

//        redirect('#index.php/ereg/All_ver_pn');
    }

    function cancelUpld() {
        $id = $this->input->post("coo");
        if ($id) {
            $this->Mt_pn_upload_xls_temp->delete($id);
            echo '1';
        } else {
            echo '0';
        }
    }

    /**
     * Fungsi Khusus untuk Halaman Daftar/Update via Excel
     * @param type $id
     * @param type $idjb
     */
    function cancelVerPN($id, $idjb) {
        $this->load->model('Munitkerja');
        $jab = $this->mglobal->get_by_id('t_pn_jabatan', 'ID', $idjb);
        $pn = $this->mglobal->get_by_id('t_pn', 'ID_PN', $id);

        $id_uk = $this->input->post("iduk");
        if (!$id_uk || $id_uk == '-') {
            echo '0';
        } else {

//            $id_unit_kerja_found = $this->Munitkerja->get_uk_id_by_name_and_lembaga_id($jab->LEMBAGA, $jab->UNIT_KERJA);

            $data = array(
                'NIK' => $pn->NIK,
                'NIP_NRP' => $pn->NIP_NRP,
                'NAMA' => $pn->NAMA,
                'JNS_KEL' => $pn->JNS_KEL == 1 ? 'Laki laki' : 'Perempuan',
                'TEMPAT_LAHIR' => $pn->TEMPAT_LAHIR,
                'TGL_LAHIR' => $pn->TGL_LAHIR,
                'ID_LEMBAGA' => $jab->LEMBAGA,
                'NAMA_LEMBAGA' => $jab->NAMA_LEMBAGA,
                'NAMA_UNIT_KERJA' => $jab->UNIT_KERJA,
                'NAMA_SUB_UNIT_KERJA' => $jab->SUB_UNIT_KERJA,
                'NAMA_JABATAN' => $jab->DESKRIPSI_JABATAN,
                'EMAIL' => $pn->EMAIL,
                'JENIS_PROSES' => 3,
                'IS_PROCESSED' => 1,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                'uk_id_detected' => $id_uk,
                'uk_id_temp' => $id_uk
            );

            $result = $this->Mt_pn_upload_xls_temp->save($data);
            echo '1';
        }
        exit;
    }

    function cancelVerPNws($id) {
        $status = array(
            'IS_DELETED' => 1,
            'IS_PROCESSED' => 1,
            'JENIS_PROSES' => 3,
        );
        return $this->mglobal->update_data_('t_pn_ws_temp', $status, 'ID', $id);
    }

    function ApproveNonActPN($id) {
        $status = array(
            'IS_WL' => 0
        );
        return $this->mglobal->update_data_('t_pn_jabatan', $status, 'ID', $id);
    }

    function cancel_nonactxls_new($id, $idj) {
        $this->db->trans_begin();
        $dt_pn = array(
            'IS_ACTIVE' => 1
        );
        $dt_pn_jab = array(
            'IS_DELETED' => 0,
            'IS_WL' => 1
        );
        $this->mglobal->update_data_('t_pn_jabatan', $dt_pn_jab, 'ID', $idj);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;
    }

    /**
     * Fungsi Khusus untuk Halaman Verifikasi Data Excel
     * @param type $id
     * @param type $idjb
     */
    function cancel_nonactxls($id, $idjb) {
        $this->load->model('Munitkerja');
        $jab = $this->mglobal->get_by_id('t_pn_jabatan', 'ID', $idjb);
        $pn = $this->mglobal->get_by_id('t_pn', 'ID_PN', $id);

        $id_uk = $this->input->post("iduk");
        if (!$id_uk || $id_uk == '-') {
            echo '0';
        } else {

//            $id_unit_kerja = $this->Munitkerja->get_uk_id_by_name_and_lembaga_id($jab->LEMBAGA, $jab->UNIT_KERJA);
//            $id_unit_kerja = $this->Munitkerja->get_uk_id_by_name_and_lembaga_id($jab->LEMBAGA, $id_uk);

            $data = array(
                'NIK' => $pn->NIK,
                'NIP_NRP' => $pn->NIP_NRP,
                'NAMA' => $pn->NAMA,
                'JNS_KEL' => $pn->JNS_KEL == 1 ? 'Laki laki' : 'Perempuan',
                'TEMPAT_LAHIR' => $pn->TEMPAT_LAHIR,
                'TGL_LAHIR' => $pn->TGL_LAHIR,
                'ID_LEMBAGA' => $jab->LEMBAGA,
                'NAMA_LEMBAGA' => $jab->NAMA_LEMBAGA,
                'NAMA_UNIT_KERJA' => $jab->UNIT_KERJA,
                'NAMA_SUB_UNIT_KERJA' => $jab->SUB_UNIT_KERJA,
                'NAMA_JABATAN' => $jab->DESKRIPSI_JABATAN,
                'EMAIL' => $pn->EMAIL,
                'JENIS_PROSES' => 3,
                'IS_PROCESSED' => 1,
                'IS_DELETE' => 0,
                'UK_ID_DETECTED' => $id_uk,
                'UK_ID_TEMP' => $id_uk,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            );

            $result = $this->Mt_pn_upload_xls_temp->save($data);
            echo '1';
        }
        exit;
    }

    function cancel_nonactws($id, $idjb) {
        $jab = $this->mglobal->get_by_id('t_pn_jabatan', 'ID', $idjb);
        $pn = $this->mglobal->get_by_id('t_pn', 'ID_PN', $id);

        $data = array(
            'NIK' => $pn->NIK,
            'NIP_NRP' => $pn->NIP_NRP,
            'NAMA' => $pn->NAMA,
            'JNS_KEL' => $pn->JNS_KEL == 1 ? 'Laki laki' : 'Perempuan',
            'TEMPAT_LAHIR' => $pn->TEMPAT_LAHIR,
            'TGL_LAHIR' => $pn->TGL_LAHIR,
            'ID_LEMBAGA' => $jab->LEMBAGA,
            'NAMA_LEMBAGA' => $jab->NAMA_LEMBAGA,
            'NAMA_UNIT_KERJA' => $jab->UNIT_KERJA,
            'NAMA_SUB_UNIT_KERJA' => $jab->SUB_UNIT_KERJA,
            'NAMA_JABATAN' => $jab->DESKRIPSI_JABATAN,
            'EMAIL' => $pn->EMAIL,
            'JENIS_PROSES' => 3,
            'IS_PROCESSED' => 1,
            'CREATED_TIME' => time(),
            'CREATED_BY' => $this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
        );

        $result = $this->Mt_pn_ws_temp->save($data);
    }

    function DeleteTempXls($id) {
        return $this->Mt_pn_upload_xls_temp->delete($id);
    }

    function DeleteTempWS($id) {
        return $this->Mt_pn_ws_temp->delete($id);
    }

    function kirim_email($via = NULL) {

        $subject = 'Informasi Verifikasi PN/WL (e-lhkpn)';
        $this->load->model('Minstansi', '', TRUE);
        $instansi = $this->Minstansi->get_by_id($this->instansi)->row();

        if ($via)
            $list_mail = $this->Mt_pn_ws_temp->get_EmailUserUK($this->instansi);
        else
            $list_mail = $this->Mt_pn_upload_xls_temp->get_EmailUserUK($this->instansi);

        display($this->db->last_query());

        $this->db->trans_begin();

        $arr_mail = array();
        foreach ($list_mail as $row):
            $arr_mail[] = $row->EMAIL;
            $usr_name = $row->ID_USER;

            if ($via)
                $data_pn_temp = $this->Mt_pn_ws_temp->get_DataTempIns($this->instansi, $row->USERNAME);
            else
                $data_pn_temp = $this->Mt_pn_upload_xls_temp->get_DataTempIns($this->instansi, $row->USERNAME);


            $email = $arr_mail;
            $message = '
           <html>
            <head>
            <style>
             table {
                    border-collapse: collapse;
                    width: 100%;
                }
                th, td {
                    text-align: left;
                    padding: 8px;
                    border-bottom: 1px solid #ddd;
                }

                tr:nth-child(even){background-color: #f2f2f2}

                th {
                    background-color: #4CAF50;
                    color: white;
                }
                tr:hover{background-color:#f5f5f5}
                        </style>
                        </head>
                        <body>
                 <div>
                 <table>
            <tr>
            <td><H1>Selamat datang di Aplikasi KPK</H1>
		<H3>Email ini berisi informasi data verifikasi.</H3><td>
            <td><img style="width:75%;" src="http://www.interisti.club/wp-content/uploads/2016/04/logolhkpn-kecil.png" ><td>
            </tr>                 
            </table>
		<h4>Berikut data verifikasi Penyelenggara Negara/ Wajib Lapor  <h4>
                <div >
                <table>
                <tr >
                <th>NO</th>
                <th>NIK</th>
                <th>NIP</th>
                <th>NAMA</th>
                <th>UNIT KERJA</th>
                <th>SUB UNIT KERJA</th>
                <th>JABATAN</th>
                <th>STATUS</th>
                </tr>
                ';
            $i = 0;
            foreach ($data_pn_temp as $row):
                $i++;
                $message .= '
                <tr>
                <td><small>' . $i . '</td>                
                <td><small>' . $row->NIK . '</td>                
                <td><small>' . $row->NIP_NRP . '</td>                
                <td><small>' . $row->NAMA . '</td>                
                <td><small>' . $row->NAMA_UNIT_KERJA . '</td>                
                <td><small>' . $row->NAMA_SUB_UNIT_KERJA . '</td>                
                <td><small>' . $row->NAMA_JABATAN . '</td>                
                <td><small><b>' . $row->STS . '</td>                
                
                </tr>';

            endforeach;

            $message .= '
                </table>
		</div>
		<div><br>
		</div>
		<div>Informasi Ini disampaikan oleh admin  ' . $instansi->INST_NAMA . '</div>
		<div>
		</div> 		<br> 		<br> 		<br> 		<br> 		<br> 		<br>
		<div><a href="' . base_url() . '" target="_blank">' . $instansi->INST_NAMA . '</a><br/>
		<div>Terima Kasih</div>
		</div>
		';


            //ng::mail_send($row->EMAIL, $subject, $message);
            $data['ID_PENGIRIM'] = $this->session->userdata('ID_USER');
            $data['ID_PENERIMA'] = $usr_name;
            $data['SUBJEK'] = $subject;
            $data['PESAN'] = $message;
            $data['FILE'] = '';
            $data['TANGGAL_KIRIM'] = date('Y-m-d H:i:s');
            $data['IS_ACTIVE'] = '1';
            $result = $this->mglobal->insert('T_PESAN_KELUAR', $data);

            if ($result) {
                $data2 = array(
                    'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
                    'ID_PENERIMA' => $usr_name,
                    'SUBJEK' => $subject,
                    'PESAN' => $message,
                    'FILE' => '',
                    'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                    'IS_ACTIVE' => '1'
                );
                $this->mglobal->insert('T_PESAN_MASUK', $data2);
            }

            //echo $message.'<br>';


        endforeach;
//        $id = $row->INST_SATKERKD;
        if ($via)
            $this->Mt_pn_ws_temp->deletedata($this->instansi);
        else
            $this->Mt_pn_upload_xls_temp->deletedata($this->instansi);


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;


        echo $res;
    }

}
