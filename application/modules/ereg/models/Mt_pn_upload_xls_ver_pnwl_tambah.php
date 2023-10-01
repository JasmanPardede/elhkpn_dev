<?php

class Mt_pn_upload_xls_ver_pnwl_tambah extends CI_Model {

    private $table = 't_pn_upload_xls_temp';

    public function __construct() {
        parent::__construct();
        $this->role = $this->session->userdata('ID_ROLE');
    }

    function get_unit_kerja() {
        $sql = "SELECT UK_ID "
                . "FROM m_unit_kerja "
                . "JOIN m_inst_satker ON INST_SATKERKD = UK_LEMBAGA_ID  "
                . "WHERE "
                . "UK_NAMA = t_pn_upload_xls_temp.NAMA_UNIT_KERJA AND "
                . "INST_NAMA = t_pn_upload_xls_temp.NAMA_LEMBAGA LIMIT 1";

        $q = $this->db->query($sql);

        if ($q) {
            $result = $q->row();
            if ($result) {
                return $result->UK_ID;
            }
        }
        return "0";
    }

    function get_uk_condition() {
        return "  ";
    }

    function get_uk_join() {
        $cari_advance = $this->input->get('CARI');
		if( $cari_advance)
		{
			$ins	= $cari_advance["INSTANSI"] != '' ? "   ID_LEMBAGA = '". $cari_advance["INSTANSI"]."'   " : " 1 ";
			$is_uk 	= $cari_advance["UNIT_KERJA"] != '' ? "1" : "1";
			$id_uk 	= $cari_advance["UNIT_KERJA"] != '' ? "  UK_ID ='". $cari_advance["UNIT_KERJA"] ."' ": " 1";
			$condition_cari_ = $cari_advance["TEXT"] != '' ? "   p.NAMA like '%". $cari_advance["TEXT"] ."%' ": " 1";
		}

        if ($is_uk == 1) {

            return "JOIN m_unit_kerja muk ON upl.`NAMA_UNIT_KERJA` = muk.`UK_NAMA`"
                    . "JOIN m_inst_satker mis ON mis.`INST_NAMA` = upl.`NAMA_LEMBAGA`";
        }

        return "    ";
    }

    function get_nik_not_in_instansi($ins, $uk_id=FALSE) {

        $pqry_inst = "      pn_j.LEMBAGA = '" . $ins . "'       ";
        
        $join_master_unit_kerja = "";
        
        $sql = "SELECT DISTINCT(NIK) NIK_DISTINCT FROM t_pn pn left join t_pn_jabatan pn_j on pn_j.ID_PN = pn.ID_PN ".$join_master_unit_kerja." WHERE " . $pqry_inst;

        $q = $this->db->query($sql);

        if ($q) {
            $result = $q->result();

            if ($result) {

                $arr_nik = [];

                foreach ($result as $record) {
                    $arr_nik[] = $record->NIK_DISTINCT;
                }

                if (count($arr_nik) > 0) {
                    return "'" . implode("','", $arr_nik) . "'";
                }
            }
        }
        return "''";
    }

    function get_ver_pnwl_tambahan_two($ins, $offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false, $uk = FALSE) {
        //$is_uk = $this->session->userdata('IS_UK');
        //$id_uk = $this->session->userdata('UK_ID');
        $cari_advance = $this->input->get('CARI');
	$instem = $ins;
		if($cari_advance)
		{
			$ins	= trim($cari_advance["INSTANSI"]) != "" ? "". $cari_advance["INSTANSI"]."  " : "1 ";
			if ($ins == '1' || $ins == 1 || $ins == '1 '){
                            $ins = $instem;
                        }
                        $is_uk = $uk;
			$id_uk 	= trim($cari_advance["UNIT_KERJA"]) != "" ? "". $cari_advance["UNIT_KERJA"] ."": "1";
			$condition_cari_ = $cari_advance["TEXT"] != "" ? "   p.NAMA like '%". $cari_advance["TEXT"] ."%' ": "1";
		}
        /* Comment By Rian
		if($uk_id && $uk_id!="" && $uk_id!=NULL){
            $is_uk = TRUE;
            $id_uk = $uk;
        }
		*/
        $pqry = '1';
        /**
         * ROle :
         *  1. Admin Aplikasi
         *  2. Admin KPK
         *  3. Admin Instansi
         */
        if ($ins && $this->role > 2) {
            $pqry = "     ID_LEMBAGA = '" . $ins . "'    ";
        }
        
        if (trim($ins) == "" || trim($ins) == '-'){
            $inst = " and 1 ";
        }else{
            $inst = " and upl.ID_LEMBAGA = '" . $ins . "'    ";
        }

        $nik_not_in = $this->get_nik_not_in_instansi($ins, $uk);
        
        $qry_uk = $this->get_uk_condition($uk);
        
        $qryukjoin = $this->get_uk_join();
        
        $ukcondition = "   ";
        if ($is_uk == 1 || $is_uk == '1' && trim($id_uk) != '-') {
            $ukcondition = " AND muk.UK_ID = '".$id_uk."'  ";
        }else{
            if($is_uk=="" || trim($is_uk) == '-'){
                $ukcondition = " AND 1  ";
            }else{
                $ukcondition = " AND muk.UK_ID = '" . $is_uk . "'  ";
            }
        }
        
        $sql_select = "SELECT DISTINCT upl.ID as IDUPLTEMP, upl.*, "
                . "(CASE WHEN upl.id_jab_temp IS NOT NULL THEN 'ada' ELSE NULL END) as CEK ";

        $sql_select_count = "SELECT count(DISTINCT upl.ID) as JML ";

        $sql_from = " FROM t_pn_upload_xls_temp upl ";
//        $sql_condition = "  WHERE upl.NIK NOT IN(" . $nik_not_in . ") and upl.ID_LEMBAGA= $ins AND upl.IS_PROCESSED = 0 AND " . $pqry . "  " . $qry_uk."  ".$ukcondition;
        $sql_condition = "  WHERE upl.NIK NOT IN(" . $nik_not_in . ") $inst AND upl.IS_PROCESSED = 0 AND " . $pqry . "  " . $qry_uk."  ".$ukcondition;

        $sql_cari = "    ";

        if (!is_null($cari) && $cari != '') {
            $sql_cari = "AND (upl.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "upl.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "upl.NAMA_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%'))";
        }

        $strQuery = $this->db->query($sql_select . $sql_from . $qryukjoin . $sql_condition . $sql_cari . "  LIMIT " . $offset . ", " . $rowperpage);

//        echo $this->db->last_query();exit;

        $strQueryCount = $this->db->query($sql_select_count . $sql_from . $qryukjoin . $sql_condition . $sql_cari);


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
