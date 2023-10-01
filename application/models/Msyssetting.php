<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Msyssetting
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Msyssetting extends CI_Model {

    private $table = 'CORE_SETTING';
    
    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->order_by('ID_SETTING', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '') {
        $this->db->where("IS_ACTIVE",'1');
        $this->db->where("SETTING",'tts');
        $this->db->where("OWNER",'app.lhkpn');
//        if (is_array($filter)) {
//            $useLike['AGAMA'] = 'both';
//            foreach ($filter as $key => $value) {
//                if (array_key_exists($key, $useLike)) {
//                    $this->db->or_like($key, $value, $useLike[$key]);
//                } else {
//                    $this->db->or_where($key, $value);
//                }
//            }
//        }
        return $this->db->get($this->table)->num_rows();
        // return $this->db->count_all($this->table);
    }

    
    function get_total_setting($cari = NULL, $advance_cari = NULL) {

        $this->db->from('CORE_SETTING');
        $this->db->where("IS_ACTIVE",'1');
        $this->db->where("SETTING",'tts');
        $this->db->where("OWNER",'app.lhkpn');
//        $this->set_where_daftar_agama($cari, $advance_cari);        
        
        return $this->db->count_all_results();
    }

    
    /**
     * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
     */
    function get_daftar_master_setting($offset = 0, $cari = NULL, $rowperpage = 10, $advance_cari = NULL) {
        $total_rows = $this->get_total_setting($cari, $advance_cari);
//        var_dump($total_rows, $this->db->last_query());
//        exit;
        
        $result = array();
        if ($total_rows > 0) {
            $sql_select = 
                    "`ID_SETTING`, " .
                    "`OWNER`, " .
                    "`SETTING`, " .
                    "`VALUE`, ";
            
            $this->db->select($sql_select, FALSE); 
            $this->db->where("IS_ACTIVE",'1');
            $this->db->where("SETTING",'tts');
            $this->db->where("OWNER",'app.lhkpn');
            
            $query = $this->db->get('CORE_SETTING', $rowperpage, $offset);

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
//        if (is_array($filter)) {
//            $useLike['AGAMA'] = 'both';
//            foreach ($filter as $key => $value) {
//                if (array_key_exists($key, $useLike)) {
//                    $this->db->or_like($key, $value, $useLike[$key]);
//                } else {
//                    $this->db->or_where($key, $value);
//                }
//            }
//        }
        $this->db->order_by('ID_SETTING', 'asc');
        $result= $this->db->get($this->table, $limit, $offset);
        return $result;
    }

    function get_nama_setting($id) {
        $this->db->select('VALUE')
            ->from($this->table)
            ->where('ID_SETTING', $id);
        $query = $this->db->get()->row();
        if ( is_object($query) )
            return $query->VALUE;
        return NULL;
    }

     
    function get_by_id($id)
    {
        $this->db->where('ID_SETTING', $id);
        return $this->db->get($this->table);
    }
//
//    function save($agama)
//    {
//        $this->db->insert($this->table, $agama);
//        return $this->db->insert_id();
//    }
//
//    function update($id, $agama)
//    {
//        $this->db->where('ID_AGAMA', $id);
//        $this->db->update($this->table, $agama);
//    }
//
//    function delete($id)
//    {
//        $this->db->where('ID_AGAMA', $id);
//        $this->db->delete($this->table);
//    }
}

?>