<?php

class Mt_pn_upload_xls_jabatan extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function sql_select() {
        $sql_select = "SELECT DISTINCT 
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
		CASE WHEN `x`.`id_jab_temp` IS NOT NULL THEN 'ada' ELSE NULL END as CEK";

        return $sql_select;
    }

    function sql_select_count() {
        $sql_select_count = "select count(distinct(`a`.ID_PN)) as JML ";
        return $sql_select_count;
    }

    function get_ver_pnwl_nonact_two_join() {
        $stringjoin = "JOIN `t_pn_jabatan` `b` ON `a`.`ID_PN` = `b`.`ID_PN` 
			   JOIN `m_jabatan` `c` ON `c`.`ID_JABATAN` = `b`.`ID_JABATAN` 
			   LEFT JOIN `m_sub_unit_kerja` `d` ON `d`.`SUK_ID` = `c`.`SUK_ID` 
			   LEFT JOIN `m_unit_kerja` `e` ON `e`.`UK_ID` = `c`.`UK_ID` 
			   LEFT JOIN `m_inst_satker` `f` ON `f`.`INST_SATKERKD` = `e`.`UK_LEMBAGA_ID` 
			   JOIN `t_pn_upload_xls_temp` `x` ON `x`.`NIK` = `a`.`NIK`";

        return $stringjoin;
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
    
    private function __get_static_where(){
        return " AND IS_PROCESSED = 0 AND b.IS_CURRENT = 1 AND b.ID_STATUS_AKHIR_JABAT IN (0,5) AND b.IS_ACTIVE = 1 AND b.tahun_wl = YEAR(NOW())";
    }

    function get_ver_pnwl_nonact_two_condition($distinct_nik, $pqry, $cari = NULL) {

        $stringcondition = "  ((NOT((TRIM(`c`.`NAMA_JABATAN`) LIKE CONVERT(`x`.`NAMA_JABATAN` USING utf8)))) ) $pqry ";
        
        $stringcondition .= $this->__get_static_where();

        if (!is_null($cari) && $cari != '') {
            $stringcondition .= " AND (`x`.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR `x`.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR `x`.NAMA_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%'))";
        }
		$cari_advance = $this->input->get('CARI');
		if($cari_advance)
		{
		 $stringcondition .= trim($cari_advance["INSTANSI"]) != '' && trim($cari_advance["INSTANSI"]) != '-' ? " and f.INST_SATKERKD=". $cari_advance["INSTANSI"]."  " : " and 1 ";
		 $stringcondition .= trim($cari_advance["UNIT_KERJA"]) != '' && trim($cari_advance["UNIT_KERJA"]) != '-' ? " and c.UK_ID=". $cari_advance["UNIT_KERJA"] ."": "  and 1";
		}
        return $stringcondition;
    }

    function select_count_nik_pn_upload_xls() {

        $sql = "SELECT COUNT(NIK) as JML FROM t_pn_upload_xls_temp";

        $q = $this->db->query($sql);

        if ($q) {
            $rs = $q->row();

            if ($rs) {

                return $rs->JML;
            }
        }

        return '0';
    }

    function get_ver_pnwl_jabatan_two($ins, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = true, $uk_id = FALSE) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');
        
        $uk_where = $id_uk && !is_null($id_uk) && trim($id_uk) != '-' ? "AND e.UK_ID = $id_uk " : " AND 1=1 ";
        $pqry = '  ';
        $distinct_nik = ' ';
        
        if($uk_id && $uk_id!="" && $uk_id!=NULL && trim($uk_id) != '-'){
            
            $uk_where .= " AND e.UK_ID = '".$uk_id."' ";
        }

        /**
         * ROle :
         *  1. Admin Aplikasi
         *  2. Admin KPK
         *  3. Admin Instansi
         */
        if (!is_null($ins) && $ins && $this->role = 2 && $this->role = 31)
            $pqry = "   AND b.LEMBAGA = $ins $uk_where";
        if (!is_null($ins) && $ins && $this->role > 2 && $this->role = 31)
            $pqry = "   AND b.LEMBAGA = $ins $uk_where";

        //        $qryins = $ins && !is_null($ins) ? " AND b.LEMBAGA = $ins" : "1";

        $qryins = $ins ? " ID_LEMBAGA = $ins " : "1 = 1";
        $sql_select = $this->sql_select();
        $sql_select_count = "select count(distinct(`a`.ID_PN)) as JML ";

        $sql_join = $this->get_ver_pnwl_nonact_two_join();


        //$distinct_nik = $this->select_distinct_nik_pn_upload_xls($qryins);

//        $jml_nik = $this->select_count_nik_pn_upload_xls();

        $sql_condition = " WHERE  " . $this->get_ver_pnwl_nonact_two_condition($distinct_nik, $pqry, $cari);
        $sql_from = " FROM `t_pn` `a` ";


        $sql_for_select_with_limit = $sql_select . $sql_from . $sql_join . $sql_condition . "  ORDER BY `a`.`NIK`   ";
        //echo $sql_for_select_with_limit;
        $sql_for_count_all_without_limit = $sql_select_count . $sql_from . $sql_join . $sql_condition;

        $strQuery = NULL;

        $limit = "   ";

        if ($limit_mode) {
            $limit = " LIMIT $offset , $rowperpage ";
        }
        $sql_for_select_with_limit .= $limit;
        
//        echo $sql_for_select_with_limit;exit;
        
        $strQuery = $this->db->query($sql_for_select_with_limit);

        $strQueryCount = $this->db->query($sql_for_count_all_without_limit);
        $resultCount = $strQueryCount->row();

        $result = $strQuery->result();
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

}

?>
    
