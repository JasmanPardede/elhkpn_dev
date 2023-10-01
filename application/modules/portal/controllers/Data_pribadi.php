<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

DEFINE('ENCRYPTION_KEY', 'd0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282');
DEFINE ('FTP_USER','yourUser');
DEFINE ('FTP_PASS','yourPassword');


class Data_pribadi extends MY_Controller {

    function __Construct() {
        parent::__Construct();
        $this->TABLE = 't_lhkpn_data_pribadi';
        call_user_func('ng::islogin');
    }

    function data($ID_LHKPN) {
        $this->load->model('mlhkpndatapribadi');

        $data = $this->mlhkpndatapribadi->get_by_id_lhkpn($ID_LHKPN);

        header('Content-Type: application/json');
        echo json_encode($data);
		exit;
    }

    function get($col, $table, $value, $return) {
        $result = FALSE;
        $this->db->like($col, $value);
        $this->db->limit(1);
        $data = $this->db->get($table)->row();
        if ($data) {
            $result = $data->$return;
        }
        return $result;
    }

    function get_select($table, $name) {
        if($table == 'm_agama'){
            $this->db->where('IS_ACTIVE','1');
        }
        $this->db->order_by($name);
        $data = $this->db->get($table)->result();

        $html = '<option></option>';
        foreach ($data as $row) {
            $html .= '<option value="' . $row->$name . '">' . strtoupper($row->$name) . '</option>';
        }
        return $html;
    }

	function encrypt( $string, $action = 'e' ) {
    // you may change these values to your own
		$secret_key = 'R@|-|a5iaKPK';
		$secret_iv = 'R@|-|a5ia|/|394124';

		$output = false;
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $secret_key );
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

		if( $action == 'e' ) {
			$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		}
		else if( $action == 'd' ){
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		}

