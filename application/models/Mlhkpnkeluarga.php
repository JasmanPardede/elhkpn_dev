<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Mkeluarga
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mlhkpnkeluarga extends CI_Model {

    private $table = 'T_LHKPN_KELUARGA';
    private $table_penerimaan = 't_lhkpnoffline_penerimaan';

    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->order_by('ID_KELUARGA', 'asc');
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
        $this->db->order_by('ID_KELUARGA', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_rincian($id) {
        $sql = " SELECT HUBUNGAN, COUNT(HUBUNGAN) AS JUMLAH FROM T_LHKPN_KELUARGA WHERE ID_LHKPN ='$id' GROUP BY HUBUNGAN ";
        $hasil = $this->db->query($sql)->result();
        return $hasil;
    }

    function get_rincian2($param) {
        $sql = " SELECT HUBUNGAN, COUNT(HUBUNGAN) AS JUMLAH FROM T_LHKPN_KELUARGA WHERE $param GROUP BY HUBUNGAN ";
        $hasil = $this->db->query($sql)->result();
        return $hasil;
    }

    function get_by_id($id) {
        $this->db->where('ID_KELUARGA', $id);
        return $this->db->get($this->table);
    }

    function save($keluarga) {
        $this->db->insert($this->table, $keluarga);
        return $this->db->insert_id();
    }

    function update($id, $keluarga) {
        $this->db->where('ID_KELUARGA', $id);
        $this->db->update($this->table, $keluarga);
    }

    function delete($id) {
        $this->db->where('ID_KELUARGA', $id);
        $this->db->delete($this->table);
    }

    function get_lhkpn_version($id) {
        $this->db->where('ID_LHKPN', $id);
        $hasil = $this->db->get($this->table_penerimaan);
        $this->db->flush_cache();
        if ($hasil->num_rows() > 0) {
            $res = $hasil->row();
            return $res->VERSI_EXCEL;
        }
        else{
            return false;
        }
    }

}

?>