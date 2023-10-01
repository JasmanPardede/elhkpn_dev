<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller{

	private $t_menu = 'T_MENU';
	function __Construct(){
		parent::__Construct();
		
		call_user_func('ng::islogin');
		$this->load->model('mrole','mrole');
		$this->load->model('mmenu','mmenu');
        	$this->load->model('Muser', 'muser');
		$this->ID_PN = $this->session->userdata('ID_PN');
		if($this->ID_PN ){
	        $this->check_lhkpn_draft = $this->db->where("ID_PN = ". $this->ID_PN ." AND IS_ACTIVE = 1 AND JENIS_LAPORAN <> '5' AND entry_via = '0' AND (STATUS = '0' OR STATUS = '2')")->count_all_results("t_lhkpn");
		}else{
			$this->check_lhkpn_draft = 0;
		}
	}

	function index(){
		//print_r($this->session->userdata);

		$notice = $this->session->userdata('NOTICE');
		if($notice){
			$this->session->set_userdata('NOTICE', $notice+=1);
		}else{
			$this->session->set_userdata('NOTICE', 1);
		}

		$shortcut = array();
		$id_parent = 0;
		$id_user_role   = $this->session->userdata('ID_ROLE');

		$this->db->select('*');
		$this->db->order_by('PARENT');
		$this->db->order_by('WEIGHT');
		$this->db->where('t_user_akses.ID_ROLE',$id_user_role);
		$this->db->where('t_menu.IS_ACTIVE','1');
		$this->db->where('t_menu.PARENT','0');
		$this->db->join('t_menu','t_menu.ID_MENU = t_user_akses.ID_MENU');
		$this->db->join('t_user_role','t_user_role.ID_ROLE = t_user_akses.ID_ROLE');
		$data = $this->db->get('t_user_akses')->result();

		if($data){

			foreach($data as $menu){

                if (!$menu->URL || $menu->URL == '') {
                    $link = base_url() . 'index.php?dpg='.make_secure_text(strtolower(trim($menu->MENU))).'#index.php/welcome/dashboard';
                } else {
                    $link = base_url() . 'index.php?dpg='.make_secure_text(strtolower(trim($menu->MENU))).'#' . $menu->URL;
                }

				$btn = NULL;
				if($menu->PARENT!=0 || $menu->ICON_COLOR==NULL || $menu->ICON_COLOR==''){
					$btn = 'btn-admin';
				}else{
					$btn = $menu->ICON_COLOR;

				}

				$shortcut[] = array(
        			'nama'=>$menu->MENU,
        			'link'=>$link,
        			'button'=>$btn
        		);
			}
		}
		if($this->session->userdata('ID_ROLE')==5){
		    $this->db->like('ID_ROLE',5);
		    // $this->db->where('ID_ROLE',5);
		}elseif($this->session->userdata('ID_ROLE')!=5){
		    $this->db->like('ID_ROLE',1);
		    // $this->db->where('ID_ROLE!=',5);
		}
   		$this->db->where('IS_PUBLISH',1);
        $this->db->order_by('ID_PENGUMUMAN','DESC');
        $this->db->limit(1);
        $rp = $this->db->get('t_pengumuman')->row();
        if($rp){
        	$pengumuman = $rp->PENGUMUMAN;
        }else{
        	$pengumuman = "Tidak ada pengumuman baru";
        }


		$options = array(
			'title'=>'e-LHKPN',
			'NOTICE'=>$notice,
            'check_lhkpn_draft' => $this->check_lhkpn_draft,
		);
		$this->load->view('template/header',$options);
		$this->load->view('home/index',array('shortcut'=>$shortcut,'pengumuman'=>$pengumuman));
		$this->load->view('template/footer_user',$options);
	}

	function panduan(){
		$options = array(
			'title'=>'Panduan',
            'check_lhkpn_draft' => $this->check_lhkpn_draft,
		);
		$this->load->view('template/header',$options);
		$this->load->view('home/bantuan',$options);
		$this->load->view('template/footer',$options);
	}

	function faq(){
		$this->db->where('IS_PUBLISH',1);
        $this->db->order_by('ID_FAQ','DESC');
        $faq = $this->db->get('t_faq')->result();
		$options = array(
			'title'=>'FAQ',
            'check_lhkpn_draft' => $this->check_lhkpn_draft,
		);
		$this->load->view('template/header',$options);
		$this->load->view('home/faq',array('title'=>'FAQ','faq'=>$faq));
		$this->load->view('template/footer',$options);
	}


    function input($table,$empty=null){
    	$data = $this->db->query('DESC  '.$table)->result_array();
    	$input = array();
    	foreach($data as $row){
    		if($empty){
    			echo "'".$row['Field']."' =>  , ";
    		}else{
    			echo "'".$row['Field']."' => xthis->input->post('".$row['Field']."'), ";
    		}
    		echo "<br>";
    	}
    }

	function profil() {
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

	

	function update_pribadi() {
//        $id_user = $this->input->post('ID_USER');
        $id_user = $this->session->userdata('ID_USER');
        $username = $this->input->post('username');
        $handphone = $this->input->post('handphone');
        $this->db->where('username', $username);
        $this->db->where('ID_USER !=', $id_user);
        $failed = $this->db->get('t_user')->result();

        if ($failed)
        {
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , username sudah ada yang menggunakan !!');
        }else {
            $this->db->where('ID_USER', $id_user);
            $user = $this->db->get('T_USER')->row();

            if ($_FILES['file']['name']) {
                
                //////////SISTEM KEAMANAN///////////
                $post_nama_file = 'file';
				$extension_diijinkan = array('jpg','png','jpeg');
                $check_protect = protectionDocument($post_nama_file,$extension_diijinkan);
                if($check_protect){
                    $method = __METHOD__;
                    $this->load->model('mglobal');
                    $this->mglobal->recordLogAttacker($check_protect,$method);
                    $this->session->set_flashdata('error_message', 'error_message');
                    $this->session->set_flashdata('message','Dokument tidak diijinkan !!');
                    redirect('portal/user');
                    return;
                }   
                //////////SISTEM KEAMANAN///////////

                if (file_exists('images/' . $user->PHOTO) && $user->PHOTO) {
                    unlink('images/' . $user->PHOTO);
                }
                $ext = end((explode(".", $_FILES['file']['name'])));
                if($id_user==1){
                    $file_name = sha1($id_user) . '.' . $ext;
                }else{
                    $file_name = md5($id_user) . '.' . $ext;
                }
                $uploaddir = 'images/' . $file_name;
                if(switchMinio()){
                    $resultMinio = uploadToMinio('file', $file_name, 'images/');
                    if(!$resultMinio){
                        $this->session->set_flashdata('error_message', 'error_message');
                        $this->session->set_flashdata('message', 'Gagal upload foto !!');
                        redirect('portal/user');
                    }else{
                        $this->db->where('ID_USER', $id_user);
                        $update_foto = $this->db->update('t_user', array('STORAGE_MINIO'=>storageDiskMinio(),'PHOTO' => $file_name, 'UPDATED_TIME' => date("Y-m-d H:i:s"), 'UPDATED_BY' => $this->session->userdata('NAMA'), 'UPDATED_IP' => $this->get_client_ip()));
                        if ($update_foto) {
                            $this->session->set_userdata('PHOTO', $file_name);
                        }
                    }
                }else{
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir)) {
                        $this->db->where('ID_USER', $id_user);
                        $update_foto = $this->db->update('t_user', array('STORAGE_MINIO'=>storageDiskMinio(),'PHOTO' => $file_name, 'UPDATED_TIME' => date("Y-m-d H:i:s"), 'UPDATED_BY' => $this->session->userdata('NAMA'), 'UPDATED_IP' => $this->get_client_ip()));
                        if ($update_foto) {
                            $this->session->set_userdata('PHOTO', $file_name);
                        }
                    }
                }
            }

            $this->db->where('ID_USER', $id_user);

            $update = $this->db->update('t_user', array(
                // 'USERNAME' => $username, [mod]
                'HANDPHONE' => $handphone,
                'UPDATED_TIME' => date("Y-m-d H:i:s"),
                'UPDATED_BY' => $this->session->userdata('NAMA'),
                'UPDATED_IP' => $this->get_client_ip()));

            if ($update) {
                $this->session->set_userdata('USERNAME', $username);
                $this->session->set_flashdata('success_message', 'success_message');
                $this->session->set_flashdata('message', 'Data Pribadi berhasil di update !!');
            } else {
                $this->session->set_flashdata('error_message', 'error_message');
                $this->session->set_flashdata('message', 'Mohon Maaf , ada kesalahan sistem !!');
            }
        }
        redirect('portal/user');
    }

    function update_password() {
//        $id_user = $this->input->post('ID_USER');
        $id_user = $this->session->userdata('ID_USER');
        $old_password = $this->input->post('old_password');
        $new_password = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');
        
        //// cek new password tidak sama & tidak identik dg old password ////
        if(strpos($new_password, $old_password) !== false){
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf, password baru tidak boleh sama atau identik dengan password lama !!');
            $this->session->set_flashdata('password_alert', 'password_alert');
            redirect('portal/user');
        }

        if($new_password != $confirm_password){  
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf, password baru dan konfirmasi password baru harus sama !!');
            $this->session->set_flashdata('password_alert', 'password_alert');
            redirect('portal/user');
        }

        $valid_length = false;
        $valid_lowerLetter = false;
        $valid_upperLetter = false;
        $valid_number = false;
        $valid_simbol = false;

        if(strlen($new_password) >= 8){
			$valid_length = true;
		}

		if(preg_match("/[a-z]/",$new_password)){
			$valid_lowerLetter = true;
        }

		if(preg_match("/[A-Z]/",$new_password)){
			$valid_upperLetter = true;
        }
        
        if(preg_match("/\d/",$new_password)){
			$valid_number = true;
        }
        
        if(preg_match("/[!@#$%^&*()]/",$new_password)){
			$valid_simbol = true;
        }
		
        $salt = $this->muser->get_by_id($id_user)->row()->SALT;
        $old_pwd_hash = md5($old_password);

        if($salt == null){
            $pass_old = md5($old_password); //encryp tanpa salt
            $cek = $this->muser->cek_password($id_user, $pass_old, true);
        }else{
            $cek = $this->muser->cek_password($id_user, $old_pwd_hash.$salt);
        }

        if ($cek && $valid_length && $valid_lowerLetter && $valid_upperLetter && $valid_number && $valid_simbol) { 
            $new_pwd_hash = md5($new_password);
            $this->db->where('ID_USER', $id_user);
            $update = $this->db->update('t_user', array('PASSWORD' => sha1(md5($new_pwd_hash.$salt)), 'UPDATED_TIME' => date("Y-m-d H:i:s"), 'UPDATED_BY' => $this->session->userdata('NAMA'), 'UPDATED_IP' => $this->get_client_ip()));
            if ($update) {
                $this->session->set_flashdata('success_message', 'success_message');
                $this->session->set_flashdata('message', 'Password berhasil di update !!');
            } else {
                $this->session->set_flashdata('error_message', 'error_message');
                $this->session->set_flashdata('message', 'Mohon Maaf , ada kesalahan sistem !!');
            }
        } else {
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , password lama anda salah !!');
        }
        $this->session->set_flashdata('password_alert', 'password_alert');
        redirect('portal/user');
    }

    function update_email() {
//        $id_user = $this->input->post('ID_USER');
        $id_user = $this->session->userdata('ID_USER');
        $new_email = $this->input->post('new_email');
        $this->db->where('ID_USER !=', $id_user);
        $this->db->where('EMAIL', $new_email);
        $exist_email = $this->db->get('t_user')->row();
        $ID_PN = $this->session->userdata('ID_PN');
        if ($exist_email) {
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , email sudah digunakan !!');
        } else {
            $this->db->where('ID_USER', $id_user);
            $update = $this->db->update('t_user', array('EMAIL' => $new_email, 'UPDATED_TIME' => date("Y-m-d H:i:s"), 'UPDATED_BY' => $this->session->userdata('NAMA'), 'UPDATED_IP' => $this->get_client_ip()));

            $this->db->where('ID_PN', $ID_PN);
            $update = $this->db->update('t_pn', array('EMAIL' => $new_email, 'UPDATED_TIME' => date("Y-m-d H:i:s"), 'UPDATED_BY' => $this->session->userdata('NAMA'), 'UPDATED_IP' => $this->get_client_ip()));
            if ($update) {
                $this->session->set_flashdata('success_message', 'success_message');
                $this->session->set_flashdata('message', 'Email berhasil di update !!');
            } else {
                $this->session->set_flashdata('error_message', 'error_message');
                $this->session->set_flashdata('message', 'Mohon Maaf , ada kesalahan sistem !!');
            }
        }
        $this->session->set_flashdata('email_alert', 'email_alert');
        redirect('portal/user');
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

}