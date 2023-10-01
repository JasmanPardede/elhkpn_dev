<?php

class Mlhkpnofflinekeluarga extends CI_Model {

    private $table = 't_imp_xl_lhkpn_keluarga';

    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->order_by('id_imp_xl_keluarga', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '') {
        if (is_array($filter)) {
            $useLike['NAMA'] = 'both';
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

    function get_paged_list($limit = 10, $offset = 0, $filter = '') {
        if (is_array($filter)) {
            $useLike['NAMA'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('id_imp_xl_keluarga', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_rincian($id) {
        $sql = " SELECT HUBUNGAN, COUNT(HUBUNGAN) AS JUMLAH FROM ".$this->table." WHERE id_imp_xl_lhkpn ='$id' GROUP BY HUBUNGAN ";
        $hasil = $this->db->query($sql)->result();
        return $hasil;
    }

    function get_rincian2($param) {
        $sql = " SELECT HUBUNGAN, COUNT(HUBUNGAN) AS JUMLAH FROM ".$this->table." WHERE $param GROUP BY HUBUNGAN ";
        $hasil = $this->db->query($sql)->result();
        return $hasil;
    }

    function get_by_id($id) {
        $this->db->where('id_imp_xl_keluarga', $id);
        return $this->db->get($this->table);
    }

    function save($keluarga) {
        $this->db->insert($this->table, $keluarga);
        return $this->db->insert_id();
    }

    function update($id, $keluarga) {
        $this->db->where('id_imp_xl_keluarga', $id);
        $this->db->update($this->table, $keluarga);
    }

    function delete($id) {
        $this->db->where('id_imp_xl_keluarga', $id);
        $this->db->delete($this->table);
    }

}