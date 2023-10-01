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
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller
{
    function __construct(){
        parent::__construct();
        $sql = "SELECT *
            FROM `core_setting`
            WHERE SETTING = 'productkey'";
        $this->pk = $this->db->query($sql)->row();
    }

    /**
     * Login form
     *
     * @return html login form
     */
    public function index(){
        $random_word = random_word(5);
        $user_agent = ng::get_user_agent_information();
        /*
        $this->load->helper('captcha');


//        gwe_dump($user_agent, TRUE);

        $vals = array(
            'word'   => strtolower($random_word),
            'img_path'   => './images/captcha/',
            'img_url'    => base_url() . './images/captcha/',
            'font_path'  => './system/fonts/arial.ttf',
            'font_size'  => 24,
            'img_width'  => '150',
            'img_height' => 60,
            'expiration' => 5
        );

        $cap = create_captcha($vals);
        $image_captcha = ''; //$cap['image'];
        $_SESSION['rdm_cptcha'] = strtolower($random_word);
        $data['image_captcha']  = $image_captcha;
        $data['random_word']    = strtolower($random_word);
        */
        $_SESSION['rdm_cptcha'] = strtolower($random_word);
        $data['random_word']    = strtolower($random_word);        
        $data['agent_info'] = ng::get_user_agent_information();
        $data['image_captcha']  = '';
        //$this->load->view('login', $data);
        redirect('portal/user/login');
    }
    /**
     * Logout process redirect to login form
     *
     * @return html login form
     */
    public function reload_captcha() {
        $random_word = random_word(5);
        //$user_agent = ng::get_user_agent_information();
        /*
        $this->load->helper('captcha');
        $random_word = random_word(5);
        $vals = array(
            'word'   => strtolower($random_word),
            'img_path'   => './images/captcha/',
            'img_url'    => base_url() . './images/captcha/',
            'font_path'  => './system/fonts/arial.ttf',
            'font_size'  => 15,
            'img_width'  => '150',
            'img_height' => 28,
            'expiration' => 5
        );

        $cap = create_captcha($vals);

        $image_captcha = $cap['image'];
        $_SESSION['rdm_cptcha'] = strtolower($random_word);
        echo json_encode(array('image' => $image_captcha, 'value' => strtolower($random_word)));
        */
        $_SESSION['rdm_cptcha'] = strtolower($random_word);
        echo json_encode(array('image' => '', 'value' => strtolower($random_word)));
    }
    public function reload_captcha_announ() {
        $random_word = random_word(5);
        //$user_agent = ng::get_user_agent_information();
        /*
        $this->load->helper('captcha');
        $random_word = random_word(5);
        $vals = array(
            'word'   => strtolower($random_word),
            'img_path'   => './images/captcha/',
            'img_url'    => base_url() . './images/captcha/',
            'font_path'  => './system/fonts/arial.ttf',
            'font_size'  => 15,
            'img_width'  => '110',
            'img_height' => 28,
            'expiration' => 5
        );

        $cap = create_captcha($vals);
        $image_captcha = $cap['image'];
        $_SESSION['rdm_cptcha_announ'] = strtolower($random_word);
        echo json_encode(array('image' => $image_captcha, 'value' => strtolower($random_word)));
        */
        $_SESSION['rdm_cptcha_announ'] = strtolower($random_word);
        echo json_encode(array('image' => '', 'value' => strtolower($random_word)));
    }
    public function logout()    {
        $this->load->model('muser', '', TRUE);
        $this->muser->doUpLogin($this->session->userdata('USR'), '0');
        $this->session->sess_destroy();

        $newdata = array(
            'USR' => '',
            'logged_in' => FALSE,
            'NAMA' => ''
        );
        $this->session->set_userdata($newdata);

        /*
        $this->load->helper('captcha');
        $random_word = random_word(5);
        $vals = array(
            'word'   => strtolower($random_word),
            'img_path'   => './images/captcha/',
            'img_url'    => base_url() . './images/captcha/',
            'font_path'  => './system/fonts/arial.ttf',
            'font_size'  => 24,
            'img_width'  => '150',
            'img_height' => 60,
            'expiration' => 5
        );

        $cap = create_captcha($vals);
        $image_captcha = $cap['image'];
        $_SESSION['rdm_cptcha'] = strtolower($random_word);
        $data['image_captcha']  = $image_captcha;
        $data['random_word']    = strtolower($random_word);
        */
        $_SESSION['rdm_cptcha'] = strtolower($random_word);
        $data['random_word']    = strtolower($random_word);
        $data['image_captcha']  = '';

        //$this->load->view('login', $data);
        redirect(base_url());
    }
    /**
     * Login process & session register
     *
     * @return boolean
     */
    public function dologin()    {

        if (!empty($_POST) ) {
            if($this->session->userdata('next_login_time')>time()){ // gak boleh masih masa menunggu
                echo 0;
                return;
            }
            else if($this->session->userdata('failed_login')>3){ // boleh login lagi jika waktu kurang dari dan failed > 3 dengan catatan
                $newdata = array('failed_login'=>0, 'next_login_time'=> time());
                $this->session->set_userdata($newdata);
            }

            $usr = $this->input->post('usr', TRUE);
            $pwd = $this->input->post('pwd', TRUE);

            $this->load->model('muser', '', TRUE);
            $rs = $this->muser->ceklogin($usr, $pwd);
            if ( $rs ) {
                $user = $rs;
                $newdata = array(
                    'USR' => $usr,
                    'logged_in'     => TRUE,
                    'ID_USER'       => $user->ID_USER,
                    'IS_FIRST'      => $user->IS_FIRST,
                    'USERNAME'      => $user->USERNAME,
                    'NAMA_INSTANSI_AKRONIM' => $user->INST_AKRONIM,
                    'NAMA'          => $user->NAMA,
                    'ID_ROLE'       => $user->ID_ROLE,
                    'INST_SATKERKD' => $user->INST_SATKERKD,
                    'UK_ID'         => $user->UK_ID,
					'UK_nama'       => $user->UK_nama,
                    'IS_UK'         => $user->IS_UK,
                    'PHOTO'         => $user->PHOTO,
                    'INST_NAMA'     => $user->INST_NAMA,
                    'LAST_LOGIN'    => $this->muser->getLastLogin($user->USERNAME),
                    'IS_KPK'        => $user->IS_KPK,
                    'productkey'    => substr(substr($this->pk->VALUE, 3),11),
                );

                $sql = "SELECT * FROM T_USER_ROLE WHERE ID_ROLE = '$user->ID_ROLE'";
                $role = $this->db->query($sql)->row();
                $newdata = array_merge($newdata, array('IS_PN'=>$role->IS_PN, 'IS_INSTANSI'=>$role->IS_INSTANSI, 'IS_KPK'=>$role->IS_KPK));

                if($role->IS_PN){
                    $newdata = array_merge($newdata, array('NIK'=>$user->USERNAME));

                    $sql = "SELECT ID_PN FROM T_PN where NIK='$user->USERNAME'";
                    $tmp = $this->db->query($sql)->row();

                    $newdata['ID_PN']   = $tmp->ID_PN;
                }

                $this->muser->updateLastLogin($user->USERNAME);
                // $this->muser->insertLog($user->USERNAME);

                $this->session->set_userdata($newdata);
                $this->session->set_userdata(array('splash'=>'1'));

                $captcha = $this->input->post('captcha', TRUE);
                $exists_captcha =  $this->input->post('sec', TRUE);

                if ( $captcha == $_SESSION['rdm_cptcha'] ) {
                    if($rs->IS_LOGIN == '0') {
                        echo '1';
                        $this->muser->doUpLogin($user->USERNAME, '1');
                    } else {
                        echo '3';
                    }
                } else {
                    echo '2';
//                    $this->setfailed();
                }

            } else {
                echo '0';
                $this->setfailed();
            }
        }
        else{
            redirect(base_url('index.php/welcome'));
        }
    }
    public function dochangepassword () {
        $this->load->model('muser');
        $id_user            = $this->session->userdata('ID_USER');
        $password_lama      = $this->input->post('password_lama');
        $password_baru      = $this->input->post('password_baru');
        $ulangi_password    = $this->input->post('ulangi_password');
        $check_password     = $this->muser->cek_password($id_user, $password_lama);

        if ( $check_password ) {
            if ( empty($password_baru) ) {
                echo 'Password baru tidak boleh kosong!';
            } else if ( $password_baru != $ulangi_password ) {
                echo 'Pengulangan password tidak valid!';
            } else {
                $this->db->trans_begin();
                $data_update = array(
                    'IS_FIRST'  => 0,
                    'PASSWORD'  => sha1(md5($password_baru))
                );
                $update = $this->muser->update($id_user, $data_update);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                }
                $status = intval($this->db->trans_status());
                if ( $status == 1 ) {
                    $this->session->unset_userdata('IS_FIRST');
                    echo $status;
                } else {
                    echo 'Password gagal disimpan, terjadi kesalahan dalam sistem';
                }

            }
        } else {
            echo 'Password lama tidak valid!';
        }
    }
    private function setfailed(){
        if($this->session->userdata('failed_login')>0){
            $newdata = array('failed_login'=>$this->session->userdata('failed_login')+1);
        }else{
            $newdata = array('failed_login'=>1);
        }
        $this->session->set_userdata($newdata);
    }

    public function loginalert()    {
        if($this->session->userdata('failed_login')<3){
            echo 'Gagal login ke ';
            echo $this->session->userdata('failed_login');
        }
        else{
            $newdata = array('next_login_time'=> time() + 300); // time add
            $this->session->set_userdata($newdata);
            echo 'Anda Harus Menunggu 5 Menit untuk dapat login kembali!';
//            echo $this->session->userdata('failed_login');
        }
    }

    public function lupapassword(){
        $this->load->view('lupapassword');
    }

    public function resetpassword($data){
        $this->load->model('muser', '', TRUE);
        $getData = $this->muser->getKey($data);
        $req['REQUEST_RESET']          = @$getData[0]->REQUEST_RESET;
        $req['REQUEST_RESET_KEY']      = @$getData[0]->REQUEST_RESET_KEY;
        $req['REQUEST_RESET_TIME']     = @$getData[0]->REQUEST_RESET_TIME;
        $req['REQUEST_RESET_EXPIRED']  = @$getData[0]->REQUEST_RESET_EXPIRED;
        $req['USERNAME']               = @$getData[0]->USERNAME;
        $req['IS_FIRST']               = '1';
        $vldt                          = $this->muser->getUserValid($req);

        if($vldt == '1'){
            $req['status'] = 'sukses';
        }else{
            $req['status'] = 'gagal';
        }

        $this->load->view('resetpassword',$req);
    }

    public function token_failed(){
            $this->load->view('token_failed');
    }


    public function doupdtPasswordNew(){
        $this->load->model('muser', '', TRUE);
        $data['USERNAME']           = $this->input->post('username');
        $data['PASSWORD']           = sha1(md5($this->input->post('password')));
        $data['REQUEST_RESET']      = 0;
        $data['REQUEST_RESET_KEY']  = $this->input->post('reqKey');

        $updatePassword = $this->muser->doUpPasNew($data);
        if($updatePassword){
            echo "berhasil";
        }else{
            echo "gagal";
        }

    }

    public function doresetpassword(){
        $this->load->model('mglobal');

        if ($_POST != '') {

            $email      = $this->input->post('email', TRUE);
            $join       = [['table' => 'T_USER_ROLE role', 'on' => 'user.ID_ROLE = role.ID_ROLE']];
            $check      = $this->mglobal->get_data_all('T_USER user', $join, ['EMAIL' => $email, 'user.IS_ACTIVE' => '1'], 'user.ID_USER, user.USERNAME, user.EMAIL, user.ID_ROLE, role.ROLE');
            $exp        = $this->mglobal->get_data_all('T_RESET_PASSWORD', null, ['EMAIL' => $email, 'EXP >' => time()], 'EMAIL, EXP');

            if(!empty($check)){
                if (empty($exp)) {

                    $subject = 'Konfirmasi Reset Password';

                    $value   = '';
                    foreach ($check as $key) {
                        $token  = sha1($email.$key->USERNAME.time());
                        $value .=   '<div>
                                        Username : '.$key->USERNAME.'
                                    </div>
                                    <div><br></div>
                                    <div>
                                        Role : '.$key->ROLE.'
                                    </div>
                                    <div><br></div>
                                    <div>
                                        Jika anda yakin untuk mereset password anda, silahkan klik link dibawah ini :
                                    </div>
                                    <div><br></div>
                                    <div>
                                        <a href="'.base_url().'index.php/auth/token_check/'.$token.'" target="_blank">'.base_url().'index.php/auth/token_check/'.$token.'</a><br>
                                    </div>
                                    <div><br></div>';
                        $dataToken[] = array('token' => $token, 'id_user' => $key->ID_USER);
                    }

                    $message = '
                    <div>
                        Email ini berisi konfirmasi Reset Password akun anda.
                    </div>
                    <div>
                        <br>
                    </div>
                    '.$value;

                    $kirim_email = ng::mail_send($email, $subject, $message);

                    if ( $kirim_email ) {
                        $this->db->trans_begin();

                        $data = array(
                                    'EMAIL' => $email,
                                    'EXP'   => time()+3600,
                                );
                        $id_reset = $this->mglobal->insert('T_RESET_PASSWORD', $data);
                        $last_id  = $this->db->insert_id();
                        if ($id_reset) {
                            foreach ($dataToken as $key) {
                                $data = array(
                                        'TOKEN'     => $key['token'],
                                        'ID_USER'   => $key['id_user'],
                                        'DATETIME'  => time()+3600,
                                        'ID_RESET_PASSWORD' => $last_id,
                                    );
                                $this->mglobal->insert('T_RESET_PASSWORD_TOKEN', $data);
                            }
                        }

                        if ($this->db->trans_status() === FALSE){
                            $this->db->trans_rollback();
                        }else{
                            $this->db->trans_commit();
                        }
                        $status = intval($this->db->trans_status());
                        if ( $status == 1 )
                            echo $status;
                        else
                            echo 'Password gagal direset, terjadi kesalahan dalam sistem';
                    } else {
                        echo 'Gagal mengirim email';
                    }
                    return ;
                }else{
                    echo 'Tidak bisa mereset password, tunggu beberapa saat lagi!';
                    return ;
                }
            } else {
                echo 'Reset password gagal, email tidak valid';
                return ;
            }

        }else{
            echo 0;
            return ;
        }
    }

    function token_check($token = '')
    {
        $this->load->model('mglobal');
        $getDataToken = $this->mglobal->get_data_all('T_RESET_PASSWORD_TOKEN', null, ['TOKEN' => $token, 'IS_ACTIVE' =>'1']);

        if (!empty($getDataToken)) {

            $date = $getDataToken[0]->DATETIME;

            if ($date > time()) {
                $this->load->view('change_password2', array('token' => $token));
            } else {
                redirect('auth/token_failed','refresh');
                echo 'Token sudah tidak berlaku!';
                return ;
            }
        } else {
            redirect('auth/token_failed','refresh');
            echo 'Token tidak ada!';
            return ;
        }

    }

    public function newPassword()
    {
        $this->load->model('mglobal');
        $password_baru      = $this->input->post('password_baru');
        $ulangi_password    = $this->input->post('ulangi_password');
        $token              = $this->input->post('token');

        $getDataToken = $this->mglobal->get_data_all('T_RESET_PASSWORD_TOKEN', null, ['TOKEN' => $token]);
        if ( $password_baru == $ulangi_password ) {
            if (!empty($getDataToken)) {

                $date = $getDataToken[0]->DATETIME;

                if ($date > time()) {
                    $update = $this->mglobal->update('T_USER', ['PASSWORD' => sha1(md5($password_baru))], ['ID_USER' => $getDataToken[0]->ID_USER]);

                    if ($update) {
                        $this->mglobal->update('T_RESET_PASSWORD_TOKEN', ['IS_ACTIVE' => '0'], ['ID_TOKEN' => $getDataToken[0]->ID_TOKEN]);
                        // echo $this->db->last_query();
                        echo '1';
                        return;
                    } else {
                        echo 'Password gagal dirubah!';
                    }
                } else {
                    echo 'Token Tidak Berlaku Lagi!';
                    return ;
                }
            } else {
                echo 'Token Tidak Tersedia!';
                return ;
            }
        } else {
            echo 'Pengulangan password tidak valid!';
        }
    }

    public function is_login($user) {
        $this->load->model('muser', '', TRUE);
        $this->muser->doUpLogin($user, '0');
        echo json_encode(array('status' => '1'));
    }
}