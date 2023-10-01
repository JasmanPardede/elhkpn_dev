<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Model DEVISSUE
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mdevissue extends CI_Model
{

    private $table = 'T_DEV_ISSUE';

    function __construct()
    {
        parent::__construct();
    }

    function list_all()
    {
        $this->db->order_by('ID_ISSUE', 'ASC');
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
        $this->db->order_by('ID_ISSUE', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_by_id($id)
    {
        $this->db->where('ID_ISSUE', $id);
        return $this->db->get($this->table);
    }

    function save($DEVISSUE)
    {
        $this->db->insert($this->table, $DEVISSUE);
        return $this->db->insert_id();
    }

    function update($id, $DEVISSUE)
    {
        $this->db->where('ID_ISSUE', $id);
        $this->db->update($this->table, $DEVISSUE);
    }

    function delete($id)
    {
        $this->db->where('ID_ISSUE', $id);
        $this->db->delete($this->table);
    }
}

?>