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
 * @author Arif Kurniawan - PT.Mitreka Solusi Indonesia
 * @package Controllers
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_instansi extends MY_Controller {

    // num of records per page
    public $limit = 10;

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->load->model('mglobal');
        // $this->makses->check_is_read();
    }

    /** User List
     * 
     * @return html User List
     */
    public function index($offset = 0) {
        // load model
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);

        // prepare paging
        // $this->base_url	   = site_url('/ereg/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
        // $this->uri_segment = 4;
        // $this->offset	   = $this->uri->segment($this->uri_segment);
        // filter
        $cari = '';
        $filter = array();
        $instansi = '';
        if ($_POST && $this->input->post('cari', TRUE) != '') {
            $cari = $this->input->post('cari', TRUE);
            $filter = array(
                'USERNAME' => $this->input->post('cari', TRUE),
                'EMAIL' => $this->input->post('cari', TRUE),
                'NAMA' => $this->input->post('cari', TRUE),
            );
        }
        if ($_POST && $this->input->post('INST', TRUE) != '') {
            if ($this->input->post('INST', TRUE) != '99') {
                $instansi = $this->input->post('INST', TRUE);
                // $filter['INST_SATKERKD'] = $this->input->post('INST', TRUE);
            }
        }

        // load and packing data
        // $this->items        = $this->muser->get_list_admininstansi($this->limit, $this->offset, $filter, $instansi)->result();
        // $this->total_rows   = $this->muser->count_all($filter, $instansi, ID_ROLE_AI);
        // $this->instansis    = $this->minstansi->list_all()->result();

        $data = array(
            // 'items'         => $this->items,
            // 'total_rows'    => $this->total_rows,
            'offset' => $this->offset,
            'cari' => $cari,
            'instansis' => $this->instansis,
            'instansi' => $instansi,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E Registration' => 'index.php/dashboard/eregistration',
                'Admin Instansi' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
                // 'pagination'	=> call_user_func('ng::genPagination'),
        );

        // load view
        $this->load->view(strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
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

        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);

        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        $query = $this->muser->get_list_admininstansi_two($this->instansi, $currentpage, $keyword, $rowperpage, true);
