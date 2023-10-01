<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mglobal extends CI_Model {

    private $table_status_akhir = 'T_STATUS_AKHIR_JABAT';

    public function __construct() {
        parent::__construct();
        $this->role = $this->session->userdata('ID_ROLE');
        $this->instansi = $this->session->userdata('INST_SATKERKD');
    }

    public function count_data_all($table, $join = NULL, $where = NULL, $where_e = NULL, $group = NULL) {
        $this->db->select("count(*) as jumlah")->from($table);

        if (!is_null($join)) {
            foreach ($join as $rows) {
                if (!isset($rows['join'])) {
                    $rows['join'] = 'inner';
                }

                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query = $this->db->get();
        $result = $query->row();
        //var_dump("vardump".$this->db->last_query());exit;
        $this->db->flush_cache();
        return $result->jumlah;
    }

    public function get_data_all_secure($pk, $tb_alias = FALSE, $table = FALSE, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL) {
        if (!$table) {
            return FALSE;
        }

        $tb_alias = $tb_alias ? $tb_alias . "." : "";

        if (is_array($select)) {
            $select[0] .= ", func_make_secure(" . $tb_alias . $pk . ") " . $pk . "_secure ";
            $this->db->select($select[0], $select[1])->from($table);
        } else {
            $select .= ", func_make_secure(" . $tb_alias . $pk . ") " . $pk . "_secure ";
            $this->db->select($select)->from($table);
        }

        if (!is_null($join)) {
            foreach ($join as $key_index => $rows) {
                if (!isset($rows['join'])) {
                    $rows['join'] = 'LEFT';
                }
                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($order) ? $this->db->order_by($order[0], $order[1], @$order[2]) : '');
        (!is_null($tampil) ? $this->db->limit($tampil, $start) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query = $this->db->get();
        if (is_object($query)) {
            $result = $query->result();
        } else {
            $result = array();
        }
        $this->db->flush_cache();

        return $result;
    }

    public function get_data_all($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL) {
        if (is_array($select)) {
            $this->db->select($select[0], $select[1])->from($table);
        } else {
            $this->db->select($select,FALSE)->from($table);
        }

        if (!is_null($join)) {
            foreach ($join as $key_index => $rows) {
                if (!isset($rows['join'])) {
                    $rows['join'] = 'LEFT';
                }
                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($order) ? $this->db->order_by($order[0], $order[1], @$order[2]) : '');
        (!is_null($tampil) ? $this->db->limit($tampil, $start) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query = $this->db->get();
        if (is_object($query)) {
            $result = $query->result();
        } else {
            $result = array();
        }
        $this->db->flush_cache();

        return $result;
    }

    public function get_data_all_array($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL) {

        if (is_array($select)) {
            $this->db->select($select[0], $select[1])->from($table);
        } else {
            $this->db->select($select)->from($table);
        }

        if (!is_null($join)) {
            foreach ($join as $rows) {
                if (!isset($rows['join'])) {
                    $rows['join'] = 'inner';
                }

                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($order) ? $this->db->order_by($order[0], $order[1], @$order[2]) : '');
        (!is_null($tampil) ? $this->db->limit($tampil, $start) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query = $this->db->get();
//        echo "data ;".$query->num_rows();;
//        var_dump("vardump".$this->db->last_query());exit;
//        foreach ($query->result_array() as $row)
//{
//        echo "111".$row['SETTING'];
//        echo "112".$row['VALUE'];
//        echo "113".$row['OWNER'];
//}
        $result = $query->result_array();
        $this->db->flush_cache();
        return $result;
    }

    public function get_data_all_array_new($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL) {
        if (is_array($select)) {
            $this->db->select($select[0], $select[1])->from($table);
        } else {
            $this->db->select($select)->from($table);
        }

        if (!is_null($join)) {
            foreach ($join as $rows) {
                if (!isset($rows['join'])) {
                    $rows['join'] = 'left';
                }

                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($order) ? $this->db->order_by($order[0], $order[1], @$order[2]) : '');
        (!is_null($tampil) ? $this->db->limit($tampil, $start) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query = $this->db->get();
//        var_dump("vardump".$this->db->last_query());exit;
        $result = $query->result_array();
        $this->db->flush_cache();
        return $result;
    }

    public function insert($table, $data = NULL) {
        $result = $this->db->insert($table, $data);
        if ($result == TRUE) {
            $result = [];
            $result['status'] = TRUE;
            $result['id'] = $this->db->insert_id();
        } else {
            $result = [];
            $result['status'] = FALSE;
        }
        return $result;
    }

    public function update($table, $data = NULL, $where = NULL, $where_e = NULL) {
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        $result = $this->db->update($table, $data, $where);
        //display($this->db->last_query());exit;
        return $result;
    }

    function update_pn($id_pn, $data) {
        $this->db->where('ID_PN', $id_pn);
        $update = $this->db->update('t_pn_jabatan', $data);
        return $update;
    }

    public function get_status_akhir() {
        $this->db->from($this->table_status_akhir);
        $query = $this->db->get();
        if (is_object($query)) {
            return $query->result();
        }
        return false;
    }

    public function get_status_akhir_by_id($id_status_akhir = '') {
        $this->db->from($this->table_status_akhir)
                ->where('ID_STATUS_AKHIR_JABAT', $id_status_akhir);
        $this->db->order_by("IS_ORDER", "asc");
        $query = $this->db->get();
        if (is_object($query)) {
            $data = $query->row();
            if (is_object($data))
                return $data;
        }
        return false;
    }

    public function delete($table, $where = NULL, $where_e = NULL) {
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : ''
                );

        $result = $this->db->delete($table, $where);

        return $result;
    }

    function get_data_by_id($table, $field, $id, $limit = FALSE, $result_as_row = FALSE) {
        if ($limit !== FALSE) {
            $this->db->limit($limit);
        }
        $this->db->where($field, $id);
        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            if ($result_as_row) {
                return $query->row();
            }
            return $query->result();
        } else {
            return false;
        }
    }

    private function get_secure_condition($field, $id, $is_secure_id = FALSE) {
        if (!$is_secure_id)
            return "func_make_secure(" . $field . ") = '" . $id . "' ";

        return $field . " = '" . $id . "' ";
    }

    private function __secure_get_by_id($table, $pk_without_table_name, $return_type = "object", $additional_condition = FALSE) {

        if ($additional_condition && is_string($additional_condition)) {
            $this->db->where($additional_condition);
        }

        $query = $this->db->get($table);
        $result = FALSE;
        if (is_object($query)) {
            $result = $query->row();
            if (is_object($data)) {
                if ($return_type != "object") {
                    $result = $query->row_array();
                }
            }

            if ($result) {
                $result = make_secure_object($pk_without_table_name, $result, ($return_type == "object"));
            }
        }
        unset($query);
        return $result;
    }

    /**
     * 
     * @param type $table
     * @param type $field
     * @param type $pk_without_table_name
     * @param type $secure_id
     * @param type $return_type
     * @param string $additional_condition
     * @return type
     */
    function secure_get_by_secure_id($table, $field, $pk_without_table_name, $secure_id, $return_type = "object", $additional_condition = FALSE) {
        $this->db->where($this->get_secure_condition($field, $secure_id, TRUE));
        return $this->__secure_get_by_id($table, $pk_without_table_name, $return_type, $additional_condition);
    }

    /**
     * 
     * @param type $table
     * @param type $field
     * @param type $pk_without_table_name
     * @param type $id
     * @param type $return_type
     * @param string $additional_condition
     * @return type
     */
    function secure_get_by_id($table, $field, $pk_without_table_name, $id, $return_type = "object", $additional_condition = FALSE) {

        $this->db->where($this->get_secure_condition($field, $id));
        return $this->__secure_get_by_id($table, $pk_without_table_name, $return_type, $additional_condition);
    }

    function get_by_id($table, $field, $id, $return_type = "object") {
        $this->db->where($field, $id);
        $query = $this->db->get($table); 
        $result = FALSE;
        if (is_object($query)) {
            $result = $query->row(); 

            if ($return_type != "object") {
                $result = $query->row_array();
            }
        }
        unset($query); 
        return $result;
    }

    function get_by_id_2($id, $uname) {
        $this->db->select('a.*,c.`UK_nama`');
        $this->db->from('m_inst_satker a ,t_user b,m_unit_kerja c');
        $this->db->where('a.INST_SATKERKD=' . $id . ' AND b.`INST_SATKERKD` = b.`INST_SATKERKD` AND c.`UK_ID` = b.UK_ID and b.USERNAME="' . $uname . '"');

        $query = $this->db->get();
        //display($this->db->last_query());exit;
        if (is_object($query)) {
            $data = $query->row();
            if (is_object($data))
                return $data;
        }
        return false;
    }

    function get_by_id_rian($id) {
        $this->db->select('b.USERNAME, b.EMAIL');
        $this->db->from('t_user b');
        $this->db->where('b.ID_USER = "' . $id . '"');

        $query = $this->db->get();
        //display($this->db->last_query());exit;
        if (is_object($query)) {
            $data = $query->row();
            if (is_object($data))
                return $data;
        }
        return false;
    }

    function get_lembaga_uk_id_pn_by_nik($nik = FALSE) {
        $response = FALSE;
        if ($nik) {
            $query = $this->db->query("CALL `fu_select_uk_id_by_nik`('" . $nik . "')");
            $this->db->close();
            $this->db->initialize();

            if ($query) {
                $response = $query->row();
            }
            unset($query);
        }
        return $response;
    }

    // Untuk Detail PNWL   
    function get_detail_pn($id, $idjb = NULL, $select_fields = '*', $custom_condition = FALSE, $force_limit = FALSE, $return_in_array = FALSE) {
        $this->db->select($select_fields);
        $this->db->from('t_pn as p');
        $this->db->join('t_pn_jabatan as j', 'j.ID_PN = p.ID_PN', 'LEFT');
        $this->db->join('m_jabatan as jab', 'jab.ID_JABATAN = j.ID_JABATAN', 'LEFT');
        $this->db->join('m_sub_unit_kerja suk', 'suk.suk_id = jab.suk_id', 'LEFT');
        $this->db->join('m_unit_kerja uk', 'uk.uk_id = jab.uk_id', 'LEFT');
        $this->db->join('m_inst_satker ints', 'ints.INST_SATKERKD = uk.UK_LEMBAGA_ID', 'LEFT');
        $this->db->join('t_user as b', 'b.username = p.NIK', 'LEFT');

        if ($custom_condition) {
            $this->db->where($custom_condition);
        } else {
            $this->db->where('p.ID_PN', $id);
        }
        $this->db->where('j.IS_CURRENT', 1);
        if ($idjb) {
            $this->db->where('j.ID', $idjb);
        }

        if ($force_limit) {
            $this->db->limit(1);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            if ($return_in_array) {
                return $query->result();
            }

            return $query->row();
        } else {
            return false;
        }
    }

    private function __join_get_detail_pn_lhkpn($include_data_pribadi = FALSE, $include_data_pn = FALSE) {
        $this->db->join('t_pn as p', 'p.nik = u.username', 'LEFT');
        $this->db->join('t_lhkpn as l', 'l.id_pn = p.id_pn', 'LEFT');
        if ($include_data_pn) {
            $this->db->join('t_lhkpn_jabatan as j', 'j.id_lhkpn = l.id_lhkpn and j.IS_PRIMARY = "1"', 'LEFT');
            $this->db->join('M_JABATAN as mj', 'mj.ID_JABATAN = j.ID_JABATAN', 'LEFT');
            $this->db->join('M_INST_SATKER as mis', 'mis.INST_SATKERKD = mj.INST_SATKERKD', 'LEFT');
            $this->db->join('M_UNIT_KERJA as muk', 'muk.UK_ID = mj.UK_ID', 'LEFT');
            $this->db->join('M_SUB_UNIT_KERJA as suk', 'suk.SUK_ID = mj.SUK_ID', 'LEFT');
            $this->db->join('M_BIDANG as mb', 'mb.BDG_ID = mis.INST_BDG_ID', 'LEFT');
        }
        if ($include_data_pribadi) {
            $this->db->join('t_lhkpn_data_pribadi', 'l.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
            $this->db->join('t_pn_jabatan as pnj', 'pnj.ID_PN = l.ID_PN AND pnj.IS_CURRENT = 1', 'LEFT');
        }
    }

    function get_detail_pn_lhkpn($ID_LHKPN, $include_data_pribadi = FALSE, $include_data_pn = FALSE) {
        $this->db->select('*');
        $this->db->from('t_user as u');
        $this->__join_get_detail_pn_lhkpn($include_data_pribadi, $include_data_pn);
        $this->db->where('u.IS_ACTIVE', 1);
        $this->db->where('l.ID_LHKPN', $ID_LHKPN);


        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    private function __join_get_detail_pn_lhkpn_excel($include_data_pribadi = FALSE, $include_data_pn = FALSE) {
        $this->db->join('t_lhkpn as l', 'l.id_pn = p.id_pn', 'LEFT');
        if ($include_data_pn) {
            $this->db->join('t_lhkpn_jabatan as j', 'j.id_lhkpn = l.id_lhkpn and j.IS_PRIMARY = "1"', 'LEFT');
            $this->db->join('M_JABATAN as mj', 'mj.ID_JABATAN = j.ID_JABATAN', 'LEFT');
            $this->db->join('M_INST_SATKER as mis', 'mis.INST_SATKERKD = mj.INST_SATKERKD', 'LEFT');
            $this->db->join('M_UNIT_KERJA as muk', 'muk.UK_ID = mj.UK_ID', 'LEFT');
            $this->db->join('M_BIDANG as mb', 'mb.BDG_ID = mis.INST_BDG_ID', 'LEFT');
        }
        if ($include_data_pribadi) {
            $this->db->join('t_lhkpn_data_pribadi', 'l.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
            $this->db->join('t_pn_jabatan as pnj', 'pnj.ID_PN = l.ID_PN AND pnj.IS_CURRENT = 1', 'LEFT');
        }
    }
    
    function get_detail_pn_lhkpn_excel($ID_LHKPN, $include_data_pribadi = FALSE, $include_data_pn = FALSE) {
        $this->db->select('*');
        $this->db->from('t_pn as p');
        $this->__join_get_detail_pn_lhkpn_excel($include_data_pribadi, $include_data_pn);
        $this->db->where('p.IS_ACTIVE', 1);
        $this->db->where('l.ID_LHKPN', $ID_LHKPN);


        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Untuk menghapus data
    function delete_data($field, $id, $table) {
        $data = array(
            'IS_DELETED' => '1'
        );
        $this->db->where($field, $id);
        $this->db->where('IS_CURRENT', 1);
        $result = $this->db->update($table, $data);
        return $result;
    }

    function get_riwayat_jabatan($id) {
        $this->db->select("*, CASE WHEN pj.ID_STATUS_AKHIR_JABAT <> 1 THEN 'Primary' ELSE 'Rangkap' END AS ISPRIMARY");
        $this->db->from('T_PN_JABATAN pj');
        $this->db->join('M_JABATAN JAB', 'pj.ID_JABATAN=JAB.ID_JABATAN', "left");
        $this->db->join('m_sub_unit_kerja SUK', 'SUK.SUK_ID=JAB.SUK_ID', "left");
        $this->db->join('m_unit_kerja UK', 'UK.UK_ID=JAB.UK_ID', "left");
        $this->db->join('m_inst_satker inst', 'inst.INST_SATKERKD=JAB.INST_SATKERKD', "left");
        $this->db->where('pj.ID', $id);
        $this->db->where('pj.IS_ACTIVE', 1);
        $this->db->where('pj.IS_DELETED', 0);
        $this->db->where('pj.IS_CURRENT', 1);
        $this->db->where("(pj.ID_STATUS_AKHIR_JABAT = 0 OR pj.ID_STATUS_AKHIR_JABAT = 5 OR pj.ID_STATUS_AKHIR_JABAT = 10 OR pj.ID_STATUS_AKHIR_JABAT = 11 OR pj.ID_STATUS_AKHIR_JABAT = 15  OR pj.ID_STATUS_AKHIR_JABAT = 1  )");
        //$this->db->group_by('pj.id_pn'); 
        /*
          if ($this->role > 2)
          {
          $this->db->where('pj.LEMBAGA', $this->instansi);
          }
         */
        $query = $this->db->get();

        // Start "Penambahan union untuk rangkap jabatan"
        $id_pn = $query->row()->ID_PN;
        $this->db->select("*, CASE WHEN pj.ID_STATUS_AKHIR_JABAT <> 1 THEN 'Primary' ELSE 'Rangkap' END AS ISPRIMARY");
        $this->db->from('T_PN_JABATAN pj');
        $this->db->join('M_JABATAN JAB', 'pj.ID_JABATAN=JAB.ID_JABATAN', "left");
        $this->db->join('m_sub_unit_kerja SUK', 'SUK.SUK_ID=JAB.SUK_ID', "left");
        $this->db->join('m_unit_kerja UK', 'UK.UK_ID=JAB.UK_ID', "left");
        $this->db->join('m_inst_satker inst', 'inst.INST_SATKERKD=JAB.INST_SATKERKD', "left");
        $this->db->where('pj.ID_PN', $id_pn);
        $this->db->where('pj.IS_ACTIVE', 1);
        $this->db->where('pj.IS_DELETED', 0);
        $this->db->where('pj.IS_CURRENT', 1);
        $this->db->where('pj.ID_STATUS_AKHIR_JABAT', 1);
        $query2 = $this->db->get();
        $res = array_merge($query->result(), $query2->result());
        // End "Penambahan union untuk rangkap jabatan"

        //var_dump("vardump : ".$this->db->last_query());
        if ($query->num_rows() > 0) {
            // return $query->result();
            return $res;
        } else {
            return false;
        }
    }

    public function secure_update_data($table, $data = NULL, $field = NULL, $id = NULL) {
        $this->db->where($this->get_secure_condition($field, $id));
        $result = $this->db->update($table, $data);
        return $result;
    }
    
    public function secure_delete_data($table, $field = NULL, $id = NULL) {
        $this->db->where($this->get_secure_condition($field, $id));
        $this->db->delete($table);
    }

    public function update_data($table, $data = NULL, $field = NULL, $id = NULL) {
        $this->db->where($field, $id);
        $result = $this->db->update($table, $data);
        return $result;
    }

    function get_daftar_pn_individual($cari = NULL) {
        $this->db->select('*');
        $this->db->from('t_pn p');
        $this->db->join('t_pn_jabatan pj', 'p.ID_PN = pj.ID_PN', 'left');
        $this->db->where('pj.IS_CALON', 0);
        $this->db->where('pj.IS_ACTIVE', 0);
        $this->db->where('p.IS_ACTIVE', 0);
        $this->db->where('pj.IS_DELETED', 0);
        $this->db->where('pj.ID_STATUS_AKHIR_JABAT', 0);
//        $this->db->where('pj.IS_CURRENT', 1);

        if ($cari != NULL) {
            $this->db->like('NAMA', $cari);
            $this->db->like('NIK', $cari);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function daftar_pn_individual_by_id($id,$idj = NULL) {
        $this->db->select('*, p.NIK as KNIK, p.EMAIL as tpn_email, muk.UK_ID as unit_kerja_kd,pj.IS_WL,pj.TAHUN_WL,tu.ID_USER,p.NAMA,inst.INST_SATKERKD');
        $this->db->from('t_pn p');
        $this->db->join('t_pn_jabatan pj', 'p.ID_PN = pj.ID_PN', 'left');
        $this->db->join('m_jabatan jab', 'jab.ID_JABATAN = pj.ID_JABATAN', 'left');
        $this->db->join('m_unit_kerja muk', 'muk.UK_ID = jab.UK_ID', 'left');
        $this->db->join('m_sub_unit_kerja suk', 'suk.SUK_ID = jab.SUK_ID', 'left');
        $this->db->join('m_inst_satker inst', 'inst.INST_SATKERKD = jab.INST_SATKERKD', 'left');
        $this->db->join('t_user tu', 'tu.USERNAME = p.NIK', 'left');
//        $this->db->where('pj.IS_CALON', 0);
        $this->db->where('p.IS_ACTIVE', 1);
        $this->db->where('pj.IS_DELETED', 0);
        $this->db->where('pj.IS_CURRENT', 1);

        $this->db->where('p.ID_PN', $id);
        if ($idj != NULL){
            $this->db->where('pj.ID', $idj);
        }
        $query = $this->db->get();
        //var_dump("vardump : ".$this->db->last_query());
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function daftar_pn_individual_by_id_isCalon_1($id) {
        $this->db->select('*, p.NIK as KNIK, p.EMAIL as tpn_email, muk.UK_ID as unit_kerja_kd,pj.IS_WL,pj.TAHUN_WL');
        $this->db->from('t_pn p');
        $this->db->join('t_pn_jabatan pj', 'p.ID_PN = pj.ID_PN', 'left');
        $this->db->join('m_jabatan jab', 'jab.ID_JABATAN = pj.ID_JABATAN', 'left');
        $this->db->join('m_unit_kerja muk', 'muk.UK_ID = jab.UK_ID', 'left');
        $this->db->join('m_sub_unit_kerja suk', 'suk.SUK_ID = jab.SUK_ID', 'left');
        //$this->db->join('t_user tu', 'tu.USERNAME = p.NIK', 'left');
        $this->db->where('pj.IS_CALON', 1);
        $this->db->where('p.IS_ACTIVE', 1);
        $this->db->where('pj.IS_DELETED', 0);
        $this->db->where('pj.IS_CURRENT', 1);

        $this->db->where('p.ID_PN', $id);
        $query = $this->db->get();
        //var_dump("vardump : ".$this->db->last_query());
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function update_data_($table, $data, $field, $id) {
        $this->db->where($field, $id);
        $result = $this->db->update($table, $data);
        return $result;
    }

    function delete_data_($field, $id = null) {
        $this->db->where($field, $id);
        $result = $this->db->delete($table);
    }

    function update_perubahan_jabatan($id) {
        $LEMBAGA = $this->session->userdata('INST_SATKERKD');
        $this->db->where('ID_PN', $id);
        $this->db->where('LEMBAGA', $LEMBAGA);
        $this->db->where('IS_ACTIVE', 1);
        $this->db->where('IS_DELETED', 0);
        $this->db->where('IS_CURRENT', 1);

        $data = array('IS_CURRENT' => 0);
        $result = $this->db->update('T_PN_JABATAN', $data);
        return $result;
    }

    function update_perubahan_calon($id) {
        $this->db->where('ID_PN', $id);
        $this->db->where('IS_ACTIVE', 1);
        $this->db->where('IS_DELETED', 0);
        $this->db->where('IS_CURRENT', 1);

        $data = array('IS_CURRENT' => 0);
        $result = $this->db->update('T_PN_JABATAN', $data);
        return $result;
    }

    function cek_nik($nik) {
        $this->db->where('NIK', $nik);
        $this->db->from('T_PN');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function getMax($table, $field) {
        $this->db->select_max("$field");
        $result = $this->db->get($table)->row();
        return $result->$field;
    }

    function getMaxNHK($table, $field) {
        $sql = "select max(" . $field . "*1) as NHK from " . $table;
        $result = $this->db->query($sql)->row();
        return $result->$field;
    }

    function cek_inst_satkerkd($id) {
        $sql = "select * from m_inst_satker_area where INST_SATKERKD =" . $id;
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function save_area($instansi) {
        $insert = $this->db->insert('m_inst_satker_area', $instansi);
        return $insert;
    }

    function update_area($id, $instansi) {
        $this->db->where('INST_SATKERKD', $id);
        $this->db->update('m_inst_satker_area', $instansi);
    }

    function delete_area($id) {
        $this->db->where('INST_SATKERKD', $id);
        $this->db->delete('m_inst_satker_area');
    }

    function count_row_allpn($id) {
        $qry = "";
        if ($id)
            $qry = " AND PJ.LEMBAGA = $id";
        $sql = "
           SELECT DISTINCT
             P.ID_PN
           FROM
             t_pn AS P
           INNER JOIN `t_pn_jabatan` `PJ` ON `P`.`ID_PN` = `PJ`.`ID_PN`
           AND `PJ`.`ID_STATUS_AKHIR_JABAT` IN(0, 10, 11, 15) 
           AND `PJ`.`IS_DELETED` = '0' 
           AND `PJ`.`IS_CALON` = '0' 
           AND `PJ`.`IS_ACTIVE` = '1' 
           AND `PJ`.`IS_CURRENT` = '1' 
           WHERE
           `P`.`IS_ACTIVE` = '1' $qry 
           ";
        $query = $this->db->query($sql);


        return $query->num_rows();
    }

    function rekap_pnwl_delete() {
        $this->db->where('TAHUN', date('Y'));
        $this->db->delete('t_pn_kepatuhan_tahun');
        //echo "delete: ".$this->db->affected_rows();
    }

    function rekap_pnwl_entry() {
        $sql = "INSERT INTO t_pn_kepatuhan_tahun (SELECT ID_PN, " . date('Y') . ", DESKRIPSI_JABATAN, ESELON, 
                NAMA_LEMBAGA, UNIT_KERJA, SUB_UNIT_KERJA, IS_PRIMARY, ID_STATUS_AKHIR_JABAT, IS_CALON,
                IS_CURRENT, '" . time() . "', '" . $this->session->userdata('USR') . "' FROM t_pn_jabatan)";
        $query = $this->db->query($sql);
        echo "query: " . $query . "--"; //exit;
        return $query;
    }

    function get_excel_version($id_imp_lhkpn){
        $this->db->where('ID_IMP_XL_LHKPN', $id_imp_lhkpn);
        $result = $this->db->get('t_lhkpnoffline_penerimaan');
        return $result->row();
    }

    function get_data_pn_by_id_imp_xl_lhkpn($id)
    {
        $this->db->select('T_PN.*');
        $this->db->from('T_PN');
        $this->db->join('t_imp_xl_LHKPN', 'T_PN.ID_PN = t_imp_xl_LHKPN.ID_PN', 'left');
        $this->db->join('t_imp_xl_LHKPN_JABATAN', 't_imp_xl_LHKPN_JABATAN.id_imp_xl_lhkpn = t_imp_xl_LHKPN.id_imp_xl_lhkpn', 'left');
        $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = t_imp_xl_LHKPN_JABATAN.ID_JABATAN', 'left');
        $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = t_imp_xl_LHKPN_JABATAN.LEMBAGA', 'left');
        $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
        $this->db->where('t_imp_xl_LHKPN.id_imp_xl_lhkpn', $id);
        $data = $this->db->get();
        return $data->row();
    }

    function get_tahun_pelaporan($id_imp_xl_lhkpn)
    {
        $this->db->select('p.TANGGAL_PELAPORAN,p.JENIS_LAPORAN, p.TAHUN_PELAPORAN, l.TGL_LAPOR');
        $this->db->from('t_lhkpnoffline_penerimaan p');
        $this->db->join('t_imp_xl_lhkpn l', 'l.id_imp_xl_lhkpn = p.id_imp_xl_lhkpn');
        $this->db->where('p.id_imp_xl_lhkpn', $id_imp_xl_lhkpn);
        $result = $this->db->get();
        return $result->row();
    }

    function get_oflline_status_by_id($id)
    {
        $this->db->where('ID_LHKPN', $id);
        $result = $this->db->get('t_lhkpnoffline_penerimaan');
        if ($result->num_rows() == 1) {
            return $result->row()->IS_KEMBALI;
        }
        else{
            return 1;
        }
    }


    function recordLogAttacker($data,$method){
        if($this->session->userdata('USR')){
            $user = $this->session->userdata('USR');
        }else{
            $user = "Guest";
        }
        $state = array(
            'METHOD'=>$method,
            'DETECT'=>$data['detect'],
            'NAME_FILE'=>$data['name'],
            'TYPE_FILE'=>$data['type'],
            'KETERANGAN'=>$data['keterangan'],
            'CREATED_TIME'=>date('Y-m-d H:i:s'),
            'CREATED_BY'=>$user,
            'CREATED_IP'=>$_SERVER["REMOTE_ADDR"],
        );
        $this->db->insert('log_attacker', $state);
    }

}
