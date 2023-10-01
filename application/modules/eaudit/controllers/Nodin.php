<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @author Ahmad Saughi
* @version 04122018
*/

class Nodin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('Nodin_model','nodin');
        $this->load->model('Mglobal', '', TRUE);
    }

    public function index() {
        $data['title'] = 'Nota Dinas';
        $data['id_user'] = $this->session->userdata('ID_USER');
        $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_index', $data);
    }

    public function ajax_list() {
        $nodin = $this->nodin->get_datatables();

        $data = array();
        $no = $_POST['start'];
        foreach ($nodin as $list) {
            $array_nama = explode(",", $list->NAMA);
            $array_noagenda = explode(",", $list->NOMOR_AGENDA);
            $nama_noagenda = '';
            $i = 0;
//            foreach($array_nama as $key=>&$val){
//                $val2 = $array_noagenda[$key];
//                if ($i == 0) {
//                    $nama_noagenda .= '<b>' . $val . '</b> (' . $val2 . ')';
//                } else {
//                    $nama_noagenda .= '<br><b>' . $val . '</b> (' . $val2 . ')';
//                }
//                $i++;
//            }
            $nama_noagenda = '<br><b>' . $list->NAMA . '</b> (' . $list->NOMOR_AGENDA . ')';

            $btn_download = ($list->FILE == NULL || $list->FILE == '') ? '' : '<button class="btn btn-primary btn-sm btn-download" title="Download" id="btnDownloadNodin" data-idlist="'.$list->ID.'" data-toggle="modal"><i class="fa fa-download"></i></button>';
            $btn_edit = '<button class="btn btn-success btn-sm btn-edit" title="Edit" id="btnUpdateNodin" data-nomorlist="'.$list->NOMOR_NOTA_DINAS.'" data-idlist="'.$list->ID.'" href=""><i class="fa fa-pencil"></i></button>';
            $btn_delete = '<button class="btn btn-danger btn-sm btn-delete" title="Delete" id="btnDeleteNodin" data-nomorlist="'.$list->NOMOR_NOTA_DINAS.'" data-idlist="'.$list->ID.'" href=""><i class="fa fa-trash"></i></button>';
            //btn stakeholder
            if ($list->IS_STAKEHOLDER == 0) {
                $btn_stakeholder = '<button class="btn btn-success btn-sm btn-stakeholder" title="Stakehholder" id="btnStakeholder" data-nomorlist="'.$list->NOMOR_NOTA_DINAS.'" data-idlist="'.$list->ID.'" data-isstakeholder="'.$list->IS_STAKEHOLDER.'" href=""><i class="fa fa-check"></i></button>';
            } else {
                $btn_stakeholder = '<button class="btn btn-warning btn-sm btn-stakeholder" title="Non Stakeholder" id="btnStakeholder" data-nomorlist="'.$list->NOMOR_NOTA_DINAS.'" data-idlist="'.$list->ID.'" data-isstakeholder="'.$list->IS_STAKEHOLDER.'" href=""><i class="fa fa-close"></i></button>';
            }
            

            $no++;
            $row = array();
            
            $row[] = $no;
            $row[] = $list->TUJUAN_NOTA_DINAS;
            if ($list->JENIS_NOTA_DINAS == '1') {
                $row[] = 'Data Keuangan';
            } elseif ($list->JENIS_NOTA_DINAS == '2') {
                $row[] = 'LHP (Rekomendasi)';
            } elseif ($list->JENIS_NOTA_DINAS == '3') {
                $row[] = 'LHP';
            } elseif ($list->JENIS_NOTA_DINAS == '4') {
                $row[] = 'Data Lainnya';
            }
            $row[] = ($list->NAMA_PIC == NULL) ? '-' : $list->NAMA_PIC;
            $row[] = $nama_noagenda;
            $row[] = ($list->NOMOR_NOTA_DINAS == NULL) ? '-' : $list->NOMOR_NOTA_DINAS . ($list->IS_STAKEHOLDER ? '<br> (Stakeholder)' : '');
            $row[] = ($list->TANGGAL_NOTA_DINAS == NULL) ? '-' : date('d/m/Y', strtotime($list->TANGGAL_NOTA_DINAS));
            // $row[] = '<b>' . $list->NAMA . '</b><br><small>' . $list->NOMOR_AGENDA . '</small>';
            $row[] = $btn_edit .' '. $btn_download . ' ' . ($list->JENIS_NOTA_DINAS == 2 ? $btn_stakeholder : '') .' '. $btn_delete;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->nodin->count_all(),
            "recordsFiltered" => $this->nodin->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    // MULAI CREATED
    public function updateNodin($mode, $id = NULL, $id_lhkpn = NULL) { 
        $data['ID'] = $id;
	$data['ID_LHKPN'] = $id_lhkpn;
		
        if ($mode == 'new') {
            $data['onAdd'] = false;
            $data['action'] = 'do_updateNodin/new';
        } else {
            $data['onAdd'] = true;
            $data['action'] = 'do_updateNodin/edit/'.$id;
            
            $join_nd = [
                ['table' => 't_eaudit_nodin_lhkpn', 'on' => 't_eaudit_nodin_lhkpn.ID_NODIN = t_eaudit_nodin.ID'],
                ['table' => 'm_unit_kerja_eaudit', 'on' => 'm_unit_kerja_eaudit.ID_UK_EAUDIT = t_eaudit_nodin.TUJUAN_NOTA_DINAS'],
            ];
            $select_nd = 'DISTINCT
                            JENIS_NOTA_DINAS,
                            TUJUAN_NOTA_DINAS,
                            NOMOR_NOTA_DINAS,
                            TANGGAL_NOTA_DINAS,
                            KETERANGAN,
                            FILE,
                            NAMA_UK_EAUDIT,
                            GROUP_CONCAT(DISTINCT t_eaudit_nodin_lhkpn.ID_LHKPN) AS ID_LHKPN,
                            GROUP_CONCAT(DISTINCT ID_AUDIT) AS ID_AUDIT,
                            GROUP_CONCAT(DISTINCT t_eaudit_nodin_lhkpn.NOMOR_AGENDA) AS NOMOR_AGENDA,
                            GROUP_CONCAT(DISTINCT t_eaudit_nodin_lhkpn.NAMA) AS NAMA';
            $result = $this->Mglobal->get_data_all('t_eaudit_nodin', $join_nd, ['ID' => $id], $select_nd)[0];
            
            $data['ID_LHKPN'] =  $result->ID_LHKPN;
            $data['ID_AUDIT'] =  $result->ID_AUDIT;
            $data['NAMA'] =  $result->NAMA;
			$data['NO_AGENDA'] = $result->NOMOR_AGENDA;
			$data['JENIS_ND'] = $result->JENIS_NOTA_DINAS;
			$data['TUJUAN_ND'] = $result->TUJUAN_NOTA_DINAS;
			$data['NOMOR_ND'] = $result->NOMOR_NOTA_DINAS;
			$data['TANGGAL_ND'] = $result->TANGGAL_NOTA_DINAS;
            $data['KETERANGAN'] = $result->KETERANGAN;

            $dataJSON = [];
            $array_lhkpn = explode(',', $result->ID_LHKPN);
            $array_nama = explode(',', $result->NAMA);
            $array_agenda = explode(',', $result->NOMOR_AGENDA);
            $join = [
                ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
                ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN AND T_LHKPN_JABATAN.IS_PRIMARY = "1"'],
                ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD'],
            ];
            $select_id_audit = ',(SELECT MAX(T_LHKPN_AUDIT.`id_audit`) FROM t_lhkpn_audit WHERE t_lhkpn_audit.`id_lhkpn` = T_LHKPN.`ID_LHKPN` GROUP BY T_LHKPN_AUDIT.`id_lhkpn`) AS id_audit';
            $select_no_st = ',coalesce((SELECT GROUP_CONCAT(DISTINCT nomor_surat_tugas) FROM t_lhkpn_audit WHERE t_lhkpn_audit.`id_lhkpn` = T_LHKPN.`ID_LHKPN` GROUP BY T_LHKPN_AUDIT.`id_lhkpn`),"--") AS no_st';
            foreach($array_lhkpn as $key=>&$val){

                $edit = $this->Mglobal->get_data_all('T_LHKPN', $join, ['T_LHKPN.ID_LHKPN' => $val], 'T_PN.NIK, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA'.$select_id_audit.$select_no_st);
                $val2 = $array_nama[$key];
                $val3 = $array_agenda[$key];
                $no++;
                $row = array(
                    "nomor" => $no,
                    "id_lhkpn" => $val,
                    "nama_pn" => $val2,
                    "nik_pn" => $edit[0]->NIK,
                    "agenda_pn" => $val3,
                    "jabatan_pn" => ($edit[0]->NAMA_JABATAN == NULL) ? '-' : $edit[0]->NAMA_JABATAN,
                    "lembaga_pn" => ($edit[0]->INST_NAMA == NULL) ? '-' : $edit[0]->INST_NAMA,
                    "no_st" => $edit[0]->no_st,
                    "id_audit" => $edit[0]->id_audit,
                );
                $dataJSON[] = $row; 
            }
            $output = array(
                "data" => $dataJSON,
            );
            $data['JSON_LIST'] = json_encode($output);
        }
        $this->load->view(strtolower(__CLASS__) . '/' . 'nodin_add_form', $data); 
    }

    public function do_updateNodin($mode, $id = NULL) {
        $lhkpn = $this->input->post('listPilihId');
        $audit = $this->input->post('listPilihIdAudit');
        $NAMAPN = $this->input->post('iNamaPN');
        $JENIS_ND = $this->input->post('iJenis');
        $TUJUAN_ND = $this->input->post('iTujuan');
        $NOMOR_ND_POST = $this->input->post('iNomorND');
        $NOMOR_ND_POST_BARU = $NOMOR_ND_POST;     
        $NOMOR_ND = strtoupper(trim($NOMOR_ND_POST));
        $NOMOR_ND_BARU = strtoupper(trim($NOMOR_ND_POST_BARU));
        $TANGGAL = $this->input->post('iTanggalND');
        $TANGGAL_ND = ($TANGGAL == '') ? NULL : date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL)));
        $KETERANGAN = $this->input->post('iKeterangan');
        $lhkpn_pn = '';
        $nama_pn = '';
        $no_agenda = '';
        $upload = $this->upload_file_nodin($NOMOR_ND);

        $lhkpn = explode(',', $lhkpn);
        $lhkpnarr = array($lhkpn);
        $getlhkpn = $lhkpnarr[0];
        
        $audit = explode(',', $audit);
        $auditarr = array($audit);
        $getaudit = $auditarr[0];
        
        if ($mode == 'edit'){
            if ($NOMOR_ND_BARU != '' || $NOMOR_ND_BARU != null){
                $clause = [
                    'NOMOR_NOTA_DINAS' => $NOMOR_ND_BARU,
                    'NOMOR_NOTA_DINAS !=' => $NOMOR_ND_POST
                ];
                $cekND = $this->nodin->cek_nd_by_clause($clause);
            }
        }elseif ($mode == 'new'){
            if ($NOMOR_ND != '' || $NOMOR_ND != null){
                $cekND = $this->nodin->cek_nd(base64_encode($NOMOR_ND));
            }
        }

        if ($cekND == TRUE){
            echo '2';
            return;
        }
        
            $i = 0;
            foreach ($lhkpn as $idlhkpn) {
                $data = $this->Mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN  = T_PN.ID_PN']], ['ID_LHKPN' => $lhkpn[$i]], 'ID_LHKPN,JENIS_LAPORAN,NIK,NAMA,YEAR(TGL_LAPOR) AS TAHUN_LAPOR');
                $agenda = ($data[0]->TAHUN_LAPOR == NULL ? '-' : $data[0]->TAHUN_LAPOR) . '/' . ($data[0]->JENIS_LAPORAN == '4' ? 'R' : ($data[0]->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $data[0]->NIK . '/' . $data[0]->ID_LHKPN;
                if ($i == 0) {
                    $lhkpn_pn .= $idlhkpn;
                    $nama_pn .= $data[0]->NAMA;
                    $no_agenda .= $agenda;
                } else {
                    $lhkpn_pn .= ','.$idlhkpn;
                    $nama_pn .= ','.$data[0]->NAMA;
                    $no_agenda .= ','.$agenda;
                }
                $i++;
            }
            if ($NOMOR_ND_BARU == "" || $NOMOR_ND_BARU == NULL){
                $NOMOR_ND = $NOMOR_ND;
            }else{
                $NOMOR_ND = $NOMOR_ND_BARU;
            }
            if ($upload['upload']) {
                $FILE_NODIN = $upload['url'];
                $posted_fields = array(
//                    "ID_LHKPN" => $getlhkpn[$a],
//                    "NAMA" => $nama_pn,
//                    "NOMOR_AGENDA" => $no_agenda,
                    "JENIS_NOTA_DINAS" => $JENIS_ND,
                    "TUJUAN_NOTA_DINAS" => $TUJUAN_ND,
                    "NOMOR_NOTA_DINAS" => $NOMOR_ND,
                    "TANGGAL_NOTA_DINAS" => $TANGGAL_ND,
                    "KETERANGAN" => $KETERANGAN,
                    "FILE" => $FILE_NODIN,
//                    "ID_AUDIT" => $audit[$a] == null || $audit[$a] == 'null' ? NULL : $audit[$a],
                );
            } else {
                $posted_fields = array(
//                    "ID_LHKPN" => $getlhkpn[$a],
//                    "NAMA" => $nama_pn,
//                    "NOMOR_AGENDA" => $no_agenda,
                    "JENIS_NOTA_DINAS" => $JENIS_ND,
                    "TUJUAN_NOTA_DINAS" => $TUJUAN_ND,
                    "NOMOR_NOTA_DINAS" => $NOMOR_ND,
                    "TANGGAL_NOTA_DINAS" => $TANGGAL_ND,
                    "KETERANGAN" => $KETERANGAN,
//                    "ID_AUDIT" => $audit[$a] == null || $audit[$a] == 'null' ? NULL : $audit[$a],
                );
            }

            if ($mode == 'new') {
                $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
                $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
                $posted_fields['CREATED_IP'] = $this->get_client_ip();

                $update = $this->nodin->insert_data($posted_fields, 't_eaudit_nodin');
                $last_id = $this->db->insert_id();
                
                if ($update){
                    for ($a=0; $a < sizeof($getlhkpn) ; $a++) {
                        $data = $this->Mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN  = T_PN.ID_PN']], ['ID_LHKPN' => $getlhkpn[$a]], 'ID_LHKPN,JENIS_LAPORAN,NIK,NAMA,YEAR(TGL_LAPOR) AS TAHUN_LAPOR');
                        $agenda = ($data[0]->TAHUN_LAPOR == NULL ? '-' : $data[0]->TAHUN_LAPOR) . '/' . ($data[0]->JENIS_LAPORAN == '4' ? 'R' : ($data[0]->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $data[0]->NIK . '/' . $data[0]->ID_LHKPN;
                        
                        $nama_pn = $data[0]->NAMA;
                        $no_agenda = $agenda;
                            
                        $posted_fields_lhkpn = array(
                            "ID_LHKPN" => $getlhkpn[$a],
                            "ID_NODIN" => $last_id,
                            "NAMA" => $nama_pn,
                            "NOMOR_AGENDA" => $no_agenda,
                            "ID_AUDIT" => $audit[$a] == null || $audit[$a] == 'null' ? NULL : $audit[$a],
                        );
                        $update = $this->nodin->insert_data($posted_fields_lhkpn, 't_eaudit_nodin_lhkpn');
                    }
                }

            } elseif ($mode == 'edit') {
                $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
                $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
                $posted_fields['UPDATED_IP'] = $this->get_client_ip();

                $update = $this->nodin->update_data($posted_fields, $id, 't_eaudit_nodin');
                
                if($update){
                    $delete = $this->nodin->delete_data_nodin($id, 't_eaudit_nodin_lhkpn');
                }
                
                if($delete){
                    for ($a=0; $a < sizeof($getlhkpn) ; $a++) {
                        $data = $this->Mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN  = T_PN.ID_PN']], ['ID_LHKPN' => $getlhkpn[$a]], 'ID_LHKPN,JENIS_LAPORAN,NIK,NAMA,YEAR(TGL_LAPOR) AS TAHUN_LAPOR');
                        $agenda = ($data[0]->TAHUN_LAPOR == NULL ? '-' : $data[0]->TAHUN_LAPOR) . '/' . ($data[0]->JENIS_LAPORAN == '4' ? 'R' : ($data[0]->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $data[0]->NIK . '/' . $data[0]->ID_LHKPN;
                        
                        $nama_pn = $data[0]->NAMA;
                        $no_agenda = $agenda;
                            
                        $posted_fields_lhkpn = array(
                            "ID_LHKPN" => $getlhkpn[$a],
                            "ID_NODIN" => $id,
                            "NAMA" => $nama_pn,
                            "NOMOR_AGENDA" => $no_agenda,
                            "ID_AUDIT" => $audit[$a] == null || $audit[$a] == 'null' ? NULL : $audit[$a],
                        );
                        $update = $this->nodin->insert_data($posted_fields_lhkpn, 't_eaudit_nodin_lhkpn');
                    }
                }

            } elseif ($mode == 'nonActive') {
                $update = $this->nodin->update_data(["IS_ACTIVE" => '-1'], $id, 't_eaudit_nodin');
                if($update){
                    $delete = $this->nodin->delete_data_nodin($id, 't_eaudit_nodin_lhkpn');
                }
            }

        if ($update) {
            echo '1';
            return;
        } else {
            echo '0';
            return;
        }
    }

    function upload_file_nodin($NIK) {
        $result = array();
        // $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
        $folder = $this->encrypt($NIK, 'e');
        
        ///////////////////security image///////////////////
        $post_nama_file = 'file1'; 
        $extension_diijinkan = array("pdf", "jpg", "png", "jpeg", "tif", "tiff");
        $extension_current_diijinkan = array("application","image");
        $redirect_jika_gagal = null;
        $tipe_function = "json";
        $check_protect = protectionMultipleDocument($post_nama_file,$extension_diijinkan,$redirect_jika_gagal,$tipe_function,$extension_current_diijinkan);
        if($check_protect){
            echo 'INGAT DOSA WAHAI PARA HACKER';
            exit;
        }
        ///////////////////security image///////////////////

        if (!file_exists('uploads/nodin/' . $folder)) {
            mkdir('uploads/nodin/' . $folder);
            $content = "Bukti Nota Dinas Dari " . $folder . " dengan user " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/nodin/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
            /* IBO UPDATE */
        }
        
        $rst = false;
        $urllist = 'uploads/nodin/' . $folder . '/';
        // dump($_FILES['file1']['tmp_name']);exit;
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            // $file_name = $_FILES['file1']['name'][$key];
            // dump($file_name . '|' . $_FILES['file1']['name'][$key]);exit;
            $uploaddir = 'uploads/nodin/' . $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);
            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {

                $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist);
        return $result;
    }

    function getFile($id) {
        $files = $this->Mglobal->get_data_all('T_EAUDIT_NODIN', NULL, ['ID' => $id], 'ID,FILE');
        $explode = explode('/',$files[0]->FILE);
        $dir = $explode[0].'/'.$explode[1].'/'.$explode[2].'/';
        $file_name = array_filter(explode(',', $explode[3]), function($value) { return $value !== ''; });
        $file_array = [];
        foreach ($file_name as $value) {
            $no++;
            $row = array(
                "nomor" => $no,
                "nama_file" => $value,
                "link_file" => $dir.$value,
            );
            $file_array[] = $row;
        }
        $output = array(
            "data" => $file_array,
        );
        echo json_encode($output);
    }

    function encrypt($string, $action = 'e') {
        $secret_key = 'R@|-|a5iaKPK|-|@rTa';
        $secret_iv = 'R@|-|a5ia|/|394124|-|@rTa';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
        exit; 
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

    public function get_pn_by_name() {
        $nama =  $this->input->post('nama');
        
        $data = $this->nodin->get_pn_by_name($nama);
        echo json_encode($data);
    }
    
    function cek_nd($id = NULL) {
        if ($id != NULL) {
            $data = $this->nodin->cek_nd($id);
            // JIKA DATA TIDAK ADA, MAKA BENAR
            if ($data == false) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            // JIKA DATA TIDAK ADA, MAKA BENAR
            echo 1;
        }
        exit;
    }
    
    public function cekND($ND) {
        $this->load->model('mglobal', '', TRUE);
//        $ND_up = strtoupper($ND);
        $ND_up = base64_decode($ND);
        $record = $this->mglobal->get_data_all('t_eaudit_nodin', null, ['NOMOR_NOTA_DINAS' => trim($ND_up)], '*');
        if (count($record)) {
//            $nodin = $this->nodin->get_datatables();
            $this->db->select('DISTINCT
                            ID,
                            JENIS_NOTA_DINAS,
                            TUJUAN_NOTA_DINAS,
                            NOMOR_NOTA_DINAS,
                            DATE_FORMAT(TANGGAL_NOTA_DINAS, "%d/%m/%Y") AS TANGGAL_NOTA_DINAS,
                            KETERANGAN,
                            FILE,
                            NAMA_UK_EAUDIT,
                            GROUP_CONCAT(DISTINCT t_eaudit_nodin_lhkpn.ID_LHKPN) AS ID_LHKPN,
                            GROUP_CONCAT(DISTINCT ID_AUDIT) AS ID_AUDIT,
                            GROUP_CONCAT(DISTINCT t_eaudit_nodin_lhkpn.NOMOR_AGENDA) AS NOMOR_AGENDA,
                            GROUP_CONCAT(DISTINCT t_eaudit_nodin_lhkpn.NAMA) AS NAMA', FALSE);
            $this->db->from('t_eaudit_nodin');
            $this->db->join('t_eaudit_nodin_lhkpn', 't_eaudit_nodin_lhkpn.ID_NODIN = t_eaudit_nodin.ID', 'left');
            $this->db->join('m_unit_kerja_eaudit', 'm_unit_kerja_eaudit.ID_UK_EAUDIT = t_eaudit_nodin.TUJUAN_NOTA_DINAS', 'left');
            $this->db->where('t_eaudit_nodin.IS_ACTIVE', 1);
            $this->db->where('t_eaudit_nodin.NOMOR_NOTA_DINAS', $ND_up);
            $this->db->order_by('TANGGAL_NOTA_DINAS', 'DESC');
            $query = $this->db->get();
            $item = $query->result();

            echo json_encode($item);
            /**/
        } else {
            echo 0;
        }
        exit;
    }

    public function update_stakeholder($id, $is_stakeholder)
    {
        //do check
        $clause = [
            'id' => $id,
            'is_stakeholder' => $is_stakeholder
        ];
        $cek_nodin = $this->nodin->cek_nd_by_clause($clause);
        if ($cek_nodin) {
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $posted_fields['IS_STAKEHOLDER'] = $is_stakeholder == 1 ? 0 : 1;

            $update = $this->nodin->update_data($posted_fields, $id, 't_eaudit_nodin');
            if ($update) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

}
