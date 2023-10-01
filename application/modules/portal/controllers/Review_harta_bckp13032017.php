<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Review_harta extends CI_Controller {

    function __Construct() {
        parent::__Construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
    }

    function index() {
        $options = array();
        $this->load->view('portal/filing/review_harta', $options);
//                $curl_data= 'SEND={"tujuan":"085640763677","isiPesan":"Kode Token Pengiriman LHKPN adalah xxxx","idModem":6}';
//                CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
    }

    function data_pribadi($ID_LHKPN, $OPTION) {

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->update('t_lhkpn', array('STATUS_SURAT_PERNYATAAN' => '' . $OPTION));

        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
        $data = $this->db->get('t_lhkpn_data_pribadi')->row();

        /* $date1 = $data->TGL_LAPOR;
          $date2 = date('Y-m-d');
          $diff = abs(strtotime($date2) - strtotime($date1));
          $years   = floor($diff / (365*60*60*24));

          $result = null;
          if((int)$years>5){
          $result = $data;
          } */



        header('Content-type: application/json');
        echo json_encode($data);
    }

    function surat_kuasa_pdf($ID_LHKPN, $OPTION = NULL) {
        date_default_timezone_set('Asia/Jakarta');
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
        $data = $this->db->get('t_lhkpn_data_pribadi')->row();
        $judul = strtoupper($data->NIK . '_' . substr($data->TGL_LAPOR, 0, 4));

        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $check = $this->db->get('t_lhkpn')->row();

        if ($check) {
            $SURAT_UMUMKAN = $this->load->view('filing/surat_kuasa', array('data' => $data, 'OPTION' => $OPTION), TRUE);
            $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('STATUS_SURAT_UMUMKAN' => $OPTION, 'SURAT_UMUMKAN' => $SURAT_UMUMKAN, 'CETAK_SURAT_UMUMKAN_TIME' => date('Y-m-d H:i:s')));
//    		ng::exportDataTo(array('data'=>$data),'pdf', 'filing/surat_kuasa', $judul.'.pdf');
            try {
                include_once APPPATH . 'third_party/TCPDF/tcpdf.php';
                $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
                $pdf->SetFont('dejavusans', '', 9);
                $pdf->AddPage();
                $pdf->writeHTML($SURAT_UMUMKAN, true, false, true, false, '');
                $pdf->lastPage();
                $pdf->Output('ikhtisar.pdf', 'I');
            } catch (Exception $e) {
                
            }
        }
    }

    function data_keluarga($ID_LHKPN, $INDEX, $OPTION = NULL) {

        date_default_timezone_set('Asia/Jakarta');

        if (!empty($OPTION)) {
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('STATUS_SURAT_UMUMKAN' => '' . $OPTION));
        }

        $this->db->select('t_lhkpn.*,CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN', FALSE);
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $LHKPN = $this->db->get('t_lhkpn')->row();

        $this->db->select('
			t_lhkpn.ID_PN,
			t_lhkpn.TGL_LAPOR,
			NULL AS ID,
			NULL AS ID_OLD,
			t_lhkpn_data_pribadi.NAMA_LENGKAP AS NAMA,
			t_lhkpn_data_pribadi.NIK,
			t_lhkpn_data_pribadi.TEMPAT_LAHIR,
			t_lhkpn_data_pribadi.TANGGAL_LAHIR,
			t_lhkpn_data_pribadi.ALAMAT_RUMAH,
			NULL AS HUBUNGAN,
			NULL STATUS_KELUARGA,
			CEIL(DATEDIFF(NOW(),t_lhkpn_data_pribadi.TANGGAL_LAHIR)/365)-1 AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN
		', FALSE);
        $this->db->where('t_lhkpn_data_pribadi.ID_LHKPN', $ID_LHKPN);
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
        $PN = $this->db->get('t_lhkpn_data_pribadi')->row();

        $this->db->select('
    		t_lhkpn.ID_PN,
    		t_lhkpn.TGL_LAPOR,
    		t_lhkpn_keluarga.ID_KELUARGA AS ID,
    		t_lhkpn_keluarga.ID_KELUARGA_LAMA AS ID_OLD,
    		t_lhkpn_keluarga.NAMA,
			t_lhkpn_keluarga.NIK,
    		t_lhkpn_keluarga.TEMPAT_LAHIR,
    		t_lhkpn_keluarga.TANGGAL_LAHIR,
    		t_lhkpn_keluarga.ALAMAT_RUMAH,
    		t_lhkpn_keluarga.HUBUNGAN,
    		t_lhkpn_keluarga.STATUS_CETAK_SURAT_KUASA AS STATUS_KELUARGA,
    		CEIL(DATEDIFF(NOW(),t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1 AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN
    	', FALSE);
        $this->db->group_by('t_lhkpn_keluarga.ID_KELUARGA');
        $this->db->order_by('t_lhkpn_keluarga.TANGGAL_LAHIR', 'ASC');
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->where('(CEIL(DATEDIFF(NOW(),t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1) >=17');
        $this->db->where_in('t_lhkpn_keluarga.HUBUNGAN', array('1', '2', '3'));

        if ($LHKPN->IS_COPY == '1' && (int) $LHKPN->TIME_LHKPN < 5) { // JIKA LAPORAN KE 2
            $this->db->where('t_lhkpn_keluarga.ID_KELUARGA_LAMA IS NULL');
        }

        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_keluarga.ID_LHKPN');
        $KELUARGA = $this->db->get('t_lhkpn_keluarga')->result();

        if ($PN->ALAMAT_RUMAH) {
            $alamat_pn = $pn->ALAMAT_RUMAH;
        } else {
            $alamat_pn = $pn->ALAMAT_NEGARA;
        }


        $temp = array();
        /* $temp[1] = array(
          'ID'=>NULL,
          'NAMA'=>$PN->NAMA,
          'TTL'=>$PN->TEMPAT_LAHIR.' - '.tgl_format($PN->TANGGAL_LAHIR),
          'NOMOR_KTP'=>$PN->NIK,
          'ALAMAT'=>$PN->ALAMAT_RUMAH,
          'LAST'=>'0',
          'NEXT'=>'KUASA_KELUARGA2(2,"#ModalKuasaKeluarga")',
          'UMUR'=>$PN->UMUR,
          'LIMA_TAHUN'=>'0',
          'STATUS_CETAK_SURAT_KUASA'=>'0'
          );
          $SURAT_KUASA = $this->load->view('filing/surat_kuasa2',array('data'=>$temp[1]),TRUE);
          $this->db->where('ID_LHKPN',$ID_LHKPN);
          $this->db->update('t_lhkpn',array('SURAT_KUASA'=>$SURAT_KUASA)); */


        $i_data = 1;
        $i = 2;

        foreach ($KELUARGA as $row) {
            if ($i_data == count($KELUARGA)) {
                $last = '1';
                $next = '';
            } else {
                $last = '0';
                $next = 'KUASA_KELUARGA2(' . ($i + 1) . ',"#ModalKuasaKeluarga")';
            }
            if ((int) $row->TIME_LHKPN > 5) {
                $LIMA_TAHUN = '1';
            } else {
                $LIMA_TAHUN = '0';
            }
            $temp[$i] = array(
                'ID' => $row->ID,
                'NAMA' => $row->NAMA,
                'TTL' => $row->TEMPAT_LAHIR . ' - ' . tgl_format($row->TANGGAL_LAHIR),
                'NOMOR_KTP' => $row->NIK,
                'ALAMAT' => $row->ALAMAT_RUMAH,
                'LAST' => $last,
                'NEXT' => $next,
                'UMUR' => $row->UMUR,
                'LIMA_TAHUN' => $LIMA_TAHUN,
                'STATUS_CETAK_SURAT_KUASA' => $row->STATUS_KELUARGA
            );

            $SURAT_KUASA = $this->load->view('filing/surat_kuasa2', array('data' => $temp[$i]), TRUE);
            $this->db->where('ID_KELUARGA', $row->ID);
            $this->db->update('t_lhkpn_keluarga', array('SURAT_KUASA' => $SURAT_KUASA));
            $i_data++;
            $i++;
        }



        $result = $temp[$INDEX];
        if ($INDEX == '1') {
            if ($LHKPN->IS_COPY == '1' && (int) $LHKPN->TIME_LHKPN < 5) {
                $result = $temp[2];
            }
        }

        header('Content-type: application/json');
        echo json_encode($result);
    }

    function info_harta($index) {
        $data = array();
        $data[4] = 'Data Harta Tidak Bergerak';
        $data[5] = 'Data Harta Bergerak Lain';
        $data[6] = 'Data Harta Bergerak';
        $data[7] = 'Data Harta Kas';
        $data[8] = 'Data Harta Surat Berharga';
        $data[9] = 'Data Harta Lainnya';
        return $data[$index];
    }

    function checklhkpn($ID_LHKPN, $INDEX = NULL) {

        $this->load->model('mlhkpn');

        $data = $this->mlhkpn->data_lhkpn();

        $i = 1;
        if ($INDEX) {
            $i = $INDEX + 1;
        }
        while ($i <= 13) {

            if ($i == 11) { // MODUL PENERIMAAN
                $this->db->select('SUM(PN+PASANGAN) AS JML', false);
                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $pnr = $this->db->get('t_lhkpn_penerimaan_kas2')->row();
                if ((int) $pnr->JML > 0) {
                    $check = TRUE;
                } else {
                    $check = FALSE;
                }
            } else if ($i == 2) {

                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $check_data = $this->db->get($data[$i]['table'])->result();
                $jum_jab = count($check_data);

                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $this->db->where('IS_PRIMARY', '1');
                $check_primary = $this->db->get($data[$i]['table'])->result();

                if (!$check_data) {
                    $data[$i]['notif'] = 'Data Jabatan belum diisi, apakah Anda yakin ? Bila Ya klik tombol Lanjutkan.';
                    $check = FALSE;
                } else if ($jum_jab > 1 && !$check_primary && $check_data) {
                    $data[$i]['notif'] = 'Data Jabatan utama belum dipilih, Isi data terlebih dahulu!';
                    $check = FALSE;
                } else if (!$check_data && !$check_primary) {
                    $data[$i]['notif'] = 'Data Jabatan belum diisi atau Anda belum memilih data Jabatan utama, apakah Anda yakin ? Bila Ya klik tombol Lanjutkan.';
                    $check = FALSE;
                } else {
                    $check = TRUE;
                }
            } else if ($i == 12) { // MODUL PENGELUARAN
                $this->db->select('SUM(JML) AS JML_PENGELUARAN', false);
                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $png = $this->db->get('t_lhkpn_pengeluaran_kas2')->row();
                if ((int) $png->JML_PENGELUARAN > 0) {
                    $check = TRUE;
                } else {
                    $check = FALSE;
                }
            } else if ($i >= 4 && $i <= 9) { // MODUL HARTA

                /**
                 * @deprecated since 07-maret-2017
                 * @author lahirwisada@gmail.com
                 */
                /*
                  $this->db->where('ID_LHKPN', $ID_LHKPN);
                  $this->db->where('IS_CHECKED', '1');
                  $this->db->or_where('ID_HARTA IS NULL');
                  $check_pilih = $this->db->get($data[$i]['table'])->result();
                 * 
                 */

                /**
                 * cek data lama ketika belum dilakukan aksi apapun
                 */
                $check_pilih = $this->mlhkpn->check_data_review_harta($data[$i]['table'], $ID_LHKPN);
                
//                if ($i == 5) {
//                    echo $this->db->last_query();
//                    exit;
//                }

                
                $check_data = $this->mlhkpn->check_data_review_harta($data[$i]['table'], $ID_LHKPN, TRUE);

//                if ($i == 5) {
//                    echo $this->db->last_query();
//                    exit;
//                }

//                $this->db->where('ID_LHKPN', $ID_LHKPN);
//                $check_data = $this->db->get($data[$i]['table'])->result();

                $title = $this->mlhkpn->info_harta($i);
                if (!$check_data) { // Data Kosong
//                    $data[$i]['notif'] = $title . ' belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.';
                    $data[$i]['notif'] = $title . ' belum diisi.';
                    $check = FALSE;
//                } else if ($check_data && !$check_pilih) {
                } else if ($check_data && $check_pilih > 0) {
                    $data[$i]['notif'] = $data[$i]['notif_status'];
                    $check = FALSE;
//                } else if (!$check_data && !$check_pilih) {
                } else if (!$check_data && $check_pilih > 0) {
                    $data[$i]['notif'] = $data[$i]['notif_status'];
                    $check = FALSE;
                } else {
                    $check = TRUE;
                }
            } else { // MODUL LAINNYA
                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $check = $this->db->get($data[$i]['table'])->result();
            }




            if (!$check) {
                header('Content-type: application/json');
                echo json_encode($data[$i]);
                break;
            }
            $i++;
        }
    }

    function harta() {
        $data = array();
        $data[1] = 't_lhkpn_harta_tidak_bergerak';
        $data[2] = 't_lhkpn_harta_bergerak';
        $data[3] = 't_lhkpn_harta_bergerak_lain';
        $data[4] = 't_lhkpn_harta_surat_berharga';
        $data[5] = 't_lhkpn_harta_kas';
        $data[6] = 't_lhkpn_harta_lainnya';
        $data[7] = 't_lhkpn_hutang';
        return $data;
    }

    function jumlah($ID_LHKPN) {
        $data = $this->harta();
        $result = array();
        foreach ($data as $z) {
            if ($z == 't_lhkpn_harta_kas') {
                $key = 'NILAI_EQUIVALEN';
            } else if ($z == 't_lhkpn_hutang') {
                $key = 'SALDO_HUTANG';
            } else {
                $key = 'NILAI_PELAPORAN';
            }
            $this->db->select_sum($key);
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            if ($z != 't_lhkpn_hutang') {
                $this->db->where('IS_PELEPASAN <> \'1\' ');
            }
            $this->db->where('IS_ACTIVE', '1');
            $hasil = $this->db->get($z)->result();
            if ($hasil[0]->$key) {
                $result[] = $hasil[0]->$key;
            } else {
                $result[] = 0;
            }
        }
//        exit;
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    function kirim_lhkpn($ID_LHKPN) {
        $usr_name = $this->session->userdata('USERNAME');
        $datapn = @$this->mglobal->get_data_all('T_USER', [
                    ['table' => 'T_PN', 'on' => 'T_PN.NIK = T_USER.USERNAME'],
                    ['table' => 'T_LHKPN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'],
                    ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA'],
                    ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID']
                        ], ['T_USER.IS_ACTIVE' => '1', 'T_LHKPN.ID_LHKPN' => $ID_LHKPN, 'IS_PRIMARY' => '1'], 'ID_USER, T_PN.NIK, T_USER.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_BIDANG.BDG_NAMA, T_LHKPN.JENIS_LAPORAN, T_LHKPN.TGL_LAPOR, T_PN.EMAIL')[0];

        $j_lap = ($datapn->JENIS_LAPORAN == 1) ? 'Khusus, Calon Penyelenggara' : ($datapn->JENIS_LAPORAN == 2) ? 'Khusus, Awal Menjabat' : ($datapn->JENIS_LAPORAN == 3) ? 'Khusus, Akhir Menjabat' : 'Periodik';


        $pesan_valid = '
           <html>
    <head>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                text-align: left;
                padding: 8px;
                border-bottom: 1px solid #ddd;
            }

            tr:nth-child(even){background-color: #f2f2f2}

            th {
                background-color: #4CAF50;
                color: white;
            }
            tr:hover{background-color:#f5f5f5}
        </style>
    </head>
    <body>
        <div>
            <div class="row">
                <div class="col-lg-12">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td><em><u>Alamat pengirim dari KPK</u></em></td>
                            <td> : <em><u>elhkpn@kpk.go.id</u></em></td>
                        </tr>
                        <tr>
                            <td width="22%"><em><u>Subject email</u></em></td>
                            <td width="78%"> :<em><u> Konfirmasi e-LHKPN telah berhasil dikirim</u></em></td>
                        </tr>
                    </table>

                    <div>Yth. Sdr ' . @$datapn->NAMA . '</div>
                </div>
            </div>

        </div>
        <div><br>
        </div>
        <div>' . @$datapn->INST_NAMA . '</div>
        <div><br>

        </div>
        <div>Di Tempat </div>
        <div>
            <br>        <br>        <br> 
            <div>Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terkonfirmasi masuk ke sistem kami dengan rincian sebagai berikut </div><br/><br/>
            <div> 
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="3%">1</td>
                        <td width="15%">Nama</td>
                        <td width="2%">:</td>
                        <td width="80%">' . @$datapn->NAMA . '</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>NIK</td>
                        <td>:</td>
                        <td>' . @$datapn->NIK . '</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>' . @$datapn->NAMA_JABATAN . '</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Jenis Laporan </td>
                        <td>:</td>
                        <td>' . @$j_lap . '</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Tahun Laporan </td>
                        <td>:</td>
                        <td>' . @date('Y', $datapn->TGL_LAPOR) . '</td>
                    </tr>
                </table> 
            </div>
        </div> <br>
        <div>  Laporan e-LHKPN Saudara akan kami verifikasi lebih lanjut.</div>
        <br>
        <div> Email ini tidak dapat digunakan sebagai tanda terima LHKPN, tanda terima akan diberikan apabila hasil verifikasi menyatakan laporan e-LHKPN Saudara dinyatakan lengkap.</div>
        <br><br><br><br>
        <div>  Terima Kasih</div>
        <br/><br/><br/>
        <div>  Direktorat Pendaftaran dan Pemeriksaan LHKPN</div><br/>
        <div>  -------------------------------------------------</div>
        <br/>
        <div> Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.</div><br/><br/><br/>
        <div>  Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</div>

    </div>

      
        ';

        if ($ID_LHKPN) {
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $curr_date = date('Y-m-d');
            $update = $this->db->update('t_lhkpn', array('STATUS' => '1', 'tgl_kirim_final' => $curr_date));
            if ($update) {
                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN');
                $pn = $this->db->get('t_lhkpn')->row();
                $tahun = substr($pn->TGL_LAPOR, 0, 4);

                $data = array(
                    'ID_PENGIRIM' => 1,
                    'ID_PENERIMA' => $this->session->userdata('ID_USER'),
                    'SUBJEK' => 'Registrasi e-LHKPN',
                    'PESAN' => $pesan_valid,
                    'FILE' => null,
                    'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
                    'IS_ACTIVE' => '1'
                );
                $send = $this->db->insert('T_PESAN_KELUAR', $data);
                if ($send) {
                    $data2 = array(
                        'ID_PENGIRIM' => 1,
                        'ID_PENERIMA' => $this->session->userdata('ID_USER'),
                        'SUBJEK' => 'Registrasi e-LHKPN',
                        'PESAN' => $pesan_valid,
                        'FILE' => null,
                        'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                        'IS_ACTIVE' => '1'
                    );
                    $send2 = $this->db->insert('T_PESAN_MASUK', $data2);
                }


                $this->session->set_flashdata('success_message', 'success_message');
                $this->session->set_flashdata('message', 'Data LHKPN Tahun <b>' . $tahun . '</b> atas nama <b>' . strtoupper($pn->NAMA) . '</b> berhasil di kirim.');
                redirect('portal/filing');
            }
        } else {
            redirect('portal/filing');
        }
    }

    function check_token() {
        $this->db->where('ID_LHKPN', $this->input->post('ID_LHKPN'));
        $this->db->where('TOKEN_PENGIRIMAN', strtolower($this->input->post('nomor_token')));
        $check = $this->db->get('t_lhkpn')->row();
        if ($check) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function create_token($ID_LHKPN) {
        /* $datapn = @$this->mglobal->get_data_all('T_USER', [
          ['table' => 'T_PN', 'on' => 'T_PN.NIK = T_USER.USERNAME'],
          ['table' => 'T_LHKPN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'],
          ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
          ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
          ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA'],
          ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID']
          ], ['T_USER.IS_ACTIVE' => '1', 'T_LHKPN.ID_LHKPN' => $ID_LHKPN, 'IS_PRIMARY' => '1'], 'ID_USER, T_PN.NIK, T_USER.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_BIDANG.BDG_NAMA, T_LHKPN.JENIS_LAPORAN, T_LHKPN.TGL_LAPOR, T_PN.EMAIL')[0]; */
        $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN);
        $random = createRandomPassword(5);
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $result = $this->db->update('t_lhkpn', array('TOKEN_PENGIRIMAN' => strtolower($random)));

//                $curl_data= 'SEND={"tujuan":"085640763677","isiPesan":"Kode Token Pengiriman LHKPN adalah xxxx","idModem":6}';
        $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"Kode Token Pengiriman LHKPN adalah ' . $random . '", "idModem":6}';
//                 //CallURLPage('http://192.168.2.39:3333/sendSMS?SEND={"idOutbox":20,"tujuan":"' . $datapn->NO_HP . '","isiPesan":"Kode Token Pengiriman LHKPN adalah '.$random.'", "idModem":"5", "jmlPesan":1}');
        CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);

        if ($result == '1') {
            echo "1";
        } else {
            echo "0";
        }
    }

    function surat_kuasa_pdf2($ID_LHKPN, $INDEX, $OPTION = NULL) {

        date_default_timezone_set('Asia/Jakarta');

        if (!empty($OPTION)) {
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('STATUS_SURAT_UMUMKAN' => '' . $OPTION));
        }

        $this->db->select('t_lhkpn.*,CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN', FALSE);
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $LHKPN = $this->db->get('t_lhkpn')->row();
        $this->db->select('
			t_lhkpn.ID_PN,
			t_lhkpn.TGL_LAPOR,
			NULL AS ID,
			NULL AS ID_OLD,
			t_lhkpn_data_pribadi.NAMA_LENGKAP AS NAMA,
			t_lhkpn_data_pribadi.NIK,
			t_lhkpn_data_pribadi.TEMPAT_LAHIR,
			t_lhkpn_data_pribadi.TANGGAL_LAHIR,
			t_lhkpn_data_pribadi.ALAMAT_RUMAH,
			NULL AS HUBUNGAN,
			NULL STATUS_KELUARGA,
			CEIL(DATEDIFF(NOW(),t_lhkpn_data_pribadi.TANGGAL_LAHIR)/365)-1 AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN
		', FALSE);
        $this->db->where('t_lhkpn_data_pribadi.ID_LHKPN', $ID_LHKPN);
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
        $PN = $this->db->get('t_lhkpn_data_pribadi')->row();

        $this->db->select('
    		t_lhkpn.ID_PN,
    		t_lhkpn.TGL_LAPOR,
    		t_lhkpn_keluarga.ID_KELUARGA AS ID,
    		t_lhkpn_keluarga.ID_KELUARGA_LAMA AS ID_OLD,
    		t_lhkpn_keluarga.NAMA,
			t_lhkpn_keluarga.NIK,
    		t_lhkpn_keluarga.TEMPAT_LAHIR,
    		t_lhkpn_keluarga.TANGGAL_LAHIR,
    		t_lhkpn_keluarga.ALAMAT_RUMAH,
    		t_lhkpn_keluarga.HUBUNGAN,
    		t_lhkpn_keluarga.STATUS_CETAK_SURAT_KUASA AS STATUS_KELUARGA,
    		CEIL(DATEDIFF(NOW(),t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1 AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN
    	', FALSE);
        $this->db->group_by('t_lhkpn_keluarga.ID_KELUARGA');
        $this->db->order_by('t_lhkpn_keluarga.TANGGAL_LAHIR', 'ASC');
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->where('(CEIL(DATEDIFF(NOW(),t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1) >=17');
        $this->db->where_in('t_lhkpn_keluarga.HUBUNGAN', array('1', '2', '3'));

        if ($LHKPN->IS_COPY == '1' && (int) $LHKPN->TIME_LHKPN < 5) { // JIKA LAPORAN KE 2
            $this->db->where('t_lhkpn_keluarga.ID_KELUARGA_LAMA IS NULL');
        }

        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_keluarga.ID_LHKPN');
        $KELUARGA = $this->db->get('t_lhkpn_keluarga')->result();

        if ($PN->ALAMAT_RUMAH) {
            $alamat_pn = $pn->ALAMAT_RUMAH;
        } else {
            $alamat_pn = $pn->ALAMAT_NEGARA;
        }


        $temp = array();
        $temp[1] = array(
            'ID' => NULL,
            'tgl_kirim' => $LHKPN->tgl_kirim,
            'NAMA' => $PN->NAMA,
            'TTL' => $PN->TEMPAT_LAHIR . ' - ' . tgl_format($PN->TANGGAL_LAHIR),
            'NOMOR_KTP' => $PN->NIK,
            'ALAMAT' => $PN->ALAMAT_RUMAH,
            'LAST' => '0',
            'NEXT' => 'KUASA_KELUARGA2(2,"#ModalKuasaKeluarga")',
            'UMUR' => $PN->UMUR,
            'LIMA_TAHUN' => '0',
            'STATUS_CETAK_SURAT_KUASA' => '0'
        );
        $SURAT_KUASA = $this->load->view('filing/surat_kuasa2', array('data' => $temp[1]), TRUE);
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->update('t_lhkpn', array('SURAT_KUASA' => $SURAT_KUASA));


        $i_data = 1;
        $i = 2;

        foreach ($KELUARGA as $row) {
            if ($i_data == count($KELUARGA)) {
                $last = '1';
                $next = '';
            } else {
                $last = '0';
                $next = 'KUASA_KELUARGA2(' . ($i + 1) . ',"#ModalKuasaKeluarga")';
            }
            if ((int) $row->TIME_LHKPN > 5) {
                $LIMA_TAHUN = '1';
            } else {
                $LIMA_TAHUN = '0';
            }
            $temp[$i] = array(
                'ID' => $row->ID,
                'tgl_kirim' => $LHKPN->tgl_kirim,
                'NAMA' => $row->NAMA,
                'TTL' => $row->TEMPAT_LAHIR . ' - ' . tgl_format($row->TANGGAL_LAHIR),
                'NOMOR_KTP' => $row->NIK,
                'ALAMAT' => $row->ALAMAT_RUMAH,
                'LAST' => $last,
                'NEXT' => $next,
                'UMUR' => $row->UMUR,
                'LIMA_TAHUN' => $LIMA_TAHUN,
                'STATUS_CETAK_SURAT_KUASA' => $row->STATUS_KELUARGA
            );

            $SURAT_KUASA = $this->load->view('filing/surat_kuasa2', array('data' => $temp[$i]), TRUE);
            $this->db->where('ID_KELUARGA', $row->ID);
            $this->db->update('t_lhkpn_keluarga', array('SURAT_KUASA' => $SURAT_KUASA));
            $i_data++;
            $i++;
        }

        $result = $temp[$INDEX];
        if ($INDEX == '1') {
            if ($LHKPN->IS_COPY == '1' && (int) $LHKPN->TIME_LHKPN < 5) {
                $result = $temp[2];
            }
        }

        $judul = 'LAMPIRAN4';
//		ng::exportDataTo(array('data'=>$result,'OPTION'=>$OPTION),'pdf', 'filing/surat_kuasa2', $judul.'.pdf');
        $html = $this->load->view('filing/surat_kuasa2', array('data' => $result, 'OPTION' => $OPTION), TRUE);
        try {
            include_once APPPATH . 'third_party/TCPDF/tcpdf.php';
            $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetFont('dejavusans', '', 9);
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->lastPage();
            $pdf->Output('ikhtisar.pdf', 'I');
        } catch (Exception $e) {
            
        }
        if ($result['ID']) {
            $this->db->where('ID_KELUARGA', $result['ID']);
            $this->db->update('t_lhkpn_keluarga', array(
                'STATUS_CETAK_SURAT_KUASA' => '1',
                'CETAK_SURAT_KUASA_TIME' => date('Y-m-d H:i:s')
            ));
        }
    }

    function SEND_SMS($ID_LHKPN) {
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_data_pribadi.ID_LHKPN = t_lhkpn.ID_LHKPN');
        $LHKPN = $this->db->get('t_lhkpn')->row();
    }

}
