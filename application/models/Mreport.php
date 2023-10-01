<?php

class Mreport extends CI_Model {

    // private $table = 'T_USER';
    // private $table_role = 'T_USER_ROLE';
    // private $user_pokja_role = 3;

    function __construct() {
        parent::__construct();
    }

    function report_per_bidang($tahun=NULL) {     
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
            $satker = "mis.INST_SATKERKD";
        }else{
            $satker = "mis.INST_NAMA";
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;

        $sql = "SELECT BDG_ID, BDG_NAMA,

                (SELECT count(*) FROM ".$tabel." AS t_pn_j
                LEFT JOIN m_inst_satker AS mis
                ON t_pn_j.LEMBAGA = ".$satker."
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                AND mis.INST_BDG_ID=mb.BDG_ID ".$where.") as wajib_lapor_lhkpn,    

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as mis
                ON tlj.LEMBAGA = mis.INST_SATKERKD
                WHERE tl.status IN ('3','4') AND tlj.IS_PRIMARY = 1 AND mis.INST_BDG_ID = mb.BDG_ID ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as mis
                ON tlj.LEMBAGA = mis.INST_SATKERKD
                WHERE tl.status IN ('5','6') AND tlj.IS_PRIMARY = 1 AND mis.INST_BDG_ID = mb.BDG_ID ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as mis
                ON tlj.LEMBAGA = mis.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND tlj.IS_PRIMARY = 1 AND mis.INST_BDG_ID = mb.BDG_ID ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as mis
                ON tlj.LEMBAGA = mis.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND tlj.IS_PRIMARY = 1 AND mis.INST_BDG_ID = mb.BDG_ID ".$filter.") AS belum_terverif_b

