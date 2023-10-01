<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 */
class Penugasan_model extends CI_Model
{

  var $table = 't_elhkpn';
  // var $order = array('tgl_kirim_final' => 'asc');

  public function get_user_roles()
  {
    $this->db->select('tu.ID_USER, tu.ID_ROLE')
     ->from('t_user tu')
     ->join('t_user_role tr', 'tr.ID_ROLE = tu.ID_ROLE')
     ->where("tu.ID_USER = '".$this->session->userdata()['ID_USER']."'");

     $q = $this->db->get();

     return $q->result();
  }

  public function get_nama_instansi()
  {
    $this->db->select('INST_NAMA');
    $this->db->distinct();
    $this->db->where('INST_NAMA IS NOT NULL');
    $this->db->where('INST_NAMA <>', '---');
    $q  = $this->db->get('m_inst_satker');

    return $q->result();
  }

  public function count_pelaporan($id_lhkpn)
  {
    $this->db->select('count(ID_LHKPN) as jumlah');
    $this->db->from('T_LHKPN_PELAPORAN');
    $this->db->where('ID_LHKPN', $id_lhkpn);
    $q  = $this->db->get();
    return $q->result();
  }

    function init_db_penugasan()
  {

    // select from selected status in t_lhkpn tables
    $this->db->where_in('T_LHKPN.STATUS',  array('1','2','3','4','5','6'));
    // add input from filter here
    ///if ($_POST['tahun']) {
    ///   $where_clause .= " AND YEAR(T_LHKPN.tgl_lapor) = '".$_POST['tahun']."'";
    /// }
    /// else {
    ///   $where_clause .= "AND YEAR(T_LHKPN.tgl_lapor) = '".date('Y')."'";
    /// }
    // else {
    //   $where_clause .= "AND YEAR(T_LHKPN.tgl_kirim_final) = '".date('Y')."'";
    // }
    ///if ($_POST['jenisLaporan']) {
    ///    $where_clause .= " AND T_LHKPN.JENIS_LAPORAN = '".$_POST['jenisLaporan']."'";
    ///} else{
    ///   $where_clause .= " AND T_LHKPN.JENIS_LAPORAN <> '5'";
    ///}
    // if ($this->input->post('statusPenugasan') == 0) {
    //   $status_periksa = '';
    //   $jenis_penugasan = '';
    //   $where_clause .= "AND 1=1";
    //   $and = '';
    // }else if ($this->input->post('statusPenugasan') == 1){
    //   //$status_periksa = '';
    //   //$jenis_penugasan = '';
    //   $and = "AND T_LHKPN.ID_LHKPN NOT IN (t_lhkpn_audit.ID_LHKPN)";
    // }else if ($this->input->post('statusPenugasan') == 2){
    //   $status_periksa = 1 ;
    //   $jenis_penugasan = 0;
    //   $where_clause .= " AND t_lhkpn_audit.STATUS_PERIKSA = '".$status_periksa."' AND t_lhkpn_audit.JENIS_PENUGASAN = '".$jenis_penugasan."'";
    //   $and = '';
    // }else if ($this->input->post('statusPenugasan') == 3){
    //   $status_periksa = 1;
    //   $jenis_penugasan = 1;
    //   $where_clause .= " AND t_lhkpn_audit.STATUS_PERIKSA >= '".$status_periksa."' AND t_lhkpn_audit.JENIS_PENUGASAN = '".$jenis_penugasan."'";
    //   $and ='';
    // }
    //if ($this->input->post('jabatan')) {
    //    $where_clause .= " AND NAMA_JABATAN like '%".$this->input->post('jabatan')."%'";
    //}


    $this->db->select('
    T_LHKPN.ID_LHKPN,
    T_LHKPN.tgl_lapor,
    T_LHKPN.tgl_kirim,
    T_LHKPN.tgl_kirim_final,
    T_LHKPN.JENIS_LAPORAN,
    T_LHKPN.STATUS as status,
    T_PN.NAMA,
    T_PN.NIK,
    T_LHKPN_JABATAN.DESKRIPSI_JABATAN as DESKRIPSI_JABATAN1,
    m_jabatan.NAMA_JABATAN as DESKRIPSI_JABATAN,
    M_INST_SATKER.INST_NAMA,
    M_UNIT_KERJA.UK_NAMA,
    t_lhkpn_audit.status_periksa,
    t_lhkpn_audit.jenis_penugasan,
    t_lhkpn_audit.id_audit,
    MAX(t_lhkpn_audit.id_audit) as maksimal,
  	t_pn.NHK,
  	t_lhkpn_audit.is_active,
    t_lhkpn_data_pribadi.TANGGAL_LAHIR,
    
    GROUP_CONCAT(T_USER.NAMA) AS nama_pemeriksa');
    $this->db->from('T_LHKPN');
    $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN AND T_LHKPN_JABATAN.IS_PRIMARY = "1"', 'left');
    // $this->db->join('m_jabatan','t_lhkpn_jabatan.id_jabatan = m_jabatan.id_jabatan')
    $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN','left');
    $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD', 'left');
    $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID', 'left');
    $this->db->join('t_lhkpn_audit', 't_lhkpn_audit.id_lhkpn = t_lhkpn.id_lhkpn', 'left');
    
    $this->db->join('T_USER', 'T_USER.ID_USER = t_lhkpn_audit.id_pemeriksa', 'left');
    $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_data_pribadi.ID_LHKPN = t_lhkpn.ID_LHKPN', 'left');
    //$this->db->join('v_t_lhkpn_audit_pemeriksa' ,'v_t_lhkpn_audit_pemeriksa.ID_LHKPN = T_LHKPN.ID_LHKPN '.$and.'','left');
    $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN','left');
    $this->db->where('T_LHKPN.IS_ACTIVE', '1');
    $this->db->where('T_LHKPN.JENIS_LAPORAN !=', '5');


    // $this->db->where($where_clause);
    //--- end filter processing
    if (!isset($_POST['order'])) {
      $this->db->order_by('T_PN.NAMA', 'ASC');
      $this->db->order_by('T_LHKPN.ID_LHKPN', 'DESC');
      // $this->db->order_by('t_lhkpn_audit.id_audit', 'DESC');
      
    }
    $this->db->group_by('T_LHKPN.ID_LHKPN');

    // [TODO] for development mode only, comment this line when producion
    // $this->db->limit(100);
    // $my_query = $this->db->get();
    // return $my_query->result();

  }

  private function _get_datatables_query()
  {
    // define which table that will be used
    $this->init_db_penugasan();
    // $this->init_query_penugasan();


    // filter event listener
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
      // $this->db->order_by('t_lhkpn_audit.id_audit', 'DESC');
    }

    // check if input field NHK is not empty
    $nhk = $this->input->post('nhk');
    if ($nhk !== '' ) {
      $this->db->like('t_pn.NHK', $nhk);
    }

    // check if input field tgl_lahir is not empty
    $tgl_lahir = $this->input->post('tgl_lahir');
    if ($tgl_lahir !== '' ) {
      $this->db->like('t_lhkpn_data_pribadi.TANGGAL_LAHIR', $tgl_lahir);
    }

    // check if input field nama_PN is not empty
    $nama_pn = $this->input->post('namaPn');
    if ($nama_pn !== '' ) {
      // $this->db->like('t_pn.NAMA ', $nama_pn);
      // $this->db->or_like('t_pn.NIK', $nama_pn);
      $this->db->where('(t_pn.NAMA LIKE "%'.$nama_pn.'%" ESCAPE "!" OR t_pn.NIK LIKE "'.$nama_pn.'" ESCAPE "!" )');
    }

    $nama_instansi = $this->input->post('nama_instansi');
    if ($nama_instansi !== '') {
      $this->db->like('M_INST_SATKER.INST_NAMA', $nama_instansi);
    }

    $status_penugasan = $this->input->post('statusPenugasan');
    if ($status_penugasan > 0) {

        if ($status_penugasan == 1) {
          // $this->db->where('t_lhkpn_audit.status_periksa', NULL);
          $this->db->where('(t_lhkpn_audit.status_periksa IS NULL OR t_lhkpn_audit.is_active <> 1)');
        }
        elseif($status_penugasan == 2){
          $this->db->where('t_lhkpn_audit.status_periksa >', 0);
          $this->db->where('t_lhkpn_audit.jenis_penugasan', 0);
          $this->db->where('t_lhkpn_audit.is_active', 1);
        }
        elseif ($status_penugasan == 3) {
          $this->db->where('t_lhkpn_audit.status_periksa >', 0);
          $this->db->where('t_lhkpn_audit.jenis_penugasan', 1);
          $this->db->where('t_lhkpn_audit.is_active', 1);
        }
    }
  }

