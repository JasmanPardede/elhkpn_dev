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

    function __construct() {
        parent::__construct();
        $this->role = $this->session->userdata('ID_ROLE');
        $this->ins = $this->session->userdata('INST_SATKERKD');
        $this->uker = $this->session->userdata('UK_ID');
    }

    private function __select_fields() {

        $select_fields = "DISTINCT P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, INTS.INST_NAMA, P.NIK, PJ.ID, PJ.ID_STATUS_AKHIR_JABAT, PJ.ID_STATUS_AKHIR_JABAT STS_J,"
                . "P.NAMA, P.GELAR_DEPAN, P.GELAR_BELAKANG, U.ID_USER, PJ.SUB_UNIT_KERJA, "
                . "PJ.ALAMAT_KANTOR, PJ.DESKRIPSI_JABATAN, PJ.EMAIL_KANTOR, PJ.ESELON,IFNULL(PJ.IS_WL,0)AS IS_WL,PJ.TAHUN_WL,"
                . "p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR";
        $this->db->select($select_fields, FALSE);

        return;
    }

    private function __select_fields_offline() {

        $select_fields = "DISTINCT P.ID_PN,
            JAB.NAMA_JABATAN N_JAB, SUK.SUK_NAMA N_SUK, UK.UK_NAMA N_UK, INTS.INST_NAMA,
            P.NIK,
            P.NAMA,
            PJ.ID ID_JAB,
            PJ.UNIT_KERJA,
            PJ.SUB_UNIT_KERJA,
            U.ID_USER,
            PJ.ID_STATUS_AKHIR_JABAT,p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR";

        $this->db->select($select_fields, FALSE);

        return;
    }

    private function __set_additional_selectfieldswl() {
        $this->db->select('
            P.ID_PN,
            P.NIK,
            P.NAMA,
            INTS.INST_NAMA,
            PJ.ID ID_JAB,
            PJ.UNIT_KERJA,
            PJ.SUB_UNIT_KERJA,
            U.ID_USER,
            PJ.ID_STATUS_AKHIR_JABAT,p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR', false);
    }

    private function __select_fieldswl() {

        $select_fields = "DISTINCT P.ID_PN, JAB.NAMA_JABATAN, SUK.SUK_NAMA, "
                . "UK.UK_NAMA, P.NIK, "
                . "P.NAMA, IF(PJ.`ID_STATUS_AKHIR_JABAT`=0, 'Online','Offline')AS IS_WL, "
                . "PJ.TAHUN_WL,PJ.tgl_kirim, plh.Status as StatusKirim, "
                . "p.IS_FORMULIR_EFILLING, p.TGL_TERIMA_FORMULIR";

        $this->db->select($select_fields, FALSE);

        if ($this->set_additional_join_wl_aktif) {
            $this->__set_additional_selectfieldswl();
        }

        return;
    }

    private function __set_join() {

        $this->db->join('`T_USER` `U`', '`U`.`USERNAME` = `P`.`NIK`');

        $this->db->join('`t_pn_jabatan` `PJ`', '`P`.`ID_PN` = `PJ`.`ID_PN`');

        $where_join_t_pn_jabatan = "`PJ`.`ID_STATUS_AKHIR_JABAT` IN(0, 10, 11, 15) AND "
                . "`PJ`.`IS_DELETED` = '0' AND "
                . "`PJ`.`IS_CALON` = '0' AND "
                . "`PJ`.`IS_ACTIVE` = '1' AND "
                . "`PJ`.`IS_CURRENT` = '1' ";

        $this->db->where($where_join_t_pn_jabatan);

        $this->db->join('`M_JABATAN` `JAB`', '`PJ`.`ID_JABATAN` = `JAB`.`ID_JABATAN`', 'left');
        $this->db->join('`m_sub_unit_kerja` `SUK`', '`SUK`.`SUK_ID` = `JAB`.`SUK_ID`', 'left');
        $this->db->join('`m_unit_kerja` `UK`', '`UK`.`UK_ID` = `JAB`.`UK_ID`', 'left');
        $this->db->join('`m_inst_satker` `INTS`', '`INTS`.`INST_SATKERKD` = `UK`.`UK_LEMBAGA_ID`', 'left');
    }

    private function __set_join_offline() {

        $this->db->join('T_USER U', 'U.USERNAME = P.NIK', 'LEFT');
        $this->db->join('T_PN_JABATAN PJ', 'P.ID_PN = PJ.ID_PN', 'LEFT');
        $this->db->join('M_JABATAN JAB', 'PJ.ID_JABATAN = JAB.ID_JABATAN', 'left');
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
        $this->db->join('m_inst_satker INTS', 'INTS.INST_SATKERKD = UK.UK_LEMBAGA_ID', 'left');
        $this->db->join('T_USER U', 'U.USERNAME = P.NIK', 'left');
        $this->db->where("PJ.is_wl = '1'");
    }

    private function __set_joinwl() {

        $left = 'left';
        if (!$this->set_additional_join_wl_aktif) {
            $left = NULL;
        }

        $this->db->join('`t_pn_jabatan` `PJ`', '`P`.`ID_PN` = `PJ`.`ID_PN`', $left);

        $where_join_t_pn_jabatan = ""
                . "`PJ`.`IS_DELETED` = '0' AND "
                . "`PJ`.`IS_CALON` = '0' AND "
                . "`PJ`.`IS_ACTIVE` = '1' AND "
                . "`P`.`IS_ACTIVE` = '1' AND "
                . "`PJ`.`IS_CURRENT` = '1'";
        //. " PJ.IS_WL='1'";

        $this->db->where($where_join_t_pn_jabatan);
        $this->db->join('`M_JABATAN` `JAB`', '`PJ`.`ID_JABATAN` = `JAB`.`ID_JABATAN`', 'left');
        $this->db->join('`m_sub_unit_kerja` `SUK`', '`SUK`.`SUK_ID` = `JAB`.`SUK_ID`', 'left');
        $this->db->join('`m_unit_kerja` `UK`', '`UK`.`UK_ID` = `JAB`.`UK_ID`', 'left');
        $this->db->join('`t_lhkpn` `plh`', '`plh`.`ID_PN` = `P`.`ID_PN`', 'left');

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
            $condition = " AND (PJ.UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "PJ.SUB_UNIT_KERJA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "P.NAMA LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "P.NIK LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "P.EMAIL LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "P.NO_HP LIKE CONCAT('%', '" . $cari . "' , '%') OR "
                    . "PJ.DESKRIPSI_JABATAN LIKE CONCAT('%', '" . $cari . "' , '%') )";

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
        if ($this->role != 1 && $this->role != 2) {
            $sql_where .= " AND pj.LEMBAGA = $this->ins";
            if ($cari_advance) {
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   pj.LEMBAGA = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
                $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
                $condition_cari_ = $cari_advance["TEXT"] != '' ? "   (p.NAMA like '%" . $cari_advance["TEXT"] . "%' OR p.NIK like '%" . $cari_advance["TEXT"] . "%') " : " 1";
                $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : "1";
                $sql_where .= " AND " . $condition_tahun_wl . " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
                //$sql_where .= " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
            } else {
                $sql_where .= "AND pj.lembaga = 1081";
                $sql_where .= " $uk_where";
            }
        }
        //adding by iqbal
        else if ($this->role == 1 || $this->role == 2) {


//			if($cari_advance['INSTANSI'] != "" || $cari_advance['UNIT_KERJA'] != ""){
            if ($cari_advance) {
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   pj.LEMBAGA = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
                $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "' " : " 1";
                $condition_cari_ = $cari_advance["TEXT"] != '' ? "   (p.NAMA like '%" . $cari_advance["TEXT"] . "%' OR p.NIK like '%" . $cari_advance["TEXT"] . "%') " : " 1";
                $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : "1";
                $sql_where .= " AND " . $condition_tahun_wl . " AND " . $condition_instansi . " and " . $condition_uk . " and " . $condition_cari_ . " $uk_where     ";
            } else {
                $sql_where .= "AND pj.lembaga = 1081";
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
        if ($this->role != 1 && $this->role != 2) {
            $sql_where .= " AND PJ.LEMBAGA = $this->ins";
//			if($cari_advance['UNIT_KERJA'] != ""){
            if ($cari_advance) {
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   pj.LEMBAGA = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
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
                if ($this->role == 1 && $this->role == 2) {
                    $sql_where .= "AND PJ.lembaga = 1081";
                }
                $sql_where .= " $uk_where";
            }
        } else if ($this->role == 1 || $this->role == 2) {


            //if($cari_advance['INSTANSI'] != "" || $cari_advance['UNIT_KERJA'] != ""){
            if ($cari_advance) {
                $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   pj.LEMBAGA = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
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
                $sql_where .= "AND pj.lembaga = 1081";
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
        $result = $this->db->query("select min(pj.tahun_wl) as min_tahun from t_pn p join t_pn_jabatan pj on p.id_pn = pj.id_pn");
        if ($result) {
            $record = $result->row();
            if ($record) {
                return $record->min_tahun;
            }
        }
        return FALSE;
    }

    protected function load_page_PL_AKTIF_cari_advance($uk_where) {
        $cari_advance = $this->input->get('CARI');

        $condition_is_wl = " 1 ";
        $condition_tahun_wl = " 1 ";
        $condition_instansi = " 1 ";
        $condition_uk = " 1 ";

        $sql_where = "  ";

        if ($cari_advance) {
            $condition_is_wl = $cari_advance["IS_WL"] == '' ? "   1   " :
                    $condition_is_wl = $cari_advance["IS_WL"] != '1' ? "   PJ.tgl_kirim is null   " :
                    $condition_is_wl = $cari_advance["IS_WL"] != '0' ? "   PJ.tgl_kirim is not null   " :
                    $condition_is_wl = $cari_advance["IS_WL"] != '2' ? "   1   " :
                    $condition_is_wl;
            $condition_tahun_wl = $cari_advance["TAHUN_WL"] != '' ? "   PJ.TAHUN_WL='" . $cari_advance["TAHUN_WL"] . "'  " : $condition_tahun_wl;
            $condition_instansi = $cari_advance["INSTANSI"] != '' ? "   pj.LEMBAGA = '" . $cari_advance["INSTANSI"] . "'   " : " 1 ";
            $condition_uk = $cari_advance["UNIT_KERJA"] != '' ? "   uk.UK_ID ='" . $cari_advance["UNIT_KERJA"] . "'   " : $condition_uk;
        } else {
            if ($this->role == 1 || $this->role == 2) {
                if ($condition_instansi == " 1 ") {
                    $condition_instansi = " pj.LEMBAGA = 1081 ";
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

        $sql_where = "";

        if ($aktifwl == 1) {
            $sql_where .= "  and is_wl= 1  AND `PJ`.`ID_STATUS_AKHIR_JABAT` IN(0,5)";
        }

        if ($this->role != 1 && $this->role != 2) {
            if ($aktifwl == 1) {
                $sql_where .= "  AND pj.LEMBAGA = $this->ins   ";
            } else {
                $sql_where .= " and (is_wl= 0  OR pj.`is_wl` IS NULL) AND pj.LEMBAGA = $this->ins  AND PJ.ID_STATUS_AKHIR_JABAT IN(0,5) ";
            }
        } else if (($this->role == 1 || $this->role == 2) AND ( $this->ins == "" OR $this->ins == NULL)) {
            //adding by iqbal jika login sebagai admin_kpk default yang tampil pegawai KPK
            if ($aktifwl != 1) {
                $sql_where .= " and is_wl= 0 AND `PJ`.`ID_STATUS_AKHIR_JABAT` IN(0,5) $uk_where   ";
            }
        }
        return $sql_where;
    }

    protected function load_page_pl_aktif_condition($cari = NULL, $aktifwl = 1) {
        $sql_where = " 1 " . $this->__cari($cari);

        $uk_where = $this->uker && !is_null($this->uker) ? " AND uk.UK_ID = '" . $this->uker . "' " : "  ";

        $sql_where .= $this->load_page_PL_AKTIF_cari_advance($uk_where);
//        echo $sql_where;exit;

        $sql_where .= $this->load_page_pl_aktif_condition_by_role($aktifwl, $uk_where);
//        echo $sql_where;exit;


        $is_aktif_tlhkpn = " AND plh.IS_ACTIVE = '1' ";


        $sql_where .= "  $uk_where ";
        $sql_where .= " AND IF (plh.tgl_lapor =
                        (SELECT
                          MAX(tgl_lapor)
                        FROM
                          t_lhkpn
                        WHERE id_pn = `plh`.`id_pn` " . $is_aktif_tlhkpn . ") IS NULL, 1, plh.tgl_lapor =
                        (SELECT
                          MAX(tgl_lapor)
                        FROM
                          t_lhkpn
                        WHERE id_pn = `plh`.`id_pn` " . $is_aktif_tlhkpn . ")) AND (plh.IS_ACTIVE = '1' OR "
                    . "(SELECT
                          MAX(tgl_lapor)
                        FROM
                          t_lhkpn
                        WHERE id_pn = `plh`.`id_pn` " . $is_aktif_tlhkpn . ") IS NULL)";

//        echo $sql_where;exit;
        return $sql_where;
    }

    public function load_page_PL_AKTIF($instansi, $offset = 0, $cari = NULL, $rowperpage = 10, $page_mode = '', $aktifwl = 1) {
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
        $this->db->select("count(DISTINCT P.ID_PN) as cnt");
        $this->__set_joinwl();

        $sql_where = $this->load_page_pl_aktif_condition($cari, $aktifwl);

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

        $this->__select_fieldswl();

        $this->__set_joinwl();
        $this->db->order_by('P.NAMA');
        $query = $this->db->get_where('t_pn P', $sql_where, $rowperpage, $offset);


        //exit;
        if ($query) {
            $result = $query->result();
        }
        $query->free_result();
//        display($this->db->last_query());
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

        return (object) array("total_rows" => $total_rows, "result" => $result);
        exit;
    }

}