                from m_bidang as mb
                ORDER BY mb.BDG_ID ASC";      

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function report_eksekutif_pusat($tahun=NULL) {
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
            $satker = "m_is.INST_SATKERKD";
        }else{
            $satker = "m_is.INST_NAMA";
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;

        $sql = "SELECT mis.INST_SATKERKD,  mis.INST_NAMA,
                (SELECT count(*) FROM ".$tabel." AS t_pn_j
                LEFT JOIN m_inst_satker AS m_is
                ON t_pn_j.LEMBAGA = ".$satker."
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$where.") as wajib_lapor_lhkpn,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.status IN ('3','4') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.status IN ('5','6') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_b

                FROM m_inst_satker AS mis WHERE mis.INST_LEVEL = 1 AND mis.INST_BDG_ID = 1";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function report_eksekutif_daerah($tahun=NULL, $prov_id=NULL) {
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
            $satker = "m_is.INST_SATKERKD";
        }else{
            $satker = "m_is.INST_NAMA";
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        if($prov_id != NULL){
            $prov = " AND mi_sa.ID_PROV =".$prov_id;
        }else{
            $prov = "";
        }

        $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;

        $sql = "SELECT mis.INST_NAMA,
                (SELECT count(*) FROM ".$tabel." AS t_pn_j
                LEFT JOIN m_inst_satker AS m_is
                ON t_pn_j.LEMBAGA = ".$satker."
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0 ".$prov."
                AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$where.") as wajib_lapor_lhkpn,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.status IN ('3','4') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.status IN ('5','6') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_b

                FROM m_inst_satker as mis WHERE mis.INST_LEVEL=2 AND mis.INST_BDG_ID=1 
                ORDER BY mis.INST_SATKERKD";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function report_legislatif($tahun=NULL, $prov_id=NULL) {
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
            $satker = "m_is.INST_SATKERKD";
        }else{
            $satker = "m_is.INST_NAMA";
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        if($prov_id != NULL){
            $prov = " AND mi_sa.ID_PROV =".$prov_id;
        }else{
            $prov = "";
        }

        $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;

        $sql = "SELECT mis.INST_NAMA,
                (SELECT count(*) FROM ".$tabel." AS t_pn_j
                LEFT JOIN m_inst_satker AS m_is
                ON t_pn_j.LEMBAGA = ".$satker." 
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$prov .$where.") as wajib_lapor_lhkpn,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.status IN ('3','4') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.status IN ('5','6') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_b

                FROM m_inst_satker as mis WHERE mis.INST_BDG_ID=2 
                ORDER BY mis.INST_SATKERKD";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function report_yudikatif($tahun=NULL) {
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
            $satker = "m_is.INST_SATKERKD";
        }else{
            $satker = "m_is.INST_NAMA";
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;

        $sql = "SELECT mis.INST_NAMA, 
                (SELECT count(*) FROM ".$tabel." AS t_pn_j
                LEFT JOIN m_inst_satker AS m_is
                ON t_pn_j.LEMBAGA = ".$satker."
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$where.") as wajib_lapor_lhkpn,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.status IN ('3','4') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.status IN ('5','6') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_b

                FROM m_inst_satker AS mis WHERE mis.INST_BDG_ID = 3 ORDER BY mis.INST_SATKERKD";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function report_bumn($tahun=NULL) {
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
            $satker = "m_is.INST_SATKERKD";
        }else{
            $satker = "m_is.INST_NAMA";
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;

        $sql = "SELECT mis.INST_NAMA,
                (SELECT count(*) FROM ".$tabel." AS t_pn_j
                LEFT JOIN m_inst_satker AS m_is
                ON t_pn_j.LEMBAGA = ".$satker."
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$where.") as wajib_lapor_lhkpn,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.status IN ('3','4') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.status IN ('5','6') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND tlj.IS_PRIMARY = 1 AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_b

                FROM m_inst_satker as mis WHERE mis.INST_LEVEL=1 AND mis.INST_BDG_ID=4 ORDER BY mis.INST_SATKERKD";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function report_bumd($tahun=NULLl, $prov_id=NULL) {
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
            $satker = "m_is.INST_SATKERKD";
        }else{
            $satker = "m_is.INST_NAMA";
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        if($prov_id != NULL){
            $prov = " AND mi_sa.ID_PROV =".$prov_id;
        }else{
            $prov = "";
        }

        $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;

        $sql = "SELECT mis.INST_NAMA,
                (SELECT count(*) FROM ".$tabel." AS t_pn_j
                LEFT JOIN m_inst_satker AS m_is
                ON t_pn_j.LEMBAGA = ".$satker."
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$where.") as wajib_lapor_lhkpn,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.status IN ('3','4') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.status IN ('5','6') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_inst_satker as m_is
                ON tlj.LEMBAGA = m_is.INST_SATKERKD
                LEFT JOIN m_inst_satker_area AS mi_sa
                ON m_is.INST_SATKERKD = mi_sa.INST_SATKERKD
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND tlj.IS_PRIMARY = 1 ".$prov." AND m_is.INST_SATKERKD=mis.INST_SATKERKD ".$filter.") AS belum_terverif_b

                FROM m_inst_satker as mis WHERE mis.INST_LEVEL=2 AND mis.INST_BDG_ID=4 ORDER BY mis.INST_SATKERKD";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function report_unit_kerja_1($lembaga_id=NULL, $tahun=NULL) {
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
        }else{
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;

        $sql = "SELECT UK_NAMA,
                (SELECT count(*) FROM ".$tabel." AS t_pn_j
                LEFT JOIN m_unit_kerja AS m_uk
                ON t_pn_j.UNIT_KERJA = m_uk.UK_NAMA
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                AND m_uk.UK_ID=muk.UK_ID ".$where.") as wajib_lapor_lhkpn,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_unit_kerja as m_uk
                ON tlj.UNIT_KERJA = m_uk.UK_ID
                WHERE tl.status IN ('3','4') AND m_uk.UK_ID=muk.UK_ID ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_unit_kerja as m_uk
                ON tlj.UNIT_KERJA = m_uk.UK_ID
                WHERE tl.status IN ('5','6') AND m_uk.UK_ID=muk.UK_ID ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_unit_kerja as m_uk
                ON tlj.UNIT_KERJA = m_uk.UK_ID
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND m_uk.UK_ID=muk.UK_ID ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_unit_kerja as m_uk
                ON tlj.UNIT_KERJA = m_uk.UK_ID
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND m_uk.UK_ID=muk.UK_ID ".$filter.") AS belum_terverif_b

                FROM m_unit_kerja AS muk";

            if($lembaga_id != NULL){
                $sql .= " WHERE muk.UK_LEMBAGA_ID = ".$lembaga_id;
            }

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function report_unit_kerja_2($uk_id=NULL,$tahun=NULL) {
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
        }else{
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;

        $sql = "SELECT msuk.SUK_ID, msuk.UK_ID, msuk.SUK_NAMA,
                (SELECT count(*) FROM ".$tabel." AS t_pn_j
                LEFT JOIN m_sub_unit_kerja AS ms_uk
                ON t_pn_j.SUB_UNIT_KERJA = ms_uk.SUK_NAMA
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                AND ms_uk.SUK_ID = msuk.SUK_ID ".$where.") as wajib_lapor_lhkpn,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_sub_unit_kerja as ms_uk
                ON tlj.SUB_UNIT_KERJA = ms_uk.SUK_ID
                WHERE tl.status IN ('3','4') AND ms_uk.SUK_ID=msuk.SUK_ID ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_sub_unit_kerja as ms_uk
                ON tlj.SUB_UNIT_KERJA = ms_uk.SUK_ID
                WHERE tl.status IN ('5','6') AND ms_uk.SUK_ID=msuk.SUK_ID ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_sub_unit_kerja as ms_uk
                ON tlj.SUB_UNIT_KERJA = ms_uk.SUK_ID
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND ms_uk.SUK_ID=msuk.SUK_ID ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_sub_unit_kerja as ms_uk
                ON tlj.SUB_UNIT_KERJA = ms_uk.SUK_ID
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND ms_uk.SUK_ID=msuk.SUK_ID ".$filter.") AS belum_terverif_b

                FROM m_sub_unit_kerja as msuk";
            if($uk_id != NULL){
                $sql .= " WHERE msuk.UK_ID = ".$uk_id;
            }

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function report_eselon($tahun=NULL, $lembaga = NULL){
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
        }else{
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }

        if($lembaga != NULL){
            $filter = " AND tlj.LEMBAGA =".$lembaga;            
        }else{
            $filter = "";
        }

        $sql = "SELECT me.ESELON, me.KODE_ESELON,
                (SELECT count(*) FROM
                ".$tabel." as t_pn_j
                LEFT JOIN m_unit_kerja as muk
                ON t_pn_j.UNIT_KERJA = muk.UK_NAMA
                LEFT JOIN m_eselon AS m_es
                ON t_pn_j.ESELON = m_es.KODE_ESELON
                WHERE 
                t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                AND m_es.ID_ESELON = me.ID_ESELON ".$where.") as wajib_lapor_lhkpn,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_eselon as m_e
                ON tlj.ESELON = m_e.ID_ESELON
                WHERE tl.status IN ('3','4') AND m_e.ID_ESELON=me.ID_ESELON ".$filter.") AS lapor_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_eselon as m_e
                ON tlj.ESELON = m_e.ID_ESELON
                WHERE tl.status IN ('5','6') AND m_e.ID_ESELON=me.ID_ESELON ".$filter.") AS lapor_tidak_lengkap,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_eselon as m_e
                ON tlj.ESELON = m_e.ID_ESELON
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2') AND m_e.ID_ESELON=me.ID_ESELON ".$filter.") AS belum_terverif_a,

                (SELECT count(*) from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_eselon as m_e
                ON tlj.ESELON = m_e.ID_ESELON
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2') AND m_e.ID_ESELON=me.ID_ESELON ".$filter.") AS belum_terverif_b

                from m_eselon as me 
                GROUP BY me.ESELON";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }   
    }


    function report_belum_lapor($tahun=NULL,$lembaga=NULL,$unitkerja=NULL){
        $year_now = date('Y');
        if($tahun == $year_now){
            $tabel = " t_pn_jabatan";
            $where = " AND t_pn_j.IS_ACTIVE = 1 AND t_pn_j.IS_DELETED = 0 ";
            $satker = "mis.INST_SATKERKD";
        }else{
            $satker = "mis.INST_NAMA";
            $tabel = " t_pn_kepatuhan_tahun";
            $where = " AND t_pn_j.tahun =". $tahun;
        }


        if($lembaga != NULL){
            $where_lemb = " AND muk.UK_LEMBAGA_ID =" .$lembaga;
        }else{
            $where_lemb = "";
        }

        if($unitkerja != NULL){
            $join = " LEFT JOIN m_unit_kerja as muk ON t_pn_j.UNIT_KERJA = muk.UK_NAMA ";
            $where_uk = " AND muk.UK_LEMBAGA_ID = t_pn_j.LEMBAGA AND muk.UK_ID =" .$unitkerja;
        }else{
            $join = "";
            $where_uk = "";
        }


        $sql = "SELECT tp.NIK, tp.NAMA, mis.INST_NAMA, t_pn_j.UNIT_KERJA, t_pn_j.SUB_UNIT_KERJA, t_pn_j.DESKRIPSI_JABATAN, tp.NHK FROM
                ".$tabel." AS t_pn_j
                LEFT JOIN t_pn AS tp
                ON t_pn_j.ID_PN = tp.ID_PN
                LEFT JOIN m_inst_satker AS mis
                ON t_pn_j.LEMBAGA = ".$satker.$join. "
                WHERE t_pn_j.IS_CURRENT = 1
                AND t_pn_j.ID_STATUS_AKHIR_JABAT = 0
                ".$where.$where_uk."
                AND tp.ID_PN NOT IN (SELECT id_pn from t_lhkpn as tl WHERE DATE_FORMAT(tl.TGL_LAPOR, '%Y') = ".$tahun." GROUP BY tl.ID_PN)";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }   
    }


    function report_sudah_lapor($tahun=NULL){
        if($tahun != NULL){
            $where = " AND t_pn_j.TAHUN =" .$tahun;
        }else{
            $where = "";
        }

        $sql = "SELECT t_pn.ID_PN, t_pn_j.tahun, tl.ID_LHKPN, t_pn.NIK, t_pn.NAMA, t_pn_j.DESKRIPSI_JABATAN, t_pn_j.UNIT_KERJA, t_pn_j.SUB_UNIT_KERJA, msl.STATUS,
                t_pn.NHK, tlsh.DATE_INSERT from t_pn
                LEFT JOIN t_pn_kepatuhan_tahun AS t_pn_j
                ON t_pn.ID_PN = t_pn_j.ID_PN
                LEFT JOIN t_lhkpn as tl
                ON t_pn.ID_PN = tl.ID_PN
                LEFT JOIN t_lhkpn_status_history as tlsh
                ON tl.ID_LHKPN = tlsh.ID_LHKPN
                LEFT JOIN m_status_lhkpn as msl
                ON msl.ID_STATUS=tlsh.ID_STATUS

                WHERE t_pn_j.IS_CURRENT=1     
                AND t_pn_j.ID_STATUS_AKHIR_JABAT=0
                AND t_pn.ID_PN IN (SELECT id_pn from t_lhkpn GROUP BY ID_PN) ".$where."
                GROUP BY t_pn_j.ID_PN";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }   
    }

    function get_nik($nik){
        $sql = "SELECT * FROM t_pn WHERE NIK = '".$nik."'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }   
    }

    function get_history($id_pn){
        $sql = "SELECT * FROM t_lhkpn AS tl
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN t_pn AS tp
                ON tl.ID_PN = tp.ID_PN 
                LEFT JOIN m_unit_kerja as muk
                ON tlj.UNIT_KERJA = muk.UK_ID
                LEFT JOIN m_sub_unit_kerja as msuk
                ON tlj.SUB_UNIT_KERJA = msuk.SUK_ID
                LEFT JOIN m_inst_satker as mis
                ON tlj.LEMBAGA = mis.INST_SATKERKD    
                WHERE tl.ID_PN = ".$id_pn;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function report_seluruhnya($tahun=NULL){
        if($tahun != NULL){
            $where = " AND t_pn_j.TAHUN =" .$tahun;
        }else{
            $where = "";
        }

        $sql = "SELECT * FROM t_pn
                LEFT JOIN t_pn_kepatuhan_tahun AS t_pn_j
                ON t_pn.ID_PN = t_pn_j.ID_PN
                LEFT JOIN t_lhkpn as tl
                ON t_pn.ID_PN = tl.ID_PN
                WHERE t_pn_j.IS_CURRENT=1     
                AND t_pn_j.ID_STATUS_AKHIR_JABAT=0
                ".$where."
                GROUP BY t_pn_j.ID_PN";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        } 
    }


    function report_sudah_lapor_a($tahun=NULL, $lembaga=NULL, $uk_id=NULL){
        if($tahun != NULL){
            $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;
        }else{
            $filter = "";
        }

        if($lembaga != NULL){
            $lemb = " AND tlj.LEMBAGA = ".$lembaga;
        }else{
            $lemb = "";
        }

        if($uk_id != NULL){
            $uk = " AND m_uk.UK_ID =" .$uk_id;
        }else{
            $uk = "";
        }

        $sql = "SELECT tp.NIK, tp.NAMA, mis.INST_NAMA, m_uk.UK_NAMA, msuk.SUK_NAMA, tlj.DESKRIPSI_JABATAN, tp.NHK, tl.TGL_LAPOR, tl.`STATUS` from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_unit_kerja as m_uk
                ON tlj.UNIT_KERJA = m_uk.UK_ID
                LEFT JOIN m_sub_unit_kerja as msuk
                ON tlj.SUB_UNIT_KERJA = msuk.SUK_ID
                LEFT JOIN m_inst_satker as mis
                ON tlj.LEMBAGA = mis.INST_SATKERKD
                LEFT JOIN t_pn as tp 
                ON tl.ID_PN = tp.ID_PN
                WHERE tl.status IN ('3','4','5','6') ".$filter .$lemb . $uk;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // belum terverif via 0
    function report_sudah_lapor_b($tahun=NULL, $lembaga=NULL, $uk_id=NULL){
        if($tahun != NULL){
            $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;
        }else{
            $filter = "";
        }

        if($lembaga != NULL){
            $lemb = " AND tlj.LEMBAGA = ".$lembaga;
        }else{
            $lemb = "";
        }

        if($uk_id != NULL){
            $uk = " AND m_uk.UK_ID =" .$uk_id;
        }else{
            $uk = "";
        }

        $sql = "SELECT tp.NIK, tp.NAMA, mis.INST_NAMA, m_uk.UK_NAMA, msuk.SUK_NAMA, tlj.DESKRIPSI_JABATAN, tp.NHK, tl.TGL_LAPOR, tl.`STATUS` from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_unit_kerja as m_uk
                ON tlj.UNIT_KERJA = m_uk.UK_ID
                LEFT JOIN m_sub_unit_kerja as msuk
                ON tlj.SUB_UNIT_KERJA = msuk.SUK_ID
                LEFT JOIN m_inst_satker as mis
                ON tlj.LEMBAGA = mis.INST_SATKERKD
                LEFT JOIN t_pn as tp 
                ON tl.ID_PN = tp.ID_PN
                WHERE tl.ENTRY_VIA = '0' AND tl.status IN ('1','2')".$filter.$lemb . $uk;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }           
    }

    // belum terverif via 1
    function report_sudah_lapor_c($tahun=NULL, $lembaga=NULL, $uk_id=NULL){
        if($tahun != NULL){
            $filter = " AND DATE_FORMAT(tl.TGL_LAPOR, ".'"%Y"'.") = ".$tahun;
        }else{
            $filter = "";
        }

        if($lembaga != NULL){
            $lemb = " AND tlj.LEMBAGA = ".$lembaga;
        }else{
            $lemb = "";
        }

        if($uk_id != NULL){
            $uk = " AND m_uk.UK_ID =" .$uk_id;
        }else{
            $uk = "";
        }

        $sql = "SELECT tp.NIK, tp.NAMA, mis.INST_NAMA, m_uk.UK_NAMA, msuk.SUK_NAMA, tlj.DESKRIPSI_JABATAN, tp.NHK, tl.TGL_LAPOR, tl.`STATUS` from t_lhkpn as tl 
                LEFT JOIN t_lhkpn_jabatan as tlj
                ON tl.ID_LHKPN = tlj.ID_LHKPN
                LEFT JOIN m_unit_kerja as m_uk
                ON tlj.UNIT_KERJA = m_uk.UK_ID
                LEFT JOIN m_sub_unit_kerja as msuk
                ON tlj.SUB_UNIT_KERJA = msuk.SUK_ID
                LEFT JOIN m_inst_satker as mis
                ON tlj.LEMBAGA = mis.INST_SATKERKD
                LEFT JOIN t_pn as tp 
                ON tl.ID_PN = tp.ID_PN
                WHERE tl.ENTRY_VIA = '1' AND tl.status IN ('0','1','2')". $filter . $lemb. $uk;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}

?>