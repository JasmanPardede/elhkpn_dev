<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller LHKPN
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Share/Controllers/Reff
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reff extends CI_Controller {

    public $limit = 10;

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
        $this->username = $this->session->userdata('USERNAME');
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

    public function getSubUnitKerja2($id_uk) {

        $q = $_GET['q'];
        $where = ['UK_ID' => $id_uk];
        $result = $this->mglobal->get_data_all('M_SUB_UNIT_KERJA', null, $where, 'SUK_ID, SUK_NAMA', "SUK_NAMA LIKE '%$q%'", null, null, '15');
        $res = [];
        foreach ($result as $row) {
            $res[] = ['id' => $row->SUK_ID, 'name' => strtoupper($row->SUK_NAMA)];
        }

        $data = ['item' => $res];

        echo json_encode($data);
    }

    public function getEselon($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];


            $result = $this->mglobal->get_data_all('M_ESELON', null, null, 'ID_ESELON,KODE_ESELON', "KODE_ESELON LIKE '%$q%'", ['KODE_ESELON', 'ASC'], null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_ESELON, 'name' => strtoupper($row->KODE_ESELON)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $where = ['ID_ESELON' => $id,];


            $result = $this->mglobal->get_data_all('M_ESELON', null, $where, 'ID_ESELON, KODE_ESELON', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_ESELON, 'name' => strtoupper($row->KODE_ESELON)];
            }

            echo json_encode($res);
        }
    }

    /**
     * Jabatan
     *
     * @return json Jabatan
     */
    public function getJabatan3($id_uk, $id_suk=NULL, $id_jab=NULL) {
        $q = $_GET['q'];
        $where['M_JABATAN.IS_ACTIVE'] = '1';
        if (!is_null($id_suk))
            $where['M_JABATAN.SUK_ID'] = $id_suk;
        if (!is_null($id_jab))
            $where['M_JABATAN.ID_JABATAN'] = $id_jab;
        $where['M_JABATAN.UK_ID'] = $id_uk;
        $where['M_JABATAN.NAMA_JABATAN LIKE'] = '%' . $q . '%';
        $where['M_SUB_UNIT_KERJA.UK_STATUS'] = '1';
        $where['M_UNIT_KERJA.UK_STATUS'] = '1';
        //$result = $this->mglobal->get_data_all('M_JABATAN', null, $where, 'ID_JABATAN, KODE_ESELON, NAMA_JABATAN, SUK_ID', null, ['LENGTH(NAMA_JABATAN), NAMA_JABATAN', 'asc'], null, '15');
        $result = $this->mglobal->get_data_all('M_JABATAN', [['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_JABATAN.SUK_ID = M_SUB_UNIT_KERJA.SUK_ID', 'join' => 'left'],['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_SUB_UNIT_KERJA.UK_ID', 'join' => 'left']], $where, 'M_JABATAN.ID_JABATAN, M_JABATAN.KODE_ESELON, M_JABATAN.NAMA_JABATAN, M_JABATAN.SUK_ID', null, ['LENGTH(NAMA_JABATAN), NAMA_JABATAN', 'asc'], null, '15');
//         echo $this->db->last_query();
//        $res[] = ['id' => '-99', 'name' => '-- Pilih Jabatan --'];
        foreach ($result as $row) {
            if ($row->SUK_ID == NULL || $row->SUK_ID == 'NULL'){
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper('<html><body><strike>'.$row->NAMA_JABATAN).'</strike></body></html>'];
                }else{
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper($row->NAMA_JABATAN)];
                }
        }

        $data = ['item' => $res];
        echo json_encode($res);
    }

    private function __getDefaultValueSelect2($textName = "-- Pilih --") {
        return ['id' => '', 'name' => $textName];
    }

    private function __getDefaultPencegahan($textName = "DEPUTI BIDANG PENCEGAHAN DAN MONITORING") {
        return ['id' => '12928', 'name' => $textName];
    }

    public function getJabatan2($id_uk) {
        $q = $_GET['q'];
        $where['IS_ACTIVE'] = '1';
        $where['UK_ID'] = $id_uk;
        $where['NAMA_JABATAN LIKE'] = '%' . $q . '%';
        $result = $this->mglobal->get_data_all('M_JABATAN', null, $where, 'ID_JABATAN, KODE_ESELON, NAMA_JABATAN, SUK_ID', null, ['LENGTH(NAMA_JABATAN), NAMA_JABATAN', 'asc'], null, '15');
        // echo $this->db->last_query();
        $res[] = $this->__getDefaultValueSelect2("-- Pilih Jabatan --");
        foreach ($result as $row) {
            if ($row->SUK_ID == NULL || $row->SUK_ID == 'NULL'){
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper('<html><body><strike>'.$row->NAMA_JABATAN).'</strike></body></html>'];
                }else{
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper($row->NAMA_JABATAN)];
                }
        }
        $data = ['item' => $res];
        echo json_encode($data);
    }

    public function getJabatan_maret($id_uk, $id_suk = null) {
        $q = $_GET['q'];
        $where['IS_ACTIVE'] = '1';
        $where['UK_ID'] = $id_uk;
        if ($id_suk != null) {
            $where['SUK_ID'] = $id_suk;
        }
        $where['NAMA_JABATAN LIKE'] = '%' . $q . '%';
        $result = $this->mglobal->get_data_all('M_JABATAN', null, $where, 'ID_JABATAN, KODE_ESELON, NAMA_JABATAN, SUK_ID', null, ['LENGTH(NAMA_JABATAN), NAMA_JABATAN', 'asc'], null, '15');
        //echo $this->db->last_query();
        $res[] = $this->__getDefaultValueSelect2("-- Pilih Jabatan --");
        foreach ($result as $row) {
            if ($row->SUK_ID == NULL || $row->SUK_ID == 'NULL'){
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper('<html><body><strike>'.$row->NAMA_JABATAN).'</strike></body></html>'];
                }else{
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper($row->NAMA_JABATAN)];
                }
        }
        $data = ['item' => $res];
        echo json_encode($data);
    }

    public function getJabatan($id_uk, $id_suk = NULL) {

        if (is_null($id_suk)) {
            $q = $_GET['q'];
            $where['IS_ACTIVE'] = '1';
            $where['UK_ID'] = $id_uk;
            $where['NAMA_JABATAN LIKE'] = '%' . $q . '%';
            $result = $this->mglobal->get_data_all('M_JABATAN', null, $where, 'ID_JABATAN, KODE_ESELON, NAMA_JABATAN, SUK_ID', null, ['NAMA_JABATAN', 'asc'], null, '30');
            // echo $this->db->last_query();
            $res[] = $this->__getDefaultValueSelect2("-- Pilih Jabatan --");
            foreach ($result as $row) {
                if ($row->SUK_ID == NULL || $row->SUK_ID == 'NULL'){
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper('<html><body><strike>'.$row->NAMA_JABATAN).'</strike></body></html>'];
                }else{
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper($row->NAMA_JABATAN)];
                }
            }
            $data = ['item' => $res];
            echo json_encode($data);
        } else {
            $q = $_GET['q'];
            $where['IS_ACTIVE'] = '1';
            $where['UK_ID'] = $id_uk;
            $where['SUK_ID'] = $id_suk;
            $where['NAMA_JABATAN LIKE'] = '%' . $q . '%';
            $result = $this->mglobal->get_data_all('M_JABATAN', null, $where, 'ID_JABATAN, KODE_ESELON, NAMA_JABATAN, SUK_ID', null, ['NAMA_JABATAN', 'asc'], null, '30');
            // echo $this->db->last_query();
            $res[] = $this->__getDefaultValueSelect2("-- Pilih Jabatan --");
            foreach ($result as $row) {
                if ($row->SUK_ID == NULL || $row->SUK_ID == 'NULL'){
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper('<html><body><strike>'.$row->NAMA_JABATAN).'</strike></body></html>'];
                }else{
                    $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper($row->NAMA_JABATAN)];
                }
            }
            $data = ['item' => $res];
            echo json_encode($data);
            exit;
        }


