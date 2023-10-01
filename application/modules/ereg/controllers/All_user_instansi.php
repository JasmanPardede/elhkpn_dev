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
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class All_user_instansi extends MY_Controller {

    // num of records per page
    public $limit = 10;

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model(array('mglobal', 'muser'));
        $this->makses->initialize();
        $this->makses->check_is_read();
        $this->instansi = $this->session->userdata('INST_SATKERKD');
    }

    /** User List
     * 
     * @return html User List
     */
    public function index($offset = 10) {
        // load model
        $this->load->model('minstansi');

        // prepare paging
//            $this->base_url	 = site_url('/ereg/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
//            $this->uri_segment  = 4;
//            $this->offset	   = $this->uri->segment($this->uri_segment);
        // filter
        $cari = '';
        $filter = array();
        $id_instansi = '';

        if ($_POST && $this->input->post('cari', TRUE) != '') {
            $cari = $this->input->post('cari', TRUE);
            $filter = array(
                'USERNAME' => $this->input->post('cari', TRUE),
                'NAMA' => $this->input->post('cari', TRUE),
                'EMAIL' => $this->input->post('cari', TRUE),
                'NIP' => $this->input->post('cari', TRUE),
                    // 'INST_SATKERKD' => $id_instansi
            );
        }
        
        $default_instansi = $this->instansi;
        if ($_POST && $this->input->post('INST', TRUE) != '') {
            if ($this->input->post('INST', TRUE) != '99') {
                $id_instansi = $this->input->post('INST', TRUE);
                $default_instansi = $this->input->post('INST', TRUE);
            }
        }
        // $filter['INST_SATKERKD'] = '';
        // echo "<pre>";
        // print_r ($filter);
        // echo "</pre>";exit();
        // if($_POST && ($this->input->post('cari', TRUE)!='' || $this->input->post('INST', TRUE)!= 99)){
        // 	$cari = $this->input->post('cari', TRUE);
        // $id_instansi = $this->input->post('INST', TRUE);
        // 	$filter = array(
        // 'USERNAME' => $this->input->post('cari', TRUE),
        // 'NAMA' => $this->input->post('cari', TRUE),
        // 'EMAIL' => $this->input->post('cari', TRUE),
        // 'INST_SATKERKD' => $id_instansi
        // 	);			
        // }

        if ($this->is_instansi() != false) {
            $id_instansi = $this->is_instansi();
        }

        // load and packing data
//        $this->items = $this->muser->get_list_userpokja($this->limit, $this->offset, $filter, $id_instansi)->result();
        // echo $this->db->last_query();exit;    
//        $this->total_rows   = $this->muser->count_all($filter, $id_instansi, ID_ROLE_UI);
        // echo $this->db->last_query();exit;    

        $data = array(
//            'items'         => $this->items,
//            'total_rows'    => $this->total_rows,
            'offset' => $this->offset,
            'cari' => $cari,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E Registration' => 'index.php/dashboard/eregistration',
                'User Instansi' => 'index.php/all_user_instansi/index',
            )),
