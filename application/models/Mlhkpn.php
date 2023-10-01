<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Mkeluarga
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mlhkpn extends CI_Model {

    private $table = 'T_LHKPN';
    public $tipe_pelaporan = array();
    public $status_lhkpn = array();

    function __construct() {
        parent::__construct();
        $this->set_tipe_pelaporan();
        $this->set_status_pelaporan();
    }


/*
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
*/
    private function set_status_pelaporan() {
        $this->status_lhkpn[0] = '<div align="center" class="label label-info">Draft</div>';
        $this->status_lhkpn[1] = '<div align="center" class="label label-succes" style="background-color: green; color: white;">Proses Verifikasi</div></h5>';
        $this->status_lhkpn[2] = '<div align="center" class="label label-succes" style="background-color: orange; color: white;">Perlu Perbaikan</div></h5>';
        $this->status_lhkpn[3] = '<div align="center" class="label label-success" style="background-color: #ff6600;" >Terverifikasi Lengkap</div></h5>';
        $this->status_lhkpn[4] = '<div align="center" class="label label-primary">Diumumkan</div></h5>';
        $this->status_lhkpn[5] = '<div align="center" class="label label-primary" style="background-color: #944743;" >Terverifikasi Tidak Lengkap</div></h5>';
        $this->status_lhkpn[6] = '<div align="center" class="label label-success" style="background-color: Red; color: white;">Diumumkan Tidak Lengkap</div></h5>';
        $this->status_lhkpn[7] = '<div align="center" class="label label-success" style="background-color: black; color: white;" >Dikembalikan</div></h5>';
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
            'notif' => 'Data Pribadi belum diisi, harap lengkapi data pribadi terlebih dahulu.',
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
            'notif' => 'Status di data pribadi anda Menikah, harap isi data keluarga terlebih dahulu.',
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
            'title' => 'Data Harta Kas / Setara Kas',
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
        $data[8] = 'Data Harta Kas / Setara Kas';
        $data[9] = 'Data Harta Lainnya';
        return $data[$index];
    }

    private function set_tipe_pelaporan() {
        $this->tipe_pelaporan[1] = 'Khusus, Calon PN';
        $this->tipe_pelaporan[2] = 'Khusus, Awal Menjabat';
        $this->tipe_pelaporan[3] = 'Khusus, Akhir Menjabat';
        $this->tipe_pelaporan[4] = 'Periodik';
        $this->tipe_pelaporan[5] = 'Klarifikasi';
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
                    . " ID_LHKPN = '" . $id_lhkpn . "' AND IS_ACTIVE = '1' AND "
                    . " ( "
//                    . "     ( "
                    . "         IS_LOAD = '1' AND "
                    . "         IS_CHECKED = '0' "
//                    . "     ) "
//                    . "     OR ID_HARTA IS NULL "
                    . " ) ";
        }

        if ($id_lhkpn && $is_check_data) {
            $sql_where = " ID_LHKPN = '" . $id_lhkpn . "' AND IS_ACTIVE = '1' ";
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

    public function check_data_harta_pelepasan($table_name = FALSE, $id_lhkpn = FALSE){
        $result = 0;

        if($table_name && $id_lhkpn){
            $sql = "(SELECT count(*) as jumlah FROM $table_name WHERE ID_LHKPN = $id_lhkpn AND IS_PELEPASAN = '1' AND IS_ACTIVE = '1')";
            $result = $this->db->query($sql)->result()[0]->jumlah;
        }

        return (int)$result;
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
        $this->db->where("t_lhkpn.JENIS_LAPORAN <> '5' ");
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
    function get_pn($id_lhkpn) {
        $sql = "SELECT T_LHKPN.ID_LHKPN, T_LHKPN.ID_PN
				FROM `t_lhkpn`
                WHERE `t_lhkpn`.`ID_LHKPN` = '" . $id_lhkpn . "'";
        $query = $this->db->query($sql);
        $response = FALSE;
        if ($query) {
            $response = $query->result();
        }
        $id_pn = $response[0]->ID_PN; 
        return $id_pn;
    }
    
    function get_sk($id_lhkpn) {
        $id_pn = $this->get_pn($id_lhkpn);
        
        $sql = "SELECT T_LHKPN.ID_LHKPN, T_LHKPN.ID_PN, t_lhkpn_data_pribadi.FLAG_SK
        FROM `t_lhkpn`
            LEFT JOIN t_lhkpn_data_pribadi ON t_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN
        WHERE `t_lhkpn`.`ID_PN` = '" . $id_pn . "' AND `t_lhkpn`.`IS_ACTIVE` = '1' AND `t_lhkpn_data_pribadi`.`FLAG_SK` = '1'";
        $query = $this->db->query($sql);
        $response = FALSE;
        if ($query) {
            $response = $query->result();
            if ($response) {return '1';}
            return '0';
        }
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

        $query_tombol = $this->db->query("call `fu_select_t_lhkpn_by_id_user`(" . $limit . "," . $offset . ",'" . $id_user . "')");
        $this->db->close();
        $this->db->initialize();

        $query_history_lhkpn = $this->db->query("call `fu_select_t_lhkpn_by_id_user_for_history_lhkpn`(" . $limit . "," . $offset . ",'" . $id_user . "')");
        $this->db->close();
        $this->db->initialize();
        
        $total_rows = 0;
//        $total_rows = $this->count_select_for_get_table_filling();
        if ($query_history_lhkpn) {
            $result = $query_history_lhkpn->result();
            $result_tombol = $query_tombol->result();

            if ($result) {
                $i = 1 + $offset;
                foreach ($result as $key => $record) {
                    
                    foreach ($result_tombol as $value){
                        if($record->ID_LHKPN == $value->ID_LHKPN){
                            //// entry via = online ////
                            $result[$key]->IS_WL = $value->IS_WL;
                        }
                    }
                    $result[$key]->NO_URUT = $i;
                    $result[$key]->TGL_LAPOR_ORI = $record->tgl_lapor;
                    $result[$key]->TGL_LAPOR = string_to_date($record->tgl_lapor, 'd/m/Y');
                    $result[$key]->TIPE_PELAPORAN = $this->get_type_pelaporan($record->JENIS_LAPORAN);
                    $result[$key]->STATUS_LHKPN = $this->get_status_pelaporan($record->STATUS);
                    $result[$key]->ALL_JABATAN = $this->get_jabatan_all($record->ID_LHKPN);
                    $result[$key]->SK = $this->get_sk($record->ID_LHKPN);
                    $result[$key]->ENTRY_VIA = $record->ENTRY_VIA;
                    $result[$key]->ALASAN = $record->ALASAN;
                    $result[$key]->DIKEMBALIKAN = $record->DIKEMBALIKAN;
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
            $this->db->where($additional_condition, NULL, FALSE);
        }
        $this->db->where('IS_ACTIVE', 1);
        if ($jenis_laporan && $jenis_laporan !== FALSE) {
            $this->db->where('JENIS_LAPORAN', $jenis_laporan, NULL, FALSE);
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
            // $this->db->where('STATUS !=', '0');
            // $this->db->where('JENIS_LAPORAN !=', '5');
            $this->db->where('case when JENIS_LAPORAN = 5 then STATUS in (\'3\',\'4\',\'5\',\'6\') else STATUS != 0 END',null,false);
            $this->db->order_by('tgl_lapor', 'DESC');
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

    function summaryHarta($idLhkpn, $tabel, $field, $as_field, $id_lhkpn_field_name = 'ID_LHKPN') {
        $sql = "SELECT SUM($field) as $as_field, COUNT($field) as jumlah FROM $tabel WHERE (IS_ACTIVE = '1' OR IS_ACTIVE IS NULL) AND $id_lhkpn_field_name = " . $this->db->escape($idLhkpn);
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

    function get_by_id_pn($id_pn, $limit = false) {
        // $this->db->where('ID_PN', $id_pn);
        // $this->db->where('IS_ACTIVE', '1');
        // // $this->db->where('JENIS_LAPORAN <>', '5');
        // $this->db->where('IS_ACTIVE', '1');
        // $this->db->where_in('STATUS', array('3','4','5','6'));
        // $this->__sculpt_limit(2);
        // $this->db->order_by('TGL_LAPOR', 'DESC');
        // $query = $this->db->get('t_lhkpn');

        $str = '';

        if($limit = true){
            $str = " LIMIT 2";
          }

        $script_mysql = "SELECT *
            FROM t_lhkpn
            WHERE ID_PN = '". $id_pn . "' AND
            JENIS_LAPORAN <> '5'
            AND IS_ACTIVE = '1'
            ORDER BY TGL_LAPOR DESC".$str;
       
            
        $query = $this->db->query($script_mysql);
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
    function build_query_list_verifikasi_lhkpn($cari, $limit, $offset, $penugasan) {
        $my_where = [];
        $my_where_find = [];

        $my_select_string = [];
        $my_select_string[] = " T_LHKPN_DATA_PRIBADI.*,
                                M_JABATAN.*,
                                M_INST_SATKER.*,
                                M_UNIT_KERJA.*,
                                M_ESELON.*,
                                T_VERIFICATION.*,
                                T_PN.*,
                                T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.*,
                                T_LHKPN.ID_LHKPN,
                                T_LHKPN.ID_LHKPN_PREV,
                                T_LHKPN.ALASAN,
                                T_LHKPN.entry_via,
                                T_LHKPN.ID_PN,
                                T_VERIFICATION.ID as ID_VERIF,
                                T_VERIFICATION.IS_ACTIVE as IS_ACTIVE_VERIF,
                                T_LHKPN.IS_ACTIVE,
                                T_LHKPN.JENIS_LAPORAN,
                                T_LHKPN.STATUS,
                                T_LHKPN.tgl_kirim,
                                T_LHKPN.tgl_kirim_final,
                                T_LHKPN.tgl_lapor,
                                T_LHKPN.USERNAME_ENTRI,
                                T_LHKPN.back_to_draft,
                                T_LHKPN.DIKEMBALIKAN,
                                CASE WHEN fu_flag_sk_pn(T_LHKPN.ID_LHKPN) = '1' THEN
                                    CASE WHEN fu_flag_sk_pasangan(T_LHKPN.ID_LHKPN) NOT LIKE '%0%' OR fu_flag_sk_pasangan(T_LHKPN.ID_LHKPN) IS NULL THEN
                                        CASE WHEN `fu_flag_sk_anak_tanggungan`(T_LHKPN.ID_LHKPN, T_LHKPN.tgl_lapor) NOT LIKE '%0%' 
                                            OR `fu_flag_sk_anak_tanggungan`(T_LHKPN.ID_LHKPN, T_LHKPN.tgl_lapor) IS NULL 
                                            THEN 'Lengkap'
                                        ELSE 'Tidak Lengkap' END
                                    ELSE 'Tidak Lengkap' END
                                ELSE 'Tidak Lengkap' END AS Status_Kelengkapan,
                                IF(ISNULL(RANGKAP.ID_LHKPN), 'TIDAK', 'YA') AS RANGKAP,
                                (SELECT T_LHKPN_2.STATUS FROM T_LHKPN T_LHKPN_2 WHERE T_LHKPN_2.ID_LHKPN = T_LHKPN.ID_LHKPN_PREV) AS STATUS_LHKPN_SEBELUMNYA";
        
        if($cari['STATUS'] == '2' || $cari['STATUS'] == '8') {
              $my_select_string[] = "DATE_FORMAT(DATE_ADD(t_lhkpn_status_history.DATE_INSERT, INTERVAL 30 DAY), '%d-%m-%Y') AS DUE_DATE2";
        }

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

        $my_from = " FROM T_LHKPN";
//        $this->db->from('T_LHKPN');

        $my_join = "LEFT JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_LHKPN = `T_LHKPN`.ID_LHKPN AND T_LHKPN_JABATAN.`IS_PRIMARY` = '1'
                    LEFT JOIN (SELECT ID_LHKPN FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN HAVING COUNT(ID_LHKPN) > 1) AS RANGKAP ON RANGKAP.ID_LHKPN = T_LHKPN.ID_LHKPN
                    LEFT JOIN T_LHKPN_DATA_PRIBADI ON T_LHKPN_DATA_PRIBADI.ID_LHKPN = `T_LHKPN`.ID_LHKPN
                    LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN AND M_JABATAN.IS_ACTIVE <> 0
                    LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD
                    LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID
                    LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = M_JABATAN.KODE_ESELON

                    LEFT JOIN `T_VERIFICATION`
                      ON `T_LHKPN`.`ID_LHKPN` = `T_VERIFICATION`.`ID_LHKPN`
                    LEFT JOIN `T_PN`
                      ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN`";

        if ($penugasan == TRUE) {
            $my_join .= "LEFT JOIN `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`
                        ON `T_LHKPN`.`ID_LHKPN` = `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`.`ID_LHKPN`";
        } else {
            $my_join .= "JOIN `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`
                        ON `T_LHKPN`.`ID_LHKPN` = `T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI`.`ID_LHKPN`";
        }
        
        if ($cari['STATUS'] == '2' || $cari['STATUS'] == '8'){
//            $my_join .= "LEFT JOIN (SELECT MAX(DATE_INSERT) AS DATE_INSERT, ID_STATUS, ID_LHKPN FROM t_lhkpn_status_history GROUP BY ID_LHKPN, ID_STATUS) AS HISTORY 
//                        ON HISTORY.ID_LHKPN = T_LHKPN.ID_LHKPN AND HISTORY.ID_STATUS = 7";
            $my_join .= "left join t_lhkpn_status_history on t_lhkpn_status_history.`ID_LHKPN` = T_LHKPN.ID_LHKPN AND t_lhkpn_status_history.ID_STATUS = 7";
        }

        if (@$cari['ENTRY_VIA'] == '0' || @$cari['ENTRY_VIA'] == '1') {
            $my_where[] = " T_LHKPN.ENTRY_VIA = '" . $cari['ENTRY_VIA'] . "' ";
        } else {
            $my_where[] = " T_LHKPN.entry_via <> '2' ";
        }

        if ($penugasan == TRUE) {
            if ($cari['STATUS_LHKPN'] == '') {
                $my_where[] = "T_LHKPN.STATUS IN ('1','2')";
            }
	    $my_where[] = "T_LHKPN.JENIS_LAPORAN <> '5'";
//            $my_where[] = "T_LHKPN.entry_via <> '2'";
        } 
        // else {
        //     $my_where[] = "T_LHKPN.STATUS NOT IN ('0', '4')";
        // }

        $my_where[] = "T_LHKPN.IS_ACTIVE = '1'";

        if (@$cari['STATUS_LHKPN'] != 0) { 

            if($cari['STATUS_LHKPN'] == 3){ //jika status lhkpn sudah diperbaiki
                $my_where[] = "T_LHKPN.STATUS = '1'";
                $my_where[] = "T_LHKPN.ALASAN IS NOT NULL";
            }else{
                $my_where[] = "T_LHKPN.STATUS = '" . $cari['STATUS_LHKPN'] . "'";
                if ($cari['STATUS_LHKPN'] == 1) {
                    $my_where[] = "T_LHKPN.ALASAN IS NULL";
                }
            }
           
        }

        if (@$cari['TAHUN']) {
            $my_where[] = "YEAR(TGL_LAPOR) = '" . $cari['TAHUN'] . "'";
        }
        

        if (@$cari['TAHUN_KIRIM_FINAL']) {
            $my_where[] = "YEAR(TGL_KIRIM_FINAL) = '" . $cari['TAHUN_KIRIM_FINAL'] . "'";
        }

        if (@$cari['LEMBAGA']) {
            // $my_where[] = "INST_NAMA LIKE '%" . $cari['LEMBAGA'] . "%'";
            $my_where[] = "M_INST_SATKER.INST_SATKERKD = '" . $cari['LEMBAGA'] . "'";
        }

        if (@$cari['UNIT_KERJA']) {
            // $my_where[] = "UK_NAMA LIKE '%" . $cari['UNIT_KERJA'] . "%'";
            $my_where[] = "M_UNIT_KERJA.UK_ID = '" . $cari['UNIT_KERJA'] . "'";
        }

        if (@$cari['ESELON']) {
            $my_where[] = "ID_ESELON = '" . $cari['ESELON'] . "'";
        }

        if (@$cari['UU'] != NULL || @$cari['UU'] != '' ) {
            $my_where[] = "IS_UU = '" . $cari['UU'] . "'";
        }
        
        if (@$cari['NAMA']) {
//            $my_where[] = "T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%'";
            $my_where[] = "(T_LHKPN_DATA_PRIBADI.NAMA_LENGKAP LIKE '%" . $cari['NAMA'] . "%'  OR T_LHKPN_DATA_PRIBADI.EMAIL_PRIBADI LIKE  '%" . $cari['NAMA'] . "%' OR T_LHKPN_DATA_PRIBADI.NIK LIKE '%" . $cari['NAMA'] . "%')";
        }

        if (@$cari['RANGKAP']) {
            $my_where[] = "IF(ISNULL(RANGKAP.ID_LHKPN), 'TIDAK', 'YA') = '" . $cari['RANGKAP'] . "'";
        }

        if (@$cari['AKTIFASI'] != '') {
            $my_where[] = "T_PN.IS_FORMULIR_EFILLING = '" . $cari['AKTIFASI'] . "'";
        }

        return [$my_where, $my_where_find, $my_select_string, $my_from, $my_join];
    }

    function build_query_list_verifikasi_lhkpn2($cari, $limit, $offset = 0) {
        $my_where = [];
        $my_where_find = [];

        $my_select_string = [];
        $my_select_string[] = " *";
        
        if(@$cari['STATUS'] == '2' || @$cari['STATUS'] == '8') {
              $my_select_string[] = "DATE_FORMAT(DATE_ADD(t_lhkpn_status_history.DATE_INSERT, INTERVAL 30 DAY), '%d-%m-%Y') AS DUE_DATE2";
        }

        $my_from = " FROM v_lhkpn_list_verifikasi";
        
        if (@$cari['STATUS'] == '2' || @$cari['STATUS'] == '8'){
           //   $my_join .= "LEFT JOIN (SELECT MAX(DATE_INSERT) AS DATE_INSERT, ID_STATUS, ID_LHKPN FROM t_lhkpn_status_history GROUP BY ID_LHKPN, ID_STATUS) AS HISTORY 
           //   N HISTORY.ID_LHKPN = T_LHKPN.ID_LHKPN AND HISTORY.ID_STATUS = 7";             
               $my_join = "LEFT JOIN t_lhkpn_status_history ON t_lhkpn_status_history.ID_LHKPN = v_lhkpn_list_verifikasi.ID_LHKPN AND t_lhkpn_status_history.ID_STATUS = 7 ";        
        }

        $my_where[] = "IS_ACTIVE = '1'";

        if (@$cari['TAHUN']) {
            $my_where[] = "YEAR(TGL_LAPOR) = '" . $cari['TAHUN'] . "'";
        }
        

        if (@$cari['TAHUN_KIRIM_FINAL']) {
            $my_where[] = "YEAR(TGL_KIRIM_FINAL) = '" . $cari['TAHUN_KIRIM_FINAL'] . "'";
        }

        if (@$cari['LEMBAGA']) {
            // $my_where[] = "INST_NAMA LIKE '%" . $cari['LEMBAGA'] . "%'";
            $my_where[] = "INST_SATKERKD = '" . $cari['LEMBAGA'] . "'";
        }

        if (@$cari['UNIT_KERJA']) {
            // $my_where[] = "UK_NAMA LIKE '%" . $cari['UNIT_KERJA'] . "%'";
            $my_where[] = "UK_ID = '" . $cari['UNIT_KERJA'] . "'";
        }

        if (@$cari['ESELON']) {
            $my_where[] = "ID_ESELON = '" . $cari['ESELON'] . "'";
        }

        if (@$cari['UU'] != NULL || @$cari['UU'] != '' ) {
            $my_where[] = "IS_UU = '" . $cari['UU'] . "'";
        }
        
        if (@$cari['NAMA']) {
//            $my_where[] = "T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%'";
            $my_where[] = "(NAMA_LENGKAP LIKE '%" .  $this->db->escape_like_str($cari['NAMA']) . "%' ESCAPE '!' OR EMAIL_PRIBADI LIKE  '%" . $this->db->escape_like_str($cari['NAMA']) . "%' ESCAPE '!' OR NIK LIKE '%" . $this->db->escape_like_str($cari['NAMA']) . "%' ESCAPE '!')";
        }

        if (@$cari['RANGKAP']) {
            $my_where[] = "IF(ISNULL(RANGKAP.ID_LHKPN), 'TIDAK', 'YA') = '" . $cari['RANGKAP'] . "'";
        }

        return [$my_where, $my_where_find, $my_select_string, $my_from, $my_join];
    }

    function build_query_list_tracking_lhkpn($cari, $limit, $offset, $penugasan) {
        $my_where = [];
        $my_where_find = [];

        $my_select_string = [];
        $my_select_string[] = " M_JABATAN.NAMA_JABATAN,
                                M_INST_SATKER.INST_NAMA,
                                M_UNIT_KERJA.UK_NAMA,
                                T_PN.NAMA, T_PN.NIK, T_PN.EMAIL, T_PN.NHK, T_PN.TGL_LAHIR,
                                T_USER.LAST_LOGIN,
                                T_LHKPN.ALASAN,
                                T_LHKPN.entry_via,
                                T_LHKPN.ID_PN,
                                T_LHKPN.IS_ACTIVE,
                                T_LHKPN.JENIS_LAPORAN,
                                T_LHKPN.STATUS,
                                T_LHKPN.tgl_kirim,
                                T_LHKPN.tgl_kirim_final,
                                T_LHKPN.tgl_lapor,
                                T_LHKPN.USERNAME_ENTRI,
                                T_LHKPN.back_to_draft,
                                T_LHKPN.ID_LHKPN,
                                CASE WHEN fu_flag_sk_pn(T_LHKPN.ID_LHKPN) = '1' THEN
                                    CASE WHEN fu_flag_sk_pasangan(T_LHKPN.ID_LHKPN) NOT LIKE '%0%' OR fu_flag_sk_pasangan(T_LHKPN.ID_LHKPN) IS NULL THEN
                                        CASE WHEN `fu_flag_sk_anak_tanggungan`(T_LHKPN.ID_LHKPN, T_LHKPN.tgl_lapor) NOT LIKE '%0%' 
                                            OR `fu_flag_sk_anak_tanggungan`(T_LHKPN.ID_LHKPN, T_LHKPN.tgl_lapor) IS NULL 
                                            THEN 'Lengkap'
                                        ELSE 'Tidak Lengkap' END
                                    ELSE 'Tidak Lengkap' END
                                ELSE 'Tidak Lengkap' END AS Status_Kelengkapan,
                                T_LHKPN.DIKEMBALIKAN";
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
                    -- LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = M_JABATAN.KODE_ESELON

                    -- LEFT JOIN `T_VERIFICATION`
                      -- ON `T_LHKPN`.`ID_LHKPN` = `T_VERIFICATION`.`ID_LHKPN`
                    JOIN `T_PN`
                      ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN` AND `T_PN`.`IS_ACTIVE` <> '-1'
                    LEFT JOIN `T_USER`
                      ON `T_USER`.`USERNAME` = `T_PN`.`NIK`";

        // $my_where[] = "T_LHKPN.IS_ACTIVE = '1' AND T_LHKPN.JENIS_LAPORAN <> '5'";
        $my_where[] = "T_LHKPN.IS_ACTIVE = '1' AND
            CASE
                WHEN T_LHKPN.JENIS_LAPORAN = '5' THEN STATUS IN ('3', '4', '5', '6')
                ELSE TRUE
            END
        ";

        if (@$cari['LEMBAGA']) {
            // $my_where[] = "INST_NAMA LIKE '%" . $cari['LEMBAGA'] . "%'";
            $my_where[] = "M_INST_SATKER.INST_SATKERKD = '" . $cari['LEMBAGA'] . "'";
        }
        if (@$cari['UNIT_KERJA']) {
            // $my_where[] = "UK_NAMA LIKE '%" . $cari['UNIT_KERJA'] . "%'";
            $my_where[] = "M_UNIT_KERJA.UK_ID = '" . $cari['UNIT_KERJA'] . "'";
        }

        if (@$cari['NAMA']) {
            //            $my_where[] = "T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%'";
            //////////////SEARCH BY T_LHKPN_DATA_PRIBADI///////////////////////
            //             $my_where[] = "(T_LHKPN_DATA_PRIBADI.NAMA_LENGKAP LIKE '%" . $cari['NAMA'] . "%' OR T_LHKPN_DATA_PRIBADI.NIK LIKE '%" . $cari['NAMA'] . "%')";
            $my_where[] = "(T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%' OR T_PN.NIK = '" . $cari['NAMA'] . "' OR T_PN.EMAIL = '" . $cari['NAMA'] . "')";
        }

        if (@$cari['TGL_LAHIR']) {
            $my_where[] = "T_LHKPN_DATA_PRIBADI.TANGGAL_LAHIR = '" . $cari['TGL_LAHIR'] . "'";
        }

        if (@$cari['NHK']) {
            $my_where[] = "T_PN.NHK = '" . $cari['NHK'] . "'";
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

        $date_now_to_time = "TO_SECONDS(CURDATE())";

        $my_having = '';

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

            if($cari['FILTER_BELUM_DITUGASKAN'] != 0){
                
                $tgl_kirim_max5 = "DATE_ADD(T_LHKPN.tgl_kirim_final, INTERVAL 5 DAY)"; 
                $tgl_kirim_max5_to_time = "TO_SECONDS($tgl_kirim_max5)";

                $tgl_kirim_max10 = "DATE_ADD(T_LHKPN.tgl_kirim_final, INTERVAL 10 DAY)"; 
                $tgl_kirim_max10_to_time = "TO_SECONDS($tgl_kirim_max10)";

                if($cari['FILTER_BELUM_DITUGASKAN'] == 1){ 
                    
                    $my_where_find[] = " $date_now_to_time <= $tgl_kirim_max5_to_time ";

                }else if($cari['FILTER_BELUM_DITUGASKAN'] == 2){ 
                        
                    $my_where_find[] = " $date_now_to_time > $tgl_kirim_max5_to_time && $date_now_to_time <= $tgl_kirim_max10_to_time ";

                }else if($cari['FILTER_BELUM_DITUGASKAN'] == 3){ 
                    
                    $my_where_find[] = " $date_now_to_time > $tgl_kirim_max10_to_time ";

                }

            }

            if($cari['FILTER_SUDAH_DITUGASKAN'] != 0){

                $condition = " T_VERIFICATION.IS_ACTIVE  != 1 || T_VERIFICATION.ID IS NULL ";

                $my_where_find[] = $condition;

                $tgl_tugas_max3 = "DATE_ADD(T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.TANGGAL_PENUGASAN, INTERVAL 3 DAY)";
                $tgl_tugas_max3_to_time = "TO_SECONDS($tgl_tugas_max3)";
               
                $tgl_tugas_max7 = "DATE_ADD(T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.TANGGAL_PENUGASAN, INTERVAL 7 DAY)";
                $tgl_tugas_max7_to_time = "TO_SECONDS($tgl_tugas_max7)";

                if($cari['FILTER_SUDAH_DITUGASKAN'] == 1){ 
                   
                    $my_where_find[] = " $date_now_to_time <= $tgl_tugas_max3_to_time ";

                }else if($cari['FILTER_SUDAH_DITUGASKAN'] == 2){

                    $my_where_find[] = " $date_now_to_time > $tgl_tugas_max3_to_time && $date_now_to_time <= $tgl_tugas_max7_to_time ";

                }else if($cari['FILTER_SUDAH_DITUGASKAN'] == 3){

                    $my_where_find[] = " $date_now_to_time > $tgl_tugas_max7_to_time ";
                    
                }

            }

            if($cari['STATUS_LHKPN_SEBELUMNYA'] != 0){

                $sub_query_select = "AND (SELECT T_LHKPN_2.STATUS FROM T_LHKPN T_LHKPN_2 WHERE T_LHKPN_2.ID_LHKPN = T_LHKPN.ID_LHKPN_PREV)";

                if($cari['STATUS_LHKPN_SEBELUMNYA'] == 1){ 
                   
                    $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 4 ";  //Diumumkan Lengkap

                    $my_subquery = $sub_query_select." = 4 ";

                }else if($cari['STATUS_LHKPN_SEBELUMNYA'] == 2){

                    $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 6 ";  //Diumumkan Tidak Lengkap

                    $my_subquery = $sub_query_select." = 6 ";

                }else if($cari['STATUS_LHKPN_SEBELUMNYA'] == 3){

                    $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 3 ";  //Terverifikasi Lengkap

                    $my_subquery = $sub_query_select. " = 3 ";

                }else if($cari['STATUS_LHKPN_SEBELUMNYA'] == 4){

                    $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 5 ";  //Terverifikasi Tidak Lengkap

                    $my_subquery = $sub_query_select. " = 5 ";
                }
            }


        } else {
            $my_where_find[] = " T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.STAT IS NULL ";
            //            $my_where_find[] = " T_LHKPN.STATUS IN ('1', '2') ";
        }
        $order_by = " T_LHKPN.tgl_kirim_final asc ";
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, TRUE, $my_having, $my_subquery);

    //    echo $sql;exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
    }

    function build_query_list_announ_lhkpn($cari, $limit, $offset, $penugasan) {
        $my_where = [];
        $my_where_find = [];

        $my_select_string = [];
        $my_select_string[] = " T_LHKPN_JABATAN.*, 
                                T_LHKPN_DATA_PRIBADI.*,
                                M_JABATAN.*,
                                M_INST_SATKER.*,
                                M_UNIT_KERJA.*,
                                T_PN.*,
                                R_BA_PENGUMUMAN.*,
                                T_LHKPN.ALASAN,
                                T_LHKPN.entry_via,
                                T_LHKPN.ID_PN,
                                T_LHKPN.IS_ACTIVE,
                                T_LHKPN.JENIS_LAPORAN,
                                T_LHKPN.STATUS,
                                T_LHKPN.tgl_kirim,
                                T_LHKPN.tgl_kirim_final,
                                T_LHKPN.tgl_lapor,
                                T_LHKPN.USERNAME_ENTRI,
                                T_LHKPN.back_to_draft,                        
                                T_LHKPN.ID_LHKPN AS ID_LHKPN_DIJABATAN,
                                T_LHKPN.ID_LHKPN,
                                T_LHKPN_JABATAN.ID_LHKPN,
                                T_LHKPN.STATUS";

        $my_from = " FROM T_LHKPN ";

        $my_join = "LEFT JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_LHKPN = `T_LHKPN`.ID_LHKPN AND T_LHKPN_JABATAN.`IS_PRIMARY` = '1'
                    LEFT JOIN T_LHKPN_DATA_PRIBADI ON T_LHKPN_DATA_PRIBADI.ID_LHKPN = `T_LHKPN`.ID_LHKPN
                    LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                    LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD
                    LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID
                    -- LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
                    -- LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT
                    LEFT JOIN `T_PN` ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN`
                    LEFT JOIN `R_BA_PENGUMUMAN` ON R_BA_PENGUMUMAN.ID_LHKPN = T_LHKPN.ID_LHKPN";

        $my_where[] = "T_LHKPN.IS_ACTIVE = 1 AND T_LHKPN.ID_LHKPN NOT IN (SELECT ID_LHKPN FROM R_BA_PENGUMUMAN) AND R_BA_PENGUMUMAN.ID_LHKPN IS NULL AND T_LHKPN.entry_via <> '2' AND YEAR(T_LHKPN.tgl_kirim_final) >= '2017' AND T_LHKPN.JENIS_LAPORAN <> '5' /*CASE WHEN T_LHKPN.JENIS_LAPORAN <> '5' THEN T_LHKPN.entry_via <> '2' AND YEAR(T_LHKPN.tgl_kirim_final) >= '2017' ELSE TRUE END*/ ";

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
                $my_where_find[] = " T_LHKPN.JENIS_LAPORAN ='" . $cari['JENIS'] . "' ";
            }
        }

        if (@$cari['STATUS']) {
            if ($cari['STATUS'] == '') {
                $my_where_find[] = " T_LHKPN.STATUS IS NULL ";
            } else {
                $my_where_find[] = " T_LHKPN.STATUS ='" . $cari['STATUS'] . "' ";
            }
        } else {
            $my_where_find[] = " T_LHKPN.STATUS IN ('5', '3') ";
        }

        $order_by = " T_LHKPN.tgl_kirim_final asc ";
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by);

        // echo $sql;exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
    }

    function build_list_query_verifikasi_all_data($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, $penugasan = NULL) {
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

        $sql = " SELECT " . $string_select . " " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where . " ORDER BY  " . $order_by;

        $sql_count = " SELECT count(T_LHKPN.ID_LHKPN) total_row " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where;

        return [$sql, $sql_count];
    }

    function build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, $penugasan = NULL, $my_having='', $my_subquery='') {
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

        $sql = " SELECT " . $string_select . " " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where ." ". $my_having . "ORDER BY  " . $order_by . " LIMIT " . $offset . ", " . $limit;

        
        if(@$this->input->post('CARI')['INFO_SK'] != ''){
            $this->db->initialize();
            
            $sql_2 = " SELECT " . $string_select . " " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where ." ". $my_having . "ORDER BY  " . $order_by;
            $query = $this->db->query($sql_2);
            $sql_count = (int)$query->num_rows();

            $this->db->close();

        }else{
            $sql_count = " SELECT count(T_LHKPN.ID_LHKPN) total_row " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where." ". $my_subquery;
        }

        return [$sql, $sql_count];
    }

    function build_list_query_verifikasi2($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, $penugasan = NULL, $my_having='', $my_subquery='') {
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

        $sql = " SELECT " . $string_select . " " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where ." ". $my_having . "ORDER BY  " . $order_by . " LIMIT " . $offset . ", " . $limit;

        
        if(@$this->input->post('CARI')['INFO_SK'] != ''){
            $this->db->initialize();
            
            $sql_2 = " SELECT " . $string_select . " " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where ." ". $my_having . "ORDER BY  " . $order_by;
            $query = $this->db->query($sql_2);
            $sql_count = (int)$query->num_rows();

            $this->db->close();

        }else{
            $sql_count = " SELECT count(v_lhkpn_list_verifikasi.ID_LHKPN) total_row " . $my_from . " " . $my_join . " WHERE " . $compiled_string_where." ". $my_subquery;
        }

        // display($sql_count); exit;

        return [$sql, $sql_count];
    }


    function execute_list_verifikasi_lhkpn_query($sql, $sql_count) {
        $this->db->close();

        $this->db->initialize();
    
        if(is_string($sql_count)){
            $q_count = $this->db->query($sql_count);
        }

        $this->db->close();
        $this->db->initialize();

        $total_rows = 0;

        if (isset($q_count) && is_object($q_count)) {
            $rcount = $q_count->row();
            if ($rcount) {
                $total_rows = $rcount->total_row;
            }
        }else{
            $total_rows = $sql_count;
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

    function list_verifikasi_lhkpn($usr, $cari, $limit, $offset, $roles) {


        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_verifikasi_lhkpn($cari, $limit, $offset, FALSE);

        $my_where_find = [];
        $my_where_finds = "";
        $my_having = '';
        if (@$cari['PETUGAS']) {
            if ($cari['PETUGAS'] == '') {
                $my_where_finds .= "T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.USERNAME IS NULL ";
            } else {
                $my_where_finds .= "T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.USERNAME ='" . $cari['PETUGAS'] . "' ";
            }
            if (@$cari['MENUGASKAN']) {
                if ($cari['MENUGASKAN'] == '') {
                    $my_where_find .= "AND T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.UPDATED_BY IS NULL ";
                } else {
                    $my_where_finds .= "AND T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.UPDATED_BY ='" . $cari['MENUGASKAN'] . "' ";
                }
            }
        }else{
            if (@$cari['MENUGASKAN']) {
                if ($cari['MENUGASKAN'] == '') {
                    $my_where_find .= "T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.UPDATED_BY IS NULL ";
                } else {
                    $my_where_finds .= "T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.UPDATED_BY ='" . $cari['MENUGASKAN'] . "' ";
                }
            }
        }
        array_push($my_where_find, $my_where_finds);

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
        
        if ($roles == '2' || $roles == '31'){
//            $my_where[] = "(T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.USERNAME IS NOT NULL OR T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.UPDATED_BY IS NOT NULL)";
            $my_where[] = "1";
        }else{
            $my_where[] = "(T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.USERNAME = '" . $usr . "' OR T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI.UPDATED_BY = '" . $usr . "')";
        }

        if ($status == '8'){
            $my_where[] = "T_LHKPN.STATUS = '1' AND T_LHKPN.ALASAN IN ('1','2') AND T_LHKPN.DIKEMBALIKAN = '0'";
        }else if ($status == '1'){
            $my_where[] = "T_LHKPN.STATUS = '1' AND T_LHKPN.ALASAN IS NULL AND T_LHKPN.DIKEMBALIKAN = '0'";
            // $my_where[] = "T_LHKPN.STATUS = '1' AND T_LHKPN.ALASAN IS NULL AND (T_VERIFICATION.STATUS_VERIFIKASI <> '1' OR T_VERIFICATION.STATUS_VERIFIKASI IS NULL)";
        }else if ($status == '9'){
            $my_where[] = "T_LHKPN.STATUS = '1' AND T_LHKPN.DIKEMBALIKAN > '0'";
        }else{
            $my_where[] = "T_LHKPN.STATUS = '" . $status . "'";
        }

        if($cari['STATUS_LHKPN_SEBELUMNYA'] != 0){

            $sub_query_select = "AND (SELECT T_LHKPN_2.STATUS FROM T_LHKPN T_LHKPN_2 WHERE T_LHKPN_2.ID_LHKPN = T_LHKPN.ID_LHKPN_PREV)";

            if($cari['STATUS_LHKPN_SEBELUMNYA'] == 1){ 
            
                $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 4 ";  //Diumumkan Lengkap

                $my_subquery = $sub_query_select." = 4 ";

            }else if($cari['STATUS_LHKPN_SEBELUMNYA'] == 2){

                $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 6 ";  //Diumumkan Tidak Lengkap

                $my_subquery = $sub_query_select." = 6 ";

            }else if($cari['STATUS_LHKPN_SEBELUMNYA'] == 3){

                $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 3 ";  //Terverifikasi Lengkap

                $my_subquery = $sub_query_select. " = 3 ";

            }else if($cari['STATUS_LHKPN_SEBELUMNYA'] == 4){

                $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 5 ";  //Terverifikasi Tidak Lengkap

                $my_subquery = $sub_query_select. " = 5 ";
            }
        }

        if(@$cari['STATUS_LHKPN_SEBELUMNYA'] != 0){ 

            if(@$cari['INFO_SK'] != ''){

                if(@$cari['INFO_SK'] == 'Lengkap'){ 
    
                    $my_having .= "AND Status_kelengkapan = 'Lengkap' ";
    
                }elseif(@$cari['INFO_SK'] == 'Tidak Lengkap'){
    
                    $my_having .= "AND Status_kelengkapan = 'Tidak Lengkap' ";
    
                }

            }

        }else{

            if(@$cari['INFO_SK'] != ''){

                if(@$cari['INFO_SK'] == 'Lengkap'){
                
                    $my_having = "HAVING Status_kelengkapan = 'Lengkap' ";
        
                }elseif(@$cari['INFO_SK'] == 'Tidak Lengkap'){
                   
                    $my_having = "HAVING Status_kelengkapan = 'Tidak Lengkap' ";
        
                }

            }

        }

        $order_by = " T_PN.NAMA asc ";
        $order_by = " T_LHKPN.tgl_kirim_final asc ";

        if ($status == '8' || $status == '2'){
            $group_by = " GROUP BY t_lhkpn.id_lhkpn ";
        }
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, NULL, $my_having, @$my_subquery);
        //var_dump($sql);die;
        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
//        $this->db->order_by('T_PN.NAMA', 'asc');

    }

    function list_verifikasi_lhkpn2($usr, $cari, $limit, $offset, $roles) {


        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_verifikasi_lhkpn2($cari, $limit, $offset);

        $my_where_find = [];
        $my_where_finds = "";
        $my_having = '';

        if (@$cari['PETUGAS']) {
            if ($cari['PETUGAS'] == '') {
                $my_where_finds .= "USERNAME IS NULL ";
            } else {
                $my_where_finds .= "USERNAME ='" . $cari['PETUGAS'] . "' ";
            }
            if (@$cari['MENUGASKAN']) {
                if ($cari['MENUGASKAN'] == '') {
                    $my_where_find .= "AND UPDATED_BY IS NULL ";
                } else {
                    $my_where_finds .= "AND UPDATED_BY ='" . $cari['MENUGASKAN'] . "' ";
                }
            }
        }else{
            if (@$cari['MENUGASKAN']) {
                if ($cari['MENUGASKAN'] == '') {
                    $my_where_find .= "UPDATED_BY IS NULL ";
                } else {
                    $my_where_finds .= "UPDATED_BY ='" . $cari['MENUGASKAN'] . "' ";
                }
            }
        }
        array_push($my_where_find, $my_where_finds);

        if (@$cari['JENIS']) {
            $my_where_find = "JENIS_LAPORAN = '" . $cari['JENIS'] . "'";
        }

        $status = '1';
        if (@$cari['STATUS'] != '') {
            $status = $cari['STATUS'];
        }

        if ($roles == '2'){
            $my_where[] = "1";
        }else{
            $my_where[] = "(USERNAME = '" . $usr . "' OR UPDATED_BY = '" . $usr . "')";
        }

        if ($status == '8'){
            $my_where[] = "STATUS = '1' AND ALASAN IN ('1','2') AND DIKEMBALIKAN = '0'";
        }else if ($status == '1'){
            $my_where[] = "STATUS = '1' AND ALASAN IS NULL AND DIKEMBALIKAN = '0'";
        }else if ($status == '9'){
            $my_where[] = "STATUS = '1' AND DIKEMBALIKAN > '0'";
        }else{
            $my_where[] = "STATUS = '" . $status . "'";
        }

        if(@$cari['STATUS_LHKPN_SEBELUMNYA'] != 0){

            $sub_query_select = "AND (SELECT T_LHKPN.STATUS FROM T_LHKPN WHERE T_LHKPN.ID_LHKPN = v_lhkpn_list_verifikasi.ID_LHKPN_PREV)";

            if($cari['STATUS_LHKPN_SEBELUMNYA'] == 1){ 
            
                $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 4 ";  //Diumumkan Lengkap

                $my_subquery = $sub_query_select." = 4 ";

            }else if($cari['STATUS_LHKPN_SEBELUMNYA'] == 2){

                $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 6 ";  //Diumumkan Tidak Lengkap

                $my_subquery = $sub_query_select." = 6 ";

            }else if($cari['STATUS_LHKPN_SEBELUMNYA'] == 3){

                $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 3 ";  //Terverifikasi Lengkap

                $my_subquery = $sub_query_select. " = 3 ";

            }else if($cari['STATUS_LHKPN_SEBELUMNYA'] == 4){

                $my_having = "HAVING STATUS_LHKPN_SEBELUMNYA = 5 ";  //Terverifikasi Tidak Lengkap

                $my_subquery = $sub_query_select. " = 5 ";
            }
        }

        if(@$cari['STATUS_LHKPN_SEBELUMNYA'] != 0){ 

            if(@$cari['INFO_SK'] != ''){

                if(@$cari['INFO_SK'] == 'Lengkap'){ 
    
                    $my_having .= "AND Status_kelengkapan = 'Lengkap' ";
    
                }elseif(@$cari['INFO_SK'] == 'Tidak Lengkap'){
    
                    $my_having .= "AND Status_kelengkapan = 'Tidak Lengkap' ";
    
                }

            }

        }else{

            if(@$cari['INFO_SK'] != ''){

                if(@$cari['INFO_SK'] == 'Lengkap'){
                
                    $my_having = "HAVING Status_kelengkapan = 'Lengkap' ";
        
                }elseif(@$cari['INFO_SK'] == 'Tidak Lengkap'){
                   
                    $my_having = "HAVING Status_kelengkapan = 'Tidak Lengkap' ";
        
                }

            }

        }

        $order_by = " tgl_kirim_final asc ";

        if ($status == '8' || $status == '2'){
            $group_by = " GROUP BY id_lhkpn ";
        }
        list($sql, $sql_count) = $this->build_list_query_verifikasi2($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by, NULL, $my_having, @$my_subquery);

        // display($sql);
        // exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
//        $this->db->order_by('T_PN.NAMA', 'asc');

    }

    function list_tracking_lhkpn_all($cari, $limit = 0, $offset = 0) {
        
        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_tracking_lhkpn($cari, $limit, $offset, FALSE);
       
        $order_by = " T_LHKPN.tgl_lapor desc ";
        
        list($sql, $sql_count) = $this->build_list_query_verifikasi_all_data($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by);
/*        echo $sql;
        exit; */

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
//        $this->db->order_by('T_PN.NAMA', 'asc');
    }

    function list_tracking_lhkpn($cari, $limit, $offset = 0) {

        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_tracking_lhkpn($cari, $limit, $offset, FALSE);

        $order_by = " T_LHKPN.tgl_lapor desc ";
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by);

/*        echo $sql;
        exit; */

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
//        $this->db->order_by('T_PN.NAMA', 'asc');
    }

    function build_query_list_announ_lhkpn_pnwl($cari, $limit, $offset = 0) {
        $my_where = [];
        $my_where_find = [];

        $my_select_string = [];
        $my_select_string[] = " T_LHKPN.ID_LHKPN,
                                T_LHKPN.tgl_kirim_final,
                                T_LHKPN.tgl_lapor,
                                T_LHKPN.JENIS_LAPORAN,
                                T_LHKPN.entry_via,
                                T_PN.ID_PN,
                                T_PN.NIK,
                                T_PN.NAMA,
                                T_PN.EMAIL,
                                M_INST_SATKER.INST_NAMA,
                                M_UNIT_KERJA.UK_NAMA,
                                M_JABATAN.NAMA_JABATAN,
                                T_BA_PENGUMUMAN.TGL_BA_PENGUMUMAN,
                                R_BA_PENGUMUMAN.STATUS_CETAK_PENGUMUMAN_PDF,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
                                (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6,
                                (SELECT SUM(SALDO_HUTANG) FROM t_lhkpn_hutang WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') T7";
        $my_from = " FROM R_BA_PENGUMUMAN ";

        $my_join = "LEFT JOIN `T_BA_PENGUMUMAN` ON `R_BA_PENGUMUMAN`.`ID_BAP` = `T_BA_PENGUMUMAN`.`ID_BAP`
                    LEFT JOIN `T_LHKPN` ON `R_BA_PENGUMUMAN`.`ID_LHKPN` = `T_LHKPN`.`ID_LHKPN`
                    LEFT JOIN `T_PN` ON `T_LHKPN`.`ID_PN` = `T_PN`.`ID_PN`
                    LEFT JOIN `T_LHKPN_JABATAN` ON `T_LHKPN_JABATAN`.`ID_LHKPN` = `T_LHKPN`.`ID_LHKPN` AND `T_LHKPN_JABATAN`.`IS_PRIMARY` = '1'
                    LEFT JOIN `T_LHKPN_DATA_PRIBADI` ON `T_LHKPN_DATA_PRIBADI`.`ID_LHKPN` = `T_LHKPN`.`ID_LHKPN`
                    LEFT JOIN `M_JABATAN` ON `M_JABATAN`.`ID_JABATAN` = `T_LHKPN_JABATAN`.`ID_JABATAN`  AND `M_JABATAN`.`IS_ACTIVE` <> 0
                    LEFT JOIN `M_INST_SATKER` ON `M_INST_SATKER`.`INST_SATKERKD` = `M_JABATAN`.`INST_SATKERKD`
                    LEFT JOIN `M_UNIT_KERJA` ON `M_UNIT_KERJA`.`UK_ID` = `M_JABATAN`.`UK_ID`";

//        if ($cari['ENTRY_VIA'] == '0' || $cari['ENTRY_VIA'] == '1') {
//            $my_where[] = " T_LHKPN.ENTRY_VIA = '" . $cari['ENTRY_VIA'] . "' ";
//        } else {
//            $my_where[] = " T_LHKPN.entry_via <> '2' ";
//        }

        $my_where[] = "T_LHKPN.IS_ACTIVE = '1'";
        $my_where[] = "R_BA_PENGUMUMAN.STATUS_CETAK_PENGUMUMAN_PDF = '1'";

        if (@$cari['TAHUN']) {
            $my_where[] = "YEAR(tgl_lapor) = '" . $cari['TAHUN'] . "'";
        }

        // if (@$cari['UNITKERJA']) {
        //     $my_where[] = "UK_NAMA LIKE '%" . $cari['UNITKERJA'] . "%'";
        // }

        if (@$cari['LEMBAGA']) {
            $my_where[] = "M_INST_SATKER.INST_SATKERKD = '" . $cari['LEMBAGA'] . "'";
        }

        // if (@$cari['LEMBAGA_NAMA']) {
        //     $my_where[] = "M_INST_SATKER.INST_NAMA LIKE '%" . $cari['LEMBAGA_NAMA'] . "%'";
        // }
        
        if (@$cari['UNIT_KERJA']) {
            $my_where[] = "M_UNIT_KERJA.UK_ID = '" . $cari['UNIT_KERJA'] . "'";
        }

        if(@$cari['LEMBAGA']==14){
            $my_where[] = "M_UNIT_KERJA.UK_ID IN (51337,51336)";
        }
        
        if (@$cari['NAMA']) {
            $my_where[] = "(T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%' OR T_PN.NIK LIKE '%" . $cari['NAMA'] . "%')";
        }

        return [$my_where, $my_where_find, $my_select_string, $my_from, $my_join];
    }

    function list_announ_lhkpn_pnwl($cari, $limit, $offset = 0) {

        
        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_announ_lhkpn_pnwl($cari, $limit, $offset, FALSE);


        if (@$cari['JENIS']) {
            $my_where_find[] = "T_LHKPN.JENIS_LAPORAN = '" . $cari['JENIS'] . "'";
        }

        if (@$cari['NHK'] != '') {
            $my_where_find[] = "T_PN.NHK = '" . $cari['NHK'] . "'";
        }

//        $status = '1';
//        if (@$cari['STATUS'] != '') {
//            $status = $cari['STATUS'];
//        }
//
//        $my_where[] = "T_LHKPN.STATUS = '" . $status . "'";


        $order_by = " T_PN.NAMA asc ";
        $order_by = " R_BA_PENGUMUMAN.ID_BAP desc ";
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by);
    
//        echo $sql;
//        exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
//        $this->db->order_by('T_PN.NAMA', 'asc');
    }

    /////////////////////////////////PERAN SERTA MASYARAKAT//////////////////////////////
    public function load_data_pelaporan($offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $result = FALSE;
        $total_rows = 0;
        $cari_advance = $this->input->get('CARI');
        $sql_where = " 1=1 AND ";
        // if($this->role == '3' || $this->role == '4'){
        //     $cari_advance["INSTANSI"] = $this->ins;
        // }
        if($cari_advance["KODE_PENGADUAN"]){
            $cari_kode = str_replace('PSM-','',$cari_advance["KODE_PENGADUAN"]);
        }

        if($cari_advance["TANGGAL_PENGADUAN"]){
            $tanggal_cut = substr($cari_advance["TANGGAL_PENGADUAN"],0,2);
            $tahun_cut = substr($cari_advance["TANGGAL_PENGADUAN"],-4);
            $bulan_cut = substr($cari_advance["TANGGAL_PENGADUAN"],3,-5);
            $tanggal_pengaduan = $tahun_cut.'-'.$bulan_cut.'-'.$tanggal_cut;
        }
        
        if($cari_advance["STATUS"]==9){
            $cari_advance["STATUS"]=null;
        }
        if ($cari_advance) {
            $condition_tanggal = array_key_exists("TANGGAL_PENGADUAN", $cari_advance) && $cari_advance["TANGGAL_PENGADUAN"] != '' ? "tlr.CREATED_TIME like '%" . $tanggal_pengaduan . "%'" : " 1";
            $condition_kode = array_key_exists("KODE_PENGADUAN", $cari_advance) && $cari_advance["KODE_PENGADUAN"] != '' ? "tlr.ID_PELAPORAN ='" . $cari_kode . "'" : " 1";
            $condition_instansi = array_key_exists("INSTANSI", $cari_advance) && $cari_advance["INSTANSI"] != '' ? "mj.INST_SATKERKD ='" . $cari_advance["INSTANSI"] . "'" : " 1";
            $condition_cari_keyword = array_key_exists("TEXT", $cari_advance) && $cari_advance["TEXT"] != '' ? "  p.NAMA like '%" . $cari_advance["TEXT"] . "%' OR p.NIK like '%" . $cari_advance["TEXT"] . "%'" : " 1";
            $condition_status = array_key_exists("STATUS", $cari_advance) && $cari_advance["STATUS"] != '' ? "tlr.IS_VERIFICATION = '". $cari_advance["STATUS"] . "'" : " 1";
            // $sql_where .= "  " . $condition_instansi . " and " . $condition_cari_keyword . " and ". $condition_status . " ";
            $sql_where .= "  " . $condition_status . " and ". $condition_cari_keyword . " and ". $condition_instansi . " and ". $condition_kode . " and ". $condition_tanggal . " ";
        }
        $sql_where .= " AND tlr.IS_ACTIVE = 1 "; 
        /////cek total baris//////
        $this->db->select("count(ID_PELAPORAN) as cnt");
        $this->db->from('t_lhkpn_pelaporan tlr');
        $this->db->where($sql_where);
        $this->db->join('t_lhkpn as l', 'l.id_lhkpn = tlr.id_lhkpn', 'LEFT');
        $this->db->join('t_pn as p', 'p.id_pn = l.id_pn', 'LEFT');
        $this->db->join('t_lhkpn_jabatan as j', 'j.id_lhkpn = tlr.id_lhkpn and j.IS_PRIMARY = "1"', 'LEFT');
        $this->db->join('M_JABATAN as mj', 'mj.ID_JABATAN = j.ID_JABATAN', 'LEFT');
        $queryCount = $this->db->get();
        if ($queryCount) {
            $result = $queryCount->row();
            if ($result) {
                $total_rows = $result->cnt;
            }
        }
        /////ambil data///////
        $this->db->select("tlr.*,p.NAMA,mis.INST_NAMA,mj.NAMA_JABATAN");
        $this->db->from('t_lhkpn_pelaporan tlr');
        $this->db->join('t_lhkpn as l', 'l.id_lhkpn = tlr.id_lhkpn', 'LEFT');
        $this->db->join('t_pn as p', 'p.id_pn = l.id_pn', 'LEFT');
        $this->db->join('t_lhkpn_jabatan as j', 'j.id_lhkpn = tlr.id_lhkpn and j.IS_PRIMARY = "1"', 'LEFT');
        $this->db->join('M_JABATAN as mj', 'mj.ID_JABATAN = j.ID_JABATAN', 'LEFT');
        $this->db->where($sql_where);
        $this->db->join('M_INST_SATKER as mis', 'mis.INST_SATKERKD = mj.INST_SATKERKD', 'LEFT');
        $this->db->join('M_UNIT_KERJA as muk', 'muk.UK_ID = mj.UK_ID', 'LEFT');
        $this->db->join('M_BIDANG as mb', 'mb.BDG_ID = mis.INST_BDG_ID', 'LEFT');
        $this->db->order_by('tlr.ID_PELAPORAN', 'desc');
        if ($limit_mode) {
            $query = $this->db->get(null,$rowperpage, $offset);
        } else {
            $query = $this->db->get();
        }

        if ($query) {
            $result = $query->result();
        }

        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $result[$key]->encrypt_id = encrypt_username($item->ID_PELAPORAN);
                $result[$key]->CREATED_TIME_INDONESIA = tgl_format($item->CREATED_TIME);
                $i++;
            }
        }
        return (object) array("total_rows" => $total_rows, "result" => $result);
        exit;
    }


    public function save_data_telaah($action){
        $ID = encrypt_username($this->input->post('token_pelaporan',TRUE),'d');
        if(!$ID){
            return 9;
        }
        switch($action){
            case "doinsert":
                $state = array(
                    'IS_VERIFICATION' => $this->input->post('STATUS_PEMERIKSA', TRUE),
                    'KETERANGAN_PEMERIKSA' => $this->input->post('KETERANGAN_PEMERIKSA', TRUE),
                    'UPDATED_TIME' => date("Y-m-d H:i:s"),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->where('ID_PELAPORAN = '.$ID);
                $result = $this->db->update('t_lhkpn_pelaporan',$state);
                break;
        }
        return $result;
    }




    /////////////////////////////////PENCARIAN PENGUMUMAN DI HALAMAN NON LOGIN PADA USER CONTROLLER//////////////////////////////
    function build_query_list_announ_lhkpn_pnwl_v2($cari, $limit, $offset = 0) {
        $my_where = [];
        $my_where_find = [];

        $my_select_string = [];

        
        $my_select_string[] = " T_LHKPN.ID_LHKPN,
                                T_LHKPN.tgl_kirim_final,
                                T_LHKPN.tgl_lapor,
                                T_LHKPN.JENIS_LAPORAN,
                                T_LHKPN.entry_via,
                                T_PN.ID_PN,
                                T_PN.NIK,
                                T_PN.NAMA,
                                M_INST_SATKER.INST_NAMA,
                                M_UNIT_KERJA.UK_NAMA,
                                M_JABATAN.NAMA_JABATAN,
                                T_BA_PENGUMUMAN.TGL_BA_PENGUMUMAN,
                                R_BA_PENGUMUMAN.STATUS_CETAK_PENGUMUMAN_PDF,
                                T_LHKPN.ID_LHKPN, 
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
                                (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6,
                                (SELECT SUM(SALDO_HUTANG) FROM t_lhkpn_hutang WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') T7";
        $my_from = " FROM T_LHKPN ";

        $my_join = "LEFT JOIN `R_BA_PENGUMUMAN` ON `R_BA_PENGUMUMAN`.`ID_LHKPN` = `T_LHKPN`.`ID_LHKPN`
                    LEFT JOIN `T_BA_PENGUMUMAN` ON `R_BA_PENGUMUMAN`.`ID_BAP` = `T_BA_PENGUMUMAN`.`ID_BAP`
                    LEFT JOIN `T_PN` ON `T_LHKPN`.`ID_PN` = `T_PN`.`ID_PN`
                    LEFT JOIN `T_LHKPN_JABATAN` ON `T_LHKPN_JABATAN`.`ID_LHKPN` = `T_LHKPN`.`ID_LHKPN` AND `T_LHKPN_JABATAN`.`IS_PRIMARY` = '1'
                    LEFT JOIN `T_LHKPN_DATA_PRIBADI` ON `T_LHKPN_DATA_PRIBADI`.`ID_LHKPN` = `T_LHKPN`.`ID_LHKPN`
                    LEFT JOIN `M_JABATAN` ON `M_JABATAN`.`ID_JABATAN` = `T_LHKPN_JABATAN`.`ID_JABATAN` AND `M_JABATAN`.`IS_ACTIVE` <> 0
                    LEFT JOIN `M_INST_SATKER` ON `M_INST_SATKER`.`INST_SATKERKD` = `M_JABATAN`.`INST_SATKERKD`
                    LEFT JOIN `M_UNIT_KERJA` ON `M_UNIT_KERJA`.`UK_ID` = `M_JABATAN`.`UK_ID`";


//        if ($cari['ENTRY_VIA'] == '0' || $cari['ENTRY_VIA'] == '1') {
//            $my_where[] = " T_LHKPN.ENTRY_VIA = '" . $cari['ENTRY_VIA'] . "' ";
//        } else {
//            $my_where[] = " T_LHKPN.entry_via <> '2' ";
//        }

        $my_where[] = "T_LHKPN.IS_ACTIVE = '1'";
        $my_where[] = "(CASE WHEN T_LHKPN.entry_via = '2' THEN TRUE ELSE R_BA_PENGUMUMAN.STATUS_CETAK_PENGUMUMAN_PDF = '1' END)";

        if (@$cari['TAHUN']) {
            $my_where[] = "YEAR(tgl_lapor) = '" . $cari['TAHUN'] . "'";
        }

        // if (@$cari['UNITKERJA']) {
        //     $my_where[] = "UK_NAMA LIKE '%" . $cari['UNITKERJA'] . "%'";
        // }

        if (@$cari['LEMBAGA']) {
            $my_where[] = "M_INST_SATKER.INST_SATKERKD = '" . $cari['LEMBAGA'] . "'";
        }

        // if (@$cari['LEMBAGA_NAMA']) {
        //     $my_where[] = "M_INST_SATKER.INST_NAMA LIKE '%" . $cari['LEMBAGA_NAMA'] . "%'";
        // }
        
        if (@$cari['UNIT_KERJA']) {
            $my_where[] = "M_UNIT_KERJA.UK_ID = '" . $cari['UNIT_KERJA'] . "'";
        }

        if(@$cari['LEMBAGA']==14){
            $my_where[] = "M_UNIT_KERJA.UK_ID IN (51337,51336)";
        }
        
        if (@$cari['NAMA']) {
            $my_where[] = "(T_PN.NAMA LIKE '%" . $cari['NAMA'] . "%' OR T_PN.NIK LIKE '%" . $cari['NAMA'] . "%')";
        }

        return [$my_where, $my_where_find, $my_select_string, $my_from, $my_join];
    }

    function list_announ_lhkpn_pnwl_v2($cari, $limit, $offset = 0) {

        
        list($my_where, $my_where_find, $my_select_string, $my_from, $my_join) = $this->build_query_list_announ_lhkpn_pnwl_v2($cari, $limit, $offset, FALSE);


        if (@$cari['JENIS']) {
            $my_where_find[] = "T_LHKPN.JENIS_LAPORAN = '" . $cari['JENIS'] . "'";
        }

        if (@$cari['NHK'] != '') {
            $my_where_find[] = "T_PN.NHK = '" . $cari['NHK'] . "'";
        }

//        $status = '1';
//        if (@$cari['STATUS'] != '') {
//            $status = $cari['STATUS'];
//        }
//
//        $my_where[] = "T_LHKPN.STATUS = '" . $status . "'";


        $order_by = " T_PN.NAMA asc ";
        $order_by = " R_BA_PENGUMUMAN.ID_BAP desc ";
        list($sql, $sql_count) = $this->build_list_query_verifikasi($my_where, $my_where_find, $my_select_string, $my_from, $my_join, $limit, $offset, $order_by);
    
//        echo $sql;
//        exit;

        return $this->execute_list_verifikasi_lhkpn_query($sql, $sql_count);
//        $this->db->order_by('T_PN.NAMA', 'asc');
    }

    public function load_data_analisa_kas($offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $result = FALSE;
        $total_rows = 0;

        /////cek total baris//////
        $this->db->select("count(ID_LHKPN) as cnt");
        $this->db->from('v_analisa_harta_kas');
        $queryCount = $this->db->get();
        if ($queryCount) {
            $result = $queryCount->row();
            if ($result) {
                $total_rows = $result->cnt;
            }
        }

        /////ambil data///////
        $this->db->select("*");
        $this->db->order_by('ID_LHKPN','desc');
        $this->db->limit($rowperpage, $offset);

        if ($limit_mode) {
             $this->db->limit($rowperpage, $offset);
        } else {
             $this->db->get();
        }

        $query = $this->db->get('v_analisa_harta_kas');

        if ($query) {
            $result = $query->result();
        }

        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $result[$key]->no_rekening_dekrip = strlen($item->NOMOR_REKENING)>=20?encrypt_username($item->NOMOR_REKENING, 'd'):$item->NOMOR_REKENING;
                $result[$key]->nama_bank_dekrip = strlen($item->NAMA_BANK)>=20?encrypt_username($item->NAMA_BANK, 'd'):$item->NAMA_BANK;
                $i++;
            }
        }

        return (object) array("total_rows" => $total_rows, "result" => $result);
        exit;
    }

    public function cetak_analisa_kas($offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $result = FALSE;
        $total_rows = 0;

        $my_view = 'v_analisa_harta_kas';

        /////cek total baris//////
        $this->db->select("count(ID_LHKPN) as cnt");
        $this->db->from($my_view);
        $queryCount = $this->db->get();
        if ($queryCount) {
            $result = $queryCount->row();
            if ($result) {
                $total_rows = $result->cnt;
            }
        }
        
        /////ambil data///////
        $this->db->select("*");
        $this->db->order_by('ID_LHKPN','desc');
        $query = $this->db->get($my_view);

        if ($query) {
            $result = $query->result();
        }

        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $result[$key]->no_rekening_dekrip = strlen($item->NOMOR_REKENING)>=20?encrypt_username($item->NOMOR_REKENING, 'd'):$item->NOMOR_REKENING;
                $result[$key]->nama_bank_dekrip = strlen($item->NAMA_BANK)>=20?encrypt_username($item->NAMA_BANK, 'd'):$item->NAMA_BANK;
                $i++;
            }
        }

        return $query;
    }

    public function get_lhkpn_announ_by_pn($id_pn, $min_tahun_lapor = false, $max_tahun_lapor = false)
    {
        $this->db->select('
            lhkpn.*
        ');
        $this->db->join('r_ba_pengumuman as bap', 'bap.id_lhkpn = lhkpn.id_lhkpn', 'left');
        $this->db->join('t_ba_pengumuman as tbap', 'tbap.id_bap = bap.id_bap', 'left');
        $this->db->join('t_pn as pn', 'pn.id_pn = lhkpn.id_pn', 'left');
        $this->db->join('t_lhkpn_jabatan as lhkpn_jabatan', 'lhkpn_jabatan.id_lhkpn = lhkpn.id_lhkpn and lhkpn_jabatan.is_primary = "1"', 'left');
        $this->db->join('t_lhkpn_data_pribadi as lhkpn_data_pribadi', 'lhkpn_data_pribadi.id_lhkpn = lhkpn.id_lhkpn', 'left');
        $this->db->join('m_jabatan as jabatan', 'jabatan.id_jabatan = lhkpn_jabatan.id_jabatan', 'left');
        $this->db->join('m_inst_satker as satker', 'satker.inst_satkerkd = jabatan.inst_satkerkd', 'left');
        $this->db->join('m_unit_kerja as unit_kerja', 'unit_kerja.uk_id = jabatan.uk_id', 'left');
        $this->db->where('lhkpn.id_pn', $id_pn);
        $this->db->where('lhkpn.IS_ACTIVE', '1');
        $this->db->where('case when lhkpn.entry_via = 2 then true else bap.STATUS_CETAK_PENGUMUMAN_PDF = 1 END', NULL, FALSE);

        if ($min_tahun_lapor) {
            $this->db->where('year(lhkpn.tgl_lapor) >=', $min_tahun_lapor);
        }
        if ($max_tahun_lapor) {
            $this->db->where('year(lhkpn.tgl_lapor) <=', $max_tahun_lapor);
        }
        $this->db->order_by('lhkpn.ID_LHKPN', 'asc');
        $lhkpn = $this->db->get('t_lhkpn lhkpn')->result();
        
        return $lhkpn;
    }

    public function get_all_harta_by_pn($id_pn, $min_tahun_lapor = false, $max_tahun_lapor = false)
    {
        $lhkpn = $this->get_lhkpn_announ_by_pn($id_pn, $min_tahun_lapor, $max_tahun_lapor);
        $id_lhkpn = [];
        foreach ($lhkpn as $key => $value) {
            $id_lhkpn[] = $value->ID_LHKPN;
        }
        $result = [];
        $result['dhb'] = $this->get_harta_bergerak($id_lhkpn);
        $result['dhtb'] = $this->get_harta_tidak_bergerak($id_lhkpn);
        $result['dhbl'] = $this->get_harta_bergerak_lain($id_lhkpn);
        $result['dhsb'] = $this->get_harta_surat_berharga($id_lhkpn);
        $result['dhl'] = $this->get_harta_lainnya($id_lhkpn);
        $result['dhk'] = $this->get_harta_kas($id_lhkpn);
        $result['dh'] = $this->get_hutang($id_lhkpn);
        // return $id_lhkpn;
        return $result;
    }
    
    public function get_harta_by_lhkpn($id_lhkpn)
    {
        if (!is_array($id_lhkpn)) $id_lhkpn = array($id_lhkpn);
        
        $result = [];
        $result['dhb'] = $this->get_harta_bergerak($id_lhkpn);
        $result['dhtb'] = $this->get_harta_tidak_bergerak($id_lhkpn);
        $result['dhbl'] = $this->get_harta_bergerak_lain($id_lhkpn);
        $result['dhsb'] = $this->get_harta_surat_berharga($id_lhkpn);
        $result['dhl'] = $this->get_harta_lainnya($id_lhkpn);
        $result['dhk'] = $this->get_harta_kas($id_lhkpn);
        $result['dh'] = $this->get_hutang($id_lhkpn);
        // return $id_lhkpn;
        return $result;
    }

    public function get_harta_bergerak($id_lhkpn)
    {
        if (!is_array($id_lhkpn)) $id_lhkpn = array($id_lhkpn);

        $table = 't_lhkpn_harta_bergerak hb';
        $this->db->select('
            hb.ID, hb.ID_HARTA, hb.ID_LHKPN, hb.Previous_ID, hb.NILAI_PELAPORAN, hb.NAMA, hb.MEREK, hb.MODEL, hb.TAHUN_PEMBUATAN,
            asal.ASAL_USUL,
            lhkpn.tgl_lapor, lhkpn.tgl_kirim, lhkpn.tgl_kirim_final,
            jenis_harta.NAMA as JENIS
        ');
        $this->db->join('t_lhkpn as lhkpn', 'hb.ID_LHKPN = lhkpn.ID_LHKPN');
        $this->db->join('m_asal_usul as asal', 'hb.ASAL_USUL = asal.ID_ASAL_USUL');
        $this->db->join('m_jenis_harta as jenis_harta', 'hb.KODE_JENIS = jenis_harta.ID_JENIS_HARTA');
        $this->db->where_in('hb.ID_LHKPN', $id_lhkpn)
            ->where('hb.IS_PELEPASAN', 0)
            ->where('hb.IS_ACTIVE', 1);
        $harta = $this->db->get($table);
        $harta = $harta->result();

        if ($harta) {
            $total = 0;
            $i = 0;
            foreach ($harta as $val) {
                $total += $val->NILAI_PELAPORAN;
                $harta[$i]->DESKRIPSI = $val->JENIS . ', ' . $val->MEREK . ' ' . $val->MODEL . ' Tahun ' . $val->TAHUN_PEMBUATAN . ', ' . $val->ASAL_USUL;
                $i++;
            }
            
            // $harta['TOTAL_HARTA'] = $total;
        }
        return $harta;
    }

    public function get_harta_tidak_bergerak($id_lhkpn)
    {
        if (!is_array($id_lhkpn)) $id_lhkpn = array($id_lhkpn);

        $table = 't_lhkpn_harta_tidak_bergerak htb';
        $this->db->select('
            htb.ID, htb.ID_HARTA, htb.ID_LHKPN, htb.Previous_ID, htb.NILAI_PELAPORAN, JALAN, htb.LUAS_TANAH, htb.NEGARA, htb.LUAS_BANGUNAN,
            negara.NAMA_NEGARA, htb.KAB_KOT,
            lhkpn.tgl_lapor, lhkpn.tgl_kirim, lhkpn.tgl_kirim_final,
            asal.ASAL_USUL
        ');
        $this->db->join('t_lhkpn as lhkpn', 'htb.ID_LHKPN = lhkpn.ID_LHKPN');
        $this->db->join('m_negara as negara', 'htb.ID_NEGARA = negara.ID');
        $this->db->join('m_asal_usul as asal', 'htb.ASAL_USUL = asal.ID_ASAL_USUL');
        $this->db->join('m_pemanfaatan as faedah', 'faedah.ID_PEMANFAATAN = htb.PEMANFAATAN');
        $this->db->where_in('htb.ID_LHKPN', $id_lhkpn)
            ->where('htb.IS_PELEPASAN', 0)
            ->where('htb.IS_ACTIVE', 1);
        $harta = $this->db->get($table);
        $harta = $harta->result();
        // print_r ($this->db->last_query()) && exit;

        if ($harta) {
            $total = 0;
            $i = 0;
            foreach ($harta as $val) {
                // $total += $val->NILAI_PELAPORAN;
                $tmp = $val->NEGARA == 2 ? 'NEGARA '.$val->NAMA_NEGARA : 'KAB / KOTA '.$val->KAB_KOT;
                if ($val->LUAS_TANAH == NULL || $val->LUAS_TANAH == '') {
                    $luas_tanah = '-';
                } else {
                    $luas_tanah = $val->LUAS_TANAH;
                }
                if ($val->LUAS_BANGUNAN == NULL || $val->LUAS_BANGUNAN == '') {
                    $luas_bangunan = '-';
                } else {
                    $luas_bangunan = $val->LUAS_BANGUNAN;
                }
                if ($val->LUAS_BANGUNAN !== "0" && $val->LUAS_TANAH !== "0") {
                    $harta[$i]->DESKRIPSI = 'Tanah dan Bangunan Seluas ' . $luas_tanah . ' m2/' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $val->ASAL_USUL;
                } else if ($val->LUAS_TANAH !== "0" && $val->LUAS_BANGUNAN == "0") {
                    $harta[$i]->DESKRIPSI = 'Tanah Seluas ' . $luas_tanah . ' m2 di ' . $tmp . ', ' . $val->ASAL_USUL;
                } else {
                    $harta[$i]->DESKRIPSI = 'Bangunan Seluas ' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $val->ASAL_USUL;
                }

                $i++;
            }
            // $harta['TOTAL_HARTA'] = $total;
        }
        
        return $harta;
    }

    public function get_harta_bergerak_lain($id_lhkpn)
    {
        if (!is_array($id_lhkpn)) $id_lhkpn = array($id_lhkpn);

        $table = 't_lhkpn_harta_bergerak_lain hbl';
        $this->db->select('
            hbl.ID, hbl.ID_HARTA, hbl.ID_LHKPN, hbl.Previous_ID, hbl.NILAI_PELAPORAN,
            lhkpn.tgl_lapor, lhkpn.tgl_kirim, lhkpn.tgl_kirim_final
        ');
        $this->db->join('t_lhkpn as lhkpn', 'hbl.ID_LHKPN = lhkpn.ID_LHKPN');
        $this->db->where_in('hbl.ID_LHKPN', $id_lhkpn)
            ->where('hbl.IS_PELEPASAN', 0)
            ->where('hbl.IS_ACTIVE', 1);
        $harta = $this->db->get($table);
        $harta = $harta->result();

        if ($harta) {
            $total = 0;
            foreach ($harta as $val) {
                $total += $val->NILAI_PELAPORAN;
            }
            // $harta['TOTAL_HARTA'] = $total;
        }
        return $harta;
    }

    public function get_harta_surat_berharga($id_lhkpn)
    {
        if (!is_array($id_lhkpn)) $id_lhkpn = array($id_lhkpn);

        $table = 't_lhkpn_harta_surat_berharga hsb';
        $this->db->select('
            hsb.ID, hsb.ID_HARTA, hsb.ID_LHKPN, hsb.Previous_ID, hsb.NILAI_PELAPORAN,
            lhkpn.tgl_lapor, lhkpn.tgl_kirim, lhkpn.tgl_kirim_final
        ');
        $this->db->select('
            hsb.ID, hsb.ID_HARTA, hsb.ID_LHKPN, hsb.ID_HARTA, hsb.Previous_ID, hsb.NILAI_PELAPORAN,
            lhkpn.tgl_lapor, lhkpn.tgl_kirim, lhkpn.tgl_kirim_final
        ');
        $this->db->join('t_lhkpn as lhkpn', 'hsb.ID_LHKPN = lhkpn.ID_LHKPN');
        $this->db->where_in('hsb.ID_LHKPN', $id_lhkpn)
            ->where('hsb.IS_PELEPASAN', 0)
            ->where('hsb.IS_ACTIVE', 1);
        $harta = $this->db->get($table);
        $harta = $harta->result();

        if ($harta) {
            $total = 0;
            foreach ($harta as $val) {
                $total += $val->NILAI_PELAPORAN;
            }
            // $harta['TOTAL_HARTA'] = $total;
        }
        return $harta;
    }

    public function get_harta_lainnya($id_lhkpn)
    {
        if (!is_array($id_lhkpn)) $id_lhkpn = array($id_lhkpn);

        $table = 't_lhkpn_harta_lainnya hl';
        $this->db->select('
            hl.ID, hl.ID_HARTA, hl.ID_LHKPN, hl.ID_HARTA, hl.Previous_ID, hl.NILAI_PELAPORAN,
            lhkpn.tgl_lapor, lhkpn.tgl_kirim, lhkpn.tgl_kirim_final
        ');
        $this->db->join('t_lhkpn as lhkpn', 'hl.ID_LHKPN = lhkpn.ID_LHKPN');
        $this->db->where_in('hl.ID_LHKPN', $id_lhkpn)
            ->where('hl.IS_PELEPASAN', '0')
            ->where('hl.IS_ACTIVE', '1');
        $harta = $this->db->get($table);
        $harta = $harta->result();

        if ($harta) {
            $total = 0;
            foreach ($harta as $val) {
                $total += $val->NILAI_PELAPORAN;
            }
            // $harta['TOTAL_HARTA'] = $total;
        }
        return $harta;
    }

    public function get_harta_kas($id_lhkpn)
    {
        if (!is_array($id_lhkpn)) $id_lhkpn = array($id_lhkpn);

        $table = 't_lhkpn_harta_kas hk';
        $this->db->select('
            hk.ID, hk.ID_HARTA, hk.ID_LHKPN, hk.Previous_ID, hk.NILAI_SALDO, hk.NILAI_EQUIVALEN,
            lhkpn.tgl_lapor, lhkpn.tgl_kirim, lhkpn.tgl_kirim_final
        ');
        $this->db->join('t_lhkpn as lhkpn', 'hk.ID_LHKPN = lhkpn.ID_LHKPN', 'left');
        $this->db->where_in('hk.ID_LHKPN', $id_lhkpn)
            ->where('hk.IS_PELEPASAN', 0)
            ->where('hk.IS_ACTIVE', 1);
        $harta = $this->db->get($table);
        $harta = $harta->result();

        if ($harta) {
            $total = 0;
            foreach ($harta as $val) {
                $total += $val->NILAI_EQUIVALEN;
            }
            // $harta['TOTAL_HARTA'] = $total;
        }
        return $harta;
    }

    public function get_hutang($id_lhkpn)
    {
        if (!is_array($id_lhkpn)) $id_lhkpn = array($id_lhkpn);

        $table = 't_lhkpn_hutang hutang';
        $this->db->select('
            hutang.ID_HUTANG, hutang.ID_HARTA, hutang.ID_LHKPN, hutang.Previous_ID, hutang.SALDO_HUTANG,
            lhkpn.tgl_lapor, lhkpn.tgl_kirim, lhkpn.tgl_kirim_final
        ');
        $this->db->join('t_lhkpn as lhkpn', 'hutang.ID_LHKPN = lhkpn.ID_LHKPN');
        $this->db->where_in('hutang.ID_LHKPN', $id_lhkpn)
            ->where('hutang.IS_ACTIVE', 1);
        $harta = $this->db->get($table);
        $harta = $harta->result();

        if ($harta) {
            $total = 0;
            foreach ($harta as $val) {
                $total += $val->NILAI_PELAPORAN;
            }
            // $harta['TOTAL_HARTA'] = $total;
        }
        return $harta;
    }
    
    public function get_harta_all($id_lhkpn)
    {
        $jenis_harta = $this->jenis_harta();
        $result = [];
        foreach ($jenis_harta as $key => $value) {
            $fun_name ='get_' . str_replace(' ', '_', strtolower ($value));
            $result[$key] = $this->$fun_name($id_lhkpn);
        }
        return $result;
    }

    public function jenis_harta()
    {
        return [
            'dhb' => 'Harta Bergerak',
            'dhtb' => 'Harta Tidak Bergerak',
            'dhbl' => 'Harta Bergerak Lain',
            'dhsb' => 'Harta Surat Berharga',
            'dhl' => 'Harta Lainnya',
            'dhk' => 'Harta Kas',
            'dh' => 'Hutang'
        ];
    }

    public function jabatan($id_lhkpn)
    {
        $this->db->select('
            lhkpn.JENIS_LAPORAN,
            lhkpn.tgl_lapor,
            lhkpn.tgl_kirim,
            lhkpn.tgl_kirim_final,
            lhkpn.entry_via,
            pn.NAMA,
            pn.GELAR_DEPAN,
            pn.GELAR_BELAKANG,
            pn.NHK,
            jbt.ID_LHKPN,
            jbt.DESKRIPSI_JABATAN,
            j.NAMA_JABATAN,
            uk.UK_NAMA,
            suk.SUK_NAMA,
            satker.INST_NAMA
        ');
        
        $this->db->join('t_lhkpn_jabatan as jbt', 'jbt.id_lhkpn = lhkpn.id_lhkpn', 'left');
        $this->db->join('t_pn as pn', 'lhkpn.ID_PN = pn.ID_PN', 'left');
        $this->db->join('m_jabatan as j', 'jbt.ID_JABATAN = j.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker as satker', 'j.INST_SATKERKD = satker.INST_SATKERKD', 'left');
        $this->db->join('m_unit_kerja as uk', 'j.UK_ID = uk.UK_ID', 'left');
        $this->db->join('m_sub_unit_kerja as suk', 'j.SUK_ID = suk.SUK_ID', 'left');
        $this->db->where('lhkpn.ID_LHKPN', $id_lhkpn);
        $this->db->order_by('jbt.ID_LHKPN', 'asc');
        $this->db->limit(1);
        $result = $this->db->get('t_lhkpn lhkpn')->result();
        if ($result) {
            $response = $result[0];
            $jenis_laporan = $response->JENIS_LAPORAN;
            $response->DESKRIPSI_JENIS_LAPORAN = ($jenis_laporan == 4 ? "Periodik " : ($datapn->JENIS_LAPORAN == '5' ? 'Klarifikasi' : 'Khusus')) . " - " . show_jenis_laporan_khusus($jenis_laporan, $response->tgl_lapor, tgl_format($response->tgl_lapor));
        }
        // dd ($this->db->last_query());
        return $result ? $result[0] : null;
    }

    public function tracking_lhkpn_cetak($cari = NULL)
    {  
        $my_select_string = "   M_JABATAN.NAMA_JABATAN,
                                M_INST_SATKER.INST_NAMA,
                                M_UNIT_KERJA.UK_NAMA,
                                M_SUB_UNIT_KERJA.SUK_NAMA,
                                T_PN.NAMA, T_PN.NIK, T_PN.EMAIL, T_PN.NHK, T_PN.TGL_LAHIR,
                                T_USER.LAST_LOGIN,
                                T_LHKPN.ALASAN,
                                T_LHKPN.entry_via,
                                T_LHKPN.ID_PN,
                                T_LHKPN.IS_ACTIVE,
                                T_LHKPN.JENIS_LAPORAN,
                                T_LHKPN.STATUS,
                                T_LHKPN.tgl_kirim,
                                T_LHKPN.tgl_kirim_final,
                                T_LHKPN.tgl_lapor,
                                T_LHKPN.USERNAME_ENTRI,
                                T_LHKPN.back_to_draft,
                                T_LHKPN.ID_LHKPN,
                                T_LHKPN.DIKEMBALIKAN ,
                                (SELECT SUM(T_LHKPN_HUTANG.SALDO_HUTANG) FROM T_LHKPN_HUTANG WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = 1) AS hutang,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = 1) hartirak,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = 1) suberga,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = 1) harlin,
                                (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = 1) kaseka,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = 1) harger2,
                                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = 1) harger ";
        $my_from = " FROM T_LHKPN ";

        $my_join = "LEFT JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_LHKPN = `T_LHKPN`.ID_LHKPN AND T_LHKPN_JABATAN.`IS_PRIMARY` = '1'
                    LEFT JOIN T_LHKPN_DATA_PRIBADI ON T_LHKPN_DATA_PRIBADI.ID_LHKPN = `T_LHKPN`.ID_LHKPN
                    LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                    LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD
                    LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID
                    LEFT JOIN M_SUB_UNIT_KERJA ON M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID
                    JOIN `T_PN`
                      ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN` AND `T_PN`.`IS_ACTIVE` <> '-1'
                    LEFT JOIN `T_USER`
                      ON `T_USER`.`USERNAME` = `T_PN`.`NIK`";

         $my_where = "T_LHKPN.IS_ACTIVE = '1' AND T_LHKPN.JENIS_LAPORAN <> '5'";

        if (@$cari->lembaga) {
            $my_where .= " AND M_INST_SATKER.INST_SATKERKD = '" . $cari->lembaga . "'";
        }
        if (@$cari->uk) {
            $my_where .= " AND M_UNIT_KERJA.UK_ID = '" . $cari->uk . "'";
        }

        if (@$cari->nama) {
            $my_where .= " AND (T_PN.NAMA LIKE '%" . $this->db->escape_like_str($cari->nama) . "%' ESCAPE '!' OR T_PN.NIK = '" . $this->db->escape_str($cari->nama) . "' OR T_PN.EMAIL = '" . $this->db->escape_str($cari->nama) . "')";
        }

        if (@$cari->tgl_lahir) {
            $my_where .= "T_LHKPN_DATA_PRIBADI.TANGGAL_LAHIR = '" . $cari->tgl_lahir . "'";
        }

        if (@$cari->nhk) {
            $my_where .= "T_PN.NHK = '" . $cari->nhk . "'";
        }

        $order_by = " T_LHKPN.tgl_lapor desc ";

        $sql = " SELECT " . $my_select_string . " " . $my_from . " " . $my_join . " WHERE " . $my_where ."  ORDER BY ". $order_by;
        $q = $this->db->query($sql);

        return $q->result();
    }
    public function listingSK($cari = NULL)
    {
        $select =  " M_JABATAN.NAMA_JABATAN,
                    M_INST_SATKER.INST_NAMA,
                    M_UNIT_KERJA.UK_NAMA,
                    T_PN.NAMA, T_PN.NIK, T_PN.EMAIL, T_PN.NHK, T_PN.TGL_LAHIR,
                    T_USER.LAST_LOGIN,
                    T_LHKPN.ALASAN,
                    T_LHKPN.entry_via,
                    T_LHKPN.ID_PN,
                    T_LHKPN.IS_ACTIVE,
                    T_LHKPN.JENIS_LAPORAN,
                    T_LHKPN.STATUS,
                    T_LHKPN.tgl_kirim,
                    T_LHKPN.tgl_kirim_final,
                    T_LHKPN.tgl_lapor,
                    T_LHKPN.USERNAME_ENTRI,
                    T_LHKPN.back_to_draft,
                    T_LHKPN.ID_LHKPN,
                    CASE WHEN fu_flag_sk_pn(T_LHKPN.ID_LHKPN) = '1' THEN
                        CASE WHEN fu_flag_sk_pasangan(T_LHKPN.ID_LHKPN) NOT LIKE '%0%' OR fu_flag_sk_pasangan(T_LHKPN.ID_LHKPN) IS NULL THEN
                            CASE WHEN `fu_flag_sk_anak_tanggungan`(T_LHKPN.ID_LHKPN, T_LHKPN.tgl_lapor) NOT LIKE '%0%' 
                                OR `fu_flag_sk_anak_tanggungan`(T_LHKPN.ID_LHKPN, T_LHKPN.tgl_lapor) IS NULL 
                                THEN 'Lengkap'
                            ELSE 'Tidak Lengkap' END
                        ELSE 'Tidak Lengkap' END
                    ELSE 'Tidak Lengkap' END AS Status_Kelengkapan,
                    T_LHKPN.DIKEMBALIKAN";

            $my_from = " FROM T_LHKPN ";
            //        $this->db->from('T_LHKPN');

            $my_join = "LEFT JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_LHKPN = `T_LHKPN`.ID_LHKPN AND T_LHKPN_JABATAN.`IS_PRIMARY` = '1'
            LEFT JOIN T_LHKPN_DATA_PRIBADI ON T_LHKPN_DATA_PRIBADI.ID_LHKPN = `T_LHKPN`.ID_LHKPN
            LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
            LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD
            LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID
            -- LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = M_JABATAN.KODE_ESELON

            -- LEFT JOIN `T_VERIFICATION`
            -- ON `T_LHKPN`.`ID_LHKPN` = `T_VERIFICATION`.`ID_LHKPN`
            JOIN `T_PN`
            ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN` AND `T_PN`.`IS_ACTIVE` <> '-1'
            LEFT JOIN `T_USER`
            ON `T_USER`.`USERNAME` = `T_PN`.`NIK`";

            $my_where = "T_LHKPN.IS_ACTIVE = '1' AND T_LHKPN.JENIS_LAPORAN <> '5' AND T_PN.NIK = '" . $this->db->escape_str($cari) . "' ";
            $order_by = " T_LHKPN.tgl_lapor desc ";

            $sql = " SELECT " . $select . " " . $my_from . " " . $my_join . " WHERE " . $my_where ."  ORDER BY ". $order_by;
            $q = $this->db->query($sql);

            return $q->row();
    }
}

