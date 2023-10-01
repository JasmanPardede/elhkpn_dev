<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'third_party/phpword/vendor/autoload.php';

class Periksa extends CI_Controller{

  var $id_user = 0;

  public function __construct()
  { 
    parent::__construct();
    call_user_func('ng::islogin');
    //Codeigniter : Write Less Do More
    $this->load->model('eaudit/Periksa_model');
    // $this->load->model('Pemeriksaan_model');
    $this->load->model('eaudit/Kkp_model');
    $this->load->model('eaudit/Penugasan_model');
    $this->load->model('mglobal');
		$this->load->model('eaudit/Klarifikasi_model', 'klarifikasi');

    $this->load->helper(array('url','download'));
  }

  function index()
  {
    $id_user = $this->session->userdata('ID_USER');
    $data = array(
      'tahun_lapor' => $this->Periksa_model->get_list_tahun($id_user),
      'id_user' =>  $id_user,
      'user_data' => $this->session->userdata()
    );
    $data['users_admin'] = $this->Penugasan_model->get_all_pemeriksa();
    $this->load->view('periksa/periksa_index', $data);
  }

  public function hasil_klarifikasi()
  {
    $id_user = $this->session->userdata('ID_USER');
    $data = array(
      'tahun_lapor' => $this->Periksa_model->get_list_tahun($id_user),
      'id_user' =>  $id_user,
      'user_data' => $this->session->userdata()
    );
    $data['users_admin'] = $this->Penugasan_model->get_all_pemeriksa();
    $this->load->view('periksa/hasil_klafifikasi_page', $data);
  }  

  public function tgl_klarifikasi($id_lhkpn_prev)
  {
    echo "<pre>";
    print_r( $this->Periksa_model->get_tgl_klarifikasi($id_lhkpn_prev) );
    echo "</pre>";
  }

