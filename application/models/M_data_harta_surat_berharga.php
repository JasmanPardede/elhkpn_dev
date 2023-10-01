<?php

class M_data_harta_surat_berharga extends CI_Model {

    public $TABLE = 't_lhkpn_harta_surat_berharga';
    public $PK = null;

    function pk($TABLE) {
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        return $data[0]['Column_name'];
    }

    public function __construct() {
        parent::__construct();
        $this->PK = $this->pk($this->TABLE);
    }

    protected function set_cari_condition($cari = FALSE) {
        if ($cari) {

            $sql_like = " (m_jenis_harta.NAMA LIKE '%" . $cari . "%' OR "
                    . " ATAS_NAMA LIKE '%" . $cari . "%' OR "
                    . " NAMA_PENERBIT LIKE '%" . $cari . "%' OR "
                    . " CUSTODIAN LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
    }

    private function __set_join_grid_surat_berharga($TABLE, $PK, $ID_PN) {
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ', 'left');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '" . $ID_PN . "'");
    }

    private function __set_condition($ID_LHKPN) {
        $this->db->where($this->TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($this->TABLE . '.IS_ACTIVE', '1');
    }

    public function get_asal_usul_by_id($id) {
        $this->db->where('ID_HARTA', $id);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_surat_berharga.ID_ASAL_USUL');
        $q = $this->db->get('t_lhkpn_asal_usul_pelepasan_surat_berharga');
        if($q){
            return $q->result();
        }
        
        return FALSE;
    }
    
    public function count_num_rows($ID_LHKPN, $ID_PN, $iDisplayLength, $iDisplayStart, $cari = FALSE){
        $this->__set_condition($ID_LHKPN);
        $this->set_cari_condition($cari);
        $this->__set_join_grid_surat_berharga($this->TABLE, $this->PK, $ID_PN);
        
        return $this->db->count_all_results($this->TABLE);
    }

    public function get_data($ID_LHKPN, $ID_PN, $iDisplayLength, $iDisplayStart, $cari = FALSE) {
        $this->__set_condition($ID_LHKPN);
        $this->set_cari_condition($cari);

        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_surat_berharga.*,
        	t_lhkpn_harta_surat_berharga.STATUS AS STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_surat_berharga`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_surat_berharga`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
                `t_lhkpn`.`STATUS` `STATUS_LHKPN`,
        	t_lhkpn.*
        ');

        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_surat_berharga($this->TABLE, $this->PK, $ID_PN);
        $this->db->order_by($this->PK, 'DESC');
        $this->db->group_by($this->TABLE . '.' . $this->PK);
        $q = $this->db->get($this->TABLE);

        if ($q) {
            return $q->result();
        }

        return FALSE;
    }

}
