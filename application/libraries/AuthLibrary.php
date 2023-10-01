<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class authlibrary {

    var $CI;

    function authlibrary() {
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->helper(array('form', 'url', 'email'));
        $this->CI->load->library('form_validation');
        $this->CI->load->library('session');
    }

    function IsSuperAdmin($username, $password) {
        if ($username == "superadmin") {
            $hashedSHA1 = $this->CI->config->item('hashed');

            if ($password == $hashedSHA1) {
                return TRUE;
            }
        }

        return FALSE;
    }

    function Login() {
        $username = $this->CI->input->post('username');
        $passwordasli = $this->CI->input->post('password');
        $password = do_hash(($this->CI->input->post('password'))); //sha1 , //dohash(set_value('password'), 'md5');
        $passwordmd5 = '{MD5}' . base64_encode(md5(strtoupper($this->CI->input->post('password')), TRUE));

        if ($this->IsSuperAdmin($username, $password)) {
            $key = $this->CI->config->item('KPKkey');

            $string = $passwordasli; // note the spaces

            $encryptedpassword = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));

            $datauser = array(
                'username' => 'superadmin',
                'password' => $encryptedpassword,
                'iduser' => '',
                'kodeuser' => 0,
//                'id_supervisor' => 19, //development
                'id_supervisor' => '', //production
                'role' => 1,
                'logged_in' => TRUE
            );

            $this->CI->session->set_userdata($datauser);

            $this->goto_homepage();
        }

        $success = 'TRUE';

        $this->CI->load->model('Users');
        $userdata = $this->CI->Users->select_ref_user_by_username($username);
        //$userdata = $this->CI->db->query("select * from sc_dumas.fu_select_ref_user_by_username('{$username}')");
        $myiduser = '';

        if ($userdata->num_rows() > 0) {
            $rowuser = $userdata->row_array();
            $myiduser = $rowuser['id_user'];

            if ($myiduser != '') {
                $this->CI->load->helper('ldap');
                $success = cek_login($username, $passwordasli); //TRUE;

                if (!$success) {
                    $this->CI->session->set_flashdata('success', 'Anda gagal login ke LDAP.');
                }

                $key = $this->CI->config->item('KPKkey');

                $string = $passwordasli; // note the spaces

                $encryptedpassword = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));


                $datauser = array(
                    'username' => $rowuser['username'],
                    'id_supervisor' => $rowuser['id_supervisor'],
                    'password' => $encryptedpassword,
                    'iduser' => $myiduser,
                    //'nip' => $rowuser['NOMOR_PEGAWAI'],
                    //'unitkerja' => $rowuser['kdbiro'],
                    //'role' => 1,
                    'logged_in' => TRUE
                );

                $this->CI->session->set_userdata($datauser);
            } else {
                $success = FALSE;
                $this->CI->session->set_flashdata('success', 'Anda belum terdaftar sebagai pengguna aplikasi ini.');
            }
        } else {
            $success = FALSE;
            $this->CI->session->set_flashdata('success', 'Anda belum terdaftar sebagai pengguna aplikasi ini.');
        }


        if (!$success) {
            redirect(base_url() . 'login', 'refresh');
        } else {
            //catat last login
            $client_ip = getip();

            $this->CI->load->model('Users');
            $query = $this->CI->Users->update_ref_user_last_login($myiduser, $client_ip);

            //$query = $this->CI->db->query("select * from sc_dumas.fu_update_ref_user_last_login('{$myiduser}', '{$client_ip}')");

            $this->CI->session->set_flashdata('success', 'TRUE');
            $this->goto_homepage();
        }
    }
    private function goto_homepage() {
        $current_location = $this->CI->session->userdata('current_location');
        if ($current_location) {
            redirect(base_url() . $current_location, 'refresh');
        }
        redirect(base_url() . 'home', 'refresh');
    }

    //username
    function loggedin() {
        if ($this->CI->session->userdata('logged_in') == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function preserveauth() {
        $this->CI->session->keep_flashdata('success');
    }

    function username() {

        return $this->CI->session->userdata('username');
    }

    function pass() {

        return $this->CI->session->userdata('password');
    }

    function iduser() {

        return $this->CI->session->userdata('iduser');
    }

    function id_supervisor() {

        return $this->CI->session->userdata('id_supervisor');
    }

    function is_supervisor() {

        if ($this->CI->session->userdata('id_supervisor') == $this->CI->session->userdata('iduser')) {
            return true;
        } else {
            return false;
        }
    }

    function role() {
        $iduser = $this->CI->session->userdata('iduser');

        if ($this->CI->session->userdata('username') == "superadmin") {
            $trole = "Administrator";
            return $trole;
        }

        $this->CI->load->model('Users');
        $userrole = $this->CI->Users->select_ref_user_roles_by_id_user($iduser);

        //$userrole = $this->CI->db->query("select * from sc_dumas.fu_select_ref_user_roles_by_id_user('{$iduser}') as (id_roles integer, nama_roles varchar)");

        $count = $userrole->num_rows();

        $role_mod = $userrole->result();



        if ($count == 0) {
            $trole = ' Belum punya Role ';
            return $trole;
        }

        if ($count == 1) {
            $trole = "";
            $trole = $role_mod[0]->nama_roles;
            return $trole;
        } else {
            for ($i = 0; $i < $count; $i++) {
                if ($i == 0) {
                    $trole = $role_mod[$i]->nama_roles;
                } else {
                    $trole = $trole . ' - ' . $role_mod[$i]->nama_roles;
                }
            }
            return $trole;
        }
    }

    function module() {
        $iduser = $this->CI->session->userdata('iduser');

        if ($this->CI->session->userdata('username') == "superadmin") {
            $tmodule = "*";

            return $tmodule;
        }

        $this->CI->load->model('Users');
        $query = $this->CI->Users->select_ref_modul_roles_by_id_user($iduser);

        //$query = $this->CI->db->query("select * from sc_dumas.fu_select_ref_modul_roles_by_id_user('{$iduser}') as (nama_modul varchar)");

        $count = $query->num_rows();

        $modules = $query->result();

        if ($count == 0) {
            $tmodule = ' Tidak ada Module yang boleh dilihat ';
            return $tmodule;
        }

        if ($count == 1) {
            $tmodule = "";

            $tmodule = $modules[0]->nama_modul;
            return $tmodule;
        } else {
            for ($i = 0; $i < $count; $i++) {
                if ($i == 0) {
                    $tmodule = $modules[$i]->nama_modul;
                } else {
                    $tmodule = $tmodule . '|' . $modules[$i]->nama_modul;
                }
            }
            return $tmodule;
        }
    }

    function ListModulAuth($KolomACL) {
        $iduser = $this->CI->session->userdata('iduser');

        if ($this->CI->session->userdata('username') == "superadmin") {
            $tmodule = "*";

            return $tmodule;
        }

        $this->CI->load->model('Users');
        $query = $this->CI->Users->select_KolomACL_ref_modul_roles_by_id_user($iduser, $KolomACL);

        //$query = $this->CI->db->query("select * from sc_dumas.fu_select_KolomACL_ref_modul_roles_by_id_user('{$iduser}', '{$KolomACL}') as (nama_modul varchar)");

        $count = $query->num_rows();

        $modules = $query->result();

        if ($count == 0) {
            $tmodule = ' Tidak ada ';
            return $tmodule;
        }

        if ($count == 1) {
            $tmodule = "";

            $tmodule = $modules[0]->nama_modul;
            return $tmodule;
        } else {
            for ($i = 0; $i < $count; $i++) {
                if ($i == 0) {
                    $tmodule = $modules[$i]->nama_modul;
                } else {
                    $tmodule = $tmodule . '|' . $modules[$i]->nama_modul;
                }
            }
            return $tmodule;
        }
    }

    function kelompokmodule() {
        $iduser = $this->CI->session->userdata('iduser');

        if ($this->CI->session->userdata('username') == "superadmin") {
            $tmodule = "*";

            return $tmodule;
        }

        $this->CI->load->model('Users');
        $query = $this->CI->Users->select_kelompok_modul_ref_modul_roles_by_id_user($iduser);

        //$query = $this->CI->db->query("select * from sc_dumas.fu_select_kelompok_modul_ref_modul_roles_by_id_user('{$iduser}') as (kelompok_modul varchar)");

        $count = $query->num_rows();

        $modules = $query->result();

        if ($count == 0) {
            $tmodule = ' Tidak ada ';
            return $tmodule;
        }

        if ($count == 1) {
            $tmodule = "";

            $tmodule = $modules[0]->kelompok_modul;
            return $tmodule;
        } else {
            for ($i = 0; $i < $count; $i++) {
                if ($i == 0) {
                    $tmodule = $modules[$i]->kelompok_modul;
                } else {
                    $tmodule = $tmodule . '|' . $modules[$i]->kelompok_modul;
                }
            }
            return $tmodule;
        }
    }

    function checkAuth($self_redirect = FALSE) {

        if ($this->loggedin() == FALSE) {

            if (IS_AJAX) {
                header('HTTP/1.1 401 Unauthorized');
            } else {
                if (!$self_redirect) {
                redirect(base_url() . 'login', 'refresh');
                }
                return FALSE;
            }
        } else {
            if ($this->isAllowReadModule($this->CI->uri->rsegment(1)) == FALSE) {
                if (!$self_redirect) {
                redirect(base_url() . 'login', 'refresh');
                }
            }
            return TRUE;
        }
    }

    function isOneOfTheRoles($roles) {

        $myrole = $this->role();

        $arrroles = preg_split("/\ - /", $myrole);

        foreach ($arrroles as $role) {

            if ($roles == $role OR $role == "*")
                return TRUE;
        }

        return FALSE;
    }

    function isAllowReadModule($modulename) {

        $mymodule = $this->module();
        $arrmodule = preg_split("/\|/", $mymodule);
        foreach ($arrmodule as $module) {
            if ($module == $modulename OR $module == "*")
                return TRUE;
        }
        return FALSE;
    }

    function isAllowModule($modulename, $KolomACL) {

        $mymodule = $this->ListModulAuth($KolomACL);
        $arrmodule = preg_split("/\|/", $mymodule);
        foreach ($arrmodule as $module) {
            if ($module == $modulename OR $module == "*")
                return TRUE;
        }
        return FALSE;
    }

    function isAllowReadKelompokModule($kelompokmodule) {

        $mykelmodule = $this->kelompokmodule();
        $arrkelmodule = preg_split("/\|/", $mykelmodule);
        foreach ($arrkelmodule as $kelmodule) {
            if ($kelmodule == $kelompokmodule OR $kelmodule == "*")
                return TRUE;
        }
        return FALSE;
    }

    function setRoles($roles) {

        $myrole = $this->role();
        $arrroles = preg_split("/\|/", $roles);
        $allowed = FALSE;

        foreach ($arrroles as $role) {
            if ($myrole == $role OR $role == "*") {
                $allowed = TRUE;
                break;
            }
        }

        if ($allowed == FALSE)
            redirect(base_url() . 'home', 'refresh');
    }

    function logout() {

        $this->CI->session->sess_destroy();

        redirect(base_url() . 'login', 'refresh');
    }

}

?>