  public function ajax_list_periksa_hasil_klarif()
  { 
    // return null if the role is not Tim / Koordinator Pemeriksa
    $roles = explode(',', $this->session->userdata()['ID_ROLE_AUDIT']);

    if (in_array('24', $roles) || in_array('25', $roles) || in_array('100', $roles)) {
      $this->process_list_periksa(true);
    } else {
      $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => array(),
      );

      //output to json format
      echo json_encode($output);
      die();
    }
  }

  public function ajax_list_periksa()
  {

    // display($this->session->userdata()['ID_ROLE'] );
    // die();

    // return null if the role is not Tim / Koordinator Pemeriksa
    $roles = explode(',', $this->session->userdata()['ID_ROLE_AUDIT']);
    // echo "<pre>";
    // echo (in_array('24', $roles).' || '.in_array('25', $roles));
    // echo "<br>";
    // echo (!in_array('24', $roles) && !in_array('25', $roles));
    // echo "</pre>";
    // exit;

    if (in_array('24', $roles) || in_array('25', $roles) || in_array('100', $roles)) {
      $this->process_list_periksa();
    }
    else {
      $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => array(),
      );
      //output to json format
      echo json_encode($output);
      die();
    }
  }

  private function process_list_periksa($is_hasil_klarif=false)
  { 
    
    if($is_hasil_klarif==true){
        $list = $this->Periksa_model->get_datatables($this->session->userdata('ID_USER'), true);
    }else{
        $list = $this->Periksa_model->get_datatables($this->session->userdata('ID_USER'));
    }

    $data = array();
    $no = $_POST['start'];

    foreach ($list as $data_periksa) {
        $no++;
        $row = array();
        $tahun_lapor = date('Y', strtotime($data_periksa->tgl_lapor));

        // Konversi jenis laporan
        $jenis_laporan = $this->get_jenis_laporan($data_periksa->JENIS_LAPORAN);

        // Konversi nama pic
        $nama_pic = $this->get_nama_pic($data_periksa->id_pic);
        $pelaporan = $this->Periksa_model->count_pelaporan($data_periksa->ID_LHKPN);
        if ($data_periksa->status_periksa == 1)
          $status_periksa = ($data_periksa->jenis_penugasan == 0) ? 'Penelaahan Baru' : 'Pemeriksaan Baru';
        else if ($data_periksa->status_periksa == 2)
          $status_periksa = ($data_periksa->jenis_penugasan == 0) ? 'Proses Penelaahan' : 'Proses Pemeriksaan';
        else if ($data_periksa->status_periksa == 3)
          $status_periksa = ($data_periksa->jenis_penugasan == 0) ? 'Penelaahan Selesai' : 'Pemeriksaan Selesai<br>( '.date('d-M-Y', strtotime($data_periksa->tgl_lhp)).' )';

        if ($is_hasil_klarif==true){
            $status_periksa = "";
        }

        // jenis pemeriksaan
        $label_jenis_pemeriksaan = '';
        switch ($data_periksa->jenis_pemeriksaan) {
          case '0':
            $label_jenis_pemeriksaan = '<span class="label label-success" style="margin-left: 5px">Terbuka</span>';
            break;
          case '1':
            // $label_jenis_pemeriksaan = ' - Tertutup';
            $label_jenis_pemeriksaan = '<span class="label label-default" style="margin-left: 5px">Tertutup</span>';
            break;

          default:
            $label_jenis_pemeriksaan = '';
            break;
        }
        // jenis penugasan
        if ($data_periksa->jenis_penugasan == 0)
          $label_penugasan = '<span class="label label-info">Penelaahan</span>';
        else if ($data_periksa->jenis_penugasan == 1)
          $label_penugasan = '<span class="label label-danger">Pemeriksaan</span>'.$label_jenis_pemeriksaan;

        //new notification
        $new_icon = ($data_periksa->status_periksa == 1) ? '<img src="'.base_url().'img/new2.gif" />' : '' ;









        
        // cek jika input tanggal ada isinya
        $new_lhkpn = $this->Periksa_model->get_new_lhkpn($data_periksa->ID_LHKPN);
        // echo $data_periksa->ID_LHKPN;
        // echo "<pre>";
        // print_r($new_lhkpn);
        // echo "</pre>";
        // die();

        $status_lhkpn = ''; // 0 = Draft, 1 = Masuk, 2 = Perlu Perbaikan, 3 = Terverifikasi Lengkap, 4 = Diumumkan Lengkap, 5=Terverifikasi tidak lengkap, 6=Diumumkan tidak lengkap, 7=ditolak
        switch ($data_periksa->status) {
          case '0':
            $status_lhkpn = 'Draft';
            break;
          case '1':
            $status_lhkpn = 'Proses Verifikasi';
            break;
          case '2':
            $status_lhkpn = 'Perlu Perbaikan';
            break;
          case '3':
            $status_lhkpn = 'Terverifikasi Lengkap';
            break;
          case '4':
            $status_lhkpn = 'Diumumkan Lengkap';
            break;
          case '5':
            $status_lhkpn = 'Terverifikasi Tidak Lengkap';
            break;
          case '6':
            $status_lhkpn = 'Diumumkan Tidak Lengkap';
            break;

          default:
            $status_lhkpn = '-';
            break;
        }

        if ($data_periksa->jenis_penugasan == 1 && $data_periksa->jenis_pemeriksaan == 0) {
          $status_klarifikasi = ''; // 0 = Draft, 1 = Masuk, 2 = Perlu Perbaikan, 3 = Terverifikasi Lengkap, 4 = Diumumkan Lengkap, 5=Terverifikasi tidak lengkap, 6=Diumumkan tidak lengkap, 7=ditolak
          switch ($data_periksa->status_klarifikasi) {
            case '0':
              $status_klarifikasi = '';//'Status Klarifikasi:<br><b>Draft</b>';
              break;
            case '1':
              $status_klarifikasi = '';//'Status Klarifikasi:<br><b>Proses Verifikasi</b>';
              break;
            case '2':
              $status_klarifikasi = '';//'Status Klarifikasi:<br><b>Perlu Perbaikan</b>';
              break;
            case '3':
              $status_klarifikasi = 'Status Klarifikasi:<br><b>Terverifikasi Lengkap</b>';
              break;
            case '4':
              $status_klarifikasi = 'Status Klarifikasi:<br><b>Diumumkan Lengkap</b>';
              break;
            case '5':
              $status_klarifikasi = 'Status Klarifikasi:<br><b>Terverifikasi Tidak Lengkap</b>';
              break;
            case '6':
              $status_klarifikasi = 'Status Klarifikasi:<br><b>Diumumkan Tidak Lengkap</b>';
              break;

            default:
              $status_klarifikasi = '';//'Status Klarifikasi:<br><b>-</b>';
              break;
          }
        } else {
          $status_klarifikasi = '';
        }
                
        // cek apakah status lhkpn = Terverifikasi lengkap || terverifikasi tidak lengkap || diumumkan lengkap || diumumkan tidak lengkap
        $isStatus = ($data_periksa->status == '3' || $data_periksa->status == '4' || $data_periksa->status == '5' || $data_periksa->status == '6') ? true : false; 
        $btn_input_tgl = (count($new_lhkpn) == 0 && $data_periksa->status_periksa != 1 && $isStatus) ? ( "<button title='Input tanggal klarifikasi' class='btn btn-primary btn-xs btn-input-tgl' href='".str_replace('http://', 'https://', site_url('index.php/eaudit/periksa/ajax_input_tanggal/')).'/'.$data_periksa->id_audit."' data-idlhkpn='".$data_periksa->ID_LHKPN."'  data-idaudit='".$data_periksa->id_audit."'><i class='fa fa-calendar' ></i></button>" ) : '';
        $btn_periksa = (count($new_lhkpn) > 0 && $data_periksa->status_klarifikasi < 3) ? "<a title='Input hasil klarifikasi' class='btn btn-success btn-xs btn-periksa' data-nost='".$data_periksa->nomor_surat_tugas."' data-idlhkpn='".strtr(base64_encode($data_periksa->ID_LHKPN), '+/=', '-_~')."' data-idaudit='".$data_periksa->id_audit."'><i class='fa fa-pencil' ></i></a>" : '';
        // $btn_bak = ($data_periksa->status_periksa != 1 && count($new_lhkpn) > 0) ? "<a title='Manajemen Dokumen' class='btn btn-info btn-xs btn-bak' data-idlhkpn='".strtr(base64_encode($data_periksa->ID_LHKPN), '+/=', '-_~')."'><i class='fa fa-cloud-upload' ></i></a>" : '';
        $btn_bak = "<a title='Manajemen Dokumen' class='btn btn-info btn-xs btn-bak' data-idaudit='".$data_periksa->id_audit."' data-idlhkpn='".strtr(base64_encode($data_periksa->ID_LHKPN), '+/=', '-_~')."'><i class='fa fa-cloud-upload' ></i></a>";
        $btn_progress = "<a class='btn btn-info btn-xs btn-progress' data-idaudit='".$data_periksa->id_audit."' data-idlhkpn='".strtr(base64_encode($data_periksa->ID_LHKPN), '+/=', '-_~')."'>Progress</a>";
        $isPenelaahan = ($data_periksa->jenis_penugasan == 0) ? true : false;
        $btn_lhp = ($data_periksa->status_periksa != 1) ? "<a title='Selesai pemeriksaan, Input tanggal LHP' class='btn btn-danger btn-xs btn-lhp btn-same' data-idlhkpn='".$data_periksa->ID_LHKPN."' data-idaudit='".$data_periksa->id_audit."' >Selesai</a>" : '';
        $btn_selesai_telaah = ($data_periksa->status_periksa != 1 && $isPenelaahan) ? "<a title='Selesai penelaahan, Input LT' class='btn btn-info btn-xs btn-lhp btn-same' data-idlhkpn='".$data_periksa->ID_LHKPN."' data-idaudit='".$data_periksa->id_audit."' data-isPenelaahan='".$isPenelaahan."'>Telaah</a>" : '';
        $btn_lhp_detail = "<a title='Detail' class='btn btn-default btn-xs btn-lhp btn-same' data-idaudit='".$data_periksa->id_audit."' data-idlhkpn='".$data_periksa->ID_LHKPN."' data-islhpdetail='1'>LHP</a>";
        $btn_view_klarifikasi = "<a id='KlarifikasiInfo' class='btn btn-info btn-xs btn-same btn-preview-klarif' data-nost='".$data_periksa->nomor_surat_tugas."' data-idlhkpn='".strtr(base64_encode($data_periksa->ID_LHKPN), '+/=', '-_~')."' data-idaudit='".$data_periksa->id_audit."' title='Lihat hasil Klarifikasi'>Preview</a>";
        $btn_jabatan = "<a id='JabatanInfo' class='btn btn-info btn-xs btn-same btn-preview-jabatan' data-idpn='".$data_periksa->ID_PN."' title='Lihat Jabatan' onclick='btnInfoJabatanOnClick(this);'>jabatan</a>";
        $btn_hasil_analisis = "<a class='btn btn-primary btn-xs btn-hasil-analisis' data-idaudit='".$data_periksa->id_audit."'>Hasil Analisis</a>";

        $periode_periksa = date('d-M-Y', strtotime($data_periksa->tgl_mulai_periksa)).'<br>s.d.<br>'.date('d-M-Y', strtotime($data_periksa->tgl_selesai_periksa));

        // Cek jika pemeriksaaan mendekati tanggal selesai
        $my_date = time() - strtotime($data_periksa->tgl_selesai_periksa);
        $date1  = date_create($data_periksa->tgl_selesai_periksa);
        $date2  = date_create();
        $diff   = date_diff($date1,$date2);
        // $is_kadaluarsa = $diff->m == 0 && $diff->d <=7 ? 1 : 0;

        $label_kadaluarsa = '';
        if ($date2 >= $date1) {
          $label_kadaluarsa = '<span class="label label-danger" title="pemeriksaan melewati tanggal penugasan"><i class="fa fa-warning"></i></span><b> +'.$diff->days.' hari</b>';
        }
        else if ($diff->days <= 7) {
            $label_kadaluarsa = '<span class="label label-warning" title="pemeriksaan mendekati akhir tanggal penugasan"><i class="fa fa-warning"></i></span> <b>-'.$diff->days.' hari</b>';
        }
        // cek jika tugas sudah Selesai
        $label_kadaluarsa = ($data_periksa->status_periksa == 3) ? '' : $label_kadaluarsa;

        // btn-cetak kkp
        $btn_kkp2 = '<a class="btn btn-xs btn-modal-kkp btn-default" data-idlhkpn="'.$data_periksa->ID_LHKPN.'" data-idaudit="'.$data_periksa->id_audit.'">Semua KKP</a>';
        $btn_ikhtisar = '<a class="btn btn-xs btn-modal-ikhtisar btn-success" title="cetak ikhtisar" data-idlhkpn="'.$data_periksa->ID_LHKPN.'" data-idaudit="'.$data_periksa->id_audit.'"><i class="fa fa-print"></i></a>';
        $btn_pelaporan = ($pelaporan == 0) ? '' : '<a style="background: #fd8f62;" class="btn btn-xs btn-modal-pelaporan btn-default" data-idlhkpn="'.$data_periksa->ID_LHKPN.'" data-idaudit="'.$data_periksa->id_audit.'">Pengaduan</a>';

        $btn_ikhtisar_klarif = '<a class="btn btn-xs btn-modal-ikhtisar-klarif btn-primary" title="cetak ikhtisar" data-idlhkpn="'.$new_lhkpn[0]->ID_LHKPN.'" data-idaudit="'.$data_periksa->id_audit.'"><i class="fa fa-print"></i></a>';
        $btn_draft = '<a class="btn btn-xs btn-modal-draft btn-success" title="cetak draft surat" data-idlhkpn="'.$data_periksa->ID_LHKPN.'" data-idaudit="'.$data_periksa->id_audit.'"><i class="fa fa-download"></i></a>';
	$btn_resend_email = '<a class="btn btn-xs btn-modal-resend btn-info" title="kirim ulang email" data-idlhkpn="'.$data_periksa->ID_LHKPN.'" data-idaudit="'.$data_periksa->id_audit.'"><i class="fa fa-send"></i></a>';
   
        // simpan ke dalam row untuk data tables
        $row[] = $no.$new_icon;
        $row[] = '<b>'.$data_periksa->NAMA_LENGKAP.'</b><br><small>'.$tahun_lapor.'/'.$jenis_laporan.'/'.$data_periksa->NIK.'/'.$data_periksa->ID_LHKPN.'<br>'.$label_penugasan.'<br><b>Status LHKPN:</b> '.$status_lhkpn.'<br>'.$btn_jabatan.'</small>';
        $row[] = $data_periksa->DESKRIPSI_JABATAN; //.' -->> '.count($new_lhkpn).' | '.$data_periksa->jenis_pemeriksaan;
        $row[] = $data_periksa->INST_NAMA;
        $row[] = $nama_pic;
        $row[] = '<small>'.$periode_periksa.'<br>'.$label_kadaluarsa.'</small>';
        // $row[] = '<small>'.(($data_periksa->jenis_penugasan == 0) ? '-' : $periode_periksa).'<br>'.$is_kadaluarsa.'/ '.date('d', $sisa_d).'</small>';
        $row[] = '<small>'.$status_periksa.'<br><b>'.$data_periksa->nomor_surat_tugas.'</b><br>'.$status_klarifikasi.'</small>';

        if($is_hasil_klarif == false){
          if ($data_periksa->status_periksa >= 3) {
            if ($data_periksa->jenis_penugasan == 1) {
              $row[] = ''.$btn_bak.'<br>'.$btn_progress.'<br>'.$btn_lhp_detail.$btn_view_klarifikasi.$btn_hasil_analisis;
            }
            if ($data_periksa->jenis_penugasan == 0) {
              $row[] = ''.$btn_bak.'<br>'.$btn_progress.'<br>'.$btn_selesai_telaah;
            }
          } else {
            $row[] = ($data_periksa->jenis_penugasan == 1 ? $btn_input_tgl.$btn_periksa.$btn_bak.$btn_ikhtisar.($data_periksa->status_periksa == 2 ? $btn_hasil_analisis : '') : $btn_bak.$btn_ikhtisar).
              ($data_periksa->status_periksa == 2 ? $btn_progress : '').
              '<br><a id="btn-kkp-" class="btn-kkp btn btn-warning btn-xs btn-same" data-idlhkpn="'.$data_periksa->ID_LHKPN.'" data-idaudit="'.$data_periksa->id_audit.'" data-status="'.$data_periksa->status_periksa.'" data-namapn="'.$data_periksa->NAMA_LENGKAP.'" data-nost="'.$data_periksa->nomor_surat_tugas.'">Cetak KKP</a><br>'.
              $btn_kkp2.'<br>'.
              (($isPenelaahan) ? $btn_selesai_telaah.'<br>' : $btn_lhp ).$btn_pelaporan.(!$isPenelaahan && $data_periksa->status_periksa != 1 && $data_periksa->status_klarifikasi >= 3 ? $btn_view_klarifikasi : '');
            }
            $filter = $this->Periksa_model->count_filtered($this->session->userdata('ID_USER'));
        }else{
          $row[] = ''.$btn_ikhtisar_klarif.' '.$btn_draft
          . ($data_periksa->status_klarifikasi >= 3 ? ' ' . $btn_resend_email : '');
          $filter = $this->Periksa_model->count_filtered($this->session->userdata('ID_USER'), true);
        }

        // if ($data_periksa->status_periksa >= 3 && $data_periksa->jenis_penugasan == 1) {
        //     $row[] = ''.$btn_bak.'<br>'.$btn_lhp_detail.$btn_view_klarifikasi;
        // } else if ($data_periksa->status_periksa >= 3 && $data_periksa->jenis_penugasan == 0) {
        //   $row[] = ''.$btn_bak.'<br>'.$btn_selesai_telaah;
        // }
        // else {
        //   $row[] = ($data_periksa->jenis_penugasan == 1 ? $btn_input_tgl.$btn_periksa.$btn_bak.$btn_ikhtisar : $btn_bak.$btn_ikhtisar.$btn_progress).
        //     '<br><a id="btn-kkp-" class="btn-kkp btn btn-warning btn-xs btn-same" data-idlhkpn="'.$data_periksa->ID_LHKPN.'" data-idaudit="'.$data_periksa->id_audit.'" data-status="'.$data_periksa->status_periksa.'" data-namapn="'.$data_periksa->NAMA_LENGKAP.'" data-nost="'.$data_periksa->nomor_surat_tugas.'">Cetak KKP</a><br>'.
        //     $btn_kkp2.'<br>'.
        //     (($isPenelaahan) ? $btn_selesai_telaah.'<br>' : $btn_lhp ).$btn_pelaporan.(!$isPenelaahan && $data_periksa->status_periksa != 1 && $data_periksa->status_klarifikasi >= 3 ? $btn_view_klarifikasi : '');
        // }

        $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => ($is_hasil_klarif)?$this->Periksa_model->count_all_hasil_klarif():$this->Periksa_model->count_all(),
      "recordsFiltered" => $filter,
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function ajax_set_status_periksa($id_audit = NULL, $status = NULL, $id_lhkpn = NULL)
  {
    $id_audit = $this->input->post('id_audit');
    $id_lhkpn = $this->input->post('id_lhkpn');
    $status = $this->input->post('status');
    $no_st = $this->input->post('no_st');

    $is_updated = $this->Periksa_model->set_status_periksa($status, $id_lhkpn, $no_st);
    echo $is_updated;
  }

  public function ajax_input_tanggal($id_audit = NULL)
  {
    $data_pn = $this->Periksa_model->get_data_pn($id_audit)[0];
    $data_audit = $this->Periksa_model->get_data_audit($id_audit);

    // -- get data Penelaahan
    $data_latest_no_lt = $this->Periksa_model->get_latest_no_lt();
    $data_penelaahan = $this->Periksa_model->get_penelaahan($id_audit);
    // echo ">> ". $data_penelaahan->num_rows(). '<br>';

    // -- get data lhkpn pemeriksaan
    if (isExist($id_audit) == '---') {
      $id_lhkpn_pemeriksaan = $this->input->post('id_lhkpn_pemeriksaan');
      $join = [
        ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN AND T_PN.IS_ACTIVE = "1"'],
      ];
      $where = [
        'T_LHKPN.ID_LHKPN' => $id_lhkpn_pemeriksaan,
        'T_LHKPN.JENIS_LAPORAN' => '5',
      ];
      $data_pemeriksaan = $this->mglobal->get_data_all('T_LHKPN', $join, $where, 'T_LHKPN.ID_LHKPN, T_PN.NAMA, T_LHKPN.TGL_LAPOR, T_LHKPN.JENIS_LAPORAN, T_PN.NIK');

      $nama_pn = $data_pemeriksaan[0]->NAMA;
      $no_agenda = date('Y', strtotime($data_pemeriksaan[0]->TGL_LAPOR)).'/'.$this->get_jenis_laporan($data_pemeriksaan[0]->JENIS_LAPORAN).'/'.$data_pemeriksaan[0]->NIK.'/'.$data_pemeriksaan[0]->ID_LHKPN;
    } else {
      $nama_pn = $data_pn->NAMA_LENGKAP;
      $no_agenda = date('Y', strtotime($data_pn->tgl_lapor)).'/'.$this->get_jenis_laporan($data_pn->JENIS_LAPORAN).'/'.$data_pn->NIK.'/'.$data_pn->ID_LHKPN;
    }

    $data = array(
      'id_audit' => $id_audit,
      'id_lhkpn' => $data_pn->ID_LHKPN,
      'nama_pn' => $nama_pn,
      'jenis_penugasan' => $data_audit[0]->jenis_penugasan,
      'no_agenda' => $no_agenda,
      'is_lhp' => $this->input->post('is_lhp'),
      'is_penelaahan' => $this->input->post('is_penelaahan'),
      'no_st' => $data_audit[0]->nomor_surat_tugas,
      'nomor_lt_terakhir' => $data_latest_no_lt[0]->latest_num !== '' ? ($data_latest_no_lt[0]->latest_num + 1) : '1',
      'nomor_lt_dok' => ($data_penelaahan->num_rows() > 0) ? $data_penelaahan->result()[0]->nomor_lt_dok : '',
      'submited_at' => ($data_penelaahan->result()[0]->submited_at !== '') ? $data_penelaahan->result()[0]->submited_at : '',
      'rekomendasi_lt' => $data_penelaahan->result()[0]->rekomendasi !== '' ? $data_penelaahan->result()[0]->rekomendasi : '',
      'keterangan' => $data_penelaahan->result()[0]->keterangan !== '' ? $data_penelaahan->result()[0]->keterangan : '',
      'is_detail_lhp' => $this->input->post('is_detail_lhp'),
      'nomor_lhp' => ($data_audit[0]->nomor_lhp !== '') ? $data_audit[0]->nomor_lhp : '',
      'tgl_lhp' => ($data_audit[0]->tgl_lhp !== '') ? $data_audit[0]->tgl_lhp : '',
      'rekomendasi_lhp' => ($data_audit[0]->rekomendasi !== '') ? $data_audit[0]->rekomendasi : '',
      'keterangan_lhp' => ($data_audit[0]->keterangan !== '') ? $data_audit[0]->keterangan : '',
      'tgl_periode_awal_lhp' => ($data_audit[0]->periode_pemeriksaan_awal !== '') ? $data_audit[0]->periode_pemeriksaan_awal : '',
      'tgl_periode_akhir_lhp' => ($data_audit[0]->periode_pemeriksaan_akhir !== '') ? $data_audit[0]->periode_pemeriksaan_akhir : '',
    );
    $data['id_lhkpn_pemeriksaan'] = isExist($id_lhkpn_pemeriksaan);
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
    $this->load->view(strtolower(__CLASS__) . '/' . 'periksa_input_tanggal.php', $data);
  }

  public function update_tgl_klarifikasi($id_lhkpn_pemeriksaan) {
    $tgl_klarifikasi = $this->input->post('tgl_klarifikasi');
    $tgl_klarifikasi = implode('-', array_reverse(explode('/', $tgl_klarifikasi)));
    $posted_fields = array(
      'TGL_KLARIFIKASI' => $tgl_klarifikasi,
    );
    $update = $this->klarifikasi->update_data($posted_fields, $id_lhkpn_pemeriksaan, 'catatan_pemeriksaan');

    if ($update) {
      echo "1";
    }
    else{
      echo "0";
    }
  }


  public function ajax_create_new_lhkpn_pemeriksaan()
  {

    $id_lhkpn_lama = $this->input->get_post('id_lhkpn');
    $no_st = $this->input->get_post('no_st');
    $tgl_klarifikasi = $this->input->get_post('tgl_klarifikasi');
    $tgl_klarifikasi = implode('-', array_reverse(explode('/', $tgl_klarifikasi)));
    $id_audit = $this->input->get_post('id_audit');
    $ENCRYPT_IDLHKPN = strtr(base64_encode($id_lhkpn_lama), '+/=', '-_~');
    $is_exist = $this->Periksa_model->get_new_lhkpn($id_lhkpn_lama);

    if (empty($is_exist)) {

      // update jenis pemeriksaan 0 (terbuka)
      $tbk = $this->Periksa_model->set_jenis_pemeriksaan_tbk($id_lhkpn_lama, $no_st,$ENCRYPT_IDLHKPN); 

      // create new lhkpn copy record
      $data = $this->Periksa_model->copy_to_lhkpn($id_lhkpn_lama, $tgl_klarifikasi, $id_audit); 
      if ($data) {
        echo "1";
      }
      else{
        echo "0";
      }
    } else {
      // data sudah ada
      echo "-1";
    }
  }

  public function ajax_update_tgl_lhp()
  {

    $id_audit = $this->input->post('id_audit');
    $id_lhkpn = $this->input->post('id_lhkpn');
    $nomor_lhp = $this->input->post('nomor_lhp');
    $tgl_lhp = $this->input->post('tgl_lhp');
    $tgl_lhp = implode('-', array_reverse(explode('/', $tgl_lhp)));
    $rekomendasi = $this->input->post('rekomendasi');
    $keterangan = $this->input->post('keterangan');
//    $awal_periode = $this->input->post('awal_periode');
//    $awal_periode = implode('-', array_reverse(explode('/', $awal_periode)));
//    $akhir_periode = $this->input->post('akhir_periode');
//    $akhir_periode = implode('-', array_reverse(explode('/', $akhir_periode)));
    $no_st = $this->input->post('no_st');

    $posted_fields = array(
      'nomor_lhp' => $nomor_lhp,
      'tgl_lhp' => $tgl_lhp,
      'rekomendasi' => $rekomendasi,
      'keterangan' => $keterangan,
      'status_periksa' => 3,
//      'periode_pemeriksaan_awal' => $awal_periode,
//      'periode_pemeriksaan_akhir' => $akhir_periode,
      // 'jenis_pemeriksaan' => 1,
    );

    $data = $this->Periksa_model->update_tgl_lhp($id_audit, $posted_fields, $id_lhkpn, $no_st);

    echo $data;
  }

  public function ajax_modal_kkp($id_audit, $id_lhkpn) {
    $data_pn = $this->Periksa_model->get_data_pn($id_audit)[0];
    $data_audit = $this->Periksa_model->get_data_audit($id_audit);

    // echo "<pre>";
    // var_dump($data_audit);
    // echo "</pre>";
    // die();

    $data = array(
      'id_audit' => $id_audit,
      'id_lhkpn' => $data_pn->ID_LHKPN,
      'nama_pn' => $data_pn->NAMA_LENGKAP,
      'jenis_penugasan' => $data_audit[0]->jenis_penugasan,
      'no_agenda' => date('Y', strtotime($data_pn->tgl_lapor)).'/'.$this->get_jenis_laporan($data_pn->JENIS_LAPORAN).'/'.$data_pn->NIK.'/'.$data_pn->ID_LHKPN,
      'no_st' => $data_audit[0]->nomor_surat_tugas,
      'riwayat_lapor' => $this->Periksa_model->get_list_lhkpn($id_lhkpn),
      'status_periksa' => $data_audit[0]->status_periksa,
      'rekomendasi' => $data_audit[0]->rekomendasi,
      'keterangan' => $data_audit[0]->keterangan,
    );
    $this->load->view(strtolower(__CLASS__) . '/' . 'periksa_modal_kkp.php', $data);
  }

  public function ajax_modal_pelaporan($id_audit, $id_lhkpn) {
    $data_pn = $this->Periksa_model->get_data_pn($id_audit)[0];
    $data_pelaporan = $this->Periksa_model->get_data_pelaporan($id_lhkpn);
    $data = array(
      'id_audit' => $id_audit,
      'id_lhkpn' => $data_pn->ID_LHKPN,
      'nama_pn' => $data_pn->NAMA_LENGKAP,
      'agenda' => date('Y', strtotime($data_pn->tgl_lapor)).'/'.$this->get_jenis_laporan($data_pn->JENIS_LAPORAN).'/'.$data_pn->NIK.'/'.$data_pn->ID_LHKPN,
      'pelaporan' => $this->Periksa_model->get_list_pelaporan($id_lhkpn)
    );
    $this->load->view(strtolower(__CLASS__) . '/' . 'periksa_modal_pelaporan.php', $data);
  }

  public function ajax_modal_progress($id_audit) {
    $data_progress = $this->Periksa_model->get_data_audit_progress($id_audit);
    $data_progress_pedal = $this->Periksa_model->get_data_audit_progress($id_audit,true);
    $data = array(
      'id_audit' => $id_audit,
      'id_lhkpn' => $data_progress->id_lhkpn,
      'tgl_mulai_periksa' => $data_progress->tgl_mulai_periksa,
      'tgl_selesai_periksa' => $data_progress->tgl_selesai_periksa,
      'status_periksa' => $data_progress->status_periksa,
      'jenis_pemeriksaan' => $data_progress->jenis_pemeriksaan,
      'is_paparan_pimpinan' => $data_progress->is_paparan_pimpinan,
      'is_arahan_pimpinan' => $data_progress->is_arahan_pimpinan,
      'ID_AUDIT' => $data_progress->ID_AUDIT,
      'IS_CEK_TELUSUR' => $data_progress->IS_CEK_TELUSUR,
      'IS_CEK_SIPESAT' => $data_progress->IS_CEK_SIPESAT,
      'IS_VOUCHER_PEDAL' => $data_progress->IS_VOUCHER_PEDAL,
      'IS_CEK_LAPORAN' => $data_progress->IS_CEK_LAPORAN,
      'IS_DRAFT_LHKPN' => $data_progress->IS_DRAFT_LHKPN,
      'IS_PENDALAMAN' => $data_progress->IS_PENDALAMAN,
      'CREATED_BY' => $data_progress->CREATED_BY,
      'UPDATED_BY' => $data_progress->UPDATED_BY,
      'CREATED_AT' => $data_progress->CREATED_AT,
      'UPDATED_AT' => $data_progress->UPDATED_AT,
      'nama' => ucwords($data_progress->NAMA),
      'nama_pn' => ucwords($data_progress->NAMA_PN),
      'pedal_no_permintaan' => $data_progress_pedal->no_permintaan,
      'pedal_tgl_approval3' => $data_progress_pedal->tgl_approval3,
    );
    $this->load->view(strtolower(__CLASS__) . '/' . 'periksa_modal_progress.php', $data);
  }

  public function set_progress()
  {
    $params = array(
      'ID_AUDIT' => $this->input->post('id_audit'),
      'ID_LHKPN' => $this->input->post('lhkpn'),
      'IS_CEK_TELUSUR' => $this->input->post('telusur'),
      'IS_CEK_SIPESAT' => $this->input->post('sipesat'),
      'IS_VOUCHER_PEDAL' => $this->input->post('voucher_pedal'),
      'IS_CEK_LAPORAN' => $this->input->post('laporan'),
      'IS_DRAFT_LHKPN' => $this->input->post('draft_lhkpn'),
      'IS_PENDALAMAN' => $this->input->post('pendalaman'),
      'CREATED_AT' => $this->input->post('created_at')
    );

    if ($params['CREATED_AT']) {
      //do update
      $this->Periksa_model->update_data_audit_progress($params);
    } else {
      //do create
      $this->Periksa_model->create_data_audit_progress($params);
    }

    $log_activity = 'Ubah Data Pemeriksaan, ID_AUDIT = ' . $params['ID_AUDIT'];
    $log_activity .= ' ID_LHKPN = ' . $params['ID_LHKPN'];
    $log_activity .= ' Data = Telusur: ';
    $log_activity .= $params['IS_CEK_TELUSUR'] == 1 ? 'Ya' : 'Tidak';
    $log_activity .= ', Sipesat: ';
    $log_activity .= $params['IS_CEK_SIPESAT'] == 1 ? 'Ya' : 'Tidak';
    $log_activity .= ', Voucher: ';
    $log_activity .= $params['IS_VOUCHER_PEDAL'] == 1 ? 'Ya' : 'Tidak';
    $log_activity .= ', Cek Lapangan: ';
    $log_activity .= $params['IS_CEK_LAPORAN'] == 1 ? 'Ya' : 'Tidak';
    $log_activity .= ', Draft LHKPN: ';
    $log_activity .= $params['IS_DRAFT_LHKPN'] == 1 ? 'Ya' : 'Tidak,';
    $log_activity .= ', Pendalaman: ';
    $log_activity .= $params['CREATED_AT'] == 1 ? 'Ya' : 'Tidak';

    ng::logActivity($log_activity);

    echo "Progress permintaan telah diperbarui";
  }

  public function set_jenis_pemeriksaan()
  {
    // code...
    $id_audit = $this->input->post('id_audit');
    $id_lhkpn = $this->input->post('id_lhkpn');
    $no_st = $this->input->post('no_st');

    $posted_fields = array(
      'jenis_pemeriksaan' => 1,
    );

    $data = $this->Periksa_model->update_tgl_lhp($id_audit, $posted_fields, $id_lhkpn, $no_st);

    return $data;
  }

  // -- penelaahan
  public function ajax_download_lt()
  {
    $data = file_get_contents(site_url("./file/template/penelaahan/LembarTelaahan-Template.doc"));
    force_download('LembarTelaahan-Template.doc', $data);
  }

  public function count_harta($arr_harta) {

    // print_r($arr_harta);exit;

    $total_harta = 0;
    foreach ($arr_harta as $key => $harta) {

      $total_harta += $harta->NILAI_PELAPORAN + $harta->NILAI_EQUIVALEN + $harta->SALDO_HUTANG;
    }

    return $total_harta;
  }

  public function count_harta_pasangan($arr_obj)
  {
    // print_r($arr_obj);
    // code...
    // $harta_pn = json_decode($arr_obj[0]->NILAI_PENERIMAAN_KAS_PN);
    // $harta_pasangan = json_decode($arr_obj[0]->NILAI_PENERIMAAN_KAS_PASANGAN);
    // $pengeluaran_kas = json_decode($arr_obj[0]->NILAI_PENGELUARAN_KAS);

    // $total_pn = 0;
    // $total_pasangan = 0;
    // $total_pengeluaran = 0;

    // foreach ($harta_pn as $key_harta_pn => $sub_harta) {
    //   foreach ($sub_harta as $key_sub_harta => $h) {
    //     $val = floatval(str_replace(',', '.', str_replace('.', '', current($h))));
    //     $total_pn += $val;
    //   }
    // }
    // foreach ($harta_pasangan as $key_harta_pn => $sub_harta) {
    //   foreach ($sub_harta as $key_sub_harta => $h) {
    //     $val = floatval(str_replace(',', '.', str_replace('.', '', current($h))));
    //     $total_pasangan += $val;
    //   }
    // }
    // foreach ($pengeluaran_kas as $key_harta_pn => $sub_harta) {
    //   foreach ($sub_harta as $key_sub_harta => $h) {
    //     $val = floatval(str_replace(',', '.', str_replace('.', '', current($h))));
    //     $total_pengeluaran += $val;
    //   }
    // }

    // $total_harta_pasangan = $total_pn + $total_pasangan + $total_pengeluaran;
  
    foreach ($arr_obj as $value) {
      $total_penerimaan_pn += $value->NILAI_PENERIMAAN_KAS_PN;
      $total_penerimaan_ps += $value->NILAI_PENERIMAAN_KAS_PASANGAN;
      $total_pengeluaran2 += $value->NILAI_PENGELUARAN_KAS;
    }
    
    $total_harta_pasangan = $total_penerimaan_pn + $total_penerimaan_ps + $total_pengeluaran2;
    return $total_harta_pasangan;
  }

  public function ajax_lt($id_lhkpn, $id_audit)
  {
    // TODO: update cetak KKP, dengan ajax input post
    // $id_audit = $this->input->post('id_audit');
    // $id_lhkpn = $this->input->post('id_lhkpn');

    $data_penelaahan = $this->Periksa_model->get_penelaahan($id_audit)->result()[0];
    $data_pn = $this->Periksa_model->get_data_pn($id_audit)[0];
    $data_tanah_bangunan          = $this->Kkp_model->get_data_tanah_bangunan($id_lhkpn);
    $data_harta_bergerak          = $this->Kkp_model->get_data_bergerak($id_lhkpn);
    $data_harta_bergerak_lainnya  = $this->Kkp_model->get_data_bergerak_lainnya($id_lhkpn);
    $data_surat_berharga          = $this->Kkp_model->get_surat_berharga($id_lhkpn);
    $data_kas                     = $this->Kkp_model->get_data_kas($id_lhkpn);
    $data_harta_lainnya           = $this->Kkp_model->get_harta_lainnya($id_lhkpn);
    $data_hutang                  = $this->Kkp_model->get_data_hutang($id_lhkpn);
    $data_penerimaan_tunai        = $this->Kkp_model->get_data_penerimaan_tunai($id_lhkpn);
    $data_pengeluaran_tunai       = $this->Kkp_model->get_data_pengeluaran_tunai($id_lhkpn);

    $total_harta =
      ($this->count_harta($data_tanah_bangunan) +
      $this->count_harta($data_harta_bergerak) +
      $this->count_harta($data_harta_bergerak_lainnya) +
      $this->count_harta($data_surat_berharga) +
      $this->count_harta($data_kas) +
      $this->count_harta($data_harta_lainnya)) -
      $this->count_harta($data_hutang);

    $total_penghasilan = $this->count_harta_pasangan($data_penerimaan_tunai);
    $total_pengeluaran = $this->count_harta_pasangan($data_pengeluaran_tunai);

    // echo "> ".$total_harta.'<br>';
    // echo "> ".$total_penghasilan.'<br>';
    // echo "> ".$total_pengeluaran.'<br>';
    // die();

    $filename = './file/template/penelaahan/LembarTelaahan-Template.docx';

    try {
      $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($filename);
      // set values
      $templateProcessor->setValue('nolt', $data_penelaahan->nomor_lt_dok);
      $templateProcessor->setValue('tgl_lt', date('d-m-Y', strtotime($data_penelaahan->created_at)) );
      $templateProcessor->setValue('nama_pn', $data_pn->NAMA_LENGKAP);
      $templateProcessor->setValue('jabatan', $data_pn->DESKRIPSI_JABATAN);
      $templateProcessor->setValue('nhk', $data_pn->NHK);
      $templateProcessor->setValue('nik', $data_pn->NIK);
      $templateProcessor->setValue('thn_lapor', date('Y', strtotime($data_pn->tgl_lapor)));
      $templateProcessor->setValue('tgl_kirim_final', $data_pn->tgl_kirim_final);
      $templateProcessor->setValue('total_harta',  strrev(implode('.',str_split(strrev(strval($total_harta)),3))));
      $templateProcessor->setValue('total_penghasilan', strrev(implode('.',str_split(strrev(strval($total_penghasilan)),3))));
      // $templateProcessor->setValue('total_penghasilan', $total_penghasilan );
      $templateProcessor->setValue('total_pengeluaran', strrev(implode('.',str_split(strrev(strval($total_pengeluaran)),3))));
      // $templateProcessor->setValue('total_pengeluaran', $total_pengeluaran);

      // save to local
      $temp_name = 'temp_file_'.date('YmdHis');
      $temp_filename = APPPATH.'../uploads/'.$temp_name.'.docx';
      $templateProcessor->saveAs($temp_filename);

      $filename = $data_penelaahan->nomor_lt_dok.'__'.$data_pn->NAMA_LENGKAP.'_'.$data_pn->ID_LHKPN.'_'.date('Ymd').'.docx';
      $filename = str_replace('/', '.', $filename);
      // download
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.$filename);
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      // header('Content-Length: ' . filesize($temp_filename));
      flush();
      readfile($temp_filename);
      unlink($temp_filename);

    } catch (\Exception $e) {
      echo "[x] Error: <br>";
      echo "<pre>";
      print_r($e);
      echo "</pre>";
    }

    echo "--- EOF ---";

    // $templateProcessor->saveAs('MyWordFile.docx');
  }

  public function ajax_set_nolt()
  {
    $params = $this->input->post();
    $is_created = $this->Periksa_model->create_penelaahan($params);

    if ($is_created) {
      echo json_encode(array(
        'message' => 'Success, data inserted',
        'success' => true
      ));
    }

    echo json_encode(array(
      'message' => 'Error, insert data failed',
      'success' => false
    ));

  }

  public function update_lt()
  {
    // code...
    $params = $this->input->post();
    $is_updated = $this->Periksa_model->update_penelaahan($params);

    $is_updated_periksa = $this->Periksa_model->set_status_periksa('3',$params['id_lhkpn'],NULL,'0');

    if ($is_updated && $is_updated_periksa) {
      // set penelaahan selesai di t_lhkpn_audit
      return json_encode(array(
        'message' => 'Success, data is updated',
        'success'=> true
      ));
    }

    echo json_encode(array(
      'message' => 'Error, failed to save data',
      'success' => false
    ));
  }

  // private funcion ---------------
  private function get_jenis_laporan($jenis_laporan)
  {
    # code...
    if ($jenis_laporan == 4)
      return 'R';
    else if ($jenis_laporan == 5)
      return 'P';
    else
      return 'K';
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

  public function getJabatanPNPeriksa($id_pn) {
    $this->data = [];
    $this->db->select('T_PN.ID_PN, '
              . 'T_PN.NIK,'
              . 'T_PN.NAMA,'
              . 'JAB.NAMA_JABATAN N_JAB,'
              . 'SUK.SUK_NAMA N_SUK,'
              . 'UK.UK_NAMA N_UK,'
              . 'INTS.INST_NAMA,'
              . 'INTS.INST_SATKERKD,'
              . 'ID_STATUS_AKHIR_JABAT,'
              . 't_pn_jabatan.IS_WL,'
              . 't_pn_jabatan.tahun_wl, '
              . 't_pn_jabatan.ID', FALSE);
      $this->db->from('T_PN');
      $this->db->join('t_pn_jabatan', 't_pn_jabatan.ID_PN = T_PN.ID_PN', 'left');
      $this->db->join('M_JABATAN JAB', 't_pn_jabatan.ID_JABATAN = JAB.ID_JABATAN', 'left');
      $this->db->join('m_sub_unit_kerja SUK', 'SUK.SUK_ID = JAB.SUK_ID', 'left');
      $this->db->join('m_unit_kerja UK', 'UK.UK_ID = JAB.UK_ID', 'left');
      $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = JAB.INST_SATKERKD', 'left');
      $this->db->where('T_PN.ID_PN', $id_pn);
      $this->db->where('T_PN.IS_ACTIVE', 1);
      $this->db->where('t_pn_jabatan.IS_ACTIVE', 1);
      $this->db->where('t_pn_jabatan.IS_CURRENT', 1);
      $this->db->where('t_pn_jabatan.IS_DELETED', 0);
      $this->db->where('t_pn_jabatan.tahun_wl IS NOT NULL');
      $this->db->order_by('t_pn_jabatan.tahun_wl','asc');
      $query = $this->db->get();
      $this->items = $query->result();
      $this->data['getJabatan'] = $this->items;
      $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_detail_jabatan', $this->data);
//        echo json_encode($item);
//        exit;
  }  
  
  public function list_data_hasil_analisis($id_audit) {
    $roles = explode(',', $this->session->userdata()['ID_ROLE_AUDIT']);

    if (!in_array('24', $roles) && !in_array('25', $roles)) {
      show_error('Anda tidak memiliki akses untuk melakukan tindakan ini!', 401);
      exit;
    }

    $data = $this->Periksa_model->get_data_hasil_analisis_pemeriksaan($id_audit);
    $getIDLHKPN = $this->mglobal->get_data_by_id('t_lhkpn_audit','id_audit',$data->ID_AUDIT,false,true);
    $data = [
      'id_audit' => $data->ID_AUDIT,
      'id_lhkpn' => $data->ID_LHKPN,
      'nama' => ucwords($data->NAMA),
      'nama_pn' => ucwords($data->NAMA_PN),
      'nik_pn' => ucwords($data->NIK_PN),
      'deskripsi_jabatan' => ucwords($data->DESKRIPSI_JABATAN),
      'inst_nama' => ucwords($data->INST_NAMA),
      'is_paparan_pimpinan' => $data->is_paparan_pimpinan,
      'is_arahan_pimpinan' => $data->is_arahan_pimpinan,
      'keterangan_arahan_pimpinan' => $data->keterangan_arahan_pimpinan,
      'keterangan_hasil_analisis' => $data->keterangan_hasil_analisis,
      'keterangan_dasar_pemeriksaan' => $data->keterangan_dasar_pemeriksaan,
      'keterangan_dasar_pemeriksaan' => $data->keterangan_dasar_pemeriksaan,
    ];
    
    $data['icon'] = 'fa fa-list';
    $data['title'] = 'Hasil Analisis Pemeriksaan';
    $breadcrumbitem[] = ['Dashboard' => 'index.php'];
    $breadcrumbitem[] = ['Pemeriksaan' => 'index.php/eaudit/periksa/index'];
    $breadcrumbitem[] = ['Hasil Analisis Pemeriksaan' => '#'];
    $breadcrumbdata = [];
    foreach ($breadcrumbitem as $list) {
        $breadcrumbdata = array_merge($breadcrumbdata, $list);
    }
    $data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);
    
    $this->load->view(strtolower(__CLASS__).'/periksa_list_hasil_analisis', $data);
  }

  public function load_hasil_analisis($id_audit, $is_underlying_transaksi=false) {
    $roles = explode(',', $this->session->userdata()['ID_ROLE_AUDIT']);
  
    if (!in_array('24', $roles) && !in_array('25', $roles)) {
      show_error('Anda tidak memiliki akses untuk melakukan tindakan ini!', 401);
      exit;
    }

    $no = $_POST['start'];

    $data = $this->Periksa_model->get_data_hasil_analisis($id_audit, $is_underlying_transaksi, $no);
    $jumlah = $this->Periksa_model->count_data_hasil_analisis($id_audit, $is_underlying_transaksi);
    
      if ($jumlah > 0) {
        foreach ($data as $list) {
            $aaData[] = array(
              $no+++1,
                $list['hasil_analisis'],
                $list['mata_uang']['simbol'].' '.$list['nominal_string'],
                '<a href="#modal-tambah-hasil-analisis" data-id='.$list['id'].' id="btn-edit-hasil-analisis" class="btn btn-success btn-xs" data-toggle="modal" data-backdrop="static" data-keyboard="false" title="Edit Analisis"><i class="fa fa-pencil"></i></a>
                <a class="btn-hapus-hasil-analisis btn btn-danger btn-xs" data-id='.$list['id'].' title="Hapus Analisis"><i class="fa fa-trash"></i></a>'
            );
        }
    }
    else{
        $aaData = array();
    }
    
  $sOutput = array
    (
      "draw" => $_POST['draw'],
      "iTotalRecords" => $jumlah,
      "iTotalDisplayRecords" => $jumlah,
      "aaData" => $aaData
    );

    header('Content-Type: application/json');
    echo json_encode($sOutput);
  }

  public function get_hasil_analisis_by_id() {
    $roles = explode(',', $this->session->userdata()['ID_ROLE_AUDIT']);

    if (!in_array('24', $roles) && !in_array('25', $roles)) {
      show_error('Anda tidak memiliki akses untuk melakukan tindakan ini!', 401);
      exit;
    }

    $id = $this->input->post('id_hasil_analisis');

    if(!empty($id)){
      try{
        $data = $this->Periksa_model->get_data_hasil_analisis_by_id($id);
        $response = [
          'success' => true,
          'data' => $data
        ];
      }catch(\Exception $e){
        $response = [
          'success' => false,
          'data' => null
        ];
      }
    }
    
    echo json_encode($response);
    exit;
  }
  
  public function update_hasil_analisis_pemeriksaan() {
    $roles = explode(',', $this->session->userdata()['ID_ROLE_AUDIT']);

    if (!in_array('24', $roles) && !in_array('25', $roles)) {
      show_error('Anda tidak memiliki akses untuk melakukan tindakan ini!', 401);
      exit;
    }
    
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');

    $this->form_validation->set_rules('id_audit', 'ID Audit', 'trim|required|xss_clean|integer');
    $this->form_validation->set_rules('paparan_pimpinan', 'Paparan Pimpinan', 'trim|required|xss_clean|in_list[0,1]');
    $this->form_validation->set_rules('arahan_pimpinan', 'Arahan Pimpinan', 'trim|required|xss_clean|in_list[0,1]');
    $this->form_validation->set_rules('keterangan_arahan_pimpinan', 'Keterangan Arahan Pimpinan', 'trim|callback_required_keterangan_arahan_pimpinan|xss_clean');
    $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|xss_clean');
    $this->form_validation->set_rules('keterangan_dasar_pemeriksaan', 'Keterangan Dasar Pemeriksaan', 'trim|xss_clean');

    $this->form_validation->set_message('required', '{field} wajib diisi.');

    $response = [];

    if ($this->form_validation->run() == false) {
      $response = [
        'success' => false,
        'message' => 'Data Hasil Analisis Pemeriksaan tidak valid, mohon dicek kembali.',
        'error_messages' => $this->form_validation->error_array()
      ];
    } else {
      $id_audit = $this->input->post('id_audit', true);

      try {
        $this->Periksa_model->update_data_hasil_analisis_pemeriksaan([
          'id_audit' => $id_audit,
          'is_paparan_pimpinan' => $this->input->post('paparan_pimpinan', true),
          'is_arahan_pimpinan' => $this->input->post('arahan_pimpinan', true),
          'keterangan_arahan_pimpinan' => $this->input->post('keterangan_arahan_pimpinan', true),
          'keterangan_hasil_analisis' => $this->input->post('keterangan', true),
          'keterangan_dasar_pemeriksaan' => $this->input->post('keterangan_dasar_pemeriksaan', true),
          'ENCRYPT_IDLHKPN' => strtr(base64_encode($this->input->post('id_lhkpn', true)), '+/=', '-_~')
        ]);

        $response = [
          'success' => true,
          'message' => 'Data Hasil Analisis Pemeriksaan berhasil disimpan.'
        ];
      } catch (\Exception $e) {
        $response = [
          'success' => false,
          'message' => 'Data Hasil Analisis Pemeriksaan gagal disimpan.'
        ];
      }
    }

    echo json_encode($response);

    exit;
  }

  public function save_hasil_analisis_pemeriksaan(){
    $roles = explode(',', $this->session->userdata()['ID_ROLE_AUDIT']);

    if (!in_array('24', $roles) && !in_array('25', $roles)) {
      show_error('Anda tidak memiliki akses untuk melakukan tindakan ini!', 401);
      exit;
    }

    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');

    $this->form_validation->set_rules('hasil_analisis','Hasil Analisis','required|trim|xss_clean');
    $this->form_validation->set_rules('mata_uang','Mata Uang','callback_required_mata_uang');
    $this->form_validation->set_rules('nominal','Nominal','trim|xss_clean|callback_required_nominal');
    $this->form_validation->set_message('required', '{field} wajib diisi.');

    $response = [];

    if ($this->form_validation->run() == false) {
      $response = [
        'success' => false,
        'message' => 'Data Hasil Analisis Pemeriksaan tidak valid, mohon dicek kembali.',
        'error_messages' => $this->form_validation->error_array()
      ];
    } else {
      $data_hasil_analisis = [
        'ID_MATA_UANG' => $this->input->post('mata_uang')['id'],
        'NILAI_TRANSAKSI' => $this->input->post('nominal'),
        'IS_UNDERLYING_TRANSAKSI' => $this->input->post('underlying_transaksi'),          
        'HASIL_ANALISIS' => $this->input->post('hasil_analisis'),
      ];

      try{
        $id_hasil_analisis =  $this->input->post('id_hasil_analisis'); 
        
        if(empty($id_hasil_analisis)){ // add new data
          $data_hasil_analisis['ID_AUDIT'] =  $this->input->post('id_audit');
          $this->Periksa_model->create_hasil_analisis($data_hasil_analisis);
        }else{ // update data
          $this->Periksa_model->update_hasil_analisis($id_hasil_analisis, $data_hasil_analisis);
        }

        $response = [
          'success' => true,
          'message' => 'Data Hasil Analisis Pemeriksaan berhasil disimpan.'
        ];

      }catch (\Exception $e) {
        $response = [
          'success' => false,
          'message' => 'Data Hasil Analisis Pemeriksaan gagal disimpan.'
        ];
      }
    }
  
    echo json_encode($response);
    exit;
  }

  public function hapus_data_hasil_analisis(){
      $id = $this->input->post('id');

      if(!empty($id)){
        try{
          $this->Periksa_model->soft_delete_hasil_analisis($id);
          $response = [
            'success' => true,
            'message' => 'Data Hasil Analisis Pemeriksaan berhasil dihapus.'
          ];
        }catch(\Exception $e){
          $response = [
            'success' => false,
            'message' => 'Data Hasil Analisis Pemeriksaan gagal dihapus.'
          ];
        }
      }

      echo json_encode($response);
      exit;
  }

  public function required_nominal(){
    if (!$_POST['nominal'] || !is_numeric($_POST['nominal'])){
      $this->form_validation->set_message('required_nominal', "Nominal wajib diisi dan harus berupa bilangan bulat.");
      return false;
    }
    return true;
  }

  public function required_mata_uang(){
    if (!$_POST['mata_uang']['id'] || (filter_var($_POST['mata_uang']['id'], FILTER_VALIDATE_INT) === false)) {
      $this->form_validation->set_message('required_mata_uang', "ID Mata Uang wajib diisi dan harus berupa bilangan bulat.");
      return false;
    }
    return true;
  }

  public function required_keterangan_arahan_pimpinan() {
    if ($this->input->post('arahan_pimpinan', true) == "true" && !$this->input->post('keterangan_arahan_pimpinan', true)) {
      $this->form_validation->set_message('required_keterangan_arahan_pimpinan', 'Keterangan Arahan Pimpinan wajib diisi jika Arahan Pimpinan di-tick.');
      return false;
    }

    return true;
  }

  public function ajax_get_mata_uang() {
    $this->load->model('Mmatauang');

    $param = $this->input->get('q');

    $advance_cari = [
      'TEXT' => $param,
      'SINGKATAN' => $param,
      'SIMBOL' => $param,
      'NEGARA' => $param
    ];

    $mata_uang = $this->Mmatauang->get_daftar_master_matauang(0, NULL, 20, $advance_cari);

    $response = array_map(function ($mata_uang) {
      return [
        "id" => $mata_uang->ID_MATA_UANG,
        "simbol" => $mata_uang->SIMBOL,
        "nama_mata_uang" => $mata_uang->NAMA_MATA_UANG,
        "singkatan" => $mata_uang->SINGKATAN
      ];
    }, $mata_uang->result);

    echo json_encode($response);

    exit;
  }
}
