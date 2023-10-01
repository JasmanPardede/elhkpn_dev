<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Klarifikasi_model extends CI_Model {

	protected $t_lhkpn = 't_lhkpn';
	protected $t_pribadi = 't_lhkpn_data_pribadi';
	protected $t_keluarga = 't_lhkpn_keluarga';
	protected $t_jabatan = 't_lhkpn_jabatan';
	protected $t_htb = 't_lhkpn_harta_tidak_bergerak';

	protected $m_jabatan = 'm_jabatan';
	protected $t_harta_bergerak = 't_lhkpn_harta_bergerak';
	protected $t_harta_bergerak_lain = 't_lhkpn_harta_bergerak_lain';
	protected $t_harta_surat_berharga = 't_lhkpn_harta_surat_berharga';
	protected $t_harta_kas = 't_lhkpn_harta_kas';
	protected $t_harta_lainnya = 't_lhkpn_harta_lainnya';
	protected $t_hutang = 't_lhkpn_hutang';
	protected $t_penerimaan2 = 't_lhkpn_penerimaan_kas2';
	protected $t_fasilitas = 't_lhkpn_fasilitas';
	protected $m_inst_satker = 'm_inst_satker';
	protected $m_unit_kerja = 'm_unit_kerja';
	protected $m_sub_unit_kerja = 'm_sub_unit_kerja';

	protected $t_audit = 't_lhkpn_audit';
	protected $t_lampiran = 't_lhkpn_audit_lampiran';

	function __construct()
	{
		parent::__construct();
	}

	public function get_data_lhkpn_by_old_id($old_id)
	{
		//$this->db->where('ID_LHKPN', $old_id);
		$this->db->where('ID_LHKPN_PREV', $old_id);
		$this->db->where('JENIS_LAPORAN', 5);
		$this->db->where('IS_ACTIVE', 1);
		$data = $this->db->get($this->t_lhkpn);
		if ($data->num_rows() == 1) {
			return $data->row();
		}
		else{
			$this->db->where('ID_LHKPN', $old_id);
			$data = $this->db->get($this->t_lhkpn);
			if ($data->num_rows() == 1) {
				return $data->row();
			}else{
				return false;
			}
		}
	}

	public function get_data_lhkpn_pn_by_old_id($old_id)
	{
		 $this->db->where('ID_LHKPN_PREV', $old_id);
		$this->db->where('t_lhkpn.IS_ACTIVE', 1);
		$this->db->where('t_pn.IS_ACTIVE', 1);
		//$this->db->where('ID_LHKPN', $old_id);
		$this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN');
		$data = $this->db->get($this->t_lhkpn);
		if ($data->num_rows() == 1) {
			return $data->row();
		}
		else{
			return false;
		}
	}

	public function get_data_pribadi_by_id($id)
	{
		$this->db->where('ID_LHKPN', $id);
		$data = $this->db->get($this->t_pribadi);
		if ($data->num_rows() == 1) {
			return $data->row();
		}
		else{
			return false;
		}
	}

	public function get_data_jabatan_by_id($id)
	{
		$this->db->where('t_lhkpn_jabatan.ID_LHKPN', $id);
		$this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN');
		$this->db->join('m_unit_kerja', 'm_jabatan.UK_ID = m_unit_kerja.UK_ID');
		$this->db->join('m_sub_unit_kerja', 'm_jabatan.SUK_ID = m_sub_unit_kerja.SUK_ID', 'left');
		$this->db->join('m_inst_satker', 'm_jabatan.INST_SATKERKD = m_inst_satker.INST_SATKERKD');
		$data = $this->db->get($this->t_jabatan);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_keluarga_by_id($id)
	{
		$this->db->where('ID_LHKPN', $id);
		$this->db->where('IS_ACTIVE', '1');
		$this->db->order_by('HUBUNGAN', 'ASC');
		$this->db->order_by('TANGGAL_LAHIR', 'ASC');
		$data = $this->db->get($this->t_keluarga);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_htb_by_id($id)
	{
		$this->db->select('t_lhkpn_harta_tidak_bergerak.*, m_jenis_bukti.JENIS_BUKTI as HTB_JENIS_BUKTI, m_negara.NAMA_NEGARA');
		$this->db->where('t_lhkpn_harta_tidak_bergerak.ID_LHKPN', $id);
		$this->db->where('t_lhkpn_harta_tidak_bergerak.IS_ACTIVE', '1');
		$this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = t_lhkpn_harta_tidak_bergerak.JENIS_BUKTI', 'left');
		$this->db->join('m_negara', 'm_negara.ID = t_lhkpn_harta_tidak_bergerak.ID_NEGARA', 'left');
		$this->db->order_by('ID', 'DESC');
		$data = $this->db->get($this->t_htb);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}
	
	public function pk($TABLE) {
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        return $data[0]['Column_name'];
    }
	
	public function grid_harta_tidak_bergerak($ID_LHKPN) {  

        $TABLE = 't_lhkpn_harta_tidak_bergerak';
        $PK = $this->pk($TABLE);
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $sql_like = "";
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        if ($cari) {

            $sql_like = " (JALAN LIKE '%" . $cari . "%' OR "
                    . " KEL LIKE '%" . $cari . "%' OR "
                    . " KEC LIKE '%" . $cari . "%' OR "
                    . " KAB_KOT LIKE '%" . $cari . "%' OR "
                    . " NAMA_NEGARA LIKE '%" . $cari . "%' OR "
                    . " LUAS_BANGUNAN LIKE '%" . $cari . "%' OR "
                    . " LUAS_TANAH LIKE '%" . $cari . "%' OR "
                    . " m_jenis_bukti.JENIS_BUKTI LIKE '%" . $cari . "%' OR "
                    . " NOMOR_BUKTI LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        } 
        $this->db->select('
        	`m_jenis_bukti`.*,
                `m_mata_uang`.*,
                `t_lhkpn`.*,
                `t_lhkpn`.`STATUS` `STATUS_LHKPN`,
                `t_lhkpn_harta_tidak_bergerak`.*,

                `t_lhkpn_harta_tidak_bergerak`.`STATUS` AS `STATUS_HARTA`,
                CASE 
                      WHEN `t_lhkpn_harta_tidak_bergerak`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_tidak_bergerak`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
                `m_jenis_bukti`.`JENIS_BUKTI` AS `JENIS_BUKTI_HARTA`
        ');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_harta_tidak_bergerak($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');  
        $data = $this->db->get($TABLE)->result(); 
		
        if ($data) {
            foreach ($data as $list) { 
                $i++;
                $this->db->where('ID', $list->ID_NEGARA);
                $ng = $this->db->get('m_negara')->row();
                if ($list->ID_NEGARA == '2') {
                    $JALAN = $list->JALAN;
                    $KEL = $list->KEL;
                    $KEC = $list->KEC;
                    $KOTA = $list->KAB_KOT;
                    $NEGARA = $ng->NAMA_NEGARA;
//                    $NEGARA = $list->aNEGARA;
                    $lokasi = "
						<table class='table table-child table-condensed'>
	                        <tr>
	                            <td><b>Jalan / No</b></td>
	                            <td>:</td>
	                            <td>" . $JALAN . "</td>
	                         </tr>
	                         <tr>
	                            <td><b>Kel / Desa</b></td>
	                            <td>:</td>
	                            <td>" . $KEL . "</td>
	                        </tr>
	                         <tr>
	                            <td><b>Kecamatan</b></td>
	                            <td>:</td>
	                            <td>" . $KEC . "</td>
	                        </tr>
	                         <tr>
	                            <td><b>Kab/Kota</b></td>
	                            <td>:</td>
	                            <td>" . $KOTA . "</td>
	                        </tr>
	                         <tr>
	                            <td><b>Prov / Negara</b></td>
	                            <td>:</td>
	                            <td>" . $list->PROV . ' / ' . $NEGARA . "</td>
	                        </tr>
	                    </table>
					";
                } else {
                    $JALAN = $list->JALAN;
                    $KEL = '';
                    $KEC = '';
                    $KOTA = '';
                    $NEGARA = $ng->NAMA_NEGARA;
                    $lokasi = "
					  <table class='table table-child table-condensed'>
					  	  <tr>
	                        <td><b>Jalan</b></td>
	                        <td>:</td>
	                        <td>" . $JALAN . "</td>
	                      </tr>
	                       <tr>
	                            <td><b>Negara</b></td>
	                            <td>:</td>
	                            <td>" . $NEGARA . "</td>
	                        </tr>
					  </table>
					";
                }

                $luas = "
					<table class='table table-child table-condensed'>
                        <tr>
                            <td><b>Tanah</b></td>
                            <td>:</td>
                            <td>" . str_replace('.', ',', $list->LUAS_TANAH) . " m <sup>2</sup></td>
                         </tr>
                         <tr>
                            <td><b>Bangunan</b></td>
                            <td>:</td>
                            <td>" . str_replace('.', ',', $list->LUAS_BANGUNAN) . " m <sup>2</sup></td>
                         </tr>
                    </table>
				";

                $this->db->where('ID_HARTA', $list->ID);
                $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.ID_ASAL_USUL');
                $asul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak')->result();
                $list_asul = NULL;
                $c_asl = explode(',', $list->ASAL_USUL);
                if ((int) count($c_asl) == 1) {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI';
                    } else {
                        if ($asul) {
                            $list_asul .= '' . $asul[0]->ASAL_USUL . ' ';
                        } else {
                            foreach ($asul as $a) {
                                $list_asul .= '' . $a->ASAL_USUL . ' , ';
                            }
                        }
                    }
                } else {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI , ';
                    }
                    if ($asul) {
                        foreach ($asul as $a) {
                            $list_asul .= '' . $a->ASAL_USUL . ' , ';
                        }
                    }
                }


                $this->db->where_in('ID_PEMANFAATAN', explode(",", $list->PEMANFAATAN));
                $pm = $this->db->get('m_pemanfaatan')->result();
                $list_pm = NULL;
                if ($pm) {
                    if (count($pm) == 1) {
                        foreach ($pm as $p) {
                            $list_pm .= '' . $p->PEMANFAATAN . '  ';
                        }
                    } else {
                        foreach ($pm as $p) {
                            $list_pm .= '' . $p->PEMANFAATAN . ' , ';
                        }
                    }
                }

                
                $get_atas_nama = $list->ATAS_NAMA;
                $atas_nama = '';              
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }        
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $list->PASANGAN_ANAK);
                    $get_list_pasangan = '';
                    $loop_first_pasangan = 0;
                    foreach($pasangan_array as $ps){
                        $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                        $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                        if($loop_first_pasangan==0){
                            $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                        }else{
                            $get_list_pasangan = $get_list_pasangan.',<br> '.$data_pasangan_anak[0]['NAMA'];
                        }
                        $loop_first_pasangan++;
                    }
                    $show_pasangan = $get_list_pasangan;
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }
                }
                if(strstr($get_atas_nama, "3")){
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$list->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$list->KET_LAINNYA.')' ;
                    }
                }
                
                $kepemilikan = "
					<table class='table table-child table-condensed'>
                        <tr>
                            <td><b>Jenis Bukti</b></td>
                            <td>:</td>
                            <td>" . $list->JENIS_BUKTI_HARTA . "</td>
                         </tr>
                         <tr>
                            <td><b>Nomor Bukti</b></td>
                            <td>:</td>
                            <td>" . $list->NOMOR_BUKTI . "</td>
                         </tr>
                         <tr>
                            <td><b>Atas Nama</b></td>
                            <td>:</td>

                            <td>" . $atas_nama . "</td>
                         </tr>
                         <tr>
                            <td><b>Asal Usul Harta</b></td>
                            <td>:</td>
                            <td>
                            	 " . $list_asul . "
                            </td>
                         </tr>
                         <tr>
                            <td><b>Pemanfaatan</b></td>
                            <td>:</td>
                            <td>
                            	" . $list_pm . "
                            </td>
                         </tr>
                         <tr>
                            <td><b>Tahun Perolehan</b></td>
                            <td>:</td>
                            <td>
                            	" . $list->TAHUN_PEROLEHAN_AWAL . "
                            </td>
                         </tr>
                    </table>
				";

                if ($list->ID_LHKPN == $ID_LHKPN) {
                    $aaData[] = array(
                        $i,
                        $this->STATUS_HARTA($list->STATUS_HARTA, $list->IS_PELEPASAN),
                        $lokasi,
                        $luas,
                        $kepemilikan,
                        'Rp. ' . number_rupiah($list->NILAI_PEROLEHAN) . '',
                        'Rp. ' . number_rupiah($list->NILAI_PELAPORAN) . '',
                        $list->STATUS_LHKPN == '0' || $list->STATUS_LHKPN == '2' && $list->entry_via == '0' ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);

        if ($cari) {
            $this->db->where($sql_like);
        }
        $this->__set_join_grid_harta_tidak_bergerak($TABLE, $PK);
        $jml = $this->db->get($TABLE)->num_rows(); 
        
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
        exit;
    }
	
	private function __set_join_grid_harta_tidak_bergerak($TABLE, $PK) {
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
        $this->db->join('m_negara', 'm_negara.ID = ' . $TABLE . '.ID_NEGARA ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN ");
		//and ID_PN = '" . $this->session->userdata('ID_PN') . "'
        $this->db->group_by($TABLE . '.' . $PK);
    }

	public function get_data_harta_bergerak_by_id($id)
	{
		$this->db->where('t_lhkpn_harta_bergerak.ID_LHKPN', $id);
		$this->db->where('t_lhkpn_harta_bergerak.IS_ACTIVE', '1');
		$this->db->order_by('ID', 'DESC');
		$data = $this->db->get($this->t_harta_bergerak);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_harta_bergerak_lain_by_id($id)
	{
		$this->db->select('t_lhkpn_harta_bergerak_lain.*, m_jenis_harta.NAMA as HBL_NAMA');
		$this->db->where('t_lhkpn_harta_bergerak_lain.ID_LHKPN', $id);
		$this->db->where('t_lhkpn_harta_bergerak_lain.IS_ACTIVE', '1');
		$this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_bergerak_lain.KODE_JENIS', 'left');
		$this->db->order_by('ID', 'DESC');
		$data = $this->db->get($this->t_harta_bergerak_lain);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_harta_surat_berharga_by_id($id)
	{
		$this->db->select('t_lhkpn_harta_surat_berharga.*, m_jenis_harta.NAMA as HSB_NAMA');
		$this->db->where('t_lhkpn_harta_surat_berharga.ID_LHKPN', $id);
		$this->db->where('t_lhkpn_harta_surat_berharga.IS_ACTIVE', '1');
		$this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_surat_berharga.KODE_JENIS', 'left');
		$this->db->order_by('ID', 'DESC');
		$data = $this->db->get($this->t_harta_surat_berharga);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_harta_kas_by_id($id)
	{
		$this->db->select('t_lhkpn_harta_kas.*, m_jenis_harta.NAMA as HKAS_NAMA, m_mata_uang.SIMBOL');
		$this->db->where('t_lhkpn_harta_kas.ID_LHKPN', $id);
		$this->db->where('t_lhkpn_harta_kas.IS_ACTIVE', '1');
		$this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_kas.KODE_JENIS', 'left');
		$this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = t_lhkpn_harta_kas.MATA_UANG', 'left');
		$this->db->order_by('ID', 'DESC');
		$data = $this->db->get($this->t_harta_kas);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_harta_lainnya_by_id($id)
	{
		$this->db->select('t_lhkpn_harta_lainnya.*, m_jenis_harta.NAMA as HLAIN_NAMA, m_mata_uang.SIMBOL');
		$this->db->where('t_lhkpn_harta_lainnya.ID_LHKPN', $id);
		$this->db->where('t_lhkpn_harta_lainnya.IS_ACTIVE', '1');
		$this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_lainnya.KODE_JENIS', 'left');
		$this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = t_lhkpn_harta_lainnya.MATA_UANG', 'left');
		$this->db->order_by('ID', 'DESC');
		$data = $this->db->get($this->t_harta_lainnya);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_hutang_by_id($id)
	{
		$this->db->select('t_lhkpn_hutang.*, m_jenis_hutang.NAMA');
		$this->db->where('t_lhkpn_hutang.ID_LHKPN', $id);
		$this->db->where('t_lhkpn_hutang.IS_ACTIVE', '1');
		$this->db->join('m_jenis_hutang', 'm_jenis_hutang.ID_JENIS_HUTANG = t_lhkpn_hutang.KODE_JENIS', 'left');
		$this->db->order_by('ID_HUTANG', 'DESC');
		$data = $this->db->get($this->t_hutang);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_penerimaan_kas_by_id($id)
	{
		// $this->db->select('t_lhkpn_hutang.*, m_mata_uang.SIMBOL');
		$this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $id);
		// $this->db->where('t_lhkpn_penerimaan_kas2.IS_ACTIVE', '1');
		// $this->db->join('m_jenis_hutang', 'm_jenis_hutang.ID_JENIS_HUTANG = t_lhkpn_hutang.KODE_JENIS', 'left');
		// $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = t_lhkpn_penerimaan_kas2.MATA_UANG', 'left');
		$data = $this->db->get($this->t_penerimaan2);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_fasilitas_by_id($id)
	{
		$this->db->where('t_lhkpn_fasilitas.ID_LHKPN', $id);
		$this->db->where('t_lhkpn_fasilitas.IS_ACTIVE', '1');
		$data = $this->db->get($this->t_fasilitas);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return false;
		}
	}

	public function get_data_by_id($id, $type)
	{
		switch ($type) {
			// case 'data_pribadi':
			// 	$this->db->update($this->t_pribadi, $posted_fields);
			// 	break;

			case 'harta_tidak_bergerak':
				$this->db->where('ID', $id);
				$data = $this->db->get($this->t_htb); 
				break;

			case 'harta_bergerak':
				$this->db->where('ID', $id);
				$data = $this->db->get($this->t_harta_bergerak);
				break;

			case 'harta_bergerak_lain':
				$this->db->where('ID', $id);
				$data = $this->db->get($this->t_harta_bergerak_lain);
				break;

			case 'harta_surat_berharga':
				$this->db->where('ID', $id);
				$data = $this->db->get($this->t_harta_surat_berharga);
				break;

			case 'harta_kas':
				$this->db->where('ID', $id); 
				$data = $this->db->get($this->t_harta_kas); 
				break;

			case 'harta_lainnya':
				$this->db->where('ID', $id);
				$data = $this->db->get($this->t_harta_lainnya);
				break;

			case 'hutang':
				$this->db->where('ID_HUTANG', $id);
				$data = $this->db->get($this->t_hutang);
				break;

			case 'penerimaan_kas':
				$this->db->where('ID_LHKPN', $id);
				$data = $this->db->get($this->t_penerimaan);
				break;

			case 'fasilitas':
				$this->db->where('ID', $id);
				$data = $this->db->get($this->t_fasilitas);
				break;

			case 'lampiran_bak':
				$this->db->where('ID', $id);
				$data = $this->db->get($this->t_lampiran);
				break;

			case 'jabatan':
			    $this->db->where('ID', $id);
			    $data = $this->db->get($this->t_jabatan);
			    break;

			case 'inst_satker':
    			$this->db->where('INST_SATKERKD', $id);
    			$data = $this->db->get($this->m_inst_satker);
       			break;

			case 'unit_kerja':
    			$this->db->where('UK_ID', $id);
    			$data = $this->db->get($this->m_unit_kerja);
		      	break;

			case 'sub_unit_kerja':
    			$this->db->where('SUK_ID', $id);
    			$data = $this->db->get($this->m_sub_unit_kerja);
			    break;

			case 'jabatan_1':
    			$this->db->where('ID_JABATAN', $id);
    			$data = $this->db->get($this->m_jabatan);
    			break;

			case 'lhkpn_audit':
    			$this->db->select('T_LHKPN_AUDIT.*, T_USER.NAMA, T_USER.EMAIL, T_USER.HANDPHONE');
    			$this->db->where('ID_AUDIT', $id);
				$this->db->join('T_USER', 'T_LHKPN_AUDIT.ID_PIC = T_USER.ID_USER');
    			$data = $this->db->get($this->t_audit);
    			break;

			default:
				$data = false;
				break;
		}
		
		
				$this->db->where('ID_HARTA', $id);
				$this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.ID_ASAL_USUL');
				$asal_usul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak')->result(); 

		if ($data->num_rows() == 1) {
			return $data = array(
								'result' => $data->row(),
								'asal_usul' => $asal_usul
							);
		}
		else{
			return null;
		}
	}

	public function update_data($posted_fields, $id, $type, $no_st='')
	{ 
		$id = trim($id);
		switch ($type) {
			case 'data_pribadi':
				$this->db->where('ID', $id);
				$this->db->update($this->t_pribadi, $posted_fields);
				break;

			case 'jabatan':
				$this->db->where('ID', $id);
				$this->db->update($this->t_jabatan, $posted_fields);
				break;

			case 'harta_tidak_bergerak': 
				if ($id) { 
					$this->db->where('ID', $id);
					$result = $this->db->update($this->t_htb, $posted_fields); 
				} else {
					$result = $this->db->insert($this->t_htb, $posted_fields); 
				}
				
				if ($result) {
					if ($id) {
						return $id;
					} else {
						return $this->db->insert_id();
					}
				} else {
					return "0";
				}
				break;

			case 'harta_bergerak':
			
				if ($id) { 
					$this->db->where('ID', $id);
					$result = $this->db->update($this->t_harta_bergerak, $posted_fields); 
				} else {
					$result = $this->db->insert($this->t_harta_bergerak, $posted_fields); 
				}
				
				if ($result) {
					if ($id) {
						return $id;
					} else {
						return $this->db->insert_id();
					}
				} else {
					return "0";
				}
				break;

			case 'harta_bergerak_lain':
				if ($id) {
					$this->db->where('ID', $id);
					$result = $this->db->update($this->t_harta_bergerak_lain, $posted_fields); 
				} else {
					$result = $this->db->insert($this->t_harta_bergerak_lain, $posted_fields);
				}
				
				if ($result) {
					if ($id) {
						return $id;
					} else {
						return $this->db->insert_id();
					}
				} else {
					return "0";
				}
				//$this->db->where('ID', $id);
				//$this->db->update($this->t_harta_bergerak_lain, $posted_fields);
				break;

			case 'harta_surat_berharga':
				if ($id) {
					$this->db->where('ID', $id);
					$result = $this->db->update($this->t_harta_surat_berharga, $posted_fields); 
				} else {
					$result = $this->db->insert($this->t_harta_surat_berharga, $posted_fields);
				}
				
				if ($result) {
					if ($id) {
						return $id;
					} else {
						return $this->db->insert_id();
					}
				} else {
					return "0";
				}
				//$this->db->where('ID', $id);
				//$this->db->update($this->t_harta_surat_berharga, $posted_fields);
				break;

			case 'harta_kas':
				if ($id) {
					$this->db->where('ID', $id);
					$result = $this->db->update($this->t_harta_kas, $posted_fields); 
				} else {
					$result = $this->db->insert($this->t_harta_kas, $posted_fields);
				}
				
				if ($result) {
					if ($id) {
						return $id;
					} else {
						return $this->db->insert_id();
					}
				} else {
					return "0";
				}
				//$this->db->where('ID', $id);
				//$this->db->update($this->t_harta_kas, $posted_fields);
				break;

			case 'harta_lainnya':
				if ($id) {
					$this->db->where('ID', $id);
					$result = $this->db->update($this->t_harta_lainnya, $posted_fields);  
				} else {
					//$result = $this->db->insert($TABLE, $DATA);
					$result = $this->db->insert($this->t_harta_lainnya, $posted_fields); 
				}
				
				if ($result) {
					if ($id) {
						return $id;
					} else {
						return $this->db->insert_id();
					}
				} else {
					return "0";
				}
				//$this->db->where('ID', $id);
				//$this->db->update($this->t_harta_lainnya, $posted_fields);
				break;

			case 'hutang':
				$this->db->where('ID_HUTANG', $id);
				$this->db->update($this->t_hutang, $posted_fields);
				break;

			case 'penerimaan_kas':
				$this->db->where('ID_LHKPN', $id);
				$this->db->update($this->t_penerimaan, $posted_fields);
				break;

			case 'catatan_pemeriksaan':
				$this->db->where('ID_LHKPN', $id);
				$this->db->update($this->t_lhkpn, $posted_fields);
				break;

			case 'fasilitas':
			    $this->db->where('ID', $id);
			    $this->db->update($this->t_fasilitas, $posted_fields);
				break;
			
			case 'audit':
				// $this->db->where('ID_AUDIT', $id);
				$this->db->where('id_lhkpn', $id);
  				$this->db->where('nomor_surat_tugas', $no_st);
			    $this->db->update($this->t_audit, $posted_fields);
				break;
				
			case 'lhkpn':
			    $this->db->where('ID_LHKPN', $id);
			    $this->db->update($this->t_lhkpn, $posted_fields);
				break;
			

				default:
				$data = false;
				break;
		}if ($this->db->affected_rows() > 0) {
		 	return true;
		}
		else{
			return false;
		}
	}

	public function insert_data($posted_fields, $type)
	{
		switch ($type) {
			case 'harta_tidak_bergerak':
				$this->db->insert($this->t_htb, $posted_fields);
				break;

			case 'harta_bergerak':
				$this->db->insert($this->t_harta_bergerak, $posted_fields);
				break;

			case 'harta_bergerak_lain':
				$this->db->insert($this->t_harta_bergerak_lain, $posted_fields);
				break;

			case 'harta_surat_berharga':
				$this->db->insert($this->t_harta_surat_berharga, $posted_fields);
				break;

			case 'harta_kas':
				$this->db->insert($this->t_harta_kas, $posted_fields);
				break;

			case 'harta_lainnya':
				$this->db->insert($this->t_harta_lainnya, $posted_fields);
				return $this->db->insert_id();
				break;

			case 'hutang':
				$this->db->insert($this->t_hutang, $posted_fields);
				break;

			case 'lampiran_bak':
				$this->db->insert($this->t_lampiran, $posted_fields);
				break;

			case 'jabatan':
			    $this->db->insert($this->t_jabatan, $posted_fields);
			    break;

			case 'fasilitas':
			    $this->db->insert($this->t_fasilitas, $posted_fields);
			    break;

			default:
				$data = false;
				break;
		}

		if ($this->db->affected_rows() > 0) {
		 	return true;
		}
		else{
			return false;
		}
	}

	public function soft_delete_by_id($id, $type)
	{
		$posted_fields = array(
			'IS_ACTIVE' => '0'
		);
		switch ($type) {
			case 'keluarga':
				$this->db->where('ID_KELUARGA', $id);
				$this->db->update($this->t_keluarga, $posted_fields);
				break;

			case 'harta_tidak_bergerak':
				$this->db->where('ID', $id);
				$this->db->update($this->t_htb, $posted_fields);
				break;

			case 'harta_bergerak':
				$this->db->where('ID', $id);
				$this->db->update($this->t_harta_bergerak, $posted_fields);
				break;

			case 'harta_bergerak_lain':
				$this->db->where('ID', $id);
				$this->db->update($this->t_harta_bergerak_lain, $posted_fields);
				break;

			case 'harta_surat_berharga':
				$this->db->where('ID', $id);
				$this->db->update($this->t_harta_surat_berharga, $posted_fields);
				break;

			case 'harta_kas':
				$this->db->where('ID', $id);
				$this->db->update($this->t_harta_kas, $posted_fields);
				break;

			case 'harta_lainnya':
				$this->db->where('ID', $id);
				$this->db->update($this->t_harta_lainnya, $posted_fields);
				break;

			case 'hutang':
				$this->db->where('ID_HUTANG', $id);
				$this->db->update($this->t_hutang, $posted_fields);
				break;

			case 'fasilitas':
			    $this->db->where('ID', $id);
			    $this->db->update($this->t_fasilitas, $posted_fields);
			    break;

			case 'lampiran_bak':
				$this->db->where('ID', $id);
				$this->db->update($this->t_lampiran, $posted_fields);
				break;

			default:
				$data = false;
				break;
		}

		if ($this->db->affected_rows() > 0) {
		 	return true;
		}
		else{
			return false;
		}
	}

	public function set_klarifikasi_as_final($id, $no_st, $posted_fields)
	{
		$this->db->where('id_lhkpn', $id);
		$this->db->where('nomor_surat_tugas', $no_st);
		$this->db->update($this->t_audit, $posted_fields);
		if ($this->db->affected_rows() > 0) {
		 	return true;
		}
		else{
			return false;
		}
	}

	public function get_data_hibah($id_lhkpn) {
        $result = $this->db->query("
	      SELECT
	        'Tanah / Bangunan' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT('Tanah/Bangunan , Atas Nama ',ATAS_NAMA,' dengan luas tanah ',LUAS_TANAH,' dan luas bangunan ',LUAS_BANGUNAN,' dengan bukti berupa ',
	        C.JENIS_BUKTI,' dengan nomor bukti ',NOMOR_BUKTI) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_TIDAK_BERGERAK A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_BUKTI C ON A.JENIS_BUKTI=C.ID_JENIS_BUKTI
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Mesin / Alat Transport' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT('Sebuah ',C.NAMA,' , Atas Nama ',ATAS_NAMA,' , merek ',MEREK,' dengan nomor registrasi ',NOPOL_REGISTRASI,' dan nomor bukti ',NOMOR_BUKTI) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_BERGERAK A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Harta bergerak' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT(
	          CASE
	            WHEN KODE_JENIS LIKE '%1%' THEN 'Perabotan Rumah Tangga'
	            WHEN KODE_JENIS LIKE '%2%' THEN 'Barang Elektronik'
	            WHEN KODE_JENIS LIKE '%3%' THEN 'Perhiasan & Logam / Batu Mulia'
	            WHEN KODE_JENIS LIKE '%4%' THEN 'Persediaan'
	            WHEN KODE_JENIS LIKE '%5%' THEN 'Harta Bergerak Lainnya'
	          END,
	          ' bernama ',A.NAMA,' , Atas nama ',ATAS_NAMA,' dengan jumlah ',JUMLAH,' ',SATUAN) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_BERGERAK_LAIN A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Surat Berharga' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT(
	          CASE
	            WHEN KODE_JENIS LIKE '%1%' THEN 'Penyertaan Modal pada Badan Hukum'
	            WHEN KODE_JENIS LIKE '%2%' THEN 'Investasi'
	          END,
	          ', Atas nama ',ATAS_NAMA,' berupa surat ',NAMA_SURAT_BERHARGA,' dengan jumlah ',JUMLAH,' ',SATUAN) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_SURAT_BERHARGA A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Kas / Setara Kas' as kode,
	        '' as tgl,
	        CONCAT('KAS berupa ',
	          CASE
	            WHEN KODE_JENIS LIKE '%1%' THEN 'Uang Tunai'
	            WHEN KODE_JENIS LIKE '%2%' THEN 'Deposite'
	            WHEN KODE_JENIS LIKE '%3%' THEN 'Giro'
	            WHEN KODE_JENIS LIKE '%4%' THEN 'Tabungan'
	            WHEN KODE_JENIS LIKE '%5%' THEN 'Lainnya'
	          END,
	          ', Atas nama ',ATAS_NAMA_REKENING,' pada bank ',NAMA_BANK,' dengan nomor rekening ',NOMOR_REKENING) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_KAS A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_KAS B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Harta Lainnya' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT('Harta lain berupa ',
	          CASE
	            WHEN KODE_JENIS LIKE '%1%' THEN 'Piutang'
	            WHEN KODE_JENIS LIKE '%2%' THEN 'Kerjasama Usaha yang Tidak Berbadan Hukum'
	            WHEN KODE_JENIS LIKE '%3%' THEN 'Hak Kekayaan Intelektual'
	            WHEN KODE_JENIS LIKE '%4%' THEN 'Sewa Jangaka Panjang Dibayar Dimuka'
	            WHEN KODE_JENIS LIKE '%5%' THEN 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan'
	          END,
	        ' dengan nama harta ',A.NAMA,' atas nama ',ATAS_NAMA) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_LAINNYA A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'")->result();

        return $result;
    }

    public function get_data_lampiran_by_id($id)
    {
    	$this->db->where('ID_LHKPN', $id);
    	$this->db->where('IS_ACTIVE', 1);
    	$data = $this->db->get($this->t_lampiran);
    	return $data->result();
    }

    public function count_data_by_id($id)
    {
    	$this->db->where('ID_LHKPN', $id);
    	$this->db->where('IS_ACTIVE', 1);
    	$data = $this->db->get($this->t_lampiran);
    	return $data->num_rows();
    }

    public function get_nik_by_id_pn($id_pn)
    {
    	$this->db->where('ID_PN', $id_pn);
    	$data = $this->db->get('t_pn');
		if ($data->num_rows() == 1) {
			$nik = $data->row();
			return $nik->NIK;
		}
		else{
			return false;
		}
	}

	public function cek_data_lama($id,$table)
	{
		$where_fields = array(
            'ID_LHKPN' => $id,
            'ID_HARTA !=' => '',
            'STATUS' => 3,
            'IS_ACTIVE' => 1,
        );
		
		$data = $this->db->get_where($table, $where_fields);
		
		return $data->num_rows();

	}

	public function delete_by_id($id, $type)
	{
	    switch ($type) {
	        case 'jabatan':
	            $this->db->where('ID', $id);
	            $this->db->delete($this->t_jabatan);
				break;
			case 'keluarga':
				$this->db->where('ID_KELUARGA', $id);
				$this->db->delete($this->t_keluarga);
				break;
			case 'fasilitas':
			    $this->db->where('ID', $id);
			    $this->db->delete($this->t_fasilitas);
			    break;
	        default:
	            $data = false;
	            break;
	    }

	    if ($this->db->affected_rows() > 0) {
	        return true;
	    }
	    else{
	        return false;
	    }
	}




}
