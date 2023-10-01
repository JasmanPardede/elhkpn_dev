<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Munitkerja
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Munitkerja extends CI_Model {

    private $table = 'M_UNIT_KERJA';

    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->order_by('UK_NAMA', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '') {
        //$this->db->order_by("m.UK_STATUS",'1');
        if (is_array($filter)) {
            $useLike['UK_NAMA'] = 'both';
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

    /**
     * 
     * @param type $cari ini digunakan untuk eksekusi keyword yang datang dari text box datatable
     * @param type $advance_cari ini gunakan untuk eksekusi keyword yang datang dari formulir pencarian advance
     */
    function set_where_daftar_unitkerja($cari = NULL, $advance_cari = NULL) {
        if (!is_null($cari) && trim($cari) != '') {
            $this->db->like("m.UK_NAMA", $cari);
        }

        if (!is_null($advance_cari) && is_array($advance_cari)) {
            $dictionary_advance_cari = array(
                "STATUS" => array(
                    "field_to_compare" => "m.UK_STATUS",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "INSTANSI" => array(
                    "field_to_compare" => "m.UK_LEMBAGA_ID",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "UNIT_KERJA" => array(
                    "field_to_compare" => "m.UK_ID",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "TEXT" => array(
                    "field_to_compare" => "m.UK_NAMA",
                    "compare_method" => "like",
                    "ci_slash_string" => TRUE
                ),
            );

            foreach ($dictionary_advance_cari as $input_name_cari => $field_name) {
                if (array_key_exists($input_name_cari, $advance_cari) && trim($advance_cari[$input_name_cari]) != '') {
                    if ($field_name["compare_method"] == "like") {
                        $this->db->or_like($field_name["field_to_compare"], $advance_cari[$input_name_cari]);
                    } else {
                        $this->db->where($field_name["field_to_compare"], $advance_cari[$input_name_cari], $field_name["ci_slash_string"]);
                    }
                }
            }
        }
    }

    function get_total_unitkerja($cari = NULL, $advance_cari = NULL) {

        $this->db->from('M_UNIT_KERJA m');
        $this->from_and_join_master_unitkerja();
//        $this->db->where("m.UK_STATUS",'1');
        $this->set_where_daftar_unitkerja($cari, $advance_cari);

        return $this->db->count_all_results();
    }

    function get_uk_id_by_name_and_lembaga_id($id_lembaga, $uk_nama = FALSE) {
        if ($uk_nama && !empty($uk_nama) && trim($uk_nama) != '') {
            $this->db->select(" UK_ID ", FALSE);
            $uk_nama_lowercase = strtolower(trim($uk_nama));
            $this->db->where(" UK_LEMBAGA_ID = '" . $id_lembaga . "' AND LOWER(TRIM(UK_NAMA)) = '" . $uk_nama_lowercase . "' ");
            $query = $this->db->get("M_UNIT_KERJA");
            $record_result = $query ? $query->row() : FALSE;
            if ($record_result) {
                return $record_result->UK_ID;
            }
        }
        return FALSE;
    }
    
    function get_suk_id_by_name_and_uk_id($uk_id, $nama_suk=FALSE){
        if ($nama_suk && !empty($nama_suk) && trim($nama_suk) != '') {
            $this->db->select(" SUK_ID ", FALSE);
            $suk_nama_lowercase = strtolower(trim($nama_suk));
            $this->db->where(" UK_ID = '" . $uk_id . "' AND LOWER(TRIM(SUK_NAMA)) = '" . $suk_nama_lowercase . "' ");
            $query = $this->db->get("m_sub_unit_kerja");
            $record_result = $query ? $query->row() : FALSE;
            if ($record_result) {
                return $record_result->SUK_ID;
            }
        }
        return FALSE;
    }

    /**
     * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
     */
    function get_daftar_master_unitkerja($offset = 0, $cari = NULL, $rowperpage = 10, $advance_cari = NULL) {
        $total_rows = $this->get_total_unitkerja($cari, $advance_cari);
//        var_dump($total_rows, $this->db->last_query());
//        exit;

        $result = array();
        if ($total_rows > 0) {
            $sql_select = "`m`.`UK_ID`, " .
                    "`M_INST_SATKER`.`INST_NAMA`, " .
                    "UPPER(`m`.`UK_NAMA`) as UK_NAMA, ";

            $this->db->select($sql_select, FALSE);
            $this->from_and_join_master_unitkerja();
//            $this->db->where("m.UK_STATUS",'1');
            $this->set_where_daftar_unitkerja($cari, $advance_cari);

            $query = $this->db->get('M_UNIT_KERJA m', $rowperpage, $offset);

            if ($query) {
                //               display($this->db->last_query());
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

    function from_and_join_master_unitkerja() {
        $this->db->join("M_INST_SATKER", "`m`.`UK_LEMBAGA_ID` = `M_INST_SATKER`.`INST_SATKERKD`", "left");
    }

    function get_paged_list($limit = 10, $offset = 0, $filter = '') {
        if (is_array($filter)) {
            $useLike['INST_NAMA'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('UK_ID', 'asc');
        $result = $this->db->get($this->table, $limit, $offset);
        return $result;
    }

    function get_nama_unitkerja($id) {
        $this->db->select('INST_NAMA')
                ->from($this->table)
                ->where('UK_ID', $id);
        $query = $this->db->get()->row();
        if (is_object($query))
            return $query->INST_NAMA;
        return NULL;
    }

    function get_by_id($id, $only_query = TRUE) {
        $this->db->where('UK_ID', $id);
        $q = $this->db->get($this->table);
        if ($only_query) {
            return $q;
        }
        
        if($q){
            return $q->row();
        }
        return FALSE;
    }

    function save($unitkerja) {
        $this->db->insert($this->table, $unitkerja);
        return $this->db->insert_id();
    }

    function update($id, $unitkerja) {
        $this->db->where('UK_ID', $id);
        $this->db->update($this->table, $unitkerja);
    }

    function delete($id) {
        $this->db->where('UK_ID', $id);
        $this->db->delete($this->table);
    }

}

?>