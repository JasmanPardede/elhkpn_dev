<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Model Role
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mrole extends CI_Model
{

    private $table = 'T_USER_ROLE';

    function __construct()
    {
        parent::__construct();
    }

    function list_all()
    {
        $this->db->order_by('ID_ROLE', 'asc');
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
        $this->db->order_by('ID_ROLE', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_by_id($id)
    {
        $this->db->where('ID_ROLE', $id);
        return $this->db->get($this->table);
    }

    function get_nama_role($id) {
        $this->db->select('ROLE')->from($this->table)->where('ID_ROLE',$id);
        $get    = $this->db->get();
        $res    = $get->row();
        $role   = '';
        if ( !empty($res->ROLE) )
            $role   = $res->ROLE;

        return $role;
    }

    function save($role)
    {
        $this->db->insert($this->table, $role);
        return $this->db->insert_id();
    }

    function update($id, $role)
    {
        $this->db->where('ID_ROLE', $id);
        $this->db->update($this->table, $role);
    }

    function delete($id)
    {
        $this->db->where('ID_ROLE', $id);
        $this->db->delete($this->table);
    }
}

?>