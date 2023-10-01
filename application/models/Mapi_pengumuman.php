<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Magama
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mapi_pengumuman extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function list_all($offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $result = FALSE;
        $total_rows = 0;
        $cari_advance = $this->input->get('CARI');
        $sql_where = " 1=1 AND ";

        if($cari_advance["STATUS"]==9){
            $cari_advance["STATUS"]=null;
        }
        if ($cari_advance) {
            $condition_instansi = array_key_exists("INSTANSI", $cari_advance) && $cari_advance["INSTANSI"] != '' ? "tlr.INST_SATKERKD ='" . $cari_advance["INSTANSI"] . "'" : " 1";
            $condition_status = array_key_exists("STATUS", $cari_advance) && $cari_advance["STATUS"] != '' ? "tlr.IS_ACTIVE = '". $cari_advance["STATUS"] . "'" : " 1";
            $sql_where .= "  " . $condition_status . " and ". $condition_instansi . " ";
        }

        /////cek total baris//////
        $this->db->select("count(id) as cnt");
        $this->db->where($sql_where);
        $this->db->from('api_pengumuman tlr');
        $queryCount = $this->db->get();
        if ($queryCount) {
            $result = $queryCount->row();
            if ($result) {
                $total_rows = $result->cnt;
            }
        }
        /////ambil data///////
        $this->db->select("tlr.*,mis.INST_NAMA");
        $this->db->where($sql_where);
        $this->db->from('api_pengumuman tlr');
        $this->db->join('M_INST_SATKER as mis', 'mis.INST_SATKERKD = tlr.INST_SATKERKD', 'LEFT');
        $this->db->order_by('tlr.ID', 'desc');
        if ($limit_mode) {
            $query = $this->db->get(null,$rowperpage, $offset);
        } else {
            $query = $this->db->get();
        }

        if ($query) {
            $result = $query->result();
        }

        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $result[$key]->DIAKSES = $item->DIAKSES.' kali';
                $result[$key]->API_SHORT = substr($item->API,0,60).'.......';
                $result[$key]->CREATED_TIME_INDONESIA = tgl_format($item->CREATED_TIME);
                $result[$key]->PENERIMA = $item->EMAIL_PENERIMA ? $item->EMAIL_PENERIMA.'<br>'.indonesian_date($item->EMAIL_DIKIRIM) : '---';
                $result[$key]->LINK_WEB = $item->LINK_WEB;
                $source_logo = $item->LOGO ? $item->LOGO : 'portal-assets/img/no_image_available.jpg';

                $result[$key]->IMG_LOGO = '<img src="'.base_url().$source_logo.'" width="50%" />';
                $i++;
            }
        }
        return (object) array("total_rows" => $total_rows, "result" => $result);
        exit;
    }

    public function save_data($action){
        $instansi = $this->input->post('INST_SATKERKD', TRUE);
        
        switch($action){
            case "doinsert":
                $check_api = $this->db->where("INST_SATKERKD",$this->input->post('INST_SATKERKD', TRUE))->count_all_results("api_pengumuman");
                if($check_api){
                    return 9;
                }
                $id =  $instansi;
                $time = date('Y-m-d-H:i:s');
                $combine = $time.$id.$time;
                $encr = base64_encode(encrypt_username($combine));

                $instansi_state = $this->Mglobal->get_data_by_id('M_INST_SATKER','INST_SATKERKD',$instansi,false,true);
                $name_state = strtolower(str_slug($instansi_state->INST_NAMA));

                $api = base_url().'portal/user/pengumuman_lhkpn/'.$encr;

                $location = null;

                if ($_FILES['logo']['name']) {
                    $currentFolder = 'uploads/api/logo/';
                    $ext = end((explode(".", $_FILES['logo']['name'])));

                    $file_name =  $name_state.'-'.time().'.'. $ext;
                    $uploaddir = $currentFolder.$file_name;
                    $result = move_uploaded_file($_FILES['logo']['tmp_name'], $uploaddir);
                    $location = 'uploads/api/logo/'.$file_name;
                }

                $state = array(
                    'INST_SATKERKD' => $instansi,
                    'KEY' => $encr,
                    'API' => $api,
                    'DIAKSES' => 0,
                    'LINK_WEB' => $this->input->post('LINK_WEB', TRUE),
                    'LOGO' => $location,
                    'CREATED_TIME' => date("Y-m-d H:i:s"),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $result = $this->db->insert('api_pengumuman', $state);
                $last_id = $this->db->insert_id();
                // ng::logActivity("Tambah regulasi untuk, nomor_regulasi = ".$this->input->post('nomor_regulasi', TRUE).", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$last_id);
                break;
            case "doapprove":
                $ID = $this->input->post('ID', TRUE);
                $get_regulasi = $this->Mglobal->get_data_by_id('api_pengumuman','ID',$ID,false,true);
                if($get_regulasi->IS_ACTIVE==0){
                    $status = 1;
                }else{
                    $status = 0;
                }
               $state = array(
                    'IS_ACTIVE' => $status,
                );

                $this->db->where('ID = '.$ID);
                $result = $this->db->update('api_pengumuman',$state);
                // ng::logActivity("Verifikasi regulasi untuk, nomor_regulasi = ".$get_regulasi->NOMOR_REGULASI.", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$ID);
                break;
            case "doemail":
                $ID = $this->input->post('ID', TRUE);
                $email = $this->input->post('email', TRUE);
                $state = array(
                        'EMAIL_PENERIMA' => $email,
                        'EMAIL_DIKIRIM' => date("Y-m-d H:i:s"),
                    );

                $this->db->where('ID = '.$ID);
                $result = $this->db->update('api_pengumuman',$state);
                // ng::logActivity("Verifikasi regulasi untuk, nomor_regulasi = ".$get_regulasi->NOMOR_REGULASI.", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$ID);
                break;
            case "dologo":
                $ID = $this->input->post('ID', TRUE);
                $get_regulasi = $this->Mglobal->get_data_by_id('api_pengumuman','ID',$ID,false,true);
                $instansi = $this->Mglobal->get_data_by_id('M_INST_SATKER','INST_SATKERKD',$get_regulasi->INST_SATKERKD,false,true);
                $name = strtolower(str_slug($instansi->INST_NAMA));

                $location = $get_regulasi->LOGO;

                if($this->input->post('hapus_logo',TRUE)){
                    $hasil = unlink($location);
                    $location = null;
                }else{
                    if ($_FILES['logo']['name']) {
                        $hasil = unlink($location);
                        $currentFolder = 'uploads/api/logo/';
                        $ext = end((explode(".", $_FILES['logo']['name'])));

                        $file_name =  $name.'-'.time().'.'. $ext;
                        $uploaddir = $currentFolder.$file_name;
                        $result = move_uploaded_file($_FILES['logo']['tmp_name'], $uploaddir);
                        $location = 'uploads/api/logo/'.$file_name;
                    }
                }


                $state = array(
                    'LOGO' => $location,
                    'LINK_WEB' => $this->input->post('LINK_WEB', TRUE),
                );

                $this->db->where('ID = '.$ID);
                $result = $this->db->update('api_pengumuman',$state);
                // ng::logActivity("Verifikasi regulasi untuk, nomor_regulasi = ".$get_regulasi->NOMOR_REGULASI.", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$ID);
                break;
        }
        return $result;
    }


}

?>