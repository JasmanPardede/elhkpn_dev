<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Minstansi
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mt_pn_upload_xls_temp extends CI_Model {

    private $table = 't_pn_upload_xls_temp';

    function __construct() {
        parent::__construct();
        $this->role = $this->session->userdata('ID_ROLE');
    }

    function list_all() {
        $this->db->order_by('NIK', 'asc');
        return $this->db->get($this->table);
    }

    function save($data) {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }
    
    function check_nik_exist ($id){
        /**
         * check NIK if exist(s)
         */
        $query = $this->db->query("SELECT COUNT(ID_PN) NIK_FOUND FROM T_PN WHERE NIK = (SELECT NIK FROM t_pn_upload_xls_temp WHERE ID = '" . $id . "' LIMIT 1)");
        $record = $query->row();
        // unset($query, $record);
        // return FALSE;
        return $record->NIK_FOUND;
    }

    function save_penambahan_pnwl_ver($id) {


        /**
         * check NIK if exist(s)
         */
//        $query = $this->db->query("SELECT COUNT(ID_PN) NIK_FOUND FROM T_PN WHERE NIK = (SELECT NIK FROM t_pn_upload_xls_temp WHERE ID = '" . $id . "' LIMIT 1)");

//        $record = $query->row();
//        if ($record && $record->NIK_FOUND <= 0) {
            $usr = $this->session->userdata('USR');
            $time = time();

            $ip = $_SERVER["REMOTE_ADDR"];
            $query = "
                    INSERT INTO t_pn (NIK, NIP_NRP, NAMA, EMAIL, JNS_KEL, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP,TEMPAT_LAHIR,TGL_LAHIR) SELECT
                            NIK,
                            NIP_NRP,
                            NAMA, EMAIL,IF(
                                            JNS_KEL = 'Laki-laki',
                                            '1',
                                            '2'
                                          ) AS JNS_KEL, 1, $time, '$usr', '$ip',TEMPAT_LAHIR,TGL_LAHIR
                    FROM
                            t_pn_upload_xls_temp
                    WHERE
                            ID = $id";
            $query = $this->db->query($query);
            return $this->db->insert_id();
//        }
//        unset($query, $record);
//        return FALSE;
    }

    function save_penambahan_jab_pnwl_ver($id, $id_pn) {
        $usr = $this->session->userdata('USR');
        $time = time();
        $ip = $_SERVER["REMOTE_ADDR"];
        $query = "
                INSERT INTO t_pn_jabatan (ID_PN, DESKRIPSI_JABATAN, ID_JABATAN, ESELON, LEMBAGA, NAMA_LEMBAGA, UNIT_KERJA, SUB_UNIT_KERJA, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP,IS_WL, TAHUN_WL, ID_STATUS_AKHIR_JABAT) 
                    SELECT
                        $id_pn,
                        NAMA_JABATAN,
                        (
                        SELECT
                            m_jabatan.ID_JABATAN
                          FROM
                            m_jabatan
                          JOIN m_unit_kerja ON m_unit_kerja.UK_ID = m_jabatan.UK_ID
                          JOIN m_sub_unit_kerja ON m_sub_unit_kerja.SUK_ID = m_jabatan.SUK_ID
                          WHERE NAMA_JABATAN = t_pn_upload_xls_temp.NAMA_JABATAN AND 
                                m_jabatan.inst_satkerkd = t_pn_upload_xls_temp.ID_LEMBAGA AND 
                                m_jabatan.`IS_ACTIVE` = 1 AND 
                                m_unit_kerja.UK_NAMA = t_pn_upload_xls_temp.NAMA_UNIT_KERJA AND 
                                m_sub_unit_kerja.SUK_NAMA = t_pn_upload_xls_temp.NAMA_SUB_UNIT_KERJA

                          ),
                          (
                        SELECT
                            m_jabatan.KODE_ESELON
                          FROM
                            m_jabatan
                          JOIN m_unit_kerja ON m_unit_kerja.UK_ID = m_jabatan.UK_ID
                          JOIN m_sub_unit_kerja ON m_sub_unit_kerja.SUK_ID = m_jabatan.SUK_ID
                          WHERE NAMA_JABATAN = t_pn_upload_xls_temp.NAMA_JABATAN AND 
                                m_jabatan.inst_satkerkd = t_pn_upload_xls_temp.ID_LEMBAGA AND 
                                m_jabatan.`IS_ACTIVE` = 1 AND 
                                m_unit_kerja.UK_NAMA = t_pn_upload_xls_temp.NAMA_UNIT_KERJA AND 
                                m_sub_unit_kerja.SUK_NAMA = t_pn_upload_xls_temp.NAMA_SUB_UNIT_KERJA

                          ),
                          t_pn_upload_xls_temp.ID_LEMBAGA,
                        NAMA_LEMBAGA,
                        NAMA_UNIT_KERJA,
                        NAMA_SUB_UNIT_KERJA, 1, $time, '$usr', '$ip', 1, '" . date('Y') . "',5
                FROM
                        t_pn_upload_xls_temp
                WHERE
                        ID = $id";
        $query = $this->db->query($query);
        return $this->db->insert_id();
    }

    function save_penambahan_user_pnwl_ver($id, $pass) {
        $password = sha1(md5($pass));
        $usr = $this->session->userdata('USR');
        $time = time();
        $ip = $_SERVER["REMOTE_ADDR"];
        $query = "
                    INSERT INTO t_user (USERNAME, NAMA, NIP, EMAIL, IS_ACTIVE, ID_ROLE, INST_SATKERKD, CREATED_TIME, CREATED_BY, CREATED_IP, PASSWORD )
                    SELECT  NIK, NAMA, NIP_NRP, EMAIL, 0, 5, (SELECT INST_SATKERKD FROM m_inst_satker WHERE INST_NAMA = t_pn_upload_xls_temp.NAMA_LEMBAGA), $time, '$usr', '$ip', '$password'
                    FROM
                        t_pn_upload_xls_temp
                    WHERE
                        ID = $id ";
        $query = $this->db->query($query);
        return $this->db->insert_id();
    }

    function update($id, $data) {
        $this->db->where('ID', $id);
        $this->db->update($this->table, $data);
    }

    function deleteByNIK($nik) {
        $this->db->where('NIK', $id);
        $this->db->delete($this->table);
    }

    function delete($id) {
        $this->db->where('ID', $id);
        $this->db->delete($this->table);
    }

    function deletedata($id) {
        $this->db->where('ID_LEMBAGA', $id);
        $this->db->delete($this->table);
    }

    function delete_instansi($id, $by) {//delete temp xls
        if ($by)
            $this->db->where('CREATED_BY', $by);
        $this->db->where('ID_LEMBAGA', $id);
        $this->db->delete($this->table);
    }

    function get_temp_excel($id) {
        $this->db->where('ID', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    /* Untuk Menampilkan Data Upload Via Excel
     * create : April 15 2016
     */

    function get_ver_pnwl_jabatan($ins) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');

        //$ins = $this->get_instansi_daftar_pn_wl_via_excel();

        $pqry = '1';
//        if (!is_null($ins) && $ins && $this->role > 2)
        if (!is_null($ins) && $ins && $this->role == 2 && $this->role == 31)
//            $pqry = " AND b.LEMBAGA = $ins";
            $qryins = $ins && !is_null($ins) ? "AND b.LEMBAGA = $ins" : "AND b.LEMBAGA = 1";

        if ($is_uk == 1)
            $pqry .= " AND c.UK_ID = $id_uk";

        $query = "SELECT
		`x`.`ID` AS `ID`,
		`a`.`NIK` AS `NIK`,
		`a`.`GELAR_DEPAN` AS `GELAR_DEPAN`,
		`a`.`NAMA` AS `NAMA`,
		`x`.`NAMA` AS `NAMA_TEMP`,
		`a`.`GELAR_BELAKANG` AS `GELAR_BELAKANG`,
		`f`.`INST_SATKERKD` AS `INST_SATKERKD`,
		`c`.`NAMA_JABATAN` AS `NAMA_JABATAN`,
		`x`.`NAMA_JABATAN` AS `NAMA_JABATAN_TEMP`,
		`d`.`SUK_NAMA` AS `SUK_NAMA`,
		`x`.`NAMA_SUB_UNIT_KERJA` AS `SUK_NAMA_TEMP`,
		`e`.`UK_NAMA` AS `UK_NAMA`,
		`x`.`NAMA_UNIT_KERJA` AS `UK_NAMA_TEMP`,
		`f`.`INST_NAMA` AS `INST_NAMA`,
		`x`.`NAMA_LEMBAGA` AS `INST_NAMA_TEMP`,
		`a`.`JNS_KEL` AS `JNS_KEL`,
		`a`.`TEMPAT_LAHIR` AS `TEMPAT_LAHIR`,
		`a`.`TGL_LAHIR` AS `TGL_LAHIR`,
		`a`.`NO_HP` AS `NO_HP`,
		`a`.`EMAIL` AS `EMAIL`,
		(SELECT 'ada' FROM m_jabatan mjab JOIN m_unit_kerja muk ON muk.UK_ID = mjab.UK_ID LEFT JOIN m_sub_unit_kerja msuk ON msuk.SUK_ID = mjab.SUK_ID  
		WHERE 
		TRIM(CONCAT(mjab.NAMA_JABATAN,IFNULL('-',msuk.SUK_NAMA),muk.UK_NAMA)) = TRIM(CONCAT(x.NAMA_JABATAN,IFNULL('-',x.NAMA_SUB_UNIT_KERJA),x.NAMA_UNIT_KERJA)) LIMIT 1) AS CEK 
		FROM
				((((((`t_pn` `a` JOIN `t_pn_jabatan` `b` ON((`a`.`ID_PN` = `b`.`ID_PN`))) 
		   JOIN `m_jabatan` `c` ON((`c`.`ID_JABATAN` = `b`.`ID_JABATAN`))) 
		   LEFT JOIN `m_sub_unit_kerja` `d` ON((`d`.`SUK_ID` = `c`.`SUK_ID`))) 
		   LEFT JOIN `m_unit_kerja` `e` ON((`e`.`UK_ID` = `c`.`UK_ID`))) 
		   LEFT JOIN `m_inst_satker` `f` ON((`f`.`INST_SATKERKD` = `e`.`UK_LEMBAGA_ID`))) 
		   JOIN `t_pn_upload_xls_temp` `x` ON((`x`.`NIK` = `a`.`NIK`)))
		WHERE
				((NOT((`c`.`NAMA_JABATAN` LIKE CONVERT(`x`.`NAMA_JABATAN` USING utf8)))) ) $qryins AND IS_PROCESSED = 0 AND b.IS_CURRENT = 1 AND b.ID_STATUS_AKHIR_JABAT = 0 AND b.IS_ACTIVE = 1
		ORDER BY
		`a`.`NIK`";
        $query = $this->db->query($query);
        //echo $this->db->last_query();//exit();
        return $query->result();
    }

    function get_ver_pnwl_jabatan_two($ins, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = true) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');
        //$ins = $this->session->userdata('INST_SATKERKD');
        $pqry = '1';

        if (!is_null($ins) && $ins && $this->role == 2 && $this->role == 31)
            $pqry = " AND b.LEMBAGA = $ins ";
//        if (!is_null($ins) && $ins && $this->role > 2)
        if (!is_null($ins) && $ins && $this->role == 2 && $this->role == 31)
            $pqry = " AND b.LEMBAGA = $ins ";
        //$qryins = $ins && !is_null($ins) ? "AND b.LEMBAGA = $ins" : "AND b.LEMBAGA = 1";

        if ($is_uk == 1)
            $pqry .= " AND c.UK_ID = $id_uk";

        $query = "SELECT
		`x`.`ID` AS `ID`,
		`a`.`NIK` AS `NIK`,
		`a`.`ID_PN` AS `ID_PN`,
		`a`.`GELAR_DEPAN` AS `GELAR_DEPAN`,
		`a`.`NAMA` AS `NAMA`,
		`x`.`NAMA` AS `NAMA_TEMP`,
		`a`.`GELAR_BELAKANG` AS `GELAR_BELAKANG`,
		`f`.`INST_SATKERKD` AS `INST_SATKERKD`,
		`c`.`NAMA_JABATAN` AS `NAMA_JABATAN`,
		`x`.`NAMA_JABATAN` AS `NAMA_JABATAN_TEMP`,
		`d`.`SUK_NAMA` AS `SUK_NAMA`,
		`x`.`NAMA_SUB_UNIT_KERJA` AS `SUK_NAMA_TEMP`,
		`e`.`UK_NAMA` AS `UK_NAMA`,
		`x`.`NAMA_UNIT_KERJA` AS `UK_NAMA_TEMP`,
		`f`.`INST_NAMA` AS `INST_NAMA`,
		`x`.`NAMA_LEMBAGA` AS `INST_NAMA_TEMP`,
		`a`.`JNS_KEL` AS `JNS_KEL`,
		`a`.`TEMPAT_LAHIR` AS `TEMPAT_LAHIR`,
		`a`.`TGL_LAHIR` AS `TGL_LAHIR`,
		`a`.`NO_HP` AS `NO_HP`,
		`a`.`EMAIL` AS `EMAIL`,
                CASE WHEN `x`.`id_jab_temp` IS NOT NULL THEN 'ada' ELSE NULL END as CEK
		FROM
				((((((`t_pn` `a` JOIN `t_pn_jabatan` `b` ON((`a`.`ID_PN` = `b`.`ID_PN`))) 
		   JOIN `m_jabatan` `c` ON((`c`.`ID_JABATAN` = `b`.`ID_JABATAN`))) 
		   LEFT JOIN `m_sub_unit_kerja` `d` ON((`d`.`SUK_ID` = `c`.`SUK_ID`))) 
		   LEFT JOIN `m_unit_kerja` `e` ON((`e`.`UK_ID` = `c`.`UK_ID`))) 
		   LEFT JOIN `m_inst_satker` `f` ON((`f`.`INST_SATKERKD` = `e`.`UK_LEMBAGA_ID`))) 
		   JOIN `t_pn_upload_xls_temp` `x` ON((`x`.`NIK` = `a`.`NIK`)))
		WHERE
				((NOT((`c`.`NAMA_JABATAN` LIKE CONVERT(`x`.`NAMA_JABATAN` USING utf8)))) ) $pqry AND IS_PROCESSED = 0 AND b.IS_CURRENT = 1 AND b.ID_STATUS_AKHIR_JABAT = 0 AND b.IS_ACTIVE = 1
		";

        if (!is_null($cari) && $cari != '') {
            $query .= " AND (`x`.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR `x`.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR `x`.NAMA_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%'))";
        }

        $query .= " ORDER BY `a`.`NIK`";
        //echo $query;exit;

        $strQuery = NULL;
        if ($limit_mode) {
            $strQuery = $this->db->query($query, array($this->db->limit($rowperpage, $offset)));
        } else {
            $strQuery = $this->db->query($query);
        }
        //echo $this->db->last_query();exit();
        $result = $strQuery->result();
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return $strQuery;
    }

    function get_ver_pnwl_tambahan($ins) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');

        $pqry = '1';
        $pqry_inst = '1';
        $qry_uk = NULL;
//        if ($ins && !is_null($ins) && $this->role > 2) {
        if ($ins && !is_null($ins) && $this->role == 2 && $this->role == 31) {
            $pqry = "ID_LEMBAGA = $ins";
            $pqry_inst = "pn_j.LEMBAGA = $ins";
        }

        if ($is_uk == 1)// cek user unit kerja
            $qry_uk = " AND (SELECT UK_ID FROM m_unit_kerja JOIN m_inst_satker ON INST_SATKERKD = UK_LEMBAGA_ID  WHERE UK_NAMA = upl.NAMA_UNIT_KERJA AND INST_NAMA = upl.NAMA_LEMBAGA LIMIT 1) = $id_uk";

        $strSql = "SELECT upl.*, 
                 (
		SELECT
			'ada'
		FROM
			m_jabatan mjab
		JOIN m_unit_kerja muk ON muk.UK_ID = mjab.UK_ID
		LEFT JOIN m_sub_unit_kerja msuk ON msuk.SUK_ID = mjab.SUK_ID
		WHERE
			TRIM(CONCAT(mjab.NAMA_JABATAN, IFNULL('-',msuk.SUK_NAMA), muk.UK_NAMA)) = TRIM(CONCAT(upl.NAMA_JABATAN, IFNULL('-',upl.NAMA_SUB_UNIT_KERJA), upl.NAMA_UNIT_KERJA))
		LIMIT 1
                ) AS CEK
                 FROM t_pn_upload_xls_temp upl
                 WHERE upl.NIK NOT IN(SELECT DISTINCT(NIK) FROM t_pn pn left join t_pn_jabatan pn_j on pn_j.ID_PN = pn.ID_PN WHERE $pqry_inst ) AND upl.IS_PROCESSED = 0 AND $pqry $qry_uk ";

        $query = $this->db->query($strSql);
//        echo $this->db->last_query();exit();
        return $query->result();
    }

    function get_ver_pnwl_tambahan_two($ins, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false, $unit_kerja = NULL) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');

        $pqry = '1';
        $pqry_inst = '1';
        $qry_uk = NULL;