//            'pagination'    => call_user_func('ng::genPagination'),
            'instansis' => $this->minstansi->list_all()->result(),
            'inst_satkerkd' => $id_instansi,
            'inst' => $this->is_instansi(),
            'default_instansi' => $default_instansi,
        );

        // load view
        $this->load->view('all_user_instansi_index', $data);
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

    function load_data_index() {
        // load model
        $this->load->model(array('muser', 'minstansi'));

        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $query = $this->muser->get_list_userpokja_two($this->instansi, $currentpage, $keyword, $rowperpage, true, $this->role);
        $queryCount = $this->muser->get_list_userpokja_two($this->instansi, $currentpage, $keyword, $rowperpage, false, $this->role);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($queryCount->num_rows()),
            "iTotalDisplayRecords" => intval($queryCount->num_rows()),
            "aaData" => $query->result()
        );
        $this->to_json($dtable_output);
    }

    /** Process Insert, Update, Delete User
     * 
     * @return boolean process User
     */
    private function valid_form() {
        $this->load->library('form_validation');
        $config = array(
            array(
                'field' => 'USERNAME',
                'label' => 'Username',
                'rules' => 'required|xss_clean'
            ),
            array(
                'field' => 'NAMA',
                'label' => 'Nama',
                'rules' => 'xss_clean'
            ),
            array(
                'field' => 'EMAIL',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            )
        );
        $this->form_validation->set_rules($config);
    }

    function saveuser() {

        $form_act = $this->input->post('act', TRUE);

        $detected_role = ID_ROLE_UI;
        $detected_destination_inst = $this->input->post('INST_TUJUAN', TRUE);

        if ($form_act == 'dodelete') {
            if (!$this->input->post('ID_USER', TRUE)) {
                show_404();
                exit;
            }
            $user_info = $this->muser->get_by_id($this->input->post('ID_USER', TRUE))->row();
            $detected_role = $user_info->ID_ROLE;
            $detected_destination_inst = $user_info->INST_SATKERKD;
            unset($user_info);
        }

        if (!$this->__check_has_action($detected_role, $detected_destination_inst)) {
            show_404();
            exit;
        }

        $this->load->library('form_validation');
        $this->makses->check_is_write();
        $this->db->trans_begin();
        $this->load->model('muser', '', TRUE);

        if ($form_act == 'doinsert') {
            $this->valid_form();
            if ($this->form_validation->run() == FALSE) {
                //exit('0');
            }

            $username = $this->input->post('USERNAME', TRUE);

            if (!empty($_POST['UNIT_KERJA'])) {
                $id_UK = $_POST['UNIT_KERJA'];
            }

            if (empty($_POST['UNIT_KERJA'])) {
                $id_UK = null;
            }

            $check = $this->muser->check_user_if_exist($username);
            if ($cek_user > 0) {
                echo 0;
                die;
            }

            $jenis_user = $this->input->post('jenis_user') == 2 ? 1 : 0;

            $password = $this->muser->createRandomPassword(6);
            $user = array(
                'USERNAME' => $username,
                'NAMA' => $this->input->post('NAMA', TRUE),
                'EMAIL' => $this->input->post('EMAIL', TRUE),
                'HANDPHONE' => $this->input->post('HANDPHONE', TRUE),
                'PASSWORD' => sha1(md5($password)),
                'IS_ACTIVE' => '0',
                'UNIT_KERJA_ADMIN' => $this->input->post('UNIT_KERJA_ADMIN', TRUE),
                'SUB_UNIT_KERJA_ADMIN' => $this->input->post('SUB_UNIT_KERJA_ADMIN', TRUE),
                'JABATAN_ADMIN' => $this->input->post('JABATAN_ADMIN', TRUE),
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                'ID_ROLE' => $detected_role,
                // 'INST_SATKERKD'     => $this->input->post('INST_SATKERKD', TRUE),
                'INST_SATKERKD' => $this->input->post('INST_TUJUAN', TRUE),
                'UK_ID' => $id_UK,
                'IS_UK' => $jenis_user,
                    // 'UPDATED_TIME'     => time(),
                    // 'UPDATED_BY'     => $this->session->userdata('USR'),
                    // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],
            );
            $simpan = $this->muser->save($user);
            if ($simpan) {
                $this->muser->old_kirim_info_akun($this->input->post('EMAIL', TRUE), $this->input->post('USERNAME', TRUE), $password);
                ng::logActivity('Admin Instansi '. $this->session->userdata('USR') .' menambahkan admin unit kerja ' . $username);
            }
        } else if ($form_act == 'doupdate') {
            $this->valid_form();
            if ($this->form_validation->run() == FALSE) {
                echo 0;
                die;
            }
            if (!empty($_POST['UNIT_KERJA'])) {
                $id_UK = $_POST['UNIT_KERJA'];
            }

            if (empty($_POST['UNIT_KERJA'])) {
                $id_UK = null;
            }
            
            $username = $this->input->post('USERNAME', TRUE);
            $jenis_user = $this->input->post('jenis_user') == 2 ? 1 : 0;
            
            $user = array(
                'USERNAME' => $this->input->post('USERNAME', TRUE), // tidak boleh di edit
                'NAMA' => $this->input->post('NAMA', TRUE), // tidak boleh di edit
                'EMAIL' => $this->input->post('EMAIL', TRUE),
                'HANDPHONE' => $this->input->post('HANDPHONE', TRUE),
                'UNIT_KERJA_ADMIN' => $this->input->post('UNIT_KERJA_ADMIN', TRUE),
                'SUB_UNIT_KERJA_ADMIN' => $this->input->post('SUB_UNIT_KERJA_ADMIN', TRUE),
                'JABATAN_ADMIN' => $this->input->post('JABATAN_ADMIN', TRUE),
                //'IS_ACTIVE'     => $this->input->post('IS_ACTIVE', TRUE),
                //'INST_SATKERKD'     => $this->input->post('INSTANSI', TRUE),
                // 'ID_ROLE'     => ID_ROLE_UI, // user gag bisa diupdate rolenya
                // 'INST_SATKERKD'     => $this->session->userdata('INST_SATKERKD'), // user gag bisa diupdate rolenya
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'INST_SATKERKD' => $this->input->post('INST_TUJUAN', TRUE),
                'UK_ID' => $id_UK,
                'IS_UK' => $jenis_user,
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );

            $user['ID_USER'] = $this->input->post('ID_USER', TRUE);
            $this->muser->update($user['ID_USER'], $user);
            ng::logActivity('Admin Instansi '. $this->session->userdata('USR') .' mengupdate admin unit kerja ' . $username);
        } else if ($form_act == 'dodelete') {
            $username = $this->input->post('USERNAME', TRUE);
            $user['ID_USER'] = $this->input->post('ID_USER', TRUE);
            $this->load->model('mglobal');
            $this->mglobal->update('T_USER', ['IS_ACTIVE' => '-1'], ['ID_USER' => $user['ID_USER']]);
            ng::logActivity('Admin Instansi '. $this->session->userdata('USR') .' menghapus admin unit kerja ' . $username);
//            $this->muser->delete($user['ID_USER']);
        } else if ($form_act == 'doactive'){
            $username = $this->input->post('USERNAME', TRUE);
            $user['ID_USER'] = $this->input->post('ID_USER', TRUE);
            $this->load->model('mglobal');
            $this->mglobal->update('T_USER', ['IS_ACTIVE' => '1'], ['ID_USER' => $user['ID_USER']]);
            ng::logActivity('Admin Instansi '. $this->session->userdata('USR') .' mengaktifkan admin unit kerja ' . $username);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah User
     * 
     * @return html form tambah User
     */
    function adduser() {
        // $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form' => 'add',
            'instansis' => $this->minstansi->list_all()->result(),
            'is_instansi' => $this->is_instansi()
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    function cek_user($username, $id = null) {
        $this->load->model('muser', '', TRUE);
        if ($id) {
            $this->db->where('ID_USER !=', $id);
            $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
            $this->db->where('USERNAME', $username);
            $check = $this->db->get('t_user')->row();
        } else {
            $check = $this->muser->check_user_if_exist($username);
        }
        if (!empty($check)) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function cek_user_edit($username, $current_username) {
        $this->load->model('muser', '', TRUE);
        $check = $this->muser->check_user_for_edit($username, $current_username);
        if (!empty($check)) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function cek_email($email, $id = NULL) {
        $decode = urldecode($email);
        $check = null;
        if ($id) {
            $this->db->where('EMAIL', $decode);
            $this->db->where('ID_USER !=', $id);
            $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
            $check = $this->db->get('t_user')->result();
        } else {
            $this->db->where('EMAIL', $decode);
            $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
            $this->db->limit(1);
            $check = $this->db->get('t_user')->row();
        }
        if (!$this->isValidEmail($decode)) {
            echo "0";
        } else {
            if ($check) {
                echo "1";
            } else {
                echo "2";
            }
        }
    }

    /** Form Edit User
     * 
     * @return html form edit User
     */
    function edituser($id) {
        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $user = $this->muser->get_by_id($id)->row();
        $unit_kerja = NULL;
        if ($user->UK_ID) {
            $this->db->where('UK_ID', $user->UK_ID);
            $unit_kerja = $this->db->get('m_unit_kerja')->row();
        }
        $this->db->where('INST_SATKERKD', $user->INST_SATKERKD);
        $instansi = $this->db->get('m_inst_satker')->row();
        $data = array(
            'form' => 'edit',
            'instansis' => $this->minstansi->list_all()->result(),
            'item' => $user,
            'unit_kerja' => $unit_kerja,
            'instansi' => $instansi,
            'is_instansi' => $this->is_instansi()
        );

        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    /** Form Konfirmasi Hapus User
     * 
     * @return html form konfirmasi hapus User
     */
    function deleteuser($id) {
        // $this->makses->check_is_write();
        $user_info = $this->muser->get_by_id($id)->row();

        if (!$user_info || !$this->__check_has_action($user_info->ID_ROLE, $user_info->INST_SATKERKD)) {
            show_404();
            exit;
        }

        $data = array(
            'form' => 'delete',
            'item' => $user_info,
        );
        $this->load->view('all_user_instansi_form', $data);
    }
    
    /** Form Konfirmasi Aktifkan User
     * 
     * @return html form konfirmasi Aktifkan User
     */
    function activeuser($id) {
        // $this->makses->check_is_write();
        $user_info = $this->muser->get_by_id($id)->row();

        if (!$user_info || !$this->__check_has_action($user_info->ID_ROLE, $user_info->INST_SATKERKD)) {
            show_404();
            exit;
        }

        $data = array(
            'form' => 'active',
            'item' => $user_info,
        );
        $this->load->view('all_user_instansi_form', $data);
    }

    /** Detail User
     * 
     * @return html detail User
     */
    function detailuser($id) {
        $this->load->model('muser', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->muser->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    function aktivitas($offset = 0) {
        $this->index($offset);
        return;
        $data = '';
        $this->load->view(strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    function pokja($offset = 0) {
        echo 'Pokja';
        $this->index($offset);
    }

    function pejabat($offset = 0) {
        echo 'Pejabat';
        $this->index($offset);
    }

    function reset_password($id_user) {
        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $data = array(
            'form' => 'reset_password',
            'item' => $this->muser->get_by_id($id_user)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    function kirimaktivasi($id) {
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form' => 'kirimaktivasi',
            'item' => $this->muser->get_by_id($id)->row(),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );

        // print_r($data['item']);
        // $data['ROLE'] = $this->mrole->get_by_id($data['item']->ID_ROLE)->row()->ROLE;

        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    /**
     * @deprecated since 27-April-2017
     */
    function __do_reset_password() {
        $id_user = $this->input->post('ID_USER');
        $this->makses->check_is_write();
        $this->load->model('muser');
        $password = $this->muser->createRandomPassword(6);
        $data_user = $this->muser->get_by_id($id_user)->row();
        //$kirim_info = $this->muser->kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $password, 'Pemberitahuan Reset Password');
        $kirim_info = $this->muser->kirim_info_lupa_password($data_user->EMAIL, $data_user->USERNAME, $password);
        if ($kirim_info) {
            $data_update = array(
                'password' => sha1(md5($password)),
                'IS_FIRST' => '1',
            );
            $update = $this->muser->update($id_user, $data_update);
            echo 1;
        } else {
            echo 0;
        }
    }

    /*
      private function is_instansi(){
      $idRole = $this->session->userdata('ID_ROLE');
      $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_INSTANSI, IS_USER_INSTANSI');

      if(!empty($role)){
      $inst   = $role[0]->IS_INSTANSI;
      $user   = $role[0]->IS_USER_INSTANSI;

      if($inst == '1' || $user == '1'){
      $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
      return $INST_SATKERKD;
      }else{
      return false;
      }
      }else{
      return false;
      }
      }
     */
}
