<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller Penyelenggara Negara
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class All_pn extends CI_Controller {

    // num of records per page
    public $limit = 10;

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
        $this->load->model('muser');
        $this->load->model('mpn');
        $this->username = $this->session->userdata('USERNAME');
        $this->instansi = $this->session->userdata('INST_SATKERKD');
        $this->ID_USER = $this->session->userdata('ID_USER');

        $this->role = $this->session->userdata('ID_ROLE');
        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_KPK, IS_INSTANSI, IS_USER_INSTANSI');
        $this->IS_KPK = $role[0]->IS_KPK;

        $this->makses->initialize();
        $this->makses->check_is_read();
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);

        // prepare search
        foreach ((array) @$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];

        $this->act = $this->input->post('act', TRUE);
        $this->remapSegment();
    }

    private function remapSegment() {
        $segs = $this->uri->segment_array();
        $i = 0;
        $map[] = 'index.php';
        foreach ($segs as $segment) {
            ++$i;
            $map[] = $segment;
            $this->segmentName[$i] = $segment;
            $this->segmentTo[$i] = implode('/', $map) . '/';
        }
    }

    /**
     * Penyelenggara Negara List
     * 
     * @return html Penyelenggara Negara List
     */
    public function index__($offset = 0, $cetak = false) {
        if (in_array($cetak, ['pdf', 'excel', 'word'])) {
            $this->iscetak = true;
            $this->limit = 0;
        }

        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_KPK, IS_INSTANSI, IS_USER_INSTANSI');

        $user_uk = $this->mglobal->get_data_by_id('t_user', 'ID_USER', $this->ID_USER);

//        display($this->session->userdata('IS_UK'));
        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
        $this->base_url = 'index.php/ereg/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/';
        $this->uri_segment = 4;
        $this->offset = $this->uri->segment($this->uri_segment);

        $this->db->start_cache();

        $this->db->select('
            P.ID_PN,
            JAB.NAMA_JABATAN N_JAB, SUK.SUK_NAMA N_SUK, UK.UK_NAMA N_UK, INTS.INST_NAMA,
            P.NIK, PJ.ID,
            PJ.ID_STATUS_AKHIR_JABAT STS_J,
            P.NAMA,
            P.GELAR_DEPAN,
            P.GELAR_BELAKANG,
            U.ID_USER,
            PJ.SUB_UNIT_KERJA,
            
            
            PJ.*
            ', false); // parameter false wajib
        $this->db->from('T_PN P');
        $this->db->join('T_USER U', 'U.USERNAME = P.NIK');
        $this->db->join('T_PN_JABATAN PJ', 'P.ID_PN = PJ.ID_PN', 'left');
//        $this->db->join('(SELECT MAX(ID) AS MID FROM T_PN_JABATAN GROUP BY ID_PN ) MPJ ', ' PJ.ID = MPJ.MID');
        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN', 'left');
        $this->db->join('m_sub_unit_kerja SUK', 'SUK.SUK_ID = JAB.SUK_ID', 'left');
        $this->db->join('m_unit_kerja UK', 'UK.UK_ID = JAB.UK_ID', 'left');
        $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = UK.UK_LEMBAGA_ID', 'left');

        $this->db->where(' P.IS_ACTIVE', '1');
        $this->db->where(' PJ.ID_STATUS_AKHIR_JABAT IN (0,10,11,15) ');
        $this->db->where(' PJ.IS_DELETED', '0');
        $this->db->where(' PJ.IS_CALON', '0');
        $this->db->where(' PJ.IS_ACTIVE', '1');
        $this->db->where(' PJ.IS_CURRENT', '1');
        $this->db->where(" PJ.ID IN (SELECT MAX(ID) AS MID FROM T_PN_JABATAN GROUP BY ID_PN) ", NULL, FALSE);

        if ($this->role > 2)
            $this->db->where('INTS.INST_SATKERKD', $this->instansi);

        if ($user_uk[0]->IS_UK == 1) {
            $this->db->where('JAB.UK_ID', $user_uk[0]->UK_ID);
        }

        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;
            $IS_KPK = $role[0]->IS_KPK;

            if ($inst == '1' || $user == '1') {
                $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
                $this->db->where("(INTS.INST_SATKERKD like '" . $INST_SATKERKD . "')", null, false);
            }
        }

        if (@$this->CARI['USEWHEREONLY']) {
            $this->db->where('P.NIK', $this->CARI['TEXT']);
        } else {
            if (@$this->CARI['TEXT']) {
                $this->db->where("(PJ.UNIT_KERJA LIKE '%" . $this->CARI['TEXT'] . "%' OR PJ.SUB_UNIT_KERJA LIKE '%" . $this->CARI['TEXT'] . "%' OR PJ.DESKRIPSI_JABATAN LIKE '%" . $this->CARI['TEXT'] . "%' OR P.NAMA LIKE '%" . $this->CARI['TEXT'] . "%' OR P.NIK LIKE '%" . $this->CARI['TEXT'] . "%')");
            }

            if (@$this->CARI['INST'] && @$this->CARI['INST'] != -99) {
                $this->db->where("V1.LEMBAGA like '" . $this->CARI['INST'] . "'", null, false);
            }
        }

        $this->db->order_by('P.NAMA', 'asc');
        $this->db->group_by('P.ID_PN'); // parameter false wajib

        $this->total_rows = $this->db->get('')->num_rows();
        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();
//        display($this->db->last_query());
        $this->end = $query->num_rows();
        $this->db->flush_cache();

        $havelhkpn = array();
        foreach ($this->items as $item) {
            $ID_PN[] = $item->ID_PN;
            $havelhkpn[$item->ID_PN] = false;
        }

        if (!empty($ID_PN)) {
            $ID_PN_SEL = implode($ID_PN, ',');
            $sql = "SELECT * FROM T_LHKPN WHERE ID_PN IN ($ID_PN_SEL)";
            $query = $this->db->query($sql)->result();
            foreach ($query as $lhkpn) {
                $havelhkpn[$lhkpn->ID_PN] = true;
            }
        }


        $data = array(
            'linkCetak' => 'index.php/ereg/all_pn/index/0',
            'titleCetak' => 'cetakpn',
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'CARI' => @$this->CARI,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E-reg' => 'index.php/dashboard/efilling',
                'PN' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
            'instansis' => $this->mglobal->get_data_all('M_INST_SATKER'),
            'status_akhir' => $this->mglobal->get_data_all('T_STATUS_AKHIR_JABAT', NULL, NULL, '*', NULL, ['ORDER', 'asc']),
            'IS_KPK' => @$IS_KPK,
            'havelhkpn' => $havelhkpn,
            'iscln' => 0,
                // 'data_pnwl' => $this->mglobal->get_data_pnwl_aktif()    
        );

        // load view
        if (@$this->iscetak) {
            ng::exportDataTo($data, $cetak, strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . 'index' . '_' . 'cetak', 'cetakpn');
        } else {
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
        }




        return;
        $this->load->model('mpn', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);

        // prepare paging
        $this->base_url = site_url(strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->uri_segment = 3;
        $this->offset = $this->uri->segment($this->uri_segment);

        // filter
        $cari = '';
        $id_instansi = '';
        $filter = '';
        if ($_POST && $this->input->post('table_search', TRUE) != '' || $this->input->post('INST', TRUE) != '99') {
            $cari = $this->input->post('table_search', TRUE);
            $id_instansi = $this->input->post('INST', TRUE);
            $filter = array(
                'NAMA' => $this->input->post('table_search', TRUE),
            );
        }

        // load and packing data
        $this->items = $this->mpn->get_all_paged_list2($this->limit, $this->offset, $filter, $id_instansi)->result();
        $this->total_rows = $this->mpn->count_all2($this->limit, $this->offset, $filter, $id_instansi);
        $joinkabatan = [
            ['table' => 'M_UNIT_KERJA as unit', 'on' => 'data.UNIT_KERJA   = unit.UK_ID'],
            ['table' => 'M_JABATAN as jabatan', 'on' => 'data.ID_JABATAN   = jabatan.ID_JABATAN'],
            ['table' => 'M_INST_SATKER as lembaga', 'on' => 'data.LEMBAGA      = lembaga.INST_SATKERKD'],
        ];
        foreach ($this->items as $key => $value) {
            $data[$value->ID_PN] = $this->mglobal->get_data_all('T_PN_JABATAN as data', $joinkabatan, ['ID_PN' => $value->ID_PN], 'data.ID as ID, jabatan.NAMA_JABATAN,lembaga.INST_AKRONIM,unit.UK_NAMA');
        }


        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'items' => $this->items,
            'dt_rows' => $dt_rows,
            'jabatan' => $data,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'cari' => $cari,
            'id_instansi' => $id_instansi,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E Registration' => 'index.php/welcome/dashboard',
                'Penyelenggara Negara' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
                // 'instansis'      => $this->minstansi->list_all()->result(),
        );


        // load view
        $this->load->view(strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

public function index($offset = 0, $cetak = false) {
       if (in_array($cetak, ['pdf', 'excel', 'word'])) {
            $this->iscetak = true;
            $this->limit = 0;
        }

        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_KPK, IS_INSTANSI, IS_USER_INSTANSI');

        $user_uk = $this->mglobal->get_data_by_id('t_user', 'ID_USER', $this->ID_USER);
        
//        display($this->session->userdata('IS_UK'));
        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
        $this->base_url = 'index.php/ereg/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/';
        $this->uri_segment = 4;
        $this->offset = $this->uri->segment($this->uri_segment);

        $this->db->start_cache();

        $splim = $this->offset == "" ? 0 : $this->offset;


       if ($this->role == 2){
         $c_all_pn = $this->mglobal->count_row_allpn();
           if ($this->CARI['TEXT']) 
                 $query = $this->db->query("CALL splist_all_pn_admin_search('".$this->CARI['TEXT']."')");
            else
                $query = $this->db->query("CALL splist_all_pn_admin($splim)");
       } else {
        $c_all_pn = $this->mglobal->count_row_allpn($this->instansi);
        if ($this->role > 2) {
            if ($this->CARI['TEXT']) 
                 $query = $this->db->query("CALL splist_all_pn_search($this->instansi, '".$this->CARI['TEXT']."')");
            else
                 $query = $this->db->query("CALL splist_all_pn($this->instansi, $splim)");         
        }
            

        if ($user_uk[0]->IS_UK == 1) {
            if ($this->CARI['TEXT']) 
             $query = $this->db->query("CALL splist_all_pn_uk_search($this->instansi, $user_uk[0]->UK_ID, '".$this->CARI['TEXT']."')");
            else
             $query = $this->db->query("CALL splist_all_pn_uk($this->instansi, $splim, $user_uk[0]->UK_ID)");
        }

       }



         
        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;
            $IS_KPK = $role[0]->IS_KPK;

            // if ($inst == '1' || $user == '1') {
            //     $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
            //     $query = $this->db->query("CALL splist_all_pn($INST_SATKERKD, $splim)");
            // }
        }




        $this->items = $query->result();

        $query->next_result(); // Dump the extra resultset.
        $query->free_result(); // Does what it says.
        $this->total_rows  =  $c_all_pn;
        $this->end = $query->num_rows();
        $this->db->flush_cache();

        foreach ($this->items as $item) {
          
            $ID_PN[] = $item->ID_PN;
            $havelhkpn[$item->ID_PN] = false;
        }

        if (!empty($ID_PN)) {
            $ID_PN_SEL = implode($ID_PN, ',');
            $sql = "SELECT * FROM T_LHKPN WHERE ID_PN IN ($ID_PN_SEL)";
            $query = $this->db->query($sql)->result();
            foreach ($query as $lhkpn) {
                $havelhkpn[$lhkpn->ID_PN] = true;
            }
        }


        $data = array(
            'linkCetak' => 'index.php/ereg/all_pn/index/0',
            'titleCetak' => 'cetakpn',
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'CARI' => @$this->CARI,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E-reg' => 'index.php/dashboard/efilling',
                'PN' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
            'instansis' => $this->mglobal->get_data_all('M_INST_SATKER'),
            'status_akhir' => $this->mglobal->get_data_all('T_STATUS_AKHIR_JABAT', NULL, NULL, '*', NULL, ['ORDER', 'asc']),
            'IS_KPK' => @$IS_KPK,
            'havelhkpn' => $havelhkpn,
            'iscln' => 0,
                // 'data_pnwl' => $this->mglobal->get_data_pnwl_aktif()    
        );

        if (@$this->iscetak) {
            ng::exportDataTo($data, $cetak, strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . 'index' . '_' . 'cetak', 'cetakpn');
        } else {
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
        }

     

   
    }



    private function is_instansi() {
        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_INSTANSI, IS_USER_INSTANSI');

        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;

            if ($inst == '1' || $user == '1') {
                $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
                return $INST_SATKERKD;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function is_unit_kerja() {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');
        if ($is_uk == 1) {
            $uk = $this->mglobal->get_by_id('m_unit_kerja', 'UK_ID', $id_uk);
            return $id_uk;
        } else {
            return false;
        }
    }

    /**
     * Process Insert, Update, Delete Penyelenggara Negara
     * 
     * @return boolean process Penyelenggara Negara
     */
    public function savepnwl1() {
        echo var_dump($_POST);
        echo '<br/>' . $this->input->post('LEMBAGA');
        echo '<br/>' . $this->input->post('UNIT_KERJA');
        echo '<br/>' . $this->input->post('SUB_UNIT_KERJA');
        echo '<br/>' . $this->input->post('JABATAN');
    }

    public function savepnwl() {
        $this->db->trans_begin();
        $this->load->model('muser', '', TRUE);
        $this->load->model('mpn', '', TRUE);

        $recordNIK = $this->mglobal->get_data_all('T_USER', null, ['USERNAME' => trim($this->input->post('NIK', TRUE))], '*');

        $NIK = $this->input->post('NIK', TRUE);
        $NAMA = $this->input->post('NAMA', TRUE);
        $NO_HP = $this->input->post('NO_HP', TRUE);
        $EMAIL = $this->input->post('EMAIL', TRUE);
        $JABATAN = $this->input->post('JABATAN', TRUE);
        $SUB_UNIT_KERJA = $this->input->post('SUB_UNIT_KERJA', TRUE);
        $LAMBAGA = $this->input->post('LEMBAGA', TRUE);
        $UNIT_KERJA = $this->input->post('UNIT_KERJA', TRUE);
        $NIP = $this->input->post('NIP', TRUE);
        $JNS_KEL = $this->input->post('JNS_KEL', TRUE);
        $TGL_LAHIR = $this->input->post('TGL_LAHIR', TRUE);
        $TEMPAT_LAHIR = $this->input->post('TEMPAT_LAHIR', TRUE);
        $lembaga = $this->input->post('LEMBAGA', TRUE);
        $password = createRandomPassword(6);

        if ($this->input->post('iscln') == 2) {
            $isactv = 0;
        } else {
            $isactv = 1;
        }


        $arr_pn = array(
            'NIK' => $NIK,
            'NIP_NRP' => $NIP,
            'NAMA' => $NAMA,
            'TEMPAT_LAHIR' => $TEMPAT_LAHIR,
            'JNS_KEL' => $JNS_KEL,
            'TGL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $TGL_LAHIR))),
            'EMAIL' => $EMAIL,
            'NO_HP' => $NO_HP,
            'IS_ACTIVE' => 1, //$isactv
            'CREATED_TIME' => time(),
            'CREATED_BY' => 'admin', //$this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
        );

        $insert = $this->mpn->save($arr_pn);
        $id = $this->db->insert_id();

        if ($this->input->post('iscln') == 1) {
            $iscln = 1;
        } else {
            $iscln = 0;
        }

        // Lembaga
        $id_lembaga = $this->input->post('LEMBAGA');
        $data1 = $this->mglobal->get_by_id('m_inst_satker', 'INST_SATKERKD', $id_lembaga);
        $f_lembaga = $data1->INST_NAMA;

        // Unit Kerja
        $id_unit_kerja = $this->input->post('UNIT_KERJA');
        $data = $this->mglobal->get_by_id('m_unit_kerja', 'UK_ID', $id_unit_kerja);
        $f_unit_kerja = $data->UK_NAMA;

        // Sub Unit Kerja
        $id_sub_unit_kerja = $this->input->post('SUB_UNIT_KERJA');
        if ($id_sub_unit_kerja != NULL) {
            $data = $this->mglobal->get_by_id('m_sub_unit_kerja', 'SUK_ID', $id_sub_unit_kerja);
            $f_sub_unit_kerja = $data->SUK_NAMA;
        } else {
            $f_sub_unit_kerja = NULL;
        }

        // Jabatan dan Eselon
        $id_jabatan = $this->input->post('JABATAN');

        $data = $this->mglobal->get_by_id('m_jabatan', 'ID_JABATAN', $id_jabatan);
        $f_jabatan = $data->NAMA_JABATAN;
        $f_eselon = $data->KODE_ESELON;

        $iscln = $this->input->post('iscln');
        if ($iscln == 1) {
            $id_iscln = 1;
            $v_a_jab = 0;
        } else {
            $id_iscln = 0;
            $v_a_jab = 10;
        }

        $jabatan = array(
            'ID' => $this->input->post('ID', TRUE),
            'ID_JABATAN' => $id_jabatan,
            'ID_PN' => $id,
            'LEMBAGA' => $id_lembaga,
            'NAMA_LEMBAGA' => $f_lembaga,
            'UNIT_KERJA' => $f_unit_kerja,
            'SUB_UNIT_KERJA' => $f_sub_unit_kerja,
            'DESKRIPSI_JABATAN' => $f_jabatan,
            'ESELON' => $f_eselon,
            'IS_ACTIVE' => 1, //$isactv
            'IS_CURRENT' => '1',
            'IS_CALON' => $id_iscln,
            'ID_STATUS_AKHIR_JABAT' => $v_a_jab,
            'CREATED_TIME' => time(),
            'CREATED_BY' => $this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
        );
        $this->db->insert('T_PN_JABATAN', $jabatan);


        $data_user = array(
            'USERNAME' => $this->input->post('NIK', TRUE),
            'NAMA' => $this->input->post('NAMA', TRUE),
            'EMAIL' => $this->input->post('EMAIL', TRUE),
            'HANDPHONE' => $this->input->post('NO_HP', TRUE),
            'INST_SATKERKD' => $lembaga,
            'PASSWORD' => sha1(md5($password)),
            'IS_ACTIVE' => '0',
            'CREATED_TIME' => time(),
            'CREATED_BY' => $this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            'ID_ROLE' => ID_ROLE_PN,
            'INST_SATKERKD' => ($this->is_instansi() != FALSE ? $this->is_instansi() : NULl)
        );

//        if ($insert) {
        $this->muser->save($data_user);

        // Jika iscln = 2, maka view dari daftar pnwl individual
        if ($this->input->post('iscln') == 1)
            $kirim_info = $this->muser->kirim_info_akun($EMAIL, $NIK, $password);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo '0';
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => '1', 'id' => 'true']);
        }
//            return;
//        }

        if (count($recordNIK) > 0) {
            echo json_encode(['status' => '2', 'id' => 'true']);
            exit();
        } else {
            
        }


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;
    }

    public function savepn() {
        $this->db->trans_begin();
        $this->load->model('muser', '', TRUE);
        $this->load->model('mpn', '', TRUE);
        $this->load->model('mglobal', '', TRUE);

        if ($this->input->post('act', TRUE) == 'doterpilih') {
            $pnjabatan = array(
                'IS_CALON' => 0,
                'ID_STATUS_AKHIR_JABAT' => 0,
                'TMT' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
            );

            $this->db->where('ID', $this->input->post('ID', TRUE));
            $this->db->update('T_PN_JABATAN', $pnjabatan);
        } else if ($this->input->post('act', TRUE) == 'dononaktif') {
            $data_user = array(
                'IS_ACTIVE' => '0'
            );
            $pn['ID_PN'] = $this->input->post('ID_PN', TRUE);
            $this->db->update('T_PN', $pn);
        } else if ($this->input->post('act', TRUE) == 'dorangkapjabatan') {
            $pejabat = array(
                'ID_PN' => $this->input->post('ID_PN', TRUE),
                'ID_JABATAN' => $this->input->post('JABATAN', TRUE),
                'LEMBAGA' => $this->input->post('INST_SATKERKD', TRUE),
                'ESELON' => $this->input->post('ESELON', TRUE),
                'UNIT_KERJA' => $this->input->post('UNIT_KERJA', TRUE),
                'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR', TRUE),
                'CREATED_TIME' => time(),
                'CREATED_BY' => 'admin', //$this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    // 'UPDATED_TIME'     => time(),
                    // 'UPDATED_BY'     => $this->session->userdata('USR'),
                    // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],
            );
            $insert = $this->mpn->save_jabatan_pn($pejabat);
        } else if ($this->input->post('act', TRUE) == 'doinsert') {
            $recordNIK = $this->mglobal->get_data_all('T_USER', null, ['USERNAME' => trim($this->input->post('NIK', TRUE))], '*');

            if (count($recordNIK) > 0) {
                echo json_encode(['status' => '2', 'id' => 'true']);
                exit();
            } else {

                $id = $this->input->post('NIK', TRUE);
                $url = '';
                $maxsize = 500000;
                $user = $this->session->userdata('USR');
                $filependukung = @$_FILES['FILE_FOTO'];

                $extension = strtolower(@substr(@$filependukung['name'], -4));
                $type_file = array('.jpg', '.png', '.jpeg', '.tiff', '.tif');

                $filename = 'uploads/data_pribadi/' . $id . '/readme.txt';
                if (!file_exists($filename)) {
                    $dir = './uploads/data_pribadi/' . $id . '/';

                    $file_to_write = 'readme.txt';
                    $content_to_write = "FOTO PN Dari " . $user . ' dengan nik ' . $id;

                    if (is_dir($dir) === false) {
                        mkdir($dir);
                    }

                    $file = fopen($dir . '/' . $file_to_write, "w");

                    fwrite($file, $content_to_write);

                    // closes the file
                    fclose($file);
                }

                $urlSK = '';
                $fileSK = $_FILES['FILE_FOTO'];
                $extension = strtolower(@substr(@$fileSK['name'], -4));

                if ($fileSK['size'] <= $maxsize) {
                    if (in_array($extension, $type_file)) {
                        $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/data_pribadi/" . $id . "/", 0, 10000);
                        if ($filependukung['size'] != '' && $filependukung['size'] <= $maxsize) {
                            $url = time() . "-" . trim($filependukung['name']);
                            $password = createRandomPassword(6);
                            $pejabat = array(
                                'ID_PN' => $this->input->post('ID_PN', TRUE),
                                'NIK' => $this->input->post('NIK', TRUE),
                                'NAMA' => $this->input->post('NAMA', TRUE),
                                'GELAR_DEPAN' => $this->input->post('GELAR_DEPAN', TRUE),
                                'GELAR_BELAKANG' => $this->input->post('GELAR_BELAKANG', TRUE),
                                'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR', TRUE),
                                'JNS_KEL' => $this->input->post('JNS_KEL', TRUE),
                                'TGL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL_LAHIR')))),
                                'ID_AGAMA' => $this->input->post('ID_AGAMA', TRUE),
                                'ID_STATUS_NIKAH' => $this->input->post('ID_STATUS_NIKAH', TRUE),
                                'ID_PENDIDIKAN' => $this->input->post('ID_PENDIDIKAN', TRUE),
                                'NPWP' => $this->input->post('NPWP', TRUE),
                                'NEGARA' => $this->input->post('NEGARA', TRUE),
                                'LOKASI_NEGARA' => $this->input->post('KD_ISO3_NEGARA', TRUE),
                                'KEL' => $this->input->post('KEL', TRUE),
                                'KEC' => $this->input->post('KEC', TRUE),
                                'KAB_KOT' => $this->input->post('KAB_KOT', TRUE),
                                'PROV' => $this->input->post('PROV', TRUE),
                                'ALAMAT_TINGGAL' => $this->input->post('ALAMAT_TINGGAL', TRUE),
                                'EMAIL' => $this->input->post('EMAIL', TRUE),
                                'NO_HP' => $this->input->post('NO_HP', TRUE),
                                'FOTO' => $url,
                                // 'ID_JABATAN'         => $this->input->post('JABATAN', TRUE),
                                // 'BIDANG'             => $this->input->post('BIDANG', TRUE),
                                // 'LEMBAGA'            => $this->input->post('INST_SATKERKD', TRUE),
                                // 'TINGKAT'            => $this->input->post('TINGKAT', TRUE),
                                // 'ESELON'             => $this->input->post('ESELON', TRUE),
                                // 'UNIT_KERJA'         => $this->input->post('UNIT_KERJA', TRUE),
                                // 'ALAMAT_KANTOR'  => $this->input->post('ALAMAT_KANTOR', TRUE),
                                // 'IS_CALON'          => ($this->input->post('iscln', TRUE) == '1' ? '1' : '0'),
                                'IS_ACTIVE' => '1',
                                'CREATED_TIME' => time(),
                                'CREATED_BY' => 'admin', //$this->session->userdata('USR'),
                                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                                    // 'UPDATED_TIME'     => time(),
                                    // 'UPDATED_BY'     => $this->session->userdata('USR'),
                                    // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],
                            );

                            $insert = $this->mpn->save($pejabat);
                            $id = $this->db->insert_id();

                            $NIK = $this->input->post('NIK', TRUE);
                            $allowedExts = array("gif", "jpeg", "jpg", "png", "pdf", "doc", "docx");
                            $filename = 'uploads/data_jabatan/' . $NIK . '/readme.txt';
                            //upload sk
                            if (!file_exists($filename)) {
                                $dir = './uploads/data_jabatan/' . $NIK . '/';
                                $file_to_write = 'readme.txt';
                                $content_to_write = "SK Dari nik " . $NIK;
                                if (is_dir($dir) === false) {
                                    mkdir($dir);
                                }
                                $file = fopen($dir . '/' . $file_to_write, "w");
                                fwrite($file, $content_to_write);
                                // closes the file
                                fclose($file);
                            }

                            $FILE = 'FILE_SK';
                            $FILE_SK = '';
                            $temp = explode(".", $_FILES[$FILE]["name"]);
                            $extension = end($temp);
                            $newName = time() . "-" . $_FILES[$FILE]["name"];

                            $type = array("image/gif", "image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png", "application/msword", "application/doc", "application/txt", "application/pdf", "text/pdf");

                            $maxsize = 200000000;

                            if (in_array($_FILES[$FILE]["type"], $type) && ($_FILES[$FILE]["size"] < $maxsize) && in_array($extension, $allowedExts)) {
                                if ($_FILES[$FILE]["error"] > 0) {
                                    // echo "Return Code: " . $_FILES[$FILE]["error"] . "<br>";
                                } else {
                                    if (file_exists("uploads/data_jabatan/" . $NIK . '/' . $newName)) {
                                        // echo $_FILES[$FILE]["name"] . " already exists. ";
                                    } else {
                                        // chmod('upload/', 0777);
                                        move_uploaded_file($_FILES[$FILE]["tmp_name"], "./uploads/data_jabatan/" . $NIK . '/' . $newName);
                                    }
                                    $FILE_SK = $newName;
                                }
                            } else {
                                // echo "Invalid file";
                            }

                            $id_pn = $this->input->post('ID_PN', TRUE);
                            $lembaga = $this->input->post('LEMBAGA', TRUE);


                            // Lembaga
                            $data1 = $this->mglobal->get_by_id('m_inst_satker', 'INST_SATKERKD', $lembaga);
                            $f_lembaga = $data1->INST_NAMA;

                            $jabatan = array(
                                'ID' => $this->input->post('ID', TRUE),
                                'ID_JABATAN' => $this->input->post('JABATAN', TRUE),
                                'ID_PN' => $id,
                                'DESKRIPSI_JABATAN' => strtoupper($this->input->post('DESKRIPSI_JABATAN', TRUE)),
                                'ESELON' => $this->input->post('ESELON', TRUE),
                                'LEMBAGA' => $lembaga,
                                'NAMA_LEMBAGA' => $f_lembaga,
                                'UNIT_KERJA' => $this->input->post('UNIT_KERJA', TRUE),
                                'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR', TRUE),
                                'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR', TRUE),
                                'FILE_SK' => $FILE_SK,
                                'TMT' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
                                'SD' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD', TRUE)))),
                                'IS_ACTIVE' => '1',
                                'IS_CURRENT' => '1',
                                'IS_CALON' => $this->input->post('iscln', TRUE),
                                'CREATED_TIME' => time(),
                                'CREATED_BY' => $this->session->userdata('USR'),
                                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                            );
                            $this->db->insert('T_PN_JABATAN', $jabatan);

                            $data_user = array(
                                'USERNAME' => $this->input->post('NIK', TRUE),
                                'NAMA' => $this->input->post('NAMA', TRUE),
                                'EMAIL' => $this->input->post('EMAIL', TRUE),
                                'HANDPHONE' => $this->input->post('NO_HP', TRUE),
                                'PASSWORD' => sha1(md5($password)),
                                'IS_ACTIVE' => '1',
                                'CREATED_TIME' => time(),
                                'CREATED_BY' => $this->session->userdata('USR'),
                                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                                'ID_ROLE' => ID_ROLE_PN,
                                'INST_SATKERKD' => ($this->is_instansi() != FALSE ? $this->is_instansi() : NULl)
                            );

                            if ($insert) {
                                $this->muser->save($data_user);

                                $kirim_info = $this->mpn->kirim_info_pn($this->input->post('EMAIL', TRUE), $this->input->post('NIK', TRUE), $password, 'Pemberitahuan Informasi Akun');

                                if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    echo '0';
                                } else {
                                    $this->db->trans_commit();
                                    echo json_encode(['status' => '1', 'id' => 'true']);
                                }
                                return;
                            }
                        }
                    } else {
                        echo json_encode(['status' => '0', 'id' => 'false']);
                        exit();
                    }
                } else {
                    echo json_encode(['status' => '0', 'id' => 'false']);
                    exit();
                }
            }
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $id = $this->input->post('NIK', TRUE);
            $url = '';
            $user = $this->session->userdata('USR');
            $maxsize = 500000;

            $extension = strtolower(@substr(@$filependukung['name'], -4));
            $type_file = array('.jpg', '.png', '.jpeg', '.tiff', '.tif');

            $filename = 'uploads/data_pribadi/' . $id . '/readme.txt';
            if (!file_exists($filename)) {
                $dir = './uploads/data_pribadi/' . $id . '/';

                $file_to_write = 'readme.txt';
                $content_to_write = "FOTO PN Dari " . $user . ' dengan nik ' . $id;

                if (is_dir($dir) === false) {
                    mkdir($dir);
                }

                $file = fopen($dir . '/' . $file_to_write, "w");

                fwrite($file, $content_to_write);

                // closes the file
                fclose($file);
            }

            $filependukung = (isset($_FILES['FILE_FOTO'])) ? $_FILES['FILE_FOTO'] : '';
            $del = FALSE;

            // if($filependukung == ''){
            //     echo 0;exit();
            // }

            $fileSK = $_FILES['FILE_FOTO'];

            if ($fileSK['size'] <= $maxsize) {
                if ($filependukung['error'] == 0) {
                    $extension = strtolower(@substr(@$filependukung['name'], -4));
                    if (in_array($extension, $type_file) && $filependukung['size'] != '' && $filependukung['size'] <= $maxsize) {
                        $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/data_pribadi/" . $id . "/", 0, 10000);
                        $url = time() . "-" . trim($filependukung['name']);
                    }
                }
            }
            $pejabat = array(
                'NAMA' => $this->input->post('NAMA', TRUE),
                'GELAR_DEPAN' => $this->input->post('GELAR_DEPAN', TRUE),
                'GELAR_BELAKANG' => $this->input->post('GELAR_BELAKANG', TRUE),
                'JNS_KEL' => $this->input->post('JNS_KEL', TRUE),
                'TEMPAT_LAHIR' => $this->input->post('TEMPAT_LAHIR', TRUE),
                'TGL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TGL_LAHIR', TRUE)))),
                'ID_AGAMA' => $this->input->post('ID_AGAMA', TRUE),
                'ID_STATUS_NIKAH' => $this->input->post('ID_STATUS_NIKAH', TRUE),
                'ID_PENDIDIKAN' => $this->input->post('ID_PENDIDIKAN', TRUE),
                'NPWP' => $this->input->post('NPWP', TRUE),
                'ALAMAT_TINGGAL' => $this->input->post('ALAMAT_TINGGAL', TRUE),
                'NEGARA' => $this->input->post('NEGARA', TRUE),
                'LOKASI_NEGARA' => $this->input->post('KD_ISO3_NEGARA', TRUE),
                'KEL' => $this->input->post('KEL', TRUE),
                'KEC' => $this->input->post('KEC', TRUE),
                'KAB_KOT' => $this->input->post('KAB_KOT', TRUE),
                'PROV' => $this->input->post('PROV', TRUE),
                'EMAIL' => $this->input->post('EMAIL', TRUE),
                'NO_HP' => $this->input->post('NO_HP', TRUE),
                // 'IS_ACTIVE'         => $this->input->post('IS_ACTIVE', TRUE),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            // echo '<pre>';
            // print_r($pejabat);exit();
            $url_file = $this->input->post('OLD_FILE', TRUE);
            if ($filependukung['error'] == 0) {
                $extension = strtolower(@substr(@$filependukung['name'], -4));
                if (in_array($extension, $type_file) && $filependukung['size'] != '' && $filependukung['size'] <= $maxsize) {
                    $pejabat['FOTO'] = @$url;
                    $del = TRUE;
                    unlink("./uploads/data_pribadi/" . $id . "/$url_file");
                }
            }
            // if($this->input->post('FOTO')!=""){
            //     $config['upload_path']          = './foto';
            //     $config['allowed_types']        = 'gif|jpg|png';
            //     $this->load->library('upload', $config);
            //     if (!$this->upload->do_upload('FOTO'))
            //     {
            //         $error = $this->upload->display_errors();
            //         print_r($error);
            //         exit();
            //     }
            //     else
            //     {
            //         $data = $this->upload->data();
            //         $fupload = array('FOTO' => $data['file_name'] );
            //         array_push($pejabat,$fupload);
            //     }
            // }
            $data_user = array(
                'NAMA' => $this->input->post('NAMA', TRUE),
                'EMAIL' => $this->input->post('EMAIL', TRUE),
                'HANDPHONE' => $this->input->post('NO_HP', TRUE),
                'IS_ACTIVE' => '1'
            );
            $pejabat['ID_PN'] = $this->input->post('ID_PN', TRUE);
            $this->mpn->update($pejabat['ID_PN'], $pejabat);
            $this->muser->update($this->input->post('ID_USER', true), $data_user);
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $pejabat['ID_PN'] = $this->input->post('ID_PN', TRUE);
            $get_pn = $this->mpn->get_by_id($pejabat['ID_PN']);
            $id_user = is_object($get_pn) ? $get_pn->row()->ID_USER : NULL;
            $this->muser->delete($id_user);
            $this->mpn->delete($pejabat['ID_PN']);
        } else if ($this->input->post('act', TRUE) == 'doActive') {
            $data['ID_STATUS_AKHIR_JABAT'] = 0;
            $data['SD'] = NULL;
            $data['KETERANGAN_AKHIR_JABAT'] = NULL;
            $this->db->where('ID', $this->input->post('ID', TRUE));
            $this->db->update('T_PN_JABATAN', $data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        echo $res;
    }

    /**
     * Form Tambah Penyelenggara Negara
     * 
     * @return html form tambah Penyelenggara Negara
     */
    public function addpn($iscln = NULL, $status = NULL) {
        $this->load->model('mpn', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
//        $this->load->model('mjabatan', '', TRUE);
//        $this->load->model('munitkerja', '', TRUE);
        $data = array(
            'iscln' => $iscln,
            'status' => $status,
            'form' => 'add',
//            'instansis'     => $this->minstansi->list_all()->result(),
//            'jabatans'      => $this->mjabatan->list_all()->result(),
//            'unitkerjas'    => $this->munitkerja->list_all()->result(),
            'agama' => $this->mglobal->get_data_all('M_AGAMA', null, ['IS_ACTIVE' => 1]),
            'sttnikah' => $this->mglobal->get_data_all('M_STATUS_NIKAH'),
            'penhir' => $this->mglobal->get_data_all('M_PENDIDIKAN', null, ['IS_ACTIVE' => 1]),
            'uk_id' => $this->session->userdata('UK_ID'),
            'is_uk' => $this->is_unit_kerja(),
            'isInstansi' => $this->is_instansi()
        );

//        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
        $this->load->view(strtolower(__CLASS__) . '/' . 'v_all_pn_form', $data);
    }

    /**
     * Form Edit Penyelenggara Negara
     * 
     * @return html form edit Penyelenggara Negara
     */
    public function editpn($id) {
        $this->load->model('mpn', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
        if ($this->session->userdata('IS_KPK') == 0) {
            $this->db->select('*');
            $this->db->from('T_PN p');
            $this->db->join('T_PN_JABATAN j', 'p.ID_PN=j.ID_PN');
            $this->db->where('LEMBAGA', $this->session->userdata('INST_SATKERKD'));
            $this->db->where('p.ID_PN', $id);
            $this->db->where('p.IS_ACTIVE', '1');
            $this->db->where('j.IS_ACTIVE', '1');
            $this->db->where('j.IS_CURRENT', '1');
            $jml = $this->db->get()->num_rows();
            if ($jml == 0) {
                echo 'jabatan terakhir tidak di ' . $this->session->userdata('INST_NAMA');
                return false;
            }
        }
        $data = array(
            'form' => 'edit',
            'item' => $this->mpn->get_by_id($id)->row(),
            'instansis' => $this->minstansi->list_all()->result(),
            'jabatans' => $this->mjabatan->list_all()->result(),
            'agama' => $this->mglobal->get_data_all('M_AGAMA'),
            'sttnikah' => $this->mglobal->get_data_all('M_STATUS_NIKAH'),
            'penhir' => $this->mglobal->get_data_all('M_PENDIDIKAN'),
        );
        // $data['unitkerjas'] = $this->munitkerja->list_all($data['item']->LEMBAGA)->result();
        $data['unitkerjas'] = $this->munitkerja->list_all()->result();
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    public function get_unit_kerja() {
        $this->load->model('munitkerja');
        $inst_satkerkd = $this->input->post('INST_SATKERKD');
        $inst_satkerkd = empty($inst_satkerkd) ? '999' : $inst_satkerkd;
        $unitkerja = $this->munitkerja->list_all($inst_satkerkd)->result();
        echo json_encode(array('result' => $unitkerja));
    }

    /**
     * Form Konfirmasi Hapus Penyelenggara Negara
     * 
     * @return html form konfirmasi hapus Penyelenggara Negara
     */
    public function deletepn($id, $idjpn = NULL) {
        $this->load->model('mpn', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form' => 'delete',
            'idjpn' => $idjpn,
            'item' => $this->mpn->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    public function update_calon_pn($id, $idjpn = NULL) {
        $this->load->model('mpn', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form' => 'update',
            'idjpn' => $idjpn,
            'item' => $this->mpn->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    function do_delete_pn($id_pn, $idjpn = NULL) {
        $this->load->model('mglobal', '', TRUE);
//        $hapus = $this->mglobal->delete_data('ID_PN', $id_pn, 'T_PN_JABATAN');

        $arr_pn = array(
            'IS_ACTIVE' => 0
        );
        $arr_jpn = array(
            'IS_ACTIVE' => 0,
            'IS_DELETE' => 0,
            'ID_STATUS_AKHIR_JABAT' => 5,
        );

        $hapus = $this->mglobal->update_data_('T_PN', $arr_pn, 'ID_PN', $id_pn);
        if ($idjpn)
            $hapus = $this->mglobal->update_data_('t_pn_jabatan', $arr_jpn, 'ID', $idjpn);


        if ($hapus) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function do_nonact_pn($id_pn, $idjpn) {
        $this->load->model('mglobal', '', TRUE);

        $this->db->trans_begin();


        $arr_pn = array(
            'IS_ACTIVE' => 1
        );
        $arr_jpn = array(
            'IS_ACTIVE' => 1,
            'IS_DELETED' => 0,
            'ID_STATUS_AKHIR_JABAT' => 15,
        );

        $this->mglobal->update_data_('T_PN', $arr_pn, 'ID_PN', $id_pn);

        $this->mglobal->update_data_('t_pn_jabatan', $arr_jpn, 'ID', $idjpn);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    function do_update_pn($id_pn, $idjpn = NULL) {
        $this->load->model('mglobal', '', TRUE);
//        $hapus = $this->mglobal->delete_data('ID_PN', $id_pn, 'T_PN_JABATAN');
        $data['IS_CALON'] = 0;
        $update = $this->mglobal->update_pn($id_pn, $data);

        if ($update) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * Detail Penyelenggara Negara
     * 
     * @return html detail Penyelenggara Negara
     */
    public function detailpn($id, $idjb = NULL) {
        $this->load->model('mpn', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
//        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mglobal', '', TRUE);

        if ($this->session->userdata('IS_KPK') == 0) {
            $this->db->select('*');
            $this->db->from('T_PN p');
            $this->db->join('T_PN_JABATAN j', 'p.ID_PN=j.ID_PN');
            $this->db->where('LEMBAGA', $this->session->userdata('INST_SATKERKD'));
            $this->db->where('p.ID_PN', $id);
            $this->db->where('p.IS_ACTIVE', '1');
            $this->db->where('j.IS_ACTIVE', '1');
            $this->db->where('j.IS_CURRENT', '1');
            $jml = $this->db->get()->num_rows();
            if ($jml == 0) {
                echo 'jabatan terakhir tidak di ' . $this->session->userdata('INST_NAMA');
                return false;
            }
        }

        $cek = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id], 'NEGARA');
        $select = '';
        if ($cek[0]->NEGARA == 2) {
            $itemjoin = [
                    // ['table' => 'T_PN_JABATAN as pj', 'on' => 'pj.ID_PN   = p.ID_PN', 'join' => 'left'],
                    // ['table' => 'T_USER as b', 'on' => 'b.USERNAME   = a.NIK', 'join' => 'left'],
                    // ['table' => 'M_AREA as c', 'on' => 'a.PROV = c.IDPROV AND c.LEVEL = 1', 'join' => 'left'],
                    // ['table' => 'M_AREA as f', 'on' => 'a.PROV = c.IDPROV and a.PROV = f.IDPROV and a.KAB_KOT = CAST(f.IDKOT AS UNSIGNED) AND f.LEVEL = 2', 'join' => 'left']
            ];
            $select = 'a.*,c.NAME as PROVINSI,f.NAME as KABKOT';
        } else {
            $itemjoin = [
                ['table' => 'T_USER as b', 'on' => 'b.USERNAME   = a.NIK', 'join' => 'left'],
            ];
            $select = 'a.*';
        }

        $item = $this->mglobal->get_data_all('T_PN a', $itemjoin, ['a.ID_PN' => $id], $select);
        // $detail = $this->mglobal->get_by_id('T_PN_JABATAN', 'ID_PN', $id);
        $detail = $this->mglobal->get_detail_pn($id, $idjb);
        $riwayat_jabatan = $this->mglobal->get_riwayat_jabatan($id);
        $data = array(
            'riwayat_jabatan' => $riwayat_jabatan,
            'IS_KPK' => $this->IS_KPK,
            'form' => 'detail',
            'item' => $item[0],
            'agama' => $this->mglobal->get_data_all('M_AGAMA', null, ['ID_AGAMA' => $item[0]->ID_AGAMA]),
            'sttnikah' => $this->mglobal->get_data_all('M_STATUS_NIKAH', null, ['ID_STATUS' => $item[0]->ID_STATUS_NIKAH]),
            'penhir' => $this->mglobal->get_data_all('M_PENDIDIKAN', null, ['ID_PENDIDIKAN' => $item[0]->ID_PENDIDIKAN]),
            'dt_detail' => $detail
        );
        // $data[$value->ID_PN] = $this->mglobal->get_data_all('T_PN_JABATAN as jabatan', $joinkabatan, ['ID_PN' => $value->ID_PN],'jabatan.JABATAN,lembaga.INST_NAMA,unit.UK_NAMA');
        $join = [
            ['table' => 'M_INST_SATKER b', 'on' => 'b.INST_SATKERKD = a.LEMBAGA'],
            ['table' => 'M_UNIT_KERJA c', 'on' => 'c.UK_ID = a.UNIT_KERJA'],
            ['table' => 'M_JABATAN d', 'on' => 'd.ID_JABATAN = a.ID_JABATAN'],
            ['table' => 'M_ESELON e', 'on' => 'e.ID_ESELON = a.ESELON']
        ];
        $select = "e.*,a.FILE_SK,a.TMT,a.SD,a.ID,a.ID_PN,a.ID_JABATAN,a.DESKRIPSI_JABATAN,a.LEMBAGA,a.UNIT_KERJA,e.ESELON,c.UK_ID,c.UK_NAMA,b.INST_SATKERKD,b.INST_NAMA,d.ID_JABATAN,d.NAMA_JABATAN";
        $data['detJabatan'] = $this->mglobal->get_data_all('T_PN_JABATAN a', $join, ['a.ID_PN' => $id], $select);
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    public function pensiun($offset = 0) {
        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
        $this->base_url = site_url(strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->uri_segment = 3;
        $this->offset = $this->uri->segment($this->uri_segment);

        // filter
        $cari = '';
        $filter = '';
        if ($_POST && $this->input->post('table_search', TRUE) != '') {
            $cari = $this->input->post('table_search', TRUE);
            $filter = array(
                'NAMA' => $this->input->post('table_search', TRUE),
            );
        }

        // load and packing data
        $this->items = $this->mpn->get_paged_list($this->limit, $this->offset, $filter)->result();
        $this->total_rows = $this->mpn->count_all($filter);

        $this->load->model('minstansi', '', TRUE);

        $data = array(
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'cari' => $cari,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E Registration' => 'index.php/welcome/eregistration',
                'Penyelenggara Negara' => 'index.php/' . strtolower(__CLASS__) . '/index',
                'Pensiun' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
            'instansis' => $this->minstansi->list_all()->result(),
        );

        // load view
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    public function nonaktif($offset = 0, $cetak = false) {
        if (in_array($cetak, ['pdf', 'excel', 'word'])) {
            $this->iscetak = true;
            $this->limit = 0;
        }

        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_KPK, IS_INSTANSI, IS_USER_INSTANSI');

        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
        $this->base_url = 'index.php/ereg/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/';
        $this->uri_segment = 4;
        $this->offset = $this->uri->segment($this->uri_segment);


        $this->db->start_cache();

        $this->db->select('
            P.ID_PN,
            JAB.NAMA_JABATAN N_JAB, SUK.SUK_NAMA N_SUK, UK.UK_NAMA N_UK, INTS.INST_NAMA,
            P.NIK,
            P.NAMA,
            PJ.ID ID_JAB,
            PJ.UNIT_KERJA,
            PJ.SUB_UNIT_KERJA,
            U.ID_USER
       --     V1.ID_PN AS ID_PN_DIJABATAN,
       --     group_concat(
       --         CONCAT( CONVERT( REPEAT( "0",( 5 - LENGTH( V1.LEMBAGA ) ) ) USING latin1 ), V1.LEMBAGA ) SEPARATOR ","
       --     ) AS LEMBAGA,
        --    group_concat(
       --         CONCAT( REPEAT( "0",( 5 - LENGTH( V1.ID_JABATAN ) ) ), V1.ID_JABATAN ) SEPARATOR ","
        --    ) AS JABATAN,
        --    group_concat(
       --         CONCAT( REPEAT( "0",( 5 - LENGTH( V1.IS_CALON ) ) ), V1.IS_CALON ) SEPARATOR ","
        --    ) AS IS_CALON,
        --    group_concat(
        --        CONCAT( ifnull( V1.ID, "" ), ":||:", ifnull( V1.ID_STATUS_AKHIR_JABAT, "" ), ":||:", CONVERT( ifnull( V1.STATUS, "" ) USING utf8 ), ":||:", ifnull( V1.ID_PN_JABATAN, "NULL" ), ":||:", CONVERT( ifnull( V1.LEMBAGA, "" ) USING utf8 ), ":||:", ifnull( V1.MJC_NAMA, "" ), " ", CONVERT( ifnull( V1.DESKRIPSI_JABATAN, "" ) USING utf8 ), " - ", "<span style=\"font-weight : bold\">", CONVERT( ifnull( V1.MUC_NAMA, "" ) USING utf8 ), " - ", ifnull( V1.MIC_AKRONIM, V1.MIC_NAMA ), "</span>", ":||:", ifnull( V1.TMT, "" ), ":||:", CONVERT( ifnull( V1.SD, "" ) USING utf8 ), ":||:", ifnull( V1.IS_CALON, "" ), ":||:", ifnull( V1.MIT_NAMA, "" ) ) SEPARATOR ":|||:"
        --    ) AS NAMA_JABATAN,
        --    V1.*,
        --    V2.JML_AKTIF,
         --   V3.JML_NON_AKTIF,
         --   V4.PN_MENINGGAL
            ', false); // parameter false wajib
        $this->db->from('T_PN P');
        $this->db->join('T_USER U', 'U.USERNAME = P.NIK');
//        $this->db->join('V_JABATAN_CURRENT_EXC_CALON V1', 'V1.ID_PN = P.ID_PN');
//        $this->db->join('V_PN_JML_JABATAN_AKTIF_EXC_CALON V2', 'V2.ID_PN = V1.ID_PN', 'left');
//        $this->db->join('V_PN_JML_JABATAN_NONAKTIF_EXC_CALON V3', 'V3.ID_PN = V1.ID_PN', 'left');
        $this->db->join('T_PN_JABATAN PJ', 'P.ID_PN = PJ.ID_PN', 'LEFT');
        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN', 'left');
        $this->db->join('m_sub_unit_kerja SUK', 'SUK.SUK_ID = JAB.SUK_ID', 'left');
        $this->db->join('m_unit_kerja UK', 'UK.UK_ID = JAB.UK_ID', 'left');
        $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = UK.UK_LEMBAGA_ID', 'left');
//        $this->db->join('
//            (
//                SELECT ID_PN, 1 PN_MENINGGAL
//                FROM
//                T_PN_JABATAN
//                INNER JOIN T_STATUS_AKHIR_JABAT ON T_PN_JABATAN.ID_STATUS_AKHIR_JABAT = T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT
//                WHERE IS_MENINGGAL = 1
//            )
//         V4', 'V4.ID_PN = V1.ID_PN', 'left');

        //$this->db->where(' V2.JML_AKTIF IS NULL AND V3.JML_NON_AKTIF IS NOT NULL', null, false); // parameter false wajib
        $this->db->where(' P.IS_ACTIVE', '1');
        $this->db->where(' PJ.ID_STATUS_AKHIR_JABAT', '5');
        // $this->db->where(' PJ.IS_DELETED', '0');
        $this->db->where(' PJ.IS_CURRENT', '1');

        if ($this->role > 2)
            $this->db->where('INTS.INST_SATKERKD', $this->instansi);

        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;
            $IS_KPK = $role[0]->IS_KPK;

            if ($inst == '1' || $user == '1') {
                $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
//                $this->db->where("(V1.LEMBAGA like '" . $INST_SATKERKD . "' OR U.INST_SATKERKD = '" . $INST_SATKERKD . "')", null, false);
            }
        }

        if (@$this->CARI['USEWHEREONLY']) {
            $this->db->where('P.NIK', $this->CARI['TEXT']);
        } else {
            if (@$this->CARI['TEXT']) {
                $this->db->where("(PJ.UNIT_KERJA LIKE '%" . $this->CARI['TEXT'] . "%' OR PJ.SUB_UNIT_KERJA LIKE '%" . $this->CARI['TEXT'] . "%' OR PJ.DESKRIPSI_JABATAN LIKE '%" . $this->CARI['TEXT'] . "%' OR P.NAMA LIKE '%" . $this->CARI['TEXT'] . "%' OR P.NIK LIKE '%" . $this->CARI['TEXT'] . "%')");
            }

            if (@$this->CARI['INST'] && @$this->CARI['INST'] != -99) {
                $this->db->where("V1.LEMBAGA like '" . $this->CARI['INST'] . "'", null, false);
            }
        }

        if (@$this->CARI['STAT']) {
            $this->db->where("V1.ID_STATUS_AKHIR_JABAT = '" . $this->CARI['STAT'] . "'", null, false);
        }

        $this->db->order_by('P.NAMA', 'asc');
        $this->db->group_by('P.ID_PN'); // parameter false wajib

        $this->total_rows = $this->db->get('')->num_rows();

        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();

        //display($this->db->last_query());

        $this->end = $query->num_rows();
        // display($this->db->last_query());
        $this->db->flush_cache();

        $havelhkpn = array();
        foreach ($this->items as $item) {
            $ID_PN[] = $item->ID_PN;
            $havelhkpn[$item->ID_PN] = false;
        }

        if (!empty($ID_PN)) {
            $ID_PN_SEL = implode($ID_PN, ',');
            $sql = "SELECT * FROM T_LHKPN WHERE ID_PN IN ($ID_PN_SEL)";
            $query = $this->db->query($sql)->result();
            foreach ($query as $lhkpn) {
                $havelhkpn[$lhkpn->ID_PN] = true;
            }
        }

        $data = array(
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'CARI' => @$this->CARI,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E-reg' => 'index.php/dashboard/efilling',
                'PN' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
            'instansis' => $this->mglobal->get_data_all('M_INST_SATKER'),
            'IS_KPK' => @$kpk,
            'havelhkpn' => $havelhkpn,
        );

        // load view
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);

        return;

        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
        $this->base_url = site_url(strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->uri_segment = 3;
        $this->offset = $this->uri->segment($this->uri_segment);

        // filter
        $cari = '';
        $filter = '';
        if ($_POST && $this->input->post('table_search', TRUE) != '') {
            $cari = $this->input->post('table_search', TRUE);
            $filter = array(
                'NAMA' => $this->input->post('table_search', TRUE),
            );
        }

        // load and packing data
        $this->items = $this->mpn->get_nonaktif_list($this->limit, $this->offset, $filter)->result();
        $this->total_rows = count($this->items);
        // $this->total_rows   = $this->mpn->count_all($filter);

        $this->load->model('minstansi', '', TRUE);

        $data = array(
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'cari' => $cari,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E Registration' => 'index.php/welcome/eregistration',
                'Penyelenggara Negara' => 'index.php/' . strtolower(__CLASS__) . '/index',
                'Non Aktif' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
            'instansis' => $this->minstansi->list_all()->result(),
        );

        // load view
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    public function mutasimasuk($offset = 0, $cetak = false) {

        if (in_array($cetak, ['pdf', 'excel', 'word'])) {
            $this->iscetak = true;
            $this->limit = 0;
        }

        // echo 'Masuk';
        $this->load->model('mmutasi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
        // prepare paging
        $this->base_url = site_url(strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->uri_segment = 3;
        $this->offset = $this->uri->segment($this->uri_segment);

        // filter
        $cari = '';
        $filter = '';
        if ($_POST && $this->input->post('table_search', TRUE) != '') {
            $cari = $this->input->post('table_search', TRUE);
            $filter = array(
                'NAMA' => $this->input->post('table_search', TRUE)
            );
        }

        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_INSTANSI, IS_USER_INSTANSI');

        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;

            if ($inst == '1' || $user == '1') {
                $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
                if (is_array($filter)) {
                    $filter['ID_INST_TUJUAN'] = $INST_SATKERKD;
                } else {
                    $filter = ['ID_INST_TUJUAN' => $INST_SATKERKD];
                }
            }
        }

        // load and packing data
        $this->items = $this->mmutasi->get_paged_list2($this->limit, $this->offset, $filter)->result();
        //var_dump($this->session->userdata);
        $this->total_rows = $this->mmutasi->count_all($filter);

        $data = array(
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'cari' => $cari,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E Registration' => 'index.php/welcome/eregistration',
                'Penyelenggara Negara' => 'index.php/' . strtolower(__CLASS__) . '/index',
                'Mutasi Masuk' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
        );

        // load view
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    //start update by sukma
    /**
     * Mutasi Keluar Penyelenggara Negara
     *
     * @return html Mutasi Keluar Penyelenggara Negara
     */
    public function mutasikeluar($offset = 0) {
        // echo 'Pindah';
        $this->load->model('mmutasi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
        // prepare paging
        $this->base_url = site_url(strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->uri_segment = 3;
        $this->offset = $this->uri->segment($this->uri_segment);

        // filter
        $cari = '';
        $filter = '';
        if ($_POST && $this->input->post('cari', TRUE) != '') {
            $cari = $this->input->post('cari', TRUE);
            $filter = array(
                'NAMA' => $this->input->post('cari', TRUE)
            );
        }

        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_INSTANSI, IS_USER_INSTANSI');

        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;

            if ($inst == '1' || $user == '1') {
                $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
                if (is_array($filter)) {
                    $filter['ID_INST_ASAL'] = $INST_SATKERKD;
                } else {
                    $filter = ['ID_INST_ASAL' => $INST_SATKERKD];
                }
            }
        }

        // load and packing data
        $this->items = $this->mmutasi->get_paged_list($this->limit, $this->offset, $filter)->result();
        //var_dump($this->session->userdata);
        $this->total_rows = $this->mmutasi->count_all_keluar($filter);

        $data = array(
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'cari' => $cari,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E Registration' => 'index.php/welcome/eregistration',
                'Penyelenggara Negara' => 'index.php/' . strtolower(__CLASS__) . '/index',
                'Mutasi Keluar' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
        );

        // load view
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    public function addmutasi() {
        $this->load->model('mmutasi', '', TRUE);
        $data = array(
            'form' => 'addmutasi_keluar',
            'url_load_pejabat' => 'index.php/ereg/' . strtolower(__CLASS__) . '/load_pejabat',
            'url_load_instansi' => 'index.php/ereg/' . strtolower(__CLASS__) . '/load_instansi'
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '/' . 'all_pn_mutasi_form', $data);
    }

    public function save_edit_mutasi() {
        if ($_POST && $this->input->post('JABATAN')) {
            $this->load->model('mmutasi');
            $this->load->model('muser');
            $this->load->model('mpn');
            $inst_asal = $this->input->post('ID_INST_ASAL');
            $inst_tujuan = $this->input->post('INST_TUJUAN');
            $username = $this->input->post('USERNAME');
            $id_jabatan = $this->input->post('JABATAN');
            $id_user = $this->input->post('ID_USER');
            $id_pn = $this->mmutasi->get_id_pn($username);

            if (!empty($id_pn)) {
                $data_update = array(
                    'ID_INST_TUJUAN' => empty($inst_tujuan) ? NULL : $inst_tujuan,
                    'ID_JABATAN' => $id_jabatan,
                );
                $save = $this->mmutasi->update($id_pn, $data_update);
                echo '1';
            } else {
                echo '0';
            }
        } else {
            echo '0';
        }
    }

    public function editmutasi($id) {
        $this->load->model('mmutasi', '', TRUE);
        $this->load->model('muser', '', TRUE);
        $this->load->model('mpn', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $data = array(
            'form' => 'editmutasi_keluar',
            'url_load_pejabat' => 'index.php/' . strtolower(__CLASS__) . '/load_pejabat',
            'url_load_instansi' => 'index.php/' . strtolower(__CLASS__) . '/load_instansi',
            'items' => $this->mmutasi->get_mutasi_by_id($id)->row(),
            'instansis' => $this->minstansi->list_all()->result(),
            'jabatans' => $this->mjabatan->list_all()->result(),
        );

        $data['data_pn'] = $this->mpn->get_by_id($data['items']->ID_PN)->row();
        $data['sk_jab'] = $this->mmutasi->get_his_jab_last($data['items']->ID_PN);
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '/' . 'all_pn_mutasi_form', $data);
    }

    public function deletemutasi($id) {
        $this->load->model('mmutasi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $data = array(
            'form' => 'deletemutasi_keluar',
            'url_load_pejabat' => 'index.php/' . strtolower(__CLASS__) . '/load_pejabat',
            'url_load_instansi' => 'index.php/' . strtolower(__CLASS__) . '/load_instansi',
            'items' => $this->mmutasi->get_mutasi_by_id($id)->row()
        );
        $this->load->view(strtolower(__CLASS__) . '/' . 'all_pn_mutasi_form', $data);
    }

    public function load_pejabat() {
        $this->load->model('mmutasi', '', TRUE);
        $post = $this->input->post_get('q');
        $result = $this->mmutasi->load_pejabat($post);
        $data = array();
        foreach ($result as $val) {
            // $_data['id'] = $val->ID_PN;
            // $_data['label'] = $val->NAMA;
            // $_data['value'] = $val->NIK;
            echo $val->NAMA . "|" . $val->ID_PN . "\n";
        }

        // echo ($_data);
    }

    public function load_instansi() {
        $this->load->model('mmutasi', '', TRUE);
        $post = $this->input->post_get('q');
        $result = $this->mmutasi->load_instansi($post);
        $data = array();
        foreach ($result as $val) {
            echo $val->INST_NAMA . "|" . $val->INST_SATKERKD . "\n";
        }

        //	echo ($_data);
    }

    public function dodeletemutasi() {
        $this->load->model('mmutasi');
        $this->db->trans_begin();
        $pejabat['ID_MUTASI'] = $this->input->post('id_mutasi', TRUE);
        $this->mmutasi->delete($pejabat['ID_MUTASI']);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    //save data
    /* function savemutasi(){
      $this->db->trans_begin();
      $this->load->model('mmutasi', '', TRUE);
      $this->load->model('mpn', '', TRUE);
      //save data mutasi
      if($this->input->post('act', TRUE)=='doinsert'){
      $status = 0;
      //status langsung approved jika mutasi satu instansi
      if($this->session->userdata('INST_SATKERKD') == $this->input->post('id_instansi')){
      $dt_update = array('JABATAN'=>$this->input->post('jabatan'));
      $this->mpn->update($this->input->post('id_pN'),$dt_update);
      $status = 1;
      }
      $dt_insert = array('ID_PN' => $this->input->post('id_pN'),
      'ID_INST_ASAL' => $this->session->userdata('INST_SATKERKD'),
      'ID_INST_TUJUAN' => $this->input->post('id_instansi'),
      'STATUS_APPROVAL' => $status,
      'JABATAN' => $this->input->post('jabatan'));
      $save = $this->mmutasi->save($dt_insert);
      $dt_his_jab = array(
      'ID_PEJABAT' 	=> $this->input->post('id_pejabat'),
      'INS_SATKERKD' 	=> $this->input->post('id_instansi'),
      'NO_SK' 		=> $this->input->post('no_sk'),
      'TMT' 			=> $this->input->post('tmt'),
      'SD' 			=> $this->input->post('sd'),
      'NAMA_JABATAN' 	=> $this->input->post('jabatan'),
      'ID_MUTASI' 	=> $save
      );
      $this->mmutasi->insert_history_jabatan($dt_his_jab);

      // update mutasi
      }else if($this->input->post('act', TRUE)=='doupdate'){
      //status langsung approved jika mutasi masih dalam satu instansi
      $status=0;
      if($this->session->userdata('INST_SATKERKD') == $this->input->post('id_instansi')){
      $dt_update = array('JABATAN'=>$this->input->post('jabatan'));
      $this->mpn->update($this->input->post('id_pN'),$dt_update);
      $status = 1;
      }
      $dt_insert = array('ID_PN' => $this->input->post('id_pN'),
      'ID_INST_ASAL' => $this->session->userdata('INST_SATKERKD'),
      'ID_INST_TUJUAN' => $this->input->post('id_instansi'),
      'JABATAN' => $this->input->post('jabatan'),
      'STATUS_APPROVAL' =>$status
      );
      $idm = 	$this->input->post('id_mutasi');
      $this->mmutasi->update($idm,$dt_insert);

      $dt_his_jab = array('ID_PN' => $this->input->post('id_pN'),
      'INS_SATKERKD' => $this->input->post('id_instansi'),
      'NO_SK' => $this->input->post('no_sk'),
      'TMT' => $this->input->post('tmt'),
      'SD' => $this->input->post('sd'),
      'NAMA_JABATAN' => $this->input->post('jabatan'));
      $this->mmutasi->update_history_jabatan($idm,$dt_his_jab);

      //delete data mutasi
      }else if($this->input->post('act', TRUE)=='dodelete'){
      $pejabat['ID_MUTASI']    = $this->input->post('id_mutasi', TRUE);
      $this->mmutasi->delete($pejabat['ID_MUTASI']);
      $this->mmutasi->del_jab_by_mutasi($pejabat['ID_MUTASI']);

      // update data jika diapprove
      }else if($this->input->post('act', TRUE)=='doapprove'){

      $this->load->model('mpn', '', TRUE);
      $up_mutasi = array('STATUS_APPROVAL' => 1);
      $up_pejabat = array(
      'LEMBAGA' => $this->input->post('id_instansi'),
      'JABATAN' => $this->input->post('jabatan')
      );
      $idm = 	$this->input->post('id_mutasi');
      $idp = 	$this->input->post('id_pN');
      $this->mmutasi->update($idm,$up_mutasi);
      $this->mpn->update($idm,$up_pejabat);

      // update data jika mutasi di tolak
      }else if($this->input->post('act', TRUE)=='tolakmutasi'){
      $this->load->model('mmutasi', '', TRUE);
      $up_mutasi = array('STATUS_APPROVAL' => -1);
      $idm = $this->input->post('id_mutasi');
      $this->mmutasi->update($idm,$up_mutasi);
      }

      if ($this->db->trans_status() === FALSE){
      $this->db->trans_rollback();
      }else{
      $this->db->trans_commit();
      }
      echo intval($this->db->trans_status());
      } */

    public function savetolakmutasi() {
        $this->db->trans_begin();

        $this->load->model('mmutasi', '', TRUE);
//        $up_mutasi = array('STATUS_APPROVAL' => -1);
        $idm = $this->input->post('id_mutasi');
//        $this->mmutasi->update($idm,$up_mutasi);
        $this->mglobal->delete('T_MUTASI_PN', ['ID_MUTASI' => $idm]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    // load page approved
    public function approvmutasi($id, $stat = false) {
        $this->load->model('mmutasi', '', TRUE);
        $form = 'approvmutasi';
        if ($stat == true) {
            $form = 'approvmutasi1';
        }

        $data = array(
            'form' => $form,
            'url_load_pejabat' => 'index.php/' . strtolower(__CLASS__) . '/load_pejabat',
            'url_load_instansi' => 'index.php/' . strtolower(__CLASS__) . '/load_instansi',
            'items' => $this->mmutasi->get_mutasi_by_id($id)->row()
        );
        $data['sk_jab'] = $this->mmutasi->get_his_jab_last($data['items']->ID_PN);
        $this->load->view(strtolower(__CLASS__) . '/' . 'all_pn_mutasi_form', $data);
    }

    public function approvmutasi_table($id) {
        $this->load->model('mmutasi', '', TRUE);
        $form = 'approvmutasi';

        $data = array(
            'form' => $form,
            'url_load_pejabat' => 'index.php/' . strtolower(__CLASS__) . '/load_pejabat',
            'url_load_instansi' => 'index.php/' . strtolower(__CLASS__) . '/load_instansi',
            'items' => $this->mmutasi->get_mutasi_by_id($id)->row()
        );
        $data['sk_jab'] = $this->mmutasi->get_his_jab_last($data['items']->ID_PN);
        $this->load->view(strtolower(__CLASS__) . '/' . 'all_pn_mutasi_table_form', $data);
    }

    public function saveapprove($id = null) {
        $this->db->trans_begin();
        if ($this->input->post('act') == 'doapprove' || !is_null($id)) {
            $this->load->model('mmutasi', '', TRUE);
            $this->load->model('muser', '', TRUE);
            $this->load->model('mpn', '', TRUE);
            $this->load->model('mglobal', '', TRUE);

            $id_mutasi = is_null($id) ? $this->input->post('id_mutasi') : $id;
            $data_mutasi = $this->mmutasi->get_mutasi_by_id($id_mutasi)->row();

            // insert jabatan baru
            $data_pn = array(
                'ID_PN' => $data_mutasi->ID_PN,
                'ID_JABATAN' => $this->input->post('JABATAN'),
                'LEMBAGA' => $data_mutasi->ID_INST_TUJUAN,
                'UNIT_KERJA' => $data_mutasi->UNIT_KERJA_BARU,
                'ESELON' => $this->input->post('ESELON'),
                'FILE_SK' => $data_mutasi->FILE_SK,
                'TMT' => date('Y-m-d'),
                'SD' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
                'DESKRIPSI_JABATAN' => $this->input->post('DESKRIPSI_JABATAN'),
                'IS_CALON' => '0',
                'IS_ACTIVE' => '1',
                'IS_CURRENT' => '1'
            );

            $this->mpn->save_jabatan_pn($data_pn);

            // update jabatan lama
            $mutasi = $this->mglobal->get_data_all('T_MUTASI_PN', null, null, '*', "ID_MUTASI = '$id_mutasi'")[0];

            $pnjabatan['SD'] = date('Y-m-d');
            $pnjabatan['ID_STATUS_AKHIR_JABAT'] = $data_mutasi->ID_STATUS_AKHIR_JABAT;
            $pnjabatan['IS_CURRENT'] = 0;
            $this->db->where('ID', $mutasi->ID_PN_JABATAN);
            $this->db->update('T_PN_JABATAN', $pnjabatan);

            // delete temp mutasi
            $this->mglobal->delete('T_MUTASI_PN', '1=1', "ID_MUTASI = '$id_mutasi'");
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    //load page tolak mutasi
    public function tolakmutasi($id, $tipe) {
        $this->load->model('mmutasi', '', TRUE);
        $data = array(
            'tipe' => $tipe,
            'form' => 'tolakmutasi',
            'url_load_pejabat' => 'index.php/' . strtolower(__CLASS__) . '/load_pejabat',
            'url_load_instansi' => 'index.php/' . strtolower(__CLASS__) . '/load_instansi',
            'items' => $this->mmutasi->get_mutasi_by_id($id)->row()
        );
        $data['sk_jab'] = $this->mmutasi->get_his_jab_last($data['items']->ID_PN);
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_mutasi_form', $data);
    }

    public function getUser($id = NULL) {
        $this->load->model('mglobal', '', TRUE);

        if (is_null($id)) {
            $q = $_GET['q'];

            $where = 'IS_ACTIVE = 1';

            $result = $this->mglobal->get_data_all('T_PN', null, $where, 'ID_PN, NAMA, NIK', "NAMA LIKE '%$q%' OR NIK LIKE '%$q%'");

            $res = array();
            foreach ($result as $row) {
                $res[] = array('id' => $row->ID_PN, 'name' => $row->NIK . ' - ' . $row->NAMA);
            }

            $data = array('item' => $res);

            echo json_encode($data);
        } else {
            $where = array('IS_ACTIVE' => '1', 'NIK' => $id);

            $result = $this->mglobal->get_data_all('T_PN', null, $where, 'ID_PN, NAMA, NIK');

            $res = array();
            foreach ($result as $row) {
                $res[] = array('id' => $row->ID_PN, 'name' => $row->NIK . ' - ' . $row->NAMA);
            }

            echo json_encode($res);
        }
    }

    public function cek_user($username) {
        $this->load->model('muser', '', TRUE);
        $this->load->model('mpn', '', TRUE);
        $check = $this->muser->check_user_if_exist($username);
        if (!empty($check)) {
            $data_pn = $this->mpn->get_by_nik($username)->row();
            echo json_encode(array('success' => 1, 'result' => $data_pn));
        } else {
            echo json_encode(array('success' => 0));
        }
    }

    public function cek_user_edit($username, $current_username) {
        $this->load->model('muser', '', TRUE);
        $check = $this->muser->check_user_for_edit($username, $current_username);
        if (!empty($check)) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function cek_email($email) {
        $this->load->model('muser', '', TRUE);
        $decode = urldecode($email);
        $check = $this->muser->check_email_if_exist($decode, 1);
        if (!empty($check)) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function cek_emails($email, $id = NULL) {
        $decode = urldecode($email);
        $check = null;
        if ($id) {
            $this->db->where('EMAIL', $decode);
            $this->db->where('ID_USER !=', $id);
            $check = $this->db->get('t_user')->result();
        } else {
            $this->db->where('EMAIL', $decode);
            $this->db->limit(1);
            $check = $this->db->get('t_user')->row();
        }
        if (!$this->isValidEmail($decode)) {
            echo "0";
        } else {
            if ($check) {
                echo "1";
            } else {
                echo "2";
            }
        }
    }

    public function mts($idjabatan, $jenis = 1, $stat = null) {
        $this->makses->check_is_write();
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mpn', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);

        if ($jenis == 58) {
            // terpilih
            $rowjabatan = $this->mglobal->get_data_all(
                            'T_PN_JABATAN', [
                        ['table' => 'T_PN', 'on' => 'T_PN_JABATAN.ID_PN = T_PN.ID_PN', 'join' => 'left'],
                        ['table' => 'M_JABATAN', 'on' => 'T_PN_JABATAN.ID_JABATAN  = M_JABATAN.ID_JABATAN', 'join' => 'left'],
                        ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD  = T_PN_JABATAN.LEMBAGA', 'join' => 'left'],
                        ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID  = T_PN_JABATAN.UNIT_KERJA', 'join' => 'left'],
                            ]
                            , ['ID' => $idjabatan]
                            , 'T_PN_JABATAN.ID, T_PN.ID_PN, T_PN.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_UNIT_KERJA.UK_NAMA, T_PN_JABATAN.IS_CALON'
                    )[0];
            // display($rowjabatan);
            $data = array(
                'form' => 'terpilih',
                'item' => $rowjabatan,
                'stat' => $stat
            );
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
            return;
        }
        // display($this->db->last_query());

        $join = [
            ['table' => 'M_JABATAN as jabatan', 'on' => 'data.ID_JABATAN   = jabatan.ID_JABATAN'],
        ];
        $select = 'data.ID_PN as ID_PN, jabatan.NAMA_JABATAN as NAMA_JABATAN, data.id as ID, data.LEMBAGA, data.IS_CALON';
        $pnjabatan = $this->mglobal->get_data_all('T_PN_JABATAN as data', $join, ['ID' => $idjabatan], $select)[0];
        $pn = $this->mglobal->get_data_all('T_PN', null, ['ID_PN' => $pnjabatan->ID_PN])[0];
        if ($this->session->userdata('IS_KPK') == 0) {
            $this->db->select('*');
            $this->db->from('T_PN p');
            $this->db->join('T_PN_JABATAN j', 'p.ID_PN=j.ID_PN');
            $this->db->where('LEMBAGA', $this->session->userdata('INST_SATKERKD'));
            $this->db->where('p.ID_PN', $pn->ID_PN);
            $this->db->where('p.IS_ACTIVE', '1');
            $this->db->where('j.IS_ACTIVE', '1');
            $this->db->where('j.IS_CURRENT', '1');
            $jml = $this->db->get()->num_rows();

            //display($this->db->last_query());
            if ($jml == 0) {
                echo 'jabatan terakhir tidak di ' . $this->session->userdata('INST_NAMA');
                return false;
            }
        }

        // $user       = $this->muser->get_by_id($pnjabatan->ID)->row();
        $eselon = $this->mglobal->get_data_all('M_ESELON');
        $status_akhir = $this->mglobal->get_status_akhir();
        $instansi_asal = $this->mpn->get_instansi_pn($pn->ID_PN);

        $data = array(
            'jenis' => $jenis,
            'id' => $idjabatan,
            'status_akhir' => $status_akhir,
            'form' => 'mutasi',
            // 'item_data' => $user,
            'item_pn' => $pn,
            'eselon' => $eselon,
            'jabatan' => $pnjabatan,
            'instansi_asal' => $instansi_asal,
            'instansis' => $this->minstansi->list_all()->result(),
            'jabatans' => $this->mjabatan->list_all()->result(),
            'stat' => $stat,
        );
        // display($data);
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    public function mts_calon($idjabatan, $jenis = 1, $stat = null) {
        $this->makses->check_is_write();
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mpn', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);

        if ($jenis == 58) {
            // terpilih
            $rowjabatan = $this->mglobal->get_data_all(
                            'T_PN_JABATAN', [
                        ['table' => 'T_PN', 'on' => 'T_PN_JABATAN.ID_PN = T_PN.ID_PN', 'join' => 'left'],
                        ['table' => 'M_JABATAN', 'on' => 'T_PN_JABATAN.ID_JABATAN  = M_JABATAN.ID_JABATAN', 'join' => 'left'],
                        ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD  = T_PN_JABATAN.LEMBAGA', 'join' => 'left'],
                        ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID  = T_PN_JABATAN.UNIT_KERJA', 'join' => 'left'],
                            ]
                            , ['ID' => $idjabatan]
                            , 'T_PN_JABATAN.ID, T_PN.ID_PN, T_PN.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_UNIT_KERJA.UK_NAMA, T_PN_JABATAN.IS_CALON'
                    )[0];
            // display($rowjabatan);
            $data = array(
                'form' => 'terpilih',
                'item' => $rowjabatan,
                'stat' => $stat
            );
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
            return;
        }

        $join = [
            ['table' => 'M_JABATAN as jabatan', 'on' => 'data.ID_JABATAN   = jabatan.ID_JABATAN'],
        ];
        $select = 'data.ID_PN as ID_PN, jabatan.NAMA_JABATAN as NAMA_JABATAN, data.id as ID, data.LEMBAGA, data.IS_CALON';
        $pnjabatan = $this->mglobal->get_data_all('T_PN_JABATAN as data', $join, ['ID' => $idjabatan], $select)[0];
        $pn = $this->mglobal->get_data_all('T_PN', null, ['ID_PN' => $pnjabatan->ID_PN])[0];
        if ($this->session->userdata('IS_KPK') == 0) {
            $this->db->select('*');
            $this->db->from('T_PN p');
            $this->db->join('T_PN_JABATAN j', 'p.ID_PN=j.ID_PN');
            $this->db->where('LEMBAGA', $this->session->userdata('INST_SATKERKD'));
            $this->db->where('p.ID_PN', $pn->ID_PN);
            $this->db->where('p.IS_ACTIVE', '1');
            $this->db->where('j.IS_ACTIVE', '1');
            $this->db->where('j.IS_CURRENT', '1');
            $jml = $this->db->get()->num_rows();
            if ($jml == 0) {
                echo 'jabatan terakhir tidak di ' . $this->session->userdata('INST_NAMA');
                return false;
            }
        }
        // $user       = $this->muser->get_by_id($pnjabatan->ID)->row();
        $eselon = $this->mglobal->get_data_all('M_ESELON');
        $status_akhir = $this->mglobal->get_status_akhir();
        $instansi_asal = $this->mpn->get_instansi_pn($pn->ID_PN);

        $data = array(
            'jenis' => $jenis,
            'id' => $idjabatan,
            'status_akhir' => $status_akhir,
            'form' => 'mutasi_calon',
            // 'item_data' => $user,
            'item_pn' => $pn,
            'eselon' => $eselon,
            'jabatan' => $pnjabatan,
            'instansi_asal' => $instansi_asal,
            'instansis' => $this->minstansi->list_all()->result(),
            'jabatans' => $this->mjabatan->list_all()->result(),
            'stat' => $stat,
            'isInstansi' => $this->is_instansi()
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    public function savemutasi() {
        if ($_POST && $this->input->post('pn')) {
            $this->load->model('mmutasi');
            $this->load->model('muser');
            $this->load->model('mpn');
            $this->load->model('mglobal');
            $id_pn = $this->input->post('pn');
            $inst_asal = $this->input->post('INST_ASAL');
            $inst_tujuan = $this->input->post('INST_TUJUAN');
            $username = $this->input->post('USERNAME');
            $jenis_mutasi = $this->input->post('JENIS_MUTASI');
            $id_jabatan_asal = $this->mpn->get_jabatan_akhir_by_instansi($id_pn, $inst_asal)->ID_JABATAN;
            $id_jabatan = $this->input->post('JABATAN');
            $deskripsi = $this->input->post('deskripsi');
            $id_user = $this->input->post('ID_USER');
            $unit_kerja = $this->input->post('UNIT_KERJA');
            $data_user = $this->muser->get_by_id($id_user)->row();
            $data_pn = $this->mpn->get_by_id($id_pn)->row();
            $data_jenis_mutasi = $this->mglobal->get_data_all('T_STATUS_AKHIR_JABAT', NULL, NULL, '*', "ID_STATUS_AKHIR_JABAT = '" . $this->input->post('JENIS_MUTASI') . "'")[0];
            $eselon = $this->input->post('ESELON');

            // if ( !is_object($data_user) || !is_object($data_pn) || !$data_jenis_mutasi) {
            //     echo '0';
            // } else {
            if (!empty($id_pn)) {
                if ($data_jenis_mutasi->IS_AKHIR == 1 && $data_jenis_mutasi->IS_MENINGGAL == 1) {
                    // meninggal
                    // update semua jabatan yang sedang aktif
                    // isikan sampai dengannya
                    $pnjabatan['SD'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD_MENJABAT'))));
                    $pnjabatan['ID_STATUS_AKHIR_JABAT'] = $this->input->post('JENIS_MUTASI');
                    $pnjabatan['KETERANGAN_AKHIR_JABAT'] = $this->input->post('KETERANGAN_AKHIR_JABAT');
                    $this->db->where('ID_PN', $id_pn);
                    $this->db->update('T_PN_JABATAN', $pnjabatan);
                    echo '1';
                    // non aktifkan pn
                    // display($this->db->last_query());
                } else if ($data_jenis_mutasi->IS_AKHIR == 1) {
//                        display($_POST);
                    // pensiun
                    // isikan sampai dengannya
                    $pnjabatan['SD'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD_MENJABAT'))));
                    $pnjabatan['ID_STATUS_AKHIR_JABAT'] = $this->input->post('JENIS_MUTASI');
                    $pnjabatan['KETERANGAN_AKHIR_JABAT'] = $this->input->post('KETERANGAN_AKHIR_JABAT');
                    // $this->db->where('SD', '');
                    // update jabatan yang sedang aktif
                    $this->db->where('ID', $this->input->post('ID_PN_JABATAN'));
                    $this->db->where('ID_PN', $id_pn);
                    $this->db->update('T_PN_JABATAN', $pnjabatan);
                    echo '1';
                    // display($this->db->last_query());
                } else if ($data_jenis_mutasi->IS_PINDAH == 1) {
                    // display($_POST);
                    // mutasi
                    $type_file = array('.xls', '.xlsx', '.doc', '.docx', '.pdf', '.png', '.jpg', 'jpeg');
                    $id = $id_pn;
                    $getNik = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id])[0];
                    $filename = 'uploads/data_jabatan/' . $getNik->NIK . '/readme.txt';
                    if (!file_exists($filename)) {
                        $dir = './uploads/data_jabatan/' . $getNik->NIK . '/';
                        $file_to_write = 'readme.txt';
                        $content_to_write = "SK Dari " . $getNik->NAMA . " dengan NIK " . $getNik->NIK;

                        if (is_dir($dir) == false) {
                            mkdir($dir);
                        }

                        $file = fopen($dir . '/' . $file_to_write, "w");

                        fwrite($file, $content_to_write);

                        // closes the file
                        fclose($file);
                    }

                    $urlSK = '';
                    $fileSK = $_FILES['FILE_SK'];
                    $extension = strtolower(@substr(@$fileSK['name'], -4));
                    $maxsize = 500000;
                    if ($fileSK['size'] <= $maxsize) {
                        if (in_array($extension, $type_file)) {
                            $c = save_file($fileSK['tmp_name'], $fileSK['name'], $fileSK['size'], "./uploads/data_jabatan/" . $getNik->NIK . "/", 0, 10000);
                            if ($fileSK['size'] == '') {
                                $urlSK = NULL;
                            } else {
                                $urlSK = time() . "-" . trim($fileSK['name']);
                            }
                            $data_insert = array(
                                'ID_PN' => $id_pn,
                                'ID_STATUS_AKHIR_JABAT' => $jenis_mutasi,
                                'ID_INST_ASAL' => $inst_asal,
                                'ID_INST_TUJUAN' => empty($inst_tujuan) ? NULL : $inst_tujuan,
                                'ID_JABATAN' => $id_jabatan_asal,
                                'ID_JABATAN_BARU' => $id_jabatan,
                                'UNIT_KERJA_LAMA' => empty($data_pn->UNIT_KERJA) ? NULL : $data_pn->UNIT_KERJA,
                                'UNIT_KERJA_BARU' => $unit_kerja,
                                'DESKRIPSI' => $deskripsi,
                                'ESELON_LAMA' => empty($data_pn->ESELON) ? NULL : $data_pn->ESELON,
                                'ESELON_BARU' => $eselon,
                                'FILE_SK' => $urlSK,
                                'ID_PN_JABATAN' => $this->input->post('ID_PN_JABATAN'),
                            );

                            $save = $this->mmutasi->save($data_insert);

                            $this->isInstansiSame($data_insert['ID_INST_ASAL'], $data_insert['ID_INST_TUJUAN'], $save);

                            echo '1';
                        } else {
                            echo 0;
                        }
                    } else {
                        echo 0;
                    }
                    // display($this->db->last_query());
                } else if ($data_jenis_mutasi->IS_AKHIR == 0 && $data_jenis_mutasi->IS_PINDAH == 0 && $data_jenis_mutasi->IS_AKTIF == 1 && $data_jenis_mutasi->IS_MENINGGAL == 0) {
                    // display($_POST);
                    // PROMOSI
                    $type_file = array('.xls', '.xlsx', '.doc', '.docx', '.pdf', '.png', '.jpg', 'jpeg');
                    $id = $id_pn;
                    $getNik = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id])[0];
                    $filename = 'uploads/data_jabatan/' . $getNik->NIK . '/readme.txt';
                    if (!file_exists($filename)) {
                        $dir = './uploads/data_jabatan/' . $getNik->NIK . '/';
                        $file_to_write = 'readme.txt';
                        $content_to_write = "SK Dari " . $getNik->NAMA . " dengan NIK " . $getNik->NIK;

                        if (is_dir($dir) == false) {
                            mkdir($dir);
                        }

                        $file = fopen($dir . '/' . $file_to_write, "w");

                        fwrite($file, $content_to_write);

                        // closes the file
                        fclose($file);
                    }

                    $urlSK = '';
                    $fileSK = $_FILES['FILE_SK'];
                    $extension = strtolower(@substr(@$fileSK['name'], -4));
                    $maxsize = 500000;
                    if ($fileSK['size'] <= $maxsize) {
                        if (in_array($extension, $type_file)) {
                            $c = save_file($fileSK['tmp_name'], $fileSK['name'], $fileSK['size'], "./uploads/data_jabatan/" . $getNik->NIK . "/", 0, 10000);
                            if ($fileSK['size'] == '') {
                                $urlSK = NULL;
                            } else {
                                $urlSK = time() . "-" . trim($fileSK['name']);
                            }
                            $data_insert = array(
                                'ID_PN' => $id_pn,
                                'ID_STATUS_AKHIR_JABAT' => $jenis_mutasi,
                                'ID_INST_ASAL' => $inst_asal,
                                'ID_INST_TUJUAN' => empty($inst_tujuan) ? NULL : $inst_tujuan,
                                'ID_JABATAN' => $id_jabatan_asal,
                                'ID_JABATAN_BARU' => $id_jabatan,
                                'UNIT_KERJA_LAMA' => empty($data_pn->UNIT_KERJA) ? NULL : $data_pn->UNIT_KERJA,
                                'UNIT_KERJA_BARU' => $unit_kerja,
                                'DESKRIPSI' => $deskripsi,
                                'ESELON_LAMA' => empty($data_pn->ESELON) ? NULL : $data_pn->ESELON,
                                'ESELON_BARU' => $eselon,
                                'FILE_SK' => $urlSK,
                                'ID_PN_JABATAN' => $this->input->post('ID_PN_JABATAN'),
                            );

                            $save = $this->mmutasi->save($data_insert);

                            $this->isInstansiSame($data_insert['ID_INST_ASAL'], $data_insert['ID_INST_TUJUAN'], $save);
                            echo '1';
                        } else {
                            echo 0;
                        }
                    } else {
                        echo 0;
                    }
                } else {
                    // non wl
                    // isikan sampai dengannya
                    $pnjabatan['SD'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD_MENJABAT'))));
                    $pnjabatan['ID_STATUS_AKHIR_JABAT'] = $this->input->post('JENIS_MUTASI');
                    $pnjabatan['KETERANGAN_AKHIR_JABAT'] = $this->input->post('KETERANGAN_AKHIR_JABAT');
                    // $this->db->where('SD', '');
                    $this->db->where('ID', $this->input->post('ID_PN_JABATAN'));
                    $this->db->where('ID_PN', $id_pn);
                    $this->db->update('T_PN_JABATAN', $pnjabatan);
                    echo '1';
                }
            } else {
                echo '0';
            }
            // }
        } else {
            echo '0';
        }
    }

    public function delete_calon_pn($id_pn) {
        $data = array(
            'form' => 'delete_calon_pn',
            'id' => $id_pn
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    function do_delete_calon_pn($id_pn) {
        $this->load->model('mglobal', '', TRUE);
        $data_pn = $this->mglobal->get_by_id('T_PN', 'ID_PN', $id_pn);
//  
        $this->db->trans_begin();
        $this->mglobal->delete('T_PN_JABATAN', ['ID_PN' => $id_pn]);
        $hapus = $this->mglobal->delete('T_PN', ['ID_PN' => $id_pn]);
        $this->mglobal->delete('t_user', ['USERNAME' => $data_pn->NIK]);


//        $hapus = $this->mglobal->delete_data('ID_PN', $id_pn, 'T_PN');
//        $hapus = $this->mglobal->delete_data('ID_PN', $id_pn, 'T_PN_JABATAN');
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        if ($hapus) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function reset_password($id_user) {
        if ($this->session->userdata('IS_KPK') == 0) {
            // $this->db->select('*');
            // $this->db->from('T_USER u');
            // $this->db->join('T_PN p', 'u.USERNAME=p.NIK');
            // $this->db->join('T_PN_JABATAN j', 'p.ID_PN=j.ID_PN');
            // $this->db->where('LEMBAGA', $this->session->userdata('INST_SATKERKD'));
            // $this->db->where('u.ID_USER', $id_user);
            // $this->db->where('p.IS_ACTIVE', '1');
            // $this->db->where('j.IS_ACTIVE', '1');
            // $this->db->where('j.IS_CURRENT', '1');
            $where['LEMBAGA'] = $this->session->userdata('INST_SATKERKD');
            $where['p.IS_ACTIVE'] = '1';
            $where['j.IS_ACTIVE'] = '1';
            $where['j.IS_CURRENT'] = '1';
            $join = [
                ['table' => 'T_PN p', 'on' => 'u.USERNAME  = p.NIK'],
                ['table' => 'T_PN_JABATAN j', 'on' => 'p.ID_PN=j.ID_PN']
            ];
            $jml = $this->mglobal->count_data_all('T_USER u', $join, $where, "SUBSTRING(md5(u.ID_USER), 6, 8) = '$id_user'");
            // $jml = $this->db->get()->num_rows();
            if ($jml == 0) {
                echo 'jabatan terakhir tidak di ' . $this->session->userdata('INST_NAMA');
                return false;
            }
        }

        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $data = array(
            'form' => 'reset_password',
            'item' => $this->mglobal->get_data_all('T_USER', NULL, NULL, '*', "SUBSTRING(md5(ID_USER), 6, 8) = '$id_user'")[0],
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    public function do_reset_password() {
        $id_user = $this->input->post('ID_USER');
        $this->makses->check_is_write();
        $this->load->model('muser');
        $password = $this->muser->createRandomPassword(6);
        $data_user = $this->mglobal->get_data_all('T_USER', NULL, NULL, '*', "SUBSTRING(md5(ID_USER), 6, 8) = '$id_user'")[0];
        // $data_user = $this->muser->get_by_id($id_user)->row();
//        $kirim_info = $this->muser->kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $password, 'Pemberitahuan Reset Password');
        $kirim_info = $this->muser->kirim_info_lupa_password($data_user->EMAIL, $data_user->USERNAME, $password);
        if ($kirim_info) {
            $data_update = array(
                'password' => sha1(md5($password)),
                'IS_FIRST' => '1'
            );
            $update = $this->mglobal->update('T_USER', $data_update, null, "SUBSTRING(md5(ID_USER), 6, 8) = '$id_user'");
            // $update = $this->muser->update($id_user, $data_update);
            echo 1;
        } else {
            echo 0;
        }
    }

    public function getInfoPn($id) {
        $this->load->model('mglobal');
        $tmp = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id], 'FOTO, NAMA, NIK, JNS_KEL, TEMPAT_LAHIR, TGL_LAHIR, NPWP, JABATAN, ALAMAT_TINGGAL, EMAIL, NO_HP')[0];

        $data['data'] = $tmp;
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_detail', $data);
    }

    //end update

    public function addjabatan($id_pn = null, $stat = null, $uri = null) {
        $this->load->model('mglobal');
        $calon = $this->input->get('calon');
        $data = array(
            'form' => 'kllJabatan',
            'id_pn' => $id_pn,
            'iscln' => ($calon != '' ? $calon : ''),
            'stts' => ($stat != '' ? $stat : ''),
            'uri' => $uri
        );

        if ($id_pn == 'true') {
            $addPN = $this->session->userdata('addPN');

            $data['pnJabatan'] = [];
            $data['PN'] = (object) ['NIK' => $addPN['pejabat']['NIK'], 'NAMA' => $addPN['pejabat']['NAMA']];
            $data['eselon'] = $this->mglobal->get_data_all('M_ESELON');
        } else {
            if ($this->session->userdata('IS_KPK') == 0) {
                $this->db->select('*');
                $this->db->from('T_PN p');
                $this->db->join('T_PN_JABATAN j', 'p.ID_PN=j.ID_PN');
                $this->db->where('LEMBAGA', $this->session->userdata('INST_SATKERKD'));
                $this->db->where('p.ID_PN', $id_pn);
                $this->db->where('p.IS_ACTIVE', '1');
                $this->db->where('j.IS_ACTIVE', '1');
                $this->db->where('j.IS_CURRENT', '1');
                $jml = $this->db->get()->num_rows();
                if ($jml == 0) {
                    echo 'jabatan terakhir tidak di ' . $this->session->userdata('INST_NAMA');
                    return false;
                }
            }
            $join = [
                ['table' => 'M_INST_SATKER b', 'on' => 'b.INST_SATKERKD = a.LEMBAGA'],
                ['table' => 'M_UNIT_KERJA c', 'on' => 'c.UK_ID = a.UNIT_KERJA'],
                ['table' => 'M_JABATAN d', 'on' => 'd.ID_JABATAN = a.ID_JABATAN'],
                ['table' => 'M_ESELON e', 'on' => 'e.ID_ESELON = a.ESELON'],
                ['table' => 'T_STATUS_AKHIR_JABAT f', 'on' => 'f.ID_STATUS_AKHIR_JABAT = a.ID_STATUS_AKHIR_JABAT', 'join' => 'left']
            ];
            $select = "e.*,a.FILE_SK,a.TMT,a.SD,a.ID,a.ID_PN,a.ID_JABATAN,a.DESKRIPSI_JABATAN,a.LEMBAGA,a.UNIT_KERJA,a.ESELON,c.UK_ID,c.UK_NAMA,b.INST_SATKERKD,b.INST_NAMA,d.ID_JABATAN,d.NAMA_JABATAN,f.STATUS,a.IS_CALON, a.ID_STATUS_AKHIR_JABAT as id_jabat";
            $data['pnJabatan'] = $this->mglobal->get_data_all('T_PN_JABATAN a', $join, ['a.ID_PN' => $id_pn, 'a.IS_ACTIVE' => '1'], $select);
            $data['PN'] = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id_pn])[0];
            $data['eselon'] = $this->mglobal->get_data_all('M_ESELON');
            foreach ($data['pnJabatan'] as $key => $value) {
                $data['pnJabatan'][$key]->proses_mutasi = $this->mglobal->count_data_all('T_MUTASI_PN', NULL, ['ID_PN_JABATAN' => $value->ID]);
            }
        }

        $data['is_instansi'] = $this->is_instansi();
        $data['IS_KPK'] = $this->IS_KPK;

        $sql = "SELECT COUNT(*) FROM T_PN_JABATAN WHERE ID_PN = '" . $id_pn . "'";
        $data['COUNT_JABATAN'] = $this->mglobal->count_data_all('T_PN_JABATAN', null, ['ID_PN' => $id_pn])[0];
        if ($stat == NULL) {
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
        } else if ($stat == '0') {
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
        } else if ($stat == '1') {
            // Jika dari menu nonaktif
            $data['STATUSPN'] = $uri;
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form_nonaktif', $data);
        } else if ($stat == '2') {
            // Jika dari menu calon
            $data['url'] = 'index.php/ereg/all_pn/calonpn';
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
        }
    }

    public function addjabatan_table($id_pn = null, $status = NULL) {
        $this->load->model('mglobal');
        $data = array(
            'form' => 'kllJabatan',
            'id_pn' => $id_pn
        );
        $join = [
            ['table' => 'M_INST_SATKER b', 'on' => 'b.INST_SATKERKD = a.LEMBAGA'],
            ['table' => 'M_UNIT_KERJA c', 'on' => 'c.UK_ID = a.UNIT_KERJA'],
            ['table' => 'M_JABATAN d', 'on' => 'd.ID_JABATAN = a.ID_JABATAN'],
            ['table' => 'M_ESELON e', 'on' => 'e.ID_ESELON = a.ESELON']
        ];
        $select = "e.*,a.FILE_SK,a.TMT,a.SD,a.ID,a.ID_PN,a.ID_JABATAN,a.DESKRIPSI_JABATAN,a.LEMBAGA,a.UNIT_KERJA,a.ESELON,c.UK_ID,c.UK_NAMA,b.INST_SATKERKD,b.INST_NAMA,d.ID_JABATAN,d.NAMA_JABATAN";
        $data['pnJabatan'] = $this->mglobal->get_data_all('T_PN_JABATAN a', $join, ['a.ID_PN' => $id_pn], $select);
        $data['PN'] = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id_pn])[0];
        $data['eselon'] = $this->mglobal->get_data_all('M_ESELON');
        foreach ($data['pnJabatan'] as $key => $value) {
            $data['pnJabatan'][$key]->proses_mutasi = $this->mglobal->count_data_all('T_MUTASI_PN', NULL, ['ID_PN_JABATAN' => $value->ID]);
        }

        $data['is_instansi'] = $this->is_instansi();
        $data['status'] = $status;
        $data['redirect'] = @$this->input->post('redirect');
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_table_jabatan_form', $data);
    }

    public function addklljab($id_pn = null) {
        echo 'aaaaa';
        // $this->load->model('mglobal');
        // $data = array(
        //     'form'      => 'kllJabatan',
        //     'id_pn'     => $id_pn,
        // );
        // $data['pnJabatan'] = $this->mglobal->get_data_all('T_PN_JABATAN', NULL, ['ID_PN' => $id_pn]);
        // $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_jabatan_form', $data);
    }

    public function editjabatan($id = null) {
        $joinJabatan = [
            ['table' => 'M_INST_SATKER', 'on' => 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD']
        ];
        $data = array(
            'form' => 'edit',
            'item' => $this->mglobal->get_data_all('T_PN_JABATAN', $joinJabatan, NULL, '*', "ID = '$id'")[0],
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    public function deletejabatan($id = null) {
        $joinJabatan = [
            ['table' => 'M_INST_SATKER', 'on' => 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD']
        ];

        $data = array(
            'form' => 'delete',
            'item' => $this->mglobal->get_data_all('T_PN_JABATAN', $joinJabatan, NULL, '*', "ID = '$id'")[0],
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    public function detailjabatan($id = null) {
        $joinJabatan = [
            ['table' => 'M_INST_SATKER', 'on' => 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD']
        ];
        $data = array(
            'form' => 'detail',
            'item' => $this->mglobal->get_data_all('T_PN_JABATAN', $joinJabatan, NULL, '*', "ID = '$id'")[0],
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    public function savejabatan() {
        $this->load->model('mglobal');
        $allowedExts = array("gif", "jpeg", "jpg", "png", "pdf", "doc", "docx");
        $ID_PN = $this->input->post('ID_PN', TRUE);
        $PN = $this->mglobal->get_data_all('T_PN', NULL, NULL, '*', "ID_PN = '$ID_PN'")[0];
        $NIK = $PN->NIK;
        $filename = 'uploads/PN/' . $NIK . '/readme.txt';

        $this->db->trans_begin();
        if ($this->input->post('act', TRUE) == 'doinsert') {

            //upload sk
            if (!file_exists($filename)) {
                $dir = './uploads/PN/' . $NIK . '/';
                $file_to_write = 'readme.txt';
                $content_to_write = "SK Dari nik " . $NIK;
                if (is_dir($dir) === false) {
                    mkdir($dir);
                }
                $file = fopen($dir . '/' . $file_to_write, "w");
                fwrite($file, $content_to_write);
                // closes the file
                fclose($file);
            }

            $FILE = 'FILE_SK';
            $FILE_SK = '';
            $temp = explode(".", $_FILES[$FILE]["name"]);
            $extension = end($temp);
            $newName = time() . "-" . $_FILES[$FILE]["name"];

            $type = array("image/gif", "image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png", "application/msword", "application/doc", "application/txt", "application/pdf", "text/pdf");

            $maxsize = 200000000;

            if (in_array($_FILES[$FILE]["type"], $type) && ($_FILES[$FILE]["size"] < $maxsize) && in_array($extension, $allowedExts)) {
                if ($_FILES[$FILE]["error"] > 0) {
                    // echo "Return Code: " . $_FILES[$FILE]["error"] . "<br>";
                } else {
                    if (file_exists("uploads/PN/" . $NIK . '/' . $newName)) {
                        // echo $_FILES[$FILE]["name"] . " already exists. ";
                    } else {
                        // chmod('upload/', 0777);
                        move_uploaded_file($_FILES[$FILE]["tmp_name"], "./uploads/PN/" . $NIK . '/' . $newName);
                    }
                    $FILE_SK = $newName;
                }
            } else {
                // echo "Invalid file";
            }

            $id_pn = $this->input->post('ID_PN', TRUE);
            $lembaga = $this->input->post('LEMBAGA', TRUE);

            $jabatan = array(
                'ID' => $this->input->post('ID', TRUE),
                'ID_JABATAN' => $this->input->post('JABATAN', TRUE),
                'ID_PN' => $id_pn,
                'DESKRIPSI_JABATAN' => strtoupper($this->input->post('DESKRIPSI_JABATAN', TRUE)),
                'ESELON' => $this->input->post('ESELON', TRUE),
                'LEMBAGA' => $lembaga,
                'UNIT_KERJA' => $this->input->post('UNIT_KERJA', TRUE),
                'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR', TRUE),
                'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR', TRUE),
                'FILE_SK' => $FILE_SK,
                'TMT' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
                'SD' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD', TRUE)))),
                // 'IS_ACTIVE'      => 1,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $this->db->insert('T_PN_JABATAN', $jabatan);
        } else if ($this->input->post('act', TRUE) == 'doupdate') {

            //upload sk
            if (!file_exists($filename)) {
                $dir = './uploads/PN/' . $NIK . '/';
                $file_to_write = 'readme.txt';
                $content_to_write = "SK Dari nik " . $NIK;
                if (is_dir($dir) === false) {
                    mkdir($dir);
                }
                $file = fopen($dir . '/' . $file_to_write, "w");
                fwrite($file, $content_to_write);
                // closes the file
                fclose($file);
            }

            $FILE = 'FILE_SK';
            $FILE_SK = $this->input->post('FILE_SK_OLD', TRUE);
            $temp = explode(".", $_FILES[$FILE]["name"]);
            $extension = end($temp);
            $newName = time() . "-" . $_FILES[$FILE]["name"];

            $type = array("image/gif", "image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png", "application/msword", "application/doc", "application/txt", "application/pdf", "text/pdf");

            $maxsize = 200000000;

            if (in_array($_FILES[$FILE]["type"], $type) && ($_FILES[$FILE]["size"] < $maxsize) && in_array($extension, $allowedExts)) {
                if ($_FILES[$FILE]["error"] > 0) {
                    // echo "Return Code: " . $_FILES[$FILE]["error"] . "<br>";
                } else {
                    if (file_exists("uploads/PN/" . $NIK . '/' . $newName)) {
                        // echo $_FILES[$FILE]["name"] . " already exists. ";
                    } else {
                        // chmod('upload/', 0777);
                        move_uploaded_file($_FILES[$FILE]["tmp_name"], "./uploads/PN/" . $NIK . '/' . $newName);
                    }
                    $FILE_SK = $newName;
                }
            } else {
                // echo "Invalid file";
            }

            $jabatan = array(
                // 'ID'                 => $this->input->post('ID_LHKPN', TRUE),
                // 'ID_JABATAN'        => $this->input->post('ID_LHKPN', TRUE),
                'ID' => $this->input->post('ID', TRUE),
                'ID_JABATAN' => $this->input->post('JABATAN', TRUE),
                'ID_PN' => $this->input->post('ID_PN', TRUE),
                'DESKRIPSI_JABATAN' => strtoupper($this->input->post('DESKRIPSI_JABATAN', TRUE)),
                'ESELON' => $this->input->post('ESELON', TRUE),
                'LEMBAGA' => $this->input->post('LEMBAGA', TRUE),
                'UNIT_KERJA' => $this->input->post('UNIT_KERJA', TRUE),
                'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR', TRUE),
                'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR', TRUE),
                'FILE_SK' => $FILE_SK,
                'TMT' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
                'SD' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD', TRUE)))),
                // 'IS_ACTIVE'      => 1,           
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $jabatan['ID'] = $this->input->post('ID', TRUE);
            $this->db->where('ID', $jabatan['ID']);
            $this->db->update('T_PN_JABATAN', $jabatan);
            // echo $this->db->last_query();
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    public function saveklljabatan() {
        if ($this->input->post('act', TRUE) == 'doinsert') {

            $this->db->trans_begin();
            $this->load->model('mglobal', '', TRUE);
            //upload file SK
            $type_file = array('xls', 'xlsx', 'doc', 'docx', 'pdf', 'png', 'jpg', 'jpeg');

            $id = $this->input->post('PNID');
            $stat = false;
            if ($id == 'true') {
                $stat = true;
                $this->load->model('mpn');
                $this->load->model('muser');

                $addPN = $this->session->userdata('addPN');

                $insert = $this->mpn->save($addPN['pejabat']);
                $id = $this->db->insert_id();

                $this->muser->save($addPN['user']);


                //$kirim_info = $this->mpn->kirim_info_pn($addPN['pejabat']['EMAIL'], $addPN['pejabat']['NIK'], $addPN['user']['PASSWORD'], 'Pemberitahuan Informasi Akun');
            }

            $getNik = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id])[0];
            $filename = 'uploads/data_jabatan/' . $getNik->NIK . '/readme.txt';
            if (!file_exists($filename)) {
                $dir = './uploads/data_jabatan/' . $getNik->NIK . '/';
                $file_to_write = 'readme.txt';
                $content_to_write = "SK Dari " . $getNik->NAMA . " dengan NIK " . $getNik->NIK;

                if (is_dir($dir) == false) {
                    mkdir($dir);
                }

                $file = fopen($dir . '/' . $file_to_write, "w");

                fwrite($file, $content_to_write);

                // closes the file
                fclose($file);
            }

            $urlSK = '';
            $fileSK = $_FILES['FILE_SK'];
            $arrayFile = explode('.', $fileSK['name']);
            $extension = strtolower(end($arrayFile));
            $maxsize = 500000;
            if ($this->input->post('IS_CALON') != 1) {
                if ($fileSK['size'] <= $maxsize) {
                    if (in_array($extension, $type_file)) {
                        $c = save_file($fileSK['tmp_name'], $fileSK['name'], $fileSK['size'], "./uploads/data_jabatan/" . $getNik->NIK . "/", 0, 10000);
                        if ($fileSK['size'] == '') {
                            $urlSK = NULL;
                        } else {
                            $urlSK = time() . "-" . trim($fileSK['name']);
                        }
                        $lembaga = $this->input->post('LEMBAGA');

                        $cek = $this->mglobal->count_data_all('T_PN_JABATAN', NULL, ['ID_PN' => $id, 'ID_STATUS_AKHIR_JABAT' => '0', 'LEMBAGA' => $lembaga]);
                        if ($cek) {
                            $this->db->trans_rollback();
                            echo 'PN sudah terdaftar di Instansi tsb!';
                            return;
                        }

                        $jabatanPn = array(
                            'IS_CALON' => $this->input->post('IS_CALON'),
                            'ID_JABATAN' => $this->input->post('JABATAN'),
                            'LEMBAGA' => $lembaga,
                            'UNIT_KERJA' => $this->input->post('UNIT_KERJA'),
                            'ID_PN' => $id,
                            'DESKRIPSI_JABATAN' => $this->input->post('DESK_JABATAN'),
                            'ESELON' => $this->input->post('ESELON'),
                            'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR'),
                            'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR'),
                            'FILE_SK' => $urlSK,
                            'TMT' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
                            'SD' => ($this->input->post('SD') != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD', TRUE)))) : ''),
                            'IS_ACTIVE' => 1,
                        );

                        $countKJ = $this->mglobal->get_data_all("T_PN_JABATAN", NULL, ["ID_PN" => $id], "count('ID_PN') as jumlah")[0];
                        $jumData = $countKJ->jumlah;
                        if ($jumData >= '5') {
                            echo '2';
                        } else {
                            $save = $this->db->insert('T_PN_JABATAN', $jabatanPn);
                            if ($save) {
                                if ($stat == true) {
                                    echo json_encode(['id' => $id, 'status' => '3']);
                                } else {
                                    echo '1';
                                }
                            } else {
                                echo 'Gagal menginputkan data';
                            }
                        }
                    } else {
                        echo 'Error Extension';
                    }
                } else {
                    echo 'Size Kebesaran!';
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                return;
            } else {
                if ($fileSK['size'] <= $maxsize) {
                    if (in_array($extension, $type_file)) {
                        $c = save_file($fileSK['tmp_name'], $fileSK['name'], $fileSK['size'], "./uploads/data_jabatan/" . $getNik->NIK . "/", 0, 10000);
                        if ($fileSK['size'] == '') {
                            $urlSK = NULL;
                        } else {
                            $urlSK = time() . "-" . trim($fileSK['name']);
                        }
                    }
                }
                $jabatanPn = array(
                    'IS_CALON' => $this->input->post('IS_CALON'),
                    'ID_JABATAN' => $this->input->post('JABATAN'),
                    'LEMBAGA' => $this->input->post('LEMBAGA'),
                    'UNIT_KERJA' => $this->input->post('UNIT_KERJA'),
                    'ID_PN' => $id,
                    'DESKRIPSI_JABATAN' => $this->input->post('DESK_JABATAN'),
                    'ESELON' => $this->input->post('ESELON'),
                    'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR'),
                    'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR'),
                    'FILE_SK' => $urlSK,
                    'TMT' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
                    'SD' => ($this->input->post('SD') != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD', TRUE)))) : ''),
                    'IS_ACTIVE' => 1,
                );

                $countKJ = $this->mglobal->get_data_all("T_PN_JABATAN", NULL, ["ID_PN" => $id], "count('ID_PN') as jumlah")[0];
                $jumData = $countKJ->jumlah;
                if ($jumData >= '5') {
                    echo '2';
                } else {
                    $save = $this->db->insert('T_PN_JABATAN', $jabatanPn);
                    // echo "<pre>".$save;
                    // print_r ($jabatanPn);
                    // echo "</pre>";
                    // echo $this->db->last_query();exit();
                    if ($save) {
                        echo '1';
                    } else {
                        echo '0';
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
            }
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $allowedExts = array('xls', 'xlsx', 'doc', 'docx', 'pdf', 'jpeg', 'jpg', 'png');
            $ID = $this->input->post('ID');
            $NIK = $this->input->post('NNIK');
            $filename = 'uploads/data_jabatan/' . $NIK . '/readme.txt';

            if (!file_exists($filename)) {
                $dir = './uploads/data_jabatan/' . $NIK . '/';
                $file_to_write = 'readme.txt';
                $content_to_write = "SK Dari nik " . $NIK;
                if (is_dir($dir) === false) {
                    mkdir($dir);
                }
                $file = fopen($dir . '/' . $file_to_write, "w");
                fwrite($file, $content_to_write);
                // closes the file
                fclose($file);
            }

            $FILE = 'FILE_SK';
            $FILE_SK = $this->input->post('FILE_SK_OLD', TRUE);
            $temp = explode(".", $_FILES[$FILE]["name"]);
            $extension = end($temp);
            $newName = time() . "-" . $_FILES[$FILE]["name"];

            $maxsize = 500000;
            // echo $extension;
            if (($_FILES[$FILE]["size"] <= $maxsize) && in_array($extension, $allowedExts)) {
                if ($_FILES[$FILE]["error"] > 0) {
                    // echo "Return Code: " . $_FILES[$FILE]["error"] . "<br>";
                } else {
                    if (file_exists("uploads/data_jabatan/" . $NIK . '/' . $newName)) {
                        // echo $_FILES[$FILE]["name"] . " already exists. ";
                    } else {
                        // chmod('upload/', 0777);
                        move_uploaded_file($_FILES[$FILE]["tmp_name"], "./uploads/data_jabatan/" . $NIK . '/' . $newName);
                    }
                    $FILE_SK = $newName;
                }
            } else {
                // echo "Invalid file";
            }

            $jabatanPn = array(
                'ID_JABATAN' => $this->input->post('JABATAN'),
                'LEMBAGA' => $this->input->post('LEMBAGA'),
                'UNIT_KERJA' => $this->input->post('UNIT_KERJA'),
                'DESKRIPSI_JABATAN' => $this->input->post('DESK_JABATAN'),
                'ID_PN' => $this->input->post('INIIDPN'),
                'ESELON' => $this->input->post('ESELON'),
                'ALAMAT_KANTOR' => $this->input->post('ALAMAT_KANTOR'),
                'EMAIL_KANTOR' => $this->input->post('EMAIL_KANTOR'),
                'TMT' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('TMT', TRUE)))),
                'SD' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('SD', TRUE)))),
            );


            if ($this->input->post('iscalon') != '1') {
                $jabatanPn['IS_CALON'] = $this->input->post('IS_CALON');
            }

            if (in_array($extension, $allowedExts) && ($_FILES[$FILE]["size"] <= $maxsize) && ($_FILES[$FILE]["size"] != '')) {
                $jabatanPn['FILE_SK'] = $FILE_SK;
            } elseif ((!in_array($extension, $allowedExts) || ($_FILES[$FILE]["size"] > $maxsize)) && ($_FILES[$FILE]["size"] != '')) {
                echo 'file terlalu besar atau tidak sesuai format';
            }
            $jabatanPn['ID'] = $ID;
            $this->db->where('ID', $jabatanPn['ID']);
            $update = $this->db->update('T_PN_JABATAN', $jabatanPn);
            if ($update) {
                echo '1';
            } else {
                echo '0';
            }
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $jabatanPn = array(
                'IS_ACTIVE' => '0',
                'IS_DELETED' => '1',
            );
            $jabatanPn['ID'] = $this->input->post('ID');
            $this->db->where('ID', $jabatanPn['ID']);
            $update = $this->db->update('T_PN_JABATAN', $jabatanPn);
            //$delete = $this->db->delete('T_PN_JABATAN', $jabatanPn);
            if ($update) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }

    public function showEditKllJab($ID, $IDPN, $stat = NULL, $uri = NULL) {
        $this->load->model('mglobal');
        $join = [
            ['table' => 'T_PN b', 'on' => 'b.ID_PN = a.ID_PN']
        ];
        $select = "a.IS_CALON,a.ID,a.ID_JABATAN,a.ID_PN,a.DESKRIPSI_JABATAN,a.ESELON,a.LEMBAGA,a.UNIT_KERJA,a.ALAMAT_KANTOR,a.EMAIL_KANTOR,a.TMT,a.SD,a.FILE_SK,b.NIK";
        $data = array(
            'IDPN' => $IDPN,
            'form' => 'editKJ',
            'Eeselon' => $this->mglobal->get_data_all('M_ESELON'),
            'item' => $this->mglobal->get_data_all('T_PN_JABATAN a', $join, NULL, $select, "a.ID = '$ID'")[0],
            'stat' => $stat,
            'uri' => $uri
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    public function showDeleteKllJab($ID, $IDPN) {
        $this->load->model('mglobal');
        $join = [
            ['table' => 'M_INST_SATKER b', 'on' => 'b.INST_SATKERKD = a.LEMBAGA'],
            ['table' => 'M_UNIT_KERJA c', 'on' => 'c.UK_ID = a.UNIT_KERJA'],
            ['table' => 'M_JABATAN d', 'on' => 'd.ID_JABATAN = a.ID_JABATAN']
        ];
        $select = "a.ID,a.ID_PN,a.ID_JABATAN,a.DESKRIPSI_JABATAN,a.LEMBAGA,a.UNIT_KERJA,c.UK_ID,c.UK_NAMA,b.INST_SATKERKD,b.INST_NAMA,d.ID_JABATAN,d.NAMA_JABATAN";
        $data = array(
            'IDPN' => $IDPN,
            'form' => 'deleteKJ',
            'item' => $this->mglobal->get_data_all('T_PN_JABATAN a', $join, NULL, $select, "a.ID = '$ID'")[0],
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_jabatan_form', $data);
    }

    /**
     * Form Menonaktifkan Penyelenggara Negara
     * 
     * @return html form menonaktifkan pn/wl
     */
    public function nonaktifkan($ID_PN) {
        $this->load->model('mglobal');
        $data = array(
            'form' => 'nonaktifkan',
            'item' => $this->mglobal->get_data_all('T_PN', NULL, NULL, '*', "ID_PN = $ID_PN")[0],
        );
        $join = [
            ['table' => 'M_UNIT_KERJA as b', 'on' => 'a.UNIT_KERJA   = b.UK_ID'],
            ['table' => 'M_INST_SATKER as c', 'on' => 'a.LEMBAGA      = c.INST_SATKERKD'],
        ];
        $data['detJabatan'] = $this->mglobal->get_data_all('T_PN_JABATAN a', $join, ['a.ID_PN' => $ID_PN], 'a.*,b.*,c.*');
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    /**
     * List Calon PN/WL
     * 
     * @return html Penyelenggara Negara List
     */
    public function calonpn($offset = 0, $cetak = false) {
        if (in_array($cetak, ['pdf', 'excel', 'word'])) {
            $this->iscetak = true;
            $this->limit = 0;
        }

        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_KPK, IS_INSTANSI, IS_USER_INSTANSI');

        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
        $this->base_url = 'index.php/ereg/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/';
        $this->uri_segment = 4;
        $this->offset = $this->uri->segment($this->uri_segment);


        $this->db->start_cache();

        $this->db->select('
            P.ID_PN,
            P.NIK,
            P.NAMA,
            P.GELAR_DEPAN,
            P.GELAR_BELAKANG,
            PJ.UNIT_KERJA,
            PJ.SUB_UNIT_KERJA,
            U.ID_USER,
            PJ.IS_DELETED,
            V1.ID_PN AS ID_PN_DIJABATAN,
            group_concat(
                CONCAT( CONVERT( REPEAT( "0",( 5 - LENGTH( V1.LEMBAGA ) ) ) USING latin1 ), V1.LEMBAGA ) SEPARATOR ","
            ) AS LEMBAGA,
            group_concat(
                CONCAT( REPEAT( "0",( 5 - LENGTH( V1.ID_JABATAN ) ) ), V1.ID_JABATAN ) SEPARATOR ","
            ) AS JABATAN,
            group_concat(
                CONCAT( REPEAT( "0",( 5 - LENGTH( V1.IS_CALON ) ) ), V1.IS_CALON ) SEPARATOR ","
            ) AS IS_CALON,
            group_concat(
                CONCAT( ifnull( V1.ID, "" ), ":||:", ifnull( V1.ID_STATUS_AKHIR_JABAT, "" ), ":||:", CONVERT( ifnull( V1.STATUS, "" ) USING utf8 ), ":||:", ifnull( V1.ID_PN_JABATAN, "NULL" ), ":||:", CONVERT( ifnull( V1.LEMBAGA, "" ) USING utf8 ), ":||:", ifnull( V1.MJC_NAMA, "" ), " ", CONVERT( ifnull( V1.DESKRIPSI_JABATAN, "" ) USING utf8 ), " - ", "<span style=\"font-weight : bold\">", CONVERT( ifnull( V1.MUC_NAMA, "" ) USING utf8 ), " - ", ifnull( V1.MIC_AKRONIM, V1.MIC_NAMA ), "</span>", ":||:", ifnull( V1.TMT, "" ), ":||:", CONVERT( ifnull( V1.SD, "" ) USING utf8 ), ":||:", ifnull( V1.IS_CALON, "" ), ":||:", ifnull( V1.MIT_NAMA, "" ) ) SEPARATOR ":|||:"
            ) AS NAMA_JABATAN,
            V1.*', false); // parameter false wajib
        $this->db->from('T_PN P');
        $this->db->join('T_USER U', 'U.USERNAME = P.NIK');
        $this->db->join('V_JABATAN_CURRENT_INC_CALON V1', 'V1.ID_PN = P.ID_PN');
        $this->db->join('T_PN_JABATAN PJ', 'P.ID_PN = PJ.ID_PN');
        $this->db->where('PJ.IS_DELETED', 0);

        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;
            $IS_KPK = $role[0]->IS_KPK;

            if ($inst == '1' || $user == '1') {
                $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
                $this->db->where("(V1.LEMBAGA like '" . $INST_SATKERKD . "' OR U.INST_SATKERKD = '" . $INST_SATKERKD . "')", null, false);
            }
        }

        if (@$this->CARI['USEWHEREONLY']) {
            $this->db->where('P.NIK', $this->CARI['TEXT']);
        } else {
            if (@$this->CARI['TEXT']) {
                $this->db->where("(PJ.UNIT_KERJA LIKE '%" . $this->CARI['TEXT'] . "%' OR PJ.SUB_UNIT_KERJA LIKE '%" . $this->CARI['TEXT'] . "%' OR PJ.DESKRIPSI_JABATAN LIKE '%" . $this->CARI['TEXT'] . "%' OR P.NAMA LIKE '%" . $this->CARI['TEXT'] . "%' OR P.NIK LIKE '%" . $this->CARI['TEXT'] . "%')");
            }

            if (@$this->CARI['INST'] && @$this->CARI['INST'] != -99) {
                $this->db->where("V1.LEMBAGA like '" . $this->CARI['INST'] . "'", null, false);
            }
        }

        $this->db->order_by('P.NAMA', 'asc');
        $this->db->group_by('P.ID_PN'); // parameter false wajib

        $this->total_rows = $this->db->get('')->num_rows();

        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();
        $this->end = $query->num_rows();
        // echo $this->db->last_query();
        $this->db->flush_cache();

        $havelhkpn = array();
        foreach ($this->items as $item) {
            $ID_PN[] = $item->ID_PN;
            $havelhkpn[$item->ID_PN] = false;
        }

        if (!empty($ID_PN)) {
            $ID_PN_SEL = implode($ID_PN, ',');
            $sql = "SELECT * FROM T_LHKPN WHERE ID_PN IN ($ID_PN_SEL)";
            $query = $this->db->query($sql)->result();
            foreach ($query as $lhkpn) {
                $havelhkpn[$lhkpn->ID_PN] = true;
            }
        }

        $data = array(
            'linkCetak' => 'index.php/ereg/all_pn/calonpn/0',
            'title' => 'Calon PN/WL',
            'titleCetak' => 'cetakpn',
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'CARI' => @$this->CARI,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E-reg' => 'index.php/dashboard/efilling',
                'PN' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
            'instansis' => $this->mglobal->get_data_all('M_INST_SATKER'),
            'status_akhir' => $this->mglobal->get_data_all('T_STATUS_AKHIR_JABAT'),
            'IS_KPK' => @$IS_KPK,
            'havelhkpn' => $havelhkpn,
            'iscln' => 1
        );

        // load view
        if (@$this->iscetak) {
            ng::exportDataTo($data, $cetak, strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . 'index' . '_' . 'cetak', 'cetakpn');
        } else {
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
        }
    }

    /**
     * Cek NIK
     * 
     * 
     * @return boolean NIK EXIST
     */
    public function getNIK_WS($NIK = NULL) {
        $item = $this->load->model('minstansi', '', TRUE);
        $row = $this->minstansi->get_xt_dummy_nik($NIK)->row();
//         echo $row->NAMA;
        $dummy_nik = array();
        $dummy_nik['nama'] = $row->NAMA;
        $dummy_nik['tmp_lahir'] = $row->TEMPAT_LAHIR;
        $dummy_nik['tgl_lahir'] = date('d/m/Y', strtotime($row->TANGGAL_LAHIR));
        $dummy_nik['jk'] = $row->JENIS_KELAMIN;



        $this->output->set_output(json_encode($dummy_nik));
    }

    public function cekNIK($NIK, $status = NULL) {
        $record = $this->mglobal->get_data_all('T_PN', null, ['NIK' => trim(urldecode($NIK))], '*');
        if (count($record)) {
            $data['status'] = '1';

            $this->db->select('T_PN.ID_PN, T_PN.NIK, T_PN.NAMA, JABATAN.NAMA_JABATAN,  t_pn_jabatan.DESKRIPSI_JABATAN, T_USER.ID_USER,  JAB.NAMA_JABATAN N_JAB, SUK.SUK_NAMA N_SUK, UK.UK_NAMA N_UK, INTS.INST_NAMA, ID_STATUS_AKHIR_JABAT');
            $this->db->from('T_PN');
            $this->db->join('T_USER', 'T_USER.USERNAME = T_PN.NIK');
            $this->db->join('t_pn_jabatan', 't_pn_jabatan.ID_PN = T_PN.ID_PN');

            $this->db->join('M_JABATAN JAB', 't_pn_jabatan.ID_JABATAN = JAB.ID_JABATAN', 'left');
            $this->db->join('m_sub_unit_kerja SUK', 'SUK.SUK_ID = JAB.SUK_ID', 'left');
            $this->db->join('m_unit_kerja UK', 'UK.UK_ID = JAB.UK_ID', 'left');
            $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = UK.UK_LEMBAGA_ID', 'left');


            $this->db->join('(
                SELECT DISTINCT(T_PN.ID_PN) as ID_PN_HAVEACTIVE FROM T_PN
                    LEFT JOIN T_PN_JABATAN ON T_PN_JABATAN.ID_PN = T_PN.ID_PN
                    LEFT JOIN T_STATUS_AKHIR_JABAT ON T_PN_JABATAN.ID_STATUS_AKHIR_JABAT = T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT
            ) PN_HAVEACTIVE', 'PN_HAVEACTIVE.ID_PN_HAVEACTIVE = T_PN.ID_PN');
            $this->db->join('(
            select  T_PN_JABATAN.ID_PN AS ID_PN_DIJABATAN,
                group_concat(CONCAT( REPEAT( "0", 5 - LENGTH( T_PN_JABATAN.LEMBAGA ) ), T_PN_JABATAN.LEMBAGA )) LEMBAGA,
                group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_PN_JABATAN.ID_JABATAN)),T_PN_JABATAN.ID_JABATAN)) JABATAN,
                group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_PN_JABATAN.IS_CALON)),T_PN_JABATAN.IS_CALON)) IS_CALON,
                group_concat(
                    CONCAT(
                        IFNULL(T_PN_JABATAN.ID,""),":||:",
                        IFNULL(T_PN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":||:",
                        IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":||:",
                        IFNULL(T_MUTASI_PN.ID_PN_JABATAN,"NULL"),":||:",
                        IFNULL(T_PN_JABATAN.LEMBAGA,""),":||:",
                        IFNULL(M_JABATAN.NAMA_JABATAN,""), " - ",
                        IFNULL(T_PN_JABATAN.DESKRIPSI_JABATAN,""), " - ",
                        -- "(", IFNULL(M_ESELON.ESELON,""), ") - ", 
                        "<span style=\'font-weight:bold\'> ",IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ", 
                        IFNULL(M_INST_SATKER.INST_AKRONIM,M_INST_SATKER.INST_NAMA),"</span>",":||:",
                        IFNULL(T_PN_JABATAN.TMT,""),":||:",
                        IFNULL(T_PN_JABATAN.SD,""),":||:",
                        IFNULL(T_PN_JABATAN.IS_CALON,""),":||:",
                        IFNULL(M_INST_SATKER_MUTASI.INST_AKRONIM,""),":||:",
                        IFNULL(T_MUTASI_PN.ID_INST_TUJUAN,"")
                    ) separator ":|||:"
                ) as NAMA_JABATAN, T_STATUS_AKHIR_JABAT.IS_AKTIF
            FROM T_PN_JABATAN
                INNER JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN
                INNER JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_PN_JABATAN.LEMBAGA
                INNER JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_PN_JABATAN.UNIT_KERJA
                LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_PN_JABATAN.ID_STATUS_AKHIR_JABAT
                LEFT JOIN T_MUTASI_PN ON T_MUTASI_PN.ID_PN_JABATAN = T_PN_JABATAN.ID
                LEFT JOIN M_INST_SATKER AS M_INST_SATKER_MUTASI ON M_INST_SATKER_MUTASI.INST_SATKERKD = T_MUTASI_PN.ID_INST_TUJUAN
                GROUP BY T_PN_JABATAN.ID_PN
            ) JABATAN', 'JABATAN.ID_PN_DIJABATAN = PN_HAVEACTIVE.ID_PN_HAVEACTIVE',
                    // 'JABATAN.ID_PN_DIJABATAN = T_PN.ID_PN',
                    'left');

            $this->db->where('T_PN.ID_PN', $record[0]->ID_PN);
            $this->db->where('T_PN.IS_ACTIVE', 1);
            $this->db->where('t_pn_jabatan.IS_CURRENT', 1);
            $query = $this->db->get('', $this->limit, $this->offset);
//            display($this->db->last_query());
            $item = $query->result();


            if ($this->session->userdata('IS_KPK') !== '1') {
                $this->db->where('ID_INST_TUJUAN', $this->session->userdata('INST_SATKERKD'));
            }

            $this->db->select('A.ID_MUTASI,
							A.ID_PN,
							A.ID_INST_ASAL,
							A.ID_INST_TUJUAN,
							A.STATUS_APPROVAL,
							A.ID_JABATAN AS JABATAN_LAMA,
							A.ID_JABATAN_BARU AS JABATAN_BARU,
							A.UNIT_KERJA_LAMA,
							A.UNIT_KERJA_BARU,
                            A.ID_STATUS_AKHIR_JABAT,
                            C.STATUS as STATUS_JABAT,
							NIK,
							NAMA
							')//ada fild UNIT_KERJA (dihapus)
                    ->from('T_MUTASI_PN A')
                    ->join('T_PN B', 'A.ID_PN=B.ID_PN', 'left')
                    ->join('T_STATUS_AKHIR_JABAT C', 'A.ID_STATUS_AKHIR_JABAT=C.ID_STATUS_AKHIR_JABAT', 'left')
                    ->where('B.ID_PN', $record[0]->ID_PN);
            if ($this->session->userdata('IS_KPK') == 1) {
                $this->db->where('( STATUS_APPROVAL IS NULL OR STATUS_APPROVAL = 0) AND ID_INST_ASAL != ' . $this->session->userdata('INST_SATKERKD'), NULL, false);
            } else {
                $this->db->where('( STATUS_APPROVAL IS NULL OR STATUS_APPROVAL = 0) AND ID_INST_TUJUAN = ' . $this->session->userdata('INST_SATKERKD'), NULL, false);
            }

            $this->db->order_by('B.NIK', 'asc');
            $masuk = $this->db->get()->result();

            $data = [
                'items' => $item,
                'id_pn' => $record[0]->ID_PN,
                'nik' => $record[0]->NIK,
                'nama' => $record[0]->NAMA,
                'status_akhir' => $this->mglobal->get_data_all('T_STATUS_AKHIR_JABAT'),
                'masuk' => !empty($masuk) ? true : false,
                'IS_KPK' => $this->IS_KPK,
                'redirect' => @$this->input->post('redirect'),
                'status' => $status,
            ];

            if (!empty($masuk)) {
                $data['items_masuk'] = $this->load->view('all_pn/all_pn_mutasimasuk_table', ['items' => $masuk], TRUE);
            }

            echo $this->load->view('all_pn/all_pn_table', $data, TRUE);
        } else {
            echo 0;
        }
    }

    /**
     * Penyelenggara Negara List
     * 
     * @return html Penyelenggara Negara List
     */
    public function semuapn($offset = 0, $cetak = false) {
        if (in_array($cetak, ['pdf', 'excel', 'word'])) {
            $this->iscetak = true;
            $this->limit = 0;
        }

        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_KPK, IS_INSTANSI, IS_USER_INSTANSI');

        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
        $this->base_url = 'index.php/ereg/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/';
        $this->uri_segment = 4;
        $this->offset = $this->uri->segment($this->uri_segment);


        $this->db->start_cache();

        $this->db->select('
            P.ID_PN,
            P.NIK,
            P.NAMA,
            P.GELAR_DEPAN,
            P.GELAR_BELAKANG,
            U.ID_USER,
            V1.ID_PN AS ID_PN_DIJABATAN,
            group_concat(
                CONCAT( CONVERT( REPEAT( "0",( 5 - LENGTH( V1.LEMBAGA ) ) ) USING latin1 ), V1.LEMBAGA ) SEPARATOR ","
            ) AS LEMBAGA,
            group_concat(
                CONCAT( REPEAT( "0",( 5 - LENGTH( V1.ID_JABATAN ) ) ), V1.ID_JABATAN ) SEPARATOR ","
            ) AS JABATAN,
            group_concat(
                CONCAT( REPEAT( "0",( 5 - LENGTH( V1.IS_CALON ) ) ), V1.IS_CALON ) SEPARATOR ","
            ) AS IS_CALON,
            group_concat(
                CONCAT( ifnull( V1.ID, "" ), ":||:", ifnull( V1.ID_STATUS_AKHIR_JABAT, "" ), ":||:", CONVERT( ifnull( V1.STATUS, "" ) USING utf8 ), ":||:", ifnull( V1.ID_PN_JABATAN, "NULL" ), ":||:", CONVERT( ifnull( V1.LEMBAGA, "" ) USING utf8 ), ":||:", ifnull( V1.MJC_NAMA, "" ), " ", CONVERT( ifnull( V1.DESKRIPSI_JABATAN, "" ) USING utf8 ), " - ", "<span style=\"font-weight : bold\">", CONVERT( ifnull( V1.MUC_NAMA, "" ) USING utf8 ), " - ", ifnull( V1.MIC_AKRONIM, V1.MIC_NAMA ), "</span>", ":||:", ifnull( V1.TMT, "" ), ":||:", CONVERT( ifnull( V1.SD, "" ) USING utf8 ), ":||:", ifnull( V1.IS_CALON, "" ), ":||:", ifnull( V1.MIT_NAMA, "" ) ) SEPARATOR ":|||:"
            ) AS NAMA_JABATAN,
            V1.*,
            V2.JML_AKTIF,
            V3.JML_NON_AKTIF,
            V4.PN_MENINGGAL', false); // parameter false wajib
        $this->db->from('T_PN P');
        $this->db->join('T_USER U', 'U.USERNAME = P.NIK');
        $this->db->join('V_JABATAN_CURRENT_EXC_CALON V1', 'V1.ID_PN = P.ID_PN');
        $this->db->join('V_PN_JML_JABATAN_AKTIF_EXC_CALON V2', 'V2.ID_PN = V1.ID_PN', 'left');
        $this->db->join('V_PN_JML_JABATAN_NONAKTIF_EXC_CALON V3', 'V3.ID_PN = V1.ID_PN', 'left');
        $this->db->join('
            (
                SELECT ID_PN, 1 PN_MENINGGAL
                FROM
                T_PN_JABATAN
                INNER JOIN T_STATUS_AKHIR_JABAT ON T_PN_JABATAN.ID_STATUS_AKHIR_JABAT = T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT
                WHERE IS_MENINGGAL = 1
            )
         V4', 'V4.ID_PN = V1.ID_PN', 'left');
        $this->db->where(' P.IS_ACTIVE', '1');

        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;
            $IS_KPK = $role[0]->IS_KPK;

            if ($inst == '1' || $user == '1') {
                $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
                $this->db->where("(V1.LEMBAGA like '" . $INST_SATKERKD . "')", null, false);
            }
        }

        if (@$this->CARI['USEWHEREONLY']) {
            $this->db->where('P.NIK', $this->CARI['TEXT']);
        } else {
            if (@$this->CARI['TEXT']) {
                $this->db->where("(P.NAMA LIKE '%" . $this->CARI['TEXT'] . "%' OR P.NIK LIKE '%" . $this->CARI['TEXT'] . "%')");
            }

            if (@$this->CARI['INST'] && @$this->CARI['INST'] != -99) {
                $this->db->where("V1.LEMBAGA like '" . $this->CARI['INST'] . "'", null, false);
            }
        }

        $this->db->order_by('P.NAMA', 'asc');
        $this->db->group_by('P.ID_PN'); // parameter false wajib

        $this->total_rows = $this->db->get('')->num_rows();

        $query = $this->db->get('', $this->limit, $this->offset);
        $this->items = $query->result();
        $this->end = $query->num_rows();
        // echo $this->db->last_query();
        $this->db->flush_cache();

        $havelhkpn = array();
        foreach ($this->items as $item) {
            $ID_PN[] = $item->ID_PN;
            $havelhkpn[$item->ID_PN] = false;
        }

        if (!empty($ID_PN)) {
            $ID_PN_SEL = implode($ID_PN, ',');
            $sql = "SELECT * FROM T_LHKPN WHERE ID_PN IN ($ID_PN_SEL)";
            $query = $this->db->query($sql)->result();
            foreach ($query as $lhkpn) {
                $havelhkpn[$lhkpn->ID_PN] = true;
            }
        }

        $data = array(
            'linkCetak' => 'index.php/ereg/all_pn/index/0',
            'titleCetak' => 'cetakpn',
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'CARI' => @$this->CARI,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E-reg' => 'index.php/dashboard/efilling',
                'PN' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
            'instansis' => $this->mglobal->get_data_all('M_INST_SATKER'),
            'status_akhir' => $this->mglobal->get_data_all('T_STATUS_AKHIR_JABAT', NULL, NULL, '*', NULL, ['IS_ORDER', 'asc']),
            'IS_KPK' => @$IS_KPK,
            'havelhkpn' => $havelhkpn,
        );

        // load view
        if (@$this->iscetak) {
            ng::exportDataTo($data, $cetak, strtolower(get_called_class()) . '/' . strtolower(get_called_class()) . '_' . 'index' . '_' . 'cetak', 'cetakpn');
        } else {
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
        }
    }

    public function getAdmin($id) {
        $join = [
            ['table' => 'T_USER_ROLE', 'on' => 'T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE'],
            ['table' => 'M_INST_SATKER', 'on' => 'T_USER.INST_SATKERKD=M_INST_SATKER.INST_SATKERKD']
        ];
        $data = $this->mglobal->get_data_all('T_USER', $join, ['T_USER.INST_SATKERKD' => $id, 'IS_INSTANSI' => '1', 'T_USER.IS_ACTIVE' => '1'], 'ID_USER, NAMA, INST_NAMA')[0];

        echo json_encode($data);
    }

    private function isInstansiSame($instAsal, $instTujuan, $id) {
        if ($instAsal == $instTujuan) {
            $this->saveapprove($id);
        }
    }

    //Admin KPK bisa mengaktifkan user yang non  aktif, meninggal , pensiun, non-wl
    function aktifkan($idjabatan = null, $jenis = null, $stat = null) {
        $this->makses->check_is_write();
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mpn', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);

        $rowjabatan = $this->mglobal->get_data_all(
                        'T_PN_JABATAN', [
                    ['table' => 'T_PN', 'on' => 'T_PN_JABATAN.ID_PN = T_PN.ID_PN', 'join' => 'left'],
                    ['table' => 'M_JABATAN', 'on' => 'T_PN_JABATAN.ID_JABATAN  = M_JABATAN.ID_JABATAN', 'join' => 'left'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD  = T_PN_JABATAN.LEMBAGA', 'join' => 'left'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID  = T_PN_JABATAN.UNIT_KERJA', 'join' => 'left'],
                        ]
                        , ['ID' => $idjabatan]
                        , 'T_PN_JABATAN.ID, T_PN.ID_PN, T_PN.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_UNIT_KERJA.UK_NAMA, T_PN_JABATAN.IS_CALON'
                )[0];
        // display($rowjabatan);
        $data = array(
            'form' => 'actMeninggal',
            'item' => $rowjabatan,
            'stat' => $stat
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
        return;
    }

    public function dummyDataPNWL() {
        $this->load->helper('dummy');
        display(insertPNWL());
    }

    public function dummyDataCalon() {
        $this->load->helper('dummy');
        $this->load->model('mpn', '', TRUE);
        $this->load->model('muser', '', TRUE);
        $datas = insertDataCalon();

        for ($i = 0; $i < count($datas); $i++) {
            $password = createRandomPassword(6);
            $pejabat = array(
                'NIK' => $datas[$i]['nik'],
                'NAMA' => $datas[$i]['nama'],
                'TEMPAT_LAHIR' => $datas[$i]['tempat'],
                'JNS_KEL' => $datas[$i]['jk'],
                'TGL_LAHIR' => date('Y-m-d', strtotime($datas[$i]['tanggal'])),
                'ID_AGAMA' => $datas[$i]['agama'],
                'ID_STATUS_NIKAH' => $datas[$i]['status'],
                'ID_PENDIDIKAN' => $datas[$i]['pend'],
                'NPWP' => $datas[$i]['npwp'],
                'NEGARA' => '2',
                'PROV' => $datas[$i]['lokasi']['prov'],
                'KAB_KOT' => $datas[$i]['lokasi']['kot'],
                'KEC' => $datas[$i]['lokasi']['kec'],
                'KEL' => $datas[$i]['lokasi']['kel'],
                'ALAMAT_TINGGAL' => $datas[$i]['lokasi']['kel'],
                'EMAIL' => $datas[$i]['nik'] . '@gmail.com',
                'IS_ACTIVE' => '1',
                'CREATED_TIME' => time(),
                'CREATED_BY' => 'admin',
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            );

            $insert = $this->mpn->save($pejabat);
            $id = $this->db->insert_id();

            $jabatan = array(
                'ID_PN' => $id,
                'DESKRIPSI_JABATAN' => 'Jabatan ' . $datas[$i]['nama'],
                'ESELON' => rand(0, 4),
                'LEMBAGA' => '8',
                'UNIT_KERJA' => '13',
                'ALAMAT_KANTOR' => $datas[$i]['tempat'],
                'IS_ACTIVE' => '1',
                'IS_CURRENT' => '1',
                'IS_CALON' => '1',
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $this->db->insert('T_PN_JABATAN', $jabatan);

            $data_user = array(
                'USERNAME' => $datas[$i]['nik'],
                'NAMA' => $datas[$i]['nama'],
                'EMAIL' => $datas[$i]['nik'] . '@gmail.com',
                'PASSWORD' => sha1(md5($password)),
                'IS_ACTIVE' => '1',
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                'ID_ROLE' => ID_ROLE_PN,
                'INST_SATKERKD' => ($this->is_instansi() != FALSE ? $this->is_instansi() : NULl)
            );

            if ($insert) {
                $this->muser->save($data_user);

                $this->session->set_userdata(
                        ['addPN' =>
                            ['pejabat' => $pejabat, 'user' => $data_user]
                        ]
                );
            }
        }
    }

//    YOGI SIMPLE XLS

    public function UpExcelload($upXlsFile = NULL) {
        $this->load->model('Mt_pn_upload_xls_temp', '', TRUE);
        $this->load->library('excel');
        $objReader = new PHPExcel_Reader_Excel5();
        $objReader->setReadDataOnly(true);

//        $filename = './uploads/data_pn_xls/ALL/1460023164-tpl_data_pnwl.xls';
        $filename = './uploads/data_pn_xls/' . $upXlsFile;
        $objPHPExcel = $objReader->load($filename);
//
        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        array();
        foreach ($rowIterator as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
            if ($row->getRowIndex() < 6)
                continue; //skip first row
            $rowIndex = $row->getRowIndex();
            $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => '', 'F' => '', 'G' => '', 'H' => '');

            foreach ($cellIterator as $cell) {
                if ('A' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('B' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('C' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('D' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('E' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('F' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('G' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('H' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                }
            }
        }

        $this->load->model('Minstansi', '', TRUE);
        $instansi = $this->Minstansi->get_by_id($this->instansi)->row();

        //cek data
        $cek = $this->Mt_pn_upload_xls_temp->get_ver_pnwl_inst($this->instansi, $this->session->userdata('USR'));
        if ($cek)
            $this->Mt_pn_upload_xls_temp->delete_instansi($this->instansi, $this->session->userdata('USR'));


        foreach ($array_data as $data):
            $data_xls = array(
                'NIK' => $data['B'],
                'NIP_NRP' => $data['C'],
                'NAMA' => strtoupper($data['D']),
                'NAMA_UNIT_KERJA' => strtoupper($data['E']),
                'NAMA_SUB_UNIT_KERJA' => strtoupper($data['F']),
                'ID_LEMBAGA' => $this->instansi,
                'NAMA_LEMBAGA' => strtoupper($instansi->INST_NAMA),
                'NAMA_JABATAN' => strtoupper($data['G']),
                'EMAIL' => $data['H'],
                'IS_PROCESSED' => '0',
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"]
            );

            $this->Mt_pn_upload_xls_temp->save($data_xls);
        endforeach;

// -- for view data --
//        $data = array(
//            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
//                'Dashboard' => 'index.php/welcome/dashboard',
//            )),
//            'list' => $array_data
//        );
//echo '<br/>'.$filename;
//        var_dump('<pre>');
//        var_dump($array_data);
//        $this->load->view('v_exc_load', $data);
    }

    public function DownUpExcels() {
        $array_data = array();
        $this->load->model('Mt_pn_upload_xls_temp', '', TRUE);

        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
            )),
            'list_ver_pnwl' => $this->Mt_pn_upload_xls_temp->get_ver_pnwl_jabatan($this->instansi),
            'list_ver_pnwl_tambah' => $this->Mt_pn_upload_xls_temp->get_ver_pnwl_tambahan($this->instansi),
            'list_ver_pnwl_non_aktif' => $this->Mt_pn_upload_xls_temp->get_ver_pnwl_nonact($this->instansi),
            'list_cek_temp' => $this->Mt_pn_upload_xls_temp->get_temp_uk_suk($this->instansi),
        );


        $this->load->view('v_exc_downup', $data);
    }

    public function UpWebService() {
        $array_data = array();

        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
            )),
            'list' => $array_data
        );


        $this->load->view('v_ws_load', $data);
    }

    public function createXls() {

        $this->load->model('mpn', '', TRUE);
        $list_data = $this->mpn->get_all($this->instansi);
        $list_instansi = $this->mpn->get_all_custome('M_INST_SATKER');
        $list_unit_kerja = $this->mpn->get_all_unit_kerja();
        $list_sub_unit_kerja = $this->mpn->get_all_sub_unit_kerja();
        $list_jabatan = $this->mpn->get_all_jabatan();


        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);

        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        $this->load->library('excel');
        $dir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
        $objReader = PHPExcel_IOFactory::createReader('Excel5');

        $filename = './file/excel/template/tpl_data_pnwl.xls';
        $fileresult = './file/file_result/file_result.xls';
        echo $filename . '<br/>';

        $objPHPExcel = $objReader->load($filename);
        PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
        $styleArrayheader = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 10.5,
                'name' => 'Calibri'
        ));
        $styleArray = array(
            'font' => array(
                'bold' => FALSE,
                'color' => array('rgb' => '000000'),
                'size' => 10,
                'name' => 'Calibri'
        ));


        $objPHPExcel->getActiveSheet(1)->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));

        ////BEGIN INSTANSI
        // Add new sheet
        $objWorkInstansi = $objPHPExcel->createSheet(1); //Setting index when creating
        //Write cells

        $objWorkInstansi->getColumnDimension('A')->setWidth(10);
        $objWorkInstansi->getColumnDimension('B')->setWidth(14);
        $objWorkInstansi->getColumnDimension('C')->setWidth(75);

        $objWorkInstansi->getStyle('A1')->applyFromArray($styleArrayheader);
        $objWorkInstansi->getStyle('B1')->applyFromArray($styleArrayheader);
        $objWorkInstansi->getStyle('C1')->applyFromArray($styleArrayheader);

        $objWorkInstansi->setCellValue('A1', 'ID Instansi')
                ->setCellValue('B1', 'Kode Instansi!')
                ->setCellValue('C1', 'Nama Instansi');

        $ii = 1;
        foreach ($list_instansi as $dataRow) {
            $ii++;

            $objWorkInstansi->getStyle('A' . $ii)->applyFromArray($styleArray);
            $objWorkInstansi->getStyle('B' . $ii)->applyFromArray($styleArray);
            $objWorkInstansi->getStyle('C' . $ii)->applyFromArray($styleArray);

            $objWorkInstansi->setCellValue('A' . $ii, $dataRow->INST_SATKERKD)
                    ->setCellValue('B' . $ii, $dataRow->INST_KODE)
                    ->setCellValue('C' . $ii, $dataRow->INST_NAMA);
        }


        // Rename sheet
        $objWorkInstansi->setTitle("instansi");

        //END
        //BEGIN UNIT KERJA
        $objWorkUK = $objPHPExcel->createSheet(2); //Setting index when creating

        $objWorkUK->getColumnDimension('A')->setWidth(11);
        $objWorkUK->getColumnDimension('B')->setWidth(75);
        $objWorkUK->getColumnDimension('C')->setWidth(75);

        $objWorkUK->getStyle('A1')->applyFromArray($styleArrayheader);
        $objWorkUK->getStyle('B1')->applyFromArray($styleArrayheader);
        $objWorkUK->getStyle('C1')->applyFromArray($styleArrayheader);

        $objWorkUK->setCellValue('A1', 'ID Unit Kerja')
                ->setCellValue('B1', 'Nama Instansi!')
                ->setCellValue('C1', 'Nama Unit Kerja');

        $ii = 1;
        foreach ($list_unit_kerja as $dataRow) {
            $ii++;

            $objWorkUK->getStyle('A' . $ii)->applyFromArray($styleArray);
            $objWorkUK->getStyle('B' . $ii)->applyFromArray($styleArray);
            $objWorkUK->getStyle('C' . $ii)->applyFromArray($styleArray);

            $objWorkUK->setCellValue('A' . $ii, $dataRow->UK_ID)
                    ->setCellValue('B' . $ii, $dataRow->INST_NAMA)
                    ->setCellValue('C' . $ii, $dataRow->UK_NAMA);
        }

        $objWorkUK->setTitle("unit_kerja");
        //END
        //BEGIN SUB UNIT KERJA
        $objWorkSUK = $objPHPExcel->createSheet(3); //Setting index when creating

        $objWorkSUK->getColumnDimension('A')->setWidth(15);
        $objWorkSUK->getColumnDimension('B')->setWidth(55);
        $objWorkSUK->getColumnDimension('C')->setWidth(55);

        $objWorkSUK->getStyle('A1')->applyFromArray($styleArrayheader);
        $objWorkSUK->getStyle('B1')->applyFromArray($styleArrayheader);
        $objWorkSUK->getStyle('C1')->applyFromArray($styleArrayheader);

        $objWorkSUK->setCellValue('A1', 'ID Sub Unit Kerja')
                ->setCellValue('B1', 'Nama Unit Kerja')
                ->setCellValue('C1', 'Nama Sub Unit Kerja');

        $ii = 1;
        foreach ($list_sub_unit_kerja as $dataRow) {
            $ii++;

            $objWorkSUK->getStyle('A' . $ii)->applyFromArray($styleArray);
            $objWorkSUK->getStyle('B' . $ii)->applyFromArray($styleArray);
            $objWorkSUK->getStyle('C' . $ii)->applyFromArray($styleArray);

            $objWorkSUK->setCellValue('A' . $ii, $dataRow->SUK_ID)
                    ->setCellValue('B' . $ii, $dataRow->UK_NAMA)
                    ->setCellValue('C' . $ii, $dataRow->SUK_NAMA);
        }

        $objWorkSUK->setTitle("sub_unit_kerja");
        //END
        //BEGIN JABATAN
        $objWorkJabatan = $objPHPExcel->createSheet(4); //Setting index when creating

        $objWorkJabatan->getColumnDimension('A')->setWidth(11);
        $objWorkJabatan->getColumnDimension('B')->setWidth(55);
        $objWorkJabatan->getColumnDimension('C')->setWidth(55);
        $objWorkJabatan->getColumnDimension('D')->setWidth(55);

        $objWorkJabatan->getStyle('A1')->applyFromArray($styleArrayheader);
        $objWorkJabatan->getStyle('B1')->applyFromArray($styleArrayheader);
        $objWorkJabatan->getStyle('C1')->applyFromArray($styleArrayheader);
        $objWorkJabatan->getStyle('D1')->applyFromArray($styleArrayheader);

        $objWorkJabatan->setCellValue('A1', 'ID Jabatan')
                ->setCellValue('B1', 'Nama Unit Kerja')
                ->setCellValue('C1', 'Nama Sub Unit Kerja')
                ->setCellValue('D1', 'Nama Jabatan');

        $ii = 1;
        foreach ($list_jabatan as $dataRow) {
            $ii++;

            $objWorkJabatan->getStyle('A' . $ii)->applyFromArray($styleArray);
            $objWorkJabatan->getStyle('B' . $ii)->applyFromArray($styleArray);
            $objWorkJabatan->getStyle('C' . $ii)->applyFromArray($styleArray);
            $objWorkJabatan->getStyle('D' . $ii)->applyFromArray($styleArray);

            $objWorkJabatan->setCellValue('A' . $ii, $dataRow->SUK_ID)
                    ->setCellValue('B' . $ii, $dataRow->UK_NAMA)
                    ->setCellValue('C' . $ii, $dataRow->SUK_NAMA)
                    ->setCellValue('D' . $ii, $dataRow->NAMA_JABATAN);
        }

        $objWorkJabatan->setTitle("jabatan");
        //END
        // Sheet 1

        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $sheet->getStyle('A5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('B5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('C5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('D5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('E5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('F5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('G5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('H5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('I5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('J5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('K5')->applyFromArray($styleArrayheader);


        $baseRow = 7;
        $i = -1;
        foreach ($list_data as $dataRow) {
            $i++;
            $row = $baseRow + $i;

            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
            $jk = $dataRow->JNS_KEL == '1' ? 'Laki laki' : 'Perempuan';
            $objPHPExcel->getActiveSheet()
                    ->setCellValue('A' . $row, $i + 1)
                    ->setCellValue('B' . $row, $dataRow->NIK)
                    ->setCellValue('C' . $row, $dataRow->NIP_NRP)
                    ->setCellValue('D' . $row, $dataRow->NAMA)
                    ->setCellValue('E' . $row, $dataRow->UK_NAMA)
                    ->setCellValue('F' . $row, $dataRow->SUK_NAMA)
                    ->setCellValue('G' . $row, $dataRow->NAMA_JABATAN)
                    ->setCellValue('H' . $row, $jk)
                    ->setCellValue('I' . $row, $dataRow->TGL_LAHIR)
                    ->setCellValue('J' . $row, $dataRow->NO_HP)
                    ->setCellValue('K' . $row, $dataRow->EMAIL);
        }
        $objPHPExcel->getActiveSheet()->removeRow($baseRow - 1, 1);

        echo date('H:i:s'), " Write to Excel5 format", EOL;
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(str_replace('.php', '.xls', $fileresult));
        echo date('H:i:s'), " File written to ", str_replace('.php', '.xls', pathinfo(__FILE__, PATHINFO_BASENAME)), EOL;



// Echo memory peak usage
        echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;

// Echo done
        echo date('H:i:s'), " Done writing file", EOL;
        echo 'File has been created in ', getcwd(), EOL;



//
    }

    function saveUploadExl() {
        $this->load->model('Minstansi', '', TRUE);


        $id = $this->input->post('test', TRUE);
        $url = '';
        $maxsize = 50000000;
        $user = $this->session->userdata('USR');
        $fileexcel = @$_FILES['file_xls'];
        $extension = strtolower(@substr(@$fileexcel['name'], -4));
        $type_file = array('.xls', '.xlsx');
        if (!$this->instansi) {
            $nfolder = 'ALL';
            $n_instansi = 'ALL';
        } else {
            $instansi = $this->Minstansi->get_by_id($this->instansi)->row();
            $n_instansi = $instansi->INST_NAMA;
            $nfolder = $instansi->INST_KODE;
        }

        $dir = "./uploads/data_pn_xls/$nfolder/";
        $filename = "uploads/data_pn_xls/$nfolder/readme.txt";
        if (!file_exists($filename)) {

            $file_to_write = 'readme.txt';
            $content_to_write = "file ini di upload oleh instansi $n_instansi";

            if (is_dir($dir) === false) {
                mkdir($dir);
            }

            $file = fopen($dir . '/' . $file_to_write, "w");

            fwrite($file, $content_to_write);

            // closes the file
            fclose($file);
            $fileexcel = (isset($_FILES['file_xls'])) ? $_FILES['file_xls'] : '';
        }
        $namefilexls = 'tpl_data_pnwl.xls';

        if ($fileexcel['error'] == 0) {
            $extension = strtolower(@substr(@$fileexcel['name'], -4));
            if (in_array($extension, $type_file) && $fileexcel['size'] != '' && $fileexcel['size'] <= $maxsize) {
                $c = save_file($fileexcel['tmp_name'], $namefilexls, $fileexcel['size'], "./uploads/data_pn_xls/$nfolder", 0, 10000);
            }
        }

        //BACA FILE DI DIRECTORI
        // Open a directory, and read its contents
        $lfile = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
//                    echo "filename:" . $file . "<br>";
                    if ($file != 'readme.txt')
                        $lfile[] = $file;
                }
                closedir($dh);
            }
        }
        $upXlsFile = $nfolder . '/' . end($lfile);
//        echo $upXlsFile;
        $save = $this->UpExcelload($upXlsFile);

//        var_dump('<pre>');
//        var_dump($lfile);
//
//        echo end($lfile);
        redirect('#index.php/ereg/All_ver_pn/daftar_xl');
    }

    function downloadFileExcel() {

        $this->load->model('Minstansi', '', TRUE);
        $instansi = $this->Minstansi->get_by_id($this->instansi)->row();
        $n_instansi = $instansi->INST_NAMA;
        $kode = $instansi->INST_KODE;



        $this->load->model('mpn', '', TRUE);
        $un_ker = $this->mpn->get_uk($this->session->userdata('ID_USER'));
        $uk_nama = ($un_ker != NULL) ? $un_ker->UK_NAMA : NULL;
        $uk_id = ($un_ker != NULL) ? $un_ker->UK_ID : NULL;

        $list_data = $this->mpn->get_all($this->instansi, $uk_nama);
        $list_instansi = $this->mpn->get_all_instansi($this->instansi);
        $list_unit_kerja = $this->mpn->get_all_unit_kerja($this->instansi, $uk_id);
        $list_sub_unit_kerja = $this->mpn->get_all_sub_unit_kerja($this->instansi, $uk_id);
        $list_jabatan = $this->mpn->get_all_jabatan($this->instansi, $uk_id);
        //echo $un_ker.'<br/>';
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);

        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        $this->load->library('excel');
        $objReader = PHPExcel_IOFactory::createReader('Excel5');

        $year = date('Y');
        $dir = "./file/file_result/$year";

        if (is_dir($dir) === false) {
            mkdir($dir);
        }
        $filename = './file/excel/template/template_pnwl.xls';
        $fileresult = $dir . '/' . $kode . '.xls';

//        echo $filename . '<br/>';

        $objPHPExcel = $objReader->load($filename);
        PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
        $styleArrayheader = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 10.5,
                'name' => 'Calibri'
        ));
        $styleArray = array(
            'font' => array(
                'bold' => FALSE,
                'color' => array('rgb' => '000000'),
                'size' => 10,
                'name' => 'Calibri'
        ));


        $objPHPExcel->getActiveSheet(1)->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));

        ////BEGIN INSTANSI
        // Add new sheet
        $objWorkInstansi = $objPHPExcel->createSheet(1); //Setting index when creating
        //Write cells

        $objWorkInstansi->getColumnDimension('A')->setWidth(10);
        $objWorkInstansi->getColumnDimension('B')->setWidth(14);
        $objWorkInstansi->getColumnDimension('C')->setWidth(75);

        $objWorkInstansi->getStyle('A1')->applyFromArray($styleArrayheader);
        $objWorkInstansi->getStyle('B1')->applyFromArray($styleArrayheader);
        $objWorkInstansi->getStyle('C1')->applyFromArray($styleArrayheader);

        $objWorkInstansi->setCellValue('A1', 'ID Instansi')
                ->setCellValue('B1', 'Kode Instansi')
                ->setCellValue('C1', 'Nama Instansi');

        $ii = 1;
        foreach ($list_instansi as $dataRow) {
            $ii++;

            $objWorkInstansi->getStyle('A' . $ii)->applyFromArray($styleArray);
            $objWorkInstansi->getStyle('B' . $ii)->applyFromArray($styleArray);
            $objWorkInstansi->getStyle('C' . $ii)->applyFromArray($styleArray);

            $objWorkInstansi->setCellValue('A' . $ii, $dataRow->INST_SATKERKD)
                    ->setCellValue('B' . $ii, $dataRow->INST_KODE)
                    ->setCellValue('C' . $ii, $dataRow->INST_NAMA);
        }


        // Rename sheet
        $objWorkInstansi->setTitle("instansi");

        //END
        //BEGIN UNIT KERJA
        $objWorkUK = $objPHPExcel->createSheet(2); //Setting index when creating

        $objWorkUK->getColumnDimension('A')->setWidth(11);
        $objWorkUK->getColumnDimension('B')->setWidth(75);
        $objWorkUK->getColumnDimension('C')->setWidth(75);

        $objWorkUK->getStyle('A1')->applyFromArray($styleArrayheader);
        $objWorkUK->getStyle('B1')->applyFromArray($styleArrayheader);
        $objWorkUK->getStyle('C1')->applyFromArray($styleArrayheader);

        $objWorkUK->setCellValue('A1', 'ID Unit Kerja')
                ->setCellValue('B1', 'Nama Instansi')
                ->setCellValue('C1', 'Nama Unit Kerja');

        $ii = 1;
        foreach ($list_unit_kerja as $dataRow) {
            $ii++;

            $objWorkUK->getStyle('A' . $ii)->applyFromArray($styleArray);
            $objWorkUK->getStyle('B' . $ii)->applyFromArray($styleArray);
            $objWorkUK->getStyle('C' . $ii)->applyFromArray($styleArray);

            $objWorkUK->setCellValue('A' . $ii, $dataRow->UK_ID)
                    ->setCellValue('B' . $ii, $dataRow->INST_NAMA)
                    ->setCellValue('C' . $ii, $dataRow->UK_NAMA);
        }

        $objWorkUK->setTitle("unit_kerja");
        //END
        //BEGIN SUB UNIT KERJA
        $objWorkSUK = $objPHPExcel->createSheet(3); //Setting index when creating

        $objWorkSUK->getColumnDimension('A')->setWidth(15);
        $objWorkSUK->getColumnDimension('B')->setWidth(55);
        $objWorkSUK->getColumnDimension('C')->setWidth(55);

        $objWorkSUK->getStyle('A1')->applyFromArray($styleArrayheader);
        $objWorkSUK->getStyle('B1')->applyFromArray($styleArrayheader);
        $objWorkSUK->getStyle('C1')->applyFromArray($styleArrayheader);

        $objWorkSUK->setCellValue('A1', 'ID Sub Unit Kerja')
                ->setCellValue('B1', 'Nama Unit Kerja')
                ->setCellValue('C1', 'Nama Sub Unit Kerja');

        $ii = 1;
        foreach ($list_sub_unit_kerja as $dataRow) {
            $ii++;

            $objWorkSUK->getStyle('A' . $ii)->applyFromArray($styleArray);
            $objWorkSUK->getStyle('B' . $ii)->applyFromArray($styleArray);
            $objWorkSUK->getStyle('C' . $ii)->applyFromArray($styleArray);

            $objWorkSUK->setCellValue('A' . $ii, $dataRow->SUK_ID)
                    ->setCellValue('B' . $ii, $dataRow->UK_NAMA)
                    ->setCellValue('C' . $ii, $dataRow->SUK_NAMA);
        }

        $objWorkSUK->setTitle("sub_unit_kerja");
        //END
        //BEGIN JABATAN
        $objWorkJabatan = $objPHPExcel->createSheet(4); //Setting index when creating

        $objWorkJabatan->getColumnDimension('A')->setWidth(11);
        $objWorkJabatan->getColumnDimension('B')->setWidth(55);
        $objWorkJabatan->getColumnDimension('C')->setWidth(55);
        $objWorkJabatan->getColumnDimension('D')->setWidth(55);

        $objWorkJabatan->getStyle('A1')->applyFromArray($styleArrayheader);
        $objWorkJabatan->getStyle('B1')->applyFromArray($styleArrayheader);
        $objWorkJabatan->getStyle('C1')->applyFromArray($styleArrayheader);
        $objWorkJabatan->getStyle('D1')->applyFromArray($styleArrayheader);

        $objWorkJabatan->setCellValue('A1', 'ID Jabatan')
                ->setCellValue('B1', 'Nama Unit Kerja')
                ->setCellValue('C1', 'Nama Sub Unit Kerja')
                ->setCellValue('D1', 'Nama Jabatan');

        $ii = 1;
        foreach ($list_jabatan as $dataRow) {
            $ii++;

            $objWorkJabatan->getStyle('A' . $ii)->applyFromArray($styleArray);
            $objWorkJabatan->getStyle('B' . $ii)->applyFromArray($styleArray);
            $objWorkJabatan->getStyle('C' . $ii)->applyFromArray($styleArray);
            $objWorkJabatan->getStyle('D' . $ii)->applyFromArray($styleArray);

            $objWorkJabatan->setCellValue('A' . $ii, $dataRow->ID_JABATAN)
                    ->setCellValue('B' . $ii, $dataRow->UK_NAMA)
                    ->setCellValue('C' . $ii, $dataRow->SUK_NAMA)
                    ->setCellValue('D' . $ii, $dataRow->NAMA_JABATAN);
        }

        $objWorkJabatan->setTitle("jabatan");
        //END
        // Sheet 1

        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $sheet->getStyle('A5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('B5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('C5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('D5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('E5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('F5')->applyFromArray($styleArrayheader);
        $sheet->getStyle('G5')->applyFromArray($styleArrayheader);

//        $objPHPExcel->getActiveSheet()->setCellValue('I6', $this->instansi);
//        $objPHPExcel->getActiveSheet()->setCellValue('J6', $kode);
        $objPHPExcel->getActiveSheet()->setCellValue('B3', $n_instansi);

        $baseRow = 7;
        $i = -1;
        foreach ($list_data as $dataRow) {
            $i++;
            $row = $baseRow + $i;

            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
            $objPHPExcel->getActiveSheet()
                    ->setCellValue('A' . $row, $i + 1)
                    ->setCellValue('B' . $row, $dataRow->NIK)
                    ->setCellValue('C' . $row, $dataRow->NIP_NRP)
                    ->setCellValue('D' . $row, $dataRow->NAMA)
                    ->setCellValue('E' . $row, $dataRow->UK_NAMA)
                    ->setCellValue('F' . $row, $dataRow->SUK_NAMA)
                    ->setCellValue('G' . $row, $dataRow->NAMA_JABATAN)
                    ->setCellValue('H' . $row, $dataRow->EMAIL);
        }
        $objPHPExcel->getActiveSheet()->removeRow($baseRow - 1, 1);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(str_replace('.php', '.xls', $fileresult));






// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $kode . '.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function daftar_pn_individu($cetak = false) {
        $this->makses->check_is_first();
        $this->load->model('mglobal', '', TRUE);
        $user_uk = $this->mglobal->get_data_by_id('t_user', 'ID_USER', $this->ID_USER);

        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E-reg' => 'index.php/dashboard/efilling',
                'PN' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'iscln' => 0,
        );

        if ($this->input->post('CARI_TEXT') != NULL) {
            $cari = $this->input->post('CARI_TEXT');
        } else {
            $cari = NULL;
        }


        $data['cari'] = $cari;
        $data['view_form'] = 'daftar';
        $data['daftar_pn'] = $this->mpn->get_daftar_pn_individual($this->instansi, $cari);


        // display($this->session->userdata('ID_ROLE'));
        $this->load->view('all_pn/daftar_individual', $data);
    }

    function daftar_pn_individu_ver($offset = 0, $cetak = false) {
        $this->makses->check_is_first();
        $this->load->model('mglobal', '', TRUE);


        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E-reg' => 'index.php/dashboard/efilling',
                'PN' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
        );

        if ($this->input->post('CARI') != NULL) {
            $cari = $this->input->post('CARI');
        } else {
            $cari = NULL;
        }

        $data['cari'] = $cari;
        $data['view_form'] = 'verifikasi';
        $data['daftar_pn'] = $this->mpn->get_daftar_pn_individual($this->instansi, $cari);
        $data['daftar_pn_perubahan'] = $this->mpn->get_daftar_pn_individual_PerubahanJabatan($this->instansi, $cari);
        $data['daftar_pn_nonact'] = $this->mpn->get_daftar_pn_individual_nonact($this->instansi, $cari);
//                 display($this->db->last_query());
        // $this->load->view('all_pn/daftar_individual', $data);
        $this->load->view('all_pn/v_ver_daftar_individual', $data);
    }

    public function addpn_daftar($iscln = NULL, $status = NULL) {
        $this->load->model('mpn', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
//        $this->load->model('mjabatan', '', TRUE);
//        $this->load->model('munitkerja', '', TRUE);
        $data = array(
            'iscln' => $iscln,
            'status' => $status,
            'form' => 'add',
            'agama' => $this->mglobal->get_data_all('M_AGAMA', null, ['IS_ACTIVE' => 1]),
            'sttnikah' => $this->mglobal->get_data_all('M_STATUS_NIKAH'),
            'penhir' => $this->mglobal->get_data_all('M_PENDIDIKAN', null, ['IS_ACTIVE' => 1]),
            'uk_id' => $this->session->userdata('UK_ID'),
            'is_uk' => $this->is_unit_kerja(),
            'isInstansi' => $this->is_instansi()
        );

        $this->load->view(strtolower(__CLASS__) . '/' . 'v_all_pn_form', $data);
    }

    public function editpn_daftar($iscln = NULL, $status = NULL, $id = NULL) {
        $this->load->model('mpn', '', TRUE);
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
        $data = array(
            'iscln' => $iscln,
            'status' => $status,
            'form' => 'add',
            'agama' => $this->mglobal->get_data_all('M_AGAMA', null, ['IS_ACTIVE' => 1]),
            'sttnikah' => $this->mglobal->get_data_all('M_STATUS_NIKAH'),
            'penhir' => $this->mglobal->get_data_all('M_PENDIDIKAN', null, ['IS_ACTIVE' => 1]),
            'isInstansi' => $this->is_instansi()
        );
        $data['daftar_pn'] = $this->mglobal->daftar_pn_individual_by_id($id);
        $this->load->view(strtolower(__CLASS__) . '/' . 'form_edit_daftar_pn', $data);
    }

    function save_edit_daftar($id) {
        $this->load->model('mglobal', '', TRUE);

        $NIK = $this->input->post('NIK');
        $NAMA = $this->input->post('NAMA');
        $NO_HP = $this->input->post('NO_HP');
        $EMAIL = $this->input->post('EMAIL');
        $JABATAN = $this->input->post('JABATAN');
        $SUB_UNIT_KERJA = $this->input->post('SUB_UNIT_KERJA');
        $UNIT_KERJA = $this->input->post('UNIT_KERJA');
        $NIP = $this->input->post('NIP');
        $JNS_KEL = $this->input->post('JNS_KEL');
        $TGL_LAHIR = $this->input->post('TGL_LAHIR');
        $TEMPAT_LAHIR = $this->input->post('TEMPAT_LAHIR');
        $lembaga = $this->input->post('LEMBAGA');

        $arr_pn = array(
            'NIK' => $NIK,
            'NIP_NRP' => $NIP,
            'NAMA' => $NAMA,
            'TEMPAT_LAHIR' => $TEMPAT_LAHIR,
            'JNS_KEL' => $JNS_KEL,
            'TGL_LAHIR' => date('Y-m-d', strtotime(str_replace('/', '-', $TGL_LAHIR))),
            'EMAIL' => $EMAIL,
            'NO_HP' => $NO_HP,
            'UPDATED_TIME' => time(),
            'UPDATED_BY' => 'admin', //$this->session->userdata('USR'),
            'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
        );

        $simpan = $this->mglobal->update_data_('T_PN', $arr_pn, 'ID_PN', $id);


        // Lembaga
        $id_lembaga = $this->input->post('LEMBAGA');
        $data1 = $this->mglobal->get_by_id('m_inst_satker', 'INST_SATKERKD', $id_lembaga);
        $f_lembaga = $data1->INST_NAMA;

        // Unit Kerja
        $id_unit_kerja = $this->input->post('UNIT_KERJA');
        $data = $this->mglobal->get_by_id('m_unit_kerja', 'UK_ID', $id_unit_kerja);
        $f_unit_kerja = $data->UK_NAMA;

        // Sub Unit Kerja
        $id_sub_unit_kerja = $this->input->post('SUB_UNIT_KERJA');
        if ($id_sub_unit_kerja != NULL) {
            $data = $this->mglobal->get_by_id('m_sub_unit_kerja', 'SUK_ID', $id_sub_unit_kerja);
            $f_sub_unit_kerja = $data->SUK_NAMA;
        } else {
            $f_sub_unit_kerja = NULL;
        }

        // Jabatan dan Eselon
        $id_jabatan = $this->input->post('JABATAN');

        $data = $this->mglobal->get_by_id('m_jabatan', 'ID_JABATAN', $id_jabatan);
        $f_jabatan = $data->NAMA_JABATAN;
        $f_eselon = $data->KODE_ESELON;

        $jabatan = array(
            'ID_JABATAN' => $id_jabatan,
            'LEMBAGA' => $id_lembaga,
            'NAMA_LEMBAGA' => $f_lembaga,
            'UNIT_KERJA' => $f_unit_kerja,
            'SUB_UNIT_KERJA' => $f_sub_unit_kerja,
            'DESKRIPSI_JABATAN' => $f_jabatan,
            'ESELON' => $f_eselon,
            'UPDATED_TIME' => time(),
            'UPDATED_BY' => $this->session->userdata('USR'),
            'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
        );
        $this->mglobal->update_data_('T_PN_JABATAN', $jabatan, 'ID_PN', $id);

        if ($simpan) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function delete_daftar_pn_individual($id_pn) {
        $data = array(
            'form' => 'delete_daftar_pn_individual',
            'id' => $id_pn
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    public function delete_vi($id_pn) {
        $data = array(
            'form' => 'delete_verifikasi_data_individu',
            'id' => $id_pn
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    function do_delete_daftar_pn_individual($id_pn) {
        $this->load->model('mglobal', '', TRUE);
        $data_pn = $this->mglobal->get_by_id('T_PN', 'ID_PN', $id_pn);
//        $data = array('IS_ACTIVE' => 0);
//        $this->mglobal->update_data_('T_PN_JABATAN', $data, 'ID_PN', $id);
        $this->db->trans_begin();
        $this->mglobal->delete('T_PN_JABATAN', ['ID_PN' => $id_pn]);
        $hapus = $this->mglobal->delete('T_PN', ['ID_PN' => $id_pn]);
        $this->mglobal->delete('t_user', ['USERNAME' => $data_pn->NIK]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        if ($hapus) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function approve_daftar_pnwl($id) {
        $status = array('IS_ACTIVE' => 1);
        $this->mglobal->update_data_('T_PN_JABATAN', $status, 'ID_PN', $id);
        $this->mglobal->update_data_('T_PN', $status, 'ID_PN', $id);

        if ($status) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function save_perubahan_jabatan($id) {
        $this->load->model('mglobal', '', TRUE);
//        $this->mglobal->update_perubahan_jabatan($id);
        // Lembaga
        // $id_lembaga = $this->input->post('LEMBAGA');
        // Unit Kerja
        $id_unit_kerja = $this->input->post('UNIT_KERJA');
        $data = $this->mglobal->get_by_id('m_unit_kerja', 'UK_ID', $id_unit_kerja);
        $f_unit_kerja = $data->UK_NAMA;
        $id_lembaga = $data->UK_LEMBAGA_ID;

        $data1 = $this->mglobal->get_by_id('m_inst_satker', 'INST_SATKERKD', $id_lembaga);
        $f_lembaga = $data1->INST_NAMA;

        // Sub Unit Kerja
        $id_sub_unit_kerja = $this->input->post('SUB_UNIT_KERJA');
        if ($id_sub_unit_kerja != NULL) {
            $data = $this->mglobal->get_by_id('m_sub_unit_kerja', 'SUK_ID', $id_sub_unit_kerja);
            $f_sub_unit_kerja = $data->SUK_NAMA;
        } else {
            $f_sub_unit_kerja = NULL;
        }

        // Jabatan dan Eselon
        $id_jabatan = $this->input->post('JABATAN');

        $data = $this->mglobal->get_by_id('m_jabatan', 'ID_JABATAN', $id_jabatan);
        $f_jabatan = $data->NAMA_JABATAN;
        $f_eselon = $data->KODE_ESELON;

        $iscln = $this->input->post('iscln');
        if ($iscln == 1) {
            $id_iscln = 1;
        } else {
            $id_iscln = 0;
        }


        $arr_pn = array(
            'IS_ACTIVE' => 1
        );
        $simpan = $this->mglobal->update_data_('T_PN', $arr_pn, 'ID_PN', $id);

        $jabatan = array(
            'ID_JABATAN' => $id_jabatan,
            'ID_PN' => $id,
            'LEMBAGA' => $id_lembaga,
            'NAMA_LEMBAGA' => $f_lembaga,
            'UNIT_KERJA' => $f_unit_kerja,
            'SUB_UNIT_KERJA' => $f_sub_unit_kerja,
            'DESKRIPSI_JABATAN' => $f_jabatan,
            'ESELON' => $f_eselon,
            'IS_ACTIVE' => '1',
            'IS_DELETED' => '0',
            'IS_CURRENT' => '1',
            'ID_STATUS_AKHIR_JABAT' => '11',
            'IS_CALON' => $id_iscln,
            'CREATED_TIME' => time(),
            'CREATED_BY' => $this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
        );
        $save = $this->db->insert('T_PN_JABATAN', $jabatan);

        if ($save) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function save_multy_jabatan($id) {
        $this->load->model('mglobal', '', TRUE);
        $this->db->trans_begin();
//        $this->mglobal->update_perubahan_jabatan($id);
        // Lembaga
        // $id_lembaga = $this->input->post('LEMBAGA');
        // Unit Kerja
        $id_unit_kerja = $this->input->post('UNIT_KERJA');
        $data = $this->mglobal->get_by_id('m_unit_kerja', 'UK_ID', $id_unit_kerja);
        $f_unit_kerja = $data->UK_NAMA;
        $id_lembaga = $data->UK_LEMBAGA_ID;

        $data1 = $this->mglobal->get_by_id('m_inst_satker', 'INST_SATKERKD', $id_lembaga);
        $f_lembaga = $data1->INST_NAMA;

        // Sub Unit Kerja
        $id_sub_unit_kerja = $this->input->post('SUB_UNIT_KERJA');
        if ($id_sub_unit_kerja != NULL) {
            $data = $this->mglobal->get_by_id('m_sub_unit_kerja', 'SUK_ID', $id_sub_unit_kerja);
            $f_sub_unit_kerja = $data->SUK_NAMA;
        } else {
            $f_sub_unit_kerja = NULL;
        }

        // Jabatan dan Eselon
        $id_jabatan = $this->input->post('JABATAN');

        $data = $this->mglobal->get_by_id('m_jabatan', 'ID_JABATAN', $id_jabatan);
        $f_jabatan = $data->NAMA_JABATAN;
        $f_eselon = $data->KODE_ESELON;

        $iscln = $this->input->post('iscln');
        if ($iscln == 1) {
            $id_iscln = 1;
        } else {
            $id_iscln = 0;
        }


//        $arr_pn = array(
//            'IS_ACTIVE' => 0
//        );
//        $simpan = $this->mglobal->update_data_('T_PN', $arr_pn, 'ID_PN', $id);

        $jabatan = array(
            'ID_JABATAN' => $id_jabatan,
            'ID_PN' => $id,
            'LEMBAGA' => $id_lembaga,
            'NAMA_LEMBAGA' => $f_lembaga,
            'UNIT_KERJA' => $f_unit_kerja,
            'SUB_UNIT_KERJA' => $f_sub_unit_kerja,
            'DESKRIPSI_JABATAN' => $f_jabatan,
            'ESELON' => $f_eselon,
            'IS_ACTIVE' => '1',
            'IS_DELETED' => '0',
            'IS_CURRENT' => '1',
            'ID_STATUS_AKHIR_JABAT' => '1',
            'IS_CALON' => $id_iscln,
            'CREATED_TIME' => time(),
            'CREATED_BY' => $this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
        );
        $save = $this->db->insert('T_PN_JABATAN', $jabatan);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        if ($save) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function save_perubahan_calon($id) {
        $this->load->model('mglobal', '', TRUE);
        $this->mglobal->update_perubahan_calon($id);

        // Unit Kerja
        $id_unit_kerja = $this->input->post('UNIT_KERJA2');
        $data = $this->mglobal->get_by_id('m_unit_kerja', 'UK_ID', $id_unit_kerja);
        $f_unit_kerja = $data->UK_NAMA;
        $id_lembaga = $data->UK_LEMBAGA_ID;

        $data1 = $this->mglobal->get_by_id('m_inst_satker', 'INST_SATKERKD', $id_lembaga);
        $f_lembaga = $data1->INST_NAMA;

        // Sub Unit Kerja
        $id_sub_unit_kerja = $this->input->post('SUB_UNIT_KERJA3');
        if ($id_sub_unit_kerja != NULL) {
            $data = $this->mglobal->get_by_id('m_sub_unit_kerja', 'SUK_ID', $id_sub_unit_kerja);
            $f_sub_unit_kerja = $data->SUK_NAMA;
        } else {
            $f_sub_unit_kerja = NULL;
        }

        // Jabatan dan Eselon
        $id_jabatan = $this->input->post('JABATAN3');

        $data = $this->mglobal->get_by_id('m_jabatan', 'ID_JABATAN', $id_jabatan);
        $f_jabatan = $data->NAMA_JABATAN;
        $f_eselon = $data->KODE_ESELON;


        $jabatan = array(
            'ID_JABATAN' => $id_jabatan,
            'ID_PN' => $id,
            'LEMBAGA' => $id_lembaga,
            'NAMA_LEMBAGA' => $f_lembaga,
            'UNIT_KERJA' => $f_unit_kerja,
            'SUB_UNIT_KERJA' => $f_sub_unit_kerja,
            'DESKRIPSI_JABATAN' => $f_jabatan,
            'ESELON' => $f_eselon,
            'IS_ACTIVE' => '1',
            'IS_CURRENT' => '1',
            'IS_CALON' => '1',
            'CREATED_TIME' => time(),
            'CREATED_BY' => $this->session->userdata('USR'),
            'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
        );
        $save = $this->db->insert('T_PN_JABATAN', $jabatan);

        if ($save) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function cek_nik($id = NULL) {
        $this->load->model('mglobal', '', TRUE);

        if ($id != NULL) {
            $data = $this->mglobal->cek_nik($id);
            $target = array(10, 11, 15); //STATUS MASIH DALAM PROSES VERIFIKASI
            // JIKA DATA TIDAK ADA, MAKA BENAR
            if ($data == false) {
                echo 1;
            } else {
                $data_jb = $this->mglobal->get_data_by_id('T_PN_JABATAN', 'ID_PN', $data->ID_PN);
                $arr_jb = array();
                foreach ($data_jb as $rows):
                    $arr_jb[] = $rows->ID_STATUS_AKHIR_JABAT;
                endforeach;

                // JIKA PN SUDAH ADA
                if (count(array_intersect($arr_jb, $target)) > 0) // PN DALAM PROSES VERIFIKASI
                    echo 2;
                else
                    echo 0;
            }
        } else {
            // JIKA DATA TIDAK ADA, MAKA BENAR
            echo 1;
        }
    }

    function cek_nomor_hp($no_hp, $id = NULL) {
        $check = null;
        if ($id) {
            $this->db->where('HANDPHONE', $no_hp);
            $this->db->where('ID_USER !=', $id);
            $check = $this->db->get('t_user')->result();
        } else {
            $this->db->where('HANDPHONE', $no_hp);
            $this->db->limit(1);
            $check = $this->db->get('t_user')->row();
        }


        if ($id) {

            if ($check) {
                echo "1"; // jika edit dan ada datanya
            } else {
                echo "2";
            }
        } else {

            if ($check) {
                echo "3"; // jika tambah dan ada datanya
            } else {
                echo "2";
            }
        }
    }

    // Verifikasi Individu


    function app_vi_add($id, $idj) {
        $this->db->trans_begin();
        $data_pn = $this->mglobal->get_data_by_id('t_pn', 'ID_PN', $id);
        $user_uk = $this->mglobal->get_data_by_id('t_user', 'USERNAME', $data_pn[0]->NIK);
        $password = $this->muser->createRandomPassword(6);

        $dt_pn = array(
            'IS_ACTIVE' => 1
        );

        $dt_pn_jab = array(
            'IS_CURRENT' => 1,
            'ID_STATUS_AKHIR_JABAT' => 0,
            'IS_ACTIVE' => 1
        );

        $data_update = array(
            'password' => sha1(md5($password)),
            'IS_FIRST' => '1'
        );
        $update = $this->muser->update($user_uk[0]->ID_USER, $data_update);
//        $this->mglobal->update('T_USER', $data_update, null, "ID_USER = '$user_uk[0]->ID_USER'");
        $this->muser->kirim_info_akun($user_uk[0]->EMAIL, $user_uk[0]->USERNAME, $password);

        $this->mglobal->update_data_('t_pn', $dt_pn, 'ID_PN', $id);
        $this->mglobal->update_data_('t_pn_jabatan', $dt_pn_jab, 'ID', $idj);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;
    }

    function app_vi_up($id, $idj) {
        $data_pn = $this->mglobal->get_data_by_id('t_pn', 'ID_PN', $id);
        $data_pn_jab = $this->mglobal->get_by_id('t_pn_jabatan', 'ID', $idj);
        $user_uk = $this->mglobal->get_data_by_id('t_user', 'USERNAME', $data_pn[0]->NIK);
        $this->db->trans_begin();
        $dt_pn = array(
            'IS_ACTIVE' => 1
        );
        $dt_pn_ = array(
            'IS_CURRENT' => 0
        );
        $dt_pn_jab = array(
            'IS_CURRENT' => 1,
            'ID_STATUS_AKHIR_JABAT' => 0,
            'IS_ACTIVE' => 1
        );

        $this->db->where('LEMBAGA', $data_pn_jab->LEMBAGA);
        $this->db->where('ID_PN', $id);
        $this->db->update('t_pn_jabatan', $dt_pn_);

//        $this->mglobal->update_data_('t_pn', $dt_pn, 'ID_PN', $id);
        $this->mglobal->update_data_('t_pn_jabatan', $dt_pn_jab, 'ID', $idj);

        $this->muser->kirim_info_akun_aktif($user_uk[0]->EMAIL, $user_uk[0]->USERNAME, $password);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;
    }

    function app_vi_nonact($id, $idj) {
        $data_pn_jab = $this->mglobal->get_by_id('t_pn_jabatan', 'ID', $idj);
        $this->db->trans_begin();
        $dt_pn = array(
            'IS_ACTIVE' => 1
        );
        $dt_pn_ = array(
            'IS_CURRENT' => 0
        );
        $dt_pn_jab = array(
            'IS_CURRENT' => 1,
            'ID_STATUS_AKHIR_JABAT' => 5
        );

        $this->db->where('LEMBAGA', $data_pn_jab->LEMBAGA);
        $this->db->where('ID_PN', $id);
        $this->db->update('t_pn_jabatan', $dt_pn_);

        $this->mglobal->update_data_('t_pn_jabatan', $dt_pn_jab, 'ID', $idj);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;
    }

    function delete_vi2($id, $idj) {
        $this->db->trans_begin();
        $dt_pn = array(
            'IS_ACTIVE' => 1
        );
        $dt_pn_jab = array(
            'IS_DELETED' => 1
        );

        $this->mglobal->update_data_('t_pn', $dt_pn, 'ID_PN', $id);
        $this->mglobal->update_data_('t_pn_jabatan', $dt_pn_jab, 'ID', $idj);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;
    }

    function Cancel_VerNon($id, $idj) {
        $this->db->trans_begin();
        $dt_pn = array(
            'IS_ACTIVE' => 1
        );
        $dt_pn_jab = array(
            'IS_DELETED' => 0,
            'ID_STATUS_AKHIR_JABAT' => 0
        );
//        $this->mglobal->update_data_('t_pn', $dt_pn, 'ID_PN', $id);
        $this->mglobal->update_data_('t_pn_jabatan', $dt_pn_jab, 'ID', $idj);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $res = intval($this->db->trans_status());
        return $res;
    }

}
