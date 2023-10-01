<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mpengeluaran extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_lhkpn($id_lhkpn)
    {
        $result = false;
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('IS_ACTIVE', 1);
        $lhkpn = $this->db->get('t_lhkpn')->row();
        $result = $lhkpn ?: false;

        return $result;
    }

    public function is_exists_pengeluaran1($id_lhkpn, $kode_jenis = null)
    {
        $result = false;
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('IS_ACTIVE', 1);
        if ($kode_jenis) {
            $this->db->like('NILAI_PENGELUARAN_KAS', '{"'.$kode_jenis.'"'); // where like '%{"kode_jenis:"%'
        }
        $pengeluaran1 = $this->db->get('t_lhkpn_pengeluaran_kas')->result();

        if ($pengeluaran1) {
            $result = $pengeluaran1;
        }

        return $result;
    }

    public function is_exists_pengeluaran2($id_lhkpn, $kode_jenis = null)
    {
        $result = false;
        $this->db->where('ID_LHKPN', $id_lhkpn);
        if ($kode_jenis) {
            $this->db->where('KODE_JENIS', $kode_jenis);
        }
        $pengeluaran2 = $this->db->get('t_lhkpn_pengeluaran_kas2')->result();
        
        if ($pengeluaran2) {
            $result = $pengeluaran2;
        }

        return $result;
    }

    public function is_exists($id_lhkpn)
    {
        $result = false;
        $kas = $this->is_exists_pengeluaran1($id_lhkpn);
        $kas2 = $this->is_exists_pengeluaran2($id_lhkpn);

        if ($kas && $kas2) {
            $result = [
                'kas' => $kas,
                'kas2' => $kas2
            ];
        }

        return $result;
    }

    /*
        Sama seperti Data_pengeluaran->load_data()
    */
    public function get_pengeluaran($id_lhkpn)
    {
        $this->db->join(
            'm_jenis_pengeluaran_kas jenis_kas',
            'jenis_kas.nama = kas2.jenis_pengeluaran',
            'left'
        );
        $this->db->join(
            't_lhkpn lhkpn',
            "lhkpn.ID_LHKPN = kas2.ID_LHKPN and lhkpn.ID_PN = '".$this->session->userdata('ID_PN')."'"
        );
        $this->db->where('jenis_kas.IS_ACTIVE', '1');
		$this->db->where('kas2.ID_LHKPN',$id_lhkpn);
        $data = $this->db->get('t_lhkpn_pengeluaran_kas2 kas2')->result();

		$this->db->group_by('GOLONGAN');
		$this->db->order_by('GOLONGAN');
		$temp = $this->db->get('m_jenis_pengeluaran_kas')->result();

		//get lhkpn
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $lhkpn = $this->db->get('t_lhkpn')->row();

        //sum harta tidak bergerak
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
		$this->db->where('STATUS', 3);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->where('ASAL_USUL', 1);
        $this->db->like('TAHUN_PEROLEHAN_AWAL', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_peroleh1 = $this->db->get('t_lhkpn_harta_tidak_bergerak')->row();

        //sum harta bergerak
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
		$this->db->where('STATUS', 3);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->where('ASAL_USUL', 1);
        $this->db->like('TAHUN_PEROLEHAN_AWAL', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_peroleh2 = $this->db->get('t_lhkpn_harta_bergerak')->row();

        //sum harta bergerak lain
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
		$this->db->where('STATUS', 3);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->where('ASAL_USUL', 1);
        $this->db->like('TAHUN_PEROLEHAN_AWAL', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_peroleh3 = $this->db->get('t_lhkpn_harta_bergerak_lain')->row();

        //sum harta surat berharga
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
		$this->db->where('STATUS', 3);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->where('ASAL_USUL', 1);
        $this->db->like('TAHUN_PEROLEHAN_AWAL', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_peroleh4 = $this->db->get('t_lhkpn_harta_surat_berharga')->row();

        //sum harta lainnya
        $this->db->select_sum('NILAI_PEROLEHAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
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
			$this->db->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN',$id_lhkpn);
			$this->db->where('GROUP_JENIS',$this->golongan($t->GOLONGAN));
			$temp2 = $this->db->get('t_lhkpn_pengeluaran_kas2')->row();
			
			if($temp2->JML){
				$jml['SUM_'.$this->golongan($t->GOLONGAN)] = $temp2->JML;
			}else{
				$jml['SUM_'.$this->golongan($t->GOLONGAN)] = 0;	
			}

			$total+=$temp2->JML;
			$i++;
		}
        $jml['SUM_A'] = $jml['SUM_A'] ?: 0;
        $jml['SUM_B'] = $jml['SUM_B'] ? $jml['SUM_B'] : ($total_peroleh ?: 0);
        $jml['SUM_C'] = $jml['SUM_C'] ?: 0;

        $jml['SUM_ALL'] = $jml['SUM_A'] + $jml['SUM_B'] + $jml['SUM_C'];
        $jml['SUM_B0'] = $total_peroleh;
        $perolehan = [
            'htb' => $sum_peroleh1->NILAI_PEROLEHAN,
            'hb' => $sum_peroleh4->NILAI_PEROLEHAN,
            'hbl' => $sum_peroleh2->NILAI_PEROLEHAN,
            'hsb' => $sum_peroleh3->NILAI_PEROLEHAN,
            'hl' => $sum_peroleh5->NILAI_PEROLEHAN
        ];
        $result_data = [
            'list' => $data,
            'sum' => $jml,
            'perolehan' => $perolehan
        ];
        
        return $result_data;
    }

    /**
     * create data ke t_lhkpn_pengeluaran_kas
     */
    public function create_kas($id_lhkpn)
    {
        $result = false;

        $this->db->select('GOLONGAN');
        $this->db->where('IS_ACTIVE','1');
        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $data = $this->db->get('m_jenis_pengeluaran_kas')->result();

        $PN = [];
        $data_kas2 = [];
        foreach ($data as $row) {
            $this->db->where('GOLONGAN', $row->GOLONGAN);
            $this->db->where('IS_ACTIVE','1');
            $value = $this->db->get('m_jenis_pengeluaran_kas')->result();
            $i = 0;
            $index = $this->golongan($row->GOLONGAN);
            foreach ($value as $rs) {
                $PN[$index][] = [
                    $this->golongan($rs->GOLONGAN) . '' . $i => 0
                ];
                $i++;
            }
        }

        $data_kas = array(
            'ID_LHKPN' => $id_lhkpn,
            'NILAI_PENGELUARAN_KAS' => json_encode($PN),
            'IS_ACTIVE' => 1,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        );
        
        $save_kas = $this->db->insert('t_lhkpn_pengeluaran_kas', $data_kas);
        
        if ($save_kas) {
            $result = true;
        }

        return $result;
    }

    /**
     * create data ke t_lhkpn_pengeluaran_kas2
     */
    public function create_kas2($id_lhkpn)
    {
        $result = false;

        $this->db->select('GOLONGAN');
        $this->db->where('IS_ACTIVE','1');
        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $data = $this->db->get('m_jenis_pengeluaran_kas')->result();

        $data_kas2 = [];
        foreach ($data as $row) {
            $this->db->where('GOLONGAN', $row->GOLONGAN);
            $this->db->where('IS_ACTIVE','1');
            $value = $this->db->get('m_jenis_pengeluaran_kas')->result();
            $i = 0;
            $index = $this->golongan($row->GOLONGAN);
            foreach ($value as $rs) {
                $data_kas2[] = [
                    'ID_LHKPN' => $id_lhkpn,
                    'GROUP_JENIS' => $this->golongan($rs->GOLONGAN),
                    'KODE_JENIS' => $this->golongan($rs->GOLONGAN) . '' . $i,
                    'JENIS_PENGELUARAN' => $rs->NAMA,
                    'JML' => 0
                ];
                $i++;
            }
        }
        
        $save_kas2 = $this->db->insert_batch('t_lhkpn_pengeluaran_kas2', $data_kas2);
        
        if ($save_kas2) {
            $result = true;
        }

        return $result;
    }

    /**
     * akumulasi ulang Pengeluaran (kode_jenis = B0)
     * dengan kondisi (Status Harta = Status Baru
     * Tahun perolehan = Tahun pelaporan
     * Asal-usul = Hasil Sendiri)
     */
    public function re_acumulate($id_lhkpn)
    {
        $result = false;
        $lhkpn = $this->get_lhkpn($id_lhkpn);
        $is_exists = $this->is_exists($id_lhkpn);

        if ($lhkpn && $is_exists) {
            $pengeluaran = $this->get_pengeluaran($id_lhkpn);
            $sum_b0 = $pengeluaran['sum']['SUM_B0'];
            $nilai_pengeluaran_kas2 = [];

            foreach ($pengeluaran['list'] as $list) {
                $pengeluaran_pn = $list->KODE_JENIS === 'B0'
                    ? $sum_b0
                    : $list->JML;
                $nilai_pengeluaran_kas2[$list->GROUP_JENIS][] = [
                    $list->KODE_JENIS => number_format($pengeluaran_pn,0,",",".")
                ];
            }
            
            // update pengeluaran kas
            $this->db->where([
                'ID_LHKPN' => $id_lhkpn,
                'IS_ACTIVE' => 1
            ]);
            $update_kas1 = $this->db->update(
                't_lhkpn_pengeluaran_kas',
                [
                    'NILAI_PENGELUARAN_KAS' => json_encode($nilai_pengeluaran_kas2),
                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                    'UPDATED_TIME' => date('Y-m-d H:i:s'),
                    'UPDATED_IP' => $this->input->ip_address()
                ]
            );

            // update pengeluaran kas2
            $this->db->where([
                'ID_LHKPN' => $id_lhkpn,
                'KODE_JENIS' => 'B0'
            ]);
            $update_kas2 = $this->db->update(
                't_lhkpn_pengeluaran_kas2',
                [
                    'JML' => $sum_b0
                ]
            );
            
            if ($update_kas1 && $update_kas2) {
                $result = $pengeluaran;
            } else {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * create kas && kas2 if not exists
     * and
     * do re_acumulate
     */
    public function create_or_update($id_lhkpn)
    {
        $result = true;
        $is_exists_kas = $this->is_exists_pengeluaran1($id_lhkpn);
        $is_exists_kas2 = $this->is_exists_pengeluaran2($id_lhkpn);

        //do create kas && kas 2 if not exists
        if (!$is_exists_kas || !$is_exists_kas2) {
            if (!$is_exists_kas) {
                $create_kas = $this->create_kas($id_lhkpn);
                if (!$create_kas) {
                    $result = false;
                }
            }
            if (!$is_exists_kas2) {
                $create_kas2 = $this->create_kas2($id_lhkpn);
                if (!$create_kas2) {
                    $result = false;
                }
            }
        }

        // do re_acumulate
        $acumulate = $this->re_acumulate($id_lhkpn);
        if (!$acumulate) {
            $result = false;
        }

        return $result;
    }

	public function golongan($index){
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
}
