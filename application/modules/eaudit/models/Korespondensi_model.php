<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 /**
  * Primaningtyas
  * 7 Februari 2018
  */
 class Korespondensi_model extends CI_Model
 {

   protected $t_surat = 't_eaudit_surat';
   protected $t_surat_lampiran = 't_eaudit_surat_lampiran';
   protected $t_surat_balasan = 't_eaudit_balasan';
   protected $m_surat_pemenuhan = 'm_surat_pemenuhan';

   var $table = 't_eaudit_surat';
   var $column_order = array('SURAT_NOMOR', 'SURAT_TANGGAL', 'SURAT_INSTANSI_ID', 'SURAT_HAL', NULL, 'SURAT_STATUS_ID', 'SURAT_PEMENUHAN_ID', NULL);
   var $column_search = array('SURAT_NOMOR', 'SURAT_TANGGAL', 'ORG_NAMA', 'PEMENUHAN_NAMA', 'NAMA_PN');
   var $sql_korespondensi_data = "
   SELECT * FROM t_eaudit_surat
   ";

   var $sql_user_publication = "
  SELECT
    t_lhkpn.ID_LHKPN,
    t_lhkpn.tgl_kirim_final,
    t_pn.NIK,
    t_pn.ID_PN,
    t_lhkpn.USERNAME_ENTRI,
    t_lhkpn_jabatan.DESKRIPSI_JABATAN,
    t_pn_jabatan.NAMA_LEMBAGA,
    t_lhkpn.JENIS_LAPORAN
  FROM
    t_lhkpn
        JOIN
    t_pn ON t_lhkpn.ID_PN = t_pn.ID_PN
        JOIN
    t_lhkpn_jabatan ON t_lhkpn.ID_LHKPN = t_lhkpn_jabatan.ID_LHKPN
        JOIN
    t_pn_jabatan ON t_lhkpn_jabatan.ID_JABATAN = t_pn_jabatan.ID_JABATAN
  WHERE
    status IN (4 , 6)
    AND t_lhkpn.tgl_kirim_final >= 2017
  GROUP BY t_lhkpn.ID_LHKPN
    ";

   public function get_users_publication()
  {
    return $this->db->query($this->sql_user_publication)->result();
  }

  public function nu_init_korespondensi()
  {
    $rolePemeriksa = $this->session->userdata('ID_ROLE_AUDIT');
    $idPemeriksa = $this->session->userdata('ID_USER');

    // 24 -> tim pemeriksaan, 25 -> koor pemeriksaan, 27 -> katim pemeriksaan
    // if (strpos($this->session->userdata('ID_ROLE_AUDIT'), '24') && !strpos($this->session->userdata('ID_ROLE_AUDIT'), '25')) {
    //   $whereInQuery = "AND lamp.ID_LHKPN IN (SELECT ID_LHKPN FROM t_lhkpn_audit T WHERE T.id_pemeriksa = ".$idPemeriksa.")";
    // }
    // elseif (strpos($this->session->userdata('ID_ROLE_AUDIT'), '25') && !strpos($this->session->userdata('ID_ROLE_AUDIT'), '24')) {
    //   $whereInQuery = "AND lamp.ID_LHKPN IN (SELECT ID_LHKPN FROM t_lhkpn_audit T WHERE T.id_creator = ".$idPemeriksa.")";
    // }
    // elseif (strpos($this->session->userdata('ID_ROLE_AUDIT'), '27') && !strpos($this->session->userdata('ID_ROLE_AUDIT'), '24') && !strpos($this->session->userdata('ID_ROLE_AUDIT'), '25')) {
    //   $whereInQuery = "AND lamp.ID_LHKPN IN (SELECT ID_LHKPN FROM t_lhkpn_audit T WHERE T.id_creator = ".$idPemeriksa.")";
    // }

    $this->db->select('surat.SURAT_ID as ID_SURAT_KELUAR, surat.SURAT_NOMOR, surat.SURAT_TANGGAL, surat.SURAT_CREATED_DATE,
        surat.ORG_NAMA, surat.ORG_TEMBUSAN_SURAT, surat.ORG_GEDUNG, surat.ORG_ALAMAT, surat.ORG_PROVINSI, surat.SURAT_SIFAT, surat.balasan_id, surat.NAMA_PN, surat.PEMENUHAN_ID,
        surat.PEMENUHAN_NAMA, surat.SURAT_PEMENUHAN_STATUS, surat.SURAT_LAMPIRAN, surat.SURAT_TEMPLATE_ID, surat.SURAT_INSTANSI_ID,
        surat.SURAT_HAL, surat.SURAT_PENANDATANGAN_ID, surat.SURAT_KONTAK, surat.PENANDATANGAN_NAMA, surat.PENANDATANGAN_JABATAN,
        surat.SURAT_KONTAK_2, surat.SURAT_STATUS, surat.ORG_TIPE', false);
    $this->db->from("(SELECT
        s.SURAT_ID,
            s.SURAT_NOMOR,
            s.SURAT_TANGGAL,
            s.SURAT_SIFAT,
            s.SURAT_LAMPIRAN,
            s.SURAT_HAL,
            s.SURAT_TEMPLATE_ID,
            s.SURAT_INSTANSI_ID,
            s.SURAT_PENANDATANGAN_ID,
            s.SURAT_PEMENUHAN_STATUS,
            s.SURAT_CREATED_DATE,
            sp.PENANDATANGAN_NAMA,
            sp.PENANDATANGAN_JABATAN,
            s.SURAT_KONTAK,
            s.SURAT_KONTAK_2,
            s.SURAT_STATUS,
            org.ORG_NAMA,
            org.ORG_TEMBUSAN_SURAT,
            org.ORG_GEDUNG,
            org.ORG_ALAMAT,
            org.ORG_PROVINSI,
            o.ORG_TIPE,
            lamp.NAMA_PN as NAMA_PN_OLD,
            group_concat(distinct lamp.NAMA_PN separator ', ' ) as NAMA_PN,
            mp.PEMENUHAN_ID,
            mp.PEMENUHAN_NAMA,
            MAX(b.BALASAN_ID) AS balasan_id
    FROM
        t_eaudit_surat s
    LEFT JOIN t_eaudit_surat_lampiran lamp ON s.SURAT_ID = lamp.ID_SURAT_KELUAR
    LEFT JOIN m_org o ON s.SURAT_TEMPLATE_ID = o.ORG_KD
    LEFT JOIN m_org_tujuan org ON s.SURAT_INSTANSI_ID = org.ORG_TUJUANKD
    LEFT JOIN m_status_surat ss ON s.SURAT_STATUS_ID = ss.STATUS_ID
    LEFT JOIN t_eaudit_balasan b ON s.SURAT_ID = b.SURAT_ID
    LEFT JOIN m_surat_pemenuhan mp ON b.BALASAN_STATUS_PEMENUHAN_ID = mp.PEMENUHAN_ID
    LEFT JOIN m_surat_penandatangan sp ON s.SURAT_PENANDATANGAN_ID = sp.PENANDATANGAN_ID
    WHERE
        s.IS_ACTIVE = 1 ".$whereInQuery."
    GROUP BY s.SURAT_ID
    ORDER BY s.SURAT_ID DESC) AS surat");

    if ($this->input->post('namaInstansi')) {
      $this->db->like('surat.ORG_NAMA', $this->input->post('namaInstansi'));
    }
    if ($this->input->post('nomorSurat')) {
      $this->db->like('surat.SURAT_NOMOR', $this->input->post('nomorSurat'));
    }
    if ($this->input->post('namaPn')) {
      $this->db->like('surat.NAMA_PN', $this->input->post('namaPn'));
    }
    if ($this->input->post('tanggalSurat')) {
      $tanggal = implode('-', array_reverse(explode('/', $this->input->post('tanggalSurat'))));
      $this->db->like('surat.SURAT_TANGGAL', $tanggal);
    }
    if ($this->input->post('statusPemenuhan')) {
      $this->db->where('surat.SURAT_PEMENUHAN_STATUS', $this->input->post('statusPemenuhan'));
    }

  }

   function init_db_surat()
   {

     // $this->db->select('s.SURAT_ID, s.SURAT_HAL, s.SURAT_INSTANSI_ID, s.SURAT_STATUS_ID, s.SURAT_PENANDATANGAN_ID,
     //            s.SURAT_NOMOR, s.SURAT_TANGGAL, s.SURAT_INSTANSI_ID, org.ORG_NAMA,
     //            lamp.NAMA_PN, MAX(b.BALASAN_ID), b.BALASAN_STATUS_PEMENUHAN_ID, mp.PEMENUHAN_NAMA, s.IS_ACTIVE');
     $this->db->select('*, MAX(b.BALASAN_ID)');
     $this->db->from('t_eaudit_surat s');
     $this->db->join('t_eaudit_surat_lampiran lamp', 's.SURAT_ID = lamp.ID_SURAT_KELUAR', 'left');
     $this->db->join('m_org_tujuan org', 's.SURAT_INSTANSI_ID = org.ORG_TUJUANKD', 'left');
     $this->db->join('m_status_surat ss', 's.SURAT_STATUS_ID = ss.STATUS_ID', 'left');
     $this->db->join('t_eaudit_balasan b', 's.SURAT_ID = b.SURAT_ID', 'left');
     $this->db->join('m_surat_pemenuhan mp', 'b.BALASAN_STATUS_PEMENUHAN_ID = mp.PEMENUHAN_ID', 'left');
     $this->db->join('m_surat_penandatangan sp', 's.SURAT_PENANDATANGAN_ID = sp.PENANDATANGAN_ID', 'left');
     $this->db->group_by('s.SURAT_ID');
     $this->db->where("s.IS_ACTIVE = 1 ");
     // Sub Query
     // $subQuery =  $this->db->get_compiled_select();

     // $this->db->select('')
     //     ->from("$subQuery");
     // var_dump($this->db->get_compiled_select());
     // die();
	 // $where_clause = "s.IS_ACTIVE = 1 ";
	 //

  	 if ($_POST['namaInstansi']) {
  		$this->db->like('org.ORG_NAMA', $_POST['namaInstansi']);
  	 }
  	 if ($_POST['nomorSurat']) {
      $this->db->like('s.SURAT_NOMOR', $_POST['nomorSurat']);
  	 }
  	 if ($_POST['namaPn']) {
      $this->db->like('lamp.NAMA_PN', $_POST['namaPn']);
  	 }
  	 if ($_POST['tanggalSurat']) {
      $tanggal = implode('-', array_reverse(explode('/', $_POST['tanggalSurat'])));
      $this->db->like('s.SURAT_TANGGAL', $tanggal);
  	 }
  	 if ($_POST['statusPemenuhan']) {
      $this->db->where('b.BALASAN_STATUS_PEMENUHAN_ID', $_POST['statusPemenuhan']);
  	 }

   }

   // call the data from datatables query
   public function get_datatables()
   {
     $this->_get_datatables_query();
     if($_POST['length'] != -1)
     $this->db->limit($_POST['length'], $_POST['start']);

     $query = $this->db->get(); //display($this->db->last_query());die;
     return $query->result();
   }

   private function _get_datatables_query()
   {
     $temp = $this->nu_init_korespondensi();

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
           $this->db->or_like($item, $_POST['search']['value']);
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

   function count_filtered()
   {
       $this->_get_datatables_query();
       $query = $this->db->get();

       return $query->num_rows();
   }

   public function count_all()
   {
       $this->_get_datatables_query();
       return $this->db->count_all_results();
   }
   // call the data from datatables query
   public function get_korespondensi_data()
   {
     $this->nu_init_korespondensi();

     if(isset($_POST['length'])){
       if($_POST['length'] != -1)
          $this->db->limit($_POST['length'], $_POST['start']);
     }
     $query = $this->db->get();
     return $query->result_array();
   }
   public function savesurat($data)
   {
       $this->db->insert('t_eaudit_surat', $data);
       $surat_id = $this->db->insert_id();
       return $surat_id;
   }
   public function savelampiran($data)
   {
     $this->db->insert('t_eaudit_surat_lampiran', $data);
     $lampiran_id = $this->db->insert_id();
     return $lampiran_id;
   }
   public function deactivelampiran($suratID)
   {
     $this->db->select('ID_LAMPIRAN, IS_ACTIVE');
     $this->db->from('t_eaudit_surat_lampiran');
     $this->db->where('t_eaudit_surat_lampiran.ID_SURAT_KELUAR', $suratID);
     $query = $this->db->get();
     $result = $query->result_array();

     foreach ($result as $lamp) {
       $lamp['IS_ACTIVE'] = "0";
       $this->db->where('ID_LAMPIRAN', $lamp['ID_LAMPIRAN']);
       $this->db->update('t_eaudit_surat_lampiran', $lamp);

       // updateLampiranData($lamp);
     }

     // for($i = 0; $i < sizeof($lampiranData); $i++)
     // {
     //   $lampiranData[$i]["IS_ACTIVE"] = "0";
     //   $this->db->update('t_eaudit_surat_lampiran', $lampiranData[$i]);
     //   $this->db->where('ID_LAMPIRAN', $lampiranData[$i]["ID_LAMPIRAN"]);
     // }

     // var_dump($lampiranData);
     // die();

     // $this->db->update('t_eaudit_surat_lampiran', $lampiranData);
     // $this->db->where('ID_SURAT_KELUAR', $suratID);
     if ($this->db->affected_rows() > 0) {
       return true;
     }
     else {
       return false;
     }
   }

   public function savesuratbalasan($data)
   {
       $this->db->insert('t_eaudit_balasan', $data);
       $balasan_id = $this->db->insert_id();

       $surat = array( 'SURAT_ID' => $data['SURAT_ID'],
                       'SURAT_STATUS' => 'Dibalas',
                        'SURAT_MODIFIED_DATE' => date('Y-m-d H:i:s'),
                        'SURAT_MODIFIED_BY' => $this->session->userdata('USR'),
                        'SURAT_MODIFIED_IP' => $_SERVER["REMOTE_ADDR"],
                        'SURAT_PEMENUHAN_STATUS' => $data['BALASAN_STATUS_PEMENUHAN_ID']
                        );
       $this->updatesurat($surat);
       return $balasan_id;
   }
   public function addLogCetakSurat($data)
   {
     $this->db->insert('t_eaudit_surat_cetak', $data);
     $cetak_id = $this->db->insert_id();
     return $cetak_id;
   }

   public function updatesurat($data)
   {
       $this->db->where('SURAT_ID', $data['SURAT_ID']);
       $this->db->update('t_eaudit_surat', $data);
       if ($this->db->affected_rows() > 0) {
         return $data['SURAT_ID'];
       }
       else{
         return false;
       }
   }

   public function updateLampiranPN($id_lampiran)
   {
     $lampiran = array( 'ID_LAMPIRAN' => $id_lampiran,
                     'IS_ACTIVE' => '0',
                      );
    $this->updateLampiranData($lampiran);
   }

   // update is_active lampiran
   public function updateLampiranData ($data)
   {
       $this->db->where('ID_LAMPIRAN', $data['ID_LAMPIRAN']);
       $this->db->update('t_eaudit_surat_lampiran', $data);
       if ($this->db->affected_rows() > 0) {
         return true;
       }
       else {
         return false;
       }
   }

   public function get_korespondensi_data2()
   {
     return $this->db->get($this->t_surat)->result_array();
   }

   public function get_selected_surat($suratId)
   {
     $this->nu_init_korespondensi();
     $this->db->where('surat.SURAT_ID', $suratId);
     $query = $this->db->get();
     $result = $query->result_array();
     return $result;
   }

   public function get_selected_balasan($suratId)
   {
     $this->db->select('');
     $this->db->from('t_eaudit_balasan');
     $this->db->join('m_surat_pemenuhan', 't_eaudit_balasan.BALASAN_STATUS_PEMENUHAN_ID = m_surat_pemenuhan.PEMENUHAN_ID', 'left');
     $this->db->where('SURAT_ID', $suratId);
     $this->db->order_by('BALASAN_ID','desc');
     $query = $this->db->get();
     $result = $query->result_array();
     return $result;
   }

   public function get_selected_pn($id_lhkpn)
   {
     $this->db->select('');
     $this->db->from('T_LHKPN');
     $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
     $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN.ID_LHKPN = T_LHKPN_JABATAN.ID_LHKPN', 'left');
     $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
     $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD', 'left');
     // $this->db->join('T_LHKPN_KELUARGA', 'T_LHKPN.ID_LHKPN = T_LHKPN_KELUARGA.ID_LHKPN', 'left');
     $this->db->where('T_LHKPN.ID_LHKPN', $id_lhkpn);
     // $this->db->order_by('HUBUNGAN','asc');
     $query = $this->db->get();
     // $result = $query->result_array();
     $result = $query->result();
     return $result;
   }

   public function get_selected_pn_by_st($idPemeriksa, $nomorSuratTugas, $termasukKeluarga)
   {
     $this->db->select('');
     $this->db->from('T_LHKPN_AUDIT');
     $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
     $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
     $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN.ID_LHKPN = T_LHKPN_JABATAN.ID_LHKPN', 'left');
     $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
     $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD', 'left');
     $this->db->where('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
     if(strpos($this->session->userdata('ID_ROLE_AUDIT'), '24')) //pemeriksa
       $this->db->where('T_LHKPN_AUDIT.id_pemeriksa', $idPemeriksa);
     $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
     // else if($rolePemeriksa == 25) //pemeriksa
     //   $this->db->where('T_LHKPN_AUDIT.id_creator', $idPemeriksa);
     $this->db->group_by('T_LHKPN_AUDIT.ID_LHKPN');
     $query = $this->db->get();
     $resultFinal = $query->result();
     if($termasukKeluarga == 'true')
     {
       $this->db->select(' ');
       $this->db->from('t_lhkpn_audit');
       $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
       $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_audit.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN', 'left');
       $this->db->join('T_LHKPN_KELUARGA', 'T_LHKPN.ID_LHKPN = T_LHKPN_KELUARGA.ID_LHKPN', 'left');

       $this->db->where('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
       $where_umur = date("Y")." - YEAR(T_LHKPN_KELUARGA.TANGGAL_LAHIR) > 16";
       $this->db->where($where_umur);
       $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
       $this->db->group_by('T_LHKPN_KELUARGA.ID_KELUARGA');
       $queryKeluarga = $this->db->get();
       $resultKeluarga = $queryKeluarga->result();
       $resultFinal = array_merge($resultFinal, $resultKeluarga);
     }
     return $resultFinal;
   }

   function sortById($x, $y) {
     return $x['id_audit'] - $y['id_audit'];
  }

   public function get_nomor_surat_tugas($id_pemeriksa)
   {
     // $this->db->select('');
     // $this->db->from('T_LHKPN_AUDIT');
     // $this->db->where('ID_PEMERIKSA', $id_pemeriksa);
     // $this->db->order_by('NOMOR_SURAT_TUGAS','asc');
     // // $this->db->group_by('NOMOR_SURAT_TUGAS');
     $this->db->select('');
     $this->db->from('T_LHKPN_AUDIT');
     $this->db->order_by('NOMOR_SURAT_TUGAS','asc');
     $query = $this->db->get();
     $result = $query->result_array();
     var_dump($result);
     return $result;
   }

   public function get_pn_by_name($idPemeriksa, $nama, $termasukKeluarga)
   {
     $this->db->select('T_PN.NAMA, USERNAME_ENTRI, tgl_kirim_final, JENIS_LAPORAN, NIK, T_LHKPN.ID_LHKPN, INST_NAMA, DESKRIPSI_JABATAN ');
     $this->db->from('T_LHKPN_AUDIT');
     $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
     $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
     $this->db->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
     $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
     $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
     if(strpos($this->session->userdata('ID_ROLE_AUDIT'), '24')) //pemeriksa
        $this->db->where('T_LHKPN_AUDIT.id_pemeriksa', $idPemeriksa);
     $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
     $this->db->like('T_PN.NAMA', $nama);
     $this->db->order_by('T_PN.NAMA','asc');
     $query = $this->db->get();
     $resultFinal = $query->result();

     if($termasukKeluarga == 'true') {
       // // $this->db->from('T_LHKPN_KELUARGA');
       //
       // $this->db->select('t_lhkpn_keluarga.ID_KELUARGA, t_lhkpn_keluarga.HUBUNGAN, t_lhkpn_keluarga.NAMA');
       // $this->db->from('t_lhkpn_audit');
       // $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
       // // $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
       // // $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_audit.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN', 'left');
       // $this->db->join('T_LHKPN_KELUARGA', 'T_LHKPN.ID_LHKPN = T_LHKPN_KELUARGA.ID_LHKPN', 'left');
       // $this->db->like('T_LHKPN.USERNAME_ENTRI', $nama);
       // // // $this->db->where('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
       // // $this->db->like('T_PN.NAMA', $nama);
       // $where_umur = date("Y")." - YEAR(T_LHKPN_KELUARGA.TANGGAL_LAHIR) > 16";
       // $this->db->where($where_umur);
       // $queryKeluarga = $this->db->get();
       // $resultKeluarga = $queryKeluarga->result();
       // $resultFinal = array_merge($resultFinal, $resultKeluarga);
       // // var_dump($resultFinal);
       // // die();

       // $this->db->select('USERNAME_ENTRI, tgl_kirim_final, JENIS_LAPORAN, t_lhkpn_data_pribadi.NIK, T_LHKPN.ID_LHKPN, T_LHKPN_KELUARGA.NAMA ');
       $this->db->select('');
       $this->db->from('t_lhkpn_audit');
       $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
       $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_audit.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN', 'left');
       $this->db->join('T_LHKPN_KELUARGA', 'T_LHKPN.ID_LHKPN = T_LHKPN_KELUARGA.ID_LHKPN', 'left');
       $this->db->like('T_LHKPN.USERNAME_ENTRI', $nama);
       $where_umur = date("Y")." - YEAR(T_LHKPN_KELUARGA.TANGGAL_LAHIR) > 16";
       $this->db->where($where_umur);
       $queryKeluarga = $this->db->get();
       $resultKeluarga = $queryKeluarga->result();
       $resultFinal = array_merge($resultFinal, $resultKeluarga);
     }
     return $resultFinal;
   }

   //backup
   // public function get_pn_by_st_name($idPemeriksa, $nama, $nomorSuratTugas, $termasukKeluarga)
   // {
   //   $rolePemeriksa = $this->session->userdata('ID_ROLE');
   //
   //   $this->db->select('');
   //   $this->db->from('T_LHKPN_AUDIT');
   //   $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
   //   $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
   //   if($rolePemeriksa == 24) //pemeriksa
   //      $this->db->where('T_LHKPN_AUDIT.id_pemeriksa', $idPemeriksa);
   //   // $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
   //   $this->db->where('status_periksa != 3');
   //   $this->db->like('T_PN.NAMA', $nama);
   //   $this->db->like('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
   //   $this->db->order_by('T_PN.NAMA','asc');
   //   // var_dump($this->db->last_query());
   //   // die();
   //   $query = $this->db->get();
   //   $resultFinal = $query->result();
   //   return $resultFinal;
   // }
   public function get_pn_by_st_name($idPemeriksa, $nama, $nomorSuratTugas, $termasukKeluarga)
   {
     $this->db->select('');
     $this->db->from('T_LHKPN_AUDIT');
     $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
     $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
     $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN.ID_LHKPN = T_LHKPN_JABATAN.ID_LHKPN', 'left');
     $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
     $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD', 'left');
     $this->db->where('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
     if(strpos($this->session->userdata('ID_ROLE_AUDIT'), '24')) //pemeriksa
       $this->db->where('T_LHKPN_AUDIT.id_pemeriksa', $idPemeriksa);
     $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
     $this->db->like('T_PN.NAMA', $nama);
     $this->db->group_by('T_LHKPN_AUDIT.ID_LHKPN');
     $query = $this->db->get();
     $resultFinal = $query->result();
     if($termasukKeluarga == 'true')
     {
       $this->db->select(' ');
       $this->db->from('t_lhkpn_audit');
       $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
       $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_audit.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN', 'left');
       $this->db->join('T_LHKPN_KELUARGA', 'T_LHKPN.ID_LHKPN = T_LHKPN_KELUARGA.ID_LHKPN', 'left');

       $this->db->where('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
       $where_umur = date("Y")." - YEAR(T_LHKPN_KELUARGA.TANGGAL_LAHIR) > 16";
       $this->db->where($where_umur);
       $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
       $this->db->like('NAMA_LENGKAP', $nama);
       $this->db->group_by('T_LHKPN_KELUARGA.ID_KELUARGA');
       $queryKeluarga = $this->db->get();
       $resultKeluarga = $queryKeluarga->result();
       $resultFinal = array_merge($resultFinal, $resultKeluarga);
     }
     return $resultFinal;
   }

   public function get_detail_surat_by_id($surat_id)
   {
     $this->db->select('NAMA_PN');
     $this->db->where('ID_SURAT_KELUAR', $surat_id);
     $this->db->where('ID_KELUARGA is null');
     // $this->db->where('IS_ACTIVE', '1');
     $this->db->DISTINCT();
     $data = $this->db->get($this->t_surat_lampiran);
     if ($data->num_rows() > 0) {
       return $data->result_array();
     }
     else{
       return false;
     }
   }


   public function getDataKontak($idSurat, $idKontak)
   {
     $this->db->select('');
     $this->db->from('t_eaudit_surat');
     $this->db->join('t_user', 't_eaudit_surat.SURAT_KONTAK = t_user.ID_USER', 'left');
     $this->db->where('SURAT_ID', $idSurat);
     $query1 = $this->db->get()->result();

     return $query1;
   }

   public function getDataKontak2($idSurat, $idKontak2)
   {
     $this->db->select('');
     $this->db->from('t_eaudit_surat');
     $this->db->join('t_user', 't_eaudit_surat.SURAT_KONTAK_2 = t_user.ID_USER', 'left');
     $this->db->where('SURAT_ID', $idSurat);
     $query2 = $this->db->get()->result();

     return $query2;
   }

   public function get_lampiran_by_suratID($surat_id)
   {
     $this->db->select('USERNAME_ENTRI, HUBUNGAN, tgl_kirim_final, JENIS_LAPORAN, t_pn.NIK, T_LHKPN.ID_LHKPN, INST_NAMA, DESKRIPSI_JABATAN, t_pn.NAMA, t_lhkpn_keluarga.ID_KELUARGA');
     $this->db->from('t_eaudit_surat_lampiran');
     $this->db->where('ID_SURAT_KELUAR', $surat_id);
     $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = t_eaudit_surat_lampiran.ID_LHKPN', 'left');
     $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN', 'left');
     $this->db->join('t_lhkpn_keluarga', 't_eaudit_surat_lampiran.ID_KELUARGA = t_lhkpn_keluarga.ID_KELUARGA', 'left');
     $this->db->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
     $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
     $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
     $query = $this->db->get();
     $resultFinal = $query->result_array();

     return $resultFinal;
   }

   public function get_lampiran_by_suratID_cetak($surat_id)
   {
     $this->db->select('SURAT_NOMOR, SURAT_TANGGAL, ID_LAMPIRAN, ID_SURAT_KELUAR, T_LHKPN.ID_LHKPN, tgl_kirim_final, JENIS_LAPORAN, t_pn.NIK,
                        t_eaudit_surat_lampiran.ID_KELUARGA, t_pn.ID_PN, INST_NAMA, DESKRIPSI_JABATAN, t_lhkpn_data_pribadi.NAMA_LENGKAP,
                        USERNAME_ENTRI, t_pn.TEMPAT_LAHIR TempatLahirPN, SURAT_PENANDATANGAN_ID,
                        t_pn.TGL_LAHIR, HUBUNGAN, t_lhkpn_keluarga.NAMA, t_lhkpn_keluarga.TEMPAT_LAHIR TempatLahirKlg,
                        t_lhkpn_keluarga.TANGGAL_LAHIR, t_pn.ALAMAT_TINGGAL, t_lhkpn_data_pribadi.ALAMAT_RUMAH, t_lhkpn_data_pribadi.KELURAHAN, t_lhkpn_data_pribadi.KECAMATAN, t_lhkpn_data_pribadi.KABKOT, t_lhkpn_data_pribadi.PROVINSI');
     $this->db->from('t_eaudit_surat_lampiran');
     $this->db->where('ID_SURAT_KELUAR', $surat_id);
     $this->db->where('t_eaudit_surat_lampiran.IS_ACTIVE', "1");
     $this->db->join('t_eaudit_surat', 't_eaudit_surat.SURAT_ID = t_eaudit_surat_lampiran.ID_SURAT_KELUAR', 'left');
     $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = t_eaudit_surat_lampiran.ID_LHKPN', 'left');
     $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN', 'left');
     $this->db->join('t_lhkpn_keluarga', 't_eaudit_surat_lampiran.ID_KELUARGA = t_lhkpn_keluarga.ID_KELUARGA', 'left');
     $this->db->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
     $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
     $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
     $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_data_pribadi.id_lhkpn = t_lhkpn.id_lhkpn', 'left');
     $this->db->group_by('ID_LAMPIRAN, ID_SURAT_KELUAR, t_eaudit_surat_lampiran.ID_LHKPN');

     $query = $this->db->get();
     $resultFinal = $query->result();
     return $resultFinal;
   }

   public function get_status_pemenuhan($surat_id)
   {
     $this->db->select('surat_id, pemenuhan_nama');
     $this->db->from('t_eaudit_balasan');
     $this->db->join('m_surat_pemenuhan', 't_eaudit_balasan.balasan_status_pemenuhan_id = m_surat_pemenuhan.PEMENUHAN_ID', 'left');
     $this->db->where('IS_ACTIVE', '1');
     $this->db->where('surat_id', $surat_id);
     $this->db->order_by('balasan_id', 'desc');
     $this->db->limit(1);
     $query = $this->db->get();
     $resultFinal = $query->result();
     return $resultFinal;
   }

   public function update_surat_pemenuhan($surat)
   {
      $suratID = $surat->ID_SURAT_KELUAR;
      $this->db->set('SURAT_PEMENUHAN_STATUS', $surat->SURAT_PEMENUHAN_STATUS);
      $this->db->where('SURAT_ID', $suratID);
      $this->db->update('t_eaudit_surat');
     if ($this->db->affected_rows() > 0) {
       return true;
     }
     else{
       return false;
     }
   }
 }
