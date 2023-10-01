<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller User
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public $limit = 10;

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);
    }

    /** Home
     * 
     * @return html Home
     */
    public function index() {
        $this->makses->check_is_first();
        $this->load->model('mmenu', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('muser', '', TRUE);

        $this->mmenu->dpg = $this->input->get('dpg');
        if (!$this->mmenu->dpg) {
            $this->mmenu->dpg = FALSE;
        }

        $data = array(
            'roles' => $this->mrole->list_all()->result(),
            'menu' => $this->mmenu->get_list_menu(),
            'user' => $this->muser->get_by_id($this->session->userdata('ID_USER'))->row(),
        );
        $this->load->view('home', $data);
    }

    public function changepassword() {
        $this->load->view('change_password');
    }

    public function form_isian() {
        $this->load->view('form_isian');
    }

    public function dashboard_() {
        $this->load->view('dashboard_chart');
    }
    
    public function lws_nik($nik = FALSE){
        if($nik){
            echo encrypt_username($nik);
            exit;
        }
        echo "ga ada";
        exit;
    }

    /** Dashboard
     * 
     * @return html Dashboard
     */
    public function dashboard($thn = 0) {

        if ($this->input->get('show_profiler')) {
            $this->output->enable_profiler(TRUE);
        }

        $this->load->model('mglobal');
        $this->load->model('mlhkpn');
        $this->load->model('mmutasi', '', TRUE);
        $data_tahun = FALSE;

        $nik_user_login = $this->session->userdata('NIK');

        if ($thn == 0 && $nik_user_login && !is_null($nik_user_login)) {
            $data_tahun = $this->mlhkpn->dataTahun($this->session->userdata('NIK'));
            @$thn = $data_tahun ? $data_tahun[0]->tahun : FALSE;
        }

        $id_lhkpn = FALSE;
        if ($nik_user_login && !is_null($nik_user_login)) {
            @$id_lhkpn = $this->mglobal->get_data_all('T_PN', [
                        [
                            'table' => 'T_LHKPN',
                            'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'
                        ]
                            ], [
                        'T_PN.IS_ACTIVE' => '1',
                        'T_PN.NIK' => $nik_user_login,
                        'YEAR(TGL_LAPOR)' => $thn
                            ], 'ID_LHKPN')[0]->ID_LHKPN;
        }

        $whereperhitunganpengeluaran = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn'";
        $whereperhitunganpemaasukan = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn' ";

        $getGolongan1 = FALSE;
        $getGolongan2 = FALSE;
        $getPenka = FALSE;
        $getPemka = FALSE;
        if ($data_tahun != FALSE) {
            $getGolongan1 = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
            $getGolongan2 = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
            $getPenka = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS', $whereperhitunganpengeluaran);
            $getPemka = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $whereperhitunganpemaasukan);
        }

        $data = array(
            'getGolongan1' => $getGolongan1,
            'getGolongan2' => $getGolongan2,
            'getPenka' => $getPenka,
            'getPemka' => $getPemka,
            'item' => $this->mglobal->get_data_all('T_USER', null, ['ID_USER' => $this->session->userdata('ID_USER')], 'ID_USER, NAMA, EMAIL, from_unixtime(LAST_LOGIN) as LAST_LOGIN'),
            'mailbox' => $this->mglobal->count_data_all('T_PESAN_MASUK', null, ['IS_ACTIVE' => '1', 'ID_PENERIMA' => $this->session->userdata('ID_USER'), 'IS_READ' => '0']),
            'pn_wl_masuk' => $this->mmutasi->get_paged_list2(0, 0)->num_rows(),
            'tahuns' => $data_tahun,
            'tahun' => $thn
        );

        if ($this->input->get('show_profiler')) {
            //do nothing
        } else {
            $this->load->view('dashboard', $data);
        }
    }

    private function isDataEntry() {
        $role = $this->session->userdata('ID_ROLE');
        if ($role == '6') {
            return true;
        } else {
            return false;
        }
    }

    function jumlah_instansi($param1) {
        $sql = "
					SELECT G.ID_ROLE, G.INST_SATKERKD, G.JML, I.INST_NAMA, I.INST_AKRONIM FROM( SELECT ID_ROLE, INST_SATKERKD, COUNT(*) JML FROM ( SELECT USERNAME, INST_SATKERKD, SUBSTRING_INDEX(SUBSTRING_INDEX(t.ID_ROLE, ',', n.n), ',', -1) ID_ROLE FROM T_USER t CROSS JOIN (
       SELECT a.N + b.N * 10 + 1 n
         FROM (
		 		SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
		) a,(
				SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
		) b ORDER BY n
      ) n WHERE n.n <= 1 + (LENGTH(t.ID_ROLE) - LENGTH(REPLACE(t.ID_ROLE, ',', ''))) ORDER BY USERNAME, ID_ROLE ) F GROUP BY ID_ROLE, INST_SATKERKD ) G LEFT JOIN M_INST_SATKER I ON I.INST_SATKERKD = G.INST_SATKERKD WHERE G.INST_SATKERKD = $param1 ORDER BY I.INST_SATKERKD
				";



        //"SELECT COUNT(*) JUMLAH  FROM T_USER WHERE ID_ROLE = $param1 AND INST_SATKERKD = $param2";
        $rs = $this->db->query($sql)->result();
        return $rs;
    }

    public function lhkpndraft() {
        if ($this->session->userdata('IS_PN')) {
            $nik = $this->session->userdata('NIK');

            $sql = "SELECT ID_PN FROM T_PN WHERE NIK = $nik";
            $data = $this->db->query($sql)->result()[0];
            // $this->db->where(['T_LHKPN.ID_PN' => $data->ID_PN]);            
        }

        $this->db->start_cache();

        /*
          @todo dalam process perubahan
         */
        if ($this->isDataEntry()) {
            $this->db->select("T_LHKPNOFFLINE_PENERIMAAN.*
                        ,D.*
                        ,JABATAN.*
                        ,T_PN.*
                        ,T_LHKPN.*
                        ,CONCAT(YEAR(TGL_LAPOR),IF(T_LHKPN.JENIS_LAPORAN = '4','R','K'),NIK,T_LHKPN.ID_LHKPN) AS AGENDA", FALSE);
        } else {
            $this->db->select("T_LHKPNOFFLINE_PENERIMAAN.*
                        ,JABATAN.*
                        ,T_PN.*
                        ,T_LHKPN.*
                        ,CONCAT(YEAR(TGL_LAPOR),IF(T_LHKPN.JENIS_LAPORAN = '4','R','K'),NIK,T_LHKPN.ID_LHKPN) AS AGENDA", FALSE);
        }

        $this->db->from('T_LHKPN');
        // $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
        $this->db->join('(
                        select  ID_LHKPN AS ID_LHKPN_DIJABATAN, 
                            group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN, 
                            group_concat(
                            CONCAT(
                                IFNULL(T_LHKPN_JABATAN.ID,""),":58:",
                                IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                                IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                                IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":58:",
                                IFNULL(M_JABATAN.NAMA_JABATAN,""), 
                                "(", IFNULL(M_ESELON.ESELON,""), ") - ", 
                                IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ", 
                                IFNULL(M_INST_SATKER.INST_AKRONIM,"")
                            )
                        ) as NAMA_JABATAN
                            from T_LHKPN_JABATAN
                            LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                            LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA
                            LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA
                            LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
                            LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT
                            GROUP BY T_LHKPN_JABATAN.ID_LHKPN
                        ) JABATAN', 'JABATAN.ID_LHKPN_DIJABATAN = T_LHKPN.ID_LHKPN', 'left');
        $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
        $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPN.ID_LHKPN=T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'LEFT');
        // $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = T_LHKPN.LEMBAGA', 'left');
        //        if($this->isDataEntry()){
        $this->db->join('T_LHKPNOFFLINE_PENUGASAN_ENTRY D', 'D.ID_LHKPN = T_LHKPN.ID_LHKPN', 'LEFT');
        $this->db->where('(T_LHKPNOFFLINE_PENERIMAAN.USERNAME_KOORD_ENTRY = "' . $this->session->userdata('USERNAME') . '" OR USERNAME_ENTRI = "' . $this->session->userdata('USERNAME') . '" OR T_LHKPN.ID_PN = "' . @$this->session->userdata('ID_PN') . '")', NULL, FALSE);
        //        }

        $this->db->order_by("TGL_LAPOR", 'desc');

        $query = $this->db->get('', $this->limit, $this->offset);
        // display($this->db->last_query());exit();

        return $query->result();
        ;
        exit();
    }

}
