<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Minstansi
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Minstansi extends CI_Model {

    private $table = 'M_INST_SATKER';

    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->order_by('INST_NAMA', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '') {
//        $this->db->where("m.IS_ACTIVE",'1');
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
        return $this->db->get($this->table)->num_rows();
        // return $this->db->count_all($this->table);
    }

    
    /**
     * 
     * @param type $cari ini digunakan untuk eksekusi keyword yang datang dari text box datatable
     * @param type $advance_cari ini gunakan untuk eksekusi keyword yang datang dari formulir pencarian advance
     */
    function set_where_daftar_instansi($cari = NULL, $advance_cari = NULL) {
        if (!is_null($cari) && trim($cari) != '') {
            $this->db->where("(m.INST_NAMA LIKE '%$cari%' OR m.INST_AKRONIM LIKE '%$cari%')", NULL, FALSE);
        }

        if (!is_null($advance_cari) && is_array($advance_cari)) {
            $dictionary_advance_cari = array(
                "STATUS" => array(
                    "field_to_compare" => "m.IS_ACTIVE",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "TEXT" => array(
                    "field_to_compare" => "m.INST_NAMA",
                    "compare_method" => "like",
                    "ci_slash_string" => TRUE
                ),
            );

            foreach ($dictionary_advance_cari as $input_name_cari => $field_name) {
                if (array_key_exists($input_name_cari, $advance_cari) && trim($advance_cari[$input_name_cari]) != '') {

                    if ($input_name_cari == "STATUS") {
                            $this->db->where($field_name["field_to_compare"], $advance_cari[$input_name_cari], $field_name["ci_slash_string"]);
                    }
                }
            }
        }
    }

    function get_total_instansi($cari = NULL, $advance_cari = NULL) {

        $this->db->from('M_INST_SATKER m');
        //$this->from_and_join_master_kabupaten();
//        $this->db->where("m.IS_ACTIVE",'1');
        $this->set_where_daftar_instansi($cari, $advance_cari);        
        
        return $this->db->count_all_results();
    }

    
    /**
     * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
     */
    function get_daftar_master_instansi($offset = 0, $cari = NULL, $rowperpage = 10, $advance_cari = NULL) {
        $total_rows = $this->get_total_instansi($cari, $advance_cari);
    //    var_dump($total_rows, $this->db->last_query());
    //     exit;
        
        $result = array();
        if ($total_rows > 0) {
            $sql_select = "`m`.`INST_SATKERKD`, " .
                    "`m`.`INST_AKRONIM`, " .
                    "CASE WHEN (`m`.`INST_LEVEL` = 1 ) THEN 'PUSAT'"
                    . "     WHEN (`m`.`INST_LEVEL` = 2 ) THEN 'DAERAH TK.I' "
                    . "     WHEN (`m`.`INST_LEVEL` = 3 ) THEN 'DAERAH TK.II'"
                    . "END AS INST_LEVEL, " .                    
                    "UPPER(`m`.`INST_NAMA`) as INST_NAMA, " ;

            $this->db->select($sql_select, FALSE); 
//            $this->db->where("m.IS_ACTIVE",'1');
            $this->set_where_daftar_instansi($cari, $advance_cari);

            $query = $this->db->get('M_INST_SATKER m', $rowperpage, $offset);
			//display($this->db->last_query());exit;
            if ($query) {

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
//display($this->db->last_query());
//exit;
        return (object) array("total_rows" => $total_rows, "result" => $result);
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
        $this->db->order_by('INST_SATKERKD', 'asc');
        $result= $this->db->get($this->table, $limit, $offset);
        return $result;
    }

    function get_nama_instansi($id) {
        $this->db->select('INST_NAMA')
            ->from($this->table)
            ->where('INST_SATKERKD', $id);
			$this->db->order_by('INST_NAMA', 'asc');
        $query = $this->db->get()->row();
        if ( is_object($query) )
            return $query->INST_NAMA;
        return NULL;
    }

    function get_id_instansi_by_nama($nama = NULL) {
        if ($nama && !is_null($nama)) {
            
            $condition_nama = "LOWER(TRIM(INST_NAMA)) = LOWER(TRIM('".$nama."'))";
            
            $this->db->select('INST_SATKERKD')
                    ->from($this->table)
                    ->where($condition_nama);
            $query = $this->db->get();
            if ($query && !is_null($query->row())){
                return $query->row()->INST_SATKERKD;
            }
        }
        return FALSE;
    }

    function get_by_id($id) {
        $this->db->where('INST_SATKERKD', $id);
        return $this->db->get($this->table);
    }

    function save($instansi) {
        $this->db->insert($this->table, $instansi);
        return $this->db->insert_id();
    }

    function update($id, $instansi) {
        $this->db->where('INST_SATKERKD', $id);
        $this->db->update($this->table, $instansi);
    }

    function delete($id) {
        $this->db->where('INST_SATKERKD', $id);
        $this->db->delete($this->table);
    }
}

?>