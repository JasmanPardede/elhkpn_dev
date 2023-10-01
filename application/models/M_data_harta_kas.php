<?php

class M_data_harta_kas extends CI_Model {

    public $TABLE = 't_lhkpn_harta_kas';
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

    protected function set_cari_condition($cari = FALSE, $encry = NULL) {
        if ($cari) {

            $sql_like = " (m_jenis_harta.NAMA LIKE '%" . $cari . "%' OR "
                    . " NAMA_BANK = '" . $encry . "' OR "
                    . " NAMA_BANK LIKE '%" . $cari . "%' OR "
                    . " NOMOR_REKENING = '" . $encry . "' OR "
                    . " NOMOR_REKENING LIKE '%" . $cari . "%' OR "
                    . " ATAS_NAMA_REKENING LIKE '%" . $cari . "%' OR "
                    . " KETERANGAN LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
    }

    private function __set_condition($ID_LHKPN) {
        $this->db->where($this->TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($this->TABLE . '.IS_ACTIVE', '1');
    }

    private function __set_join_grid_kas($TABLE, $PK, $ID_PN) {
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG', 'left');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '" . $ID_PN . "'");
    }

    public function get_asal_usul_by_id($id) {
        $this->db->where('ID_HARTA', $id);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_kas.ID_ASAL_USUL');
        $q = $this->db->get('t_lhkpn_asal_usul_pelepasan_kas');
        if ($q) {
            return $q->result();
        }

        return FALSE;
    }
    
    public function count_num_rows($ID_LHKPN, $ID_PN, $iDisplayLength, $iDisplayStart, $cari = FALSE){
        $encry = encrypt_username($cari, 'e');
        $this->__set_condition($ID_LHKPN);
        $this->set_cari_condition($cari, $encry);
        $this->__set_join_grid_kas($this->TABLE, $this->PK, $ID_PN);
        
        return $this->db->count_all_results($this->TABLE);
    }

    public function get_data($ID_LHKPN, $ID_PN, $iDisplayLength, $iDisplayStart, $cari = FALSE) {
        $encry = encrypt_username($cari, 'e');
        $this->__set_condition($ID_LHKPN);
        $this->set_cari_condition($cari, $encry);

        $this->db->select('
        	m_jenis_harta.*,
        	m_mata_uang.*,
        	t_lhkpn_harta_kas.*,
        	t_lhkpn_harta_kas.STATUS AS STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_kas`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_kas`.`NILAI_SALDO`
                END `NILAI_SALDO`,
                `t_lhkpn`.`STATUS` `STATUS_LHKPN`,
        	t_lhkpn.*
        ');

        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_kas($this->TABLE, $this->PK, $ID_PN);
        $this->db->order_by($this->PK, 'DESC');
        $this->db->group_by($this->TABLE . '.' . $this->PK);
        $q = $this->db->get($this->TABLE);
//        echo $this->db->last_query();exit;
        if ($q) {
            return $q->result();
        }

        return FALSE;
    }

}
