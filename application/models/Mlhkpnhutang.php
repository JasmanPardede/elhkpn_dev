<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
	
/** 
 * Model Mlhkpnhutang
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mlhkpnhutang extends CI_Model
{

    private $table = 'T_LHKPN_HUTANG';

    function __construct()
    {
        parent::__construct();
    }

    function list_all()
    {
        $this->db->order_by('ID_HUTANG', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '')
    {
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

    function get_paged_list($limit = 10, $offset = 0, $filter = '')
    {
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
        $this->db->order_by('ID_HUTANG', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }
    function get_hutang()
    {
        $this->db->order_by('ID_JENIS_HUTANG', 'ASC');
        // $this->db->where('LEVEL', '1');
        // $this->db->like('GOLONGAN', $gol);
        $this->db->select('ID_JENIS_HUTANG, NAMA');
        $data = $this->db->get('M_JENIS_HUTANG')->result();
        return $data;
    }


    function get_by_id($id)
    {
        $this->db->where('ID_HUTANG', $id);
        return $this->db->get($this->table);
    }

    function save($lhkpnhutang)
    {
        $this->db->insert($this->table, $lhkpnhutang);
        return $this->db->insert_id();
    }

    function update($id, $lhkpnhutang)
    {
        $this->db->where('ID_HUTANG', $id);
        $this->db->update($this->table, $lhkpnhutang);
    }

    function delete($id)
    {
        $this->db->where('ID_HUTANG', $id);
        $this->db->delete($this->table);
    }
}

?>