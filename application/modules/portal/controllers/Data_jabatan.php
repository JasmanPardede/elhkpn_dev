<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_jabatan extends MY_Controller {

    function __Construct() {
        parent::__Construct();
        $this->TABLE = 't_lhkpn_jabatan';
        $this->load->model('mglobal');
        call_user_func('ng::islogin');
    }

    function index() {
        $options["js_page"][] = $this->load->view("filing/js/data_jabatan/index_js", array(), TRUE);
        $this->load->view('filing/data_jabatan', $options);
//        $this->load->view('portal/filing/data_jabatan', $options);
    }

    function GetLembaga() {
        $key = $this->input->post_get('q');
        $this->db->limit(10);
        $this->db->like('INST_NAMA', $key);
        $this->db->order_by('INST_NAMA', 'ASC');
        $result = $this->db->get('m_inst_satker')->result();
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->INST_SATKERKD,
                'text' => strtoupper($row->INST_NAMA)
            );
        }
        header('Content-type: application/json');
        echo json_encode($array);
    }

    function GetUK($ID_LEMBAGA) {
        $key = $this->input->post_get('q');
//        $this->db->limit(10);
        $this->db->select('M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA, COUNT(m_sub_unit_kerja.suk_id) AS c_SUK_ID, COUNT(m_jabatan.id_jabatan) AS ID_JAB');
        $this->db->join('m_sub_unit_kerja','m_sub_unit_kerja.uk_id = M_UNIT_KERJA.uk_id','left');
        $this->db->join('m_jabatan','m_jabatan.suk_id = m_sub_unit_kerja.suk_id','left');
        $this->db->where('UK_LEMBAGA_ID', $ID_LEMBAGA);
        $this->db->where("M_UNIT_KERJA.UK_STATUS <> '0' AND M_UNIT_KERJA.UK_STATUS <> '-1'");
        $this->db->like('UK_NAMA', $key);
        $this->db->group_by('M_UNIT_KERJA.UK_ID');
        $this->db->group_by('M_UNIT_KERJA.UK_NAMA');
        $this->db->order_by('UK_NAMA', 'ASC');
        $result = $this->db->get('m_unit_kerja')->result();
        $array = array();
        foreach ($result as $row) {
            if (($row->c_SUK_ID != 0 || $row->c_SUK_ID != '0') && ($row->ID_JAB != 0 || $row->ID_JAB != '0')){
                $array[] = ['id' => $row->UK_ID, 'text' => strtoupper($row->UK_NAMA)];
            }
//            else{
//                $array[] = ['id' => $row->UK_ID, 'text' => strtoupper($row->UK_NAMA)];
//            }
        }
        header('Content-type: application/json');
        echo json_encode($array);exit;
    }

    function GetSUBUK($ID_UK) {
        $key = $this->input->post_get('q');
        $this->db->limit(10);
        $this->db->where('UK_ID', $ID_UK);
        $this->db->where("UK_STATUS <> '0' AND UK_STATUS <> '-1'");
        $this->db->like('SUK_NAMA', $key);
        $this->db->order_by('SUK_NAMA', 'ASC');
        $result = $this->db->get('m_sub_unit_kerja')->result();
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->SUK_ID,
                'text' => strtoupper($row->SUK_NAMA)
            );
        }
        header('Content-type: application/json');
        echo json_encode($array);exit;
    }

    function GetJabatan($UK_ID, $SUK_ID = NULL) {
        $key = $this->input->post_get('q');
        $this->db->limit(10);
        $this->db->where('UK_ID', $UK_ID);
        $this->db->where("IS_ACTIVE <> '0' && IS_ACTIVE <> '-1'");
        if ($SUK_ID) {
            $this->db->where('SUK_ID', $SUK_ID);
        }
        $this->db->like('NAMA_JABATAN', $key);
        $this->db->order_by('NAMA_JABATAN', 'ASC');
        $result = $this->db->get('m_jabatan')->result();
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->ID_JABATAN,
                'text' => strtoupper($row->NAMA_JABATAN)
            );
        }
        header('Content-type: application/json');
        echo json_encode($array);exit;
    }

    function test() {

        $ID_LHKPN = 21;
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $lhkpn = $this->db->get('t_lhkpn')->row();

        $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('t_pn_jabatan.TMT <>', $lhkpn->TGL_LAPOR);
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_pn_jabatan.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_pn_jabatan.LEMBAGA', 'left');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_NAMA = t_pn_jabatan.UNIT_KERJA', 'left');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_NAMA = t_pn_jabatan.SUB_UNIT_KERJA', 'left');
        $this->db->order_by('t_pn_jabatan.TMT', 'DESC');
        $array = $this->db->get('t_pn_jabatan')->row();
        print_r($array);
        header('Content-type: application/json');
        //echo json_encode($array);
    }

    function TableJabatan($ID_LHKPN) {
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

//        var_dump($keyword);exit;
        $this->load->model('mlhkpnjabatan');

        $record_set = $this->mlhkpnjabatan->get_data_jabatan_by_id_lhkpn($ID_LHKPN, $currentpage, $rowperpage, $keyword);

        //display($this->db->last_query())  ;
        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($record_set->total_rows),
            "iTotalDisplayRecords" => intval($record_set->total_rows),
            "aaData" => $record_set->result
        );

        $this->to_json($dtable_output);
    }

    function Edit($ID) {
        $this->db->where('t_lhkpn_jabatan.ID', $ID);
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = m_jabatan.UK_ID', 'left');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_ID = m_jabatan.SUK_ID', 'left');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_jabatan.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        $result = $this->db->get($this->TABLE)->row();
//        display($this->db->last_query());
        $data = array();
        foreach ($result as $key => $value) {
            $data[$key] = trim($value);
        }
        header('Content-Type: application/json');
        echo json_encode($data);exit;
    }

    function Delete_($ID) {
        $this->db->where('ID', $ID);
        $this->db->delete($this->TABLE);
        echo '1';
    }
	function Delete($ID) {
		$data['data_pn'] = $this->Get_id_jabtan_t_pn_jabatan($ID);
    
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


		
		// if( $data['data_pn']){
		// 	$arr_pn = array(
		// 	'IS_ACTIVE'=>0
		// 	);
		// 	$update = $this->mglobal->update_data_('T_PN_JABATAN', $arr_pn, 'ID', $data['data_pn']->ID);
            $this->db->where('ID', $ID);
            $this->db->delete($this->TABLE);
            ng::logActivity("Hapus Data Rangkap Jabatan, ID_JABATAN = ".$data['data_pn']->ID_JABATAN.", ID_LHKPN = ".$data['data_pn']->ID_LHKPN);
            
			echo '1';
		// }
		//var_dump($id_pn_jabatan);
    }
	function Get_id_jabtan_t_pn_jabatan($IDs){
		// $this->db->select('tpnjabatan.*');
		$this->db->select('tljabatan.*');
        // $this->db->from('t_lhkpn_jabatan tljabatan, t_lhkpn tlh, t_pn_jabatan tpnjabatan');
        $this->db->from('t_lhkpn_jabatan tljabatan');
        // $this->db->where('tljabatan.ID_LHKPN=tlh.ID_LHKPN');
        // $this->db->where('tpnjabatan.ID_PN=tlh.ID_PN');
        // $this->db->where('tljabatan.ID_JABATAN=tpnjabatan.ID_JABATAN');
        $this->db->where('tljabatan.ID', $IDs);


        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
	}
    function Get($id, $table, $column, $value) {
        $result = NULL;
        $this->db->where($id, $value);
        $this->db->limit(1);
        $data = $this->db->get($table)->row();
        if ($data) {
            $result = $data->$column;
        }
        return $result;
    }

    function Update($mode = NULL) {

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

        switch ($mode) {
            default:
                $ID = $this->input->post('ID');
                $LEMBAGA = $this->input->post('LEMBAGA');
                $jab = $this->mglobal->get_by_id('m_jabatan', 'ID_JABATAN', $this->input->post('ID_JABATAN'));
                $ese = $this->mglobal->get_by_id('m_eselon', 'KODE_ESELON', $jab->KODE_ESELON);
                $SUB_UNIT_KERJA = $this->input->post('SUB_UNIT_KERJA') ? $this->input->post('SUB_UNIT_KERJA') : NULL;
                if ($ID) {
        //            $LEMBAGA = $this->session->userdata("INST_SATKERKD");
                    $LEMBAGA = $this->input->post("ID_LEM");
                }
                $data = array(
                    'ID_JABATAN' => $this->input->post('ID_JABATAN'),
                    'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                    'DESKRIPSI_JABATAN' => $this->DESKRIPSI_JABATAN($this->input->post('ID_JABATAN')),
                    'ESELON' => $ese->ID_ESELON,
                    'LEMBAGA' => $LEMBAGA,
                    'UNIT_KERJA' => $this->input->post('UNIT_KERJA'),
                    'SUB_UNIT_KERJA' => $SUB_UNIT_KERJA,
                    'TMT' => $this->input->post('TMT'),
                    'SD' => $this->input->post('SD'),
                    'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR'),
                    'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR'),
                    'FILE_SK' => $this->input->post('FILE_SK'),
                    'CREATED_TIME' => date("Y-m-d H:i:s"),
                    'CREATED_BY' => $this->session->userdata('NAMA'),
                    'CREATED_IP' => get_client_ip(),
                    'UPDATED_TIME' => date("Y-m-d H:i:s"),
                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                    'UPDATED_IP' => get_client_ip(),
                    'ID_STATUS_AKHIR_JABAT' => 0,
        //            'IS_PRIMARY' => $this->input->post('IS_PRIMARY'),
                    'TEXT_JABATAN_PUBLISH' => $this->input->post('TEXT_JABATAN_PUBLISH'),
                );
                if ($ID) {

                    $this->db->where('ID', $ID);
                    $result = $this->db->update($this->TABLE, $data);
                    ng::logActivity("Ubah Data Rangkap Jabatan, ID_JABATAN = ".$data['ID_JABATAN'].", ID_LHKPN = ".$data['ID_LHKPN']);

                    $ID_PN = $this->session->userdata('ID_PN');
                    $ID_JABATAN = $this->input->post('ID_JABATAN');
                    $UNIT_KERJA = $this->Get('UK_ID', 'm_unit_kerja', 'UK_NAMA', $this->input->post('UNIT_KERJA'));
                    $SUB_UNIT_KERJA = $this->Get('SUK_ID', 'm_sub_unit_kerja', 'SUK_NAMA', $this->input->post('SUB_UNIT_KERJA'));

                    $PN_JABATAN = array(
                        'ID_PN' => $ID_PN,
                        'ID_JABATAN' => $ID_JABATAN,
                        'DESKRIPSI_JABATAN' => $this->DESKRIPSI_JABATAN($ID_JABATAN),
                        'ESELON' => $jab->KODE_ESELON,
                        'NAMA_LEMBAGA' => $this->NAMA_LEMBAGA($LEMBAGA),
                        'LEMBAGA' => $LEMBAGA,
                        'UNIT_KERJA' => $this->UNIT_KERJA($this->input->post('UNIT_KERJA')),
                        'SUB_UNIT_KERJA' => $this->SUB_UNIT_KERJA($this->input->post('SUB_UNIT_KERJA')),
                        'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR'),
                        'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR'),
                        'CREATED_TIME' => time(),
                        'CREATED_BY' => $this->session->userdata('NAMA'),
                        'CREATED_IP' => get_client_ip(),
                        'UPDATED_TIME' => time(),
                        'UPDATED_BY' => $this->session->userdata('NAMA'),
                        'UPDATED_IP' => get_client_ip(),
        //                'ID_STATUS_AKHIR_JABAT' => 11,
                        'ID_STATUS_AKHIR_JABAT' => 1,
                        'IS_CALON' => 0,
                        'IS_ACTIVE' => 1,
                        'IS_DELETED' => 0,
                        'IS_CURRENT' => 1,
                        'IS_WL' => 1,
                    );
                    // $this->db->insert('t_pn_jabatan', $PN_JABATAN);
                } else {
                    $result = $this->db->insert($this->TABLE, $data);
                    ng::logActivity("Tambah Data Rangkap Jabatan, ID_JABATAN = ".$data['ID_JABATAN'].", ID_LHKPN = ".$data['ID_LHKPN']);
                    
                    $ID_PN = $this->session->userdata('ID_PN');
                    $this->db->where('ID_PN', $ID_PN);
                    $this->db->update('t_pn', array('IS_ACTIVE' => 1));

                    $ID_JABATAN = $this->input->post('ID_JABATAN');
                    $UNIT_KERJA = $this->Get('UK_ID', 'm_unit_kerja', 'UK_NAMA', $this->input->post('UNIT_KERJA'));
                    $SUB_UNIT_KERJA = $this->Get('SUK_ID', 'm_sub_unit_kerja', 'SUK_NAMA', $this->input->post('SUB_UNIT_KERJA'));
                    $PN_JABATAN = array(
                        'ID_PN' => $ID_PN,
                        'ID_JABATAN' => $ID_JABATAN,
                        'DESKRIPSI_JABATAN' => $this->DESKRIPSI_JABATAN($ID_JABATAN),
                        'ESELON' => $jab->KODE_ESELON,
                        'NAMA_LEMBAGA' => $this->NAMA_LEMBAGA($LEMBAGA),
                        'LEMBAGA' => $LEMBAGA,
                        'UNIT_KERJA' => $this->UNIT_KERJA($this->input->post('UNIT_KERJA')),
                        'SUB_UNIT_KERJA' => $this->SUB_UNIT_KERJA($this->input->post('SUB_UNIT_KERJA')),
                        'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR'),
                        'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR'),
                        'CREATED_TIME' => time(),
                        'CREATED_BY' => $this->session->userdata('NAMA'),
                        'CREATED_IP' => get_client_ip(),
                        'UPDATED_TIME' => time(),
                        'UPDATED_BY' => $this->session->userdata('NAMA'),
                        'UPDATED_IP' => get_client_ip(),
        //                'ID_STATUS_AKHIR_JABAT' => 0,
                        'ID_STATUS_AKHIR_JABAT' => 1,
                        'IS_CALON' => 0,
                        'IS_ACTIVE' => 1,
                        'IS_DELETED' => 0,
                        'IS_CURRENT' => 1,
                        'IS_WL' => 1,
                    );
                    // $this->db->insert('t_pn_jabatan', $PN_JABATAN);
                }
                if ($result) {
                    echo "1";
                } else {
                    echo "0";
                }
            break;
            case 'primary':
                //////  INSERT T_LHKPN_JABATAN  //////
                $data = array(
                    'ID_JABATAN' => $this->input->post('ID_JABATAN'),
                    'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                    'DESKRIPSI_JABATAN' => $this->DESKRIPSI_JABATAN($this->input->post('ID_JABATAN')),
                    'ESELON' => $ese->ID_ESELON,
                    'LEMBAGA' => $LEMBAGA,
                    'UNIT_KERJA' => $this->input->post('UNIT_KERJA'),
                    'SUB_UNIT_KERJA' => $SUB_UNIT_KERJA,
                    'TMT' => $this->input->post('TMT'),
                    'SD' => $this->input->post('SD'),
                    'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR'),
                    'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR'),
                    'FILE_SK' => $this->input->post('FILE_SK'),
                    'CREATED_TIME' => date("Y-m-d H:i:s"),
                    'CREATED_BY' => $this->session->userdata('NAMA'),
                    'CREATED_IP' => get_client_ip(),
                    'UPDATED_TIME' => date("Y-m-d H:i:s"),
                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                    'UPDATED_IP' => get_client_ip(),
                    'ID_STATUS_AKHIR_JABAT' => 0,
                    'TEXT_JABATAN_PUBLISH' => $this->input->post('TEXT_JABATAN_PUBLISH'),
                );
                $result = $this->db->insert($this->TABLE, $data);
                ng::logActivity("Tambah Data Rangkap Jabatan, ID_JABATAN = ".$data['ID_JABATAN'].", ID_LHKPN = ".$data['ID_LHKPN']);

                //////  INSERT T_PN_JABATAN  //////                    
                $ID_PN = $this->session->userdata('ID_PN');
                $this->db->where('ID_PN', $ID_PN);
                $this->db->update('t_pn', array('IS_ACTIVE' => 1));
                $ID_JABATAN = $this->input->post('ID_JABATAN');
                $UNIT_KERJA = $this->Get('UK_ID', 'm_unit_kerja', 'UK_NAMA', $this->input->post('UNIT_KERJA'));
                $SUB_UNIT_KERJA = $this->Get('SUK_ID', 'm_sub_unit_kerja', 'SUK_NAMA', $this->input->post('SUB_UNIT_KERJA'));
                $PN_JABATAN = array(
                    'ID_PN' => $ID_PN,
                    'ID_JABATAN' => $ID_JABATAN,
                    'DESKRIPSI_JABATAN' => $this->DESKRIPSI_JABATAN($ID_JABATAN),
                    'ESELON' => $jab->KODE_ESELON,
                    'NAMA_LEMBAGA' => $this->NAMA_LEMBAGA($LEMBAGA),
                    'LEMBAGA' => $LEMBAGA,
                    'UNIT_KERJA' => $this->UNIT_KERJA($this->input->post('UNIT_KERJA')),
                    'SUB_UNIT_KERJA' => $this->SUB_UNIT_KERJA($this->input->post('SUB_UNIT_KERJA')),
                    'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR'),
                    'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR'),
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('NAMA'),
                    'CREATED_IP' => get_client_ip(),
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                    'UPDATED_IP' => get_client_ip(),
                    'ID_STATUS_AKHIR_JABAT' => 1,
                    'IS_CALON' => 0,
                    'IS_ACTIVE' => 1,
                    'IS_DELETED' => 0,
                    'IS_CURRENT' => 1,
                    'IS_WL' => 1,
                );
                // $this->db->insert('t_pn_jabatan', $PN_JABATAN);
                $ID_NEW = $this->db->query("SELECT id FROM t_lhkpn_jabatan WHERE id_lhkpn = '".$state_id_lhkpn."' ORDER BY id DESC limit 1")->row();

                //////  PRIMARY KEY NEW DATA  //////
                $this->db->trans_begin();
                $dataall = array('IS_PRIMARY' => '0');
                $data = array('IS_PRIMARY' => '1');
                $this->mglobal->update_data('t_lhkpn_jabatan', $dataall, 'ID_LHKPN', $state_id_lhkpn);
                $this->mglobal->update_data('t_lhkpn_jabatan', $data, 'ID', $ID_NEW->id);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
                echo intval($this->db->trans_status());
            break;
        }

    }

    function SetPrimary() {
        $ID = $this->input->post('ID');
        $ID_LHKPN = $this->input->post('ID_LHKPN');


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
    
        
        

        ////////////////SISTEM KEAMANAN////////////////
            $state_id_pn = $this->session->userdata('ID_PN');
            $check_protect = protectLhkpn($state_id_pn,$ID_LHKPN);  
            if($check_protect){
                $method = __METHOD__;
                $this->load->model('mglobal');
                $this->mglobal->recordLogAttacker($check_protect,$method);
                echo 9;
                return;
            }   
        ////////////////SISTEM KEAMANAN////////////////


        $this->db->trans_begin();
        $dataall = array(
            'IS_PRIMARY' => '0'
        );
        $data = array(
            'IS_PRIMARY' => '1'
        );

        $this->mglobal->update_data('t_lhkpn_jabatan', $dataall, 'ID_LHKPN', $ID_LHKPN);
        $this->mglobal->update_data('t_lhkpn_jabatan', $data, 'ID', $ID);
//        $this->db->where('ID', $ID);
//        $result = $this->db->update('t_lhkpn_jabatan', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    function DESKRIPSI_JABATAN($ID_JABATAN) {
        $result = NULL;
        $this->db->where('ID_JABATAN', $ID_JABATAN);
        $data = $this->db->get('m_jabatan')->row();
        if ($data) {
            $result = $data->NAMA_JABATAN;
        }
        return $result;
    }

    function NAMA_LEMBAGA($INST_SATKERKD) {
        $result = NULL;
        $this->db->where('INST_SATKERKD', $INST_SATKERKD);
        $data = $this->db->get('m_inst_satker')->row();
        if ($data) {
            $result = $data->INST_NAMA;
        }
        return $result;
    }

    function UNIT_KERJA($UK_ID) {
        $result = NULL;
        $this->db->where('UK_ID', $UK_ID);
        $data = $this->db->get('m_unit_kerja')->row();
        if ($data) {
            $result = $data->UK_NAMA;
        }
        return $result;
    }

    function SUB_UNIT_KERJA($SUK_ID) {
        $result = NULL;
        $this->db->where('SUK_ID', $SUK_ID);
        $data = $this->db->get('m_sub_unit_kerja')->row();
        if ($data) {
            $result = $data->SUK_NAMA;
        }
        return $result;
    }

}
