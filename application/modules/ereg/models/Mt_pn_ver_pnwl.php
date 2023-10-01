<?php

class Mt_pn_ver_pnwl extends CI_Model {

    private $table = 't_pn_upload_xls_temp';

    function __construct() {
        parent::__construct();
        $this->role = $this->session->userdata('ID_ROLE');
    }

    function get_condition_instansi_ver_pnwl_jabatan($ins = NULL, $param_uk = FALSE) {
        
        $condition = $ins && !is_null($ins) ? "     AND b.LEMBAGA = '" . $ins . "'    " : "   AND b.LEMBAGA = 1    ";
        
        if($param_uk){
            $condition .= "   AND e.UK_ID = $param_uk   ";
        }
        
//        if (!is_null($ins) && $ins && $this->role >= 2) {
        if (!is_null($ins) && $ins && ($this->role == 2 || $this->role == 31)) {
            return $condition;
        }

        return "   ";
    }

    function get_join_instansi_ver_pnwl_jabatan() {
        return "JOIN `t_pn_jabatan` `b` ON `a`.`ID_PN` = `b`.`ID_PN` "
                . "JOIN `m_jabatan` `c` ON `c`.`ID_JABATAN` = `b`.`ID_JABATAN` "
                . "LEFT JOIN `m_sub_unit_kerja` `d` ON `d`.`SUK_ID` = `c`.`SUK_ID` "
                . "LEFT JOIN `m_unit_kerja` `e` ON `e`.`UK_ID` = `c`.`UK_ID` "
                . "LEFT JOIN `m_inst_satker` `f` ON `f`.`INST_SATKERKD` = `c`.`INST_SATKERKD` "
                . "JOIN `t_pn_upload_xls_temp` `x` ON `x`.`NIK` = `a`.`NIK`";
    }

    function get_count_ver_pnwl_jabatan($ins, $param_uk = FALSE) {

        $qryins = $this->get_condition_instansi_ver_pnwl_jabatan($ins, $param_uk);
        $qryfrom = "   FROM `t_pn` `a`   ";
        $qryjoin = $this->get_join_instansi_ver_pnwl_jabatan();

        $qrywhere = "WHERE    NOT trim(`c`.`NAMA_JABATAN`) LIKE CONVERT(`x`.`NAMA_JABATAN` USING utf8)    " . $qryins . "    AND IS_PROCESSED = 0    AND b.IS_CURRENT = 1    AND b.ID_STATUS_AKHIR_JABAT IN (0,5)    AND b.IS_ACTIVE = 1";

        $qryselect = "  SELECT count(`x`.`ID`) AS `JML`   ";

        $sql = $qryselect . $qryfrom . $qryjoin . $qrywhere;

        $q = $this->db->query($sql);

//        echo $this->db->last_query();exit();
        if ($q) {
            $result = $q->row();
            if ($result) {
                return (int) $result->JML;
            }
        }


        return 0;
    }

    function get_count_ver_pnwl_tambahan($ins, $param_uk = FALSE) {
        $is_uk = $this->session->userdata('IS_UK');
        $id_uk = $this->session->userdata('UK_ID');

        $pqry = '1';
        $pqry_inst = '1';
        $qry_uk = NULL;
//        if ($ins && !is_null($ins) && $this->role >= 2) {
        if ($ins && !is_null($ins) && $this->role == 2 && $this->role == 31) {
            $pqry = "ID_LEMBAGA = $ins";
            $pqry_inst = "upl.ID_LEMBAGA = $ins";
        }
        
        $sub_query_join = "  LEFT JOIN m_jabatan `jab_subq` ON pn_j.id_jabatan = jab_subq.ID_JABATAN LEFT JOIN m_unit_kerja `muk_subq` ON muk_subq.UK_ID = jab_subq.UK_ID  ";
        if($param_uk && $is_uk != 1){
            $pqry_inst .= " AND muk.UK_ID = '$param_uk' ";
        }elseif($param_uk && $is_uk == 1){
            $pqry_inst .= " AND muk.UK_ID = '$id_uk' ";
        }

        if ($is_uk == 1) {// cek user unit kerja
            $qry_uk = " AND ("
                    . "SELECT UK_ID "
                    . "FROM m_unit_kerja "
                    . "JOIN m_inst_satker ON INST_SATKERKD = UK_LEMBAGA_ID  "
                    . "WHERE UK_NAMA = upl.NAMA_UNIT_KERJA AND "
                    . "INST_NAMA = upl.NAMA_LEMBAGA LIMIT 1"
                    . ") = '" . $id_uk . "'";
        }
        //ini tambahan eko
        $query_join = "JOIN m_unit_kerja muk
                        ON upl.`NAMA_UNIT_KERJA` = muk.`UK_NAMA`
                        JOIN m_inst_satker mis
                        ON mis.`INST_NAMA` = upl.`NAMA_LEMBAGA`";
        $slc_distinct = "SELECT DISTINCT (NIK)
                        FROM
                        t_pn pn
                        LEFT JOIN t_pn_jabatan pn_j
                        ON pn_j.ID_PN = pn.ID_PN
                        WHERE $pqry_inst";
        //sampai sini
        $strSql = "SELECT count(`upl`.`ID`) AS `JML`
                 FROM t_pn_upload_xls_temp upl
                 $query_join
                 WHERE upl.NIK NOT IN($slc_distinct) AND upl.IS_PROCESSED = 0 AND $pqry AND $pqry_inst ";

        $q = $this->db->query($strSql);

//        echo $this->db->last_query();exit();

        if ($q) {
            $result = $q->row();
            if ($result) {
                return (int) $result->JML;
            }
        }

        //echo $this->db->last_query();//exit();
        return 0;
    }

    function get_count_ver_pnwl_nonact($ins) {
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
	count(`a`.`ID_PN`) AS `JML`
            FROM
                    ((((((`t_pn` `a` JOIN `t_pn_jabatan` `b` ON((`a`.`ID_PN` = `b`.`ID_PN`))) 
					JOIN `m_jabatan` `c` ON((`c`.`ID_JABATAN` = `b`.`ID_JABATAN`))) 
					LEFT JOIN `m_sub_unit_kerja` `d` ON((`d`.`SUK_ID` = `c`.`SUK_ID`))) 
					LEFT JOIN `m_unit_kerja` `e` ON((`e`.`UK_ID` = `c`.`UK_ID`))) 
					LEFT JOIN `m_inst_satker` `f` ON((`f`.`INST_SATKERKD` = `e`.`UK_LEMBAGA_ID`))))
            WHERE
                    a.NIK NOT IN (SELECT DISTINCT(NIK) FROM t_pn_upload_xls_temp WHERE $qryins) AND ID_STATUS_AKHIR_JABAT = 0 AND IS_CALON = 0 AND IS_DELETED = 0 AND a.IS_ACTIVE = 1 AND b.IS_CURRENT = 1
             $pqry   ";
        $q = $this->db->query($queryStr);

        if ($q) {
            $result = $q->row();
            if ($result) {
                return (int) $result->JML;
            }
        }

        //echo $this->db->last_query();//exit();
        return 0;
    }

    function get_count_temp_uk_suk($int) {

        $this->db->select('count(`ID`) AS `JML`');

        if ($int)
            $this->db->where('ID_LEMBAGA', $int);

        $q = $this->db->get("t_pn_upload_xls_temp");

        if ($q) {
            $result = $q->row();
            if ($result) {
                return (int) $result->JML;
            }
        }

        //echo $this->db->last_query();//exit();
        return 0;
    }

}
