<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller Profile
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller
{
    // num of records per page
    public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
    }

    /** Profile
     * 
     * @return html Profile
     */
    public function index()
    {
        $sql = "SELECT
            T_USER.USERNAME,
            T_USER.ID_USER,
            T_USER.EMAIL,
            T_USER.HANDPHONE,
            T_USER.NAMA,
            T_USER.LAST_LOGIN,
            from_unixtime(T_USER.LAST_LOGIN) as LAST_LOGIN,
            T_USER.ID_ROLE,
            T_USER.INST_SATKERKD,
            M_INST_SATKER.INST_SATKERKD,
            M_INST_SATKER.INST_NAMA,
            T_USER_ROLE.ROLE,
            T_USER_ROLE.COLOR,
            T_USER.PHOTO
            FROM
            T_USER
            LEFT JOIN T_USER_ROLE ON T_USER.ID_ROLE = T_USER_ROLE.ID_ROLE
            LEFT JOIN M_INST_SATKER ON T_USER.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD
            WHERE T_USER.ID_USER = '".$this->session->userdata('ID_USER')."'";
        $user = $this->db->query($sql)->row();

        $data = array(
            'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'Profile'   => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            'user'      => $user,
        );
        $this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }

    public function changePassword($id){
        $data['ID'] = $id;
        $this->load->view('v_changepswd', $data);
    }

    public function changeEmail($id){
        $data['ID'] = $id;
        $this->load->model('mglobal');
        $data['email'] = $this->mglobal->get_data_all('T_USER', NULL, ['ID_USER' => $this->session->userdata('ID_USER')], 'email')[0]->email;
        $this->load->view('v_changeemail', $data);
    }

    public function cekpswdLama(){
        $this->load->model('mglobal', '', TRUE);
        $ID    = $this->input->post('ID');
        $DT    = $this->input->post('PARAM');
        $where = [ 
                    'SUBSTRING(md5(ID_USER), 6, 8) =' => $ID,
                    'PASSWORD' => sha1(md5($DT))
        ];
        $data  = count($this->mglobal->get_data_all('T_USER', NULL, $where));
        if($data > 0){ echo 'ada'; }else{ echo 'kosong'; }
    }

    public function dochangeEmail()
    {
        $this->load->model('mglobal');
        $email      = $this->input->post('email');
        $password   = sha1(md5($this->input->post('password')));
        $idUser     = $this->session->userdata('ID_USER');

        $this->db->trans_begin();

        $count = $this->mglobal->count_data_all('T_USER', NULL, ['ID_USER' => $idUser, 'PASSWORD' => $password]);
        if($count){
            $cekEmail = $this->mglobal->count_data_all('T_USER', NULL, ['ID_USER !=' => $idUser, 'EMAIL' => $email]);
            if($cekEmail == '0'){
                $res = $this->mglobal->update('T_USER', ['EMAIL' => $email], ['ID_USER' => $idUser]);
                if($res){
                    $msg = [
                        'status' => '1',
                        'msg' => 'Email berhasil Diubah!'
                    ];
                }else{
                    $msg = [
                        'status' => '0',
                        'msg' => 'Update Email Error!'
                    ];
                }
            }else{
                $msg = [
                    'status' => '0',
                    'msg' => 'Email sudah digunakan!'
                ];
            }
        }else{
            $msg = [
                'status' => '0',
                'msg' => 'Password salah!'
            ];
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo json_encode($msg);
    }

    public function dochangePassword()
    {
        if ($_POST) {
            $this->load->model('muser', '', TRUE);
            if (($this->input->post('pwdBaru2', TRUE) == $this->input->post('pwdBaru', TRUE)) && ($this->input->post('pwdBaru2', TRUE) != '')) {
                $USERNAME = $this->session->userdata('USERNAME');

                $PASSWORDLAMA = $this->input->post('pwdLama', TRUE);
                $PASSWORDBARU = $this->input->post('pwdBaru2', TRUE);

                $this->load->model('mglobal');
                $result = $this->mglobal->get_data_all('T_USER', NULL, ['PASSWORD' => $USERNAME, 'PASSWORD' => sha1(md5($PASSWORDLAMA))]);

                if(empty($result)){
                    echo '0';die;
                }


                $this->db->trans_begin();
                $data = array(
                    'PASSWORD' => sha1(md5($PASSWORDBARU))
                );
                $this->db->where('USERNAME', $USERNAME);
                $this->db->where('PASSWORD', sha1(md5($PASSWORDLAMA)));

                $this->db->update('T_USER', $data);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                }
                echo intval($this->db->trans_status());
                // return $this->muser->changePassword($PEG_NIP_BARU, $PASSWORDLAMA, $PASSWORDBARU); // deprecated
            } else {
                echo '0';
            }
        }
    }

    function updateFoto(){
        $this->db->trans_begin();
        $this->load->model('muser', '', TRUE);

        $url       = '';
        $maxsize   = 2000000;
        $username  = $this->input->post('USERNAME', TRUE);
        $poto      = @$_FILES['FILE_FOTO'];

        $extension = strtolower(@substr(@$poto['name'],-4));
        $type_file = array('.jpg','.png','.jpeg','.tiff', '.tif');

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

        $hasil = '';
        $fileSK      = @$_FILES['FILE_FOTO'];
        $extension   = strtolower(@substr(@$fileSK['name'],-4));
        if ($fileSK['size'] <= $maxsize) {
            if (in_array($extension, $type_file)) {
                $c          = save_file($poto['tmp_name'], $poto['name'], $poto['size'], "./uploads/users/".$username."/", 0, 10000);
                if($poto['size'] != '' && $poto['size'] <= $maxsize){
                    $url        = time()."-". trim($poto['name']);
                    $hasil = 'success';
                }
            }
        }

        $user = array( 
            'ID_USER' => $this->input->post('ID_USER', TRUE)
        );

        if (!empty($url)) {
            $user['PHOTO'] = $url;
            $url_file      = $this->input->post('tmp_PHOTO', TRUE);
            unlink("./uploads/users/".$username."/".$url_file);
        }

        $user['ID_USER']   = $this->input->post('ID_USER', TRUE);
        $this->muser->update($user['ID_USER'], $user);

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }
}