<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Model untuk melakukan load page pada halaman PN/WL Aktif
 *
 * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
 */
class Mmpnwn extends CI_Model {

    private $table = 'T_PN';
    private $table_user = 'T_USER';
    private $table_role = 'T_USER_ROLE';
    private $table_jabatan = 'M_JABATAN';
    private $table_jabatan_pn = 'T_PN_JABATAN';
    private $table_satker = 'M_INST_SATKER';
    private $table_uker = 'M_UNIT_KERJA';
    public $role;
    public $set_additional_join_wl_aktif = FALSE;
    public $is_lhkpn_offline = FALSE;
    public $is_tracking_lhkpn = FALSE;

    function __construct() {
        parent::__construct();
        $this->role = $this->session->userdata('ID_ROLE');
        $this->ins = $this->session->userdata('INST_SATKERKD');
        $this->uker = $this->session->userdata('UK_ID');
    }

    private function __select_fields() {

        $select_fields = "DISTINCT P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, PJ.ID_STATUS_AKHIR_JABAT STS_J,"
                . "TRIM(CONCAT(IF(ISNULL(P.GELAR_DEPAN),'',P.GELAR_DEPAN),' ',P.NAMA,IF(ISNULL(P.GELAR_BELAKANG) OR P.GELAR_BELAKANG = '', '', ', '),IF(ISNULL(P.GELAR_BELAKANG),'',P.GELAR_BELAKANG))) AS NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, PJ.SUB_UNIT_KERJA, "
                . "PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, PJ.EMAIL_KANTOR, PJ.ESELON,IFNULL(PJ.IS_WL,0)AS IS_WL,PJ.TAHUN_WL,"
                . "p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR";
        $this->db->select($select_fields, FALSE);

        return;
    }

    private function __select_fields_offline() {

        $select_fields = "DISTINCT P.ID_PN,
            JAB.NAMA_JABATAN N_JAB, SUK.SUK_NAMA N_SUK, UK.UK_NAMA N_UK, INTS.INST_NAMA,
            P.NIK,
            TRIM(CONCAT(IF(ISNULL(P.GELAR_DEPAN),'',P.GELAR_DEPAN),' ',P.NAMA,IF(ISNULL(P.GELAR_BELAKANG) OR P.GELAR_BELAKANG = '', '', ', '),IF(ISNULL(P.GELAR_BELAKANG),'',P.GELAR_BELAKANG))) AS NAMA,
            PJ.ID ID_JAB,
            PJ.UNIT_KERJA,
            PJ.SUB_UNIT_KERJA,
            U.ID_USER,
            PJ.ID_STATUS_AKHIR_JABAT,p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR, PJ.TAHUN_WL";

        $this->db->select($select_fields, FALSE);

        return;
    }



