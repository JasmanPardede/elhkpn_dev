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
 * @author Gunaones - PT.Mitreka Solusi Indonesia || Irfan Isma Somantri 
 * @package Admin/Controllers/User
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	// num of records per page
	public $limit = 10;
    
    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->load->model('mglobal');
        date_default_timezone_set('Asia/Jakarta');

        // prepare search
        foreach ((array)@$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];

    }

    /** User List
     * 
     * @return html User List
     */
	public function index($offset = 0)
	{
        $filter_role = false;
        // load model
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);

        // prepare paging
        $this->base_url  = 'index.php/admin/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/';
        $this->uri_segment  = 4;
        $this->offset      = $this->uri->segment($this->uri_segment);
        

        $this->db->flush_cache();
        $this->db->start_cache();
        
        $this->db->select('T_USER_ROLE.*, T_USER.*');
        
        if(@$this->CARI['ROLE']){
            if($this->CARI['ROLE']!=-99){
                $this->db->from('T_USER AS T_USER2');

                $this->db->join("(
                    SELECT t.*, SUBSTRING_INDEX(SUBSTRING_INDEX(t.ID_ROLE, ',', n.n), ',', -1) ID_ROLE_SPLIT
                        FROM T_USER t CROSS JOIN (
                    SELECT a.N + b.N * 10 + 1 n
                     FROM 
                    (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
                    ,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
                    ORDER BY n
                    ) n
                     WHERE n.n <= 1 + (LENGTH(t.ID_ROLE) - LENGTH(REPLACE(t.ID_ROLE, ',', '')))
                     ORDER BY ID_USER, ID_ROLE_SPLIT
                ) T_USER", 'T_USER.ID_USER = T_USER2.ID_USER', 'left');
                $this->db->join('T_USER_ROLE', 'T_USER.ID_ROLE_SPLIT = T_USER_ROLE.ID_ROLE', 'left');
                $filter_role = true;
            }
        }
        if($filter_role==false){
            $this->db->from('T_USER');
            $this->db->join('T_USER_ROLE', 'T_USER.ID_ROLE = T_USER_ROLE.ID_ROLE', 'left');
        }

        $this->db->where("(T_USER_ROLE.IS_KPK = '1' AND T_USER.IS_ACTIVE = 1)");

        if(@$this->CARI['TEXT']){
            $this->db->where("(T_USER.USERNAME LIKE '%".$this->CARI['TEXT']."%')");
        }
        // echo $this->CARI['ROLE'];
        if(@$this->CARI['ROLE']){
            if($this->CARI['ROLE']!=-99){
                $this->db->where("(T_USER.ID_ROLE_SPLIT = ".$this->CARI['ROLE'].")");
            }
        }
        if(@$this->CARI['INST']){
            if($this->CARI['INST']!=-99){
                $this->db->where("(T_USER.INST_SATKERKD = '".$this->CARI['INST']."')");
            }
        }

        $this->total_rows = $this->db->get('')->num_rows();

        $this->db->order_by('T_USER.USERNAME','ASC');
        
        $query = $this->db->get('',$this->limit, $this->offset);
        $this->items = $query->result();
        // echo $this->db->last_query();
        $this->end = $query->num_rows();
        $this->db->flush_cache();

        $data = array(
            'linkCetak'     => 'index.php/admin/user/index/0',
            'titleCetak'    => 'cetak User',            
            'items'         => $this->items,
            'total_rows'    => $this->total_rows,
            'offset'        => $this->offset,
            'CARI'          => @$this->CARI,
            'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'User'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            'pagination'    => call_user_func('ng::genPagination'),
            
            
            'instansis'     => $this->mglobal->get_data_all('M_INST_SATKER', NULL, NULL, '*', NULL, array('INST_NAMA', 'ASC')),
            'roles'     => $this->mglobal->get_data_all('T_USER_ROLE', NULL, NULL, '*', "IS_ACTIVE = '1' AND (IS_PN != '1' AND IS_INSTANSI != '1' AND IS_USER_INSTANSI != '1' AND IS_KPK = '1')", array('ROLE', 'ASC')),
        );
        // load view
        $this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);

        return;


		// filter
		$cari             = '';
		$filter		     = '';
        $SEL_ROLE = '';         
        if(@$this->CARI['TEXT']){
           $filter = array(
                'NAMA' => $this->CARI['TEXT'],
            ); 
        }
        // print_r($_POST);
        if($this->input->post('SEL_ROLE', TRUE)){
            $filter = array(
                'ID_ROLE' => $this->input->post('SEL_ROLE', TRUE),
            );
            $SEL_ROLE = $this->input->post('SEL_ROLE', TRUE); 
        // }else{
        //     $filter = array(
        //         'ID_ROLE' => 1,
        //     );
        //     $SEL_ROLE = 1; 
        }

		// load and packing data
		$this->items		= $this->muser->get_paged_list($this->limit, $this->offset, $filter)->result();
        // echo $this->db->last_query();
		$this->total_rows   = $this->muser->count_all($filter);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
            'CARI'          => @$this->CARI,
			'SEL_ROLE'          => $SEL_ROLE,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
									'Administrator'     => 'index.php/dashboard/administrator',
									'User'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
            'roles'     => $this->mglobal->get_data_all('T_USER_ROLE', NULL, NULL, '*', "IS_ACTIVE = '1' AND (IS_PN != '1' AND IS_INSTANSI != '1' AND IS_USER_INSTANSI != '1')"),
            'instansis' => $this->minstansi->list_all()->result(),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}

	/** Process Insert, Update, Delete User
     * 
     * @return boolean process User
     */
    function saveuser(){
        $this->load->library('form_validation');
        $this->makses->check_is_write();


        //////////SISTEM KEAMANAN///////////
            $post_nama_file = 'PHOTO';
            $extension_diijinkan = array('jpg','png','jpeg','tiff','tif');
            $check_protect = protectionDocument($post_nama_file,$extension_diijinkan);
            if($check_protect){
                $method = __METHOD__;
                $this->load->model('mglobal');
                $this->mglobal->recordLogAttacker($check_protect,$method);
                echo 'INGAT DOSA WAHAI PARA HACKER';
                return;
            }   
        //////////SISTEM KEAMANAN///////////
        

        $this->db->trans_begin();
        $this->load->model('muser', '', TRUE);
        
        
        if($this->input->post('act', TRUE)=='doinsert'){
            $this->valid_form();
            if ( $this->form_validation->run() == FALSE ) {
                //exit('0');
            }

            $username       = $this->input->post('USERNAME', TRUE);
            
            $url            = '';
            $maxsize        = 2000000;
            $user           = $this->session->userdata('USR');
            $filependukung  = @$_FILES['PHOTO'];
            
            $extension      = strtolower(@substr(@$filependukung['name'],-4));
            $type_file      = array('.jpg','.png','.jpeg','.tiff', '.tif');

            $filename = 'uploads/users/'.$username.'/readme.txt';
            if(!file_exists($filename)){
                $dir = './uploads/users/'.$username.'/' ;

                $file_to_write = 'readme.txt';
                $content_to_write = "FOTO user Dari user = ".$username;

                if(is_dir($dir) === false)
                {
                     mkdir($dir);
                }

                $file = fopen($dir . '/' . $file_to_write,"w");

                fwrite($file, $content_to_write);

                // closes the file
                fclose($file);
            }

            $urlSK = '';
            $hasil = '';
            $fileSK      = $_FILES['PHOTO'];
            $extension   = strtolower(@substr(@$fileSK['name'],-4));
            if ($fileSK['size'] <= $maxsize) {
                if (in_array($extension, $type_file)) {
                    $c          = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/users/".$username."/", 0, 10000);
                    if($filependukung['size'] != '' && $filependukung['size'] <= $maxsize){
                        $url        = time()."-". trim($filependukung['name']);
                        $hasil = 'success';
                    }
                }
            }

            $password = $this->muser->createRandomPassword(6);
            $user = array(
                'USERNAME'       => $this->input->post('USERNAME', TRUE),
                'EMAIL'          => $this->input->post('EMAIL', TRUE),
                'NAMA'           => $this->input->post('NAMA', TRUE),
                'PASSWORD'       => sha1(md5($password)),
                'NOMOR_SK'       => $this->input->post('NOMOR_SK'),   
                'HANDPHONE'      => $this->input->post('HANDPHONE'),
                'PHOTO'          => (!empty($url)) ? $url : 'no_available_image.png',
                'IS_ACTIVE'      => 1,
                'IS_FIRST'       => 1,
                'ID_ROLE'        => implode(',',$this->input->post('ID_ROLE', TRUE)),
                'INST_SATKERKD'  => $this->input->post('INST_SATKERKD', TRUE),//$this->input->post('INST_SATKERKD', TRUE),
                'CREATED_TIME'   => time(),
                'CREATED_BY'     => $this->session->userdata('USR'),
                'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
            );

            $this->muser->save($user);
			ng::logActivity('Tambah User Untuk, id_user = '.$this->db->insert_id().', username = '.$this->input->post('USERNAME', TRUE));
            $this->muser->kirim_info_akun($this->input->post('EMAIL', TRUE), $this->input->post('USERNAME', TRUE), $password, $this->input->post('NAMA', TRUE));
            
        }else if($this->input->post('act', TRUE)=='doupdate'){

            $username       = $this->input->post('USERNAME', TRUE);
            $url            = '';
            $maxsize        = 2000000;
            $user           = $this->session->userdata('USR');
            $filependukung  = @$_FILES['PHOTO'];
            
            $extension      = strtolower(@substr(@$filependukung['name'],-4));
            $type_file      = array('.jpg','.png','.jpeg','.tiff', '.tif');

            $filename = 'uploads/users/'.$username.'/readme.txt';
            if(!file_exists($filename)){
                $dir = './uploads/users/'.$username.'/' ;

                $file_to_write = 'readme.txt';
                $content_to_write = "FOTO user Dari user = ".$username;

                if(is_dir($dir) === false)
                {
                     mkdir($dir);
                }

                $file = fopen($dir . '/' . $file_to_write,"w");

                fwrite($file, $content_to_write);

                // closes the file
                fclose($file);
            }

            $urlSK = '';
            $hasil = '';
            $fileSK      = $_FILES['PHOTO'];
            $extension   = strtolower(@substr(@$fileSK['name'],-4));
            if ($fileSK['size'] <= $maxsize) {
                if (in_array($extension, $type_file)) {
                    $c          = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/users/".$username."/", 0, 10000);
                    if($filependukung['size'] != '' && $filependukung['size'] <= $maxsize){
                        $url        = time()."-". trim($filependukung['name']);
                        $hasil = 'success';
                    }
                }
            }

            $user = array(
                'ID_USER' 		 => $this->input->post('ID_USER', TRUE),
				'USERNAME' 		 => $this->input->post('USERNAME', TRUE),
                'EMAIL'          => $this->input->post('EMAIL', TRUE),
				'NAMA' 		     => $this->input->post('NAMA', TRUE),
                'ID_ROLE'        => implode(',',$this->input->post('ID_ROLE', TRUE)),
                // 'INST_SATKERKD'  => $this->input->post('INST_SATKERKD', TRUE),
                'NOMOR_SK'       => $this->input->post('NOMOR_SK'),   
                'HANDPHONE'      => $this->input->post('HANDPHONE'),
                'UPDATED_TIME'   => time(),
                'UPDATED_BY'     => $this->session->userdata('USR'),
                'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"], 
            );
            if (!empty($url)) {
                $user['PHOTO']  = $url;

                $url_file = $this->input->post('tmp_PHOTO', TRUE);
                unlink("./uploads/users/".$username."/".$url_file);
            }

            $user['ID_USER']    = $this->input->post('ID_USER', TRUE);
            $this->muser->update($user['ID_USER'], $user);
			ng::logActivity('Edit User Untuk, id_user = '.$user['ID_USER'].', username = '.$this->input->post('USERNAME', TRUE));
        }else if($this->input->post('act', TRUE)=='dodelete'){
            $user['ID_USER']    = $this->input->post('ID_USER', TRUE);
            $user['IS_ACTIVE']  = 0;
            // $this->muser->delete($user['ID_USER']);
            $this->muser->update($user['ID_USER'],$user);
			ng::logActivity('Hapus User Untuk, id_user = '.$user['ID_USER']);
        }else if($this->input->post('act', TRUE)=='doresetpassword'){
            echo "masuk sini reset";
            $id_user = $this->input->post('ID_USER');
            $this->makses->check_is_write();
            $this->load->model('muser');
            $password = $this->muser->createRandomPassword(6);
            $data_user = $this->muser->get_by_id($id_user)->row();
            $kirim_info = $this->muser->kirim_info_lupa_password($data_user->EMAIL,$data_user->USERNAME,$password);
            if ( $kirim_info ) {
                    $data_update = array(
                            'password' => sha1(md5($password))
                );
                $update = $this->muser->update($id_user, $data_update);
                ng::logActivity('Reset Password Untuk User KPK dengan, id_user = '.$this->input->post('ID_USER', TRUE));
            }
            else {
                echo 0; exit();
            }
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
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form'      => 'add',
            'roles'     => $this->mglobal->get_data_all('T_USER_ROLE', NULL, NULL, '*', "IS_ACTIVE = '1' AND (IS_PN != '1' AND IS_INSTANSI != '1' AND IS_USER_INSTANSI != '1' AND IS_KPK = '1')"),
            'instansis'      => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Edit User
     * 
     * @return html form edit User
     */
    function edituser($id){
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        // $this->load->model('minstansi', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $data = array(
            'form'      => 'edit',
            'item'      => $this->muser->get_by_id($id)->row(),
            'roles'     => $this->mglobal->get_data_all('T_USER_ROLE', NULL, NULL, '*', "IS_ACTIVE = '1' AND (IS_PN != '1' AND IS_INSTANSI != '1' AND IS_USER_INSTANSI != '1' AND IS_KPK = '1')"),
            // 'instansis'      => $this->minstansi->list_all()->result(),
            'instansis'      => $this->mglobal->get_data_all('M_INST_SATKER', null, ['IS_ACTIVE' => '1'], 'INST_SATKERKD, INST_NAMA', null, ['INST_NAMA', 'ASC']),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Konfirmasi Hapus User
     * 
     * @return html form konfirmasi hapus User
     */
    function deleteuser($id){
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        // $this->load->model('minstansi', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $data = array(
            'form'  => 'delete',
            'item'  => $this->muser->get_by_id($id)->row(),
            'roles'      => $this->mrole->list_all()->result(),
            // 'instansis'      => $this->minstansi->list_all()->result(),
            'instansis'      => $this->mglobal->get_data_all('M_INST_SATKER', null, ['IS_ACTIVE' => '1'], 'INST_SATKERKD, INST_NAMA', null, ['INST_NAMA', 'ASC']),
        );

        $data['ROLE'] = $this->mrole->get_by_id($data['item']->ID_ROLE)->row()->ROLE;

        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Detail User
     * 
     * @return html detail User
     */    
    function detailuser($id){
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        // $this->load->model('minstansi', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $data = array(
            'form'  => 'detail',
            'item'  => $this->muser->get_by_id($id)->row(),
            'roles' => $this->mrole->list_all()->result(),
            // 'instansis'      => $this->minstansi->list_all()->result(),
            'instansis'      => $this->mglobal->get_data_all('M_INST_SATKER', null, ['IS_ACTIVE' => '1'], 'INST_SATKERKD, INST_NAMA', null, ['INST_NAMA', 'ASC']),
        );

        // print_r($data['item']);
        
        // $data['ROLE'] = $this->mrole->get_by_id($data['item']->ID_ROLE)->row()->ROLE;

        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }    

    /** Reset Password
     * 
     * @return html form reset password
     */    
    function resetpassword($id){
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form'  => 'resetpassword',
            'item'  => $this->muser->get_by_id($id)->row(),
            'roles' => $this->mrole->list_all()->result(),
            'instansis'      => $this->minstansi->list_all()->result(),
        );

        // print_r($data['item']);
        
        // $data['ROLE'] = $this->mrole->get_by_id($data['item']->ID_ROLE)->row()->ROLE;

        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    function doresetpassword(){
//        $post = $this->input->post();
//        echo doresetpasswordAll($post);
        $id_user = $this->input->post('ID_USER');
        if ( !empty($id_user) ) {
            $this->makses->check_is_write();
            $this->load->model('muser');
            $new_password = $this->muser->createRandomPassword(7);
            $data_user = $this->muser->get_by_id($id_user)->row();
            $salt = $data_user->SALT;
            if($salt == null){
                $salt = random_word(8);
            };
            $new_pwd_hash = md5($new_password);
            $password = sha1(md5($new_pwd_hash.$salt)); 

            //$kirim_info = $this->muser->kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $password, 'Pemberitahuan Reset Password');
            $kirim_info = $this->muser->kirim_info_lupa_password($data_user->EMAIL,$data_user->USERNAME,$new_password);
            if ( $kirim_info ) {
                $data_update = array(
                    'SALT' => $salt,
                    'password' => $password,
                    'IS_FIRST'  => '1'
                );
                $update = $this->muser->update($id_user, $data_update);
//                echo $this->db->last_query();
                if ($this->session->userdata('ID_ROLE')=="3"){
					ng::logActivity('Reset Password Untuk Admin Unit Kerja dengan, id_user = ' . $this->input->post('ID_USER', TRUE));}
				else{
					ng::logActivity('Reset Password Untuk Admin Instansi dengan, id_user = ' . $this->input->post('ID_USER', TRUE));}
					
                echo 1;
            }
            else {
                echo 0; exit();
            }
        } else {
            echo '0';
        }
    }

    function aktivitas($offset = 0){
        $this->index($offset);
        return;
        $data = '';
    	$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }

    function pokja($offset = 0){
        // echo 'Pokja';
        $this->index($offset);
        
    }

    function pejabat($offset = 0){
        // echo 'Pejabat';
        echo '<pre>';
        print_r($this->session->all_userdata());
        echo '</pre>';
        $this->index($offset);

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

    
    function cek_email($email, $id = ''){
        $this->load->model('mglobal', '', TRUE);
        $decode = urldecode($email);
        $decode = trim($decode);
        // $check  = $this->muser->check_email_if_exist($decode);
        
        $check = $this->mglobal->get_data_all('T_USER', [['table' => 'T_USER_ROLE', 'on' => 'T_USER.ID_ROLE = T_USER_ROLE.ID_ROLE', 'join' => 'left']], ['ID_USER <>' => $id, 'EMAIL' => $decode, 'IS_PN' => '0', 'T_USER.IS_ACTIVE' => '1']);
        if(!empty($check)){
            echo '1';
        }else{
            echo '0';
        }
    }

    function cek_email_pn($email){
        $this->load->model('mglobal', '', TRUE);
        $decode = urldecode($email);
        $decode = trim($decode);
        // $check  = $this->muser->check_email_if_exist($decode);
        $check = $this->mglobal->get_data_all('T_PN', null, ['EMAIL' => $decode, 'T_PN.IS_ACTIVE' => '1']);
        if(!empty($check)){
            echo '1';
        }else{
            echo '0';
        }
    }

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

    public function search_user_ldap() {
        $username = $this->input->get('username');
        $arr_result = array();
        if(ENV_USE_LDAP === true){
            if ( !empty($username) ) {
                $kpk_ldap = new $kpk_ldap();
                $result = $kpk_ldap->lhkpn_cari_ldap($username);

                if(is_array($result)){
                    foreach ($result as $k=>$v) { 
                        // $q++;
                        // //echo $k . '-';
                        // //echo $v;
                        // //echo '<br/>';

                        // $temp = array('id' => $k,
                        // 'value' => $k,
                        // 'info'=> $v
                        // );
                        // $arr[] = $temp;
                        $arr_result[] = array('value' => $k, 'displayName' => $v);
                    }
                    // return "{ \"results\": ".json_encode($arr)."}";

                }else{
                    // return "";
                }


                // $this->load->config('ldap');
                // $LDAPServerAddress  = $this->config->item('ldap_host'); 
                // $LDAPContainer      = $this->config->item('ldap_base_dn');
                // $filter = "(&(objectClass=user)(samaccounttype=805306368)(objectCategory=person)(cn=".$username."*) (!(userAccountControl:1.2.840.113556.1.4.803:=2)))";
                // $fields=array("samaccountname","displayname");

                // if( ( $ds=ldap_connect($LDAPServerAddress) ) ) {
                //     ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
                //     ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
                // if ( $bind = ldap_bind($ds, $this->config->item('ldap_user'), $this->config->item('ldap_pass')) ) {
                //     if( $sr=ldap_search($ds, $LDAPContainer, $filter, $fields) ) {
                //         if( $info = ldap_get_entries($ds, $sr) ) {
                //             //print_r($info); exit();
                //             foreach( $info as $if ) {
                //                 $arr_result[] = array('value' => $if['samaccountname'][0], 'displayName' => $if['displayname'][0]);
                //             }
                //         }
                    
                //     }
                // }
                // }
            }
        }else{
            $arr_result = array(
                array(
                    'value' =>'test1',
                    'displayName'=> 'test1'
                ),
                array(
                    'value' =>'test2',
                    'displayName'=> 'test2'
                ),
                array(
                    'value' =>'test3',
                    'displayName'=> 'test3'
                )
            );            
        }
        echo json_encode($arr_result);
    }

    function cek_user_ldap(){
        $this->load->model('muser', '', TRUE);

        $username = $this->input->get_post('username');
        $current_username = $this->input->get_post('current_username');
        echo $username . ' - : - ' . $current_username; exit();
        if ( !empty($current_username) )
            $check = $this->muser->check_user_for_edit($username, $current_username);
        else
            $check = $this->muser->check_user_if_exist($username);

        $check_ldap = false;
        if(ENV_USE_LDAP === true){
            if ( !empty($username) ) {
                $this->load->config('ldap');
                $LDAPServerAddress  = $this->config->item('ldap_host');
                $LDAPContainer      = $this->config->item('ldap_base_dn');
                $filter = "(&(objectClass=user)(samaccounttype=805306368)(objectCategory=person)(cn=".$username.") (!(userAccountControl:1.2.840.113556.1.4.803:=2)))";
                $fields=array("samaccountname","displayname");

                if( ( $ds=ldap_connect($LDAPServerAddress) ) ) {
                    ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
                    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

                    if ( $bind = ldap_bind($ds, $this->config->item('ldap_user'), $this->config->item('ldap_pass')) ) {

                        if( $sr=ldap_search($ds, $LDAPContainer, $filter, $fields) ) {
                            if( $info = ldap_get_entries($ds, $sr) ) {
                                if ( $info['count'] > 0 )
                                    $check_ldap = true;
                            }
                        }
                    }
                }
            }
        }else{
            $check_ldap = true;
        }
        if($check){
            echo '1';
        } else if ( $check_ldap == false ) {
            echo '2';
        } else{
            echo '0';
        }
    }
}
