<?php

/**
 * Edit data Excel pada tahap Verifikasi
 *
 * @author Wahyu Widodo <whywdd@yahoo.com>
 * @version
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Verification_edit extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('makses');
		$this->makses->initialize();
		$this->load->model('ever/Verification_model');
		$this->config->load('harta');
	}

	/* Edit Data Pribadi */
	public function index($id_lhkpn)
	{
        $data['data_pribadi'] = $this->Verification_model->get_data_pribadi_by_id_lhkpn($id_lhkpn);
        $this->load->view('verification_edit/data_pribadi', $data);
	}

	public function update_data_pribadi()
	{
		$id = $this->input->post('ID');
		$tgl_lahir = $this->input->post('TANGGAL_LAHIR');
		$tgl_lahir = implode('-', array_reverse(explode('/', $tgl_lahir)));
		$posted_fields = array(
			"GELAR_DEPAN" => $this->input->post('GELAR_DEPAN'),
	        "NO_KK" => $this->input->post('NO_KK'),
	        "NIK" => $this->input->post('NIK'),
	        "NAMA_LENGKAP" => $this->input->post('NAMA_LENGKAP'),
	        "GELAR_BELAKANG" => $this->input->post('GELAR_BELAKANG'),
	        "JENIS_KELAMIN" => $this->input->post('JENIS_KELAMIN'),
	        "TEMPAT_LAHIR" => $this->input->post('TEMPAT_LAHIR'),
	        "TANGGAL_LAHIR" => $tgl_lahir,
	        "AGAMA" => $this->input->post('AGAMA'),
	        "NPWP" => $this->input->post('NPWP'),
	        "TELPON_RUMAH" => $this->input->post('TELPON_RUMAH'),
	        "EMAIL_PRIBADI" => $this->input->post('EMAIL_PRIBADI'),
	        "HP" => $this->input->post('HP'),
	        "ALAMAT_RUMAH" => $this->input->post('ALAMAT_RUMAH'),
	        "STATUS_PERKAWINAN" => $this->input->post('STATUS_PERKAWINAN')
		);
		$update = $this->Verification_model->update_data($posted_fields, $id, 'data_pribadi');
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}



	public function keluarga($id, $mode = 'new')
	{
// 	    dump($id);
		$data['id_lhkpn'] = $id;
		if ($mode == 'edit') {
			$data['keluarga'] = $this->Verification_model->get_keluarga_by_id_keluarga($id);
			$data['mode'] = $mode;
			$t_lhkpn_data_pribadi = $this->Verification_model->get_data_by_id($data['keluarga']->ID_LHKPN,'t_data_pribadi');
			$data['alamat_rumah'] = $t_lhkpn_data_pribadi->ALAMAT_RUMAH;

      		$this->load->model('mlhkpnkeluarga');
			$data['lhkpn_ver'] = $this->mlhkpnkeluarga->get_lhkpn_version($data['keluarga']->ID_LHKPN);
		}
		else{
			$data['mode'] = $mode;
			$t_lhkpn_data_pribadi = $this->Verification_model->get_data_by_id($id,'t_data_pribadi');
			$data['alamat_rumah'] = $t_lhkpn_data_pribadi->ALAMAT_RUMAH;
		}

//
		$this->load->view('verification_edit/data_keluarga', $data);
	}

	public function update_keluarga($mode)
	{
		$id = $this->input->post('ID');
		$tgl_lahir = $this->input->post('TANGGAL_LAHIR');
		$tgl_lahir = implode('-', array_reverse(explode('/', $tgl_lahir)));
		$posted_fields = array(
			"NIK" => $this->input->post('NIK'),
	        "NAMA" => $this->input->post('NAMA'),
	        "HUBUNGAN" => $this->input->post('HUBUNGAN'),
	        "TEMPAT_LAHIR" => $this->input->post('TEMPAT_LAHIR'),
	        "TANGGAL_LAHIR" => $tgl_lahir,
	        "JENIS_KELAMIN" => $this->input->post('JENIS_KELAMIN'),
	        "PEKERJAAN" => $this->input->post('PEKERJAAN'),
	        "NOMOR_TELPON" => $this->input->post('NOMOR_TELPON'),
	        "ALAMAT_RUMAH" => $this->input->post('ALAMAT_RUMAH')
		);
		if ($mode == 'new') {
			$posted_fields['ID_LHKPN'] = $id;
			$posted_fields['IS_ACTIVE'] = '1';
			$posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$update = $this->Verification_model->insert_data_keluarga($posted_fields);
		}
		else{
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_IP'] = $this->get_client_ip();
			$update = $this->Verification_model->update_data_keluarga($posted_fields, $id);
		}
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}

	function soft_delete($id,$datasource){
		$result = $this->Verification_model->soft_delete_by_id($id,$datasource);
	}

	function delete($id,$datasource){
	    $result = $this->Verification_model->delete_by_id($id,$datasource);
	}

	function changePrimaryKey($id,$datasource,$id_lhkpn){
	    $this->Verification_model->set_primary_null_by_id($id_lhkpn,$datasource);
	    $result = $this->Verification_model->change_primary_by_id($id,$datasource);
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

	public function update_htb($id, $mode)
	{
		$data['id'] = $id;
		if ($mode == 'new') {
			$data['onAdd'] = false;
			$data['action'] = 'do_update_htb/new';
		}
		else{
			$data['onAdd'] = false;
			$data['action'] = 'do_update_htb/edit';
			$data['harta'] = $this->Verification_model->get_data_by_id($id, 'harta_tidak_bergerak');
			if($data['harta']->NEGARA==2){
				$get_negara = $this->Verification_model->get_data_by_id($data['harta']->ID_NEGARA, 'm_negara');
				$data['negara_nama'] = $get_negara->NAMA_NEGARA;
			}
			// dump($data['id_negara']->NAMA_NEGARA);
		}
		$this->load->view('verification_edit/data_htb', $data);
	}

	public function do_update_htb($mode)
	{
		$id = $this->input->post('ID');
		$provinsi = $this->Verification_model->get_data_by_id($this->input->post('PROV'),'provinsi');
		$provinsi_nama = $provinsi->NAME;
		if($this->input->post('NEGARA')==1){
			$id_negara = 2;
		}else{
			$id_negara =$this->input->post('ID_NEGARA');
		}
		$posted_fields = array(
			"ID_PROV" => $this->input->post('PROV') == '' ? NULL : $this->input->post('PROV'),
		    "PROV" => $provinsi_nama,
	        "KAB_KOT" => $this->input->post('KAB_KOT'),
	        "KEC" => $this->input->post('KEC'),
		    "NEGARA" => $this->input->post('NEGARA'),
		    "ID_NEGARA" => $id_negara,
	        "KEL" => $this->input->post('KEL'),
	        "JALAN" => $this->input->post('JALAN'),
	        "LUAS_TANAH" => $this->input->post('LUAS_TANAH'),
	        "LUAS_BANGUNAN" => $this->input->post('LUAS_BANGUNAN')=='' ? NULL : $this->input->post('LUAS_BANGUNAN'),
	        "JENIS_BUKTI" => $this->input->post('JENIS_BUKTI'),
	        "NOMOR_BUKTI" => $this->input->post('NOMOR_BUKTI'),
	        "ATAS_NAMA" => $this->input->post('ATAS_NAMA'),
	        "KET_LAINNYA" => $this->input->post('ATAS_NAMA_LAINNYA'),
	        "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
	        "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
	        "ASAL_USUL" => implode(',', $this->input->post('ASAL_USUL')),
	        "PEMANFAATAN" => implode(',', $this->input->post('PEMANFAATAN')),
		    "TAHUN_PEROLEHAN_AWAL" => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
		);
		if ($mode == 'new') {
			$posted_fields['ID_LHKPN'] = $id;
			$posted_fields['IS_ACTIVE'] = '1';
			$posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$update = $this->Verification_model->insert_data($posted_fields, 'harta_tidak_bergerak');
		}
		else{
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_IP'] = $this->get_client_ip();
			$update = $this->Verification_model->update_data($posted_fields, $id, 'harta_tidak_bergerak');
		}
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}

	public function update_harta_bergerak($id, $mode)
	{
		$data['ID'] = $id;
		if ($mode == 'new') {
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_bergerak/new';
		}
		else{
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_bergerak/edit';
// 			$data['harta'] = $this->Verification_model->get_harta_by_id($id, 'harta_bergerak');
			$data['harta'] = $this->Verification_model->get_data_by_id($id, 'harta_bergerak');
		}
		$this->load->view('verification_edit/data_harta_bergerak', $data);
	}

	public function do_update_harta_bergerak($mode)
	{
		$id = $this->input->post('ID');
		$posted_fields = array(
			"KODE_JENIS" => $this->input->post('KODE_JENIS'),
	        "MEREK" => $this->input->post('MEREK'),
	        "MODEL" => $this->input->post('MODEL'),
	        "TAHUN_PEMBUATAN" => $this->input->post('TAHUN_PEMBUATAN'),
	        "NOPOL_REGISTRASI" => $this->input->post('NOPOL_REGISTRASI'),
	        "JENIS_BUKTI" => $this->input->post('JENIS_BUKTI'),
	        "ATAS_NAMA" => $this->input->post('ATAS_NAMA'),
	        "KET_LAINNYA" => $this->input->post('ATAS_NAMA_LAINNYA'),
	        "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
	        "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
	        "ASAL_USUL" => implode(',', $this->input->post('ASAL_USUL')),
		    "PEMANFAATAN" => implode(',', $this->input->post('PEMANFAATAN')),
		    "TAHUN_PEROLEHAN_AWAL" => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
// 		    "PEMANFAATAN" => 5,
// 		    "MATA_UANG" => 1
		);
		if ($mode == 'new') {
		    $posted_fields['ID_LHKPN'] = $id;
			$posted_fields['IS_ACTIVE'] = '1';
			$posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$update = $this->Verification_model->insert_data($posted_fields, 'harta_bergerak');
		}
		else{
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_IP'] = $this->get_client_ip();
			$update = $this->Verification_model->update_data($posted_fields, $id, 'harta_bergerak');
		}
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}

	public function update_harta_bergerak_lain($id, $mode)
	{
		$data['ID'] = $id;
		if ($mode == 'new') {
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_bergerak_lain/new';
		}
		else{
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_bergerak_lain/edit';
// 			$data['harta'] = $this->Verification_model->get_harta_by_id($id, 'harta_bergerak_lain');
			$data['harta'] = $this->Verification_model->get_data_by_id($id, 'harta_bergerak_lain');
		}
		$this->load->view('verification_edit/data_harta_bergerak_lain', $data);
	}

	public function do_update_harta_bergerak_lain($mode)
	{
		$id = $this->input->post('ID');
		$posted_fields = array(
			"KODE_JENIS" => $this->input->post('KODE_JENIS'),
	        "JUMLAH" => str_replace('.', '', $this->input->post('JUMLAH')),
	        "SATUAN" => $this->input->post('SATUAN'),
	        "KETERANGAN" => $this->input->post('KETERANGAN'),
	        "ASAL_USUL" => implode(',', $this->input->post('ASAL_USUL')),
	        "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
		    "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
		    "TAHUN_PEROLEHAN_AWAL" => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
		);
		if ($mode == 'new') {
			$posted_fields['ID_LHKPN'] = $id;
			$posted_fields['IS_ACTIVE'] = '1';
			$posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$update = $this->Verification_model->insert_data($posted_fields, 'harta_bergerak_lain');
		}
		else{
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_IP'] = $this->get_client_ip();
			$update = $this->Verification_model->update_data($posted_fields, $id, 'harta_bergerak_lain');
		}
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}

	public function update_harta_surat_berharga($id, $mode)
	{
		$data['ID'] = $id;
		if ($mode == 'new') {
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_surat_berharga/new';
		}
		else{
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_surat_berharga/edit';
			$data['harta'] = $this->Verification_model->get_data_by_id($id, 'harta_surat_berharga');
		}
		$this->load->view('verification_edit/data_harta_surat_berharga', $data);
	}

	public function do_update_harta_surat_berharga($mode)
	{
		$encrypt_norek = encrypt_username($this->input->post('NOMOR_REKENING'),'e');
		$id = $this->input->post('ID');
		$posted_fields = array(
			"NOMOR_REKENING" => $encrypt_norek,
	        "KODE_JENIS" => $this->input->post('KODE_JENIS'),
	        "ATAS_NAMA" => $this->input->post('ATAS_NAMA'),
	        "KET_LAINNYA" => $this->input->post('ATAS_NAMA_LAINNYA'),
	        "NAMA_PENERBIT" => $this->input->post('NAMA_PENERBIT'),
	        "CUSTODIAN" => $this->input->post('CUSTODIAN'),
	        "ASAL_USUL" => implode(',', $this->input->post('ASAL_USUL')),
	        "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
		    "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
		    "TAHUN_PEROLEHAN_AWAL" => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
		);
		if ($mode == 'new') {
			$posted_fields['ID_LHKPN'] = $id;
			$posted_fields['IS_ACTIVE'] = '1';
			$posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$update = $this->Verification_model->insert_data($posted_fields, 'harta_surat_berharga');
		}
		else{
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_IP'] = $this->get_client_ip();
			$update = $this->Verification_model->update_data($posted_fields, $id, 'harta_surat_berharga');
		}
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}

	public function update_harta_kas($id, $mode)
	{
		$data['ID'] = $id;
		if ($mode == 'new') {
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_kas/new';
		}
		else{
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_kas/edit';
			$data['harta'] = $this->Verification_model->get_data_by_id($id, 'harta_kas');
		}
		$this->load->view('verification_edit/data_harta_kas', $data);
	}

	public function do_update_harta_kas($mode)
	{
		$encrypt_namabank = encrypt_username($this->input->post('NAMA_BANK'),'e');
        $encrypt_norek = encrypt_username($this->input->post('NOMOR_REKENING'),'e');
		$id = $this->input->post('ID');
		$posted_fields = array(
			"KODE_JENIS" => $this->input->post('KODE_JENIS'),
	        "NAMA_BANK" => $encrypt_namabank,
	        "NOMOR_REKENING" => $encrypt_norek,
	        "ATAS_NAMA_REKENING" => $this->input->post('ATAS_NAMA_REKENING'),
	        "ATAS_NAMA_LAINNYA" => $this->input->post('ATAS_NAMA_LAINNYA'),
	        "MATA_UANG" => $this->input->post('MATA_UANG'),
	        "MATA_UANG" => $this->input->post('MATA_UANG'),
	        "ASAL_USUL" => implode(',', $this->input->post('ASAL_USUL')),
	        "NILAI_SALDO" => str_replace('.', '', $this->input->post('NILAI_SALDO')),
	        "NILAI_KURS" => str_replace('.', '', $this->input->post('NILAI_KURS')),
	        "NILAI_EQUIVALEN" => str_replace(',', '.', str_replace('.', '', $this->input->post('NILAI_EQUIVALEN'))),
		    "TAHUN_BUKA_REKENING" => str_replace('.', '', $this->input->post('TAHUN_BUKA_REKENING'))
		);
		if ($mode == 'new') {
			$posted_fields['ID_LHKPN'] = $id;
			$posted_fields['IS_ACTIVE'] = '1';
			$posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$update = $this->Verification_model->insert_data($posted_fields, 'harta_kas');
		}
		else{
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_IP'] = $this->get_client_ip();
			$update = $this->Verification_model->update_data($posted_fields, $id, 'harta_kas');
		}
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}

	public function update_harta_lainnya($id, $mode)
	{
		$data['ID'] = $id;
		if ($mode == 'new') {
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_lainnya/new';
		}
		else{
			$data['onAdd'] = false;
			$data['action'] = 'do_update_harta_lainnya/edit';
			$data['harta'] = $this->Verification_model->get_data_by_id($id, 'harta_lainnya');
		}
		$this->load->view('verification_edit/data_harta_lainnya', $data);
	}

	public function do_update_harta_lainnya($mode)
	{
		$id = $this->input->post('ID');
		$posted_fields = array(
			"KODE_JENIS" => $this->input->post('KODE_JENIS'),
	        "KETERANGAN" => $this->input->post('KETERANGAN'),
	        "ASAL_USUL" => implode(',', $this->input->post('ASAL_USUL')),
	        "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
		    "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
		    "TAHUN_PEROLEHAN_AWAL" => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
		);
		if ($mode == 'new') {
			$posted_fields['ID_LHKPN'] = $id;
			$posted_fields['IS_ACTIVE'] = '1';
			$posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$update = $this->Verification_model->insert_data($posted_fields, 'harta_lainnya');
		}
		else{
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_IP'] = $this->get_client_ip();
			$update = $this->Verification_model->update_data($posted_fields, $id, 'harta_lainnya');
		}
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}

	public function update_hutang($id, $mode)
	{
		$data['ID'] = $id;
		if ($mode == 'new') {
			$data['onAdd'] = false;
			$data['action'] = 'do_update_hutang/new';
		}
		else{
			$data['onAdd'] = false;
			$data['action'] = 'do_update_hutang/edit';
			$data['hutang'] = $this->Verification_model->get_data_by_id($id, 'hutang');
		}
		$this->load->view('verification_edit/data_hutang', $data);
	}

	public function do_update_hutang($mode)
	{
		$id = $this->input->post('ID');
		$posted_fields = array(
			"KODE_JENIS" => $this->input->post('KODE_JENIS'),
			"ATAS_NAMA" => $this->input->post('ATAS_NAMA'),
	        "ATAS_NAMA_LAINNYA" => $this->input->post('ATAS_NAMA_LAINNYA'),
	        "NAMA_KREDITUR" => $this->input->post('NAMA_KREDITUR'),
	        "AGUNAN" => $this->input->post('AGUNAN'),
	        "AWAL_HUTANG" => str_replace('.', '', $this->input->post('AWAL_HUTANG')),
	        "SALDO_HUTANG" => str_replace('.', '', $this->input->post('SALDO_HUTANG'))
		);
		if ($mode == 'new') {
			$posted_fields['ID_LHKPN'] = $id;
			$posted_fields['IS_ACTIVE'] = '1';
			$posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$update = $this->Verification_model->insert_data($posted_fields, 'hutang');
		}
		else{
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_IP'] = $this->get_client_ip();
			$update = $this->Verification_model->update_data($posted_fields, $id, 'hutang');
		}
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}

	public function update_penerimaan($id)
	{
		$data['ID'] = $id;
		$data['onAdd'] = false;
		$data['action'] = 'do_update_penerimaan';
		$data['penerimaan'] = $this->Verification_model->get_data_by_id($id, 'penerimaan_kas');
		$penerimaan = json_decode($data['penerimaan']->NILAI_PENERIMAAN_KAS_PN);
		$penerimaan_istri = json_decode($data['penerimaan']->NILAI_PENERIMAAN_KAS_PASANGAN);
		$penerimaanA = $penerimaan->A;

		for ($i=0; $i < sizeof($penerimaanA); $i++) {
			$cukA = 'A'.$i;
			$data_penerimaan[$cukA] = $penerimaanA[$i]->$cukA;
		}
		$penerimaanB = $penerimaan->B;
		for ($i=0; $i < sizeof($penerimaanB); $i++) {
			$cukB = 'B'.$i;
			$data_penerimaan[$cukB] = $penerimaanB[$i]->$cukB;
		}
		$penerimaanC = $penerimaan->C;
		for ($i=0; $i < sizeof($penerimaanC); $i++) {
			$cukC = 'C'.$i;
			$data_penerimaan[$cukC] = $penerimaanC[$i]->$cukC;
		}
		for ($i=0; $i < sizeof($penerimaan_istri); $i++) {
			$cukPA = 'PA'.$i;
			$data_penerimaan[$cukPA] = $penerimaan_istri[$i]->$cukPA;
		}

		$data['item'] = (object)$data_penerimaan;
		$data["jenis_penerimaan_kas_pn"] = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $data["golongan_penerimaan_kas_pn"] = $this->config->item('golongan_penerimaan_kas_pn', 'harta');
		$this->load->view('verification_edit/data_penerimaan_kas', $data);
	}

	public function do_update_penerimaan($mode)
	{
		$id = $this->input->post('ID');

		$posted_fields_2 = array(
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'A',"KODE_JENIS" => 'A0',"JENIS_PENERIMAAN" => 'Gaji dan Tunjangan',
				"PN" => str_replace('.','',$this->input->post('A0')),"PASANGAN" => str_replace('.','',$this->input->post('PA0')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'A',"KODE_JENIS" => 'A1',"JENIS_PENERIMAAN" => 'Penghasilan dari Profesi/Keahlian',
				"PN" => str_replace('.','',$this->input->post('A1')),"PASANGAN" => str_replace('.','',$this->input->post('PA1')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'A',"KODE_JENIS" => 'A2',"JENIS_PENERIMAAN" => 'Honorarium',
				"PN" => str_replace('.','',$this->input->post('A2')),"PASANGAN" => str_replace('.','',$this->input->post('PA2')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'A',"KODE_JENIS" => 'A3',"JENIS_PENERIMAAN" => 'Tantiem, bonus, jasa produksi, THR',
				"PN" => str_replace('.','',$this->input->post('A3')),"PASANGAN" => str_replace('.','',$this->input->post('PA3')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'A',"KODE_JENIS" => 'A4',"JENIS_PENERIMAAN" => 'Penerimaan Pekerjaan Lainnya',
				"PN" => str_replace('.','',$this->input->post('A4')),"PASANGAN" => str_replace('.','',$this->input->post('PA4')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'B',"KODE_JENIS" => 'B0',"JENIS_PENERIMAAN" => 'Hasil Investasi dalam Surat Berharga',
				"PN" => str_replace('.','',$this->input->post('B0')),"PASANGAN" => 0,
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'B',"KODE_JENIS" => 'B1',"JENIS_PENERIMAAN" => 'Hasil Usaha/Sewa',
				"PN" => str_replace('.','',$this->input->post('B1')),"PASANGAN" => 0,
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'B',"KODE_JENIS" => 'B2',"JENIS_PENERIMAAN" => 'Bunga Tabungan/Deposito dan Lainnya',
				"PN" => str_replace('.','',$this->input->post('B2')),"PASANGAN" => 0,
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'B',"KODE_JENIS" => 'B3',"JENIS_PENERIMAAN" => 'Penjualan atau Pelepasan Harta',
				"PN" => str_replace('.','',$this->input->post('B3')),"PASANGAN" => 0,
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'C',"KODE_JENIS" => 'C0',"JENIS_PENERIMAAN" => 'Penerimaan Hutang',
				"PN" => str_replace('.','',$this->input->post('C0')),"PASANGAN" => 0,
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'C',"KODE_JENIS" => 'C1',"JENIS_PENERIMAAN" => 'Penerimaan Warisan',
				"PN" => str_replace('.','',$this->input->post('C1')),"PASANGAN" => 0,
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'C',"KODE_JENIS" => 'C2',"JENIS_PENERIMAAN" => 'Penerimaan Hibah/Hadiah',
				"PN" => str_replace('.','',$this->input->post('C2')),"PASANGAN" => 0,
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'C',"KODE_JENIS" => 'C3',"JENIS_PENERIMAAN" => 'Lainnya',
				"PN" => str_replace('.','',$this->input->post('C3')),"PASANGAN" => 0,
			],
		);

		$posted_fields = array(
			'NILAI_PENERIMAAN_KAS_PN' => '{"A":[{"A0":"'.str_replace('.','',$this->input->post('A0')).'"},{"A1":"'.str_replace('.','',$this->input->post('A1')).'"},{"A2":"'.str_replace('.','',$this->input->post('A2')).'"},{"A3":"'.str_replace('.','',$this->input->post('A3')).'"},{"A4":"'.str_replace('.','',$this->input->post('A4')).'"}],"B":[{"B0":"'.str_replace('.','',$this->input->post('B0')).'"},{"B1":"'.str_replace('.','',$this->input->post('B1')).'"},{"B2":"'.str_replace('.','',$this->input->post('B2')).'"},{"B3":"'.str_replace('.','',$this->input->post('B3')).'"},{"B4":"'.str_replace('.','',$this->input->post('B4')).'"}],"C":[{"C0":"'.str_replace('.','',$this->input->post('C0')).'"},{"C1":"'.str_replace('.','',$this->input->post('C1')).'"},{"C2":"'.str_replace('.','',$this->input->post('C2')).'"},{"C3":"'.str_replace('.','',$this->input->post('C3')).'"}]}',
			'NILAI_PENERIMAAN_KAS_PASANGAN' => '[{"PA0":"'.str_replace('.','',$this->input->post('PA0')).'"},{"PA1":"'.str_replace('.','',$this->input->post('PA1')).'"},{"PA2":"'.str_replace('.','',$this->input->post('PA2')).'"},{"PA3":"'.str_replace('.','',$this->input->post('PA3')).'"},{"PA4":"'.str_replace('.','',$this->input->post('PA4')).'"}]',
			'UPDATED_TIME' => date('Y-m-d H:i:s'),
	        'UPDATED_BY' => $this->session->userdata('USERNAME'),
	        'UPDATED_IP' => $this->get_client_ip()
		);
		$update = $this->Verification_model->update_data($posted_fields, $id, 'penerimaan_kas',$posted_fields_2);
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
	}


	public function update_pengeluaran($id)
	{
	    $data['ID'] = $id;
	    $data['onAdd'] = false;
	    $data['action'] = 'do_update_pengeluaran';
	    $data['pengeluaran'] = $this->Verification_model->get_data_by_id($id, 'pengeluaran_kas');
	    $pengeluaran = json_decode($data['pengeluaran']->NILAI_PENGELUARAN_KAS);
	    $pengeluaranA = $pengeluaran->A;

	    for ($i=0; $i < sizeof($pengeluaranA); $i++) {
	        $cukA = 'A'.$i;
	        $data_pengeluaran[$cukA] = $pengeluaranA[$i]->$cukA;
	    }
	    $pengeluaranB = $pengeluaran->B;
	    for ($i=0; $i < sizeof($pengeluaranB); $i++) {
	        $cukB = 'B'.$i;
	        $data_pengeluaran[$cukB] = $pengeluaranB[$i]->$cukB;
	    }
	    $pengeluaranC = $pengeluaran->C;
	    for ($i=0; $i < sizeof($pengeluaranC); $i++) {
	        $cukC = 'C'.$i;
	        $data_pengeluaran[$cukC] = $pengeluaranC[$i]->$cukC;
	    }
	    $data['item'] = (object)$data_pengeluaran;
	    $data["jenis_pengeluaran_kas_pn"] = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
	    $data["golongan_pengeluaran_kas_pn"] = $this->config->item('golongan_pengeluaran_kas_pn', 'harta');

	    $this->load->view('verification_edit/data_pengeluaran_kas', $data);
	}

	public function do_update_pengeluaran($mode)
	{
	    $id = $this->input->post('ID');

		$posted_fields_2 = array(
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'A',"KODE_JENIS" => 'A0',"JENIS_PENGELUARAN" => 'Biaya Rumah Tangga (termasuk transportasi, pendidikan, kesehatan, rekreasi, pembayaran kartu kredit)',
				"JML" => str_replace('.','',$this->input->post('A0')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'A',"KODE_JENIS" => 'A1',"JENIS_PENGELUARAN" => 'Biaya Sosial (antara lain keagamaan, zakat, infaq, sumbangan lain)',
				"JML" => str_replace('.','',$this->input->post('A1')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'A',"KODE_JENIS" => 'A2',"JENIS_PENGELUARAN" => 'Pembayaran Pajak (antara lain PBB, kendaraan, pajak daerah, pajak lain)',
				"JML" => str_replace('.','',$this->input->post('A2')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'A',"KODE_JENIS" => 'A3',"JENIS_PENGELUARAN" => 'Pengeluaran Rutin Lainnya',
				"JML" => str_replace('.','',$this->input->post('A3')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'B',"KODE_JENIS" => 'B0',"JENIS_PENGELUARAN" => 'Pembelian/Perolehan Harta Baru',
				"JML" => str_replace('.','',$this->input->post('B0')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'B',"KODE_JENIS" => 'B1',"JENIS_PENGELUARAN" => 'Pemeliharaan/Modifikasi/Rehabilitasi Harta',
				"JML" => str_replace('.','',$this->input->post('B1')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'B',"KODE_JENIS" => 'B2',"JENIS_PENGELUARAN" => 'Pengeluaran Non Rutin Lainnya',
				"JML" => str_replace('.','',$this->input->post('B2')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'C',"KODE_JENIS" => 'C0',"JENIS_PENGELUARAN" => 'Biaya Pengurusan Waris/hibah/hadiah',
				"JML" => str_replace('.','',$this->input->post('C0')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'C',"KODE_JENIS" => 'C1',"JENIS_PENGELUARAN" => 'Pelunasan/Angsuran Hutang',
				"JML" => str_replace('.','',$this->input->post('C1')),
			],
			[
				"ID_LHKPN" => $id,"GROUP_JENIS" => 'C',"KODE_JENIS" => 'C2',"JENIS_PENGELUARAN" => 'Pengeluaran Lainnya',
				"JML" => str_replace('.','',$this->input->post('C2')),
			],

		);

	    $posted_fields = array(
	        'NILAI_PENGELUARAN_KAS' => '{"A":[{"A0":"'.str_replace('.','',$this->input->post('A0')).'"},{"A1":"'.str_replace('.','',$this->input->post('A1')).'"},{"A2":"'.str_replace('.','',$this->input->post('A2')).'"},{"A3":"'.str_replace('.','',$this->input->post('A3')).'"},{"A4":"'.str_replace('.','',$this->input->post('A4')).'"}],"B":[{"B0":"'.str_replace('.','',$this->input->post('B0')).'"},{"B1":"'.str_replace('.','',$this->input->post('B1')).'"},{"B2":"'.str_replace('.','',$this->input->post('B2')).'"},{"B3":"'.str_replace('.','',$this->input->post('B3')).'"},{"B4":"'.str_replace('.','',$this->input->post('B4')).'"}],"C":[{"C0":"'.str_replace('.','',$this->input->post('C0')).'"},{"C1":"'.str_replace('.','',$this->input->post('C1')).'"},{"C2":"'.str_replace('.','',$this->input->post('C2')).'"},{"C3":"'.str_replace('.','',$this->input->post('C3')).'"}]}',
	        'UPDATED_TIME' => date('Y-m-d H:i:s'),
	        'UPDATED_BY' => $this->session->userdata('USERNAME'),
	        'UPDATED_IP' => $this->get_client_ip()
	    );
	    $update = $this->Verification_model->update_data($posted_fields, $id, 'pengeluaran_kas',$posted_fields_2);
	    if ($update) {
	        $result = array(
	            'success' => 1,
	            'msg' => 'Data Berhasil Diupdate'
	        );
	    }
	    else{
	        $result = array(
	            'success' => 0,
	            'msg' => 'Data Gagal Diupdate'
	        );
	    }
// 	    return "berhasil";
	    echo json_encode($result);
	}



	public function update_fasilitas($id, $mode)
	{
	    $data['id'] = $id;
	    if ($mode == 'new') {
	        $data['onAdd'] = false;
	        $data['action'] = 'do_update_fasilitas/new';
	    }
	    else{
	        $data['onAdd'] = false;
	        $data['action'] = 'do_update_fasilitas/edit';
	        $data['fasilitas'] = $this->Verification_model->get_data_by_id($id, 'fasilitas');
	    }
	    $this->load->view('verification_edit/data_fasilitas', $data);
	}

	public function do_update_fasilitas($mode)
	{
	    $id = $this->input->post('ID');
	    $posted_fields = array(
	        "JENIS_FASILITAS" => $this->input->post('JENIS_FASILITAS'),
	        "KETERANGAN" => $this->input->post('KETERANGAN'),
	        "PEMBERI_FASILITAS" => $this->input->post('PEMBERI_FASILITAS'),
	        "KETERANGAN_LAIN" => $this->input->post('KETERANGAN_LAIN'),
	    );
	    if ($mode == 'new') {
	        $posted_fields['ID_LHKPN'] = $id;
	        $posted_fields['IS_ACTIVE'] = '1';
	        $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
	        $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
	        $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
	        $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
	        $update = $this->Verification_model->insert_data($posted_fields, 'fasilitas');
	    }
	    else{
	        $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
	        $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
	        $posted_fields['UPDATED_IP'] = $this->get_client_ip();
	        $update = $this->Verification_model->update_data($posted_fields, $id, 'fasilitas');
	    }
	    if ($update) {
	        $result = array(
	            'success' => 1,
	            'msg' => 'Data Berhasil Diupdate'
	        );
	    }
	    else{
	        $result = array(
	            'success' => 0,
	            'msg' => 'Data Gagal Diupdate'
	        );
	    }
	    echo json_encode($result);
	}


	/* Edit Data Jabatan */
	public function jabatan($id, $mode)
	{
	    // 		$data['ID'] = $id_lhkpn;
	    //         $data['lhkpn_jabatan'] = $this->Verification_model->get_jabatan_by_id_lhkpn($id_lhkpn);
	    //         $data['action'] = 'do_update_jabatan';
	    //         $this->load->view('verification_edit/data_jabatan', $data);

		$data['lhkpn_jabatan'] = new \stdClass();
	    $data['ID'] = $id;
	    if ($mode == 'new') {
	        $data['onAdd'] = false;
	        $data['lhkpn_jabatan']->edit = false;
	        $data['action'] = 'do_update_jabatan/new';
	    }
	    else{
	        $data['onAdd'] = false;
	        $data['action'] = 'do_update_jabatan/edit';
	        $data['lhkpn_jabatan'] = $this->Verification_model->get_data_by_id($id, 'jabatan');

	        // $lembaga = $this->Verification_model->get_data_by_id($data['lhkpn_jabatan']->LEMBAGA,'inst_satker');
	        // $data['lhkpn_jabatan']->LEMBAGA_NAMA = $lembaga->INST_NAMA;

	        // $unit_kerja = $this->Verification_model->get_data_by_id($data['lhkpn_jabatan']->UNIT_KERJA,'unit_kerja');
	        // $data['lhkpn_jabatan']->UNIT_KERJA_NAMA = $unit_kerja->UK_NAMA;

	        // $sub_unit_kerja = $this->Verification_model->get_data_by_id($data['lhkpn_jabatan']->SUB_UNIT_KERJA,'sub_unit_kerja');
	        // $data['lhkpn_jabatan']->SUB_UNIT_KERJA_NAMA = $sub_unit_kerja->SUK_NAMA;

	        $jabatan = $this->Verification_model->get_data_by_id($data['lhkpn_jabatan']->ID_JABATAN,'jabatan_1');
			$data['lhkpn_jabatan']->JABATAN_NAMA = $jabatan->NAMA_JABATAN;
			
			$lembaga = $this->Verification_model->get_data_by_id($jabatan->INST_SATKERKD, 'inst_satker');
	        $data['lhkpn_jabatan']->LEMBAGA_NAMA = $lembaga->INST_NAMA;

	        $unit_kerja = $this->Verification_model->get_data_by_id($jabatan->UK_ID, 'unit_kerja');
	        $data['lhkpn_jabatan']->UNIT_KERJA_NAMA = $unit_kerja->UK_NAMA;

	        $sub_unit_kerja = $this->Verification_model->get_data_by_id($jabatan->SUK_ID, 'sub_unit_kerja');
	        $data['lhkpn_jabatan']->SUB_UNIT_KERJA_NAMA = $sub_unit_kerja->SUK_NAMA;

	        $data['lhkpn_jabatan']->edit = true;
// 	        dump($data['lhkpn_jabatan']);
	    }
	    $this->load->view('verification_edit/data_jabatan', $data);
	}

	public function do_update_jabatan($mode)
	{
	    $id = $this->input->post('ID');

	    $jabatan = $this->Verification_model->get_data_by_id($this->input->post('ID_JABATAN'),'jabatan_1');
	    $DESKRIPSI_JABATAN = $jabatan->NAMA_JABATAN;

	    $posted_fields = array(
	        "LEMBAGA" => $this->input->post('LEMBAGA'),
	        "UNIT_KERJA" => $this->input->post('UNIT_KERJA'),
	        "SUB_UNIT_KERJA" => $this->input->post('SUB_UNIT_KERJA'),
	        "ID_JABATAN" => $this->input->post('ID_JABATAN'),
	        "ALAMAT_KANTOR" => $this->input->post('ALAMAT_KANTOR'),
	        "DESKRIPSI_JABATAN" => $DESKRIPSI_JABATAN,
	        "UPDATED_TIME" => date('Y-m-d H:i:s'),
	        "UPDATED_BY" => $this->session->userdata('USERNAME'),
	        "UPDATED_IP" => $this->get_client_ip()
	    );
// 	    $update = $this->Verification_model->update_data($posted_fields, $id, 'jabatan');



// 	    $id = $this->input->post('ID');
// 	    $posted_fields = array(
// 	        "JENIS_FASILITAS" => $this->input->post('JENIS_FASILITAS'),
// 	        "KETERANGAN" => $this->input->post('KETERANGAN'),
// 	        "PEMBERI_FASILITAS" => $this->input->post('PEMBERI_FASILITAS'),
// 	        "KETERANGAN_LAIN" => $this->input->post('KETERANGAN_LAIN'),
// 	    );
	    if ($mode == 'new') {
	        $posted_fields['ID_LHKPN'] = $id;
// 	        $posted_fields['IS_ACTIVE'] = '1';
	        $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
	        $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
// 	        $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
// 	        $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
	        $update = $this->Verification_model->insert_data($posted_fields, 'jabatan');
	    }
	    else{
// 	        $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
// 	        $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
// 	        $posted_fields['UPDATED_IP'] = $this->get_client_ip();
	        $update = $this->Verification_model->update_data($posted_fields, $id, 'jabatan');
	    }
	    if ($update) {
	        $result = array(
	            'success' => 1,
	            'msg' => 'Data Berhasil Diupdate'
	        );
	    }
	    else{
	        $result = array(
	            'success' => 0,
	            'msg' => 'Data Gagal Diupdate'
	        );
	    }
	    echo json_encode($result);
	}
}
