<?php

/**
 * Model Verifikasi data Excel
 *
 * @author Wahyu Widodo <whywdd@yahoo.com>
 * @version
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Verification_model extends CI_Model {

	protected $m_jabatan = 'm_jabatan';
	protected $t_jabatan = 't_lhkpn_jabatan';
	protected $t_data_pribadi = 't_lhkpn_data_pribadi';
	protected $t_data_keluarga = 't_lhkpn_keluarga';
	protected $t_harta_tidak_bergerak = 't_lhkpn_harta_tidak_bergerak';
	protected $t_harta_bergerak = 't_lhkpn_harta_bergerak';
	protected $t_harta_bergerak_lain = 't_lhkpn_harta_bergerak_lain';
	protected $t_harta_surat_berharga = 't_lhkpn_harta_surat_berharga';
	protected $t_harta_kas = 't_lhkpn_harta_kas';
	protected $t_harta_lainnya = 't_lhkpn_harta_lainnya';
	protected $t_hutang = 't_lhkpn_hutang';
	protected $t_penerimaan = 't_lhkpn_penerimaan_kas';
	protected $t_penerimaan_2 = 't_lhkpn_penerimaan_kas2';
	protected $t_pengeluaran = 't_lhkpn_pengeluaran_kas';
	protected $t_pengeluaran_2 = 't_lhkpn_pengeluaran_kas2';
	protected $t_fasilitas = 't_lhkpn_fasilitas';
	protected $m_inst_satker = 'm_inst_satker';
	protected $m_unit_kerja = 'm_unit_kerja';
	protected $m_sub_unit_kerja = 'm_sub_unit_kerja';
	protected $m_area_prov = 'm_area_prov';
	protected $t_lhkpn = 't_lhkpn';
	protected $t_pn = 't_pn';
	protected $m_negara = 'm_negara';


	function __construct()
	{
		parent::__construct();
	}

	public function get_data_pribadi_by_id_lhkpn($id_lhkpn)
	{
		$this->db->where('ID_LHKPN', $id_lhkpn);
		$data = $this->db->get($this->t_data_pribadi);
		if ($data->num_rows() == 1) {
			return $data->row();
		}
		else{
			return false;
		}
	}

	public function get_jabatan_by_id_lhkpn($id_lhkpn)
	{
		$this->db->where('ID_LHKPN', $id_lhkpn);
		$this->db->where('IS_PRIMARY', '1');
		$data = $this->db->get($this->t_jabatan);
		if ($data->num_rows() == 1) {
			return $data->row();
		}
		else{
			return null;
		}
	}

	public function get_m_jabatan_by_id_jabatan($id_jabatan)
	{
		$this->db->where('ID_JABATAN', $id_jabatan);
		$data = $this->db->get($this->m_jabatan);
		return $data->row();
	}

	public function update_data_keluarga($posted_fields, $id)
	{
		$this->db->where('ID_KELUARGA', $id);
		$this->db->update($this->t_data_keluarga, $posted_fields);

		if ($this->db->affected_rows() > 0) {
		 	return true;
		}
		else{
			return false;
		}
	}

	public function insert_data_keluarga($posted_fields)
	{
		$this->db->insert($this->t_data_keluarga, $posted_fields);
		if ($this->db->affected_rows() > 0) {
		 	return true;
		}
		else{
			return false;
		}
	}

	public function get_keluarga_by_id_lhkpn($id_lhkpn)
	{
		$this->db->select('t_lhkpn_keluarga.*,t_lhkpn.tgl_kirim_final,t_lhkpn.tgl_lapor,TIMESTAMPDIFF(YEAR,t_lhkpn_keluarga.tanggal_lahir,t_lhkpn.tgl_lapor) AS umur_lapor',false);
		$this->db->where('t_lhkpn_keluarga.ID_LHKPN', $id_lhkpn);
		$this->db->where('t_lhkpn_keluarga.IS_ACTIVE', '1');
		$this->db->join($this->t_lhkpn, 't_lhkpn_keluarga.id_lhkpn = t_lhkpn.id_lhkpn');
		$data = $this->db->get($this->t_data_keluarga);
		if ($data->num_rows() > 0) {
			return $data->result();
		}
		else{
			return null;
		}
	}

	public function penugasan_verifikasi_cetak($cari = NULL)
	{
		$my_select = "T_LHKPN_DATA_PRIBADI.NAMA_LENGKAP,
                                        T_LHKPN_DATA_PRIBADI.NIK,
					M_JABATAN.NAMA_JABATAN,
					M_INST_SATKER.INST_NAMA,
					M_UNIT_KERJA.UK_NAMA,
					M_ESELON.ESELON,
					-- T_VERIFICATION.*,
					-- T_PN.*,
					T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.*,
					T_LHKPN.ID_LHKPN,
					T_LHKPN.ALASAN,
					T_LHKPN.entry_via,
					T_LHKPN.ID_PN,
					T_LHKPN.IS_ACTIVE,
					T_LHKPN.JENIS_LAPORAN,
					T_LHKPN.STATUS,
					T_LHKPN.tgl_kirim,
					T_LHKPN.tgl_kirim_final,
					T_LHKPN.tgl_lapor,
					T_LHKPN.USERNAME_ENTRI,
					T_LHKPN.back_to_draft,
					IF(ISNULL(RANGKAP.ID_LHKPN), 'TIDAK', 'YA') AS RANGKAP";

		if ($cari->status_lhkpn_sebelumnya !== '0'){
                    $my_select .= " ,(SELECT T_LHKPN_2.STATUS FROM T_LHKPN T_LHKPN_2 WHERE T_LHKPN_2.ID_LHKPN = T_LHKPN.ID_LHKPN_PREV) AS STATUS_LHKPN_SEBELUMNYA";
                }
	
		if($cari->stat >= '2') {
			$my_select .= ", DATE_FORMAT(HISTORY.DATE_INSERT, '%Y-%m-%d') AS DATE_INSERT";
			// $my_select .= ", DATE_FORMAT(DATE_ADD(HISTORY.DATE_INSERT, INTERVAL 14 DAY), '%d-%m-%Y') AS DUE_DATE";
		}
        
		$my_from = " FROM T_LHKPN ";
		$my_join = "LEFT JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_LHKPN = `T_LHKPN`.ID_LHKPN AND T_LHKPN_JABATAN.`IS_PRIMARY` = '1'
                    LEFT JOIN (SELECT ID_LHKPN FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN HAVING COUNT(ID_LHKPN) > 1) AS RANGKAP ON RANGKAP.ID_LHKPN = T_LHKPN.ID_LHKPN
                    LEFT JOIN T_LHKPN_DATA_PRIBADI ON T_LHKPN_DATA_PRIBADI.ID_LHKPN = `T_LHKPN`.ID_LHKPN
                    LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                    LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD
                    LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID
                    LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = M_JABATAN.KODE_ESELON

                    /*LEFT JOIN `T_VERIFICATION`
                      ON `T_LHKPN`.`ID_LHKPN` = `T_VERIFICATION`.`ID_LHKPN`
                    JOIN `T_PN`
					  ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN`*/";
		$my_join .= "LEFT JOIN `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`
					  ON `T_LHKPN`.`ID_LHKPN` = `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`.`ID_LHKPN`";
					  
		if($cari->stat >= '2') {
			$my_join .= "LEFT JOIN (SELECT MAX(DATE_INSERT) AS DATE_INSERT, ID_STATUS, ID_LHKPN FROM t_lhkpn_status_history GROUP BY ID_LHKPN, ID_STATUS) AS HISTORY 
						ON HISTORY.ID_LHKPN = T_LHKPN.ID_LHKPN AND HISTORY.ID_STATUS = 7";
		}	

		if ($cari->entry == '0' || $cari->entry == '1') {
            $my_where = " T_LHKPN.ENTRY_VIA = '" . $cari->entry . "' ";
        } else {
            $my_where = " T_LHKPN.entry_via <> '2' ";
		}
		$my_where .= "AND T_LHKPN.STATUS IN ('1','2')";
	    $my_where .= "AND T_LHKPN.JENIS_LAPORAN <> '5'";
		$my_where .= "AND T_LHKPN.IS_ACTIVE = '1'";

		if ($cari->status_lhkpn != ''){
			if($cari->status_lhkpn == 3){ //jika status lhkpn  = sudah diperbaiki
                $my_where .= "AND T_LHKPN.STATUS = '1' ";
                $my_where .= "AND T_LHKPN.ALASAN IS NOT NULL ";
            }else if($cari->status_lhkpn == 1){ //jika status lhkpn = proses verifikasi
                $my_where .= "AND T_LHKPN.STATUS = '" . $cari->status_lhkpn . "'";
                $my_where .= "AND T_LHKPN.ALASAN IS NULL ";
            }else{
                $my_where .= "AND T_LHKPN.STATUS = '" . $cari->status_lhkpn . "'";
			}
		}

		if ($cari->tahun) {
            $my_where .= "AND YEAR(TGL_LAPOR) = '" . $cari->tahun . "'";
		}
		
		if ($cari->tahunKirimFinal) {
            $my_where .= "AND YEAR(TGL_KIRIM_FINAL) = '" . $cari->tahunKirimFinal . "'";
		}

		if ($cari->lembaga) {
            $my_where .= "AND M_INST_SATKER.INST_SATKERKD = '" . $cari->lembaga . "'";
        }

        if ($cari->uk) {
            $my_where .= "AND M_UNIT_KERJA.UK_ID = '" . $cari->uk . "'";
        }

        if ($cari->eselon) {
            $my_where .= "AND ID_ESELON = '" . $cari->eselon . "'";
        }

        if ($cari->uu != '' ) {
            $my_where .= "AND IS_UU = '" . $cari->uu . "'";
		}
		
		if ($cari->aktifasi != '' ) {
            $my_where .= "AND T_PN.IS_FORMULIR_EFILLING = '" . $cari->aktifasi . "'";
        }
        
        if ($cari->nama) {
            $my_where .= "AND (T_LHKPN_DATA_PRIBADI.NAMA_LENGKAP LIKE '%" . $cari->nama . "%'  OR T_LHKPN_DATA_PRIBADI.EMAIL_PRIBADI LIKE  '%" . $cari->nama . "%' OR T_LHKPN_DATA_PRIBADI.NIK LIKE '%" . $cari->nama . "%')";
        }

        if ($cari->rangkap) {
            $my_where .= "AND IF(ISNULL(RANGKAP.ID_LHKPN), 'TIDAK', 'YA') = '" . $cari->rangkap . "'";
		}


		if ($cari->petugas) {
            if ($cari->petugas == '') {
                $my_where .= "AND  T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.USERNAME IS NULL ";
            } else {
                $my_where .= "AND  T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.USERNAME ='" . $cari->petugas . "' ";
            }
		}
		
		$my_having = '';
		$my_where_find = '';

        if ($cari->stat) {
            if ($cari->stat == 1) {

                $my_where_find .= " T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.STAT IS NULL ";
            } else if ($cari->stat == 2) {

                $my_where_find .= " T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.STAT = '" . $cari->stat . "' ";
            } else if ($cari->stat == 3) {
                $my_where_find .= " T_LHKPN.STATUS IN ('3', '5') ";
			}
			
			$date_now_to_time = "TO_SECONDS(CURDATE())";
		
			if($cari->belum_ditugaskan != 0){
					
				$tgl_kirim_max5 = "DATE_ADD(T_LHKPN.tgl_kirim_final, INTERVAL 5 DAY)"; 
				$tgl_kirim_max5_to_time = "TO_SECONDS($tgl_kirim_max5)";

				$tgl_kirim_max10 = "DATE_ADD(T_LHKPN.tgl_kirim_final, INTERVAL 10 DAY)"; 
				$tgl_kirim_max10_to_time = "TO_SECONDS($tgl_kirim_max10)";

				if($cari->belum_ditugaskan == 1){ 
					
					$my_where_find .= "AND $date_now_to_time <= $tgl_kirim_max5_to_time ";

				}else if($cari->belum_ditugaskan == 2){ 
						
					$my_where_find .= "AND $date_now_to_time > $tgl_kirim_max5_to_time AND $date_now_to_time <= $tgl_kirim_max10_to_time ";

				}else if($cari->belum_ditugaskan == 3){ 
					
					$my_where_find .= "AND $date_now_to_time > $tgl_kirim_max10_to_time ";

				}

			}

			if($cari->sudah_ditugaskan != 0){

				$my_select .= " ,T_VERIFICATION.*, T_PN.* ";
				$my_join .= " LEFT JOIN `T_VERIFICATION` ON `T_LHKPN`.`ID_LHKPN` = `T_VERIFICATION`.`ID_LHKPN` JOIN `T_PN` ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN`";

				$condition = " AND T_VERIFICATION.IS_ACTIVE  != 1 || T_VERIFICATION.ID IS NULL ";

				$my_where_find .= $condition;

				$tgl_tugas_max3 = "DATE_ADD(T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.TANGGAL_PENUGASAN, INTERVAL 3 DAY)";
				$tgl_tugas_max3_to_time = "TO_SECONDS($tgl_tugas_max3)";
			
				$tgl_tugas_max7 = "DATE_ADD(T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.TANGGAL_PENUGASAN, INTERVAL 7 DAY)";
				$tgl_tugas_max7_to_time = "TO_SECONDS($tgl_tugas_max7)";

				if($cari->sudah_ditugaskan == 1){ 
				
					$my_where_find .= "AND $date_now_to_time <= $tgl_tugas_max3_to_time ";

				}else if($cari->sudah_ditugaskan == 2){

					$my_where_find .= "AND $date_now_to_time > $tgl_tugas_max3_to_time AND $date_now_to_time <= $tgl_tugas_max7_to_time ";

				}else if($cari->sudah_ditugaskan == 3){

					$my_where_find .= "AND $date_now_to_time > $tgl_tugas_max7_to_time ";
					
				}

			}

			if($cari->status_lhkpn_sebelumnya != 0){
                if($cari->status_lhkpn_sebelumnya == 1){ 
                   
                    $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 4 ";  //Diumumkan Lengkap

                }else if($cari->status_lhkpn_sebelumnya == 2){

                    $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 6 ";  //Diumumkan Tidak Lengkap

                }else if($cari->status_lhkpn_sebelumnya == 3){

                    $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 3 ";  //Terverifikasi Lengkap

                }else if($cari->status_lhkpn_sebelumnya == 4){

                    $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 5 ";  //Terverifikasi Tidak Lengkap
                }
            }

			$compiled_string_where = trim($my_where) != "" ? $my_where : ' 1=1 ';
			if ($my_where_find != "") {
				$my_where = $compiled_string_where . " AND (" . $my_where_find . ")";
			}

        } else {
            $my_where .= "AND  T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.STAT IS NULL ";
		}

        $order_by = "  T_LHKPN.tgl_kirim_final asc ";

		$sql = " SELECT " . $my_select . " " . $my_from . " " . $my_join . " WHERE " . $my_where ." ". $my_having . " ORDER BY ". $order_by;
		
		$q = $this->db->query($sql);
		
        return $q->result();
	}

	public function get_keluarga_by_id_keluarga($id)
	{
		$this->db->where('ID_KELUARGA', $id);
		$data = $this->db->get($this->t_data_keluarga);
		if ($data->num_rows() == 1) {
			return $data->row();
		}
		else{
			return null;
		}
	}

	public function get_data_by_id($id, $type)
	{
		switch ($type) {
			// case 'data_pribadi':
			// 	$this->db->update($this->t_data_pribadi, $posted_fields);
			// 	break;

			case 'harta_tidak_bergerak':
				$this->db->where('ID', $id);
				$data = $this->db->get($this->t_harta_tidak_bergerak);
				break;

			case 'jabatan':
			    $this->db->where('ID', $id);
			    $data = $this->db->get($this->t_jabatan);
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

			case 'pengeluaran_kas':
			    $this->db->where('ID_LHKPN', $id);
			    $data = $this->db->get($this->t_pengeluaran);
			    break;

			case 'fasilitas':
			    $this->db->where('ID', $id);
			    $data = $this->db->get($this->t_fasilitas);
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

			case 'provinsi':
		      	$this->db->where('ID_PROV', $id);
    			$data = $this->db->get($this->m_area_prov);
    			break;

			case 't_lhkpn':
			    $this->db->where('ID_LHKPN', $id);
			    $data = $this->db->get($this->t_lhkpn);
			    break;

			case 't_data_pribadi':
			    $this->db->where('ID_LHKPN', $id);
			    $data = $this->db->get($this->t_data_pribadi);
			    break;

			case 'm_negara':
			    $this->db->where('ID', $id);
			    $data = $this->db->get($this->m_negara);
			    break;


			default:
				$data = false;
				break;
		}

		if ($data->num_rows() == 1) {
			return $data->row();
		}
		else{
			return null;
		}
	}

	public function update_data($posted_fields, $id, $type, $posted_fields_2 = null)
	{
		switch ($type) {
			case 'data_pribadi':
				$this->db->where('ID', $id);
				$this->db->update($this->t_data_pribadi, $posted_fields);
				break;

			case 'jabatan':
				$this->db->where('ID', $id);
				$this->db->update($this->t_jabatan, $posted_fields);
				break;

			case 'harta_tidak_bergerak':
				$this->db->where('ID', $id);
				$this->db->update($this->t_harta_tidak_bergerak, $posted_fields);
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

			case 'penerimaan_kas':
				$this->db->where('ID_LHKPN', $id);
				$this->db->update($this->t_penerimaan, $posted_fields);

				$this->db->where('ID_LHKPN', $id);
     			$this->db->delete($this->t_penerimaan_2);
				foreach($posted_fields_2 as $p){
					$this->db->insert($this->t_penerimaan_2, $p);
				}
				break;

			case 'pengeluaran_kas':
			    $this->db->where('ID_LHKPN', $id);
			    $this->db->update($this->t_pengeluaran, $posted_fields);

				$this->db->where('ID_LHKPN', $id);
     			$this->db->delete($this->t_pengeluaran_2);
				foreach($posted_fields_2 as $p){
					$this->db->insert($this->t_pengeluaran_2, $p);
				}
			    break;

			case 'fasilitas':
			    $this->db->where('ID', $id);
			    $this->db->update($this->t_fasilitas, $posted_fields);
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

	public function insert_data($posted_fields, $type)
	{
		switch ($type) {
			case 'harta_tidak_bergerak':
				$this->db->insert($this->t_harta_tidak_bergerak, $posted_fields);
				break;

			case 'jabatan':
			    $this->db->insert($this->t_jabatan, $posted_fields);
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
				break;

			case 'hutang':
				$this->db->insert($this->t_hutang, $posted_fields);
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
				$this->db->update($this->t_data_keluarga, $posted_fields);
				break;

			case 'harta_tidak_bergerak':
				$this->db->where('ID', $id);
				$this->db->update($this->t_harta_tidak_bergerak, $posted_fields);
				break;

			case 'htb':
			    $this->db->where('ID', $id);
			    $this->db->update($this->t_harta_tidak_bergerak, $posted_fields);
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

	public function delete_by_id($id, $type)
	{
	    switch ($type) {
	        case 'jabatan':
	            $this->db->where('ID', $id);
	            $this->db->delete($this->t_jabatan);
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

	public function delete_htb_by_id($id)
	{
		$posted_fields = array(
			'IS_ACTIVE' => '0'
		);
		$this->db->where('ID', $id);
		$this->db->update($this->t_harta_tidak_bergerak, $posted_fields);
		if ($this->db->affected_rows() > 0) {
		 	return true;
		}
		else{
			return false;
		}
	}

	public function delete_harta_bergerak_by_id($id)
	{
		$posted_fields = array(
			'IS_ACTIVE' => '0'
		);
		$this->db->where('ID', $id);
		$this->db->update($this->t_harta_bergerak, $posted_fields);
		if ($this->db->affected_rows() > 0) {
		 	return true;
		}
		else{
			return false;
		}
	}

	public function set_primary_null_by_id($id_lhkpn, $type)
	{
	    $posted_fields = array(
	        'IS_PRIMARY' => '0'
	    );
	    switch ($type) {
	        case 'jabatan':
	            $this->db->where('ID_LHKPN', $id_lhkpn);
	            $this->db->update($this->t_jabatan, $posted_fields);
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

	public function change_primary_by_id($id, $type)
	{
	    $posted_fields = array(
	        'IS_PRIMARY' => '1'
	    );
	    switch ($type) {
	        case 'jabatan':
	            $this->db->where('ID', $id);
	            $this->db->update($this->t_jabatan, $posted_fields);
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

	public function get_history_verification($id_lhkpn)
	{
		$this->db->select('*');
		$this->db->where('ID_LHKPN', $id_lhkpn);
		$this->db->where("(ID_STATUS = '17' OR ID_STATUS = '18')");
		$this->db->order_by('DATE_INSERT', 'DESC');
		$this->db->limit('1');
		$data = $this->db->get('T_LHKPN_STATUS_HISTORY');
		if ($data->num_rows() == 1) {
			return $data->row();
		}
		else{
			return false;
		}
	}

	function get_check_ai($id) {
		$this->db->select('response');
		$this->db->from('t_lhkpn_ai');
		$this->db->where('id_lhkpn=' . $id);

		$query = $this->db->get();
		//display($this->db->last_query());exit;
		if (is_object($query)) {
				$data = $query->row();
				if (is_object($data))
						return $data;
		}
		return false;
}

}
