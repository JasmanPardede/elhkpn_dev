<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
 */

class Mjenispelepasanharta extends CI_Model {

    private $table = 'M_JENIS_PELEPASAN_HARTA';

    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by('ID', 'asc');
        return $this->db->get($this->table);
    }
    
    function get_all(){
        $q = $this->list_all();
        return $q->result();
    }

    function count_all($filter = '') {
//        $this->db->where("m.IS_ACTIVE",'1');
        if (is_array($filter)) {
            $useLike['JENIS_PELEPASAN_HARTA'] = 'both';
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
    function set_where_daftar_jenispelepasanharta($cari = NULL, $advance_cari = NULL) {
        if (!is_null($cari) && trim($cari) != '') {
            $this->db->like("m.JENIS_PELEPASAN_HARTA", $cari);
        }

        if (!is_null($advance_cari) && is_array($advance_cari)) {
            $dictionary_advance_cari = array(
                "STATUS" => array(
                    "field_to_compare" => "m.IS_ACTIVE",
                    "compare_method" => "=",
                    "ci_slash_string" => TRUE
                ),
                "TEXT" => array(
                    "field_to_compare" => "m.JENIS_PELEPASAN_HARTA",
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

    function get_total_jenispelepasanharta($cari = NULL, $advance_cari = NULL) {

        $this->db->from('M_JENIS_PELEPASAN_HARTA m');
//        $this->from_and_join_master_jenisbukti();
//        $this->db->where("m.IS_ACTIVE",'1');
        $this->set_where_daftar_jenispelepasanharta($cari, $advance_cari);        
        
        return $this->db->count_all_results();
    }

    
    /**
     * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
     */
    function get_daftar_master_jenispelepasanharta($offset = 0, $cari = NULL, $rowperpage = 10, $advance_cari = NULL) {
        $total_rows = $this->get_total_jenispelepasanharta($cari, $advance_cari);
//        var_dump($total_rows, $this->db->last_query());
//        exit;
        
        $result = array();
        if ($total_rows > 0) {
            $sql_select = 
                    "`m`.`ID`, " .
                    "UPPER(`m`.`JENIS_PELEPASAN_HARTA`) as JENIS_PELEPASAN_HARTA, " ;

            $this->db->select($sql_select, FALSE); 
//            $this->from_and_join_master_jenisbukti();
//            $this->db->where("m.IS_ACTIVE",'1');
            $this->set_where_daftar_jenispelepasanharta($cari, $advance_cari);

            $query = $this->db->get('M_JENIS_PELEPASAN_HARTA m', $rowperpage, $offset);

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
            $useLike['JENIS_PELEPASAN_HARTA'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('ID', 'asc');
        $result= $this->db->get($this->table, $limit, $offset);
        return $result;
    }

    function get_nama_jenispelepasanharta($id) {
        $this->db->select('JENIS_PELEPASAN_HARTA')
            ->from($this->table)
            ->where('ID', $id);
        $query = $this->db->get()->row();
        if ( is_object($query) )
            return $query->JENIS_PELEPASAN_HARTA;
        return NULL;
    }
     
    function get_by_id($id)
    {
        $this->db->where('ID', $id);
        return $this->db->get($this->table);
    }

    function save($jenispelepasanharta)
    {
        $this->db->insert($this->table, $jenispelepasanharta);
        return $this->db->insert_id();
    }

    function update($id, $jenispelepasanharta)
    {
        $this->db->where('ID', $id);
        $this->db->update($this->table, $jenispelepasanharta);
    }

    function delete($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete($this->table);
    }
}