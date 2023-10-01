<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Korespondensi extends CI_Controller {

	public function __construct()
  {
    parent::__construct();
    $this->load->model('Korespondensi_model');
  }

  public function index()
  {
    $data['title'] = 'Korespondensi';
    $data['id_user'] = $this->session->userdata('ID_USER');
    $this->load->view('korespondensi/korespondensi_index_new', $data);
  }

  public function ajax_list()
  {
    $list = $this->Korespondensi_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    $i = 1;
    foreach ($list as $surat) {
			$no++;
      $row = array();
			// Cek jika surat lebih dari 60 hari dan belum dibalas
			if($surat->SURAT_TANGGAL != null)
			{
				$createdDate  = date_create($surat->SURAT_TANGGAL);
				$now  = date_create();
				$diff = date_diff($now, $createdDate);

				if($surat->SURAT_PEMENUHAN_STATUS == '8'){
					if ($diff->days >= 60) {
						// update kolom status pemenuhan, jika lewat dari 60 hari dan surat belum dibalas
						// $surat->SURAT_PEMENUHAN_STATUS = 6;
						// $this->Korespondensi_model->update_surat_pemenuhan($surat);
					}
				}
			}

			if($surat->SURAT_PEMENUHAN_STATUS != "7") {
				if ($diff->days >= 60) {
					$warning = '<span class="label label-warning" title="Surat telah lewat '.$diff->days.' hari tidak dibalas"><i class="fa fa-warning"></i></span>';
					$row[] = $no.' '.$warning;
				}
				else {
					$row[] = $no;
				}
			}
			else {
				$row[] = $no;
			}

		  $tgl = "";
		  $formatNomor = "";
		  if($surat->SURAT_TANGGAL != null)
		    $tgl = tgl_format(date('d-m-Y', strtotime($surat->SURAT_TANGGAL)));
		  if($surat->SURAT_NOMOR != null && $surat->SURAT_TANGGAL != null)
		    $formatNomor = $surat->SURAT_NOMOR.'<br>'.$tgl;
			if ($surat->SURAT_NOMOR != null && $surat->SURAT_TANGGAL == null) {
				$formatNomor = $surat->SURAT_NOMOR;
			}
			if ($surat->SURAT_NOMOR == null && $surat->SURAT_TANGGAL != null) {
				$formatNomor = $tgl;
			}

      $row[] = $formatNomor; // nomor surat dan tanggal
      $row[] = $surat->ORG_NAMA; // instansi

			if($surat->SURAT_PEMENUHAN_STATUS == "7")
          $statusPemenuhan = "Diterima Lengkap";
      else if($surat->SURAT_PEMENUHAN_STATUS == "8")
          $statusPemenuhan = "Diterima Belum Lengkap";
      else
          $statusPemenuhan = "-";

      $dataPN = $this->Korespondensi_model->get_detail_surat_by_id($surat->ID_SURAT_KELUAR);

      $ii = 1;
      $displayPN = "";

      foreach ($dataPN as $key => $pn) {
        $displayPN .= $ii.'. '.$pn["NAMA_PN"].'<br>';
        $ii++;
      }
      $row[] = $displayPN; // nama pn
      $row[] = $statusPemenuhan; // pemenuhan
      $row[] = '<button class="btn btn-success btn-sm btn-edit" title="Edit" id="btnUpdateSurat" data-idsurat="'.$surat->ID_SURAT_KELUAR.'"
				href=""><i class="fa fa-pencil"></i></button>
                <button class="btn btn-sm btn-primary btn-reply" title="Input Surat Balasan" id="btnInputSuratBalasan" data-idsurat="'.$surat->ID_SURAT_KELUAR.'" href=""><i class="fa fa-check-square-o"></i></button>
                <button class="btn btn-success btn-sm btnCetakSurat" title="Cetak Surat" id="btnCetakSurat" data-idsurat="'.$surat->ID_SURAT_KELUAR.'" data-idtemplate="'.$surat->SURAT_TEMPLATE_ID.'"><i class="fa fa-file-o"></i></button>';
      $data[] = $row;
    }
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Korespondensi_model->count_all(),
      "recordsFiltered" => $this->Korespondensi_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function cetakKorespondensi($suratId, $templateId)
  {
    $output_filename = "Surat_" . date('d-F-Y') . ".docx";
    $filename = 'uploads/pdf/korespondensi/'.$output_filename;
    $dataSurat = $this->Korespondensi_model->get_selected_surat($suratId);

    if (!file_exists($filename)) {
        $dir = './uploads/pdf/korespondensi/';

        if (is_dir($dir) === false) {
            mkdir($dir);
        }
    }

    $this->load->library('lwphpword/lwphpword', array(
        "base_path" => APPPATH . "../uploads/pdf/korespondensi/",
        "base_url" => base_url() . "../uploads/pdf/korespondensi/",
        "base_root" => base_url(),
    ));

    if($templateId == '1') {
      $template_file = "../file/template/korespondensi/SuratAsuransi.docx";
    }
    else if ($templateId == '2') {
      $template_file = "../file/template/korespondensi/SuratBPN.docx";
    }
    else if ($templateId == '3') {
      $template_file = "../file/template/korespondensi/SuratDataBank.docx";
    }

    $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, null);
    $this->lwphpword->save_path = APPPATH . "../uploads/pdf/korespondensi/";

    $suratNomor = $dataSurat[0]['SURAT_NOMOR'] == '' ? "  " : $dataSurat[0]['SURAT_NOMOR'] ;
    $suratTanggal = /* $dataSurat[0]['SURAT_TANGGAL'] == '' ? "   " : */ tgl_format(date('Y-m-d'));
    $orgGedung = $dataSurat[0]['ORG_GEDUNG'] == '' ? "   " : $dataSurat[0]['ORG_GEDUNG'] ;

    //get data kontak1
    $kontak1 = $this->Korespondensi_model->getDataKontak($suratId, $dataSurat[0]['SURAT_KONTAK']);
    $kontak2 = $this->Korespondensi_model->getDataKontak2($suratId, $dataSurat[0]['SURAT_KONTAK_2']);
    $namaKontak = $kontak2[0]->NAMA == '' ? $kontak1[0]->NAMA : $kontak1[0]->NAMA.' atau Sdr. '.$kontak2[0]->NAMA ;
    $iptelKontak = $kontak2[0]->HANDPHONE == '' ? $kontak1[0]->HANDPHONE : $kontak1[0]->HANDPHONE.' atau '.$kontak2[0]->HANDPHONE ;
    $emailKontak = $kontak2[0]->EMAIL == '' ? $kontak1[0]->EMAIL : $kontak1[0]->EMAIL.' atau '.$kontak2[0]->EMAIL ;

    $this->lwphpword->set_value("SURAT_NOMOR",$suratNomor);
    $this->lwphpword->set_value("SURAT_TANGGAL", $suratTanggal);
    $this->lwphpword->set_value("SURAT_LAMPIRAN", $dataSurat[0]['SURAT_LAMPIRAN']);
    $this->lwphpword->set_value("SURAT_HAL", $dataSurat[0]['SURAT_HAL']);
    $this->lwphpword->set_value("SURAT_SIFAT", $dataSurat[0]['SURAT_SIFAT']);

    $this->lwphpword->set_value("ORG_TEMBUSAN_SURAT", $dataSurat[0]['ORG_TEMBUSAN_SURAT']);
    $this->lwphpword->set_value("ORG_NAMA", $dataSurat[0]['ORG_NAMA']);
    $this->lwphpword->set_value("ORG_GEDUNG", $orgGedung);
    $this->lwphpword->set_value("ORG_ALAMAT", $dataSurat[0]['ORG_ALAMAT']);
    $this->lwphpword->set_value("ORG_PROVINSI", $dataSurat[0]['ORG_PROVINSI']);
    $this->lwphpword->set_value("PENANDATANGAN_JABATAN", $dataSurat[0]['PENANDATANGAN_JABATAN']);
    $this->lwphpword->set_value("PENANDATANGAN_NAMA", $dataSurat[0]['PENANDATANGAN_NAMA']);

    $this->lwphpword->set_value("NAMAKONTAK", $namaKontak);
    $this->lwphpword->set_value("IPTEL", $iptelKontak);
    $this->lwphpword->set_value("EMAIL_KONTAK", $emailKontak);

    $save_document_success = $this->lwphpword->save_document(0, '', 1, $output_filename);

    $this->lwphpword->download($save_document_success->document_path, $output_filename);
  }

  public function save_korespondensi_keluar($idSuratKeluar = NULL, $idPenandatangan = NULL)
  {
     $tgl_surat = $this->input->post('TGL_SURAT') == null ? null : $this->convert_to_date_mysql($this->input->post('TGL_SURAT'));
     if($idSuratKeluar != ''){
       $korespondensi_keluar = array( 'SURAT_ID' => $idSuratKeluar,
                                      'SURAT_NOMOR' => $this->input->post('NomorSurat'),
                                      'SURAT_KONTAK' => $this->input->post('CARI[KONTAK1]'),
                                      'SURAT_KONTAK_2' => $this->input->post('CARI[KONTAK2]'),
                                      'SURAT_TANGGAL' => $tgl_surat,
                                      'SURAT_SIFAT' => $this->input->post('Sifat'),
                                      'SURAT_LAMPIRAN' => $this->input->post('Lampiran'),
                                      'SURAT_HAL' => $this->input->post('Hal'),
                                      'SURAT_PENANDATANGAN_ID' => $idPenandatangan,
                                      'SURAT_TEMPLATE_ID' => $this->input->post('CARI[INSTANSI]'),
                                      'SURAT_INSTANSI_ID' => $this->input->post('CARI[INSTANSI_TUJUAN]'),
                                      'SURAT_MODIFIED_DATE' => date('Y-m-d H:i:s'),
                                      'SURAT_MODIFIED_BY' => $this->session->userdata('USR'),
                                      'SURAT_MODIFIED_IP' => $_SERVER["REMOTE_ADDR"]
                                      );

       $suratId = $this->Korespondensi_model->updatesurat($korespondensi_keluar);
       //update lampiran
       $return = $this->Korespondensi_model->deactivelampiran($suratId);
     }
     else{
       $korespondensi_keluar = array( 'SURAT_NOMOR' => $this->input->post('NomorSurat'),
                                      'SURAT_KONTAK' => $this->input->post('CARI[KONTAK1]'),
                                      'SURAT_KONTAK_2' => $this->input->post('CARI[KONTAK2]'),
                                      'SURAT_TANGGAL' => $tgl_surat,
                                      'SURAT_SIFAT' => $this->input->post('Sifat'),
                                      'SURAT_LAMPIRAN' => $this->input->post('Lampiran'),
                                      'SURAT_HAL' => $this->input->post('Hal'),
                                      'SURAT_PENANDATANGAN_ID' => $this->input->post('CARI[PENANDATANGAN]'),
                                      'SURAT_TEMPLATE_ID' => $this->input->post('CARI[INSTANSI]'),
                                      'SURAT_INSTANSI_ID' => $this->input->post('CARI[INSTANSI_TUJUAN]'),
                                      'SURAT_CREATED_DATE' => date('Y-m-d H:i:s'),
                                      'SURAT_CREATED_BY' => $this->session->userdata('USR'),
                                      'SURAT_CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                                      'SURAT_MODIFIED_DATE' => date('Y-m-d H:i:s'),
                                      'SURAT_MODIFIED_BY' => $this->session->userdata('USR'),
                                      'SURAT_MODIFIED_IP' => $_SERVER["REMOTE_ADDR"],
                                      'SURAT_PEMENUHAN_STATUS' => '-'
                                      );
        $suratId = $this->Korespondensi_model->savesurat($korespondensi_keluar);
     }

      $keluarga = $this->input->post('idKeluarga');
      for ($i=0; $i < sizeof($keluarga); $i++) {
        $id_kel = explode(',', $keluarga[$i]);
        $idlhkpn = $id_kel[0];
        $idKeluarga = $id_kel[1];
        $namaPN = $id_kel[2];
        $idKeluarga = $idKeluarga == 'undefined' ? NULL : $idKeluarga ;
        $lampiran = array('ID_SURAT_KELUAR' => $suratId,
                          'ID_LHKPN' => $idlhkpn,
                          'ID_KELUARGA' => $idKeluarga,
                          'NAMA_PN' => $namaPN,
                          'LAMPIRAN_CREATED_DATE' => date('Y-m-d H:i:s'),
                          'LAMPIRAN_CREATED_BY' => $this->session->userdata('USR'),
                          'LAMPIRAN_CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                          'IS_ACTIVE' => 1
                        );

        $this->Korespondensi_model->savelampiran($lampiran);
      }
      $data = $this->Korespondensi_model->get_korespondensi_data();
      echo $data;
    }
    public function save_lampiran_anggota($idSuratKeluar)
    {
      $lampiran_anggota = array( 'ID_SURAT_KELUAR' => $idSuratKeluar,
                                 'ID_LHKPN' => $this->input->post(''),
                                 'ID_KELUARGA' => $this->input->post(''),
                                 'LAMPIRAN_CREATED_DATE' => date('Y-m-d H:i:s'),
                                 'LAMPIRAN_CREATED_BY' => $this->session->userdata('USR'),
                                 'LAMPIRAN_CREATED_IP' => $_SERVER["REMOTE_ADDR"]
       );
       $this->Korespondensi_model->save_lampiran_anggota($lampiran_anggota);
    }

    public function get_status_pemenuhan($idSurat)
    {
      $statusPemenuhan = $this->Korespondensi_model->get_status_pemenuhan($idSurat);
    }

		public function save_korespondensi_balasan_new()
		{
		  $korespondensi_masuk = array(
		      'SURAT_ID' => $this->input->post('SURAT_ID'),
		      'BALASAN_TANGGAL' => $this->convert_to_date_mysql($this->input->post('TGL_SURAT')),
		      'BALASAN_NOMOR' => $this->input->post('NomorSurat'),
		      'BALASAN_PENGIRIM' => $this->input->post('NamaPengirim'),
		      'BALASAN_STATUS_PEMENUHAN_ID' => $this->input->post('StatusPemenuhan'),
		      'BALASAN_CREATED_DATE' => date('Y-m-d H:i:s'),
		      'BALASAN_CREATED_BY' => $this->session->userdata('USR'),
		      'BALASAN_CREATED_IP' => $_SERVER["REMOTE_ADDR"]
		    );
		  $this->Korespondensi_model->savesuratbalasan($korespondensi_masuk);
		}

		public function convert_to_date_mysql($param)
		{
		  if ($param != '' || $param != NULL) {
		    $date = explode('/', $param);
		    $date = array_reverse($date);
		    $date = implode('-', $date);
		    return $date;
		  }
		  else{
		    return null;
		  }
		}
		public function get_daftar_data_pn($IDLHKPN)
		{
		  $data = $this->Korespondensi_model->get_selected_pn($IDLHKPN);
		  echo json_encode($data);
		}

		// public function get_pn_by_st($idPemeriksa, $nomorSuratTugas, $termasukKeluarga)
		public function get_pn_by_st()
		{
			$idPemeriksa = $this->input->post('idPemeriksa');
			$nomorSuratTugas =  $this->input->post('nomorSuratTugas');
			$termasukKeluarga =$this->input->post('checkbox');

		  $data = $this->Korespondensi_model->get_selected_pn_by_st($idPemeriksa, $nomorSuratTugas, $termasukKeluarga);
		  echo json_encode($data);
		}
		// public function get_pn_by_name($idPemeriksa, $nama, $termasukKeluarga)
		public function get_pn_by_name()
		{
			$idPemeriksa = $this->input->post('idPemeriksa');
			$nama =  $this->input->post('nama');
			$termasukKeluarga =$this->input->post('checkbox');

		  $data = $this->Korespondensi_model->get_pn_by_name($idPemeriksa, $nama, $termasukKeluarga);
		  echo json_encode($data);
		}
		// public function get_pn_by_st_name($idPemeriksa, $nama, $nomorSuratTugas, $termasukKeluarga)
		public function get_pn_by_st_name()
		{
			$idPemeriksa = $this->input->post('idPemeriksa');
			$nama =  $this->input->post('nama');
			$nomorSuratTugas =  $this->input->post('nomorSuratTugas');
			$termasukKeluarga =$this->input->post('checkbox');

		  $data = $this->Korespondensi_model->get_pn_by_st_name($idPemeriksa, $nama, $nomorSuratTugas, $termasukKeluarga);
		  echo json_encode($data);
		}
		public function get_pn_by_surattugas($nomorSuratTugas = NULL)
		{
		  if (is_null($nomorSuratTugas)) {
		    $q = $_GET['q'];
		      $where = ['IS_ACTIVE' => '1'];
		    $result = $this->mglobal->get_data_all('T_LHKPN_AUDIT', null, $where, 'ID_LHKPN, NOMOR_SURAT_TUGAS', "NOMOR_SURAT_TUGAS LIKE '%$q%'", array('ID_LHKPN', 'ASC'), null, '15', null);
		    $res = [];
		    foreach ($result as $row) {
		        $res[] = ['id' => $row->ID_LHKPN, 'name' => strtoupper($row->NOMOR_SURAT_TUGAS)];
		    }
		    $data = ['item' => $res];
		    echo json_encode($data);
		  }
		  else
		  {
		    $where = ['IS_ACTIVE' => '1', 'NOMOR_SURAT_TUGAS' => $nomorSuratTugas];
		    $result = $this->mglobal->get_data_all('T_LHKPN_AUDIT', null, $where, 'ID_LHKPN, NOMOR_SURAT_TUGAS', null, null, null, '15', null);
		    $res = [];
		    foreach ($result as $row) {
		        $res[] = ['id' => $row->ID_LHKPN, 'name' => strtoupper($row->NOMOR_SURAT_TUGAS)];
		    }
		    echo json_encode($res);
		  }
		  exit;
		}

		public function get_daftar_data_pn2($IDLHKPN)
		{
		  $result = $this->Korespondensi_model->get_selected_pn($IDLHKPN);
		  $res = [];
		  foreach ($result as $row) {
		    var_dump($row);
		    $res[] = ['id' => $row["ID_LHKPN"], 'name' => strtoupper($row["NAMA"])];
		  }
		  return json_encode($res);
		}
		public function get_lampiran_data($idSurat)
		{
		  $data = $this->Korespondensi_model->get_lampiran_by_suratID($idSurat);
		  echo json_encode($data);
		}
		public function get_lampiran_data_cetak($idSurat)
		{
		  $data = $this->Korespondensi_model->get_lampiran_by_suratID_cetak($idSurat);
		  echo json_encode($data);
		}
	  public function addsuratkeluar($suratId = null)
	  {
	    $data['id_user'] = $this->session->userdata('ID_USER');
	    if($suratId != null) {
	      $data['surat'] = $this->Korespondensi_model->get_selected_surat($suratId);
	    }
	    else{
	      $data['surat'] = null;
	    }
	    $this->load->view(strtolower(__CLASS__) . '/' . 'korespondensi_keluar_form_new', $data);
	  }

	  public function addsuratbalasan($suratId)
	  {
	    $data['surat'] = $this->Korespondensi_model->get_selected_balasan($suratId);
	    $this->load->view(strtolower(__CLASS__) . '/' . 'korespondensi_balasan_form', $data);
	  }

	  public function editsuratkeluar($suratId)
	  {
	    $data['surat'] = $this->Korespondensi_model->get_selected_surat($suratId);
	    $this->load->view(strtolower(__CLASS__) . '/' . 'korespondensi_keluar_form_edit', $data);
	  }

	  public function hasilcaripn($offset = 0) {
	      $this->offset = $offset;
	      $this->base_url = site_url('eaudit/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
	      $this->limit = 5;
	      $this->uri_segment = 4;
	      $this->load->model('Mmpnwn');

	      $this->Mmpnwn->set_additional_join_wl_aktif = TRUE;
	      $this->Mmpnwn->ins = NULL;
	      $this->Mmpnwn->is_lhkpn_offline = TRUE;
	      $this->Mmpnwn->is_tracking_lhkpn = TRUE;

				$response = $this->Mmpnwn->load_page_PL_AKTIF(NULL, $this->offset, $this->CARI, $this->limit, "", 1, TRUE);

	      $this->data['CARI'] = @$this->CARI;
	      $this->data['total_rows'] = intval($response->total_rows);
	      $this->data['offset'] = @$this->offset;
	      $this->data['items'] = $response->result;
	      $this->data['start'] = @$this->offset + 1;
	      $this->data['end'] = @$this->offset + @$this->end;

	      $this->total_rows = intval($response->total_rows);
	      $this->limit = 5;

	      $this->data['pagination'] = call_user_func('ng:)[0];:genPagination');
	      $this->Mmpnwn->is_tracking_lhkpn = FALSE;
	      $this->load->view("eaudit/korespondensi/korespondensi_hasilcaripn", $this->data);
	  }

  /**
   * Penandatangan
   *
   * @return json
   */
  public function get_kontak() {
      $q = $_GET['q'];
      $result = $this->get_data_all('t_user', null, "is_active = '1' and (ID_ROLE like '%27%' or ID_ROLE like '%25%' or ID_ROLE like '%24%')", 'ID_USER, NAMA', "NAMA LIKE '%$q%'",array('NAMA', 'ASC'), null, '15', null);
			$res = [];
      foreach ($result as $row) {
          $res[] = ['id' => $row->ID_USER, 'name' => $row->NAMA];
      }
      $data = ['item' => $res];
      echo json_encode($data);
      exit;
  }
  public function get_selected_kontak($kontak) {
    $where = ['IS_ACTIVE' => '1', 'ID_USER' => $kontak];
    $result = $this->get_data_all('t_user', null, $where, 'ID_USER, NAMA', null, array('NAMA', 'ASC'), null, '15', null);
    $res = [];
    foreach ($result as $row) {
        $res[] = ['id' => $row->ID_USER, 'name' => $row->NAMA];
    }
    echo json_encode($res);
      exit;
  }
  public function get_daftar_surat_tugas($id_user = NULL)
  {
		$role_audit = $this->session->userdata('ID_ROLE_AUDIT');
      $q = $_GET['q'];
    
			$where = ['is_active' => '1'];
			$result = $this->get_data_all('t_lhkpn_audit', null, $where, 'id_audit, nomor_surat_tugas', "nomor_surat_tugas LIKE '%$q%' AND status_periksa < 3", array('nomor_surat_tugas', 'ASC'), null, '15', 'nomor_surat_tugas');
      $res = [];
      foreach ($result as $row) {
        if($row->nomor_surat_tugas != "")
        {
          $res[] = ['id' => $row->nomor_surat_tugas, 'name' => $row->nomor_surat_tugas];
        }
      }
      if(sizeof($res) == 0) {
          $res[] = ['id' => 0, 'name' => '--'];
      }
      $data = ['item' => $res];
      echo json_encode($data);
      exit;
  }
  /**
   * Instansi Tujuan Surat
   *
   * @return json Instansi
   */
  public function getInstansiTujuan($id = NULL) {
      if (is_null($id)) {

          $not_using_default_value = $this->input->get('nudv');
          $setdefault_to_null = $this->input->get('setdefault_to_null');

          $q = $_GET['q'];
          $where = ['IS_ACTIVE' => '1'];
          $result = $this->get_data_all('m_org', null, $where, 'ORG_KD, ORG_TIPE', "ORG_TIPE LIKE '%$q%'", array('ORG_TIPE', 'ASC'), null, '15', null);
          $res = [];
          if (!$not_using_default_value || $setdefault_to_null) {
              $res[] = $this->__getDefaultValueSelect2("-- Pilih Instansi --");
          }
          foreach ($result as $row) {
              $res[] = ['id' => $row->ORG_KD, 'name' => strtoupper($row->ORG_TIPE)];
          }
          $data = ['item' => $res];
          echo json_encode($data);
      } else {
        $where = ['IS_ACTIVE' => '1', 'ORG_KD' => $id];
        $result = $this->get_data_all('m_org', null, $where, 'ORG_KD, ORG_TIPE', null, null, null, '15', null);
        $res = [];
        foreach ($result as $row) {
            $res[] = ['id' => $row->ORG_KD, 'name' => strtoupper($row->ORG_TIPE)];
        }
        echo json_encode($res);
      }
      exit;
  }
  public function getNamaInstansiTujuan($jenisInstansi = NULL, $id = NULL) {
      $res = [];
      if (is_null($id)) {
          $res[] = ['id' => '', 'name' => "-- All --"];
          $q = $_GET['q'];

          $where = ['IS_ACTIVE' => '1', 'ORG_KD' => $jenisInstansi];
          $result = $this->get_data_all('m_org_tujuan', null, $where , 'ORG_KD, ORG_TUJUANKD, ORG_NAMA', "ORG_NAMA LIKE '%$q%'", ['ORG_NAMA', 'asc'], null, null, null);

          foreach ($result as $row) {
              $res[] = ['id' => $row->ORG_TUJUANKD, 'name' => strtoupper($row->ORG_NAMA)];
          }

          $data = ['item' => $res];
          echo json_encode($data);
      } else {
          $where = ['IS_ACTIVE' => '1', 'ORG_TUJUANKD' => $id, 'ORG_KD' => $jenisInstansi];
          $result = $this->get_data_all('m_org_tujuan', NULL, $where , 'ORG_KD, ORG_TUJUANKD, ORG_NAMA', null, null, null, null);

          foreach ($result as $row) {
              $res[] = ['id' => $row->ORG_TUJUANKD, 'name' => strtoupper($row->ORG_NAMA)];
          }
          echo json_encode($res);
      }
      exit;
  }
  /**
   * Penandatangan
   *
   * @return json
   */
  public function getPenandatangan($id = NULL) {
      if (is_null($id)) {
          $not_using_default_value = $this->input->get('nudv');
          $setdefault_to_null = $this->input->get('setdefault_to_null');

          $q = $_GET['q'];
          $where = ['IS_ACTIVE' => '1'];
          $result = $this->get_data_all('m_surat_penandatangan', null, $where, 'PENANDATANGAN_ID, PENANDATANGAN_NAMA, PENANDATANGAN_JABATAN', "PENANDATANGAN_NAMA LIKE '%$q%'", array('PENANDATANGAN_NAMA', 'ASC'), null, '15');
          $res = [];
          if (!$not_using_default_value || $setdefault_to_null) {
              $res[] = $this->__getDefaultValueSelect2("-- Pilih --");
          }
          foreach ($result as $row) {
              $res[] = ['id' => $row->PENANDATANGAN_ID, 'name' => strtoupper($row->PENANDATANGAN_NAMA)];
          }
          $data = ['item' => $res];
          echo json_encode($data);
      } else {
          $where = ['IS_ACTIVE' => '1', 'PENANDATANGAN_ID' => $id];

          $result = $this->get_data_all('m_surat_penandatangan', null, $where, 'PENANDATANGAN_ID, PENANDATANGAN_NAMA, PENANDATANGAN_JABATAN', null, null, null, '15');
          $res = [];
          foreach ($result as $row) {
              $res[] = ['id' => $row->PENANDATANGAN_ID, 'name' => strtoupper($row->PENANDATANGAN_NAMA)];
          }
          echo json_encode($res);
      }
      exit;
  }

  private function __getDefaultValueSelect2($textName = "-- Pilih --") {
      return ['id' => '', 'name' => $textName];
  }
  private function get_data_all($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL) {
      if (is_array($select)) {
          $this->db->select($select[0], $select[1])->from($table);
      } else {
          $this->db->select($select)->from($table);
      }

      if (!is_null($join)) {
          foreach ($join as $key_index => $rows) {
              if (!isset($rows['join'])) {
                  $rows['join'] = 'LEFT';
              }
              $this->db->join($rows['table'], $rows['on'], $rows['join']);
          }
      }

      (!is_null($order) ? $this->db->order_by($order[0], $order[1], @$order[2]) : '');
      (!is_null($tampil) ? $this->db->limit($tampil, $start) : '');
      (!is_null($where) ? $this->db->where($where) : '');
      (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
      (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

      $query = $this->db->get();
      if (is_object($query)) {
          $result = $query->result();
      } else {
          $result = array();
      }

      $this->db->flush_cache();

      return $result;
  }
}
