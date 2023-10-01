<?php

/**
 * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
 * @since 12 January 2017
 * 
 * untuk mengakomodasi pengambilan data dari m_area_prov
 */
class Marea_prov extends CI_Model {

    private $table = 'm_area_prov';

    function __construct() {
        parent::__construct();
    }

    private function set_limit($limit = FALSE, $offset = FALSE) {
        if ($limit !== FALSE && $offset !== FALSE) {
            $this->db->limit($limit, $offset);
        }
    }

    private function count_total_records_by_keyword($keyword = FALSE, $null_if_keyword_not_set = TRUE) {
        if ($null_if_keyword_not_set && $keyword === FALSE) {
            return 0;
        }

        $this->db->like('NAME', $keyword);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_select2_by_keyword($keyword = FALSE, $limit = FALSE, $offset = FALSE) {

        $result = $this->get_by_keyword($keyword, $limit, $offset);

        $result_province = (object) array(
                    "province" => array(),
                    "total" => $this->count_total_records_by_keyword($keyword),
        );

        if ($result && !empty($result)) {
            foreach ($result as $row) {
                $result_province->province[] = array(
                    'id' => $row->ID_PROV,
                    'text' => $row->NAME
                );
            }
        }
        return $result_province;
    }

    public function get_by_keyword($keyword = FALSE, $limit = FALSE, $offset = FALSE) {
        if ($keyword) {
            $this->set_limit($limit, $offset);
            $this->db->like('NAME', $keyword);
            $this->db->where(" NAME NOT LIKE '%--%' and IS_ACTIVE = '1' ");
            $this->db->order_by('NAME', 'ASC');

            $q = $this->db->get($this->table);

            if ($q) {
                return $q->result();
            }
        }
        return FALSE;
    }

}
