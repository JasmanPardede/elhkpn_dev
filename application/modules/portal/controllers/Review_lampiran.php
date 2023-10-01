<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_lampiran extends CI_Controller{

	function __Construct(){
		parent::__Construct();
		call_user_func('ng::islogin');
	}

	function index(){
		$options = array();
		$this->load->view('portal/filing/review_lampiran',$options);
	}


	function grid_pelepasan($ID_LHKPN){
		$iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->where('ID_LHKPN',$ID_LHKPN);
        if($cari){
        	$this->db->like('JENIS_PENJUALAN',$cari);
        	$this->db->or_like('TANGGAL_TRANSAKSI',$cari);
        	$this->db->or_like('URAIAN_HARTA',$cari);
        	$this->db->or_like('ATAS_NAMA',$cari);
        	$this->db->or_like('NILAI_PENJUALAN',$cari);
        	$this->db->or_like('NAMA_PIHAK_KEDUA',$cari);
        	$this->db->or_like('ALAMAT_PIHAK_KEDUA',$cari);
        }
        $this->db->limit($iDisplayLength,$iDisplayStart);
		$this->db->order_by('ID_PENJUALAN','DESC');
		$data = $this->db->get('t_lhkpn_pelepasan_manual')->result();
		if($data){
			foreach ($data as $list){
				
			}
		}
		$jml = $this->db->get('t_lhkpn_pelepasan_manual')->num_rows();
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

	function atas_nama($index){
		$data = array();
		$data[1] = 'PN YANG BERSANGKUTAN'; 
		$data[2] = 'PASANGAN / ANAK'; 
		$data[3] = 'LAINNYA'; 
		return $data;exit;
	}
	
	function jenis_bukti($relasi,$id){
		$result = null;
		$this->db->where('m_jenis_bukti.ID_JENIS_BUKTI',$id);
		$data = $this->db->get('m_jenis_bukti')->row();
		if($data){
			$result = $data->JENIS_BUKTI;
		}
		return $result;	exit;
		
	}

	function jenis_harta($relasi,$id){
		$result = null;
		$this->db->where('m_jenis_harta.ID_JENIS_HARTA',$id);
		$data = $this->db->get('m_jenis_harta')->row();
		if($data){
			$result = $data->NAMA;
		}
		return $result;	exit;

	}

	function pk($TABLE){
		$sql = "SHOW KEYS FROM ".$TABLE." WHERE Key_name = 'PRIMARY'";
		$data = $this->db->query($sql)->result_array();
		return $data[0]['Column_name'];exit;
	}


	function get_uraian($JENIS,$TABLE,$ID){
		$html = null;
		$PK = $this->pk($TABLE);
		$this->db->where($PK,$ID);
		$list = $this->db->get($TABLE)->row();
		if (strlen($list->NAMA_BANK) >= 32){
			$decrypt_namabank = encrypt_username($list->NAMA_BANK,'d');
		} else {
			$decrypt_namabank = $list->NAMA_BANK;
		}
		if($TABLE=='t_lhkpn_harta_bergerak'){
			$html = "
				<table class='table table-child table-condensed'>
					 <tr>
					    <td><b>Jenis</b></td>
                        <td>:</td>
                        <td>".$JENIS."</td>
					 </tr>
					 <tr>
					    <td><b>Merek</b></td>
                        <td>:</td>
                        <td>".$list->MEREK."</td>
					 </tr>
					 <tr>
					    <td><b>Jenis</b></td>
                        <td>:</td>
                        <td>".$list->MODEL."</td>
					 </tr>
					 <tr>
					    <td><b>Tahun Pembuatan</b></td>
                        <td>:</td>
                        <td>".$list->TAHUN_PEMBUATAN."</td>
					 </tr>
					 <tr>
					    <td><b>No Pol / Registrasi</b></td>
                        <td>:</td>
                        <td>".$list->NOPOL_REGISTRASI."</td>
					 </tr>
				</table>
			";
		}else if($TABLE=='t_lhkpn_harta_bergerak_lain'){
			$html = "
				<table class='table table-child table-condensed'>
					 <tr>
					    <td><b>Jenis</b></td>
	                    <td>:</td>
	                    <td>".$JENIS."</td>
					 </tr>
					 <tr>
					    <td><b>Jumlah</b></td>
	                    <td>:</td>
	                    <td>".$list->JUMLAH."</td>
					 </tr>
					 <tr>
					    <td><b>Satuan</b></td>
	                    <td>:</td>
	                    <td>".$list->SATUAN."</td>
					 </tr>
					 <tr>
					    <td><b>Ket Lainnya</b></td>
	                    <td>:</td>
	                    <td>".$list->KETERANGAN."</td>
					 </tr>
				</table>
			";
		}else if($TABLE=='t_lhkpn_harta_kas'){
			$html = "
				<table class='table table-child table-condensed'>
					 <tr>
					    <td><b>Jenis</b></td>
	                    <td>:</td>
	                    <td>".$JENIS."</td>
					 </tr>
					 <tr>
					    <td><b>Keterangan</b></td>
	                    <td>:</td>
	                   <td>".$list->KETERANGAN."</td>
					 </tr>
					 <tr>
					    <td><b>Nama Bank / Lembaga</b></td>
	                    <td>:</td>
	                   <td>".$decrypt_namabank."</td>
					 </tr>
				</table>
			";
		}else if($TABLE=='t_lhkpn_harta_lainnya'){
			$html = "
				<table class='table table-child table-condensed'>
					<tr>
					    <td><b>Jenis</b></td>
                        <td>:</td>
                        <td>".$JENIS."</td>
					 </tr>
					 <tr>
					    <td><b>Keterangan</b></td>
                        <td>:</td>
                        <td>".$list->KETERANGAN."</td>
					 </tr>
				</table>
			";
		}else if($TABLE=='t_lhkpn_harta_surat_berharga'){
			$html = "
				<table class='table table-child table-condensed'>
					 <tr>
					    <td><b>Jenis</b></td>
                        <td>:</td>
                        <td>".$JENIS."</td>
					 </tr>
					  <tr>
					    <td><b>Atas Nama</b></td>
                        <td>:</td>
                        <td>".$list->ATAS_NAMA."</td>
					 </tr>
					  <tr>
					    <td><b>Penerbit / Perusahaan</b></td>
                        <td>:</td>
                        <td>".$list->NAMA_PENERBIT."</td>
					 </tr>
					  <tr>
					    <td><b>Custodion / Sekuritas</b></td>
                        <td>:</td>
                         <td>".$list->CUSTODIAN."</td>
					 </tr>
				</table>
			";
		}else{
			$this->db->where('ID',$list->ID_NEGARA);
			$ng = $this->db->get('m_negara')->row();
			if($list->ID_NEGARA=='96'){
				$JALAN = $list->JALAN;
				$KEL = $list->KEL;
				$KEC = $list->KEC;
				$KOTA = $list->KAB_KOT;
				$NEGARA = $ng->NAMA_NEGARA;
				$html = "
					<table class='table table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
	                        <td>:</td>
	                        <td>".$JENIS."</td>
					 	</tr>
                        <tr>
                            <td><b>Jalan / No</b></td>
                            <td>:</td>
                            <td>".$JALAN."</td>
                         </tr>
                         <tr>
                            <td><b>Kel / Desa</b></td>
                            <td>:</td>
                            <td>".$KEL."</td>
                        </tr>
                         <tr>
                            <td><b>Kecamatan</b></td>
                            <td>:</td>
                            <td>".$KEC."</td>
                        </tr>
                         <tr>
                            <td><b>Kab/Kota</b></td>
                            <td>:</td>
                            <td>".$KOTA."</td>
                        </tr>
                         <tr>
                            <td><b>Prov / Negara</b></td>
                            <td>:</td>
                            <td>".$list->PROV.' / '.$NEGARA."</td>
                        </tr>
                        <tr>
	                        <td><b>Tanah</b></td>
	                        <td>:</td>
	                        <td>".number_rupiah($list->LUAS_TANAH)." m <sup>2</sup></td>
                    	 </tr>
	                    <tr>
	                        <td><b>Bangunan</b></td>
	                        <td>:</td>
	                        <td>".number_rupiah($list->LUAS_BANGUNAN)." m <sup>2</sup></td>
	                    </tr>
                    </table>
				";
			}else{
				$JALAN = $list->JALAN;
				$KEL = '';
				$KEC = '';
				$KOTA = '';
				$NEGARA = $ng->NAMA_NEGARA;
				$html = "
				  <table class='table table-child table-condensed'>
				  	  <tr>
						<td><b>Jenis</b></td>
	                    <td>:</td>
	                    <td>".$JENIS."</td>
					  </tr>
				  	  <tr>
                        <td><b>Jalan</b></td>
                        <td>:</td>
                        <td>".$JALAN."</td>
                      </tr>
                       <tr>
                            <td><b>Negara</b></td>
                            <td>:</td>
                            <td>".$NEGARA."</td>
                        </tr>
                        <tr>
	                        <td><b>Tanah</b></td>
	                        <td>:</td>
	                        <td>".number_rupiah($list->LUAS_TANAH)." m <sup>2</sup></td>
                    	 </tr>
	                    <tr>
	                        <td><b>Bangunan</b></td>
	                        <td>:</td>
	                        <td>".number_rupiah($list->LUAS_BANGUNAN)." m <sup>2</sup></td>
	                    </tr>
				  </table>
				";
			}
			
		}

		return $html;exit;
	}

	function grid_pelepasan_now($ID_LHKPN){
		$table = array(
			't_lhkpn_pelepasan_harta_bergerak',
			't_lhkpn_pelepasan_harta_bergerak_lain',
			't_lhkpn_pelepasan_harta_kas',
			't_lhkpn_pelepasan_harta_lainnya',
			't_lhkpn_pelepasan_harta_surat_berharga',
			't_lhkpn_pelepasan_harta_tidak_bergerak'
		);
		$no = 1;
		foreach($table as $t){
			$PK = $this->pk($t);
			$relasi = str_replace('t_lhkpn_pelepasan_', 't_lhkpn_', $t);
			$this->db->select('
				 '.$t.'.*, 
				 '.$relasi.'.*,
				 '.$t.'.NAMA AS NAMA_PIHAK, 
				 '.$t.'.URAIAN_HARTA AS KETERANGAN, 
				 m_jenis_pelepasan_harta.JENIS_PELEPASAN_HARTA AS JMA,
				 '.$t.'.ALAMAT AS ALAMAT_PIHAK '
			);
			$this->db->where($t.'.ID_LHKPN',$ID_LHKPN);
			$this->db->join($relasi,$relasi.'.ID = '.$t.'.ID_HARTA');
			$this->db->join('m_jenis_pelepasan_harta','m_jenis_pelepasan_harta.ID = '.$t.'.JENIS_PELEPASAN_HARTA');
			$data = $this->db->get($t)->result();
			
			if($data){
				foreach($data as $row){
					if($t=='t_lhkpn_pelepasan_harta_tidak_bergerak'){
						$name = $this->jenis_bukti($relasi,$row->JENIS_BUKTI);
					}else{
						$name = $this->jenis_harta($relasi,$row->KODE_JENIS);
					}

					echo "
						<tr>
							<td>".$no."</td>
							<td>
								<table class='table table-child table-condensed'>
									<tr>
			                            <td><b>Jenis</b></td>
			                            <td>:</td>
			                            <td>".$row->JMA."</td>
			                         </tr>
			                         <tr>
			                            <td><b>Keterangan</b></td>
			                            <td>:</td>
			                            <td>".$row->KETERANGAN."</td>
			                         </tr>
								</table>
							</td>
							<td>".$this->get_uraian($name,$relasi,$row->$PK)."</td>
							<td>Rp. ".number_rupiah($row->NILAI_PELEPASAN)."</td>
							<td>
								<table class='table table-child table-condensed'>
									<tr>
			                            <td><b>Nama</b></td>
			                            <td>:</td>
			                            <td>".$row->NAMA_PIHAK."</td>
			                         </tr>
			                         <tr>
			                            <td><b>Alamat</b></td>
			                            <td>:</td>
			                            <td>".$row->ALAMAT_PIHAK."</td>
			                         </tr>
								</table>
							</td>
						</tr>
					";
					$no++;
				}
			}
		}
		exit;
	}

	function grid_hibah($ID_LHKPN){
		$data = array();
		$data[0] = $this->harta_bergerak($ID_LHKPN);
		$data[1] = $this->harta_bergerak_lain($ID_LHKPN);
		$data[2] = $this->harta_kas($ID_LHKPN);
		$data[3] = $this->harta_lainnya($ID_LHKPN);
		$data[4] = $this->harta_surat_berharga($ID_LHKPN);
		$data[5] = $this->harta_tidak_bergerak($ID_LHKPN);
		$table = array(
			't_lhkpn_harta_bergerak',
			't_lhkpn_harta_bergerak_lain',
			't_lhkpn_harta_kas',
			't_lhkpn_harta_lainnya',
			't_lhkpn_harta_surat_berharga',
			't_lhkpn_harta_tidak_bergerak'
		);
		$no = 1;
		$i = 0;
		foreach($data as $row){
			foreach($row as $rs){
				echo "
					<tr>
						<td>".$no."</td>
						<td>
							<table class='table table-child table-condensed'>
								<tr>
		                            <td><b>Jenis</b></td>
		                            <td>:</td>
		                            <td>".$rs->ASAL_USUL."</td>
		                         </tr>
		                         <tr>
		                            <td><b>Keterangan</b></td>
		                            <td>:</td>
		                            <td>".$rs->KETERANGAN."</td>
		                         </tr>
							</table>
						</td>
						<td>".$this->get_uraian($rs->JENIS_HARTA_NAMA,$table[$i],$rs->PK)."</td>
						<td>Rp. ".number_rupiah($rs->NILAI)."</td>
						<td>
							<table class='table table-child table-condensed'>
								<tr>
		                            <td><b>Nama</b></td>
		                            <td>:</td>
		                            <td>".$rs->NAMA2."</td>
		                         </tr>
		                         <tr>
		                            <td><b>Alamat</b></td>
		                            <td>:</td>
		                            <td>".$rs->ALAMAT2."</td>
		                         </tr>
							</table>
						</td>
					</tr>
				";
				$no++;
			}
			$i++;
		}
		exit;
	}

	function harta_bergerak($ID_LHKPN){
		$this->db->select('
			t_lhkpn_harta_bergerak.ID AS PK,
			m_jenis_harta.NAMA AS JENIS_HARTA_NAMA,
			t_lhkpn_asal_usul_pelepasan_harta_bergerak.URAIAN_HARTA AS KETERANGAN,
			CONCAT(t_lhkpn_harta_bergerak.MEREK," ",t_lhkpn_harta_bergerak.MODEL) AS NAMA,
			t_lhkpn_asal_usul_pelepasan_harta_bergerak.NILAI_PELEPASAN AS NILAI,
			t_lhkpn_asal_usul_pelepasan_harta_bergerak.NAMA AS NAMA2,
			t_lhkpn_asal_usul_pelepasan_harta_bergerak.ALAMAT AS ALAMAT2,
			m_asal_usul.ASAL_USUL AS ASAL_USUL
		',FALSE);
		$this->db->where('t_lhkpn_harta_bergerak.ID_LHKPN',$ID_LHKPN);
		$this->db->where('t_lhkpn_harta_bergerak.IS_PELEPASAN','0');
		$this->db->where('t_lhkpn_harta_bergerak.IS_ACTIVE','1');
		$this->db->where('t_lhkpn_harta_bergerak.ASAL_USUL !=','1');
		$this->db->where('t_lhkpn_harta_bergerak.ID_HARTA IS NULL');
		$this->db->join('t_lhkpn_asal_usul_pelepasan_harta_bergerak','t_lhkpn_asal_usul_pelepasan_harta_bergerak.ID_HARTA = t_lhkpn_harta_bergerak.ID','LEFT');
		$this->db->join('m_asal_usul','m_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_bergerak.ID_ASAL_USUL','LEFT');
		$this->db->join('m_jenis_harta','m_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_bergerak.KODE_JENIS','LEFT');
		$this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_harta_bergerak.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
                $data = $this->db->get('t_lhkpn_harta_bergerak')->result();
		return $data;exit;
	}

	function harta_bergerak_lain($ID_LHKPN){
		$this->db->select('
			t_lhkpn_harta_bergerak_lain.ID AS PK,
			m_jenis_harta.NAMA AS JENIS_HARTA_NAMA,
			t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain.URAIAN_HARTA AS KETERANGAN,
			m_jenis_harta.NAMA AS NAMA,
			t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain.NILAI_PELEPASAN AS NILAI,
			t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain.NAMA AS NAMA2,
			t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain.ALAMAT AS ALAMAT2,
			m_asal_usul.ASAL_USUL AS ASAL_USUL
		',FALSE);
		$this->db->where('t_lhkpn_harta_bergerak_lain.ID_LHKPN',$ID_LHKPN);
		$this->db->where('t_lhkpn_harta_bergerak_lain.IS_PELEPASAN','0');
		$this->db->where('t_lhkpn_harta_bergerak_lain.IS_ACTIVE','1');
		$this->db->where('t_lhkpn_harta_bergerak_lain.ID_HARTA IS NULL');
		$this->db->where('t_lhkpn_harta_bergerak_lain.ASAL_USUL !=','1');
		$this->db->join('t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain','t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain.ID_HARTA = t_lhkpn_harta_bergerak_lain.ID','LEFT');
		$this->db->join('m_asal_usul','m_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain.ID_ASAL_USUL','LEFT');
		$this->db->join('m_jenis_harta','m_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_bergerak_lain.KODE_JENIS','LEFT');
                $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_harta_bergerak_lain.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
		$data = $this->db->get('t_lhkpn_harta_bergerak_lain')->result();
		return $data;exit;
	}

	function harta_kas($ID_LHKPN){
		$this->db->select('
			t_lhkpn_harta_kas.ID AS PK,
			m_jenis_harta.NAMA AS JENIS_HARTA_NAMA,
			t_lhkpn_asal_usul_pelepasan_kas.URAIAN_HARTA AS KETERANGAN,
			t_lhkpn_harta_kas.NAMA_BANK AS NAMA,
			t_lhkpn_asal_usul_pelepasan_kas.NILAI_PELEPASAN AS NILAI,
			t_lhkpn_asal_usul_pelepasan_kas.NAMA AS NAMA2,
			t_lhkpn_asal_usul_pelepasan_kas.ALAMAT AS ALAMAT2,
			m_asal_usul.ASAL_USUL AS ASAL_USUL
		',FALSE);
		$this->db->where('t_lhkpn_harta_kas.ID_LHKPN',$ID_LHKPN);
		$this->db->where('t_lhkpn_harta_kas.IS_PELEPASAN','0');
		$this->db->where('t_lhkpn_harta_kas.IS_ACTIVE','1');
		$this->db->where('t_lhkpn_harta_kas.ASAL_USUL !=','1');
		$this->db->where('t_lhkpn_harta_kas.ID_HARTA IS NULL');
		$this->db->join('t_lhkpn_asal_usul_pelepasan_kas','t_lhkpn_asal_usul_pelepasan_kas.ID_HARTA = t_lhkpn_harta_kas.ID','LEFT');
		$this->db->join('m_asal_usul','m_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_kas.ID_ASAL_USUL','LEFT');
		$this->db->join('m_jenis_harta','m_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_kas.KODE_JENIS','LEFT');
                $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_harta_kas.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
		$data = $this->db->get('t_lhkpn_harta_kas')->result();
		return $data;exit;
	}

	function harta_lainnya($ID_LHKPN){
		$this->db->select('
			t_lhkpn_harta_lainnya.ID AS PK,
			m_jenis_harta.NAMA AS JENIS_HARTA_NAMA,
			t_lhkpn_asal_usul_pelepasan_harta_lainnya.URAIAN_HARTA AS KETERANGAN,
			m_jenis_harta.NAMA AS NAMA,
			t_lhkpn_asal_usul_pelepasan_harta_lainnya.NILAI_PELEPASAN AS NILAI,
			t_lhkpn_asal_usul_pelepasan_harta_lainnya.NAMA AS NAMA2,
			t_lhkpn_asal_usul_pelepasan_harta_lainnya.ALAMAT AS ALAMAT2,
			m_asal_usul.ASAL_USUL AS ASAL_USUL
		',FALSE);
		$this->db->where('t_lhkpn_harta_lainnya.ID_LHKPN',$ID_LHKPN);
		$this->db->where('t_lhkpn_harta_lainnya.IS_PELEPASAN','0');
		$this->db->where('t_lhkpn_harta_lainnya.IS_ACTIVE','1');
		$this->db->where('t_lhkpn_harta_lainnya.ASAL_USUL !=','1');
		$this->db->where('t_lhkpn_harta_lainnya.ID_HARTA IS NULL');
		$this->db->join('t_lhkpn_asal_usul_pelepasan_harta_lainnya','t_lhkpn_asal_usul_pelepasan_harta_lainnya.ID_HARTA = t_lhkpn_harta_lainnya.ID','LEFT');
		$this->db->join('m_asal_usul','m_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_lainnya.ID_ASAL_USUL','LEFT');
		$this->db->join('m_jenis_harta','m_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_lainnya.KODE_JENIS','LEFT');
                $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_harta_lainnya.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
		$data = $this->db->get('t_lhkpn_harta_lainnya')->result();
		return $data;exit;
	}

	function harta_surat_berharga($ID_LHKPN){
		$this->db->select('
			t_lhkpn_harta_surat_berharga.ID AS PK,
			m_jenis_harta.NAMA AS JENIS_HARTA_NAMA,
			t_lhkpn_asal_usul_pelepasan_surat_berharga.URAIAN_HARTA AS KETERANGAN,
			m_jenis_harta.NAMA AS NAMA,
			t_lhkpn_asal_usul_pelepasan_surat_berharga.NILAI_PELEPASAN AS NILAI,
			t_lhkpn_asal_usul_pelepasan_surat_berharga.NAMA AS NAMA2,
			t_lhkpn_asal_usul_pelepasan_surat_berharga.ALAMAT AS ALAMAT2,
			m_asal_usul.ASAL_USUL AS ASAL_USUL
		',FALSE);
		$this->db->where('t_lhkpn_harta_surat_berharga.ID_LHKPN',$ID_LHKPN);
		$this->db->where('t_lhkpn_harta_surat_berharga.IS_PELEPASAN','0');
		$this->db->where('t_lhkpn_harta_surat_berharga.IS_ACTIVE','1');
		$this->db->where('t_lhkpn_harta_surat_berharga.ASAL_USUL !=','1');
		$this->db->where('t_lhkpn_harta_surat_berharga.ID_HARTA IS NULL');
		$this->db->join('t_lhkpn_asal_usul_pelepasan_surat_berharga','t_lhkpn_asal_usul_pelepasan_surat_berharga.ID_HARTA = t_lhkpn_harta_surat_berharga.ID','LEFT');
		$this->db->join('m_asal_usul','m_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_surat_berharga.ID_ASAL_USUL','LEFT');
		$this->db->join('m_jenis_harta','m_jenis_harta.ID_JENIS_HARTA = t_lhkpn_harta_surat_berharga.KODE_JENIS','LEFT');
                $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_harta_surat_berharga.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
		$data = $this->db->get('t_lhkpn_harta_surat_berharga')->result();
		return $data;exit;
	}

	function harta_tidak_bergerak($ID_LHKPN){
		$this->db->select('
			t_lhkpn_harta_tidak_bergerak.ID AS PK,
			m_jenis_bukti.JENIS_BUKTI AS JENIS,
			m_jenis_bukti.JENIS_BUKTI AS JENIS_HARTA_NAMA,
			t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.URAIAN_HARTA AS KETERANGAN,
			t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.NILAI_PELEPASAN AS NILAI,
			t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.NAMA AS NAMA2,
			t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.ALAMAT AS ALAMAT2,
			m_asal_usul.ASAL_USUL AS ASAL_USUL
		',FALSE);
		$this->db->where('t_lhkpn_harta_tidak_bergerak.ID_LHKPN',$ID_LHKPN);
		$this->db->where('t_lhkpn_harta_tidak_bergerak.IS_PELEPASAN','0');
		$this->db->where('t_lhkpn_harta_tidak_bergerak.IS_ACTIVE','1');
		$this->db->where('t_lhkpn_harta_tidak_bergerak.ASAL_USUL !=','1');
		$this->db->where('t_lhkpn_harta_tidak_bergerak.ID_HARTA IS NULL');
		$this->db->join('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak','t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.ID_HARTA = t_lhkpn_harta_tidak_bergerak.ID','LEFT');
		$this->db->join('m_asal_usul','m_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.ID_ASAL_USUL','LEFT');
		$this->db->join('m_jenis_bukti','m_jenis_bukti.ID_JENIS_BUKTI = t_lhkpn_harta_tidak_bergerak.JENIS_BUKTI','LEFT');
                $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_harta_tidak_bergerak.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
		$data = $this->db->get('t_lhkpn_harta_tidak_bergerak')->result();
		return $data;exit;
	}

}