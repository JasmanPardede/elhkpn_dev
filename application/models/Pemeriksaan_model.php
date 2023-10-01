<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Pemeriksaan
 *
 * @author Ferry Ricardo Siagian - Komisi Pemberantasan Korupsi
 * @package Models
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pemeriksaan_model extends CI_Model {

    private $table = 'T_LHKPN';
    public $tipe_pelaporan = array();
    public $status_lhkpn = array();
	public $DIR_TEMP_UPLOAD = './uploads/lhkpn_import_excel/temp/';
    public $DIR_TEMP_SKM_UPLOAD = './uploads/lhkpn_import_excel/temp/skm/';
    public $DIR_TEMP_SKUASA_UPLOAD = './uploads/lhkpn_import_excel/temp/sk/';
    public $DIR_TEMP_IKHTISAR_UPLOAD = './uploads/lhkpn_import_excel/temp/ikhtisar/';
    public $DIR_IMP_UPLOAD = './uploads/lhkpn_import_excel/final/';
    public $DIR_SKM_UPLOAD = './uploads/data_skm/';
    public $DIR_SKUASA_UPLOAD = './uploads/data_sk/';
    public $DIR_IKHTISAR_UPLOAD = './uploads/ikhtisar/';
    //private $status_lhkpn = '0';
    private $id_pn = FALSE;

    function __construct() {
        parent::__construct();
        $this->set_tipe_pelaporan();
        $this->set_status_pelaporan();
		$this->config->load('harta');
    }

    private function set_status_pelaporan() {
        $this->status_lhkpn[0] = '<h5><div align="center" style="background-color: lightblue;" >Draft</div></h5>';
        $this->status_lhkpn[1] = '<h5><div align="center" style="background-color: green; color: white;" >Proses Verifikasi</div></h5>';
        $this->status_lhkpn[2] = '<h5><div align="center" style="background-color: orange; color: white;">Perlu Perbaikan</div></h5>';
        $this->status_lhkpn[3] = '<h5><div align="center" style="background-color: #ff6600;" >Terverifikasi Lengkap</div></h5>';
        $this->status_lhkpn[4] = '<h5><div align="center" style="background-color: blue; color: white;" >Diumumkan</div></h5>';
        $this->status_lhkpn[5] = '<h5><div align="center" style="background-color: Cyan;" >Terverifikasi Tidak Lengkap</div></h5>';
        $this->status_lhkpn[6] = '<h5><div align="center" style="background-color: Red; color: white;" >Diumumkan Tidak Lengkap</div></h5>';
        $this->status_lhkpn[7] = '<h5><div align="center" style="background-color: black; color: white;" >Ditolak</div></h5>';
        return;
    }

    function __count_data_harta($ID_LHKPN) {
        $query_harta = "select(select count(*) from (SELECT
                            t_lhkpn_harta_tidak_bergerak.ID_HARTA
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_tidak_bergerak`
                              ON `t_lhkpn_harta_tidak_bergerak`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              union
                          SELECT
                            t_lhkpn_harta_bergerak.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_bergerak`
                              ON `t_lhkpn_harta_bergerak`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_bergerak_lain.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_bergerak_lain`
                              ON `t_lhkpn_harta_bergerak_lain`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_surat_berharga.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_surat_berharga`
                              ON `t_lhkpn_harta_surat_berharga`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_kas.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_kas`
                              ON `t_lhkpn_harta_kas`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_lainnya.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_lainnya`
                              ON `t_lhkpn_harta_lainnya`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                           SELECT
                            t_lhkpn_hutang.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_hutang`
                              ON `t_lhkpn_hutang`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                          WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "') as co_harta) as c_harta";
        $count_harta = $this->db->query($query_harta)->row();
        return $count_harta->c_harta;
    }

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
                . "t_lhkpn.`ID_LHKPN`,"
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
        $sql = "SELECT ID,`ID_LHKPN`,t_lhkpn_jabatan.`ID_JABATAN`,`DESKRIPSI_JABATAN`,`IS_PRIMARY`, M_JABATAN.NAMA_JABATAN
				FROM `t_lhkpn_jabatan`
                                LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN
				WHERE `t_lhkpn_jabatan`.`ID_LHKPN` = '" . $id_lhkpn . "'
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
                    $result[$key]->ENTRY_VIA = $record->ENTRY_VIA;
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
	
	function update_t_lhkpn($id, $tgl_klarifikasi) {
        $this->db->where('ID_LHKPN', $id);
        $this->db->update($this->table, array('TGL_KLARIFIKASI' => $tgl_klarifikasi));
		
    }

    function delete($id) {
        $this->db->where('ID_LHKPN', $id);
        $this->db->delete($this->table);
    }

    function summaryHarta($idLhkpn, $tabel, $field, $as_field, $id_lhkpn_field_name = 'ID_LHKPN') {
        $sql = "SELECT SUM($field) as $as_field FROM $tabel WHERE (IS_ACTIVE = '1' OR IS_ACTIVE IS NULL) AND $id_lhkpn_field_name = " . $this->db->escape($idLhkpn);
        //echo $sql.";<br />";
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
     * E_AUDIT
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
    function build_query_list_verifikasi_lhkpn($cari, $limit, $offset, $penugasan) {
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
                    LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD
                    LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID

                    LEFT JOIN `T_VERIFICATION`
                      ON `T_LHKPN`.`ID_LHKPN` = `T_VERIFICATION`.`ID_LHKPN`
                    JOIN `T_PN`
                      ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN`
                    LEFT JOIN `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`
                      ON `T_LHKPN`.`ID_LHKPN` = `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`.`ID_LHKPN`";

        if ($cari['ENTRY_VIA'] == '0' || $cari['ENTRY_VIA'] == '1') {
            $my_where[] = " T_LHKPN.ENTRY_VIA = '" . $cari['ENTRY_VIA'] . "' ";
        } else {
            $my_where[] = " T_LHKPN.entry_via <> '2' ";
        }

        if ($penugasan == TRUE) {
            $my_where[] = "T_LHKPN.STATUS = '1'";
//            $my_where[] = "T_LHKPN.entry_via <> '2'";
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
//            $my_where[] = "T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%'";
            $my_where[] = "(T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%' OR T_PN.NIK LIKE '%" . $cari['NAMA'] . "%')";
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
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, TRUE);

//        echo $sql;exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
    }

    // --------- eAudit
	 function list_pemeriksaan_lhkpn($id_usr, $cari, $limit, $offset, $roles) {
		
        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_pemeriksaan_lhkpn($cari, $limit, $offset, TRUE);



    /*    if (@$cari['JENIS']) {
            $my_where_find = "T_LHKPN.JENIS_LAPORAN = '" . $cari['JENIS'] . "'";
        }

        if (@$cari['VIA'] != '') {
            $my_where_find = "T_LHKPN.ENTRY_VIA = '" . $cari['VIA'] . "'";
        } */

        $status = '1';
        if (@$cari['STATUS'] != '') {
            $status = $cari['STATUS'];
        }

        if ($roles == '2'){
            $my_where[] = "(T_LHKPN_AUDIT.ID_PEMERIKSA IS NOT NULL OR T_LHKPN_AUDIT.UPDATED_BY IS NOT NULL)";
        }else{
            $my_where[] = "(T_LHKPN_AUDIT.ID_PEMERIKSA  = '" . $id_usr . "' OR T_LHKPN_AUDIT.UPDATED_BY = '" . $id_usr . "')";
        }

        $my_where[] = "T_LHKPN_AUDIT.STATUS_PERIKSA = '" . $status . "'";


        $order_by = " T_PN.NAMA asc ";
        $order_by = " T_LHKPN.tgl_kirim_final asc ";
        list($sql, $sql_count) = $this->build_list_query_pemeriksaan($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, TRUE);

       /* echo $sql;
        exit;*/

        return $this->execute_list_pemeriksaan_lhkpn_query($sql, $sql_count);
//        $this->db->order_by('T_PN.NAMA', 'asc');
    }

    function build_query_list_pemeriksaan_lhkpn($cari, $limit, $offset, $penugasan) {
        $my_where = [];
        $my_where_find = [];

        $my_select_string = [];
        $my_select_string[] = " *,T_LHKPN.ID_LHKPN";

        $my_from = " FROM T_LHKPN ";
//        $this->db->from('T_LHKPN');

        $my_join = "LEFT JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_LHKPN = `T_LHKPN`.ID_LHKPN AND T_LHKPN_JABATAN.`IS_PRIMARY` = '1'
                    LEFT JOIN T_LHKPN_DATA_PRIBADI ON T_LHKPN_DATA_PRIBADI.ID_LHKPN = `T_LHKPN`.ID_LHKPN
                    LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                    LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD
                    LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID
                    JOIN `T_PN`
                      ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN`
                    RIGHT JOIN `T_LHKPN_AUDIT`
                      ON `T_LHKPN`.`ID_LHKPN` = `T_LHKPN_AUDIT`.`ID_LHKPN`";

        if ($cari['ENTRY_VIA'] == '0' || $cari['ENTRY_VIA'] == '1') {
            $my_where[] = " T_LHKPN.ENTRY_VIA = '" . $cari['ENTRY_VIA'] . "' ";
        } else {
            $my_where[] = " T_LHKPN.entry_via <= '2' ";
        }

        if ($penugasan == TRUE) {
            // $my_where[] = "T_LHKPN.STATUS = '1'";
            $my_where[] = "T_LHKPN.STATUS IN ('4','6')";
//            $my_where[] = "T_LHKPN.entry_via <> '2'";
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
//            $my_where[] = "T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%'";
            $my_where[] = "(T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%' OR T_PN.NIK LIKE '%" . $cari['NAMA'] . "%')";
        }

        return [$my_where, $my_where_find, $my_select_string, $my_from, $my_join];
    }

    function list_penugasan_pemeriksaan_lhkpn($cari, $limit, $offset = 0) {

        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_pemeriksaan_lhkpn($cari, $limit, $offset, TRUE);

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
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, TRUE);

//        echo $sql;exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
    }
    // --------- end eAudit


    function build_query_list_announ_lhkpn($cari, $limit, $offset, $penugasan) {
        $my_where = [];
        $my_where_find = [];

        $my_select_string = [];
        $my_select_string[] = "*, T_LHKPN.ID_LHKPN AS ID_LHKPN_DIJABATAN,T_LHKPN.ID_LHKPN,T_LHKPN_JABATAN.ID_LHKPN, T_LHKPN.STATUS";

        $my_from = " FROM T_LHKPN ";

        $my_join = "LEFT JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_LHKPN = `T_LHKPN`.ID_LHKPN AND T_LHKPN_JABATAN.`IS_PRIMARY` = '1'
                    LEFT JOIN T_LHKPN_DATA_PRIBADI ON T_LHKPN_DATA_PRIBADI.ID_LHKPN = `T_LHKPN`.ID_LHKPN
                    LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                    LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD
                    LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID
                    LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
                    LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT
                    JOIN `T_PN` ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN`
                    LEFT JOIN `R_BA_PENGUMUMAN` ON R_BA_PENGUMUMAN.ID_LHKPN = T_LHKPN.ID_LHKPN";

        $my_where[] = "T_LHKPN.STATUS IN ('5', '3') AND T_LHKPN.IS_ACTIVE = 1 AND T_LHKPN.ID_LHKPN NOT IN (SELECT ID_LHKPN FROM R_BA_PENGUMUMAN) AND R_BA_PENGUMUMAN.ID_LHKPN IS NULL AND T_LHKPN.entry_via <> '2' AND YEAR(T_LHKPN.tgl_kirim_final) >= '2017'";

        if (@$cari['TAHUN']) {
            $my_where[] = "YEAR(TGL_LAPOR) = '" . $cari['TAHUN'] . "'";
        }

        if (@$cari['LEMBAGA']) {
            $my_where[] = "M_INST_SATKER.INST_NAMA LIKE '%" . $cari['LEMBAGA'] . "%'";
        }

        if (@$cari['UNITKERJA']) {
            $my_where[] = "M_UNIT_KERJA.UK_NAMA LIKE '%" . $cari['UNITKERJA'] . "%'";
        }

        if (@$cari['NAMA']) {
            $my_where[] = "(T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%' OR T_PN.NIK LIKE '%" . $cari['NAMA'] . "%')";
        }

        return [$my_where, $my_where_find, $my_select_string, $my_from, $my_join];
    }

     function list_penugasan_announ_lhkpn($cari, $limit, $offset = 0) {

        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_announ_lhkpn($cari, $limit, $offset, TRUE);


        if (@$cari['VIA']) {
            if ($cari['VIA'] == '') {
                $my_where_find[] = " T_LHKPN.ENTRY_VIA IS NULL ";
            } else {
                $my_where_find[] = " T_LHKPN.ENTRY_VIA ='" . $cari['VIA'] . "' ";
            }
        }

        if (@$cari['JENIS']) {
            if ($cari['JENIS'] == '') {
                $my_where_find[] = " T_LHKPN.JENIS_LAPORAN IS NULL ";
            } else {
                $my_where_find[] = " T_LHKPN.JENIS_LAPORAN ='" . $cari['PETUGAS'] . "' ";
            }
        }

        if (@$cari['STATUS']) {
            if ($cari['STATUS'] == '') {
                $my_where_find[] = " T_LHKPN.STATUS IS NULL ";
            } else {
                $my_where_find[] = " T_LHKPN.STATUS ='" . $cari['STATUS'] . "' ";
            }
        }

        $order_by = " T_LHKPN.tgl_kirim_final asc ";
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by);

//        echo $sql;exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
    }

    function build_list_query_pemeriksaan($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, $penugasan) {
        $string_where = implode(" AND ", $my_where);
        if ($penugasan == TRUE){
            $string_where_find = implode(" AND ", $my_where_find);
        }else{
            $string_where_find = implode(" OR ", $my_where_find);
        }

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
	
	function execute_list_pemeriksaan_lhkpn_query($sql, $sql_count) {
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

	public function copy_to_lhkpn($id_lhkpn_prev, $tgl_klarifikasi) { 

        $id_lhkpn = $this->copy_to_t_lhkpn($id_lhkpn_prev); 
            
        if ($id_lhkpn) {
            $this->copy_to_t_lhkpn_data_pribadi($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_fasilitas($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_bergerak($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_bergerak_lain($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_kas($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_lainnya($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_surat_berharga($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_tidak_bergerak($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_hutang($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_keluarga($id_lhkpn_prev, $id_lhkpn);
            
            $this->copy_to_t_lhkpn_jabatan($id_lhkpn_prev, $id_lhkpn);
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_bergerak", "t_lhkpn_pelepasan_harta_bergerak");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_bergerak_lain", "t_lhkpn_pelepasan_harta_bergerak_lain");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_kas", "t_lhkpn_pelepasan_harta_kas");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_lainnya", "t_lhkpn_pelepasan_harta_lainnya");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_surat_berharga", "t_lhkpn_pelepasan_harta_surat_berharga");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_tidak_bergerak", "t_lhkpn_pelepasan_harta_tidak_bergerak");
            $this->copy_to_t_lhkpn_penerimaan_kas($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_t_lhkpn_pengeluaran_kas($id_lhkpn_prev, $id_lhkpn);

            $this->copy_to_pengeluaran2($id_lhkpn_prev, $id_lhkpn);
            $this->copy_to_penerimaan2($id_lhkpn_prev, $id_lhkpn);
            
            
        }
		$tgl = $this->update_t_lhkpn($id_lhkpn, $tgl_klarifikasi); 
        return $id_lhkpn;
    }

    public function copy_to_penerimaan2($id_imp_xl_lhkpn, $id_lhkpn, $return_data_array = FALSE, $debug = FALSE) {

        $data_detail = $this->mglobal->secure_get_by_id("t_lhkpn_penerimaan_kas", "id_lhkpn", "id_lhkpn_penerimaan_kas", make_secure_text($id_imp_xl_lhkpn));

//        if ($data_detail && !is_null($data_detail)) {

        $pn = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PN) ? $data_detail->NILAI_PENERIMAAN_KAS_PN : "{}");
        $pa = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PASANGAN) ? $data_detail->NILAI_PENERIMAAN_KAS_PASANGAN : "{}");

        $jenis_penerimaan_kas_pn = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $golongan_penerimaan_kas_pn = $this->config->item('golongan_penerimaan_kas_pn', 'harta');

//            var_dump($jenis_penerimaan_kas_pn, $golongan_penerimaan_kas_pn);
//        exit;

        $label = array('A', 'B', 'C');

        $data_arr = array();

        $k = 0;
        for ($i = 0; $i < count($jenis_penerimaan_kas_pn); $i++) {
            for ($j = 0; $j < count($jenis_penerimaan_kas_pn[$i]); $j++) {
                $PA_val = 'PA' . $j;
                $code = $label[$i] . $j;

                $data_arr[$k] = array(
                    "ID_LHKPN" => $id_lhkpn,
                    "GROUP_JENIS" => $label[$i],
                    "KODE_JENIS" => $code,
                    "JENIS_PENERIMAAN" => $jenis_penerimaan_kas_pn[$i][$j],
                    "PN" => 0,
                    "PASANGAN" => 0,
                );
                if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
                    $data_arr[$k]["PN"] = $pn->{$label[$i]}[$j]->$code;
                }

                if ($i == 0) {
                    if (is_array($pa) && !empty($pa) && property_exists($pa[$j], $PA_val)) {
                        $data_arr[$k]["PASANGAN"] = $pa[$j]->{$PA_val};
                    }
                }

                $k++;
            }
        }
        unset($pn, $pa, $jenis_penerimaan_kas_pn);
        if ($debug) {
            var_dump($data_arr);
            exit;
        }

        if ($return_data_array) {
            return $data_arr;
        }

        $this->db->insert_batch('t_lhkpn_penerimaan_kas2', $data_arr);
    }

    public function copy_to_pengeluaran2($id_imp_xl_lhkpn, $id_lhkpn, $return_data_array = FALSE, $debug = FALSE) {

        $data_detail = $this->mglobal->secure_get_by_id("t_lhkpn_pengeluaran_kas", "id_lhkpn", "id_lhkpn_pengeluaran_kas", make_secure_text($id_imp_xl_lhkpn));

        $pn = json_decode(!is_null($data_detail->NILAI_PENGELUARAN_KAS) ? $data_detail->NILAI_PENGELUARAN_KAS : "{}");

        $jenis_pengeluaran_kas_pn = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $label = array('A', 'B', 'C');

        $data_arr = array();

        $k = 0;
        for ($i = 0; $i < count($jenis_pengeluaran_kas_pn); $i++) {
            for ($j = 0; $j < count($jenis_pengeluaran_kas_pn[$i]); $j++) {
                $code = $label[$i] . $j;

//                $result->{$code} = '0';

                $data_arr[$k] = array(
                    "ID_LHKPN" => $id_lhkpn,
                    "GROUP_JENIS" => $label[$i],
                    "KODE_JENIS" => $code,
                    "JENIS_PENGELUARAN" => $jenis_pengeluaran_kas_pn[$i][$j],
                    "JML" => 0,
                );

                if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
//                    $result->{$code} = $pn->{$label[$i]}[$j]->$code;
                    $data_arr[$k]["JML"] = $pn->{$label[$i]}[$j]->$code;
                }
                $k++;
            }
        }

        unset($pn, $jenis_pengeluaran_kas_pn);
        if ($debug) {
            var_dump($data_arr);
            exit;
        }

        if ($return_data_array) {
            return $data_arr;
        }

        $this->db->insert_batch('t_lhkpn_pengeluaran_kas2', $data_arr);
    }

    private function copy_to_t_lhkpn_pengeluaran_kas($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_pengeluaran_kas (  ID_LHKPN,   ID_JENIS_PENGELUARAN_KAS,   LAINNYA,   NILAI_PENGELUARAN_KAS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP) "
                . "(select   " . $id_lhkpn . ",   ID_JENIS_PENGELUARAN_KAS,   LAINNYA,   NILAI_PENGELUARAN_KAS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP "
                . " from t_lhkpn_pengeluaran_kas "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_penerimaan_kas($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_penerimaan_kas (  ID_LHKPN,   ID_JENIS_PENERIMAAN_KAS,   LAINNYA,   NILAI_PENERIMAAN_KAS_PN,   NILAI_PENERIMAAN_KAS_PASANGAN,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP) "
                . "(select   " . $id_lhkpn . ",   ID_JENIS_PENERIMAAN_KAS,   LAINNYA,   NILAI_PENERIMAAN_KAS_PN,   NILAI_PENERIMAAN_KAS_PASANGAN,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP "
                . " from t_lhkpn_penerimaan_kas "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, $table_source, $table_destination) {
        $sql = "insert into " . $table_destination . " (  ID_HARTA,   ID_LHKPN,   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta "
                . " from " . $table_source . " "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_pelepasan_harta_bergerak($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_pelepasan_harta_bergerak (  ID_HARTA,   ID_LHKPN,   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta "
                . " from t_lhkpn_pelepasan_harta_bergerak "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_keluarga($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_keluarga (  ID_LHKPN,   NIK,   NAMA,   HUBUNGAN,   STATUS_HUBUNGAN,   TEMPAT_LAHIR,   TANGGAL_LAHIR,   JENIS_KELAMIN,   TEMPAT_NIKAH,   TANGGAL_NIKAH,   TEMPAT_CERAI,   TANGGAL_CERAI,   PEKERJAAN,   ALAMAT_RUMAH,   NOMOR_TELPON,   IS_ACTIVE,   KETERANGAN_DIHAPUS,   STATUS_CETAK_SURAT_KUASA,   CETAK_SURAT_KUASA_TIME,   SURAT_KUASA,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta) "
                . "(select   " . $id_lhkpn . ",   NIK,   NAMA,   HUBUNGAN,   STATUS_HUBUNGAN,   TEMPAT_LAHIR,   TANGGAL_LAHIR,   JENIS_KELAMIN,   TEMPAT_NIKAH,   TANGGAL_NIKAH,   TEMPAT_CERAI,   TANGGAL_CERAI,   PEKERJAAN,   ALAMAT_RUMAH,   NOMOR_TELPON,   IS_ACTIVE,   KETERANGAN_DIHAPUS,   STATUS_CETAK_SURAT_KUASA,   CETAK_SURAT_KUASA_TIME,   SURAT_KUASA,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta "
                . " from t_lhkpn_keluarga "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_hutang($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_hutang (  ID_HARTA,   ID_LHKPN,   ATAS_NAMA,   KODE_JENIS,   NAMA_KREDITUR,   TANGGAL_TRANSAKSI,   TANGGAL_JATUH_TEMPO,   AGUNAN,   AWAL_HUTANG,   SALDO_HUTANG,   STATUS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   ATAS_NAMA,   KODE_JENIS,   NAMA_KREDITUR,   TANGGAL_TRANSAKSI,   TANGGAL_JATUH_TEMPO,   AGUNAN,   AWAL_HUTANG,   SALDO_HUTANG,   STATUS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA "
                . " from t_lhkpn_hutang "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_tidak_bergerak($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_tidak_bergerak (ID_HARTA,   ID_LHKPN,   NEGARA,   ID_NEGARA,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   IS_PELEPASAN,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta) "
                . "(select ID_HARTA,   " . $id_lhkpn . ",   1,   2,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   0,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta "
                . " from t_lhkpn_harta_tidak_bergerak "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        /**
         * @todo Untuk Versi Excel Selanjutnya ID NEGARA (yg sekarang nilainya 1) dan NEGARA (yg sekarang nilainya 2) sebaiknya diberikan deteksi dari excel
         */
//        $sql = "insert into t_lhkpn_harta_tidak_bergerak (ID_HARTA,   ID_LHKPN,   NEGARA,   ID_NEGARA,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   IS_PELEPASAN,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta) "
//                . "(select ID_HARTA,   " . $id_lhkpn . ",   1,   2,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   0,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta "
//                . " from t_imp_xl_lhkpn_harta_tidak_bergerak "
//                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_surat_berharga($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_surat_berharga (  ID_HARTA,   ID_LHKPN,   KODE_JENIS,   JUMLAH,   SATUAN,   NAMA_SURAT_BERHARGA,   NAMA_PENERBIT,   CUSTODIAN,   NOMOR_REKENING,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_PELEPASAN,   STATUS,   IS_CHECKED,   FILE_BUKTI,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   KODE_JENIS,   JUMLAH,   SATUAN,   NAMA_SURAT_BERHARGA,   NAMA_PENERBIT,   CUSTODIAN,   NOMOR_REKENING,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   0,   STATUS,   IS_CHECKED,   FILE_BUKTI,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA "
                . " from t_lhkpn_harta_surat_berharga "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_lainnya($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_lainnya (ID_HARTA,   ID_LHKPN,   KODE_JENIS,   NAMA,   KETERANGAN,   KUANTITAS,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   IS_PELEPASAN,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD ) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   KODE_JENIS,   NAMA,   KETERANGAN,   KUANTITAS,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   0,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD "
                . " from t_lhkpn_harta_lainnya "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_kas($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_kas (  ID_HARTA,   ID_LHKPN,   KODE_JENIS,   ASAL_USUL,   ATAS_NAMA_REKENING,   NAMA_BANK,   NOMOR_REKENING,   KETERANGAN,   TAHUN_BUKA_REKENING,   MATA_UANG,   NILAI_SALDO,   NILAI_KURS,   NILAI_EQUIVALEN,   STATUS,   IS_PELEPASAN,   FILE_BUKTI,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta ) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   KODE_JENIS,   ASAL_USUL,   ATAS_NAMA_REKENING,   NAMA_BANK,   NOMOR_REKENING,   KETERANGAN,   TAHUN_BUKA_REKENING,   MATA_UANG,   NILAI_SALDO,   NILAI_KURS,   NILAI_EQUIVALEN,   STATUS,   0,   FILE_BUKTI,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta "
                . " from t_lhkpn_harta_kas "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_bergerak_lain($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_bergerak_lain (ID_HARTA,   ID_LHKPN,   KODE_JENIS,   NAMA,   JUMLAH,   SATUAN,   KETERANGAN,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   STATUS,   IS_PELEPASAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta ) "
                . "(select ID_HARTA,   " . $id_lhkpn . ",   KODE_JENIS,   NAMA,   JUMLAH,   SATUAN,   KETERANGAN,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   STATUS,   0,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta "
                . " from t_lhkpn_harta_bergerak_lain "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_bergerak($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_bergerak (ID_LHKPN, KODE_JENIS, MEREK, MODEL, TAHUN_PEMBUATAN, NOPOL_REGISTRASI, NAMA, JUMLAH, SATUAN, JENIS_BUKTI, NOMOR_BUKTI, ATAS_NAMA, ASAL_USUL, PEMANFAATAN, KET_LAINNYA, TAHUN_PEROLEHAN_AWAL, TAHUN_PEROLEHAN_AKHIR, MATA_UANG, NILAI_PEROLEHAN, NILAI_PELAPORAN, JENIS_NILAI_PELAPORAN, IS_ACTIVE, JENIS_LEPAS, TGL_TRANSAKSI, NILAI_JUAL, NAMA_PIHAK2, ALAMAT_PIHAK2, STATUS, IS_PELEPASAN, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_CHECKED, IS_LOAD, FormulirID, ref_form_harta) "
                . "(select " . $id_lhkpn . ", KODE_JENIS, MEREK, MODEL, TAHUN_PEMBUATAN, NOPOL_REGISTRASI, NAMA, JUMLAH, SATUAN, JENIS_BUKTI, NOMOR_BUKTI, ATAS_NAMA, ASAL_USUL, PEMANFAATAN, KET_LAINNYA, TAHUN_PEROLEHAN_AWAL, TAHUN_PEROLEHAN_AKHIR, MATA_UANG, NILAI_PEROLEHAN, NILAI_PELAPORAN, JENIS_NILAI_PELAPORAN, IS_ACTIVE, JENIS_LEPAS, TGL_TRANSAKSI, NILAI_JUAL, NAMA_PIHAK2, ALAMAT_PIHAK2, STATUS, 0, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_CHECKED, IS_LOAD, FormulirID, ref_form_harta "
                . " from t_lhkpn_harta_bergerak "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_fasilitas($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_fasilitas (ID_FASILITAS, ID_LHKPN, JENIS_FASILITAS, NAMA_FASILITAS, PEMBERI_FASILITAS, KETERANGAN, KETERANGAN_LAIN, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_LOAD) "
                . "(select ID_FASILITAS, " . $id_lhkpn . ", JENIS_FASILITAS, NAMA_FASILITAS, PEMBERI_FASILITAS, KETERANGAN, KETERANGAN_LAIN, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_LOAD "
                . " from t_lhkpn_fasilitas "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_jabatan($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_jabatan (ID_LHKPN, ID_JABATAN, DESKRIPSI_JABATAN, ESELON, LEMBAGA, UNIT_KERJA, SUB_UNIT_KERJA, TMT, SD, ALAMAT_KANTOR, EMAIL_KANTOR, FILE_SK, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, ID_STATUS_AKHIR_JABAT, IS_PRIMARY, TEXT_JABATAN_PUBLISH) "
                . "(select " . $id_lhkpn . ", ID_JABATAN, DESKRIPSI_JABATAN, ESELON, LEMBAGA, UNIT_KERJA, SUB_UNIT_KERJA, TMT, SD, ALAMAT_KANTOR, EMAIL_KANTOR, FILE_SK, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, ID_STATUS_AKHIR_JABAT, 1, TEXT_JABATAN_PUBLISH "
                . " from t_lhkpn_jabatan "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";
        
        $res = $this->db->query($sql);
        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_data_pribadi($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_data_pribadi (ID_LHKPN, id_agama, GELAR_DEPAN, GELAR_BELAKANG, NAMA_LENGKAP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, NIK, NPWP, STATUS_PERKAWINAN, AGAMA, JABATAN, JABATAN_LAINNYA, ALAMAT_RUMAH, EMAIL_PRIBADI, PROVINSI, KABKOT, KECAMATAN, KELURAHAN, TELPON_RUMAH, HP, HP_LAINNYA, FOTO, FILE_NPWP, FILE_KTP, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, KD_ISO3_NEGARA, NEGARA, ALAMAT_NEGARA, formulirid_migrasi, pnid_migrasi, NO_KK) "
                . "(select " . $id_lhkpn . ", id_agama, GELAR_DEPAN, GELAR_BELAKANG, NAMA_LENGKAP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, NIK, NPWP, STATUS_PERKAWINAN, AGAMA, JABATAN, JABATAN_LAINNYA, ALAMAT_RUMAH, EMAIL_PRIBADI, PROVINSI, KABKOT, KECAMATAN, KELURAHAN, TELPON_RUMAH, HP, HP_LAINNYA, FOTO, FILE_NPWP, FILE_KTP, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, KD_ISO3_NEGARA, NEGARA, ALAMAT_NEGARA, formulirid_migrasi, pnid_migrasi, NO_KK "
                . " from t_lhkpn_data_pribadi "
                . "where id_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql); 
        if ($res) {
            return TRUE;
        }
        return FALSE;
    }
	
	public function searching_lhkpn($id){
		$sql = "SELECT ID_LHKPN FROM t_lhkpn where ID_LHKPN_PREV = '".$id."' and JENIS_LAPORAN = '5' and IS_ACTIVE = '1'";
        $act = $this->db->query($sql)->row();
        return $act;
	}
	
	public function tgl_klarifikasi($id){
		$sql = "SELECT TGL_KLARIFIKASI FROM t_lhkpn where ID_LHKPN = '".$id."' and JENIS_LAPORAN = '5' and IS_ACTIVE = '1'";
        $act = $this->db->query($sql)->row(); 
        return $act; 
	}
	
	public function is_riksa($id){
		$sql = "SELECT ID_LHKPN FROM t_lhkpn where ID_LHKPN_PREV = '".$id."' and JENIS_LAPORAN = '5' and IS_ACTIVE = '1'";
        $act = $this->db->query($sql)->row(); 

		if($act){
			return TRUE;
		}else{
			return FALSE;
		}
        
	}
	
	private function copy_to_t_lhkpn($id_lhkpn_prev) { 
		$jenis_laporan = 5;
        $sql = "INSERT INTO `t_lhkpn` (`JENIS_LAPORAN`, `ID_PN`, `tgl_lapor`, `tgl_kirim`, `nip`, `entry_via`, `FILE_KK`, `ALASAN`, `IS_COPY`, `STATUS_PERBAIKAN_NASKAH`, `CATATAN_PERBAIKAN_NASKAH`, `USERNAME_ENTRI`, `STATUS_SURAT_PERNYATAAN`, `SURAT_PERNYATAAN`, `STATUS_CETAK_SURAT_KUASA`, `CETAK_SURAT_KUASA_TIME`, `SURAT_KUASA`, `STATUS_SURAT_UMUMKAN`, `CETAK_SURAT_UMUMKAN_TIME`, `SURAT_UMUMKAN`, `TOKEN_PENGIRIMAN`, `created_time`, `CREATED_IP`, `pnid_migrasi`, `formulirid_migrasi`, `tgl_kirim_final`, `FILE_BUKTI_SKM`, `FILE_BUKTI_SK`, `FILE_BUKTI_IKHTISAR`, `ID_LHKPN_PREV`, `status`, `is_active`) "
                . "(select ".$jenis_laporan.", `ID_PN`, `tgl_lapor`, `tgl_kirim`, `nip`, `entry_via`, `FILE_KK`, `ALASAN`, `IS_COPY`, `STATUS_PERBAIKAN_NASKAH`, `CATATAN_PERBAIKAN_NASKAH`, `USERNAME_ENTRI`, `STATUS_SURAT_PERNYATAAN`, `SURAT_PERNYATAAN`, `STATUS_CETAK_SURAT_KUASA`, `CETAK_SURAT_KUASA_TIME`, `SURAT_KUASA`, `STATUS_SURAT_UMUMKAN`, `CETAK_SURAT_UMUMKAN_TIME`, `SURAT_UMUMKAN`, `TOKEN_PENGIRIMAN`, `created_time`, `CREATED_IP`, `pnid_migrasi`, `formulirid_migrasi`, `tgl_kirim_final`, `FILE_BUKTI_SKM`, `FILE_BUKTI_SK`, `FILE_BUKTI_IKHTISAR`, ".$id_lhkpn_prev.", `status`, `is_active`"
                . " from t_lhkpn "
                . "where id_lhkpn = '" . $id_lhkpn_prev . "')";
		
        $res = $this->db->query($sql); 
        if ($res) {
            //return TRUE;
			$id_lhkpn = $this->db->insert_id(); 
            
            $this->transfer_files($record_found, $id_lhkpn);

            //$this->db->where('id_lhkpn', $id_imp_xl_lhkpn);
            //$this->db->update('t_lhkpn', array('is_send' => '1'));

            //$this->db->where('id_imp_xl_lhkpn', $id_imp_xl_lhkpn);
            //$this->db->update('t_lhkpnoffline_penerimaan', array(
            //    'is_send' => '1',
            //    'id_lhkpn' => $id_lhkpn
            //    ));

            return $id_lhkpn;
        }
        return FALSE;
    }

    private function transfer_files($lhkpn_record, $id_lhkpn) {
        
        $nik = encrypt_username($lhkpn_record["NIK"], 'e');
        
        
        if(!is_null($lhkpn_record["FILE_BUKTI_SKM"])){
            $this->deploy_files($lhkpn_record["FILE_BUKTI_SKM"], $this->DIR_TEMP_SKM_UPLOAD.$nik, $this->DIR_SKM_UPLOAD . "$nik/$id_lhkpn/");
        }
        
        if(!is_null($lhkpn_record["FILE_BUKTI_SK"])){
            $this->deploy_files($lhkpn_record["FILE_BUKTI_SK"], $this->DIR_TEMP_SKUASA_UPLOAD.$nik, $this->DIR_SKUASA_UPLOAD . "$nik/$id_lhkpn/");
        }
        
        if(!is_null($lhkpn_record["FILE_BUKTI_IKHTISAR"])){
            $this->deploy_files($lhkpn_record["FILE_BUKTI_IKHTISAR"], $this->DIR_TEMP_IKHTISAR_UPLOAD.$nik, $this->DIR_IKHTISAR_UPLOAD . "$nik/$id_lhkpn/");
        }
        
    }
    
    private function deploy_files($string_file, $origin, $destination){
        $file_names = explode(", ", $string_file);
        if(is_array($file_names)){
            foreach($file_names as $file_name){
                rename($origin.$file_name, $destination.$file_name);
            }
        }
    }

//    private function copy_to_t_lhkpn($id_lhkpn_prev) {
//      //$this->db->join('t_lhkpnoffline_penerimaan', 't_lhkpnoffline_penerimaan.id_imp_xl_lhkpn = t_imp_xl_lhkpn.id_imp_xl_lhkpn');
//      //$this->db->join('t_pn', 't_pn.ID_PN = t_lhkpnoffline_penerimaan.ID_PN');
//      //$this->db->select('t_imp_xl_lhkpn.*, t_pn.NIK, t_lhkpnoffline_penerimaan.RAND_ID');
//      $record_found = $this->mglobal->get_by_id("t_lhkpn", "t_lhkpn.id_lhkpn", $id_lhkpn_prev, 'array'); 
//	
//    
//      if ($record_found) {
//    
//          
//    
//          $record_found = (array) $record_found; 
//          unset(
//                  $record_found['id_lhkpn'], $record_found['is_send']
//          );
//    
//          $record_found['status'] = $this->status_lhkpn;
//          $record_found['is_active'] = '1';
//          //display($record_found);die;
//          $insert_record = $record_found;
//          if(array_key_exists("NIK", $insert_record)){
//              unset($insert_record["NIK"]);
//          }
//          if(array_key_exists("RAND_ID", $insert_record)){
//              unset($insert_record["RAND_ID"]);
//          }
//            
////            ["FILE_BUKTI_SKM"]=>
////  string(12) "Penguins.jpg"
////  ["FILE_BUKTI_SK"]=>
////  string(40) "Tulips.jpg, Hydrangeas.jpg, Penguins.jpg"
////  ["FILE_BUKTI_IKHTISAR"]=>
////  string(39) "Lahir Wisada Santoso.JPG, GedungKPK.jpg"
//            
//            $insert_record["FILE_BUKTI_SKM"] = json_encode(explode(", ", $insert_record["FILE_BUKTI_SKM"]));
//            $insert_record["FILE_BUKTI_SK"] = json_encode(explode(", ", $insert_record["FILE_BUKTI_SK"]));
//            $insert_record["FILE_BUKTI_IKHTISAR"] = json_encode(explode(", ", $insert_record["FILE_BUKTI_IKHTISAR"]));
//			
//            display($this->db->insert('t_lhkpn', $insert_record));die;
//            $id_lhkpn = $this->db->insert_id(); 
//            
//            $this->transfer_files($record_found, $id_lhkpn);
//
//            $this->db->where('id_imp_xl_lhkpn', $id_imp_xl_lhkpn);
//            $this->db->update('t_imp_xl_lhkpn', array('is_send' => '1'));
//
//            $this->db->where('id_imp_xl_lhkpn', $id_imp_xl_lhkpn);
//            $this->db->update('t_lhkpnoffline_penerimaan', array(
//                'is_send' => '1',
//                'id_lhkpn' => $id_lhkpn
//                ));
//
//            return $id_lhkpn;
//        }
//        return FALSE;
//    }

}