		return $output;exit;
}

	function update() {

        $state_id = $this->input->post('ID');
        $state_id_lhkpn = $this->input->post('ID_LHKPN');

        ////////////////SISTEM KEAMANAN////////////////
        $state_id_pn = $this->session->userdata('ID_PN');
        $check_protect = protectFilling($state_id_pn,$this->TABLE,$state_id);
        if($check_protect){
            $method = __METHOD__;
            $this->load->model('mglobal');
            $this->mglobal->recordLogAttacker($check_protect,$method);
            echo 9;
            return;
        }
        ////////////////SISTEM KEAMANAN////////////////


        ////////////////SISTEM KEAMANAN////////////////
            $state_id_pn = $this->session->userdata('ID_PN');
            $check_protect = protectLhkpn($state_id_pn,$state_id_lhkpn);
            if($check_protect){
                $method = __METHOD__;
                $this->load->model('mglobal');
                $this->mglobal->recordLogAttacker($check_protect,$method);
                echo 9;
                return;
            }
        ////////////////SISTEM KEAMANAN////////////////

        $this->load->model('mglobal', '', TRUE);
        $user = $this->session->userdata('NAMA');
        $negara = $this->input->post('NEGARA');
        $NIK = $this->input->post('NIK');

        if ($negara == 1) {
            $ID_NEGARA = 'ID';
            $PROVINSI = $this->get('ID_PROV', 'm_area_prov', $this->input->post('ID_PROPINSI'), 'NAME');
            $KABKOT = $this->get('ID_KAB', 'm_area_kab', $this->input->post('ID_KOTA'), 'NAME_KAB');
            $KECAMATAN = $this->input->post('KECAMATAN');
            $KELURAHAN = $this->input->post('KELURAHAN');
            $ALAMAT_RUMAH = $this->input->post('ALAMAT_RUMAH');
            $ALAMAT_NEGARA = NULL;
        } else {
            $ID_NEGARA = $this->input->post('ID_NEGARA');
            $PROVINSI = NULL;
            $KABKOT = NULL;
            $KECAMATAN = NULL;
            $KELURAHAN = NULL;
            $ALAMAT_RUMAH = NULL;
            $ALAMAT_NEGARA = $this->input->post('ALAMAT_NEGARA');
        }

        //////////SISTEM KEAMANAN///////////
                $post_nama_file = 'FILE_FOTO';
				$extension_diijinkan = array('jpg','png','jpeg');
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

		$NIK_Enc =$this->encrypt($NIK,'e');
        $NIKLengkap = $NIK_Enc.$NIK;//var_dump($NIKLengkap); exit;

        $maxsize = 1000000;
        $filefoto = $_FILES['FILE_FOTO']; //var_dump($_FILES['FILE_FOTO']); exit;
        $extension = strtolower(@substr(@$filefoto['name'], -4));
        $type_file = array('.jpg', '.png', '.jpeg', '.tiff', '.tif');
        $filename = 'uploads/data_pribadi/' . $NIKLengkap . '/readme.txt';
        $linkfoto = 'uploads/data_pribadi/' . $NIKLengkap . '/foto.jpg';
		$destination='/home/elhkpn/uploads/' . $NIKLengkap . '/';
        //var_dump($NIK_Enc); exit;
        if (!file_exists($filename)) {
            $dir = './uploads/data_pribadi/' . $NIKLengkap . '/';


            $file_to_write = 'readme.txt';
            $content_to_write = "FOTO PN Dari " . $user . ' dengan nik ' . $NIK;

            if (is_dir($dir) === false) {
                mkdir($dir);
            }
			if (is_dir($destination) === false) {
                if(mkdir($destination)){ echo "Sukses Vrohh"; }
				else{echo "Cannot write to file ($filename)";}
            }
			//var_dump($filename , $destination);exit;
            $file = fopen($dir . '/' . $file_to_write, "w");

            fwrite($file, $content_to_write);
			// closes the file
            fclose($file);
        }

        $ext = end((explode(".", $filefoto['name'])));
        if (file_exists($linkfoto) && is_uploaded_file($_FILES['myfile']['tmp_name']))
            unlink($linkfoto);
//        $namefile = 'foto.'.$ext;
        $namefile = 'foto.jpg';

        if ($filefoto['error'] == 0) {
            $extension = strtolower(@substr(@$filefoto['name'], -4));

            if (in_array($extension, $type_file) && $filefoto['size'] != '' && $filefoto['size'] <= $maxsize) {

                if(switchMinio()){
                    //proses save to minio
                    $resultMinio = uploadToMinio('FILE_FOTO','foto.jpg', "uploads/data_pribadi/$NIKLengkap/");
                    if(!$resultMinio){
                      // do action!
                    }
                }else{
                    $c = save_file_name($filefoto['tmp_name'], $namefile, $filefoto['size'], "./uploads/data_pribadi/$NIKLengkap", 10000);
                }
            }
        }

        $data = array(
//            'ID_LHKPN' => $this->input->post('ID_LHKPN'),
            'GELAR_DEPAN' => $this->input->post('GELAR_DEPAN'),
            'GELAR_BELAKANG' => $this->input->post('GELAR_BELAKANG'),
            'NAMA_LENGKAP' => $this->input->post('NAMA_LENGKAP'),
            'JENIS_KELAMIN' => $this->input->post('JENIS_KELAMIN'),
            'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR'),
            'TANGGAL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post("TANGGAL_LAHIR")))),
//            'NIK' => $this->input->post('NIK'),
            'NPWP' => $this->input->post('NPWP'),
            'NO_KK' => $this->input->post('KK'),
            'STATUS_PERKAWINAN' => $this->input->post('STATUS_PERKAWINAN'),
            'AGAMA' => $this->input->post('AGAMA'),
            'ALAMAT_RUMAH' => $ALAMAT_RUMAH,
            'EMAIL_PRIBADI' => $this->input->post('EMAIL_PRIBADI'),
            'PROVINSI' => $PROVINSI,
            'KABKOT' => $KABKOT,
            'KECAMATAN' => $KECAMATAN,
            'KELURAHAN' => $KELURAHAN,
            'TELPON_RUMAH' => $this->input->post('TELPON_RUMAH'),
            'HP' => $this->input->post('HP'),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
            'KD_ISO3_NEGARA' => $ID_NEGARA,
            'NEGARA' => $negara,
            'STORAGE_MINIO' => storageDiskMinio(),
            'FOTO' => @$filefoto['name'],
            'ALAMAT_NEGARA' => $ALAMAT_NEGARA,
            'NIP' => $this->input->post('NIP')
        );

        $nikah = $this->mglobal->get_by_id('m_status_nikah', 'STATUS_NIKAH', $this->input->post('STATUS_PERKAWINAN'));
        $agama = $this->mglobal->get_by_id('m_agama', 'AGAMA', $this->input->post('AGAMA'));

        $data_pn = array(
            'GELAR_DEPAN' => $this->input->post('GELAR_DEPAN'),
            'GELAR_BELAKANG' => $this->input->post('GELAR_BELAKANG'),
            'NAMA' => $this->input->post('NAMA_LENGKAP'),
            'JNS_KEL' => $this->input->post('JENIS_KELAMIN'),
            'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR'),
            'TGL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post("TANGGAL_LAHIR")))),
            'NIK' => $this->input->post('NIK'),
            'NPWP' => $this->input->post('NPWP'),
            'ID_STATUS_NIKAH' => $nikah->ID_STATUS,
            'ID_AGAMA' => $agama->ID_AGAMA,
            'ALAMAT_TINGGAL' => $ALAMAT_RUMAH,
            'EMAIL' => $this->input->post('EMAIL_PRIBADI'),
            'PROV' => $PROVINSI,
            'KAB_KOT' => $KABKOT,
            'KEC' => $KECAMATAN,
            'KEL' => $KELURAHAN,
            'NO_HP' => $this->input->post('HP'),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
            'NEGARA' => $negara,
            'ID_PENDIDIKAN' => NULL,
            'STORAGE_MINIO' => storageDiskMinio(),
            'FOTO' => @$filefoto['name'],
            'LOKASI_NEGARA' => $ALAMAT_NEGARA
        );

        $data_user = array(
            'EMAIL' => $this->input->post('EMAIL_PRIBADI'),
            'HANDPHONE' => $this->input->post('HP'),
            'NAMA' => $this->input->post('NAMA_LENGKAP'),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
            'NIP' => $this->input->post('NIP')
        );

        // $id_user = $this->session->userdata('ID_USER');
        // $email_pribadi = $this->input->post('EMAIL_PRIBADI');
        // $this->db->where('ID_USER !=', $id_user);
        // $this->db->where('EMAIL', $email_pribadi);
        // $exist_email = $this->db->get('t_user')->row();
        // if ($exist_email) {
        //     echo '2';
        // } else {
            $this->mglobal->update_data_('t_pn', $data_pn, 'NIK', $this->input->post('NIK'));
            $this->mglobal->update_data_('t_user', $data_user, 'USERNAME', $this->input->post('NIK'));


            $this->db->where('ID', $this->input->post('ID'));
            $result = $this->db->update($this->TABLE, $data);
            ng::logActivity("Ubah Data Pribadi, ID = ".$this->input->post('ID').", ID_LHKPN = ".$this->input->post('ID_LHKPN'));

        // }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo '0';
        } else {
            $this->db->trans_commit();
            echo '1';
        }
        redirect('portal/filing/entry/' . $this->input->post('ID_LHKPN'));
    }

    function upload() {
        if ($_FILES['file']['name']) {

            $ext = end((explode(".", $_FILES['file']['name'])));
            $file_name = md5('AAAA') . '.' . $ext;
            $uploaddir = 'images/' . $file_name;
            $uploadext = strtolower(strrchr($_FILES["file"],"."));
            if($uploadext=='.jpg' || $uploadext=='.png' || $uploadext=='.jpeg') {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir)) {
                    echo "1";
                }
            } else {
                header('Location: '.base_url());
             }
        }
    }

    function index() {
        $data = array(
            'nikah' => $this->get_select('m_status_nikah', 'STATUS_NIKAH'),
            'NIK' => $this->session->userdata('NIK'),
            'agama' => $this->get_select('m_agama', 'AGAMA'),

        );
        $options = $data;
        $options['js_page'][] = $this->load->view('portal/filing/js/data_pribadi/index_js', $data, TRUE);
        $options['js_page'][] = $this->load->view('portal/filing/js/data_pribadi/submit_data_pribadi_js', $data, TRUE);
        $options['js_page'][] = $this->load->view('portal/filing/js/data_pribadi/select2_index_js', $data, TRUE);
        $options['js_page'][] = $this->load->view('portal/filing/js/data_pribadi/get_detail_index_js', $data, TRUE);
        $options['js_page'][] = $this->load->view('portal/filing/js/data_pribadi/main_js', $data, TRUE);
        $this->load->view('portal/filing/data_pribadi', $options);
    }


}