//        if ($ins && $this->role > 2)
        if ($ins && $this->role == 2 && $this->role == 31)
            $pqry = "ID_LEMBAGA = $ins";
        $pqry_inst = "pn_j.LEMBAGA = $ins";
        if ($unit_kerja == NULL) {
            if ($is_uk == 1) {// cek user unit kerja
                $qry_uk = " AND (SELECT UK_ID FROM m_unit_kerja JOIN m_inst_satker ON INST_SATKERKD = UK_LEMBAGA_ID  WHERE UK_NAMA = t_pn_upload_xls_temp.NAMA_UNIT_KERJA AND INST_NAMA = t_pn_upload_xls_temp.NAMA_LEMBAGA LIMIT 1) = '" . $id_uk . "'";
            } else {
                $qry_uk = " AND (SELECT UK_ID FROM m_unit_kerja JOIN m_inst_satker ON INST_SATKERKD = UK_LEMBAGA_ID  WHERE UK_NAMA = t_pn_upload_xls_temp.NAMA_UNIT_KERJA AND INST_NAMA = t_pn_upload_xls_temp.NAMA_LEMBAGA LIMIT 1) = '" . $unit_kerja . "'";
            }
        }
        $query = "SELECT upl.*, 
                 (
		CASE WHEN upl.id_jab_temp IS NOT NULL THEN 'ada' ELSE NULL END) as CEK
                 FROM t_pn_upload_xls_temp upl
                 WHERE upl.NIK NOT IN(SELECT DISTINCT(NIK) FROM t_pn pn left join t_pn_jabatan pn_j on pn_j.ID_PN = pn.ID_PN WHERE $pqry_inst ) AND upl.IS_PROCESSED = 0 AND $pqry $qry_uk";

        if (!is_null($cari) && $cari != '') {
            $query .= "AND (upl.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR upl.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR upl.NAMA_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%'))";
        }

        $strQuery = NULL;
        if ($limit_mode) {
            $strQuery = $this->db->query($query, array($this->db->limit($rowperpage, $offset)));
        } else {
            $strQuery = $this->db->query($query);
        }
        $result = $strQuery->result();
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
//        echo $this->db->last_query();exit();
        return $strQuery;
    }

    function get_ver_pnwl_nonact($ins) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');

        //$ins = $this->get_instansi_daftar_pn_wl_via_excel();
        $pqry = '';
        if ($is_uk == 1)
            $pqry = " AND c.UK_ID = $id_uk";

