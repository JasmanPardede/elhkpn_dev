<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periksa_model extends CI_Model{
  var $table = 't_lhkpn_audit';
  var $column_order = array(null, 'NAMA_LENGKAP', 'DESKRIPSI_JABATAN', 'LEMBAGA', 'tgl_mulai_periksa', 'status_periksa', null);
  var $column_search = array('NAMA_LENGKAP','tgl_lapor', 'DESKRIPSI_JABATAN', 'LEMBAGA', 'status_periksa', 'LEMBAGA');
  var $order = array('id_audit' => 'desc', );

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  private function _get_datatables_query($id_pemeriksa, $is_hasilVerif=false)
  {
    // inisialisasi query
    if($is_hasilVerif==true){
       $this->get_daftar_periksa($id_pemeriksa, true);
       $this->db->Where('jenis_penugasan', '1'); // jenis = pemeriksaan
       $this->db->Where('jenis_pemeriksaan', '0'); // jenis pemeriksaan Terbuka
       $this->db->where_in('status_periksa', array('2','3')); // status proses atau selesai
    }else{
      $this->get_daftar_periksa($id_pemeriksa);
    
      if( $this->input->post('nomor_surat_tugas') !== '' )
      {
          $this->db->like('nomor_surat_tugas', $this->input->post('nomor_surat_tugas'));
      }
      if( $this->input->post('id_pic') !== '' )
      {
          $this->db->Where('id_pic', $this->input->post('id_pic'));
      }
  
    }

    // keyword dari form filter
    if($this->input->post('tgl_lapor'))
    {
        $this->db->where('YEAR(t_lhkpn.tgl_lapor)', $this->input->post('tgl_lapor'));
    }
    if($this->input->post('nama_lengkap'))
    {
        // $this->db->like('NAMA_LENGKAP', $this->input->post('nama_lengkap'));
        $this->db->like('t_pn.NAMA', $this->input->post('nama_lengkap'));
    }
    if($this->input->post('status_periksa'))
    {
        $this->db->Where('status_periksa', $this->input->post('status_periksa'));
    }
    if($this->input->post('lembaga'))
    {
        $this->db->like('INST_NAMA', $this->input->post('lembaga'));
    }

    $jns_penugasan = @$this->input->post('jenis_penugasan');
    if( isset($jns_penugasan) && $this->input->post('jenis_penugasan') !== '' )
    {
        $this->db->Where('jenis_penugasan', $this->input->post('jenis_penugasan'));
    }
    
    $i = 0;

    foreach ($this->column_search as $item) // loop column
    {
      if($_POST['search']['value']) // if datatable send POST for search
      {

        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        }
        else
        {
          if($item == 'tgl_lapor') {
            $this->db->or_like('t_lhkpn.'.$item, $_POST['search']['value']);
          } else {
            $this->db->or_like($item, $_POST['search']['value']);
          }
        }

        if(count($this->column_search) - 1 == $i) //last loop
        $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if(isset($_POST['order'])) // here order processing
    {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    }
    else if(isset($this->order))
    {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_daftar_periksa($id_pemeriksa  = 0, $is_hasilVerif = false)
  {

    $this->db->select('
    t_lhkpn_audit.id_audit,
    t_lhkpn.tgl_lapor,
    t_lhkpn.JENIS_LAPORAN,
    t_lhkpn.ID_LHKPN,
    t_lhkpn.STATUS as status,
    t_pn.NAMA as NAMA_LENGKAP,
    t_pn.NIK as NIK,
    t_pn.ID_PN as ID_PN,
    t_lhkpn_data_pribadi.NIK as NIK2,
    t_lhkpn_data_pribadi.NAMA_LENGKAP as NAMA_LENGKAP2,
    m_jabatan.NAMA_JABATAN as DESKRIPSI_JABATAN,
    m_jabatan.INST_SATKERKD as LEMBAGA,
    m_inst_satker.INST_NAMA,
    t_lhkpn_audit.tgl_mulai_periksa,
    t_lhkpn_audit.tgl_selesai_periksa,
    t_lhkpn_audit.status_periksa,
    t_lhkpn_audit.id_pemeriksa,
    t_lhkpn_audit.jenis_penugasan,
    t_lhkpn.TGL_KLARIFIKASI,
    t_lhkpn_audit.tgl_lhp,
    t_lhkpn_audit.nomor_surat_tugas,
    t_lhkpn_audit.jenis_pemeriksaan,
    t_lhkpn_audit.id_pic,
    t_lhkpn2.STATUS as status_klarifikasi,
    t_lhkpn.IS_ACTIVE
    ')
    ->from('t_lhkpn_audit')
    ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_audit.id_lhkpn', 'left')
    ->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left')
    ->join('m_jabatan','t_lhkpn_jabatan.id_jabatan = m_jabatan.id_jabatan', 'left')
    ->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD' , 'left')
    ->join('t_lhkpn_data_pribadi', 't_lhkpn_data_pribadi.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left')
    ->join('t_user', 't_user.ID_USER = t_lhkpn_audit.id_pemeriksa', 'left')
    ->join('t_pn', 't_lhkpn.ID_PN = t_pn.ID_PN', 'left')
    ->join('t_lhkpn t_lhkpn2', 't_lhkpn2.ID_LHKPN_PREV = t_lhkpn_audit.id_lhkpn AND t_lhkpn2.JENIS_LAPORAN = 5 AND t_lhkpn2.is_active = 1', 'left')
    ->where('t_lhkpn_audit.is_active = 1')
    // ->where('case when t_lhkpn_audit.jenis_penugasan = 1 and t_lhkpn_audit.jenis_pemeriksaan = 0 then t_lhkpn2.is_active = 1 else 1 end',null,false)
    ->group_by(array('t_lhkpn.ID_LHKPN', 't_lhkpn_audit.nomor_surat_tugas', 'id_pic'));
    //->order_by(array('t_lhkpn_audit.id_audit2', 'DESC'));

    $roles = explode(',', $this->session->userdata()['ID_ROLE_AUDIT']);
    // echo "<pre>";
    // print_r($roles);
    // echo "<pre>";
    // die();

    if($is_hasilVerif == true ){
         $this->db->where('t_lhkpn2.is_active', 1);
      if($this->input->post('status_klarif') == 2){  //belum klarif
          $this->db->where('t_lhkpn2.tgl_klarifikasi IS NOT NULL', null, false);
          $this->db->where('t_lhkpn2.status <', '3');
      }else if($this->input->post('status_klarif') == 1){ //sudah klarif 
         $this->db->where('t_lhkpn2.status >=', '3');
      }
   }
   
    if ( in_array('25', $roles)) {
      // $this->db->where('t_lhkpn_audit.id_creator', $id_pemeriksa);
      // $this->db->where('t_lhkpn_audit.id_pemeriksa', $id_pemeriksa);
    }
    else if (in_array('24', $roles) || in_array('100', $roles)) {
      $this->db->where('t_lhkpn_audit.id_pemeriksa', $id_pemeriksa);
    }
    // // -- comment sementara, semua koordinator pemeriksaan dapat melihat semua penugasan

    // $daftar_periksa = $this->db->get();
    // return $daftar_periksa->result();
  }

  function get_datatables($id_pemeriksa, $is_hasilVerif=false)
  {  
      if($is_hasilVerif==true){
        $this->_get_datatables_query($id_pemeriksa, true);
      }else{
        $this->_get_datatables_query($id_pemeriksa);
      }

      if($_POST['length'] != -1){
        $this->db->limit($_POST['length'], $_POST['start']);
      }
      $query = $this->db->get();

      return $query->result();
  }

  function count_filtered($id_pemeriksa, $is_hasilVerif=false)
  {
      if($is_hasilVerif==true){
        $this->_get_datatables_query($id_pemeriksa, true);
      }else{
        $this->_get_datatables_query($id_pemeriksa);
      }
      $query = $this->db->get();
      return $query->num_rows();
  }

  public function count_all()
  {
      $this->db->select('id_audit');
      $this->db->from($this->table);
      $this->db->group_by(array('id_lhkpn', 'nomor_surat_tugas'));
      $this->db->where('is_active', 1);
      $q = $this->db->get();

      // return $this->db->count_all_results();
      return $q->num_rows();
  }

  public function count_all_hasil_klarif()
  {
      $this->db->select('id_audit');
      $this->db->from($this->table);
      $this->db->group_by(array('id_lhkpn', 'nomor_surat_tugas', 'id_pic'));
      $this->db->where('is_active', 1);
      $this->db->Where('jenis_penugasan', '1');
      $this->db->Where('jenis_pemeriksaan', '0');
      $this->db->where_in('status_periksa', array('2','3'));
      $q = $this->db->get();
      return $q->num_rows();
  }

  public function count_pelaporan($id_lhkpn)
  {
    $this->db->select('ID_LHKPN');
    $this->db->from('T_LHKPN_PELAPORAN');
    $this->db->where('ID_LHKPN', $id_lhkpn);
    $q  = $this->db->get();
    return $q->num_rows();
  }

  public function set_status_periksa($status, $id_lhkpn, $no_st = NULL, $jenis_penugasan = NULL)
  {
    $posted_fields = array(
        'status_periksa' => $status,
        'UPDATED_DATE' => date('Y-m-d H:i:s'),
        'UPDATED_BY' => $this->session->userdata('USERNAME'),
        'UPDATED_IP' => $this->get_client_ip()
    );    
    $this->db->where('id_lhkpn',$id_lhkpn);
    if ($no_st != NULL) {
        $this->db->where('nomor_surat_tugas',$no_st);
    }
    if ($jenis_penugasan != NULL) {
        $this->db->where('jenis_penugasan', $jenis_penugasan);
        
        return $this->db->update('t_lhkpn_audit', $posted_fields);
    } else {
        $this->db->update('t_lhkpn_audit', $posted_fields);

        return $this->db->affected_rows();
    }
  }

  public function get_list_tahun($id_pemeriksa)
  {
    $this->db->select('year(t_lhkpn.tgl_lapor) as tahun_lapor');
    $this->db->from('t_lhkpn_audit');
    $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_audit.id_lhkpn');

    if ($id_pemeriksa > 0) {
      $this->db->where('t_lhkpn_audit.id_pemeriksa', $id_pemeriksa);
    }
    $this->db->group_by('tahun_lapor');
    $this->db->order_by('tahun_lapor', 'desc');
    $list_tahun = $this->db->get();

    return $list_tahun->result();
  }

  public function get_data_pn($id_audit)
  {
    $this->db->select('
    t_pn.NAMA as NAMA_LENGKAP,
    t_pn.ID_PN as ID_PN,
    t_lhkpn_data_pribadi.NAMA_LENGKAP as NAMA_LENGKAP2,
    m_jabatan.NAMA_JABATAN as DESKRIPSI_JABATAN,
    t_lhkpn.tgl_lapor,
    t_lhkpn.tgl_kirim_final,
    t_lhkpn.JENIS_LAPORAN,
    t_lhkpn_data_pribadi.NIK,
    t_pn.NHK,
    t_lhkpn.ID_LHKPN')
      ->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_audit.id_lhkpn')
      ->join('t_lhkpn_data_pribadi', 't_lhkpn_data_pribadi.ID_LHKPN = t_lhkpn_audit.id_lhkpn', 'left')
      ->join('t_pn', 't_lhkpn.ID_PN = t_pn.ID_PN', 'left')
      ->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left')
      ->join('m_jabatan','t_lhkpn_jabatan.id_jabatan = m_jabatan.id_jabatan', 'left')
      ->where('t_lhkpn_audit.id_audit =', $id_audit)
      ->where('t_lhkpn_audit.is_active = 1');
    $q = $this->db->get('t_lhkpn_audit');

    return $q->result();
  }

  function set_jenis_pemeriksaan_tbk($id_lhkpn, $no_st,$ENCRYPT_IDLHKPN) {

    $this->db->set('jenis_pemeriksaan', 0);
    $this->db->where('id_lhkpn',$id_lhkpn);
    $this->db->where('nomor_surat_tugas',$no_st);
    $this->db->where('ENCRYPT_IDLHKPN',$ENCRYPT_IDLHKPN);
    $this->db->update('t_lhkpn_audit'); 

    return $this->db->affected_rows();
  }

  public function get_data_audit($id_audit)
  {
    $this->db->select('*');
    $this->db->where('id_audit', $id_audit);
    $data_audit = $this->db->get('t_lhkpn_audit');
    return $data_audit->result();
  }
  public function get_data_pelaporan($id_lhkpn)
  {
    $this->db->select('*');
    $this->db->where('id_lhkpn', $id_lhkpn);
    $data_pelaporan = $this->db->get('t_lhkpn_pelaporan');
    return $data_pelaporan->result();
  }

  public function get_new_lhkpn($id_lhkpn_prev)
  {
    $this->db->select('ID_LHKPN');
    $this->db->from('t_lhkpn');
    $this->db->where('ID_LHKPN_PREV', $id_lhkpn_prev);
    $this->db->where('JENIS_LAPORAN', '5');
    $this->db->where('IS_ACTIVE', 1);
    $q = $this->db->get();

    return $q->result();
  }

  public function inisiasi_lhkpn_pemeriksaan($id_lhkpn, $tgl_klarifikasi)
  {
    // select data LHKPN
    $this->db->select('*');
    $this->db->from('t_lhkpn');
    $this->db->where('id_lhkpn', $id_lhkpn);
    $q_select = $this->db->get();
    $lhkpn_temp = $q_select->result()[0];

    $this->DuplicateMySQLRecord('t_lhkpn', 'ID_LHKPN', $id_lhkpn, $tgl_klarifikasi);

    return $this->db->affected_rows();
  }

  public function update_tgl_lhp($id_audit, $posted_fields, $id_lhkpn, $no_st)
  {
    // $this->db->where('id_audit',$id_audit);
    $this->db->where('id_lhkpn',$id_lhkpn);
    $this->db->where('nomor_surat_tugas',$no_st);
    $this->db->update('t_lhkpn_audit', $posted_fields);

    return $this->db->affected_rows();
  }

  // duplicate ibase_affected_rows
  private function DuplicateMySQLRecord ($table, $primary_key_field, $primary_key_val, $tgl_klarifikasi)
  {
     /* generate the select query */
     $this->db->where($primary_key_field, $primary_key_val);
     $query = $this->db->get($table);

      foreach ($query->result() as $row){
         foreach($row as $key=>$val){
            if($key != $primary_key_field){
            /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
            $this->db->set($key, $val);

            // --- custom
            if ($key == 'ID_LHKPN_PREV') {
              $this->db->set('ID_LHKPN_PREV', $primary_key_val);
            }
            if ($key == 'TGL_KLARIFIKASI') {
              $this->db->set('TGL_KLARIFIKASI', $tgl_klarifikasi);
            }
            }//endif
         }//endforeach
      }//endforeach

      /* insert the new record into table*/
      return $this->db->insert($table);
  }

  public function copy_to_lhkpn($id_lhkpn_prev, $tgl_klarifikasi, $id_audit) {

    $id_lhkpn = $this->copy_to_t_lhkpn($id_lhkpn_prev, $tgl_klarifikasi);
    if ($id_lhkpn) {
      $this->copy_to_t_lhkpn_data_pribadi($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_fasilitas($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_keluarga($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_harta_bergerak($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_harta_bergerak_lain($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_harta_kas($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_harta_lainnya($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_harta_surat_berharga($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_harta_tidak_bergerak($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_hutang($id_lhkpn_prev, $id_lhkpn);
      $this->copy_to_t_lhkpn_jabatan($id_lhkpn_prev, $id_lhkpn); 
      $this->copy_to_t_lhkpn_penerimaan_kas($id_lhkpn_prev, $id_lhkpn); 
      $this->copy_to_t_lhkpn_pengeluaran_kas($id_lhkpn_prev, $id_lhkpn);
      //$this->copy_to_pengeluaran2($id_lhkpn_prev, $id_lhkpn);
      //$this->copy_to_penerimaan2($id_lhkpn_prev, $id_lhkpn);
      $posted_fields = array(
        'status_periksa' => '2',
        'ENCRYPT_IDLHKPN' => strtr(base64_encode($id_lhkpn_prev), '+/=', '-_~')
      );
      $this->db->where('id_audit', $id_audit);
      $this->db->update('t_lhkpn_audit', $posted_fields);
    }
    return $id_lhkpn;
  }

  private function copy_to_t_lhkpn($id_lhkpn_prev, $tgl_klarifikasi) {
    $this->db->where('id_lhkpn', $id_lhkpn_prev);
    $data = $this->db->get('t_lhkpn');
    if ($data->num_rows() == 1) {
      $t_lhkpn = $data->row();
      $insert_fields = array(
        'JENIS_LAPORAN' => 5,
        'ID_PN' => $t_lhkpn->ID_PN,
        'tgl_lapor' => $t_lhkpn->tgl_lapor,
        //'tgl_lapor' => date('Y-m-d'),
        //'tgl_kirim' => date('Y-m-d'),
        'tgl_kirim' => $t_lhkpn->tgl_kirim,
        'nip' => $t_lhkpn->nip,
        'entry_via' => $t_lhkpn->entry_via,
        'FILE_KK' => $t_lhkpn->FILE_KK,
        'ALASAN' => $t_lhkpn->ALASAN,
        'STATUS' => '0',
        'IS_ACTIVE' => 1,
        'IS_COPY' => '1',
        'STATUS_PERBAIKAN_NASKAH' => $t_lhkpn->STATUS_PERBAIKAN_NASKAH,
        'CATATAN_PERBAIKAN_NASKAH' => $t_lhkpn->CATATAN_PERBAIKAN_NASKAH,
        'USERNAME_ENTRI' => $t_lhkpn->USERNAME_ENTRI,
        'STATUS_SURAT_PERNYATAAN' => $t_lhkpn->STATUS_SURAT_PERNYATAAN,
        'SURAT_PERNYATAAN' => $t_lhkpn->SURAT_PERNYATAAN,
        'STATUS_CETAK_SURAT_KUASA' => $t_lhkpn->STATUS_CETAK_SURAT_KUASA,
        'CETAK_SURAT_KUASA_TIME' => $t_lhkpn->CETAK_SURAT_KUASA_TIME,
        'SURAT_KUASA' => $t_lhkpn->SURAT_KUASA,
        'STATUS_SURAT_UMUMKAN' => $t_lhkpn->STATUS_SURAT_UMUMKAN,
        'CETAK_SURAT_UMUMKAN_TIME' => $t_lhkpn->CETAK_SURAT_UMUMKAN_TIME,
        'SURAT_UMUMKAN' => $t_lhkpn->SURAT_UMUMKAN,
        'TOKEN_PENGIRIMAN' => NULL,
        'created_time' => date('Y-m-d H:i:s'),
        'CREATED_IP' => $this->get_client_ip(),
        'pnid_migrasi' => $t_lhkpn->pnid_migrasi,
        'formulirid_migrasi' => $t_lhkpn->formulirid_migrasi,
        'tgl_kirim_final' => $t_lhkpn->tgl_kirim_final,
        'FILE_BUKTI_SKM' => $t_lhkpn->FILE_BUKTI_SKM,
        'FILE_BUKTI_SK' => $t_lhkpn->FILE_BUKTI_SK,
        'FILE_BUKTI_IKHTISAR' => $t_lhkpn->FILE_BUKTI_IKHTISAR,
        'ID_LHKPN_PREV' => $id_lhkpn_prev,
        'TGL_KLARIFIKASI' => $tgl_klarifikasi,
        'back_to_draft' => $t_lhkpn->back_to_draft,
        'CATATAN_PEMERIKSAAN' => NULL,
        'UPDATED_TIME' => date('Y-m-d H:i:s'),
        'UPDATED_BY' => $this->session->userdata('USERNAME'),
        'UPDATED_IP' => $this->get_client_ip()
      );
      
      $this->db->insert('t_lhkpn', $insert_fields);
      if ($this->db->affected_rows() == 1) {
        $new_id_lhkpn = $this->db->insert_id();
        return $new_id_lhkpn;
      }
      else{
        return false;
      }

    }
    else{
      return false;
    }
  }

  private function copy_to_t_lhkpn_data_pribadi($id_lhkpn_prev, $id_lhkpn) {
    $sql = "insert into t_lhkpn_data_pribadi (ID_LHKPN, id_agama, GELAR_DEPAN, GELAR_BELAKANG, NAMA_LENGKAP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, NIK, NPWP, STATUS_PERKAWINAN, AGAMA, JABATAN, JABATAN_LAINNYA, ALAMAT_RUMAH, EMAIL_PRIBADI, PROVINSI, KABKOT, KECAMATAN, KELURAHAN, TELPON_RUMAH, HP, HP_LAINNYA, FOTO, FILE_NPWP, FILE_KTP, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, KD_ISO3_NEGARA, NEGARA, ALAMAT_NEGARA, formulirid_migrasi, pnid_migrasi, NO_KK) "
            . "(select " . $id_lhkpn . ", id_agama, GELAR_DEPAN, GELAR_BELAKANG, NAMA_LENGKAP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, NIK, NPWP, STATUS_PERKAWINAN, AGAMA, JABATAN, JABATAN_LAINNYA, ALAMAT_RUMAH, EMAIL_PRIBADI, PROVINSI, KABKOT, KECAMATAN, KELURAHAN, TELPON_RUMAH, HP, HP_LAINNYA, FOTO, FILE_NPWP, FILE_KTP, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, KD_ISO3_NEGARA, NEGARA, ALAMAT_NEGARA, formulirid_migrasi, pnid_migrasi, NO_KK "
            . " from t_lhkpn_data_pribadi "
            . "where id_lhkpn = '" . $id_lhkpn_prev . "')";

    $res = $this->db->query($sql);
    if ($res) {
        return TRUE;
    }
    return FALSE;
  }

  private function copy_to_t_lhkpn_fasilitas($id_lhkpn_prev, $id_lhkpn) {
    $sql = "insert into t_lhkpn_fasilitas (ID_FASILITAS, ID_LHKPN, JENIS_FASILITAS, NAMA_FASILITAS, PEMBERI_FASILITAS, KETERANGAN, KETERANGAN_LAIN, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_LOAD) "
            . "(select ID_FASILITAS, " . $id_lhkpn . ", JENIS_FASILITAS, NAMA_FASILITAS, PEMBERI_FASILITAS, KETERANGAN, KETERANGAN_LAIN, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_LOAD "
            . " from t_lhkpn_fasilitas "
            . "where id_lhkpn = '" . $id_lhkpn_prev . "')";

    $res = $this->db->query($sql);

    if ($res) {
        return TRUE;
    }
    return FALSE;
  }

  private function copy_to_t_lhkpn_jabatan($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_jabatan (ID_LHKPN, ID_JABATAN, DESKRIPSI_JABATAN, ESELON, LEMBAGA, UNIT_KERJA, SUB_UNIT_KERJA, TMT, SD, ALAMAT_KANTOR, EMAIL_KANTOR, FILE_SK, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, ID_STATUS_AKHIR_JABAT, IS_PRIMARY, TEXT_JABATAN_PUBLISH) "
                . "(select " . $id_lhkpn . ", ID_JABATAN, DESKRIPSI_JABATAN, ESELON, LEMBAGA, UNIT_KERJA, SUB_UNIT_KERJA, TMT, SD, ALAMAT_KANTOR, EMAIL_KANTOR, FILE_SK, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, ID_STATUS_AKHIR_JABAT, 1, TEXT_JABATAN_PUBLISH "
                . " from t_lhkpn_jabatan "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "')";

        $res = $this->db->query($sql);
        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_bergerak($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_bergerak (ID_HARTA,ID_LHKPN, KODE_JENIS, MEREK, MODEL, TAHUN_PEMBUATAN, NOPOL_REGISTRASI, NAMA, JUMLAH, SATUAN, JENIS_BUKTI, NOMOR_BUKTI, ATAS_NAMA, ASAL_USUL, PEMANFAATAN, KET_LAINNYA, PASANGAN_ANAK, TAHUN_PEROLEHAN_AWAL, TAHUN_PEROLEHAN_AKHIR, MATA_UANG, NILAI_PEROLEHAN,  JENIS_NILAI_PELAPORAN, IS_ACTIVE, JENIS_LEPAS, TGL_TRANSAKSI, NILAI_JUAL, NAMA_PIHAK2, ALAMAT_PIHAK2, STATUS, IS_PELEPASAN, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_CHECKED, IS_LOAD, FormulirID, ref_form_harta, Previous_ID, NILAI_PELAPORAN_OLD) "
                . "(select ID, " . $id_lhkpn . ", KODE_JENIS, MEREK, MODEL, TAHUN_PEMBUATAN, NOPOL_REGISTRASI, NAMA, JUMLAH, SATUAN, JENIS_BUKTI, NOMOR_BUKTI, ATAS_NAMA, ASAL_USUL, PEMANFAATAN, KET_LAINNYA, PASANGAN_ANAK, TAHUN_PEROLEHAN_AWAL, TAHUN_PEROLEHAN_AKHIR, MATA_UANG, NILAI_PEROLEHAN,  JENIS_NILAI_PELAPORAN, IS_ACTIVE, JENIS_LEPAS, TGL_TRANSAKSI, NILAI_JUAL, NAMA_PIHAK2, ALAMAT_PIHAK2, 3, 0, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_CHECKED, IS_LOAD, FormulirID, ref_form_harta, ID, NILAI_PELAPORAN"
                . " from t_lhkpn_harta_bergerak "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "' and is_pelepasan = '0' and is_active = '1')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_bergerak_lain($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_bergerak_lain (ID_HARTA,   ID_LHKPN,   KODE_JENIS,   NAMA,   JUMLAH,   SATUAN,   KETERANGAN,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   JENIS_NILAI_PELAPORAN,   STATUS,   IS_PELEPASAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta, Previous_ID, NILAI_PELAPORAN_OLD) "
                . "(select ID,   " . $id_lhkpn . ",   KODE_JENIS,   NAMA,   JUMLAH,   SATUAN,   KETERANGAN,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   JENIS_NILAI_PELAPORAN,   3,   0,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta, ID, NILAI_PELAPORAN"
                . " from t_lhkpn_harta_bergerak_lain "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "' and is_pelepasan = '0' and is_active = '1')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }


    private function copy_to_t_lhkpn_harta_surat_berharga($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_surat_berharga (  ID_HARTA,   ID_LHKPN,   KODE_JENIS,   JUMLAH,   SATUAN,   NAMA_SURAT_BERHARGA,   NAMA_PENERBIT,   CUSTODIAN,   NOMOR_REKENING,   ATAS_NAMA, PASANGAN_ANAK,  ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_PELEPASAN,   STATUS,   IS_CHECKED,   FILE_BUKTI,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA, NILAI_PELAPORAN_OLD, Previous_ID) "
                . "(select   ID,   " . $id_lhkpn . ",   KODE_JENIS,   JUMLAH,   SATUAN,   NAMA_SURAT_BERHARGA,   NAMA_PENERBIT,   CUSTODIAN,   NOMOR_REKENING,   ATAS_NAMA, PASANGAN_ANAK,  ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   0,   3,   IS_CHECKED,   FILE_BUKTI,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA, NILAI_PELAPORAN, ID "
                . " from t_lhkpn_harta_surat_berharga "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "' and is_pelepasan = '0' and is_active = '1')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_lainnya($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_lainnya (ID_HARTA,   ID_LHKPN,   KODE_JENIS,   NAMA,   KETERANGAN,   KUANTITAS,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   IS_PELEPASAN,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD, NILAI_PELAPORAN_OLD, Previous_ID ) "
                . "(select   ID,   " . $id_lhkpn . ",   KODE_JENIS,   NAMA,   KETERANGAN,   KUANTITAS,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   3,   0,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD, NILAI_PELAPORAN, ID "
                . " from t_lhkpn_harta_lainnya "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "' and is_pelepasan = '0' and is_active = '1')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_kas($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_kas (  ID_HARTA,   ID_LHKPN,   KODE_JENIS,   ASAL_USUL,   ATAS_NAMA_REKENING, PASANGAN_ANAK,   NAMA_BANK,   NOMOR_REKENING,   KETERANGAN,   TAHUN_BUKA_REKENING,   MATA_UANG,   NILAI_KURS,   STATUS,   IS_PELEPASAN,   FILE_BUKTI,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta, NILAI_EQUIVALEN_OLD, Previous_ID) "
                . "(select   ID,   " . $id_lhkpn . ",   KODE_JENIS,   ASAL_USUL,   ATAS_NAMA_REKENING,  PASANGAN_ANAK,  NAMA_BANK,   NOMOR_REKENING,   KETERANGAN,   TAHUN_BUKA_REKENING,   MATA_UANG,  NILAI_KURS,   3,   0,   FILE_BUKTI,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta, NILAI_EQUIVALEN, ID"
                . " from t_lhkpn_harta_kas "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "' and is_pelepasan = '0' and is_active = '1')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_tidak_bergerak($id_lhkpn_prev, $id_lhkpn) {

        $sql = "insert into t_lhkpn_harta_tidak_bergerak (ID_HARTA,   ID_LHKPN,   NEGARA,   ID_NEGARA,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA, PASANGAN_ANAK, TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   IS_PELEPASAN,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta, NILAI_PELAPORAN_OLD, Previous_ID) "
                . "(select ID,   " . $id_lhkpn . ",   1,   2,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA, PASANGAN_ANAK,  TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   3,   0,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta, NILAI_PELAPORAN, ID "
                . " from t_lhkpn_harta_tidak_bergerak "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "' and is_pelepasan = '0' and is_active = '1')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_hutang($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_hutang (  ID_HARTA,   ID_LHKPN,   ATAS_NAMA, PASANGAN_ANAK, KODE_JENIS,   NAMA_KREDITUR,   TANGGAL_TRANSAKSI,   TANGGAL_JATUH_TEMPO,   AGUNAN,   AWAL_HUTANG,   STATUS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA, SALDO_HUTANG_OLD, Previous_ID) "
                . "(select   ID_HUTANG,   " . $id_lhkpn . ",   ATAS_NAMA, PASANGAN_ANAK,  KODE_JENIS,   NAMA_KREDITUR,   TANGGAL_TRANSAKSI,   TANGGAL_JATUH_TEMPO,   AGUNAN,   AWAL_HUTANG,   STATUS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA, SALDO_HUTANG, ID_HUTANG"
                . " from t_lhkpn_hutang "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "' and is_active = '1')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_keluarga($id_lhkpn_prev, $id_lhkpn) { 

        $this->db->where('ID_LHKPN', $id_lhkpn_prev);
        $this->db->where('IS_ACTIVE', 1);
        $keluarga = $this->db->get('t_lhkpn_keluarga')->result();
        
        $res = 0;

        if ($keluarga) {
          
          foreach ($keluarga as $kl) {

              $arr_keluarga = array(
                'ID_KELUARGA_LAMA' => $kl->ID_KELUARGA,
                'ID_LHKPN' => $id_lhkpn,
                'NIK' => $kl->NIK,
                'NAMA' => $kl->NAMA,
                'HUBUNGAN' => $kl->HUBUNGAN,
                'STATUS_HUBUNGAN' => $kl->STATUS_HUBUNGAN,
                'TEMPAT_LAHIR' => $kl->TEMPAT_LAHIR,
                'TANGGAL_LAHIR' => $kl->TANGGAL_LAHIR,
                'JENIS_KELAMIN' => $kl->JENIS_KELAMIN,
                'TEMPAT_NIKAH' => $kl->TEMPAT_NIKAH,
                'TANGGAL_NIKAH' => $kl->TANGGAL_NIKAH,
                'TEMPAT_CERAI' => $kl->TEMPAT_CERAI,
                'TANGGAL_CERAI' => $kl->TANGGAL_CERAI,
                'PEKERJAAN' => $kl->PEKERJAAN,
                'ALAMAT_RUMAH' => $kl->ALAMAT_RUMAH,
                'NOMOR_TELPON' => $kl->NOMOR_TELPON,
                'IS_ACTIVE' => $kl->IS_ACTIVE,
                'KETERANGAN_DIHAPUS' => $kl->KETERANGAN_DIHAPUS,
                'STATUS_CETAK_SURAT_KUASA' => $kl->STATUS_CETAK_SURAT_KUASA,
                'CETAK_SURAT_KUASA_TIME' => $kl->CETAK_SURAT_KUASA_TIME,
                'SURAT_KUASA' => $kl->SURAT_KUASA,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('NAMA'),
                'CREATED_IP' => get_client_ip(),
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('NAMA'),
                'UPDATED_IP' => get_client_ip(),
                'FormulirID' => $kl->FormulirID,
                'ref_form_harta' => $kl->ref_form_harta,
                'FLAG_SK' => $kl->FLAG_SK,
            );
            $res = $this->db->insert('t_lhkpn_keluarga', $arr_keluarga);

          }

        }

        // $sql = "insert into t_lhkpn_keluarga (ID_LHKPN,   NIK,   NAMA,   HUBUNGAN,   STATUS_HUBUNGAN,   TEMPAT_LAHIR,   TANGGAL_LAHIR,   JENIS_KELAMIN,   TEMPAT_NIKAH,   TANGGAL_NIKAH,   TEMPAT_CERAI,   TANGGAL_CERAI,   PEKERJAAN,   ALAMAT_RUMAH,   NOMOR_TELPON,   IS_ACTIVE,   KETERANGAN_DIHAPUS,   STATUS_CETAK_SURAT_KUASA,   CETAK_SURAT_KUASA_TIME,   SURAT_KUASA,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta) "
        //         . "(select   " . $id_lhkpn . ",   NIK,   NAMA,   HUBUNGAN,   STATUS_HUBUNGAN,   TEMPAT_LAHIR,   TANGGAL_LAHIR,   JENIS_KELAMIN,   TEMPAT_NIKAH,   TANGGAL_NIKAH,   TEMPAT_CERAI,   TANGGAL_CERAI,   PEKERJAAN,   ALAMAT_RUMAH,   NOMOR_TELPON,   IS_ACTIVE,   KETERANGAN_DIHAPUS,   STATUS_CETAK_SURAT_KUASA,   CETAK_SURAT_KUASA_TIME,   SURAT_KUASA,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta "
        //         . " from t_lhkpn_keluarga "
        //         . "where id_lhkpn = '" . $id_lhkpn_prev . "')";

        // $res = $this->db->query($sql);

        if ($res) { 
            return TRUE;
        }
        return FALSE;
    }

    public function copy_to_penerimaan2($id_lhkpn_prev, $id_lhkpn, $return_data_array = FALSE) {

      $this->db->where('id_lhkpn', $id_lhkpn_prev);
      $data = $this->db->get('t_lhkpn_penerimaan_kas');
      if ($data->num_rows() == 1) {
        $data_detail = $data->row();
        $pn = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PN) ? $data_detail->NILAI_PENERIMAAN_KAS_PN : "{}");
        $pa = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PASANGAN) ? $data_detail->NILAI_PENERIMAAN_KAS_PASANGAN : "{}");
      }
      else{
        $pn = json_decode("{}");
        $pa = json_decode("{}");
      }

        $jenis_penerimaan_kas_pn = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $golongan_penerimaan_kas_pn = $this->config->item('golongan_penerimaan_kas_pn', 'harta');

        $label = array('A', 'B', 'C');

        $data_arr = array();

        $k = 0;
        for ($i = 0; $i < count($jenis_penerimaan_kas_pn); $i++) {
            for ($j = 0; $j < count($jenis_penerimaan_kas_pn[$i]); $j++) {
                $PA_val = 'PA' . $j;
                $code = $label[$i] . $j;

                $data_arr[$k] = array(
                    "ID_LHKPN" => $id_lhkpn,
                    "GROUP_JENIS" => $label[$i],
                    "KODE_JENIS" => $code,
                    "JENIS_PENERIMAAN" => $jenis_penerimaan_kas_pn[$i][$j],
                    "PN" => 0,
                    "PN_OLD" => 0,
                    "PASANGAN" => 0,
                    "PASANGAN_OLD" => 0,
                );
                if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
                    $data_arr[$k]["PN"] = $pn->{$label[$i]}[$j]->$code;
                    $data_arr[$k]["PN_OLD"] = $pn->{$label[$i]}[$j]->$code;
                }

                if ($i == 0) {
                    if (is_array($pa) && !empty($pa) && property_exists($pa[$j], $PA_val)) {
                        $data_arr[$k]["PASANGAN"] = $pa[$j]->{$PA_val};
                        $data_arr[$k]["PASANGAN_OLD"] = $pa[$j]->{$PA_val};
                    }
                }

                $k++;
            }
        }
        unset($pn, $pa, $jenis_penerimaan_kas_pn);

        if ($return_data_array) {
            return $data_arr;
        }

        $this->db->insert_batch('t_lhkpn_penerimaan_kas2', $data_arr);
    }

    public function copy_to_pengeluaran2($id_lhkpn_prev, $id_lhkpn, $return_data_array = FALSE) {

      $this->db->where('id_lhkpn', $id_lhkpn_prev);
      $data = $this->db->get('t_lhkpn_pengeluaran_kas');
      if ($data->num_rows() == 1) {
        $data_detail = $data->row();
        $pn = json_decode(!is_null($data_detail->NILAI_PENGELUARAN_KAS) ? $data_detail->NILAI_PENGELUARAN_KAS : "{}");
      }
      else{
        $pn = json_decode("{}");
      }

        $jenis_pengeluaran_kas_pn = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $label = array('A', 'B', 'C');

        $data_arr = array();

        $k = 0;
        for ($i = 0; $i < count($jenis_pengeluaran_kas_pn); $i++) {
            for ($j = 0; $j < count($jenis_pengeluaran_kas_pn[$i]); $j++) {
                $code = $label[$i] . $j;

                $data_arr[$k] = array(
                    "ID_LHKPN" => $id_lhkpn,
                    "GROUP_JENIS" => $label[$i],
                    "KODE_JENIS" => $code,
                    "JENIS_PENGELUARAN" => $jenis_pengeluaran_kas_pn[$i][$j],
                    "JML" => 0,
                    "JML_OLD" => 0,
                );

                if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
                    $data_arr[$k]["JML"] = $pn->{$label[$i]}[$j]->$code;
                    $data_arr[$k]["JML_OLD"] = $pn->{$label[$i]}[$j]->$code;
                }
                $k++;
            }
        }

        unset($pn, $jenis_pengeluaran_kas_pn);

        if ($return_data_array) {
            return $data_arr;
        }

        $this->db->insert_batch('t_lhkpn_pengeluaran_kas2', $data_arr);
    }

    private function copy_to_t_lhkpn_pengeluaran_kas($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_pengeluaran_kas2 (  ID_LHKPN,  GROUP_JENIS, KODE_JENIS , JENIS_PENGELUARAN, Previous_ID, JML_OLD  ) "
                . "(select   " . $id_lhkpn . ", GROUP_JENIS, KODE_JENIS , JENIS_PENGELUARAN,  ID_PENGELUARAN_KAS, JML "
                . " from t_lhkpn_pengeluaran_kas2 "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "')";

        $res = $this->db->query($sql); 

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_penerimaan_kas($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_penerimaan_kas2 (  ID_LHKPN,   GROUP_JENIS, KODE_JENIS , JENIS_PENERIMAAN, Previous_ID,   PN_OLD,   PASANGAN_OLD) "
                . "(select   " . $id_lhkpn . ", GROUP_JENIS, KODE_JENIS , JENIS_PENERIMAAN, ID_PENERIMAAN_KAS,   PN,  PASANGAN "
                . " from t_lhkpn_penerimaan_kas2 "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "')";
			
        $res = $this->db->query($sql); 

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_pelepasan($id_lhkpn_prev, $id_lhkpn, $table_source, $table_destination) {
        $sql = "insert into " . $table_destination . " (  ID_HARTA,   ID_LHKPN,   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta "
                . " from " . $table_source . " "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "')";

        $res = $this->db->query($sql); 

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_pelepasan_harta_bergerak($id_lhkpn_prev, $id_lhkpn) {
        $sql = "insert into t_lhkpn_pelepasan_harta_bergerak (  ID_HARTA,   ID_LHKPN,   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta "
                . " from t_lhkpn_pelepasan_harta_bergerak "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
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

  public function get_list_lhkpn($id_lhkpn)
  {
    // get id pn
    $this->db->select('ID_PN');
    $this->db->from('t_lhkpn');
    $this->db->where('ID_LHKPN', $id_lhkpn);
    $q = $this->db->get()->result();
    $id_pn = $q[0]->ID_PN;

    // get all lhkpn by id_pn
    $this->db->select('lh.ID_LHKPN, lh.JENIS_LAPORAN, lh.tgl_lapor, lh.STATUS, pn.NIK, au.id_audit, au.nomor_surat_tugas');
    $this->db->from('t_lhkpn lh');
    $this->db->join('t_pn pn', 'pn.ID_PN = lh.ID_PN', 'left');
    $this->db->join('t_lhkpn_audit au', 'au.id_lhkpn = lh.ID_LHKPN', 'left');
    $this->db->where('lh.ID_PN', $id_pn);
    $this->db->where('lh.IS_ACTIVE = 1');
    $this->db->group_by('lh.ID_LHKPN');
    $this->db->order_by('lh.tgl_lapor', 'desc');
    $qq = $this->db->get()->result();
    return $qq;
  }

  public function get_list_pelaporan($id_lhkpn)
  {
    $this->db->select('CREATED_TIME AS tgl, ISI_PENGADUAN AS pengaduan, KETERANGAN_PEMERIKSA AS keterangan');
    $this->db->from('t_lhkpn_pelaporan');
    $this->db->where('ID_LHKPN', $id_lhkpn);
    $this->db->where('IS_VERIFICATION', '1');
    $qq = $this->db->get()->result();
    return $qq;
  }

  // ambil nomor terakhir pada tahun aplikasi diakses
  public function get_latest_no_lt()
  {
    $this->db->select('MAX(nomor_lt) as latest_num');
    $this->db->from('t_eaudit_penelaahan');
    $this->db->where('YEAR(tanggal_lt)', date('Y'));
    $q = $this->db->get();

    return $q->result();
  }

  // create new row of penelaahan based on nomor surat tugas
  // @params : array of field in table t_eaudit_penelaahan
  public function create_penelaahan($params)
  {
    $data = array(
      'nomor_lt' => $params['nomor_lt'],
      'tanggal_lt' => $params['tanggal_lt'],
      'nomor_lt_dok' => $params['nomor_lt_dok'],
      'id_audit' => $params['id_audit'],
      'created_at' => date("Y-m-d H:i:s")
    );
    $q = $this->db->insert('t_eaudit_penelaahan', $data);
    return $q;
  }

  public function get_penelaahan($id_audit)
  {
    $this->db->select('nomor_lt_dok, created_at, submited_at, keterangan, rekomendasi');
    $this->db->where('id_audit', $id_audit);
    $this->db->where('created_at IS NOT NULL');
    $this->db->order_by('created_at', 'desc');

    return $this->db->get('t_eaudit_penelaahan');
  }

  public function update_penelaahan($params)
  {
    $data = array(
      'rekomendasi' => $params['rekomendasi'],
      'keterangan' => $params['keterangan'],
      'submited_at' => date("Y-m-d H:i:s")
    );
    $this->db->where('nomor_lt_dok', $params['nomor_lt_dok']);
    $this->db->where('id_audit', $params['id_audit']);
    return $this->db->update('t_eaudit_penelaahan', $data);
  }

  public function get_data_audit_progress($id_audit, $is_pedal=FALSE)
  {
    $this->db->select('
      t_lhkpn_audit.*,
      t_lhkpn.ID_PN,
      t_pn.NAMA as NAMA_PN,
      t_lhkpn_audit_progress.*,
      t_user.NAMA,
      t_eaudit_pemenuhan_data_perbankan.id_permintaan,
      t_eaudit_permintaan_data_perbankan.id_permintaan,
      t_eaudit_permintaan_data_perbankan.no_permintaan,
      t_eaudit_permintaan_data_perbankan.tgl_approval3
    ');
    $this->db->from('t_lhkpn_audit');
    $this->db->where('t_lhkpn_audit.id_audit', $id_audit);
    if($is_pedal){
      $this->db->where('t_eaudit_permintaan_data_perbankan.tgl_approval3 IS NOT NULL', NULL, FALSE);
      $this->db->where('t_eaudit_permintaan_data_perbankan.no_permintaan IS NOT NULL', NULL, FALSE);
    }
    $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_audit.id_lhkpn', 'left');
    $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN', 'left');
    $this->db->join('t_eaudit_pemenuhan_data_perbankan', 't_eaudit_pemenuhan_data_perbankan.id_lhkpn = t_lhkpn_audit.id_lhkpn', 'left');
    $this->db->join('t_eaudit_permintaan_data_perbankan', 't_eaudit_permintaan_data_perbankan.id_permintaan = t_eaudit_pemenuhan_data_perbankan.id_permintaan', 'left');
    $this->db->join('t_lhkpn_audit_progress', 't_lhkpn_audit_progress.ID_AUDIT = t_lhkpn_audit.id_audit', 'left');
    $this->db->join('t_user', 't_user.ID_USER = t_lhkpn_audit_progress.UPDATED_BY', 'left');
    $data_progress = $this->db->get();

    return $data_progress->row();
  }

  public function create_data_audit_progress($params)
  {
    $data = array(
      'ID_AUDIT' => $params['ID_AUDIT'],
      'ID_LHKPN' => $params['ID_LHKPN'],
      'IS_CEK_TELUSUR' => $params['IS_CEK_TELUSUR'],
      'IS_CEK_SIPESAT' => $params['IS_CEK_SIPESAT'],
      'IS_VOUCHER_PEDAL' => $params['IS_VOUCHER_PEDAL'],
      'IS_CEK_LAPORAN' => $params['IS_CEK_LAPORAN'],
      'IS_DRAFT_LHKPN' => $params['IS_DRAFT_LHKPN'],
      'IS_PENDALAMAN' => $params['IS_PENDALAMAN'],
      'CREATED_BY' => $this->session->userdata('ID_USER'),
      'UPDATED_BY' => $this->session->userdata('ID_USER'),
      'CREATED_AT' => date('Y-m-d H:i:s'),
      'UPDATED_AT' => date('Y-m-d H:i:s'),
    );
    $q = $this->db->insert('t_lhkpn_audit_progress', $data);
    return $q;
  }

  public function update_data_audit_progress($params)
  {
    $data = array(
      'IS_CEK_TELUSUR' => $params['IS_CEK_TELUSUR'],
      'IS_CEK_SIPESAT' => $params['IS_CEK_SIPESAT'],
      'IS_VOUCHER_PEDAL' => $params['IS_VOUCHER_PEDAL'],
      'IS_CEK_LAPORAN' => $params['IS_CEK_LAPORAN'],
      'IS_DRAFT_LHKPN' => $params['IS_DRAFT_LHKPN'],
      'IS_PENDALAMAN' => $params['IS_PENDALAMAN'],
      'UPDATED_BY' => $this->session->userdata('ID_USER'),
      'UPDATED_AT' => date('Y-m-d H:i:s'),
    );
    $this->db->where('ID_AUDIT', $params['ID_AUDIT']);
    return $this->db->update('t_lhkpn_audit_progress', $data);
  }

  public function get_data_hasil_analisis_pemeriksaan($id_audit) {
    $this->db->select('
      t_lhkpn_audit.ID_AUDIT,
      t_lhkpn_audit.ID_LHKPN,
      t_lhkpn_audit.is_paparan_pimpinan,
      t_lhkpn_audit.is_arahan_pimpinan,
      t_lhkpn_audit.keterangan_arahan_pimpinan,
      t_lhkpn_audit.keterangan_hasil_analisis,
      t_lhkpn_audit.keterangan_dasar_pemeriksaan,
      t_pn.NAMA as NAMA_PN,
      t_pn.NIK as NIK_PN,
      m_jabatan.NAMA_JABATAN as DESKRIPSI_JABATAN,
      m_jabatan.INST_SATKERKD as LEMBAGA,
      m_inst_satker.INST_NAMA
    ');

    $this->db->from('t_lhkpn_audit');
    $this->db->where('t_lhkpn_audit.id_audit', $id_audit);
    $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_audit.id_lhkpn', 'left');
    $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN', 'left');
    $this->db->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
    $this->db->join('m_jabatan','t_lhkpn_jabatan.id_jabatan = m_jabatan.id_jabatan', 'left');
    $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD' , 'left');

    $data = $this->db->get();

    return $data->first_row();
  }

  public function get_data_hasil_analisis($id_audit, $is_underlying_transaksi=false, $start=0) {  
    $this->db->select('
      t_lhkpn_audit_hasil_analisis.ID_HASIL_ANALISIS,
      t_lhkpn_audit_hasil_analisis.ID_AUDIT,
      t_lhkpn_audit_hasil_analisis.NILAI_TRANSAKSI,
      t_lhkpn_audit_hasil_analisis.IS_UNDERLYING_TRANSAKSI,
      t_lhkpn_audit_hasil_analisis.HASIL_ANALISIS,
      m_mata_uang.ID_MATA_UANG,
      m_mata_uang.NAMA_MATA_UANG,
      m_mata_uang.SINGKATAN,
      m_mata_uang.SIMBOL
    ');

    $this->db->from('t_lhkpn_audit_hasil_analisis');
    $this->db->where('t_lhkpn_audit_hasil_analisis.ID_AUDIT', $id_audit);    
    $this->db->where('t_lhkpn_audit_hasil_analisis.IS_UNDERLYING_TRANSAKSI', $is_underlying_transaksi);

    $this->db->where('t_lhkpn_audit_hasil_analisis.DELETED_DATE', null);
    $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = t_lhkpn_audit_hasil_analisis.ID_MATA_UANG', 'left');
    $this->db->limit('10', $start);

    $data = $this->db->get();

    return array_map(function ($data) {
      return [
        'id' => $data->ID_HASIL_ANALISIS,
        'id_audit' => $data->ID_AUDIT,
        'mata_uang' => [
          'id' => $data->ID_MATA_UANG,
          'simbol' => $data->SIMBOL,
          'nama_mata_uang' => $data->NAMA_MATA_UANG,
          'singkatan' => $data->SINGKATAN
        ],
        'nominal' => $data->NILAI_TRANSAKSI,
        'nominal_string' => number_format($data->NILAI_TRANSAKSI, 2, ",", "."),
        'underlying_transaksi' => (int)$data->IS_UNDERLYING_TRANSAKSI,
        'hasil_analisis' => $data->HASIL_ANALISIS
      ];
    }, $data->result());
  }

  public function get_data_hasil_analisis_by_id($id) {  
    // is_all (ambil semua data) //
    $this->db->select('
      t_lhkpn_audit_hasil_analisis.ID_HASIL_ANALISIS,
      t_lhkpn_audit_hasil_analisis.ID_AUDIT,
      t_lhkpn_audit_hasil_analisis.NILAI_TRANSAKSI,
      t_lhkpn_audit_hasil_analisis.IS_UNDERLYING_TRANSAKSI,
      t_lhkpn_audit_hasil_analisis.HASIL_ANALISIS,
      m_mata_uang.ID_MATA_UANG,
      m_mata_uang.NAMA_MATA_UANG,
      m_mata_uang.SINGKATAN,
      m_mata_uang.SIMBOL
    ');

    $this->db->from('t_lhkpn_audit_hasil_analisis');
    $this->db->where('t_lhkpn_audit_hasil_analisis.ID_HASIL_ANALISIS', $id);
    $this->db->where('t_lhkpn_audit_hasil_analisis.DELETED_DATE', null);
    $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = t_lhkpn_audit_hasil_analisis.ID_MATA_UANG', 'left');
    
    $data = $this->db->get();

    return array_map(function ($data) {
      return [
        'id' => $data->ID_HASIL_ANALISIS,
        'id_audit' => $data->ID_AUDIT,
        'mata_uang' => [
          'id' => $data->ID_MATA_UANG,
          'simbol' => $data->SIMBOL,
          'nama_mata_uang' => $data->NAMA_MATA_UANG,
          'singkatan' => $data->SINGKATAN
        ],
        'nominal' => $data->NILAI_TRANSAKSI,
        'nominal_string' => number_format($data->NILAI_TRANSAKSI, 2, ",", "."),
        'underlying_transaksi' => (int)$data->IS_UNDERLYING_TRANSAKSI,
        'hasil_analisis' => $data->HASIL_ANALISIS
      ];
    }, $data->result());
  }


  public function count_data_hasil_analisis($id_audit, $is_underlying_transaksi=false) {
    $this->db->select('
      t_lhkpn_audit_hasil_analisis.ID_HASIL_ANALISIS,
      t_lhkpn_audit_hasil_analisis.ID_AUDIT,
      t_lhkpn_audit_hasil_analisis.NILAI_TRANSAKSI,
      t_lhkpn_audit_hasil_analisis.IS_UNDERLYING_TRANSAKSI,
      t_lhkpn_audit_hasil_analisis.HASIL_ANALISIS,
      m_mata_uang.ID_MATA_UANG,
      m_mata_uang.NAMA_MATA_UANG,
      m_mata_uang.SINGKATAN,
      m_mata_uang.SIMBOL
    ');

    $this->db->from('t_lhkpn_audit_hasil_analisis');
    $this->db->where('t_lhkpn_audit_hasil_analisis.ID_AUDIT', $id_audit);
    $this->db->where('t_lhkpn_audit_hasil_analisis.IS_UNDERLYING_TRANSAKSI', $is_underlying_transaksi);
    $this->db->where('t_lhkpn_audit_hasil_analisis.DELETED_DATE', null);
    $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = t_lhkpn_audit_hasil_analisis.ID_MATA_UANG', 'left');

    $data = $this->db->get();

    return $data->num_rows();
  }

  public function update_data_hasil_analisis_pemeriksaan($params) {
    $id_audit = $params['id_audit'];

    $data_update = [
      'is_paparan_pimpinan' => $params['is_paparan_pimpinan'],
      'is_arahan_pimpinan' => $params['is_arahan_pimpinan'],
      'keterangan_arahan_pimpinan' => $params['keterangan_arahan_pimpinan'],
      'keterangan_hasil_analisis' => $params['keterangan_hasil_analisis'],
      'keterangan_dasar_pemeriksaan' => $params['keterangan_dasar_pemeriksaan'],
      'ENCRYPT_IDLHKPN' => $params['ENCRYPT_IDLHKPN'],
      'updated_by' => $this->session->userdata('USERNAME'),
      'updated_date' => date('Y-m-d H:i:s'),
      'updated_ip' => $this->get_client_ip()
    ];

    $this->db->where('id_audit', $id_audit);
    $this->db->where('jenis_penugasan', 1);
    $this->db->where('(status_periksa=2 OR status_periksa=3)');
    $update = $this->db->update('t_lhkpn_audit', $data_update);

    if($update){

      if (($this->db->affected_rows() > 0)) {
        ng::logActivity('Update Data Hasil Analisis Pemeriksaan, ID_AUDIT = '.$id_audit);
      }
      
      return true;
    }

    return false;
  }

  public function create_hasil_analisis($hasil_analisis) {
    $current_user_id = $this->session->userdata('ID_USER');
    $current_date = date('Y-m-d H:i:s');
    $current_user_ip = $this->get_client_ip();

    $data_insert = [
      'ID_AUDIT' => $hasil_analisis['ID_AUDIT'],
      'ID_MATA_UANG' => $hasil_analisis['ID_MATA_UANG'],
      'NILAI_TRANSAKSI' => $hasil_analisis['NILAI_TRANSAKSI'],
      'IS_UNDERLYING_TRANSAKSI' => $hasil_analisis['IS_UNDERLYING_TRANSAKSI'],
      'HASIL_ANALISIS' => $hasil_analisis['HASIL_ANALISIS'],
      'CREATED_BY' => $current_user_id,
      'CREATED_DATE' => $current_date,
      'CREATED_IP' => $current_user_ip,
      'UPDATED_BY' => $current_user_id,
      'UPDATED_DATE' => $current_date,
      'UPDATED_IP' => $current_user_ip
    ];

    $this->db->insert('t_lhkpn_audit_hasil_analisis', $data_insert);

    $is_inserted = $this->db->affected_rows() > 0;

    if ($is_inserted) {
      ng::logActivity('Create Data Hasil Analisis, ID = '.$this->db->insert_id());
    }

    return $is_inserted;
  }

  public function update_hasil_analisis($id, $hasil_analisis) {
    $this->db->where('ID_HASIL_ANALISIS', $id);
    $q = $this->db->get('t_lhkpn_audit_hasil_analisis');

    if ($q->num_rows() > 0) {
      $data_update = [
        'ID_MATA_UANG' => $hasil_analisis['ID_MATA_UANG'],
        'NILAI_TRANSAKSI' => $hasil_analisis['NILAI_TRANSAKSI'],
        'HASIL_ANALISIS' => $hasil_analisis['HASIL_ANALISIS'],
        'IS_UNDERLYING_TRANSAKSI' => $hasil_analisis['IS_UNDERLYING_TRANSAKSI'],
        'UPDATED_BY' => $this->session->userdata('ID_USER'),
        'UPDATED_DATE' => date('Y-m-d H:i:s'),
        'UPDATED_IP' => $this->get_client_ip()
      ];

      $this->db->where('ID_HASIL_ANALISIS', $id);
      $this->db->update('t_lhkpn_audit_hasil_analisis', $data_update);

      $is_updated = $this->db->affected_rows() > 0;

      if ($is_updated) {
        ng::logActivity('Update Data Hasil Analisis, ID = '.$id);
      }

      return $is_updated;
    }

    return false;
  }

  public function soft_delete_hasil_analisis($id) {
    $this->db->where('ID_HASIL_ANALISIS', $id);
    $q = $this->db->get('t_lhkpn_audit_hasil_analisis');

    if ($q->num_rows() > 0) {
      $data_update = [
        'DELETED_BY' => $this->session->userdata('ID_USER'),
        'DELETED_DATE' => date('Y-m-d H:i:s'),
        'DELETED_IP' => $this->get_client_ip()
      ];

      $this->db->where('ID_HASIL_ANALISIS', $id);
      $this->db->update('t_lhkpn_audit_hasil_analisis', $data_update);

      $is_updated = $this->db->affected_rows() > 0;

      if ($is_updated) {
        ng::logActivity('Hapus Data Hasil Analisis, ID = '.$id);
      }

      return $is_updated;
    }

    return false;
  }
}
