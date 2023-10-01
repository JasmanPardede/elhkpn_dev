<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_penerimaan extends CI_Controller {

    function __Construct() {
        parent::__Construct();
        call_user_func('ng::islogin');
    }

    function index() {
        $options = array();
        $this->load->view('portal/filing/data_penerimaan', $options);
    }

    /* rollback
    function load_data($ID_LHKPN, $return_data = false) {
		// create or update kas
		$this->create_or_update_kas($ID_LHKPN);
        
        $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_penerimaan_kas2.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
        $this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_penerimaan_kas2')->result();

        //get lhkpn
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $lhkpn = $this->db->get('t_lhkpn')->row();

        //sum harta tidak bergerak
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual1 = $this->db->get('t_lhkpn_pelepasan_harta_tidak_bergerak')->row();

        //sum harta bergerak
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual2 = $this->db->get('t_lhkpn_pelepasan_harta_bergerak')->row();

        //sum harta bergerak lainnya
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual3 = $this->db->get('t_lhkpn_pelepasan_harta_bergerak_lain')->row();

        //sum harta surat berharga
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual4 = $this->db->get('t_lhkpn_pelepasan_harta_surat_berharga')->row();

        //sum harta lainnya
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual5 = $this->db->get('t_lhkpn_pelepasan_harta_lainnya')->row();

        // total jual
        $total_jual = $sum_jual1->NILAI_PELEPASAN;
        $total_jual += $sum_jual2->NILAI_PELEPASAN;
        $total_jual += $sum_jual3->NILAI_PELEPASAN;
        $total_jual += $sum_jual4->NILAI_PELEPASAN;
        $total_jual += $sum_jual5->NILAI_PELEPASAN;
        
        $this->db->where('IS_ACTIVE','1');
        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $temp = $this->db->get('m_jenis_penerimaan_kas')->result();

        $jml = array();
        $total = 0;
        $i = 1;
        foreach ($temp as $t) {
            $this->db->select('SUM(PN+PASANGAN) AS JML', false);
            // $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
            // $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_penerimaan_kas2.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
            // $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
            $this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $ID_LHKPN);
            $this->db->where('GROUP_JENIS', $this->GOLONGAN($t->GOLONGAN));
            $temp2 = $this->db->get('t_lhkpn_penerimaan_kas2')->row();

            if ($temp2->JML) {
                $jml['SUM_' . $this->GOLONGAN($t->GOLONGAN)] = $temp2->JML;
            } else {
                $jml['SUM_' . $this->GOLONGAN($t->GOLONGAN)] = 0;
            }

            $i++;
        }
        $jml['SUM_A'] = $jml['SUM_A'] ?: 0;
        $jml['SUM_B'] = $jml['SUM_B'] ? $jml['SUM_B'] : ($total_jual ?: 0);
        $jml['SUM_C'] = $jml['SUM_C'] ?: 0;

        $jml['SUM_ALL'] = $jml['SUM_A'] + $jml['SUM_B'] + $jml['SUM_C'];
        $jml['SUM_B3'] = $total_jual;
        if ($return_data) {
			$result_data = array('list'=>$data,'sum'=>$jml);
			return $result_data;
		} else {
			header('Content-Type: application/json');
            echo json_encode(array('list' => $data, 'sum' => $jml));exit;
		}
    }
    rollback */

    function load_data($ID_LHKPN) {
        
        $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_penerimaan_kas2.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
        $this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_penerimaan_kas2')->result();
        
        $this->db->where('IS_ACTIVE','1');
        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $temp = $this->db->get('m_jenis_penerimaan_kas')->result();

        $jml = array();
        $total = 0;
        $i = 1;
        foreach ($temp as $t) {
            $this->db->select('SUM(PN+PASANGAN) AS JML', false);
            // $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
            // $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_penerimaan_kas2.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
            // $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
            $this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $ID_LHKPN);
            $this->db->where('GROUP_JENIS', $this->GOLONGAN($t->GOLONGAN));
            $temp2 = $this->db->get('t_lhkpn_penerimaan_kas2')->row();

            if ($temp2->JML) {
                $jml['SUM_' . $this->GOLONGAN($t->GOLONGAN)] = $temp2->JML;
            } else {
                $jml['SUM_' . $this->GOLONGAN($t->GOLONGAN)] = 0;
            }


            $total += $temp2->JML;
            $i++;
        }
        $jml['SUM_ALL'] = "" . $total;
        header('Content-Type: application/json');
        echo json_encode(array('list' => $data, 'sum' => $jml));exit;
    }

    function load_penerimaan($index, $golongan) {
        $this->db->where('IS_ACTIVE','1');
        $this->db->order_by('ID_JENIS_PENERIMAAN_KAS');
        $this->db->where('GOLONGAN', $golongan);
        $data = $this->db->get('m_jenis_penerimaan_kas')->result();

        if ($data) {
            $i = 1;
            $temp = 0;
            foreach ($data as $row) {
                if($row->NAMA == "Gaji dan Tunjangan"){
                    $cek = $row->NAMA.'<text style=color:red>*</text>';
                }else{
                    $cek = $row->NAMA;
                }
                if ($golongan == 1) {
                    echo '
						<tr>
	        				<td>' . $i . '</td>
	        				<td>' .$cek.'</td>
	        				<td>
                                Rp.
                                <input type="text" id="' . $index . '' . $temp . '" name="' . $index . '' . $temp . '" class="table-input-text  input" autocomplete="off"/>
                                <input type="text" style="font-weight:bold;background-color:#92d0fe;width:100%" name="TERBILANG_' . $index . '' . $temp . '" id="TERBILANG_' . $index . '' . $temp . '" placeholder="" class="form-control"/>
                            </td>
	        				<td>
                                Rp.
                                <input type="text" id="PA' . $temp . '" name="PA' . $temp . '" class="table-input-text  input" autocomplete="off"/>
                                <input type="text" style="font-weight:bold;background-color:#92d0fe;width:100%" name="TERBILANG_PA' . $temp . '" id="TERBILANG_PA' . $temp . '" placeholder="" class="form-control"/>
                            </td>
	        			</tr>
					';
                } else {
                    echo '
						<tr>
	        				<td>' . $i . '</td>
	        				<td>' . $row->NAMA . '</td>
	        				<td>
                                Rp.
                                <input type="text" id="' . $index . '' . $temp . '" name="' . $index . '' . $temp . '" class="table-input-text  input" autocomplete="off"/>
                                <input type="text" style="font-weight:bold;background-color:#92d0fe;width:100%" name="TERBILANG_' . $index . '' . $temp . '" id="TERBILANG_' . $index . '' . $temp . '" placeholder="" class="form-control"/>
                            </td>
	        			</tr>
					';
                }
                $temp++;
                $i++;
            }
        }
    exit;
	}

    function update() {

        $state_id_lhkpn = $this->input->post('ID_LHKPN');

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

        $result = NULL;
        $ID_LHKPN = $this->input->post('ID_LHKPN');

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->delete('t_lhkpn_penerimaan_kas2');

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $check = $this->db->get('t_lhkpn_penerimaan_kas')->result();

        $DATA = array(
            'ID_LHKPN' => $ID_LHKPN,
            'LAINNYA' => $this->input->post('LAINNYA'),
            'NILAI_PENERIMAAN_KAS_PN' => NULL,
            'NILAI_PENERIMAAN_KAS_PASANGAN' => NULL,
            'IS_ACTIVE' => 1,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        );

        if($this->input->post('A0')==0){
          echo 90;exit;
        }

        if ($check) {
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $update = $this->db->update('t_lhkpn_penerimaan_kas', $DATA);
            if ($update) {
                $result = 1;
            } else {
                $result = 0;
            }
        } else {
            $save = $this->db->insert('t_lhkpn_penerimaan_kas', $DATA);
            if ($save) {
                $result = 1;
            } else {
                $result = 0;
            }
        }
        if ($result == 1) {

            $this->db->select('GOLONGAN');
            $this->db->where('IS_ACTIVE','1');
            $this->db->group_by('GOLONGAN');
            $this->db->order_by('GOLONGAN');
            $data = $this->db->get('m_jenis_penerimaan_kas')->result();

            $PN = array();
            $PASANGAN = array();
            foreach ($data as $row) {
                $this->db->where('GOLONGAN', $row->GOLONGAN);
                $this->db->where('IS_ACTIVE','1');
                $value = $this->db->get('m_jenis_penerimaan_kas')->result();
                $i = 0;
                $index = $this->GOLONGAN($row->GOLONGAN);
                foreach ($value as $rs) {
                    $PN[$index][] = array($this->GOLONGAN($rs->GOLONGAN) . '' . $i => $this->input->post($this->GOLONGAN($rs->GOLONGAN) . '' . $i));
                    if ($rs->GOLONGAN == 1) {
                        $PASANGAN[] = array('P' . $this->GOLONGAN($rs->GOLONGAN) . '' . $i => $this->input->post('P' . $this->GOLONGAN($rs->GOLONGAN) . '' . $i));
                        $v_pn = $this->input->post($this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                        $v_ps = $this->input->post('P' . $this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                        $child = array(
                            'ID_LHKPN' => $ID_LHKPN,
                            'GROUP_JENIS' => $this->GOLONGAN($rs->GOLONGAN),
                            'KODE_JENIS' => $this->GOLONGAN($rs->GOLONGAN) . '' . $i,
                            'JENIS_PENERIMAAN' => $rs->NAMA,
                            'PN' => intval(str_replace('.', '', $v_pn)),
                            'PASANGAN' => intval(str_replace('.', '', $v_ps)),
                        );
                        $this->db->insert('t_lhkpn_penerimaan_kas2', $child);
                    } else {
                        $v_pn = $this->input->post($this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                        $child = array(
                            'ID_LHKPN' => $ID_LHKPN,
                            'GROUP_JENIS' => $this->GOLONGAN($rs->GOLONGAN),
                            'KODE_JENIS' => $this->GOLONGAN($rs->GOLONGAN) . '' . $i,
                            'JENIS_PENERIMAAN' => $rs->NAMA,
                            'PN' => intval(str_replace('.', '', $v_pn)),
                            'PASANGAN' => 0
                        );
                        $this->db->insert('t_lhkpn_penerimaan_kas2', $child);
                    }
                    $i++;
                }
            }

            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $update = $this->db->update('t_lhkpn_penerimaan_kas', array(
                'NILAI_PENERIMAAN_KAS_PN' => json_encode($PN),
                'NILAI_PENERIMAAN_KAS_PASANGAN' => json_encode($PASANGAN)
            ));
        }
        ng::logActivity("Ubah Data Penerimaan, Total Penerimaan = ".$this->input->post('SUM-ALL').", ID_LHKPN = ".$DATA['ID_LHKPN']);

        echo $result;exit;
    }

    function test() {

        $this->db->select('GOLONGAN');
        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $data = $this->db->get('m_jenis_penerimaan_kas')->result();

        $block = array();
        foreach ($data as $row) {
            $this->db->where('GOLONGAN', $row->GOLONGAN);
            $result = $this->db->get('m_jenis_penerimaan_kas')->result();
            $i = 0;
            $index = $this->GOLONGAN($row->GOLONGAN);
            foreach ($result as $rs) {
                $block[$index][] = array($this->GOLONGAN($rs->GOLONGAN) . '' . $i => 'VALUE' . $i);
                $i++;
            }
        }



        header('Content-Type: application/json');
        echo json_encode($block);exit;
    }

    function GOLONGAN($index) {
        $data = array();
        $data['1'] = 'A';
        $data['2'] = 'B';
        $data['3'] = 'C';
        $data['4'] = 'D';
        $data['5'] = 'E';
        $data['6'] = 'F';
        $data['7'] = 'G';
        $data['8'] = 'H';
        $data['9'] = 'I';
        $data['10'] = 'J';
        $data['11'] = 'K';
        $data['12'] = 'L';
        $data['13'] = 'M';
        $data['14'] = 'N';
        $data['15'] = 'O';
        $data['16'] = 'P';
        $data['17'] = 'Q';
        $data['18'] = 'R';
        $data['19'] = 'S';
        $data['20'] = 'T';
        $data['21'] = 'U';
        $data['22'] = 'V';
        $data['23'] = 'W';
        $data['24'] = 'X';
        $data['25'] = 'Y';
        $data['26'] = 'Z';
        return $data[$index];
    }

    public function create_or_update_kas($id_lhkpn)
    {
		$result = false;
        $this->load->model('efill/Mpenerimaan', 'mpenerimaan');
		$penerimaan = $this->mpenerimaan->create_or_update($id_lhkpn);
		if ($penerimaan) {
			$result = true;
		}

		return $result;
	}

}
