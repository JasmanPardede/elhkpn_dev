<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 /**
  * @author Ahmad Saughi
  * @version 04122018
  */
class Nodin_model extends CI_Model {

   protected $t_pn = 't_pn';
   protected $t_surat = 't_eaudit_surat';
   protected $t_surat_lampiran = 't_eaudit_surat_lampiran';
   protected $t_surat_balasan = 't_eaudit_balasan';
   protected $m_surat_pemenuhan = 'm_surat_pemenuhan';

   var $table = 't_eaudit_nodin';
  //  var $column_order = array('NOMOR_NOTA_DINAS', 'TANGGAL_NOTA_DINAS', 'ID');
   var $column_search = array('NOMOR_NOTA_DINAS', 'TANGGAL_NOTA_DINAS', 't_eaudit_nodin_lhkpn.NAMA', 'TUJUAN_NOTA_DINAS');
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

  public function nu_init_notadinas()
  {
    $rolePemeriksa = $this->session->userdata('ID_ROLE_AUDIT');
    $idPemeriksa = $this->session->userdata('ID_USER');

    $this->db->select('t_eaudit_nodin.*,t_eaudit_nodin_lhkpn.*,T_LHKPN_AUDIT.*,T_USER.NAMA AS NAMA_PIC, m_unit_kerja_eaudit.NAMA_UK_EAUDIT AS TUJUAN_NOTA_DINAS', FALSE);
    $this->db->from('t_eaudit_nodin');
    $this->db->join('t_eaudit_nodin_lhkpn', 't_eaudit_nodin_lhkpn.ID_NODIN = t_eaudit_nodin.ID', 'left');
    $this->db->join('T_LHKPN_AUDIT', 'T_LHKPN_AUDIT.ID_AUDIT = t_eaudit_nodin_lhkpn.ID_AUDIT', 'left');
    $this->db->join('T_USER', 'T_LHKPN_AUDIT.ID_PIC = T_USER.ID_USER', 'left');
    $this->db->join('m_unit_kerja_eaudit', 'm_unit_kerja_eaudit.ID_UK_EAUDIT = t_eaudit_nodin.TUJUAN_NOTA_DINAS', 'left');
    $this->db->where('t_eaudit_nodin.IS_ACTIVE', 1);
    $this->db->order_by('TANGGAL_NOTA_DINAS', 'DESC');

    if ($this->input->post('TUJUAN_ND')) {
      $this->db->where('TUJUAN_NOTA_DINAS', $this->input->post('TUJUAN_ND'));
    }
    if ($this->input->post('NOMOR_ND')) {
      $this->db->like('NOMOR_NOTA_DINAS', $this->input->post('NOMOR_ND'));
    }
    if ($this->input->post('NAMA')) {
      $this->db->like('t_eaudit_nodin_lhkpn.NAMA', $this->input->post('NAMA'));
    }
    if ($this->input->post('TANGGAL_ND')) {
      $tanggal = implode('-', array_reverse(explode('/', $this->input->post('TANGGAL_ND'))));
      $this->db->like('TANGGAL_NOTA_DINAS', $tanggal);
    }
    if ($this->input->post('JENIS_ND')) {
      $this->db->where('JENIS_NOTA_DINAS', $this->input->post('JENIS_ND'));
    }

  }

   // call the data from datatables query
   public function get_datatables()
   {
     $this->_get_datatables_query();
     if($_POST['length'] != -1)
     $this->db->limit($_POST['length'], $_POST['start']);

     $query = $this->db->get();
     return $query->result();
   }

