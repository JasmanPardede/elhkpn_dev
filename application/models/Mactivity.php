<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
	
/** 
 * Model Mactivity
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mactivity extends CI_Model
{

    private $table = 'T_USER_ACTIVITY';

    function __construct()
    {
        parent::__construct();
    }

    function list_all()
    {
        $this->db->order_by('ID_ACTIVITY', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '')
    {
        if (is_array($filter)) {
            $useLike['ACTIVITY']        = 'both';
            $useLike['CREATED_TIME']    = 'both';
            $useLike['USERNAME']        = 'both';
            foreach ($filter as $key => $value) {
                if ($key == 'CREATED_TIME') {
                    $tmp = explode(' - ', $value);
                    $this->db->where($key.' >=', strtotime(@$tmp[0]));
                    $this->db->where($key.' <=', strtotime(@$tmp[1]));
                }
                else
                {
                    if (array_key_exists($key, $useLike)) {
                        $this->db->like($key, $value, $useLike[$key]);
                    } else {
                        $this->db->where($key, $value);
                    }
                }
            }
        }
        return $this->db->get($this->table)->num_rows();
        // return $this->db->count_all($this->table);
    }

    function get_paged_list($limit = 10, $offset = 0, $filter = '')
    {
        if (is_array($filter)) {
            $useLike['ACTIVITY']        = 'both';
            $useLike['CREATED_TIME']    = 'both';
            $useLike['USERNAME']        = 'both';
            foreach ($filter as $key => $value) {
                if ($key == 'CREATED_TIME') {
                    $tmp = explode(' - ', $value);
                    $this->db->where($key.' >=', strtotime(@$tmp[0]));
                    $this->db->where($key.' <=', strtotime(@$tmp[1]));
                }
                else
                {
                    if (array_key_exists($key, $useLike)) {
                        $this->db->like($key, $value, $useLike[$key]);
                    } else {
                        $this->db->where($key, $value);
                    }
                }
            }
        }
        $this->db->order_by('ID_ACTIVITY', 'asc');
        $data = $this->db->get($this->table, $limit, $offset);   
        return $data;
    }
	function list_role()
    {
		$this->db->where('IS_ACTIVE', '1');
		$this->db->order_by('ROLE', 'ASC');
        return $this->db->get('T_USER_ROLE');
    }
	function get_role()
    {
        $this->db->order_by('ROLE', 'asc');
        $this->db->where('IS_ACTIVE', '1');
        $this->db->select('ID_ROLE, ROLE');
        $data = $this->db->get('T_USER_ROLE')->result();
        return $data;
    }
	function get_user($id_role)
    {
        $this->db->order_by('USERNAME', 'asc');
        // $this->db->where('LEVEL', '1');
        $this->db->like('ID_ROLE', $id_role);
        $this->db->select('ID_USER, USERNAME');
        $data = $this->db->get('T_USER')->result();
        return $data;
    }
	
    function get_by_id($id)
    {
        $this->db->where('ID_ACTIVITY', $id);
        return $this->db->get($this->table);
    }

    function save($activity)
    {
        $this->db->insert($this->table, $activity);
        return $this->db->insert_id();
    }

    function update($id, $activity)
    {
        $this->db->where('ID_ACTIVITY', $id);
        $this->db->update($this->table, $activity);
    }

    function delete($id)
    {
        $this->db->where('ID_ACTIVITY', $id);
        $this->db->delete($this->table);
    }
}

?>