//        if (!is_null($ins) && $ins && $this->role > 2)
        if (!is_null($ins) && $ins && $this->role == 2 && $this->role == 31)
            $pqry .= " AND b.LEMBAGA = $ins ";

        $qryins = $ins && !is_null($ins) ? "b.LEMBAGA = $ins" : "1";

        $queryStr = "SELECT
		`a`.ID_PN, b.ID AS ID_PN_JAB, 
		`a`.`NIK` AS `NIK`,
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
		`a`.`EMAIL` AS `EMAIL`
            FROM
                    ((((((`t_pn` `a` JOIN `t_pn_jabatan` `b` ON((`a`.`ID_PN` = `b`.`ID_PN`))) 
					JOIN `m_jabatan` `c` ON((`c`.`ID_JABATAN` = `b`.`ID_JABATAN`))) 
					LEFT JOIN `m_sub_unit_kerja` `d` ON((`d`.`SUK_ID` = `c`.`SUK_ID`))) 
					LEFT JOIN `m_unit_kerja` `e` ON((`e`.`UK_ID` = `c`.`UK_ID`))) 
					LEFT JOIN `m_inst_satker` `f` ON((`f`.`INST_SATKERKD` = `e`.`UK_LEMBAGA_ID`))))
            WHERE
                    a.NIK NOT IN (SELECT DISTINCT(NIK) FROM t_pn_upload_xls_temp WHERE $qryins) AND ID_STATUS_AKHIR_JABAT = 0 AND IS_CALON = 0 AND IS_DELETED = 0 AND a.IS_ACTIVE = 1 AND b.IS_CURRENT = 1
             $pqry 
            ORDER BY
	`a`.`NAMA`";
        $query = $this->db->query($queryStr);
        //echo $this->db->last_query();exit();
        return $query->result();
    }

    function select_distinct_nik_pn_upload_xls($qryins) {
        $sql = "SELECT DISTINCT(NIK) FROM t_pn_upload_xls_temp WHERE " . $qryins;

        $q = $this->db->query($sql);

        $where_in = [];

        if ($q) {
            $rs = $q->result();

            if ($rs) {

                foreach ($rs as $r) {
                    $where_in[] = $r->NIK;
                }
            }
        }

        if (count($where_in) > 0) {
            return "'" . implode("','", $where_in) . "'";
        }
        return "''";
    }

    function select_count_nik_pn_upload_xls($condition = " 1 = 1 ") {

        $sql = "SELECT COUNT(NIK) as JML FROM t_pn_upload_xls_temp WHERE " . $condition;

        $q = $this->db->query($sql);

        if ($q) {
            $rs = $q->row();

            if ($rs) {

                return $rs->JML;
            }
        }

        return '0';
    }

    function get_ver_pnwl_nonact_two_condition($distinct_nik, $jml_nik, $pqry, $cari) {
        if (!is_null($cari) && $cari != '') {
            $cari = " AND (`a`.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR `a`.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR `c`.NAMA_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%'))";
        }

        $nik_not_in_temp_xls = " 0=1 ";
        if ($distinct_nik != "''") {
            $nik_not_in_temp_xls = " a.NIK NOT IN (" . $distinct_nik . ") ";
        }
        $stringcondition = $nik_not_in_temp_xls . " AND ID_STATUS_AKHIR_JABAT IN (0, 5) AND IS_WL = 1 AND IS_CALON = 0 AND IS_DELETED = 0 AND a.IS_ACTIVE = 1 AND b.IS_CURRENT = 1 " .
                $pqry . " AND (" . $jml_nik . ") <> '' " . $cari;

        return $stringcondition;
    }

    function get_ver_pnwl_nonact_two_join() {
        $stringjoin = 'JOIN `t_pn_jabatan` `b` ON`a`.`ID_PN` = `b`.`ID_PN`
			JOIN `m_jabatan` `c` ON `c`.`ID_JABATAN` = `b`.`ID_JABATAN` 
			LEFT JOIN `m_sub_unit_kerja` `d` ON `d`.`SUK_ID` = `c`.`SUK_ID` 
			LEFT JOIN `m_unit_kerja` `e` ON `e`.`UK_ID` = `c`.`UK_ID`
			LEFT JOIN `m_inst_satker` `f` ON `f`.`INST_SATKERKD` = `e`.`UK_LEMBAGA_ID`';

        return $stringjoin;
    }

    function get_ver_pnwl_nonact_two($ins, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = TRUE, $param_id_uk = FALSE) {

        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');
        $id_suk = $this->session->userdata('SUK_ID');

        if ($param_id_uk) {
            $id_uk = $param_id_uk;
            $is_uk = TRUE;
        }

        $pqry = NULL;
        $cari_advance = $this->input->get('CARI');
        if ($cari_advance) {
            $instan = $cari_advance["INSTANSI"] != '' ? TRUE : FALSE;


            if ($instan) {
                $pqry .= $cari_advance["INSTANSI"] != '' ? " and b.LEMBAGA=" . $cari_advance["INSTANSI"] . "  " : " and 1 ";
            } elseif ($ins != "") {
                $pqry .= " and b.LEMBAGA=" . $ins;
            }
            /* Update Baru */
            $UKID = $cari_advance["UNIT_KERJA"] != '' ? TRUE : FALSE;
            if ($UKID) {
                $pqry .= $cari_advance["UNIT_KERJA"] != '-' ? " and c.UK_ID=" . $cari_advance["UNIT_KERJA"] . "" : "  and 1";
            }
            /* Sampek doank */
        }
        if (!is_null($id_suk)) {
            $pqry .= " AND c.SUK_ID = $id_suk ";
        }
        $arrqryins = [];
        $arrqryins[] = $ins ? " ID_LEMBAGA = '" . $ins . "' " : " 1 = 1 ";
//        $arrqryins = $ins ? " ID_LEMBAGA = '" . $ins . "' " : " 1 = 1 ";
        $UKID = $cari_advance["UNIT_KERJA"] == '-' ? TRUE : FALSE;
        if ($UKID) {
            $arrqryins[] = $is_uk ? " upload_as_instansi = '1' " : " upload_as_instansi = '1'";
        } else {
            $arrqryins[] = $is_uk ? " uk_id_detected = '" . $id_uk . "' " : " upload_as_instansi = '1' ";
        }


        $qryins = implode(" AND ", $arrqryins);
//        $qryins = $arrqryins;
        /**
         * compose query for select and count;
         */
        $sql_select = 'SELECT
		`a`.ID_PN, b.ID AS ID_PN_JAB, 
		`a`.`NIK` AS `NIK`,
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
		`a`.`EMAIL` AS `EMAIL`';

        $sql_select_count = "select count(`a`.ID_PN) as JML ";

        $sql_join = $this->get_ver_pnwl_nonact_two_join();

        $distinct_nik = $this->select_distinct_nik_pn_upload_xls($qryins);
        //echo $this->db->last_query();
        $jml_nik = $this->select_count_nik_pn_upload_xls($qryins);
        //echo $this->db->last_query();

        $sql_condition = " WHERE  " . $this->get_ver_pnwl_nonact_two_condition($distinct_nik, $jml_nik, $pqry, $cari);
        $sql_from = " FROM `t_pn` `a` ";


        $sql_for_select_with_limit = $sql_select . $sql_from . $sql_join . $sql_condition . "  ORDER BY `a`.`NIK`   ";
        $sql_for_count_all_without_limit = $sql_select_count . $sql_from . $sql_join . $sql_condition;

        $strQuery = NULL;
        $limit = "   ";
        if ($limit_mode) {
            $limit = " LIMIT $offset , $rowperpage ";
        }
        $sql_for_select_with_limit .= $limit;
//        echo $sql_for_select_with_limit;exit;
        $strQuery = $this->db->query($sql_for_select_with_limit);
//        echo $this->db->last_query();exit;
        $result = $strQuery->result();
        $strQueryCount = $this->db->query($sql_for_count_all_without_limit);
//        echo $this->db->last_query();exit;
        $resultCount = $strQueryCount->row();

//        echo $this->db->last_query();exit();
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($resultCount->JML),
            "iTotalDisplayRecords" => intval($resultCount->JML),
            "aaData" => $result
        );


        return $dtable_output;
    }

    function get_ver_pnwl_inst($id, $by) {
        if ($by && $this->role > 3)
            $this->db->where('CREATED_BY', $by);

        $this->db->where('ID_LEMBAGA', $id);
        $query = $this->db->get($this->table);
        //echo $this->db->last_query();
        return $query->result();
    }

    function get_jabatan_byNAME($nama, $record_temp_excel = FALSE) {
        if($record_temp_excel){
            $condition = "m_jabatan.inst_satkerkd = '".$record_temp_excel->ID_LEMBAGA."' AND "
                    . "m_unit_kerja.uk_nama = '".$record_temp_excel->NAMA_UNIT_KERJA."' AND "
                    . "m_sub_unit_kerja.suk_nama = '".$record_temp_excel->NAMA_SUB_UNIT_KERJA."'";
            
            $this->db->where($condition);
        }
        
        $this->db->join('m_unit_kerja', 'm_unit_kerja.uk_id = m_jabatan.uk_id');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.suk_id = m_jabatan.suk_id');
        $this->db->where("m_jabatan.IS_ACTIVE = '1'");
        $this->db->where('NAMA_JABATAN', $nama);
        $query = $this->db->get('m_jabatan');
        return $query->row();
    }

    function get_pn_byNIK($nik) {
        $this->db->join('t_pn_jabatan', 't_pn_jabatan.id_pn = t_pn.id_pn');
        $this->db->where('NIK', $nik);
        $query = $this->db->get('t_pn');
        return $query->row();
    }

    function get_temp_uk_suk($int) {
        if ($int)
            $this->db->where('ID_LEMBAGA', $int);

        $query = $this->db->get($this->table);
        return $query->result();
    }

    //    For Email
    function get_EmailUserUK($INS) {
        $query = "SELECT DISTINCT  B.EMAIL, UK_ID , B.ID_USER,B.USERNAME FROM t_pn_upload_xls_temp AS A
                  INNER JOIN t_user AS B ON B.USERNAME = A.CREATED_BY
                  WHERE A.ID_LEMBAGA = $INS";
        $query = $this->db->query($query);
        return $query->result();
    }

    function get_DataTempIns($INS, $U_NAME) {
        $query = "SELECT A.NIK, A.NIP_NRP, A.NAMA, A.NAMA_LEMBAGA, A.NAMA_UNIT_KERJA, A.NAMA_SUB_UNIT_KERJA, A.NAMA_JABATAN, A.EMAIL, A.ID_LEMBAGA,
                    IF(A.JENIS_PROSES = 1, 'Approved',IF(A.JENIS_PROSES = 2, 'Edit',IF(A.JENIS_PROSES = 3, 'Cancel', 'Unverified') ) ) AS STS
                    FROM t_pn_upload_xls_temp AS A
                    WHERE A.ID_LEMBAGA = $INS AND  A.CREATED_BY = '$U_NAME' AND A.IS_PROCESSED  = 1
                    ORDER BY A.NAMA_UNIT_KERJA, A.NAMA_SUB_UNIT_KERJA, A.NAMA_JABATAN, A.NAMA 
                    ";
        $query = $this->db->query($query);
        return $query->result();
    }

    function kirim_info_pn($id, $password) {
        $subject = 'Aktivasi e-lhkpn';
        $data_temp = $this->get_temp_excel($id);

        $this->db->where('USERNAME', $data_temp->NIK);
        $this->db->limit(1);
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD');
        $user = $this->db->get('t_user')->row();

        $ID_USER = sha1($user->ID_USER);
        $PASSWORD = $user->PASSWORD;
        $user_key = $ID_USER . '' . $PASSWORD;

        $message = '



            <table style="margin-top:2%;"> 
                <tr>
                    <td><strong>Yth Sdr ' . strtoupper($data_temp->NAMA) . '</strong></td>
                </tr>
                <tr>
                    <td><strong>' . strtoupper($data_temp->NAMA_LEMBAGA) . '</strong></td>
                </tr>
                <tr>
                    <td><strong>Di Tempat</strong></td>
                </tr>
            </table>

            <table style="margin-top:2%;"> 
                <tr>
                    <td>Selamat, dan terima kasih anda telah terdaftar di Aplikasi e-LHKPN KPK dengan account: </td>
                </tr>
            </table>

            <table style="margin-top:2%; border:1px solid #666; border-collapse: collapse; table-layout:fixed;" cellpadding="5" border="1"> 
                <tr style="background-color:#32CD32; color:#333;">
                    <td>Username</td>
                    <td>:</td>
                    <td>' . $data_temp->NIK . '</td>
                </tr>
                <tr style="background-color:#32CD32; color:#333;">
                    <td>Password</td>
                    <td>:</td>
                    <td>' . $password . '</td>
                </tr>
            </table>

            <table style="margin-top:2%;"> 
                 <tr>
                    <td>Silahkan klik tautan dibawah ini untuk mengaktifkan account anda <a style="color:green; font-weight:bold;" href="' . base_url() . 'portal/user/login?' . $user_key . '">' . base_url() . 'portal/user/login?' . $user_key . '</a></td>
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


        return ng::mail_send($data_temp->EMAIL, $subject, $message);
    }

}
