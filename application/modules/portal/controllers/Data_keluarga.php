<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_keluarga extends CI_Controller {

    function __Construct() {
        parent::__Construct();
        $this->TABLE = 't_lhkpn_keluarga';
        $this->load->model('mglobal');
        call_user_func('ng::islogin');
    }

    function index() {
        $ID_PN = $this->session->userdata('ID_PN');
        $DT_PN = $this->mglobal->get_data_all('t_pn', NULL, ['ID_PN' => $ID_PN])[0];
        if ($DT_PN->NEGARA == 1 && substr($DT_PN->KAB_KOT,0,5) == 'KOTA ')
            // $alamat_tgl = $DT_PN->ALAMAT_TINGGAL . ', Kelurahan ' . $DT_PN->KEL . ', Kecamatan ' . $DT_PN->KEC . ', Kabupaten/Kota ' . substr($DT_PN->KAB_KOT,5,20) . ', Provinsi ' . $DT_PN->PROV;
            $alamat_tgl = $DT_PN->ALAMAT_TINGGAL . ', Kelurahan ' . $DT_PN->KEL . ', Kecamatan ' . $DT_PN->KEC . ', ' . $DT_PN->KAB_KOT . ', Provinsi ' . $DT_PN->PROV;
        else if ($DT_PN->NEGARA == 1)
            $alamat_tgl = $DT_PN->ALAMAT_TINGGAL . ', Kelurahan ' . $DT_PN->KEL . ', Kecamatan ' . $DT_PN->KEC . ', Kabupaten/Kota ' . $DT_PN->KAB_KOT . ', Provinsi ' . $DT_PN->PROV;
        else 
            $alamat_tgl = $DT_PN->ALAMAT_TINGGAL;

        $alamat_tgl = preg_replace("/[\r\n]+/", "\n", $alamat_tgl);
        //$alamat_tgl = wordwrap($alamat_tgl, 120, '<br/>', true);
        $alamat_tgl = nl2br($alamat_tgl);
        $alamat_tgl = trim(preg_replace('/\s\s+/', ' ', $alamat_tgl));
        $alamat_tgl = preg_replace('/\s+/', ' ', str_replace(array("\r\n", "\r", "\n"), ' ', trim($alamat_tgl)));
        $alamat_tgl = preg_replace('#\s+#', ' ', trim($alamat_tgl));
        $alamat_tgl = strip_tags($alamat_tgl);

        $options = array(
//            'alamat_rumah' => $DT_PN->ALAMAT_TINGGAL . ', ' . $DT_PN->KEL . ', ' . $DT_PN->KEC . ', ' . $DT_PN->KAB_KOT . ', ' . $DT_PN->PROV,
            'alamat_rumah' => $alamat_tgl,
            'ID_PN' => $ID_PN
        );
        $this->load->view('portal/filing/data_keluarga', $options);
    }

    function hubungan($id) {
        $data = array();
        $data[1] = 'ISTRI';
        $data[2] = 'SUAMI';
        $data[3] = 'ANAK TANGGUNGAN';
        $data[4] = 'ANAK BUKAN TANGGUNGAN';
        $data[5] = 'LAINNYA';
        return $data[$id];
    }

    function get_sk_kel($id_kel){
        
        $sql = "SELECT ID_KELUARGA, ID_KELUARGA_LAMA, ID_LHKPN, FLAG_SK
				FROM `t_lhkpn_keluarga`
                WHERE `t_lhkpn_keluarga`.`ID_KELUARGA` = '" . $id_kel . "'";
        $query = $this->db->query($sql);
        $response = FALSE;
        if ($query) {
            $response = $query->result();
        }
        $sk = $response[0]->ID_KELUARGA; 
        return $sk;
    }

    function TableKeluarga($ID_LHKPN, $ID_PN = NULL) {
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->select('*,
                    -- CEIL(DATEDIFF(NOW(),t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1 AS UMUR,
                    -- CEIL(DATEDIFF(t_lhkpn.TGL_KIRIM_FINAL,t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1 AS UMUR_LAPOR
                    TIMESTAMPDIFF(YEAR,t_lhkpn_keluarga.TANGGAL_LAHIR,NOW()) AS UMUR,
                    TIMESTAMPDIFF(YEAR,t_lhkpn_keluarga.TANGGAL_LAHIR,t_lhkpn.tgl_lapor) AS UMUR_LAPOR
                        ', FALSE);
//        $this->db->where('t_lhkpn' . '.ID_PN', $ID_PN);
        $this->db->where($this->TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where('t_lhkpn_keluarga' . '.IS_ACTIVE', '1');
//        $this->db->where($this->TABLE . '.ID_KELUARGA is not null');
        if ($cari) {

            $sql_like = " ( NAMA LIKE '%" . $cari . "%' OR "
                    . " (CASE hubungan 
		WHEN 1 THEN 'ISTRI'
		WHEN 2 THEN 'SUAMI'
		WHEN 3 THEN 'ANAK TANGGUNGAN'
		WHEN 4 THEN 'ANAK BUKAN TANGGUNGAN'
		ELSE 'LAINNYA' END) LIKE '%" . $cari . "%' OR "
                    . " TEMPAT_LAHIR LIKE '%" . $cari . "%' OR "
                    . " PEKERJAAN LIKE '%" . $cari . "%' ) ";

            $this->db->where($sql_like);
//            $this->db->like('NAMA', $cari);
//            $this->db->or_like('HUBUNGAN', $cari);
//            $this->db->or_like('TEMPAT_LAHIR', $cari);
//            $this->db->or_like('PEKERJAAN', $cari);
        }
//        $this->db->join('t_lhkpn_keluarga', 't_lhkpn.ID_LHKPN = ' . $this->TABLE . '.ID_LHKPN');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $this->TABLE . '.ID_LHKPN');
        if ($ID_PN != '') {
            $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $this->TABLE . ".ID_LHKPN and ID_PN = '" . $ID_PN . "'");
        }
        else{
            $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $this->TABLE . ".ID_LHKPN and ID_PN = '" . $this->session->userdata('ID_PN') . "'");
        }
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->db->order_by('ID_KELUARGA', 'ASC');
        $data = $this->db->get($this->TABLE)->result();
//        $data = $this->db->get('t_lhkpn')->result();
//        display($this->db->last_query());exit;
        if ($data) {

            $this->db->select('*');
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $datapribadi = $this->db->get('t_lhkpn_data_pribadi')->result();

            foreach ($data as $list) {
                //============================================================
                //Rian Ipdate

                if ($datapribadi) {
                    foreach ($datapribadi AS $ROW) {
                        $alamatrumah = $ROW->ALAMAT_RUMAH;
                    }
                }

                //echo $this->db->last_query();exit;
                //End
                //============================================================
                $tgl_lahir = null;
                if ($list->TANGGAL_LAHIR) {
                    $tgl_lahir = tgl_format($list->TANGGAL_LAHIR);
                }

                $i++;
                $edit = "<a id='" . $list->ID_KELUARGA . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
                $delete = "<a id='" . $list->ID_KELUARGA . "'  href='javascript:void(0)' class='btn btn-danger btn-sm delete-action' title='Delete'><i class='fa fa-trash'></i></a>";
                $cetakSK = "<a id='" . $list->ID_KELUARGA  . "' data-id='".$list->ID_LHKPN."'  href='javascript:void(0)' class='btn btn-success btn-sm cetakSK-action' title='Cetak SK'><i class='fa fa-file'></i></a>";
                $sttsSK = '';
                if ($list->STATUS == 0 || $list->STATUS == 2 || $list->STATUS == 7 && $list->entry_via == '0') {
                    $action1 = $edit . '' . $delete;
                }
                // else{
                    $sk_sudah = '<br>Surat Kuasa: Sudah Diterima';
                    $sk_belum = '<br>Surat Kuasa: Belum Diterima';
                    $non_wajib = '<br>Surat Kuasa: Belum Wajib';
                    $tdk_wajib = '<br>Surat Kuasa: Tidak Wajib';
                    if($list->STATUS != 0 && $list->entry_via == '0' && $list->UMUR_LAPOR >= 17 && ($list->HUBUNGAN == '1' || $list->HUBUNGAN == '2' || $list->HUBUNGAN == '3')){
                        $action2 = $cetakSK;
                        $sttsSK = $sk_sudah;
                    }else{
                        $action2 = '';
//                        if(($list->UMUR_LAPOR !== NULL && $list->UMUR_LAPOR < 17) || ($list->UMUR !== NULL && $list->UMUR < 17)){
                        if($list->UMUR_LAPOR !== NULL && $list->UMUR_LAPOR < 17){
                            $sttsSK = $non_wajib;
                        }
                        else{
                            if($list->FLAG_SK == 0){
                                $sttsSK = $sk_belum;
                            }else{
                                $sttsSK = $sk_sudah;
                            }
                            
                        }
                    }

                    if($list->STATUS != 0 && $list->entry_via == '0' && $list->UMUR_LAPOR >= 17 && ($list->HUBUNGAN == '1' || $list->HUBUNGAN == '2' || $list->HUBUNGAN == '3') && $list->FLAG_SK == 0){
                        if($list->ID_KELUARGA_LAMA !== NULL){
                            $sk = $this->get_sk_kel($list->ID_KELUARGA_LAMA);
                            if($sk == 0){
                                $sttsSK = $sk_belum;
                            }else{
                                $sttsSK = $sk_sudah;        
                            }
                        }
                        $sttsSK = $sk_belum;
                    }
//                    elseif(($list->UMUR_LAPOR !== NULL && $list->UMUR_LAPOR < 17) || ($list->UMUR !== NULL && $list->UMUR < 17)){
                    elseif($list->UMUR_LAPOR !== NULL && $list->UMUR_LAPOR < 17){
                        $sttsSK = $non_wajib;
                    }

                    if($list->HUBUNGAN == '4' || $list->HUBUNGAN == '5'){
                        $sttsSK = $tdk_wajib;
                    }
                
                
                $action = $action1 . '' . $action2;
                if ($ID_PN != '' && $list->entry_via == '1') {
                    $action = $edit . '' . $delete;
                }

                if ($list->JENIS_KELAMIN == '2') {
                    $list->JENIS_KELAMIN = 'LAKI-LAKI';
                } else if ($list->JENIS_KELAMIN == '3') {
                    $list->JENIS_KELAMIN = 'PEREMPUAN';
                }

//                if ($list->STATUS != 0) {
                    $umur2 = getHitungUmur($list->TANGGAL_LAHIR, $list->tgl_lapor);
//                } else {
//                    $umur2 = getHitungUmur($list->TANGGAL_LAHIR);
//                }
//                if ($list->STATUS !=0) {
//                    $TTJU = $list->TEMPAT_LAHIR . ' , ' . $tgl_lahir . ' / ' . $list->JENIS_KELAMIN . "<br><strong>Umur Saat lapor LHKPN: " . $umur2 . $sttsSK . "</strong>";
//                } else {
                    $TTJU = $list->TEMPAT_LAHIR . ' , ' . $tgl_lahir . ' / ' . $list->JENIS_KELAMIN . "<br><strong>Umur Saat lapor LHKPN: " . $umur2 . $sttsSK . "</strong>";
//                }
                $aaData[] = array(
                    $i,
                    $list->NAMA,
                    $this->hubungan($list->HUBUNGAN),
                    $TTJU, //$list->TEMPAT_LAHIR . ' , ' . $tgl_lahir . ' / ' . $list->JENIS_KELAMIN,
                    $list->PEKERJAAN,
                    $list->ALAMAT_RUMAH,
                    $action,
                    $alamatrumah
                );
            }
        }
        if ($cari) {
            $query = "SELECT
                        COUNT(*) AS JML
                        FROM `t_lhkpn_keluarga`
                        JOIN `t_lhkpn` ON `t_lhkpn`.`ID_LHKPN` = `t_lhkpn_keluarga`.`ID_LHKPN`
                        WHERE `t_lhkpn_keluarga`.`ID_LHKPN` = '$ID_LHKPN'
                        AND `t_lhkpn_keluarga`.`IS_ACTIVE` = 1
                        AND `t_lhkpn_keluarga`.`ID_KELUARGA` IS NOT NULL AND $sql_like
                        ORDER BY `ID_KELUARGA` ASC";
        } else {
            $query = "SELECT
                        COUNT(*) AS JML
                        FROM `t_lhkpn_keluarga`
                        JOIN `t_lhkpn` ON `t_lhkpn`.`ID_LHKPN` = `t_lhkpn_keluarga`.`ID_LHKPN`
                        WHERE `t_lhkpn_keluarga`.`ID_LHKPN` = '$ID_LHKPN'
                        AND `t_lhkpn_keluarga`.`IS_ACTIVE` = 1
                        AND `t_lhkpn_keluarga`.`ID_KELUARGA` IS NOT NULL
                        ORDER BY `ID_KELUARGA` ASC";
        }

        $query_cnt = $this->db->query($query);
//        display($this->db->last_query());
//        exit;
        $jml_found = 0;
        if ($query_cnt) {
            $result = $query_cnt->row();
            if ($result) {
                $jml_found = $result->JML;
            }
        }

//        $jml = $this->db->count_all_results($this->TABLE);
        $jml = $jml_found;
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData,
            "alamatrumah" => $alamatrumah
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
        exit;
    }

    function Edit($ID) {
        ////////////////SISTEM KEAMANAN////////////////
        $state_id_pn = $this->session->userdata('ID_PN');
        $check_protect = protectFilling($state_id_pn,$this->TABLE,$ID);
        if($check_protect){
            $method = __METHOD__;
            $this->load->model('mglobal');
            $this->mglobal->recordLogAttacker($check_protect,$method);
            $data = array(
                'result' => 'alert_security'
            );
            echo json_encode($data);
            return;
        }   
        ////////////////SISTEM KEAMANAN////////////////
        $this->db->select(' *,'
                . 'date_format(TANGGAL_LAHIR, \'%Y-%m-%d\') as TANGGAL_LAHIR', FALSE);
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $this->TABLE . ".ID_LHKPN", 'left');
        $this->db->where('ID_KELUARGA', $ID);
        $result = $this->db->get($this->TABLE)->row();
        $data = array();
        foreach ($result as $key => $value) {
            $data[$key] = trim($value);
        }
        //============================================================
        //Rian Ipdate
        $this->db->select('*');
        $this->db->where('ID_LHKPN', $result->ID_LHKPN);
        $datapribadi = $this->db->get('t_lhkpn_data_pribadi')->result();
        if ($datapribadi) {
            foreach ($datapribadi AS $ROW) {
                $alamatrumah = $ROW->ALAMAT_RUMAH;
            }
        }
        $data['Alamatrumah'] = $alamatrumah;
        //echo $this->db->last_query();exit;
        //End
        //============================================================

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function Delete($ID) {
        ////////////////SISTEM KEAMANAN////////////////
        $state_id_pn = $this->session->userdata('ID_PN');
        $check_protect = protectFilling($state_id_pn,$this->TABLE,$ID);
        if($check_protect){
            $method = __METHOD__;
            $this->load->model('mglobal');
            $this->mglobal->recordLogAttacker($check_protect,$method);
            echo 9;
            return;
        }   
        ////////////////SISTEM KEAMANAN////////////////
        $data = $this->mglobal->get_by_id($this->TABLE, 'ID_KELUARGA', $ID);
        $this->db->where('ID_KELUARGA', $ID);
        echo $this->db->delete($this->TABLE);
        ng::logActivity("Hapus Data Keluarga, ID_KELUARGA = ".$data->ID_KELUARGA.", ID_LHKPN = ".$data->ID_LHKPN);
    }

    function Update() {

        $state_id = $this->input->post('ID');
        $state_id_lhkpn = $this->input->post('ID_LHKPN');

        if($state_id){
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
        }

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


        $ID = $this->input->post('ID');
        $STATUS_HUBUNGAN = $this->input->post('STATUS_HUBUNGAN');
        if ($STATUS_HUBUNGAN == '1') {
            $TEMPAT_NIKAH = $this->input->post('TEMPAT');
            $TANGGAL_NIKAH = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post("TANGGAL"))));
            $TEMPAT_CERAI = NULL;
            $TANGGAL_CERAI = NULL;
        } else {
            $TEMPAT_NIKAH = NULL;
            $TANGGAL_NIKAH = NULL;
            $TEMPAT_CERAI = $this->input->post('TEMPAT');
            $TANGGAL_CERAI = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post("TANGGAL"))));
        }
        if ($this->input->post('HUBUNGAN') == '3' || $this->input->post('HUBUNGAN') == '4') {
            $TEMPAT_NIKAH = NULL;
            $TANGGAL_NIKAH = NULL;
            $TEMPAT_CERAI = NULL;
            $TANGGAL_CERAI = NULL;
        }

        $is_wna = $this->input->post('IS_WNA');

        $data = array(
            // 'ID_KELUARGA_LAMA' => $this->input->post('ID_KELUARGA_LAMA'),
            'ID_LHKPN' => $this->input->post('ID_LHKPN'),
            'NAMA' => $this->input->post('NAMA'),
            'IS_WNA' => $this->input->post('IS_WNA'),
            'NIK' => ($is_wna == 0)?$this->input->post('NIK_KELUARGA'):$this->input->post('NO_KITAS'),
            'HUBUNGAN' => $this->input->post('HUBUNGAN'),
            'STATUS_HUBUNGAN' => $this->input->post('STATUS_HUBUNGAN'),
            'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR'),
            'TANGGAL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post("TANGGAL_LAHIR")))),
            'JENIS_KELAMIN' => $this->input->post('JENIS_KELAMIN'),
            'TEMPAT_NIKAH' => $TEMPAT_NIKAH,
            'TANGGAL_NIKAH' => $TANGGAL_NIKAH,
            'TEMPAT_CERAI' => $TEMPAT_CERAI,
            'TANGGAL_CERAI' => $TANGGAL_CERAI,
            'PEKERJAAN' => $this->input->post('PEKERJAAN'),
            'ALAMAT_RUMAH' => $this->input->post('ALAMAT_RUMAH'),
            'NOMOR_TELPON' => $this->input->post('NOMOR_TELPON'),
            'IS_ACTIVE' => 1,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        );
        if ($ID) {
            $this->db->where('ID_KELUARGA', $ID);
            $result = $this->db->update($this->TABLE, $data);
            ng::logActivity("Ubah Data Keluarga, ID_KELUARGA = ".$ID.", ID_LHKPN = ".$data['ID_LHKPN']);
        } else {
            $result = $this->db->insert($this->TABLE, $data);
            ng::logActivity("Tambah Data Keluarga, ID_KELUARGA = ".$this->db->insert_id().", ID_LHKPN = ".$data['ID_LHKPN']);
        }
        if ($result) {
            echo "1";
        } else {
            echo "0";
        }
    }

}
