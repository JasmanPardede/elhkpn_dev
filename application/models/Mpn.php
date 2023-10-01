<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Model Mpejabat
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mpn extends CI_Model {

    private $table = 'T_PN';
    private $table_user = 'T_USER';
    private $table_role = 'T_USER_ROLE';
    private $table_jabatan = 'M_JABATAN';
    private $table_jabatan_pn = 'T_PN_JABATAN';
    private $table_satker = 'M_INST_SATKER';

    function __construct() {
        parent::__construct();
        $this->role = $this->session->userdata('ID_ROLE');
    }

    function testMpn() {
        echo "dapat";
    }

    function list_all() {
        $this->db->select($this->table_user . '.ID_USER,' . $this->table . '.*', false)
                ->from($this->table_user)
                ->join($this->table, $this->table_user . '.USERNAME = ' . $this->table . '.NIK', 'left')
                ->join($this->table_role, $this->table_user . '.ID_ROLE = ' . $this->table_role . '.ID_ROLE')
                ->where(array('IS_PN' => 1, 'IS_ACTIVE' => 1));
        $this->db->order_by('ID_PN', 'asc');
        return $this->db->get();
    }

    private function __set_join_get_all() {
        $this->db->join($this->table_jabatan_pn, $this->table_jabatan_pn . '.ID_PN = ' . $this->table . '.ID_PN');
        $this->db->join($this->table_jabatan, $this->table_jabatan_pn . '.ID_JABATAN = ' . $this->table_jabatan . '.ID_JABATAN', 'left');
        $this->db->join('m_sub_unit_kerja', "m_sub_unit_kerja.SUK_ID = $this->table_jabatan.SUK_ID ", 'left');
        $this->db->join('m_unit_kerja', "m_unit_kerja.UK_ID = $this->table_jabatan.UK_ID ", 'left');
        $this->db->join($this->table_satker, "$this->table_jabatan.INST_SATKERKD = $this->table_satker.INST_SATKERKD", 'left');
    }

    private function __set_where_get_all($ins, $uk_nama = NULL, $uk_id = NULL, $wl_tahun) {
        $this->db->where_in($this->table_jabatan_pn . '.ID_STATUS_AKHIR_JABAT', [0, 5]);
        $this->db->where($this->table_jabatan_pn . '.IS_CALON', 0);
        $this->db->where($this->table_jabatan_pn . '.IS_ACTIVE', 1);
        $this->db->where($this->table . '.IS_ACTIVE', 1);
        $this->db->where($this->table_jabatan_pn . '.IS_DELETED', 0);
        $this->db->where($this->table_jabatan_pn . '.IS_CURRENT', 1);
        $this->db->where($this->table_jabatan_pn . '.IS_WL', 1);


        if ($ins)
            $this->db->where($this->table_satker . '.INST_SATKERKD', $ins);

        if ($uk_id != NULL && $uk_id != '') {
            $this->db->where('m_unit_kerja.UK_ID', $uk_id);
        } else {
            if ($uk_nama)
                $this->db->where($this->table_jabatan_pn . '.UNIT_KERJA', "$uk_nama");
        }
        
        if ($wl_tahun){
            $this->db->where($this->table_jabatan_pn.'.tahun_wl', $wl_tahun);
        }
    }

    public function __count_get_all($ins, $uk_nama = NULL, $uk_id = NULL, $wl_tahun) {
        $this->__set_join_get_all();
        $this->__set_where_get_all($ins, $uk_nama, $uk_id, $wl_tahun);

        return $this->db->count_all_results($this->table);
    }

    private function __get_all($ins, $uk_nama = NULL, $uk_id = NULL, $wl_tahun) {
        $this->__set_join_get_all();
        $this->__set_where_get_all($ins, $uk_nama, $uk_id, $wl_tahun);

        $query = $this->db->get($this->table);

        return $query->result();
    }

    function get_mysql_secure_file_priv() {
        /**
         * jika kosong maka ada di /tmp
         * 
         * jika tidak kosong maka ada di folder yang terdapat pada field Value
         */
        $q = $this->db->query('SHOW VARIABLES LIKE "secure_file_priv"');

        return $q->row();
    }

    function get_all_limited($ins, $uk_nama, $limit, $offset, $uk_id = NULL, $wl_tahun) {
        $this->db->limit($limit, $offset);
        return $this->__get_all($ins, $uk_nama, $uk_id, $wl_tahun);
    }

    /**
     * MOHON JANGAN HAPUS FUNGSI DAN KOMENTAR INI
     * BERBAHAYA
     * BERBAHAYA
     * BERBAHAYA
     * 
     * KHUSUSON FERI JANGAN KODING
     * 
     * @param type $ins
     * @param type $uk_nama
     * @return type
     */
    function get_all($ins, $uk_nama = NULL, $uk_id = NULL) {

        $this->__set_join_get_all();
        $this->__set_where_get_all($ins, $uk_nama, $uk_id);

        $query = $this->db->get($this->table);
        //        debug($query);
        //        echo $this->db->last_query();exit;
        return $query->result();
    }

    function count_uk_by_uk_id($uk_id) {
        $this->db->join('t_user', 't_user.UK_ID = m_unit_kerja.UK_ID', 'inner');
        $this->db->where("t_user.UK_ID = '" . $uk_id . "'");
        return $this->db->count_all_results('m_unit_kerja');
    }

    function get_uk($uk_id) {

        /**
         * MOHON JANGAN HAPUS KOMENTAR INI
         */
        $jml_row = $this->count_uk_by_uk_id($uk_id);

        $query = "SELECT  A.UK_ID, A.UK_NAMA FROM m_unit_kerja AS A
                  INNER JOIN t_user AS B ON B.UK_ID = A.UK_ID
                  WHERE B.ID_USER = $uk_id";
        $query = $this->db->query($query);
        return $query->row();
    }

    function get_all_custome($table) {

        $query = $this->db->get($table);
        return $query->result();
    }

    public function __count_get_all_instansi($ins) {
        $this->__set_where_get_all_instansi($ins);

        return $this->db->count_all_results($this->table_satker);
    }

    private function __set_where_get_all_instansi($ins) {
        if ($ins) {
            $this->db->where("$this->table_satker.INST_SATKERKD", $ins);
        }

        return false;
    }

    function get_all_instansi($ins) {

        $this->__set_where_get_all_instansi($ins);

        $query = $this->db->get($this->table_satker);
        return $query->result();
    }

    function get_all_instansi_limited($ins, $limit, $offset) {
        $this->db->limit($limit, $offset);
        $this->__set_where_get_all_instansi($ins);

        $query = $this->db->get($this->table_satker);
        return $query->result();
    }

    private function __set_where_get_all_unit_kerja($ins, $uk_id) {
        $this->db->join($this->table_satker, "m_unit_kerja.UK_LEMBAGA_ID = $this->table_satker.INST_SATKERKD", 'LEFT');

        if ($ins)
            $this->db->where('m_unit_kerja.UK_LEMBAGA_ID', $ins);
        if ($uk_id)
            $this->db->where('m_unit_kerja.UK_ID', $uk_id);
    }

    public function __count_get_all_unit_kerja($ins, $uk_id) {
        $this->__set_where_get_all_unit_kerja($ins, $uk_id);

        return $this->db->count_all_results('m_unit_kerja');
    }

    function get_all_unit_kerja_limited($ins, $uk_id, $limit, $offset) {
        $this->db->limit($limit, $offset);
        $this->__set_where_get_all_unit_kerja($ins, $uk_id);

        $query = $this->db->get('m_unit_kerja');
        return $query->result();
    }

    function get_all_unit_kerja($ins, $uk_id = NULL) {

        $this->__set_where_get_all_unit_kerja($ins, $uk_id);

        $query = $this->db->get('m_unit_kerja');
        return $query->result();
    }

    public function __count_get_all_sub_unit_kerja($ins, $uk_id) {
        $this->__set_where_get_all_sub_unit_kerja($ins, $uk_id);

        return $this->db->count_all_results('m_sub_unit_kerja');
    }

    private function __set_where_get_all_sub_unit_kerja($ins, $uk_id) {
        $this->db->join('m_unit_kerja', "m_unit_kerja.UK_ID = m_sub_unit_kerja.UK_ID ", 'LEFT');

        if ($ins)
            $this->db->where('m_unit_kerja.UK_LEMBAGA_ID', $ins);
        if ($uk_id)
            $this->db->where('m_sub_unit_kerja.UK_ID', $uk_id);
    }

    function get_all_sub_unit_kerja_limited($ins, $uk_id, $limit, $offset) {
        $this->db->limit($limit, $offset);
        $this->__set_where_get_all_sub_unit_kerja($ins, $uk_id);

        $query = $this->db->get('m_sub_unit_kerja');
        return $query->result();
    }

    function get_all_sub_unit_kerja($ins, $uk_id = NULL) {
        $this->__set_where_get_all_sub_unit_kerja($ins, $uk_id);

        $query = $this->db->get('m_sub_unit_kerja');
        return $query->result();
    }

    function __set_where_get_all_jabatan($ins, $uk_id) {
        $this->db->join('m_sub_unit_kerja', "m_sub_unit_kerja.SUK_ID = $this->table_jabatan.SUK_ID ", 'left');
        $this->db->join('m_unit_kerja', "m_unit_kerja.UK_ID = $this->table_jabatan.UK_ID ", 'left');

        if ($ins)
            $this->db->where('m_unit_kerja.UK_LEMBAGA_ID', $ins);
        if ($uk_id)
            $this->db->where($this->table_jabatan . '.UK_ID', $uk_id);
        $this->db->where($this->table_jabatan . '.IS_ACTIVE', '1');
    }

    public function __count_get_all_jabatan($ins, $uk_id) {
        $this->__set_where_get_all_jabatan($ins, $uk_id);

        return $this->db->count_all_results($this->table_jabatan);
    }

    function get_all_jabatan_limited($ins, $uk_id, $limit, $offset) {
        $this->db->limit($limit, $offset);
        $this->__set_where_get_all_jabatan($ins, $uk_id);
        $query = $this->db->get($this->table_jabatan);
        return $query->result();
    }

    function get_all_jabatan($ins, $uk_id = NULL) {
        $this->__set_where_get_all_jabatan($ins, $uk_id);

        $query = $this->db->get($this->table_jabatan);
        return $query->result();
    }

    //verse 2 no jabatan
    function count_all2($limit = 10, $offset = 0, $filter = '', $d_instansi = '') {
        $where = "IS_PN = 1";
        if (is_array($filter)) {
            $where .= " AND (";
            foreach ($filter as $key => $value) {
                $where .= $this->table . '.' . $key . " LIKE '%" . $value . "%' OR ";
            }
            $where = substr($where, 0, strlen($where) - 4);
            $where .= ")";
        }
        if (!empty($id_instansi))
            $where .= " AND INST_SATKERKD = '" . $id_instansi . "'";
        $this->db->select($this->table_user . '.ID_USER,  ' . $this->table . '.*', false)
                ->from($this->table_user)
                ->join($this->table, $this->table_user . '.USERNAME = ' . $this->table . '.NIK', 'left')
                ->join($this->table_role, $this->table_user . '.ID_ROLE = ' . $this->table_role . '.ID_ROLE', 'left')
                // ->join($this->table_jabatan, $this->table_jabatan.'.ID_JABATAN = '.$this->table.'.ID_JABATAN')
                ->limit($limit, $offset)
                ->where($where, NULL, false);
        return $this->db->get()->num_rows();
    }

    function count_all($filter) {
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

    function get_paged_list($limit = 10, $offset = 0, $filter = '') {
        $where = "(INST_SATKERKD = '" . $this->session->userdata('INST_SATKERKD') . "'
            OR ID_PN IN (SELECT ID_PN FROM " . $this->table_jabatan_pn . ")
        ) AND IS_PN = 1";
        if (is_array($filter)) {
            $where .= " AND (";
            foreach ($filter as $key => $value) {
                $where .= $this->table . '.' . $key . " LIKE '%" . $value . "%' OR ";
            }
            $where = substr($where, 0, strlen($where) - 4);
            $where .= ")";
        }
        $this->db->select($this->table_user . '.ID_USER,
            ' . $this->table_jabatan . '.NAMA_JABATAN, ' . $this->table . '.*', false)
                ->from($this->table_user)
                ->join($this->table, $this->table_user . '.USERNAME = ' . $this->table . '.NIK', 'left')
                ->join($this->table_role, $this->table_user . '.ID_ROLE = ' . $this->table_role . '.ID_ROLE', 'left')
                ->join($this->table_jabatan, $this->table_jabatan . '.ID_JABATAN = ' . $this->table . '.ID_JABATAN')
                ->limit($limit, $offset)
                ->where($where, NULL, false);
        return $this->db->get();
    }

    function get_nonaktif_list($limit = 10, $offset = 0, $filter = '') {
        // $where = "INST_SATKERKD = '".$this->session->userdata('INST_SATKERKD')."' AND IS_PN = 1
        $where = "1=1 AND IS_PN = 1
                  AND " . $this->table . ".IS_ACTIVE = 0";
        if (is_array($filter)) {
            $where .= " AND (";
            foreach ($filter as $key => $value) {
                $where .= $this->table . '.' . $key . " LIKE '%" . $value . "%' OR ";
            }
            $where = substr($where, 0, strlen($where) - 4);
            $where .= ")";
        }
        $this->db->select($this->table_user . '.ID_USER,' . $this->table . '.*', false)
                ->from($this->table_user)
                ->join($this->table, $this->table_user . '.USERNAME = ' . $this->table . '.NIK', 'left')
                ->join($this->table_role, $this->table_user . '.ID_ROLE = ' . $this->table_role . '.ID_ROLE')
                ->limit($limit, $offset)
                ->where($where, NULL, false);
        return $this->db->get();
    }

    //versi2 (no jabatan)

    function get_all_paged_list2($limit = 10, $offset = 0, $filter = '', $id_instansi = '') {
        $where = "IS_PN = 1";
        if (is_array($filter)) {
            $where .= " AND (";
            foreach ($filter as $key => $value) {
                $where .= $this->table . '.' . $key . " LIKE '%" . $value . "%' OR ";
            }
            $where = substr($where, 0, strlen($where) - 4);
            $where .= ")";
        }
        if (!empty($id_instansi))
            $where .= " AND INST_SATKERKD = '" . $id_instansi . "'";
        $this->db->select($this->table_user . '.ID_USER,  ' . $this->table . '.*', false)
                ->from($this->table_user)
                ->join($this->table, $this->table_user . '.USERNAME = ' . $this->table . '.NIK', 'left')
                ->join($this->table_role, $this->table_user . '.ID_ROLE = ' . $this->table_role . '.ID_ROLE', 'left')
                // ->join($this->table_jabatan, $this->table_jabatan.'.ID_JABATAN = '.$this->table.'.ID_JABATAN')
                ->limit($limit, $offset)
                ->where($where, NULL, false);
        return $this->db->get();
    }

    function get_all_paged_list($limit = 10, $offset = 0, $filter = '', $id_instansi = '') {
        $where = "IS_PN = 1";
        if (is_array($filter)) {
            $where .= " AND (";
            foreach ($filter as $key => $value) {
                $where .= $this->table . '.' . $key . " LIKE '%" . $value . "%' OR ";
            }
            $where = substr($where, 0, strlen($where) - 4);
            $where .= ")";
        }
        if (!empty($id_instansi))
            $where .= " AND INST_SATKERKD = '" . $id_instansi . "'";
        $this->db->select($this->table_user . '.ID_USER,' . $this->table . '.*', false)
                ->from($this->table_user)
                ->join($this->table, $this->table_user . '.USERNAME = ' . $this->table . '.NIK', 'left')
                ->join($this->table_role, $this->table_user . '.ID_ROLE = ' . $this->table_role . '.ID_ROLE', 'left')
                // ->join($this->table_jabatan, $this->table_jabatan.'.ID_JABATAN = '.$this->table.'.ID_JABATAN')
                ->limit($limit, $offset)
                ->where($where, NULL, false);
        return $this->db->get();
    }

    function get_by_id($id) {
        $this->db->select($this->table_user . '.ID_USER, ' . $this->table . '.*')
                ->from($this->table)
                ->join($this->table_user, $this->table_user . '.USERNAME = ' . $this->table . '.NIK', 'left')
                ->where('ID_PN', $id);
        return $this->db->get();
        exit;
    }

    function get_by_nik($id) {
        $this->db->select($this->table_user . '.ID_USER, ' . $this->table . '.*')
                ->from($this->table)
                ->join($this->table_user, $this->table_user . '.USERNAME = ' . $this->table . '.NIK', 'left')
                ->where('NIK', $id);
        return $this->db->get();
    }

    function save($pejabat) {
        $this->db->insert($this->table, $pejabat);
        return $this->db->insert_id();
    }

    function save_jabatan_pn($pejabat) {
        $this->db->insert($this->table_jabatan_pn, $pejabat);
        return $this->db->insert_id();
    }

    function update($id, $pejabat) {
        $this->db->where('ID_PN', $id);
        $this->db->update($this->table, $pejabat);
    }

    function delete($id) {
        $this->db->where('ID_PN', $id);
        $this->db->delete($this->table);
    }

    function update_jabatan_pn($id_jabatan, $data) {
        $this->db->where('ID_JABATAN', $id_jabatan);
        $this->db->update($this->table_jabatan_pn, $data);
    }

    function kirim_info_pn($email, $username, $password, $judul = 'Pemberitahuan Informasi Akun') {
        $subject = $judul;
        $message = '
		<div>Selamat datang di Aplikasi KPK</div>
		<div>Email ini berisi informasi account anda.</div>
		<div><br>
		</div>
		<div>Username : ' . $username . '</div>
		<div>Password : ' . $password . '</div>
		<div><br>
		</div>
		<div>Untuk dapat menggunakan username dan password anda silakan mulai dari sini :</div>
		<div><br>
		</div>
		<div><a href="' . base_url() . '" target="_blank">' . base_url() . '</a>	 		<br>
		<div>Peringatan : Username dan password akan kadaluarsa dalam 8 hari jika anda tidak login!</div>
		</div>
		';
        return ng::mail_send($email, $subject, $message);
    }

    function get_data_jabatan_pn($id_pn, $id_instansi) {
        $this->db->select('ID_JABATAN, LEMBAGA, UNIT_KERJA, ESELON')
                ->from($this->table)
                ->where('ID_PN', $id_pn)
                ->where('LEMBAGA', $id_instansi);
        $query = $this->db->get();
        if (is_object($query)) {
            $data = $query->row();
            if (is_object($data)) {
                $parent = true;
                return $data;
            }
        }

        $this->db->select('ID_JABATAN, LEMBAGA, UNIT_KERJA, ESELON')
                ->from($this->table_jabatan_pn)
                ->where('ID_PN', $id_pn)
                ->where('LEMBAGA', $id_instansi);
        $query = $this->db->get();
        if (is_object($query)) {
            $data = $query->row();
            if (is_object($data)) {
                $parent = true;
                return $data;
            }
        }
        return false;
    }

    public function get_instansi_pn($id_pn) {
        $this->db->select('DISTINCT(A.LEMBAGA) AS INST_SATKERKD, B.INST_NAMA', false)
                ->from($this->table_jabatan_pn . ' A')
                ->join($this->table_satker . ' B', 'A.LEMBAGA = B.INST_SATKERKD', 'left')
                ->where('A.ID_PN', $id_pn);
        $query = $this->db->get();
        if (is_object($query)) {
            $data = $query->result();
            return $data;
        }
        return array();
    }

    public function get_jabatan_akhir_by_instansi($id_pn, $id_instansi) {
        $this->db->select('ID_JABATAN')
                ->from($this->table_jabatan_pn)
                ->where('ID_PN', $id_pn)
                ->where('LEMBAGA', $id_instansi)
                ->limit(1)
                ->order_by('ID', 'DESC');
        $query = $this->db->get();
        if (is_object($query)) {
            $data = $query->row();
            if (is_object($data)) {
                return $data;
            }
        }
        return false;
    }

    function get_daftar_pn_individual($ins, $cari = NULL, $additional_join_fn = FALSE) { // penambahan
        $cari_advance = $this->input->get('CARI');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');

        $this->db->select('*');
        $this->db->from('t_pn p');
        $this->db->join('t_pn_jabatan pj', 'p.ID_PN = pj.ID_PN', 'left');
        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN', 'left');
        $this->__get_daftar_pn_individual_two_join();
        $this->db->join("T_USER U", "U.USERNAME = P.NIK");

        if ($additional_join_fn && is_string($additional_join_fn) && method_exists($this, $additional_join_fn)) {
            $this->{$additional_join_fn}();
        }

        $this->db->where('pj.IS_CALON', 0);
        $this->db->where('pj.IS_ACTIVE', 1);
        $this->db->where('pj.IS_CURRENT', 1);
        $this->db->where('p.IS_ACTIVE', 1);
        $this->db->where('pj.IS_DELETED', 0);
        $this->db->where('pj.ID_STATUS_AKHIR_JABAT', 10);

        if ($id_role != 1 && $id_role != 2) {
            if ($this->session->userdata('INST_SATKERKD') !== null){
                $this->db->where('INTS.INST_SATKERKD', $this->session->userdata('INST_SATKERKD'));
            }
            
        }
        //        $this->db->where('pj.IS_CURRENT', 1);

        if ($is_uk == 1) {
            $this->db->where('JAB.UK_ID', $id_uk);
        }

        if ($cari != NULL) {
            $nik_or_nama = " (p.NAMA LIKE '%" . $cari . "%' or p.NIK LIKE '%" . $cari . "%')";
            $this->db->where($nik_or_nama);
        }

        // 
        return $this->db->count_all_results();

        //        } else {
        //            return false;
        //        }
    }

    function get_where_status_akhir_jabatan($page_mode = '') {
        $response = "`PJ`.`ID_STATUS_AKHIR_JABAT` IN(10) ";
        switch ($page_mode) {
            case 0:
                /**
                 * perubahan individu
                 */
                $response = "`PJ`.`ID_STATUS_AKHIR_JABAT` = 10 ";
                break;
            case 1:
                /**
                 * perubahan individu
                 */
                $response = "`PJ`.`ID_STATUS_AKHIR_JABAT` = 11 ";
                break;
            case 2:
                /**
                 * pn individu nonactive
                 */
                $response = "`PJ`.`ID_STATUS_AKHIR_JABAT` = 15 ";
                break;
            default:
                /**
                 * penambahan individu
                 */
                $response = "`PJ`.`ID_STATUS_AKHIR_JABAT` = 10 ";
                break;
        }
        return $response;
    }

    function get_total_row_individu($page_mode = NULL, $cari = NULL, $additional_join_fn = FALSE) {
        $ins = $this->session->userdata('INST_SATKERKD');
            //        $total_rows = $this->get_daftar_pn_individual($ins, $cari);
        switch ($page_mode) {
            case 1:
                $total_rows = $this->get_daftar_pn_individual_PerubahanJabatan($ins, $cari);
                break;
            case 2:
                $total_rows = $this->get_daftar_pn_individual_nonact($ins, $cari);
                break;
            case 4:
                $total_rows = $this->get_daftar_wajiblapor($ins, $cari);
                break;
            case 5:
                $total_rows = $this->get_daftar_nonwajiblapor($ins, $cari);
                break;
            case 6:
                $total_rows = $this->get_daftar_jml_calonpn($ins, $cari);
                break;
            case 0:
            default:
            //                $total_rows = $this->get_daftar_pn_individual($ins, $cari, $additional_join_fn);
                $total_rows = $this->get_daftar_pn_individual($ins, $cari, TRUE);
                break;
        }
        return $total_rows;
    }
    private function get_daftar_pn_individual_two_cari_advance($uk_where, $cari = NULL, $page_mode = "") {

        $cari_advance = $this->input->get('CARI');
        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');

        //$cari_advance["INSTANSI"] = $cari_advance["INSTANSI"] != FALSE && $cari_advance["INSTANSI"] != "" ? $cari_advance["INSTANSI"] : '1081';
        /* IBO UPDATE */
        if($cari_advance["INSTANSI"]=="000000"){
            $cari_advance["INSTANSI"]='';
        }
        /* End IBO UPDATE */
        $sql_where = "P.IS_ACTIVE = '1' AND " . $this->get_where_status_akhir_jabatan($page_mode);
        
        if ($cari_advance) {
            $is_role_one_two = $id_role && ($id_role == 2 || $id_role == 31);
            //var_dump($is_role_one_two);exit;
            if ($is_role_one_two) {
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
            } else {
                $condition_instansi = " 1 ";
            }
            $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : ( "  1  ");
            $sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " $uk_where     ";
        }
        /* IBO UPDATE */
        if($cari_advance){
            //var_dump($cari_advance["JENIS"]);exit;
            if($cari_advance["JENIS"] !=2)
            {
                $condition_jenis=$cari_advance["JENIS"] != '' ? "   P.IS_APLIKASI = '" . $cari_advance["JENIS"] . "'   " : " 1 ";
                $sql_where .= " AND " . $condition_jenis; 
            }
        }
        /* End IBO UPDATE */
        if (!is_null($cari) && $cari != '') {
            $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";
        }

        if ($id_role != 1 && $id_role != 2 && $id_role != 31) {
            if ($ins !== null){
                $sql_where .= " AND INTS.INST_SATKERKD = $ins";
            }else{
                $sql_where .= " ";
            }
            
        }

        if ($is_uk == 1) {
            $sql_where .= " AND JAB.UK_ID = $id_uk";
        }

        return $sql_where;
    }
    private function __get_daftar_pn_individual_two_join() {
        $this->db->join("`m_sub_unit_kerja` `SUK`", "`SUK`.`SUK_ID` = `JAB`.`SUK_ID`", "left");
        $this->db->join("`m_unit_kerja` `UK`", "`UK`.`UK_ID` = `JAB`.`UK_ID`", "left");
        $this->db->join("`m_inst_satker` `INTS`", "`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`", "left");
    }
    function get_daftar_pn_individual_two($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {

        $uk_where = $this->uker && !is_null($this->uker) ? "AND uk.UK_ID = $this->uker" : "AND 1";

        $sql_where = $this->get_daftar_pn_individual_two_cari_advance($uk_where, $cari, $page_mode);
        $this->db->where($sql_where);

        $total_rows = $this->get_total_row_individu($page_mode, $cari, '__get_daftar_pn_individual_two_join');
        //echo $this->db->last_query();exit;
        if ($instansi) {
            $instansi == '' ? " 1 " : $instansi;
        }


        $result = [];
        if ($total_rows > 0) {
            $result = [];
            $sql_select = "DISTINCT " . $offset . " + 1 AS position, P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                    . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                    . " TRIM(CONCAT(IF(ISNULL(P.GELAR_DEPAN),'',P.GELAR_DEPAN),' ',P.NAMA,IF(ISNULL(P.GELAR_BELAKANG) OR P.GELAR_BELAKANG = '', '', ', '),IF(ISNULL(P.GELAR_BELAKANG),'',P.GELAR_BELAKANG))) AS NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                    . " PJ.SUB_UNIT_KERJA, PJ.NAMA_LEMBAGA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                    . " PJ.EMAIL_KANTOR, PJ.ESELON, P.IS_APLIKASI";

            $this->db->select($sql_select, FALSE);
            $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

            $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
            //                    . "AND `PJ`.`ID_STATUS_AKHIR_JABAT` = 10 "
                    . "AND `PJ`.`IS_DELETED` = '0' "
            //                    . "AND `PJ`.`IS_CALON` = '0' "
                    . "AND `PJ`.`IS_ACTIVE` = '1' "
                    . "AND `PJ`.`IS_CURRENT` = '1' ";
            
            if ($page_mode == '6'){
                $sql_where_join .= "AND `PJ`.`IS_CALON` = '1' ";
            }else{
                $sql_where_join .= "AND `PJ`.`IS_CALON` = '0' ";
            }
            
            $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
            $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN AND JAB.IS_ACTIVE <> 0");
            //            $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN", "left");

            $this->__get_daftar_pn_individual_two_join();
            $this->db->where($sql_where);


            $query = $this->db->get_where('t_pn P', NULL, $rowperpage, $offset);
            //            echo $this->db->last_query();exit;

            if ($query) {
                $result = $query->result();
            }
            if ($result) {
                $i = 1 + $offset;
                foreach ($result as $key => $item) {
                    $result[$key]->NO_URUT = $i;
                    $i++;
                }
            }
        //            }
        }
        //
        return (object) array("total_rows" => $total_rows, "result" => $result);
    }
    function get_daftar_pn_individual_two_ctk($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {
        
        $uk_where = $this->uker && !is_null($this->uker) ? "AND uk.UK_ID = $this->uker" : "AND 1";
        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');

        $sql_where = "P.IS_ACTIVE = '1' AND " . $this->get_where_status_akhir_jabatan($page_mode);
       
        if ($id_role != 1 && $id_role != 2 && $id_role != 31) {
            if ($ins !== null){
                $sql_where .= " AND INTS.INST_SATKERKD = '".$ins. "'   ";
            }
            if ($cari->unit_kerja != '') {
                $condition_uk = $cari->unit_kerja != '' ? "   uk.UK_ID ='" . $cari->unit_kerja . "' " : ( "  1  ");
                $sql_where .= " AND " . $condition_uk . "   ";
            }
        }
        else{
            if ($cari->instansi != '') {
                $condition_instansi = $cari->instansi != '' ? "   INTS.INST_SATKERKD = '" . $cari->instansi . "' " : ("  1  ");
                $sql_where .= " AND " . $condition_instansi . "   ";
                if ($cari->unit_kerja != '') {
                    $condition_uk = $cari->unit_kerja != '' ? "   uk.UK_ID ='" . $cari->unit_kerja . "' " : ( "  1  ");
                    $sql_where .= " AND " . $condition_uk . "   ";
                }
            }
        }
        
        if($cari->jenis != ''){
            if($cari->jenis !=2)
            {
                $condition_jenis=$cari->jenis != '' ? "   P.IS_APLIKASI = '" . $cari->jenis . "'   " : " 1 ";
                $sql_where .= " AND " . $condition_jenis; 
            }
        }
        
        if (!is_null($cari->text) && $cari->text != '') {
            // $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari->text . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari->text . "' , '%') )";
            $sql_where .= "AND (P.NAMA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari->text . "' , '%') )";
        }
        
       
        if ($is_uk == 1) {
            $sql_where .= " AND JAB.UK_ID = $id_uk";
        }
        
        $this->db->where($sql_where);
        $total_rows = $this->get_total_row_individu($page_mode, $cari, '__get_daftar_pn_individual_two_join');
        
        $result = [];
        $sql_select = "DISTINCT " . $offset . " + 1 AS position, P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                . " TRIM(CONCAT(IF(ISNULL(P.GELAR_DEPAN),'',P.GELAR_DEPAN),' ',P.NAMA,IF(ISNULL(P.GELAR_BELAKANG) OR P.GELAR_BELAKANG = '', '', ', '),IF(ISNULL(P.GELAR_BELAKANG),'',P.GELAR_BELAKANG))) AS NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                . " PJ.SUB_UNIT_KERJA, PJ.NAMA_LEMBAGA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                . " PJ.EMAIL_KANTOR, PJ.ESELON, P.IS_APLIKASI";

        $this->db->select($sql_select, FALSE);
        $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

        $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
                . "AND `PJ`.`IS_DELETED` = '0' "
                . "AND `PJ`.`IS_ACTIVE` = '1' "
                . "AND `PJ`.`IS_CURRENT` = '1' ";
        
        if ($page_mode == '6'){
            $sql_where_join .= "AND `PJ`.`IS_CALON` = '1' ";
        }else{
            $sql_where_join .= "AND `PJ`.`IS_CALON` = '0' ";
        }
        
        $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
        $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN");

        $this->__get_daftar_pn_individual_two_join();
        $this->db->where($sql_where);
        
        $query = $this->db->get_where('t_pn P', NULL, $rowperpage, $offset);
        
        if ($query) {
            $result = $query->result();
        }
        
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return (object) array("result" => $result);
    }
    function get_daftar_pn_individual_PerubahanJabatan($ins, $cari = NULL) { // penambahan
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');


        $this->db->select('*');
        $this->db->from('t_pn p');
        $this->db->join('t_pn_jabatan pj', 'p.ID_PN = pj.ID_PN', 'left');
        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN');
        //        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN', 'left');
        $this->__get_daftar_pn_individual_two_join();

        $this->db->where('pj.IS_CALON', 0);
        $this->db->where('pj.IS_ACTIVE', 1);
        $this->db->where('pj.IS_CURRENT', 1);
        $this->db->where('p.IS_ACTIVE', 1);
        $this->db->where('pj.IS_DELETED', 0);
        $this->db->where('pj.ID_STATUS_AKHIR_JABAT', 11);
        

        if ($this->role > 2)
            if ($this->session->userdata('INST_SATKERKD') !== null){
                $this->db->where('INTS.INST_SATKERKD', $this->session->userdata('INST_SATKERKD'));
            }
        //        $this->db->where('pj.IS_CURRENT', 1);

        if ($is_uk == 1)
            $this->db->where('JAB.UK_ID', $id_uk);


        if ($cari != NULL) {
            $nik_or_nama = " (NAMA LIKE '%" . $cari . "%' or NIK LIKE '%" . $cari . "%')";
            $this->db->where($nik_or_nama);
        }




        return $this->db->count_all_results();
        //        $query = $this->db->get();
        //
        //        if ($query->num_rows() > 0) {
        //            return $query->result();
        //        } else {
        //            return false;
        //        }
    }
    function get_daftar_pn_individual_nonact($ins, $cari = NULL) { // penambahan
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');



        $this->db->select('*');
        $this->db->from('t_pn p');
        $this->db->join('t_pn_jabatan pj', 'p.ID_PN = pj.ID_PN', 'left');
        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN', 'left');
        $this->__get_daftar_pn_individual_two_join();
        $this->db->where('pj.IS_CALON', 0);
        $this->db->where('pj.IS_DELETED', 0);
        $this->db->where('pj.IS_CURRENT', 1);
        $this->db->where('pj.ID_STATUS_AKHIR_JABAT', 15);

        if ($this->role > 2)
            if ($this->session->userdata('INST_SATKERKD') !== null){
                $this->db->where('INTS.INST_SATKERKD', $this->session->userdata('INST_SATKERKD'));
            }
        //        $this->db->where('pj.IS_CURRENT', 1);

        if ($is_uk == 1)
            $this->db->where('JAB.UK_ID', $id_uk);


        if ($cari != NULL) {
            $nik_or_nama = " (NAMA LIKE '%" . $cari . "%' or NIK LIKE '%" . $cari . "%')";
            $this->db->where($nik_or_nama);
        }

        return $this->db->count_all_results();
    }
    function get_all_pn_active($ins) {
        $query = "SELECT
                `a`.`NIK` AS `NIK`,
                 a.ID_PN, u.ID_USER,
                 b.ID_PN AS ID_PN_DIJABATAN,
                 b.DESKRIPSI_JABATAN, UNIT_KERJA, SUB_UNIT_KERJA,
                `a`.`GELAR_DEPAN` AS `GELAR_DEPAN`,
                `a`.`NAMA` AS `NAMA`,
                `a`.`GELAR_BELAKANG` AS `GELAR_BELAKANG`,
                `f`.`INST_SATKERKD` AS `INST_SATKERKD`,
                `c`.`NAMA_JABATAN` AS `NAMA_JABATAN`,
                `d`.`SUK_NAMA` AS `SUK_NAMA`,
                `e`.`UK_NAMA` AS `UK_NAMA`,
                `f`.`INST_NAMA` AS `INST_NAMA`,
                `a`.`JNS_KEL` AS `JNS_KEL`,
                `a`.`TEMPAT_LAHIR` AS `TEMPAT_LAHIR`,
                `a`.`TGL_LAHIR` AS `TGL_LAHIR`,
                `a`.`NO_HP` AS `NO_HP`,
                `a`.`EMAIL` AS `EMAIL`,
                b.ID
                    FROM
                            (
                                    (
                                            (
                                                    (
                                                            (
                                                                    (`t_pn` `a` JOIN `t_pn_jabatan` `b` ON((`a`.`ID_PN` = `b`.`ID_PN`)))
                                                                    JOIN `m_jabatan` `c` ON ((`c`.`ID_JABATAN` = `b`.`ID_JABATAN`))
                                                            )
                                                            LEFT JOIN `m_sub_unit_kerja` `d` ON ((`d`.`SUK_ID` = `c`.`SUK_ID`))
                                                    )
                                                    LEFT JOIN `m_unit_kerja` `e` ON ((`e`.`UK_ID` = `c`.`UK_ID`))
                                            )
                                            LEFT JOIN `m_inst_satker` `f` ON ((`f`.`INST_SATKERKD` = `e`.`UK_LEMBAGA_ID`))
                                    )
                                    LEFT JOIN `t_user` `u` ON ((`u`.`USERNAME` = `a`.`NIK`))
                            )
            WHERE
                    1
            AND a.IS_ACTIVE = 1
            AND b.ID_STATUS_AKHIR_JABAT = 0
            AND b.IS_DELETED = 0
            AND b.IS_ACTIVE = 1
            AND b.IS_CURRENT = 1
            ORDER BY
	            `a`.`NIK`";


        if ($ins)
            $this->db->where('.LEMBAGA', $ins);


        $query = $this->db->query($query);
        return $query->result();
    }
    function get_daftar_wl_ctk($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {

        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');

        $result = [];
        $sql_select = " P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                . " P.NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                . " PJ.SUB_UNIT_KERJA, PJ.NAMA_LEMBAGA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                . " PJ.EMAIL_KANTOR, PJ.ESELON";

        $this->db->select($sql_select, FALSE);
        $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

        $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
                . " AND `PJ`.`is_wl` = 90 "
                . " AND `PJ`.`IS_DELETED` = '0' "
                . " AND `PJ`.`IS_CALON` = '0' "
                . " AND `PJ`.`IS_ACTIVE` = '1' "
                . " AND `PJ`.`IS_CURRENT` = '1' ";

        $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
        $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN");
        //            $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN", "left");

        $this->db->join("`m_sub_unit_kerja` `SUK`", "`SUK`.`SUK_ID` = `JAB`.`SUK_ID`", "left");
        $this->db->join("`m_unit_kerja` `UK`", "`UK`.`UK_ID` = `JAB`.`UK_ID`", "left");
        $this->db->join("`m_inst_satker` `INTS`", "`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`", "left");
        $this->db->group_by('p.ID_PN');
        $sql_where = "P.IS_ACTIVE = '1' and  `PJ`.`ID_STATUS_AKHIR_JABAT` IN (0,5)";
 
        if (!is_null($cari) && $cari != '') {
            $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari->text . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari->text . "' , '%') )";
        }

        if ($id_role != 1 && $id_role != 2) {
            if ($ins !== null){
                $sql_where .= " AND INTS.INST_SATKERKD = $ins";
            }
            if ($cari->unit_kerja != '') {
                $condition_uk = $cari->unit_kerja != '' ? "   uk.UK_ID ='" . $cari->unit_kerja . "' " : ( "  1  ");
                $sql_where .= " AND " . $condition_uk . "   ";
            }
            else{
                $sql_where .= " ";
            }
        }
        else{
            if ($cari->instansi != '') {
                $condition_instansi = $cari->instansi != '' ? "   INTS.INST_SATKERKD = '" . $cari->instansi . "' " : ("  1  ");
                $sql_where .= " AND " . $condition_instansi . "   ";
                if ($cari->unit_kerja != '') {
                    $condition_uk = $cari->unit_kerja != '' ? "   uk.UK_ID ='" . $cari->unit_kerja . "' " : ( "  1  ");
                    $sql_where .= " AND " . $condition_uk . "   ";
                }
            }
        }

        if ($is_uk == 1) {
            $sql_where .= " AND JAB.UK_ID = $id_uk";
        }

        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);


        if ($query) {
            $result = $query->result();
        }
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return (object) array("result" => $result);
    }
    function get_daftar_wl($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {
        $cari_advance = $this->input->get('CARI');
        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');
        $total_rows = $this->get_daftar_wajiblapor($page_mode, $cari,$cari_advance);
        $result = [];
        //$total_rows=1;
        //echo $total_rows;
        //if ($total_rows > 0) {
        $result = [];
        $sql_select = " P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                . " P.NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                . " PJ.SUB_UNIT_KERJA, PJ.NAMA_LEMBAGA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                . " PJ.EMAIL_KANTOR, PJ.ESELON";

        $this->db->select($sql_select, FALSE);
        $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

        $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
                . " AND `PJ`.`is_wl` = 90 "
                . " AND `PJ`.`IS_DELETED` = '0' "
                . " AND `PJ`.`IS_CALON` = '0' "
                . " AND `PJ`.`IS_ACTIVE` = '1' "
                . " AND `PJ`.`IS_CURRENT` = '1' ";

        $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
        $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN AND JAB.IS_ACTIVE <> 0");
        //            $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN", "left");

        $this->db->join("`m_sub_unit_kerja` `SUK`", "`SUK`.`SUK_ID` = `JAB`.`SUK_ID`", "left");
        $this->db->join("`m_unit_kerja` `UK`", "`UK`.`UK_ID` = `JAB`.`UK_ID`", "left");
        $this->db->join("`m_inst_satker` `INTS`", "`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`", "left");
        $this->db->group_by('p.ID_PN');
        $sql_where = "P.IS_ACTIVE = '1' and  `PJ`.`ID_STATUS_AKHIR_JABAT` IN (0,5)";

        if ($cari_advance) {
            $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
            $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
            $sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " $uk_where     ";
        }

        if (!is_null($cari) && $cari != '') {
            $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";
        }

        if ($id_role != 1 && $id_role != 2) {
            if ($ins !== null){
                $sql_where .= " AND INTS.INST_SATKERKD = $ins";
            }else{
                $sql_where .= " ";
            }
        }

        if ($is_uk == 1) {
            $sql_where .= " AND JAB.UK_ID = $id_uk";
        }

        
        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);

        //echo $this->db->last_query();

        if ($query) {
            //display($this->db->last_query());exit;
            $result = $query->result();
        }
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        //}

        return (object) array("total_rows" => $total_rows, "result" => $result);
    }
    function get_daftar_nwl_ctk($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {
        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');

        $result = [];
        $sql_select = "P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                . " P.NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                . " PJ.SUB_UNIT_KERJA, PJ.NAMA_LEMBAGA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                . " PJ.EMAIL_KANTOR, PJ.ESELON, PJ.ALASAN_NON_WL";

        $this->db->select($sql_select, FALSE);
        $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

        $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
                . " AND `PJ`.`is_wl` = 99 "
                . " AND `PJ`.`IS_DELETED` = '0' "
                . " AND `PJ`.`IS_CALON` = '0' "
                . " AND `PJ`.`IS_ACTIVE` = '1' "
                . " AND `PJ`.`IS_CURRENT` = '1' ";

        $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
        $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN");

        $this->db->join("`m_sub_unit_kerja` `SUK`", "`SUK`.`SUK_ID` = `JAB`.`SUK_ID`", "left");
        $this->db->join("`m_unit_kerja` `UK`", "`UK`.`UK_ID` = `JAB`.`UK_ID`", "left");
        $this->db->join("`m_inst_satker` `INTS`", "`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`", "left");

        $sql_where = "P.IS_ACTIVE = '1' and  `PJ`.`ID_STATUS_AKHIR_JABAT` IN (0,5)";

        if (!is_null($cari) && $cari != '') {
            $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari->text . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari->text . "' , '%') )";
        }

        if ($id_role != 1 && $id_role != 2) {
            if ($ins !== null){
                $sql_where .= " AND INTS.INST_SATKERKD = $ins";
            }
            if ($cari->unit_kerja != '') {
                $condition_uk = $cari->unit_kerja != '' ? "   uk.UK_ID ='" . $cari->unit_kerja . "' " : ( "  1  ");
                $sql_where .= " AND " . $condition_uk . "   ";
            }
            else{
                $sql_where .= " ";
            }
        }
        else{
            if ($cari->instansi != '') {
                $condition_instansi = $cari->instansi != '' ? "   INTS.INST_SATKERKD = '" . $cari->instansi . "' " : ("  1  ");
                $sql_where .= " AND " . $condition_instansi . "   ";
                if ($cari->unit_kerja != '') {
                    $condition_uk = $cari->unit_kerja != '' ? "   uk.UK_ID ='" . $cari->unit_kerja . "' " : ( "  1  ");
                    $sql_where .= " AND " . $condition_uk . "   ";
                }
            }
        }

        if ($is_uk == 1) {
            $sql_where .= " AND JAB.UK_ID = $id_uk";
        }


        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);


        if ($query) {
            $result = $query->result();
        }
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return (object) array("result" => $result);
        exit;
    }
    function get_daftar_nwl($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {
        $cari_advance = $this->input->get('CARI');
        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');
        // $total_rows = $this->get_total_row_individu($page_mode, $cari);
        $total_rows = $this->get_daftar_nonwajiblapor($page_mode, $cari,$cari_advance);

        $result = [];

        $result = [];
        $sql_select = "P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                . " P.NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                . " PJ.SUB_UNIT_KERJA, PJ.NAMA_LEMBAGA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                . " PJ.EMAIL_KANTOR, PJ.ESELON, PJ.ALASAN_NON_WL";

        $this->db->select($sql_select, FALSE);
        $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

        $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
            . " AND `PJ`.`is_wl` = 99 "
            . " AND `PJ`.`IS_DELETED` = '0' "
            // . " AND `PJ`.`IS_CALON` = '0' "
            . " AND `PJ`.`IS_ACTIVE` = '1' "
            . " AND `PJ`.`IS_CURRENT` = '1' ";

        $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
        $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN AND JAB.IS_ACTIVE <> 0");

        $this->db->join("`m_sub_unit_kerja` `SUK`", "`SUK`.`SUK_ID` = `JAB`.`SUK_ID`", "left");
        $this->db->join("`m_unit_kerja` `UK`", "`UK`.`UK_ID` = `JAB`.`UK_ID`", "left");
        $this->db->join("`m_inst_satker` `INTS`", "`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`", "left");

        $sql_where = "P.IS_ACTIVE = '1' and  `PJ`.`ID_STATUS_AKHIR_JABAT` IN (0,5)";

        if ($cari_advance) {
            $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
            $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
            $sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " $uk_where     ";
        }



        if (!is_null($cari) && $cari != '') {
            $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";
        }

        if ($id_role != 1 && $id_role != 2) {
            if ($ins !== null){
                $sql_where .= " AND INTS.INST_SATKERKD = $ins";
            }else{
                $sql_where .= " ";
            }
        }

        if ($is_uk == 1) {
            $sql_where .= " AND JAB.UK_ID = $id_uk";
        }


        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);
        //echo $this->db->last_query();

        if ($query) {
            //display($this->db->last_query());exit;
            $result = $query->result();
        }
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
            //$total_rows = $i - 1;
        }

        //}

        return (object) array("total_rows" => $total_rows, "result" => $result);
        exit;
    }
    function get_daftar_wajiblapor($ins, $cari = NULL, $cari_advance = NULL) { // wajiblapor
        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');
        $result = [];
        $sql_select = "P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                . " P.NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                . " PJ.SUB_UNIT_KERJA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                . " PJ.EMAIL_KANTOR, PJ.ESELON";

        $this->db->select($sql_select, FALSE);
        $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

        $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
                . " AND `PJ`.`is_wl` = 90 "
                . " AND `PJ`.`IS_DELETED` = '0' "
                . " AND `PJ`.`IS_CALON` = '0' "
                . " AND `PJ`.`IS_ACTIVE` = '1' "
                . " AND `PJ`.`IS_CURRENT` = '1' ";

        $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
        $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN");
        $this->db->join("`m_sub_unit_kerja` `SUK`", "`SUK`.`SUK_ID` = `JAB`.`SUK_ID`", "left");
        $this->db->join("`m_unit_kerja` `UK`", "`UK`.`UK_ID` = `JAB`.`UK_ID`", "left");
        $this->db->join("`m_inst_satker` `INTS`", "`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`", "left");
        $this->db->group_by('p.id_pn');
        $sql_where = "P.IS_ACTIVE = '1' and  `PJ`.`ID_STATUS_AKHIR_JABAT` IN (0,5)";



        if (!is_null($cari) && $cari != '') {
            $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";
        }

        if ($id_role != 1 && $id_role != 2) {
            if ($ins !== null){
                $sql_where .= " AND INTS.INST_SATKERKD = $ins";
            }else{
                $sql_where .= " ";
            }
        }

        if ($is_uk == 1) {
            $sql_where .= " AND JAB.UK_ID = $id_uk";
        }

        if ($cari_advance) {
            $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
            $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
            $sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " $uk_where     ";
        }


        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);
        $total_rows = $query->num_rows();
        //echo $this->db->last_query();

        if ($query) {
            $result = $query->result();
        }
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return $total_rows;
    }
    function get_daftar_nonwajiblapor($ins, $cari = NULL, $cari_advance = NULL) { // wajiblapor
        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');
        $result = [];
        $sql_select = "P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                . " P.NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                . " PJ.SUB_UNIT_KERJA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                . " PJ.EMAIL_KANTOR, PJ.ESELON";

        $this->db->select($sql_select, FALSE);
        $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

        $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
                . " AND `PJ`.`is_wl` = 99 "
                . " AND `PJ`.`IS_DELETED` = '0' "
                . " AND `PJ`.`IS_CALON` = '0' "
                . " AND `PJ`.`IS_ACTIVE` = '1' "
                . " AND `PJ`.`IS_CURRENT` = '1' ";

        $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
        $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN");

        $this->db->join("`m_sub_unit_kerja` `SUK`", "`SUK`.`SUK_ID` = `JAB`.`SUK_ID`", "left");
        $this->db->join("`m_unit_kerja` `UK`", "`UK`.`UK_ID` = `JAB`.`UK_ID`", "left");
        $this->db->join("`m_inst_satker` `INTS`", "`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`", "left");

        $sql_where = "P.IS_ACTIVE = '1' and  `PJ`.`ID_STATUS_AKHIR_JABAT` IN (0,5)";
        if (!is_null($cari) && $cari != '') {
            $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";
        }
        if ($id_role != 1 && $id_role != 2) {
            if ($ins !== null){
                $sql_where .= " AND INTS.INST_SATKERKD = $ins";
            }else{
                $sql_where .= " ";
            }
        }

        if ($is_uk == 1) {
            $sql_where .= " AND JAB.UK_ID = $id_uk";
        }

        if ($cari_advance) {
            $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
            $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
            $sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " $uk_where     ";
        }
        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);
        $total_rows = $query->num_rows();

        if ($query) {
            $result = $query->result();
        }
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return $total_rows;
        exit;
    }
    function get_daftar_Calonpn($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {

        $uk_where = $this->uker && !is_null($this->uker) ? "AND uk.UK_ID = $this->uker" : "AND 1";
        $sql_where = $this->get_daftar_pn_individual_two_cari_advance($uk_where, $cari, $page_mode);
        $this->db->where($sql_where);

        $total_rows = $this->get_total_row_individu($page_mode, $cari, '__get_daftar_pn_individual_two_join');
        //echo $total_rows;exit;
        if ($instansi) {
            $instansi == '' ? " 1 " : $instansi;
        }


        $result = [];
        if ($total_rows > 0) {
            $result = [];
            $sql_select = "DISTINCT " . $offset . " + 1 AS position, P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                    . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                    . " P.NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                    . " PJ.SUB_UNIT_KERJA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                    . " PJ.EMAIL_KANTOR, PJ.ESELON, P.IS_APLIKASI, PJ.TAHUN_WL";

            $this->db->select($sql_select, FALSE);
            $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

            $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
        //                    . "AND `PJ`.`ID_STATUS_AKHIR_JABAT` = 10 "
                    . "AND `PJ`.`IS_DELETED` = '0' "
                    . "AND `PJ`.`IS_CALON` = '1' "
                    . "AND `PJ`.`IS_ACTIVE` = '1' "
                    . "AND `PJ`.`IS_CURRENT` = '1' ";

            $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
            $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN");
            $this->__get_daftar_pn_individual_two_join();
            $this->db->where($sql_where);
            $query = $this->db->get_where('t_pn P', NULL, $rowperpage, $offset);
            //echo $this->db->last_query();exit;
            if ($query) {
                $result = $query->result();
            }
            if ($result) {
                $i = 1 + $offset;
                foreach ($result as $key => $item) {
                    $result[$key]->NO_URUT = $i;
                    $i++;
                }
            }
        //            }
        }
        //
        return (object) array("total_rows" => $total_rows, "result" => $result);
    }
    function get_daftar_jml_calonpn($ins, $cari = NULL, $cari_advance = NULL) { // wajiblapor
        $cari_advance = $this->input->get('CARI');
        $id_uk = $this->session->userdata('UK_ID');
        $ins = $this->session->userdata('INST_SATKERKD');
        $id_role = $this->session->userdata('ID_ROLE');
        $is_uk = $this->session->userdata('IS_UK');
        $result = [];
        $sql_select = "P.NIK,P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, "
                . " P.NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, "
                . " PJ.SUB_UNIT_KERJA, PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, "
                . " PJ.EMAIL_KANTOR, PJ.ESELON";

        $this->db->select($sql_select, FALSE);
        $this->db->join("T_USER U", "U.USERNAME = P.NIK", "left");

        $sql_where_join = "`P`.`ID_PN` = `PJ`.`ID_PN` "
                . " AND `PJ`.`IS_DELETED` = '0' "
                . " AND `PJ`.`IS_CALON` = '1' "
                . " AND `PJ`.`IS_ACTIVE` = '1' "
                . " AND `PJ`.`IS_CURRENT` = '1' "
                . " AND `PJ`.`ID_STATUS_AKHIR_JABAT` = '10' ";

        $this->db->join("T_PN_JABATAN PJ", $sql_where_join);
        $this->db->join("M_JABATAN JAB", "PJ.ID_JABATAN = JAB.ID_JABATAN");

        $this->db->join("`m_sub_unit_kerja` `SUK`", "`SUK`.`SUK_ID` = `JAB`.`SUK_ID`", "left");
        $this->db->join("`m_unit_kerja` `UK`", "`UK`.`UK_ID` = `JAB`.`UK_ID`", "left");
        $this->db->join("`m_inst_satker` `INTS`", "`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`", "left");

        $sql_where = "P.IS_ACTIVE = '1'";
        if (!is_null($cari) && $cari != '') {
            $sql_where .= "AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";
        }
        if ($id_role != 1 && $id_role != 2) {
            if ($ins !== null){
                $sql_where .= " AND INTS.INST_SATKERKD = $ins";
            }else{
                $sql_where .= " ";
            }
        }

        if ($is_uk == 1) {
            $sql_where .= " AND JAB.UK_ID = $id_uk";
        }
        if ($cari_advance) {
            $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
            $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
            $sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " $uk_where     ";
        }

        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);
        $total_rows = $query->num_rows();

        if ($query) {
            $result = $query->result();
        }
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return $total_rows;
        exit;
    }

    function get_jabatan_by_id($id_pn_jabatan) {
        $this->db->where('ID', $id_pn_jabatan);
        return $this->db->get($this->table_jabatan_pn)->row();
    }
}

?>