//echo $this->db->last_query();exit;
        $queryCount = $this->muser->get_list_admininstansi_two($this->instansi, $currentpage, $keyword, $rowperpage, false);
        
        
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
                'rules' => 'required|valid_email|xss_clean'
            )
        );
        $this->form_validation->set_rules($config);
    }

    function cek_user($username) {
        $this->load->model('muser', '', TRUE);
        $cek_user = count($this->muser->cek_user($username));
        if ($cek_user > 0) {
            echo 'ada';
        } else {
            echo 'kosong';
        }
    }

    //  function cek_user($username,$id=null){
    //     $this->load->model('muser', '', TRUE);
    //     if($id){
    //          $this->db->where('ID_USER !=',$id);
    //          $this->db->where('IS_ACTIVE', '1');
    //          $this->db->where('USERNAME',$username);
    //          $check = $this->db->get('t_user')->row();
    //     }else{
    //          $check = $this->muser->check_user_if_exist($username);
    //     }
    //     if(!empty($check)){
    //         echo '1';
    //     }else{
    //         echo '0';
    //     }
    // }


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

    function saveuser() {
        $this->makses->check_is_write();
        $this->db->trans_begin();
        $this->load->model('muser', '', TRUE);
        $this->load->model('mglobal', '', TRUE);

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $username = $this->input->post('USERNAME', TRUE);
            $cek_user = count($this->muser->check_user_if_exist($username));
            if ($cek_user > 0) {
                echo 0;
                exit();
            }
            $this->valid_form();
            if ($this->form_validation->run() == FALSE) {
                exit('0');
            }
            $password = $this->muser->createRandomPassword(6);
            $user = array(
                'USERNAME' => $this->input->post('USERNAME', TRUE),
                'NAMA' => $this->input->post('NAMA', TRUE),
                'EMAIL' => $this->input->post('EMAIL', TRUE),
                'NOMOR_SK' => $this->input->post('NOMOR_SK', TRUE),
                'HANDPHONE' => $this->input->post('HANDPHONE', TRUE),
                'PASSWORD' => sha1(md5($password)),
                'IS_ACTIVE' => '0',
                'UNIT_KERJA_ADMIN' => $this->input->post('UNIT_KERJA_ADMIN', TRUE),
                'SUB_UNIT_KERJA_ADMIN' => $this->input->post('SUB_UNIT_KERJA_ADMIN', TRUE),
                'JABATAN_ADMIN' => $this->input->post('JABATAN_ADMIN', TRUE),
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                'ID_ROLE' => ID_ROLE_AI,
                'INST_SATKERKD' => $this->input->post('INST_SATKERKD', TRUE),
            );
            $this->muser->save($user);
//            echo $this->db->last_query();
            ng::logActivity('Tambah Admin Instansi Untuk, username = ' . $this->input->post('USERNAME', TRUE));
            $this->muser->old_kirim_info_akun($this->input->post('EMAIL', TRUE), $this->input->post('USERNAME', TRUE), $password);
//            echo $this->db->last_query();exit;
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            // $this->valid_form();
            // if ( $this->form_validation->run() == FALSE ) {
            //     exit('0');
            // }
            $user = array(
                // 'USERNAME' 		=> $this->input->post('USERNAME', TRUE), // tidak boleh di edit
                // 'NAMA' 		    => $this->input->post('NAMA', TRUE), // tidak boleh diedit
                'EMAIL' => $this->input->post('EMAIL', TRUE),
                'NOMOR_SK' => $this->input->post('NOMOR_SK', TRUE),
                'HANDPHONE' => $this->input->post('HANDPHONE', TRUE),
                // 'IS_ACTIVE'     => $this->input->post('IS_ACTIVE', TRUE),
                'INST_SATKERKD' => $this->input->post('INST_SATKERKD', TRUE),
                // 'ID_ROLE'     => ID_ROLE_AI, // user gag bisa diupdate rolenya
                // 'INST_SATKERKD'     => $this->session->userdata('INST_SATKERKD'), // user gag bisa diupdate rolenya
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UNIT_KERJA_ADMIN' => $this->input->post('UNIT_KERJA_ADMIN', TRUE),
                'SUB_UNIT_KERJA_ADMIN' => $this->input->post('SUB_UNIT_KERJA_ADMIN', TRUE),
                'JABATAN_ADMIN' => $this->input->post('JABATAN_ADMIN', TRUE),
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $user['ID_USER'] = $this->input->post('ID_USER', TRUE);
            //@TODO bypas dlu
//            $check_email        = $this->mglobal->get_data_all('T_USER', null, ['ID_USER !=' => $user['ID_USER'], 'EMAIL' => $user['EMAIL'], 'IS_ACTIVE' => '1'], 'ID_USER');
//            if (count($check_email) == 0) {
            $this->muser->update($user['ID_USER'], $user);
            ng::logActivity('Update Admin Instansi Untuk, username = ' . $this->input->post('USERNAME', TRUE));
//            } else {
//                echo 0; exit();
//            }
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            // $this->valid_form();
            // if ( $this->form_validation->run() == FALSE ) {
            //     exit('0');
            // }
            $user = array(
                'IS_ACTIVE' => '-1',
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $user['ID_USER'] = $this->input->post('ID_USER', TRUE);
            $this->muser->update($user['ID_USER'], $user);
            ng::logActivity('Hapus Admin Instansi Untuk, username = ' . $this->input->post('USERNAME', TRUE));
        } else if ($this->input->post('act', TRUE) == 'doresetpassword') {
            $id_user = $this->input->post('ID_USER');
            $this->makses->check_is_write();
            $this->load->model('muser');
            $password = $this->muser->createRandomPassword(6);
            $data_user = $this->muser->get_by_id($id_user)->row();
            //$kirim_info = $this->muser->kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $password, 'Pemberitahuan Reset Password');
            $kirim_info = $this->muser->kirim_info_lupa_password($data_user->EMAIL, $data_user->USERNAME, $password);
            if ($kirim_info) {
                $data_update = array(
                    'password' => sha1(md5($password))
                );
                $update = $this->muser->update($id_user, $data_update);
                ng::logActivity('Reset Password Untuk Admin Instansi dengan, username = ' . $this->input->post('USERNAME', TRUE));
            } else {
                echo 0;
                exit();
            }
        } else if ($this->input->post('act', TRUE) == 'doactive'){
            $user = array(
                'IS_ACTIVE' => '1',
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $user['ID_USER'] = $this->input->post('ID_USER', TRUE);
            $this->muser->update($user['ID_USER'], $user);
            ng::logActivity('Aktifkan Admin Instansi Untuk, username = ' . $this->input->post('USERNAME', TRUE));
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    public function doresetpassword() {
        parent::do_reset_password();
    }

    /**
     * @deprecated since 27 April 2017
     */
    function __doresetpassword() {
        $id_user = $this->input->post('ID_USER');
        if (!empty($id_user)) {
            $this->makses->check_is_write();
            $this->load->model('muser');
            $password = $this->muser->createRandomPassword(6);
            $data_user = $this->muser->get_by_id($id_user)->row();
            //$kirim_info = $this->muser->kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $password, 'Pemberitahuan Reset Password');
            $kirim_info = $this->muser->kirim_info_lupa_password($data_user->EMAIL, $data_user->USERNAME, $password);
            if ($kirim_info) {
                $data_update = array(
                    'password' => sha1(md5($password)),
                    'IS_FIRST' => '1'
                );
                $update = $this->muser->update($id_user, $data_update);
//                echo $this->db->last_query();
                ng::logActivity('Reset Password Untuk Admin Instansi dengan, id_user = ' . $this->input->post('ID_USER', TRUE));
                echo 1;
            } else {
                echo 0;
                exit();
            }
        } else {
            echo '0';
        }
    }

    /** Form Tambah User
     * 
     * @return html form tambah User
     */
    function adduser() {
        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'add',
            'list_instansi' => $list_instansi,
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    function resetpassword($id) {
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form' => 'resetpassword',
            'item' => $this->muser->get_by_id($id)->row(),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );

        // print_r($data['item']);
        // $data['ROLE'] = $this->mrole->get_by_id($data['item']->ID_ROLE)->row()->ROLE;

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

    /** Form Edit User
     * 
     * @return html form edit User
     */
    function edituser($id) {
        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'edit',
            'item' => $this->muser->get_by_id($id)->row(),
            'list_instansi' => $list_instansi,
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    /** Form Konfirmasi Hapus User
     * 
     * @return html form konfirmasi hapus User
     */
    function deleteuser($id) {
        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'delete',
            'item' => $this->muser->get_by_id($id)->row(),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }
    
    /** Form Konfirmasi Active User
     * 
     * @return html form konfirmasi active User
     */
    function activeuser($id) {
        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'active',
            'item' => $this->muser->get_by_id($id)->row(),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    /** Detail User
     * 
     * @return html detail User
     */
    function detailuser($id) {
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form' => 'detail',
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
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

    public function getInst($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];

            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, NULL, 'INST_SATKERKD,INST_NAMA', "INST_NAMA LIKE '%$q%'", NULL, NULL, '15');

            $res[] = ['id' => '', 'name' => '-SELECT ALL-'];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['INST_SATKERKD' => $id];

            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }
            $res[] = ['id' => '', 'name' => '-SELECT ALL-'];
            echo json_encode($res);
        }
    }

}
