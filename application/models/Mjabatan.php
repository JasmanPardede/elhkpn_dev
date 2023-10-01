<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Mjabatan
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mjabatan extends CI_Model {

    private $table = 'M_JABATAN';

    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->order_by('NAMA_JABATAN', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '') {
        //$this->db->where("m.IS_ACTIVE",'1');
        if (is_array($filter)) {
            $useLike['NAMA_JABATAN'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        return $this->db->get($this->table)->num_rows();
        // return $this->db->count_all($this->table);
    }

    function get_total_row_individu($page_mode = null, $cari) {
        $ins = $this->session->userdata('INST_SATKERKD');
        $total_rows = $this->get_daftar_pn_individual($ins, $cari);
        switch ($page_mode) {
            default:
                $total_rows = $this->get_daftar_pn_master_jabatan($ins, $cari);
                break;
        }
        return $total_rows;
    }

    /**
     * 
     * @param type $cari ini digunakan untuk eksekusi keyword yang datang dari text box datatable
     * @param type $advance_cari ini gunakan untuk eksekusi keyword yang datang dari formulir pencarian advance
     */
    function set_where_daftar_jabatan($cari = NULL, $advance_cari = NULL) {
        if (!is_null($cari) && trim($cari) != '') {
            $this->db->like("m.NAMA_JABATAN", $cari);
//            $this->db->or_like("m.ESELON", $cari);
//            $this->db->or_like("m_unit_kerja.uk_nama", $cari);
//            $this->db->or_like("m_sub_unit_kerja.suk_nama", $cari);
//            $this->db->or_like("m_inst_satker.inst_nama", $cari);
        }

        if (!is_null($advance_cari) && is_array($advance_cari)) {
            $dictionary_advance_cari = array(
                "STATUS" => array(
                    "field_to_compare" => "m.IS_ACTIVE",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "TEXT" => array(
                    "field_to_compare" => "m.NAMA_JABATAN",
                    "compare_method" => "like",
                    "ci_slash_string" => TRUE
                ),
                "INSTANSI" => array(
                    "field_to_compare" => "M_INST_SATKER.INST_SATKERKD",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "UNITKERJA" => array(
                    "field_to_compare" => "M_UNIT_KERJA.UK_ID",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "SUBUKER" => array(
                    "field_to_compare" => "M_SUB_UNIT_KERJA.SUK_ID",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "IS_ACTIVE" => array(
                    "field_to_compare" => "IS_ACTIVE",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "BIDANG" => array(
                    "field_to_compare" => "SUBSTR(ID_JABATAN, 1, 1)",
                    "compare_method" => "=",
                    "ci_slash_string" => FALSE
                ),
                "ESELON" => array(
                    "field_to_compare" => "SUBSTR(ID_JABATAN, 1, 1)",
                    "compare_method" => "=",
                    "ci_slash_string" => FALSE
                ),
                "UU" => array(
                    "field_to_compare" => "IS_UU",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
            );

            foreach ($dictionary_advance_cari as $input_name_cari => $field_name) {
                if (array_key_exists($input_name_cari, $advance_cari) && trim($advance_cari[$input_name_cari]) != '') {
                    if ($field_name["compare_method"] == "like") {
                        $this->db->like($field_name["field_to_compare"], $advance_cari[$input_name_cari]);
                    } else {
                        $this->db->where($field_name["field_to_compare"], $advance_cari[$input_name_cari], $field_name["ci_slash_string"]);
                    }
                }
            }
        }
    }

    function get_total_jabatan($instansi, $cari = NULL, $advance_cari = NULL) {
        $this->db->select('*');
        $this->db->from('M_JABATAN m');
        $this->from_and_join_master_jabatan($instansi);
        //$this->db->where("m.IS_ACTIVE",'1');   
        //$this->db->where(set_where_daftar_jabatan($cari, $advance_cari));                
        $this->set_where_daftar_jabatan($cari, $advance_cari);
//        $this->db->where("m.IS_ACTIVE",'1');
        //echo "last query: ".$this->db->last_query()."==";        
        return $this->db->count_all_results();
    }

    function from_and_join_master_jabatan($instansi) {

        if (!is_null($instansi)) {
            $this->db->where("m_inst_satker.INST_SATKERKD = '" . $instansi . "'");
        }
        $this->db->join("m_inst_satker", "`m_inst_satker`.`INST_SATKERKD` = `m`.`INST_SATKERKD`", "left");
        $this->db->join("M_UNIT_KERJA", "`m`.`UK_ID` = `m_unit_kerja`.`UK_ID`", "left");
        $this->db->join("M_SUB_UNIT_KERJA", "`m`.`SUK_ID` = `M_SUB_UNIT_KERJA`.`SUK_ID`", "left");
        $this->db->join("M_ESELON", "`m`.`KODE_ESELON` = `M_ESELON`.`ID_ESELON`", "left");

//        $this->db->join("M_UNIT_KERJA", "`m`.`UK_ID` = `m_unit_kerja`.`UK_ID`", "left");
//        $this->db->join("M_SUB_UNIT_KERJA", "`M_UNIT_KERJA`.`UK_ID` = `M_SUB_UNIT_KERJA`.`UK_ID`", "left");
//        $this->db->join("m_inst_satker", "`m_inst_satker`.`INST_SATKERKD` = `m_unit_kerja`.`UK_LEMBAGA_ID`", "left");
    }

    /*
     * FROM `M_JABATAN` `m` 
      LEFT JOIN `m_inst_satker` ON `m_inst_satker`.`INST_SATKERKD` = `m`.`INST_SATKERKD`
      LEFT JOIN `M_UNIT_KERJA` ON `m`.`UK_ID` = `m_unit_kerja`.`UK_ID`
      LEFT JOIN `M_SUB_UNIT_KERJA` ON `m`.`SUK_ID` = `M_SUB_UNIT_KERJA`.`SUK_ID`
      AND `M_UNIT_KERJA`.`UK_ID` = `M_SUB_UNIT_KERJA`.`UK_ID`
     */

    /**
     * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
     */
    function get_daftar_master_jabatan($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $advance_cari = NULL) {

        $total_rows = $this->get_total_jabatan($instansi, $cari, $advance_cari);
//        var_dump($total_rows, $this->db->last_query());
//        exit;

        $result = array();
        if ($total_rows > 0) {
            $sql_select = "`m_unit_kerja`.`UK_NAMA`, " .
                    "`m_unit_kerja`.`UK_LEMBAGA_ID`, " .
                    "`m`.`SUK_ID`, " .
                    "`m`.`ID_JABATAN`, " .
                    "`m`.`NAMA_JABATAN`, " .
                    "`m`.`KODE_ESELON`, " .
                    "`m_eselon`.`ESELON`, " .
                    "`m`.`IS_UU`, " .
                    "`m_unit_kerja`.`UK_ID`, " .
                    "`m`.`UK_ID`, " .
                    "`m_sub_unit_kerja`.`SUK_ID`, " .
                    "`m_sub_unit_kerja`.`SUK_NAMA`, " .
                    "`m_inst_satker`.`INST_SATKERKD`, " .
                    "`m_inst_satker`.`INST_NAMA` ";

            $this->db->select($sql_select, FALSE);
            $this->from_and_join_master_jabatan($instansi);
            //$this->db->where("m.IS_ACTIVE",'1');
            $this->set_where_daftar_jabatan($cari, $advance_cari);
//            $this->db->where("m.IS_ACTIVE",'1');
            $this->db->order_by('`m_sub_unit_kerja`.`SUK_NAMA`','DESC');
            $query = $this->db->get('M_JABATAN m', $rowperpage, $offset);

            if ($query) {
//                               display($this->db->last_query());
 //                    exit;
                $result = $query->result();
                if ($result) {
                    $i = 1 + $offset;
                    foreach ($result as $key => $record) {
                        $record->NO_URUT = $i;
                        $result[$key] = $record;
                        $i++;
                    }
                } else {
                    $result = array();
                }
            }
        }
        
        return (object) array("total_rows" => $total_rows, "result" => $result);
    }

    function get_daftar_master_jabatan_cetak($cari = NULL) {
        $cari_advance['STATUS'] = $cari->status;
        $cari_advance['INSTANSI'] = $cari->instansi;
        $cari_advance['UNITKERJA'] = $cari->uk;
        $cari_advance['SUBUKER'] = $cari->subuker;
        $cari_advance['TEXT'] = $cari->text;
        $total_rows = $this->get_total_jabatan($cari->instansi, $cari->text, $cari_advance);
        
        $result = array();
        if ($total_rows > 0) {
            $sql_select = "`m_unit_kerja`.`UK_NAMA`, " .
                    "`m_unit_kerja`.`UK_LEMBAGA_ID`, " .
                    "`m`.`SUK_ID`, " .
                    "`m`.`ID_JABATAN`, " .
                    "`m`.`NAMA_JABATAN`, " .
                    "`m`.`KODE_ESELON`, " .
                    "`m_eselon`.`ESELON`, " .
                    "`m`.`IS_UU`, " .
                    "`m_unit_kerja`.`UK_ID`, " .
                    "`m`.`UK_ID`, " .
                    "`m_sub_unit_kerja`.`SUK_ID`, " .
                    "`m_sub_unit_kerja`.`SUK_NAMA`, " .
                    "`m_inst_satker`.`INST_SATKERKD`, " .
                    "`m_inst_satker`.`INST_LEVEL`, " .
                    "`M_BIDANG`.`BDG_NAMA`, " .
                    "`m_inst_satker`.`INST_NAMA` ";

            $this->db->select($sql_select, FALSE);
            
            $this->from_and_join_master_jabatan($cari->instansi);
            $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID =  m_inst_satker.INST_BDG_ID', 'left');

            $this->set_where_daftar_jabatan($cari->text, $cari_advance);
            
            $this->db->order_by('`m_sub_unit_kerja`.`SUK_NAMA`','DESC');
            $query = $this->db->get('M_JABATAN m');
            
            
            if ($query) {
                $result = $query->result();
                if ($result) {
                    $i = 1 + $offset;
                    foreach ($result as $key => $record) {
                        $record->NO_URUT = $i;
                        $result[$key] = $record;
                        $i++;
                    }
                } else {
                    $result = array();
                }
            }
        }
        
        return (object) array("result" => $result);
    }

    /**
     * @author Ferry Ricardo Siagian
     * @deprecated 5-January-2017 by lahirwisada
     * @see this -> get_daftar_master_jabatan above this method
     * @param type $instansi
     * @param type $offset
     * @param type $cari
     * @param type $rowperpage
     * @param type $page_mode
     * @return type
     */
    function ___get_daftar_master_jabatan($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {
        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');
//        $total_rows = $this->db->count_all('t_pn');
        // $total_rows = $this->get_daftar_pn_individual($ins, $cari);
        $total_rows = $this->get_total_row_individu($page_mode, $cari);
        $result = FALSE;
        if ($total_rows > 0) {
            $result = FALSE;
            $sql_select = "m_uk.UK_NAMA, m_uk.UK_LEMBAGA_ID, m.SUK_ID, m.ID_JABATAN,m.NAMA_JABATAN,"
                    . "m.KODE_ESELON,m.IS_UU,m_uk.UK_ID, m.UK_ID, m_suk.SUK_ID,"
                    . "m_suk.SUK_NAMA,m_isk.INST_SATKERKD, m_isk.INST_NAMA";

            $this->db->select($sql_select, FALSE);
            //$this->db->join("T_USER U", "U.USERNAME = P.NIK");
            $this->db->from('M_JABATAN m');
            $this->db->join('M_UNIT_KERJA m_uk', 'm.UK_ID = m_uk.UK_ID ', 'left');
            $this->db->join('M_SUB_UNIT_KERJA m_suk', 'm_uk.UK_ID = m_suk.UK_ID ', 'left');
            $this->db->join('m_inst_satker m_isk', 'm_isk.INST_SATKERKD = m_uk.UK_LEMBAGA_ID ', 'left');
            $this->db->where('1=1', null, false);

            /* $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
              //                    . "AND `PJ`.`ID_STATUS_AKHIR_JABAT` = 10 "
              . "AND `PJ`.`IS_DELETED` = '0' "
              . "AND `PJ`.`IS_CALON` = '0' "
              . "AND `PJ`.`IS_ACTIVE` = '1' "
              . "AND `PJ`.`IS_CURRENT` = '1' "; */

            //$this->db->join("T_PN_JABATAN PJ", $sql_where_join);
            //$this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN");
            //$this->db->join("`m_sub_unit_kerja` `SUK`", "`SUK`.`SUK_ID` = `JAB`.`SUK_ID`", "left");
            //$this->db->join("`m_unit_kerja` `UK`", "`UK`.`UK_ID` = `JAB`.`UK_ID`", "left");
            //$this->db->join("`m_inst_satker` `INTS`", "`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`", "left");
//            $sql_where = "m.IS_ACTIVE = '1'";



            if (!is_null($cari) && $cari != '') {
                $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";
            }

            if ($id_role != 1 && $id_role != 2) {
                $sql_where .= " AND pj.LEMBAGA = $ins";
            }

            if ($is_uk == 1) {
                $sql_where .= " AND JAB.UK_ID = $id_uk";
            }


            $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);

            if ($query) {
//                               display($this->db->last_query());
//                    exit;
                $result = $query->result();

                if ($result) {
                    $i = 1 + $offset;
                    foreach ($result as $key => $record) {
                        $record->NO_URUT = $i;
                        $result[$key] = $record;
                        $i++;
                    }
                } else {
                    $result = array();
                }
            }
//            }
        }

        return (object) array("total_rows" => $total_rows, "result" => $result);
    }

    function get_paged_list($limit = 10, $offset = 0, $filter = '') {
        if (is_array($filter)) {
            $useLike['NAMA_JABATAN'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('ID_JABATAN', 'asc');
        $result = $this->db->get($this->table, $limit, $offset);
        return $result;
    }

    function get_nama_jabatan($id) {
        $this->db->select('NAMA_JABATAN')
                ->from($this->table)
                ->where('ID_JABATAN', $id);
        $query = $this->db->get()->row();
        if (is_object($query))
            return $query->NAMA_JABATAN;
        return NULL;
    }

    function get_id_jabatan($nama_jabatan = FALSE) {
        if ($nama_jabatan) {
            $this->db->select('ID_JABATAN')
                    ->from($this->table)
                    ->where('NAMA_JABATAN', $nama_jabatan);
            
            $query = $this->db->get()->row();
            
            if (is_object($query)) {
                return $query->ID_JABATAN;
            }
        }
        return FALSE;
    }
    
    function get_id_jabatan_by_nama_uk_id_dan_suk_id($uk_id = FALSE, $suk_id = FALSE, $nama_jabatan = FALSE) {
        if ($uk_id && $suk_id && $nama_jabatan) {
            
            $condition = " UK_ID = '".$uk_id."' AND SUK_ID = '".$suk_id."' AND LOWER(TRIM(NAMA_JABATAN)) = LOWER(TRIM('".$nama_jabatan."')) ";
            
            $this->db->select('ID_JABATAN')
                    ->from($this->table)
                    ->where($condition);
            
            $query = $this->db->get();
            
            if ($query->num_rows() > 0) {
                return $query->row()->ID_JABATAN;
            }
        }
        return FALSE;
    }

    function get_pn_jabatan($id) {
        $this->db->select('NAMA_LEMBAGA', $id)
                ->from('t_pn_jabatan')
                ->where('ID_PN', $id);
        $result = $this->db > get()->row;
        return $result;
    }

    function get_by_id($id) {
        $this->db->where('ID_JABATAN', $id);
        return $this->db->get($this->table);
    }

    function save($jabatan) {
        $this->db->insert($this->table, $jabatan);
        return $this->db->insert_id();
    }

    function update($id, $jabatan) {
        $this->db->where('ID_JABATAN', $id);
        $this->db->update($this->table, $jabatan);
    }

    function delete($id) {
        $this->db->where('ID_JABATAN', $id);
        $this->db->delete($this->table);
    }

}

?>