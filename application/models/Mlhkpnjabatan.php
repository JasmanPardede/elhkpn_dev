<?php

class Mlhkpnjabatan extends CI_Model {

    private $table = 't_lhkpn_jabatan';

    function __construct() {
        parent::__construct();
    }

    protected function configure_join() {
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
        // $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_lhkpn_jabatan.LEMBAGA', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
        // $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_lhkpn_jabatan.UNIT_KERJA', 'left');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = m_jabatan.UK_ID', 'left');
        // $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_ID = t_lhkpn_jabatan.SUB_UNIT_KERJA', 'left');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_ID = m_jabatan.SUK_ID', 'left');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_jabatan.ID_LHKPN");
//        $this->db->group_by('t_lhkpn_jabatan.ID');
        $this->db->order_by('ID', 'DESC');
    }

    protected function configure_pencarian($cari = FALSE) {
        $str_cari = ' 1=1 ';
        if ($cari) {
            
            $str_cari = " (m_jabatan.NAMA_JABATAN LIKE '%".$cari."%' ".
                    " OR  m_inst_satker.INST_NAMA LIKE '%".$cari."%'  " .
                    " OR  m_unit_kerja.UK_NAMA LIKE '%".$cari."%'  " .
                    " OR  m_sub_unit_kerja.SUK_NAMA LIKE '%".$cari."%')  ";
        }
        return " AND ".$str_cari;
    }
    
    function count_result($id_lhkpn, $cari = FALSE){
        $str_cari = $this->configure_pencarian($cari);
        $this->db->where("t_lhkpn_jabatan.ID_LHKPN = '". $id_lhkpn."' ".$str_cari);
        

        $this->configure_join();
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_data_jabatan_by_id_lhkpn($id_lhkpn, $offset = 0, $limit = 10, $cari = FALSE, $ID_PN = FALSE) {

        $result = [];
        $total_rows = 0;

        $str_cari = $this->configure_pencarian($cari);
        $this->db->where("t_lhkpn_jabatan.ID_LHKPN = '". $id_lhkpn."' ".$str_cari);
        if ($ID_PN) {
            $this->db->where("t_lhkpn.ID_PN = ".$ID_PN);
        }
        else{
            $this->db->where("t_lhkpn.ID_PN = ".$this->session->userdata('ID_PN'));
        }
//        $this->db->where("t_lhkpn_jabatan.ID_JABATAN IS NOT NULL");
        

        $this->configure_join();
        $this->db->limit($limit, $offset);

        $query = $this->db->get($this->table);
        if ($query) {
            $result = $query->result();
            
            $total_rows = $this->count_result($id_lhkpn, $cari);
            if ($result) {
                $i = 1+$offset;
                foreach($result as $key => $record){
                    $result[$key]->NO_URUT_TABLE_COL = $i;
                    $result[$key]->IS_PRIMARY = (int) $result[$key]->IS_PRIMARY;
                    $i++;
                }
            }
        }
        
        return (object) array("total_rows" => $total_rows, "result" => $result);
    }

}
