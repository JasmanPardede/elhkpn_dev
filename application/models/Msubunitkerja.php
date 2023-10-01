<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Msubunitkerja
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Msubunitkerja extends CI_Model {

    private $table = 'M_SUB_UNIT_KERJA';

    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->order_by('SUK_NAMA', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '') {
//        $this->db->where("m.UK_STATUS",'1');
        if (is_array($filter)) {
            $useLike['SUK_NAMA'] = 'both';
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
    function set_where_daftar_subunitkerja($cari = NULL, $advance_cari = NULL) {
        if (!is_null($cari) && trim($cari) != '') {
            $this->db->like("m.SUK_NAMA", $cari);
        }

        if (!is_null($advance_cari) && is_array($advance_cari)) {
            $dictionary_advance_cari = array(
                "STATUS" => array(
                    "field_to_compare" => "m.UK_STATUS",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "TEXT" => array(
                    "field_to_compare" => "m.SUK_NAMA",
                    "compare_method" => "like",
                    "ci_slash_string" => TRUE
                ),
				
				"INSTANSI" => array(
                    "field_to_compare" => "m_inst_satker.inst_satkerkd",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "UNIT_KERJA" => array(
                    "field_to_compare" => "m.UK_ID",
                    "compare_method" => "=",
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

    function get_total_subunitkerja($cari = NULL, $advance_cari = NULL) {

        $this->db->from('M_SUB_UNIT_KERJA m');
        $this->db->join("m_unit_kerja", "`m`.`UK_ID` = `m_unit_kerja`.`UK_ID`", "left");
        $this->db->join("m_inst_satker", "`m_unit_kerja`.`uk_lembaga_id` = `m_inst_satker`.`inst_satkerkd`", "left");
        //$this->from_and_join_master_unitkerja();
//        $this->db->where("m.UK_STATUS",'1');
        $this->set_where_daftar_subunitkerja($cari, $advance_cari);        
        
        return $this->db->count_all_results();
    }

    
    /**
     * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
     */
    function get_daftar_master_subunitkerja($offset = 0, $cari = NULL, $rowperpage = 10, $advance_cari = NULL) {
        $total_rows = $this->get_total_subunitkerja($cari, $advance_cari);

        
        $result = array();
        if ($total_rows > 0) {
            $sql_select = 
                    "`m`.`SUK_ID`, " .
                    "`m`.`UK_ID`, " .
                    "`m_unit_kerja`.`UK_NAMA`, " .
                    "`m_inst_satker`.`INST_NAMA`, " .
                    "UPPER(`m`.`SUK_NAMA`) as SUK_NAMA, " ;

            $this->db->select($sql_select, FALSE); 
            $this->from_and_join_master_subunitkerja();
//            $this->db->where("m.UK_STATUS",'1');
            $this->set_where_daftar_subunitkerja($cari, $advance_cari);
			$this->db->order_by('SUK_NAMA', 'asc');
            $query = $this->db->get('M_SUB_UNIT_KERJA m', $rowperpage, $offset);
//                    var_dump($total_rows, $this->db->last_query()); exit;
            if ($query) {
//                display($this->db->last_query());exit;
                $result = $query->result();
                if($result){
                    $i = 1 + $offset;
                    foreach($result as $key => $record){
                        $record->NO_URUT = $i;
                        $result[$key] = $record;
                        $i++;
                    }
                }else{
                    $result = array();
                }
            }
        }
        return (object) array("total_rows" => $total_rows, "result" => $result);
    }
    
    function from_and_join_master_subunitkerja() {
        $this->db->join("m_unit_kerja", "`m`.`UK_ID` = `m_unit_kerja`.`UK_ID`", "left");
        $this->db->join("m_inst_satker", "`m_unit_kerja`.`uk_lembaga_id` = `m_inst_satker`.`inst_satkerkd`", "left");
    }

    function get_paged_list($limit = 10, $offset = 0, $filter = '') {
        if (is_array($filter)) {
            $useLike['suk_nama'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('SUK_NAMA', 'asc');
        $result= $this->db->get($this->table, $limit, $offset);
        return $result;
    }

    function get_nama_subunitkerja($id) {
        $this->db->select('SUK_NAMA')
            ->from($this->table)
            ->where('SUK_ID', $id);
		$this->db->order_by('SUK_NAMA', 'asc');
        $query = $this->db->get()->row();
        if ( is_object($query) )
            return $query->SUK_NAMA;
        return NULL;
    }

     
    function get_by_id($id)
    {
        $this->db->where('SUK_ID', $id);
		$this->db->order_by('SUK_NAMA', 'asc');
        return $this->db->get($this->table);
    }
    
    function get_suk_id_by_name_and_uk_id($uk_id, $suk_nama = FALSE) {
        if ($suk_nama && !empty($suk_nama) && trim($suk_nama) != '') {
            $this->db->select(" SUK_ID ", FALSE);
            $this->db->where(" UK_ID = '" . $uk_id . "' AND LOWER(TRIM(SUK_NAMA)) = LOWER(TRIM('" . $suk_nama . "')) ");
            $query = $this->db->get("m_sub_unit_kerja");
            $record_result = $query ? $query->row() : FALSE;
            if ($record_result) {
                return $record_result->SUK_ID;
            }
        }
        return FALSE;
    }

    function save($subunitkerja)
    {
        $this->db->insert($this->table, $subunitkerja);
        return $this->db->insert_id();
    }

    function update($id, $subunitkerja)
    {
        $this->db->where('SUK_ID', $id);
        $this->db->update($this->table, $subunitkerja);
    }

    function delete($id)
    {
        $this->db->where('SUK_ID', $id);
        $this->db->delete($this->table);
    }
}

?>