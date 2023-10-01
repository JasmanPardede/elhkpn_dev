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

class Minstansi extends CI_Model
{

    private $table = 'M_INST_SATKER';

    function __construct()
    {
        parent::__construct();
    }

    function list_all()
    {
        $this->db->order_by('INST_SATKERKD', 'asc');
        return $this->db->get($this->table);
    }
    
    function get_xt_dummy_nik($nik)
    {
        $this->db->where('NIK', $nik);
        return $this->db->get('xt_dummy_nik');
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
        $this->db->order_by('INST_SATKERKD', 'asc');
        $result = $this->db->get($this->table, $limit, $offset);
        return $result;
    }

    function get_by_id($id)
    {
        $this->db->where('INST_SATKERKD', $id);
        return $this->db->get($this->table);
    }

    function save($instansi)
    {
        $this->db->insert($this->table, $instansi);
        return $this->db->insert_id();
    }

    function update($id, $instansi)
    {
        $this->db->where('INST_SATKERKD', $id);
        $this->db->update($this->table, $instansi);
    }

    function delete($id)
    {
        $this->db->where('INST_SATKERKD', $id);
        $this->db->delete($this->table);
    }
    function get_nama_instansi($inst_satkerkd) {
        $this->db->select('INST_NAMA')->from($this->table)->where('INST_SATKERKD', $inst_satkerkd);
        $execute = $this->db->get();
        $result = '';
        if ( $execute->num_rows() > 0 ) {
            $data   = $execute->row();
            $result = $data->INST_NAMA;
        }
        return $result;
    }
}

?>