    private function __set_additional_selectfieldswl() {
        $this->db->select('
            P.ID_PN,
            P.NIK,
            INTS.INST_NAMA,
            PJ.ID ID_JAB,
            PJ.UNIT_KERJA,
            PJ.SUB_UNIT_KERJA,
            U.ID_USER,
            PJ.ID_STATUS_AKHIR_JABAT,p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR', false);
    }

    private function __select_fieldswl($request_from_lhkpnoffline = FALSE, $aktifwl=0) {

//        $select_fields = "DISTINCT P.ID_PN, PJ.ID, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
//                . "UK.UK_NAMA, P.NIK, "
//                . "TRIM(CONCAT(IF(ISNULL(P.GELAR_DEPAN),'',P.GELAR_DEPAN),' ',P.NAMA,IF(ISNULL(P.GELAR_BELAKANG) OR P.GELAR_BELAKANG = '', '', ', '),IF(ISNULL(P.GELAR_BELAKANG),'',P.GELAR_BELAKANG))) AS NAMA, IF(PJ.`ID_STATUS_AKHIR_JABAT`=0, 'Online','Offline')AS IS_WL, "
//                . "PJ.tgl_kirim, PJ.status_lapor as StatusKirim, "
//                . "P.NAMA as NAMA_TANPA_GELAR, "
//                . "P.EMAIL as EMAIL, "
//                . "p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR, plh.tgl_kirim_final, plh.JENIS_LAPORAN, "
//                . "plh.ID_LHKPN, P.TGL_LAHIR, P.TEMPAT_LAHIR, plh.tgl_lapor, ver.STAT, plh.entry_via ";

        $select_fields = "DISTINCT P.ID_PN, PJ.ID, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, P.NIK, "
                . "TRIM(CONCAT(IF(ISNULL(P.GELAR_DEPAN),'',P.GELAR_DEPAN),' ',P.NAMA,IF(ISNULL(P.GELAR_BELAKANG) OR P.GELAR_BELAKANG = '', '', ', '),IF(ISNULL(P.GELAR_BELAKANG),'',P.GELAR_BELAKANG))) AS NAMA, IF(PJ.`ID_STATUS_AKHIR_JABAT`=0, 'Online','Offline')AS IS_WL, "
                . "PJ.tgl_kirim, PJ.status_lapor as StatusKirim, "
                . "P.NAMA as NAMA_TANPA_GELAR, "
                . "P.EMAIL as EMAIL, "
                . "p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR, "
                . "P.TGL_LAHIR, P.TEMPAT_LAHIR, "
                . "JAB.IS_ACTIVE AS JAB_ACTIVE, SUK.UK_STATUS AS SUK_ACTIVE, UK.UK_STATUS AS UK_ACTIVE, INTS.IS_ACTIVE AS INST_ACTIVE";
        if($aktifwl==1){
            $select_fields .=",IF (ISNULL(aaa.tgl_kirim_final) || (aaa.STATUS = '0') || ((aaa.STATUS = '7') && (aaa.DIKEMBALIKAN > '0')), 'Belum', 'Sudah') AS STATUS_KIRIM_FINAL, aaa.STATUS, aaa.id_lhkpn ";
        }
        

        if(!$request_from_lhkpnoffline){
            $select_fields .= ", PJ.TAHUN_WL ";
        }

        $this->db->select($select_fields, FALSE);

        if ($this->set_additional_join_wl_aktif) {
            $this->__set_additional_selectfieldswl();
        }

        return;
    }

    private function __set_join() {

        $this->db->join('`T_USER` `U`', '`U`.`USERNAME` = `P`.`NIK`', 'left');

        $this->db->join('`t_pn_jabatan` `PJ`', '`P`.`ID_PN` = `PJ`.`ID_PN`');

        $where_join_t_pn_jabatan = "`PJ`.`ID_STATUS_AKHIR_JABAT` IN(0, 10, 11, 15) AND "
                . "`PJ`.`IS_DELETED` = '0' AND "
//                . "`PJ`.`IS_CALON` = '0' AND "
                . "`PJ`.`IS_ACTIVE` = '1' AND "
                . "`P`.`IS_ACTIVE` = '1' AND "
                . "`PJ`.`IS_CURRENT` = '1' ";

        $this->db->where($where_join_t_pn_jabatan);

        $this->db->join('`M_JABATAN` `JAB`', '`PJ`.`ID_JABATAN` = `JAB`.`ID_JABATAN` AND `JAB`.`IS_ACTIVE` <> 0', 'left');
        $this->db->join('`m_sub_unit_kerja` `SUK`', '`SUK`.`SUK_ID` = `JAB`.`SUK_ID`', 'left');
        $this->db->join('`m_unit_kerja` `UK`', '`UK`.`UK_ID` = `JAB`.`UK_ID`', 'left');
        $this->db->join('`m_inst_satker` `INTS`', '`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`', 'left');
    }

    private function __set_join_offline() {

        $this->db->join('T_USER U', 'U.USERNAME = P.NIK', 'LEFT');
        $this->db->join('T_PN_JABATAN PJ', 'P.ID_PN = PJ.ID_PN', 'LEFT');
        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN AND JAB.IS_ACTIVE <> 0', 'left');
        $this->db->join('m_sub_unit_kerja SUK', 'SUK.SUK_ID = JAB.SUK_ID', 'left');
        $this->db->join('m_unit_kerja UK', 'UK.UK_ID = JAB.UK_ID', 'left');
        $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = UK.UK_LEMBAGA_ID', 'left');

        $where_join_t_pn_jabatan = "`PJ`.`ID_STATUS_AKHIR_JABAT` = '5' AND "
                . "`P`.`IS_ACTIVE` = '1' AND "
                . "`PJ`.`IS_WL` = '1' AND "
                . "`PJ`.`IS_CURRENT` = '1' ";

        $this->db->where($where_join_t_pn_jabatan);
    }



    private function __set_additional_join() {
//        $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = UK.UK_LEMBAGA_ID', 'left');
        $this->db->join('T_USER U', 'U.USERNAME = P.NIK', 'left');
        $this->db->where("PJ.is_wl = '1'");
    }

    private function __set_joinwl($sql_where=null, $thn_wl=null, $aktifwl=0) {

        $cari_advance = $this->input->get('CARI');
        $is_wl_deleted = $cari_advance["IS_DELETED"];

        $left = 'left';
        if (!$this->set_additional_join_wl_aktif) {
            $left = NULL;
        }

        $this->db->join('`t_pn_jabatan` `PJ`', '`P`.`ID_PN` = `PJ`.`ID_PN`', $left);

        if ($is_wl_deleted == '2'){
            $where_join_t_pn_jabatan = ""
                . "`PJ`.`IS_DELETED` = '1' AND "
//                . "`PJ`.`IS_CALON` = '0' AND "
                . "`PJ`.`IS_ACTIVE` = '0' AND "
                . "`P`.`IS_ACTIVE` = '0' AND "
                . "`PJ`.`IS_CURRENT` = '1'";
        //. " PJ.IS_WL='1'";
        }else{
            $where_join_t_pn_jabatan = ""
                . "`PJ`.`IS_DELETED` = '0' AND "
//                . "`PJ`.`IS_CALON` = '0' AND "
                . "`PJ`.`IS_ACTIVE` = '1' AND "
                . "`P`.`IS_ACTIVE` = '1' AND "
                . "`PJ`.`IS_CURRENT` = '1'";
        //. " PJ.IS_WL='1'";
        }

        $this->db->where($where_join_t_pn_jabatan);
        $this->db->join('`M_JABATAN` `JAB`', '`PJ`.`ID_JABATAN` = `JAB`.`ID_JABATAN` AND `JAB`.`IS_ACTIVE` <> 0', 'left');
        $this->db->join('`m_sub_unit_kerja` `SUK`', '`SUK`.`SUK_ID` = `JAB`.`SUK_ID`', 'left');
        $this->db->join('`m_unit_kerja` `UK`', '`UK`.`UK_ID` = `JAB`.`UK_ID`', 'left');
//        $this->db->join('`t_lhkpn` `plh`', '`plh`.`ID_PN` = `P`.`ID_PN`', 'left');
        $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = UK.UK_LEMBAGA_ID', 'left');
//        $this->db->join('t_lhkpnoffline_penugasan_verifikasi ver', 'ver.ID_LHKPN = plh.ID_LHKPN', 'left');

        
        if(!is_null($sql_where) && $aktifwl==1){

            $TAHUN_WL = $cari_advance['TAHUN_WL'];
            
            if($thn_wl){ 
                $get_tahun_wl = $thn_wl;
                $appendTahun = 'AND YEAR(tgl_lapor) = '.$get_tahun_wl.'';
            }elseif($TAHUN_WL=='')
            {
                $appendTahun = '';   
            }
            else{
                $get_tahun_wl = $TAHUN_WL;  
                $appendTahun = 'AND YEAR(tgl_lapor) = '.$get_tahun_wl.'';   
            }

            $this->db->join('(SELECT id_lhkpn, id_pn, tgl_kirim_final, JENIS_LAPORAN, STATUS, DIKEMBALIKAN, YEAR(tgl_lapor) AS tahun_lapor 
            FROM 
                t_lhkpn t 
            WHERE 
                id_lhkpn IN (
                SELECT 
                    MAX(id_lhkpn) AS max_idlhkpn 
                FROM 
                    t_lhkpn 
                WHERE 
                    ID_PN IN (
                    SELECT 
                        * 
                    FROM 
                        (
                        SELECT 
                            DISTINCT P.ID_PN 
                        FROM 
                            `t_pn` `P` 
                            LEFT JOIN `t_pn_jabatan` `PJ` ON `P`.`ID_PN` = `PJ`.`ID_PN` 
                            LEFT JOIN `M_JABATAN` `JAB` ON `PJ`.`ID_JABATAN` = `JAB`.`ID_JABATAN` 
                            AND `JAB`.`IS_ACTIVE` <> 0 
                            LEFT JOIN `m_sub_unit_kerja` `SUK` ON `SUK`.`SUK_ID` = `JAB`.`SUK_ID` 
                            LEFT JOIN `m_unit_kerja` `UK` ON `UK`.`UK_ID` = `JAB`.`UK_ID` 
                            LEFT JOIN `m_inst_satker` `INTS` ON `INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID` 
                            LEFT JOIN `T_USER` `U` ON `U`.`USERNAME` = `P`.`NIK` 
                        WHERE 
                            `PJ`.`IS_DELETED` = 0 
                            AND `PJ`.`IS_ACTIVE` = 1 
                            AND `P`.`IS_ACTIVE` = 1 
                            AND `PJ`.`IS_CURRENT` = 1 
                            AND `PJ`.`is_wl` = 1 
                            AND '.$sql_where.' 
                        ORDER BY 
                            `P`.`NAMA`
                        ) AS subquery
                    ) 
                    AND is_active = 1
                    '.$appendTahun.' 
                GROUP BY 
                    id_pn
                    ) AND is_active = 1
                    '.$appendTahun.' 
                GROUP BY id_pn
                ) aaa', 'aaa.id_pn = `P`.`ID_PN`', 'left');
                
        }

        if ($this->set_additional_join_wl_aktif) {
            $this->__set_additional_join();
        }
//        $cari_advance = $this->input->get('CARI');
//
//        $condition_is_wl = '  1  ';
//        $condition_tahun_wl = '  1  ';
        /*
          if($cari_advance){
          $this->db->join('`t_lhkpn` `tlh`', '`tlh`.`ID_PN` = `p`.`ID_PN`', 'left');
          }
         */
    }

    private function __cari($cari = FALSE) {
        if ($cari && $cari != '') {

            $condition = " AND (P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') ) ";

            if ($this->is_tracking_lhkpn) {

                $condition = " AND (P.NAMA LIKE CONCAT('%', '" . $cari["TEXT"] . "' , '%') OR "
                    . "P.NIK LIKE CONCAT('%', '" . $cari["TEXT"] . "' , '%') ) ";

                if ($cari["TGL_LAHIR"] == ''){
                    $condition .= " AND 1 ";
                }else{
                    $tgl_lahir = explode('/', $cari["TGL_LAHIR"]);
                    $tgl_lahir = array_reverse($tgl_lahir);
                    $tgl_lahir = implode('-', $tgl_lahir);
                    $condition .= " AND P.TGL_LAHIR = '".$tgl_lahir."'";
                }
            }

            if (!$this->is_lhkpn_offline) {
                $condition = " AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                        . "PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                        . "P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                        . "P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                        . "P.EMAIL LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                        . "P.NO_HP LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                        . "PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";
            }

            return $condition;
        }
        return " ";
    }

    public function load_page_offline($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {

        $cari_advance = $this->input->get('CARI');

        $result = FALSE;
        $total_rows = 0;
        $uk_where = $this->uker && !is_null($this->uker) ? "AND uk.UK_ID = $this->uker" : "AND 1";
        $this->db->select("count(P.ID_PN) as cnt");
        $this->__set_join_offline();
        $sql_where = " 1 " . $this->__cari($cari);
        if ($this->role != 1 && $this->role != 2 && $this->role != 31) {
            $sql_where .= $this->ins && !is_null($this->ins) ? " AND INTS.INST_SATKERKD = $this->ins" : "AND 1";
            if ($cari_advance) {
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
                $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
                $condition_cari_ = $cari_advance["TEXT"] != '' ? "   (p.NAMA like '%" . $cari_advance["TEXT"] . "%' OR p.NIK like '%" . $cari_advance["TEXT"] . "%') " : " 1";
                $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : "1";
                $sql_where .= " AND " . $condition_tahun_wl . " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
                //$sql_where .= " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
            } else {
                $sql_where .= "AND INTS.INST_SATKERKD = 1081";
                $sql_where .= " $uk_where";
            }
        }
        //adding by iqbal
        else if ($this->role == 1 || $this->role == 2  || $this->role == 31) {


//			if($cari_advance['INSTANSI'] != "" || $cari_advance['UNIT_KERJA'] != ""){
            if ($cari_advance) {
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
                $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
                $condition_cari_ = $cari_advance["TEXT"] != '' ? "   (p.NAMA like '%" . $cari_advance["TEXT"] . "%' OR p.NIK like '%" . $cari_advance["TEXT"] . "%') " : " 1";
                $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : "1";
                $sql_where .= " AND " . $condition_tahun_wl . " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
            } else {
                $sql_where .= "AND INTS.INST_SATKERKD = 1081";
                $sql_where .= " $uk_where";
            }
        }
        $this->db->where($sql_where);

        $query = $this->db->get('t_pn P');

        if ($query) {
            $result = $query->row();

            if ($result) {
                $total_rows = $result->cnt;
            }
        }
        $query->free_result();
        unset($result, $query);

        $this->__select_fields_offline();

        $this->__set_join_offline();
        $this->db->order_by("P.NAMA", "asc");
        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);
        //display($this->db->last_query());exit;
        if ($query) {
            $result = $query->result();
        }
        $query->free_result();

        $ID_PN = array();
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $ID_PN[] = $item->ID_PN;
                $result[$key]->NO_URUT = $i;
                $result[$key]->havelhkpn = FALSE;
                $i++;
            }
        }

        if (!empty($ID_PN)) {
            $ID_PN_SEL = implode($ID_PN, ',');
            $sql = "SELECT * FROM T_LHKPN WHERE ID_PN IN ($ID_PN_SEL)";
            $query = $this->db->query($sql);

            $rs_tlhkpn = FALSE;
            if ($query) {
                $rs_tlhkpn = $query->result();
            }

            $query->free_result();

            if ($rs_tlhkpn) {
                foreach ($rs_tlhkpn as $lhkpn) {

                    foreach ($result as $key => $item) {
                        if ($item->ID_PN == $lhkpn->ID_PN) {
                            $result[$key]->havelhkpn = TRUE;
                            $result[$key]->id_user_md5 = substr(md5($item->ID_USER), 5, 8);
                            break;
                        }
                    }
                }
            }
            unset($rs_tlhkpn);
        }

