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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_instansi extends CI_Controller {
	// num of records per page
	public $limit = 10;
    
    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->makses->check_is_read();
    }

    /** User List
     * 
     * @return html User List
     */
	public function index($offset = 0)
	{
		// load model
		$this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);

		// prepare paging
		$this->base_url	 = site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
		$this->uri_segment  = 3;
		$this->offset	   = $this->uri->segment($this->uri_segment);
		
		// filter
		$cari             = '';
		$filter		     = array();
		if($_POST && $this->input->post('cari', TRUE)!=''){
			$cari = $this->input->post('cari', TRUE);
			$filter = array(
                'NAMA' => $this->input->post('cari', TRUE),
                'EMAIL' => $this->input->post('cari', TRUE),
			);			
		}

		// load and packing data
        $this->items		= $this->muser->get_userpokja_by_instansi($this->limit, $this->offset, $filter, $this->session->userdata('INST_SATKERKD'))->result();
		$this->total_rows   = $this->muser->count_all($filter, $this->session->userdata('INST_SATKERKD'), ID_ROLE_UI);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => $cari,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
									'E Registration'     => 'index.php/dashboard/eregistration',
									'User Instansi'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
            'instansis'     => $this->minstansi->list_all()->result(),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
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
    function saveuser(){
        $this->load->library('form_validation');
        $this->makses->check_is_write();
        $this->db->trans_begin();
        $this->load->model('muser', '', TRUE);
        $this->load->model('mglobal', '', TRUE);

        if($this->input->post('act', TRUE)=='doinsert'){
            $this->valid_form();
            if ( $this->form_validation->run() == FALSE ) {
                //exit('0');
            }

            $username = $this->input->post('USERNAME', TRUE);

            $check = $this->muser->check_user_if_exist($username);
            if(!empty($check)){
                echo 0;
                die;
            }

            $password = $this->muser->createRandomPassword(6);
            $user = array(
                'USERNAME' 		    => $username,
                'NAMA' 		        => $this->input->post('NAMA', TRUE),
                'EMAIL'             => $this->input->post('EMAIL', TRUE),
                'HANDPHONE' 		    => $this->input->post('HANDPHONE', TRUE),
                'PASSWORD' 		    => sha1(md5($password)),
                'IS_ACTIVE' 		=> '1',
                'CREATED_TIME'      => time(),
                'CREATED_BY'        => $this->session->userdata('USR'),
                'CREATED_IP'        => $_SERVER["REMOTE_ADDR"],
                'ID_ROLE'           => ID_ROLE_UI,
                'INST_SATKERKD'     => $this->session->userdata('INST_SATKERKD'),
                // 'UPDATED_TIME'     => time(),
                // 'UPDATED_BY'     => $this->session->userdata('USR'),
                // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],
            );
            $this->muser->save($user);
            $this->muser->kirim_info_akun($this->input->post('EMAIL', TRUE), $this->input->post('USERNAME', TRUE), $password);
        }else if($this->input->post('act', TRUE)=='doupdate'){
            $this->valid_form();
            if ( $this->form_validation->run() == FALSE ) {
                echo 0;
                die;
            }
            $user = array(
                'USERNAME' 		=> $this->input->post('USERNAME', TRUE),
                'NAMA' 		    => $this->input->post('NAMA', TRUE),
                'EMAIL' 		=> $this->input->post('EMAIL', TRUE),
                'HANDPHONE'             => $this->input->post('HANDPHONE', TRUE),
                // 'IS_ACTIVE'      => '1',
                // 'ID_ROLE'     => ID_ROLE_UI, // user gag bisa diupdate rolenya
                // 'INST_SATKERKD'     => $this->session->userdata('INST_SATKERKD'), // user gag bisa diupdate rolenya
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME'     => time(),
                'UPDATED_BY'     => $this->session->userdata('USR'),
                'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"], 
            );
            $user['ID_USER']    = $this->input->post('ID_USER', TRUE);
            $check_email        = $this->mglobal->get_data_all('T_USER', null, ['ID_USER !=' => $user['ID_USER'], 'EMAIL' => $user['EMAIL'], 'IS_ACTIVE' => '1'], 'ID_USER');
            if (count($check_email) == 0) {
                $this->muser->update($user['ID_USER'], $user);
            } else {
                echo 0; exit();
            }
        }else if($this->input->post('act', TRUE)=='dodelete'){
            $user['ID_USER']    = $this->input->post('ID_USER', TRUE);
            $user['IS_ACTIVE']  = '-1';
            $this->muser->update($user['ID_USER'], $user);
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }
    
    /** Form Tambah User
     * 
     * @return html form tambah User
     */
    function adduser(){
        // $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $data = array(
            'form'      => 'add'
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    function cek_user($username){
        $this->load->model('muser', '', TRUE);
        $check = $this->muser->check_user_if_exist($username);
        if(!empty($check)){
            echo '1';
        }else{
            echo '0';
        }
    }
    function cek_user_edit($username, $current_username){
        $this->load->model('muser', '', TRUE);
        $check = $this->muser->check_user_for_edit($username, $current_username);
        if(!empty($check)){
            echo '1';
        }else{
            echo '0';
        }
    }
    function cek_email($email, $id){
        $this->load->model('mglobal', '', TRUE);
        $decode = urldecode($email);
        // $check  = $this->muser->check_email_if_exist($decode);
        $check = $this->mglobal->get_data_all('T_USER', [['table' => 'T_USER_ROLE', 'on' => 'T_USER.ID_ROLE = T_USER_ROLE.ID_ROLE', 'join' => 'left']], ['ID_USER !=' => $id, 'EMAIL' => $decode, 'IS_PN' => '0', 'T_USER.IS_ACTIVE' => '1']);
        if(!empty($check)){
            echo '1';
        }else{
            echo '0';
        }
    }

    /** Form Edit User
     * 
     * @return html form edit User
     */
    function edituser($id){
        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $data = array(
            'data_id'   => $id,
            'form'      => 'edit',
            'item'      => $this->muser->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Konfirmasi Hapus User
     * 
     * @return html form konfirmasi hapus User
     */
    function deleteuser($id){
        // $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $data = array(
            'form'  => 'delete',
            'item'  => $this->muser->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Detail User
     * 
     * @return html detail User
     */    
    function detailuser($id){
        $this->load->model('muser', '', TRUE);
        $data = array(
            'form'  => 'detail',
            'item'  => $this->muser->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    function aktivitas($offset = 0){
        $this->index($offset);
        return;
        $data = '';
    	$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }

    function pokja($offset = 0){
        echo 'Pokja';
        $this->index($offset);
    }

    function pejabat($offset = 0){
        echo 'Pejabat';
        $this->index($offset);

    }
    function reset_password($id_user) {
        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $data = array(
            'form'      => 'reset_password',
            'item'      => $this->muser->get_by_id($id_user)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }
    function do_reset_password() {
        $id_user = $this->input->post('ID_USER');
        $this->makses->check_is_write();
        $this->load->model('muser');
        $password = $this->muser->createRandomPassword(6);
        $data_user = $this->muser->get_by_id($id_user)->row();
        $kirim_info = $this->muser->kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $password, 'Pemberitahuan Reset Password');
        if ( $kirim_info ) {
            $data_update = array(
                'password' => sha1(md5($password))
            );
            $update = $this->muser->update($id_user, $data_update);
            echo 1 ;
        }
        else {
            echo 0;
        }
    }
}
