<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
 (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Mpendidikan
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mpendidikan extends CI_Model {

    private $table = 'M_PENDIDIKAN';

    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->order_by('ID_PENDIDIKAN', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '') {
        if (is_array($filter)) {
            $useLike['PENDIDIKAN'] = 'both';
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
    function set_where_daftar_pendidikan($cari = NULL, $advance_cari = NULL) {
        if (!is_null($cari) && trim($cari) != '') {
            $this->db->like("m.PENDIDIKAN", $cari);
        }

        if (!is_null($advance_cari) && is_array($advance_cari)) {
            $dictionary_advance_cari = array(
                "STATUS" => array(
                    "field_to_compare" => "m.IS_ACTIVE",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "TEXT" => array(
                    "field_to_compare" => "m.PENDIDIKAN",
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

    function get_total_pendidikan($cari = NULL, $advance_cari = NULL) {

        $this->db->from('M_PENDIDIKAN m');
//        $this->db->where("IS_ACTIVE",'1');
        $this->set_where_daftar_pendidikan($cari, $advance_cari);        
        
        return $this->db->count_all_results();
    }

    
    /**
     * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
     */
    function get_daftar_master_pendidikan($offset = 0, $cari = NULL, $rowperpage = 10, $advance_cari = NULL) {
        $total_rows = $this->get_total_pendidikan($cari, $advance_cari);
//        var_dump($total_rows, $this->db->last_query());
//        exit;
        
        $result = array();
        if ($total_rows > 0) {
            $sql_select = 
                    "`m`.`ID_PENDIDIKAN`, " .
                    "UPPER(`m`.`PENDIDIKAN`) as PENDIDIKAN, " ;

            $this->db->select($sql_select, FALSE);   
//            $this->db->where("IS_ACTIVE",'1');
            $this->set_where_daftar_pendidikan($cari, $advance_cari);

            $query = $this->db->get('M_PENDIDIKAN m', $rowperpage, $offset);

            if ($query) {
                //               display($this->db->last_query());
//                    exit;
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

    

    function get_paged_list($limit = 10, $offset = 0, $filter = '') {
        if (is_array($filter)) {
            $useLike['PENDIDIKAN'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('ID_PENDIDIKAN', 'asc');
        $result= $this->db->get($this->table, $limit, $offset);
        return $result;
    }

    function get_nama_pendidikan($id) {
        $this->db->select('PENDIDIKAN')
            ->from($this->table)
            ->where('ID_PENDIDIKAN', $id);
        $query = $this->db->get()->row();
        if ( is_object($query) )
            return $query->PENDIDIKAN;
        return NULL;
    }

     
    function get_by_id($id)
    {
        $this->db->where('ID_PENDIDIKAN', $id);
        return $this->db->get($this->table);
    }

    function save($pendidikan)
    {
        $this->db->insert($this->table, $pendidikan);
        return $this->db->insert_id();
    }

    function update($id, $pendidikan)
    {
        $this->db->where('ID_PENDIDIKAN', $id);
        $this->db->update($this->table, $pendidikan);
    }

    function delete($id)
    {
        $this->db->where('ID_PENDIDIKAN', $id);
        $this->db->delete($this->table);
    }
}

?>