        return (object) array("total_rows" => $total_rows, "result" => $result, "query" => $sql_where, "cari" => $cari_advance);
    }

    public function load_page($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {
        $cari_advance = $this->input->get('CARI');
        $result = FALSE;
        $total_rows = 0;
        $uk_where = $this->uker && !is_null($this->uker) ? "AND uk.UK_ID = $this->uker" : "AND 1";
        $this->db->select("count(P.ID_PN) as cnt");
        $this->__set_join();
        $sql_where = " 1 " . $this->__cari($cari);
        if ($this->role != 1 && $this->role != 2 && $this->role != 31) {
            $sql_where .= $this->ins && !is_null($this->ins) ? " AND INTS.INST_SATKERKD = $this->ins" : "AND 1";
//			if($cari_advance['UNIT_KERJA'] != ""){
            if ($cari_advance) {
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
                $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
//				$condition_wl = $cari_advance["IS_WL"] != '' ? "   pj.is_wl ='". $cari_advance["IS_WL"] ."' ": " 1";
                $condition_wl = $cari_advance["IS_WL"] == '' ? "   1   " :
                        $condition_wl = $cari_advance["IS_WL"] != '1' ? "   PJ.tgl_kirim is null   " :
                        $condition_wl = $cari_advance["IS_WL"] != '0' ? "   PJ.tgl_kirim is not null   " :
                        $condition_wl = $cari_advance["IS_WL"] != '2' ? "   1   " :
                        $condition_wl;
                $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : "1";
                $condition_cari_ = $cari_advance["TEXT"] != '' ? "   (p.NAMA like '%" . $cari_advance["TEXT"] . "%' OR p.NIK like '%" . $cari_advance["TEXT"] . "%') " : " 1";
                //$sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
                $sql_where .= " and " . $condition_tahun_wl . " and " . $condition_wl . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
            } else {
                if ($this->role == 1 && $this->role == 2 && $this->role == 31) {
                    $sql_where .= "AND INTS.INST_SATKERKD = 1081";
                }
                $sql_where .= " $uk_where";
            }
        } else if ($this->role == 1 || $this->role == 2 || $this->role == 31) {


            //if($cari_advance['INSTANSI'] != "" || $cari_advance['UNIT_KERJA'] != ""){
            if ($cari_advance) {
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
                $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
//				$condition_wl = $cari_advance["IS_WL"] != '' ? "   pj.is_wl ='". $cari_advance["IS_WL"] ."' ": " 1";
                $condition_wl = $cari_advance["IS_WL"] == '' ? "   1   " :
                        $condition_wl = $cari_advance["IS_WL"] != '1' ? "   PJ.tgl_kirim is null   " :
                        $condition_wl = $cari_advance["IS_WL"] != '0' ? "   PJ.tgl_kirim is not null   " :
                        $condition_wl = $cari_advance["IS_WL"] != '2' ? "   1   " :
                        $condition_wl;
                $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : "1";
                $condition_cari_ = $cari_advance["TEXT"] != '' ? "   (p.NAMA like '%" . $cari_advance["TEXT"] . "%' OR p.NIK like '%" . $cari_advance["TEXT"] . "%') " : " 1";
                //$sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
                $sql_where .= " and ". $condition_instansi ." and " . $condition_tahun_wl . " and " . $condition_wl . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
            } else {
                $sql_where .= "AND INTS.INST_SATKERKD = 1081";
                $sql_where .= " $uk_where";
            }
        }

        $sql_where .= " AND PJ.ID_STATUS_AKHIR_JABAT NOT IN (10, 11, 15) AND PJ.`is_wl` = '1' $uk_where "; //status active
        $this->db->where($sql_where);

        $query = $this->db->get('t_pn P');

        if ($query) {
            $result = $query->row();

            if ($result) {
                $total_rows = $result->cnt;
            }
        }
        $query->free_result();
        unset($result, $query);

        $this->__select_fields();

        $this->__set_join();
        $this->db->order_by("P.NAMA", "asc");
        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);

//        display($this->db->last_query());exit;
        if ($query) {
            $result = $query->result();
        }
        $query->free_result();

        $ID_PN = array();
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $ID_PN[] = $item->ID_PN;
                $result[$key]->NO_URUT = $i;
                $result[$key]->havelhkpn = FALSE;
                $i++;
            }
        }
        if (!empty($ID_PN)) {
            $ID_PN_SEL = implode($ID_PN, ',');
            $sql = "SELECT * FROM T_LHKPN WHERE ID_PN IN ($ID_PN_SEL)";
            $query = $this->db->query($sql);

            $rs_tlhkpn = FALSE;
            if ($query) {
                $rs_tlhkpn = $query->result();
            }

            $query->free_result();

            if ($rs_tlhkpn) {
                foreach ($rs_tlhkpn as $lhkpn) {

                    foreach ($result as $key => $item) {
                        if ($item->ID_PN == $lhkpn->ID_PN) {
                            $result[$key]->havelhkpn = TRUE;
                            $result[$key]->id_user_md5 = substr(md5($item->ID_USER), 6, 8);
                            break;
                        }
                    }
                }
            }
            unset($rs_tlhkpn);
        }
        return (object) array("total_rows" => $total_rows, "result" => $result, "sql" => $sql_where);
        exit;
    }

    public function get_min_tahun_t_pn() {
        $result = $this->db->query("select min(tahun_wl) as min_tahun from t_pn_jabatan");
        // $result = $this->db->query("select min(pj.tahun_wl) as min_tahun from t_pn p join t_pn_jabatan pj on p.id_pn = pj.id_pn");
        if ($result) {
            $record = $result->row();
            if ($record) {
                return $record->min_tahun;
            }
        }
        return FALSE;
    }

    protected function load_page_PL_AKTIF_cari_advance($uk_where, $aktifwl = 1) {
        $cari_advance = $this->input->get('CARI');

        $condition_is_wl = " 1 ";
        $condition_tahun_wl = " 1 ";
        $condition_instansi = " 1 ";
        $condition_uk = " 1 ";

        $sql_where = "  ";

        if ($cari_advance) {
            if($aktifwl != 1){
                $condition_is_wl = $cari_advance["IS_WL"] == '' ? "   1   " :
                $condition_is_wl = $cari_advance["IS_WL"] != '1' ? "   PJ.tgl_kirim is null   " :
                $condition_is_wl = $cari_advance["IS_WL"] != '0' ? "   PJ.tgl_kirim is not null   " :
                $condition_is_wl = $cari_advance["IS_WL"] != '2' ? "   1   " :
                $condition_is_wl;
            }
            
            $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : $condition_tahun_wl;
            $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
            $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "'   " : $condition_uk;
        } else {
            if ($this->role == 1 || $this->role == 2 || $this->role == 31) {
                if ($condition_instansi == " 1 ") {
                    $condition_instansi = " INTS.INST_SATKERKD = 1081 ";
                }
                if ($condition_uk == " 1 ") {
                    $condition_uk = " uk.UK_ID = 590 ";
                }
            }
        }

        $sql_where .= " AND " . $condition_is_wl . " and " . $condition_tahun_wl;
        $sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " $uk_where     ";

        return $sql_where;
    }

    protected function load_page_pl_aktif_condition_by_role($aktifwl, $uk_where) {

        $cari_advance = $this->input->get('CARI');
        $is_wl_deleted = $cari_advance["IS_DELETED"];
        $sql_where = "";

        if ($aktifwl == 1) {
            $sql_where .= "  and is_wl= 1  AND `PJ`.`ID_STATUS_AKHIR_JABAT` IN(0,5)";
        }

        if ($this->role != 1 && $this->role != 2  && $this->role != 31 && $this->ins !== NULL) {
            if ($aktifwl == 1) {
                $sql_where .= "  AND INTS.INST_SATKERKD = $this->ins   ";
            } else {
                if ($is_wl_deleted == '2'){
                    $sql_where .= " AND INTS.INST_SATKERKD = $this->ins and is_wl= 2 AND `PJ`.`ID_STATUS_AKHIR_JABAT` = 99 $uk_where   ";
                } else {
                    $sql_where .= " and (is_wl= 0  OR pj.`is_wl` IS NULL) AND INTS.INST_SATKERKD = $this->ins  AND PJ.ID_STATUS_AKHIR_JABAT IN(0,5) ";
                }
            }
        } else if (($this->role == 1 || $this->role == 2 || $this->role == 3 || $this->role == 7 || $this->role == 10 || $this->role == 13 || $this->role == 14 || $this->role == 18 || $this->role == 31) AND ( $this->ins == "" OR $this->ins == NULL)) {
            //adding by iqbal jika login sebagai admin_kpk default yang tampil pegawai KPK
            if ($aktifwl != 1) {
                if ($is_wl_deleted == '2'){
                    $sql_where .= " and is_wl= 2 AND `PJ`.`ID_STATUS_AKHIR_JABAT` = 99 $uk_where   ";
                }else{
                    $sql_where .= " and is_wl= 0 AND `PJ`.`ID_STATUS_AKHIR_JABAT` IN(0,5) $uk_where   ";
                }
            }
        }

        return $sql_where;
    }

    protected function load_page_pl_aktif_condition($cari = NULL, $aktifwl = 1) {
        //        $sql_where = "  (plh.IS_ACTIVE <> '0' OR  plh.IS_ACTIVE IS NULL) " . $this->__cari($cari);
        $sql_where = " 1 " . $this->__cari($cari);

        $uk_where = $this->uker && !is_null($this->uker) ? " AND uk.UK_ID = '" . $this->uker . "' " : "  ";

        if (!$this->is_lhkpn_offline) {
            $sql_where .= $this->load_page_PL_AKTIF_cari_advance($uk_where, $aktifwl);
        }
        //        echo $sql_where;exit;

        $sql_where .= $this->load_page_pl_aktif_condition_by_role($aktifwl, $uk_where);
        //        echo $sql_where;exit;


        $is_aktif_tlhkpn = " AND subtbllhkpn.IS_ACTIVE = '1' AND subtbllhkpn.IS_ACTIVE <> '0'  ";


        $sql_where .= "  $uk_where ";
        //        $sql_where .= " AND IF (plh.tgl_lapor =
        //                        (SELECT
        //                          MAX(subtbllhkpn.tgl_lapor)
        //                        FROM
        //                          t_lhkpn subtbllhkpn
        //                        WHERE subtbllhkpn.id_pn = `plh`.`id_pn` " . $is_aktif_tlhkpn . ") IS NULL, 1, plh.tgl_lapor =
        //                        (SELECT
        //                          MAX(subtbllhkpn.tgl_lapor)
        //                        FROM
        //                          t_lhkpn subtbllhkpn
        //                        WHERE subtbllhkpn.id_pn = `plh`.`id_pn` " . $is_aktif_tlhkpn . ")) AND (plh.IS_ACTIVE = '1' OR "
        //                . "(SELECT
        //                          MAX(subtbllhkpn.tgl_lapor)
        //                        FROM
        //                          t_lhkpn subtbllhkpn
        //                        WHERE subtbllhkpn.id_pn = `plh`.`id_pn` " . $is_aktif_tlhkpn . ") IS NULL)";

        //        echo $sql_where;exit;
        return $sql_where;
    }

    public function load_page_PL_AKTIF($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '', $aktifwl = 1, $request_from_lhkpn_offline = FALSE) {

        if ($instansi) {
            $instansi == '' ? " 1 " : $instansi;
        }

        $result = FALSE;
        $total_rows = 0;

        $this->set_additional_join_wl_aktif = FALSE;
        if ($aktifwl) {
            $this->set_additional_join_wl_aktif = TRUE;
        }

            //        $uk_where = $this->uker && !is_null($this->uker) ? "AND uk.UK_ID = $this->uker" : "AND 1";

        /// CODE ASLI ///
        $sql_where = $this->load_page_pl_aktif_condition($cari, $aktifwl);
                
        $this->db->select("count(DISTINCT PJ.ID) as cnt");
        
        $this->__set_joinwl($sql_where, null, $aktifwl);
        $this->db->where($sql_where);

        $query = $this->db->get('t_pn P');


        if ($query) {
            $result = $query->row();

            if ($result) {
                $total_rows = $result->cnt;
            }
        }

        $query->free_result();
        unset($result, $query);

        /// END CODE ASLI ///
        

        //// query count jika filter status lapor disi ////

        $cari = $this->input->get('CARI');

        if($aktifwl == 1 && $cari['IS_WL'] != null){

            $status_lapor = ($cari['IS_WL']==0)?"Belum":"Sudah";

            $query_total_row= "SELECT COUNT(*) AS jumlah FROM 
            (
                SELECT 
                  DISTINCT P.ID_PN, 
                  IF (
                    ISNULL(aaa.tgl_kirim_final) || (aaa.STATUS = '0') || (
                      (aaa.STATUS = '7') && (aaa.DIKEMBALIKAN > '0')
                    ), 
                    'Belum', 
                    'Sudah'
                  ) AS STATUS_KIRIM_FINAL 
                FROM 
                  `t_pn` `P` 
                  LEFT JOIN `t_pn_jabatan` `PJ` ON `P`.`ID_PN` = `PJ`.`ID_PN` 
                  LEFT JOIN `M_JABATAN` `JAB` ON `PJ`.`ID_JABATAN` = `JAB`.`ID_JABATAN` 
                  AND `JAB`.`IS_ACTIVE` <> 0 
                  LEFT JOIN `m_sub_unit_kerja` `SUK` ON `SUK`.`SUK_ID` = `JAB`.`SUK_ID` 
                  LEFT JOIN `m_unit_kerja` `UK` ON `UK`.`UK_ID` = `JAB`.`UK_ID` 
                  LEFT JOIN `m_inst_satker` `INTS` ON `INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID` 
                  LEFT JOIN (
                    SELECT 
                        id_lhkpn, 
                        id_pn, 
                        tgl_kirim_final, 
                        JENIS_LAPORAN, 
                        STATUS, 
                        DIKEMBALIKAN, 
                        YEAR(tgl_lapor) AS tahun_lapor 
                    FROM 
                        t_lhkpn t 
                    WHERE 
                    id_lhkpn IN (
                        SELECT 
                        MAX(id_lhkpn) AS max_idlhkpn 
                        FROM 
                        t_lhkpn 
                        WHERE 
                        ID_PN IN (
                            SELECT 
                            * 
                            FROM 
                            (
                                SELECT 
                                DISTINCT P.ID_PN 
                                FROM 
                                `t_pn` `P` 
                                LEFT JOIN `t_pn_jabatan` `PJ` ON `P`.`ID_PN` = `PJ`.`ID_PN` 
                                LEFT JOIN `M_JABATAN` `JAB` ON `PJ`.`ID_JABATAN` = `JAB`.`ID_JABATAN` 
                                AND `JAB`.`IS_ACTIVE` <> 0 
                                LEFT JOIN `m_sub_unit_kerja` `SUK` ON `SUK`.`SUK_ID` = `JAB`.`SUK_ID` 
                                LEFT JOIN `m_unit_kerja` `UK` ON `UK`.`UK_ID` = `JAB`.`UK_ID` 
                                LEFT JOIN `m_inst_satker` `INTS` ON `INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID` 
                                LEFT JOIN `T_USER` `U` ON `U`.`USERNAME` = `P`.`NIK` 
                                WHERE 
                                `PJ`.`IS_DELETED` = 0 
                                AND `PJ`.`IS_ACTIVE` = 1 
                                AND `P`.`IS_ACTIVE` = 1 
                                AND `PJ`.`IS_CURRENT` = 1 
                                AND `PJ`.`is_wl` = 1 
                                AND $sql_where
                                ORDER BY 
                                `P`.`NAMA`
                            ) AS subquery
                        ) 
                        AND is_active = '1' 
                        AND YEAR(tgl_lapor) = ".$cari['TAHUN_WL']."
                        GROUP BY 
                        id_pn
                    ) 
                    AND is_active = '1' 
                    AND YEAR(tgl_lapor) = ".$cari['TAHUN_WL']."
                    GROUP BY 
                    id_pn      
                  ) aaa ON `aaa`.`id_pn` = `P`.`ID_PN` 
                  LEFT JOIN `T_USER` `U` ON `U`.`USERNAME` = `P`.`NIK` 
                WHERE 
                  `PJ`.`IS_DELETED` = '0' 
                  AND `PJ`.`IS_ACTIVE` = '1' 
                  AND `P`.`IS_ACTIVE` = '1' 
                  AND `PJ`.`IS_CURRENT` = '1' 
                  AND `PJ`.`is_wl` = '1' 
                  AND ".$sql_where." 
                HAVING 
                  (STATUS_KIRIM_FINAL = '".$status_lapor."')
              ) AS table_abc
              
            ";

           $result = $this->db->query($query_total_row);
           $total_rows = $result->row()->jumlah;
        }
        unset($result, $query_total_row);
        //// end ////

        $this->__select_fieldswl($request_from_lhkpn_offline,$aktifwl);

        $this->__set_joinwl($sql_where, null, $aktifwl);

        if($aktifwl==1 && $cari['IS_WL'] != null){
            $sql_where .=  "HAVING(STATUS_KIRIM_FINAL = '".$status_lapor."')";
        }

        $this->db->order_by('P.NAMA');
        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);

        //exit;
        if ($query) {
            $result = $query->result();
        }
        $query->free_result();
       
                // display($this->db->last_query()); die('test');
        $ID_PN = array();
        if ($result) {
            $i = 1 + $offset;
            foreach ($result as $key => $item) {
                $ID_PN[] = $item->ID_PN;
                $result[$key]->NO_URUT = $i;
                $result[$key]->havelhkpn = FALSE;
                $result[$key]->IS_BTN_NON_WL_SHOW = $this->check_lhkpn_pn_non_wl($item->ID_PN, $item->TAHUN_WL);
                $result[$key]->IS_BTN_COPY_WL = $this->is_can_copy_wl($item->ID_PN, $item->TAHUN_WL) ?: false;
                $i++;
            }
        }

        if (!empty($ID_PN)) {
            $ID_PN_SEL = implode($ID_PN, ',');
            $sql = "SELECT * FROM T_LHKPN WHERE ID_PN IN ($ID_PN_SEL)";
            $query = $this->db->query($sql);

            $rs_tlhkpn = FALSE;
            if ($query) {
                $rs_tlhkpn = $query->result();
            }

            $query->free_result();
            
            if ($rs_tlhkpn) {
                foreach ($rs_tlhkpn as $lhkpn) {
                    
                    foreach ($result as $key => $item) {
                        if ($item->ID_PN == $lhkpn->ID_PN) {
                            $result[$key]->havelhkpn = TRUE;
                            $result[$key]->id_user_md5 = substr(md5($item->ID_USER), 5, 8);
                            break;
                        }
                    }
                }
            }
            unset($rs_tlhkpn);
        }

        return (object) array("total_rows" => $total_rows, "result" => $result);
        exit;
    }
    public function cetak_page_PL_AKTIF($instansi, $offset = 0, $cari = NULL, $page_mode = '', $aktifwl = 1, $request_from_lhkpn_offline = FALSE) {
        if ($instansi) {
            $instansi == '' ? " 1 " : $instansi;
        }

        $result = FALSE;
        $total_rows = 0;

        $this->set_additional_join_wl_aktif = FALSE;
        if ($aktifwl) {
            $this->set_additional_join_wl_aktif = TRUE;
        }

        // $uk_where = $this->uker && !is_null($this->uker) ? "AND uk.UK_ID = $this->uker" : "AND 1";
        $this->db->select("count(DISTINCT PJ.ID) as cnt");
        $this->__set_joinwl();

        // $sql_where = $this->load_page_pl_aktif_condition($cari, $aktifwl);
        // $sql_where = " 1 " . $this->__cari($cari);
        if ($cari->text && $cari->text != '') {

            $condition = " AND (P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') ) ";

            if ($this->is_tracking_lhkpn) {

                $condition = " AND (P.NAMA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR "
                    . "P.NIK LIKE CONCAT('%', '" . $cari->text . "' , '%') ) ";
            }

            if (!$this->is_lhkpn_offline) {
                $condition = " AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR "
                        . "PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR "
                        . "P.NAMA LIKE CONCAT('%', '" . $cari->text . "' , '%') OR "
                        . "P.NIK LIKE CONCAT('%', '" . $cari->text . "' , '%') OR "
                        . "P.EMAIL LIKE CONCAT('%', '" . $cari->text . "' , '%') OR "
                        . "P.NO_HP LIKE CONCAT('%', '" . $cari->text . "' , '%') OR "
                        . "PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari->text . "' , '%') )";
            }
        }
        else{
            $condition = " ";
        }
        $sql_where = " 1 " . $condition;
        $uk_where = $this->uker && !is_null($this->uker) ? " AND uk.UK_ID = '" . $this->uker . "' " : "  ";
        
        if (!$this->is_lhkpn_offline) {
            // $sql_where .= $this->load_page_PL_AKTIF_cari_advance($uk_where);
            $cari_advance = $this->input->get('CARI');

            $condition_is_wl = " 1 ";
            $condition_tahun_wl = " 1 ";
            $condition_instansi = " 1 ";
            $condition_uk = " 1 ";

            $where = "  ";

            if ($cari) {

                $condition_tahun_wl = $cari->tahun != '' ? "   PJ.TAHUN_WL='" . $cari->tahun . "'  " : $condition_tahun_wl;
                $condition_instansi = $cari->instansi != '' ? "   INTS.INST_SATKERKD = '" . $cari->instansi . "'   " : " 1 ";
                $condition_uk = $cari->uk != '' ? "   uk.UK_ID ='" . $cari->uk . "'   " : $condition_uk;
                if($aktifwl != 1) {
                    $condition_is_wl = $cari->is_wl == '' ? "   1   " :
                    $condition_is_wl = $cari->is_wl != '1' ? "   PJ.tgl_kirim is null   " :
                    $condition_is_wl = $cari->is_wl != '0' ? "   PJ.tgl_kirim is not null   " :
                    $condition_is_wl = $cari->is_wl != '2' ? "   1   " :
                    $condition_is_wl;
            }
                
            } else {
                if ($this->role == 1 || $this->role == 2) {
                    if ($condition_instansi == " 1 ") {
                        $condition_instansi = " INTS.INST_SATKERKD = 3122 ";
                    }
                    if ($condition_uk == " 1 ") {
                        $condition_uk = " uk.UK_ID = 12928 ";
                    }
                }
            }

            $where .= " AND " . $condition_is_wl . " and " . $condition_tahun_wl;
            $where .= " AND " . $condition_instansi . " and " . $condition_uk . " $uk_where     ";

            $sql_where .= $where;
        }

        // $sql_where .= $this->load_page_pl_aktif_condition_by_role($aktifwl, $uk_where);

        $cari_advance = $this->input->get('CARI');

        $where = "";

        if ($aktifwl == 1) {
            $where .= "  and is_wl= 1  AND `PJ`.`ID_STATUS_AKHIR_JABAT` IN(0,5)";
        }

        if ($this->role != 1 && $this->role != 2 && $this->ins !== NULL) {
            if ($aktifwl == 1) {
                $where .= "  AND INTS.INST_SATKERKD = $this->ins   ";
            } else {
                if ($is_wl_deleted == '2'){
                    $where .= " AND INTS.INST_SATKERKD = $this->ins and is_wl= 2 AND `PJ`.`ID_STATUS_AKHIR_JABAT` = 99 $uk_where   ";
                } else {
                    $where .= " and (is_wl= 0  OR pj.`is_wl` IS NULL) AND INTS.INST_SATKERKD = $this->ins  AND PJ.ID_STATUS_AKHIR_JABAT IN(0,5) ";
                }
            }
        } else if (($this->role == 1 || $this->role == 2 || $this->role == 3 || $this->role == 7 || $this->role == 10 || $this->role == 13 || $this->role == 14 || $this->role == 18) AND ( $this->ins == "" OR $this->ins == NULL)) {
            //adding by iqbal jika login sebagai admin_kpk default yang tampil pegawai KPK
            // echo "n";exit;
            if ($aktifwl != 1) {
                if ($is_wl_deleted == '2'){
                    $where .= " and is_wl= 2 AND `PJ`.`ID_STATUS_AKHIR_JABAT` = 99 $uk_where   ";
                }else{
                    $where .= " and is_wl= 0 AND `PJ`.`ID_STATUS_AKHIR_JABAT` IN(0,5) $uk_where   ";
                }
            }
        }

        // return $sql_where;
        // $sql_where .= $this->load_page_pl_aktif_condition_by_role($aktifwl, $uk_where);
        $sql_where .= $where;
        //        echo $sql_where;exit;

        
        $is_aktif_tlhkpn = " AND subtbllhkpn.IS_ACTIVE = '1' AND subtbllhkpn.IS_ACTIVE <> '0'  ";


        $sql_where .= "  $uk_where ";

        $this->db->where($sql_where);

        $query = $this->db->get('t_pn P');
        
        if ($query) {
            $result = $query->row();

            if ($result) {
                $total_rows = $result->cnt;
            }
        }

        $query->free_result();
        unset($result, $query);
        $select_fields = "DISTINCT P.ID_PN, PJ.ID, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, P.NIK, "
                . "TRIM(CONCAT(IF(ISNULL(P.GELAR_DEPAN),'',P.GELAR_DEPAN),' ',P.NAMA,IF(ISNULL(P.GELAR_BELAKANG) OR P.GELAR_BELAKANG = '', '', ', '),IF(ISNULL(P.GELAR_BELAKANG),'',P.GELAR_BELAKANG))) AS NAMA, IF(PJ.`ID_STATUS_AKHIR_JABAT`=0, 'Online','Offline')AS IS_WL, ";

        $this->__select_fieldswl($request_from_lhkpn_offline, $aktifwl);

        $this->__set_joinwl($sql_where, $cari->tahun, $aktifwl);
        
        if($aktifwl == 1 && $cari->is_wl != null){     
            $status_lapor = ($cari->is_wl==0)?"Belum":"Sudah";
            $sql_where .=  "HAVING(STATUS_KIRIM_FINAL = '".$status_lapor."')";
        }

        $this->db->order_by('P.NAMA');
        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);

        if ($query) {
            $result = $query->result();
        }
        return (object) array("result" => $result);
        // exit;
    }

    
    public function cetak_page_PN_wl_online($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') {
        
        // $cari_advance = $this->input->get('CARI');
        $result = FALSE;
        $total_rows = 0;
        $uk_where = $this->uker && !is_null($this->uker) ? "AND uk.UK_ID = $this->uker" : "AND 1";
        $this->db->select("count(P.ID_PN) as cnt");
        $this->__set_join();
        $sql_where = " 1 " . $this->__cari($cari);
        
        if ($this->role != 1 && $this->role != 2) {
            $sql_where .= $this->ins && !is_null($this->ins) ? " AND INTS.INST_SATKERKD = $this->ins" : "AND 1";
            //			if($cari_advance['UNIT_KERJA'] != ""){
            if ($cari) {
                $condition_instansi = $cari->instansi != '' ? "   INTS.INST_SATKERKD = '" . $cari->instansi . "'   " : " 1 ";
                $condition_uk = $cari->uk != '' ? "   uk.UK_ID ='" . $cari->uk . "' " : " 1";
                //				$condition_wl = $cari_advance["IS_WL"] != '' ? "   pj.is_wl ='". $cari_advance["IS_WL"] ."' ": " 1";
                $condition_wl = $cari->is_wl == '' ? "   1   " :
                        $condition_wl = $cari->is_wl != '1' ? "   PJ.tgl_kirim is null   " :
                        $condition_wl = $cari->is_wl != '0' ? "   PJ.tgl_kirim is not null   " :
                        $condition_wl = $cari->is_wl != '2' ? "   1   " :
                        $condition_wl;
                $condition_tahun_wl = $cari->tahun != '' ? "   PJ.TAHUN_WL='" . $cari->tahun . "'  " : "1";
                $condition_cari_ = $cari->text != '' ? "   (p.NAMA like '%" . $cari->text . "%' OR p.NIK like '%" . $cari->text . "%') " : " 1";
                //$sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
                $sql_where .= " and " . $condition_tahun_wl . " and " . $condition_wl . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
            } else {
                if ($this->role == 1 && $this->role == 2) {
                    $sql_where .= "AND INTS.INST_SATKERKD = 3122";
                }
                $sql_where .= " $uk_where";
            }
        } else if ($this->role == 1 || $this->role == 2) {


            //if($cari_advance['INSTANSI'] != "" || $cari_advance['UNIT_KERJA'] != ""){
            if ($cari) {
                $condition_instansi = $cari->instansi != '' ? "   INTS.INST_SATKERKD = '" . $cari->instansi . "'   " : " 1 ";
                $condition_uk = $cari->uk != '' ? "   uk.UK_ID ='" . $cari->uk . "' " : " 1";
                //				$condition_wl = $cari_advance["IS_WL"] != '' ? "   pj.is_wl ='". $cari_advance["IS_WL"] ."' ": " 1";
                $condition_wl = $cari->is_wl == '' ? "   1   " :
                        $condition_wl = $cari->is_wl != '1' ? "   PJ.tgl_kirim is null   " :
                        $condition_wl = $cari->is_wl != '0' ? "   PJ.tgl_kirim is not null   " :
                        $condition_wl = $cari->is_wl != '2' ? "   1   " :
                        $condition_wl;
                $condition_tahun_wl = $cari->tahun != '' ? "   PJ.TAHUN_WL='" . $cari->tahun . "'  " : "1";
                $condition_cari_ = $cari->text != '' ? "   (p.NAMA like '%" . $cari->text . "%' OR p.NIK like '%" . $cari->text . "%') " : " 1";
                //$sql_where .= " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
                $sql_where .= " and ". $condition_instansi ." and " . $condition_tahun_wl . " and " . $condition_wl . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
            } else {
                $sql_where .= "AND INTS.INST_SATKERKD = 3122";
                $sql_where .= " $uk_where";
            }
        }
        
        $sql_where .= " AND PJ.ID_STATUS_AKHIR_JABAT NOT IN (10, 11, 15) AND PJ.`is_wl` = '1' $uk_where "; //status active
        $this->db->where($sql_where);

        $query = $this->db->get('t_pn P');

        $this->__select_fields();

        $this->__set_join();
        $this->db->order_by("P.NAMA", "asc");
        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);

        if ($query) {
            $result = $query->result();
        }
        
        return (object) array("result" => $result);
    }



    public function load_page_tracking_pn_wl($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '') { 

        $cari_advance = $this->input->get('CARI');

        $result = FALSE;
        $total_rows = 0;
        $uk_where = $this->uker && !is_null($this->uker) ? "AND uk.UK_ID = $this->uker" : "AND 1";
        $this->db->select("count(P.ID_PN) as cnt");
        $this->__set_join_tracking();
        $sql_where = " 1 " . $this->__cari($cari);
        if ($this->role != 1 && $this->role != 2 && $this->role != 31) {
            $sql_where .= $this->ins && !is_null($this->ins) ? " AND INTS.INST_SATKERKD = $this->ins" : "AND 1";
            if ($cari_advance) {
              $condition_nama = $cari_advance["NAMA"] != '' ? "   p.NAMA like '%" . $cari_advance["NAMA"] . "%'   " : " 1 ";
              $condition_nik = $cari_advance["NIK"] != '' ? "   p.NIK like '" . $cari_advance["NIK"] . "%'   " : " 1 ";
              $condition_tgl_lahir = $cari_advance["TGL_LAHIR"] != '' ? "   p.TGL_LAHIR = '" . $cari_advance["TGL_LAHIR"] . "'   " : " 1 ";
              $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : "1";
              $condition_email = $cari_advance["EMAIL"] != '' ? "   p.EMAIL = '" . $cari_advance["EMAIL"] . "'  " : "1";
              $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'  " : "1";
              $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   JAB.UK_ID = '" . $cari_advance["UNIT_KERJA"] . "'  " : "1";

                
              $sql_where .= " AND " . $condition_tgl_lahir . " and " . $condition_tahun_wl . " and "  . $condition_nik . " and " . $condition_nama . "  and " . $condition_email . "  and " . $condition_instansi . "  and " . $condition_uk . " $uk_where     ";

                // $sql_where .= " AND " . $condition_tgl_lahir . " and " . $condition_tahun_wl . " and "  . $condition_nik . "  and " . $condition_nama . "  and " . $condition_email . " $uk_where     ";
                //$sql_where .= " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
                $debug = "masuk atas if";
            } else {
            //   $sql_where .= "AND p.NIK = '1301050505620004'";
                // $sql_where .= "AND INTS.INST_SATKERKD = 3122";
              $sql_where .= " $uk_where";
              $debug = "masuk atas else";
            }
        }
        //adding by iqbal
        else if ($this->role == 1 || $this->role == 2 || $this->role == 31) {


//			if($cari_advance['INSTANSI'] != "" || $cari_advance['UNIT_KERJA'] != ""){
            if ($cari_advance) {
                $condition_nama = $cari_advance["NAMA"] != '' ? "   p.NAMA like '%" . $cari_advance["NAMA"] . "%'   " : " p.NAMA!='' ";
                $condition_nik = $cari_advance["NIK"] != '' ? "   p.NIK like '" . $cari_advance["NIK"] . "%'   " : "  p.NIK!=''  ";
                $condition_tgl_lahir = $cari_advance["TGL_LAHIR"] != '' ? "   p.TGL_LAHIR = '" . $cari_advance["TGL_LAHIR"] . "'   " : " 1 ";
                $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : "1";
                $condition_email = $cari_advance["EMAIL"] != '' ? "   p.EMAIL = '" . $cari_advance["EMAIL"] . "'  " : "1";
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   INTS.INST_SATKERKD = '" . $cari_advance["INSTANSI"] . "'  " : "1";
                $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   JAB.UK_ID = '" . $cari_advance["UNIT_KERJA"] . "'  " : "1";
                
                $sql_where .= " AND " . $condition_tgl_lahir . " and " . $condition_tahun_wl . " and "  . $condition_nik . " and " . $condition_nama . "  and " . $condition_email . "  and " . $condition_instansi .  "  and " . $condition_uk . " $uk_where     ";
                // $sql_where .= " AND " . $condition_tgl_lahir . " and " . $condition_tahun_wl . " and "  . $condition_nik . " and " . $condition_nama . "  and " . $condition_email . " $uk_where     ";
                // $condition = " AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                //         . "PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                //         . "P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                //         . "P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                //         . "P.EMAIL LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                //         . "P.NO_HP LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                //         . "PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";
                $debug = "masuk bawah if";
            } else {
              $sql_where .= "AND p.NIK = '1301050505620004'";
                // $sql_where .= "AND INTS.INST_SATKERKD = 3122";
                $sql_where .= " $uk_where";
                $debug = "masuk bawah else";
            }
        }
        $this->db->where($sql_where);

        $query = $this->db->get('t_pn P');

        if ($query) {
            $result = $query->row();

            if ($result) {
                $total_rows = $result->cnt;
            }
        }
    
        $query->free_result();
        unset($result, $query);

        $this->__select_fields_tracking();

        $this->__set_join_tracking();
        $this->db->order_by("P.NAMA", "asc");
        
        // $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);
        $query = $this->db->get_where('t_pn P', $sql_where);

        if ($query) {
            $result = $query->result();
        }
        $query->free_result();

        $ID_PN = array();

        $for = 0;
        $tambahan = 0;
        $result_2 = [];

        if ($result) {
            // $i = 1 + $offset;
            $i = 1;
            foreach ($result as $key => $item) {

                if($item->P_IS_ACTIVE==1 and $item->IS_DELETED==0 and $item->IS_CURRENT==1){
                    if($item->ID_STATUS_AKHIR_JABAT==10 and $item->IS_CALON==1){
                        $menu = "Verifikasi Data Individual";
                        $sub_menu = "Penambahan Calon PN/WL";
                    }elseif($item->ID_STATUS_AKHIR_JABAT==10 and $item->IS_CALON==0){
                        $menu = "Verifikasi Data Individual";
                        $sub_menu = "Penambahan PN/WL";
                    }elseif($item->ID_STATUS_AKHIR_JABAT==11){
                        $menu = "Verifikasi Data Individual";
                        $sub_menu = "PN/WL Online";
                    }elseif($item->IS_WL==90){
                        $menu = "Verifikasi Data Individual";
                        $sub_menu = "Wajib Lapor";
                    }
                    elseif($item->IS_WL==99){
                        $menu = "Verifikasi Data Individual";
                        $sub_menu = "Non Wajib Lapor";
                    }
                    elseif($item->ID_STATUS_AKHIR_JABAT==0){
                        $menu = "PN/WL Online";
                        $sub_menu = "-----";
                    }
                    elseif($item->ID_STATUS_AKHIR_JABAT==5){
                        $menu = "PN/WL Offline";
                        $sub_menu = "-----";
                    }

                    if($menu!=null){
                      $ID_PN[] = $item->ID_PN;
                      $result_2[$for] = new \stdClass();
                      $result_2[$for]->NO_URUT = $i;
                      $result_2[$for]->havelhkpn = FALSE;
                      $result_2[$for]->TGL_LAHIR = tgl_format($item->TGL_LAHIR);
                      $result_2[$for]->MENU = $menu;
                      $result_2[$for]->SUB_MENU = $sub_menu;
                      $result_2[$for]->TAHUN_WL = $item->TAHUN_WL;
                      $result_2[$for]->NIK = $item->NIK;
                      $result_2[$for]->NAMA = $item->NAMA;
                      $result_2[$for]->INST_NAMA = $item->INST_NAMA;
                      $result_2[$for]->UNIT_KERJA = $item->UNIT_KERJA;
                      $i++;
                      $for++;
                    }
                  }else{
                      $menu = "";
                      $sub_menu = "";
                  }

                    if($item->P_IS_ACTIVE==-1){
                        $menu_2 = "Hubungi Admin KPK";
                        $sub_menu_2 = "-----";
                    }elseif($item->IS_WL==1){
                        if($item->ID_STATUS_AKHIR_JABAT==0 ||$item->ID_STATUS_AKHIR_JABAT==5){
                          $menu_2 = "Daftar Wajib Lapor";
                          $sub_menu_2 = "-----";
                        }
                    }elseif($item->IS_WL==0){
                        if($item->P_IS_ACTIVE==0){
                           $menu_2 = "Daftar Non Wajib Lapor (Delete)";
                        }else{
                           $menu_2 = "Daftar Non Wajib Lapor";
                        }
                        $sub_menu_2 = "-----";
                    }elseif($item->IS_WL==2){
                        $menu_2 = "Daftar Non Wajib Lapor (Delete)";
                        $sub_menu_2 = "-----";
                    }elseif($item->ID_STATUS_AKHIR_JABAT==1){
                        $menu_2 = "Rangkap Jabatan";
                        $sub_menu_2 = "-----";
                    }

                    if($menu_2!=null){
                      $result_2[$for] = new \stdClass();
                      $result_2[$for]->NO_URUT = $i;
                      $result_2[$for]->havelhkpn = FALSE;
                      $result_2[$for]->TGL_LAHIR = tgl_format($item->TGL_LAHIR);
                      $result_2[$for]->MENU = $menu_2;
                      $result_2[$for]->SUB_MENU = $sub_menu_2;
                      $result_2[$for]->TAHUN_WL = $item->TAHUN_WL;
                      $result_2[$for]->NIK = $item->NIK;
                      $result_2[$for]->NAMA = $item->NAMA;
                      $result_2[$for]->INST_NAMA = $item->INST_NAMA;
                      $result_2[$for]->UNIT_KERJA = $item->UNIT_KERJA;
                      $i++;
                      $for++;
                    }


                    $tambahan++;

            }

        // $total_rows = $total_rows + $tambahan;
        $total_rows = count($result_2);

        }

        else{
               $result_2 = $result;
        }

        if (!empty($ID_PN)) {
            $ID_PN_SEL = implode($ID_PN, ',');
            $sql = "SELECT * FROM T_LHKPN WHERE ID_PN IN ($ID_PN_SEL)";
            $query = $this->db->query($sql);

            $rs_tlhkpn = FALSE;
            if ($query) {
                $rs_tlhkpn = $query->result();
            }

            $query->free_result();

            if ($rs_tlhkpn) {
                foreach ($rs_tlhkpn as $lhkpn) {

                    foreach ($result as $key => $item) {
                        if ($item->ID_PN == $lhkpn->ID_PN) {
                            $result[$key]->havelhkpn = TRUE;
                            $result[$key]->id_user_md5 = substr(md5($item->ID_USER), 5, 8);
                            break;
                        }
                    }
                }
            }
            unset($rs_tlhkpn);
        }

        $result_2 = array_slice($result_2, $offset, $rowperpage);

        return (object) array("total_rows" => $total_rows, "result" => $result_2, "query" => $sql_where, "cari" => $cari_advance);
    }


    private function __select_fields_tracking() {

        $select_fields = "DISTINCT P.ID_PN,
            JAB.NAMA_JABATAN N_JAB, SUK.SUK_NAMA N_SUK, UK.UK_NAMA N_UK, INTS.INST_NAMA,
            P.NIK, P.TGL_LAHIR,
            TRIM(CONCAT(IF(ISNULL(P.GELAR_DEPAN),'',P.GELAR_DEPAN),' ',P.NAMA,IF(ISNULL(P.GELAR_BELAKANG) OR P.GELAR_BELAKANG = '', '', ', '),IF(ISNULL(P.GELAR_BELAKANG),'',P.GELAR_BELAKANG))) AS NAMA,
            PJ.ID_STATUS_AKHIR_JABAT, PJ.TAHUN_WL,
            PJ.IS_ACTIVE,PJ.IS_DELETED,PJ.IS_CURRENT,PJ.IS_CALON,PJ.IS_WL,
            PJ.ID ID_JAB,
            PJ.UNIT_KERJA,
            PJ.SUB_UNIT_KERJA,
            U.ID_USER,
            P.IS_ACTIVE P_IS_ACTIVE";

    // buat select is_active ke t_pn;
        $this->db->select($select_fields, FALSE);

//         $select_fields = "DISTINCT P.ID_PN,
//             JAB.NAMA_JABATAN N_JAB, SUK.SUK_NAMA N_SUK, UK.UK_NAMA N_UK, INTS.INST_NAMA,
//             P.NIK,
//             TRIM(CONCAT(IF(ISNULL(P.GELAR_DEPAN),'',P.GELAR_DEPAN),' ',P.NAMA,IF(ISNULL(P.GELAR_BELAKANG) OR P.GELAR_BELAKANG = '', '', ', '),IF(ISNULL(P.GELAR_BELAKANG),'',P.GELAR_BELAKANG))) AS NAMA,
//             PJ.ID ID_JAB,
//             PJ.UNIT_KERJA,
//             PJ.SUB_UNIT_KERJA,
//             U.ID_USER,
//             PJ.ID_STATUS_AKHIR_JABAT,p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR, PJ.TAHUN_WL";

//         $this->db->select($select_fields, FALSE);


        return;
    }

    private function __set_join_tracking() {

//         $this->db->join('T_PN_JABATAN PJ', 'P.ID_PN = PJ.ID_PN', 'LEFT');

        // $where_join_t_pn_jabatan = "`PJ`.`ID_STATUS_AKHIR_JABAT` = '5' AND "
        //         . "`P`.`IS_ACTIVE` = '1' AND "
        //         . "`PJ`.`IS_WL` = '1' AND "
        //         . "`PJ`.`IS_CURRENT` = '1' ";
        //
        // $this->db->where($where_join_t_pn_jabatan);




        $this->db->join('T_USER U', 'U.USERNAME = P.NIK', 'LEFT');
        $this->db->join('T_PN_JABATAN PJ', 'P.ID_PN = PJ.ID_PN', 'LEFT');
        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN AND JAB.IS_ACTIVE <> 0', 'left');
        $this->db->join('m_sub_unit_kerja SUK', 'SUK.SUK_ID = JAB.SUK_ID', 'left');
        $this->db->join('m_unit_kerja UK', 'UK.UK_ID = JAB.UK_ID', 'left');
        $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = UK.UK_LEMBAGA_ID', 'left');

        $where_join_t_pn_jabatan = "`PJ`.`IS_CURRENT` = '1' AND "
                                  . "`PJ`.`ID_STATUS_AKHIR_JABAT` != '1' ";

                    $this->db->where($where_join_t_pn_jabatan);
    }







    /////////////////////////////////REGULASI//////////////////////////////
    public function load_data_regulasi($offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $result = FALSE;
        $total_rows = 0;
        $cari_advance = $this->input->get('CARI');
        $sql_where = " 1=1 AND ";
        if($this->role == '3' || $this->role == '4'){
            $cari_advance["INSTANSI"] = $this->ins;
        }
        if($cari_advance["STATUS"]==9){
            $cari_advance["STATUS"]=null;
        }
        if ($cari_advance) {
            $condition_instansi = array_key_exists("INSTANSI", $cari_advance) && $cari_advance["INSTANSI"] != '' ? "tlr.INST_SATKERKD ='" . $cari_advance["INSTANSI"] . "'" : " 1";
            $condition_cari_keyword = array_key_exists("TEXT", $cari_advance) && $cari_advance["TEXT"] != '' ? "  tlr.NOMOR_REGULASI like '%" . $cari_advance["TEXT"] . "%'" : " 1";
            $condition_status = array_key_exists("STATUS", $cari_advance) && $cari_advance["STATUS"] != '' ? "tlr.STATUS = '". $cari_advance["STATUS"] . "'" : " 1";
            $sql_where .= "  " . $condition_instansi . " and " . $condition_cari_keyword . " and ". $condition_status . " ";
        }
        $sql_where .= " AND tlr.IS_ACTIVE = 1 "; 
        /////cek total baris//////
        $this->db->select("count(ID_REGULASI) as cnt");
        $this->db->from('t_lhkpn_regulasi tlr');
        $this->db->where($sql_where);
        $queryCount = $this->db->get();
        if ($queryCount) {
            $result = $queryCount->row();
            if ($result) {
                $total_rows = $result->cnt;
            }
        }
        /////ambil data///////
        $this->db->select("tlr.*,mis.INST_NAMA");
        $this->db->from('t_lhkpn_regulasi tlr');
        $this->db->where($sql_where);
        $this->db->join('m_inst_satker mis', 'mis.INST_SATKERKD = tlr.INST_SATKERKD');
        $this->db->order_by('tlr.ID_REGULASI', 'desc');
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
                $i++;
            }
        }
        return (object) array("total_rows" => $total_rows, "result" => $result);
        exit;
    }

    public function save_data_regulasi($action, $id_regulasi = null,  $data = null){
     
        if($this->role == '3' || $this->role == '4'){
            $instansi = $this->ins;
            if($this->role == '4'){
                $uk_id = $this->uker;
            }else{
                if($this->input->post('UK_ID', TRUE)){
                    $uk_id = $this->input->post('UK_ID', TRUE);
                }else{
                    $uk_id = null;
                }
            }
        }else{
            $instansi = $this->input->post('INST_SATKERKD', TRUE);
            if($this->input->post('UK_ID', TRUE)){
                $uk_id = $this->input->post('UK_ID', TRUE);
            }else{
                $uk_id = null;
            }
        }

        

        switch($action){
            case "doinsert":
                $check_nomor_regulasi = $this->db->where("NOMOR_REGULASI",$this->input->post('nomor_regulasi', TRUE))->count_all_results("t_lhkpn_regulasi");
                if($check_nomor_regulasi){
                    return 9;
                }
                $state = array(
                    'INST_SATKERKD' => $instansi,
                    'UK_ID' => $uk_id,
                    'NOMOR_REGULASI' => $this->input->post('nomor_regulasi', TRUE),
                    'PENGELOLA_LHKPN' => textarea_to_html($this->input->post('pengelola_lhkpn', TRUE)),
                    'SANKSI' => textarea_to_html($this->input->post('sanksi', TRUE)),
                    'WAJIB_LAPOR' =>textarea_to_html($this->input->post('wajib_lapor', TRUE)),
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
               
                $result = $this->db->insert('T_LHKPN_REGULASI', $state);
                $last_id = $this->db->insert_id();
                ng::logActivity("Tambah regulasi untuk, nomor_regulasi = ".$this->input->post('nomor_regulasi', TRUE).", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$last_id);
                break;
            case "doupdate":

                if($id_regulasi != null){
                    $ID = $id_regulasi;
                    $store_minio = storageDiskMinio();
                }else{ 
                    $ID = $this->input->post('ID_REGULASI', TRUE);
                }
               
                $check_nomor_regulasi = $this->db->where("NOMOR_REGULASI = '".$this->input->post('nomor_regulasi', TRUE)."' AND ID_REGULASI != ".$ID)->count_all_results("t_lhkpn_regulasi");

                if($check_nomor_regulasi){
                    return 9;
                }

                $state = array(
                    'INST_SATKERKD' => $instansi,
                    'UK_ID' => $uk_id,
                    'NOMOR_REGULASI' => $this->input->post('nomor_regulasi', TRUE),
                    'PENGELOLA_LHKPN' => textarea_to_html($this->input->post('pengelola_lhkpn', TRUE)),
                    'SANKSI' => textarea_to_html($this->input->post('sanksi', TRUE)),
                    'WAJIB_LAPOR' => textarea_to_html($this->input->post('wajib_lapor', TRUE)),
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );

                if (!is_null($id_regulasi)) {
                    $state = array_merge($state, ['FILE_REGULASI' => $data, 'STORAGE_MINIO' => $store_minio]);
                }
                
                $this->db->where('ID_REGULASI = '.$ID);
                
                $result = $this->db->update('t_lhkpn_regulasi',$state);
                ng::logActivity("Edit regulasi untuk, nomor_regulasi = ".$this->input->post('nomor_regulasi', TRUE).", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$ID);
                break;
            case "dodelete":
                $ID = $this->input->post('ID_REGULASI', TRUE);
                $this->db->where('ID_REGULASI = '.$ID);
                // $result = $this->db->delete('t_lhkpn_regulasi');
                $result = $this->db->update('t_lhkpn_regulasi',["IS_ACTIVE"=>"-1"]);
                $get_regulasi = $this->Mglobal->get_data_by_id('t_lhkpn_regulasi','ID_REGULASI',$ID,false,true);
                ng::logActivity("Hapus regulasi untuk, nomor_regulasi = ".$get_regulasi->NOMOR_REGULASI.", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$ID);
                break;
            case "doapprove":
                $ID = $this->input->post('ID_REGULASI', TRUE);
                $get_regulasi = $this->Mglobal->get_data_by_id('t_lhkpn_regulasi','ID_REGULASI',$ID,false,true);
                if($get_regulasi->STATUS==0){
                    $status = 1;
                }else{
                    $status = 0;
                }
               $user = array(
                    'STATUS' => $status,
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->where('ID_REGULASI = '.$ID);
                $result = $this->db->update('t_lhkpn_regulasi',$user);
                ng::logActivity("Verifikasi regulasi untuk, nomor_regulasi = ".$get_regulasi->NOMOR_REGULASI.", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$ID);
                break;   
        }
        return $result;
    }




    ///////////////////////////SOSIALISASI///////////////////////////////////
    public function load_data_sosialisasi($offset = 0, $cari = NULL, $rowperpage = 10, $limit_mode = false) {
        $result = FALSE;
        $total_rows = 0;
        $cari_advance = $this->input->get('CARI');;
        $sql_where = " 1=1 AND ";
        if($this->role == '3' || $this->role == '4'){
            $cari_advance["INSTANSI"] = $this->ins;
        }
        if($this->role == '4'){
            $cari_advance["UK_ID"] = $this->uker ;
        }
        if($cari_advance["STATUS"]==9){
            $cari_advance["STATUS"]=null;
        }
        if($cari_advance["BIMTEK"]==9){
            $cari_advance["BIMTEK"]=null;
        }
        if ($cari_advance) {
            $condition_uk = array_key_exists("UK_ID", $cari_advance) && $cari_advance["UK_ID"] != '' ? "( tlr.UK_ID ='" . $cari_advance["UK_ID"] . "' OR tlr.UK_ID IS NULL )" : " 1";
            $condition_instansi = array_key_exists("INSTANSI", $cari_advance) && $cari_advance["INSTANSI"] != '' ? "tlr.INST_SATKERKD ='" . $cari_advance["INSTANSI"] . "'" : " 1";
            $condition_bimtek = array_key_exists("BIMTEK", $cari_advance) && $cari_advance["BIMTEK"] != '' ? "tlr.BIMTEK ='" . $cari_advance["BIMTEK"] . "'" : " 1";
            $condition_tanggal = array_key_exists("TANGGAL", $cari_advance) && $cari_advance["TANGGAL"] != '' ? "tlr.TANGGAL = '" . to_mysql_date($cari_advance["TANGGAL"]) . "'" : " 1";
            $condition_cari_keyword = array_key_exists("TEXT", $cari_advance) && $cari_advance["TEXT"] != '' ? "  tlr.PELAKSANA like '%" . $cari_advance["TEXT"] . "%'" : " 1";
            $condition_status = array_key_exists("STATUS", $cari_advance) && $cari_advance["STATUS"] != '' ? "tlr.STATUS = '". $cari_advance["STATUS"] . "'" : " 1";
            $sql_where .= "  " . $condition_instansi . " AND " . $condition_bimtek . " AND " . $condition_uk . " AND " . $condition_tanggal . " AND " . $condition_status . " AND " . $condition_cari_keyword . "   ";
        }
        $sql_where .= " AND tlr.IS_ACTIVE = 1 "; 
        /////cek total baris//////
        $this->db->select("count(ID_SOSIALISASI) as cnt");
        $this->db->from('t_lhkpn_sosialisasi tlr');
        $this->db->where($sql_where);
        $queryCount = $this->db->get();
        if ($queryCount) {
            $result = $queryCount->row();
            if ($result) {
                $total_rows = $result->cnt;
            }
        }
        

        /////ambil data//////////
        $this->db->select("tlr.*,muk.UK_NAMA,mis.INST_NAMA");
        $this->db->from('t_lhkpn_sosialisasi tlr');
        $this->db->where($sql_where);
        $this->db->join('m_inst_satker mis', 'mis.INST_SATKERKD = tlr.INST_SATKERKD','LEFT');
        $this->db->join('m_unit_kerja muk', 'muk.UK_ID = tlr.UK_ID','LEFT');
        $this->db->order_by('tlr.ID_SOSIALISASI', 'desc');
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
                if($result[$key]->BIMTEK==1){
                    $result[$key]->BIMTEK = "e-Filling";
                }else if($result[$key]->BIMTEK==2){
                    $result[$key]->BIMTEK = "e-Registration";
                }else if($result[$key]->BIMTEK==3){
                    $result[$key]->BIMTEK = "Regulasi";
                }else if($result[$key]->BIMTEK==4){
                    $result[$key]->BIMTEK = "Rakor Monev";
                }else{
                    $result[$key]->BIMTEK = "----";
                }

                if(!$result[$key]->UK_NAMA){
                    $result[$key]->UK_NAMA = "SEMUA UNIT KERJA";
                }
                
                $result[$key]->TANGGAL = tgl_format($result[$key]->TANGGAL);
                $result[$key]->NO_URUT = $i;
                $i++;
            }
        }
        return (object) array("total_rows" => $total_rows, "result" => $result);
        exit;
    }



    public function save_data_sosialisasi($action){
        if($this->role == '3' || $this->role == '4'){
            $instansi = $this->ins;
            if($this->role == '4'){
                $uk_id = $this->uker;
            }else{
                if($this->input->post('UK_ID', TRUE)){
                     $uk_id = $this->input->post('UK_ID', TRUE);
                }else{
                     $uk_id = null;
                }
               
            }
        }else{
            $instansi = $this->input->post('INST_SATKERKD', TRUE);
            if($this->input->post('UK_ID', TRUE)){
                $uk_id = $this->input->post('UK_ID', TRUE);
            }else{
                $uk_id = null;
            }
        }


        switch($action){
            case "doinsert":
                $state = array(
                    'INST_SATKERKD' => $instansi,
                    'UK_ID' => $uk_id,
                    'BIMTEK' => $this->input->post('bimtek', TRUE),
                    'TEMPAT' => textarea_to_html($this->input->post('tempat', TRUE)),
                    'TANGGAL' => to_mysql_date($this->input->post('tanggal', TRUE)),
                    'WAKTU_PELAKSANAAN' =>textarea_to_html($this->input->post('waktu_pelaksanaan', TRUE)),
                    'PELAKSANA' => textarea_to_html($this->input->post('pelaksana', TRUE)),
                    'JUMLAH_PESERTA' => textarea_to_html($this->input->post('jumlah_peserta', TRUE)),
                    'STATUS' => 0,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $result = $this->db->insert('t_lhkpn_sosialisasi', $state);
                $last_id = $this->db->insert_id();
                ng::logActivity("Tambah sosialisasi untuk, bimtek = ".$this->input->post('bimtek', TRUE).", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$last_id);
                break;
            case "doupdate":
                $state = array(
                    'INST_SATKERKD' => $instansi,
                    'UK_ID' => $uk_id,
                    'BIMTEK' => $this->input->post('bimtek', TRUE),
                    'TEMPAT' => textarea_to_html($this->input->post('tempat', TRUE)),
                    'TANGGAL' => to_mysql_date($this->input->post('tanggal', TRUE)),
                    'WAKTU_PELAKSANAAN' =>textarea_to_html($this->input->post('waktu_pelaksanaan', TRUE)),
                    'PELAKSANA' => textarea_to_html($this->input->post('pelaksana', TRUE)),
                    'JUMLAH_PESERTA' => textarea_to_html($this->input->post('jumlah_peserta', TRUE)),
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $ID = $this->input->post('ID_SOSIALISASI', TRUE);
                $this->db->where('ID_SOSIALISASI = '.$ID);
                $result = $this->db->update('t_lhkpn_sosialisasi',$state);
                ng::logActivity("Edit sosialisasi untuk, bimtek = ".$this->input->post('bimtek', TRUE).", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$ID);
                break;
            case "dodelete":
                $ID = $this->input->post('ID_SOSIALISASI', TRUE);
                $this->db->where('ID_SOSIALISASI = '.$ID);
                // $result = $this->db->delete('t_lhkpn_sosialisasi');
                $result = $this->db->update('t_lhkpn_sosialisasi',["IS_ACTIVE"=>"-1"]);
                $get_sosialisasi = $this->Mglobal->get_data_by_id('t_lhkpn_sosialisasi','ID_SOSIALISASI',$ID,false,true);
                ng::logActivity("Hapus sosialisasi untuk, bimtek = ".$get_sosialisasi->BIMTEK.", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$ID);
                break;
            case "doapprove":
                $ID = $this->input->post('ID_SOSIALISASI', TRUE);
                $get_sosialisasi = $this->Mglobal->get_data_by_id('t_lhkpn_sosialisasi','ID_SOSIALISASI',$ID,false,true);
                if($get_sosialisasi->STATUS==0){
                    $status = 1;
                }else{
                    $status = 0;
                }
               $user = array(
                    'STATUS' => $status,
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->where('ID_SOSIALISASI = '.$ID);
                $result = $this->db->update('t_lhkpn_sosialisasi',$user);
                ng::logActivity("Verifikasi sosialisasi untuk, bimtek = ".$get_sosialisasi->BIMTEK.", tanggal =  ".tgl_format(date('Y-m-d')).", id = ".$ID);
                break;
        }
        return $result;
    }

    /**
     * Fungsi untuk melakukan pengecekan apakah PN dapat di Non WL-kan atau tidak
     * berdasarkan Tahun WL (n dan n-1), ada atau tidak adanya laporan (tgl_lapor) dan STATUS sudah diumumkan atau belum.
     * Status diumumkan: STATUS = 4 atau 6
     * 
     * @param   int   $ID_PN
     * @param   int   $TAHUN_WL
     * @return  boolean
     */
    public function check_lhkpn_pn_non_wl($ID_PN, $TAHUN_WL)
    {
        $thn_sekarang = date('Y');
        if ($TAHUN_WL == $thn_sekarang) {
            $t_lhkpn_count = $this->db->query("SELECT ID_LHKPN FROM T_LHKPN WHERE ID_PN = $ID_PN AND IS_ACTIVE = 1 AND YEAR(tgl_lapor) = $TAHUN_WL AND STATUS NOT IN ('0', '4', '6', '7')")->num_rows();

            return $t_lhkpn_count > 0 ? FALSE : TRUE;
        }

        return FALSE;
    }

    public function get_last_regulasi(){
        $this->db->select("*");
        $this->db->from('t_lhkpn_regulasi');
        $this->db->order_by('ID_REGULASI', 'desc');
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * fungsi untuk melakukan pengecekan apakah wl tersebut dapat dicopy atau tidak
     * 
     * @param   int   $ID_PN
     * @param   int   $TAHUN_WL
     * @return  boolean
     */
    public function is_can_copy_wl($ID_PN, $TAHUN_WL)
    {
        //get max thn wl
        $max_thn_wl = $this->mglobal->get_data_all(
            't_pn_jabatan',
            NULL,
            ['ID_PN' => $ID_PN, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1'],
            'TAHUN_WL',
            NULL,
            ['TAHUN_WL', 'DESC']
        )[0]->TAHUN_WL;

        if ($max_thn_wl < date('Y') && $max_thn_wl == $TAHUN_WL) {
            //get lhkpn with condition is_active and status in (3,4,5,6)
            $this->db->where('ID_PN', $ID_PN);
            $this->db->where('IS_ACTIVE >', 0);
            $this->db->where_in('STATUS', ['3','4','5','6']);
            $this->db->where('YEAR(tgl_lapor)', $TAHUN_WL);
            $lhkpn = $this->db->get('t_lhkpn')->num_rows();
            
            if ($lhkpn > 0) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }
    
}