   private function _get_datatables_query()
   {
     $temp = $this->nu_init_notadinas();

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
  //  public function get_korespondensi_data()
  //  {
  //    $this->nu_init_notadinas();

  //    if(isset($_POST['length'])){
  //      if($_POST['length'] != -1)
  //         $this->db->limit($_POST['length'], $_POST['start']);
  //    }
  //    $query = $this->db->get();
  //    return $query->result_array();
  //  }
  //  public function savesurat($data)
  //  {
  //      $this->db->insert('t_eaudit_surat', $data);
  //      $surat_id = $this->db->insert_id();
  //      return $surat_id;
  //  }
  //  public function savelampiran($data)
  //  {
  //    $this->db->insert('t_eaudit_surat_lampiran', $data);
  //    $lampiran_id = $this->db->insert_id();
  //    return $lampiran_id;
  //  }
  //  public function deactivelampiran($suratID)
  //  {
  //    $this->db->select('ID_LAMPIRAN, IS_ACTIVE');
  //    $this->db->from('t_eaudit_surat_lampiran');
  //    $this->db->where('t_eaudit_surat_lampiran.ID_SURAT_KELUAR', $suratID);
  //    $query = $this->db->get();
  //    $result = $query->result_array();

  //    foreach ($result as $lamp) {
  //      $lamp['IS_ACTIVE'] = "0";
  //      $this->db->where('ID_LAMPIRAN', $lamp['ID_LAMPIRAN']);
  //      $this->db->update('t_eaudit_surat_lampiran', $lamp);

  //      // updateLampiranData($lamp);
  //    }

  //    // for($i = 0; $i < sizeof($lampiranData); $i++)
  //    // {
  //    //   $lampiranData[$i]["IS_ACTIVE"] = "0";
  //    //   $this->db->update('t_eaudit_surat_lampiran', $lampiranData[$i]);
  //    //   $this->db->where('ID_LAMPIRAN', $lampiranData[$i]["ID_LAMPIRAN"]);
  //    // }

  //    // var_dump($lampiranData);
  //    // die();

  //    // $this->db->update('t_eaudit_surat_lampiran', $lampiranData);
  //    // $this->db->where('ID_SURAT_KELUAR', $suratID);
  //    if ($this->db->affected_rows() > 0) {
  //      return true;
  //    }
  //    else {
  //      return false;
  //    }
  //  }

  //  public function savesuratbalasan($data)
  //  {
  //      $this->db->insert('t_eaudit_balasan', $data);
  //      $balasan_id = $this->db->insert_id();

  //      $surat = array( 'SURAT_ID' => $data['SURAT_ID'],
  //                      'SURAT_STATUS' => 'Dibalas',
  //                       'SURAT_MODIFIED_DATE' => date('Y-m-d H:i:s'),
  //                       'SURAT_MODIFIED_BY' => $this->session->userdata('USR'),
  //                       'SURAT_MODIFIED_IP' => $_SERVER["REMOTE_ADDR"],
  //                       'SURAT_PEMENUHAN_STATUS' => $data['BALASAN_STATUS_PEMENUHAN_ID']
  //                       );
  //      $this->updatesurat($surat);
  //      return $balasan_id;
  //  }
  //  public function addLogCetakSurat($data)
  //  {
  //    $this->db->insert('t_eaudit_surat_cetak', $data);
  //    $cetak_id = $this->db->insert_id();
  //    return $cetak_id;
  //  }

  //  public function updatesurat($data)
  //  {
  //      $this->db->where('SURAT_ID', $data['SURAT_ID']);
  //      $this->db->update('t_eaudit_surat', $data);
  //      if ($this->db->affected_rows() > 0) {
  //        return $data['SURAT_ID'];
  //      }
  //      else{
  //        return false;
  //      }
  //  }

  //  public function updateLampiranPN($id_lampiran)
  //  {
  //    $lampiran = array( 'ID_LAMPIRAN' => $id_lampiran,
  //                    'IS_ACTIVE' => '0',
  //                     );
  //   $this->updateLampiranData($lampiran);
  //  }

  //  // update is_active lampiran
  //  public function updateLampiranData ($data)
  //  {
  //      $this->db->where('ID_LAMPIRAN', $data['ID_LAMPIRAN']);
  //      $this->db->update('t_eaudit_surat_lampiran', $data);
  //      if ($this->db->affected_rows() > 0) {
  //        return true;
  //      }
  //      else {
  //        return false;
  //      }
  //  }

  //  public function get_korespondensi_data2()
  //  {
  //    return $this->db->get($this->t_surat)->result_array();
  //  }

  //  public function get_selected_surat($suratId)
  //  {
  //    $this->nu_init_notadinas();
  //    $this->db->where('surat.SURAT_ID', $suratId);
  //    $query = $this->db->get();
  //    $result = $query->result_array();
  //    return $result;
  //  }

  //  public function get_selected_balasan($suratId)
  //  {
  //    $this->db->select('');
  //    $this->db->from('t_eaudit_balasan');
  //    $this->db->join('m_surat_pemenuhan', 't_eaudit_balasan.BALASAN_STATUS_PEMENUHAN_ID = m_surat_pemenuhan.PEMENUHAN_ID', 'left');
  //    $this->db->where('SURAT_ID', $suratId);
  //    $this->db->order_by('BALASAN_ID','desc');
  //    $query = $this->db->get();
  //    $result = $query->result_array();
  //    return $result;
  //  }

  //  public function get_selected_pn($id_lhkpn)
  //  {
  //    $this->db->select('');
  //    $this->db->from('T_LHKPN');
  //    $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
  //    $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN.ID_LHKPN = T_LHKPN_JABATAN.ID_LHKPN', 'left');
  //    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
  //    $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD', 'left');
  //    // $this->db->join('T_LHKPN_KELUARGA', 'T_LHKPN.ID_LHKPN = T_LHKPN_KELUARGA.ID_LHKPN', 'left');
  //    $this->db->where('T_LHKPN.ID_LHKPN', $id_lhkpn);
  //    // $this->db->order_by('HUBUNGAN','asc');
  //    $query = $this->db->get();
  //    // $result = $query->result_array();
  //    $result = $query->result();
  //    return $result;
  //  }

  //  public function get_selected_pn_by_st($idPemeriksa, $nomorSuratTugas, $termasukKeluarga)
  //  {
  //    $this->db->select('');
  //    $this->db->from('T_LHKPN_AUDIT');
  //    $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
  //    $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
  //    $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN.ID_LHKPN = T_LHKPN_JABATAN.ID_LHKPN', 'left');
  //    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
  //    $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD', 'left');
  //    $this->db->where('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
  //    if(strpos($this->session->userdata('ID_ROLE_AUDIT'), '24')) //pemeriksa
  //      $this->db->where('T_LHKPN_AUDIT.id_pemeriksa', $idPemeriksa);
  //    $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
  //    // else if($rolePemeriksa == 25) //pemeriksa
  //    //   $this->db->where('T_LHKPN_AUDIT.id_creator', $idPemeriksa);
  //    $this->db->group_by('T_LHKPN_AUDIT.ID_LHKPN');
  //    $query = $this->db->get();
  //    $resultFinal = $query->result();
  //    if($termasukKeluarga == 'true')
  //    {
  //      $this->db->select(' ');
  //      $this->db->from('t_lhkpn_audit');
  //      $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
  //      $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_audit.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN', 'left');
  //      $this->db->join('T_LHKPN_KELUARGA', 'T_LHKPN.ID_LHKPN = T_LHKPN_KELUARGA.ID_LHKPN', 'left');

  //      $this->db->where('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
  //      $where_umur = date("Y")." - YEAR(T_LHKPN_KELUARGA.TANGGAL_LAHIR) > 16";
  //      $this->db->where($where_umur);
  //      $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
  //      $this->db->group_by('T_LHKPN_KELUARGA.ID_KELUARGA');
  //      $queryKeluarga = $this->db->get();
  //      $resultKeluarga = $queryKeluarga->result();
  //      $resultFinal = array_merge($resultFinal, $resultKeluarga);
  //    }
  //    return $resultFinal;
  //  }

  //  function sortById($x, $y) {
  //    return $x['id_audit'] - $y['id_audit'];
  // }

  //  public function get_nomor_surat_tugas($id_pemeriksa)
  //  {
  //    // $this->db->select('');
  //    // $this->db->from('T_LHKPN_AUDIT');
  //    // $this->db->where('ID_PEMERIKSA', $id_pemeriksa);
  //    // $this->db->order_by('NOMOR_SURAT_TUGAS','asc');
  //    // // $this->db->group_by('NOMOR_SURAT_TUGAS');
  //    $this->db->select('');
  //    $this->db->from('T_LHKPN_AUDIT');
  //    $this->db->order_by('NOMOR_SURAT_TUGAS','asc');
  //    $query = $this->db->get();
  //    $result = $query->result_array();
  //    var_dump($result);
  //    return $result;
  //  }



  //  //backup
  //  // public function get_pn_by_st_name($idPemeriksa, $nama, $nomorSuratTugas, $termasukKeluarga)
  //  // {
  //  //   $rolePemeriksa = $this->session->userdata('ID_ROLE');
  //  //
  //  //   $this->db->select('');
  //  //   $this->db->from('T_LHKPN_AUDIT');
  //  //   $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
  //  //   $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
  //  //   if($rolePemeriksa == 24) //pemeriksa
  //  //      $this->db->where('T_LHKPN_AUDIT.id_pemeriksa', $idPemeriksa);
  //  //   // $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
  //  //   $this->db->where('status_periksa != 3');
  //  //   $this->db->like('T_PN.NAMA', $nama);
  //  //   $this->db->like('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
  //  //   $this->db->order_by('T_PN.NAMA','asc');
  //  //   // var_dump($this->db->last_query());
  //  //   // die();
  //  //   $query = $this->db->get();
  //  //   $resultFinal = $query->result();
  //  //   return $resultFinal;
  //  // }
  //  public function get_pn_by_st_name($idPemeriksa, $nama, $nomorSuratTugas, $termasukKeluarga)
  //  {
  //    $this->db->select('');
  //    $this->db->from('T_LHKPN_AUDIT');
  //    $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
  //    $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
  //    $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN.ID_LHKPN = T_LHKPN_JABATAN.ID_LHKPN', 'left');
  //    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
  //    $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD', 'left');
  //    $this->db->where('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
  //    if(strpos($this->session->userdata('ID_ROLE_AUDIT'), '24')) //pemeriksa
  //      $this->db->where('T_LHKPN_AUDIT.id_pemeriksa', $idPemeriksa);
  //    $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
  //    $this->db->like('T_PN.NAMA', $nama);
  //    $this->db->group_by('T_LHKPN_AUDIT.ID_LHKPN');
  //    $query = $this->db->get();
  //    $resultFinal = $query->result();
  //    if($termasukKeluarga == 'true')
  //    {
  //      $this->db->select(' ');
  //      $this->db->from('t_lhkpn_audit');
  //      $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN', 'left');
  //      $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_audit.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN', 'left');
  //      $this->db->join('T_LHKPN_KELUARGA', 'T_LHKPN.ID_LHKPN = T_LHKPN_KELUARGA.ID_LHKPN', 'left');

  //      $this->db->where('T_LHKPN_AUDIT.nomor_surat_tugas', $nomorSuratTugas);
  //      $where_umur = date("Y")." - YEAR(T_LHKPN_KELUARGA.TANGGAL_LAHIR) > 16";
  //      $this->db->where($where_umur);
  //      $this->db->where('T_LHKPN_AUDIT.status_periksa != 3');
  //      $this->db->like('NAMA_LENGKAP', $nama);
  //      $this->db->group_by('T_LHKPN_KELUARGA.ID_KELUARGA');
  //      $queryKeluarga = $this->db->get();
  //      $resultKeluarga = $queryKeluarga->result();
  //      $resultFinal = array_merge($resultFinal, $resultKeluarga);
  //    }
  //    return $resultFinal;
  //  }

  //  public function get_detail_surat_by_id($surat_id)
  //  {
  //    $this->db->select('NAMA_PN');
  //    $this->db->where('ID_SURAT_KELUAR', $surat_id);
  //    $this->db->where('ID_KELUARGA is null');
  //    // $this->db->where('IS_ACTIVE', '1');
  //    $this->db->DISTINCT();
  //    $data = $this->db->get($this->t_surat_lampiran);
  //    if ($data->num_rows() > 0) {
  //      return $data->result_array();
  //    }
  //    else{
  //      return false;
  //    }
  //  }


  //  public function getDataKontak($idSurat, $idKontak)
  //  {
  //    $this->db->select('');
  //    $this->db->from('t_eaudit_surat');
  //    $this->db->join('t_user', 't_eaudit_surat.SURAT_KONTAK = t_user.ID_USER', 'left');
  //    $this->db->where('SURAT_ID', $idSurat);
  //    $query1 = $this->db->get()->result();

  //    return $query1;
  //  }

  //  public function getDataKontak2($idSurat, $idKontak2)
  //  {
  //    $this->db->select('');
  //    $this->db->from('t_eaudit_surat');
  //    $this->db->join('t_user', 't_eaudit_surat.SURAT_KONTAK_2 = t_user.ID_USER', 'left');
  //    $this->db->where('SURAT_ID', $idSurat);
  //    $query2 = $this->db->get()->result();

  //    return $query2;
  //  }

  //  public function get_lampiran_by_suratID($surat_id)
  //  {
  //    $this->db->select('USERNAME_ENTRI, HUBUNGAN, tgl_kirim_final, JENIS_LAPORAN, t_pn.NIK, T_LHKPN.ID_LHKPN, INST_NAMA, DESKRIPSI_JABATAN, t_pn.NAMA, t_lhkpn_keluarga.ID_KELUARGA');
  //    $this->db->from('t_eaudit_surat_lampiran');
  //    $this->db->where('ID_SURAT_KELUAR', $surat_id);
  //    $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = t_eaudit_surat_lampiran.ID_LHKPN', 'left');
  //    $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN', 'left');
  //    $this->db->join('t_lhkpn_keluarga', 't_eaudit_surat_lampiran.ID_KELUARGA = t_lhkpn_keluarga.ID_KELUARGA', 'left');
  //    $this->db->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
  //    $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
  //    $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
  //    $query = $this->db->get();
  //    $resultFinal = $query->result_array();

  //    return $resultFinal;
  //  }

  //  public function get_lampiran_by_suratID_cetak($surat_id)
  //  {
  //    $this->db->select('SURAT_NOMOR, SURAT_TANGGAL, ID_LAMPIRAN, ID_SURAT_KELUAR, T_LHKPN.ID_LHKPN, tgl_kirim_final, JENIS_LAPORAN, t_pn.NIK,
  //                       t_eaudit_surat_lampiran.ID_KELUARGA, t_pn.ID_PN, INST_NAMA, DESKRIPSI_JABATAN, t_lhkpn_data_pribadi.NAMA_LENGKAP,
  //                       USERNAME_ENTRI, t_pn.TEMPAT_LAHIR TempatLahirPN, SURAT_PENANDATANGAN_ID,
  //                       t_pn.TGL_LAHIR, HUBUNGAN, t_lhkpn_keluarga.NAMA, t_lhkpn_keluarga.TEMPAT_LAHIR TempatLahirKlg,
  //                       t_lhkpn_keluarga.TANGGAL_LAHIR, t_pn.ALAMAT_TINGGAL, t_lhkpn_data_pribadi.ALAMAT_RUMAH, t_lhkpn_data_pribadi.KELURAHAN, t_lhkpn_data_pribadi.KECAMATAN, t_lhkpn_data_pribadi.KABKOT, t_lhkpn_data_pribadi.PROVINSI');
  //    $this->db->from('t_eaudit_surat_lampiran');
  //    $this->db->where('ID_SURAT_KELUAR', $surat_id);
  //    $this->db->where('t_eaudit_surat_lampiran.IS_ACTIVE', "1");
  //    $this->db->join('t_eaudit_surat', 't_eaudit_surat.SURAT_ID = t_eaudit_surat_lampiran.ID_SURAT_KELUAR', 'left');
  //    $this->db->join('T_LHKPN', 'T_LHKPN.ID_LHKPN = t_eaudit_surat_lampiran.ID_LHKPN', 'left');
  //    $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN', 'left');
  //    $this->db->join('t_lhkpn_keluarga', 't_eaudit_surat_lampiran.ID_KELUARGA = t_lhkpn_keluarga.ID_KELUARGA', 'left');
  //    $this->db->join('t_lhkpn_jabatan', 't_lhkpn_jabatan.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
  //    $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
  //    $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
  //    $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_data_pribadi.id_lhkpn = t_lhkpn.id_lhkpn', 'left');
  //    $this->db->group_by('ID_LAMPIRAN, ID_SURAT_KELUAR, t_eaudit_surat_lampiran.ID_LHKPN');

  //    $query = $this->db->get();
  //    $resultFinal = $query->result();
  //    return $resultFinal;
  //  }

  //  public function get_status_pemenuhan($surat_id)
  //  {
  //    $this->db->select('surat_id, pemenuhan_nama');
  //    $this->db->from('t_eaudit_balasan');
  //    $this->db->join('m_surat_pemenuhan', 't_eaudit_balasan.balasan_status_pemenuhan_id = m_surat_pemenuhan.PEMENUHAN_ID', 'left');
  //    $this->db->where('IS_ACTIVE', '1');
  //    $this->db->where('surat_id', $surat_id);
  //    $this->db->order_by('balasan_id', 'desc');
  //    $this->db->limit(1);
  //    $query = $this->db->get();
  //    $resultFinal = $query->result();
  //    return $resultFinal;
  //  }

  //  public function update_surat_pemenuhan($surat)
  //  {
  //     $suratID = $surat->ID_SURAT_KELUAR;
  //     $this->db->set('SURAT_PEMENUHAN_STATUS', $surat->SURAT_PEMENUHAN_STATUS);
  //     $this->db->where('SURAT_ID', $suratID);
  //     $this->db->update('t_eaudit_surat');
  //    if ($this->db->affected_rows() > 0) {
  //      return true;
  //    }
  //    else{
  //      return false;
  //    }
  //  }

    // AHMAD SAUGHI
    public function insert_data($posted_fields, $table) {
        $this->db->insert($table, $posted_fields);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_data($posted_fields, $id, $table) { 
		$this->db->where('ID', $id);
        $this->db->update($table, $posted_fields);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_data($id, $table) { 
      $this->db->where('ID', $id);
      $this->db->delete($table);
      if ($this->db->affected_rows() > 0) {
          return true;
      } else {
          return false;
      }
    }

    public function get_pn_by_name($nama) {
      $where = "(T_PN.NAMA LIKE '%" . $nama . "%' OR T_PN.NIK = '" . $nama . "')";
      $this->db->select('T_PN.NAMA, T_PN.NIK, T_LHKPN.ID_LHKPN, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, T_LHKPN.tgl_lapor, T_LHKPN.ID_LHKPN, T_LHKPN.JENIS_LAPORAN');
      $this->db->select(',(SELECT MAX(T_LHKPN_AUDIT.`id_audit`) FROM t_lhkpn_audit WHERE t_lhkpn_audit.`id_lhkpn` = T_LHKPN.`ID_LHKPN` GROUP BY T_LHKPN_AUDIT.`id_lhkpn`) AS id_audit');
      $this->db->select(',coalesce((SELECT GROUP_CONCAT(DISTINCT nomor_surat_tugas) FROM t_lhkpn_audit WHERE t_lhkpn_audit.`id_lhkpn` = T_LHKPN.`ID_LHKPN` GROUP BY T_LHKPN_AUDIT.`id_lhkpn`),"--") AS no_st');
      $this->db->from('T_LHKPN');
      $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN', 'left');
      $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
      $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'left');
      $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD', 'left');
      $this->db->where($where);
      $this->db->where('T_LHKPN.IS_ACTIVE', 1);
      $this->db->where('T_PN.IS_ACTIVE', 1);
      $this->db->where('T_LHKPN.STATUS !=', '0');
      $this->db->where('T_LHKPN.JENIS_LAPORAN <>', '5');
      $this->db->where('T_LHKPN_JABATAN.IS_PRIMARY', 1);
      //$this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
      $this->db->order_by('T_PN.NAMA', 'ASC');
       
      $query = $this->db->get();
      $resultFinal = $query->result();
      return $resultFinal;
    }
    
    public function delete_data_nodin($id, $table) { 
      $this->db->where('ID_NODIN', $id);
      $this->db->delete($table);
      if ($this->db->affected_rows() > 0) {
          return true;
      } else {
          return false;
      }
    }
    
    function cek_nd($nd) {
        $nd_t = trim($nd,'');
//        $nd_l = strtoupper($nd_t);
        $nd_l = base64_decode($nd_t);
        $this->db->where('NOMOR_NOTA_DINAS', $nd_l);
        $this->db->where('IS_ACTIVE', 1);
        $this->db->from('t_eaudit_nodin');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function cek_nd_by_clause($clause) {
      $this->db->where($clause);
      $this->db->from('t_eaudit_nodin');
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
          return $query->row();
      } else {
          return false;
      }
  }

}