  // call the data from datatables query
  public function get_datatables()
  {

    if ($this->input->post('namaPn') !== '' || $this->input->post('statusPenugasan') > 0 || $this->input->post('tgl_lahir') !== '' || $this->input->post('nhk') !== '' || $this->input->post('nama_instansi') !== '') {
      $this->_get_datatables_query();

      if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);

      $query = $this->db->get();
      
      // dump($this->db->last_query());exit;

      return $query->result();
    }else{
      return FALSE;
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
      // $this->db->from($this->table);
      // $this->db->query($this->sql_user_publication);
      $this->_get_datatables_query();
      return $this->db->count_all_results();
  }

  // untuk sementara buat ambil semua user dengan role_id != 3,4,5
  public function get_all_pemeriksa()
  {
    $this->db->select('t_user.ID_USER, t_user.NAMA, t_user_role.ROLE');
    $this->db->from('t_user');
    // $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_user.UK_ID', 'left');
    $this->db->join('t_user_role', 't_user_role.ID_ROLE = t_user.ID_ROLE');
    $this->db->where("
    INSTR(t_user.ID_ROLE,'27') > 0
    OR INSTR(t_user.ID_ROLE,'24') > 0
    OR INSTR(t_user.ID_ROLE,'25') > 0
    ");
    $this->db->order_by('t_user.NAMA', 'ASC');
    $query = $this->db->get();
    return $query->result();
  }
  
  public function inputDataPenugasan($data)
  {
    $this->db->insert('t_lhkpn_audit', $data);
    // if ($this->db->affected_rows() > 0) {
    //   return true;
    // }
    // else{
    //   return false;
    // }
  }

  public function notifEmailPenugasan() {
    $sql = "SELECT MAX(id_audit) id_audit, id_lhkpn, tgl_mulai_periksa, tgl_selesai_periksa, id_pic, nama, email, CASE WHEN DATEDIFF(tgl_selesai_periksa, CURRENT_DATE()) = 7 THEN '1' ELSE '0' END AS email_notif FROM t_lhkpn_audit
      JOIN t_user ON t_lhkpn_audit.`id_pic` = t_user.`ID_USER`
      WHERE id_pic IS NOT NULL 
      AND CASE WHEN DATEDIFF(tgl_selesai_periksa, CURRENT_DATE()) = 7 THEN '1' ELSE '0' END = '1'
      GROUP BY id_lhkpn, tgl_mulai_periksa, tgl_selesai_periksa, id_pic";
    
    return $this->db->query($sql)->result();
  }


}


?>
