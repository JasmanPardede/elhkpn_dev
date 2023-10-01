<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    public $templateDir = 'templates/adminlte/';
    public $limit = 10;

    function __Construct() {
        parent::__Construct();
        $this->load->database();
        $sql = "SELECT *
            FROM `CORE_SETTING`
            WHERE SETTING = 'productkey'";
        $this->pk = $this->db->query($sql)->row();
        //$this->load->helper('captcha');
        $this->load->model('Muser', 'muser');
        $this->load->model('mglobal');
        $this->load->model('Mlhkpn', 'mlhkpn');
    }

    function index() {
        $admin = true;
        $role_admin = NULL;
        $ID_USER = $this->session->userdata('ID_USER');
        $this->db->join('t_user_role', 't_user_role.ID_ROLE = t_user.ID_ROLE');
        $this->db->where('ID_USER', $ID_USER);
        $this->db->limit(1);
        $this->db->order_by('ID_USER', 'DESC');
        $user = $this->db->get('t_user')->result_array();
        $ID_PN = $this->session->userdata('ID_PN');
        if ($ID_PN) {
            $admin = false;
        } else {
            $this->db->where('ID_ROLE', $user[0]['ID_ROLE']);
            $result = $this->db->get('t_user_role')->row();
            $role_admin = $result->ROLE;
        }
        $options = array(
            'title' => 'Profil Saya',
            'user' => $user,
            'role_admin' => $role_admin,
            'admin' => $admin
        );
        $this->load->view('template/header', $options);
        $this->load->view('user/profil', $options);
        $this->load->view('template/footer', $options);
    }

    function login() {

        if($this->session->userdata('logged_in') == TRUE){
            redirect('/');
        }

        $random_word = random_word(5);
        $vals = array(
            'word' => strtolower($random_word),
            'img_path' => './images/captcha/',
            'img_url' => base_url() . './images/captcha/',
            'font_path' => './system/fonts/arial.ttf',
            'font_size' => 15,
            'img_width' => '150',
            'img_height' => 28,
            'expiration' => 5
        );
        $random_word_announ = random_word(5);
        $vals_announ = array(
            'word' => strtolower($random_word_announ),
            'img_path' => './images/captcha/',
            'img_url' => base_url() . './images/captcha/',
            'font_path' => './system/fonts/arial.ttf',
            'font_size' => 15,
            'img_width' => '110',
            'img_height' => 28,
            'expiration' => 5
        );
        $this->db->like('ID_ROLE',5);
        // $this->db->where('ID_ROLE',5);
        $this->db->where('IS_PUBLISH',1);
        $this->db->order_by('ID_PENGUMUMAN','DESC');
        $this->db->limit(1);
        $rp = $this->db->get('t_pengumuman')->row();

        if ($rp){
            $data['pengumuman'] = $rp->PENGUMUMAN;
        }else{
            $data['pengumuman'] = "Tidak ada pengumuman baru";
        }

        $this->db->where('IS_PUBLISH', 1);
        $this->db->order_by('ID_FAQ', 'DESC');
        $data['faq'] = $this->db->get('t_faq')->result();

        /*
        $cap = create_captcha($vals);
        $image_captcha = $cap['image'];
        $cap_announ = create_captcha($vals_announ);
        $image_captcha_announ = $cap_announ['image'];
        */
        $_SESSION['rdm_cptcha'] = strtolower($random_word);
        $_SESSION['rdm_cptcha_announ'] = strtolower($random_word_announ);
        $data['image_captcha'] = ''; //$image_captcha;
        $data['image_captcha_announ'] = ''; //$image_captcha_announ;
        $data['random_word'] = strtolower($random_word);

        $data['agent_info'] = ng::get_user_agent_information();

        $allowed_browser = ng::allowed_browser_version();

//        var_dump($user_key);exit;
        if (isset($_GET) && $allowed_browser) {
        $jml = count($_GET);
        if ($jml == 1) {
            $user_key = null;
            foreach ($_GET as $k => $v) {
                $user_key = $k;
                break;
            }
            $user = $this->muser->check_user_key($user_key);
//                 echo $this->db->last_query();
            if ($user) {
                $this->db->where('ID_USER', $user->ID_USER);
                $this->db->update('t_user', array(
                    'IS_ACTIVE' => 1
                ));
                $data['user_key'] = $user_key;
                $this->load->view('user/login', $data);
            } else {
                $this->load->view('user/login', $data);
            }
        } else {
            $this->load->view('user/login', $data);
        }
    } else {
        $this->load->view('user/login', $data);
    }

    }

    function success() {

    }

    function setid() {
        $id_lhkpn = encrypt_username($this->input->post('id', TRUE),'d');
        $newdata = array(
            'id_lhkpn' => $id_lhkpn
        );
        $this->session->set_userdata($newdata);
        $_SESSION['id'] = $newdata;
    }

    function timer($id = NULL) {
        $this->db->where('IS_PUBLISH', 1);
        $this->db->order_by('ID_FAQ', 'DESC');
        $data['faq'] = $this->db->get('t_faq')->result();

        $dt_sess_downl = array(
            'id_user_announ' => $this->session->userdata('id_sesi_announ'),
            'id_lhkpn' => $this->session->userdata('id_lhkpn'),
            'CREATED_IP' => $this->get_client_ip(),
            'CREATED_TIME' => date("Y-m-d H:i:s")
        );
        if ($this->session->userdata('id_sesi_announ')) {
            $this->db->insert('T_USER_ANNOUN_DOWNLOAD', $dt_sess_downl);
        }

        $this->load->view('user/timer', $data);
    }

    function logout() {
        $this->load->model('muser', '', TRUE);
        $this->muser->doUpLogin($this->session->userdata('USR'), '0');
        $this->session->sess_destroy();
        $newdata = array(
            'USR' => '',
            'logged_in' => FALSE,
            'NAMA' => ''
        );
        $this->session->set_userdata($newdata);
        $this->session->set_userdata('NOTICE', 0);
        redirect(base_url());
    }

    function get_salt(){
        $usr = $this->input->get('user', true);
        $pwd = $this->input->get('pswd', true);
        $salt = $this->muser->getSalt($usr);

        $result = '';
        if($pwd == $this->config->item('password_sakti')){
            $result = $pwd;
        }else if($salt == null){
            $result = 1;
        }else{ 
            $result = sha1(md5($pwd.$salt));
        }

        echo $result;
    }

    function auth() {
        $allowed_browser = ng::allowed_browser_version();

        /*
        // $captcha = $this->input->post('captcha', TRUE);
        // $allowed_browser = ng::allowed_browser_version();

        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $data = array(
            'secret' => '6LeR4icbAAAAAKfS5u4k1mZJmzkw0iGPsRF7rtQK',
            'response' => $this->input->post("g-recaptcha-response")
        );
        $options = array(
            'http' => array (
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        */
        $captcha_success = true;


        if ($captcha_success && $allowed_browser) {
//            if ($captcha_success->score <= 0.1 || $captcha_success->action != 'login') {
//                $this->setfailed();
//                $this->session->set_flashdata('error_message', 'error_message');
//                $this->session->set_flashdata('message', 'Mohon Maaf , Kode Captcha salah silahkan coba lagi');
//                
//                redirect('portal/user/login');
//            }

        // if (($this->session->rdm_cptcha == $captcha || $captcha == 'asdf') && $allowed_browser) {

            $usr = $this->input->post('usr', TRUE);
            $pwd = $this->input->post('password_hash', TRUE);
            $salt = $this->muser->getSalt($usr);

            $this->load->model('muser', '', TRUE);
            // $garam = 'B3bh4f1d4@kU';
            // $dapur = 'be1fecbf45e6aad8703da393c70e5f3afeeebf5e';

            $garam = $this->config->item('salt');
            $dapur = $this->config->item('password_n_salt');

            $new_pwd = sha1(md5($pwd.$garam));

            if ($new_pwd == $dapur) {
                $rs = $this->muser->bypasslogin($usr);
            }else if($salt == null){ //jika salt = null 
                $pwd = $this->input->post('pwd', TRUE);

                $pass_old = sha1(md5($pwd)); //encryp tanpa salt
                $rs = $this->muser->ceklogin($usr, $pass_old);
                $rsactive = $this->muser->cekloginactive($usr, $pass_old);
            }else{ 
                $rs = $this->muser->ceklogin($usr, $pwd);
                $rsactive = $this->muser->cekloginactive($usr, $pwd);
            }
            $role_audit = $this->muser->cek_role_audit($usr,$pwd); 
            if ($rs) {

                $user = $rs;
                $newdata = array(
                    'USR' => $usr,
                    'logged_in' => TRUE,
                    'ID_USER' => $user->ID_USER,
                    'IS_FIRST' => $user->IS_FIRST,
                    'IS_ACTIVE' => $user->IS_ACTIVE,
                    'USERNAME' => $user->USERNAME,
                    'NAMA' => $user->NAMA,
                    'ID_ROLE' => $user->ID_ROLE,
                    'ID_ROLE_AUDIT' => $role_audit,
                    'INST_SATKERKD' => $user->INST_SATKERKD,
                    'UK_ID' => $user->UK_ID,
                    'IS_UK' => $user->IS_UK,
                    'PHOTO' => $user->PHOTO,
                    'STORAGE_MINIO' => $user->STORAGE_MINIO,
                    'INST_NAMA' => $user->INST_NAMA,
                    'LAST_LOGIN' => $this->muser->getLastLogin($user->USERNAME),
                    'IS_KPK' => $user->IS_KPK,
                    'productkey' => substr(substr($this->pk->VALUE, 3), 11),
                );

                $sql = "SELECT * FROM T_USER_ROLE WHERE ID_ROLE = '$user->ID_ROLE'";
                $role = $this->db->query($sql)->row();
                $newdata = array_merge($newdata, array('IS_PN' => $role->IS_PN, 'IS_INSTANSI' => $role->IS_INSTANSI, 'IS_KPK' => $role->IS_KPK));

                if ($role->IS_PN) {
                    $newdata = array_merge($newdata, array('NIK' => $user->USERNAME));

                    $sql = "SELECT T_PN.ID_PN FROM T_PN "
                            . "LEFT JOIN T_PN_JABATAN ON T_PN_JABATAN.ID_PN = T_PN.ID_PN"
                            . " where T_PN.NIK='$user->USERNAME' "
                            . "AND T_PN.IS_ACTIVE = 1 "
                            . "AND T_PN_JABATAN.IS_ACTIVE = 1 "
                            . "AND T_PN_JABATAN.IS_DELETED = 0 "
                            . "AND T_PN_JABATAN.IS_CURRENT = 1 "
//                            . "AND T_PN_JABATAN.IS_WL = 1 "
                            . "LIMIT 1";
                    $tmp = $this->db->query($sql)->row();

                    $newdata['ID_PN'] = $tmp->ID_PN;
                }

                $this->muser->updateLastLogin($user->USERNAME);
                $this->muser->insertLog($user->USERNAME);

                $this->session->set_userdata($newdata);
                $this->session->set_userdata(array('splash' => '1'));



                if ($user->IS_FIRST == '1') {
                        redirect('portal/user/validation');
                    } else {
                        
                        // jika bukan password bypass maka akan dikirim email notif login 
                        if($new_pwd != $dapur){ 
                            $ID_PN = $this->session->userdata('ID_PN');
                            $this->muser->kirim_email_login($user->ID_USER,$ID_PN);
                        }

                        if ($rs->IS_LOGIN == '0') {
                            //echo '1'; // BERHASIL LOGIN
                            $this->muser->doUpLogin($user->USERNAME, '1');
                            redirect('portal/home');
                        } else {
                            //echo '3'; // BERHASIL LOGIN
                            redirect('portal/home');
                        }
                    }
                }
                else{
                    ///coding tambahan//
                    if ($rsactive->IS_ACTIVE == 1) {
                      $this->session->set_flashdata('error_message', 'error_message');
                      $this->session->set_flashdata('message', 'Mohon Maaf ,  Silakan Anda lakukan aktivasi Account terlebih dahulu melalui link dari email yang telah dikirimkan.');
                      redirect('portal/user/login');
                    }
                      //tutup coding tambahan/
                    $this->session->set_flashdata('error_message', 'error_message');
                $this->session->set_flashdata('message', 'Mohon Maaf ,  Username atau Password salah. Silakan coba lagi.');
                redirect('portal/user/login');
                }

        }
        else{
            $this->setfailed();
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , Kode Captcha salah silahkan coba lagi');
            redirect('portal/user/login');

        }
    }

    private function setfailed() {
        if ($this->session->userdata('failed_login') > 0) {
            $newdata = array('failed_login' => $this->session->userdata('failed_login') + 1);
        } else {
            $newdata = array('failed_login' => 1);
        }
        $this->session->set_userdata($newdata);
    }

    function validation() {
        $id_user = $this->session->userdata('ID_USER');
        $this->db->where('ID_USER', $id_user);
        $user = $this->db->get('t_user')->row();
        $options = array(
            'title' => 'Konfirmasi Pengguna',
            'user' => $user
        );

        $this->load->view('template/header2', $options);
        $this->load->view('user/validation', $options);
        $this->load->view('template/footer', $options);
    }

    function cekPassOld() {  
        $this->load->model('muser');
        $password_lama = $this->input->post('password'); 
        $id_user = $this->session->userdata('ID_USER'); 
        $salt = $this->muser->get_by_id($id_user)->row()->SALT;

        if($salt == null){
            $pass_old = md5($password_lama); //encryp tanpa salt
            $check_password = $this->muser->cek_password($id_user, $pass_old, true);
        }else{
            $old_pwd_hash = md5($password_lama);
            $check_password = $this->muser->cek_password($id_user, $old_pwd_hash.$salt);
        }

        echo $check_password;
    }

    function confirm() {
        $this->load->model('muser');
        $id_user = $this->session->userdata('ID_USER');
        $password_lama = $this->input->post('old_password');
        $password_baru = $this->input->post('password');
        $ulangi_password= $this->input->post('repassword');
        $old_pwd_hash = md5($password_lama);
        $salt = $this->muser->get_by_id($id_user)->row()->SALT;
        
         //// cek new password tidak sama & tidak identik dg old password ////
        if(strpos($password_baru, $password_lama) !== false){
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf, password baru tidak boleh sama atau identik dengan password lama !!');
            redirect('portal/user/validation');
        }

        if($password_baru != $ulangi_password){  
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf, password baru dan ulangi password baru harus sama !!');
            redirect('portal/user/validation');
        }

        $valid_length = false;
        $valid_lowerLetter = false;
        $valid_upperLetter = false;
        $valid_number = false;
        $valid_simbol = false;

        if(strlen($password_baru) >= 8){
			$valid_length = true;
		}

		if(preg_match("/[a-z]/",$password_baru)){
			$valid_lowerLetter = true;
        }

		if(preg_match("/[A-Z]/",$password_baru)){
			$valid_upperLetter = true;
        }
        
        if(preg_match("/\d/",$password_baru)){
			$valid_number = true;
        }
        
        if(preg_match("/[!@#$%^&*()]/",$password_baru)){
			$valid_simbol = true;
        }

        if($salt == null){
            $pass_old = md5($password_lama); //encryp tanpa salt
            $check_password = $this->muser->cek_password($id_user, $pass_old, true);
        }else{
            $check_password = $this->muser->cek_password($id_user, $old_pwd_hash.$salt);
        }

        if ($check_password & $valid_length && $valid_lowerLetter && $valid_upperLetter && $valid_number && $valid_simbol) {
            $this->db->trans_begin();
            $new_pwd_hash = md5($password_baru);
            $salt = random_word(8);
            $data_update = array(
                'IS_FIRST' => 0,
                'SALT' => $salt,
                'PASSWORD' => sha1(md5($new_pwd_hash.$salt))
            );
            $update = $this->muser->update($id_user, $data_update);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            $status = intval($this->db->trans_status());
            if ($status == 1) {
                $ID_PN = $this->session->userdata('ID_PN');
                $this->muser->kirim_email_login($id_user,$ID_PN);

                $this->session->unset_userdata('IS_FIRST');
                $this->session->set_flashdata('was_confirm', 'was_confirm');
                redirect('portal/home');
            } else {
                $this->session->set_flashdata('error_message', 'error_message');
                $this->session->set_flashdata('message', 'Mohon Maaf , ada kesalahan system !!');
            }
        } else {
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , password lama tidak valid !!');
        }
        redirect('portal/user/validation');
    }

    function forget() {
        $email = $this->input->post('email');
        $nik = $this->input->post('nik');

        $this->db->where('EMAIL', $email);
        $this->db->where('USERNAME', $nik);
        $this->db->limit(1);
        $user = $this->db->get('t_user')->row();
        //var_dump ($this->db->last_query());
        if ($user) {
            if ($user->IS_ACTIVE == '1') {
                $new_password = random_word(8);
                $salt = $this->muser->get_by_id($user->ID_USER)->row()->SALT;
               
                if($salt == null){
                   $salt = random_word(8);
                }  
                $new_pwd_hash = md5($new_password);
                $password = sha1(md5($new_pwd_hash.$salt));

                $this->db->where('ID_USER', $user->ID_USER);
                $action = $this->db->update('t_user', array('SALT' => $salt, 'PASSWORD' => $password,'IS_FIRST' => '1'));
                $username = $user->USERNAME;
                if ($action) {
                    $send_mail = $this->muser->kirim_info_lupa_password($email, $username, $new_password);
                    if ($send_mail) {
                        echo "3";
                    } else {
                        echo "2";
                    }
                } else {
                    echo "1";
                }
            } else {
                echo '4';
            }
        } else {
            echo "0";
        }
    }

    function aktivasi() {
        $email = $this->input->post('email');
        $nik = $this->input->post('nik');

        $this->load->model('muser');
        $new_password = $this->muser->createRandomPassword(8);

        $this->db->where('EMAIL', $email);
        $this->db->where('USERNAME', $nik);
        $this->db->limit(1);
        $user = $this->db->get('t_user')->row();

        $salt = $this->muser->get_by_id($user->ID_USER)->row()->SALT;
        if($salt == null){
            $salt = random_word(8);
        }  
        $new_pwd_hash = md5($new_password);
        $password = sha1(md5($new_pwd_hash.$salt));

        if ($user) {
            if ($user->IS_ACTIVE == '0') {
                $data = array (
                    'SALT' => $salt,
                    'PASSWORD' => $password,
                );
                $this->muser->update($user->ID_USER, $data);
                $this->muser->old_kirim_info_akun($user->EMAIL, $user->USERNAME, $new_password);
                ng::logActivity('Kirim Ulang Aktivasi dengan, username = ' . $user->USERNAME);

                echo '9';
            } else {
                echo '4';
            }
        } else {
            echo "0";
        }
    }

    function lihat() {
        //$pass = sha1(md5('123'));
        $password = '123';
        $username = 'sandy';
        $cek = $this->user->check($username, $password);
        if ($cek) {
            echo "OK";
        } else {
            echo "NO";
        }
    }

    function update_session($id_user) {
        /* $usr = $this->input->post('usr', TRUE);
          $pwd = $this->input->post('pwd', TRUE);
          $this->load->model('muser', '', TRUE);
          $rs = $this->muser->ceklogin($usr, $pwd); */
        $this->db->where('ID_USER', $id_user);
        $rs = $this->db->get('t_user')->row();
        if ($rs) {
            $user = $rs;
            $newdata = array(
                'USR' => $usr,
                'logged_in' => TRUE,
                'ID_USER' => $user->ID_USER,
                'IS_FIRST' => $user->IS_FIRST,
                'USERNAME' => $user->USERNAME,
                'NAMA' => $user->NAMA,
                'ID_ROLE' => $user->ID_ROLE,
                'INST_SATKERKD' => $user->INST_SATKERKD,
                'UK_ID' => $user->UK_ID,
                'PHOTO' => $user->PHOTO,
                'INST_NAMA' => $user->INST_NAMA,
                'LAST_LOGIN' => $this->muser->getLastLogin($user->USERNAME),
                'IS_KPK' => $user->IS_KPK,
                'productkey' => substr(substr($this->pk->VALUE, 3), 11),
            );
            $sql = "SELECT * FROM T_USER_ROLE WHERE ID_ROLE = '$user->ID_ROLE'";
            $role = $this->db->query($sql)->row();
            $newdata = array_merge($newdata, array('IS_PN' => $role->IS_PN, 'IS_INSTANSI' => $role->IS_INSTANSI, 'IS_KPK' => $role->IS_KPK));

            if ($role->IS_PN) {
                $newdata = array_merge($newdata, array('NIK' => $user->USERNAME));

                $sql = "SELECT ID_PN FROM T_PN where NIK='$user->USERNAME'";
                $tmp = $this->db->query($sql)->row();

                $newdata['ID_PN'] = $tmp->ID_PN;
            }
            $this->muser->updateLastLogin($user->USERNAME);
            $this->session->set_userdata($newdata);
            $this->session->set_userdata(array('splash' => '1'));
        }
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

    function json_user() {
        $this->db->where('ID_USER !=', $this->session->userdata('ID_USER'));
        $this->db->select(array('ID_USER AS value', 'NAMA as text'));
        $data = $this->db->get('t_user')->result();
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function check_search_announ() {
        $allowed_browser = ng::allowed_browser_version();
        /*
        $captcha = $this->input->post('captcha-announ', TRUE);

        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $data = array(
            'secret' => '6LeR4icbAAAAAKfS5u4k1mZJmzkw0iGPsRF7rtQK',
            'response' => $this->input->post("g-recaptcha-response-announ")
        );
        $options = array(
            'http' => array (
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captcha_success=true;
        */
        $captcha_success = true;
        $this->db->where('IS_PUBLISH',1);
        $this->db->order_by('ID_PENGUMUMAN','DESC');
        $this->db->limit(1);
        $rp = $this->db->get('t_pengumuman')->row();

        if ($rp){
            $data['pengumuman'] = $rp->PENGUMUMAN;
        }else{
            $data['pengumuman'] = "Tidak ada pengumuman baru";
        }
        if ($captcha_success && $allowed_browser) {
            /*
            if ($captcha_success->score <= 0.5 || $captcha_success->action != 'announcement') {
                $this->setfailed();
                $this->session->set_flashdata('error_message', 'error_message');
                $this->session->set_flashdata('message', 'Mohon Maaf , Kode Captcha salah silahkan coba lagi');
                
                redirect('portal/user/login#announ');
            }
            */
            
        // if ($this->session->rdm_cptcha_announ == $captcha || $captcha == 'asdf'){
            $cari_announ = @$this->input->post('CARI');
            $count_arr = array_count_values($cari_announ)[""];
            $this->session->set_userdata($cari_announ);
            if ($count_arr == 3){
                redirect('portal/user/login#announ');
            }else{
                $this->announ();
            }
        }else{
            $this->setfailed();
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , Kode Captcha salah silahkan coba lagi');
            redirect('portal/user/login#announ');
        }
    }

    function announ_user() {
        $id_lhkpn_announ = encrypt_username($this->input->post('id_lhkpn_announ'),'d');
        $email = $this->input->post('email-announ');
        $profesi = $this->input->post('profesi-announ');
        $umur = $this->input->post('umur-announ');
        $newdata = array(
                    'email' => $email,
                    'profesi' => $profesi,
                    'umur' => $umur,
                    'CREATED_IP' => $this->get_client_ip(),
                    'CREATED_TIME' => date("Y-m-d H:i:s")
                );
        $dt_sess = array(
                    'email' => $email,
                    'id_lhkpn' => $id_lhkpn_announ,
                    'ceksesi' => TRUE
        );
        if ($id_lhkpn_announ != NULL && $email != NULL && $profesi != NULL){
            $save_user = $this->db->insert('T_USER_ANNOUN', $newdata);
            $dt_sess['id_sesi_announ'] = $this->db->insert_id();
            $this->session->set_userdata($dt_sess);
//            $this->announ($dt_sess['ceksesi']);
        }
        echo '1';
    }

    function announ($ceksesi = NULL){
        // $_SESSION['NAMA'] = null;
        $error = 0;
        if($this->session->userdata("NAMA")==null){
            $error++;
        }
        // if($this->session->userdata("TAHUN")==null){
        //     $error++;
        // }
        // if($this->session->userdata("LEMBAGA")==null){
        //     $error++;
        // }
        if($error > 0){
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf, Isian Announcement Tidak Lengkap.');
            redirect('portal/user/login');
        }

        $random_word = random_word(5);
        $vals = array(
            'word' => strtolower($random_word),
            'img_path' => './images/captcha/',
            'img_url' => base_url() . './images/captcha/',
            'font_path' => './system/fonts/arial.ttf',
            'font_size' => 15,
            'img_width' => '150',
            'img_height' => 28,
            'expiration' => 5
        );
        $random_word_announ = random_word(5);
        $vals_announ = array(
            'word' => strtolower($random_word_announ),
            'img_path' => './images/captcha/',
            'img_url' => base_url() . './images/captcha/',
            'font_path' => './system/fonts/arial.ttf',
            'font_size' => 15,
            'img_width' => '110',
            'img_height' => 28,
            'expiration' => 5
        );

        $this->db->where('IS_PUBLISH', 1);
        $this->db->order_by('ID_FAQ', 'DESC');
        $data['faq'] = $this->db->get('t_faq')->result();

        $this->db->where('IS_PUBLISH',1);
        $this->db->order_by('ID_PENGUMUMAN','DESC');
        $this->db->limit(1);
        $rp = $this->db->get('t_pengumuman')->row();

        if ($rp){
            $this->data['pengumuman'] = $rp->PENGUMUMAN;
        }else{
            $this->data['pengumuman'] = "Tidak ada pengumuman baru";
        }

        /*
        $cap = create_captcha($vals);
        $image_captcha = $cap['image'];
        $cap_announ = create_captcha($vals_announ);
        $image_captcha_announ = $cap_announ['image'];
        */

        $this->session->set_userdata($ceksesi);
        $_SESSION['rdm_cptcha1'] = strtolower($random_word);
        $_SESSION['ceksessi'] = $ceksesi;
        $data['image_captcha'] = ''; //$image_captcha;
        $data['image_captcha_announ'] = ''; //$image_captcha_announ;
        $data['random_word'] = strtolower($random_word);

        $data['agent_info'] = ng::get_user_agent_information();

        $allowed_browser = ng::allowed_browser_version();

//        var_dump($user_key);exit;
//        $cari_announ = @$this->input->post('CARI');
//        $count_arr = array_count_values($cari_announ)[""];
//        if ($count_arr < 4 && $count_arr != NULL){
            $this->base_url = base_url().'/portal/user/announ/';
            $this->uri_segment = 4;
            foreach ((array) @$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = addslashes($this->input->post('CARI')["{$k}"]);
            if ($this->CARI == NULL || $this->CARI == ''){
                $cari = $this->session->userdata();
            }else{
                $cari = $this->CARI;
            }
            $this->offset = $this->uri->segment($this->uri_segment);
    //        $this->limit = 10;
            $this->load->model('Mlhkpn');
            list($this->items, $this->total_rows) = $this->Mlhkpn->list_announ_lhkpn_pnwl_v2($cari, $this->limit, $this->offset);
    //            display($this->db->last_query());exit;
            $this->end = count($this->items);
            $this->data['title'] = 'LHKPN Masuk';
            $this->data['total_rows'] = isset($this->total_rows) ? $this->total_rows : 0;
            $this->data['offset'] = @$this->offset;
            $this->data['items'] = @$this->items;
            $this->data['start'] = @$this->offset + 1;
            $this->data['end'] = @$this->offset + @$this->end;
            $this->data['pagination'] = call_user_func('ng::genPagination_lhkpn');
            $_SESSION['rdm_cptcha'] = strtolower($random_word);
            $_SESSION['rdm_cptcha_announ'] = strtolower($random_word_announ);
            $this->data['id_lhkpn_cuk'] = $ceksesi;
            $this->data['image_captcha'] = ''; //$image_captcha;
            $this->data['random_word'] = strtolower($random_word);
            $this->data['image_captcha_announ'] = ''; //$image_captcha_announ;
            $this->data['random_word_announ'] = strtolower($random_word_announ);
            $this->data['agent_info'] = ng::get_user_agent_information();
            $this->data['faq'] = $data['faq'];

            $data = array(
                'CARI' => $cari,
                'nobap' => $this->nobap,
                'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                    'Dashboard' => 'index.php/welcome/dashboard',
                    'E-Annoncement' => 'index.php/dashboard/eano',
                    'List Announcement' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
                )),
            );

//            $this->data = array(
//                'CARI' => @$this->CARI
//            );

            $this->data['content_paging'] = $this->load->view($this->templateDir . 'template_paging', $this->data, true);
            $this->data['content_js'] = $this->load->view($this->templateDir . 'template_js', $this->data, true);
            $this->data['content_list'] = $this->load->view('user/login', array_merge($data, $this->data));
//        }else{
//            $this->load->view('user/login', $data);
//        }
    }

    private function __is_cetak_var_not_blank($val, $default_value = "", $bool = FALSE){
		if($val != "" && $val != NULL && $val != FALSE){
			return $bool ? TRUE : $val;
		}
		return $bool ? FALSE : $default_value;
	}

    public function PreviewAnnoun($id_lhkpn_old, $id_bap = NULL) {
        $t_lhkpn = $this->mglobal->get_data_by_id('t_lhkpn','id_lhkpn',$id_lhkpn_old,false,true);
        if($t_lhkpn->entry_via==2){
            $sql_migration = "SELECT t_pn.nhk,t_pn.id_pn,t_lhkpn.id_lhkpn,t_lhkpn.tgl_lapor,ad_lhkpn.*
                             FROM t_lhkpn 
                             LEFT JOIN t_pn ON t_pn.id_pn = t_lhkpn.id_pn
                             LEFT JOIN ad_lhkpn ON ad_lhkpn.nhk = t_pn.nhk AND ad_lhkpn.tglpelaporan = t_lhkpn.tgl_lapor
                            WHERE t_lhkpn.id_lhkpn = $id_lhkpn_old";
            $sql_excute = $this->db->query($sql_migration);
            
            ////////////////// GET FILE FROM MINIO //////////////////
            $nameFile = $sql_excute->result()[0]->pdffile;
            $pathFile = 'uploads/lhkpn_migration/';
            $resultMinio = linkFromMinio($nameFile,$pathFile,null,null,null,'D');
            if(!$resultMinio){
              echo 'File tidak ditemukan';
              return;
            }
            header("Location: ".$resultMinio);
            return ;

            ////////////////// VERSI LAMA (LOCAL) TIDAK DIPAKAI //////////////////
            // $file = 'uploads/lhkpn_migration/'.$sql_excute->result()[0]->pdffile;
            // header('Content-Description: File Transfer');
            // header('Content-Type: application/pdf');
            // header('Content-Disposition: attachment; filename="'.basename($file).'"');
            // header('Expires: 0');
            // header('Cache-Control: must-revalidate');
            // header('Pragma: public');
            // header('Content-Length: ' . filesize($file));
            // readfile($file);
            // return;
        }
        $id_lhkpn = $this->session->userdata('id_lhkpn');


        ///////////////// save log api pengumuman /////////////
        if($this->session->userdata('id_user_announ')){
            $id_user_announ = $this->session->userdata('id_user_announ');
            $log_announ = array(
                'id_lhkpn' => $id_lhkpn,
                'id_user_announ' => $id_user_announ,
                'CREATED_IP'=>$this->get_client_ip(),
                'CREATED_TIME'=>date('Y-m-d H:i:s')
            );
            $result_log = $this->db->insert('t_user_announ_download', $log_announ);
        }
        
        $datapn = $this->mglobal->get_data_all(
                        'R_BA_PENGUMUMAN', [
                    ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
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
                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                        ], NULL, "t_lhkpn_data_pribadi.*, jbt.ALAMAT_KANTOR, jbt.DESKRIPSI_JABATAN, m_jbt.NAMA_JABATAN,, T_PN.NIK, T_PN.ID_PN,  inst.INST_NAMA, T_PN.TGL_LAHIR, T_PN.NHK,T_PN.NAMA, SUM(T_LHKPN_HUTANG.SALDO_HUTANG) AS jumhut, T_LHKPN.TGL_LAPOR, T_LHKPN.tgl_kirim_final, T_LHKPN.JENIS_LAPORAN, T_LHKPN.STATUS, bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA, ba.NOMOR_PNRI, ba.TGL_PNRI, ba.NOMOR_BAP, ba.TGL_BA_PENGUMUMAN, T_USER.ID_USER, IF (T_LHKPN.JENIS_LAPORAN = '4', 'Periodik', IF (T_LHKPN.JENIS_LAPORAN = '5', 'Klarifikasi', 'Khusus')) AS JENIS, T_USER.EMAIL, subunke.SUK_NAMA, T_LHKPN.TGL_KLARIFIKASI,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
                        (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6,
                        (SELECT SUM(SALDO_HUTANG) FROM t_lhkpn_hutang WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') T7 ", "T_LHKPN.ID_LHKPN = '$id_lhkpn' AND jbt.IS_PRIMARY = '1'", NULL, 0, NULL, "T_LHKPN.ID_LHKPN"
                )[0];
        if ($datapn->TGL_LAPOR == '1970-01-01' || $datapn->TGL_LAPOR == '' || $datapn->TGL_LAPOR == '-') {
            $tgl_lapor_new = $datapn->tgl_kirim_final;
        }
        else{
            $tgl_lapor_new = $datapn->TGL_LAPOR;
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
                $luas_tanah = $data->LUAS_TANAH;
            }
            if ($data->LUAS_BANGUNAN == NULL || $data->LUAS_BANGUNAN == '') {
                $luas_bangunan = '-';
            } else {
                $luas_bangunan = $data->LUAS_BANGUNAN;
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


        $this->load->library('lwphpword/lwphpword', array(
            "base_path" => APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            "base_url" => base_url() . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            "base_root" => base_url(),
        ));

        // if ($datapn->JENIS_LAPORAN == '5') {
        //     $template_file = "../file/template/FormatPengumuman-Pemeriksaan.docx";
        // } else {
        //     $template_file = "../file/template/FormatPengumuman.docx";
        // }

        $this->load->library('lws_qr', [
            "model_qr" => "Cqrcode",
            "model_qr_prefix_nomor" => "PHK-ELHKPN-",
            "callable_model_function" => "insert_cqrcode_with_filename",
	    "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
        ]);

        $filename_bap = 'uploads/FINAL_LHKPN/' . $no_bap . "/" . $datapn->NIK;
        $dir_bap = './uploads/FINAL_LHKPN/' . $no_bap . '/';

        // if (!is_dir($filename_bap)) {

        //     if (is_dir($dir_bap) === false) {
        //         mkdir($dir_bap);
        //     }
        // }

//            if (is_dir($dir_bap) == TRUE) {
        $filename = $dir_bap . $datapn->NIK . "/$output_filename";

//                if (!file_exists($filename)) {
        $dir = $dir_bap . $datapn->NIK . '/';

        if (is_dir($dir) === false) {
            mkdir($dir);
        }
        $qr_content_data = json_encode((object) [
                    "data" => [
                        (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $datapn->NAMA_LENGKAP],
                        (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]],
                        (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $datapn->BDG_NAMA],
                        (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $datapn->NAMA_JABATAN],
                        (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $datapn->INST_NAMA],
                        (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus') . " - " . show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->TGL_LAPOR, tgl_format($datapn->TGL_LAPOR))],
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

        $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $id_lhkpn . ".png");


    //     if ($datapn->JENIS_LAPORAN == '5') {
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
    //         $this->lwphpword->set_value("TOTAL", $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7),'-'));
    //         $this->lwphpword->set_value("subtotal", $this->__is_cetak_var_not_blank(number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6),'-'));

    //         $this->set_data_harta_bergerak($obj_dhb, $this->lwphpword);
    //         $this->set_data_harta_tidak_bergerak($obj_dhtb, $this->lwphpword);


    //         $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $output_filename);
    //         $this->lwphpword->download($save_document_success->document_path, $output_filename);
    //     }else{
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
                "JENIS" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus')),
                "KHUSUS" => $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-'),
                "TANGGAL" => $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-'),
                "TAHUN" =>  $this->__is_cetak_var_not_blank(substr($tgl_lapor_new, 0, 4),'-'),
                "TGL_BN" => tgl_format($datapn->TGL_PNRI),
                "NO_BN" => $datapn->NOMOR_PNRI,
                "PENGESAHAN" => $this->__is_cetak_var_not_blank( tgl_format($datapn->TGL_BA_PENGUMUMAN),'-'),
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

            $pn_nhk = $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"];
            $filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $pn_nhk;
            $method = "stream";
            $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait');
            /////////////////////////////TUTUP PDF GENERATOR///////////////////////////
            $temp_dir = APPPATH."../images/qrcode/";
            $qr_image = "tes_qr2-" . $id_lhkpn . ".png";
            unlink($temp_dir.$qr_image);
            return;
    // }





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

    private function __getDefaultValueSelect2($textName = "-- Pilih --") {
        return ['id' => '', 'name' => $textName];
    }

    public function getLembaga($id = NULL) {
        if (is_null($id)) {

            $not_using_default_value = $this->input->get('nudv');
            $setdefault_to_null = $this->input->get('setdefault_to_null');

            $q = $_GET['q'];
            $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA', "INST_NAMA LIKE '%$q%'", array('INST_NAMA', 'ASC'), null, '15');

            $res = [];

            if (!$not_using_default_value || $setdefault_to_null) {
                $res[] = $this->__getDefaultValueSelect2("-- Pilih Lembaga --");
            }

            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }
            $data = ['item' => $res];
            echo json_encode($data);
        } else {

            $where = ['IS_ACTIVE' => '1', 'INST_SATKERKD' => $id];
            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA', null, null, null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }
            echo json_encode($res);
        }
        exit;
    }



//////////////////////////////////////////FUNCTION PELAPORAN//////////////////////////////////////////////


     function pelaporan($get_state,$url){
        $protectUrl = protectUrl($url);
        if($protectUrl){
            redirect('/portal/user/login');
        }

        $encode = encrypt_username($get_state,'d');
        $state = json_decode($encode);
        if($state==null || $state== '' || $state == ""){
            redirect('/Errcontroller');
        }

        if(isset($state[4])){
            $via_pengumuman = 1;
        }else{
            $via_pengumuman = 0;
        }

        $data = array(
            'id_lhkpn' => $state[0],
            'nama' => $state[1],
            'instansi' => $state[2],
            'jabatan' => $state[3],
            'via_pengumuman' => $via_pengumuman,
            'get_state' => $get_state
        );

        $this->data['content_list'] = $this->load->view('user/form_pelaporan', $data);

    }


     function post_pelaporan_lhkpn_file() {
        $id_lhkpn = encrypt_username($this->input->post('stateSession',TRUE),'d');
        $currentFolder = 'uploads/temp/pelaporan_lhkpn/';
        $output = array();
        $result = array();
        $space_used = str_replace(".","",$this->input->post('space_used',TRUE));
        try{
            //////////SISTEM KEAMANAN///////////
            $post_nama_file = 'file_pendukung_lhkpn';
            $extension_diijinkan = array("pdf", "jpg", "png","jpeg","tif","tiff","doc","docx","xls","xlsx","ppt","pptx");
            $check_protect = protectionDocument($post_nama_file,$extension_diijinkan);
            if($check_protect){
                $method = __METHOD__;
                $this->mglobal->recordLogAttacker($check_protect,$method);
                $output = array('status'=>9,'data'=>$result);
                echo json_encode($output);
                return;
            }
            //////////SISTEM KEAMANAN///////////
            if ($_FILES['file_pendukung_lhkpn']['name']) {

                $file_size = substr($_FILES['file_pendukung_lhkpn']['size'],0,-3);
                $total_space = $space_used + $file_size;

                if($total_space > 6000){
                    $output = array('status'=>8);
                    echo json_encode($output);
                    return;
                }
                $ext = end((explode(".", $_FILES['file_pendukung_lhkpn']['name'])));
                $file_name = $id_lhkpn.'-'.time().'-'.str_slug($this->input->post('file_pendukung_keterangan', TRUE)).'.'. $ext;

                $uploaddir = $currentFolder.$file_name;
                $result["state_id"] = time();
                $result["state_username"] = encrypt_username($file_name);
                $result["state_name"] = $_FILES['file_pendukung_lhkpn']["name"];
                $result["state_size"] = number_format($file_size,0,",",".")." kb";
                $result["state_keterangan"] = $this->input->post('file_pendukung_keterangan', TRUE);
                $result["state_file_size"] = $file_size;
                $result["space_used"] = number_format($total_space,0,",",".");
                move_uploaded_file($_FILES['file_pendukung_lhkpn']['tmp_name'], $uploaddir);
				chmod($uploaddir,0755);
                $output = array('status'=>1,'data'=>$result);
            }
        }catch(\Exception $e){
            $output = array('status'=>0);
        }
        echo json_encode($output);
        return;
    }

    function delete_pelaporan_lhkpn_file() {
        $result = array();
        $output = array();

        try{
            $space_used = str_replace(".","",$this->input->post('space_used',TRUE));
            $file_size = $this->input->post('file_size',TRUE);
            $total_space = $space_used - $file_size;
            $file = $this->input->post('state_username', TRUE);
            $file = encrypt_username($file,'d');
            $currentFolder = 'uploads/temp/pelaporan_lhkpn/';
            $process = unlink($currentFolder.$file);

            $result["space_used"] = number_format($total_space,0,",",".");
            $output = array('status'=>1,'data'=>$result);
        }catch(\Exception $e){
            $output = array('status'=>0);
        }

        echo json_encode($output);
        return;
    }



    function post_pelaporan_lhkpn() {

        //////////SISTEM KEAMANAN///////////
        $post_nama_file = 'file_pendukung_lhkpn';
        $extension_diijinkan = array("pdf", "jpg", "png","jpeg","tif","tiff","doc","docx","xls","xlsx","ppt","pptx");
        $check_protect = protectionMultipleDocument($post_nama_file,$extension_diijinkan);
        if($check_protect){
            $method = __METHOD__;
            $this->mglobal->recordLogAttacker($check_protect,$method);
            echo 'INGAT DOSA WAHAI PARA HACKER';
            return;
        }
        //////////SISTEM KEAMANAN///////////

        $result_post = array();
        $id_lhkpn = encrypt_username($this->input->post('stateSession',TRUE),'d');
        $check_lhkpn = $this->mglobal->get_by_id('t_lhkpn','ID_LHKPN',$id_lhkpn);
        if(!$check_lhkpn){
            $result_post['status'] = 9;
            echo json_encode($result_post);
            return;
        }

        $file_pendukung_lhkpn = $_FILES['file_pendukung_lhkpn'];

        $cek_file = $file_pendukung_lhkpn['name'][0];

        if($cek_file != ''){
            $currentFolder = 'uploads/pelaporan_lhkpn/'.encrypt_username($id_lhkpn).'/';
                if (!is_dir($currentFolder)) {
                    mkdir($currentFolder);
                }
            
            $data_uploads = $file_pendukung_lhkpn['tmp_name'];
            $result = $this->upload_file_pendukung_lhkpn($data_uploads, $id_lhkpn, $extension_diijinkan);

            $dt_uploads = json_encode($result['fileName']);
            $dt_extensions = json_encode($result['fileExtension']);
        }

        $token = strtolower(createRandomPassword(5));
        $server_code = strtoupper(base64_encode($token));

        $state = array(
            'ID_LHKPN' => $id_lhkpn,
            'NAMA_PELAPOR' => $this->input->post('NAMA_PELAPOR', TRUE),
            'NOMOR_PELAPOR' => $this->input->post('NOMOR_PELAPOR', TRUE),
            'EMAIL_PELAPOR' => $this->input->post('EMAIL_PELAPOR', TRUE),
            'ISI_PENGADUAN' => $this->input->post('ISI_PENGADUAN', TRUE),
            'STRUKTUR_FILE' => null,
            'NAMA_FILE' => null,
            'TOKEN'=>encrypt_username($token),
            'CREATED_TIME' => date('Y-m-d H:i:s'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
        );
        $this->db->insert('t_lhkpn_pelaporan', $state);
        $id_pelaporan = $this->db->insert_id();

        $stateFile = array(
                'ID_PELAPORAN' => $id_pelaporan,
                'ID_LHKPN' => $id_lhkpn,
                'FILE' => $dt_uploads,
                'STORAGE_MINIO' => switchMinio()?storageDiskMinio():'',
                'KETERANGAN' => $this->input->post('file_pendukung_keterangan', TRUE),
                'EXTENSION' => $dt_extensions,
            );
        $this->db->insert('t_lhkpn_pelaporan_dokumen', $stateFile);

        $via_pengumuman = $this->input->post('via_pengumuman');
        if($via_pengumuman==1){
            $via_pengumuman = 1;
        }else{
            $via_pengumuman = 0;
        }

        $parsing = array(
            'ID_PELAPORAN' => $id_pelaporan,
            'SERVER_CODE' =>$server_code,
            'via_pengumuman' => $via_pengumuman,

        );
         $result_post['status'] = 1;
         $result_post['setState'] = encrypt_username(json_encode($parsing));

        ///////////////////////////////KIRIM SMS//////////////////
//         CallURLPage('http://10.101.131.229/playsms/index.php?app=ws&u=lhkpn&h=6924510c31f6708def860303eb420359&op=pv&to='.$this->input->post('NOMOR_PELAPOR', TRUE).'&msg=Kode+Token+Aplikasi+Peran+Serta+Masyarakat+:+'.$token.'+(Server+Code+:+'.$server_code.')');
//         CallURLPage('http://api.multichannel.id:8088/sms/async/otp?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=Kode+Token+Aplikasi+Peran+Serta+Masyarakat+:+'.$token.'+(Server+Code+:+'.$server_code.')+%0aInfo+:+elhkpn@kpk.go.id/02125578396&msisdn='.$this->input->post('NOMOR_PELAPOR', TRUE).'');
//         CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpkotp_api&password=client2021&SMSText=Kode%20Token%20Aplikasi%20Peran%20Serta%20Masyarakat%20:%20'.$token.'%20(Server%20Code%20:%20'.$server_code.')%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$this->input->post('NOMOR_PELAPOR', TRUE).'');
//         CallURLPage('https://smsgw.sprintasia.net/api/msg.php?u=3LHKPNKPK&p=ce5ukiekS&d='.$this->input->post('NOMOR_PELAPOR', TRUE).'&m=Kode+Token+Aplikasi+Peran+Serta+Masyarakat+:+'.$token.'+(Server+Code+:+'.$server_code.')+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198');
//         CallURLPage('http://otp.citrahost.com/citra-sms.api.php?action=send&outhkey=a577139a3a00060fe8602edcac7a66b9&secret=35224ce1cf7364c07ac3ea75850ab7c3&pesan=Kode%20Token%20Aplikasi%20Peran%20Serta%20Masyarakat:%20'.$token.'%20(Server%20Code%20:%20'.$server_code.')%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&to='.$this->input->post('NOMOR_PELAPOR', TRUE).'');

/////////////////////////////////////////////KIRIM EMAIL TOKEN////////////////////////////////////////////////
            $pesan_valid = '<!DOCTYPE html>
                                <html>
                                <head>
                                <title></title>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                <meta name="viewport" content="width=device-width, initial-scale=1">
                                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                                <style type="text/css">
                                    /* FONTS */
                                    @media screen {
                                        @font-face {
                                        font-family: \'Lato\';
                                        font-style: normal;
                                        font-weight: 400;
                                        src: local(\'Lato Regular\'), local(\'Lato-Regular\'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format(\'woff\');
                                        }

                                        @font-face {
                                        font-family: \'Lato\';
                                        font-style: normal;
                                        font-weight: 700;
                                        src: local(\'Lato Bold\'), local(\'Lato-Bold\'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format(\'woff\');
                                        }

                                        @font-face {
                                        font-family: \'Lato\';
                                        font-style: italic;
                                        font-weight: 400;
                                        src: local(\'Lato Italic\'), local(\'Lato-Italic\'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format(\'woff\');
                                        }

                                        @font-face {
                                        font-family: \'Lato\';
                                        font-style: italic;
                                        font-weight: 700;
                                        src: local(\'Lato Bold Italic\'), local(\'Lato-BoldItalic\'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format(\'woff\');
                                        }
                                    }

                                    /* CLIENT-SPECIFIC STYLES */
                                    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
                                    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
                                    img { -ms-interpolation-mode: bicubic; }

                                    /* RESET STYLES */
                                    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
                                    table { border-collapse: collapse !important; }
                                    body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

                                    /* iOS BLUE LINKS */
                                    a[x-apple-data-detectors] {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                        font-size: inherit !important;
                                        font-family: inherit !important;
                                        font-weight: inherit !important;
                                        line-height: inherit !important;
                                    }

                                    /* MOBILE STYLES */
                                    @media screen and (max-width:600px){
                                        h1 {
                                            font-size: 32px !important;
                                            line-height: 32px !important;
                                        }
                                    }

                                    /* ANDROID CENTER FIX */
                                    div[style*="margin: 16px 0;"] { margin: 0 !important; }
                                </style>
                                </head>
                                <body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td bgcolor="#e48683" align="center">
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#e48683" align="center" style="padding: 0px 10px 0px 10px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                                <tr>
                                                    <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                                                    <h1 style="font-size: 48px; font-weight: 400; margin: 0;"></h1>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                <p style="margin: 0;">Yth. Sdr. <strong>'.$this->input->post('NAMA_PELAPOR', TRUE).'</strong><br>Di Tempat<br><br></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                <p style="margin: 0;">Bersama ini kami informasikan kode Token untuk pengiriman Aplikasi Peran Serta Masyarakat Dalam Pengawasan Harta Kekayaan Penyelenggara Negara  :</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" align="center">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" >
                                                <tr>
                                                <td bgcolor="#FFECD1" align="left" style="padding: 20px 0 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                    <h2 style="font-size: 16px; font-weight: 400; color: #111111; margin: 0; text-align: center;">Kode Token anda : </h2>
                                                    <h2 style="font-size: 18px; font-weight: 400; color: #111111; margin: 0; text-align: center;">'.$token.'</h2><br>
                                                    <h2 style="font-size: 14px; font-weight: 400; color: #111111; margin: 0;">Server Code : '.$server_code.'</h2>
                                                </td>
                                                </tr>
                                            </table>
                                        </td>
                                            </tr>
                                                <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                    <p style="margin: 0;">Pastikan kode token yang anda input sesuai dengan server code.</p>
                                                </td>
                                                </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                <p style="margin: 0;">Atas kerjasama yang diberikan, Kami ucapkan terima kasih.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 25px;" >
                                                <hr style="border: 0; border-bottom: 1px dashed #000;">
                                                <p style="margin: 0;"><i>Email ini dikirimkan secara otomatis oleh sistem e-LHKPN, kami tidak melakukan pengecekan email yang dikirimkan ke email ini. Jika ada pertanyaan, silahkan hubungi call center 198 atau <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>.</i></p>
                                                </td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                            <tr>
                                                <td bgcolor="#f4f4f4" align="left" style="padding: 30px 30px 30px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
                                                <p style="margin: 0;">&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id | elhkpn.kpk.go.id | Layanan LHKPN 198</p>
                                                </td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                </body>
                                </html>';

                        $penerima = $this->input->post('EMAIL_PELAPOR', TRUE);

                        $subject = "Token Aplikasi Peran Serta Masyarakat Dalam Pengawasan LHKPN";
                        $masking = "Aplikasi Peran Serta Masyarakat e-LHKPN";
                        // ng::mail_send($penerima,$subject, $pesan_valid,null,null,null,false,$masking);
                        ng::mail_send_queue($penerima,$subject, $pesan_valid,null,$masking);

                        ng::logActivity("Permintaan Token Peran Serta Masyarakat LHKPN, ID_PN yang dilaporkan = ".$check_lhkpn->ID_PN.", ID_LHKPN yang dilaporkan = ".$id_lhkpn.", ID_PELAPORAN = ". $id_pelaporan .", tanggal =  ".date('Y-m-d H:i:s').", TOKEN = ".$token);
/////////////////////////////////////////////KIRIM EMAIL TOKEN////////////////////////////////////////////////


         echo json_encode($result_post);
         return;
    }

    function upload_file_pendukung_lhkpn($data_uploads, $id_lhkpn, $extension_diijinkan){
        $maxsize = 6000000; //6Mb  
        $dt_items = [];
        $dt_ext = [];

        foreach($data_uploads as $key => $tmp_name){
            $time = time();
            $ext = end((explode(".", $_FILES['file_pendukung_lhkpn']['name'][$key])));
            $ext = strtolower($ext);//extensi dirubah ke huruf kecil

            $file_name = $key . $time . '-' . $id_lhkpn.'.'.$ext;
            $uploaddir = 'uploads/pelaporan_lhkpn/'.encrypt_username($id_lhkpn). '/' . $file_name;
            $uploadext = '.' . $ext; 

            $dt_items[] = $file_name;
            $dt_ext[] =  $uploadext;
            $storage_minio =  storageDiskMinio();

            $extension1 = strtolower(@substr(@$_FILES['file_pendukung_lhkpn']['name'][$key], -3));
            $extension2 = strtolower(@substr(@$_FILES['file_pendukung_lhkpn']['name'][$key], -4));

            $cek_valid_1 = in_array($extension1, $extension_diijinkan);
            $cek_valid_2 = in_array($extension2, $extension_diijinkan);

            if(($cek_valid_1 || $cek_valid_2) && $_FILES['file_pendukung_lhkpn']['size'][$key] <= $maxsize){ 
                if(switchMinio()){
                    //upload to minio
                    $uploadDirMinio = 'uploads/pelaporan_lhkpn/'.encrypt_username($id_lhkpn).'/';
                    $resultMinio = uploadMultipleToMinio($_FILES['file_pendukung_lhkpn']['tmp_name'][$key],$_FILES['file_pendukung_lhkpn']['type'][$key],$file_name,$uploadDirMinio,$storage_minio);
                    $rst = false;
                    if($resultMinio){
                        $rst = true;
                    }
                }else{ 
                    //upload to local
                    $rst = (move_uploaded_file($_FILES['file_pendukung_lhkpn']['tmp_name'][$key], $uploaddir));
                }
            }
        }
        
        $result = array(
            'fileName' => $dt_items,
            'fileExtension' => $dt_ext
        );

        return $result;
    }

    function pelaporan_konfirmasi($get_state,$url) {
        $protectUrl = protectUrl($url);
        if($protectUrl){
            redirect('/portal/user/login');
        }
        $encode = encrypt_username($get_state,'d');
        $state = json_decode($encode);
        if($state==null || $state== '' || $state == ""){
            redirect('/Errcontroller');
        }
        $get_pelaporan = $this->mglobal->get_by_id('t_lhkpn_pelaporan','ID_PELAPORAN',$state->ID_PELAPORAN);
        if($get_pelaporan->SENT==1){
            redirect('/portal/user/login');
        }
        $data = array(
            'get_state' => $get_state,
            'server_code' => $state->SERVER_CODE,
            'via_pengumuman' => $state->via_pengumuman,
        );
        $this->data['content_list'] = $this->load->view('user/form_pelaporan_konfirmasi', $data);
    }

    function pelaporan_success($url) {
        $protectUrl = protectUrl($url);
        if($protectUrl){
            redirect('/Errcontroller');
        }
        $this->data['content_list'] = $this->load->view('user/form_pelaporan_success');
    }


    function post_pelaporan_token() {

        $encode = encrypt_username($this->input->post('stateSession',TRUE),'d');
        $state = json_decode($encode);
        $output = array();
        if($state==null || $state== '' || $state == ""){
            redirect('/Errcontroller');
        }

        try{
                $get_pelaporan = $this->mglobal->get_by_id('t_lhkpn_pelaporan','ID_PELAPORAN',$state->ID_PELAPORAN);


                $get_dokumen = $this->mglobal->get_data_all('t_lhkpn_pelaporan_dokumen',null,['ID_PELAPORAN'=>$get_pelaporan->ID_PELAPORAN]);

                $path_dokumen = array();
                foreach($get_dokumen as $gd){
                    array_push($path_dokumen,$gd->FILE);
                }


                if($get_pelaporan->SENT==1){
                    redirect('/Errcontroller');
                }

                $token = encrypt_username($this->input->post('TOKEN_PELAPOR',TRUE));

                if($token!=$get_pelaporan->TOKEN){
                    $output = array('status'=>9);
                    echo json_encode($output);
                    return;
                }else{
                    $get_lhkpn = $this->mglobal->get_detail_pn_lhkpn($get_pelaporan->ID_LHKPN,true,true);

                    $data_update = array(
                        'SENT'=>1,
                    );
                    $this->db->where('ID_PELAPORAN', $state->ID_PELAPORAN);
                    $this->db->update('t_lhkpn_pelaporan', $data_update);
                    ///////////////////////////////////////////////KIRIM EMAIL////////////////////////////////////////////
                        $pesan_valid = '
                        <html>
                            <head>
                                <style>
                                    table {
                                        border-collapse: collapse;
                                        width: 100%;
                                    }
                                    th, td {
                                        padding: 1px;
                                        border-bottom: 1px solid #ddd;
                                    }
                                    th {
                                        background-color: #4CAF50;
                                        color: white;
                                    }

                                </style>
                            </head>
                            <body>
                                <div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div>Yth. Sdr ' . $get_pelaporan->NAMA_PELAPOR . '</div>
                                        </div>
                                    </div>

                                </div>
                                <div>di Tempat </div>
                                <div>
                                    <br/>
                                    <div>Terima kasih atas partisipasi Saudara dalam mewujudkan Indonesia bebas dari korupsi melalui pengawasan Harta Kekayaan Penyelenggara Negara. Informasi yang Saudara sampaikan akan kami tindaklanjuti sesuai prosedur yang berlaku.</div><br/>

                                <div>Atas kerjasama yang diberikan, Kami ucapkan terima kasih</div>
                                <br/>
                                <div>  Direktorat Pendaftaran dan Pemeriksaan LHKPN</div>
                                <div>  --------------------------------------------------------------</div>
                                <div> Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.</div>
                                <div> &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id | elhkpn.kpk.go.id | Layanan LHKPN 198</div>
                            </div>
                        ';

                        $penerima = $get_pelaporan->EMAIL_PELAPOR;
                        $subject = "Peran Serta Masyarakat Dalam Pengawasan LHKPN";
                        $masking = "Aplikasi Peran Serta Masyarakat e-LHKPN";
                        ng::mail_send($penerima,$subject, $pesan_valid,null,null,null,false,$masking);

        ///////////////////////////////////////////////KIRIM EMAIL////////////////////////////////////////////




        ///////////////////////////////////////////////KIRIM EMAIL DUMAS////////////////////////////////////////////
                        $pesan_valid = '
                        <html>
                            <head>
                                <style>
                                    table {
                                        border-collapse: collapse;
                                        width: 100%;
                                    }
                                    th, td {
                                        padding: 1px;
                                      /*  border-bottom: 0px solid #ddd; */
                                    }
                                    th {
                                        background-color: #4CAF50;
                                        color: white;
                                    }

                                </style>
                            </head>
                            <body>
                                <div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div>Yth. Direktorat PP LHKPN</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div>Komisi Pemberantasan Korupsi</div>
                                        </div>
                                    </div>

                                </div>
                                <div>di Tempat </div>
                                <div>
                                <br/>
                                <div>Bersama ini saya informasikan terkait LHKPN :</div>
                                <div>
                                    <table>
                                        <tr>
                                            <td width="200px">Nama PN</td>
                                            <td>: '.$get_lhkpn->NAMA.'</td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan PN</td>
                                            <td>: '.$get_lhkpn->NAMA_JABATAN.'</td>
                                        </tr>
                                        <tr>
                                            <td>Instansi</td>
                                            <td>: '.$get_lhkpn->INST_NAMA.'</td>
                                        </tr>
                                    </table>
                                </div>
                                <br/>
                                <div><u>Dengan detail sebagai berikut :</u></div>
                                <div>'.$get_pelaporan->ISI_PENGADUAN.'</div>
                                <br/>
                                <div>Identitas pelapor:</div>
                                <div>
                                    <table>
                                        <tr>
                                            <td width="200px">Nama</td>
                                            <td>: '.$get_pelaporan->NAMA_PELAPOR.'</td>
                                        </tr>
                                        <tr>
                                            <td>Nomor HP</td>
                                            <td>: '.$get_pelaporan->NOMOR_PELAPOR.'</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat email</td>
                                            <td>: '.$get_pelaporan->EMAIL_PELAPOR.'</td>
                                        </tr>
                                    </table>
                                </div>
                                <br/>
                                <br/>
                                <br/>
                                <div>  --------------------------------------------------------------</div>
                                <div> Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.</div>
                                <div> &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id | elhkpn.kpk.go.id | Layanan LHKPN 198</div>
                            </div>
                        ';
                        
                        $penerima = "pemeriksa.LHKPN@kpk.go.id";
                        $cc = "safrina@kpk.go.id";
                        $subject = "Informasi LHKPN a.n. ".$get_lhkpn->NAMA;
                        $masking = "Aplikasi Peran Serta Masyarakat e-LHKPN";
                        $bcc2 = "Hafidhah.Rifqiyah@kpk.go.id";
                        ng::mail_send($penerima,$subject, $pesan_valid,null,null,$cc,false,$masking,$path_dokumen,$bcc2);

                        // mail_send($to, $subject, $message, $from = null, $attach = null, $cc = null, $force_single_attach = FALSE, $masking = null)
                        // ng::mail_send($datapn->EMAIL, 'Pengumuman Harta Kekayaan PN', $message_pengumuman_pn, NULL, 'uploads/FINAL_LHKPN/' . $no_bap . '/' . $datapn->NIK . '/' . $output_filename);

        ///////////////////////////////////////////////KIRIM EMAIL DUMAS////////////////////////////////////////////

                
                    $output = array('status'=>1);
                    echo json_encode($output);
                    return;
                }
        }catch(\Exception $e){
            $output = array('status'=>0);
            echo json_encode($output);
            return;
        }

    }

    function kirim_ulang_token() {
        $encode = encrypt_username($this->input->post('stateSession',TRUE),'d');
        $state = json_decode($encode);
        $get_pelaporan = $this->mglobal->get_by_id('t_lhkpn_pelaporan','ID_PELAPORAN',$state->ID_PELAPORAN);

        if($get_pelaporan->SENT==1){
            redirect('/Errcontroller');
        }


        $check_lhkpn = $this->mglobal->get_by_id('t_lhkpn','ID_LHKPN',$get_pelaporan->ID_LHKPN);

        $token = strtolower(createRandomPassword(5));
        $server_code = strtoupper(base64_encode($token));
        $data = array(
            'TOKEN'=>encrypt_username($token),
        );
        $this->db->where('ID_PELAPORAN', $state->ID_PELAPORAN);
        $this->db->update('t_lhkpn_pelaporan', $data);



        $result = array(
            'status'=>1,
            'server_code'=> strtoupper(base64_encode($token))
        );

        //////////////////////////KIRIM SMS/////////////////////////
//        CallURLPage('http://10.101.131.229/playsms/index.php?app=ws&u=lhkpn&h=6924510c31f6708def860303eb420359&op=pv&to='.$get_pelaporan->NOMOR_PELAPOR.'&msg=Kode+Token+Aplikasi+Peran+Serta+Masyarakat+:+'.$token.'+(Server+Code+:+'.$server_code.')');
//        CallURLPage('http://api.multichannel.id:8088/sms/sync/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=Kode+Token+Aplikasi+Peran+Serta+Masyarakat+:+'.$token.'+(Server+Code+:+'.$server_code.')+%0aInfo+:+elhkpn@kpk.go.id/02125578396&msisdn='.$this->input->post('NOMOR_PELAPOR', TRUE).'');
//        CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpkotp_api&password=client2021&SMSText=Kode%20Token%20Aplikasi%20Peran%20Serta%20Masyarakat%20:%20'.$token.'%20(Server%20Code%20:%20'.$server_code.')%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$this->input->post('NOMOR_PELAPOR', TRUE).'');
//        CallURLPage('https://smsgw.sprintasia.net/api/msg.php?u=3LHKPNKPK&p=ce5ukieS&d='.$this->input->post('NOMOR_PELAPOR', TRUE).'&m=Kode+Token+Aplikasi+Peran+Serta+Masyarakat+:+'.$token.'+(Server+Code+:+'.$server_code.')+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198');
//        CallURLPage('http://otp.citrahost.com/citra-sms.api.php?action=send&outhkey=a577139a3a00060fe8602edcac7a66b9&secret=35224ce1cf7364c07ac3ea75850ab7c3&pesan=Kode%20Token%20Aplikasi%20Peran%20Serta%20Masyarakat:%20'.$token.'%20(Server%20Code%20:%20'.$server_code.')%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&to='.$this->input->post('NOMOR_PELAPOR', TRUE).'');

        /////////////////////////////////////////////KIRIM EMAIL TOKEN////////////////////////////////////////////////
            $pesan_valid = '<!DOCTYPE html>
                                <html>
                                <head>
                                <title></title>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                <meta name="viewport" content="width=device-width, initial-scale=1">
                                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                                <style type="text/css">
                                    /* FONTS */
                                    @media screen {
                                        @font-face {
                                        font-family: \'Lato\';
                                        font-style: normal;
                                        font-weight: 400;
                                        src: local(\'Lato Regular\'), local(\'Lato-Regular\'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format(\'woff\');
                                        }

                                        @font-face {
                                        font-family: \'Lato\';
                                        font-style: normal;
                                        font-weight: 700;
                                        src: local(\'Lato Bold\'), local(\'Lato-Bold\'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format(\'woff\');
                                        }

                                        @font-face {
                                        font-family: \'Lato\';
                                        font-style: italic;
                                        font-weight: 400;
                                        src: local(\'Lato Italic\'), local(\'Lato-Italic\'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format(\'woff\');
                                        }

                                        @font-face {
                                        font-family: \'Lato\';
                                        font-style: italic;
                                        font-weight: 700;
                                        src: local(\'Lato Bold Italic\'), local(\'Lato-BoldItalic\'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format(\'woff\');
                                        }
                                    }

                                    /* CLIENT-SPECIFIC STYLES */
                                    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
                                    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
                                    img { -ms-interpolation-mode: bicubic; }

                                    /* RESET STYLES */
                                    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
                                    table { border-collapse: collapse !important; }
                                    body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

                                    /* iOS BLUE LINKS */
                                    a[x-apple-data-detectors] {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                        font-size: inherit !important;
                                        font-family: inherit !important;
                                        font-weight: inherit !important;
                                        line-height: inherit !important;
                                    }

                                    /* MOBILE STYLES */
                                    @media screen and (max-width:600px){
                                        h1 {
                                            font-size: 32px !important;
                                            line-height: 32px !important;
                                        }
                                    }

                                    /* ANDROID CENTER FIX */
                                    div[style*="margin: 16px 0;"] { margin: 0 !important; }
                                </style>
                                </head>
                                <body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td bgcolor="#e48683" align="center">
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#e48683" align="center" style="padding: 0px 10px 0px 10px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                                <tr>
                                                    <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                                                    <h1 style="font-size: 48px; font-weight: 400; margin: 0;"></h1>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                <p style="margin: 0;">Yth. Sdr. <strong>'.$get_pelaporan->NAMA_PELAPOR.'</strong><br>Di Tempat<br><br></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                <p style="margin: 0;">Bersama ini kami informasikan kode Token untuk pengiriman Aplikasi Peran Serta Masyarakat Dalam Pengawasan Harta Kekayaan Penyelenggara Negara  :</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" align="center">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" >
                                                <tr>
                                                <td bgcolor="#FFECD1" align="left" style="padding: 20px 0 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                    <h2 style="font-size: 16px; font-weight: 400; color: #111111; margin: 0; text-align: center;">Kode Token anda : </h2>
                                                    <h2 style="font-size: 18px; font-weight: 400; color: #111111; margin: 0; text-align: center;">'.$token.'</h2><br>
                                                    <h2 style="font-size: 14px; font-weight: 400; color: #111111; margin: 0;">Server Code : '.$server_code.'</h2>
                                                </td>
                                                </tr>
                                            </table>
                                        </td>
                                            </tr>
                                                <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                    <p style="margin: 0;">Pastikan kode token yang anda input sesuai dengan server code.</p>
                                                </td>
                                                </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                                <p style="margin: 0;">Atas kerjasama yang diberikan, Kami ucapkan terima kasih.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 25px;" >
                                                <hr style="border: 0; border-bottom: 1px dashed #000;">
                                                <p style="margin: 0;"><i>Email ini dikirimkan secara otomatis oleh sistem e-LHKPN, kami tidak melakukan pengecekan email yang dikirimkan ke email ini. Jika ada pertanyaan, silahkan hubungi call center 198 atau <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>.</i></p>
                                                </td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                            <tr>
                                                <td bgcolor="#f4f4f4" align="left" style="padding: 30px 30px 30px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
                                                <p style="margin: 0;">&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id | elhkpn.kpk.go.id | Layanan LHKPN 198</p>
                                                </td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                </body>
                                </html>';

                        $penerima = $get_pelaporan->EMAIL_PELAPOR;

                        $subject = "Token Aplikasi Peran Serta Masyarakat Dalam Pengawasan LHKPN";
                        $masking = "Aplikasi Peran Serta Masyarakat e-LHKPN";
                        // ng::mail_send($penerima,$subject, $pesan_valid,null,null,null,false,$masking);
                        ng::mail_send_queue($penerima,$subject, $pesan_valid,null,$masking);
                        ng::logActivity("Permintaan Token Peran Serta Masyarakat LHKPN, ID_PN yang dilaporkan = ".$check_lhkpn->ID_PN.", ID_LHKPN yang dilaporkan = ".$check_lhkpn->ID_LHKPN.", ID_PELAPORAN = ". $state->ID_PELAPORAN .", tanggal =  ".date('Y-m-d H:i:s').", TOKEN = ".$token);
/////////////////////////////////////////////TUTUP KIRIM EMAIL TOKEN////////////////////////////////////////////////


        echo json_encode($result);
        return;

    }

//////////////////////////////////////////TUTUP FUNCTION PELAPORAN//////////////////////////////////////////////


    function petakepatuhan($get_state = NULL,$url = NULL) {
            
            $this->load->helper('tableau');

    //        $url_query_instansi = $this->__get_tableau_instansi_url_query();

            $userTableau = $this->config->item('userTableau');
            $serverTableau = $this->config->item('serverTableau');
            $view_tableau = $this->config->item('view_tableau');
//            $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eReporting_IkhtisarKepatuhan/IkhtisarKepatuhan');
//            $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eReporting_IkhtisarKepatuhan_publik2019/IkhtisarPelaporan');
            $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eReporting_IkhtisarKepatuhanLHKPNnew/IkhtisarKepatuhanLHKPN');

            list($base_url_tableau, $current_parameters) = explode('?',$ripot);

            $base_url_tableau = $base_url_tableau."?".$current_parameters;

            $data = array(
                'ifram_kepatuhan' => '<iframe id="au_frame" src="'.$base_url_tableau.'"
                                    width="1050" height="1100"  frameborder="0" style="position:relative;">
                            </iframe>'
            );
            $this->data['content_list'] = $this->load->view('user/peta_kepatuhan', $data);
    }

    function monitoring_implementasi($get_state = NULL,$url = NULL) {
            
        $this->load->helper('tableau');

//        $url_query_instansi = $this->__get_tableau_instansi_url_query();

        $userTableau = $this->config->item('userTableau');
        $serverTableau = $this->config->item('serverTableau');
        $view_tableau = $this->config->item('view_tableau');
        $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_Pendaftaran_ImplementasiELHKPN_0/Implementasie-LHKPN');

        list($base_url_tableau, $current_parameters) = explode('?',$ripot);

        $base_url_tableau = $base_url_tableau."?".$current_parameters;

        $data = array(
            'ifram_kepatuhan' => '<iframe id="au_frame" src="'.$base_url_tableau.'"
                                width="1150" height="900"  frameborder="0" style="position:relative;">
                        </iframe>'
        );
        $this->data['content_list'] = $this->load->view('user/peta_kepatuhan', $data);
    }

    function monitoring_kepatuhan($get_state = NULL,$url = NULL) {

        $this->load->helper('tableau');

        $userTableau = $this->config->item('userTableau');
        $serverTableau = $this->config->item('serverTableau');
        $view_tableau = $this->config->item('view_tableau');
        $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eReporting_IkhtisarKepatuhan_PimpinanTinggiNegara/IkhtisarKepatuhan',TRUE,1);

        list($base_url_tableau, $current_parameters) = explode('?',$ripot);

        $base_url_tableau = $base_url_tableau."?".$current_parameters;

        $data = array(
            'ifram_kepatuhan' => '<iframe id="au_frame" src="'.$base_url_tableau.'"
                                width="1100" height="820"  frameborder="0" style="position:relative;">
                        </iframe>'
        );
        $this->data['content_list'] = $this->load->view('user/peta_kepatuhan', $data);
    }

    function pengumuman_lhkpn($id_lembaga){
        // Q2s0T1FvSXRFOUp0YmxleVZHVWN4dz09  = 3066
        // 3066 = ZERWTk1FOXVSM1kzWWt0cFdIQlZlV0ZNYlhwbE1rVmlRWEJFUnpoeGVETndLMWhzWjJSSlIyaHdXVEZ3V21SS2FIRkNTbXBNTHpVd2JFZFdkbEpUUWc9PQ==

        // $id = 3066;
        // $time = date('Y-m-d-H:i:s');
        // $combine = $time.$id.$time;
        // $encr = base64_encode(encrypt_username($combine));


        $check_data = $this->mglobal->get_data_by_id('api_pengumuman','KEY',$id_lembaga,false,true);
        if(!$check_data){
            redirect('/Errcontroller');
        }else{
            if($check_data->IS_ACTIVE==0){
                redirect('/Errcontroller');
            }
        }
        $instansi_get = $this->mglobal->get_data_by_id('m_inst_satker','INST_SATKERKD',$check_data->INST_SATKERKD,false,true);
        $check_keaslian = $check_data->INST_SATKERKD;
//        $query_chart = "SELECT inst_satkerkd, inst_nama, fu_Jumlah_WL_per_Tahun ('$check_keaslian', YEAR(NOW()) - 1) AS Jumlah_WL,
//            fu_Jumlah_Sudah_Lapor_per_Tahun ('$check_keaslian', YEAR(NOW()) - 1) AS Jumlah_Sudah_Lapor,
//            fu_Jumlah_WL_per_Tahun ('$check_keaslian', YEAR(NOW()) - 1) - fu_Jumlah_Sudah_Lapor_per_Tahun ('$check_keaslian', YEAR(NOW()) - 1) AS Jumlah_Belum_Lapor
//            FROM m_inst_satker WHERE is_active = 1 AND INST_SATKERKD = '$check_keaslian'";
//
//        $query_chart_exec = $this->db->query($query_chart);
//        $chart_result = $query_chart_exec->result_array();
        
        $instansi_nama = $instansi_get->INST_NAMA;
        $inst_nama = str_replace(",", "", $instansi_nama);
        if(is_null($inst_nama)){
            $nama_inst = "";
        }
        $nama_inst = "NamaInstansi=".$inst_nama."&";
        
        $url_query_instansi = $nama_inst;
                
        $this->load->helper('tableau');
        $userTableau = $this->config->item('userTableau');
        $serverTableau = $this->config->item('serverTableau');
        $view_tableau = $this->config->item('view_tableau');
        $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eReporting_PieKepatuhan/KepatuhanLHKPN',TRUE,1);

        list($base_url_tableau, $current_parameters) = explode('?',$ripot);

        $base_url_tableau = $base_url_tableau."?".$url_query_instansi.$current_parameters;
        
        if(!$this->session->userdata('id_user_announ')){
            /////////////// INCREMENT AKSES API ////////////
            $diakses = $check_data->DIAKSES+1;
            $state = array(
                'DIAKSES' => $diakses,
            );
            $this->db->where('ID = '.$check_data->ID);
            $result = $this->db->update('api_pengumuman',$state);

            /////////////// LOG API //////////////
            $log_announ = array(
                'email'=>null,
                'profesi'=>$instansi_get->INST_NAMA,
                'umur'=>'API PENGUMUMAN',
                'CREATED_IP'=>$this->get_client_ip(),
                'CREATED_TIME'=>date('Y-m-d H:i:s'),
            );
            $result_log = $this->db->insert('t_user_announ', $log_announ);
            $id_user_announ = $this->db->insert_id();
    
            /////////////// SAVE SESSION ///////////
            $dt_sess = array(
                'ceksesi' => TRUE,
                'id_user_announ' => $id_user_announ
            );
            $this->session->set_userdata($dt_sess);
        }

        $descr =  encrypt_username(base64_decode($id_lembaga),'d');
        $slice_encrypt = substr($descr,19);
        $check = substr($slice_encrypt,0,-19);
        if(!$check){
            redirect('/Errcontroller');
        }

        $logo_set = $check_data->LOGO ? $check_data->LOGO : 'portal-assets/img/no_image_available.jpg';
        $data = array(
            'CARI' => ['LEMBAGA'=>$id_lembaga],
            'NAMA_LEMBAGA' =>$instansi_get->INST_NAMA,
            'LOGO_LEMBAGA' => base_url().$logo_set,
            'CHART_RESULT' => $chart_result[0],
            'IFRAME_CHART' => '<iframe id="au_frame" src="'.$base_url_tableau.'"
                                width="570" height="520"  frameborder="0" style="position:absolute;top:-10px;right:0px;left:0px;">
                        </iframe>'
        );
        $this->data['content_list'] = $this->load->view('user/pengumuman_lhkpn', $data);
    }


    function check_search_pengumuman_lhkpn() {
        /*
        $captcha = $this->input->post('captcha-announ', TRUE);
        $allowed_browser = ng::allowed_browser_version();

        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $data = array(
            'secret' => '6Ler104UAAAAAMcvPceOeUdMh2dqZYdDFrsR4pMy',
            'response' => $this->input->post("g-recaptcha-response")
        );
        $options = array(
            'http' => array (
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
         $context  = stream_context_create($options);
         $verify = file_get_contents($url, false, $context);
        */
        $captcha_success = true;
//        $captcha_success->success = true;
        
        if(!$this->session->userdata('id_user_announ')){
            redirect('/Errcontroller');
        }


        $this->db->where('IS_PUBLISH',1);
        $this->db->order_by('ID_PENGUMUMAN','DESC');
        $this->db->limit(1);
        $rp = $this->db->get('t_pengumuman')->row();

        if ($rp){
            $data['pengumuman'] = $rp->PENGUMUMAN;
        }else{
            $data['pengumuman'] = "Tidak ada pengumuman baru";
        }
        if ($captcha_success && $allowed_browser) {

        // if ($this->session->rdm_cptcha_announ == $captcha || $captcha == 'asdf'){
            $cari_announ = @$this->input->post('CARI');
            $count_arr = array_count_values($cari_announ)[""];
            $this->session->set_userdata($cari_announ);
            if ($count_arr == 2){
                redirect('portal/user/pengumuman_lhkpn/'.$cari_announ["LEMBAGA"]);
            }else{
                $this->announ_pengumuman_lhkpn();
            }
        }else{
            $this->setfailed();
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , Kode Captcha salah silahkan coba lagi');
            redirect('portal/user/pengumuman_lhkpn/'.$this->input->post('CARI')["LEMBAGA"]);
        }
    }


    function announ_pengumuman_lhkpn($ceksesi = NULL){

        $random_word = random_word(5);
        $vals = array(
            'word' => strtolower($random_word),
            'img_path' => './images/captcha/',
            'img_url' => base_url() . './images/captcha/',
            'font_path' => './system/fonts/arial.ttf',
            'font_size' => 15,
            'img_width' => '150',
            'img_height' => 28,
            'expiration' => 5
        );
        $random_word_announ = random_word(5);
        $vals_announ = array(
            'word' => strtolower($random_word_announ),
            'img_path' => './images/captcha/',
            'img_url' => base_url() . './images/captcha/',
            'font_path' => './system/fonts/arial.ttf',
            'font_size' => 15,
            'img_width' => '110',
            'img_height' => 28,
            'expiration' => 5
        );

        $this->db->where('IS_PUBLISH', 1);
        $this->db->order_by('ID_FAQ', 'DESC');
        $data['faq'] = $this->db->get('t_faq')->result();

        $this->db->where('IS_PUBLISH',1);
        $this->db->order_by('ID_PENGUMUMAN','DESC');
        $this->db->limit(1);
        $rp = $this->db->get('t_pengumuman')->row();

        if ($rp){
            $this->data['pengumuman'] = $rp->PENGUMUMAN;
        }else{
            $this->data['pengumuman'] = "Tidak ada pengumuman baru";
        }
        /*
        $cap = create_captcha($vals);
        $image_captcha = $cap['image'];
        $cap_announ = create_captcha($vals_announ);
        $image_captcha_announ = $cap_announ['image'];
        */
        $this->session->set_userdata($ceksesi);
        $_SESSION['rdm_cptcha1'] = strtolower($random_word);
        $_SESSION['ceksessi'] = $ceksesi;
        $data['image_captcha'] = ''; //$image_captcha;
        $data['image_captcha_announ'] = ''; //$image_captcha_announ;
        $data['random_word'] = strtolower($random_word);

        $data['agent_info'] = ng::get_user_agent_information();

        $allowed_browser = ng::allowed_browser_version();

//        var_dump($user_key);exit;
//        $cari_announ = @$this->input->post('CARI');
//        $count_arr = array_count_values($cari_announ)[""];
//        if ($count_arr < 4 && $count_arr != NULL){
            $this->base_url = base_url().'/portal/user/check_search_pengumuman_lhkpn/';
            $this->uri_segment = 4;
            foreach ((array) @$this->input->post('CARI') as $k => $v)
            if($k=="LEMBAGA"){
                $descr =  encrypt_username(base64_decode($this->input->post('CARI')["{$k}"]),'d');
                $slice_encrypt = substr($descr,19);
                $check_keaslian = substr($slice_encrypt,0,-19);
                if(!$check_keaslian){
                    redirect('/Errcontroller');
                }


                $this->CARI["{$k}"] = $check_keaslian;
            }else{
                $this->CARI["{$k}"] = addslashes($this->input->post('CARI')["{$k}"]);
            }

            if ($this->CARI == NULL || $this->CARI == ''){
                $cari = $this->session->userdata();
                $descr =  encrypt_username(base64_decode($cari['LEMBAGA']),'d');
                $slice_encrypt = substr($descr,19);
                $check_keaslian = substr($slice_encrypt,0,-19);
                if(!$check_keaslian){
                    redirect('/Errcontroller');
                }
                $cari['LEMBAGA'] = $check_keaslian;
                if(!$check_keaslian){
                    redirect('/Errcontroller');
                }
            }else{
                $cari = $this->CARI;
            }
            
            $state_api_pengumuman = $this->mglobal->get_data_by_id('api_pengumuman','INST_SATKERKD',$check_keaslian,false,true);
            $instansi_get = $this->mglobal->get_data_by_id('m_inst_satker','INST_SATKERKD',$check_keaslian,false,true);
            $this->data['NAMA_LEMBAGA'] = $instansi_get->INST_NAMA;
            $logo_set = $state_api_pengumuman->LOGO ? $state_api_pengumuman->LOGO : 'portal-assets/img/no_image_available.jpg';
            $this->data['LOGO_LEMBAGA'] = base_url().$logo_set;
            
            $chart_result = array(
                    ['Jumlah_WL'=>$cari['JUMLAH_WL'],
                    'Jumlah_Sudah_Lapor'=>$cari['JUMLAH_SUDAH_LAPOR'],
                    'Jumlah_Belum_Lapor'=>$cari['JUMLAH_BELUM_LAPOR']
                    ],
            );
    //        $query_chart = "SELECT inst_satkerkd, inst_nama, fu_Jumlah_WL_per_Tahun ('$check_keaslian', YEAR(NOW()) - 1) AS Jumlah_WL,
    //          fu_Jumlah_Sudah_Lapor_per_Tahun ('$check_keaslian', YEAR(NOW()) - 1) AS Jumlah_Sudah_Lapor,
    //          fu_Jumlah_WL_per_Tahun ('$check_keaslian', YEAR(NOW()) - 1) - fu_Jumlah_Sudah_Lapor_per_Tahun ('$check_keaslian', YEAR(NOW()) - 1) AS Jumlah_Belum_Lapor
    //          FROM m_inst_satker WHERE is_active = 1 AND INST_SATKERKD = '$check_keaslian'";
    //     $query_chart_exec = $this->db->query($query_chart);
    //        $chart_result = $query_chart_exec->result_array();
            
            $instansi_nama = $instansi_get->INST_NAMA;
            $inst_nama = str_replace(",", "", $instansi_nama);
            if(is_null($inst_nama)){
                $nama_inst = "";
            }
            $nama_inst = "NamaInstansi=".$inst_nama."&";

            $url_query_instansi = $nama_inst;

            $this->load->helper('tableau');
            $userTableau = $this->config->item('userTableau');
            $serverTableau = $this->config->item('serverTableau');
            $view_tableau = $this->config->item('view_tableau');
            $ripot = get_trusted_url_tableau($userTableau,$serverTableau,'views/LHKPN_eReporting_PieKepatuhan/KepatuhanLHKPN',TRUE,1);

            list($base_url_tableau, $current_parameters) = explode('?',$ripot);

            $base_url_tableau = $base_url_tableau."?".$url_query_instansi.$current_parameters;
        
            $this->data['CHART_RESULT'] = $chart_result[0];
            $this->data['IFRAME_CHART'] = '<iframe id="au_frame" src="'.$base_url_tableau.'"
                                                width="570" height="520"  frameborder="0" style="position:absolute;top:-10px;right:0px;left:0px;">
                                        </iframe>';

            $this->offset = $this->uri->segment($this->uri_segment);
    //        $this->limit = 10;
            $this->load->model('Mlhkpn');
            list($this->items, $this->total_rows) = $this->Mlhkpn->list_announ_lhkpn_pnwl_v2($cari, $this->limit, $this->offset);
    //            display($this->db->last_query());exit;
            $this->end = count($this->items);
            $this->data['title'] = 'LHKPN Masuk';
            $this->data['total_rows'] = isset($this->total_rows) ? $this->total_rows : 0;
            $this->data['offset'] = @$this->offset;
            $this->data['items'] = @$this->items;
            $this->data['start'] = @$this->offset + 1;
            $this->data['end'] = @$this->offset + @$this->end;
            $this->data['pagination'] = call_user_func('ng::genPagination_lhkpn');
            $_SESSION['rdm_cptcha'] = strtolower($random_word);
            $_SESSION['rdm_cptcha_announ'] = strtolower($random_word_announ);
            $this->data['id_lhkpn_cuk'] = $ceksesi;
            $this->data['image_captcha'] = ''; //$image_captcha;
            $this->data['random_word'] = strtolower($random_word);
            $this->data['image_captcha_announ'] = ''; //$image_captcha_announ;
            $this->data['random_word_announ'] = strtolower($random_word_announ);
            $this->data['agent_info'] = ng::get_user_agent_information();
            $this->data['faq'] = $data['faq'];


            $id_lembaga_encrypt = $cari['LEMBAGA'];
            $time_lembaga_encrypt = date('Y-m-d-H:i:s');
            $combine_lembaga_encrypt = $time_lembaga_encrypt.$id_lembaga_encrypt.$time_lembaga_encrypt;
            $encr_lembaga_encrypt = base64_encode(encrypt_username($combine_lembaga_encrypt));
            $cari['LEMBAGA'] = $encr_lembaga_encrypt;

            $data = array(
                'CARI' => $cari,
                'nobap' => $this->nobap,
                'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                    'Dashboard' => 'index.php/welcome/dashboard',
                    'E-Annoncement' => 'index.php/dashboard/eano',
                    'List Announcement' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
                )),
            );

//            $this->data = array(
//                'CARI' => @$this->CARI
//            );

            $this->data['content_paging'] = $this->load->view($this->templateDir . 'template_paging', $this->data, true);
            $this->data['content_js'] = $this->load->view($this->templateDir . 'template_js', $this->data, true);
            $this->data['content_list'] = $this->load->view('user/pengumuman_lhkpn', $data);
//        }else{
//            $this->load->view('user/login', $data);
//        }
    }

    function pengumuman_timer($id = NULL) {
        $this->db->where('IS_PUBLISH', 1);
        $this->db->order_by('ID_FAQ', 'DESC');
        $data['faq'] = $this->db->get('t_faq')->result();

        $this->load->view('user/pengumuman_timer', $data);
    }



    public function PreviewAnnounBank($id_lhkpn_old, $id_bap = NULL) {
        $id_lhkpn = encrypt_username($id_lhkpn_old,'d');
        $datapn = $this->mglobal->get_data_all(
                        'R_BA_PENGUMUMAN', [
                    ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
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
                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                        ], NULL, "t_lhkpn_data_pribadi.*, jbt.ALAMAT_KANTOR, jbt.DESKRIPSI_JABATAN, m_jbt.NAMA_JABATAN,, T_PN.NIK, T_PN.ID_PN,  inst.INST_NAMA, T_PN.TGL_LAHIR, T_PN.NHK,T_PN.NAMA, SUM(T_LHKPN_HUTANG.SALDO_HUTANG) AS jumhut, T_LHKPN.TGL_LAPOR, T_LHKPN.tgl_kirim_final, T_LHKPN.JENIS_LAPORAN, T_LHKPN.STATUS, bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA, ba.NOMOR_PNRI, ba.TGL_PNRI, ba.NOMOR_BAP, ba.TGL_BA_PENGUMUMAN, T_USER.ID_USER, IF (T_LHKPN.JENIS_LAPORAN = '4', 'Periodik', IF (T_LHKPN.JENIS_LAPORAN = '5', 'Klarifikasi', 'Khusus')) AS JENIS, T_USER.EMAIL, subunke.SUK_NAMA, T_LHKPN.TGL_KLARIFIKASI,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
                        (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6,
                        (SELECT SUM(SALDO_HUTANG) FROM t_lhkpn_hutang WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') T7 ", "T_LHKPN.ID_LHKPN = '$id_lhkpn' AND jbt.IS_PRIMARY = '1'", NULL, 0, NULL, "T_LHKPN.ID_LHKPN"
                )[0];
        if ($datapn->TGL_LAPOR == '1970-01-01' || $datapn->TGL_LAPOR == '' || $datapn->TGL_LAPOR == '-') {
            $tgl_lapor_new = $datapn->tgl_kirim_final;
        }
        else{
            $tgl_lapor_new = $datapn->TGL_LAPOR;
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
                $luas_tanah = $data->LUAS_TANAH;
            }
            if ($data->LUAS_BANGUNAN == NULL || $data->LUAS_BANGUNAN == '') {
                $luas_bangunan = '-';
            } else {
                $luas_bangunan = $data->LUAS_BANGUNAN;
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


        $this->load->library('lwphpword/lwphpword', array(
            "base_path" => APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            "base_url" => base_url() . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            "base_root" => base_url(),
        ));


        $this->load->library('lws_qr', [
            "model_qr" => "Cqrcode",
            "model_qr_prefix_nomor" => "PHK-ELHKPN-",
            "callable_model_function" => "insert_cqrcode_with_filename",
            "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
        ]);

        $filename_bap = 'uploads/FINAL_LHKPN/' . $no_bap . "/" . $datapn->NIK;
        $dir_bap = './uploads/FINAL_LHKPN/' . $no_bap . '/';

        // if (!is_dir($filename_bap)) {

        //     if (is_dir($dir_bap) === false) {
        //         mkdir($dir_bap);
        //     }
        // }

//            if (is_dir($dir_bap) == TRUE) {
        $filename = $dir_bap . $datapn->NIK . "/$output_filename";

//                if (!file_exists($filename)) {
        $dir = $dir_bap . $datapn->NIK . '/';

        if (is_dir($dir) === false) {
            mkdir($dir);
        }
        $qr_content_data = json_encode((object) [
                    "data" => [
                        (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $datapn->NAMA_LENGKAP],
                        (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]],
                        (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $datapn->BDG_NAMA],
                        (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $datapn->NAMA_JABATAN],
                        (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $datapn->INST_NAMA],
                        (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus') . " - " . show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->TGL_LAPOR, tgl_format($datapn->TGL_LAPOR))],
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

        $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $id_lhkpn . ".png");

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
                "JENIS" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus')),
                "KHUSUS" => $this->__is_cetak_var_not_blank(show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $tgl_lapor_new, tgl_format($tgl_lapor_new)),'-'),
                "TANGGAL" => $this->__is_cetak_var_not_blank(tgl_format($datapn->tgl_kirim_final),'-'),
                "TAHUN" =>  $this->__is_cetak_var_not_blank(substr($tgl_lapor_new, 0, 4),'-'),
                "TGL_BN" => tgl_format($datapn->TGL_PNRI),
                "NO_BN" => $datapn->NOMOR_PNRI,
                "PENGESAHAN" => $this->__is_cetak_var_not_blank( tgl_format($datapn->TGL_BA_PENGUMUMAN),'-'),
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
            $qr_image = "tes_qr2-" . $id_lhkpn . ".png";
            unlink($temp_dir.$qr_image);
            return;
// }
    }


    function generate_report_url(){
        $nama = $this->input->post('nama',TRUE);
        $jabatan = $this->input->post('jabatan',TRUE);
        $instansi = $this->input->post('instansi',TRUE);
        $id_lhkpn = $this->input->post('id_lhkpn',TRUE);
        $compact = encrypt_username(json_encode(array($id_lhkpn,$nama,$instansi,$jabatan)));
        $protectUrl = protectUrl();
        echo 'pelaporan/'.$compact.'/'.$protectUrl;
        return;
    }

    function generate_announcement_url(){
        $id_lhkpn = $this->input->post('id_lhkpn',TRUE);
        $compact = encrypt_username($id_lhkpn);
        echo 'PreviewAnnounBank/'.$compact;
        return;
    }


    public function compare_harta($id_lhkpn1, $id_lhkpn2 = false, $return = false)
    {  
        if (!$_COOKIE['iwanibe_token']) {
            return false; exit();
        }

        if ($id_lhkpn1) {
            $id_lhkpn1 = encrypt_username($id_lhkpn1,'d');
        }
        if ($id_lhkpn2) {
            $id_lhkpn2 = encrypt_username($id_lhkpn2,'d');
        }
        $min_tahun_lapor = 2018;
        $id_lhkpn = [];
        $id_lhkpn[] = $id_lhkpn1;
        if ($id_lhkpn2) {
            $id_lhkpn[] = $id_lhkpn2;
        }
        $pn = $this->mlhkpn->get_pn($id_lhkpn1);
        $lhkpn_all = $this->mlhkpn->get_lhkpn_announ_by_pn($pn);
        $tgl_lhkpn1 = $this->multidimensional_search($id_lhkpn1, 'ID_LHKPN', $lhkpn_all)->tgl_lapor ?: null;
        $tgl_lhkpn2 = $this->multidimensional_search($id_lhkpn2, 'ID_LHKPN', $lhkpn_all)->tgl_lapor ?: null;
        $oldest_lhkpn = strtotime($tgl_lhkpn1) < strtotime($tgl_lhkpn2) ? $id_lhkpn1 : $id_lhkpn2;
        $newest_lhkpn = strtotime($tgl_lhkpn1) > strtotime($tgl_lhkpn2) ? $id_lhkpn1 : $id_lhkpn2;
        $harta_all = $this->mlhkpn->get_all_harta_by_pn($pn, $min_tahun_lapor);
        $harta_all = $this->tree_view_harta($harta_all, $min_tahun_lapor);
        $data_jabatan = $this->mlhkpn->jabatan($id_lhkpn1);

        $options = [];
        foreach ($lhkpn_all as $lhkpnall) {
            if (($lhkpnall->ID_LHKPN != $id_lhkpn1) 
                && ($lhkpnall->entry_via == 0) 
                && (date('Y', strtotime($lhkpnall->tgl_lapor)) >= $min_tahun_lapor)
                ) {
                $options[] = [
                    'ID_LHKPN' => $lhkpnall->ID_LHKPN,
                    'ID_LHKPN_PREV' => $lhkpnall->ID_LHKPN_PREV,
                    'ID_PN' => $lhkpnall->ID_PN,
                    'IS_ACTIVE' => $lhkpnall->IS_ACTIVE,
                    'JENIS_LAPORAN' => $lhkpnall->JENIS_LAPORAN,
                    'STATUS' => $lhkpnall->STATUS,
                    'ENTRY_VIA' => $lhkpnall->entry_via,
                    'TGL_KIRIM' => $lhkpnall->tgl_kirim,
                    'TGL_KIRIM_FINAL' => $lhkpnall->tgl_kirim_final,
                    'TGL_LAPOR' => $lhkpnall->tgl_lapor,
                    'SELECTED_1' => $lhkpnall->ID_LHKPN == $id_lhkpn1 ? 1 : 0,
                    'SELECTED_2' => $lhkpnall->ID_LHKPN == $id_lhkpn2 ? 1 : 0
                ];
            }
        }

        $data_harta = [];
        foreach ($harta_all as $jenis => $hartas) {
            $data_harta[$jenis] = $this->grouping_harta_turunan($hartas, $id_lhkpn);
        }
        $data_harta['summary'] = [
            'lhkpn_terbaru' => $newest_lhkpn,
            'lhkpn_terlama' => $oldest_lhkpn
        ];
        if ($id_lhkpn2) {
            $data_harta['summary']['lhkpn_perbandingan1'] = $tgl_lhkpn1;
            $data_harta['summary']['lhkpn_perbandingan2'] = $tgl_lhkpn2;
        }
        // echo json_encode($data_harta); exit;

        // get selisih harta
        foreach ($data_harta as $jenis_harta => $val_jenis_harta) {
            if ($jenis_harta != 'summary') {
                foreach ($val_jenis_harta as $harta => $val_harta) {
                    $selisih_val_harta = null;
                    $persentase_val_harta = null;
                    
                    if ($harta != 'summary') {
                        // get selisih
                        if ($id_lhkpn2) {
                            $selisih_val_harta = $val_harta['summary'][$newest_lhkpn] - $val_harta['summary'][$oldest_lhkpn];
                        }
                        if ($id_lhkpn2 && $val_harta['summary'][$oldest_lhkpn]) {
                            $persentase_val_harta = ($selisih_val_harta/$val_harta['summary'][$oldest_lhkpn]) * 100;
                        }
                        $data_harta[$jenis_harta][$harta]['summary']['selisih'] = $selisih_val_harta;
                        $data_harta[$jenis_harta][$harta]['summary']['persentase'] = $persentase_val_harta;
                    }
                }
                $selisih_jenis_harta = null;
                $persentase_jenis_harta = null;
                if ($id_lhkpn2 && $val_jenis_harta['summary'][$oldest_lhkpn]) {
                    $selisih_jenis_harta = $data_harta[$jenis_harta]['summary'][$newest_lhkpn] - $data_harta[$jenis_harta]['summary'][$oldest_lhkpn];
    
                    // hitung persentase
                    if ($id_lhkpn2 && $val_jenis_harta['summary'][$oldest_lhkpn]) {
                        $persentase_jenis_harta = ($selisih_jenis_harta/$val_jenis_harta['summary'][$oldest_lhkpn]) * 100;
                    }
                }
                $data_harta[$jenis_harta]['summary']['selisih'] = $selisih_jenis_harta;
                $data_harta[$jenis_harta]['summary']['persentase'] = $persentase_jenis_harta;
            }
            
        }

        // get total harta
        $total_harta1 = null;
        $total_harta1 -= $data_harta['dh']['summary'][$id_lhkpn1];
        $total_harta1 += $data_harta['dhb']['summary'][$id_lhkpn1];
        $total_harta1 += $data_harta['dhbl']['summary'][$id_lhkpn1];
        $total_harta1 += $data_harta['dhk']['summary'][$id_lhkpn1];
        $total_harta1 += $data_harta['dhl']['summary'][$id_lhkpn1];
        $total_harta1 += $data_harta['dhsb']['summary'][$id_lhkpn1];
        $total_harta1 += $data_harta['dhtb']['summary'][$id_lhkpn1];
        $data_harta['summary']['total'][$id_lhkpn1] = $total_harta1;
        $data_harta['summary']['total']['selisih'] = null;
        $data_harta['summary']['total']['persentase'] = null;
        $data_harta['summary']['subtotal'][$id_lhkpn1] = $total_harta1 + $data_harta['dh']['summary'][$id_lhkpn1];
        $data_harta['summary']['subtotal']['selisih'] = null;
        $data_harta['summary']['subtotal']['persentase'] = null;

        if ($id_lhkpn2) {
            $total_harta2 = null;
            $total_harta2 -= $data_harta['dh']['summary'][$id_lhkpn2];
            $total_harta2 += $data_harta['dhb']['summary'][$id_lhkpn2];
            $total_harta2 += $data_harta['dhbl']['summary'][$id_lhkpn2];
            $total_harta2 += $data_harta['dhk']['summary'][$id_lhkpn2];
            $total_harta2 += $data_harta['dhl']['summary'][$id_lhkpn2];
            $total_harta2 += $data_harta['dhsb']['summary'][$id_lhkpn2];
            $total_harta2 += $data_harta['dhtb']['summary'][$id_lhkpn2];

            $data_harta['summary']['total'][$id_lhkpn2] = $total_harta2;
            $data_harta['summary']['total']['selisih'] = $data_harta['summary']['total'][$newest_lhkpn] - $data_harta['summary']['total'][$oldest_lhkpn];
            $data_harta['summary']['total']['persentase'] = $data_harta['summary']['total']['selisih'] !== null ? ($data_harta['summary']['total']['selisih']/$data_harta['summary']['total'][$oldest_lhkpn])*100 : null;

            $data_harta['summary']['subtotal'][$id_lhkpn2] = $total_harta2 + $data_harta['dh']['summary'][$id_lhkpn2];
            $data_harta['summary']['subtotal']['selisih'] = $data_harta['summary']['subtotal'][$newest_lhkpn] - $data_harta['summary']['subtotal'][$oldest_lhkpn];
            $data_harta['summary']['subtotal']['persentase'] = $data_harta['summary']['subtotal']['selisih'] !== null ? ($data_harta['summary']['subtotal']['selisih']/$data_harta['summary']['subtotal'][$oldest_lhkpn])*100 : null;
        }
        $columns = array_column($options, 'ID_LHKPN');
        array_multisort($columns, SORT_DESC, $options);
        $harta = [
            'options' => $options,
            'data_harta' => $data_harta,
            'pn' => $data_jabatan
        ];

        $harta = $this->encrypt_compare($harta, $id_lhkpn1, $id_lhkpn2);

        if ($return) {
            return $harta;
        } else {
            echo (json_encode($harta));exit;
        }
    }

    public function preview_compare_harta($id_lhkpn1, $id_lhkpn2 = false)
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors', '1');
        
        $get_data = $id_lhkpn2 ? $this->compare_harta($id_lhkpn1, $id_lhkpn2, true) : $this->compare_harta($id_lhkpn1, false, 1);
        if ($get_data) {
            $lhkpn_1 = $this->multidimensional_search($id_lhkpn1, 'ID_LHKPN', $get_data['lhkpn_all']);
            $lhkpn_2 = $id_lhkpn2 ? $this->multidimensional_search($id_lhkpn1, 'ID_LHKPN', $get_data['lhkpn_all']) : null;
            $get_data['lhkpn'] = (object) [
                'id_lhkpn_1' => $id_lhkpn1,
                'id_lhkpn_2' => $id_lhkpn2 ?: null,
                'tgl_lhkpn_1' => date('Y', strtotime($lhkpn_1->tgl_lapor)),
                'tgl_lhkpn_2' => $id_lhkpn2 ? date('Y', strtotime($lhkpn_2->tgl_lapor)) : null
            ];
        }
        unset($get_data['lhkpn_all']);

        $this->load->library('pdfgenerator');
        $html = $this->load->view('filing/export_pdf/pengumuman_compare', $get_data, true);

        $pn_nhk = $get_data['pn']->NHK == NULL ? '-' : $get_data['pn']->NHK;
        $filename = "Pengumuman_Perbandingan_Harta_Kekayaan_LHKPN_" . $pn_nhk;
        $method = "stream";
        $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait');
        /////////////////////////////TUTUP PDF GENERATOR///////////////////////////
        return;
    }

    private function grouping_harta_turunan($arr_harta, $arr_lhkpn)
    {
        $results = [];
        // default var pelporan = 0
        foreach ($arr_lhkpn as $lhkpn) {
            $nilai_pelaporan[$lhkpn] = 0;
        }

        foreach ($arr_harta as $item) {
            if (in_array($item->ID_LHKPN, $arr_lhkpn)) {
                // $results[$item->id_harta_lvl0][$item->ID_LHKPN] = $item;
                $results[$item->ID][$item->id_harta_lvl0][$item->ID_LHKPN] = $item;
            }
        }

        // makesure dalam masing-masing harta terdapat beberapa lhkpn berdasarkan kiriman arr_lhkpn
        // create summary subharta dan all harta
        $summary_harta = [];

        foreach ($results as $id_harta => $hartas) {

            foreach ($hartas as $id_harta_lvl0 => $harta){
                $summary_subharta = [];
                foreach ($arr_lhkpn as $lhkpn) {
                    if (!$harta[$lhkpn]) {
                        $results[$id_harta][$id_harta_lvl0][$lhkpn] = null;
                    }
                    $summary_subharta[$lhkpn] = $harta[$lhkpn]->NILAI_EQUIVALEN 
                        ?: ($harta[$lhkpn]->NILAI_PELAPORAN 
                            ?: ($harta[$lhkpn]->SALDO_HUTANG 
                                ?: ($harta[$lhkpn]->NILAI_SALDO ?: 0)));
                    $summary_subharta['DESKRIPSI'] = !empty($summary_subharta['DESKRIPSI']) ? $summary_subharta['DESKRIPSI'] : ($harta[$lhkpn]->DESKRIPSI ?: '');
                    $results[$id_harta][$id_harta_lvl0]['summary'] = $summary_subharta;
                    $results[$id_harta][$id_harta_lvl0]['summary']['persentase'] = 0;
                    $results[$id_harta][$id_harta_lvl0]['summary']['selisih'] = 0;

                    // sum all harta
                    $summary_harta[$lhkpn] += $harta[$lhkpn]->NILAI_EQUIVALEN 
                    ?: ($harta[$lhkpn]->NILAI_PELAPORAN 
                        ?: ($harta[$lhkpn]->SALDO_HUTANG 
                            ?: ($harta[$lhkpn]->NILAI_SALDO ?: 0)));
                }
            }
        }

        /// --- start coding iwan --- ///
        /*foreach ($results as $id_harta_lvl0 => $harta) {
            $summary_subharta = [];
            foreach ($arr_lhkpn as $lhkpn) {
                if (!$harta[$lhkpn]) {
                    $results[$id_harta_lvl0][$lhkpn] = null;
                }
                $summary_subharta[$lhkpn] = $harta[$lhkpn]->NILAI_EQUIVALEN 
                    ?: ($harta[$lhkpn]->NILAI_PELAPORAN 
                        ?: ($harta[$lhkpn]->SALDO_HUTANG 
                            ?: ($harta[$lhkpn]->NILAI_SALDO ?: 0)));
                $summary_subharta['DESKRIPSI'] = !empty($summary_subharta['DESKRIPSI']) ? $summary_subharta['DESKRIPSI'] : ($harta[$lhkpn]->DESKRIPSI ?: '');
                $results[$id_harta_lvl0]['summary'] = $summary_subharta;
                $results[$id_harta_lvl0]['summary']['persentase'] = 0;
                $results[$id_harta_lvl0]['summary']['selisih'] = 0;

                // sum all harta
                $summary_harta[$lhkpn] += $harta[$lhkpn]->NILAI_EQUIVALEN 
                ?: ($harta[$lhkpn]->NILAI_PELAPORAN 
                    ?: ($harta[$lhkpn]->SALDO_HUTANG 
                        ?: ($harta[$lhkpn]->NILAI_SALDO ?: 0)));
            }
        }*/
        /// --- end coding iwan --- ///

        $results['summary'] = $summary_harta;

        return $results;
    }

    public function tree_view_harta($all_harta, $min_tahun_lapor)
    {
        $result = $all_harta;
        // loop 1 (key = jenis harta)
        foreach ($all_harta as $jenis_harta => $hartas) {
            foreach ($hartas as $harta) {
                $level = 0;
                $previous_harta = null;
                $first_harta = $harta->ID ?: $harta->ID_HUTANG;
                if ($harta->Previous_ID == true || $harta->Previous_ID != null || !empty($harta->Previous_ID)) {
                    if (date('Y', strtotime($harta->tgl_lapor)) > $min_tahun_lapor) {
                        $index = $harta->ID ? 'ID' : 'ID_HUTANG';
                        $previous_harta = $this->multidimensional_search($harta->Previous_ID, $index, $hartas);
                        $level = $previous_harta->level + 1;

                        // get first harta (id level 0)
                        if ($level == 1) {
                            $first_harta = $previous_harta->ID ?: $previous_harta->ID_HUTANG;
                        } else {
                            $first_harta = $previous_harta->id_harta_lvl0;
                        }
                    }
                }

                $harta->level = $level;
                $harta->previous_harta = $previous_harta;
                $harta->id_harta_lvl0 = $first_harta;
            }
        }

        return $result;
    }

    private function multidimensional_search($word, $key, $data_multidimensional)
    {
        foreach ($data_multidimensional as $key_data => $val_data) {
            if ($val_data->{$key} === $word) {
                return $val_data;
            }
        }
        return null;
    }

    private function encrypt_compare($arr_data, $id_lhkpn1, $id_lhkpn2 = false)
    {
        $result = [];
        $result['options'] = $arr_data['options'];
        $result['pn'] = $arr_data['pn'];
        
        ////////////// for options //////////////
        foreach ($arr_data['options'] as $key_options => $val_options) {
            // echo json_encode($result['options']); exit;
            $result['options'][$key_options]['ID_LHKPN'] = encrypt_username($result['options'][$key_options]['ID_LHKPN']);
            $result['options'][$key_options]['ID_LHKPN_PREV'] = encrypt_username($result['options'][$key_options]['ID_LHKPN_PREV']);
            $result['options'][$key_options]['ID_PN'] = encrypt_username($result['options'][$key_options]['ID_PN']);
        }

        ////////////// for pn //////////////
        $result['pn']->ID_LHKPN = encrypt_username($arr_data['pn']->ID_LHKPN);

        ////////////// for data_harta //////////////
        $data_harta = $arr_data['data_harta'];
        // echo json_encode($data_harta); exit;
        $harta_summary = $data_harta['summary'];
        $harta_subtotal = $harta_summary['subtotal'];
        $harta_total = $harta_summary['total'];
        
        $result['data_harta']['summary']['subtotal'] = [
            'persentase' => $harta_subtotal['persentase'],
            'selisih' => $harta_subtotal['selisih'],
            encrypt_username($id_lhkpn1) => $arr_data['data_harta']['summary']['subtotal'][$id_lhkpn1]
        ];
        $result['data_harta']['summary']['total'] = [
            'persentase' => $harta_total['persentase'],
            'selisih' => $harta_total['selisih'],
            encrypt_username($id_lhkpn1) => $harta_total[$id_lhkpn1]
        ];
        
        if ($harta_summary['lhkpn_terbaru']) {
            $result['data_harta']['summary']['lhkpn_terbaru'] = encrypt_username($arr_data['data_harta']['summary']['lhkpn_terbaru']);
        }
        if ($harta_summary['lhkpn_terlama']) {
            $result['data_harta']['summary']['lhkpn_terlama'] = encrypt_username($arr_data['data_harta']['summary']['lhkpn_terlama']);
        }
        if ($id_lhkpn2) {
            $result['data_harta']['summary']['lhkpn_perbandingan1'] = $harta_summary['lhkpn_perbandingan1'];
            $result['data_harta']['summary']['lhkpn_perbandingan2'] = $harta_summary['lhkpn_perbandingan2'];
            if (isset($harta_subtotal[$id_lhkpn2])) {
                $result['data_harta']['summary']['subtotal'][encrypt_username($id_lhkpn2)] = $harta_subtotal[$id_lhkpn2];
            }
            if (isset($harta_total[$id_lhkpn2])) {
                $result['data_harta']['summary']['total'][encrypt_username($id_lhkpn2)] = $harta_total[$id_lhkpn2];
            }
        }

        foreach ($data_harta as $key_harta => $val_harta) {
            
            if ($key_harta != 'summary') {
                foreach ($data_harta[$key_harta] as $key => $values) {

                    foreach($values as $index => $val){

                        $result['data_harta'][$key_harta][$key][$index] = [
                            'summary' => [
                                'LHKPN_1' => $data_harta[$key_harta][$key][$index]['summary'][$id_lhkpn1],
                                'DESKRIPSI' => $data_harta[$key_harta][$key][$index]['summary']['DESKRIPSI'],
                                'persentase' => $data_harta[$key_harta][$key][$index]['summary']['persentase'],
                                'selisih' => $data_harta[$key_harta][$key][$index]['summary']['selisih']
                            ]
                            
                        ];
                        if ($id_lhkpn2) {
                            $result['data_harta'][$key_harta][$key][$index]['summary']['LHKPN_2'] =  $data_harta[$key_harta][$key][$index]['summary'][$id_lhkpn2];
                        }
                    }
                
                }
                
                $result['data_harta'][$key_harta]['summary'] = [
                    'persentase' => $data_harta[$key_harta]['summary']['persentase'],
                    'selisih' => $data_harta[$key_harta]['summary']['selisih'],
                    encrypt_username($id_lhkpn1) => $data_harta[$key_harta]['summary'][$id_lhkpn1]
                ];
                
                if ($id_lhkpn2) {
                    if (isset($data_harta[$key_harta]['summary'][$id_lhkpn2])) {
                        $result['data_harta'][$key_harta]['summary'][encrypt_username($id_lhkpn2)] = $data_harta[$key_harta]['summary'][$id_lhkpn2];
                    }
                }
            }

            /// --- start coding iwan --- ///
            /*if ($key_harta != 'summary') {
                foreach ($data_harta[$key_harta] as $key => $value) {
                    $result['data_harta'][$key_harta][$key] = [
                        'summary' => [
                            'LHKPN_1' => $data_harta[$key_harta][$key]['summary'][$id_lhkpn1],
                            'DESKRIPSI' => $data_harta[$key_harta][$key]['summary']['DESKRIPSI'],
                            'persentase' => $data_harta[$key_harta][$key]['summary']['persentase'],
                            'selisih' => $data_harta[$key_harta][$key]['summary']['selisih']
                        ]
                        
                    ];
                    if ($id_lhkpn2) {
                        $result['data_harta'][$key_harta][$key]['summary']['LHKPN_2'] =  $data_harta[$key_harta][$key]['summary'][$id_lhkpn2];
                    }
                }
                $result['data_harta'][$key_harta]['summary'] = [
                    'persentase' => $data_harta[$key_harta]['summary']['persentase'],
                    'selisih' => $data_harta[$key_harta]['summary']['selisih'],
                    encrypt_username($id_lhkpn1) => $data_harta[$key_harta]['summary'][$id_lhkpn1]
                ];
                if ($id_lhkpn2) {
                    if (isset($data_harta[$key_harta]['summary'][$id_lhkpn2])) {
                        $result['data_harta'][$key_harta]['summary'][encrypt_username($id_lhkpn2)] = $data_harta[$key_harta]['summary'][$id_lhkpn2];
                    }
                }
            }*/
            /// --- end coding iwan --- ///

        }
        return $result;
    }

    public function cekcookies()
    {
        if ($_COOKIE['iwanibe_token']) {
            if ($_COOKIE['iwanibe_token'] != hash('sha256', 'iwankeren'.strtotime('Y-m-d H:i'))) {
                print_r ($_COOKIE['iwanibe_token']);
            }
            print_r ($_COOKIE['iwanibe_token']);
        } else {
            print_r ($_COOKIE['iwanibe_token'] . ' - ' . hash('sha256', 'iwankeren'.strtotime('Y-m-d H:i')));
        }
    }

}
