<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_pengeluaran extends CI_Controller{

	function __Construct(){
		parent::__Construct();
		call_user_func('ng::islogin');
	}

	function index(){
		$options = array();
		$this->load->view('portal/filing/data_pengeluaran',$options);
	}

	function GOLONGAN($index){
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
		return $data[$index];exit;
	}

	/* rollback
	function load_data($ID_LHKPN, $return_data = false){
		// create or update kas
		$this->create_or_update_kas($ID_LHKPN);
            
		$this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
                $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_pengeluaran_kas2.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
                $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
		$this->db->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN',$ID_LHKPN);
		$data = $this->db->get('t_lhkpn_pengeluaran_kas2')->result();

		$this->db->group_by('GOLONGAN');
		$this->db->order_by('GOLONGAN');
		$temp = $this->db->get('m_jenis_pengeluaran_kas')->result();

		//get lhkpn
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $lhkpn = $this->db->get('t_lhkpn')->row();

        //sum harta tidak bergerak
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
		$this->db->where('STATUS', 3);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->where('ASAL_USUL', 1);
        $this->db->like('TAHUN_PEROLEHAN_AWAL', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_peroleh1 = $this->db->get('t_lhkpn_harta_tidak_bergerak')->row();

        //sum harta bergerak
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
		$this->db->where('STATUS', 3);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->where('ASAL_USUL', 1);
        $this->db->like('TAHUN_PEROLEHAN_AWAL', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_peroleh2 = $this->db->get('t_lhkpn_harta_bergerak')->row();

        //sum harta bergerak lain
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
		$this->db->where('STATUS', 3);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->where('ASAL_USUL', 1);
        $this->db->like('TAHUN_PEROLEHAN_AWAL', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_peroleh3 = $this->db->get('t_lhkpn_harta_bergerak_lain')->row();

        //sum harta surat berharga
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
		$this->db->where('STATUS', 3);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->where('ASAL_USUL', 1);
        $this->db->like('TAHUN_PEROLEHAN_AWAL', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_peroleh4 = $this->db->get('t_lhkpn_harta_surat_berharga')->row();

        //sum harta lainnya
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
		$this->db->where('STATUS', 3);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->where('ASAL_USUL', 1);
        $this->db->like('TAHUN_PEROLEHAN_AWAL', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_peroleh5 = $this->db->get('t_lhkpn_harta_lainnya')->row();

        // total perolehan
        $total_peroleh = $sum_peroleh1->NILAI_PEROLEHAN;
        $total_peroleh += $sum_peroleh2->NILAI_PEROLEHAN;
        $total_peroleh += $sum_peroleh3->NILAI_PEROLEHAN;
        $total_peroleh += $sum_peroleh4->NILAI_PEROLEHAN;
		$total_peroleh += $sum_peroleh5->NILAI_PEROLEHAN;

		$jml = array();
		$total = 0;
		$i = 1;
		foreach($temp as $t){
			$this->db->select('SUM(JML) AS JML',false);
                        // $this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
                        // $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_pengeluaran_kas2.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
                        // $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
			$this->db->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN',$ID_LHKPN);
			$this->db->where('GROUP_JENIS',$this->GOLONGAN($t->GOLONGAN));
			$temp2 = $this->db->get('t_lhkpn_pengeluaran_kas2')->row();
			
			if($temp2->JML){
				$jml['SUM_'.$this->GOLONGAN($t->GOLONGAN)] = $temp2->JML;
			}else{
				$jml['SUM_'.$this->GOLONGAN($t->GOLONGAN)] = 0;	
			}

			$total+=$temp2->JML;
			$i++;
		}
        $jml['SUM_A'] = $jml['SUM_A'] ?: 0;
        $jml['SUM_B'] = $jml['SUM_B'] ? $jml['SUM_B'] : ($total_peroleh ?: 0);
        $jml['SUM_C'] = $jml['SUM_C'] ?: 0;

        $jml['SUM_ALL'] = $jml['SUM_A'] + $jml['SUM_B'] + $jml['SUM_C'];
		$jml['SUM_B0'] = $total_peroleh;
		if ($return_data) {
			$result_data = array('list'=>$data,'sum'=>$jml);
			return $result_data;
		} else {
			header('Content-Type: application/json');
			echo json_encode(array('list'=>$data,'sum'=>$jml));exit;
		}
	}
	rollback */

	function load_data($ID_LHKPN){
            
		$this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
                $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_pengeluaran_kas2.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
                $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
		$this->db->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN',$ID_LHKPN);
		$data = $this->db->get('t_lhkpn_pengeluaran_kas2')->result();

		$this->db->group_by('GOLONGAN');
		$this->db->order_by('GOLONGAN');
		$temp = $this->db->get('m_jenis_pengeluaran_kas')->result();

		$jml = array();
		$total = 0;
		$i = 1;
		foreach($temp as $t){
			$this->db->select('SUM(JML) AS JML',false);
                        // $this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
                        // $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_pengeluaran_kas2.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
                        // $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
			$this->db->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN',$ID_LHKPN);
			$this->db->where('GROUP_JENIS',$this->GOLONGAN($t->GOLONGAN));
			$temp2 = $this->db->get('t_lhkpn_pengeluaran_kas2')->row();
			
			if($temp2->JML){
				$jml['SUM_'.$this->GOLONGAN($t->GOLONGAN)] = $temp2->JML;
			}else{
				$jml['SUM_'.$this->GOLONGAN($t->GOLONGAN)] = 0;	
			}

			$total+=$temp2->JML;
			$i++;
		}
		$jml['SUM_ALL'] = "".$total;
		header('Content-Type: application/json');
        echo json_encode(array('list'=>$data,'sum'=>$jml));exit;
	}

	function load_pengeluaran($index,$golongan){
        $this->db->where('IS_ACTIVE','1');
		$this->db->order_by('ID_JENIS_PENGELUARAN_KAS');
		$this->db->where('GOLONGAN',$golongan);
		$data = $this->db->get('m_jenis_pengeluaran_kas')->result();
		if($data){
			$i = 1;
			$temp = 0;
			foreach($data as $row){
				echo '
					<tr>
	        			<td>'.$i.'</td>
	        			<td>'.$row->NAMA.'</td>
	        			<td>
							Rp.
							<input type="text" id="'.$index.''.$temp.'" name="'.$index.''.$temp.'" class="table-input-text  input" autocomplete="off"/>
							<input type="text" style="font-weight:bold;background-color:#92d0fe;width:100%" name="TERBILANG_' . $index . '' . $temp . '" id="TERBILANG_' . $index . '' . $temp . '" placeholder="" class="form-control"/>
						</td>
	        		</tr>
				';
				$temp++;
				$i++;
			}
		}
		exit;
	}

	function update(){

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

		$this->db->where('ID_LHKPN',$ID_LHKPN);
		$this->db->delete('t_lhkpn_pengeluaran_kas2');

		$this->db->where('ID_LHKPN',$ID_LHKPN);
		$check = $this->db->get('t_lhkpn_pengeluaran_kas')->result();

		$DATA = array(
			'ID_LHKPN' => $ID_LHKPN,
			'NILAI_PENGELUARAN_KAS' => NULL,
			'IS_ACTIVE' => 1,
			'CREATED_TIME' => date("Y-m-d H:i:s"),
			'CREATED_BY' => $this->session->userdata('NAMA'),
			'CREATED_IP' => get_client_ip(),
			'UPDATED_TIME' => date("Y-m-d H:i:s"),
			'UPDATED_BY' => $this->session->userdata('NAMA'),
			'UPDATED_IP' => get_client_ip(),
		);
		
		if($check){
			$this->db->where('ID_LHKPN',$ID_LHKPN);
			$update = $this->db->update('t_lhkpn_pengeluaran_kas',$DATA);
			if($update){
				$result = 1;
			}else{
				$result = 0;	
			}
		}else{
			$save = $this->db->insert('t_lhkpn_pengeluaran_kas',$DATA);
			if($save){
				$result = 1;
			}else{
				$result = 0;
			}
		}
		if($result==1){

			$this->db->select('GOLONGAN');
                        $this->db->where('IS_ACTIVE','1');
			$this->db->group_by('GOLONGAN');
			$this->db->order_by('GOLONGAN');
			$data = $this->db->get('m_jenis_pengeluaran_kas')->result();

			$PN  = array();
			$PASANGAN = array();
			foreach($data as $row){
				$this->db->where('GOLONGAN',$row->GOLONGAN);
                                $this->db->where('IS_ACTIVE','1');
				$value = $this->db->get('m_jenis_pengeluaran_kas')->result();
				$i = 0;
				$index = $this->GOLONGAN($row->GOLONGAN);
				foreach ($value as $rs) {
					$PN[$index][] = array($this->GOLONGAN($rs->GOLONGAN).''.$i=>$this->input->post($this->GOLONGAN($rs->GOLONGAN).''.$i));
					$v_pn = $this->input->post($this->GOLONGAN($rs->GOLONGAN).''.$i);
					$child = array(
						'ID_LHKPN'=>$ID_LHKPN,
						'GROUP_JENIS'=>$this->GOLONGAN($rs->GOLONGAN),
						'KODE_JENIS'=>$this->GOLONGAN($rs->GOLONGAN).''.$i,
						'JENIS_pengeluaran'=>$rs->NAMA,
						'JML'=>intval(str_replace('.', '', $v_pn)),
					);
					$this->db->insert('t_lhkpn_pengeluaran_kas2',$child);
					$i++;
				}

			}

			$this->db->where('ID_LHKPN',$ID_LHKPN);
			$update = $this->db->update('t_lhkpn_pengeluaran_kas',array(
				'NILAI_PENGELUARAN_KAS'=>json_encode($PN),
			));

		}
        ng::logActivity("Ubah Data Pengeluaran, Total Pengeluaran = ".$this->input->post('SUM-ALL').", ID_LHKPN = ".$DATA['ID_LHKPN']);

		echo $result;exit;
	}

    public function create_or_update_kas($id_lhkpn)
    {
		$result = false;
        $this->load->model('efill/Mpengeluaran', 'mpengeluaran');
		$pengeluaran = $this->mpengeluaran->create_or_update($id_lhkpn);
		if ($pengeluaran) {
			$result = true;
		}

		return $result;
	}

}