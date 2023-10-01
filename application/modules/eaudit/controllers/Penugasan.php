<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penugasan extends CI_Controller {

  public function __construct() {
    parent::__construct();

    // Load the todo model to make it available
    // to *all* of the controller's actions
    $this->load->model('eaudit/Penugasan_model');
    $this->load->model('Mglobal');
  }

  public function index()
  {
    // 1. Load the data:

    // 2. Make the data available to the view
    $data['test'] = 'test';
    $data['nama_instansi'] = $this->Penugasan_model->get_nama_instansi();
    $data['users_admin'] = $this->Penugasan_model->get_all_pemeriksa();
    $data['user_roles'] = $this->Penugasan_model->get_user_roles()[0]->ID_ROLE;

    // 3. Render the view:
    $this->load->view('penugasan_new/penugasan_index', $data);
  }

  public function ajax_list(){

    $list = $this->Penugasan_model->get_datatables();

    if($list == FALSE){
      //$data = array();
      $data = "";
      $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => $data,
      );

      echo json_encode($output);
    } else{


      $data = array();
      $no = $_POST['start'];
      foreach ($list as $penugasan) {
        $pelaporan = $this->Penugasan_model->count_pelaporan($penugasan->ID_LHKPN);
        $no++;
        $row = array();

        $jenis_penugasan = '';
        if ($penugasan->JENIS_LAPORAN == 4) {
          $jenis_penugasan = 'R';
        }
        elseif ($penugasan->JENIS_LAPORAN == 5) {
          $jenis_penugasan = 'P';
        }
        else {
          $jenis_penugasan = 'K';
        }

        $no_agenda = date('Y', strtotime($penugasan->tgl_lapor)).'/' .$jenis_penugasan. '/' . $penugasan->NIK . '/' . $penugasan->ID_LHKPN;
        if( $penugasan->status == 1 or $penugasan->status == 2 or $penugasan->status == 3 or $penugasan->status == 4 or $penugasan->status == 5 or $penugasan->status == 6 ){
          $btn_color = $penugasan->status_periksa == '' ? 'btn-success' : 'btn-info';
          $title = $penugasan->status_periksa == '' ? 'assign pemeriksaan' : 'PN sudah pernah ditugaskan';

          $row[] = '<button id="btn-penugasan"
          data-idlhkpn="'.$penugasan->ID_LHKPN.'"
          data-nama="'.$penugasan->NAMA.'"
          data-agenda="'.$no_agenda.'"
          data-jabatan="'.$penugasan->DESKRIPSI_JABATAN.'"
          data-lembaga="'.$penugasan->INST_NAMA.'"
          data-tglpengumuman="'.date( 'm F Y',strtotime($penugasan->tgl_kirim_final)).'"
          class="btn '.$btn_color.' btn-sm" title="'.$title.'">Pilih</button>'; // checklist button
        }else{
          $row[] = '';
        }

        if($penugasan->status == 1){
          $status_lhkpn = "Proses Verifikasi";
        }else if($penugasan->status == 2){
          $status_lhkpn = "Perlu Perbaikan";
        }else if($penugasan->status == 3){
          $status_lhkpn = "Terverifikasi Lengkap";
        }else if($penugasan->status == 4){
          $status_lhkpn = "Diumumkan Lengkap";
        }else if($penugasan->status == 5){
          $status_lhkpn = "Terverifikasi tidak lengkap";
        }else if($penugasan->status == 6){
          $status_lhkpn = "Diumumkan tidak lengkap";
        }

        $petugas_pemeriksa = explode(',', $penugasan->nama_pemeriksa);
        $u_pp = array_unique($petugas_pemeriksa);
        $res_pp = implode(',', $u_pp);
        
        if($penugasan->maksimal != ''){
          $data_audit = $this->Mglobal->get_by_id('t_lhkpn_audit', 'id_audit', $penugasan->maksimal);
          
        
        }
                       
        // if($penugasan->status_periksa == ''){
        if($penugasan->status_periksa == '' || $data_audit->is_active <> 1){
          $status_pemeriksaan = '';
          $status_penugasan = 'Belum ditugaskan';
        }
        else if($data_audit->status_periksa == 1 && $data_audit->jenis_penugasan == 0){
          $status_pemeriksaan = 'Penelahaan Baru';
          $status_penugasan = 'Ditugaskan kepada : <b>'.$res_pp.' ';
        }else if($data_audit->status_periksa == 1 && $data_audit->jenis_penugasan == 1){
          $status_pemeriksaan = 'Pemeriksaan Baru';
          $status_penugasan = 'Ditugaskan kepada : <b>'.$res_pp.' ';
        }
        else if($data_audit->status_periksa == 2 && $data_audit->jenis_penugasan == 0){
          $status_pemeriksaan = 'Proses Penelaahan <i class="fa fa-warning text-danger"></i>';
          $status_penugasan = 'Ditugaskan kepada : <b>'.$res_pp.' ';
        }
        else if($data_audit->status_periksa == 2 && $data_audit->jenis_penugasan == 1){
          $status_pemeriksaan = 'Proses Pemeriksaan <i class="fa fa-warning text-danger"></i>';
          $status_penugasan = 'Ditugaskan kepada : <b>'.$res_pp.' ';
        }
        else if($data_audit->status_periksa == 3 && $data_audit->jenis_penugasan == 1){
          $status_pemeriksaan = 'Pemeriksaan Selesai <i class="fa fa-check-circle text-success"></i>';
          $status_penugasan = 'Penugasan selesai oleh : <b>'.$res_pp.' ';
        }
        else if($data_audit->status_periksa == 3 && $data_audit->jenis_penugasan == 0){
          $status_pemeriksaan = 'Penelaahan Selesai <i class="fa fa-check-circle text-success"></i>';
          $status_penugasan = 'Penugasan selesai oleh : <b> '.$res_pp.' ';
        }

        // -- pre process buttons
        $btn_riwayat_penugasan = '<button class="btn btn-xs btn-primary btn-rw-penugasan" title="riwayat penugasan" data-id_lhkpn="'.$penugasan->ID_LHKPN.'" ><span class="fa fa-book"></span></button>';
        // -- end buttons
        if($pelaporan[0]->jumlah != 0){$aduan = '(Jumlah Pengaduan: '.$pelaporan[0]->jumlah.')<br>';}else{$aduan = '';}
        $row[] = $no;
        // $row[] = ->ID_LHKPN;
        $tgl_lahir = $penugasan->TANGGAL_LAHIR !== '' ? date( 'd-M-Y',strtotime($penugasan->TANGGAL_LAHIR)) : '-';
        $row[] = '<b>'.$penugasan->NAMA.'</b> <br>'.$aduan.'<small>'.$no_agenda.'<br><b>NHK:</b> '.($penugasan->NHK == '' ? '-' : $penugasan->NHK).'<br><b>Tgl Lahir:</b> '.$tgl_lahir.'</small>';  // no-agenda
        // $row[] = $penugasan->NAMA_JABATAN;
        $row[] = $penugasan->DESKRIPSI_JABATAN;
        $row[] = $penugasan->INST_NAMA;
        $row[] = date( 'd M Y',strtotime($penugasan->tgl_kirim_final)); // tanggal pengumuman
        $row[] = $status_lhkpn;//.' >> '.$penugasan->status_periksa.' - '.$penugasan->jenis_penugasan;
        $row[] = $status_penugasan; //status penugasan
        $row[] = $btn_riwayat_penugasan.'<br>'.$status_pemeriksaan; //status pemeriksaan
        $data[] = $row;
      }
      // dump($list);

      // data sample for testing
      // $data = array(
      //   ['6063', '2006-08-31', '3271065404590004', '1377', 'TRI WAHYU HARINI', 'KEPALA DINAS KESEHATAN', 'PEMERINTAH KABUPATEN BOGOR', "4"],
      //   ['9916', '2007-02-16', '3207032901680002', '32366', 'SYARIP HIDAYAT', 'HAKIM PENGADILAN AGAMA MUARA BULIAN', 'MAHKAMAH AGUNG', "4"] );

      // echo json_encode($output);

      $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => 20, //$this->Penugasan_model->count_all(),
        "recordsFiltered" => 20, //$this->Penugasan_model->count_filtered(),
        "data" => $data,
      );


      // echo "<pre>";
      // print_r($this->db->last_query());
      // echo "</pre>";
      // die();

      echo json_encode($output);
    }
  }

  public function savePenugasan()
  {
    $id_creator = $this->session->userdata(ID_USER);
    $nama_creator = $this->get_nama_pic($id_creator);
    $pemeriksa =  $this->input->post('iNamaPemeriksa');
    $lhkpn = $this->input->post('idnya');
    $pic = $this->input->post('iNamaPIC');
    $id_uk_eaudit = $this->input->post('iNamaUK');

    for ($i=0; $i < sizeof($lhkpn) ; $i++) {
      foreach($pemeriksa as $id_pemeriksa){
        $explode_periksa = explode("|",$id_pemeriksa);
        $data = array(
          'id_lhkpn' => $lhkpn[$i],
          'tgl_mulai_periksa' => $this->convert_to_date_mysql($this->input->post('iTanggalPenugasanAwal')),
          'tgl_selesai_periksa' => $this->convert_to_date_mysql($this->input->post('iTanggalPenugasanAkhir')),
          'id_pemeriksa' => $explode_periksa[0],
          // 'id_pemeriksa' => $id_pemeriksa,
          'id_creator' => $id_creator,
          //'id_pemeriksa' => implode(',', $this->input->post('iNamaPemeriksa')),
          'is_active' => 1,
          'status_periksa' => 1,
          'created_by' => $this->session->userdata('USERNAME'),
          'created_date' => date('Y-m-d H:i:s'),
          'updated_by' => $this->session->userdata('USERNAME'),
          'updated_date' => date('Y-m-d H:i:s'),
          'updated_ip' => $this->get_client_ip(),
          'nomor_surat_tugas' => $this->input->post('iNomorSuratTugas'),
          'jenis_penugasan' => $this->input->post('optionPenugasan'),
          'jenis_pemeriksaan' => 1,
          'id_pic' => $pic[$i],
          'id_uk_eaudit' => $id_uk_eaudit,
          'periode_pemeriksaan_awal' => $this->convert_to_date_mysql($this->input->post('TGL_PEMERIKSAAN_1')),
          'periode_pemeriksaan_akhir' => $this->convert_to_date_mysql($this->input->post('TGL_PEMERIKSAAN_2')),
          'ENCRYPT_IDLHKPN' => strtr(base64_encode($lhkpn[$i]), '+/=', '-_~')
        );
        $this->Penugasan_model->inputDataPenugasan($data);
      }

      $email_pic = $this->Mglobal->get_by_id('t_user', 'id_user', $pic[$i])->EMAIL;
      $nama_pic = $this->get_nama_pic($pic[$i]);
      $jenis = ($this->input->post('optionPenugasan') == 0) ? 'Penelaahan' : 'Pemeriksaan';
      $tgl_mulai = $this->convert_to_date_mysql($this->input->post('iTanggalPenugasanAwal'));
      $tgl_akhir = $this->convert_to_date_mysql($this->input->post('iTanggalPenugasanAkhir'));
      $no_surat = $this->input->post('iNomorSuratTugas');
      
      $hasil[] = array(
        'email' => $email_pic, 
        'nama_pic' => $nama_pic, 
        'jenis' => $jenis, 
        'tgl_mulai' => $tgl_mulai, 
        'tgl_akhir' => $tgl_akhir, 
        'no_surat' => $no_surat,
        'id_lhkpn' => $lhkpn[$i]
      );

    }
    
    foreach ($hasil as $item) {
      if (!isset($hasil2[$item['email']])) {
        $hasil2[$item['email']] = $item;
    } else {
        $hasil2[$item['email']]['id_lhkpn'] .= ',' . $item['id_lhkpn'];
      }
    }
    
    foreach ($hasil2 as $list) {
      $lhkpn = explode(',', $list['id_lhkpn']);
      $table_pn = "";
      $no = 1;
      foreach ($lhkpn as $id) {
        $selectInfo = 'T_PN.NAMA, T_PN.NIK, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, T_LHKPN.TGL_LAPOR, T_LHKPN.JENIS_LAPORAN, T_LHKPN.ID_LHKPN';
        $joinInfo = [
            ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN AND T_LHKPN_JABATAN.IS_PRIMARY = 1'],
            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
            ['table' => 'M_INST_SATKER', 'on' => 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],
            ['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'],
        ];
        $data_pn = $this->Mglobal->get_data_all('T_LHKPN', $joinInfo, NULL, $selectInfo, "T_LHKPN.ID_LHKPN = '$id'")[0];
        $jenis_penugasan = ($penugasan->JENIS_LAPORAN == 4) ? 'R' : (($penugasan->JENIS_LAPORAN == 5) ? 'P' : 'K');
        $no_agenda = date('Y', strtotime($data_pn->TGL_LAPOR)).'/' .$jenis_penugasan. '/' . $data_pn->NIK . '/' . $data_pn->ID_LHKPN;

        $table_pn .= '
          <tr>
            <td align="center">' . $no++ . '</td>
            <td>' . $data_pn->NAMA . '</td>
            <td>' . $data_pn->NAMA_JABATAN . '</td>
            <td>' . $data_pn->INST_NAMA . '</td>
            <td>' . $no_agenda . '</td>
          </tr>
        ';
      }
      $penerima_email = $list['email'];
      $subject = 'PIC Penugasan e-Audit';
      $pesan_valid = '
        <html>
        <head>
          <style>
            th {
              padding: 5px;
              vertical-align: text-middle;
              background-color: #2588ba;
              color: white;
            }
            td {
              padding: 5px;
              vertical-align: text-middle;
            }
            #table1 {
              width: 30%;
            }
            #table2, #table2 th, #table2 td {
              border: 1px solid black;
              border-collapse: collapse;
            }
          </style>
        </head>
        <body>
          <div>Yth. Sdr ' . $list['nama_pic'] . '</div>
          <br>
          <div>Bersama ini kami informasikan bahwa Anda mendapatkan penugasan baru dari aplikasi e-Audit sebagai PIC dengan rincian sebagai berikut:</div>
          <br>
          <div>
            <table id="table1">
              <tr>
                <td>Ditugaskan oleh</td>
                <td>:</td>
                <td>' . $nama_creator . '</td>
              </tr>
              <tr>
                <td>No Surat Tugas</td>
                <td>:</td>
                <td><b>' . $list['no_surat'] . '</b></td>
              </tr>
              <tr>
                <td>Jenis Penugasan</td>
                <td>:</td>
                <td><b>' . $list['jenis'] . '</b></td>
              </tr>
              <tr>
                <td>Tanggal Awal Penugasan</td>
                <td>:</td>
                <td>' . $list['tgl_mulai'] . '</td>
              </tr>
              <tr>
                <td>Tanggal Akhir Penugasan</td>
                <td>:</td>
                <td>' . $list['tgl_akhir'] . '</td>
              </tr>
            </table>
          </div>
          <br>
          <div>Adapun detail Penyelenggara Negara yang ditugaskan kepada Saudara adalah sebagai berikut.</div>
          <br>
          <div>
            <table id="table2" width="80%">
              <tr>
                <th width="3%">No.</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Lembaga</th>
                <th>No. Agenda</th>
              </tr>
              ' . $table_pn . '
            </table>
          </div>
          <br>
          <div>Silahkan mengakses aplikasi e-Audit menu Pemeriksaan untuk memulai pengerjaan penugasan tersebut.</div>
          <div>Atas kerjasama yang diberikan, Kami ucapkan terima kasih.</div>
          <br>
          <div>Direktorat Pendaftaran dan Pemeriksaan LHKPN</div>
          <div>--------------------------------------------------------------</div>
          <div>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id | elhkpn.kpk.go.id | Layanan LHKPN 198</div>
      ';
      $send = ng::mail_send($penerima_email, $subject, $pesan_valid);
    }

    if ($send) {
      echo 1;
    } else {
      echo 0;
    }
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

    private function get_nama_pic($id_pic) {
      $this->db->select('t_user.NAMA');
      $this->db->from('t_user');
      $this->db->where('id_user =', $id_pic);
      $query = $this->db->get();
      $res = $query->result();
      if (!empty($res)) {
        return $res[0]->NAMA;
      } else {
        return '-';
      }
    }

}

?>
