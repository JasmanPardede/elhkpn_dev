<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mlhkpnoffline extends CI_Model {

    private $table = 't_imp_xl_lhkpn';
    public $tipe_pelaporan = array();
    public $status_lhkpn = array();

    function __construct() {
        parent::__construct();
        $this->set_tipe_pelaporan();
        $this->set_status_pelaporan();
    }

    private function set_status_pelaporan() {
        $this->status_lhkpn[0] = '<h5><div align="center" style="background-color: lightblue;" >Draft</div></h5>';
        $this->status_lhkpn[1] = '<h5><div align="center" style="background-color: green; color: white;" >Terkirim</div></h5>';
        $this->status_lhkpn[2] = '<h5><div align="center" style="background-color: orange; color: white;">Perlu Perbaikan</div></h5>';
        $this->status_lhkpn[3] = '<h5><div align="center" style="background-color: #ff6600;" >Terverifikasi</div></h5>';
        $this->status_lhkpn[4] = '<h5><div align="center" style="background-color: blue; color: white;" >Diumumkan</div></h5>';
        $this->status_lhkpn[5] = '<h5><div align="center" style="background-color: Cyan;" >Terverifikasi Tidak Lengkap</div></h5>';
        $this->status_lhkpn[6] = '<h5><div align="center" style="background-color: Red; color: white;" >Diumumkan Tidak Lengkap</div></h5>';
        $this->status_lhkpn[7] = '<h5><div align="center" style="background-color: black; color: white;" >Ditolak</div></h5>';
        return;
    }

    function __count_data_harta($id_imp_xl_lhkpn) {
        $query_harta = "select(select count(*) from (SELECT
                            t_imp_xl_lhkpn_harta_tidak_bergerak.id_imp_xl_harta_tidak_bergerak
                          FROM
                            ".$this->table."
                            JOIN `t_imp_xl_lhkpn_harta_tidak_bergerak`
                              ON `t_imp_xl_lhkpn_harta_tidak_bergerak`.`id_imp_xl_lhkpn` = ".$this->table.".`id_imp_xl_lhkpn`
                              WHERE ".$this->table.".`id_imp_xl_lhkpn` = '" . $id_imp_xl_lhkpn . "'
                              union
                          SELECT
                            t_imp_xl_lhkpn_harta_bergerak.`id_imp_xl_lhkpn`
                          FROM
                            ".$this->table."    
                            JOIN `t_imp_xl_lhkpn_harta_bergerak`
                              ON `t_imp_xl_lhkpn_harta_bergerak`.`id_imp_xl_lhkpn` = ".$this->table.".`id_imp_xl_lhkpn`
                              WHERE ".$this->table.".`id_imp_xl_lhkpn` = '" . $id_imp_xl_lhkpn . "'
                              UNION
                          SELECT
                            t_imp_xl_lhkpn_harta_bergerak_lain.`id_imp_xl_lhkpn`
                          FROM
                            ".$this->table."    
                            JOIN `t_imp_xl_lhkpn_harta_bergerak_lain`
                              ON `t_imp_xl_lhkpn_harta_bergerak_lain`.`id_imp_xl_lhkpn` = ".$this->table.".`id_imp_xl_lhkpn`
                              WHERE ".$this->table.".`id_imp_xl_lhkpn` = '" . $id_imp_xl_lhkpn . "'
                              UNION
                          SELECT
                            t_imp_xl_lhkpn_harta_surat_berharga.`id_imp_xl_lhkpn`
                          FROM
                            ".$this->table."    
                            JOIN `t_imp_xl_lhkpn_harta_surat_berharga`
                              ON `t_imp_xl_lhkpn_harta_surat_berharga`.`id_imp_xl_lhkpn` = ".$this->table.".`id_imp_xl_lhkpn`
                              WHERE ".$this->table.".`id_imp_xl_lhkpn` = '" . $id_imp_xl_lhkpn . "'
                              UNION
                          SELECT
                            t_imp_xl_lhkpn_harta_kas.`id_imp_xl_lhkpn`
                          FROM
                            ".$this->table."    
                            JOIN `t_imp_xl_lhkpn_harta_kas`
                              ON `t_imp_xl_lhkpn_harta_kas`.`id_imp_xl_lhkpn` = ".$this->table.".`id_imp_xl_lhkpn`
                              WHERE ".$this->table.".`id_imp_xl_lhkpn` = '" . $id_imp_xl_lhkpn . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_lainnya.`id_imp_xl_lhkpn`
                          FROM
                            ".$this->table."    
                            JOIN `t_imp_xl_lhkpn_harta_lainnya`
                              ON `t_imp_xl_lhkpn_harta_lainnya`.`id_imp_xl_lhkpn` = ".$this->table.".`id_imp_xl_lhkpn`
                              WHERE ".$this->table.".`id_imp_xl_lhkpn` = '" . $id_imp_xl_lhkpn . "'
                              UNION
                           SELECT
                            t_imp_xl_lhkpn_hutang.`id_imp_xl_lhkpn`
                          FROM
                            ".$this->table."   
                            JOIN `t_imp_xl_lhkpn_hutang`
                              ON `t_imp_xl_lhkpn_hutang`.`id_imp_xl_lhkpn` = ".$this->table.".`id_imp_xl_lhkpn`
                          WHERE ".$this->table.".`id_imp_xl_lhkpn` = '" . $id_imp_xl_lhkpn . "') as co_harta) as c_harta";
        $count_harta = $this->db->query($query_harta)->row();
        return $count_harta->c_harta;
    }

    /**
     * BELUM DIEDIT
     * @return string
     */
    
    function data_lhkpn() {
        $c_dt_harta = $this->__count_data_harta($ID_LHKPN);
        $data = array();
        $data[1] = array(
            'index' => '1',
            'table' => 't_lhkpn_data_pribadi',
            'notif' => 'Data Pribadi belum diisi, Apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'view' => '1',
            'grid' => null,
            'ctr' => 0
        );
        $data[2] = array(
            'index' => '2',
            'table' => 't_lhkpn_jabatan',
            'notif' => 'Data Jabatan belum diisi, Apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'view' => '2',
            'grid' => null,
            'ctr' => 0
        );
        $data[3] = array(
            'index' => '3',
            'table' => 't_lhkpn_keluarga',
            'notif' => 'Data Keluarga belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'view' => '3',
            'grid' => null,
            'ctr' => 0,
            'title' => 'Data Keluarga',
        );

        // HARTA
        $data[4] = array(
            'index' => '4',
            'table' => 't_lhkpn_harta_tidak_bergerak',
            'notif' => 'Data Harta Tanah / Bangunan belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Tanah / Bangunan yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '4',
            'grid' => '#harta_tidak_bergerak',
            'ctr' => 0,
            'title' => 'Data Harta Harta Tanah / Bangunan',
            'total_harta' => $c_dt_harta
        );

        $data[5] = array(
            'index' => '5',
            'table' => 't_lhkpn_harta_bergerak',
            'notif' => 'Data Harta Alat Transportasi / Mesin belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta Alat Transportasi / Mesin yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '4',
            'grid' => '#harta_bergerak',
            'ctr' => 0,
            'title' => 'Data Harta Alat Transportasi / Mesin',
            'total_harta' => $c_dt_harta
        );

        $data[6] = array(
            'index' => '6',
            'table' => 't_lhkpn_harta_bergerak_lain',
            'notif' => 'Data Harta Bergerak Lainnya belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta Bergerak Lainnya yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '4',
            'grid' => '#harta_lain',
            'ctr' => 0,
            'title' => 'Data Harta Bergerak Lainnya',
            'total_harta' => $c_dt_harta
        );

        $data[7] = array(
            'index' => '7',
            'table' => 't_lhkpn_harta_surat_berharga',
            'notif' => 'Data Surat Berharga belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta Surat Berharga yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '4',
            'grid' => '#surat_berharga',
            'ctr' => 0,
            'title' => 'Data Harta Surat Berharga',
            'total_harta' => $c_dt_harta
        );

        $data[8] = array(
            'index' => '8',
            'table' => 't_lhkpn_harta_kas',
            'notif' => 'Data KAS/Setara KAS belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta KAS/Setara KAS yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '4',
            'grid' => '#kas',
            'ctr' => 0,
            'title' => 'Data Harta Kas',
            'total_harta' => $c_dt_harta
        );

        $data[9] = array(
            'index' => '9',
            'table' => 't_lhkpn_harta_lainnya',
            'notif' => 'Data Harta Lainnya belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta Lainnya yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '4',
            'grid' => '#lain',
            'ctr' => 0,
            'title' => 'Data Harta Lainnya',
            'total_harta' => $c_dt_harta
        );

        $data[10] = array(
            'index' => '10',
            'table' => 't_lhkpn_hutang',
            'notif' => 'Data Hutang belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
//            'notif_status' => 'Di temukan data Hutang yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '4',
            'grid' => '#hutang',
            'ctr' => 0,
            'title' => 'Data Hutang',
            'total_harta' => $c_dt_harta
        );

        // END OF HARTA


        $data[11] = array(
            'index' => '11',
            'table' => 't_lhkpn_penerimaan_kas2',
//            'notif' => 'Data Penerimaan belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif' => 'Data Penerimaan belum diisi.',
            'view' => '5',
            'grid' => null,
            'ctr' => 0
        );
        $data[12] = array(
            'index' => '12',
            'table' => 't_lhkpn_pengeluaran_kas2',
//            'notif' => 'Data Pengeluaran belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif' => 'Data Pengeluaran belum diisi.',
            'view' => '6',
            'grid' => null,
            'ctr' => 0
        );
        $data[13] = array(
            'index' => '13',
            'table' => 't_lhkpn_fasilitas',
            'notif' => 'Data Fasilitas belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'view' => '7',
            'grid' => null,
            'ctr' => 0,
            'title' => 'Data Fasilitas'
        );
        return $data;
    }

    function info_harta($index) {
        $data = array();
        $data[4] = 'Data Harta Harta Tanah / Bangunan';
        $data[5] = 'Data Harta Alat Transportasi / Mesin';
        $data[6] = 'Data Harta Bergerak Lainnya';
        $data[7] = 'Data Harta Surat Berharga';
        $data[8] = 'Data Harta Kas';
        $data[9] = 'Data Harta Lainnya';
        return $data[$index];
    }

    private function set_tipe_pelaporan() {
        $this->tipe_pelaporan[1] = 'Khusus, Calon PN';
        $this->tipe_pelaporan[2] = 'Khusus, Awal Menjabat';
        $this->tipe_pelaporan[3] = 'Khusus, Akhir Menjabat';
        $this->tipe_pelaporan[4] = 'Periodik';
        return;
    }

    public function get_status_pelaporan($index) {
        return $this->status_lhkpn[$index];
    }

    public function get_type_pelaporan($index) {
        return $this->tipe_pelaporan[$index];
    }

    private function __set_where_check_data_review_harta_lama($id_lhkpn = FALSE, $is_check_data = FALSE) {
        $sql_where = ' 1=0 ';
        if ($id_lhkpn && !$is_check_data) {
            $sql_where = " "
                    . " ID_LHKPN = '" . $id_lhkpn . "' AND "
                    . " ( "
//                    . "     ( "
                    . "         IS_LOAD = '1' AND "
                    . "         IS_CHECKED = '0' "
//                    . "     ) "
//                    . "     OR ID_HARTA IS NULL "
                    . " ) ";
        }

        if ($id_lhkpn && $is_check_data) {
            $sql_where = " ID_LHKPN = '" . $id_lhkpn . "' ";
        }

        $this->db->where($sql_where, NULL, FALSE);
        return;
    }

    public function check_data_review_harta($table_name = FALSE, $id_lhkpn = FALSE, $is_check_data = FALSE) {
        $check_pilih = 0;
        if ($table_name && $id_lhkpn) {

            $this->__set_where_check_data_review_harta_lama($id_lhkpn, $is_check_data);
            $check_pilih = $this->db->count_all_results($table_name);
        }
        return $check_pilih;
    }

    function list_all() {
        $this->db->order_by('ID_LHKPN', 'asc');
        return $this->db->get($this->table);
    }

    function select_for_get_table_filling() {
        $sql_select = "t_lhkpn.`tgl_lapor`, "
                . "t_lhkpn.`JENIS_LAPORAN`, "
                . "t_lhkpn.`STATUS`, "
                . "t_lhkpn.`id_imp_xl_lhkpn`,"
                . "t_pn.`NAMA`";
        $this->db->select($sql_select, FALSE);
        return;
    }

    function configure_select_for_get_table_filling() {

        $this->db->order_by('TGL_LAPOR', 'DESC');
        $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN');
        $this->db->join('t_user', 't_user.USERNAME = t_pn.NIK');
        $this->db->where('t_user.ID_USER', $this->session->userdata('ID_USER'));
        $this->db->where('t_lhkpn.IS_ACTIVE', '1');
        return;
    }

    function count_select_draft_for_get_table_filling() {
        $this->db->where("t_lhkpn.STATUS  = '0' ");
        return $this->count_select_for_get_table_filling();
    }

    function count_select_for_get_table_filling() {
        $this->configure_select_for_get_table_filling();
        //$this->db->select('`ID`,`ID_LHKPN`,`ID_JABATAN`,`DESKRIPSI_JABATAN`,`IS_PRIMARY`,STATUS');
        $this->db->from('t_lhkpn');

        return $this->db->count_all_results();
    }

    function get_jabatan_all($id_lhkpn) {
        $sql = "SELECT ID,`ID_LHKPN`,`ID_JABATAN`,`DESKRIPSI_JABATAN`,`IS_PRIMARY`
				FROM `t_lhkpn_jabatan`
				WHERE `t_lhkpn_jabatan`.`id_imp_xl_lhkpn` = '" . $id_lhkpn . "'
				ORDER BY `t_lhkpn_jabatan`.`ID` DESC";

        $query = $this->db->query($sql);
        $response = FALSE;
        if ($query) {
            $response = $query->result();
        }

//        clean_mysqli_connection($this->db->conn_id);
//        $query->free_result();
        return $response;

        /**
          $this->db->where('t_lhkpn_jabatan.ID_LHKPN', $id_lhkpn);
          $this->db->order_by('t_lhkpn_jabatan.ID', 'DESC');
          $data = $this->db->get('t_lhkpn_jabatan')->result();
         * */
        //echo $this->db->last_query();exit;
//        return $data;
    }

    function get_table_filling($offset = 0, $limit = 10, $id_user = FALSE) {
        $result = [];
        $total_rows = 0;

//        $this->db->limit($limit, $offset);
//        $this->db->distinct();
//        $this->db->group_by('t_lhkpn.ID_LHKPN');
//        $this->select_for_get_table_filling();
//        $this->configure_select_for_get_table_filling();
//        $query = $this->db->get('t_lhkpn');

        if (!$id_user) {
            $id_user = $this->session->userdata('ID_USER');
        }

        $query = $this->db->query("call `fu_select_t_lhkpn_by_id_user`(" . $limit . "," . $offset . ",'" . $id_user . "')");
        $this->db->close();
        $this->db->initialize();

        $total_rows = 0;
//        $total_rows = $this->count_select_for_get_table_filling();
        if ($query) {
            $result = $query->result();

            if ($result) {



                $i = 1 + $offset;
                foreach ($result as $key => $record) {
                    $result[$key]->NO_URUT = $i;
                    $result[$key]->TGL_LAPOR_ORI = $record->tgl_lapor;
                    $result[$key]->TGL_LAPOR = string_to_date($record->tgl_lapor, 'd/m/Y');
                    $result[$key]->TIPE_PELAPORAN = $this->get_type_pelaporan($record->JENIS_LAPORAN);
                    $result[$key]->STATUS_LHKPN = $this->get_status_pelaporan($record->STATUS);
                    $result[$key]->ALL_JABATAN = $this->get_jabatan_all($record->ID_LHKPN);
                    $i++;

                    $total_rows = $record->rowcount;
                }
            }
        }



        return (object) array("total_rows" => $total_rows, "result" => $result);
    }

    function count_all($filter = '') {
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


        $role = $this->session->userdata('ID_ROLE');
        if ($this->session->userdata('IS_PN')) {
            $nik = $this->session->userdata('NIK');

            $sql = "SELECT ID_PN FROM T_PN WHERE NIK = $nik";
            $data = $this->db->query($sql)->result()[0];
            $this->db->where(['T_LHKPN.ID_PN' => $data->ID_PN]);
        }
        $this->db->order_by('ID_LHKPN', 'asc');
        $this->db->join('T_PN', 'T_PN.ID_PN=T_LHKPN.ID_PN');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_tahun_akhir_lapor($id_lhkpn = FALSE, $id_pn = FALSE) {
        if ($id_lhkpn & $id_pn) {
            
        }
        return FALSE;
    }

    function get_detail_by_pn($id_lhkpn, $id_pn) {
        $this->db->where('ID_PN', $id_pn);
        $query = $this->get_by_id($id_lhkpn);
        if ($query) {
            return $query->row();
        }
        return FALSE;
    }

    private function __sculpt_limit($limit_offset = FALSE) {
        if ($limit_offset !== FALSE) {
            if (is_array($limit_offset)) {
                if (array_key_exists("limit", $limit_offset) && array_key_exists("offset", $limit_offset)) {
                    $this->db->limit($limit_offset["limit"], $limit_offset["offset"]);
                } elseif (count($limit_offset) == 2) {
                    $this->db->limit($limit_offset[0], $limit_offset[1]);
                }
            } elseif (is_numeric($limit_offset)) {
                $this->db->limit($limit_offset);
            }
        }
        return;
    }

    private function __sculpt_order($order_by = FALSE) {
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        return;
    }

    /**
     * 
     * @param numeric $id_pn required
     * @param numeric $jenis_laporan default 4
     * @param array $additional_condition
     * @param array|numeric $limit_offset
     * @param bool $response_as_row
     * @return mix
     */
    function get_lhkpn_pn_by_id_pn($id_pn, $jenis_laporan = 4, $additional_condition = FALSE, $limit_offset = FALSE, $order_by = FALSE, $response_as_row = FALSE) {
        $this->db->where('ID_PN', $id_pn);

        if ($additional_condition) {
            $this->db->where($additional_condition);
        }
        $this->db->where('IS_ACTIVE', 1);
        if ($jenis_laporan && $jenis_laporan !== FALSE) {
            $this->db->where('JENIS_LAPORAN', $jenis_laporan);
        }

        $this->__sculpt_limit($limit_offset);
        $this->__sculpt_order($order_by);

        $query = $this->get_by_id();

        if ($response_as_row) {
            return $query->row();
        }

        return $query->result();
    }

    function get_lhkpn_pn($id_lhkpn = FALSE, $id_pn = FALSE) {
        if ($id_lhkpn && $id_pn) {
            $this->db->where('ID_PN', $id_pn);
            $this->db->where('ID_LHKPN !=', $id_lhkpn);
            $this->db->where('IS_ACTIVE', '1');
            $this->db->where('STATUS !=', '0');
            $this->db->order_by('ID_LHKPN', 'DESC');
            $this->__sculpt_limit(1);
            $q = $this->db->get($this->table);
            if ($q) {
                return $q->row();
            }
        }
        return FALSE;
    }

    function get_by_id($id = FALSE) {
        if ($id) {
            $this->db->where('ID_LHKPN', $id);
        }
        return $this->db->get($this->table);
    }

    function cetak_surat_kuasa_time_by_pn($id_pn = FALSE) {
        if ($id_pn) {
            $this->db->where('ID_PN', $id_pn);
            $this->__sculpt_limit(1);
            $this->db->order_by('CETAK_SURAT_KUASA_TIME', 'DESC');
            $q = $this->db->get('t_lhkpn');
            if ($q) {
                return $q->row();
            }
        }
        return FALSE;
    }

    function save($lhkpn) {
        $this->db->insert($this->table, $lhkpn);
        return $this->db->insert_id();
    }

    function update($id, $lhkpn) {
        $this->db->where('ID_LHKPN', $id);
        $this->db->update($this->table, $lhkpn);
    }

    function delete($id) {
        $this->db->where('ID_LHKPN', $id);
        $this->db->delete($this->table);
    }

    function summaryHarta($idLhkpn, $tabel, $field, $as_field) {
        $sql = "SELECT SUM($field) as $as_field FROM $tabel WHERE IS_ACTIVE = '1' AND ID_LHKPN = " . $this->db->escape($idLhkpn);
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function summaryHartaDashboard($nik, $thn, $tabel, $field, $as_field) {
        //SELECT SUM(NILAI_PELAPORAN) as sum_hartirak FROM T_LHKPN_HARTA_TIDAK_BERGERAK join T_LHKPN on T_LHKPN_HARTA_TIDAK_BERGERAK.ID_LHKPN=T_LHKPN.ID_LHKPN join T_PN on T_LHKPN.ID_PN=T_PN.ID_PN WHERE T_LHKPN_HARTA_TIDAK_BERGERAK.IS_ACTIVE = '1' AND YEAR(TGL_LAPOR)='2010' AND T_PN.NIK = '222333'
        // $sql = "SELECT SUM($field) as $as_field FROM $tabel join T_LHKPN on $tabel.ID_LHKPN=T_LHKPN.ID_LHKPN join T_PN on T_LHKPN.ID_PN=T_PN.ID_PN WHERE $tabel.IS_ACTIVE = '1' AND T_LHKPN.STATUS='3' AND YEAR(TGL_LAPOR)='$thn' AND T_PN.NIK = ".$this->db->escape($nik);
        $sql = "SELECT SUM($field) as $as_field FROM $tabel join T_LHKPN on $tabel.ID_LHKPN=T_LHKPN.ID_LHKPN join T_PN on T_LHKPN.ID_PN=T_PN.ID_PN WHERE $tabel.IS_ACTIVE = '1' AND YEAR(TGL_LAPOR)='$thn' AND T_PN.NIK = " . $this->db->escape($nik);
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function summaryHartaReport($nik, $thn, $jenis, $tabel, $field, $as_field) {
        //SELECT SUM(NILAI_PELAPORAN) as sum_hartirak FROM T_LHKPN_HARTA_TIDAK_BERGERAK join T_LHKPN on T_LHKPN_HARTA_TIDAK_BERGERAK.ID_LHKPN=T_LHKPN.ID_LHKPN join T_PN on T_LHKPN.ID_PN=T_PN.ID_PN WHERE T_LHKPN_HARTA_TIDAK_BERGERAK.IS_ACTIVE = '1' AND YEAR(TGL_LAPOR)='2010' AND T_PN.NIK = '222333'
        if ($jenis != '') {
            $sql = "SELECT SUM($field) as $as_field FROM $tabel join T_LHKPN on $tabel.ID_LHKPN=T_LHKPN.ID_LHKPN join T_PN on T_LHKPN.ID_PN=T_PN.ID_PN WHERE $tabel.IS_ACTIVE = '1' AND YEAR(TGL_LAPOR)='$thn' AND T_LHKPN.STATUS='3' AND JENIS_LAPORAN ='$jenis' AND T_PN.NIK = " . $this->db->escape($nik);
        } else {
            $sql = "SELECT SUM($field) as $as_field FROM $tabel join T_LHKPN on $tabel.ID_LHKPN=T_LHKPN.ID_LHKPN join T_PN on T_LHKPN.ID_PN=T_PN.ID_PN WHERE $tabel.IS_ACTIVE = '1' AND YEAR(TGL_LAPOR)='$thn' AND T_LHKPN.STATUS='3' AND T_PN.NIK = " . $this->db->escape($nik);
        }
//		echo $sql;
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function get_by_id_pn($id_pn) {
        $this->db->where('ID_PN', $id_pn);
        $this->db->where('IS_ACTIVE', '1');
        $this->__sculpt_limit(2);
        $this->db->order_by('TGL_LAPOR', 'DESC');
        $query = $this->db->get('t_lhkpn');
        if ($query) {
            return $query->result();
        }
        return [];
    }

    function getGol($tabel, $field) {
        $sql = "SELECT $field FROM $tabel";
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function getFieldWhere($tabel, $field, $where) {
        $sql = "SELECT $field FROM $tabel $where";
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function getRepekas($id_lhkpn, $tabel1, $tabel2, $tabel3, $field_sum, $field, $type) {
        if ($type == '1') {
            $join = " 
                LEFT JOIN " . $tabel2 . " b ON a.ID_JENIS_PENERIMAAN_KAS = b.ID_JENIS_PENERIMAAN_KAS
                LEFT JOIN " . $tabel3 . " c ON b.GOLONGAN = c.ID_GOLONGAN_PENERIMAAN_KAS 
            ";
        } else {
            $join = " 
                LEFT JOIN " . $tabel2 . " b ON a.ID_JENIS_PENGELUARAN_KAS = b.ID_JENIS_PENGELUARAN_KAS
                LEFT JOIN " . $tabel3 . " c ON b.GOLONGAN = c.ID_GOLONGAN_PENGELUARAN_KAS 
            ";
        }
        $sql = "SELECT
                    SUM($field_sum) AS summary
                FROM
                    $tabel1 a
                    $join
                WHERE
                    c.NAMA_GOLONGAN = '$field'
                AND a.IS_ACTIVE = '1' 
                AND a.ID_LHKPN = " . $this->db->escape($id_lhkpn);
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function getInpekas($id_lhkpn, $tabel1, $tabel2, $tabel3, $field, $type) {
        if ($type == '1') {
            $join = " 
                LEFT JOIN " . $tabel3 . " c ON b.GOLONGAN = c.ID_GOLONGAN_PENERIMAAN_KAS 
            ";

            $ext = "
                b.ID_JENIS_PENERIMAAN_KAS, 
                b.GOLONGAN,
                b.NAMA,
                c.ID_GOLONGAN_PENERIMAAN_KAS,
                c.NAMA_GOLONGAN
            ";
        } else {
            $join = " 
                LEFT JOIN " . $tabel3 . " c ON b.GOLONGAN = c.ID_GOLONGAN_PENGELUARAN_KAS 
            ";

            $ext = "
                b.ID_JENIS_PENGELUARAN_KAS, 
                b.GOLONGAN,
                b.NAMA,
                c.ID_GOLONGAN_PENGELUARAN_KAS,
                c.NAMA_GOLONGAN
            ";
        }

        $sql = "SELECT 
                    $ext
                FROM 
                    $tabel2 b
                    $join
                WHERE 
                    c.NAMA_GOLONGAN = '$field'
        ";
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function getValue($tabel, $where) {
        $sql = "SELECT * FROM $tabel $where";
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function getData($table, $ID) {
        $sql = "SELECT ID_LHKPN FROM $table WHERE ID_LHKPN = '$ID'";
        $act = $this->db->query($sql)->result();
        return $act;
    }

    function insertPK($table, $param) {
        $this->db->insert($table, $param);
        return $this->db->insert_id();
    }

    function delData($table, $id) {
        $this->db->where('ID_LHKPN', $id);
        $this->db->delete($table);
    }

    function dataTahun($NIK) {
        $query = $this->db->query("select distinct(year(TGL_LAPOR)) as tahun from T_LHKPN t join T_PN p on t.ID_PN=p.ID_PN where NIK=" . $this->db->escape($NIK) . " AND STATUS != '0' order by tahun desc");
        return $query->result();
    }

    /**
     * 
     * 
     * E_VERIFICATION
     * 
     * 
     */

    /**
     * 
     * @param type $usr
     * @param type $cari
     * @param type $limit
     * @param type $offset
     * @return type
     */
    function build_query_list_verifikasi_lhkpn($cari, $limit, $offset = 0, $penugasan) {
        $my_where = [];
        $my_where_find = [];

        $my_select_string = [];
        $my_select_string[] = " *,T_LHKPN.ID_LHKPN";
//        $my_select_string[] = " GROUP_CONCAT(
//        CONCAT(
//          REPEAT(
//            '0',
//            5- LENGTH(T_LHKPN_JABATAN.ID_JABATAN)
//          ),
//          T_LHKPN_JABATAN.ID_JABATAN
//        )
//      ) JABATAN ";
//        $my_select_string[] = " GROUP_CONCAT(
//        CONCAT(
//          IFNULL(T_LHKPN_JABATAN.ID, ''),
//          ':58:',
//          IFNULL(
//            T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,
//            ''
//          ),
//          ':58:',
//          IFNULL(T_STATUS_AKHIR_JABAT.STATUS, ''),
//          ':58:',
//          IFNULL(T_LHKPN_JABATAN.LEMBAGA, ''),
//          ':58:',
//          IFNULL(M_JABATAN.NAMA_JABATAN, ''),
//          ' ',
//          IFNULL(
//            T_LHKPN_JABATAN.DESKRIPSI_JABATAN,
//            ''
//          ),
//          ' - ',
//          IFNULL(M_UNIT_KERJA.UK_NAMA, ''),
//          ' - ',
//          IFNULL(M_INST_SATKER.INST_AKRONIM, ''),
//          ':58:',
//          IFNULL(T_LHKPN_JABATAN.IS_PRIMARY, '')
//        ) SEPARATOR '|'
//      ) AS NAMA_JABATAN ";

        $my_from = " FROM T_LHKPN ";
//        $this->db->from('T_LHKPN');

        $my_join = "LEFT JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_LHKPN = `T_LHKPN`.ID_LHKPN AND T_LHKPN_JABATAN.`IS_PRIMARY` = '1'
                    LEFT JOIN T_LHKPN_DATA_PRIBADI ON T_LHKPN_DATA_PRIBADI.ID_LHKPN = `T_LHKPN`.ID_LHKPN
                    LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                    LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA
                    LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA

                    LEFT JOIN `T_VERIFICATION`
                      ON `T_LHKPN`.`id_imp_xl_lhkpn` = `T_VERIFICATION`.`id_imp_xl_lhkpn`
                    JOIN `T_PN`
                      ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN`
                    LEFT JOIN `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`
                      ON `T_LHKPN`.`id_imp_xl_lhkpn` = `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`.`id_imp_xl_lhkpn`";

        if ($penugasan == TRUE) {
            $my_where[] = "T_LHKPN.STATUS NOT IN ('0', '4', '3')";
        } else {
            $my_where[] = "T_LHKPN.STATUS NOT IN ('0', '4')";
        }

        $my_where[] = "T_LHKPN.IS_ACTIVE = '1'";

        if (@$cari['TAHUN']) {
            $my_where[] = "YEAR(TGL_LAPOR) = '" . $cari['TAHUN'] . "'";
        }

        if (@$cari['LEMBAGA']) {
            $my_where[] = "INST_NAMA LIKE '%" . $cari['LEMBAGA'] . "%'";
        }

        if (@$cari['NAMA']) {
            $my_where[] = "T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%'";
        }

        return [$my_where, $my_where_find, $my_select_string, $my_from, $my_join];
    }

    function list_penugasan_verifikasi_lhkpn($cari, $limit, $offset = 0) {

        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_verifikasi_lhkpn($cari, $limit, $offset, TRUE);


        if (@$cari['PETUGAS']) {
            if ($cari['PETUGAS'] == '') {
                $my_where_find[] = " T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.USERNAME IS NULL ";
            } else {
                $my_where_find[] = " T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.USERNAME ='" . $cari['PETUGAS'] . "' ";
            }
        }

        if (@$cari['STAT']) {
            if ($cari['STAT'] == 1) {

                $my_where_find[] = " T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.STAT IS NULL ";
//                $my_where_find[] = " T_LHKPN.STATUS IN ('1', '2') ";
            } else if ($cari['STAT'] == 2) {

                $my_where_find[] = " T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.STAT = '" . $cari['STAT'] . "' ";
//                $my_where_find[] = " T_LHKPN.STATUS IN ('1', '2') ";
            } else if ($this->CARI['STAT'] == 3) {
                $my_where_find[] = " T_LHKPN.STATUS IN ('3', '5') ";
            }
        } else {
            $my_where_find[] = " T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.STAT IS NULL ";
//            $my_where_find[] = " T_LHKPN.STATUS IN ('1', '2') ";
        }
        $order_by = " T_LHKPN.tgl_kirim_final asc ";
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by);

//        echo $sql;exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
    }

    function build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by) {
        $string_where = implode(" AND ", $my_where);
        $string_where_find = implode(" OR ", $my_where_find);
        $compiled_string_where = trim($string_where) != "" ? $string_where : ' 1=1 ';
        if ($string_where_find != "") {
            $compiled_string_where = $compiled_string_where . " AND (" . $string_where_find . ")";
        }

        $string_select = implode(", ", $my_select_string);

        $offset = $offset !== NULL && $offset != FALSE && $offset != "" ? $offset : 0;

        $sql = " SELECT " . $string_select . " " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where . " ORDER BY  " . $order_by . " LIMIT " . $offset . ", " . $limit;

        $sql_count = " SELECT count(T_LHKPN.ID_LHKPN) total_row " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where;

        return [$sql, $sql_count];
    }

    function execute_list_verifikasi_lhkpn_query($sql, $sql_count) {
        $this->db->close();
        $this->db->initialize();

        $q_count = $this->db->query($sql_count);

        $this->db->close();
        $this->db->initialize();

        $total_rows = 0;

        if ($q_count) {
            $rcount = $q_count->row();
            if ($rcount) {
                $total_rows = $rcount->total_row;
            }
        }

        $q = $this->db->query($sql);

        $result_set = FALSE;
        if ($q) {
            $result_set = $q->result();
        }

        $this->db->close();
        $this->db->initialize();

        return [$result_set, $total_rows];
    }

    function list_verifikasi_lhkpn($usr, $cari, $limit, $offset = 0) {


        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_verifikasi_lhkpn($cari, $limit, $offset, FALSE);



        if (@$cari['JENIS']) {
            $my_where_find = "T_LHKPN.JENIS_LAPORAN = '" . $cari['JENIS'] . "'";
        }

        if (@$cari['VIA'] != '') {
            $my_where_find = "T_LHKPN.ENTRY_VIA = '" . $cari['VIA'] . "'";
        }

        $status = '1';
        if (@$cari['STATUS'] != '') {
            $status = $cari['STATUS'];
        }

        $my_where[] = "(T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.USERNAME = '" . $usr . "' OR T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.UPDATED_BY = '" . $usr . "')";
        $my_where[] = "T_LHKPN.STATUS = '" . $status . "'";


        $order_by = " T_PN.NAMA asc ";
        $order_by = " T_LHKPN.tgl_kirim_final asc ";
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by);

//        echo $sql;
//        exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
//        $this->db->order_by('T_PN.NAMA', 'asc');
    }

}
