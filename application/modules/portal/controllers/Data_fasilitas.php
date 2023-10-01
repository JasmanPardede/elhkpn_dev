<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_fasilitas extends CI_Controller {

    function __Construct() {
        parent::__Construct();
        $this->TABLE = 't_lhkpn_fasilitas';
        call_user_func('ng::islogin');
        $this->load->model('mglobal');

    }

    function index() {
        $options = array();
        $this->load->view('portal/filing/data_fasilitas', $options);
    }
    
    private function __set_join_fasilitas($TABLE, $PK){
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        
        $this->db->group_by($TABLE . '.' . $PK);
    }
    
    function pk($TABLE) {
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        return $data[0]['Column_name'];
    }

    function TableFasilitas($ID_LHKPN) {
        $TABLE = 't_lhkpn_fasilitas';
        $PK = $this->pk($TABLE);
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->where($this->TABLE . '.ID_LHKPN', $ID_LHKPN);
        if ($cari) {
//        	$this->db->like('JENIS_FASILITAS',$cari);
//        	$this->db->or_like('NAMA_FASILITAS',$cari);
//        	$this->db->or_like('PEMBERI_FASILITAS',$cari);
//        	$this->db->or_like('KETERANGAN',$cari);
//		$this->db->or_like('KETERANGAN_LAIN',$cari);
            $sql_like = " (JENIS_FASILITAS LIKE '%" . $cari . "%' OR "
                    . " NAMA_FASILITAS LIKE '%" . $cari . "%' OR "
                    . " PEMBERI_FASILITAS LIKE '%" . $cari . "%' OR "
                    . " KETERANGAN LIKE '%" . $cari . "%' OR "
                    . " KETERANGAN_LAIN LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $this->TABLE . ".ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->db->order_by('ID', 'DESC');
        $data = $this->db->get($this->TABLE)->result();
        if ($data) {
            foreach ($data as $list) {
                $i++;

                $html = "
					<table class='table table-child table-condensed'>
                        <tr>
                            <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $list->JENIS_FASILITAS . "</td>
                         </tr>
                         <tr>
                            <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . $list->KETERANGAN . "</td>
                        </tr>
                    </table>
				";

                $edit = "<a id='" . $list->ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
                $delete = "<a id='" . $list->ID . "'  href='javascript:void(0)' class='btn btn-danger btn-sm delete-action' title='Delete'><i class='fa fa-trash'></i></a>";

                if ($list->STATUS == '1') {
                    $action = $edit;
                } else {
                    $action = $edit . '' . $delete;
                }

                $aaData[] = array(
                    $i,
                    $html,
                    $list->PEMBERI_FASILITAS,
                    $list->KETERANGAN_LAIN,
                    $action
                );
            }
        }
        $this->db->where($this->TABLE . '.ID_LHKPN', $ID_LHKPN);
        if ($cari) {
            $this->db->where($sql_like);
        }
        $this->__set_join_fasilitas($TABLE, $PK);
        $jml = $this->db->get($this->TABLE)->num_rows();
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);exit;
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

        $this->db->where('t_lhkpn_fasilitas.ID', $ID);
        $result = $this->db->get($this->TABLE)->row();
        $data = array();
        foreach ($result as $key => $value) {
            $data[$key] = trim($value);
        }
        header('Content-Type: application/json');
        echo json_encode($data);exit;
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
        $data2 = $this->mglobal->get_by_id($this->TABLE, 'ID', $ID);
        ng::logActivity("Hapus Data Fasilitas, ID = ".$ID.", ID_LHKPN = ".$data2->ID_LHKPN);

        $this->db->where('ID', $ID);
        echo $this->db->delete($this->TABLE);
    }

    function Update() {
        $ID = $this->input->post('ID');
        $ID_LHKPN = $this->input->post('ID_LHKPN');

        if($ID){
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
        }

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


        $data = array(
            'ID_LHKPN' => $this->input->post('ID_LHKPN'),
            'JENIS_FASILITAS' => $this->input->post('JENIS_FASILITAS'),
            'NAMA_FASILITAS' => $this->input->post('NAMA_FASILITAS'),
            'PEMBERI_FASILITAS' => $this->input->post('PEMBERI_FASILITAS'),
            'KETERANGAN' => $this->input->post('KETERANGAN'),
            'KETERANGAN_LAIN' => $this->input->post('KETERANGAN_LAIN'),
            'IS_ACTIVE' => 1,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        );
        if ($ID) {
            $this->db->where('ID', $ID);
            $result = $this->db->update($this->TABLE, $data);
            ng::logActivity("Ubah Data Fasilitas, ID = ".$ID.", ID_LHKPN = ".$data['ID_LHKPN']);

        } else {
            $result = $this->db->insert($this->TABLE, $data);
            ng::logActivity("Tambah Data Fasilitas, ID = ".$this->db->insert_id().", ID_LHKPN = ".$data['ID_LHKPN']);
        }
        if ($result) {
            echo "1";
        } else {
            echo "0";
        }
    }

}