//        if (is_null($id)) {
//            $wheree['INST_SATKERKD'] = $id_lemb;
//            $lemb = $this->mglobal->get_data_all('M_INST_SATKER', null, $wheree, 'INST_BDG_ID')[0];
//
//            // echo $this->db->last_query();
//
//            $q = $_GET['q'];
//            $where['IS_ACTIVE'] = '1';
//            $where['KODE_JABATAN LIKE'] = $lemb->INST_BDG_ID . '%';
//            $where['NAMA_JABATAN LIKE'] = '%' . $q . '%';
//            $result = $this->mglobal->get_data_all('M_JABATAN', null, $where, 'ID_JABATAN, KODE_JABATAN, NAMA_JABATAN', null, ['LENGTH(NAMA_JABATAN), NAMA_JABATAN', 'asc'], null, '15');
//            // echo $this->db->last_query();
//            $res[] = ['id' => '-99', 'name' => '-- Pilih Jabatan --'];
//            foreach ($result as $row) {
//                $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper($row->NAMA_JABATAN)];
//            }
//            $data = ['item' => $res];
//            echo json_encode($data);
//        } else {
//            $wheree['INST_SATKERKD'] = $id_lemb;
//            $lemb = $this->mglobal->get_data_all('M_INST_SATKER', null, $wheree, 'INST_BDG_ID')[0];
//
//            $where = ['IS_ACTIVE' => '1', 'ID_JABATAN' => $id];
//            $where['KODE_JABATAN LIKE'] = $lemb->INST_BDG_ID . '%';
//
//            $result = $this->mglobal->get_data_all('M_JABATAN', null, $where, 'ID_JABATAN, NAMA_JABATAN', null, ['LENGTH(NAMA_JABATAN), NAMA_JABATAN', 'asc'], null, '15');
//            $res = [];
//            foreach ($result as $row) {
//                $res[] = ['id' => $row->ID_JABATAN, 'name' => strtoupper($row->NAMA_JABATAN)];
//            }
//            echo json_encode($res);
//        }
    }

    public function getPN($id = NULL) {
        if (is_null($id)) {

            $q = $_GET['q'];
            $where['IS_ACTIVE'] = '1';

            $whereE = "(NIK LIKE '%$q%' OR NAMA LIKE '%$q%')";
            $result = $this->mglobal->get_data_all('T_PN', null, $where, 'ID_PN, NIK, NAMA', $whereE, ['NAMA', 'asc'], null, '15');

            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PN, 'name' => strtoupper($row->NAMA), 'nik' => $row->NIK];
            }

            $data = ['item' => $res];
            echo json_encode($data);
        } else {
            $where = ['IS_ACTIVE' => '1', 'ID_PN' => $id];

            $result = $this->mglobal->get_data_all('T_PN', null, $where, 'ID_PN, NIK, NAMA', null, ['NAMA', 'asc'], null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PN, 'name' => strtoupper($row->NAMA), 'nik' => $row->NIK];
            }
            echo json_encode($res);
        }
    }

    /**
     * PN
     *
     * @return json Jabatan
     */
    public function getUser($role, $id = NULL) {
        // $whereE = "T_USER_ROLE.ID_ROLE IN (".urldecode($role).")";
        // $join = [
        //     ['table' => 'T_USER_ROLE', 'on' => 'T_USER.ID_ROLE_SPLIT=T_USER_ROLE.ID_ROLE']
        // ];

        if (is_null($id)) {
            $q = $_GET['q'];
            // $where['T_USER.IS_ACTIVE']     = '1';
            // $where['USERNAME LIKE'] = '%'.$q.'%';
            // $result = $this->mglobal->get_data_all("(
            // 	SELECT t.*, SUBSTRING_INDEX(SUBSTRING_INDEX(t.ID_ROLE, ',', n.n), ',', -1) ID_ROLE_SPLIT
            // 	  FROM T_USER t CROSS JOIN (
            // 	SELECT a.N + b.N * 10 + 1 n
            // 	 FROM
            // 	(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
            // 	,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
            // 	ORDER BY n
            // 	) n
            // 	 WHERE n.n <= 1 + (LENGTH(t.ID_ROLE) - LENGTH(REPLACE(t.ID_ROLE, ',', '')))
            // 	 ORDER BY ID_USER, ID_ROLE_SPLIT
            // 	) T_USER", $join, $where, 'USERNAME, ROLE', $whereE, null , null , '15');

            $sql = "SELECT T_USER.USERNAME, T_USER_ROLE.ROLE FROM /*(
					SELECT t.*, SUBSTRING_INDEX(SUBSTRING_INDEX(t.ID_ROLE, ',', n.n), ',', -1) ID_ROLE_SPLIT
						FROM T_USER t CROSS JOIN (
					SELECT a.N + b.N * 10 + 1 n
					 FROM
					(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
					,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
					ORDER BY n
					) n
					 WHERE n.n <= 1 + (LENGTH(t.ID_ROLE) - LENGTH(REPLACE(t.ID_ROLE, ',', '')))
					 ORDER BY ID_USER, ID_ROLE_SPLIT

					)*/ T_USER
					-- JOIN T_USER_ROLE ON T_USER.ID_ROLE_SPLIT=T_USER_ROLE.ID_ROLE
					JOIN T_USER_ROLE ON T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE
					WHERE T_USER.IS_ACTIVE = 1
					AND USERNAME LIKE '%" . $q . "%'
					AND T_USER_ROLE.ID_ROLE IN (" . urldecode($role) . ")
					ORDER BY ROLE, USERNAME ASC
					LIMIT 15
					";
            $result = $this->db->query($sql)->result();
            // echo $this->db->last_query();
            $res[] = ['id' => '0', 'name' => '-- Pilih CS --'];
            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => strtoupper($row->USERNAME), 'role' => $row->ROLE];
            }

            $data = ['item' => $res];
            echo json_encode($data);
            exit;
        } else {
            //          $where['T_USER.IS_ACTIVE']     = '1';
            //          $where['USERNAME']      = $id;
            // $result = $this->mglobal->get_data_all("(
            // 	SELECT t.*, SUBSTRING_INDEX(SUBSTRING_INDEX(t.ID_ROLE, ',', n.n), ',', -1) ID_ROLE_SPLIT
            // 	  FROM T_USER t CROSS JOIN (
            // 	SELECT a.N + b.N * 10 + 1 n
            // 	 FROM
            // 	(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
            // 	,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
            // 	ORDER BY n
            // 	) n
            // 	 WHERE n.n <= 1 + (LENGTH(t.ID_ROLE) - LENGTH(REPLACE(t.ID_ROLE, ',', '')))
            // 	 ORDER BY ID_USER, ID_ROLE_SPLIT
            // 	) T_USER", $join, $where, 'USERNAME, ROLE', $whereE, null , null , '15');

            $sql = "SELECT T_USER.USERNAME, T_USER_ROLE.ROLE FROM /*(
					SELECT t.*, SUBSTRING_INDEX(SUBSTRING_INDEX(t.ID_ROLE, ',', n.n), ',', -1) ID_ROLE_SPLIT
						FROM T_USER t CROSS JOIN (
					SELECT a.N + b.N * 10 + 1 n
					 FROM
					(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
					,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
					ORDER BY n
					) n
					 WHERE n.n <= 1 + (LENGTH(t.ID_ROLE) - LENGTH(REPLACE(t.ID_ROLE, ',', '')))
					 ORDER BY ID_USER, ID_ROLE_SPLIT

					)*/ T_USER
					-- JOIN T_USER_ROLE ON T_USER.ID_ROLE_SPLIT=T_USER_ROLE.ID_ROLE
                                        JOIN T_USER_ROLE ON T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE
					WHERE T_USER.IS_ACTIVE = 1
					AND USERNAME LIKE '%" . $id . "%'
					AND T_USER_ROLE.ID_ROLE IN (" . urldecode($role) . ")
					LIMIT 15
					";
            $result = $this->db->query($sql)->result();

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => strtoupper($row->USERNAME), 'role' => $row->ROLE];
            }

            if (empty($res)) {
                $res[] = ['id' => '0', 'name' => '-- Pilih CS --', 'role' => ''];
            }
            echo json_encode($res);
            exit;
        }
    }

    public function getUserVerif($role, $id = NULL) {

        if (is_null($id)) {
            
            $res[] = ['id' => '','name' => '-- ALL --', 'role' => ''];
            $q = $_GET['q'];

            $sql = "SELECT T_USER.USERNAME, T_USER_ROLE.ROLE FROM /*(
					SELECT t.*, SUBSTRING_INDEX(SUBSTRING_INDEX(t.ID_ROLE, ',', n.n), ',', -1) ID_ROLE_SPLIT
						FROM T_USER t CROSS JOIN (
					SELECT a.N + b.N * 10 + 1 n
					 FROM
					(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
					,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
					ORDER BY n
					) n
					 WHERE n.n <= 1 + (LENGTH(t.ID_ROLE) - LENGTH(REPLACE(t.ID_ROLE, ',', '')))
					 ORDER BY ID_USER, ID_ROLE_SPLIT

					)*/ T_USER
					-- JOIN T_USER_ROLE ON T_USER.ID_ROLE_SPLIT=T_USER_ROLE.ID_ROLE
					JOIN T_USER_ROLE ON T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE
					WHERE T_USER.IS_ACTIVE = 1
					AND USERNAME LIKE '%" . $q . "%'
					AND T_USER_ROLE.ID_ROLE IN (" . urldecode($role) . ")
					ORDER BY ROLE, USERNAME ASC
					LIMIT 15
					";
            $result = $this->db->query($sql)->result();

            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => strtoupper($row->USERNAME), 'role' => $row->ROLE];
            }

            $data = ['item' => $res];
            echo json_encode($data);
            exit;
        } else {


            $sql = "SELECT T_USER.USERNAME, T_USER_ROLE.ROLE FROM /*(
					SELECT t.*, SUBSTRING_INDEX(SUBSTRING_INDEX(t.ID_ROLE, ',', n.n), ',', -1) ID_ROLE_SPLIT
						FROM T_USER t CROSS JOIN (
					SELECT a.N + b.N * 10 + 1 n
					 FROM
					(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
					,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
					ORDER BY n
					) n
					 WHERE n.n <= 1 + (LENGTH(t.ID_ROLE) - LENGTH(REPLACE(t.ID_ROLE, ',', '')))
					 ORDER BY ID_USER, ID_ROLE_SPLIT

					)*/ T_USER
					-- JOIN T_USER_ROLE ON T_USER.ID_ROLE_SPLIT=T_USER_ROLE.ID_ROLE
                                        JOIN T_USER_ROLE ON T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE
					WHERE T_USER.IS_ACTIVE = 1
					AND USERNAME LIKE '%" . $id . "%'
					AND T_USER_ROLE.ID_ROLE IN (" . urldecode($role) . ")
					LIMIT 15
					";
            $result = $this->db->query($sql)->result();

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => strtoupper($row->USERNAME), 'role' => $row->ROLE];
            }

            if (empty($res)) {
                $res[] = ['id' => '0', 'name' => '-- Pilih CS --', 'role' => ''];
            }
            echo json_encode($res);
            exit;
        }
    }

    public function getUsercustomru($role, $id = NULL) {
        if ($role == 'kpk') {
            $where['IS_KPK'] = '1';
        }

        $join = [
            ['table' => 'T_USER_ROLE', 'on' => 'T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE']
        ];

        if (is_null($id)) {
            $q = $_GET['q'];
            $where['T_USER.IS_ACTIVE'] = '1';
            $where['USERNAME LIKE'] = '%' . $q . '%';

            $result = $this->mglobal->get_data_all('T_USER', $join, $where, 'USERNAME,ROLE', null, ['T_USER_ROLE.ROLE,T_USER.USERNAME', 'ASC'], null, '15');
            $res[] = ['id' => '0', 'name' => '-- Pilih CS dahulu --'];
            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => '<strong>' . strtoupper($row->ROLE) . '</strong> : ' . strtoupper($row->USERNAME)];
            }

            $data = ['item' => $res];
            echo json_encode($data);
        } else {
            $where['T_USER.IS_ACTIVE'] = '1';
            $where['USERNAME'] = $id;
            $result = $this->mglobal->get_data_all('T_USER', $join, $where, 'USERNAME,ROLE', null, ['T_USER_ROLE.ROLE,T_USER.USERNAME', 'ASC'], null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => '<strong>' . strtoupper($row->ROLE) . '</strong> : ' . strtoupper($row->USERNAME)];
            }

            if (empty($res)) {
                $res[] = ['id' => '0', 'name' => '-- Pilih CS dahulu--'];
            }
            echo json_encode($res);
        }
    }

    public function getUsercustomruKoord($role, $id = NULL) {
        if ($role == 'kpk') {
            $where['IS_KPK'] = '1';
        }

        $join = [
            ['table' => 'T_USER_ROLE', 'on' => 'T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE']
        ];

        if (is_null($id)) {
            $q = $_GET['q'];
            $where['T_USER.IS_ACTIVE'] = '1';
            $where['USERNAME LIKE'] = '%' . $q . '%';

            $result = $this->mglobal->get_data_all('T_USER', $join, $where, 'USERNAME,ROLE', null, ['T_USER_ROLE.ROLE,T_USER.USERNAME', 'ASC'], null, '15');
            $res[] = ['id' => '0', 'name' => '-- Pilih Koordinator CS --'];
            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => '<strong>' . strtoupper($row->ROLE) . '</strong> : ' . strtoupper($row->USERNAME)];
            }

            $data = ['item' => $res];
            echo json_encode($data);
        } else {
            $where['T_USER.IS_ACTIVE'] = '1';
            $where['USERNAME'] = $id;
            $result = $this->mglobal->get_data_all('T_USER', $join, $where, 'USERNAME,ROLE', null, ['T_USER_ROLE.ROLE,T_USER.USERNAME', 'ASC'], null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => '<strong>' . strtoupper($row->ROLE) . '</strong> : ' . strtoupper($row->USERNAME)];
            }

            if (empty($res)) {
                $res[] = ['id' => '0', 'name' => '-- Pilih Koordinator CS --'];
            }
            echo json_encode($res);
        }
    }

    /**
     * Unit Kerja
     *
     * @return json Unit Kerja
     */
    public function getUnitKerja2() {
        $q = $_GET['q'];


        $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, null, 'UK_ID, UK_NAMA', "UK_NAMA LIKE '%$q%'", null, null, '15');
        $res = [];
        foreach ($result as $row) {
            $res[] = ['id' => $row->UK_ID, 'name' => strtoupper($row->UK_NAMA)];
        }
            $res[] = ['id' => '', 'name' => "-- All --"];
        $data = ['item' => $res];

        echo json_encode($data);
    }

    public function getUnitKerja($lembaga = NULL, $id = NULL) {
        $res = [];

        $set_default_null = $this->input->get('setdefault_to_null');

//        var_dump($id);exit;
        if ($lembaga == "1081") {
            $res[] = ['id' => '590', 'name' => "DEPUTI BIDANG PENCEGAHAN DAN MONITORING"];
        }

        if (is_null($id)) {
            $res[] = ['id' => '', 'name' => "-- All --"];
            $q = $_GET['q'];

            $where = ['M_UNIT_KERJA.UK_STATUS' => '1', 'UK_LEMBAGA_ID' => $lembaga];

            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', [['table' => 'm_sub_unit_kerja', 'on' => 'm_sub_unit_kerja.uk_id = M_UNIT_KERJA.uk_id', 'join' => 'left'],['table' => 'm_jabatan', 'on' => 'm_jabatan.suk_id = m_sub_unit_kerja.suk_id', 'join' => 'left']], $where , 'M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA, COUNT(m_sub_unit_kerja.suk_id) AS SUK_ID, COUNT(m_jabatan.id_jabatan) AS ID_JAB', "UK_NAMA LIKE '%$q%'", ['UK_NAMA', 'asc'], null, '15','M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA');
            $resultEqui = $this->mglobal->get_data_all('M_UNIT_KERJA', [['table' => 'm_sub_unit_kerja', 'on' => 'm_sub_unit_kerja.uk_id = M_UNIT_KERJA.uk_id', 'join' => 'left'],['table' => 'm_jabatan', 'on' => 'm_jabatan.suk_id = m_sub_unit_kerja.suk_id', 'join' => 'left']], $where , 'M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA, COUNT(m_sub_unit_kerja.suk_id) AS SUK_ID, COUNT(m_jabatan.id_jabatan) AS ID_JAB', "UK_NAMA = '$q'", ['UK_NAMA', 'asc'], null, '1','M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA');
            
            $result = array_merge($resultEqui, $result);
            $result = array_map("unserialize", array_unique(array_map("serialize", $result)));

            foreach ($result as $row) {
                if (($row->SUK_ID == 0 || $row->SUK_ID == '0') || ($row->ID_JAB == 0 || $row->ID_JAB == '0')){
                    $res[] = ['id' => $row->UK_ID, 'name' => strtoupper('<html><body><strike>'.$row->UK_NAMA).'</strike></body></html>'];
                }else{
                    $res[] = ['id' => $row->UK_ID, 'name' => strtoupper($row->UK_NAMA)];
                }
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['M_UNIT_KERJA.UK_STATUS' => '1', 'M_UNIT_KERJA.UK_ID' => $id, 'UK_LEMBAGA_ID' => $lembaga];

            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', [['table' => 'm_sub_unit_kerja', 'on' => 'm_sub_unit_kerja.uk_id = M_UNIT_KERJA.uk_id', 'join' => 'left'],['table' => 'm_jabatan', 'on' => 'm_jabatan.suk_id = m_sub_unit_kerja.suk_id', 'join' => 'left']], $where , 'M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA, COUNT(m_sub_unit_kerja.suk_id) AS SUK_ID, COUNT(m_jabatan.id_jabatan) AS ID_JAB', null, null, null, '15','M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA');


            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, null,
            //     'UK_ID, UK_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row) {
                if (($row->SUK_ID == 0 || $row->SUK_ID == '0') || ($row->ID_JAB == 0 || $row->ID_JAB == '0')){
                    $res[] = ['id' => $row->UK_ID, 'name' => strtoupper('<html><body><strike>'.$row->UK_NAMA).'</strike></body></html>'];
                }else{
                    $res[] = ['id' => $row->UK_ID, 'name' => strtoupper($row->UK_NAMA)];
                }
            }

            echo json_encode($res);
        }
        exit;
    }

    public function getUnitKerjaTanpaDefault($lembaga = NULL, $id = NULL) {
        $res = [];

        $set_default_null = $this->input->get('setdefault_to_null');

        if (is_null($id)) {
            $res[] = ['id' => '', 'name' => "-- All --"];
            $q = $_GET['q'];

            $where = ['M_UNIT_KERJA.UK_STATUS' => '1', 'UK_LEMBAGA_ID' => $lembaga];

            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', [['table' => 'm_sub_unit_kerja', 'on' => 'm_sub_unit_kerja.uk_id = M_UNIT_KERJA.uk_id', 'join' => 'left'],['table' => 'm_jabatan', 'on' => 'm_jabatan.suk_id = m_sub_unit_kerja.suk_id', 'join' => 'left']], $where , 'M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA, COUNT(m_sub_unit_kerja.suk_id) AS SUK_ID, COUNT(m_jabatan.id_jabatan) AS ID_JAB', "UK_NAMA LIKE '%$q%'", ['UK_NAMA', 'asc'], null, '15','M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA');
            $resultEqui = $this->mglobal->get_data_all('M_UNIT_KERJA', [['table' => 'm_sub_unit_kerja', 'on' => 'm_sub_unit_kerja.uk_id = M_UNIT_KERJA.uk_id', 'join' => 'left'],['table' => 'm_jabatan', 'on' => 'm_jabatan.suk_id = m_sub_unit_kerja.suk_id', 'join' => 'left']], $where , 'M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA, COUNT(m_sub_unit_kerja.suk_id) AS SUK_ID, COUNT(m_jabatan.id_jabatan) AS ID_JAB', "UK_NAMA = '$q'", ['UK_NAMA', 'asc'], null, '1','M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA');

            $result = array_merge($resultEqui, $result);
            $result = array_map("unserialize", array_unique(array_map("serialize", $result)));

            foreach ($result as $row) {
                if (($row->SUK_ID == 0 || $row->SUK_ID == '0') || ($row->ID_JAB == 0 || $row->ID_JAB == '0')){
                    $res[] = ['id' => $row->UK_ID, 'name' => strtoupper('<html><body><strike>'.$row->UK_NAMA).'</strike></body></html>'];
                }else{
                    $res[] = ['id' => $row->UK_ID, 'name' => strtoupper($row->UK_NAMA)];
                }
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['M_UNIT_KERJA.UK_STATUS' => '1', 'M_UNIT_KERJA.UK_ID' => $id, 'UK_LEMBAGA_ID' => $lembaga];

            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', [['table' => 'm_sub_unit_kerja', 'on' => 'm_sub_unit_kerja.uk_id = M_UNIT_KERJA.uk_id', 'join' => 'left'],['table' => 'm_jabatan', 'on' => 'm_jabatan.suk_id = m_sub_unit_kerja.suk_id', 'join' => 'left']], $where , 'M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA, COUNT(m_sub_unit_kerja.suk_id) AS SUK_ID, COUNT(m_jabatan.id_jabatan) AS ID_JAB', null, null, null, '15','M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA');


            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, null,
            //     'UK_ID, UK_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row) {
                if (($row->SUK_ID == 0 || $row->SUK_ID == '0') || ($row->ID_JAB == 0 || $row->ID_JAB == '0')){
                    $res[] = ['id' => $row->UK_ID, 'name' => strtoupper('<html><body><strike>'.$row->UK_NAMA).'</strike></body></html>'];
                }else{
                    $res[] = ['id' => $row->UK_ID, 'name' => strtoupper($row->UK_NAMA)];
                }
            }

            echo json_encode($res);
        }
        exit;
    }

    public function getSubUnitKerja($unitkerja = NULL, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];

            $where = ['M_SUB_UNIT_KERJA.UK_STATUS' => '1', 'M_SUB_UNIT_KERJA.UK_ID' => $unitkerja, 'M_UNIT_KERJA.UK_STATUS' => '1'];
            
            //$result = $this->mglobal->get_data_all('M_SUB_UNIT_KERJA', null, $where, 'SUK_ID, SUK_NAMA', "SUK_NAMA LIKE '%$q%' order by SUK_NAMA asc", null, null, '15');
            $result = $this->mglobal->get_data_all('M_SUB_UNIT_KERJA', [['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.uk_id = M_SUB_UNIT_KERJA.uk_id', 'join' => 'left']], $where, 'M_SUB_UNIT_KERJA.SUK_ID, M_SUB_UNIT_KERJA.SUK_NAMA', "M_SUB_UNIT_KERJA.SUK_NAMA LIKE '%$q%' order by M_SUB_UNIT_KERJA.SUK_NAMA desc", null, null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->SUK_ID, 'name' => strtoupper($row->SUK_NAMA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['M_SUB_UNIT_KERJA.UK_STATUS' => '1', 'M_SUB_UNIT_KERJA.SUK_ID' => $id, 'M_SUB_UNIT_KERJA.UK_ID' => $unitkerja, 'M_UNIT_KERJA.UK_STATUS' => '1'];

            //$result = $this->mglobal->get_data_all('M_SUB_UNIT_KERJA', null, $where, 'SUK_ID, SUK_NAMA', null, null, null, '15');
            $result = $this->mglobal->get_data_all('M_SUB_UNIT_KERJA', [['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.uk_id = M_SUB_UNIT_KERJA.uk_id', 'join' => 'left']], $where, 'M_SUB_UNIT_KERJA.SUK_ID, M_SUB_UNIT_KERJA.SUK_NAMA', "M_SUB_UNIT_KERJA.SUK_NAMA LIKE '%$q%' order by M_SUB_UNIT_KERJA.SUK_NAMA desc", null, null, '15');
            
            $res = [];
            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, null,
            //     'UK_ID, UK_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row) {
                $res[] = ['id' => $row->SUK_ID, 'name' => strtoupper($row->SUK_NAMA)];
            }

            echo json_encode($res);
        }
        exit;
    }

    public function getUnitKerjanama($lembaga = NULL, $id = NULL) {
        $lembaga = explode('-', $lembaga)[0];
        if (is_null($id)) {
            $q = $_GET['q'];

            $where = ['UK_LEMBAGA_ID' => $lembaga];
            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, $where, 'UK_ID, UK_NAMA', "UK_NAMA LIKE '%$q%'", null, null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->UK_ID . '-' . urlencode(str_replace(" ", "_", $row->UK_NAMA)), 'name' => strtoupper($row->UK_NAMA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $idexplode = explode('-', $id);
            $id = $idexplode[0];
            $where = ['UK_ID' => $id, 'UK_LEMBAGA_ID' => $lembaga];

            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, $where, 'UK_ID, UK_NAMA', null, null, null, '15');

            $res = [];
            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, null,
            //     'UK_ID, UK_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row) {
                $res[] = ['id' => $row->UK_ID . '-' . urlencode(str_replace(" ", "_", $row->UK_NAMA)), 'name' => strtoupper($row->UK_NAMA)];
            }

            echo json_encode($res);
        }
    }

    public function getLembaganama($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA', "INST_NAMA LIKE '%$q%'", array('INST_NAMA', 'ASC'), null, '15');
            $res[] = $this->__getDefaultValueSelect2("-- Pilih Instansi --");
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD . '-' . urlencode(str_replace(" ", "_", $row->INST_NAMA)), 'name' => strtoupper($row->INST_NAMA)];
            }
            $data = ['item' => $res];
            echo json_encode($data);
        } else {
            // $idexplode = explode('-', $id);
            // $id = $idexplode[0];
            $where = ['IS_ACTIVE' => '1', 'INST_SATKERKD' => $id];
            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA', null, null, null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD . '-' . urlencode(str_replace(" ", "_", $row->INST_NAMA)), 'name' => strtoupper($row->INST_NAMA)];
            }
            echo json_encode($res);
        }
    }

    /**
     * Lembaga
     *
     * @return json Lembaga
     */
    public function getLembaga($id = NULL) {
        if (is_null($id)) {

            $not_using_default_value = $this->input->get('nudv');
            $setdefault_to_null = $this->input->get('setdefault_to_null');

            $q = $_GET['q'];
            $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA', "INST_NAMA LIKE '%$q%'", array('INST_NAMA', 'ASC'), null, '15');
            $res = [];
            //if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31" || $this->session->userdata('ID_ROLE') == "108") {
            $role_default_kpk = in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_PN_WL_OFFLINE')['default_instansi_is_kpk']);
            if($role_default_kpk) {
                $res[] = ['id' => '1081', 'name' => "KOMISI PEMBERANTASAN KORUPSI (KPK)"];
            }
            if (!$not_using_default_value || $setdefault_to_null) {
                $res[] = $this->__getDefaultValueSelect2("-- All Instansi --");
            }
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }
            $data = ['item' => $res];
            echo json_encode($data);
        } else {
            $where = ['IS_ACTIVE' => '1', 'INST_SATKERKD' => $id];
            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, $where, 'INST_SATKERKD, INST_NAMA', null, null, null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }
            echo json_encode($res);
        }
        exit;
    }

    /**
     * User KPK Aktif
     *
     * @return json User KPK Aktif
     */
    public function getUserKPKAktif($id = NULL) {
        $idRole = $this->session->userdata('ID_ROLE');
        $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, ['ID_ROLE' => $idRole], 'IS_KPK, IS_INSTANSI, IS_USER_INSTANSI');

        if (!empty($role)) {
            $inst = $role[0]->IS_INSTANSI;
            $user = $role[0]->IS_USER_INSTANSI;
            $IS_KPK = $role[0]->IS_KPK;
            if ($IS_KPK != 1) {
                $res = [];
                $data = ['item' => $res];
                echo json_encode($data);
                return;
            }
        }

        $join = [
            ['table' => 'T_USER_ROLE', 'on' => 'T_USER_ROLE.ID_ROLE = T_USER.ID_ROLE AND T_USER_ROLE.IS_KPK = 1', 'join' => '']
        ];
        if (is_null($id)) {
            $q = $_GET['q'];
            $where = ['T_USER.IS_ACTIVE' => '1', 'T_USER.USERNAME !=' => 'admin_kpk'];
            $result = $this->mglobal->get_data_all('T_USER', $join, $where, 'T_USER.USERNAME, T_USER.USERNAME', "T_USER.USERNAME LIKE '%$q%'", null, null, '15');
            $res[] = ['id' => '', 'name' => '-- Pilih User --'];
            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => strtoupper($row->USERNAME)];
            }
            $data = ['item' => $res];
            echo json_encode($data);
        } else {
            $where = ['T_USER.IS_ACTIVE' => '1', 'T_USER.USERNAME' => $id];
            $result = $this->mglobal->get_data_all('T_USER', $join, $where, 'T_USER.USERNAME, T_USER.USERNAME', null, null, null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->USERNAME, 'name' => strtoupper($row->USERNAME)];
            }
            echo json_encode($res);
        }
        exit;
    }

    /**
     * Akhir Jabatan
     *
     * @return json Akhir Jabatan
     */
    public function getAkhirJabat($lembaga = NULL, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $where = ['IS_AKTIF' => '0'];
            $result = $this->mglobal->get_data_all('T_STATUS_AKHIR_JABAT', null, $where, 'ID_STATUS_AKHIR_JABAT, STATUS', "STATUS LIKE '%$q%'", null, null, '15');
            $res[] = $this->__getDefaultValueSelect2("-- Pilih Status --");
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_STATUS_AKHIR_JABAT, 'name' => strtoupper($row->STATUS)];
            }
            $data = ['item' => $res];
            echo json_encode($data);
        } else {
            $where = ['IS_ACTIVE' => '1', 'ID_STATUS_AKHIR_JABAT' => $id];
            $result = $this->mglobal->get_data_all('T_STATUS_AKHIR_JABAT', null, $where, 'ID_STATUS_AKHIR_JABAT, STATUS', null, null, null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_STATUS_AKHIR_JABAT, 'name' => strtoupper($row->STATUS)];
            }
            echo json_encode($res);
        }
        exit;
    }

    /**
     * Negara
     *
     * @return json Negara
     */
    public function getNegara($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];

            $result = $this->mglobal->get_data_all('M_NEGARA', null, NULL, '*', "NAMA_NEGARA LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID, 'name' => strtoupper($row->NAMA_NEGARA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['id' => $id];

            $result = $this->mglobal->get_data_all('M_NEGARA', null, $where, '*', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID, 'name' => strtoupper($row->NAMA_NEGARA)];
            }

            echo json_encode($res);
        }
        exit;
    }

    /**
     * Provinsi
     *
     * @return json Provinsi
     */
    public function get_propinsi($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];


            $result = $this->mglobal->get_data_all('M_AREA_PROV', null, ['IS_ACTIVE' => '1'], 'ID_PROV,NAME', "NAME LIKE '%$q%'", ['NAME', 'ASC'], null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PROV, 'name' => strtoupper($row->NAME)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $where = ['ID_PROV' => $id, 'IS_ACTIVE' => '1'];


            $result = $this->mglobal->get_data_all('M_AREA_PROV', null, $where, 'ID_PROV, NAME', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PROV, 'name' => strtoupper($row->NAME)];
            }

            echo json_encode($res);
        }
        exit;
    }

    public function getProvinsi($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];


            $result = $this->mglobal->get_data_all('M_AREA', null, ['IS_ACTIVE' => '1', 'LEVEL' => '1'], 'IDPROV,NAME', "NAME LIKE '%$q%'", ['NAME', 'ASC'], null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDPROV, 'name' => strtoupper($row->NAME)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $where = ['IDPROV' => $id, 'IS_ACTIVE' => '1'];


            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDPROV, NAME', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDPROV, 'name' => strtoupper($row->NAME)];
            }

            echo json_encode($res);
        }
    }

    public function getProvinsiNew($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];


            $result = $this->mglobal->get_data_all('M_AREA_PROV', null, ['IS_ACTIVE' => '1', 'LEVEL' => '1'], 'ID_PROV,NAME', "NAME LIKE '%$q%'", ['NAME', 'ASC'], null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PROV, 'name' => strtoupper($row->NAME)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $where = ['ID_PROV' => $id, 'IS_ACTIVE' => '1'];


            $result = $this->mglobal->get_data_all('M_AREA_PROV', null, $where, 'ID_PROV, NAME', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_PROV, 'name' => strtoupper($row->NAME)];
            }

            echo json_encode($res);
        }
    }

    /**
     * Kota
     *
     * @return json Kota
     */
    public function getKabNew($prov = NULL, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $prov = $_GET['prov'];


            $where = ['IDPROV' => $prov, 'LEVEL' => '2'];


            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKOT,NAME', "NAME LIKE '%$q%'", ['NAME', 'ASC'], null, '15');


            $res = [];
            foreach ($result as $row) {
                if (substr($row->IDKOT, 0, 1) == 7) {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower('Kota ' . $row->NAME))];
                } else {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower('Kabupaten ' . $row->NAME))];
                }
            }


            $data = ['item' => $res];


            echo json_encode($data);
        } else {
            $where = ['IDPROV' => $prov, 'IDKOT' => $id, 'LEVEL' => '2'];


            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKOT, NAME', null, ['NAME', 'ASC'], null, '15');


            $res = [];
            foreach ($result as $row) {
                if (substr($row->IDKOT, 0, 1) == 7) {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower($row->NAME))];
                } else {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower('Kabupaten ' . $row->NAME))];
                }
            }


            echo json_encode($res);
        }
    }

    /**
     * Kota
     *
     * @return json Kota
     */
    public function getKabupatenKota($prov = NULL, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $prov = $_GET['prov'];


            $where = ['IDPROV' => $prov, 'LEVEL' => '2'];


            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKOT,NAME', "NAME LIKE '%$q%'", ['NAME', 'ASC'], null, '15');


            $res = [];
            foreach ($result as $row) {
                if (substr($row->IDKOT, 0, 1) == 7) {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower('Kota ' . $row->NAME))];
                } else {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower('Kabupaten ' . $row->NAME))];
                }
            }


            $data = ['item' => $res];


            echo json_encode($data);
        } else {
            $where = ['IDPROV' => $prov, 'IDKOT' => $id, 'LEVEL' => '2'];


            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKOT, NAME', null, ['NAME', 'ASC'], null, '15');


            $res = [];
            foreach ($result as $row) {
                if (substr($row->IDKOT, 0, 1) == 7) {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower($row->NAME))];
                } else {
                    $res[] = ['id' => $row->IDKOT, 'name' => strtoupper(strtolower('Kabupaten ' . $row->NAME))];
                }
            }


            echo json_encode($res);
        }
    }

    public function getKab($prov = NULL, $id = NULL) {
        if (is_null($id)) {
//            $q = $_GET['q'];
//            $this->input->post
            $q = $this->input->get('q');
            $prov = $this->input->get('prop');

            //$prov = $_POST['propinsi'];
//            echo "prov: ".$prop;
            $where = ['ID_PROV' => $prov, 'IS_ACTIVE' => '1'];

            $result = $this->mglobal->get_data_all('M_AREA_KAB', null, $where, 'ID_KAB,NAME_KAB', "NAME_KAB LIKE '%$q%'", ['NAME_KAB', 'ASC'], null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_KAB, 'name' => strtoupper($row->NAME_KAB)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            //tik
            //$where = ['ID_PROV' => $prov,'ID_KAB' => $id];
            $where = ['ID_KAB' => $id];


            $result = $this->mglobal->get_data_all('M_AREA_KAB', null, $where, 'ID_KAB, NAME_KAB', null, null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_KAB, 'name' => strtoupper($row->NAME_KAB)];
            }

            echo json_encode($res);
        }
//        if (is_null($id)) {
//            $q = $_GET['q'];
//            $prov = $_GET['prov'];
//            print_r("prov: ".$prov);
//
//            $where = ['ID_PROV' => $prov];
//
//
//            $result = $this->mglobal->get_data_all('M_AREA_KAB', null, $where, 'ID_KAB,NAME_KAB', "NAME_KAB LIKE '%$q%'", ['NAME_KAB', 'ASC'], null, '15');
//
//
//            $res = [];
//            foreach ($result as $row) {
//                if (substr($row->ID_KAB, 0, 1) == 7) {
//                    $res[] = ['id' => $row->ID_KAB, 'name' => strtoupper(strtolower('Kota ' . $row->NAME_KAB))];
//                } else {
//                    $res[] = ['id' => $row->ID_KAB, 'name' => strtoupper(strtolower('Kabupaten ' . $row->NAME_KAB))];
//                }
//            }
//
//
//            $data = ['item' => $res];
//
//
//            echo json_encode($data);
//        } else {
//            $where = ['ID_PROV' => $prov, 'ID_KAB' => $id];
//
//
//            $result = $this->mglobal->get_data_all('M_AREA_KAB', null, $where, 'ID_KAB, NAME_KAB', null, ['NAME_KAB', 'ASC'], null, '15');
//
//
//            $res = [];
//            foreach ($result as $row) {
//                if (substr($row->ID_KAB, 0, 1) == 7) {
//                    $res[] = ['id' => $row->ID_KAB, 'name' => strtoupper(strtolower($row->NAME_KAB))];
//                } else {
//                    $res[] = ['id' => $row->ID_KAB, 'name' => strtoupper(strtolower('Kabupaten ' . $row->NAME_KAB))];
//                }
//            }
//
//
//            echo json_encode($res);
//        }
    }

    public function getKecamatan($prov = NULL, $kab = NULL, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $prov = $_GET['prov'];
            $kab = $_GET['kab'];

            $where = [
                'IDPROV' => $prov,
                'IDKEC !=' => '',
                'IDKEL' => '',
                'IDKEC <>' => ''
            ];

            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKEC,NAME', "IDKEL = '' AND CAST(IDKOT as UNSIGNED) = '$kab' AND NAME LIKE '%$q%'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDKEC, 'name' => strtoupper($row->NAME)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = [
                'IDPROV' => $prov,
                'IDKEC' => $id,
                'IDKEL' => '',
            ];

            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKEC, NAME', "CAST(IDKOT as UNSIGNED) = '$kab'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDKEC, 'name' => strtoupper($row->NAME)];
            }

            echo json_encode($res);
        }
    }

    public function getKelurahan($prov = NULL, $kab = NULL, $kec = NULL, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $prov = $_GET['prov'];
            $kab = $_GET['kab'];
            $kec = $_GET['kec'];

            $where = ['IDPROV' => $prov, 'IDKEC' => $kec, 'IDKEL <>' => ''];

            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKEL,NAME', "CAST(IDKOT as UNSIGNED) = '$kab' AND NAME LIKE '%$q%'", null, null, '15');
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDKEL, 'name' => strtoupper($row->NAME)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $where = ['IDPROV' => $prov, 'IDKEC' => $kec, 'IDKEL' => $id];

            $result = $this->mglobal->get_data_all('M_AREA', null, $where, 'IDKEL, NAME', "CAST(IDKOT as UNSIGNED) = '$kab'", null, null, '15');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->IDKEL, 'name' => strtoupper($row->NAME)];
            }

            echo json_encode($res);
        }
    }

    public function getInstansi($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $qq = $_GET['qq'];

            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, ['IS_ACTIVE' => '1', 'INST_NAMA LIKE' => "%$q%", 'INST_BDG_ID' => "$qq"], 'INST_SATKERKD, INST_NAMA', null, null, 0, 10);
            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $result = $this->mglobal->get_data_all('M_INST_SATKER', null, ['IS_ACTIVE' => '1', 'INST_SATKERKD' => $id], 'INST_SATKERKD, INST_NAMA', null, null, 0, 10);

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->INST_SATKERKD, 'name' => strtoupper($row->INST_NAMA)];
            }

            echo json_encode($res);
        }
    }

    public function getatasnamabylhkpn($id_lhkpn, $id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $where = ['IS_ACTIVE' => '1', 'ID_LHKPN' => $id_lhkpn];
            $where2 = ['ID_LHKPN' => $id_lhkpn];
            $result = $this->mglobal->get_data_all('T_LHKPN_KELUARGA', null, $where, 'NAMA', "NAMA LIKE '%$q%'", array('NAMA', 'ASC'), null, '15');
            $res[] = ['id' => $q, 'name' => $q];
            foreach ($result as $row) {
                $res[] = ['id' => $row->NAMA, 'name' => $row->NAMA];
            }

            $lhkpn = $this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', null, $where2, 'NAMA_LENGKAP', "NAMA_LENGKAP LIKE '%$q%'", array('NAMA_LENGKAP', 'ASC'), null, '1');
            // echo $this->db->last_query();
            foreach ($lhkpn as $row) {
                $res[] = ['id' => $row->NAMA_LENGKAP, 'name' => $row->NAMA_LENGKAP];
            }

            $data = ['item' => $res];
            echo json_encode($data);
        } else {
            $id = urldecode($id);
            $where = ['IS_ACTIVE' => '1', 'ID_LHKPN' => $id_lhkpn, 'NAMA' => $id];
            $where2 = ['ID_LHKPN' => $id_lhkpn, 'NAMA_LENGKAP' => $id];
            $result = $this->mglobal->get_data_all('T_LHKPN_KELUARGA', null, $where, 'NAMA', null, null, null, '15');
            $lhkpn = $this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', null, $where2, 'NAMA_LENGKAP', null, null, null, '1');
            $res = [];
            if (count($result) <= 0 && count($lhkpn) <= 0) {
                $res[] = ['id' => $id, 'name' => $id];
            } elseif (count($result) > 0 && count($lhkpn) <= 0) {
                foreach ($result as $row) {
                    $res[] = ['id' => $row->NAMA, 'name' => $row->NAMA];
                }
            } elseif (count($result) <= 0 && count($lhkpn) > 0) {
                foreach ($lhkpn as $row) {
                    $res[] = ['id' => $row->NAMA_LENGKAP, 'name' => $row->NAMA_LENGKAP];
                }
            }

            echo json_encode($res);
        }
    }

    public function getNamaBank($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];

            $result = $this->mglobal->get_data_all('M_NAMA_BANK', null, NULL, '*', "NAMA_BANK LIKE '%$q%' OR AKRONIM_BANK LIKE '%$q%'", null, null, '15');

            foreach ($result as $row) {
                if ($row->AKRONIM_BANK) {
                    $res[] = ['id' => strtoupper($row->NAMA_BANK), 'name' => strtoupper($row->NAMA_BANK) . ' (' . strtoupper($row->AKRONIM_BANK) . ')'];
                } else {
                    $res[] = ['id' => strtoupper($row->NAMA_BANK), 'name' => strtoupper($row->NAMA_BANK)];
                }
            }
            $res[] = ['id' => $q, 'name' => $q];

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $id = urldecode($id);
            // $where = ['NAMA_BANK' => $id];
            $where = null;

            $result = $this->mglobal->get_data_all('M_NAMA_BANK', null, $where, '*', "NAMA_BANK LIKE '%$id%' OR AKRONIM_BANK LIKE '%$id%'", null, null, '15');

            if (count($result) <= 0) {
                $res[] = ['id' => $id, 'name' => $id];
            } else {
                $res = [];
                foreach ($result as $row) {
                    if ($row->AKRONIM_BANK) {
                        $res[] = ['id' => strtoupper($row->NAMA_BANK), 'name' => strtoupper($row->NAMA_BANK) . ' (' . strtoupper($row->AKRONIM_BANK) . ')'];
                    } else {
                        $res[] = ['id' => strtoupper($row->NAMA_BANK), 'name' => strtoupper($row->NAMA_BANK)];
                    }
                }
            }

            echo json_encode($res);
        }
    }

    public function getMerkMobil($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];

            $result = $this->mglobal->get_data_all('M_NAMA_MERKMOBIL', null, NULL, '*', "MERKMOBIL LIKE '%$q%' ", null, null, '15');

            foreach ($result as $row) {
                $res[] = ['id' => strtoupper($row->MERKMOBIL), 'name' => strtoupper($row->MERKMOBIL)];
            }
            $res[] = ['id' => $q, 'name' => $q];

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $id = urldecode($id);
            // $where = ['NAMA_BANK' => $id];
            $where = null;

            $result = $this->mglobal->get_data_all('M_NAMA_MERKMOBIL', null, $where, '*', "MERKMOBIL LIKE '%$id%' ", null, null, '15');

            if (count($result) <= 0) {
                $res[] = ['id' => $id, 'name' => $id];
            } else {
                $res = [];
                foreach ($result as $row) {
                    $res[] = ['id' => strtoupper($row->MERKMOBIL), 'name' => strtoupper($row->MERKMOBIL)];
                }
            }

            echo json_encode($res);
        }
    }

    public function getMerkMotor($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];

            $result = $this->mglobal->get_data_all('M_NAMA_MERKMOTOR', null, NULL, '*', "MERKMOTOR LIKE '%$q%' ", null, null, '15');

            foreach ($result as $row) {
                $res[] = ['id' => strtoupper($row->MERKMOTOR), 'name' => strtoupper($row->MERKMOTOR)];
            }
            $res[] = ['id' => $q, 'name' => $q];

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            $id = urldecode($id);
            // $where = ['NAMA_BANK' => $id];
            $where = null;

            $result = $this->mglobal->get_data_all('M_NAMA_MERKMOTOR', null, $where, '*', "MERKMOTOR LIKE '%$id%' ", null, null, '15');

            if (count($result) <= 0) {
                $res[] = ['id' => $id, 'name' => $id];
            } else {
                $res = [];
                foreach ($result as $row) {
                    $res[] = ['id' => strtoupper($row->MERKMOTOR), 'name' => strtoupper($row->MERKMOTOR)];
                }
            }

            echo json_encode($res);
        }
    }

    public function getMerkJenisKendaraan($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];
            $res[] = ['id' => $q, 'name' => $q];
            echo json_encode($res);
        } else {
            $id = urldecode($id);
            $res[] = ['id' => $id, 'name' => $id];
            echo json_encode($res);
        }
    }

    /**
     * Instansi Tujuan Surat
     *
     * @return json Instansi
     */
    public function getInstansiTujuan($id = NULL) {
        if (is_null($id)) {

            $not_using_default_value = $this->input->get('nudv');
            $setdefault_to_null = $this->input->get('setdefault_to_null');

            $q = $_GET['q'];
            $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('m_org', null, $where, 'ORG_KD, ORG_TIPE', "ORG_TIPE LIKE '%$q%'", array('ORG_TIPE', 'ASC'), null, '15');
            $res = [];
            // if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2") {
            //     $res[] = ['id' => '3122', 'name' => "KOMISI PEMBERANTASAN KORUPSI (KPK)"];
            // }
            if (!$not_using_default_value || $setdefault_to_null) {
                $res[] = $this->__getDefaultValueSelect2("-- Pilih Instansi --");
            }
            foreach ($result as $row) {
                $res[] = ['id' => $row->ORG_KD, 'name' => strtoupper($row->ORG_TIPE)];
            }
            $data = ['item' => $res];
            echo json_encode($data);
        } else {
          $where = ['IS_ACTIVE' => '1', 'ORG_KD' => $id];
          $result = $this->mglobal->get_data_all('m_org', null, $where, 'ORG_KD, ORG_TIPE', null, null, null, '15');
          $res = [];
          foreach ($result as $row) {
              $res[] = ['id' => $row->ORG_KD, 'name' => strtoupper($row->ORG_TIPE)];
          }
          echo json_encode($res);
        }
        exit;
    }
    public function getNamaInstansiTujuan($jenisInstansi = NULL, $id = NULL) {
        $res = [];

        // $set_default_null = $this->input->get('setdefault_to_null');
        // if ($jenisInstansi == "3122") {
        //     $res[] = ['id' => '12928', 'name' => "DEPUTI BIDANG PENCEGAHAN"];
        // }

        if (is_null($id)) {
            $res[] = ['id' => '', 'name' => "-- All --"];
            $q = $_GET['q'];

            $where = ['IS_ACTIVE' => '1', 'ORG_KD' => $jenisInstansi];

            $result = $this->mglobal->get_data_all('m_org_tujuan', null, $where , 'ORG_KD, ORG_TUJUANKD, ORG_NAMA', "ORG_NAMA LIKE '%$q%'", ['ORG_NAMA', 'asc'], null, null, null);

            // foreach ($result as $row) {
            //     if (($row->SUK_ID == 0 || $row->SUK_ID == '0') || ($row->ID_JAB == 0 || $row->ID_JAB == '0')){
            //         $res[] = ['id' => $row->UK_ID, 'name' => strtoupper('<html><body><strike>'.$row->UK_NAMA).'</strike></body></html>'];
            //     }else{
            //         $res[] = ['id' => $row->UK_ID, 'name' => strtoupper($row->UK_NAMA)];
            //     }
            // }
            foreach ($result as $row) {
                $res[] = ['id' => $row->ORG_TUJUANKD, 'name' => strtoupper($row->ORG_NAMA)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {
            // $where = ['m_org_tujuan.IS_ACTIVE' => '1', 'm_org_tujuan.ORG_TUJUANKD' => $id, 'ORG_KD' => $jenisInstansi];
            $where = ['IS_ACTIVE' => '1', 'ORG_TUJUANKD' => $id, 'ORG_KD' => $jenisInstansi];
            // $result = $this->mglobal->get_data_all('m_org_tujuan', NULL, $where , 'ORG_KD, ORG_TUJUANKD, ORG_NAMA', null, null, null, '15','m_org_tujuan.ORG_TUJUANKD, m_org_tujuan.ORG_NAMA');
            $result = $this->mglobal->get_data_all('m_org_tujuan', NULL, $where , 'ORG_KD, ORG_TUJUANKD, ORG_NAMA', null, null, null, null);

            // foreach ($result as $row) {
            //     if (($row->SUK_ID == 0 || $row->SUK_ID == '0') || ($row->ID_JAB == 0 || $row->ID_JAB == '0')){
            //         $res[] = ['id' => $row->UK_ID, 'name' => strtoupper('<html><body><strike>'.$row->UK_NAMA).'</strike></body></html>'];
            //     }else{
            //         $res[] = ['id' => $row->UK_ID, 'name' => strtoupper($row->UK_NAMA)];
            //     }
            // }
            foreach ($result as $row) {
                $res[] = ['id' => $row->ORG_TUJUANKD, 'name' => strtoupper($row->ORG_NAMA)];
            }

            echo json_encode($res);
        }
        exit;
    }
    /**
     * Penandatangan
     *
     * @return json
     */
    public function getPenandatangan($id = NULL) {
        if (is_null($id)) {

            $not_using_default_value = $this->input->get('nudv');
            $setdefault_to_null = $this->input->get('setdefault_to_null');

            $q = $_GET['q'];
            $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('m_surat_penandatangan', null, $where, 'PENANDATANGAN_ID, PENANDATANGAN_NAMA, PENANDATANGAN_JABATAN', "PENANDATANGAN_NAMA LIKE '%$q%'", array('PENANDATANGAN_NAMA', 'ASC'), null, '15');
            $res = [];
            // if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2") {
            //     $res[] = ['id' => '3122', 'name' => "KOMISI PEMBERANTASAN KORUPSI (KPK)"];
            // }
            if (!$not_using_default_value || $setdefault_to_null) {
                $res[] = $this->__getDefaultValueSelect2("-- Pilih --");
            }
            foreach ($result as $row) {
                $res[] = ['id' => $row->PENANDATANGAN_ID, 'name' => strtoupper($row->PENANDATANGAN_NAMA)];
            }
            $data = ['item' => $res];
            echo json_encode($data);
        } else {
          // $id = '2';
            $where = ['IS_ACTIVE' => '1', 'PENANDATANGAN_ID' => $id];

            // $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('m_surat_penandatangan', null, $where, 'PENANDATANGAN_ID, PENANDATANGAN_NAMA, PENANDATANGAN_JABATAN', null, null, null, '15');
            $res = [];
            // $res[] = ['id' => $id, 'name' => $nama];
            foreach ($result as $row) {
                $res[] = ['id' => $row->PENANDATANGAN_ID, 'name' => strtoupper($row->PENANDATANGAN_NAMA)];
            }
            // $res[0] = ['id' => $id, 'name' => $nama];
            echo json_encode($res);
        }
        exit;
    }

    /**
     * Instansi Tujuan Surat
     *
     * @return json Instansi
     */
    public function getNomorSuratTugas($idpemeriksa) {
        // if (is_null($idpemeriksa)) {
        //
        //     $not_using_default_value = $this->input->get('nudv');
        //     $setdefault_to_null = $this->input->get('setdefault_to_null');
        //
        //     $q = $_GET['q'];
        //     $where = ['ID_PEMERIKSA' => $idpemeriksa];
        //     $result = $this->mglobal->get_data_all('T_LHKPN_AUDIT', null, $where, 'ID_LHKPN, ID_PEMERIKSA, NOMOR_SURAT_TUGAS', "NOMOR_SURAT_TUGAS LIKE '%$q%'", array('NOMOR_SURAT_TUGAS', 'ASC'), null, '15');
        //     $res = [];
        //     // if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2") {
        //     //     $res[] = ['id' => '3122', 'name' => "KOMISI PEMBERANTASAN KORUPSI (KPK)"];
        //     // }
        //     if (!$not_using_default_value || $setdefault_to_null) {
        //         $res[] = $this->__getDefaultValueSelect2("-- Pilih Nomor ST --");
        //     }
        //     foreach ($result as $row) {
        //         $res[] = ['id' => $row->ID_LHKPN, 'name' => strtoupper($row->NOMOR_SURAT_TUGAS)];
        //     }
        //     $data = ['item' => $res];
        //     echo json_encode($data);
        // } else {
          $where = ['ID_PEMERIKSA' => $idpemeriksa];
          $result = $this->mglobal->get_data_all('T_LHKPN_AUDIT', null, $where, 'ID_LHKPN, ID_PEMERIKSA, NOMOR_SURAT_TUGAS', null, null, null, '15');
          $res = [];
          foreach ($result as $row) {
              $res[] = ['id' => $row->ID_LHKPN, 'name' => strtoupper($row->NOMOR_SURAT_TUGAS)];
          }
          // $data = ['item' => $res];
          // echo json_encode($data);
          echo json_encode($res);
        // }
        exit;
    }
    
    public function getUnitKerjaEaudit($id = NULL) {
        if (is_null($id)) {
            $q = $_GET['q'];


            $result = $this->mglobal->get_data_all('m_unit_kerja_eaudit', null, null, 'ID_UK_EAUDIT,NAMA_UK_EAUDIT', "NAMA_UK_EAUDIT LIKE '%$q%'", ['NAMA_UK_EAUDIT', 'ASC'], null, '14');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_UK_EAUDIT, 'name' => strtoupper($row->NAMA_UK_EAUDIT)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $where = ['ID_UK_EAUDIT' => $id,];


            $result = $this->mglobal->get_data_all('m_unit_kerja_eaudit', null, $where, 'ID_UK_EAUDIT, NAMA_UK_EAUDIT', null, null, null, '14');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_UK_EAUDIT, 'name' => strtoupper($row->NAMA_UK_EAUDIT)];
            }

            echo json_encode($res);
        }
    }
    
    public function getUnitKerjaNodin($id = NULL) {
        if (is_null($id)) {
            $not_using_default_value = $this->input->get('nudv');
            $setdefault_to_null = $this->input->get('setdefault_to_null');

            $q = $_GET['q'];
            $where = ['IS_ACTIVE' => '1'];

            $result = $this->mglobal->get_data_all('m_unit_kerja_eaudit', null, $where, 'ID_UK_EAUDIT,NAMA_UK_EAUDIT', "NAMA_UK_EAUDIT LIKE '%$q%'", ['NAMA_UK_EAUDIT', 'ASC'], null, '14');

            $res = [];
            if (!$not_using_default_value || $setdefault_to_null) {
                $res[] = $this->__getDefaultValueSelect2("-- All Tujuan --");
            }
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_UK_EAUDIT, 'name' => strtoupper($row->NAMA_UK_EAUDIT)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $where = ['IS_ACTIVE' => '1', 'ID_UK_EAUDIT' => $id,];

            $result = $this->mglobal->get_data_all('m_unit_kerja_eaudit', null, $where, 'ID_UK_EAUDIT, NAMA_UK_EAUDIT', null, null, null, '14');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->ID_UK_EAUDIT, 'name' => strtoupper($row->NAMA_UK_EAUDIT)];
            }

            echo json_encode($res);
        }
    }

    public function getNodin($id = NULL) {
        if (is_null($id)) {
            $not_using_default_value = $this->input->get('nudv');
            $setdefault_to_null = $this->input->get('setdefault_to_null');

            $q = $_GET['d'];
            $where = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('t_eaudit_nodin', null, $where, 'NOMOR_NOTA_DINAS', "NOMOR_NOTA_DINAS LIKE '%$q%'", ['NOMOR_NOTA_DINAS', 'ASC'], null, '14');

            $res = [];
            if (!$not_using_default_value || $setdefault_to_null) {
                $res[] = $this->__getDefaultValueSelect2("-- All Nodin --");
            }
            foreach ($result as $row) {
                $res[] = ['id' => $row->NOMOR_NOTA_DINAS, 'name' => strtoupper($row->NOMOR_NOTA_DINAS)];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        } else {

            $result = $this->mglobal->get_data_all('t_eaudit_nodin', null, null, 'NOMOR_NOTA_DINAS', null, null, null, '14');

            $res = [];
            foreach ($result as $row) {
                $res[] = ['id' => $row->NOMOR_NOTA_DINAS, 'name' => strtoupper($row->NOMOR_NOTA_DINAS)];
            }

            echo json_encode($res);
        }
    }

    public function getNomorND($no_surat = null) {
        $key = $no_surat ?: $this->input->get('key');
        $url = "https://eoffice-api-dev-app.kpk.go.id/api-system/v2/surat-keluar/find-lhkpn-mail?no_surat=" . $key;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_PROXY => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // "Content-Type: application/json",
                "x-access-id: 1535",
                "accept: application/json",
                "Authorization: Basic ZWxoa3BuOkpkOU5OaHBuMHlUcUF4MWxKb1FJ"
            ),
        ));
        $response = curl_exec($curl);
        $httprespon = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        if ($httprespon == 200 ) {
            $response = json_decode($response);
            $data = [];
            foreach ($response->data as $key => $value) {
                $data[] = [
                    'id' => $value->no_surat,
                    'text' => $value->no_surat,
                    'tanggal' => $value->tanggal_surat
                ];
            }
            echo json_encode($data);
        }
    }
}
