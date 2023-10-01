<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model AI_LOG
 *
 * @author Rizki Nanda Mustaqim - PT.Akhdani Reka Solusi
 * @package Models
 */
?>
<?php

class Mapi_ai_log extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function list_all($offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false){
        $result = FALSE;
        $total_rows = 0;
        $cari_advance = $this->input->get('CARI');
        $sql_where = null;
        $status_code = null;

        if($cari_advance["STATUS"]==1){
            $status_code=200;
        }else if($cari_advance["STATUS"]==0){
            $status_code=500;
        }

        if(($status_code != null)){
            $condition_status = ($status_code==200) ? "http_response_code = '". $status_code . "'" : "http_response_code <> '". 200 . "'" ;
            $sql_where = "  " . $condition_status;
        }

        ///cek total baris//////
        $this->db->select("count(id) as cnt");
        if($sql_where){
            $this->db->where($sql_where);
        }
        $this->db->from('t_lhkpn_ai_log');
        $queryCount = $this->db->get();
        if ($queryCount) {
            $result = $queryCount->row();
            if ($result) {
                $total_rows = $result->cnt;
            }
        }

        /////ambil data///////
        $this->db->select("*");
        if($sql_where){
            $this->db->where($sql_where);
        }
        $this->db->from('t_lhkpn_ai_log');
        $this->db->order_by('created_at', 'desc');
        if ($limit_mode) {
            $query = $this->db->get(null,$rowperpage, $offset);
        } else {
            $query = $this->db->get();
        }

        if ($query) {
            $result = $query->result();
        }

        if($result){
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $result[$key]->ID_LHKPN = $item->id_lhkpn;
                $result[$key]->HTTP_CODE = $item->http_response_code;
                $result[$key]->CREATED_TIME_INDONESIA = tgl_format($item->created_at). ' ' .date_format(date_create($item->created_at),"H:i:s");
                $i++;
            }

        }
        return (object) array("total_rows" => $total_rows, "result" => $result);
        exit;

    }

}