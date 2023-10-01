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

class Muser extends CI_Model {

    private $table = 'T_USER';
    private $table_role = 'T_USER_ROLE';
    private $user_pokja_role = 3;

    function __construct() {
        parent::__construct();
    }

    function get_id_by_username($username = '') {
        $this->db->select('ID_USER')->from($this->table)->where('USERNAME', $username);
        $query = $this->db->get();
        if (is_object($query)) {
            $data = $query->row();
            if (is_object($data)) {
                return $data->ID_USER;
            }
        }
        return 0;
    }

    function list_all() {
        $this->db->order_by('ID_USER', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '', $id_instansi = '', $id_role = '') {
        $where = '1=1';
        if (!empty($id_role))
            $where .= " AND ID_ROLE = " . $id_role;
        if (!empty($id_instansi))
            $where .= " AND INST_SATKERKD = " . $id_instansi;
        if (is_array($filter) && count($filter) > 0) {
            $where .= " AND (";
            $or_where = "";
            foreach ($filter as $key_filter => $val_filter) {
                $or_where .= "LOWER(" . $key_filter . ") LIKE '%" . $val_filter . "%' OR ";
            }
            $where .= substr($or_where, 0, strlen($or_where) - 4);
            $where .= ")";
        }
        $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
        $this->db->where($where)->from($this->table);
        return $this->db->count_all_results();
        // return $this->db->count_all($this->table);
    }

    function get_paged_list($limit = 10, $offset = 0, $filter = '') {
        if (is_array($filter)) {
            $useLike['T_USER.USERNAME'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('ID_USER', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function count_alluser($filter = '') {
        $where = "1=1";

        if (is_array($filter) && count($filter) > 0) {
            $where .= " AND (";
            $or_where = "";
            foreach ($filter as $key_filter => $val_filter) {
                $or_where .= "LOWER(" . $key_filter . ") LIKE '%" . strtolower($val_filter) . "%' OR ";
            }
            $where .= substr($or_where, 0, strlen($or_where) - 4);
            $where .= ")";
        }
        $this->db->where($where)->from($this->table);
        return $this->db->count_all_results();
    }

    function get_list_alluser($limit = 10, $offset = 0, $filter = '', $con = '1=1') {
        $where = $con;

        if (is_array($filter) && count($filter) > 0) {
            $where .= " AND (";
            $or_where = "";
            foreach ($filter as $key_filter => $val_filter) {
                $or_where .= "LOWER(" . $key_filter . ") LIKE '%" . strtolower($val_filter) . "%' OR ";
            }
            $where .= substr($or_where, 0, strlen($or_where) - 4);
            $where .= ")";
        }
        $this->db->where($where);
        $this->db->order_by('ID_USER', 'desc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_list_admininstansi($limit, $offset, $filter, $id_instansi) {
        $where = "ID_ROLE = " . ID_ROLE_AI;
        if (!empty($id_instansi))
            $where .= " AND INST_SATKERKD = " . $id_instansi;
        if (is_array($filter) && count($filter) > 0) {
            $where .= " AND (";
            $or_where = "";
            foreach ($filter as $key_filter => $val_filter) {
                $or_where .= "LOWER(" . $key_filter . ") LIKE '%" . strtolower($val_filter) . "%' OR ";
            }
            $where .= substr($or_where, 0, strlen($or_where) - 4);
            $where .= ")";
        }
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_user.UK_ID', 'LEFT');
        $this->db->where($where);
        $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
        $this->db->group_by('t_user.ID_USER');
        $this->db->order_by('NAMA', 'asc');
        // $this->db->order_by('ID_USER', 'desc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_list_admininstansi_two($id_instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {

        $cari_advance = $this->input->get('CARI');

        $condition_instansi = FALSE;

        $sql_where = " 1=1 AND ";

        if ($cari_advance) {
            //            $condition_instansi = $cari_advance["INSTANSI"] != '' ? "m_inst_satker.INST_SATKERKD ='" . $cari_advance["INSTANSI"] . "'" : " 1 ";

            $condition_instansi = array_key_exists("INSTANSI", $cari_advance) && $cari_advance["INSTANSI"] != '' ? "m_inst_satker.INST_SATKERKD ='" . $cari_advance["INSTANSI"] . "'" : " 1";

            $condition_status = array_key_exists("STATUS", $cari_advance) && $cari_advance["STATUS"] != '' ? "T_USER.IS_ACTIVE IN (" . $cari_advance["STATUS"] . ")" : " 1";

            $condition_cari_keyword = array_key_exists("TEXT", $cari_advance) && $cari_advance["TEXT"] != '' ? "   (T_USER.NAMA like '%" . $cari_advance["TEXT"] . "%' OR T_USER.USERNAME like '%" . $cari_advance["TEXT"] . "%' OR T_USER.EMAIL like '%" . $cari_advance["TEXT"]. "%') " : " 1";

            $sql_where .= "  " . $condition_status . " and " . $condition_instansi . " and " . $condition_cari_keyword . "   " . (is_null($uk_where) ? "" : $uk_where);
        }

        $select_fields = "*,m_inst_satker.INST_NAMA";

        $this->db->select($select_fields, FALSE);

        $where = "ID_ROLE = " . ID_ROLE_AI;

        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_user.UK_ID', 'LEFT');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
        $this->db->where($where);
        if ($cari_advance["STATUS"] == ''){
            $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
        }

        //        var_dump(trim($condition_instansi), trim($condition_instansi) != "1");exit;

        if ($condition_instansi) {
            $this->db->where($sql_where);
        }
        $this->db->group_by('t_user.ID_USER');
        $this->db->order_by('NAMA', 'asc');

        // $this->db->order_by('ID_USER', 'desc');
        //display($this->db->last_query());
        $query = NULL;
        if ($limit_mode) {
            $query = $this->db->get($this->table, $rowperpage, $offset);
        } else {
            $query = $this->db->get($this->table);
        }
        $result = $query->result();
        //display($this->db->last_query());
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return $query;
    }

    function admininstansi_ctk($id_instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {

        $condition_instansi = FALSE;

        $sql_where = " 1=1 AND ";

        if ($cari) {

            $condition_instansi = $cari->instansi != '' ? "m_inst_satker.INST_SATKERKD ='" . $cari->instansi . "'" : " 1";

            $condition_status = $cari->status != '' ? "T_USER.IS_ACTIVE IN (" . $cari->status . ")" : " 1";

            $condition_cari_keyword = $cari->text != '' ? "   (T_USER.NAMA like '%" . $cari->text . "%' OR T_USER.USERNAME like '%" . $cari->text . "%' OR T_USER.EMAIL like '%" . $cari->text. "%') " : " 1";

            $sql_where .= "  " . $condition_status . " and " . $condition_instansi . " and " . $condition_cari_keyword . "   " . (is_null($uk_where) ? "" : $uk_where);
        }

        $select_fields = "*,m_inst_satker.INST_NAMA";

        $this->db->select($select_fields, FALSE);

        $where = "ID_ROLE = " . ID_ROLE_AI;

        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_user.UK_ID', 'LEFT');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
        $this->db->where($where);
        if ($cari->status == ''){
            $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
        }

        if ($condition_instansi) {
            $this->db->where($sql_where);
        }
        $this->db->group_by('t_user.ID_USER');
        $this->db->order_by('NAMA', 'asc');

        $query = NULL;
        if ($limit_mode) {
            $query = $this->db->get($this->table, $rowperpage, $offset);
        } else {
            $query = $this->db->get($this->table);
        }
        $result = $query->result();

        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return $query;
    }

    function get_list_userpokja($limit, $offset, $filter, $id_instansi) {
        $where = "IS_USER_INSTANSI = 1";
        $and_where = "";
        if (!empty($id_instansi))
            $where .= " AND INST_SATKERKD = " . $id_instansi;
        if (is_array($filter) && count($filter) > 0) {
            $where .= " AND (";
            $or_where = "";
            foreach ($filter as $key_filter => $val_filter) {
                $or_where .= "LOWER(" . $key_filter . ") LIKE '%" . strtolower($val_filter) . "%' OR ";
            }
            $where .= substr($or_where, 0, strlen($or_where) - 4);
            $where .= ")";
        }
        $where .= $and_where;
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_user.UK_ID', 'LEFT');
        $this->db->where($where);
        $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
        //$this->db->or_where('T_USER.IS_ACTIVE',0);
        $this->db->group_by('t_user.ID_USER');
        $this->db->order_by('NAMA', 'asc');
        // $this->db->order_by('ID_USER', 'desc');
        $this->db->from($this->table);
        $this->db->join($this->table_role, $this->table . '.ID_ROLE = ' . $this->table_role . '.ID_ROLE');
        $this->db->limit($limit, $offset);
        return $this->db->get();
    }

    function get_list_userpokja_two($id_instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false, $role=3) {
        $cari_advance = $this->input->get('CARI');

        $where = " IS_USER_INSTANSI = 1";
        $and_where = "";

        if($role == '3' || $role == '4'){
            $cari_advance["INSTANSI"] = $id_instansi;
        }

        if ($cari_advance) {
            $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   m_inst_satker.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
            $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   t_user.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
            $condition_cari_ = $cari_advance["TEXT"] != '' ? "   (T_USER.NAMA like '%" . $cari_advance["TEXT"] . "%' OR T_USER.USERNAME like '%" . $cari_advance["TEXT"] . "%' OR T_USER.EMAIL like '%" . $cari_advance["TEXT"]. "%') " : " 1";
            $condition_status = array_key_exists("STATUS", $cari_advance) && $cari_advance["STATUS"] != '' ? "T_USER.IS_ACTIVE IN (" . $cari_advance["STATUS"] . ")" : " 1";

            $sql_where .= "  AND " . $condition_status . " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
        }

        $where .= $sql_where;
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_user.UK_ID', 'LEFT');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
        $this->db->where($where);
        if ($cari_advance["STATUS"] == ''){
            $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
        }
        $this->db->group_by('t_user.ID_USER');
        $this->db->order_by('NAMA', 'asc');
        $this->db->from($this->table);
        $this->db->join($this->table_role, $this->table . '.ID_ROLE = ' . $this->table_role . '.ID_ROLE');

        $query = NULL;
        if ($limit_mode) {
            $this->db->limit($rowperpage, $offset);
            $query = $this->db->get();
        } else {
            $query = $this->db->get();
        }
        //display($this->db->last_query());exit;
        $result = $query->result();
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return $query;
    }

    function adminauk_ctk($id_instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $where = " IS_USER_INSTANSI = 1";
        $and_where = "";

        if($this->session->userdata('ID_ROLE') == '3' || $this->session->userdata('ID_ROLE') == '4'){
            $cari->instansi = $this->session->userdata('INST_SATKERKD');
        }
        else{
            $cari->instansi = $id_instansi;
        }

        if ($cari) {
            $condition_instansi = $cari->instansi != '' ? "   m_inst_satker.INST_SATKERKD = '" . $cari->instansi . "'   " : " 1 ";
            $condition_uk = $cari->unit_kerja != '' ? "   t_user.UK_ID ='" . $cari->unit_kerja . "' " : " 1";
            $condition_cari_ = $cari->text != '' ? "   (T_USER.NAMA like '%" . $cari->text . "%' OR T_USER.USERNAME like '%" . $cari->text . "%' OR T_USER.EMAIL like '%" . $cari->text. "%') " : " 1";
            $condition_status = $cari->status != '' ? "T_USER.IS_ACTIVE IN (" . $cari->status . ")" : " 1";

            $sql_where .= "  AND " . $condition_status . " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
        }

        $where .= $sql_where;
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_user.UK_ID', 'LEFT');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
        $this->db->where($where);
        if ($cari->status == ''){
            $this->db->where('T_USER.IS_ACTIVE IN (0,1)');
        }
        $this->db->group_by('t_user.ID_USER');
        $this->db->order_by('NAMA', 'asc');
        $this->db->from($this->table);
        $this->db->join($this->table_role, $this->table . '.ID_ROLE = ' . $this->table_role . '.ID_ROLE');

        $query = NULL;
        if ($limit_mode) {
            $this->db->limit($rowperpage, $offset);
            $query = $this->db->get();
        } else {
            $query = $this->db->get();
        }
        $result = $query->result();
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return $query;
    }

    function get_userpokja_by_instansi($limit = 10, $offset = 0, $filter = '', $id_instansi = 0) {
        $where = "ID_ROLE = " . ID_ROLE_UI;
        if (!empty($id_instansi)) {
            $where .= " AND INST_SATKERKD = " . $id_instansi;
        }
        $where .= " AND IS_ACTIVE = 1";
        if (is_array($filter) && count($filter) > 0) {
            $where .= " AND (";
            $or_where = "";
            foreach ($filter as $key_filter => $val_filter) {
                $or_where .= "LOWER(" . $key_filter . ") LIKE '%" . strtolower($val_filter) . "%' OR ";
            }
            $where .= substr($or_where, 0, strlen($or_where) - 4);
            $where .= ")";
        }

        $this->db->where($where);
        $this->db->from($this->table);
        $this->db->limit($limit, $offset);
        $this->db->order_by('ID_USER', 'desc');
        return $this->db->get();
    }

    function get_by_id($id) {
        $this->db->where('ID_USER', $id);
        //display($this->db->last_query());exit;
        return $this->db->get($this->table);
    }

    function save($user) {
        $this->db->insert($this->table, $user);
        return $this->db->insert_id();
    }

    function update($id, $user) {
        $this->db->where('ID_USER', $id);
        $this->db->update($this->table, $user);
    }

    function delete($id) {
        $this->db->where('ID_USER', $id);
        $this->db->delete($this->table);
    }

    //Awal Fungsi Authentifikasi
    function get_role_kpk() {
        $this->db->select('ID_ROLE')->from($this->table_role)
                ->where('IS_KPK', 1);
        $query = $this->db->get();
        if (is_object($query)) {
            $data = $query->result();
            $id_role = array();
            foreach ($data as $dt) {
                $id_role[] = $dt->ID_ROLE;
            }
            return $id_role;
        }
        return array();
    }

    function cek_role_audit($usr){
        $this->db->select('ID_ROLE');
        $this->db->where('USERNAME', $usr);
        $this->db->where('IS_ACTIVE', '1');
        $query = $this->db->get($this->table);
        $role = $query->row();
        return $role->ID_ROLE;
    }

    function getSalt($usr){
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('USERNAME', $usr);
        $this->db->where('T_USER.IS_ACTIVE', '1');
        $query = $this->db->get();
        return $query->row()->SALT;
    }

    function ceklogin($usr, $pwd) {
        $role_kpk = $this->get_role_kpk();

        $this->db->select('*,m_unit_kerja.UK_nama');
        $this->db->from($this->table);
        $this->db->join('M_INST_SATKER', $this->table . '.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD', 'LEFT');
        $this->db->join('T_USER_ROLE', 'T_USER_ROLE.ID_ROLE = ' . $this->table . '.ID_ROLE', 'LEFT');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = ' . $this->table . '.UK_ID', 'LEFT');
        $this->db->where('USERNAME', $usr);
        $this->db->where('PASSWORD', $pwd);
        $this->db->where('T_USER.IS_ACTIVE', '1');
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $row = $query->row();
        if ($row) {
            $row->IS_KPK = 0;
            if (is_object($row)) {
//                $is_kpk = false;
//                $role_user = explode(',',$row->ID_ROLE);
//                for ( $i=0; $i<count($role_kpk); $i++ ) {
//                    if ( in_array($role_kpk[$i], $role_user) )
//                        $is_kpk = true;
//                }
//                //echo $is_kpk; exit();
//                if ( $is_kpk )
//                    $row->IS_KPK = 1;
//                if ( $is_kpk && ENV_USE_LDAP === true) {
//                    $ldap_login = $this->cekLoginLDAP($usr, $pwd);
//                    if ( $ldap_login )
//                        return $row;
//                } else if ( sha1(md5($pwd)) == $row->PASSWORD ) {
//                    return $row;
//                }
                return $row;
            }
        }

        return false;
    }

    function cekloginactive($usr, $pwd) {
        $role_kpk = $this->get_role_kpk();

        $this->db->select('*,m_unit_kerja.UK_nama');
        $this->db->from($this->table);
        $this->db->join('M_INST_SATKER', $this->table . '.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD', 'LEFT');
        $this->db->join('T_USER_ROLE', 'T_USER_ROLE.ID_ROLE = ' . $this->table . '.ID_ROLE', 'LEFT');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = ' . $this->table . '.UK_ID', 'LEFT');
        $this->db->where('USERNAME', $usr);
        $this->db->where('PASSWORD', $pwd);
        $this->db->where('T_USER.IS_ACTIVE', '0');
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $row = $query->row();
        if ($row) {
            $row->IS_KPK = 0;
            if (is_object($row)) {
                return $row;
            }
        }

        return false;
    }

    function bypasslogin($usr) {
        $role_kpk = $this->get_role_kpk();

        $this->db->select('*,m_unit_kerja.UK_nama');
        $this->db->from($this->table);
        $this->db->join('M_INST_SATKER', $this->table . '.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD', 'LEFT');
        $this->db->join('T_USER_ROLE', 'T_USER_ROLE.ID_ROLE = ' . $this->table . '.ID_ROLE', 'LEFT');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = ' . $this->table . '.UK_ID', 'LEFT');
        $this->db->where('USERNAME', $usr);
        $this->db->where('T_USER.IS_ACTIVE', '1');
        $query = $this->db->get();
        $row = $query->row();
        if ($row) {
            $row->IS_KPK = 0;
            if (is_object($row)) {
                return $row;
            }
        }

        return false;
    }

    function cekLoginLDAP($usr, $pwd) {
        if (empty($usr) || empty($pwd)) {
            return FALSE;
        }
        $kpk_ldap = new kpk_ldap();
        return $kpk_ldap->cek_login($usr, $pwd);

        // $ldap["host"] = $this->config->item('ldap_host');
        // $ldap["port"] = $this->config->item('ldap_port');
        // $ldap["dn"] = str_replace("[USERNAME]", $usr, $this->config->item('ldap_base_dn'));
        // // var_dump($ldap); exit();
        // // $ldap["dn"] = "uid=$usr,ou=id,ou=undp,o=un";
        // $ldap_connection = ldap_connect($ldap["host"], $ldap["port"]);
        // ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        // ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0);
        // if (@ldap_bind($ldap_connection, $ldap["dn"], $pwd)){
        //     // var_dump($ldap);
        //     ldap_unbind($ldap_connection);
        //     return TRUE;
        // }else{
        //     return FALSE;
        // }
        // $this->load->config('ldap');
        // $LDAPServerAddress      = $this->config->item('ldap_host');
        // $LDAPServerPort         = $this->config->item('ldap_port');
        // $LDAPServerTimeOut      = "60";
        // $LDAPContainer          = $this->config->item('ldap_base_dn');
        // $filter                 = "sAMAccountName=".$usr;
        // if( $ds=ldap_connect($LDAPServerAddress) ) {
        //     ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
        //     ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        //     if( $r=ldap_bind($ds,stripslashes($usr),$pwd) ) {
        //         if( $sr2=ldap_search($ds, $LDAPContainer, $filter, array("givenName","sn","mail","displayName")) ) {
        //             if( $info2 = ldap_get_entries($ds, $sr2) ) {
        //                 return $info2;
        //             }
        //         }
        //     }
        // }
        // return false;
    }

    //Akhir Authentifikasi
    function cek_username_email($username, $email) {
        $this->db->select('ID_USER, USERNAME, EMAIL')
                ->from($this->table)
                ->where(array('USERNAME' => $username, 'EMAIL' => $email));
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function cek_password($id_user, $password, $without_salt = false) {
        
        if($without_salt){
            $v_password = sha1($password);
        }else{
            $v_password = sha1(md5($password));
        }
        
        $this->db->select('COUNT(ID_USER) AS jumlah', false)
                ->from($this->table)
                ->where('ID_USER', $id_user)
                ->where('PASSWORD', $v_password);
        $query = $this->db->get();
        if (is_object($query)) {
            $data = $query->row();
            if (is_object($data))
                return $data->jumlah;
        }
        return false;
    }

    //cek otp verifikasi 2 langkah (login)
    function cek_otp($id_user, $otp){
        $this->db->select('COUNT(ID_USER) AS jumlah', false)
                ->from($this->table)
                ->where('ID_USER', $id_user)
                ->where('KODE_OTP', $otp);
        $query = $this->db->get();
        if (is_object($query)) {
            $data = $query->row();
            if (is_object($data))
                return $data->jumlah;
        }
        return false;
    }

    function cek_user($act) {
        $this->db->select('*');
        $this->db->where('USERNAME', $act);
//         $this->db->where('IS_ACTIVE', '1');
        $this->db->or_where('EMAIL', $act);
        return $this->db->get($this->table)->result_array();
    }

    function getPegawaiByNIP($NIP) {
        $this->db->where('NIP', $NIP);
        return $this->db->get('T_PEGAWAI');
    }

    function changePassword($NIP, $PASSWORDLAMA, $PASSWORDBARU) {

        $data = array(
            'PASSWORD' => sha1(md5($PASSWORDBARU))
        );

        $this->db->where('NIP', $NIP);
        $this->db->where('PASSWORD', md5($PASSWORDLAMA));
        return $this->db->update($this->table, $data);
    }

    function updateLastLogin($NIP) {
        $person['UPDATED_TIME'] = date('Y-m-d H:i:s');
        $person['UPDATED_BY'] = $NIP;
        $person['UPDATED_IP'] = $this->get_client_ip();
        $person['LAST_LOGIN'] = time();
        $this->db->where('USERNAME', $NIP);
        $this->db->update($this->table, $person);
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function insertLog($NIP) {
        $log['NIP'] = $NIP;
        $log['LOGIN_TIME'] = time();
        $log['IP'] = $this->get_client_ip();
        $this->db->insert('T_USER_LOG', $log);
        return $this->db->insert_id();
    }

    function getLastLogin($NIP) {
        $this->db->select('LAST_LOGIN');
        $this->db->where('USERNAME', $NIP);
        $rs = $this->db->get($this->table)->result();
        return $rs[0]->LAST_LOGIN;
    }

    function getUserSettingData($OWNER) {
        $this->db->where('OWNER', $OWNER);
        return $this->db->get('CORE_SETTING');
    }

    function setUserSettingData($OWNER, $setting, $value) {
        $settings['SETTING'] = $setting;
        $settings['VALUE'] = $value;
        $settings['OWNER'] = $OWNER;
        $this->db->onlyReturnQuery();
        $this->db->set($settings);
        $insert = $this->db->insert('CORE_SETTING');
        $this->db->onlyReturnQuery();
        $this->db->set($settings);
        $update = $this->db->update('CORE_SETTING');
        $update = preg_replace('/UPDATE.*?SET /', ' ON DUPLICATE KEY UPDATE ', $update);
        return $this->db->query($insert . $update);
    }

    function createRandomPassword($length) {
        $chars = "23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
        $i = 0;
        $password = "";
        while ($i <= $length) {
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
            $i++;
        }
        return $password;
        exit;
    }

    function kirim_reset_password($email, $username, $password) {
        $subject = 'Pemberitahuan Password Baru';
        $message = '
        <div>Email ini berisi informasi password baru akun anda.</div>
        <div><br>
        </div>
        <div>Username : ' . $username . '</div>
        <div>Password : ' . $password . '</div>
        <div><br>
        </div>
        <div>Untuk dapat menggunakan username dan password anda silakan mulai dari sini :</div>
        <div><br>
        </div>
        <div><a href="' . base_url() . '" target="_blank">' . base_url() . '</a>            <br>
        </div>
        ';
        return ng::mail_send($email, $subject, $message);
    }

    function kirim_reset_password_no_user($email, $username, $password) {
        $subject = 'Pemberitahuan Password Baru';
        $message = '
        <div>Email ini berisi informasi password baru akun anda.</div>
        <div><br>
        </div>
        <div>Password : ' . $password . '</div>
        <div><br>
        </div>
        <div>Untuk dapat menggunakan username dan password anda silakan mulai dari sini :</div>
        <div><br>
        </div>
        <div><a href="' . base_url() . '" target="_blank">' . base_url() . '</a>            <br>
        </div>
        ';
        return ng::mail_send($email, $subject, $message);
    }

    function kirim_info_akun($email, $username, $password, $nama = NULL, $inst_nama = NULL, $id_user = NULL, $_password = NULL) {

        $subject = 'Aktivasi e-LHKPN';
        $user_key = $id_user . $_password;
        $message = '<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<style type="text/css">
    /* FONTS */
    @media screen {
        @font-face {
          font-family: \'Lato\';
          font-style: normal;
          font-weight: 400;
          src: local(\'Lato Regular\'), local(\'Lato-Regular\'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format(\'woff\');
        }

        @font-face {
          font-family: \'Lato\';
          font-style: normal;
          font-weight: 700;
          src: local(\'Lato Bold\'), local(\'Lato-Bold\'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format(\'woff\');
        }

        @font-face {
          font-family: \'Lato\';
          font-style: italic;
          font-weight: 400;
          src: local(\'Lato Italic\'), local(\'Lato-Italic\'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format(\'woff\');
        }

        @font-face {
          font-family: \'Lato\';
          font-style: italic;
          font-weight: 700;
          src: local(\'Lato Bold Italic\'), local(\'Lato-BoldItalic\'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format(\'woff\');
        }
    }

    /* CLIENT-SPECIFIC STYLES */
    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    img { -ms-interpolation-mode: bicubic; }

    /* RESET STYLES */
    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
    table { border-collapse: collapse !important; }
    body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

    /* iOS BLUE LINKS */
    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }

    /* MOBILE STYLES */
    @media screen and (max-width:600px){
        h1 {
            font-size: 32px !important;
            line-height: 32px !important;
        }
    }

    /* ANDROID CENTER FIX */
    div[style*="margin: 16px 0;"] { margin: 0 !important; }
</style>
</head>
<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#e48683" align="center">
            <br>
            <br>
            <br>
            <br>
        </td>
    </tr>
    <tr>
        <td bgcolor="#e48683" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                <tr>
                    <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                      <h1 style="font-size: 48px; font-weight: 400; margin: 0;"></h1>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                  <p style="margin: 0;">Yth. Sdr. <strong>'.$nama.'</strong><br><strong>'.$inst_nama.'</strong><br>Di Tempat<br><br>Selamat, dan terima kasih anda telah terdaftar di Aplikasi e-LHKPN KPK, silahkan klik tombol dibawah ini untuk mengaktifkan akun anda :</p>
                </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="left">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td bgcolor="#ffffff" align="center" style="padding: 10px 30px 20px 30px;">
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                              <td align="center" style="border-radius: 3px;" bgcolor="#e48683"><a href="https://elhkpn.kpk.go.id/portal/user/login?' . $user_key . '" target="_blank" style="font-size: 18px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #e48683; display: inline-block;">Aktifkan Akun e-LHKPN</a></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                  <p style="margin: 0;">Berikut ini adalah Usename dan Password untuk Log in ke dalam Aplikasi e-LHKPN :</p>
                </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" >
                <tr>
                  <td bgcolor="#FFECD1" align="left" style="padding: 20px 0 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                    <h2 style="font-size: 16px; font-weight: 400; color: #111111; margin: 0;">Username : '.$username.'</h2><br>
                    <h2 style="font-size: 16px; font-weight: 400; color: #111111; margin: 0;">Password : '.$password.'</h2>
                  </td>
                </tr>
            </table>
        </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                  <p style="margin: 0;">Apabila tombol diatas tidak dapat digunakan, silahkan <i>copy-paste</i> tautan berikut ke browser chrome:</p>
                </td>
              </tr>
                <tr>
                  <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                    <p style="margin: 0;"><a href="https://elhkpn.kpk.go.id/portal/user/login?' . $user_key . '" target="_blank" style="color: #e48683;">https://elhkpn.kpk.go.id/portal/user/login?' . $user_key . '</a></p>
                  </td>
                </tr>
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                  <p style="margin: 0;">Untuk informasi lebih lanjut, silakan menghubungi kami melalui email <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>  atau call center 198.<br><br>Terima kasih,<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN</p>
                </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 25px;" >
                  <hr style="border: 0; border-bottom: 1px dashed #000;">
                  <p style="margin: 0;"><i>Email ini dikirimkan secara otomatis oleh sistem e-LHKPN, kami tidak melakukan pengecekan email yang dikirimkan ke email ini. Jika ada pertanyaan, silahkan hubungi call center 198 atau <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>.</i></p>
                </td>
              </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
              <tr>
                <td bgcolor="#f4f4f4" align="left" style="padding: 30px 30px 30px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
                  <p style="margin: 0;">&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</p>
                </td>
              </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';

        return ng::mail_send($email, $subject, $message);
    }

    /**
     * Berganti Nama
     *
     * ini MASIH DIGUNAKAN untuk Admin_instansi.php
     *
     * @see $this->kirim_info_akun
     * @author Lahir Wisada <lahirwisada@gmail.com>
     * @param type $email
     * @param type $username
     * @param type $password
     * @return type
     */
    function old_kirim_info_akun($email, $username, $password) {

        $subject = 'Aktivasi e-LHKPN';
        $this->db->where('USERNAME', $username);
        $this->db->order_by("ID_USER", 'desc');
        $this->db->limit(1);
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
        $user = $this->db->get('t_user')->row();
        $ID_USER = $user->ID_USER;
        $PASSWORDS = $user->PASSWORD;
        $user_key = $ID_USER . $PASSWORDS;
        $message = $this->__get_html_mail_pendaftaran_baru(strtoupper($user->NAMA), strtoupper($user->INST_NAMA), $username, $password, $user_key);

        return ng::mail_send($email, $subject, $message);
    }

    function __get_html_mail_pendaftaran_baru($nama, $inst_nama, $username, $password, $user_key) {
        return '<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<style type="text/css">
    /* FONTS */
    @media screen {
        @font-face {
          font-family: \'Lato\';
          font-style: normal;
          font-weight: 400;
          src: local(\'Lato Regular\'), local(\'Lato-Regular\'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format(\'woff\');
        }

        @font-face {
          font-family: \'Lato\';
          font-style: normal;
          font-weight: 700;
          src: local(\'Lato Bold\'), local(\'Lato-Bold\'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format(\'woff\');
        }

        @font-face {
          font-family: \'Lato\';
          font-style: italic;
          font-weight: 400;
          src: local(\'Lato Italic\'), local(\'Lato-Italic\'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format(\'woff\');
        }

        @font-face {
          font-family: \'Lato\';
          font-style: italic;
          font-weight: 700;
          src: local(\'Lato Bold Italic\'), local(\'Lato-BoldItalic\'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format(\'woff\');
        }
    }

    /* CLIENT-SPECIFIC STYLES */
    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    img { -ms-interpolation-mode: bicubic; }

    /* RESET STYLES */
    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
    table { border-collapse: collapse !important; }
    body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

    /* iOS BLUE LINKS */
    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }

    /* MOBILE STYLES */
    @media screen and (max-width:600px){
        h1 {
            font-size: 32px !important;
            line-height: 32px !important;
        }
    }

    /* ANDROID CENTER FIX */
    div[style*="margin: 16px 0;"] { margin: 0 !important; }
</style>
</head>
<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#e48683" align="center">
            <br>
            <br>
            <br>
            <br>
        </td>
    </tr>
    <tr>
        <td bgcolor="#e48683" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                <tr>
                    <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                      <h1 style="font-size: 48px; font-weight: 400; margin: 0;"></h1>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                  <p style="margin: 0;">Yth. Sdr. <strong>'.$nama.'</strong><br><strong>'.$inst_nama.'</strong><br>Di Tempat<br><br>Selamat, dan terima kasih anda telah terdaftar di Aplikasi e-LHKPN KPK, silahkan klik tombol dibawah ini untuk mengaktifkan akun anda :</p>
                </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="left">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td bgcolor="#ffffff" align="center" style="padding: 10px 30px 20px 30px;">
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                              <td align="center" style="border-radius: 3px;" bgcolor="#e48683"><a href="https://elhkpn.kpk.go.id/portal/user/login?' . $user_key . '" target="_blank" style="font-size: 18px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #e48683; display: inline-block;">Aktifkan Akun e-LHKPN</a></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                  <p style="margin: 0;">Berikut ini adalah Usename dan Password untuk Log in ke dalam Aplikasi e-LHKPN :</p>
                </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" >
                <tr>
                  <td bgcolor="#FFECD1" align="left" style="padding: 20px 0 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                    <h2 style="font-size: 16px; font-weight: 400; color: #111111; margin: 0;">Username : '.$username.'</h2><br>
                    <h2 style="font-size: 16px; font-weight: 400; color: #111111; margin: 0;">Password : '.$password.'</h2>
                  </td>
                </tr>
            </table>
        </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                  <p style="margin: 0;">Apabila tombol diatas tidak dapat digunakan, silahkan <i>copy-paste</i> tautan berikut ke browser chrome:</p>
                </td>
              </tr>
                <tr>
                  <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                    <p style="margin: 0;"><a href="https://elhkpn.kpk.go.id/portal/user/login?' . $user_key . '" target="_blank" style="color: #e48683;">https://elhkpn.kpk.go.id/portal/user/login?' . $user_key . '</a></p>
                  </td>
                </tr>
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                  <p style="margin: 0;">Untuk informasi lebih lanjut, silakan menghubungi kami melalui email <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>  atau call center 198.<br><br>Terima kasih,<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN</p>
                </td>
              </tr>
              <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 25px;" >
                  <hr style="border: 0; border-bottom: 1px dashed #000;">
                  <p style="margin: 0;"><i>Email ini dikirimkan secara otomatis oleh sistem e-LHKPN, kami tidak melakukan pengecekan email yang dikirimkan ke email ini. Jika ada pertanyaan, silahkan hubungi call center 198 atau <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>.</i></p>
                </td>
              </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
              <tr>
                <td bgcolor="#f4f4f4" align="left" style="padding: 30px 30px 30px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
                  <p style="margin: 0;">&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</p>
                </td>
              </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';
    }

    function kirim_info_lupa_password($email, $username, $password) {
        $subject = 'Reset Password || e-LHKPN';
        // $this->db->where('USERNAME', $username);
        // $this->db->limit(1);
        // $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD');
        // $user = $this->db->get('t_user')->row();

        $user = $this->db->select('t_user.NAMA, t_user_role.ID_ROLE')
                         ->join('t_user_role', 't_user_role.ID_ROLE = t_user.ID_ROLE', 'left')
                         ->where('USERNAME', $username)
                         ->limit(1)
                         ->get('t_user')
                         ->row();

        if ($user->ID_ROLE == '5') {
            $instansi = $this->db->select('m_inst_satker.INST_NAMA')
                                 ->join('t_pn_jabatan', 't_pn_jabatan.ID_PN = t_pn.ID_PN', 'left')
                                 ->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_pn_jabatan.ID_JABATAN', 'left')
                                 ->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left')
                                 ->where('t_pn_jabatan.IS_ACTIVE', 1)
                                 ->where('t_pn_jabatan.IS_CURRENT', 1)
                                 ->where('t_pn_jabatan.IS_DELETED', 0)
                                 ->where('NIK', $username)
                                 ->where('t_pn.IS_ACTIVE', 1)
                                 ->order_by('t_pn_jabatan.tahun_wl', 'DESC')
                                 ->limit(1)
                                 ->get('t_pn')
                                 ->row();
        } else {
            $instansi = $this->db->select('m_inst_satker.INST_NAMA')
                                 ->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'left')
                                 ->where('USERNAME', $username)
                                 ->limit(1)
                                 ->get('t_user')
                                 ->row();
        }

        $message = '


            <table style="margin-top:2%;">
                <tr>
                    <td><strong>Yth Sdr ' . strtoupper($user->NAMA) . '</strong></td>
                </tr>
                <tr>
                    <td><strong>' . strtoupper($instansi->INST_NAMA) . '</strong></td>
                </tr>
                <tr>
                    <td><strong>Di Tempat</strong></td>
                </tr>
            </table>

            <table style="margin-top:2%;">
                <tr>
                    <td>Anda menerima email ini karena adanya permintaan reset password ke server KPK. Silakan log in dengan password baru yaitu : </td>
                </tr>
            </table>

            <table style="margin-top:2%; border:1px solid #666; border-collapse: collapse; table-layout:fixed;" cellpadding="5" border="1">
                <tr style="background-color:#32CD32; color:#333;">
                    <td>Username</td>
                    <td>:</td>
                    <td>' . $username . '</td>
                </tr>
                <tr style="background-color:#32CD32; color:#333;">
                    <td>Password Baru</td>
                    <td>:</td>
                    <td><b>' . $password . '</b></td>
                </tr>
            </table>

            <table style="margin-top:2%;">
                 <tr>
                    <td>Silahkan klik tautan dibawah ini untuk login ke aplikasi e-lhkpn <a style="color:blue; font-weight:bold;" href="https://elhkpn.kpk.go.id">https://elhkpn.kpk.go.id</a></td>
                </tr>
                 <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>Apabila tautan diatas tidak dapat digunakan, silahkan <i>copy-paste</i> tautan tersebut ke browser Anda.</td>
                </tr>
            </table>

            <table style="margin-top:2%;">
                <tr>
                    <td>Terima kasih</td>
                </tr>
            </table>

            <table style="margin-top:2%;">
                <tr>
                    <td>Direktorat Pendaftaran dan Pemeriksaan LHKPN</td>
                </tr>
                <tr>
                    <td><hr style="border: 0; border-bottom: 1px dashed #000;"></td>
                </tr>
                <tr>
                    <td>&copy; 2017 Direktorat PP LHKPN KPK | <a target="_blank" href="http://www.kpk.go.id">www.kpk.go.id.</a> | <a target="_blank" href="http://www.elhkpn.kpk.go.id">elhkpn.kpk.go.id</a> | Layanan LHKPN 198</td>
                </tr>
            </table>
        ';

        return ng::mail_send($email, $subject, $message);
    }

    function updtResetPass($param) {
        $sql = $this->db->update_string('T_USER', $param, 'USERNAME = ' . $this->db->escape($param['USERNAME']));
        $ex = $this->db->query($sql);
        return $ex;
    }

    function getUser($NAME) {
        $sql = "SELECT * FROM T_USER where USERNAME = " . $this->db->escape($NAME);
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function getUser2($NAME, $EMAIL) {
        $sql = "SELECT * FROM T_USER where USERNAME = " . $this->db->escape($NAME);
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function check_user_if_exist($username) {
        $data = $this->db->select('*')->from('T_USER')->where('USERNAME', $username)->get()->result_array();
        return $data;
    }

    function check_user_for_edit($username, $current_username) {
        if (!empty($current_username) && !empty($username)) {
            $data = $this->db->select('*')->from('T_USER')->where('USERNAME', $username)
                            ->where('USERNAME !=', $current_username)->get()->result_array();
            return $data;
        }
        return 1;
    }

    function check_email_if_exist($email, $is_pn = 0) {
        $this->db->select('*')
                ->from('T_USER')
                ->join('T_USER_ROLE', 'T_USER.ID_ROLE = T_USER_ROLE.ID_ROLE', 'left')
                ->where('EMAIL', $email)
                ->where('IS_PN', $is_pn);

        $data = $this->db->get()->result_array();
        return $data;
    }

    function getKey($key) {
        $sql = "SELECT * FROM T_USER WHERE REQUEST_RESET_KEY = " . $this->db->escape($key);
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function getUserValid($data) {
        $sql = "SELECT
                    USERNAME,EMAIL,REQUEST_RESET_KEY
                FROM T_USER
                WHERE USERNAME = '$data[USERNAME]'
                AND REQUEST_RESET_KEY = '$data[REQUEST_RESET_KEY]'
                AND DATE(REQUEST_RESET_EXPIRED) >= CURDATE()
            ";
        $act = $this->db->query($sql)->num_rows();
        return $act;
    }

    function doUpPasNew($param) {
        $sql = $this->db->update_string('T_USER', $param, 'REQUEST_RESET_KEY = ' . $this->db->escape($param['REQUEST_RESET_KEY']));
        $ex = $this->db->query($sql);
        return $ex;
    }

    function doUpLogin($user, $status) {
        $person['IS_LOGIN'] = $status;
        $this->db->where('USERNAME', $user);
        $this->db->update($this->table, $person);
    }

    function check_user_key($key) {
        $sql = "
            SELECT ID_USER,SHA1(ID_USER) AS ID_KEY ,
            PASSWORD,
            CONCAT(SHA1(ID_USER),PASSWORD) AS USER_KEY
            FROM t_user
            WHERE CONCAT(ID_USER,PASSWORD) = '" . $key . "'
            AND IS_ACTIVE = 0
            AND IS_FIRST = 1
            LIMIT 1
        ";
        $data = $this->db->query($sql)->row();
        return $data;
    }

    function kirim_info_akun_aktif($email, $username) {

        $subject = 'Aktivasi e-LHKPN';
        $this->db->where('USERNAME', $username);
        $this->db->limit(1);
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
        $user = $this->db->get('t_user')->row();
        $ID_USER = sha1($user->ID_USER);
        $PASSWORD = $user->PASSWORD;
        $user_key = $ID_USER . $PASSWORD;
        $message = '

            <table>
                <tr>
                    <td><i>Alamat pengirim dari KPK</i></td>
                    <td style="width:10%;"></td>
                    <td>:<i><a target="_blank" href="http://wwww.elhkpn@kpk.go.id">elhkpn@kpk.go.id</a></i></td>
                </tr>
                <tr>
                    <td><i>Subject email</i></td>
                    <td style="width:10%;"></td>
                    <td>: <i>Aktivasi e-LHKPN</i></td>
                </tr>
            </table>

            <table style="margin-top:2%;">
                <tr>
                    <td><strong>Yth Sdr ' . strtoupper($user->NAMA) . '</strong></td>
                </tr>
                <tr>
                    <td><strong>' . strtoupper($user->INST_NAMA) . '</strong></td>
                </tr>
                <tr>
                    <td><strong>Di Tempat</strong></td>
                </tr>
            </table>



            <table style="margin-top:2%;">
                 <tr>
                    <td>Silahkan klik tautan dibawah ini untuk login <a style="color:blue; font-weight:bold;" href="https://elhkpn.kpk.go.id/portal/user/login?' . $user_key . '">https://elhkpn.kpk.go.id/portal/user/login?' . $user_key . '</a></td>
                </tr>
                 <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>Apabila tautan diatas tidak dapat digunakan, silahkan <i>copy-paste</i> tautan tersebut ke browser Anda.</td>
                </tr>
            </table>

            <table style="margin-top:2%;">
                <tr>
                    <td>Terima kasih</td>
                </tr>
            </table>

            <table style="margin-top:2%;">
                <tr>
                    <td>Direktorat Pendaftaran dan Pemeriksaan LHKPN</td>
                </tr>
                <tr>
                    <td>---------------------------------------------</td>
                </tr>
                <tr>
                    <td>@ ' . date('Y') . ' Direktorat PP LHKPN KPK | <a target="_blank" href="http://www.kpk.go.id">www.kpk.go.id.</a> | <a target="_blank" href="http://www.elhkpn.kpk.go.id">elhkpn.kpk.go.id</a> | Layanan LHKPN 198</td>
                </tr>
            </table>
        ';

        return ng::mail_send($email, $subject, $message);
    }

    function kirim_email_login($id_user,$ID_PN){
        $subject = 'Login e-LHKPN';
        $this->db->where('ID_USER', $id_user);
        $this->db->order_by("ID_USER", 'desc');
        $this->db->limit(1);
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
        $user = $this->db->get('t_user')->row();
        
        $this->db->select(
            't_pn_jabatan.*,
            m_inst_satker.INST_NAMA,
            '
        );
        $this->db->where('t_pn_jabatan.IS_CURRENT', 1);
        $this->db->where('t_pn_jabatan.IS_ACTIVE', 1);
        $this->db->where('t_pn_jabatan.IS_DELETED', 0);
        $this->db->where('t_pn_jabatan.ID_STATUS_AKHIR_JABAT', 0);
        $this->db->where('t_pn_jabatan.ID_STATUS_AKHIR_JABAT <>', 1);
        $this->db->where('t_pn_jabatan.ID_PN', $ID_PN);
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_pn_jabatan.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
        $this->db->group_by('t_pn_jabatan.TAHUN_WL');
        $this->db->order_by('t_pn_jabatan.TAHUN_WL','desc');
        $this->db->limit(1);
        $PN_JABATAN = $this->db->get('t_pn_jabatan')->row();

        $nama_pn = $user->NAMA;
        $instansi_pn = isset($PN_JABATAN->INST_NAMA) ? $PN_JABATAN->INST_NAMA : (isset($PN_JABATAN->NAMA_LEMBAGA) ? $PN_JABATAN->NAMA_LEMBAGA : '');
        $email = $user->EMAIL;

        ///// get user log /////
        $this->db->where('NIP', $user->USERNAME);
        $this->db->order_by("ID_LOG", 'desc');
        $this->db->limit(1);
        $user_login = $this->db->get('t_user_log')->row();

        $timestamp = $user_login->LOGIN_TIME;
        $convert_to_day = date('l', $timestamp);
        $login_day = $this->lib_date->convert_hari_indo($convert_to_day);
        
        $login_date = indonesian_date($timestamp, 'j F Y');
        $login_time = indonesian_date($timestamp, 'H:i');

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $OS = ng::getOS($user_agent);
        $browser = ng::getBrowser($user_agent);
        $ip = $user_login->IP;
        $getLocation = ng::detect_location($ip);
        $location = json_decode($getLocation);
        $country = $location->geoplugin_countryName;
        $city = $location->geoplugin_city;

        $message = $this->__get_html_mail_notif_login(strtoupper($nama_pn), strtoupper($instansi_pn), $login_day, trim($login_date), trim($login_time), $OS, $browser, $country, $city);
        return ng::mail_send_queue($email, $subject, $message, null, null, null, null, null, null, null, false, false, false, true);
    }

    function __get_html_mail_notif_login($nama, $inst_nama, $login_day, $login_date, $login_time, $OS, $browser, $country, $city) {
        $location = '';
        // if(!is_null($country) && !is_null($city)){
        //     $location = "lokasi : $city, $country";
        // }
        return '<html>
        <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <style type="text/css">
            /* FONTS */
            @media screen {
                @font-face {
                  font-family: \'Lato\';
                  font-style: normal;
                  font-weight: 400;
                  src: local(\'Lato Regular\'), local(\'Lato-Regular\'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format(\'woff\');
                }
        
                @font-face {
                  font-family: \'Lato\';
                  font-style: normal;
                  font-weight: 700;
                  src: local(\'Lato Bold\'), local(\'Lato-Bold\'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format(\'woff\');
                }
        
                @font-face {
                  font-family: \'Lato\';
                  font-style: italic;
                  font-weight: 400;
                  src: local(\'Lato Italic\'), local(\'Lato-Italic\'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format(\'woff\');
                }
        
                @font-face {
                  font-family: \'Lato\';
                  font-style: italic;
                  font-weight: 700;
                  src: local(\'Lato Bold Italic\'), local(\'Lato-BoldItalic\'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format(\'woff\');
                }
            }
        
            /* CLIENT-SPECIFIC STYLES */
            body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
            table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
            img { -ms-interpolation-mode: bicubic; }
        
            /* RESET STYLES */
            img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
            table { border-collapse: collapse !important; }
            body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
        
            /* iOS BLUE LINKS */
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }
        
            /* MOBILE STYLES */
            @media screen and (max-width:600px){
                h1 {
                    font-size: 32px !important;
                    line-height: 32px !important;
                }
            }
        
            /* ANDROID CENTER FIX */
            div[style*="margin: 16px 0;"] { margin: 0 !important; }
        </style>
        </head>
        <body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td bgcolor="#e48683" align="center">
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td bgcolor="#e48683" align="center" style="padding: 0px 10px 0px 10px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                        <tr>
                            <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                              <h1 style="font-size: 48px; font-weight: 400; margin: 0;"></h1>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                      <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                          <p style="margin: 0;">Yth. Sdr. <strong>'.$nama.'</strong><br><strong>'.$inst_nama.'</strong><br>Di Tempat<br><br>Akun LHKPN Anda baru saja login pada hari '.$login_day.' Tanggal '.$login_date.' jam '.$login_time.', '.$location.'  pada browser '.$browser.' di perangkat '.$OS.'. Anda mendapatkan email ini untuk memastikan ini memang Anda. Jika itu bukan Anda silahkan segera mengganti password untuk keamanan akun anda dengan klik tombol merah atau pada link https://elhkpn.kpk.go.id/.</p>
                        </td>
                      </tr>
                      <tr>
                        <td bgcolor="#ffffff" align="left">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td bgcolor="#ffffff" align="center" style="padding: 10px 30px 20px 30px;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                      <td align="center" style="border-radius: 3px;" bgcolor="#e48683"><a href="' . base_url() . 'portal/user/login" target="_blank" style="font-size: 18px; font-family: Helvetica, Arial, sans-serif; color: #000; text-decoration: none; color: #000; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #e48683; display: inline-block;">Login e-LHKPN</a></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>              
                       
                      <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                          <p style="margin: 0;">Untuk informasi lebih lanjut, silakan menghubungi kami melalui email <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>  atau call center 198.<br><br>Terima kasih,<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN</p>
                        </td>
                      </tr>
                      <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 25px;" >
                          <hr style="border: 0; border-bottom: 1px dashed #000;">
                          <p style="margin: 0;"><i>Email ini dikirimkan secara otomatis oleh sistem e-LHKPN, kami tidak melakukan pengecekan email yang dikirimkan ke email ini. Jika ada pertanyaan, silahkan hubungi call center 198 atau <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>.</i></p>
                        </td>
                      </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                      <tr>
                        <td bgcolor="#f4f4f4" align="left" style="padding: 30px 30px 30px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
                          <p style="margin: 0;">&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</p>
                        </td>
                      </tr>
                    </table>
                </td>
            </tr>
        </table>
        </body>
        </html>';
    }

}

?>