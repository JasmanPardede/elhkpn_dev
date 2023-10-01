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

    function save_penambahan_pnwl_ver($id) {
        $usr = $this->session->userdata('USR');
        $time = time();
        $ip = $_SERVER["REMOTE_ADDR"];
        $query = "
                    INSERT INTO t_pn (NIK, NIP_NRP, NAMA, EMAIL, NO_HP, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP) SELECT
                            NIK,
                            NIP_NRP,
                            NAMA, EMAIL, NO_HP, 1, $time, '$usr', '$ip'
                    FROM
                            t_pn_upload_xls_temp
                    WHERE
                            ID = $id";
        $query = $this->db->query($query);
        return $this->db->insert_id();
    }

    function save_penambahan_jab_pnwl_ver($id, $id_pn) {
        $usr = $this->session->userdata('USR');
        $time = time();
        $ip = $_SERVER["REMOTE_ADDR"];
        $query = "
                INSERT INTO t_pn_jabatan (ID_PN, DESKRIPSI_JABATAN, ID_JABATAN, ESELON, LEMBAGA, NAMA_LEMBAGA, UNIT_KERJA, SUB_UNIT_KERJA, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP) SELECT
                        $id_pn,
                        NAMA_JABATAN,
                        (SELECT ID_JABATAN FROM m_jabatan WHERE NAMA_JABATAN LIKE t_pn_upload_xls_temp.NAMA_JABATAN),
                        (SELECT KODE_ESELON FROM m_jabatan WHERE NAMA_JABATAN LIKE t_pn_upload_xls_temp.NAMA_JABATAN),
                        (SELECT INST_SATKERKD FROM m_inst_satker WHERE INST_NAMA = t_pn_upload_xls_temp.NAMA_LEMBAGA), 
                        NAMA_LEMBAGA,
                        NAMA_UNIT_KERJA,
                        NAMA_SUB_UNIT_KERJA, 1, $time, '$usr', '$ip'
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


        $pqry = NULL;
        if ($ins && $this->role > 2)
            $pqry = " AND b.LEMBAGA = $ins";

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
                    ((NOT((`c`.`NAMA_JABATAN` LIKE CONVERT(`x`.`NAMA_JABATAN` USING utf8)))) ) $pqry AND IS_PROCESSED = 0 AND b.IS_CURRENT = 1 AND b.ID_STATUS_AKHIR_JABAT = 0 AND b.IS_ACTIVE = 1
            ORDER BY
	`a`.`NIK`";
        $query = $this->db->query($query);

        return $query->num_rows();
    }
    
    function get_ver_pnwl_jabatan_two($ins, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');

        $pqry = NULL;
        if ($ins && $this->role > 2)
            $pqry = " AND b.LEMBAGA = $ins";

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
                    ((NOT((`c`.`NAMA_JABATAN` LIKE CONVERT(`x`.`NAMA_JABATAN` USING utf8)))) ) $pqry AND IS_PROCESSED = 0 AND b.IS_CURRENT = 1 AND b.ID_STATUS_AKHIR_JABAT = 0 AND b.IS_ACTIVE = 1
            ";
	
		if (!is_null($cari) && $cari != '') {
			$query .= " AND (`x`.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR `x`.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR `x`.NAMA_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%'))";
		}
		
		$query .= " ORDER BY `a`.`NIK`";
	
        $strQuery = NULL;
        if($limit_mode){
            $strQuery = $this->db->query($query,array($this->db->limit($rowperpage, $offset)));
        }else{
            $strQuery = $this->db->query($query);
        }
        return $strQuery;
    }

    function get_ver_pnwl_tambahan($ins) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');

        $pqry = '1';
        $qry_uk = NULL;
         if ($ins && $this->role > 2)
            $pqry = "ID_LEMBAGA = $ins";

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
                 WHERE upl.NIK NOT IN(SELECT NIK FROM t_pn WHERE $pqry ) AND upl.IS_PROCESSED = 0 AND $pqry $qry_uk";

        $query = $this->db->query($strSql);
        return $query->num_rows();
    }
    
    function get_ver_pnwl_tambahan_two($ins, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');

        $pqry = '1';
        $qry_uk = NULL;
         if ($ins && $this->role > 2)
            $pqry = "ID_LEMBAGA = $ins";

        if ($is_uk == 1)// cek user unit kerja
            $qry_uk = " AND (SELECT UK_ID FROM m_unit_kerja JOIN m_inst_satker ON INST_SATKERKD = UK_LEMBAGA_ID  WHERE UK_NAMA = t_pn_upload_xls_temp.NAMA_UNIT_KERJA AND INST_NAMA = t_pn_upload_xls_temp.NAMA_LEMBAGA LIMIT 1) = $id_uk";

        $query = "SELECT upl.*, 
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
                 WHERE upl.NIK NOT IN(SELECT NIK FROM t_pn WHERE $pqry ) AND upl.IS_PROCESSED = 0 AND $pqry $qry_uk";

		if (!is_null($cari) && $cari != '') {
			$query .= "AND (upl.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR upl.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR upl.NAMA_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%'))";
		}
				 
        $strQuery = NULL;
        if($limit_mode){
            $strQuery = $this->db->query($query,array($this->db->limit($rowperpage, $offset)));
        }else{
            $strQuery = $this->db->query($query);
        }
        return $strQuery;
    }

    function get_ver_pnwl_nonact($ins) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');
        $pqry = NULL;
        if ($is_uk == 1)
            $pqry = " AND c.UK_ID = $id_uk";
        
         if ($ins && $this->role > 2)
            $pqry .= " AND b.LEMBAGA = $ins ";

         $qryins = $ins ? "ID_LEMBAGA = $ins" : "1 = 1";
         
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
                    a.NIK NOT IN (SELECT NIK FROM t_pn_upload_xls_temp WHERE $qryins) AND ID_STATUS_AKHIR_JABAT = 0 AND IS_CALON = 0 AND IS_DELETED = 0 AND a.IS_ACTIVE = '1' AND b.IS_CURRENT = 1
             $pqry 
            ORDER BY
	`a`.`NAMA`";
        $query = $this->db->query($queryStr);

        return $query->num_rows();
    }

    function get_ver_pnwl_nonact_two($ins, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');
        
        $pqry = NULL;
        if ($is_uk == 1)
            $pqry = " AND c.UK_ID = $id_uk";
        
         if ($ins && $this->role > 2)
            $pqry .= " AND b.LEMBAGA = $ins";
			
         
         $qryins = $ins ? "ID_LEMBAGA = $ins" : "1 = 1";

        $query = "SELECT
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
                    a.NIK NOT IN (SELECT NIK FROM t_pn_upload_xls_temp WHERE $qryins) AND ID_STATUS_AKHIR_JABAT = 0 AND IS_CALON = 0 AND IS_DELETED = 0 AND a.IS_ACTIVE = '1' AND b.IS_CURRENT = 1
             $pqry ";
        
		if (!is_null($cari) && $cari != '') {
			$query .= " AND (`a`.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR `a`.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR `c`.NAMA_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%'))";
		}
		
		$query .= " ORDER BY `a`.`NIK`";
		
        $strQuery = NULL;
        if($limit_mode){
            $strQuery = $this->db->query($query,array($this->db->limit($rowperpage, $offset)));
        }else{
            $strQuery = $this->db->query($query);
        }
        return $strQuery;
    }

    function get_ver_pnwl_inst($id, $by) {
        if ($by && $this->role > 3)
            $this->db->where('CREATED_BY', $by);

        $this->db->where('ID_LEMBAGA', $id);
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    function get_jabatan_byNAME($nama) {
        $this->db->where('NAMA_JABATAN', $nama);
        $query = $this->db->get('m_jabatan');
        return $query->row();
    }

    function get_pn_byNIK($nik) {
        $this->db->where('NIK', $nik);
        $query = $this->db->get('t_pn');
        return $query->row();
    }

    function get_temp_uk_suk($int) {
        if ($int)
            $this->db->where('ID_LEMBAGA', $int);

        $query = $this->db->get($this->table);
        return $query->num_rows();
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
        $user_key = $ID_USER.''.$PASSWORD;

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
